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
require_once('../mdl/ModelCommon.php');
class ColumnInfo extends ModelCommon {
// *****************************************************************************
// クラス名：ColumnInfo
// 処理概要：カラム情報
// *****************************************************************************
	private $column_info_rec;					// カラム情報レコード
	private $column_chk_rec;					// カラムチェック用レコード
// =============================================================================
// コンストラクタ
// 引数:
//			$p_table_name			テーブル名
//			$p_moge					取得モード(C:最低限のカラム情報、F:全てのカラム情報)
// =============================================================================
	function __construct($p_table_name, $p_mode = 'C'){
		$schema_name	= SCHEMA_NAME;					// スキーマ名
		$query_rec;										// クエリ結果のレコード
		$mdb;											// DBコネクション
		
		// SQL設定
		$sql = "
				SELECT
					 CASE WHEN
						 CASE WHEN locate('(',info_col.`COLUMN_TYPE`) > 0
						 	THEN SUBSTRING(info_col.`COLUMN_TYPE`,locate('(',info_col.`COLUMN_TYPE`)+1,locate(')',info_col.`COLUMN_TYPE`)-locate('(',info_col.`COLUMN_TYPE`)-1)
						 	ELSE 0
						 	END = info_col.`CHARACTER_OCTET_LENGTH`
					 THEN info_col.`CHARACTER_OCTET_LENGTH`
					 ELSE info_col.`CHARACTER_MAXIMUM_LENGTH`
					 END AS `CHARACTER_MAXIMUM_LENGTH`
					,info_col.`TABLE_CATALOG`                AS `TABLE_CATALOG`
					,info_col.`TABLE_SCHEMA`                 AS `TABLE_SCHEMA`
					,info_col.`TABLE_NAME`                   AS `TABLE_NAME`
					,info_col.`COLUMN_NAME`                  AS `COLUMN_NAME`
					,info_col.`ORDINAL_POSITION`             AS `ORDINAL_POSITION`
					,info_col.`COLUMN_DEFAULT`               AS `COLUMN_DEFAULT`
					,info_col.`IS_NULLABLE`                  AS `IS_NULLABLE`
					,info_col.`DATA_TYPE`                    AS `DATA_TYPE`
					,info_col.`CHARACTER_OCTET_LENGTH`       AS `CHARACTER_OCTET_LENGTH`
					,info_col.`NUMERIC_PRECISION`            AS `NUMERIC_PRECISION`
					,info_col.`NUMERIC_SCALE`                AS `NUMERIC_SCALE`
					,info_col.`CHARACTER_SET_NAME`           AS `CHARACTER_SET_NAME`
					,info_col.`COLLATION_NAME`               AS `COLLATION_NAME`
					,info_col.`COLUMN_TYPE`                  AS `COLUMN_TYPE`
					,info_col.`COLUMN_KEY`                   AS `COLUMN_KEY`
					,info_col.`EXTRA`                        AS `EXTRA`
					,info_col.`PRIVILEGES`                   AS `PRIVILEGES`
					,info_col.`COLUMN_COMMENT`               AS `COLUMN_COMMENT`
				FROM
					information_schema.columns info_col
				WHERE
						info_col.`TABLE_SCHEMA`	=	'$schema_name' 
				AND		info_col.`TABLE_NAME`	=	'$p_table_name'
		";
		
		// DB接続
		require_once('../lib/ConnectDB.php');
		//$mdb = getConnection();
		$mdb = getMysqlConnection();
		/*
		print $sql;
		return;
		*/
		/*
		$rcnt = 0;
		foreach ($mdb->query($sql) as $key => $row) {
			$this->column_info_rec[$key] = $row;
		}
		
		$rcnt = 0;
		foreach ($mdb->query($sql) as $row) {
			$rcnt = $rcnt + 1;
			$this->column_chk_rec[$rcnt] = $row;
		}
		*/
		$rcnt = 0;
		$l_result = $mdb->query($sql);
		while ($row = $l_result->fetch_assoc()) {
			$this->column_info_rec[$rcnt] = $row;
			$rcnt = $rcnt + 1;
			$this->column_chk_rec[$rcnt] = $row;
		}
		
		
		// DB切断
		$l_result->close();
		$mdb = null;
	}

// =============================================================================
// カラム情報取得
// 処理概要：
// 			クラス作成時に指定したテーブルのカラム情報を返す
// =============================================================================
	function getColumnInfoAll(){
		return $this->column_info_rec;
	}

// =============================================================================
// チェック用カラム情報取得
// 処理概要：
// 			クラス作成時に指定したテーブルのチェック用カラム情報を返す
// =============================================================================
	function getColumnChk(){
		return $this->column_chk_rec;
	}
// =============================================================================
// カラム情報取得
// 処理概要：
// 			引数に指定したテーブルのカラム情報を返す
// 引数:
//			$p_column_name			カラム名
// =============================================================================
	function getColumnInfo($p_column_name){
		$return_rec = null;
		for($i=0; $i<count($this->column_info_rec); $i++){
			if($this->column_info_rec[$i]["COLUMN_NAME"]==$p_column_name){
				$return_rec = $this->column_info_rec[$i];
				break;
			}
		}
		
		return $return_rec;
	}
// =============================================================================
// カラム名取得
// 処理概要：
// 			クラス作成時に指定したテーブルのカラム情報を返す
// =============================================================================
	function getColumnName(){
		$return_rec = null;
		
		for($i=0; $i<count($this->column_info_rec); $i++){
			$return_rec[$i] = $this->column_info_rec[$i]["COLUMN_NAME"];
		}
		
		return $return_rec;
	}

// =============================================================================
// カラム日本語名取得
// 処理概要：
// 			クラス作成時に指定したテーブルのカラム日本語名を返す
// 引数:
//			$p_column_name			カラム名
// =============================================================================
	function getColumnNameJ($p_column_name){
		$return_value = null;
		
		for($i=0; $i<count($this->column_info_rec); $i++){
			if($this->column_info_rec[$i]["COLUMN_NAME"]==$p_column_name){
				$return_value = $this->column_info_rec[$i]["COLUMN_COMMENT"];
				break;
			}
		}
		
		return $return_value;
	}
	
// =============================================================================
// カラム日本語名 + 英字名取得
// 処理概要：
// 			クラス作成時に指定したテーブルのカラム日本語名と英字名を返す
// 			（入力チェックメッセージ用）
// 引数:
//			$p_column_name			カラム名
// =============================================================================
	function getColumnNameMS($p_column_name){
		$return_value = null;
		
		for($i=0; $i<count($this->column_info_rec); $i++){
			if($this->column_info_rec[$i]["COLUMN_NAME"]==$p_column_name){
				//$return_value = $this->column_info_rec[$i]["COLUMN_NAME"]."(".$this->column_info_rec[$i]["COLUMN_COMMENT"].")";
				$return_value = $this->column_info_rec[$i]["COLUMN_COMMENT"];
				break;
			}
		}
		
		return $return_value;
	}
}
?>
