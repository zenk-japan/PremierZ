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
 メールログ画面処理
*******************************************************************************/
var $data_id_item_name			= "nm_data_id";				// DATA_IDの隠し項目名(変更不可)
var $use_page_item_name			= "nm_use_page";			// 使用ページの隠し項目名(変更不可)
var $show_page_item_name		= "nm_show_page";			// 表示ページの隠し項目名(変更不可)
var $send_from_item_name		= "nm_send_from";			// 送信者の隠し項目名(変更不可)
var $send_to_item_name			= "nm_send_to";				// 受信者の隠し項目名(変更不可)
var $date_from_item_name		= "nm_date_from";			// 送信日時Fromの隠し項目名(変更不可)
var $date_to_item_name			= "nm_date_to";				// 送信日時Toの隠し項目名(変更不可)
var $send_purpose_item_name		= "nm_send_purpose";		// 送信目的の隠し項目名(変更不可)
var $search_phrase_item_name	= "nm_search_phrase";		// タイトル/本文検索の隠し項目名(変更不可)
var $pkey_item_name				= "nm_mail_log_id";			// 定義IDの隠し項目名(変更不可)
var $token_item_name			= "nm_token_code";			// トークンの隠し項目名(変更不可)

var $data_id_item_id			= "id_sel_src_dataid";		// DATA_IDの検索項目ID
var $use_page_item_id			= "id_txt_use_page_search";	// 表示ページの検索項目ID
var $from_item_id				= "id_txt_src_from";		// 送信者の検索項目ID
var $to_item_id					= "id_txt_src_to";			// 受信者の検索項目ID
var $date_from_item_id			= "id_txt_src_date_from";	// 送信日時Fromの検索項目ID
var $date_to_item_id			= "id_txt_src_date_to";		// 送信日時Toの検索項目ID
var $purpose_item_id			= "id_sel_src_purpose";		// 送信目的の検索項目ID
var $title_item_id				= "id_txt_src_title";		// タイトル/本文検索の検索項目ID
var $del_post_to				= "c_mailLogDel.php";		// 削除処理でPOSTするphpファイル


var $this_page					= "mailLog.php";				// 自ページのファイル名
var $key_item_colnum			= "8";							// 更新や削除のキーとなる項目の列番号
var $menu_post_to				= "mntMenu.php";				// メニューに戻るでPOSTするphpファイル

/*==============================================================================
  検索処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  				$p_show_page				表示ページ番号
  ============================================================================*/
function procSearch($p_obj_hidden, $p_show_page){
	if(!$p_obj_hidden){
		// 隠し項目のオブジェクトが存在しない場合は終了
		return false;
	}
	
	// 隠し項目にDATA_ID、使用ページ、ページ番号をセットする
	// DATA_ID
	$p_obj_hidden.find("input[name='"+$data_id_item_name+"']").val($("#"+$data_id_item_id).val());
	
	// 使用ページ
	$p_obj_hidden.find("input[name='"+$use_page_item_name+"']").val($("#"+$use_page_item_id).val());
	
	// 送信者
	$p_obj_hidden.find("input[name='"+$send_from_item_name+"']").val($("#"+$from_item_id).val());
	
	// 受信者
	$p_obj_hidden.find("input[name='"+$send_to_item_name+"']").val($("#"+$to_item_id).val());
	
	// 送信日時From
	$p_obj_hidden.find("input[name='"+$date_from_item_name+"']").val($("#"+$date_from_item_id).val());
	
	// 送信日時To
	$p_obj_hidden.find("input[name='"+$date_to_item_name+"']").val($("#"+$date_to_item_id).val());
	
	// 送信目的
	$p_obj_hidden.find("input[name='"+$send_purpose_item_name+"']").val($("#"+$purpose_item_id).val());
	
	// タイトル/本文検索
	$p_obj_hidden.find("input[name='"+$search_phrase_item_name+"']").val($("#"+$title_item_id).val());
	
	// 表示ページ
	$p_obj_hidden.find("input[name='"+$show_page_item_name+"']").val($p_show_page);
	
	// 自画面にPOST
	//function movePage($p_object, $p_move_to)
	//maintenance.js内に記述
	movePage($p_obj_hidden, $this_page);
}

/*==============================================================================
  キー値の取得
  引数
  				$p_checked_index			選択されているレコードの番号(0が先頭)
  ============================================================================*/
function getKeyValue($p_checked_index){
	// 選択されているレコードのkey_item_colnumで指定された列の項目値を返す。
	$l_item_addr = ($p_checked_index + 1) + "" + $key_item_colnum;
	$l_key_value = $("#id_hdn_dtl"+$l_item_addr).val();
	//alert("p_checked_index->"+$p_checked_index+":"+"id_txt_dtl"+$l_item_addr+":"+$l_key_value)
	return $l_key_value;
}

/*==============================================================================
  メニューに戻る処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procReturn($p_obj_hidden){
	//alert("insert");
	// メニューの起動
	movePage($p_obj_hidden, $menu_post_to);
}

/*==============================================================================
  検索結果パージ処理
  引数
  ============================================================================*/
function purgeSelected(){
	$lr_param = {};			// 連想配列の初期化
	
	// 警告表示
	var $purge_yn_res = confirm("検索条件に該当する全てのレコードをパージします。\nよろしいですか？\n※検索設定の結果をパージしますので、現在一覧表示中のものと同じとは限りません。");
	
	if ($purge_yn_res == true){
	
		// パラメータ設定
		$lr_param[$token_item_name]			= $("input[name='"+$token_item_name+"']").val();
		$lr_param[$data_id_item_name]		= $("#"+$data_id_item_id).val();
		$lr_param[$send_from_item_name]		= $("#"+$from_item_id).val();
		$lr_param[$send_to_item_name]		= $("#"+$to_item_id).val();
		$lr_param[$date_from_item_name]		= $("#"+$date_from_item_id).val();
		$lr_param[$date_to_item_name]		= $("#"+$date_to_item_id).val();
		$lr_param[$send_purpose_item_name]	= $("#"+$purpose_item_id).val();
		$lr_param[$search_phrase_item_name]	= $("#"+$title_item_id).val();
		
		
		// 削除処理の起動
		//alert("delete done");
		$.post($del_post_to, $lr_param, callBackFncDel);
	}
	
}
// コールバック関数
function callBackFncDel($p_data){
	if($p_data == 0){
		//alert($p_data);
		alert("パージが完了しました。");
	}else{
		alert("パージできませんでした。");
	}
	procSearch($("#id_form_hidden"), 1)
}

/*==============================================================================
  起動時処理
  ============================================================================*/
jQuery(function($){
	// 詳細ボタンクリック時の処理
	$("input.c_btn_dtl").bind("click", function(){
		// クリックされた項目のインデックスを取得
		$l_this_index = $("input.c_btn_dtl").index(this);
		
		// キー項目を取得
		$l_key_value = getKeyValue($l_this_index);
		
		// POST値の編集
		var $lr_post_value = {};
		$lr_post_value[$pkey_item_name]		= $l_key_value;										// メールログID
		$lr_post_value[$token_item_name]	= $("input[name='"+$token_item_name+"']").val();	// token
		
		// サブ画面を起動
		showSubView($lr_post_value, "mail");
	});
	
	// 検索結果のパージボタンクリック時の処理
	$("#id_btn_erasev").bind("click", function(){
		purgeSelected();
	});
});