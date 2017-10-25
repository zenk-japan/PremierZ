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
require_once('../lib/MailSettings.php');
/*============================================================================
  携帯用共通関数
  クラス名：MobileCommonFunctions
  ============================================================================*/
class MobileCommonFunctions {
/*----------------------------------------------------------------------------
  キャリア別のヘッダー記載情報の取得
  引数:				$p_terminal		キャリア
  					$p_model		モデル
  ----------------------------------------------------------------------------*/
	function getSpecificDescription($p_terminal, $p_model){
		$lr_return_array	= "";
		$l_char_code		= "character_code";		// 文字コード
		$l_declaration		= "declaration";		// ドキュメントタイプ宣言
		$l_xmlns			= "xmlns";				// XML名前空間
		
		switch ($p_terminal){
			case TERMINAL_DOCOMO :
				$lr_return_array[$l_char_code]		=	"Shift_JIS";
				$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.0) 1.0//EN\" \"i-xhtml_4ja_10.dtd\">";
				$lr_return_array[$l_xmlns]			=	" xmlns=\"http://www.w3.org/1999/xhtml\"";
				break;
			case TERMINAL_SOFTBANK :
				if($p_model == AGENT_IPHONE){
					$lr_return_array[$l_char_code]		=	"UTF-8";
					$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\">";
					$lr_return_array[$l_xmlns]			=	"";
				} else {
					$lr_return_array[$l_char_code]		=	"Shift_JIS";
					$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//J-PHONE//DTD XHTML Basic 1.0 Plus//EN\" \"xhtml-basic10-plus.dtd\">";
					$lr_return_array[$l_xmlns]			=	" xmlns=\"http://www.w3.org/1999/xhtml\"";
				}
				break;
			case TERMINAL_AU :
				$lr_return_array[$l_char_code]		=	"Shift_JIS";
				$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML Basic 1.0//EN\" \"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd\">";
				$lr_return_array[$l_xmlns]			=	" xmlns=\"http://www.w3.org/1999/xhtml\"";
				break;
			case TERMINAL_WILLCOM :
				$lr_return_array[$l_char_code]		=	"UTF-8";
				$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\" \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
				$lr_return_array[$l_xmlns]			=	" xmlns=\"http://www.w3.org/1999/xhtml\"";
				break;
			case TERMINAL_PC :
				$lr_return_array[$l_char_code]		=	"UTF-8";
				$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\">";
				$lr_return_array[$l_xmlns]			=	"";
				break;
			default :
				$lr_return_array[$l_char_code]		=	"UTF-8";
				$lr_return_array[$l_declaration]	=	"<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\" \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
				$lr_return_array[$l_xmlns]			=	" xmlns=\"http://www.w3.org/1999/xhtml\"";
		}
		
		return $lr_return_array;
	}
/*----------------------------------------------------------------------------
  キャリア別のアクセスキーの記述取得
  引数:				$p_terminal		キャリア
  					$p_model		モデル
  					$p_key			キー
  ----------------------------------------------------------------------------*/
	function getAccessKeyPhrase($p_terminal, $p_model, $p_key){
		$l_return_value = "";
		
		if($p_terminal == TERMINAL_SOFTBANK && $p_model != "iPhone"){
			$l_return_value = "directkey=\"".$p_key."\" nonumber";
		}else if($p_terminal == "PersonalComputer"){
			$l_return_value = "";			// PCはアクセスキーをつけない
		}else{
			$l_return_value = "accesskey=\"".$p_key."\"";
		}
		
		return $l_return_value;
	}
/*----------------------------------------------------------------------------
  キャリア別の入力モードの指定の取得
  引数:				$p_terminal		キャリア
  					$p_key			キー
  ----------------------------------------------------------------------------*/
	function getInputModePhrase($p_terminal, $p_key){
		$l_return_value = "";
		
		if($p_terminal == TERMINAL_DOCOMO || $p_terminal == TERMINAL_WILLCOM){
			$l_return_value = "istyle=\"".$p_key."\"";
		}else if($p_terminal == TERMINAL_SOFTBANK){
			$l_return_value = "mode=\"".$p_key."\"";
		}else if($p_terminal == TERMINAL_AU){
			$l_return_value = "format=\"".$p_key."\"";
		}else{
			$l_return_value = "";
		}
		
		return $l_return_value;
	}
