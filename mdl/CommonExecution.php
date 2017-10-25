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

class CommonExecution {
	private $l_return;						// リターンコード
	private $l_debug_mode;
	
	function __construct(){
		$this->l_debug_mode = 0;
		// この値を1にすると、各関数は入力されたSQLをechoし、RETURN_ERRORを返して終了します。
	}

	// SQL実行（メッセージ表示有）
	function CommonSQL($sql,$sqlstate = "",$errmessage =""){
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $sql;
			return RETURN_ERROR;
		}
		
		try {
			if ($sql == null) {
			} else {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// トランザクションの開始
				//$mdb->beginTransaction();
				$mdb->autocommit(FALSE);
				
				// クエリー実行
				$stmt = $mdb->query($sql);
				
				// コミット
				$mdb->commit();
				echo "データを更新しました。";
			}
		} catch (Exception $e) {
			$mdb->rollBack();
			$sqlmessage = $e->getMessage();
			
			// 特定のSQLSTATEが返ってきた場合は対応したエラーメッセージを表示させる。
			if(mb_strpos($sqlmessage, $sqlstate) == 0 && $errmessage !=""){
				print $errmessage."\n";
			}else {
				echo "\n更新処理に失敗しました。\n". $e->getMessage();
			}
			$l_return = RETURN_ERROR;
		}
		
		// DB切断
		$mdb = null;
		
		return $l_return;
	}
	
	// SQL実行（メッセージ表示無）
	function CommonSilentSQL($sql){
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $sql;
			return RETURN_ERROR;
		}
		
		try {
			if ($sql == null) {
			} else {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// トランザクションの開始
				//$mdb->beginTransaction();
				$mdb->autocommit(FALSE);
				
				// クエリー実行
				$stmt = $mdb->query($sql);
				
				// コミット
				$mdb->commit();
			}
		} catch (Exception $e) {
			$mdb->rollBack();
			print $e->getMessage()."\n";
			$l_return = RETURN_ERROR;
		}
		
		// DB切断
		$mdb = null;

		return $l_return;
	}
	
	// SQL実行（メール送信）
	function CommonSend($sql){
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $sql;
			return RETURN_ERROR;
		}
		
		try {
			if ($sql == null) {
			} else {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// トランザクションの開始
				//$mdb->beginTransaction();
				$mdb->autocommit(FALSE);
				
				// クエリー実行
				$stmt = $mdb->query($sql);
				
				// コミット
				$mdb->commit();
				echo "送信しました。";
			}
		} catch (Exception $e) {
			$mdb->rollBack();
			echo "\n送信処理に失敗しました。\n". $e->getMessage();
			$l_return = RETURN_ERROR;
		}
		
		// DB切断
		$mdb = null;

		return $l_return;
	}

	// SQL実行（メッセージ表示有）複数SQL
	function executeSQL($pr_sql){
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $pr_sql;
			return RETURN_ERROR;
		}
		
		try {
			// DB接続
			require_once('../lib/ConnectDB.php');
			//$mdb = getConnection($dbh);
			$mdb = getMysqlConnection();
		}catch(Exception $e){
			echo "\nデータベースに接続できませんでした。\n". $e->getMessage();
			return RETURN_ERROR;
		}
		
		try {
			// トランザクションの開始
			//$mdb->beginTransaction();
			$mdb->autocommit(FALSE);
			
			if(is_array($pr_sql)){
				// 引数のSQLが配列の場合
				foreach($pr_sql as $value){
					// クエリー実行
					$stmt = $mdb->query($value);
				}
			}else{
				// 引数のSQLが配列以外の場合
				// クエリー実行
				$stmt = $mdb->query($pr_sql);
			}
			// コミット
			$mdb->commit();
			echo "データを更新しました。\n";
			
		} catch (Exception $e) {
			$mdb->rollBack();
			echo "\n更新処理に失敗しました。\n". $e->getMessage();
			return RETURN_ERROR;
		}
		
		// DB切断
		$mdb = null;
		
		return $l_return;
	}
	
	function CommonMobileSQL($sql){
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $sql;
			return RETURN_ERROR;
		}
		
		try {
			if ($sql == null) {
			} else {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// トランザクションの開始
				//$mdb->beginTransaction();
				$mdb->autocommit(FALSE);
				
				// クエリー実行
				$stmt = $mdb->query($sql);
				
				// コミット
				$mdb->commit();
				$l_msg = "データを更新しました。";
			}
		} catch (Exception $e) {
			$mdb->rollBack();
			$l_msg = "\n更新処理に失敗しました。\n". $e->getMessage();
			$l_return = RETURN_ERROR;
		}
		
		// DB切断
		$mdb = null;
		
		return array("RETERN_CODE" => $l_return, "RETERN_MSG" => $l_msg);
	}
	
/*============================================================================
	SQL実行（メッセージ表示無）
	処理概要：SQLを実行し、実行結果を返す
			$sql							実行するSQL
			$pr_data						データ配列
			$p_login_user_id				ユーザーID(更新者)
  ============================================================================*/
	function execSilentSQL($sql){
		
		$l_return = RETURN_NOMAL;
		
		// デバッグモードはSQLを表示して終了
		if ($this->l_debug_mode === 1){
			echo $sql;
			return RETURN_ERROR;
		}
		
		try {
			if ($sql == null) {
			} else {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// トランザクションの開始
				//$mdb->beginTransaction();
				$mdb->autocommit(FALSE);
				
				// クエリー実行
				$stmt = $mdb->query($sql);
				
				// コミット
				$mdb->commit();
			}
		} catch (Exception $e) {
//			$mdb->rollBack();
//			print $e->getMessage()."\n";
//			$l_return = RETURN_ERROR;

			$mdb->rollBack();
//			$sqlmessage = $e->getMessage();
//			
//			// 特定のSQLSTATEが返ってきた場合は対応したエラーメッセージを表示させる。
//			if(mb_strpos($sqlmessage, $sqlstate) == 0 && $errmessage !=""){
//				print $errmessage."\n";
//			}else {
//				echo "\n更新処理に失敗しました。\n". $e->getMessage();
//			}
			$l_return = $e->getMessage();
		}
		
		// DB切断
		$mdb = null;
		
		return $l_return;
	}
	
}
?>
