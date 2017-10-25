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
<html{$xmlns}>
<head>
	<meta content=text/html;charset=utf-8 http-equiv=Content-Type>
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
					<td id="id_td_mainmenu_worklist" class="c_td_main_menu_selector_now">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_worklist" value=" " />
					</td>
					<td id="id_td_mainmenu_workcomplist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcomplist" value=" " />
					</td>
				</tr>
			</table>
		</div>
		<br>
		<!-- 項目表示 -->
		<div id="id_div_workreport_top_table">
			<table class="c_table_workreport_list">
				<tr id="id_tr_list_menu_top">
					<td class="c_td_list_menu" id="id_td_list_menu_date">作業日</td>
					<td class="c_td_list_menu" id="id_td_list_menu_status">作業状況</td>
					<td class="c_td_list_menu" id="id_td_list_menu_approval">承認状況</td>
					<td class="c_td_list_menu" id="id_td_list_menu_name">作業名</td>
					<td class="c_td_list_menu" id="id_td_list_menu_base">作業場所</td>
					<td class="c_td_list_menu" id="id_td_list_menu_detail"></td>
				</tr>
			</table>
		</div>
		<div id="id_div_workreport_table">
			<table class="c_table_workreport_list">
				{foreach from=$ar_workstaff item=workstaff_rec name=ar_workstaff_menu}
				<tr id="id_tr_list_menu{$smarty.foreach.ar_workstaff_menu.iteration}" class="c_tr_list_menu">
					<td class="c_td_list_menu_date">
						{$workstaff_rec.WORK_DATE}
					</td>
					<td class="c_td_list_menu_status">
						{$workstaff_rec.STAFF_STATUS_NAME}
					</td>
					<td class="c_td_list_menu_approval">
						{if $workstaff_rec.APPROVAL_DIVISION=="AP"}
							承認
						{elseif $workstaff_rec.APPROVAL_DIVISION=="NO"}
							不承諾
						{elseif $workstaff_rec.APPROVAL_DIVISION=="UA"}
							未回答
						{else}
							未確認
						{/if}
					</td>
					<td class="c_td_list_menu_work_name">
						{$workstaff_rec.WORK_NAME}
					</td>
					<td class="c_td_list_menu_work_base">
						{$workstaff_rec.WORK_BASE_NAME}
					</td>
					<td class="c_td_list_menu_detail">
							<input type="button" class="c_btn_list_menu_detail" id="id_btn_list_menu_detail{$smarty.foreach.ar_workstaff_menu.iteration}" value="報告画面" />
							<input type="hidden" id="id_workstaffid_list_menu{$smarty.foreach.ar_workstaff_menu.iteration}" value={$workstaff_rec.WORK_STAFF_ID} />
					</td>
				</tr>
				{foreachelse}
				<tr><td colspan=5>現在ご登録頂いております作業はございません。ご依頼させていただいた際はよろしくお願い申し上げます。</td></tr>
				{/foreach}
			</table>
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
	</div>
</body>
</html>
