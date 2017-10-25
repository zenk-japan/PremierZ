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
 会社画面詳細表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;													// 隠し項目のオブジェクト
var $this_page_file	= "companies_mnt.php";
var $delete_check = 0;													// 削除のチェックが入っていない数
var $list_key_affiliation_item_name = "nm_selected_group_id";			// リストのキー項目の隠し項目名
var $ar_group_orgcolor = [];											// 各行の背景色
var $page_groups_edit = "../../page/groups_edit.php";					// グループ管理編集画面
var $page_users_edit = "../../page/users_edit.php";						// ユーザー管理編集画面
var $page_workbase_edit = "../../page/workplace_edit.php";				// 作業拠点管理編集画面

/*==============================================================================
  リストホバー処理
  ============================================================================*/

function procGroupListHover(){
	// オブジェクト
	$l_trgt_obj_group = $(".c_tr_affiliation_menu, c_tr_affiliation_user_menu, c_tr_affiliation_workbase_menu");
	// 背景色設定が取得できない場合は終了
	if(!$ar_orgcolor){
		return false;
	}
	$l_trgt_obj_group.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", $ar_group_orgcolor[$l_trgt_obj_group.index(this)]);
		}
	);
	// オブジェクト
	$l_trgt_obj_user = $(".c_tr_affiliation_user_menu");
	// 背景色設定が取得できない場合は終了
	if(!$ar_orgcolor){
		return false;
	}
	$l_trgt_obj_user.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", '');
		}
	);
	// オブジェクト
	$l_trgt_obj_place = $(".c_tr_affiliation_workbase_menu");
	// 背景色設定が取得できない場合は終了
	if(!$ar_orgcolor){
		return false;
	}
	$l_trgt_obj_place.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", '');
		}
	);
}

/*==============================================================================
  タブの表示・非表示
  ============================================================================*/
function ShowHideTab(){
	var $l_group_button_id		= "id_btn_affiliation_tab_group";
	var $l_user_button_id		= "id_btn_affiliation_tab_user";
	var $l_workbase_button_id	= "id_btn_affiliation_tab_workbase";
	var $l_change_button_id		= "";
	
	if($obj_hidden_form_list.find("input[name='nm_selected_tb']").val() == "group"){
		// 所属グループリストを表示
		$("#id_div_affiliation_group_table").show();
		// 所属ユーザーリスト・所属作業拠点を非表示
		$("#id_div_affiliation_user_table").hide();
		$("#id_div_affiliation_workbase_table").hide();
		// 変更対象のIDを設定
		$l_change_button_id		= $l_group_button_id;
	}else if($obj_hidden_form_list.find("input[name='nm_selected_tb']").val() == "user"){
		// 所属ユーザーリストを表示
		$("#id_div_affiliation_user_table").show();
		// 所属グループリスト・所属作業拠点を非表示
		$("#id_div_affiliation_group_table").hide();
		$("#id_div_affiliation_workbase_table").hide();
		// 変更対象のIDを設定
		$l_change_button_id		= $l_user_button_id;
	}else {
		// 所属所属作業拠点を表示
		$("#id_div_affiliation_workbase_table").show();
		// 所属グループリスト・所属ユーザーリストを非表示
		$("#id_div_affiliation_group_table").hide();
		$("#id_div_affiliation_user_table").hide();
		// 変更対象のIDを設定
		$l_change_button_id		= $l_workbase_button_id;
	}
	
	// 一旦全てのボタンを元に戻す
	$(".c_btn_affiliation_tab").css("background-color", "");
	
	// 変更対象のカラーを変更
	$("#" + $l_change_button_id).css("background-color", "#799af6");
}
/*==============================================================================
  所属グループの詳細ボタンクリック時処理
  ============================================================================*/
function procClickGroupDetail($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_group_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_group_id"]').remove();}
	
	// 行番号を元に会社IDを取得
	$l_trgt_affiliation_item_id = "id_groupid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_group_id = $("#" + $l_trgt_affiliation_item_id).val();
	
	$l_html = '<input type="hidden" name="nm_selected_group_id" value="'+$l_trgt_group_id+'"/>'
	$obj_hidden_form_list.append($l_html);
	
	//グループ管理画面に移動
	movePage($obj_hidden_form_list,"groups_mnt.php");
}

