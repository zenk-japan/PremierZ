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
 ファイル名：users_mnt.php
 処理概要  ：ユーザー管理画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_comp_name_cond          会社名(検索用)(任意)
             nm_group_name_cond         グループ名(検索用)(任意)
             nm_user_name_cond          ユーザー名(検索用)(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_selected_user_id        ユーザーID(任意)
             nm_valid_checkstat         有効データチェック状態(任意)
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
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_comp_name_cond	= "";									// 会社名(検索用)
	$l_group_name_cond	= "";									// グループ名(検索用)
	$l_user_name_cond	= "";									// ユーザー名(検索用)
	$l_show_page		= "";									// 表示ページ番号
	$l_max_page			= "";									// 最大ページ番号
	$l_selected_user_id	= "";									// POSTされたユーザーID
	$l_show_dtl_user_id	= "";									// 明細を表示するユーザーID
	$lr_dtl_rec			= "";									// 明細表示用のレコード
	$l_valid_checkstat	= "";									// 有効データチェック状態
	$l_ins_button		= "OFF";								// 新規登録ボタン表示
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
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		if($l_debug_mode==3){throw new Exception('l_post_tokenがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		if($l_debug_mode==3){throw new Exception('l_sess_tokenがNULL');}
		throw new Exception($l_error_type_st);
	}
	if($l_post_token != $l_sess_token){
		if($l_debug_mode==3){throw new Exception('token不整合');}
		throw new Exception($l_error_type_st);
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		if($l_debug_mode==3){throw new Exception('l_data_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// AUTHORITY_CODEの取得
	$l_auth_code = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	if($l_auth_code == ""){
		throw new Exception($l_error_type_st);
	}
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');

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
  ----------------------------------------------------------------------------*/
	// 会社名(検索用)
	if(!is_null($_POST['nm_comp_name_cond'])){
		$l_comp_name_cond = $_POST['nm_comp_name_cond'];
	}
	
	// グループ名(検索用)
	if(!is_null($_POST['nm_group_name_cond'])){
		$l_group_name_cond = $_POST['nm_group_name_cond'];
	}
	
	// ユーザー名(検索用)
	if(!is_null($_POST['nm_user_name_cond'])){
		$l_user_name_cond = $_POST['nm_user_name_cond'];
	}
	
	// 表示ページ番号
	if(!is_null($_POST['nm_show_page'])){
		$l_show_page = $_POST['nm_show_page'];
	}else{
		$l_show_page = 1;
	}
	
	// 最大ページ番号
	if(!is_null($_POST['nm_max_page'])){
		$l_max_page = $_POST['nm_max_page'];
	}else{
		$l_max_page = 1;
	}
	
	// ユーザーID
	if(!is_null($_POST['nm_selected_user_id'])){
		$l_selected_user_id = $_POST['nm_selected_user_id'];
	}
	
	// 有効データチェック状態
	if(!is_null($_POST['nm_valid_checkstat'])){
		$l_valid_checkstat = $_POST['nm_valid_checkstat'];
	}else{
		$l_valid_checkstat = "Y";
	}
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// ユーザーMDL
	require_once('../mdl/m_user_master.php');
	
	// ------------------------------
	// リスト表示用のデータ取得
	// ------------------------------
	// 検索条件設定
	$lr_user_cond = array('DATA_ID = '.$l_data_id);
	// 会社
	if($l_comp_name_cond != ""){
		array_push($lr_user_cond, "COMPANY_NAME like '%".$l_comp_name_cond."%'");
	}
	// グループ
	if($l_group_name_cond != ""){
		array_push($lr_user_cond, "GROUP_NAME like '%".$l_group_name_cond."%'");
	}
	
	// ユーザー
	if($l_user_name_cond != ""){
		array_push($lr_user_cond, "NAME like '%".$l_user_name_cond."%'");
	}
	
	// 有効フラグ
	if($l_valid_checkstat == "Y"){
		array_push($lr_user_cond, "VALIDITY_FLAG = 'Y'");
	}
	
	// レコード取得
	$l_mum = new m_user_master('Y', $lr_user_cond, array('COMPANY_NAME', 'NAME', 'USER_CODE'));
	$lr_users = $l_mum->getViewRecord();
	//print_r($lr_users);
	
	// ページ分割したデータを取得
	require_once('../lib/PagedData.php');
	$lr_pd = new PagedData($lr_users, 'Y', 8);
	
	// 表示対象分のデータを抽出
	$lr_show_users = $lr_pd->pickPageRecord($l_show_page);
	//print_r($lr_show_users);
	
	// レコード数
	$l_user_cnt = $lr_pd->getRecCount();
	
	// 総ページ数
	$l_max_page = $lr_pd->getPageCount();
	
	// 前のページのレコード数
	$l_prevpage_cnt = $lr_pd->getPrevRecCount($l_show_page);
	
	// 次のページのレコード数
	$l_nextpage_cnt = $lr_pd->getNextRecCount($l_show_page);
	
	// ------------------------------
	// 明細表示用のデータ取得
	// ------------------------------
	// 明細に表示するデータのユーザーIDを選択
	// POSTされたユーザーIDがあれば、POSTされたユーザーID、
	// なければセッションに保持されたユーザーIDを表示対象とする
	if($l_selected_user_id != ""){
		$l_show_dtl_user_id	= $l_selected_user_id;
	}else{
		$l_show_dtl_user_id	= $l_sess_user_id;
	}
	// 検索条件設定
	$lr_user_cond_dtl = array('USER_ID = '.$l_show_dtl_user_id);
	
	// レコード取得
	$l_mum_dtl = new m_user_master('Y', $lr_user_cond_dtl);
	$lr_user_detail = $l_mum_dtl->getViewRecord();
	//print_r($lr_user_detail);
	
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}
/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";
	
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
	$ar_css_files	= array(DIR_CSS."v_masters.css",
							DIR_CSS."v_top_block.css", 
							DIR_CSS."v_main_menu_block.css", 
							DIR_CSS."v_sub_menu_block.css", 
							DIR_CSS."v_search_menu_block.css", 
							DIR_CSS."v_users_detail_block.css", 
							DIR_CSS."v_users_list_block.css", 
							DIR_CSS."v_valuelist_div.css", 
							DIR_CSS."gb_styles.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_value_list.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_users_sub.js", 
							DIR_JS."jfnc_users_main_menu.js", 
							DIR_JS."jfnc_users_search_menu.js", 
							DIR_JS."jfnc_users_list.js", 
							DIR_JS."jfnc_users_detail.js", 
							DIR_JS."greybox.js", 
							DIR_JS."greybox/AJS.js", 
							DIR_JS."greybox/AJS_fx.js", 
							DIR_JS."greybox/gb_scripts.js");

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
	$lc_smarty->assign("top_include_tpl"	,"top_block.tpl");					// トップ
	$lc_smarty->assign("main_include_tpl"	,"main_menu_block.tpl");			// メインメニュー
	$lc_smarty->assign("sub_include_tpl"	,"sub_menu_users_block.tpl");		// サブメニュー
	$lc_smarty->assign("search_include_tpl"	,"users_search_menu_block.tpl");	// 検索メニュー
	$lc_smarty->assign("list_include_tpl"	,"users_list_block.tpl");			// リスト
	$lc_smarty->assign("detail_include_tpl"	,"users_detail_block.tpl");			// 明細
	
	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"ユーザー管理");				// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_auth"		,$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名
	// ------------------------------
	// トップメニュー
	// ------------------------------
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	
	$lc_smarty->assign("auth_code"		, $l_auth_code);				// ユーザー権限
	// ------------------------------
	// タブ
	// ------------------------------
	$lc_smarty->assign("now_page",		"USER");						// 現在のページ
	
	// ------------------------------
	// 検索条件
	// ------------------------------
	// タイトル
	$lc_smarty->assign("cond_title"		, "ユーザー検索");
	$lc_smarty->assign("cond_comp_name"	,$l_comp_name_cond);			// 会社名(検索用)
	$lc_smarty->assign("cond_group_name",$l_group_name_cond);			// グループ名(検索用)
	$lc_smarty->assign("cond_user_name"	,$l_user_name_cond);			// ユーザー名(検索用)
	
	// ------------------------------
	// リストメニュー
	// ------------------------------
	// タイトル
	$lc_smarty->assign("list_title"		, "ユーザー一覧");
	
	// データレコード
	$lc_smarty->assign("ar_list_menu"	, $lr_show_users);
	
	// ボタン操作部
	if(count($lr_show_users) > 0){
		$lc_smarty->assign("pageitem_visible"	,"ON");
		$lc_smarty->assign("rec_count"			,$l_user_cnt);
		$lc_smarty->assign("show_page"			,$l_show_page);
		$lc_smarty->assign("page_count"			,$l_max_page);
		// 前のページボタン
		if($l_prevpage_cnt > 0){
			$lc_smarty->assign("prevbtn_visible"	,"ON");
			$lc_smarty->assign("prev_btn_value"		,"前の".$l_prevpage_cnt."件");
		}
		// 次のページボタン
		if($l_nextpage_cnt > 0){
			$lc_smarty->assign("nextbtn_visible"	,"ON");
			$lc_smarty->assign("next_btn_value"		,"次の".$l_nextpage_cnt."件");
		}
	}
	
	// 有効データのみ表示チェックの設定
	$lc_smarty->assign("valid_checkstat"	, $l_valid_checkstat);
	
	// ------------------------------
	// 明細項目
	// ------------------------------
	// タイトル
	$lc_smarty->assign("detail_title"		, "ユーザー詳細");
	
	// データレコード
	$lc_smarty->assign("detail_table_item"	, $lr_user_detail[1]);
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$lr_hidden_items	= array(
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
								)
							);
	$lc_smarty->assign("txt_copyright",	$copyright_text);			// コピーライト
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
	
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('master_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>