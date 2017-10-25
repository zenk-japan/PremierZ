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
class m_value_list_defines extends ModelCommon{
// *****************************************************************************
// クラス名：m_value_list_defines
// 処理概要：値リスト定義モデル
// *****************************************************************************
	private	$debug_mode	= 0;							// デバッグモード(1:デバッグ)
	private $l_select_item			= "SELECT_PHRASE";
	private $l_option1_item			= "OPTION_WHERE_1";
	private $l_option2_item			= "OPTION_WHERE_2";
	private $l_option3_item			= "OPTION_WHERE_3";
	private $l_option4_item			= "OPTION_WHERE_4";
	private $l_option5_item			= "OPTION_WHERE_5";
	private $l_option6_item			= "OPTION_WHERE_6";
	private $l_option7_item			= "OPTION_WHERE_7";
	private $l_option8_item			= "OPTION_WHERE_8";
	private $l_option9_item			= "OPTION_WHERE_9";
	private $l_option10_item		= "OPTION_WHERE_10";
	private $l_order_by_item		= "ORDER_BY_PHRASE";
	private $l_group_by_item		= "GROUP_BY_PHRASE";
	private $l_value_dest_item		= "VALUE_DEST_ITEM_ID";
	private $l_id_dest_item			= "ID_DEST_ITEM_ID";
	private $l_data_id_item			= "DATA_ID";
	private $l_use_page_item		= "USE_PAGE";
	private $l_use_item_item		= "USE_ITEM";
	private $l_validity_flag_item	= "VALIDITY_FLAG";
	
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	
	private $value_set_item_id;				// 値を返す項目ID
	private $id_set_item_id;				// IDを返す項目ID
	private $r_select_phrase;				// SELECT文用の配列
/*==============================================================================
	コンストラクタ
	引数:
  ==============================================================================*/
	function __construct(){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct("VALUE_LIST_DEFINES_V");						// ビュー名を指定
	}
	
/*==============================================================================
	値リスト定義取得
	処理概要：値リスト定義を検索し値リスト情報を取得する
			
	引数:
			$p_data_id			データID
			$p_use_page			リストを使っているページ
			$p_use_item			リストを使っている項目
	戻り値:
			$rr_return_rec		SELECT文と追加WHERE句の連想配列
  ==============================================================================*/
	function getSelectPhrase($p_data_id, $p_use_page, $p_use_item){
		$rr_return_rec = array();						// 戻り値の配列
		$lr_condition = array();						// レコード取得用の条件配列
		/*
		print "p_data_id:".$p_data_id."<br>";
		print "p_use_page:".$p_use_page."<br>";
		print "p_use_item:".$p_use_item."<br>";
		*/
		// 条件配列セット
		$lr_condition = array(
						$this->l_data_id_item		=> $p_data_id,
						$this->l_use_page_item		=> $p_use_page,
						$this->l_use_item_item		=> $p_use_item,
						$this->l_validity_flag_item	=> "Y"
						);
		
		// 条件セット
		$lr_cond_result = $this->setCondition($lr_condition);
		if($this->debug_mode == 1) echo "cond<BR>";
		if($this->debug_mode == 1) print_r($lr_condition);
		
		// レコード取得
		$l_result_rec = $this->getRecord();
		if($this->debug_mode == 1) print "record count -> ".count($l_result_rec)."<BR>";
		if(count($l_result_rec)==0){
			// レコードが取得できなかった場合はそのままreturn
			return;
		}
		
		// 必要項目だけ抜き出してSELECT文用配列にセット
		foreach($l_result_rec as $recnum => $lr_value){
			foreach($lr_value as $key => $value){
				switch($key){
					case $this->l_select_item:
						$rr_return_rec[$this->l_select_item] = $value;
						break;
					case $this->l_option1_item:
						$rr_return_rec[$this->l_option1_item] = $value;
						break;
					case $this->l_option2_item:
						$rr_return_rec[$this->l_option2_item] = $value;
						break;
					case $this->l_option3_item:
						$rr_return_rec[$this->l_option3_item] = $value;
						break;
					case $this->l_option4_item:
						$rr_return_rec[$this->l_option4_item] = $value;
						break;
					case $this->l_option5_item:
						$rr_return_rec[$this->l_option5_item] = $value;
						break;
					case $this->l_option6_item:
						$rr_return_rec[$this->l_option6_item] = $value;
						break;
					case $this->l_option7_item:
						$rr_return_rec[$this->l_option7_item] = $value;
						break;
					case $this->l_option8_item:
						$rr_return_rec[$this->l_option8_item] = $value;
						break;
					case $this->l_option9_item:
						$rr_return_rec[$this->l_option9_item] = $value;
						break;
					case $this->l_option10_item:
						$rr_return_rec[$this->l_option10_item] = $value;
						break;
					case $this->l_order_by_item:
						$rr_return_rec[$this->l_order_by_item] = $value;
						break;
					case $this->l_group_by_item:
						$rr_return_rec[$this->l_group_by_item] = $value;
						break;
					case $this->l_value_dest_item:
						$this->value_set_item_id = $value;
						break;
					case $this->l_id_dest_item:
						$this->id_set_item_id = $value;
						break;
				}
			}
		}
		if($this->debug_mode == 1) print_r($rr_return_rec);
		
		// 条件初期化
		$this->resetCondition();
		
		// クラス変数にセット
		$this->r_select_phrase = $rr_return_rec;
		
		return $rr_return_rec;
	}
/*==============================================================================
	WHERE句構築
	$で囲まれた部分をGET引数の値に置換する
	引数:	
			$p_org_phrase							DBから取得したSELECT文データ
			$pr_post								postされた値の配列
  ==============================================================================*/
	function makeWherePhrase($p_org_phrase, $pr_post){
		$l_return_phrase	= "";
		$l_find_pos			= array();
		$l_rpls_string		= array();
		$l_split_char		= "$";						// 分割用の正規表現文字列
		
		// POST値が無い場合は終了
		if(count($pr_post) == 0){
			return $l_return_phrase;
		}
		
		// $で囲まれた部分をPOST引数に置き換える
		$l_org_phrase = trim($p_org_phrase);
		$l_len        = strlen($l_org_phrase);
		
		// 文字列を順に検索し、$の個所を配列にため込む
		$l_cnt = 0;
		for($i=0; $i<=$l_len; $i++){
			$l_pos = strpos($l_org_phrase, $l_split_char, $i);
			if(is_numeric($l_pos)){
				if(count($l_find_pos) > 0){
					// すでに登録がある場合は、前と異なる場合のみ登録
					if($l_find_pos[$l_cnt] != $l_pos){
						$l_cnt++;
						$l_find_pos[$l_cnt] = $l_pos;
					}
				}else{
					// まだ登録がない場合はそのまま登録
					$l_find_pos[$l_cnt] = $l_pos;
				}
			}
		}
		
		if(count($l_find_pos) == 0){
		// 1つも$が見つからない場合は元の文字列をそのままreturn
			return $l_org_phrase;
		}else if(count($l_find_pos) % 2 == 1){
		// $が奇数個の場合はエラー
			$this->l_errmess = "追加WHERE句の設定が不正です。<BR>";
			throw new Exception('New Excption');
		}
		
		//print "l_org_phrase:".$l_org_phrase."<br>";
		
		// $間の文字列とそれ以外の部分の文字列を切り分け、配列に格納する
		$l_new_phrase = $l_org_phrase;
		for($i=0;$i<count($l_find_pos);$i=$i+2){
			// $間の文字列
			$l_rpls_from_str = substr($l_org_phrase, $l_find_pos[$i] + 1, $l_find_pos[$i+1] - $l_find_pos[$i] - 1);
			
			// POST引数の検索
			if (array_key_exists($l_rpls_from_str, $pr_post)) {
				// キーが存在したら値を取得
				$l_new_phrase = $pr_post[$l_rpls_from_str];
				if($this->debug_mode == 1) print "GET引数:".$l_new_phrase."<BR>";
				if($l_new_phrase==''){
					// POST引数の値が空欄の場合は、引用符にする
					$l_new_phrase = "''";
				}else{
					// POST引数の値が空欄以外の場合は、引用符を前後につける
					$l_new_phrase = "'".$l_new_phrase."'";
				}
				
				// 配列に格納
				$l_rpls_string[$l_split_char.$l_rpls_from_str.$l_split_char] = $l_new_phrase;
				
			}else{
				// キーが存在しない場合は終了
				return $l_return_phrase;
			}
		}
		
		// 置換用配列を使用して元のWHERE句を置換する
		$l_return_phrase = $l_org_phrase;
		foreach($l_rpls_string as $key => $value){
			$l_return_phrase = str_replace($key, $value, $l_return_phrase);
		}
		
		if($this->debug_mode == 1) print $l_return_phrase." at=>".basename(__FILE__)."<BR>";
		if($this->debug_mode == 1) print "STEP-囲まれた部分をGET引数に置き換え"." at=>".basename(__FILE__)."<BR>";
		
		return $l_return_phrase;
    }
/*==============================================================================
	SELECT文構築
	引数:	
			$pr_value								DBから取得したSELECT文データ
			$pr_post								postされた値の配列
  ==============================================================================*/
	function buildSelectPhrase($pr_value, $pr_post){
		$l_select_phrase = "";
		
		if(count($pr_value) > 0){
			foreach($pr_value as $key => $value){
				// OPTION_WHEREの項目を変換
				if(substr($key, 0, 12)=="OPTION_WHERE"){
					// 追加WHEREは値があれば$間を引数に変換し、ANDをつけて結合
					if(!is_null($value) && $value != ''){
						$l_option_where = $this->makeWherePhrase($value, $pr_post);
						if($l_option_where != ''){
							$l_select_phrase .= "AND ".$l_option_where." ";
						}
					}
				}else if($key==$this->l_select_item){
					// SELECT句はそのまま結合
					$l_select_phrase .= $value." ";
				}
				/*
				else if($key=="ORDER_BY_PHRASE"){
					// ORDER BY句は「ORDER BY」をつけて結合
					if($value != ''){
						$l_select_phrase .= "ORDER BY ".$value." ";
					}
				}
				*/
			}
			// Group By があれば結合
			if($pr_value[$this->l_group_by_item] != ''){
				$l_select_phrase .= "GROUP BY ".$value." ";
			}
			
			// Order By があれば結合
			if($pr_value[$this->l_order_by_item] != ''){
				$l_select_phrase .= "ORDER BY ".$value." ";
			}
		}
		
		return $l_select_phrase;
	}
/*==============================================================================
	値リスト抽出
	引数:	
			$p_select_phrase						再構築したSELECT文
  ==============================================================================*/
	function receiveValueListRecord($p_select_phrase){
		$l_ar_retrec = "";
		
		$lr_result_rec		= "";
		$lr_view_rec		= array();
		$lr_comment_rec		= array();
		
		// DB接続
		require_once('../lib/ConnectDB.php');
		//$mdb = getConnection();
		$mdb = getMysqlConnection();
				
		//print $p_select_phrase;
				
		$l_ar_retrec = getRowWithRownum($mdb, $p_select_phrase);
		
		/*
		print "<pre>";
		var_dump($l_ar_retrec);
		print "</pre>";
		*/
		// レコードが返ってきた場合は戻り値をセットする
		if(count($l_ar_retrec) > 0){
			$l_loop_cnt = 0;
			foreach($l_ar_retrec as $key => $value){
				$l_loop_cnt++;
				foreach($value as $item_key => $item_value){
					if(preg_match("/^[0-9]+$/",$item_key) ){
						// 配列キーが数値の部分は無視
					} else {
						//print "item_key:".$item_key." item_value:".$item_value."<br>";
						if($item_value != ''){
							$lr_view_rec[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);		// レコードの先頭番号は1
						}else{
							// 空の項目は不正な値がセットされてしまう為、htmlspecialcharsを適用しない
							$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
						}
						// レコード番号1のときのみカラムコメント一覧作成
						if($l_loop_cnt == 1){
							$lr_comment_rec[$item_key] = htmlspecialchars($item_key);
						}
					}
				}
			}
		}
		
		// レコードをセット
		$this->r_view_rec		= $lr_view_rec;
		$this->r_col_name		= $lr_comment_rec;
		
		if($this->debug_mode==1){print("Step-queryDBRecordレコードをセット");print "<br>";}
	}
/*============================================================================
	DATA_ID一覧取得
	処理概要：値リスト定義で使用されているDATA_IDの一覧を戻す
				
	戻り値:
			$rr_return_rec		DATA_IDの配列
  ============================================================================*/
	function getDataId(){
		$rr_return_rec = array();
		
		// group byとorder byの設定
		$this->setGroupbyPhrase(array("DATA_ID"));
		$this->setOrderbyPhrase(array("DATA_ID"));
		
		// レコード取得
		$lr_qresult = $this->getRecord();
		
		$l_reccnt = 0;
		foreach($lr_qresult as $key => $value){
			$rr_return_rec[$l_reccnt] = $value["DATA_ID"];
			$l_reccnt++;
		}
		
		// 条件初期化
		$this->resetCondition();
		
		return $rr_return_rec;
	}
/*============================================================================
	値リスト定義全取得
	処理概要：値リスト定義を検索し有効な値を全て取得する
	引数:
			$p_data_id			データID
			$p_use_page			使用ページ
			$p_define_id		定義ID
			$p_include_invalid	無効値取得フラグ(Y:取得、N:除外)
  ============================================================================*/
	function getValueListDefineAll($p_data_id = '', $p_use_page = '', $p_define_id = '', $p_include_invalid = 'Y'){
		//print "p_data_id = ".$p_data_id." p_use_page = ".$p_use_page." p_define_id = ".$p_define_id." p_include_invalid = ".$p_include_invalid."<br>";
		//$this->resetCondition();
		$l_return_value	= array();
		$l_result_rec	= "";
		$l_ar_condition	= "";
		$l_cond_ret		= "";
		
		// 並べ替え
		$this->setOrderbyPhrase(array("USE_PAGE", "USE_ITEM"));
		
		// 条件設定
		// DATA_ID
		if(trim($p_data_id) != ''){
			$l_ar_condition["DATA_ID"]	= $p_data_id;
		}
		
		// ID
		if(trim($p_define_id) != ''){
			$l_ar_condition["DEFINE_ID"]	= $p_define_id;
		}
		
		// コードセット
		if(trim($p_use_page) != ''){
			$l_ar_condition["USE_PAGE"]	= "%".$p_use_page."%";
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


/*============================================================================
	削除処理
	処理概要：引数で指定されたコードIDのデータを削除する
				
	引数:
			$p_define_id		定義ID
			$p_user_id			ユーザーID
  ============================================================================*/
	function deleteRecord($p_define_id, $p_user_id){
		
		// SQL組み立て
		$l_del_sql  = "delete from VALUE_LIST_DEFINES ";
		$l_del_sql .= "where DEFINE_ID = ".$p_define_id." ";
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_del_sql);
		
		return $l_retcode;
	}
/*============================================================================
	引用符エスケープ処理
	処理概要：引数で指定されたテキスト内の引用符('")に\をつけてエスケープする
				
	引数:
			$p_text				変換元文字列
  ============================================================================*/
	function escapeQuot($p_text){
		$l_return_value = str_replace('\'', '\\\'', $p_text);
		$l_return_value = str_replace("\"", "\\\"", $l_return_value);
		$l_return_value = str_replace("\$", "\\\$", $l_return_value);
		
		return $l_return_value;
	}
/*============================================================================
	新規登録処理
	処理概要：引数で指定された値でINSERT処理を行う
				
	引数:
			$p_user_id			ユーザーID
			$pr_value			各項目値
  ============================================================================*/
	function insertRecord($p_user_id, $pr_value){
		// SQL組み立て
		$l_sql  = "insert into `VALUE_LIST_DEFINES` ";
		$l_sql .= "(";
		$l_sql .= "DATA_ID,";
		//$l_sql .= "'DEFINE_ID',";
		$l_sql .= "DEFINE_CODE,";
		$l_sql .= "USE_PAGE,";
		$l_sql .= "USE_ITEM,";
		$l_sql .= "SELECT_PHRASE,";
		$l_sql .= "OPTION_WHERE_1,";
		$l_sql .= "OPTION_WHERE_2,";
		$l_sql .= "OPTION_WHERE_3,";
		$l_sql .= "OPTION_WHERE_4,";
		$l_sql .= "OPTION_WHERE_5,";
		$l_sql .= "OPTION_WHERE_6,";
		$l_sql .= "OPTION_WHERE_7,";
		$l_sql .= "OPTION_WHERE_8,";
		$l_sql .= "OPTION_WHERE_9,";
		$l_sql .= "OPTION_WHERE_10,";
		$l_sql .= "GROUP_BY_PHRASE,";
		$l_sql .= "ORDER_BY_PHRASE,";
		$l_sql .= "VALUE_DEST_ITEM_ID,";
		$l_sql .= "ID_DEST_ITEM_ID,";
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
		//$l_sql .= "'',";										// DEFINE_ID
		$l_sql .= "'".$pr_value["DEFINE_CODE"]."',";			// DEFINE_CODE
		$l_sql .= "'".$pr_value["USE_PAGE"]."',";				// USE_PAGE
		$l_sql .= "'".$pr_value["USE_ITEM"]."',";				// USE_ITEM
		$l_sql .= "'".$this->escapeQuot($pr_value["SELECT_PHRASE"])."',";			// SELECT_PHRASE
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_1"])."',";			// OPTION_WHERE_1
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_2"])."',";			// OPTION_WHERE_2
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_3"])."',";			// OPTION_WHERE_3
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_4"])."',";			// OPTION_WHERE_4
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_5"])."',";			// OPTION_WHERE_5
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_6"])."',";			// OPTION_WHERE_6
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_7"])."',";			// OPTION_WHERE_7
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_8"])."',";			// OPTION_WHERE_8
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_9"])."',";			// OPTION_WHERE_9
		$l_sql .= "'".$this->escapeQuot($pr_value["OPTION_WHERE_10"])."',";			// OPTION_WHERE_10
		$l_sql .= "'".$this->escapeQuot($pr_value["GROUP_BY_PHRASE"])."',";			// GROUP_BY_PHRASE
		$l_sql .= "'".$this->escapeQuot($pr_value["ORDER_BY_PHRASE"])."',";			// ORDER_BY_PHRASE
		$l_sql .= "'".$this->escapeQuot($pr_value["VALUE_DEST_ITEM_ID"])."',";		// VALUE_DEST_ITEM_ID
		$l_sql .= "'".$this->escapeQuot($pr_value["ID_DEST_ITEM_ID"])."',";			// ID_DEST_ITEM_ID
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
		$l_sql .= "'".$pr_value["VALIDITY_FLAG"]."',";			// VALIDITY_FLAG
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

/*============================================================================
	更新処理
	処理概要：引数で指定された値でUPDATE処理を行う
				
	引数:
			$p_user_id			ユーザーID
			$pr_value			各項目値
  ============================================================================*/
	function updateRecord($p_user_id, $pr_value){
		// SQL組み立て
		$l_sql  = "update `VALUE_LIST_DEFINES` ";
		$l_sql .= "set ";
		$l_sql .= "DATA_ID = '".$pr_value["DATA_ID"]."',";											// DATA_ID
		$l_sql .= "DEFINE_CODE = '".$pr_value["DEFINE_CODE"]."',";									// DEFINE_CODE
		$l_sql .= "USE_PAGE = '".$pr_value["USE_PAGE"]."',";										// USE_PAGE
		$l_sql .= "USE_ITEM = '".$pr_value["USE_ITEM"]."',";										// USE_ITEM
		$l_sql .= "SELECT_PHRASE = '".$this->escapeQuot($pr_value["SELECT_PHRASE"])."',";			// SELECT_PHRASE
		$l_sql .= "OPTION_WHERE_1 = '".$this->escapeQuot($pr_value["OPTION_WHERE_1"])."',";			// OPTION_WHERE_1
		$l_sql .= "OPTION_WHERE_2 = '".$this->escapeQuot($pr_value["OPTION_WHERE_2"])."',";			// OPTION_WHERE_2
		$l_sql .= "OPTION_WHERE_3 = '".$this->escapeQuot($pr_value["OPTION_WHERE_3"])."',";			// OPTION_WHERE_3
		$l_sql .= "OPTION_WHERE_4 = '".$this->escapeQuot($pr_value["OPTION_WHERE_4"])."',";			// OPTION_WHERE_4
		$l_sql .= "OPTION_WHERE_5 = '".$this->escapeQuot($pr_value["OPTION_WHERE_5"])."',";			// OPTION_WHERE_5
		$l_sql .= "OPTION_WHERE_6 = '".$this->escapeQuot($pr_value["OPTION_WHERE_6"])."',";			// OPTION_WHERE_6
		$l_sql .= "OPTION_WHERE_7 = '".$this->escapeQuot($pr_value["OPTION_WHERE_7"])."',";			// OPTION_WHERE_7
		$l_sql .= "OPTION_WHERE_8 = '".$this->escapeQuot($pr_value["OPTION_WHERE_8"])."',";			// OPTION_WHERE_8
		$l_sql .= "OPTION_WHERE_9 = '".$this->escapeQuot($pr_value["OPTION_WHERE_9"])."',";			// OPTION_WHERE_9
		$l_sql .= "OPTION_WHERE_10 = '".$this->escapeQuot($pr_value["OPTION_WHERE_10"])."',";		// OPTION_WHERE_10
		$l_sql .= "GROUP_BY_PHRASE = '".$this->escapeQuot($pr_value["GROUP_BY_PHRASE"])."',";		// GROUP_BY_PHRASE
		$l_sql .= "ORDER_BY_PHRASE = '".$this->escapeQuot($pr_value["ORDER_BY_PHRASE"])."',";		// ORDER_BY_PHRASE
		$l_sql .= "VALUE_DEST_ITEM_ID = '".$this->escapeQuot($pr_value["VALUE_DEST_ITEM_ID"])."',";	// VALUE_DEST_ITEM_ID
		$l_sql .= "ID_DEST_ITEM_ID = '".$this->escapeQuot($pr_value["ID_DEST_ITEM_ID"])."',";		// ID_DEST_ITEM_ID
		$l_sql .= "VALIDITY_FLAG = '".$pr_value["VALIDITY_FLAG"]."',";								// VALIDITY_FLAG
		$l_sql .= "LAST_UPDATE_DATET = now(),";														// LAST_UPDATE_DATET
		$l_sql .= "LAST_UPDATE_USER_ID = ".$p_user_id." ";											// LAST_UPDATE_USER_ID
		$l_sql .= "where DEFINE_ID = ".$pr_value["DEFINE_ID"];
		
		//print $l_sql;
		
		// SQL実行
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		$l_retcode = $lc_cex->CommonSilentSQL($l_sql);
		
		return $l_retcode;
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
	// SELECT文用の配列
	function getSelectPhraseRec(){
		return $this->r_select_phrase;
	}
	// 値を返す項目ID
	function getValueSetItemId(){
		return $this->value_set_item_id;
	}
	// IDを返す項目ID
	function getIdSetItemId(){
		return $this->id_set_item_id;
	}
}
?>
