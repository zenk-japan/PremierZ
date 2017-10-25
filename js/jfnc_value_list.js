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
 リスト表示用javascript関数
*******************************************************************************/
var $page_name;												// ページ名
var $target_item_name;										// 値の戻り項目名
var $id_item_name;											// IDの戻り項目名
var $value_list_btn_class	= "c_btn_call_value_list";		// 呼び出し用ボタンのクラス
var $extlist_post_to				= "../ctl/c_create_valuelist.php";
var $obj_parent_list_hform;									// 隠し項目(元画面)
var $obj_ext_list_hform;									// 隠し項目(リスト画面)
var $obj_ext_list_param;									// 検索項目
var $selected_extlist_col;									// 選択されているリスト行番号
var $selected_extlist_bgcolor	= "#3bff93";				// 選択されているリストの背景色
var $hover_extlist_bgcolor		= "#bafc49";				// カーソルのあるリストの背景色
var $ext_list_fnd_div_html		= '<div id="id_ext_div_value_list_fnd" style="display:none;"></div>';
var $ext_list_trgt_top;										// リストの表示top
var $ext_list_trgt_left;									// リストの表示left
var $obj_ext_list_trgt_item_id;								// リストを起動した項目のID

/*============================================================================
  DIV削除
  ============================================================================*/
function removeExtListDivFnd(){
	// リスト基礎
	if($("#id_ext_div_value_list_fnd")){
		$("#id_ext_div_value_list_fnd").fadeOut("fast", function(){
			$("#id_ext_div_value_list_fnd").remove();
		});
	}
}
function removeExtListDiv(){
	// リスト本体
	if($("#id_ext_div_value_list")){
		$("#id_ext_div_value_list").fadeOut("fast", function(){
			$("#id_ext_div_value_list").remove();
		});
	}
}

/*============================================================================
  POST処理
  引数:
  			$p_mode									モード(1:新規,2:更新)
  			$pr_param								POST変数のパラメータ
  ============================================================================*/
function procExtListPost($p_mode, $pr_param){
	var $l_return_value = "";
	
	/*
	var $l_display = "";
	for ( var key in $pr_param ) {
		$l_display = $l_display + key + " -> " + $pr_param[key] + "\n";
	}
	alert($l_display);
	*/
	
	// POST処理
	$.post($extlist_post_to, $pr_param, function($p_data){
		if($p_data){
			// 表示
			if($p_mode == 1){
				showExtListDiv($p_data);
			}else{
				replaceExtListDiv($p_data);
			}
		}else{
			return false;
		}
	});
	
}
/*============================================================================
  リスト入れ替え処理
  引数:
  			$p_html									リストのHTML
  ============================================================================*/
function replaceExtListDiv($p_html){
	// 既にある場合DIVを削除
	unbindExtListEvent();
	$("#id_ext_div_value_list").remove();
	
	// DIV配置
	$("#id_ext_div_value_list_fnd").after($p_html);
	
	// CSS設定
	$("#id_ext_div_value_list").css("z-index", "3001");
	var $l_cssObj = {
		position: "absolute",
		top: $ext_list_trgt_top,
		left: $ext_list_trgt_left
	}
	$("#id_ext_div_value_list").css($l_cssObj);
	
	// 隠し項目オブジェクトセット
	$obj_ext_list_hform = $("#id_ext_form_hidden");
	
	// 表示
	$("#id_ext_div_value_list").fadeIn("100", function(){
		// ホバー処理バインド
		bindExtListHoverEvent();
		
		// リストクリック処理バインド
		bindExtListClick();
		
		// ボタン処理バインド
		bindExtListButtonEvent();
		
	});
}

/*============================================================================
  リスト表示処理
  引数:
  			$p_html									リストのHTML
  ============================================================================*/
