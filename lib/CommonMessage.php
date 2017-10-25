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
// =============================================================================
// メール本文メッセージ用固定値
// =============================================================================

define("MESSAGE_LINE",				"\n--------------------\n");
define("MESSAGE_ATTENTION_AHEAD",	"による入退店等の報告は全て弊社宛ての連絡となります。\n");
define("MESSAGE_ATTENTION_BACK",	"依頼元・お客様宛てへ入退店連絡報告の指示が別途ありましたら、指定先へのご連絡も必ず実施願います。\n");


class CommonMessage {
/*******************************************************************************
	クラス名：CommonMessage.php
	処理概要：メッセージを一括管理する
*******************************************************************************/
	private $debug_mode				= 0;
	// メッセージ内の仮置き記号
	public $prov_user_code			= "%%USER_CODE%%";			// ユーザーコード
	public $prov_user_name			= "%%USER_NAME%%";			// ユーザー名
	public $prov_use_company_code	= "%%USE_COMPANY_CODE%%";	// 利用会社
	public $prov_pc_url				= "%%PC_URL%%";				// PC用入口
	public $prov_mobile_url			= "%%MOBILE_URL%%";			// 携帯用入口
	public $prov_manage_addr		= "%%MANAGE_ADDR%%";		// 管理用アドレス
	public $prov_attend_addr		= "%%ATTEND_ADDR%%";		// 勤怠用アドレス
	public $prov_system_name		= "%%SYS_NAME%%";			// システム名
	public $prov_passreset_url		= "%%URL_FOR_PASSRESET%%";	// パスワードリセット用URL
	public $prov_user_default_pass	= "%%USER_DEFAULT_PASS%%";	// デフォルトのパスワード
	
	private $prov_work_name					= "%%WORK_NAME%%";				// 作業名
	private $prov_work_date					= "%%WORK_DATE%%";				// 作業日時
	private $prov_schedule_timet_from		= "%%SCHEDULE_TIMET_FROM%%";	// 予定時間（自）
	private $prov_schedule_timet_to			= "%%SCHEDULE_TIMET_TO%%";		// 予定時間（至）
	private $prov_aggregate_point			= "%%AGGREGATE_POINT%%";		// 集合場所
	private $prov_aggregate_timet			= "%%AGGREGATE_TIMET%%";		// 集合時間
	private $prov_work_base_name			= "%%WORK_BASE_NAME%%";			// 作業場所
	private $prov_work_address				= "%%WORK_ADDRESS%%";			// 作業場所住所
	private $prov_work_closest_station		= "%%WORK_CLOSEST_STATION%%";	// 最寄駅
	private $prov_work_arrangement_name		= "%%WORK_ARRANGEMENT_NAME%%";	// 作業纏め者
	private $prov_work_arrg_mobile_phone	= "%%WORK_ARRG_MOBILE_PHONE%%";	// 作業纏め者携帯
	private $prov_work_content_details		= "%%WORK_CONTENT_DETAILS%%";	// 作業内容詳細
	private $prov_bringing_goods			= "%%BRINGING_GOODS%%";			// 持参品
	private $prov_introduce					= "%%INTRODUCE%%";				// 名乗り
	private $prov_clothes					= "%%CLOTHES%%";				// 服装
	private $prov_other_remarks				= "%%OTHER_REMARKS%%";			// その他
	private $prov_work_date_short			= "%%WORK_DATE_SHORT%%";		// 作業日（短表示）
	private $prov_delay_message				= "%%DELAY_MESSAGE%%";			// 遅延メッセージ
	
