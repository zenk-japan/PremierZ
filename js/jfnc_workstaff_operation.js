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
var $this_page_file		= "workstaff.php";							// 当画面のPHPファイル
var $delete_check		= 0;										// 削除のチェックが入っていない数
var $edit_page_file		= "../../page/workstaff_edit.php";			// 編集ページのファイル名
var $mail_page_file		= "../../page/workstaff_mailsend.php";		// メール送信ページのファイル名
var $delete_ctl_file	= "../ctl/c_delete_workstaff.php";			// 削除実行のファイル名
var $proc_mode_insert	= "insert";
var $proc_mode_update	= "update";
var $edit_div_width		= 675;
var $edit_div_height	= 580;
var $mail_div_width		= 660;
var $mail_div_height	= 580;

/*==============================================================================
  削除用POST処理
  処理概要：
  		削除対象のIDをパラメータにセットしてPOST処理を行う
  ============================================================================*/
function procDeletePost(){
	var $l_checked_workstaff = $(".c_chk_workstaff_report:checked");
	var $lr_param = {};			// 連想配列の初期化
	
	// チェックが１つも入っていなければ終了
	if ($l_checked_workstaff.size() < 1){
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
	$l_checked_workstaff.each(function(){
		$lr_param[$l_cnt] = $(".c_hd_td_workstaff_id").eq($(".c_chk_workstaff_report").index(this)).val();
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
			// 正常終了
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
  メール送信用GET処理
  処理概要：
  		送信対象のIDをパラメータにセットしてGET処理を行う
  ============================================================================*/
function procMainsendGet(){
	var $l_checked_workstaff = $(".c_chk_workstaff_report:checked");
	var $lr_param = {};			// 連想配列の初期化
	
	// チェックが１つも入っていなければ終了
	if ($l_checked_workstaff.size() < 1){
		alert("メール送信対象にチェックを入れてください。");
		return false;
	}
	
	// メール送信画面を開く
	$edit_pages =	$mail_page_file +
					"?page=" +
					$this_page_file +
					"&nm_token_code=" +
					$obj_hidden_form.find("input[name='nm_token_code']").val();
	// チェックの入っているレコードの人員IDをパラメータに付加
	$l_cnt = 0;
	$l_checked_workstaff.each(function(){
		$edit_pages =	$edit_pages +
						"&target_workstaff_id" + $l_cnt + "=" +
						$(".c_hd_td_workstaff_id").eq($(".c_chk_workstaff_report").index(this)).val();
		$l_cnt ++;
	});
	
	GB_showCenter('作業人員 - メール送信', $edit_pages, $mail_div_height, $mail_div_width);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// メール送信
	$("#id_btn_mail_send").bind("click", function(){
		procMainsendGet();
	});
	
	// 新規
	$("#id_btn_insert").bind("click", function(){
		// 新規で編集画面を開く
		$edit_pages =	$edit_page_file +
						"?bid=" + 
						$proc_mode_insert + 
						"&nm_token_code=" + 
						$obj_hidden_form.find("input[name='nm_token_code']").val() + 
						"&nm_selected_workcontents_id=" + 
						$obj_hidden_form.find("input[name='nm_selected_workcontents_id']").val();
		GB_showCenter('作業人員 - 新規作成', $edit_pages, $edit_div_height, $edit_div_width);
	});
	
	// 再表示
	$("#id_btn_reload").bind("click", function(){
		// 再読み込み
		movePage($obj_hidden_form, $this_page_file);
	});
	
	// 削除
	$("#id_btn_delete").bind("click", function(){
		procDeletePost();
	});
	
});