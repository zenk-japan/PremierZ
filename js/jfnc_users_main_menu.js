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
 ユーザー画面メインメニュー用javascript関数
*******************************************************************************/
var $obj_hidden_form_main;									// 隠し項目のオブジェクト
var $company_php_file		= "companies_mnt.php";			// 会社管理
var $user_php_file			= "users_mnt.php";				// ユーザー管理
var $group_php_file			= "groups_mnt.php";				// グループ管理
var $workplace_php_file		= "workplace_mnt.php";			// 作業場所管理

/*==============================================================================
  リストホバー処理
  ============================================================================*/
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

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	$obj_hidden_form_main = $("#id_form_hidden");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 会社管理
	$("#id_btn_mainmenu_companies").bind("click", function(){
			// 会社名のhiddn項目をクリアする
			$obj_hidden_form_list.find("input[name='nm_comp_name_cond']").val('');
			// 表示中のページ数のhiddn項目を1にする
			$obj_hidden_form_list.find("input[name='nm_show_page']").val("1");
			movePage($obj_hidden_form_main, $company_php_file);
		}
	);
	
	// 作業場所管理
	$("#id_btn_mainmenu_bases").bind("click", function(){
			// 会社名のhiddn項目をクリアする
			$obj_hidden_form_list.find("input[name='nm_comp_name_cond']").val('');
			// 表示中のページ数のhiddn項目を1にする
			$obj_hidden_form_list.find("input[name='nm_show_page']").val("1");
			movePage($obj_hidden_form_main, $workplace_php_file);
		}
	);
	
	// グループ管理
	$("#id_btn_mainmenu_groups").bind("click", function(){
			// 会社名のhiddn項目をクリアする
			$obj_hidden_form_list.find("input[name='nm_comp_name_cond']").val('');
			// 表示中のページ数のhiddn項目を1にする
			$obj_hidden_form_list.find("input[name='nm_show_page']").val("1");
			movePage($obj_hidden_form_main, $group_php_file);
		}
	);
});