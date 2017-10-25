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
 カレンダー表示用javascript関数
*******************************************************************************/
var $obj_ymcal_call_item;
var $ext_list_trgt_top;
var $ext_list_trgt_left;
var $g_date = new Date();
var $g_year = $g_date.getYear();
var $g_year = ($g_year < 2000) ? $g_year+1900 : $g_year;	// 1900年からの増分が返される場合がある
var $g_month = $g_date.getMonth() + 1;		// 1月が0になる為
var $g_date = $g_date.getDate();
var $g_today_ym = ($g_month < 10) ? $g_year + "-" + "0" + $g_month : $g_year + "-" + $g_month;		// 本日の年月

/*============================================================================
  年月カレンダー表示処理
  引数:
  		$p_call_item_id							呼び出し元項目のID
  ============================================================================*/
function showYMCalendar($p_call_item_id){
	var $ym_cal_fnd_html	= '<div id="id_ext_div_ymcalendar_fnd" style="display:none;"></div>';
	var $ym_cal_html		= '<div id="id_ext_div_ymcalendar" style="display:none;"><table id="id_ext_table_ymcal"><tr><td id="id_ext_td_ymcal_title" colspan=2>年月</td><td id="id_td_btn_ymcal_close"><input id="id_ext_btn_ymcal_close" type="button" value="×"/></td></tr><tr><td class="c_ext_td_ymcal_movemonth"><input id="id_ext_btn_ymcal_prev" class="c_ext_btn_ymcal_movemonth" type="button" title="前の月" value="<"/></td><td id="id_ext_td_ymcal_ym"><input id="id_ext_btn_ymcal_ymval" type="button" title="クリックでセット" value=""/></td><td class="c_ext_td_ymcal_movemonth"><input id="id_ext_btn_ymcal_next" class="c_ext_btn_ymcal_movemonth" type="button" title="次の月" value=">"/></td></tr></table></div>';
	
	// 元の項目のオブジェクトを格納
	$obj_ymcal_call_item = $("#" + $p_call_item_id);
	if(!$obj_ymcal_call_item){
		// 元の項目が取得できない場合は終了
		return false;
	}
	
	// 元の項目の位置から表示位置を算出
	$ext_list_trgt_top		= parseInt($obj_ymcal_call_item.offset().top, 10) + 20;
	$ext_list_trgt_left		= parseInt($obj_ymcal_call_item.offset().left, 10) + 20;
	
	// 既にDIVがある場合は削除
	removeExtYMCalFnd();
	removeExtYMCalDiv();
	//alert('remove');
	
	// DIV配置
	$("body").prepend($ym_cal_fnd_html);
	$("#id_ext_div_ymcalendar_fnd").after($ym_cal_html);
	//alert('set');
	
	// 現在の年月を表示
	$("#id_ext_btn_ymcal_ymval").val($g_today_ym);
	
	// CSS設定
	$("#id_ext_div_ymcalendar_fnd").css("z-index", "2000");
	$("#id_ext_div_ymcalendar").css("z-index", "2001");
	var $l_cssObj = {
		position: "absolute",
		top: $ext_list_trgt_top,
		left: $ext_list_trgt_left
	}
	$("#id_ext_div_ymcalendar_fnd").css($l_cssObj);
	$("#id_ext_div_ymcalendar").css($l_cssObj);
	//alert('cssset');
	
	// 表示
	$("#id_ext_div_ymcalendar").fadeIn("800", function(){
		// ボタン処理をバインド
		/*--------------------------------------------------------------------------
		  日付ボタンクリック時の処理
		--------------------------------------------------------------------------*/
		$("#id_ext_btn_ymcal_ymval").bind("click", function(){
			// 月を1か月減らす
			$obj_ymcal_call_item.val($("#id_ext_btn_ymcal_ymval").val());
			// unbind
			unbindExtYMCal();
			// 閉じる
			removeExtYMCalFnd();
			removeExtYMCalDiv();
		});
		/*--------------------------------------------------------------------------
		  日付減少ボタンクリック時の処理
		--------------------------------------------------------------------------*/
		$("#id_ext_btn_ymcal_prev").bind("click", function(){
			// 月を1か月減らす
			$("#id_ext_btn_ymcal_ymval").val(decYM($("#id_ext_btn_ymcal_ymval").val()));
		});
		/*--------------------------------------------------------------------------
		  日付増加ボタンクリック時の処理
		--------------------------------------------------------------------------*/
		$("#id_ext_btn_ymcal_next").bind("click", function(){
			// 月を1か月増やす
			$("#id_ext_btn_ymcal_ymval").val(incYM($("#id_ext_btn_ymcal_ymval").val()));
		});
		
		/*--------------------------------------------------------------------------
		  閉じるボタンクリック時の処理
		--------------------------------------------------------------------------*/
		$("#id_ext_btn_ymcal_close").bind("click", function(){
			// unbind
			unbindExtYMCal();
			// 閉じる
			removeExtYMCalFnd();
			removeExtYMCalDiv();
		});
	});
	$("#id_ext_div_ymcalendar_fnd").fadeIn("fast");

}
/*============================================================================
  unbind
  ============================================================================*/
