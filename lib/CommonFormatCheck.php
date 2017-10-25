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

/******************************************************************************
 ファイル名：CommonFormatCheck.php
 処理概要  ：テーブル値チェック
******************************************************************************/
require_once('../lib/CommonStaticValue.php');
class CommonFormatCheck{
/* =============================================================================
   変数定義
   ===========================================================================*/
	private $length_reference_column = "CHARACTER_MAXIMUM_LENGTH";	// 文字列の長さを参照するカラム
	//private $length_reference_column = "CHARACTER_OCTET_LENGTH";	// 文字列の長さを参照するカラム(livedoor)

/* =============================================================================
	例外定義
   ===========================================================================*/
	function expt_commonCheck(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }

/* =============================================================================
   テーブル値チェック
   概要：引数でテーブル名とレコードを取得し、レコード内の各値が、テーブルの規格
         に合っているかチェックする
   引数:
			$pr_value				項目名と値の連想配列
			$p_table				チェック対象のテーブル
			$p_check_exist			カラムの存在をチェックするか(Y:する,N:しない)
   ===========================================================================*/
	function checkValue($pr_value, $p_table, $p_check_exist = 'N'){
		// 例外ハンドラ設定
		set_exception_handler(array($this, 'expt_commonCheck'));
		
		$lr_return_mess = "";
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table, "all");
		$l_col_num = 0;
		
