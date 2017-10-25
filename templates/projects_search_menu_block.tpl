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
					<table id = "id_table_cond_title">
						<tr>
							<td id="id_td_cond_title">
								プロジェクト検索
							</td>
						</tr>
					</table>
					<table id = "id_table_cond">
						<tr>
							<td class="c_td_cond_caption">
								見積コード
							</td>
							<td class="c_td_cond_input">
								<input type="text" id="id_txt_cond_estimate_code" class="c_txt_search_textbox" value="{$estimate_code}"/>
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								作業名
							</td>
							<td class="c_td_cond_input">
								<input type="text" id="id_txt_cond_work_name" class="c_txt_search_textbox" value="{$work_name}"/>
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								エンドユーザー会社
							</td>
							<td class="c_td_cond_input">
								<input type="text" id="id_txt_cond_enduser_company_name" class="c_txt_search_textbox" value="{$enduser_company_name}"/>
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								依頼元会社
							</td>
							<td class="c_td_cond_input">
								<input type="text" id="id_txt_cond_request_company_name" class="c_txt_search_textbox" value="{$request_company_name}"/>
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								見積担当者
							</td>
							<td class="c_td_cond_input">
								<input type="text" id="id_txt_cond_estimate_user_name" class="c_txt_search_textbox" value="{$estimate_user_name}"/>
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								注文区分
							</td>
							<td class="c_td_cond_input">
							{if ($ar_order_division|@count) > 0}
								<select id="id_sel_cond_order_division" class="c_sel_cond" name="nm_order_division_name">
									<option value=""></option>
								{foreach from=$ar_order_division key=l_od_key item=l_od_val name=fe_order_division}
									<option value="{$l_od_key}"{if $l_od_key==$selected_order_devision} selected{/if}>{$l_od_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_caption">
								作業区分
							</td>
							<td class="c_td_cond_input">
							{if ($ar_work_division|@count) > 0}
								<select id="id_sel_cond_work_division" class="c_sel_cond" name="nm_work_division_name">
									<option value=""></option>
								{foreach from=$ar_work_division key=l_wd_key item=l_wd_val name=fe_work_division}
									<option value="{$l_wd_key}"{if $l_wd_key==$selected_work_devision} selected{/if}>{$l_wd_val}</option>
								{/foreach}
								</select>
							{else}
								マスターに設定がありません
							{/if}
							</td>
						</tr>
						<tr>
							<td class="c_td_cond_search_btn">
								<input id="id_btn_prj_search" class="c_btn_cond_search_btn" type="button" value="検索"/>
							</td>
							<td class="c_td_cond_clear_btn">
								<input id="id_btn_prj_cond_clear" class="c_btn_cond_search_btn" type="button" value="クリア"/>
							</td>
						</tr>
					</table>
