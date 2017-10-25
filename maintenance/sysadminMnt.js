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
 システム管理者変更画面処理
*******************************************************************************/
var $this_page			= "sysadminMnt.php";			// 自ページのファイル名
var $menu_post_to		= "mntMenu.php";				// メニューに戻るでPOSTするphpファイル
var $edit_page			= "c_editSysadmin.php";			// ユーザー
var $pchk_post_to		= "../ctl/c_loginCheck.php";	// パスワードのチェック用モジュール
var $obj_hiddenform		= "";

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
  チェックボックスクリック時処理
  引数
  				$p_value			チェックボックスの値
  				$p_mode				動作モード（user,password）
  ============================================================================*/
function procCheckBox($p_value, $p_mode){
	//alert($p_value+":"+$p_mode);
	var $lo_trgt = "";
	
	// 変更ターゲットを決定
	if ($p_mode == "user"){
		// ユーザーの場合
		$lo_trgt = $("#id_txt_newuser");
	}else{
		// パスワードの場合
		$lo_trgt = $("#id_txt_oldpass, #id_txt_newpass");
	}
	
	// 入力項目の使用可否を変更
	if ($p_value == true){
		// チェックが入った場合
		$lo_trgt.attr('disabled','');
	}else{
		// チェックが外れた場合
		$lo_trgt.attr('disabled','disabled');
	}
}

/*==============================================================================
  保存ボタンクリック時処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procSave($p_obj_hidden){
	var $l_err_code = 0;
	var $l_err_mess = "";
	
	// 変更なしの場合は終了
	if ($("#id_ckb_user").attr("checked") == false && $("#id_ckb_password").attr("checked") == false){
		alert("変更項目がありません。");
		return false;
	}
	
	// 値取得
	var $l_new_user = $("#id_txt_newuser").val();
	$l_new_user = removeSpace($l_new_user);
	$l_new_user = removeSpChar($l_new_user);
	
	var $l_old_pass = $("#id_txt_oldpass").val();
	$l_old_pass = removeSpace($l_old_pass);
	$l_old_pass = removeSpChar($l_old_pass);
	
	var $l_new_pass = $("#id_txt_newpass").val();
	$l_new_pass = removeSpace($l_new_pass);
	$l_new_pass = removeSpChar($l_new_pass);
	
	// チェック
	// ユーザーコード
	if ($("#id_ckb_user").attr("checked") == true){
		
		if ($l_new_user.length == 0){
			$l_err_code = 1;
			$l_err_mess += "ユーザーコードを入力して下さい。\n";
		}else{
			if (!IsAlphNum($l_new_user)){
				$l_err_code = 1;
				$l_err_mess += "ユーザーコードは英数字で入力して下さい。\n";
			}else if ($l_new_user.length > 10){
				$l_err_code = 1;
				$l_err_mess += "ユーザーコードは１０文字以内で入力して下さい。\n";
			}
		}
	}
	
	// パスワード
	if ($("#id_ckb_password").attr("checked") == true){
		
		// 現在のパスワード
		if ($l_old_pass.length == 0){
			$l_err_code = 1;
			$l_err_mess += "現在のパスワードを入力して下さい。\n";
		}else{
			if (!IsAlphNum($l_old_pass)){
				$l_err_code = 1;
				$l_err_mess += "現在のパスワードは英数字で入力して下さい。\n";
			}else if ($l_old_pass.length > 50){
					$l_err_code = 1;
					$l_err_mess += "現在のパスワードは５０文字以内で入力して下さい。\n";
			}
		}
			
		// 新しいパスワード
		if ($l_new_pass.length == 0){
			$l_err_code = 1;
			$l_err_mess += "新しいパスワードを入力して下さい。\n";
		}else{
			if (!IsAlphNum($l_new_pass)){
				$l_err_code = 1;
				$l_err_mess += "新しいパスワードは英数字で入力して下さい。\n";
 			}else if ($l_new_pass.length > 50){
				$l_err_code = 1;
				$l_err_mess += "新しいパスワードは５０文字以内で入力して下さい。\n";
			}
		}
	}
	
	// ここまででエラーが有った場合は終了
	if ($l_err_code == 1){
		alert($l_err_mess);
		return false;
	}
	
	if ($("#id_ckb_password").attr("checked") == true){
		// 現在のパスワードをチェック
		// 連想配列の初期化
		var $lr_param	= {};
		
		// パラメータセット
		$lr_param["nm_userid"]		= $obj_hiddenform.find("input[name='nm_user_id']").val();
		$lr_param["nm_password"]	= $l_old_pass;
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
					return false;
				}
			}
		);
	}else{
		// パスワード変更なしの場合はそのまま保存処理へ
		procUserDataPost();
	}
}
/*==============================================================================
  ユーザー変更処理
  ============================================================================*/
function procUserDataPost(){
	// 連想配列の初期化
	var $lr_param	= {};
	
	// パラメータセット
	$lr_param["nm_token_code"]		= $obj_hiddenform.find("input[name='nm_token_code']").val();
	$lr_param["USER_ID"]			= $obj_hiddenform.find("input[name='nm_user_id']").val();
	$lr_param["USER_CODE"]			= $("#id_txt_newuser").val();
	$lr_param["PASSWORD"]			= $("#id_txt_newpass").val();

	$.post($edit_page, $lr_param, function($p_data){
		if($p_data == "update nomal"){
			// 正常終了
			alert("ユーザー情報を更新しました。");
			// 再表示
			movePage($obj_hiddenform, $this_page);
		}else{
			// 異常終了
			alert($p_data);
		}
	});
}

/*==============================================================================
  起動時処理
  ============================================================================*/
jQuery(function($){
	// 隠し項目のオブジェクト
	$obj_hiddenform = $("#id_form_hidden");
	
	// メニューに戻るボタン
	$("#id_btn_gomenu").bind("click", function(){
		// メニューの起動
		procReturn($obj_hiddenform);
	});
	
	// 保存ボタン
	$("#id_btn_save").bind("click", function(){
		// メニューの起動
		procSave($obj_hiddenform);
	});
	
	// チェックボックス処理
	$("#id_ckb_user").bind("change", function(){
		procCheckBox($(this).attr("checked"),"user");
	});
	$("#id_ckb_password").bind("change", function(){
		procCheckBox($(this).attr("checked"),"password");
	});
});