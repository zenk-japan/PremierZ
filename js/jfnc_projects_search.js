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
 Project検索用javascript関数
*******************************************************************************/
var $this_page_file	= "projects.php";
//var $obj_hidden_form;										// 隠し項目のオブジェクト

/*==============================================================================
  検索処理
  処理概要：検索項目を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function procSearchByCond(){
	// 入力された条件値を取得する
	var $l_estimate_code				= $("#id_txt_cond_estimate_code").val();
	var $l_work_name					= $("#id_txt_cond_work_name").val();
	var $l_request_company_name			= $("#id_txt_cond_request_company_name").val();
	var $l_enduser_company_name			= $("#id_txt_cond_enduser_company_name").val();
	var $l_estimate_user_name			= $("#id_txt_cond_estimate_user_name").val();
	var $l_selected_order_devision		= $("#id_sel_cond_order_division").val();
	var $l_selected_work_devision		= $("#id_sel_cond_work_division").val();
	
	// 取得した値をhidden項目にセットする
	$obj_hidden_form.find("input[name='nm_estimate_code']").val($l_estimate_code);
	$obj_hidden_form.find("input[name='nm_work_name']").val($l_work_name);
	$obj_hidden_form.find("input[name='nm_request_company_name']").val($l_request_company_name);
	$obj_hidden_form.find("input[name='nm_enduser_company_name']").val($l_enduser_company_name);
	$obj_hidden_form.find("input[name='nm_estimate_user_name']").val($l_estimate_user_name);
	$obj_hidden_form.find("input[name='nm_order_division']").val($l_selected_order_devision);
	$obj_hidden_form.find("input[name='nm_work_division']").val($l_selected_work_devision);
	
	// ページを1に設定
	$obj_hidden_form.find("input[name='nm_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	//※project_common.jsで取得
	
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 検索
	$("#id_btn_prj_search").bind("click", function(){
		procSearchByCond();
	});
	// クリア
	$("#id_btn_prj_cond_clear").bind("click", function(){
		$("#id_txt_cond_estimate_code").val('');
		$("#id_txt_cond_work_name").val('');
		$("#id_txt_cond_request_company_name").val('');
		$("#id_txt_cond_enduser_company_name").val('');
		$("#id_txt_cond_estimate_user_name").val('');
		$("#id_sel_cond_order_division").val('');
		$("#id_sel_cond_work_division").val('');
	});
});