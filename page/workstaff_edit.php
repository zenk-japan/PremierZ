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
 ファイル名：workstaff_edit.php
 処理概要   人員編集画面
 GET受領値：
						 bid                            動作モード(必須) insert or update
						 nm_token_code                  トークン(必須)
						 nm_selected_workcontents_id    作業ID(insert時必須)
						 nm_workstaff_id                人員ID(update時必須)
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
$l_page_name			= "WORKSTAFF_EDIT";
$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
$l_sess_data_id			= "";									// 画面にセットするDATA_ID
$l_proc_mode			= "";									// 動作モード
$l_work_content_id		= "";									// 作業ID
$l_workstaff_id			= "";									// 人員ID
$l_new_sub_number		= "";									// 更新用の枝番
$l_sess_user_name		= "";									// 実行ユーザー名
$l_work_cal_ymd			= "";									// 作業管理画面から引き継いだ作業日
$l_default_work_status	= INPUT_WORK_STATUS;					// 新規作成時のデフォルトステータス
$l_default_work_content_code = "1";								// 新規作成時のデフォルト作業コード
//print "step3<br>";
/*----------------------------------------------------------------------------
	例外定義
----------------------------------------------------------------------------*/
function my_exception_usermnt(Exception $e){
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
set_exception_handler('my_exception_usermnt');

if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
	セッション確認
----------------------------------------------------------------------------*/
require_once('../lib/sessionControl.php');
$lc_sess = new sessionControl();

// GETされたトークンを取得
$l_get_token = $_GET['nm_token_code'];
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

// ユーザーIDの取得
$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
if($l_sess_user_id == ""){
	throw new Exception($l_error_type_st);
}

// ユーザー名の取得
$l_sess_user_name = $lc_sess->getSesseionItem('NAME');
if($l_sess_user_name == ""){
	throw new Exception($l_error_type_st);
}

// DATA_IDの取得
$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
if($l_sess_data_id == ""){
	throw new Exception($l_error_type_st);
}

// 親レコードの取得
$l_parent_rec = $lc_sess->getSesseionItem('WORKSTAFF-PARENTREC');

if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
	GET変数取得
----------------------------------------------------------------------------*/
// 動作モード
if(!is_null($_GET['bid'])){
	$l_proc_mode = $_GET['bid'];
}

// 作業ID
if(!is_null($_GET['nm_selected_workcontents_id'])){
	$l_work_content_id = $_GET['nm_selected_workcontents_id'];
}

if($l_proc_mode == OPMODE_UPDATE){
	// 更新の場合
	// 人員ID
	if(!is_null($_GET['nm_workstaff_id'])){
		$l_workstaff_id = $_GET['nm_workstaff_id'];
	}
}

if($l_debug_mode==1){print("Step-GET変数取得");print "<br>";}

/*----------------------------------------------------------------------------
	DBデータ取得
----------------------------------------------------------------------------*/
// 作業MDL
require_once('../mdl/m_workstaff.php');
if($l_proc_mode == OPMODE_INSERT){
	$lc_model_class = new m_workstaff('Y');
	// ------------------------------
	// 新規
	// ------------------------------
	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_create", "value" => "作成"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// SQLタイプ
	$l_edit_sql_type = OPMODE_INSERT;
}else{
	// ------------------------------
	// 編集表示用のデータ取得
	// ------------------------------

	// 検索条件設定
	$lr_cond_dtl = array('WORK_STAFF_ID = '.$l_workstaff_id);

	// レコード取得
	$lc_model_class = new m_workstaff('Y', $lr_cond_dtl);
	$lr_update_data = $lc_model_class->getViewRecord();

	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_save", "value" => "保存"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// SQLタイプ
	$l_edit_sql_type = OPMODE_UPDATE;
}

if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}

/*----------------------------------------------------------------------------
	共通マスタ取得
----------------------------------------------------------------------------*/
// 有効フラグ
$ar_validity = $lc_model_class->createValidityFlagList($l_sess_data_id, $lr_update_data[1]['VALIDITY_FLAG']);

