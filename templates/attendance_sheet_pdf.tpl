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
{* タイトル *}
<table class="c_tab_title" width="100%" cellspacing="0" style="border-top:solid 1px #888888;border-bottom:solid 1px #888888;">
	<tr>
		<td align="center" width="100%" >
			<font size="15">{$title_ym}度&nbsp;&nbsp;勤務実績表</font>
		</td>
	</tr>
</table>
{* 明細 *}
<div id="id_ats_div_detail">
{* 作業日  	作業名	開始時間  	終了時間  	休憩時間  	実働時間  	残業時間  	作業内容詳細  	備考 *}
	<table class="c_tab_detail" border=2 bordercolor="#dddddd">
		<tr>
			<td colspan=9>
				{* 作業者 *}
				<table class="c_tab_workuser" cellspacing="1">
					<tr>
						<td width="20%" style="background-color:#c9d6ff;border-bottom:solid 1px #888888;">
							<font size="10">作業者名:</font>
						</td>
						<td width="50%" style="border-bottom:solid 1px #888888;">
							<font size="10">{$workuser_name}</font>
						</td>
						<td width="30%">
							<font size="10">&nbsp;</font>
						</td>
					</tr>
					<tr>
						<td width="20%" style="background-color:#c9d6ff;border-bottom:solid 1px #888888;">
							<font size="10">所属:</font>
						</td>
						<td width="50%" style="border-bottom:solid 1px #888888;">
							<font size="10">{$workuser_company_name}</font>
						</td>
						<td width="30%">
							<font size="10">&nbsp;</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">作業日</font></th>
			<th nowrap width="20%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">作業名</font></th>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">開始時間</font></th>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">終了時間</font></th>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">休憩時間</font></th>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">実働時間</font></th>
			<th nowrap width="7%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">残業時間</font></th>
			<th nowrap width="19%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">作業内容</font></th>
			<th nowrap width="19%" style="background-color:#444444;color:#ffffff;border:solid 1px #888888;text-align:center;"><font size="8">備考</font></th>
		</tr>
	{foreach from=$ar_attendance_detail key=l_work_date item=lr_work_info name=fe_atd_detail}
		{foreach from=$lr_work_info key=l_inner_num item=lr_work_detail name=fe_work_detail}
		<tr>
		{* 作業日 *}
			{if $l_inner_num=="1"}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}" rowspan={$lr_work_info|@count}>
				<font size="8">{$l_work_date}</font>
			</td>
			{/if}
		{* 作業名 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="20%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.WORK_NAME}</font>
			</td>
		{* 開始時間 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.ENTERING_TIMET}</font>
			</td>
		{* 終了時間 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.LEAVE_TIMET}</font>
			</td>
		{* 休憩時間 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.BREAK_TIME}</font>
			</td>
		{* 実働時間 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.WORKING_HOURS}</font>
			</td>
		{* 残業時間 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_num_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_num_even{else}c_ats_td_detail_num_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.OVERWORK_HOURS}</font>
			</td>
		{* 作業内容詳細 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:left;" width="19%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.CONTENT_DETAILS}</font>
			</td>
		{* 備考 *}
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:left;" width="19%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">
				<font size="8">{$lr_work_detail.REMARKS}</font>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td width="7%" nowrap class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_date_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_date_even{else}c_ats_td_detail_date_odd{/if}{/if}">
				<font size="8">{$l_work_date}</font>
			</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="20%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="7%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="19%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
			<td style="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}background-color:#ffcccc;{/if}border:solid 1px #888888;text-align:center;" width="19%" class="{if $lr_work_detail.HOLIDAY_FLAG=='Y'}c_ats_td_detail_char_holiday{else}{if $smarty.foreach.fe_atd_detail.iteration is even}c_ats_td_detail_char_even{else}c_ats_td_detail_char_odd{/if}{/if}">&nbsp;</td>
		</tr>
		{/foreach}
	{foreachelse}
		<tr>
			<td colspan=9>
				<span><font size="8">出力項目が有りません</font></span>
			</td>
		</tr>
	{/foreach}
{* 合計 *}
		<tr>
		{* 作業日 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" nowrap class="c_ats_td_detail_total"><font size="8">合計</font></td>
		{* 作業名 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="20%" class="c_ats_td_detail_total">&nbsp;</td>
		{* 開始時間 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" class="c_ats_td_detail_total">&nbsp;</td>
		{* 終了時間 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" class="c_ats_td_detail_total">&nbsp;</td>
		{* 休憩時間 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" class="c_ats_td_detail_total"><font size="8">{$total_break_time}</font></td>
		{* 実働時間 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" class="c_ats_td_detail_total"><font size="8">{$total_working_hours}</font></td>
		{* 残業時間 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:center;" width="7%" class="c_ats_td_detail_total"><font size="8">{$total_overtime_hours}</font></td>
		{* 作業内容詳細 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:left;" width="19%" class="c_ats_td_detail_total">&nbsp;</td>
		{* 備考 *}
			<td style="background-color: #ccf2ff;border:solid 1px #888888;text-align:left;" width="19%" class="c_ats_td_detail_total">&nbsp;</td>
		</tr>
	</table>

</div>
{* 補足 *}
<div id="id_ats_div_notice">
	<span class="c_ats_span_notice"><font size="8">{$base_method_notice}</font></span>
</div>