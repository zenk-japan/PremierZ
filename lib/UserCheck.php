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
// ファイル名：UserCheck
// 処理概要  ：ユーザの新規登録・更新の入力項目チェックを行うクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class UserCheck {
	
	public $encrypition;
	// 入力項目のチェック
	function UserDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value){
		
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
					//郵便コード
						if ($info_table[$num]["column_name"]=="ZIP_CODE"){
							$str_zip = mb_convert_kana($entry_value,"a");
							if( mb_strpos($str_zip ,"-") == NULL ){
								if(preg_match("/^\d{7}$/",$str_zip)){
									$entry_value = substr($str_zip, 0, 3)."-".substr($str_zip, 3, 4);
								} else {
									$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はnnn-nnnn形式で数字を入力して下さい。"."\n";
									$check_code = 1;
								}
							} else {
								if(preg_match("/^[0-9]{3}-?[0-9]{4}$/",$str_zip)){
									$entry_value = $str_zip;
								} else {
									$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はnnn-nnnn形式で数字を入力して下さい。"."\n";
									$check_code = 1;
								}
							}
						
						//自宅電話番号または電話番号
						} else if ($info_table[$num]["column_name"] == "HOME_PHONE" || $info_table[$num]["column_name"] == "TELEPHONE"){
							$str_phone = mb_convert_kana($entry_value,"a");
							if(preg_match("/^0\d{1,4}-\d{1,5}-?\d{1,5}$/",$str_phone)){
								$entry_value = $str_phone;
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は○○-○○-○○形式で数字を入力して下さい。(各○○は5桁以内)"."\n";
								$check_code = 1;
							}
						
						//携帯電話番号
						} else if ($info_table[$num]["column_name"] == "MOBILE_PHONE"){
							$str_mphone = mb_convert_kana($entry_value,"a");
						//	print $str_mphone;
							if( mb_strpos($str_mphone ,"-") == NULL ){
								if(preg_match("/^0\d0\d{8}$/",$str_mphone)){
									$entry_value = substr($str_mphone, 0, 3)."-".substr($str_mphone, 3, 4)."-".substr($str_mphone, 7, 4);
								} else {
									$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はnnn-nnnn-nnnn形式で数字を入力して下さい。(ハイフンはなくても可)"."\n";
									$check_code = 1;
								}
							} else {
								if(preg_match("/^0\d0\-?\d{4}-?\d{4}$/",$str_mphone)){
									$entry_value = $str_mphone;
								} else {
									$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はnnn-nnnn-nnnn形式で数字を入力して下さい。(ハイフンはなくても可)"."\n";
									$check_code = 1;
								}
							}
						
						//メールアドレス
						} else if(substr($info_table[$num]["column_name"],-4,4) == "MAIL"){
							//入力部分に@が含まれているか
							//半角小文字に変換
							$str_mail = strtolower(mb_convert_kana($entry_value,"a")); 
							if(preg_match("/^[\w\.\-\_]+@[\w\.\-\_]+$/", $str_mail)){
								$entry_value = $str_mail;
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はxxx@xx形式で英数字を入力して下さい。"."\n";
								$check_code = 1;
								
							}
						
						//口座番号
						} else if($info_table[$num]["column_name"] == "ACCOUNT_NUMBER"){
							if(preg_match("/^\d{1,10}-?\d{1,10}-?\d{1,10}$/",mb_convert_kana($entry_value,"a"))){
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は○○-○○-○○形式で数字を入力して下さい。(各○○は10桁以内)"."\n";
								$check_code = 1;
							}
						
						//パスワード
						} else if($info_table[$num]["column_name"] == "PASSWORD"){
							$str_pass = mb_convert_kana ($entry_value, 'a');
							$entry_value = $str_pass;
							if(preg_match("/^[a-zA-Z0-9\!\#\$\%\&\(\)\*\+\,\-\.\_\/]{1,50}+$/",$str_pass)){
								//暗号化
								$this->encrypition = md5($str_pass);
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」は50文字以内の半角英数字と記号［!#$%&()*/+-_,.］で入力して下さい。"."\n";
								$check_code = 1;
							}
						
						//フリガナ
						} else if($info_table[$num]["column_name"] == "KANA"){
							$str_kana = mb_convert_kana($entry_value, 'KVC');
							if(preg_match("/^[ァ-ヾ｡-ﾟ\　\ ]+$/u",$str_kana)){
								$entry_value = $str_kana;
							} else {
								$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はカタカナを入力して下さい。"."\n";
								$check_code = 1;
							}
							
						//上記以外のコード項目
						}else if(substr($info_table[$num]["column_name"],-4,4) == "CODE"){
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
							
					//日付時間
					case "datetime":
						//日付と時間を変数に分けてそれぞれ形式が正しく入力されているかチェックする
						$array = explode(" ",mb_convert_kana($entry_value,"a"));
						//日付の確認
						if(mb_strlen($array[0],"UTF-8") == 8){
							if(substr($array[0],5,1) == "-" && substr($array[0],8,1) == "-"){
								//入力した日を年、月、日に分割し配列に導入する
								$array1 = explode("-",$array[0]);
								if(checkdate($array1[1],$array1[2],$array1[0])){
									//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =1;
								} else {
									//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =0;
								}
							} else if(substr($array[0],5,1) == "/" && substr($array[0],8,1) == "/"){
								//入力した日を年、月、日に分割し配列に導入する
								$array1 = explode("/",$array[0]);
								if(checkdate($array1[1],$array1[2],$array1[0])){
									//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =1;
								} else {
									//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =0;
								}
							} else {
								//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =0;
							}
						} else {
							//変数$kは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$k =0;
						}
						//時間の確認
						$array2 = explode(":",$array[1]);
						if(ctype_digit($array2[0]) && ctype_digit($array2[1])){
							if($array2[2] == null && $array2[0] <= "23" && $array2[1] <= "59"){
								//変数$lは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$l =1;
							} else {
								//変数$lは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$l =0;
							}
						} else {
							//変数$lは最後に別々の配列に分けた日付と時間の入力形式が両方とも正しかったかチェックするのに使用する
									$l =0;
						}
						
						if($k == 1 && $l == 1){
	//						print $cchk->getColumnNameJ($entry_key)."は日付時間です。"."\n";
						} else if ($k == 0 && $l == 1){
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」の日付はyyyy-mm-dd形式で入力して下さい。"."\n";
							$check_code = 1;
						} else if ($k == 1 && $l == 0){
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」の時間はmm:ss形式で入力して下さい。"."\n";
							$check_code = 1;
						} else {
							$error_message = "「".$cchk->getColumnNameJ($entry_key)."」はyyyy-mm-dd mm:ss形式で日時を入力して下さい。"."\n";
							$check_code = 1;
						}
						return array("Message" => $error_message,"Code" => $check_code);

				}
			}
		}
		
		return array("Message" => $error_message,"Code" => $check_code);
	}
}
?>