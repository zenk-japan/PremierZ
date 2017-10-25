<?php

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
		default :
			if (!headers_sent()) {
				$extra	=	'/workreport/wrlogin.php';
				$site_add = $httpheader."://".$host.$uri.$extra;
			//	echo "<hr>".$site_add."<hr>";
				header("Location: $site_add");
				exit;
			}
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
