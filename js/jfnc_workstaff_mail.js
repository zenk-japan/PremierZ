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
var $obj_main_form;												// POST対象オブジェクト
//var $this_page_file	= "companies_mnt.php";
var $mailsend_page		= "../ctl/c_workRequestMailSend.php";	// メール送信
var $lr_param			= {};									// 連想配列の初期化

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
		procSend();
	});
	
	// GreyBox Close
	$("#id_btn_cancel").bind("click", function(){
		parent.parent.GB_CURRENT.hide();
	});
});

/*==============================================================================
  ページPOST
  処理概要：
  		各種パラメータをパラメータ配列に格納し、メール送信プログラムを起動する
  ============================================================================*/
function procSend(){
	
	// 対象作業人員をパラメータにセット
	var $l_trgt_name = "";
	var $l_trgt_cnt = 0;
	var $l_err_flag = 0;
	$(".c_hd_mail_work_staff_id").each(function(){
		$l_rec_num = parseInt($(".c_hd_mail_work_staff_id").index(this), 10) + 1;
		
		if ($("#id_txt_mail_trgtws_work_home_mail"+$l_rec_num).val() != "" || $("#id_txt_mail_trgtws_work_mobile_phone_mail"+$l_rec_num).val() != ""){
		// 送信メールアドレスの設定がある行のみ送信対象とする
			// 人員IDとPC、携帯のメールアドレスを格納する
			$l_trgt_cnt++;
			$lr_param["work_staff_id" + $l_trgt_cnt]			= $("#id_hd_mail_work_staff_id"+$l_rec_num).val();
			$lr_param["work_home_mail" + $l_trgt_cnt]			= $("#id_txt_mail_trgtws_work_home_mail"+$l_rec_num).val();
			$lr_param["work_mobile_phone_mail" + $l_trgt_cnt]	= $("#id_txt_mail_trgtws_work_mobile_phone_mail"+$l_rec_num).val();
			// メールアドレスのチェック-PC
			if(!$lr_param["work_home_mail" + $l_trgt_cnt]==""){
				// 無効なアドレスの場合は終了
				if(!checkAddress($lr_param["work_home_mail" + $l_trgt_cnt])){
					$("#id_txt_mail_trgtws_work_home_mail"+$l_rec_num).focus();
					$l_err_flag = 1;
				}
			}
			// メールアドレスのチェック-携帯
			if(!$lr_param["work_mobile_phone_mail" + $l_trgt_cnt]==""){
				// 無効なアドレスの場合は終了
				if(!checkAddress($lr_param["work_mobile_phone_mail" + $l_trgt_cnt])){
					$("#id_txt_mail_trgtws_work_mobile_phone_mail"+$l_rec_num).focus();
					$l_err_flag = 1;
				}
			}
		// 対象者表示用のユーザー名取得
			$l_trgt_name += "・ " + $("#id_span_mail_work_user_name"+$l_rec_num).html() + "\n";
		}
	});
	
	// エラーが有った場合は終了
	if ($l_err_flag == 1){
		return false;
	}
	
	// 対象が0件の場合は終了
	if ($l_trgt_cnt == 0){
		alert("送信対象が0名です。");
		return false;
	}
	
	// 各種パラメータをセットする
	// トークン
	$lr_param["token"]	= $(".c_hd_mail_hidden_items[name='nm_token_code']").val();
	// CC
	$lr_param["cc"]		= $("#id_txt_mail_cc").val();
	if(!$lr_param["cc"]==""){
		// 無効なアドレスの場合は終了
		if(!checkAddress($lr_param["cc"])){
			 $("#id_txt_mail_cc").focus();
			return false;
		}
	}
	
	// BCC
	$lr_param["bcc"]	= $("#id_txt_mail_bcc").val();
	if(!$lr_param["bcc"]==""){
		// 無効なアドレスの場合は終了
		if(!checkAddress($lr_param["bcc"])){
			 $("#id_txt_mail_bcc").focus();
			return false;
		}
	}
	
	// タイトル
	$lr_param["title"]	= $("#id_txt_mail_title").val();
	// 本文
	$lr_param["body"]	= $("#id_txtarea_mail_body").val();
	
	if(window.confirm($l_trgt_name + "\n上記ユーザーにメールを送信します。\nよろしいですか？")){
	// 最終確認OKならメールを送信
		// nowloading
		showNowLoading();
		
		// 送信処理の起動
		$.post($mailsend_page, $lr_param, callBackFnc);
		return true;
	}
		
}
function callBackFnc($p_data){
	// NowLoading削除
	removeNowLoading();
	//alert($p_data);
	if($p_data == "send nomal"){
		// 正常終了
		alert("作業依頼メールを送信しました。");
		// GreyBox Close
		parent.parent.GB_CURRENT.hide();
	}else{
		// 異常終了
		alert($p_data);
	}
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
