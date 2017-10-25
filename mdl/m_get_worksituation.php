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


require_once('../lib/CommonStaticValue.php');
require_once('../lib/CommonFunctions.php');
class GetWorksituation {
// *****************************************************************************
// クラス名：GetWorksituation
// 処理概要：作業一覧取得
// *****************************************************************************
	protected $ar_condition;							// 条件の配列
	protected $where_phrase;							// where句
	protected $ar_groupby;								// group byの配列
	protected $ar_orderby;								// order byの配列
	protected $table_name;								// テーブル名
	
// =============================================================================
// コンストラクタ
// =============================================================================
	function __construct($p_table_name){
		$this->table_name = $p_table_name;				// テーブル名セット
	}
	
// =============================================================================
// 変数初期化
// =============================================================================
	function resetCondition(){
		$this->ar_condition = "";						// 条件の配列
		$this->where_phrase = "";						// where句
		$this->ar_groupby = "";							// group byの配列
		$this->ar_orderby = "";							// order byの配列
	}
// =============================================================================
// カラム適応値取得
// 処理概要：引数のカラムタイプに適応させた値を返す
//				date		->	YYYY-MM-DDで指定
//				datetime	->	YYYY-MM-DD H24:MI:SS で指定
//				varchar		->  単一引用符で囲む
// 引数:
//				$p_col_type			カラムのデータ型
//				$p_value			値
// =============================================================================
	function getAdjustedValue($p_col_type, $p_value){
		$l_return_value = null;
		
		switch($p_col_type){
			case COLUMN_TYPE_DATE:
				// 日付の場合は日付型に変換する
		//		$l_return_value = "STR_TO_DATE('" . $p_value . "', '%Y-%m-%d')";
				$l_return_value = "'".$p_value."'";
			break;
			case COLUMN_TYPE_DATETIME:
				// 日時の場合は日時型に変換する
				if(strlen($p_value)<=10){
					// 年月日のみ指定された場合
					$l_return_value = "STR_TO_DATE('" . $p_value . " 00:00:00', '%Y-%m-%d %H:%i:%s')";
				}else{
					// 自分秒まで指定された場合
					$l_return_value = "STR_TO_DATE('" . $p_value . "', '%Y-%m-%d %H:%i:%s')";
				}
			break;
			case COLUMN_TYPE_VARCHAR:
				// 文字列の場合は単一引用符で囲む
				$l_return_value = "'" . $p_value . "'";
			break;
			default:
				// その他の場合はそのまま返す
				$l_return_value = $p_value;
			break;
		}
		
		//echo $p_col_type." getAdjustedValue col_value ".$l_return_value ."<BR>";
		
		return $l_return_value;
		
	}

// =============================================================================
// 条件取得
// 処理概要：引数の情報から条件式の配列を組み立てて返す
// 			
// 引数:
//			$p_table_name			テーブル名
//			$p_ar_condition			条件を指定した変数または配列
// =============================================================================
	function getCondition($p_table_name, $p_ar_condition){
		$l_info_datatype 	= "DATA_TYPE";		// カラム情報におけるデータ型の項目名
		$l_info_colname 	= "COLUMN_NAME";	// カラム情報におけるカラム名の項目名
		$l_ar_condition 	= null;				// 条件
		$l_cond_cnt 		= 0;				// 条件カウンタ
		$l_ar_cinfo 		= null;				// カラム情報
		$l_adjusted_val 	= null;				// カラムの型に適応させた値
		$l_condition_buff 	= null;				// 条件バッファ
		$l_in_prefix 		= " in (";			// in句の接頭辞
		$l_in_suffix 		= ")";				// in句の接尾辞
		
		//print $p_table_name."<br>";
		//print_r($p_ar_condition);
		//print "<br>";
		// テーブルのカラムを取得
		require_once('../mdl/m_column_info.php');
		//$cinfo = new ColumnInfo(strtoupper($p_table_name),$column_chk);
		$cinfo = new ColumnInfo(strtoupper($p_table_name));
		$column_chk = $cinfo->getColumnChk();
		
		if(is_array($p_ar_condition)){
			foreach($p_ar_condition as $col_name => $ar_value){
				// カラム情報取得
				$l_ar_cinfo = null;
				$l_ar_cinfo = $cinfo->getColumnInfo(strtoupper($col_name));
				
				// カラム情報が取得できなかった場合はその条件を無視する
				if(is_null($l_ar_cinfo)){
					continue;
				}
				if(is_array($ar_value)){
					// 値が配列の場合
					$l_loop_cnt = 0;
					$l_condition_buff = null;
					
					// 接頭辞をつける
					$l_condition_buff .= $l_in_prefix;
					
					// 配列の値をカンマでつなぐ
					foreach($ar_value as $col_value){
						// 適応値取得
						$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $col_value);
						if($l_loop_cnt!=0){
							// 1つめの条件以外はカンマをつける
							$l_condition_buff .= ",";
						}
						$l_condition_buff .= $l_adjusted_val;
						
						$l_loop_cnt++;
					}
					
					// 括弧を閉める
					$l_condition_buff .= $l_in_suffix;
					
					// 条件を配列にセット
					$l_cond_cnt++;
					$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . $l_condition_buff;
					
				}else{
					// 値が配列以外の場合
					$l_cond_cnt++;
					
					if(is_null($ar_value) || $ar_value==""){
						// 条件がNULLの場合はis null
						$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " is null";
					}else{
						if(strstr($ar_value, "<") !=""){
							//VALIDATION_DATE_START,VALIDATION_DATE_ENDが範囲内である値を取得する条件を作成する
							$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
							$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " <= " . str_replace("<", "", $l_adjusted_val );
						}else if(strstr($ar_value, ">") !=""){
							//VALIDATION_DATE_START,VALIDATION_DATE_ENDが範囲内である値を取得する条件を作成する
							$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
							$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " >= " . str_replace(">", "", $l_adjusted_val );
						}else if(strstr($ar_value, "!") != ""){
							//AUTHORITY_IDが1以外のAUTHORITY_IDを取得するための条件を作成する
							$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
							$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " <> " . str_replace("!", "", $l_adjusted_val );
						}else if(strstr($ar_value, CONDITION_PLURAL) !=""){
							$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
							$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " in " . str_replace(CONDITION_PLURAL, "", $l_adjusted_val );
						}else if(strstr($ar_value, "%")==""){
							// %指定がない場合
							// 適応値取得
							$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
							$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " = " . $l_adjusted_val;
						}else{
							// %指定がある場合はlikeにする
							// 適応値取得
							if($ar_value == "%%"){
								//検索条件に値が入っていない場合は何もしない
							}else{
								$l_adjusted_val = $this->getAdjustedValue($l_ar_cinfo[$l_info_datatype], $ar_value);
								$l_condition[$l_cond_cnt] .= $l_ar_cinfo[$l_info_colname] . " like " . $l_adjusted_val;
							}
						}
					}
				}
			}
		}else{
			// 引数が配列ではない場合は条件なしとする
			return null;
		}
		
