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
 ファイル名：c_projects_xls_import.php
 処理概要  ：エクセルデータでの一括登録処理
 POST受領値：
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 1;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>\n";
	//	print var_dump($_POST);
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
	require_once('../mdl/m_common_master.php');
	//print "step2<br>\n";
	
/*----------------------------------------------------------------------------
   ライブラリのインクルード
  ----------------------------------------------------------------------------*/
	include '../phpexcel/Classes/PHPExcel.php';
	include '../phpexcel/Classes/PHPExcel/IOFactory.php';

/*----------------------------------------------------------------------------
  変数宣言
  ----------------------------------------------------------------------------*/
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)
	$l_mes_sufix		= " at=>".basename(__FILE__)."<br>\n";	// メッセージ用接尾辞
	$l_html_rts			= "<br>\n";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_sess_data_id		= "";									// 画面にセットするDATA_ID
	$l_comp_name_cond	= "";									// 会社名(検索用)
	$l_group_name_cond	= "";									// グループ名(検索用)
	$l_user_name_cond	= "";									// ユーザー名(検索用)
	$l_show_page		= "";									// 表示ページ番号
	$l_max_page			= "";									// 最大ページ番号
	$l_selected_user_id	= "";									// POSTされたユーザーID
	$l_show_dtl_user_id	= "";									// 編集を表示するユーザーID
	$lr_dtl_rec			= "";									// 編集表示用のレコード

	$l_error_flag		= 0;									// エラーフラグ

	$write_pj_flag		= ($_POST["ins_pj"] == 1 ) ? 1 : 0;		// プロジェクト管理データ読み取りフラグ
	$write_task_flag	= ($_POST["ins_task"] == 1 ) ? 1 : 0;	// 作業管理データ読み取りフラグ
	$write_staff_flag	= ($_POST["ins_staff"] == 1 ) ? 1 : 0;	// 人員管理データ読み取りフラグ

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
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
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
	// ユーザー名の取得
	$l_sess_user_name = $lc_sess->getSesseionItem('NAME');
	if($l_sess_user_name == ""){
		throw new Exception($l_error_type_st);
	}
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>\n";}
	
	

/*----------------------------------------------------------------------------
  Excelからデータを取得し配列に格納する
  ----------------------------------------------------------------------------*/

	/*---------------------------------
	ファイルを読み込んでインスタンス化
	-----------------------------------*/
/*	$excelFile = "../uploads/Book1.xlsx";//ファイルパスを指定
	$objReader = PHPExcel_IOFactory::createReader('Excel5');//Excel2003以前の形式
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');//Excel2007以降の形式
	$xlsObject = $objReader->load($excelFile); */

	$objReader = selectXlsReader($_POST["excel_file"]);
	$xlsObject = $objReader->load($_POST["excel_file"]);

	// シートごとに読んでいく
	for ($i = 0; $i < $xlsObject->getSheetCount(); $i++) {
		$xlsObject->setActiveSheetIndex($i);
		$xlsSheet = $xlsObject->getActiveSheet();
		// シート名
		$sheetTitle = $xlsSheet->getTitle();
		$j = 0;
		// シートの行ごとに読んでいく
		if($sheetTitle == "プロジェクト管理" && $write_pj_flag == 1){
			getProjectData($xlsSheet,$l_sess_user_id,$l_sess_user_name,$l_sess_data_id,$l_debug_mode);
		}else if($sheetTitle == "作業管理" && $write_task_flag == 1){
			getTaskData($xlsSheet,$l_sess_user_id,$l_sess_user_name,$l_sess_data_id,$l_debug_mode);
		}else if($sheetTitle == "人員管理" && $write_staff_flag == 1){
			getWorkstaffSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode);
		}/*ここまで各シートごとの処理*/
	}/*エクセルデータ読み取り処理終了*/
	

	return true;

/*----------------------------------------------------------------------------
	  [excel処理]postされたデータの拡張子に応じてエクセル処理用の定義を分岐させる
  ----------------------------------------------------------------------------*/
