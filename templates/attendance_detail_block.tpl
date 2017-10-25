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
	<div id = "id_div_detail_menu">
		{* タイトル *}
		<div id="id_div_detail_title">
			<span>{$detail_title}</span>
		</div>
		{* 出力操作部 *}
		<div id="id_div_detail_output">
			<table id="id_table_detail_output">
				<tr>
					<td id="id_td_detail_output_mess">
					{if $detail_html_show_flag=='Y'}
						&nbsp;
					{else}
						<span class="c_span_nodata_comment">作業一覧から作業を選択して下さい。</span>
					{/if}
					</td>
					<td id="id_td_detail_output">
					{if $detail_html_show_flag=='Y'}
						<FORM name="fm_search" id="fm_search" method="POST">
						<input type="button" id="id_btn_detail_pdfbtn" value="PDF出力" />
						<input type="button" id="id_btn_detail_htmlbtn" value="印刷用HTML出力" />
						<INPUT type=hidden name=hd_dataid value="{$data_id}"></INPUT>
						<INPUT type=hidden name=hd_loginuserid value="{$loginuser_id}"></INPUT>
						<INPUT type=hidden name=hd_delete_check value=""></INPUT>
						<INPUT type=hidden name=hd_reserv1_id value=""></INPUT>
						<INPUT type=hidden name=hd_reserv2_id value=""></INPUT>
						<INPUT type=hidden name=hd_reserv3_id value=""></INPUT>
						<INPUT type=hidden name=hd_reserv4_id value=""></INPUT>
						<INPUT type=hidden name=hd_page_name value="attendance_sheet"></INPUT>
						<INPUT type=hidden name=ESTIMATE_ID value="{$estimate_id}"></INPUT>
						<INPUT type=hidden name=WORK_NAME value="{$work_name}"></INPUT>
						<INPUT type=hidden name=WORK_CONTENT_ID value="{$workcontent_id}"></INPUT>
						<INPUT type=hidden name=WORK_DATE value="{$work_date}"></INPUT>
						<INPUT type=hidden name=WORK_USER_NAME value="{$work_name}"></INPUT>
						<INPUT type=hidden name=WORK_USER_ID value="{$workuser_id}"></INPUT>
						<INPUT type=hidden name=BASE_TIME value="{$base_time}"></INPUT>
						<INPUT type=hidden name=ROUND_TYPE value="{$round_type}"></INPUT>
						</FORM>
					{else}
						&nbsp;
					{/if}
					</td>
				</tr>
			</table>
		</div>
		{* 明細表 *}
		<div id="id_div_detail_table">
			{if $detail_html_show_flag=='Y'}
			{$detail_html}
			{/if}
		</div>
	</div>

