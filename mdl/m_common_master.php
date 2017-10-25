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
require_once('../mdl/ModelCommon.php');
class m_common_master extends ModelCommon{
// *****************************************************************************
// クラス名：m_common_master
// 処理概要：共通マスタモデル
// *****************************************************************************
// =============================================================================
// コンストラクタ
// =============================================================================
	function __construct(){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct("COMMON_MASTER_V");		// ビュー名を指定
	}
	
// =============================================================================
// DATA_ID取得
// 処理概要：共通マスタを検索しDATA_IDを取得する
// 			 共通マスタは全DATA_ID分必ず作成する為、DATA_IDの一覧を取得できる
// 引数:
//			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)(省略可)
// =============================================================================
	function getDATAID($p_include_invalid = 'Y'){
		$l_return_value = "";
		$l_ar_condition = "";
		$l_cond_ret = "";
		
		// 並べ替え
		$this->setOrderbyPhrase(array("DATA_ID"));
		
		// 条件設定
		if($p_include_invalid == "N"){
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
			$l_cond_ret = $this->setCondition($l_ar_condition);
		}else{
			$l_cond_ret = "where 1 = 1 ";
		}
		
		// 条件セット
		$this->where_phrase = $l_cond_ret . "
				and (   VALIDATION_START_DATE is null
					 OR VALIDATION_START_DATE <= now() )
				and (   VALIDATION_END_DATE is null
					 OR VALIDATION_END_DATE >= str_to_date(TRUNCATE(now(),-6),'%Y%m%d%H%i%s') )
				";
		
		// 集約設定
		$this->setGroupbyPhrase(array("DATA_ID"));
		
		// レコード取得
		$l_result_rec = $this->getRecord();
		
		// レコードが返ってきた場合は戻り値をセットする
		if(count($l_result_rec)>0){
			$l_loop_cnt = 0;
			foreach($l_result_rec as $key=>$value){
				$l_loop_cnt++;
				$l_return_value[$l_loop_cnt] = $l_result_rec[$l_loop_cnt]["DATA_ID"];
			}
		}
		// 条件初期化
		$this->resetCondition();
		
		return $l_return_value;
	}
	
// =============================================================================
// 共通マスタ全取得
// 処理概要：共通マスタを検索し有効な値を全て取得する
// 引数:
//			$p_data_id			データID(省略可)
//			$p_code_set			コードセット(省略可)
//			$p_code_id			コードID(省略可)
//			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)(省略可)
// =============================================================================
	function getCommonMasterAll($p_data_id = '', $p_code_set = '', $p_code_id = '', $p_include_invalid = 'Y'){
		//print "p_data_id = ".$p_data_id." p_code_set = ".$p_code_set." p_code_id = ".$p_code_id." p_include_invalid = ".$p_include_invalid."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";
		
		// 並べ替え
		$this->setOrderbyPhrase(array("CODE_SET", "CODE_ID"));
		
		// 条件設定
		// DATA_ID
		if(trim($p_data_id) != ''){
			$l_ar_condition["DATA_ID"]	= $p_data_id;
		}
		
		// コードID
		if(trim($p_code_id) != ''){
			$l_ar_condition["CODE_ID"]	= $p_code_id;
		}
		
		// コードセット
		if(trim($p_code_set) != ''){
			$l_ar_condition["CODE_SET"]	= "%".$p_code_set."%";
		}
		
		// 有効フラグの条件
		if($p_include_invalid == "N"){
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
		}
		
		// 条件セット
		if(count($l_ar_condition) > 0){
			// 何かの条件がセットされている場合
			$l_cond_ret = $this->setCondition($l_ar_condition);
			if($p_include_invalid == "N"){
				$this->where_phrase = $l_cond_ret . "
						and (   VALIDATION_START_DATE is null
							 OR VALIDATION_START_DATE <= now() )
						and (   VALIDATION_END_DATE is null
							 OR VALIDATION_END_DATE >= str_to_date(TRUNCATE(now(),-6),'%Y%m%d%H%i%s') )
						";
			}
		}else{
			if($p_include_invalid == "N"){
				$this->where_phrase = $l_cond_ret . "
						where (   VALIDATION_START_DATE is null
							 OR VALIDATION_START_DATE <= now() )
						and (   VALIDATION_END_DATE is null
							 OR VALIDATION_END_DATE >= str_to_date(TRUNCATE(now(),-6),'%Y%m%d%H%i%s') )
						";
			}
		}
		//print $this->where_phrase."<br>";
		// レコード取得
		$l_result_rec = $this->getRecord();
		//print_r($l_result_rec);
		//print "<br>";
		// レコードが返ってきた場合は戻り値をセットする
		if(count($l_result_rec)>0){
			$l_loop_cnt = 0;
			foreach($l_result_rec as $key => $value){
				$l_loop_cnt++;
				foreach($value as $item_key => $item_value){
					if(preg_match("/^[0-9]+$/",$item_key) ){
						// 配列キーが数値の部分は無視
					} else {
						//print "item_key:".$item_key." item_value:".$item_value."<br>";
						$l_return_value[$l_loop_cnt][$item_key] = $item_value;
					}
				}
			}
		}
		//print_r($l_return_value);
		// 条件初期化
		$this->resetCondition();
		
		return $l_return_value;
	}
// =============================================================================
// 共通マスタコード値取得
// 処理概要：共通マスタをコード名で検索しコード値を取得する
// 			
// 引数:
//			$p_data_id			データID
//			$p_setname			コードセット
//			$p_get_mode		    取得モード現時点で無効な値も取得する場合は「ALL」をセット(省略可)
// =============================================================================
	function getCommonValueRec($p_data_id, $p_setname, $p_get_mode=''){
		return $this->getCommonMaster($p_data_id, $p_setname, "",GET_ITEM_VALUE_REC, $p_get_mode);
	}
// =============================================================================
// 共通マスタコード値取得
// 処理概要：共通マスタをコード名で検索しコード値を取得する
// 			
// 引数:
//			$p_data_id			データID
//			$p_setname			コードセット
//			$p_code_name		コード名
//			$p_get_mode		    取得モード現時点で無効な値も取得する場合は「ALL」をセット(省略可)
// =============================================================================
	function getCommonValue($p_data_id, $p_setname, $p_code_name, $p_get_mode=''){
		return $this->getCommonMaster($p_data_id, $p_setname, $p_code_name, GET_ITEM_VALUE, $p_get_mode);
	}
// =============================================================================
// 共通マスタコード値取得
// 処理概要：共通マスタをコード値で検索しコード名を取得する
// 			
// 引数:
//			$p_data_id			データID
//			$p_setname			コードセット
//			$p_code_value		コード値
//			$p_get_mode		    取得モード現時点で無効な値も取得する場合は「ALL」をセット(省略可)
// =============================================================================
	function getCommonName($p_data_id, $p_setname, $p_code_name, $p_get_mode=''){
		//print "$p_data_id".":"."$p_setname".":"."$p_code_name"."<br>";
		return $this->getCommonMaster($p_data_id, $p_setname, $p_code_name, GET_ITEM_NAME, $p_get_mode);
	}

// =============================================================================
// 共通マスタコード値取得
// 処理概要：共通マスタを検索しコード値またはコード名を取得する
// 			
// 引数:
//			$p_data_id			データID
//			$p_setname			コードセット
//			$p_code				検索値
//			$p_get_item			取得する値(VALUE or NAME or REC(一覧))
//			$p_get_mode		    取得モード現時点で無効な値も取得する場合は「ALL」をセット(省略可)
// =============================================================================
	function getCommonMaster($p_data_id, $p_setname, $p_code, $p_get_item, $p_get_mode=''){
		$l_ar_condition	= null;						// 条件バッファ
		$l_cond_ret		= null;						// 条件戻り値
		$l_return_value = null;						// 戻り値
		
		// 検索値別の条件設定
		switch($p_get_item){
			case GET_ITEM_VALUE_REC:
				// コード値レコード取得の場合
				$l_ar_condition = array(
									"DATA_ID"		=> $p_data_id,
									"CODE_SET"		=> $p_setname
								);
				// レコードの場合は並べ替える
				$this->setOrderbyPhrase(array("CODE_VALUE"));
				break;
			case GET_ITEM_VALUE:
				// コード値取得の場合
				$l_ar_condition = array(
									"DATA_ID"		=> $p_data_id,
									"CODE_SET"		=> $p_setname,
									"CODE_NAME"		=> $p_code
								);
				break;
			case GET_ITEM_NAME:
				// コード名取得の場合
				$l_ar_condition = array(
									"DATA_ID"		=> $p_data_id,
									"CODE_SET"		=> $p_setname,
									"CODE_VALUE"	=> $p_code
								);
				break;
		}
				
		// 取得モード別の条件設定
		if($p_get_mode=='ALL'){
			// すべてを取得する場合
			// 条件セット
			$l_cond_ret = $this->setCondition($l_ar_condition);
		}else{
			// 有効な値のみ取得する場合
			// 有効フラグの条件を付ける
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
			
			// 条件セット
			$l_cond_ret = $this->setCondition($l_ar_condition);
			
			// 日付の条件を追加し、Where句を再セット
			$this->where_phrase = $l_cond_ret . "
					and (   VALIDATION_START_DATE is null
						 OR VALIDATION_START_DATE <= now() )
					and (   VALIDATION_END_DATE is null
						 OR VALIDATION_END_DATE >= str_to_date(TRUNCATE(now(),-6),'%Y%m%d%H%i%s') )
					";
		}
		
		// レコード取得
		$l_result_rec = $this->getRecord();
		
		// レコードが返ってきた場合は戻り値をセットする
		if(count($l_result_rec)>0){
			switch($p_get_item){
				case GET_ITEM_VALUE_REC:
					// コード値取得の場合
					$l_loop_cnt = 0;
					foreach($l_result_rec as $key=>$value){
						$l_loop_cnt++;
						$l_return_value[$l_result_rec[$l_loop_cnt]["CODE_NAME"]] = $l_result_rec[$l_loop_cnt]["CODE_VALUE"];
					}
					break;
				case GET_ITEM_VALUE:
					// コード値取得の場合
					$l_return_value = $l_result_rec[1]["CODE_VALUE"];
					break;
				case GET_ITEM_NAME:
					// コード名取得の場合
					$l_return_value = $l_result_rec[1]["CODE_NAME"];
					break;
			}
		}
		
		// 条件初期化
		$this->resetCondition();
		
		return $l_return_value;
	}
	
// =============================================================================
// 有効フラグ変更
// 処理概要：引数で指定されたコードIDのデータの有効フラグを変更する
// 			
// 引数:
//			$p_code_id			コードID
//			$p_user_id			ユーザーID
//			$p_value			変更値(Y/N)
// =============================================================================
	function changeValidityFlag($p_code_id, $p_user_id, $p_value){
		$l_flag_value = strtoupper($p_value);			// 大文字化
		
		if($l_flag_value != "Y" && $l_flag_value != "N"){
			return false;
		}
		
		// SQL組み立て
		$l_del_sql  = "update COMMON_MASTER ";
		$l_del_sql .= "set VALIDITY_FLAG = '".$p_value."' ";
		$l_del_sql .= ",LAST_UPDATE_DATET = now() ";
		$l_del_sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
		$l_del_sql .= "where CODE_ID = ".$p_code_id." ";
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_del_sql);
		
