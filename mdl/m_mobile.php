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
	class m_mobile {
		private $ar_condition;					// 検索条件配列
		private $ar_orderby;					// order by配列
		
		// 一覧表示
		function mobile_workstaff_list(&$w_workstaff){
			
			require_once('../mdl/Workstaff_v.php');
			$dbobj = new Workstaff_v();
			
			// 検索キーの設定
			$dataid							=	$_GET["did"];						//データID
			$work_user_id					=	$_GET["uid"];						//作業者ID
			$validityflag					=	'Y';								//有効フラグ(Yのみ)
			$canceldivision					=	'WR';								//キャンセル区分(WRのみ)
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> "'".$dataid."'",
									"WORK_USER_ID"		=> "'".$work_user_id."'",
									"CANCEL_DIVISION"	=> $canceldivision,
									"VALIDITY_FLAG"		=> $validityflag,
									"WORK_DATE"			=> ">".date( "Y-m-d"),
									"LEAVE_STAFF_TIMET" => ""
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array(
									"WORK_DATE"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_workstaff = $dbobj->getRecord();
		}
		// 作業詳細表示
		function mobile_workstaff_details(&$w_workstaff_d){
			require_once('../mdl/Workstaff_v.php');
			$dbobj = new Workstaff_v();
			
			// 検索キーの設定
			$dataid							=	$_GET["did"];						//データID
			$work_staff_id					=	$_GET["wsid"];						//作業者ID
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> "'".$dataid."'",
									"WORK_STAFF_ID"		=> "'".$work_staff_id."'"
						);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$w_workstaff_d = $dbobj->getRecord();
		}
		
		// 出発、入店、退店時間登録処理
		function mobile_workstaff_approval($p_data_id, $p_user_id, $p_staff_id){
			
			// Update対象テーブル名
			$table_name = SCHEMA_NAME.".WORK_STAFF";
			
			// SQL文生成用配列
			$sql = null;
			
			$sql .= "update ".$table_name." "."set ";
			
			// 更新部分
			$sql .= "APPROVAL_DIVISION = 'UA'";
			
			// 共通部分
			$sql .= ",LAST_UPDATE_DATET = now() ";
			$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			
			// 更新キーの設定
			$sql .= "where DATA_ID = '".$p_data_id."' ";
			$sql .= "  and WORK_STAFF_ID = '".$p_staff_id."'; ";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// 出発、入店、退店時間登録処理
		function Update_Time($p_data_id, $p_user_id, $p_staff_id, $p_break_time, $p_remarks, $p_button_mode, $p_button_name, &$e_msg){
			
			// Update対象テーブル名
			$table_name = SCHEMA_NAME.".WORK_STAFF";
			
			// SQL文生成用配列
			$sql = null;
			
			$sql .= "update ".$table_name." "."set ";
			
			// 特有部分
			$sql .= $p_button_mode. " = now() ";
			if(is_null($p_break_time)){
			} else {
				$sql .= ",BREAK_TIME = '".$p_break_time."' ";
			}
			if(is_null($p_remarks)){
			} else {
				$sql .= ",REMARKS = '".$p_remarks."' ";
			}
			
			// 共通部分
			$sql .= ",LAST_UPDATE_DATET = now() ";
			$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			
			// 更新キーの設定
			$sql .= "where DATA_ID = '".$p_data_id."' ";
			$sql .= "  and WORK_STAFF_ID = '".$p_staff_id."'; ";
			
			switch($p_button_mode){
				case "DISPATCH_STAFF_TIMET":
					$e_msg	=	"[".date("H:i")."]を出発時間として登録しました。";
				 	break;
				case "ENTERING_STAFF_TIMET":
					$e_msg	=	"[".date("H:i")."]を入店時間として登録しました。";
					break;
				default:
					break;
			}
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// 承認、時間登録処理
		function Update_Time_Approval(&$e_msg){
			
			$p_table_name	= "WORK_STAFF";
			$sql_type		= "UPDATE";
			$sql			= null;
			$sql_column		= null;
			$sql_data		= null;
			
			//入力項目をチェックしSQLを作成するファンクションの呼び出し
			require_once('../lib/MobileCheck.php');
			$dbcheck = new MobileCheck();
			$sql = $dbcheck->DataCheck($p_table_name,$sql_type,$e_msg);
			
			if ( $sql == null ){
			} else {
				// 共通部分
				$sql[0]		.= ",LAST_UPDATE_DATET = now() ";
				$sql[0]		.= ",LAST_UPDATE_USER_ID = '".$_GET["uid"]."' ";
				
				//検索条件の指定
				$sql[0]		.= $sql[2];
				$sql		= $sql[0];
			}
			
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// 設定情報検索
		function mobile_userssetup(&$w_userssetup){
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 検索キーの設定
			$dataid							=	$_GET["did"];						//データID
			$userid							=	$_GET["uid"];						//作業者ID
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> "'".$dataid."'",
									"USER_ID"			=> "'".$userid."'"
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$w_userssetup = $dbobj->getRecord();
		}
		
		// 承認、時間登録処理
		function mobile_userssetup_Update($p_data_id, $p_user_id, $p_name, $p_user_code, $p_password, $p_zip_code, $p_address, $p_closest_station, $p_home_phone, $p_home_mail, $p_mobile_phone, $p_mobile_phone_mail, &$e_msg){
			
			$p_table_name	= "USERS";
			$sql_type		= "UPDATE";
			$sql			= null;
			$sql_column		= null;
			$sql_data		= null;
			
			//入力項目をチェックしSQLを作成するファンクションの呼び出し
			require_once('../lib/MobileCheck.php');
			$dbcheck = new MobileCheck();
			$sql = $dbcheck->DataCheck($p_table_name,$sql_type,$e_msg);
			
			if ( $sql == null ){
			} else {
				// 共通部分
				$sql[0]		.= ",LAST_UPDATE_DATET = now() ";
				$sql[0]		.= ",LAST_UPDATE_USER_ID = '".$_GET["uid"]."' ";
				
				//検索条件の指定
				$sql[0]		.= $sql[1];
				$sql[0]		.= $sql[2];
				$sql		= $sql[0];
			}
			
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// 作業状況表示
		function mobile_worksituation(&$w_worksituation){
			require_once('../mdl/Workstaff_v.php');
			$dbobj = new Workstaff_v();
			
			// 検索キーの設定
			$dataid								=	$_GET["did"];					//データID
			$validityflag						= 'Y';								//有効フラグ(Yのみ)
			
			//作業日
			if(is_null($_GET["WORK_DATE"])){
				$workdate						=	date( "Y-m-d");
			} else {
				$workdate						=	$_GET["WORK_DATE"];
			}
			
			//作業名
			if($_GET["WORK_NAME"] == ""){
				$workname						=	"";
				$specified_order				= "WORK_DATE".","."WORK_NAME";
			} else {
				$workname						=	$_GET["WORK_NAME"];
				$specified_order				= "WORK_NAME".","."WORK_DATE";
			}
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> "'".$dataid."'",
									"WORK_DATE"			=> "%".$workdate."%",
									"WORK_NAME"			=> "%".$workname."%",
									"VALIDITY_FLAG"		=> $validityflag
						);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array(
								//	"WORK_NAME",
								//	"WORK_DATE",
									$specified_order,
									"WORK_USER_NAME"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_worksituation = $dbobj->getRecord();
		}
		
		// 作業状況詳細表示
		function mobile_worksituation_detail(&$w_worksituation_d){
			require_once('../mdl/Workstaff_v.php');
			$dbobj = new Workstaff_v();
			
			// 検索キーの設定
			$dataid							=	$_GET["did"];						//データID
			$workstaffid					=	$_GET["wsid"];						//作業者ID
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> "'".$dataid."'",
									"WORK_STAFF_ID"		=> "'".$workstaffid."'"
						);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$w_worksituation_d = $dbobj->getRecord();
		}
		
		// 出発、入店、退店時間代理登録処理
		function Stead_Update_Time($p_data_id, $p_user_id, $p_staff_id, $p_break_time, $p_remarks, $p_button_mode, $p_button_name, &$e_msg, &$e_msg2){
			
			$p_table_name	= "WORK_STAFF";
			$sql_type		= "UPDATE";
			$sql			= null;
			$sql_column		= null;
			$sql_data		= null;
			
			if($_GET[ST_UPDATE_TIME] ==""){
				$e_msg = "代理登録を行う時間をmm:ss形式で入力してください。";
			} else {
				//入力項目をチェックしSQLを作成するファンクションの呼び出し
				require_once('../lib/MobileCheck.php');
				$dbcheck = new MobileCheck();
				$sql = $dbcheck->DataCheck($p_table_name,$sql_type,$e_msg);
				
				if($sql == null){
				}else{
					// 共通部分
					$sql[0]		.= ",LAST_UPDATE_DATET = now() ";
					$sql[0]		.= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
					
					//検索条件の指定
					$sql[0]		.= $sql[2];
					$sql		= $sql[0];
					
					require_once('../mdl/CommonExecution.php');
					$dbobj = new CommonExecution();
					$dbobj->CommonSilentSQL($sql);
					
					switch($p_button_mode){
						case "DISPATCH_STAFF_TIMET":
							$e_msg2	=	"出発時間を代理で登録しました。";
						 	break;
						case "ENTERING_STAFF_TIMET":
							$e_msg2	=	"入店時間を代理で登録しました。";
							break;
						case "LEAVE_STAFF_TIMET":
							$e_msg2	=	"退店時間を代理で登録しました。";
							break;
						default:
							break;
					}
				}
				
				return $sql;
			}
		}
		
		// キャンセル区分登録処理
		function mobile_cancel_update($p_data_id, $p_user_id, $p_staff_id, $p_cancel_mode ){
			
			// Update対象テーブル名
			$table_name = SCHEMA_NAME.".WORK_STAFF";
			
			// SQL文生成用配列
			$sql = null;
			$sql .= "update ".$table_name." "."set ";
			
			// 特有部分
			$sql .= "CANCEL_DIVISION = '".$p_cancel_mode."' ";
			
			// 共通部分
			$sql .= ",LAST_UPDATE_DATET = now() ";
			$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			
			// 更新キーの設定
			$sql .= "where DATA_ID = '".$p_data_id."' ";
			$sql .= "  and WORK_STAFF_ID = '".$p_staff_id."'; ";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// 問い合せ
		function mobile_inquiry($p_user_code, $p_home_mail, $p_mobile_phone_mail, &$w_inquiry, &$e_msg){
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 検索キーの設定
			// 自宅メールアドレス、携帯メールアドレス入力の場合
			if($p_home_mail != "" && $p_mobile_phone_mail != ""){
				// 条件配列セット
				$this->ar_condition = array(
										"USER_CODE"			=> $p_user_code,
										"HOME_MAIL"			=> $p_home_mail,
										"MOBILE_PHONE_MAIL"	=> $p_mobile_phone_mail
										);
				$transmission_address = array($p_home_mail ,$p_mobile_phone_mail);
			// 自宅メールアドレス入力の場合
			}else if ($p_home_mail != ""){
				// 条件配列セット
				$this->ar_condition = array(
										"USER_CODE"			=> $p_user_code,
										"HOME_MAIL"			=> $p_home_mail
										);
				$transmission_address = array($p_home_mail);
			// 携帯メールアドレス入力の場合
			}else if ($p_mobile_phone_mail != ""){
				// 条件配列セット
				$this->ar_condition = array(
										"USER_CODE"			=> $p_user_code,
										"MOBILE_PHONE_MAIL"	=> $p_mobile_phone_mail
										);
				$transmission_address = array($p_mobile_phone_mail);
			// ユーザコードのみ入力の場合
			} else {
				// 条件配列セット
				$this->ar_condition = array(
										"USER_CODE"			=> $p_user_code,
										"HOME_MAIL"			=> $p_home_mail,
										"MOBILE_PHONE_MAIL"	=> $p_mobile_phone_mail
										);
				$transmission_address = null;
			}
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$w_inquiry = $dbobj->getRecord();
			
			// メール設定読込
			$lc_mails = new MailSettings($w_inquiry[1]['DATA_ID']);
			
			
			// メール自動返信処理
			if(count($w_inquiry) == 1){
				if(is_null($transmission_address)){
					$e_msg = "送信出来ませんでした。<BR>ユーザ情報にアドレスが登録されていません。<BR><BR><a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。<BR>";
				} else {
					$authority	=	$w_inquiry[1][AUTHORITY_ID];
					if ($authority == AUTH_ADMI || $authority == AUTH_MANG || $authority == AUTH_GEN1 || $authority == AUTH_GEN2) {
						$e_msg = "ユーザ権限によりメール送信出来ませんでした。<BR><BR><a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。<BR>";
					} else {
						$e_msg = "登録されたアドレスにパスワードを送信致しました。<BR><BR>メールが届かない場合は、<a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。<BR>";
						
						// 宛先
					 	$name					=	$w_inquiry[1][NAME];
						
						// 件名
						$subject				=	"パスワード送信";
						
						// 本文
						$u_name					=	preg_replace("/　/", "", preg_replace("/ /", "", $w_inquiry[1][NAME]))." 様";
						$body					=	$u_name."\n\n"."以下の内容でログインしてください。\n\nユーザコード：".$w_inquiry[1][USER_CODE]."\n"."パスワード：".$w_inquiry[1][PASSWORD]."\n\n"."URL:".getPCURL();
						
						// 送信者のユーザーIDを取得
						session_start();
						$l_sender_userid = $_SESSION["_authsession"]["data"]["USER_ID"];
						
						// メール送信
						require_once('../lib/SendPHPMail.php');
						$lc_sgm = new SendPHPMail($w_inquiry[1]['DATA_ID']);

						// From
						$lc_sgm->setFromaddr($lc_mails->getMailAddr1());
						// To
						$lc_sgm->setToAddress(implode(',', $transmission_address));
						// Subject
						$lc_sgm->setSubject($subject);
						// Body
						$lc_sgm->setBody($body);

						// 送信ログ用データセット
						$lc_sgm->setLogDataId($w_inquiry[1]['DATA_ID']);
						$lc_sgm->setLogSendUserId($l_sender_userid);
						$lc_sgm->setLogUserId($l_sender_userid);
						$lc_sgm->setSendPurpose("パスワード送信");

						// メール送信
						$l_result = $lc_sgm->doSend();

						if ($l_result > 0){
							$e_msg = "メール送信に失敗しました。\n";
						}
					}
				}
			} else {
				$e_msg ="複数の該当データが存在します。<BR><BR><a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。<BR>";
			}
		}
	}
?>