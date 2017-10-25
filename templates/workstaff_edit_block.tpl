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
<!-- 編集 -->
	<div id="id_div_edit_menu">
		<div id="id_div_edit_table">
			<form id="id_form_main">
				<table id="id_table_edit">
{* 有効/無効選択 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							有効&nbsp;／&nbsp;無効
						</td>
{* 編集ボタン *}
						<td class="c_td_edit_btn_5col" colspan=5 rowspan=2>
{foreach from=$edit_button item=button_item}
							<input class="{$button_item.class}" type="{$button_item.type}" id="{$button_item.id}" value="{$button_item.value}">
{/foreach}
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_radio_value_3col"  colspan=3>
{foreach from=$edit_validity item=validity_item name=fe_edit_validity}
							<input type="radio" id="id_radio_edit_{$smarty.foreach.fe_edit_validity.iteration}" name="VALIDITY_FLAG" value="{$validity_item.value}" {$validity_item.checked}>&nbsp;{$validity_item.itemname}</input>&nbsp;&nbsp;
{/foreach}
						</td>
					</tr>
{* 人員情報 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							人員情報
						</td>
					</tr>
					<tr>
					{* 拠点 *}
						<td class="c_td_edit_name">
							拠点
						</td>
						<td class="c_td_edit_value_col">
							会社
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_search_textval" id="id_txt_edit_company_name" type="text" name="COMPANY_NAME" value="{$edit_table_item.COMPANY_NAME}" title="<ダブルクリックでリスト表示>" readonly></input>
						</td>
						<td class="c_td_edit_value_col">
							拠点名
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_base_name" type="text" name="WORK_BASE_NAME" value="{$edit_table_item.WORK_BASE_NAME}" title="<ダブルクリックでリスト表示>" readonly></input>
							<input class="c_table_td_search" id="id_hid_edit_work_base_id" type="hidden" name="WORK_BASE_ID" value="{$edit_table_item.WORK_BASE_ID}" title=""></input>
						</td>
						<td class="c_td_edit_value_col">
						</td>
					</tr>
					<tr>
					{* 作業者 *}
						<td class="c_td_edit_name" rowspan={if $proc_mode=='insert'}3{else}2{/if}>
							作業者
						</td>
						<td class="c_td_edit_value_col">
							会社
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_company_name" type="text" name="WORK_COMPANY_NAME" value="{$edit_table_item.WORK_COMPANY_NAME}" title="<ダブルクリックでリスト表示>" readonly></input>
						</td>
						<td class="c_td_edit_value_col">
							グループ
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_group_name" type="text" name="WORK_GROUP_NAME" value="{$edit_table_item.WORK_GROUP_NAME}" title="<ダブルクリックでリスト表示>" readonly></input>
						</td>
						<td class="c_td_edit_value_col">
						</td>
					</tr>
					{if $proc_mode=='insert'}
					{* 新規の場合 *}
					<tr>
						<td class="c_td_edit_value_col3" colspan=3>
							<select class="c_sel_edit_user_list" name="" id="id_sel_edit_user_list_all" size=5>
							</select>
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_btn_edit_user_inout" id="id_btn_edit_user_load" type="button" value="リスト読込" /><br>
							<input class="c_btn_edit_user_inout" id="id_btn_edit_user_in" type="button" value="追加 >>" /><br>
							<input class="c_btn_edit_user_inout" id="id_btn_edit_user_out" type="button" value="<< 削除" />
						</td>
						<td class="c_td_edit_value_col3" colspan=3>
							<select class="c_sel_edit_user_list" name="" id="id_sel_edit_user_list_insert" size=5>
							</select>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_value_col7" colspan=7>
							<span class="c_span_explain">
							会社とグループを指定し、「リスト読込」をクリックすると左のリストに所属ユーザが表示されますので、
							ユーザーを選択して「追加>>」ボタンで右のリストに追加します。右のリストにあるユーザー分新規作成されます。
							また、左のリストでダブルクリックするとユーザー情報がポップアップで表示されます。
							</span>
						</td>
					</tr>
					{else}
					{* 更新の場合 *}
					<tr>
						<td class="c_td_edit_value_col">
							作業者名
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_user_name" type="text" name="WORK_USER_NAME" value="{$edit_table_item.WORK_USER_NAME}" title="<ダブルクリックでリスト表示>" readonly></input>
							<input class="c_table_td_search" id="id_hid_edit_work_user_id" type="hidden" name="WORK_USER_ID" value="{$edit_table_item.WORK_USER_ID}" title=""></input>
						</td>
						<td class="c_td_edit_value_col">
						</td>
					</tr>
					{/if}
					<tr>
					{* 承認区分 *}
						<td class="c_td_edit_name">
							承認区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							{if ($ar_approval_division|@count) > 0}
								<select id="id_sel_edit_approval_division_name" class="c_table_td_select" name="APPROVAL_DIVISION" {if $proc_mode=='insert'}disabled{/if}>
									<option value=""></option>
								{foreach from=$ar_approval_division key=l_adv_key item=l_adv_val name=fe_approval_division}
									<option value="{$l_adv_key}"{if $l_adv_key==$edit_table_item.APPROVAL_DIVISION or $l_adv_key==$default_approval_division} selected{/if}>{$l_adv_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
						</td>
					{* キャンセル区分 *}
						<td class="c_td_edit_name">
							キャンセル区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							{if ($ar_cancel_division|@count) > 0}
								<select id="id_sel_edit_cancel_division_name" class="c_table_td_select" name="CANCEL_DIVISION" {if $proc_mode=='insert'}disabled{/if}>
									<option value=""></option>
								{foreach from=$ar_cancel_division key=l_cdv_key item=l_cdv_val name=fe_cancel_division}
									<option value="{$l_cdv_key}"{if $l_cdv_key==$edit_table_item.CANCEL_DIVISION or $l_cdv_key==$default_cancel_division} selected{/if}>{$l_cdv_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
						</td>
					</tr>
					<tr>
					{* 作業費(単価) *}
						<td class="c_td_edit_name">
							作業費(単価)
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_work_unit_price" type="text" name="WORK_UNIT_PRICE" value="{$edit_table_item.WORK_UNIT_PRICE_ORIG}" title="" {if $proc_mode=='insert'}disabled{/if}></input>
						</td>
					{* 作業費表示フラグ *}
						<td class="c_td_edit_name">
							作業費表示
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input type="radio" name="nm_rdb_work_unit_price_display_flag" id="id_rdb_work_unit_price_display_flag_y"{if $proc_mode=='insert'} disabled{/if}{if $edit_table_item.WORK_UNIT_PRICE_DISPLAY_FLAG == "Y"} checked{/if}/>
							<label for="id_rdb_work_unit_price_display_flag_y">表示する</label>
							<input type="radio" name="nm_rdb_work_unit_price_display_flag" id="id_rdb_work_unit_price_display_flag_n"{if $proc_mode=='insert'} disabled{/if}{if $edit_table_item.WORK_UNIT_PRICE_DISPLAY_FLAG != "Y"} checked{/if}/>
							<label for="id_rdb_work_unit_price_display_flag_n">表示しない</label>
						</td>
					</tr>
					<tr>
					{* 出発予定時間 *}
						<td class="c_td_edit_name" rowspan=2>
							出発時間
						</td>
						<td class="c_td_edit_value_col">
							予定時間
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_dispatch_schedule_timet" type="text" name="DISPATCH_SCHEDULE_TIMET" value="{$edit_table_item.DISPATCH_SCHEDULE_TIMET_HHMM}" title="hh:mm" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
						<td class="c_td_edit_value_4col" rowspan=2 colspan=4>
							<span class="c_span_explain">
							日付はhh:mmで入力して下さい。翌日になる場合はam1:00なら25:00といったように、作業日を基準とした時間で入力して下さい。
							</span>
						</td>
					</tr>
					<tr>
					{* 出発時間(作業者) *}
						<td class="c_td_edit_value_col">
							(作業者)
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_dispatch_staff_timet" type="text" name="DISPATCH_STAFF_TIMET" value="{$edit_table_item.DISPATCH_STAFF_TIMET_HHMM}" title="hh:mm" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
					{* 入店予定時間 *}
						<td class="c_td_edit_name" rowspan=3>
							入店時間
						</td>
						<td class="c_td_edit_value_col">
							予定時間
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_entering_schedule_timet" type="text" name="ENTERING_SCHEDULE_TIMET" value="{if $proc_mode=='insert'}{$default_entering_schedule_timet}{else}{$edit_table_item.ENTERING_SCHEDULE_TIMET_HHMM}{/if}" title="hh:mm"></input>
						</td>
					{* 退店予定時間 *}
						<td class="c_td_edit_name" rowspan=3>
							退店時間
						</td>
						<td class="c_td_edit_value_col">
							予定時間
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_leave_schedule_timet" type="text" name="LEAVE_SCHEDULE_TIMET" value="{if $proc_mode=='insert'}{$default_leave_schedule_timet}{else}{$edit_table_item.LEAVE_SCHEDULE_TIMET_HHMM}{/if}" title="hh:mm"></input>
						</td>
					</tr>
					<tr>
					{* 入店時間(作業者) *}
						<td class="c_td_edit_value_col">
							(作業者)
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_entering_staff_timet" type="text" name="ENTERING_STAFF_TIMET" value="{$edit_table_item.ENTERING_STAFF_TIMET_HHMM}" title="hh:mm" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					{* 退店時間(作業者) *}
						<td class="c_td_edit_value_col">
							(作業者)
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_leave_staff_timet" type="text" name="LEAVE_STAFF_TIMET" value="{$edit_table_item.LEAVE_STAFF_TIMET_HHMM}" title="hh:mm" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
					{* 入店時間(管理部) *}
						<td class="c_td_edit_value_col">
							(管理部)
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_entering_manage_timet" type="text" name="ENTERING_MANAGE_TIMET" value="{$edit_table_item.ENTERING_MANAGE_TIMET_HHMM}" title="hh:mm"></input>
						</td>
					{* 退店時間(管理部) *}
						<td class="c_td_edit_value_col">
							(管理部)
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_leave_manage_timet" type="text" name="LEAVE_MANAGE_TIMET" value="{$edit_table_item.LEAVE_MANAGE_TIMET_HHMM}" title="hh:mm"></input>
						</td>
					</tr>
					<tr>
					{* 超過(単価) *}
						<td class="c_td_edit_name">
							超過(単価)
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_excess_amount" type="text" name="EXCESS_AMOUNT" value="{$edit_table_item.EXCESS_AMOUNT}"></input>
						</td>
					{* 交通費 *}
						<td class="c_td_edit_name">
							交通費
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_transport_amount" type="text" name="TRANSPORT_AMOUNT" value="{$edit_table_item.TRANSPORT_AMOUNT}"></input>
						</td>
					</tr>
					<tr>
					{* その他手当 *}
						<td class="c_td_edit_name">
							その他手当
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_other_amount" type="text" name="OTHER_AMOUNT" value="{$edit_table_item.OTHER_AMOUNT}"></input>
						</td>
						<td class="c_td_edit_value_col">
						</td>
						<td class="c_td_edit_value_col">
						</td>
						<td class="c_td_edit_value_col">
						</td>
						<td class="c_td_edit_value_col">
						</td>
					</tr>
					<tr>
					{* 備考 *}
						<td class="c_td_edit_name">
							備考
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<input class="c_table_td_textval" id="id_txt_edit_remarks" type="text" name="REMARKS" value="{$edit_table_item.REMARKS}"></input>
						</td>
					</tr>
					<tr>
					{* 基本時間 *}
						<td class="c_td_edit_name">
							基本時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_basic_time" type="text" name="BASIC_TIME" value="{if $proc_mode=='insert'}{$default_basic_time}{else}{$edit_table_item.BASIC_TIME}{/if}" {if $proc_mode=='insert'}disabled{/if}></input>
						</td>
					{* 休憩時間 *}
						<td class="c_td_edit_name">
							休憩時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_break_time" type="text" name="BREAK_TIME" value="{if $proc_mode=='insert'}{$default_break_time}{else}{$edit_table_item.BREAK_TIME}{/if}" {if $proc_mode=='insert'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
					{* 実作業時間 *}
						<td class="c_td_edit_name">
							実作業時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_real_working_hours" type="text" name="REAL_LABOR_HOURS" value="{$edit_table_item.REAL_LABOR_HOURS}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					{* 実残業時間 *}
						<td class="c_td_edit_name">
							実残業時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_real_overtime_hours" type="text" name="REAL_OVERTIME_HOURS" value="{$edit_table_item.REAL_OVERTIME_HOURS}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
					{* 残業代 *}
						<td class="c_td_edit_name">
							残業代
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_overtime_work_amount" type="text" name="OVERTIME_WORK_AMOUNT" value="{$edit_table_item.OVERTIME_WORK_AMOUNT}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					{* 作業費合計 *}
						<td class="c_td_edit_name">
							作業費合計
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_work_expense_amount_total" type="text" name="WORK_EXPENSE_AMOUNT_TOTAL" value="{$edit_table_item.WORK_EXPENSE_AMOUNT_TOTAL}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
					{* 出金合計 *}
						<td class="c_td_edit_name">
							出金合計
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_payment_amount_total" type="text" name="PAYMENT_AMOUNT_TOTAL" value="{$edit_table_item.PAYMENT_AMOUNT_TOTAL}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					{* 差引支給額 *}
						<td class="c_td_edit_name">
							差引支給額
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_supplied_amount_total" type="text" name="SUPPLIED_AMOUNT_TOTAL" value="{$edit_table_item.SUPPLIED_AMOUNT_TOTAL}" {if $proc_mode=='insert' || $proc_mode=='update'}disabled{/if}></input>
						</td>
					</tr>
{* 隠し項目 *}
					<tr>
						<td class="c_table_td_hidden_col">
{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
							<input type="hidden" class="c_table_td_hidden_val" id="id_hd_{$hidden_items.name|lower}" name="{$hidden_items.name}" value="{$hidden_items.value}" title=""></input>
{/foreach}
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
