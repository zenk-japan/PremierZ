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
 ファイル名：c_create_valuelist.php
 処理概要  ：リスト画面作成
 POST受領値：nm_token_code              トークン(必須)
             page_name                  ページ名(必須)
             value_use_item_id          リスト呼び出し元の項目ID(必須)
             show_page                  表示ページ番号(任意)
             max_page                   最大ページ番号(任意)
             その他                     検索項目の値
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
	$l_html_rts				= "<BR>";								// HTMLの改行
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_list_title			= "";									// リストのタイトル
	$l_page_name			= "";									// ページ名
	$l_value_use_item_id	= "";									// リスト呼び出し元の項目ID
	$l_value_set_item_id	= "";									// 値を返す項目ID
	$l_id_set_item_id		= "";									// IDを返す項目ID
	$lr_cond				= array();								// 条件値に使用する値の配列
	$l_return_html			= "";									// 値リストのHTML
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
//	if(is_null($l_post_token)){
//		throw new Exception($l_error_type_st);
//	}
//	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
//	if(is_null($l_sess_token)){
//		throw new Exception($l_error_type_st);
//	}
//	if($l_post_token != $l_sess_token){
//		throw new Exception($l_error_type_st);
//	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		throw new Exception($l_error_type_st);
	}
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
	// ページ名
	if(!is_null($_POST['page_name'])){
		$l_page_name = $_POST['page_name'];
	}
	
	// リスト呼び出し元の項目ID
	if(!is_null($_POST['value_use_item_id'])){
		$l_value_use_item_id = $_POST['value_use_item_id'];
	}
	
	// 表示ページ番号
	if(!is_null($_POST['show_page'])){
		$l_show_page = $_POST['show_page'];
	}else{
		$l_show_page = 1;
	}
	
	// 最大ページ番号
	if(!is_null($_POST['max_page'])){
		$l_max_page = $_POST['max_page'];
	}else{
		$l_max_page = 1;
	}
	
	// 全てのPOST引数を配列に格納
	$lr_cond = $_POST;
	
	// DATA_IDを別途追加
	$lr_cond['_DATA_ID'] = $l_data_id;
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DBデータ取得
  ----------------------------------------------------------------------------*/
	// 値リスト設定
	require_once('../mdl/m_value_list_defines.php');
	$lc_value_list = new m_value_list_defines();
	
	
	// SELECT文取得
	$lr_select_phrase = $lc_value_list->getSelectPhrase($l_data_id, $l_page_name, $l_value_use_item_id);
	if(count($lr_select_phrase) == 0){
		return false;
	}
	
	// 値を返す項目IDを取得
	$l_value_set_item_id = $lc_value_list->getValueSetItemId();
	if(!$l_value_set_item_id){
		$l_value_set_item_id = $l_value_use_item_id;
	}
	
	// IDを返す項目IDを取得
	$l_id_set_item_id = $lc_value_list->getIdSetItemId();
	
	// SELECT文再構築
	$l_select_phrase = $lc_value_list->buildSelectPhrase($lr_select_phrase, $lr_cond);
	
	
	// データ取得
	$lc_value_list->receiveValueListRecord($l_select_phrase);
	$lr_value_list_comment	= $lc_value_list->getColumnComment();
	$lr_value_list_rec		= $lc_value_list->getViewRecord();
	if($l_debug_mode==1){print("Step-DBデータ取得");print "<br>";}
	//print_r($lr_value_list_rec);
	
	// ページ分割したデータを取得
	require_once('../lib/PagedData.php');
	$lr_pd = new PagedData($lr_value_list_rec, 'Y', 8);
	
	// 表示対象分のデータを抽出
	$lr_show_rec = $lr_pd->pickPageRecord($l_show_page);
	//print_r($lr_show_rec);
	
	// レコード数
	$l_rec_cnt = $lr_pd->getRecCount();
	
	// 総ページ数
	$l_max_page = $lr_pd->getPageCount();
	
	// 前のページのレコード数
	$l_prevpage_cnt = $lr_pd->getPrevRecCount($l_show_page);
	
	// 次のページのレコード数
	$l_nextpage_cnt = $lr_pd->getNextRecCount($l_show_page);

	if($l_debug_mode==1){print("Step-分割データ取得");print "<br>";}

/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
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
	// リストメニュー
	// ------------------------------
	// タイトル
	list($l_main_item_key, $l_main_item_name) = each($lr_value_list_comment);	// コメント配列の最初の値をタイトルとする
	$lc_smarty->assign("ext_list_title"		, $l_main_item_name."一覧");
	
	// リストタイトル
	$lc_smarty->assign("ext_ar_list_title"	, $lr_value_list_comment);
	
	// データレコード
	$lc_smarty->assign("ext_ar_list_value"	, $lr_show_rec);
	
	// ボタン操作部
	if(count($lr_show_rec) > 0){
		$lc_smarty->assign("ext_pageitem_visible"	,"ON");
		$lc_smarty->assign("ext_rec_count"			,$l_rec_cnt);
		$lc_smarty->assign("ext_show_page"			,$l_show_page);
		$lc_smarty->assign("ext_page_count"			,$l_max_page);
		// 前のページボタン
		if($l_prevpage_cnt > 0){
			$lc_smarty->assign("ext_prevbtn_visible"	,"ON");
			$lc_smarty->assign("ext_prev_btn_value"		,"前の".$l_prevpage_cnt."件");
		}
		// 次のページボタン
		if($l_nextpage_cnt > 0){
			$lc_smarty->assign("ext_nextbtn_visible"	,"ON");
			$lc_smarty->assign("ext_next_btn_value"		,"次の".$l_nextpage_cnt."件");
		}
	}
	
	// ------------------------------
	// 隠し項目
	// ------------------------------
	// 最大ページの取得
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "ext_list_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// 表示ページ番号
								  "name"	=> "ext_list_show_page"
								, "value"	=> $l_show_page
								),
							array(									// 最大ページ番号
								  "name"	=> "ext_list_max_page"
								, "value"	=> $l_max_page
								),
							array(									// 値を返す項目ID
								  "name"	=> "ext_list_value_set_item_id"
								, "value"	=> $l_value_set_item_id
								),
							array(									// IDを返す項目ID
								  "name"	=> "ext_list_id_set_item_id"
								, "value"	=> $l_id_set_item_id
								)
							);
	$lc_smarty->assign("ext_ar_hidden_items",	$lr_hidden_items);
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
	
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$l_return_html = $lc_smarty->fetch('ValueListDiv.tpl');
	print $l_return_html;
	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>