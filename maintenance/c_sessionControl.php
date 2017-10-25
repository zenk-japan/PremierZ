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

// *****************************************************************************
// ファイル名：c_sessionControl.php
// 処理概要  ：セッション制御
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');
require_once('../lib/CommonFunctions.php');
class sessionControl {
	public  $l_dir_prfx;
	private $l_token_item_name				= 'SESSION_TOKEN';
	
/*============================================================================
	コンストラクタ
	引数:
  ============================================================================*/
	function __construct(){
		$this->l_dir_prfx		= "../";		// 当画面のDIR階層を補完するためのDIRプレフィックス
	}

/*============================================================================
	セッション変数追加
		処理概要:	セッション変数を追加する
		引数:		
  ============================================================================*/
	function setSesseionItem($p_item_name, $p_item_value){
		// セッションを開始
		session_start();
		
		// セッション変数を追加
		$_SESSION[$p_item_name] = $p_item_value;
		
		// 保存してセッションを終了
		session_write_close();
		
		return;
	}

/*============================================================================
	セッション変数取得
		処理概要:	セッション変数を取得する
		引数:		
  ============================================================================*/
	function getSesseionItem($p_item_name){
		// セッションを開始
		session_start();
		
		// セッション変数取得
		$l_return_val = $_SESSION[$p_item_name];
		
		// 保存してセッションを終了
		session_write_close();
		
		return $l_return_val;
	}

/*============================================================================
	トークンを格納
		処理概要:	トークンを格納する
		引数:		
  ============================================================================*/
	function setToken(){
		// セッションを開始
		session_start();
		
		// 32桁のトークンを生成
		$l_token = getPassword(32,PASS_ALNUMSIG);
		
		// セッションにトークンをセット
		$_SESSION[$this->l_token_item_name] = $l_token;
		
		// 保存してセッションを終了
		session_write_close();
		
		return $l_token;
	}

/*============================================================================
	トークン取得
		処理概要:	セッションを開始し、トークンを取得する
		引数:		
  ============================================================================*/
	function getToken(){
		// セッションを開始
		session_start();
		
		// トークンを取得
		$l_token = $_SESSION[$this->l_token_item_name];
		
		// 保存してセッションを終了
		session_write_close();
		
		return $l_token;
	}

/*============================================================================
	トークン削除
		処理概要:	トークンを削除する
		引数:		
  ============================================================================*/
	function deleteToken(){
		// セッションを開始
		session_start();
		
		// トークンを削除
		$_SESSION[$this->l_token_item_name] = null;

		// 保存してセッションを終了
		session_write_close();
		
		return true;
	}

/*============================================================================
	セッションを破棄
		処理概要:	セッションを破棄する
		引数:		
  ============================================================================*/
	function destroySession(){
		// セッション変数を初期化
		$_SESSION = array();
		session_unset();
		
		// クッキーを無効化
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		
		// セッションを破棄
		session_destroy();
	}

}
?>
