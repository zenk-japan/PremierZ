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
 メニュー用javascript関数
*******************************************************************************/
var $debug_mode = 0;						// デバッグモード
var $message = "";							// メッセージ
var $obj_mainform = "";						// メインフォーム
var $obj_hiddenform = "";					// 隠し項目

/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
//maintenance.js内に記述

/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(document).ready(function(){
	$obj_mainform = $("#id_form_main");
	$obj_hiddenform = $("#id_form_hidden");
	
	//==============================================
	// 各ボタンクリック時処理
	//==============================================
	// 利用会社
	$("#id_use_company").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "useCompany.php");
	});
	// 共通マスタ
	$("#id_common_master").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "commonMaster.php");
	});
	// 権限
	$("#id_authority").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "authority.php");
	});
	// 値リスト
	$("#id_value_list").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "valueList.php");
	});
	// 画面利用管理
	$("#id_page_using_conf").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "pageUsingConf.php");
	});
	// メールログ
	$("#id_mail_log").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "mailLog.php");
	});
	// ログインログ
	$("#id_login_log").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "loginLog.php");
	});
	// システム管理者変更
	$("#id_sysadmin_mnt").bind("click", function(){
		// 画面移動
		movePage($obj_hiddenform, "sysadminMnt.php");
	});
});
