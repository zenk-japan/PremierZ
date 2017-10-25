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
 ファイル名：projects_edit.php
 処理概要   プロジェクト管理編集画面
 GET受領値：
						 bid                        動作モード(必須) insert or udpate
						 nm_token_code              トークン(必須)
						 nm_estimate_id             見積ID(必須)
******************************************************************************/
$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
if($l_debug_mode == 1 || $l_debug_mode == 2){
	print "post-><br>";
	print var_dump($_GET);
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

//print "step2<br>";
/*----------------------------------------------------------------------------
	変数宣言
----------------------------------------------------------------------------*/
$l_page_name			= "PROJECTS_EDIT";
$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
$l_sess_data_id			= "";									// 画面にセットするDATA_ID
$l_proc_mode			= "";									// 動作モード
$l_estimate_id			= "";									// 見積ID
$l_new_sub_number		= "";									// 更新用の枝番
$l_sess_user_name		= "";									// 実行ユーザー名
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

if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
	GET変数取得
----------------------------------------------------------------------------*/
// 動作モード
if(!is_null($_GET['bid'])){
	$l_proc_mode = $_GET['bid'];
}

// 見積ID
if(!is_null($_GET['nm_estimate_id'])){
	$l_estimate_id = $_GET['nm_estimate_id'];
}


if($l_debug_mode==1){print("Step-GET変数取得");print "<br>";}

/*----------------------------------------------------------------------------
	DBデータ取得
----------------------------------------------------------------------------*/
// 見積MDL
require_once('../mdl/m_estimates.php');
if($l_proc_mode == OPMODE_INSERT){
	$lc_model_class = new m_estimates('Y');
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
	$lr_cond_dtl = array('ESTIMATE_ID = '.$l_estimate_id);

	// レコード取得
	$lc_model_class = new m_estimates('Y', $lr_cond_dtl);
	$lr_update_data = $lc_model_class->getViewRecord();
	//print_r($lr_update_data);

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
// 注文区分
$lr_order_division	= $lr_common->getCommonValueRec($l_sess_data_id, "ORDER_DIVISION");

// 作業区分
$lr_work_division	= $lr_common->getCommonValueRec($l_sess_data_id, "WORK_DIVISION");

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
DIR_CSS."v_edit_block.css", 
DIR_CSS."example.css", 
DIR_CSS."v_valuelist_div.css"
							);

// jsファイル
$ar_js_files	= array(DIR_JS."jquery.js", 
	DIR_JS."jquery-ui-custom.min.js", 
	DIR_JS."jfnc_value_list_edit.js", 
	DIR_JS."jfnc_common.js", 
	DIR_JS."jfnc_projects_edit.js", 
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
$lc_smarty->assign("edit_include_tpl"				, "projects_edit_block.tpl");	// 編集

// ------------------------------
// Smartyセット
// ------------------------------
$lc_smarty->assign("headtitle"						, "プロジェクト管理");			// 画面タイトル
$lc_smarty->assign("ar_js_files"					, $ar_js_files);				// jsファイル
$lc_smarty->assign("ar_css_files"					, $ar_css_files);				// CSSファイル
$lc_smarty->assign("proc_mode"						, $l_proc_mode);				// モード

// ------------------------------
// 編集項目
// ------------------------------	
// ボタン
$lc_smarty->assign("edit_button"					, $ar_button);

// 見積枝番
if($l_proc_mode != OPMODE_INSERT){
	// 更新
	// 数値の場合、２桁の連番とし、１インクリメントして出力
	$l_new_sub_number = $lr_update_data[1]['SUB_NUMBER'];
	if (preg_match('/^\d\d$/', $l_new_sub_number)){
		$ln_new_sub_number	= intval($l_new_sub_number) + 1;
		$l_new_sub_number	= sprintf("%02d", $ln_new_sub_number);
	}
	$lc_smarty->assign("sub_number_update"				, $l_new_sub_number);
}else{
	// 新規
	$lc_smarty->assign("sub_number_default"				, "00");
}

// 見積担当者
if($l_proc_mode == OPMODE_INSERT){
	// 新規の場合は実行ユーザーを自動的にセットする
	$lc_smarty->assign("estimate_user_name_default"		, $l_sess_user_name);
}

// データレコード
$lc_smarty->assign("edit_table_item"				, $lr_update_data[1]);

// 有効フラグ
$lc_smarty->assign("edit_validity"					, $ar_validity);

// 注文区分
$lc_smarty->assign("ar_order_division"				, $lr_order_division);

// 作業区分
$lc_smarty->assign("ar_work_division"				, $lr_work_division);

// ------------------------------
// 隠し項目
// ------------------------------
$lr_hidden_items	= array(

	array(									// データID
		"name"	=> "DATA_ID"
		, "value"	=> $l_sess_data_id
	),
	array(									// SQLタイプ
		"name"	=> "sql_type"
		, "value"	=> $l_edit_sql_type
	),
	array(									// トークン
		"name"	=> "nm_token_code"
		, "value"	=> $l_sess_token
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
