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
 作業完了明細表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト

/*==============================================================================
  リストホバー処理
  ============================================================================*/
/*
function procListHover(){
	// オブジェクト
	$l_trgt_obj = $(".c_tr_list_menu");
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", '');
		}
	);
}
*/
/*==============================================================================
  補足/修正送信フォームボタンクリック時処理
  ============================================================================*/
function procClickOpenForm($list_td_workstaff_num){
	// 補足/修正送信フォーム画面に移動
	movePage($obj_hidden_form_list,"wrcompletionmailform.php");
}

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
	// 作業内容一覧へ戻る
	$("#id_btn_mainmenu_worklist").bind("click", function(){
		// TOPページに移動
		movePage($obj_hidden_form_list,"wrworkcontents.php");
		}
	);
	
	// 作業完了一覧へ戻る
	$("#id_btn_mainmenu_workcomplist").bind("click", function(){
		// TOPページに移動
		movePage($obj_hidden_form_list,"wrcompletionlist.php");
		}
	);
	
	// 補足/修正送信フォーム
	$("#id_btn_workcorrectform").bind("click", function(){
		// TOPページに移動
		movePage($obj_hidden_form_list,"wrcompletionmailform.php");
		}
	);
});


