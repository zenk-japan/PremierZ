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
// *****************************************************************************
// ファイル名：sessionControl.php
// 処理概要  ：セッション制御
// *****************************************************************************
	
	class sessionControl {
		private $token_item_name				= 'SESSION_TOKEN';
		private	$debug_mode						= 0;
	// =============================================================================
	// セッション開始
	//	処理概要:	セッションを開始し、トークンを格納する
	//	引数:		
	// =============================================================================
		function sessionStart(){
			// セッションを開始
			session_start();
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッションを開始"."</br>";}
			// n桁のトークンを生成
		//	$l_token = getPassword(SESSION_NUMTOKEN,PASS_ALNUMSIG);
			$l_token = getPassword(SESSION_NUMTOKEN,PASS_ALNUM);
			
			// セッションにトークンをセット
			$_SESSION[$this->token_item_name] = $l_token;
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッションにトークンをセット"."</br>";}
			
			// 保存してセッションを終了
			session_write_close();
			
			return $l_token;
		}
	// =============================================================================
	// トークンを生成
	//	処理概要:	トークンを生成
	//	引数:		
	// =============================================================================
		function createToken(){
			// n桁のトークンを生成
		//	$l_token = getPassword(SESSION_NUMTOKEN,PASS_ALNUMSIG);
			$l_token = getPassword(SESSION_NUMTOKEN,PASS_ALNUM);
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."n桁のトークンを生成"."</br>";}
			
			return $l_token;
		}
	// =============================================================================
	// セッション変数追加
	//	処理概要:	セッション変数を追加する
	//	引数:		
	// =============================================================================
		function setSesseionItem($p_item_name, $p_item_value){
			// セッションを開始
			session_start();
			
			// セッション変数を追加
			$_SESSION['_authsession']['data'][$p_item_name] = $p_item_value;
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッション変数を追加"."</br>";}
			
			// 保存してセッションを終了
			session_write_close();
			
			return;
		}
	// =============================================================================
	// セッション変数取得
	//	処理概要:	セッション変数を取得する
	//	引数:		
	// =============================================================================
		function getSesseionItem($p_item_name){
			// セッションを開始
			session_start();
			
			//print_r($_SESSION);
			
			// セッション変数取得
			$l_return_val = $_SESSION['_authsession']['data'][$p_item_name];
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッション変数取得"."</br>";}
			
			// 保存してセッションを終了
			session_write_close();
			
			return $l_return_val;
		}
	// =============================================================================
	// トークンをセット
	//	処理概要:	セッションを開始し、トークンをセットする
	//	引数:		
	// =============================================================================
		function setToken(){
			// セッションを開始
			session_start();
			
			// トークンを取得
			$l_token = $this->createToken();
			
			// セッション変数にトークンを設定
			$_SESSION[$this->token_item_name] = $l_token;
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッション変数にトークンを設定"."</br>";}
			
			// 保存してセッションを終了
			session_write_close();
			
			return $l_token;
		}
		
	// =============================================================================
	// トークン取得
	//	処理概要:	セッションを開始し、トークン取得する
	//	引数:		
	// =============================================================================
		function getToken(){
			// セッションを開始
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッションを開始"."</br>";}
			session_start();
			
			// トークンを取得
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."トークンを取得"."</br>";}
			$l_token = $_SESSION[$this->token_item_name];
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."トークン -> ".$l_token."</br>";}
			
			// 保存してセッションを終了
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."保存してセッションを終了"."</br>";}
			session_write_close();
			
			return $l_token;
		}
		
	// =============================================================================
	// セッションを破棄
	//	処理概要:	セッションを破棄する
	//	引数:		
	// =============================================================================
		function destroySession(){
			// セッションを開始
			session_start();
			
			// セッション変数を初期化
			$_SESSION = array();
			session_unset();
			
			// クッキーを無効化
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			
			// セッションを破棄
			session_destroy();
			if($this->debug_mode == 1){print $_SERVER["PHP_SELF"]." -> ".__FUNCTION__." -> "."セッションを破棄"."</br>";}
			
			// 保存してセッションを終了
			session_write_close();
		}
	}
?>
