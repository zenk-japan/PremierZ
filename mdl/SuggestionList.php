<?php
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


// *****************************************************************************
// ファイル名：SuggestionList
// 処理概要：入力補完用リスト
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');
	$l_getpname_table	= "tableName";						// GET引数名のテーブル名
	$l_getpname_item	= "itemName";						// GET引数名の項目名
	$l_getpname_dataid	= "dataId";							// GET引数名のデータID
	$l_getpname_input	= "q";								// GET引数名の入力値
	$l_view_name		= null;								// ビュー名
	$l_ar_condition		= null;								// 条件用配列バッファ
	$l_ar_orderby		= null;								// ORDER BY句用配列バッファ
	$l_result_rec		= null;								// 取得したデータレコード
	$l_ar_items			= null;								// リスト用配列
	
	// 入力した文字列
	$q = strtolower($_REQUEST[$l_getpname_input]);
	if (!$q) return;

	// GET引数加工
	foreach($_GET as $key => $value){
		
		switch($key){
			case $l_getpname_table:
				// テーブル名の場合は、ビュー名に変換
				$l_view_name	= strtoupper($_GET[$l_getpname_table]."_v");
				break;
				
			case $l_getpname_item:
				// 項目名はそのまま
				break;
				
			case $l_getpname_input:
				// 入力値はそのまま
				break;
				
			case $l_getpname_dataid:
				// DATA_IDは条件配列バッファにセット
				$l_ar_condition["DATA_ID"]	= $value;
				break;
			default:
				// その他は条件配列にセット
				$l_ar_condition[$key]	= $value;
				break;
		}
	}
	
	// リスト項目取得
	require_once('../mdl/ModelCommon.php');
	$dbobj = new ModelCommon($l_view_name);
	
	// 条件セット
	$l_cond_ret = $dbobj->setCondition($l_ar_condition);
	
	// order by配列セット
	$l_ar_orderby = array(
						"DATA_ID",
						$_GET[$l_getpname_item]
						);
	
	// order byセット
	$l_orderby_ret = $dbobj->setOrderbyPhrase($l_ar_orderby);
	
	// レコード取得
	$l_result_rec = $dbobj->getRecord();
	
	// 項目を配列に設定
	$lr_col_value_buff = array();	// 配列初期化
	$lr_col_value      = array();	// 配列初期化
	$l_col_value_cnt = 0;
	foreach($l_result_rec as $col_num => $col_rec){
		foreach($col_rec as $col_key => $col_value){
			if(strtoupper($col_key)==strtoupper($_GET[$l_getpname_item])){
				$l_col_value_cnt ++;
				$lr_col_value_buff[$l_col_value_cnt] = $col_value;
			}
		}
	}
	// 配列から重複を削除
	$lr_col_value = array_unique($lr_col_value_buff);
	
	// 配列を並べ替え
	sort($lr_col_value);
	
	// リストを出力
	$l_col_value_cnt = 0;
	foreach($lr_col_value as $key => $value){
		// 該当項目名のキーを含む値を表示する
		if (strpos(strtolower($value), $q) !== false){
			echo "$value\n";
		}
	}
?>