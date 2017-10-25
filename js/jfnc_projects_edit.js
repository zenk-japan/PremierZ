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
 プロジェクト編集画面表示用javascript関数
*******************************************************************************/
var $obj_main_form;											// POST対象オブジェクト
var $parent_page_file	= "projects.php";				// 親画面PHPファイル
var $edit_page			= "../ctl/c_editProjects.php";		// 更新用PHPファイル
var $lr_param			= {};								// 連想配列の初期化

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$.updnWatermark.attachAll();
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	/*-----------------------------
		カレンダー設定
	  -----------------------------*/
	// 見積依頼日
	procCalDate("#id_txt_edit_estimate_request_date");
	// 作業開始予定日
	procCalDate("#id_txt_edit_schedule_from_date");
	// 作業完了予定日
	procCalDate("#id_txt_edit_schedule_to_date");
	// 見積提出日1
	procCalDate("#id_txt_edit_submitting_date1");
	// 見積提出日2
	procCalDate("#id_txt_edit_submitting_date2");
	// 見積提出日3
	procCalDate("#id_txt_edit_submitting_date3");
	// 見積提出日4
	procCalDate("#id_txt_edit_submitting_date4");
	// 見積提出日5
	procCalDate("#id_txt_edit_submitting_date5");
	// 作業完了日
	procCalDate("#id_txt_edit_work_completion_date");
	// 帳簿入力日
	procCalDate("#id_txt_edit_book_input_date");
	// 請求書送付日
	procCalDate("#id_txt_edit_bill_sending_date");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 作成
	$("#id_btn_create").bind("click", function(){
		postPage($obj_main_form, $edit_page);
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		postPage($obj_main_form, $edit_page);
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
	
	/*-----------------------------
		リスト使用項目のDELキー設定
	  -----------------------------*/
	$(".c_table_td_search_textval").keyup(function(e){
		if(e.keyCode==46){
		// DELが押された場合は値消去
			$(this).val('');
		}
	})
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
		alert("プロジェクト情報を登録しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	//	// ページを更新
	//	movePage($obj_hidden_form_list, $parent_page_file);
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("プロジェクト情報を更新しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}
