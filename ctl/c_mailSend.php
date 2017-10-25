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
 ファイル名：c_mailSend.php
 処理概要  ：メール送信
 POST受領値：
             nm_token_code              トークン(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>\n";
		print var_dump($_POST);
		print "<br>\n";
		print "session-><br>\n";
		print var_dump($_SESSION);
		print "<br>\n";
	//	print "リクエスト:";
	//	print var_dump($_REQUEST);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<br>\n";	// メッセージ用接尾辞
	$l_html_rts			= "<br>\n";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_sess_data_id		= "";									// 画面にセットするDATA_ID
	$l_comp_name_cond	= "";									// 会社名(検索用)
	$l_group_name_cond	= "";									// グループ名(検索用)
	$l_user_name_cond	= "";									// ユーザー名(検索用)
	$l_show_page		= "";									// 表示ページ番号
	$l_max_page			= "";									// 最大ページ番号
	$l_selected_user_id	= "";									// POSTされたユーザーID
	$l_show_dtl_user_id	= "";									// 編集を表示するユーザーID
	$lr_dtl_rec			= "";									// 編集表示用のレコード
	
	$l_error_flag		= 0;									// エラーフラグ
	
	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_cmailsend(Exception $e){
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
	set_exception_handler('my_exception_cmailsend');
	
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
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}
	
	// トークンの取得
	$l_sess_token = $lc_sess->getToken();
	
	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	
	// セッションと_GETでトークンが一致しない場合は不正アクセスとみなす
	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
/*----------------------------------------------------------------------------
   POST値の取得
  ----------------------------------------------------------------------------*/
	foreach($_POST as $key => $post_val){
		$lr_data[$key] = $post_val;
	}
	
	// 送信アドレスの設定
	$l_to_addr = null;
	$l_send_addr = null;
	
	// To（自宅）
	if($lr_data["TO_HOME"] != ""){
		$l_to_addr = $lr_data["TO_HOME"];
		$l_send_addr = $lr_data["TO_HOME"];
	}
	
	// To（携帯）
	if($lr_data["TO_MOBILE"] != ""){
		if(is_null($l_to_addr)){
			$l_to_addr = $lr_data["TO_MOBILE"];
			$l_send_addr = $lr_data["TO_MOBILE"];
		}else{
			$l_to_addr .= ",".$lr_data["TO_MOBILE"];
			$l_send_addr .= ",".$lr_data["TO_MOBILE"];
		}
	}
	
	// Cc
	if($lr_data["CC"] != ""){
		$l_cc_addr = $lr_data["CC"];
		if(is_null($l_send_addr)){
			$l_send_addr = $lr_data["CC"];
		}else{
			$l_send_addr .= ",".$lr_data["CC"];
		}
	}
	
	// Bcc
	if($lr_data["BCC"] != ""){
		$l_bcc_addr = $lr_data["BCC"];
		if(is_null($l_send_addr)){
			$l_send_addr = $lr_data["BCC"];
		}else{
			$l_send_addr .= ",".$lr_data["BCC"];
		}
	}
	
	// 件名
	if($lr_data["SUBJECT"] != ""){
		$l_mail_title = $lr_data["SUBJECT"];
	}
	
	// 本文
	if($lr_data["BODY"] != ""){
		$l_mail_text = $lr_data["BODY"];
	}
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
	
/*----------------------------------------------------------------------------
  メール送信
  ----------------------------------------------------------------------------*/
	require_once('../lib/SendPHPMail.php');
	require_once('../lib/MailSettings.php');
	
	$lc_sgm = new SendPHPMail($l_sess_data_id);
	
	// メール設定読込
	$lc_mailset = new MailSettings($l_sess_data_id);
			
	// From
	$lc_sgm->setFromaddr($lc_mailset->getMailAddr1());
	// Send
	$lc_sgm->setSendaddr($l_send_addr);
	// To
	$lc_sgm->setToAddress($l_to_addr);
	// Cc
	$lc_sgm->setCcAddress($l_cc_addr);
	// Bcc
	$lc_sgm->setBccAddress($l_bcc_addr);
	// Subject
	$lc_sgm->setSubject($l_mail_title);
	// Body
	$lc_sgm->setBody($l_mail_text);
	
	// 送信ログ用データセット
	$lc_sgm->setLogDataId($l_sess_data_id);
	$lc_sgm->setLogSendUserId($l_sess_user_id);
	$lc_sgm->setLogUserId($l_sess_user_id);
	$lc_sgm->setSendPurpose("ログイン情報送信");
	
	// メール送信
	
	$l_result = $lc_sgm->doSend();
	
	if($l_debug_mode==1){print("Step-メール送信");print "<br>";}
	
	if ($l_result > 0){
		$l_error_flag = 1;
		$l_error_message .= "メール送信に失敗しました。\n";
	}
	
	if($l_debug_mode==1){print("Step-データ保存");print "\n";}
/*----------------------------------------------------------------------------
  終了処理
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		print "send nomal";
	}else{
		print $l_error_message;
	}
	return true;
?>
