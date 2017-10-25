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
	<table id="id_table_topline">
		<!-- イメージファイル -->
		<td id="id_td_topline" class="c_td_topmenu">&nbsp;</td>
		<!-- ログインユーザー名 -->
		<td id="id_td_topline_name" class="c_td_topmenu">{$user_auth}:{$user_name} 様</td>
		<td id="id_td_topline_btn1" class="c_td_topmenu c_td_topline_btn">
		<!-- メニュー -->
			&nbsp;
		</td>
		<td id="id_td_topline_btn2" class="c_td_topmenu c_td_topline_btn">
		<!-- ログアウト -->
			&nbsp;
		</td>
	</table>
	<br>
	<!-- ページ移動リンク -->
		<div id="id_div_main_menu">
			<table id="id_table_main_menu">
				<tr>
					<td id="id_td_mainmenu_worklist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_worklist" value=" " / onClick="movePage(this, 'wrworkcontents.php')">
					</td>
					<td id="id_td_mainmenu_workcomplist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcomplist" value=" " / onClick="movePage(this, 'wrcompletionlist.php')">
					</td>
					<td id="id_td_mainmenu_workcompdetail" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcompdetail" value=" " / onClick="movePage(this, 'wrcompletiondetail.php')">
					</td>
					<td id="id_td_mainmenu_workmailform" class="c_td_main_menu_selector_now">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workmailform" value=" " />
					</td>
				</tr>
			</table>
		</div>
	<br>
<!--	<DIV>-->
	<form method="POST">
	<!-- メッセージ本文 -->
		{$main_message}
		<br>
		<br>
	<!-- ボタン -->
		<input type="submit" onClick="movePage(this, 'wrcompletiondetail.php')" value="OK"></input><br>
	<!-- パラメータ -->
	{foreach from=$ar_param item=param_item}
		<input type="hidden" name="{$param_item.name}" value="{$param_item.value}"></input>
	{/foreach}
	</form>
<!--	</DIV>-->
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
<div>
</body>
</html>
