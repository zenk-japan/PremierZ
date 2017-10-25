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
 作業情報画面メニュー用javascript関数
*******************************************************************************/
var $this_page_file			= "work_status.php";						// 当画面のPHPファイル
var	$ar_orgcolor = [];										// 各行の背景色


/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procListHover(){
	// オブジェクト
	$l_trgt_obj = $(".c_tr_list");
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
  リスト部の背景色セット
  処理概要：リスト部の背景色をセットする。現在表示中の会社IDの行は強調する
  ============================================================================*/
function backgroundColorSetup(){
	// 行内のID
	var $l_row_compid = "";
	
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_list");
	
	// 選択中の会社ID
	var $l_selected_compid = $obj_hidden_form.find("input[name='nm_selected_work_content_id']").val();
	if($l_trgt_obj){
		$l_trgt_obj.each(function(){
				// 会社IDの取得
				$l_row_compid = $(this).find(".c_hd_work_content_id").val();
				
				// 選択中の会社IDと一致した場合は強調色
				if($l_row_compid == $l_selected_compid){
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
  検索処理
  処理概要：検索項目を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function procSearchByCond(){
	// 入力された条件値を取得する
	var $l_work_date	= $("#id_txt_search_work_date").val();
	var $l_end_user		= $("#id_txt_search_end_user").val();
	var $l_work_name	= $("#id_txt_search_work_name").val();
	
	// 取得した値をhidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_date']").val($l_work_date);
	$obj_hidden_form.find("input[name='nm_end_user_name']").val($l_end_user);
	$obj_hidden_form.find("input[name='nm_work_name']").val($l_work_name);
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  作業クリック時処理
  引数：
  			$list_td_num				クリックされた行の行番号
  ============================================================================*/
function procClickTask($list_td_num){
	
	// 行番号を元にIDを取得
	$l_trgt_item_id = "id_hd_work_content_id" + $list_td_num;
	$l_trgt_workcontent_id = $("#" + $l_trgt_item_id).val();
	
	// ユーザーIDをhiddn項目に追加
	$obj_hidden_form.find("input[name='nm_selected_work_content_id']").val($l_trgt_workcontent_id);
	
	// ページを更新
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_work_status_common.jsで$obj_hidden_formとして取得
	
	// 背景色セット
	backgroundColorSetup();
	
	// リストホバー処理
	procListHover();
	
	/*-----------------------------
		カレンダー設定
	  -----------------------------*/
	// 作業日
	procCalDate("#id_txt_search_work_date");
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 検索
	$("#id_btn_search").bind("click", function(){
		// 作業日がNULLでの検索はNG
		if ($("#id_txt_search_work_date").val() == ""){
			alert("作業日は入力必須です。");
			return false;
		}
		// 画面再読み込み
		procSearchByCond();
	});
	
	// クリア
	$("#id_btn_cond_clear").bind("click", function(){
		// 値の初期化
		$("#id_txt_search_work_date").val(getTodayDate());
		$("#id_txt_search_end_user").val('');
		$("#id_txt_search_work_name").val('');
		$obj_hidden_form.find("input[name='nm_selected_work_content_id']").val('');
	});
	
	/*-----------------------------
		リストクリック時処理
	  -----------------------------*/
	$(".c_tr_list").bind("click", function(){
			// クリックされたユーザーの行番号を取得
			$l_clicked_num = parseInt($(".c_tr_list").index(this), 10) + 1;
			
			procClickTask($l_clicked_num);
		}
	);
});