		return $l_retcode;
	}
	
// =============================================================================
// 削除処理
// 処理概要：引数で指定されたコードIDのデータを削除する
// 			
// 引数:
//			$p_code_id			コードID
//			$p_user_id			ユーザーID
// =============================================================================
	function deleteRecord($p_code_id, $p_user_id){
		
		// SQL組み立て
		$l_del_sql  = "delete from COMMON_MASTER ";
		$l_del_sql .= "where CODE_ID = ".$p_code_id." ";
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_del_sql);
		
		return $l_retcode;
	}

// =============================================================================
// 新規登録処理
// 処理概要：引数で指定された値でINSERT処理を行う
// 			
// 引数:
//			$p_user_id			ユーザーID
//			$pr_value			各項目値
// =============================================================================
	function insertRecord($p_user_id, $pr_value){
					
		// SQL組み立て
		$l_sql  = "insert into `COMMON_MASTER` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'CODE_ID',";
		$l_sql .= "CODE_SET,";
		$l_sql .= "CODE_NAME,";
		$l_sql .= "CODE_VALUE,";
		$l_sql .= "REMARKS,";
		if($pr_value["VALIDATION_START_DATE"] != ''){
			$l_sql .= "VALIDATION_START_DATE,";
		}
		if($pr_value["VALIDATION_END_DATE"] != ''){
			$l_sql .= "VALIDATION_END_DATE,";
		}
		$l_sql .= "RESERVE_1,";
		$l_sql .= "RESERVE_2,";
		$l_sql .= "RESERVE_3,";
		$l_sql .= "RESERVE_4,";
		$l_sql .= "RESERVE_5,";
		$l_sql .= "RESERVE_6,";
		$l_sql .= "RESERVE_7,";
		$l_sql .= "RESERVE_8,";
		$l_sql .= "RESERVE_9,";
		$l_sql .= "RESERVE_10,";
		$l_sql .= "VALIDITY_FLAG,";
		$l_sql .= "REGISTRATION_DATET,";
		$l_sql .= "REGISTRATION_USER_ID,";
		$l_sql .= "LAST_UPDATE_DATET,";
		$l_sql .= "LAST_UPDATE_USER_ID";
		$l_sql .= ") ";
		$l_sql .= "values (";
		$l_sql .= $this->getMysqlEscapedValue($pr_value["DATA_ID"]).",";						// DATA_ID
		//$l_sql .= "'',";										// CODE_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["CODE_SET"])."',";				// CODE_SET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["CODE_NAME"])."',";			// CODE_NAME
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["CODE_VALUE"])."',";			// CODE_VALUE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["REMARKS"])."',";				// REMARKS
		if($pr_value["VALIDATION_START_DATE"] != ''){
			$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["VALIDATION_START_DATE"])."',";	// VALIDATION_START_DATE
		}
		if($pr_value["VALIDATION_END_DATE"] != ''){
			$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["VALIDATION_END_DATE"])."',";	// VALIDATION_END_DATE
		}
		$l_sql .= "null,";	// $pr_value["RESERVE_1"].","		// RESERVE_1
		$l_sql .= "null,";	// $pr_value["RESERVE_2"].","		// RESERVE_2
		$l_sql .= "null,";	// $pr_value["RESERVE_3"].","		// RESERVE_3
		$l_sql .= "null,";	// $pr_value["RESERVE_4"].","		// RESERVE_4
		$l_sql .= "null,";	// $pr_value["RESERVE_5"].","		// RESERVE_5
		$l_sql .= "null,";	// $pr_value["RESERVE_6"].","		// RESERVE_6
		$l_sql .= "null,";	// $pr_value["RESERVE_7"].","		// RESERVE_7
		$l_sql .= "null,";	// $pr_value["RESERVE_8"].","		// RESERVE_8
		$l_sql .= "null,";	// $pr_value["RESERVE_9"].","		// RESERVE_9
		$l_sql .= "null,";	// $pr_value["RESERVE_10"].","		// RESERVE_10
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["VALIDITY_FLAG"])."',";		// VALIDITY_FLAG
		$l_sql .= "now(),";										// REGISTRATION_DATET
		$l_sql .= $p_user_id.",";								// REGISTRATION_USER_ID
		$l_sql .= "now(),";										// LAST_UPDATE_DATET
		$l_sql .= $p_user_id;									// LAST_UPDATE_USER_ID
		$l_sql .= ")";
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);
		
		return $l_retcode;
	}

