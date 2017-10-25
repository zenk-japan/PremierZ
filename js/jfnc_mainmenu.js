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
				
			// タイトルの文字
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
				
			// タイトルの文字
			$obj_title = $(this).find("span.c_span_menu_inner_title");
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
	// プロジェクト管理
	$("#id_td_menu_work").bind("click", function(){
			movePage($obj_hidden_form,'projects.php');
		}
	);
	
	// 作業状況
	$("#id_td_menu_workstat").bind("click", function(){
			movePage($obj_hidden_form,'work_status.php');
		}
	);
	
	// 作業報告
	$("#id_td_menu_workreport").bind("click", function(){
			movePage($obj_hidden_form,'../workreport/wrworkcontents.php');
		}
	);
	
	// ユーザー管理
	$("#id_td_menu_user").bind("click", function(){
			movePage($obj_hidden_form,'users_mnt.php');
		}
	);
	
	// 作業場所管理
	$("#id_td_menu_base").bind("click", function(){
			movePage($obj_hidden_form,'workplace_mnt.php');
		}
	);
	
	// 会社管理
	$("#id_td_menu_company").bind("click", function(){
			movePage($obj_hidden_form,'companies_mnt.php');
		}
	);
	
	// グループ管理
	$("#id_td_menu_group").bind("click", function(){
			movePage($obj_hidden_form,'groups_mnt.php');
		}
	);
	
	// 帳票出力
	$("#id_td_menu_list").bind("click", function(){
			movePage($obj_hidden_form,'reportmenu.php');
		}
	);
	
	// 設定情報
	$("#id_td_menu_usersetting").bind("click", function(){
			movePage($obj_hidden_form,'user_self_edit.php');
		}
	);
	
	// マニュアル
	$("#id_td_menu_manual").hover(
		function(){
			$l_obj_man = $("#id_div_menu13");
			$l_obj_man.css("position","relative");
			$l_obj_man.css("z-index","100");
			// 現在のポジション
			var $l_left = $l_obj_man.offset().left;
			var $l_top = $l_obj_man.offset().top;
			//alert($l_left+":"+$l_top);
			$l_obj_man.animate({
				top: '+=100'
			},200);
		},
		function(){
			$l_obj_man = $("#id_div_menu13");
			$l_obj_man.animate({
				top: '-=100'
			},1000);
		}
	);
	
	// メニュー表示
	$(".c_div_menu").show(1000);
});