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
 データ更新画面共通処理
*******************************************************************************/
var $debug_mode = 0;						// デバッグモード
var $message = "";							// メッセージ
var $obj_mainform = "";						// メインフォーム
var $obj_hiddenform = "";					// 隠し項目
var $obj_hd_right = "";						// ヘッダー部右のオブジェクト
var $obj_dtl_right = "";					// 明細部右のオブジェクト
var $ar_default;

/*==============================================================================
  ページ移動
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_move_to			移動先のphpファイル
  ============================================================================*/
//function movePage($p_object, $p_move_to)
//maintenance.js内に記述

/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){
	// 隠し項目のオブジェクト
	$obj_hiddenform = $("#id_form_hidden");
});
