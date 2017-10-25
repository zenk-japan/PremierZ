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
<!-- 明細 -->
	<div id = "id_div_detail_menu">
		{* タイトル *}
		<div id="id_div_detail_title">
			<span>{$detail_title}</span>
		</div>
		{* 明細表 *}
		<div id="id_div_detail_table">
			<table id = "id_table_detail">
				{* 基本プロフィール *}
				<tr class="c_tr_section_header">
					<td class="c_td_section_header" colspan=3>
						基本プロフィール
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						名前
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.NAME)}{$detail_table_item.NAME}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						性別
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.SEX_NAME)}{$detail_table_item.SEX_NAME}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						年齢
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.AGE)}{$detail_table_item.AGE}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						フリガナ
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.KANA)}{$detail_table_item.KANA}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						生年月日
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.BIRTHDATE)}{$detail_table_item.BIRTHDATE}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr class = "c_tr_detail">
					<td class="c_td_detail_name">
						コード
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.USER_CODE)}{$detail_table_item.USER_CODE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						権限
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.AUTHORITY_NAME)}{$detail_table_item.AUTHORITY_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				{* 所属 *}
				<tr class="c_tr_section_header">
					<td class="c_td_section_header" colspan=3>
						所属
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						会社
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.COMPANY_NAME)}{$detail_table_item.COMPANY_NAME}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						グループ
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.GROUP_NAME)}{$detail_table_item.GROUP_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				{* 住所 *}
				<tr class="c_tr_section_header">
					<td class="c_td_section_header" colspan=3>
						住所
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						郵便番号
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.ZIP_CODE)}{$detail_table_item.ZIP_CODE}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						住所
					</td>
					<td class="c_td_detail_value_7col" colspan=7>
						{if isset($detail_table_item.ADDRESS)}{$detail_table_item.ADDRESS}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						最寄駅
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.CLOSEST_STATION)}{$detail_table_item.CLOSEST_STATION}{else}&nbsp;{/if}
					</td>
				</tr>
				{* 連絡先 *}
				<tr class="c_tr_section_header">
					<td class="c_td_section_header" colspan=3>
						連絡先
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						自宅電話
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.HOME_PHONE)}{$detail_table_item.HOME_PHONE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						自宅メール
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.HOME_MAIL)}{$detail_table_item.HOME_MAIL}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						携帯電話
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.MOBILE_PHONE)}{$detail_table_item.MOBILE_PHONE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						携帯メール
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.MOBILE_PHONE_MAIL)}{$detail_table_item.MOBILE_PHONE_MAIL}{else}&nbsp;{/if}
					</td>
				</tr>
				{* 単価 *}
				<tr class="c_tr_section_header">
					<td class="c_td_section_header" colspan=3>
						単価
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						単価
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.UNIT_PRICE)}{$detail_table_item.UNIT_PRICE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						支払区分
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.PAYMENT_DIVISION_NAME)}{$detail_table_item.PAYMENT_DIVISION_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						銀行名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.BANK_NAME)}{$detail_table_item.BANK_NAME}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						支店名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.BRANCH_NAME)}{$detail_table_item.BRANCH_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						口座番号
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.ACCOUNT_NUMBER)}{$detail_table_item.ACCOUNT_NUMBER}{else}&nbsp;{/if}
					</td>
				</tr>
				{* その他 *}
				<tr class="c_tr_section_separator">
					<td class="c_td_section_separator" colspan=8>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						備考
					</td>
					<td class="c_td_detail_value_7col" colspan=7>
						{if isset($detail_table_item.REMARKS)}{$detail_table_item.REMARKS}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						遅延警告
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.ALERT_PERMISSION_FLAG)}
						{* Yなら「通知する」、Nなら「通知しない」と表示 *}
							{if $detail_table_item.ALERT_PERMISSION_FLAG=="Y"}
								通知する
							{else}
								通知しない
							{/if}
						{else}
							&nbsp;
						{/if}
					</td>
				</tr>
			</table>
		</div>
	</div>