		foreach($pr_value as $col_name => $col_val){
			
			// メッセージ初期化
			$lr_return_mess[$col_name]['COL_NAME']	= $col_name;
			$lr_return_mess[$col_name]['STATUS']	= 0;
			$lr_return_mess[$col_name]['MESSAGE']	= "";
			$lr_return_mess[$col_name]['LOG']		= "";
			
			// カラムinfoの取得
			$lr_colum_info = $lc_mci->getColumnInfo($col_name);
			
			//print_r($lr_colum_info)."<br>\n";
			
			$lr_return_mess[$col_name]['LOG']	.= "[COLUMN_NAME] -> ".$lr_colum_info['COLUMN_NAME'];
			$lr_return_mess[$col_name]['LOG']	.= " [IS_NULLABLE] -> ".$lr_colum_info['IS_NULLABLE'];
			$lr_return_mess[$col_name]['LOG']	.= " [COLUMN_DEFAULT] -> ".$lr_colum_info['COLUMN_DEFAULT'];
			$lr_return_mess[$col_name]['LOG']	.= " [EXTRA] -> ".$lr_colum_info['EXTRA'];
			$lr_return_mess[$col_name]['LOG']	.= " [DATA_TYPE] -> ".$lr_colum_info['DATA_TYPE'];
			$lr_return_mess[$col_name]['LOG']	.= " [CHARACTER_MAXIMUM_LENGTH] -> ".$lr_colum_info['CHARACTER_MAXIMUM_LENGTH'];
			$lr_return_mess[$col_name]['LOG']	.= " [CHARACTER_OCTET_LENGTH] -> ".$lr_colum_info['CHARACTER_OCTET_LENGTH'];
			$lr_return_mess[$col_name]['LOG']	.= "\n";
			
			if(!is_null($lr_colum_info)){
			// 該当カラムが存在する場合
			// 必須チェック(default値またはauto incrementの設定が有る項目は除外)
				if((is_null($col_val) || $col_val == "") && $lr_colum_info['IS_NULLABLE'] == "NO" && $lr_colum_info['COLUMN_DEFAULT'] == "" && $lr_colum_info['EXTRA'] != "auto_increment"){
					$lr_return_mess[$col_name]['STATUS'] = 2;
					$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、入力必須項目です。\n";
				}
			
			// 型チェック
				$l_ipt_length	= strlen(bin2hex($col_val))/2;
				$l_coltype		= $lr_colum_info['DATA_TYPE'];
				$l_char_length	= intval($lr_colum_info[$this->length_reference_column]);
				
				if(!is_null($col_val) && $col_val != ''){
					switch($l_coltype){
						// ---------------
						// テキスト系
						// ---------------
						case "text":
						case "varchar":
						case "longtext":
							//print "col_val->".$col_val.":"."bin2hex->".bin2hex($col_val).":"."l_ipt_length->".$l_ipt_length.":"."l_char_length->".$l_char_length."\n";
							// 文字列のバイト数と、OCTET_LENGTHを比較し、溢れる場合はエラー
							if($l_ipt_length > $l_char_length){
								$lr_return_mess[$col_name]['STATUS'] = 2;
								$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、".$l_ipt_length."文字入力されています。\n".$l_char_length."文字以内で入力してください。(全角1文字は半角3文字分になります)\n";
							}
							break;
						// ---------------
						// 整数系
						// ---------------
						case "tinyint":
						case "bit":
						case "bool":
						case "smallint":
						case "mediumint":
						case "int":
						case "integer":
						case "bigint":
							// 文字列が数値で構成されていない場合はエラー
							if(preg_match("/^[+-]?\d+$/",$col_val)){
							}else{
								$lr_return_mess[$col_name]['STATUS'] = 2;
								$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、数値を入力してください。\n";
							}
							break;
						// ---------------
						// double
						// ---------------
						case "double":
							$l_numeric_precision	= $lr_colum_info["NUMERIC_PRECISION"];
							$l_numeric_scale		= $lr_colum_info["NUMERIC_SCALE"];
							if (!preg_match('/^[+-]?\d{1,'.$l_numeric_precision.'}\.\d{1,'.$l_numeric_scale.'}$/', $col_val) && !preg_match('/^[+-]?\d{1,'.$l_numeric_precision.'}$/', $col_val)){
								$lr_return_mess[$col_name]['STATUS'] = 2;
								$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、整数部".$l_numeric_precision."桁以内、少数部".$l_numeric_scale."桁以内で入力してください。\n";
							}
							break;
						// ---------------
						// 日時yyyy-mm-dd
						// ---------------
						case "date":
							// 書式のチェック
							if (!preg_match('/^(\d\d\d\d)\-(\d?\d)\-(\d?\d)$/', $col_val, $lr_preg_result) && !preg_match('/^(\d\d\d\d)\/(\d?\d)\/(\d?\d)$/', $col_val, $lr_preg_result) && !preg_match('/^(\d\d\d\d)(\d\d)(\d\d)$/', $col_val, $lr_preg_result)){
								$lr_return_mess[$col_name]['STATUS'] = 2;
								$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、YYYY-MM-DD形式で入力してください。\n";
							}else{
								// 日付を変換し、エラーがないことを確認する
								//print_r($lr_preg_result);
								if (!checkdate($lr_preg_result[2], $lr_preg_result[3], $lr_preg_result[1])) {
									$lr_return_mess[$col_name]['STATUS'] = 2;
									$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」に入力された日付「".$col_val."」は無効です。有効な日付を入力してください。\n";
								}
							}
							break;
						// ---------------
						// 時刻hh:mm:ss
						// ---------------
						case "time":
							// 書式のチェック
							if (!preg_match('/^(\d\d)\:(\d\d)\:(\d\d)$/', $col_val, $lr_preg_result) && !preg_match('/^(\d?\d)\:(\d?\d)$/', $col_val, $lr_preg_result)){
								$lr_return_mess[$col_name]['STATUS'] = 2;
								$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」は、HH:MM[:SS]形式で入力してください。\n";
							}else{
								// 日付を変換し、エラーがないことを確認する
								//print_r($lr_preg_result);
								if (!mktime($lr_preg_result[1], $lr_preg_result[2], $lr_preg_result[3], 1, 1, 2010)) {
									$lr_return_mess[$col_name]['STATUS'] = 2;
									$lr_return_mess[$col_name]['MESSAGE'] .= "「".$lr_colum_info['COLUMN_COMMENT']."」に入力された時刻「".$col_val."」は無効です。有効な時刻を入力してください。\n";
								}
							}
							break;
						}
				}
			}else{
				// 該当カラムなしの場合
				if($p_check_exist == 'Y'){
					$lr_return_mess[$col_name]['STATUS'] = 1;
					$lr_return_mess[$col_name]['MESSAGE'] .= "「".$col_name."」は、「".$p_table."」に存在しません。\n";
				}
			}
			$l_col_num++;
		}
		return $lr_return_mess;
	}
	
/* =============================================================================
   日付チェック
   概要：日付が正しい形式になっているかチェックする
   引数:
			$p_date					日付
   ===========================================================================*/
   function checkDateString($p_date){
		// 書式のチェック
		if (!preg_match('/^(\d\d\d\d)\-(\d?\d)\-(\d?\d)$/',	$p_date, $lr_preg_result)
		 && !preg_match('/^(\d\d\d\d)\/(\d?\d)\/(\d?\d)$/',	$p_date, $lr_preg_result)
		 && !preg_match('/^(\d\d\d\d)(\d\d)(\d\d)$/',		$p_date, $lr_preg_result)){
			return false;
		}else{
			// 日付を変換し、エラーがないことを確認する
			//var_dump($lr_preg_result);
			//var_dump(checkdate($lr_preg_result[2], $lr_preg_result[3], $lr_preg_result[1]));
			if (!checkdate($lr_preg_result[2], $lr_preg_result[3], $lr_preg_result[1])) {
				return false;
			}
		}
		return true;
   }
