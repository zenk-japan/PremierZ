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
	class m_attendance_sheet {
		private $ar_condition;					// 検索条件配列
		private $ar_orderby;					// order by配列
		function attendance_sheet_defaultlist(&$w_attendance_sheet, $pr_post_value){
			// 一覧表示
			require_once('../mdl/Attendance_sheet_v.php');
			$dbobj = new Attendance_sheet_v();
			
			// 検索キーの設定
			$dataid				=	$pr_post_value["hd_dataid"];			//データID
			$estimateid			=	$pr_post_value["ESTIMATE_ID"];			//見積ID
			$workuserid			=	$pr_post_value["WORK_USER_ID"];			//作業者ID
			$validityflag		=	'Y';							//有効フラグ(Yのみ)
			
			//print_r($pr_post_value);
			//print "<br>";
			//print "$estimateid -> ".$estimateid."<br>";
			//print "$workuserid -> ".$workuserid."<br>";
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"ESTIMATE_ID"		=> $estimateid,
									"WORK_USER_ID"		=> $workuserid,
									"WC_VALIDITY_FLAG"	=> $validityflag,
									"WS_VALIDITY_FLAG"	=> $validityflag
									);
									
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// order by配列セット
			$this->ar_orderby = array(
									"DATA_ID",
									"WORK_DATE"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_attendance_sheet = $dbobj->getRecord();
		}
		
		function attendance_sheet_searchlist(&$w_attendance_sheet, $pr_post_value, $p_authority){
			// 一覧表示
			require_once('../mdl/Attendance_sheet_v.php');
			$dbobj = new Attendance_sheet_v();
			
			// 検索キーの設定
			$dataid				=	$pr_post_value["hd_dataid"];			//データID
			$workdate			=	$pr_post_value["WORK_DATE"];			//作業日
			$workname			=	$pr_post_value["WORK_NAME"];			//作業名
			$workusername		=	$pr_post_value["WORK_USER_NAME"];		//作業者ユーザ名
			$loginuserid		=	$pr_post_value["hd_loginuserid"];		//作業者ユーザID
			$validityflag		=	'Y';									//有効フラグ(Yのみ)
			
			//print_r($pr_post_value);
			//print "<br>";
			//print "workdate -> ".$workdate."<br>";
			//print "workname -> ".$workname."<br>";
			//print "workusername -> ".$workusername."<br>";
			//print "loginuserid -> ".$loginuserid."<br>";
			//print "p_authority -> ".$p_authority."<br>";
			
			// 条件配列セット
			$this->ar_condition = array(
									"DATA_ID"			=> $dataid,
									"WORK_DATE"			=> "%".$workdate."%",
									"WORK_NAME"			=> "%".$workname."%",
									"WORK_USER_NAME"	=> "%".$workusername."%",
									"WC_VALIDITY_FLAG"	=> $validityflag,
									"WS_VALIDITY_FLAG"	=> $validityflag
									);
			
			// 一般ユーザーの場合は、自分の分のみ出力する条件を追加する
			if($p_authority == AUTH_GEN1 || $p_authority == AUTH_GEN2 || $p_authority == AUTH_GENE){
				$this->ar_condition += array("WORK_USER_ID"	=> $loginuserid);
			}
			
			// 条件セット
			$l_ar_cond = $dbobj->setCondition($this->ar_condition);
			
			// group by配列セット
			$this->ar_groupby = array(
									"DATA_ID",
									"WORK_DATE_YM",
									"ESTIMATE_ID",
									"WORK_USER_ID"
									);
			
			// group byセット
			$l_ar_groupby = $dbobj->setGroupbyPhrase($this->ar_groupby);
			
			//echo $l_ar_groupby."<BR>";
			
			// order by配列セット
			$this->ar_orderby = array(
									"DATA_ID",
									"WORK_DATE",
									"ESTIMATE_ID",
									"WORK_USER_ID"
									);
			
			// order byセット
			$l_ar_orderby = $dbobj->setOrderbyPhrase($this->ar_orderby);
			
			// レコード取得
			$w_attendance_sheet = $dbobj->getRecord();
		}
	}
?>