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
// ファイル名：CommonCheck
// 処理概要  ：新規登録・更新の入力項目の共通チェックの機能を持つクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class CommonCheck {
	
	/*--------------------------------------------------------
	  入力項目の共通チェックモジュール
	---------------------------------------------------------*/
	function CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		$str_numeric = "";
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		
		//入力項目が正しくないときにチェックを入れる変数
		$check_code = 0;
		//エラーメッセージを格納する変数
		$error_message = "";
		
		if($entry_key == "[object Window]"){
		} else {
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				
				//$info_table[$num][column_name]が主キーで新規登録の場合、チェックは実行しない
				if($entry_key == $pri_column_name && $sql_type == "INSERT"){
					return array("Message" => $error_message, "Code" => $check_code);
				}
				
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//文字数のチェック
				if ($info_table[$num]["character_maximum_length"] == NULL || $entry_value == NULL){
				} else if (mb_strlen($entry_value,ENCODE_TYPE)<=$info_table[$num]["character_maximum_length"]) {
				
				} else {
					$check_code = 1;
				}
				
				//バイト数のチェック
				if ($info_table[$num]["character_maximum_length"] == NULL || $entry_value == NULL || strlen(mb_convert_kana($entry_value,"a"))<=$info_table[$num]["character_maximum_length"]){
				
				} else {
				//	$sql_data .= ",'".$entry_value."'\n";
					$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は".strlen(mb_convert_kana($entry_value,"a"))."文字入力されています。\n".$info_table[$num]["character_maximum_length"]."文字以内で入力して下さい。(全角は3文字分になります)"."\n";
					$check_code = 1;
				}
				
				//必須項目のチェック
				switch ($info_table[$num]["is_nullable"]) {
					
					case "NO":
					if( substr($p_table_name,-1,1) == "S"){
						if ($info_table[$num]["column_name"] == substr($p_table_name,0,-1)."_ID"){
						
						} else if ($entry_value == "") {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は必須項目です。"."\n";
							$check_code = 1;
						} else {
						}
					} else {
						if ($info_table[$num]["column_name"] == $p_table_name."_ID"){
						
						} else if ($entry_value == "") {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は必須項目です。"."\n";
							$check_code = 1;
						} else {
						
						}
					}
				}
			}
		}
		
		return array("Message" => $error_message,"Code" => $check_code);
	}
	
	/*--------------------------------------------------------
	  全角の数字を半角に変換するモジュール
	---------------------------------------------------------*/
	function ConvertNumber($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		
		mb_internal_encoding(ENCODE_TYPE);
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		
		if($entry_value==NULL){
			return $entry_value;
		}
		if($entry_key == "[object Window]"){
		} else {
			
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//data_typeの判別
				switch ($info_table[$num]["data_type"]){
					case "bigint":
					case "int":
					case "double":
					case "date":
						$str_numeric = mb_convert_kana($entry_value,"a");
						return $str_numeric;
				}
			}
		}
		
		return $entry_value;
	}
	
	/*--------------------------------------------------------
	  
	---------------------------------------------------------*/
}
?>
