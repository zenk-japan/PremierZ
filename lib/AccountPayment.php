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
// ファイル名：AccountPayment
// 処理概要  ：作業人員の給与の計算を行うクラス
// *****************************************************************************
require_once('../lib/CommonStaticValue.php');

class AccountPayment {
	
	private $cost;								// 交通費などの手当の合計
	private $work_expense_amount_total;			// 作業費の合計
	private $excess_amount;						// 超過
	private $basic_time;						// 基本時間
	private $break_time;						// 休憩時間
	private $real_labor_hours;					// 実作業時間
	private $real_overtime_hours;				// 実残業時間
	private $overtime_work_amount;				// 残業代
	private $entering;							// 入店時間
	private $leaving;							// 退店時間
	
	
	// 入力項目のチェック
	function AccountPaymentTotal($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value,$work_unit_price,$work_payment_division,$classification_division){
	
		//$p_table_name							//入力したデータを登録するテーブル名
		//$sql_type								//新規登録、更新登録を判別する為のタイプ名
		//$cchk									//クラス"ColumnInfo"が入ったオブジェクト名
		//$info_colum							//二次元配列$column_chkのcolumn_nameの列が入っている配列
		//$info_key								//二次元配列$column_chkのcolumn_keyの列が入っている配列
		//$info_table							//二次元配列$column_chkのcolumn_tableの列が入っている配列
		//$entry_key							//入力したデータの項目名
		//$entry_value							//入力したデータの値
		//$work_unit_price						//作業者の単価
		//$work_payment_division				//作業者の支払い区分
		//$classification_division				//作業者の所属するグループの分類区分
		
		mb_internal_encoding(ENCODE_TYPE);
		
		//主キーのカラム名の取得
		$raw = array_search("PRI",$info_key);
		$pri_column_name = $info_colum[$raw];
		if($entry_key == "[object Window]"){
		} else {
			
			//入力項目名がチェック項目に該当するか判別する
			if(array_search($entry_key,$info_colum)){
				//入力項目が配列info_columの何番目にあるかを出し、変数$numに入れる
				$num = array_search($entry_key,$info_colum);
				
				//data_typeの判別
				switch ($info_table[$num]["data_type"]){
					case "int":
						// 交通費
						if ($info_table[$num][column_name] =="TRANSPORT_AMOUNT"){
							$transport_amount = $entry_value;
							$this->cost = $this->cost + $transport_amount;
		//					print "<HR>TRANSPORT_AMOUNTの計算です。<BR>".$cost;
						
						// その他手当
						} else if ($info_table[$num][column_name] =="OTHER_AMOUNT"){
							$other_amount = $entry_value;
							$this->cost = $this->cost + $other_amount;
		//					print "<HR>OTHER_AMOUNTの計算です。<BR>".$this->cost;
						
						// 出金合計
						} else if ($info_table[$num][column_name] =="PAYMENT_AMOUNT_TOTAL"){
							$payment_amount_total	=	ceil($this->work_expense_amount_total + $this->cost);
							$entry_value	=	$payment_amount_total;
		//					print "<HR>PAYMENT_AMOUNT_TOTALの計算です。<BR>出金合計:".$payment_amount_total."<BR>";
						}
						break;
						
					case "double":	
						// 超過	
						if ($info_table[$num][column_name] =="EXCESS_AMOUNT"){
							switch ( $work_payment_division ){
								// 日給
								case 'DP':
									$this->excess_amount		=	round((($work_unit_price / 8 ) * 1.25),2);
									break;
								// 時給
								case 'HP':
									$this->excess_amount		=	round(($work_unit_price * 1.25),2);
									break;
								default:
									$this->excess_amount		=	0;
							}
							
						// 基本時間
						} else if ($info_table[$num][column_name] =="BASIC_TIME"){
							$this->basic_time = $entry_value;
		//					print "<HR>BASIC_TIMEの計算です。<BR>基本時間:".$this->basic_time;
					
						// 休憩時間
						} else if ($info_table[$num][column_name] =="BREAK_TIME"){
							$this->break_time = $entry_value;
		//					print "<HR>BREAK_TIMEの計算です。<BR>休憩時間:".$this->break_time;
					
						// 実働時間
						} else if ($info_table[$num][column_name] =="REAL_WORKING_HOURS"){
							
							if($this->leaving - $this->entering < 0){
								$real_working_hours		=	(($this->leaving - $this->entering) / 60 / 60 ) + 24;
							} else {
								$real_working_hours		=	(($this->leaving - $this->entering) / 60 / 60 );
							}
							$entry_value			=	$real_working_hours;
							$this->real_labor_hours		=	$real_working_hours - $this->break_time;
		//					print "<HR>REAL_WORKING_HOURSの計算です。<BR>作業時間:".$real_working_hours."<BR>";
		//					print "実働時間:".$this->real_labor_hours."<BR>";
							
						// 実残業時間
	    				} else if ($info_table[$num][column_name] =="REAL_OVERTIME_HOURS"){
							if(($this->real_labor_hours - 8) > 0){
								$this->real_overtime_hours	=	$this->real_labor_hours - 8;
							} else {
								$this->real_overtime_hours	=	0;
							}
							
							$entry_value = $this->real_overtime_hours;
		//					print "<HR>REAL_OVERTIME_HOURSの計算です。<BR>実残業時間:".$this->real_overtime_hours."<BR>";
						
						// 残業代
						} else if ($info_table[$num][column_name] =="OVERTIME_WORK_AMOUNT"){
							$this->overtime_work_amount		=	round($this->real_overtime_hours * $this->excess_amount,2);
							$entry_value = $this->overtime_work_amount;
		//					print "<HR>OVERTIME_WORK_AMOUNTの計算です。<BR>残業代:".$this->overtime_work_amount."<BR>";
						
						// 作業費合計
						} else if ($info_table[$num][column_name] =="WORK_EXPENSE_AMOUNT_TOTAL"){
							switch ( $work_payment_division ){
								// 日給
								case 'DP':
									$this->work_expense_amount_total	=	$work_unit_price;
									break;
								// 時給
								case 'HP':
									if($this->real_labor_hours > 8){
										$this->work_expense_amount_total = $work_unit_price * 8;
									} else {
										if($this->real_labor_hours < 5){
											$this->work_expense_amount_total = $work_unit_price * $this->basic_time;
										} else {
											$this->work_expense_amount_total = $work_unit_price * $this->real_labor_hours;
										}
									}
									break;
								default:
							}
							
							//協力会社の場合、税込金額にする
							if($classification_division == 'CC'){
								$this->work_expense_amount_total	=	round(($this->work_expense_amount_total + $this->overtime_work_amount) * 1.05,2);
							} else {
								$this->work_expense_amount_total	=	$this->work_expense_amount_total + $this->overtime_work_amount;
							}
							$entry_value = $this->work_expense_amount_total;
		//					print "<HR>WORK_EXPENSE_AMOUNT_TOTALの計算です。<BR>作業費合計：".$this->work_expense_amount_total."<BR>";
								
						}
						break;
					//文字型
					case "varchar":
						if ($info_table[$num][column_name] =="ENTERING_MANAGE_TIMET" || $info_table[$num][column_name] =="LEAVE_MANAGE_TIMET"){
							$str_time = mb_convert_kana($entry_value,"a");
							$str_replenishment = null;
							$check_code = 0;
							
							if(mb_strpos($str_time ,":")){
								$array_time = preg_split("/\:/",$entry_value);
								
								if($array_time[0] > 23){
									$check_code = 1;
									$array_time[0] = $array_time[0] - 24;
									$str_time = $array_time[0].":".$array_time[1];
								}
							} else {
								$str_time_len = mb_strlen( $str_time );
								
								if ($str_time_len > 6){
									$str_time = mb_substr($str_time, 0, 6);
								} else {
									for($w_len = $str_time_len ; $w_len < 6; $w_len++) {
										$str_replenishment .= "0";
									}
								}
								$array_time[0]	= substr($str_time,0,2);
								$array_time[1]	= substr($str_time,-2,2);
								if($array_time[0] > 23){
									$array_time[0] = $array_time[0] - 24;
								}
								$str_time		= $array_time[0].$array_time[1].$str_replenishment;
							}
							
							if($info_table[$num][column_name] =="ENTERING_MANAGE_TIMET"){
								$this->entering = strtotime($str_time);
								//print "ENTERING_MANAGE_TIMET = ".$str_time."\n";
								//print "<HR>ENTERING_MANAGE_TIMETの計算です。<BR>".$this->entering;
							
							} else if ($info_table[$num][column_name] =="LEAVE_MANAGE_TIMET"){
								$this->leaving = strtotime($str_time);
								//print "LEAVE_MANAGE_TIMET = ".$str_time."\n";
								//print "<HR>LEAVE_MANAGE_TIMETの計算です。<BR>".$this->leaving;
							}
						} else {
						}
						break;
				}
			}
		}
		
		return $entry_value;
	}
}
?>
