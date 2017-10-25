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
 ユーザー設定画面表示用javascript関数
*******************************************************************************/
var $obj_main_form;											// POST対象オブジェクト
var $obj_hidden_form;										// 隠し項目のオブジェクト
var $this_page_file		= "";
var $edit_page			= "../ctl/c_editUsers.php";			// ユーザー
var $pchk_post_to		= "../ctl/c_loginCheck.php";		// パスワードのチェック用モジュール
var $lr_param			= {};								// 連想配列の初期化
var $lr_old_value		= {};								// 既存値配列の初期化
var $l_sqltype;												// 新規更新区分
var $l_sqltype_insert	= "insert";
var $l_sqltype_update	= "update";
var $change_passwd_flag	= 0;								// パスワード変更フラグ（0:しない、1:する）

/*==============================================================================
  保存処理
  処理概要：
  		古いパスワードが合っているかチェックし、合っていれば保存処理へPOSTする
  ============================================================================*/
function procSave(){
	var $l_passwd_1 = $("#id_txt_use_password_old").val();
	var $l_passwd_2 = $("#id_txt_use_password").val();
	
	var $l_retcode = true;
	
	if ($change_passwd_flag == 1){
	// パスワードチェックをする場合
	// 上の値とユーザーIDが正しいかチェック
		// 新パスワードの長さが0の場合はエラー
		if ($l_passwd_2.length < 1) {
			alert("パスワード無しは設定できません。");
			return false;
		}
	
		// 連想配列の初期化
		var $lr_param	= {};
		
		// パラメータセット
		$lr_param["nm_userid"]		= $("#id_hd_use_user_id").val();
		$lr_param["nm_password"]	= $l_passwd_1;
		$lr_param["only_check"]		= '1';
		// POST処理
		$.post($pchk_post_to, $lr_param, function($p_data){
				//alert($p_data);
				if ($p_data == "OK") {
					$l_retcode = true;
					// .postを使う場合はここに次の処理を書かないとpost処理中にreturnまで進んでしまう為、
					// ここに保存処理を記述する
					//alert("パスワードチェックOK");
					procUserDataPost();
				}else{
					alert("現在のパスワードが間違っています。");
					$l_retcode = false;
				}
			}
		);
	}else{
		// パスワード変更がない場合はPOST処理に進む
		procUserDataPost();
	}
	
	
	return true;
}
/*==============================================================================
  ページPOST
  処理概要：
  		ユーザー編集用モジュールに入力データをPOSTする
  引数：
		$p_data				
  ============================================================================*/
function procUserDataPost(){
	// 連想配列の初期化
	var $lr_param	= {};
	
	// パラメータセット
	$lr_param["nm_token_code"]		= $obj_hidden_form.find("input[name='nm_token_code']").val();
	$lr_param["USER_ID"]			= $("#id_hd_use_user_id").val();
	$lr_param["PASSWORD"]			= $("#id_txt_use_password").val();
	$lr_param["hd_edit_password"]	= $("#id_hd_use_change_password").val();
	$lr_param["ZIP_CODE"]			= $("#id_txt_use_zip_code").val();
	$lr_param["ADDRESS"]			= $("#id_txt_use_address").val();
	$lr_param["CLOSEST_STATION"]	= $("#id_txt_use_closest_station").val();
	$lr_param["HOME_PHONE"]			= $("#id_txt_use_home_phone").val();
	$lr_param["HOME_MAIL"]			= $("#id_txt_use_home_mail").val();
	$lr_param["MOBILE_PHONE"]		= $("#id_txt_use_mobile_phone").val();
	$lr_param["MOBILE_PHONE_MAIL"]	= $("#id_txt_use_mobile_phone_mail").val();
	$lr_param["sql_type"]			= "update";
	
	$.post($edit_page, $lr_param, function($p_data){
	
		if($p_data == "update nomal"){
			// 正常終了
			alert("ユーザー情報を更新しました。");
		}else{
			// 異常終了
			alert($p_data);
		}
	});
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
	var $passwd_txtbox = $("#id_txt_use_password_old, #id_txt_use_password");
	var $passwd_hidden_param = $("#id_hd_use_change_password");
	
	if ($p_ckbvalue){
		$change_passwd_flag = 1;
		$passwd_txtbox.attr('disabled','');
		//$passwd_txtbox.css("background-color", "transparent");
	}else{
		$change_passwd_flag = 0;
		$passwd_txtbox.attr('disabled','disabled');
		//$passwd_txtbox.css("background-color", "#cccccc");
	}
	$passwd_hidden_param.val($change_passwd_flag);
}

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	$obj_hidden_form = $("#id_form_hidden");
	
	// 初期値を保管
	$lr_old_value["ZIP_CODE"]			= $("#id_txt_use_zip_code").val();
	$lr_old_value["ADDRESS"]			= $("#id_txt_use_address").val();
	$lr_old_value["CLOSEST_STATION"]	= $("#id_txt_use_closest_station").val();
	$lr_old_value["HOME_PHONE"]			= $("#id_txt_use_home_phone").val();
	$lr_old_value["HOME_MAIL"]			= $("#id_txt_use_home_mail").val();
	$lr_old_value["MOBILE_PHONE"]		= $("#id_txt_use_mobile_phone").val();
	$lr_old_value["MOBILE_PHONE_MAIL"]	= $("#id_txt_use_mobile_phone_mail").val();
	
	// パスワード変更チェックボックス
	$("#id_ckb_change_passwd").bind("change", function(){
		procChangePChk($(this).attr("checked"));
	});
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 保存
	$("#id_btn_use_save").bind("click", function(){
		if(!procSave()){
			return false;
		}
	});
	// 元に戻す
	$("#id_btn_use_reload").bind("click", function(){
		$("#id_txt_use_zip_code").val($lr_old_value["ZIP_CODE"]);
		$("#id_txt_use_address").val($lr_old_value["ADDRESS"]);
		$("#id_txt_use_closest_station").val($lr_old_value["CLOSEST_STATION"]);
		$("#id_txt_use_home_phone").val($lr_old_value["HOME_PHONE"]);
		$("#id_txt_use_home_mail").val($lr_old_value["HOME_MAIL"]);
		$("#id_txt_use_mobile_phone").val($lr_old_value["MOBILE_PHONE"]);
		$("#id_txt_use_mobile_phone_mail").val($lr_old_value["MOBILE_PHONE_MAIL"]);
		// パスワード変更チェックは外す
        $("#id_ckb_change_passwd").removeAttr('checked');
		procChangePChk(false);
	});
});
