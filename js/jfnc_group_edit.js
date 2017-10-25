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
 グループ編集画面表示用javascript関数
*******************************************************************************/
var $obj_main_form;											// POST対象オブジェクト
var $this_page_file		= "";
var $edit_page			= "../ctl/c_editGroups.php";		// グループ更新
var $lr_param			= {};								// 連想配列の初期化

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 作成
	$("#id_btn_create").bind("click", function(){
		$parent_pagename = $obj_main_form.find('input[name="nm_parent_pagename"]').val();
		postPage($obj_main_form, $edit_page);
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		$parent_pagename = $obj_main_form.find('input[name="nm_parent_pagename"]').val();
		postPage($obj_main_form, $edit_page);
	});
	
	// 会社名値クリア
	$("#id_txt_clear_company_name").bind("click", function(){
		$("#id_txt_edit_company_name").val('');
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
});

/*==============================================================================
  ページPOST
  処理概要：
  		コールバック関数
  引数：
		$p_data				
  ============================================================================*/
function callBackFnc($p_data){
	
	if($p_data == "insert nomal"){
		// 正常終了
		alert("グループ管理情報を登録しました。");
		// 親ウインドウが"会社管理画面"の場合、親ウインドウを更新する。
		if($parent_pagename == "company"){
			$this_page_file = "companies_mnt.php";
			parent.parent.movePage($obj_main_form, $this_page_file);
		}
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	//	// ページを更新
	//	movePage($obj_hidden_form_list, $this_page_file);
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("グループ管理情報を更新しました。");
		// 親ウインドウを判別しファイル名をセット
		if($parent_pagename == "group"){
			$this_page_file = "groups_mnt.php";
		}else if($parent_pagename == "company"){
			$this_page_file = "companies_mnt.php";
		}
		// ページを更新
		parent.parent.movePage($obj_main_form, $this_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}
