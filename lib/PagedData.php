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
/*============================================================================
  ページ単位に分けられたデータ
  クラス名：PagedData
  ============================================================================*/
class PagedData {
	private	$data_rec;						// データレコード
	private	$rec_count;						// レコード数
	private	$page_count;					// 総ページ数
	private	$show_count;					// 表示数
	private	$htmlspecial_flag;				// htmlspecialchars適用フラグ
	private	$debug_mode = 0;
	
/* ==========================================================================
	例外定義
   ========================================================================== */
	function expt_PageData(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }
/*----------------------------------------------------------------------------
  コンストラクタ
  引数:				$p_data_rec				データレコード
					$p_special_flag			項目にhtmlspecialcharsを適用するか(Y:する,N:しない)
					$p_show_count			表示数
  ----------------------------------------------------------------------------*/
	function __construct($p_data_rec, $p_special_flag = 'N', $p_show_count = 5){
		// htmlspecialchars適用フラグ
		if($p_special_flag == 'Y'){
			$this->htmlspecial_flag = true;
		}else{
			$this->htmlspecial_flag = false;
		}
		
		// 表示数の初期化
		$this->show_count	= $p_show_count;
		
		// 変数の初期化
		$this->rec_count	= 0;
		$this->page_count	= 0;
		
		// レコードセット
		$this->setRecord($p_data_rec);
	}
	
/*----------------------------------------------------------------------------
  データの表示ページ数の算出
  引数:
  ----------------------------------------------------------------------------*/
	function calcPageCount($p_rec_count, $p_show_count){
		//print "p_rec_count->".$p_rec_count.":"."p_show_count->".$p_show_count."<br>";
		$l_page_count = "";
		
		if($p_rec_count == 0){
			// レコード数が0の場合は1
			$l_page_count = 1;
		}else{
			// レコード数を表示数で割るあまりが出たら+1
			$l_page_count = floor($p_rec_count / $p_show_count);
			if($p_rec_count % $p_show_count > 0){
				$l_page_count++;
			}
		}
		
		return $l_page_count;
	}
/*----------------------------------------------------------------------------
  指定ページのデータ抽出
  引数:				$p_page					ページ番号
  ----------------------------------------------------------------------------*/
	function pickPageRecord($p_page){
		$lr_return_rec = array();
		
		//print "rec_count->".$this->rec_count."<br>";
		//print "page_count->".$this->page_count."<br>";
		//print "p_page->".$p_page."<br>";
		
		// レコードがない場合
		if($this->rec_count == 0){
			return $lr_return_rec;
		}
		
		// 1ページ分しかない場合はレコードをそのまま返す
		if($this->page_count == 1){
			return $this->data_rec;
		}
		
		// ページの開始レコード番号を算出(先頭0)
		$l_start_rec_num = ($p_page - 1) * $this->show_count;
		
		// 開始レコードから表示数分を抽出
		$l_rec_num = 0;			// 入力レコード番号
		$l_rec_num_output = 0;	// 出力レコード番号
		
		foreach($this->data_rec as $l_rec){
			if($l_rec_num >= $l_start_rec_num && $l_rec_num <= $l_start_rec_num + $this->show_count - 1){
				$lr_return_rec[$l_rec_num_output] = $l_rec;
				$l_rec_num_output++;
			}
			$l_rec_num++;
		}
		
		return $lr_return_rec;
	}
/* ==========================================================================
	ページ選択用コンボボックスの作成
	引数:
			$p_page_num			選択中のページ番号
   ========================================================================== */
	function makePageSelectHtml($p_page_num){
		if($this->debug_mode==1){print("Step-makePageSelectHtml開始");print "<br>";}
		set_exception_handler(array($this, 'expt_PageData'));
		
		$l_return_html	= "";
		$l_page_count	= $this->getPageCount();

		// page_numの指定が無ければからのまま返す
		if ($p_page_num == ""){return $l_return_html;}
		
		if ($l_page_count > 0){
			// レコードがある場合
			// ページ表示部
			$l_return_html .= "<select id=\"id_sel_po_page\" size=\"1\">\n";
			
			for ($i_cnt = 1; $i_cnt <= $l_page_count; $i_cnt++){
				if ($i_cnt == $p_page_num){
					$l_return_html .= "<option value=\"".$i_cnt."\" selected=\"selected\">".$i_cnt." / ".$l_page_count." ページ"."</option>\n";
				}else{
					$l_return_html .= "<option value=\"".$i_cnt."\">".$i_cnt." / ".$l_page_count." ページ"."</option>\n";
				}
			}
			
			$l_return_html .= "</select>\n";
		}
		if($this->debug_mode==1){print("Step-makePageSelectHtml終了");print "<br>";}
		return $l_return_html;
	}
/*============================================================================
  Getter
  ============================================================================*/
/*----------------------------------------------------------------------------
  レコード数
  ----------------------------------------------------------------------------*/
	function getRecCount(){
		return $this->rec_count;
	}
/*----------------------------------------------------------------------------
  総ページ数
  ----------------------------------------------------------------------------*/
	function getPageCount(){
		return $this->page_count;
	}
/*----------------------------------------------------------------------------
  ページ選択用コンボボックスのHTML
  ----------------------------------------------------------------------------*/
	function getPageSelectHtml($p_page_num){
		return $this->makePageSelectHtml($p_page_num);
	}
/*----------------------------------------------------------------------------
  前のページのレコード数
  引数:			$p_page_num				ページ番号
  ----------------------------------------------------------------------------*/
	function getPrevRecCount($p_page_num){
		$l_return_value = 0;
		
		// ページ番号が1の場合は何もしない
		if($p_page_num == 1){
		}else{
			// 前のページのレコード数は表示レコード数と一致する
			$l_return_value = $this->show_count;
		}
		
		return $l_return_value;
	}
/*----------------------------------------------------------------------------
  次のページのレコード数
  引数:			$p_page_num				ページ番号
  ----------------------------------------------------------------------------*/
	function getNextRecCount($p_page_num){
		$l_return_value = 0;
		
		// ページ番号が総ページ数と同じ場合は何もしない
		if($p_page_num == $this->page_count){
		}else{
			// 総レコード数 - (ページ番号 * 表示レコード数) によって次ページ以降のレコード数を算出
			$l_remain_cnt = $this->rec_count - ($p_page_num * $this->show_count);
			
			if($l_remain_cnt <= 0){
				// 0以下の場合はそのページで終了なので何もしない
			}else{
				// 0より大きい場合
				if($l_remain_cnt >= $this->show_count){
					// 残りが表示レコード数以上の場合は表示レコード数を返す
					$l_return_value = $this->show_count;
				}else{
					// 残りが表示レコード数より小さい場合は残レコード数を返す
					$l_return_value = $l_remain_cnt;
				}
			}
		}
			
		return $l_return_value;
	}
	
/*============================================================================
  Setter
  ============================================================================*/
/*----------------------------------------------------------------------------
  レコードセット
  ----------------------------------------------------------------------------*/
	function setRecord($p_data_rec){
		// 変数にレコードをセット
		$this->data_rec = $p_data_rec;
		
		// レコード数を算出
		$this->rec_count = count($this->data_rec);
		
		// htmlspecialchars適用
		if($this->rec_count > 0 && $this->htmlspecial_flag){
			foreach($this->data_rec as $l_rec_num => $l_rec){
				if(count($l_rec) > 0){
					foreach($l_rec as $l_key => $l_value){
						$this->data_rec[$l_rec_num][$l_key] = htmlspecialchars($l_value);
					}
				}
			}
		}
		
		// 総ページ数を算出
		$this->page_count = $this->calcPageCount($this->rec_count, $this->show_count);
	}
	
/*----------------------------------------------------------------------------
  表示数セット
  ----------------------------------------------------------------------------*/
	function setShowCount($p_value){
		$this->show_count = $p_value;
		
		// 総ページ数を算出
		$this->page_count = $this->calcPageCount($this->rec_count, $this->show_count);
	}

}
?>