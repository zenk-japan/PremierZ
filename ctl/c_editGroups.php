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
 ファイル名：c_editGroups.php
 処理概要  ：グループ管理編集画面
 POST受領値：
             nm_token_code              トークン(必須)
             nm_comp_name_cond          会社名(検索用)(任意)
             nm_group_name_cond         グループ名(検索用)(任意)
             nm_user_name_cond          ユーザー名(検索用)(任意)
             nm_show_page               表示ページ番号(任意)
             nm_max_page                最大ページ番号(任意)
             nm_selected_user_id        ユーザーID(任意)
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
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_sess_data_id			= "";									// 画面にセットするDATA_ID
	$l_comp_name_cond	= "";									// 会社名(検索用)
	$l_group_name_cond	= "";									// グループ名(検索用)
	$l_user_name_cond	= "";									// ユーザー名(検索用)
	$l_show_page		= "";									// 表示ページ番号
	$l_max_page			= "";									// 最大ページ番号
	$l_selected_user_id	= "";									// POSTされたユーザーID
	$l_show_dtl_user_id	= "";									// 編集を表示するユーザーID
	$lr_dtl_rec			= "";									// 編集表示用のレコード
	
	$l_error_flag		= 0;									// エラーフラグ

	//print "step3<br>\n";
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_usermnt(Exception $e){
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
	set_exception_handler('my_exception_usermnt');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>\n";}
	
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	
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
		if($key == "COMPANY_ID"){
			/*---------------
			   会社ID設定
			  ---------------*/
			// 会社MDL
			require_once('../mdl/m_company_master.php');
			$l_show_dtl_company_name	= $lr_data["COMPANY_NAME"];
			
			// 検索条件設定
			$lr_company_cond_dtl = array("COMPANY_NAME = '".$l_show_dtl_company_name."'");
			
			// レコード取得
			$l_comp_dtl = new m_company_master('Y', $lr_company_cond_dtl);
			$lr_company_detail = $l_comp_dtl->getViewRecord();
			
			$lr_data["COMPANY_ID"] = $lr_company_detail[1]["COMPANY_ID"];
		}else{
			$lr_data[$key] = $post_val;
		}
	}
	
	//print_r($lr_data);
	
	if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
	
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_group_masterクラスインスタンス作成
	require_once('../mdl/m_group_master.php');
	$lc_m_group = new m_group_master();
		
	// レコードセット
	$lc_m_group->setSaveRecord($lr_data);
	//print var_dump($lr_data)."\n";
	
	// チェック処理
	$lr_check_result = $lc_m_group->checkData();
	
	if(!$lr_check_result){
		// データが無い場合はfalseが戻る
		throw new Exception("データが有りません。");
	}
	//print var_dump($lr_check_result)."\n";
	
	// チェックに問題がなければ保存
	foreach($lr_check_result[0] as $l_key => $lr_result){
		//print "l_key->".$l_key."\n";
		//print "STATUS->".$lr_result['STATUS']."\n";
		//print "MESSAGE->".$lr_result['MESSAGE']."\n";
		if($lr_result['STATUS'] > 1){
			$l_error_flag = 1;
			$l_error_message .= $lr_result['MESSAGE'];
		}
	}
	
	if($l_debug_mode==1){print("Step-データチェック");print "\n";}
	
/*----------------------------------------------------------------------------
  データ保存
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		// 新規作成
		if($lr_data['sql_type'] == "insert"){
			if(!$lc_m_group->insertRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを登録できませんでした。";
			}
		// 更新
		}else if($lr_data['sql_type'] == "update"){
			if(!$lc_m_group->updateRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを更新できませんでした。";
			}
		}
	}
	
	if($l_debug_mode==1){print("Step-データ保存");print "\n";}
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
