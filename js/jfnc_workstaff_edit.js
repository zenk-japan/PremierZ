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
 人員管理画面用javascript関数
*******************************************************************************/
var $this_page_file			= "workstaff_edit.php";				// 当画面のPHPファイル
var $user_list_file			= "../ctl/c_getUserList.php";		// ユーザーリスト作成用PHP
var $parent_page_file		= "workstaff.php";					// 親画面のPHPファイル
var $edit_page				= "../ctl/c_editWorkstaff.php";		// 更新用PHPファイル
var $lr_param				= {};								// 連想配列の初期化
var $obj_main_form;												// POST対象オブジェクト
var $lobj_others_list		= "";
var $lobj_members_list		= "";
var $proc_mode_insert		= "insert";
var $proc_mode_update		= "update";
var $userinfo_div_width		= 640;
var $userinfo_div_height	= 500;
var $userinfo_page			= "../page/userinfo.php";					// ユーザー情報表示用PHPファイル

/*==============================================================================
  ユーザーリスト読み込み処理
  処理概要：会社名とグループ名からユーザーリストを読み込んで
            リストボックスに表示する
  ============================================================================*/
function makeUserList(){
	var $l_comp_name = $("#id_txt_edit_work_company_name").val();
	var $l_group_name = $("#id_txt_edit_work_group_name").val();
	var $lr_param = {};			// 連想配列の初期化
	
	// 会社名とグループ名が入力されていない場合は終了
	if (   $l_comp_name == ""
		|| $l_comp_name === undefined
		|| $l_group_name == ""
		|| $l_group_name === undefined
	){
		alert("会社名とグループ名を両方入力して下さい。");
		return false;
	}
	// POST用パラメータ設定
	$lr_param['company_name']	= $l_comp_name;
	$lr_param['group_name']		= $l_group_name;
	$lr_param['data_id']		= $("#id_hd_data_id").val();
	$lr_param['token_code']		= $("#id_hd_nm_token_code").val();
	
	// POST処理
	$.post($user_list_file, $lr_param, callBackUserList);
}
// コールバック関数
function callBackUserList($p_data){
	//alert($p_data);
	//return true;
	
	if($p_data){
		if($p_data == "0"){
			alert("該当するユーザーが存在しませんでした。");
			$("#id_sel_edit_user_list_all").html('');
			// bind処理を削除
			$(".c_opt_edit_user_list").bind("dblclick");
			return false;
		}else{
			// リストボックスにリストを追加
			$("#id_sel_edit_user_list_all").html($p_data);
			// bind処理を追加
			$(".c_opt_edit_user_list").bind("dblclick", userListDblClick);
		}
	}else{
		alert("データが取得できませんでした");
		return false;
	}
}
/*============================================================================
  リスト内容移動処理
  引数:
		$po_from							移動元のリスト
		$po_to								移動先のリスト
		$p_dest_option_class				移動先リストのOptionのクラス
  ============================================================================*/
function moveItem($po_from, $po_to, $p_dest_option_class){
	var $l_selected_item_name;
	var $l_selected_item_id;
	var $l_selected_item_index;
	var $l_insert_ok_flag = "Y";
	
	// 選択されている項目の値を取得
	$l_selected_item_name	= $po_from.find('option:selected').html();
	$l_selected_item_id		= $po_from.find('option:selected').val();
	$l_selected_item_index	= $po_from.find('option').index($po_from.find('option:selected'));
	//alert("l_selected_item_name->"+$l_selected_item_name+":"+"l_selected_item_id->"+$l_selected_item_id+":"+"l_selected_item_index->"+$l_selected_item_index);
	
	if($l_selected_item_id != "" && $l_selected_item_id != undefined && $l_selected_item_id != null){
		// 既にリスト内にある場合は警告を表示して追加は行わない
		var $l_old_list = $po_to.find('option');
		$l_old_list.each(function(){
			if ($(this).val() == $l_selected_item_id){
				alert("そのユーザーは既に追加されています。");
				$l_insert_ok_flag = "N";
			}
		});
		// 先のリストに追加
		if ($l_insert_ok_flag == "Y"){
			var $l_add_html = '<option class="'+$p_dest_option_class+'" value="'+$l_selected_item_id+'">'+$l_selected_item_name+'</option>';
			$po_to.append($l_add_html);
		}
	}
}
/*============================================================================
  リスト内容削除処理
  引数:
		$po_from							元のリスト
  ============================================================================*/
