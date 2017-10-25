<?php

/******************************************************************************
 ファイル名：wrcompletionlist.php
 処理概要  ：作業完了一覧画面
 GET受領値：
             token                      トークン(必須)
             gv_show_page               表示ページ番号(任意)
             gv_max_page                最大ページ番号(任意)
             gv_num_to_show             表示レコード数(任意)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_POST);
		print "step1<br>";
	}
/*----------------------------------------------------------------------------
  変数定義
  ----------------------------------------------------------------------------*/
	$l_terminal			= "";					// 端末キャリア
	$l_model			= "";					// 端末モデル
	$lr_spdesc			= "";					// 端末固有のヘッダー記載情報
	$l_char_code		= "character_code";		// 文字コード
	$l_doctype			= "declaration";		// ドキュメントタイプ宣言
	$l_xmlns			= "xmlns";				// XML名前空間
	$l_token			= "";					// GETトークン
	$l_phpsessid		= "";					// セッションID
	$l_show_page		= "";					// 表示ページ番号
	$l_max_page			= "";					// 最大ページ番号
	$l_num_to_show		= "";					// 表示レコード数
	$l_rec_count		= "";					// 総レコード数
	$l_sess_token		= "";					// セッショントークン
	$l_err_flag			= true;					// エラーフラグ
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_workname_shortcut= 25;					// 作業名の最大表示文字数
	$l_workbasename_shortcut= 25;				// 拠点名の最大表示文字数の限度

	
/*----------------------------------------------------------------------------
  モバイル共通関数インスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/MobileCommonFunctions.php');
	$lc_mcf = new MobileCommonFunctions();
	
/*==================================
  キャリア判別
  ==================================*/
	require_once('../lib/CommonMobiles.php');
	$lc_cm = new CommonMobiles();
	$l_connec_terminal = $lc_cm->checkMobiles();
	
	$l_terminal		= $l_connec_terminal['Terminal'];
	$l_model		= $l_connec_terminal['Model'];
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
		
	if($l_debug_mode==1){print("Step-キャリア判別");print "<br>";}
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_mainmenu(Exception $e){
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
	set_exception_handler('my_exception_mainmenu');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_POST['nm_token_code'];				// トークン
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	/*
	//print "l_token->".$l_token."<br>";
	// セッションチェック
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	//print "l_token->".$l_token."<br>";
	//print var_dump($lr_session);
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
		
	if($l_debug_mode==1){
		//print_r($lr_session);
		//print "<br>";
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	*/
	
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		$l_err_flag = false;
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		$l_err_flag = false;
	}
	if($l_post_token != $l_sess_token){
		$l_err_flag = false;
	}
	
	// 権限の取得
	$l_authority = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_user_id == ""){
		$l_err_flag = false;
	}
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	if($l_user_name == ""){
		$l_err_flag = false;
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		$l_err_flag = false;
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_workstaff.php');
	$lc_mwkst = new m_workstaff();
	
	$lr_workstaff = $lc_mwkst->getCompletionList($l_user_id, 'WC');
//$lr_workstaff = array();
	if($l_debug_mode==1){
		print_r($lr_workstaff);
		print "<br>";
	}
	
	// ページ単位レコードクラス作成
	require_once('../lib/PagedData.php');
	$lc_pd = new PagedData($lr_workstaff, 'Y');			// htmlspecialchars適用で取得
	
	// 総レコード数取得
	$l_rec_count = $lc_pd->getRecCount();
	
	// ページ数算出
	$l_max_page = $lc_pd->getPageCount();
	
	// 表示する分のレコードのみ抽出
	$lr_show_rec = $lc_pd->pickPageRecord($l_show_page);
	//print var_dump($lr_show_rec);
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	// 作業内容・拠点名が長すぎる場合については、省略表示用に編集する。
	$l_show_rec_cnt = 1;
	foreach($lr_workstaff as $key => $w_value){
		
		$l_workname = "";
		$l_workname_len = "";
		
		$l_workname = $w_value[WORK_NAME];
		$l_workname_len = mb_strlen($l_workname);
		if($l_workname_len >= $l_workname_shortcut){
			$lr_workstaff[$l_show_rec_cnt]['WORK_NAME'] = mb_substr($l_workname, 0, $l_workname_shortcut)."...";
		}
		
		$l_show_rec_cnt++;
	}
	
	$l_show_rec_cnt = 1;
	foreach($lr_workstaff as $key => $w_value){
		
		$l_workbasename = "";
		$l_workbasename_len = "";
		
		$l_workbasename = $w_value[WORK_BASE_NAME];
		$l_workbasename_len = mb_strlen($l_workbasename);
		if($l_workbasename_len >= $l_workbasename_shortcut){
			$lr_workstaff[$l_show_rec_cnt]['WORK_BASE_NAME'] = mb_substr($l_workbasename, 0, $l_workbasename_shortcut)."...";
		}
		
		$l_show_rec_cnt++;
	}
	
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
	$ar_css_files	= array(DIR_CSS."v_top_block.css", 
							DIR_CSS."v_workreport_common.css", 
							DIR_CSS."v_workreport_complist.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js", 
							DIR_JS."jfnc_common.js", 
							DIR_JS."jfnc_workreport_complist.js", 
							DIR_JS."jfnc_top.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
/*==================================
  smartyクラスインスタンス作成
  ==================================*/
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir	= DIR_TEMPLATES;
	$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
	$lc_smarty->config_dir		= DIR_CONFIGS;
	$lc_smarty->cache_dir		= DIR_CACHE;
	
	if($l_debug_mode==1){print("Step-smartyクラスインスタンス作成");print "<br>";}
	
/*==================================
  smartyアサイン
  ==================================*/
	// ヘッダー部
	$lc_smarty->assign("char_code",	$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",		$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",	$l_terminal);
	$lc_smarty->assign("model",		$l_model);
	
	$lc_smarty->assign("ar_js_files"	,$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files"	,$ar_css_files);				// CSSファイル
	
	// タイトル
	$lc_smarty->assign("headtitle",	"作業完了一覧");
	$lc_smarty->assign("user_auth",		$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name",		$l_user_name);
	
	// ロゴ
	$lc_smarty->assign("img_logo",	MOBILE_LOGO);
	
	// 作業完了一覧
	$lc_smarty->assign("ar_workstaff",	$lr_workstaff);
	$lc_smarty->assign("detail_page",	"wrcompletiondetail.php");
	$lc_smarty->assign("token",	$l_token);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	
	// 隠し項目
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_token
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('workreport_complist.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>