		return $l_condition;
	}

// =============================================================================
// WHERE句取得
// 処理概要：引数の情報からWHERE句の配列を組み立てて返す
// 			
// 引数:
//			$p_ar_formula			条件を指定した式の配列
// =============================================================================
	function getWherePhrase($p_ar_formula){
		$l_char_where		= "where ";
		$l_char_and			= "and ";
		$l_return_val		= null;
		$l_loop_cnt			= 0;
		
		if(is_array($p_ar_formula)){
			foreach($p_ar_formula as $formula){
				if($l_loop_cnt==0){
					// 最初の条件はwhereに続ける
					$l_return_val .= $l_char_where . $formula;
				}else{
					// 最初の条件以外はandに続ける
					$l_return_val .= $l_char_and . $formula;
				}
				// 折り返す	
				$l_return_val .= "  ";
				$l_loop_cnt++;
			}
		}else{
			// 条件が配列ではない場合は、nullを返す
			$l_return_val = null;
		}
		
		return $l_return_val;
	}

// =============================================================================
// Group By句取得
// 処理概要：引数の情報からGroup By句の配列を組み立てて返す
// 			
// 引数:
//			$p_ar_group				条件を指定した式の配列
// =============================================================================
	function getGroupbyPhrase($p_ar_group){
		$l_char_groupby		= "group by ";
		$l_return_val		= null;
		$l_loop_cnt			= 0;
		
		if(is_array($p_ar_group)){
			foreach($p_ar_group as $value){
				if($l_loop_cnt==0){
					// 最初の条件はgroup byに続ける
					$l_return_val .= $l_char_groupby . $value;
				}else{
					// 最初の条件以外はカンマに続ける
					$l_return_val .= "," . $value;
				}
				// 折り返す	
				$l_return_val .= "  ";
				$l_loop_cnt++;
			}
		}else{
			// 条件が配列ではない場合は、nullを返す
			$l_return_val = null;
		}
		//echo "group by :: $l_return_val<BR>";
		return $l_return_val;
	}

// =============================================================================
// Order By句取得
// 処理概要：引数の情報からOrder By句の配列を組み立てて返す
// 			
// 引数:
//			$p_ar_order				条件を指定した式の配列
// =============================================================================
	function getOrderbyPhrase($p_ar_order){
		$l_char_orderby		= "order by ";
		$l_return_val		= null;
		$l_loop_cnt			= 0;
		
		if(is_array($p_ar_order)){
			foreach($p_ar_order as $value){
				if($l_loop_cnt==0){
					// 最初の条件はorder byに続ける
					$l_return_val .= $l_char_orderby . $value;
				}else{
					// 最初の条件以外はカンマに続ける
					$l_return_val .= "," . $value;
				}
				// 折り返す	
				$l_return_val .= "  ";
				$l_loop_cnt++;
			}
		}else{
			// 条件が配列ではない場合は、nullを返す
			$l_return_val = null;
		}
		//echo "order by :: $l_return_val<BR>";
		return $l_return_val;
	}

