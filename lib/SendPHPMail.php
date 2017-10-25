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


//ライブラリ読み込み
require_once('../lib/CommonStaticValue.php');
require_once('../PHPMailer/class.phpmailer.php');
/*============================================================================
  Gメール送信クラス
  クラス名：SendPHPMail
  ============================================================================*/
class SendPHPMail {
	private	$fromadd;						// 送信元アドレス
	private	$fromname;						// 送信者名
	private	$sendadd;						// 送信される全アドレス(カンマ区切り)
	private	$r_to_address;					// ヘッダー表示用-Toアドレス
	private	$r_cc_address;					// ヘッダー表示用-Ccアドレス
	private	$r_bcc_address;					// ヘッダー表示用-Bccアドレス
	private	$subject;						// 件名
	private	$body;							// 本文
	
	// 送信ログ用の未エンコード設定
	private	$noenc_fromadd;					// 送信元アドレス
	private	$noenc_fromname;				// 送信者名
	private	$noenc_sendadd;					// 送信される全アドレス(カンマ区切り)
	private	$noenc_to_address;				// ヘッダー表示用-Toアドレス(カンマ区切り)
	private	$noenc_cc_address;				// ヘッダー表示用-Ccアドレス(カンマ区切り)
	private	$noenc_bcc_address;				// ヘッダー表示用-Bccアドレス(カンマ区切り)
	private	$noenc_subject;					// 件名
	private	$noenc_body;					// 本文
	
	private	$internal_ccd;					// mb_internal_encodingの文字コード
	private	$input_ccd;						// mb_encode_mimeheaderに渡す文字列の文字コード
	private	$charset;						// PHPMailerのCharSet用
	private	$encoding;						// PHPMailerのEncoding用
	private	$output_ccd;					// mime変換する最終文字コード(body)
	private	$log_data_id;					// 送信ログ用DATA_ID
	private	$log_send_user_id;				// 送信ログ用送信者ユーザーID
	private	$log_user_id;					// 送信ログ用ログユーザーID

	private	$sg_mail_host;					// 接続先
	private	$sg_mail_port;					// ポート
	private	$sg_mail_username;				// ユーザアカウント
	private	$sg_mail_password;				// パスワード

