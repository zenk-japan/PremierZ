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
/*******************************************************************************
 勤務表用javascript関数
*******************************************************************************/
var openWindow;								// 子画面のウィンドウオブジェクト
var thisWindowName;							// 今の画面のウィンドウ名
var thisWindow;								// 今の画面のウィンドウオブジェクト
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	$obj_select = $("select.css_select");
	$obj_hdl_form = $("form[name='fm_dtltab']");		// 明細
	$obj_srch_form = $("#fm_search");					// ヘッダー
	
	// リスト値をボタンクリック時の隠し項目にセット
	if($obj_select){
		$obj_select.each(function(index1, domEle1){
			// ヘッダー部にセット
			$obj_srch_form.append("<INPUT type=hidden name=\""+$(domEle1).attr("name")+"\" value=\""+$(domEle1).val()+"\"></INPUT>");
			
			// 明細部の各行にセット
			if($obj_hdl_form){
				$obj_hdl_form.each(function(index2, domEle2){
					$(domEle2).append("<INPUT type=hidden name=\""+$(domEle1).attr("name")+"\" value=\""+$(domEle1).val()+"\"></INPUT>");
				});
			}
		});
		
		// リスト変更時は隠し項目の値を変更する
		$obj_select.bind("change", function(){
			$obj_select.each(function(index1, domEle1){
				// ヘッダー部にセット
				$obj_srch_form.append("<INPUT type=hidden name=\""+$(domEle1).attr("name")+"\" value=\""+$(domEle1).val()+"\"></INPUT>");
				
				// 明細部の各行にセット
				$obj_hdl_form.each(function(index2, domEle2){
					if($(domEle2).find("input[name='"+$(domEle1).attr("name")+"']")){
						$(domEle2).find("input[name='"+$(domEle1).attr("name")+"']").val($(domEle1).val());
					}else{
						$(domEle2).append("<INPUT type=hidden name=\""+$(domEle1).attr("name")+"\" value=\""+$(domEle1).val()+"\"></INPUT>");
					}
				});
			});
		});
	}
	
	// 入力例の表示
	$.updnWatermark.attachAll();

});