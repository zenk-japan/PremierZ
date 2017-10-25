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
 ファイル名：attendance_report.php
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
	
	// 権限名の取得
	$l_authority_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
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
	$l_work_ym_cond = $_POST['nm_work_ym_cond'];
	if($l_work_ym_cond == ""){
		$l_work_ym_cond = date("Y-m");
	}
	
	// 作業名(検索用)
	$l_work_name_cond = $_POST['nm_work_name_cond'];
	
	// 作業者名(検索用)
	$l_workuser_name_cond = $_POST['nm_workuser_name_cond'];
	
	// 出力単位
	$l_output_unit= $_POST['nm_output_unit'];
	if($l_output_unit == ""){
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_output_unit = ATTENDANCE_OUTPUT_UNIT_DEFAULT;
	}
	
	// 丸め基準時間
	$l_round_base= $_POST['nm_round_base'];
	if($l_round_base == ""){
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_round_base = BASE_TIME_DEFAULT."M";
	}
	
	// 丸め方法
	$l_round_method = $_POST['nm_round_method'];
	if($l_round_method == ""){
		// POST値が存在しなかった場合は、所定の初期値とする
		$l_round_method = ROUND_METHOD_DEFAULT;
	}
	
	// 作業年月
	$l_work_date_ym = trim($_POST['nm_work_date_ym']);
	
	// 作業者ID
	$l_work_user_id = $_POST['nm_work_user_id'];
	
	// 見積ID
	$l_estimate_id = $_POST['nm_estimate_id'];
	
	// 表示ページ番号
	$l_show_page = $_POST['nm_show_page'];
	if($l_show_page == ""){
		$l_show_page = 1;
	}
	
	// 最大ページ番号
	$l_max_page = $_POST['nm_max_page'];
	if($l_max_page == ""){
		$l_max_page = 1;
	}
	
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*==============================
		作業
	  ==============================*/
	// 勤務表MDL
	require_once('../mdl/m_attendance.php');
	
	// ------------------------------
	// リスト表示用のデータ取得
	// ------------------------------
	// 検索条件設定
	$lr_query_cond = array("DATA_ID = ".$l_data_id);
	array_push($lr_query_cond, "WS_VALIDITY_FLAG = 'Y'");
	
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
//{print "<pre>";var_dump($lr_query_records);print "</pre>";}
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
//{print "<pre>";var_dump($lr_detail_rec);print "</pre>";}
	}else{
		// 年月と作業者IDがPOSTされない場合は、レコードを取得しない
		$l_show_dtl_group_id	= "";
		$lr_user_detail			= "";
	}
	if($l_debug_mode==1){print("Step-DBデータ取得_group");print "<br>";}
	
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
							DIR_CSS."v_top_block.css", 
							DIR_CSS."v_report_masters.css", 
							DIR_CSS."v_report_menu_block.css", 
							DIR_CSS."v_sub_menu_block.css", 
							DIR_CSS."v_attendance_search_menu_block.css", 
							DIR_CSS."v_attendance_detail_block.css", 
							DIR_CSS."v_attendance_list_block.css", 
							DIR_CSS."v_valuelist_div.css", 
							DIR_CSS."v_calendar_div.css", 
							DIR_CSS."v_attendance_sheet.css"
							);
	// jsファイル
	$ar_js_files	= array(
							DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_calendar.js", 
							DIR_JS."jfnc_value_list.js", 
							DIR_JS."jfnc_report_top.js", 
							DIR_JS."jfnc_group_main_menu.js", 
							DIR_JS."jfnc_attendance_search_menu.js", 
							DIR_JS."jfnc_attendance_list.js", 
							DIR_JS."jfnc_group_detail.js", 
							DIR_JS."jfnc_attendance_report.js"
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
	// インクルードするテンプレート
	// ------------------------------
	$lc_smarty->assign("top_include_tpl"	,"top_block.tpl");						// トップ
	$lc_smarty->assign("main_include_tpl"	,"report_menu_block.tpl");				// メインメニュー
	//$lc_smarty->assign("sub_include_tpl"	,"report_sub_menu_block.tpl");			// サブメニュー
	$lc_smarty->assign("search_include_tpl"	,"attendance_search_menu_block.tpl");	// 検索メニュー
	$lc_smarty->assign("list_include_tpl"	,"attendance_list_block.tpl");			// リスト
	$lc_smarty->assign("detail_include_tpl"	,"attendance_detail_block.tpl");		// 明細
	
	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"勤務表出力");					// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_auth"		,$l_authority_name);			// ユーザー権限
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名
	
	// ------------------------------
	// トップメニュー
	// ------------------------------
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	
	// ------------------------------
	// タブ
	// ------------------------------
	$lc_smarty->assign("now_page",		"ATTENDANCE");					// 現在のページ
	
	// ------------------------------
	// 検索条件
	// ------------------------------
	// タイトル
	$lc_smarty->assign("cond_title"			, "作業検索");
	$lc_smarty->assign("cond_work_ym"		,$l_work_ym_cond);				// 作業年月(検索用)
	$lc_smarty->assign("cond_work_name"		,$l_work_name_cond);			// 作業名(検索用)
	$lc_smarty->assign("cond_workuser_name"	,$l_workuser_name_cond);		// 作業者名(検索用)
	$lc_smarty->assign("workuser_fix_flag"	,$l_workuser_fix_flag);			// 作業者名固定フラグ
	$lc_smarty->assign("output_unit_mode"	,$l_output_unit);				// 出力設定
	
	// ------------------------------
	// 出力設定
	// ------------------------------
	$lc_smarty->assign("output_style_title"		, "出力設定");
	$lc_smarty->assign("ar_output_unit"			, $lr_output_unit);
	$lc_smarty->assign("output_unit_default"	, $l_output_unit);
	$lc_smarty->assign("ar_round_base"			, $lr_round_base);
	$lc_smarty->assign("round_base_default"		, $l_round_base);
	$lc_smarty->assign("ar_round_method"		, $lr_round_method);
	$lc_smarty->assign("round_method_default"	, $l_round_method);
	
	
	// ------------------------------
	// リストメニュー
	// ------------------------------
	// タイトル
	$lc_smarty->assign("list_title"			, "作業一覧");
	
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
	
	// 作業名表示の設定
	$lc_smarty->assign("workname_display_flag"	, 	$l_workname_display_flag);
	
	// コピーライト
	$lc_smarty->assign("txt_copyright"	, 	"<font size=\"1\">".COPY_RIGHT_PHRASE."</font>");
	
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
	$l_attendance_sheet_html = $lc_smarty_detail->fetch('attendance_sheet.tpl');
	//$l_attendance_sheet_html = "test";
	
	// htmlをセット
	$lc_smarty->assign("detail_html"		, $l_attendance_sheet_html);
	//}
	
	$l_workcontent_id		= $lr_detail_rec[1]['WORK_CONTENT_ID'];
	$l_detail_wuser_name	= $lr_detail_rec[1]['WORK_USER_NAME'];
	$l_estimate_id			= $lr_detail_rec[1]['ESTIMATE_ID'];
	$l_work_name			= $lr_detail_rec[1]['WORK_NAME'];
	// ------------------------------
	// PDF出力用の隠し項目
	// ------------------------------
	$lc_smarty->assign("data_id",			$l_data_id);
	$lc_smarty->assign("loginuser_id",		$l_sess_user_id);
	$lc_smarty->assign("estimate_id",		$l_estimate_id);
	$lc_smarty->assign("workuser_id",		$l_work_user_id);
	$lc_smarty->assign("workcontent_id",	$l_workcontent_id);
	$lc_smarty->assign("work_date",			$l_work_date_ym);
	$lc_smarty->assign("base_time",			$l_round_base);
	$lc_smarty->assign("round_type",		$l_round_method);
	$lc_smarty->assign("work_name",			$l_work_name);
	
	
	
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// 作業年月(検索用)
								  "name"	=> "nm_work_ym_cond"
								, "value"	=> $l_work_ym_cond
								),
							array(									// 作業名(検索用)
								  "name"	=> "nm_work_name_cond"
								, "value"	=> $l_work_name_cond
								),
							array(									// 作業者(検索用)
								  "name"	=> "nm_workuser_name_cond"
								, "value"	=> $l_workuser_name_cond
								),
							array(									// 出力単位
								  "name"	=> "nm_output_unit"
								, "value"	=> $l_output_unit
								),
							array(									// 丸め基準時間
								  "name"	=> "nm_round_base"
								, "value"	=> $l_round_base
								),
							array(									// 丸め方法
								  "name"	=> "nm_round_method"
								, "value"	=> $l_round_method
								),
							array(									// 作業年月
								  "name"	=> "nm_work_date_ym"
								, "value"	=> $l_work_date_ym
								),
							array(									// 作業者ID
								  "name"	=> "nm_work_user_id"
								, "value"	=> $l_work_user_id
								),
							array(									// 見積ID
								  "name"	=> "nm_estimate_id"
								, "value"	=> $l_estimate_id
								),
							array(									// 表示ページ番号
								  "name"	=> "nm_show_page"
								, "value"	=> $l_show_page
								),
							array(									// 最大ページ番号
								  "name"	=> "nm_max_page"
								, "value"	=> $l_max_page
								),
							array(									// グループID
								  "name"	=> "nm_selected_group_id"
								, "value"	=> $l_show_dtl_group_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
	
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('master_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>