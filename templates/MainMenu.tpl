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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
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
		<table id="id_table_topline">
			<tr>
				<td id="id_td_topline" class="c_td_topmenu">&nbsp;</td>
				<!-- ログイン名 -->
				<td id="id_td_topline_name" class="c_td_topmenu">{$user_auth}:{$user_name} 様</td>
				<td id="id_td_topline_btn1" class="c_td_topmenu c_td_topline_btn">
				<!-- ボタン1 -->
					&nbsp;
				</td>
				<td id="id_td_topline_btn2" class="c_td_topmenu c_td_topline_btn">
				<!-- ボタン2 -->
					<input id="id_btn_topline_logout" class="c_btn_topline_menu" type="button" value=" " title="ログアウト"/>
				</td>
			</tr>
		</table>
	</div>
<!-- タイトル -->
	<div id="id_div_menubar">
		<table id="id_table_menubar">
			<tr>
				<td class="c_td_menubar">&nbsp;{*{$systemname} メインメニュー*}</td>
			</tr>
		</table>
	</div>
<!-- メニュー -->
	<div id="id_div_menu">
		<table id="id_table_menu_outer">
			<tr class="c_tr_menu_outer">
				{foreach from=$ar_menu item=menu_item name=fe_menu_table}
				{if $menu_item.mode == "RETURN"}
			</tr>
			<tr class="c_tr_menu_outer">
				{else}
				<td id="{$menu_item.tdid}" class="c_td_menu_outer">
					<div id="id_div_menu{$smarty.foreach.fe_menu_table.iteration}" class="c_div_menu">
						<table class="c_table_menu_inner">
							<tr class="c_tr_menu_inner_top">
								<td class="c_td_menu_inner_logo" rowspan=2>
									<!-- ロゴ -->
									<img src="{$menu_item.logo}" />
								</td>
								<td class="c_td_menu_inner_title">
									<!-- タイトル -->
									<span class="c_span_menu_inner_title">{$menu_item.title}</span>
								</td>
							</tr>
							<tr class="c_tr_menu_inner_buttom">
								<td class="c_td_menu_inner_remarks">
									<!-- 説明 -->
									<span class="c_span_menu_inner_remarks">{$menu_item.remarks}</span>
								</td>
							</tr>
						</table>
					</div>
				</td>
				{/if}
				{/foreach}
			</tr>
		</table>
	</div>
<!-- 新着情報 -->
	<div id="id_div_whatsnew">
	</div>
	<div id="id_div_hidden">
		<form id="id_form_hidden">
		{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
		<input type="hidden" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
		{/foreach}
		</form>
	</div>
	<!-- コピーライト -->
	<div id="id_div_copyright">{$txt_copyright}</div>
</div>
<!-- BODY終了 -->
</body>
</html>