function removeItem($po_from){
	var $l_selected_item_name;
	var $l_selected_item_id;
	var $l_selected_item_index;
	// 選択されている項目の値を取得
	$l_selected_item_name	= $po_from.find('option:selected').html();
	$l_selected_item_id		= $po_from.find('option:selected').val();
	$l_selected_item_index	= $po_from.find('option').index($po_from.find('option:selected'));
	
	if($l_selected_item_id != "" && $l_selected_item_id != undefined && $l_selected_item_id != null){
		// 削除処理
		$po_from.find('option').eq($l_selected_item_index).remove();
	}
}

/*==============================================================================
  POST処理
  処理概要：
  		登録/更新設定をhidden項目にセットしてPOST処理を行う
  ============================================================================*/
function procPost(){
	var $l_html				= "";
	var $lobj_opt_user		= "";
	var $l_proc_mode		= $("#id_hd_sql_type").val();
	
	if ($l_proc_mode == $proc_mode_insert){
	// insert時にユーザーリストに１件もユーザーが無い場合はエラーとする
		$lobj_opt_user		= $lobj_members_list.find('option');
		if ($lobj_opt_user.size() < 1){
			alert("ユーザーを最低１名リストに追加して下さい。");
			return false;
		}
	// insert時は、ユーザーリストからユーザーを読み込み、隠し項目にセットする
		$l_cnt = 1;
		$lobj_opt_user.each(function(){
			$l_html = '<input type="hidden" name="nm_work_user_id' + $l_cnt + '" value="' + $(this).val() + '"/>';
			$obj_main_form.append($l_html);
			$l_cnt++;
		});
	}
	// update時は、作業費表示フラグの値を別途POSTする
	if ($l_proc_mode == $proc_mode_update){
		if ($("#id_rdb_work_unit_price_display_flag_y").attr("checked") == true){
			$l_html = '<input type="hidden" name="nm_unit_price_display" value="Y"/>';
			$obj_main_form.append($l_html);
		}else{
			$l_html = '<input type="hidden" name="nm_unit_price_display" value="N"/>';
			$obj_main_form.append($l_html);
		}
	}
	
	// POST処理
	postPage($obj_main_form, $edit_page, callBackFnc);
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
		alert("人員情報を登録しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("人員情報を更新しました。");
		// ページを更新
		parent.parent.movePage($obj_main_form, $parent_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}

/*==============================================================================
  ユーザーリストダブルクリック時処理
  処理概要：
  		該当ユーザーの情報をポップアップで表示する
  ============================================================================*/
function userListDblClick(){
	//alert($(this).val());
	$edit_pages =	$userinfo_page +
					"?token_code=" +
					$("#id_hd_nm_token_code").val() +
					"&user_id=" +
					$(this).val();
	openPopup($edit_pages, 'ユーザー情報', 700, 500);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	$lobj_others_list	= $("#id_sel_edit_user_list_all");
	$lobj_members_list	= $("#id_sel_edit_user_list_insert");
	
	$.updnWatermark.attachAll();
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// リスト移動
	// 追加
	$("#id_btn_edit_user_in").bind("click", function(){
		// メンバー移動
		moveItem($lobj_others_list, $lobj_members_list, "c_opt_edit_user_insert");
	});
	
	// 削除
	$("#id_btn_edit_user_out").bind("click", function(){
		// メンバー削除
		removeItem($lobj_members_list);
	});
	
	// 作成
	$("#id_btn_create").bind("click", function(){
		procPost();
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		procPost();
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
	
	/*-----------------------------
		ユーザーリスト読込処理
	  -----------------------------*/
	$("#id_btn_edit_user_load").bind("click", function(){
		makeUserList();
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