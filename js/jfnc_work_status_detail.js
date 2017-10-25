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
 作業情報画面明細用javascript関数
*******************************************************************************/
var $this_page_file			= "work_status.php";			// 当画面のPHPファイル
var $userinfo_div_width		= 640;
var $userinfo_div_height	= 500;
var $userinfo_page			= "../page/userinfo.php";					// ユーザー情報表示用PHPファイル

/*==============================================================================
  リストホバー処理
  ============================================================================*/
function procListHoverDetail(){
	// オブジェクト
	var $l_trgt_obj = $(".c_tr_detail_list");
	
	$l_trgt_obj.hover(
		function(){
		// カーソルホバー時
			// 背景
			$(this).find("td").css("background-color", '#b0ceff');
		},
		function(){
		// カーソルアウト時
			// 背景
			$(this).find("td").css("background-color", '');
		}
	);
}

/*==============================================================================
  ユーザークリック時処理
  処理概要：
  		該当ユーザーの情報をポップアップで表示する
  引数：
  		$p_clicked_num					クリックされた明細行の行番号
  ============================================================================*/
function showUserPopup($p_clicked_num){
	$edit_pages =	$userinfo_page +
					"?token_code=" +
					$obj_hidden_form.find("input[name='nm_token_code']").val() +
					"&user_id=" +
					$("#id_hd_user_id" + $p_clicked_num).val();
	openPopup($edit_pages, 'ユーザー情報', 700, 500);
}
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_work_status_common.jsで$obj_hidden_formとして取得
	
	// リストホバー処理
	procListHoverDetail();
	
	/*-----------------------------
		ユーザー名クリック時処理
	  -----------------------------*/
	$(".c_td_detail_list_wuser").bind('click', function(){
			// クリックされたユーザーの行番号を取得
			$l_clicked_num = parseInt($(".c_td_detail_list_wuser").index(this), 10) + 1;
			
			showUserPopup($l_clicked_num);
	});
});