// コールバック関数
function GroupcallBackFncDel($p_data){
	if($p_data){
		//alert($p_data);
		alert("グループ削除を実行しました。");
		// 現在のページが一番後ろで表示されているグループを全部削除する場合は、ページ番号を更新
		if($obj_hidden_form_list.find("input[name='nm_group_show_page']").val() == $obj_hidden_form_list.find("input[name='nm_group_max_page']").val() && $delete_check == 0){
			$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_group_show_page']").val(), 10) - 1;
			$obj_hidden_form_list.find("input[name='nm_group_show_page']").val($l_prev_page);
		}
		// ページを更新
		movePage($obj_hidden_form_list, $this_page_file);
	}else{
		alert("No DATA");
	}
}

/*==============================================================================
  所属グループの編集ボタンクリック時処理
  ============================================================================*/
function procClickGroupsEdit($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_group_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_group_id"]').remove();}
	
	// 行番号を元にグループIDを取得
	$l_trgt_affiliation_item_id = "id_groupid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_group_id = $("#" + $l_trgt_affiliation_item_id).val();
	// トークンを取得
	$l_token_id = $obj_hidden_form_list.find('input[name="nm_token_code"]').val();
	// 会社名（検索用）を取得
	$l_company_name = $obj_hidden_form_list.find('input[name="nm_comp_name_cond"]').val();
	// 表示中のページ数を取得
	$l_show_page = $obj_hidden_form_list.find('input[name="nm_show_page"]').val();
	// 最大ページ数を取得
	$l_max_page = $obj_hidden_form_list.find('input[name="nm_max_page"]').val();
	// 選択している会社のIDを取得
	$l_selected_company_id = $obj_hidden_form_list.find('input[name="nm_selected_company_id"]').val();
	// 現在表示中のタブ名を取得
	$l_selected_tab = $obj_hidden_form_list.find('input[name="nm_selected_tb"]').val();
	// 表示中のグループタブのページ数を取得
	$l_group_show_page = $obj_hidden_form_list.find('input[name="nm_group_show_page"]').val();
	// 表示中のグループタブの最大ページ数を取得
	$l_group_max_page = $obj_hidden_form_list.find('input[name="nm_group_max_page"]').val();
	// 有効チェックを取得
	$l_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_valid_checkstat"]').val();
	// グループタブの有効チェックを取得
	$l_group_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_group_valid_checkstat"]').val();
	// ユーザータブの有効チェックを取得
	$l_user_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_user_valid_checkstat"]').val();
	// 作業拠点タブの有効チェックを取得
	$l_workbase_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_workbase_valid_checkstat"]').val();
	// 編集画面を開いているときの親ウインドウのページ名をセット
	$l_parent_pagename = "company";
	
	var $groups_edit_pages = "";						// グループ管理編集画面表示用
	
	//グループ編集画面表示
	$groups_edit_pages = $page_groups_edit + "?gid=" + $l_trgt_group_id  + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename + "&tab=" + $l_selected_tab + "&gspage=" + $l_group_show_page + "&gmpage=" + $l_group_max_page + "&vgcheck=" + $l_group_valid_checkstat + "&vucheck=" + $l_user_valid_checkstat + "&vwcheck=" + $l_workbase_valid_checkstat + "&cid" + $l_selected_company_id;
	GB_showCenter('グループ管理 - 編集', $groups_edit_pages, 255, 650);
	//movePage($obj_hidden_form_list,"groups_edit.php?gid=" + $l_trgt_group_id);
}

/*==============================================================================
  所属ユーザーの詳細ボタンクリック時処理
  ============================================================================*/
function procClickUSER($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_user_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_user_id"]').remove();}
	
	// 行番号を元にユーザーIDを取得
	$l_trgt_affiliation_item_id = "id_userid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_user_id = $("#" + $l_trgt_affiliation_item_id).val();
	
	$l_html = '<input type="hidden" name="nm_selected_user_id" value="'+$l_trgt_user_id+'"/>'
	$obj_hidden_form_list.append($l_html);
	
	//ユーザー管理画面に移動
	movePage($obj_hidden_form_list,"users_mnt.php");
}

