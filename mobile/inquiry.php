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
 ファイル名：inquiry.php
 処理概要  ：問い合せ
 GET受領値：
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	require_once('../lib/MailSettings.php');
	require_once('../lib/SendPHPMail.php');
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
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
	$l_mail_send_flag		= 0;
	$l_users_write_flag		= 0;
	$l_input_error_flag		= 0;
	
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
  POSTデータ確認
  ----------------------------------------------------------------------------*/
	$l_msg['status'] = RETURN_NOMAL;
	$l_mess_cnt = 0;
	
	// USER_CODEのキーが存在していて中身が空の場合はエラーとする
	if (array_key_exists('USER_CODE', $_POST)){
		if (trim($_POST['USER_CODE']) == ''){
			$l_msg['status'] = RETURN_ERROR;
			$l_msg['mess'][$l_mess_cnt++] = "ユーザコードを入力して下さい。";
			$l_input_error_flag = 1;
		}else{
			$l_user_code = $_POST['USER_CODE'];
		}
	}
	
	// USE_COMPANY_CODEのキーが存在していて中身が空の場合はエラーとする
	if (array_key_exists('USE_COMPANY_CODE', $_POST)){
		if (trim($_POST['USE_COMPANY_CODE']) == ''){
			$l_msg['status'] = RETURN_ERROR;
			$l_msg['mess'][$l_mess_cnt++] = "利用会社を入力して下さい。";
			$l_input_error_flag = 1;
		}else{
			$l_use_company_code = $_POST['USE_COMPANY_CODE'];
		}
	}
	
	// ユーザーデータ取得
	if ($_POST['bt_send'] != '' && $l_msg['status'] == RETURN_NOMAL){
		require_once('../lib/PasswordProduction.php');
		$lc_pprod = new PasswordProduction('', $l_user_code, $l_use_company_code);
		$lr_users = array();
		$lr_users = $lc_pprod->getUserRec();

		// 携帯メールアドレスが無い場合はエラーとする
		if ($lr_users[1]['MOBILE_PHONE_MAIL'] == ''){
			$l_msg['status'] = RETURN_ERROR;
			$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">携帯アドレスの登録がありませんでした。</font>";
			$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">ユーザーコード、利用会社が間違っていないか確認して下さい。</font>";
			$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">問題が解決しない場合は管理者に連絡をして下さい。</font>";
			$l_input_error_flag = 1;
		}else{
			// 携帯メールにパスワードリセット依頼用URLを送付する
			$lc_pprod->sendPasswordResetRequiestURL(2);
			
			// 結果取得
			$l_mail_send_flag = $lc_pprod->getMailsendFlag();
			$l_users_write_flag = $lc_pprod->getUserwriteFlag();
			
		}
	}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	if ($_POST['bt_send'] == "" or $l_input_error_flag == 1){
		// 初めて開いた、または入力値に問題があった
	
	}else{
		if ($l_mail_send_flag === 1){
			if ($l_users_write_flag === 1){
			// リセット依頼URLの通知完了の場合
				$l_msg_cnt++;
				$lr_show_rec[$l_msg_cnt]		=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "<font size=\"2\" color=\"#ff0000\">登録済みの携帯アドレスにパスワードリセット依頼用URLを送付しました。</font>"
													);
				$l_msg_cnt++;
				$lr_show_rec[$l_msg_cnt]		=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "<font size=\"2\" color=\"#ff0000\">メールを確認し、パスワードリセット依頼を行ってください。</font>"
													);
			}else{
			// USERS登録失敗の場合
				$l_msg['status'] = RETURN_ERROR;
				$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">システム上のエラーが発生し、パスワードのリセットが行えませんでした。</font>";
				$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">管理者に連絡し、対応を依頼して下さい。</font>";
			}
		}else{
			// メール送信失敗の場合
			$l_msg['status'] = RETURN_ERROR;
			$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">システム上のエラーが発生し、パスワードのリセットが行えませんでした。</font>";
			$l_msg['mess'][$l_mess_cnt++] = "<font size=\"2\">管理者に連絡し、対応を依頼して下さい。</font>";
		}
	}
	
	// メール送信失敗または初回の場合は入力欄を表示
	if ($l_mail_send_flag != 1){
		// リセット依頼URLの通知未完了の場合
		
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt]		=	array(
												"type"		=> INPUT_TYPE_COMMENT,
												"value"		=> "<font size=\"2\" color=\"#ff0000\">登録済みの携帯アドレスにパスワードリセット依頼用URLを送付します。</font>"
											);
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt]		=	array(
												"type"		=> INPUT_TYPE_COMMENT,
												"value"		=> "<font size=\"2\" color=\"#ff0000\">ユーザーコードと利用会社を入力し、送信ボタンをクリックして下さい。</font>"
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
												"value"		=> $_POST['USER_CODE'],
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt]		=	array(
												"caption"	=> "【利用会社】<font size=\"2\" color=\"#ff0000\">(必須)</font>",
												"name"		=> "USE_COMPANY_CODE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> $_POST['USE_COMPANY_CODE'],
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		if($l_debug_mode==1){print("Step-利用会社まで表示");print "<br>";}
		
		// 警告メッセージがある場合はここに表示
		if ($l_msg['status'] == RETURN_ERROR){
			$l_msg_cnt++;
			$lr_show_rec[$l_msg_cnt]	=	array(
												"type"		=> "RETURN"
											);
											
			
			foreach($l_msg['mess'] as $key => $e_msg){
				$l_msg_cnt++;
				$lr_show_rec[$l_msg_cnt] =	array(
												"type"		=> INPUT_TYPE_COMMENT,
												"value"		=> "<font size=\"2\">$e_msg</font>"
											);
			}
		}
		
		if($l_debug_mode==1){print("Step-警告メッセージまで表示");print "<br>";}

		// 送信ボタン
		$l_btn_rec_cnt++;
		$lr_btn_rec[$l_btn_rec_cnt]	=	array(
											"name"		=> "bt_send",
											"type"		=> INPUT_TYPE_SUBMIT,
											"value"		=> "送信"
											);
		
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt]	=	array(
											"type"		=> "RETURN"
										);
		$l_msg_cnt++;
	}
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
								"link_url"	=> "login.php",
								"value"		=> SCREEN_ZSMMC001."画面へ戻る",
								"key"		=> "0"
							)/*,
							array(
								"link_url"	=> DIR_MAN."index.php",
								"value"		=> "操作マニュアル",
								"key"		=> "#"
							)*/
						);
	
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateInquiry.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>