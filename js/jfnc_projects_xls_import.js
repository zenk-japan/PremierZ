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
 Excelデータインポート画面表示用javascript関数
*******************************************************************************/
var $obj_main_form;												// POST対象オブジェクト
var $this_page_file		= "xls_import.php";
var $xls_import			= "../ctl/c_projects_xls_import.php";	// excelデータインポート処理
var $lr_param			= {};									// 連想配列の初期化
var $post_excel_file;											// POST対象エクセルファイル(FQDNだとダメみたいです)
var $xls_upload			= "../lib/FileUpload.php";

var $l_debug_mode	= 0;										// デバッグモード(0:無効、1:有効)
if($l_debug_mode == 1){
	$post_excel_file = "/var/www/html/ztest/uploads/Book1.xlsx";
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/

	// エクセルアップロード upload(url, [data], [callback], [type])
	$('#id_btn_upload').change(function(upload) {
		$(this).upload(
			$xls_upload,		//url
			function(res) {		//callback
				$post_excel_file = (res);
				$('#id_xls_print').text("ファイルアップロード完了");
			},
			'text'				//type
		);
	});


	// エクセルインポート
	$("#id_btn_submit").bind("click", function(){
		if($post_excel_file != null){
//			if (confirm($post_excel_file + "のデータをインポートします")){
			if (confirm("データインポート処理を開始します")){
				postXls($obj_main_form, $xls_import,$post_excel_file);
			}else{
				parent.parent.GB_CURRENT.hide();
			}
		}else{
			alert("データをインポートしたいexcelファイルをアップロードしてください");
		}
	});
	
	// エクセル出力をクリア
	$("#id_btn_clear").bind("click", function(){
		$('#id_xls_print').text("");
	});

	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
});
/*==============================================================================
  ページPOST
  処理概要：
  		コールバック関数
  引数：
		$p_data				
  ============================================================================*/

function callBackFnc($p_param){
//	$('#id_xls_print').text($p_param);
//	alert($p_param);
	$('#id_xls_print').text("インポート処理完了");
	alert("インポート処理完了");parent.parent.GB_CURRENT.hide();
}

function callBackFncSql($p_data){
	
	if($p_data == "insert nomal"){
		// 正常終了
		alert("会社管理情報を登録しました。");
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	//	// ページを更新
	//	movePage($obj_hidden_form_list, $this_page_file);
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("会社管理情報を更新しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $this_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
	$('#id_xls_print').text($p_data);
}

/*==============================================================================
  postXls
  処理概要：
  		セッション情報とxlsファイルのフルパスをphpへpost
  引数：
		$p_object			:セッション情報
		$p_post_to			:post先phpのパス
		$post_excel_file	:処理するxlsファイルのパス
  ============================================================================*/
function postXls($p_object, $p_post_to,$post_excel_file){
	// 引数設定
	if($p_object){
		// inputタグのtextとhiddenを取得
		//$lobj_param = $p_object.find("input[type='text'],input[type='password'],input[type='checkbox']:checked,input[type='radio']:checked,select,textarea,input[type='hidden']");
		$lobj_param = $p_object.find("input[type='checkbox']:checked,input[type='hidden']");
		
		if($lobj_param){
			$l_cnt = 0;
			//チェックボックスで渡す値の初期化
			$lr_param["ins_task"]	 = "0";
			$lr_param["ins_task"]	 = "0";
			$lr_param["ins_staff"]	 = "0";
			// 値を連想配列に格納する
			$lobj_param.each(function(){
				$lr_param[$(this).attr("name")]	= $(this).val();
				$l_cnt = $l_cnt + 1;
			});
			$lr_param["excel_file"]	= $post_excel_file;
		}else{
			// form内にtextまたはhiddenがない場合
			$message = "オブジェクト不正です";
			return false;
		}
	}else{
		// p_objectが空の場合
		$message = "オブジェクト不正です";
		return false;
	}
	// POST処理
	$.post($p_post_to, $lr_param, callBackFnc);
}
