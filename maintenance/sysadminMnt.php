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
 ファイル名：sysadminMnt.php
 処理概要  ：システム管理者変更画面
 POST受領値：
             nm_token_code              トークン(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";				// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;									// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_POST);
		print "step1<br>";
		print "<br>";
		print_r($_SESSION);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_mes_sufix				= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts					= "<BR>";								// HTMLの改行
	$l_post_token				= "";									// POSTされたトークン
	$l_sess_token				= "";									// セッションで保持しているトークン
	$l_user_name				= "";									// セッションで保持しているユーザー名
	$lr_params					= "";									// 各種パラメータ
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_sysadminmnt(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_sysadminmnt');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
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
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  POST変数取得
  POST変数はセッションの値より優先される
  ----------------------------------------------------------------------------*/
	
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/* ==================================
	Smarty変数定義
   ================================== */
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>";}
/* ==================================
	Smarty変数セット
   ================================== */
	// CSSファイル
	$ar_css_files	= array("v_mnt_main.css", "v_mnt_sysadm.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS . "jquery.js", "maintenance.js", "sysadminMnt.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
/* ==================================
	Smartyセット
   ================================== */
	/* ------------------------------
		クラスインスタンス作成
	   ------------------------------ */
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = "./";									// テンプレートはphpと同じディレクトリに収めるため
	$lc_smarty->compile_dir  = $l_dir_prfx.DIR_TEMPLATES_C;
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."管理");			// 画面タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	$lc_smarty->assign("maintitle",		"システム管理者変更");			// メインタイトル

	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}
	
	
	/* ------------------------------
		その他出力
	   ------------------------------ */
	// 現在のユーザー名
	// ユーザーMDL
	require_once('../mdl/m_user_master.php');
	
	// 検索条件設定
	$lr_user_cond = array('DATA_ID = '. $lc_sess->getSesseionItem('DATA_ID'));
	array_push($lr_user_cond, 'USER_ID = '. $lc_sess->getSesseionItem('USER_ID'));
	
	// レコード取得
	$l_mum = new m_user_master('Y', $lr_user_cond);
	$lr_users = $l_mum->getViewRecord();
	
	$lc_smarty->assign("old_user",		$lr_users[1]["USER_CODE"]);
	
	/* ------------------------------
		隠し項目
	   ------------------------------ */
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// ユーザーID
								  "name"	=> "nm_user_id"
								, "value"	=> $lc_sess->getSesseionItem('USER_ID')
								)
								
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
/* ==================================
	ページ表示
   ================================== */
	$lc_smarty->display('SysadminMnt.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>
