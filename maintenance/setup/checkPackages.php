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
/******************************************************************************
	処理概要：パッケージ確認
			Smarty、PHPMailer、tcpdfの確認を行う
 ******************************************************************************/
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	$l_message		= "0";
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		var_dump($_POST);
	}
	
/*-----------------------------------------------------------------------------
	本体処理
 -----------------------------------------------------------------------------*/
	$l_Smarty_file		= "../../Smarty/libs/Smarty.class.php";
	$l_PHPMailer_file	= "../../PHPMailer/class.phpmailer.php";
	$l_tcpdf_file1		= "../../tcpdf/tcpdf.php";
	$l_tcpdf_file2		= "../../tcpdf/config/lang/jpn.php";
	$l_phpexcel_file	= "../../phpexcel/Classes/PHPExcel.php";

	// Smarty
	if ( !file_exists( $l_Smarty_file )) {
		$l_message .= "Smarty/libs/Smarty.class.phpが見つかりませんでした。Smartyをセットアップして下さい。\n";
	}
	// PHPMailer
	if ( !file_exists( $l_PHPMailer_file )) {
		$l_message .= "PHPMailer/class.phpmailer.phpが見つかりませんでした。PHPMailerをセットアップして下さい。\n";
	}
	// tcpdf
	if ( !file_exists( $l_tcpdf_file1 )) {
		$l_message .= "tcpdf/tcpdf.phpが見つかりませんでした。tcpdfをセットアップして下さい。\n";
	}
	// tcpdf
	if ( !file_exists( $l_tcpdf_file2 )) {
		$l_message .= "tcpdf/config/lang/jpn.phpが見つかりませんでした。tcpdfをセットアップして下さい。\n";
	}
	// phpexcel
	if ( !file_exists( $l_phpexcel_file )) {
		$l_message .= "phpexcel/Classes/PHPExcel.phpが見つかりませんでした。phpexcelをセットアップして下さい。\n";
	}
	print $l_message;
	exit;
?>