function selectXlsReader($postedFile){
	$xlsExt = getExtension($postedFile);
	if($xlsExt == "xls"){
		$xlsReader = PHPExcel_IOFactory::createReader('Excel5');
	}else if($xlsExt == "xlsx"){
		$xlsReader = PHPExcel_IOFactory::createReader('Excel2007');
	}else{
		print 'cannot handling this file.';
		exit;
	}
	return $xlsReader;
}

/*----------------------------------------------------------------------------
	  [excel処理]postされたデータの拡張子を取得する
  ----------------------------------------------------------------------------*/
function getExtension($postedFileName){
	$fileExt = substr($postedFileName, strrpos($postedFileName, '.') + 1);
	return $fileExt;
}

/*----------------------------------------------------------------------------
	  [各シート共通]レコード読み取り、データチェック、データ保存、終了処理
  ----------------------------------------------------------------------------*/

function getSqlColumn($tableName){
	require_once('../mdl/m_column_info.php');
	$cInfo		= new ColumnInfo($tableName);
	$tableData	= $cInfo->getColumnInfoAll();
	$arrayCode	= array();
	$i=0;
	foreach($tableData as $table => $column){
		$arrayCode[$i] = $column["COLUMN_NAME"];
		$i++;
	}
	return $arrayCode;
}

/*----------------------------------------------------------------------------
	  [プロジェクト管理]レコード読み取り、データチェック、データ保存、終了処理
  ----------------------------------------------------------------------------*/
function getProjectData($xlsSheet,$l_sess_user_id,$l_sess_user_name,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode = getSqlColumn('ESTIMATES');
	$arrayCode[9]	= "ENDUSER_COMPANY_NAME";
	$arrayCode[10]	= "ENDUSER_USER_NAME";
	$arrayCode[11]	= "REQUEST_COMPANY_NAME";
	$arrayCode[12]	= "REQUEST_USER_NAME";
//	var_dump($arrayCode);unlink($_POST["excel_file"]);return;

	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 2;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {
			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			if($arrayCode[$k] == "ORDER_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "ORDER_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = "";
				}
				unset($cMas);
			}else if($arrayCode[$k] == "WORK_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "WORK_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = "";
				}
				unset($cMas);
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
			while(	$arrayCode[$k] == "SUB_NUMBER"
				 || $arrayCode[$k] == "ESTIMATE_USER_ID"){
				$k++;
			}
		}
		foreach($_POST as $key => $post_val){
			if($key == "nm_token_code" || $key == "sql_type" ){
				$data[$sheetTitle][$j][$key] = $post_val;
			}
		}
		
		$data[$sheetTitle][$j]["ESTIMATE_ID"]			= "";		
		$data[$sheetTitle][$j]["SUB_NUMBER"]			= "00";		
		$data[$sheetTitle][$j]["DATA_ID"]				= $l_sess_data_id;
		$data[$sheetTitle][$j]["ESTIMATE_USER_NAME"]	= $l_sess_user_name;
		$data[$sheetTitle][$j]["VALIDITY_FLAG"]			= "Y";

		$j++;
	}
	
	/* 配列の中身を確認 */
//	var_dump($data);unlink($_POST["excel_file"]);return;

	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				$lr_data[$key] = $post_val;
			}
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}

//			var_dump($lr_data);	unlink($_POST["excel_file"]);continue;

			writeProjectSql($lr_data,$l_sess_user_id,$l_debug_mode);
			
		}/*ここまでエクセル各行ごとの処理*/
	}
}

