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
{$doctype}
<html{$xmlns}>
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<title>{$headtitle}</title>
{foreach from=$ar_css_files item=css_file}
	<LINK REL="stylesheet" HREF="{$css_file}" TYPE="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<SCRIPT type="text/javascript" src="{$js_file}"></SCRIPT>
{/foreach}
</head>
<body>
	<div id = "id_div_master">
	<div>
	<table>
		<!-- イメージファイル -->
		<td id="id_td_topline" class="c_td_topmenu">&nbsp;</td>
	</table>
	<hr>
	<font color="#6495ED"><b>{$headtitle}</b></font></div>
	<!-- ヘッドライン -->
	<div>{$headinfo}<br></div>
	<!-- 項目表示 -->
	<div>ログアウト処理を実行しました。<br></div>
	<div>作業を行う場合は、ログイン画面から再ログインして下さい。<br></div>
	<div><br></div>
	<hr>
	<form id="id_form_hidden" method="POST">
	<div>
		<input type="button" onClick="movePage(this, '../page/entrance.php')" value="ログイン画面へ" />
	</div>
	<!-- 隠し項目 -->
	<div id="id_div_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
	</div>
	</form>
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
	</div>
</body>
</html>
