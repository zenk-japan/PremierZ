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
	class m_sessions {
		// 検索用
		function sessions_list(&$data_sel){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
			// 検索キーの設定
			$dataid				=	$_SESSION['_authsession']['data']['DATA_ID'];			//データID
			$userid				=	$_SESSION['_authsession']['data']['USER_ID'];			//ユーザID
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"USER_ID"			=> $userid
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_sel = $dbobj->getRecord();
		}
		
		// Mobile検索用
		function sessions_mobile_list(&$data_sel){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
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
			$data_sel = $dbobj->getRecord();
		}
		
		// セッションの更新（ログアウト処理）
		function DataForLastUpdate(){
			
			$p_table_name	= "SESSIONS";
			$sql			= null;
			
			//セッションID
			if($_GET["sessid"] == ""){
				$sessid									= $_SESSION['SESSID'];
			}else{
				$sessid									= $_GET["sessid"];
			}
			
			//ユーザID
			if($_GET["uid"] == ""){
				$userid									= $_SESSION['_authsession']['data']['USER_ID'];
			}else{
				$userid									= $_GET["uid"];
			}
			
			// SQLの組立て
			// セッション管理テーブル更新
			$sql[0] .= " UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
			$sql[0] .= " SET"." ";
			$sql[0] .= "         LAST_UPDATE_DATET   = now() ";
			$sql[0] .= "        ,LAST_UPDATE_USER_ID = '".$userid."' ";
			
			// 条件部分
			$sql[1] .= " WHERE  SESSID = '".$sessid."' ";
			
			$sql[0]		.= $sql[1];
			$sql		= $sql[0];
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}
		
		// Mobile検索用(KEY=SESS_TOKEN)
		function sessions_MobileList_SessToken(&$data_sel){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
			// 検索キーの設定
			// SESS_TOKEN
			if($_GET["token"] == ""){
				$session_token							= $_SESSION['SESS_TOKEN'];
			}else{
				$session_token							= $_GET["token"];
			}
			
			// 条件配列セット
			$this->ar_condition = array("SESS_TOKEN" => $session_token);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_sel = $dbobj->getRecord();
			
		}
/*----------------------------------------------------------------------------
  Mobile検索用(KEY=SESS_TOKEN)
  引数:				$p_token		トークン
  ----------------------------------------------------------------------------*/
		function getSessionRecByToken($p_token){
			// 引数のトークンからセッションレコードを取得する
			$l_result_rec = "";
			$l_return_value = array();
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
			// 条件配列セット
			$this->ar_condition = array("SESS_TOKEN" => $p_token);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
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
			return $l_return_value;
			
		}
		
		// Mobile検索用(KEY=OLD_SESS_TOKEN)
		function sessions_MobileList_OldSessToken(&$data_sel){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
			// 検索キーの設定
			// SESS_TOKEN
			if($_GET["token"] == ""){
				$session_token							= $_SESSION['SESS_TOKEN'];
			}else{
				$session_token							= $_GET["token"];
			}
			
			// 条件配列セット
			$this->ar_condition = array("OLD_SESS_TOKEN" => $session_token);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_sel = $dbobj->getRecord();
			
		}
		
		// Mobile検索用(KEY=SESSID)
		function sessions_MobileList_Sessid($sessid,&$data_sel){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			$table_name = "SESSIONS_V";
			
			// 条件配列セット
			$this->ar_condition = array("SESSID" => $sessid);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_sel = $dbobj->getRecord();
			
		}
		
		// セッショントークンの更新
		function DataForTokenUpdate($sessid,$sess_token){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			// キーの設定
			// SESS_TOKEN
			if($_GET["token"] == ""){
				$session_token							= $_SESSION['SESS_TOKEN'];
			}else{
				$session_token							= $_GET["token"];
			}
			
	//		echo "<HR>更新<BR>";
	//		echo "キー　：".$session_token."<BR>";
	//		echo "更新後：".$sess_token."<HR>";
			
			// 条件配列セット
			$this->ar_condition = array("SESSID" => $sessid);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$sessioninfo = $dbobj->getRecord();
			
			$p_table_name	= "SESSIONS";
			$sql			= null;
			
			// SQLの組立て
			// SESSIONS－UPDATE
			$sql	.=	"UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
			$sql	.=	" SET"." ";
			$sql	.=	"         SESS_TOKEN          = '".$sess_token."' ";
			$sql	.=	"        ,OLD_SESS_TOKEN      = '".$session_token."' ";
			$sql	.=	"        ,LAST_UPDATE_DATET   = now() ";
			$sql	.=	"        ,LAST_UPDATE_USER_ID = '".$sessioninfo[1]["USER_ID"]."' ";
		//	$sql	.=	" WHERE  SESSID = '".$sessid."' ";
		//	$sql	.=	" WHERE  SESSID = '".$sessid."' ";
			$sql	.=	" WHERE  SESS_TOKEN = '".$session_token."' ";
			
	//		echo "<BR>".$sql."<BR>";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			
		}
		
/*----------------------------------------------------------------------------
  ログイン処理
  引数:				$p_data_id			データID
  					$p_user_id			ユーザーID
  					$p_sessid			セッション
  					$p_token			トークン
  					$p_table_name		更新テーブル
  ----------------------------------------------------------------------------*/
		function updateLogin($p_data_id, $p_user_id, $p_sessid, $p_token = null){
			
			$p_table_name	= "SESSIONS";
			$sql			= null;
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"USER_ID"			=> $p_user_id
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$sessioninfo = $dbobj->getRecord();
			
			if(count($sessioninfo) == 0){
				// SESSIONS－INSERT
				$sql	.=	"INSERT INTO ".SCHEMA_NAME.".".$p_table_name ." ";
				$sql	.=	"(DATA_ID,SESSION_ID,USER_ID,SESSID,SESS_TOKEN,LOGIN_DATET,VALIDITY_FLAG,REGISTRATION_DATET,REGISTRATION_USER_ID,LAST_UPDATE_DATET,LAST_UPDATE_USER_ID)"." ";
				$sql	.=	"VALUE"." ";
				$sql	.=	"('".$p_data_id."',null,'".$p_user_id."','".$p_sessid."','".$p_token."',now(),default,now(),'".$p_user_id."',now(),'".$p_user_id."')"." ";
			} else {
				// SESSIONS－UPDATE
				$sql	.=	"UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
				$sql	.=	" SET"." ";
				$sql	.=	"         SESSID              = '".$p_sessid."' ";
				$sql	.=	"        ,SESS_TOKEN          = '".$p_token."' ";
				$sql	.=	"        ,LOGIN_DATET         = now() ";
				$sql	.=	"        ,LOGIN_FLAG          = 'Y'"." ";
				$sql	.=	"        ,LAST_UPDATE_DATET   = now() ";
				$sql	.=	"        ,LAST_UPDATE_USER_ID = '".$p_user_id."' ";
				$sql	.=	" WHERE  DATA_ID = '".$p_data_id."' ";
				$sql	.=	" AND    USER_ID = '".$p_user_id."' ";
			}
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}
		
/*----------------------------------------------------------------------------
  セッショントークンの更新
  引数:				$p_session_id		セッションID
  					$p_sess_token		トークン
  					$p_user_id			ユーザーID
  ----------------------------------------------------------------------------*/
		// セッショントークンの更新
		function updateToken($p_session_id, $p_sess_token, $p_user_id){
			$sql	= null;
			
			// SQLの組立て
			// SESSIONS－UPDATE
			$sql	.=	"UPDATE SESSIONS  ";
			$sql	.=	" SET"." ";
			$sql	.=	"         SESS_TOKEN          = '".$p_sess_token."' ";
			$sql	.=	"        ,LAST_UPDATE_DATET   = now() ";
			$sql	.=	"        ,LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			$sql	.=	" WHERE  SESSION_ID = '".$p_session_id."' ";
			
	//		echo "<BR>".$sql."<BR>";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			
		}
		
/*----------------------------------------------------------------------------
  ログアウト処理
  引数:				$p_user_id			ユーザーID
  					$p_table_name		更新テーブル
  ----------------------------------------------------------------------------*/
		function updateLogout($p_user_id){
			
			$p_table_name	= "SESSIONS";
			$sql	= null;
			
			// SQLの組立て
			// SESSIONS－UPDATE
			$sql	.=	"UPDATE ".$p_table_name;
			$sql	.=	" SET";
			$sql	.=	" SESSID = null";
			$sql	.=	" ,SESS_TOKEN = null";
			$sql	.=	" ,LOGIN_FLAG = 'N'";
			$sql	.=	" ,LAST_UPDATE_DATET = now()";
			$sql	.=	" ,LAST_UPDATE_USER_ID = '".$p_user_id."'";
			$sql	.=	"WHERE  USER_ID = '".$p_user_id."'";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			
		}
		
		// ログアウトリンク
		function DataForUpdateMobileTermination(){
			
			require_once('../mdl/Sessions_v.php');
			$dbobj = new Sessions_v();
			
			// キーの設定
			// SESS_TOKEN
			if($_GET["token"] == ""){
				$sess_token								= $_SESSION['SESS_TOKEN'];
			}else{
				$sess_token								= $_GET["token"];
			}
			
			// 条件配列セット
			$this->ar_condition = array("SESS_TOKEN" => $sess_token);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$sessioninfo = $dbobj->getRecord();
			
			$p_table_name	= "SESSIONS";
			$sql			= null;
			
			// SQLの組立て
			// SESSIONS－UPDATE
			$sql	.=	"UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
			$sql	.=	" SET"." ";
			$sql	.=	"         SESSID              = null"." ";
			$sql	.=	"        ,SESS_TOKEN          = null"." ";
			$sql	.=	"        ,OLD_SESS_TOKEN      = null"." ";
			$sql	.=	"        ,LOGIN_FLAG          = 'N'"." ";
			$sql	.=	"        ,LAST_UPDATE_DATET   = now() ";
			$sql	.=	"        ,LAST_UPDATE_USER_ID = '".$sessioninfo[1]["USER_ID"]."' ";
			$sql	.=	" WHERE  DATA_ID = '".$sessioninfo[1]["DATA_ID"]."' ";
			$sql	.=	" AND    USER_ID = '".$sessioninfo[1]["USER_ID"]."' ";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}
		
		function DataForUpdateSuspended($sessid){
			
			$p_table_name	= "SESSIONS";
			$sql			= null;
			
			// SQLの組立て
			// SESSIONS－UPDATE
			$sql	.=	"UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
			$sql	.=	" SET"." ";
			$sql	.=	"         SESSID              = null"." ";
			$sql	.=	"        ,SESS_TOKEN          = null"." ";
			$sql	.=	"        ,OLD_SESS_TOKEN      = null"." ";
			$sql	.=	"        ,LOGIN_FLAG          = 'N'"." ";
			$sql	.=	"        ,LAST_UPDATE_DATET   = now() ";
			$sql	.=	"        ,LAST_UPDATE_USER_ID = '".SYSTEM_USER."' ";
			$sql	.=	" WHERE  SESSID = '".$sessid."' ";
			
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}
	}
?>