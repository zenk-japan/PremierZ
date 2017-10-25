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
class DtlTableSetup{
// *****************************************************************************
// クラス名：DtlTableSetup
// 処理概要：明細表のHTMLを構築
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	private $prmptcol			= 0;			// プロンプトがセットされているカラム番号
	private $attribcol			= 1;			// 属性がセットされているカラム番号
	private $valcol				= 2;			// 値がセットされているカラム番号
	private $actcol				= 3;			// アクションがセットされているカラム番号
	
	// クラス設定
	private $l_dtl_btn_class			= "glbt";			// 明細行のボタン一般用CSSクラス
	private $l_dtl_chkb_class			= "chkbk";			// 明細行のチェックボックス用CSSクラス
	private $l_dtl_updbtn_class			= "updbt";			// 明細行のupdateボタン用CSSクラス
	private $l_dtl_hd_class				= "dtltab";			// テーブルヘッダ用CSSクラス
	private $l_dtl_cap_class			= "dtltabtop";		// 一行目の見出し用CSSクラス
	private $l_dtl_tr_class				= "dtl_tr_nomal";	// 明細行tr用CSSクラス(通常行)
	private $l_dtl_amt_tr_class			= "dtl_tr_amt";		// 明細行tr用CSSクラス(合計行)
	private $l_dtl_td_class_even 		= "dtltext_even";	// 明細行td用CSSクラス(偶数行)
	private $l_dtl_td_amt_class_even	= "dtltextnum_even";// 明細行td用CSSクラス(偶数行数値)
	private $l_dtl_td_class_odd			= "dtltext_odd";	// 明細行td用CSSクラス(奇数行)
	private $l_dtl_td_amt_class_odd		= "dtltextnum_odd";	// 明細行td用CSSクラス(奇数行数値)
	private $l_dtl_td_hidden_class		= "dtlhidden";		// 明細行hidden項目用CSSクラス
	
