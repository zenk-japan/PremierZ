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
class m_page_using_conf extends ModelCommon{
/*******************************************************************************
// クラス名：m_page_using_conf
// 処理概要：画面利用マスタモデル
*******************************************************************************/
	private $debug_mode = 0;
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス
// =============================================================================
// コンストラクタ
// =============================================================================
	function __construct(){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct("PAGE_USING_CONF_V");		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__construct-継承元のコンストラクタを起動完了");print "<br>";}

		// クラス変数の初期化
		$this->r_view_rec		= '';
		$this->r_col_name		= '';
		$this->r_where			= '';
		$this->r_ordery_by		= '';
		$this->r_group_by		= '';
		if($this->debug_mode==1){print("Step-__construct-クラス変数の初期化完了");print "<br>";}

		// カラム情報を取得
		require_once('../mdl/m_column_info.php');
		$this->c_column_info = new ColumnInfo('PAGE_USING_CONF_V');		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__constructカラム情報を取得");print "<br>";}
	}

// =============================================================================
// マスタ全取得
// 処理概要：有効な値を全て取得する
// 引数:
//			$p_data_id			データID
//			$p_authority_id		権限ID
//			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
// =============================================================================
	function getRecordAll($p_data_id = '', $p_page_using_conf_id = '', $p_include_invalid = 'Y'){
		//print "p_data_id = ".$p_data_id." p_page_using_conf_id = ".$p_page_using_conf_id." p_include_invalid = ".$p_include_invalid."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";

		// 並べ替え
		$this->setOrderbyPhrase(array("PAGE_CODE", "PAGE_NAME"));

		// 条件設定
		// DATA_ID
		if(trim($p_data_id) != ''){
			$l_ar_condition["DATA_ID"]	= $p_data_id;
		}

		// ID
		if(trim($p_page_using_conf_id) != ''){
			$l_ar_condition["PAGE_USING_CONF_ID"]	= $p_page_using_conf_id;
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
		$l_del_sql  = "delete from PAGE_USING_CONF ";
		$l_del_sql .= "where PAGE_USING_CONF_ID = ".$p_id." ";

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
		$l_sql  = "insert into `PAGE_USING_CONF` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'PAGE_USING_CONF_ID',";
		$l_sql .= "PAGE_CODE,";
		$l_sql .= "PAGE_NAME,";
		$l_sql .= "ALLOWED_AUTHCODE,";
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
		$l_sql .= $pr_value["DATA_ID"].",";						// DATA_ID
		//$l_sql .= "'',";										// PAGE_USING_CONF_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["PAGE_CODE"])."',";			// PAGE_CODE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["PAGE_NAME"])."',";			// PAGE_NAME
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_value["ALLOWED_AUTHCODE"])."',";		// ALLOWED_AUTHCODE
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
		$l_sql  = "update `PAGE_USING_CONF` ";
		$l_sql .= "set ";

		$l_sql .= $this->getUpdataSetPhrase("DATA_ID", $pr_value["DATA_ID"], 'N').",";	// DATA_ID
		$l_sql .= $this->getUpdataSetPhrase("PAGE_CODE", $pr_value["PAGE_CODE"], 'C').",";	// PAGE_CODE
		$l_sql .= $this->getUpdataSetPhrase("PAGE_NAME", $pr_value["PAGE_NAME"], 'C').",";	// PAGE_NAME
		$l_sql .= $this->getUpdataSetPhrase("ALLOWED_AUTHCODE", $pr_value["ALLOWED_AUTHCODE"], 'C').",";	// ALLOWED_AUTHCODE
		$l_sql .= $this->getUpdataSetPhrase("REMARKS", $pr_value["REMARKS"], 'C').",";	// REMARKS

		$l_sql .= "VALIDITY_FLAG = '".$pr_value["VALIDITY_FLAG"]."',";						// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";												// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = ".$p_user_id." ";									// LAST_UPDATE_USER_ID
		$l_sql .= "where PAGE_USING_CONF_ID = ".$pr_value["PAGE_USING_CONF_ID"];

		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);

		return $l_retcode;
	}

/*============================================================================
	レコード取得
  ============================================================================*/
	function queryDBRecord(){
		$lr_result_rec		= "";
		$lr_view_rec		= array();
		$lr_comment_rec		= array();

		// where セット
		$l_where_phrase		= $this->setWherePhrase($this->r_where);
		// order by セット
		$l_orderby_phrase	= $this->setOrderbyPhrase($this->r_ordery_by);
		// group by セット
		$l_groupby_phrase	= $this->setGroupbyPhrase($this->r_group_by);

		// レコード取得
		$lr_result_rec		= $this->getRecord();
		if($this->debug_mode==1){print("Step-queryDBRecordレコード取得");print "<br>";}
		if($this->debug_mode==1){print_r($lr_result_rec);print "<br>";}

		// レコードが返ってきた場合は戻り値をセットする
		if(count($lr_result_rec)>0){
			$l_loop_cnt = 0;
			foreach($lr_result_rec as $key => $value){
				$l_loop_cnt++;

				foreach($value as $item_key => $item_value){
					if(preg_match("/^[0-9]+$/",$item_key) ){
						// 配列キーが数値の部分は無視
						if($this->debug_mode==1){print("配列キーが数値の部分は無視");print "<br>";}
					} else {
						if($this->debug_mode==1){print "item_key:".$item_key." item_value:".$item_value."<br>";}
						if($item_value != ''){
							$lr_view_rec[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);		// レコードの先頭番号は1
						}else{
							// 空の項目は不正な値がセットされてしまう為、適用しない
							$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
						}
						// レコード番号1のときのみカラムコメント一覧作成
						if($l_loop_cnt == 1){
							$lr_comment_rec[$item_key] = $this->c_column_info->getColumnNameJ($item_key);
						}
					}
				}
			}
		}
		if($this->debug_mode==1){print("Step-viewレコード取得");print "<br>";}
		if($this->debug_mode==1){print_r($lr_view_rec);print "<br>";}
		if($this->debug_mode==1){print("Step-commentレコード取得");print "<br>";}
		if($this->debug_mode==1){print_r($lr_comment_rec);print "<br>";}

		// レコードをセット
		$this->r_view_rec		= $lr_view_rec;
		$this->r_col_name		= $lr_comment_rec;
		if($this->debug_mode==1){print("Step-queryDBRecordレコードをセット");print "<br>";}

		// 条件初期化
		$this->resetCondition();
		if($this->debug_mode==1){print("Step-queryDBRecord条件初期化");print "<br>";}
	}

/*============================================================================
	getter
  ============================================================================*/
	// レコード全て
	function getViewRecord(){
		$this->queryDBRecord();
		return $this->r_view_rec;
	}
	function getColumnComment(){
		$this->queryDBRecord();
		return $this->r_col_name;
	}

/*============================================================================
	setter
  ============================================================================*/
	// Where
	function setWhereArray($p_data_rec){
		if(count($p_data_rec) > 0){
			$this->r_where			= $p_data_rec;
		}
	}
	// Order by
	function setOrderyBy($p_data_rec){
		if(count($p_data_rec) > 0){
			$this->r_ordery_by		= $p_data_rec;
		}
	}
	// Group by
	function setGroupBy($p_data_rec){
		if(count($p_data_rec) > 0){
			$this->r_group_by		= $p_data_rec;
		}
	}
}
?>
