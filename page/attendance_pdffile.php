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
 ファイル名：attendance_pdffile.php
 処理概要  ：勤務表出力画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_work_ym_cond            作業年月(検索用)(任意)
             nm_work_name_cond          作業名(検索用)(任意)
             nm_workuser_name_cond      作業者(検索用)(任意)
             nm_output_unit             出力単位(任意)
             nm_round_base              丸め基準時間(任意)
             nm_round_method            丸め方法(任意)
             nm_work_date_ym            作業年月
             nm_work_user_id            作業者ID
             nm_estimate_id             見積ID
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_selected_workstaff_id   作業人員ID(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
		print "session-><br>";
		print var_dump($_SESSION);
		print "<br>";
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているグループ名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$l_auth_code			= "";									// 権限
	$l_show_page			= "";									// 表示ページ番号
	$l_max_page				= "";									// 最大ページ番号
	$l_work_ym_cond			= "";									// 作業年月(検索用)
	$l_work_name_cond		= "";									// 作業名(検索用)
	$l_workuser_name_cond	= "";									// 作業者(検索用)
	$l_output_unit			= "";									// 出力単位
	$l_round_base			= "";									// 丸め基準時間
	$l_round_method			= "";									// 丸め方法
	$l_work_date_ym			= "";									// 作業年月
	$l_work_user_id			= "";									// 作業者ID
	$l_estimate_id			= "";									// 見積ID
	$l_selected_workstaff_id	= "";								// POSTされたグループID
	$l_show_dtl_group_id	= "";									// 明細を表示するグループID
	$lr_dtl_rec				= "";									// 明細表示用のレコード
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_attendance(Exception $e){
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
	set_exception_handler('my_exception_attendance');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		if($l_debug_mode==3){throw new Exception('l_post_tokenがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		if($l_debug_mode==3){throw new Exception('l_sess_tokenがNULL');}
		throw new Exception($l_error_type_st);
	}
	if($l_post_token != $l_sess_token){
		if($l_debug_mode==3){throw new Exception('token不整合');}
		throw new Exception($l_error_type_st);
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		if($l_debug_mode==3){throw new Exception('l_data_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// 権限の取得
	$l_auth_code = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	if($l_auth_code == ""){
		if($l_debug_mode==3){throw new Exception('l_auth_codeがNULL');}
		throw new Exception($l_error_type_st);
	}
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
	
/*----------------------------------------------------------------------------
  POST変数取得
  ----------------------------------------------------------------------------*/
	// 作業年月(検索用)
	if(!is_null($_POST['nm_work_ym_cond'])){
		$l_work_ym_cond = $_POST['nm_work_ym_cond'];
	}else{
		$l_work_ym_cond = date("Y-m");
	}
	
	// 作業名(検索用)
	if(!is_null($_POST['nm_work_name_cond'])){
		$l_work_name_cond = $_POST['nm_work_name_cond'];
	}
	
	// 作業者名(検索用)
	if(!is_null($_POST['nm_workuser_name_cond'])){
		$l_workuser_name_cond = $_POST['nm_workuser_name_cond'];
	}
	
	// 出力単位
	if(!is_null($_POST['nm_output_unit'])){
		$l_output_unit= $_POST['nm_output_unit'];
	}else{
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_output_unit = ATTENDANCE_OUTPUT_UNIT_DEFAULT;
	}
	
	// 丸め基準時間
	if(!is_null($_POST['nm_round_base'])){
		$l_round_base= $_POST['nm_round_base'];
	}else{
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_round_base = BASE_TIME_DEFAULT;
	}
	
	// 丸め方法
	if(!is_null($_POST['nm_round_method'])){
		$l_round_method = $_POST['nm_round_method'];
	}else{
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_round_method = ROUND_METHOD_DEFAULT;
	}
	
	// 作業年月
	if(!is_null($_POST['nm_work_date_ym'])){
		$l_work_date_ym = trim($_POST['nm_work_date_ym']);
	}
	
	// 作業者ID
	if(!is_null($_POST['nm_work_user_id'])){
		$l_work_user_id = $_POST['nm_work_user_id'];
	}
	
	// 見積ID
	if(!is_null($_POST['nm_estimate_id'])){
		$l_estimate_id = $_POST['nm_estimate_id'];
	}
	
	// 表示ページ番号
	if(!is_null($_POST['nm_show_page'])){
		$l_show_page = $_POST['nm_show_page'];
	}else{
		$l_show_page = 1;
	}
	
	// 最大ページ番号
	if(!is_null($_POST['nm_max_page'])){
		$l_max_page = $_POST['nm_max_page'];
	}else{
		$l_max_page = 1;
	}
	
	// 作業人員ID
	if(!is_null($_POST['nm_selected_workstaff_id'])){
		$l_selected_workstaff_id = $_POST['nm_selected_workstaff_id'];
	}
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*==============================
		作業
	  ==============================*/
	// グループMDL
	require_once('../mdl/m_attendance.php');
	
	// ------------------------------
	// リスト表示用のデータ取得
	// ------------------------------
	// 検索条件設定
	$lr_query_cond = array('DATA_ID = '.$l_data_id);
	// 作業年月
	if($l_work_ym_cond != ""){
		array_push($lr_query_cond, "WORK_DATE_YM like '%".$l_work_ym_cond."%'");
	}
	
	// 作業名
	if($l_work_name_cond != ""){
		array_push($lr_query_cond, "WORK_NAME like '%".$l_work_name_cond."%'");
	}
	
	if($l_auth_code == AUTH_GEN2 || $l_auth_code == AUTH_GEN3 || $l_auth_code == AUTH_GENE){
		// 一般ユーザーの場合は、自分の分のみ出力する
		// また、検索用の作業者名も自分の名前に固定する
		array_push($lr_query_cond, "WORK_USER_ID = ".$l_sess_user_id);
		$l_workuser_name_cond	= $l_user_name;
		$l_workuser_fix_flag	= 'Y';
	}else{
		// 一般ユーザー以外は名前で検索を行う
		// 作業者名
		if($l_workuser_name_cond != ""){
			array_push($lr_query_cond, "WORK_USER_NAME like '%".$l_workuser_name_cond."%'");
		}
		$l_workuser_fix_flag	= 'N';
	}
	
	// カラム設定(出力単位がSTAFFなら作業者で集約、WORKなら作業者+作業で集約)
	if($l_output_unit == "STAFF"){
		$lr_select_columns = array('DATA_ID', 'WORK_DATE_YM', 'WORK_DIVISION', 'WORK_USER_COMPANY_ID', 'WORK_USER_COMPANY_NAME', 'WORK_USER_ID', 'WORK_USER_NAME');
		$lr_order_by = array('WORK_DATE_YM desc', 'WORK_USER_NAME');
		$lr_group_by = array('WORK_DATE_YM', 'WORK_USER_NAME');
		$l_workname_display_flag = "N";
	}else{
		$lr_select_columns = array('DATA_ID', 'WORK_DATE_YM', 'WORK_NAME', 'ESTIMATE_ID', 'WORK_DIVISION', 'WORK_USER_COMPANY_ID', 'WORK_USER_COMPANY_NAME', 'WORK_USER_ID', 'WORK_USER_NAME');
		$lr_order_by = array('WORK_DATE_YM desc', 'WORK_NAME', 'WORK_USER_NAME');
		$lr_group_by = array('WORK_DATE_YM', 'WORK_NAME', 'WORK_USER_NAME');
		$l_workname_display_flag = "Y";
	}
	
	// レコード取得
	$lc_model = new m_attendance('Y', $lr_select_columns, $lr_query_cond, $lr_order_by, $lr_group_by);
	$lr_query_records = $lc_model->getViewRecord();
	//print_r($lr_query_records);
	if($l_debug_mode==1){print("Step-DBquery_group");print "<br>";}
	
	// ページ分割したデータを取得
	require_once('../lib/PagedData.php');
	$lr_pd = new PagedData($lr_query_records, 'Y', 8);
	
	// 表示対象分のデータを抽出
	$lr_show_records = $lr_pd->pickPageRecord($l_show_page);
	//print_r($lr_show_records);
	
	// レコード数
	$l_record_cnt = $lr_pd->getRecCount();
	
	// 総ページ数
	$l_max_page = $lr_pd->getPageCount();
	
	// 前のページのレコード数
	$l_prevpage_cnt = $lr_pd->getPrevRecCount($l_show_page);
	
	// 次のページのレコード数
	$l_nextpage_cnt = $lr_pd->getNextRecCount($l_show_page);
	
	if($l_debug_mode==1){print("Step-ページ分割したデータ取得_group");print "<br>";}
	
	
	/*==============================
		リスト用データ
	  ==============================*/
	// 共通マスタ
	require_once('../mdl/m_common_master.php');
	$lc_mcm = new m_common_master();
	
	// 出力単位
	$lr_output_unit = $lc_mcm->getCommonValueRec($l_data_id, "ATTENDANCE_OUTPUT_UNIT");
	
	// 丸め基準時間
	$lr_round_base = $lc_mcm->getCommonValueRec($l_data_id, "FRACTION_UNIT");
	
	// 丸め方法
	$lr_round_method = $lc_mcm->getCommonValueRec($l_data_id, "ROUNDING_STATUS");
	
	if($l_debug_mode==1){print("Step-DBデータ取得_list");print "<br>";}
	
	// ------------------------------
	// 明細表示用のデータ取得
	// ------------------------------
	if($l_work_date_ym != "" && $l_work_user_id != ""){
		// 作業年月と作業者IDがPOSTされている場合は勤務表を表示する
		// 検索条件設定
		$lr_query_cond_dtl = array("DATA_ID = ".$l_data_id);
		array_push($lr_query_cond_dtl, "WORK_DATE_YM = '".$l_work_date_ym."'");	// 年月
		array_push($lr_query_cond_dtl, "WORK_USER_ID = ".$l_work_user_id);		// 作業者ID
		array_push($lr_query_cond_dtl, "WC_VALIDITY_FLAG = 'Y'");				// 有効フラグ
		
		// 出力単位がWORKの場合はさらに見積IDの条件を追加する
		if($l_output_unit == 'WORK'){
			array_push($lr_query_cond_dtl, "ESTIMATE_ID = ".$l_estimate_id);
		}
		
		// レコード取得
		$lc_model_dtl = new m_attendance('Y', null, $lr_query_cond_dtl, 'WORK_DATE');
		$lr_detail_rec = $lc_model_dtl->getViewRecord();
		//var_dump($lr_detail_rec);
	}else{
		// POSTされたグループIDがない場合は、レコードを取得しない
		$l_show_dtl_group_id	= "";
		$lr_user_detail			= "";
	}
	if($l_debug_mode==1){print("Step-DBデータ取得_group");print "<br>";}
	
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
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
	$lc_smarty->assign("headtitle"		,"勤務表出力");					// 画面タイトル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	
	// ------------------------------
	// リストメニュー
	// ------------------------------
	// タイトル
	$lc_smarty->assign("list_title"			, "勤務表");
	
	// データレコード
	$lc_smarty->assign("ar_list_menu"	, $lr_show_records);
	
	// ボタン操作部
	if(count($lr_show_records) > 0){
		$lc_smarty->assign("pageitem_visible"	,"ON");
		$lc_smarty->assign("rec_count"			,$l_record_cnt);
		$lc_smarty->assign("show_page"			,$l_show_page);
		$lc_smarty->assign("page_count"			,$l_max_page);
		// 前のページボタン
		if($l_prevpage_cnt > 0){
			$lc_smarty->assign("prevbtn_visible"	,"ON");
			$lc_smarty->assign("prev_btn_value"		,"前の".$l_prevpage_cnt."件");
		}
		// 次のページボタン
		if($l_nextpage_cnt > 0){
			$lc_smarty->assign("nextbtn_visible"	,"ON");
			$lc_smarty->assign("next_btn_value"		,"次の".$l_nextpage_cnt."件");
		}
	}
	
	// ------------------------------
	// 明細項目
	// ------------------------------
	// タイトル
	$lc_smarty->assign("detail_title"		, "勤務表");
	
	if($l_debug_mode==1){print("Step-明細項目-タイトル");print "<br>";}	
	
	// 各種表示用の値を設定
	$l_detail_title_year		= mb_substr($l_work_date_ym, 0, 4);
	$l_detail_title_month		= mb_substr($l_work_date_ym, 5, 2);
	$l_detail_title_ym			= $l_detail_title_year. "年". $l_detail_title_month. "月";
	$l_detail_wuser_name		= $lr_detail_rec[1]['WORK_USER_NAME'];
	$l_detail_wuser_comp_name	= $lr_detail_rec[1]['WORK_USER_COMPANY_NAME'];
	
	// アサイン
	$lc_smarty->assign("title_ym"				, $l_detail_title_ym);
	$lc_smarty->assign("workuser_name"			, $l_detail_wuser_name);
	$lc_smarty->assign("workuser_company_name"	, $l_detail_wuser_comp_name);
	
	
	/*--------------------------
		勤務表用の配列を再作成
	  --------------------------*/
	// 日付を取得
	require_once('../lib/CommonDate.php');
	$lc_commond = new CommonDate();
	
	// 基準時間、丸め方法のセット
	$lc_commond->setBaseTime($l_round_base);
	$lc_commond->setRoundType($l_round_method);
	if($l_debug_mode==1){print("Step-明細項目-基準時間、丸め方法");print "<br>";}	
	
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
	if($l_debug_mode==1){print("Step-明細項目-合計用変数");print "<br>";}	
	
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
	if($l_debug_mode==1){print("Step-明細項目-明細完了");print "<br>";}	
	
	// 補足
	$l_base_method_notice = "※実働時間、及び残業時間は、"
							.$lr_round_base[$l_round_base]
							."分単位に"
							.$lr_round_method[$l_round_method]
							."処理を行っています。";
	
	// 合計値の0.00化
	$l_total_break_time		= number_format($l_total_break_time, 2, '.', '');
	$l_total_working_hours	= number_format($l_total_working_hours, 2, '.', '');
	$l_total_overtime_hours	= number_format($l_total_overtime_hours, 2, '.', '');
	
	// アサイン
	$lc_smarty->assign("ar_attendance_detail"	, $lr_attendance_array);	// 明細
	$lc_smarty->assign("total_break_time"		, $l_total_break_time);		// 休憩時間合計
	$lc_smarty->assign("total_working_hours"	, $l_total_working_hours);	// 実働時間合計
	$lc_smarty->assign("total_overtime_hours"	, $l_total_overtime_hours);	// 残業時間合計
	$lc_smarty->assign("base_method_notice"		, $l_base_method_notice);	// 補足
	
	// htmlを作成
	$l_attendance_sheet_html = $lc_smarty->fetch('attendance_sheet_pdf.tpl');
	//$l_attendance_sheet_html = $lc_smarty->display('attendance_sheet_pdf.tpl');
	if($l_debug_mode==1){print("Step-明細項目-htmlを作成");print "<br>";}	
	
	//print_r($l_attendance_sheet_html);
	//print $l_attendance_sheet_html;
	/* PDF出力 */
		/* ライブラリをインクルードする(TCPDFをインストールしたパスを指定する) */
		require_once('../tcpdf/config/lang/jpn.php');
		require_once('../tcpdf/tcpdf.php');
		
		/* PDF オブジェクトを作成し、以降の処理で操作します */
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
		//$pdf = new TCPDF('P', 'mm', 'A4', true); 
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new TCPDF('P', 'mm', 'A3', true);
		
		// ヘッダ画像
		//$pdf->SetHeaderData('zenklog_half.jpg');
		
		// ページの上部と左右の余白
		$pdf->SetMargins(5, 10, 5);
		// ページの上部余白位置からヘッダーまでの高さ
		$pdf->setHeaderMargin(10);
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	SetFontパラメータ																		//
	//////////////////////////////////////////////////////////////////////////////////////////////
		$family	AddFont()で追加したフォント名もしくは以下の標準フォント:
				* times (Times-Roman)
				* timesb (Times-Bol)
				* timesi (Times-Italic)
				* timesbi (Times-BoldItalic)
				* helvetica (Helvetica)
				* helveticab (Helvetica-Bold)
				* helveticai (Helvetica-Oblique)
				* helveticabi (Helvetica-BoldOblique)
				* courier (Courier)
				* courierb (Courier-Bold)
				* courieri (Courier-Oblique)
				* courierbi (Courier-BoldOblique)
				* symbol (Symbol)
				* zapfdingbats (ZapfDingbats)
		''空文字を指定するとこれまで使用していたフォントが使われる。
		$style	フォント・スタイル:
				* 空文字: regular
				* B: ボールド
				* I: イタリック
				* U: アンダーライン
				* D: 取り消し
		もしくは、上記の組み合わせ。既定値は'標準'、また'Symbol'か'ZapfDingbats'フォントを選択した場合、ボールドとイタリックは無効。
		$size	フォントサイズ(ポイント数)、省略時は現在までのフォントサイズ、ドキュメント開始時点では12pt。
		$fontfile	フォント定義ファイルを指定、フォント名とフォントスタイルから規定される名称。
	--------------------------------------------------------------------------------------------*/
		// フォントをセット
		//$pdf->SetFont('arialunicid0', 'B', 18);
		//$pdf->SetFont('kozgopromedium', '', 18);
		//$pdf->SetFont('arialunicid0', 'B', 10);
		$pdf->SetFont('kozgopromedium');
		
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	AddPageパラメータ																		//
	//////////////////////////////////////////////////////////////////////////////////////////////
		$orientation	用紙方向 (P or PORTRAIT(縦:既定) | L or LANDSCAPE(横))
		$format	用紙フォーマット、以下のいずれか。
				[ 4A0 | 2A0 | A0 | A1 | A2 | A3 | A4(既定) | A5 | A6 | A7 | A8 | A9 | A10
				| B0 | B1 | B2 | B3 | B4 | B5 | B6 | B7 | B8 | B9 | B10
				| C0 | C1 | C2 | C3 | C4 | C5 | C6 | C7 | C8 | C9 | C10
				| RA0 | RA2 | RA3 | RA4 | SRA0 | SRA1 | SRA2 | SRA3 | SRA4
				| LETTER | LEGAL | EXECUTIVE | FOLIO ]
				またカスタムページサイズの場合はheightとwidthの配列を指定。
	--------------------------------------------------------------------------------------------*/
		/* 1ページ目を準備します */
		$pdf->AddPage('L','A3');
		
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	Cellパラメータ																			//
	//////////////////////////////////////////////////////////////////////////////////////////////
		$w		矩形領域の幅
		$h		矩形領域の高さ
		$txt	印字するテキスト
		$border	境界線で囲むか否かを指定する。
				* 0: 境界線なし(既定)
				* 1: 枠で囲む
				* L: 左
				* T: 上
				* R: 右
				* B: 下
		$ln	出力後のカーソルの移動方法を指定する:
				* 0: 右へ移動(既定)、但しアラビア語などRTLの場合は左へ移動
				* 1: 次の行へ移動
				* 2: 下へ移動
		$align	テキストの整列を以下のいずれかで指定する
				* L or 空文字: 左揃え(既定)
				* C: 中央揃え
				* R: 右揃え
				* J: 両端揃え
		$fill	矩形領域の塗つぶし指定 [0:透明(既定) 1:塗つぶす]
		$link	登録するリンク先のURL、もしくはAddLink()で作成したドキュメント内でのリンク
		$stretch	テキストの伸縮(ストレッチ)モード:
				* 0 = なし
				* 1 = 必要に応じて水平伸縮
				* 2 = 水平伸縮
				* 3 = 必要に応じてスペース埋め
				* 4 = スペース埋め
		$ignore_min_height	「true」とすると矩形領域の高さの最小値調整をしない
	--------------------------------------------------------------------------------------------*/
		//$titlename		=	$dt_year."年".$dt_month."月度　勤務実績表";
		
		/* 文字列を出力します */
		//$pdf->Cell(0,0,$titlename,'B',1,'C',0);
		
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	Lnパラメータ																			//
	//////////////////////////////////////////////////////////////////////////////////////////////
		$h	改行する高さ、既定では直近で処理したセルの高さ。
		$cell	trueとすると、次の行の左端からcMarginだけカーソルを右に移動する。
	--------------------------------------------------------------------------------------------*/
		/* 改行します */
		//$pdf->Ln(3);
		
		
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	writeHTMLパラメータ																		//
	//////////////////////////////////////////////////////////////////////////////////////////////
			$html	出力するHTMLテキスト
			$ln		改行する場合true
			$fill	背景の塗つぶし指定 [0:透明(既定) 1:塗つぶす]
			$reseth	前回の高さ設定をリセットする場合はtrue、引き継ぐ場合はfalse
			$cell	trueとすると各行にcMargin分のスペースを自動挿入する
			$align	テキストの整列を以下のいずれかで指定する
				* L : 左端
				* C : 中央
				* R : 右端
				* '' : 空文字 : 左端(RTLの場合は右端)
	--------------------------------------------------------------------------------------------*/
		//$pdf->writeHTML($l_attendance_sheet_html, false, false, false, false, '');
		$pdf->writeHTML($l_attendance_sheet_html, false, false, true, false, '');
		//$pdf->writeHTML("Hello world", false, false, true, false, '');
		
	/*--------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////
	//	Outputパラメータ																			//
	//////////////////////////////////////////////////////////////////////////////////////////////
		
		$name	保存時のファイル名、特殊文字は適宜'_'(アンダースコア)に置換される。
		$dest	ドキュメントの出力先を指定、以下のいずれかを指定。:
				* I: ブラウザに出力する(既定)、保存時のファイル名が$nameで指定した名前になる。
				* D: ブラウザで(強制的に)ダウンロードする。
				* F: ローカルファイルとして保存する。
				* S: PDFドキュメントの内容を文字列として出力する。
	--------------------------------------------------------------------------------------------*/
		/* PDF を出力します */
		$pdf->Output("attendance_sheet.pdf", "D");
		
?>