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
 サブ画面表示処理
*******************************************************************************/
var $debug_mode			= 0;						// デバッグモード
var $message			= "";						// メッセージ
var $ar_post_value;									// POSTする値の配列
var $load_file_name;								// サブ画面に読み込むphpファイル
var $id_back_div		= "id_ext_back_div";		// 背景のDIVのID
var $id_page_div		= "id_ext_page_div";		// ページ表示用DIVのID
var $id_close_button	= "id_subv_btn_close";		// 閉じるボタンのID
var $id_save_button		= "id_subv_btn_save";		// 保存ボタンのID
var $id_loading_div		= "id_ext_loading_div";		// ロード中表示DIVのID
var $alert_color		= "#ff0000";				// 入力エラー項目の背景色
var $insupd_post_to		= "c_useCompanySave.php";	// 新規登録、更新処理でPOSTするPHPファイル

/*==============================================================================
  サブ画面を表示
  引数：
  					$pr_post_value				読み込むPHPファイルに渡すパラメータ配列
  					$p_load_file_name			読み込むPHPファイル
==============================================================================*/
function showSubView($pr_post_value, $p_load_file_name){
	// POSTする値をセット
	$ar_post_value = $pr_post_value;
	
	// 読み込むPHPファイルをセット
	$load_file_name = $p_load_file_name;
	if(!$load_file_name){
		return false;
	}
	
	// 背景のDIVを表示
	showBackDiv();
}
/*==============================================================================
  背景のDIVを表示
  引数：
==============================================================================*/
function showBackDiv(){
	// 背景のDIVを設定
	$l_html_backdiv = "<div style=\"display:none\" id=\"" + $id_back_div + "\"></div>"
	
	// 背景のDIVを表示
	$("body").prepend($l_html_backdiv);
	$lo_back_div = $("#" + $id_back_div);
	$lo_back_div.css("opacity", "0");
	$lo_back_div.show();
	$lo_back_div.animate({
	    	opacity: 0.7
		},600,function(){
			// HTMLを配置するDIVを表示
			showPageDiv($lo_back_div);
		}
	);
	
	$lo_back_div.bind("click", function(){
		removePageDiv();
	});
	
}
/*==============================================================================
  ページのDIVを表示
  引数：
  					$po_master_div				背景のDIV
==============================================================================*/
function showPageDiv($po_master_div){
	// HTMLを配置するDIVを設定
	$l_html_pagediv = "<div style=\"display:none\" id=\"" + $id_page_div + "\"></div>";
	
	// DIVを配置
	$po_master_div.after($l_html_pagediv);
	//$po_master_div.prepend($l_html_pagediv);
	$lo_page_div = $("#" + $id_page_div);
	
	// NowLoading表示
	showNowLoading();
	
	// phpページをロード
	$lo_page_div.load($load_file_name, $ar_post_value, function(){
			// NowLoading削除
			removeNowLoading();
		
			// DIVを表示
			$lo_page_div.fadeIn("nomal",function(){
					// 保存ボタンクリック時の処理をバインド
					if($("#" + $id_save_button)){
						$("#" + $id_save_button).bind("click", function(){
							saveUseComp();
						});
					}
					// 閉じるボタンクリック時の処理をバインド
					$("#" + $id_close_button).bind("click", function(){
						removePageDiv();
					});
				}
			);
		}
	);
	
}
/*==============================================================================
  NowLoading表示
==============================================================================*/
function showNowLoading(){
	$l_html_nowloading = "<div style=\"display:none\" id=\"" + $id_loading_div + "\"></div>";
	$("body").prepend($l_html_nowloading);
	$lo_loading_div = $("#" + $id_loading_div);
	$lo_loading_div.fadeIn("fast");
}

/*==============================================================================
  NowLoading削除
==============================================================================*/
function removeNowLoading(){
	if($("#" + $id_loading_div)){
		$lo_loading_div.fadeOut("fast", function(){
				$lo_loading_div.remove();
			}
		);
	}
}
/*==============================================================================
  ページのDIVを削除
==============================================================================*/
function removePageDiv(){
	$lo_page_div = $("#" + $id_page_div);
	if($lo_page_div){
		$lo_page_div.fadeOut("nomal",function(){
				// 背景を削除
				removeBackDiv();
			}
		);
	}
}

/*==============================================================================
  背景のDIVを削除
  ============================================================================*/
function removeBackDiv(){
	$lo_back_div = $("#" + $id_back_div);
	if($lo_back_div){
		$lo_back_div.animate({
				opacity: 0
			},500,function(){
				$lo_back_div.hide();
				$lo_back_div.remove();
			}
		);
	}
}

  
/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){

});


