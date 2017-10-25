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
<noscript style="font-size:24px;font-weight:bold;color:red;">
	<div style="width:100%;height:100%;position:absolute;z-index:100;background-color:yellow;text-align:center;vertical-align:middle;">
		javascriptが使用できない設定になっています。このサイトを利用するにはjavascriptを有効にして下さい。
	</div>
</noscript>
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
	<br>
	<div id="id_div_hd">
		<hr id="id_hr_hd"></hr>
		<span id="id_span_hd">{$headtitle} ログイン</span>
	</div>
	<br>
	<br>
	<div id="id_div_main">
		<form id="id_form_main" method="" action="">
			<table id="id_table_main">
				<tr id="id_tr_main1" class="c_tr_main">
					<td class="c_td_main_top" colspan=2>ユーザー情報入力</td>
				</tr>
				<tr id="id_tr_main2" class="c_tr_main">
					<td class="c_td_main">ユーザー名</td>
					<td class="c_td_main_ipt"><input type="text" class="c_ipt_main" id="id_ipt_username" name="nm_username"></input></td>
				</tr>
				<tr id="id_tr_main2" class="c_tr_main">
					<td class="c_td_main">パスワード</td>
					<td class="c_td_main_ipt"><input type="password" class="c_ipt_main" id="id_ipt_pass" name="nm_password"></input></td>
				</tr>
				<tr id="id_tr_main3" class="c_tr_main">
					<td class="c_td_main_btn" colspan=2><input id="id_sbt_login" type="button" onClick="" value="ログイン"></input></td>
				</tr>
			</table>
		</form>
	</div>
	<br>
</div>
<!-- BODY終了 -->
</body>
</html>