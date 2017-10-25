<?php

/******************************************************************************
 ファイル名：wrcontentslist.php
 処理概要  ：作業一覧画面(TOP)
 GET受領値：
             token                      トークン(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 1;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_REQUEST);
		print "<br>\n";
		print "Step-Debug-mode<br>\n";
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
	$guid				= NULL;
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	
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
	
	$l_terminal			= $l_connec_terminal['Terminal'];
	$l_model			= $l_connec_terminal['Model'];
	$l_identification	= $lc_cm->getUniqueId($l_terminal);
	$l_uid				= $l_identification['Uid'];
	$l_serial			= $l_identification['Serial'];
	$l_card				= $l_identification['Card'];
	
	if($l_terminal == TERMINAL_DOCOMO){$guid = "&guid=ON";}
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
	
	if($l_debug_mode==1){
		print "Step-キャリア判別<br>\n";
		print "端末取得<br>\n";
		print "&nbsp;".$l_terminal."<br>&nbsp;/".$l_model."<br>&nbsp;/".$l_uid."<br>&nbsp;/".$l_serial."<br>&nbsp;/".$l_card."<br>\n";
		print "USER_AGENT<br>\n";
		print "&nbsp;".$_SERVER['HTTP_USER_AGENT']."<br>\n";
	}
	
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
	$l_token			= $_POST['nm_token_code'];				// トークン
	
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	/*
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
	
	if($l_debug_mode==1){
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	*/
	
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		$l_err_flag = false;
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		$l_err_flag = false;
	}
	if($l_post_token != $l_sess_token){
		$l_err_flag = false;
	}
	
	// 権限の取得
	$l_authority = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_user_id == ""){
		$l_err_flag = false;
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		$l_err_flag = false;
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($lr_spdesc, $l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_users.php');
	$lc_users = new m_users();
	
	$lr_users = $lc_users->getUsersList($l_user_id);
	
	if($l_debug_mode==1){
		print count($lr_users)."<br>";
		print_r($lr_users);
		print "<br>";
	}
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	// ボタン押下判定
	
	if($_POST[update_user_switch]!="ON"){
		$name = str_replace(' ', '', str_replace('　', '', htmlspecialchars($lr_users[1][NAME])));
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		= 	array(
												"caption"	=> "【お名前】",
												"name"		=> "NAME",
												"type"		=> INPUT_TYPE_DISP,
												"value"		=> $name."&nbsp;様"
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		= 	array(
												"caption"	=> "【ユーザコード】",
												"name"		=> "USER_CODE",
												"type"		=> INPUT_TYPE_DISP,
												"value"		=> htmlspecialchars($lr_users[1][USER_CODE])
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【パスワード】",
												"name"		=> "PASSWORD",
												"type"		=> INPUT_TYPE_PASSWORD,
												"value"		=> htmlspecialchars($lr_users[1][PASSWORD]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【郵便番号】",
												"name"		=> "ZIP_CODE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][ZIP_CODE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【住所】",
												"name"		=> "ADDRESS",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][ADDRESS]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【最寄駅】",
												"name"		=> "CLOSEST_STATION",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][CLOSEST_STATION]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【自宅電話番号】",
												"name"		=> "HOME_PHONE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][HOME_PHONE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【自宅メールアドレス】",
												"name"		=> "HOME_MAIL",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][HOME_MAIL]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【携帯電話番号】",
												"name"		=> "MOBILE_PHONE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][MOBILE_PHONE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【携帯メールアドレス】",
												"name"		=> "MOBILE_PHONE_MAIL",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][MOBILE_PHONE_MAIL]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		if($l_terminal == TERMINAL_DOCOMO || $l_terminal == TERMINAL_SOFTBANK || $l_terminal == TERMINAL_AU ){
			$show_rec_cnt++;
			$lr_show_rec[$show_rec_cnt]	=	array(
												"caption"	=> "【簡単ログイン】",
												"name"		=> "IDENTIFICATION_FLAG",
												"type"		=> INPUT_TYPE_RADIO,
												"value"		=> htmlspecialchars($lr_users[1][IDENTIFICATION_FLAG])
											);
		}
		
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"name"		=> "DATA_ID",
												"type"		=> INPUT_TYPE_HIDDEN,
												"value"		=> htmlspecialchars($lr_users[1][DATA_ID])
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"name"		=> "USER_ID",
												"type"		=> INPUT_TYPE_HIDDEN,
												"value"		=> htmlspecialchars($lr_users[1][USER_ID])
											);
		
		// 送信ボタン
		$l_btn_rec_cnt++;
		$lr_btn_rec[$l_btn_rec_cnt]	=		array(
												"name"		=> "bt_send",
												"type"		=> INPUT_TYPE_SUBMIT,
												"value"		=> "送信"
											);
		$l_switch = "ON";
	} else {
		// 入力データ
		foreach($_POST as $key => $i_val){
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => $key, "Input_val" => $i_val);
		}
		
		print_r($input_data);
		
		// USERS更新
		$l_msg = $lc_users->upUsersList($l_user_id, $input_data, $l_uid);
		
		if($l_msg[RETERN_CODE] == RETURN_NOMAL){
			$lr_show_rec = array(
								array(
									"type"		=> INPUT_TYPE_COMMENT,
									"value"		=> $l_msg[RETERN_MSG]
								)
			);
		} else {
			foreach($l_msg as $key => $e_msg){
				if($e_msg != RETURN_ERROR){
					$l_msg_cnt++;
					$lr_show_rec[$l_msg_cnt] =	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> $e_msg
												);
				}
			}
		}
		
		// 前画面へ戻る
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt] =	array(
										"type"		=> INPUT_TYPE_COMMENT,
										"value"		=> "<br>&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?token=".$l_token.$guid."\" ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 2).">"."戻る</a>"
									);
	}
	
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
	$ar_css_files	= array(DIR_CSS."v_top_block.css", DIR_CSS."v_workreport_userssetup.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", DIR_JS."jfnc_common.js");

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
	$lc_smarty->assign("doctype",			$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	$lc_smarty->assign("ar_js_files",		$ar_js_files);				// jsファイル
	$lc_smarty->assign("ar_css_files",		$ar_css_files);				// CSSファイル
	
	// タイトル
	$lc_smarty->assign("headtitle",			SCREEN_ZSMM0010);
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	$lc_smarty->assign("headinfo",			"");
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF']."?token=".$l_token.$guid);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// ユーザ情報
	$lc_smarty->assign("ar_users",		$lr_show_rec);
	$lc_smarty->assign("token",				$l_token);
	$lc_smarty->assign("ar_users_btn",		$lr_btn_rec);
	$lc_smarty->assign("ar_users_rec",			$lr_users[1]);
	
	// ハイパーリンク
	$lr_bottom_menu	=	array(
							array(
								"link_url"	=> "wrworkcontents.php?token=".$l_token,
								"value"		=> SCREEN_ZSMM0020."へ戻る",
								"key"		=> "0"
							),
							array(
								"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token.$guid,
								"value"		=> "ページ更新",
								"key"		=> "5"
							),
							array(
								"link_url"	=> "wrlogout.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC002,
								"key"		=> "9"
							),
							array(
								"link_url"	=> DIR_MAN."index.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC999,
								"key"		=> "#"
							)
						);
	
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	
	// 隠し項目
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_token
								),
							array(
								  "name"	=> "update_user_switch"
								, "value"	=> $l_switch
								),
							array(
								  "name"	=> "USER_ID"
								, "value"	=> $l_user_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('workreport_userssetup.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>