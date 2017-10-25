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
	class m_worksituation {
		private $ar_condition;					// 検索条件配列
		private $ar_orderby;					// order by配列
		// 一覧表示（検索）
		function worksituation_list(&$w_worksituation){
			require_once('../mdl/Worksituation_v.php');
			$dbobj = new Worksituation_v();
			
			// POSTされた項目から条件を設定
			// 検索キーの設定
			$dataid				=	$_POST["hd_dataid"];				//データID
			$estimatecode		=	$_POST["ESTIMATE_CODE"];			//見積コード
			$workname			=	$_POST["WORK_NAME"];				//作業名
			$workbasename		=	$_POST["WORK_BASE_NAME"];			//拠点名
			$workdate			=	$_POST["WORK_DATE"];				//作業日
			$workstaffname		=	$_POST["WORK_STAFF_NAME"];			//作業者名
			$approvaldivision	=	$_POST["APPROVAL_DIVISION_NAME"];	//承認区分
			$canceldivision		=	$_POST["CANCEL_DIVISION_NAME"];		//キャンセル区分
			$workstatusname		=	$_POST["WORK_STATUS_NAME"];			//作業ステータス
			$displaydelete		=	$_POST["hd_delete_check"];			//削除済表示
			
			// 削除済表示を元に有効フラグの設定
			if($displaydelete == '' ){
				$validityflag = 'Y';									//有効フラグ(Yのみ)
			} else {
				$validityflag = '';										//有効フラグ(全て)
			}
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"					=> $dataid,
									"ESTIMATE_CODE"				=> "%".$estimatecode."%",
									"WORK_STAFF_NAME"			=> "%".$workstaffname."%",
									"WORK_DATE"					=> "%".$workdate."%",
									"WORK_NAME"					=> "%".$workname."%",
									"WORK_BASE_NAME"			=> "%".$workbasename."%",
									"APPROVAL_DIVISION_NAME"	=> "%".$approvaldivision."%",
									"CANCEL_DIVISION_NAME"		=> "%".$canceldivision."%",
									"WORK_STATUS_NAME"			=> "%".$workstatusname."%",
									"VALIDITY_FLAG"				=> "%".$validityflag."%"
									);
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array(
									"DATA_ID",
									"WORK_DATE",
									"ESTIMATE_CODE",
									"WORK_STAFF_NAME"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_worksituation = $dbobj->getRecord();
			
		}
	}
?>