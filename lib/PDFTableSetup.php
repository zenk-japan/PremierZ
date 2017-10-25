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
class PDFTableSetup{
// *****************************************************************************
// クラス名：PDFTableSetup
// 処理概要：TABLEを構築
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	private $prmptcol			= 0;			// プロンプトがセットされているカラム番号
	private $attribcol			= 1;			// 属性がセットされているカラム番号
	private $valcol				= 2;			// 値がセットされているカラム番号
	private $bgcolorcol			= 3;			// 色がセットされているカラム番号
	private $fontcol			= 4;			// 文字色がセットされているカラム番号
	private $widthcol			= 5;			// 幅がセットされているカラム番号
	private $aligncol			= 6;			// 位置がセットされているカラム番号

// =============================================================================
// 明細表定義
// 概要：明細表のHTMLを構築
// =============================================================================
	function setDtlTab($p_ar_pdftab,$p_tab_spacing,$p_tab_padding,$p_tab_border,$p_tab_width,$p_tab_align){
		
		$l_retval = "";
		$l_row_count		=	count($p_ar_pdftab);		// レコード数
		$l_col_count		=	count($p_ar_pdftab[1]);		// カラム数
		$row_dtl_amt		=	0;							// 明細合計開始行数保存用
		$l_row_dtl_count	=	$l_row_count - 1;			// レコード数明細本体
		
		if($l_row_count > 0){
		// テーブルヘッダ
			$l_retval	.=	"\n<table cellspacing=\"".$p_tab_spacing."\" cellpadding=\"".$p_tab_padding."\" border=\"".$p_tab_border."\" width=\"".$p_tab_width."\">\n";
			$l_retval	.=	"\t<tr>\n";
			
			// 項目見出し
			for ($col = 0; $col <= $l_col_count; $col++){
				$l_retval .= $this->getHTML($p_ar_pdftab[1][$col], 1, $col);
			}
			
			$l_retval .= "\t</tr>\n";
			
		// 本体
			for ($row = 2; $row <= $l_row_count; $row++){
				$l_retval .= "\t<tr>\n";
				
				// 各項目の出力
				for ($col = 0; $col <= $l_col_count; $col++){
					$l_retval .= $this->getHTML($p_ar_pdftab[$row][$col], $row, $col);
				}
				
				$l_retval .= "\t</tr>\n";
			}
			
			$l_retval .= "</table>\n";
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
		$l_bgcolorcol	= $this->bgcolorcol;				// 色がセットされているカラム番号
		$l_fontcol		= $this->fontcol;					// 文字色がセットされているカラム番号
		$l_widthcol		= $this->widthcol;					// 幅がセットされているカラム番号
		$l_aligncol		= $this->aligncol;					// 位置がセットされているカラム番号
		$l_retval		= "";
		
		// 属性値毎にそれぞれのHTMLを構築する
		switch ($p_ar_field[$l_attribcol]){
			case INPUT_TYPE_DISP : // ただのテキスト
				$l_retval .= "\t\t<td bgcolor=\"".$p_ar_field[$l_bgcolorcol]."\" style=\"color:".$p_ar_field[$l_fontcol]."\" width=\"".$p_ar_field[$l_widthcol]."%\" align=\"".$p_ar_field[$l_aligncol]."\">";
				$l_retval .= $p_ar_field[$l_valcol];
				$l_retval .= "</td>\n";
			break;
			case INPUT_TYPE_NUM : // ただのテキスト-数値
				$l_retval .= "\t\t<td bgcolor=\"".$p_ar_field[$l_bgcolorcol]."\" style=\"color:".$p_ar_field[$l_fontcol]."\" width=\"".$p_ar_field[$l_widthcol]."%\" align=\"".$p_ar_field[$l_aligncol]."\">";
				$l_retval .= $p_ar_field[$l_valcol];
				$l_retval .= "</td>\n";
			break;
			case INPUT_TYPE_PDF : // PDF用
				$l_retval .= "\t\t<td bgcolor=\"".$p_ar_field[$l_bgcolorcol]."\" style=\"color:".$p_ar_field[$l_fontcol]."\" width=\"".$p_ar_field[$l_widthcol]."%\" align=\"".$p_ar_field[$l_aligncol]."\">";
				$l_retval .= $p_ar_field[$l_valcol];
				$l_retval .= "</td>\n";
			break;
		}
		
		return $l_retval;
	}
}