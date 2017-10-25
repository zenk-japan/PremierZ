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
 ファイル名：users_edit.php
 処理概要  ：ユーザー管理編集画面
 GET受領値：
						 cid                        会社ID(任意)
						 gid                        グループID(任意)
						 uid                        ユーザーID(必須)
******************************************************************************/
$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
if($l_debug_mode == 1 || $l_debug_mode == 2){
	print "post-><br>\n";
	print var_dump($_POST);
	print "<br>\n";
	print "session-><br>\n";
	print var_dump($_SESSION);
	print "<br>\n";
	print var_dump($_REQUEST);
	print "<br>\n";
}
/*----------------------------------------------------------------------------
	前処理
----------------------------------------------------------------------------*/
require_once('../lib/CommonStaticValue.php');

//print "step2<br>\n";
/*----------------------------------------------------------------------------
	変数宣言
----------------------------------------------------------------------------*/
$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
$l_mes_sufix		= " at=>".basename(__FILE__)."<br>\n";	// メッセージ用接尾辞
$l_html_rts			= "<br>\n";								// HTMLの改行
$l_get_token		= "";									// GETされたトークン
$l_sess_token		= "";									// セッションで保持しているトークン
$l_user_name		= "";									// セッションで保持しているユーザー名
$l_data_id			= "";									// 画面にセットするDATA_ID
$l_comp_name_cond	= "";									// 会社名(検索用)
$l_group_name_cond	= "";									// グループ名(検索用)
$l_user_name_cond	= "";									// ユーザー名(検索用)
$l_show_page		= "";									// 表示ページ番号
$l_max_page			= "";									// 最大ページ番号
$l_valid_checkstat	= "";									// 有効チェック
$l_tabname			= "";									// 現在表示中のタブ名
$l_user_show_page	= "";									// ユーザータブで表示中のページ数
$l_user_max_page	= "";									// ユーザータブの最大ページ数
$l_group_valid_check= "";									// グループの有効チェック
$l_user_valid_check	= "";									// ユーザーの有効チェック
$l_workbase_valid_check	= "";								// 作業拠点の有効チェック
$l_selected_user_id	= "";									// POSTされたユーザーID
$l_sess_user_id		= "";									// ログインユーザーID
$l_show_dtl_user_id	= "";									// 編集を表示するユーザーID
$lr_dtl_rec			= "";									// 編集表示用のレコード
$l_mess_passwd_1	= "";									// パスワード欄のメッセージ(上)
$l_mess_passwd_2	= "";									// パスワード欄のメッセージ(下)
//print "step3<br>\n";
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

if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}

/*----------------------------------------------------------------------------
	共通マスタ取得用インスタンス
----------------------------------------------------------------------------*/
function getCommonMaster($lr_where){
	require_once('../mdl/m_new_common_master.php');
	$lc_common = new NewCommonMaster('Y', $lr_where);
	$lr_common = $lc_common->getViewRecord();
	return $lr_common;
}

if($l_debug_mode==1){print("Step-共通マスタ取得");print "<br>\n";}

/*----------------------------------------------------------------------------
	権限取得用インスタンス
----------------------------------------------------------------------------*/
function getAuthorityMaster($lr_where){
	require_once('../mdl/m_auth_master.php');
	$lc_auth = new m_auth_master('Y', $lr_where);
	$lr_auth = $lc_auth->getViewRecord();
	return $lr_auth;
}

if($l_debug_mode==1){print("Step-権限マスタ取得");print "<br>\n";}
/*----------------------------------------------------------------------------
	セッション確認
----------------------------------------------------------------------------*/
require_once('../lib/sessionControl.php');
$lc_sess = new sessionControl();
$l_sess_token = $lc_sess->getToken();

// DATA_IDの取得
$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
//	if($l_data_id == ""){
//		if($l_debug_mode==3){throw new Exception('l_sess_data_idがNULL');}
//		throw new Exception($l_error_type_st);
//	}

// COMPANY_IDの取得
$l_sess_company_id = $lc_sess->getSesseionItem('COMPANY_ID');
if($l_sess_company_id == ""){
	throw new Exception($l_error_type_st);
}

// GROUP_IDの取得
$l_sess_group_id = $lc_sess->getSesseionItem('GROUP_ID');
if($l_sess_group_id == ""){
	throw new Exception($l_error_type_st);
}