/* =============================================================================
   時刻チェック
   概要：時刻が正しい形式になっているかチェックする
   引数:
			$p_time					時刻
			$p_one_day_flg			23:59までのチェックをするかどうかのフラグ
   ===========================================================================*/
   function checkTimeString($p_time, $p_one_day_flg = 'N'){
		// 書式のチェック

		if (!preg_match('/^(\d?\d)\:(\d?\d)$/',	$p_time, $lr_preg_result)){
			return false;
		}else{
			// 時のチェック
			if ($p_one_day_flg == 'Y' and $lr_preg_result[1] > 23){
				return false;
			}
			
			// 分のチェック
			if ($lr_preg_result[2] > 59){
				return false;
			}
		}
		return true;
   }
/* =============================================================================
   時刻大小チェック
   概要：入力された時刻の大小を判定する。(1:時刻1が大、2:等しい、3:時刻2が大)
   引数:
			$p_time1					時刻1
			$p_time2					時刻2
   ===========================================================================*/
	function chekTimeMagnitudeRelationship($p_time1, $p_time2){
		$l_return_value = 0;
		
		// 入力された時刻を分換算する
		if ($p_time1 == ""){
			$l_minutes1		= 0;
		}else{
			$lr_minutes1	= preg_split("/\:/", $p_time1);
			$l_minutes1		= intval($lr_minutes1[0]) * 60 + intval($lr_minutes1[1]);
		}
		
		if ($p_time2 == ""){
			$l_minutes2		= $l_minutes1;
		}else{
			$lr_minutes2	= preg_split("/\:/", $p_time2);
			$l_minutes2		= intval($lr_minutes2[0]) * 60 + intval($lr_minutes2[1]);
		}
		
		// 大小を判定する
		if ($l_minutes1 > $l_minutes2){
			$l_return_value = 1;
		}else if ($l_minutes1 < $l_minutes2){
			$l_return_value = 3;
		}else{
			$l_return_value = 2;
		}
		
		return $l_return_value;
	}
/* =============================================================================
   郵便番号チェック
   概要：郵便番号がnnn-nnnn形式になっているかチェックする
   引数:
			$p_zipcode				郵便番号
   ===========================================================================*/
	function checkZipCode($p_zipcode){
		if( mb_strpos($p_zipcode, '-') == NULL ){
			if(preg_match('/^\d{7}$/',$p_zipcode)){
				return true;
			} else {
				return false;
			}
		} else {
			if(preg_match('/^[0-9]{3}-?[0-9]{4}$/',$p_zipcode)){
				return true;
			} else {
				return false;
			}
		}
	}
	
/* =============================================================================
   電話番号チェック
   概要：電話番号がnnnnn-nnnnn-nnnnn形式になっているかチェックする
   引数:
			$p_phone				電話番号,自宅電話番号
   ===========================================================================*/
	function checkPhone($p_phone){
		if(preg_match('/^0\d{1,4}-\d{1,5}-?\d{1,5}$/',$p_phone)){
			return true;
		} else {
			return false;
		}
	}
	
/* =============================================================================
   携帯電話番号チェック
   概要：携帯電話番号がnnn-nnnn-nnnn形式になっているかチェックする
   引数:
			$p_mobile_phone			携帯電話番号
   ===========================================================================*/
	function checkMobilePhone($p_mobile_phone){
		if( mb_strpos($p_mobile_phone, '-') == NULL ){
			if(preg_match('/^0\d0\d{8}$/',$p_mobile_phone)){
				return true;
			} else {
				return false;
			}
		} else {
			if(preg_match('/^0\d0\-?\d{4}-?\d{4}$/',$p_mobile_phone)){
				return true;
			} else {
				return false;
			}
		}
	}
	
/* =============================================================================
   メールアドレスチェック
   概要：メールアドレスがnnnn@nn形式になっているかチェックする
   引数:
			$p_mail_address			メールアドレス
   ===========================================================================*/
	function checkMailAddress($p_mail_address){
		$pattern = 
			'/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!'.
	    '#\$\%&\'*+\\/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:'.
	    '[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`'.
	    "{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/";
		return preg_match($pattern, $p_mail_address);
	}
/* =============================================================================
   メッセージの入れ物取得
   概要：指定されたカラム名のメッセージの入れ物を返す
   引数:
			$p_colmun_name			カラム名
   ===========================================================================*/
	function getMessArray($p_colmun_name = ''){
		$lr_return_mess = "";
		
		if ($p_colmun_name == ''){
			return $lr_return_mess;
		}
		
		$lr_return_mess[$p_colmun_name]['COL_NAME']		= $p_colmun_name;
		$lr_return_mess[$p_colmun_name]['STATUS']		= 0;
		$lr_return_mess[$p_colmun_name]['MESSAGE']		= "";
		$lr_return_mess[$p_colmun_name]['LOG']			= "";
		
		return $lr_return_mess;
	}
}
?>
