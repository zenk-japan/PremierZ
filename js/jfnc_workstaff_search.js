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
 作業管理画面メニュー用javascript関数
*******************************************************************************/
var $this_page_file			= "workstaff.php";						// 当画面のPHPファイル

/*==============================================================================
  検索処理
  処理概要：検索項目を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function procSearchByCond(){
	// 入力された条件値を取得する
	var $l_work_company_id					= $("#id_sel_work_company_name").val();
	var $l_work_group_id					= $("#id_sel_work_group_name").val();
	var $l_work_classification_division		= $("#id_sel_work_classification_division_name").val();
	var $l_work_user_id						= $("#id_sel_work_user_name").val();
	
	// 取得した値をhidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_company_id']").val($l_work_company_id);
	$obj_hidden_form.find("input[name='nm_work_group_id']").val($l_work_group_id);
	$obj_hidden_form.find("input[name='nm_work_classification_division']").val($l_work_classification_division);
	$obj_hidden_form.find("input[name='nm_work_user_id']").val($l_work_user_id);
	
	if($("#id_ckb_workstaff_onlyinvalid").attr("checked")){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form.find("input[name='nm_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form.find("input[name='nm_valid_checkstat']").val('N');
	}
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	
	/*-----------------------------
		コンボボックス変更時処理
	  -----------------------------*/
	$("#id_sel_work_company_name").bind("change", function(){
		procSearchByCond();
	});
	$("#id_sel_work_group_name").bind("change", function(){
		procSearchByCond();
	});
	$("#id_sel_work_classification_division_name").bind("change", function(){
		procSearchByCond();
	});
	$("#id_sel_work_user_name").bind("change", function(){
		procSearchByCond();
	});
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// クリア
	$("#id_btn_search_clear").bind("click", function(){
		$("#id_sel_work_company_name").val('');
		$("#id_sel_work_group_name").val('');
		$("#id_sel_work_classification_division_name").val('');
		$("#id_sel_work_user_name").val('');
		$("#id_ckb_workstaff_onlyinvalid").attr('checked','true');
		// 画面再読み込み
		procSearchByCond();
	});
	
	/*---------------------------------------
		有効のみ表示クリック時処理
	  ---------------------------------------*/
	$("#id_ckb_workstaff_onlyinvalid").bind("click", function(){
		procSearchByCond();
	});
	
});