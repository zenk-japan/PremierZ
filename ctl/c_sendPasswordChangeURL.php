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
 ファイル名：c_sendPasswordChangeURL.php
 処理概要  ：PC用パスワード変更受付処理
 POST受領値：
 			nm_username						ユーザーコード
 			nm_usecomp						利用会社コード
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
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
	$l_user_code		= "";
	$l_use_company_code	= "";
	
	$l_error_flag		= 0;									// エラーフラグ

	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_passwordchangereq(Exception $e){
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
	set_exception_handler('my_exception_passwordchangereq');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
   POST値の取得
  ----------------------------------------------------------------------------*/
	$l_user_code		= $_POST["nm_username"];
	$l_use_company_code	= $_POST["nm_usecomp"];
	
	if ($l_user_code == ''){
		throw new exception('ユーザーコードが不正です。');
	}
	if ($l_use_company_code == ''){
		throw new exception('利用会社コード。');
	}
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
/*----------------------------------------------------------------------------
  パスワード再設定用URL送信
  ----------------------------------------------------------------------------*/
	require_once('../lib/PasswordProduction.php');
	$lc_pprod = new PasswordProduction('', $l_user_code, $l_use_company_code);
	$lr_users = array();
	$lr_users = $lc_pprod->getUserRec();

	// ユーザーが取得できない場合はNGを返す
	if (count($lr_users) == 0){
		echo "NG";
		return;
	}
	
	// メールアドレスが登録されていない場合はエラーメッセージを返す
	if ($lr_users[1]['HOME_MAIL'] == ""){
		echo "自宅メールアドレスの登録が有りませんでした。\n管理者に連絡し、対処を依頼して下さい。";
		return;
	}
	
	// 再設定用URLの送信
	$lc_pprod->sendPasswordResetRequiestURL(1);
	
	// 結果取得
	$l_mail_send_flag = $lc_pprod->getMailsendFlag();
	$l_users_write_flag = $lc_pprod->getUserwriteFlag();
	
	if($l_debug_mode==1){print("Step-データチェック");print "\n";}

/*----------------------------------------------------------------------------
  終了処理
  ----------------------------------------------------------------------------*/
	if ($l_mail_send_flag === 0){
		echo "メールの送信に失敗しました。\n管理者に連絡し、対処を依頼して下さい。";
	}else if ($l_users_write_flag === 0){
		echo "パスワードのリセットに失敗しました。\n管理者に連絡し、対処を依頼して下さい。";
	}else{
		echo "OK";
	}
	return;
?>
