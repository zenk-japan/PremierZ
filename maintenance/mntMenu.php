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
 ファイル名：mntMenu.php
 処理概要  ：管理メニュー画面
 POST受領値：
             nm_token_code              トークン(必須)
******************************************************************************/
	$l_dir_prfx		= "./";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "step1<br>";
	//print "<br>";
	//print_r($_SESSION);
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix	= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts		= "<BR>";								// HTMLの改行
	$l_user_name		= "";								// セッションで保持しているユーザー名

	//print "step3<br>";
// ==================================
// 例外定義
// ==================================
	function my_exception_maintenance(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_maintenance');
	
	//print "step4<br>";
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
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
	//print "step5<br>";
// ==================================
// 変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	//print "step6<br>";
// ==================================
// 変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array("v_mnt_menu.css");
	// jsファイル
	$ar_js_files	= array($l_dir_prfx.DIR_JS . "jquery.js", "maintenance.js", "mntMenu.js");

	//print "step7<br>";
// ==================================
// Smartyセット
// ==================================
	// クラスインスタンス作成
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = "./";									// テンプレートはphpと同じディレクトリに収めるため
	$lc_smarty->compile_dir  = $l_dir_prfx.DIR_TEMPLATES_C;
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."管理");			// タブタイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	$lc_smarty->assign("head_title",	SYSTEM_NAME."管理 メニュー");	// ヘッダータイトル
	
	// メニューヘッダー
	$lr_main_top		= array(
								"メニュー"
							  , "説明"
							);
	$lc_smarty->assign("ar_maintab_top",	$lr_main_top);
	
	// メニュー
	$lr_maintab_dtl		= array(
							array(
								"btn_id"		=> "id_use_company",
								"btn_value"		=> "利用会社管理",
								"explain"		=> "DATA_IDと利用会社を管理します"
								),
							array(
								"btn_id"		=> "id_common_master",
								"btn_value"		=> "共通マスター管理",
								"explain"		=> "各機能共通のマスター値を管理します"
								),
							array(
								"btn_id"		=> "id_authority",
								"btn_value"		=> "権限管理",
								"explain"		=> "各ユーザーに割り当てる為の権限を管理します"
								),
							array(
								"btn_id"		=> "id_value_list",
								"btn_value"		=> "値リスト定義管理",
								"explain"		=> "値リストの使用箇所や、使用するSQLを管理します"
								),
							array(
								"btn_id"		=> "id_page_using_conf",
								"btn_value"		=> "画面利用管理",
								"explain"		=> "各ユーザーの分類区分によって、使用できる画面を制御します"
								),
							array(
								"btn_id"		=> "id_mail_log",
								"btn_value"		=> "メール送信ログ管理",
								"explain"		=> "メールの送信ログを確認します"
								),
							array(
								"btn_id"		=> "id_login_log",
								"btn_value"		=> "ログインログ管理",
								"explain"		=> "ログインログを確認します"
								),
							array(
								"btn_id"		=> "id_sysadmin_mnt",
								"btn_value"		=> "システム管理者変更",
								"explain"		=> "システム管理者のユーザーコードやパスワードを変更します"
								)
							);
	$lc_smarty->assign("ar_maintab_dtl",	$lr_maintab_dtl);

	// 隠し項目
	$lr_hidden_items	= array(
							array(
								  "name"	=> "nm_token_code"
								, "value"	=> $l_post_token
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	
	//print "step8<br>";
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('MaintenanceMenu.tpl');
	//print "step9<br>";
?>