/*----------------------------------------------------------------------------
  キャリア別の入力モードの指定となる文字列の取得
  引数:				$p_terminal		キャリア
  					$p_key			キー
  ----------------------------------------------------------------------------*/
	function getInputStylePhrase($p_terminal, $p_key){
		$l_disp_mode = "";
		
		switch ($p_terminal){
			// DoCoMo
			case TERMINAL_DOCOMO :
				switch($p_key){
					//全角かな
					case "HIRAGANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_DOCOMO, INPUT_ISTYLE_HIRAGANA);
					break;
					//半角カナ
					case "HANKAKUKANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_DOCOMO, INPUT_ISTYLE_HANKAKUKANA);
					break;
					//半角英字
					case "ALPHABET" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_DOCOMO, INPUT_ISTYLE_ALPHABET);
					break;
					//半角数字
					case "NUMERIC" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_DOCOMO, INPUT_ISTYLE_NUMERIC);
					break;
				}
			break;
			// SoftBank
			case TERMINAL_SOFTBANK :
				switch($p_key){
					//全角かな
					case "HIRAGANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_SOFTBANK, INPUT_MODE_HIRAGANA);
					break;
					//半角カナ
					case "HANKAKUKANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_SOFTBANK, INPUT_MODE_HANKAKUKANA);
					break;
					//半角英字
					case "ALPHABET" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_SOFTBANK, INPUT_MODE_ALPHABET);
					break;
					//半角数字
					case "NUMERIC" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_SOFTBANK, INPUT_MODE_NUMERIC);
					break;
				}
			break;
			// au
			case TERMINAL_AU :
				switch($p_key){
					//全角かな
					case "HIRAGANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_AU, INPUT_FORMAT_HIRAGANA);
					break;
					//半角カナ
					//半角カナが存在しないので全角かなを指定
					case "HANKAKUKANA" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_AU, INPUT_FORMAT_HIRAGANA);
					break;
					//半角英字
					case "ALPHABET" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_AU, INPUT_FORMAT_SALPHA_NUM);
					break;
					//半角数字
					case "NUMERIC" :
						$l_disp_mode = $this->getInputModePhrase(TERMINAL_AU, INPUT_FORMAT_NUMERIC);
					break;
				}
			break;
			default :
				$l_disp_mode = NULL;
			break;
		}
		
		return $l_disp_mode;
	}
/*----------------------------------------------------------------------------
  セッションチェック
  引数:				&$p_token		トークン(実行終了後に新しいトークンに変更)
  ----------------------------------------------------------------------------*/
	function sessionCheck(&$p_token){
		$l_new_token = "";
		$lr_return_rec = array();
		
		if($p_token == NULL || $p_token == ''){
			// tokenを取得できなければfalseを返す
			return false;
		}
		
		// 引数のトークをキーにしてセッションテーブルからデータを取得
		require_once('../mdl/m_sessions.php');
		$l_msess = new m_sessions();
		$lr_session = $l_msess->getSessionRecByToken($p_token);
		
		if(count($lr_session) == 0){
			// 取得できなければfalseを返す
			return false;
		}
		
		// 取得したセッションを単一レコードに変換
		foreach($lr_session as $lr_rec){
			$lr_return_rec = $lr_rec;
		}
		
		// セッション制御クラスを利用してトークンを作成
		require_once('../lib/sessionControl.php');
		$lc_scont = new sessionControl();
		//$l_new_token = $lc_scont -> sessionStart();
		$l_new_token = $lc_scont -> createToken();
		
		// セッションIDをキーにトークンを更新
		$l_msess->updateToken($lr_return_rec['SESSION_ID'], $l_new_token, $lr_return_rec['USER_ID']);
		
		// トークンを更新
		$p_token = $l_new_token;
		
		// 取得したセッション情報を返す
		return $lr_return_rec;
	}
