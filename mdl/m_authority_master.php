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
class m_authority_master extends ModelCommon{
// *****************************************************************************
// クラス名：m_authority_master.php
// 処理概要：権限マスタモデル
// *****************************************************************************
// =============================================================================
// コンストラクタ
// =============================================================================
	function __construct(){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct("AUTHORITY_V");		// ビュー名を指定
	}

// =============================================================================
// DATA_ID取得
// 処理概要：共通マスタを検索しDATA_IDを取得する
// 			 共通マスタは全DATA_ID分必ず作成する為、DATA_IDの一覧を取得できる
// 引数:
//			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
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
			$this->where_phrase = $l_cond_ret;
		}else{
			$this->where_phrase = "";
			//$l_cond_ret = "where 1 = 1 ";
		}

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
// マスタ全取得
// 処理概要：有効な値を全て取得する
// 引数:
//			$p_data_id			データID
//			$p_authority_id		権限ID
//			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
// =============================================================================
	function getRecordAll($p_data_id = '', $p_authority_id = '', $p_include_invalid = 'Y'){
		//print "p_data_id = ".$p_data_id." p_authority_id = ".$p_authority_id." p_include_invalid = ".$p_include_invalid."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";

		// 並べ替え
		$this->setOrderbyPhrase(array("AUTHORITY_CODE", "AUTHORITY_NAME"));

		// 条件設定
		// DATA_ID
		if(trim($p_data_id) != ''){
			$l_ar_condition["DATA_ID"]	= $p_data_id;
		}

		// ID
		if(trim($p_authority_id) != ''){
			$l_ar_condition["AUTHORITY_ID"]	= $p_authority_id;
		}

		// 有効フラグの条件
		if($p_include_invalid == "N"){
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
		}

		// 条件セット
		if(count($l_ar_condition) > 0){
			// 何かの条件がセットされている場合
			$l_cond_ret = $this->setCondition($l_ar_condition);
			$this->where_phrase = $l_cond_ret;
		}else{
			$this->where_phrase = "";
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
// 削除処理
// 処理概要：引数で指定されたIDのデータを削除する
//
// 引数:
//			$p_id				ID値
//			$p_user_id			ユーザーID
// =============================================================================
	function deleteRecord($p_id, $p_user_id){
		//print "p_id->".$p_id.":p_user_id->".$p_user_id;
		// SQL組み立て
		$l_del_sql  = "delete from AUTHORITY ";
		$l_del_sql .= "where AUTHORITY_ID = ".$p_id." ";

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
		$l_sql  = "insert into `AUTHORITY` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'AUTHORITY_ID',";
		$l_sql .= "AUTHORITY_CODE,";
		$l_sql .= "AUTHORITY_NAME,";
		$l_sql .= "TERMINAL_DIVISION,";
		$l_sql .= "SCREEN_NAME,";
		$l_sql .= "ADMITTED_OPERATION_FLAG,";
		$l_sql .= "REMARKS,";
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
		//$l_sql .= "'',";										// AUTHORITY_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["AUTHORITY_CODE"])."',";			// AUTHORITY_CODE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["AUTHORITY_NAME"])."',";			// AUTHORITY_NAME
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["TERMINAL_DIVISION"])."',";		// TERMINAL_DIVISION
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["SCREEN_NAME"])."',";			// SCREEN_NAME
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["ADMITTED_OPERATION_FLAG"])."',";// ADMITTED_OPERATION_FLAG
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["REMARKS"])."',";				// REMARKS
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
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["VALIDITY_FLAG"])."',";			// VALIDITY_FLAG
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
		$l_sql  = "update `AUTHORITY` ";
		$l_sql .= "set ";
		$l_sql .= "DATA_ID = '".$this->getMysqlEscapedValue($pr_value["DATA_ID"])."',";									// DATA_ID
		$l_sql .= "AUTHORITY_CODE = '".$this->getMysqlEscapedValue($pr_value["AUTHORITY_CODE"])."',";					// AUTHORITY_CODE
		$l_sql .= "AUTHORITY_NAME = '".$this->getMysqlEscapedValue($pr_value["AUTHORITY_NAME"])."',";					// AUTHORITY_NAME
		$l_sql .= "TERMINAL_DIVISION = '".$this->getMysqlEscapedValue($pr_value["TERMINAL_DIVISION"])."',";				// TERMINAL_DIVISION
		$l_sql .= "SCREEN_NAME = '".$this->getMysqlEscapedValue($pr_value["SCREEN_NAME"])."',";							// SCREEN_NAME
		$l_sql .= "ADMITTED_OPERATION_FLAG = '".$this->getMysqlEscapedValue($pr_value["ADMITTED_OPERATION_FLAG"])."',";	// ADMITTED_OPERATION_FLAG
		$l_sql .= "REMARKS = '".$this->getMysqlEscapedValue($pr_value["REMARKS"])."',";									// REMARKS
		$l_sql .= "VALIDITY_FLAG = '".$this->getMysqlEscapedValue($pr_value["VALIDITY_FLAG"])."',";						// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";												// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = ".$p_user_id." ";									// LAST_UPDATE_USER_ID
		$l_sql .= "where AUTHORITY_ID = ".$this->getMysqlEscapedValue($pr_value["AUTHORITY_ID"]);

		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);

		return $l_retcode;
	}
}
?>