/*==============================================================================
  所属ユーザーの編集ボタンクリック時処理
  ============================================================================*/
function procClickUsersEdit($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_user_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_user_id"]').remove();}
	
	// 行番号を元にユーザーIDを取得
	$l_trgt_affiliation_item_id = "id_userid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_user_id = $("#" + $l_trgt_affiliation_item_id).val();
	// トークンを取得
	$l_token_id = $obj_hidden_form_list.find('input[name="nm_token_code"]').val();
	// 会社名（検索用）を取得
	$l_company_name = $obj_hidden_form_list.find('input[name="nm_comp_name_cond"]').val();
	// 表示中のページ数を取得
	$l_show_page = $obj_hidden_form_list.find('input[name="nm_show_page"]').val();
	// 最大ページ数を取得
	$l_max_page = $obj_hidden_form_list.find('input[name="nm_max_page"]').val();
	// 選択している会社のIDを取得
	$l_selected_company_id = $obj_hidden_form_list.find('input[name="nm_selected_company_id"]').val();
	// 現在表示中のタブ名を取得
	$l_selected_tab = $obj_hidden_form_list.find('input[name="nm_selected_tb"]').val();
	// 表示中のユーザータブのページ数を取得
	$l_user_show_page = $obj_hidden_form_list.find('input[name="nm_user_show_page"]').val();
	// 表示中のユーザータブの最大ページ数を取得
	$l_user_max_page = $obj_hidden_form_list.find('input[name="nm_user_max_page"]').val();
	// 有効チェックを取得
	$l_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_valid_checkstat"]').val();
	// グループタブの有効チェックを取得
	$l_group_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_group_valid_checkstat"]').val();
	// ユーザータブの有効チェックを取得
	$l_user_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_user_valid_checkstat"]').val();
	// 作業拠点タブの有効チェックを取得
	$l_workbase_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_workbase_valid_checkstat"]').val();
	// 編集画面を開いているときの親ウインドウのページ名をセット
	$l_parent_pagename = "company";
	
	var $users_edit_pages = "";						// ユーザー管理編集画面表示用
	
	//ユーザー編集画面表示
	$users_edit_pages = $page_users_edit + "?uid=" + $l_trgt_user_id + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename + "&tab=" + $l_selected_tab + "&uspage=" + $l_user_show_page + "&umpage=" + $l_user_max_page + "&vgcheck=" + $l_group_valid_checkstat + "&vucheck=" + $l_user_valid_checkstat + "&vwcheck=" + $l_workbase_valid_checkstat + "&cid" + $l_selected_company_id;
	GB_showCenter('ユーザー管理 - 編集', $users_edit_pages, 480, 650);
}

/*==============================================================================
  所属作業拠点の詳細ボタンクリック時処理
  ============================================================================*/
function procClickWORKBASE($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_place_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_workplace_id"]').remove();}
	
	// 行番号を元にユーザーIDを取得
	$l_trgt_affiliation_item_id = "id_workbaseid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_workbase_id = $("#" + $l_trgt_affiliation_item_id).val();
	
	$l_html = '<input type="hidden" name="nm_selected_workplace_id" value="'+$l_trgt_workbase_id+'"/>'
	$obj_hidden_form_list.append($l_html);
	
	//ユーザー管理画面に移動
	movePage($obj_hidden_form_list,"workplace_mnt.php");
}

/*==============================================================================
  所属作業拠点の編集ボタンクリック時処理
  ============================================================================*/
