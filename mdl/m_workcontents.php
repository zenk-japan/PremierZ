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
class m_workcontents extends ModelCommon{
/*******************************************************************************
	クラス名：m_workcontents.php
	処理概要：作業モデル
*******************************************************************************/
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス
	//private $ar_condition;									// 検索条件配列 ※継承元で定義済み
	//private $ar_orderby;										// order by配列 ※継承元で定義済み
	private $set_view_name		= 'WORKCONTENTS_V';			// ビュー名
	private $set_table_name		= 'WORK_CONTENTS';			// テーブル名
	private $primary_key_col	= 'WORK_CONTENT_ID';		// 主キーの項目

	public	$htmlspchar_flag = 'Y';			// htmlspecialchars適用フラグ
	public	$shortname_size  = 25;			// 短縮名の文字サイズ

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
		ModelCommon::__construct($this->set_view_name);		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__construct継承元のコンストラクタを起動");print "<br>";}

		// カラム情報を取得
		require_once('../mdl/m_column_info.php');
		$this->c_column_info	= new ColumnInfo($this->set_view_name);		// ビュー名を指定
		$this->r_col_name		= $this->getColumnCommentArray();
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

		//{print "<pre>";var_dump($l_where_phrase);print "</pre>";}

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
						if($this->htmlspchar_flag == 'Y'){
							// htmlspecialchars適用
							if($item_value != ''){
								$lr_view_rec[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);		// レコードの先頭番号は1
							}else{
								// 空の項目は不正な値がセットされてしまう為、適用しない
								$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
							}
							// レコード番号1のときのみカラムコメント一覧作成
							/*
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = htmlspecialchars($this->c_column_info->getColumnNameJ($item_key));
							}
							*/
							// 会社名、作業名は表示用の短縮名を作成する
							if($item_key=="ENDUSER_COMPANY_NAME" or $item_key=="REQUEST_COMPANY_NAME" or $item_key=="WORK_NAME"){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}

						}else{
							// htmlspecialchars非適用
							$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
							// レコード番号1のときのみカラムコメント一覧作成
							/*
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = $this->c_column_info->getColumnNameJ($item_key);
							}
							*/
						}
					}
				}
			}
		}

		// レコードをセット
		$this->r_view_rec		= $lr_view_rec;
		//$this->r_col_name		= $lr_comment_rec;
		if($this->debug_mode==1){print("Step-queryDBRecordレコードをセット");print "<br>";}

		// 条件初期化
		$this->resetCondition();
		if($this->debug_mode==1){print("Step-queryDBRecord条件初期化");print "<br>";}
	}
/*============================================================================
	カラム名一覧の作成
	カラム情報のレコードからカラム名一覧を作成する
  ============================================================================*/
	function getColumnCommentArray(){
		$lr_return_rec = "";
		foreach ($this->c_column_info->getColumnInfoAll() as $l_rec_num => $lr_rec_data){
			$lr_return_rec[$lr_rec_data["column_name"]]	= $lr_rec_data["column_comment"];
		}
		return $lr_return_rec;
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
	時刻再設定処理
	処理概要：時刻が H:i 形式の場合、入力された日付から Y-m-d H:i:s に変換する
			$p_time				時刻
			$p_date				日付
  ============================================================================*/
	function resetTime($p_time, $p_date){

		if (count(explode('-',$p_time)) > 2){
		// 既にY-m-d H:i:s形式の場合はそのまま
			return $p_time;
		}else{
			// 日付クラス読み込み
			require_once('../lib/CommonDate.php');
			$lc_cdate = new CommonDate();
		// 作業日からY-m-d H-i-s形式の日付に変更する
			$l_newdate = $lc_cdate->getDateByHHMM($p_time, $p_date);
			$p_time = date('Y-m-d H:i:s', strtotime($l_newdate));
		}
		return $p_time;
	}
/*============================================================================
	新規登録処理
	処理概要：INSERT処理を行う
			$pr_data				更新するカラムと値の連想配列(文字列は''で囲む事)
			$p_user_id				更新者キー値
  ============================================================================*/
	function execInsert($pr_data, $p_user_id = ''){
		// レコードが設定されていない場合は処理しない
		if(count($pr_data) == 0){
			return false;
		}

		// 入店予定時刻のセット
		if ($pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"] != ""){
			$pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"] = $this->resetTime($pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"], $pr_data["WORK_DATE"]);
		}
		// 退店予定時刻のセット
		if ($pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"] != ""){
			$pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"] = $this->resetTime($pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"], $pr_data["WORK_DATE"]);
		}

		//****************************************************************
		// Insert文作成
		// ※DATA型とNumber型は値が無い場合0がセットされてしまう為、INSERT文から外す
		$l_sql  = "insert into `WORK_CONTENTS` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'WORK_CONTENT_ID',";
		$l_sql .= "WORK_CONTENT_CODE,";
		$l_sql .= "ESTIMATE_ID,";
		$l_sql .= "WORK_DATE,";
		$l_sql .= "DEFAULT_ENTERING_SCHEDULE_TIMET,";
		$l_sql .= "DEFAULT_LEAVE_SCHEDULE_TIMET,";
		if ($pr_data["DEFAULT_WORKING_TIME"] != ""){$l_sql .= "DEFAULT_WORKING_TIME,";}
		if ($pr_data["DEFAULT_BREAK_TIME"] != ""){$l_sql .= "DEFAULT_BREAK_TIME,";}
		if ($pr_data["AGGREGATE_TIMET"] != ""){$l_sql .= "AGGREGATE_TIMET,";}
		$l_sql .= "AGGREGATE_POINT,";
		if ($pr_data["WORK_ARRANGEMENT_ID"] != ""){$l_sql .= "WORK_ARRANGEMENT_ID,";}
		$l_sql .= "WORK_CONTENT_DETAILS,";
		$l_sql .= "BRINGING_GOODS,";
		$l_sql .= "CLOTHES,";
		$l_sql .= "INTRODUCE,";
		$l_sql .= "TRANSPORT_AMOUNT_REMARKS,";
		$l_sql .= "OTHER_REMARKS,";
		if ($pr_data["OTHER_COST"] != ""){$l_sql .= "OTHER_COST,";}
		if ($pr_data["EXCESS_AMOUNT"] != ""){$l_sql .= "EXCESS_AMOUNT,";}
		$l_sql .= "EXCESS_LIQUIDATION_FLAG,";
		if ($pr_data["CANCEL_CHARGE"] != ""){$l_sql .= "CANCEL_CHARGE,";}
		if ($pr_data["TOTAL_SALES"] != ""){$l_sql .= "TOTAL_SALES,";}
		if ($pr_data["GROSS_MARGIN"] != ""){$l_sql .= "GROSS_MARGIN,";}
		if ($pr_data["GROSS_MARGIN_RATE"] != ""){$l_sql .= "GROSS_MARGIN_RATE,";}
		$l_sql .= "WORK_STATUS,";
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
		$l_sql .= $pr_data["DATA_ID"].",";																// DATA_ID
		//$l_sql .= "'',";																				// WORK_CONTENT_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_CONTENT_CODE"])."',";												// WORK_CONTENT_CODE
		$l_sql .= $pr_data["ESTIMATE_ID"].",";															// ESTIMATE_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_DATE"])."',";														// WORK_DATE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"])."',";									// DEFAULT_ENTERING_SCHEDULE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"])."',";									// DEFAULT_LEAVE_SCHEDULE_TIMET
		if ($pr_data["DEFAULT_WORKING_TIME"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["DEFAULT_WORKING_TIME"]).",";}	// DEFAULT_WORKING_TIME
		if ($pr_data["DEFAULT_BREAK_TIME"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["DEFAULT_BREAK_TIME"]).",";}		// DEFAULT_BREAK_TIME
		if ($pr_data["AGGREGATE_TIMET"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["AGGREGATE_TIMET"])."',";}			// AGGREGATE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["AGGREGATE_POINT"])."',";													// AGGREGATE_POINT
		if ($pr_data["WORK_ARRANGEMENT_ID"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["WORK_ARRANGEMENT_ID"]).",";}		// WORK_ARRANGEMENT_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_CONTENT_DETAILS"])."',";											// WORK_CONTENT_DETAILS
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["BRINGING_GOODS"])."',";													// BRINGING_GOODS
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["CLOTHES"])."',";															// CLOTHES
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["INTRODUCE"])."',";														// INTRODUCE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["TRANSPORT_AMOUNT_REMARKS"])."',";										// TRANSPORT_AMOUNT_REMARKS
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["OTHER_REMARKS"])."',";													// OTHER_REMARKS
		if ($pr_data["OTHER_COST"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["OTHER_COST"]).",";}						// OTHER_COST
		if ($pr_data["EXCESS_AMOUNT"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["EXCESS_AMOUNT"]).",";}					// EXCESS_AMOUNT
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["EXCESS_LIQUIDATION_FLAG"])."',";											// EXCESS_LIQUIDATION_FLAG
		if ($pr_data["CANCEL_CHARGE"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["CANCEL_CHARGE"]).",";}					// CANCEL_CHARGE
		if ($pr_data["TOTAL_SALES"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["TOTAL_SALES"]).",";}						// TOTAL_SALES
		if ($pr_data["GROSS_MARGIN"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["GROSS_MARGIN"]).",";}					// GROSS_MARGIN
		if ($pr_data["GROSS_MARGIN_RATE"] != ""){$l_sql .= $this->getMysqlEscapedValue($pr_data["GROSS_MARGIN_RATE"]).",";}			// GROSS_MARGIN_RATE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_STATUS"])."',";														// WORK_STATUS
		$l_sql .= "null,";	// $pr_data["RESERVE_1"].","			// RESERVE_1
		$l_sql .= "null,";	// $pr_data["RESERVE_2"].","			// RESERVE_2
		$l_sql .= "null,";	// $pr_data["RESERVE_3"].","			// RESERVE_3
		$l_sql .= "null,";	// $pr_data["RESERVE_4"].","			// RESERVE_4
		$l_sql .= "null,";	// $pr_data["RESERVE_5"].","			// RESERVE_5
		$l_sql .= "null,";	// $pr_data["RESERVE_6"].","			// RESERVE_6
		$l_sql .= "null,";	// $pr_data["RESERVE_7"].","			// RESERVE_7
		$l_sql .= "null,";	// $pr_data["RESERVE_8"].","			// RESERVE_8
		$l_sql .= "null,";	// $pr_data["RESERVE_9"].","			// RESERVE_9
		$l_sql .= "null,";	// $pr_data["RESERVE_10"].","			// RESERVE_10
		if ($pr_data["VALIDITY_FLAG"] != ""){$l_sql .= "'".$pr_data["VALIDITY_FLAG"]."',";}else{$l_sql .= "'Y',";}				// VALIDITY_FLAG
		$l_sql .= "now(),";											// REGISTRATION_DATET
		$l_sql .= $p_user_id.",";									// REGISTRATION_USER_ID
		$l_sql .= "now(),";											// LAST_UPDATE_DATET
		$l_sql .= $p_user_id;										// LAST_UPDATE_USER_ID
		$l_sql .= ")";

		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);

		if ($l_retcode == RETURN_NOMAL){
			return true;
		}else{
			return $l_retcode;
		}
	}

