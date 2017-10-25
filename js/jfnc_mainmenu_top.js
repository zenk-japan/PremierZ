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
 メニュー画面トップメニュー用javascript関数
*******************************************************************************/
var $obj_hidden_form_top;									// 隠し項目のオブジェクト
var $logout_php_file		= "exit.php";					// ログアウト

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	$obj_hidden_form_top = $("#id_form_hidden");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// マニュアル
	$("#id_btn_topline_manual").bind("click", function(){
			alert("Practice makes perfect.\n習うより慣れろ。");
		}
	);
	// ログアウト
	$("#id_btn_topline_logout").bind("click", function(){
			movePage($obj_hidden_form_top, $logout_php_file);
		}
	);
});