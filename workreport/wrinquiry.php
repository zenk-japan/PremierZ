<?php

/******************************************************************************
 ファイル名：wrinquiry.php
 処理概要  ：問い合せ
 GET受領値：
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_GET);
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
	$l_err_flag			= true;					// エラーフラグ
	
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
	function my_exception_complist(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_complist');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_users.php');
	$lc_users = new m_users();
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"type"		=> INPUT_TYPE_COMMENT,
											"value"		=> "<font size=\"2\" color=\"#ff0000\">ユーザコードと".SYSTEM_NAME."よりメール受信しているアドレス（自宅または携帯）をご入力ください。</font>"
										);
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"type"		=> INPUT_TYPE_COMMENT,
											"value"		=> "<font size=\"2\" color=\"#ff0000\">ご入力されたアドレス宛てにパスワードを送信いたします。</font>"
										);
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"type"		=> "RETURN"
										);
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"caption"	=> "【ユーザコード】<font size=\"2\" color=\"#ff0000\">(必須)</font>",
											"name"		=> "USER_CODE",
											"type"		=> INPUT_TYPE_TEXT,
											"value"		=> $_POST[USER_CODE],
											"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
										);
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"caption"	=> "【自宅メールアドレス】<font size=\"2\" color=\"#ff0000\">(任意)</font>",
											"name"		=> "HOME_MAIL",
											"type"		=> INPUT_TYPE_TEXT,
											"value"		=> $_POST[HOME_MAIL],
											"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
										);
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]		=	array(
											"caption"	=> "【携帯メールアドレス】<font size=\"2\" color=\"#ff0000\">(任意)</font>",
											"name"		=> "MOBILE_PHONE_MAIL",
											"type"		=> INPUT_TYPE_TEXT,
											"value"		=> $_POST[MOBILE_PHONE_MAIL],
											"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
										);
	
	if(empty($_POST[bt_send])){
		// 送信ボタン
		$l_btn_rec_cnt++;
		$lr_btn_rec[$l_btn_rec_cnt]	=	array(
											"name"		=> "bt_send",
											"type"		=> INPUT_TYPE_SUBMIT,
											"value"		=> "送信"
										);
	} else {
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt]	=	array(
											"type"		=> "RETURN"
										);
		
		// 入力データ配列
		foreach($_POST as $key => $i_val){
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => $key, "Input_val" => $i_val);
		}
		
		// USERS検索およびメール送信
		$l_msg = $lc_users->inquiryPassword($input_data);
		
		if($l_msg[RETERN_CODE] == RETURN_NOMAL){
			$l_msg_cnt++;
			$lr_show_rec[$l_msg_cnt] =	array(
											"type"		=> INPUT_TYPE_COMMENT,
											"value"		=> $l_msg[RETERN_MSG]
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
			
			// 送信ボタン
			$l_btn_rec_cnt++;
			$lr_btn_rec[$l_btn_rec_cnt]	=		array(
													"name"		=> "bt_send",
													"type"		=> INPUT_TYPE_SUBMIT,
													"value"		=> "送信"
												);
		}
	}
	
	$l_msg_cnt++;
	$lr_show_rec[$l_msg_cnt]	=	array(
										"type"		=> "RETURN"
									);
	$l_msg_cnt++;
	/*
	$lr_show_rec[$l_msg_cnt]	=	array(
										"type"		=> INPUT_TYPE_COMMENT,
										"value"		=> "<font size=\"2\">※その他のお問い合わせは"."<a href=\"mailto:".RECRUIT_MAIL."\">こちら</a>"."をご利用ください。</font>"
									);
	*/
/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";
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
	$lc_smarty->assign("doctype",			$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	// タイトル
	$lc_smarty->assign("headtitle",			SCREEN_ZSMMC003);
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	$lc_smarty->assign("headinfo",			"");
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF']);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// ユーザ情報
	$lc_smarty->assign("ar_users",			$lr_show_rec);
	$lc_smarty->assign("token",				$l_token);
	$lc_smarty->assign("ar_users_btn",		$lr_btn_rec);
	
	// ハイパーリンク
	$lr_bottom_menu	=	array(
							array(
								"link_url"	=> "../page/login.php",
								"value"		=> SCREEN_ZSMMC001."画面へ戻る",
								"key"		=> "0"
							),
							array(
								"link_url"	=> DIR_MAN."index.php",
								"value"		=> SCREEN_ZSMMC999,
								"key"		=> "#"
							)
						);
	
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateInquiry.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>