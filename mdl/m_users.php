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
	class m_users {
		private $ar_condition;					// 検索条件配列
		private $ar_orderby;					// order by配列
		// 一覧表示
		function users_list(&$w_users){
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 検索キーの設定
			$dataid				=	$_POST["hd_dataid"];				//データID
			$groupid			=	$_POST["GROUP_ID"];					//グループID
			$usercode			=	$_POST["USER_CODE"];				//ユーザコード
			$username			=	$_POST["NAME"];						//名前
			$displaydelete		=	$_POST["hd_delete_check"];			//削除済表示
			
			// 削除済表示を元に有効フラグの設定
			if($displaydelete == '' ){
				$validityflag = 'Y';									//有効フラグ(Yのみ)
			} else {
				$validityflag = '';										//有効フラグ(全て)
			}
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"GROUP_ID"			=> $groupid,
									"USER_CODE"			=> "%".$usercode."%",
									"NAME"				=> "%".$username."%",
									"VALIDITY_FLAG"		=> "%".$validityflag."%"
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array(
									"DATA_ID",
									"KANA",
									"USER_CODE"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_users = $dbobj->getRecord();
		}
		
		// 更新表示用検索
		function users_ups(&$data_sel){
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 検索キーの設定
			$dataid				=	$_POST["hd_dataid"];				//データID
			$groupid			=	$_POST["GROUP_ID"];					//グループID
			$userid				=	$_POST["USER_ID"];					//ユーザID
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"GROUP_ID"			=> $groupid,
									"USER_ID"			=> $userid
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_sel = $dbobj->getRecord();
		}
		
		// 削除用データ取得
		function getDataForDelete($p_ar_trgtid){
			$return_rec;				// レコード格納用
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array("USER_ID" => split(",",$p_ar_trgtid));
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$return_rec = $dbobj->getRecord();
			
			return $return_rec;
		}
		// USER_IDをキーとしたデータ取得
		function getRecordByID($p_user_id){
			$return_rec;				// レコード格納用
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array("USER_ID" => $p_user_id);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$return_rec = $dbobj->getRecord();
			
			return $return_rec;
		}

		// SQL（GROUPSからの論理削除）の作成
		function DataForUsersInvalid($del_target){
			
			if($del_target == "" || is_null($del_target)){
			}else{
				// Invalid対象テーブル名
				$table_name = SCHEMA_NAME.".USERS";
				
				// 削除済表示を元に有効フラグの設定
				$validityflag	=	'N';									//有効フラグ->無効
				
				$sql = null;
				$sql .= "update ".$table_name." "."set ";
				
				// 共通部分
				$sql .= " VALIDITY_FLAG = '".$validityflag."' ";
				$sql .= ",LAST_UPDATE_DATET = now() ";
				$sql .= ",LAST_UPDATE_USER_ID = '".$_POST["LOGINUSER_ID"]."' ";
				
				// 更新キーの設定
				$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
				$sql .= "  and GROUP_ID in (".$del_target."); ";
				
				require_once('../mdl/CommonExecution.php');
				$dbobj = new CommonExecution();
				$dbobj->CommonSilentSQL($sql);
			}
		}
		
		// SQL（物理削除）の作成
		function DataForDelete(){
			
			// 削除キー
			$deletetarget		=	$_POST["DELETE_TARGET"];			//削除対象
			
			// Delete対象テーブル名
			$table_name = SCHEMA_NAME.".USERS";
			
			$sql = null;
			$sql .= "delete from ".$table_name." ";
			
			// 更新キーの設定
			$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
			$sql .= "  and GROUP_ID = '".$_POST["GROUP_ID"]."' ";
			$sql .= "  and USER_ID in (".$deletetarget."); ";
			
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSQL($sql);
			return $sql;
		}

		
		// 一般ユーザからのマスタ物理削除は廃止(システム管理者のみ可)
		// SQL（GROUPSからの物理削除）の作成
		function DataForUsersDelete($del_target){
			
			if($del_target == "" || is_null($del_target)){
			}else{
				// Delete対象テーブル名
				$table_name = SCHEMA_NAME.".USERS";
				
				$sql = null;
				$sql .= "delete from ".$table_name." ";
				
				// 更新キーの設定
				$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
				$sql .= "  and GROUP_ID in (".$del_target."); ";
				
				require_once('../mdl/CommonExecution.php');
				$dbobj = new CommonExecution();
				$dbobj->CommonSilentSQL($sql);
			}
		}
		
		function LoginCheck(&$data_chk){
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 検索キーの設定
			//データID
			if(is_null($_GET["did"])){
				$dataid									= $_SESSION['_authsession']['data']['DATA_ID'];
			}else{
				$dataid									= $_GET["did"];
			}
			
			//ユーザID
			if($_GET["uid"] == ""){
				$userid									= $_SESSION['_authsession']['data']['USER_ID'];
			}else{
				$userid									= $_GET["uid"];
			}
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"USER_ID"			=> $userid
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_chk = $dbobj->getRecord();
		}
		
		// USER_ID取得用
		function getUserId($p_data_id,$p_username,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"NAME"				=> $p_username,
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			//print $l_ar_cond."\n";
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["USER_ID"];
		}
		
		// ENDUSER_USER_ID,REQUEST_USER_ID取得用
		function getEndUserId($p_data_id,$p_username,$p_companyname,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"COMPANY_NAME"		=> $p_companyname,
									"NAME"				=> $p_username
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["USER_ID"];
		}
		
		// UNIT_PRICE取得用
		function getUnitPrice($p_data_id,$p_username,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"NAME"				=> $p_username
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["UNIT_PRICE"];
			
		}
		
		// PAYMENT_DIVISION取得用
		function getPaymentDivision($p_data_id,$p_username,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"NAME"				=> $p_username
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["PAYMENT_DIVISION"];
			
		}
		
		// WORK_ARRANGEMENT_ID取得用
		function getWorkArrangement($p_data_id,$p_username,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"NAME"				=> $p_username
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["USER_ID"];
			
		}
		
		// CLASSIFICATION_DIVISION取得用
		function getClassificationDivision($p_data_id,$p_username,&$return_user){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"NAME"				=> $p_username
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_user = $dbobj->getRecord();
			$return_user = $data_user[1]["CLASSIFICATION_DIVISION"];
			
		}
		
		// 一括メール送信用データ取得
		function getDataForBatchsend($p_ar_trgtid){
			$return_rec;				// レコード格納用
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件配列セット
			$this->ar_condition = array("USER_ID" => split(",",$p_ar_trgtid));
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$return_rec = $dbobj->getRecord();
			
			return $return_rec;
		}
		
		function MailSend(){
			
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// データID
			$dataid					=	$_POST["DATA_ID"];					//データID
			
			// メール設定読込
			$lc_mails = new MailSettings($dataid);
			
			// 送信先の設定
			$send_target			=	$_POST["BATCHSEND_TARGET"];			//送信先
			$subject				=	$_POST["SUBJECT"];					//件名
			$from_address			=	$lc_mails->getMailAddr1();			//FROMアドレス
			$cc_address				=	$lc_mails->getMailAddr1();			//CCアドレス
			$bcc_address			=	NULL;								//BCCアドレス
			
			if(mb_strpos($send_target,",") > 0){
				// 一括送信
				// 条件配列セット
				$this->ar_condition = array(
										"DATA_ID"			=> $dataid,
										"USER_ID"			=> CONDITION_PLURAL."( ".$send_target." )"
										);
			} else {
				// 個別送信
				// 条件配列セット
				$this->ar_condition = array(
										"DATA_ID"			=> $dataid,
										"USER_ID"			=> $send_target
										);
			}
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array("DATA_ID","USER_ID");
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_sendtarget	= $dbobj->getRecord();
			
			// メール送信用処理
			foreach($w_sendtarget as $target){
				$target_cnt++;
				
				if(is_null($target["HOME_MAIL"]) && is_null($target["MOBILE_PHONE_MAIL"])){
					echo "有効なメールアドレスが登録されていないため、\n送信出来ませんでした。";
				} else {
					$to_address	= null;
					
					// ユーザ名
					$str_name = preg_replace("/　/", "", preg_replace("/ /", "", $target["NAME"]));
					
					// 送信先設定
					$to_address	=	null;
					
					if(is_null($target["HOME_MAIL"])){
					} else {
						$to_address	=	$target["HOME_MAIL"];
					}
					
					if(is_null($target["MOBILE_PHONE_MAIL"])){
					} else {
						if(is_null($to_address)){
							$to_address	=	$target["MOBILE_PHONE_MAIL"];
						} else {
							$to_address	.=	",".$target["MOBILE_PHONE_MAIL"];
						}
					}
						
					// 件名のユーザ名を置換
					$subject	= $_POST["SUBJECT"];
					$subject	= preg_replace('/' . INFORMATION_NAME . '/', $str_name, $subject);
					
					// 本文のユーザ名を置換
					$body		= $_POST["BODY"];
					$body		= preg_replace('/' . INFORMATION_NAME . '/', $str_name, $body);
					
					// 本文のユーザコードを置換
					$body		= preg_replace('/' . INFORMATION_USER_CODE . '/', $target["USER_CODE"], $body);
					
					// 本文のパスワードを置換
					$body		= preg_replace('/' . INFORMATION_PASSWORD . '/', $target["PASSWORD"], $body);
					
					// 送信者のユーザーIDを取得
					session_start();
					$l_sender_userid = $_SESSION["_authsession"]["data"]["USER_ID"];
					
					// メール送信
					require_once('../lib/SendPHPMail.php');
					$lc_sgm = new SendPHPMail($target['DATA_ID']);

					// From
					$lc_sgm->setFromaddr($from_address);
					// To
					$lc_sgm->setToAddress($to_address);
					// Cc
					$lc_sgm->setCcAddress($cc_address);
					// Bcc
					$lc_sgm->setBccAddress($bcc_address);
					// Subject
					$lc_sgm->setSubject($subject);
					// Body
					$lc_sgm->setBody($body);

					// 送信ログ用データセット
					$lc_sgm->setLogDataId($target['DATA_ID']);
					$lc_sgm->setLogSendUserId($l_sender_userid);
					$lc_sgm->setLogUserId($l_sender_userid);
					$lc_sgm->setSendPurpose("ログイン情報送信");

					// メール送信
					$l_result = $lc_sgm->doSend();

					if ($l_result > 0){
						echo $str_name."様の[ログイン情報]送信に失敗しました。\n[error]".$result->getMessage()."\n";
					} else {
						echo $str_name."様に[ログイン情報]を送信しました。\n";
					}
				}
			}
		}
		
/*----------------------------------------------------------------------------
  設定情報取得
  USERS登録データを取得
  引数:			$p_user_id			USER_IDの条件にセットするユーザーID
				$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
  ----------------------------------------------------------------------------*/
		function getUsersList($p_user_id, $p_include_invalid = 'N'){
			
			$l_return_value	= array();
			$l_result_rec	= "";
			$l_ar_condition	= "";
			
			// クラスインスタンス作成
			require_once('../mdl/Users_v.php');
			$dbobj = new Users_v();
			
			// 条件設定
			// ユーザ(USER_ID)
			$l_ar_condition["USER_ID"]	= "'".$p_user_id."'";
			
			// 有効フラグ(VALIDITY_FLAG)
			if($p_include_invalid == 'N'){
				$l_ar_condition["VALIDITY_FLAG"] = "Y";
			}
			
			// 条件セット
			$l_cond_ret = $dbobj->setCondition($l_ar_condition);
			
			$dbobj->setWherePhraseText($l_cond_ret);
			
			// レコード取得
			$l_result_rec = $dbobj->getRecord();
			
			// レコードが返ってきた場合は戻り値をセットする
			if(count($l_result_rec)>0){
				$l_loop_cnt = 0;
				foreach($l_result_rec as $key => $value){
					$l_loop_cnt++;
					foreach($value as $item_key => $item_value){
						if(preg_match("/^[0-9]+$/",$item_key) ){
							// 配列キーが数値の部分は無視
						} else {
							//print "item_key:".$item_key." item_value:".$item_value."<br>";
							$l_return_value[$l_loop_cnt][$item_key] = $item_value;
							if($item_key=="WORK_NAME"){
								// 作業内容については、省略表示用のカラムも作成する
								$l_value_len = mb_strlen($item_value);
								if($l_value_len >= $this->workname_short_size){
									$l_short_value = mb_substr($item_value, 0, $this->workname_short_size)."...";
								}else{
									$l_short_value = $item_value;
								}
								$l_return_value[$l_loop_cnt]["WORK_NAME_SHORT"] = $l_short_value;
							}
							if($item_key=="WORK_DATE"){
								// 作業日時については、省略表示用のカラムも作成する
								$l_return_value[$l_loop_cnt]["WORK_DATE_SHORT"] = mb_substr($item_value, 5, 5);
							}
						}
					}
				}
			}
			
			// 条件初期化
			$dbobj->resetCondition();
			
			return $l_return_value;
		}
		
/*----------------------------------------------------------------------------
  設定情報更新
  USERSデータを更新
  引数:			$p_user_id					ログインユーザID
  				$p_data						POSTされた配列
  ----------------------------------------------------------------------------*/
		function upUsersList($p_user_id, $p_data, $p_uid){
			
			$p_table_name	= "USERS";
			$sql_type		= "UPDATE";
			$sql			= null;
			$sql_column		= null;
			$sql_data		= null;
			$l_return_value	= array();
			
			$member_table_name = $p_table_name;
			require_once('../mdl/m_column_info.php');
			$cchk = new ColumnInfo(strtoupper($p_table_name));
			
			$column_chk = $cchk->getColumnChk();
			
			$rcnt = 0;
			foreach ($column_chk as $column_chk1) {
				$column_chk[++$rcnt] = $column_chk1;
			}
			
			//二次元配列$column_chkのcolumn_nameの列を別の配列に移す
			$j=1;
			for( $j = 1; $j<=count($column_chk); $j++ ){
				$info_colum[$j]	= $column_chk[$j]['COLUMN_NAME'];
				$info_key[$j]	= $column_chk[$j]['COLUMN_KEY'];
				$info_table[$j]	= array( column_name				=>$column_chk[$j]['COLUMN_NAME']
										,data_type					=>$column_chk[$j]['DATA_TYPE']
										,character_maximum_length	=>$column_chk[$j]['CHARACTER_MAXIMUM_LENGTH']
										,is_nullable				=>$column_chk[$j]['IS_NULLABLE']
										,column_default				=>$column_chk[$j]['COLUMN_DEFAULT']);
			}
			
			// 入力項目を共通チェックするファンクションがあるオブジェクトの呼び出し
			require_once('../lib/CommonCheck.php');
			$dbcommoncheck = new CommonCheck();
			
			// ユーザ固有の入力項目をチェックしSQL文を作成するファンクションがあるオブジェクトの呼び出し
			require_once('../lib/UserCheck.php');
			$dbusercheck = new UserCheck();
			
			require_once('../lib/CreateSQL.php');
			$dbcreatesql = new CreateSQL();
			
			for( $i = 1; $i<=count($p_data); $i++ ){
				//モジュールに入れる$_POSTの値を変数に代入する。
				$entry_key		= $p_data[$i]['Input_col'];
				$entry_value	= $p_data[$i]['Input_val'];
				
				// 入力項目を共通チェックするファンクションの呼び出し
				$err_check_common = $dbcommoncheck->CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);
				
				if($err_check_common["Code"] == 1){
					$l_return_value[$i] = $err_check_common["Message"];
				}
				
				// ユーザ固有の入力項目をチェックしSQL文を作成するファンクションの呼び出し
				$err_check_user = $dbusercheck->UserDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);
				
				if($err_check_user["Code"] == 1){
					$l_return_value[$i] = $err_check_user["Message"];
				}
				
				// テーブルに登録するSQL文を作成するファンクションの呼び出し
				$dbcreatesql->CreateSQLString($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value,NULL);
				
				if ($err_check_common["Code"]  == 1 || $err_check_user["Code"] == 1){
					$err_check_code = 1;
				}
				
				if($entry_key == "IDENTIFICATION_FLAG"){
					$identification_flag = $entry_value;
				}
			}
			
			if($identification_flag == "Y" && $p_uid == NULL){
				$err_check_code = 1;
				$i++;
				$l_return_value[$i] = "ご使用中の機種では「簡単ログイン」の設定が出来ませんでした。";
			} else if ($identification_flag == "N"){
				$identification_id = "null";
			} else {
				$identification_id = "'".$p_uid."'";
			}
			
			if ($err_check_code != 1){
				//SQL文の作成
				//更新登録
				$sql  = "UPDATE ".SCHEMA_NAME.".".$p_table_name ." "." SET ".$dbcreatesql->d_update;
				$sql .= ",ENCRYPTION_PASSWORD = '".$dbusercheck->encrypition."'";
				$sql .= ",IDENTIFICATION_ID = ".$identification_id;
				$sql .= ",LAST_UPDATE_DATET = now() ";
				$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."'";
				$sql .= " WHERE ".$dbcreatesql->pri_column_name ." = '".$dbcreatesql->pri_column_data."' ";
				
				//print $sql."\n";
				if(is_null($dbcreatesql->d_update) || is_null($dbusercheck->encrypition) || is_null($dbcreatesql->pri_column_name) || is_null($dbcreatesql->pri_column_data)){
				}else{
					require_once('../mdl/CommonExecution.php');
					$dbobj = new CommonExecution();
					$l_return_value = $dbobj->CommonMobileSQL($sql);
				}
			} else {
				$l_return_value['RETERN_CODE'] = RETURN_ERROR;
			}
			
			return $l_return_value;
		}
		
