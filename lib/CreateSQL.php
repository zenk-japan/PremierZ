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
// ファイル名：CreateSQL
// 処理概要  ：SQL文作成の機能を持つクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class CreateSQL {
	//メンバ変数
	public $d_insert1;				//SQL文のInsert作成時に列の項目名に使われる変数
	public $d_insert2;				//SQL文のInsert作成時にvalue句に使われる変数
	public $d_update;				//SQL文のUpdate作成時にset句に使われる変数
	public $pri_column_name;		//SQL文のUpdate作成時にwhere句に使われる主キーの名前
	public $pri_column_data;		//SQL文のUpdate作成時にwhere句に使われる主キーの値
	
	function CreateSQLString($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value,$work_unit_price){
		
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
		$this->pri_column_name = $info_colum[$raw];
		
		if($entry_key == "[object Window]"){
		} else {
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				//$info_table[$num][column_name]が主キーで新規登録の場合、主キーはNULLとなる
				if($entry_key == $this->pri_column_name && $sql_type == "INSERT"){
					$entry_value = NULL;
				}
				
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//入力したデータでテーブルに挿入する為に形式を変える必要があるなら変換を行う。
				switch ($info_table[$num][data_type]){
					case "varchar":
						//郵便コード
						if ($info_table[$num][column_name]=="ZIP_CODE"){
							$str_zip = mb_convert_kana($entry_value,"a");
							//print $str_zip;
							if( mb_strpos($str_zip ,"-") == NULL && preg_match("/^\d{7}$/",$str_zip)){
								$entry_value = substr($str_zip, 0, 3)."-".substr($str_zip, 3, 4);
							} else if(preg_match("/^[0-9]{3}-?[0-9]{4}$/",$str_zip)){
								$entry_value = $str_zip;
							}
						//自宅電話番号または電話番号
						} else if ($info_table[$num][column_name] == "HOME_PHONE" || $info_table[$num][column_name] == "TELEPHONE"){
							$str_phone = mb_convert_kana($entry_value,"a");
							if(preg_match("/^0\d{1,4}-\d{1,5}-?\d{1,5}$/",$str_phone)){
								$entry_value = $str_phone;
							}
						//携帯電話番号
						} else if ($info_table[$num][column_name] == "MOBILE_PHONE"){
							$str_mphone = mb_convert_kana($entry_value,"a");
							if( mb_strpos($str_mphone ,"-") == NULL && preg_match("/^0\d0\d{8}$/",$str_mphone)){
								$entry_value = substr($str_mphone, 0, 3)."-".substr($str_mphone, 3, 4)."-".substr($str_mphone, 7, 4);
							} else if(preg_match("/^0\d0\-?\d{4}-?\d{4}$/",$str_mphone)){
								$entry_value = $str_mphone;
							}
						//メールアドレス
						} else if(substr($info_table[$num][column_name],-4,4) == "MAIL"){
							//半角小文字に変換
							$str_mail = strtolower(mb_convert_kana($entry_value,"a")); 
							if(preg_match("/^[\w\.\-\_]+@[\w\.\-\_]+$/", $str_mail)){
								$entry_value = $str_mail;
							}
						//見積コード
						} else if($info_table[$num][column_name] == "ESTIMATE_CODE"){
							$str_estimate = mb_convert_kana($entry_value,"a");
							if(preg_match("/^\d{4}-?\d{3}$/",$str_estimate) && checkdate(substr($str_estimate,2,2),'1',substr($str_estimate,0,2))){
								$entry_value = $str_estimate;
							}
						// 枝番
						} else if($info_table[$num][column_name] == "SUB_NUMBER"){
							$str_subnum = mb_convert_kana($entry_value,"a");
							$entry_value = $str_subnum;
						//フリガナ
						} else if($info_table[$num][column_name] == "KANA"){
							$str_kana = mb_convert_kana($entry_value, 'KVC');
							if(preg_match("/^[ァ-ヾ｡-ﾟ\　\ ]+$/u",$str_kana)){
								$entry_value = $str_kana;
							}
						//上記以外のコード項目
						} else if(substr($info_table[$num][column_name],-4,4) == "CODE"){
							$str_code = mb_convert_kana ($entry_value, 'a');
							if(preg_match("/^[a-zA-Z0-9\-\_]{1,50}+$/",$str_code)){
								$entry_value = $str_code;
							}
						}
						break;
					
					//日付型
					case "date":
						$str_date = mb_convert_kana($entry_value,"a");
						// 『 yyyy/m/d 』
						if(strrpos($str_date,'/') > 0 && checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$str_con_date = explode("/",$str_date);
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						// 『 yyyy-m-d 』
						} else if(strrpos($str_date,'-') > 0 && checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
							$str_con_date = explode("-",$str_date);
							$entry_value = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
						// 『 yyyymmdd 』
						} else if(checkdate(substr($str_date,4,2),substr($str_date,6,2),substr($str_date,0,4))){
							$entry_value = substr($str_date,0,4)."-".substr($str_date,4,2)."-".substr($str_date,6,2);
						}
						break;
					//時間型
					case "time":
						
						if($entry_value == NULL) {
								break;
						}
						
						//入力された時間が正しいかチェックする
						$str_time = mb_convert_kana($entry_value,"a");
						$str_replenishment = null;
						
						if(mb_strpos($str_time ,":")){
						} else {
							$str_time_len = mb_strlen( $str_time );
							
							if ($str_time_len > 6){
								$str_time = mb_substr($str_time, 0, 6);
							} else {
								for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
									$str_replenishment .= "0";
								}
							}
							$str_time	= $str_time.$str_replenishment;
						}
							
						if(strtotime($str_time) == NULL){
						} else {
								$entry_value = $str_time;
						}
						break;
				}
				//SQL文の生成
				//新規登録の場合
				
				if ($sql_type == "INSERT"){
					if ($this->d_insert1 == NULL){
						if($info_table[$num][column_name] == "WORK_UNIT_PRICE"){
							$this->d_insert1 = $info_table[$num]["column_name"];
							$this->d_insert2 = "'".$work_unit_price."'";
						}
						else {
							$this->d_insert1 = $info_table[$num]["column_name"];
							$this->d_insert2 = "'".$entry_value."'";
						}
					}else if($info_table[$num][column_name] == "DEFAULT_WORKING_TIME" || $info_table[$num][column_name] == "DEFAULT_BREAK_TIME"){
						if($entry_value == NULL){
							$this->d_insert1 = $this->d_insert1.",".$info_table[$num]["column_name"];
							$this->d_insert2 = $this->d_insert2.",null";
						}
						else{
							$this->d_insert1 = $this->d_insert1.",".$info_table[$num]["column_name"];
							$this->d_insert2 = $this->d_insert2.",'".$entry_value."'";
						}
					}else if($info_table[$num][column_name] == "WORK_UNIT_PRICE"){
						$this->d_insert1 = $this->d_insert1.",".$info_table[$num]["column_name"];
						$this->d_insert2 = $this->d_insert2.",'".$work_unit_price."'";	
					}else if($entry_value == NULL){
						$this->d_insert1 = $this->d_insert1.",".$info_table[$num]["column_name"];
						$this->d_insert2 = $this->d_insert2.",default";
					}else {
						$this->d_insert1 = $this->d_insert1.",".$info_table[$num]["column_name"];
						$this->d_insert2 = $this->d_insert2.",'".$entry_value."'";	
					}
				
				//更新の場合
				}else if ($sql_type == "UPDATE"){
					//入力項目が主キーの時
					if ($this->pri_column_name == $entry_key){
						$this->pri_column_data = $entry_value;
						return 0;
					}
					
					//このファンクションを実行するのが1回目の時
					if($this->d_update == NULL){
						if($entry_value == NULL){
							if ($info_table[$num][column_name] == "BASIC_TIME" || $info_table[$num][column_name] == "BREAK_TIME" || $info_table[$num][column_name] == "DEFAULT_WORKING_TIME" || $info_table[$num][column_name] == "DEFAULT_BREAK_TIME"){
								$this->d_update = $info_table[$num]["column_name"] ." = null";
							}else{
								$this->d_update = $info_table[$num]["column_name"] ." = default";
							}
						}else {
							$this->d_update = $info_table[$num]["column_name"] ." = '".$entry_value."'";
						}
					//このファンクションを実行するのが2回目以降の時
					}else if($entry_value == NULL){
						if ($info_table[$num][column_name] == "BASIC_TIME" || $info_table[$num][column_name] == "BREAK_TIME" || $info_table[$num][column_name] == "DEFAULT_WORKING_TIME" || $info_table[$num][column_name] == "DEFAULT_BREAK_TIME"){
							$this->d_update = $this->d_update."," .$info_table[$num]["column_name"] ." = null";
						}else{
							$this->d_update = $this->d_update."," .$info_table[$num]["column_name"] ." = default";
						}
					}else{
						$this->d_update = $this->d_update."," .$info_table[$num]["column_name"] ." = '".$entry_value."'";
					}
					
				}
			}
		}
	return 0;
	}
}
?>