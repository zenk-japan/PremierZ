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
 ファイル名：c_pageUsingConfDel.php
 処理概要  ：権限定義削除
 POST受領値：
             nm_token_code              トークン(必須)
             nm_page_using_conf_id       画面利用ID(必須)
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
	$l_user_id			= "";
	
	if($l_debug_mode==1){print "step3<br>";}
// ==================================
// 例外定義
// ==================================
	function my_exception_pageUsingConfDel(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_pageUsingConfDel');
	
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
	// 権限IDを取得
	if(!is_null($_POST['nm_page_using_conf_id'])){
		$l_prekey= $_POST['nm_page_using_conf_id'];
	}else{
		// 権限が取得できない場合はエラー
		throw new Exception("ID エラー");
	}
	
	if($l_debug_mode==1){print "step6<br>";}
// ==================================
// 物理削除処理
// ==================================
	require_once('../mdl/m_page_using_conf.php');
	$lc_mam = new m_page_using_conf();
	
	$l_retcode = $lc_mam->deleteRecord($l_prekey, $l_user_id);
	
	if($l_retcode === RETURN_NOMAL){
		print "削除が完了しました";
	}else{
		print "削除に失敗しました";
	}
	if($l_debug_mode==1){print "step7<br>";}
?>
