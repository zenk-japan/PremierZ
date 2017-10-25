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
 ファイル名：mobilepasswordreset.php
 処理概要  ：モバイル用パスワードリセット画面
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
  変数定義
  ----------------------------------------------------------------------------*/
	$l_terminal			= "";					// 端末キャリア
	$l_model			= "";					// 端末モデル
	$lr_spdesc			= "";					// 端末固有のヘッダー記載情報
	$l_char_code		= "character_code";		// 文字コード
	$l_doctype			= "declaration";		// ドキュメントタイプ宣言
	$l_xmlns			= "xmlns";				// XML名前空間
	$l_token			= "";					// GETトークン
	$l_sess_token		= "";					// セッショントークン
	$l_err_flag			= true;					// エラーフラグ
	
/*----------------------------------------------------------------------------
  モバイル共通関数インスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/MobileCommonFunctions.php');
	$lc_mcf = new MobileCommonFunctions();
	
/*==================================
  キャリア判別
  ==================================*/
	require_once('../lib/CommonMobiles.php');
	$lc_cm = new CommonMobiles();
	$l_connec_terminal = $lc_cm->checkMobiles();
	
	$l_terminal		= $l_connec_terminal['Terminal'];
	$l_model		= $l_connec_terminal['Model'];
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
		
	if($l_debug_mode==1){print("Step-キャリア判別");print "<br>";}
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_mresetpass(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_mresetpass');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン
	$l_user_id			= $_GET['mode'];				// ユーザーID
	
	if ($l_token == "" or $l_user_id == ""){
		// tokenかユーザーIDが空の場合は不正アクセスとする
		$lc_mcf->showUnauthorizedAccessPage($l_terminal, $l_model);
		return;
	}
	
	//print "l_show_page->".$l_show_page.":"."l_max_page->".$l_max_page.":"."l_num_to_show->".$l_num_to_show."<br>";
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}

/*----------------------------------------------------------------------------
  パスワード再設定
  ----------------------------------------------------------------------------*/
	require_once('../lib/PasswordProduction.php');
	$lc_passprod = new PasswordProduction($l_user_id);
	
	// トークンの一致を確認する。一致していなければ不正アクセスとみなす
	if ($lc_passprod->checkToken($l_token) != 0){
		$lc_mcf->showUnauthorizedAccessPage($l_terminal, $l_model);
		return;
	}
	
	// パスワード再設定
	$l_new_password = $lc_passprod->passwordReset();
	// パスワードが取得できない場合は不正アクセスとみなす
	if ($l_new_password == 1 or $l_new_password == ""){
		$lc_mcf->showUnauthorizedAccessPage($l_terminal, $l_model);
		return;
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_passprod->getUserName();
	
	if($l_debug_mode==1){print("Step-パスワード再設定");print "<br>";}
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*==================================
  smartyクラスインスタンス作成
  ==================================*/
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir	= DIR_TEMPLATES;
	$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
	$lc_smarty->config_dir		= DIR_CONFIGS;
	$lc_smarty->cache_dir		= DIR_CACHE;
	
	if($l_debug_mode==1){print("Step-smartyクラスインスタンス作成");print "<br>";}
	
/*==================================
  smartyアサイン
  ==================================*/
	// ヘッダー部
	$lc_smarty->assign("doctype",	$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",	$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",		$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",	$l_terminal);
	$lc_smarty->assign("model",		$l_model);
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// タイトル
	$lc_smarty->assign("headtitle",			"パスワードリセット通知");
	
	// ユーザー名
	$lc_smarty->assign("user_name",			$l_user_name);
	
	// パスワード
	$lc_smarty->assign("new_passwoed",		$l_new_password);
	
	// ログインページ
	$lc_smarty->assign("login_page",		'../mobile/login.php');
	
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplatePasswordReset.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>