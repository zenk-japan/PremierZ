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
<!doctype html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
	<meta content=text/html;charset=utf-8 http-equiv=Content-Type>
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
		<table id="id_table_disp_main">
			<tr>
				<td id="id_td_disp_title">
<!-- タイトル -->
					{$overview_title}
				</td>
			</tr>
			<tr>
				<td id="id_td_disp_main">
<!-- 本体 -->
					{include file = "$main_include_tpl"}
				</td>
			</tr>
			<tr>
				<td id="id_td_disp_button">
<!-- ボタン -->
					<input type="button" value="閉じる" id="id_btn_close" />
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
