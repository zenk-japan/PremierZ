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
<div id="id_div_task_ov_main">
	<table id="id_tab_task_ov_main">
		<tr>
{*作業名*}
			<td colspan=4 id="id_td_task_ov_wname">
				{$work_name}
			</td>
		</tr>
		<tr>
{*作業日*}{*纏め者*}
			<td class="c_td_task_ov_caption">
				作業日
			</td>
			<td class="c_td_task_ov_value">
				{$work_date}
			</td>
			<td class="c_td_task_ov_caption">
				作業纏め者
			</td>
			<td class="c_td_task_ov_value">
				{$work_arrangement_name}
			</td>
		</tr>
		<tr>
{*エンドユーザー*}{*依頼元*}
			<td class="c_td_task_ov_caption">
				エンドユーザー
			</td>
			<td class="c_td_task_ov_value">
				{$enduser_comp_name}
			</td>
			<td class="c_td_task_ov_caption">
				依頼元
			</td>
			<td class="c_td_task_ov_value">
				{$request_comp_name}
			</td>
		</tr>
		<tr>
{*作業参加者一覧*}
			<td colspan=4 id="id_td_task_ov_wstaff_title">
				作業参加者一覧
			</td>
		</tr>
		<tr>
			<td colspan=4 id="id_td_task_ov_wstaff">
				<div id="id_div_task_ov_wstaff">
				{*所属会社*}{*氏名*}{*残業*}{*出金合計*}{*その他費用*}
					<table id="id_tab_tov_ws_main">
						<tr>
							<th class="c_th_tov_ws_main">No.</th>
							<th class="c_th_tov_ws_main">拠点</th>
							<th class="c_th_tov_ws_main">所属会社</th>
							<th class="c_th_tov_ws_main">氏名</th>
							<th class="c_th_tov_ws_main">残業</th>
							<th class="c_th_tov_ws_main">出金合計</th>
							<th class="c_th_tov_ws_main">その他費用</th>
						</tr>
					{foreach from=$ar_work_staff key=l_ws_key item=l_ws_val name=fe_work_staff}
						<tr>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main">{$smarty.foreach.fe_work_staff.iteration}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main">{$l_ws_val.WORK_BASE_NAME_SHORT}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main">{$l_ws_val.WORK_COMPANY_NAME_SHORT}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main">{$l_ws_val.WORK_USER_NAME_SHORT}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main_dr">{$l_ws_val.OVERTIME_WORK_AMOUNT|number_format}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main_cr">{$l_ws_val.PAYMENT_AMOUNT_TOTAL|number_format}</div></td>
							<td class="c_td_tov_ws_main"><div class="c_div_tov_ws_main_cr">{$l_ws_val.OTHER_AMOUNT|number_format}</div></td>
						</tr>
						{if $smarty.foreach.fe_work_staff.last}
						<tr>
							<td colspan=4 class="c_td_tov_ws_total_cap">
								合計
							</td>
							<td class="c_td_tov_ws_total_dr">
								<div class="c_div_tov_ws_total">{$overtime_work_amount_sum|number_format}</div>
							</td>
							<td class="c_td_tov_ws_total_cr">
								<div class="c_div_tov_ws_total">{$payment_amount_total_sum|number_format}</div>
							</td>
							<td class="c_td_tov_ws_total_cr">
								<div class="c_div_tov_ws_total">{$other_amount_sum|number_format}</div>
							</td>
						</tr>
						{/if}
					{foreachelse}
						<tr>
							<td colspan=7>作業者の登録がありません</td>
						</tr>
					{/foreach}
					</table>
				</div>
			</td>
		</tr>
	</table>



</div>
