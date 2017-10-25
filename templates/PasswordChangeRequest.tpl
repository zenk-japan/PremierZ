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
		<span id="id_span_hd">{$systemname} パスワードリセット依頼</span>
	</div>
	<br>
	<br>
	<div id="id_div_main">
		<form id="id_form_main" method="" action="">
			<table id="id_table_main">
				<tr id="id_tr_main1" class="c_tr_main">
					<td class="c_td_main_pcr" colspan=2>&nbsp;</td>
				</tr>
				<tr class="c_tr_main">
					<td class="c_td_main_notice" colspan=2>
						<span>
						ユーザーコードと利用会社コード入力し、OKボタンをクリックして下さい。<br>
						登録済みのメールアドレスにパスワード変更依頼用のURLを送付いたします。
						</span>
					</td>
				</tr>
				<tr id="id_tr_main2" class="c_tr_main">
					<td class="c_td_main" id="id_td_main_user">ユーザーコード</td>
					<td class="c_td_main_ipt"><input type="text" class="c_ipt_main" id="id_ipt_username" name="nm_username"></input></td>
				</tr>
				<tr id="id_tr_main2" class="c_tr_main">
					<td class="c_td_main" id="id_td_main_pass">利用会社コード</td>
					<td class="c_td_main_ipt"><input type="text" class="c_ipt_main" id="id_ipt_usecomp" name="nm_usecomp"></input></td>
				</tr>
				<tr id="id_tr_main3" class="c_tr_main">
					<td class="c_td_main_btn" colspan=2><input id="id_sbt_login" type="button" onClick="" value="OK"></input></td>
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
