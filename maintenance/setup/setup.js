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
 セットアップ用javascript関数
*******************************************************************************/
var $debug_mode		= 0;						// デバッグモード
var $status_code	= 1;
var $lr_param		= {};						// POST用配列
var $status_tag1	= "<span class='c_span_setup_status'>";
var $status_tag2	= "</span>";
var $alart_mess		= "セットアップを実行します。\n指定したDB名に有る既存のデータはすべて消去されます。\nよろしいですか？";

/*============================================================================
  画面起動時処理
  ============================================================================*/
$(document).ready(function(){
	// ボタン処理バインド
	$("#id_btn_setup").bind("click", function(){
		if(window.confirm($alart_mess)){
			if (clickSetup()){
			
			}else{
				alert("セットアップに失敗しました。");
			}
		}
	});
	

});


/*============================================================================
  setupボタン処理
  ============================================================================*/
function clickSetup(){
	//alert("setup");
	$status_code = 0;
	$lr_param	= {};	 // 配列の初期化
	
	// チェック結果クリア
	$("#id_td_dbchkresult").html("&nbsp;");
	$("#id_td_compchkresult").html("&nbsp;");
	$("#id_td_setup_status").html("&nbsp;");
	
	// 空欄がある場合はエラーとする
	var $alert_mess = "";
	$(".c_ipt_required").each(function(){
		if ($(this).val() == ""){
			$alert_mess = $alert_mess + "『" + $(this).attr("name") + "』" + "\n";
		}
	});
	if ($alert_mess != ""){
		alert($alert_mess + "を入力して下さい。");
		// チェックNGをステータスに表示
		$("#id_td_setup_status").html($status_tag1 + "入力チェック NG" + $status_tag2);
		$status_code = 1
		return false;
	}
	
	// 各値のチェック
	// 初期会社
	// DATA_ID->正の数値3桁まで
	var $l_data_id = $("#id_txt_data_id").val();
	if (!$l_data_id.match(/^[0-9]+$/)){
		$alert_mess = $alert_mess + "DATA_IDは数値で指定して下さい。" + "\n";
		$status_code = 1
	}else if(Number($l_data_id) < 1){
		$alert_mess = $alert_mess + "DATA_IDは1以上の数値で指定して下さい。" + "\n";
		$status_code = 1
	}
	if ($l_data_id.length > 3){
		$alert_mess = $alert_mess + "DATA_IDは３桁以内で指定して下さい。" + "\n";
		$status_code = 1
	}
	
	// 利用会社コード->英数字6桁まで
	var $l_comp_code = $("#id_txt_comp_code").val();
	if (!$l_comp_code.match(/^[a-zA-Z0-9]+$/)){
		$alert_mess = $alert_mess + "利用会社コードは英数字で指定して下さい。" + "\n";
		$status_code = 1
	}
	if ($l_comp_code.length > 6){
		$alert_mess = $alert_mess + "利用会社コードは６文字以内で指定して下さい。" + "\n";
		$status_code = 1
	}
	
	// 利用会社名->50文字まで
	var $l_comp_name = $("#id_txt_comp_name").val();
	if ($l_comp_name.length > 50){
		$alert_mess = $alert_mess + "利用会社コードは５０文字以内で指定して下さい。" + "\n";
		$status_code = 1
	}
	
	// 管理者ユーザーコード->英数字10桁まで
	var $l_adminusr_code = $("#id_txt_adminusr_code").val();
	if (!$l_adminusr_code.match(/^[a-zA-Z0-9]+$/)){
		$alert_mess = $alert_mess + "管理者ユーザーコードは英数字で指定して下さい。" + "\n";
		$status_code = 1
	}
	if ($l_adminusr_code.length > 10){
		$alert_mess = $alert_mess + "管理者ユーザーコードは１０文字以内で指定して下さい。" + "\n";
		$status_code = 1
	}
	
	// チェックで異常があった場合は終了
	if ($status_code == 1){
		alert($alert_mess);
		// NGを表示
		$("#id_td_compchkresult").html("NG")
		$("#id_td_compchkresult").css("color", "red");
		// チェックNGをステータスに表示
		$("#id_td_setup_status").html($status_tag1 + "入力チェック NG" + $status_tag2);
		return false;
	}else{
		// OKの場合はOKを表示
		$("#id_td_compchkresult").html("OK")
		$("#id_td_compchkresult").css("color", "green");
	}
	
	// チェックOKをステータスに表示
	$("#id_td_setup_status").html($status_tag1 + "入力チェック OK" + $status_tag2);
	
	// DB周り->接続を確認
	// パラメータ取得
	$lr_param["db_host"]		= $("#id_txt_db_host").val();
	$lr_param["db_user"]		= $("#id_txt_db_user").val();
	$lr_param["db_pass"]		= $("#id_txt_db_pass").val();
	$lr_param["db_name"]		= $("#id_txt_db_name").val();
	$lr_param["data_id"]		= $("#id_txt_data_id").val();
	$lr_param["comp_code"]		= $("#id_txt_comp_code").val();
	$lr_param["comp_name"]		= $("#id_txt_comp_name").val();
	$lr_param["adminusr_code"]	= $("#id_txt_adminusr_code").val();
	$lr_param["smtp_server"]	= $("#id_txt_smtp_server").val();
	$lr_param["smtp_port"]		= $("#id_txt_smtp_port").val();
	$lr_param["smtp_account"]	= $("#id_txt_smtp_account").val();
	$lr_param["smtp_pass"]		= $("#id_txt_smtp_pass").val();
	$lr_param["smtp_secure"]	= $("#id_txt_smtp_secure").val();
	$lr_param["mail_manager"]	= $("#id_txt_mail_manager").val();
	$lr_param["mail_report"]	= $("#id_txt_mail_report").val();
	
	// チェックプログラム起動（以降の処理はコールバック関数から起動）
	checkPackages();
	
	return true;
}
/*============================================================================
  パッケージチェック処理
  ============================================================================*/
