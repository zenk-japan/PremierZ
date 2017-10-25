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
 ファイル名：c_loginLogDel.php
 処理概要  ：ログインログ削除
 POST受領値：
             nm_token_code              トークン(必須)
             nm_used_user_code          ユーザコード(任意)
             nm_used_comp_code          会社コード(任意)
             nm_certification_result    認証結果(任意)
             nm_date_from               ログイン日時From(任意)
             nm_date_to                 ログイン日時To(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";				// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;									// デバッグモード(1:有効、0:無効)
	if($l_debug_mode==1){
		print_r($_POST);
		//print "step1<br>";
		//print "<br>";
		//print_r($_SESSION);
	}
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

	if($l_debug_mode==1){print "step2<br>";}
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix				= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts					= "<BR>";								// HTMLの改行
	$l_post_token				= "";									// POSTされたトークン
	$l_sess_token				= "";									// セッションで保持しているトークン
	$l_get_key2					= "USER_ID";							// 認証で取得するキー項目2
	$l_user_id					= "";									// ユーザーID
	$lr_params					= "";									// 各種パラメータ
	$l_data_id					= "data_id";							// DATA_ID
	$l_used_user_code			= "used_user_code";						// ユーザコード
	$l_used_comp_code			= "used_company_code";					// 会社コード
	$l_certification_result		= "certification_result";				// 認証結果
	$l_date_from				= "last_update_datet_from";				// ログイン日時From
	$l_date_to					= "last_update_datet_to";				// ログイン日時To
	$lr_where					= "";
	$l_where_cnt				= 0;
	
	if($l_debug_mode==1){print "step3<br>";}
// ==================================
// 例外定義
// ==================================
	function my_exception_mailLogDel(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_loginLogDel');
	
	if($l_debug_mode==1){print "step4<br>";}
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
	if($l_debug_mode==1){print "step5<br>";}

// ==================================
// POST変数取得
// ==================================
	$lr_params[$l_used_user_code]		= $_POST['nm_used_user_code'];
	$lr_params[$l_used_comp_code]		= $_POST['nm_used_comp_code'];
	$lr_params[$l_certification_result]	= $_POST['nm_certification_result'];
	$lr_params[$l_date_from]			= $_POST['nm_date_from'];
	$lr_params[$l_date_to]				= $_POST['nm_date_to'];
	
	if($l_debug_mode==1){print "step6<br>";}
// ==================================
// Where句の設定
// ==================================
	require_once('../mdl/m_login_log.php');
	$lc_mam = new m_login_log();
	
	$lr_where		= $lc_mam->makeWherePhrase($lr_params);
	
//var_dump($lr_params);
//var_dump($lr_where);
	if($l_debug_mode==1){print "step7<br>";}
// ==================================
// 削除処理
// ==================================
	// Where句をセット
	$lc_mam->setWhereArray($lr_where);
	$l_retcode = $lc_mam->deleteRecord();
	
	print $l_retcode;
	if($l_debug_mode==1){print "step8<br>";}
?>
