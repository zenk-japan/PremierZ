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
 メインメニュー用javascript関数
*******************************************************************************/
var $img_nomal	= "../img/menu_back.png";					// 通常時の背景
var $img_hover	= "../img/menu_back_hv.png";				// ホバー時の背景
var $interval_01;											// インターバル設定1
var $obj_title;												// タイトルのオブジェクト
var $obj_hidden_form;										// 隠し項目のオブジェクト

/*==============================================================================
  メニューホバー処理
  ============================================================================*/
function procMenuHover(){
	// オブジェクト
	$l_obj_menu = $(".c_div_menu");								// メニュー
	
	$l_obj_menu.hover(
		function(){
		// カーソルホバー時
			// メニューの背景、カーソル
			$l_obj_css = {
				cursor:				'pointer',
				backgroundImage:	'url(' + $img_hover + ')'
			}
			$(this).css($l_obj_css);
				
			// タイトルの文字色
			$obj_title = $(this).find("span.c_span_menu_inner_title");
		},
		function(){
		// カーソルアウト時
			// メニューの背景、カーソル
			$l_obj_css = {
				cursor:				'auto',
				backgroundImage:	'url(' + $img_nomal + ')'
			}
			$(this).css($l_obj_css);
				
			// タイトルの文字色
			clearInterval($interval_01);
			$obj_title.css('margin-left', '0px');
			$obj_title.css('margin-top', '0px');
		}
	);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	$obj_hidden_form = $("#id_form_hidden");
	
	// メニューホバー処理
	procMenuHover();
	
	/*-----------------------------
		メニュークリック時処理
	  -----------------------------*/
	// 勤務表
	$("#id_td_menu_attendance").bind("click", function(){
			movePage($obj_hidden_form,'attendance_report.php');
		}
	);
	
	// メニュー表示
	$(".c_div_menu").show(1000);
});