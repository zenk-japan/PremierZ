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
 ファイル名：userinfo.php
 処理概要   ユーザー情報画面
 GET受領値：
             token_code                 トークン(必須)
             user_id                    ユーザーID(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print "<pre>";
		var_dump($_GET);
		print "</pre>";
		print "<br>";
		print "session-><br>";
		session_start();
		print "<pre>";
		var_dump($_SESSION);
		print "</pre>";
		print "<br>";
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "TASKS_EDIT";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_sess_data_id			= "";									// 画面にセットするDATA_ID
	$l_sess_user_name		= "";									// 実行ユーザー名
	$l_get_token			= "";									// GETされたトークン
	$l_sess_token			= "";									// セッション保持のトークン
	$l_user_id				= "";									// ユーザーID
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_userinfo(Exception $e){
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
	set_exception_handler('my_exception_userinfo');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();

	// GETされたトークンを取得
	$l_get_token = $_GET['token_code'];
	if(is_null($l_get_token)){
		throw new Exception($l_error_type_st);
	}

	// トークンの取得
	$l_sess_token = $lc_sess->getToken();

	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	// セッションと_GETでトークンが一致しない場合は不正アクセスとみなす
	if($l_get_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}

	// DATA_IDの取得
	$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_sess_data_id == ""){
		throw new Exception($l_error_type_st);
	}

	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  GET変数取得
  ----------------------------------------------------------------------------*/
	// ユーザーID
	$l_user_id = $_GET['user_id'];
	if ($l_user_id == ""){
		throw new Exception($l_error_type_st);
	}

	if($l_debug_mode==1){print("Step-GET変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}
	
	// ユーザーMDL
	require_once('../mdl/m_user_master.php');
	
	// 検索条件設定
	$lr_cond_dtl = array('USER_ID = '.$l_user_id);
	
	// レコード取得
	$lc_model_class = new m_user_master('Y', $lr_cond_dtl);
	$lr_user_data = $lc_model_class->getViewRecord();
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
	$ar_css_files	= array(DIR_CSS."jquery-ui-custom.css",
							DIR_CSS."v_edit_masters.css",
							DIR_CSS."v_edit_block.css",
							DIR_CSS."example.css",
							DIR_CSS."v_user_info_block.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js",
							DIR_JS."jquery-ui-custom.min.js",
							DIR_JS."jfnc_common_edit.js",
							DIR_JS."jquery.updnWatermark.js"
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
	$lc_smarty->assign("edit_include_tpl"				, "user_info_block.tpl");	// 編集

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"						, "ユーザー情報");					// 画面タイトル
	$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
	$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル
	$lc_smarty->assign("proc_mode"						, $l_proc_mode);				// モード

	// ------------------------------
	// 表示項目
	// ------------------------------
	// データレコード
	$lc_smarty->assign("detail_table_item"				, $lr_user_data[1]);


	// ------------------------------
	// 隠し項目
	// ------------------------------

	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}

/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('EditMasterMain.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>