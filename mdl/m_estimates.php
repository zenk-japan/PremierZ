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
class m_estimates extends ModelCommon{
/*******************************************************************************
	クラス名：m_estimates.php
	処理概要：見積モデル
*******************************************************************************/
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス

	private $set_view_name	= 'ESTIMATES_V';	// ビュー名
	private $set_table_name	= 'ESTIMATES';		// テーブル名
	//※table_nameはModelCommonで定義されています

	private $primary_key_col = 'ESTIMATE_ID';	// 主キーの項目

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
	新規登録処理
	処理概要：INSERT処理を行う
			$pr_data						更新するカラムと値の連想配列(文字列は''で囲む事)
			$p_user_id				更新者キー値
  ============================================================================*/
	function execInsert($pr_data, $p_user_id = ''){
		// レコードが設定されていない場合は処理しない
		if(count($pr_data) == 0){
			return false;
		}
		// Insert文作成
		// ※DATA型とNumber型は値が無い場合0がセットされてしまう為、INSERT文から外す
		$l_sql  = "insert into `ESTIMATES` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'ESTIMATE_ID',";
		$l_sql .= "ESTIMATE_CODE,";
		$l_sql .= "SUB_NUMBER,";
		$l_sql .= "ESTIMATE_USER_ID,";
		if ($pr_data["ESTIMATE_REQUEST_DATE"] != ""){$l_sql .= "ESTIMATE_REQUEST_DATE,";}
		if ($pr_data["SCHEDULE_FROM_DATE"] != ""){$l_sql .= "SCHEDULE_FROM_DATE,";}
		if ($pr_data["SCHEDULE_TO_DATE"] != ""){$l_sql .= "SCHEDULE_TO_DATE,";}
		$l_sql .= "WORK_NAME,";
		if ($pr_data["ENDUSER_COMPANY_ID"] != ""){$l_sql .= "ENDUSER_COMPANY_ID,";}
		if ($pr_data["ENDUSER_USER_ID"] != ""){$l_sql .= "ENDUSER_USER_ID,";}
		if ($pr_data["REQUEST_COMPANY_ID"] != ""){$l_sql .= "REQUEST_COMPANY_ID,";}
		if ($pr_data["REQUEST_USER_ID"] != ""){$l_sql .= "REQUEST_USER_ID,";}
		if ($pr_data["SUBMITTING_DATE1"] != ""){$l_sql .= "SUBMITTING_DATE1,";}
		if ($pr_data["SUBMITTING_DATE2"] != ""){$l_sql .= "SUBMITTING_DATE2,";}
		if ($pr_data["SUBMITTING_DATE3"] != ""){$l_sql .= "SUBMITTING_DATE3,";}
		if ($pr_data["SUBMITTING_DATE4"] != ""){$l_sql .= "SUBMITTING_DATE4,";}
		if ($pr_data["SUBMITTING_DATE5"] != ""){$l_sql .= "SUBMITTING_DATE5,";}
		if ($pr_data["FINAL_PRESENTATION_AMOUNT"] != ""){$l_sql .= "FINAL_PRESENTATION_AMOUNT,";}
		$l_sql .= "ORDER_DIVISION,";
		if ($pr_data["WORK_COMPLETION_DATE"] != ""){$l_sql .= "WORK_COMPLETION_DATE,";}
		$l_sql .= "WORK_DIVISION,";
		$l_sql .= "REMARKS,";
		if ($pr_data["BOOK_INPUT_DATE"] != ""){$l_sql .= "BOOK_INPUT_DATE,";}
		if ($pr_data["BILL_SENDING_DATE"] != ""){$l_sql .= "BILL_SENDING_DATE,";}
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
		$l_sql .= $pr_data["DATA_ID"].",";							// DATA_ID
		//$l_sql .= "'',";											// ESTIMATE_ID
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ESTIMATE_CODE"])."',";				// ESTIMATE_CODE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUB_NUMBER"])."',";					// SUB_NUMBER
		$l_sql .= $p_user_id.",";									// ESTIMATE_USER_ID
		if ($pr_data["ESTIMATE_REQUEST_DATE"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ESTIMATE_REQUEST_DATE"])."',";}		// ESTIMATE_REQUEST_DATE
		if ($pr_data["SCHEDULE_FROM_DATE"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SCHEDULE_FROM_DATE"])."',";}			// SCHEDULE_FROM_DATE
		if ($pr_data["SCHEDULE_TO_DATE"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SCHEDULE_TO_DATE"])."',";}				// SCHEDULE_TO_DATE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_NAME"])."',";					// WORK_NAME
		if ($pr_data["ENDUSER_COMPANY_ID"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ENDUSER_COMPANY_ID"])."',";}			// ENDUSER_COMPANY_ID
		if ($pr_data["ENDUSER_USER_ID"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ENDUSER_USER_ID"])."',";}					// ENDUSER_USER_ID
		if ($pr_data["REQUEST_COMPANY_ID"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["REQUEST_COMPANY_ID"])."',";}			// REQUEST_COMPANY_ID
		if ($pr_data["REQUEST_USER_ID"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["REQUEST_USER_ID"])."',";}					// REQUEST_USER_ID
		if ($pr_data["SUBMITTING_DATE1"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUBMITTING_DATE1"])."',";}				// SUBMITTING_DATE1
		if ($pr_data["SUBMITTING_DATE2"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUBMITTING_DATE2"])."',";}				// SUBMITTING_DATE2
		if ($pr_data["SUBMITTING_DATE3"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUBMITTING_DATE3"])."',";}				// SUBMITTING_DATE3
		if ($pr_data["SUBMITTING_DATE4"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUBMITTING_DATE4"])."',";}				// SUBMITTING_DATE4
		if ($pr_data["SUBMITTING_DATE5"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["SUBMITTING_DATE5"])."',";}				// SUBMITTING_DATE5
		if ($pr_data["FINAL_PRESENTATION_AMOUNT"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["FINAL_PRESENTATION_AMOUNT"])."',";}		// FINAL_PRESENTATION_AMOUNT
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["ORDER_DIVISION"])."',";				// ORDER_DIVISION
		if ($pr_data["WORK_COMPLETION_DATE"] != ""){$l_sql .= "'".$pr_data["WORK_COMPLETION_DATE"]."',";}		// WORK_COMPLETION_DATE
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["WORK_DIVISION"])."',";				// WORK_DIVISION
		$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["REMARKS"])."',";						// REMARKS
		if ($pr_data["BOOK_INPUT_DATE"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["BOOK_INPUT_DATE"])."',";}					// BOOK_INPUT_DATE
		if ($pr_data["BILL_SENDING_DATE"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["BILL_SENDING_DATE"])."',";}				// BILL_SENDING_DATE
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
		if ($pr_data["VALIDITY_FLAG"] != ""){$l_sql .= "'".$this->getMysqlEscapedValue($pr_data["VALIDITY_FLAG"])."',";}else{$l_sql .= "'Y',";}				// VALIDITY_FLAG
		$l_sql .= "now(),";											// REGISTRATION_DATET
		$l_sql .= $p_user_id.",";									// REGISTRATION_USER_ID
		$l_sql .= "now(),";											// LAST_UPDATE_DATET
		$l_sql .= $p_user_id;										// LAST_UPDATE_USER_ID
		$l_sql .= ")";

		if($debug_mode == "1"){var_dump($l_sql);}
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

		// SQL組み立て
		// getUpdataSetPhraseを使用する場合は、mysql_real_escape_stringはgetUpdataSetPhraseないで行う為、
		// ここで実行する必要はない
		$l_sql  = "update `ESTIMATES` ";
		$l_sql .= "set ";
		$l_sql .= $this->getUpdataSetPhrase("SUB_NUMBER", 					$pr_data["SUB_NUMBER"], 				'C').",";
		$l_sql .= $this->getUpdataSetPhrase("ESTIMATE_REQUEST_DATE", 		$pr_data["ESTIMATE_REQUEST_DATE"], 		'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SCHEDULE_FROM_DATE", 			$pr_data["SCHEDULE_FROM_DATE"], 		'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SCHEDULE_TO_DATE", 			$pr_data["SCHEDULE_TO_DATE"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_NAME", 					$pr_data["WORK_NAME"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("ENDUSER_COMPANY_ID", 			$pr_data["ENDUSER_COMPANY_ID"], 		'N').",";
		$l_sql .= $this->getUpdataSetPhrase("ENDUSER_USER_ID", 				$pr_data["ENDUSER_USER_ID"], 			'N').",";
		$l_sql .= $this->getUpdataSetPhrase("REQUEST_COMPANY_ID", 			$pr_data["REQUEST_COMPANY_ID"], 		'N').",";
		$l_sql .= $this->getUpdataSetPhrase("REQUEST_USER_ID", 				$pr_data["REQUEST_USER_ID"], 			'N').",";
		$l_sql .= $this->getUpdataSetPhrase("SUBMITTING_DATE1", 			$pr_data["SUBMITTING_DATE1"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SUBMITTING_DATE2", 			$pr_data["SUBMITTING_DATE2"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SUBMITTING_DATE3", 			$pr_data["SUBMITTING_DATE3"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SUBMITTING_DATE4", 			$pr_data["SUBMITTING_DATE4"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("SUBMITTING_DATE5", 			$pr_data["SUBMITTING_DATE5"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("FINAL_PRESENTATION_AMOUNT", 	$pr_data["FINAL_PRESENTATION_AMOUNT"], 	'N').",";
		$l_sql .= $this->getUpdataSetPhrase("ORDER_DIVISION", 				$pr_data["ORDER_DIVISION"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_COMPLETION_DATE", 		$pr_data["WORK_COMPLETION_DATE"], 		'C').",";
		$l_sql .= $this->getUpdataSetPhrase("WORK_DIVISION", 				$pr_data["WORK_DIVISION"], 				'C').",";
		$l_sql .= $this->getUpdataSetPhrase("REMARKS", 						$pr_data["REMARKS"], 					'C').",";
		$l_sql .= $this->getUpdataSetPhrase("BOOK_INPUT_DATE", 				$pr_data["BOOK_INPUT_DATE"], 			'C').",";
		$l_sql .= $this->getUpdataSetPhrase("BILL_SENDING_DATE", 			$pr_data["BILL_SENDING_DATE"], 			'C').",";

		$l_sql .= "VALIDITY_FLAG = '".$pr_data["VALIDITY_FLAG"]."',";				// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";										// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = "			.$p_user_id." ";				// LAST_UPDATE_USER_ID
		$l_sql .= "where ESTIMATE_ID = "			.$p_key_value;

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

		foreach($this->r_table_rec as $l_recnum => $lr_record){
			//var_dump($lr_record);
			if(is_null($lr_record[$this->primary_key_col]) || $lr_record[$this->primary_key_col] == ''){
				// 主キーの設定が無い場合
				return false;
			}else{
				if(!$this->execUpdate($lr_record, $lr_record[$this->primary_key_col], $p_user_id)){
					// エラーとなるレコードが発生した時点で終了
					return false;
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

		// 始めに見積に紐付く作業データが有る場合はそれを削除する
		// 作業読み込み
		require_once('../mdl/m_workcontents.php');
		$lr_workcontents = "";
		$lr_whare = array();
		array_push($lr_whare, "ESTIMATE_ID in (".$l_del_target_id.")");
		$lc_workstaff	= new m_workcontents('Y', $lr_whare);
		$lr_workcontents	= $lc_workstaff->getViewRecord();
		$l_ws_del_target_id = "";

		if (count($lr_workcontents) > 0){
			// 削除する作業IDの設定
			foreach ($lr_workcontents as $l_rec_num => $lr_ws_rec){
				if($l_rec_num === 1){
					$l_ws_del_target_id .= $lr_ws_rec["WORK_CONTENT_ID"];
				}else {
					$l_ws_del_target_id .=",".$lr_ws_rec["WORK_CONTENT_ID"];
				}
			}
			// 作業削除
			if(!$lc_workstaff->deleteRecordByID($l_ws_del_target_id)){
				echo "作業削除に失敗しました。\n";
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
				// 見積コード、枝番
				// 新規登録の場合、すでに使用されている組み合わせの場合はエラーとする
				if ($lr_data_rec["sql_type"] == OPMODE_INSERT){
					$l_target_col_name	= "見積コード、枝番";
					$l_target_col		= "ESTIMATE_CODE";
					$lr_whare = array();
					$lr_estimate_rec = "";
					$this->r_view_rec = "";
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "ESTIMATE_CODE = '".$this->getMysqlEscapedValue($lr_data_rec['ESTIMATE_CODE'])."'");
					array_push($lr_whare, "SUB_NUMBER = '".$this->getMysqlEscapedValue($lr_data_rec['SUB_NUMBER'])."'");
					$this->r_where = $lr_whare;
					$this->queryDBRecord();
					if (count($this->r_view_rec) > 0){
						$lr_check_res[$l_key][$l_target_col]['STATUS']	= 2;
						$lr_check_res[$l_key][$l_target_col]['MESSAGE']	= $l_target_col_name."「".$lr_data_rec[$l_target_col]."、".$lr_data_rec['SUB_NUMBER']."」の組み合わせは既に使用されています。修正して下さい。\n";
					}
				}

				require_once('../mdl/m_company_master.php');

				// エンドユーザー会社
				// マスターを検索し、存在を確認する
				$l_target_col			= "ENDUSER_COMPANY_NAME";
				$l_target_col_id		= "ENDUSER_COMPANY_ID";
				$l_target_col_mst_id	= "COMPANY_ID";
				$l_target_col_name		= "エンドユーザー会社";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// 会社マスタから会社名をキーに値を取得(会社区分は敢えて条件に加えない)
					$lr_whare = array();
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "COMPANY_NAME = '".$lr_data_rec[$l_target_col]."'");
					array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_whare);
					$lc_master_mdl_class	= new m_company_master('Y', $lr_whare);
					$lr_master_mdl			= $lc_master_mdl_class->getViewRecord();

					// 会社が見つからない場合は、メッセージを追加する
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
				// 依頼元会社
				// マスターを検索し、存在を確認する
				$l_target_col			= "REQUEST_COMPANY_NAME";
				$l_target_col_id		= "REQUEST_COMPANY_ID";
				$l_target_col_mst_id	= "COMPANY_ID";
				$l_target_col_name		= "依頼元会社";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// 会社マスタから会社名をキーに値を取得(会社区分は敢えて条件に加えない)
					$lr_whare = array();
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "COMPANY_NAME = '".$lr_data_rec[$l_target_col]."'");
					array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_whare);
					$lc_master_mdl_class	= new m_company_master('Y', $lr_whare);
					$lr_master_mdl			= $lc_master_mdl_class->getViewRecord();

					// 会社が見つからない場合は、メッセージを追加する
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

				require_once('../mdl/m_user_master.php');
				// 見積担当者
				// マスターを検索し、存在を確認する
				$l_target_col			= "ESTIMATE_USER_NAME";
				$l_target_col_id		= "ESTIMATE_USER_ID";
				$l_target_col_mst_id	= "USER_ID";
				$l_target_col_name		= "見積担当者";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// ユーザーマスタからユーザー名をキーに値を取得
					$lr_whare = array();
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "NAME = '".$lr_data_rec[$l_target_col]."'");
					array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_whare);
					$lc_master_mdl_class	= new m_user_master('Y', $lr_whare);
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

				// エンドユーザー担当者
				// マスターを検索し、存在を確認する
				$l_target_col			= "ENDUSER_USER_NAME";
				$l_target_col_id		= "ENDUSER_USER_ID";
				$l_target_col_mst_id	= "USER_ID";
				$l_target_col_name		= "エンドユーザー担当者";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// ユーザーマスタからユーザー名をキーに値を取得
					$lr_whare = array();
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "NAME = '".$lr_data_rec[$l_target_col]."'");
					array_push($lr_whare, "COMPANY_NAME = '".$lr_data_rec['ENDUSER_COMPANY_NAME']."'");
					array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_whare);
					$lc_master_mdl_class	= new m_user_master('Y', $lr_whare);
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

				// 依頼元担当者
				// マスターを検索し、存在を確認する
				$l_target_col			= "REQUEST_USER_NAME";
				$l_target_col_id		= "REQUEST_USER_ID";
				$l_target_col_mst_id	= "USER_ID";
				$l_target_col_name		= "依頼元担当者";
				if ($lr_data_rec[$l_target_col] != ""){
					$lc_master_mdl_class	= "";
					$lr_master_mdl			= "";

					// ユーザーマスタからユーザー名をキーに値を取得
					$lr_whare = array();
					array_push($lr_whare, "DATA_ID = '".$lr_data_rec['DATA_ID']."'");
					array_push($lr_whare, "NAME = '".$lr_data_rec[$l_target_col]."'");
					array_push($lr_whare, "COMPANY_NAME = '".$lr_data_rec['REQUEST_COMPANY_NAME']."'");
					array_push($lr_whare, "VALIDITY_FLAG = 'Y'");
					//var_dump($lr_whare);
					$lc_master_mdl_class	= new m_user_master('Y', $lr_whare);
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

				// 作業予定日
				// 開始終了両方の設定があって、作業予定開始>作業予定終了の場合はエラーとする
				$l_target_from_col			= "SCHEDULE_FROM_DATE";
				$l_target_to_col			= "SCHEDULE_TO_DATE";
				$l_target_from_name			= "作業開始予定日";
				$l_target_to_name			= "作業終了予定日";

				if (	$lr_data_rec[$l_target_from_col] != ''
					and $lr_data_rec[$l_target_to_col] != ''
					and strtotime($lr_data_rec[$l_target_from_col]) > strtotime($lr_data_rec[$l_target_to_col])
				){
					$lr_check_res[$l_key][$l_target_from_col]['STATUS']		= 2;
					$lr_check_res[$l_key][$l_target_from_col]['MESSAGE']	= "「".$l_target_to_name."」は、「".$l_target_from_name."」以降で設定して下さい。\n";
				}
			}
			if($this->debug_mode==1){
				//print var_dump($lr_check_res)."<br>";
				print("Step-checkData終了");print "<br>";
			}
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
}
?>