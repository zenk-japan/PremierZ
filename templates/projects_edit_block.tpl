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
{* プロジェクト情報 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							プロジェクト情報
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							見積コード
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_estimate_code" type="text" name="ESTIMATE_CODE" value="{$edit_table_item.ESTIMATE_CODE}" title="<<必須項目>>" {if $proc_mode=='update'}disabled{/if}></input>
							<input class="c_table_td_hidden_val" id="id_txt_edit_estimate_id" type="hidden" name="ESTIMATE_ID" value="{$edit_table_item.ESTIMATE_ID}" ></input>
						</td>
						<td class="c_td_edit_name">
							枝番
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_sub_number" type="text" name="SUB_NUMBER" value="{if $proc_mode=='insert'}{$sub_number_default}{else}{$sub_number_update}{/if}" title="" {if $proc_mode=='insert'}disabled{/if}></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							見積担当者
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_estimate_user_id" type="text" name="ESTIMATE_USER_NAME" value="{if $proc_mode=='insert'}{$estimate_user_name_default}{else}{$edit_table_item.ESTIMATE_USER_NAME}{/if}" {if $proc_mode=='insert'}disabled{else}title="ダブルクリックでリスト表示" readOnly{/if}></input>
						</td>
						<td class="c_td_edit_name">
							見積依頼日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_estimate_request_date" type="text" name="ESTIMATE_REQUEST_DATE" value="{$edit_table_item.ESTIMATE_REQUEST_DATE}" title="YYYY-MM-DD"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業開始予定日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_schedule_from_date" type="text" name="SCHEDULE_FROM_DATE" value="{$edit_table_item.SCHEDULE_FROM_DATE}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_name">
							作業完了予定日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_schedule_to_date" type="text" name="SCHEDULE_TO_DATE" value="{$edit_table_item.SCHEDULE_TO_DATE}" title="YYYY-MM-DD"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業名
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<input class="c_table_td_textval" id="id_txt_edit_work_name" type="text" name="WORK_NAME" value="{$edit_table_item.WORK_NAME}" title=""></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							エンドユーザー会社
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_enduser_company_name" type="text" name="ENDUSER_COMPANY_NAME" value="{$edit_table_item.ENDUSER_COMPANY_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
						<td class="c_td_edit_name">
							エンドユーザー担当者
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_enduser_user_name" type="text" name="ENDUSER_USER_NAME" value="{$edit_table_item.ENDUSER_USER_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							依頼元会社
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_request_company_name" type="text" name="REQUEST_COMPANY_NAME" value="{$edit_table_item.REQUEST_COMPANY_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
						<td class="c_td_edit_name">
							依頼元担当者
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_search_textval" id="id_txt_edit_request_user_name" type="text" name="REQUEST_USER_NAME" value="{$edit_table_item.REQUEST_USER_NAME}" title="ダブルクリックでリスト表示" readOnly></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							見積提出日1
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_submitting_date1" type="text" name="SUBMITTING_DATE1" value="{$edit_table_item.SUBMITTING_DATE1}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_name">
							見積提出日2
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_submitting_date2" type="text" name="SUBMITTING_DATE2" value="{$edit_table_item.SUBMITTING_DATE2}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_name">
							見積提出日3
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_submitting_date3" type="text" name="SUBMITTING_DATE3" value="{$edit_table_item.SUBMITTING_DATE3}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							見積提出日4
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_submitting_date4" type="text" name="SUBMITTING_DATE4" value="{$edit_table_item.SUBMITTING_DATE4}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_name">
							見積提出日5
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_submitting_date5" type="text" name="SUBMITTING_DATE5" value="{$edit_table_item.SUBMITTING_DATE5}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
						<td class="c_td_edit_value_col">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							最終掲示金額
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_final_presentation_amount" type="text" name="FINAL_PRESENTATION_AMOUNT" value="{$edit_table_item.FINAL_PRESENTATION_AMOUNT}" title=""></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							注文区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							{if ($ar_order_division|@count) > 0}
								<select id="id_sel_edit_order_division_name" class="c_table_td_select" name="ORDER_DIVISION">
									<option value=""></option>
								{foreach from=$ar_order_division key=l_od_key item=l_od_val name=fe_order_division}
									<option value="{$l_od_key}"{if $l_od_key==$edit_table_item.ORDER_DIVISION} selected{/if}>{$l_od_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
						</td>
						<td class="c_td_edit_name">
							作業区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							{if ($ar_work_division|@count) > 0}
								<select id="id_sel_edit_work_division_name" class="c_table_td_select" name="WORK_DIVISION">
									<option value=""></option>
								{foreach from=$ar_work_division key=l_wd_key item=l_wd_val name=fe_work_division}
									<option value="{$l_wd_key}"{if $l_wd_key===$edit_table_item.WORK_DIVISION} selected{/if}>{$l_wd_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							作業完了日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_work_completion_date" type="text" name="WORK_COMPLETION_DATE" value="{$edit_table_item.WORK_COMPLETION_DATE}" title="YYYY-MM-DD"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							帳簿入力日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_book_input_date" type="text" name="BOOK_INPUT_DATE" value="{$edit_table_item.BOOK_INPUT_DATE}" title="YYYY-MM-DD"></input>
						</td>
						<td class="c_td_edit_name">
							請求書送付日
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_bill_sending_date" type="text" name="BILL_SENDING_DATE" value="{$edit_table_item.BILL_SENDING_DATE}" title="YYYY-MM-DD"></input>
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
