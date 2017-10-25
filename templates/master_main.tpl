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
<!-- BODY開始 -->
	<div id = "id_div_master">
<!-- トップ -->
		{include file = "$top_include_tpl"}
<!-- メインメニュー -->
		{include file = "$main_include_tpl"}
<!-- サブメニュー -->
		{if isset($sub_include_tpl)}{include file = "$sub_include_tpl"}{/if}
		<table id="id_tbl_disp_main">
			<tr>
				<td id="id_td_disp_search">
<!-- 検索メニュー -->
					{include file = "$search_include_tpl"}
				</td>
				<td id="id_td_disp_detail" rowspan=2>
<!-- 明細 -->
					{include file = "$detail_include_tpl"}
				</td>
			</tr>
<!-- リスト -->
			<tr>
				<td id="id_td_disp_list">
					{include file = "$list_include_tpl"}
				</td>
			</tr>
		</table>
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
	</div>
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
	<div>
	</div>
<!-- BODY終了 -->
</BODY>
</HTML>
