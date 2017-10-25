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
<!-- メイン -->
	<div id="id_div_search">
		<table id="id_table_search">
			<tr class="c_tr_search">
				<td class="c_td_search_title" rowspan=2>
					&nbsp;検索
				</td>
				<td class="c_td_search_select_cap">
					所属会社
				</td>
				<td class="c_td_search_select">
				{if ($ar_work_company_name|@count) > 0}
					<select id="id_sel_work_company_name" class="c_sel_search" name="WORK_COMPANY_NAME">
						<option class="c_opt_search" value=""></option>
					{foreach from=$ar_work_company_name key=l_wcn_key item=l_wcn_val name=fe_work_company_name}
						<option class="c_opt_search" value="{$l_wcn_key}"{if $l_wcn_key==$default_work_company_name} selected{/if}>{$l_wcn_val}</option>
					{/foreach}
					</select>
				{/if}
				</td>
				<td class="c_td_search_select_cap">
					グループ
				</td>
				<td class="c_td_search_select">
				{if ($ar_work_group_name|@count) > 0}
					<select id="id_sel_work_group_name" class="c_sel_search" name="WORK_GROUP_NAME">
						<option class="c_opt_search" value=""></option>
					{foreach from=$ar_work_group_name key=l_wgn_key item=l_wgn_val name=fe_work_group_name}
						<option class="c_opt_search" value="{$l_wgn_key}"{if $l_wgn_key==$default_work_group_name} selected{/if}>{$l_wgn_val}</option>
					{/foreach}
					</select>
				{/if}
				</td>
			</tr>
			<tr class="c_tr_search">
				<td class="c_td_search_select_cap">
					分類区分
				</td>
				<td class="c_td_search_select">
				{if ($ar_work_classification_division_name|@count) > 0}
					<select id="id_sel_work_classification_division_name" class="c_sel_search" name="WORK_CLASSIFICATION_DIVISION_NAME">
						<option class="c_opt_search" value=""></option>
					{foreach from=$ar_work_classification_division_name key=l_wcdn_key item=l_wcdn_val name=fe_work_classification_division_name}
						<option class="c_opt_search" value="{$l_wcdn_key}"{if $l_wcdn_key==$default_work_classification_division_name} selected{/if}>{$l_wcdn_val}</option>
					{/foreach}
					</select>
				{/if}
				</td>
				<td class="c_td_search_select_cap">
					作業者名
				</td>
				<td class="c_td_search_select">
				{if ($ar_work_user_name|@count) > 0}
					<select id="id_sel_work_user_name" class="c_sel_search" name="WORK_USER_NAME">
						<option class="c_opt_search" value=""></option>
					{foreach from=$ar_work_user_name key=l_wun_key item=l_wun_val name=fe_work_user_name}
						<option class="c_opt_search" value="{$l_wun_key}"{if $l_wun_key==$default_work_user_name} selected{/if}>{$l_wun_val}</option>
					{/foreach}
					</select>
				{/if}
				</td>
				<td id="id_td_workstaff_dtl_ckb">
					<input type="checkbox" class="c_ckb_workstaff_onlyvalid" id="id_ckb_workstaff_onlyinvalid" value="" {if $valid_workstaff_checkstat=="Y"}checked{/if}/>
					<span class="c_span_task_onlyvalid_expl">有効データのみ表示</span>
				</td>
				<td id="id_td_search_button">
					<input type="button" value="クリア" id="id_btn_search_clear" />
				</td>
				<td class="c_td_search">
				</td>
				<td class="c_td_search">
				</td>
			</tr>
		</table>
	</div>

