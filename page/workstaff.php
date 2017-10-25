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
 ファイル名：workstaff.php
 処理概要   人員管理画面
 POST受領値：
            nm_token_code              				トークン(必須)
            nm_valid_checkstat         				有効データチェック状態(任意)
            nm_selected_workcontents_id				作業画面で選択された作業ID(必須)
            nm_selected_estimate_id					プロジェクト画面で選択された見積ID(必須)
            nm_work_company_id						所属会社(任意)
            nm_work_group_id						グループ(任意)
            nm_work_classification_division			分類区分(任意)
            nm_work_user_id							作業者(任意)
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
	$l_page_name			= "WORKSTAFF";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$lr_workcontents_cond	= "";									// 作業検索条件
	$lr_workstaff_cond		= "";									// 人員検索条件
	$lr_workcontents		= "";									// 作業レコード
	$l_valid_checkstat		= "";									// 有効フラグ
	
	$l_selected_workcontents_id	= "";								// 作業画面で選択された作業ID
	$l_selected_estimate_id		= "";								// プロジェクト画面で選択された見積ID
	$l_work_company_id				= "";							// 所属会社
	$l_work_group_id				= "";							// グループ
	$l_work_classification_division	= "";							// 分類区分
	$l_work_user_id					= "";							// 作業者
	$lr_old_cond					= "";							// セッションに格納されている検索条件の値
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_workstaffmnt(Exception $e){
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
	set_exception_handler('my_exception_workstaffmnt');

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
	
	// 作業画面で選択された作業ID
	$l_selected_workcontents_id = $_POST['nm_selected_workcontents_id'];
	if($l_selected_workcontents_id == ""){
		// 編集画面から戻ってきた場合は、セッションにある値を使用する
		$l_selected_workcontents_id = $lr_old_cond["nm_selected_workcontents_id"];
	}
	if($l_selected_workcontents_id == ""){
		// POST値にもセッションにもない場合はエラー
		throw new Exception($l_error_type_st);
	}
	// プロジェクト画面で選択された見積ID
	$l_selected_estimate_id = $_POST['nm_selected_estimate_id'];
	if($l_selected_estimate_id == ""){
		// 編集画面から戻ってきた場合は、セッションにある値を使用する
		$l_selected_estimate_id = $lr_old_cond["nm_selected_estimate_id"];
	}
	if($l_selected_estimate_id == ""){
		// POST値にもセッションにもない場合はエラー
		throw new Exception($l_error_type_st);
	}
	
	// 所属会社
	$l_work_company_id				= $_POST['nm_work_company_id'];
	// グループ
	$l_work_group_id				= $_POST['nm_work_group_id'];
	// 分類区分
	$l_work_classification_division	= htmlspecialchars($_POST['nm_work_classification_division']);
	// 作業者
	$l_work_user_id					= $_POST['nm_work_user_id'];
	// 有効フラグ
	$l_valid_checkstat				= $_POST['nm_valid_checkstat'];

	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*------------------------------
		作業
	------------------------------*/
	// 作業MDL
	require_once('../mdl/m_workcontents.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_作業MDL");print "<br>";}

	// 作業ID
	$lr_workcontents_cond = array('WORK_CONTENT_ID = '.$l_selected_workcontents_id);
	
	// レコード取得
	$lc_workcontents = new m_workcontents('Y', $lr_workcontents_cond);
	$lr_workcontents = $lc_workcontents->getViewRecord();
	
	// 親レコードをセッションにセット
	//print_r($_SERVER);
	$lc_sess->setSesseionItem($l_page_name."-PARENTREC", $lr_workcontents);
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}

	/*------------------------------
		作業人員
	------------------------------*/
	// 作業人員MDL
	require_once('../mdl/m_workstaff.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_作業人員MDL");print "<br>";}
	
	// 検索条件設定
	// DATA_ID
	$lr_workstaff_cond = array('DATA_ID = '.$l_data_id);
	// 有効フラグ
	if($l_valid_checkstat == "Y"){
		array_push($lr_workstaff_cond, "VALIDITY_FLAG = 'Y'");
	}
	// 作業ID
	array_push($lr_workstaff_cond, 'WORK_CONTENT_ID = '.$l_selected_workcontents_id);
	
	// 所属会社
	if ($l_work_company_id != ""){
		array_push($lr_workstaff_cond, 'WORK_COMPANY_ID = '.$l_work_company_id);
	}
	// グループ
	if ($l_work_group_id != ""){
		array_push($lr_workstaff_cond, 'WORK_GROUP_ID = '.$l_work_group_id);
	}
	
	// 分類区分
	if ($l_work_classification_division != ""){
		array_push($lr_workstaff_cond, "WORK_CLASSIFICATION_DIVISION = '".$l_work_classification_division."'");
	}
	
	// 作業者
	if ($l_work_user_id != ""){
		array_push($lr_workstaff_cond, 'WORK_USER_ID = '.$l_work_user_id);
	}
	
	// 整列設定
	$lr_workstaff_order = array('WORK_BASE_NAME', 'WORK_USER_KANA');
	
	//------------------------
	// レコード取得
	//------------------------
	$lc_workstaff_all = new m_workstaff('Y', $lr_workstaff_cond, $lr_workstaff_order);
	$lr_workstaff_all = $lc_workstaff_all->getViewRecord();
//{print "<pre>";var_dump($lr_workstaff_all);print "</pre>";}
	
	$lr_work_company_name					= "";
	$lr_work_group_name						= "";
	$lr_work_classification_division_name	= "";
	$lr_work_user_name						= "";

	if (count($lr_workstaff_all) > 0){
	// 所属会社用リスト取得
		$lr_work_company_name					= $lc_workstaff_all->get2ColumnValueAll("WORK_COMPANY_ID", "WORK_COMPANY_NAME");
	// グループ用リスト取得
		$lr_work_group_name						= $lc_workstaff_all->get2ColumnValueAll("WORK_GROUP_ID", "WORK_GROUP_NAME");
	// 分類区分用リスト取得
		$lr_work_classification_division_name	= $lc_workstaff_all->get2ColumnValueAll("WORK_CLASSIFICATION_DIVISION", "WORK_CLASSIFICATION_DIVISION_NAME");
	// 作業者用リスト取得
		$lr_work_user_name						= $lc_workstaff_all->get2ColumnValueAll("WORK_USER_ID", "WORK_USER_NAME");
	// 合計値算出
		$l_work_unit_price_orig_sum			= 0;
		$l_excess_amount_sum				= 0;
		$l_basic_time_sum					= 0;
		$l_break_time_sum					= 0;
		$l_transport_amount_sum				= 0;
		$l_other_amount_sum					= 0;
		$l_overtime_work_amount_sum			= 0;
		$l_work_expense_amount_total_sum	= 0;
		$l_payment_amount_total_sum			= 0;
		$l_real_working_hours_sum			= 0;
		$l_real_overtime_hours_sum			= 0;
		$l_supplied_amount_total_sum		= 0;
		foreach ($lr_workstaff_all as $l_rec_num => $lr_workstaff_rec){
			$l_work_unit_price_orig_sum			+= $lr_workstaff_rec["WORK_UNIT_PRICE_ORIG"];
			$l_excess_amount_sum				+= $lr_workstaff_rec["EXCESS_AMOUNT"];
			$l_basic_time_sum					+= $lr_workstaff_rec["BASIC_TIME"];
			$l_break_time_sum					+= $lr_workstaff_rec["BREAK_TIME"];
			$l_transport_amount_sum				+= $lr_workstaff_rec["TRANSPORT_AMOUNT"];
			$l_other_amount_sum					+= $lr_workstaff_rec["OTHER_AMOUNT"];
			$l_overtime_work_amount_sum			+= $lr_workstaff_rec["OVERTIME_WORK_AMOUNT"];
			$l_work_expense_amount_total_sum	+= $lr_workstaff_rec["WORK_EXPENSE_AMOUNT_TOTAL"];
			$l_payment_amount_total_sum			+= $lr_workstaff_rec["PAYMENT_AMOUNT_TOTAL"];
			$l_real_working_hours_sum			+= $lr_workstaff_rec["REAL_LABOR_HOURS"];	// 休憩時間減算後の値を使用する
			$l_real_overtime_hours_sum			+= $lr_workstaff_rec["REAL_OVERTIME_HOURS"];
			$l_supplied_amount_total_sum		+= $lr_workstaff_rec["SUPPLIED_AMOUNT_TOTAL"];
		}
	}
	/*------------------------------
		共通マスター取得
	------------------------------*/
	require_once('../mdl/m_common_master.php');
	$lr_common = new m_common_master();

	// 注文区分
	$lr_order_division	= $lr_common->getCommonValueRec($l_data_id, "ORDER_DIVISION");

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
							DIR_CSS."v_workstaff_menu_block.css", 
							DIR_CSS."v_workstaff_common.css", 
							DIR_CSS."v_workstaff_main.css", 
							DIR_CSS."v_workstaff_info.css", 
							DIR_CSS."v_workstaff_search.css", 
							DIR_CSS."v_workstaff_operation.css", 
							DIR_CSS."v_workstaff_detail.css", 
							DIR_CSS."gb_styles.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_projects_common.js", 
							DIR_JS."jfnc_workstaff_menu.js", 
							DIR_JS."jfnc_workstaff_search.js", 
							DIR_JS."jfnc_workstaff_operation.js", 
							DIR_JS."jfnc_workstaff_detail.js", 
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
	$lc_smarty->assign("main_include_tpl"		,"workstaff_menu_block.tpl");			// メインメニュー
	$lc_smarty->assign("info_include_tpl"		,"workstaff_info_block.tpl");			// 概要
	$lc_smarty->assign("search_include_tpl"		,"workstaff_search_block.tpl");			// 人員検索
	$lc_smarty->assign("operation_include_tpl"	,"workstaff_operation_block.tpl");		// 操作
	$lc_smarty->assign("detail_include_tpl"		,"workstaff_detail_block.tpl");			// 明細

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"人員管理");					// 画面タイトル
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
	$lc_smarty->assign("work_name"							,$lr_workcontents[1]['WORK_NAME']);
	// 見積コード
	$lc_smarty->assign("estimate_code"						,$lr_workcontents[1]['ESTIMATE_CODE']." ".$lr_workcontents[1]['SUB_NUMBER']);
	// 作業日
	$lc_smarty->assign("work_date"							,$lr_workcontents[1]['WORK_DATE']);
	// エンドユーザー
	$lc_smarty->assign("enduser_company_name"				,$lr_workcontents[1]['ENDUSER_COMPANY_NAME']);
	// 依頼元
	$lc_smarty->assign("request_company_name"				,$lr_workcontents[1]['REQUEST_COMPANY_NAME']);
	// 入店予定時刻
	$lc_smarty->assign("default_entering_schedule_timet"	,$lr_workcontents[1]['DEFAULT_ENTERING_SCHEDULE_TIMET']);
	// 退店予定時刻
	$lc_smarty->assign("default_leave_schedule_timet"		,$lr_workcontents[1]['DEFAULT_LEAVE_SCHEDULE_TIMET']);
	// 集合場所
	$lc_smarty->assign("aggregate_point"					,$lr_workcontents[1]['AGGREGATE_POINT']);

	// ------------------------------
	// 検索部
	// ------------------------------
	// 所属会社
	$lc_smarty->assign("ar_work_company_name"						,$lr_work_company_name);
	$lc_smarty->assign("default_work_company_name"					,$l_work_company_id);
	// グループ
	$lc_smarty->assign("ar_work_group_name"							,$lr_work_group_name);
	$lc_smarty->assign("default_work_group_name"					,$l_work_group_id);
	// 分類区分
	$lc_smarty->assign("ar_work_classification_division_name"		,$lr_work_classification_division_name);
	$lc_smarty->assign("default_work_classification_division_name"	,$l_work_classification_division);
	// 作業者
	$lc_smarty->assign("ar_work_user_name"							,$lr_work_user_name);
	$lc_smarty->assign("default_work_user_name"						,$l_work_user_id);
	// ------------------------------
	// 操作部
	// ------------------------------
	// 有効データのみ表示チェックの設定
	$lc_smarty->assign("valid_workstaff_checkstat"	, $l_valid_checkstat);
	
	// ------------------------------
	// 合計
	// ------------------------------
	$lc_smarty->assign("work_unit_price_orig_sum"					,number_format($l_work_unit_price_orig_sum, 0,'.',','));
	$lc_smarty->assign("excess_amount_sum"							,number_format($l_excess_amount_sum, 2, '.', ','));
	$lc_smarty->assign("basic_time_sum"								,number_format($l_basic_time_sum, 2, '.', ','));
	$lc_smarty->assign("break_time_sum"								,number_format($l_break_time_sum, 2, '.', ','));
	$lc_smarty->assign("transport_amount_sum"						,number_format($l_transport_amount_sum, 0, '.', ','));
	$lc_smarty->assign("other_amount_sum"							,number_format($l_other_amount_sum, 0, '.', ','));
	$lc_smarty->assign("overtime_work_amount_sum"					,number_format($l_overtime_work_amount_sum, 2, '.', ','));
	$lc_smarty->assign("work_expense_amount_total_sum"				,number_format($l_work_expense_amount_total_sum, 2, '.', ','));
	$lc_smarty->assign("payment_amount_total_sum"					,number_format($l_payment_amount_total_sum, 0, '.', ','));
	$lc_smarty->assign("real_working_hours_sum"						,number_format($l_real_working_hours_sum, 2, '.', ','));
	$lc_smarty->assign("real_overtime_hours_sum"					,number_format($l_real_overtime_hours_sum, 2, '.', ','));
	$lc_smarty->assign("supplied_amount_total_sum"					,number_format($l_supplied_amount_total_sum, 0, '.', ','));
	
	// ------------------------------
	// 明細部
	// ------------------------------
	// データレコード
	$lc_smarty->assign("ar_wstaff_dtl"	,$lr_workstaff_all);					// 明細レコード

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
							array(									// 作業画面で選択された作業ID
								  "name"	=> "nm_selected_workcontents_id"
								, "value"	=> $l_selected_workcontents_id
								),
							array(									// プロジェクト画面で選択された見積ID
								  "name"	=> "nm_selected_estimate_id"
								, "value"	=> $l_selected_estimate_id
								),
							array(									// 所属会社
								  "name"	=> "nm_work_company_id"
								, "value"	=> $l_work_company_id
								),
							array(									// グループ
								  "name"	=> "nm_work_group_id"
								, "value"	=> $l_work_group_id
								),
							array(									// 分類区分
								  "name"	=> "nm_work_classification_division"
								, "value"	=> $l_work_classification_division
								),
							array(									// 作業者
								  "name"	=> "nm_work_user_id"
								, "value"	=> $l_work_user_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('workstaff_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>