// ユーザーIDの取得
$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
//	if($l_sess_user_id == ""){
//		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
//		throw new Exception($l_error_type_st);
//	}

if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}

/*----------------------------------------------------------------------------
	GET変数取得
----------------------------------------------------------------------------*/
// 会社名（検索用）
if(!is_null($_GET['cname'])){
	$l_comp_name_cond = $_GET['cname'];
}

// グループ名（検索用）
if(!is_null($_GET['gname'])){
	$l_group_name_cond = $_GET['gname'];
}

// ユーザー名（検索用）
if(!is_null($_GET['uname'])){
	$l_user_name_cond = $_GET['uname'];
}

// 表示ページ番号
if(!is_null($_GET['spage'])){
	$l_show_page = $_GET['spage'];
}

// 最大ページ番号
if(!is_null($_GET['mpage'])){
	$l_max_page = $_GET['mpage'];
}

// 会社ID
if(!is_null($_GET['cid'])){
	$l_selected_company_id = $_GET['cid'];
}

// グループID
if(!is_null($_GET['gid'])){
	$l_selected_group_id = $_GET['gid'];
}

// ユーザーID
if(!is_null($_GET['uid'])){
	$l_selected_user_id = $_GET['uid'];
}

// 有効チェック
if(!is_null($_GET['vcheck'])){
	$l_valid_checkstat = $_GET['vcheck'];
}

// 親ウインドウのページ名
if(!is_null($_GET['ppagename'])){
	$l_parent_pagename = $_GET['ppagename'];
}

// 現在表示中のタブ名
if(!is_null($_GET['tab'])){
	$l_tabname = $_GET['tab'];
}

// ユーザータブで表示中のページ数
if(!is_null($_GET['uspage'])){
	$l_user_show_page = $_GET['uspage'];
}

// ユーザータブの最大ページ数
if(!is_null($_GET['umpage'])){
	$l_user_max_page = $_GET['umpage'];
}

// グループの有効チェック
if(!is_null($_GET['vgcheck'])){
	$l_group_valid_check = $_GET['vgcheck'];
}

// ユーザーの有効チェック
if(!is_null($_GET['vucheck'])){
	$l_user_valid_check = $_GET['vucheck'];
}

// 作業拠点の有効チェック
if(!is_null($_GET['vwcheck'])){
	$l_workbase_valid_check = $_GET['vwcheck'];
}

// 新規の場合、パスワードにDEFAULT_PASSWORDをセットする
if ($_GET['uid'] == 'new'){
	$l_password = DEFAULT_PASSWORD;
}

if($l_debug_mode==1){print("Step-GET変数取得");print "<br>\n";}

