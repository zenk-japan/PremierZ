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
 カレンダー用javascript関数
*******************************************************************************/
var $debug_mode = 0;							// デバッグモード
var $g_date = new Date();
var $g_year = $g_date.getYear();
var $g_year = ($g_year < 2000) ? $g_year+1900 : $g_year;	// 1900年からの増分が返される場合がある
var $g_month = $g_date.getMonth() + 1;		// 1月が0になる為
var $g_date = $g_date.getDate();
var $g_today_ym = $g_year + " - " + $g_month;		// 本日の年月

/*==============================================================================
  年取得処理
  処理概要:	yyyy - mm 形式の日付から年の数値を切り出す
  引数:		$p_ym		年月
  ============================================================================*/
	function getY($p_ym){
		var $l_splitpos = $p_ym.indexOf("-");
		var $l_year = $p_ym.substring(0, $l_splitpos);
		return Number($l_year);
	}
	
/*==============================================================================
  月取得処理
  処理概要:	yyyy - mm 形式の日付から月の数値を切り出す
  引数:		$p_ym		年月
  ============================================================================*/
	function getM($p_ym){
		var $l_splitpos = $p_ym.indexOf("-");
		var $l_month = $p_ym.substring($l_splitpos + 1);
		return Number($l_month);
	}
	
/*==============================================================================
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
		
		return $l_year + " - " + $l_month;
	}
/*==============================================================================
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
		
		return $l_year + " - " + $l_month;
	}

/*==============================================================================
  カレンダーセット処理
  引数:		$p_ym		年月
  ============================================================================*/
	function setCalendar($p_ym){
		//var $l_size = $(".css_cal_dtl_table_tr2 > * > INPUT").size();
		//alert("css_cal_dtl_table_tr2 : "+$l_size);
		
		// 月の最終日を算出
		var $l_year = getY($p_ym);
		var $l_month = getM($p_ym);
		var $ld_firstday = new Date($l_year, $l_month - 1, 1);		// 月は1月が0になる為これで当月初日
		var $ld_lastday = new Date($l_year, $l_month, 0);			// 月は1月が0になる為これで当月最終日
		if($debug_mode==1){alert($ld_lastday);}
		var $l_lastday_num = $ld_lastday.getDate();				// 当月最終日の日
		if($debug_mode==1){alert($l_lastday_num);}
		var $l_firstday_day = $ld_firstday.getDay();				// 当月初日の曜日(日曜=0)
		if($debug_mode==1){alert($l_firstday_day);}
		
		// カウンタリセット
		var $l_count = 0;				// ループカウンタ
		var $l_day_count = 1;			// 日にちのカウンタ
		
		$(".css_cal_dtl_table_tr2 > * > INPUT").each(function(){
			// 表示をクリア
			$(this).val(" ");
			$(this).css("font-weight","normal");
			$(this).hide(200);
		});
		$(".css_cal_dtl_table_tr2 > * > INPUT").each(function(){
			//カレンダー表示
			if($l_count >= $l_firstday_day && $l_day_count <= $l_lastday_num){
				// 月初の曜日番号以上のループカウンタで、日にちが当月最終日以下までの場合は日付表示
				$(this).val($l_day_count);
				// 本日の場合は、太字に変更
				if($l_year==$g_year && $l_month==$g_month && $l_day_count==$g_date){
					$(this).css("font-weight","bold");
				}
				
				// 日付インクリメント
				$l_day_count = $l_day_count + 1;
			}
			$l_count = $l_count + 1;
		});
		$(".css_cal_dtl_table_tr2 > * > INPUT").each(function(){
			// 再表示
			$(this).show(200);
		});
	}