function showExtListDiv($p_html){
	// 既にDIVがある場合は削除
	unbindExtListEvent();
	removeExtListDivFnd();
	removeExtListDiv();
	
	// DIV配置
	$("body").prepend($ext_list_fnd_div_html);
	$("#id_ext_div_value_list_fnd").after($p_html);
	
	// CSS設定
	$("#id_ext_div_value_list_fnd").css("z-index", "3000");
	//$("#id_ext_div_value_list_fnd").css("opacity", 1);
	$("#id_ext_div_value_list").css("z-index", "3001");
	var $l_cssObj = {
		position: "absolute",
		top: $ext_list_trgt_top,
		left: $ext_list_trgt_left
	}
	$("#id_ext_div_value_list_fnd").css($l_cssObj);
	$("#id_ext_div_value_list").css($l_cssObj);
	
	
	// 隠し項目オブジェクトセット
	$obj_ext_list_hform = $("#id_ext_form_hidden");
	
	// 表示
	$("#id_ext_div_value_list").fadeIn("800", function(){
		// ホバー処理バインド
		bindExtListHoverEvent();
		
		// リストクリック処理バインド
		bindExtListClick();
		
		// ボタン処理バインド
		bindExtListButtonEvent();
		
	});
	$("#id_ext_div_value_list_fnd").fadeIn("fast");
}
/*============================================================================
  パラメーター設定処理
  引数:
  			$p_show_page				表示ページ
  ============================================================================*/
function initExtListParam($p_show_page){
	// POST用配列にページ名を追加
	var $l_location  = location.href;
	var $l_file_name = $l_location.substring($l_location.lastIndexOf("/")+1,$l_location.length);
	var $l_page_name = $l_file_name.substring(0,$l_file_name.indexOf("."));
	var $lr_param    = {};
	
	$lr_param["page_name"] = $l_page_name;
	
	// POST用配列に値を返す項目IDを追加
	$lr_param["value_use_item_id"] = $obj_ext_list_trgt_item_id;
	
	// POST用配列に検索項目を追加
	$obj_ext_list_param.each(function(){
		$lr_param[$(this).attr("name")]	= $(this).val();
	});
	
	// ページ番号
	$lr_param["show_page"]	= $p_show_page;
	
	// POST用配列にhidden項目を追加
	$obj_parent_list_hform.find('input[type=hidden]').each(function(){
		$lr_param[$(this).attr("name")]	= $(this).val();
	});
	
	return $lr_param;
}
/*============================================================================
  リストボタンbind処理
  ============================================================================*/
function bindExtListButtonEvent(){
	// 閉じる
	$("#id_ext_btn_lv_exit").bind("click", function(){
		unbindExtListEvent();
		removeExtListDiv();
		removeExtListDivFnd();
	});
	
	// セット
	if($("#id_ext_btn_set_value")){
		$("#id_ext_btn_set_value").bind("click", function(){
			// 選択されている値のindexを取得
			var $l_target_index = getExtListCheckedRDB();
			
			// indexが0の場合はメッセージを出力して終了
			if($l_target_index == 0){
				alert("リストを選択して下さい。");
				return false;
			}
			
			// indexから返却値を取得
			var $l_set_value = $("#id_ext_td_set_item" + $l_target_index).html();
			$l_set_value = $l_set_value.replace(/^\s+|\s+$/g, "");
			//alert("#id_ext_td_set_item" + $l_target_index + " -> " + $l_set_value);
			// hidden項目があれば取得
			var $l_hidden_value = $("#id_ext_hidden_item" + $l_target_index).val();
			if ($l_hidden_value != null && $l_hidden_value != undefined){
				$l_hidden_value = $l_hidden_value.replace(/^\s+|\s+$/g, "");
			}
			
			// セット先の項目を取得
			var $l_set_dest_id = $obj_ext_list_hform.find('input[name="ext_list_value_set_item_id"]').val();
			// IDセット先の項目を取得
			var $l_setid_dest_id = "#"+$obj_ext_list_hform.find('input[name="ext_list_id_set_item_id"]').val();
			
			// 値をセット
			if($l_set_dest_id){
				$("#" + $l_set_dest_id).val($l_set_value);
			}else{
				alert("エラー:セット先の項目が特定できませんでした。");
			}
			// IDセット先の項目がある場合はIDをセットする
			if($l_setid_dest_id != "#" && $l_hidden_value != null && $l_hidden_value != undefined){
				$($l_setid_dest_id).val($l_hidden_value);
			}
			
			// 画面を閉じる
			unbindExtListEvent();
			removeExtListDiv();
			removeExtListDivFnd();
		});
	}
	
	/*----------------------------------------
		ページ移動
	  ----------------------------------------*/
	var $l_max_page_num		= parseInt($obj_ext_list_hform.find('input[name="ext_list_max_page"]').val(), 10);
	var $l_now_page_num		= parseInt($obj_ext_list_hform.find('input[name="ext_list_show_page"]').val(), 10);
	
	// 前へ
	if($("#id_ext_btn_prev")){
		$("#id_ext_btn_prev").bind("click", function(){
			// 現在1ページ目以外の場合、ページ数を減らしてPOST
			if($l_now_page_num > 1){
				var $l_show_page_num = $l_now_page_num - 1;
				
				// パラメータ取得
				var $lr_param_prev = initExtListParam($l_show_page_num);
				
				// リスト表示
				procExtListPost(2, $lr_param_prev);
			}
		});
	}
	
	// 次へ
	if($("#id_ext_btn_next")){
		$("#id_ext_btn_next").bind("click", function(){
			// 現在maxページ以外の場合、ページ数を増やしてPOST
			if($l_now_page_num < $l_max_page_num){
				var $l_show_page_num = $l_now_page_num + 1;
				
				// パラメータ取得
				var $lr_param_next = initExtListParam($l_show_page_num);
				
				// リスト表示
				procExtListPost(2, $lr_param_next);
			}
		});
	}
}
/*============================================================================
  現在チェックされているラジオボタンの行番号取得
  ============================================================================*/
