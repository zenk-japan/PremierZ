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
<!-- 作業概要 -->
	<div id="id_div_info">
		<div id="id_div_info_title">
			作業状況
		</div>
		<table id="id_tab_info">
			<tr class="c_tr_info">
				<td class="c_td_info_title" colspan=6>
					{if $data_selected == "Y"}{$work_date}&nbsp;-&nbsp;{$work_name}{/if}
				</td>
			</tr>
			<tr class="c_tr_info">
				<td class="c_td_info_cap">
					作業ステータス
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data">{$work_status_name}</div>
				</td>
				<td class="c_td_info_cap">
					エンドユーザー
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data" title="{$enduser_company_name}">{$enduser_company_name}</div>
				</td>
				<td class="c_td_info_cap">
					作業纏め者
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data" title="{$work_arrangement_user_name}"><span id="id_span_arrangement_user" class="c_span_data_underline">{$work_arrangement_user_name}</span></div>
					<input id="id_hd_work_arrangement_id" type="hidden" value="{$work_arrangement_id}"/>
				</td>
			</tr>
			<tr class="c_tr_info">
				<td class="c_td_info_cap">
					入店予定時刻
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data">{$default_entering_schedule_timet}</div>
				</td>
				<td class="c_td_info_cap">
					退店予定時刻
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data">{$default_leave_schedule_timet}</div>
				</td>
				<td class="c_td_info_cap">
					集合場所
				</td>
				<td class="c_td_info_data">
					<div class="c_div_info_data" title="{$aggregate_point}">{$aggregate_point}</div>
				</td>
			</tr>
		</table>
	</div>

