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
 ファイル名：contentslist.php
 処理概要  ：作業一覧画面(TOP)
 GET受領値：
             token                      トークン(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
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
	$guid				= NULL;
	
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
	
	$l_terminal			= $l_connec_terminal['Terminal'];
	$l_model			= $l_connec_terminal['Model'];
	$l_identification	= $lc_cm->getUniqueId($l_terminal);
	$l_uid				= $l_identification['Uid'];
	$l_serial			= $l_identification['Serial'];
	$l_card				= $l_identification['Card'];
	
	if($l_terminal == TERMINAL_DOCOMO){$guid = "&guid=ON";}
	
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
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン
	
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
	
	if($l_debug_mode==1){
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($lr_spdesc, $l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_users.php');
	$lc_users = new m_users();
	
	$lr_users = $lc_users->getUsersList($l_user_id);
	
	if($l_debug_mode==1){
		print count($lr_users)."<br>";
		print_r($lr_users);
		print "<br>";
	}
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	// ボタン押下判定
	
	if(empty($_POST[bt_send])){
		$name = str_replace(' ', '', str_replace('　', '', htmlspecialchars($lr_users[1][NAME])));
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		= 	array(
												"caption"	=> "【お名前】",
												"name"		=> "NAME",
												"type"		=> INPUT_TYPE_DISP,
												"value"		=> $name."&nbsp;様"
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		= 	array(
												"caption"	=> "【ユーザコード】",
												"name"		=> "USER_CODE",
												"type"		=> INPUT_TYPE_DISP,
												"value"		=> htmlspecialchars($lr_users[1][USER_CODE])
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【パスワード】",
												"name"		=> "PASSWORD",
												"type"		=> INPUT_TYPE_PASSWORD,
												"value"		=> htmlspecialchars($lr_users[1][PASSWORD]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【郵便番号】",
												"name"		=> "ZIP_CODE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][ZIP_CODE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【住所】",
												"name"		=> "ADDRESS",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][ADDRESS]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【最寄駅】",
												"name"		=> "CLOSEST_STATION",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][CLOSEST_STATION]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【自宅電話番号】",
												"name"		=> "HOME_PHONE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][HOME_PHONE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【自宅メールアドレス】",
												"name"		=> "HOME_MAIL",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][HOME_MAIL]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【携帯電話番号】",
												"name"		=> "MOBILE_PHONE",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][MOBILE_PHONE]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"caption"	=> "【携帯メールアドレス】",
												"name"		=> "MOBILE_PHONE_MAIL",
												"type"		=> INPUT_TYPE_TEXT,
												"value"		=> htmlspecialchars($lr_users[1][MOBILE_PHONE_MAIL]),
												"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "ALPHABET")
											);
		if($l_terminal == TERMINAL_DOCOMO || $l_terminal == TERMINAL_SOFTBANK || $l_terminal == TERMINAL_AU ){
			$show_rec_cnt++;
			$lr_show_rec[$show_rec_cnt]	=	array(
												"caption"	=> "【簡単ログイン】",
												"name"		=> "IDENTIFICATION_FLAG",
												"type"		=> INPUT_TYPE_RADIO,
												"value"		=> htmlspecialchars($lr_users[1][IDENTIFICATION_FLAG])
											);
		}
		
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"name"		=> "DATA_ID",
												"type"		=> INPUT_TYPE_HIDDEN,
												"value"		=> htmlspecialchars($lr_users[1][DATA_ID])
											);
		$show_rec_cnt++;
		$lr_show_rec[$show_rec_cnt]		=	array(
												"name"		=> "USER_ID",
												"type"		=> INPUT_TYPE_HIDDEN,
												"value"		=> htmlspecialchars($lr_users[1][USER_ID])
											);
		
		// 送信ボタン
		$l_btn_rec_cnt++;
		$lr_btn_rec[$l_btn_rec_cnt]	=		array(
												"name"		=> "bt_send",
												"type"		=> INPUT_TYPE_SUBMIT,
												"value"		=> "送信"
											);
	} else {
		// 入力データ
		foreach($_POST as $key => $i_val){
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => $key, "Input_val" => $i_val);
		}
		
		// USERS更新
		$l_msg = $lc_users->upUsersList($l_user_id, $input_data, $l_uid);
		
		if($l_msg[RETERN_CODE] == RETURN_NOMAL){
			$lr_show_rec = array(
								array(
									"type"		=> INPUT_TYPE_COMMENT,
									"value"		=> $l_msg[RETERN_MSG]
								)
			);
		} else {
			foreach($l_msg as $key => $e_msg){
				if($e_msg != RETURN_ERROR){
					$l_msg_cnt++;
					$lr_show_rec[$l_msg_cnt] =	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> $e_msg
												);
				}
			}
		}
		
		// 前画面へ戻る
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt] =	array(
										"type"		=> INPUT_TYPE_COMMENT,
										"value"		=> "<br>&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?token=".$l_token.$guid."\" ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 2).">"."戻る</a>"
									);
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
	$lc_smarty->assign("doctype",			$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	// タイトル
	$lc_smarty->assign("headtitle",			SCREEN_ZSMM0010);
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	$lc_smarty->assign("headinfo",			"");
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF']."?token=".$l_token.$guid);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// ユーザ情報
	$lc_smarty->assign("ar_users",			$lr_show_rec);
	$lc_smarty->assign("token",				$l_token);
	$lc_smarty->assign("ar_users_btn",		$lr_btn_rec);
	
	// ハイパーリンク
	$lr_bottom_menu	=	array(
							array(
								"link_url"	=> "workcontents.php?token=".$l_token,
								"value"		=> SCREEN_ZSMM0020."へ戻る",
								"key"		=> "0"
							),
							array(
								"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token.$guid,
								"value"		=> "ページ更新",
								"key"		=> "5"
							),
							array(
								"link_url"	=> "logout.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC002,
								"key"		=> "9"
							)/*,
							array(
								"link_url"	=> DIR_MAN."index.php?token=".$l_token,
								"value"		=> "操作マニュアル",
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
	$lc_smarty->display('MobileTemplateUsersSetup.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>