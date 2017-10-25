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
 ファイル名：c_xls_import.php
 処理概要  ：エクセルデータでの一括登録処理
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
	require_once('../mdl/m_common_master.php');
	require_once('../mdl/m_authority_master.php');
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

	$write_company_flag		= ($_POST["ins_comp"] == 1 ) ? 1 : 0;	// 会社管理データ読み取りフラグ
	$write_workplace_flag	= ($_POST["ins_workp"] == 1 ) ? 1 : 0;	// 作業拠点管理データ読み取りフラグ
	$write_group_flag		= ($_POST["ins_grp"] == 1 ) ? 1 : 0;	// グループ管理データ読み取りフラグ
	$write_user_flag		= ($_POST["ins_user"] == 1 ) ? 1 : 0;	// ユーザ管理データ読み取りフラグ

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
		if($sheetTitle == "会社管理" && $write_company_flag == 1){
			getCompanyData($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode);
		}else if($sheetTitle == "作業拠点管理" && $write_workplace_flag == 1){
			getWorkplaceSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode);
		}else if($sheetTitle == "グループ管理" && $write_group_flag == 1){
			getGroupSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode);
		}else if($sheetTitle == "ユーザ管理" && $write_user_flag == 1){
			getUserSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode);
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
	  [会社情報]レコード読み取り、データチェック、データ保存、終了処理
  ----------------------------------------------------------------------------*/
function getCompanyData($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode = getSqlColumn('COMPANIES');
	
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
			if($arrayCode[$k] == "COMP_CLASS"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "COMP_CLASS", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
				}
				unset($cMas);
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
		}
		foreach($_POST as $key => $post_val){
			$data[$sheetTitle][$j][$key] = $post_val;
		}
		$data[$sheetTitle][$j]["DATA_ID"] = $l_sess_data_id;
		$j++;
	}
	
	/* 配列の中身を確認 */
//			var_dump($data);

	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				$lr_data[$key] = $post_val;
			}
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			writeCompanySql($lr_data);
//					var_dump($lr_data);
			
		}/*ここまでエクセル各行ごとの処理*/
	}
}

function writeCompanySql($lr_data){
	/*----------------------------------------------------------------------------
	  データチェック
	  ----------------------------------------------------------------------------*/
		// m_company_masterクラスインスタンス作成
		require_once('../mdl/m_company_master.php');
		$lc_m_company = new m_company_master();
			
		// レコードセット
		$lc_m_company->setSaveRecord($lr_data);
		//print var_dump($lr_data)."\n";
		
		// チェック処理
		$lr_check_result = $lc_m_company->checkData();
		
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
				if(!$lc_m_company->insertRecord($l_sess_user_id)){
					$l_error_flag = 1;
				//	$l_error_message .= "データを登録できませんでした。";
				}
			// 更新
			}else if($lr_data['sql_type'] == "update"){
				if(!$lc_m_company->updateRecord($l_sess_user_id)){
					$l_error_flag = 1;
				//	$l_error_message .= "データを更新できませんでした。";
				}
			}
		}
		
		if($l_debug_mode==1){print("Step-データ保存");print "\n";print $lr_data['sql_type']."\n";}
		chkErrorflag($lr_data,$l_error_flag);

}

/*----------------------------------------------------------------------------
	  [グループ情報]データ格納、レコードごとにデータチェック、データ保存
  ----------------------------------------------------------------------------*/
function getGroupSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode		= getSqlColumn('GROUPS');
	$arrayCode[1]	= "COMPANY_NAME";
	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 1;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {
			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			if($arrayCode[$k] == "CLASSIFICATION_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "CLASSIFICATION_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
				}
				unset($cMas);
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
			if($k == 2) $k++;
		}
		foreach($_POST as $key => $post_val){
			$data[$sheetTitle][$j][$key] = $post_val;
		}
		$data[$sheetTitle][$j]["DATA_ID"] = $l_sess_data_id;
		$data[$sheetTitle][$j]["COMPANY_ID"] = "0";
		$j++;
	}
	

	/*---------------------------------
	 レコード１件毎の処理
	-----------------------------------*/

	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				if($key == "COMPANY_ID"){
					/*---------------
					   会社名から会社IDを検索して設定
					  ---------------*/
					// 会社MDL
					require_once('../mdl/m_company_master.php');
					$l_show_dtl_company_name	= $lr_data["COMPANY_NAME"];
					$l_show_dtl_data_id			= $lr_data["DATA_ID"];
					
					// 検索条件設定
					$lr_company_cond_dtl	= array("COMPANY_NAME = '".$l_show_dtl_company_name."'");
					$lr_company_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
					$lr_company_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
					
					// レコード取得
					$l_comp_dtl = new m_company_master('Y', $lr_company_cond_dtl);
					$lr_company_detail = $l_comp_dtl->getViewRecord();
					
					$lr_data["COMPANY_ID"] = $lr_company_detail[1]["COMPANY_ID"];
				}else{
					$lr_data[$key] = $post_val;
				}
			}
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			writeGroupSql($lr_data);
		}/*ここまでエクセル各行ごとの処理*/
	}
}
function writeGroupSql($lr_data){
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
	chkErrorflag($lr_data,$l_error_flag);
}

/*----------------------------------------------------------------------------
	  [ユーザ情報]データ格納、レコードごとにデータチェック、データ保存
  ----------------------------------------------------------------------------*/
function getUserSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義(できればDBから直接引っ張ってきたい)
	-----------------------------------*/
	$arrayCode		= getSqlColumn('USERS');
	$arrayCode[1]	= "COMPANY_NAME";
	$arrayCode[2]	= "GROUP_NAME";

	$aMas			= new m_authority_master();
	$arrayAuthClass = $aMas->getRecordAll($l_sess_data_id);


	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 1;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {

			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			if($arrayCode[$k] == "SEX"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "SEX", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
				}
				unset($cMas);
			}else if($arrayCode[$k] == "AUTHORITY_ID"){
				foreach($arrayAuthClass as $aArr){
					if($aArr["AUTHORITY_NAME"] == $cell->getCalculatedValue()){
						$data[$sheetTitle][$j][$arrayCode[$k]] = $aArr["AUTHORITY_ID"];
						break;
					}else if($cell->getCalculatedValue() == $aArr["AUTHORITY_ID"]){
						$data[$sheetTitle][$j][$arrayCode[$k]] = $aArr["AUTHORITY_ID"];
						break;
					}else{
						$data[$sheetTitle][$j][$arrayCode[$k]] = "28";
					}
				}
			}else if($arrayCode[$k] == "PAYMENT_DIVISION"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonName($l_sess_data_id, "PAYMENT_DIVISION", $cell->getCalculatedValue());
				if($mData != null){
					$data[$sheetTitle][$j][$arrayCode[$k]] = $mData;
				}else{
					$mData = $cMas->getCommonMasterAll($l_sess_data_id,"PAYMENT_DIVISION");
					foreach ($mData as $mArr){
						if($mArr["CODE_NAME"] == $cell->getCalculatedValue()){
							$data[$sheetTitle][$j][$arrayCode[$k]] = $mArr["CODE_NAME"];
							break;
						}else{
							$data[$sheetTitle][$j][$arrayCode[$k]] = "";
						}
					}
				}
				unset($cMas);
			}else if($arrayCode[$k] == "ALERT_PERMISSION_FLAG"){
				$cMas = new m_common_master();
				$mData = $cMas->getCommonMasterAll($l_sess_data_id,"ALERT_PERMISSION_FLAG");
				foreach ($mData as $mArr){
					if($mArr["CODE_NAME"] == $cell->getCalculatedValue() || $mArr["CODE_VALUE"] == $cell->getCalculatedValue()){
						$data[$sheetTitle][$j][$arrayCode[$k]] = $mArr["CODE_NAME"];
						break;
					}else{
						$data[$sheetTitle][$j][$arrayCode[$k]] = "N";
					}
				}
				unset($cMas);
			}else{
				$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			}
			$k++;
			while(	$arrayCode[$k] == "USER_ID" 
				||	$arrayCode[$k] == "ENCRYPTION_PASSWORD" 
				||	$arrayCode[$k] == "IDENTIFICATION_ID" 
				||	$arrayCode[$k] == "IDENTIFICATION_FLAG"){
				$k++;
			}
		}
		
		foreach($_POST as $key => $post_val){
			$data[$sheetTitle][$j][$key] = $post_val;
		}
		$data[$sheetTitle][$j]["DATA_ID"]		= $l_sess_data_id;
		$j++;
	}

	/*---------------------------------
	 レコード１件毎の処理
	-----------------------------------*/
	
	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				if($key == "COMPANY_ID"){
					/*---------------
					   会社ID設定
					  ---------------*/
					$lr_data["COMPANY_ID"] = getCompanyId($lr_data);

				}else if($key == "GROUP_ID"){

					/*---------------
					   グループID設定
					  ---------------*/
					// グループMDL
					require_once('../mdl/m_group_master.php');
					$l_show_dtl_group_name		= $lr_data["GROUP_NAME"];
					$l_show_dtl_data_id			= $lr_data["DATA_ID"];
					$l_show_dtl_company_id		= getCompanyId($lr_data);
					
					// 検索条件設定
					$lr_group_cond_dtl		= array("COMPANY_ID = '".$l_show_dtl_company_id."'"
											  ,"GROUP_NAME = '".$l_show_dtl_group_name."'");
					$lr_group_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
					$lr_group_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");

					// レコード取得
					$l_grp_dtl = new m_group_master('Y', $lr_group_cond_dtl);
					$lr_group_detail = $l_grp_dtl->getViewRecord();
					
					$lr_data["GROUP_ID"] = $lr_group_detail[1]["GROUP_ID"];
				}else{
					if ($key == "PASSWORD") {
						if ($_POST["hd_edit_password"] == 1 or $_POST['sql_type'] == "insert"){
							// パスワードは、変更にチェックがある場合または、新規登録の場合に暗号化して出力
							$lr_data["ENCRYPTION_PASSWORD"] = md5($post_val);
						}
					}else{
						if ($key != "ENCRYPTION_PASSWORD") {
							$lr_data[$key] = $post_val;
						}
					}
				}
				
			}
			
			//print_r($lr_data);
			
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			writeUserSql($lr_data,$l_debug_mode);
		}/*ここまでエクセル各行ごとの処理*/
	}
}
/*----------------------------------------------------------------------------
	会社ID設定
  ----------------------------------------------------------------------------*/
function getCompanyId($lr_data){
	// 会社MDL
	require_once('../mdl/m_company_master.php');
	$l_show_dtl_company_name	= $lr_data["COMPANY_NAME"];
	$l_show_dtl_data_id			= $lr_data["DATA_ID"];
	
	// 検索条件設定
	$lr_company_cond_dtl = array("COMPANY_NAME = '".$l_show_dtl_company_name."'");
	$lr_company_cond_dtl[]	= ("DATA_ID = '".$l_show_dtl_data_id."'");
	$lr_company_cond_dtl[]	= ("VALIDITY_FLAG = 'Y'");
	
	// レコード取得
	$l_comp_dtl = new m_company_master('Y', $lr_company_cond_dtl);
	$lr_company_detail = $l_comp_dtl->getViewRecord();
	return $lr_company_detail[1]["COMPANY_ID"];
//	$lr_data["COMPANY_ID"] = $lr_company_detail[1]["COMPANY_ID"];
}

