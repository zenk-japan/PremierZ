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
 作業拠点詳細画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $l_trgt_keyvalue_id	= "";											// 表示対象作業拠点ID
var $page_workbase_edit	= "../../page/workplace_edit.php";				// 作業拠点管理編集phpファイル

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_hidden_form = $("#id_form_hidden");
	
	//==============================================
	// 編集ボタン
	//==============================================
	$("#id_btn_detail_editbtn").bind("click", function(){
		// 作業拠点ID取得
		$l_trgt_keyvalue_id = $obj_hidden_form.find('input[name="nm_selected_workplace_id"]').val();
		
		// トークンを取得
		$l_token_id = $obj_hidden_form.find('input[name="nm_token_code"]').val();
		// 会社名（検索用）を取得
		$l_company_name = $obj_hidden_form.find('input[name="nm_comp_name_cond"]').val();
		// 作業拠点名（検索用）を取得
		$l_workbase_name = $obj_hidden_form.find('input[name="nm_workplace_name_cond"]').val();
		// 表示中のページ数を取得
		$l_show_page = $obj_hidden_form.find('input[name="nm_show_page"]').val();
		// 最大ページ数を取得
		$l_max_page = $obj_hidden_form.find('input[name="nm_max_page"]').val();
		// 有効チェックの有無を取得
		$l_valid_checkstat = $obj_hidden_form.find('input[name="nm_valid_checkstat"]').val();
		// 編集画面を開いているときの親ウインドウのページ名をセット
		$l_parent_pagename = "workbase";
		
		// 編集画面表示
		GB_showCenter('作業拠点管理 - 編集', $page_workbase_edit + "?bid=" + $l_trgt_keyvalue_id + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&bname=" + $l_workbase_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename, 310, 650);
	});
	
});