function getExtListCheckedRDB(){
	var $l_return_value = 0;
	
	if($(".c_ext_rdb_list_detail")){
		$l_return_value = parseInt($(".c_ext_rdb_list_detail").index($(".c_ext_rdb_list_detail:checked")), 10) + 1;
	}
	
	return $l_return_value
}
/*============================================================================
  unbind処理
  ============================================================================*/
function unbindExtListEvent(){
	// 閉じるボタン
	if($("#id_ext_btn_lv_exit")){
		$("#id_ext_btn_lv_exit").unbind("click");
	}
	// セットボタン
	if($("#id_ext_btn_set_value")){
		$("#id_ext_btn_set_value").unbind("click");
	}
	// リストhover
	if($(".c_ext_tr_list_detail")){
		$(".c_ext_tr_list_detail").unbind("hover");
	}
}
/*============================================================================
  リストhover処理
  ============================================================================*/
function bindExtListHoverEvent(){
	// オブジェクト
	var $l_trgt_obj = $(".c_ext_tr_list_detail");

	if($l_trgt_obj){
		$l_trgt_obj.hover(
			function(){
			// カーソルホバー時
				// 背景
				$(this).find("td").css("background-color", $hover_extlist_bgcolor);
			},
			function(){
			// カーソルアウト時
				// 選択されている行
				var $l_selected_extlist_num = getExtListCheckedRDB();
				
				// カーソルのある行
				var $l_hover_extlist_num = parseInt($l_trgt_obj.index($(this)), 10) + 1;
				
				// 背景
				if($l_selected_extlist_num == $l_hover_extlist_num){
					$(this).find("td").css("background-color", $selected_extlist_bgcolor);
				}else{
					$(this).find("td").css("background-color", '');
				}
			}
		);
	}
}
/*============================================================================
  リストクリック処理
  ============================================================================*/
function bindExtListClick(){
	var $l_trgt_obj = $(".c_ext_tr_list_detail");
	
	$l_trgt_obj.bind("click", function(){
		// 一旦全ての背景色をクリア
		$l_trgt_obj.find("td").css("background-color", '');
		
		// 選択されている行のラジオボタンをチェック
		//alert($(this).find("input[name='nm_ext_rdb_list_detail']").val());
		$(this).find("input[name='nm_ext_rdb_list_detail']").val([$(this).find("input[name='nm_ext_rdb_list_detail']").val()]);
		
		// 選択されている行の背景色を変更
		$(this).find("td").css("background-color", $selected_extlist_bgcolor);
	});
}


/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_ext_list_param		= $(".c_txt_search_textbox");
	$obj_parent_list_hform	= $("#id_form_hidden");
	$selected_extlist_col	= "";
	
	// リストクリック時処理
	$obj_ext_list_param.bind("dblclick", function(){
		var $l_obj_this = $(this);
		// ボタンが押された項目の位置を検出
		$obj_ext_list_trgt_item_id	= $l_obj_this.attr("id");
		$ext_list_trgt_top			= parseInt($l_obj_this.offset().top, 10) + 20;
		$ext_list_trgt_left			= parseInt($l_obj_this.offset().left, 10) + 20;
		//alert("l_trgt_top -> "+$l_trgt_top+": l_trgt_left -> "+$l_trgt_left);
		
		// パラメータ取得
		var $lr_param = initExtListParam(1);
	
		// POST処理
		var $l_list_div_html = procExtListPost(1, $lr_param);
	});
	
});