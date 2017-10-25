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
require_once('../lib/MailSettings.php');
require_once('../mdl/ModelCommon.php');
class m_workstaff extends ModelCommon{
/*******************************************************************************
	クラス名：m_workstaff.php
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
	private $set_view_name		= 'WORKSTAFF_V';			// ビュー名
	private $set_table_name		= 'WORK_STAFF';				// テーブル名
	private $primary_key_col	= 'WORK_STAFF_ID';			// 主キーの項目

	public	$htmlspchar_flag = 'Y';							// htmlspecialchars適用フラグ
	public	$shortname_size  = 25;							// 短縮名の文字サイズ

	private $workname_short_size;							// 作業名省略時の文字数
	private $username_short_size;							// 作業者省略時の文字数

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

		// 固定値の設定
		$this->workname_short_size = WORKNAME_SHORT_SIZE;		// 作業名省略時の文字数
		$this->username_short_size = USERNAME_SHORT_SIZE;		// 作業者省略時の文字数

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
							if(	$item_key=="WORK_ARRANGEMENT_NAME"
								or $item_key=="COMPANY_NAME"
								or $item_key=="WORK_NAME"
								or $item_key=="WORK_BASE_NAME"
								or $item_key=="WORK_COMPANY_NAME"
								or $item_key=="WORK_GROUP_NAME"
								or $item_key=="WORK_USER_NAME"
							){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}

							// 出発、入店、退店の時刻はhh:mm形式の値を作成する
							if(	$item_key=="DISPATCH_SCHEDULE_TIMET"
								or $item_key=="DISPATCH_STAFF_TIMET"
								or $item_key=="ENTERING_SCHEDULE_TIMET"
								or $item_key=="ENTERING_STAFF_TIMET"
								or $item_key=="ENTERING_MANAGE_TIMET"
								or $item_key=="LEAVE_SCHEDULE_TIMET"
								or $item_key=="LEAVE_STAFF_TIMET"
								or $item_key=="LEAVE_MANAGE_TIMET"
							){
								if($item_value != "" and !is_null($item_value)){
									$lr_view_rec[$l_loop_cnt][$item_key."_HHMM"] = $this->setTime($lr_view_rec[$l_loop_cnt][$item_key], $lr_view_rec[$l_loop_cnt]["WORK_DATE"]);
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
	特定2カラムデータ取得
	特定2カラムのデータ一覧を取得する
	引数:			$p_item1					取得対象項目1
					$p_item2					取得対象項目2
  ============================================================================*/
	function get2ColumnValueAll($p_item1, $p_item2){
		if($this->debug_mode==1){print("Step-getColumnValueAll開始");print "<br>";}
		$lr_return_value = "";

		// 項目指定がない場合は終了
		if(is_null($p_item1) || $p_item1 == ''){
			return false;
		}
		if(is_null($p_item2) || $p_item2 == ''){
			return false;
		}

		// レコードが0件の場合は終了
		if(count($this->r_view_rec) == 0){
			return false;
		}

		// レコードを検索し、指定の項目のみ取得
		foreach($this->r_view_rec as $l_rec_num => $l_rec_data){
			$lr_return_value[$l_rec_data[$p_item1]] = $l_rec_data[$p_item2];
		}

		// 並べ替え
		asort ($lr_return_value);

		if($this->debug_mode==1){print("Step-getColumnValueAll終了");print "<br>";}
		return $lr_return_value;
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
			$p_date				基準日付
  ============================================================================*/
	function resetTime($p_time, $p_date){

		require_once('../lib/CommonDate.php');
		$lc_cdate = new CommonDate();
		// 作業日からY-m-d H-i-s形式の日付に変更する
		$l_newdate = $lc_cdate->getYmdHiTime($p_time, $p_date);

		return $l_newdate;
	}
/*============================================================================
	時刻設定処理
	処理概要：時刻が Y-m-d H:i:s 形式の場合、入力された日付から H:i に変換する
			$p_ymd				YMD形式の日付
			$p_date				基準日付
  ============================================================================*/
	function setTime($p_ymd, $p_date){

		require_once('../lib/CommonDate.php');
		$lc_cdate = new CommonDate();
		// 作業日からY-m-d H-i-s形式の日付に変更する
		$l_newdate = $lc_cdate->getTimeByYMD($p_ymd, $p_date);

		return $l_newdate;
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

		/*--------------------
		   時間再設定
		  --------------------*/
		// 入店予定時間のセット
		if ($pr_data["ENTERING_SCHEDULE_TIMET"] != ""){
			$pr_data["ENTERING_SCHEDULE_TIMET"] = $this->resetTime($pr_data["ENTERING_SCHEDULE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 入店時間（管理部）のセット
		if ($pr_data["ENTERING_MANAGE_TIMET"] != ""){
			$pr_data["ENTERING_MANAGE_TIMET"] = $this->resetTime($pr_data["ENTERING_MANAGE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 退店予定時間のセット
		if ($pr_data["LEAVE_SCHEDULE_TIMET"] != ""){
			$pr_data["LEAVE_SCHEDULE_TIMET"] = $this->resetTime($pr_data["LEAVE_SCHEDULE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 退店時間（管理部）のセット
		if ($pr_data["LEAVE_MANAGE_TIMET"] != ""){
			$pr_data["LEAVE_MANAGE_TIMET"] = $this->resetTime($pr_data["LEAVE_MANAGE_TIMET"], $pr_data["nm_work_date"]);
		}

		//****************************************************************
		// Insert文作成
		// ※DATA型とNumber型は値が無い場合0がセットされてしまう為、INSERT文から外す
		// ※デフォルト値がある項目もデフォルト値が入らなくなる為、INSERT文から外す
		$l_sql  = "insert into `WORK_STAFF` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'WORK_STAFF_ID',";
		$l_sql .= "WORK_CONTENT_ID,";
		$l_sql .= "WORK_BASE_ID,";
		$l_sql .= "WORK_USER_ID,";
		if ($pr_data["APPROVAL_DIVISION"] != ""){$l_sql .= "APPROVAL_DIVISION,";}
		if ($pr_data["TRANSMISSION_FLAG"] != ""){$l_sql .= "TRANSMISSION_FLAG,";}
		if ($pr_data["CANCEL_DIVISION"] != ""){$l_sql .= "CANCEL_DIVISION,";}
		if ($pr_data["WORK_UNIT_PRICE"] != ""){$l_sql .= "WORK_UNIT_PRICE,";}
		if ($pr_data["EXCESS_AMOUNT"] != ""){$l_sql .= "EXCESS_AMOUNT,";}
		if ($pr_data["BASIC_TIME"] != ""){$l_sql .= "BASIC_TIME,";}
		if ($pr_data["BREAK_TIME"] != ""){$l_sql .= "BREAK_TIME,";}
		$l_sql .= "DISPATCH_SCHEDULE_TIMET,";
		$l_sql .= "DISPATCH_STAFF_TIMET,";
		$l_sql .= "ENTERING_SCHEDULE_TIMET,";
		$l_sql .= "ENTERING_STAFF_TIMET,";
		$l_sql .= "ENTERING_MANAGE_TIMET,";
		$l_sql .= "LEAVE_SCHEDULE_TIMET,";
		$l_sql .= "LEAVE_STAFF_TIMET,";
		$l_sql .= "LEAVE_MANAGE_TIMET,";
		if ($pr_data["TRANSPORT_AMOUNT"] != ""){$l_sql .= "TRANSPORT_AMOUNT,";}
		if ($pr_data["OTHER_AMOUNT"] != ""){$l_sql .= "OTHER_AMOUNT,";}
		$l_sql .= "REMARKS,";
		if ($pr_data["OVERTIME_WORK_AMOUNT"] != ""){$l_sql .= "OVERTIME_WORK_AMOUNT,";}
		if ($pr_data["WORK_EXPENSE_AMOUNT_TOTAL"] != ""){$l_sql .= "WORK_EXPENSE_AMOUNT_TOTAL,";}
		if ($pr_data["PAYMENT_AMOUNT_TOTAL"] != ""){$l_sql .= "PAYMENT_AMOUNT_TOTAL,";}
		if ($pr_data["REAL_WORKING_HOURS"] != ""){$l_sql .= "REAL_WORKING_HOURS,";}
		if ($pr_data["REAL_OVERTIME_HOURS"] != ""){$l_sql .= "REAL_OVERTIME_HOURS,";}
		if ($pr_data["SUPPLIED_AMOUNT_TOTAL"] != ""){$l_sql .= "SUPPLIED_AMOUNT_TOTAL,";}
		if ($pr_data["STAFF_STATUS"] != ""){$l_sql .= "STAFF_STATUS,";}
		if ($pr_data["DISPATCH_DELAY_NOTIFIED"] != ""){$l_sql .= "DISPATCH_DELAY_NOTIFIED,";}
		if ($pr_data["ENTERING_DELAY_NOTIFIED"] != ""){$l_sql .= "ENTERING_DELAY_NOTIFIED,";}
		if ($pr_data["LEAVE_DELAY_NOTIFIED"] != ""){$l_sql .= "LEAVE_DELAY_NOTIFIED,";}
		if ($pr_data["WORK_UNIT_PRICE_DISPLAY_FLAG"] != ""){$l_sql .= "WORK_UNIT_PRICE_DISPLAY_FLAG,";}
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
		$l_sql .= $pr_data["DATA_ID"].",";																			// DATA_ID
		//$l_sql .= $pr_data["WORK_STAFF_ID"].",";																	// WORK_STAFF_ID
		$l_sql .= $pr_data["WORK_CONTENT_ID"].",";																	// WORK_CONTENT_ID
		$l_sql .= $pr_data["WORK_BASE_ID"].",";																		// WORK_BASE_ID
		$l_sql .= $pr_data["WORK_USER_ID"].",";																		// WORK_USER_ID
		if ($pr_data["APPROVAL_DIVISION"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["APPROVAL_DIVISION"])."',";}	// APPROVAL_DIVISION
		if ($pr_data["TRANSMISSION_FLAG"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["TRANSMISSION_FLAG"])."',";}	// TRANSMISSION_FLAG
		if ($pr_data["CANCEL_DIVISION"] != ""){$l_sql 	.= "'".$this->getMysqlEscapedValue($pr_data["CANCEL_DIVISION"])."',";}		// CANCEL_DIVISION
		if ($pr_data["WORK_UNIT_PRICE"] != ""){$l_sql 	.= $pr_data["WORK_UNIT_PRICE"].",";}						// WORK_UNIT_PRICE
		if ($pr_data["EXCESS_AMOUNT"] != ""){$l_sql 	.= $pr_data["EXCESS_AMOUNT"].",";}							// EXCESS_AMOUNT
		if ($pr_data["BASIC_TIME"] != ""){$l_sql 		.= $pr_data["BASIC_TIME"].",";}								// BASIC_TIME
		if ($pr_data["BREAK_TIME"] != ""){$l_sql 		.= $pr_data["BREAK_TIME"].",";}								// BREAK_TIME
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["DISPATCH_SCHEDULE_TIMET"])."',";						// DISPATCH_SCHEDULE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["DISPATCH_STAFF_TIMET"])."',";							// DISPATCH_STAFF_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ENTERING_SCHEDULE_TIMET"])."',";						// ENTERING_SCHEDULE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ENTERING_STAFF_TIMET"])."',";							// ENTERING_STAFF_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ENTERING_MANAGE_TIMET"])."',";							// ENTERING_MANAGE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["LEAVE_SCHEDULE_TIMET"])."',";							// LEAVE_SCHEDULE_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["LEAVE_STAFF_TIMET"])."',";								// LEAVE_STAFF_TIMET
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["LEAVE_MANAGE_TIMET"])."',";								// LEAVE_MANAGE_TIMET
		if ($pr_data["TRANSPORT_AMOUNT"] != ""){$l_sql 	.= $pr_data["TRANSPORT_AMOUNT"].",";}						// TRANSPORT_AMOUNT
		if ($pr_data["OTHER_AMOUNT"] != ""){$l_sql 		.= $pr_data["OTHER_AMOUNT"].",";}							// OTHER_AMOUNT
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["REMARKS"])."',";										// REMARKS
		if ($pr_data["OVERTIME_WORK_AMOUNT"] != ""){$l_sql 			.= $pr_data["OVERTIME_WORK_AMOUNT"].",";}		// OVERTIME_WORK_AMOUNT
		if ($pr_data["WORK_EXPENSE_AMOUNT_TOTAL"] != ""){$l_sql 	.= $pr_data["WORK_EXPENSE_AMOUNT_TOTAL"].",";}	// WORK_EXPENSE_AMOUNT_TOTAL
		if ($pr_data["PAYMENT_AMOUNT_TOTAL"] != ""){$l_sql 			.= $pr_data["PAYMENT_AMOUNT_TOTAL"].",";}		// PAYMENT_AMOUNT_TOTAL
		if ($pr_data["REAL_WORKING_HOURS"] != ""){$l_sql 			.= $pr_data["REAL_WORKING_HOURS"].",";}			// REAL_WORKING_HOURS
		if ($pr_data["REAL_OVERTIME_HOURS"] != ""){$l_sql 			.= $pr_data["REAL_OVERTIME_HOURS"].",";}		// REAL_OVERTIME_HOURS
		if ($pr_data["SUPPLIED_AMOUNT_TOTAL"] != ""){$l_sql 		.= $pr_data["SUPPLIED_AMOUNT_TOTAL"].",";}		// SUPPLIED_AMOUNT_TOTAL
		if ($pr_data["STAFF_STATUS"] != ""){$l_sql 					.= "'".$this->getMysqlEscapedValue($pr_data["STAFF_STATUS"])."',";}				// STAFF_STATUS
		if ($pr_data["DISPATCH_DELAY_NOTIFIED"] != ""){$l_sql 		.= "'".$this->getMysqlEscapedValue($pr_data["DISPATCH_DELAY_NOTIFIED"])."',";}		// DISPATCH_DELAY_NOTIFIED
		if ($pr_data["ENTERING_DELAY_NOTIFIED"] != ""){$l_sql 		.= "'".$this->getMysqlEscapedValue($pr_data["ENTERING_DELAY_NOTIFIED"])."',";}		// ENTERING_DELAY_NOTIFIED
		if ($pr_data["LEAVE_DELAY_NOTIFIED"] != ""){$l_sql 			.= "'".$this->getMysqlEscapedValue($pr_data["LEAVE_DELAY_NOTIFIED"])."',";}		// LEAVE_DELAY_NOTIFIED
		if ($pr_data["WORK_UNIT_PRICE_DISPLAY_FLAG"] != ""){$l_sql	.= "'".$this->getMysqlEscapedValue($pr_data["WORK_UNIT_PRICE_DISPLAY_FLAG"])."',";}// WORK_UNIT_PRICE_DISPLAY_FLAG
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
		/*--------------------
		   時間再設定
		  --------------------*/
		// 入店予定時間のセット
		if ($pr_data["ENTERING_SCHEDULE_TIMET"] != ""){
			$pr_data["ENTERING_SCHEDULE_TIMET"] = $this->resetTime($pr_data["ENTERING_SCHEDULE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 入店時間（管理部）のセット
		if ($pr_data["ENTERING_MANAGE_TIMET"] != ""){
			$pr_data["ENTERING_MANAGE_TIMET"] = $this->resetTime($pr_data["ENTERING_MANAGE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 退店予定時間のセット
		if ($pr_data["LEAVE_SCHEDULE_TIMET"] != ""){
			$pr_data["LEAVE_SCHEDULE_TIMET"] = $this->resetTime($pr_data["LEAVE_SCHEDULE_TIMET"], $pr_data["nm_work_date"]);
		}
		// 退店時間（管理部）のセット
		if ($pr_data["LEAVE_MANAGE_TIMET"] != ""){
			$pr_data["LEAVE_MANAGE_TIMET"] = $this->resetTime($pr_data["LEAVE_MANAGE_TIMET"], $pr_data["nm_work_date"]);
		}

		// SQL組み立て
		$l_sql  = "update `WORK_STAFF` ";
		$l_sql .= "set ";
		$l_sql .= $this->getUpdataSetPhrase("WORK_BASE_ID", 				$pr_data["WORK_BASE_ID"], 					'N').",";// WORK_BASE_ID
		$l_sql .= $this->getUpdataSetPhrase("WORK_USER_ID", 				$pr_data["WORK_USER_ID"], 					'N').",";// WORK_USER_ID
		$l_sql .= $this->getUpdataSetPhrase("APPROVAL_DIVISION", 			$pr_data["APPROVAL_DIVISION"], 				'C').",";// APPROVAL_DIVISION
		$l_sql .= $this->getUpdataSetPhrase("CANCEL_DIVISION", 				$pr_data["CANCEL_DIVISION"], 				'C').",";// CANCEL_DIVISION
		$l_sql .= $this->getUpdataSetPhrase("WORK_UNIT_PRICE", 				$pr_data["WORK_UNIT_PRICE"], 				'N').",";// WORK_UNIT_PRICE
		$l_sql .= $this->getUpdataSetPhrase("EXCESS_AMOUNT", 				$pr_data["EXCESS_AMOUNT"], 					'N').",";// EXCESS_AMOUNT
		$l_sql .= $this->getUpdataSetPhrase("WORK_UNIT_PRICE_DISPLAY_FLAG", $pr_data["WORK_UNIT_PRICE_DISPLAY_FLAG"], 	'C').",";// WORK_UNIT_PRICE_DISPLAY_FLAG
		$l_sql .= $this->getUpdataSetPhrase("ENTERING_SCHEDULE_TIMET", 		$pr_data["ENTERING_SCHEDULE_TIMET"], 		'C').",";// ENTERING_SCHEDULE_TIMET
		$l_sql .= $this->getUpdataSetPhrase("ENTERING_MANAGE_TIMET", 		$pr_data["ENTERING_MANAGE_TIMET"], 			'C').",";// ENTERING_MANAGE_TIMET
		$l_sql .= $this->getUpdataSetPhrase("LEAVE_SCHEDULE_TIMET", 		$pr_data["LEAVE_SCHEDULE_TIMET"], 			'C').",";// LEAVE_SCHEDULE_TIMET
		$l_sql .= $this->getUpdataSetPhrase("LEAVE_MANAGE_TIMET", 			$pr_data["LEAVE_MANAGE_TIMET"], 			'C').",";// LEAVE_MANAGE_TIMET
		$l_sql .= $this->getUpdataSetPhrase("TRANSPORT_AMOUNT", 			$pr_data["TRANSPORT_AMOUNT"], 				'N').",";// TRANSPORT_AMOUNT
		$l_sql .= $this->getUpdataSetPhrase("OTHER_AMOUNT", 				$pr_data["OTHER_AMOUNT"], 					'N').",";// OTHER_AMOUNT
		$l_sql .= $this->getUpdataSetPhrase("REMARKS", 						$pr_data["REMARKS"], 						'C').",";// REMARKS
		$l_sql .= $this->getUpdataSetPhrase("BASIC_TIME", 					$pr_data["BASIC_TIME"], 					'C').",";// BASIC_TIME
		$l_sql .= $this->getUpdataSetPhrase("BREAK_TIME", 					$pr_data["BREAK_TIME"], 					'C').",";// BREAK_TIME

		$l_sql .= "VALIDITY_FLAG = '".$pr_data["VALIDITY_FLAG"]."',";				// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";										// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = "			.$p_user_id." ";				// LAST_UPDATE_USER_ID
		$l_sql .= "where WORK_STAFF_ID = "		.$p_key_value;

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
	送信フラグ更新処理
	処理概要：
			指定されたWORK_STAFF_IDのレコードの送信フラグをUpdateする
	引数:
			$p_user_id					更新者ユーザーID
			$p_work_staff_id			人員ID
			$p_transmission_flag		送信フラグ
  ============================================================================*/
	function updateTransmissionFlag($p_user_id, $p_work_staff_id, $p_transmission_flag = 'Y'){
		// SQL組立
		$l_sql  = "update `WORK_STAFF` ";
		$l_sql .= "set TRANSMISSION_FLAG = '".$p_transmission_flag."', ";
		$l_sql .= "LAST_UPDATE_DATET = now(),";							// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = ".$p_user_id." ";				// LAST_UPDATE_USER_ID
		$l_sql .= "where WORK_STAFF_ID = ".$p_work_staff_id;

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
		foreach($this->r_table_rec as $l_recnum => $lr_record){
			if(!$this->execInsert($lr_record, $p_user_id)){
				// エラーとなるレコードが発生した時点で終了
				return false;
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
		if(is_null($this->r_table_rec[0][$this->primary_key_col]) || $this->r_table_rec[0][$this->primary_key_col] == ''){
			// 主キーの設定が無い場合
			return false;
		}else{
			if(!$this->execUpdate($this->r_table_rec[0], $this->r_table_rec[0][$this->primary_key_col], $p_user_id)){
				// エラーとなるレコードが発生した時点で終了
				return false;
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

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".".$this->set_table_name;

		$l_sql = null;
		$l_sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$l_sql .= "where ".$this->primary_key_col." in (".$l_del_target_id."); ";

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
				// 入店時刻、退店時刻
				// nn:nn形式で記述されてい無い場合はエラーとする
				$l_target_col = "ENTERING_SCHEDULE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「入店予定時間」は、hh:mm形式で入力して下さい。\n";
				}
				$l_target_col = "LEAVE_SCHEDULE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「退店予定時間」は、hh:mm形式で入力して下さい。\n";
				}
				$l_target_col = "ENTERING_MANAGE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「入店時間(管理部)」は、hh:mm形式で入力して下さい。\n";
				}
				$l_target_col = "LEAVE_MANAGE_TIMET";
				if ($lr_data_rec[$l_target_col] != "" and !$lc_ccnm->checkTimeString($lr_data_rec[$l_target_col])){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "「退店時間(管理部)」は、hh:mm形式で入力して下さい。\n";
				}


				// 拠点
				require_once('../mdl/m_workplace_master.php');
				$l_target_col			= "WORK_BASE_NAME";
				$l_target_col_id		= "WORK_BASE_ID";
				$l_target_parent_col	= "COMPANY_NAME";
				$l_target_col_mst_id	= "BASE_ID";
				$l_target_col_name		= "拠点名";
				// 会社名とセットで検索する為、会社名が未入力の場合はエラーとする
				if ($lr_data_rec[$l_target_parent_col] == ""){
					$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
					$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= "会社（拠点）は入力必須項目です。\n";
				}else{
					if ($lr_data_rec[$l_target_col] != ""){
					// 拠点が設定されている場合、マスターを検索し、存在を確認する
						$lc_master_mdl_class	= "";
						$lr_master_mdl			= "";

						// 拠点マスタから拠点名をキーに値を取得
						$lr_whare = array();
						array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
						array_push($lr_whare, "COMPANY_NAME = '".$this->getMysqlEscapedValue($lr_data_rec[$l_target_parent_col])."'");
						array_push($lr_whare, "BASE_NAME = '".$this->getMysqlEscapedValue($lr_data_rec[$l_target_col])."'");
						array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
						//var_dump($lr_whare);
						$lc_master_mdl_class	= new m_workplace_master('Y', $lr_whare);
						$lr_master_mdl			= $lc_master_mdl_class->getViewRecord();

						// 拠点が見つからない場合は、メッセージを追加する
						if (count($lr_master_mdl) == 0){
							$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
							$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= $l_target_col_name."「".$lr_data_rec[$l_target_col]."」は存在しません。修正して下さい。\n";
							//var_dump($lr_check_res);
						}else{
						// 拠点コードをレコードにセットする
							$this->r_table_rec[$l_key][$l_target_col_id] = $lr_master_mdl[1][$l_target_col_mst_id];
						}
					}else{
					// 必須入力項目なので値が無ければエラー
						$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= $l_target_col_name."は入力必須項目です。\n";
					}
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
	// 一旦セットしたレコード
	function getSetRecord(){
		return $this->r_table_rec;
	}
	// カラムのコメント(日本語名)一覧取得
	function getColumnComment(){
		return $this->r_col_name;
	}
	// 合計金額取得
	function getTotalAmount($p_mode = 'all'){
		$l_return_value = 0;
		$l_overtime_work_amount = 0;	// 残業($p_mode == 'OVERTIME_WORK_AMOUNT')
		$l_payment_amount_total = 0;	// 出金合計($p_mode == 'PAYMENT_AMOUNT_TOTAL')
		$l_other_amount = 0;			// その他費用($p_mode == 'OTHER_AMOUNT')

		if (count($this->r_view_rec) > 0){
			// レコードセットから各合計値を算出
			foreach ($this->r_view_rec as $l_rec_cnt => $lr_rec){
				// 残業
				$l_overtime_work_amount += $lr_rec['OVERTIME_WORK_AMOUNT'];
				// 出金合計
				$l_payment_amount_total += $lr_rec['PAYMENT_AMOUNT_TOTAL'];
				// その他費用
				$l_other_amount += $lr_rec['OTHER_AMOUNT'];
			}

			// 返り値のセット
			if ($p_mode == 'OVERTIME_WORK_AMOUNT'){
				$l_return_value = $l_overtime_work_amount;
			}else if ($p_mode == 'PAYMENT_AMOUNT_TOTAL'){
				$l_return_value = $l_payment_amount_total;
			}else if ($p_mode == 'OTHER_AMOUNT'){
				$l_return_value = $l_other_amount;
			}else if ($p_mode == 'all'){
				$l_return_value = $l_overtime_work_amount + $l_payment_amount_total + $l_other_amount;
			}
		}

		return $l_return_value;
	}

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

/*----------------------------------------------------------------------------
	 一覧表示
  ----------------------------------------------------------------------------*/
	function workstaff_list(&$w_workstaff){
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v();

		// 検索キーの設定
		$dataid							=	$_POST["hd_dataid"];						//データID
		$workcontentid					=	$_POST["WORK_CONTENT_ID"];					//作業内容ID
		$workdate						=	$_POST["WORK_DATE"];						//作業日
		$work_base_name					=	$_POST["WORK_BASE_NAME"];					//拠点名
		$work_user_name					=	$_POST["WORK_USER_NAME"];					//作業者名
		$classification_division_name	=	$_POST["WORK_CLASSIFICATION_DIVISION_NAME"];//分類区分
		$displaydelete					=	$_POST["hd_delete_check"];					//削除済表示

		// 削除済表示を元に有効フラグの設定
		if($displaydelete == '' ){
			$validityflag = 'Y';								//有効フラグ(Yのみ)
		} else {
			$validityflag = '';									//有効フラグ(全て)
		}

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"							=> $dataid,
								"WORK_CONTENT_ID"					=> $workcontentid,
								"WORK_DATE"							=> $workdate,
								"WORK_BASE_NAME"					=> "%".$work_base_name."%",
								"WORK_USER_NAME"					=> "%".$work_user_name."%",
								"WORK_CLASSIFICATION_DIVISION_NAME"	=> "%".$classification_division_name."%",
								"VALIDITY_FLAG"						=> "%".$validityflag."%"
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array(
								"DATA_ID",
		//						"WORK_BASE_NAME",
								"WORK_COMPANY_NAME",
								"WORK_USER_KANA",
								"WORK_USER_NAME"
								);

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$w_workstaff = $dbobj->getRecord();
	}

/*----------------------------------------------------------------------------
	 更新表示用検索
  ----------------------------------------------------------------------------*/
	function workstaff_ups(&$data_sel){
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v();

		// 検索キーの設定
		$dataid				=	$_POST["hd_dataid"];					//データID
		$workcontentid		=	$_POST["WORK_CONTENT_ID"];				//作業内容ID
		$workstaffid		=	$_POST["WORK_STAFF_ID"];				//作業人員ID

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"			=> $dataid,
								"WORK_CONTENT_ID"	=> $workcontentid,
								"WORK_STAFF_ID"		=> $workstaffid
								);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$data_sel = $dbobj->getRecord();
	}

/*----------------------------------------------------------------------------
	 削除用データ取得
  ----------------------------------------------------------------------------*/
	function getDataForDelete($p_ar_trgtid){
		$return_rec;				// レコード格納用

		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v();

		// 条件配列セット
		$this->ar_condition = array("WORK_STAFF_ID" => split(",",$p_ar_trgtid));

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$return_rec = $dbobj->getRecord();

		return $return_rec;
	}

/*----------------------------------------------------------------------------
	 一括メール送信用データ取得
  ----------------------------------------------------------------------------*/
	function getDataForBatchsend($p_ar_trgtid){
		$return_rec;				// レコード格納用

		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v();

		// 条件配列セット
		$this->ar_condition = array("WORK_STAFF_ID" => split(",",$p_ar_trgtid));

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$return_rec = $dbobj->getRecord();

		return $return_rec;
	}

/*----------------------------------------------------------------------------
	 SQL（WORK_CONTENTSからの論理削除）の作成
  ----------------------------------------------------------------------------*/
	function DataForWorkstaffInvalid($del_target){

		// Invalid対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_STAFF";

		// 削除済表示を元に有効フラグの設定
		$validityflag	=	'N';										//有効フラグ->無効

		$sql = null;
		$sql .= "update ".$table_name." "."set ";

		// 共通部分
		$sql .= " VALIDITY_FLAG = '".$validityflag."' ";
		$sql .= ",LAST_UPDATE_DATET = now() ";
		$sql .= ",LAST_UPDATE_USER_ID = '".$_POST["LOGINUSER_ID"]."' ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and WORK_CONTENT_ID in (".$del_target."); ";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSilentSQL($sql);

	}

/*----------------------------------------------------------------------------
	 SQL（物理削除）の作成
  ----------------------------------------------------------------------------*/
	function DataForDelete(){

		// 削除キー
		$deletetarget		=	$_POST["DELETE_TARGET"];				//削除対象

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_STAFF";

		$sql = null;
		$sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and WORK_CONTENT_ID = '".$_POST["WORK_CONTENT_ID"]."' ";
		$sql .= "  and WORK_STAFF_ID in (".$deletetarget."); ";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSQL($sql);

	}

/*----------------------------------------------------------------------------
	 SQL（WORK_CONTENTSからの物理削除）の作成
  ----------------------------------------------------------------------------*/
	function DataForWorkstaffDelete($del_target){

		// Delete対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_STAFF";

		$sql = null;
		$sql .= "delete from ".$table_name." ";

		// 更新キーの設定
		$sql .= "where DATA_ID = '".$_POST["DATA_ID"]."' ";
		$sql .= "  and WORK_CONTENT_ID in (".$del_target."); ";

		if($del_target != null){
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}
	}

/*----------------------------------------------------------------------------
	 メール送信処理
  ----------------------------------------------------------------------------*/
	function MailSend(){
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v();

		// データID
		$dataid					=	$_POST["DATA_ID"];					//データID

		// メール設定読込
		$lc_mails = new MailSettings($dataid);

		// 送信先の設定
		$send_target			=	$_POST["BATCHSEND_TARGET"];			//送信先
		$subject				=	$_POST["SUBJECT"];					//件名
		$from_address			=	$lc_mails->getMailAddr1();			//FROMアドレス
		$cc_address				=	$lc_mails->getMailAddr1();			//CCアドレス
		$bcc_address			=	NULL;								//BCCアドレス

		if(mb_strpos($send_target,",") > 0){
			// 一括送信
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"						=> $dataid,
									"WORK_STAFF_ID"					=> CONDITION_PLURAL."( ".$send_target." )"
									);
		} else {
			// 個別送信
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"						=> $dataid,
									"WORK_STAFF_ID"					=> $send_target
									);
		}

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array("DATA_ID","WORK_STAFF_ID");

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$w_sendtarget	= $dbobj->getRecord();

		// メール送信用処理
		foreach($w_sendtarget as $target){
			$to_address	=	null;
			$work_staff_id = $target["WORK_STAFF_ID"];
			$target_cnt++;
			if(is_null($target["WORK_HOME_MAIL"]) && is_null($target["WORK_MOBILE_PHONE_MAIL"])){
				echo "有効なメールアドレスが登録されていないため、\n送信出来ませんでした。";
			} else {
				// ユーザ名
				$str_name = preg_replace("/　/", "", preg_replace("/ /", "", $target["WORK_USER_NAME"]));

				// 送信先設定
				if(is_null($target["WORK_HOME_MAIL"])){
				} else {
					$to_address	=	$target["WORK_HOME_MAIL"];
				}

				if(is_null($target["WORK_MOBILE_PHONE_MAIL"])){
				} else {
					if(is_null($to_address)){
						$to_address	=	$target["WORK_MOBILE_PHONE_MAIL"];
					} else {
						$to_address	.=	",".$target["WORK_MOBILE_PHONE_MAIL"];
					}
				}

				// 件名のユーザ名を置換
				$subject	= $_POST["SUBJECT"];
				$subject	= preg_replace('/' . INFORMATION_NAME . '/', $str_name, $subject);

				// 本文のユーザ名を置換
				$body		= $_POST["BODY"];
				$body		= preg_replace('/' . INFORMATION_NAME . '/', $str_name, $body);

				// 作業費がある場合のみ、且つ、作業費表示フラグ = 表示
				if (is_null($target["WORK_UNIT_PRICE"]) || $target["WORK_UNIT_PRICE"] == 0){
					$body	= preg_replace("/【作業費】/"	."\n  ￥".INFORMATION_UNIT_PRICE."\n", "", $body);
				} else {
					if($target["WORK_UNIT_PRICE_DISPLAY_FLAG"] == 'N'){
						$body	= preg_replace("/【作業費】/"	."\n  ￥".INFORMATION_UNIT_PRICE."\n", "", $body);
					} else {
						$body	= preg_replace('/' . INFORMATION_UNIT_PRICE . '/', number_format($target["WORK_UNIT_PRICE"]), $body);
					}
				}

				// 送信者のユーザーIDを取得
				session_start();
				$l_sender_userid = $_SESSION["_authsession"]["data"]["USER_ID"];

				// メール送信
				require_once('../lib/SendPHPMail.php');
				$lc_sgm = new SendPHPMail($target['DATA_ID']);

				// From
				$lc_sgm->setFromaddr($from_address);
				// To
				$lc_sgm->setToAddress($to_address);
				// Cc
				$lc_sgm->setCcAddress($cc_address);
				// Bcc
				$lc_sgm->setBccAddress($bcc_address);
				// Subject
				$lc_sgm->setSubject($subject);
				// Body
				$lc_sgm->setBody($body);

				// 送信ログ用データセット
				$lc_sgm->setLogDataId($target['DATA_ID']);
				$lc_sgm->setLogSendUserId($l_sender_userid);
				$lc_sgm->setLogUserId($l_sender_userid);
				$lc_sgm->setSendPurpose("作業依頼");

				// メール送信
				$l_result = $lc_sgm->doSend();

				if ($l_result > 0){
					echo $str_name."様の[作業依頼]送信に失敗しました。\n[error]".$result->getMessage()."\n";
				} else {
					// Update対象テーブル名
					$table_name = SCHEMA_NAME.".WORK_STAFF";

					// メール送信フラグを送信済に設定
					$transmission_flag = 'Y';										//メール送信フラグ->送信済

					$sql = null;
					$sql .= "update ".$table_name." "."set ";

					// 共通部分
					$sql .= " TRANSMISSION_FLAG = '".$transmission_flag."' ";
					$sql .= ",LAST_UPDATE_DATET = now() ";
					$sql .= ",LAST_UPDATE_USER_ID = '".$_POST["LOGINUSER_ID"]."' ";

					// 更新キーの設定
					$sql .= "where DATA_ID = '".$dataid."' ";
					$sql .= "  and WORK_STAFF_ID = '".$work_staff_id."'; ";

					// 送信フラグを送信済に設定
					require_once('../mdl/CommonExecution.php');
					$dbobj = new CommonExecution();
					$dbobj->CommonSilentSQL($sql);

					echo $str_name."様に[作業依頼]を送信しました。\n";
				}
			}
		}
	}

/*----------------------------------------------------------------------------
	 作業人員が登録されているか検索
  ----------------------------------------------------------------------------*/
	function DataForSearch($dataid,$estimateid,$workdate,&$s_workstaff,$workcontentid = ""){

		require_once('../mdl/Workcontents_copy_v.php');
		$dbobj = new Workcontents_copy_v();

		$validityflag = 'Y';									//有効フラグ(Yのみ)

		// 条件配列セット
		if($workcontentid == ""){
			$this->ar_condition = array(
									"DATA_ID"							=> $dataid,
									"ESTIMATE_ID"						=> $estimateid,
									"WORK_DATE"							=> $workdate,
									"WS_VALIDITY_FLAG"					=> $validityflag
									);
		}else {
			$this->ar_condition = array(
									"DATA_ID"							=> $dataid,
									"ESTIMATE_ID"						=> $estimateid,
									"WORK_CONTENT_ID"					=> $workcontentid,
									"WORK_DATE"							=> $workdate,
									"WS_VALIDITY_FLAG"					=> $validityflag
									);
		}
		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$s_workstaff = $dbobj->getRecord();
	}

/*----------------------------------------------------------------------------
	 作業人員のコピー処理
  ----------------------------------------------------------------------------*/
	function DataForCopy($dataid,$workcontentid,$baseid,$userid,$entering_schedule_timet,$leave_schedule_timet,$basic_time,$break_time,&$msg){

		require_once('../mdl/Workcontents_copy_v.php');
		$dbobj = new Workcontents_copy_v();

		$validityflag = 'Y';									//有効フラグ(Yのみ)

		// 条件配列セット
		$this->ar_condition = array(
								"DATA_ID"							=> $dataid,
								"WORK_CONTENT_ID"					=> $workcontentid,
								"WORK_USER_ID"						=> $userid
								);
		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// レコード取得
		$sc_workstaff = $dbobj->getRecord();
		if(count($sc_workstaff) == 0){
			//作業人員を登録処理
			$p_table_name	= "WORK_STAFF";
			$sql_column		= "INSERT INTO ".SCHEMA_NAME.".".$p_table_name ." "." (";
			$sql_data		= "VALUE (";

			// DATA_ID
			$sql_column		.= "DATA_ID ";
			$sql_data		.= "'".$dataid."' ";

			// 作業内容ID
			$sql_column		.= ",WORK_CONTENT_ID ";
			$sql_data		.= ",'".$workcontentid."' ";

			// 作業拠点ID
			$sql_column		.= ",WORK_BASE_ID ";
			$sql_data		.= ",'".$baseid."' ";

			// 作業者ユーザID
			$sql_column		.= ",WORK_USER_ID ";
			$sql_data		.= ",'".$userid."' ";

			// 入店予定時間
			$sql_column		.= ",ENTERING_SCHEDULE_TIMET ";
			if($entering_schedule_timet == ""){
				$sql_data	.= ",default ";
			} else {
				$sql_data	.= ",'".$entering_schedule_timet."' ";
			}

			// 退店予定時間
			$sql_column		.= ",LEAVE_SCHEDULE_TIMET ";
			if($leave_schedule_timet == ""){
				$sql_data	.= ",default ";
			} else {
				$sql_data	.= ",'".$leave_schedule_timet."' ";
			}

			// 基本時間
			$sql_column		.= ",BASIC_TIME ";
			if($basic_time == ""){
				$sql_data	.= ",default ";
			} else {
				$sql_data	.= ",'".$basic_time."' ";
			}

			// 休憩時間
			$sql_column		.= ",BREAK_TIME ";
			if($break_time == ""){
				$sql_data	.= ",default ";
			} else {
				$sql_data	.= ",'".$break_time."' ";
			}

			// 共通項目
			$sql_column		.= ",VALIDITY_FLAG ";
			$sql_data		.= ",default ";
			$sql_column		.= ",REGISTRATION_DATET ";
			$sql_data		.= ",now() ";
			$sql_column		.= ",REGISTRATION_USER_ID ";
			$sql_data		.= ",'".$_POST["LOGINUSER_ID"]."' ";
			$sql_column		.= ",LAST_UPDATE_DATET ";
			$sql_data		.= ",now() ";
			$sql_column		.= ",LAST_UPDATE_USER_ID"." ) ";
			$sql_data		.= ",'".$_POST["LOGINUSER_ID"]."' ); ";

			$sql = $sql_column.$sql_data;

			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
		}

		return $sql;
	}
/*----------------------------------------------------------------------------
  作業完了一覧取得
  作業者ステータスがWC(作業完了)で、1カ月前の1日以降のデータを取得
  引数:			$p_user_id			WORK_USER_IDの条件にセットするユーザーID
				$p_staff_status		作業者ステータス
				$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
  ----------------------------------------------------------------------------*/
	function getCompletionList($p_user_id, $p_staff_status = '', $p_include_invalid = 'N'){
		//print "p_user_id = ".$p_user_id." p_staff_status = ".$p_staff_status."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";
		$l_date_cond	= "WORK_DATE >= DATE_ADD(DATE_ADD(last_day(now()),INTERVAL -2 MONTH),INTERVAL 1 DAY)";

		// クラスインスタンス作成
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v('WORKSTAFF_MOBILE_V');

		// 並べ替え
		$dbobj->setOrderbyPhrase(array("WORK_DATE desc", "LEAVE_STAFF_TIMET desc"));

		// 条件設定
		// ID
		$l_ar_condition["WORK_USER_ID"]	= $p_user_id;
		// 作業者ステータス
		if(trim($p_staff_status) != ''){
			$l_ar_condition["STAFF_STATUS"]	= $p_staff_status;
		}

		// 有効フラグの条件
		if($p_include_invalid == 'N'){
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
		}


		// 条件セット
		if(count($l_ar_condition) > 0){
			// 何かの条件がセットされている場合
			$l_cond_ret = $dbobj->setCondition($l_ar_condition);
			// 1か月前のデータまでを取得
			$l_cond_ret	.= "and ".$l_date_cond;
		}else{
			// 1か月前のデータまでを取得
			$l_cond_ret	= "where ".$l_date_cond;
		}
		$dbobj->setWherePhraseText($l_cond_ret);

		//print $l_cond_ret."<br>";
		// レコード取得
		$l_result_rec = $dbobj->getRecord();
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
						$l_return_value[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);
						if($item_key=="WORK_NAME"){
							// 作業内容については、省略表示用のカラムも作成する
							$l_value_len = mb_strlen($item_value,ENCODE_TYPE);
							if($l_value_len >= $this->workname_short_size){
								$l_short_value = mb_substr($item_value, 0, $this->workname_short_size,ENCODE_TYPE)."...";
							}else{
								$l_short_value = $item_value;
							}

							if($l_short_value != ""){
								$l_return_value[$l_loop_cnt]["WORK_NAME_SHORT"] = $l_short_value;
							}else{
								// 作業内容が未登録の場合は、(作業名未登録)を表示
								$l_return_value[$l_loop_cnt]["WORK_NAME_SHORT"] = "(作業名未登録)";
							}
						}
						if($item_key=="WORK_DATE"){
							// 作業日時については、省略表示用のカラムも作成する
							$l_return_value[$l_loop_cnt]["WORK_DATE_SHORT"] = mb_substr($item_value, 5, 5,ENCODE_TYPE);
						}
					}
				}
			}
		}
		//print_r($l_return_value);
		// 条件初期化
		$dbobj->resetCondition();

		return $l_return_value;
	}
/*----------------------------------------------------------------------------
  作業取得
  引数で指定された作業人員IDの作業を取得
  引数:			$p_work_staff_id	作業人員ID
				$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
  ----------------------------------------------------------------------------*/
	function getWorkStaffRec($p_work_staff_id, $p_include_invalid = 'N'){
		//print "p_work_staff_id = ".$p_work_staff_id." p_include_invalid = ".$p_include_invalid."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";

		// クラスインスタンス作成
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v('WORKSTAFF_MOBILE_V');

		// 条件設定
		// ID
		$l_ar_condition["WORK_STAFF_ID"]	= $p_work_staff_id;

		// 有効フラグの条件
		if($p_include_invalid == 'N'){
			$l_ar_condition["VALIDITY_FLAG"] = "Y";
		}

		// 条件セット
		$l_cond_ret = $dbobj->setCondition($l_ar_condition);

		//print $l_cond_ret."<br>";
		// レコード取得
		$l_result_rec = $dbobj->getRecord();
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
						//$l_return_value[$l_loop_cnt][$item_key] = $item_value;
						$l_return_value[$item_key] = htmlspecialchars($item_value);
					}
				}
			}
		}
		//print_r($l_return_value);
		// 条件初期化
		$dbobj->resetCondition();

		return $l_return_value;
	}

/*----------------------------------------------------------------------------
  作業一覧取得(作業詳細画面用)
  作業者ステータスがWC(作業完了)以外のデータを取得
  引数:			$p_user_id			WORK_USER_IDの条件にセットするユーザーID
				$p_cancel_division	キャンセル区分
				$p_staff_status		作業者ステータス
				$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
  ----------------------------------------------------------------------------*/
	function getContentsList($p_user_id, $p_cancel_division = '', $p_staff_status ='', $p_include_invalid = 'N'){

		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";

		// クラスインスタンス作成
		require_once('../mdl/Workstaff_v.php');
		$dbobj = new Workstaff_v('WORKSTAFF_MOBILE_LIST_V');

		// 並べ替え
		$dbobj->setOrderbyPhrase(array("WORK_DATE", "ENTERING_SCHEDULE_TIMET", "AGGREGATE_TIMET"));

		// 条件設定
		// 作業者(WORK_USER_ID)
		$l_ar_condition["WORK_USER_ID"]	= "'".$p_user_id."'";

		// キャンセル区分(CANCEL_DIVISION)
		if(trim($p_cancel_division) != ''){
			$l_ar_condition["CANCEL_DIVISION"]	= $p_cancel_division;
		}

		// 作業員ステータス(STAFF_STATUS)
		if(trim($p_staff_status) != ''){
			$l_ar_condition["STAFF_STATUS"] = "!".$p_staff_status;
		}

		// 有効フラグ(VALIDITY_FLAG)
		if($p_include_invalid == 'N'){
			$l_ar_condition["WS_VALIDITY_FLAG"] = "Y";
		}

		// 条件セット
		$l_cond_ret = $dbobj->setCondition($l_ar_condition);


		$dbobj->setWherePhraseText($l_cond_ret);

		// レコード取得
		$l_result_rec = $dbobj->getRecord();

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
						$l_return_value[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);
						if($item_key=="WORK_NAME"){
							// 作業内容については、省略表示用のカラムも作成する
							$l_value_len = mb_strlen($item_value,ENCODE_TYPE);
							if($l_value_len >= $this->workname_short_size){
								$l_short_value = mb_substr($item_value, 0, $this->workname_short_size,ENCODE_TYPE)."...";
							}else{
								$l_short_value = $item_value;
							}
							$l_return_value[$l_loop_cnt]["WORK_NAME_SHORT"] = $l_short_value;
						}
						if($item_key=="WORK_DATE"){
							// 作業日時については、省略表示用のカラムも作成する
							$l_return_value[$l_loop_cnt]["WORK_DATE_SHORT"] = mb_substr($item_value, 5, 5,ENCODE_TYPE);
						}
					}
				}
			}
		}
		// 条件初期化
		$dbobj->resetCondition();

		return $l_return_value;
	}

/*----------------------------------------------------------------------------
  作業人員承認区分更新
  承認区分=UC(未確認)をUA(未回答)に更新
  引数:			$p_user_id			ユーザーID
				$p_staff_id			作業者ユーザーID
  ----------------------------------------------------------------------------*/
	function upApproval($p_user_id, $p_staff_id){
		// SQL文生成用配列
		$sql = null;

		// Update対象テーブル名
		$table_name = SCHEMA_NAME.".WORK_STAFF";
		$sql .= "update ".$table_name." "."Set ";

		// 更新部分
		$sql .= "APPROVAL_DIVISION = 'UA'";
		$sql .= ",LAST_UPDATE_DATET = now() ";
		$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
		$sql .= "where WORK_STAFF_ID = '".$p_staff_id."'; ";

		// SQLの実行
		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$dbobj->CommonSilentSQL($sql);
	}

/*----------------------------------------------------------------------------
  作業人員を更新
  WORK_STAFFの作業を更新
  引数:			$p_user_id					作業者ID
  				$p_data						POSTされた配列
  ----------------------------------------------------------------------------*/
	function upWorkstaffDetail($p_user_id, $p_data){

		$p_table_name	= "WORK_STAFF";
		$sql_type		= "UPDATE";
		$sql			= NULL;
		$sql_column		= NULL;
		$sql_data		= NULL;
		$l_return_value	= array();
		$err_check_code = 0;

		for( $i = 1; $i<=NUMBER_OF_POST; $i++ ){
			switch ($p_data[$i][Input_col]) {
				//設定するコード値
				case "WORK_DATE":
					$w_date_num		=	$i;
				break;
				default:
				break;
			}
		}

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
			$info_colum[$j]	= $column_chk[$j]["COLUMN_NAME"];
			$info_key[$j]	= $column_chk[$j]["COLUMN_KEY"];
			$info_table[$j]	= array( column_name				=>$column_chk[$j]["COLUMN_NAME"]
									,data_type					=>$column_chk[$j]["DATA_TYPE"]
									,character_maximum_length	=>$column_chk[$j]["CHARACTER_MAXIMUM_LENGTH"]
									,is_nullable				=>$column_chk[$j]["IS_NULLABLE"]
									,column_default				=>$column_chk[$j]["COLUMN_DEFAULT"]);
		}

		// 入力項目を共通チェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CommonCheck.php');
		$dbcommoncheck = new CommonCheck();

		// ユーザ固有の入力項目をチェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/WorkStaffCheck.php');
		$dbworkstaffcheck = new WorkStaffCheck();

		// テーブルに登録するSQL文を作成するファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CreateSQL.php');
		$dbcreatesql = new CreateSQL();

		for( $i = 1; $i<=count($p_data); $i++ ){
			// モジュールに入れる$_POSTの値を変数に代入する。
			$entry_key		= $p_data[$i][Input_col];
			$entry_value	= $p_data[$i][Input_val];

			if($p_data[$i][Input_col] == "APPROVAL_DIVISION"){
				$approval_division			=	$p_data[$i][Input_val];
			} else if($p_data[$i][Input_col] == "DISPATCH_SCHEDULE_TIMET"){
				$dispatch_schedule_timet	=	$p_data[$i][Input_val];
			}

			// 入力項目を共通チェックするファンクションの呼び出し
			$err_check_common = $dbcommoncheck->CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if($err_check_common["Code"] == 1){
				if($entry_key == "WORK_BASE_ID"){
					$l_return_value[$i] = str_replace("作業拠点ID","拠点名",$err_check_common["Message"]);
				} else if($entry_key == "WORK_USER_ID"){
					$l_return_value[$i] = str_replace("作業者ユーザID","作業者名",$err_check_common["Message"]);
				} else{
					$l_return_value[$i] = $err_check_common["Message"];
				}
			}

			// 作業人員固有の入力項目をチェックするファンクションの呼び出し
			$err_check_workstaff = $dbworkstaffcheck->WorkStaffMobileDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);
			if($err_check_workstaff["Code"] == 1){
				$l_return_value[$i] = $err_check_workstaff["Message"];
			}

			// 出発・入店・退店時間は、作業日をくっ付けてdatetime型にする。
			if($entry_key == "DISPATCH_SCHEDULE_TIMET"){
				$p_work_date = $p_data[$w_date_num][Input_val];
				$entry_value = $this->convert_DATETIME($entry_value,$p_work_date);
			}

			// テーブルに登録するSQL文を作成するファンクションの呼び出し
			$dbcreatesql->CreateSQLString($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value,NULL);

			if ($err_check_common["Code"] == 1 || $err_check_workstaff["Code"] == 1){
				$err_check_code = 1;
			}

		}

		// 承認区分 = AP かつ 出発予定時間が設定せれない場合はエラーとする
		if($approval_division == "AP" && $dispatch_schedule_timet == NULL){
			$err_check_code = 1;
			$l_return_value[$i] = "「出発予定時間」を入力して下さい。"."\n";
		}

		if ($err_check_code != 1){
			//SQL文の作成
			//更新登録
			$sql .= "UPDATE ".SCHEMA_NAME.".".$p_table_name ." "." SET ".$dbcreatesql->d_update;
			$sql .= ",LAST_UPDATE_DATET = now() ";
			$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			$sql .= " WHERE ".$dbcreatesql->pri_column_name ." = '".$dbcreatesql->pri_column_data."' ";

			//print "sql->".$sql."\n";

			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$l_return_value = $dbobj->CommonMobileSQL($sql);
		} else {
			$l_return_value[RETERN_CODE] = RETURN_ERROR;
		}

		return $l_return_value;
	}

/*----------------------------------------------------------------------------
  代理登録で作業人員を更新
  WORK_STAFFの作業を更新
  引数:			$p_user_id					作業者ID
  				$p_data						POSTされた配列
  ----------------------------------------------------------------------------*/
	function CommissionUpWorkstaffDetail($p_user_id, $p_data){

		$p_table_name	= "WORK_STAFF";
		$sql_type		= "UPDATE";
		$sql			= NULL;
		$sql_column		= NULL;
		$sql_data		= NULL;
		$l_return_value	= array();

		for( $i = 1; $i<=NUMBER_OF_POST; $i++ ){
			switch ($p_data[$i][Input_col]) {
				//設定するコード値
				case "WORK_DATE":
					$w_date_num		=	$i;
				break;
				default:
				break;
			}
		}

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
			$info_colum[$j]	= $column_chk[$j]["COLUMN_NAME"];
			$info_key[$j]	= $column_chk[$j]["COLUMN_KEY"];
			$info_table[$j]	= array( column_name				=>$column_chk[$j]["COLUMN_NAME"]
									,data_type					=>$column_chk[$j]["DATA_TYPE"]
									,character_maximum_length	=>$column_chk[$j]["CHARACTER_MAXIMUM_LENGTH"]
									,is_nullable				=>$column_chk[$j]["IS_NULLABLE"]
									,column_default				=>$column_chk[$j]["COLUMN_DEFAULT"]);
		}

