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
<!-- BODY開始 -->
	<div id = "id_div_master">
		<form id="id_form_main">
		<table id="id_table_topline">
		<tr>
			<!-- イメージファイル -->
			<td id="id_td_topline" class="c_td_topmenu">&nbsp;</td>
			<!-- ログインユーザー名 -->
			<td id="id_td_topline_name" class="c_td_topmenu">{$user_auth}:{$user_name} 様</td>
			<td id="id_td_topline_btn1" class="c_td_topmenu c_td_topline_btn">
			<!-- メニュー -->
				<input id="id_btn_topline_backmenu" class="c_btn_topline_menu" type="button" value=" " title="メニュー"/>
			</td>
			<td id="id_td_topline_btn2" class="c_td_topmenu c_td_topline_btn">
			<!-- ログアウト -->
				<input id="id_btn_topline_logout" class="c_btn_topline_menu" type="button" value=" " title="ログアウト"/>
			</td>
		</tr>
		</table>
		<br>
		<!-- ページ移動リンク -->
		<div id="id_div_main_menu">
			<table id="id_table_main_menu">
				<tr>
					<td id="id_td_mainmenu_worklist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_worklist" value=" " />
					</td>
					<td id="id_td_mainmenu_workcomplist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcomplist" value=" " />
					</td>
					<td id="id_td_mainmenu_workcompdetail" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcompdetail" value=" " />
					</td>
					<td id="id_td_mainmenu_workmailform" class="c_td_main_menu_selector_now">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workmailform" value=" " />
					</td>
				</tr>
			</table>
		</div>
		<br>
		<!-- 項目表示 -->
		<div id="id_div_workreport_table">
			<div class="c_div_mailform">TO:&nbsp;<font color="#FF0303">*編集不可</font></div>
			<div class="c_div_mailform"><input type="text" name="nm_to_addr" class="c_textbox_mailform" value="{$mail_address}" readOnly="true"></div>
			<br>
			<div class="c_div_mailform">タイトル:&nbsp;<font color="#FF0303">*編集不可</font></div>
			<div class="c_div_mailform"><input type="text" name="nm_mail_title" class="c_textbox_mailform" value="{$mail_title}" readOnly="true"></div>
			<br>
			<div class="c_div_mailform">本文:</div>
			<div class="c_div_mailform"><textarea id="id_textbox_body" name="nm_mail_text" value=""></textarea></div>
			<br>
			<div class="c_div_mailform"><input type="button" id="id_btn_workmailsend" value="送信" /></div>
		</div>
		<!-- コピーライト -->
		<div id="id_div_copyright">{$txt_copyright}</div>
		<!-- 隠し項目 -->
		<div id="id_div_hidden">
			<form id="id_form_hidden">
			{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
			<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
			{/foreach}
			</form>
		</div>
		</form>
	</div>
</body>
</html>
