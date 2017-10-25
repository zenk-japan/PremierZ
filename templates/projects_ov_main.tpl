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
<div id="id_div_prj_ov_main">
	<table id="id_tab_prj_ov_main">
		<tr>
{*作業名*}
			<td colspan=4 id="id_td_prj_ov_wname">
				{$work_name}
			</td>
		</tr>
		<tr>
{*作業予定*}
			<td class="c_td_prj_ov_caption">
				作業予定日
			</td>
			<td colspan=3 class="c_td_prj_ov_value">
				{$work_date_sch_from}&nbsp;&nbsp;-&nbsp;&nbsp;{$work_date_sch_to}
			</td>
		</tr>
		<tr>
{*作業日*}
			<td class="c_td_prj_ov_caption">
				作業日
			</td>
			<td colspan=3 class="c_td_prj_ov_value">
				{$work_date_act_from}&nbsp;&nbsp;-&nbsp;&nbsp;{$work_date_act_to}
			</td>
		</tr>
		<tr>
{*エンドユーザー*}{*依頼元*}
			<td class="c_td_prj_ov_caption">
				エンドユーザー
			</td>
			<td class="c_td_prj_ov_value">
				{$enduser_comp_name}
			</td>
			<td class="c_td_prj_ov_caption">
				依頼元
			</td>
			<td class="c_td_prj_ov_value">
				{$request_comp_name}
			</td>
		</tr>
		<tr>
{*損益*}
			<td colspan=4 id="id_td_prj_ov_task_title">
				損益
			</td>
		</tr>
		<tr>
			<td colspan=4 id="id_td_prj_ov_account">
				<div id="id_div_prj_ov_account">
					<table id="id_tab_prj_ov_account">
						<tr>
							<th class="c_th_pov_ac_cr" colspan=2>費用</th>
							<th class="c_th_pov_ac_dr" colspan=2>収益</th>
						</tr>
						<tr>
							<th class="c_th_pov_ac_cr">科目</th>
							<th class="c_th_pov_ac_cr">金額</th>
							<th class="c_th_pov_ac_dr">科目</th>
							<th class="c_th_pov_ac_dr">金額</th>
						</tr>
						<tr>
							<td class="c_td_pov_ac_cap_cr">&nbsp;</td>
							<td class="c_td_pov_ac_val_cr">&nbsp;</td>
							<td class="c_td_pov_ac_cap_dr">見積金額</td>
							<td class="c_td_pov_ac_val_dr">{$ac_estimate_amount|number_format}</td>
						</tr>
						<tr>
							<td class="c_td_pov_ac_cap_cr">&nbsp;</td>
							<td class="c_td_pov_ac_val_cr">&nbsp;</td>
							<td class="c_td_pov_ac_cap_dr">残業</td>
							<td class="c_td_pov_ac_val_dr">{$ac_owerk_amount|number_format}</td>
						</tr>
						<tr>
							<td class="c_td_pov_ac_cap_cr">出金合計</td>
							<td class="c_td_pov_ac_val_cr">{$ac_payment_amount|number_format}</td>
							<td class="c_td_pov_ac_cap_dr">&nbsp;</td>
							<td class="c_td_pov_ac_val_dr">&nbsp;</td>
						</tr>
						<tr>
							<td class="c_td_pov_ac_cap_cr">その他費用</td>
							<td class="c_td_pov_ac_val_cr">{$ac_other_amount|number_format}</td>
							<td class="c_td_pov_ac_cap_dr">&nbsp;</td>
							<td class="c_td_pov_ac_val_dr">&nbsp;</td>
						</tr>
						<tr>
							<td class="c_td_pov_ac_cap_cr">{if isset($ac_cr_pl)}{$ac_cr_cap}{else}&nbsp;{/if}</td>
							<td class="c_td_pov_ac_val_cr">{if isset($ac_cr_pl)}{$ac_cr_pl|number_format}{else}&nbsp;{/if}</td>
							<td class="c_td_pov_ac_cap_dr">{if isset($ac_dr_pl)}{$ac_dr_cap}{else}&nbsp;{/if}</td>
							<td class="c_td_pov_ac_val_dr">{if isset($ac_dr_pl)}{$ac_dr_pl|number_format}{else}&nbsp;{/if}</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
{*作業日一覧*}
			<td colspan=4 id="id_td_prj_ov_task_title">
				作業日一覧
			</td>
		</tr>
		<tr>
			<td colspan=4 id="id_td_prj_ov_task">
				<div id="id_div_prj_ov_task">
					<table id="id_tab_pov_ws_main">
						<tr>
							<th class="c_th_pov_ws_main">No.</th>
							<th class="c_th_pov_ws_main">作業日</th>
							<th class="c_th_pov_ws_main">纏め者</th>
							<th class="c_th_pov_ws_main">作業人数</th>
							<th class="c_th_pov_ws_main">残業</th>
							<th class="c_th_pov_ws_main">出金合計</th>
							<th class="c_th_pov_ws_main">その他費用</th>
						</tr>
					{foreach from=$ar_tasks key=l_task_key item=l_task_val name=fe_tasks}
						<tr>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main">{$smarty.foreach.fe_tasks.iteration}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main">{$l_task_val.WORK_DATE}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main">{$l_task_val.WORK_ARRANGEMENT_USER_NAME}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main">{$l_task_val.staff_count}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main_dr">{$l_task_val.overtime_work_amount|number_format}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main_cr">{$l_task_val.payment_amount_total|number_format}</div></td>
							<td class="c_td_pov_ws_main"><div class="c_div_pov_ws_main_cr">{$l_task_val.other_amount|number_format}</div></td>
						</tr>
						{if $smarty.foreach.fe_tasks.last}
						<tr>
							<td colspan=4 class="c_td_pov_ws_total_cap">
								合計
							</td>
							<td class="c_td_pov_ws_total_dr">
								<div class="c_div_pov_ws_total">{$overtime_work_amount_sum|number_format}</div>
							</td>
							<td class="c_td_pov_ws_total_cr">
								<div class="c_div_pov_ws_total">{$payment_amount_total_sum|number_format}</div>
							</td>
							<td class="c_td_pov_ws_total_cr">
								<div class="c_div_pov_ws_total">{$other_amount_sum|number_format}</div>
							</td>
						</tr>
						{/if}
					{foreachelse}
						<tr>
							<td colspan=7>作業の登録がありません</td>
						</tr>
					{/foreach}
					</table>
				</div>
			</td>
		</tr>
	</table>



</div>
