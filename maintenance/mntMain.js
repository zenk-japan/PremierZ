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
var $obj_hd_right = "";						// ヘッダー部右のオブジェクト
var $obj_dtl_right = "";					// 明細部右のオブジェクト
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
  ポップアップを表示
  処理概要：
  		ポップアップを表示
  引数：
  ============================================================================*/
function showPopup($p_trgt_obj){
	$l_id_div_popup		= "id_div_popup_z"+$p_trgt_obj.attr("id") ;	// ポップアップ用divのid
	$l_popup_pos_left	= 20;										// ポップアップを表示する位置x
	$l_popup_pos_top	= 20;										// ポップアップを表示する位置y
	
	$l_value	= $p_trgt_obj.val();								// 表示元項目の値
	
	$l_html = "<div id=\"" + $l_id_div_popup + "\" style=\"position:relative; top:" + $l_popup_pos_left + "px; left:" + $l_popup_pos_top + "px;\">";
	$l_html = $l_html + "<span style=\"background-color:#a6f7e1; font-size:14px; border: 1px solid #5ebfa5; \">" + $l_value + "</span>";
	$l_html = $l_html + "</div>";
	
	$($p_trgt_obj).after($l_html);
}
function removePopup($p_trgt_obj){
	$l_id_div_popup		= "id_div_popup_z"+$p_trgt_obj.attr("id") ;		// ポップアップ用divのid
	$("#"+$l_id_div_popup).remove();
}

/*==============================================================================
  明細ハイライトのバインド
  処理概要：
  		明細行がある場合、ハイライト処理をバインドする
  引数：
  ============================================================================*/
function bindHighlight(){
	$l_right_exist_flg	= false;				// 右側明細存在フラグ
	
	// 右側の明細が存在する場合は、右側明細存在フラグを立てる
	if($("#id_table_dtl_right")){
		$l_right_exist_flg = true;
	}
	
	// 明細左
	$("tr.c_tr_dtl_left").bind("mouseover", function(){
		// 該当項目のインデックスを取得
		$l_this_index = $("tr.c_tr_dtl_left").index(this);
		
		// ラジオボタンがチェックされていない行のみ処理を行う
		if($l_this_index != $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"))){
			// 該当項目の明細左を強調
			$(this).find("td").css("backgroundColor", "#B8E4F2");
			// 該当項目の明細右を強調
			if($l_right_exist_flg){
				$("tr.c_tr_dtl_right").eq($l_this_index).find("td").css("backgroundColor", "#B8E4F2");
			}
		}
	});
	$("tr.c_tr_dtl_left").bind("mouseout", function(){
		// 該当項目のインデックスを取得
		$l_this_index = $("tr.c_tr_dtl_left").index(this);
		
		// ラジオボタンがチェックされていない行のみ処理を行う
		if($l_this_index != $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"))){
			// 該当項目の明細左を強調解除
			$(this).find("td").css("backgroundColor", "");
			// 該当項目の明細右を強調解除
			if($l_right_exist_flg){
				$("tr.c_tr_dtl_right").eq($l_this_index).find("td").css("backgroundColor", "");
			}
		}
	});
	
	
	// 明細右
	if($l_right_exist_flg){
		$("tr.c_tr_dtl_right").bind("mouseover", function(){
			// 該当項目のインデックスを取得
			$l_this_index = $("tr.c_tr_dtl_right").index(this);
			
			// ラジオボタンがチェックされていない行のみ処理を行う
			if($l_this_index != $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"))){
				// 該当項目の明細右を強調
				$(this).find("td").css("backgroundColor", "#B8E4F2");
				// 該当項目の明細左を強調
				$("tr.c_tr_dtl_left").eq($l_this_index).find("td").css("backgroundColor", "#B8E4F2");
			}
		});
		$("tr.c_tr_dtl_right").bind("mouseout", function(){
			// 該当項目のインデックスを取得
			$l_this_index = $("tr.c_tr_dtl_right").index(this);

			// ラジオボタンがチェックされていない行のみ処理を行う
			if($l_this_index != $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"))){
				// 該当項目の明細右を強調解除
				$(this).find("td").css("backgroundColor", "");
				// 該当項目の明細左を強調解除
				$("tr.c_tr_dtl_left").eq($l_this_index).find("td").css("backgroundColor", "");
			}
		});
	}
}

/*==============================================================================
  テーブルクリック処理のバインド
  処理概要：
  		項目クリック時は該当行のラジオボタンをチェック状態にする
  引数：
  ============================================================================*/
