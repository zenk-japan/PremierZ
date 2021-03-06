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
	<form method="GET">
	<!-- 項目表示 -->
	【作業日】<br>
	{$work_date}<br>
	【作業名】<br>
	{$work_name}<br>
	【作業時間】<br>
	{$working_hour}<br>
	【作業場所】<br>
	{$work_place}<br>
	【作業纏め者】<br>
	{$responsible}<br>
	【入退店登録時間】<br>
	{$enter_leave_time}<br>
	【作業費】<br>
	{$working_costs}<br>
	【残業代】<br>
	{$overtime_costs}<br>
	【交通費】<br>
	{$travelexpenses}<br>
	<font size="-1" color="#FF0303">{$remarks}</font>
	<br>
	<br>
	<!-- 送信フォーム -->
	<a href="{$mail_href}">補足/修正送信フォーム</a>
	<input type="hidden" name="gv_work_staff_id" value={$work_staff_id}></input>
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
	<div align="center">{$txt_copyright}</div>
</body>
</html>
