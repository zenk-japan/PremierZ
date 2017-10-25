<!--
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
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<HTML>
<HEAD>
	<META content=text/html;charset=utf-8 http-equiv=Content-Type>
{foreach from=$ar_css_files item=css_file}
	<LINK REL="stylesheet" HREF="{$css_file}" TYPE="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<SCRIPT type="text/javascript" src="{$js_file}"></SCRIPT>
{/foreach}
	<TITLE>{$headtitle}</TITLE>
</HEAD>
<BODY>
<DIV id="cp1" class="coverplate"><INPUT id="hd_cover_stat" type=hidden></INPUT></DIV>
<!-- 外枠-->
<DIV class={$css_outer} >
	<DIV class={$css_topline}>
		<!-- 3色線 -->
		<H1 class={$css_topline}>&nbsp;</H1>
		<table class={$css_imagetab}>
			<TR>
				<!-- zenkロゴ -->
				<TD><IMG src={$img_logo}></TD>
			</TR>
		</table>
	</DIV>
	<P>
<!-- 本体 -->
	<DIV class={$css_maintab_headline}>
<!-- 1段目:見出し -->
		<HR class={$css_maintab_headline}></HR>
	</DIV>
	<P>
<!-- 2段目:リンク -->
	<DIV class={$css_maintab_link}>
	<!--	{$html_bclink}-->
	</DIV>
	<P>
<!-- 3段目:ヘッドライン項目 -->
	<FORM name={$fname_search} id={$fname_search} method=POST>
		<DIV class={$css_maintab_hdlval}>
			<TABLE>
				<TR><TD>{$message1}</TD></TR>
				<TR><TD>{$message2}</TD></TR>
				<TR><TD>{$message3}</TD></TR>
			</TABLE>
			<P>
<!-- 4段目:ボタン -->
			<P>
		</DIV>
	</FORM>
	<P>
<!-- 5段目:インナーフレーム -->
	<DIV class={$css_maintab_frame}>
		<!-- 明細表示部 -->
	</DIV>
<!-- 6段目:フッター領域 -->
	<DIV class={$css_maintab_footer}>
		<!-- フッター表示部 -->
<!--
		{$html_footer}
-->
	</DIV>
<!-- 7段目:コピーライト -->
	<DIV class={$css_maintab_btmcr}>
		{$txt_copyright}
	</DIV>
<!-- 本体終了 -->
</DIV>
<!-- 外枠終了 -->
</BODY>
</HTML>
