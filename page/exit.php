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
 ファイル名：exit.php
 処理概要  ：ログアウト
 POST受領値：
             なし
******************************************************************************/
	$l_dir_prfx		= "./";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "step1<br>";
	//print "<br>";
	//print_r($_SESSION);
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix	= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts		= "<BR>";								// HTMLの改行
	$l_error_type_st	= "ST";								// エラータイプ(ST:セッション断)

	//print "step3<br>";
// ==================================
// 例外定義
// ==================================
	function my_exception_logout(Exception $e){
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
	set_exception_handler('my_exception_logout');
	
// ==================================
// セッション破棄
// ==================================
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	
	// データIDの取得
	$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	
	if(is_null($l_sess_data_id)){
		// exit画面でリロードした場合はセッションが消えているので何もしない
		//throw new Exception('クラスが作成できませんでした');
	}else{
	
		// 検索条件設定
		$lr_query_cond = array(
									"DATA_ID	= ".$l_sess_data_id,
									"USER_ID	= ".$l_sess_user_id,
								);
		
		// m_session_masterクラスインスタンス作成
		require_once('../mdl/m_session_master.php');
		// レコード取得
		$lc_m_sess = new m_session_master('Y', $lr_query_cond, array('SESSION_ID'));
		$lr_query_records = $lc_m_sess->getViewRecord();
		//print_r($lr_query_records);
		
		// SESSIONS更新
		$lr_data["DATA_ID"] = $l_sess_data_id;
		$lr_data["SESSION_ID"] = $lr_query_records[1][SESSION_ID];
		$lr_data["USER_ID"] = $l_sess_user_id;
		$lr_data["SESSID"] = '';
		$lr_data["SESS_TOKEN"] = '';
		$lr_data["LOGIN_FLAG"] = 'N';
		
		// レコードセット
		$lc_m_sess->setSaveRecord($lr_data);
		
		$lc_m_sess->updateRecord($l_sess_user_id);
		
		$lc_sess->destroySession();						// セッション破棄
	
	}
	
// ==================================
// 変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	$copyright_text	= NULL;			// コピーライト
	//print "step6<br>";
// ==================================
// 変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array(DIR_CSS."v_exit.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js");
	// コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";

	//print "step7<br>";
// ==================================
// Smartyセット
// ==================================
	// クラスインスタンス作成
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = DIR_TEMPLATES;
	$lc_smarty->compile_dir  = DIR_TEMPLATES_C;
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."ログアウト");		// タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("txt_copyright",	$copyright_text);				// コピーライト
	$lc_smarty->assign("login_page",	"entrance.php");				// ログイン画面
		
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('Exit.tpl');
	//print "step9<br>";
?>
