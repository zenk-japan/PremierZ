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
<!-- リスト -->
	<div id="id_div_list_menu">
		<div id="id_div_list_menu_title">
			<span>{$list_title}</span>
		</div>
		<div id="id_div_list_menu_page">
			{if $pageitem_visible == 'ON'}
			<table id="id_table_list_menu_page">
				<tr>
					<td id="id_td_list_menu_reccnt">
						<span>該当{$rec_count}件</span>
					</td>
					<td id="id_td_list_menu_pgcnt">
						<span>{$show_page}/{$page_count}ページ</span>
					</td>
					<td id="id_td_list_menu_btnpev">
					{if $prevbtn_visible == 'ON'}
						<input type="button" class="c_btn_list_menu" id="id_btn_prev" value="{$prev_btn_value}" />
					{/if}
					</td>
					<td id="id_td_list_menu_btnnext">
					{if $nextbtn_visible == 'ON'}
						<input type="button" class="c_btn_list_menu" id="id_btn_next" value="{$next_btn_value}" />
					{/if}
					</td>
				</tr>
			</table>
			{/if}
		</div>
		<div id="id_div_list_menu_table">
			<table id="id_table_list_menu">
				<tr id="id_tr_list_menu_top">
					<td class="c_td_list_menu" id="id_td_list_menu_workdate">作業年月</td>
					<td class="c_td_list_menu" id="id_td_list_menu_workuser">作業者名</td>
					{if $workname_display_flag == 'Y'}
					<td class="c_td_list_menu" id="id_td_list_menu_workname">作業名</td>
					{/if}
				</tr>
			{foreach from=$ar_list_menu item=list_menu_item name=fe_list_menu}
				<tr id="id_tr_list_menu{$smarty.foreach.fe_list_menu.iteration}" class="c_tr_list_menu">
					{* 作業年月 *}
					<td class="c_td_list_menu_workdate" id="id_td_list_menu_workdate{$smarty.foreach.fe_list_menu.iteration}">
						{$list_menu_item.WORK_DATE_YM}
					</td>
					{* 作業者 *}
					<td class="c_td_list_menu_workuser" title="{$list_menu_item.WORK_USER_NAME}">
						{$list_menu_item.WORK_USER_NAME_SHORT}
						<input type="hidden" id="id_hdn_work_user_id{$smarty.foreach.fe_list_menu.iteration}" class="c_hdn_work_user_id" value="{$list_menu_item.WORK_USER_ID}"/>
					</td>
					{* 作業名 *}
					{if $workname_display_flag == 'Y'}
					<td class="c_td_list_menu_workname" title="{$list_menu_item.WORK_NAME}">
						{$list_menu_item.WORK_NAME_SHORT}
						<input type="hidden" id="id_hdn_estimate_id{$smarty.foreach.fe_list_menu.iteration}" class="c_hdn_estimate_id" value="{$list_menu_item.ESTIMATE_ID}"/>
					</td>
					{/if}
				</tr>
			{foreachelse}
				<tr>
					<td colspan=4>条件に合う作業が存在しません</td>
				</tr>
			{/foreach}
			</table>
		</div>
	</div>
