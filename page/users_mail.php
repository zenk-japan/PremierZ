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
 ファイル名：users_mail.php
 処理概要  ：ユーザー管理メール送信画面
 GET受領値：
             cid                        会社ID(任意)
             gid                        グループID(任意)
             uid                        ユーザーID(必須)
             nm_token_code              トークン(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>\n";
		print var_dump($_GET);
		print "<br>\n";
		print "session-><br>\n";
		print var_dump($_SESSION);
		print "<br>\n";
		print var_dump($_REQUEST);
		print "<br>\n";
	}

/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>\n";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<br>\n";	// メッセージ用接尾辞
	$l_html_rts			= "<br>\n";								// HTMLの改行
	$l_get_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_selected_user_id	= "";									// POSTされたユーザーID
	$l_sess_user_id	= "";										// ログインユーザーID
	$l_show_dtl_user_id	= "";									// メール送信するユーザーID
	$lr_dtl_rec			= "";									// メール送信用のレコード
	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_usermnt(Exception $e){
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
	set_exception_handler('my_exception_usermnt');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	
	// DATA_IDの取得
	$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_sess_data_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_data_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// GETされたトークンを取得
	$l_get_token = $_GET['nm_token_code'];
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
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  GET変数取得
  ----------------------------------------------------------------------------*/
	// ユーザーID
	if(!is_null($_GET['uid'])){
		$l_selected_user_id = $_GET['uid'];
	}
	
	if($l_debug_mode==1){print("Step-メールサーバー設定取得");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// ------------------------------
	// メール設定読込
	// ------------------------------
	require_once('../lib/MailSettings.php');
	$lc_mailset = new MailSettings($l_sess_data_id);
	
	// ------------------------------
	// 送信用のユーザーデータ取得
	// ------------------------------
	// ユーザーMDL
	require_once('../mdl/m_user_master.php');
	
		$l_show_dtl_user_id	= $l_selected_user_id;
	
	// 検索条件設定
	$lr_user_cond_dtl = array('USER_ID = '.$l_show_dtl_user_id);
	
	// レコード取得
	$l_user_mum_dtl = new m_user_master('Y', $lr_user_cond_dtl);
	$lr_user_detail = $l_user_mum_dtl->getViewRecord();
//	print_r($lr_user_detail);
	
	/*--------------------
	   ボタン
	  --------------------*/
	$ar_button = array(
	//	array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_confirmation", "value" => "確認"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_send", "value" => "送信"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);
	
	/*--------------------
	   送信先設定
	  --------------------*/
	// To(自宅)
	$lr_address_to_home	= $lr_user_detail[1]['HOME_MAIL'];
	
	// To(携帯)
	$lr_address_to_mobile	= $lr_user_detail[1]['MOBILE_PHONE_MAIL'];
	
//	if(isset($lr_user_detail[1]['MOBILE_PHONE_MAIL'])){
//		if(isset($lr_address_to)){
//			$lr_address_to	.= ",".$lr_user_detail[1]['MOBILE_PHONE_MAIL'];
//		}else{
//			$lr_address_to	= $lr_user_detail[1]['MOBILE_PHONE_MAIL'];
//		}
//	}
//	
//	if(!isset($lr_address_to)){
//		$lr_address_to = "送信先メールアドレスを入力してください。";
//	}
	
	/*--------------------
	   件名
	  --------------------*/
	require_once('../lib/CommonMessage.php');
	$lc_cmmess = new CommonMessage();
	// 宛先名
	$lr_subject				= $lc_cmmess->getUserNoticeTitle($lr_user_detail[1]);
	
	/*--------------------
	   本文
	  --------------------*/
	$lr_body				.= $lc_cmmess->getUserNoticeMess($lr_user_detail[1]);
	
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*-----------------------------------
	Smarty変数定義
  -----------------------------------*/
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>\n";}
/*-----------------------------------
	Smarty変数セット
  -----------------------------------*/
	// CSSファイル
	$ar_css_files	= array(
							DIR_CSS."v_mail_masters.css", 
							DIR_CSS."v_mail_block.css", 
							DIR_CSS."example.css"
						);
	// jsファイル
	$ar_js_files	= array(
							DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_common_mail.js", 
							DIR_JS."jfnc_users_mail.js", 
							DIR_JS."jquery.updnWatermark.js"
						);
	
	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>\n";}
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
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>\n";}	
	
	// ------------------------------
	// インクルードするテンプレート
	// ------------------------------
	$lc_smarty->assign("mail_include_tpl"				, "users_mail_block.tpl");		// メール送信
	
	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"						, "ユーザ管理");				// 画面タイトル
	$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
	$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル
	
	// ------------------------------
	// 表示項目
	// ------------------------------
	// ボタン
	$lc_smarty->assign("mail_button"					, $ar_button);
	
	// From
	$lc_smarty->assign("mail_address_from"				, $lc_mailset->getMailAddr1());
	
	// To
	//$lc_smarty->assign("mail_address_to"				, $lr_address_to);
	$lc_smarty->assign("mail_address_to_home"			, $lr_address_to_home);
	$lc_smarty->assign("mail_address_to_mobile"			, $lr_address_to_mobile);
	
	// Cc
	$lc_smarty->assign("mail_address_cc"				, $lc_mailset->getMailAddr1());
	
	// 件名
	$lc_smarty->assign("mail_subject"					, $lr_subject);
	
	// 本文
	$lc_smarty->assign("mail_body"						, $lr_body);
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_get_token
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>\n";}
	
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('MailMasterMain.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>\n";}
?>