function writeProjectSql($lr_data,$l_sess_user_id,$l_debug_mode){
	/*----------------------------------------------------------------------------
	  データチェック
	  ----------------------------------------------------------------------------*/
		// m_estimatesクラスインスタンス作成
		require_once('../mdl/m_estimates.php');
		$lc_db_model = new m_estimates();
			
		// レコードセット
		$lc_db_model->setSaveRecord($lr_data);
		//var_dump($lr_data)."\n";
		
		// チェック処理
		$lr_check_result = $lc_db_model->checkData();
		
		if(!$lr_check_result){
			// データが無い場合はfalseが戻る
			throw new Exception("データが有りません。");
		}
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
		
		if($l_debug_mode==1){print("Step-データチェック");print($lr_data['sql_type']);print "\n";}
		
	/*----------------------------------------------------------------------------
	  データ保存
	  ----------------------------------------------------------------------------*/
		if($l_error_flag == 0){
			// 新規作成
			if($lr_data['sql_type'] == OPMODE_INSERT){
				if(!$lc_db_model->insertRecord($l_sess_user_id)){
					$l_error_flag = 1;
					//$l_error_message .= "データを登録できませんでした。";
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
		chkErrorflag($lr_data,$l_error_flag);

}

/*----------------------------------------------------------------------------
	  [作業管理]データ格納、レコードごとにデータチェック、データ保存
  ----------------------------------------------------------------------------*/
function getTaskData($xlsSheet,$l_sess_user_id,$l_sess_user_name,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode		= getSqlColumn('WORK_CONTENTS');
	$arrayCode[3]	= "ESTIMATE_CODE";
	$arrayCode[11]	= "WORK_ARRANGEMENT_CODE";
	
	
	
	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 2;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {
			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			if($arrayCode[$k] == "WORK_STATUS"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "WORK_STATUS", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
					unset($cMas);
				}
			}else if($arrayCode[$k] == "EXCESS_LIQUIDATION_FLAG"){
				if($cell->getCalculatedValue() == "する"){
					$data[$sheetTitle][$j][$arrayCode[$k]] = "Y";
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = "N";
				}
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
			while(	$arrayCode[$k] == "TOTAL_SALES"
				 || $arrayCode[$k] == "GROSS_MARGIN"
				 || $arrayCode[$k] == "GROSS_MARGIN_RATE"){
				$k++;
			}
		}
		foreach($_POST as $key => $post_val){
			if($key == "nm_token_code" || $key == "sql_type" ){
				$data[$sheetTitle][$j][$key] = $post_val;
			}
		}
		$data[$sheetTitle][$j]["DATA_ID"]				= $l_sess_data_id;
		$data[$sheetTitle][$j]["ESTIMATE_ID"] 			= "0";
		$data[$sheetTitle][$j]["WORK_ARRANGEMENT_ID"] 	= "0";
		$data[$sheetTitle][$j]["VALIDITY_FLAG"] 		= "Y";
		$data[$sheetTitle][$j]["nm_rd_copytype"] 		= "S";
		$j++;
	}
	
	//var_dump($data);unlink($_POST["excel_file"]);exit;
	/*---------------------------------
	 レコード１件毎の処理
	-----------------------------------*/

	$ctr = "0";
	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				if($key == "ESTIMATE_ID"){
					/*---------------
					   見積もりコードから見積もりIDを検索して設定
					  ---------------*/
					// 見積もりMDL
					require_once('../mdl/m_estimates.php');
					$l_show_dtl_estimate_code	= $lr_data["ESTIMATE_CODE"];
					$l_show_dtl_data_id			= $lr_data["DATA_ID"];
					
					// 検索条件設定
					$lr_estimate_cond_dtl	= array("ESTIMATE_CODE = '".$l_show_dtl_estimate_code."'");
					$lr_estimate_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
					$lr_estimate_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
					
					// レコード取得
					$l_est_dtl = new m_estimates('Y', $lr_estimate_cond_dtl);
					$lr_estimate_detail = $l_est_dtl->getViewRecord();
					
					$lr_data["ESTIMATE_ID"] = $lr_estimate_detail[1]["ESTIMATE_ID"];
				}else if($key == "WORK_ARRANGEMENT_ID"){
					$l_show_dtl_user_code	= $lr_data["WORK_ARRANGEMENT_CODE"];
					$l_show_dtl_data_id		= $lr_data["DATA_ID"];
					$lr_data["WORK_ARRANGEMENT_ID"] = getUserIdForCode($l_show_dtl_user_code,$l_show_dtl_data_id);
				}else{
					$lr_data[$key] = $post_val;
				}
			}
			//var_dump($lr_data);	unlink($_POST["excel_file"]);continue;
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			
			if($ctr != "0")writeTaskSql($lr_data,$l_sess_user_id,$l_debug_mode);$ctr++;
		}/*ここまでエクセル各行ごとの処理*/
	}
}
function writeTaskSql($lr_data,$l_sess_user_id,$l_debug_mode){
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_estimatesクラスインスタンス作成
	require_once('../mdl/m_workcontents.php');
	$lc_db_model = new m_workcontents();
		
	// レコードセット
	$lc_db_model->setSaveRecord($lr_data);
	if($l_debug_mode==1){print("Step-SQLレコードセット");print "\n";}
	
	// チェック処理
	$lr_check_result = $lc_db_model->checkData();
	if($l_debug_mode==1){print("Step-SQLチェック処理");print "\n";}
	
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
	
	if($l_debug_mode==1){print("Step-データチェック");print "\n";}
	
/*----------------------------------------------------------------------------
  データ保存
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		// 新規作成
		if($lr_data['sql_type'] == OPMODE_INSERT){
			if(!$lc_db_model->insertRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを登録できませんでした。";
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
	chkErrorflag($lr_data,$l_error_flag);
}

/*----------------------------------------------------------------------------
	  [人員管理]データ格納、レコードごとにデータチェック、データ保存
  ----------------------------------------------------------------------------*/
function getWorkstaffSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode		= getSqlColumn('WORK_STAFF');
	$arrayCode[2]	= "WORK_CONTENT_CODE";
	$arrayCode[3]	= "WORK_BASE_CODE";
	$arrayCode[4]	= "WORK_USER_CODE";

	//var_dump($arrayCode);	unlink($_POST["excel_file"]);exit;
	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 2;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {
			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			if($arrayCode[$k] == "APPROVAL_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "APPROVAL_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = "UC";
				}
				unset($cMas);
			}else if($arrayCode[$k] == "CANCEL_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "CANCEL_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = "WR";
				}
				unset($cMas);
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
			while(	$arrayCode[$k] == "TRANSMISSION_FLAG" 
				||	$arrayCode[$k] == "DISPATCH_STAFF_TIMET" 
				||	$arrayCode[$k] == "ENTERING_STAFF_TIMET" 
				||	$arrayCode[$k] == "ENTERING_MANAGE_TIMET"
				||	$arrayCode[$k] == "LEAVE_STAFF_TIMET"
				||	$arrayCode[$k] == "LEAVE_MANAGE_TIMET"){
				$k++;
			}
		}
		
		foreach($_POST as $key => $post_val){
			if($key == "nm_token_code" || $key == "sql_type" ){
				$data[$sheetTitle][$j][$key] = $post_val;
			}
		}
		$data[$sheetTitle][$j]["DATA_ID"]			= $l_sess_data_id;
		$data[$sheetTitle][$j]["WORK_CONTENT_ID"]	= "0";
		$data[$sheetTitle][$j]["WORK_BASE_NAME"]	= "0";
		$data[$sheetTitle][$j]["WORK_USER_ID"]		= "0";
		$data[$sheetTitle][$j]["COMPANY_NAME"]		= "0";
		$data[$sheetTitle][$j]["WORK_BASE_ID"]		= "0";//拠点関係を上書きするため一番最後に設定
		$j++;
	}
	//var_dump($data);	unlink($_POST["excel_file"]);exit;
	/*---------------------------------
	 レコード１件毎の処理
	-----------------------------------*/
	
	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				if($key == "WORK_CONTENT_ID"){
					$l_show_dtl_wcon_code	= $lr_data["WORK_CONTENT_CODE"];
					$l_show_dtl_data_id		= $lr_data["DATA_ID"];
					require_once('../mdl/m_workcontents.php');
					// 検索条件設定
					$lr_wcon_cond_dtl	= array("WORK_CONTENT_CODE = '".$l_show_dtl_wcon_code."'");
					$lr_wcon_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
					$lr_wcon_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
					// レコード取得
					$l_wcon_dtl		= new m_workcontents('Y', $lr_wcon_cond_dtl);
					$lr_wcon_detail	= $l_wcon_dtl->getViewRecord();
					$lr_data["WORK_CONTENT_ID"] = $lr_wcon_detail[1]["WORK_CONTENT_ID"];
				}else if($key == "WORK_BASE_ID"){
					$l_show_dtl_wb_code	= $lr_data["WORK_BASE_CODE"];
					$l_show_dtl_data_id	= $lr_data["DATA_ID"];
					require_once('../mdl/m_workplace_master.php');
					// 検索条件設定
					$lr_wb_cond_dtl		= array("BASE_CODE = '".$l_show_dtl_wb_code."'");
					$lr_wb_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
					$lr_wb_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
					// レコード取得
					$l_wb_dtl = new m_workplace_master('Y', $lr_wb_cond_dtl);
					$lr_wb_detail = $l_wb_dtl->getViewRecord();
					$lr_data["WORK_BASE_ID"]	= $lr_wb_detail[1]["BASE_ID"];
					$lr_data["WORK_BASE_NAME"]	= $lr_wb_detail[1]["BASE_NAME"];
					$lr_data["COMPANY_NAME"]	= getCompanyName($lr_wb_detail[1]["COMPANY_ID"],$l_show_dtl_data_id);
				}else if($key == "WORK_USER_ID"){
					$l_show_dtl_user_code		= $lr_data["WORK_USER_CODE"];
					$l_show_dtl_data_id			= $lr_data["DATA_ID"];
					$lr_data["WORK_USER_ID"]	= getUserIdForCode($l_show_dtl_user_code,$l_show_dtl_data_id);
				}else{
					$lr_data[$key] = $post_val;
				}
			}
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			//var_dump($lr_data);	unlink($_POST["excel_file"]);continue;

			writeWorkstaffSql($lr_data,$l_sess_user_id,$l_debug_mode);
		}/*ここまでエクセル各行ごとの処理*/
	}
}

