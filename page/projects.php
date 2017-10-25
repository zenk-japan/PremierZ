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
 ファイル名：projects.php
 処理概要   プロジェクト管理画面
 POST受領値：
            nm_token_code              トークン(必須)
            nm_valid_checkstat         有効データチェック状態(任意)
            nm_show_page               表示中の明細ページ番号(任意)
            nm_estimate_code           見積コード(任意)
            nm_work_name               作業名(任意)
            nm_request_company_name    依頼元会社(任意)
            nm_enduser_company_name    エンドユーザー会社(任意)
            nm_estimate_user_name      見積担当者(任意)
            nm_order_division          注文区分(任意)
            nm_work_division           作業区分(任意)
            nm_cal_yyyy                カレンダーの年(任意)
            nm_cal_mm                  カレンダーの月(任意)
            nm_cond_cal_dd             カレンダーの日(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
		print "session-><br>";
		session_start();
		print var_dump($_SESSION);
		print "<br>";
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "PROJECTS";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$lr_estimate_cond		= "";									// 検索条件
	$lr_estimate_order		= "";									// 整列条件
	$l_show_record_cnt		= 12;									// 明細の表示件数
	$lr_estimate			= "";									// 見積レコード
	$l_valid_checkstat		= "";									// 有効フラグ
	$l_show_page			= "";									// 表示中の明細ページ番号
	$l_estimate_code		= "";									// 見積コード
	$l_work_name			= "";									// 作業名
	$l_request_company_name	= "";									// 依頼元会社
	$l_enduser_company_name	= "";									// エンドユーザー会社
	$l_estimate_user_name	= "";									// 見積担当者
	$l_order_division		= "";									// 注文区分
	$l_work_division		= "";									// 作業区分
	$l_cal_yyyy				= "";									// カレンダーの年
	$l_cal_mm  				= "";									// カレンダーの月
	$l_cal_dd  				= "";									// カレンダーの日
	$l_sche_cond_from		= "";									// 日付検索条件From
	$l_sche_cond_to			= "";									// 日付検索条件To
	$l_cal_cond_mess		= "";									// 画面表示用の検索日付
	$l_cal_cond_mess_pre	= " を作業予定期間に含むプロジェクト";	// 画面表示用のメッセージ
	$l_cal_cond_mess_no		= "作業予定期間の設定がないプロジェクト";	// 画面表示用のメッセージ

/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_projectmnt(Exception $e){
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
	set_exception_handler('my_exception_projectmnt');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();

	// POSTされたトークンを取得
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}

	// セッションからトークンを取得
	$l_sess_token = $lc_sess->getToken();
	//$l_sess_token = $lc_sess->setToken();
	//print var_dump($_SESSION);

	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}

	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}

	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');

	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		throw new Exception($l_error_type_st);
	}

	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	// 検索条件をセッションにセット
	//print_r($_SERVER);
	$lc_sess->setSesseionItem($l_page_name."-COND", $_POST);
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	// 有効データチェック状態
	$l_valid_checkstat = $_POST['nm_valid_checkstat'];
	if(is_null($l_valid_checkstat)){
		$l_valid_checkstat = 'Y';
	}

	// 表示ページ番号
	$l_show_page = $_POST['nm_show_page'];
	if(is_null($l_show_page)){
		$l_show_page = 1;
	}

	// 見積コード
	$l_estimate_code = $_POST['nm_estimate_code'];

	// 作業名
	$l_work_name = $_POST['nm_work_name'];

	// 依頼元会社
	$l_request_company_name = $_POST['nm_request_company_name'];

	// エンドユーザー会社
	$l_enduser_company_name = $_POST['nm_enduser_company_name'];

	// 見積担当者
	$l_estimate_user_name = $_POST['nm_estimate_user_name'];

	// 注文区分
	$l_order_division = $_POST['nm_order_division'];

	// 作業区分
	$l_work_division = $_POST['nm_work_division'];
	
	// 作業予定期間の検索条件
	$l_cal_dd = $_POST['nm_cond_cal_dd'];
	
	// 年月日
	// 本日の値を取得
	require_once('../lib/CommonDate.php');
	$lc_commondate = new CommonDate();
	
	$l_today_yyyy	= $lc_commondate->getTodayY();
	$l_today_mm		= $lc_commondate->getTodayM();
	
	// POST値に年の指定があればPOST値を使う。なければ本日の年月を使用する。
	$l_cal_yyyy = $_POST['nm_cal_yyyy'];
	if($l_cal_yyyy == ""){
		$l_cal_yyyy			= $l_today_yyyy;
		$l_cal_mm			= $l_today_mm;
	}else{
		$l_cal_mm			= $_POST['nm_cal_mm'];
	}

	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}
