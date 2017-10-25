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
<?xml version="1.0" encoding="{$char_code}"?>
<!-- / -->
{$doctype}
<html{$xmlns}>
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title>{$headtitle}</title>
</head>
<body>
	<DIV>
	<!-- イメージファイル -->
	<IMG src="{$img_logo}" alt=""><HR>
	<font color="#6495ED"><b>{$headtitle}</b></font>
	</DIV>
	<!-- メッセージ出力 -->
	<DIV>
	</DIV>
	<DIV>
	{$headinfo}<BR>
	</DIV>
<!--	<DIV>-->
	<!-- 入力項目 -->
	<form action="{$worksituation_url}" method="GET">
	【作業日】<BR>
	FROM <SELECT name="F_WORK_YEAR">
	{foreach from = $f_workyear_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<SELECT name="F_WORK_MONTH">
	{foreach from = $f_workmonth_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<SELECT name="F_WORK_DATE">
	{foreach from = $f_workday_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<BR>
	TO&nbsp;&nbsp; <SELECT name="T_WORK_YEAR">
	{foreach from = $t_workyear_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<SELECT name="T_WORK_MONTH">
	{foreach from = $t_workmonth_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<SELECT name="T_WORK_DATE">
	{foreach from = $t_workday_list key=row_num item=row_rec}
			<option value={$row_rec.VALUE} {$row_rec.SELECTED}>{$row_rec.VIEW}</option>
	{foreachelse}
	{/foreach}
	</SELECT>
	
	<BR>
	【作業名】<BR>
	<INPUT name="WORK_NAME" type="text" value="{$work_name}" size="40" istyle="1" {$input_style}><BR>
	<INPUT name="token" type="hidden" value="{$token}">
	【拠点名】<BR>
	<INPUT name="BASE_NAME" type="text" value="{$base_name}" size="40" istyle="1" {$input_style}><BR>
	<INPUT name="RETRIEVA" type="submit" value="検索" ><BR>
	</form>
	<form action="{$worksituation_url}" method="GET">
	<!-- ボタン -->
	
	</form>	
	<BR>

<!--	<DIV>-->
	<!-- 項目表示 -->
	<form>
	{foreach from=$ar_workstaff key=row_num item=row_rec}
	{if $row_rec.DATE_CHECK != 1 || $row_rec.NAME_CHECK != 1 || $row_rec.BASE_CHECK != 1}
		<BR>
	{/if}
	{if $row_rec.DATE_CHECK != 1}
		<hr>
		<font color="#FFA500">
		【作業日】{$row_rec.WORK_DATE_SHORT}<BR>
		</font>
	{/if}
	{if $row_rec.DATE_CHECK != 1 || $row_rec.NAME_CHECK != 1}
		<font color="#32CD32">
		【作業名】{$row_rec.WORK_NAME_SHORT}<BR>
		</font>
	{/if}
	{if $row_rec.DATE_CHECK != 1 || $row_rec.NAME_CHECK != 1 || $row_rec.BASE_CHECK != 1}
		<font color="#BF00DF">
		【拠点名】{$row_rec.WORK_BASE_NAME_SHORT}<BR>
		</font>
		作業者名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;承&nbsp;出&nbsp;入&nbsp;退<BR>
	{/if}
	<a href="worksituationdetail.php?token={$token}&wsid={$row_rec.WORK_STAFF_ID}&wdate={$row_rec.WORK_DATE}">{$row_rec.WORK_USER_NAME_SHORT}</a>	
	{if $row_rec.NAME_COUNT != 0}
		{section name=cnt start=0  loop=$row_rec.NAME_COUNT}
			&nbsp;
		{/section}
	{/if}
	{if $row_rec.CANCEL_DIVISION == "WC"}
	＊
	{elseif $row_rec.APPROVAL_DIVISION == "AP"}
	○
	{elseif $row_rec.APPROVAL_DIVISION == "NO"}
	△
	{elseif $row_rec.APPROVAL_DIVISION == "UA"}
	×
	{else}
	‐
	{/if}
	{if $row_rec.CANCEL_DIVISION == "WC"}
	＊
	{elseif $row_rec.DISPATCH_STAFF_TIMET == ""}
	×
	{else}
	○
	{/if}
	{if $row_rec.CANCEL_DIVISION == "WC"}
	＊
	{elseif $row_rec.ENTERING_STAFF_TIMET == ""}
	×
	{else}
	○
	{/if}
	{if $row_rec.CANCEL_DIVISION == "WC"}
	＊
	{elseif $row_rec.LEAVE_STAFF_TIMET == ""}
	×
	{else}
	○
	{/if}
	<BR>
	{foreachelse}
		{if $error_messa != ""}
			{$error_messa}
		{else}
			指定された現地作業は登録されていません。
		{/if}
	{/foreach}
	</form>
	<HR>
	<!-- ハイパーリンク -->
	<DIV>
	{foreach from=$fmlink item=link_line}
		{if $link_line.value == "RETURN"}
		<BR>
		{elseif $link_line.value != ""}
			{if $terminal == "SoftBank" && $model != "iPhone" }
			<a href="{$link_line.link_url}" directkey="{$link_line.key}" nonumber>{$link_line.value}</a><BR>
			{elseif $terminal == "PersonalComputer"}
			<a href="{$link_line.link_url}">{$link_line.value}</a><BR>
			{else}
			<a href="{$link_line.link_url}" accesskey="{$link_line.key}">{$link_line.value}</a><BR>
			{/if}
		{/if}
	{/foreach}
		<BR>
	</DIV>
	<HR>
	<!-- コピーライト -->
	<div align="center">{$txt_copyright}</div>
</body>
</html>