function writeWorkstaffSql($lr_data,$l_sess_user_id,$l_debug_mode){
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_estimatesクラスインスタンス作成
	require_once('../mdl/m_workstaff.php');
	$lc_db_model = new m_workstaff();

	// レコードセット
	$lc_db_model->setSaveRecord($lr_data);
	if($l_debug_mode==1){print("Step-SQLレコードセット");print "\n";}

	// チェック処理
	$lr_check_result = $lc_db_model->checkData();
	if($l_debug_mode==1){print("Step-SQLチェック処理");print "\n";}

	if(!$lr_check_result){
		// データが無い場合はfalseが戻る
		throw new Exception("データが有りません。");
	}
	var_dump($lr_check_result);
	// チェックにがあれば、メッセージを格納しエラーフラグを立てる
	foreach($lr_check_result[0] as $l_key => $lr_result){
		print "l_key->".$l_key."\n";
		print "STATUS->".$lr_result['STATUS']."\n";
		print "MESSAGE->".$lr_result['MESSAGE']."\n";
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
		if($lr_data['sql_type'] == OPMODE_INSERT){
			if(!$lc_db_model->insertRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを登録できませんでした。";
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
	chkErrorflag($lr_data,$l_error_flag);

}


/*----------------------------------------------------------------------------
	[各シート共通]会社名から会社IDを設定
  ----------------------------------------------------------------------------*/
function getCompanyId($lr_data_cname,$lr_data_id){
	// 会社MDL
	require_once('../mdl/m_company_master.php');
	$l_show_dtl_company_name	= $lr_data_cname;
	$l_show_dtl_data_id			= $lr_data_id;
	
	// 検索条件設定
	$lr_company_cond_dtl	= array("COMPANY_NAME = '".$l_show_dtl_company_name."'");
	$lr_company_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
	$lr_company_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
	
	// レコード取得
	$l_comp_dtl = new m_company_master('Y', $lr_company_cond_dtl);
	$lr_company_detail = $l_comp_dtl->getViewRecord();
	return $lr_company_detail[1]["COMPANY_ID"];
//	$lr_data["COMPANY_ID"] = $lr_company_detail[1]["COMPANY_ID"];
}

/*----------------------------------------------------------------------------
	[人員管理]会社IDから会社名を設定
  ----------------------------------------------------------------------------*/
function getCompanyName($lr_data_cid,$lr_data_id){
	// 会社MDL
	require_once('../mdl/m_company_master.php');
	$l_show_dtl_company_id	= $lr_data_cid;
	$l_show_dtl_data_id		= $lr_data_id;
	
	// 検索条件設定
	$lr_company_cond_dtl	= array("COMPANY_ID = '".$l_show_dtl_company_id."'");
	$lr_company_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
	$lr_company_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
	
	// レコード取得
	$l_comp_dtl = new m_company_master('Y', $lr_company_cond_dtl);
	$lr_company_detail = $l_comp_dtl->getViewRecord();
	return $lr_company_detail[1]["COMPANY_NAME"];
}
/*----------------------------------------------------------------------------
	[各シート共通]会社名とユーザ名からユーザIDを設定
	※同会社内に同姓同名のユーザがいる場合の処理は未実装
	　ユーザコードからユーザIDを検索する処理に差し替える予定
  ----------------------------------------------------------------------------*/
function getUserId($lr_data_uname,$lr_data_cname,$lr_data_id){

	// UserMDL
	require_once('../mdl/m_user_master.php');
	$l_show_dtl_user_name	= $lr_data_uname;
	$l_show_dtl_data_id		= $lr_data_id;
	$l_show_dtl_company_id	= getCompanyId($lr_data_cname,$lr_data_id);
	
	// 検索条件設定
	$lr_user_cond_dtl	= array("NAME = '".$l_show_dtl_user_name."'");
	$lr_user_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
	$lr_user_cond_dtl[]	= ("COMPANY_ID = '".$l_show_dtl_company_id."'");
	$lr_user_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
	
	// レコード取得
	$l_usr_dtl = new m_user_master('Y', $lr_user_cond_dtl);
	$lr_user_detail = $l_usr_dtl->getViewRecord();

	return $lr_user_detail[1]["USER_ID"];
}

/*----------------------------------------------------------------------------
	[各シート共通]ユーザコードからユーザIDを設定
  ----------------------------------------------------------------------------*/
function getUserIdForCode($lr_data_ucode,$lr_data_id){

	// UserMDL
	require_once('../mdl/m_user_master.php');
	$l_show_dtl_user_code	= $lr_data_ucode;
	$l_show_dtl_data_id		= $lr_data_id;
	
	// 検索条件設定
	$lr_user_cond_dtl	= array("USER_CODE = '".$l_show_dtl_user_code."'");
	$lr_user_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
	$lr_user_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
	
	// レコード取得
	$l_usr_dtl = new m_user_master('Y', $lr_user_cond_dtl);
	$lr_user_detail = $l_usr_dtl->getViewRecord();

	return $lr_user_detail[1]["USER_ID"];
}

function chkErrorflag($lr_data,$l_error_flag){
/*----------------------------------------------------------------------------
  [各シート共通]終了処理
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		unlink($_POST["excel_file"]); 
		if($lr_data['sql_type'] == "insert"){
			print "insert nomal\n";
		}else if($lr_data['sql_type'] == "update"){
			print "update nomal";
		}
	}else{
		print $l_error_message;
	}
}
?>