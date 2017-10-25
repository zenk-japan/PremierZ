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
 ファイル名：c_loginCheck.php
 処理概要  ：ログインチェック
 POST受領値：
             nm_username                ユーザー名(必須)
             nm_password                パスワード(必須)
 戻り値    ：
             $l_token					認証OKの場合の表示値(トークン)
             "NG"						認証NGの場合の表示値
******************************************************************************/
	$l_dir_prfx		= "./";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//return;
	
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');
	require_once('../lib/IndividualStaticValue.php');

// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix	= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts		= "<BR>";								// HTMLの改行
	$l_username		= "";									// ユーザー名
	$l_password		= "";									// パスワード
	$l_auth_table	= "USERS_V";							// 認証に使用するテーブル名
	$l_auth_name	= "USER_CODE";							// 認証に使用する名前項目
	$l_auth_pass	= "ENCRYPTION_PASSWORD";				// 認証に使用するパスワード項目
	$l_get_key1		= "DATA_ID";							// 認証で取得するキー項目1
	$l_get_key2		= "USER_ID";							// 認証で取得するキー項目2
	$l_get_key3		= "NAME";								// 認証で取得するキー項目3
	$l_authlevel1	= AUTH_SADM;							// 管理画面を利用できる権限(システム管理者)
	$lr_login_log	= "";									// ログインログ用配列

// ==================================
// 例外定義
// ==================================
	function my_exception_logincheck(Exception $e){
		echo $e->getMessage();
		return;
    }

// ==================================
// POST値の取得とチェック
// ==================================
	$l_username = $_POST['nm_username'];
	$l_password = $_POST['nm_password'];
	
	if(is_null($l_username)){
		throw new Exception("ユーザー名が取得できませんでした。");
	}
	if(is_null($l_password)){
		throw new Exception("パスワードが取得できませんでした。");
	}
	
// ==================================
// ログインチェック
// ==================================
	$lr_login_log["USED_USER_CODE"]			= $_POST['nm_username'];
	$lr_login_log["USED_PASSWORD"]			= md5($_POST['nm_password']);
	$lr_login_log["USED_COMPANY_CODE"]		= "";
	$lr_login_log["CERTIFICATION_RESULT"]	= "";
	$lr_login_log["SPG_REFERER"]			= $_SERVER['HTTP_REFERER'];
	$lr_login_log["SPG_REMORT_ADDR"]		= $_SERVER['REMOTE_ADDR'];
	$lr_login_log["SPG_SERVER"]				= print_r($_SERVER, true);
	$lr_login_log["SPG_REQUEST"]			= "";	// パスワードが格納されている為NULLで登録
	$lr_login_log["REMARK"]					= "Maintenance";
	
	// SELECT文作成
	$l_select_phrase  = "SELECT ". $l_get_key1. ",". $l_get_key2. "," .$l_get_key3;
	$l_select_phrase .= " FROM ". $l_auth_table;
	$l_select_phrase .= " WHERE ";
	$l_select_phrase .= " ". $l_auth_name. " = \"". $l_username. "\"";
	$l_select_phrase .= " AND ". $l_auth_pass. " = \"". md5($l_password). "\"";
	$l_select_phrase .= " AND AUTHORITY_CODE = \"". $l_authlevel1. "\"";
	$l_select_phrase .= " AND VALIDITY_FLAG = \"Y\"";
	
	// DB接続
	require_once('../lib/ConnectDB.php');
	$mdb = getMysqlConnection();
	
	// クエリ実行
	$rcnt = 0;
	try{
		$l_ar_retrec = getRowWithRownum($mdb, $l_select_phrase);
		/*
		$l_result = $mdb->query($l_select_phrase);
		while($l_user_rec = $l_result->fetch_object()){
			$l_ar_retrec[$rcnt] = $l_user_rec;
			$rcnt++;
		}
		*/
		//print_r($l_ar_retrec);
		//print(count($l_ar_retre));
		//return;
	}catch(Exception $e){
		throw new Exception("クエリー失敗");
	}
	
	if(count($l_ar_retrec) == 1){
		$lr_login_log["CERTIFICATION_RESULT"] = "OK";
		
		// 認証成功(セッションを開始しtokenを返す)
		require_once('../maintenance/c_sessionControl.php');

		$lc_sess = new sessionControl();
		$lc_sess->destroySession();						// セッション破棄
		$l_token = $lc_sess->setToken();				// トークンセット
		
		// DATA_ID、ユーザーID、ユーザー名をセッションにセット
		$lc_sess->setSesseionItem($l_get_key1, $l_ar_retrec[1][$l_get_key1]);
		$lc_sess->setSesseionItem($l_get_key2, $l_ar_retrec[1][$l_get_key2]);
		$lc_sess->setSesseionItem($l_get_key3, $l_ar_retrec[1][$l_get_key3]);
		
		print $l_token;
	}else if(count($l_ar_retrec) > 1){
		// ユーザー設定エラー
		throw new Exception("同一名称のユーザーが複数設定されています");
	}else{
		$lr_login_log["CERTIFICATION_RESULT"] = "NG";
		
		// 認証失敗
		print "NG";
	}
	
	// ログインログへの書き込み
	require_once('../mdl/m_login_log.php');
	$lc_mllog = new m_login_log();
	$lr_mllog = $lc_mllog->setSaveRecord($lr_login_log);
	$lc_mllog->insertRecord();
?>
