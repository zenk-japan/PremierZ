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
 人員管理画面メニュー用javascript関数
*******************************************************************************/
var $this_page_file			= "workstaff.php";						// 当画面のPHPファイル
var $obj_header_div			= "";
var $obj_detail_div			= "";
var $obj_td_dtl_header		= "";
var $obj_td_workstaff_dtl	= "";
var $edit_page_file			= "../../page/workstaff_edit.php";		// 編集ページのファイル名
var $proc_mode_insert		= "insert";
var $proc_mode_update		= "update";
var $edit_div_width_u		= 675;
var $edit_div_height_u		= 500;

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procDtlListHover(){
	// オブジェクト
	$l_trgt_obj_tr = $(".c_tr_workstaff_dtl");
	
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
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	// ヘッダーのDIV
	$obj_header_div = $("#id_div_dtl_header");
	// 明細のDIV
	$obj_detail_div = $("#id_div_dtl_detail");
	// ヘッダーの各TD
	$obj_td_dtl_header = $(".c_td_dtl_header");
	// 明細の各TD
	$obj_td_workstaff_dtl = $("#id_tr_workstaff_dtl1 > .c_td_workstaff_dtl");
	
	// リストホバー処理
	procDtlListHover();
	
	/*---------------------------------------
		明細スクロール時の処理
	  ---------------------------------------*/
	$obj_detail_div.scroll(function(){
		//alert(this.type+" got focus.");
		//$l_header_pos = $obj_header_div.scrollLeft();	// ヘッダーのスクロール量
		//$l_detail_pos = $obj_detail_div.scrollLeft();	// 明細のスクロール量
		//$l_mess_value = $l_header_pos + ":" + $l_detail_pos;
		//alert($l_mess_value);
		
		// 明細の位置にヘッダーの位置を合わせる
		$obj_header_div.scrollLeft($obj_detail_div.scrollLeft());
		if($obj_header_div.scrollLeft() != $obj_detail_div.scrollLeft()){
			// 縦のスクロールバーがある場合、右端でずれが起こるので、強制的に同期する
			$obj_detail_div.scrollLeft($obj_header_div.scrollLeft());
		}
	});
	
	/*---------------------------------------
		編集
	  ---------------------------------------*/
	$(".c_btn_workstaff_update").bind("click", function(){
		// クリックされた行番号を取得
		$l_clicked_detail_num = parseInt($(".c_btn_workstaff_update").index(this), 10) + 1;
		// 更新で編集画面を開く
		$edit_pages = 	$edit_page_file +
						"?bid=" + $proc_mode_update + 
						"&nm_token_code=" + 
						$obj_hidden_form.find("input[name='nm_token_code']").val() + 
						"&nm_selected_workcontents_id=" + 
						$obj_hidden_form.find("input[name='nm_selected_workcontents_id']").val() + 
						"&nm_workstaff_id=" + 
						$("#id_txt_workstaff_id"+$l_clicked_detail_num).val();
		GB_showCenter('人員 - 更新', $edit_pages, $edit_div_height_u, $edit_div_width_u);
	});
	/*---------------------------------------
		チェックボックスクリック時処理
	  ---------------------------------------*/
	$("#id_ckb_dtl_delete").bind("click", function(){
		$l_chk_checkbox = $(".c_chk_workstaff_report");
		
		if($(this).attr("checked")){
			// チェックが入った場合は全チェック
			$l_chk_checkbox.attr("checked",true);
		}else{
			// チェックが外れた場合は全外し
			$l_chk_checkbox.attr("checked",false);
		}
	});
});