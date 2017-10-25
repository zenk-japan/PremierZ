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
	ファイル名：CommonFunctions.php
	処理概要：共通で使用する関数の定義
  ============================================================================*/

/*----------------------------------------------------------------------------
	画面遷移関数起動スクリプト作成
	処理概要：
				引数で指定した画面に遷移するjavascript関数を呼び出すコードを返す
	引数：
				$p_page_name		遷移先の画面のphpファイル名
  ----------------------------------------------------------------------------*/
	function jsMovePage($p_page_name){
		$l_retval = "";
		
		// コードのセット
		//$l_retval .= "javascript:action='" . $p_page_name . "';target=this.window;submit();";
		$l_retval .= "movePage(this,'" . $p_page_name . "')";
		
		return $l_retval;
	}

/*----------------------------------------------------------------------------
	基底URL作成
	処理概要：
				基底URLを返す
  ----------------------------------------------------------------------------*/
	function getBaseURL(){
		$l_retval = "";
		
		$l_retval = BASE_URL;
		/*
		$l_base_dir = "";
		
		$lr_split_uri = explode("/", $_SERVER['REQUEST_URI']);
		if (count($lr_split_uri) > 2){
			// REQUEST_URIには公開ディレクトリをルートとしたファイル名が記載される
			// 最後がファイル名で、その1つ前は固定のディレクトリ名となる為、
			// 最後から3つめまでが規定ディレクトリとなる
			for($l_cnt = 0; $l_cnt < count($lr_split_uri) - 2; $l_cnt++){
				$l_base_dir .= $lr_split_uri[$l_cnt]."/";
			}
		}else{
			// 公開ディレクトリにサブディレクトリを作成せずにセットアップされている場合
			$l_base_dir = "/";
		}
		
		$l_retval .= URI_SCHEME."://";
		$l_retval .= $_SERVER['HTTP_HOST'];
		$l_retval .= $l_base_dir;
		*/
		return $l_retval;
	}
/*----------------------------------------------------------------------------
	PCサイトURL作成
	処理概要：
				PCサイトのURLを返す
  ----------------------------------------------------------------------------*/
	function getPCURL(){
		$l_retval = "";
		
		$l_retval .= getBaseURL();
		$l_retval .= "page/entrance.php";
		
		return $l_retval;
	}

/*----------------------------------------------------------------------------
	MobileサイトURL作成
	処理概要：
				MobileサイトのURLを返す
  ----------------------------------------------------------------------------*/
	function getMobileURL(){
		$l_retval = "";
		
		$l_retval .= getBaseURL();
		$l_retval .= "mobile/login.php";
		
		return $l_retval;
	}

/*----------------------------------------------------------------------------
	ファイル名・ユニークID・パスワード生成
	引数:
				$p_length				パスワード指定文字数
				$p_mode					使用する文字列（大小英字 + 数字 + 記号）
  ----------------------------------------------------------------------------*/
	function getPassword($p_length,$p_mode){
		if ($p_length < 1 || $p_length > 256) {
			 return false;
		}
		$smallAlphabet	= 'abcdefghijklmnopqrstuvwxyz';
		$largeAlphabet	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numeric		= '0123456789';
		$siglum			= '!#$%&()*/+-_,.';
		
		switch ($p_mode) {
			
			// 小文字英字
			case PASS_SMALL:
				 $chars = $smallAlphabet;
				 break;
			// 大文字英字
			case PASS_LARGE:
				 $chars = $largeAlphabet;
				 break;
			// 小文字英数字
			case PASS_SMALLALNUM:
				 $chars = $smallAlphabet . $numeric;
				 break;
			// 大文字英数字
			case PASS_LARGEALNUM:
				 $chars = $largeAlphabet . $numeric;
				 break;
			// 数字
			case PASS_NUM:
				 $chars = $numeric;
				 break;
			// 記号
			case PASS_SIG:
				 $chars = $siglum;
				 break;
			// 大小文字英字
			case PASS_ALPHABET:
				 $chars = $smallAlphabet . $largeAlphabet;
				 break;
			// 大小文字英数字
			case PASS_ALNUM:
				 $chars = $smallAlphabet . $largeAlphabet . $numeric;
				 break;
			// 大小文字英字記号
			case PASS_ALSIG:
				 $chars = $smallAlphabet . $largeAlphabet . $siglum;
				 break;
			// 大小文字英数字記号
			case PASS_ALNUMSIG:
				 $chars = $smallAlphabet . $largeAlphabet . $numeric . $siglum;
				 break;
		}
		
		$charsLength = strlen($chars);
		
		$password = '';
		for ($i = 0; $i < $p_length; $i++) {
			$num = mt_rand(0, $charsLength - 1);
			$password .= $chars{$num};
		}
		
		return $password;
	}
?>