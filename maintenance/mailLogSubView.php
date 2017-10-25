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
 ファイル名：mailLogSubView.php
 処理概要  ：メールログサブ画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_mail_log_id             メールログID(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_POST);
		print "step1<br>";
		print "<br>";
		print_r($_SESSION);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// DATA_ID
	$l_mail_log_id		= "";									// メールログID
	
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_maillogsubv(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_maillogsubv');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception("不正なアクセスです。");
	}
	
	require_once('../maintenance/c_sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		throw new Exception("不正なアクセスです。");
	}
	if($l_post_token != $l_sess_token){
		throw new Exception("不正なアクセスです。");
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
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
  POST変数はセッションの値より優先される
  ----------------------------------------------------------------------------*/
	// メールログIDを取得
	if(!is_null($_POST['nm_mail_log_id'])){
		$l_mail_log_id = $_POST['nm_mail_log_id'];
	}else{
		throw new Exception("不正なアクセスです。");
	}
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
// ==================================
// Smarty変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>";}
// ==================================
// Smarty変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array("v_mnt_main.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS . "jquery.js", "callLogSubView.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
// ==================================
// Smartyセット
// ==================================
	// ------------------------------
	// クラスインスタンス作成
	// ------------------------------
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = "./";									// テンプレートはphpと同じディレクトリに収めるため
	$lc_smarty->compile_dir  = $l_dir_prfx.DIR_TEMPLATES_C;
	
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}
	
	// ------------------------------
	// 検索領域
	// ------------------------------
	// リスト用データ取得
	require_once('../mdl/m_mail_log.php');
	$lc_mail_log = new m_mail_log('Y');
	// ------------------------------
	// データ取得用の条件配列作成
	// ------------------------------
	$lr_where		= "";
	$l_where_cnt	= 0;
  
	// MAIL_LOG_ID
	$lr_where[$l_where_cnt++] = "MAIL_LOG_ID = ".$l_mail_log_id;
	
	// where と order byのセット
	$lc_mail_log->setWhereArray($lr_where);
	
	if($l_debug_mode==1){print("Step-データ取得用の条件配列作成");print "<br>";}
	
	// ------------------------------
	// 明細領域
	// ------------------------------
	// レコード再取得
	$lc_mail_log->queryDBRecord();
	
	// 表示用のレコードを再編集
	$lr_db_data		= $lc_mail_log->getViewRecord();			// データレコード
	$lr_db_head_j	= $lc_mail_log->getColumnComment();			// 項目名のコメントを取得
	//print var_dump($lr_db_data)."<br>";
	//print var_dump($lr_db_head_j)."<br>";
	if($l_debug_mode==1){print("Step-表示用のレコードを再編集");print "<br>";}
		
	// 各項目のアサイン
	$lc_smarty->assign("send_date"		, date("Y-m-d H:i:s",strtotime($lr_db_data[1]['LAST_UPDATE_DATET'])));
	$lc_smarty->assign("send_purpose"	, htmlspecialchars($lr_db_data[1]['SEND_PURPOSE']));
	$lc_smarty->assign("from_addr"		, htmlspecialchars($lr_db_data[1]['FROM_ADDRESS']));
	$lc_smarty->assign("to_addr"		, htmlspecialchars($lr_db_data[1]['TO_ADDRESS']));
	$lc_smarty->assign("mail_title"		, htmlspecialchars($lr_db_data[1]['MAIL_TITLE']));
	$lc_smarty->assign("mail_body"		, htmlspecialchars($lr_db_data[1]['MAIL_BODY']));
	
	// 送信者情報
	$l_sender_info  = "DATA_ID : ".$lr_db_data[1]['DATA_ID']."<br>";
	$l_sender_info .= "会社名 : ".htmlspecialchars($lr_db_data[1]['SEND_USER_COMPANY'])."<br>";
	$l_sender_info .= "組織名 : ".htmlspecialchars($lr_db_data[1]['SEND_USER_GROUP'])."<br>";
	$l_sender_info .= "ユーザー名 : ".htmlspecialchars($lr_db_data[1]['SEND_USER_NAME']);
	$lc_smarty->assign("send_user"		, $l_sender_info);
		
	if($l_debug_mode==1){print("Step-明細領域の表示");print "<br>";}
	
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('MailLogSubView.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>
