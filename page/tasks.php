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
 ファイル名：tasks.php
 処理概要   作業管理画面
 POST受領値：
            nm_token_code              	トークン(必須)
            nm_selected_estimate_id    	プロジェクト画面で選択された見積ID(必須)
            nm_valid_checkstat         	有効データチェック状態(任意)
            nm_show_page               	表示中の明細ページ番号(任意)
            nm_work_cal_yyyy			カレンダーの年(任意)
            nm_work_cal_mm				カレンダーの月(任意)
            nm_work_cal_dd				カレンダーの日(任意)
            nm_work_content_code		作業コード(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print "<pre>";
		var_dump($_POST);
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
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "TASKS";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$lr_estimate_cond		= "";									// 見積検索条件
	$lr_workcontents_cond	= "";									// 作業検索条件
	$l_show_record_cnt		= 10;									// 明細の表示件数
	$lr_estimate			= "";									// 見積レコード
	$l_valid_checkstat		= "";									// 有効フラグ
	$l_show_page			= "";									// 表示中の明細ページ番号
	$l_selected_estimate_id	= "";									// プロジェクト画面で選択された見積ID
	$l_work_cal_yyyy		= "";									// 年
	$l_work_cal_mm			= "";									// 月
	$l_work_cal_dd			= "";									// 日
	$lr_work_content_code	= array();								// 作業コード用配列
	$l_work_content_code	= "";									// POST値用作業コード

/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_taskmnt(Exception $e){
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
	set_exception_handler('my_exception_taskmnt');

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

	// 見積IDを取得
	$l_selected_estimate_id = $_POST['nm_selected_estimate_id'];
	if($l_selected_estimate_id == ""){
		$l_selected_estimate_id = $_POST['ESTIMATE_ID']; // 編集画面から戻ってきた場合
	}
	if($l_selected_estimate_id == ""){
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
	
	// セッションから検索条件を取得
	$lr_old_cond = $lc_sess->getSesseionItem($l_page_name."-COND");
	
	// 検索条件をセッションにセット
	//print_r($_SERVER);
	if ($_POST['nm_selected_estimate_id'] != ""){
		// 編集画面から戻ってきたときのPOST値と入れ替わらないように、
		// 見積IDがPOSTされた場合（作業画面からPOSTされた場合）のみ条件を保存する
		$lc_sess->setSesseionItem($l_page_name."-COND", $_POST);
	}
	
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

	// 年月日
	$l_work_cal_yyyy	= $_POST['nm_work_cal_yyyy'];
	$l_work_cal_mm		= $_POST['nm_work_cal_mm'];
	$l_work_cal_dd		= $_POST['nm_work_cal_dd'];

	// カレンダー用の日付設定
	// POST値があればPOST値を使用
	// なければ今日の日付を設定する
	require_once('../lib/CommonDate.php');
	$lc_commondate = new CommonDate();
	
	if($l_work_cal_yyyy == ""){
	// 年の設定がない（初めて当画面を開いた）場合
		$lr_sess_cond = "";
		$lr_sess_cond = $lc_sess->getSesseionItem($l_page_name."-COND", $_POST);
		
		if (count($lr_sess_cond) > 0){
		// セッションに日付の設定がある場合はその日付を使用
			$l_work_cal_yyyy	= $lr_sess_cond['nm_work_cal_yyyy'];
			$l_work_cal_mm		= $lr_sess_cond['nm_work_cal_mm'];
			$l_work_cal_dd		= $lr_sess_cond['nm_work_cal_dd'];
		}
		
		if ($l_work_cal_yyyy == "" or $l_work_cal_mm == ""){
		// セッションにも設定が無い場合は本日の日付を使用
			$l_work_cal_yyyy	= $lc_commondate->getTodayY();
			$l_work_cal_mm		= $lc_commondate->getTodayM();
			$l_work_cal_dd		= $lc_commondate->getTodayD();
		}
	}
	
	// 作業コード
	$l_work_content_code = $_POST['nm_work_content_code'];
	
	
	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*------------------------------
		見積
	------------------------------*/
	require_once('../mdl/m_estimates.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_見積MDL");print "<br>";}

	// 検索条件設定
	// 見積ID
	$lr_estimate_cond = array('ESTIMATE_ID = '.$l_selected_estimate_id);
	
	// レコード取得
	$lc_estimate = new m_estimates('Y', $lr_estimate_cond);
	$lr_estimate = $lc_estimate->getViewRecord();
	
	//print_r($lr_estimate);
	if($l_debug_mode==1){print("Step-DBデータ取得_見積取得");print "<br>";}

	/*------------------------------
		作業
	------------------------------*/
	require_once('../mdl/m_workcontents.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_作業MDL");print "<br>";}

	// 検索条件設定
	// DATA_ID
	$lr_workcontents_cond = array('DATA_ID = '.$l_data_id);
	// 有効フラグ
	if($l_valid_checkstat == "Y"){
		array_push($lr_workcontents_cond, "VALIDITY_FLAG = 'Y'");
	}
	// 見積ID
	array_push($lr_workcontents_cond, 'ESTIMATE_ID = '.$l_selected_estimate_id);
	
	// 整列設定
	$lr_workcontents_order = array('WORK_DATE', 'WORK_CONTENT_CODE', 'WORK_STATUS_NAME');
	
	// 作業コードの条件取得設定前に作業コードの一覧を取得
	$lc_workcontents_all = new m_workcontents('Y', $lr_workcontents_cond, $lr_workcontents_order);
	$lr_work_content_code = $lc_workcontents_all->getColumnValueAll('WORK_CONTENT_CODE');
	// コンボボックス用の作業コード一覧の重複削除と並び替え
	if (count($lr_work_content_code) > 0){
		sort($lr_work_content_code);
	}
	
	// 作業コード
	if ($l_work_content_code != ""){
		array_push($lr_workcontents_cond, "WORK_CONTENT_CODE = '".$l_work_content_code."'");
	}
	
	//------------------------
	// 全作業レコード取得
	//------------------------
	$lc_workcontents_all = new m_workcontents('Y', $lr_workcontents_cond, $lr_workcontents_order);
	$lr_workcontents_all = $lc_workcontents_all->getViewRecord();
	//{print "<pre>";var_dump($lr_workcontents_all);print "</pre>";}
	
	// 作業の初日と最終日を抽出する
	if (count($lr_workcontents_all) > 0){
		$l_first_work_date	= $lc_workcontents_all->getWorkDate('first');
		$l_last_work_date	= $lc_workcontents_all->getWorkDate('last');
	}
	
	// ヘッダー項目の数値算出
	$l_total_revenue	= 0;	// 総売上
	$l_gross_margin		= 0;	// 粗利
	$l_gross_margin_prc	= 0;	// 粗利率
	
	// 見積金額
	$l_final_presentation_amount = $lr_estimate[1]['FINAL_PRESENTATION_AMOUNT'];
	// 残業(作業人員の残業の合計)
	$l_overtime_work_amount = 0;
	// 出金合計(作業人員の出金合計の合計)
	$l_payment_amount_total = 0;
	// その他費用(作業人員のその他費用の合計)
	$l_other_amount = 0;
	
	// 人員データ用MDLと絞込み条件
	$lr_workstaff_cond = array('ESTIMATE_ID = '.$l_selected_estimate_id);
	require_once('../mdl/m_workstaff.php');
	$lc_workstaff = new m_workstaff('Y', $lr_workstaff_cond);
	// 人員データの読み込み
	$lr_workstaff = $lc_workstaff->getViewRecord();
	
	// 各種合計金額の算出
	$l_overtime_work_amount	= $lc_workstaff->getTotalAmount('OVERTIME_WORK_AMOUNT');
	$l_payment_amount_total	= $lc_workstaff->getTotalAmount('PAYMENT_AMOUNT_TOTAL');
	$l_other_amount			= $lc_workstaff->getTotalAmount('OTHER_AMOUNT');
	
	// 総売上(見積金額(税込)+残業(税抜))
	$l_total_revenue	= $l_final_presentation_amount + $l_overtime_work_amount;
	// 粗利(総売上(税込)-出金合計(税込)-その他費用(税込))
	$l_gross_margin		= $l_total_revenue - $l_payment_amount_total - $l_other_amount;
	// 粗利率(粗利(税込)/総売上(税込))
	if ($l_total_revenue != 0){
		$l_gross_margin_prc	= number_format($l_gross_margin / $l_total_revenue * 100, 2);
	}else{
		$l_gross_margin_prc	= "-";
	}
	//------------------------
	// 絞込み済みレコード取得
	//------------------------
	// 作業日
	// 日まで指定がある場合はその日のみ、月までの指定の場合は月の初日と最終日で範囲指定する
	if ($l_work_cal_dd != ""){
		// 日まで指定
		$l_sche_cond	= "(WORK_DATE = DATE('".$l_work_cal_yyyy."-".$l_work_cal_mm."-".$l_work_cal_dd."'))";
		array_push($lr_workcontents_cond, $l_sche_cond);
	}else if ($l_work_cal_mm != ""){
		// 月まで指定
		$l_cond_schedule_from	= $lc_commondate->getMonthFirstDay($l_work_cal_yyyy, $l_work_cal_mm);
		$l_cond_schedule_to		= $lc_commondate->getMonthEndDay($l_work_cal_yyyy, $l_work_cal_mm);
		$l_sche_cond_from		= "(WORK_DATE >= DATE('".$l_cond_schedule_from."'))";
		$l_sche_cond_to			= "(WORK_DATE <= DATE('".$l_cond_schedule_to."'))";
		array_push($lr_workcontents_cond, $l_sche_cond_from);
		array_push($lr_workcontents_cond, $l_sche_cond_to);
	}
	
	$lc_workcontents = new m_workcontents('Y', $lr_workcontents_cond, $lr_workcontents_order);
	$lr_workcontents = $lc_workcontents->getViewRecord();

	//var_dump($lr_workcontents);
	if($l_debug_mode==1){print("Step-作業取得");print "<br>";}
	// ------------------------------
	// ページ分割した作業データを取得
	// ------------------------------
	require_once('../lib/PagedData.php');
	$lr_pd = new PagedData($lr_workcontents, 'Y', $l_show_record_cnt);

	// 表示対象分のデータを抽出
	$lr_show_paged_data = $lr_pd->pickPageRecord($l_show_page);
	//print_r($lr_show_paged_data);

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
	
	if($l_debug_mode==1){print("Step-共通マスター取得");print "<br>";}
/*----------------------------------------------------------------------------
  カレンダー取得
  ----------------------------------------------------------------------------*/
	// 見積の期間を指定し、指定月の週単位のカレンダーを取得する
	$lr_this_month_days		= $lc_commondate->getDaysForCal($l_work_cal_yyyy, $l_work_cal_mm, 1, $lr_estimate[1]['SCHEDULE_FROM_DATE'], $lr_estimate[1]['SCHEDULE_TO_DATE']);
	//{print "<pre>";var_dump($lr_this_month_days);print "</pre>";}

	// 取得した期間と作業を比較し、作業のある日には印をつける
	// また、日付指定をしている場合は、指定日に印をつける
	// 本日にも印をつける
	foreach ($lr_this_month_days as &$lr_week){
		foreach ($lr_week as &$lr_wcval){
			//print $l_work_cal_yyyy."-".$l_work_cal_mm."-".$lr_wcval['DD']."<br>";
			if ($lr_wcval['DD'] != "-"){
			// 作業のある日はWORK_EXIST属性を'Y'で追加する
				foreach ($lr_workcontents_all as $lr_wcrec){
					if (strtotime($lr_wcrec['WORK_DATE']) == strtotime($l_work_cal_yyyy."-".$l_work_cal_mm."-".$lr_wcval['DD'])){
						$lr_wcval['WORK_EXIST'] = 'Y';
					}
				}
			// 日付指定がある場合は指定日にSELECTED_DAY属性を'Y'で追加する
				if ($l_work_cal_dd != ""){
					if (strtotime($l_work_cal_yyyy."-".$l_work_cal_mm."-".$l_work_cal_dd) == strtotime($l_work_cal_yyyy."-".$l_work_cal_mm."-".$lr_wcval['DD'])){
						$lr_wcval['SELECTED_DAY'] = 'Y';
					}
				}
			// 本日にTODAY属性を'Y'で追加する
				if (strtotime($lc_commondate->getTodayY()."-".$lc_commondate->getTodayM()."-".$lc_commondate->getTodayD()) == strtotime($l_work_cal_yyyy."-".$l_work_cal_mm."-".$lr_wcval['DD'])){
						$lr_wcval['TODAY'] = 'Y';
				}
			}
		}
	}
	//{print "<pre>";var_dump($lr_this_month_days);print "</pre>";}
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
							DIR_CSS."v_tasks_menu_block.css", 
							DIR_CSS."v_tasks_common.css", 
							DIR_CSS."v_tasks_main.css", 
							DIR_CSS."v_tasks_info.css", 
							DIR_CSS."v_tasks_calendar.css", 
							DIR_CSS."v_tasks_detail.css", 
							DIR_CSS."gb_styles.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_projects_common.js", 		// プロジェクト以下の画面は隠し項目取得の為共通で使用する
							DIR_JS."jfnc_tasks_menu.js", 
							DIR_JS."jfnc_tasks_calendar.js", 
							DIR_JS."jfnc_tasks_detail.js", 
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
	$lc_smarty->assign("top_include_tpl"		,"top_block.tpl");						// トップ
	$lc_smarty->assign("main_include_tpl"		,"tasks_menu_block.tpl");				// メインメニュー
	$lc_smarty->assign("info_include_tpl"		,"tasks_info_block.tpl");				// 概要
	$lc_smarty->assign("calendar_include_tpl"	,"tasks_calendar_block.tpl");			// カレンダー
	$lc_smarty->assign("detail_include_tpl"		,"tasks_detail_block.tpl");				// 明細

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"作業管理");					// 画面タイトル
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
	// 概要部
	// ------------------------------
	// 作業名
	$lc_smarty->assign("work_name", 				$lr_estimate[1]['WORK_NAME']);
	// 見積コード
	$lc_smarty->assign("estimate_code", 			$lr_estimate[1]['ESTIMATE_CODE']." ".$lr_estimate[1]['SUB_NUMBER']);
	// 作業期間
	$lc_smarty->assign("work_schedule", 			$lr_estimate[1]['SCHEDULE_FROM_DATE']." - ".$lr_estimate[1]['SCHEDULE_TO_DATE']);
	// エンドユーザー
	$lc_smarty->assign("enduser_company_name", 		$lr_estimate[1]['ENDUSER_COMPANY_NAME']);
	// 依頼元
	$lc_smarty->assign("request_company_name", 		$lr_estimate[1]['REQUEST_COMPANY_NAME']);
	// 総売上
	$lc_smarty->assign("total_revenue", 			$l_total_revenue);
	// 粗利
	$lc_smarty->assign("gross_margin", 				$l_gross_margin);
	// 粗利率
	$lc_smarty->assign("gross_margin_prc", 			$l_gross_margin_prc);
	
	// ------------------------------
	// カレンダー部
	// ------------------------------
	// 上部メッセージ
	$lc_smarty->assign("calendar_top_mess", 		"作業予定期間の設定");
	// 年月
	$lc_smarty->assign("cal_yyyy", 					$l_work_cal_yyyy);
	$lc_smarty->assign("cal_mm", 					$l_work_cal_mm);
	// 各週
	$lc_smarty->assign("cal_week1", 				$lr_this_month_days[1]);
	$lc_smarty->assign("cal_week2", 				$lr_this_month_days[2]);
	$lc_smarty->assign("cal_week3", 				$lr_this_month_days[3]);
	$lc_smarty->assign("cal_week4", 				$lr_this_month_days[4]);
	$lc_smarty->assign("cal_week5", 				$lr_this_month_days[5]);
	$lc_smarty->assign("cal_week6", 				$lr_this_month_days[6]);
	// 選択済みの年月日
	$lc_smarty->assign("selected_year", 			$l_work_cal_yyyy);
	$lc_smarty->assign("selected_month", 			$l_work_cal_mm);
	$lc_smarty->assign("selected_day", 				$l_work_cal_dd);
	// 作業の初日、最終日
	$lc_smarty->assign("first_work_date", 			$l_first_work_date);
	$lc_smarty->assign("last_work_date", 			$l_last_work_date);
	
	// ------------------------------
	// 明細部
	// ------------------------------
	// データレコード
	$lc_smarty->assign("ar_task_dtl",				$lr_show_paged_data);					// 明細レコード
	
	// 作業コード
	$lc_smarty->assign("ar_work_content_code",		$lr_work_content_code);
	$lc_smarty->assign("default_work_content_code",	$l_work_content_code);
	
	// 指定年月日
	if ($l_work_cal_dd != ""){
		$lc_smarty->assign("dtl_target_date",			$l_work_cal_yyyy."-".$l_work_cal_mm."-".$l_work_cal_dd);
	}else{
		$lc_smarty->assign("dtl_target_date",			$l_work_cal_yyyy."-".$l_work_cal_mm);
	}
	
	// ボタン操作部
	if(count($lr_show_paged_data) > 0){
		$lc_smarty->assign("ope_pageitem_visible",		"ON");
		$lc_smarty->assign("ope_page_select_html",		$l_page_select_html);
		$lc_smarty->assign("ope_rec_count",				$l_company_cnt);

		// 前のページボタン
		if($l_prevpage_cnt > 0){
			$lc_smarty->assign("ope_prev_btn_visible",	"ON");
			$lc_smarty->assign("ope_prev_btn_value",	"前の".$l_prevpage_cnt."件");
		}
		// 次のページボタン
		if($l_nextpage_cnt > 0){
			$lc_smarty->assign("ope_next_btn_visible",	"ON");
			$lc_smarty->assign("ope_next_btn_value",	"次の".$l_nextpage_cnt."件");
		}
	}
	// 有効データのみ表示チェックの設定
	$lc_smarty->assign("valid_task_checkstat",			$l_valid_checkstat);

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
								  "name"	=> "nm_selected_estimate_id"
								, "value"	=> $l_selected_estimate_id
								),
							array(									// カレンダーの年
								  "name"	=> "nm_work_cal_yyyy"
								, "value"	=> $l_work_cal_yyyy
								),
							array(									// カレンダーの月
								  "name"	=> "nm_work_cal_mm"
								, "value"	=> $l_work_cal_mm
								),
							array(									// カレンダーの日
								  "name"	=> "nm_work_cal_dd"
								, "value"	=> $l_work_cal_dd
								),
							array(									// 作業コード
								  "name"	=> "nm_work_content_code"
								, "value"	=> $l_work_content_code
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('tasks_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>