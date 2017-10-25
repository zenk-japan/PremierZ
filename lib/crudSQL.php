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
   ファイル名：crudSQL
   処理概要  ：SQL文(create, read, update, delete)作成の機能を持つクラス
 ******************************************************************************/
require_once('../lib/CommonStaticValue.php');

class crudSQL {
/*============================================================================
	SQL作成関数
	処理概要：SQLを返す
			$p_table_name					対象テーブル
			$p_type							SQLタイプ(INSERT/UPDATE)
			$p_ar_data						データ配列
			$p_user_id						ユーザーID(登録者/更新者)
			$up_pri_key						更新用プライマリーキー値
			$up_where_phrase				更新用where句
  ============================================================================*/
	function crudSQLString($p_table_name, $p_type, $p_ar_data, $p_user_id, $up_pri_key = '', $up_where_phrase = ''){
		
		switch ($p_type){
			case "INSERT" :
				// カラム部を生成
				$r_insert_col = $this->CreateInsertCol($p_table_name);
				$r_data_key = "(".$r_insert_col.") ";
				
				// データ部を生成
				$r_insert_val = $this->CreateInsertVal($p_table_name, $p_ar_data, $p_user_id);
				$r_data_val = "(".$r_insert_val.")";
				
				$l_sql = "insert into ".$p_table_name." ";
				$l_sql .= $r_data_key;
				$l_sql .= "value ";
				$l_sql .= $r_data_val."; ";
			break;
			case "UPDATE" :
				// set句を生成
				$r_update_col = $this->CreateUpdateSet($p_table_name, $p_ar_data, $p_user_id);
				$r_set_key = $r_update_col;
				
				// where句を生成
				if($up_pri_key != '' && $up_where_phrase == ''){
					$r_update_where = $this->CreateUpdateWhere($p_table_name, $up_pri_key);
				}else{
					$r_update_where = $up_where_phrase;
				}
				
				$l_sql = "update ".$p_table_name." ";
				$l_sql .= $r_set_key." ";
				$l_sql .= $r_update_where."; ";
			break;
		}
		return $l_sql;
	}
	
/*============================================================================
	新規登録用カラム名作成関数
	処理概要：新規登録用のテーブルのカラム名を返す
			$p_table						対象テーブル
  ============================================================================*/
	function CreateInsertCol($p_table){
		
		$l_return_value = null;
		
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table, "all");
		$lr_colum_infoAll = $lc_mci->getColumnInfoAll();
		
		foreach($lr_colum_infoAll as $info_key => $info_val){
			if(is_null($l_return_value)){
				$l_return_value = $info_val['COLUMN_NAME'];
			}else{
				$l_return_value .= " , ".$info_val['COLUMN_NAME'];
			}
		}
		return $l_return_value;
	}
	
/*============================================================================
	新規登録用データ部作成関数
	処理概要：新規登録用のデータ部を返す
			$p_table						対象テーブル
			$pr_data						データ配列
			$p_login_user_id				ユーザーID(登録者/更新者)
  ============================================================================*/
	function CreateInsertVal($p_table, $pr_data, $p_login_user_id){
		
		$l_return_value = NULL;
		$encrypition = NULL;
		
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table, "all");
		$lr_colum_infoAll = $lc_mci->getColumnInfoAll();
		
		for ($cnt_info = 0; $cnt_info <= (count($lr_colum_infoAll) - 1); $cnt_info++) {
			$value = NULL;
			$val_check = "OFF";
			
			foreach($pr_data as $data_key => $data_val){
				// この時点でエスケープを行う
				$data_val = $lc_mci->getMysqlEscapedValue($data_val);
				
				if($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == $data_key && !is_null($data_val) && strlen($data_val) > 0){
					$val_check = "ON";
					switch ($data_key) {
						// 郵便番号
						case "ZIP_CODE" :
							if(mb_strpos($data_val ,'-') == NULL){
								$value = "'".substr($data_val, 0, 3)."-".substr($data_val, 3, 4)."'";
							}else{
								$value = "'".$data_val."'";
							}
							break;
						// カナ
						case "KANA" :
							$value = "'".mb_convert_kana($data_val, "KV")."'";
							break;
						default:
							$value = "'".$data_val."'";
					}
				}else{
					if($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "REGISTRATION_DATET" || $lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "LAST_UPDATE_DATET"){
						$val_check = "ON";
						$value = "now()";
					}else if($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "REGISTRATION_USER_ID" || $lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "LAST_UPDATE_USER_ID"){
						$val_check = "ON";
						$value = "'".$p_login_user_id."'";
					}else if($val_check != "ON"){
						$value = "default";
					}
				}
			}
			
			if(is_null($l_return_value)){
				$l_return_value = $value;
			}else{
				$l_return_value .= " , ".$value;
			}
		}
		return $l_return_value;
	}
	
