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
						{if $detail_table_item.VALIDITY_FLAG=="Y"}&nbsp;{else}※このグループは無効化されているので使用できません。有効にする場合は編集画面で「有効」にチェックを入れて下さい。{/if}
					{else}
						{if isset($detail_table_item.GROUP_ID)}
						&nbsp;
						{else}
						グループ一覧からグループを選択して下さい。
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
						グループ名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.GROUP_NAME) && $detail_table_item.GROUP_NAME!=""}{$detail_table_item.GROUP_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						グループコード
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.GROUP_CODE) && $detail_table_item.GROUP_CODE!=""}{$detail_table_item.GROUP_CODE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						分類区分
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.CLASSIFICATION_DIVISION_NAME) && $detail_table_item.CLASSIFICATION_DIVISION_NAME!=""}{$detail_table_item.CLASSIFICATION_DIVISION_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						会社名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.COMPANY_NAME) && $detail_table_item.COMPANY_NAME!=""}{$detail_table_item.COMPANY_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr class = "c_tr_detail">
					<td class="c_td_detail_name">
						備考
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.REMARKS) && $detail_table_item.REMARKS!=""}{$detail_table_item.REMARKS}{else}&nbsp;{/if}
					</td>
				</tr>
			</table>
		</div>
		{* メンバー *}
		<div id="id_div_detail_members">
			{* タイトル *}
			<div id="id_div_detail_members_title">
				<table id="id_table_detail_menbers_title">
					<tr>
						<td id="id_dt_detail_menbers_title_cap">
							グループメンバー
						</td>
						<td id="id_dt_detail_menbers_title_btn">
							<input id="id_btn_detail_members_save" type="button" value="メンバー保存"/>
						</td>
					</tr>
				</table>
			</div>
			{* 一覧表 *}
			<div id="id_div_detail_members_edit">
				<table id="id_table_detail_members_edit">
					<tr>
						<th class="c_th_detail_group_members_header">
							現在のメンバー(<span id="id_span_group_member_count">{$ar_group_member|@count}</span>名)
						</th>
						<th>
							&nbsp;
						</th>
						<th class="c_th_detail_group_members_header">
							社内の他メンバー(【】内は現所属)
						</th>
					</tr>
					<tr>
						<td class="c_td_detail_members_list">
						{* 現在のメンバー *}
							<select size=14 id="id_select_detail_menbers">
							{foreach from=$ar_group_member item=group_member_item name=fe_group_member}
								<option class="c_opt_select_detail_members" value="{$group_member_item.USER_ID}">{if $group_member_item.VALIDITY_FLAG=='N'}[無効]&nbsp;{/if}{$group_member_item.NAME_SHORT}</option>
							{/foreach}
							</select>
						</td>
						<td class="c_td_detail_members_arrow">
						{* 矢印 *}
						{if isset($detail_table_item.GROUP_NAME) && $detail_table_item.GROUP_NAME!=""}
							<input id="id_btn_detail_members_add" class="c_btn_detail_members_arrow" type="button" value="<< 追加"/><br>
							<input id="id_btn_detail_members_remove" class="c_btn_detail_members_arrow" type="button" value="削除 >>"/>
						{else}
							&nbsp;
						{/if}
						</td>
						<td>
						{* その他のメンバー *}
							<select size=14 id="id_select_detail_others">
							{foreach from=$ar_other_member item=other_member_item name=fe_other_member}
								<option class="c_opt_select_detail_others" value="{$other_member_item.USER_ID}">{if $other_member_item.VALIDITY_FLAG=='N'}[無効]&nbsp;{/if}【{if isset($other_member_item.GROUP_ID) && $other_member_item.GROUP_ID>0}{$other_member_item.GROUP_NAME_SHORT}{else}※所属なし※{/if}】：{$other_member_item.NAME_SHORT}</option>
							{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

