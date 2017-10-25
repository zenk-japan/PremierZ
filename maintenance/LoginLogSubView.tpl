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
			<td class="c_td_subv_title" colspan=2>ログインログ詳細</td>
		</tr>
<!-- ログイン日時 -->
		<tr>
			<td class="c_td_subv_cap">ログイン日時</td>
			<td class="c_td_subv_val">{if $login_time != ""}{$login_time}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 使用ユーザーコード -->
		<tr>
			<td class="c_td_subv_cap">使用ユーザーコード</td>
			<td class="c_td_subv_val">{if $used_user_code != ""}{$used_user_code}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 使用パスワード -->
		<tr>
			<td class="c_td_subv_cap">使用パスワード</td>
			<td class="c_td_subv_val">{if $used_password != ""}{$used_password}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 使用利用会社コード -->
		<tr>
			<td class="c_td_subv_cap">使用利用会社コード</td>
			<td class="c_td_subv_val">{if $used_company_code != ""}{$used_company_code}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 認証結果 -->
		<tr>
			<td class="c_td_subv_cap">認証結果</td>
			<td class="c_td_subv_val">{if $certification_result != ""}{$certification_result}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- 移動元画面 -->
		<tr>
			<td class="c_td_subv_cap">移動元画面</td>
			<td class="c_td_subv_val">{if $spg_referer != ""}{$spg_referer}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- ユーザーIPアドレス -->
		<tr>
			<td class="c_td_subv_cap">ユーザーIPアドレス</td>
			<td class="c_td_subv_val">{if $spg_remort_addr != ""}{$spg_remort_addr}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
		</tr>
<!-- _SERVER -->
		<tr>
			<td class="c_td_subv_body_cap">{literal}${/literal}_SERVER</td>
			<td class="c_td_subv_body_val"><div id="id_div_server">{if $spg_server != ""}{$spg_server}{else}<font color="#bbbbbb"><値なし></font>{/if}</div></td>
		</tr>
<!-- 備考 -->
		<tr>
			<td class="c_td_subv_cap">備考</td>
			<td class="c_td_subv_val">{if $remark != ""}{$remark}{else}<font color="#bbbbbb"><値なし></font>{/if}</td>
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