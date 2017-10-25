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


/******************************************************************************
 ファイル名：attendance_print.php
 処理概要   勤務表印刷用画面
 GET受領値：
             token_code                 トークン(必須)
             output_unit                出力単位
             round_base                 丸め基準
             round_method               丸め方法
             work_date_ym               出力年月
             estimate_id                見積ID
             work_user_id               作業者のユーザーID
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print "<pre>";
		var_dump($_GET);
		print "</pre>";
		print "<br>";
		print "session-><br>";
		session_start();
		print "<pre>";
		var_dump($_SESSION);
		print "</pre>";
		print "<br>";
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "TASKS_EDIT";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_sess_data_id			= "";									// 画面にセットするDATA_ID
	$l_sess_user_name		= "";									// 実行ユーザー名
	$l_get_token			= "";									// GETされたトークン
	$l_sess_token			= "";									// セッション保持のトークン
	$l_user_id				= "";									// ユーザーID
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_attprint(Exception $e){
		//echo "例外が発生しました。";
		//echo $e->getMessage();
		// セッション切断の場合はメッセージに「ST」と入ってくる
		if($e->getMessage() == "ST"){
			$l_error_type = "ST";
		}else{
			$l_error_type = "ER";
		}

		require_once('../lib/ShowMessage.php');
		$lc_smess = new ShowMessage($l_error_type);

		// 予期せぬ例外の場合は追加メッセージをセット
		if($l_error_type != "ST"){
			$lc_smess->setExtMessage($e->getMessage());
		}

		$lc_smess->showMessage();
		return;
    }
	set_exception_handler('my_exception_attprint');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();

	// GETされたトークンを取得
	$l_get_token = $_GET['token_code'];
	if(is_null($l_get_token)){
		throw new Exception($l_error_type_st);
	}

	// トークンの取得
	$l_sess_token = $lc_sess->getToken();

	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	// セッションと_GETでトークンが一致しない場合は不正アクセスとみなす
	if($l_get_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}

	// DATA_IDの取得
	$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_sess_data_id == ""){
		throw new Exception($l_error_type_st);
	}

	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  GET変数取得
  ----------------------------------------------------------------------------*/
	$l_work_date_ym	= $_GET['work_date_ym'];
	$l_work_user_id	= $_GET['work_user_id'];
	$l_output_unit	= $_GET['output_unit'];
	$l_estimate_id	= $_GET['estimate_id'];
	$l_round_base	= $_GET['round_base'];
	$l_round_method	= $_GET['round_method'];
	if($l_debug_mode==1){print("Step-GET変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// ------------------------------
	// マスター取得
	// ------------------------------
	// 共通マスタ
	require_once('../mdl/m_common_master.php');
	$lc_mcm = new m_common_master();
	
	// 出力単位
	$lr_output_unit = $lc_mcm->getCommonValueRec($l_sess_data_id, "ATTENDANCE_OUTPUT_UNIT");
	
	// 丸め基準時間
	$lr_round_base = $lc_mcm->getCommonValueRec($l_sess_data_id, "FRACTION_UNIT");
	
	// 丸め方法
	$lr_round_method = $lc_mcm->getCommonValueRec($l_sess_data_id, "ROUNDING_STATUS");
	
	if($l_debug_mode==1){print("Step-DBデータ取得_list");print "<br>";}
	// ------------------------------
	// 明細表示用のデータ取得
	// ------------------------------
	if($l_work_date_ym != "" && $l_work_user_id != ""){
		// 作業年月と作業者IDがPOSTされている場合は勤務表を表示する
		// 検索条件設定
		$lr_query_cond_dtl = array("DATA_ID = ".$l_sess_data_id);
		array_push($lr_query_cond_dtl, "WORK_DATE_YM = '".$l_work_date_ym."'");	// 年月
		array_push($lr_query_cond_dtl, "WORK_USER_ID = ".$l_work_user_id);		// 作業者ID
		array_push($lr_query_cond_dtl, "WC_VALIDITY_FLAG = 'Y'");				// 有効フラグ
		
		// 出力単位がWORKの場合はさらに見積IDの条件を追加する
		if($l_output_unit == 'WORK'){
			array_push($lr_query_cond_dtl, "ESTIMATE_ID = ".$l_estimate_id);
		}
		// レコード取得
		// 勤務表MDL
		require_once('../mdl/m_attendance.php');
		$lc_model_dtl = new m_attendance('Y', null, $lr_query_cond_dtl, 'WORK_DATE');
		$lr_detail_rec = $lc_model_dtl->getViewRecord();
	}
//{print "<pre>";var_dump($lr_detail_rec);print "</pre>";}
	
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*-----------------------------------
	Smarty変数定義
  -----------------------------------*/
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル

	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>";}
/*-----------------------------------
	Smarty変数セット
  -----------------------------------*/
	// CSSファイル
	$ar_css_files	= array(
							DIR_CSS."v_attendance_sheet.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js"
							);

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
/*-----------------------------------
	Smartyセット
  -----------------------------------*/
	// ------------------------------
	// クラスインスタンス作成
	// ------------------------------
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = DIR_TEMPLATES;
	$lc_smarty->compile_dir  = DIR_TEMPLATES_C;
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"						, "勤務表印刷");				// 画面タイトル
	$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
	$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル
	$lc_smarty->assign("proc_mode"						, $l_proc_mode);				// モード

	// ------------------------------
	// 明細項目
	// ------------------------------
	// タイトル
	$lc_smarty->assign("detail_title"		, "勤務表");
	
	
	// 明細が取得できていれば勤務表を表示
	//if(count($lr_detail_rec) > 0){
	// 明細表示フラグ
	$lc_smarty->assign("detail_html_show_flag", "Y");
	
	// 各種表示用の値を設定
	$l_detail_title_year		= mb_substr($l_work_date_ym, 0, 4);
	$l_detail_title_month		= mb_substr($l_work_date_ym, 5, 2);
	$l_detail_title_ym			= $l_detail_title_year. "年". $l_detail_title_month. "月";
	$l_detail_wuser_name		= $lr_detail_rec[1]['WORK_USER_NAME'];
	$l_detail_wuser_comp_name	= $lr_detail_rec[1]['WORK_USER_COMPANY_NAME'];
	
	// 勤務表用にSmartyクラスを新作成
	$lc_smarty_detail = new Smarty();
	if(is_null($lc_smarty_detail)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty_detail->template_dir = DIR_TEMPLATES;
	$lc_smarty_detail->compile_dir  = DIR_TEMPLATES_C;
	
	// アサイン
	$lc_smarty_detail->assign("title_ym"				, $l_detail_title_ym);
	$lc_smarty_detail->assign("workuser_name"			, $l_detail_wuser_name);
	$lc_smarty_detail->assign("workuser_company_name"	, $l_detail_wuser_comp_name);
	
	/*--------------------------
		勤務表用の配列を再作成
	  --------------------------*/
	// 日付を取得
	require_once('../lib/CommonDate.php');
	$lc_commond = new CommonDate();
	
	// 基準時間、丸め方法のセット
	$lc_commond->setBaseTime($l_round_base);
	$lc_commond->setRoundType($l_round_method);
	
	// 月初日取得
	$l_first_of_month	= $lc_commond->getMonthfirstDay($l_detail_title_year, $l_detail_title_month);
	$l_from_year		= date("Y", strtotime($l_first_of_month));
	$l_from_month		= date("m", strtotime($l_first_of_month));
	$l_from_day			= date("d", strtotime($l_first_of_month));
	
	// 月末日取得
	$l_end_of_month	= $lc_commond->getMonthEndDay($l_detail_title_year, $l_detail_title_month);
	$l_to_year		= date("Y", strtotime($l_end_of_month));
	$l_to_month		= date("m", strtotime($l_end_of_month));
	$l_to_day		= date("d", strtotime($l_end_of_month));

	// 日数・日付取得
	$lr_workday		= $lc_commond->getDays($l_detail_title_year, $l_detail_title_month);
	$l_workdaycount	= count($lr_workday);
	
	if($l_workdaycount == 0){
		throw new Exception('カレンダーが取得できませんでした');
	}
	
	$lr_attendance_array = array();
	
	// 合計用変数
	$l_total_break_time		= 0;
	$l_total_working_hours	= 0;
	$l_total_overtime_hours	= 0;
	
	if(count($lr_detail_rec) > 0){
		// 勤務表用配列の作成
		// array([日付(m-d)] => array([内部No.(1～)] => array([作業名],[開始時間],[終了時間],[休憩時間],[実働時間],[残業時間],[作業内容詳細],[備考])))
		foreach($lr_workday as $l_daynum => $lr_dayinfo){
			// 配列初期化
			$l_atd_work_date	= $lr_dayinfo['MM-DD']."(".$lr_dayinfo['DAYCHAR'].")";
			$lr_attendance_array[$l_atd_work_date] = array();
			
			// 変数初期化
			$l_inner_num				= 0;	// 内部No.
			$l_atd_work_name			= "";
			$l_atd_entering_timet		= "";
			$l_atd_entering_timet_hi	= "";
			$l_atd_leave_timet			= "";
			$l_atd_leave_timet_hi		= "";
			$l_atd_break_time			= "";
			$l_atd_working_hours		= "";
			$l_atd_overwork_hours		= "";
			$l_atd_content_details		= "";
			$l_atd_remarks				= "";
			$l_atd_holiday_flag			= "";
			
			// 休日フラグ
			$l_atd_holiday_flag		= $lr_dayinfo["HOLIDAY_FLAG"];
			
			// 作業データを検索し、作業日と日付が合致するものを順次配列に登録する
			foreach($lr_detail_rec as $l_recnum => $lr_workdetail){
				//print $lr_workdetail['WORK_DATE'] .":". $lr_dayinfo['YYYY-MM-DD']."<br>";
				if($lr_workdetail['WORK_DATE'] == $lr_dayinfo['YYYY-MM-DD']){
					$l_inner_num ++;
					
					// 作業名
					$l_atd_work_name		= $lr_workdetail["WORK_NAME"];
					
					// 開始時間
					// 管理部時間 < 作業者時間の優先順で決定
					if ($lr_workdetail["ENTERING_MANAGE_TIMET"] != ""){
						$l_atd_entering_timet	= $lr_workdetail["ENTERING_MANAGE_TIMET"];
						// h:i形式化
						$l_atd_entering_timet_hi	= $lc_commond->getTimeByYMD($l_atd_entering_timet, $lr_workdetail["WORK_DATE"]);
					}elseif ($lr_workdetail["ENTERING_STAFF_TIMET"] != ""){
						$l_atd_entering_timet	= $lr_workdetail["ENTERING_STAFF_TIMET"];
						// h:i形式化
						$l_atd_entering_timet_hi	= $lc_commond->getTimeByYMD($l_atd_entering_timet, $lr_workdetail["WORK_DATE"]);
					}else{
						$l_atd_entering_timet		= "";
						$l_atd_entering_timet_hi	= "";
					}
					
					// 終了時間
					// 管理部時間 < 作業者時間の優先順で決定
					if ($lr_workdetail["LEAVE_MANAGE_TIMET"] != ""){
						$l_atd_leave_timet	= $lr_workdetail["LEAVE_MANAGE_TIMET"];
						// h:i形式化
						$l_atd_leave_timet_hi		= $lc_commond->getTimeByYMD($l_atd_leave_timet, $lr_workdetail["WORK_DATE"]);
					}elseif ($lr_workdetail["LEAVE_STAFF_TIMET"] != ""){
						$l_atd_leave_timet	= $lr_workdetail["LEAVE_STAFF_TIMET"];
						// h:i形式化
						$l_atd_leave_timet_hi		= $lc_commond->getTimeByYMD($l_atd_leave_timet, $lr_workdetail["WORK_DATE"]);
					}else{
						$l_atd_leave_timet		= "";
						$l_atd_leave_timet_hi	= "";
					}
					
					// 休憩時間
					$l_atd_break_time		= $lr_workdetail["DISP_BREAK_TIME"];
					
					// 実働時間
					if ($l_atd_entering_timet != "" and $l_atd_leave_timet != "" ){
						$l_atd_working_hours	= $lc_commond->getRoundedTime($l_atd_entering_timet, $l_atd_leave_timet, $lr_workdetail["DISP_BREAK_TIME"]);
						$l_atd_working_hours	= number_format($l_atd_working_hours, 2, '.', '');
					}else{
						$l_atd_working_hours	= "";
					}
					
					// 残業時間
					if ($l_atd_entering_timet != "" and $l_atd_leave_timet != "" ){
						$l_atd_overwork_hours	= $lc_commond->getOWTime($l_atd_entering_timet, $l_atd_leave_timet, $lr_workdetail["DISP_BREAK_TIME"], $lr_workdetail["DEFAULT_WORKING_TIME"]);
						$l_atd_overwork_hours	= number_format($l_atd_overwork_hours, 2, '.', '');
					}else{
						$l_atd_overwork_hours	= "";
					}
					
					// 作業内容詳細
					$l_atd_content_details	= $lr_workdetail["WORK_CONTENT_DETAILS"];
					
					// 備考
					// 承認区分がAP（承諾）以外は備考欄に出力
					$l_atd_remarks = "";
					if($lr_workdetail["APPROVAL_DIVISION"] <> "AP"){
						$l_atd_remarks	=	$lr_workdetail["APPROVAL_DIVISION_NAME"];
					}
					// キャンセル区分がWC（作業依頼）以外は備考欄に出力
					if($lr_workdetail["CANCEL_DIVISION"] <> "WR"){
						if(is_null($l_atd_remarks)){
							$l_atd_remarks	.=	$lr_workdetail["CANCEL_DIVISION_NAME"];
						} else {
							$l_atd_remarks	.=	",".$lr_workdetail["CANCEL_DIVISION_NAME"];
						}
					}
					
					// 合計用変数計算
					if($l_atd_break_time != ""){$l_total_break_time			+= $l_atd_break_time;}
					if($l_atd_working_hours != ""){$l_total_working_hours	+= $l_atd_working_hours;}
					if($l_atd_overwork_hours != ""){$l_total_overtime_hours	+= $l_atd_overwork_hours;}
					
					// 配列構築
					$lr_attendance_array[$l_atd_work_date][$l_inner_num] = array(
																					"WORK_NAME"			=> $l_atd_work_name,				// 作業名
																					"ENTERING_TIMET"	=> $l_atd_entering_timet_hi,		// 開始時間
																					"LEAVE_TIMET"		=> $l_atd_leave_timet_hi,			// 終了時間
																					"BREAK_TIME"		=> $l_atd_break_time,				// 休憩時間
																					"WORKING_HOURS"		=> $l_atd_working_hours,			// 実働時間
																					"OVERWORK_HOURS"	=> $l_atd_overwork_hours,			// 残業時間
																					"CONTENT_DETAILS"	=> $l_atd_content_details,			// 作業内容詳細
																					"REMARKS"			=> $l_atd_remarks,					// 備考
																					"HOLIDAY_FLAG"		=> $l_atd_holiday_flag				// 休日フラグ
																				);
					//{print "<pre>";print_r($lr_attendance_array[$l_atd_work_date][$l_inner_num]);print "</pre>";}
				}
			}
			
			// 作業が無い場合は空のデータを登録
			if($l_inner_num == 0){
				// 配列構築
				$lr_attendance_array[$l_atd_work_date][1] = array(
																	"WORK_NAME"			=> $l_atd_work_name,				// 作業名
																	"ENTERING_TIMET"	=> $l_atd_entering_timet_hi,		// 開始時間
																	"LEAVE_TIMET"		=> $l_atd_leave_timet_hi,			// 終了時間
																	"BREAK_TIME"		=> $l_atd_break_time,				// 休憩時間
																	"WORKING_HOURS"		=> $l_atd_working_hours,			// 実働時間
																	"OVERWORK_HOURS"	=> $l_atd_overwork_hours,			// 残業時間
																	"CONTENT_DETAILS"	=> $l_atd_content_details,			// 作業内容詳細
																	"REMARKS"			=> $l_atd_remarks,					// 備考
																	"HOLIDAY_FLAG"		=> $l_atd_holiday_flag				// 休日フラグ
																);
			}
		}
		
		//print_r($lr_attendance_array);
		
		// 補足
		$l_base_method_notice = "※実働時間、及び残業時間は、"
								.$lr_round_base[$l_round_base]
								."分単位に"
								.$lr_round_method[$l_round_method]
								."処理を行っています。";
	}
	// 合計値の0.00化
	$l_total_break_time		= number_format($l_total_break_time, 2, '.', '');
	$l_total_working_hours	= number_format($l_total_working_hours, 2, '.', '');
	$l_total_overtime_hours	= number_format($l_total_overtime_hours, 2, '.', '');
	
	// アサイン
	$lc_smarty_detail->assign("ar_attendance_detail"	, $lr_attendance_array);	// 明細
	$lc_smarty_detail->assign("total_break_time"		, $l_total_break_time);		// 休憩時間合計
	$lc_smarty_detail->assign("total_working_hours"		, $l_total_working_hours);	// 実働時間合計
	$lc_smarty_detail->assign("total_overtime_hours"	, $l_total_overtime_hours);	// 残業時間合計
	$lc_smarty_detail->assign("base_method_notice"		, $l_base_method_notice);	// 補足
	
	// htmlを作成
	$l_attendance_sheet_html = $lc_smarty_detail->fetch('attendance_sheet_popup.tpl');
	//$l_attendance_sheet_html = "test";
	
	// htmlをセット
	$lc_smarty->assign("detail_html"		, $l_attendance_sheet_html);
	//}
	


	// ------------------------------
	// 隠し項目
	// ------------------------------

	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}

/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('attendance_sheet_print.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>