		// 入力項目を共通チェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CommonCheck.php');
		$dbcommoncheck = new CommonCheck();

		// ユーザ固有の入力項目をチェックするファンクションがあるオブジェクトの呼び出し
		require_once('../lib/WorkStaffCheck.php');
		$dbworkstaffcheck = new WorkStaffCheck();

		// テーブルに登録するSQL文を作成するファンクションがあるオブジェクトの呼び出し
		require_once('../lib/CreateSQL.php');
		$dbcreatesql = new CreateSQL();

		for( $i = 1; $i<=count($p_data); $i++ ){

			// モジュールに入れる$_POSTの値を変数に代入する。
			$entry_key		= $p_data[$i][Input_col];
			$entry_value	= $p_data[$i][Input_val];

			if($p_data[$i][Input_col] == "APPROVAL_DIVISION"){
				$approval_division			=	$p_data[$i][Input_val];
			}

			// 入力項目を共通チェックするファンクションの呼び出し
			$err_check_common = $dbcommoncheck->CommonDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if($err_check_common["Code"] == 1){
				if($entry_key == "WORK_BASE_ID"){
					$l_return_value[$i] = str_replace("作業拠点ID","拠点名",$err_check_common["Message"]);
				}
				else if($entry_key == "WORK_USER_ID"){
					$l_return_value[$i] = str_replace("作業者ユーザID","作業者名",$err_check_common["Message"]);
				}
				else{
					$l_return_value[$i] = $err_check_common["Message"];
				}
			}

			// 作業人員固有の入力項目をチェックするファンクションの呼び出し
			$err_check_workstaff = $dbworkstaffcheck->WorkStaffDetailMobileDataCheck($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value);

			if($err_check_workstaff["Code"] == 1){
				$l_return_value[$i] = $err_check_workstaff["Message"];
			}

			// 出発・入店・退店時間は、作業日をくっ付けてdatetime型にする。
			if($p_data[$i][Input_col] == "DISPATCH_STAFF_TIMET" || $p_data[$i][Input_col] == "ENTERING_STAFF_TIMET" || $p_data[$i][Input_col] == "LEAVE_STAFF_TIMET"){
				$p_work_date = $p_data[$w_date_num][Input_val];
				$entry_value = $this->convert_DATETIME($entry_value,$p_work_date);
			}

			// テーブルに登録するSQL文を作成するファンクションの呼び出し
			$dbcreatesql->CreateSQLString($p_table_name,$sql_type,$cchk,$info_colum,$info_key,$info_table,$entry_key,$entry_value,NULL);

			if ($err_check_common["Code"] == 1 || $err_check_workstaff["Code"] == 1){
				$err_check_code = 1;
			}

		}

