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
 ハッシュキー作成用javascript関数
*******************************************************************************/
var $debug_mode		= 0;							// デバッグモード(1:デバッグ)
var $post_to		= "../ctl/c_enquete_key.php";	// POST先のphpファイル


/*==============================================================================
  キー登録処理
  ============================================================================*/
function makeHash($p_this){
	// キーワードがNULLの場合は終了
	if($("#ipkeyword").val()){
		$l_keyword = $("#ipkeyword").val();
		if($l_keyword.length>20){
			alert("キーワードが長すぎます。(現在"+$l_keyword.length+"文字)");
			return false;
		}
	}else{
		alert("キーワードを入力して下さい");
		return false;
	}
	
	// 呼び出し元の項目ID
	var $l_target_id = $p_this.id;
	// 呼び出し元の項目名
	var $l_target_name = $p_this.name;
	// DATA_ID
	var $l_data_id = $("input[name=DATA_ID]").val();
	// LOGIN_USER_ID
	var $l_login_user_id = $("input[name=LOGIN_USER_ID]").val();
	
	// POST処理
	$.post(
		$post_to, {
			 "DATA_ID"					: $l_data_id
			,"KEY_WORD"					: $l_keyword
			,"LOGIN_USER_ID"			: $l_login_user_id
		}, showMess
	);

	return false;
}

/*==============================================================================
  処理結果表示処理
  ============================================================================*/
function showMess(data){
	if( data != '') alert(data);
}

/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(document).ready(function(){
	
	//------------------------
	// 新規登録ボタンクリック
	//------------------------
	$("#btnnewkey").bind("click", function(){
		makeHash(this);
	});
	/*
	$("#btnnewkey").keyup(function(e){
	    if(e.keyCode==32){
	    // spaceが押された場合はキー登録起動
	    	makeHash(this);
	    }
	})
	*/
});