// =============================================================================
// 更新処理
// 処理概要：引数で指定された値でUPDATE処理を行う
// 			
// 引数:
//			$p_user_id			ユーザーID
//			$pr_value			各項目値
// =============================================================================
	function updateRecord($p_user_id, $pr_value){
		// SQL組み立て
		$l_sql  = "update `COMMON_MASTER` ";
		$l_sql .= "set ";
		$l_sql .= "DATA_ID = '".$this->getMysqlEscapedValue($pr_value["DATA_ID"])."',";								// DATA_ID
		$l_sql .= "CODE_SET = '".$this->getMysqlEscapedValue($pr_value["CODE_SET"])."',";							// CODE_SET
		$l_sql .= "CODE_NAME = '".$this->getMysqlEscapedValue($pr_value["CODE_NAME"])."',";							// CODE_NAME
		$l_sql .= "CODE_VALUE = '".$this->getMysqlEscapedValue($pr_value["CODE_VALUE"])."',";						// CODE_VALUE
		$l_sql .= "REMARKS = '".$this->getMysqlEscapedValue($pr_value["REMARKS"])."',";								// REMARKS
		if($pr_value["VALIDATION_START_DATE"] != ''){
			$l_sql .= "VALIDATION_START_DATE = '".$this->getMysqlEscapedValue($pr_value["VALIDATION_START_DATE"])."',";	// VALIDATION_START_DATE
		}else{
			$l_sql .= "VALIDATION_START_DATE = null,";									// VALIDATION_START_DATE
		}
		if($pr_value["VALIDATION_END_DATE"] != ''){
			$l_sql .= "VALIDATION_END_DATE = '".$this->getMysqlEscapedValue($pr_value["VALIDATION_END_DATE"])."',";		// VALIDATION_END_DATE
		}else{
			$l_sql .= "VALIDATION_END_DATE = null,";									// VALIDATION_END_DATE
		}
		$l_sql .= "VALIDITY_FLAG = '".$this->getMysqlEscapedValue($pr_value["VALIDITY_FLAG"])."',";					// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";											// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = ".$p_user_id." ";								// LAST_UPDATE_USER_ID
		$l_sql .= "where CODE_ID = ".$this->getMysqlEscapedValue($pr_value["CODE_ID"]);
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);
		
		return $l_retcode;
	}
}
?>