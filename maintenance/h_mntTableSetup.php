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
// *****************************************************************************
// クラス名：mntTableSetup
// 処理概要：明細部のHTML構築
// *****************************************************************************
class mntTableSetup{
/* =============================================================================
	変数定義
   ===========================================================================*/
	public		$l_dir_prfx;							// 当画面のDIR階層を補完するためのDIRプレフィックス
	private		$l_errmess;								// エラーメッセージ
	private		$src_record;							// ソースレコード
	private		$index_item_num;						// 見出しに使用する項目の番号(-1で見出しなし)
	private		$flag_show_search_box;					// 見出しの検索項目を作成するかのフラグ
	private		$number_to_show;						// 表示するレコードの数
	private		$record_count;							// 総レコード数
	private		$pege_count;							// 総ページ数
	private		$show_page_num;							// 表示するページ番号
	private		$start_rownum;							// 表示を開始するレコード番号
	private		$item_count;							// 1行当たりの項目数
	
/* =============================================================================
	例外定義
   ===========================================================================*/
	function expt_mntTableSetup(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }
	
/* =============================================================================
	コンストラクタ
   ===========================================================================*/
	function __construct($p_src_record){
		if(count($p_src_record) == 0 || !is_array($p_src_record)){
			// レコードが渡ってこない場合は各種数量に0を設定して終了
			// レコードの数
			$this->record_count = 0;
			
			// ページ数
			$this->pege_count = 0;
			
			// 1レコード当たりの項目数
			$this->item_count = 0;
			
			return false;
		}
		// DIRプレフィックス設定
		$this->l_dir_prfx = dirname(__FILE__)."/";
		
		// 見出しに使用する項目の番号設定
		$this->index_item_num = 1;
		
		// 見出しの検索項目を作成するかのフラグを設定
		$this->flag_show_search_box = true;
		
		// 表示を開始するレコード番号
		$this->start_rownum = 1;
		
		// 表示するレコードの数
		$this->number_to_show = 12;
		
		// レコードの数
		$this->record_count = count($p_src_record);
		
		// ページ数
		$this->pege_count = $this->getPageCount();
		
		// 1レコード当たりの項目数
		$this->item_count = count($p_src_record[1]);
		/*
		print "レコードの数：".$this->record_count."<br>";		// レコードの数
		print " ページ数：".$this->pege_count."<br>";			// ページ数
		*/
		
		// レコード配列再設定
		// 各レコードのアイテムをkeyとvalueに分けて格納
		foreach($p_src_record as $recnum => $rec){
			$l_item_cnt = 0;
			foreach($rec as $key => $value){
				$l_item_cnt++;
				$this->src_record[$recnum][$l_item_cnt]['key'] = $key;
				$this->src_record[$recnum][$l_item_cnt]['value'] = $value;
			}
		}
		//print_r($this->src_record);
	}
	
/* =============================================================================
	ページ数の算出
   ===========================================================================*/
	function getPageCount(){
		// 表示レコード0件の場合は終了
		if($this->number_to_show == 0){
			return 0;
		}
		// レコード数を表示数で割るあまりが出たら+1
		$l_pege_count = floor($this->record_count / $this->number_to_show);
		if($this->record_count % $this->number_to_show > 0){
			$l_pege_count++;
		}
		
		return $l_pege_count;
	}
	
/* =============================================================================
	見出しに使用する項目の番号設定
   ===========================================================================*/
	function setIndexItemNum($p_value){
		$this->index_item_num = $p_value;
	}
	
/* =============================================================================
	見出しの検索項目を作成するかのフラグを設定
   ===========================================================================*/
	function setFlagShowSearchBox($p_value){
		if($p_value){
			$this->flag_show_search_box = true;
		}else{
			$this->flag_show_search_box = false;
		}
	}

/* =============================================================================
	表示を開始するページを設定
	引数
			$p_value				ページ番号
   ===========================================================================*/
	function setStartPageNum($p_value){
		$l_page_start_rec	= "";
		$l_page_num			= "";
		
		// 1未満の場合、最大数を超える場合は終了
		if($p_value < 1 || $p_value > $this->pege_count){
			return false;
		}
		
		// ページ設定とページ開始レコード算出
		$l_page_start_rec	= (($p_value - 1) * $this->number_to_show) + 1;
		
		$this->start_rownum		= $l_page_start_rec;
		$this->show_page_num	= $p_value;
		/*
		print "start_rownum：".$l_page_start_rec."<br>";
		print "show_page_num：".$p_value."<br>";
		*/
	}
	
/* =============================================================================
	表示するレコードの数を設定
   ===========================================================================*/
	function setNumberToShow($p_value){
		$this->number_to_show = $p_value;
		// 使用箇所の再計算
		// ページ数
		$this->pege_count = $this->getPageCount();
	}

/* =============================================================================
	レコード数の取得
   ===========================================================================*/
	function getRecordCount(){
		return $this->record_count;
	}

/* =============================================================================
	1レコード当たりの項目数取得
   ===========================================================================*/
	function getItemCount(){
		return $this->item_count;
	}

/* =============================================================================
	ページ操作項目作成
	ページ数/総ページ数、レコード数、前/次ボタンの表示
   ===========================================================================*/
	function makePageOpeItemHtml(){
		$l_return_html = "";
		
		// TABLE構築
		$l_return_html .= "<table id=\"id_table_po_right\">\n";
		$l_return_html .= "<tr id=\"id_tr_po_right\">\n";
		
		if($this->record_count > 0){
			// レコードがある場合
			// ページ表示部
			$l_return_html .= "<td id=\"id_td_po_right_sp\">\n";
//			$l_return_html .= "<span id=\"id_span_po_page\" class=\"c_span_pageope\">".$this->show_page_num."</span>";
//			$l_return_html .= "<span class=\"c_span_pageope\">/</span>";
//			$l_return_html .= "<span id=\"id_span_po_pagecnt\" class=\"c_span_pageope\">".$this->pege_count."</span>";
//			$l_return_html .= "<span class=\"c_span_pageope\"> ページ</span>";
			$l_return_html .= "<select id=\"id_sel_po_page\" size=\"1\">\n";
			
			for ($i_cnt = 1; $i_cnt <= $this->pege_count; $i_cnt++){
				if ($i_cnt == $this->show_page_num){
					$l_return_html .= "<option value=\"".$i_cnt."\" selected=\"selected\">".$i_cnt." / ".$this->pege_count." ページ"."</option>\n";
				}else{
					$l_return_html .= "<option value=\"".$i_cnt."\">".$i_cnt." / ".$this->pege_count." ページ"."</option>\n";
				}
			}
			
			$l_return_html .= "</select>\n";
			$l_return_html .= "</td>\n";
			
			// レコード数表示部
			$l_return_html .= "<td id=\"id_td_po_right_rc\">\n";
			$l_return_html .= "<span id=\"id_span_po_rec\" class=\"c_span_pageope\">";
			$l_return_html .= $this->record_count." レコードが該当しました";
			$l_return_html .= "</span>";
			$l_return_html .= "</td>\n";
			
			// ページ操作ボタン部
			$l_return_html .= "<td id=\"id_td_po_right_bt\">\n";
			$l_return_html .= "<input type=\"button\" id=\"id_btn_po_prev\" class=\"c_btn_main_nomal\" value=\"前のページ\" />";
			$l_return_html .= "<input type=\"button\" id=\"id_btn_po_next\" class=\"c_btn_main_nomal\" value=\"次のページ\" />";
			$l_return_html .= "</td>\n";
		}else{
			// ページ表示部
			$l_return_html .= "<td id=\"id_td_po_right_sp\">\n";
			$l_return_html .= "<span id=\"id_span_po_page\" class=\"c_span_pageope\">";
			$l_return_html .= "該当するレコードがありませんでした。";
			$l_return_html .= "</span>";
			$l_return_html .= "</td>\n";
		}
		
		$l_return_html .= "</tr>\n";
		$l_return_html .= "</table>\n";
		
		return $l_return_html;
	}

/* =============================================================================
	ヘッダー部インデックス項目作成
	index_item_numに指定された番号の項目から見出しと検索用テキストBOXを作成
   ===========================================================================*/
	function makeHDIndexHtml(){
		if($this->record_count == 0){
			// レコードなしの場合は終了
			return false;
		}
		if($this->index_item_num == -1){
			// index_item_numが-1の場合は終了
			return true;
		}
		
		$l_return_html = "";
		$l_item_num = 1;					// 画面上の項目番号
		$l_rec_num = 1;						// レコード番号
		
		// TABLE構築
		$l_return_html .= "<table id=\"id_table_hd_left\">\n";
		$l_return_html .= "<tr id=\"id_tr_hd_left\">\n";
		
		// ラジオボタン用の空白
		$l_return_html .= "<td class=\"c_td_hd_rdb\">";
		$l_return_html .= "<input type=\"text\" id=\"id_txt_hd_rdb\" class=\"c_cap_hd_rdb\" readOnly=\"true\" />";
		$l_return_html .= "</td>";
		
		// 見出し項目
		$l_return_html .= "<td id=\"id_td_hd_left\" class=\"c_td_hd\">\n";
		$l_return_html .= "<input type=\"text\" id=\"id_txt_hd". $l_item_num. "\" class=\"c_cap_hd\" readOnly=\"true\" ";
		$l_return_html .= "value=\"". htmlspecialchars($this->src_record[$l_rec_num][$this->index_item_num]['key']). "\" />\n";
		
		if($this->flag_show_search_box){
			// 見出しの検索項目
			$l_return_html .= "<br>\n";
			$l_return_html .= "<input type=\"text\" id=\"id_txt_hd". $l_item_num. "\" class=\"c_txt_hd\" />";
		}
		
		$l_return_html .= "</td>\n";
		$l_return_html .= "</tr>\n";
		$l_return_html .= "</table>\n";
		
		return $l_return_html;
	}

/* =============================================================================
	ヘッダー部その他項目作成
	index_item_numに指定された番号以外の項目から見出しと検索用テキストBOXを作成
   ===========================================================================*/
	function makeHDOtherHtml(){
		if($this->record_count == 0){
			// レコードなしの場合は終了
			return false;
		}
		
		$l_return_html = "";
		$l_item_num = 2;					// 画面上の項目番号
		$l_rec_num = 1;						// レコード番号
		
		// TABLE構築
		$l_return_html .= "<table id=\"id_table_hd_right\">\n";
		
		$l_return_html .= "<tr id=\"id_tr_hd_right\">\n";
		
		for($i=1; $i<=count($this->src_record[$l_rec_num]); $i++){
			if($i != $this->index_item_num){					// インデックスに指定された番号は除外
				$l_return_html .= "<td id=\"id_td_hd_right\" class=\"c_td_hd\">\n";
				$l_return_html .= "<input type=\"text\" id=\"id_txt_hd". $l_item_num. "\" class=\"c_cap_hd\" readOnly=\"true\" ";
				$l_return_html .= "value=\"". htmlspecialchars($this->src_record[$l_rec_num][$i]['key']). "\" />\n";
				if($this->flag_show_search_box){
					// 見出しの検索項目
					$l_return_html .= "<br>\n";
					$l_return_html .= "<input type=\"text\" id=\"id_txt_hd". $l_item_num. "\" class=\"c_txt_hd\" />";
				}
				
				$l_return_html .= "</td>\n";
				
				// 項目番号インクリメント
				$l_item_num++;
			}
		}
		
		$l_return_html .= "</tr>\n";
		
		$l_return_html .= "</table>\n";
		
		return $l_return_html;
	}
	
/* =============================================================================
	明細部インデックス項目作成
	index_item_numに指定された番号の項目から明細表を作成
   ===========================================================================*/
	function makeDTLIndexHtml(){
		if($this->record_count == 0){
			// レコードなしの場合は終了
			return false;
		}
		if($this->index_item_num == -1){
			// index_item_numが-1の場合は終了
			return true;
		}
		
		$l_return_html = "";
		$l_row_num = 1;							// 行番号
		$l_item_num = 1;						// 画面上の項目番号
		$l_rec_cnt = $this->record_count;		// レコード数
		
		if($l_rec_cnt < $this->start_rownum){
			// 表示開始行が、レコード数より大きい場合は終了
			return false;
		}
		
		// TABLE構築
		$l_return_html .= "<table id=\"id_table_dtl_left\">\n";
		
		for($i = $this->start_rownum ;($i <= $l_rec_cnt && $i <= $this->start_rownum + $this->number_to_show - 1) ;$i++){
			$l_return_html .= "<tr id=\"id_tr_dtl_left". $l_row_num ."\" class=\"c_tr_dtl_left\">\n";

			// ラジオボタン
			$l_return_html .= "<td id=\"id_td_dtl_rdb". $l_row_num . $l_item_num ."\" ";
			// 偶数行と奇数行でクラスを分ける
			if($i % 2 == 0){
				// 偶数行
				$l_return_html .= "class=\"c_td_dtl_rdb_even\">\n";
			}else{
				// 奇数行
				$l_return_html .= "class=\"c_td_dtl_rdb_odd\">\n";
			}
			$l_return_html .= "<input type=\"radio\" name=\"nm_td_dtl_rdb\" id=\"id_ipt_dtl_rdb". $l_row_num . $l_item_num ."\" value=\"vl_ipt_dtl_rdb". $l_row_num . $l_item_num ."\" />";
			$l_return_html .= "</td>";
			
			// 見出し項目
			$l_return_html .= "<td id=\"id_td_dtl_left". $l_row_num . $l_item_num ."\" ";
			// 偶数行と奇数行でクラスを分ける
			if($i % 2 == 0){
				// 偶数行
				$l_return_html .= "class=\"c_td_dtl_even\">\n";
			}else{
				// 奇数行
				$l_return_html .= "class=\"c_td_dtl_odd\">\n";
			}
			$l_return_html .= "<input type=\"text\" id=\"id_txt_dtl". $l_row_num . $l_item_num ."\" class=\"c_txt_dtl\" readOnly=\"true\" ";
			$l_return_html .= "value=\"". htmlspecialchars($this->src_record[$i][$this->index_item_num]['value']). "\" />\n";
			$l_return_html .= "</td>\n";
			
			$l_return_html .= "</tr>\n";
			
			$l_row_num++;
		}
		
		$l_return_html .= "</table>\n";
		
		return $l_return_html;
	}
	
/* =============================================================================
	明細部その他項目作成
	index_item_numに指定された番号以外の項目から明細表を作成
   ===========================================================================*/
	function makeDTLOtherHtml(){
		if($this->record_count == 0){
			// レコードなしの場合は終了
			return false;
		}
		
		$l_return_html = "";
		$l_row_num = 1;							// 行番号
		$l_rec_cnt = $this->record_count;		// レコード数
		
		
		if($l_rec_cnt < $this->start_rownum){
			// 表示開始行が、レコード数より大きい場合は終了
			return false;
		}
		
		// TABLE構築
		$l_return_html .= "<table id=\"id_table_dtl_right\">\n";
		
		for($i = $this->start_rownum ;($i <= $l_rec_cnt && $i <= $this->start_rownum + $this->number_to_show - 1) ;$i++){
			$l_return_html .= "<tr id=\"id_tr_dtl_right". $l_row_num ."\" class=\"c_tr_dtl_right\">\n";
			
			$l_item_num = 2;						// 画面上の項目番号開始番号
			
			foreach($this->src_record[$i] as $item_num => $item_rec){
				if($item_num != $this->index_item_num){					// インデックスに指定された番号は除外
					$l_return_html .= "<td id=\"id_td_dtl_right". $l_row_num . $l_item_num ."\" ";
					// 偶数行と奇数行でクラスを分ける
					if($i % 2 == 0){
						// 偶数行
						$l_return_html .= "class=\"c_td_dtl_even\">\n";
					}else{
						// 奇数行
						$l_return_html .= "class=\"c_td_dtl_odd\">\n";
					}
					$l_return_html .= "<input type=\"text\" id=\"id_txt_dtl". $l_row_num . $l_item_num ."\" class=\"c_txt_dtl\" readOnly=\"true\" ";
					$l_return_html .= "value=\"". htmlspecialchars($item_rec['value']). "\" />\n";
					$l_return_html .= "</td>\n";
					
					$l_item_num++;
				}
			}
			
			$l_return_html .= "</tr>\n";
			$l_row_num++;
		}
		
		$l_return_html .= "</table>\n";
		
		return $l_return_html;
	}
}
?>