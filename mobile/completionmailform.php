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
 ファイル名：completionmailform.php
 処理概要  ：完了作業メール送信画面
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
	$l_sess_token		= "";					// セッショントークン
	$l_work_staff_id	= "";					// 作業人員ID
	$l_err_flag			= true;					// エラーフラグ
	$l_data_id			= "";					// DATA_ID
	
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
	function my_exception_compmail(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_compmail');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン
	$l_work_staff_id	= $_GET['gv_work_staff_id'];	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー(前画面に戻る際に使用する)
	
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
		
	// DATA_ID設定
	$l_data_id = $lr_session['DATA_ID'];
	
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
	// ユーザーデータ読込
	require_once('../mdl/m_users.php');
	$lc_musr = new m_users();

		//print var_dump($lc_musr);
		//print "<br>";
	if($l_debug_mode==1){print("Step-データ読込_ユーザーデータ");print "<br>";}
	
	// 携帯のメールアドレスを取得
	$lr_users = $lc_musr->getRecordByID($l_user_id);
	$l_send_from_addr = $lr_users[1]['MOBILE_PHONE_MAIL'];
	//print var_dump($lr_users);
	if($l_debug_mode==1){print("Step-データ読込_携帯のメールアドレス");print "<br>";}
	
	// 作業人員読込
	require_once('../mdl/m_workstaff.php');
	$lc_mwkst = new m_workstaff();
	
	$lr_workstaff = $lc_mwkst->getWorkStaffRec($l_work_staff_id);

		//print_r($lr_workstaff);
		//print "<br>";
	if($l_debug_mode==1){print("Step-データ読込_作業人員読込");print "<br>";}
	
	// 日付と作業名からタイトルを作成
	$l_mail_title = "【作業補足/修正】".htmlspecialchars($lr_workstaff['MAIL_WORK_DATE'])."の「".htmlspecialchars($lr_workstaff['WORK_NAME'])."」について"."[From:".$lr_users[1]['NAME']."]";
	
	// メール設定用MDL読込
	require_once('../lib/MailSettings.php');
	$lc_mails = new MailSettings($l_data_id);
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*==================================
  メール送信画面クラスインスタンス作成
  ==================================*/
	require_once('../lib/MobileMail.php');
	$lc_mmail = new MobileMail();
	if(is_null($lc_mmail)){
		throw new Exception('MobileMailクラスが作成できませんでした');
	}
	
	if($l_debug_mode==1){print("Step-MobileMailクラスインスタンス作成");print "<br>";}
	
/*==================================
  アサイン用変数設定
  ==================================*/
	$lr_assign_data = array();
	
	$lr_assign_data['doctype']					= $lr_spdesc[$l_doctype];		// ドキュメントタイプ
	$lr_assign_data['char_code']				= $lr_spdesc[$l_char_code];		// 文字コード
	$lr_assign_data['xmlns']					= $lr_spdesc[$l_xmlns];			// XML名前空間
	$lr_assign_data['terminal']					= $l_terminal;					// 端末種別
	$lr_assign_data['model']					= $l_model;						// 端末モデル
	$lr_assign_data['token']					= $l_token;						// トークン
	$lr_assign_data['page_title']				= '作業詳細 補足/修正';			// ページタイトル
	$lr_assign_data['logout_page_file_name']	= 'logout.php';					// ログアウトページのファイル名
	$lr_assign_data['man_page_file_name']		= '../manual/index.php';		// マニュアルページのファイル名
	$lr_assign_data['prev_page_file_name']		= 'completiondetail.php';		// 前のページのファイル名
	$lr_assign_data['prev_page_id_name']		= 'gv_work_staff_id';			// 前のページの一意キーGET項目名
	$lr_assign_data['prev_page_id_value']		= $l_work_staff_id;				// 前のページの一意キー値
	$lr_assign_data['next_page_file_name']		= 'completionmailsend.php';		// 完了を表示するphpファイル
	// 一括設定
	$lc_mmail->setAssignData($lr_assign_data);
	
	// 追加パラメータ
	$lr_hidden_param = array(
							array(
									"name"	=> "token",
									"value"	=> $l_token
								),
							array(
									"name"	=> "nm_work_staff_id",
									"value"	=> $l_work_staff_id
								)
							);
	$lc_mmail->setHiddenParam($lr_hidden_param);
	
	if($l_debug_mode==1){print("Step-smartyアサイン用変数設定");print "<br>";}
	
/*==================================
  メール関係設定
  ==================================*/
	$lc_mmail->sm_from_display_flag			= false;		// From表示フラグ(Gmail使用の場合変更できないので表示しない)
	$lc_mmail->setFrom($l_send_from_addr);					// From
	$lc_mmail->sm_send_from_ro_flag			= true;			// 読み取り専用
	$lc_mmail->setTo($lc_mails->getMailAddr1());			// To
	$lc_mmail->sm_send_to_ro_flag			= true;			// 読み取り専用
	$lc_mmail->setMailTitle($l_mail_title);					// タイトル
	$lc_mmail->sm_mail_title_ro_flag		= true;			// 読み取り専用
	$lc_mmail->setMailText("");
	
	if($l_debug_mode==1){print("Step-smartyメール関係設定");print "<br>";}
/*==================================
  アサイン
  ==================================*/
	$lc_mmail->procAssign();
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_mmail->showPage();
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>