	// 送信先単位置換用
	public $static_each_user		= "%各ユーザ名%";
	public $static_each_user_up		= "%各ユーザ－作業費%";
/*============================================================================
	コンストラクタ
  ============================================================================*/
	function __construct(){

		require_once('../mdl/m_message_template.php');
		
		if($this->debug_mode==1){print("Step-__construct");print "<br>";}
	}
	
/*-------------------------------------------------------------------------------
	処理概要：
		メッセージ取得
	引数：
		$p_data_id						DATA_ID
		$p_message_template_name		テンプレート名
-------------------------------------------------------------------------------*/
	function getMessageTemplate($p_data_id, $p_message_template_name){
		$l_return_mess = "";
		
		// 条件設定
		$lr_cond = array('DATA_ID = '.$p_data_id);
		array_push($lr_cond, 'MESSAGE_TEMPLATE_NAME = "'.$p_message_template_name.'"');
		
		// メッセージ取得
		$lc_mdl = new m_message_template('Y', $lr_cond);
		$lr_message = $lc_mdl->getViewRecord();
		
		$l_return_mess = $lr_message[1]['MESSAGE'];
		
		return $l_return_mess;
	}

/*-------------------------------------------------------------------------------
	処理概要：
		入力された文字列の指定箇所を置換する
	引数：
		$p_string						置換前のデータ
		$p_replace_trgt					置換対象文字列
		$p_replace_string				置換後の文字列
-------------------------------------------------------------------------------*/
	function getReplacedStrings($p_string, $p_replace_trgt, $p_replace_string = ' '){
		$l_return_value = "";
		if ($p_string == "" or $p_replace_trgt == ""){
		// 引数にNULLがある場合は入力をそのまま返す
			return $p_string;
		}

		// 置換
		$l_return_value = str_replace($p_replace_trgt, $p_replace_string, $p_string);

		return $l_return_value;
	}

/*-------------------------------------------------------------------------------
	処理概要：
		メッセージ内の置換文字列をすべて置換する
	引数：
		$p_message						置換処理をするメッセージ
		$p_data_rec						置換に使用するレコード
-------------------------------------------------------------------------------*/
	function replaceAll($p_message, $p_data_rec){
		$l_return_mess = $p_message;
		
		// 日付モジュールの読み込み
		require_once('../lib/CommonDate.php');
		$lc_cdate = new CommonDate();
		// メール設定モジュールの読み込み
		require_once('../lib/MailSettings.php');
		$lc_mailset = new MailSettings($p_data_rec["DATA_ID"]);


		// メッセージを置換
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_user_code, $p_data_rec["USER_CODE"]);						// ユーザーコード
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_user_name, $p_data_rec["NAME"]);								// ユーザー名
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_use_company_code, $p_data_rec["USE_COMPANY_CODE"]);			// 利用会社
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_user_default_pass, DEFAULT_PASSWORD);						// デフォルトのパスワード
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_pc_url, getPCURL());											// PC URL
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_mobile_url, getMobileURL());									// Mobile URL
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_name, $p_data_rec["WORK_NAME"]);						// 作業名
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_date_short, $p_data_rec["WORK_DATE_SHORT"]);			// 作業日（短表示）
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_delay_message, $p_data_rec["DELAY_MESSAGE"]);				// 遅延メッセージ
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_date, $p_data_rec["MAIL_WORK_DATE"]);					// 作業日時
		
		$l_e_schedule_timet = $lc_cdate->getTimeByYMD($p_data_rec["ENTERING_SCHEDULE_TIMET"], $p_data_rec["WORK_DATE"]);
		$l_l_schedule_timet = $lc_cdate->getTimeByYMD($p_data_rec["LEAVE_SCHEDULE_TIMET"], $p_data_rec["WORK_DATE"]);
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_schedule_timet_from, $l_e_schedule_timet);					// 予定時間（自）
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_schedule_timet_to, $l_l_schedule_timet);						// 予定時間（至）
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_aggregate_point, $p_data_rec["AGGREGATE_POINT"]);			// 集合場所
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_aggregate_timet, $p_data_rec["AGGREGATE_TIMET"]);			// 集合時間
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_base_name, $p_data_rec["WORK_BASE_NAME"]);				// 作業場所
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_address, $p_data_rec["WORK_ADDRESS"]);					// 作業場所住所
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_closest_station, $p_data_rec["WORK_CLOSEST_STATION"]);	// 最寄駅

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_arrangement_name, $p_data_rec["WORK_CLOSEST_STATION"]);	// 作業纏め者
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_arrg_mobile_phone, $p_data_rec["MOBILE_PHONE"]);		// 作業纏め者携帯

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_work_content_details, $p_data_rec["WORK_CONTENT_DETAILS"]);	// 作業内容詳細
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_bringing_goods, $p_data_rec["BRINGING_GOODS"]);				// 持参品

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_introduce, $p_data_rec["INTRODUCE"]);						// 名乗り

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_clothes, $p_data_rec["CLOTHES"]);							// 服装
		
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_other_remarks, $p_data_rec["OTHER_REMARKS"]);				// その他

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_system_name, SYSTEM_NAME);									// システム名

		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_manage_addr, $lc_mailset->getMailAddr1());					// 管理用アドレス
		$l_return_mess = $this->getReplacedStrings($l_return_mess, $this->prov_attend_addr, $lc_mailset->getMailAddr2());					// 勤怠用アドレス
		
		return $l_return_mess;
	}
