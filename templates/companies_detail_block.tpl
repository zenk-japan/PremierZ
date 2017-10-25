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
						{if $detail_table_item.VALIDITY_FLAG=="Y"}&nbsp;{else}※この会社は無効化されているので使用できません。有効にする場合は編集画面で「有効」にチェックを入れて下さい。{/if}
					{else}
						{if isset($detail_table_item.COMPANY_ID)}
						&nbsp;
						{else}
						会社一覧から会社を選択して下さい。
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
						会社名
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.COMPANY_NAME) && $detail_table_item.COMPANY_NAME!=''}{$detail_table_item.COMPANY_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						会社コード
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.COMPANY_CODE) && $detail_table_item.COMPANY_CODE!=''}{$detail_table_item.COMPANY_CODE}{else}&nbsp;{/if}
					</td>
					<td class="c_td_detail_name">
						分類区分
					</td>
					<td class="c_td_detail_value">
						{if isset($detail_table_item.COMP_CLASS_NAME) && $detail_table_item.COMP_CLASS_NAME!=''}{$detail_table_item.COMP_CLASS_NAME}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr class = "c_tr_detail">
					<td class="c_td_detail_name">
						締日
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.WELL_SET_DAY) && $detail_table_item.WELL_SET_DAY!=''}{$detail_table_item.WELL_SET_DAY}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						入金日
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.PAYMENT_DAY) && $detail_table_item.PAYMENT_DAY!=''}{$detail_table_item.PAYMENT_DAY}{else}&nbsp;{/if}
					</td>
				</tr>
				<tr>
					<td class="c_td_detail_name">
						備考
					</td>
					<td class="c_td_detail_value_3col" colspan=3>
						{if isset($detail_table_item.REMARKS) && $detail_table_item.REMARKS!=''}{$detail_table_item.REMARKS}{else}&nbsp;{/if}
					</td>
				</tr>
			</table>
		{*所属リスト*}
		<div id="id_div_affiliation_tab">
			<table id="id_table_affiliation_tab">
				<td id="id_td_affiliation_tab_group" class="c_td_affiliation_tab">
					<input id="id_btn_affiliation_tab_group" class="c_btn_affiliation_tab" type="button" value="所属グループ"/>
				</td>
				<td id="id_td_affiliation_tab_user" class="c_td_affiliation_tab">
					<input id="id_btn_affiliation_tab_user" class="c_btn_affiliation_tab" type="button" value="所属ユーザー"/>
				</td>
				<td id="id_td_affiliation_tab_workbase" class="c_td_affiliation_tab">
					<input id="id_btn_affiliation_tab_workbase" class="c_btn_affiliation_tab" type="button" value="所属作業拠点"/>
				</td>
				<td class="c_td_affiliation_tab_sp">&nbsp;</td>
			</table>
		</div>
		{*所属グループ*}
		<div id="id_div_affiliation_group_table" class="c_div_comp_dtail_tab_outer">
			<div id="id_div_affiliation_menu_page" class="c_div_comp_dtail_tab_pageope">
				<table id="id_table_affiliation_menu_page"  class="c_table_comp_detail_afftitle">
					<tr>
						<td class="c_td_comp_detail_affmenu_listtitle">
							{$comp_detail_affmenu_listtitle_group}
						</td>
						<td class="c_td_comp_detail_afftitle_btn">
							<input type="button" id="id_btn_affiliation_insert" value="グループ新規作成" />
						</td>
					</tr>
				</table>
				<table class="c_table_comp_detail_affmenu">
					{if $group_pageitem_visible == 'ON'}
					<tr class="c_tr_affiliation_page_menu">
						<td id="id_td_affiliation_menu_reccnt" class="c_td_comp_detail_affmenu">
							<span>該当{$group_rec_count}件</span>
						</td>
						<td id="id_td_affiliation_menu_pgcnt" class="c_td_comp_detail_affmenu">
							<span>{$group_show_page}/{$group_page_count}ページ</span>
						</td>
						<td id="id_td_affiliation_menu_btnpev" class="c_td_comp_detail_affmenu_prev">
						{if $group_prev_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_group_menu" id="id_btn_affiliation_group_prev" value="{$group_prev_btn_value}" />
						{/if}
						</td>
						<td id="id_td_affiliation_menu_btnnext" class="c_td_comp_detail_affmenu_next">
						{if $group_next_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_group_menu" id="id_btn_affiliation_group_next" value="{$group_next_btn_value}" />
						{/if}
						</td>
					</tr>
					{/if}
				</table>
			</div>
			{* 以下グループリスト表 *}
			<div id="id_div_affiliation_menu_table" class="c_div_comp_detail_tab_dtltable">
				<table id="id_table_affiliation_menu" class="c_table_comp_detail_list">
					<tr id="id_tr_affiliation_menu_top" class="c_tr_comp_detail_list_top">
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_invalid">&nbsp;</td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_group_code">グループコード</td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_group_name">グループ名</td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_classification_division_name">分類区分</td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_remark">備考</td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_detail"></td>
						<td class="c_td_affiliation_menu" id="id_td_affiliation_menu_update"></td>
					</tr>
				{foreach from=$ar_affiliation_menu item=affiliation_menu_item name=fe_affiliation_menu}
					<tr id="id_tr_affiliation_menu{$smarty.foreach.fe_affiliation_menu.iteration}" class="c_tr_affiliation_menu">
						<td class="c_td_affiliation_menu_invalid">
						{* 有効無効表示 *}
						{if $affiliation_menu_item.VALIDITY_FLAG=="Y"}
							<input id="id_btn_affiliation_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_valid" type="text" value=" " readOnly="true" />
						{else}
							<input id="id_btn_affiliation_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_invalid" type="text" value=" " readOnly="true" />
						{/if}
							<input id="id_groupid_affiliation_menu{$smarty.foreach.fe_affiliation_menu.iteration}" type="hidden" value="{$affiliation_menu_item.GROUP_ID}" />
						</td>
						<td class="c_td_affiliation_menu_group_code" title="{$affiliation_menu_item.GROUP_CODE}">
							{$affiliation_menu_item.GROUP_CODE}
						</td>
						<td class="c_td_affiliation_menu_group_name" title="{$affiliation_menu_item.GROUP_NAME}">
							{$affiliation_menu_item.GROUP_NAME_SHORT}
						</td>
						<td class="c_td_affiliation_menu_classification_division_name" title="{$affiliation_menu_item.CLASSIFICATION_DIVISION_NAME}">
							{$affiliation_menu_item.CLASSIFICATION_DIVISION_NAME_SHORT}
						</td>
						<td class="c_td_affiliation_menu_remark" title="{$affiliation_menu_item.REMARKS}">
							{$affiliation_menu_item.REMARKS_SHORT}
						</td>
						<td class="c_td_affiliation_menu_detail">
							<input type="button" class="c_btn_affiliation_group_detail" id="id_btn_affiliation_detail{$smarty.foreach.fe_affiliation_menu.iteration}" value="詳細" />
						</td>
						<td class="c_td_affiliation_menu_update">
							<input type="button" class="c_btn_affiliation_group_update" id="id_btn_affiliation_update{$smarty.foreach.fe_affiliation_menu.iteration}" value="編集" />
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan=4>所属するグループが存在しません</td>
					</tr>
				{/foreach}
				</table>
			</div>
			<div id="id_div_affiliation_menu_btn">
				<table id="id_table_affiliation_menu_btn">
					<tr>	
						<td id="id_td_affiliation_menu_ckb">
							<input type="checkbox" class="c_ckb_affiliation_menu_onlyvalid" id="id_ckb_affiliation_onlyinvalid" value="" {if $valid_group_checkstat=="Y"}checked{/if}/>
							<span class="c_span_affiliation_onlyvalid_expl">有効データのみ表示</span>
						</td>
						<td id="id_td_affiliation_menu_ckb_expl">
							&nbsp;
						</td>
					</tr>
				</table>
			</div>
		</div>
		{*所属ユーザー*}
		<div id="id_div_affiliation_user_table" class="c_div_comp_dtail_tab_outer">
			<div id="id_div_affiliation_user_menu_page" class="c_div_comp_dtail_tab_pageope">
				<table id="id_table_affiliation_user_menu_page" class="c_table_comp_detail_afftitle">
					<tr>
						<td class="c_td_comp_detail_affmenu_listtitle">
							{$comp_detail_affmenu_listtitle_user}
						</td>
						{if $auth_code=="ADMIN" || $auth_code=="GENERAL1" || $auth_code=="GENERAL2"}
						<td class="c_td_comp_detail_afftitle_btn">
							<input type="button" id="id_btn_affiliation_user_insert" value="ユーザー新規作成" />
						</td>
						{/if}
					</tr>
				</table>
				<table class="c_table_comp_detail_affmenu">
					{if $user_pageitem_visible == 'ON'}
					<tr class="c_tr_affiliation_page_menu">
						<td id="id_td_affiliation_user_menu_reccnt" class="c_td_comp_detail_affmenu">
							<span>該当{$user_rec_count}件</span>
						</td>
						<td id="id_td_affiliation_user_menu_pgcnt" class="c_td_comp_detail_affmenu">
							<span>{$user_show_page}/{$user_page_count}ページ</span>
						</td>
						<td id="id_td_affiliation_user_menu_btnpev" class="c_td_comp_detail_affmenu_prev">
						{if $user_prev_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_user_menu" id="id_btn_affiliation_user_prev" value="{$user_prev_btn_value}" />
						{/if}
						</td>
						<td id="id_td_affiliation_user_menu_btnnext" class="c_td_comp_detail_affmenu_next">
						{if $user_next_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_user_menu" id="id_btn_affiliation_user_next" value="{$user_next_btn_value}" />
						{/if}
						</td>
					</tr>
					{/if}
				</table>
			</div>
			{* 以下ユーザーリスト表 *}
			<div id="id_div_affiliation_user_menu_table" class="c_div_comp_detail_tab_dtltable">
				<table id="id_table_affiliation_user_menu" class="c_table_comp_detail_list">
					<tr id="id_tr_affiliation_user_menu_top" class="c_tr_comp_detail_list_top">
						<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_invalid">&nbsp;</td>
						<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_name">ユーザー名</td>
						<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_group">所属グループ</td>
						<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_remark">備考</td>
						<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_detail"></td>
						{if $auth_code=="ADMIN" || $auth_code=="GENERAL1" || $auth_code=="GENERAL2"}<td class="c_td_affiliation_user_menu" id="id_td_affiliation_user_menu_update"></td>{/if}
					</tr>
				{foreach from=$ar_affiliation_user_menu item=affiliation_user_menu_item name=fe_affiliation_user_menu}
					<tr id="id_tr_affiliation_user_menu{$smarty.foreach.fe_affiliation_user_menu.iteration}" class="c_tr_affiliation_user_menu">
						<td class="c_td_affiliation_menu_invalid">
						{* 有効無効表示 *}
						{if $affiliation_user_menu_item.VALIDITY_FLAG=="Y"}
							<input id="id_btn_affiliation_user_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_valid" type="text" value=" " readOnly="true" />
						{else}
							<input id="id_btn_affiliation_user_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_invalid" type="text" value=" " readOnly="true" />
						{/if}
							<input id="id_userid_affiliation_menu{$smarty.foreach.fe_affiliation_user_menu.iteration}" type="hidden" value="{$affiliation_user_menu_item.USER_ID}" />
						</td>
						<td class="c_td_affiliation_menu_user_name" title="{$affiliation_user_menu_item.NAME}">
							{$affiliation_user_menu_item.NAME_SHORT}
						</td>
						<td class="c_td_affiliation_menu_user_group" title="{$affiliation_user_menu_item.GROUP_NAME}">
							{$affiliation_user_menu_item.GROUP_NAME_SHORT}
						</td>
						<td class="c_td_affiliation_menu_user_remark" title="{$affiliation_user_menu_item.REMARKS}">
							{$affiliation_user_menu_item.REMARKS_SHORT}
						</td>
						<td class="c_td_affiliation_menu_user_detail">
							<input type="button" class="c_btn_affiliation_user_detail" id="id_btn_affiliation_detail{$smarty.foreach.fe_affiliation_user_menu.iteration}" value="詳細" />
						</td>
						{if $auth_code=="ADMIN" || $auth_code=="GENERAL1" || $auth_code=="GENERAL2"}
						<td class="c_td_affiliation_menu_user_update">
							<input type="button" class="c_btn_affiliation_user_update" id="id_btn_affiliation_update{$smarty.foreach.fe_affiliation_user_menu.iteration}" value="編集" />
						</td>
						{/if}
					</tr>
				{foreachelse}
					<tr>
						<td colspan=4>所属するユーザーが存在しません</td>
					</tr>
				{/foreach}
				</table>
			</div>
			<div id="id_div_affiliation_user_menu_btn">
				<table id="id_table_affiliation_user_menu_btn">
					<tr>	
						<td id="id_td_affiliation_user_menu_ckb">
							<input type="checkbox" class="c_ckb_affiliation_menu_onlyvalid" id="id_ckb_affiliation_user_onlyinvalid" value="" {if $valid_user_checkstat=="Y"}checked{/if}/>
							<span class="c_span_affiliation_onlyvalid_expl">有効データのみ表示</span>
						</td>
						<td id="id_td_affiliation_user_menu_ckb_expl">
							&nbsp;
						</td>
					</tr>
				</table>
			</div>
		</div>
		{*所属作業拠点*}
		<div id="id_div_affiliation_workbase_table" class="c_div_comp_dtail_tab_outer">
			<div id="id_div_affiliation_workbase_menu_page" class="c_div_comp_dtail_tab_pageope">
				<table id="id_table_affiliation_workbase_menu_page" class="c_table_comp_detail_afftitle">
					<tr>
						<td class="c_td_comp_detail_affmenu_listtitle">
							{$comp_detail_affmenu_listtitle_place}
						</td>
						<td class="c_td_comp_detail_afftitle_btn">
							<input type="button" id="id_btn_affiliation_workbase_insert" value="作業拠点新規作成" />
						</td>
					</tr>
				</table>
				<table class="c_table_comp_detail_affmenu">
					{if $workbase_pageitem_visible == 'ON'}
					<tr class="c_tr_affiliation_page_menu">
						<td id="id_td_affiliation_workbase_menu_reccnt" class="c_td_comp_detail_affmenu">
							<span>該当{$workbase_rec_count}件</span>
						</td>
						<td id="id_td_affiliation_workbase_menu_pgcnt" class="c_td_comp_detail_affmenu">
							<span>{$workbase_show_page}/{$workbase_page_count}ページ</span>
						</td>
						<td id="id_td_affiliation_workbase_menu_btnpev" class="c_td_comp_detail_affmenu_prev">
						{if $workbase_prev_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_workbase_menu" id="id_btn_affiliation_workbase_prev" value="{$workbase_prev_btn_value}" />
						{/if}
						</td>
						<td id="id_td_affiliation_workbase_menu_btnnext" class="c_td_comp_detail_affmenu_next">
						{if $workbase_next_btn_visible == 'ON'}
							<input type="button" class="c_btn_affiliation_workbase_menu" id="id_btn_affiliation_workbase_next" value="{$workbase_next_btn_value}" />
						{/if}
						</td>
					</tr>
					{/if}
				</table>
			</div>
			{* 以下作業拠点リスト表 *}
			<div id="id_div_affiliation_workbase_menu_table" class="c_div_comp_detail_tab_dtltable">
				<table id="id_table_affiliation_workbase_menu" class="c_table_comp_detail_list">
					<tr id="id_tr_affiliation_workbase_menu_top" class="c_tr_comp_detail_list_top">
						<td class="c_td_affiliation_workbase_menu" id="id_td_affiliation_workbase_menu_invalid">&nbsp;</td>
						<td class="c_td_affiliation_workbase_menu" id="id_td_affiliation_workbase_menu_name">作業拠点名</td>
						<td class="c_td_affiliation_workbase_menu" id="id_td_affiliation_workbase_menu_remark">備考</td>
						<td class="c_td_affiliation_workbase_menu" id="id_td_affiliation_workbase_menu_detail"></td>
						<td class="c_td_affiliation_workbase_menu" id="id_td_affiliation_workbase_menu_update"></td>
					</tr>
				{foreach from=$ar_affiliation_workbase_menu item=affiliation_workbase_menu_item name=fe_affiliation_workbase_menu}
					<tr id="id_tr_affiliation_workbase_menu{$smarty.foreach.fe_affiliation_workbase_menu.iteration}" class="c_tr_affiliation_workbase_menu">
						<td class="c_td_affiliation_menu_invalid">
						{* 有効無効表示 *}
						{if $affiliation_workbase_menu_item.VALIDITY_FLAG=="Y"}
							<input id="id_btn_affiliation_workbase_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_valid" type="text" value=" " readOnly="true" />
						{else}
							<input id="id_btn_affiliation_workbase_menu_valid{$smarty.foreach.fe_list_menu.iteration}" class="c_txt_affiliation_menu_invalid" type="text" value=" " readOnly="true" />
						{/if}
							<input id="id_workbaseid_affiliation_menu{$smarty.foreach.fe_affiliation_workbase_menu.iteration}" type="hidden" value="{$affiliation_workbase_menu_item.BASE_ID}" />
						</td>
						<td class="c_td_affiliation_menu_workbase_name" title="{$affiliation_workbase_menu_item.BASE_NAME}">
							{$affiliation_workbase_menu_item.BASE_NAME_SHORT}
						</td>
						<td class="c_td_affiliation_menu_workbase_remark" title="{$affiliation_workbase_menu_item.REMARKS}">
							{$affiliation_workbase_menu_item.REMARKS_SHORT}
						</td>
						<td class="c_td_affiliation_menu_workbase_detail">
							<input type="button" class="c_btn_affiliation_workbase_detail" id="id_btn_affiliation_datail{$smarty.foreach.fe_affiliation_workbase_menu.iteration}" value="詳細" />
						</td>
						<td class="c_td_affiliation_menu_workbase_update">
							<input type="button" class="c_btn_affiliation_workbase_update" id="id_btn_affiliation_update{$smarty.foreach.fe_affiliation_workbase_menu.iteration}" value="編集" />
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan=4>所属する作業拠点が存在しません</td>
					</tr>
				{/foreach}
				</table>
			</div>
			<div id="id_div_affiliation_workbase_menu_btn">
				<table id="id_table_affiliation_workbase_menu_btn">
					<tr>	
						<td id="id_td_affiliation_workbase_menu_ckb">
							<input type="checkbox" class="c_ckb_affiliation_menu_onlyvalid" id="id_ckb_affiliation_workbase_onlyinvalid" value="" {if $valid_workbase_checkstat=="Y"}checked{/if}/>
							<span class="c_span_affiliation_onlyvalid_expl">有効データのみ表示</span>
						</td>
						<td id="id_td_affiliation_workbase_menu_ckb_expl">
							&nbsp;
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	</div>
</div>