// =============================================================================
// 条件セット
// 処理概要：条件をセットする
//			 条件をセットすると、自動的にwhere句もセットされる
//			
// 引数:
//			$p_ar_formula			条件を指定した式の配列
// =============================================================================
	function setCondition($p_ar_condition){
		$this->where_phrase = "";		// 条件式の配列
		
		// 条件の解析
		$this->ar_condition = $this->getCondition($this->table_name, $p_ar_condition);
		// where句の組立て
		$this->where_phrase = $this->getWherePhrase($this->ar_condition);
			
		return $this->where_phrase;
	}
// =============================================================================
// Where句セット(配列設定)
// 処理概要：Whrere句をセットする
//			
// 引数:
//			$p_ar_formula			条件を指定した式の配列
// =============================================================================
	function setWherePhrase($p_ar_formula){
		// where句の組立て
		$this->where_phrase = $this->getWherePhrase($p_ar_formula);
		
		return $this->where_phrase;			
	}
// =============================================================================
// Where句セット(文字列設定)
// 処理概要：Whrere句をセットする
//			
// 引数:
//			$p_formula				条件を指定した式
// =============================================================================
	function setWherePhraseText($p_formula){
		// where句の組立て
		$this->where_phrase = $p_formula;
		
		return $this->where_phrase;			
	}
// =============================================================================
// Order By句セット
// 処理概要：Order By句をセットする
//			
// 引数:
//			$p_ar_order				Order Byの配列
// =============================================================================
	function setOrderbyPhrase($p_ar_order){
		// Order By句の組立て
		$this->ar_orderby = $this->getOrderbyPhrase($p_ar_order);
		
		return $this->ar_orderby;	
	}
// =============================================================================
// Group By句セット
// 処理概要：Group By句をセットする
//			
// 引数:
//			$p_ar_group				Group Byの配列
// =============================================================================
	function setGroupbyPhrase($p_ar_group){
		// Group By句の組立て
		$this->ar_groupby = $this->getGroupbyPhrase($p_ar_group);
		
		return $this->ar_groupby;	
	}
// =============================================================================
// レコード取得
// 処理概要：クラス内のwhere句の情報を使用してクエリを実行し、レコードを返す
//			
// 引数:
// =============================================================================
	function getRecord(){
		
		$l_ar_condition 	= null;		// 条件式の配列
		$l_where_phrase		= null;		// where句
		$l_groupby_phrase	= null;		// group by句
		$l_orderby_phrase	= null;		// order by句
		$l_sql				= null;		// select文
		
		$l_ar_retrec		= null;		// returnするレコード
		
		// select文の組立て
		$l_sql .= "SELECT *   ";
		$l_sql .= "FROM ".$this->table_name."  ";
		
		// where句の組立て
		$l_where_phrase = $this->where_phrase;
		
		// Group By句の組立て
		$l_groupby_phrase = $this->ar_groupby;
		
		// Order By句の組立て
		$l_orderby_phrase = $this->ar_orderby;
		
		// where句が設定されている場合は、where句もセット
		if(!is_null($l_where_phrase) && $l_where_phrase!=""){
			$l_sql .= $l_where_phrase;
		}
		
		// Group By句が設定されている場合は、Group By句もセット
		if(!is_null($l_groupby_phrase) && $l_groupby_phrase!=""){
			$l_sql .= $l_groupby_phrase;
		}
		
		// Order By句が設定されている場合は、Order By句もセット
		if(!is_null($l_orderby_phrase) && $l_orderby_phrase!=""){
			$l_sql .= $l_orderby_phrase;
		}
		
		// print $l_sql." ";
		
		// クエリー実行
		// DB接続
		require_once('../lib/ConnectDB.php');
		//$mdb = getConnection();
		$mdb = getMysqlConnection();
		
		//print $l_sql."<br>";
		
		$l_ar_retrec = getRowWithRownum($mdb, $l_sql);
		/*
		$rcnt = 0;
		$l_result = $mdb->query($l_sql);
		//foreach ($mdb->query($l_sql) as $key => $row) {
		while ($row = $l_result->fetch_assoc()) {
			// レコードの配列は1から始める
			$rcnt = $rcnt + 1;
			$l_ar_retrec[$rcnt] = $row;
		}
		// DB切断
		$l_result->close();
		$mdb = null;
		*/
		return $l_ar_retrec;
	}
}
?>