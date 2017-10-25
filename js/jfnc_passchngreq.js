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
javascript関数
*******************************************************************************/
var $debug_mode = 0;						// デバッグモード
var $message = "";							// メッセージ
var $id_message = "id_alert_message";		// メッセージ表示部のID
var $nm_message = "nm_message";				// メッセージ表示部のNAME
var $id_token_code = "id_token_code";		// メッセージ表示部のID
var $nm_token_code = "nm_token_code";		// メッセージ表示部のNAME
var $obj_username = "";						// ユーザー名のオブジェクト
var $obj_password = "";						// パスワードのオブジェクト
var $obj_usecomp = "";						// 利用会社コードのオブジェクト
var $obj_mainform = "";						// メインフォームのオブジェクト
var $page_passchange = "../ctl/c_sendPasswordChangeURL.php";	// パスワード変更用URL送付行うphpファイル

/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
//maintenance.js内に記述

/*==============================================================================
  ページPOST
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_post_to			POST先のphpファイル
  ============================================================================*/
function postPage($p_object, $p_post_to){
	var $lr_param = {};			// 連想配列の初期化
	
	// 引数設定
	if($p_object){
		// inputタグのtextとhiddenを取得
		$lobj_param = $p_object.find("input[type='text'],input[type='password'],input[type='hidden']");

		if($lobj_param){
			$l_cnt = 0;
			
			// 値を連想配列に格納する
			$lobj_param.each(function(){
				$lr_param[$(this).attr("name")]	= $(this).val();
				$l_cnt = $l_cnt + 1;
			});
			
		}else{
			// form内にtextまたはhiddenがない場合
			$message = "オブジェクト不正です";
			return false;
		}
	}else{
		// p_objectが空の場合
		$message = "オブジェクト不正です";
		return false;
	}
	
	// POST処理
	$.post($p_post_to, $lr_param, callBackFnc);
	//$obj_mainform.load($p_post_to, $lr_param, callBackFnc);
}
// コールバック関数
function callBackFnc($p_data){
	//alert($p_data);
	//return true;
	
	if($p_data){
		if ($p_data == "NG"){
			// 認証失敗の場合
			alert("メールアドレスの登録がありませんでした。\nユーザーコード、利用会社が間違っていないか確認して下さい。");
			return true;
		}else if ($p_data == "OK"){
			// mainフォームに値をセット
			alert("登録済みのメールアドレスにパスワードリセット依頼用URLを送付しました。\nメールを確認し、パスワードリセット依頼を行ってください。");
			return true;
		}else{
			alert($p_data);
			return true;
		}
	}else{
		showMessage($obj_mainform, "No DATA");
		return false;
	}
}

/*==============================================================================
  入力チェック処理
  処理概要：
  		ユーザー名、パスワード、利用会社コードをチェックする
  ============================================================================*/
function checkInput(){
	// 入力値読み込み
	$obj_username = $("#id_ipt_username");
	$obj_usecomp = $("#id_ipt_usecomp");
	
	// ユーザー名とパスワードのチェック
	if($obj_username.val() === ''){
		$message = "ユーザー名を入力してください。";
		$obj_username.focus();
		//alert($message);
		return false;
	}
	if($obj_usecomp.val() === ''){
		$message = "利用会社コードを入力してください。";
		$obj_usecomp.focus();
		//alert($message);
		return false;
	}
	
	
	return true;
}

/*==============================================================================
  メッセージの表示
  処理概要：
  		引数で指定されたオブジェクトにメッセージを追加する
  ============================================================================*/
function showMessage($p_object, $p_mess){
	// メッセージをクリア
	clearMessage();
	
	if($p_object != undefined && $p_object != null){
		$l_html = "<span id=\"" + $id_message + "\" style=\"color:red; font-weight:bold\" name=\"" + $nm_message + "\">" + $p_mess + "</span>";
		$p_object.append($l_html);
	}
	return true;
}
/*==============================================================================
  メッセージのクリア
  処理概要：
  		メッセージをクリアする
  ============================================================================*/
function clearMessage(){
	$obj_message = $("#"+$id_message);
	
	if($obj_message != undefined && $obj_message != null){
		$obj_message.remove();
	}
	return true;
}
/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(function(){
	$obj_mainform = $("#id_form_main");
	
	//==============================================
	// OKボタン
	//==============================================
	$("#id_sbt_login").bind("click", function(){
		clearMessage();
		
		// 入力チェック
		if(!checkInput()){
			showMessage($obj_mainform, $message);
			return false;
		}
		
		// パスワード変更依頼用URL送付処理起動
		postPage($obj_mainform, $page_passchange);
	});
	// フォーム内でEnterが押された場合も同様の処理を行う
	$obj_mainform.keyup(function(e){
	    // ↓が押された場合
	    if(e.keyCode==13){
	    	$("#id_sbt_login").click();
	    }
	})
});
