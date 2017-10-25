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
 ファイル名：work_status.php
 処理概要   作業状況画面
 POST受領値：
            nm_token_code              				トークン(必須)
            nm_work_date							検索用作業日(任意)
            nm_end_user_name						検索用エンドユーザー(任意)
            nm_work_name							検索用作業名(任意)
            nm_selected_work_content_id				リスト内で選択された作業ID(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
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
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_page_name			= "WORKSTATUS";
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_name			= "";									// セッションで保持しているユーザー名
	$l_data_id				= "";									// 画面にセットするDATA_ID
	$l_work_date			= "";									// 作業日
	$l_end_user_name		= "";									// エンドユーザー
	$l_work_name			= "";									// 作業名
	$lr_workcontents_cond	= "";									// 作業用条件
	$lr_workstaff_cond		= "";									// 人員用条件
	$l_selected_work_content_id = "";
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_workstaffmnt(Exception $e){
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
	set_exception_handler('my_exception_workstaffmnt');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}

/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();

	// POSTされたトークンを取得
	$l_post_token = $_POST['nm_token_code'];
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
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');

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
	// 作業日
	$l_work_date = $_POST['nm_work_date'];
	if ($l_work_date == ""){
		$l_work_date = date("Y-m-d");
	}
	
	// エンドユーザー
	$l_end_user_name = $_POST['nm_end_user_name'];
	
	// 作業名
	$l_work_name = $_POST['nm_work_name'];
	
	// 作業ID
	$l_selected_work_content_id = $_POST['nm_selected_work_content_id'];
	
	if($l_debug_mode==1){print("Step-POST引数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	/*------------------------------
		作業
	------------------------------*/
	// 作業MDL
	require_once('../mdl/m_workcontents.php');
	if($l_debug_mode==1){print("Step-DBデータ取得_作業MDL");print "<br>";}

	// DATA_ID
	$lr_workcontents_cond = array("DATA_ID = ".$l_data_id);
	// 作業日
	array_push($lr_workcontents_cond, "WORK_DATE = '".$l_work_date."'");
	// エンドユーザー
	if ($l_end_user_name != ""){
		array_push($lr_workcontents_cond, "ENDUSER_COMPANY_NAME like '%".$l_end_user_name."%'");
	}
	// 作業名
	if ($l_work_name != ""){
		array_push($lr_workcontents_cond, "WORK_NAME like '%".$l_work_name."%'");
	}
	
	// レコード取得
	$lc_workcontents = new m_workcontents('Y', $lr_workcontents_cond);
	$lr_workcontents = $lc_workcontents->getViewRecord();
//{print "<pre>";var_dump($lr_workcontents);print "</pre>";}
	if($l_debug_mode==1){print("Step-作業データ取得");print "<br>";}
	
	// 選択された作業がある場合はそのデータを別途格納する
	if ($l_selected_work_content_id != ""){
		$lr_selected_work_content = "";
		foreach ($lr_workcontents as $l_rec_num => $l_wc_rec){
			if ($l_wc_rec['WORK_CONTENT_ID'] == $l_selected_work_content_id){
				$lr_selected_work_content = $l_wc_rec;
			}
		}
	}
	/*------------------------------
		人員
	------------------------------*/
	// 選択された作業がある場合は紐付く作業人員を表示
	if ($l_selected_work_content_id != ""){
		// 人員MDL
		require_once('../mdl/m_workstaff.php');
		if($l_debug_mode==1){print("Step-DBデータ取得_人員MDL");print "<br>";}

		// 作業ID
		$lr_workstaff_cond = array("WORK_CONTENT_ID = ".$l_selected_work_content_id);
		
		// 整列条件
		$lr_workstaff_order = array('WORK_BASE_NAME', 'WORK_USER_KANA');
		
		// レコード取得
		$lc_workstaff_all = new m_workstaff('Y', $lr_workstaff_cond, $lr_workstaff_order);
		$lr_workstaff_all = $lc_workstaff_all->getViewRecord();
	}
	if($l_debug_mode==1){print("Step-人員データ取得");print "<br>";}
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
	$ar_css_files	= array(DIR_CSS."v_top_block.css",
							DIR_CSS."v_work_status_common.css", 
							DIR_CSS."v_work_status_main.css", 
							DIR_CSS."v_work_status_menu_block.css", 
							DIR_CSS."v_work_status_search_block.css", 
							DIR_CSS."v_work_status_info_block.css", 
							DIR_CSS."v_work_status_detail_block.css", 
							DIR_CSS."jquery-ui-custom.css"
							);
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jquery-ui-custom.min.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_top.js", 
							DIR_JS."jfnc_work_status_common.js", 
							DIR_JS."jfnc_work_status_menu.js", 
							DIR_JS."jfnc_work_status_search.js", 
							DIR_JS."jfnc_work_status_info.js", 
							DIR_JS."jfnc_work_status_detail.js"
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
	$lc_smarty->assign("top_include_tpl"		,"top_block.tpl");						// トップ
	$lc_smarty->assign("main_include_tpl"		,"work_status_menu_block.tpl");			// メインメニュー
	$lc_smarty->assign("search_include_tpl"		,"work_status_search_block.tpl");		// 検索
	$lc_smarty->assign("info_include_tpl"		,"work_status_info_block.tpl");			// 概要
	$lc_smarty->assign("detail_include_tpl"		,"work_status_detail_block.tpl");			// 明細

	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("headtitle"		,"作業状況");					// 画面タイトル
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	$lc_smarty->assign("user_auth"		,$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name"		,$l_user_name);					// ユーザー名

	// ------------------------------
	// 共通設定
	// ------------------------------
	$lc_smarty->assign("now_page",		$l_page_name);					// 現在のページ

	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	// ------------------------------
	// 検索部
	// ------------------------------
	$lc_smarty->assign("default_work_date",		$l_work_date);
	$lc_smarty->assign("default_end_user",		$l_end_user_name);
	$lc_smarty->assign("default_work_name",		$l_work_name);
	// ------------------------------
	// リスト表示部
	// ------------------------------
	$lc_smarty->assign("ar_work_contents",		$lr_workcontents);
	
	// ------------------------------
	// 概要部
	// ------------------------------
	// リストから作業が選択されていれば、概要を表示
	if ($l_selected_work_content_id != ""){
		$lc_smarty->assign("data_selected",		'Y');
		$lc_smarty->assign("work_date",			$lr_selected_work_content["WORK_DATE"]);
		$lc_smarty->assign("work_name",			$lr_selected_work_content["WORK_NAME"]);
		
		$lc_smarty->assign("work_status_name",					$lr_selected_work_content["WORK_STATUS_NAME"]);
		$lc_smarty->assign("enduser_company_name",				$lr_selected_work_content["ENDUSER_COMPANY_NAME"]);
		$lc_smarty->assign("work_arrangement_user_name",		$lr_selected_work_content["WORK_ARRANGEMENT_USER_NAME"]);
		$lc_smarty->assign("work_arrangement_id",				$lr_selected_work_content["WORK_ARRANGEMENT_ID"]);
		$lc_smarty->assign("default_entering_schedule_timet",	$lr_selected_work_content["DEFAULT_ENTERING_SCHEDULE_TIMET"]);
		$lc_smarty->assign("default_leave_schedule_timet",		$lr_selected_work_content["DEFAULT_LEAVE_SCHEDULE_TIMET"]);
		$lc_smarty->assign("aggregate_point",					$lr_selected_work_content["AGGREGATE_POINT"]);
	}

	// ------------------------------
	// 明細部
	// ------------------------------
	if ($l_selected_work_content_id != ""){
		// データレコード
		$lc_smarty->assign("ar_work_staff"	,$lr_workstaff_all);					// 明細レコード
	}
	// ------------------------------
	// 隠し項目
	// ------------------------------
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// 表示ページ番号
								  "name"	=> "nm_show_page"
								, "value"	=> $l_show_page
								),
							array(									// 作業日
								  "name"	=> "nm_work_date"
								, "value"	=> htmlspecialchars($l_work_date)
								),
							array(									// エンドユーザー
								  "name"	=> "nm_end_user_name"
								, "value"	=> htmlspecialchars($l_end_user_name)
								),
							array(									// 作業名
								  "name"	=> "nm_work_name"
								, "value"	=> htmlspecialchars($l_work_name)
								),
							array(									// リスト内で選択された作業ID
								  "name"	=> "nm_selected_work_content_id"
								, "value"	=> $l_selected_work_content_id
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);

	/*-----------------------------------
	ページ表示
  -----------------------------------*/
	//$lc_smarty->debugging = true;
	$lc_smarty->display('work_status_main.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}


?>