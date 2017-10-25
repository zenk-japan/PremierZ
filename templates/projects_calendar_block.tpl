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
					<div id="id_div_calendar">
						<div id="id_div_cal_top_mess">
							<span class="c_span_cal_top_mess">{$calendar_top_mess}</span>
						</div>
						<table id = "id_table_calendar">
							<tr>
								<td>&nbsp;</td>
								<td>
									<input id="id_btn_cal_prev_month" class="c_id_cal_btn_movemonth" type="button" value="←" title="前月に移動"/>
								</td>
								<td id="id_td_cal_yyyymm" class="c_id_cal_7col" colspan=5>
									{$cal_yyyy}-{$cal_mm}
								</td>
								<td>
									<input id="id_btn_cal_next_month" class="c_id_cal_btn_movemonth" type="button" value="→" title="翌月に移動"/>
								</td>
								<td>
									<input id="id_btn_go_thismonth" type="button" value="今月に戻す"/>
									<input id="id_hd_cal_yyyy" type="hidden" value="{$cal_yyyy}" />
									<input id="id_hd_cal_mm" type="hidden" value="{$cal_mm}" />
								</td>
							</tr>
{*
							<tr>
								<th class="c_th_clendar_top_null"></th>
								<th class="c_th_clendar_top1">日</th>
								<th class="c_th_clendar_top2">月</th>
								<th class="c_th_clendar_top3">火</th>
								<th class="c_th_clendar_top4">水</th>
								<th class="c_th_clendar_top5">木</th>
								<th class="c_th_clendar_top6">金</th>
								<th class="c_th_clendar_top7">土</th>
								<th class="c_th_clendar_top_null"></th>
							</tr>
							<tr class="c_tr_cal_days">
								<td class="c_id_cal_6row" rowspan=6>
								</td>
							{foreach from=$cal_week1 key=cal_week1_daynum item=r_cal_week1 name=fe_cal_week1}
								<td id="id_td_cal_matrix{1}{$smarty.foreach.fe_cal_week1.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week1.iteration}">
									<input id="id_btn_cal_matrix{1}{$smarty.foreach.fe_cal_week1.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week1.iteration}" type="button" value="{$r_cal_week1.DD}" {if $r_cal_week1.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
								<td class="c_id_cal_6row" rowspan=6>
								</td>
							</tr>
							<tr class="c_tr_cal_days">
							{foreach from=$cal_week2 key=cal_week2_daynum item=r_cal_week2 name=fe_cal_week2}
								<td id="id_td_cal_matrix{2}{$smarty.foreach.fe_cal_week2.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week2.iteration}">
									<input id="id_btn_cal_matrix{2}{$smarty.foreach.fe_cal_week2.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week2.iteration}" type="button" value="{$r_cal_week2.DD}" {if $r_cal_week2.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
							</tr>
							<tr class="c_tr_cal_days">
							{foreach from=$cal_week3 key=cal_week3_daynum item=r_cal_week3 name=fe_cal_week3}
								<td id="id_td_cal_matrix{3}{$smarty.foreach.fe_cal_week3.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week3.iteration}">
									<input id="id_btn_cal_matrix{3}{$smarty.foreach.fe_cal_week3.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week3.iteration}" type="button" value="{$r_cal_week3.DD}" {if $r_cal_week3.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
							</tr>
							<tr class="c_tr_cal_days">
							{foreach from=$cal_week4 key=cal_week4_daynum item=r_cal_week4 name=fe_cal_week4}
								<td id="id_td_cal_matrix{4}{$smarty.foreach.fe_cal_week4.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week4.iteration}">
									<input id="id_btn_cal_matrix{4}{$smarty.foreach.fe_cal_week4.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week4.iteration}" type="button" value="{$r_cal_week4.DD}" {if $r_cal_week4.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
							</tr>
							<tr class="c_tr_cal_days">
							{foreach from=$cal_week5 key=cal_week5_daynum item=r_cal_week5 name=fe_cal_week5}
								<td id="id_td_cal_matrix{5}{$smarty.foreach.fe_cal_week5.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week5.iteration}">
									<input id="id_btn_cal_matrix{5}{$smarty.foreach.fe_cal_week5.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week5.iteration}" type="button" value="{$r_cal_week5.DD}" {if $r_cal_week5.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
							</tr>
							<tr class="c_tr_cal_days">
							{foreach from=$cal_week6 key=cal_week6_daynum item=r_cal_week6 name=fe_cal_week6}
								<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week6.iteration}">
									<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week6.iteration}" type="button" value="{$r_cal_week6.DD}" {if $r_cal_week6.HOLIDAY_FLAG=='X'}disabled{/if}/>
								</td>
							{/foreach}
							</tr>
							<tr>
								<td class="c_id_cal_3col" colspan=3>
									<input id="id_btn_cal_yyyymm" type="button" value="{$cal_yyyy}-{$cal_mm}全て" title="この月が作業予定期間に含まれるすべてのプロジェクトを表示"/>
									<input id="id_hd_cal_yyyy" type="hidden" value="{$cal_yyyy}" />
									<input id="id_hd_cal_mm" type="hidden" value="{$cal_mm}" />
								</td>
								<td class="c_id_cal_3col" colspan=3>
									<input id="id_btn_go_thismonth" type="button" value="今月に戻す"/>
								</td>
								<td class="c_id_cal_3col" colspan=3>
									<input id="id_btn_no_schedule" type="button" value="期間設定なし"/>
								</td>
							</tr>
							<tr>
								<td id="id_td_cal_bottom_mess" class="c_id_cal_9col" colspan=9>
									現在の設定:<br>{$cal_cond_mess}
								</td>
							</tr>
*}
						</table>
					</div>