/*==============================================================================
  画面起動時処理
  ============================================================================*/
	$(document).ready(function(){
		var $l_caller_id = $('#_TARGET_ID').val();		//呼び出し元の項目ID
	/*--------------------------------------------------------------------------
	  デフォルト年月のセット
	--------------------------------------------------------------------------*/
		$("#cal_cond_date").val($g_today_ym);
		// カレンダー描写
		setCalendar($("#cal_cond_date").val());

	/*--------------------------------------------------------------------------
	  セットボタンクリック時の処理
	  セットボタンにはid=cal_setbtをつける事
	--------------------------------------------------------------------------*/
		$("#cal_setbt").bind("click", function(){
		// 呼び出し元のテキストボックスに値をセット
			// 値設定
			var $l_caller_id = $('#caller_id').val();		//呼び出し元の項目ID
			//alert($('#caller_id').val());
			var $l_put_value = $('#iftx_close').val();		//セットする値
			//alert($('#iftx_close').val());
			
		// 値セット
			//alert(self.parent.$(":input#"+$l_caller_id).val());
			self.parent.$(":input#"+$l_caller_id).val($l_put_value);
			
		// リストを閉じる
			self.parent.tb_remove();
		});
	/*--------------------------------------------------------------------------
	  キャンセルボタンクリック時の処理
	  キャンセルボタンにはid=cal_cancelbtをつける事
	--------------------------------------------------------------------------*/
		$("#cal_cancelbt").bind("click", function(){
			// リストを閉じる
			self.parent.tb_remove();
		});
	/*--------------------------------------------------------------------------
	  日付減少ボタンクリック時の処理
	--------------------------------------------------------------------------*/
		$("#cal_cond_bt_left").bind("click", function(){
			// 月を1か月減らす
			$("#cal_cond_date").val(decYM($("#cal_cond_date").val()));
			// カレンダー再描写
			setCalendar($("#cal_cond_date").val());
		});
	/*--------------------------------------------------------------------------
	  日付増加ボタンクリック時の処理
	--------------------------------------------------------------------------*/
		$("#cal_cond_bt_right").bind("click", function(){
			// 月を1か月増やす
			$("#cal_cond_date").val(incYM($("#cal_cond_date").val()));
			// カレンダー再描写
			setCalendar($("#cal_cond_date").val());
		});
		
	/*--------------------------------------------------------------------------
	  日付ボタンクリック時の処理
	--------------------------------------------------------------------------*/
		$(".css_cal_dtl_table_tr2 > * > INPUT").bind("click", function(){
			var $l_cond_date = $("#cal_cond_date").val();		// 表示中の年月
			var $l_year = getY($l_cond_date);
			var $l_month = getM($l_cond_date);
			var $ls_date_string = "";							// 日付の文字列yyyy-mm-dd
			var $ls_year = "";									// 年の文字列
			var $ls_month = "";									// 月の文字列
			var $ls_date = "";									// 日の文字列
			
			// 日付の算出
			var $l_clicked_date = Number($(this).val());
			
			// 以降の処理は日付が入ったボタンがクリックされたときのみ実行
			if($l_clicked_date > 0){
				// 年を文字列化
				$ls_year = "" + $l_year;
				if($debug_mode==1){alert($ls_year);}
				
				// 月を2桁にする
				if($l_month < 10){
					$ls_month = "0" + $l_month;
				}else{
					$ls_month = "" + $l_month;
				}
				if($debug_mode==1){alert($ls_month);}
				
				// 日を2桁にする
				if($l_clicked_date < 10){
					$ls_date = "0" + $l_clicked_date;
				}else{
					$ls_date = "" + $l_clicked_date;
				}
				if($debug_mode==1){alert($ls_date);}
				
				// 年と結合して日付の文字列を作成
				$ls_date_string = $l_year + "-" + $ls_month + "-" + $ls_date;
				if($debug_mode==1){alert($ls_date_string);}
				//$("#test_text").val($ls_date_string);
				
				// 値セット
				if($debug_mode==1){alert(self.parent.$(":input#"+$l_caller_id).val());}
				self.parent.$(":input#"+$l_caller_id).val($ls_date_string);
			
				// リストを閉じる
				self.parent.tb_remove();
			}
		});
	/*--------------------------------------------------------------------------
	  年月ダブルクリック時の処理
	--------------------------------------------------------------------------*/
		$("#cal_cond_date").bind("dblclick", function(){
			// 日付を元に戻す
			$("#cal_cond_date").val($g_today_ym);
			// カレンダー描写
			setCalendar($("#cal_cond_date").val());
		});
	});
