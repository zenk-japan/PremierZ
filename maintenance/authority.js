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
  権限一覧画面処理
*******************************************************************************/
var $data_id_item_name		= "nm_data_id";					// DATA_IDの隠し項目名(変更不可)
var $pkey_item_name			= "nm_authority_id";			// 主キーの隠し項目名(権限ID)
var $token_item_name		= "nm_token_code";				// トークンの隠し項目名(変更不可)
var $data_id_item_id		= "id_list_dataid_search";		// DATA_IDの検索項目ID
var $this_page				= "authority.php";				// 自ページのファイル名
var $key_item_colnum		= "2";							// 更新や削除のキーとなる項目の列番号
var $del_post_to			= "c_authorityDel.php";			// 削除処理でPOSTするphpファイル
var $insupd_post_to			= "authorityMaintenance.php";	// 新規登録、更新処理でPOSTするphpファイル
var $menu_post_to			= "mntMenu.php";				// メニューに戻るでPOSTするphpファイル

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
	
	// 隠し項目にDATA_IDをセットする
	// DATA_ID
	$p_obj_hidden.find("input[name='"+$data_id_item_name+"']").val($("#"+$data_id_item_id).val());
	
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
	$l_key_value = $("#id_txt_dtl"+$l_item_addr).val();
	//alert("p_checked_index->"+$p_checked_index+":"+"id_txt_dtl"+$l_item_addr+":"+$l_key_value)
	return $l_key_value;
}

/*==============================================================================
  新規登録処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procInsert($p_obj_hidden){
	//alert("insert");
	// 新規登録画面の起動
	movePage($p_obj_hidden, $insupd_post_to);
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
  更新処理
  引数
  				$p_checked_index			選択されているレコードの番号(0が先頭)
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procUpdate($p_checked_index, $p_obj_hidden){
	//alert("update:"+$p_checked_index);
	// code_idの取得
	$l_key_vlaue	= getKeyValue($p_checked_index);
	$l_hd_html		= "<input type=\"hidden\" id=\"id_ipt_hd91\" name=\""+$pkey_item_name+"\" value=\""+$l_key_vlaue+"\"></input>";

	// 隠し項目にcode_idをセット
	$p_obj_hidden.append($l_hd_html);
	//alert($pkey_item_name+"->"+$("input[name='"+$pkey_item_name+"']").val());
	
	// 更新画面の起動
	movePage($p_obj_hidden, $insupd_post_to);
}

/*==============================================================================
  削除処理
  引数
  				$p_checked_index			選択されているレコードの番号(0が先頭)
  ============================================================================*/
function procDelete($p_checked_index){
	$lr_param = {};			// 連想配列の初期化

	// パラメータ設定
	$lr_param["nm_token_code"]		= $("input[name='nm_token_code']").val();
	$lr_param["nm_authority_id"]	= getKeyValue($p_checked_index);
	
	// 削除処理の起動
	//alert("delete "+$pkey_item_name+" -> "+$lr_param[$pkey_item_name]);
	$.post($del_post_to, $lr_param, callBackFncDel);
	
	alert("AUTHORITY_ID:"+$lr_param_del[$pkey_item_name]+" を削除しました");
}
// コールバック関数
function callBackFncDel($p_data){
	if($p_data){
		//alert($p_data);
	}else{
		alert("No DATA");
	}
}

/*==============================================================================
  起動時処理
  ============================================================================*/
jQuery(function($){
	
});