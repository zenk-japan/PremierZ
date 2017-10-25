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
 ファイル名：companies_edit.php
 処理概要   会社管理編集画面
 GET受領値：
						 cid                        会社ID(必須)
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
$l_sess_data_id			= "";									// 画面にセットするDATA_ID
$l_selected_company_id	= "";									// GETした会社ID
$l_show_dtl_user_id		= "";									// 明細を表示するユーザーID
$l_comp_class_cond		= "";									// 会社区分
$lr_common_comp_class	= array();								// 会社区分用配列
$lr_common_validity		= array();								// 有効フラグ用配列
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
	セッション確認
----------------------------------------------------------------------------*/
require_once('../lib/sessionControl.php');
$lc_sess = new sessionControl();

// トークンの取得
$l_sess_token = $lc_sess->getToken();

// ユーザーIDの取得
$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
if($l_sess_user_id == ""){
	throw new Exception($l_error_type_st);
}

// DATA_IDの取得
$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
if($l_sess_data_id == ""){
	throw new Exception($l_error_type_st);
}

// COMPANY_IDの取得
$l_sess_company_id = $lc_sess->getSesseionItem('COMPANY_ID');
if($l_sess_company_id == ""){
	throw new Exception($l_error_type_st);
}

if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
	GET変数取得
----------------------------------------------------------------------------*/
// 会社ID
if(!is_null($_GET['cid'])){
	$l_selected_company_id = $_GET['cid'];
}

// 会社名（検索用）
if(!is_null($_GET['cname'])){
	$l_comp_name_cond = $_GET['cname'];
}

// 会社区分(検索用)
if(!is_null($_POST['nm_comp_class_cond'])){
	$l_comp_class_cond = $_POST['nm_comp_class_cond'];
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

// 有効チェック
if(!is_null($_GET['vcheck'])){
	$l_valid_checkstat = $_GET['vcheck'];
}

// 現在表示中のタブ名
if(!is_null($_GET['tab'])){
	$l_tabname = $_GET['tab'];
}

// グループタブで表示中のページ数
if(!is_null($_GET['gspage'])){
	$l_group_show_page = $_GET['gspage'];
}

// グループタブの最大ページ数
if(!is_null($_GET['gmpage'])){
	$l_group_max_page = $_GET['gmpage'];
}

// ユーザータブで表示中のページ数
if(!is_null($_GET['uspage'])){
	$l_user_show_page = $_GET['uspage'];
}

// ユーザータブの最大ページ数
if(!is_null($_GET['umpage'])){
	$l_user_max_page = $_GET['umpage'];
}

// 作業拠点タブで表示中のページ数
if(!is_null($_GET['bspage'])){
	$l_workbase_show_page = $_GET['bspage'];
}

// 作業拠点タブの最大ページ数
if(!is_null($_GET['bmpage'])){
	$l_workbase_max_page = $_GET['bmpage'];
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

if($l_debug_mode==1){print("Step-GET変数取得");print "<br>";}

/*----------------------------------------------------------------------------
	DBデータ取得
----------------------------------------------------------------------------*/
require_once('../mdl/m_company_master.php');
if($l_selected_company_id != "new"){
	// ------------------------------
	// 編集表示用のデータ取得
	// ------------------------------
	// 明細に表示するデータの会社IDを選択
	// POSTされた会社IDがあれば、POSTされた会社ID、
	// なければセッションに保持された会社IDを表示対象とする

	if($l_selected_company_id != ""){
		$l_show_dtl_company_id	= $l_selected_company_id;
	}else{
		$l_show_dtl_company_id	= $l_sess_company_id;
	}

	// 検索条件設定
	$lr_company_cond_dtl = array('COMPANY_ID = '.$l_show_dtl_company_id);

	// レコード取得
	$l_mum_dtl = new m_company_master('Y', $lr_company_cond_dtl);
	$lr_company_detail = $l_mum_dtl->getViewRecord();
	//print_r($lr_company_detail);

	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_save", "value" => "保存"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// SQLタイプ
	$l_edit_sql_type = "update";
}else{
	$l_mum_dtl = new m_company_master('Y');
	// ボタン
	$ar_button = array(
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_create", "value" => "作成"),
		array("class" => "c_btn_edit_menu", "type" => "button", "id" => "id_btn_cancel", "value" => "キャンセル")
	);

	// SQLタイプ
	$l_edit_sql_type = "insert";
}

if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}

/*----------------------------------------------------------------------------
	共通マスタ取得
----------------------------------------------------------------------------*/
/*--------------------*/
// 会社区分
/*--------------------*/
// where句の設定
$lr_where_phrase = array();
$lr_where_phrase[$l_where_cnt++] = "DATA_ID = ".$l_sess_data_id;			// DATA_ID
$lr_where_phrase[$l_where_cnt++] = "CODE_SET = 'COMP_CLASS'";				// CODE_SET

// レコード取得
$lr_common_comp_class = getCommonMaster($lr_where_phrase);

// レコード取得
$lr_common_comp_class = getCommonMaster($lr_where_phrase);

// 分類区分リスト作成
$l_rec_cnt = 0;

$l_rec_cnt++;
$ar_comp_class[$l_rec_cnt] = array("name" => "COMP_CLASS", "value" => "", "itemname" => "");

foreach($lr_common_comp_class as $key => $common_val){
	$l_rec_cnt++;
	if($common_val[CODE_NAME] == htmlspecialchars($lr_company_detail[1]['COMP_CLASS'])){
		$ar_comp_class[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE] , "selected" => COLKEY_SELECTED);
	}else{
		$ar_comp_class[$l_rec_cnt] = array("name" => $common_val[CODE_SET], "value" => $common_val[CODE_NAME], "itemname" => $common_val[CODE_VALUE]);
	}
}
/*--------------------*/
// 有効フラグ
/*--------------------*/
$ar_validity = $l_mum_dtl->createValidityFlagList($l_sess_data_id, $lr_company_detail[1]['VALIDITY_FLAG']);

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
$ar_css_files	= array(DIR_CSS."v_edit_masters.css", DIR_CSS."v_edit_block.css", DIR_CSS."example.css");

