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
{* キャンセルボタン *}
					<tr class="c_tr_section_header">
						<td class="c_td_section_header">
							対象excelファイルをアップロード
						</td>
						<td class="c_td_edit_btn_5col">
							<input class="c_btn_edit_menu" type="button" id="id_btn_cancel" value="キャンセル">
						</td>
					</tr>
{* ファイルアップロード *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_btn_4col">
						</td>
						<td class="c_td_edit_btn_4col">
							<input id="id_btn_upload" type="file" name="userfile" method="POST" enctype="multipart/form-data">
							<div id="result"></div>
						</td>
					</tr>
{* インポート対象選択チェックボックス *}
					<tr>
						<td class="c_td_section_header">
							インポートするシートを選択
						</td>
						<td class="c_td_insert_ckb">
{foreach from=$insert_checkbox item=chk_item}
							<input type="{$chk_item.type}" id="{$chk_item.id}" name="{$chk_item.name}" value="{$chk_item.value}" checked>{$chk_item.label}
{/foreach}
						</td>
					</tr>
{* ファイルインポート *}
					<tr class="c_tr_section_header">
						<td>
							ファイルインポート
						</td>
						<td class="c_td_edit_btn_4col">
{foreach from=$edit_button item=button_item}
							<input class="{$button_item.class}" type="{$button_item.type}" id="{$button_item.id}" value="{$button_item.value}">
{/foreach}
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
	<div id="id_xls_print">
	</div>
