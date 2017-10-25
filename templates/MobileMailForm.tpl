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
<?xml version="1.0" encoding="{$char_code}"?>
<!-- {$terminal}/{$model} -->
{$doctype}
<html{$xmlns}>
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<title>{$headtitle}</title>
</head>
<body>
	<DIV>
	<!-- イメージファイル -->
	<IMG src="{$img_logo}" alt=""><HR>
	<font color="#6495ED"><b>{$headtitle}</b></font>
	</DIV>
	<!-- メッセージ出力 -->
	<DIV>
	</DIV>
	<DIV>
	{$headinfo}<BR>
	</DIV>
<!--	<DIV>-->
	<form method="GET" action="{$form_action}">
	<!-- 送信元 -->
	{if $from_display_flag}
		■From：{if $from_addr_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $from_addr_readonly != ""}
			{$from_addr}<input type="hidden" name="nm_from_addr" value="{$from_addr}"></input>
		{else}
			<input style="width:97%" type="text" name="nm_from_addr" value="{$from_addr}" {$from_addr_readonly}></input>
		{/if}
		<br><br>
	{else}
		<input type="hidden" name="nm_from_addr" value="{$from_addr}" {$from_addr_readonly}></input>
	{/if}
	<!-- 送信先 -->
		■To：{if $to_addr_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $to_addr_readonly != ""}
			{$to_addr}<input type="hidden" name="nm_to_addr" value="{$to_addr}"></input>
		{else}
			<input style="width:97%" type="text" name="nm_to_addr" value="{$to_addr}" {$to_addr_readonly}></input>
		{/if}
		<br><br>
	<!-- CC -->
	{if $cc_addr != ""}
		■CC：{if $cc_addr_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $cc_addr_readonly != ""}
			{$cc_addr}<input type="hidden" name="nm_cc_addr" value="{$cc_addr}"></input>
		{else}
			<input style="width:97%" type="text" name="nm_cc_addr" value="{$cc_addr}" {$cc_addr_readonly}></input>
		{/if}
		<br><br>
	{/if}
	<!-- BCC -->
	{if $bcc_addr != ""}
		■BCC：{if $bcc_addr_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $bcc_addr_readonly != ""}
			{$bcc_addr}<input type="hidden" name="nm_bcc_addr" value="{$bcc_addr}"></input>
		{else}
			<input style="width:97%" type="text" name="nm_bcc_addr" value="{$bcc_addr}" {$bcc_addr_readonly}></input>
		{/if}
		<br><br>
	{/if}
	<!-- タイトル -->
		■タイトル：{if $to_addr_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $mail_title_readonly != ""}
			{$mail_title}<input type="hidden" name="nm_mail_title" value="{$mail_title}"></input>
		{else}
			<input style="width:97%" type="text" name="nm_mail_title" value="{$mail_title}" {$mail_title_readonly}></input>
		{/if}
		<br><br>
	<!-- 本文 -->
		■本文：{if $mail_text_readonly != ""}<font size="-1" color="red">※変更できません</font>{/if}<br>
		{if $mail_text_readonly != ""}
			{$mail_text}<input type="hidden" name="nm_mail_text" value="{$mail_text}"></input>
		{else}
			<textarea style="width:97%;height:5em;" rows="5" wrap="hard" name="nm_mail_text" value="{$mail_text}" {$mail_text_readonly}></textarea>
		{/if}
		<br><br>
	<!-- ボタン -->
		<input type="submit" value="送信"></input><br>
	<!-- パラメータ -->
	{foreach from=$ar_param item=param_item}
		<input type="hidden" name="{$param_item.name}" value="{$param_item.value}"></input>
	{/foreach}
	</form>
<!--	</DIV>-->
	<HR>
	<!-- ハイパーリンク -->
	<DIV>
	{foreach from=$fmlink item=link_line}
		{if $link_line.value == "RETURN"}
		<BR>
		{elseif $link_line.value != ""}
			<a href="{$link_line.link_url}" {$link_line.key}>{$link_line.value}</a><BR>
		{/if}
	{/foreach}
		<BR>
	</DIV>
	<HR>
	<!-- コピーライト -->
	<div style="text-align:center">{$txt_copyright}</div>
</body>
</html>
