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
 会社詳細画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $l_trgt_keyvalue_id			= "";									// 表示対象会社ID
var $company_edit_page			= "../../page/companies_edit.php";		// 会社管理編集phpファイル

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
		// 会社ID取得
		$l_trgt_keyvalue_id = $obj_hidden_form.find('input[name="nm_selected_company_id"]').val();
		
		// トークンを取得
		$l_token_id = $obj_hidden_form_list.find('input[name="nm_token_code"]').val();
		// 会社名（検索用）を取得
		$l_company_name = $obj_hidden_form_list.find('input[name="nm_comp_name_cond"]').val();
		// 表示中のページ数を取得
		$l_show_page = $obj_hidden_form_list.find('input[name="nm_show_page"]').val();
		// 最大ページ数を取得
		$l_max_page = $obj_hidden_form_list.find('input[name="nm_max_page"]').val();
		// 現在表示中のタブ名を取得
		$l_selected_tab = $obj_hidden_form_list.find('input[name="nm_selected_tb"]').val();
		// 表示中のグループタブのページ数を取得
		$l_group_show_page = $obj_hidden_form_list.find('input[name="nm_group_show_page"]').val();
		// 表示中のグループタブの最大ページ数を取得
		$l_group_max_page = $obj_hidden_form_list.find('input[name="nm_group_max_page"]').val();
		// 表示中のユーザータブのページ数を取得
		$l_user_show_page = $obj_hidden_form_list.find('input[name="nm_user_show_page"]').val();
		// 表示中のユーザータブの最大ページ数を取得
		$l_user_max_page = $obj_hidden_form_list.find('input[name="nm_user_max_page"]').val();
		// 表示中の作業拠点タブのページ数を取得
		$l_workbase_show_page = $obj_hidden_form_list.find('input[name="nm_workbase_show_page"]').val();
		// 表示中の作業拠点タブの最大ページ数を取得
		$l_workbase_max_page = $obj_hidden_form_list.find('input[name="nm_workbase_max_page"]').val();
		// 有効チェックを取得
		$l_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_valid_checkstat"]').val();
		// グループタブの有効チェックを取得
		$l_group_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_group_valid_checkstat"]').val();
		// ユーザータブの有効チェックを取得
		$l_user_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_user_valid_checkstat"]').val();
		// 作業拠点タブの有効チェックを取得
		$l_workbase_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_workbase_valid_checkstat"]').val();
		
		// 編集画面表示
		GB_showCenter('会社管理 - 編集', $company_edit_page + "?cid=" + $l_trgt_keyvalue_id + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&tab=" + $l_selected_tab + "&uspage=" + $l_user_show_page + "&umpage=" + $l_user_max_page + "&gspage=" + $l_group_show_page + "&gmpage=" + $l_group_max_page + "&bspage=" + $l_workbase_show_page + "&bmpage=" + $l_workbase_max_page + "&vgcheck=" + $l_group_valid_checkstat + "&vucheck=" + $l_user_valid_checkstat + "&vwcheck=" + $l_workbase_valid_checkstat, 240, 650);
	//	movePage($obj_hidden_form, "companies_edit.php?cid=" + $l_trgt_keyvalue_id);
	});
	
});