require_once('../mdl/m_common_master.php');
$lr_common = new m_common_master();
// 承認区分
$lr_approval_division	= $lr_common->getCommonValueRec($l_sess_data_id, "APPROVAL_DIVISION");

// キャンセル区分
$lr_cancel_division		= $lr_common->getCommonValueRec($l_sess_data_id, "CANCEL_DIVISION");

if($l_debug_mode==1){print("Step-共通マスタレコードの取得");print "<br>\n";}

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
$ar_css_files	= array(DIR_CSS."jquery-ui-custom.css", 
DIR_CSS."v_edit_masters.css", 
DIR_CSS."v_workstaff_edit_block.css", 
DIR_CSS."example.css", 
DIR_CSS."v_valuelist_div.css"
							);

// jsファイル
$ar_js_files	= array(DIR_JS."jquery.js", 
	DIR_JS."jquery-ui-custom.min.js", 
	DIR_JS."jfnc_value_list_edit.js", 
	DIR_JS."jfnc_common.js", 
	DIR_JS."jfnc_workstaff_edit.js", 
	DIR_JS."jfnc_common_edit.js", 
	DIR_JS."jquery.updnWatermark.js"
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
$lc_smarty->assign("edit_include_tpl"				, "workstaff_edit_block.tpl");	// 編集

// ------------------------------
// Smartyセット
// ------------------------------
$lc_smarty->assign("headtitle"						, "人員管理");			// 画面タイトル
$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル
$lc_smarty->assign("proc_mode"						, $l_proc_mode);				// モード

// ------------------------------
// 編集項目
// ------------------------------	
// ボタン
$lc_smarty->assign("edit_button"					, $ar_button);

// 承認区分
$lc_smarty->assign("ar_approval_division"			, $lr_approval_division);

// キャンセル区分
$lc_smarty->assign("ar_cancel_division"				, $lr_cancel_division);

// データレコード
$lc_smarty->assign("edit_table_item"				, $lr_update_data[1]);

// 有効フラグ
$lc_smarty->assign("edit_validity"					, $ar_validity);

if ($l_proc_mode == OPMODE_INSERT){
	// 入店予定時間
	$lc_smarty->assign("default_entering_schedule_timet"	, $l_parent_rec[1]["DEFAULT_ENTERING_SCHEDULE_TIMET"]);

	// 退店予定時間
	$lc_smarty->assign("default_leave_schedule_timet"		, $l_parent_rec[1]["DEFAULT_LEAVE_SCHEDULE_TIMET"]);

	// 基本時間
	$lc_smarty->assign("default_basic_time"					, $l_parent_rec[1]["DEFAULT_WORKING_TIME"]);

	// 基本休憩時間
	$lc_smarty->assign("default_break_time"					, $l_parent_rec[1]["DEFAULT_BREAK_TIME"]);
}
// ------------------------------
// 隠し項目
// ------------------------------
$lr_hidden_items	= array(

	array(									// データID
		"name"	=> "DATA_ID"
		, "value"	=> $l_sess_data_id
	),
	array(									// 作業ID
		"name"	=> "WORK_CONTENT_ID"
		, "value"	=> $l_work_content_id
	),
	array(									// 人員ID
		"name"	=> "WORK_STAFF_ID"
		, "value"	=> $l_workstaff_id
	),
	array(									// SQLタイプ
		"name"	=> "sql_type"
		, "value"	=> $l_edit_sql_type
	),
	array(									// トークン
		"name"	=> "nm_token_code"
		, "value"	=> $l_sess_token
	),
	array(									// 作業日
		"name"	=> "nm_work_date"
		, "value"	=> $l_parent_rec[1]["WORK_DATE"]
	)
);
$lc_smarty->assign("ar_hidden_items"				, $lr_hidden_items);

if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}

/*-----------------------------------
	ページ表示
-----------------------------------*/
$lc_smarty->display('EditMasterMain.tpl');
if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>