function procClickWorkbaseEdit($list_td_affiliation_num){
	if($obj_hidden_form_list.find('input[name="nm_selected_place_id"]')){$obj_hidden_form_list.find('input[name="nm_selected_workplace_id"]').remove();}
	
	// 行番号を元にユーザーIDを取得
	$l_trgt_affiliation_item_id = "id_workbaseid_affiliation_menu" + $list_td_affiliation_num;
	$l_trgt_workbase_id = $("#" + $l_trgt_affiliation_item_id).val();
	// トークンを取得
	$l_token_id = $obj_hidden_form_list.find('input[name="nm_token_code"]').val();
	// 会社名（検索用）を取得
	$l_company_name = $obj_hidden_form_list.find('input[name="nm_comp_name_cond"]').val();
	// 表示中のページ数を取得
	$l_show_page = $obj_hidden_form_list.find('input[name="nm_show_page"]').val();
	// 最大ページ数を取得
	$l_max_page = $obj_hidden_form_list.find('input[name="nm_max_page"]').val();
	// 選択している会社のIDを取得
	$l_selected_company_id = $obj_hidden_form_list.find('input[name="nm_selected_company_id"]').val();
	// 現在表示中のタブ名を取得
	$l_selected_tab = $obj_hidden_form_list.find('input[name="nm_selected_tb"]').val();
	// 表示中の作業拠点タブのページ数を取得
	$l_workbase_show_page = $obj_hidden_form_list.find('input[name="nm_workbase_show_page"]').val();
	// 表示中の作業拠点タブの最大ページ数を取得
	$l_workbase_max_page = $obj_hidden_form_list.find('input[name="nm_workbase_max_page"]').val();
	// 有効チェックを取得
	$l_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_valid_checkstat"]').val();
	// グループタブの有効チェックを取得
	$l_group_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_group_valid_checkstat"]').val();
	// ユーザータブの有効チェックを取得
	$l_user_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_user_valid_checkstat"]').val();
	// 作業拠点タブの有効チェックを取得
	$l_workbase_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_workbase_valid_checkstat"]').val();
	// 編集画面を開いているときの親ウインドウのページ名をセット
	$l_parent_pagename = "company";
	
	
	var $workbase_edit_pages = "";						// 作業拠点管理編集画面表示用
	
	//ユーザー編集画面表示
	$workbase_edit_pages = $page_workbase_edit + "?bid=" + $l_trgt_workbase_id + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename + "&tab=" + $l_selected_tab + "&bspage=" + $l_workbase_show_page + "&bmpage=" + $l_workbase_max_page + "&vgcheck=" + $l_group_valid_checkstat + "&vucheck=" + $l_user_valid_checkstat + "&vwcheck=" + $l_workbase_valid_checkstat + "&cid" + $l_selected_company_id;
	GB_showCenter('作業拠点管理 - 編集', $workbase_edit_pages, 310, 650);
}


/*==============================================================================
  リスト部の背景色セット
  処理概要：リスト部の背景色をセットする。現在表示中のグループIDの行は強調する
  ============================================================================*/
function GroupbackgroundColorSetup(){
	// 行内のグループID
	var $l_row_groupid = "";
	
	// オブジェクト
	var $l_trgt_group_obj = $(".c_tr_affiliation_menu");
	
	// 選択中のグループID
	var $l_selected_groupid = $obj_hidden_form_list.find("input[name='" + $list_key_affiliation_item_name + "']").val();
	
	if($l_trgt_group_obj){
		$l_trgt_group_obj.each(function(){
			// グループIDの取得
			$l_row_groupid = $(this).find(".c_td_affiliation_menu_check").find("input[type='hidden']").val();
			
			// 選択中のグループIDと一致した場合は強調色
			if($l_row_groupid == $l_selected_groupid){
				$ar_group_orgcolor[$l_trgt_group_obj.index(this)] = '#40ff90';
			}else{
				$ar_group_orgcolor[$l_trgt_group_obj.index(this)] = 'transparent';
			}
			
			// 背景色設定
			$(this).find("td").css("background-color", $ar_group_orgcolor[$l_trgt_group_obj.index(this)]);
		});
	}
}
/*==============================================================================
  有効のみ表示チェックボックスクリック時処理
  処理概要：チェック状態をPOSTして再読み込み
  ============================================================================*/
