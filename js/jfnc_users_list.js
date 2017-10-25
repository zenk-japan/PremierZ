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
 ユーザー画面リスト表示用javascript関数
*******************************************************************************/
var $obj_hidden_form;										// 隠し項目のオブジェクト
var $this_page_file	= "users_mnt.php";
var	$ar_orgcolor = [];										// 各行の背景色
var $list_key_item_name = "nm_selected_user_id";			// リストのキー項目の隠し項目名
var $delete_check = 0;										// 削除のチェックが入っていない数

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procListHover(){
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_list_menu");
	
	// 背景色設定が取得できない場合は終了
	if(!$ar_orgcolor){
		return false;
	}
	
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", $ar_orgcolor[$l_trgt_obj.index(this)]);
		}
	);
}

/*==============================================================================
  ユーザー名・会社名クリック時処理
  ============================================================================*/
function procClickUser($list_td_num){
	
	// 行番号を元にユーザーIDを取得
	$l_trgt_item_id = "id_usrid_list_menu" + $list_td_num;
	$l_trgt_user_id = $("#" + $l_trgt_item_id).val();
	
	// ユーザーIDをhiddn項目に追加
	$obj_hidden_form_list.find("input[name='nm_selected_user_id']").val($l_trgt_user_id);
	
	// ページを更新
	movePage($obj_hidden_form_list, $this_page_file);
}

// コールバック関数
function callBackFncDel($p_data){
	if($p_data){
		//alert($p_data);
		alert("ユーザー削除を実行しました。");
		// 現在のページが一番後ろで表示されているユーザーを全部削除する場合は、ページ番号を更新
		if($obj_hidden_form_list.find("input[name='nm_show_page']").val() == $obj_hidden_form_list.find("input[name='nm_max_page']").val() && $delete_check == 0){
			$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_max_page']").val(), 10) - 1;
			$obj_hidden_form_list.find("input[name='nm_show_page']").val($l_prev_page);
		}
		// ページを更新
		movePage($obj_hidden_form_list, $this_page_file);
	}else{
		alert("No DATA");
	}
}
/*==============================================================================
  リスト部の背景色セット
  処理概要：リスト部の背景色をセットする。現在表示中のユーザーIDの行は強調する
  ============================================================================*/
function backgroundColorSetup(){
	// 行内のユーザーID
	var $l_row_userid = "";
	
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_list_menu");
	
	// 選択中のユーザーID
	var $l_selected_userid = $obj_hidden_form_list.find("input[name='" + $list_key_item_name + "']").val();
	
	if($l_trgt_obj){
		$l_trgt_obj.each(function(){
				// ユーザーIDの取得
				$l_row_userid = $(this).find(".c_td_list_menu_invalid").find("input[type='hidden']").val();
				
				// 選択中のユーザーIDと一致した場合は強調色
				if($l_row_userid == $l_selected_userid){
					$ar_orgcolor[$l_trgt_obj.index(this)] = '#40ff90';
				}else{
					$ar_orgcolor[$l_trgt_obj.index(this)] = 'transparent';
				}
				
				// 背景色設定
				$(this).find("td").css("background-color", $ar_orgcolor[$l_trgt_obj.index(this)]);
			}
		);
	}
}
/*==============================================================================
  有効のみ表示チェックボックスクリック時処理
  処理概要：チェック状態をPOSTして再読み込み
  ============================================================================*/
function procValidOnlyCheckBox($p_checkstat){
	if($p_checkstat){
		// チェックがtrueの場合は、nm_valid_checkstatにYをセット
		$obj_hidden_form_list.find("input[name='nm_valid_checkstat']").val('Y');
	}else{
		// チェックがfalseの場合は、nm_valid_checkstatにNをセット
		$obj_hidden_form_list.find("input[name='nm_valid_checkstat']").val('N');
	}
	// 選択済みIDをクリア
	$obj_hidden_form_list.find("input[name='nm_selected_user_id']").val('');
	
	// ページを1に設定
	$obj_hidden_form_list.find("input[name='nm_show_page']").val('1');
	
	// 再読み込み
	movePage($obj_hidden_form_list, $this_page_file);
}

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$lr_param = {};				// 配列の初期化
	// 隠し項目のFORM
	$obj_hidden_form_list = $("#id_form_hidden");
	
	// 背景色セット
	backgroundColorSetup();
	
	// リストホバー処理
	procListHover();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 前のページ
	$("#id_btn_prev").bind("click", function(){
			// ページ番号を更新してmove
			$l_prev_page = parseInt($obj_hidden_form_list.find("input[name='nm_show_page']").val(), 10) - 1;
			$obj_hidden_form_list.find("input[name='nm_show_page']").val($l_prev_page);
			movePage($obj_hidden_form_list, $this_page_file);
		}
	);
	
	// 次のページ
	$("#id_btn_next").bind("click", function(){
			// ページ番号を更新してmove
			$l_next_page = parseInt($obj_hidden_form_list.find("input[name='nm_show_page']").val(), 10) + 1;
			$obj_hidden_form_list.find("input[name='nm_show_page']").val($l_next_page);
			movePage($obj_hidden_form_list, $this_page_file);
		}
	);
	
	/*-----------------------------
		ユーザー名クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_user").bind("click", function(){
			// クリックされたユーザーの行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_user").index(this), 10) + 1;
			
			procClickUser($l_clicked_num);
		}
	);
	
	/*-----------------------------
		会社名クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_company").bind("click", function(){
			// クリックされたユーザーの行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_company").index(this), 10) + 1;
			
			procClickUser($l_clicked_num);
		}
	);
	
	/*-----------------------------
		グループクリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_group").bind("click", function(){
			// クリックされたユーザーの行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_group").index(this), 10) + 1;
			
			procClickUser($l_clicked_num);
		}
	);
	
	/*-----------------------------
		有効のみ表示クリック時処理
	  -----------------------------*/
	$("#id_ckb_onlyinvalid").bind("click", function(){
			$l_stat_checkbox = $(this).attr("checked");
			procValidOnlyCheckBox($l_stat_checkbox);
		}
	);
});