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
 ファイル名：mailLog.php
 処理概要  ：メールログ画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_data_id                 DATA_ID(任意)
             nm_use_page                使用ページ(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_send_from               送信元(任意)
             nm_send_to                 送信先(任意)
             nm_date_from               送信日時From(任意)
             nm_date_to                 送信日時To(任意)
             nm_send_purpose            送信目的(任意)
             nm_search_phrase           タイトル/本文検索用文字列(任意)
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
	$l_code_set			= "";									// 画面にセットするコードセット
	$l_show_pagenum		= "1";									// 表示を開始するページ番号
	$l_number_to_show	= "10";									// 表示するレコードの数
	$l_max_page			= "";									// 最大ページ番号
	$lr_params			= "";									// 各種パラメータ
	$l_data_id			= "data_id";
	$l_send_from		= "send_from";
	$l_send_to			= "send_to";
	$l_send_purpose		= "send_purpose";
	$l_date_from		= "date_from";
	$l_date_to			= "date_to";
	$l_search_phrase	= "search_phrase";
	
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_maillog(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_maillog');
	
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
	// DATA_IDを取得
	if(!is_null($_POST['nm_data_id'])){
		$lr_params[$l_data_id] = $_POST['nm_data_id'];
	}//else{
	//	$lr_params[$l_data_id] = $_SESSION['DATA_ID'];
	//}
	
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
	
	// 送信元を取得
	if(!is_null($_POST['nm_send_from'])){
		$lr_params[$l_send_from] = $_POST['nm_send_from'];
	}
	
	// 送信先を取得
	if(!is_null($_POST['nm_send_to'])){
		$lr_params[$l_send_to] = $_POST['nm_send_to'];
	}
	
	// 送信日時Fromを取得
	if(!is_null($_POST['nm_date_from'])){
		$lr_params[$l_date_from] = $_POST['nm_date_from'];
	}
	
	// 送信日時Toを取得
	if(!is_null($_POST['nm_date_to'])){
		$lr_params[$l_date_to] = $_POST['nm_date_to'];
	}
	
	// 送信目的を取得
	if(!is_null($_POST['nm_send_purpose'])){
		$lr_params[$l_send_purpose] = $_POST['nm_send_purpose'];
	}
	
	// タイトル/本文検索
	if(!is_null($_POST['nm_search_phrase'])){
		$lr_params[$l_search_phrase] = $_POST['nm_search_phrase'];
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
	$ar_js_files	= array(DIR_JS . "jquery.js", "callLogSubView.js", "maintenance.js", "mailLog.js", "mntLogView.js");

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
	$lc_smarty->assign("maintitle",		"メールログ");					// メインタイトル
	
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
	$lr_where		= $lc_mail_log->makeWherePhrase($lr_params);

	$lr_orderby		= array("LAST_UPDATE_DATET desc","DATA_ID","SEND_PURPOSE","FROM_ADDRESS","TO_ADDRESS","MAIL_TITLE");
	
	// where と order byのセット
	$lc_mail_log->setWhereArray($lr_where);
	$lc_mail_log->setOrderyBy($lr_orderby);
	
	
	if($l_debug_mode==1){print("Step-データ取得用の条件配列作成");print "<br>";}
	
	// ------------------------------
	// リスト出力
	// ------------------------------
	// DATA_ID
	$lr_data_id_db = $lc_mail_log->getColumnValueAll('DATA_ID');
	$lr_data_id = array(
					array(
							"caption"		=> "-",
							"value"			=> "",
							"selected"		=> ""
						)
					);

	// 取得したデータを追加
	if(count($lr_data_id_db) > 0){
		foreach($lr_data_id_db as $l_value){
			if($l_value == $lr_params[$l_data_id]){
				// post値かsession値と同じならselected
				$l_selected_phrase = "selected";
			}else{
				$l_selected_phrase = "";
			}
			
			// push用配列定義
			$lr_push_value=	array(
									"caption"		=> htmlspecialchars($l_value),
									"value"			=> htmlspecialchars($l_value),
									"selected"		=> $l_selected_phrase
								);
			array_push($lr_data_id, $lr_push_value);
		}
	}
	$lc_smarty->assign("ar_data_id",		$lr_data_id);
	
	if($l_debug_mode==1){print("Step-DATA_IDリストend");print "<br>";}
	
	// 送信目的
	$lr_send_purpose_db = $lc_mail_log->getColumnValueAll('SEND_PURPOSE');
	$lr_purpose = array(
					array(
							"caption"		=> "-",
							"value"			=> "",
							"selected"		=> ""
						)
					);
	// 取得したデータを追加
	if(count($lr_send_purpose_db) > 0){
		foreach($lr_send_purpose_db as $l_value){
			if($l_value == $lr_params[$l_send_purpose]){
				// post値かsession値と同じならselected
				$l_selected_phrase = "selected";
			}else{
				$l_selected_phrase = "";
			}
			
			// push用配列定義
			$lr_push_value= array(
									"caption"		=> htmlspecialchars($l_value),
									"value"			=> htmlspecialchars($l_value),
									"selected"		=> $l_selected_phrase
								);
			array_push($lr_purpose, $lr_push_value);
		}
	}
	$lc_smarty->assign("ar_purpose",		$lr_purpose);
	
	if($l_debug_mode==1){print("Step-送信目的リスト");print "<br>";}
	
	// ------------------------------
	// その他出力
	// ------------------------------
	$lc_smarty->assign("dsp_src_data_id",		"DATA_ID");				// DATA_ID見出し
	$lc_smarty->assign("dsp_src_fromto_cap",	"送受信アドレス");		// 送受信見出し
	$lc_smarty->assign("dsp_src_from",			"送信元");				// 送信元見出し
	$lc_smarty->assign("txt_src_from",			$lr_params[$l_send_from]);			// 送信元入力
	$lc_smarty->assign("dsp_src_to",			"送信先");				// 送信先見出し
	$lc_smarty->assign("txt_src_to",			$lr_params[$l_send_to]);			// 送信先入力
	$lc_smarty->assign("dsp_src_date_cap",		"送信日時");			// 送信日時見出し
	$lc_smarty->assign("dsp_src_date_from",		"From");				// 送信日時From見出し
	$lc_smarty->assign("txt_src_date_from",		$lr_params[$l_date_from]);			// 送信日時From入力
	$lc_smarty->assign("dsp_src_date_to",		"To");					// 送信日時To見出し
	$lc_smarty->assign("txt_src_date_to",		$lr_params[$l_date_to]);			// 送信日時To入力
	$lc_smarty->assign("dsp_src_purpose",		"送信目的");			// 送信目的見出し
	$lc_smarty->assign("dsp_src_title",			"タイトル/本文検索");	// タイトル/本文検索見出し
	$lc_smarty->assign("txt_src_title",			$lr_params[$l_search_phrase]);		// タイトル/本文検索入力

	if($l_debug_mode==1){print("Step-その他");print "<br>";}

	// ------------------------------
	// 明細領域
	// ------------------------------
	// レコード再取得
	$lc_mail_log->queryDBRecord();
	
	// 表示用のレコードを再編集
	$lr_db_data		= $lc_mail_log->getViewRecord();			// データレコード
	$lr_db_head_j	= $lc_mail_log->getColumnComment();			// 項目名のコメントを取得
	//print "<pre>";var_dump($lr_db_data);print "</pre>";
	//print "<pre>";var_dump($lr_db_head_j);print "</pre>";
	
	$lr_show_rec = "";
	foreach($lr_db_data as $l_rec_num => $lr_data_rec){
		$lr_show_rec[$l_rec_num]['送信日']								= date("Y-m-d H:i:s",strtotime($lr_data_rec['LAST_UPDATE_DATET']));	// 送信日
		$lr_show_rec[$l_rec_num][$lr_db_head_j['FROM_ADDRESS']]			= $lr_data_rec['FROM_ADDRESS'];									// From
		$lr_show_rec[$l_rec_num][$lr_db_head_j['TO_ADDRESS']]			= $lr_data_rec['TO_ADDRESS'];									// To
		// メールタイトルは15文字でカットする
		if(mb_strlen($lr_data_rec['MAIL_TITLE']) >= 15){
			$l_adj_title = mb_substr($lr_data_rec['MAIL_TITLE'], 0, 15)."...";
		}else{
			$l_adj_title = $lr_data_rec['MAIL_TITLE'];
		}
		$l_mail_titile_phrase = 
		$lr_show_rec[$l_rec_num][$lr_db_head_j['MAIL_TITLE']]			= $l_adj_title;													// タイトル
		$lr_show_rec[$l_rec_num][$lr_db_head_j['SEND_PURPOSE']]			= $lr_data_rec['SEND_PURPOSE'];									// 送信目的
		$lr_show_rec[$l_rec_num]['送信者']								= $lr_data_rec['SEND_USER_NAME'];								// 送信者
		$lr_show_rec[$l_rec_num]['MAIL_LOG_ID']							= $lr_data_rec['MAIL_LOG_ID'];									// メールログID(隠し項目)
	}
	//print var_dump($lr_show_rec)."<br>";
	if($l_debug_mode==1){print("Step-表示用のレコードを再編集");print "<br>";}
	
	// 明細用HTML作成クラスインスタンス作成
	require_once('../maintenance/h_logTableSetup.php');
	$lc_lts = new logTableSetup($lr_show_rec);
	if($l_debug_mode==1){print("Step-明細用HTML作成クラスインスタンス作成");print "<br>";}
	
	// 隠し項目の指定
	$lc_lts->setHiddenItem(array('MAIL_LOG_ID'));
	if($l_debug_mode==1){print("Step-隠し項目の指定");print "<br>";}
	
	// ボタンの設定
	$lc_lts->setDtlButton(array('詳細'));
	if($l_debug_mode==1){print("Step-ボタンの設定");print "<br>";}
	
	// 項目幅を個別指定
	$lc_lts->setItemWidth(array
							(
								'詳細'							=> '50px',
								'送信日'						=> '150px',
								$lr_db_head_j['FROM_ADDRESS']	=> '200px',
								$lr_db_head_j['TO_ADDRESS']		=> '200px',
								$lr_db_head_j['MAIL_TITLE']		=> '180px',
								$lr_db_head_j['SEND_PURPOSE']	=> '150px',
								'送信者'						=> '150px'
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
							array(									// DATA_ID
								  "name"	=> "nm_data_id"
								, "value"	=> $lr_params[$l_data_id]
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
							array(									// 送信元
								  "name"	=> "nm_send_from"
								, "value"	=> $lr_params[$l_send_from]
								),
							array(									// 送信先
								  "name"	=> "nm_send_to"
								, "value"	=> $lr_params[$l_send_to]
								),
							array(									// 送信日時From
								  "name"	=> "nm_date_from"
								, "value"	=> $lr_params[$l_date_from]
								),
							array(									// 送信日時To
								  "name"	=> "nm_date_to"
								, "value"	=> $lr_params[$l_date_to]
								),
							array(									// 送信目的
								  "name"	=> "nm_send_purpose"
								, "value"	=> $lr_params[$l_send_purpose]
								),
							array(									// タイトル/本文検索
								  "name"	=> "nm_search_phrase"
								, "value"	=> $lr_params[$l_search_phrase]
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('MailLog.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>
