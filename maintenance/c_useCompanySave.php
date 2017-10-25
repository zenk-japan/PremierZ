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
 ファイル名：c_useCompanySave.php
 処理概要  ：利用会社保存処理
 POST受領値：
             nm_token_code              トークン(必須)
             data_record[1]             以下の内容がレコード配列の要素としてPOSTされる
               data_id                  DATA_ID
               comp_code                利用会社コード
               comp_name                利用会社名
               comp_remarks             備考
               adminusr_code            管理者ユーザーコード
               smtp_server              SMTPサーバ
               smtp_port                SMTPサーバのポート番号
               smtp_account             SMTPサーバのアカウント
               smtp_pass                SMTPサーバのパスワード
               mail_manager             作業取り纏め用アドレス
               mail_report              勤怠報告用アドレス
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	//print_r($_POST);
	//print "<br>";
	//session_start();
	//print_r($_SESSION);
	//return;
// ==================================
// 前処理
// ==================================
	require_once('../lib/CommonStaticValue.php');

// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_txt_rts			= "\n";									// テキストの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_get_key2			= "USER_ID";							// 認証で取得するキー項目2
	$l_user_id			= "";
	$l_record_item		= "data_record";						// POST引数の連勝配列の中でデータを格納している項目
	$l_mode				= "";									// 起動モード(INSorUPD)
	$l_errflg			= 0;									// エラーフラグ
	$l_errmess			= "";									// エラーメッセージ
	
