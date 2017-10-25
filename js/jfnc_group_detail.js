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
 作業拠点詳細画面表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;											// 隠し項目のオブジェクト
var $l_trgt_keyvalue_id	= "";									// 表示対象ID
var $page_groups_edit	= "../../page/groups_edit.php";			// グループ管理編集phpファイル
var $ar_member_list		= [];
var $ar_others_list		= [];
var $c_detail_members	= "c_opt_select_detail_members";		// メンバーのリスト
var $c_detail_others	= "c_opt_select_detail_others";			// その他メンバーのリスト
var $groupdetail_post_to = "../ctl/c_group_member_update.php";	// グループメンバー保存処理のPHPファイル

/*============================================================================
  リスト内容取得処理
  ============================================================================*/
function gatherListValue(){
	var $l_member_name;
	var $l_member_id;
	var $l_cnt = 0;
	
	// メンバーリスト
	$("."+$c_detail_members).each(function(){
		$l_member_name	= $(this).html();
		$l_member_id	= $(this).val();
		//alert("l_member_name->"+$l_member_name+":"+"l_member_id->"+$l_member_id);
		$ar_member_list[$l_cnt] = $l_member_id;
		$l_cnt++;
	});
	
	var $l_other_name;
	var $l_other_id;
	$l_cnt = 0;
	// その他リスト
	$("."+$c_detail_others).each(function(){
		$l_other_name	= $(this).html();
		$l_other_id		= $(this).val();
		//alert("l_other_name->"+$l_other_name+":"+"l_other_id->"+$l_other_id);
		$ar_others_list[$l_cnt] = $l_other_id;
		$l_cnt++;
	});
}
/*============================================================================
  メンバーリストカウント取得処理
  ============================================================================*/
function getMembersCount(){
	$l_return_value = $("."+$c_detail_members).size();
	if(!$l_return_value){
		$l_return_value = 0;
	}
	return $l_return_value;
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

	var $from_list_width;
	var $from_list_height;
	var $to_list_width;
	var $to_list_height;
	// リストサイズ取得
	$from_list_width	= $po_from.css("width");
	$from_list_height	= $po_from.css("height");
	$to_list_width		= $po_to.css("width");
	$to_list_height		= $po_to.css("height");
	//alert($from_list_width+":"+$from_list_height+":"+$to_list_width+":"+$to_list_height);
	
	// 選択されている項目の値を取得
	$l_selected_item_name	= $po_from.find('option:selected').html();
	$l_selected_item_id		= $po_from.find('option:selected').val();
	$l_selected_item_index	= $po_from.find('option').index($po_from.find('option:selected'));
	//alert("l_selected_item_name->"+$l_selected_item_name+":"+"l_selected_item_id->"+$l_selected_item_id+":"+"l_selected_item_index->"+$l_selected_item_index);
	
	if($l_selected_item_id != "" && $l_selected_item_id != undefined && $l_selected_item_id != null){
		// 元のリストから削除
		$po_from.find('option').eq($l_selected_item_index).remove();
		// サイズを保つ
		$po_from.css("width", $from_list_width);
		$po_from.css("height", $from_list_height);
		
		// 先のリストに追加
		var $l_add_html = '<option class="'+$p_dest_option_class+'" value="'+$l_selected_item_id+'">'+$l_selected_item_name+'</option>';
		$po_to.append($l_add_html);
		// サイズを保つ
		$po_to.css("width", $to_list_width);
		$po_to.css("height", $to_list_height);
	}
}

/*============================================================================
  保存処理
  処理概要：メンバーリストの内容を画面起動時に取得した配列と比較し、
            変更があった分についてDBを更新する
  ============================================================================*/
