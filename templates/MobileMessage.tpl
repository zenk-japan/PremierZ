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
	<!-- メッセージ本文 -->
		{$main_message}
		<br>
		<br>
	<!-- ボタン -->
		<input type="submit" value="OK"></input><br>
	<!-- パラメータ -->
	{foreach from=$ar_param item=param_item}
		<input type="hidden" name="{$param_item.name}" value="{$param_item.value}"></input>
	{/foreach}
	</form>
<!--	</DIV>-->
	<HR>
	<!-- コピーライト -->
	<div style="text-align:center">{$txt_copyright}</div>
</body>
</html>
