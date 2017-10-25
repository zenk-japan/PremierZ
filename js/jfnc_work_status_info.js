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
 作業情報画面概要用javascript関数
*******************************************************************************/
var $this_page_file			= "work_status.php";			// 当画面のPHPファイル
var $userinfo_div_width		= 640;
var $userinfo_div_height	= 500;
var $userinfo_page			= "../page/userinfo.php";					// ユーザー情報表示用PHPファイル

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_work_status_common.jsで$obj_hidden_formとして取得
	
	/*-----------------------------
		ユーザー名クリック時処理
	  -----------------------------*/
	$("#id_span_arrangement_user").bind('click', function(){
		$edit_pages =	$userinfo_page +
						"?token_code=" +
						$obj_hidden_form.find("input[name='nm_token_code']").val() +
						"&user_id=" +
						$("#id_hd_work_arrangement_id").val();
		openPopup($edit_pages, 'ユーザー情報', 700, 500);
	});
});