/*----------------------------------------------------------------------------
  簡単ログイン-ユーザ情報更新
  USERSデータを更新
  引数:			$p_user_id				ログインユーザID
  				$p_uid					アクセスされた機種の個体識別ID
										・DoCoMo	=	iモードID
										・SoftBank	=	UID
										・au		=	EZ番号
  ----------------------------------------------------------------------------*/
		function upUsersEasyLogin($p_user_id, $p_uid, $p_identification_flag = 'Y'){
			
			$p_table_name	= "USERS";
			$sql			= null;
			
			if(is_null($p_uid)){
				$uid	=	"null";
			} else {
				$uid	=	"'".$p_uid."'";
			}
			
			//SQL文の作成
			//更新登録
			$sql  = "UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
			$sql .= " SET  ";
			$sql .= "  IDENTIFICATION_ID = ".$uid." ";
			$sql .= " ,IDENTIFICATION_FLAG = '".$p_identification_flag."' ";
			$sql .= " ,LAST_UPDATE_DATET = now() ";
			$sql .= " ,LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			$sql .= " WHERE USER_ID = '".$p_user_id."' ";
			
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}
		
/*----------------------------------------------------------------------------
  ユーザ情報検索
  USERSデータを更新
  引数:			$p_data						POSTされた配列
  ----------------------------------------------------------------------------*/
		function inquiryPassword($p_data){
			
			$p_table_name	= "USERS";
			$sql_type		= "UPDATE";
			$sql			= null;
			$sql_column		= null;
			$sql_data		= null;
			$l_return_value	= array();
			
			$member_table_name = $p_table_name;
			require_once('../mdl/m_column_info.php');
			$cchk = new ColumnInfo(strtoupper($p_table_name));
			
			$column_chk = $cchk->getColumnChk();
			
			$rcnt = 0;
			foreach ($column_chk as $column_chk1) {
				$column_chk[++$rcnt] = $column_chk1;
			}
			
			//二次元配列$column_chkのcolumn_nameの列を別の配列に移す
			$j=1;
			for( $j = 1; $j<=count($column_chk); $j++ ){
				$info_colum[$j]	= $column_chk[$j]['COLUMN_NAME'];
				$info_key[$j]	= $column_chk[$j]['COLUMN_KEY'];
				$info_table[$j]	= array( column_name				=>$column_chk[$j]['COLUMN_NAME']
										,data_type					=>$column_chk[$j]['DATA_TYPE']
										,character_maximum_length	=>$column_chk[$j]['CHARACTER_MAXIMUM_LENGTH']
										,is_nullable				=>$column_chk[$j]['IS_NULLABLE']
										,column_default				=>$column_chk[$j]['COLUMN_DEFAULT']);
			}
			
			// 入力項目を共通チェックするファンクションがあるオブジェクトの呼び出し
			require_once('../lib/CommonCheck.php');
			$dbcommoncheck = new CommonCheck();
			
			// ユーザ固有の入力項目をチェックしSQL文を作成するファンクションがあるオブジェクトの呼び出し
			require_once('../lib/UserCheck.php');
			$dbusercheck = new UserCheck();
			
			for( $i = 1; $i<=count($p_data); $i++ ){
				//モジュールに入れる$_POSTの値を変数に代入する。
				$entry_key		= $p_data[$i]['Input_col'];
				$entry_value	= $p_data[$i]['Input_val'];
				
				// 入力項目を共通チェックするファンクションの呼び出し
				$err_check_common = $dbcommoncheck->CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);
				
				if($err_check_common["Code"] == 1){$l_return_value[$i] = $err_check_common["Message"];}
				
				// ユーザ固有の入力項目をチェックしSQL文を作成するファンクションの呼び出し
				$err_check_user = $dbusercheck->UserDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);
				
				if($err_check_user["Code"] == 1){$l_return_value[$i] = $err_check_user["Message"];}
				
				if ($err_check_common["Code"]  == 1 || $err_check_user["Code"] == 1){$err_check_code = 1;}
				
				if($entry_key == "USER_CODE"){
					$p_user_code = $entry_value;
				} else if($entry_key == "HOME_MAIL"){
					$p_home_mail = $entry_value;
				} else if($entry_key == "MOBILE_PHONE_MAIL"){
					$p_mobile_phone_mail = $entry_value;
				}
			}
			
			// 自宅メールアドレス且つ携帯メールアドレスが入力されていない場合はエラーをかえす
			if(empty($p_home_mail) && empty($p_mobile_phone_mail)){
				$err_check_code = 1;
				$l_return_value[$i] = "「メールアドレス」が入力されていません。";
			}
			
			if ($err_check_code != 1){
				// ユーザ情報を検索
				require_once('../mdl/Users_v.php');
				$dbobj = new Users_v();
				
				// 検索キーの設定
				// ユーザコード
				$l_ar_condition["USER_CODE"]				= $p_user_code;
				
				// 自宅メールアドレス
				if(trim($p_home_mail) != ''){
					$l_ar_condition["HOME_MAIL"]			= $p_home_mail;
					$to_address								= $p_home_mail;
				}
				
				// 携帯メールアドレス
				if(trim($p_mobile_phone_mail) != ''){
					$l_ar_condition["MOBILE_PHONE_MAIL"]	= $p_mobile_phone_mail;
					
					if(empty($to_address)){
						$to_address							= $p_mobile_phone_mail;
					} else {
						$to_address							.= ",".$p_mobile_phone_mail;
					}
				}
				
				// 条件セット
				$l_cond_ret = $dbobj->setCondition($l_ar_condition);
				
				$dbobj->setWherePhraseText($l_cond_ret);
				
				// レコード取得
				$l_result_rec = $dbobj->getRecord();
				
				// メール設定読込
				$lc_mails = new MailSettings($l_result_rec[1]['DATA_ID']);
				
				$from_address = $lc_mails->getMailAddr1();
				$cc_address = $lc_mails->getMailAddr1();
				$bcc_address = NULL;
				
				// メール返信処理
				if(count($l_result_rec) == 0){
					$i++;
					$l_return_value[$i] = "該当データが存在しません。";
					$i++;
					$l_return_value[$i] = "ご入力されたデータに間違いがない場合は、<a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。";
				} else if(count($l_result_rec) == 1){
					$authority	=	$l_result_rec[1]['AUTHORITY_CODE'];
					if ($authority == AUTH_ADMI || $authority == AUTH_MANG || $authority == AUTH_GEN1 || $authority == AUTH_GEN2) {
						$i++;
						$l_return_value[$i] = "ユーザ権限によりメール送信出来ませんでした。";
						$i++;
						$l_return_value[$i] = "作業を継続する場合は、<a href=\"mailto:".$lc_mails->getMailAddr1()."\">管理者</a>までお問い合わせください。";
					} else {
						// 件名
						$subject				=	"【パスワード送信】";
						
						// 本文
						$u_name					=	preg_replace("/　/", "", preg_replace("/ /", "", $l_result_rec[1]['NAME']))." 様";
						$body					=	$u_name."\n\n"."以下の内容でログインしてください。\n\nユーザコード：".$l_result_rec[1]['USER_CODE']."\n"."パスワード：".$l_result_rec[1][PASSWORD]."\n\n"."URL:".getPCURL();
						
						// 送信者のユーザーIDを取得
						session_start();
						$l_sender_userid = $l_result_rec[1]["USER_ID"];
						
						// メール送信
						require_once('../lib/SendPHPMail.php');
						$lc_sgm = new SendPHPMail($l_result_rec[1]['DATA_ID']);

						// From
						$lc_sgm->setFromaddr($from_address);
						// To
						$lc_sgm->setToAddress($to_address);
						// Cc
						$lc_sgm->setCcAddress($cc_address);
						// Bcc
						$lc_sgm->setBccAddress($bcc_address);
						// Subject
						$lc_sgm->setSubject($subject);
						// Body
						$lc_sgm->setBody($body);

						// 送信ログ用データセット
						$lc_sgm->setLogDataId($l_result_rec[1]['DATA_ID']);
						$lc_sgm->setLogSendUserId($l_sender_userid);
						$lc_sgm->setLogUserId($l_sender_userid);
						$lc_sgm->setSendPurpose("パスワード送信");

						// メール送信
						$l_result = $lc_sgm->doSend();

						if ($l_result > 0){
							$l_return_value['RETERN_CODE'] = RETURN_ERROR;
							$l_return_value['RETERN_MSG'] = $result->getMessage();
						} else {
							$l_return_value['RETERN_CODE'] = RETURN_NOMAL;
							$l_return_value['RETERN_MSG'] = "ご入力されたメールアドレスへパスワードを送信しました。";
						}
						
					}
				} else {
					$i++;
					$l_return_value[$i] = "複数のユーザが存在しています。";
				}
			} else {
				$l_return_value['RETERN_CODE'] = RETURN_ERROR;
			}
			return $l_return_value;
		}
		
