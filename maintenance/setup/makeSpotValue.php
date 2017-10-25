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
// *****************************************************************************
// 処理概要：Spot値作成
//           指定されたパラメータでSpot値ファイルの作成を行う
// *****************************************************************************
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	$l_message		= "0";
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		var_dump($_POST);
	}
	
	$l_hostname		= $_POST["db_host"];
	$l_username		= $_POST["db_user"];
	$l_userpass		= $_POST["db_pass"];
	$l_dbname		= $_POST["db_name"];
	$l_data_id		= $_POST["data_id"];
	$l_comp_code	= $_POST["comp_code"];
	$l_comp_name	= $_POST["comp_name"];
	
	$l_lib_dir			= "../../lib/";
	$l_org_file_name	= "SpotValueOrg.php";
	$l_new_file_name	= "SpotValue.php";
	
	// オリジナルファイル読み込み
	$l_org_value = file_get_contents($l_lib_dir. $l_org_file_name);
	
	// セットアップ画面入力値から値を置換
	$l_new_value = preg_replace("/%%schema_name%%/",	$l_dbname,		$l_org_value);
	$l_new_value = preg_replace("/%%db_server_addr%%/",	$l_hostname,	$l_new_value);
	$l_new_value = preg_replace("/%%db_server_user%%/",	$l_username,	$l_new_value);
	$l_new_value = preg_replace("/%%db_server_pass%%/",	$l_userpass,	$l_new_value);
	
	// BASE_URL作成
	$l_base_url = "";
	$l_base_dir = "";
	$lr_split_uri = explode("/", $_SERVER['REQUEST_URI']);
	if (count($lr_split_uri) > 2){
		// REQUEST_URIには公開ディレクトリをルートとしたファイル名が記載される
		// 最後がファイル名で、その1つ前は固定のディレクトリ名、その前がmaintenanceディレクトリとなる為、
		// 最後から4つめまでが規定ディレクトリとなる
		for($l_cnt = 0; $l_cnt < count($lr_split_uri) - 3; $l_cnt++){
			$l_base_dir .= $lr_split_uri[$l_cnt]."/";
		}
	}else{
		// 公開ディレクトリにサブディレクトリを作成せずにセットアップされている場合
		$l_base_dir = "/";
	}
	
	$l_base_url .= $_SERVER['HTTP_HOST'];
	$l_base_url .= $l_base_dir;
	
	// BASE_URL置換
	$l_new_value = preg_replace("/%%base_url%%/",	$l_base_url,	$l_new_value);
	
	// SpotValue.phpを作成
	try{
		$l_fp = fopen($l_lib_dir. $l_new_file_name, "w");
		$l_res_fwrite = fwrite($l_fp, $l_new_value);
		fclose($l_fp);
		
		if ($l_res_fwrite === false){
			$l_message = "接続設定を書き込めませんでした。";
			print $l_message;
			exit;
		}
	}catch(Exception $e){
		$l_message = "接続設定書き込みエラー：". $e->getMessage(). "\n";
		print $l_message;
		exit;
	}
	
	print $l_message;
	exit;
?>