/*----------------------------------------------------------------------------
  不正アクセス画面表示
  引数:
					$p_terminal		キャリア
					$p_model		モデル
  ----------------------------------------------------------------------------*/
	function showUnauthorizedAccessPage($p_terminal, $p_model){
		
		$guid	=	NULL;
		
		// セッションの初期化
		session_start();
		
		// セッション情報(cookie)を削除
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 900, '/');
		}
		
		// セッションの破棄
		session_destroy();
		
		// Smartyクラス
		require_once('../Smarty/libs/Smarty.class.php');
		$lc_smarty = new Smarty();
		
		// smarty設定
		$lc_smarty->template_dir	= DIR_TEMPLATES;
		$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
		$lc_smarty->config_dir		= DIR_CONFIGS;
		$lc_smarty->cache_dir		= DIR_CACHE;
		
		// ヘッダー部
		$lr_spdesc = $this->getSpecificDescription($p_terminal, $p_model);
		
		$lc_smarty->assign("doctype",	$lr_spdesc['declaration']);
		$lc_smarty->assign("char_code",	$lr_spdesc['character_code']);
		$lc_smarty->assign("xmlns",		$lr_spdesc['xmlns']);
		$lc_smarty->assign("terminal",	$p_terminal);
		$lc_smarty->assign("model",		$p_model);
		
		// ロゴ
		$lc_smarty->assign("img_logo",	MOBILE_LOGO);
		
		if($p_terminal == TERMINAL_DOCOMO){$guid = "?guid=ON";}
		
		$lc_smarty->assign("headtitle",	"Unlawful computer access");
		$lc_smarty->assign("message1",	"不正なアクセスを検出しました。");
		$lc_smarty->assign("message2",	"ログイン画面から再ログインして下さい。<br>\n<br>\n<hr>\n"."<A HREF='../index.php".$guid."' accesskey='0'>ログイン画面へ戻る</A>\n");
		
		// コピーライト
		$lc_smarty->assign("txt_copyright", $this->getCopyRight());
		
		// 画面表示
		$lc_smarty->display('UnjustMobileAccess.tpl');
	}
	
/*----------------------------------------------------------------------------
  権限なし画面表示
  引数:
					$p_terminal		キャリア
					$p_model		モデル
  ----------------------------------------------------------------------------*/
	function showAuthorityAccessPage($p_terminal, $p_model){
		
		$guid	=	NULL;
		
		// セッションの初期化
		session_start();
		
		// メール設定読込
		$lc_mails = new MailSettings($_SESSION["_authsession"]["data"]["DATA_ID"]);
		
		// セッション情報(cookie)を削除
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 900, '/');
		}
		
		// セッションの破棄
		session_destroy();
		
		// Smartyクラス
		require_once('../Smarty/libs/Smarty.class.php');
		$lc_smarty = new Smarty();
		
		// smarty設定
		$lc_smarty->template_dir	= DIR_TEMPLATES;
		$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
		$lc_smarty->config_dir		= DIR_CONFIGS;
		$lc_smarty->cache_dir		= DIR_CACHE;
		
		// ヘッダー部
		$lr_spdesc = $this->getSpecificDescription($p_terminal, $p_model);
		
		$lc_smarty->assign("doctype",	$lr_spdesc['declaration']);
		$lc_smarty->assign("char_code",	$lr_spdesc['character_code']);
		$lc_smarty->assign("xmlns",		$lr_spdesc['xmlns']);
		$lc_smarty->assign("terminal",	$p_terminal);
		$lc_smarty->assign("model",		$p_model);
		
		// ロゴ
		$lc_smarty->assign("img_logo",	MOBILE_LOGO);
		
		if($p_terminal == TERMINAL_DOCOMO){$guid = "?guid=ON";}
		
		$lc_smarty->assign("headtitle",	"No Authority");
		$lc_smarty->assign("message1",	"ページを表示する権限がありません。");
		$lc_smarty->assign("message2",	"作業を行う場合は、<a href='mailto:".$lc_mails->getMailAddr1()."'>管理者</a>までお問い合わせください。<br>\n<br>\n<hr>\n"."<A HREF='../index.php".$guid."' accesskey='0'>ログイン画面へ戻る</A>\n");
		
		// コピーライト
		$lc_smarty->assign("txt_copyright", $this->getCopyRight());
		
		// 画面表示
		$lc_smarty->display('UnjustMobileAccess.tpl');
	}
	
/*----------------------------------------------------------------------------
  コピーライト
  引数:
  ----------------------------------------------------------------------------*/
	function getCopyRight(){
		return "<font size=\"1\">Copyright &copy; 2005-".date(Y)." <font color=\"#B40303\"><b>ZENK</b></font> Co., Ltd.</font>";
	}
}
?>