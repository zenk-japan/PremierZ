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
 ファイル名：useCompanyInsert.php
 処理概要  ：利用会社新規作成画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_data_id                 DATA_ID(任意)
             nm_use_page                使用ページ(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_num_to_show             表示レコード数(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
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
	
	//print "step3<br>";
// ==================================
// 例外定義
// ==================================
	function my_exception_usecompins(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_usecompins');
	
	//print "step4<br>";
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
		$l_data_id = $_SESSION['DATA_ID'];
	}
	
	//print "step5<br>";
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
	
	// ------------------------------
	// 明細領域
	// ------------------------------
	
// ==================================
// ページ表示
// ==================================
	$l_html =$lc_smarty->fetch('UseCompanyInsert.tpl');
	print $l_html;
	//print "step9<br>";
?>