/*============================================================================
	更新処理実行
	引数:
			$pr_data						更新するカラムと値の連想配列(文字列は''で囲む事)
			$p_key_value					更新対象キー値
			$p_user_id						更新者キー値
  ============================================================================*/
	function execUpdate($pr_data, $p_key_value, $p_user_id = ''){

		// レコードが空の場合は何もしない
		if(count($pr_data) == 0){
			return false;
		}

		// 入店予定時刻のセット
		if ($pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"] != ""){
			$pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"] = $this->resetTime($pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"], $pr_data["WORK_DATE"]);
		}
		// 退店予定時刻のセット
		if ($pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"] != ""){
			$pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"] = $this->resetTime($pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"], $pr_data["WORK_DATE"]);
		}

		// SQL組み立て
		$l_sql  = "update `WORK_CONTENTS` ";
		$l_sql .= "set ";
		$l_sql .= $this->getUpdataSetPhrase("WORK_CONTENT_CODE", 				$pr_data["WORK_CONTENT_CODE"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_DATE", 						$pr_data["WORK_DATE"], 							'C').",";
		$l_sql .= $this->getUpdataSetPhrase("DEFAULT_ENTERING_SCHEDULE_TIMET", 	$pr_data["DEFAULT_ENTERING_SCHEDULE_TIMET"], 	'C').",";
		$l_sql .= $this->getUpdataSetPhrase("DEFAULT_LEAVE_SCHEDULE_TIMET", 	$pr_data["DEFAULT_LEAVE_SCHEDULE_TIMET"], 		'C').",";
		$l_sql .= $this->getUpdataSetPhrase("DEFAULT_WORKING_TIME", 			$pr_data["DEFAULT_WORKING_TIME"], 				'C').",";
		$l_sql .= $this->getUpdataSetPhrase("DEFAULT_BREAK_TIME", 				$pr_data["DEFAULT_BREAK_TIME"], 				'C').",";
		$l_sql .= $this->getUpdataSetPhrase("AGGREGATE_TIMET", 					$pr_data["AGGREGATE_TIMET"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("AGGREGATE_POINT", 					$pr_data["AGGREGATE_POINT"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_ARRANGEMENT_ID", 				$pr_data["WORK_ARRANGEMENT_ID"], 				'N').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_CONTENT_DETAILS", 			$pr_data["WORK_CONTENT_DETAILS"], 				'C').",";
		$l_sql .= $this->getUpdataSetPhrase("BRINGING_GOODS", 					$pr_data["BRINGING_GOODS"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("CLOTHES", 							$pr_data["CLOTHES"], 							'C').",";
		$l_sql .= $this->getUpdataSetPhrase("INTRODUCE", 						$pr_data["INTRODUCE"], 							'C').",";
		$l_sql .= $this->getUpdataSetPhrase("TRANSPORT_AMOUNT_REMARKS", 		$pr_data["TRANSPORT_AMOUNT_REMARKS"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("OTHER_REMARKS", 					$pr_data["OTHER_REMARKS"], 						'C').",";
		$l_sql .= $this->getUpdataSetPhrase("OTHER_COST", 						$pr_data["OTHER_COST"], 						'N').",";
		$l_sql .= $this->getUpdataSetPhrase("EXCESS_AMOUNT", 					$pr_data["EXCESS_AMOUNT"], 						'N').",";
		$l_sql .= $this->getUpdataSetPhrase("EXCESS_LIQUIDATION_FLAG", 			$pr_data["EXCESS_LIQUIDATION_FLAG"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("CANCEL_CHARGE", 					$pr_data["CANCEL_CHARGE"], 						'N').",";
		$l_sql .= $this->getUpdataSetPhrase("TOTAL_SALES", 						$pr_data["TOTAL_SALES"], 						'N').",";
		$l_sql .= $this->getUpdataSetPhrase("GROSS_MARGIN", 					$pr_data["GROSS_MARGIN"], 						'N').",";
		$l_sql .= $this->getUpdataSetPhrase("GROSS_MARGIN_RATE", 				$pr_data["GROSS_MARGIN_RATE"], 					'N').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_STATUS", 						$pr_data["WORK_STATUS"], 						'C').",";
		$l_sql .= "VALIDITY_FLAG = '".$pr_data["VALIDITY_FLAG"]."',";				// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";										// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = "			.$p_user_id." ";				// LAST_UPDATE_USER_ID
		$l_sql .= "where WORK_CONTENT_ID = "		.$p_key_value;

//var_dump($l_sql);
//return;
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();

		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);


		if ($l_retcode == RETURN_NOMAL){
			return true;
		}else{
			return $l_retcode;
		}
	}

/*============================================================================
	新規登録処理
	処理概要：
				r_table_recにセットされたレコードを新規登録処理を行う
	引数:
			$p_user_id					更新者ユーザーID
  ============================================================================*/
	function insertRecord($p_user_id){
		//print_r($this->r_table_rec);

		if ($this->r_table_rec[0]['nm_rd_copytype'] == 'S'){
		// １日分作成の場合はそのままInsert
			foreach($this->r_table_rec as $l_recnum => $lr_record){
				if(!$this->execInsert($lr_record, $p_user_id)){
					// エラーとなるレコードが発生した時点で終了
					return false;
				}
			}
		}else{
		// 指定範囲で作成の場合
			// trueを1、falseを0に変換
			if ($this->r_table_rec[0]['nm_copy_day_1'] == 'true'){$l_copy_day_1 = 1;}else{$l_copy_day_1 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_2'] == 'true'){$l_copy_day_2 = 1;}else{$l_copy_day_2 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_3'] == 'true'){$l_copy_day_3 = 1;}else{$l_copy_day_3 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_4'] == 'true'){$l_copy_day_4 = 1;}else{$l_copy_day_4 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_5'] == 'true'){$l_copy_day_5 = 1;}else{$l_copy_day_5 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_6'] == 'true'){$l_copy_day_6 = 1;}else{$l_copy_day_6 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_7'] == 'true'){$l_copy_day_7 = 1;}else{$l_copy_day_7 = 0;}

			// 日付配列取得関数実行
			require_once('../lib/CommonDate.php');
			$c_commondate = new CommonDate();
			$lr_work_date = $c_commondate->getInTermDate(
								$this->r_table_rec[0]['nm_copy_date_from'],
								$this->r_table_rec[0]['nm_copy_date_to'],
								$l_copy_day_1,
								$l_copy_day_2,
								$l_copy_day_3,
								$l_copy_day_4,
								$l_copy_day_5,
								$l_copy_day_6,
								$l_copy_day_7
							);
			//var_dump($lr_work_date);
			//var_dump($lr_work_date[0]);
			//var_dump($lr_work_date[count($lr_work_date) - 1]);

			// 取得した日付範囲の作業日を取得する
			$lr_where_cond = array();
			$lr_where_cond[] = "DATA_ID = ".$this->r_table_rec[0]['DATA_ID'];
			$lr_where_cond[] = "VALIDITY_FLAG = 'Y'";
			$lr_where_cond[] = "ESTIMATE_ID = ".$this->r_table_rec[0]['ESTIMATE_ID'];
			$lr_where_cond[] = "WORK_CONTENT_CODE = ".$this->r_table_rec[0]['WORK_CONTENT_CODE'];
			$lr_where_cond[] = "(WORK_DATE >= DATE('".$lr_work_date[0]."'))";
			$lr_where_cond[] = "(WORK_DATE <= DATE('".$lr_work_date[count($lr_work_date) - 1]."'))";
			//var_dump($lr_where_cond);
			$this->setWhereArray($lr_where_cond);			// 条件セット
			$this->queryDBRecord();							// クエリ実行
			$lr_inner_work		= $this->getViewRecord();	// 結果取得
			$l_cnt_inner_work	= count($lr_inner_work);

			// 取得した日付でループ処理を行う
			foreach ($lr_work_date as $l_insert_date){
				// フラグ初期化
				$l_work_hit_flg = 0;

				// 同一作業コードで既に作業がある場合は、hitフラグを立てる
				if ($l_cnt_inner_work > 0){
					foreach ($lr_inner_work as $lr_inner_work_rec){
						//var_dump($lr_inner_work_rec['WORK_DATE'].":".$l_insert_date);
						if ($lr_inner_work_rec['WORK_DATE'] == $l_insert_date){
							$l_work_hit_flg = 1;
						}
					}
				}

				// hitフラグが立っていない場合はその日の作業を登録する
				if ($l_work_hit_flg == 0){
					// 作業日を変更する
					$this->r_table_rec[0]['WORK_DATE'] = $l_insert_date;

					// insert処理
					if(!$this->execInsert($this->r_table_rec[0], $p_user_id)){
						// エラーとなるレコードが発生した時点で終了
						return false;
					}
				}
			}
		}

		return true;
	}

/*============================================================================
	更新処理
	処理概要：
				r_table_recにセットされたレコードを、プライマリーキーを条件にして
				更新処理を行う
	引数:
				$p_user_id				更新者ユーザーID
  ============================================================================*/
	function updateRecord($p_user_id){
		if ($this->r_table_rec[0]['nm_rd_copytype'] == 'S'){
		// １日分更新の場合はそのままUpdate
			if(is_null($this->r_table_rec[0][$this->primary_key_col]) || $this->r_table_rec[0][$this->primary_key_col] == ''){
				// 主キーの設定が無い場合
				return false;
			}else{
				if(!$this->execUpdate($this->r_table_rec[0], $this->r_table_rec[0][$this->primary_key_col], $p_user_id)){
					// エラーとなるレコードが発生した時点で終了
					return false;
				}
			}
		}else{
		// 指定範囲で更新の場合
			// trueを1、falseを0に変換
			if ($this->r_table_rec[0]['nm_copy_day_1'] == 'true'){$l_copy_day_1 = 1;}else{$l_copy_day_1 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_2'] == 'true'){$l_copy_day_2 = 1;}else{$l_copy_day_2 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_3'] == 'true'){$l_copy_day_3 = 1;}else{$l_copy_day_3 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_4'] == 'true'){$l_copy_day_4 = 1;}else{$l_copy_day_4 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_5'] == 'true'){$l_copy_day_5 = 1;}else{$l_copy_day_5 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_6'] == 'true'){$l_copy_day_6 = 1;}else{$l_copy_day_6 = 0;}
			if ($this->r_table_rec[0]['nm_copy_day_7'] == 'true'){$l_copy_day_7 = 1;}else{$l_copy_day_7 = 0;}

			// 日付配列取得関数実行
			require_once('../lib/CommonDate.php');
			$c_commondate = new CommonDate();
			$lr_work_date = $c_commondate->getInTermDate(
								$this->r_table_rec[0]['nm_copy_date_from'],
								$this->r_table_rec[0]['nm_copy_date_to'],
								$l_copy_day_1,
								$l_copy_day_2,
								$l_copy_day_3,
								$l_copy_day_4,
								$l_copy_day_5,
								$l_copy_day_6,
								$l_copy_day_7
							);
			//var_dump($lr_work_date);
			//var_dump($lr_work_date[0]);
			//var_dump($lr_work_date[count($lr_work_date) - 1]);

			// 取得した日付範囲の作業日を取得する
			$lr_where_cond = array();
			$lr_where_cond[] = "DATA_ID = ".$this->r_table_rec[0]['DATA_ID'];
			$lr_where_cond[] = "VALIDITY_FLAG = 'Y'";
			$lr_where_cond[] = "ESTIMATE_ID = ".$this->r_table_rec[0]['ESTIMATE_ID'];
			$lr_where_cond[] = "WORK_CONTENT_CODE = ".$this->r_table_rec[0]['old_work_content_code']; // 旧コードで取得
			$lr_where_cond[] = "(WORK_DATE >= DATE('".$lr_work_date[0]."'))";
			$lr_where_cond[] = "(WORK_DATE <= DATE('".$lr_work_date[count($lr_work_date) - 1]."'))";
			//var_dump($lr_where_cond);
			$this->setWhereArray($lr_where_cond);			// 条件セット
			$this->queryDBRecord();							// クエリ実行
			$lr_inner_work		= $this->getViewRecord();	// 結果取得
			$l_cnt_inner_work	= count($lr_inner_work);

			// 取得した日付でループ処理を行う
			foreach ($lr_work_date as $l_insert_date){
				// フラグ初期化
				$l_work_hit_flg = 0;

				// 指定範囲内で既存作業がある場合は、その作業をUpdateする
				if ($l_cnt_inner_work > 0){
					foreach ($lr_inner_work as $lr_inner_work_rec){
						//var_dump($lr_inner_work_rec['WORK_DATE'].":".$l_insert_date);
						if ($lr_inner_work_rec['WORK_DATE'] == $l_insert_date){
							// フラグをセット
							$l_work_hit_flg = 1;

							// 作業日を変更する
							$this->r_table_rec[0]['WORK_DATE'] = $l_insert_date;

							// Update処理
							// 既存作業で取得したレコードの主キーでUpdateを行う
							if(is_null($lr_inner_work_rec[$this->primary_key_col]) || $lr_inner_work_rec[$this->primary_key_col] == ''){
								// 主キーの設定が無い場合
								return false;
							}else{
								if(!$this->execUpdate($this->r_table_rec[0], $lr_inner_work_rec[$this->primary_key_col], $p_user_id)){
									// エラーとなるレコードが発生した時点で終了
									return false;
								}
							}
						}
					}
				}

				// hitフラグが立っていない場合はその日の作業をInsertする
				if ($l_work_hit_flg == 0){
				// 作業の登録
					// 作業日を変更する
					$this->r_table_rec[0]['WORK_DATE'] = $l_insert_date;

					// insert処理
					if(!$this->execInsert($this->r_table_rec[0], $p_user_id)){
						// エラーとなるレコードが発生した時点で終了
						return false;
					}

				// 人員を複製する
				// 更新時に選択された作業に紐付く人員をコピーする
					// 作業取得
					$lr_workcontent = "";
					$lr_where = array();
					array_push($lr_where, "ESTIMATE_ID = '".$this->r_table_rec[0]['ESTIMATE_ID']."'");
					array_push($lr_where, "WORK_DATE = '".$this->r_table_rec[0]['WORK_DATE']."'");
					array_push($lr_where, "WORK_CONTENT_CODE = '".$this->r_table_rec[0]['WORK_CONTENT_CODE']."'");
					$this->r_where = $lr_where;
					$this->queryDBRecord();
					$lr_workcontent = $this->getViewRecord();

					// 人員取得
					require_once('../mdl/m_workstaff.php');
					$lr_workstaff = "";
					$lr_where = array();
					array_push($lr_where, "WORK_CONTENT_ID = '".$this->r_table_rec[0]['WORK_CONTENT_ID']."'");
					$lc_workstaff	= new m_workstaff('Y', $lr_where);
					$lr_workstaff	= $lc_workstaff->getViewRecord();

					// 人員登録
					if (count($lr_workstaff) > 0){
						foreach($lr_workstaff as $l_rec_num => $lr_record){
							// 作業IDの付け替え
							$lr_record['WORK_CONTENT_ID'] = $lr_workcontent[1]['WORK_CONTENT_ID'];
							// 承認区分をデフォルトに変更
							$lr_record['APPROVAL_DIVISION'] = "UC";
							// キャンセル区分をデフォルトに変更
							$lr_record['CANCEL_DIVISION'] = "WR";
							// メール送信フラグをデフォルトに変更
							$lr_record['TRANSMISSION_FLAG'] = "N";
							// 超過を0に変更
							$lr_record['EXCESS_AMOUNT'] = 0;
							// 出発予定時間をnullに変更
							$lr_record['DISPATCH_SCHEDULE_TIMET'] = "";
							// 出発時間(作業者)をnullに変更
							$lr_record['DISPATCH_STAFF_TIMET'] = "";
							// 入店時間(作業者)をnullに変更
							$lr_record['ENTERING_STAFF_TIMET'] = "";
							// 入店時間(管理部)をnullに変更
							$lr_record['ENTERING_MANAGE_TIMET'] = "";
							// 退店時間(作業者)をnullに変更
							$lr_record['LEAVE_STAFF_TIMET'] = "";
							// 退店時間(管理部)をnullに変更
							$lr_record['LEAVE_MANAGE_TIMET'] = "";
							// その他手当を0に変更
							$lr_record['OTHER_AMOUNT'] = 0;
							// 残業代を0に変更
							$lr_record['OVERTIME_WORK_AMOUNT'] = 0;
							// 作業費合計を0に変更
							$lr_record['WORK_EXPENSE_AMOUNT_TOTAL'] = 0;
							// 出金合計を0に変更
							$lr_record['PAYMENT_AMOUNT_TOTAL'] = 0;
							// 実作業時間を0に変更
							$lr_record['REAL_WORKING_HOURS'] = 0;
							// 実残業時間を0に変更
							$lr_record['REAL_OVERTIME_HOURS'] = 0;
							// 差引支給額を0に変更
							$lr_record['SUPPLIED_AMOUNT_TOTAL'] = 0;
							// 作業員ステータスをデフォルトに変更
							$lr_record['STAFF_STATUS'] = "BD";

							// 入店予定時間、退店予定時間の日付をコピー先の日付に変更
							require_once('../lib/CommonDate.php');
							$lc_cdate = new CommonDate();
							if ($lr_record['ENTERING_SCHEDULE_TIMET'] != ""){
								$l_time = $lc_cdate->getHiTime($lr_record['ENTERING_SCHEDULE_TIMET']);
								$lr_record['ENTERING_SCHEDULE_TIMET'] = $lc_cdate->getYmdHiTime($l_time, $this->r_table_rec[0]['WORK_DATE']);
							}
							if ($lr_record['LEAVE_SCHEDULE_TIMET'] != ""){
								$l_time = $lc_cdate->getHiTime($lr_record['LEAVE_SCHEDULE_TIMET']);
								$lr_record['LEAVE_SCHEDULE_TIMET'] = $lc_cdate->getYmdHiTime($l_time, $this->r_table_rec[0]['WORK_DATE']);
							}
							
							//var_dump($lr_record);
							$lc_workstaff->setSaveRecord($lr_record);
							if(!$lc_workstaff->insertRecord($p_user_id)){
								return false;
								$l_error_message .= "データを登録できませんでした。";
							}
						}
					}
				}
			}

		}
		return true;
	}
/*============================================================================
	削除処理
	処理概要：
				指定されたIDのレコードを削除する
	引数:
				$p_target				対象ID
  ============================================================================*/
	function deleteRecordByID($p_target){
		$l_del_target_id = "";

		// 対象IDが配列の場合は、カンマ区切りに直す
		if (is_array($p_target)){
			foreach($p_target as $l_key => $l_value){
				if($l_key === 0){
						$l_del_target_id .= $l_value;
				}else {
					$l_del_target_id .=",".$l_value;
				}
			}
		}else{
			$l_del_target_id = $p_target;
		}

		// 始めに作業に紐付く人員データが有る場合はそれを削除する
		// 人員読み込み
		require_once('../mdl/m_workstaff.php');
		$lr_workstaff = "";
		$lr_where = array();
		array_push($lr_where, "WORK_CONTENT_ID in (".$l_del_target_id.")");
		$lc_workstaff	= new m_workstaff('Y', $lr_where);
		$lr_workstaff	= $lc_workstaff->getViewRecord();
		$l_ws_del_target_id = "";

		if (count($lr_workstaff) > 0){
			// 削除する人員IDの設定
			foreach ($lr_workstaff as $l_rec_num => $lr_ws_rec){
				if($l_rec_num === 1){
					$l_ws_del_target_id .= $lr_ws_rec["WORK_STAFF_ID"];
				}else {
					$l_ws_del_target_id .=",".$lr_ws_rec["WORK_STAFF_ID"];
				}
			}
			// 人員削除
			if(!$lc_workstaff->deleteRecordByID($l_ws_del_target_id)){
				echo "人員削除に失敗しました。\n";
				return false;
			}
		}

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".".$this->set_table_name;

		$l_sql = null;
		$l_sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$l_sql .= "where ".$this->primary_key_col." in (".$l_del_target_id.")";

		require_once('../mdl/CommonExecution.php');
		$lc_cexe = new CommonExecution();
		$l_exe_return = $lc_cexe->CommonSilentSQL($l_sql);

		return true;
	}
/*============================================================================
	データチェック
  ============================================================================*/
	function checkData(){
	//	print "count->\n";
	//	print count($this->r_table_rec)."\n";
	//	print "r_table_rec->\n";
	//	var_dump($this->r_table_rec)."\n";
		if(count($this->r_table_rec) > 0){
			require_once('../lib/CommonFormatCheck.php');
			$lc_ccnm = new CommonFormatCheck();

			foreach($this->r_table_rec as $l_key => $lr_data_rec){
				/*--------------------
				   標準チェック
				  --------------------*/
				$lr_check_res[$l_key] = $lc_ccnm->checkValue($lr_data_rec, $this->set_table_name);
				//var_dump($lr_check_res);
				
				/*--------------------
				   個別チェック
				  --------------------*/
				// 作業日
				// コピーの場合は作業日のNULLエラーをキャンセルする
				$l_target_col			= "WORK_DATE";
				if ($lr_data_rec['nm_rd_copytype'] == 'M'){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 0;
				}

				// 作業コード
				// 新規の場合、同じ見積ID、作業日で既に同じ作業コードが使用されている場合はエラーとする
				// 更新の場合は、旧作業コードと新作業コードが異なる場合のみ同様のチェックを行う
				if (    $lr_data_rec["sql_type"] == OPMODE_INSERT
					or (    $lr_data_rec["sql_type"] == OPMODE_UPDATE
						and $lr_data_rec["old_work_content_code"] != $lr_data_rec["WORK_CONTENT_CODE"])
				){
					$l_target_col = "WORK_CONTENT_CODE";

					// 作業取得
					$lr_workcontent = "";
					$lr_where = array();
					array_push($lr_where, "ESTIMATE_ID = '".$lr_data_rec['ESTIMATE_ID']."'");
					array_push($lr_where, "WORK_DATE = '".$this->getMysqlEscapedValue($lr_data_rec['WORK_DATE'])."'");
					array_push($lr_where, "WORK_CONTENT_CODE = '".$this->getMysqlEscapedValue($lr_data_rec['WORK_CONTENT_CODE'])."'");
					$this->r_where = $lr_where;
					$this->queryDBRecord();
					$lr_workcontent = $this->getViewRecord();

					// 取得できたらエラー
					if (count($lr_workcontent) > 0){
						$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "作業コード「".$lr_data_rec['WORK_CONTENT_CODE']."」は、この作業日で既に使用されています。\n他の値に変更して下さい。\n";
					}
				}
				// 入店予定時刻、退店予定時刻
				// nn:nn形式で記述されてい無い場合はエラーとする
				$l_target_col = "DEFAULT_ENTERING_SCHEDULE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「入店予定時刻」は、hh:mm形式で入力して下さい。\n";
				}
				$l_target_col = "DEFAULT_LEAVE_SCHEDULE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「退店予定時刻」は、hh:mm形式で入力して下さい。\n";
				}
				
				// 入店 > 退店の場合はエラーとする
				$l_target_col1 = "DEFAULT_ENTERING_SCHEDULE_TIMET";
				$l_target_col2 = "DEFAULT_LEAVE_SCHEDULE_TIMET";
				if ($lc_ccnm->chekTimeMagnitudeRelationship($lr_data_rec[$l_target_col1], $lr_data_rec[$l_target_col2]) < 2){
					$lr_check_res[$l_key][$l_target_col2]['STATUS']		= 2;
					$lr_check_res[$l_key][$l_target_col2]['MESSAGE']	= "「退店予定時刻」は、「入店予定時刻」以降で入力して下さい。\n";
				}

				// 作業纏め者所属会社
				require_once('../mdl/m_company_master.php');
				// マスターを検索し、存在を確認する
				$l_target_col			= "WORK_ARRANGEMENT_COMPANY_NAME";
				$l_target_col_id		= "WORK_ARRANGEMENT_COMPANY_ID";
				$l_target_col_mst_id	= "COMPANY_ID";
				$l_target_col_name		= "作業纏め者所属会社";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// 会社マスタから会社名をキーに値を取得(会社区分は敢えて条件に加えない)
					$lr_where = array();
					array_push($lr_where, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_where, "COMPANY_NAME = '".$this->getMysqlEscapedValue($lr_data_rec[$l_target_col])."'");
					array_push($lr_where, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_where);
					$lc_master_mdl_class	= new m_company_master('Y', $lr_where);
					$lr_master_mdl			= $lc_master_mdl_class->getViewRecord();

					// 会社が見つからない場合は、メッセージを追加する
					if (count($lr_master_mdl) == 0){
						$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= $l_target_col_name."「".$lr_data_rec[$l_target_col]."」は存在しません。修正して下さい。\n";
						//var_dump($lr_check_res);
					//}else{
					// 作業纏め者の所属会社はレコードにセットしない為、見つかっても何もしない
					}
				}


				// 作業纏め者
				require_once('../mdl/m_user_master.php');
				// マスターを検索し、存在を確認する
				$l_target_col			= "WORK_ARRANGEMENT_USER_NAME";
				$l_target_col_id		= "WORK_ARRANGEMENT_ID";
				$l_target_col_mst_id	= "USER_ID";
				$l_target_col_name		= "作業纏め者";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// ユーザーマスタからユーザー名をキーに値を取得
					$lr_where = array();
					array_push($lr_where, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_where, "NAME = '".$this->getMysqlEscapedValue($lr_data_rec[$l_target_col])."'");
					array_push($lr_where, "COMPANY_NAME = '".$this->getMysqlEscapedValue($lr_data_rec['WORK_ARRANGEMENT_COMPANY_NAME'])."'");
					array_push($lr_where, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_where);
					$lc_master_mdl_class	= new m_user_master('Y', $lr_where);
					$lr_master_mdl			= $lc_master_mdl_class->getViewRecord();
					// ユーザーが見つからない場合は、メッセージを追加する
					if (count($lr_master_mdl) == 0){
						$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= $l_target_col_name."「".$lr_data_rec[$l_target_col]."」は存在しません。修正して下さい。\n";
						//var_dump($lr_check_res);
					}else{
					// 見つかった場合は、コードをテーブルにセットする
						//var_dump($lr_master_mdl[1][$l_target_col_mst_id]);
						$this->r_table_rec[$l_key][$l_target_col_id] = $lr_master_mdl[1][$l_target_col_mst_id];
						//$this->r_table_rec[$l_key]
					}
				}


				// コピー範囲の日付
				$l_target_from_col		= "nm_copy_date_from";
				$l_target_from_name		= "コピー期間開始日";
				if ($lr_data_rec[$l_target_from_col] != ""){
					if (!$lc_ccnm->checkDateString($lr_data_rec[$l_target_from_col])){
						$lr_check_res[$l_key][$l_target_from_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_from_col]['MESSAGE']	= $l_target_from_name."「".$lr_data_rec[$l_target_from_col]."」は正しくありません。修正して下さい。\n";
					}
				}
				$l_target_to_col		= "nm_copy_date_to";
				$l_target_to_name		= "コピー期間終了日";
				if ($lr_data_rec[$l_target_to_col] != ""){
					if (!$lc_ccnm->checkDateString($lr_data_rec[$l_target_to_col])){
						$lr_check_res[$l_key][$l_target_to_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_to_col]['MESSAGE']	= $l_target_to_name."「".$lr_data_rec[$l_target_to_col]."」は正しくありません。修正して下さい。\n";
					}
				}
				// コピー範囲の日付開始>終了の場合はエラーとする
				if (	$lr_data_rec[$l_target_from_col] != ''
					and $lr_data_rec[$l_target_to_col] != ''
					and strtotime($lr_data_rec[$l_target_from_col]) > strtotime($lr_data_rec[$l_target_to_col])
				){
					$lr_check_res[$l_key][$l_target_from_col]['STATUS']		= 2;
					$lr_check_res[$l_key][$l_target_from_col]['MESSAGE']	= "「".$l_target_to_name."」は、「".$l_target_from_name."」以降で設定して下さい。\n";
				}
			}
			//print var_dump($lr_check_res)."<br>";
			return $lr_check_res;
		}else{
			return false;
		}
	}
/*============================================================================
	getter
  ============================================================================*/
	// レコード全て
	function getViewRecord(){
		return $this->r_view_rec;
	}
	// カラムのコメント(日本語名)一覧取得
	function getColumnComment(){
		return $this->r_col_name;
	}
	// 作業初日
	function getWorkDate($p_mode = 'first'){
		$lr_work_date = array();
		$l_return_value = "";

		if (count($this->r_view_rec) > 0){
			// 配列に全作業日を格納
			foreach ($this->r_view_rec as $l_rec_num => $lr_rec){
				$lr_work_date[] = $lr_rec['WORK_DATE'];
			}

			// 並び替え
			asort($lr_work_date);

			// 返り値設定
			if ($p_mode == 'first'){
				// 初日
				$l_return_value = $lr_work_date[0];
			}else if ($p_mode == 'last'){
				// 最終日
				$l_return_value = $lr_work_date[sizeof($lr_work_date) - 1];
			}
		}

		return $l_return_value;
	}
	// 作業最終日

/*============================================================================
	setter
  ============================================================================*/
	// Where
	function setWhereArray($pr_data){
		if(count($pr_data) > 0){
			$this->r_where			= $pr_data;
		}
	}
	// Order by
	function setOrderyBy($pr_data){
		if(count($pr_data) > 0){
			$this->r_ordery_by		= $pr_data;
		}
	}
	// Group by
	function setGroupBy($pr_data){
		if(count($pr_data) > 0){
			$this->r_group_by		= $pr_data;
		}
	}
	// 保存用レコード
	function setSaveRecord($pr_data){
		if(count($pr_data) > 0){
			$this->r_table_rec = "";
			$l_rec_cnt = 0;
			$l_single_flag = 0;

			foreach($pr_data as $l_key => $l_value){
				if(is_array($l_value)){
					// 複数レコード場合
					$l_single_flag = 0;
				}else{
					// 単一レコードの場合
					$l_single_flag = 1;
				}
			}

			if($l_single_flag == 0){
				// 複数レコード場合
				$this->r_table_rec = $pr_data;
			}else{
				// 単一レコードの場合
				$this->r_table_rec[0] = $pr_data;
			}
			/*
			if(count(array_slice($pr_data,0,1)) > 1){
				// 複数レコード場合
				$this->r_table_rec = $pr_data;
			}else{
				// 単一レコードの場合
				$this->r_table_rec[0] = $pr_data;
			}
			*/
		}
	}
/*============================================================================
	一覧表示
	引数:
				$estimates_array
				&$w_workcontents
  ============================================================================*/
	function workcontents_list($estimates_array,&$w_workcontents){
		// 一覧表示
		require_once('../mdl/Workcontents_v.php');
		$dbobj = new Workcontents_v();

		// POSTされた項目から条件を設定
		// 検索キーの設定
		$dataid				=	$estimates_array["DATA_ID"];			//データID
		$estimateid			=	$estimates_array["ESTIMATE_ID"];		//見積ID
		$displaydelete		=	$estimates_array["DELETE_CHECK"];		//削除済表示
		$workdate			=	$estimates_array["WORK_DATE"];			//作業日

		// 削除済表示を元に有効フラグの設定
		if($displaydelete == '' ){
			$validityflag	=	'Y';							//有効フラグ(Yのみ)
		} else {
			$validityflag	=	'';								//有効フラグ(全て)
		}

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"			=> $dataid,
								"ESTIMATE_ID"		=> $estimateid,
								"WORK_DATE"			=> "%".$workdate."%",
								"VALIDITY_FLAG"		=> "%".$validityflag."%"
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array(
								"DATA_ID",
								"WORK_DATE",
								"DEFAULT_ENTERING_SCHEDULE_TIMET"
								);

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$w_workcontents = $dbobj->getRecord();
	}

/*============================================================================
	更新表示用検索
	引数:
				&$data_sel
  ============================================================================*/
	// 更新表示用検索
	function workcontents_ups(&$data_sel){
		require_once('../mdl/Workcontents_v.php');
		$dbobj = new Workcontents_v();

		// 検索キーの設定
		$dataid				=	$_POST["hd_dataid"];			//データID
		$estimateid			=	$_POST["ESTIMATE_ID"];			//見積ID
		$workcontentid		=	$_POST["WORK_CONTENT_ID"];		//作業内容ID

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"			=> $dataid,
								"ESTIMATE_ID"		=> $estimateid,
								"WORK_CONTENT_ID"	=> $workcontentid
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$data_sel = $dbobj->getRecord();
	}

/*============================================================================
	削除用データ取得
	引数:
				$p_ar_trgtid
  ============================================================================*/
	// 削除用データ取得
	function getDataForDelete($p_ar_trgtid){
		$return_rec;				// レコード格納用

		require_once('../mdl/Workcontents_v.php');
		$dbobj = new Workcontents_v();

		// 条件配列セット
		$this->ar_condition = array("WORK_CONTENT_ID" => split(",",$p_ar_trgtid));

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$return_rec = $dbobj->getRecord();

		return $return_rec;
	}

/*============================================================================
	SQL（ESTIMATESからの論理削除）の作成
	引数:
				&$deletetarget
  ============================================================================*/
	// SQL（ESTIMATESからの論理削除）の作成
	function DataForWorkcontentsInvalid($deletetarget){

		//WORK_STAFF削除キー検索
		require_once('../mdl/Workcontents_v.php');
		$dbobj = new Workcontents_v();

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"			=> $_POST["DATA_ID"],
								"ESTIMATE_ID"		=> CONDITION_PLURAL."( ".$deletetarget." )"
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array(
								"DATA_ID",
								"WORK_CONTENT_ID"
								);

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$w_workcontents = $dbobj->getRecord();

		if(count($w_workcontents) > 0){
			foreach($w_workcontents as $target){
				$target_cnt++;
				if($target_cnt == 1){
					$del_target = $target["WORK_CONTENT_ID"];
				} else {
					$del_target .= ",".$target["WORK_CONTENT_ID"];
				}
			}
		}

		//WORK_STAFF論理削除
		require_once('../mdl/m_workstaff.php');
		$mesws = new m_workstaff();
		$mesws->DataForWorkstaffInvalid($del_target);

		// Invalid対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_CONTENTS";

		// 削除済表示を元に有効フラグの設定
		$validityflag	=	'N';										//有効フラグ->無効

		$sql = null;
		$sql .= "update ".$table_name." "."Set ";

		// 共通部分
		$sql .= " VALIDITY_FLAG = '".$validityflag."' ";
		$sql .= ",LAST_UPDATE_DATET = now() ";
		$sql .= ",LAST_UPDATE_USER_ID = '".$_POST["LOGINUSER_ID"]."' ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and ESTIMATE_ID in (".$deletetarget."); ";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSilentSQL($sql);

	}

/*============================================================================
	SQL（物理削除）の作成
  ============================================================================*/
	// SQL（物理削除）の作成
	function DataForDelete(){

		// 削除キー
		$deletetarget		=	$_POST["DELETE_TARGET"];				//削除対象

		//WORK_STAFF物理削除
		require_once('../mdl/m_workstaff.php');
		$mwost = new m_workstaff();
		$mwost->DataForWorkstaffDelete($deletetarget);

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_CONTENTS";

		$sql = null;
		$sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and WORK_CONTENT_ID in (".$deletetarget."); ";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSQL($sql);

	}

/*============================================================================
	SQL（ESTIMATESからの物理削除）の作成
	引数:
				&$deletetarget
  ============================================================================*/
	// SQL（ESTIMATESからの物理削除）の作成
	function DataForWorkcontentsDelete($deletetarget){

		//WORK_STAFF削除用キー検索
		require_once('../mdl/Workcontents_v.php');
		$dbobj = new Workcontents_v();

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"			=> $_POST["DATA_ID"],
								"ESTIMATE_ID"		=> CONDITION_PLURAL."( ".$deletetarget." )"
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array(
								"DATA_ID",
								"WORK_CONTENT_ID"
								);

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$w_workcontents = $dbobj->getRecord();

		if(count($w_workcontents) > 0){
			foreach($w_workcontents as $target){
				$target_cnt++;
				if($target_cnt == 1){
					$del_target = $target["WORK_CONTENT_ID"];
				} else {
					$del_target .= ",".$target["WORK_CONTENT_ID"];
				}
			}
		}

		//WORK_STAFF物理削除
		require_once('../mdl/m_workstaff.php');
		$mesws = new m_workstaff();
		$mesws->DataForWorkstaffDelete($del_target);

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_CONTENTS";

		$sql = null;
		$sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and ESTIMATE_ID in (".$deletetarget."); ";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSilentSQL($sql);

	}

/*============================================================================
	作業内容、及び作業人員コピー
  ============================================================================*/
	// 作業内容、及び作業人員コピー
	function DataForCopy(){

		session_start();
		//DATA_ID
		$dataid					=	$_SESSION['_authsession']['data']['DATA_ID'];

		for( $i = 1; $i<=NUMBER_OF_POST; $i++ ){
			switch ($_POST["update$i"][0]) {
				//検索するコード名
				case "WORK_ARRANGEMENT_USER_NAME":
					$trg_username		=	$_POST["update$i"][1];
				break;
				//設定するコード値
				case "WORK_ARRANGEMENT_ID":
					$unum				=	$i;
				break;
				//検索するコード名
				case "EXCESS_LIQUIDATION_FLAG_NAME":
					$trg_code_name_e	=	$_POST["update$i"][1];
				break;
				//設定するコード値
				case "EXCESS_LIQUIDATION_FLAG":
					$enum				=	$i;
					$trg_setname_e		=	$_POST["update$i"][0];
				break;
				//見積ID取得
				case "ESTIMATE_ID":
					$trg_estimateid		=	$_POST["update$i"][1];
				break;
				//作業内容ID
				case "WORK_CONTENT_ID":
					$trg_contentid		=	$_POST["update$i"][1];
				break;
				//作業日
				case "WORK_DATE":
					$trg_workdate		=	mb_convert_kana($_POST["update$i"][1],"a");
				break;
				//入店予定時刻
				case "DEFAULT_ENTERING_SCHEDULE_TIMET":
					$d_enter_schedule_timet = $_POST["update$i"][1];
				break;
				//退店予定時刻
				case "DEFAULT_LEAVE_SCHEDULE_TIMET":
					$d_leave_schedule_timet = $_POST["update$i"][1];
				break;
				//集合場所
				case "AGGREGATE_POINT":
					$aggregate_point = $_POST["update$i"][1];
				break;
				//------------------------------------------
				default:
				break;
			}
		}

		// USER_IDのセット
		require_once('../mdl/m_users.php');
		$muser = new m_users();
		$muser->getUserId($dataid,$trg_username,$return_user);

		$_POST["update$unum"][1] = $return_user;

		// EXCESS_LIQUIDATION_FLAGのセット
		require_once('../mdl/m_common_master.php');
		$commonset = new m_common_master();
		$change_trgt_e = $commonset->getCommonName($dataid,$trg_setname_e,$trg_code_name_e);

		$_POST["update$enum"][1] = $change_trgt_e;

		$p_table_name	= "WORK_CONTENTS";
		$sql_type		= "INSERT";
		$sql			= null;
		$sql_column		= null;
		$sql_data		= null;

		$member_table_name = $p_table_name;
		require_once('../mdl/m_column_info.php');
		$cchk = new ColumnInfo(strtoupper($p_table_name));

		$column_chk = $cchk->getColumnChk();

		$rcnt = 0;
		foreach ($column_chk as $column_chk1) {
			$column_chk[++$rcnt] = $column_chk1;
		}

		//二次元配列$column_chkのcolumn_nameの列を別の配列に移す
		$j=1;
		for( $j = 1; $j<=count($column_chk); $j++ ){
			$info_colum[$j]	= $column_chk[$j]['COLUMN_NAME'];
			$info_key[$j]	= $column_chk[$j]['COLUMN_KEY'];
			$info_table[$j]	= array( column_name				=>$column_chk[$j]['COLUMN_NAME']
									,data_type					=>$column_chk[$j]['DATA_TYPE']
									,character_maximum_length	=>$column_chk[$j]['CHARACTER_MAXIMUM_LENGTH']
									,is_nullable				=>$column_chk[$j]['IS_NULLABLE']
									,column_default				=>$column_chk[$j]['COLUMN_DEFAULT']);
		}

		// 入力項目を共通チェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CommonCheck.php');
		$dbcommoncheck = new CommonCheck();

		// 作業内容固有の入力項目をチェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/WorkContentsCheck.php');
		$dbworkcontentscheck = new WorkContentsCheck();

		// テーブルに登録するSQL文を作成するファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CreateSQL.php');
		$dbcreatesql = new CreateSQL();

		// time型からdatetime型へ変換するファンクションがあるオブジェクトの呼び出し
		require_once('../mdl/m_workstaff.php');
		$workstaff_obj = new m_workstaff();

		for( $i = 1; $i<=NUMBER_OF_POST; $i++ ){

			//モジュールに入れる$_POSTの値を変数に代入する。
			$entry_key = $_POST["update$i"][0];
			$entry_value = $_POST["update$i"][1];

			// 入力項目を共通チェックするファンクションの呼び出し
			$err_check_common = $dbcommoncheck->CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if($err_check_common["Code"] == 1){
				print $err_check_common["Message"];
			}

			// 作業内容固有の入力項目をチェックするファンクションの呼び出し
			$err_check_workcontents = $dbworkcontentscheck->WorkContentsDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if($err_check_workcontents["Code"] == 1){
				print $err_check_workcontents["Message"];
			}

			// 全角数字を半角数字に変換して返すファンクションの呼び出し
			$entry_value = $dbcommoncheck->ConvertNumber($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			// 入店・退店時間の場合は、作業日をくっ付けてworkdate型にする。
			if($entry_key == "DEFAULT_ENTERING_SCHEDULE_TIMET" || $entry_key == "DEFAULT_LEAVE_SCHEDULE_TIMET"){
				$entry_value = $workstaff_obj->convert_DATETIME($entry_value,$trg_workdate);
			}

			// テーブルに登録するSQL文を作成するファンクションの呼び出し
			$dbcreatesql->CreateSQLString($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if ($err_check_common["Code"] == 1 || $err_check_workcontents["Code"] == 1){
				$err_check_code = 1;
			}
		}

		if ($err_check_code != 1){
			//SQL文の作成
			//新規登録
			$sql[0] = "INSERT INTO ".SCHEMA_NAME.".".$p_table_name ." "." (".$dbcreatesql->d_insert1;
			$sql[1] = "VALUE (".$dbcreatesql->d_insert2;
			// 共通部分
			$sql_column	.= ",VALIDITY_FLAG ";
			$sql_data	.= ",default ";
			$sql_column	.= ",REGISTRATION_DATET ";
			$sql_data	.= ",now() ";
			$sql_column	.= ",REGISTRATION_USER_ID ";
			$sql_data	.= ",'".$_POST["LOGINUSER_ID"]."' ";
			$sql_column	.= ",LAST_UPDATE_DATET ";
			$sql_data	.= ",now() ";
			$sql_column	.= ",LAST_UPDATE_USER_ID) ";
			$sql_data	.= ",'".$_POST["LOGINUSER_ID"]."'); ";

			$sql[0]		.= $sql_column;
			$sql[1]		.= $sql_data;
			$Executionsql		= $sql[0].$sql[1];
		}


		// 作業日を元に月末日を取得
		$dt_year	=	date("Y", strtotime($trg_workdate));
		$dt_month	=	date("m", strtotime($trg_workdate));

		require_once('../lib/CommonDate.php');
		$commond = new CommonDate();

		//月初日取得
		$first_of_month	= $commond->getMonthfirstDay($dt_year,$dt_month);
		$from_year	=	date("Y", strtotime($first_of_month));
		$from_month	=	date("m", strtotime($first_of_month));
		$from_day	=	date("d", strtotime($first_of_month));

		//月末日取得
		$end_of_month	= $commond->getMonthEndDay($dt_year,$dt_month);
		$to_year	=	date("Y", strtotime($end_of_month));
		$to_month	=	date("m", strtotime($end_of_month));
		$to_day		=	date("d", strtotime($end_of_month));

		//営業日取得
		$businessday	= $commond->compareDate(GETMODE_BUSINESS_DAY,$to_year,$to_month,$to_day,$from_year,$from_month,$from_day);

		//作業人員が登録されているか検索
		require_once('../mdl/m_workstaff.php');
		$mwost = new m_workstaff();
		$mwost->DataForSearch($dataid,$trg_estimateid,$trg_workdate,$s_workstaff);
		$msg	=	null;

		if(count($s_workstaff) == 0){
			$msg	=	"作業人員が登録されていません。\n作業人員の登録後、コピー処理を実行してください。";
		} else {
			// 登録済みデータ検索用
			require_once('../mdl/Workcontents_v.php');

			// 作業内容コピー（取得した営業日分のコピーを行う）
			foreach($businessday as $key=>$workday){
				// 登録済みデータ検索
				$dbobj_wc = new Workcontents_v();


				// 条件配列セット
				$this->ar_condition = array(
										"DATA_ID"							=> $dataid,
										"ESTIMATE_ID"						=> $trg_estimateid,
										"WORK_DATE"							=> $workday,
										"DEFAULT_ENTERING_SCHEDULE_TIMET"	=> $d_enter_schedule_timet,
										"AGGREGATE_POINT"					=> $aggregate_point
										);

				// 条件セット
				$l_ar_cond = $dbobj_wc->setCondition($this->ar_condition);

				// レコード取得
				$get_workcontents = $dbobj_wc->getRecord();

				if(count($get_workcontents) == 0){
					//登録対象
					$sql	= null;

					//日を跨ぐ作業用に作業日に1日加算した日付を変換する
					$arr_work_day = preg_split("/\-/",$trg_workdate);
					$workday_time_stamp = mktime(0,0,0,$arr_work_day[1],$arr_work_day[2],$arr_work_day[0]);
					$to_workday_time_stamp = $workday_time_stamp + 60*60*24;
					$to_work_day = date('Y-m-d',$to_workday_time_stamp);

					$arr_trg_work_day = preg_split("/\-/",$workday);
					$trg_workday_time_stamp = mktime(0,0,0,$arr_trg_work_day[1],$arr_trg_work_day[2],$arr_trg_work_day[0]);
					$to_trg_workday_time_stamp = $trg_workday_time_stamp + 60*60*24;
					$to_trg_work_date = date('Y-m-d',$to_trg_workday_time_stamp);

					$sql = str_replace($to_work_day, $to_trg_work_date, $Executionsql);

					$re_sql	= str_replace($trg_workdate, $workday, $sql);

					//print $re_sql."\n";
					//出力メッセージ
					$msg	.= "作業日：".$workday."\n";

					require_once('../mdl/CommonExecution.php');
					$exobj = new CommonExecution();
					$exobj->CommonSilentSQL($re_sql);

					//登録した作業内容IDを取得
					$this->ar_condition = array(
											"DATA_ID"							=> $dataid,
											"ESTIMATE_ID"						=> $trg_estimateid,
											"WORK_DATE"							=> $workday,
											);
					// 条件セット
					$l_ar_cond = $dbobj_wc->setCondition($this->ar_condition);

					// レコード取得
					$re_workcontents = $dbobj_wc->getRecord();

					// 登録した作業内容に所属する作業人員を取得
					$mwost->DataForSearch($dataid,$trg_estimateid,$trg_workdate,$a_workstaff,$trg_contentid);

					// 作業人員のコピー処理
					foreach($a_workstaff as $key=>$value){

						// 入店予定時間・退店予定時間は日付の部分を変更する
						$value["ENTERING_SCHEDULE_TIMET"] = str_replace($trg_workdate, $workday, $value["ENTERING_SCHEDULE_TIMET"]);

						$value["LEAVE_SCHEDULE_TIMET"] = str_replace($to_work_day, $to_trg_work_date, $value["LEAVE_SCHEDULE_TIMET"]);
						$value["LEAVE_SCHEDULE_TIMET"] = str_replace($trg_workdate, $workday, $value["LEAVE_SCHEDULE_TIMET"]);

						$mwost->DataForCopy($dataid,$re_workcontents[1]["WORK_CONTENT_ID"],$value["WORK_BASE_ID"],$value["WORK_USER_ID"],$value["ENTERING_SCHEDULE_TIMET"],$value["LEAVE_SCHEDULE_TIMET"],$value["BASIC_TIME"],$value["BREAK_TIME"],$msg);
					}
				} else {
					// 作業内容登録済みの場合、作業人員のコピー処理を行う
					// 登録済みの作業内容IDを取得

					$this->ar_condition = array(
											"DATA_ID"							=> $dataid,
											"ESTIMATE_ID"						=> $trg_estimateid,
											"WORK_DATE"							=> $workday,
											"DEFAULT_ENTERING_SCHEDULE_TIMET"	=> $d_enter_schedule_timet,
											"AGGREGATE_POINT"					=> $aggregate_point
											);
					// 条件セット
					$l_ar_cond = $dbobj_wc->setCondition($this->ar_condition);

					// レコード取得
					$re_workcontents = $dbobj_wc->getRecord();

					// 登録した作業内容に所属する作業人員を取得
					$mwost->DataForSearch($dataid,$trg_estimateid,$trg_workdate,$a_workstaff,$re_workcontents[1]["WORK_CONTENT_ID"]);

					foreach($a_workstaff as $key=>$value){
						$mwost->DataForCopy($dataid,$re_workcontents[1]["WORK_CONTENT_ID"],$value["WORK_BASE_ID"],$value["WORK_USER_ID"],$value["ENTERING_SCHEDULE_TIMET"],$value["LEAVE_SCHEDULE_TIMET"],$value["BASIC_TIME"],$value["BREAK_TIME"],$msg);
					}

				}

			}
			$msg	.= "コピー処理を実行しました。";
		}
		print $msg;

		return $sql;
	}
}
?>