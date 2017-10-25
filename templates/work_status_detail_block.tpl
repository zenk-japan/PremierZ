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
	<div id="id_div_detail">
		<table id="id_tab_detail">
			<tr class="c_tr_detail_header">
				<th class="c_th_detail_header" rowspan=2>作業者名</th>
				<th class="c_th_detail_header" rowspan=2>作業拠点</th>
				<th class="c_th_detail_header" rowspan=2>確認</th>
				<th class="c_th_detail_header" rowspan=2>出発</th>
				<th class="c_th_detail_header" rowspan=2>入店</th>
				<th class="c_th_detail_header" rowspan=2>退店</th>
				<th class="c_th_detail_header" colspan=2>出発</th>
				
				<th class="c_th_detail_header" colspan=2>入店</th>
				
				<th class="c_th_detail_header" colspan=2>退店</th>
				
			</tr>
			<tr class="c_tr_detail_header">
				{*作業者名*}
				{*作業拠点*}
				{*確認*}
				{*出発*}
				{*入店*}
				{*退店*}
				<th class="c_th_detail_header">予定</th>
				<th class="c_th_detail_header">実績</th>
				<th class="c_th_detail_header">予定</th>
				<th class="c_th_detail_header">実績</th>
				<th class="c_th_detail_header">予定</th>
				<th class="c_th_detail_header">実績</th>
			</tr>
			{foreach from=$ar_work_staff key=l_rec_num item=lr_work_staff name=fe_work_staff}
			<tr class="c_tr_detail_list">
				<td class="c_td_detail_list_wuser">
					<div class="c_div_detail_list_180"><span class="c_span_detail_underline">{$lr_work_staff.WORK_USER_NAME_SHORT}</span></div>
					<input type="hidden" id="id_hd_user_id{$smarty.foreach.fe_work_staff.iteration}" value="{$lr_work_staff.WORK_USER_ID}"/>
				</td>
				<td class="c_td_detail_list_wplace">
					<div class="c_div_detail_list_180">{$lr_work_staff.WORK_BASE_NAME_SHORT}</div>
				</td>
			{if $lr_work_staff.APPROVAL_DIVISION == "NO"}
				<td class="c_td_detail_list_15_ng">
					<div class="c_div_detail_list">×</div>{* 不承諾 *}
				</td>
			{elseif $lr_work_staff.APPROVAL_DIVISION == "AP"}
				<td class="c_td_detail_list_15_ok">
					<div class="c_div_detail_list">〇</div>{* 承諾 *}
				</td>
			{else}
				<td class="c_td_detail_list_15_na">
					<div class="c_div_detail_list">－</div>{* 未確認,未回答 *}
				</td>
			{/if}
			{if $lr_work_staff.DISPATCH_STAFF_TIMET == ""}
				<td class="c_td_detail_list_15_na">
					<div class="c_div_detail_list">－</div>{* 未出発 *}
				</td>
			{else}
				<td class="c_td_detail_list_15_ok">
					<div class="c_div_detail_list">〇</div>{* 出発済 *}
				</td>
			{/if}
			{if $lr_work_staff.ENTERING_STAFF_TIMET == ""}
				<td class="c_td_detail_list_15_na">
					<div class="c_div_detail_list">－</div>{* 未入店 *}
				</td>
			{else}
				<td class="c_td_detail_list_15_ok">
					<div class="c_div_detail_list">〇</div>{* 入店済 *}
				</td>
			{/if}
			{if $lr_work_staff.LEAVE_STAFF_TIMET == ""}
				<td class="c_td_detail_list_15_na">
					<div class="c_div_detail_list">－</div>{* 未退店 *}
				</td>
			{else}
				<td class="c_td_detail_list_15_ok">
					<div class="c_div_detail_list">〇</div>{* 退店済 *}
				</td>
			{/if}
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.DISPATCH_SCHEDULE_TIMET_HHMM}</div>
				</td>
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.DISPATCH_STAFF_TIMET_HHMM}</div>
				</td>
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.ENTERING_SCHEDULE_TIMET_HHMM}</div>
				</td>
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.ENTERING_STAFF_TIMET_HHMM}</div>
				</td>
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.LEAVE_SCHEDULE_TIMET_HHMM}</div>
				</td>
				<td class="c_td_detail_list_32">
					<div class="c_div_detail_list">{$lr_work_staff.LEAVE_STAFF_TIMET_HHMM}</div>
				</td>
			</tr>
			{foreachelse}
			<tr class="c_tr_detail_list">
				<td colspan=11>
					作業人員が登録されていません。
				</td>
			</tr>
			{/foreach}
		</table>
	</div>

