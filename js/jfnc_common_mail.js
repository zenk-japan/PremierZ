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
 javascript関数
*******************************************************************************/
/*==============================================================================
  フォーカス処理
  処理概要：input項目フォーカス取得時に行う処理
  ============================================================================*/
function procInputFocus(){
	$(function() {
		// 背景色を変える
		$("input[type='text'],input[type='password'],textarea").focus(
			function() {
				$(this).css('color'				,'#ffffff');
				$(this).css('background-color'	,'#20B2AA');
				$(this).css('font-weight'		,'bold');
//				$(this).css('font-family'		,'"Century Gothic",sans-serif');
				$(this).css('font-size'			,'12px');
			});
		
		// 背景色を戻す
		$("input[type='text'],input[type='password'],textarea").blur(
			function() {
				$(this).css('color'				,'');
				$(this).css('background-color'	, '');
				$(this).css('font-weight'		,'normal');
//				$(this).css('font-family'		,'"Century Gothic",sans-serif');
				$(this).css('font-size'			,'11px');
			});
	});
}

/*==============================================================================
  ページPOST
  処理概要：
  		引数で指定されたphpファイルにPOSTする
  引数：
		$p_object			起動元のオブジェクト
		$p_post_to			POST先のphpファイル
  ============================================================================*/
function postPage($p_object, $p_post_to){
	
	// 引数設定
	if($p_object){
		// inputタグのtextとhiddenを取得
		$lobj_param = $p_object.find("input[type='text'],input[type='password'],input[type='checkbox']:checked,input[type='radio']:checked,select,textarea,input[type='hidden']");
		
		if($lobj_param){
			$l_cnt = 0;
			
			// 値を連想配列に格納する
			$lobj_param.each(function(){
				$lr_param[$(this).attr("name")]	= $(this).val();
				$l_cnt = $l_cnt + 1;
			});
			
		}else{
			// form内にtextまたはhiddenがない場合
			$message = "オブジェクト不正です";
			return false;
		}
	}else{
		// p_objectが空の場合
		$message = "オブジェクト不正です";
		return false;
	}
	// POST処理
	$.post($p_post_to, $lr_param, callBackFnc);
}

/*==============================================================================
  ウォーターマークを表示
  処理概要：input項目にウォーターマーク表示する処理
  ============================================================================*/
$(document).ready(function(){
	$.updnWatermark.attachAll();
});

