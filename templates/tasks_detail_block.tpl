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
	<div id = "id_div_task_dtl">
		<div id="id_div_task_dtl_title">
			<span id="id_span_task_title">&nbsp;作業一覧 - {$dtl_target_date}</span>
		</div>
		<div id="id_div_task_dtl_ope">
			<table id="id_tab_task_dtl_ope">
				<tr class="c_tr_task_dtl_ope">
					<td class="c_td_task_dtl_ope_btn" id="id_td_task_search_by_wc">
						作業コード：
						<select id="id_sel_task_search_by_wc" class="c_sel_task_search_by_wc">
							<option value="" {if $default_work_content_code == ""}selected{/if}></option>
						{foreach from=$ar_work_content_code key=l_wc_key item=l_wc_val name=fe_work_content_code}
							<option class="c_sel_wc_value" value="{$l_wc_val}"{if $default_work_content_code != "" && $l_wc_val == $default_work_content_code}selected{/if}>{$l_wc_val}</option>
						{/foreach}
						</select>
					</td>
					<td class="c_td_task_dtl_ope_btn">
						&nbsp;
					</td>
					<td class="c_td_task_dtl_ope_btn" id="id_td_task_dtl_delete">
						<input type="button" value="削除" class="c_btn_task_ope" id="id_btn_task_delete"/>
					</td>
				</tr>
			</table>
		</div>
		<div id="id_div_task_dtl_main">

			<table id="id_tab_task_dtl_main">
				<tr id="id_tr_task_dtl_ope">
					<td>
					{* ページ操作部 *}
						<table id="id_tab_ope_pagemenu" class="c_table_ope_pagemenu">
							{if $ope_pageitem_visible == 'ON'}
							<tr class="c_tr_ope_pagemenu">
								<td id="id_td_ope_pagemenu_reccnt" class="c_td_ope_pagemenu">
									{$ope_page_select_html}
								</td>
								<td id="id_td_ope_pagemenu_pgcnt" class="c_td_ope_pagemenu">
									<span>{$ope_rec_count}件が該当しました</span>
								</td>
								<td id="id_td_ope_pagemenu_btnpev" class="c_td_ope_btnmenu">
								{if $ope_prev_btn_visible == 'ON'}
									<input type="button" class="c_btn_ope_menu" id="id_btn_ope_prev" value="{$ope_prev_btn_value}" />
								{/if}
								</td>
								<td id="id_td_ope_pagemenu_btnnext" class="c_td_ope_btnmenu">
								{if $ope_next_btn_visible == 'ON'}
									<input type="button" class="c_btn_ope_menu" id="id_btn_ope_next" value="{$ope_next_btn_value}" />
								{/if}
								</td>
							</tr>
							{/if}
						</table>
					</td>
				</tr>
				<tr>
					<td>
					{* 明細表 *}
						<div id="id_div_task_dtl_table">
							<table id="id_tab_task_dtl_table" class="c_table_task_dtl_table">
								<tr id="id_tr_task_dtl_top" class="c_tr_task_dtl_top">
									<th class="c_th_task_dtl" id="id_th_task_valid">&nbsp;</th>
									<th class="c_th_task_dtl" id="id_th_task_check">
										<input id="id_chk_task_dtl_top" class="c_chk_task_dtl" type="checkbox" value="" />
									</th>
									<th class="c_th_task_dtl" id="id_th_task_staff">人員</th>
									<th class="c_th_task_dtl" id="id_th_task_report">概要</th>
									<th class="c_th_task_dtl" id="id_th_task_update">更新</th>
									<th class="c_th_task_dtl" id="id_th_task_work_content_code">作業コード</th>
									<th class="c_th_task_dtl" id="id_th_task_work_status">作業ステータス</th>
									<th class="c_th_task_dtl" id="id_th_task_work_date">作業日</th>
									<th class="c_th_task_dtl" id="id_th_task_work_arrangement_user_name">責任者</th>
									<th class="c_th_task_dtl" id="id_th_task_aggregate_point">集合場所</th>
									<th class="c_th_task_dtl" id="id_th_task_aggregate_timet">集合時間</th>
									<th class="c_th_task_dtl" id="id_th_task_default_entering_schedule_timet">入店予定時刻</th>
									<th class="c_th_task_dtl" id="id_th_task_default_leave_schedule_timet">退店予定時刻</th>
								</tr>
							{foreach from=$ar_task_dtl item=task_dtl_item name=fe_task_dtl}
								<tr id="id_tr_task_dtl{$smarty.foreach.fe_task_dtl.iteration}" class="c_tr_task_dtl">
									<td class="c_td_task_dtl">
								{* 有効無効表示 *}
									{if $task_dtl_item.VALIDITY_FLAG=="Y"}
										<input id="id_txt_task_valid{$smarty.foreach.fe_task_dtl.iteration}" class="c_txt_td_task_valid" type="text" value=" " readOnly="true" />
									{else}
										<input id="id_txt_task_valid{$smarty.foreach.fe_task_dtl.iteration}" class="c_txt_td_task_invalid" type="text" value=" " readOnly="true" />
									{/if}
								{* 作業IDの隠し項目 *}
										<input id="id_txt_work_content_id{$smarty.foreach.fe_task_dtl.iteration}" class="c_hd_td_task_id" type="hidden" value="{$task_dtl_item.WORK_CONTENT_ID}" />
									</td>
								{* チェックボックス *}
									<td class="c_td_task_dtl">
										<input id="id_chk_task_chk{$smarty.foreach.fe_task_dtl.iteration}" class="c_chk_task_report" type="checkbox" value="" />
									</td>
								{* 人員ボタン *}
									<td class="c_td_task_dtl">
										<input id="id_btn_task_dtl{$smarty.foreach.fe_task_dtl.iteration}" class="c_btn_task_dtl" type="button" value="人員" />
									</td>
								{* 詳細ボタン *}
									<td class="c_td_task_dtl">
										<input id="id_btn_task_report{$smarty.foreach.fe_task_dtl.iteration}" class="c_btn_task_report" type="button" value="概要" />
									</td>
								{* 更新ボタン *}
									<td class="c_td_task_dtl">
										<input id="id_btn_task_update{$smarty.foreach.fe_task_dtl.iteration}" class="c_btn_task_update" type="button" value="更新" />
									</td>
								{* 作業コード *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.WORK_CONTENT_CODE}
									</td>
								{* 作業ステータス *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.WORK_STATUS_NAME}
									</td>
								{* 作業日 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.WORK_DATE}
									</td>
								{* 責任者 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.WORK_ARRANGEMENT_USER_NAME}
									</td>
								{* 集合場所 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.AGGREGATE_POINT}
									</td>
								{* 集合時間 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.AGGREGATE_TIMET}
									</td>
								{* 入店予定時刻 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.DEFAULT_ENTERING_SCHEDULE_TIMET}
									</td>
								{* 退店予定時刻 *}
									<td class="c_td_task_dtl">
										{$task_dtl_item.DEFAULT_LEAVE_SCHEDULE_TIMET}
									</td>
								</tr>
							{foreachelse}
								<tr>
									<td colspan=14>該当する作業が存在しません</td>
								</tr>
							{/foreach}
							</table>
						<div>
					</td>
				</tr>
			</table>
		</div>
		<div id="id_div_task_dtl_bottom">
			<table id="id_tab_task_dtl_bottom">
				<tr>	
					<td id="id_td_task_dtl_ckb">
						<input type="checkbox" class="c_ckb_task_onlyvalid" id="id_ckb_task_onlyinvalid" value="" {if $valid_task_checkstat=="Y"}checked{/if}/>
						<span class="c_span_task_onlyvalid_expl">有効データのみ表示</span>
					</td>
					<td id="id_td_task_dtl_ckb_expl">
						&nbsp;
					</td>
					<td  id="id_td_task_dtl_insert">
						<input type="button" value="新規作成" class="c_btn_task_ope" id="id_btn_task_insert"/>
						<input type="button" value="再表示" class="c_btn_task_ope" id="id_btn_task_reload"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
