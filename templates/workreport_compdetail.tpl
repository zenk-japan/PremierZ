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
<div id="id_div_master">
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
					<td id="id_td_mainmenu_workcompdetail" class="c_td_main_menu_selector_now">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcompdetail" value=" " />
					</td>
				</tr>
			</table>
		</div>
	<br>
	<div id="id_div_workreport_table">
		<table id="id_table_workreport_list">
			<tr>
				<td class="c_td_detail_name">作業日</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.WORK_DATE)}{$ar_workstaff_rec.WORK_DATE}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">作業名</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.WORK_NAME)}{$ar_workstaff_rec.WORK_NAME}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">作業時間</td>
				<td class="c_td_detail_value">
					{if isset($ar_workstaff_rec.ENTERING_SCHEDULE_TIMET)}{$ar_workstaff_rec.ENTERING_SCHEDULE_TIMET}{else}&nbsp;{/if}
					～
					{if isset($ar_workstaff_rec.LEAVE_SCHEDULE_TIMET)}{$ar_workstaff_rec.LEAVE_SCHEDULE_TIMET}{else}&nbsp;{/if}
				</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">作業場所</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.WORK_BASE_NAME)}{$ar_workstaff_rec.WORK_BASE_NAME}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">作業纏め者</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.WORK_ARRANGEMENT_NAME)}{$ar_workstaff_rec.WORK_ARRANGEMENT_NAME}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">入退店登録時間</td>
				<td class="c_td_detail_value">
					{if isset($ar_workstaff_rec.ENTERING_STAFF_TIMET)}{$ar_workstaff_rec.ENTERING_STAFF_TIMET}{else}&nbsp;{/if}
					～
					{if isset($ar_workstaff_rec.LEAVE_STAFF_TIMET)}{$ar_workstaff_rec.LEAVE_STAFF_TIMET}{else}&nbsp;{/if}
				</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">作業費</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.WORK_EXPENSE_AMOUNT_TOTAL)}{$ar_workstaff_rec.WORK_EXPENSE_AMOUNT_TOTAL}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">残業代</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.OVERTIME_WORK_AMOUNT)}{$ar_workstaff_rec.OVERTIME_WORK_AMOUNT}{else}&nbsp;{/if}</td>
			</tr>
			<tr>
				<td class="c_td_detail_name">交通費</td>
				<td class="c_td_detail_value">{if isset($ar_workstaff_rec.TRANSPORT_AMOUNT)}{$ar_workstaff_rec.TRANSPORT_AMOUNT}{else}&nbsp;{/if}</td>
			</tr>
		</table>
		<font size="-1" color="#FF0303">{$remarks}</font><br>
		<!-- 送信フォーム -->
		<input type="button" id="id_btn_workcorrectform" value="補足/修正送信フォーム" />
	</div>
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
	<!-- 隠し項目 -->
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}" />
		{/foreach}
		</form>
	</div>
</div>
</body>
</html>
