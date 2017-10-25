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
 ファイル名：c_workRequestMailSend.php
 処理概要  ：作業依頼メール送信
 POST受領値：
             token             トークン(必須)
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
	$l_staff_id_prefix	= "work_staff_id";						// 送信対象の人員IDのPOST値のプレフィックス
	$l_home_mail_prefix	= "work_home_mail";						// 送信対象のPCアドレスのPOST値のプレフィックス
	$l_mobile_phone_mail_prefix	= "work_mobile_phone_mail";				// 送信対象の携帯アドレスのPOST値のプレフィックス

	$l_error_flag		= 0;									// エラーフラグ

	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_cwrmailsend(Exception $e){
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
	set_exception_handler('my_exception_cwrmailsend');

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
	$l_post_token = $_POST['token'];
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
	// 始めに送信対象数確定の為、人員ID、PCアドレス、携帯アドレスを取得する
	// 同時にその他の値を別途配列に格納し、最後に各人員のレコードにその他の値の配列を結合する
	$lr_mail_recored			= "";
	$lr_other_value				= "";
	$l_staff_id_cnt				= 1;
	$l_home_mail_cnt			= 1;
	$l_mobile_phone_mail_cnt	= 1;
	// 人員MDL
	require_once('../mdl/m_workstaff.php');

	foreach($_POST as $l_post_key => $l_post_val){
		if (strpos($l_post_key, $l_staff_id_prefix)!==false){
		// 人員ID
			$lr_mail_recored[$l_staff_id_cnt][$l_staff_id_prefix] = $l_post_val;

			// 検索条件設定
			$lr_db_cond_dtl = "";
			$lr_db_cond_dtl = array('WORK_STAFF_ID = '.$l_post_val);

			// レコード取得
			$lc_db_mdl = new m_workstaff('Y', $lr_db_cond_dtl);
			$lr_db_record = $lc_db_mdl->getViewRecord();
			// ユーザー名のセット
			$lr_mail_recored[$l_staff_id_cnt]["work_user_name"] = $lc_db_mdl->getMysqlEscapedValue($lr_db_record[1]["WORK_USER_NAME"]);
			// 作業費のセット
			$lr_mail_recored[$l_staff_id_cnt]["work_unit_price"] = $lr_db_record[1]["WORK_UNIT_PRICE_ORIG"];
			// 作業費表示フラグのセット
			$lr_mail_recored[$l_staff_id_cnt]["work_unit_price_display_flag"] = $lr_db_record[1]["WORK_UNIT_PRICE_DISPLAY_FLAG"];

			$l_staff_id_cnt++;

		}elseif (strpos($l_post_key, $l_home_mail_prefix)!==false){
		// PCアドレス
			$lr_mail_recored[$l_home_mail_cnt][$l_home_mail_prefix] = $l_post_val;
			$l_home_mail_cnt++;

		}elseif (strpos($l_post_key, $l_mobile_phone_mail_prefix)!==false){
		// 携帯アドレス
			$lr_mail_recored[$l_mobile_phone_mail_cnt][$l_mobile_phone_mail_prefix] = $l_post_val;
			$l_mobile_phone_mail_cnt++;

		}else{
			$lr_other_value[$l_post_key] = $l_post_val;
		}
	}
	foreach ($lr_mail_recored as $l_rec_num => $lr_rec){
		$lr_mail_recored[$l_rec_num] += $lr_other_value;
	}

/*----------------------------------------------------------------------------
   メール送信用データの作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonMessage.php');
	$lc_cmmess = new CommonMessage();

	foreach ($lr_mail_recored as $l_rec_num => $lr_rec){
		$l_to_addr = "";
		$l_send_addr = "";

		// To（PC）
		if($lr_rec["work_home_mail"] != ""){
			$l_to_addr = $lr_rec["work_home_mail"];
			$l_send_addr = $lr_rec["work_home_mail"];
		}

		// To（携帯）
		if($lr_rec["work_mobile_phone_mail"] != ""){
			if($l_to_addr == ""){
				$l_to_addr = $lr_rec["work_mobile_phone_mail"];
				$l_send_addr = $lr_rec["work_mobile_phone_mail"];
			}else{
				$l_to_addr .= ",".$lr_rec["work_mobile_phone_mail"];
				$l_send_addr .= ",".$lr_rec["work_mobile_phone_mail"];
			}
		}

		// Cc
		if($lr_rec["cc"] != ""){
			if($l_send_addr == ""){
				$l_send_addr = $lr_rec["cc"];
			}else{
				$l_send_addr .= ",".$lr_rec["cc"];
			}
		}

		// Bcc
		if($lr_rec["bcc"] != ""){
			if($l_send_addr == ""){
				$l_send_addr = $lr_rec["bcc"];
			}else{
				$l_send_addr .= ",".$lr_rec["bcc"];
			}
		}

		// 配列にアドレスを格納
		$lr_mail_recored[$l_rec_num]["to_addr"]		= $l_to_addr;
		$lr_mail_recored[$l_rec_num]["send_addr"]	= $l_send_addr;

		// 件名
		if($lr_rec["title"] != ""){
			// ユーザー名を置換して再格納
			$lr_mail_recored[$l_rec_num]["title"] = $lc_cmmess->getReplacedStrings($lr_rec["title"], $lc_cmmess->static_each_user, $lr_rec["work_user_name"]);
		}

		// 本文
		if($lr_rec["body"] != ""){

			$l_replaced_body = $lc_cmmess->getReplacedStrings($lr_rec["body"], $lc_cmmess->static_each_user, $lr_rec["work_user_name"]);
			if ($lr_rec["work_unit_price_display_flag"] == 'Y'){
				$l_replaced_body = $lc_cmmess->getReplacedStrings($l_replaced_body, $lc_cmmess->static_each_user_up, $lr_rec["work_unit_price"]);
			}else{
				$l_replaced_body = $lc_cmmess->getReplacedStrings($l_replaced_body, $lc_cmmess->static_each_user_up, "-");
			}
			$lr_mail_recored[$l_rec_num]["body"] = $l_replaced_body;
		}
	}

	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}

/*----------------------------------------------------------------------------
  メール送信
  ----------------------------------------------------------------------------*/
	require_once('../lib/MailSettings.php');
	require_once('../lib/SendPHPMail.php');

	foreach ($lr_mail_recored as $l_rec_num => $lr_rec){
		$lc_sgm = new SendPHPMail($l_sess_data_id);

		// メール設定読込
		$lc_mailset = new MailSettings($l_sess_data_id);

		// From
		$lc_sgm->setFromaddr($lc_mailset->getMailAddr1());
		// Send
		$lc_sgm->setSendaddr($lr_rec["send_addr"]);
		// To
		$lc_sgm->setToAddress($lr_rec["to_addr"]);
		// Cc
		$lc_sgm->setCcAddress($lr_rec["cc"]);
		// Bcc
		$lc_sgm->setBccAddress($lr_rec["bcc"]);
		// Subject
		$lc_sgm->setSubject($lr_rec["title"]);
		// Body
		$lc_sgm->setBody($lr_rec["body"]);

		// 送信ログ用データセット
		$lc_sgm->setLogDataId($l_sess_data_id);
		$lc_sgm->setLogSendUserId($l_sess_user_id);
		$lc_sgm->setLogUserId($l_sess_user_id);
		$lc_sgm->setSendPurpose("作業依頼");

		// メール送信
		$l_result = $lc_sgm->doSend();
		//print var_dump($l_result)."<br>";
		if($l_debug_mode==1){print("Step-メール送信");print "<br>";}

		if ($l_result > 0){
			$l_error_flag = 1;
			$l_error_message .= "メール送信に失敗しました。\n";
		}else{

			// メール送信フラグを更新する
			$lc_db_model = new m_workstaff();
			if(!$lc_db_model->updateTransmissionFlag($l_sess_user_id, $lr_rec["work_staff_id"])){
				$l_error_flag = 1;
			//	$l_error_message .= "データを更新できませんでした。";
			}

		}

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
