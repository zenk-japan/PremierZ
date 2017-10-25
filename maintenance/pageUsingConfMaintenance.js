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
 commonMaster画面処理
*******************************************************************************/
var $insupd_post_to			= "c_pageUsingConfSave.php";			// 新規登録、更新処理でPOSTするphpファイル
var $return_post_to			= "pageUsingConf.php";					// 一覧に戻る処理でPOSTするphpファイル
var $reload_post_to			= "pageUsingConfMaintenance.php";		// 再読み込みでPOSTするphpファイル
var $input_item				= "input.c_inp_main_dtl_val,select.c_sel_main_dtl_val"; // 保存値を収集する項目のDOM
var $org_input_item			= "input.c_inp_main_dtl_orgval";	// 元の値を収集する項目のDOM
var $prekey_colname			= "PAGE_USING_CONF_ID";				// 主キーのDB項目名
var $pkey_item_name			= "nm_page_using_conf_id";			// 主キーの隠し項目名
var $token_item_name		= "nm_token_code";					// トークンの隠し項目名(変更不可)

/*==============================================================================
  保存処理
  引数
  				$p_obj_hidden		隠し項目のオブジェクト
  				$p_mode				モード(P:主キーをPOSTする,N:主キーをPOSTしない)
  ============================================================================*/
function procSave($p_obj_hidden, $p_mode){
	var $lr_save_value = {};
	
	// 名前と値を収集
	$($input_item).each(function(){
		// valueの値を収集する
		$lr_save_value[$(this).attr("name")] = $(this).val();
		//alert($(this).attr("name")+":"+$(this).val());
	});

	// define_idを付加
	if($p_mode=="P"){
		$lr_save_value[$prekey_colname] = $("input[name='"+$pkey_item_name+"']").val();
	}
	
	// トークンを付加
	$lr_save_value[$token_item_name] = $("input[name='"+$token_item_name+"']").val();
	
	// 保存処理の起動
	$.post($insupd_post_to, $lr_save_value, callBackFncSave);
}
// コールバック関数
function callBackFncSave($p_data){
	if($p_data){
		alert($p_data);
	}
}

/*==============================================================================
  元に戻す処理
  引数
  ============================================================================*/
function procReset(){
	// org項目を入力項目にコピー
	$($org_input_item).each(function(){
		//alert($(this).val());
		// インデックス番号取得
		$l_org_index = $($org_input_item).index(this);
		//alert($l_org_index);
		//alert($($input_item).eq($l_org_index).attr("name")+"->"+$(this).val());
		$($input_item).eq($l_org_index).val($(this).val());
	});
}

/*==============================================================================
  起動時処理
  ============================================================================*/
jQuery(function($){
	// ボタンクリック時処理
	// 一覧に戻る
	$("#id_btn_return").bind("click", function(){
		// 一覧画面に移動
		movePage($obj_hiddenform, $return_post_to);
	});
	// 保存
	$("#id_btn_save").bind("click", function(){
		// 保存処理起動
		procSave($obj_hiddenform, "P");
	});
	// 元に戻す
	$("#id_btn_reset").bind("click", function(){
		procReset();
	});
	// 新規として保存
	if($("#id_btn_copy")){
		$("#id_btn_copy").bind("click", function(){
			// 保存処理起動
			procSave($obj_hiddenform, "N");
		});
	}
});