function unbindExtYMCal(){
	// 年月
	if($("#id_ext_btn_ymcal_ymval")){
		$("#id_ext_btn_ymcal_ymval").unbind("click");
	}
	// 前の月
	if($("#id_ext_btn_ymcal_prev")){
		$("#id_ext_btn_ymcal_prev").unbind("click");
	}
	// 次の月
	if($("#id_ext_btn_ymcal_next")){
		$("#id_ext_btn_ymcal_next").unbind("click");
	}
	// 閉じる
	if($("#id_ext_btn_ymcal_close")){
		$("#id_ext_btn_ymcal_close").unbind("click");
	}
}
/*============================================================================
  DIV削除
  ============================================================================*/
function removeExtYMCalFnd(){
	// リスト基礎
	if($("#id_ext_div_ymcalendar_fnd")){
		$("#id_ext_div_ymcalendar_fnd").fadeOut("fast", function(){
			$("#id_ext_div_ymcalendar_fnd").remove();
		});
	}
}
function removeExtYMCalDiv(){
	// リスト本体
	if($("#id_ext_div_ymcalendar")){
		$("#id_ext_div_ymcalendar").fadeOut("fast", function(){
			$("#id_ext_div_ymcalendar").remove();
		});
	}
}

/*============================================================================
  年取得処理
  処理概要:	yyyy - mm 形式の日付から年の数値を切り出す
  引数:		$p_ym		年月
  ============================================================================*/
	function getY($p_ym){
		var $l_splitpos = $p_ym.indexOf("-");
		var $l_year = $p_ym.substring(0, $l_splitpos);
		return Number($l_year);
	}
	
/*============================================================================
  月取得処理
  処理概要:	yyyy - mm 形式の日付から月の数値を切り出す
  引数:		$p_ym		年月
  ============================================================================*/
	function getM($p_ym){
		var $l_splitpos = $p_ym.indexOf("-");
		var $l_month = $p_ym.substring($l_splitpos + 1);
		return Number($l_month);
	}
	
/*============================================================================
  年月減少処理
  引数:		$p_ym		年月
  ============================================================================*/
	function decYM($p_ym){
		// 年月の切り出し
		var $l_year = getY($p_ym);
		var $l_month = getM($p_ym);
		
		if($l_year>1900){
			// 年が1900年より大きい場合のみ移動
			if($l_month==1){
				// 1月は年を減らして12月にする
				$l_year = $l_year - 1;
				$l_month = 12;
			}else{
				$l_month = $l_month - 1;
			}
		}
		
		if($l_month < 10){
			// 1桁は0埋め
			$l_month = "0" + $l_month;
		}
		
		return $l_year + "-" + $l_month;
	}
/*============================================================================
  年月増加処理
  引数:		$p_ym		年月
  ============================================================================*/
	function incYM($p_ym){
		// 年月の切り出し
		var $l_year = getY($p_ym);
		var $l_month = getM($p_ym);
		
		if($l_year<9999){
			// 年が9999年より小さい場合のみ移動
			if($l_month==12){
				// 12月は年を増やして1月にする
				$l_year = $l_year + 1;
				$l_month = 1;
			}else{
				$l_month = $l_month + 1;
			}
		}
		
		if($l_month < 10){
			// 1桁は0埋め
			$l_month = "0" + $l_month;
		}
		
		return $l_year + "-" + $l_month;
	}