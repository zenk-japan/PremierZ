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
 ユーザー新規登録画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $l_trgt_user_id			= "";										// 表示対象ユーザーID
var $page_users_edit		= "../../page/users_edit.php";				// ユーザー情報編集phpファイル
var $xls_import_page			= "../../page/xls_import.php";			// xlsからの一括登録用phpファイル
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_hidden_form = $("#id_form_hidden");
	
	//==============================================
	// 新規登録ボタン
	//==============================================
	$("#id_btn_insert").bind("click", function(){
		//ユーザー新規作成画面表示
		$users_edit_pages = $page_users_edit + "?cid=0&gid=0&uid=new";
		GB_showCenter('ユーザー管理 - 新規作成', $users_edit_pages, 480, 650);
	});
	$("#id_btn_insert2").bind("click", function(){
		// 新規登録画面表示
		GB_showCenter('エクセルデータ一括登録', $xls_import_page + "?cid=new", 240, 650);
	//	movePage($obj_hidden_form, "companies_edit.php?cid=new");
	});
});
