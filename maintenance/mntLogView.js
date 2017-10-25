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
 データ明細画面共通処理
*******************************************************************************/
var $debug_mode = 0;						// デバッグモード
var $message = "";							// メッセージ
var $obj_mainform = "";						// メインフォーム
var $obj_hiddenform = "";					// 隠し項目
var $ar_default;

/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
//function movePage($p_object, $p_move_to)
//maintenance.js内に記述

/*==============================================================================
  初期値取得
  処理概要：
  		連想配列に項目IDと値をセット
  引数：
		$p_obj				初期値を取得するオブジェクト
  ============================================================================*/
function getDefault($p_obj){
	$ar_default = {};
	if($p_obj){
		$p_obj.each(function(){
			$ar_default[$(this).attr('id')] = $(this).val();
			//$(this).css("border", "3px solid rgb(255,0,0)");
		});
	}
}
/*==============================================================================
  検索条件初期化
  処理概要：
  		連想配列から初期値を取り出しセットする
  引数：
  ============================================================================*/
function setDefault(){
	for (var $l_id in $ar_default) {
	  $("#"+$l_id).val($ar_default[$l_id]);
	}
}

/*==============================================================================
  検索条件クリア
  処理概要：
  		連想配列から検索項目を取り出し、クリアする
  引数：
  ============================================================================*/
function clearCond(){
	for (var $l_id in $ar_default) {
	  //$("#"+$l_id).filter("input[type='text']").val('');
	  $("#"+$l_id).val('');
	}
}

/*==============================================================================
  ページをめくる
  処理概要：
  		指定されたモードにしたがってページ数を1増減させ自画面にPOSTする
  引数：
  		$p_mode							モード(prev:戻る,next:進む)
  ============================================================================*/
function turnAPage($p_mode){
	$l_now_page = parseInt($obj_hiddenform.find("input[name='nm_show_page']").val(), 10);
	$l_max_page = parseInt($obj_hiddenform.find("input[name='nm_max_page']").val(), 10);
	
	if($p_mode == "prev"){
		// 1ページ戻す場合
		if($l_now_page == 1){
			alert("最初のページです");
			return true;
		}else{
			// ※関数の本体は各画面用のjsファイル内に記述
			procSearch($obj_hiddenform, $l_now_page - 1);
		}
	}else{
		// 1ページ進める場合
		if($l_now_page == $l_max_page){
			alert("最後のページです");
			return true;
		}else{
			// ※関数の本体は各画面用のjsファイル内に記述
			procSearch($obj_hiddenform, $l_now_page + 1);
		}
	}
}

/*==============================================================================
  ページを選択する
  処理概要：
  		選択されたページで自画面にPOSTする
  引数：
  ============================================================================*/
function selectPage(){
	$l_selected_page = parseInt($("#id_sel_po_page").val(), 10);
	//alert ($l_selected_page);
	// ※関数の本体は各画面用のjsファイル内に記述
	procSearch($obj_hiddenform, $l_selected_page);
}

/*==============================================================================
  明細ハイライトのバインド
  処理概要：
  		明細行がある場合、ハイライト処理をバインドする
  引数：
  ============================================================================*/
function bindHighlight(){
	$("tr.c_tr_dtl").bind("mouseover", function(){
		// 該当項目のインデックスを取得
		$l_this_index = $("tr.c_tr_dtl").index(this);
		
		// 該当項目の明細左を強調
		$(this).find("td").css("backgroundColor", "#B8E4F2");
	});
	$("tr.c_tr_dtl").bind("mouseout", function(){
		// 該当項目のインデックスを取得
		$l_this_index = $("tr.c_tr_dtl").index(this);
		
		// 該当項目の明細左を強調解除
		$(this).find("td").css("backgroundColor", "");
	});
	
}
  
/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){
	// 隠し項目のオブジェクト
	$obj_hiddenform = $("#id_form_hidden");
	// 検索用項目のオブジェクト
	$obj_searchcond = $("input.c_text_search,select.c_sel_search");
	
	//==============================================
	// 初期値の取得
	//==============================================
	getDefault($obj_searchcond);
	
	//==============================================
	// 各ボタンクリック時処理
	//==============================================
	// 検索
	$("#id_btn_search").bind("click", function(){
		// ※関数の本体は各画面用のjsファイル内に記述
		procSearch($obj_hiddenform, 1);
	});
	// 元に戻す
	$("#id_btn_default").bind("click", function(){
		setDefault();
	});
	// クリア
	$("#id_btn_clear").bind("click", function(){
		clearCond();
	});
	
	// メニューに戻るボタン
	$("#id_btn_gomenu").bind("click", function(){
		// メニューの起動
		procReturn($obj_hiddenform);
	});
	
	
	// ページ操作ボタンがある場合はクリック時処理をバインド
	if("#id_btn_po_prev"){
		// ページコンボボックス
		$("#id_sel_po_page").bind("change", function(){
			selectPage();
		});
		
		// 前のページ
		$("#id_btn_po_prev").bind("click", function(){
			turnAPage("prev");
		});
	}
	if("#id_btn_po_next"){
		// 次のページ
		$("#id_btn_po_next").bind("click", function(){
			turnAPage("next");
		});
	}
	
	//==============================================
	// ハイライト処理
	//==============================================
	// 明細左がある場合はハイライト処理をバインドする
	if("#id_table_dtl"){
		bindHighlight();
	}

});