function writeUserSql($lr_data,$l_debug_mode){
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_user_masterクラスインスタンス作成
	require_once('../mdl/m_user_master.php');
	$lc_m_user = new m_user_master();
		
	// レコードセット
	$lc_m_user->setSaveRecord($lr_data);
	//print var_dump($lr_data)."\n";
	
	// チェック処理
	$lr_check_result = $lc_m_user->checkData();
	
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
	
	if($l_debug_mode==1){print("Step-データチェック");print "\n"; print $l_error_message;}
	
/*----------------------------------------------------------------------------
  データ保存
  ----------------------------------------------------------------------------*/
	if($l_error_flag == 0){
		// 新規作成
		if($lr_data['sql_type'] == "insert"){
			if(!$lc_m_user->insertRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを登録できませんでした。";
			}
		// 更新
		}else if($lr_data['sql_type'] == "update"){
			if(!$lc_m_user->updateRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを更新できませんでした。";
			}
		}
	}
	
	if($l_debug_mode==1){print("Step-データ保存");print "\n";}
	chkErrorflag($lr_data,$l_error_flag);
}

function getWorkplaceSql($xlsSheet,$l_sess_user_id,$l_sess_data_id,$l_debug_mode){
	/*---------------------------------
	 連想配列keyの定義
	-----------------------------------*/
	$arrayCode		= getSqlColumn('BASES');
	$arrayCode[1]	= "COMPANY_NAME";
	
	/*---------------------------------
	 データを連想配列に格納
	-----------------------------------*/
	foreach ($xlsSheet->getRowIterator() as $row) {
		$xlsCell = $row->getCellIterator();
		$xlsCell->setIterateOnlyExistingCells(false);
		$k = 1;
		// 行のセルごとに読んでいく
		foreach ($xlsCell as $cell) {
			// 「シート名・行番号・セル番号」の連想配列にセル内のデータを格納
			$data[$sheetTitle][$j][$arrayCode[$k]] = $cell->getCalculatedValue();
			$k++;
			while($arrayCode[$k] == "BASE_ID"){
				$k++;
			}
		}
		foreach($_POST as $key => $post_val){
			$data[$sheetTitle][$j][$key] = $post_val;
		}
		$data[$sheetTitle][$j]["DATA_ID"] = $l_sess_data_id;
		$data[$sheetTitle][$j]["COMPANY_ID"] = "0";
		$j++;
	}
	/* 配列の中身を確認 */
//	var_dump($data);
	
	foreach($data as $query){
		foreach($query as $item){
			foreach($item as $key => $post_val){
				if($key == "COMPANY_ID"){
					/*---------------
					   会社ID設定
					  ---------------*/
					$lr_data["COMPANY_ID"] = getCompanyId($lr_data);
				}else{
					$lr_data[$key] = $post_val;
				}
			}
			if($l_debug_mode==1){print("Step-POST値の取得とチェック");print "\n";}
			writeWorkplaceSql($lr_data,$l_debug_mode);
		}/*ここまでエクセル各行ごとの処理*/
	}
}

function writeWorkplaceSql($lr_data,$l_debug_mode){
/*----------------------------------------------------------------------------
  データチェック
  ----------------------------------------------------------------------------*/
	// m_workplace_masterクラスインスタンス作成
	require_once('../mdl/m_workplace_master.php');
	$lc_m_base = new m_workplace_master();
		
	// レコードセット
	$lc_m_base->setSaveRecord($lr_data);
	//print var_dump($lr_data)."\n";
	
	// チェック処理
	$lr_check_result = $lc_m_base->checkData();
	
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
			if(!$lc_m_base->insertRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを登録できませんでした。";
			}
		// 更新
		}else if($lr_data['sql_type'] == "update"){
			if(!$lc_m_base->updateRecord($l_sess_user_id)){
				$l_error_flag = 1;
			//	$l_error_message .= "データを更新できませんでした。";
			}
		}
	}
	
	if($l_debug_mode==1){print("Step-データ保存");print "\n";}
	chkErrorflag($lr_data,$l_error_flag);
	
}

function chkErrorflag($lr_data,$l_error_flag){
/*----------------------------------------------------------------------------
  [各シート共通]終了処理★ここにexcel消去の処理を書く
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