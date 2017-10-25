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
	<DIV ID="cal_title" CLASS="css_cal_title">
		<P CLASS="css_title_strings">{$as_title}</P>
	</DIV>
	<!-- 検索部 -->
	<DIV ID="cal_cond" CLASS="css_cal_cond">
		<TABLE CLASS="css_cal_cond_table">
			<TR CLASS="css_cal_cond_table_tr">
				<TD><INPUT ID="cal_cond_bt_left" CLASS="css_cal_cond_bt_left" TYPE="BUTTON" VALUE=""></TD>
				<TD><INPUT ID="cal_cond_date" CLASS="css_cal_cond_date" TYPE="TEXT" READONLY VALUE="" TITLE="ダブルクリックで今月に戻る"></TD>
				<TD><INPUT ID="cal_cond_bt_right" CLASS="css_cal_cond_bt_right" TYPE="BUTTON" VALUE=""></TD>
			</TR>
		</TABLE>
	</DIV>
	<HR>
	<!-- 明細部 -->
	<DIV ID="cal_detail" CLASS="css_cal_detail">
		<TABLE CLASS="css_cal_dtl_table">
			<TR CLASS="css_cal_dtl_table_tr1">
				<TH><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="TEXT" READONLY VALUE="SUN"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="TEXT" READONLY VALUE="MON"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="TEXT" READONLY VALUE="TUE"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="TEXT" READONLY VALUE="WED"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="TEXT" READONLY VALUE="THU"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="TEXT" READONLY VALUE="FRI"></INPUT></TH>
				<TH><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="TEXT" READONLY VALUE="SAT"></INPUT></TH>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
			<TR CLASS="css_cal_dtl_table_tr2">
				<TD><INPUT CLASS="css_cal_dtl_table_tdsun" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdmon" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdtue" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdwed" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdthu" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdfri" TYPE="BUTTON" VALUE=""></INPUT></TD>
				<TD><INPUT CLASS="css_cal_dtl_table_tdsat" TYPE="BUTTON" VALUE=""></INPUT></TD>
			</TR>
		</TABLE>
	</DIV>
	<HR>
	<!-- ボタン部 -->
	<DIV ID="cal_buttons">
	{foreach from=$ar_buttons item=ar_button_set}
		<INPUT TYPE="BUTTON" ID="{$ar_button_set.btid}" VALUE="{$ar_button_set.btcap}" CLASS="{$ar_button_set.btclass}"></INPUT>
	{foreachelse}
	    <P>ボタン配置エラー、ボタンがありません。</P>
	{/foreach}
		<INPUT TYPE="HIDDEN" ID="test_text" VALUE="" style={"width:80px;height:20px;"}></INPUT>
	</DIV>
	<!-- GET 項目部 -->
	<DIV ID="cal_get_value" CLASS="css_cal_get_value">
	{foreach from=$ar_get_value key=value_name item=get_value}
		<INPUT TYPE="HIDDEN" ID="{$value_name}" VALUE="{$get_value}" CLASS="css_get_value_item"></INPUT>
	{/foreach}
	</DIV>

<!-- BODY終了 -->
</BODY>
</HTML>