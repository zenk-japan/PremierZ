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


require_once('../lib/CommonStaticValue.php');
require_once('../lib/CommonFunctions.php');
require_once('../lib/MailSettings.php');
// *****************************************************************************
// 処理概要：警告メールの送信
//           作業予定時刻になっても報告のない作業者にメールを送信する
// *****************************************************************************
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
		print "session-><br>";
		print var_dump($_SESSION);
		print "<br>";
	}
	
	// 変数定義
	$l_dbrec						= NULL;											// DBレコード
	$dbobj							= NULL;											// DBオブジェクト
	$l_dispatch_sche				= NULL;											// 外出予定時刻
	$l_entering_sche				= NULL;											// 入店予定時刻
	$l_leave_sche					= NULL;											// 退店予定時刻
	$l_work_name					= NULL;											// 作業名
	$l_work_base_name				= NULL;											// 作業拠点名
	$l_mail_work_date				= NULL;											// 作業日
	$l_data_id_name					= "DATA_ID";									// DATA_ID項目名
	$l_work_name_name				= "WORK_NAME";									// 作業名項目名
	$l_work_base_name_name			= "WORK_BASE_NAME";								// 作業拠点名項目名
	$l_dispatch_sche_name			= "DISPATCH_SCHEDULE_TIMET";					// 外出予定時刻項目名
	$l_entering_sche_name			= "ENTERING_SCHEDULE_TIMET";					// 入店予定時刻項目名
	$l_leave_sche_name				= "LEAVE_SCHEDULE_TIMET";						// 退店予定時刻項目名
	$l_dispatch_flag_name			= "DISPATCH_DELAY_FLAG";						// 外出遅延フラグ項目名
	$l_entering_flag_name			= "ENTERING_DELAY_FLAG";						// 入店遅延フラグ項目名
	$l_leave_flag_name				= "LEAVE_DELAY_FLAG";							// 退店遅延フラグ項目名
	$l_send_to_name					= "WORK_USER_NAME";								// 宛先項目名
	$l_send_to_home_name			= "WORK_HOME_MAIL";								// 自宅アドレス項目名
	$l_send_to_mobile_name			= "WORK_MOBILE_PHONE_MAIL";						// 携帯アドレス項目名
	$l_send_to						= "WORK_USER_NAME";								// 宛先
	$l_send_to_home					= "WORK_HOME_MAIL";								// 自宅アドレス
	$l_send_to_mobile				= "WORK_MOBILE_PHONE_MAIL";						// 携帯アドレス
	$l_work_staff_id_name			= "WORK_STAFF_ID";								// 作業人員ID項目名
	$l_mail_work_date_name			= "WORK_DATE";									// 作業日
	$l_work_staff_id				= NULL;											// 作業人員ID
	$l_dispatch_delay_notified_name	= "DISPATCH_DELAY_NOTIFIED";					// 出発遅延通知項目名
	$l_entering_delay_notified_name	= "ENTERING_DELAY_NOTIFIED";					// 入店遅延通知項目名
	$l_leave_delay_notified_name	= "LEAVE_DELAY_NOTIFIED";						// 退店遅延通知項目名
	$l_mail_subject					= "【連絡依頼】株式会社zenk";					// メールタイトル
	$l_mail_buff					= NULL;											// メール本文用バッファ
	$l_mail_body_parts_RTN			= "\n";											// メール本文パーツ
	$l_parentheses_prefix			= "（";											// メール本文パーツ
	$l_parentheses_safix			= "）";											// メール本文パーツ
	$l_mail_body_parts_1    		= " 予定の、";									// メール本文パーツ
	$l_mail_body_parts_D			= "外出連絡が未着です。";						// メール本文パーツ
	$l_mail_body_parts_E			= "入店連絡が未着です。";						// メール本文パーツ
	$l_mail_body_parts_L			= "退店連絡が未着です。";						// メール本文パーツ
	$l_mail_body_parts_req			= "至急連絡をお願いいたします。";				// メール本文パーツ
	$l_dispatch_flag				= NULL;											// 外出遅延フラグ
	$l_entering_flag				= NULL;											// 入店遅延フラグ
	$l_leave_flag					= NULL;											// 退店遅延フラグ
	$l_send_flag					= NULL;											// 送信フラグ
	$l_log_buff						= NULL;											// ログバッファ
	$l_date							= NULL;											// システム日付
	$l_update_sql					= "UPDATE WORK_STAFF SET ";						// UPDATE文の共通部
	$l_data_id						= "";											// DATA_ID
	$sql							= NULL;											// フラグ更新用SQL
	$lr_weekday						= array( "日", "月", "火", "水", "木", "金", "土" );	// 曜日

	if($l_debug_mode==1){print("Step-変数定義");print "<br>";}
	
	// DB接続クラス呼び出し
	require_once('../mdl/Target_of_alert_v.php');
	$dbobj = new Target_of_alert_v();

	// レコード取得
	$l_dbrec = $dbobj->getRecord();

	if($l_debug_mode==1){print("Step-送信対象取得");print "<br><pre>";var_dump($l_dbrec);print "</pre><br>";}
	
	// レコードループ
	if(count($l_dbrec)>0){
		foreach($l_dbrec as $pkey=>$pvalue){
			// 変数初期化
			$l_dispatch_flag		= NULL;
			$l_entering_flag		= NULL;
			$l_leave_flag			= NULL;
			$l_send_flag			= 'N';
			$l_send_to				= NULL;
			$l_send_to_home			= NULL;
			$l_send_to_mobile		= NULL;
			$l_mail_buff			= NULL;
			$l_dispatch_sche		= NULL;
			$l_entering_sche		= NULL;
			$l_leave_sche			= NULL;
			$l_log_buff				= NULL;
			$l_work_staff_id		= NULL;							// 作業人員ID
			$l_data_id				= NULL;							// DATA_ID

			// レコード内のデータループ
			foreach($pvalue as $key=>$value){
				// 基本情報を取得
				if($key == $l_work_staff_id_name){
					$l_work_staff_id		= $value;		// 作業人員ID
				}else if($key == $l_send_to_name){
					$l_send_to				= $value;		// 宛先
				}else if($key == $l_send_to_home_name){
					$l_send_to_home			= $value;		// 自宅アドレス
				}else if($key == $l_send_to_mobile_name){
					$l_send_to_mobile		= $value;		// 携帯アドレス
				}else if($key == $l_dispatch_sche_name){
					$l_dispatch_sche		= substr($value, 8, 2).":".substr($value, 10, 2);		// 外出予定時刻
				}else if($key == $l_entering_sche_name){
					$l_entering_sche		= substr($value, 8, 2).":".substr($value, 10, 2);		// 入店予定時刻
				}else if($key == $l_leave_sche_name){
					$l_leave_sche			= substr($value, 8, 2).":".substr($value, 10, 2);		// 退店予定時刻
				}else if($key == $l_work_name_name){
					$l_work_name			= $value;		// 作業名
				}else if($key == $l_work_base_name_name){
					if(isset($value)){
						$l_work_base_name	= $l_parentheses_prefix.$value.$l_parentheses_safix;	// 作業拠点名
					}
				}else if($key == $l_mail_work_date_name){	// 作業日
					$l_mail_work_date		=	date("n/j", strtotime($value)).$l_parentheses_prefix.$lr_weekday[date("w", strtotime($value))].$l_parentheses_safix;
				// 各種フラグを取得
				}else if($key == $l_dispatch_flag_name){
					$l_dispatch_flag		= $value;		// 外出遅延フラグ
				}else if($key == $l_entering_flag_name){
					$l_entering_flag		= $value;		// 入店遅延フラグ
				}else if($key == $l_leave_flag_name){
					$l_leave_flag			= $value;		// 退店遅延フラグ
				}else if($key == $l_data_id_name){
					$l_data_id				= $value;		// DATA_ID
				}
			}

			// 送信判定
			if($l_dispatch_flag == 'Y'){
				// 外出していない場合
				$l_send_flag = 'Y';
				$l_mail_buff = $l_mail_buff.$l_dispatch_sche.$l_mail_body_parts_1.$l_mail_body_parts_D.$l_mail_body_parts_RTN;
			}
			if($l_entering_flag == 'Y'){
				// 入店していない場合
				$l_send_flag = 'Y';
				$l_mail_buff = $l_mail_buff.$l_entering_sche.$l_mail_body_parts_1.$l_mail_body_parts_E.$l_mail_body_parts_RTN;
			}
			if($l_leave_flag == 'Y'){
				// 退店していない場合
				$l_send_flag = 'Y';
				$l_mail_buff = $l_mail_buff.$l_leave_sche.$l_mail_body_parts_1.$l_mail_body_parts_L.$l_mail_body_parts_RTN;
			}
			
			if($l_debug_mode==1){print("Step-送信判定:".$value.":".$l_send_flag);print "<br>";}

			if($l_send_flag == 'Y'){
				if($l_data_id != $l_last_data_id){
					// メール設定読込
					$lc_mails = new MailSettings($l_data_id);

					// 変数設定(メール系はDATA_ID取得後に行う)
					$l_update_user_id		= $lc_mails->getFlagUpdateUserID();		// 更新用のログインID
					$from_address			= $lc_mails->getMailAddr1();			// FROM送信先アドレス
					$to_address				= NULL;									// TO送信先アドレス
					$cc_address				= $lc_mails->getMailAddr2();			// CC送信先アドレス
					$bcc_address			= NULL;									// BCC送信先アドレス
					$l_send_to_addr			= NULL;									// 警告メール送信宛先アドレス
					$l_last_data_id			= $l_data_id;
				}

				// メール本文構築
				require_once('../lib/CommonMessage.php');
				$lc_cmmess = new CommonMessage();
				// ユーザーデータ組立
				$lr_user_data = "";
				$lr_user_data["DATA_ID"]			= $l_data_id;					// DATA_ID
				$lr_user_data["NAME"]				= $l_send_to;					// USER_NAME
				$lr_user_data["WORK_DATE_SHORT"]	= $l_mail_work_date;			// WORK_DATE_SHORT
				$lr_user_data["WORK_NAME"]			= $l_work_name;					// WORK_NAME
				$lr_user_data["DELAY_MESSAGE"]		= $l_mail_buff;					// DELAY_MESSAGE
				// MOBILE_URL
				
				
				
				// 宛先名
				$l_mail_subject	= $lc_cmmess->getDelayAlertMailTitle($lr_user_data);
				//print $l_mail_subject;
				// 宛先名
				$l_mail_buff	= $lc_cmmess->getDelayAlertMailMess($lr_user_data);
				//print $l_mail_buff;				
				
				// メール送信情報設定
				$name						= $l_send_to;					//宛先
				$work_home_mail				= $l_send_to_home;				//自宅メールアドレス
				$work_mobile_phone_mail		= $l_send_to_mobile;			//携帯メールアドレス

				// 送信アドレスはmobileがあればmobile、なければhomeに送る
				$l_send_to_addr = NULL;
				if(empty($work_mobile_phone_mail)){
					$l_send_to_addr = $work_home_mail;
				}else{
					$l_send_to_addr = $work_mobile_phone_mail;
				}

				// TOアドレス設定
				$to_address		=	$l_send_to_addr;

				$l_log_buff = "作業名：".$l_work_name." 作業拠点名：".$l_work_base_name." 作業者：".$name;// 自宅アドレス：".$work_home_mail." 携帯アドレス：".$work_mobile_phone_mail;
				$l_log_buff = $l_log_buff." 外出遅延：".$l_dispatch_flag;
				$l_log_buff = $l_log_buff." 入店遅延：".$l_entering_flag;
				$l_log_buff = $l_log_buff." 退店遅延：".$l_leave_flag;
				echo $l_log_buff."\n";

				$sql = $l_update_sql;
				try{
					require_once('../lib/SendPHPMail.php');
					$lc_sgm = new SendPHPMail($l_data_id);

					// From
					$lc_sgm->setFromaddr($from_address);
					// To
					$lc_sgm->setToAddress($to_address);
					// Cc
					$lc_sgm->setCcAddress($cc_address);
					// Subject
					$lc_sgm->setSubject($l_mail_subject);
					// Body
					$lc_sgm->setBody($l_mail_buff);

					// 送信ログ用データセット
					$lc_sgm->setLogDataId($l_data_id);
					$lc_sgm->setLogSendUserId($l_update_user_id);
					$lc_sgm->setLogUserId($l_update_user_id);
					$lc_sgm->setSendPurpose("遅延警告");
					
					if($l_debug_mode==1){print "Step-送信直前";}

					// メール送信
					$l_result = $lc_sgm->doSend();
					if ($l_result > 0){
						echo "メール送信に失敗しました。\n";
					}
					
					if($l_debug_mode==1){print "Step-送信直後";}

					// 作業人員表更新
					// WHOカラム更新
					$sql		.= "LAST_UPDATE_DATET = now() ";
					$sql		.= ",LAST_UPDATE_USER_ID = ".$l_update_user_id." ";
					// 送信フラグを更新
					if($l_dispatch_flag == 'Y'){
						// 出発遅延メール送信済みに設定
						$sql		.= ",".$l_dispatch_delay_notified_name." = 'Y' ";
					}
					if($l_entering_flag == 'Y'){
						// 入店遅延メール送信済みに設定
						$sql		.= ",".$l_entering_delay_notified_name." = 'Y' ";
					}
					if($l_leave_flag == 'Y'){
						// 退店遅延メール送信済みに設定
						$sql		.= ",".$l_leave_delay_notified_name." = 'Y' ";
					}
					$sql		.= "WHERE WORK_STAFF_ID = ".$l_work_staff_id." ";

					require_once('../mdl/CommonExecution.php');
					$dbobj = new CommonExecution();
					$dbobj->CommonSilentSQL($sql);
					echo "\n";

				}catch (Exception $e){
					echo "[error]";
					echo $e->getMessage()."\n";
				}
			}
		}
	}
?>