	private $c_PHPMailer;					// PHPMailerクラスインスタンス

/*----------------------------------------------------------------------------
  コンストラクタ
  ----------------------------------------------------------------------------*/
	function __construct($p_data_id = ''){
		// 変数初期化
		$this->fromadd			= "";
		$this->fromname			= "";
		$this->sendadd			= "";
		$this->r_to_address		= "";
		$this->r_cc_address		= "";
		$this->r_bcc_address	= "";
		$this->subject			= "";
		$this->body				= "";
		$this->internal_ccd		= 'UTF-8';
		$this->input_ccd		= 'UTF-8';
		$this->output_ccd		= 'JIS';
		$this->charset			= 'iso-2022-jp';
		$this->encoding			= '7bit';

		$this->sg_mail_host		= "";
		$this->sg_mail_port		= "";
		$this->sg_mail_username	= "";
		$this->sg_mail_password	= "";

		// メールサーバー設定
		$this->setupMailServerInfo($p_data_id);

		// 言語設定、内部エンコーディングを指定する
		mb_language("japanese");
		mb_internal_encoding($this->internal_ccd);

		// PHPMailerインスタンス作成
		$this->c_PHPMailer				= new PHPMailer();
		$this->c_PHPMailer->CharSet		= $this->charset;
		$this->c_PHPMailer->Encoding	= $this->encoding;
		
		$this->c_PHPMailer->IsSMTP();
		if ($this->sg_mail_host != 'localhost') {
			$this->c_PHPMailer->SMTPAuth	= TRUE;
		}
		
		// メールセキュアの設定があれば設定
		if (trim($this->sg_mail_secure)){
			$this->c_PHPMailer->SMTPSecure	= $this->sg_mail_secure;
		}
		
		// SMTP認証
		if (is_null($this->sg_mail_host) || $this->sg_mail_host == '') {
		}else{
			if (is_null($this->sg_mail_port) || $this->sg_mail_port == '') {
				$this->c_PHPMailer->Host	= $this->sg_mail_host;
			}else {
				$this->c_PHPMailer->Host	= $this->sg_mail_host.':'.$this->sg_mail_port;
			}
		}
		
		if (is_null($this->sg_mail_username) || $this->sg_mail_username == '') {
		}else{
			$this->c_PHPMailer->Username	= $this->sg_mail_username;
			$this->c_PHPMailer->Password	= $this->sg_mail_password;
		}
	}
/*----------------------------------------------------------------------------
  カンマ区切り->配列変換
  概要：
  		カンマ区切りのデータを配列に再編成する
  ----------------------------------------------------------------------------*/
	function makeArray($p_data){
		if(is_null($p_data) || $p_data == ''){
			return '';
		}
		
		$lr_result = explode(",", $p_data);
		
		return $lr_result;
	}
/*----------------------------------------------------------------------------
  setter
  ----------------------------------------------------------------------------*/
	function setFromaddr($p_data){
		$this->fromadd			= $p_data;
		$this->noenc_fromadd	= $p_data;
	}
	function setFromname($p_data){
		$this->fromname			= mb_encode_mimeheader(mb_convert_encoding($p_data, $this->output_ccd, $this->internal_ccd));
		$this->noenc_fromname	= $p_data;
	}
	function setSendaddr($p_data){
		$this->sendadd			= $p_data;
		$this->noenc_sendadd	= $p_data;
	}
	function setToAddress($p_data){
		$this->r_to_address		= $this->makeArray($p_data);
		$this->noenc_to_address	= $p_data;
	}
	function setCcAddress($p_data){
		$this->r_cc_address		= $this->makeArray($p_data);
		$this->noenc_cc_address	= $p_data;
	}
	function setBccAddress($p_data){
		$this->r_bcc_address		= $this->makeArray($p_data);
		$this->noenc_bcc_address	= $p_data;
	}
	function setSubject($p_data){
		// 件名
		//$this->subject = mb_encode_mimeheader(mb_convert_encoding($p_data, $this->input, mb_detect_encoding($p_data)), $this->target);
		$this->subject			= mb_encode_mimeheader(mb_convert_encoding($p_data, $this->output_ccd, $this->internal_ccd));
		$this->noenc_subject	= $p_data;
	}
	function setBody($p_data){
		// 本文
		//$this->body = mb_convert_encoding($p_data, $this->output, mb_detect_encoding($p_data));
		$this->body				= mb_convert_encoding($p_data, $this->output_ccd, $this->internal_ccd);
		$this->noenc_body		= $p_data;
	}
	function setLogDataId($p_data){
		$this->log_data_id		= $p_data;
	}
	function setLogSendUserId($p_data){
		$this->log_send_user_id	= $p_data;
	}
	function setLogUserId($p_data){
		$this->log_user_id		= $p_data;
	}
	function setSendPurpose($p_data){
		$this->log_send_purpose	= $p_data;
	}

/*----------------------------------------------------------------------------
  メール送信ログ書込み
	概要：メール送信ログを書き込む
  ----------------------------------------------------------------------------*/
	function writeMailLog(){
		require_once('../mdl/m_mail_log.php');
		$lc_mml = new m_mail_log();
		$lr_data = array();
		$lr_data["DATA_ID"]			= $this->log_data_id;
		$lr_data["SEND_USER_ID"]	= $this->log_send_user_id;
		$lr_data["FROM_ADDRESS"]	= $this->noenc_fromadd;
		$lr_data["TO_ADDRESS"]		= $this->noenc_to_address;
		$lr_data["CC_ADDRESS"]		= $this->noenc_cc_address;
		$lr_data["BCC_ADDRESS"]		= $this->noenc_bcc_address;
		$lr_data["MAIL_TITLE"]		= $this->noenc_subject;
		$lr_data["MAIL_BODY"]		= $this->noenc_body;
		$lr_data["SEND_PURPOSE"]	= $this->log_send_purpose;
		$lr_data["VALIDITY_FLAG"]	= "Y";
		$lr_data["USER_ID"]			= $this->log_user_id;
		$lc_mml->setSaveRecord($lr_data);			// レコードセット
		$lc_mml->insertRecord();					// INSERT
	}

/*----------------------------------------------------------------------------
  メールサーバー設定
	引数:
			$p_data_id							DATA_ID
  ----------------------------------------------------------------------------*/
	function setupMailServerInfo($p_data_id){
		if(is_null($p_data_id) || $p_data_id == ''){
			// DATA_IDの設定が無い場合は終了
			return false;
		}else{
			// メール設定読込
			require_once('../lib/MailSettings.php');
			$lc_mails = new MailSettings($p_data_id);

			// サーバー情報設定
			$this->sg_mail_host		= $lc_mails->getMailHost();			// 接続先
			$this->sg_mail_port		= $lc_mails->getMailPort();			// ポート
			$this->sg_mail_username	= $lc_mails->getMailUsername();		// ユーザアカウント
			$this->sg_mail_password	= $lc_mails->getMailKey();			// パスワード
			$this->sg_mail_secure	= $lc_mails->getMailSecure();		// メールセキュア

			return true;
		}
	}

/*----------------------------------------------------------------------------
  メール送信
  ----------------------------------------------------------------------------*/
	function doSend(){
		$l_result = 0;
		
 		if ($this->c_PHPMailer){
 			// to
 			foreach ($this->r_to_address as $l_num => $l_to_address){
 				$this->c_PHPMailer->AddAddress($l_to_address);
 			}
 			// cc
			if(!is_null($this->noenc_cc_address) && $this->noenc_cc_address != ''){
	 			foreach ($this->r_cc_address as $l_num => $l_cc_address){
	 				$this->c_PHPMailer->AddCC($l_cc_address);
	 			}
			}
			// bcc
			if(!is_null($this->noenc_bcc_address) && $this->noenc_bcc_address != ''){
	 			foreach ($this->r_bcc_address as $l_num => $l_bcc_address){
					$this->c_PHPMailer->AddBCC($l_bcc_address);
	 			}
			}
			$this->c_PHPMailer->From		= $this->fromadd;		// 送信者アドレス
			$this->c_PHPMailer->FromName	= $this->fromname;		// 送信者名
			$this->c_PHPMailer->Subject		= $this->subject;		// 件名
			$this->c_PHPMailer->Body		= $this->body;			// 本文
			
			if (!$this->c_PHPMailer->Send()){
			    // 送信ログの本文をエラーメッセージに変更
				$this->body = "【送信失敗】"."\n".$this->c_PHPMailer->ErrorInfo;

				// 送信ログ書き込み
				$this->writeMailLog();

				$l_result = 1;
			}else{
				// 送信ログ書き込み
				$this->writeMailLog();

				$l_result = 0;
			}
		}else{
			$l_result = 1;
		}
		return $l_result;
	}
}
?>