		if ($err_check_code != 1){
			//SQL文の作成
			//更新登録
			$sql .= "UPDATE ".SCHEMA_NAME.".".$p_table_name ." "." SET ".$dbcreatesql->d_update;
			$sql .= ",LAST_UPDATE_DATET = now() ";
			$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
			$sql .= " WHERE ".$dbcreatesql->pri_column_name ." = '".$dbcreatesql->pri_column_data."' ";

			//print $sql."\n";

			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$l_return_value = $dbobj->CommonMobileSQL($sql);
		} else {
			$l_return_value[RETERN_CODE] = RETURN_ERROR;
		}

		return $l_return_value;
	}
/*----------------------------------------------------------------------------
  代理登録で作業人員を更新
  WORK_STAFFの作業を更新
  引数:			$p_user_id					作業者ID
  				$p_data						CANCEL_DIVISIONのステータス
  ----------------------------------------------------------------------------*/
	function upCancelDivision($p_user_id,$p_work_staff_id, $p_data){

		$p_table_name	= "WORK_STAFF";
		$sql_type		= "UPDATE";
		$sql			= NULL;
		$sql_column		= NULL;
		$sql_data		= NULL;
		$l_return_value	= array();

		//SQL文の作成
		//更新登録
		$sql .= "UPDATE ".SCHEMA_NAME.".".$p_table_name ." "." SET CANCEL_DIVISION ='".$p_data."' ";
		$sql .= ",LAST_UPDATE_DATET = now() ";
		$sql .= ",LAST_UPDATE_USER_ID = '".$p_user_id."' ";
		$sql .= " WHERE WORK_STAFF_ID = '".$p_work_staff_id."' ";

		// print $sql."\n";

		require_once('../mdl/CommonExecution.php');
		$dbobj = new CommonExecution();
		$l_return_value = $dbobj->CommonMobileSQL($sql);

		return $l_return_value;
	}

