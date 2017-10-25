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
{* 勤務表 *}
<div id="id_ats_div_outer" width="698px" height="80px">
	{* タイトル *}
	<table id="id_ats_table_outer" width="100%" CELLSPACING="0">
		<tr>
			<td id="id_ats_td_title" bordercolor= "#222222" align="center" height="30px">
				{$title_ym}度&nbsp;&nbsp;勤務実績表
			</td>
		</tr>

	{* 作業者 *}
		<tr>
			<td>
				<table id="id_ats_table_workuser">
					<tr>
						<td class="c_ats_td_workuser_cap">
							作業者名:
						</td>
						<td class="c_ats_td_workuser_value">
							{$workuser_name}
						</td>
						<td class="c_ats_td_workuser_space">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="c_ats_td_workuser_cap">
							所属:
						</td>
						<td class="c_ats_td_workuser_value">
							{$workuser_company_name}
						</td>
						<td class="c_ats_td_workuser_space">
							&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

{* 明細 *}
<div id="id_ats_div_detail">
{* 作業日  	作業名	開始時間  	終了時間  	休憩時間  	実働時間  	残業時間  	作業内容詳細  	備考 *}
	<table id="id_ats_table_detail">
		<tr>
			<th nowrap class="c_ats_th_detail_head_middle" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">作業日</th>
			<th nowrap class="c_ats_th_detail_head_long" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">作業名</th>
			<th nowrap class="c_ats_th_detail_head_short" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">開始</th>
			<th nowrap class="c_ats_th_detail_head_short" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">終了</th>
			<th nowrap class="c_ats_th_detail_head_short" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">休憩</th>
			<th nowrap class="c_ats_th_detail_head_short" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">実働</th>
			<th nowrap class="c_ats_th_detail_head_short" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">残業</th>
			<th nowrap class="c_ats_th_detail_head_long" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">作業内容詳細</th>
			<th nowrap class="c_ats_th_detail_head_long" border="1px" bordercolor= "#DDDDDD" bgcolor="#666670" color="#FFFFFF">備考</th>
		</tr>
	{foreach from=$ar_attendance_detail key=l_work_date item=lr_work_info name=fe_atd_detail}
		{foreach from=$lr_work_info key=l_inner_num item=lr_work_detail name=fe_work_detail}
		<tr>
		{* 作業日 *}
			{if $l_inner_num=="1"}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}" rowspan={$lr_work_info|@count}>
				{$l_work_date}
			</td>
			{/if}
		{* 作業名 *}
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				{$lr_work_detail.WORK_NAME}
			</td>
		{* 開始時間 *}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				{$lr_work_detail.ENTERING_TIMET}
			</td>
		{* 終了時間 *}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				{$lr_work_detail.LEAVE_TIMET}
			</td>
		{* 休憩時間 *}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				{$lr_work_detail.BREAK_TIME}
			</td>
		{* 実働時間 *}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				{$lr_work_detail.WORKING_HOURS}
			</td>
		{* 残業時間 *}
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				{$lr_work_detail.OVERWORK_HOURS}
			</td>
		{* 作業内容詳細 *}
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				{$lr_work_detail.CONTENT_DETAILS}
			</td>
		{* 備考 *}
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				{$lr_work_detail.REMARKS}
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				{$l_work_date}
			</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
		</tr>
		{/foreach}
	{foreachelse}
		<tr>
			<td colspan=9>
				<span>出力項目が有りません</span>
			</td>
		</tr>
	{/foreach}
{* 合計 *}
		<tr>
		{* 作業日 *}
			<td nowrap class="c_ats_td_detail_total">合計</td>
		{* 作業名 *}
			<td class="c_ats_td_detail_total">&nbsp;</td>
		{* 開始時間 *}
			<td class="c_ats_td_detail_total">&nbsp;</td>
		{* 終了時間 *}
			<td class="c_ats_td_detail_total">&nbsp;</td>
		{* 休憩時間 *}
			<td class="c_ats_td_detail_total">{$total_break_time}</td>
		{* 実働時間 *}
			<td class="c_ats_td_detail_total">{$total_working_hours}</td>
		{* 残業時間 *}
			<td class="c_ats_td_detail_total">{$total_overtime_hours}</td>
		{* 作業内容詳細 *}
			<td class="c_ats_td_detail_total">&nbsp;</td>
		{* 備考 *}
			<td class="c_ats_td_detail_total">&nbsp;</td>
		</tr>
	</table>

</div>
{* 補足 *}
<div id="id_ats_div_notice">
	<span class="c_ats_span_notice">{$base_method_notice}</span>
</div>