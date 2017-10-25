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
class MobileMenuSetup{
// *****************************************************************************
// クラス名：MobileMenuSetup
// 処理概要：メニュー項目のHTML構築
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	private $chkbox_on		= "on";				// チェックボックスチェック時の値
	
	public $csscl_button;						// ボタンのCSSクラス
	public $csscl_chkbox;						// チェックボックスのCSSクラス
	public $csscl_txtbox;						// テキストボックスのCSSクラス
	
	private $ins_bt_name	= "bt_insert";		// 新規ボタンの項目名
	
// =============================================================================
// メニュー定義
// 概要：メニュー用の配列の要素数分メニューHTML定義処理を起動する
// =============================================================================
	function setMenu($p_ar_menuset){
		$l_retval = "";
		
		$l_menu_count = count($p_ar_menuset);	// メニューの数
		for ($i=1; $i <= $l_menu_count; $i++){
			$l_retval .= $this->getMenuHtml($p_ar_menuset[$i]);
		}
		
		return $l_retval;
	}
// =============================================================================
// メニューHTML定義
// 概要：メニューの設定を元にHTMLを構築して戻す
// 引数：
//       $p_ar_menu		メニュー設定配列
//                      value:	HTMLのvalueにセットする値
//                      name:	HTMLのnameにセットする値
//                      type:	HTMLのtypeにセットする値
//                      action:	HTMLのonClickで実行するスクリプト
//                      action:	HTMLのonkeyUpで実行するスクリプト
//
// =============================================================================
	function getMenuHtml($p_ar_menu){
		$htmlstrings = "";
		
		switch ($p_ar_menu[FORM_PARAM_TYPE]){
		// 普通のボタン
			case INPUT_TYPE_BUTTON:
				if($p_ar_menu[FORM_PARAM_NAME]==$this->ins_bt_name){
					// 新規ボタン
					$htmlstrings  = "\n\t<INPUT ";
					$htmlstrings .= "name=\"".$p_ar_menu[FORM_PARAM_NAME]."\" ";			// name
					$htmlstrings .= "type=\"".$p_ar_menu[FORM_PARAM_TYPE]."\" ";			// type
					$htmlstrings .= "value=\"".$p_ar_menu[FORM_PARAM_VALUE]."\" ";			// value
					$htmlstrings .= "class=\"".$this->csscl_button."\" ";					// class
					$htmlstrings .= ">";
				}else{
					// 新規ボタン以外
					$htmlstrings  = "\n\t<INPUT ";
					$htmlstrings .= "name=\"".$p_ar_menu[FORM_PARAM_NAME]."\" ";			// name
					$htmlstrings .= "type=\"".$p_ar_menu[FORM_PARAM_TYPE]."\" ";			// type
					$htmlstrings .= "value=\"".$p_ar_menu[FORM_PARAM_VALUE]."\" ";			// value
					$htmlstrings .= "class=\"".$this->csscl_button."\" ";					// class
					$htmlstrings .= ">";
				}
			break;
		// 送信ボタン
			case INPUT_TYPE_SUBMIT:
				$htmlstrings  = "\n\t<INPUT ";
				$htmlstrings .= "name=\"".$p_ar_menu[FORM_PARAM_NAME]."\" ";				// name
				$htmlstrings .= "type=\"".$p_ar_menu[FORM_PARAM_TYPE]."\" ";				// type
				$htmlstrings .= "value=\"".$p_ar_menu[FORM_PARAM_VALUE]."\" ";				// value
				$htmlstrings .= "><BR>";
			break;
		// チェックボックス
			case INPUT_TYPE_CHKBOX:
				$htmlstrings  = "\n\t<INPUT ";
				$htmlstrings .= "name=\"".$p_ar_menu[FORM_PARAM_NAME]."\" ";				// name
				$htmlstrings .= "type=\"".$p_ar_menu[FORM_PARAM_TYPE]."\" ";				// type
				$htmlstrings .= "onClick=\"".$p_ar_menu[FORM_PARAM_ACTION]."\" ";
				// チェック状態がPOSTされている場合はチェックを入れる
				if($_POST[HDITEM_DELETE_CHECK]==$this->chkbox_on){
					$htmlstrings .= "checked";
				}
				$htmlstrings .= ">";
				$htmlstrings .= "<LABEL for=\"".$p_ar_menu[FORM_PARAM_NAME]."\""." class=\"".$this->csscl_chkbox."\" >";	// label(class含む)
				$htmlstrings .= $p_ar_menu[FORM_PARAM_VALUE]."</LABEL>";					// プロンプト
			break;
		// テキストボックス
			case INPUT_TYPE_TEXT:
				$htmlstrings  = "\n\t<INPUT ";
				$htmlstrings .= "name=\"".$p_ar_menu[FORM_PARAM_NAME]."\" ";				// name
				$htmlstrings .= "type=\"".$p_ar_menu[FORM_PARAM_TYPE]."\" ";				// type
				$htmlstrings .= "value=\"".$p_ar_menu[FORM_PARAM_VALUE]."\" ";				// value
				$htmlstrings .= "class=\"".$this->csscl_txtbox."\" ";						// class
				$htmlstrings .= "onClick=\"".$p_ar_menu[FORM_PARAM_ACTION]."\" ";			// onClick
				$htmlstrings .= "onkeyUp=\"".$p_ar_menu[FORM_PARAM_ONKEYUP]."\" ";			// onkeyUp
				$htmlstrings .= ">";
			break;
		}
		return $htmlstrings;
	}
}