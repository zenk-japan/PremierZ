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
class CommonDate{
	private	$base_time	= 60;								// 時間丸めの基準時間(分)
	private	$round_type	= "RD";								// 丸め方法
	private $today_yyyy	= "";								// 本日の年
	private $today_mm	= "";								// 本日の月
	private $today_dd	= "";								// 本日の日
	private $r_weekday	= array( "日", "月", "火", "水", "木", "金", "土");
/* ==========================================================================
	例外定義
   ========================================================================== */
	function expt_CommonDate(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }
/*----------------------------------------------------------------------------
  コンストラクタ
  ----------------------------------------------------------------------------*/
	function __construct(){
		// 年を設定
		$this->today_yyyy = date("Y");
		
		// 月を設定
		$this->today_mm = date("m");
		
		// 日を設定
		$this->today_dd = date("d");
	}
	
/* ==========================================================================
	getter,setter
   ========================================================================== */
	// 時間丸めの基準時間(分)
	function getBaseTime(){
		return $this->base_time;
	}
	function setBaseTime($p_value){
		$this->base_time = $p_value;
	}
	// 丸め方法
	function getRoundType(){
		return $this->round_type;
	}
	function setRoundType($p_value){
		$this->round_type = $p_value;
	}
	// 日付
	function getTodayY($p_mode = 'V'){
		// modeがVなら文字列、Nなら数値で返す
		if ($p_mode == 'V'){
			return $this->today_yyyy;
		}else{
			return intval($this->today_yyyy);
		}
	}
	function getTodayM($p_mode = 'V'){
		// modeがVなら文字列、Nなら数値で返す
		if ($p_mode == 'V'){
			return $this->today_mm;
		}else{
			return intval($this->today_mm);
		}
	}
	function getTodayD($p_mode = 'V'){
		// modeがVなら文字列、Nなら数値で返す
		if ($p_mode == 'V'){
			return $this->today_dd;
		}else{
			return intval($this->today_dd);
		}
	}
/* ==========================================================================
	Date型の日付を基準となる日からのhh:mm形式の時刻に変換する

	$p_ymd			=	yyyy-mm-dd hh:mm:ss
	$p_date			=	日付
	@return			=	時分(hh:mm)
   ========================================================================== */
	function getTimeByYMD($p_ymd, $p_date = ''){
		$l_return_val = "";
		$l_onedaysec = 86400;
	
		// ymdがNULLの場合は空で返す
		if (is_null($p_ymd)){
			return $l_return_val;
		}
		
		// 日付として無効な場合はエラーとする
		if (strtotime($p_ymd) <= 0){
			return false;
		}
		
		if ($p_date == ""){
		// 日付指定が無い場合はシステム日付を使用する
			$p_date = date('Y-m-d');
		}
		// 日付指定日とymdの日の差分を算出
		$l_dateint = strtotime($p_ymd) - strtotime($p_date);
		
		// 差分がマイナスの場合はエラーとする
		if ($l_dateint < 0){
			return false;
		}
		
		// 時刻に差分日数算出
		$l_datediff = floor($l_dateint / $l_onedaysec);
		
		// ymdを時と分に分解し、時に差分日数*24を足す
		$l_hh = date('H', strtotime($p_ymd));
		$l_mm = date('i', strtotime($p_ymd));
		$l_hh = $l_hh + $l_datediff * 24;
		
		// 時分文字列を作成
		$l_return_val = $l_hh.":".$l_mm;
		
		return $l_return_val;
	}
/* ==========================================================================
	hh:mm形式の時刻と指定日からDate型の日付を算出する

	$p_hhmm			=	時分(hh:mm)
	$p_date			=	日付
	@return			=	yyyy-mm-dd
   ========================================================================== */
	function getDateByHHMM($p_hhmm, $p_date = ''){
		if ($p_date == ""){
		// 日付指定が無い場合はシステム日付を使用する
			$p_date = date('Y-m-d');
		}
		$l_return_value = "";
		
		if (!preg_match('/^(\d?\d)\:(\d?\d)$/',	$p_hhmm, $lr_preg_result)){
		// hh:mm形式になっていない場合はfalseで返す
			return false;
		}else{
		// hh:mm形式になっている場合
			if ($lr_preg_result[1] > 23){
			// 24時以上の場合は日付を変える
				$l_datenum = strtotime($p_date) + 86400 * intval($lr_preg_result[1] / 24);
				$l_return_value = date('Y-m-d H:i',strtotime(date('Y-m-d',$l_datenum)." ".($lr_preg_result[1] % 24).":".$lr_preg_result[2]));
			}else{
			// 24時前の場合は日付と組み合わせて変換する
				$l_return_value = date('Y-m-d H:i',strtotime(date('Y-m-d',strtotime($p_date))." ".$p_hhmm));
			}
		}
		return $l_return_value;
	}

/*============================================================================
	年月を指定して月初日を求める

	$year		=	年(yyyy)
	$month		=	月(mm)
	@return		=	yyyy-mm-dd
  ============================================================================*/
	function getMonthFirstDay($year = '', $month = '') {
		$l_return_val = "";
		
		if ($year != '' && $month != ''){
			$dt = mktime(0, 0, 0, $month, 1, $year);
			$l_return_val = date("Y-m-d", $dt);
		}
		
		return $l_return_val;
	}
	
/*============================================================================
	年月を指定して月末日を求める

	$year		=	年(yyyy)
	$month		=	月(mm)
	@return		=	yyyy-mm-dd
  ============================================================================*/
	function getMonthEndDay($year = '', $month = '') {
		$l_return_val = "";
		
		if ($year != '' && $month != ''){
			$dt = mktime(0, 0, 0, $month + 1, 0, $year);
			$l_return_val = date("Y-m-d", $dt);
		}
		
		return $l_return_val;
	}
	
/*============================================================================
	Y-m-d H:i形式時間取得
	処理概要：時刻が H:i 形式の場合、入力された日付から Y-m-d H:i に変換する
			$p_time				時刻
			$p_date				日付
  ============================================================================*/
	function getYmdHiTime($p_time, $p_date){
		$l_return_value = $p_time;
		
		if (count(explode('-',$p_time)) > 2){
		// 既にY-m-d H:i形式の場合はそのまま
			return $l_return_value;
		}else{
		// 作業日からY-m-d H:i形式の日付に変更する
			$l_newdate = $this->getDateByHHMM($p_time, $p_date);
			$l_return_value = date('Y-m-d H:i', strtotime($l_newdate));
		}
		return $l_return_value;
	}
	
/*============================================================================
	H:i形式時間取得
	処理概要：入力された日付から Y-m-d H:i 時刻部分のみを抜き出す
			$p_date				日付
  ============================================================================*/
	function getHiTime($p_date){
		$l_return_value = "";
		
		$l_return_value = date('H:i', strtotime($p_date));
	
		return $l_return_value;
	}

/*============================================================================
	月初日・月末日を指定して日数をもとに、指定曜日以外の日付を求める

	$p_mode		=	
						
	$st_year		=	開始年(yyyy)
	$st_month	=	開始月(mm)
	$st_day		=	開始日(dd)
	$en_year		=	終了年(yyyy)
	$en_month	=	終了月(mm)
	$en_day		=	終了日(dd)
	$ar_count	=	n
	@return
	$diffDay		=	dd
	$ar_workdate	=	yyyy-mm-dd
  ============================================================================*/
	function compareDate($p_mode,$st_year, $st_month, $st_day, $en_year, $en_month, $en_day) {
		//開始年月日/終了年月日を元に日数を取得する
		$st_dt		=	mktime(0, 0, 0, $st_month, $st_day, $st_year);
		$en_dt		=	mktime(0, 0, 0, $en_month, $en_day, $en_year);
		$diff		=	$st_dt - $en_dt;
		$diffDay	=	$diff / 86400;
		
		switch ($p_mode) {
			// 日数取得
			case GETMODE_DAY_COUNT:
				return $diffDay;
				break;
			// 営業日取得（平日）
			case GETMODE_BUSINESS_DAY:
				$ar_workdate	=	null;
				$ar_count		=	0;
				
				//取得した日数が平日の場合は、配列で返す
				for($i=0;$i<=$diffDay;$i++){
					$workdate		=	date("Y-m-d", mktime(0, 0, 0, $en_month, $en_day + $i, $en_year));
					$workweek		=	date("w", mktime(0, 0, 0, $en_month, $en_day + $i, $en_year));
					
					if($workweek == 0 || $workweek == 6){
						//土日は無視
					} else {
						//平日を配列で返す
						$ar_count++;
						$ar_workdate[$ar_count]	=	$workdate;
					}
				}
				return $ar_workdate;
				break;
		}
	}
/* ==========================================================================
	指定年月の日付を１週間単位の配列で取得する

	引数:
					$p_year							年
					$p_month						月
					$p_start_day					開始する曜日(日:1～土:7)
					$p_period_start					期間の開始(期間判定用。指定しなくても良い)
					$p_period_end					期間の終了(期間判定用。指定しなくても良い)
	戻り値の配列:
					array( [1～5] => [DD]=>dd,
					[HOLIDAY_FLAG]=>Y or N or X:日付なし),
					[INPERIOD_FLG]=>Y or N or X:日付なし )
   ========================================================================== */
	function getDaysForCal($p_year, $p_month, $p_start_day = 1, $p_period_start = '', $p_period_end = '') {
		$lr_return_rec	= array();
		$lr_days_array	= array();
		$lr_weekday		= $this->r_weekday;
		
		// 曜日の並び配列を作成
		$l_now_day = $p_start_day;
		for ($i = 1; $i <= 7; $i++){
			//$lr_days_array[$i] = $this->r_weekday[$l_now_day];
			$lr_days_array[$i] = $lr_weekday[$l_now_day-1];
			$l_now_day++;
			if($l_now_day == 8){
				$l_now_day = 1;
			}
		}
		
		// 通常の日付取得で１月分の日付を取得
		$lr_days = $this->getDays($p_year, $p_month);
		$l_max_day = count($lr_days) + 1;
		
		//{print "<pre>";var_dump($lr_days);print "</pre>";}
		// 開始曜日から７日単位で区切り、配列に格納する
		$l_day_count = 1;
		for ($l_week_cnt = 1; $l_week_cnt <= 6; $l_week_cnt++){
			for ($l_wday_cnt = 1; $l_wday_cnt <= 7; $l_wday_cnt++){
				if ($l_day_count <= $l_max_day){
				// まだ最終日が来ていない場合
					//print $l_week_cnt.":".$l_wday_cnt.":".$lr_days[$l_day_count][DAYCHAR].":".$lr_days_array[$l_wday_cnt]."<br>";
					if ($lr_days[$l_day_count][DAYCHAR] == $lr_days_array[$l_wday_cnt]){
						//print strtotime($p_period_start).":".strtotime($p_period_end)." -> ".strtotime($lr_days[$l_day_count]["YYYY-MM-DD"])."<br>";
						if ($p_period_start != '' or $p_period_end != ''){
						// 期間指定がある場合は、期間判定フラグもつける
							if (	($p_period_start	== '' or strtotime($lr_days[$l_day_count]["YYYY-MM-DD"]) >= strtotime($p_period_start))
								and ($p_period_end		== '' or strtotime($lr_days[$l_day_count]["YYYY-MM-DD"]) <= strtotime($p_period_end))
							){
								$lr_return_rec[$l_week_cnt][] = array("DD"=>$lr_days[$l_day_count]["DD"], "HOLIDAY_FLAG"=>$lr_days[$l_day_count]["HOLIDAY_FLAG"], "INPERIOD_FLG"=>"Y");
							}else{
								$lr_return_rec[$l_week_cnt][] = array("DD"=>$lr_days[$l_day_count]["DD"], "HOLIDAY_FLAG"=>$lr_days[$l_day_count]["HOLIDAY_FLAG"], "INPERIOD_FLG"=>"N");
							}
						}else{
						// 期間指定がない場合は、期間判定フラグはXとする
							$lr_return_rec[$l_week_cnt][] = array("DD"=>$lr_days[$l_day_count]["DD"], "HOLIDAY_FLAG"=>$lr_days[$l_day_count]["HOLIDAY_FLAG"], "INPERIOD_FLG"=>"X");
						}
						$l_day_count++;
					}else{
						$lr_return_rec[$l_week_cnt][] = array("DD"=>"-", "HOLIDAY_FLAG"=>"X", "INPERIOD_FLG"=>"X");
					}
				}else{
				// 最終日が来ている場合
					$lr_return_rec[$l_week_cnt][] = array("DD"=>"-", "HOLIDAY_FLAG"=>"X", "INPERIOD_FLG"=>"X");
				}
			}
		}
		//{print "<pre>";var_dump($lr_return_rec);print "</pre>";}
		
		
		return $lr_return_rec;
	}
/* ==========================================================================
	指定年月の日付を取得する

	引数:
					$p_year							年
					$p_month						月
	戻り値の配列:
					array( [1からの通し番号] => array([YYYY-MM-DD]=>yyyy-mm-dd,
													[MM-DD]=>mm-dd, [DD]=>dd,
													[DAYCHAR]=>曜日,
													[HOLIDAY_FLAG]=>Y or N) )
   ========================================================================== */
	function getDays($p_year, $p_month) {
		$lr_return_rec	= array();
		$l_date_num		= 1;
		$l_day_sec		= 86400;
		//$lr_weekday		= array( "日", "月", "火", "水", "木", "金", "土" );
		
		// 月初日
		$l_first_date	= strtotime($this->getMonthFirstDay($p_year, $p_month));
		// 月末日
		$l_last_date	= strtotime($this->getMonthEndDay($p_year, $p_month));
		
		//print "l_first_date"."->".$l_first_date.":"."l_last_date"."->".$l_last_date."<br>";
		
		// 月初月末が取得できない場合は終了
		if(is_null($l_first_date) || is_null($l_last_date) || trim($l_first_date) == "" || trim($l_last_date) == ""){
			return false;
		}
		
		for($l_nowdate = $l_first_date; $l_nowdate <= $l_last_date; $l_nowdate += $l_day_sec){
			// 休日フラグの設定(とりあえず土日に設定)
			if(date("w", $l_nowdate) == 0 || date("w", $l_nowdate) == 6){
				$l_holiday_flag = 'Y';
			}else{
				$l_holiday_flag = 'N';
			}
			
			// 配列設定
			$lr_return_rec[$l_date_num] = array(
											"YYYY-MM-DD"	=> date("Y-m-d", $l_nowdate),
											"MM-DD"			=> date("m-d", $l_nowdate),
											"DD"			=> date("d", $l_nowdate),
											"DAYCHAR"		=> $this->r_weekday[date("w", $l_nowdate)],
											"HOLIDAY_FLAG"	=> $l_holiday_flag
											);
			$l_date_num ++;
		}
		
		return $lr_return_rec;
	}
/* ==========================================================================
	残業時間算出

	$p_start_time		=	開始時刻
	$p_end_time			=	終了時刻
	$p_deduct_time		=	控除時間(xx:xx)
	$p_restraint_time	=	基本拘束時間(n.nn)
	$p_round_flag		=	丸めフラグ(0:丸める,1:丸めない)
	@return				=	nn.n
   ========================================================================== */
	function getOWTime($p_start_time, $p_end_time, $p_deduct_time, $p_restraint_time, $p_round_flag = 0){
		$l_return_value	= 0;
		$l_today		= getdate();								// 実行日の取得
		$l_today_int	= strtotime($l_today[year]."/".$l_today[mon]."/".$l_today[mday]);

		// 開始、終了いずれかがnullの場合は計算しない
		if(is_null($p_start_time) || is_null($p_end_time)){
			return null;
		}
		
//print ($p_start_time."/".$p_end_time."/".$p_deduct_time."/".$p_restraint_time."/".$p_round_flag);
		// 各値を数値に変換する
		try{
			// xx:xxの文字列を時間に変化すると、自動的に実行日の日付になるので、実行日の00:00を時間に変化したものを引く
			// 開始
			$ln_start_time	= strtotime($p_start_time);
			// 終了
			$ln_end_time	= strtotime($p_end_time);
			
			// 基本拘束時間はfloatなのでそのまま秒数に変換
			// 基本拘束時間
			if(is_null($p_restraint_time)){
				$ln_restraint_time	= 0;
			}else{
				$ln_restraint_time	= $p_restraint_time * 3600;
			}
			
			// 控除はfloatなのでそのまま秒数に変換
			// 控除
			if(is_null($p_deduct_time)){
				$ln_deduct_time	= 0;
			}else{
				$ln_deduct_time	= $p_deduct_time * 3600;
			}
		}catch(Exception $e){
			// 変換失敗はNULL
			return null;
		}
		
		// 開始-終了間-基本拘束-控除の時間を算出する
		$l_work_time = $ln_end_time - $ln_start_time - $ln_restraint_time - $ln_deduct_time;
		if($l_work_time < 0){
			$l_work_time = 0;
		}
		
		// 丸め処理(基準値で割って端数を処理し、その商に基準値を掛け、3600秒(1時間)で割る)
		if($p_round_flag == 0){
			$l_base_second = $this->base_time * 60;						// 分を秒に変換
			switch($this->round_type){
				case "RD":		// 切り捨て
					$l_quotient = floor($l_work_time / $l_base_second);
					break;
				case "HA":		// 四捨五入(roundは不安定なのでfloorを使用)
					$l_quotient = floor($l_work_time / $l_base_second + 0.5);
					break;
				case "RU":		// 切り上げ
					$l_quotient = ceil($l_work_time / $l_base_second);
					break;
			}
			$l_return_value = $l_quotient * $l_base_second / 3600;
		}else{
			$l_return_value = $l_work_time;
		}
		/*
		echo "p_start_time->".$p_start_time;
		echo "<br>";
		echo "p_end_time->".$p_end_time;
		echo "<br>";
		echo "p_deduct_time->".$p_deduct_time;
		echo "<br>";
		echo "p_restraint_time->".$p_restraint_time;
		echo "<br>";
		echo "l_work_time->".$l_work_time;
		echo "<br>";
		echo "l_return_value->".$l_return_value;
		echo "<br>";
		echo "<br>";
		*/
		return $l_return_value;
	}

/* ==========================================================================
	実働時間の丸め値算出

	$p_start_time		=	開始時刻
	$p_end_time			=	終了時刻
	$p_deduct_time		=	控除時間(xx:xx)
	$p_round_flag		=	丸めフラグ(0:丸める,1:丸めない)
	@return				=	nn.n
   ========================================================================== */
	function getRoundedTime($p_start_time, $p_end_time, $p_deduct_time, $p_round_flag = 0){
		$l_return_value	= 0;
		
		// 基本拘束時間0で残業を算出すると実働時間となる
		$l_return_value = $this->getOWTime($p_start_time, $p_end_time, $p_deduct_time, 0, $p_round_flag);
		
		return $l_return_value;
	}
/* ==========================================================================
	指定期間の指定曜日の日付を取得する
		引数：
			$p_start_date			開始日
			$p_end_date				終了日
			$p_flg_day1				日曜フラグ（1：取得する、0：取得しない）
			$p_flg_day2				月曜フラグ（1：取得する、0：取得しない）
			$p_flg_day3				火曜フラグ（1：取得する、0：取得しない）
			$p_flg_day4				水曜フラグ（1：取得する、0：取得しない）
			$p_flg_day5				木曜フラグ（1：取得する、0：取得しない）
			$p_flg_day6				金曜フラグ（1：取得する、0：取得しない）
			$p_flg_day7				土曜フラグ（1：取得する、0：取得しない）
   ========================================================================== */
	function getInTermDate(
							$p_start_date,
							$p_end_date,
							$p_flg_day1,
							$p_flg_day2,
							$p_flg_day3,
							$p_flg_day4,
							$p_flg_day5,
							$p_flg_day6,
							$p_flg_day7
	){
		$l_one_day_sec = 86400;
		// 引数チェック
		// すべての引数を必須とし、エラーの場合は処理しない
		
		if (
			is_null($p_start_date)	or
			is_null($p_end_date)	or
			is_null($p_flg_day1)	or
			is_null($p_flg_day2)	or
			is_null($p_flg_day3)	or
			is_null($p_flg_day4)	or
			is_null($p_flg_day5)	or
			is_null($p_flg_day6)	or
			is_null($p_flg_day7)
		){
			return false;
		}
		// 日付をtimeに変換
		$l_start_date	= strtotime($p_start_date);
		$l_end_date		= strtotime($p_end_date);
		
		// フラグを配列に格納(先頭0)
		$lr_flg_day = array(
						$p_flg_day1,
						$p_flg_day2,
						$p_flg_day3,
						$p_flg_day4,
						$p_flg_day5,
						$p_flg_day6,
						$p_flg_day7,
						);
						
		// 出力配列
		$lr_return_array	= array();
		
		// 開始日から終了日までのループ		
		for($l_now_date = $l_start_date; $l_now_date <= $l_end_date; $l_now_date += $l_one_day_sec){
			// その日の曜日を取得
			$l_now_day = intval(date('w',$l_now_date));
			
			// フラグが1の場合は出力配列に追加する
			if ($lr_flg_day[$l_now_day] == 1){
				$lr_return_array[] = date('Y-m-d',$l_now_date);
			}
		}
		
		return $lr_return_array;
	}
}
?>
