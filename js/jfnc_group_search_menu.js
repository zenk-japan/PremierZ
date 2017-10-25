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
var $this_page_file_search	= "groups_mnt.php";

/*==============================================================================
  
  ============================================================================*/
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
			var $l_comp_name	= "";
			var $l_group_name	= "";
		
			// 入力された条件値を取得する
			$l_comp_name=$("#id_txt_cond_comp_name").val();
			$l_group_name=$("#id_txt_cond_group_name").val();
			
			// 取得した値をhidden項目にセットする
			$obj_hidden_form_list.find("input[name='nm_comp_name_cond']").val($l_comp_name);
			$obj_hidden_form_list.find("input[name='nm_group_name_cond']").val($l_group_name);
			
			// ページを1に戻す
			$obj_hidden_form_list.find("input[name='nm_show_page']").val("1");
			
			// 自ページにPOSTする
			//alert("this_page_file_search->"+$this_page_file_search);
			movePage($obj_hidden_form_list, $this_page_file_search);
		}
	);
	// 条件クリア
	$("#id_btn_input_clear").bind("click", function(){
			// テキストボックスの値をクリア
			$(".c_txt_search_textbox").val('');
		}
	);
});