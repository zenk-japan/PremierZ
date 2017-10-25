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
 ファイル名：c_editSysadmin.php
 処理概要  ：システム管理者変更
 POST受領値：
             nm_token_code              トークン(必須)
             USER_ID                    システム管理者のユーザーID(必須)
             USER_CODE                  新しいユーザーコード(必須)
             PASSWORD                   新しいパスワード(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>\n";
		print var_dump($_POST);
		print "<br>\n";
		session_start();
		print "session-><br>\n";
		print var_dump($_SESSION);
		print "<br>\n";
	//	print "リクエスト:";
	//	print var_dump($_REQUEST);
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>\n";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<br>\n";	// メッセージ用接尾辞
	$l_html_rts			= "<br>\n";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	
	$l_error_flag		= 0;									// エラーフラグ

	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_editsysadm(Exception $e){
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
	set_exception_handler('my_exception_editsysadm');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	require_once('../maintenance/c_sessionControl.php');
	$lc_sess = new sessionControl();
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}
	
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		if($l_debug_mode==3){throw new Exception('l_data_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
/*----------------------------------------------------------------------------
   POST値の取得
  ----------------------------------------------------------------------------*/
	$lr_save_rec = array();
	// DATA_ID
	//$lr_save_rec['DATA_ID'] = $l_data_id;
	
	// ユーザーID
	$lr_save_rec['USER_ID'] = $_POST['USER_ID'];
			
	if ($_POST['USER_CODE'] != ""){
		// ユーザーコードを変更する場合
		$lr_save_rec['USER_CODE'] = $_POST['USER_CODE'];
	}
	if ($_POST['PASSWORD'] != ""){
		// パスワードを変更する場合
		$lr_save_rec['ENCRYPTION_PASSWORD'] = md5($_POST['PASSWORD']);
	}
	
	//print_r($lr_data);
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}

/*----------------------------------------------------------------------------
  データ保存
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_user_master.php');
	$lc_mdl = new m_user_master();
	// 保存レコードセット
	$lc_mdl->setSaveRecord($lr_save_rec);
	// 保存処理実行
	if (!$lc_mdl->updateRecord($_POST['USER_ID'])){
		$l_error_flag = 1;
		$l_error_message = "更新処理に失敗しました。";
	}
	
	if($l_debug_mode==1){print("Step-データ保存");print "\n";}
/*----------------------------------------------------------------------------
  終了処理
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		print "update nomal";
	}else{
		print $l_error_message;
	}
	return true;
?>