/*----------------------------------------------------------------------------
  省略時の文字数setter
  引数:			$p_value			作業名省略時の文字数
  ----------------------------------------------------------------------------*/
	function setWorknameShortSize($p_value){
		$this->workname_short_size = $p_value;
		return $this->workname_short_size;
	}

/*----------------------------------------------------------------------------
  作業状況一覧取得
  指定した作業日、作業名と一致する作業のデータを取得
  引数:			$p_work_date		入力項目で指定した作業日
				$p_work_name		入力項目で指定した作業名
				$p_base_name		入力項目で指定した拠点名
				$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
				$p_data_id			データID
  ----------------------------------------------------------------------------*/
	function get_WorkSituation($p_work_date, $p_work_name, $p_base_name, $p_end_work_date = "",$p_data_id){
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";
		$l_date_cond	= "WORK_DATE >= DATE_ADD(DATE_ADD(last_day(now()),INTERVAL -2 MONTH),INTERVAL 1 DAY)";

		// クラスインスタンス作成
		require_once('../mdl/m_get_worksituation.php');
		$dbobj = new GetWorksituation('WORKSTAFF_MOBILE_V');
		// 検索キーの設定
		$validityflag						= 'Y';								//有効フラグ(Yのみ)


		//作業日
		if(is_null($p_work_date)){
			$workdate						=	date( "Y-m-d");
		} else {
			$workdate						=	$p_work_date;
		}

		//作業名
		if($p_work_name == ""){
			$workname						=	"";
		} else {
			$workname						=	$p_work_name;
		}

		//拠点名
		if($p_base_name == ""){
			$basename						=	"";
		} else {
			$basename						=	$p_base_name;
		}
		$specified_order				= "WORK_DATE".","."WORK_NAME".","."WORK_BASE_NAME";

		if($p_end_work_date == ""){

			// 条件配列セット
			$this->ar_condition = array(
									"WORK_DATE"			=> "%".$workdate."%",
									"WORK_NAME"			=> "%".$workname."%",
									"WORK_BASE_NAME"	=> "%".$basename."%",
									"VALIDITY_FLAG"		=> $validityflag,
									"DATA_ID"			=> $p_data_id
						);
		}else {
			// 条件配列セット
			$this->ar_condition = array(
									"WORK_DATE"			=> "<".$p_end_work_date."' and WORK_DATE >= '".$workdate,
									"WORK_NAME"			=> "%".$workname."%",
									"WORK_BASE_NAME"	=> "%".$basename."%",
									"VALIDITY_FLAG"		=> $validityflag,
									"DATA_ID"			=> $p_data_id
						);
		}

		//print_r($this->ar_condition);

		// 条件セット
		$l_ar_cond = $dbobj->setCondition($this->ar_condition);

		// order by配列セット
		$this->ar_orderby = array(
							//	"WORK_NAME",
							//	"WORK_DATE",
								$specified_order,
								"WORK_USER_NAME"
								);

		// order byセット
		$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);

		// レコード取得
		$l_result_rec = $dbobj->getRecord();

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
						if($item_key=="WORK_NAME"){
							// 作業名が登録されていない場合は、「登録なし」と表示させる。
							if(is_null($item_value) || $item_value == ""){
								$item_value = "登録なし";
							}
							// 作業内容については、省略表示用のカラムも作成する
							$l_value_len = mb_strlen($item_value,ENCODE_TYPE);
							if($l_value_len >= $this->workname_short_size){
								$l_short_value = mb_substr($item_value, 0, $this->workname_short_size,ENCODE_TYPE)."...";
							}else{
								$l_short_value = $item_value;
							}
							$l_return_value[$l_loop_cnt]["WORK_NAME_SHORT"] = $l_short_value;
						}
						if($item_key=="WORK_BASE_NAME"){
							// 拠点名については、省略表示用のカラムも作成する
							$l_value_len = mb_strlen($item_value,ENCODE_TYPE);
							if($l_value_len >= $this->workname_short_size){
								$l_short_value = mb_substr($item_value, 0, $this->workname_short_size,ENCODE_TYPE)."...";
							}else{
								$l_short_value = $item_value;
							}
							$l_return_value[$l_loop_cnt]["WORK_BASE_NAME_SHORT"] = $l_short_value;
						}
						if($item_key=="WORK_USER_NAME"){
							// 作業者名については、省略表示用のカラムも作成する
							$item_value = mb_convert_kana($item_value,"A",ENCODE_TYPE);
							$item_value = str_replace("　", "", $item_value);
							$l_value_len = mb_strlen($item_value,ENCODE_TYPE);
							if($l_value_len >= $this->username_short_size){
								$l_short_value = mb_substr($item_value, 0, $this->username_short_size,ENCODE_TYPE);
								$l_return_value[$l_loop_cnt]["NAME_COUNT"] = 0;
							}else{
								$l_short_value = $item_value;
								$l_return_value[$l_loop_cnt]["NAME_COUNT"] = $this->username_short_size -$l_value_len;
							}
							$l_return_value[$l_loop_cnt]["WORK_USER_NAME_SHORT"] = $l_short_value;
						}
						if($item_key=="WORK_DATE"){
							// 作業日時については、省略表示用のカラムも作成する
							$l_return_value[$l_loop_cnt]["WORK_DATE_SHORT"] = mb_substr($item_value, 5, 5,ENCODE_TYPE);
						}
					}
				}
				// 作業日、作業名、拠点名がひとつ前の配列と同じ場合はチェックを入れて画面に表示させない
				if($l_loop_cnt != 1 &&$l_return_value[$l_loop_cnt]["WORK_DATE"] == $l_return_value[$l_loop_cnt-1]["WORK_DATE"]){
					$l_return_value[$l_loop_cnt]["DATE_CHECK"] = "1";
				}else{
					$l_return_value[$l_loop_cnt]["DATE_CHECK"] = "0";
				}
				if($l_return_value[$l_loop_cnt]["WORK_NAME"] == $l_return_value[$l_loop_cnt-1]["WORK_NAME"]){
					$l_return_value[$l_loop_cnt]["NAME_CHECK"] = "1";
				}else{
					$l_return_value[$l_loop_cnt]["NAME_CHECK"] = "0";
				}
				if($l_return_value[$l_loop_cnt]["WORK_BASE_NAME"] == $l_return_value[$l_loop_cnt-1]["WORK_BASE_NAME"]){
					$l_return_value[$l_loop_cnt]["BASE_CHECK"] = "1";
				}else{
					$l_return_value[$l_loop_cnt]["BASE_CHECK"] = "0";
				}
			}
		}

		$dbobj->resetCondition();
		return $l_return_value;
		//return $l_result_rec;
	}
