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
class PasswordProduction{
/*============================================================================
  パスワード設定クラス
  クラス名：PasswordProduction
  ============================================================================*/
	private $r_user;										// 対象ユーザー
	private $mailsend_flag;									// メール送信成功フラグ(0:未送信、1:送信済)
	private $userwrite_flag;								// ユーザー表書込フラグ(0:未書込、1:書込済)
	
	private $debug_mode = 0;
	
/*============================================================================
	コンストラクタ
	引数:
			$p_user_id								ユーザID
			$p_user_code							ユーザコード
			$p_use_company_code						利用会社
  ============================================================================*/
	function __construct($p_user_id = '', $p_user_code = '', $p_use_company_code = ''){
		if($this->debug_mode==1){print("Step-construct-開始");print "<br>";}
		
		// 変数初期化
		$this->r_user			= array();
		$this->mailsend_flag	= 0;
		$this->userwrite_flag	= 0;
		
		// ユーザーIDまたは、ユーザーコードと利用会社のセットが設定されていない場合は終了
		if ($p_user_id == '' and ($p_user_code == '' or $p_use_company_code == '')){
			return false;
		}
		
		// 対象ユーザー取得
		require_once('../mdl/m_user_master.php');
		
		// エスケープ用のクラスインスタンス作成
		$lc_user_dummy = new m_user_master();
		
		// 検索条件設定
		if ($p_user_id != ''){
			$lr_user_cond_dtl = array('USER_ID = "'.$lc_user_dummy->getMysqlEscapedValue($p_user_id).'"');
		}else{
			$lr_user_cond_dtl = array('USER_CODE = "'.$lc_user_dummy->getMysqlEscapedValue($p_user_code).'"');
			array_push($lr_user_cond_dtl, 'USE_COMPANY_CODE = "'.$lc_user_dummy->getMysqlEscapedValue($p_use_company_code).'"');
		}
		array_push($lr_user_cond_dtl, 'VALIDITY_FLAG = "Y"');
		
		// レコード取得
		$l_user_mum_dtl = new m_user_master('Y', $lr_user_cond_dtl);
		$this->r_user = $l_user_mum_dtl->getViewRecord();
		
		/*
		var_dump($lr_user_cond_dtl);
		print "------------------<br>";
		var_dump($this->r_user);
		*/
		if($this->debug_mode==1){print("Step-construct-完了");print "<br>";}
	}

/*============================================================================
	トークンチェック
	概要:		引数のトークンとコンストラクタで読み込んだユーザーのトークンが
				一致しているかチェックする
	引数:
				$p_token					トークン
  ============================================================================*/
	function checkToken($p_token){
		$l_return_value = "";	// 0:チェックOK、1:チェックNG
		
		// トークンが空の場合はNG
		if ($p_token == ""){
			$l_return_value = 1;
			return $l_return_value;
		}
		
		// ユーザーデータが読み込まれていない場合はNG
		if (count($this->r_user) == 0){
			$l_return_value = 1;
			return $l_return_value;
		}
		
		/*
		print "USERS:";
		print $this->r_user[1]['RESERVE_1'];
		print "<br>";
		print "p_token:";
		print $p_token;
		print "<br>";
		*/

		// パラメータのトークンとユーザーデータのトークンが一致したらOK
		if ($this->r_user[1]['RESERVE_1'] == $p_token){
			$l_return_value = 0;
		}else{
			$l_return_value = 1;
		}
		
		return $l_return_value;
	}
/*============================================================================
	パスワード変更依頼URL送付
	引数:
				$p_mode					モード（1:PC or 2:MOBILE)
  ============================================================================*/
	function sendPasswordResetRequiestURL($p_mode){
		require_once('../lib/MailSettings.php');
		require_once('../lib/SendPHPMail.php');
		require_once('../lib/sessionControl.php');
		require_once('../lib/CommonMessage.php');
		
		// ユーザーが取得できていない場合は終了
		if (count($this->r_user) == 0){
			return true;
		}
		
		// メール設定読込
		$lc_mails = new MailSettings($this->r_user[1]['DATA_ID']);
			
		// トークンを取得する
		$lc_sess = new sessionControl();
		$l_token_for_url = $lc_sess->createToken();
		//{print "<pre>";var_dump($l_token_for_url);print "</pre>";}
			
		// パスワードリセット用URL作成
		$l_pass_reset_url = getBaseURL();
		if ($p_mode === 1){
			// PC
			$l_pass_reset_url .= FILE_PC_PRESET."?token=".$l_token_for_url."&mode=".$this->r_user[1]['USER_ID'];
		}else{
			// MOBILE
			$l_pass_reset_url .= FILE_MOBILE_PRESET."?token=".$l_token_for_url."&mode=".$this->r_user[1]['USER_ID'];
		}
		
		// メール文面作成
		// テンプレートの取得
		$lc_mess = new CommonMessage();
		$l_mess_title	= $lc_mess->getMessageTemplate($this->r_user[1]['DATA_ID'], "パスワードリセット用件名");
		$l_mess_body	= $lc_mess->getMessageTemplate($this->r_user[1]['DATA_ID'], "パスワードリセット用本文");
			
		// 置換処理
		$l_mess_title	= $lc_mess->getReplacedStrings($l_mess_title, $lc_mess->prov_user_name, $this->r_user[1]['NAME']);
		$l_mess_body	= $lc_mess->getReplacedStrings($l_mess_body, $lc_mess->prov_user_name, $this->r_user[1]['NAME']);
		$l_mess_body	= $lc_mess->getReplacedStrings($l_mess_body, $lc_mess->prov_passreset_url, $l_pass_reset_url);
			
		// メール設定
		$lc_phpmail = new SendPHPMail($this->r_user[1]['DATA_ID']);
		$lc_phpmail->setFromaddr($lc_mails->getMailAddr1());				// From
		if ($p_mode === 1){
			// PC
			$lc_phpmail->setToAddress($this->r_user[1]['HOME_MAIL']);	// To
		}else{
			// MOBILE
			$lc_phpmail->setToAddress($this->r_user[1]['MOBILE_PHONE_MAIL']);	// To
		}
		$lc_phpmail->setCcAddress($lc_mails->getMailAddr2());				// Cc
		$lc_phpmail->setSubject($l_mess_title);								// Subject
		$lc_phpmail->setBody($l_mess_body);									// Body
		// 送信ログ用データセット
		$lc_phpmail->setLogDataId($this->r_user[1]['DATA_ID']);
		$lc_phpmail->setLogSendUserId($this->r_user[1]['USER_ID']);
		$lc_phpmail->setLogUserId($this->r_user[1]['USER_ID']);
		$lc_phpmail->setSendPurpose("パスワードリセット依頼");
		/*
		print $l_pass_reset_url."<br>";
		print $l_mess_title."<br>";
		print $l_mess_body."<br>";
		*/
			
		// メールを送信
		$l_result = $lc_phpmail->doSend();
		
		// リセット依頼URL通知完了フラグセット
		if ($l_result == 0){
			$this->mailsend_flag = 1;
		}
			
		// メール送信に成功したら、USERS表のRESERVE1にトークンをセット
		if($this->mailsend_flag === 1){
			// m_user_masterクラスインスタンス作成
			$lc_m_user = new m_user_master();
			
			// 保存用レコードセット
			$lr_data = array();
			$lr_data['USER_ID']				= $this->r_user[1]['USER_ID'];
			$lr_data['RESERVE_1']			= $l_token_for_url;
			$lr_data['LAST_UPDATE_USER_ID']	= SYSTEM_USER;
			$lr_data['LAST_UPDATE_DATET']	= date("Y/m/d H:i:s");
			$lc_m_user->setSaveRecord($lr_data);

			// 更新処理
			if(!$lc_m_user->updateRecord($this->r_user[1]['USER_ID'])){
			}else{
				$this->userwrite_flag = 1;
			}
		}
		
		return true;
	}
	
/*============================================================================
	パスワードリセット
	引数:
				$p_length				パスワード指定文字数
				$p_mode					使用する文字列（大小英字 + 数字 + 記号）
  ============================================================================*/
	function passwordReset($p_length = 10, $p_mode = PASS_ALNUMSIG){
		if($this->debug_mode==1){print("Step-passwordReset-開始");print "<br>";}
		
		$l_return_value = "";
		
		// ユーザーが取得できていない場合は終了
		if (count($this->r_user) == 0){
			return $l_return_value;
		}
		
		// パスワード文字列取得
		$l_password_phrase = getPassword($p_length, $p_mode);
		
		// ハッシュ値作成
		$l_password_hash = md5($l_password_phrase);
		
		// USERS変更
		require_once('../mdl/m_user_master.php');
		$lc_m_user = new m_user_master();
		
		// 保存用レコードセット
		$lr_data = array();
		$lr_data['USER_ID']				= $this->r_user[1]['USER_ID'];
		$lr_data['ENCRYPTION_PASSWORD']	= $l_password_hash;
		$lr_data['RESERVE_1']			= '';
		$lr_data['LAST_UPDATE_USER_ID']	= SYSTEM_USER;
		$lr_data['LAST_UPDATE_DATET']	= date("Y/m/d H:i:s");
		$lc_m_user->setSaveRecord($lr_data);

		// 更新処理
		
		if (!$lc_m_user->updateRecord($this->r_user[1]['USER_ID'])){
			$l_return_value = 1;
		}else{
			$l_return_value = $l_password_phrase;
		}
		
		return $l_return_value;
		
		if($this->debug_mode==1){print("Step-passwordReset-完了");print "<br>";}
	}
/*============================================================================
	getter
  ============================================================================*/
	function getUserRec(){
		return $this->r_user;
	}
	function getMailsendFlag(){
		return $this->mailsend_flag;
	}
	function getUserwriteFlag(){
		return $this->userwrite_flag;
	}
	function getUserName(){
		return $this->r_user[1]['NAME'];
	}
}
?>
