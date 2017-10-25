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
	
	// キャリア判別
	require_once('../lib/CommonMobiles.php');
	$cm = new CommonMobiles();
	$connec_terminal = $cm->checkMobiles();
	
	if ($_SERVER['HTTPS'] == "on") {
		$httpheader = 'https';
	} else {
		$httpheader = 'http';
	}
	
	$host	=	$_SERVER['HTTP_HOST'];
	
	$uri	=	rtrim(dirname(rtrim(dirname($_SERVER['PHP_SELF']), '/\\')), '/\\');
	
	switch ($connec_terminal[Terminal]){
		// ログインページ - PC -
		case TERMINAL_PC :
			if (!headers_sent()) {
				$extra	=	'/page/entrance.php';
				$site_add = $httpheader."://".$host.$uri.$extra;
			//	echo "<hr>".$site_add."<hr>";
				header("Location: $site_add");
				exit;
			}
		break;
		// ログインページ - Mobile -
		case TERMINAL_DOCOMO :
			if (!headers_sent()) {
				$extra	=	'/mobile/login.php?guid=ON';
			//	$site_add = $httpheader."://".$host.$uri.$extra;
				$site_add = "http://".$host.$uri.$extra;
			//	echo "<hr>".$site_add."<hr>";
				header("Location: $site_add");
				exit;
			}
		break;
		case TERMINAL_AU :
			if (!headers_sent()) {
				$extra	=	'/mobile/login.php';
				$site_add = "http://".$host.$uri.$extra;
			//	echo "<hr>".$site_add."<hr>";
				header("Location: $site_add");
				exit;
			}
		break;
		case TERMINAL_WILLCOM :
		case TERMINAL_SOFTBANK :
			if (!headers_sent()) {
				$extra	=	'/mobile/login.php';
				$site_add = $httpheader."://".$host.$uri.$extra;
			//	echo "<hr>".$site_add."<hr>";
				header("Location: $site_add");
				exit;
			}
		break;
		// 端末情報不明
		default :
			$str_meta			=	"<META http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
			$str_stylesheet		=	"<LINK REL=\"STYLESHEET\" HREF=\"../css/v_standard.css\" TYPE=\"text/css\">";
			$img_icon			=	"<LINK REL=\"SHORTCUT ICON\" HREF=\"../img/zenkicon.png\">";
			$str_title			=	"<TITLE>Cannot be read</TITLE>";
			$img_image			=	"<TR><TD><IMG src=../img/zenklog_half.png></TD></TR>";
			$str_copyright		=	"<DIV class=btmcr>".COPY_RIGHT_PHRASE."</DIV>";
			$str_msg1			=	"<TR><TD>".MESSAGE_CANNOTBEREAD."</TD></TR>";
//以下htmlの出力
echo<<<_HTML_
<HTML>
<HEAD>
	$str_meta
	$str_stylesheet
	$img_icon
	$str_title
</HEAD>
<BODY>
	<DIV class=outer >
		<DIV class=topline>
			<H1 class=topline>&nbsp;</H1>
			<table class=imagetab>
				$img_image
			</table>
		</DIV><P>
		<DIV class=headline><HR class=headline></HR></DIV><P>
		<DIV class=bclink></DIV><P>
		<DIV class=hdldata>
			<TABLE>
				$str_msg1
				$str_msg2
			</TABLE><P>
		</DIV><P>
		<DIV class=offrame></DIV>
		<DIV class=btmcr>$str_copyright</DIV>
	</DIV>
</BODY>
</HTML>
_HTML_;
exit;
	}
?>
