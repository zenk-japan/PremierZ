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
<div id="id_div_outer" >
	<div id="id_div_topline">
		<!-- 3色線 -->
		<h1 id="id_h1_topline">&nbsp;</h1>
		<table id="id_table_topline">
			<tr>
				<!-- ロゴ -->
				<td id="id_td_topline"></td>
			</tr>
		</table>
	</div>
	<div id="id_div_logout">
		<table id="id_table_logout">
			<tr>
				<td id="id_td_margin">&nbsp;</td>
		<!-- ユーザー名 -->
				<td id="id_td_user_name">ユーザー：{$user_name}</td>
		<!-- ログアウト -->
				<td id="id_td_logout"><a href="logout.php">ログアウト</a></td>
			</tr>
		</table>
	</div>
	<br>
	<div id="id_div_hd">
		<hr id="id_hr_hd"></hr>
		<span id="id_span_hd">{$head_title}</span>
	</div>
	<br>
	<br>
	<div id="id_div_main">
		<table id="id_table_main">
			<tr id="id_tr_main_top" class="c_tr_main_top">
				{foreach from=$ar_maintab_top item=maintab_top_item name=fe_maintab_top_tr}
				<th id="id_th_main{$smarty.foreach.fe_maintab_top_tr.iteration}" class="c_th_main_top">
					<span class="c_span_main_top">{$maintab_top_item}</span>
				</th>
				{/foreach}
			</tr>
			{foreach from=$ar_maintab_dtl item=maintab_dtl name=fe_maintab_dtl_tr}
			<tr id="id_tr_main_dtl{$smarty.foreach.fe_maintab_dtl_tr.iteration}" class="c_tr_main_dtl">

				<td id="id_td_main{$smarty.foreach.fe_maintab_dtl_tr.iteration}1" class="{if $smarty.foreach.fe_maintab_dtl_tr.iteration % 2 == 0}c_td_main_even_btn{else}c_td_main_odd_btn{/if}">
					<input type="button" id="{$maintab_dtl.btn_id}" class="c_btn_main" value="{$maintab_dtl.btn_value}"></input>
				</td>
				<td id="id_td_main{$smarty.foreach.fe_maintab_dtl_tr.iteration}2" class="{if $smarty.foreach.fe_maintab_dtl_tr.iteration % 2 == 0}c_td_main_even{else}c_td_main_odd{/if}">
					<span class="c_span_main_dtl">{$maintab_dtl.explain}</span>
				</td>

			</tr>
			{/foreach}
		</table>
	</div>
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
	<br>
</div>
<!-- BODY終了 -->
</body>
</html>