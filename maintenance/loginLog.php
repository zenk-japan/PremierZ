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
 ファイル名：loginLog.php
 処理概要  ：ログインログ画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_use_page                使用ページ(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_used_user_code          ユーザコード(任意)
             nm_used_comp_code          会社コード(任意)
             nm_certification_result    認証結果(任意)
             nm_date_from               ログイン日時From(任意)
             nm_date_to                 ログイン日時To(任意)
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
	$l_mes_sufix				= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts					= "<BR>";								// HTMLの改行
	$l_post_token				= "";									// POSTされたトークン
	$l_sess_token				= "";									// セッションで保持しているトークン
	$l_user_name				= "";									// セッションで保持しているユーザー名
	$l_code_set					= "";									// 画面にセットするコードセット
	$l_show_pagenum				= "1";									// 表示を開始するページ番号
	$l_number_to_show			= "10";									// 表示するレコードの数
	$l_max_page					= "";									// 最大ページ番号
	$lr_params					= "";									// 各種パラメータ
	$l_used_user_code			= "used_user_code";
	$l_used_comp_code			= "used_company_code";
	$l_date_from				= "last_update_datet_from";
	$l_date_to					= "last_update_datet_to";
	$l_certification_result		= "certification_result";
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_loginlog(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_loginlog');
	
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
	
	// 使用ページを取得
	if(!is_null($_POST['nm_use_page'])){
		$l_use_page = $_POST['nm_use_page'];
	}else{
		$l_use_page = "";
	}
	
	// 表示するページ番号を取得
	if(!is_null($_POST['nm_show_page'])){
		$l_show_pagenum = $_POST['nm_show_page'];
	}else{
		$l_show_pagenum = 1;
	}
	
	// 最大ページ番号を取得
	if(!is_null($_POST['nm_max_page'])){
		$l_max_page = $_POST['nm_max_page'];
	}else{
		$l_max_page = 1;
	}
	
	// ユーザコードを取得
	if(!is_null($_POST['nm_used_user_code'])){
		$lr_params[$l_used_user_code] = $_POST['nm_used_user_code'];
	}
	
	// 会社コードを取得
	if(!is_null($_POST['nm_used_comp_code'])){
		$lr_params[$l_used_comp_code] = $_POST['nm_used_comp_code'];
	}
	
	// 認証結果を取得
	if(!is_null($_POST['nm_certification_result'])){
		$lr_params[$l_certification_result] = $_POST['nm_certification_result'];
	}
	else {
		// デフォルトは0（すべて）を設定
		$lr_params[$l_certification_result] = "0";
	}
	
	// ログイン日時Fromを取得
	if(!is_null($_POST['nm_date_from'])){
		$lr_params[$l_date_from] = $_POST['nm_date_from'];
	}
	
	// ログイン日時Toを取得
	if(!is_null($_POST['nm_date_to'])){
		$lr_params[$l_date_to] = $_POST['nm_date_to'];
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
	$ar_css_files	= array("v_mnt_main.css", "v_type2_search.css", "v_type2_table.css", "v_sub_view.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS . "jquery.js", "callLogSubView.js", "maintenance.js", "loginLog.js", "mntLogView.js");

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
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."管理");			// 画面タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	$lc_smarty->assign("maintitle",		"ログインログ");					// メインタイトル

	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}
	
	// ------------------------------
	// 検索領域
	// ------------------------------
	// リスト用データ取得
	require_once('../mdl/m_login_log.php');
	$lc_login_log = new m_login_log('Y');
	// ------------------------------
	// データ取得用の条件配列作成
	// ------------------------------
	$lr_where		= $lc_login_log->makeWherePhrase($lr_params);
	$lr_orderby		= array("LAST_UPDATE_DATET desc");

	// where と order byのセット
	$lc_login_log->setWhereArray($lr_where);
	$lc_login_log->setOrderyBy($lr_orderby);
	
	
	if($l_debug_mode==1){print("Step-データ取得用の条件配列作成");print "<br>";}
	
	// ------------------------------
	// リスト出力
	// ------------------------------

	
	// ------------------------------
	// その他出力
	// ------------------------------
	$lc_smarty->assign("dsp_src_user_code",		"ユーザコード");						// ユーザコード見出し
	$lc_smarty->assign("txt_src_user_code",		$lr_params[$l_used_user_code]);			// ユーザコード入力
	$lc_smarty->assign("dsp_src_comp_code",		"会社コード");							// 会社コード見出し
	$lc_smarty->assign("txt_src_comp_code",		$lr_params[$l_used_comp_code]);			// 会社コード入力
	$lc_smarty->assign("dsp_src_okng",			"認証結果");							// 認証結果見出し
	$lc_smarty->assign("dsp_src_date_cap",		"ログイン日時");						// ログイン日時見出し
	$lc_smarty->assign("dsp_src_date_from",		"From");								// ログイン日時From見出し
	$lc_smarty->assign("txt_src_date_from",		$lr_params[$l_date_from]);				// ログイン日時From入力
	$lc_smarty->assign("dsp_src_date_to",		"To");									// ログイン日時To見出し
	$lc_smarty->assign("txt_src_date_to",		$lr_params[$l_date_to]);				// ログイン日時To入力
	$lc_smarty->assign("dsp_src_purpose",		"送信目的");							// 送信目的見出し

	// 認証結果のチェックボックス設定
	if ($lr_params[$l_certification_result] === "1"){
		// OK
		$lc_smarty->assign("okng_ok", "checked");
		$lc_smarty->assign("okng_ng", "");
		$lc_smarty->assign("okng_all", "");
	}
	elseif ($lr_params[$l_certification_result] === "2"){
		// NG
		$lc_smarty->assign("okng_ok", "");
		$lc_smarty->assign("okng_ng", "checked");
		$lc_smarty->assign("okng_all", "");
	}
	else {
		// ALL
		$lc_smarty->assign("okng_ok", "");
		$lc_smarty->assign("okng_ng", "");
		$lc_smarty->assign("okng_all", "checked");
	}
	
	if($l_debug_mode==1){print("Step-その他");print "<br>";}

	// ------------------------------
	// 明細領域
	// ------------------------------
	// レコード再取得
	$lc_login_log->queryDBRecord();
	
	// 表示用のレコードを再編集
	$lr_db_data		= $lc_login_log->getViewRecord();			// データレコード
	$lr_db_head_j	= $lc_login_log->getColumnComment();			// 項目名のコメントを取得
	//var_dump($lr_db_data)."<br>";
	//var_dump($lr_db_head_j)."<br>";
	
	$lr_show_rec = "";
	foreach($lr_db_data as $l_rec_num => $lr_data_rec){
		$lr_show_rec[$l_rec_num]['ログイン日時']						= date("Y-m-d H:i:s",strtotime($lr_data_rec['LAST_UPDATE_DATET']));
		$lr_show_rec[$l_rec_num]['ユーザコード']						= $lr_data_rec['USED_USER_CODE'];
		$lr_show_rec[$l_rec_num]['会社コード']							= $lr_data_rec['USED_COMPANY_CODE'];
		$lr_show_rec[$l_rec_num]['認証結果']							= $lr_data_rec['CERTIFICATION_RESULT'];
		$lr_show_rec[$l_rec_num]['移動元画面']							= $lr_data_rec['SPG_REFERER'];
		$lr_show_rec[$l_rec_num]['ユーザIPアドレス']					= $lr_data_rec['SPG_REMORT_ADDR'];
		$lr_show_rec[$l_rec_num]['LOGIN_LOG_ID']						= $lr_data_rec['LOGIN_LOG_ID'];	// ログインログID(隠し項目)
	}
	//print var_dump($lr_show_rec)."<br>";
	if($l_debug_mode==1){print("Step-表示用のレコードを再編集");print "<br>";}
	
	// 明細用HTML作成クラスインスタンス作成
	require_once('../maintenance/h_logTableSetup.php');
	$lc_lts = new logTableSetup($lr_show_rec);
	if($l_debug_mode==1){print("Step-明細用HTML作成クラスインスタンス作成");print "<br>";}
	
	// 隠し項目の指定
	$lc_lts->setHiddenItem(array('LOGIN_LOG_ID'));
	if($l_debug_mode==1){print("Step-隠し項目の指定");print "<br>";}
	
	// ボタンの設定
	$lc_lts->setDtlButton(array('詳細'));
	if($l_debug_mode==1){print("Step-ボタンの設定");print "<br>";}
	
	// 項目幅を個別指定
	$lc_lts->setItemWidth(array
							(
								'ログイン日時'			=> '150px',
								'ユーザコード'			=> '150px',
								'会社コード'			=> '150px',
								'認証結果'				=> '100px',
								'移動元画面'			=> '300px',
								'ユーザIPアドレス'		=> '150px',
							)
						);
	if($l_debug_mode==1){print("Step-項目幅を個別指定");print "<br>";}
	
	// 表示ページの設定
	$lc_lts->setStartPageNum($l_show_pagenum);
	if($l_debug_mode==1){print("Step-表示ページの設定");print "<br>";}
		
	// 各領域のアサイン
	$lc_smarty->assign("html_div_po",	$lc_lts->makePageOpeItemHtml());
	$lc_smarty->assign("html_div_head",	$lc_lts->makeHeaderHtml());
	$lc_smarty->assign("html_div_dtl",	$lc_lts->makeDetailHtml());
		
	if($l_debug_mode==1){print("Step-明細領域の表示");print "<br>";}
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$l_max_page = $lc_lts->getPageCount();
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// 使用ページ
								  "name"	=> "nm_use_page"
								, "value"	=> $l_use_page
								),
							array(									// 表示ページ番号
								  "name"	=> "nm_show_page"
								, "value"	=> $l_show_pagenum
								),
							array(									// 最大ページ番号
								  "name"	=> "nm_max_page"
								, "value"	=> $l_max_page
								),
							array(									// ユーザコード
								  "name"	=> "nm_used_user_code"
								, "value"	=> $lr_params[$l_used_user_code]
								),
							array(									// 会社コード
								  "name"	=> "nm_used_comp_code"
								, "value"	=> $lr_params[$l_used_comp_code]
								),
							array(									// 認証結果
								  "name"	=> "nm_certification_result"
								, "value"	=> $lr_params[$l_certification_result]
								),
							array(									// ログイン日時From
								  "name"	=> "nm_date_from"
								, "value"	=> $lr_params[$l_date_from]
								),
							array(									// ログイン日時To
								  "name"	=> "nm_date_to"
								, "value"	=> $lr_params[$l_date_to]
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('LoginLog.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>