/*----------------------------------------------------------------------------
  入力されたtime型の出発・入店・退店時間に作業日を取り付けて
  datetime型に変更して値を返す。
  引数:			$p_time						出発・入店・退店時間
  				$p_work_date				作業日
 ----------------------------------------------------------------------------*/
	function convert_DATETIME($p_time, $p_work_date){
		if($p_time != ""){
			if(mb_strpos($p_time ,":")){
				// 時間を":"ごとに区切る。
				$array_time = preg_split("/\:/",$p_time);
			}else{
				$array_time[0] = substr($p_time,0,2);
				$array_time[1] = substr($p_time,-2,2);
				$p_time = $array_time[0].":".$array_time[1];
			}
			// 引数で渡された時間が24時以降の場合は24を引いて作業日に1日分加算する。
			if($array_time[0] > 23){
				$array_time[0] = $array_time[0] - 24;
				$p_time = $array_time[0].":".$array_time[1];

				// 作業日をタイムスタンプに変換する。
				$array_date = preg_split("/\-/",$p_work_date);
				$s = mktime(0,0,0,$array_date[1],$array_date[2],$array_date[0]);

				// 1日を秒数に変換する
				$d = 1 * 60 * 60 * 24;

				 // 作業日のタイムスタンプに1日の秒数を加算する
				$add_date = $s + $d;

				$p_work_date = date("Y-m-d",$add_date);
			}

			$p_work_datetime = $p_work_date." ".$p_time;

			return $p_work_datetime;
		}

		return $p_time;
	}
