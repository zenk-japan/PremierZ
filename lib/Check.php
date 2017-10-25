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
// ファイル名：Check
// 処理概要  ：新規登録・更新の入力項目のチェックの機能を持つクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class Check {
	// 入力項目のチェック＆SQLの作成
	function DataCheck($p_table_name,$sql_type){
		$multibytecnt = 3;				// 全角文字のバイト数
		
		mb_internal_encoding('UTF-8');
		
		require_once('../mdl/m_column_info.php');
		$cchk = new ColumnInfo(strtoupper($p_table_name));
		$column_chk = $cchk->getColumnChk();
		
		$rcnt = 0;
		foreach ($column_chk as $column_chk1) {
			$rcnt = $rcnt + 1;
			$column_chk[$rcnt] = $column_chk1;
		}
		
	//	print "<hr>".count($column_chk)."<hr>";														// debug
	//	print_r($column_chk);
		//二次元配列$column_chkのcolumn_nameの列を別の配列に移す
		$i=1;
		for( $i = 1; $i<=count($column_chk); $i++ ){
			$info_colum[$i]	= $column_chk[$i]['COLUMN_NAME'];
			//$info_colum[$i]	= $column_chk[$i]['COLUMN_COMMENT'];
			$info_key[$i]	= $column_chk[$i]['COLUMN_KEY'];
			$info_table[$i]	= array( column_name				=>$column_chk[$i]['COLUMN_NAME']
									,data_type					=>$column_chk[$i]['DATA_TYPE']
									,character_maximum_length	=>$column_chk[$i]['CHARACTER_MAXIMUM_LENGTH']
									,is_nullable				=>$column_chk[$i]['IS_NULLABLE']
									,column_default				=>$column_chk[$i]['COLUMN_DEFAULT']);
	//		print "<br>".$info_table[$i]['COLUMN_NAME'].","											// debug
	//					.$info_table[$i]['DATA_TYPE'].","												// debug
	//					.$info_table[$i]['CHARACTER_MAXIMUM_LENGTH'].","								// debug
	//					.$info_table[$i]['IS_NULLABLE'].","											// debug
	//					.$info_table[$i]['COLUMN_DEFAULT'];											// debug
		}
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		
	//	print "<br>Primary key = ".$pri_column_name;												// debug
	//	print "件数は".count($_POST["update$i"]);													// debug
	//	print "<HR><HR>Check.phpでの値です。<BR>";													// debug
	//	print "<hr>POST<BR>";																		// debug
	//	print_r($_POST);																			// debug
	//	print "<hr>GET<BR>";																		// debug
	//	print_r($_GET);																				// debug
	//	print "<hr>REQUEST<BR>";																	// debug
	//	print_r($_REQUEST);																			// debug
		
		// チェック部分
		$i = 1;
		for( $i = 1; $i<=NUMBER_OF_POST; $i++ ){
			if($_POST["update$i"][0] == "[object Window]"){
			} else {
	//			print "<hr>".$i."件目<br>".$cchk->getColumnNameJ($_POST["update$i"][0]).",".$_POST["update$i"][1]."<BR>";	// debug
				
				// 計算用
				//支払区分
				if($_POST["update$i"][0] == "WORK_PAYMENT_DIVISION"){
					$work_payment_division = $_POST["update$i"][1];
	//				print "<hR>支払区分を格納します。：".$work_payment_division."<BR>";
				//分類区分
				} else if($_POST["update$i"][0] == "WORK_CLASSIFICATION_DIVISION"){
					$classification_division = $_POST["update$i"][1];
	//				print "<hR>分類区分を格納します。：".$classification_division."<BR>";
				}
				
				//入力項目名がチェック項目に該当するか判別する
				if(array_search($_POST["update$i"][0],$info_colum)){
					//$info_table[$num]['COLUMN_NAME']が主キーで新規登録の場合、主キーはNULLとなる
					if($_POST["update$i"][0] == $pri_column_name && $sql_type == "INSERT"){
						$_POST["update$i"][1] = NULL;
						continue;
					}
					//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
					$num = array_search($_POST["update$i"][0],$info_colum);
	//				print $num."番目<BR>";
					//入力が未記入で、その項目が未記入で問題ない場合は次のループにいく
					if ($_POST["update$i"][1]==NULL){
						if ($info_table[$num]['COLUMN_DEFAULT'] == NULL) {
							if ($info_table[$num]['IS_NULLABLE']=="YES") {
								if ($sql_type == "INSERT"){
									if (is_null($d_insert1)){
										$d_insert1 = $info_table[$num]['COLUMN_NAME'];
										$d_insert2 = "NULL";	
									} else {
										$d_insert1 .= ",".$info_table[$num]['COLUMN_NAME'];
										$d_insert2 .= ",NULL";	
									}
								//print "<HR>".$d_insert1."<BR>".$d_insert2;
								//更新の場合
								} else if ($sql_type == "UPDATE"){
									if (is_null($d_update)){
										$d_update = $info_table[$num]['COLUMN_NAME'] ." = NULL";
									} else {
										$d_update .= "," .$info_table[$num]['COLUMN_NAME'] ." = NULL";
									}
								}
			//					print "<HR>".$d_update;
	//							print "OK"."<br>";
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."はNULLです。"."<br>";
								continue;
							}
						} else {
							$_POST["update$i"][1] = $info_table[$num]['COLUMN_DEFAULT'];
	//						print $cchk->getColumnNameJ($_POST["update$i"][0])."はDEFAULT値を入力します。"."<br>";
						}
					}
					
					//入力項目が正しくないときにチェックを入れる変数
					$check_code = 0;
					
					//data_typeの判別
					switch ($info_table[$num]['DATA_TYPE']){
						case "varchar":
							//郵便コード
							if ($info_table[$num]['COLUMN_NAME']=="ZIP_CODE"){
								$str_zip = mb_convert_kana($_POST["update$i"][1],"a");
								//print $str_zip;
								if( mb_strpos($str_zip ,"-") == NULL ){
									if(preg_match("/^\d{7}$/",$str_zip)){
										$_POST["update$i"][1] = substr($str_zip, 0, 3)."-".substr($str_zip, 3, 4);
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はnnn-nnnn形式で数字を入力して下さい。"."\n";
										$check_code = 1;
									}
								} else {
									if(preg_match("/^[0-9]{3}-?[0-9]{4}$/",$str_zip)){
										$_POST["update$i"][1] = $str_zip;
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はnnn-nnnn形式で数字を入力して下さい。"."\n";
										$check_code = 1;
									}
								}
								
							//自宅電話番号または電話番号
							} else if ($info_table[$num]['COLUMN_NAME'] == "HOME_PHONE" || $info_table[$num]['COLUMN_NAME'] == "TELEPHONE"){
								$str_phone = mb_convert_kana($_POST["update$i"][1],"a");
								if(preg_match("/^0\d{1,4}-\d{1,5}-?\d{1,5}$/",$str_phone)){
									$_POST["update$i"][1] = $str_phone;
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は○○-○○-○○形式で数字を入力して下さい。(各○○は5桁以内)"."\n";
									$check_code = 1;
								}
							
							//携帯電話番号
							} else if ($info_table[$num]['COLUMN_NAME'] == "MOBILE_PHONE"){
								$str_mphone = mb_convert_kana($_POST["update$i"][1],"a");
							//	print $str_mphone;
								if( mb_strpos($str_mphone ,"-") == NULL ){
									if(preg_match("/^0\d0\d{8}$/",$str_mphone)){
										$_POST["update$i"][1] = substr($str_mphone, 0, 3)."-".substr($str_mphone, 3, 4)."-".substr($str_mphone, 7, 4);
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はnnn-nnnn-nnnn形式で数字を入力して下さい。(ハイフンはなくても可)"."\n";
										$check_code = 1;
									}
								} else {
									if(preg_match("/^0\d0\-?\d{4}-?\d{4}$/",$str_mphone)){
										$_POST["update$i"][1] = $str_mphone;
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はnnn-nnnn-nnnn形式で数字を入力して下さい。(ハイフンはなくても可)"."\n";
										$check_code = 1;
									}
								}
							
							//メールアドレス
							} else if(substr($info_table[$num]['COLUMN_NAME'],-4,4) == "MAIL"){
								//入力部分に@が含まれているか
								//半角小文字に変換
								$str_mail = strtolower(mb_convert_kana($_POST["update$i"][1],"a")); 
								if(preg_match("/^[\w\.\-\_]+@[\w\.\-\_]+$/", $str_mail)){
									$_POST["update$i"][1] = $str_mail;
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はxxx@xx形式で英数字を入力して下さい。"."\n";
									$check_code = 1;
									
								}
							
							//口座番号
							} else if($info_table[$num]['COLUMN_NAME'] == "ACCOUNT_NUMBER"){
								if(preg_match("/^\d{1,10}-?\d{1,10}-?\d{1,10}$/",mb_convert_kana($_POST["update$i"][1],"a"))){
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は○○-○○-○○形式で数字を入力して下さい。(各○○は10桁以内)"."\n";
									$check_code = 1;
								}
							
							//見積コード
							} else if($info_table[$num]['COLUMN_NAME'] == "ESTIMATE_CODE"){
								$str_estimate = mb_convert_kana($_POST["update$i"][1],"a");
								if(preg_match("/^\d{4}-?\d{3}$/",$str_estimate)){
									
									if(checkdate(substr($str_estimate,2,2),'1',substr($str_estimate,0,2))){
										$_POST["update$i"][1] = $str_estimate;
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyymm-nnn形式で数字を入力して下さい。"."\n";
										$check_code = 1;
									}
		//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyymm-nnn形式で数字を入力して下さい。"."\n";
									$check_code = 1;
								}
							
							// 枝番
							} else if($info_table[$num]['COLUMN_NAME'] == "SUB_NUMBER"){
								$str_subnum = mb_convert_kana($_POST["update$i"][1],"a");
								$_POST["update$i"][1] = $str_subnum;
							
							//パスワード
							} else if($info_table[$num]['COLUMN_NAME'] == "PASSWORD"){
								$str_pass = mb_convert_kana ($_POST["update$i"][1], 'a');
								$_POST["update$i"][1] = $str_pass;
							//	print $str_pass;
								//if(preg_match("/^[a-zA-Z0-9\-\/\_]{1,50}+$/",$str_pass)){
								if(preg_match("/^[a-zA-Z0-9\!\#\$\%\&\(\)\*\+\,\-\.\_\/]{1,50}+$/",$str_pass)){
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は英数字です。"."\n";
									//暗号化
									$encrypition = md5($str_pass);
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は50文字以内の半角英数字と記号［!#$%&()*/+-_,.］で入力して下さい。"."\n";
									$check_code = 1;
								}
							
							//フリガナ
							} else if($info_table[$num]['COLUMN_NAME'] == "KANA"){
								$str_kana = mb_convert_kana($_POST["update$i"][1], 'KVC');
								if(preg_match("/^[ァ-ヾ｡-ﾟ\　\ ]+$/u",$str_kana)){
									$_POST["update$i"][1] = $str_kana;
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."はカタカナです。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はカタカナを入力して下さい。"."\n";
									$check_code = 1;
								}
								
							//上記以外のコード項目
							} else if(substr($info_table[$num]['COLUMN_NAME'],-4,4) == "CODE"){
								$str_code = mb_convert_kana ($_POST["update$i"][1], 'a');
								if(preg_match("/^[a-zA-Z0-9\-\_]{1,50}+$/",$str_code)){
									$_POST["update$i"][1] = $str_code;
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は英数字です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」で使える文字は英数字と「-」、「_」です。"."\n";
									$check_code = 1;
								}
							}else {
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は文字です。"."\n";
							}
							break;
							
						//日付型
						case "date":
							$str_date = mb_convert_kana($_POST["update$i"][1],"a");
							// 『 yyyy/m/d 』
							if(strrpos($str_date,'/') > 0 ){
								$str_con_date = explode("/",$str_date);
								if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
									$_POST["update$i"][1] = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は日付です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
									$check_code = 1;
								}
							// 『 yyyy-m-d 』
							} else if(strrpos($str_date,'-') > 0 ){
								
								$str_con_date = explode("-",$str_date);
								if(checkdate($str_con_date[1],$str_con_date[2],$str_con_date[0])){
									$_POST["update$i"][1] = $str_con_date[0]."-".$str_con_date[1]."-".$str_con_date[2];
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は日付です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
									$check_code = 1;
								}
							} else {
								// 『 yyyymmdd 』
								if(checkdate(substr($str_date,4,2),substr($str_date,6,2),substr($str_date,0,4))){
									$_POST["update$i"][1] = substr($str_date,0,4)."-".substr($str_date,4,2)."-".substr($str_date,6,2);
	//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は日付です。"."\n";
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyyyy-mm-dd形式で日付を入力して下さい。"."\n";
									$check_code = 1;
								}
							}
							break;
						
						//数字型
						case "bigint":
							$str_numeric = mb_convert_kana($_POST["update$i"][1],"a");
							if(is_numeric($str_numeric) || $str_numeric == NULL){
								$_POST["update$i"][1]  = $str_numeric;
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
							} else {
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は数字を入力して下さい。"."\n";
								$check_code = 1;
							}
							break;
						case "int":
							$str_numeric = mb_convert_kana($_POST["update$i"][1],"a");
							if(is_numeric($str_numeric) || $str_numeric == NULL){
								$_POST["update$i"][1]  = $str_numeric;
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
							} else {
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は数字を入力して下さい。"."\n";
								$check_code = 1;
							}
							
							// 単価計算
							if ($info_table[$num]['COLUMN_NAME']=="WORK_UNIT_PRICE"){
								$work_unit_price = $_POST["update$i"][1];
		//						print "<HR>WORK_UNIT_PRICEの計算です。<BR>".$work_unit_price;
							
							// 交通費
							} else if ($info_table[$num]['COLUMN_NAME']=="TRANSPORT_AMOUNT"){
								$transport_amount = $_POST["update$i"][1];
								$cost = $cost + $transport_amount;
		//						print "<HR>TRANSPORT_AMOUNTの計算です。<BR>".$cost;
							
							// その他手当
							} else if ($info_table[$num]['COLUMN_NAME']=="OTHER_AMOUNT"){
								$other_amount = $_POST["update$i"][1];
								$cost = $cost + $other_amount;
		//						print "<HR>OTHER_AMOUNTの計算です。<BR>".$cost;
								
							// 出金合計
							} else if ($info_table[$num]['COLUMN_NAME']=="PAYMENT_AMOUNT_TOTAL"){
								$payment_amount_total	=	ceil($work_expense_amount_total + $cost);
								$_POST["update$i"][1]	=	$payment_amount_total;
		//						print "<HR>PAYMENT_AMOUNT_TOTALの計算です。<BR>出金合計:".$payment_amount_total."<BR>";
							}
							break;
						case "double":
							// 超過
							if ($info_table[$num]['COLUMN_NAME']=="EXCESS_AMOUNT"){
								switch ( $work_payment_division ){
									// 日給
									case 'DP':
										$excess_amount		=	round((($work_unit_price / 8 ) * 1.25),2);
										break;
									// 時給
									case 'HP':
										$excess_amount		=	round(($work_unit_price * 1.25),2);
										break;
									default:
										$excess_amount		=	0;
								}
								$_POST["update$i"][1] = $excess_amount;
		//						print "<HR>EXCESS_AMOUNTの計算です。<BR>".$excess_amount;
							
							// 基本時間
							} else if ($info_table[$num]['COLUMN_NAME']=="BASIC_TIME"){
								$basic_time = $_POST["update$i"][1];
		//						print "<HR>BASIC_TIMEの計算です。<BR>基本時間:".$basic_time;
							
							// 休憩時間
							} else if ($info_table[$num]['COLUMN_NAME']=="BREAK_TIME"){
								$break_time = $_POST["update$i"][1];
		//						print "<HR>BREAK_TIMEの計算です。<BR>休憩時間:".$break_time;
							
							// 実働時間
							} else if ($info_table[$num]['COLUMN_NAME']=="REAL_WORKING_HOURS"){
								$real_working_hours		=	(($leave - $entering) / 60 / 60 );
								$_POST["update$i"][1]	=	$real_working_hours;
								$real_labor_hours		=	$real_working_hours - $break_time;
		//						print "<HR>REAL_WORKING_HOURSの計算です。<BR>作業時間:".$real_working_hours."<BR>";
		//						print "実働時間:".$real_labor_hours."<BR>";
							
							// 実残業時間
							} else if ($info_table[$num]['COLUMN_NAME']=="REAL_OVERTIME_HOURS"){
								if(($real_labor_hours - 8) > 0){
									$real_overtime_hours	=	$real_labor_hours - 8;
								} else {
									$real_overtime_hours	=	0;
								}
								
								$_POST["update$i"][1] = $real_overtime_hours;
		//						print "<HR>REAL_OVERTIME_HOURSの計算です。<BR>実残業時間:".$real_overtime_hours."<BR>";
							
							// 残業代
							} else if ($info_table[$num]['COLUMN_NAME']=="OVERTIME_WORK_AMOUNT"){
								$overtime_work_amount		=	round($real_overtime_hours * $excess_amount,2);
								$_POST["update$i"][1] = $overtime_work_amount;
		//						print "<HR>OVERTIME_WORK_AMOUNTの計算です。<BR>残業代:".$overtime_work_amount."<BR>";
								
							// 作業費合計
							} else if ($info_table[$num]['COLUMN_NAME']=="WORK_EXPENSE_AMOUNT_TOTAL"){
								switch ( $work_payment_division ){
									// 日給
									case 'DP':
										$work_expense_amount_total	=	$work_unit_price;
										break;
									// 時給
									case 'HP':
										if($real_labor_hours > 8){
											$work_expense_amount_total = $work_unit_price * 8;
										} else {
											if($real_labor_hours < 5){
												$work_expense_amount_total = $work_unit_price * $basic_time;
											} else {
												$work_expense_amount_total = $work_unit_price * $real_labor_hours;
											}
										}
										break;
									default:
								}
								
								//協力会社の場合、税込金額にする
								if($classification_division == 'CC'){
									$work_expense_amount_total	=	round(($work_expense_amount_total + $overtime_work_amount) * 1.05,2);
								} else {
									$work_expense_amount_total	=	$work_expense_amount_total + $overtime_work_amount;
								}
								$_POST["update$i"][1] = $work_expense_amount_total;
		//						print "<HR>WORK_EXPENSE_AMOUNT_TOTALの計算です。<BR>作業費合計：".$work_expense_amount_total."<BR>";
							
							} else {
								$str_numeric = explode(".",mb_convert_kana($_POST["update$i"][1],"a"));
					//			print_r($str_numeric);
								
								if(ctype_digit($str_numeric[0])){
									if(ctype_digit($str_numeric[1])){
										$_POST["update$i"][1] = $str_numeric[0].".".$str_numeric[1];
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else if($str_numeric[1] == NULL){
										$_POST["update$i"][1] = $str_numeric[0];
		//								print $cchk->getColumnNameJ($_POST["update$i"][0])."は数字です。"."\n";
									} else {
										print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は数字を入力して下さい。"."\n";
										$check_code = 1;
									}
								} else {
									print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は数字を入力して下さい。"."\n";
									$check_code = 1;
								}
							}
							break;
						
						//時間型
						case "time":
							//入力された時間が正しいかチェックする
							$str_time = mb_convert_kana($_POST["update$i"][1],"a");
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
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はmm:ss形式で時間を入力して下さい。"."\n";
								$check_code = 1;
							} else {
								
								$_POST["update$i"][1] = $str_time;
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は時間です。"."\n";
							}
							
							if ($info_table[$num]['COLUMN_NAME']=="ENTERING_MANAGE_TIMET"){
								$entering = strtotime($str_time);
	//							print "<HR>ENTERING_MANAGE_TIMETの計算です。<BR>".$entering;
							} else if ($info_table[$num]['COLUMN_NAME']=="LEAVE_MANAGE_TIMET"){
								$leave = strtotime($str_time);
	//							print "<HR>LEAVE_MANAGE_TIMETの計算です。<BR>".$leave;
							} else {
							}
							break;
						
						//日付時間
						case "datetime":
							//日付と時間を変数に分けてそれぞれ形式が正しく入力されているかチェックする
							$array = explode(" ",mb_convert_kana($_POST["update$i"][1],"a"));
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
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."は日付時間です。"."\n";
							} else if ($k == 0 && $l == 1){
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」の日付はyyyy-mm-dd形式で入力して下さい。"."\n";
								$check_code = 1;
							} else if ($k == 1 && $l == 0){
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」の時間はmm:ss形式で入力して下さい。"."\n";
								$check_code = 1;
							} else {
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」はyyyy-mm-dd mm:ss形式で日時を入力して下さい。"."\n";
								$check_code = 1;
							}
							break;
					}
					
					//文字数のチェック
					//入力箇所がZIP_CODE,HOME_PHONE,MOBILE_PHONEの場合
					if ($info_table[$num]['COLUMN_NAME']=="ZIP_CODE" || $info_table[$num]['COLUMN_NAME'] == "HOME_PHONE" || $info_table[$num]['COLUMN_NAME'] == "MOBILE_PHONE" || $info_table[$num]['CHARACTER_MAXIMUM_LENGTH'] == NULL || $_POST["update$i"][1] == NULL){
					} else if (mb_strlen($_POST["update$i"][1],'UTF-8')<=$info_table[$num]['CHARACTER_MAXIMUM_LENGTH']) {
	//					print $cchk->getColumnNameJ($_POST["update$i"][0])."は適正な文字数です。"."\n";
					} else {
						$sql_data .= ",'".$_POST["update$i"][1]."'\n";
	//					print $cchk->getColumnNameJ($_POST["update$i"][0])."は文字数が長すぎます。"."\n";
						$check_code = 1;
					}
					
					//バイト数のチェック
					//入力箇所がZIP_CODE,HOME_PHONE,MOBILE_PHONEの場合
					if ($info_table[$num]['COLUMN_NAME']=="ZIP_CODE" || $info_table[$num]['COLUMN_NAME'] == "HOME_PHONE" || $info_table[$num]['COLUMN_NAME'] == "MOBILE_PHONE" || $info_table[$num]['CHARACTER_MAXIMUM_LENGTH'] == NULL || $_POST["update$i"][1] == NULL){
					
					} else if ($_POST["update$i"][1] == NULL){
					
					} else if (strlen(mb_convert_kana($_POST["update$i"][1],"a"))<=$info_table[$num]['CHARACTER_MAXIMUM_LENGTH']){
	//					print $cchk->getColumnNameJ($_POST["update$i"][0])."は適正なバイト数です。"."\n";
					} else {
						$sql_data .= ",'".$_POST["update$i"][1]."'\n";
						print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は".strlen(mb_convert_kana($_POST["update$i"][1],"a"))."文字入力されています。\n".$info_table[$num]['character_maximum_length']."文字以内で入力して下さい。(全角は".$multibytecnt."文字分になります)"."\n";
						$check_code = 1;
					}
					
					switch ($info_table[$num]['IS_NULLABLE']) {
						
						case "NO":
						if( substr($p_table_name,-1,1) == "S"){
							if ($info_table[$num]['COLUMN_NAME'] == substr($p_table_name,0,-1)."_ID"){
							
							} else if ($_POST["update$i"][1] == "") {
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は必須項目です。"."\n";
								$check_code = 1;
							} else {
								$sql_data .= ",'".$_POST["update$i"][1]."'\n";
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."はnull以外なのでOKです。"."\n";
							}
						} else {
							if ($info_table[$num]['COLUMN_NAME'] == $p_table_name."_ID" || $sql_type == "INSERT"){
							
							} else if ($_POST["update$i"][1] == "") {
								print "「".$cchk->getColumnNameJ($_POST["update$i"][0])."」は必須項目です。"."\n";
								$check_code = 1;
							} else {
								$sql_data .= ",'".$_POST["update$i"][1]."'\n";
	//							print $cchk->getColumnNameJ($_POST["update$i"][0])."はnull以外なのでOKです。"."\n";
							}
						}
					}
					
					if($check_code == 1){
	//					print "NG"."\n";
					} else {
	//					print "<STRONG>OK</STRONG>"."\n";
					}
					
					if($check_code == 1){
						$error = 1;
					}
					
					//SQL文の生成					
					if ($sql_type == "INSERT"){
						if (is_null($d_insert1)){
							$d_insert1 = $info_table[$num]['COLUMN_NAME'];
							$d_insert2 = "'".$cchk->getMysqlEscapedValue($_POST["update$i"][1])."'";
						}else if($_POST["update$i"][1] == NULL){
							$d_insert1 = $d_insert1.",".$info_table[$num]['COLUMN_NAME'];
							$d_insert2 = $d_insert2.",NULL";	
						}else {
							$d_insert1 = $d_insert1.",".$info_table[$num]['COLUMN_NAME'];
							$d_insert2 = $d_insert2.",'".$cchk->getMysqlEscapedValue($_POST["update$i"][1])."'";	
						}
				//		print "<HR>".$d_insert1."<BR>";
				//		print "<HR>".$d_insert2."<BR>";
				//		print "<HR>".$d_insert1."<BR>".$d_insert2;
					//更新の場合
					}else if ($sql_type == "UPDATE"){
						if(is_null($d_update)){
							$d_update = $info_table[$num]['COLUMN_NAME'] ." = '".$cchk->getMysqlEscapedValue($_POST["update$i"][1])."'";
						} else {
							$d_update = $d_update."," .$info_table[$num]['COLUMN_NAME'] ." = '".$cchk->getMysqlEscapedValue($_POST["update$i"][1])."'";
						}
						
						//入力項目が主キーの時
						if ($pri_column_name == $_POST["update$i"][0]){
							$pri_column_data = $_POST["update$i"][1];
						}
				//		print "<HR>".$d_update."<BR>";
					}
				//主キーが$_POST["update$i"]に含まれずに入力項目が主キーの時
				} else {
				}
			}
		}
		
		//SQL文の作成
		//新規登録
		if ($sql_type == "INSERT"){
			if($error == 1){
				$sql = NULL;
			} else {
				$sql[0] = "INSERT INTO ".SCHEMA_NAME.".".$p_table_name ."\n"." (".$d_insert1;
				$sql[1] = "VALUE (".$d_insert2;
				$sql[2] = $encrypition;
			}
	//		return $sql;
		//更新
		} else if ($sql_type == "UPDATE"){
			if($error == 1){
				$sql = NULL;
			} else {
				$sql[0] = "UPDATE ".SCHEMA_NAME.".".$p_table_name ."\n"." SET ".$d_update;
				$sql[1] = ",ENCRYPTION_PASSWORD = '".$encrypition."'";
				$sql[2] = " WHERE ".$pri_column_name ." = '".$pri_column_data."'\n";
			}
		}
			//  print_r($sql);
		return $sql;
	}
}
?>