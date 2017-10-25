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
 ファイル名：projects_overview.php
 処理概要   プロジェクト概要画面
 POST受領値：
            token_code					トークン(必須)
            estimate_id					プロジェクト管理画面で選択された見積ID(必須)
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
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "PROJECT_OVERVIEW";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$lr_workcontents_cond	= "";									// 作業検索条件
	$l_valid_checkstat		= "";									// 有効フラグ
	$l_selected_est_id		= "";									// 見積ID

/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_prjov(Exception $e){
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
	set_exception_handler('my_exception_prjov');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();

	// POSTされたトークンを取得
	$l_post_token = $_GET['token_code'];
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
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	// 見積IDを取得
	$l_selected_est_id = $_GET['estimate_id'];
	if($l_selected_est_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	
	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*------------------------------
		見積
	------------------------------*/
	// 検索条件設定
	// 見積ID
	$lr_estiates_cond = array('ESTIMATE_ID = '.$l_selected_est_id);
	if($l_debug_mode==1){print("Step-DBデータ取得_見積MDL");print "<br>";}
	
	// 作業取得
	require_once('../mdl/m_estimates.php');
	$lc_estimates = new m_estimates('Y', $lr_estiates_cond);
	$lr_estimates = $lc_estimates->getViewRecord();
	if($l_debug_mode==1){print("Step-DBデータ取得_見積取得");print "<br>";}
	
	/*------------------------------
		作業
	------------------------------*/
	// 検索条件設定
	// 見積ID
	$lr_workcontents_cond = array('ESTIMATE_ID = '.$l_selected_est_id);
	if($l_debug_mode==1){print("Step-DBデータ取得_作業MDL");print "<br>";}
	
	// 整列設定
	$lr_workcontents_order = array('WORK_DATE');
	
	// 作業取得
	require_once('../mdl/m_workcontents.php');
	$lc_workcontents = new m_workcontents('Y', $lr_workcontents_cond, $lr_workcontents_order);
	$lr_workcontents = $lc_workcontents->getViewRecord();
	if($l_debug_mode==1){print("Step-DBデータ取得_作業取得");print "<br>";}
	
	/*------------------------------
		作業人員
	------------------------------*/
	require_once('../mdl/m_workstaff.php');
	// 金額合計のクリア
	$l_overtime_work_amount_all	= 0;
	$l_payment_amount_total_all	= 0;
	$l_other_amount_all			= 0;
	
	foreach ($lr_workcontents as $l_rec_cnt => $lr_task){
		// 各作業日に紐付く作業人員を抽出し、作業日毎の人数、金額合計を算出して作業の配列に追加する
		$l_staff_cnt				= 0;
		$l_overtime_work_amount_sum	= 0;
		$l_payment_amount_total_sum	= 0;
		$l_other_amount_sum			= 0;
		
		// 検索条件設定
		// 見積ID
		$lr_workstaff_cond = array('WORK_CONTENT_ID = '.$lr_task['WORK_CONTENT_ID']);
		
		// 作業人員取得
		$lc_workstaff = new m_workstaff('Y', $lr_workstaff_cond);
		$lr_workstaff = $lc_workstaff->getViewRecord();
		
		$l_staff_cnt				= sizeof($lr_workstaff);
		$l_overtime_work_amount_sum	= $lc_workstaff->getTotalAmount('OVERTIME_WORK_AMOUNT');
		$l_payment_amount_total_sum	= $lc_workstaff->getTotalAmount('PAYMENT_AMOUNT_TOTAL');
		$l_other_amount_sum			= $lc_workstaff->getTotalAmount('OTHER_AMOUNT');
		
		//print $lr_task['WORK_DATE'].":".$l_staff_cnt.":".$l_overtime_work_amount_sum.":".$l_payment_amount_total_sum.":".$l_other_amount_sum."<br>";
		
		// 配列に追加
		$lr_workcontents[$l_rec_cnt]['staff_count']				= $l_staff_cnt;
		$lr_workcontents[$l_rec_cnt]['overtime_work_amount']	= $l_overtime_work_amount_sum;
		$lr_workcontents[$l_rec_cnt]['payment_amount_total']	= $l_payment_amount_total_sum;
		$lr_workcontents[$l_rec_cnt]['other_amount']			= $l_other_amount_sum;
		
		// 合計値に追加
		$l_overtime_work_amount_all	+= $l_overtime_work_amount_sum;
		$l_payment_amount_total_all	+= $l_payment_amount_total_sum;
		$l_other_amount_all			+= $l_other_amount_sum;
	}
	
	if($l_debug_mode==1){print("Step-DBデータ取得_作業人員取得");print "<br>";}
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
	$ar_css_files	= array(DIR_CSS."v_overview.css",
							DIR_CSS."v_projects_overview.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_projects_common.js", 		// プロジェクト以下の画面は隠し項目取得の為共通で使用する
							DIR_JS."jfnc_projects_overview.js"
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
	$lc_smarty->assign("main_include_tpl"		,"projects_ov_main.tpl");
	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"プロジェクト概要");			// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名
	
	$lc_smarty->assign("overview_title"	,"プロジェクト概要");					// タイトル
	
	// プロジェクト概要部分
	$lc_smarty->assign("work_name"				,$lr_estimates[1]["WORK_NAME"]);			// 作業名
	$lc_smarty->assign("work_date_sch_from"		,$lr_estimates[1]["SCHEDULE_FROM_DATE"]);	// 作業予定日自
	$lc_smarty->assign("work_date_sch_to"		,$lr_estimates[1]["SCHEDULE_TO_DATE"]);		// 作業予定日至
	
	// 実作業日
	$lc_smarty->assign("work_date_act_from"		,$lc_workcontents->getWorkDate('first'));
	$lc_smarty->assign("work_date_act_to"		,$lc_workcontents->getWorkDate('last'));
	
	// 顧客情報
	$lc_smarty->assign("enduser_comp_name"		,$lr_estimates[1]["ENDUSER_COMPANY_NAME"]);	// エンドユーザー
	$lc_smarty->assign("request_comp_name"		,$lr_estimates[1]["REQUEST_COMPANY_NAME"]);	// 依頼元
	
	// 作業明細
	$lc_smarty->assign("ar_tasks"				,$lr_workcontents);
	
	// 合計
	$lc_smarty->assign("overtime_work_amount_sum"	,$l_overtime_work_amount_all);
	$lc_smarty->assign("payment_amount_total_sum"	,$l_payment_amount_total_all);
	$lc_smarty->assign("other_amount_sum"			,$l_other_amount_all);
	
	// 損益表
	// 見積金額
	$lc_smarty->assign("ac_estimate_amount"			,$lr_estimates[1]['FINAL_PRESENTATION_AMOUNT']);
	// 残業
	$lc_smarty->assign("ac_owerk_amount"			,$l_overtime_work_amount_all);
	// 出金合計
	$lc_smarty->assign("ac_payment_amount"			,$l_payment_amount_total_all);
	// その他費用
	$lc_smarty->assign("ac_other_amount"			,$l_other_amount_all);
	// 損益
	// 総売上(見積金額(税込)+残業(税抜))、粗利(総売上(税込)-出金合計(税込)-その他費用(税込))
	// 粗利が出たら右に利益を記載、損失があれば左に損失を記載
	$l_pl_amont = $lr_estimates[1]['FINAL_PRESENTATION_AMOUNT'] + $l_overtime_work_amount_all
					- ($l_payment_amount_total_all + $l_other_amount_all);
	if ($l_pl_amont >= 0){
		// 利益有り
		$lc_smarty->assign("ac_cr_cap"	, "利益");
		$lc_smarty->assign("ac_cr_pl"	, $l_pl_amont);
	}else{
		// 損失有り
		$lc_smarty->assign("ac_dr_cap"	, "損失");
		$lc_smarty->assign("ac_dr_pl"	, -1 * $l_pl_amont);
	}
	
	// ------------------------------
	// 共通設定
	// ------------------------------
	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);


	// ------------------------------
	// 隠し項目
	// ------------------------------
	$lr_hidden_items	= array(
							array(
								  "name"	=> "estimate_id"
								, "value"	=> $l_selected_est_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('overview.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>