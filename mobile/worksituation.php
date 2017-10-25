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
 ファイル名：worksituation.php
 処理概要  ：作業一覧画面
 GET受領値：
             token                      トークン(必須)
             gv_show_page               表示ページ番号(任意)
             gv_max_page                最大ページ番号(任意)
             gv_num_to_show             表示レコード数(任意)
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
	$l_rec_count		= "";					// 総レコード数
	$l_sess_token		= "";					// セッショントークン
	$l_err_flag			= true;					// エラーフラグ
	
	// FROM:作業日のリストの作成
	$f_workyear_list[0]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")-1));
	$f_workyear_list[1]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	$f_workyear_list[2]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")+1));
	
	$f_workyear_list[0]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")-1));
	$f_workyear_list[1]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	$f_workyear_list[2]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")+1));
	
	// GETでF_WORK_YEARが取得できなかった場合は今日の"年"をselectedにする。
	if(is_null($_GET['F_WORK_YEAR']) || $_GET['F_WORK_YEAR'] == ""){
		$f_workyear_list[1]["SELECTED"]	= "selected";
	}else {
		for($i=0; $i<=2; $i++){
			if($_GET['F_WORK_YEAR'] == $f_workyear_list[$i]["VALUE"]){
				$f_workyear_list[$i]["SELECTED"]	= "selected";
			}
		}
	}
	// GETでF_WORK_MONTHが取得できなかった場合は今日の"月"をselectedにする。
	$n_month = date("m",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	for($j=0; $j<12; $j++){
		$f_workmonth_list[$j]["VALUE"]  = $j + 1;
		$f_workmonth_list[$j]["VIEW"]  = $j + 1;
		if(is_null($_GET['F_WORK_MONTH']) || $_GET['F_WORK_MONTH'] == ""){
			if($j + 1 == $n_month){
				$f_workmonth_list[$j]["SELECTED"] = "selected";
			}
		}else {
			if($j + 1 == $_GET['F_WORK_MONTH']){
				$f_workmonth_list[$j]["SELECTED"] = "selected";
			}
		}
	}
	
	// GETでF_WORK_DATEが取得できなかった場合は今日の"日"をselectedにする。
	$n_date = date("d",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	for($k=0; $k<31; $k++){
		$f_workday_list[$k]["VALUE"]  = $k + 1;
		$f_workday_list[$k]["VIEW"]   = $k + 1;
		if(is_null($_GET['F_WORK_DATE']) || $_GET['F_WORK_DATE'] == ""){
			if($k + 1 == $n_date){
				$f_workday_list[$k]["SELECTED"] = "selected";
			}
		}else {if($k + 1 == $_GET['F_WORK_DATE']){
				$f_workday_list[$k]["SELECTED"] = "selected";
			}
		}
	}
	
	// FROM:作業日リストの作成
	$t_workyear_list[0]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")-1));
	$t_workyear_list[1]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	$t_workyear_list[2]["VALUE"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")+1));
	
	$t_workyear_list[0]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")-1));
	$t_workyear_list[1]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
	$t_workyear_list[2]["VIEW"] = date("Y",mktime(0, 0, 0, date("m") , date("d") , date("Y")+1));
	
	// GETでT_WORK_YEARが取得できなかった場合は今日の"年"をselectedにする。
	$n_year = date("Y",mktime(0, 0, 0, date("m")+1 , date("d") , date("Y")));
	if(is_null($_GET['T_WORK_YEAR']) || $_GET['T_WORK_YEAR'] == ""){
		for($i=0; $i<=2; $i++){
			if($t_workyear_list[$i]["VALUE"] == $n_year){
				$t_workyear_list[$i]["SELECTED"] = "selected";
			}
		}
	}else {
		for($i=0; $i<=2; $i++){
			if($_GET['T_WORK_YEAR'] == $t_workyear_list[$i]["VALUE"]){
				$t_workyear_list[$i]["SELECTED"]	= "selected";
			}
		}
	}
	// GETでT_WORK_MONTHが取得できなかった場合は今日の"月"をselectedにする。
	$n_month = date("m",mktime(0, 0, 0, date("m")+1 , date("d") , date("Y")));
	for($j=0; $j<12; $j++){
		$t_workmonth_list[$j]["VALUE"]  = $j + 1;
		$t_workmonth_list[$j]["VIEW"]  = $j + 1;
		if(is_null($_GET['T_WORK_MONTH']) || $_GET['T_WORK_MONTH'] == ""){
			if($j + 1 == $n_month){
				$t_workmonth_list[$j]["SELECTED"] = "selected";
			}
		}else {
			if($j + 1 == $_GET['T_WORK_MONTH']){
				$t_workmonth_list[$j]["SELECTED"] = "selected";
			}
		}
	}
	// GETでT_WORK_DATEが取得できなかった場合は今日の"日"をselectedにする。
	$n_date = date("d",mktime(0, 0, 0, date("m")+1 , date("d") , date("Y")));
	for($k=0; $k<31; $k++){
		$t_workday_list[$k]["VALUE"]  = $k + 1;
		$t_workday_list[$k]["VIEW"]   = $k + 1;
		if(is_null($_GET['T_WORK_DATE']) || $_GET['T_WORK_DATE'] == ""){
			if($k + 1 == $n_date){
				$t_workday_list[$k]["SELECTED"] = "selected";
			}
		}else {if($k + 1 == $_GET['T_WORK_DATE']){
				$t_workday_list[$k]["SELECTED"] = "selected";
			}
		}
	}
	
	// GETでF_WORK_YEARが取得できなかった場合は、今日の日付～１ヶ月後をFROMとTOのリストにセットする。
	if($_GET['F_WORK_YEAR'] == "" || is_null($_GET['F_WORK_YEAR'])){
		$f_workdate = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d") , date("Y")));
		$t_workdate = date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d") , date("Y")));
	}else{
		// FROMとTOの日付の比較
		$FROM_TIMESTAMP = mktime(0,0,0,$_GET['F_WORK_MONTH'],$_GET['F_WORK_DATE'],$_GET['F_WORK_YEAR']);
		$TO_TIMESTAMP = mktime(0,0,0,$_GET['T_WORK_MONTH'],$_GET['T_WORK_DATE'],$_GET['T_WORK_YEAR']);
		
		if($FROM_TIMESTAMP > $TO_TIMESTAMP){
			$error_messa = "「TO」には「FROM」より後の日付を選択してください。";
		}else {
			$f_workdate = $_GET['F_WORK_YEAR']."-".$_GET['F_WORK_MONTH']."-".$_GET['F_WORK_DATE'];
			$t_workdate = $_GET['T_WORK_YEAR']."-".$_GET['T_WORK_MONTH']."-".$_GET['T_WORK_DATE'];
		}
	}

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
	function my_exception_complist(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_complist');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= mb_convert_encoding($_GET['token']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// トークン
	$l_phpsessid		= mb_convert_encoding($_GET['PHPSESSID']	,'UTF-8','UTF-8,SJIS,EUC-JP');			// セッションID
	//作業日
	//if(is_null($_GET["WORK_DATE"])){
	//	$l_work_date						=	date( "Y-m-d");
	//} else {
	//	$l_work_date						=	$_GET["WORK_DATE"];
	//}
	
	
	$l_work_name		= mb_convert_encoding($_GET['WORK_NAME']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// 作業名
	$l_base_name		= mb_convert_encoding($_GET['BASE_NAME']		,'UTF-8','UTF-8,SJIS,EUC-JP');			// 拠点名
	//print "l_show_page->".$l_show_page.":"."l_max_page->".$l_max_page.":"."l_num_to_show->".$l_num_to_show."<br>";
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
	
/*----------------------------------------------------------------------------
  キャリア別の入力モードの指定
  ----------------------------------------------------------------------------*/
	$l_input_style		= $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA");
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	//print "l_token->".$l_token."<br>";
	// セッションチェック
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	
	// データID設定
	$l_data_id = $lr_session['DATA_ID'];
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
		
	if($l_debug_mode==1){
		//print_r($lr_session);
		//print "<br>";
		print "l_token->".$l_token."<br>";
		print var_dump($lr_session);
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
	if($error_messa == "" || is_null($error_messa)){
		require_once('../mdl/m_workstaff.php');
		$lc_mwkst = new m_workstaff();
		
		$lr_show_rec = $lc_mwkst->get_WorkSituation($f_workdate, $l_work_name,$l_base_name,$t_workdate,$l_data_id);
		
    	if($l_debug_mode==2){print_r($lr_show_rec);}
			
		if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	}
	
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
	$lc_smarty->assign("headtitle",	"作業状況確認");
	
	// ロゴ
	$lc_smarty->assign("img_logo",	MOBILE_LOGO);
	
	// 入力モードの指定
	$lc_smarty->assign("input_style",	$l_input_style);
	
	// 作業状況一覧
	$lc_smarty->assign("ar_workstaff",	$lr_show_rec);
	$lc_smarty->assign("token",	$l_token);
	$lc_smarty->assign("f_workyear_list",	$f_workyear_list);
	$lc_smarty->assign("f_workmonth_list",	$f_workmonth_list);
	$lc_smarty->assign("f_workday_list",	$f_workday_list);
	$lc_smarty->assign("t_workyear_list",	$t_workyear_list);
	$lc_smarty->assign("t_workmonth_list",	$t_workmonth_list);
	$lc_smarty->assign("t_workday_list",	$t_workday_list);
	$lc_smarty->assign("workdate_list",	$workdate_list);
	$lc_smarty->assign("worksituation_url",	$_SERVER['PHP_SELF']);
	$lc_smarty->assign("work_name",	$l_work_name);
	$lc_smarty->assign("base_name",	$l_base_name);
	$lc_smarty->assign("error_messa",	$error_messa);
	
	// ハイパーリンク
	$lr_bottom_menu = array(
						array(
								"link_url"	=> "workcontents.php?token=".$l_token,
								"value"		=> "TOPへ戻る",
								"key"		=> "0"
							),
						array(
								"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token."&"."gv_show_page=1&"."gv_max_page=".$l_max_page."&"."gv_num_to_show=".$l_num_to_show,
								"value"		=> "ページ更新",
								"key"		=> "5"
							),
						array(
								"link_url"	=> "logout.php?token=".$l_token,
								"value"		=> "ログアウト",
								"key"		=> "9"
							)/*,
						array(
								"link_url"	=> "../manual/index.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC999,
								"key"		=> "#"
							)*/
						);
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateWorkSituation.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>