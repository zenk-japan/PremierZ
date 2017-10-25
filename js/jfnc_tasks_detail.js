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
 プロジェクト画面詳細表示用javascript関数
*******************************************************************************/
var $this_page_file		= "tasks.php";
var $overview_page		= "tasks_overview.php";
var $delete_check		= 0;									// 削除のチェックが入っていない数
var $edit_page_file		= "../../page/tasks_edit.php";			// 編集ページのファイル名
var $detail_page_file	= "workstaff.php";						// 作業ページのファイル名
var $delete_ctl_file	= "../ctl/c_delete_tasks.php";
var $proc_mode_insert	= "insert";
var $proc_mode_update	= "update";
var $edit_div_width		= 650;
var $edit_div_height	= 540;

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procDtlListHover(){
	// オブジェクト
	$l_trgt_obj_tr = $(".c_tr_task_dtl");
	
	$l_trgt_obj_tr.hover(
		function(){
		// カーソルホバー時
			//alert("hover");
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			//alert("hoverout");
			// 背景
			$(this).find("td").css("background-color", 'transparent');
		}
	);
}

/*==============================================================================
  作業コードコンボボックス変更時処理
  処理概要：選択値をPOSTして再読み込み
  ============================================================================*/
function procChangeWorkCode($p_selected_value){
	// 隠し項目に作業コードをセット
	$obj_hidden_form.find("input[name='nm_work_content_code']").val($p_selected_value);
	
	// ページを1に設定
	$obj_hidden_form.find("input[name='nm_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  有効のみ表示チェックボックスクリック時処理
  処理概要：チェック状態をPOSTして再読み込み
  ============================================================================*/
function procValidOnlyCheckBox($p_checkstat){
	if($p_checkstat){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form.find("input[name='nm_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form.find("input[name='nm_valid_checkstat']").val('N');
	}
	// ページを1に設定
	$obj_hidden_form.find("input[name='nm_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  ページを選択する
  処理概要：
  		選択されたページで自画面にPOSTする
  引数：
  ============================================================================*/
function selectPage(){
	$l_selected_page = parseInt($("#id_sel_po_page").val(), 10);
	//alert ($l_selected_page);
	// ページを設定し次画面にPOST
	$obj_hidden_form.find("input[name='nm_show_page']").val($l_selected_page);
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  人員ボタンクリック時処理
  処理概要：
  			クリックされた行の主キーを取得し、人員画面にPOSTする
  引数：
  			$p_clicked_row_num				クリックされた行の番号
  ============================================================================*/
function procClickTaskDetail($p_clicked_row_num){
	// 既に隠し項目がappenされている場合は、削除する
	if($obj_hidden_form.find('input[name="nm_selected_workcontents_id"]')){
		$obj_hidden_form.find('input[name="nm_selected_workcontents_id"]').remove();
	}
	
	// 行番号を元に作業IDを取得
	$l_trgt_workcontents_id = $("#id_txt_work_content_id"+$p_clicked_row_num).val()
	
	// 隠し項目に追加
	$l_html = '<input type="hidden" name="nm_selected_workcontents_id" value="'+$l_trgt_workcontents_id+'"/>'
	$obj_hidden_form.append($l_html);
	
	// ページを1に設定
	$obj_hidden_form.find("input[name='nm_show_page']").val('1');
	
	//人員管理画面に移動
	movePage($obj_hidden_form, $detail_page_file);
}
/*==============================================================================
  削除用POST処理
  処理概要：
  		削除対象のIDをパラメータにセットしてPOST処理を行う
  ============================================================================*/
function procDeletePost(){
	var $l_checked_tasks = $(".c_chk_task_report:checked");
	var $lr_param = {};			// 連想配列の初期化
	
	// チェックが１つも入っていなければ終了
	if ($l_checked_tasks.size() < 1){
		alert("削除対象にチェックを入れてください。");
		return false;
	}
	// 最終確認
	if(!window.confirm('選択されたデータを削除します。\nよろしいですか？')){
		return false;
	}
	
	// トークンコードをパラメータにセット
	$lr_param['nm_token_code'] = $obj_hidden_form.find("input[name='nm_token_code']").val();
	
	// チェックの入った列の人員IDをパラメータにセット
	$l_cnt = 0;
	$l_checked_tasks.each(function(){
		$lr_param[$l_cnt] = $(".c_hd_td_task_id").eq($(".c_chk_task_report").index(this)).val();
		$l_cnt++;
	});
	
	// POST処理
	$.post($delete_ctl_file, $lr_param, callBackProcDel);
}
// コールバック関数
function callBackProcDel($p_data){
	//alert($p_data);
	//return true;
	
	if($p_data){
		if($p_data == "delete normal"){
			// 何もかえって来なければ正常終了
			alert("削除が完了しました。");
		}else{
			alert($p_data);
		}
	}else{
		alert("削除できませんでした。");
		return false;
	}
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  概要クリック時処理
  処理概要：
  			作業の概要をポップアップで表示する
  引数：
 			 $p_wc_id クリックされた明細行の作業ID
============================================================================*/
function showOverview($p_wc_id){
	
	$edit_pages = $overview_page +
					"?token_code=" +
					$obj_hidden_form.find("input[name='nm_token_code']").val() +
					"&work_content_id=" +
					$p_wc_id;
	openPopup($edit_pages, '作業概要', 850, screen.height,"location=no, menubar=no, status=yes, scrollbars=yes, resizable=yes, toolbar=no");
} 

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/

$(function(){
	$lr_param = {};				// 配列の初期化
	// 隠し項目のFORM
	//※project_common.jsで取得
	$selected_tab = "";
	
	// リストホバー処理
	procDtlListHover();
	
	/*---------------------------------------
		作業コードコンボボックス変更時処理
	  ---------------------------------------*/
	$("#id_sel_task_search_by_wc").bind("change", function(){
		procChangeWorkCode($(this).val());
	});
	
	/*-----------------------------
		ページ選択時処理
	  -----------------------------*/
	// ページ操作ボタンがある場合はクリック時処理をバインド
	if("#id_sel_po_page"){
		// ページコンボボックス
		$("#id_sel_po_page").bind("change", function(){
			selectPage();
		});
	}
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 新規作成
	$("#id_btn_task_insert").bind("click", function(){
		// 新規で編集画面を開く
		$edit_pages =	$edit_page_file + 
						"?bid=" + 
						$proc_mode_insert + 
						"&nm_token_code=" + 
						$obj_hidden_form.find("input[name='nm_token_code']").val() + 
						"&nm_selected_estimate_id=" + 
						$obj_hidden_form.find("input[name='nm_selected_estimate_id']").val() + 
						"&nm_work_cal_yyyy=" + 
						$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val() + 
						"&nm_work_cal_mm=" + 
						$obj_hidden_form.find("input[name='nm_work_cal_mm']").val() + 
						"&nm_work_cal_dd=" + 
						$obj_hidden_form.find("input[name='nm_work_cal_dd']").val() + 
						"&nm_token_code=" + 
						$obj_hidden_form.find("input[name='nm_token_code']").val();
		GB_showCenter('作業 - 新規作成', $edit_pages, $edit_div_height, $edit_div_width);
	});
	
	// 再表示
	$("#id_btn_task_reload").bind("click", function(){
		// 再読み込み
		movePage($obj_hidden_form, $this_page_file);
	});
	
	// 前のページ
	$("#id_btn_ope_prev").bind("click", function(){
		// ページ番号を更新してmove
		$l_prev_page = parseInt($obj_hidden_form.find("input[name='nm_show_page']").val(), 10) - 1;
		$obj_hidden_form.find("input[name='nm_show_page']").val($l_prev_page);
		movePage($obj_hidden_form, $this_page_file);
	});
	
	// 次のページ
	$("#id_btn_ope_next").bind("click", function(){
		// ページ番号を更新してmove
		$l_next_page = parseInt($obj_hidden_form.find("input[name='nm_show_page']").val(), 10) + 1;
		$obj_hidden_form.find("input[name='nm_show_page']").val($l_next_page);
		movePage($obj_hidden_form, $this_page_file);
	});
	
	// 人員
	$(".c_btn_task_dtl").bind("click", function(){
		// クリックされた行番号を取得
		$l_clicked_detail_num = parseInt($(".c_btn_task_dtl").index(this), 10) + 1;
		//alert($("#id_btn_task_dtl"+$l_clicked_detail_num).val());
		procClickTaskDetail($l_clicked_detail_num);
	});
	
	// 概要
	$(".c_btn_task_report").bind("click", function(){
		// クリックされた行番号を取得
		$l_clicked_detail_num = parseInt($(".c_btn_task_report").index(this), 10) + 1;
		showOverview($("#id_txt_work_content_id"+$l_clicked_detail_num).val());
	});
	
	// 編集
	$(".c_btn_task_update").bind("click", function(){
		// クリックされた行番号を取得
		$l_clicked_detail_num = parseInt($(".c_btn_task_update").index(this), 10) + 1;
		// 更新で編集画面を開く
		$edit_pages = 	$edit_page_file +
						"?bid=" + $proc_mode_update + 
						"&nm_token_code=" + 
						$obj_hidden_form.find("input[name='nm_token_code']").val() + 
						"&nm_work_content_id=" + 
						$("#id_txt_work_content_id"+$l_clicked_detail_num).val();
		GB_showCenter('作業 - 更新', $edit_pages, $edit_div_height, $edit_div_width);
	});
	
	// 削除
	$("#id_btn_task_delete").bind("click", function(){
		procDeletePost();
	});
	/*---------------------------------------
		有効のみ表示クリック時処理
	  ---------------------------------------*/
	$("#id_ckb_task_onlyinvalid").bind("click", function(){
		$l_stat_valid_checkbox = $(this).attr("checked");
		procValidOnlyCheckBox($l_stat_valid_checkbox);
	});
	
	/*---------------------------------------
		チェックボックスクリック時処理
	  ---------------------------------------*/
	$("#id_chk_task_dtl_top").bind("click", function(){
		$l_chk_checkbox = $(".c_chk_task_report");
		
		if($(this).attr("checked")){
			// チェックが入った場合は全チェック
			$l_chk_checkbox.attr("checked",true);
		}else{
			// チェックが外れた場合は全外し
			$l_chk_checkbox.attr("checked",false);
		}
	});
});