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
var openWindow;								// 子画面のウィンドウオブジェクト
var thisWindowName;							// 今の画面のウィンドウ名
var thisWindow;								// 今の画面のウィンドウオブジェクト
var $id_loading_div		= "id_ext_loading_div";		// ロード中表示DIVのID
/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
function movePage($p_object, $p_move_to){
	// ページ読み込み
	$p_object.attr("target", window.name);
	$p_object.attr("method", "POST");
	$p_object.attr("action", $p_move_to);
	
	//alert($p_move_to);
	$p_object.submit();
	
}
/*==============================================================================
  ページオープン
  処理概要：
  		新規にウィンドウを開いてHTMLファイルを表示する
  引数：
  		$p_window_name		新規ウィンドウの名前
  		$p_html				開くHTMLファイル
  ============================================================================*/
function openPage($p_window_name, $p_html){
	$new_win = window.open($p_html, $p_window_name);
	window.focus($new_win);
}

/*==============================================================================
  ページポップアップ
  処理概要：
  		新規にポップアップを開いてHTMLファイルを表示する
  引数：
  		$p_url				表示するURL
  		$p_window_name		ウィンドウ名
  		$p_width			幅
  		$p_height			高さ
  ============================================================================*/
function openPopup($p_url, $p_window_name, $p_width, $p_height, $p_properties){
	var $l_properties		= "";
	var $l_window_width		= screen.width;		// ウィンドウの幅
	var $l_window_height	= screen.height;	// ウィンドウの高さ
	var $l_popup_width		= $p_width;
	var $l_popup_height		= $p_height;
	
	// プロパティの指定が無い場合は、基本の仕様で表示
	if ($p_properties == 'undifined'){
		$l_properties		= "location=no, menubar=no, status=yes, scrollbars=yes, resizable=no, toolbar=no";
	}else{
		$l_properties		= $p_properties;
	}
	
	// 幅、高さの指定が無い場合はウィンドウサイズとする
	if ($p_width == 'undifined'){
		$l_popup_width		= $l_window_width;
	}
	if ($p_height == 'undifined'){
		$l_popup_height		= $l_window_height;
	}
	
	if ($l_window_width > $l_popup_width){
	// ポップアップの方が小さい場合は中心に表示されるようにleftを調整する
		$l_properties += ", left=" + ($l_window_width - $l_popup_width) / 2;
	}else{
		$l_popup_width = $l_window_width;
	}
	$l_properties += ", width=" + $l_popup_width;
	
	// ポップアップの方が小さい場合は中心に表示されるようにheightを調整する
	if ($l_window_height > $l_popup_height){
		$l_properties += ", top=" + ($l_window_height - $l_popup_height) / 2;
	}else{
		$l_popup_height = $l_window_height;
	}
	$l_properties += ", height=" + $l_popup_height;
	
	window.open($p_url, $p_window_name , $l_properties);
	
	window.focus($p_window_name);
}
/*==============================================================================
  書式チェック(YYYY-MM)
  処理概要：
  		YYYY-MM形式で入力されているかチェックする
  引数：
  		$p_value				チェック対象値
  		$p_nullcheck_flg		NULLもチェックする場合は'Y'
  ============================================================================*/
function checkFormatYYYYMM($p_value, $p_nullcheck_flg){
	$l_return_value = '0';
	
	// NULLチェック
	if($p_nullcheck_flg == 'Y' && ($p_value == null || $p_value == '')){
		$l_return_value = '入力して下さい';
		return $l_return_value;
	}
	
	// 書式チェック
	if($p_value != null && $p_value != ''){
		if($p_value.match(/^\d{4}$|^\d{4}-\d{2}$/)){
			// YYYYまたはYYYY-MMならOK
		}else{
			$l_return_value = 'YYYYまたはYYYY-MM形式で入力して下さい';
			return $l_return_value;
		}
	}
	return $l_return_value;
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
  データ操作画面起動
  処理概要：
  		データ操作画面をPOSTで起動する
  引数：
  		p_form_obj			ボタンのあるFORM
  		p_mode				オペレーションモード(insert、update、delete)
  ============================================================================*/
function openPDFData(p_form_obj, p_mode){
	// フォームの取得
	form_obj = p_form_obj.form;
	
	// 新規に開くウィンドウの名前をページ名と日付シリアル値から作成
	var dt_today = new Date();
	var trgt_name = form_obj.hd_page_name.value + "_PDFData" + dt_today.getTime();
	var windowObject;
	
	// ウィンドウオープン
	//windowObject = window.open("",trgt_name);
	form_obj.target					= trgt_name;
	//form_obj.target					= "";
	form_obj.hd_reserv1_id.value	= p_mode;
	form_obj.method					= "POST";
	form_obj.action					= "../page/pdflist_mnt.php";
	form_obj.submit();
	
	openWindow = windowObject;
	
}
/*==============================================================================
  日付入力補助
  処理概要：
  		日付入力補助
  引数：
  		$id_txt_edit_calendar		日付入力するID
  ============================================================================*/
function procCalDate($id_txt_edit_calendar){
	jQuery(function($){
		$($id_txt_edit_calendar).datepicker({
			//changeMonth: true,
			//changeYear: true
			
			
			inline: true,
			beforeShowDay: function(date){
				var weeks = date.getDay();
				var texts = "";
				if (weeks == 0)
					texts = "休日";
				/* 休日のチェック */
				/*
				if(date.isJpHoliday()) {
					texts = date.jp_hol_name;
					// description = d.jp_hol_desc;
					weeks = 0;
				}
				if (weeks == 0)
					return [true, 'days_red', texts];
				else if (weeks == 6)
					return [true, 'days_blue'];
				*/
				return [true, 'days_normal'];
			}
		});
	});
}
/*==============================================================================
  今日の日付取得
==============================================================================*/
function getTodayDate(){
	$l_return_value = "";
	
	var $l_today	= new Date();
	var $l_year		= $l_today.getFullYear();			// 年
	var $l_month	= "" + ($l_today.getMonth() + 1);	// 月
	if ($l_month.length == 1){
		$l_month = "0" + $l_month;
	}
	var $l_date		= "" + $l_today.getDate();			// 日
	if ($l_date.length == 1){
		$l_date = "0" + $l_date;
	}
	
	$l_return_value = $l_year+'/'+$l_month+'/'+$l_date;
	
	return $l_return_value;
}