/*----------------------------------------------------------------------------
  カレンダー取得
  ----------------------------------------------------------------------------*/
	//$lr_this_month_days		= $lc_commondate->getDays($l_today_yyyy, $l_today_mm);
	$lr_this_month_days		= $lc_commondate->getDaysForCal($l_cal_yyyy, $l_cal_mm, 1);
	//{print "<pre>";var_dump($lr_this_month_days);print "</pre>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// 見積MDL
	require_once('../mdl/m_estimates.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_見積MDL");print "<br>";}

	// ------------------------------
	// 検索条件設定
	// ------------------------------
	// DATA_ID
	$lr_estimate_cond = array('DATA_ID = '.$l_data_id);
	// 有効フラグ
	if($l_valid_checkstat == "Y"){
		array_push($lr_estimate_cond, "VALIDITY_FLAG = 'Y'");
	}
	// 見積コード
	if($l_estimate_code != ""){
		array_push($lr_estimate_cond, "ESTIMATE_CODE like '%".$l_estimate_code."%'");
	}

	// 作業名
	if($l_work_name != ""){
		array_push($lr_estimate_cond, "WORK_NAME like '%".$l_work_name."%'");
	}

	// 依頼元会社
	if($l_request_company_name != ""){
		array_push($lr_estimate_cond, "REQUEST_COMPANY_NAME like '%".$l_request_company_name."%'");
	}

	// エンドユーザー会社
	if($l_enduser_company_name != ""){
		array_push($lr_estimate_cond, "ENDUSER_COMPANY_NAME like '%".$l_enduser_company_name."%'");
	}

	// 見積担当者
	if($l_estimate_user_name != ""){
		array_push($lr_estimate_cond, "ESTIMATE_USER_NAME like '%".$l_estimate_user_name."%'");
	}

	// 注文区分
	if($l_order_division != ""){
		array_push($lr_estimate_cond, "ORDER_DIVISION = '".$l_order_division."'");
	}

	// 作業区分
		if($l_work_division != ""){
			array_push($lr_estimate_cond, "WORK_DIVISION = '".$l_work_division."'");
		}
		
	// 作業期間
	// 日の指定まであれば年月日で指定、無ければ年月で指定
	$l_cond_schedule_from	= "";
	$l_cond_schedule_to		= "";
	if($l_cal_dd != ""){
		// 年月日で指定
		$l_cond_schedule_from	= $l_cal_yyyy."-".$l_cal_mm."-".$l_cal_dd;
		$l_cond_schedule_to		= $l_cal_yyyy."-".$l_cal_mm."-".$l_cal_dd;
		$l_cal_cond_mess		= $l_cond_schedule_from.$l_cal_cond_mess_pre;

	}else{
		// 年月で指定
		$l_cond_schedule_from	= $lc_commondate->getMonthFirstDay($l_cal_yyyy, $l_cal_mm);
		$l_cond_schedule_to		= $lc_commondate->getMonthEndDay($l_cal_yyyy, $l_cal_mm);
		$l_cal_cond_mess		= $l_cal_yyyy."-".$l_cal_mm.$l_cal_cond_mess_pre;
	}
	$l_sche_cond_from		= "(SCHEDULE_FROM_DATE IS NULL or SCHEDULE_FROM_DATE <= DATE('".$l_cond_schedule_to."'))";
	$l_sche_cond_to			= "(SCHEDULE_TO_DATE IS NULL or SCHEDULE_TO_DATE >= DATE('".$l_cond_schedule_from."'))";
	array_push($lr_estimate_cond, $l_sche_cond_from);
	array_push($lr_estimate_cond, $l_sche_cond_to);
	
//print($l_cond_schedule_from." -> ".$l_cond_schedule_to);
//{print "<pre>";var_dump($lr_estimate_cond);print "</pre>";}
	if($l_debug_mode==1){print("Step-DBデータ取得_検索条件設定");print "<br>";}
	// ------------------------------
	// 整列設定
	// ------------------------------
	$lr_estimate_order = array('SCHEDULE_FROM_DATE', 'ESTIMATE_CODE');
	if($l_debug_mode==1){print("Step-DBデータ取得_整列設定");print "<br>";}
	// ------------------------------
	// レコード取得
	// ------------------------------
	$lc_estimate = new m_estimates('Y', $lr_estimate_cond, $lr_estimate_order);
	$lr_estimate = $lc_estimate->getViewRecord();
	//print_r($lr_estimate);
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}

	// ------------------------------
	// ページ分割したデータを取得
	// ------------------------------
	require_once('../lib/PagedData.php');
	$lr_pd = new PagedData($lr_estimate, 'Y', $l_show_record_cnt);

	// 表示対象分のデータを抽出
	$lr_show_estimate = $lr_pd->pickPageRecord($l_show_page);
	//print_r($lr_show_estimate);

	// ページ選択用SELECT
	$l_page_select_html = $lr_pd->getPageSelectHtml($l_show_page);

	// レコード数
	$l_company_cnt = $lr_pd->getRecCount();

	// 総ページ数
	$l_max_page = $lr_pd->getPageCount();

	// 前のページのレコード数
	$l_prevpage_cnt = $lr_pd->getPrevRecCount($l_show_page);

	// 次のページのレコード数
	$l_nextpage_cnt = $lr_pd->getNextRecCount($l_show_page);

	if($l_debug_mode==1){print("Step-ページ分割したデータを取得");print "<br>";}
	// ------------------------------
	// 共通マスター取得
	// ------------------------------
	require_once('../mdl/m_common_master.php');
	$lr_common = new m_common_master();

	// 注文区分
	$lr_order_division	= $lr_common->getCommonValueRec($l_data_id, "ORDER_DIVISION");
	// 作業区分
	$lr_work_division	= $lr_common->getCommonValueRec($l_data_id, "WORK_DIVISION");

