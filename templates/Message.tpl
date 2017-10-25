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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
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
<div id="id_div_outer" >
	<div id="id_div_topline">
		<!-- 3色線 -->
		<h1 id="id_h1_topline">&nbsp;</h1>
		<table id="id_table_topline">
			<tr>
				<!-- ロゴ -->
				<td id="id_td_topline">&nbsp;</td>
			</tr>
		</table>
	</div>
	<br>
<!-- タイトル -->
	<div id="id_div_hd">
		<hr id="id_hr_hd"></hr>
		<span id="id_span_hd">{$systemname}</span>
	</div>
	<br>
<!-- メッセージ表示部 -->
	<div id="id_div_message">
		{* セッション切断 *}
		{if $mess_item.mode=="ST"}
		<span class=".c_mess_sessiontimeout">セッションが切断されました。ログイン画面から再ログインして下さい。</span><br>
			{if isset($mess_item.extmess)}
			<span class=".c_mess_extend">{$mess_item.extmess}</span><br>
			{/if}
		<br>
		<a href="{$mess_item.nexturl}">ログイン画面</a>
		{* 予期せぬ例外 *}
		{elseif $mess_item.mode=="ER"}
		<span class=".c_mess_unknownexpt">予期せぬ例外が発生しました。ログイン画面からやり直してして下さい。</span><br>
			{if isset($mess_item.extmess)}
			<span class=".c_mess_extend">{$mess_item.extmess}</span><br>
			{/if}
		<br>
		<a href="{$mess_item.nexturl}">ログイン画面</a>
		{/if}
	</div>
</div>
<!-- BODY終了 -->
</body>
</html>