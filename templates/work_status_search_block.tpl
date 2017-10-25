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
<!-- メイン -->
	<div id="id_div_search">
		<div id="id_div_search_title">
			作業検索
		</div>
		<table id="id_table_search">
			<tr class="c_tr_search">
				<td class="c_td_serch_text">
					作業日
				</td>
				<td class="c_td_search_input">
					<input id="id_txt_search_work_date" class="c_txt_search_textbox" type="text" value="{$default_work_date}" />
				</td>
			</tr>
			<tr class="c_tr_search">
				<td class="c_td_serch_text">
					エンドユーザー
				</td>
				<td class="c_td_search_input">
					<input id="id_txt_search_end_user" class="c_txt_search_textbox" type="text" value="{$default_end_user}" />
				</td>
			</tr>
			<tr class="c_tr_search">
				<td class="c_td_serch_text">
					作業名
				</td>
				<td class="c_td_search_input">
					<input id="id_txt_search_work_name" class="c_txt_search_textbox" type="text" value="{$default_work_name}" />
				</td>
			</tr>
			<tr class="c_tr_search">
				<td id="id_td_search_menu_btn_search">
					<input class="c_btn_search" type="button" value="検索" id="id_btn_search" />
				</td>
				<td id="id_td_search_menu_btn_clear">
					<input class="c_btn_search" type="button" value="クリア" id="id_btn_cond_clear" />
				</td>
			</tr>
			<tr class="c_tr_search">
				<td class="c_td_search_notice" colspan=2>
					作業状況を確認したい作業を下表から選択して下さい。
				</td>
			</tr>
		</table>
		<table id="id_table_list">
			<tr>
				<th class="c_th_list_work_name">
					作業名
				</th>
				<th class="c_th_list_end_user">
					エンドユーザー
				</th>
			</tr>
			{foreach from=$ar_work_contents key=l_rec_num item=lr_work_contents name=fe_work_contents}
			<tr class="c_tr_list">
				<td class="c_td_list_work_name">
					<div class="c_div_work_contents_list">
						{$lr_work_contents.WORK_NAME}
						<input id="id_hd_work_content_id{$smarty.foreach.fe_work_contents.iteration}" class="c_hd_work_content_id" type="hidden" value="{$lr_work_contents.WORK_CONTENT_ID}"/>
					</div>
				</td>
				<td class="c_td_list_end_user">
					<div class="c_div_work_contents_list">
						{$lr_work_contents.ENDUSER_COMPANY_NAME_SHORT}
					</div>
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan=2>
					該当する作業がありません
				</td>
			</tr>
			{/foreach}
		</table>
	</div>

