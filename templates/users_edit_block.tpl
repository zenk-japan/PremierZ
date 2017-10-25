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
{* 編集 *}
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
						<td class="c_td_edit_radio_value_3col"  colspan=4>
{foreach from=$edit_validity item=validity_item name=fe_edit_validity}
							<input type="radio" id="id_radio_edit_{$smarty.foreach.fe_edit_validity.iteration}" name="VALIDITY_FLAG" value="{$validity_item.value}" {$validity_item.checked}>
								&nbsp;{$validity_item.itemname}
							</input>
							&nbsp;&nbsp;
{/foreach}
						</td>
					</tr>
{* 基本プロフィール *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							基本プロフィール
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							名前
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_name" type="text" name="NAME" value="{$edit_table_item.NAME}" title="<<必須項目>>"></input>
						</td>
						<td class="c_td_edit_name">
							性別
						</td>
						<td class="c_td_edit_radio_value" colspan=3>
{foreach from=$edit_gender item=gender_item}
							<input type="radio" id="id_radio_edit_{$gender_item.name|lower}" name="{$gender_item.name}" value="{$gender_item.value}" {$gender_item.checked}>&nbsp;{$gender_item.itemname}</input>&nbsp;
{/foreach}
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							フリガナ
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_kana" type="text" name="KANA" value="{$edit_table_item.KANA}" title=""></input>
						</td>
						<td class="c_td_edit_name">
							生年月日
						</td>
						<td class="c_td_edit_value_2col" colspan=2>
							<input class="c_table_td_textval" id="id_txt_edit_birthdate" type="text" name="BIRTHDATE" value="{$edit_table_item.BIRTHDATE}" title=""></input>
						</td>
					</tr>
					<tr class = "c_tr_edit">
						<td class="c_td_edit_name">
							コード
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_user_code" type="text" name="USER_CODE" value="{$edit_table_item.USER_CODE}" title="<<必須項目>>"></input>
						</td>
						<td class="c_td_edit_name">
							権限
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<select id="id_pull_authority" name="AUTHORITY_ID" title="<<必須項目>>">
{foreach from=$edit_authority item=ar_pulldownlist}
								<option value="{$ar_pulldownlist.value}" {$ar_pulldownlist.selected}>{$ar_pulldownlist.itemname}</option>
{/foreach}
							</select><br>
						</td>
					</tr>
					<tr class = "c_tr_edit">
						<td class="c_td_edit_name">
							パスワード
						</td>
						<td class="c_td_edit_value_3col" colspan=3 id="id_td_edit_chngpassword">
							{$passwd_mess1}{$passwd_default_mess}<input class="c_table_td_textval" id="id_txt_edit_password" type="password" name="PASSWORD_OLD" value="{$edit_password}" title=""></input>
							</br>
							{$passwd_mess2}<input class="c_table_td_textval" id="id_txt_edit_password_sub" type="password" name="PASSWORD" value="{$edit_password}" title=""></input>
						</td>
						<td class="c_td_chk_chngpass" colspan=2>
							<input type="checkbox" id="id_ckb_edit_password"><span id="id_span_edit_password_cap"></span></input>
							<input type="hidden" id="id_ipt_hd_edit_password" name="hd_edit_password" value="0"></input>
						</td>
					</tr>
{* 所属 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							所属
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
						<td class="c_td_edit_value_6col" colspan=6>
							<input class="c_table_td_search_textval" id="id_txt_edit_group_name" type="text" name="GROUP_NAME" value="{$edit_group_name}" title="ダブルクリックで会社に所属するグループ一覧を表示" readOnly="true"></input>
						</td>
						<td>
							<input type="button" id="id_txt_clear_group_name" value="値クリア"></input>
						</td>
					</tr>
{* 住所 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							住所
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							郵便番号
						</td>
						<td class="c_td_edit_value_col">
							<input class="c_table_td_textval" id="id_txt_edit_zip_code" type="text" name="ZIP_CODE" value="{$edit_table_item.ZIP_CODE}"  title="nnn-nnnn"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							住所
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<input class="c_table_td_textval" id="id_txt_edit_address" type="text" name="ADDRESS" value="{$edit_table_item.ADDRESS}" title=""></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							最寄駅
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_closest_station" type="text" name="CLOSEST_STATION" value="{$edit_table_item.CLOSEST_STATION}" title=""></input>
						</td>
					</tr>
{* 連絡先 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							連絡先
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							自宅電話
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_home_phone" type="text" name="HOME_PHONE" value="{$edit_table_item.HOME_PHONE}" title="nn-nnnn-nnnn"></input>
						</td>
						<td class="c_td_edit_name">
							自宅メール
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_home_mail" type="text" name="HOME_MAIL" value="{$edit_table_item.HOME_MAIL}" title="xxxxx@xxx.xx.xx"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							携帯電話
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_mobile_phone" type="text" name="MOBILE_PHONE" value="{$edit_table_item.MOBILE_PHONE}" title="nnn-nnnn-nnnn"></input>
						</td>
						<td class="c_td_edit_name">
							携帯メール
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_mobile_phone_mail" type="text" name="MOBILE_PHONE_MAIL" value="{$edit_table_item.MOBILE_PHONE_MAIL}" title="xxxxx@xxx.xx.xx"></input>
						</td>
					</tr>
{* 単価 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							単価
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							単価
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_unit_price" type="text" name="UNIT_PRICE" value="{$edit_table_item.UNIT_PRICE}"></input>
						</td>
						<td class="c_td_edit_name">
							支払区分
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<select id="id_pull_payment_division" name="PAYMENT_DIVISION">
								<option value="" >指定なし</option>
{foreach from=$edit_payment_division item=ar_pulldownlist}
								<option value="{$ar_pulldownlist.value}" {$ar_pulldownlist.selected}>{$ar_pulldownlist.itemname}</option>
{/foreach}
							</select><br>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							銀行名
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_bank_name" type="text" name="BANK_NAME" value="{$edit_table_item.BANK_NAME}"></input>
						</td>
						<td class="c_td_edit_name">
							支店名
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_branch_name" type="text" name="BRANCH_NAME" value="{$edit_table_item.BRANCH_NAME}"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							口座番号
						</td>
						<td class="c_td_edit_value_3col" colspan=3>
							<input class="c_table_td_textval" id="id_txt_edit_account_number" type="text" name="ACCOUNT_NUMBER" value="{$edit_table_item.ACCOUNT_NUMBER}"></input>
						</td>
					</tr>
{* その他 *}
					<tr>
						<td class="c_td_edit_name">
							備考
						</td>
						<td class="c_td_edit_value_7col" colspan=7>
							<textarea class="c_table_td_textareaval" id="id_txt_edit_remarks" name="REMARKS">{$edit_table_item.REMARKS}</textarea>
						</td>
					</tr>
					<tr>
						<td class="c_td_edit_name">
							遅延警告
						</td>
						<td class="c_td_edit_radio_value_3col" colspan=3>
{foreach from=$edit_permission item=permission_item}
							<input type="radio" id="id_radio_{$permission_item.name|lower}" name="{$permission_item.name}" value="{$permission_item.value}" {$permission_item.checked}>&nbsp;{$permission_item.itemname}&nbsp;</input>&nbsp;
{/foreach}
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
	</div>
