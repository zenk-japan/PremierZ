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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
	<meta content=text/html;charset=utf-8 http-equiv=Content-Type>
{foreach from=$ar_css_files item=css_file}
	<link rel="stylesheet" href="{$css_file}" type="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<script type="text/javascript" src="{$js_file}"></script>
{/foreach}
	<title>{$headtitle}</title>
</head>
<body>
<!-- BODY開始 -->
<div id="id_div_outer" >
	<div id="id_div_topline">
		<!-- 3色線 -->
		<h1 id="id_h1_topline">&nbsp;</h1>
		<table id="id_table_topline">
			<tr>
				<!-- ロゴ -->
				<td id="id_td_topline"></td>
			</tr>
		</table>
	</div>
	<div id="id_div_logout">
		<table id="id_table_logout">
			<tr>
				<td id="id_td_margin">&nbsp;</td>
		<!-- ユーザー名 -->
				<td id="id_td_user_name">ユーザー：{$user_name}</td>
		<!-- ログアウト -->
				<td id="id_td_logout"><a href="logout.php">ログアウト</a></td>
			</tr>
		</table>
	</div>
	<div id="id_div_hd">
		<hr id="id_hr_hd"></hr>
		<span id="id_span_hd">{$maintitle}</span>
	</div>
	<br>
	<div id="id_div_main">
	<!-- 検索項目とボタン -->
		<div id="id_div_main_top">
			<table id="id_table_main_top">
				<tr id="id_tr_main_btn">
					<td id="id_td_main_btn1">
					{foreach from=$ar_ope_button item=ope_btn_item name=fe_ope_btn}
						<input type="button" id="{$ope_btn_item.id}" class="{$ope_btn_item.class}" value="{$ope_btn_item.value}" />
					{/foreach}
					</td>
				</tr>
			</table>
		</div>
	<!-- メイン表示部 -->
		<div id="id_div_main_dtl">
			<span id="id_span_dtl_title">■設定項目</span>
			<table id="id_table_main_dtl">
				<tr class="c_tr_main_dtl_top" id="id_tr_main_dtl_top">
					<td class="c_td_main_dtl_top_no">No.</td>
					<td class="c_td_main_dtl_top_cap">項目名</td>
					<td class="c_td_main_dtl_top_val">設定値</td>
					<td class="c_td_main_dtl_top_rem">備考</td>
				</tr>
			{foreach from=$ar_main_data item=data_item name=fe_main_data}
				<tr class="c_tr_main_dtl">
					<td class="c_td_main_dtl_no">
						<input type="text" class="c_inp_main_dtl_no" readOnly="true" value="{$smarty.foreach.fe_main_data.iteration}" />
					</td>
					<td class="c_td_main_dtl_cap">
						<input type="text" class="c_inp_main_dtl_cap" readOnly="true" value="{$data_item.caption}" />
					</td>
					<td class="c_td_main_dtl_val">
					{if $data_item.type == "text"}
						<input type="text" class="c_inp_main_dtl_val" value="{$data_item.value}" name="{$data_item.caption}" />
					{elseif $data_item.type == "disp"}
						<input type="text" class="c_inp_main_dtl_val_disp" value="{$data_item.value}" name="{$data_item.caption}" readOnly="true" />
					{elseif $data_item.type == "texterea"}
						<textarea class="c_txta_main_dtl_val" name="{$data_item.caption}" >{$data_item.value}</textarea>
					{elseif $data_item.type == "list"}
						<select id="id_sel_main_dtl_val" name="{$data_item.caption}" class="c_sel_main_dtl_val">
						{foreach from=$data_item.listval key=list_caption item=list_value}
							<option value="{$list_value}"{if $list_value==$data_item.orgvalue} selected{/if}>{$list_caption}</option>
						{/foreach}
						</select>
					{/if}
						<input type="hidden" class="c_inp_main_dtl_orgval" value="{$data_item.orgvalue}" />
					</td>
					<td class="c_td_main_dtl_rem">
						<input type="text" class="c_inp_main_dtl_rem" readOnly="true" value="{$data_item.remarks}" />
					</td>
				</tr>
			{foreachelse}
				<tr class="c_tr_main_dtl_none">
					<td id="id_td_main_dtl_none" colspan=3>
						<span id="id_span_main_dtl_none">該当データがありませんでした</span>
					</td>
				</tr>
			{/foreach}
			</table>
		</div>
	</div>
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
	<br>
</div>
<!-- BODY終了 -->
</body>
</html>