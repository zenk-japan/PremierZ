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
<!-- カレンダー -->
	<div id = "id_div_calendar">
		<div id="id_div_cal_title">
			<span id="id_span_cal_title">&nbsp;カレンダー</span>
		</div>
		<div id="id_div_cal_ope">
			<table id="id_tab_cal_ope">
				<tr class="c_tr_cal_ope">
					<td class="c_td_cal_ope">
						<input id="id_btn_prev_year" type="button" class="c_btn_cal_ym_ope" value="<"/>
					</td>
					<td class="c_td_cal_ope_col2" colspan=2>
						<input id="id_btn_this_year" type="button" class="c_btn_cal_y_ope" value="{$selected_year}年"/>
					</td>
					<td class="c_td_cal_ope">
						<input id="id_btn_next_year" type="button" class="c_btn_cal_ym_ope" value=">"/>
					</td>
					<td class="c_td_cal_ope">
						<input id="id_btn_this_month" type="button" class="c_btn_cal_ym_ope" value="今月"/>
					</td>
					<td class="c_td_cal_ope">
						<input id="id_btn_today" type="button" class="c_btn_cal_ym_ope" value="今日"/>
					</td>
				</tr>
				<tr class="c_tr_cal_ope">
					<td {if $selected_month == 1}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month1" name="1" type="button" class="c_btn_cal_ope" value="1月"/></td>
					<td {if $selected_month == 2}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month2" name="2" type="button" class="c_btn_cal_ope" value="2月"/></td>
					<td {if $selected_month == 3}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month3" name="3" type="button" class="c_btn_cal_ope" value="3月"/></td>
					<td {if $selected_month == 4}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month4" name="4" type="button" class="c_btn_cal_ope" value="4月"/></td>
					<td {if $selected_month == 5}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month5" name="5" type="button" class="c_btn_cal_ope" value="5月"/></td>
					<td {if $selected_month == 6}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month6" name="6" type="button" class="c_btn_cal_ope" value="6月"/></td>
				</tr>
				<tr class="c_tr_cal_ope">
					<td {if $selected_month == 7}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month7" name="7" type="button" class="c_btn_cal_ope" value="7月"/></td>
					<td {if $selected_month == 8}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month8" name="8" type="button" class="c_btn_cal_ope" value="8月"/></td>
					<td {if $selected_month == 9}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month9" name="9" type="button" class="c_btn_cal_ope" value="9月"/></td>
					<td {if $selected_month == 10}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month10" name="10" type="button" class="c_btn_cal_ope" value="10月"/></td>
					<td {if $selected_month == 11}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month11" name="11" type="button" class="c_btn_cal_ope" value="11月"/></td>
					<td {if $selected_month == 12}class="c_td_cal_ope_month_selected"{else}class="c_td_cal_ope_month"{/if}><input id="id_btn_cal_month12" name="12" type="button" class="c_btn_cal_ope" value="12月"/></td>
				</tr>
			</table>
		</div>
		<div id="id_div_cal_main">
			<table id="id_tab_cal_main">
				<tr class="c_tr_cal_main_top">
					<th id="id_th_cal_main_sun" class="c_th_cal_main">日</th>
					<th class="c_th_cal_main">月</th>
					<th class="c_th_cal_main">火</th>
					<th class="c_th_cal_main">水</th>
					<th class="c_th_cal_main">木</th>
					<th class="c_th_cal_main">金</th>
					<th id="id_th_cal_main_sat" class="c_th_cal_main">土</th>
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week1 key=cal_week1_daynum item=r_cal_week1 name=fe_cal_week1}
					<td id="id_td_cal_matrix{1}{$smarty.foreach.fe_cal_week1.iteration}" {if $r_cal_week1.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week1.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week1.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week1.iteration}"{/if}>
						<input id="id_btn_cal_matrix{1}{$smarty.foreach.fe_cal_week1.iteration}" {if $r_cal_week1.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week1.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week1.iteration}"{/if} type="button" value="{$r_cal_week1.DD}" {if $r_cal_week1.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
				{/foreach}
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week2 key=cal_week2_daynum item=r_cal_week2 name=fe_cal_week2}
					<td id="id_td_cal_matrix{2}{$smarty.foreach.fe_cal_week2.iteration}" {if $r_cal_week2.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week2.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week2.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week2.iteration}"{/if}>
						<input id="id_btn_cal_matrix{2}{$smarty.foreach.fe_cal_week2.iteration}" {if $r_cal_week2.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week2.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week2.iteration}"{/if} type="button" value="{$r_cal_week2.DD}" {if $r_cal_week2.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
				{/foreach}
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week3 key=cal_week3_daynum item=r_cal_week3 name=fe_cal_week3}
					<td id="id_td_cal_matrix{3}{$smarty.foreach.fe_cal_week3.iteration}" {if $r_cal_week3.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week3.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week3.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week3.iteration}"{/if}>
						<input id="id_btn_cal_matrix{3}{$smarty.foreach.fe_cal_week3.iteration}" {if $r_cal_week3.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week3.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week3.iteration}"{/if} type="button" value="{$r_cal_week3.DD}" {if $r_cal_week3.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
				{/foreach}
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week4 key=cal_week4_daynum item=r_cal_week4 name=fe_cal_week4}
					<td id="id_td_cal_matrix{4}{$smarty.foreach.fe_cal_week4.iteration}" {if $r_cal_week4.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week4.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week4.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week4.iteration}"{/if}>
						<input id="id_btn_cal_matrix{4}{$smarty.foreach.fe_cal_week4.iteration}" {if $r_cal_week4.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week4.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week4.iteration}"{/if} type="button" value="{$r_cal_week4.DD}" {if $r_cal_week4.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
				{/foreach}
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week5 key=cal_week5_daynum item=r_cal_week5 name=fe_cal_week5}
					<td id="id_td_cal_matrix{5}{$smarty.foreach.fe_cal_week5.iteration}" {if $r_cal_week5.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week5.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week5.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week5.iteration}"{/if}>
						<input id="id_btn_cal_matrix{5}{$smarty.foreach.fe_cal_week5.iteration}" {if $r_cal_week5.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week5.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week5.iteration}"{/if} type="button" value="{$r_cal_week5.DD}" {if $r_cal_week5.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
				{/foreach}
				</tr>
				<tr class="c_tr_cal_main_days">
				{foreach from=$cal_week6 key=cal_week6_daynum item=r_cal_week6 name=fe_cal_week6}
					{* 最後の4マスを説明に使用する（ここが日付で埋まることはない）*}
					{if $smarty.foreach.fe_cal_week6.iteration <= 3}
					<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" {if $r_cal_week6.SELECTED_DAY=='Y'}class="c_td_cal_day_selected"{elseif $r_cal_week6.TODAY=='Y'}class="c_td_cal_day_today"{elseif $r_cal_week6.INPERIOD_FLG=='Y'}class="c_td_cal_day_inperiod"{else}class="c_td_cal_day{$smarty.foreach.fe_cal_week6.iteration}"{/if}>
						<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" {if $r_cal_week6.WORK_EXIST=='Y'}class="c_btn_cal_day_workexist"{elseif $r_cal_week6.HOLIDAY_FLAG=='X'}class="c_btn_cal_day_noday"{else}class="c_btn_cal_day{$smarty.foreach.fe_cal_week6.iteration}"{/if} type="button" value="{$r_cal_week6.DD}" {if $r_cal_week6.HOLIDAY_FLAG=='X'}disabled{/if}/>
					</td>
					{elseif $smarty.foreach.fe_cal_week6.iteration == 4}
					<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_td_cal_day_inperiod">
						<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week6.iteration}" type="button" value="作業&#13;&#10;期間内" style="font-size:10px;" disabled/>
					</td>
					{elseif $smarty.foreach.fe_cal_week6.iteration == 5}
					<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_td_cal_day{$smarty.foreach.fe_cal_week6.iteration}">
						<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_btn_cal_day_workexist" type="button" value="作業日" style="font-size:10px;" disabled/>
					</td>
					{elseif $smarty.foreach.fe_cal_week6.iteration == 6}
					<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_td_cal_day_selected">
						<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_btn_cal_day{$smarty.foreach.fe_cal_week6.iteration}" type="button" value="選択日" style="font-size:10px;" disabled/>
					</td>
					{elseif $smarty.foreach.fe_cal_week6.iteration == 7}
					<td id="id_td_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_td_cal_day_today">
						<input id="id_btn_cal_matrix{6}{$smarty.foreach.fe_cal_week6.iteration}" class="c_btn_cal_day3" type="button" value="今日" style="font-size:10px;" disabled/>
					</td>
					{/if}
				{/foreach}
				</tr>
			</table>
		</div>
		<div id="id_div_cal_mess">
			<table id="id_tab_cal_mess">
				<tr id="id_tr_cal_mess">
					<td id="id_td_cal_mess_img">&nbsp;</td>
					<td id="id_td_cal_mess">
						{if $first_work_date != ""}作業初日:{$first_work_date}{else}作業は未登録です{/if}
					</td>
					<td id="id_td_cal_mess">
						{if $last_work_date != ""}作業最終日:{$last_work_date}{/if}
					</td>
				</tr>
			</table>
		</div>
	</div>
