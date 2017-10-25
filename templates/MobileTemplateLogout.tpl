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
	<div>
	<!-- イメージファイル --><img src="{$img_logo}" alt=""><hr>
	<font color="#6495ED"><b>{$headtitle}</b></font></div>
	<!-- ヘッドライン -->
	<div>{$headinfo}<br></div>
	<!-- 項目表示 -->
	<div>ログアウト処理を実行しました。<br></div>
	<div>作業を行う場合は、ログイン画面から再ログインして下さい。<br></div>
	<div><br></div>
	<hr>
	<!-- ハイパーリンク -->
	<div>
	{foreach from=$fmlink item=link_line}
		{if $link_line.type == "RETURN"}<br>
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
