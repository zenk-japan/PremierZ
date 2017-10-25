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
class m_mail_log extends ModelCommon{
/*******************************************************************************
	クラス名：m_mail_log.php
	処理概要：権限マスタモデル
*******************************************************************************/
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス

	private $debug_mode = 0;
/*============================================================================
	コンストラクタ
	引数:
				$p_rec_get_flag				レコード取得フラグ(Y:取得,N:取得しない)
				$pr_where					where句配列
				$pr_ordery_by				order by句配列
				$pr_group_by				group by句配列
  ============================================================================*/
	function __construct($p_rec_get_flag = 'N', $pr_where = '', $pr_ordery_by = '', $pr_group_by = ''){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct('MAIL_LOG_V');		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__construct継承元のコンストラクタを起動");print "<br>";}

		// カラム情報を取得
		require_once('../mdl/m_column_info.php');
		$this->c_column_info = new ColumnInfo('MAIL_LOG_V');		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__constructカラム情報を取得");print "<br>";}

		// レコード取得用配列セット
		$this->r_where			= $pr_where;
		$this->r_ordery_by		= $pr_ordery_by;
		$this->r_group_by		= $pr_group_by;

		// レコード取得
		if($p_rec_get_flag == 'Y'){
			$this->queryDBRecord();
		}
		if($this->debug_mode==1){print("Step-__constructレコード取得");print "<br>";}

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

		// レコードが返ってきた場合は戻り値をセットする
		if(count($lr_result_rec)>0){
			$l_loop_cnt = 0;
			foreach($lr_result_rec as $key => $value){
				$l_loop_cnt++;
				foreach($value as $item_key => $item_value){
					if(preg_match("/^[0-9]+$/",$item_key) ){
						// 配列キーが数値の部分は無視
					} else {
						//print "item_key:".$item_key." item_value:".$item_value."<br>";
						// htmlspecialchars適用
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

		// レコードをセット
		$this->r_view_rec		= $lr_view_rec;
		$this->r_col_name		= $lr_comment_rec;
		if($this->debug_mode==1){print("Step-queryDBRecordレコードをセット");print "<br>";}

		// 条件初期化
		$this->resetCondition();
		if($this->debug_mode==1){print("Step-queryDBRecord条件初期化");print "<br>";}
	}

/*============================================================================
	特定カラムデータ取得
	特定カラムのデータ一覧を取得する
	引数:			$p_item						取得対象項目
					$p_obtain_duplicate_flag	重複値削除フラグ(Y:する,N:しない)
  ============================================================================*/
	function getColumnValueAll($p_item, $p_obtain_duplicate_flag = 'Y'){
		if($this->debug_mode==1){print("Step-getColumnValueAll開始");print "<br>";}
		$lr_return_value = "";

		// 項目指定がない場合は終了
		if(is_null($p_item) || $p_item == ''){
			return false;
		}

		// レコードが0件の場合は終了
		if(count($this->r_view_rec) == 0){
			return false;
		}

		// レコードを検索し、指定の項目のみ取得
		$lr_value = "";
		foreach($this->r_view_rec as $l_rec_num => $l_rec_data){
			$lr_value[$l_rec_num] = $l_rec_data[$p_item];
		}

		// 重複削除
		if($p_obtain_duplicate_flag == 'Y'){
			$lr_return_value = array_unique($lr_value);
		}else{
			$lr_return_value = $lr_value;
		}

		// 並べ替え
		sort ($lr_return_value);

		if($this->debug_mode==1){print("Step-getColumnValueAll終了");print "<br>";}
		return $lr_return_value;
	}

/*============================================================================
	新規登録処理
	処理概要：INSERT処理を行う
  ============================================================================*/
	function insertRecord(){
		// レコードが設定されていない場合は処理しない
		if(count($this->r_table_rec) == 0){
			return false;
		}

		// SQL実行クラスインスタンス作成
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		foreach($this->r_table_rec as $l_rec_num=>$lr_data){
			$l_sql = "";
			// SQL組み立て
			$l_sql  = "insert into `MAIL_LOG` ";
			$l_sql .= "(";
			$l_sql .= "DATA_ID,";
			//$l_sql .= "'MAIL_LOG_ID',";
			$l_sql .= "SEND_USER_ID,";
			$l_sql .= "FROM_ADDRESS,";
			$l_sql .= "TO_ADDRESS,";
			$l_sql .= "CC_ADDRESS,";
			$l_sql .= "BCC_ADDRESS,";
			$l_sql .= "MAIL_TITLE,";
			$l_sql .= "MAIL_BODY,";
			$l_sql .= "SEND_PURPOSE,";
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
			$l_sql .= $lr_data["DATA_ID"].",";						// DATA_ID
			//$l_sql .= "'',";										// MAIL_LOG_ID
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["SEND_USER_ID"])."',";			// SEND_USER_ID
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["FROM_ADDRESS"])."',";			// FROM_ADDRESS
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["TO_ADDRESS"])."',";				// TO_ADDRESS
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["CC_ADDRESS"])."',";				// CC_ADDRESS
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["BCC_ADDRESS"])."',";				// BCC_ADDRESS
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["MAIL_TITLE"])."',";				// MAIL_TITLE
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["MAIL_BODY"])."',";				// MAIL_BODY
			$l_sql .= "'".$this->getMysqlEscapedValue($lr_data["SEND_PURPOSE"])."',";			// SEND_PURPOSE
			$l_sql .= "null,";	// $lr_data["RESERVE_1"].","		// RESERVE_1
			$l_sql .= "null,";	// $lr_data["RESERVE_2"].","		// RESERVE_2
			$l_sql .= "null,";	// $lr_data["RESERVE_3"].","		// RESERVE_3
			$l_sql .= "null,";	// $lr_data["RESERVE_4"].","		// RESERVE_4
			$l_sql .= "null,";	// $lr_data["RESERVE_5"].","		// RESERVE_5
			$l_sql .= "null,";	// $lr_data["RESERVE_6"].","		// RESERVE_6
			$l_sql .= "null,";	// $lr_data["RESERVE_7"].","		// RESERVE_7
			$l_sql .= "null,";	// $lr_data["RESERVE_8"].","		// RESERVE_8
			$l_sql .= "null,";	// $lr_data["RESERVE_9"].","		// RESERVE_9
			$l_sql .= "null,";	// $lr_data["RESERVE_10"].","		// RESERVE_10
			$l_sql .= "'".$lr_data["VALIDITY_FLAG"]."',";			// VALIDITY_FLAG
			$l_sql .= "now(),";										// REGISTRATION_DATET
			$l_sql .= $lr_data["USER_ID"].",";						// REGISTRATION_USER_ID
			$l_sql .= "now(),";										// LAST_UPDATE_DATET
			$l_sql .= $lr_data["USER_ID"];							// LAST_UPDATE_USER_ID
			$l_sql .= ")";

			//print $l_sql;
			// SQL実行
			$l_retcode = $lc_cex->CommonSilentSQL($l_sql);
		}
		return $l_retcode;
	}

