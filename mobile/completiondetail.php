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
 ファイル名：completiondetail.php
 処理概要  ：作業完了詳細画面
 GET受領値：
             token                      トークン(必須)
             gv_work_staff_id           作業人員ID(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_GET);
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
	$l_work_staff_id	= "";					// 作業人員ID
	$l_err_flag			= true;					// エラーフラグ
	
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
	function my_exception_compdtl(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_compdtl');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン
	$l_work_staff_id	= $_GET['gv_work_staff_id'];	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー
	
	//print "l_show_page->".$l_show_page.":"."l_max_page->".$l_max_page.":"."l_num_to_show->".$l_num_to_show."<br>";
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
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
	
	$lr_workstaff = $lc_mwkst->getWorkStaffRec($l_work_staff_id);

	if($l_debug_mode==1){
		print_r($lr_workstaff);
		print "<br>";
	}
	
	//print var_dump($lr_workstaff);
	
	// 出・入・退店時間は年月日を非表示にする。
	$lr_workstaff['ENTERING_SCHEDULE_TIMET']	= $lc_mwkst->convert_TIME($lr_workstaff['ENTERING_SCHEDULE_TIMET'], $lr_workstaff['WORK_DATE']);
	$array_time = preg_split("/\:/",$lr_workstaff['ENTERING_SCHEDULE_TIMET']);
	if($array_time[0] > 23){
		$array_time[0] = $array_time[0] -24;
		$array_time[2] = "（翌）";
		$lr_workstaff['ENTERING_SCHEDULE_TIMET'] = $array_time[0].":".$array_time[1].$array_time[2];
	}
	$lr_workstaff['LEAVE_SCHEDULE_TIMET']		= $lc_mwkst->convert_TIME($lr_workstaff['LEAVE_SCHEDULE_TIMET'], $lr_workstaff['WORK_DATE']);
	$array_time = preg_split("/\:/",$lr_workstaff['LEAVE_SCHEDULE_TIMET']);
	if($array_time[0] > 23){
		$array_time[0] = $array_time[0] -24;
		$array_time[2] = "（翌）";
		$lr_workstaff['LEAVE_SCHEDULE_TIMET'] = $array_time[0].":".$array_time[1].$array_time[2];
	}
	$lr_workstaff['ENTERING_STAFF_TIMET']		= $lc_mwkst->convert_TIME($lr_workstaff['ENTERING_STAFF_TIMET'], $lr_workstaff['WORK_DATE']);
	$array_time = preg_split("/\:/",$lr_workstaff['ENTERING_STAFF_TIMET']);
	if($array_time[0] > 23){
		$array_time[0] = $array_time[0] -24;
		$array_time[2] = "（翌）";
		$lr_workstaff['ENTERING_STAFF_TIMET'] = $array_time[0].":".$array_time[1].$array_time[2];
	}
	$lr_workstaff['LEAVE_STAFF_TIMET']			= $lc_mwkst->convert_TIME($lr_workstaff['LEAVE_STAFF_TIMET'], $lr_workstaff['WORK_DATE']);
	$array_time = preg_split("/\:/",$lr_workstaff['LEAVE_STAFF_TIMET']);
	if($array_time[0] > 23){
		$array_time[0] = $array_time[0] -24;
		$array_time[2] = "（翌）";
		$lr_workstaff['LEAVE_STAFF_TIMET'] = $array_time[0].":".$array_time[1].$array_time[2];
	}
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
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
	$lc_smarty->assign("doctype",	$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",	$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",		$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",	$l_terminal);
	$lc_smarty->assign("model",		$l_model);
	
	
	// タイトル
	$lc_smarty->assign("headtitle",			"作業完了詳細");
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// 作業日
	$lc_smarty->assign("work_date",			htmlspecialchars($lr_workstaff['WORK_DATE']));

	// 作業名
	$lc_smarty->assign("work_name",			htmlspecialchars($lr_workstaff['WORK_NAME']));

	// 作業時間
	$lc_smarty->assign("working_hour",		htmlspecialchars($lr_workstaff['ENTERING_SCHEDULE_TIMET'])." ～ ".htmlspecialchars($lr_workstaff['LEAVE_SCHEDULE_TIMET']));

	// 作業場所
	$lc_smarty->assign("work_place",		htmlspecialchars($lr_workstaff['WORK_BASE_NAME']));

	// 作業纏め者
	$lc_smarty->assign("responsible",		htmlspecialchars($lr_workstaff['WORK_ARRANGEMENT_NAME']));

	// 入退店登録時間
	$lc_smarty->assign("enter_leave_time",	htmlspecialchars($lr_workstaff['ENTERING_STAFF_TIMET'])." ～ ".htmlspecialchars($lr_workstaff['LEAVE_STAFF_TIMET']));

	// 作業費
	$lc_smarty->assign("working_costs",		"\\".htmlspecialchars($lr_workstaff['WORK_EXPENSE_AMOUNT_TOTAL']));

	// 残業代
	$lc_smarty->assign("overtime_costs",	"\\".htmlspecialchars($lr_workstaff['OVERTIME_WORK_AMOUNT']));

	// 交通費
	$lc_smarty->assign("travelexpenses",	"\\".htmlspecialchars($lr_workstaff['TRANSPORT_AMOUNT']));
	
	// 備考
	$lc_smarty->assign("remarks",			"※金額は月単位で合計した後に端数処理を行いますので、日単位では端数が表示される場合が有ります。");
	
	// 送信フォーム
	$lc_smarty->assign("mail_href",			"completionmailform.php?token=".$l_token."&gv_work_staff_id=".$l_work_staff_id);
	
	// 隠し項目
	$lc_smarty->assign("work_staff_id",		$l_work_staff_id);
	
	// ハイパーリンク
	$lr_bottom_menu = array(
						array(
								"link_url"	=>	"completionlist.php?token=".$l_token,
								"value"		=>	"前画面へ戻る",
								"key"		=>	$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "2")
							),
						array(
								"link_url"	=>	$_SERVER['PHP_SELF']."?token=".$l_token."&"."gv_work_staff_id=".$l_work_staff_id,
								"value"		=>	"ページ更新",
								"key"		=>	$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "5")
							),
						array(
								"link_url"	=>	"logout.php?token=".$l_token,
								"value"		=>	"ログアウト",
								"key"		=>	$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "9")
							)/*,
						array(
								"link_url"	=>	"../manual/index.php?token=".$l_token,
								"value"		=>	"操作マニュアル",
								"key"		=>	$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "#")
							)*/
						);
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateCompDetail.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>