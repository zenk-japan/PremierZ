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
 ファイル名：reportmenu.php
 処理概要  ：帳票メニュー画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_data_id                 DATA_ID(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_reportmenu(Exception $e){
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
	set_exception_handler('my_exception_reportmenu');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	
	// POSTされたトークンを取得
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}
	
	// セッションからトークンを取得
	$l_sess_token = $lc_sess->getToken();
	//$l_sess_token = $lc_sess->setToken();
	//print var_dump($_SESSION);
	
	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	
	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	
	// 権限コードの取得
	$l_authority_code = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	
	// 権限名の取得
	$l_authority_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
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
	// DATA_IDを取得
	if(!is_null($_POST['nm_data_id'])){
		$l_data_id = $_POST['nm_data_id'];
	}else{
		$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	}
	if($l_data_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*-----------------------------------
	Smarty変数定義
  -----------------------------------*/
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>";}
/*-----------------------------------
	Smarty変数セット
  -----------------------------------*/
	// CSSファイル
	$ar_css_files	= array(DIR_CSS."v_top_block.css"
							, DIR_CSS."v_mainmenu.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js"
							, DIR_JS."jfnc_common.js"
							, DIR_JS."jfnc_reportmenu.js"
							, DIR_JS."jfnc_reportmenu_top.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
/*-----------------------------------
	Smartyセット
  -----------------------------------*/
	// ------------------------------
	// クラスインスタンス作成
	// ------------------------------
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = DIR_TEMPLATES;
	$lc_smarty->compile_dir  = DIR_TEMPLATES_C;
	
	$lc_smarty->assign("headtitle",		"帳票メニュー");	// 画面タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);		// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);		// CSSファイル
	$lc_smarty->assign("user_auth",		$l_authority_name);	// ユーザー権限
	$lc_smarty->assign("user_name",		$l_user_name);		// ユーザー名
	$lc_smarty->assign("maintitle",		"帳票メニュー");	// メインタイトル
	$lc_smarty->assign("systemname",	SYSTEM_NAME);		// システム名
	
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}	
	
	// ------------------------------
	// メニュー項目
	// ------------------------------
	if ($l_authority_code == AUTH_ADMI || $l_authority_code == AUTH_MANG || $l_authority_code == AUTH_GEN1 || $l_authority_code == AUTH_GEN2 || $l_authority_code == AUTH_GENE) {
		$lr_menu_items	= array(
								array(
									  "mode"	=> ""
									, "tdid"	=> "id_td_menu_attendance"
									, "logo"	=> DIR_IMG."table.png"
									, "title"	=> "勤務表"
									, "remarks"	=> "勤務実績の出力を行います。"
									)
							);
	}else {
		$lr_menu_items	= array(
								array(
									  "mode"	=> ""
									, "tdid"	=> "id_td_menu_attendance"
									, "logo"	=> DIR_IMG."table.png"
									, "title"	=> "勤務表"
									, "remarks"	=> "勤務実績の出力を行います。"
									)
							);
	}
		
	$lc_smarty->assign("ar_menu",	$lr_menu_items);
	
	if($l_debug_mode==1){print("Step-メニュー項目");print "<br>";}	
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// DATA_ID
								  "name"	=> "nm_data_id"
								, "value"	=> $l_data_id
								),
							array(									// DATA_ID(旧システム対応)
								  "name"	=> HDITEM_DATAID
								, "value"	=> $l_data_id
								),
							array(									// ユーザーID使用ページ対応(hd_loginuserid)
								  "name"	=> "hd_loginuserid"
								, "value"	=> $l_user_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('ReportMenu.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>