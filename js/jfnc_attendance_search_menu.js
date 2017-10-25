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
 検索用javascript関数
*******************************************************************************/
var $obj_hidden_form_list;										// 隠し項目のオブジェクト
var $this_page_file_search	= "attendance_report.php";

/*==============================================================================
  検索処理
  ============================================================================*/
function procReQuery(){
	var $l_work_ym			= "";
	var $l_work_name		= "";
	var $l_workuser_name	= "";
	var $l_output_unit		= "";
	var $l_round_base		= "";
	var $l_round_method		= "";
	
	// 入力された条件値を取得する
	$l_work_ym			= $("#id_txt_cond_work_ym").val();
	$l_work_name		= $("#id_txt_cond_work_name").val();
	$l_workuser_name	= $("#id_txt_cond_workuser_name").val();
	$l_output_unit		= $("#id_select_output_unit").val();
	$l_round_base		= $("#id_select_round_base").val();
	$l_round_method		= $("#id_select_round_method").val();
	
	// 出力単位が作業者の場合は作業名をクリアする
	if($l_output_unit=="STAFF"){
		$l_work_name = "";
	}
	
	// 取得した値をhidden項目にセットする
	$obj_hidden_form_list.find("input[name='nm_work_ym_cond']").val($l_work_ym);
	$obj_hidden_form_list.find("input[name='nm_work_name_cond']").val($l_work_name);
	$obj_hidden_form_list.find("input[name='nm_workuser_name_cond']").val($l_workuser_name);
	$obj_hidden_form_list.find("input[name='nm_output_unit']").val($l_output_unit);
	$obj_hidden_form_list.find("input[name='nm_round_base']").val($l_round_base);
	$obj_hidden_form_list.find("input[name='nm_round_method']").val($l_round_method);
	
	// ページを1に戻す
	$obj_hidden_form_list.find("input[name='nm_show_page']").val("1");
	
	
	// 自ページにPOSTする
	//alert("this_page_file_search->"+$this_page_file_search);
	movePage($obj_hidden_form_list, $this_page_file_search);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	$obj_hidden_form_list = $("#id_form_hidden");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 検索
	$("#id_btn_input_search").bind("click", function(){
			// 選択済みの値をクリア
			$obj_hidden_form_list.find("input[name='nm_work_date_ym']").val('');
			$obj_hidden_form_list.find("input[name='nm_work_user_id']").val('');
			$obj_hidden_form_list.find("input[name='nm_estimate_id']").val('');
			
			// 再読み込み
			procReQuery();
		}
	);
	// 条件クリア
	$("#id_btn_input_clear").bind("click", function(){
			// テキストボックスの値をクリア
			$(".c_txt_search_textbox").val('');
		}
	);
	/*-----------------------------
		年月クリック処理
	  -----------------------------*/
	$("#id_txt_cond_work_ym").bind("dblclick", function(){
			showYMCalendar('id_txt_cond_work_ym');//** 本体はjfnc_calendar.jsの中
		}
	);
	/*-----------------------------
		年月更時処理
	  -----------------------------*/
	$("#id_txt_cond_work_ym").bind("change", function(){
			var $l_check_yyyymm = checkFormatYYYYMM($(this).val(), 'N');
			if($l_check_yyyymm != '0'){
				alert('年月は' + $l_check_yyyymm + '。');
				$(this).val('');
			}
		}
	);
	/*-----------------------------
		出力単位変更時処理
	  -----------------------------*/
	$("#id_select_output_unit").bind("change", function(){
			// 選択済みの値をクリア
			$obj_hidden_form_list.find("input[name='nm_work_date_ym']").val('');
			$obj_hidden_form_list.find("input[name='nm_work_user_id']").val('');
			$obj_hidden_form_list.find("input[name='nm_estimate_id']").val('');
			
			// 再読み込み
			procReQuery();
		}
	);
	/*-----------------------------
		丸め基準時間更時処理
	  -----------------------------*/
	$("#id_select_round_base").bind("change", function(){
			// 再読み込み
			procReQuery();
		}
	);
	/*-----------------------------
		 丸め方法更時処理
	  -----------------------------*/
	$("#id_select_round_method").bind("change", function(){
			// 再読み込み
			procReQuery();
		}
	);
});