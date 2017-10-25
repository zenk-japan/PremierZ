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
	処理概要：DBセットアップ
			指定されたパラメータでDBの初期セットアップを行う
 ******************************************************************************/
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	$l_message		= "0";
	$l_debug_message = "";
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		$l_debug_message = print_r($_POST);
	}
	
	$l_hostname			= $_POST["db_host"];
	$l_username			= $_POST["db_user"];
	$l_userpass			= $_POST["db_pass"];
	$l_dbname			= $_POST["db_name"];
	$l_data_id			= $_POST["data_id"];
	$l_comp_code		= $_POST["comp_code"];
	$l_comp_name		= $_POST["comp_name"];
	$l_adminusr_code	= $_POST["adminusr_code"];
	$l_smtp_server		= $_POST["smtp_server"];
	$l_smtp_port		= $_POST["smtp_port"];
	$l_smtp_account		= $_POST["smtp_account"];
	$l_smtp_pass		= $_POST["smtp_pass"];
	$l_smtp_secure		= $_POST["smtp_secure"];
	$l_mail_manager		= $_POST["mail_manager"];
	$l_mail_report		= $_POST["mail_report"];
	
/*-----------------------------------------------------------------------------
	AUTHORITY_ID取得処理
 -----------------------------------------------------------------------------*/
	function getAuthorityID($p_link, $p_data_id, $p_admin_type){
		$l_result_value = "";
		
		$l_auth_sql = "select AUTHORITY_ID from AUTHORITY where AUTHORITY_CODE = '".$p_link->real_escape_string($p_admin_type)."' and DATA_ID = ".$p_link->real_escape_string($p_data_id).";";
		
		$l_result = getRowStart1($p_link, $l_auth_sql);
		$l_auth_id = $l_result[1]['AUTHORITY_ID'];
		
		if (!$l_auth_id){
			$l_message = "AUTHORITY_ID取得に失敗しました。";
			$p_link->close();
			print $l_message;
			exit;
		}
		
		return $l_auth_id;
	}
	
/*-----------------------------------------------------------------------------
	SQL実行処理
 -----------------------------------------------------------------------------*/
	function execSQL($p_link, $p_sql, $p_proc_name){
		$l_result = $p_link->query($p_sql);
		if (!$l_result){
		    $l_message = $p_proc_name.":SQL実行に失敗しました。\n";
		    $l_message .= $p_sql;
			$p_link->close();
		    print $l_message;
		    exit;
		}
	}
	
