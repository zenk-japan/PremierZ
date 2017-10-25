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
class mntCondSetup{
// *****************************************************************************
// クラス名：mntCondSetup
// 処理概要：条件部のHTML構築
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	private $chkbox_on		= "on";				// チェックボックスチェック時の値
	
/* =============================================================================
	例外定義
   ===========================================================================*/
	function expt_mntCondSetup(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }

// =============================================================================
// メニュー定義
// 概要：メニュー用の配列の要素数分メニューHTML定義処理を起動する
// =============================================================================
	function setMenu($p_ar_menuset){
		$l_retval = "";
		
		$l_menu_count = count($p_ar_menuset);	// メニューの数
		if($l_menu_count == 0){
			return;
		}
		//print_r($p_ar_menuset);
		
		
		// テーブル構築
		$l_retval .= "<table id=\"id_tab_main_cond\">\n";
		$l_retval .= "<tr>\n";
		for ($i=0; $i < $l_menu_count; $i++){
			// tr内のtd構築
			if($p_ar_menuset[$i]["type"] == "return"){
				// 改行指示
				$l_retval .= "</tr>\n";
				$l_retval .= "<tr>\n";
			}else{
				$l_retval .= $this->getMenuHtml($p_ar_menuset[$i]);
			}
			
		}
		$l_retval .= "</tr>\n";
		$l_retval .= "</table>\n";
		
		return $l_retval;
	}
// =============================================================================
// メニューHTML定義
// 概要：メニューの設定を元にHTMLを構築して戻す
// 引数：
//       $p_ar_menu		メニュー設定配列
//                      value:	HTMLのvalueにセットする値
//                      id:		HTMLのidにセットする値
//                      type:	HTMLのtypeにセットする値
//
// =============================================================================
	function getMenuHtml($p_ar_menu){
		$htmlstrings = "";
		
		switch ($p_ar_menu["type"]){
		// チェックボックス
			case "checkbox":
				$htmlstrings  = "<td class=\"c_td_search\" style=\"width:".htmlspecialchars($p_ar_menu["width"])."\">\n";	// width
				$htmlstrings .= "<span class=\"c_span_search\">".htmlspecialchars($p_ar_menu["title"])."</span><br>\n";		// title
				$htmlstrings .= "<input ";
				$htmlstrings .= "id=\"".htmlspecialchars($p_ar_menu["id"])."\" class=\"c_chb_search\" ";					// id
				$htmlstrings .= "type=\"checkbox\" ";
				$htmlstrings .= "onClick=\"\" ";
				// チェック状態が設定されている場合はチェックを入れる
				if($p_ar_menu["checked"]==$this->chkbox_on){																// checked(任意)
					$htmlstrings .= "checked";
				}
				$htmlstrings .= "style=\"width:".htmlspecialchars($p_ar_menu["width"])."\">";								// width
				$htmlstrings .= "<label for=\"".htmlspecialchars($p_ar_menu["id"])."\""." class=\"c_cbx_search\" >";		// label(class含む)
				$htmlstrings .= htmlspecialchars($p_ar_menu["value"])."</label></input>\n";									// value
				$htmlstrings .= "</td>\n";
			break;
		// テキストボックス
			case "text":
				$htmlstrings  = "<td class=\"c_td_search\" style=\"width:".htmlspecialchars($p_ar_menu["width"])."\">\n";	// width
				$htmlstrings .= "<span class=\"c_span_search\">".htmlspecialchars($p_ar_menu["title"])."</span><br>\n";		// title
				$htmlstrings .= "<input ";
				$htmlstrings .= "id=\"".htmlspecialchars($p_ar_menu["id"])."\" class=\"c_txb_search\" ";					// id
				$htmlstrings .= "type=\"text\" ";																			// type
				$htmlstrings .= "value=\"".htmlspecialchars($p_ar_menu["value"])."\" ";										// value
				$htmlstrings .= "class=\"c_txt_search\" ";
				$htmlstrings .= "onClick=\"\" ";
				$htmlstrings .= "style=\"width:".htmlspecialchars($p_ar_menu["width"])."\"></input>\n";						// width
				$htmlstrings .= "</td>\n";
			break;
		// テキストボックス(読み取り専用)
			case "disp":
				$htmlstrings  = "<td class=\"c_td_search\" style=\"width:".htmlspecialchars($p_ar_menu["width"])."\">\n";	// width
				$htmlstrings .= "<span class=\"c_span_search\">".htmlspecialchars($p_ar_menu["title"])."</span><br>\n";		// title
				$htmlstrings .= "<input ";
				$htmlstrings .= "id=\"".htmlspecialchars($p_ar_menu["id"])."\" class=\"c_dsp_search\" ";					// id
				$htmlstrings .= "type=\"text\" readOnly=\"true\" ";															// type
				$htmlstrings .= "value=\"".htmlspecialchars($p_ar_menu["value"])."\" ";										// value
				$htmlstrings .= "class=\"c_txt_search\" ";
				$htmlstrings .= "onClick=\"\" ";
				$htmlstrings .= "style=\"width:".htmlspecialchars($p_ar_menu["width"])."\"></input>\n";						// width
				$htmlstrings .= "</td>\n";
			break;
		// リストボックス
			case "list":
				$htmlstrings  = "<td class=\"c_td_search\" style=\"width:".htmlspecialchars($p_ar_menu["width"])."\">\n";	// width
				$htmlstrings .= "<span class=\"c_span_search\">".htmlspecialchars($p_ar_menu["title"])."</span><br>\n";		// title
				$htmlstrings .= "<select id=\"".htmlspecialchars($p_ar_menu["id"])."\" class=\"c_sel_search\" >\n";			// id
				foreach($p_ar_menu["value"] as $key => $value){																// value(配列)
					$htmlstrings .= "<option value=\"".$value."\"";
					if($p_ar_menu["default"] && $p_ar_menu["default"] == $value){
						// デフォルト設定がある場合はselectedにする
						$htmlstrings .= " selected";																		// default(任意)
					}
					$htmlstrings .= ">".htmlspecialchars($value)."</option>\n";
				}
				$htmlstrings .= "</select>\n";
				$htmlstrings .= "</td>\n";
			break;
		}
		return $htmlstrings;
	}

}
?>