<?php
/*******************************************************************************
*	PremierZ - The first product of ZENK
*
*	one line to give the program's name and an idea of what it does.
*
*	Copyright (C) 2012 ZENK Co., Ltd
*
*	This program is free software; you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
*
*
*	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
*	You should have received a copy of the GNU Affero General Public License along with this program; If not, see <http://www.gnu.org/licenses/>.
*******************************************************************************/

/******************************************************************************
 ファイル名：c_useCompanyDel.php
 処理概要  ：利用会社削除処理
 POST受領値：
             nm_token_code				トークン(必須)
             nm_define_id				DATA_ID(必須)
             nm_proc_mode				実行モード(任意)(無指定:削除、CNT:カウントのみ)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//return;
	//print "<br>";
	//session_start();
	//print_r($_SESSION);
	//return;
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_txt_rts			= "\n";									// テキストの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_get_key2			= "USER_ID";							// 認証で取得するキー項目2
	$l_user_id			= "";
	$l_data_id			= "";
	$l_proc_mode		= "";									// 起動モード
	$l_record_item		= "data_record";						// POST引数の連勝配列の中でデータを格納している項目
	$l_errflg			= 0;									// エラーフラグ
	$l_errmess			= "";									// エラーメッセージ
	
// ==================================
// 例外定義
// ==================================
	function my_exception_useCompDel(Exception $e){
		echo "例外が発生しました。".$l_txt_rts;
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_useCompDel');
	
// ==================================
// セッション確認
// ==================================
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception("不正なアクセスです。");
	}
	
	require_once('../maintenance/c_sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		throw new Exception("不正なアクセスです。");
	}
	if($l_post_token != $l_sess_token){
		throw new Exception("不正なアクセスです。");
	}
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem($l_get_key2);
	if(is_null($l_user_id)){
		throw new Exception("不正なアクセスです。");
	}
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
// ==================================
// POST項目チェック
// ==================================
	// 指定されたDATA_IDを変数にセット
	$l_data_id = $_POST["nm_define_id"];
	
	//--- 既存データを読み込む ---
	require_once('../mdl/m_use_company.php');
	$lc_mucdel = new m_use_company();
	
	//--- 既存データの存在チェック ---
	$lr_dtlrec = "";
	// DATA_IDをキーとして既存レコードを取得する
	$lr_where = array("DATA_ID = ".$l_data_id);
	$lc_mucdel->setWhereArray($lr_where);
	// クエリ
	$lc_mucdel->queryDBRecord();
	// レコード取得
	$lr_dtlrec = $lc_mucdel->getViewRecord();
	if(count($lr_dtlrec) < 1){
		print "データは既に削除されている可能性があります。\n最新情報に更新して下さい。";
		return;
	}
	
	//--- テーブルの一覧を取得 ---
	$lr_schema_tables = $lc_mucdel->getSchemaTables(SCHEMA_NAME);
	//var_dump($lr_schema_tables);
	if(count($lr_schema_tables) > 0){
		$lr_tables = array();
		$lr_tables_name = array();
		$l_reccnt = 0;
		foreach($lr_schema_tables as $l_key => $lr_value){
			// 一部テーブルは除外する
			if (
				$lr_value["TABLE_NAME"] != "LOGIN_LOG"
				)
			{
				$l_reccnt++;
				$lr_tables[$l_reccnt] = $lr_value["TABLE_NAME"];
				$lr_comment = "";
				$lr_comment = explode(';',$lr_value["TABLE_COMMENT"]);
				$lr_tables_name[$l_reccnt] = $lr_comment[0];
			}
		}
	}else{
		print "テーブル一覧が取得できませんでした。";
		return;
	}
	
	// 起動モード取得
	if($_POST["nm_proc_mode"] == "CNT"){
		$l_proc_mode = "CNT";
	}else{
		$l_proc_mode = "DEL";
	}
	
// ==================================
// 削除処理
// ==================================
	// 取得したテーブルからPOSTされたDATA_IDのデータを削除する
	foreach($lr_tables as $l_key => $l_value){
		$l_datacnt = 0;
		
		// 該当テーブル内のデータカウントを取得
		$l_datacnt = $lc_mucdel->countData($l_value, $l_data_id);
		//print $l_value;
		if($l_proc_mode == "CNT"){
			// カウントモードの場合は表示
			print $lr_tables_name[$l_key]."(".$l_value.") : ".$l_datacnt."\n";
		}
		
		// 削除モードでカウントが0より大きい場合は削除処理を行う
		if($l_proc_mode == "DEL" && $l_datacnt > 0){
			$lc_mucdel->deleteData($l_value, $l_data_id);
		}
	}
	
	if($l_proc_mode == "DEL"){
		// 削除モードの場合は全て正常終了した場合0を返す
		print 0;
	}
?>
