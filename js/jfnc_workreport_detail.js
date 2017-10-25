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
 作業報告詳細表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト


/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/

$(function(){
	// 隠し項目のFORM
	$obj_hidden_form_list = $("#id_form_hidden");
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 作業内容一覧
	$("#id_btn_mainmenu_worklist").bind("click", function(){
		// 作業内容一覧ページに移動
		movePage($obj_hidden_form_list,"wrworkcontents.php");
		}
	);
	// 作業完了一覧
	$("#id_btn_mainmenu_workcomplist").bind("click", function(){
		// 作業完了一覧画面に移動
		movePage($obj_hidden_form_list,"wrcompletionlist.php");
		}
	);
});


