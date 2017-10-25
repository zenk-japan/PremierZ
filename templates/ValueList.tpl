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
<HTML>
<HEAD>
	<META content=text/html;charset=utf-8 http-equiv=Content-Type>
{foreach from=$ar_css_files item=css_file}
	<LINK REL="stylesheet" HREF="{$css_file}" TYPE="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<SCRIPT type="text/javascript" src="{$js_file}"></SCRIPT>
{/foreach}
	<TITLE>{$headtitle}</TITLE>
</HEAD>
<BODY>
<!-- BODY開始 -->
	<!-- タイトル -->
	<DIV ID="lv_title" CLASS="css_lv_titile">
		{$as_title}
	</DIV>
	<HR>
	<!-- ヘッダー部 -->
	<DIV ID="lv_header" CLASS="css_lv_header">
		<TABLE ID="lv_header_table" CLASS="css_lv_header_table" CELLSPACING="0" CELLPADDING="0">
			<TR>
			{foreach from=$ar_header item=cond_item}
				<TD CLASS="css_lv_header_td">
				{if $cond_item.type == "disp"}
					<INPUT TYPE="text" CLASS="css_lv_header_item_textro" VALUE="{$cond_item.value}" readOnly=true>
				{else}
					<INPUT TYPE="{$cond_item.type}" CLASS="css_lv_header_item{$cond_item.type}" VALUE="{$cond_item.value}">
				{/if}
				</TD>
			{foreachelse}
				<P>ヘッダー部表示可能項目がありません。</P>
			{/foreach}
			</TR>
			<TR>
			{foreach from=$ar_header item=cond_item}
				<TD CLASS="css_lv_header_cond_td">
					<INPUT TYPE="button" CLASS="css_lv_header_item_btn" VALUE="CL">
					<INPUT ID="{$cond_item.id}" TYPE="text" CLASS="css_lv_header_item_textw" VALUE="">
				</TD>
			{foreachelse}
				<P>ヘッダー部表示可能項目がありません。</P>
			{/foreach}
			</TR>
		</TABLE>
	</DIV>
	<HR>
	<!-- 明細部 -->
	<DIV ID="lv_detail" CLASS="css_lv_detail">
		<TABLE ID="lv_detail_table" CLASS="css_lv_detail_table" CELLSPACING="0" CELLPADDING="0">
		{foreach from=$ar_detail key=detail_rec_num item=detail_rec}
			<TR CLASS="css_lv_dtl_tr" ID="lv_dtl_tr_{$detail_rec_num}">
			{foreach from=$detail_rec item=detail_item}
				<TD CLASS="css_lv_dtl_td">
				{if $detail_item.type == "disp"}
					<INPUT ID="{$detail_item.id}" TYPE="text" CLASS="css_lv_dtl_item_textro" VALUE="{$detail_item.value}" readOnly=true>
				{else}
					<INPUT ID="{$detail_item.id}" TYPE="text" CLASS="css_lv_dtl_item_txtret" VALUE="{$detail_item.value}" readOnly=true>
				{/if}
				</TD>
			{/foreach}
			</TR>
		{foreachelse}
			<P>明細部表示可能項目がありません。</P>
		{/foreach}
		</TABLE>
	</DIV>

	<HR>
	<!-- ボタン部 -->
	<DIV ID="lv_buttons">
	{foreach from=$ar_buttons item=ar_button_set}
		<INPUT TYPE="BUTTON" ID="{$ar_button_set.btid}" VALUE="{$ar_button_set.btcap}" CLASS="{$ar_button_set.btclass}"></INPUT>
	{foreachelse}
	    <P>ボタン配置エラー、ボタンがありません。</P>
	{/foreach}
	</DIV>
	<!-- メッセージ表示部 -->
	<DIV ID="lv_message" CLASS="css_lv_message">
		<INPUT ID="lv_output" TYPE="hidden" VALUE="" CLASS="css_lv_output"></INPUT>
	</DIV>
	<!-- GET 項目部 -->
	<DIV ID="vl_get_value" CLASS="css_vl_get_value">
	{foreach from=$ar_get_value key=value_name item=get_value}
		<INPUT TYPE="HIDDEN" ID="{$value_name}" VALUE="{$get_value}" CLASS="css_get_value_item"></INPUT>
	{/foreach}
	</DIV>
	<!-- パラメータ部 -->
	<DIV ID="lv_param" CLASS="css_lv_param">
		<INPUT ID="lv_param1" TYPE="hidden" VALUE="" CLASS="css_lv_param1"></INPUT>
	</DIV>
<!-- BODY終了 -->
</BODY>
</HTML>