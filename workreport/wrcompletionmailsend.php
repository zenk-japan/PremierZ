<?php

/******************************************************************************
 ファイル名：wrcompletionmailsend.php
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
		//print_r($_REQUEST);
		print_r($_POST);
		//print "step1<br>";
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
	$l_err_flag			= 0;					// エラーフラグ
	$lr_hidden_param	= "";					// OKボタンのリンク先に渡すパラメータ
	$l_post_token		= "";					// POSTされたトークン
	$l_sess_token		= "";					// セッションで保持しているトークン
	$l_user_name		= "";					// セッションで保持しているユーザー名
	$l_data_id			= "";					// 画面にセットするDATA_ID
	$l_error_type_st	= "ST";					// エラータイプ(ST:セッション断)
	$l_input_check		= 0;
	
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
	function my_exception_mainmenu(Exception $e){
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
	set_exception_handler('my_exception_mainmenu');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= mb_convert_encoding($_POST['nm_token_code']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// トークン
	$l_work_staff_id	= mb_convert_encoding($_POST['nm_selected_workstaff_id']		,'UTF-8','UTF-8,SJIS,EUC-JP');	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = 1;}	// 作業人員IDが取得できない場合はエラー(前画面に戻る際に使用する)
	$l_from_addr		= mb_convert_encoding($_POST['nm_from_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// 送信元メールアドレス
	$l_to_addr			= mb_convert_encoding($_POST['nm_to_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// 送信先メールアドレス
	$l_cc_addr			= mb_convert_encoding($_POST['nm_cc_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// CCメールアドレス
	$l_bcc_addr			= mb_convert_encoding($_POST['nm_bcc_addr']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// BCCメールアドレス
	$l_mail_title		= mb_convert_encoding($_POST['nm_mail_title']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// メールタイトル
	$l_mail_text		= mb_convert_encoding($_POST['nm_mail_text']		,'UTF-8','UTF-8,SJIS,EUC-JP');		// メール本文
	
	//print "l_show_page->".$l_show_page.":"."l_max_page->".$l_max_page.":"."l_num_to_show->".$l_num_to_show."<br>";
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/

	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		$l_err_flag = 1;
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		$l_err_flag = 1;
	}
	// データーIDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		$l_err_flag = 1;
	}
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_user_id == ""){
		$l_err_flag = 1;
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	if($l_user_name == ""){
		$l_err_flag = 1;
	}
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
	
	// トークン一致の確認
	if($l_post_token != $l_sess_token){
		$l_err_flag = 1;
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if($l_err_flag == 1){
		throw new Exception($l_error_type_st);
	}

/*----------------------------------------------------------------------------
  入力不足チェック
  ----------------------------------------------------------------------------*/
  	if ($l_to_addr == "" or $l_mail_title == "" or $l_mail_text == ""){
  		$l_input_check = 1;
  	}
/*----------------------------------------------------------------------------
  メール送信
  ----------------------------------------------------------------------------*/
	if ($l_input_check === 0){
	// 入力チェックOKの場合のみ送信
		require_once('../lib/SendPHPMail.php');
		$lc_sgm = new SendPHPMail($l_data_id);
		
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
		$lc_sgm->setLogDataId($l_data_id);
		$lc_sgm->setLogSendUserId($l_user_id);
		$lc_sgm->setLogUserId($l_user_id);
		$lc_sgm->setSendPurpose("作業詳細修正補足");
		
		// メール送信

		$l_result = $lc_sgm->doSend();

		if ($l_result > 0){
			throw new Exception("メール送信に失敗しました");
		}
	}
	if($l_debug_mode==1){print("Step-メール送信");print "<br>";}
/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";
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
							DIR_CSS."v_workreport_common.css",
							DIR_CSS."v_workreport_mailsend.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js",
							DIR_JS."jfnc_common.js",
							DIR_JS."jfunctions.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
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
	
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	
	// タイトル
	$lc_smarty->assign("headtitle",			"送信完了");
	$lc_smarty->assign("user_auth",		$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name",		$l_user_name);
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// メッセージ
	if ($l_result == 0 and $l_input_check === 0){
		// 送信成功の場合
		$lc_smarty->assign("main_message",	"メッセージを送信しました。");
	}else if ($l_result != 0 and $l_input_check === 0){
		// 送信失敗の場合
		$lc_smarty->assign("main_message",	"メール送信に失敗しました。アドレス等を確認し、再度送信してください。");
	}else{
		// 入力エラーの場合
		$lc_smarty->assign("main_message",	"メール送信に失敗しました。本文を入力して下さい。");
	}
	
	// OKボタンのリンク先
	$lc_smarty->assign("form_action"	, "wrcompletiondetail.php");
	
	// パラメータ
	$lr_hidden_param = array(
							array(
									"name"	=> "nm_token_code",
									"value"	=> $l_token
								),
							array(
									"name"	=> "nm_selected_workstaff_id",
									"value"	=> $l_work_staff_id
								)
						);
	$lc_smarty->assign("ar_param"		, $lr_hidden_param);
	
	// コピーライト
	$lc_smarty->assign("txt_copyright"	, $copyright_text);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('workreport_mailsend.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>