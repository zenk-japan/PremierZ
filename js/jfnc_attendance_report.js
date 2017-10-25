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
 勤務表画面明細用javascript関数
*******************************************************************************/
var $this_page_file			= "attendance_report.php";			// 当画面のPHPファイル
var $pdf_output_file_list	= "attendance_pdffile.php";			// PDF出力用PDFファイル
var $attendance_print_page	= "../page/attendance_print.php";	// 勤務表印刷用PHPファイル
var $obj_hidden_form_detail;									// 隠し項目のオブジェクト

/*==============================================================================
  印刷用HTML出力ボタンクリック時処理
  処理概要：
  		印刷用のHTML画面をポップアップで表示する
  ============================================================================*/
function showUserPopup(){
	$edit_pages =	$attendance_print_page +
					"?token_code=" +
					$obj_hidden_form_detail.find("input[name='nm_token_code']").val() +
					"&output_unit=" +
					$obj_hidden_form_detail.find("input[name='nm_output_unit']").val() +
					"&round_base=" +
					$obj_hidden_form_detail.find("input[name='nm_round_base']").val() +
					"&round_method=" +
					$obj_hidden_form_detail.find("input[name='nm_round_method']").val() +
					"&work_date_ym=" +
					$obj_hidden_form_detail.find("input[name='nm_work_date_ym']").val() +
					"&estimate_id=" +
					$obj_hidden_form_detail.find("input[name='nm_estimate_id']").val() +
					"&work_user_id=" +
					$obj_hidden_form_detail.find("input[name='nm_work_user_id']").val();
	openPopup($edit_pages, '勤務表', 700, 500, "location=no, menubar=yes, status=no, scrollbars=yes, resizable=no, toolbar=no");
}
/*============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_hidden_form_detail = $("#id_form_hidden");
	
	//==============================================
	// PDF出力ボタン
	//==============================================
	$("#id_btn_detail_pdfbtn").bind("click", function(){
		movePage($obj_hidden_form_detail, $pdf_output_file_list);
	});
	
	//==============================================
	// 印刷用HTML出力ボタン
	//==============================================
	$("#id_btn_detail_htmlbtn").bind("click", function(){
		showUserPopup();
	});
});
