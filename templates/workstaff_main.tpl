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
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
{foreach from=$ar_css_files item=css_file}
	<link rel="stylesheet" href="{$css_file}" type="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<script type="text/javascript" src="{$js_file}"></script>
{/foreach}
	<title>{$headtitle}</title>
</head>
<body>
<!-- BODY開始 -->
	<div id = "id_div_master">
<!-- トップ -->
		{include file = "$top_include_tpl"}
<!-- メインメニュー -->
		{include file = "$main_include_tpl"}
		<table id="id_table_disp_main">
			<tr>
				<td id="id_td_disp_info" colspan=2>
<!-- 概要 -->
					{include file = "$info_include_tpl"}
				</td>
			</tr>
			<tr>
				<td id="id_td_disp_search">
<!-- 検索 -->
					{include file = "$search_include_tpl"}
				</td>
			</tr>
			<tr>
				<td id="id_td_disp_operation">
<!-- 操作 -->
					{include file = "$operation_include_tpl"}
				</td>
			</tr>
			<tr>
				<td id="id_td_disp_detail">
<!-- 明細 -->
					{include file = "$detail_include_tpl"}
				</td>
			</tr>
		</table>
<!-- コピーライト -->
		<div id="id_div_copyright">{$txt_copyright}</div>
	</div>
<!-- 隠し項目 -->
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
<!-- BODY終了 -->
</body>
</html>
