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
	<div id="id_div_hd">
		<hr id="id_hr_hd"></hr>
		<span id="id_span_hd">{$maintitle}</span>
	</div>
	<br>
	<div id="id_div_main">
		<span class="c_span_menu_title">■操作メニュー</span>
		<table id="id_table_main_top">
			<tr id="id_tr_main_btn">
				<td id="id_td_main_btn1">
					<input type="button" id="id_btn_save" class="c_btn_main_nomal" value="保存" />
					<input type="button" id="id_btn_gomenu" class="c_btn_main_nomal" value="メニューに戻る" />
				</td>
			</tr>
		</table>
		<br><br>
		<span class="c_span_menu_title">■変更内容入力</span>
		<table id="id_tab_sysadminmnt">
			<tr>
				<td class="c_td_sectitle">ユーザーコード変更</td>
				<td class="c_td_input">
					<table id="id_tab_user" class="c_tab_input">
						<tr>
							<td colspan=3>
								<input type="checkbox" name="nm_ckb_user" id="id_ckb_user" ><label for="id_ckb_user">ユーザーコードを変更する</label></input>
							</td>
						</tr>
						<tr>
							<td class="c_td_ipt_caption">
								現在のユーザーコード：
							</td>
							<td class="c_td_ipt_input">
								{$old_user}
							</td>
							<td class="c_td_ipt_remark">
								システム管理者の現在のユーザーコードです。
							</td>
						</tr>
						<tr>
							<td class="c_td_ipt_caption">
								新しいユーザーコード：
							</td>
							<td class="c_td_ipt_input">
								<input type="text" id="id_txt_newuser" class="c_txt_input" name="nm_txt_newuser" disabled />
							</td>
							<td class="c_td_ipt_remark">
								システム管理者の新しいユーザーコードを８文字以内の英数字で入力して下さい。
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="c_td_sectitle">パスワード変更</td>
				<td class="c_td_input">
					<table id="id_tab_password" class="c_tab_input">
						<tr>
							<td colspan=3>
								<input type="checkbox" name="nm_ckb_password" id="id_ckb_password" ><label for="id_ckb_password">パスワードを変更する</label></input>
							</td>
						</tr>
						<tr>
							<td class="c_td_ipt_caption">
								現在のパスワード：
							</td>
							<td class="c_td_ipt_input">
								<input type="password" id="id_txt_oldpass" class="c_txt_input" name="nm_txt_oldpass" disabled />
							</td>
							<td class="c_td_ipt_remark">
								システム管理者の現在のパスワードを入力して下さい。
							</td>
						</tr>
						<tr>
							<td class="c_td_ipt_caption">
								新しいパスワード：
							</td>
							<td class="c_td_ipt_input">
								<input type="password" id="id_txt_newpass" class="c_txt_input" name="nm_txt_newpass" disabled />
							</td>
							<td class="c_td_ipt_remark">
								システム管理者の新しいパスワードを入力して下さい。
							</td>
						</tr>
					</table>
				</td>
			</tr>
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