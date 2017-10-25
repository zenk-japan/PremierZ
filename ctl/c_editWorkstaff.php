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
 ファイル名：c_editWorkstaff.php
 処理概要  ：人員編集処理
 POST受領値：
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
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_sess_data_id		= "";									// 画面にセットするDATA_ID
	
	$l_error_flag		= 0;									// エラーフラグ

	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_workstaffmnt(Exception $e){
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
	set_exception_handler('my_exception_workstaffmnt');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	
	// POSTされたトークンを取得
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}

	// セッションからトークンを取得
	$l_sess_token = $lc_sess->getToken();
	//$l_sess_token = $lc_sess->setToken();
	//print var_dump($_SESSION);

	// セッションからトークンが取得できない場合は不正アクセスとみなす
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}

	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}
		
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_user_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	// DATA_IDの取得
	$l_sess_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_sess_data_id == ""){
		if($l_debug_mode==3){throw new Exception('l_sess_data_idがNULL');}
		throw new Exception($l_error_type_st);
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
	
/*----------------------------------------------------------------------------
   POST値の取得
  ----------------------------------------------------------------------------*/
	foreach($_POST as $key => $post_val){
		$lr_data[$key] = $post_val;
	}
	
	// insert時は複数ユーザーを配列に格納
	if($lr_data['sql_type'] == OPMODE_INSERT){
		$l_cnt = 1;
		$lr_multiuser_data = "";
		$l_user_post_item_pfix = "nm_work_user_id";
		while ($_POST[$l_user_post_item_pfix.$l_cnt] != "" and $l_cnt < 10000){
			// POSTされたユーザーの数分POST値レコードを作成する
			$lr_multiuser_data[$l_cnt] = $lr_data;
			$lr_multiuser_data[$l_cnt]["WORK_USER_ID"] = $_POST[$l_user_post_item_pfix.$l_cnt];
			$l_cnt++;
		}
	}else{
		//update時は、作業費表示フラグを再設定
		$lr_data["WORK_UNIT_PRICE_DISPLAY_FLAG"] = $_POST["nm_unit_price_display"];
	}
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_estimatesクラスインスタンス作成
	require_once('../mdl/m_workstaff.php');
	$lc_db_model = new m_workstaff();
		
	
	if($lr_data['sql_type'] == OPMODE_INSERT){
	// 新規の場合
		foreach($lr_multiuser_data as $rec_num => $workstaff_rec){
			// レコードセット
			$lc_db_model->setSaveRecord($workstaff_rec);
			//var_dump($lr_data)."\n";
			// チェック処理
			$lr_check_result = $lc_db_model->checkData();

			if(!$lr_check_result){
				// データが無い場合はfalseが戻る
				throw new Exception("データが有りません。");
			}
			// チェックにがあれば、メッセージを格納しエラーフラグを立てる
			foreach($lr_check_result[0] as $l_key => $lr_result){
				//print "l_key->".$l_key."\n";
				//print "STATUS->".$lr_result['STATUS']."\n";
				//print "MESSAGE->".$lr_result['MESSAGE']."\n";
				if($lr_result['STATUS'] > 1){
					$l_error_flag = 1;
					$l_error_message .= $lr_result['MESSAGE'];
				}
			}
			
			if ($l_error_flag == 1){
				break;
			}
		}
	}else{
	// 更新の場合
		// レコードセット
		$lc_db_model->setSaveRecord($lr_data);
		//var_dump($lr_data)."\n";
		// チェック処理
		$lr_check_result = $lc_db_model->checkData();
		
		if(!$lr_check_result){
			// データが無い場合はfalseが戻る
			throw new Exception("データが有りません。");
		}
		// チェックにがあれば、メッセージを格納しエラーフラグを立てる
		foreach($lr_check_result[0] as $l_key => $lr_result){
			//print "l_key->".$l_key."\n";
			//print "STATUS->".$lr_result['STATUS']."\n";
			//print "MESSAGE->".$lr_result['MESSAGE']."\n";
			if($lr_result['STATUS'] > 1){
				$l_error_flag = 1;
				$l_error_message .= $lr_result['MESSAGE'];
			}
		}
		
	}
	
	if($l_debug_mode==1){print("Step-データチェック");print "\n";}
/*----------------------------------------------------------------------------
  データ保存
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		// 新規作成
		if($lr_data['sql_type'] == OPMODE_INSERT){
			foreach ($lr_multiuser_data as $rec_num => $workstaff_rec){
				//var_dump($workstaff_rec);
				$lc_db_model->setSaveRecord($workstaff_rec);
				if(!$lc_db_model->insertRecord($l_sess_user_id)){
					$l_error_flag = 1;
				//	$l_error_message .= "データを登録できませんでした。";
				}
			}
		// 更新
		}else if($lr_data['sql_type'] == OPMODE_UPDATE){
			if(!$lc_db_model->updateRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを更新できませんでした。";
			}
		}
	}
	if($l_debug_mode==1){print("Step-データ保存");print "\n";print $lr_data['sql_type']."\n";}
/*----------------------------------------------------------------------------
  終了処理
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		if($lr_data['sql_type'] == "insert"){
			print "insert nomal";
		}else if($lr_data['sql_type'] == "update"){
			print "update nomal";
		}
	}else{
		print $l_error_message;
	}
	return true;
?>