/*============================================================================
	削除処理
	処理概要：DELETE処理を行う
  ============================================================================*/
	function deleteRecord(){
		$l_where_phrase	= "";

		// Where句を取得
		if(count($this->r_where) > 0){
			$l_where_phrase	= $this->getWherePhrase($this->r_where);
		}

		// SQL組み立て
		$l_sql  = "delete from `MAIL_LOG` ";
		$l_sql .= $l_where_phrase;

		// SQL実行クラスインスタンス作成
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		//return $l_sql;
		// SQL実行
		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);

		return $l_retcode;
	}

/*============================================================================
	Where句作成処理
	処理概要：Where句を作成する
  ============================================================================*/
	function makeWherePhrase($pr_params){
		$lr_where			= "";
		$l_where_cnt		= 0;

		$l_data_id			= "data_id";
		$l_send_from		= "send_from";
		$l_send_to			= "send_to";
		$l_send_purpose		= "send_purpose";
		$l_date_from		= "date_from";
		$l_date_to			= "date_to";
		$l_search_phrase	= "search_phrase";

		if(count($pr_params) > 0){
			// DATA_ID
			if (!is_null($pr_params[$l_data_id]) && $pr_params[$l_data_id] != ""){
				$lr_where[$l_where_cnt++] = "DATA_ID = ".$this->getMysqlEscapedValue($pr_params[$l_data_id]);
			}
			// 送信元
			if (!is_null($pr_params[$l_send_from]) && $pr_params[$l_send_from] != ""){
				$lr_where[$l_where_cnt++] = "FROM_ADDRESS like '%".$this->getMysqlEscapedValue($pr_params[$l_send_from])."%'";
			}
			// 送信先
			if (!is_null($pr_params[$l_send_to]) && $pr_params[$l_send_to] != ""){
				$lr_where[$l_where_cnt++] = "TO_ADDRESS like '%".$this->getMysqlEscapedValue($pr_params[$l_send_to])."%'";
			}
			// 送信目的
			if (!is_null($pr_params[$l_send_purpose]) && $pr_params[$l_send_purpose] != ""){
				$lr_where[$l_where_cnt++] = "SEND_PURPOSE like '%".$this->getMysqlEscapedValue($pr_params[$l_send_purpose])."%'";
			}

			// 送信日時From
			if (!is_null($pr_params[$l_date_from]) && $pr_params[$l_date_from] != "") {
				$lr_where[$l_where_cnt++] = "DATE_FORMAT(LAST_UPDATE_DATET, '%Y-%m-%d') >= '".$this->getMysqlEscapedValue($pr_params[$l_date_from])."'";
			}

			// 送信日時To
			if (!is_null($pr_params[$l_date_to]) && $pr_params[$l_date_to] != "") {
				$lr_where[$l_where_cnt++] = "DATE_FORMAT(LAST_UPDATE_DATET, '%Y-%m-%d') <= '".$this->getMysqlEscapedValue($pr_params[$l_date_to])."'";
			}
			// タイトル/本文
			if (!is_null($pr_params[$l_search_phrase]) && $pr_params[$l_search_phrase] != "") {
				$lr_where[$l_where_cnt++] = "( MAIL_TITLE like '%".$pr_params[$l_search_phrase]."%' or MAIL_BODY like '%".$this->getMysqlEscapedValue($pr_params[$l_search_phrase])."%')";
			}

		}
		return $lr_where;
	}
/*============================================================================
	getter
  ============================================================================*/
	// レコード全て
	function getViewRecord(){
		return $this->r_view_rec;
	}
	function getColumnComment(){
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
	// 保存用レコード
	function setSaveRecord($p_data_rec){
		if(count($p_data_rec) > 0){
			if(count(array_slice($p_data_rec,0,1)) > 1){
				// 複数レコード場合
				$this->r_table_rec = $p_data_rec;
			}else{
				// 単一レコードの場合
				$this->r_table_rec[0] = $p_data_rec;
			}
		}
	}

}
?>