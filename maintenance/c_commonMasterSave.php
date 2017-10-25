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
 ファイル名：commonMasterSave.php
 処理概要  ：共通マスタ保存処理
 POST受領値：
             nm_token_code              トークン(必須)
             各データ項目
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "<br>";
	//session_start();
	//print_r($_SESSION);
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
	
	$l_mode				= "";									// 起動モード(INSorUPD)
	$l_errflg			= 0;									// エラーフラグ
	$l_errmess			= "";									// エラーメッセージ
	
// ==================================
// 例外定義
// ==================================
	function my_exception_commonMasterSave(Exception $e){
		echo "例外が発生しました。".$l_txt_rts;
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_commonMasterSave');
	
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
	// 新規か更新かの判別
	// CODE_IDがNULLの場合は新規とみなす
	if($_POST["CODE_ID"] == ''){
		$l_mode = "INS";
	}else{
		$l_mode = "UPD";
	}
	
	// チェック処理
	require_once('../maintenance/c_commonCheck.php');
	$lc_cmc = new c_commonCheck();
	
	$lr_cmc_result = $lc_cmc->checkValue($_POST, 'COMMON_MASTER', $l_mode);
	if($lr_cmc_result['STATUS'] > 0){
		print $lr_cmc_result['MESSAGE'];
		return;
	}
// ==================================
// 保存処理
// ==================================
	require_once('../mdl/m_common_master.php');
	$lc_mcm = new m_common_master();
	if($l_mode == "INS"){
		// 新規登録
		$l_retcode = $lc_mcm->insertRecord($l_user_id, $_POST);
	}else{
		// 更新
		$l_retcode = $lc_mcm->updateRecord($l_user_id, $_POST);
	}
	
	if($l_retcode === RETURN_NOMAL){
		print "保存が完了しました";
	}else{
		print "保存に失敗しました";
	}
?>
