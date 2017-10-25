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
					<td id="id_td_mainmenu_worklist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_worklist" value=" " />
					</td>
					<td id="id_td_mainmenu_workdetail" class="c_td_main_menu_selector_now">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workdetail" value=" " />
					</td>
					<td id="id_td_mainmenu_workcomplist" class="c_td_main_menu_selector">
						<input type="button" class="c_btn_mainmenu" id="id_btn_mainmenu_workcomplist" value=" " />
					</td>
				</tr>
			</table>
		</div>
		<br>
		<!-- 項目表示 -->
		<div id="id_div_workreport_table">
			{foreach from=$ar_msg item=l_ar_msg}
				{$l_ar_msg}<br>
			{foreachelse}
			<form action="../workreport/wrworkdetail.php" method="POST">
			<table id="id_table_workreport_list">
				<tr>
					<td class="c_td_detail_name">作業日</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.WORK_DATE)}{$ar_workstaff_rec.WORK_DATE}{else}&nbsp;{/if}</td>
					<td class="c_td_detail_name">作業予定時間</td>
					<td class="c_td_detail_value_2col" colspan=2>
						{if isset($ar_workstaff_rec.ENTERING_SCHEDULE_TIMET)}{$ar_workstaff_rec.ENTERING_SCHEDULE_TIMET}{else}&nbsp;{/if}
						～
						{if isset($ar_workstaff_rec.LEAVE_SCHEDULE_TIMET)}{$ar_workstaff_rec.LEAVE_SCHEDULE_TIMET}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">集合時間</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.AGGREGATE_TIMET)}{$ar_workstaff_rec.AGGREGATE_TIMET}{else}&nbsp;{/if}</td>
					<td class="c_td_detail_name">集合場所</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.AGGREGATE_POINT)}{$ar_workstaff_rec.AGGREGATE_POINT}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">住所</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.WORK_ADDRESS)}{$ar_workstaff_rec.WORK_ADDRESS}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">最寄駅</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.WORK_CLOSEST_STATION)}{$ar_workstaff_rec.WORK_CLOSEST_STATION}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">作業纏め者</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.WORK_ARRANGEMENT_NAME)}{$ar_workstaff_rec.WORK_ARRANGEMENT_NAME}{else}&nbsp;{/if}</td>
					<td class="c_td_detail_name">作業纏め者連絡先</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.WORK_ARRANGEMENT_MOBILE_PHONE)}{$ar_workstaff_rec.WORK_ARRANGEMENT_MOBILE_PHONE}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">作業内容</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.WORK_NAME)}{$ar_workstaff_rec.WORK_NAME}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">顧客名</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.COMPANY_NAME)}{$ar_workstaff_rec.COMPANY_NAME}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">持参品</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.BRINGING_GOODS)}{$ar_workstaff_rec.BRINGING_GOODS}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">服装</td>
					<td class="c_td_detail_value_5col" colspan=5>{if isset($ar_workstaff_rec.CLOTHES)}{$ar_workstaff_rec.CLOTHES}{else}&nbsp;{/if}</td>
				</tr>
					<td class="c_td_detail_name">名乗り</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.INTRODUCE)}{$ar_workstaff_rec.INTRODUCE}{else}&nbsp;{/if}</td>
					<td class="c_td_detail_name">作業費</td>
					<td class="c_td_detail_value_2col" colspan=2>{if isset($ar_workstaff_rec.WORK_UNIT_PRICE) && $ar_workstaff_rec.WORK_UNIT_PRICE_DISPLAY_FLAG == "Y"}{$ar_workstaff_rec.WORK_UNIT_PRICE}{else}&nbsp;{/if}</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">その他</td>
					<td class="c_td_detail_value_5col"  colspan=5>{if isset($ar_workstaff_rec.OTHER_REMARKS)}{$ar_workstaff_rec.OTHER_REMARKS}{else}&nbsp;{/if}</td>
				</tr>
				{if $ar_workstaff_rec.APPROVAL_DIVISION == "AP"}
					<!-- 出発前 -->
					{if $ar_workstaff_rec.STAFF_STATUS == "BD"}
						<tr>
							<td class="c_td_detail_name">登録状況</td>
							<td class="c_td_detail_value_5col"  colspan=5>承認区分：承認済</td>
						</tr>
						<tr>
							<td class="c_td_detail_name_must">出発予定時間</td>
							<td class="c_td_detail_value_5col"  colspan=5>
								<INPUT name="DISPATCH_SCHEDULE_TIMET" type="text" id="id_input_detail_dispatch_schedule_timet"  value="{$ar_workstaff_rec.DISPATCH_SCHEDULE_TIMET}" >&nbsp;ex).hh:mm、hhmm …etc&nbsp;*予定時間変更の場合は必須項目</INPUT>
							</td>
						</tr>
						<tr>
							<td class="c_td_detail_name">注意事項</td>
							<td class="c_td_detail_value_5col" id="id_td_detail_caution_value"  colspan=5>{$caution_msg}</td>
						</tr>
						<tr>
							<td class="c_td_detail_btn">
								<INPUT name="bt_approval" type="submit" class="c_btn_detail_list" value="予定時間変更" /></INPUT>
								<INPUT name="APPROVAL_DIVISION" type="hidden" value="AP"></INPUT>
							</td>
							<td class="c_td_detail_btn">
								<INPUT name="bt_dispatch" type="submit" class="c_btn_detail_list" value="出発登録" /></INPUT>
							</td>
						</tr>
					<!-- 入店前 -->
					{elseif $ar_workstaff_rec.STAFF_STATUS == "BE"}
						<tr>
							<td class="c_td_detail_name">出発予定</td>
							<td class="c_td_detail_value_2col"  colspan=2>{if isset($ar_workstaff_rec.DISPATCH_SCHEDULE_TIMET)}{$ar_workstaff_rec.DISPATCH_SCHEDULE_TIMET}{else}&nbsp;{/if}</td>
							<td class="c_td_detail_name">出発時間</td>
							<td class="c_td_detail_value_2col"  colspan=2>{if isset($ar_workstaff_rec.DISPATCH_STAFF_TIMET)}{$ar_workstaff_rec.DISPATCH_STAFF_TIMET}{else}&nbsp;{/if}</td>
						</tr>
						<tr>
							<td class="c_td_detail_name">注意事項</td>
							<td class="c_td_detail_value_5col" id="id_td_detail_caution_value" colspan=5>{$caution_msg}</td>
						</tr>
						<tr>
							<td class="c_td_detail_btn">
								<INPUT name="bt_entering" type="submit" class="c_btn_detail_list" value="入店登録" /></INPUT>
							</td>
						</tr>
					<!-- 作業中 -->
					{elseif $ar_workstaff_rec.STAFF_STATUS == "NW"}
						<tr>
							<td class="c_td_detail_name">出発予定</td>
							<td class="c_td_detail_value">{if isset($ar_workstaff_rec.DISPATCH_SCHEDULE_TIMET)}{$ar_workstaff_rec.DISPATCH_SCHEDULE_TIMET}{else}&nbsp;{/if}</td>
							<td class="c_td_detail_name">出発時間</td>
							<td class="c_td_detail_value">{if isset($ar_workstaff_rec.DISPATCH_STAFF_TIMET)}{$ar_workstaff_rec.DISPATCH_STAFF_TIMET}{else}&nbsp;{/if}</td>
							<td class="c_td_detail_name">入店時間</td>
							<td class="c_td_detail_value">{if isset($ar_workstaff_rec.ENTERING_STAFF_TIMET)}{$ar_workstaff_rec.ENTERING_STAFF_TIMET}{else}&nbsp;{/if}</td>
						</tr>
						<tr>
							<td class="c_td_detail_name">休憩</td>
							<td class="c_td_detail_value_5col"  colspan=5>
								<select name="BREAK_TIME">
									{foreach from=$ar_break_timelist item=l_ar_break_timelist}
										<option value="{$l_ar_break_timelist.value}" {$l_ar_break_timelist.selected}>{$l_ar_break_timelist.itemname}</option>
									{foreachelse}
										<option value="{$l_ar_break_timelist.value}" {$l_ar_break_timelist.selected}>データがありません</option>
									{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td class="c_td_detail_name">備考</td>
							<td class="c_td_detail_value_5col"  colspan=5>
								<INPUT name="REMARKS" type="text" id="id_input_detail_remarks" value="{$ar_workstaff_rec.REMARKS}"></INPUT>
							</td>
						</tr>
						<tr>
							<td class="c_td_detail_name">注意事項</td>
							<td class="c_td_detail_value_5col" id="id_td_detail_caution_value"  colspan=5>{$caution_msg}</td>
						</tr>
						<tr>
							<td class="c_td_detail_btn">
								<INPUT name="bt_leave" type="submit" class="c_btn_detail_list" value="退店登録" /></INPUT>
							</td>
						</tr>
					{elseif $ar_workstaff_rec.STAFF_STATUS == "WC"}
					{/if}
				{else}
					<tr>
						<td class="c_td_detail_name">承認区分</td>
						<td class="c_td_detail_value_5col"  colspan=5>
							{if $ar_workstaff_rec.APPROVAL_DIVISION == "AP"}
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_ap" type="radio" value="AP" checked>承諾</INPUT>
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_no" type="radio" value="NO">不承諾</INPUT>
							{elseif $ar_workstaff_rec.APPROVAL_DIVISION == "NO"}
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_ap" type="radio" value="AP">承諾</INPUT>
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_no" type="radio" value="NO" checked>不承諾</INPUT>
							{else}
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_ap" type="radio" value="AP" checked>承諾</INPUT>
								<INPUT name="APPROVAL_DIVISION" id="id_input_detail_approval_division_no" type="radio" value="NO">不承諾</INPUT>
							{/if}
						</td>
					</tr>
					<tr>
						<td class="c_td_detail_name_must">出発予定時間</td>
						<td class="c_td_detail_value_5col"  colspan=5>
							<INPUT name="DISPATCH_SCHEDULE_TIMET" type="text" id="id_input_detail_dispatch_schedule_timet"  value="" >&nbsp;ex).hh:mm、hhmm …etc&nbsp;*承諾の場合は必須項目</INPUT>
						</td>
					</tr>
					<tr>
						<td class="c_td_detail_name">注意事項</td>
						<td class="c_td_detail_value_5col" id="id_td_detail_caution_value" colspan=5>{$caution_msg}</td>
					</tr>
					<tr>
						<td class="c_td_detail_btn">
							<INPUT name="bt_approval" type="submit" class="c_btn_detail_list" id="id_btn_detail_entry" value="承認登録" /></INPUT>
						</td>
					</tr>
				{/if}
				<input type="hidden" name="entry_workstaff_switch" value="ON"></input>
			</table>
			{/foreach}
			{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
			<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
			{/foreach}
			</form>
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