function bindTableClick(){
	$l_right_exist_flg	= false;				// 右側明細存在フラグ
	
	// 右側の明細が存在する場合は、右側明細存在フラグを立てる
	if($("#id_table_dtl_right")){
		$l_right_exist_flg = true;
	}
	
	
	// 明細左
	$("tr.c_tr_dtl_left").bind("click", function(){
		// 一旦全行の協調を解除
		// 明細左
		$("tr.c_tr_dtl_left").find("td").css("backgroundColor", "");
		// 明細右
		if($l_right_exist_flg){
			$("tr.c_tr_dtl_right").find("td").css("backgroundColor", "");
		}
		
		// 該当項目のインデックスを取得
		$l_this_index = $("tr.c_tr_dtl_left").index(this);
		// 該当行のラジオボタンをチェック状態にする
		$("input[name='nm_td_dtl_rdb']").val([$(this).find("input[name='nm_td_dtl_rdb']").val()]);
		
		// 該当項目の明細左を強調
		$(this).find("td").css("backgroundColor", "#33ff70");
		// 該当項目の明細右を強調
		if($l_right_exist_flg){
			$("tr.c_tr_dtl_right").eq($l_this_index).find("td").css("backgroundColor", "#33ff70");
		}
	});
	
	// 明細右
	if($l_right_exist_flg){
		$("tr.c_tr_dtl_right").bind("click", function(){
			// 一旦全行の協調を解除
			// 明細左
			$("tr.c_tr_dtl_left").find("td").css("backgroundColor", "");
			// 明細右
			$("tr.c_tr_dtl_right").find("td").css("backgroundColor", "");
			
			// 該当項目のインデックスを取得
			$l_this_index = $("tr.c_tr_dtl_right").index(this);
			// 該当行のラジオボタンをチェック状態にする
			$("input[name='nm_td_dtl_rdb']").val([$("tr.c_tr_dtl_left").eq($l_this_index).find("input[name='nm_td_dtl_rdb']").val()]);
			
			// 該当項目の明細右を強調
			$(this).find("td").css("backgroundColor", "#33ff70");
			// 該当項目の明細左を強調
			$("tr.c_tr_dtl_left").eq($l_this_index).find("td").css("backgroundColor", "#33ff70");
		});
	}
	
	
}
  
/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){
	// 隠し項目のオブジェクト
	$obj_hiddenform = $("#id_form_hidden");
	// 検索用項目のオブジェクト
	$obj_searchcond = $("td.c_td_search > select, td.c_td_search > input[type='text'], td.c_td_search > input[type='radio']:checked");
	// ヘッダー部右のオブジェクト
	$obj_hd_right = $("#id_div_hd_right");
	// 明細部右のオブジェクト
	$obj_dtl_right = $("#id_div_dtl_right");
	
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
	// ページ操作ボタンがある場合はクリック時処理をバインド
	if("#id_td_po_right_bt"){
		// ページコンボボックス
		$("#id_sel_po_page").bind("change", function(){
			selectPage();
		});
		
		// 前のページ
		$("#id_btn_po_prev").bind("click", function(){
			turnAPage("prev");
		});
		// 次のページ
		$("#id_btn_po_next").bind("click", function(){
			turnAPage("next");
		});
	}
	
	// 新規登録
	if($("#id_btn_insert")){
		$("#id_btn_insert").bind("click", function(){
			// ※関数の本体は各画面用のjsファイル内に記述
			procInsert($obj_hiddenform);
		});
	}
	// 更新
	if($("#id_btn_update")){
		$("#id_btn_update").bind("click", function(){
			// いずれかのラジオボタンが選択されていない場合はエラー
			if($("input[name='nm_td_dtl_rdb']:checked").val()){
				// チェックの入っている行番号を取得
				$l_checked_index = $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"));
				// ※関数の本体は各画面用のjsファイル内に記述
				procUpdate($l_checked_index, $obj_hiddenform);
			}else{
				alert("対象レコードを選択してください");
			}
		});
	}
	// 削除
	if($("#id_btn_delete")){
		$("#id_btn_delete").bind("click", function(){
			if($("input[name='nm_td_dtl_rdb']:checked").val()){
				// チェックの入っている行番号を取得
				$l_checked_index = $("input[name='nm_td_dtl_rdb']").index($("input[name='nm_td_dtl_rdb']:checked"));
				
				$l_retval = confirm("削除すると復元はできません、よろしいですか？");
				if($l_retval){
					// ※関数の本体は各画面用のjsファイル内に記述
					procDelete($l_checked_index);
					
					// 再検索
					// ※関数の本体は各画面用のjsファイル内に記述
					procSearch($obj_hiddenform, 1);
				}
			}else{
				alert("対象レコードを選択してください");
			}
		});
	}
	
	// メニューに戻るボタン
	$("#id_btn_gomenu").bind("click", function(){
		// メニューの起動
		procReturn($obj_hiddenform);
	});
	
	//==============================================
	// ハイライト処理
	//==============================================
	// 明細左がある場合はハイライト処理をバインドする
	if("#id_table_dtl_left"){
		bindHighlight();
	}

	//==============================================
	// テーブルクリック処理
	//==============================================
	if("#id_table_dtl_left"){
		bindTableClick();
	}

	//==============================================
	// 明細スクロール時の処理
	//==============================================
	$obj_dtl_right.scroll(function(){
		//alert(this.type+" got focus.");
		$l_header_pos = $obj_hd_right.scrollLeft();	// ヘッダーのスクロール量
		$l_detail_pos = $obj_dtl_right.scrollLeft();	// 明細のスクロール量
		//$l_mess_value = $l_header_pos + ":" + $l_detail_pos;
		//alert($l_mess_value);
		
		// 明細の位置にヘッダーの位置を合わせる
		$obj_hd_right.scrollLeft($obj_dtl_right.scrollLeft());
		/*
		if($obj_hd_right.scrollLeft() != $obj_dtl_right.scrollLeft()){
			// 縦のスクロールバーがある場合、右端でずれが起こるので、強制的に同期する
			$obj_dtl_right.scrollLeft($obj_hd_right.scrollLeft());
		}
		*/
	});

	//==============================================
	// ヘッダースクロール時の処理
	//==============================================
	$obj_hd_right.scroll(function(){
		// 明細の位置をヘッダーに合わせる
		$l_header_pos = $obj_hd_right.scrollLeft();	// ヘッダーのスクロール量
		$l_detail_pos = $obj_dtl_right.scrollLeft();	// 明細のスクロール量
		//$l_mess_value = $l_header_pos + ":" + $l_detail_pos;
		//alert($l_mess_value);
		$obj_dtl_right.scrollLeft($obj_hd_right.scrollLeft());
	});
});