/*----------------------------------------------------------------------------
  ユーザ情報取得（個体識別番号）
  USERS登録データを取得
  引数:			$p_uid					アクセスされた機種の個体識別ID
										・DoCoMo	=	iモードID
										・SoftBank	=	UID
										・au		=	EZ番号
				$p_identification_flag	個体識別ID取得フラグ(Y:取得、N:除外)
				$p_include_invalid		無効値取得フラグ(Y:取得、N:除外)
  ----------------------------------------------------------------------------*/
		function getUsersLogin($p_uid, $p_identification_flag, $p_include_invalid = 'N'){
			
			if(isset($p_uid)){
				
				$l_return_value	= array();
				$l_result_rec	= "";
				$l_ar_condition	= "";
				
				// クラスインスタンス作成
				require_once('../mdl/Users_v.php');
				$dbobj = new Users_v();
				
				// 条件設定
				// 個体識別ID(IDENTIFICATION_ID)
				$l_ar_condition["IDENTIFICATION_ID"]	= $p_uid;
				
				// 個体識別フラグ(IDENTIFICATION_FLAG)
				$l_ar_condition["IDENTIFICATION_FLAG"] = "Y";
				
				// 有効フラグ(VALIDITY_FLAG)
				if($p_include_invalid == 'N'){
					$l_ar_condition["VALIDITY_FLAG"] = "Y";
				}
				
				// 条件セット
				$l_cond_ret = $dbobj->setCondition($l_ar_condition);
				
				$dbobj->setWherePhraseText($l_cond_ret);
				
				// レコード取得
				$l_result_rec = $dbobj->getRecord();
				
				// レコードが返ってきた場合は戻り値をセットする
				if(count($l_result_rec)>0){
					$l_loop_cnt = 0;
					foreach($l_result_rec as $key => $value){
						$l_loop_cnt++;
						foreach($value as $item_key => $item_value){
							if(preg_match("/^[0-9]+$/",$item_key) ){
								// 配列キーが数値の部分は無視
							} else {
								//print "item_key:".$item_key." item_value:".$item_value."<br>";
								$l_return_value[$l_loop_cnt][$item_key] = $item_value;
							}
						}
					}
				}
				
				// 条件初期化
				$dbobj->resetCondition();
				
				return $l_return_value;
			}
		}
	}
?>