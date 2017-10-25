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
// *****************************************************************************
// 処理概要：ログインチェック
//           ログイン中の作業者を対象に一定時間経過後にログアウト処理を行う
// *****************************************************************************
	$dbobj					= NULL;							// DBオブジェクト
	$l_dbrec				= NULL;							// DBレコード
	$Objusess				= NULL;							// ログアウト対象セッション
	$p_table_name			= "SESSIONS";					// UPDATE対象Table
	$l_update				= NULL;							// 更新部分
	$l_condition			= NULL;							// 条件部分
	$sql					= NULL;							// 実行SQL
	$l_log_buff				= NULL;							// ログバッファ
	
	// DB接続クラス呼び出し
	require_once('../mdl/Login_check_v.php');
	$dbobj = new Login_check_v();
	
	// レコード取得
	$l_dbrec = $dbobj->getRecord();
	
	// レコードループ
	if(count($l_dbrec)>0){
		foreach($l_dbrec as $key){
			if(is_null($Objusess)){
				$Objusess	.= "'".$key[SESSION_ID]."'";
			} else {
				$Objusess	.= ",'".$key[SESSION_ID]."'";
			}
			
			$l_log_buff		=	"作業者：".$key[NAME];
			echo $l_log_buff." ";
		}
		
		// SQLの組立て
		// セッション管理テーブル更新
		$l_update		.= " UPDATE ".SCHEMA_NAME.".".$p_table_name ." ";
		$l_update		.= " SET"." ";
		$l_update		.= "         SESSID              = null"." ";
		$l_update		.= "        ,SESS_TOKEN          = null"." ";
		$l_update		.= "        ,LOGIN_FLAG          = 'N'"." ";
		$l_update		.= "        ,LAST_UPDATE_DATET   = now() ";
		$l_update		.= "        ,LAST_UPDATE_USER_ID = '".SYSTEM_USER."' ";
		
		// 条件部分
		$l_condition	.= " WHERE  SESSION_ID IN (".$Objusess.") ";
		
		$sql			= $l_update.$l_condition;
		
		try{
			// SQLの実行
			require_once('../mdl/CommonExecution.php');
			$dbobj = new CommonExecution();
			$dbobj->CommonSilentSQL($sql);
			return $sql;
		}catch (Exception $e){
			echo "[error]";
			echo $e->getMessage()."\n";
		}
	} else {
		$l_log_buff	=	"対象なし";
		echo $l_log_buff."\n";
	}
	
?>