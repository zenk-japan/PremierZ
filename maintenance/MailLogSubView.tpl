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
<div id="id_div_subv_outer" >
	<table id="id_table_subv_main">
<!-- タイトル -->
		<tr>
			<td class="c_td_subv_title" colspan=2>メールログ詳細</td>
		</tr>
<!-- 送信日 -->
		<tr>
			<td class="c_td_subv_cap">送信日</td>
			<td class="c_td_subv_val">{if $send_date != ""}{$send_date}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 送信目的 -->
		<tr>
			<td class="c_td_subv_cap">送信目的</td>
			<td class="c_td_subv_val">{if $send_purpose != ""}{$send_purpose}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 送信/受信 -->
		<tr>
			<td class="c_td_subv_cap">From</td>
			<td class="c_td_subv_val">{if $from_addr != ""}{$from_addr}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
		<tr>
			<td class="c_td_subv_cap">To</td>
			<td class="c_td_subv_val">{if $to_addr != ""}{$to_addr}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- タイトル -->
		<tr>
			<td class="c_td_subv_cap">タイトル</td>
			<td class="c_td_subv_val">{if $mail_title != ""}{$mail_title}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 本文 -->
		<tr>
			<td class="c_td_subv_body_cap">本文</td>
			<td class="c_td_subv_body_val">{if $mail_body != ""}{$mail_body}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 送信者 -->
		<tr>
			<td class="c_td_subv_sender_cap">送信者情報</td>
			<td class="c_td_subv_sender_val">{if $send_user != ""}{$send_user}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 閉じるボタン -->
		<tr>
			<td class="c_td_subv_btn" colspan=2><input type="button" id="id_btn_subv_close" class="c_btn_main_nomal" value="閉じる" /></td>
		</tr>
	</table>
</div>
<!-- BODY終了 -->
</body>
</html>