//{print "<pre>";var_dump($lr_order_division);print "</pre>";}
//{print "<pre>";var_dump($lr_work_division);print "</pre>";}
	if($l_debug_mode==1){print("Step-共通マスター取得");print "<br>";}

/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";

	if($l_debug_mode==1){print("Step-変数定義&セット");print "<br>";}
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
	$ar_css_files	= array(DIR_CSS."v_top_block.css",
							DIR_CSS."v_projects_menu_block.css", 
							DIR_CSS."v_projects_common.css", 
							DIR_CSS."v_projects_main.css", 
							DIR_CSS."v_projects_detail.css", 
							DIR_CSS."v_projects_search.css", 
							DIR_CSS."v_projects_calendar.css", 
							DIR_CSS."gb_styles.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_projects_common.js", 
							DIR_JS."jfnc_projects_top.js", 
							DIR_JS."jfnc_projects_search.js", 
							DIR_JS."jfnc_projects_calendar.js", 
							DIR_JS."jfnc_projects_detail.js", 
							DIR_JS."greybox.js", 
							DIR_JS."greybox/AJS.js", 
							DIR_JS."greybox/AJS_fx.js", 
							DIR_JS."greybox/gb_scripts.js"
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
	$lc_smarty->assign("main_include_tpl"	,"projects_menu_block.tpl");			// メインメニュー
	$lc_smarty->assign("search_include_tpl"	,"projects_search_menu_block.tpl");		// 検索メニュー
	$lc_smarty->assign("list_include_tpl"	,"projects_calendar_block.tpl");		// リスト(カレンダー)
	$lc_smarty->assign("detail_include_tpl"	,"projects_detail_block.tpl");			// 明細

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"プロジェクト管理");			// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_auth"		,$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名

	// ------------------------------
	// 共通設定
	// ------------------------------
	$lc_smarty->assign("now_page",		$l_page_name);					// 現在のページ

	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);

	// ------------------------------
	// 検索部
	// ------------------------------
	// 注文区分リスト
	$lc_smarty->assign("ar_order_division", 		$lr_order_division);
	// 作業区分リスト
	$lc_smarty->assign("ar_work_division", 			$lr_work_division);

	// 見積コード
	$lc_smarty->assign("estimate_code", 			htmlspecialchars($l_estimate_code));
	// 作業名
	$lc_smarty->assign("work_name", 				htmlspecialchars($l_work_name));
	// 依頼元会社
	$lc_smarty->assign("request_company_name", 		htmlspecialchars($l_request_company_name));
	// エンドユーザー会社
	$lc_smarty->assign("enduser_company_name", 		htmlspecialchars($l_enduser_company_name));
	// 見積担当者
	$lc_smarty->assign("estimate_user_name", 		htmlspecialchars($l_estimate_user_name));
	// 注文区分
	$lc_smarty->assign("selected_order_devision", 	$l_order_division);
	// 作業区分
	$lc_smarty->assign("selected_work_devision", 	$l_work_division);
	// ------------------------------
	// カレンダー部
	// ------------------------------
	// 上部メッセージ
	$lc_smarty->assign("calendar_top_mess", 	"作業予定期間による絞込み");
	// 年月
	$lc_smarty->assign("cal_yyyy", 				$l_cal_yyyy);
	$lc_smarty->assign("cal_mm", 				$l_cal_mm);
	// 各週
	$lc_smarty->assign("cal_week1", 			$lr_this_month_days[1]);
	$lc_smarty->assign("cal_week2", 			$lr_this_month_days[2]);
	$lc_smarty->assign("cal_week3", 			$lr_this_month_days[3]);
	$lc_smarty->assign("cal_week4", 			$lr_this_month_days[4]);
	$lc_smarty->assign("cal_week5", 			$lr_this_month_days[5]);
	$lc_smarty->assign("cal_week6", 			$lr_this_month_days[6]);
	// 下部メッセージ
	$lc_smarty->assign("cal_cond_mess", 		$l_cal_cond_mess);
	
	// ------------------------------
	// 明細部
	// ------------------------------
	// データレコード
	$lc_smarty->assign("ar_prj_detail"	,$lr_show_estimate);					// 明細レコード

	// ボタン操作部
	if(count($lr_show_estimate) > 0){
		$lc_smarty->assign("prj_pageitem_visible"	,"ON");
		$lc_smarty->assign("prj_page_select_html"	,$l_page_select_html);
		$lc_smarty->assign("prj_rec_count"			,$l_company_cnt);

		// 前のページボタン
		if($l_prevpage_cnt > 0){
			$lc_smarty->assign("prj_prev_btn_visible"	,"ON");
			$lc_smarty->assign("prj_prev_btn_value"		,"前の".$l_prevpage_cnt."件");
		}
		// 次のページボタン
		if($l_nextpage_cnt > 0){
			$lc_smarty->assign("prj_next_btn_visible"	,"ON");
			$lc_smarty->assign("prj_next_btn_value"		,"次の".$l_nextpage_cnt."件");
		}
	}
	// 有効データのみ表示チェックの設定
	$lc_smarty->assign("valid_prj_checkstat"	, $l_valid_checkstat);

	// ------------------------------
	// 隠し項目
	// ------------------------------
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// 有効データチェック状態
								  "name"	=> "nm_valid_checkstat"
								, "value"	=> $l_valid_checkstat
								),
							array(									// 表示ページ番号
								  "name"	=> "nm_show_page"
								, "value"	=> $l_show_page
								),
							array(									// 見積コード
								  "name"	=> "nm_estimate_code"
								, "value"	=> $l_estimate_code
								),
							array(									// 作業名
								  "name"	=> "nm_work_name"
								, "value"	=> $l_work_name
								),
							array(									// 依頼元会社
								  "name"	=> "nm_request_company_name"
								, "value"	=> $l_request_company_name
								),
							array(									// エンドユーザー会社
								  "name"	=> "nm_enduser_company_name"
								, "value"	=> $l_enduser_company_name
								),
							array(									// 見積担当者
								  "name"	=> "nm_estimate_user_name"
								, "value"	=> $l_estimate_user_name
								),
							array(									// 注文区分
								  "name"	=> "nm_order_division"
								, "value"	=> $l_order_division
								),
							array(									// 作業区分
								  "name"	=> "nm_work_division"
								, "value"	=> $l_work_division
								),
							array(									// カレンダーの年
								  "name"	=> "nm_cal_yyyy"
								, "value"	=> $l_cal_yyyy
								),
							array(									// カレンダーの月
								  "name"	=> "nm_cal_mm"
								, "value"	=> $l_cal_mm
								),
							array(									// カレンダーの日
								  "name"	=> "nm_cond_cal_dd"
								, "value"	=> $l_cal_dd
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('projects_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>