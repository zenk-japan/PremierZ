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
require_once('../lib/CommonFunctions.php');
require_once('../mdl/ModelCommon.php');
	class Estimates_v extends ModelCommon{
// =============================================================================
// コンストラクタ
// 引数:
// =============================================================================
		function __construct(){
			// 継承元のコンストラクタを起動
			ModelCommon::__construct(ESTIMATES_V);						// ビュー名を指定
		}
		
		// 見積コード取得
		function estimatesGet($search_sql,&$w_estimateno){
			
			try {
				// DB接続
				require_once('../lib/ConnectDB.php');
				//$mdb = getConnection($dbh);
				$mdb = getMysqlConnection();
				
				// SELECT文を実行
				$w_estimateno = getRowWithRownum($mdb, $search_sql);
				
				/*
				$rcnt = 0;
				//foreach ($mdb->query($search_sql) as $key => $row) {
				$l_result = $mdb->query($search_sql);
				while ($row = $l_result->fetch_assoc()) {
					$rcnt = $rcnt + 1;
					$w_estimateno[$rcnt] = $row;
				}
				$l_result->close();
				*/
			} catch (Exception $e) {
				echo "\n接続に失敗しました。". $e->getMessage();
			}
			
			// DB切断
			$mdb = null;
		}
	}
?>
