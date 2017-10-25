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

// *****************************************************************************
// ファイル名：maintenance.php
// 処理概要  ：管理画面
// *****************************************************************************
	$l_dir_prfx		= "./";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "<br>";
	//print_r($_SESSION);
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix	= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts		= "<BR>";								// HTMLの改行

// ==================================
// 例外定義
// ==================================
	function my_exception_maintenance(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_maintenance');
	
// ==================================
// 変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
// ==================================
// 変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array("v_mnt_login.css");
	// jsファイル
	$ar_js_files	= array($l_dir_prfx.DIR_JS . "jquery.js", "maintenance.js", "mntLogin.js");

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
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."管理");			// タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	

// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('Maintenance.tpl');
?>
