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
 人員管理画面メニュー用javascript関数
*******************************************************************************/
var $this_page_file			= "workstaff.php";					// 当画面のPHPファイル
var $parent_page_file1		= "projects.php";					// 親画面のPHPファイル1
var $parent_page_file2		= "tasks.php";						// 親画面のPHPファイル2

/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 隠し項目のFORM
	// ※jfnc_projects_common.jsで$obj_hidden_formとして取得
	
	/*-----------------------------
		ボタンクリック時処理
	  -----------------------------*/
	// プロジェクト管理
	$("#id_btn_mainmenu_projects").bind("click", function(){
			movePage($obj_hidden_form, $parent_page_file1);
		}
	);
	// プロジェクト管理
	$("#id_btn_mainmenu_tasks").bind("click", function(){
			movePage($obj_hidden_form, $parent_page_file2);
		}
	);
});