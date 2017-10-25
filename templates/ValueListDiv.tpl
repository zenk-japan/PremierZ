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
<div id="id_ext_div_value_list" style="display:none;">
	<div id="id_ext_div_list_title">
		<table>
			<tr>
				<td id="id_ext_td_list_title">
					<span>{$ext_list_title}</span>
				</td>
				<td id="id_ext_td_list_title_btn">
					<input id="id_ext_btn_lv_exit" type="button" value="×" />
				</td>
			</tr>
		</table>
	</div>
	<div id="id_ext_div_list_page_ope">
		{if $ext_pageitem_visible == 'ON'}
		<table id="id_ext_table_list_page_ope">
			<tr>
				<td id="id_ext_td_list_page_reccnt" class="c_ext_td_list_page_ope">
					<span>該当{$ext_rec_count}件</span>
				</td>
				<td id="id_ext_td_list_page_pgcnt" class="c_ext_td_list_page_ope">
					<span>{$ext_show_page}/{$ext_page_count}ページ</span>
				</td>
				<td id="id_ext_td_list_page_btnpev" class="c_ext_td_list_page_ope">
				{if $ext_prevbtn_visible == 'ON'}
					<input type="button" class="c_ext_btn_list_page_ope" id="id_ext_btn_prev" value="{$ext_prev_btn_value}" />
				{/if}
				</td>
				<td id="id_ext_td_list_page_btnnext" class="c_ext_td_list_page_ope">
				{if $ext_nextbtn_visible == 'ON'}
					<input type="button" class="c_ext_btn_list_page_ope" id="id_ext_btn_next" value="{$ext_next_btn_value}" />
				{/if}
				</td>
			</tr>
		</table>
		{/if}
	</div>
	<div id="id_ext_div_list_detail_table">
		<table id="id_ext_table_list_detail">
			<tr id="id_ext_tr_list_detail_top">
				<td class="c_ext_td_list_detail_top" id="id_ext_th_list_detail_top_rdb">&nbsp;</td>
			{foreach from=$ext_ar_list_title item=ext_list_title name=fe_ext_list_title}
				{* 項目名が_IDの列はIDなので表示しない *}
				{if $ext_list_title != "_ID"}
				<td nowrap class="c_ext_td_list_detail_top">{$ext_list_title}</td>
					{assign var="l_exists_hidden" value="0" nocache}
				{else}
					{assign var="l_exists_hidden" value="1" nocache}
				{/if}
			{/foreach}
			</tr>
			{foreach from=$ext_ar_list_value item=ext_list_value name=fe_ext_list_value}
			<tr id="id_ext_tr_list_value{$smarty.foreach.fe_ext_list_value.iteration}" class="c_ext_tr_list_detail">
				<td class="c_ext_td_list_detail_rdb">
				{* ラジオボタン *}
					<input id="id_ext_rdb_list_detail{$smarty.foreach.fe_ext_list_value.iteration}" class="c_ext_rdb_list_detail" name="nm_ext_rdb_list_detail" type="radio" />
				</td>
				{foreach from=$ext_list_value item=ext_list_value_item name=fe_ext_list_value_item}
					{* 一般の値 *}
					{if ($l_exists_hidden == 1 && $smarty.foreach.fe_ext_list_value_item.iteration < ($ext_ar_list_title|@count)) || $l_exists_hidden == 0}
					<td nowrap {if $smarty.foreach.fe_ext_list_value_item.iteration==1}id="id_ext_td_set_item{$smarty.foreach.fe_ext_list_value.iteration}"{/if} class="c_ext_td_list_detail_value" title="{$ext_list_value_item}">
						{if isset($ext_list_value_item)}
							{$ext_list_value_item}{else}&nbsp;
						{/if}
					</td>
					{* ID *}
					{else}
						<input id="id_ext_hidden_item{$smarty.foreach.fe_ext_list_value.iteration}" type="hidden" value="{$ext_list_value_item}" />
					{/if}
				{/foreach}
			</tr>
			{foreachelse}
			<tr>
				<td>該当データが有りませんでした</td>
			</tr>
			{/foreach}
		</table>
	</div>
	<div id="id_ext_div_set_value">
		<table id="id_ext_table_set_value">
			<tr>
				<td id="id_ext_table_set_value_btn">
					{if $ext_ar_list_value|@count>0}
					<input type="button" id="id_ext_btn_set_value" value="セット"/>
					{else}
					&nbsp;
					{/if}
				</td>
				<td id="id_ext_table_set_value_oth">
					&nbsp;
				</td>
			</tr>
		</table>
	</div>
	<div id="id_ext_div_hidden">
		<form id="id_ext_form_hidden">
		{foreach from=$ext_ar_hidden_items item=ext_hidden_items name=fe_hidden}
		<input type="hidden" id="id_ext_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$ext_hidden_items.name}" value="{$ext_hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
</div>