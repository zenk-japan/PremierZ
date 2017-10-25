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
 勤務表画面リスト表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;										// 隠し項目のオブジェクト
var $this_page_file_list	= "attendance_report.php";
var	$ar_orgcolor = [];										// 各行の背景色
var $delete_check = 0;										// 削除のチェックが入っていない数
var $proc_mode	= "";										// 実行モード(STAFF or WORK)
var $mode_staff	= "STAFF";
var $mode_work	= "WORK";

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procListHover(){
	// オブジェクト
	$l_trgt_obj = $(".c_tr_list_menu");
	
	// 背景色設定が取得できない場合は終了
	if(!$ar_orgcolor){
		return false;
	}
	
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", $ar_orgcolor[$l_trgt_obj.index(this)]);
		}
	);
}

/*==============================================================================
  リストクリック時処理
  処理概要:選択された行の値をhidden項目にセットして自画面にPOST
  ============================================================================*/
function procClickTd($list_td_num){
	// 行番号を元に年月を取得
	$l_workdateym_item_id = "id_td_list_menu_workdate" + $list_td_num;
	$l_trgt_workdateym = $("#" + $l_workdateym_item_id).text();
	// 年月をhiddn項目に追加
	$obj_hidden_form_list.find("input[name='nm_work_date_ym']").val($l_trgt_workdateym);
	
	// 行番号を元に作業者IDを取得
	$l_workuser_item_id = "id_hdn_work_user_id" + $list_td_num;
	$l_trgt_workuser_id = $("#" + $l_workuser_item_id).val();
	// 作業者IDをhiddn項目に追加
	$obj_hidden_form_list.find("input[name='nm_work_user_id']").val($l_trgt_workuser_id);
	
	if($proc_mode == $mode_work){
		// 行番号を元に見積IDを取得
		$l_estimate_item_id = "id_hdn_estimate_id" + $list_td_num;
		$l_trgt_estimate_id = $("#" + $l_estimate_item_id).val();
		// 見積IDをhiddn項目に追加
		$obj_hidden_form_list.find("input[name='nm_estimate_id']").val($l_trgt_estimate_id);
	}
	
	// ページを更新
	var $l_hidden_items = "";
	/*
	$("#id_form_hidden").find("input[type='hidden']").each(function(){
		$l_hidden_items = $l_hidden_items + $(this).attr('name') + "->" + $(this).val() + "\n";
	});
	alert($l_hidden_items);
	*/
	movePage($obj_hidden_form_list, $this_page_file_list);
}

/*==============================================================================
  リスト部の背景色セット
  処理概要：リスト部の背景色をセットする。現在表示中の拠点IDの行は強調する
  ============================================================================*/
function backgroundColorSetup(){
	// 行内の年月、作業者ID、見積ID
	var $l_row_workdateym = "";
	var $l_row_workuserid = "";
	var $l_row_estimateid = "";
	
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_list_menu");
	
	// 選択中の年月、作業者ID、見積ID
	var $l_selected_workdateym = "";
	var $l_selected_workuserid = "";
	var $l_selected_estimateid = "";
	
	// 選択中の年月
	$l_selected_workdateym = $obj_hidden_form_list.find("input[name='nm_work_date_ym']").val();
	// 選択中の作業者ID
	$l_selected_workuserid = $obj_hidden_form_list.find("input[name='nm_work_user_id']").val();
	// 選択中の見積ID
	if($proc_mode == $mode_work){
		$l_selected_estimateid = $obj_hidden_form_list.find("input[name='nm_estimate_id']").val();
	}
	
	if($l_trgt_obj){
		$l_trgt_obj.each(function(){
				// 年月の取得
				$l_row_workdateym = $(this).find(".c_td_list_menu_workdate").text();
				$l_row_workdateym = $l_row_workdateym.replace(/(^\s+)|(\s+$)/g, "");
				// 作業者IDの取得
				$l_row_workuserid = $(this).find(".c_td_list_menu_workuser").find("input[class='c_hdn_work_user_id']").val();
				// 見積IDの取得
				if($proc_mode == $mode_work){
					$l_row_estimateid = $(this).find(".c_td_list_menu_workname").find("input[class='c_hdn_estimate_id']").val();
				}
			
				//alert("l_row_workdateym->"+$l_row_workdateym+":" +"l_selected_workdateym->"+$l_selected_workdateym+":" +"l_row_workuserid->"+$l_row_workuserid+":" +"l_selected_workuserid->"+$l_selected_workuserid+":" +"l_row_estimateid->"+$l_row_estimateid+":" +"l_selected_estimateid->"+$l_selected_estimateid);
				// 選択中のグループIDと一致した場合は強調色
				if($l_row_workdateym == $l_selected_workdateym && $l_row_workuserid == $l_selected_workuserid && $l_row_estimateid == $l_selected_estimateid){
					$ar_orgcolor[$l_trgt_obj.index(this)] = '#40ff90';
				}else{
					$ar_orgcolor[$l_trgt_obj.index(this)] = 'transparent';
				}
				
				// 背景色設定
				$(this).find("td").css("background-color", $ar_orgcolor[$l_trgt_obj.index(this)]);
			}
		);
	}
}

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// モード取得
	$proc_mode = $obj_hidden_form_list.find("input[name='nm_output_unit']").val();
	
	$lr_param = {};				// 配列の初期化
	// 隠し項目のFORM
	$obj_hidden_form_list = $("#id_form_hidden");
	
	// 背景色セット
	backgroundColorSetup();
	
	// リストホバー処理
	procListHover();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 前のページ
	$("#id_btn_prev").bind("click", function(){
			// ページ番号を更新してmove
			$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_show_page']").val(), 10) - 1;
			$obj_hidden_form_list.find("input[name='nm_show_page']").val($l_prev_page);
			movePage($obj_hidden_form_list, $this_page_file_list);
		}
	);
	
	// 次のページ
	$("#id_btn_next").bind("click", function(){
			// ページ番号を更新してmove
			$l_next_page = parseInt($obj_hidden_form_list.find("input[name='nm_show_page']").val(), 10) + 1;
			$obj_hidden_form_list.find("input[name='nm_show_page']").val($l_next_page);
			movePage($obj_hidden_form_list, $this_page_file_list);
		}
	);
	/*-----------------------------
		作業日クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_workdate").bind("click", function(){
		// クリックされた作業場所の行番号を取得
		$l_clicked_num = parseInt($(".c_td_list_menu_workdate").index(this), 10) + 1;
		
		procClickTd($l_clicked_num);
	});
	
	/*-----------------------------
		作業者名クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_workuser").bind("click", function(){
		// クリックされた会社の行番号を取得
		$l_clicked_num = parseInt($(".c_td_list_menu_workuser").index(this), 10) + 1;
		
		procClickTd($l_clicked_num);
	});
	
	/*-----------------------------
		作業名クリック時処理
	  -----------------------------*/
	if($proc_mode == $mode_work){
		$(".c_td_list_menu_workname").bind("click", function(){
			// クリックされた会社の行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_workname").index(this), 10) + 1;
			
			procClickTd($l_clicked_num);
		});
	}
});