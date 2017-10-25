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
	{foreach from=$ar_msg item=ar_msg_line}
		{if $ar_msg_line.txtmsg == "RETURN"}
			<BR>
		{else}
			{$ar_msg_line.txtmsg}<BR>
		{/if}
	{/foreach}
	</DIV>
	<DIV>
	{$headinfo}<BR>
	</DIV>
<!--	<DIV>-->
	<!-- 項目表示 -->
	<form action="{$url_location}" method="{$form_action}">
	{foreach from=$ar_dispdata item=ar_dispdata_line}
		{if $ar_dispdata_line.name == "RETURN"}
			<BR>
			{if $ar_dispdata_line.value != ""}
				{$ar_dispdata_line.value}
			{/if}
		{elseif $ar_dispdata_line.name == "SEPARATOR"}
			</form>
			<HR>
			<form action="{$url_location}" method="{$form_action}">
		{elseif $ar_dispdata_line.name == "SEPARATE"}
			</form>
			<form action="{$url_location}" method="{$form_action}">
		{else}
			{if $ar_dispdata_line.type == "hidden"}
				<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="{$ar_dispdata_line.value}">
			{elseif $ar_dispdata_line.type == "hyperlink"}
				{if $terminal == "SoftBank" && $model != "iPhone" }
					<a href="{$ar_dispdata_line.value}" directkey="{$ar_dispdata_line.key}" nonumber>{$ar_dispdata_line.caption}</a><BR>
				{elseif $terminal == "PersonalComputer"}
					<a href="{$ar_dispdata_line.value}">{$ar_dispdata_line.caption}</a><BR>
				{else}
					<a href="{$ar_dispdata_line.value}" accesskey="{$ar_dispdata_line.key}">{$ar_dispdata_line.caption}</a><BR>
				{/if}
			{elseif $ar_dispdata_line.caption != "" && $ar_dispdata_line.type == "" && $ar_dispdata_line.name == ""}
				{$ar_dispdata_line.caption}<BR>
				{$ar_dispdata_line.value}
			{elseif $ar_dispdata_line.type == "radio"}
				{$ar_dispdata_line.caption}<BR>
				{if $ar_dispdata_line.value == "AP"}
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="AP" checked>承諾<BR>
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="NO">不承諾<BR>
				{elseif $ar_dispdata_line.value == "NO"}
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="AP">承諾<BR>
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="NO" checked>不承諾<BR>
				{else}
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="AP">承諾<BR>
					<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="NO">不承諾<BR>
				{/if}
			{elseif $ar_dispdata_line.type == "pulldown"}
				{$ar_dispdata_line.caption}<BR>
				<SELECT name="{$ar_dispdata_line.name}">
				{foreach from=$ar_pulldata item=ar_pulldata_line}
					<option value="{$ar_pulldata_line.value}" {$ar_pulldata_line.selected}>{$ar_pulldata_line.itemname}</option>
				{foreachelse}
					<option value="{$ar_pulldata_line.value}" {$ar_pulldata_line.selected}>データがありません</option>
				{/foreach}
				</SELECT>
				<BR>
			{elseif $ar_dispdata_line.type == "submit"}
				<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="{$ar_dispdata_line.value}" {$ar_dispdata_line.disabled}><BR>
			{else}
				{$ar_dispdata_line.caption}<BR>
				<INPUT name="{$ar_dispdata_line.name}" type="{$ar_dispdata_line.type}" value="{$ar_dispdata_line.value}" istyle="{$ar_dispdata_line.istyle}" mode="{$ar_dispdata_line.mode}"><BR>
			{/if}
		{/if}
	{/foreach}
	<!-- ボタン -->
	{$html_button}
	</form>	
<!--	</DIV>-->
	<HR>
	<!-- ハイパーリンク -->
	<DIV>
	{foreach from=$fmlink item=link_line}
		{if $link_line.value == "RETURN"}
			<BR>
		{elseif $link_line.value != ""}
			{if $terminal == "SoftBank" && $model != "iPhone" }
				<a href="{$link_line.link_url}" directkey="{$link_line.key}" nonumber>{$link_line.value}</a><BR>
			{elseif $terminal == "PersonalComputer"}
				<a href="{$link_line.link_url}">{$link_line.value}</a><BR>
			{else}
				<a href="{$link_line.link_url}" accesskey="{$link_line.key}">{$link_line.value}</a><BR>
			{/if}
		{/if}
	{/foreach}<BR>
	</DIV>
	<HR>
	<!-- コピーライト -->
	<DIV>{$txt_copyright}</DIV>
</body>
</html>
