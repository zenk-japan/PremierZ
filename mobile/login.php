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
 ファイル名：login.php
 処理概要  ：ログイン
 POST受領値：
             username                ユーザー名(必須)
             password                パスワード(必須)
             usecomp                 利用会社コード(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	require_once('DB.php');
	
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_REQUEST);
		print "<br>\n";
		print "Step-Debug-mode<br>\n";
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
	$l_err_flag			= true;					// エラーフラグ
	$l_show_rec_cnt		= 0;					// 表示項目カウント
	$l_bottom_menu_cnt	= 0;					// ハイパーリンクカウント
	$guid				= NULL;
	$l_auth_table		= "USERS_V";			// 認証に使用するテーブル名
	$l_auth_name		= "USER_CODE";			// 認証に使用する名前項目
	$l_auth_pass		= "ENCRYPTION_PASSWORD";// 認証に使用するパスワード項目
	$l_auth_ucomp		= "USE_COMPANY_CODE";	// 認証に使用する利用会社コード項目
	$l_get_key1			= "DATA_ID";			// 認証で取得するキー項目1
	$l_get_key2			= "USER_ID";			// 認証で取得するキー項目2
	$l_get_key3			= "NAME";				// 認証で取得するキー項目3

/*----------------------------------------------------------------------------
  セッションインスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_scont = new sessionControl();
	
/*----------------------------------------------------------------------------
  モバイル共通関数インスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/MobileCommonFunctions.php');
	$lc_mcf = new MobileCommonFunctions();
	
/*----------------------------------------------------------------------------
  SESSIONSインスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_sessions.php');
	$msess = new m_sessions();
	
/*----------------------------------------------------------------------------
  SESSIONSインスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../mdl/ModelCommon.php');
	$lc_mcomm = new ModelCommon($l_auth_table);
	
/*==================================
  キャリア判別
  ==================================*/
	require_once('../lib/CommonMobiles.php');
	$lc_cm = new CommonMobiles();
	$l_connec_terminal = $lc_cm->checkMobiles();
	
	$l_terminal			= $l_connec_terminal['Terminal'];
	$l_model			= $l_connec_terminal['Model'];
	$l_identification	= $lc_cm->getUniqueId($l_terminal);
	$l_uid				= $l_identification['Uid'];
	$l_serial			= $l_identification['Serial'];
	$l_card				= $l_identification['Card'];
	
	if($l_terminal == TERMINAL_DOCOMO){$guid = "?guid=ON";}
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
	
	if($l_debug_mode==1){
		print "Step-キャリア判別<br>\n";
		print "端末取得<br>\n";
		print "&nbsp;".$l_terminal."<br>&nbsp;/".$l_model."<br>&nbsp;/".$l_uid."<br>&nbsp;/".$l_serial."<br>&nbsp;/".$l_card."<br>\n";
		print "USER_AGENT<br>\n";
		print "&nbsp;".$_SERVER['HTTP_USER_AGENT']."<br>\n";
	}
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_complist(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_complist');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_users.php');
	$lc_muser = new m_users();
	
	$lr_users = $lc_muser->getUsersLogin($l_uid, 'Y');
	
	if($l_debug_mode==1){
		print count($lr_users)."<br>";
		print_r($lr_users);
		print "<br>";
	}
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	
/*----------------------------------------------------------------------------
  ログイン
  ----------------------------------------------------------------------------*/
	function LoginFunction($username, $status){
	
		session_start();
		
	}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	// USER_CODE
	if($_POST['username']){
		$usercode	=	$_POST['username'];
	} else {
		$usercode	=	htmlspecialchars($lr_users[1][USER_CODE]);
	}
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"caption"	=> "ユーザコード",
										"name"		=> "username",
										"type"		=> INPUT_TYPE_TEXT,
										"value"		=> $usercode,
										"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
									);
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"type"		=> "RETURN"
									);
	
	// PASSWORD
	if($_POST['username']){
		$password	=	$_POST['password'];
	}
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"caption"	=> "パスワード",
										"name"		=> "password",
										"type"		=> INPUT_TYPE_PASSWORD,
										"value"		=> $password,
										"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
									);
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"type"		=> "RETURN"
									);
	
	// 利用会社
	if($_POST['username']){
		$usercomp	=	$_POST['usercomp'];
	}
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"caption"	=> "利用会社",
										"name"		=> "usercomp",
										"type"		=> INPUT_TYPE_TEXT,
										"value"		=> $usercomp,
										"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
									);
	
	// IDENTIFICATION_FLAG
	$identification_flag	=	htmlspecialchars($lr_users[1][IDENTIFICATION_FLAG]);
	if($l_terminal == TERMINAL_DOCOMO || $l_terminal ==  TERMINAL_SOFTBANK|| $l_terminal ==TERMINAL_AU){
		
		if($_POST[IDENTIFICATION_FLAG] == "on" ||  $identification_flag == "Y"){
			$checked	=	"checked";
		}
		
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]	=	array(
											"name"		=> "IDENTIFICATION_FLAG",
											"type"		=> INPUT_TYPE_CHKBOX,
											"checked"	=> $checked,
											"value"		=> "次回よりIDを省略"
										);
	}
	
	$show_rec_cnt++;
	$lr_show_rec[$show_rec_cnt]	=	array(
										"type"		=> "RETURN"
									);
	
	if($_POST['bt_login']){
	// ログインのsubmitがされた場合は、POST内容でユーザーテーブルを検索してチェックする
		$lr_login_log	= "";									// ログインログ用配列
		// ログ用の配列に値を格納
		$lr_login_log["USED_USER_CODE"]			= $lc_mcomm->getMysqlEscapedValue($usercode);
		$lr_login_log["USED_PASSWORD"]			= md5($password);
		$lr_login_log["USED_COMPANY_CODE"]		= $lc_mcomm->getMysqlEscapedValue($usercomp);
		$lr_login_log["CERTIFICATION_RESULT"]	= "";
		$lr_login_log["SPG_REFERER"]			= $_SERVER['HTTP_REFERER'];
		$lr_login_log["SPG_REMORT_ADDR"]		= $_SERVER['REMOTE_ADDR'];
		$lr_login_log["SPG_SERVER"]				= print_r($_SERVER, true);
		$lr_login_log["SPG_REQUEST"]			= "";	// パスワードが格納されている為NULLで登録
		$lr_login_log["REMARK"]					= "Mobile";

		// 検索条件設定
		$lr_user_cond = array($l_auth_name.' = "'.$lc_mcomm->getMysqlEscapedValue($usercode).'"');
		array_push($lr_user_cond, $l_auth_pass.' = "'.md5($password).'"');
		array_push($lr_user_cond, $l_auth_ucomp.' = "'.$lc_mcomm->getMysqlEscapedValue($usercomp).'"');
		array_push($lr_user_cond, 'VALIDITY_FLAG = "Y"');
		
		// ユーザーMDL
		require_once('../mdl/m_user_master.php');
		
		// レコード取得
		$lc_mum = new m_user_master('Y', $lr_user_cond);
		$lr_users = $lc_mum->getViewRecord();

		if(count($lr_users) == 1){
		// 認証成功
			$lr_login_log["CERTIFICATION_RESULT"] = "OK";
			// ログインログへの書き込み
			require_once('../mdl/m_login_log.php');
			$lc_mllog = new m_login_log();
			$lr_mllog = $lc_mllog->setSaveRecord($lr_login_log);
			$lc_mllog->insertRecord();
			
			// SESSIDを生成
			$_SESSION['SESSID']			=	md5(uniqid(rand(), true));
			$l_sessid					=	$_SESSION['SESSID'];
			
			// SESSION_TOKENを生成
			//$l_token = $lc_scont -> sessionStart();
			$l_token = $lc_scont -> createToken();
			
			// DATA_ID
			//$l_data_id = $_SESSION['_authsession']['data']['DATA_ID'];
			$l_data_id = $lr_users[1]['DATA_ID'];
			
			// USER_ID
			//$l_user_id = $_SESSION['_authsession']['data']['USER_ID'];
			$l_user_id = $lr_users[1]['USER_ID'];
			
			// 端末区分
			$td = $lr_users[1]['TERMINAL_DIVISION'];
			
			$effective = $lr_users[1]['VALIDITY_FLAG'];
			
			// モバイル画面の表示権限が無い場合は権限なし画面を表示
			if($td == "PC" || $td == "IM" || $effective == "N"){
				$lc_mcf->showAuthorityAccessPage($lr_spdesc, $l_terminal, $l_model);
				return;
			}
			
			// SESSIONS更新
			$msess->updateLogin($l_data_id, $l_user_id, $l_sessid, $l_token);
			
			// USERS更新
			if($_POST[IDENTIFICATION_FLAG] == "on"){
				$lc_muser->upUsersEasyLogin($l_user_id, $l_uid);
			} else if(empty($_POST[IDENTIFICATION_FLAG]) && $identification_flag == 'Y'){
				$lc_muser->upUsersEasyLogin($l_user_id, null, 'N');
			}
			
			// USERSのRESERVE_1にセットされているトークン（パスワードリセット用）をクリア
			if ($lr_users[1]['RESERVE_1'] != ""){
				// 保存用レコードセット
				$lr_data = array();
				$lr_data['USER_ID']				= $lr_users[1]['USER_ID'];
				$lr_data['RESERVE_1']			= NULL;
				$lr_data['LAST_UPDATE_USER_ID']	= SYSTEM_USER;
				$lr_data['LAST_UPDATE_DATET']	= date("Y/m/d H:i:s");
				$lc_mum->setSaveRecord($lr_data);
				
				// 更新処理
				if(!$lc_mum->updateRecord($lr_users[1]['USER_ID'])){
					throw new Exception("ユーザーの更新ができませんでした。管理者に連絡して下さい。");
				}
			}
			
			// ページリダイレクト
			if ($_SERVER['HTTPS'] == "on") {
				$httpheader = 'https';
			} else {
				$httpheader = 'http';
			}
			
			$host		=	$_SERVER['HTTP_HOST'];
			$uri		=	rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra		=	'workcontents.php?token='.$l_token;
			$site_add	=	$httpheader."://".$host.$uri."/".$extra;
			
			if (!headers_sent()) {
				if($l_debug_mode==0){
					header("Location: $site_add");
				} else {
					print "Step-ページリダイレクト<br>\n";
					print $site_add."<br>\n";
				}
				exit;
			}
		} else {
		// 認証失敗
			$lr_login_log["CERTIFICATION_RESULT"] = "NG";
			// ログインログへの書き込み
			require_once('../mdl/m_login_log.php');
			$lc_mllog = new m_login_log();
			$lr_mllog = $lc_mllog->setSaveRecord($lr_login_log);
			$lc_mllog->insertRecord();
			
			// USER_CODE/PASSWORD未入力
			if($_POST[username] == NULL && $_POST[password] == NULL){
				$show_rec_cnt++;
				$lr_show_rec[$show_rec_cnt]	=	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> "ユーザコード/パスワードを入力してください。",
												);
			// USER_CODE未入力
			} else if($_POST[username] == NULL){
				$show_rec_cnt++;
				$lr_show_rec[$show_rec_cnt]	=	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> "ユーザコードを入力してください。",
												);
			// PASSWORD未入力
			} else if($_POST[password] == NULL){
				$show_rec_cnt++;
				$lr_show_rec[$show_rec_cnt]	=	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> "パスワードを入力してください。",
												);
			// USER_CODE/PASSWORD不一致
			} else {
				$show_rec_cnt++;
				$lr_show_rec[$show_rec_cnt]	=	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> "認証に失敗しました。",
												);
				$show_rec_cnt++;
				$lr_show_rec[$show_rec_cnt]	=	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> "ログイン情報を再入力してください。",
												);
			}
			
			$show_rec_cnt++;
			$lr_show_rec[$show_rec_cnt]	=	array(
												"type"		=> "RETURN",
											);
		}
		
		
	}
	
	// ログインボタン
	$l_btn_rec_cnt++;
	$lr_btn_rec[$l_btn_rec_cnt]	=		array(
											"name"		=> "bt_login",
											"type"		=> INPUT_TYPE_SUBMIT,
											"value"		=> "ログイン"
										);
	
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
	
	if($l_debug_mode==1){print("Step-smartyクラスインスタンス作成");print "<br>\n";}
	
/*==================================
  smartyアサイン
  ==================================*/
	// ヘッダー部
	$lc_smarty->assign("doctype",			$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	// タイトル
	$lc_smarty->assign("headtitle",			SCREEN_ZSMMC001);
	$lc_smarty->assign("headinfo",			"");
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF'].$guid);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// ログイン情報
	$lc_smarty->assign("ar_users",			$lr_show_rec);
	$lc_smarty->assign("ar_users_btn",		$lr_btn_rec);
	
	// ハイパーリンク
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> "inquiry.php",
												"value"		=> "パスワードを忘れた方",
												"key"		=> "9"
											);
	/*
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> DIR_MAN."index.php?token=".$l_token,
												"value"		=> "操作マニュアル",
												"key"		=> "#"
											);
	*/
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>\n";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateLogin.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>\n";}
?>