// jsファイル
$ar_js_files	= array(DIR_JS."jquery.js", DIR_JS."jfnc_common.js", DIR_JS."jfnc_common_edit.js", DIR_JS."jfnc_company_edit.js", DIR_JS."jquery.updnWatermark.js");

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
$lc_smarty->assign("edit_include_tpl"				, "companies_edit_block.tpl");	// 編集

// ------------------------------
// Smartyセット
// ------------------------------
$lc_smarty->assign("headtitle"						, "会社管理");					// 画面タイトル
$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル

// ------------------------------
// 編集項目
// ------------------------------
$lc_smarty->assign("headtitle"						, "グループ管理");

// ボタン
$lc_smarty->assign("edit_button"					, $ar_button);

// データレコード
$lc_smarty->assign("edit_table_item"				, $lr_company_detail[1]);

// 会社区分
$lc_smarty->assign("edit_comp_class"				, $ar_comp_class);

// 有効フラグ
$lc_smarty->assign("edit_validity"					, $ar_validity);

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
		, "value"	=> $lr_company_detail[1]['COMPANY_ID']
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
	array(									// 会社区分(検索用)
		"name"	=> "nm_comp_class_cond"
		, "value"	=> $l_comp_class_cond
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
	array(									// グループタブで表示中ページ数
		"name"	=> "nm_group_show_page"
		, "value"	=> $l_group_show_page
	),
	array(									// グループタブの最大ページ数
		"name"	=> "nm_group_max_page"
		, "value"	=> $l_group_max_page
	),
	array(									// ユーザータブで表示中ページ数
		"name"	=> "nm_user_show_page"
		, "value"	=> $l_user_show_page
	),
	array(									// ユーザータブの最大ページ数
		"name"	=> "nm_user_max_page"
		, "value"	=> $l_user_max_page
	),
	array(									// 作業拠点タブで表示中ページ数
		"name"	=> "nm_workbase_show_page"
		, "value"	=> $l_workbase_show_page
	),
	array(									// 作業拠点タブの最大ページ数
		"name"	=> "nm_workbase_max_page"
		, "value"	=> $l_workbase_max_page
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
		, "value"	=> $lr_company_detail[1]['COMPANY_ID']
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