// ==================================
// 例外定義
// ==================================
	function my_exception_useCompSave(Exception $e){
		echo "例外が発生しました。".$l_txt_rts;
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_useCompSave');
	
// ==================================
// セッション確認
// ==================================
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['nm_token_code'];
	if(is_null($l_post_token)){
		throw new Exception("不正なアクセスです。");
	}
	
	require_once('../maintenance/c_sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		throw new Exception("不正なアクセスです。");
	}
	if($l_post_token != $l_sess_token){
		throw new Exception("不正なアクセスです。");
	}
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem($l_get_key2);
	if(is_null($l_user_id)){
		throw new Exception("不正なアクセスです。");
	}
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
// ==================================
// POST項目
// ==================================
	// データレコードを採取
	$lr_data_rec = $_POST[$l_record_item];
	
// ==================================
// 保存処理
// ==================================
	// 既存データを読み込む
	require_once('../mdl/m_use_company.php');
	$lc_muc = new m_use_company();
	
	// POSTされたDATA_IDと一致するレコードがあった場合は、内容に変更が有った場合のみUPDATEする
	foreach($lr_data_rec as $l_key => $lr_valrec){
		$lr_dtlrec = "";
		// DATA_IDをキーとして既存レコードを取得する
		$lr_where = array("DATA_ID = ".$lr_valrec["data_id"]);
		$lc_muc->setWhereArray($lr_where);
			
		// クエリ
		$lc_muc->queryDBRecord();
		// レコード取得
		$lr_dtlrec = $lc_muc->getViewRecord();
		
		//print_r($lr_dtlrec);
		
		if(count($lr_dtlrec) > 0){
			// 既存レコードありの場合
			// 変更点が有るか確認
			
			if(
				$lr_dtlrec[1]["DATA_ID"]			== $lr_valrec["data_id"] &&
				$lr_dtlrec[1]["USE_COMPANY_CODE"]	== $lr_valrec["comp_code"] &&
				$lr_dtlrec[1]["USE_COMPANY_NAME"]	== $lr_valrec["comp_name"] &&
				$lr_dtlrec[1]["REMARKS"]			== $lr_valrec["comp_remarks"]
			){
				// 変更点が無い場合
				//print "No Change";
				// 何もしない
			}else{
				// 変更点が有る場合
				// UPDATE処理
				//print "Change";
				$lr_update_rec = array();
				$lr_update_rec["DATA_ID"]			= $lr_valrec["data_id"];
				$lr_update_rec["USE_COMPANY_CODE"]	= $lr_valrec["comp_code"];
				$lr_update_rec["USE_COMPANY_NAME"]	= $lr_valrec["comp_name"];
				$lr_update_rec["REMARKS"]			= $lr_valrec["comp_remarks"];
				
				$lc_mucupd = new m_use_company();
				$lc_mucupd->setSaveRecord($lr_update_rec);
				if(!$lc_mucupd->updateRecord($l_user_id)){
					print "更新処理に失敗しました。DATA_ID = ".$lr_valrec["data_id"];
					return;
				}
			}
		}else{
			// 既存レコード無しの場合
			// INSERT処理
			// DBサーバ接続
			require_once('../lib/ConnectDBSub.php');
			$l_db_conn = getDBConnection(DB_SERVER, DB_USER, DB_PASS, SCHEMA_NAME);
			if ($l_db_conn->connect_errno > 0) {
			    print "DBサーバに接続できませんでした。DATA_ID = ".$lr_valrec["data_id"];
				return;
			}
			
			try{
				// XMLファイル読み込み
				$l_contents = file_get_contents( "./setup/datasetup.xml" );
				$l_xml_data = simplexml_load_string( $l_contents, 'SimpleXMLElement', LIBXML_NOCDATA );

				// XMLファイルからファイルのディレクトリを取得
				$l_sql_file_dir = $l_xml_data->sql_directory;
				
				// XMLファイルに記述されたSQLファイルを読み込み実行する
				foreach($l_xml_data->sql_file->data_node as $l_data_node){
					$l_sql = file_get_contents("./setup/".$l_sql_file_dir."/".$l_data_node->insert);
					// DATA_IDを置換
					$l_sql = preg_replace("/%%data_id%%/", $lr_valrec["data_id"], $l_sql);
					
					// 利用会社は会社コードと会社名も置換
					if ($l_data_node["name"] == "USE_COMPANY"){
						$l_sql = preg_replace("/%%comp_code%%/",		$lr_valrec["comp_code"],	$l_sql);
						$l_sql = preg_replace("/%%comp_name%%/",		$lr_valrec["comp_name"],	$l_sql);
						$l_sql = preg_replace("/%%ucomp_remarks%%/",	$lr_valrec["comp_remarks"],	$l_sql);
					}
					
					// 共通マスタのメール設定周りを置換
					if ($l_data_node["name"] == "COMMON_MASTER"){
						$l_sql = preg_replace("/%%MAIL_ADDR1%%/",		$lr_valrec["mail_manager"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_ADDR2%%/",		$lr_valrec["mail_report"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_ADDR3%%/",		'',							$l_sql);
						$l_sql = preg_replace("/%%MAIL_HOST%%/",		$lr_valrec["smtp_server"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_KEY%%/",			$lr_valrec["smtp_pass"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_PORT%%/",		$lr_valrec["smtp_port"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_USERNAME%%/",	$lr_valrec["smtp_account"],	$l_sql);
						$l_sql = preg_replace("/%%MAIL_SECURE%%/",		$lr_valrec["smtp_secure"],	$l_sql);
					}
					
					// 管理者ユーザーは管理者権限を取得して置換
					if ($l_data_node["name"] == "USERS"){
						// 管理者のAUTHORITY_IDを取得
						$l_auth_sql = "select AUTHORITY_ID from AUTHORITY where AUTHORITY_CODE = 'ADMIN' and DATA_ID = ".$lc_muc->getMysqlEscapedValue($lr_valrec["data_id"]).";";
						$l_result = getRowStart1($l_db_conn, $l_auth_sql);
						$l_auth_id = $l_result[1]['AUTHORITY_ID'];
						if (!$l_auth_id){
							$l_message = $l_table_node["name"]."管理者権限ID取得に失敗しました。";
							$l_db_conn->close();
							print $l_message;
							return;
						}
						
						// 登録用のSQLを置換
						$l_sql = preg_replace("/%%admin_auth_id%%/",	$l_auth_id, $l_sql);
						$l_sql = preg_replace("/%%admin_name%%/",		$lr_valrec["adminusr_code"], $l_sql);
						
					}
					
					// SQL実行
					$l_result = $l_db_conn->query($l_sql);
					if (!$l_result){
					    $l_message = $l_data_node["name"]."データ作成に失敗しました。";
						$l_db_conn->close();
					    print $l_message;
					    exit;
					}
				}
				
			}catch(Exception $e){
				$l_message = "初期データセットアップエラー：". $e->getMessage(). "\n";
				print $l_message;
				exit;
			}
			
			$l_db_conn->close($l_db_conn);
		}
		
	}
	
?>
