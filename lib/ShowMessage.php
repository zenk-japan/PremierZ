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
class ShowMessage {
/******************************************************************************
 クラス名  ：ShowMessage
 処理概要  ：メッセージ画面表示
******************************************************************************/
/*----------------------------------------------------------------------------
  クラス変数
  ----------------------------------------------------------------------------*/
	private $proc_mode;									// モード
	private $ext_message;								// 追加メッセージ
	private $ci_smarty;									// smartyクラスインスタンス
	private $template_file	= 'Message.tpl';			// smartyテンプレート
	private $debug_mode = 0;
	private $login_php;									// ログイン画面

/*----------------------------------------------------------------------------
	コンストラクタ
	引数:
			$p_proc_mode					モード
												ST:セッションタイムアウト
												ER:予期せぬエラー
  ----------------------------------------------------------------------------*/
	function __construct($p_proc_mode = 'ER'){
		if($this->debug_mode==1){print("Step-__construct 開始");print "<br>";}
		// 変数格納
		$this->proc_mode	= $p_proc_mode;
		
		// ログインページ
		$this->login_php	= DIR_PAGE.'entrance.php';
		
	/*-----------------------------------
		Smarty変数定義
	  -----------------------------------*/
		$ar_css_files	= array(DIR_CSS."v_common.css", DIR_CSS."v_messcommon.css");
		$ar_js_files	= array(DIR_JS."jquery.js", DIR_JS."jfuc_common.js");

	/*-----------------------------------
		smartyクラスインスタンス作成
	  -----------------------------------*/
		require_once('../Smarty/libs/Smarty.class.php');
		$this->ci_smarty = new Smarty();
		if(is_null($this->ci_smarty)){
			print "smartyエラー。システム管理者に対処を依頼して下さい。";
			return false;
		}
		$this->ci_smarty->template_dir = DIR_TEMPLATES;
		$this->ci_smarty->compile_dir  = DIR_TEMPLATES_C;
		
	/*-----------------------------------
		基本情報セット
	  -----------------------------------*/
		$this->ci_smarty->assign("headtitle"		,"メッセージ");			// 画面タイトル
		$this->ci_smarty->assign("ar_js_files"		,$ar_js_files);			// jsファイル
		$this->ci_smarty->assign("ar_css_files"		,$ar_css_files);		// CSSファイル
		$this->ci_smarty->assign("systemname"		,SYSTEM_NAME);			// システム名
		
		
		
		if($this->debug_mode==1){print("Step-__construct 完了");print "<br>";}
	}
/*----------------------------------------------------------------------------
	メッセージ画面表示
  ----------------------------------------------------------------------------*/
	function showMessage(){
		if($this->debug_mode==1){print("Step-showMessage 開始");print "<br>";}
	/*-----------------------------------
		セッション破棄
	  -----------------------------------*/
		$this->deleteSession();
		
	/*-----------------------------------
		メッセージセット
	  -----------------------------------*/
		switch($this->proc_mode){
			case "ST":
				// セッションタイムアウトの場合
				$lr_setting = array(
								'mode'		=> $this->proc_mode,
								'nexturl'	=> $this->login_php,
								'extmess'	=> $this->ext_message
								);
				break;
			case "ER":
			default:
				// 予期せぬ例外の場合
				$lr_setting = array(
								'mode'		=> $this->proc_mode,
								'nexturl'	=> $this->login_php,
								'extmess'	=> $this->ext_message
								);
				break;
		}
		$this->ci_smarty->assign("mess_item"	,$lr_setting);
		
	/*-----------------------------------
		表示
	  -----------------------------------*/
		if($this->ci_smarty){
			$this->ci_smarty->display($this->template_file);
		}
		
		if($this->debug_mode==1){print("Step-showMessage 完了");print "<br>";}
	}
/*----------------------------------------------------------------------------
	追加メッセージセット
	引数:	$p_data							追加メッセージテキスト
  ----------------------------------------------------------------------------*/
	function setExtMessage($p_data){
		$this->ext_message	= $p_data;
	}
/*----------------------------------------------------------------------------
	セッション破棄
  ----------------------------------------------------------------------------*/
	function deleteSession(){
		require_once('../lib/sessionControl.php');
		$lc_sessc = new sessionControl();
		$lc_sessc->destroySession();
	}
}
?>