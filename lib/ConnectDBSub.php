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

function getDBConnection($p_dbserver, $p_dbuser, $p_dbpass, $p_schema){
	// DBサーバ接続
	//$l_db_conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, SCHEMA_NAME);
	$l_db_conn = new mysqli($p_dbserver, $p_dbuser, $p_dbpass, $p_schema);
	
	// クエリの文字コード変換
	$l_db_conn->query("SET NAMES utf8");
	
	return $l_db_conn;
}

function getRowStart1($p_mdb, $p_sql){
	$l_ar_retrec = "";
	$l_rcnt = 0;
	
	// SELECT文を実行
	$l_result = $p_mdb->query($p_sql);
	while ($l_row = $l_result->fetch_assoc()) {
		$l_rcnt++;
		$l_ar_retrec[$l_rcnt] = $l_row;
	}
	$l_result->close();
	
	// 旧PDOと反応を合わせる為、0件の場合はNULLにする
	if ($l_ar_retrec == ""){
		$l_ar_retrec = NULL;
	}
	
	return $l_ar_retrec;
}
?>