/*-----------------------------------------------------------------------------
	本体処理
 -----------------------------------------------------------------------------*/
	// DBサーバ接続
	require_once('../../lib/ConnectDBSub.php');
	$l_db_conn = getDBConnection($l_hostname, $l_username, $l_userpass, $l_dbname);
	if ($l_db_conn->connect_errno > 0) {
	    $l_message = "DBサーバに接続できませんでした。";
	    print $l_message;
	    exit;
	}

	// テーブル作成
	try{
		// XMLファイル読み込み
		$l_contents = file_get_contents( "./createtable.xml" );
		$l_xml_data = simplexml_load_string( $l_contents, 'SimpleXMLElement', LIBXML_NOCDATA );
		
		// XMLファイルからファイルのディレクトリを取得
		$l_sql_file_dir = $l_xml_data->sql_directory;
		
		// XMLファイルに記述されたSQLファイルを読み込み実行する
		foreach($l_xml_data->sql_file->table_node as $l_table_node){
			// DROP
			$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_table_node->drop);
			execSQL($l_db_conn, $l_sql, $l_table_node["name"]);
			
			// CREATE
			$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_table_node->create);
			execSQL($l_db_conn, $l_sql, $l_table_node["name"]);
		}
		
	}catch(Exception $e){
		$l_message = "テーブル作成エラー：". $e->getMessage(). "\n";
		print $l_message;
		exit;
	}

	// ビュー作成
	try{
		// XMLファイル読み込み
		$l_contents = file_get_contents( "./createview.xml" );
		$l_xml_data = simplexml_load_string( $l_contents, 'SimpleXMLElement', LIBXML_NOCDATA );
		
		// XMLファイルからファイルのディレクトリを取得
		$l_sql_file_dir = $l_xml_data->sql_directory;
		
		// XMLファイルに記述されたSQLファイルを読み込み実行する
		foreach($l_xml_data->sql_file->view_node as $l_view_node){
			$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_view_node->create);
			
			// SQL実行
			execSQL($l_db_conn, $l_sql, $l_view_node["name"]);
		}
		
	}catch(Exception $e){
		$l_message = "ビュー作成エラー：". $e->getMessage(). "\n";
		print $l_message;
		exit;
	}
	
	
	// 初期データセットアップ
	try{
		/*--------------------------------
			システム管理者セットアップ
		--------------------------------*/
		// システム管理者のDATA_ID
		$l_sysadmin_data_id = 0;
		
		// XMLファイル読み込み
		$l_contents = file_get_contents( "./sysdatasetup.xml" );
		$l_xml_data = simplexml_load_string( $l_contents, 'SimpleXMLElement', LIBXML_NOCDATA );

		// XMLファイルからファイルのディレクトリを取得
		$l_sql_file_dir = $l_xml_data->sql_directory;
		
		// XMLファイルに記述されたSQLファイルを読み込み実行する
		foreach($l_xml_data->sql_file->data_node as $l_data_node){
			$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_data_node->insert);
			
			if ($l_data_node["name"] == "SYSADMIN"){
				// 管理者のAUTHORITY_IDを取得
				$l_auth_id = getAuthorityID($l_db_conn, $l_sysadmin_data_id, "SYSADMIN");
				
				// 登録用のSQLを置換
				$l_sql = preg_replace("/%%sysadmin_auth_id%%/", $l_auth_id, $l_sql);
			}
		
			// SQL実行
			execSQL($l_db_conn, $l_sql, $l_data_node["name"]);
		}
		
		/*--------------------------------
			セットアップ用XMLファイル読み込み
		--------------------------------*/
		$l_contents = file_get_contents( "./datasetup.xml" );
		$l_xml_data = simplexml_load_string( $l_contents, 'SimpleXMLElement', LIBXML_NOCDATA );
		
		/*--------------------------------
			システム管理者用初期データ
			セットアップ
		--------------------------------*/
		// XMLファイルからファイルのディレクトリを取得
		$l_sql_file_dir = $l_xml_data->sql_directory;
		
		// XMLファイルに記述されたSQLファイルを読み込み実行する
		foreach($l_xml_data->sql_file->data_node as $l_data_node){
			// システム管理者用はユーザー、権限、メッセージテンプレートを登録しない
			if ($l_data_node["name"] != "USERS" && $l_data_node["name"] != "AUTHORITY" && $l_data_node["name"] != "MESSAGE_TEMPLATE"){
				$l_dummy = "dummy";						// 各項目はダミーを使用する
				$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_data_node->insert);
				// DATA_IDを置換
				$l_sql = preg_replace("/%%data_id%%/", $l_sysadmin_data_id, $l_sql);
				
				// 利用会社は会社コードと会社名も置換
				if ($l_data_node["name"] == "USE_COMPANY"){
					$l_sql = preg_replace("/%%comp_code%%/", $l_dummy, $l_sql);
					$l_sql = preg_replace("/%%comp_name%%/", $l_dummy, $l_sql);
					$l_sql = preg_replace("/%%ucomp_remarks%%/", 'システム管理者用です。削除しないで下さい。', $l_sql);
				}
				
				// 共通マスタのメール設定周りを置換
				if ($l_data_node["name"] == "COMMON_MASTER"){
					$l_sql = preg_replace("/%%MAIL_ADDR1%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_ADDR2%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_ADDR3%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_HOST%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_KEY%%/",			$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_PORT%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_SECURE%%/",		$l_dummy, $l_sql);
					$l_sql = preg_replace("/%%MAIL_USERNAME%%/",	$l_dummy, $l_sql);
				}
			
				// SQL実行
				execSQL($l_db_conn, $l_sql, $l_data_node["name"]);
			}
		}
		/*--------------------------------
			その他初期データセットアップ
		--------------------------------*/
		// 配列ポインタを先頭に戻す
		reset($l_xml_data->sql_file->data_node);
		
		// XMLファイルからファイルのディレクトリを取得
		$l_sql_file_dir = $l_xml_data->sql_directory;
		
		// XMLファイルに記述されたSQLファイルを読み込み実行する
		foreach($l_xml_data->sql_file->data_node as $l_data_node){
			$l_sql = file_get_contents("./".$l_sql_file_dir."/".$l_data_node->insert);
			// DATA_IDを置換
			$l_sql = preg_replace("/%%data_id%%/", $l_data_id, $l_sql);
			
			// 利用会社は会社コードと会社名も置換
			if ($l_data_node["name"] == "USE_COMPANY"){
				$l_sql = preg_replace("/%%comp_code%%/", $l_comp_code, $l_sql);
				$l_sql = preg_replace("/%%comp_name%%/", $l_comp_name, $l_sql);
				$l_sql = preg_replace("/%%ucomp_remarks%%/", '', $l_sql);
			}
			
			// 共通マスタのメール設定周りを置換
			if ($l_data_node["name"] == "COMMON_MASTER"){
				$l_sql = preg_replace("/%%MAIL_ADDR1%%/",		$l_mail_manager,	$l_sql);
				$l_sql = preg_replace("/%%MAIL_ADDR2%%/",		$l_mail_report,		$l_sql);
				$l_sql = preg_replace("/%%MAIL_ADDR3%%/",		$l_mail_manager,	$l_sql);
				$l_sql = preg_replace("/%%MAIL_HOST%%/",		$l_smtp_server,		$l_sql);
				$l_sql = preg_replace("/%%MAIL_KEY%%/",			$l_smtp_pass,		$l_sql);
				$l_sql = preg_replace("/%%MAIL_PORT%%/",		$l_smtp_port,		$l_sql);
				$l_sql = preg_replace("/%%MAIL_SECURE%%/",		$l_smtp_secure,		$l_sql);
				$l_sql = preg_replace("/%%MAIL_USERNAME%%/",	$l_smtp_account,	$l_sql);
			}
			
			// 管理者ユーザーは管理者権限を取得して置換
			if ($l_data_node["name"] == "USERS"){
				// 管理者のAUTHORITY_IDを取得
				$l_auth_id = getAuthorityID($l_db_conn, $l_data_id, "ADMIN");
				
				// 登録用のSQLを置換
				$l_sql = preg_replace("/%%admin_auth_id%%/", $l_auth_id, $l_sql);
				$l_sql = preg_replace("/%%admin_name%%/", $l_adminusr_code, $l_sql);
				
			}
			
			// SQL実行
			execSQL($l_db_conn, $l_sql, $l_data_node["name"]);
		}
		
	}catch(Exception $e){
		$l_message = "初期データセットアップエラー：". $e->getMessage(). "\n";
		print $l_message;
		exit;
	}
	
	$l_db_conn->close($l_db_conn);
	
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "debug mode:\n".$l_debug_message;
	}else{
		print $l_message;
	}
	exit;
?>