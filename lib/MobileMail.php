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
  携帯用のメール送信画面クラス
  クラス名：MobileMail
  ============================================================================*/
class MobileMail {
	private $send_from;						// 送信元
	private $send_to;						// 送信先
	private $mail_title;					// タイトル
	private $mail_text;						// メール本文
	private $send_cc;						// CC
	private $send_bcc;						// BCC
	private $Smarty_Class;					// Smartyのクラスインスタンス
	private $Smarty_Template;				// Smartyのテンプレートファイル
	private $mcommon_Class;					// 携帯用共通関数クラスインスタンス
	private $debug_mode = 0;
	
	// アサイン用
	private $r_assign_data;					// 一括処理用配列
	public	$sm_page_title;					// 画面タイトル
	public	$sm_terminal;					// 端末種別
	public	$sm_model;						// 端末モデル
	public	$sm_token;						// トークン
	public	$sm_logout_page_file_name;		// ログアウトページのファイル名(指定しなければ該当リンクを作成しない)
	public	$sm_man_page_file_name;			// マニュアルページのファイル名(指定しなければ該当リンクを作成しない)
	public	$sm_prev_page_file_name;		// 前のページのファイル名(指定しなければ該当リンクを作成しない)
	public	$sm_prev_page_id_name;			// 前のページの一意キーGET項目名(指定しなければtokenのみ付加)
	public	$sm_prev_page_id_value;			// 前のページの一意キー値
	public	$sm_next_page_file_name;		// 完了を表示するphpファイル
	public	$sm_from_display_flag;			// FROM表示フラグ
	public	$sm_send_from_ro_flag;			// 送信元固定フラグ
	public	$sm_send_to_ro_flag;			// 送信先固定フラグ
	public	$sm_mail_title_ro_flag;			// タイトル固定フラグ
	public	$sm_mail_text_ro_flag;			// メール本文固定フラグ
	public	$sm_send_cc_ro_flag;			// CC固定フラグ
	public	$sm_send_bcc_ro_flag;			// BCC固定フラグ
	private	$r_hidden_param;				// メール送信用のPHPにパラメータを追加する場合に使用
	
/*----------------------------------------------------------------------------
  コンストラクタ
  ----------------------------------------------------------------------------*/
	function __construct(){
		// 変数初期化
		$this->send_from				= "";
		$this->send_to					= "";
		$this->mail_title				= "";
		$this->mail_text				= "";
		$this->send_cc					= "";
		$this->send_bcc					= "";
		$this->sm_page_title			= "";		// 画面タイトル
		$this->sm_terminal				= "";		// 端末種別
		$this->sm_model					= "";		// 端末モデル
		$this->sm_token					= "";		// トークン
		$this->sm_logout_page_file_name	= "";		// ログアウトページのファイル名
		$this->sm_man_page_file_name	= "";		// マニュアルページのファイル名
		$this->sm_prev_page_file_name	= "";		// 前のページのファイル名 $_SERVER['PHP_SELF']
		$this->sm_prev_page_id_name		= "";		// 前のページの一意キーGET項目名 gv_work_staff_id
		$this->sm_prev_page_id_value	= "";		// 前のページの一意キー値
		
		$this->r_hidden_param			= array();	// 追加パラメータ
		
		$this->sm_from_display_flag		= true;		// FROM表示フラグ
		$this->sm_send_from_ro_flag		= false;	// 送信元固定フラグ
		$this->sm_send_to_ro_flag		= false;	// 送信先固定フラグ
		$this->sm_mail_title_ro_flag	= false;	// タイトル固定フラグ
		$this->sm_mail_text_ro_flag		= false;	// メール本文固定フラグ
		$this->sm_send_cc_ro_flag		= false;	// CC固定フラグ
		$this->sm_send_bcc_ro_flag		= false;	// BCC固定フラグ
		
		if($this->debug_mode==1){print("Step-construct-変数初期化");print "<br>";}
		
		// Smarty設定
		require_once('../Smarty/libs/Smarty.class.php');
		$this->Smarty_Class = new Smarty();
		if($this->debug_mode==1){print("Step-construct-Smarty設定");print "<br>";}
		
		$this->Smarty_Class->template_dir	= DIR_TEMPLATES;
		$this->Smarty_Class->compile_dir	= DIR_TEMPLATES_C;
		$this->Smarty_Class->config_dir		= DIR_CONFIGS;
		$this->Smarty_Class->cache_dir		= DIR_CACHE;
		
		$this->Smarty_Template = "MobileMailForm.tpl";
		
		if($this->debug_mode==1){print("Step-construct-Smarty完了");print "<br>";}
		
		// 携帯共通関数インスタンス作成
		require_once('../lib/MobileCommonFunctions.php');
		$this->mcommon_Class = new MobileCommonFunctions();
		
		if($this->debug_mode==1){print("Step-construct-終了");print "<br>";}
	}
	
/*----------------------------------------------------------------------------
  アサイン用データ一括セット
  ----------------------------------------------------------------------------*/
	function setAssignData($pr_data){
		if(count($pr_data) == 0){
			return false;
		}
		if($this->debug_mode==1){print("Step-setAssignData-セット前");print "<br>";}
		
		$this->sm_page_title			= $pr_data['page_title'];			// 画面タイトル
		$this->sm_terminal				= $pr_data['terminal'];				// 端末種別
		$this->sm_model					= $pr_data['model'];				// 端末モデル
		$this->sm_token					= $pr_data['token'];				// トークン
		$this->sm_logout_page_file_name	= $pr_data['logout_page_file_name'];// ログアウトページのファイル名
		$this->sm_man_page_file_name	= $pr_data['man_page_file_name'];	// マニュアルページのファイル名
		$this->sm_prev_page_file_name	= $pr_data['prev_page_file_name'];	// 前のページのファイル名
		$this->sm_prev_page_id_name		= $pr_data['prev_page_id_name'];	// 前のページの一意キーGET項目名
		$this->sm_prev_page_id_value	= $pr_data['prev_page_id_value'];	// 前のページの一意キー値
		$this->sm_next_page_file_name	= $pr_data['next_page_file_name'];	// 完了を表示するphpファイル
		
		if($this->debug_mode==1){print("Step-setAssignData-完了");print "<br>";}
	}
/*----------------------------------------------------------------------------
  アサイン処理
  ----------------------------------------------------------------------------*/
	function procAssign(){
		// ドキュメントタイプ等の取得
		$lr_spdesc = $this->mcommon_Class->getSpecificDescription($this->sm_terminal, $this->sm_model);
		
		// タイトル
		$this->Smarty_Class->assign("headtitle"	, $this->sm_page_title);
		
		// ロゴ
		$this->Smarty_Class->assign("img_logo"	, MOBILE_LOGO);
		
		// ヘッダー部
		$this->Smarty_Class->assign("doctype"	, $lr_spdesc['declaration']);
		$this->Smarty_Class->assign("char_code"	, $lr_spdesc['character_code']);
		$this->Smarty_Class->assign("xmlns"		, $lr_spdesc['xmlns']);
		$this->Smarty_Class->assign("terminal"	, $this->sm_terminal);
		$this->Smarty_Class->assign("model"		, $this->sm_model);
		if($this->debug_mode==1){print("Step-procAssign-ヘッダー部");print "<br>";}
		
		// 完了を表示するphpファイル
		$this->Smarty_Class->assign("form_action", $this->sm_next_page_file_name);
		
		if($this->debug_mode==1){print("Step-procAssign-絵文字変換");print "<br>";}
		
		// 送信元
		if(!is_null($this->send_from) && $this->send_from != ''){
			$this->Smarty_Class->assign("from_addr"	, $this->send_from);
			if($this->sm_send_from_ro_flag){
				$this->Smarty_Class->assign("from_addr_readonly"	, "readOnly");
			}
			// 表示要否設定
			$this->Smarty_Class->assign("from_display_flag"	, $this->sm_from_display_flag);
		}
		
		// 送信先
		if(!is_null($this->send_to) && $this->send_to != ''){
			$this->Smarty_Class->assign("to_addr"	, $this->send_to);
			if($this->sm_send_to_ro_flag){
				$this->Smarty_Class->assign("to_addr_readonly"	, "readOnly");
			}
		}
		
		// CC
		if(!is_null($this->send_cc) && $this->send_cc != ''){
			$this->Smarty_Class->assign("cc_addr"	, $this->send_cc);
			if($this->sm_send_cc_ro_flag){
				$this->Smarty_Class->assign("cc_addr_readonly"	, "readOnly");
			}
		}
			
		// BCC
		if(!is_null($this->send_bcc) && $this->send_bcc != ''){
			$this->Smarty_Class->assign("bcc_addr"	, $this->send_bcc);
			if($this->sm_send_bcc_ro_flag){
				$this->Smarty_Class->assign("bcc_addr_readonly"	, "readOnly");
			}
		}
			
		// タイトル
		$this->Smarty_Class->assign("mail_title"	, $this->mail_title);
		if($this->sm_mail_title_ro_flag){
			$this->Smarty_Class->assign("mail_title_readonly"	, "readOnly");
		}
		
		// 本文
		$this->Smarty_Class->assign("mail_text"		, $this->mail_text);
		if($this->sm_mail_text_ro_flag){
			$this->Smarty_Class->assign("mail_text_readonly"	, "readOnly");
		}
		
		// パラメータ
		$this->Smarty_Class->assign("ar_param"		, $this->r_hidden_param);
		
		// ハイパーリンク
		$lr_bottom_menu = array();
		// 前画面へ戻る
		if($this->sm_prev_page_file_name != ""){
			// リンクの記述
			$l_option_desc = $this->sm_prev_page_file_name."?token=".$this->sm_token;
			if($this->sm_prev_page_id_name != ""){
				// 前画面に渡すGET引数が有る場合はリンクに追加する
				$l_option_desc .= "&".$this->sm_prev_page_id_name."=".$this->sm_prev_page_id_value;
			}
			array_push($lr_bottom_menu,
							array(
									"link_url"	=>	$l_option_desc,
									"value"		=>	"前画面へ戻る",
									"key"		=>	$this->mcommon_Class->getAccessKeyPhrase($this->sm_terminal, $this->sm_model, "2")
								)
						);
		}
		// ログアウト
		if($this->sm_logout_page_file_name != ""){
			array_push($lr_bottom_menu,
							array(
									"link_url"	=>	$this->sm_logout_page_file_name."?token=".$this->sm_token,
									"value"		=>	"ログアウト",
									"key"		=>	$this->mcommon_Class->getAccessKeyPhrase($this->sm_terminal, $this->sm_model, "9")
								)
						);
		}
		// 操作マニュアル
		/*
		if($this->sm_man_page_file_name != ""){
			array_push($lr_bottom_menu,
							array(
									"link_url"	=>	$this->sm_man_page_file_name."?token=".$this->sm_token,
									"value"		=>	"操作マニュアル",
									"key"		=>	$this->mcommon_Class->getAccessKeyPhrase($this->sm_terminal, $this->sm_model, "#")
								)
						);
		}
		*/
		$this->Smarty_Class->assign("fmlink",	$lr_bottom_menu);
		if($this->debug_mode==1){print("Step-procAssign-ハイパーリンク");print "<br>";}
			
		// コピーライト
		$this->Smarty_Class->assign("txt_copyright", $this->mcommon_Class->getCopyRight());
		
		if($this->debug_mode==1){print("Step-procAssign-終了");print "<br>";}
	}
/*----------------------------------------------------------------------------
  ページの表示
  ----------------------------------------------------------------------------*/
	function showPage(){
		if($this->debug_mode==1){print("Step-showPage-開始");print "<br>";}
		$this->Smarty_Class->display($this->Smarty_Template);
		if($this->debug_mode==1){print("Step-showPage-終了");print "<br>";}
	}

/*============================================================================
  Getter
  ============================================================================*/
/*----------------------------------------------------------------------------
  Smartyのクラスインスタンス
  ----------------------------------------------------------------------------*/
	function getSmartyClass(){
		return $this->Smarty_Class;
	}
	
/*============================================================================
  Setter
  ============================================================================*/
/*----------------------------------------------------------------------------
  送信元
  ----------------------------------------------------------------------------*/
	function setFrom($p_data){
		if(!is_null($p_data) && $p_data != ''){
			$this->send_from	= $p_data;
		}else{
			return false;
		}
	}
	
/*----------------------------------------------------------------------------
  送信先
  ----------------------------------------------------------------------------*/
	function setTo($p_data_rec){
		if(is_array($p_data_rec)){
			// 配列の場合
			if(count($p_data_rec) > 0){
				$l_set_value = "";
				$l_rec_count = 0;
				foreach($p_data_rec as $l_value){
					$l_rec_count++;
					if($l_rec_count == 1){
						$l_set_value .= $l_value;
					}else{
						$l_set_value .= ",".$l_value;
					}
				}
				$this->send_to	= $l_set_value;
			}else{
				return false;
			}
		}else{
			// 配列以外の場合
			if(!is_null($p_data_rec) && $p_data_rec != ''){
				$this->send_to	= $p_data_rec;
			}else{
				return false;
			}
		}
	}

/*----------------------------------------------------------------------------
  CC
  ----------------------------------------------------------------------------*/
	function setCC($p_data_rec){
		if(is_array($p_data_rec)){
			// 配列の場合
			if(count($p_data_rec) > 0){
				$l_set_value = "";
				$l_rec_count = 0;
				foreach($p_data_rec as $l_value){
					$l_rec_count++;
					if($l_rec_count == 1){
						$l_set_value .= $l_value;
					}else{
						$l_set_value .= ",".$l_value;
					}
				}
				$this->send_cc	= $l_set_value;
			}else{
				return false;
			}
		}else{
			// 配列以外の場合
			if(!is_null($p_data_rec) && $p_data_rec != ''){
				$this->send_cc	= $p_data_rec;
			}else{
				return false;
			}
		}
	}

/*----------------------------------------------------------------------------
  BCC
  ----------------------------------------------------------------------------*/
	function setBCC($p_data_rec){
		if(is_array($p_data_rec)){
			// 配列の場合
			if(count($p_data_rec) > 0){
				$l_set_value = "";
				$l_rec_count = 0;
				foreach($p_data_rec as $l_value){
					$l_rec_count++;
					if($l_rec_count == 1){
						$l_set_value .= $l_value;
					}else{
						$l_set_value .= ",".$l_value;
					}
				}
				$this->send_bcc	= $l_set_value;
			}else{
				return false;
			}
		}else{
			// 配列以外の場合
			if(!is_null($p_data_rec) && $p_data_rec != ''){
				$this->send_bcc	= $p_data_rec;
			}else{
				return false;
			}
		}
	}

/*----------------------------------------------------------------------------
  タイトル
  ----------------------------------------------------------------------------*/
	function setMailTitle($p_data){
		$this->mail_title	= $p_data;
	}

/*----------------------------------------------------------------------------
  メール本文
  ----------------------------------------------------------------------------*/
	function setMailText($p_data){
		$this->mail_text	= $p_data;
	}
/*----------------------------------------------------------------------------
  追加パラメータ
  ----------------------------------------------------------------------------*/
	function setHiddenParam($p_data_rec){
		if(is_array($p_data_rec)){
			// 配列の場合
			if(count($p_data_rec) > 0){
				$this->r_hidden_param	= $p_data_rec;
			}else{
				return false;
			}
		}else{
			// 配列以外の場合
			return false;
		}
	}
}
?>