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
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title>{$headtitle}</title>
</head>
<body>
	<!-- イメージファイル -->
	<div><img src="{$img_logo}" alt=""><hr>
	<font color="#6495ED"><b>{$headtitle}</b></font></div>
	<!-- ヘッドライン -->
	<div>{$headinfo}<br></div>
	<!-- 項目表示 -->
	<form action="{$fmurl}" method="{$fmact}">
	<div>{foreach from=$ar_workstaff item=l_ar_workstaff}
		{if $l_ar_workstaff.type == "RETURN"}<br>
		{elseif $l_ar_workstaff.type == "disp"}
			{$l_ar_workstaff.caption}<br>
			&nbsp;{$l_ar_workstaff.value}<br>
		{elseif $l_ar_workstaff.type == "text"}
			{$l_ar_workstaff.caption}<br>
			<input name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="{$l_ar_workstaff.value}" {$l_ar_workstaff.style}><br>
		{elseif $l_ar_workstaff.type == "radio"}
			{$l_ar_workstaff.caption}<BR>
			{if $l_ar_workstaff.value == "AP"}
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="AP" checked>承諾<BR>
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="NO">不承諾<BR>
			{elseif $l_ar_workstaff.value == "NO"}
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="AP">承諾<BR>
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="NO" checked>不承諾<BR>
			{else}
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="AP">承諾<BR>
				<INPUT name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="NO">不承諾<BR>
			{/if}
		{elseif $l_ar_workstaff.type == "combo"}
			{$l_ar_workstaff.caption}<br>
			<select name="{$l_ar_workstaff.name}">
			{foreach from=$l_ar_workstaff.value item=ar_combolist}
				<option value="{$ar_combolist.value}" {$ar_combolist.selected}>{$ar_combolist.itemname}</option>
			{foreachelse}
				<option value="{$ar_combolist.value}" {$ar_combolist.selected}>データがありません</option>
			{/foreach}
			</select><br>
		{elseif $l_ar_workstaff.type == "hidden"}
			<input name="{$l_ar_workstaff.name}" type="{$l_ar_workstaff.type}" value="{$l_ar_workstaff.value}">
		{elseif $l_ar_workstaff.type == "comment"}
			&nbsp;{$l_ar_workstaff.value}<br>
		{/if}
	{/foreach}<br>
	{foreach from=$ar_workstaff_btn item=l_ar_workstaff_btn}
		&nbsp;<input name="{$l_ar_workstaff_btn.name}" type="{$l_ar_workstaff_btn.type}" value="{$l_ar_workstaff_btn.value}">
	{/foreach}</div>
	</form>
	<hr>
	<!-- ハイパーリンク -->
	<div>
	{foreach from=$fmlink item=link_line}
		{if $link_line.value == "RETURN"}<br>
		{elseif $link_line.value != ""}
			{if $terminal == "SoftBank" && $model != "iPhone" }
				<a href="{$link_line.link_url}" directkey="{$link_line.key}" nonumber>{$link_line.value}</a><br>
			{elseif $terminal == "PersonalComputer"}
				<a href="{$link_line.link_url}">{$link_line.value}</a><br>
			{else}
				<a href="{$link_line.link_url}" accesskey="{$link_line.key}">{$link_line.value}</a><br>
			{/if}
		{/if}
	{/foreach}
	</div>
	<hr>
	<!-- コピーライト -->
	<div align="center">{$txt_copyright}</div>
</body>
</html>
