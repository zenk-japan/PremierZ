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
 作業管理画面カレンダー用javascript関数
*******************************************************************************/
var $this_page_file			= "tasks.php";						// 当画面のPHPファイル
var $now_year				= "";
var $now_month				= "";
/*==============================================================================
  月移動処理
  処理概要：新しい年月を隠し項目にセットし、自画面にPOST
  引数：
  		$p_move_mode					移動モード（P：前の月、N：次の月）
  ============================================================================*/
function moveCalendarMonth($p_move_mode){
	var $lv_cal_year			= "";
	var $lv_cal_month			= "";
	
	// 現在の年月を取得
	var $ln_cal_year			= parseInt($("#id_hd_cal_yyyy").val(), 10);
	var $ln_cal_month			= parseInt($("#id_hd_cal_mm").val(), 10);

	// 年月の再設定
	if ($p_move_mode == 'P'){
	// 前の月に移動の場合
		if ($ln_cal_month == 1){
		// 1月の場合は年を-1して12月にセット
			if($ln_cal_year > 1900){
				$ln_cal_year	= $ln_cal_year - 1;
				$ln_cal_month	= 12;
			}
		}else{
		// 1月以外の場合は月を-1してセット
			$ln_cal_month	= $ln_cal_month - 1;
		}
	}else{
	// 次の月に移動の場合
		if ($ln_cal_month == 12){
		// 12月の場合は年を+1して1月にセット
			if($ln_cal_year < 3000){
				$ln_cal_year	= $ln_cal_year + 1;
				$ln_cal_month	= 1;
			}
		}else{
		// 12月以外の場合は月を+1してセット
			$ln_cal_month	= $ln_cal_month + 1;
		}
	}
	
	// 年月を文字列化
	if($ln_cal_month > 9){
		$lv_cal_month	= "" + $ln_cal_month;
	}else{
		$lv_cal_month	= "0" + $ln_cal_month;
	}
	$lv_cal_year		= "" + $ln_cal_year;
	
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($lv_cal_year);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($lv_cal_month);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val('');
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  指定年月移動処理
  処理概要：指定の年月を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function moveMonth($p_mm){
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($now_year);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($p_mm);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val('');
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  今月に戻す処理
  処理概要：今月の年月を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function moveThisMonth(){
	// 今月の年月を取得
	var $l_today			= new Date();
	var $l_today_yyyy		= $l_today.getFullYear();
	var $l_today_mm			= $l_today.getMonth() + 1;
	
	// 月を文字列化
	if($l_today_mm > 9){
		$l_today_mm	= "" + $l_today_mm;
	}else{
		$l_today_mm	= "0" + $l_today_mm;
	}
	
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($l_today_yyyy);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($l_today_mm);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val('');
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  今日に戻す処理
  処理概要：今日の年月日を隠し項目にセットし、自画面にPOST
  ============================================================================*/
function moveToday(){
	// 今月の年月を取得
	var $l_today			= new Date();
	var $l_today_yyyy		= $l_today.getFullYear();
	var $l_today_mm			= $l_today.getMonth() + 1;
	var $l_today_dd			= $l_today.getDate();
	
	// 月を文字列化
	if($l_today_mm > 9){
		$l_today_mm	= "" + $l_today_mm;
	}else{
		$l_today_mm	= "0" + $l_today_mm;
	}
	// 日を文字列化
	if($l_today_dd > 9){
		$l_today_dd	= "" + $l_today_dd;
	}else{
		$l_today_dd	= "0" + $l_today_dd;
	}
	
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($l_today_yyyy);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($l_today_mm);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val($l_today_dd);
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}

/*==============================================================================
  日付指定検索処理
  処理概要：日付を隠し項目にセットし、自画面にPOST
  引数：
  		$p_daynum					日
  ============================================================================*/
function searchByDate($p_daynum){
	// 日付を作成
	
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($now_year);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($now_month);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val($p_daynum);
	
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
	
}
/*==============================================================================
  日付ホバー処理
  ============================================================================*/
function procDateHover(){
	// オブジェクト
	$l_trgt_obj = $(".c_tr_cal_main_days>td>input[type=button]");
	
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			//alert("hover");
			// 背景
			$(this).css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			//alert("hoverout");
			// 背景
			$(this).css("background-color", 'transparent');
		}
	);
}
/*==============================================================================
  年月ホバー処理
  ============================================================================*/
function procYMHover(){
	// オブジェクト
	$l_trgt_obj = $("#id_btn_prev_year,#id_btn_next_year,#id_btn_this_month,#id_btn_today,.c_btn_cal_ope,.c_btn_cal_ope_selected");
	
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			//alert("hover");
			// 背景
			$(this).css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			//alert("hoverout");
			// 背景
			$(this).css("background-color", 'transparent');
		}
	);
}
/*==============================================================================
  年移動処理
  処理概要：新しい年月を隠し項目にセットし、自画面にPOST
  引数：
  		$p_move_mode					移動モード（P：前の月、N：次の月）
  ============================================================================*/
function moveCalendarYear($p_move_mode){
	var $lv_cal_year			= "";
	
	// 現在の年を取得
	var $ln_cal_year			= parseInt($now_year, 10);

	// 年月の再設定
	if ($p_move_mode == 'P'){
		// 前の年に移動の場合
		if($ln_cal_year > 1900){
			$ln_cal_year	= $ln_cal_year - 1;
		}
	}else{
		// 次の年に移動の場合
		if($ln_cal_year < 3000){
			$ln_cal_year	= $ln_cal_year + 1;
		}
	}
	
	// 年を文字列化
	$lv_cal_year		= "" + $ln_cal_year;
	
	// hidden項目にセットする
	$obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val($lv_cal_year);
	$obj_hidden_form.find("input[name='nm_work_cal_mm']").val($now_month);
	$obj_hidden_form.find("input[name='nm_work_cal_dd']").val('');
	// 再読み込み
	movePage($obj_hidden_form, $this_page_file);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	
	// 現在の年月
	$now_year	= $obj_hidden_form.find("input[name='nm_work_cal_yyyy']").val();
	$now_month	= $obj_hidden_form.find("input[name='nm_work_cal_mm']").val();

	/*-----------------------------
		Hover処理
	  -----------------------------*/
	// 年月ボタン
	procYMHover();
	
	// 日ボタン
	procDateHover();
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// 前年移動ボタン
	$("#id_btn_prev_year").bind("click", function(){
		moveCalendarYear('P');
	});
	
	// 翌年移動ボタン
	$("#id_btn_next_year").bind("click", function(){
		moveCalendarYear('N');
	});
	
	// 月ボタン
	$(".c_btn_cal_ope,.c_btn_cal_ope_selected").bind("click", function(){
		moveMonth($(this).attr('name'));
	});
	
	// 日ボタン
	$(".c_tr_cal_main_days>td>input[type=button]").bind("click", function(){
		searchByDate($(this).val());
	});
	
	// 今月ボタン
	$("#id_btn_this_month").bind("click", function(){
		moveThisMonth();
	});
	
	// 今日ボタン
	$("#id_btn_today").bind("click", function(){
		moveToday();
	});
});