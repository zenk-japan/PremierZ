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
<!-- 概要 -->
	<div id = "id_div_info">
		<table id="id_tab_info">
			<tr class="c_tr_info">
				<td id="id_td_info_title" rowspan=3>&nbsp;概要</td>
				<td class="c_td_info_cap_col1">作業名</td>
				<td class="c_td_info_col5" colspan=5><div class="c_div_info_data">{$work_name}<div></td>
				<td class="c_td_info_cap_col1">見積コード</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$estimate_code}<div></td>
			</tr>
			<tr class="c_tr_info">
				<td class="c_td_info_cap_col1">作業日</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$work_date}<div></td>
				<td class="c_td_info_cap_col1">エンドユーザー</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$enduser_company_name}<div></td>
				<td class="c_td_info_cap_col1">依頼元</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$request_company_name}<div></td>
			</tr>
			<tr class="c_tr_info">
				<td class="c_td_info_cap_col1">入店予定時刻</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$default_entering_schedule_timet}<div></td>
				<td class="c_td_info_cap_col1">退店予定時刻</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$default_leave_schedule_timet}<div></td>
				<td class="c_td_info_cap_col1">集合場所</td>
				<td class="c_td_info_col2" colspan=2><div class="c_div_info_data">{$aggregate_point}<div></td>
			</tr>
		</table>
	</div>
