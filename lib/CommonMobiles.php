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
require_once('Net/UserAgent/Mobile.php');

class CommonMobiles{
/* =============================================================================
	接続端末取得関数
	概要：アクセスされたキャリア・機種名を取得する
		$terminal			キャリア
		$model				機種名
   =============================================================================*/
	function checkMobiles() {
		
		$terminal	=	NULL;								// キャリア
		$model		=	NULL;								// 機種
		
		$agent = Net_UserAgent_Mobile::factory();
		$model = $agent->getModel(); 
		
		/*--------------------*/
		/*  DoCoMo            */
		/*--------------------*/
		if ($agent->isDoCoMo()){
			$terminal	=	TERMINAL_DOCOMO;
			
		/*--------------------*/
		/*  SoftBank          */
		/*--------------------*/
		} else if ($agent->isSoftBank()){
			$terminal	=	TERMINAL_SOFTBANK;
			
		/*--------------------*/
		/*  au                */
		/*--------------------*/
		} else if ($agent->isEZweb()){
			$terminal	=	TERMINAL_AU;
			
		/*--------------------*/
		/*  Willcom           */
		/*--------------------*/
		} else if ($agent->isWillcom()) {
			$terminal	=	TERMINAL_WILLCOM;
			
		/*--------------------*/
		/*  Mobile以外        */
		/*--------------------*/
		} else if ($agent->isNonMobile()){
			
			$terminal	=	TERMINAL_PC;
			$useragent		=	$_SERVER['HTTP_USER_AGENT'];
			
			/*--------------------*/
			/*  iPhone            */
			/*--------------------*/
			if (count(explode(AGENT_IPHONE,$useragent)) > 1){
				$terminal	=	TERMINAL_SOFTBANK;
				$model		=	MODEL_IPHONE;
				
			/*--------------------*/
			/*  InternetExplorer  */
			/*--------------------*/
			} else if(preg_match('/' . AGENT_MSIE . '/', $useragent)) {
				$model		=	MODEL_MSIE;
				
			/*--------------------*/
			/*  FireFox           */
			/*--------------------*/
			} else if(preg_match('/' . AGENT_FIREFOX . '/', $useragent)) {
				$model		=	MODEL_FIREFOX;
				
			/*--------------------*/
			/*  Opera            */
			/*--------------------*/
			} else if(preg_match('/' . AGENT_OPERA . '/', $useragent)) {
				$model		=	MODEL_OPERA;
				
			/*--------------------*/
			/*  GoogleChrome      */
			/*--------------------*/
			} else if(preg_match('/' . AGENT_CHROME . '/', $useragent)) {
				$model		=	MODEL_CHROME;
				
			/*--------------------*/
			/*  Safari            */
			/*--------------------*/
			} else if(preg_match('/' . AGENT_SAFARI . '/', $useragent)) {
				$model		=	MODEL_SAFARI;
				
			/*--------------------*/
			/*  Other             */
			/*--------------------*/
			} else {
				$model		=	AGENT_OTHER;
			}
		/*--------------------*/
		/*  不明              */
		/*--------------------*/
		} else {
			$terminal	=	TERMINAL_UNKNOWN;
			$model		=	MODEL_UNKNOWN;
		}
		
		return array("Terminal" => $terminal, "Model" => $model);
	}
	
/* =============================================================================
	個体識別ID取得関数
	概要：アクセスしたキャリアの個体識別IDを取得する
			$terminal			キャリア
			$l_return[Uid]		個体識別ID
			$l_return[Serial]	端末製造番号
			$l_return[Card]		FOMAカード個体識別
   =============================================================================*/
	function getUniqueId($terminal) {
		
		$l_return	=	array();
		$useragent	=	$_SERVER['HTTP_USER_AGENT'];
		
		/*--------------------*/
		/*  DoCoMo            */
		/*--------------------*/
		if ($terminal == TERMINAL_DOCOMO) {
			// iモードID(XXXXXXX)
			$l_return[Uid]		=	$_SERVER['HTTP_X_DCMGUID'];
			
			// 端末製造番号(MOVA=serXXXXXXXXXXX,FOMA=serXXXXXXXXXXXXXXX)
			$l_return[Serial]	=	(preg_match("/^.+ser([0-9a-zA-Z]+).*$/", $useragent, $match_ser)) ? $match_ser[1] : '';
			
			// FOMAカード個体識別(FOMA=iccXXXXXXXXXXXXXXXXXXXX)
			$l_return[Card]		=	(preg_match("/^.+icc([0-9a-zA-Z]+).*$/", $useragent, $match_icc)) ? $match_icc[1] : '';
			
		/*--------------------*/
		/*  SoftBank          */
		/*--------------------*/
		} else if ($terminal == TERMINAL_SOFTBANK){
			// ユーザID(XXXXXXXXXXXXXXXX)
			$l_return[Uid]		=	$_SERVER['HTTP_X_JPHONE_UID'];
			
			// 端末シリアルID(SN000000000000000)
			$l_return[Serial]	=	(preg_match("/^.+\/SN([0-9a-zA-Z]+).*$/", $useragent, $match_sn)) ? $match_sn[1] : '';
		/*--------------------*/
		/*  au                */
		/*--------------------*/
		} else if ($terminal == TERMINAL_AU){
			// EZ番号(00000000000000_xx.ezweb.ne.jp)
			$l_return[Uid]		=	$_SERVER['HTTP_X_UP_SUBNO'];
			
		///*--------------------*/
		///*  その他            */
		///*--------------------*/
		//} else {
		//	$l_return[Uid]		=	$_SERVER['REMOTE_ADDR'];
		//	
		}
		return $l_return;
	}
}
?>