function saveMembers(){
	var $lr_now_value = [];
	var $lr_org_value = [];
	var $l_member_name;
	var $l_member_id;
	var $l_cnt = 0;
	var $l_change_cnt = 0;
	var $lr_post_param = {};
	
	var $lr_update_param = {};
	
	// 現在の値を配列に取得
	$("."+$c_detail_members).each(function(){
		$l_member_name	= $(this).html();
		$l_member_id	= $(this).val();
		//alert("l_member_name->"+$l_member_name+":"+"l_member_id->"+$l_member_id);
		$lr_now_value[$l_cnt] = $l_member_id;
		$l_cnt++;
	});
	
	// 元の値を配列に取得
	$lr_org_value = $ar_member_list;
	
	// 現在のメンバーについて、元のメンバーに含まれなければグループに追加
	var $l_new_found_flag;
	for($i=0; $i<$lr_now_value.length; $i++){
		$l_new_found_flag = false;
		for($j=0; $j<$lr_org_value.length; $j++){
			if($lr_now_value[$i] == $lr_org_value[$j]){
				$l_new_found_flag = true;
				break;
			}
		}
		if(!$l_new_found_flag){
			//alert($lr_now_value[$i]+"is new");
			$lr_update_param[$lr_now_value[$i]] = $l_trgt_keyvalue_id;
			$l_change_cnt++;
		}
	}
	// 元のメンバーについて、現在のメンバーに含まれなければグループを空に更新
	var $l_old_found_flag;
	for($i=0; $i<$lr_org_value.length; $i++){
		$l_old_found_flag = false;
		for($j=0; $j<$lr_now_value.length; $j++){
			if($lr_org_value[$i] == $lr_now_value[$j]){
				$l_old_found_flag = true;
				break;
			}
		}
		if(!$l_old_found_flag){
			//alert($lr_org_value[$i]+"is deleted");
			$lr_update_param[$lr_org_value[$i]] = "";
			$l_change_cnt++;
		}
	}
	
	if($l_change_cnt > 0){
		// 変更があった場合
		$lr_post_param = $lr_update_param;
		
		// POST用配列にhidden項目を追加
		$obj_hidden_form_detail.find('input[type=hidden]').each(function(){
			$lr_post_param[$(this).attr("name")]	= $(this).val();
		});
		
		/*
		for (var $l_key in $lr_update_param){
			alert($l_key+":"+$lr_update_param[$l_key]);
		}
		*/
		
		// POST処理
		$l_post_result = $.post($groupdetail_post_to, $lr_post_param, function($p_data){
			if($p_data){
				// 表示
				if($p_data == '0'){
					alert("保存しました。");
					
					// 自画面にPOST
					movePage($obj_hidden_form_list, $this_page_file);
					return true;
				}else{
					alert("保存処理でエラーが発生しました。\n" + $p_data);
					return false;
				}
			}else{
				alert("保存処理でエラーが発生しました。\n戻り値が有りません。\nシステム担当者に問い合わせてください。");
				return false;
			}
		});
		
		
	}else{
		// 変更が無い場合
		alert("メンバーに変更はありません。");
	}
}
/*============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_hidden_form_detail = $("#id_form_hidden");
	var $lobj_members_list	= $("#id_select_detail_menbers");
	var $lobj_others_list	= $("#id_select_detail_others");
	
	// リスト内容取得
	gatherListValue();
	
	// 表示中のグループID取得
	$l_trgt_keyvalue_id = $obj_hidden_form_detail.find('input[name="nm_selected_group_id"]').val();
	
	//==============================================
	// 編集ボタン
	//==============================================
	$("#id_btn_detail_editbtn").bind("click", function(){
		// トークンを取得
		$l_token_id = $obj_hidden_form.find('input[name="nm_token_code"]').val();
		// 会社名（検索用）を取得
		$l_company_name = $obj_hidden_form.find('input[name="nm_comp_name_cond"]').val();
		// グループ名（検索用）を取得
		$l_group_name = $obj_hidden_form.find('input[name="nm_group_name_cond"]').val();
		// 表示中のページ数を取得
		$l_show_page = $obj_hidden_form.find('input[name="nm_show_page"]').val();
		// 最大ページ数を取得
		$l_max_page = $obj_hidden_form.find('input[name="nm_max_page"]').val();
		// 有効チェックの有無を取得
		$l_valid_checkstat = $obj_hidden_form.find('input[name="nm_valid_checkstat"]').val();
		// 編集画面を開いているときの親ウインドウのページ名をセット
		$l_parent_pagename = "group";
		// 編集画面表示
		GB_showCenter('グループ管理 - 編集', $page_groups_edit + "?gid=" + $l_trgt_keyvalue_id  + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&gname=" + $l_group_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename, 255, 650);
	});
	
	//==============================================
	// 保存ボタン
	//==============================================
	$("#id_btn_detail_members_save").bind("click", function(){
		saveMembers();
	});
	
	/*==============================================
	  リスト移動
	  ==============================================*/
	if($l_trgt_keyvalue_id != ""){
		// 追加
		$("#id_btn_detail_members_add").bind("click", function(){
			// メンバー移動
			moveItem($lobj_others_list, $lobj_members_list, "c_opt_select_detail_members");
			// カウンタ更新
			$("#id_span_group_member_count").text(getMembersCount());
		});
		
		// 削除
		$("#id_btn_detail_members_remove").bind("click", function(){
			// メンバー移動
			moveItem($lobj_members_list, $lobj_others_list, "c_opt_select_detail_others");
			// カウンタ更新
			$("#id_span_group_member_count").text(getMembersCount());
		});
	}
});
