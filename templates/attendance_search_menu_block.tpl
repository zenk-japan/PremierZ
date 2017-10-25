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
					年月(YYYY-MM)
				</td>
				<td class="c_td_search_input">
					<input title="ダブルクリックでカレンダー表示" id="id_txt_cond_work_ym" size ="30" name="WORK_DATE" title="" type="text" value="{$cond_work_ym}" class="c_txt_search_textbox" />
				</td>
			</tr>
			{if $output_unit_mode=='WORK'}
			<tr>
				<td class="c_td_serch_text">
					作業名
				</td>
				<td class="c_td_search_input">
					<input title="ダブルクリックでリスト表示" id="id_txt_cond_work_name" size ="30" name="WORK_NAME" title="" type="text" value="{$cond_work_name}" class="c_txt_search_textbox" />
				</td>
			</tr>
			{/if}
			<tr>
				<td class="c_td_serch_text">
					作業者名
				</td>
				<td class="c_td_search_input">
					<input {if $workuser_fix_flag!='Y'}title="ダブルクリックでリスト表示"{/if} id="id_txt_cond_workuser_name{if $workuser_fix_flag=='Y'}_ro{/if}" size ="30" name="WORK_USER_NAME" title="" type="text" value="{$cond_workuser_name}" class="c_txt_search_textbox{if $workuser_fix_flag=='Y'}_ro{/if}" {if $workuser_fix_flag=='Y'}readOnly{/if}/>
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
<!-- 出力設定 -->
	<div id="id_div_output_style">
		{* タイトル *}
		<div id="id_div_output_style_title">
			<span>{$output_style_title}</span>
		</div>
		<div id="id_div_output_style_menu">
			<table id="id_table_output_style_menu">
		{* 纏め単位 *}
				<tr class="c_tr_output_style_menu">
					<td class="c_td_output_style_menu_cap">&nbsp;出力単位</td>
					<td class="c_td_output_style_menu">
						<select id="id_select_output_unit" name="OUTPUT_UNIT" class="c_select_output_style_menu">
							{foreach from=$ar_output_unit key=output_unit_key item=output_unit_item}
							<option value="{$output_unit_key}" {if $output_unit_default == $output_unit_key}selected="selected" {/if}>{$output_unit_item}</option>
							{/foreach}
						</select>
					</td>
				</tr>
		{* 丸め基準時間 *}
				<tr class="c_tr_output_style_menu">
					<td class="c_td_output_style_menu_cap">&nbsp;丸め基準時間(分)</td>
					<td class="c_td_output_style_menu">
						<select id="id_select_round_base"  name="ROUND_BASE" class="c_select_output_style_menu">
							{foreach from=$ar_round_base key=round_base_key item=round_base_item}
							<option value="{$round_base_key}" {if $round_base_default == $round_base_key}selected="selected" {/if}>{$round_base_item}</option>
							{/foreach}
						</select>
					</td>
				</tr>
		{* 丸め方法 *}
				<tr class="c_tr_output_style_menu">
					<td class="c_td_output_style_menu_cap">&nbsp;丸め方法</td>
					<td class="c_td_output_style_menu">
						<select id="id_select_round_method"  name="ROUND_METHOD" class="c_select_output_style_menu">
							{foreach from=$ar_round_method key=round_method_key item=round_method_item}
							<option value="{$round_method_key}" {if $round_method_default == $round_method_key}selected="selected" {/if}>{$round_method_item}</option>
							{/foreach}
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>