function procGroupValidOnlyCheckBox($p_group_checkstat){
	if($p_group_checkstat){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form_list.find("input[name='nm_group_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form_list.find("input[name='nm_group_valid_checkstat']").val('N');
	}
	// 選択済みIDをクリア
	$obj_hidden_form_list.find("input[name='nm_selected_group_id']").val('');
	
	// ページを1に設定
	$obj_hidden_form_list.find("input[name='nm_group_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form_list, $this_page_file);
}

function procUserValidOnlyCheckBox($p_user_checkstat){
	if($p_user_checkstat){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form_list.find("input[name='nm_user_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form_list.find("input[name='nm_user_valid_checkstat']").val('N');
	}
	// 選択済みIDをクリア
	$obj_hidden_form_list.find("input[name='nm_selected_user_id']").val('');
	
	// ページを1に設定
	$obj_hidden_form_list.find("input[name='nm_user_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form_list, $this_page_file);
}

function procWorkBaseValidOnlyCheckBox($p_workbase_checkstat){
	if($p_workbase_checkstat){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form_list.find("input[name='nm_workbase_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form_list.find("input[name='nm_workbase_valid_checkstat']").val('N');
	}
	// 選択済みIDをクリア
	$obj_hidden_form_list.find("input[name='nm_selected_workbase_id']").val('');
	
	// ページを1に設定
	$obj_hidden_form_list.find("input[name='nm_workbase_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form_list, $this_page_file);
}

/*==============================================================================
  タブボタンカラー変更処理
  引数:
  		$p_tabname								タブ名(group,user,workbase)
  ============================================================================*/
function changeDetailTabButtonColor($p_tabname){
	var $l_group_button_id		= "id_btn_affiliation_tab_group";
	var $l_user_button_id		= "id_btn_affiliation_tab_user";
	var $l_workbase_button_id	= "id_btn_affiliation_tab_workbase";
	var $l_change_button_id		= "";
	
	// 変更対象のIDを設定
	switch ($p_tabname){
		case "group":
			$l_change_button_id		= $l_group_button_id;
			break;
		case "user":
			$l_change_button_id		= $l_user_button_id;
			break;
		case "workbase":
			$l_change_button_id		= $l_workbase_button_id;
			break;
		default:
			return false;
	}
	
	// 一旦全てのボタンを元に戻す
	$(".c_btn_affiliation_tab").css("background-color", "");
	
	// 変更対象のカラーを変更
	$("#" + $l_change_button_id).css("background-color", "#799af6");
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/

$(function(){
	$lr_param = {};				// 配列の初期化
	// 隠し項目のFORM
	$obj_hidden_form_list = $("#id_form_hidden");
	$selected_tab = "";
	
	// 背景色セット
	GroupbackgroundColorSetup();
	
	// リストホバー処理
	procGroupListHover();
	
	// タブの表示・非表示
	ShowHideTab();
	
	// グループボタンを選択
	//changeDetailTabButtonColor("group");
	
	/*-----------------------------
		タブクリック時処理
	  -----------------------------*/
	$("#id_btn_affiliation_tab_group").bind("click", function(){
	 	// 所属グループリストを表示
		$("#id_div_affiliation_group_table").show();
		// 所属ユーザーリスト・所属作業拠点を非表示
		$("#id_div_affiliation_user_table").hide();
		$("#id_div_affiliation_workbase_table").hide();
		$selected_tab = "group";
		$obj_hidden_form_list.find("input[name='nm_selected_tb']").val($selected_tab);
		changeDetailTabButtonColor($selected_tab);
	});
	
	$("#id_btn_affiliation_tab_user").bind("click", function(){
	 	// 所属ユーザーリストを表示
		$("#id_div_affiliation_user_table").show();
		// 所属グループリスト・所属作業拠点を非表示
		$("#id_div_affiliation_group_table").hide();
		$("#id_div_affiliation_workbase_table").hide();
		$selected_tab = "user";
		$obj_hidden_form_list.find("input[name='nm_selected_tb']").val($selected_tab);
		changeDetailTabButtonColor($selected_tab);
	});
	
	$("#id_btn_affiliation_tab_workbase").bind("click", function(){
	 	// 所属所属作業拠点を表示
		$("#id_div_affiliation_workbase_table").show();
		// 所属グループリスト・所属ユーザーリストを非表示
		$("#id_div_affiliation_group_table").hide();
		$("#id_div_affiliation_user_table").hide();
		$selected_tab = "workbase";
		$obj_hidden_form_list.find("input[name='nm_selected_tb']").val($selected_tab);
		changeDetailTabButtonColor($selected_tab);
	});
	
	/*-----------------------------
		ボタンクリック時処理（グループ）
	  -----------------------------*/
	// グループ新規作成
	$("#id_btn_affiliation_insert").bind("click", function(){
		$sel_corp = $obj_hidden_form_list.find("input[name='nm_selected_company_id']").val();
		
		// トークンを取得
		$l_token_id = $obj_hidden_form_list.find('input[name="nm_token_code"]').val();
		// 会社名（検索用）を取得
		$l_company_name = $obj_hidden_form_list.find('input[name="nm_comp_name_cond"]').val();
		// 表示中のページ数を取得
		$l_show_page = $obj_hidden_form_list.find('input[name="nm_show_page"]').val();
		// 最大ページ数を取得
		$l_max_page = $obj_hidden_form_list.find('input[name="nm_max_page"]').val();
		// 選択している会社のIDを取得
		$l_selected_company_id = $obj_hidden_form_list.find('input[name="nm_selected_company_id"]').val();
		// 現在表示中のタブ名を取得
		$l_selected_tab = $obj_hidden_form_list.find('input[name="nm_selected_tb"]').val();
		// 表示中のグループタブのページ数を取得
		$l_group_show_page = $obj_hidden_form_list.find('input[name="nm_group_show_page"]').val();
		// 表示中のグループタブの最大ページ数を取得
		$l_group_max_page = $obj_hidden_form_list.find('input[name="nm_group_max_page"]').val();
		// 有効チェックを取得
		$l_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_valid_checkstat"]').val();
		// グループタブの有効チェックを取得
		$l_group_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_group_valid_checkstat"]').val();
		// ユーザータブの有効チェックを取得
		$l_user_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_user_valid_checkstat"]').val();
		// 作業拠点タブの有効チェックを取得
		$l_workbase_valid_checkstat = $obj_hidden_form_list.find('input[name="nm_workbase_valid_checkstat"]').val();
		// 編集画面を開いているときの親ウインドウのページ名をセット
		$l_parent_pagename = "company";
		
		
		//グループ新規作成画面表示
		$groups_edit_pages = $page_groups_edit + "?cid=" + $sel_corp + "&gid=new" + "&token=" + $l_token_id + "&cname=" + $l_company_name + "&spage=" + $l_show_page + "&mpage=" + $l_max_page + "&vcheck=" + $l_valid_checkstat + "&ppagename=" + $l_parent_pagename + "&tab=" + $l_selected_tab + "&gspage=" + $l_group_show_page + "&gmpage=" + $l_group_max_page + "&vgcheck=" + $l_group_valid_checkstat + "&vucheck=" + $l_user_valid_checkstat + "&vwcheck=" + $l_workbase_valid_checkstat;
		GB_showCenter('グループ管理 - 新規作成', $groups_edit_pages, 255, 650);
	});
	
	// 前のページ
	$("#id_btn_affiliation_group_prev").bind("click", function(){
		// ページ番号を更新してmove
		$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_group_show_page']").val(), 10) - 1;
		$obj_hidden_form_list.find("input[name='nm_group_show_page']").val($l_prev_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// 次のページ
	$("#id_btn_affiliation_group_next").bind("click", function(){
		// ページ番号を更新してmove
		$l_next_page = parseInt($obj_hidden_form_list.find("input[name='nm_group_show_page']").val(), 10) + 1;
		$obj_hidden_form_list.find("input[name='nm_group_show_page']").val($l_next_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// グループ詳細
	$(".c_btn_affiliation_group_detail").bind("click", function(){
		// クリックされたグループの行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_group_detail").index(this), 10) + 1;
		procClickGroupDetail($l_clicked_affiliation_num);
	});
	
	// グループ編集
	$(".c_btn_affiliation_group_update").bind("click", function(){
		// クリックされたグループの行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_group_update").index(this), 10) + 1;
		procClickGroupsEdit($l_clicked_affiliation_num);
	});
	
	/*
	// 削除
	$("#id_btn_affiliation_delete").bind("click", function(){
			$i = 0;
			// チェックが入った行番号を取得
			$(".c_tr_affiliation_menu").each(function(){
				$l_checked_num = parseInt($(".c_tr_affiliation_menu").index(this), 10) + 1;
				$l_trgt_item_id = "id_chk_affiliation_menu" + $l_checked_num;
				if($("#" + $l_trgt_item_id).attr('checked') == true){
					// 行番号を元にIDをグループIDを取得
					$l_trgt_group_id = $("#id_groupid_affiliation_menu" + $l_checked_num).val();
					// パラメータ設定
					$lr_param["nm_group_id" + $i] = $l_trgt_group_id;
					$i++;
				} else{
					$delete_check++;
				}
			}
			);
			// 削除対象が一つもない場合はメッセージを表示して削除を中止する。
			if($i==0){
				alert("「削除」のチェックボックスにチェックを入れてください");
			}else {
				// 確認メッセージの表示
				yn=confirm("削除したグループは元に戻すことができませんがよろしいですか？");
				if (yn == true) {
					// パラメータ設定
					$lr_param["nm_token_code"]		= $("input[name='nm_token_code']").val();
					// 削除処理の起動
					$.post("../ctl/c_delete_group.php" ,$lr_param ,GroupcallBackFncDel);
				}else {
					alert("グループ削除を中止しました。");
				}
			}
		}
	);
	*/
	/*-----------------------------
		ボタンクリック時処理（ユーザー）
	  -----------------------------*/
	// ユーザー新規作成
	$("#id_btn_affiliation_user_insert").bind("click", function(){
		$sel_corp = $obj_hidden_form_list.find("input[name='nm_selected_company_id']").val();
		
		if ($obj_hidden_form_list.find("input[name='nm_selected_group_id']").val() != "") {
			$sel_group = $obj_hidden_form_list.find("input[name='nm_selected_group_id']").val();
		} else {
			$sel_group = "new";
		}
		
		//ユーザー新規作成画面表示
		$users_edit_pages = $page_users_edit + "?cid=" + $sel_corp +"&gid=" + $sel_group + "&uid=new";
		GB_showCenter('ユーザー管理 - 新規作成', $users_edit_pages, 480, 650);
	});
	
	// 前のページ
	$("#id_btn_affiliation_user_prev").bind("click", function(){
		// ページ番号を更新してmove
		$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_user_show_page']").val(), 10) - 1;
		$obj_hidden_form_list.find("input[name='nm_user_show_page']").val($l_prev_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// 次のページ
	$("#id_btn_affiliation_user_next").bind("click", function(){
		// ページ番号を更新してmove
		$l_next_page = parseInt($obj_hidden_form_list.find("input[name='nm_user_show_page']").val(), 10) + 1;
		$obj_hidden_form_list.find("input[name='nm_user_show_page']").val($l_next_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// ユーザー詳細
	$(".c_btn_affiliation_user_detail").bind("click", function(){
		// クリックされたユーザーの行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_user_detail").index(this), 10) + 1;
		procClickUSER($l_clicked_affiliation_num);
	});
	
	// ユーザー編集
	$(".c_btn_affiliation_user_update").bind("click", function(){
		// クリックされたユーザーの行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_user_update").index(this), 10) + 1;
		procClickUsersEdit($l_clicked_affiliation_num);
	});
	
	/*-----------------------------
		ボタンクリック時処理（作業拠点）
	  -----------------------------*/
	// 作業拠点新規作成
	$("#id_btn_affiliation_workbase_insert").bind("click", function(){
		$sel_corp = $obj_hidden_form_list.find("input[name='nm_selected_company_id']").val();
		
		//作業拠点新規作成画面表示
		$workbase_edit_pages = $page_workbase_edit + "?cid=" + $sel_corp +"&bid=new"
		GB_showCenter('作業拠点管理 - 新規作成', $workbase_edit_pages, 310, 650);
	});
	
	// 前のページ
	$("#id_btn_affiliation_workbase_prev").bind("click", function(){
		// ページ番号を更新してmove
		$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_workbase_show_page']").val(), 10) - 1;
		$obj_hidden_form_list.find("input[name='nm_workbase_show_page']").val($l_prev_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// 次のページ
	$("#id_btn_affiliation_workbase_next").bind("click", function(){
		// ページ番号を更新してmove
		$l_next_page = parseInt($obj_hidden_form_list.find("input[name='nm_workbase_show_page']").val(), 10) + 1;
		$obj_hidden_form_list.find("input[name='nm_workbase_show_page']").val($l_next_page);
		movePage($obj_hidden_form_list, $this_page_file);
	});
	
	// 作業拠点詳細
	$(".c_btn_affiliation_workbase_detail").bind("click", function(){
		// クリックされた作業拠点の行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_workbase_detail").index(this), 10) + 1;
		procClickWORKBASE($l_clicked_affiliation_num);
	});
	
	// 作業拠点編集
	$(".c_btn_affiliation_workbase_update").bind("click", function(){
		// クリックされた作業拠点の行番号を取得
		$l_clicked_affiliation_num = parseInt($(".c_btn_affiliation_workbase_update").index(this), 10) + 1;
		procClickWorkbaseEdit($l_clicked_affiliation_num);
	});
	
	/*-----------------------------
		グループコードクリック時処理（グループ）
	  -----------------------------*/
	/*
	$(".c_td_affiliation_menu_group_code").bind("click", function(){
			// クリックされたグループの行番号を取得
			$l_clicked_affiliation_num = parseInt($(".c_td_affiliation_menu_group_code").index(this), 10) + 1;
			
			procClickGroup($l_clicked_affiliation_num);
		}
	);
	*/
	/*-----------------------------
		グループ名クリック時処理（グループ）
	  -----------------------------*/
	/*
	$(".c_td_affiliation_menu_group_name").bind("click", function(){
			// クリックされたグループの行番号を取得
			$l_clicked_affiliation_num = parseInt($(".c_td_affiliation_menu_group_name").index(this), 10) + 1;
			
			procClickGroup($l_clicked_affiliation_num);
		}
	);
	*/
	/*------------------------------------
		分類区分クリック時処理（グループ）
	  ------------------------------------*/
	/*
	$(".c_td_affiliation_menu_classification_division_name").bind("click", function(){
			// クリックされたグループの行番号を取得
			$l_clicked_affiliation_num = parseInt($(".c_td_affiliation_menu_classification_division_name").index(this), 10) + 1;
			
			procClickGroup($l_clicked_affiliation_num);
		}
	);
	*/
	/*--------------------------------
		備考クリック時処理（グループ）
	  --------------------------------*/
	/*
	$(".c_td_affiliation_menu_remark").bind("click", function(){
			// クリックされたグループの行番号を取得
			$l_clicked_affiliation_num = parseInt($(".c_td_affiliation_menu_remark").index(this), 10) + 1;
			
			procClickGroup($l_clicked_affiliation_num);
		}
	);
	*/
	
	/*---------------------------------------
		有効のみ表示クリック時処理(グループ)
	  ---------------------------------------*/
	$("#id_ckb_affiliation_onlyinvalid").bind("click", function(){
		$l_stat_group_checkbox = $(this).attr("checked");
		procGroupValidOnlyCheckBox($l_stat_group_checkbox);
	});
	
	/*---------------------------------------
		有効のみ表示クリック時処理(ユーザー)
	  ---------------------------------------*/
	$("#id_ckb_affiliation_user_onlyinvalid").bind("click", function(){
		$l_stat_user_checkbox = $(this).attr("checked");
		procUserValidOnlyCheckBox($l_stat_user_checkbox);
	});
	
	/*---------------------------------------
		有効のみ表示クリック時処理(作業拠点)
	  ---------------------------------------*/
	$("#id_ckb_affiliation_workbase_onlyinvalid").bind("click", function(){
		$l_stat_workbase_checkbox = $(this).attr("checked");
		procWorkBaseValidOnlyCheckBox($l_stat_workbase_checkbox);
	});
});