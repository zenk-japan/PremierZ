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
/*============================================================================
  メール設定クラス
  クラス名：MailSettings
  ============================================================================*/
class MailSettings {
	private	$data_id;								// DATA_ID
	private	$mail_addr1;							// 作業取り纏めのメールアドレス
	private	$mail_addr2;							// 勤怠報告用のメールアドレス
	private	$mail_addr3;							// 予備アドレス
	private	$mail_host;								// メールサーバーのホスト
	private	$mail_port;								// メールサーバーのポート
	private	$mail_username;							// メールサーバーのユーザー
	private	$mail_key;								// メールサーバーのキー
	private	$flag_update_user_id;					// 遅延警告発信時のユーザーID

	private $debug_mode = 0;
	
/*----------------------------------------------------------------------------
  コンストラクタ
	引数:
			$p_data_id							DATA_ID
  ----------------------------------------------------------------------------*/
	function __construct($p_data_id){
		if($this->debug_mode==1){print("Step-construct-開始");print "<br>";}
		
		// data_idが取得できない場合は異常終了
		if(is_null($p_data_id) || $p_data_id == ''){
			return false;
		}else{
			$this->data_id = $p_data_id;
		}
		
		// メール設定用MDL読込
		require_once('../mdl/m_common_master.php');
		$lc_mailm = new m_common_master();
		
		$this->mail_addr1			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_ADDR1');
		$this->mail_addr2			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_ADDR2');
		$this->mail_addr3			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_ADDR3');
		$this->mail_host			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_HOST');
		$this->mail_port			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_PORT');
		$this->mail_username		= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_USERNAME');
		$this->mail_key				= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_KEY');
		$this->mail_secure			= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'MAIL_SECURE');;
		$this->flag_update_user_id	= $lc_mailm->getCommonValue($this->data_id, 'MAIL_SETTINGS', 'FLAG_UPDATE_USER_ID');
		
		if($this->debug_mode==1){print("Step-construct-終了");print "<br>";}
	}

/*----------------------------------------------------------------------------
  Getter
  ----------------------------------------------------------------------------*/
	function getMailAddr1(){
		return $this->mail_addr1;
	}
	function getMailAddr2(){
		return $this->mail_addr2;
	}
	function getMailAddr3(){
		return $this->mail_addr3;
	}
	function getMailHost(){
		return $this->mail_host;
	}
	function getMailPort(){
		return $this->mail_port;
	}
	function getMailUsername(){
		return $this->mail_username;
	}
	function getMailKey(){
		return $this->mail_key;
	}
	function getMailSecure(){
		return $this->mail_secure;
	}
	function getFlagUpdateUserID(){
		return $this->flag_update_user_id;
	}
}
?>