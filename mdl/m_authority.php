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
	class m_authority {
		// 検索用
		function authority_list(&$data_auth){
			
			require_once('../mdl/Authority_v.php');
			$dbobj = new Authority_v();
			
			$table_name = "Authority_v";
			
			// 検索キーの設定
			$dataid				=	$_SESSION['_authsession']['data']['DATA_ID'];			//データID
			$authority_code		=	$_SESSION['_authsession']['data']['AUTHORITY_CODE'];	//権限コード
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"AUTHORITY_CODE"	=> $authority_code
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_auth = $dbobj->getRecord();
		}
		
		// 権限ID取得用
		function getAuthorityId($p_data_id,$p_authname,&$return_auth){
			
			require_once('../mdl/Authority_v.php');
			$dbobj = new Authority_v();
			
			$table_name = "Authority_v";
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $p_data_id,
									"AUTHORITY_NAME"	=> $p_authname
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// レコード取得
			$data_auth = $dbobj->getRecord();
			$return_auth = $data_auth[1]["AUTHORITY_ID"];
		}
	}
?>