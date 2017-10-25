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
 ファイル名：completionmailsend.php
 処理概要  ：完了作業メール送信確認画面
 GET受領値：
             token                      トークン(必須)
             nm_from_addr               送信元メールアドレス(必須)
             nm_to_addr                 送信先メールアドレス(必須)
             nm_cc_addr                 CCメールアドレス(任意)
             nm_bcc_addr                BCCメールアドレス(任意)
             nm_mail_title              メールタイトル(任意)
             nm_mail_text               メール本文(任意)
             nm_work_staff_id           作業人員ID(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		//print_r($_GET);
		print_r($_POST);
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
	$l_work_staff_id	= "";					// 作業人員ID
	$l_err_flag			= true;					// エラーフラグ
	$lr_hidden_param	= "";					// OKボタンのリンク先に渡すパラメータ
	$l_sess_data_id		= "";					// DATA_ID
	
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
	function my_exception_compsend(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_compsend');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
/*
	$l_token			= $_GET['token'];				// トークン
	$l_work_staff_id	= $_GET['nm_work_staff_id'];	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_from_addr		= $_GET['nm_from_addr'];		// 送信元メールアドレス
	if($l_from_addr == ''){$l_err_flag = false;}		// 送信元メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_to_addr			= $_GET['nm_to_addr'];			// 送信先メールアドレス
	if($l_to_addr == ''){$l_err_flag = false;}			// 送信先メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_cc_addr			= $_GET['nm_cc_addr'];			// CCメールアドレス
	$l_bcc_addr			= $_GET['nm_bcc_addr'];			// BCCメールアドレス
	$l_mail_title		= $_GET['nm_mail_title'];		// メールタイトル
	$l_mail_text		= $_GET['nm_mail_text'];		// メール本文
*/
	$l_token			= mb_convert_encoding($_GET['token']			,'UTF-8','UTF-8,SJIS,EUC-JP');		// トークン
	$l_work_staff_id	= mb_convert_encoding($_GET['nm_work_staff_id']	,'UTF-8','UTF-8,SJIS,EUC-JP');		// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}															// 作業人員IDが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_from_addr		= mb_convert_encoding($_GET['nm_from_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// 送信元メールアドレス
	//if($l_from_addr == ''){$l_err_flag = false;}																// 送信元メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_to_addr			= mb_convert_encoding($_GET['nm_to_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// 送信先メールアドレス
	if($l_to_addr == ''){$l_err_flag = false;}																	// 送信先メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_cc_addr			= mb_convert_encoding($_GET['nm_cc_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// CCメールアドレス
	$l_bcc_addr			= mb_convert_encoding($_GET['nm_bcc_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// BCCメールアドレス
	$l_mail_title		= mb_convert_encoding($_GET['nm_mail_title']	,'UTF-8','UTF-8,SJIS,EUC-JP');		// メールタイトル
	$l_mail_text		= mb_convert_encoding($_GET['nm_mail_text']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// メール本文
/*
	$l_token			= $_POST['token'];				// トークン
	$l_work_staff_id	= $_POST['nm_work_staff_id'];	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_from_addr		= $_POST['nm_from_addr'];		// 送信元メールアドレス
	if($l_from_addr == ''){$l_err_flag = false;}		// 送信元メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_to_addr			= $_POST['nm_to_addr'];			// 送信先メールアドレス
	if($l_to_addr == ''){$l_err_flag = false;}			// 送信先メールアドレスが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_cc_addr			= $_POST['nm_cc_addr'];			// CCメールアドレス
	$l_bcc_addr			= $_POST['nm_bcc_addr'];			// BCCメールアドレス
	$l_mail_title		= $_POST['nm_mail_title'];		// メールタイトル
	$l_mail_text		= $_POST['nm_mail_text'];		// メール本文
*/
	
	//print "l_show_page->".$l_show_page.":"."l_max_page->".$l_max_page.":"."l_num_to_show->".$l_num_to_show."<br>";
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	//print "l_token->".$l_token."<br>";
	// セッションチェック
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	//print "l_token->".$l_token."<br>";
	//print var_dump($lr_session);
	
	// DATA_IDを取得
	$l_sess_data_id = $lr_session["DATA_ID"];
	if($l_sess_data_id == ""){
		$l_err_flag = false;
	}
	
	if($l_debug_mode==1){
		//print_r($lr_session);
		//print "<br>";
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  メール送信
  ----------------------------------------------------------------------------*/
	require('lib/SendPHPMail.php');
	$lc_sgm = new SendPHPMail($l_sess_data_id);
	
	// メール設定
	$l_send_addr = $l_to_addr;
	
	if($l_cc_addr == "" || is_null($l_cc_addr)){
	}else {
		$l_send_addr		.= ",".$l_cc_addr;
	}
	
	if($l_bcc_addr == "" || is_null($l_bcc_addr)){
	}else {
		$l_send_addr		.= ",".$l_bcc_addr;
	}
	
	$lc_sgm->setFromaddr($l_from_addr);
	$lc_sgm->setSendaddr($l_send_addr);
	$lc_sgm->setCcAddress($l_cc_addr);
	$lc_sgm->setBccAddress($l_bcc_addr);
	$lc_sgm->setSubject($l_mail_title);
	$lc_sgm->setBody($l_mail_text);
	$lc_sgm->setToAddress($l_to_addr);

	
	// 送信ログ用データセット
	$lc_sgm->setLogDataId($lr_session['DATA_ID']);
	$lc_sgm->setLogSendUserId($lr_session['USER_ID']);
	$lc_sgm->setLogUserId($lr_session['USER_ID']);
	$lc_sgm->setSendPurpose("作業詳細修正補足");
	
	// メール送信
	$l_result = $lc_sgm->doSend();
	
	if ($l_result > 0){
		throw new Exception("メール送信に失敗しました");
	}
	
	if($l_debug_mode==1){print("Step-メール送信");print "<br>";}
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
	
	// タイトル
	$lc_smarty->assign("headtitle",			"送信完了");
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// メッセージ
	if($l_result){
		// 送信成功の場合
		$lc_smarty->assign("main_message",	"メッセージを送信しました。");
	}else{
		// 送信失敗の場合
		$lc_smarty->assign("main_message",	"メール送信に失敗しました。アドレス等を確認し、再度送信してください。");
	}
	
	// OKボタンのリンク先
	$lc_smarty->assign("form_action"	, "completiondetail.php");
	
	// パラメータ
	$lr_hidden_param = array(
							array(
									"name"	=> "token",
									"value"	=> $l_token
								),
							array(
									"name"	=> "gv_work_staff_id",
									"value"	=> $l_work_staff_id
								)
						);
	$lc_smarty->assign("ar_param"		, $lr_hidden_param);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright"	, $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileMessage.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>