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
 ファイル名：c_group_member_update.php
 処理概要  ：グループメンバー保存
 POST受領値：nm_token_code              トークン(必須)
             その他                     更新情報
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";	// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post->\n";
		print var_dump($_POST);
		print "\n";
		print "session->\n";
		session_start();
		print var_dump($_SESSION);
		if($l_debug_mode == 2){return;}
	}
/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st		= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix			= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_post_token			= "";									// POSTされたトークン
	$l_sess_token			= "";									// セッションで保持しているトークン
	$l_user_id				= "";									// ユーザーID
	$lr_update_param		= array();								// 更新情報
	$l_return_code			= 0;									// リターンコード
	//print "step3<br>";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_grpmupdate(Exception $e){
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
	set_exception_handler('my_exception_grpmupdate');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
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
		throw new Exception($l_error_type_st);
	}
	
	// USER_IDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_user_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}
/*----------------------------------------------------------------------------
  POST変数取得
  ----------------------------------------------------------------------------*/
	// keyがnmで始まらないPOST変数は更新情報とみなす
	foreach($_POST as $l_key => $l_value){
		if(mb_substr($l_key, 0, 2) != 'nm'){
			$lr_update_param[$l_key] = $l_value;
		}
	}
	//var_dump($lr_update_param);
	
	if($l_debug_mode==1){print("Step-POST変数取得");print "<br>";}
/*----------------------------------------------------------------------------
  DB更新取得
  ----------------------------------------------------------------------------*/
	if(count($lr_update_param) > 0){
		// 更新用のレコード作成
		$lr_update_record = array();
		$l_rec_cnt = 0;
		foreach($lr_update_param as $l_key => $l_value){
			$lr_update_record[$l_rec_cnt]['USER_ID']	= $l_key;
			$lr_update_record[$l_rec_cnt]['GROUP_ID']	= $l_value;
			$l_rec_cnt++;
		}
		//var_dump($lr_update_record);
		
		// MDLインスタンス作成
		require_once('../mdl/m_user_master.php');
		$lc_mum = new m_user_master();
		
		// レコードセット
		$lc_mum->setSaveRecord($lr_update_record);
		//$lc_mum->setSaveRecord(array("TEST"=>"SERT","TEST1"=>"SERT1"));
		
		// 更新実行
		if(!$lc_mum->updateRecord($l_user_id)){
			print "保存中にエラーが発生しました。\nデータを確認して下さい。";
			$l_return_code = 2;
		}
	}
	
	// 戻り値返却
	print $l_return_code;

	if($l_debug_mode==1){print("Step-完了");print "<br>";}
?>