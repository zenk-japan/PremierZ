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
<!-- 検索 -->
	<div id="id_div_search_menu">
		{* タイトル *}
		<div id="id_div_search_menu_title">
			<span>{$cond_title}</span>
		</div>
		{* 検索用表 *}
		<table id="id_table_search">
			<tr>
				<td class="c_td_serch_text">
					会社名
				</td>
				<td class="c_td_search_input">
					<input id="id_txt_cond_comp_name" size ="30" name="COMPANY_NAME" title="" type="text" value="{$cond_comp_name}" class="c_txt_search_textbox" />
				</td>
			</tr>
			<tr>
				<td class="c_td_serch_text">
					会社区分
				</td>
				<td class="c_td_search_input">
					<select id="id_select_comp_class" name="COMP_CLASS" class="c_select_search_input">
						{foreach from=$ar_comp_class key=comp_class_key item=comp_class_item}
						<option value="{$comp_class_key}" {if $comp_class_default == $comp_class_key}selected="selected" {/if}>{$comp_class_item}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
		{* ボタン *}
		<div id="id_div_search_menu_btn">
			<table id="id_table_search_menu_btn">
				<tr>
					<td id="id_td_search_menu_btn_search">
						<input id="id_btn_input_search" type="button" class="c_btn_search" value="検索" />
					</td>
					<td id="id_td_search_menu_btn_clear">
						<input id="id_btn_input_clear" type="button" class="c_btn_search" value="条件クリア" />
					</td>
				</tr>
			</table>
		</div>
	</div>
