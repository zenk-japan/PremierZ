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
							<input type="radio" id="id_radio_edit_{$smarty.foreach.fe_edit_validity.iteration}" name="VALIDITY_FLAG" value="{$validity_item.value}" {$validity_item.checked}>
								&nbsp;{$validity_item.itemname}
							</input>
							&nbsp;&nbsp;
{/foreach}
						</td>
					</tr>
{* グループ情報 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							グループ情報
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							会社名
						</td>
						<td class="c_td_edit_value_6col" colspan=6>
							<input class="c_table_td_search_textval" id="id_txt_edit_company_name" type="text" name="COMPANY_NAME" value="{$edit_company_name}" title="ダブルクリックで会社一覧を表示" readOnly="true"></input>
						</td>
						<td>
							<input type="button" id="id_txt_clear_company_name" value="値クリア"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							グループ名
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<input class="c_table_td_textval" id="id_txt_edit_group_name" type="text" name="GROUP_NAME" value="{$edit_table_item.GROUP_NAME}" title="<<必須項目>>"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							グループコード
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_group_code" type="text" name="GROUP_CODE" value="{$edit_table_item.GROUP_CODE}" title="<<必須項目>>"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							分類区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<select id="id_pull_classification_division" class="c_table_td_select" name="CLASSIFICATION_DIVISION" title="<<必須項目>>">
{foreach from=$edit_classification_division item=ar_pulldownlist}
								<option value="{$ar_pulldownlist.value}" {$ar_pulldownlist.selected}>{$ar_pulldownlist.itemname}</option>
{/foreach}
							</select><br>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							備考
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<textarea class="c_table_td_textareaval" id="id_txt_edit_remarks" name="REMARKS" title="">{$edit_table_item.REMARKS}</textarea>
						</td>
					</tr>
{* 隠し項目 *}
					<tr>
						<td class="c_table_td_hidden_col">
{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
							<input type="hidden" class="c_table_td_hidden_val" id="id_hd_{$hidden_items.name|lower}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
{/foreach}
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
