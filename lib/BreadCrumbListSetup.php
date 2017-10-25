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

class BreadCrumbListSetup{
// *****************************************************************************
// クラス名：BreadCrumbListSetup
// 処理概要：画面毎のパンくずリスト定義
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	public $from_name;							// FORM名
	public $hidden_val_html;					// 隠し項目のHTML
	public $cssclass_link;						// CSSクラス
	public $cssclass_linkself;					// CSSクラス自画面用
	
// =============================================================================
// メニュー定義
// 概要：パンくずリストのHTMLを構築する
//       普通のリンクだとPOSTできないので、FORMとINPUTを使用する
// =============================================================================
	function setBreadCrumbList($p_ar_bclink){
		$l_retval = "";
		
		$l_loopcnt	= 0;
		
		$l_retval .= "<FORM name=" . $this->from_name . " method=POST>\n";
		
		$i = 0;
		foreach($p_ar_bclink as $key => $value){
			$i++;
		}
		
		// パンくずリンクの配列分リンクを並べる
		foreach($p_ar_bclink as $key => $value){
			$i--;
			$l_loopcnt++;
			if($l_loopcnt != 1){
				$l_retval .= " >> \n";
			}
			if($l_loopcnt!=count($p_ar_bclink)){
				// 前画面へのハイパーリンク部分
				$l_retval .= "<INPUT class=\"" . $this->cssclass_link . "\" type=text readOnly=true value=\"" . $key . "\" ";
				$l_retval .= "onClick=\"action='" . $value . "';submit();\">";
			}else{
				// 自画面部分
				$l_retval .= "<INPUT class=\"" . $this->cssclass_linkself . "\" type=text readOnly=true value=\"" . $key . "\">";
			}
			$l_retval .= "</INPUT>";
			
		}
		unset($value);
		
		// 隠し項目のセット
		$l_retval .= $this->hidden_val_html;
		
		$l_retval .= "</FORM>\n";
		
		return $l_retval;
	}
}