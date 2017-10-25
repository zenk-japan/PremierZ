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
// ファイル名：CompanyCheck
// 処理概要  ：グループの新規登録・更新の入力項目チェックを行うクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class CompanyCheck {
	
	// 入力項目のチェック
	function CompanyDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		
		mb_internal_encoding(ENCODE_TYPE);
		
		//入力項目が正しくないときにチェックを入れる変数
		$check_code = 0;
		//エラーメッセージを格納する変数
		$error_message = "";
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		if($entry_value==NULL){
			return array("Message" => $error_message,"Code" => $check_code);
		}
		if($entry_key == "[object Window]"){
		} else {
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//data_typeの判別
				switch ($info_table[$num]["data_type"]){
					case "varchar":
						if(substr($info_table[$num]["column_name"],-4,4) == "CODE"){
							$str_code = mb_convert_kana ($entry_value, 'a');
							if(preg_match("/^[a-zA-Z0-9\-\_]{1,50}+$/",$str_code)){
								$entry_value = $str_code;
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」で使える文字は英数字と「-」、「_」です。"."\n";
								$check_code = 1;
							}
						}else{
						}
						
						return array("Message" => $error_message,"Code" => $check_code);
				}
			}
		}
		
		return array("Message" => $error_message,"Code" => $check_code);
	}
}
?>