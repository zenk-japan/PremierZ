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

	require_once('../lib/SpotValue.php');
/******************************************************************************
	ファイル名：IndividualStaticValue
	処理概要  ：ID/PASSWORD/URL/PORT等を記述
 ******************************************************************************/
/* =============================================================================
	DB系
   ============================================================================= */
	define("SCHEMA_NAME",				$schema_name);						// DB_SERVER(SCHEMA)
	define("DB_SERVER",					$db_server_addr);					// internet
	define("DB_USER",					$db_server_user);					// USER
	define("DB_PASS",					$db_server_pass);					// PASS
	define("SYSTEM_USER",				"-1");								// SYSTEM_USER_CODE
	
/* =============================================================================
	URL
   ============================================================================= */
	define("SITE_URL",					"http://".DB_SERVER."/");			// URL
	define("MANUAL_URL",				"http://".DB_SERVER."/manual/");	// モバイルマニュアル
	define("SSL_SITE_URL",				"https://".DB_SERVER."/");			// SSL-URL
	define("URI_SCHEME",				"https");							// URIスキーム（SSLを使わない場合はhttpに変更する）
	define("BASE_URL",					URI_SCHEME."://".$base_url);		// URLの基底ディレクトリ
	
/* =============================================================================
	ポート設定
   ============================================================================= */
	define("PORT_HTTP",					"80");
	define("PORT_HTTPS",				"443");
	
?>
