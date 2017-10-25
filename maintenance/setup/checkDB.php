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
// 処理概要：DBチェック
//           指定されたパラメータでDBに接続できるか確認する
// *****************************************************************************
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	$l_message		= "0";
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		var_dump($_POST);
	}
	
	$l_hostname	= $_POST["db_host"];
	$l_username	= $_POST["db_user"];
	$l_userpass	= $_POST["db_pass"];
	$l_dbname	= $_POST["db_name"];
	
	//exit;
	
	require_once('../../lib/ConnectDBSub.php');
	$link = getDBConnection($l_hostname, $l_username, $l_userpass, $l_dbname);
	if ($link->connect_errno > 0) {
	    $l_message = "DBサーバに接続できませんでした。";
	    print $l_message;
	    exit;
	}
	
	$link->close($link);
	
	print $l_message;
	exit;
?>