/*----------------------------------------------------------------------------
	DBデータ取得
----------------------------------------------------------------------------*/
// ユーザーMDL
require_once('../mdl/m_user_master.php');
if($l_selected_user_id != "new"){
	// ------------------------------
	// 編集表示用のデータ取得
	// ------------------------------
	// 編集に表示するデータのユーザーIDを選択
	// GETされたユーザーIDがあれば、GETされたユーザーID、
	// なければセッションに保持されたユーザーIDを表示対象とする

	if($l_selected_user_id != ""){
		$l_show_dtl_user_id	= $l_selected_user_id;
	}else{
		$l_show_dtl_user_id	= $l_sess_user_id;
	}

	// 検索条件設定
	$lr_user_cond_dtl = array('USER_ID = '.$l_show_dtl_user_id);

	// レコード取得
	$l_user_mum_dtl = new m_user_master('Y', $lr_user_cond_dtl);
	$lr_user_detail = $l_user_mum_dtl->getViewRecord();
	//	print_r($lr_user_detail);

	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_save", "value" => "保存"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// 会社ID
	$l_campany_id = $lr_user_detail[1]['COMPANY_ID'];
	$l_company_name = $lr_user_detail[1]['COMPANY_NAME'];

	// グループID
	$l_group_id = $lr_user_detail[1]['GROUP_ID'];
	$l_group_name = $lr_user_detail[1]['GROUP_NAME'];

	// ユーザーID
	$l_user_id = $l_show_dtl_user_id;

	// パスワード
	// 初期値
		/*
		$l_password = $lr_user_detail[1]['PASSWORD'];
		// 暗号化パスワード
		$l_encryption_password = $lr_user_detail[1]['ENCRYPTION_PASSWORD'];
		 */
	// パスワードメッセージ設定は上が旧、下が新
	$l_mess_passwd_1 = "現在のパスワード";
	$l_mess_passwd_2 = "新しいパスワード";
/*		
		if(isset($lr_user_detail[1]['ENCRYPTION_PASSWORD'])){
			$l_encryption_password = md5($lr_user_detail[1]['PASSWORD']);
		}else{
			$l_encryption_password = $lr_user_detail[1]['ENCRYPTION_PASSWORD'];
		}
 */		

	// SQLタイプ
	$l_edit_sql_type = "update";
}else{
	$l_user_mum_dtl = new m_user_master('Y');
	// 会社ID
	if($l_selected_company_id != ""){
		$l_campany_id	= $l_selected_company_id;
	}else{
		$l_campany_id	= $l_sess_company_id;
	}

	// 会社MDL
	require_once('../mdl/m_company_master.php');
	// 検索条件設定
	$lr_company_cond_dtl = array('COMPANY_ID = '.$l_campany_id);
	// レコード取得
	$l_mcm_dtl = new m_company_master('Y', $lr_company_cond_dtl);
	$lr_company_detail = $l_mcm_dtl->getViewRecord();

	$l_company_name = $lr_company_detail[1]['COMPANY_NAME'];

	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_create", "value" => "作成"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// グループID
	$l_group_id = "";

	// ユーザーID
	$l_user_id = "";

	// パスワードメッセージ設定は上が入力、下が確認
	$l_mess_passwd_1 = "パスワード";
	$l_mess_passwd_2 = "パスワード確認用再入力";


	// SQLタイプ
	$l_edit_sql_type = "insert";
}

if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>\n";}

/*----------------------------------------------------------------------------
	共通マスタ取得
----------------------------------------------------------------------------*/
/*--------------------*/
// 性別
/*--------------------*/
// where句の設定
$lr_where_phrase = array();
$lr_where_phrase[$l_where_cnt++] = "DATA_ID = ".$l_sess_data_id;			// DATA_ID
$lr_where_phrase[$l_where_cnt++] = "CODE_SET = 'SEX'";						// CODE_SET

// レコード取得
$lr_common_sex = getCommonMaster($lr_where_phrase);

$l_rec_cnt = 0;
foreach($lr_common_sex as $key => $common_val){
	$l_rec_cnt++;
	if($common_val[CODE_NAME] == htmlspecialchars($lr_user_detail[1]['SEX'])){
		$ar_gender[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE] , "checked" => COLKEY_CHECKED);
	}else{
		$ar_gender[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE]);
	}
}

/*--------------------*/
// 支払区分
/*--------------------*/
// where句の設定
$lr_where_phrase = array();
$lr_where_phrase[$l_where_cnt++] = "DATA_ID = ".$l_sess_data_id;			// DATA_ID
$lr_where_phrase[$l_where_cnt++] = "CODE_SET = 'PAYMENT_DIVISION'";			// CODE_SET

// レコード取得
$lr_common_payment = getCommonMaster($lr_where_phrase);

// 支払区分リスト作成
$l_rec_cnt = 0;
foreach($lr_common_payment as $key => $common_val){
	$l_rec_cnt++;
	if($common_val[CODE_NAME] == htmlspecialchars($lr_user_detail[1]['PAYMENT_DIVISION'])){
		$ar_payment_division[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE] , "selected" => COLKEY_SELECTED);
	}else{
		$ar_payment_division[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE]);
	}
}

/*--------------------*/
// 遅延警告許可
/*--------------------*/
// where句の設定
$lr_where_phrase = array();
$lr_where_phrase[$l_where_cnt++] = "DATA_ID = ".$l_sess_data_id;			// DATA_ID
$lr_where_phrase[$l_where_cnt++] = "CODE_SET = 'ALERT_PERMISSION_FLAG'";	// CODE_SET

// レコード取得
$lr_common_alert = getCommonMaster($lr_where_phrase);

// 遅延警告許可リスト作成
$l_rec_cnt = 0;
foreach($lr_common_alert as $key => $common_val){
	$l_rec_cnt++;
	if($common_val[CODE_NAME] == htmlspecialchars($lr_user_detail[1]['ALERT_PERMISSION_FLAG'])){
		$ar_alert_permission[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE] , "checked" => COLKEY_CHECKED);
	}else{
		$ar_alert_permission[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE]);
	}
}

