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
 ファイル名：c_mailLogDel.php
 処理概要  ：メールログ削除
 POST受領値：
             nm_token_code              トークン(必須)
             nm_data_id                 DATA_ID(任意)
             nm_send_from               送信元アドレス(任意)
             nm_send_to                 送信先アドレス(任意)
             nm_date_from               送信日時From(任意)
             nm_date_to                 送信日時To(任意)
             nm_send_purpose            送信目的(任意)
             nm_search_phrase           タイトル/本文検索(任意)
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
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_get_key2			= "USER_ID";							// 認証で取得するキー項目2
	$l_user_id			= "";									// ユーザーID
	$lr_params			= "";									// 各種パラメータ
	$l_data_id			= "data_id";							// DATA_ID
	$l_send_from		= "send_from";							// 送信元アドレス
	$l_send_to			= "send_to";							// 送信先アドレス
	$l_send_purpose		= "send_purpose";						// 送信目的
	$l_date_from		= "date_from";							// 送信日時From
	$l_date_to			= "date_to";							// 送信日時To
	$l_search_phrase	= "search_phrase";						// タイトル/本文検索
	$lr_where			= "";
	$l_where_cnt		= 0;
	
	if($l_debug_mode==1){print "step3<br>";}
// ==================================
// 例外定義
// ==================================
	function my_exception_mailLogDel(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_mailLogDel');
	
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
	$lr_params[$l_data_id]			= $_POST['nm_data_id'];
	$lr_params[$l_send_from]		= $_POST['nm_send_from'];
	$lr_params[$l_send_to]			= $_POST['nm_send_to'];
	$lr_params[$l_date_from]		= $_POST['nm_date_from'];
	$lr_params[$l_date_to]			= $_POST['nm_date_to'];
	$lr_params[$l_send_purpose]		= $_POST['nm_send_purpose'];
	$lr_params[$l_search_phrase]	= $_POST['nm_search_phrase'];
	
	if($l_debug_mode==1){print "step6<br>";}
// ==================================
// Where句の設定
// ==================================
	require_once('../mdl/m_mail_log.php');
	$lc_mam = new m_mail_log();
	
	$lr_where		= $lc_mam->makeWherePhrase($lr_params);
	
	
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
