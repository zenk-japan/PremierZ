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
 ファイル名：c_commonCheck.php
 処理概要  ：テーブル値チェック
******************************************************************************/
require_once('../lib/CommonStaticValue.php');
class c_commonCheck{
/* =============================================================================
   変数定義
   ===========================================================================*/
	
/* =============================================================================
	例外定義
   ===========================================================================*/
	function expt_c_commonCheck(Exception $e){
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
		$l_return_mess = "";
		$l_return_mess['STATUS'] = 0;
		$l_return_mess['MESSAGE'] = "";
		$l_return_mess['COLUMN'] = "";
		$l_return_mess['LOG'] = "";
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table,'F');
		
		//$lr_colinfo = $lc_mci->getColumnInfoAll();
		//print_r($lr_colinfo);
		//return;
		foreach($pr_value as $col_name => $col_val){
		// 該当カラムが存在するか
			$lr_colum_info = $lc_mci->getColumnInfo($col_name);
			$l_return_mess['COLUMN'] = $col_name;
			$l_return_mess['LOG']	.= "col_name->".$col_name;
			$l_return_mess['LOG']	.= " lr_colum_info[IS_NULLABLE]->".$lr_colum_info['IS_NULLABLE'];
			$l_return_mess['LOG']	.= " lr_colum_info[COLUMN_DEFAULT]->".$lr_colum_info['COLUMN_DEFAULT'];
			$l_return_mess['LOG']	.= " lr_colum_info[EXTRA]->".$lr_colum_info[EXTRA];
			$l_return_mess['LOG']	.= " lr_colum_info[DATA_TYPE]->".$lr_colum_info[DATA_TYPE];
			$l_return_mess['LOG']	.= " lr_colum_info[CHARACTER_MAXIMUM_LENGTH]->".$lr_colum_info[CHARACTER_MAXIMUM_LENGTH];
			$l_return_mess['LOG']	.= " lr_colum_info[CHARACTER_OCTET_LENGTH]->".$lr_colum_info[CHARACTER_OCTET_LENGTH];
			$l_return_mess['LOG']	.= "\n";

			if(!is_null($lr_colum_info)){
			// 必須チェック(default値またはauto incrementの設定が有る項目は除外)
				if((is_null($col_val) || $col_val == "") && $lr_colum_info['IS_NULLABLE'] == "NO" && $lr_colum_info['COLUMN_DEFAULT'] == "" && $lr_colum_info['EXTRA'] != "auto_increment"){

					$l_return_mess['STATUS'] = 2;
					$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」は、入力必須項目です。\n";
				}

			// 型チェック
				$l_ipt_length	= strlen(bin2hex($col_val))/2;
				$l_coltype		= $lr_colum_info['DATA_TYPE'];
				$l_char_length	= intval($lr_colum_info['CHARACTER_MAXIMUM_LENGTH']);
				
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
								$l_return_mess['STATUS'] = 2;
								$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」は、".$l_ipt_length."文字入力されています。".$l_char_length."文字以内で入力してください(全角1文字は半角3文字分になります)。\n";
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
							if(preg_match("/^[0-9]+$/",$col_val)){
							}else{
								$l_return_mess['STATUS'] = 2;
								$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」は、数値を入力してください。\n";
							}
							break;
						
						// ---------------
						// 日時yyyy-mm-dd
						// ---------------
						case "date":
							// 書式のチェック
							if (!preg_match('/^(\d\d\d\d)\-(\d\d)\-(\d\d)$/', $col_val, $lr_preg_result)){
								$l_return_mess['STATUS'] = 2;
								$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」は、YYYY-MM-DD形式で入力してください。\n";
							}else{
								// 日付を変換し、エラーがないことを確認する
								//print_r($lr_preg_result);
								if (!checkdate($lr_preg_result[2], $lr_preg_result[3], $lr_preg_result[1])) {
									$l_return_mess['STATUS'] = 2;
									$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」に入力された日付「".$col_val."」は無効です。有効な日付を入力してください。\n";
								}
							}
							break;
						
						// ---------------
						// 時刻hh:mm:ss
						// ---------------
						case "time":
							// 書式のチェック
							if (!preg_match('/^(\d\d)\:(\d\d)\:(\d\d)$/', $col_val, $lr_preg_result)){
								$l_return_mess['STATUS'] = 2;
								$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」は、HH:MM:SS形式で入力してください。\n";
							}else{
								// 日付を変換し、エラーがないことを確認する
								//print_r($lr_preg_result);
								if (!mktime($lr_preg_result[1], $lr_preg_result[2], $lr_preg_result[3], 1, 1, 2010)) {
									$l_return_mess['STATUS'] = 2;
									$l_return_mess['MESSAGE'] .= "「".$lr_colum_info['COLUMN_NAME']."(".$lr_colum_info['COLUMN_COMMENT'].")」に入力された時刻「".$col_val."」は無効です。有効な時刻を入力してください。\n";
								}
							}
							break;
						}
				}
			}else{

				// 該当カラムなしの場合
				if($p_check_exist == 'Y'){
					$l_return_mess['STATUS'] = 1;
					$l_return_mess['MESSAGE'] .= "「".$col_name."」は、「".$p_table."」に存在しません。\n";
				}
			}
		}
		return $l_return_mess;
	}

}
?>
