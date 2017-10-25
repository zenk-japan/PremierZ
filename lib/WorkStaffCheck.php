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
// ファイル名：WorkStaffCheck
// 処理概要  ：作業人員の新規登録・更新の入力項目チェックを行うクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class WorkStaffCheck {
	
	// 入力項目のチェック
	function WorkStaffDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
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
		
		//入力項目が正しくないときにチェックを入れる変数
		$check_code = 0;
		//エラーメッセージを格納する変数
		$error_message = "";
		
		if($entry_value==NULL){
			return array("Message" => $error_message,"Code" => $check_code);
		}
		if($entry_key == "[object Window]"){
		} else {
			// 計算用
			//支払区分
			if($entry_key == "WORK_PAYMENT_DIVISION"){
				$work_payment_division = $entry_value;
			//分類区分
			} else if($entry_key == "WORK_CLASSIFICATION_DIVISION"){
				$classification_division = $entry_value;
			}
			
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//data_typeの判別
				switch ($info_table[$num]["data_type"]){
					//文字型
					case "varchar":
						if($info_table[$num][column_name] == "ENTERING_MANAGE_TIMET" || $info_table[$num][column_name] == "LEAVE_MANAGE_TIMET" || $info_table[$num][column_name] == "ENTERING_SCHEDULE_TIMET" || $info_table[$num][column_name] == "LEAVE_SCHEDULE_TIMET"){
							//入力された時間が正しいかチェックする
							$str_time = mb_convert_kana($entry_value,"a");
							$str_replenishment = null;
							
							if(mb_strpos($str_time ,":")){
								$array_time = preg_split("/\:/",$entry_value);
								
								if($array_time[0] > 23){
									$array_time[0] = $array_time[0] - 24;
									$str_time = $array_time[0].":".$array_time[1];
								}
							} else{
								$str_time_len = mb_strlen( $str_time );
								
								if ($str_time_len == 4){
									for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
										$str_replenishment .= "0";
									}
								} else {
									$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
									$check_code = 1;
									return array("Message" => $error_message,"Code" => $check_code);
								}
								
								$array_time[0]	= substr($str_time,0,2);
								$array_time[1]	= substr($str_time,-2,2);
								if($array_time[0] > 23){
									$array_time[0] = $array_time[0] - 24;
								}
								$str_time		= $array_time[0].$array_time[1].$str_replenishment;
								
							}
							
							if(strtotime($str_time) == NULL){
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
								$check_code = 1;
							}
							
						}
						return array("Message" => $error_message,"Code" => $check_code);
					
					//日付型
					case "date":
						$str_date = mb_convert_kana($entry_value,"a");
						// 『 yyyy/m/d 』
						if(strrpos($str_date,'/') > 0 ){
							$str_con_date = explode("/",$str_date);
							if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
								$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
								$check_code = 1;
							}
						// 『 yyyy-m-d 』
						} else if(strrpos($str_date,'-') > 0 ){
							
							$str_con_date = explode("-",$str_date);
							if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
								$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
								$check_code = 1;
							}
						} else {
							// 『 yyyymmdd 』
							if(checkdate(substr($str_date,4,2),substr($str_date,6,2),substr($str_date,0,4))){
								$entry_value = substr($str_date,0,4)."-".substr($str_date,4,2)."-".substr($str_date,6,2);
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
								$check_code = 1;
							}
						}
							
						return array("Message" => $error_message,"Code" => $check_code);
					//数字型
					case "bigint":
						$str_numeric = mb_convert_kana($entry_value,"a");
						if(is_numeric($str_numeric) || $str_numeric == NULL){
							$entry_value  = $str_numeric;
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
							$check_code = 1;
						}
						return array("Message" => $error_message,"Code" => $check_code);
						
					case "int":
						$str_numeric = mb_convert_kana($entry_value,"a");
						if(is_numeric($str_numeric) || $str_numeric == NULL){
							$entry_value  = $str_numeric;
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
							$check_code = 1;
						}
						
						return array("Message" => $error_message,"Code" => $check_code);
						
					case "double":
						
						$str_numeric = explode(".",mb_convert_kana($entry_value,"a"));
							
						if(ctype_digit($str_numeric[0])){
							if(ctype_digit($str_numeric[1]) || $str_numeric[1] == NULL){
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
								$check_code = 1;
							}
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
							$check_code = 1;
						}
						
						return array("Message" => $error_message,"Code" => $check_code);
						
					//時間型
					case "time":
						//入力された時間が正しいかチェックする
						$str_time = mb_convert_kana($entry_value,"a");
						$str_replenishment = null;
						
						if(mb_strpos($str_time ,":")){
						} else {
							$str_time_len = mb_strlen( $str_time );
							
							if ($str_time_len == 4){
								for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
									$str_replenishment .= "0";
								}
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
								$check_code = 1;
								return array("Message" => $error_message,"Code" => $check_code);
							}
							$str_time	= $str_time.$str_replenishment;
						}
						
						if(strtotime($str_time) == NULL){
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
							$check_code = 1;
						}
						
						return array("Message" => $error_message,"Code" => $check_code);
						
				}
			}
		}
		
		return array("Message" => $error_message,"Code" => $check_code);
	}
	
	// 入力項目のチェック(Mobile)
	function WorkStaffMobileDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		$check_code		=	0;					//入力項目が正しくないときにチェックを入れる変数
		$error_message	=	NULL;				//エラーメッセージを格納する変数
		
		mb_internal_encoding(ENCODE_TYPE);
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		
		if($entry_value==NULL){
			return array("Message" => $error_message,"Code" => $check_code);
		}
		
		//入力項目名がチェック項目に該当するか判別する
		if(array_search($entry_key,$info_colum)){
			//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
			$num = array_search($entry_key,$info_colum);
			
			//data_typeの判別
			switch ($info_table[$num]["data_type"]){
				// 文字列
				case "varchar":
					// 承認区分
					if($entry_key == "APPROVAL_DIVISION"){
						if($entry_value == "AP" || $entry_value == "NO"){
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」を選択して下さい。"."\n";
							$check_code = 1;
						}
					}else if($info_table[$num][column_name] == "DISPATCH_SCHEDULE_TIMET"){
						//入力された時間が正しいかチェックする
						$str_time = mb_convert_kana($entry_value,"a");
						$str_replenishment = null;
						
						if(mb_strpos($str_time ,":")){
							$array_time = preg_split("/\:/",$entry_value);
							
							if($array_time[0] > 23){
								$array_time[0] = $array_time[0] - 24;
								$str_time = $array_time[0].":".$array_time[1];
							}
						} else{
							$str_time_len = mb_strlen( $str_time ,"UTF-8");
							
							if ($str_time_len == 4){
								for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
									$str_replenishment .= "0";
								}
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
								$check_code = 1;
								return array("Message" => $error_message,"Code" => $check_code);
							}
							
							$array_time[0]	= substr($str_time,0,2);
							$array_time[1]	= substr($str_time,-2,2);
							if($array_time[0] > 23){
								$array_time[0] = $array_time[0] - 24;
							}
							$str_time		= $array_time[0].$array_time[1].$str_replenishment;
							
						}
						
						if(strtotime($str_time) == NULL){
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
							$check_code = 1;
						}
							
					}
					
					return array("Message" => $error_message,"Code" => $check_code);
				
				// 日付型
				case "date":
					$str_date = mb_convert_kana($entry_value,"a");
					// 『 yyyy/m/d 』
					if(strrpos($str_date,'/') > 0 ){
						$str_con_date = explode("/",$str_date);
						if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					// 『 yyyy-m-d 』
					} else if(strrpos($str_date,'-') > 0 ){
						
						$str_con_date = explode("-",$str_date);
						if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					} else {
						// 『 yyyymmdd 』
						if(checkdate(substr($str_date,4,2),substr($str_date,6,2),substr($str_date,0,4))){
							$entry_value = substr($str_date,0,4)."-".substr($str_date,4,2)."-".substr($str_date,6,2);
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					}
						
					return array("Message" => $error_message,"Code" => $check_code);
				
				//数字型
				case "bigint":
				case "int":
					$str_numeric = mb_convert_kana($entry_value,"a");
					if(is_numeric($str_numeric) || $str_numeric == NULL){
						$entry_value  = $str_numeric;
					} else {
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
						$check_code = 1;
					}
					return array("Message" => $error_message,"Code" => $check_code);
					
				case "double":
					$str_numeric = explode(".",mb_convert_kana($entry_value,"a"));
						
					if(ctype_digit($str_numeric[0])){
						if(ctype_digit($str_numeric[1]) || $str_numeric[1] == NULL){
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
							$check_code = 1;
						}
					} else {
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
						$check_code = 1;
					}
					
					return array("Message" => $error_message,"Code" => $check_code);
					
				//時間型
				case "time":
					//入力された時間が正しいかチェックする
					$str_time = mb_convert_kana($entry_value,"a");
					$str_replenishment = null;
					
					// 出発予定時間
					if($entry_key == "DISPATCH_SCHEDULE_TIMET"){
						if($entry_value == NULL){
							return array("Message" => $error_message,"Code" => $check_code);
						}
					}
					
					if(mb_strpos($str_time ,":")){
					} else {
						$str_time_len = mb_strlen( $str_time );
						
						if ($str_time_len == 4){
							for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
								$str_replenishment .= "0";
							}
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
							$check_code = 1;
							return array("Message" => $error_message,"Code" => $check_code);
						}
						$str_time	= $str_time.$str_replenishment;
					}
					
					if(strtotime($str_time) == NULL){
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
						$check_code = 1;
					}
					return array("Message" => $error_message,"Code" => $check_code);
			}
		}
	}
	
	// 入力項目のチェック(Mobile)代理登録用
	function WorkStaffDetailMobileDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		$check_code		=	0;					//入力項目が正しくないときにチェックを入れる変数
		$error_message	=	NULL;				//エラーメッセージを格納する変数
		
		mb_internal_encoding(ENCODE_TYPE);
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		
		if($entry_value==NULL){
			return array("Message" => $error_message,"Code" => $check_code);
		}
		
		//入力項目名がチェック項目に該当するか判別する
		if(array_search($entry_key,$info_colum)){
			//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
			$num = array_search($entry_key,$info_colum);
			
			//data_typeの判別
			switch ($info_table[$num]["data_type"]){
				// 文字列
				case "varchar":
					// 承認区分
					if($entry_key == "APPROVAL_DIVISION"){
						if($entry_value == "AP" || $entry_value == "NO"){
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」を選択して下さい。"."\n";
							$check_code = 1;
						}
					}else if($info_table[$num][column_name] == "DISPATCH_STAFF_TIMET" || $info_table[$num][column_name] == "ENTERING_STAFF_TIMET" || $info_table[$num][column_name] == "LEAVE_STAFF_TIMET"){
						//入力された時間が正しいかチェックする
						$str_time = mb_convert_kana($entry_value,"a");
						$str_replenishment = null;
						
						if(mb_strpos($str_time ,":")){
							$array_time = preg_split("/\:/",$entry_value);
							
							if($array_time[0] > 23){
								$array_time[0] = $array_time[0] - 24;
								$str_time = $array_time[0].":".$array_time[1];
							}
						} else{
							$str_time_len = mb_strlen( $str_time );
							
							if ($str_time_len == 4){
								for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
									$str_replenishment .= "0";
								}
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
								$check_code = 1;
								return array("Message" => $error_message,"Code" => $check_code);
							}
							
							$array_time[0]	= substr($str_time,0,2);
							$array_time[1]	= substr($str_time,-2,2);
							if($array_time[0] > 23){
								$array_time[0] = $array_time[0] - 24;
							}
							$str_time		= $array_time[0].$array_time[1].$str_replenishment;
							
						}
						
						if(strtotime($str_time) == NULL){
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
							$check_code = 1;
						}
							
					}
					
					return array("Message" => $error_message,"Code" => $check_code);
				
				// 日付型
				case "date":
					$str_date = mb_convert_kana($entry_value,"a");
					// 『 yyyy/m/d 』
					if(strrpos($str_date,'/') > 0 ){
						$str_con_date = explode("/",$str_date);
						if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					// 『 yyyy-m-d 』
					} else if(strrpos($str_date,'-') > 0 ){
						
						$str_con_date = explode("-",$str_date);
						if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					} else {
						// 『 yyyymmdd 』
						if(checkdate(substr($str_date,4,2),substr($str_date,6,2),substr($str_date,0,4))){
							$entry_value = substr($str_date,0,4)."-".substr($str_date,4,2)."-".substr($str_date,6,2);
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
							$check_code = 1;
						}
					}
						
					return array("Message" => $error_message,"Code" => $check_code);
				
				//数字型
				case "bigint":
				case "int":
					$str_numeric = mb_convert_kana($entry_value,"a");
					if(is_numeric($str_numeric) || $str_numeric == NULL){
						$entry_value  = $str_numeric;
					} else {
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
						$check_code = 1;
					}
					return array("Message" => $error_message,"Code" => $check_code);
					
				case "double":
					$str_numeric = explode(".",mb_convert_kana($entry_value,"a"));
						
					if(ctype_digit($str_numeric[0])){
						if(ctype_digit($str_numeric[1]) || $str_numeric[1] == NULL){
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
							$check_code = 1;
						}
					} else {
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は数字を入力して下さい。"."\n";
						$check_code = 1;
					}
					
					return array("Message" => $error_message,"Code" => $check_code);
					
				//時間型
				case "time":
					//入力された時間が正しいかチェックする
					$str_time = mb_convert_kana($entry_value,"a");
					$str_replenishment = null;
					
					// 出発予定時間
					if($entry_key == "DISPATCH_SCHEDULE_TIMET"){
						if($entry_value == NULL){
							return array("Message" => $error_message,"Code" => $check_code);
						}
					}
					
					if(mb_strpos($str_time ,":")){
					} else {
						$str_time_len = mb_strlen( $str_time );
						
						if ($str_time_len == 4){
							for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
								$str_replenishment .= "0";
							}
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
							$check_code = 1;
							return array("Message" => $error_message,"Code" => $check_code);
						}
						$str_time	= $str_time.$str_replenishment;
					}
					
					if(strtotime($str_time) == NULL){
						$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はmm:ss形式で時間を入力して下さい。"."\n";
						$check_code = 1;
					}
					return array("Message" => $error_message,"Code" => $check_code);
			}
		}
	}
}
?>