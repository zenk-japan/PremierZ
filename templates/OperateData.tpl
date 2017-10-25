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
<HTML>
<HEAD>
	<META content=text/html;charset=utf-8 http-equiv=Content-Type>
{foreach from=$ar_css_files item=css_file}
	<LINK REL="stylesheet" HREF="{$css_file}" TYPE="text/css">
{/foreach}
{foreach from=$ar_js_files item=js_file}
	<SCRIPT type="text/javascript" src="{$js_file}"></SCRIPT>
{/foreach}
{foreach from=$ar_js_datas item=js_data}
	<SCRIPT>AjaxZip2.JSONDATA = '{$js_data}';</SCRIPT>
{/foreach}
</HEAD>
<BODY onunload="procOnClose()">
	<H1 class="css_dm_titile">{$txt_title}</H1>
	<HR class="css_dm_tophr">
	<DIV id=main class="css_dm_main">
		<FORM name="INPUT_DATA" onSubmit="return false;">
			<TABLE>
			<!-- 入力部 -->
				{foreach from=$ar_dm_main item=ar_dm_item}
				{if $ar_dm_item.type != "hidden"}
					<TR>
						<TD class="css_dm_caption">{$ar_dm_item.caption}</TD>
				{/if}
				{if $ar_dm_item.type == "checkbox"}
					{if $ar_dm_item.value == "Y"}
						<TD><INPUT class="css_dm_chkbox" type=checkbox name="{$ar_dm_item.name}" checked=true></INPUT></TD>
					{else}
						<TD><INPUT class="css_dm_chkbox" type=checkbox name="{$ar_dm_item.name}"></INPUT></TD>
					{/if}
					</TR>
				{elseif $ar_dm_item.type == "list"}
						<TD><INPUT class="uselist" type=text id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="{$ar_dm_item.value}" readOnly=true></INPUT></TD>
						<TD><INPUT class="css_dm_orgval" type=hidden name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
					</TR>
				{elseif $ar_dm_item.type == "calendar"}
						<TD><INPUT class="usecalendar" type=text id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
						<TD><INPUT class="css_dm_orgval" type=hidden name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
					</TR>
				{elseif $ar_dm_item.type == "text"}
					{if $ar_dm_item.name == "ZIP_CODE"}
						<TD><INPUT class="css_dm_txtbox" type=text id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" title="{$ar_dm_item.title}" value="{$ar_dm_item.value}" onKeyUp="{$ar_dm_item.onKeyUp}"></INPUT></TD>
						<TD><INPUT class="css_dm_orgval" type=hidden name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
					{else}
						<TD><INPUT class="css_dm_txtbox" type=text id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" title="{$ar_dm_item.title}" value="{$ar_dm_item.value}"></INPUT></TD>
						<TD><INPUT class="css_dm_orgval" type=hidden name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
					{/if}
					</TR>
				{elseif $ar_dm_item.type == "password"}
						<TD><INPUT class="css_dm_txtbox" type=password id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" title="{$ar_dm_item.title}" value="{$ar_dm_item.value}"></INPUT></TD>
						<TD><INPUT class="css_dm_orgval" type=hidden name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
					</TR>
				{elseif $ar_dm_item.type == "disp"}
						<TD><INPUT class="css_dm_txtro" type=disp id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="{$ar_dm_item.value}" readOnly=true></INPUT></TD>
					</TR>
				{elseif $ar_dm_item.type == "radio"}
					{if $ar_dm_item.value == "M"}
						<TD>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="M" checked=true>男</INPUT>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="W">女</INPUT>
						</TD>
					{elseif $ar_dm_item.value == "W"}
						<TD>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="M">男</INPUT>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="W" checked=true>女</INPUT>
						</TD>
					{else}
						<TD>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="M">男</INPUT>
							<INPUT class="css_dm_rb" type=radio id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="W">女</INPUT>
						</TD>
					{/if}
					</TR>
				{elseif $ar_dm_item.type == "hidden"}
						<TD><INPUT class="css_dm_orgval" type=hidden id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" value="{$ar_dm_item.value}"></INPUT></TD>
				{elseif $ar_dm_item.type == "textarea"}
						<TD><textarea class="css_dm_textarea" id="{$ar_dm_item.id}" name="{$ar_dm_item.name}" title="{$ar_dm_item.title}">{$ar_dm_item.value}</textarea></TD>
					</TR>
				{/if}
				{foreachelse}
					<TR>
						<TD>対象データがありません。</TD>
					</TR>
				{/foreach}
			</TABLE>
		</FORM>
	</DIV>
	<P>
	<BR>
	<DIV id=buttons class="css_dm_buttons">
		<FORM>
			{foreach from=$ar_dm_button item=ar_dmbt_item}
				<INPUT class="css_dm_bt" id="{$ar_dmbt_item.id}" type=button value="{$ar_dmbt_item.value}" onclick="{$ar_dmbt_item.onclick}"></INPUT>
			{/foreach}
		</FORM>
	</DIV>
<!--
	{$html_debug}
-->
	<p id=textData></p>
</BODY>
</HTML>