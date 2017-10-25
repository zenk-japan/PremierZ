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
					{*プロジェクト一覧*}
					<div id="id_div_prj_dtail" class="c_div_prj_dtail">
						<div id="id_div_prj_dtail_top" class="c_div_prj_dtail_top">
							<table id="id_table_prj_detail_title" class="c_table_prj_detail_title">
								<tr>
									<td id="id_td_prj_detail_title">
										プロジェクト一覧
									</td>
								</tr>
							</table>
							<table id="id_table_prj_pagemenu" class="c_table_prj_pagemenu">
								{if $prj_pageitem_visible == 'ON'}
								<tr class="c_tr_prj_pagemenu">
									<td id="id_td_prj_pagemenu_reccnt" class="c_td_prj_pagemenu">
										{$prj_page_select_html}
									</td>
									<td id="id_td_prj_pagemenu_pgcnt" class="c_td_prj_pagemenu">
										<span>{$prj_rec_count}件が該当しました</span>
									</td>
									<td id="id_td_prj_pagemenu_btnpev" class="c_td_prj_btnmenu">
									{if $prj_prev_btn_visible == 'ON'}
										<input type="button" class="c_btn_prj_menu" id="id_btn_prj_prev" value="{$prj_prev_btn_value}" />
									{/if}
									</td>
									<td id="id_td_prj_pagemenu_btnnext" class="c_td_prj_btnmenu">
									{if $prj_next_btn_visible == 'ON'}
										<input type="button" class="c_btn_prj_menu" id="id_btn_prj_next" value="{$prj_next_btn_value}" />
									{/if}
									</td>
								</tr>
								{/if}
							</table>
						</div>
						{* プロジェクト一覧リスト表 *}
						<div id="id_div_prj_dtail_table" class="c_div_prj_detail_table">
							<table id="id_table_prj_dtail_table" class="c_table_prj_dtail_table">
								<tr id="id_tr_prj_detail_top" class="c_tr_prj_detail_top">
									<th class="c_th_prj_detail" id="id_th_prj_valid">&nbsp;</th>
									<th class="c_th_prj_detail" id="id_th_prj_detail">作業</th>
									<th class="c_th_prj_detail" id="id_th_prj_report">概要</th>
									<th class="c_th_prj_detail" id="id_th_prj_update">更新</th>
									<th class="c_th_prj_detail" id="id_th_prj_estimate_code">見積コード</th>
									<th class="c_th_prj_detail" id="id_th_prj_sub_number">枝番</th>
									<th class="c_th_prj_detail" id="id_th_prj_delete">削除</th>
									<th class="c_th_prj_detail" id="id_th_prj_work_name">作業名</th>
									<th class="c_th_prj_detail" id="id_th_prj_schedule_from_date">作業開始予定日</th>
									<th class="c_th_prj_detail" id="id_th_prj_schedule_to_date">作業終了予定日</th>
									<th class="c_th_prj_detail" id="id_th_prj_enduser_company_name">エンドユーザ会社</th>
									<th class="c_th_prj_detail" id="id_th_prj_request_company_name">依頼元会社</th>
									<th class="c_th_prj_detail" id="id_th_prj_estimate_user_name">見積担当者</th>
									<th class="c_th_prj_detail" id="id_th_prj_order_division_name">注文区分</th>
									<th class="c_th_prj_detail" id="id_th_prj_work_division_name">作業区分</th>
								</tr>
							{foreach from=$ar_prj_detail item=prj_detail_item name=fe_prj_detail}
								<tr id="id_tr_prj_detail{$smarty.foreach.fe_prj_detail.iteration}" class="c_tr_prj_detail">
									<td class="c_td_prj_detail">
								{* 有効無効表示 *}
									{if $prj_detail_item.VALIDITY_FLAG=="Y"}
										<input id="id_txt_prj_valid{$smarty.foreach.fe_prj_detail.iteration}" class="c_txt_td_prj_valid" type="text" value=" " readOnly="true" />
									{else}
										<input id="id_txt_prj_valid{$smarty.foreach.fe_prj_detail.iteration}" class="c_txt_td_prj_invalid" type="text" value=" " readOnly="true" />
									{/if}
								{* 見積IDの隠し項目 *}
										<input id="id_txt_prj_estimate_id{$smarty.foreach.fe_prj_detail.iteration}" class="c_hd_td_prj_id" type="hidden" value="{$prj_detail_item.ESTIMATE_ID}" />
									</td>
								{* 作業ボタン *}
									<td class="c_td_prj_detail">
										<input id="id_btn_prj_detail{$smarty.foreach.fe_prj_detail.iteration}" class="c_btn_prj_detail" type="button" value="作業" />
									</td>
								{* 概要ボタン *}
									<td class="c_td_prj_detail">
										<input id="id_btn_prj_report{$smarty.foreach.fe_prj_detail.iteration}" class="c_btn_prj_report" type="button" value="概要" />
									</td>
								{* 更新ボタン *}
									<td class="c_td_prj_detail">
										<input id="id_btn_prj_update{$smarty.foreach.fe_prj_detail.iteration}" class="c_btn_prj_update" type="button" value="更新" />
									</td>
								{* 見積コード *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.ESTIMATE_CODE}
										<input id="id_txt_prj_estimate_code{$smarty.foreach.fe_prj_detail.iteration}" class="c_hd_td_prj_id" type="hidden" value="{$prj_detail_item.ESTIMATE_CODE}-{$prj_detail_item.SUB_NUMBER}" />
									</td>
								{* 枝番 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.SUB_NUMBER}
									</td>
								{* 削除ボタン *}
									<td class="c_td_prj_detail">
										<input id="id_btn_prj_delete{$smarty.foreach.fe_prj_detail.iteration}" class="c_btn_prj_delete" type="button" value="削除" />
									</td>
								{* 作業名 *}
									<td class="c_td_prj_detail" title="{$prj_detail_item.WORK_NAME}">
										{$prj_detail_item.WORK_NAME_SHORT}
									</td>
								{* 作業予定開始 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.SCHEDULE_FROM_DATE}
									</td>
								{* 作業予定終了 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.SCHEDULE_TO_DATE}
									</td>
								{* エンドユーザ会社 *}
									<td class="c_td_prj_detail" title="{$prj_detail_item.ENDUSER_COMPANY_NAME}">
										{$prj_detail_item.ENDUSER_COMPANY_NAME_SHORT}
									</td>
								{* 依頼元会社 *}
									<td class="c_td_prj_detail" title="{$prj_detail_item.REQUEST_COMPANY_NAME}">
										{$prj_detail_item.REQUEST_COMPANY_NAME_SHORT}
									</td>
								{* 見積担当者 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.ESTIMATE_USER_NAME}
									</td>
								{* 注文区分 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.ORDER_DIVISION_NAME}
									</td>
								{* 作業区分 *}
									<td class="c_td_prj_detail">
										{$prj_detail_item.WORK_DIVISION_NAME}
									</td>
								</tr>
							{foreachelse}
								<tr>
									<td colspan=14>該当するプロジェクトが存在しません</td>
								</tr>
							{/foreach}
							</table>
						</div>
						<div id="id_div_prj_detail_bottom">
							<table id="id_table_prj_detail_bottom">
								<tr>	
									<td id="id_td_prj_detail_ckb">
										<input type="checkbox" class="c_ckb_prj_onlyvalid" id="id_ckb_prj_onlyinvalid" value="" {if $valid_prj_checkstat=="Y"}checked{/if}/>
										<span class="c_span_prj_onlyvalid_expl">有効データのみ表示</span>
									</td>
									<td id="id_td_prj_detail_ckb_expl">
										&nbsp;
									</td>
									<td id="id_td_prj_detail_insert">
										<input type="button" value="新規作成" class="c_btn_prj_detail_btm" id="id_btn_prj_insert"/>
										<input type="button" value="一括登録" class="c_btn_prj_detail_btm" id="id_btn_xls_import"/>
										<input type="button" value="再表示" class="c_btn_prj_detail_btm" id="id_btn_prj_reload"/>
									</td>
								</tr>
							</table>
						</div>
					</div>
