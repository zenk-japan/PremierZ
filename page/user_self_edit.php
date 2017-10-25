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
 ファイル名：user_self_edit.php
 処理概要   ユーザー設定変更画面
 POST受領値：
            nm_token_code              トークン(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
		print "session-><br>";
		session_start();
		print var_dump($_SESSION);
		print "<br>";
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "USER_SELF_EDIT";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID

/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_userselfedit(Exception $e){
		//echo "例外が発生しました。";
		//echo $e->getMessage();
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
	set_exception_handler('my_exception_userselfedit');

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
	
	// 権限名の取得
	$l_authority_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');

	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		throw new Exception($l_error_type_st);
	}

	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	// 検索条件をセッションにセット
	//print_r($_SERVER);
	$lc_sess->setSesseionItem($l_page_name."-COND", $_POST);
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	

	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// ユーザーMDL
	require_once('../mdl/m_user_master.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_見積MDL");print "<br>";}

	// ------------------------------
	// 検索条件設定
	// ------------------------------
	// ユーザーID
	$lr_user_cond = array('USER_ID = '.$l_sess_user_id);
	// 有効フラグ
	if($l_valid_checkstat == "Y"){
		array_push($lr_user_cond, "VALIDITY_FLAG = 'Y'");
	}

	// ------------------------------
	// レコード取得
	// ------------------------------
	$lc_user = new m_user_master('Y', $lr_user_cond);
	$lr_user = $lc_user->getViewRecord();
	//print_r($lr_user);
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}

/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";

	if($l_debug_mode==1){print("Step-変数定義&セット");print "<br>";}
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
	$ar_css_files	= array(DIR_CSS."v_top_block.css",
							DIR_CSS."v_user_self_edit_menu_block.css", 
							DIR_CSS."v_user_self_edit_common.css", 
							DIR_CSS."v_user_self_edit_main.css", 
							DIR_CSS."v_user_self_edit_edit_block.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_user_self_edit.js"
							);

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
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}

	// ------------------------------
	// インクルードするテンプレート
	// ------------------------------
	$lc_smarty->assign("top_include_tpl"	,"top_block.tpl");						// トップ
	$lc_smarty->assign("main_include_tpl"	,"user_self_edit_menu_block.tpl");		// メインメニュー
	$lc_smarty->assign("edit_include_tpl"	,"user_self_edit_edit_block.tpl");		// 編集項目

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"ユーザー情報");				// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_auth"		,$l_authority_name);			// ユーザー権限
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名

	// ------------------------------
	// 共通設定
	// ------------------------------
	$lc_smarty->assign("now_page",		$l_page_name);					// 現在のページ

	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	
	// ------------------------------
	// 明細部
	// ------------------------------
	// データレコード
	$lc_smarty->assign("ar_user_record"	,	$lr_user[1]);					// 明細レコード

	// ------------------------------
	// 隠し項目
	// ------------------------------
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('user_self_edit_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>