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
var $parent_page_file	= "tasks.php";						// 親画面PHPファイル
var $edit_page			= "../ctl/c_editTasks.php";			// 更新用PHPファイル
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
	// 作業日
	procCalDate("#id_txt_edit_work_date");
	// 作業コピー開始
	procCalDate("#id_txt_copy_from");
	// 作業コピー終了
	procCalDate("#id_txt_copy_to");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 作成
	$("#id_btn_create").bind("click", function(){
		preProcPost();
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		preProcPost();
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
	
	
	/*-----------------------------
		コピータイプ変更処理
	  -----------------------------*/
	$(".c_rd_copytype_single").bind("click", function(){
		if ($(this).val() == 'S'){
			// １日分の場合
			$(".c_txt_edit_option_edit, .c_chk_copy_day").attr("disabled","true");
			$("#id_txt_edit_work_date").removeAttr("disabled");
		}else{
			// 複数の場合
			$(".c_txt_edit_option_edit, .c_chk_copy_day").removeAttr("disabled");
			$("#id_txt_edit_work_date").attr("disabled","true");
		}
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
  POST前処理
  処理概要：
  		登録/更新設定をhidden項目にセットしてPOST処理を行う
  ============================================================================*/
function preProcPost(){
	var $l_html				= "";
	
	if ($(".c_rd_copytype_single:checked").val() == 'M'){
	// コピーの場合はチェックとhidden項目へのセットを行う
		// 範囲指定が無い場合はエラーとする
		if ($("#id_txt_copy_from").val() == '' || $("#id_txt_copy_to").val() == ''){
			alert("対象期間を設定して下さい。");
			return false;
		}
		
		// 曜日指定がすべて未チェックの場合はエラーとする
		var $l_cnt_day_checked = $(".c_chk_copy_day:checked").size();
		if ($l_cnt_day_checked == 0){
			alert("曜日指定は少なくとも１箇所にチェックを入れてください。");
			return false;
		}
		
		// hidden項目セット
		// すでにある場合は先に削除する
		if($obj_main_form.find('input[name="nm_copy_date_from"]')){$obj_main_form.find('input[name="nm_copy_date_from"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_date_to"]')){$obj_main_form.find('input[name="nm_copy_date_to"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_1"]')){$obj_main_form.find('input[name="nm_copy_day_1"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_2"]')){$obj_main_form.find('input[name="nm_copy_day_2"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_3"]')){$obj_main_form.find('input[name="nm_copy_day_3"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_4"]')){$obj_main_form.find('input[name="nm_copy_day_4"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_5"]')){$obj_main_form.find('input[name="nm_copy_day_5"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_6"]')){$obj_main_form.find('input[name="nm_copy_day_6"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_7"]')){$obj_main_form.find('input[name="nm_copy_day_7"]').remove();}
		
		// コピー範囲HTML作成
		var $l_copy_date_from	= $("#id_txt_copy_from").val();
		var $l_copy_date_to		= $("#id_txt_copy_to").val();
		
		$l_html = $l_html + '<input type="hidden" name="nm_copy_date_from" value="'+$l_copy_date_from+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_date_to" value="'+$l_copy_date_to+'"/>'
		
		// 曜日セットHTML作成
		var $l_copy_day_1	= $("#id_chk_copy_day1").attr("checked");
		var $l_copy_day_2	= $("#id_chk_copy_day2").attr("checked");
		var $l_copy_day_3	= $("#id_chk_copy_day3").attr("checked");
		var $l_copy_day_4	= $("#id_chk_copy_day4").attr("checked");
		var $l_copy_day_5	= $("#id_chk_copy_day5").attr("checked");
		var $l_copy_day_6	= $("#id_chk_copy_day6").attr("checked");
		var $l_copy_day_7	= $("#id_chk_copy_day7").attr("checked");
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_1" value="'+$l_copy_day_1+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_2" value="'+$l_copy_day_2+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_3" value="'+$l_copy_day_3+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_4" value="'+$l_copy_day_4+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_5" value="'+$l_copy_day_5+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_6" value="'+$l_copy_day_6+'"/>'
		$l_html = $l_html + '<input type="hidden" name="nm_copy_day_7" value="'+$l_copy_day_7+'"/>'
		
		// formに追加
		$obj_main_form.append($l_html);
	}else{
	// コピーしない場合
	// hidden項目がある場合削除する
		if($obj_main_form.find('input[name="nm_copy_date_from"]')){$('input[name="nm_copy_date_from"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_date_to"]')){$obj_main_form.find('input[name="nm_copy_date_to"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_1"]')){$obj_main_form.find('input[name="nm_copy_day_1"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_2"]')){$obj_main_form.find('input[name="nm_copy_day_2"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_3"]')){$obj_main_form.find('input[name="nm_copy_day_3"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_4"]')){$obj_main_form.find('input[name="nm_copy_day_4"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_5"]')){$obj_main_form.find('input[name="nm_copy_day_5"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_6"]')){$obj_main_form.find('input[name="nm_copy_day_6"]').remove();}
		if($obj_main_form.find('input[name="nm_copy_day_7"]')){$obj_main_form.find('input[name="nm_copy_day_7"]').remove();}
	}
	
	// POST処理
	postPage($obj_main_form, $edit_page);
}

/*==============================================================================
  ページPOST後のコールバック関数
  処理概要：
  		コールバック関数
  引数：
		$p_data				
  ============================================================================*/
function callBackFnc($p_data){
	
	if($p_data == "insert nomal"){
		// 正常終了
		alert("作業情報を登録しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	//	// ページを更新
	//	movePage($obj_hidden_form_list, $parent_page_file);
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("作業情報を更新しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}
