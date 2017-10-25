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
 ファイル名：completionlist.php
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
	$l_token			= $_GET['token'];				// トークン
	//$l_phpsessid		= $_GET['PHPSESSID'];			// セッションID(使ってない)
	$l_show_page		= $_GET['gv_show_page'];		// 表示ページ番号
	if($l_show_page==''){$l_show_page = 1;}
	$l_max_page		= $_GET['gv_max_page'];				// 最大ページ番号
	if($l_max_page==''){$l_max_page = 1;}
	$l_num_to_show	= $_GET['gv_num_to_show'];			// 表示レコード数
	if($l_num_to_show==''){$l_num_to_show = 5;}

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
	$lc_smarty->assign("headtitle",	"作業完了一覧");
	
	// ロゴ
	$lc_smarty->assign("img_logo",	MOBILE_LOGO);
	
	// 作業完了一覧
	$lc_smarty->assign("ar_workstaff",	$lr_show_rec);
	$lc_smarty->assign("detail_page",	"completiondetail.php");
	$lc_smarty->assign("token",	$l_token);
	
	// 前のページ、次のページ
	// ページ番号が2以上の場合は前のページを表示
	if($l_show_page >= 2){
		$l_prev_html  = "<div align=\"left\"";
		if($l_show_page != $l_max_page){
			// 最終頁以外は次のページ表示を右端に付けるためstyleを追加
			$l_prev_html .= " style=\"float:left\"";
		}
		$l_prev_html .= ">";
		$l_prev_html .= "<a href=\"".$_SERVER['PHP_SELF']."?";
		$l_prev_html .= "token=".$l_token;
		$l_prev_html .= "&gv_show_page=".($l_show_page - 1);
		$l_prev_html .= "&gv_max_page=".$l_max_page;
		$l_prev_html .= "&gv_num_to_show=".$l_num_to_show;
		$l_prev_html .= "\"";
		$l_prev_html .= " ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 4)."";
		$l_prev_html .= ">";
		$l_prev_html .= "前の".$l_num_to_show."件";
		$l_prev_html .= "</a>";
		$l_prev_html .= "</div>";
		$lc_smarty->assign("move_prev",	$l_prev_html);
	}
	// ページ番号が最大ページ番号未満の場合は次のページを表示
	if($l_show_page < $l_max_page){
		if($l_show_page == $l_max_page - 1){
			// 次が最終頁の場合は、件数を調整する
			$l_next_num_to_show = $l_rec_count - ($l_show_page * $l_num_to_show);
		}else{
			$l_next_num_to_show = $l_num_to_show;
		}
		$l_next_html  = "<div align=\"right\">";
		$l_next_html .= "<a href=\"".$_SERVER['PHP_SELF']."?";
		$l_next_html .= "token=".$l_token;
		$l_next_html .= "&gv_show_page=".($l_show_page + 1);
		$l_next_html .= "&gv_max_page=".$l_max_page;
		$l_next_html .= "&gv_num_to_show=".$l_num_to_show;
		$l_next_html .= "\"";
		$l_next_html .= " ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 6)."";
		$l_next_html .= ">";
		$l_next_html .= "次の".$l_next_num_to_show."件";
		$l_next_html .= "</a>";
		$l_next_html .= "</div>";
		$lc_smarty->assign("move_next",	$l_next_html);
	}
	
	
	// ハイパーリンク
	$lr_bottom_menu = array(
						array(
								"link_url"	=> "workcontents.php?token=".$l_token,
								"value"		=> "TOPへ戻る",
								"key"		=> $lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "0")
							),
						array(
								"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token."&"."gv_show_page=1&"."gv_max_page=".$l_max_page."&"."gv_num_to_show=".$l_num_to_show,
								"value"		=> "ページ更新",
								"key"		=> $lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "5")
							),
						array(
								"link_url"	=> "logout.php?token=".$l_token,
								"value"		=> "ログアウト",
								"key"		=> $lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "9")
							)/*,
						array(
								"link_url"	=> "../manual/index.php?token=".$l_token,
								"value"		=> "操作マニュアル",
								"key"		=> $lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, "#")
							)*/
						);
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateCompList.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>