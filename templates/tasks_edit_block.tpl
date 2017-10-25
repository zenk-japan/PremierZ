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
{* 作業情報 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							作業情報
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name" rowspan=3>
							作業日
						</td>
						<td colspan=2>
							<input type="radio" name="nm_rd_copytype" class="c_rd_copytype_single" id="id_rd_copytype_single" value="S" checked><label for="id_rd_copytype_single">１日分{if $proc_mode=='insert'}作成{else}更新{/if}</label></input>
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_work_date" type="text" name="WORK_DATE" value="{if $proc_mode=='insert'}{$default_work_date}{else}{$edit_table_item.WORK_DATE}{/if}" title="<<必須項目>>"></input>
							<input class="c_table_td_hidden_val" id="id_txt_edit_work_content_id" type="hidden" name="WORK_CONTENT_ID" value="{$edit_table_item.WORK_CONTENT_ID}" ></input>
						</td>
					</tr>
					<tr>
						<td rowspan=2  colspan=2>
							<input type="radio" name="nm_rd_copytype" class="c_rd_copytype_single" id="id_rd_copytype_multiple" value="M" ><label for="id_rd_copytype_multiple">設定期間分{if $proc_mode=='insert'}作成{else}更新{/if}</label></input>
							{if $proc_mode=='update'}<br>※同一作業コードを更新。作業が無い日は新規作成(人員もコピー)。{/if}
						</td>
						<td class="c_td_edit_option_cap">
							期間指定
						</td>
						<td class="c_td_edit_option_edit" colspan=4>
							<input disabled type="text" class="c_txt_edit_option_edit" id="id_txt_copy_from" title="<<必須項目>>"/>から、
							<input disabled type="text" class="c_txt_edit_option_edit" id="id_txt_copy_to" title="<<必須項目>>"/>まで
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_option_cap">
							曜日指定
						</td>
						<td class="c_td_edit_option_edit" colspan=4>
							<input disabled type="checkbox" name="" id="id_chk_copy_day1" class="c_chk_copy_day"><label for="id_chk_copy_day1">日</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day2" class="c_chk_copy_day" checked><label for="id_chk_copy_day2">月</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day3" class="c_chk_copy_day" checked><label for="id_chk_copy_day3">火</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day4" class="c_chk_copy_day" checked><label for="id_chk_copy_day4">水</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day5" class="c_chk_copy_day" checked><label for="id_chk_copy_day5">木</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day6" class="c_chk_copy_day" checked><label for="id_chk_copy_day6">金</label></input>
							<input disabled type="checkbox" name="" id="id_chk_copy_day7" class="c_chk_copy_day"><label for="id_chk_copy_day7">土</label></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業コード
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_work_content_code" type="text" name="WORK_CONTENT_CODE" value="{if $proc_mode=='insert'}{$default_work_content_code}{else}{$edit_table_item.WORK_CONTENT_CODE}{/if}" title="<<必須項目>>"></input>
							{*更新用の旧コード*}
							{if $proc_mode=='update'}
								<input class="c_hd_edit_hidden" id="id_hd_edit_old_work_content_code" type="hidden" name="old_work_content_code" value="{$edit_table_item.WORK_CONTENT_CODE}" />
							{/if}
						</td>
						<td class="c_td_edit_name">
							作業ステータス
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							{if ($ar_work_status|@count) > 0}
								<select id="id_sel_edit_work_status_name" class="c_table_td_select" name="WORK_STATUS">
									<option value=""></option>
								{foreach from=$ar_work_status key=l_ws_key item=l_ws_val name=fe_work_status}
									<option value="{$l_ws_key}"{if $l_ws_key==$edit_table_item.WORK_STATUS or $l_ws_key==$default_work_status} selected{/if}>{$l_ws_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							入店予定時刻
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_default_entering_schedule_timet" type="text" name="DEFAULT_ENTERING_SCHEDULE_TIMET" value="{$edit_table_item.DEFAULT_ENTERING_SCHEDULE_TIMET}" title="HH:MM"></input>
						</td>
						<td class="c_td_edit_name">
							退店予定時刻
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_default_leave_schedule_timet" type="text" name="DEFAULT_LEAVE_SCHEDULE_TIMET" value="{$edit_table_item.DEFAULT_LEAVE_SCHEDULE_TIMET}" title="HH:MM"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							規定基本時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_default_working_time" type="text" name="DEFAULT_WORKING_TIME" value="{if $proc_mode=='insert'}8.00{else}{$edit_table_item.DEFAULT_WORKING_TIME}{/if}"></input>
						</td>
						<td class="c_td_edit_name">
							規定休憩時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_default_break_time" type="text" name="DEFAULT_BREAK_TIME" value="{if $proc_mode=='insert'}1.00{else}{$edit_table_item.DEFAULT_BREAK_TIME}{/if}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							集合時間
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_aggregate_timet" type="text" name="AGGREGATE_TIMET" value="{$edit_table_item.AGGREGATE_TIMET}" title="HH:MM"></input>
						</td>
						<td class="c_td_edit_name">
							集合場所
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_aggregate_point" type="text" name="AGGREGATE_POINT" value="{$edit_table_item.AGGREGATE_POINT}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業纏め者所属会社
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_arrangement_company_name" type="text" name="WORK_ARRANGEMENT_COMPANY_NAME" value="{$edit_table_item.WORK_ARRANGEMENT_COMPANY_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
						<td class="c_td_edit_name">
							作業纏め者
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_work_arrangement_user_name" type="text" name="WORK_ARRANGEMENT_USER_NAME" value="{$edit_table_item.WORK_ARRANGEMENT_USER_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業内容詳細
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<textarea class="c_table_td_textareaval" id="id_txt_edit_work_content_details" name="WORK_CONTENT_DETAILS" title="" style="height:120px;">{$edit_table_item.WORK_CONTENT_DETAILS}</textarea>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							持参品
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_bringing_goods" type="text" name="BRINGING_GOODS" value="{$edit_table_item.BRINGING_GOODS}"></input>
						</td>
						<td class="c_td_edit_name">
							服装
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_clothes" type="text" name="CLOTHES" value="{$edit_table_item.CLOTHES}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							名乗り
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_introduce" type="text" name="INTRODUCE" value="{$edit_table_item.INTRODUCE}"></input>
						</td>
						<td class="c_td_edit_name">
							交通費備考
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_transport_amount_remarks" type="text" name="TRANSPORT_AMOUNT_REMARKS" value="{$edit_table_item.TRANSPORT_AMOUNT_REMARKS}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							その他備考
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_transport_amount_remarks" type="text" name="TRANSPORT_AMOUNT_REMARKS" value="{$edit_table_item.TRANSPORT_AMOUNT_REMARKS}"></input>
						</td>
						<td class="c_td_edit_name">
							その他費用
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_other_cost" type="text" name="OTHER_COST" value="{$edit_table_item.OTHER_COST}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							超過金額
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_excess_amount" type="text" name="EXCESS_AMOUNT" value="{$edit_table_item.EXCESS_AMOUNT}"></input>
						</td>
						<td class="c_td_edit_name">
							超過精算
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input type="radio" value="Y" name="EXCESS_LIQUIDATION_FLAG" id="id_txt_edit_excess_liquidation_flag_y"{if $proc_mode!='insert'}{if $edit_table_item.EXCESS_LIQUIDATION_FLAG == "Y"} checked{/if}{/if}/>
							<label for="id_txt_edit_excess_liquidation_flag_y">する</label>
							<input type="radio" value="N" name="EXCESS_LIQUIDATION_FLAG" id="id_txt_edit_excess_liquidation_flag_n"{if $proc_mode=='insert'} checked{else}{if $edit_table_item.EXCESS_LIQUIDATION_FLAG != "Y"} checked{/if}{/if}/>
							<label for="id_txt_edit_excess_liquidation_flag_n">しない</label>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							キャンセル料
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_cancel_charge" type="text" name="CANCEL_CHARGE" value="{$edit_table_item.CANCEL_CHARGE}"></input>
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
