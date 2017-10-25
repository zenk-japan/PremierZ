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
 ファイル名：commonMaster.php
 処理概要  ：共通マスタ画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_data_id                 DATA_ID(任意)
             nm_code_set                コードセット(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_num_to_show             表示レコード数(任意)
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
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

	if($l_debug_mode==1){print "step2<br>";}
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_code_set			= "";									// 画面にセットするコードセット
	$l_show_pagenum		= "1";									// 表示を開始するページ番号
	$l_number_to_show	= "10";									// 表示するレコードの数
	$l_max_page			= "";									// 最大ページ番号
	
	if($l_debug_mode==1){print "step3<br>";}
// ==================================
// 例外定義
// ==================================
	function my_exception_mntmain(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_mntmain');
	
	if($l_debug_mode==1){print "step4<br>";}
// ==================================
// セッション確認
// ==================================
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
// ==================================
// POST変数取得
// POST変数はセッションの値より優先される
// ==================================
	// DATA_IDを取得
	if(!is_null($_POST['nm_data_id'])){
		$l_data_id = $_POST['nm_data_id'];
	}else{
		$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	}
	
	// コードセットを取得
	if(!is_null($_POST['nm_code_set'])){
		$l_code_set = $_POST['nm_code_set'];
	}else{
		$l_code_set = "";
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
	}
	
	// 表示を開始するレコード番号を取得
	if(!is_null($_POST['nm_num_to_show'])){
		$l_number_to_show = $_POST['nm_num_to_show'];
	}
	if($l_debug_mode==1){print "step5<br>";}
// ==================================
// Smarty変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print "step6<br>";}
// ==================================
// Smarty変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array("v_mnt_main.css", "v_mnt_main_search.css", "v_mnt_main_table.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS . "jquery.js", "maintenance.js", "commonMaster.js", "mntMain.js");

	if($l_debug_mode==1){print "step7<br>";}
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
	
	$lc_smarty->assign("headtitle",		SYSTEM_NAME."Z管理");			// 画面タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	$lc_smarty->assign("maintitle",		"共通マスタ");					// メインタイトル
	
	if($l_debug_mode==1){print "step8-1<br>";}
	// ------------------------------
	// 検索領域
	// ------------------------------
	require_once('../maintenance/h_mntCondSetup.php');
	$lc_cond_setup = new mntCondSetup();
	// リスト用データ取得
	// DATA_ID
	require_once('../mdl/m_common_master.php');
	$lc_common_master = new m_common_master();
	$lr_data_id = $lc_common_master->getDATAID();
	//print_r($lr_data_id);
	if($l_debug_mode==1){print "step8-2<br>";}
	
	// 検索項目の設定
	$lr_search = array(
						array(
							"type"			=> "list",
							"width"			=> "200px",
							"title"			=> "DATA_ID",
							"id"			=> "id_list_dataid_search",
							"value"			=> $lr_data_id,
							"default"		=> $l_data_id
						),
						array(
							"type"			=> "text",
							"width"			=> "200px",
							"title"			=> "CODE_SET",
							"id"			=> "id_txt_code_set_search",
							"value"			=> htmlspecialchars($l_code_set)
						)
					);
	$l_html_search = $lc_cond_setup->setMenu($lr_search);
	$lc_smarty->assign("html_div_main_top",		$l_html_search);
	//print_r($lr_search);
	//print "<br>";
	//print $l_html_search;
	if($l_debug_mode==1){print "step8-3<br>";}
	
	// ------------------------------
	// 明細領域
	// ------------------------------
	//共通マスタ取得
	$lr_common_master = $lc_common_master->getCommonMasterAll($l_data_id, $l_code_set);
	//print_r($lr_common_master);
	if($l_debug_mode==1){print "step8-4<br>";}
		
	require_once('../maintenance/h_mntTableSetup.php');
	// mntTableSetupのコンストラクタにレコードを渡すと、明細部を作成してくれます
	$lc_mts = new mntTableSetup($lr_common_master);
	// インデックス項目をコードセットに設定
	$lc_mts->setIndexItemNum(3);				// CODE_SET
	// ヘッダー項目に検索用テキストボックスを設置するか設定
	$lc_mts->setFlagShowSearchBox(false);
	// 表示するレコードの数
	$lc_mts->setNumberToShow($l_number_to_show);
	// 表示を開始するページ番号
	$lc_mts->setStartPageNum($l_show_pagenum);
	// 最大ページ番号の取得
	$l_max_page = $lc_mts->getPageCount();
	// 項目数の取得
	$l_item_count = $lc_mts->getItemCount();
	
	// ページ操作部
	$lc_smarty->assign("html_div_po_right",			$lc_mts->makePageOpeItemHtml());
	
	// ヘッダ左
	$lc_smarty->assign("html_div_head_left",		$lc_mts->makeHDIndexHtml());
	
	// 明細左
	$lc_smarty->assign("html_div_dtl_left",			$lc_mts->makeDTLIndexHtml());
	
	if($l_item_count > 1){
		// ヘッダ右
		$lc_smarty->assign("html_div_head_right",	$lc_mts->makeHDOtherHtml());
		
		// 明細右
		$lc_smarty->assign("html_div_dtl_right",	$lc_mts->makeDTLOtherHtml());
	}
		
	// 隠し項目
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// DATA_ID
								  "name"	=> "nm_data_id"
								, "value"	=> $l_data_id
								),
							array(									// コードセット
								  "name"	=> "nm_code_set"
								, "value"	=> $l_code_set
								),
							array(									// 表示ページ番号
								  "name"	=> "nm_show_page"
								, "value"	=> $l_show_pagenum
								),
							array(									// 最大ページ番号
								  "name"	=> "nm_max_page"
								, "value"	=> $l_max_page
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print "step8-5<br>";}
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('MaintenanceMain.tpl');
	if($l_debug_mode==1){print "step9<br>";}
?>
