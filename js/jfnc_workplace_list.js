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
var $this_page_file	= "workplace_mnt.php";
var	$ar_orgcolor = [];										// 各行の背景色
var $list_key_item_name = "nm_selected_workplace_id";		// リストのキー項目の隠し項目名
var $delete_check = 0;										// 削除のチェックが入っていない数

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procListHover(){
	// オブジェクト
	$l_trgt_obj = $(".c_tr_list_menu");
	
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
  作業拠点名・会社名クリック時処理
  ============================================================================*/
function procClickUser($list_td_num){
	
	// 行番号を元に作業場所IDを取得
	$l_trgt_item_id = "id_baseid_list_menu" + $list_td_num;
	$l_trgt_workplace_id = $("#" + $l_trgt_item_id).val();
	
	// 作業場所IDをhiddn項目に追加
	$obj_hidden_form_list.find("input[name='nm_selected_workplace_id']").val($l_trgt_workplace_id);
	
	// ページを更新
	movePage($obj_hidden_form_list, $this_page_file);
}

// コールバック関数
function callBackFncDel($p_data){
	if($p_data){
		//alert($p_data);
		alert("作業場所削除を実行しました。");
		// 現在のページが一番後ろで表示されている作業拠点を全部削除する場合は、ページ番号を更新
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
  処理概要：リスト部の背景色をセットする。現在表示中の拠点IDの行は強調する
  ============================================================================*/
function backgroundColorSetup(){
	// 行内の拠点ID
	var $l_row_placeid = "";
	
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_list_menu");
	
	// 選択中の拠点ID
	var $l_selected_placeid = $obj_hidden_form_list.find("input[name='" + $list_key_item_name + "']").val();
	
	if($l_trgt_obj){
		$l_trgt_obj.each(function(){
				// 拠点IDの取得
				$l_row_placeid = $(this).find(".c_td_list_menu_invalid").find("input[type='hidden']").val();
				
				// 選択中の拠点IDと一致した場合は強調色
				if($l_row_placeid == $l_selected_placeid){
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
	$obj_hidden_form_list.find("input[name='nm_selected_workplace_id']").val('');
	
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
	/*
	// 削除
	$("#id_btn_delete").bind("click", function(){
			$i = 0;
			// チェックが入った行番号を取得
			$(".c_tr_list_menu").each(function(){
				$l_checked_num = parseInt($(".c_tr_list_menu").index(this), 10) + 1;
				$l_trgt_item_id = "id_chk_list_menu" + $l_checked_num;
				if($("#" + $l_trgt_item_id).attr('checked') == true){
					// 行番号を元に作業場所IDを取得
					$l_trgt_workplace_id = $("#id_baseid_list_menu" + $l_checked_num).val();
					// パラメータ設定
					$lr_param["nm_workplace_id" + $i] = $l_trgt_workplace_id;
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
				yn=confirm("削除した作業場所は元に戻すことができませんがよろしいですか？");
				if (yn == true) {
					// パラメータ設定
					$lr_param["nm_token_code"]		= $("input[name='nm_token_code']").val();
					// 削除処理の起動
					$.post("../ctl/c_delete_workplace.php" ,$lr_param ,callBackFncDel);
				}else {
					alert("作業場所削除を中止しました。");
				}
			}
		}
	);
	*/
	/*-----------------------------
		作業場所名クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_workplace").bind("click", function(){
			// クリックされた作業場所の行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_workplace").index(this), 10) + 1;
			
			procClickUser($l_clicked_num);
		}
	);
	
	/*-----------------------------
		会社名クリック時処理
	  -----------------------------*/
	$(".c_td_list_menu_company").bind("click", function(){
			// クリックされた会社の行番号を取得
			$l_clicked_num = parseInt($(".c_td_list_menu_company").index(this), 10) + 1;
			
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