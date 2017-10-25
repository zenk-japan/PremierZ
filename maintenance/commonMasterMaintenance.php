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
 ファイル名：commonMasterMaintenance.php
 処理概要  ：共通マスタ編集画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_code_id                 コードID(任意)
             nm_code_set                コードセット(任意)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "step1<br>";
	//print "<br>";
	//print_r($_SESSION);
	//return;
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
	$l_data_id			= "";									// DATA_ID
	$l_primary_key		= "";									// 主キー値(コードID)
	$l_code_set			= "";									// コードセット
	$l_proc_mode		= "";									// 実行モード
	
	//print "step3<br>";
// ==================================
// 例外定義
// ==================================
	function my_exception_cmmnt(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_cmmnt');
	
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
		$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	}
	// CODE_IDを取得
	if(!is_null($_POST['nm_code_id'])){
		$l_primary_key = $_POST['nm_code_id'];
	}
	// CODE_SETを取得
	if(!is_null($_POST['nm_code_set'])){
		$l_code_set = $_POST['nm_code_set'];
	}
	//print "step5<br>";
// ==================================
// Smarty変数定義
// ==================================
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	//print "step6<br>";
// ==================================
// Smarty変数セット
// ==================================
	// CSSファイル
	$ar_css_files	= array("v_mnt_main.css", "v_mnt_main_search.css", "v_mnt_dm.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS . "jquery.js", "maintenance.js", "mntDm.js", "commonMasterMaintenance.js");

	//print "step7<br>";
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
	
	$lc_smarty->assign("headtitle",		"zproject管理");				// 画面タイトル
	$lc_smarty->assign("ar_js_files",	$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",	$ar_css_files);					// CSSファイル
	$lc_smarty->assign("user_name",		$l_user_name);					// ユーザー名
	
	// ------------------------------
	// 検索領域
	// ------------------------------
	require_once('../maintenance/h_mntCondSetup.php');
	$lc_cond_setup = new mntCondSetup();
	// リスト用データ取得
	// DATA_ID
	require_once('../mdl/m_common_master.php');
	$lc_common_master = new m_common_master();
	
	// ボタン設置
	$lr_ope_button = array(
						array(
								"id"	=>	"id_btn_save",
								"class"	=>	"c_btn_main_nomal",
								"value"	=>	"保存"
							)
						);
	if($l_primary_key != ''){
		// 更新の場合は新規保存ボタンを追加
		array_push($lr_ope_button,
							array(
									"id"	=>	"id_btn_copy",
									"class"	=>	"c_btn_main_nomal",
									"value"	=>	"新規として保存"
								)
					);
	}
	array_push($lr_ope_button,
						array(
								"id"	=>	"id_btn_reset",
								"class"	=>	"c_btn_main_nomal",
								"value"	=>	"元に戻す"
							)
				);
	array_push($lr_ope_button,
						array(
								"id"	=>	"id_btn_return",
								"class"	=>	"c_btn_main_nomal",
								"value"	=>	"一覧に戻る"
							)
				);
	$lc_smarty->assign("ar_ope_button",		$lr_ope_button);
	
	// ------------------------------
	// 明細領域
	// ------------------------------
	// 共通マスタ取得
	if($l_primary_key != ''){
		// 更新の場合はレコードを取得
		$lr_common_master = $lc_common_master->getCommonMasterAll($l_data_id, '', $l_primary_key);
		// 更新の場合はDATA_IDをDBの値で表示する
		$l_data_id_disp = htmlspecialchars($lr_common_master[1]["DATA_ID"]);
		
		// タイトルを更新に設定
		$lc_smarty->assign("maintitle",		"共通マスタ - 更新");			// メインタイトル
		
		// 隠し項目用のモードを更新に設定
		$l_proc_mode = "UPD";
	}else{
		// 新規の場合はDATA_IDをセッション内の値とする
		$l_data_id_disp = $_SESSION['DATA_ID'];
		
		// タイトルを新規に設定
		$lc_smarty->assign("maintitle",		"共通マスタ - 新規");				// メインタイトル
		
		// 隠し項目用のモードを新規に設定
		$l_proc_mode = "INS";
	}
	
	
	//print_r($lr_common_master);
	$lr_main_data = array(
						array(
							"caption"	=> "DATA_ID",
							"type"		=> "text",
							"value"		=> $l_data_id_disp,
							"orgvalue"	=> $l_data_id_disp,
							"remarks"	=> "数値のみ登録可能"
						),
						array(
							"caption"	=> "CODE_SET",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["CODE_SET"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["CODE_SET"]),
							"remarks"	=> ""
						),
						array(
							"caption"	=> "CODE_NAME",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["CODE_NAME"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["CODE_NAME"]),
							"remarks"	=> "同一DATA_ID,CODE_SETの組合せ内で一意の値を設定"
						),
						array(
							"caption"	=> "CODE_VALUE",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["CODE_VALUE"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["CODE_VALUE"]),
							"remarks"	=> ""
						),
						array(
							"caption"	=> "REMARKS",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["REMARKS"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["REMARKS"]),
							"remarks"	=> ""
						),
						array(
							"caption"	=> "VALIDATION_START_DATE",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["VALIDATION_START_DATE"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["VALIDATION_START_DATE"]),
							"remarks"	=> "YYYY-MM-DD形式で入力"
						),
						array(
							"caption"	=> "VALIDATION_END_DATE",
							"type"		=> "text",
							"value"		=> htmlspecialchars($lr_common_master[1]["VALIDATION_END_DATE"]),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["VALIDATION_END_DATE"]),
							"remarks"	=> "YYYY-MM-DD形式で入力"
						),
						array(
							"caption"	=> "VALIDITY_FLAG",
							"type"		=> "list",
							"listval"	=> array("有効"=>"Y","無効"=>"N"),
							"orgvalue"	=> htmlspecialchars($lr_common_master[1]["VALIDITY_FLAG"]),
							"remarks"	=> ""
						)
					);
	
	$lc_smarty->assign("ar_main_data",		$lr_main_data);
		

		
	// 隠し項目
	// データが取得されている場合はその値をセット
	if(count($lr_common_master) > 0){
		$l_code_set = $lr_common_master[1]["CODE_SET"];
	}
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_sess_token
								),
							array(									// DATA_ID
								  "name"	=> "nm_data_id"
								, "value"	=> $l_data_id
								),
							array(									// コードID
								  "name"	=> "nm_code_id"
								, "value"	=> $l_primary_key
								),
							array(									// コード名
								  "name"	=> "nm_code_set"
								, "value"	=> $l_code_set
								),
							array(									// 実行モード
								  "name"	=> "nm_proc_mode"
								, "value"	=> $l_proc_mode
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	//print "step8<br>";
// ==================================
// ページ表示
// ==================================
	$lc_smarty->display('InsUpdPageStandard.tpl');
	//print "step9<br>";
?>