/*-------------------------------------------------------------------------------
	処理概要：
		ユーザー画面から送信される通知メールのタイトルを作成する
	引数：
		$pr_user						送信対象ユーザーのデータ
-------------------------------------------------------------------------------*/
	function getUserNoticeTitle($pr_user){
		$l_return_mess = "";

		// ユーザーレコードが無い場合は終了
		if (count($pr_user) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_user['DATA_ID'], "ユーザー通知用件名");
		
		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_user);

		return $l_return_mess;
	}
/*-------------------------------------------------------------------------------
	処理概要：
		ユーザー画面から送信される通知メールの文言を作成する
	引数：
		$pr_user					送信対象ユーザーのデータ
-------------------------------------------------------------------------------*/
	function getUserNoticeMess($pr_user){
		$l_return_mess = "";

		// ユーザーレコードが無い場合は終了
		if (count($pr_user) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_user['DATA_ID'], "ユーザー通知用本文");

		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_user);
		
		return $l_return_mess;
	}
/*-------------------------------------------------------------------------------
	処理概要：
		作業人員画面から送信される作業依頼メールのタイトルを作成する
	引数：
		$pr_workstaff					送信対象の作業人員のデータ
-------------------------------------------------------------------------------*/
	function getWorkRequestsTitle($pr_workstaff){
		$l_return_mess = "";

		// 作業人員レコードが無い場合は終了
		if (count($pr_workstaff) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_workstaff['DATA_ID'], "作業通知用件名");
		
		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_workstaff);

		return $l_return_mess;
	}
/*-------------------------------------------------------------------------------
	処理概要：
		作業人員画面から送信される作業依頼メールの文言を作成する
	引数：
		$pr_workstaff					送信対象の作業人員のデータ
-------------------------------------------------------------------------------*/
	function getWorkRequestsMess($pr_workstaff){
		$l_return_mess = "";

		// 作業人員レコードが無い場合は終了
		if (count($pr_workstaff) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_workstaff['DATA_ID'], "作業通知用本文");
		
		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_workstaff);
		
		return $l_return_mess;
	}

/*-------------------------------------------------------------------------------
	処理概要：
		遅延警告通知メールのタイトルを作成する
	引数：
		$pr_user						送信対象ユーザーのデータ
-------------------------------------------------------------------------------*/
	function getDelayAlertMailTitle($pr_user){
		$l_return_mess = "";

		// ユーザーレコードが無い場合は終了
		if (count($pr_user) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_user['DATA_ID'], "遅延通知用件名");
		
		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_user);

		return $l_return_mess;
	}

/*-------------------------------------------------------------------------------
	処理概要：
		遅延警告通知メールの文言を作成する
	引数：
		$pr_user					送信対象ユーザーのデータ
-------------------------------------------------------------------------------*/
	function getDelayAlertMailMess($pr_user){
		$l_return_mess = "";

		// ユーザーレコードが無い場合は終了
		if (count($pr_user) < 1){
			return $l_return_mess;
		}

		// メッセージ取得
		$l_return_mess = $this->getMessageTemplate($pr_user['DATA_ID'], "遅延通知用本文");
		
		// メッセージを置換
		$l_return_mess = $this->replaceAll($l_return_mess, $pr_user);
		
		return $l_return_mess;
	}


/*-------------------------------------------------------------------------------
	処理概要：
		作業報告画面で表示される注意事項
	引数：
-------------------------------------------------------------------------------*/
	function getWorkDetailCaution(){
		$l_return_mess = "";

		$l_return_mess = "※".SYSTEM_NAME.MESSAGE_ATTENTION_AHEAD.MESSAGE_ATTENTION_BACK;

		return $l_return_mess;
	}
/*-------------------------------------------------------------------------------
	処理概要：
		携帯用作業報告画面で表示される注意事項
	引数：
-------------------------------------------------------------------------------*/
	function getWorkDetailCautionMobile(){
		$l_return_mess = "";

		$l_return_mess = "<font color=\"#ff0000\" size=\"2\">※".SYSTEM_NAME.MESSAGE_ATTENTION_AHEAD."</font><br><font color=\"#ff0000\" size=\"2\">".MESSAGE_ATTENTION_BACK."</font>";

		return $l_return_mess;
	}
}
?>
