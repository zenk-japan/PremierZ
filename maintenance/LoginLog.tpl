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
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
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
			<span id="id_span_cond_title">■検索項目</span><br>
			<table id="id_table_search">
				<tr class="c_tr_search">
					<td class="c_td_search">
						<input type="text" style="width:10em" class="c_disp_search" value="{$dsp_src_user_code}" readOnly="true" />
						<input type="text" id="id_txt_user_code" style="width:15em" class="c_text_search" value="{$txt_src_user_code}" />
					</td>
				</tr>
				<tr class="c_tr_search">
					<td class="c_td_search">
						<input type="text" style="width:10em" class="c_disp_search" value="{$dsp_src_comp_code}" readOnly="true" />
						<input type="text" id="id_txt_comp_code" style="width:15em" class="c_text_search" value="{$txt_src_comp_code}" />
					</td>
				</tr>
				<tr class="c_tr_search">
					<td class="c_td_search">
						<input type="text" style="width:10em" class="c_disp_search" value="{$dsp_src_okng}" readOnly="true" />
						<label><input type="radio" name="nm_rb_okng" id="id_rb_ok" value="1" {$okng_ok} />OKのみ</label>
						<label><input type="radio" name="nm_rb_okng" id="id_rb_ng" value="2" {$okng_ng} />NGのみ</label>
						<label><input type="radio" name="nm_rb_okng" id="id_rb_all" value="0" {$okng_all} />すべて</label>
					</td>
				</tr>
				<tr class="c_tr_search">
					<td class="c_td_search">
						<input type="text" style="width:10em" class="c_disp_search" value="{$dsp_src_date_cap}" readOnly="true" />
						<input type="text" id="id_dsp_src_date_from" style="width:5em" class="c_disp_search" value="{$dsp_src_date_from}" readOnly="true" />
						<input type="text" id="id_txt_src_date_from" style="width:15em" class="c_text_search" value="{$txt_src_date_from}" />
						<span class="c_span_search">&nbsp;&gt;&gt;&nbsp;</span>
						<input type="text" id="id_dsp_src_date_to" style="width:5em" class="c_disp_search" value="{$dsp_src_date_to}" readOnly="true" />
						<input type="text" id="id_txt_src_date_to" style="width:15em" class="c_text_search" value="{$txt_src_date_to}" />
						<span class="c_span_search">&nbsp;&nbsp;<font size="-1">※YYYY-MM-DD形式で指定</font></span>
					</td>
				</tr>
				<tr class="c_tr_search">
					<td class="c_td_search">
						<input type="button" id="id_btn_search" class="c_btn_main_nomal" value="検索" />
						<input type="button" id="id_btn_default" class="c_btn_main_nomal" value="検索条件を戻す" />
						<input type="button" id="id_btn_clear" class="c_btn_main_nomal" value="検索条件クリア" />
						<input type="button" id="id_btn_gomenu" class="c_btn_main_nomal" value="メニューに戻る" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" id="id_btn_erasev" class="c_btn_main_nomal" value="検索結果をパージ" />
					</td>
				</tr>
			</table>
		</div>
		<br>
	<!-- メイン表示部 -->
			<span id="id_span_dtl_title">■明細表</span>
		<div id="id_div_main_dtl">
			<table id="id_table_main_dtl">
			<!-- ページ操作部 -->
				<tr id="id_tr_main_pageope">
					<td id="id_td_main_po">
							{$html_div_po}
					</td>
				</tr>
			<!-- 見出し部 -->
				<tr id="id_tr_main_head">
					<td id="id_td_main_hd">
						<div id="id_div_dtl_hd">
							{$html_div_head}
						</div>
					</td>
				</tr>
			<!-- 明細部 -->
				<tr id="id_tr_main_dtl">
					<td id="id_td_main_dtl">
						<div id="id_div_dtl">
							{$html_div_dtl}
						</div>
					</td>
				</tr>
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