/*==============================================================================
  保存処理
  処理概要:
  		DATA_IDと利用会社コードの入力チェックと保存用PHPファイルの起動を行う
  ============================================================================*/
function saveUseComp(){
	//alert("save");
	var $alert_mess		= "";
	var $lo_target		= "";
	var $l_target_val	= "";
	var $status_code	= 0
	
	/************************
		パラメータのセット
	*************************/
	var $lr_param = {};
	$lr_param["data_record"] = {};	// レコード用配列を初期化
	var $lr_data = {};				// レコード作成用配列を初期化
	
	// トークン
	$lr_param["nm_token_code"]	= $("input[name='nm_token_code']").val();
	
	/************************
		パラメータのチェック
	*************************/
	/*------------------------
		DATA_ID
	------------------------*/
	$lo_target			= $("#id_subv_ipt_dataid");
	$l_target_val		= $lo_target.val();
	$l_target_val		= removeSpace($l_target_val);
	$lr_data["data_id"]	= $l_target_val;
	
	// 入力されているかチェック
	if ($lr_data["data_id"]){
		// 正常ならカラーを戻す
		$lo_target.css("background-color", "");
		
		// 数値
		if (!IsNumeric($lr_data["data_id"])){
			$alert_mess = $alert_mess + "DATA_IDは数値で指定して下さい。" + "\n";
			$lo_target.css("background-color", $alert_color);
			$status_code = 1;
		// 1以上
		}else if(Number($lr_data["data_id"]) < 1){
			$alert_mess = $alert_mess + "DATA_IDは1以上の数値で指定して下さい。" + "\n";
			$lo_target.css("background-color", $alert_color);
			$status_code = 1;
		// 3桁以内
		}else if ($lr_data["data_id"].length > 3){
			$alert_mess = $alert_mess + "DATA_IDは３桁以内で指定して下さい。" + "\n";
			$lo_target.css("background-color", $alert_color);
			$status_code = 1;
		}else{
			// 正常ならカラーを戻す
			$lo_target.css("background-color", "");
		}
	}else{
		$lo_target.css("background-color", $alert_color);
		$alert_mess = $alert_mess + "DATA_IDを入力して下さい。" + "\n";
		$status_code = 1;
	}

	// 既に使用されているDATA_IDでないかチェックする
	// チェックは画面上で変更されている可能性が有るので、hidden項目で行う
	var $l_errflg = 0;
	$lr_used_dataid = $(".c_hdn_dataid");
	if($lr_used_dataid){
		$lr_used_dataid.each(
			function(){
				if($(this).val() == $lr_data["data_id"]){
					// 同じ値がある場合は背景色を変更する
					$lo_target.css("background-color", $alert_color);
					$alert_mess = $alert_mess + "入力されたDATA_IDは既に使用されています。他の値を入力して下さい。" + "\n";
					$status_code = 1;
					$l_errflg = 1;
					return false;
				}
			}
		);
	}
	if($l_errflg == 0 && $status_code == 0){
		// OKの場合は背景色を透明にする
		$lo_target.css("background-color", "");
	}
	
	/*------------------------
		利用会社コード
	------------------------*/
	$lo_target				= $("#id_subv_ipt_compcd");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$lr_data["comp_code"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["comp_code"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// 英数字の組み合わせ
		if(!IsAlphNum($lr_data["comp_code"])){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "利用会社コードは英数字で指定して下さい。" + "\n";
			$status_code = 1;
		// ６文字以内
		}else if($lr_data["comp_code"].length > 6){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "利用会社コードは６文字以内で指定して下さい。" + "\n";
			$status_code = 1;
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}else{
		// 入力されていない場合は背景色を変更する
		$lo_target.css("background-color", $alert_color);
		$alert_mess = $alert_mess + "利用会社コードを入力して下さい。" + "\n";
		$status_code = 1;
	}
	
	/*------------------------
		利用会社名
	------------------------*/
	$lo_target				= $("#id_subv_ipt_compnm");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["comp_name"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["comp_name"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// 50文字まで
		if ($lr_data["comp_name"].length > 50){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "利用会社名は５０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}else{
		// 入力されていない場合は背景色を変更する
		$lo_target.css("background-color", $alert_color);
		$alert_mess = $alert_mess + "利用会社名を入力して下さい。" + "\n";
		$status_code = 1;
	}
	
	/*------------------------
		備考
	------------------------*/
	$lo_target				= $("#id_subv_ipt_remarks");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["comp_remarks"]	= $l_target_val;
	
	// 50文字まで
	if ($lr_data["comp_remarks"].length > 50){
		$lo_target.css("background-color", $alert_color);
		$alert_mess = $alert_mess + "備考は５０文字以内で指定して下さい。" + "\n";
		$status_code = 1
	}else{
		// OKの場合は背景色を透明にする
		$lo_target.css("background-color", "");
	}
	
	/*------------------------
		管理者ユーザーコード
	------------------------*/
	$lo_target					= $("#id_subv_ipt_admcode");
	$l_target_val				= $lo_target.val();
	$l_target_val				= removeSpace($l_target_val);
	$lr_data["adminusr_code"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["adminusr_code"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// 英数字の組み合わせ
		if(!IsAlphNum($lr_data["adminusr_code"])){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "管理者ユーザーコードは英数字で指定して下さい。" + "\n";
			$status_code = 1;
		// ６文字以内
		}else if($lr_data["adminusr_code"].length > 10){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "管理者ユーザーコードは１０文字以内で指定して下さい。" + "\n";
			$status_code = 1;
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}else{
		// 入力されていない場合は背景色を変更する
		$lo_target.css("background-color", $alert_color);
		$alert_mess = $alert_mess + "管理者ユーザーコードを入力して下さい。" + "\n";
		$status_code = 1;
	}
	
	/*------------------------
		SMTPサーバ
	------------------------*/
	$lo_target				= $("#id_subv_ipt_smtpsrv");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["smtp_server"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["smtp_server"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// ７０文字まで
		if ($lr_data["smtp_server"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "SMTPサーバは７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		SMTPサーバのポート番号
	------------------------*/
	$lo_target				= $("#id_subv_ipt_smtpprt");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["smtp_port"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["smtp_port"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// ７０文字まで
		if ($lr_data["smtp_port"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "SMTPサーバのポート番号は７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		SMTPサーバの認証暗号化方式
	------------------------*/
	$lo_target				= $("#id_subv_ipt_smtpsecure");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["smtp_secure"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["smtp_secure"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		
		// sslかtls
		if ($lr_data["smtp_secure"] != "ssl" && $lr_data["smtp_secure"] != "tls"){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "SMTPサーバの認証暗号化方式は「ssl」または「tls」を指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		SMTPサーバのアカウント
	------------------------*/
	$lo_target					= $("#id_subv_ipt_smtpact");
	$l_target_val				= $lo_target.val();
	$l_target_val				= removeSpace($l_target_val);
	$l_target_val				= removeSpChar($l_target_val);
	$lr_data["smtp_account"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["smtp_account"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		// ７０文字まで
		if ($lr_data["smtp_account"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "SMTPサーバのアカウントは７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		SMTPサーバのパスワード
	------------------------*/
	$lo_target				= $("#id_subv_ipt_smtppss");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["smtp_pass"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["smtp_pass"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		// ７０文字まで
		if ($lr_data["smtp_pass"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "SMTPサーバのパスワードは７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		作業取り纏め用アドレス
	------------------------*/
	$lo_target				= $("#id_subv_ipt_mailman");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["mail_manager"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["mail_manager"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		// ７０文字まで
		if ($lr_data["mail_manager"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "作業取り纏め用アドレスは７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	/*------------------------
		勤怠報告用アドレス
	------------------------*/
	$lo_target				= $("#id_subv_ipt_mailrep");
	$l_target_val			= $lo_target.val();
	$l_target_val			= removeSpace($l_target_val);
	$l_target_val			= removeSpChar($l_target_val);
	$lr_data["mail_report"]	= $l_target_val;
	
	// 入力されているかチェック
	if($lr_data["mail_report"]){
		// 入力されている場合は背景色を透明にする
		$lo_target.css("background-color", "");
		// ７０文字まで
		if ($lr_data["mail_report"].length > 70){
			$lo_target.css("background-color", $alert_color);
			$alert_mess = $alert_mess + "勤怠報告用アドレスは７０文字以内で指定して下さい。" + "\n";
			$status_code = 1
		}else{
			// OKの場合は背景色を透明にする
			$lo_target.css("background-color", "");
		}
	}
	
	// チェックでエラーが有った場合はここで終了
	if ($status_code == 1){
		alert($alert_mess);
		return false;
	}
	
	
	// レコード作成
	$lr_param["data_record"][1] = $lr_data;
	
	/************************
		INSERT処理起動
	*************************/
	//alert("insert done");
	// NowLoading表示
	showNowLoading();
	
	// post処理
	$.post($insupd_post_to, $lr_param, function($p_data){
			if($p_data){
				removeNowLoading();
				alert($p_data);
				
				return false;
			}else{
				removeNowLoading();
				//alert("No DATA");
				alert("保存が完了しました");
				// 自画面を再読み込み
				movePage($("#id_form_hidden"), $this_page);
			}
		}
	);
}

