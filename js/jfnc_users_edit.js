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
 ユーザー編集画面表示用javascript関数
*******************************************************************************/
var $obj_main_form;											// POST対象オブジェクト
var $this_page_file		= "";
var $edit_page			= "../ctl/c_editUsers.php";			// ユーザー
var $lr_param			= {};								// 連想配列の初期化
var $l_sqltype;												// 新規更新区分
var $l_sqltype_insert	= "insert";
var $l_sqltype_update	= "update";
var $pchk_post_to		= "../ctl/c_loginCheck.php";		// パスワードのチェック用モジュール
var $change_passwd_flag	= 0;								// パスワード変更フラグ（0:しない、1:する）

/*==============================================================================
  パスワードチェック処理
  処理概要：新規登録時 -> 2か所のパスワードが合っているかチェック
            更新時 -> 古いパスワードが有っているかチェック
  ============================================================================*/
function checkPasswd(){
	var $l_passwd_1 = $("#id_txt_edit_password").val();
	var $l_passwd_2 = $("#id_txt_edit_password_sub").val();
	
	var $l_retcode = true;
	
	if ($l_sqltype == $l_sqltype_insert) {
	// sql_typeがinsertの場合は、上下の値が有っている事をチェック
		// 双方の長さが0の場合はエラー
		if ($l_passwd_1.length < 1 || $l_passwd_2.length < 1) {
			alert("パスワード無しは設定できません。");
			return false;
		}
		
		//alert($l_sqltype_insert);
		if($l_passwd_1 != $l_passwd_2){
			alert("パスワードが一致していません。");
			return false;
		}
	}else if ($l_sqltype == $l_sqltype_update) {
		if ($change_passwd_flag == 1){
		// パスワードチェックをする場合
		// sql_typeがupdateの場合は、上の値とユーザーIDが正しいかチェック
			// 新パスワードの長さが0の場合はエラー
			if ($l_passwd_2.length < 1) {
				alert("パスワード無しは設定できません。");
				return false;
			}
			
			//alert($l_sqltype_update);
			// 連想配列の初期化
			var $lr_param	= {};
			
			// パラメータセット
			$lr_param["nm_token_code"]	= $obj_main_form.find('input[name="nm_token_code"]').val();
			$lr_param["nm_userid"]		= $obj_main_form.find('input[name="USER_ID"]').val();
			$lr_param["nm_password"]	= $l_passwd_1;
			$lr_param["only_check"]		= '1';
			
			// POST処理
			$.post($pchk_post_to, $lr_param, function($p_data){
					//alert($p_data);
					if ($p_data == "OK") {
						$l_retcode = true;
						// .postを使う場合はここに次の処理を書かないとpost処理中にreturnまで進んでしまう為、
						// ここに保存処理を記述する
						$parent_pagename = $obj_main_form.find('input[name="nm_parent_pagename"]').val();
						postPage($obj_main_form, $edit_page);
					}else{
						alert("現在のパスワードが間違っています。");
						$l_retcode = false;
					}
				}
			);
		}else{
		// パスワードチェックをしない場合
			postPage($obj_main_form, $edit_page);
		}
	}
	return true;
}
/*==============================================================================
  パスワード変更チェック変更時処理
  処理概要：
		パスワード変更チェックボックスがチェック状態ならテキストボックスを
		使用可能にし、パスワード変更フラグを立てる。
		チェック状態でなければ、テキストボックスを使用不可にし、
		パスワード変更フラグを降ろす。
  引数：
		$p_ckbvalue				チェックボックスの値（true|false）
  ============================================================================*/
function procChangePChk($p_ckbvalue){
	var $passwd_txtbox = $("#id_txt_edit_password, #id_txt_edit_password_sub");
	var $passwd_hidden_param = $("#id_ipt_hd_edit_password");
	
	if ($p_ckbvalue){
		$change_passwd_flag = 1;
		$passwd_txtbox.attr('disabled','');
		$passwd_txtbox.attr('title','<<必須項目>>');
		$passwd_txtbox.css("background-color", "transparent");
	}else{
		$change_passwd_flag = 0;
		$passwd_txtbox.attr('disabled','disabled');
		$passwd_txtbox.attr('title','');
		$passwd_txtbox.css("background-color", "#AAAAAA");
	}
	$passwd_hidden_param.val($change_passwd_flag);
}

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	// カレンダー処理
	procCalDate("#id_txt_edit_birthdate");
	
	// 新規更新区分
	$l_sqltype = $obj_main_form.find('input[name="sql_type"]').val();
	
	// パスワード変更について、新規の場合はチェックボックスを消してテキストを使用可、
	// 更新の場合は、チェックボックスを表示してテキストを使用不可にする。
	if ($l_sqltype == $l_sqltype_insert) {
		$("#id_ckb_edit_password").hide(0);
		$("#id_span_edit_password_cap").text('');
	}else{
		procChangePChk(false);
		$("#id_span_edit_password_cap").text('パスワードを変更する');
		$("#id_ckb_edit_password").bind("change", function(){
			procChangePChk($(this).attr("checked"));
		});
	}

	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 作成
	$("#id_btn_create").bind("click", function(){
		// パスワードチェック
		if(!checkPasswd()){
			return false;
		}
		// 作成
		postPage($obj_main_form, $edit_page);
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		// パスワードチェック
		if(!checkPasswd()){
			return false;
		}
	});
	
	// 会社名値クリア
	$("#id_txt_clear_company_name").bind("click", function(){
		$("#id_txt_edit_company_name").val('');
		$("#id_txt_edit_group_name").val('');
	});
	
	// グループ名値クリア
	$("#id_txt_clear_group_name").bind("click", function(){
		$("#id_txt_edit_group_name").val('');
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
	
	//$("#id_btn_cancel").bind("click", function(){
	//	$("#datepicker").datepicker({showOn: 'button', buttonImage: 'images/calendar.gif', buttonImageOnly: true});
	//});
	
	//// 生年月日
	//$("#id_img_cal").bind("click", function(){
	//});
});

/*==============================================================================
  ページPOST
  処理概要：
  		コールバック関数
  引数：
		$p_data				
  ============================================================================*/
function callBackFnc($p_data){
	
	if($p_data == "insert nomal"){
		// 正常終了
		alert("ユーザー管理情報を登録しました。");
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	//	// ページを更新
	//	movePage($obj_hidden_form_list, $this_page_file);
	}else if($p_data == "update nomal"){
		// 正常終了
		alert("ユーザー管理情報を更新しました。");
		
		// 親ウインドウを判別しファイル名をセット
		if($parent_pagename == "user"){
			$this_page_file = "users_mnt.php";
		}else if($parent_pagename == "company"){
			$this_page_file = "companies_mnt.php";
		}
		// ページを更新
		parent.parent.movePage($obj_main_form, $this_page_file);
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}

