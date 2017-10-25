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
/*******************************************************************************
 全画面共通javascript関数
*******************************************************************************/

/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
function movePage($p_object, $p_move_to){
	// ページ読み込み
	$p_object.attr("target", window.name);
	$p_object.attr("method", "POST");
	$p_object.attr("action", $p_move_to);
	
	//alert($p_move_to);
	$p_object.submit();
	
}

/*==============================================================================
  文字列の前後空白削除
  引数：
		$p_string			文字列
  ============================================================================*/
function removeSpace($p_string){
	if($p_string){
		$l_return_val = $p_string.replace(/^\s+|\s+$/g, "");
		return $l_return_val;
	}else{
		return $p_string;
	}
}
/*==============================================================================
  文字列が数値で構成されているかチェック
  引数：
		$p_string			文字列
  ============================================================================*/
function IsNumeric($p_string) {
	var $l_str		= $p_string.toString();
	var $l_regex	= /[^0-9]/;
	if ($l_str.match($l_regex)){
		return false;
	}else{
		return true;
	}
}
/*==============================================================================
  文字列が英数字で構成されているかチェック
  引数：
		$p_string			文字列
  ============================================================================*/
function IsAlphNum($p_string) {
	var $l_str		= $p_string.toString();
	var $l_regex	= /[^0-9a-zA-Z]/;
	if ($l_str.match($l_regex)){
		return false;
	}else{
		return true;
	}
}

/*==============================================================================
  文字列から特殊文字削除
  引数：
		$p_string			文字列
  ============================================================================*/
function removeSpChar($p_string){
	if($p_string){
		$l_return_val = $p_string.replace(/[\"\'\;\/]+/g, "");
		return $l_return_val;
	}else{
		return $p_string;
	}
}

/*==============================================================================
  全画面共通画面起動時処理
  ============================================================================*/
jQuery(function($){


});
