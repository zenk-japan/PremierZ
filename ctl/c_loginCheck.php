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
 ファイル名：c_loginCheck.php
 処理概要  ：ログインチェック
 POST受領値：
             nm_username                ユーザー名(必須)
             nm_password                パスワード(必須)
             nm_usecomp                 利用会社コード(必須)
             only_check                 USER_IDとパスワードが正しいかチェックだけ行う場合は1
             nm_userid                  ユーザーID(任意)
 戻り値    ：
             $l_token					認証OKの場合の表示値(トークン)
             "NG"						認証NGの場合の表示値
             "OK"						認証OKでチェックだけの場合の表示値
******************************************************************************/
	$l_dir_prfx		= "./";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>\n";
		print var_dump($_POST);
		print "<br>\n";
		print "session-><br>\n";
		print var_dump($_SESSION);
		print "<br>\n";
	//	print "リクエスト:";
	//	print var_dump($_REQUEST);
	}
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');
	require_once('../lib/IndividualStaticValue.php');

	if($l_debug_mode==1){print("Step-前処理");print "<br>\n";}
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix	= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts		= "<BR>";								// HTMLの改行
	$l_username		= "";									// ユーザー名
	$l_password		= "";									// パスワード
	$l_usecomp		= "";									// 利用会社コード
	$l_auth_table	= "USERS_V";							// 認証に使用するテーブル名
	$l_auth_name	= "USER_CODE";							// 認証に使用する名前項目
	$l_auth_pass	= "ENCRYPTION_PASSWORD";				// 認証に使用するパスワード項目
	$l_auth_ucomp	= "USE_COMPANY_CODE";					// 認証に使用する利用会社コード項目
	$l_get_key1		= "DATA_ID";							// 認証で取得するキー項目1
	$l_get_key2		= "USER_ID";							// 認証で取得するキー項目2
	$l_get_key3		= "NAME";								// 認証で取得するキー項目3
	$l_authlevel	= AUTH_ADMI;							// 管理画面を利用できる権限(システム管理者)
	$l_chkonly_flg	= 0;									// セッション設定を行うかどうかのフラグ(0:行う、1:行わない)
	$l_userid		= "";									// ユーザーID
	$lr_login_log	= "";									// ログインログ用配列

	if($l_debug_mode==1){print("Step-変数宣言");print "<br>\n";}
// ==================================
// 例外定義
// ==================================
	function my_exception_logincheck(Exception $e){
		echo $e->getMessage();
		return;
    }
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}

// ==================================
// POST値の取得とチェック
// ==================================
	$l_username = $_POST['nm_username'];
	$l_password = $_POST['nm_password'];
	$l_usecomp  = $_POST['nm_usecomp'];
	
	// only_checkの値が1の場合はチェックのみのフラグを立てる
	if ($_POST['only_check'] == '1') {
		$l_chkonly_flg	= 1;
		$l_userid  = $_POST['nm_userid'];
		if(is_null($l_userid)){
			throw new Exception("ユーザーIDが取得できませんでした。");
		}
	}
	
	if($l_chkonly_flg != 1 && is_null($l_username)){
		throw new Exception("ユーザー名が取得できませんでした。");
	}
	if(is_null($l_password)){
		throw new Exception("パスワードが取得できませんでした。");
	}
	if($l_chkonly_flg != 1 && is_null($l_usecomp)){
		throw new Exception("利用会社コードが取得できませんでした。");
	}
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "<br>\n";}
	
