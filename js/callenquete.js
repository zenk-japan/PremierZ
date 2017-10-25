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
 アンケート呼び出し用javascript関数
*******************************************************************************/
var $debug_mode			= 0;						// デバッグモード(1:デバッグ)
var $l_data_id_item		= "hd_dataid";				// データIDの項目
var $l_open_page		= "enqueteitems.php";		// 値セットのページ名
var $l_height			= 400;						// 高さ
var $l_width			= 700;						// 幅
var $l_keyword;

/*==============================================================================
  値リスト起動処理
  ============================================================================*/
function showEnqeteItems($p_this){
	// キーワードがNULLの場合は終了
	if($("#ipkeyword").val()){
		$l_keyword = $("#ipkeyword").val();
	}else{
		alert("キーワードを入力して下さい");
		return false;
	}
	
	
// href設定
	// get引数部分
	var $l_href_apart = $l_open_page + "?KeepThis=true";
	
	// 以下に渡す引数を定義
	$l_href_apart = $l_href_apart + "&_DATA_ID=" + $("input[name='DATA_ID']").val();				// データID
	$l_href_apart = $l_href_apart + "&_KEY_WORD=" + $l_keyword;										// キーワード
	$l_href_apart = $l_href_apart + "&_LOGIN_USER_ID=" + $("input[name='LOGIN_USER_ID']").val();	// ログインユーザーID
	
	
	// 表示制御部分
	var $l_href_bpart = "&amp;TB_iframe=true&amp;height=" + $l_height + "&amp;width=" + $l_width + "&amp;modal=true";
	var $l_href = $l_href_apart + $l_href_bpart;
	
// リストオープン
	// thicboxより抜粋
	var t = $p_this.title || $p_this.name || null;
	var a = $l_href || $p_this.alt;
	var g = $p_this.rel || false;
	//alert($p_this.title+":"+$p_this.name+":"+$p_this.href+":"+$p_this.alt+":"+$p_this.rel);
	tb_show(t,a,g);
	$p_this.blur();
	return false;
}


/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(document).ready(function(){
	
	//------------------------
	// 一般の値リスト
	//------------------------
	$("#btnenqst").bind("click", function(){
		showEnqeteItems(this);
	});
	/*
	$("#btnenqst").keyup(function(e){
	    if(e.keyCode==32){
	    // spaceが押された場合はアンケート起動
	    	showEnqeteItems(this);
	    }
	})
	*/

});