/*--------------------*/
// 有効フラグ
/*--------------------*/
$ar_validity = $l_user_mum_dtl->createValidityFlagList($l_sess_data_id, $lr_user_detail[1]['VALIDITY_FLAG']);

if($l_debug_mode==1){print("Step-共通マスタレコードの取得");print "<br>\n";}


/*----------------------------------------------------------------------------
	権限マスタ取得
----------------------------------------------------------------------------*/
/*--------------------*/
// 権限
/*--------------------*/
// where句の設定
$lr_where_phrase = array();
$lr_where_phrase[$l_where_cnt++] = "DATA_ID = ".$l_sess_data_id;			// DATA_ID

// レコード取得
$lr_authority = getAuthorityMaster($lr_where_phrase);
//print_r($lr_authority);

// 権限リスト作成
$l_rec_cnt = 0;
foreach($lr_authority as $key => $auth_val){
	$l_rec_cnt++;
	if($auth_val[AUTHORITY_ID] == htmlspecialchars($lr_user_detail[1]['AUTHORITY_ID'])){
		$ar_authority[$l_rec_cnt] = array("name" => $auth_val[AUTHORITY_CODE], "value" => $auth_val[AUTHORITY_ID], "itemname" => $auth_val[AUTHORITY_NAME] , "selected" => COLKEY_SELECTED);
	}else{
		$ar_authority[$l_rec_cnt] = array("name" => $auth_val[AUTHORITY_CODE], "value" => $auth_val[AUTHORITY_ID], "itemname" => $auth_val[AUTHORITY_NAME]);
	}
}

if($l_debug_mode==1){print("Step-権限マスタレコードの取得");print "<br>\n";}

/*----------------------------------------------------------------------------
	Smarty設定
----------------------------------------------------------------------------*/
/*-----------------------------------
	Smarty変数定義
-----------------------------------*/
$ar_css_files	= NULL;			// CSSファイル
$ar_js_files	= NULL;			// jsファイル

if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>\n";}
/*-----------------------------------
	Smarty変数セット
-----------------------------------*/
// CSSファイル
$ar_css_files	= array(DIR_CSS."v_edit_masters.css", 
DIR_CSS."v_edit_block.css", 
DIR_CSS."v_valuelist_div.css", 
DIR_CSS."example.css", 
DIR_CSS."jquery-ui-custom.css");
//$ar_css_files	= array(DIR_CSS."v_edit_masters.css", DIR_CSS."v_edit_block.css", DIR_CSS."v_valuelist_div.css", DIR_CSS."ui.all.css");
// jsファイル
$ar_js_files	= array(DIR_JS."jquery.js", 
	DIR_JS."jfnc_common.js", 
	DIR_JS."jfnc_common_edit.js", 
	DIR_JS."jfnc_users_edit.js", 
	DIR_JS."jfnc_value_list_edit.js", 
	DIR_JS."jquery.updnWatermark.js", 
	DIR_JS."jquery-ui-custom.min.js");
//$ar_js_files	= array(DIR_JS."jquery.js", DIR_JS."jfnc_common.js", DIR_JS."jfnc_common_edit.js", DIR_JS."jfnc_user_edit.js", DIR_JS."jfnc_value_list_edit.js", DIR_JS."ui.core.js", DIR_JS."ui.datepicker.js");

if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>\n";}
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
if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>\n";}	

// ------------------------------
// インクルードするテンプレート
// ------------------------------
$lc_smarty->assign("edit_include_tpl"				, "users_edit_block.tpl");		// 編集

// ------------------------------
// Smartyセット
// ------------------------------
$lc_smarty->assign("headtitle"						, "ユーザー管理");				// 画面タイトル（javascriptの判別に使用）
$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル

// ------------------------------
// 編集項目
// ------------------------------
// ボタン
$lc_smarty->assign("edit_button"					, $ar_button);

// データレコード
$lc_smarty->assign("edit_table_item"				, $lr_user_detail[1]);

// パスワード
$lc_smarty->assign("edit_password"					, $l_password);
if ($_GET['uid'] == 'new'){
	$lc_smarty->assign("passwd_default_mess"			, "<br>（デフォルトの初期パスワードは、'".$l_password."'です）");
}
$lc_smarty->assign("passwd_mess1"					, $l_mess_passwd_1);
$lc_smarty->assign("passwd_mess2"					, $l_mess_passwd_2);

