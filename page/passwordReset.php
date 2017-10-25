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
 ファイル名：passwordReset.php
 処理概要  ：PC用パスワードリセット画面
 GET受領値：
             token                      トークン(必須)
             mode                       ユーザーID(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_GET);
		print "step1<br>";
	}
/*----------------------------------------------------------------------------
	前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');
	if($l_debug_mode==1){print("Step-CommonStaticValue".DIR_LIB);print "<br>";}
/*----------------------------------------------------------------------------
	変数宣言
  ----------------------------------------------------------------------------*/
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	if($l_debug_mode==1){print("Step-変数宣言");print "<br>";}

/*----------------------------------------------------------------------------
	例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_passwordreset(Exception $e){
		// セッション切断の場合はメッセージに「ST」と入ってくる
		if($e->getMessage() == "ST"){
			$l_error_type = "ST";
		}else{
			$l_error_type = "ER";
		}
		
		require_once('../lib/ShowMessage.php');
		$lc_smess = new ShowMessage($l_error_type);
		
		// 予期せぬ例外の場合は追加メッセージをセット
		if($l_error_type != "ST"){
			$lc_smess->setExtMessage($e->getMessage());
		}
		
		$lc_smess->showMessage();
		return;
    }
	set_exception_handler('my_exception_passwordreset');
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
/*----------------------------------------------------------------------------
	GET値検証
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン
	$l_user_id			= $_GET['mode'];				// ユーザーID
	
	if ($l_token == "" or $l_user_id == ""){
		// tokenかユーザーIDが空の場合は不正アクセスとする
		throw new exception('不正なURLにアクセスしています。');
		return;
	}
	if($l_debug_mode==1){print("Step-GET値検証");print "<br>";}
/*----------------------------------------------------------------------------
	パスワードリセット
  ----------------------------------------------------------------------------*/
	require_once('../lib/PasswordProduction.php');
	$lc_passprod = new PasswordProduction($l_user_id);
	
	// トークンの一致を確認する。一致していなければ不正アクセスとみなす
	if ($lc_passprod->checkToken($l_token) != 0){
		throw new exception('不正なURLにアクセスしています。');
		return;
	}
	
	// パスワード再設定
	$l_new_password = $lc_passprod->passwordReset();
	// パスワードが取得できない場合は不正アクセスとみなす
	if ($l_new_password == 1 or $l_new_password == ""){
		throw new exception('不正なURLにアクセスしています。');
		return;
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_passprod->getUserName();
	
	if($l_debug_mode==1){print("Step-パスワードリセット");print "<br>";}
/*----------------------------------------------------------------------------
	変数定義
  ----------------------------------------------------------------------------*/
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	$copyright_text	= NULL;			// コピーライト
/*----------------------------------------------------------------------------
	変数セット
  ----------------------------------------------------------------------------*/
	// CSSファイル
	$ar_css_files	= array(DIR_CSS."v_entrance.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", DIR_JS."jfnc_common.js");
	// コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";

	if($l_debug_mode==1){print("Step-変数セット");print "<br>";}
/*----------------------------------------------------------------------------
	Smartyセット
  ----------------------------------------------------------------------------*/
	// クラスインスタンス作成
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = DIR_TEMPLATES;
	$lc_smarty->compile_dir  = DIR_TEMPLATES_C;
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."パスワードリセット完了");		// タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("txt_copyright",	$copyright_text);				// コピーライト
	$lc_smarty->assign("systemname",	SYSTEM_NAME);					// システム名
	
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	$lc_smarty->assign("new_password",	$l_new_password);				// 新しいパスワード
	
	
	if($l_debug_mode==1){print("Step-Smartyセット");print "<br>";}

/*----------------------------------------------------------------------------
	ページ表示
  ----------------------------------------------------------------------------*/
	$lc_smarty->display('PasswordReset.tpl');
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>