/*============================================================================
	更新用SET句作成関数
	処理概要：新規登録用のテーブルのカラム名を返す
			$p_table						対象テーブル
			$pr_data						データ配列
			$p_login_user_id				ユーザーID(更新者)
  ============================================================================*/
	function CreateUpdateSet($p_table, $pr_data, $p_login_user_id){
		
		$l_return_value = NULL;
		$encrypition = NULL;
		
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table, "all");
		$lr_colum_infoAll = $lc_mci->getColumnInfoAll();
		
		for ($cnt_info = 0; $cnt_info <= (count($lr_colum_infoAll) - 1); $cnt_info++) {
			$l_col = NULL;
			$l_val = NULL;
			$col_check = "OFF";
			
			foreach($pr_data as $data_key => $data_val){
				// この時点でエスケープを行う
				$data_val = $lc_mci->getMysqlEscapedValue($data_val);
				
				if($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == $data_key){
					
					$col_check	=	"ON";
					$l_col = $data_key;
					
					if(!is_null($data_val) && strlen($data_val) > 0){
						switch ($data_key) {
							// 郵便番号
							case "ZIP_CODE" :
								if(mb_strpos($data_val ,'-') == NULL){
									$l_val = "'".substr($data_val, 0, 3)."-".substr($data_val, 3, 4)."'";
								}else{
									$l_val = "'".$data_val."'";
								}
								break;
							// カナ
							case "KANA" :
								$l_val = "'".mb_convert_kana($data_val, "KV")."'";
								break;
							default:
								$l_val = "'".$data_val."'";
						}
					}else{
						$l_val = "default";
					}
				}
			}
			
			if($col_check == "ON"){
				if(is_null($l_return_value)){
					if($l_col == "REGISTRATION_DATET" || $l_col == "REGISTRATION_USER_ID"){
					}else{
						$l_return_value = "set ".$l_col." = ".$l_val;
					}
				}else{
					if($l_col == "REGISTRATION_DATET" || $l_col == "REGISTRATION_USER_ID"){
					}else{
						$l_return_value .= " , ".$l_col." = ".$l_val;
					}
				}
			// 証跡用カラムのセット
			}else if ($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "LAST_UPDATE_USER_ID"){
				if(!is_null($l_return_value)){
					$l_return_value .= " , ".$lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] ." = ".$p_login_user_id;
				}
			}else if ($lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] == "LAST_UPDATE_DATET"){
				if(!is_null($l_return_value)){
					$l_return_value .= " , ".$lr_colum_infoAll[$cnt_info]['COLUMN_NAME'] ." = now()";
				}
			}
		}
		return $l_return_value;
	}
	
/*============================================================================
	更新用where句作成関数
	処理概要：新規登録用のテーブルのカラム名を返す
			$p_table						対象テーブル
			$p_pri_key						プライマリーキー値
  ============================================================================*/
	function CreateUpdateWhere($p_table, $p_pri_key){
		
		$l_return_value = NULL;
		
		// カラム情報用のモデルインクルード
		require_once('../mdl/m_column_info.php');
		$lc_mci = new ColumnInfo($p_table, "all");
		$lr_colum_infoAll = $lc_mci->getColumnInfoAll();
		
		foreach($lr_colum_infoAll as $info_key => $info_val){
			if($info_val['COLUMN_KEY'] == "PRI"){
				$l_return_value = "where ".$info_val['COLUMN_NAME']." = '".$p_pri_key."'";
			}
		}
		return $l_return_value;
	}
}
?>