//// 会社名
//$lc_smarty->assign("cond_comp_name"					, $l_comp_name_cond);
//
//// グループ名
//$lc_smarty->assign("cond_group_name"				, $l_group_name_cond);

// 会社名
$lc_smarty->assign("edit_company_name"				, $l_company_name);

// グループ名
$lc_smarty->assign("edit_group_name"				, $l_group_name);

// 性別
$lc_smarty->assign("edit_gender"					, $ar_gender);

// 支払区分
$lc_smarty->assign("edit_payment_division"			, $ar_payment_division);

// 遅延警告
$lc_smarty->assign("edit_permission"				, $ar_alert_permission);

// 有効フラグ
$lc_smarty->assign("edit_validity"					, $ar_validity);

// 権限
$lc_smarty->assign("edit_authority"					, $ar_authority);

// ------------------------------
// 隠し項目
// ------------------------------
// 最大ページの取得
$lr_hidden_items	= array(
	array(									// データID
		"name"	=> "DATA_ID"
		, "value"	=> $l_sess_data_id
	),
	array(									// 会社ID
		"name"	=> "COMPANY_ID"
		, "value"	=> $l_campany_id
	),
	array(									// グループID
		"name"	=> "GROUP_ID"
		, "value"	=> $l_group_id
	),
	array(									// ユーザーID
		"name"	=> "USER_ID"
		, "value"	=> $l_user_id
	),
	array(									// 暗号化パスワード
		"name"	=> "ENCRYPTION_PASSWORD"
		, "value"	=> $l_encryption_password
	),
	array(									// SQLタイプ
		"name"	=> "sql_type"
		, "value"	=> $l_edit_sql_type
	),
	array(									// トークン
		"name"	=> "nm_token_code"
		, "value"	=> $l_sess_token
	),
	array(									// 会社名(検索用)
		"name"	=> "nm_comp_name_cond"
		, "value"	=> $l_comp_name_cond
	),
	array(									// グループ名(検索用)
		"name"	=> "nm_group_name_cond"
		, "value"	=> $l_group_name_cond
	),
	array(									// ユーザー名(検索用)
		"name"	=> "nm_user_name_cond"
		, "value"	=> $l_user_name_cond
	),
	array(									// 表示ページ番号
		"name"	=> "nm_show_page"
		, "value"	=> $l_show_page
	),
	array(									// 最大ページ番号
		"name"	=> "nm_max_page"
		, "value"	=> $l_max_page
	),
	array(									// ユーザーID
		"name"	=> "nm_selected_user_id"
		, "value"	=> $l_show_dtl_user_id
	),
	array(									// 有効データチェック状態
		"name"	=> "nm_valid_checkstat"
		, "value"	=> $l_valid_checkstat
	),
	array(									// 親ウインドウのページ名
		"name"	=> "nm_parent_pagename"
		, "value"	=> $l_parent_pagename
	),
	array(									// 現在表示中のタブ名
		"name"	=> "nm_selected_tb"
		, "value"	=> $l_tabname
	),
	array(									// ユーザータブで表示中ページ数
		"name"	=> "nm_user_show_page"
		, "value"	=> $l_user_show_page
	),
	array(									// ユーザータブの最大ページ数
		"name"	=> "nm_user_max_page"
		, "value"	=> $l_user_max_page
	),
	array(									// グループタブの有効チェック
		"name"	=> "nm_group_valid_checkstat"
		, "value"	=> $l_group_valid_check
	),
	array(									// ユーザータブの有効チェック
		"name"	=> "nm_user_valid_checkstat"
		, "value"	=> $l_user_valid_check
	),
	array(									// 作業拠点タブの有効チェック
		"name"	=> "nm_workbase_valid_checkstat"
		, "value"	=> $l_workbase_valid_check
	),
	array(									// 会社ID（会社一覧表示用）
		"name"	=> "nm_selected_company_id"
		, "value"	=> $l_campany_id
	)
);
$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>\n";}

/*-----------------------------------
	ページ表示
-----------------------------------*/
$lc_smarty->display('EditMasterMain.tpl');
if($l_debug_mode==1){print("Step-完了");print "<br>\n";}
?>
