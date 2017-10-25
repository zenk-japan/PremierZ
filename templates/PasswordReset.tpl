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
<html>
<head>
	<meta content=text/html;charset=utf-8 http-equiv=Content-Type>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
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
<div id="vdsp"></div>
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
		<span id="id_span_hd">{$systemname} パスワードリセット完了</span>
	</div>
	<br>
	<br>
	<div id="id_div_main">
		<form id="id_form_main" method="" action="">
			<table id="id_table_main">
				<tr class="c_tr_main">
					<td class="c_td_main_1" colspan=2>
						{$user_name} 様<br><br>
						パスワードを変更しました。<br>
						新しいパスワードは、<br><br>
						<span class="c_span_strong">
						{$new_password}
						</span>
						<br>
						<br>
						になります。
					</td>
				</tr>
			</table>
		</form>
		<br>
		<div id="id_div_pcmob">
			<a href="../page/entrance.php">ログイン画面へ</a>
		</div>
		<br>
		<br>
	</div>
	<br>
	<br>
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
</div>

<!-- BODY終了 -->
</body>
</html>
