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
//var $this_page_file	= "companies_mnt.php";
var $mailsend_page		= "../ctl/c_mailSend.php";			// メール送信
var $lr_param			= {};								// 連想配列の初期化

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	
	// FORM
	$obj_main_form = $("#id_form_main");
	
	// フォーカス処理
	procInputFocus();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 送信
	$("#id_btn_send").bind("click", function(){
	//	alert("送信処理を実行します");
		
		if(check_Main()){
			// nowloading
			showNowLoading();
			postPage($obj_main_form, $mailsend_page);
		}
	});
	
	// 確認
	$("#id_btn_confirmation").bind("click", function(){
		alert("送信前に確認画面に遷移しようと思います。\nまずは、送信処理の作業を優先で行ったので、\n実際にはまだ画面が出来ていません。");
	//	postPage($obj_main_form, $mailsend_page);
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
});

/*==============================================================================
  ページPOST
  処理概要：
  		コールバック関数
  引数：
		$p_data				
  ============================================================================*/
function callBackFnc($p_data){
	// NowLoading削除
	removeNowLoading();
	
	if($p_data == "send nomal"){
		// 正常終了
		alert("ユーザー管理情報を送信しました。");
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
}

/*==============================================================================
  入力データチェック
  処理概要：
  		メールアドレス入力チェック
  引数：
  ============================================================================*/
function check_Main(){
	
	// 入力必須確認
	if($obj_main_form.find("input[name='TO_HOME']").val()=="" && $obj_main_form.find("input[name='TO_MOBILE']").val()==""){
		alert("送信先はどちらか必ず入力してください。");
		$obj_main_form.find("input[name='TO_HOME']").focus();
		return false;
	}
	
	// To（自宅）
	if(!$obj_main_form.find("input[name='TO_HOME']").val()==""){
		if(!checkAddress($obj_main_form.find("input[name='TO_HOME']").val())){
			$obj_main_form.find("input[name='TO_HOME']").focus();
			return false;
		}
	}
	
	// To（携帯）
	if(!$obj_main_form.find("input[name='TO_MOBILE']").val()==""){
		if(!checkAddress($obj_main_form.find("input[name='TO_MOBILE']").val())){
			$obj_main_form.find("input[name='TO_MOBILE']").focus();
			return false;
		}
	}
	
	// Cc
	if(!$obj_main_form.find("input[name='CC']").val()==""){
		if(!checkAddress($obj_main_form.find("input[name='CC']").val())){
			$obj_main_form.find("input[name='CC']").focus();
			return false;
		}
	}
	
	// Bcc
	if(!$obj_main_form.find("input[name='BCC']").val()==""){
		if(!checkAddress($obj_main_form.find("input[name='BCC']").val())){
			$obj_main_form.find("input[name='BCC']").focus();
			return false;
		}
	}
	return true;
}

/*==============================================================================
  メールアドレスチェック
  処理概要：
  		メールアドレス入力チェック
  引数：
  		$obj_add				チェック対象メールアドレス
  ============================================================================*/
function checkAddress($obj_add){
	
	atmark = 0;
	dot =0;
	
	var j,moji_x,msg;
	for(var i=0;i<$obj_add.length;i++){
		//一文字ずつ文字列を切り出す
		moji = $obj_add.charCodeAt(i);
		if(moji >= 48 && moji<= 57){
			//数字
		} else if(moji >= 65 && moji <= 90){
			//アルファベット大文字
		} else if(moji >= 97 && moji <= 122){
			// アルファベット小文字
		} else if(moji == 64){
			//@
			if(i == 0){
				alert("一文字目が「@」のメールアドレスは不適切です。");
				return false;
			} else if(i == ($obj_add.length -1)){
				alert("末尾が「@」のメールアドレスは不適切です。");
				return false;
			}
			//一文字前の文字を調べる
			if($obj_add.charCodeAt(i-1) == 46){
				alert("「.@」のメールアドレスは不適切です。");
				return false;
			}
			atmark++;
		} else if(moji == 45 || moji == 95){
			//ハイフンもしくはアンダースコア
		} else if(moji == 46){
			//「.」（ドット）
			if(i == 0){
				alert("一文字目が「.」のメールアドレスは不適切です。");
				return false;
			} else if(i == ($obj_add.length - 1)){
				alert("末尾が「.」のメールアドレスは不適切です。");
				return false;
			//} else if(i == ($obj_add.length - 2)){
			//	alert("最後の「.」の後に一文字しかないというアドレスは不適切です。");
			//	return false;
			}
			if(i >= 1){
				//一文字前の文字を調べる
				if($obj_add.charCodeAt(i-1) == 46){
					alert("「.」が連続するメールアドレスは不適切です。");
					return false;
				}
			}
			dot++;
		} else {
			j = i + 1;
			moji_x = $obj_add.charAt(i);
			alert("「" + moji_x + "」は、メールアドレスとして不適切な文字です。");
			return false;
		}
	}
	if(atmark == 0){
		alert("メールアドレスに、「@」が含まれていません。\nメールアドレスとして不適切です。");
		return false;
	} else if (atmark >= 2){
		alert("メールアドレスに、「@」が二つ以上あります。\nメールアドレスとして不適切です。");
		return false;
	}
	if(dot == 0){
		alert("メールアドレスに、「.」がひとつもありません。\nメールアドレスとして不適切です。");
		return false;
	}
	return true;
}
