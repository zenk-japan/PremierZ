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
		{* 編集ボタン *}
		<div id="id_div_detail_editbtn">
			<table id="id_table_detail_editbtn">
				<tr>
					<td id="id_td_detail_editbtn_mess">
					{if isset($detail_table_item.VALIDITY_FLAG)}
						{if $detail_table_item.VALIDITY_FLAG=="Y"}&nbsp;{else}※この作業拠点は無効化されているので使用できません。有効にする場合は編集画面で「有効」にチェックを入れて下さい。{/if}
					{else}
						{if isset($detail_table_item.BASE_ID)}
						&nbsp;
						{else}
						作業拠点一覧から作業拠点を選択して下さい。
						{/if}
					{/if}
					</td>
					<td id="id_td_detail_editbtn">
					{if isset($detail_table_item.VALIDITY_FLAG)}
						<input type="button" id="id_btn_detail_editbtn" value="編集" />
					{else}
						&nbsp;
					{/if}
					</td>
				</tr>
			</table>
		</div>
		{* 明細表 *}
		<div id="id_div_detail_table">
			<table id = "id_table_detail">
				<tr>
					<td class="c_td_detail_name">
						拠点名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.BASE_NAME)}{$detail_table_item.BASE_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						拠点コード
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.BASE_CODE)}{$detail_table_item.BASE_CODE}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						会社名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.COMPANY_NAME)}{$detail_table_item.COMPANY_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr class = "c_tr_detail">
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
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.ADDRESS)}{$detail_table_item.ADDRESS}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						電話番号
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.TELEPHONE)}{$detail_table_item.TELEPHONE}{else}&nbsp;{/if}
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
				<tr>
					<td class="c_td_detail_name">
						備考
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.REMARKS)}{$detail_table_item.REMARKS}{else}&nbsp;{/if}
					</td>
				</tr>
			</table>
		</div>
	</div>

