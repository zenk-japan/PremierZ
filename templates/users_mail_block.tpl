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
{* メール送信 *}
<!-- メール送信 -->
	<div id="id_div_mail_menu">
		<div id="id_div_mail_table">
			<form id="id_form_main">
				<table id="id_table_mail">
					<tr class="c_tr_section_header">
{* メール送信ボタン *}
						<td colspan="5">&nbsp;</td>
						<td class="c_td_edit_btn_5col" colspan="3" rowspan="2">
{foreach from=$mail_button item=button_item}
							<input class="{$button_item.class}" type="{$button_item.type}" id="{$button_item.id}" value="{$button_item.value}">
{/foreach}
						</td>
					</tr>
{* 送信先アドレス *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							送信先
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							Ｔｏ（自宅） :
						</td>
						<td class="c_td_edit_value_4col" colspan=4>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="TO_HOME" value="{$mail_address_to_home}" title="TO（自宅）に送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							Ｔｏ（携帯） :
						</td>
						<td class="c_td_edit_value_4col" colspan=4>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="TO_MOBILE" value="{$mail_address_to_mobile}" title="TO（携帯）に送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							Ｃｃ :
						</td>
						<td class="c_td_edit_value_4col" colspan=4>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="CC" value="{$mail_address_cc}" title="CCに送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							Ｂｃｃ :
						</td>
						<td class="c_td_edit_value_4col" colspan=4>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="BCC" value="{$mail_mobile_phone_mail}" title="BCCに送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr class="c_tr_section_separator">
						<td class="c_td_section_separator" colspan=8>
							&nbsp;
						</td>
					</tr>
{* 件名 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							件名
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_value_8col" colspan=8>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="SUBJECT" value="{$mail_subject}" title=""></input>
						</td>
					</tr>
{* 本文 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							本文
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_value_8col" colspan=8>
							<textarea class="c_table_td_textareaval" id="id_txt_edit_remarks" name="BODY" title="">{$mail_body}</textarea>
						</td>
					</tr>
{* 隠し項目 *}
					<tr>
						<td class="c_table_td_hidden_col">
{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
							<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
{/foreach}
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id="id_div_hidden">
			<form id="id_form_hidden">
{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
				<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
{/foreach}
			</form>
		</div>
	</div>
