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
 ユーザー詳細画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $l_token_id					= "";									// トークン
var $l_company_name				= "";									// 会社名（検索用）
var $l_group_name				= "";									// グループ名（検索用）
var $l_user_name				= "";									// ユーザー名（検索用）
var $l_show_page				= "";									// 表示中のページ数
var $l_max_page					= "";									// 最大ページ数
var $l_valid_checkstat			= "";									// 有効チェック
var $l_trgt_keyvalue_id			= "";									// 表示対象ユーザーID
var $l_parent_pagename			= "";									// 編集画面を開いたときの親ウインドウのページ名
var $users_edit_page			= "../../page/users_edit.php";			// ユーザー管理編集phpファイル
var $users_mail_page			= "../../page/users_mail.php";			// ユーザー管理メール送信phpファイル

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_hidden_form = $("#id_form_hidden");
	
	// ユーザーID取得
	$l_trgt_keyvalue_id = $obj_hidden_form.find('input[name="nm_selected_user_id"]').val();

	//==============================================
	// 編集ボタン
	//==============================================
	$("#id_btn_detail_editbtn").bind("click", function(){
		// トークンを取得
		$l_token_id = $obj_hidden_form.find('input[name="nm_token_code"]').val();
		// 会社名（検索用）を取得
		$l_company_name = $obj_hidden_form.find('input[name="nm_comp_name_cond"]').val();
		// グループ名（検索用）を取得
		$l_group_name = $obj_hidden_form.find('input[name="nm_group_name_cond"]').val();
		// ユーザー名（検索用）を取得
		$l_user_name = $obj_hidden_form.find('input[name="nm_user_name_cond"]').val();
		// 表示中のページ数を取得
		$l_show_page = $obj_hidden_form.find('input[name="nm_show_page"]').val();
		// 最大ページ数を取得
		$l_max_page = $obj_hidden_form.find('input[name="nm_max_page"]').val();
		// 有効チェックの有無を取得
		$l_valid_checkstat = $obj_hidden_form.find('input[name="nm_valid_checkstat"]').val();
		// 編集画面を開いているときの親ウインドウのページ名をセット
		$l_parent_pagename = "user";

		
		// 編集画面表示
		GB_showCenter('ユーザー管理 - 編集', $users_edit_page + "?token=" + $l_token_id + "&cname=" + $l_company_name + "&gname=" + $l_group_name + "&uname=" + $l_user_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&uid=" + $l_trgt_keyvalue_id + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename, 480, 650);
		//movePage($obj_hidden_form, "users_edit.php?uid=" + $l_trgt_keyvalue_id);
	});
	
	//==============================================
	// Mail通知
	//==============================================
	$("#id_btn_mail_userinfo").bind("click", function(){
		// 編集画面表示
		GB_showCenter('ユーザー管理 - メール送信', $users_mail_page + "?uid=" + $l_trgt_keyvalue_id + "&nm_token_code=" + $obj_hidden_form.find('input[name="nm_token_code"]').val(), 480, 650);
	});
});
