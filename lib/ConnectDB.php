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

require_once('../lib/CommonStaticValue.php');
require_once('../lib/ConnectDBSub.php');
	function getConnection(){
		$dbh = null;
		
		$dsn		=	'mysql:dbname='.SCHEMA_NAME.';host='.DB_SERVER;		//DB名(schema),接続先[zenk18]
		$user		=	DB_USER;											//ユーザ
		$password	=	DB_PASS;											//パスワード
		
		try {
			$dbh = new PDO($dsn, $user, $password);
			// 文字コード utf8
			$dbh->query('SET NAMES utf8');
			// カラム名を小文字で取得する
			$dbh->setAttribute(PDO::ATTR_CASE, PDO_CASE_LOWER);
			// 自動コミットをOff
			$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			// エラー時にExceptionをthrowさせる
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "<hr>正常に接続できました。<hr>";				//Debug
		}catch (PDOException $e) {
			echo "接続に失敗しました。<br>";
			echo 'Connection failed: ' . $e->getMessage();
			echo '       error code: ' . $e->getCode();
			exit;
		}
		
		return $dbh;
	}
		
	function getMysqlConnection(){
		// DBサーバ接続
		$l_db_conn = getDBConnection(DB_SERVER, DB_USER, DB_PASS, SCHEMA_NAME);
		if ($l_db_conn->connect_errno > 0) {
		    print "DBサーバに接続できませんでした。<br>";
		    exit;
		}
		
		return $l_db_conn;
	}
	
	function getRowWithRownum($p_mdb, $p_sql){
		$l_ar_retrec = getRowStart1($p_mdb, $p_sql);
		
		return $l_ar_retrec;
	}
?>