/*----------------------------------------------------------------------------
  入力されたdatetime型の出発・入店・退店時間から作業日を切り離して
  time型に変更して値を返す。
  引数:			$p_datetime					出発・入店・退店時間
				$p_work_date				作業日
 ----------------------------------------------------------------------------*/
	function convert_TIME($p_datetime, $p_work_date){
		if($p_datetime != ""){
			// 日付時間を" "で区切る。
			$array_datetime = preg_split("/ /",$p_datetime);

			if($array_datetime[1] != ""){
				// 日付をタイムスタンプに変換する。
				$array_date_S = preg_split("/\-/",$p_work_date);
				$s = mktime(0,0,0,$array_date_S[1],$array_date_S[2],$array_date_S[0]);

				$array_date_D = preg_split("/\-/",$array_datetime[0]);
				$d = mktime(0,0,0,$array_date_D[1],$array_date_D[2],$array_date_D[0]);

				//print $d."\n";
				//print $s."\n";

				// 引数で渡された日付時間が作業日より後の場合は、区切った$array_datetime[1]に24時間を加算する。
				if($d > $s){
					// 時間を":"で区切る。
					$array_time = preg_split("/\:/", $array_datetime[1]);
					$add_time = $array_time[0] + 24;
					$p_time = $add_time.":".$array_time[1];
				}else {
					$p_time = $array_datetime[1];
				}

				return $p_time;
			}
		}

		return $p_datetime;
	}
}
?>