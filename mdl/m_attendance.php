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
class m_attendance extends ModelCommon{
/*******************************************************************************
	クラス名：m_attendance.php
	処理概要：勤務表モデル
*******************************************************************************/
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス
	
	private $set_view_name	= 'ATTENDANCE_SHEET_V';		// ビュー名
	private $set_table_name	= '';						// テーブル名
	//※table_nameはModelCommonで定義されています
	
	private $primary_key_col = 'WORK_STAFF_ID';	// 主キーの項目
	
	public	$htmlspchar_flag = 'Y';			// htmlspecialchars適用フラグ
	public	$shortname_size  = 10;			// 短縮名の文字サイズ
	public	$shortname_size_REMARKS = 10;	// 備考用短縮名の文字サイズ
	
	private $debug_mode = 0;
/*============================================================================
	コンストラクタ
	引数:
				$p_rec_get_flag				レコード取得フラグ(Y:取得,N:取得しない)
				$pr_columns					取得するカラムの配列
				$pr_where					where句配列
				$pr_ordery_by				order by句配列
				$pr_group_by				group by句配列
  ============================================================================*/
	function __construct($p_rec_get_flag = 'N', $pr_columns = '', $pr_where = '', $pr_ordery_by = '', $pr_group_by = ''){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct($this->set_view_name);		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__construct継承元のコンストラクタを起動");print "<br>";}
		
		// カラム情報を取得
		require_once('../mdl/m_column_info.php');
		$this->c_column_info = new ColumnInfo($this->set_view_name);		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__constructカラム情報を取得");print "<br>";}
		
		// レコード取得用配列セット
		$this->r_where			= $pr_where;
		$this->r_ordery_by		= $pr_ordery_by;
		$this->r_group_by		= $pr_group_by;
		
		// 取得カラムセット
		if(count($pr_columns) > 0){
			$this->setSelectColumns($pr_columns);
		}
		
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
		
		//var_dump($l_where_phrase);
		
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
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = htmlspecialchars($this->c_column_info->getColumnNameJ($item_key));
							}
							// 作業名、作業者会社名、作業者名の表示用の短縮名を作成する
							if($item_key=="WORK_NAME" || $item_key=="WORK_USER_COMPANY_NAME" || $item_key=="WORK_USER_NAME"){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}else if($item_key=="ES_REMARKS" || $item_key=="OTHER_REMARKS"){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size_REMARKS){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size_REMARKS)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}
							
							// 時刻はhh:mm形式の値を作成する
							if(	$item_key=="DEFAULT_ENTERING_SCHEDULE_TIMET"
								or $item_key=="DEFAULT_LEAVE_SCHEDULE_TIMET"
								or $item_key=="AGGREGATE_TIMET"
								or $item_key=="DISPATCH_SCHEDULE_TIMET"
								or $item_key=="DISPATCH_STAFF_TIMET"
								or $item_key=="ENTERING_SCHEDULE_TIMET"
								or $item_key=="ENTERING_STAFF_TIMET"
								or $item_key=="ENTERING_MANAGE_TIMET"
								or $item_key=="ENTERING_TIMET"
								or $item_key=="LEAVE_SCHEDULE_TIMET"
								or $item_key=="LEAVE_STAFF_TIMET"
								or $item_key=="LEAVE_MANAGE_TIMET"
								or $item_key=="LEAVE_TIMET"
							){
								if($item_value != "" and !is_null($item_value)){
									$lr_view_rec[$l_loop_cnt][$item_key."_HI"] = $this->setTime($lr_view_rec[$l_loop_cnt][$item_key], $lr_view_rec[$l_loop_cnt]["WORK_DATE"]);
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_HI"] = "";
								}
							}
						}else{
							// htmlspecialchars非適用
							$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
							// レコード番号1のときのみカラムコメント一覧作成
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = $this->c_column_info->getColumnNameJ($item_key);
							}
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
}
?>