// ==================================
// ログインチェック
// ==================================
	// ログ用の配列に値を格納
	$lr_login_log["USED_USER_CODE"]			= $_POST['nm_username'];
	$lr_login_log["USED_PASSWORD"]			= md5($_POST['nm_password']);
	$lr_login_log["USED_COMPANY_CODE"]		= $_POST['nm_usecomp'];
	$lr_login_log["CERTIFICATION_RESULT"]	= "";
	$lr_login_log["SPG_REFERER"]			= $_SERVER['HTTP_REFERER'];
	$lr_login_log["SPG_REMORT_ADDR"]		= $_SERVER['REMOTE_ADDR'];
	$lr_login_log["SPG_SERVER"]				= print_r($_SERVER, true);
	$lr_login_log["SPG_REQUEST"]			= "";	// パスワードが格納されている為NULLで登録
	$lr_login_log["REMARK"]					= "";
	
	// チェック処理
	if ($l_chkonly_flg == 1) {
	// チェックだけの場合
		$lr_login_log["REMARK"] .= "Only Check: USER_ID - ".$l_userid;
		// 検索条件設定
		$lr_user_cond = array($l_get_key2.' = "'.$l_userid.'"');
		array_push($lr_user_cond, $l_auth_pass.' = "'.md5($l_password).'"');
		array_push($lr_user_cond, 'VALIDITY_FLAG = "Y"');
		
		// ユーザーMDL
		require_once('../mdl/m_user_master.php');
		
		// レコード取得
		$lc_mum = new m_user_master('Y', $lr_user_cond);
		$lr_users = $lc_mum->getViewRecord();
		
		if(count($lr_users) == 1){
			// 認証成功(OKを返す)
			$lr_login_log["CERTIFICATION_RESULT"] = "OK";
			print "OK";
		}else if(count($lr_users) > 1){
			// ユーザー設定エラー
			throw new Exception("同一名称のユーザーが複数設定されています");
		}else{
			// 認証失敗
			$lr_login_log["CERTIFICATION_RESULT"] = "NG";
			print "NG";
		}
	
		if($l_debug_mode==1){print("Step-ログインチェック_チェックだけの場合");print "<br>\n";}
		
	}else{
	// トークンセットも行う場合
		// 検索条件設定
		$lr_user_cond = array($l_auth_name.' = "'.$l_username.'"');
		array_push($lr_user_cond, $l_auth_pass.' = "'.md5($l_password).'"');
		array_push($lr_user_cond, $l_auth_ucomp.' = "'.$l_usecomp.'"');
		array_push($lr_user_cond, 'VALIDITY_FLAG = "Y"');
		
		// ユーザーMDL
		require_once('../mdl/m_user_master.php');
		
		// レコード取得
		//var_dump($lr_user_cond);
		$lc_mum = new m_user_master('Y', $lr_user_cond);
		$lr_users = $lc_mum->getViewRecord();
		
		if(count($lr_users) == 1){
			// 認証成功(セッションを開始しtokenを返す)
			$lr_login_log["CERTIFICATION_RESULT"] = "OK";
			require_once('../lib/sessionControl.php');

			$lc_sess = new sessionControl();
			$lc_sess->destroySession();						// セッション破棄
			$l_token = $lc_sess->setToken();				// トークンセット
			
			// ユーザー情報をセッションにセット
			foreach($lr_users[1] as $l_key => $l_value){
				$lc_sess->setSesseionItem($l_key, $l_value);
			}
			
			// USERSのRESERVE_1にセットされているトークン（パスワードリセット用）をクリア
			// 保存用レコードセット
			if ($lr_users[1]['RESERVE_1'] != ""){
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
			print $l_token;
		}else if(count($lr_users) > 1){
			// ユーザー設定エラー
			throw new Exception("同一名称のユーザーが複数設定されています");
		}else{
			// 認証失敗
			$lr_login_log["CERTIFICATION_RESULT"] = "NG";
			print "NG";
		}
		if($l_debug_mode==1){print("Step-ログインチェック_トークンセットも行う場合");print "<br>\n";}
	}
	
	// ログインログへの書き込み
	require_once('../mdl/m_login_log.php');
	$lc_mllog = new m_login_log();
	$lr_mllog = $lc_mllog->setSaveRecord($lr_login_log);
	$lc_mllog->insertRecord();
?>
