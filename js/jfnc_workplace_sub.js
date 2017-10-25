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
 作業拠点新規登録画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $l_trgt_workplace_id	= "";										// 表示対象作業拠点ID
var $page_workbase_edit	= "../../page/workplace_edit.php";			// 作業拠点情報編集phpファイル
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
		//作業拠点新規作成画面表示
		$workbase_edit_pages = $page_workbase_edit + "?cid=0&bid=new"
		GB_showCenter('作業拠点管理 - 新規作成', $workbase_edit_pages, 310, 650);
	});
	$("#id_btn_insert2").bind("click", function(){
		// 新規登録画面表示
		GB_showCenter('エクセルデータ一括登録', $xls_import_page + "?cid=new", 240, 650);
	//	movePage($obj_hidden_form, "companies_edit.php?cid=new");
	});
});