function checkPackages(){
	var $post_to	= "checkPackages.php";
	
	// チェック用PHPにPOST
	$.post($post_to, $lr_param, callBackFncCPK);

}
function callBackFncCPK($p_data){
	//alert($p_data);
	if ($p_data == 0){
		// パッケージチェックOKをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "パッケージチェック OK" + $status_tag2);
		
		// DB接続チェックを起動
		checkConnectDB();
	}else{
		alert($p_data);
		// DBチェックNGをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "パッケージチェック NG" + $status_tag2);
		$status_code = 1;
		return false;
	}
}


/*============================================================================
  DB接続チェック処理
  ============================================================================*/
function checkConnectDB(){
	var $post_to	= "checkDB.php";
	
	// チェック用PHPにPOST
	$.post($post_to, $lr_param, callBackFncCDB);

}
function callBackFncCDB($p_data){
	//alert($p_data);
	if ($p_data == 0){
		// OKを表示
		$("#id_td_dbchkresult").html("OK")
		$("#id_td_dbchkresult").css("color", "green");
		
		// DBチェックOKをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "DBチェック OK" + $status_tag2);
		
		// 初期セットアッププログラムを起動
		procSetup();
	}else{
		alert($p_data);
		// NGを表示
		$("#id_td_dbchkresult").html("NG")
		$("#id_td_dbchkresult").css("color", "red");
		// DBチェックNGをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "DBチェック NG" + $status_tag2);
		$status_code = 1;
		return false;
	}
}


/*============================================================================
  初期セットアップ処理
  ============================================================================*/
function procSetup(){
	var $post_to	= "setupDB.php";
	
	// チェック用PHPにPOST
	$.post($post_to, $lr_param, callBackFncSDB);
}
function callBackFncSDB($p_data){
	//alert($p_data);
	
	if ($p_data == 0){
		// セットアップOKをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "セットアップ OK" + $status_tag2);
		
		// 接続情報ファイル作成処理起動
		procMakeSpotValue();
	}else{
		alert($p_data);
		$status_code = 1;
		// セットアップNGをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "セットアップ NG" + $status_tag2);
		return false;
	}
}
/*============================================================================
  接続情報ファイル作成処理
  ============================================================================*/
function procMakeSpotValue(){
	var $post_to	= "makeSpotValue.php";
	
	// チェック用PHPにPOST
	$.post($post_to, $lr_param, callBackFncMSP);
}
function callBackFncMSP($p_data){
	//alert($p_data);
	
	if ($p_data == 0){
		// 接続情報変更OKをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "接続情報変更 OK" + $status_tag2);
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "・・・セットアップ完了" + $status_tag2);
		
		alert("セットアップが完了しました。");
	}else{
		alert($p_data);
		$status_code = 1;
		// 接続情報変更NGをステータスに表示
		$(".c_span_setup_status:last").after("</br>" + $status_tag1 + "接続情報変更 NG" + $status_tag2);
		return false;
	}
}