	public $from_name;							// 明細表のFORM名
	public $form_amount_name;					// 明細表合計部のFORM名
	public $hidden_val_html;					// 隠し項目のHTML

// =============================================================================
// 明細表定義
// 概要：明細表のHTMLを構築
// =============================================================================
	function setDtlTab($p_ar_dtltab){
		$l_retval = "";		
		
		$l_row_count = count($p_ar_dtltab);		// レコード数
		$l_col_count = count($p_ar_dtltab[1]);	// カラム数
		
		$row_dtl_amt = 0;						// 明細合計開始行数保存用
		$l_row_dtl_count = $l_row_count - 1;	// レコード数明細本体

		if($l_row_count > 0){
		// テーブルヘッダ
			$l_retval .= "<TABLE class=\"" . $this->l_dtl_hd_class . "\">\n";
		// 一行目の見出し
			$l_retval .= "<TR class=\"" . $this->l_dtl_cap_class . "\">\n";
			
			// 項目見出し
			for ($col = 0; $col <= $l_col_count; $col++){
				$l_retval .= $this->getHTML($p_ar_dtltab[1][$col], 1, $col);
			}
			
			$l_retval .= "</TR>\n";
			
		// 明細本体
			if($l_row_count == 2){
				for ($row = 2; $row <= $l_row_count; $row++){
					// 行の変わり目でFORM、TRを出力
					$l_retval .= "<FORM name=" . $this->from_name . " id=" . $this->from_name . " method=POST>\n";
					$l_retval .= "<TR class=\"" . $this->l_dtl_tr_class . "\">\n";
					
					// 各項目の出力
					for ($col = 0; $col <= $l_col_count; $col++){
						$l_retval .= $this->getHTML($p_ar_dtltab[$row][$col], $row, $col);
					}
					
					// 隠し項目のセット
					$l_retval .= $this->hidden_val_html;
					
					$l_retval .= "</TR>\n";
					$l_retval .= "</FORM>\n";
				}
			}
			if($l_row_count > 2)
			{
		// 明細本体
				for ($row = 2; $row <= $l_row_dtl_count; $row++){
					// 行の変わり目でFORM、TRを出力
					$l_retval .= "<FORM name=" . $this->from_name . " id=" . $this->from_name . " method=POST>\n";
					$l_retval .= "<TR class=\"" . $this->l_dtl_tr_class . "\">\n";
					
					// 各項目の出力
					for ($col = 0; $col <= $l_col_count; $col++){
						$l_retval .= $this->getHTML($p_ar_dtltab[$row][$col], $row, $col);
					}
					
					// 隠し項目のセット
					$l_retval .= $this->hidden_val_html;
					
					$l_retval .= "</TR>\n";
					$l_retval .= "</FORM>\n";
				
				}
				$row_dtl_amt = $row;		//	明細本体合計
		// 明細合計部
				for ($row_amount = $row_dtl_amt ; $row_amount <= $l_row_count; $row_amount++){
					// 行の変わり目でFORM、TRを出力
					if($p_ar_dtltab[$row_amount][0][0] == "CALCULATION")
					{
						//最終行が合計の場合
						$l_retval .= "<FORM name=" . $this->form_amount_name . " id=" . $this->form_amount_name . " method=POST>\n";
						$l_retval .= "<TR class=\"" . $this->l_dtl_amt_tr_class . "\">\n";
					}
					else
					{
						//最終行が明細行の場合
						$l_retval .= "<FORM name=" . $this->from_name . " id=" . $this->from_name . " method=POST>\n";
						$l_retval .= "<TR class=\"" . $this->l_dtl_tr_class . "\">\n";
					}
					
					// 各項目の出力
					for ($col = 0 ; $col <= $l_col_count; $col++){
						$l_retval .= $this->getHTML($p_ar_dtltab[$row_amount][$col], $row_amount, $col);
					}
					
					// 隠し項目のセット
					$l_retval .= $this->hidden_val_html;
					
					$l_retval .= "</TR>\n";
					$l_retval .= "</FORM>\n";
				}
				
			}
			$l_retval .= "</TABLE>\n";
		}
		
		return $l_retval;
	}
// =============================================================================
// HTML構築
// 概要：各項目のHTMLを構築
// 引数：
//			$p_ar_field		出力する項目の設定値配列
//			$p_row_num		行番号
//			$p_col_num		列番号
// =============================================================================
	function getHTML($p_ar_field, $p_row_num, $p_col_num){
		$l_prmptcol		= $this->prmptcol;					// プロンプトがセットされているカラム番号
		$l_attribcol	= $this->attribcol;					// 属性がセットされているカラム番号
		$l_valcol		= $this->valcol;					// 値がセットされているカラム番号
		$l_actcol		= $this->actcol;					// アクションがセットされているカラム番号
		$l_inputprmpt	= htmlspecialchars($p_ar_field[$l_prmptcol], ENT_QUOTES);			// プロンプト
		$l_inputval		= htmlspecialchars($p_ar_field[$l_valcol], ENT_QUOTES);				// 値
		if (!is_null($p_ar_field[$l_actcol])){
			$l_inputact		= $p_ar_field[$l_actcol];		// アクション
		}
		$l_retval = "";
		
		// 偶数行と奇数行で異なるclassの設定
		if($p_row_num % 2 == 0){
			// 偶数行
			$l_dtl_td_class = $this->l_dtl_td_class_even;
			$l_dtl_td_amt_class = $this->l_dtl_td_amt_class_even;
		}else{
			// 奇数行
			$l_dtl_td_class = $this->l_dtl_td_class_odd;
			$l_dtl_td_amt_class = $this->l_dtl_td_amt_class_odd;
			
		}
		
		// 属性値毎にそれぞれのHTMLを構築する
		switch ($p_ar_field[$l_attribcol]){
			case INPUT_TYPE_DISP : // ただのテキスト
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\" onDblClick=\"".$l_inputact."\">";
				$l_retval .= "<DIV name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\">" . $l_inputval . "</DIV>";
				$l_retval .= "</TD>\n";
			break;
			case INPUT_TYPE_NUM : // ただのテキスト-数値
				$l_retval .= "<TD class=\"".$l_dtl_td_amt_class."\" onDblClick=\"".$l_inputact."\">";
				$l_retval .= "<DIV name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\">" . $l_inputval . "</DIV>";
				$l_retval .= "</TD>\n";
			break;
			case INPUT_TYPE_BUTTON : // ボタン
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\" style=\"text-align:center;\">";
				
				if($p_col_num==0){
					// 更新ボタンの場合
					$l_retval .= "<INPUT class=\"".$this->l_dtl_updbtn_class."\" ";
					$l_retval .= "onClick=\"".$l_inputact."\" ";
				}else{
					// 更新ボタン以外の場合
					$l_retval .= "<INPUT class=\"".$this->l_dtl_btn_class."\" ";
					$l_retval .= "onClick=\"".$l_inputact."\" ";
				}
				$l_retval .= "type=".INPUT_TYPE_BUTTON." id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				$l_retval .= "></INPUT></TD>\n";
			break;
			case INPUT_TYPE_SUBMIT : // 送信ボタン
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\" >";
				$l_retval .= "<INPUT class=\"".$this->l_dtl_btn_class."\" ";
				$l_retval .= "type=".INPUT_TYPE_SUBMIT." name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				$l_retval .= "onClick=\"".$l_inputact."\" ";
				$l_retval .= "></INPUT></TD>\n";
			break;
			case INPUT_TYPE_CHKBOX : // チェックボックス
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\" style=\"text-align:center;\">";
				$l_retval .= "<INPUT class=\"".$this->l_dtl_chkb_class."\" ";
				$l_retval .= "type=".INPUT_TYPE_CHKBOX." name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				// 値がY以外の場合はチェックを入れる
				if ($l_inputval!="Y"){
					$l_retval .= "checked ";
				}
				$l_retval .= "onDblClick=\"".$l_inputact."\" ";
				$l_retval .= "style=\"border:0px;\"></INPUT></TD>\n";
			break;
			case INPUT_TYPE_TEXT : // テキスト
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\"><INPUT type=".INPUT_TYPE_TEXT." name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				$l_retval .= "onDblClick=\"".$l_inputact."\" ";
				$l_retval .= "></INPUT></TD>\n";
			break;
			case INPUT_TYPE_TEXTAREA : // テキストエリア
				$l_retval .= "<TD class=\"".$l_dtl_td_class."\"><INPUT type=".INPUT_TYPE_TEXT." name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				$l_retval .= "onDblClick=\"".$l_inputact."\" ";
				$l_retval .= "></INPUT></TD>\n";
				echo $l_retval;
			break;
			case INPUT_TYPE_HIDDEN : // 隠し項目
				$l_retval .= "<TD size=\"0\" class=\"".$this->l_dtl_td_hidden_class."\"><INPUT type=".INPUT_TYPE_HIDDEN." name=\"".$l_inputprmpt."\" id=\"".$l_inputprmpt."\" value=\"".$l_inputval."\" ";
				$l_retval .= "></INPUT></TD>\n";
			break;
			
		}
		return $l_retval;
	}
}