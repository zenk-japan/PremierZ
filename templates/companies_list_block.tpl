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
					<td class="c_td_list_menu" id="id_td_list_menu_invalid">&nbsp;</td>
					<td class="c_td_list_menu" id="id_td_list_menu_companyname">会社名</td>
				</tr>
			{foreach from=$ar_list_menu item=list_menu_item name=fe_list_menu}
				<tr id="id_tr_list_menu{$smarty.foreach.fe_list_menu.iteration}" class="c_tr_list_menu">
					<td class="c_td_list_menu_invalid">
					{* 有効無効表示 *}
						{if $list_menu_item.VALIDITY_FLAG=="Y"}
						<input id="id_btn_list_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_list_menu_valid" type="text" value=" " readOnly="true" />
						{else}
						<input id="id_btn_list_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_list_menu_invalid" type="text" value=" " readOnly="true" />
						{/if}
						<input id="id_companyid_list_menu{$smarty.foreach.fe_list_menu.iteration}" type="hidden" value="{$list_menu_item.COMPANY_ID}" />
					</td>
					<td class="c_td_list_menu_company" title="{$list_menu_item.COMPANY_NAME}">
						{$list_menu_item.COMPANY_NAME_SHORT}
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan=4>条件に合う会社が存在しません</td>
				</tr>
			{/foreach}
			</table>
		</div>
		<div id="id_div_list_menu_btn">
			<table id="id_table_list_menu_btn">
				<tr>
					<td id="id_td_list_menu_ckb">
						<input type="checkbox" class="c_ckb_list_menu_onlyvalid" id="id_ckb_onlyinvalid" value="" {if $valid_checkstat=="Y"}checked{/if}/>
						<span class="c_span_onlyvalid_expl">有効データのみ表示</span>
					</td>
					<td id="id_td_list_menu_ckb_expl">
						&nbsp;
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	
	