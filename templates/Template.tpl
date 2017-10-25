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
<DIV id="cp1" class="coverplate"><INPUT id="hd_cover_stat" type=hidden></INPUT></DIV>
<!-- 外枠-->
<DIV class={$css_outer} >
	<DIV class={$css_topline}>
		<!-- 3色線 -->
		<H1 class={$css_topline}>&nbsp;</H1>
		<table class={$css_imagetab}>
			<TR>
				<!-- zenkロゴ -->
				<TD><IMG src={$img_logo}></TD>
				<!-- ログイン名 -->
				<TD class="{$css_imagetab_name}">{$l_name}</TD>
				<!-- ログアウト -->
				<TD class="{$css_imagetab_link}"><A class="{$css_imagetab_linkcapt}" HREF="../page/logout.php">{$link_value}</A></TD>
			</TR>
		</table>
	</DIV>
	<P>
<!-- 本体 -->
	<DIV class={$css_maintab_headline}>
<!-- 1段目:見出し -->
		<HR class={$css_maintab_headline}></HR>
	</DIV>
	<P>
<!-- 2段目:リンク -->
	<DIV class={$css_maintab_link}>
		{$html_bclink}
	</DIV>
	<P>
<!-- 3段目:ヘッドライン項目 -->
	<FORM name={$fname_search} id={$fname_search} method=POST>
		<DIV class={$css_maintab_hdlval}>
			<TABLE>
				<TR>
				{foreach from=$ar_hdldata item=ar_hdldata_line}
					{if $ar_hdldata_line.name == "RETURN"}
						</TR>
						<TR>
					{else}
						{if $ar_hdldata_line.type == "hidden"}
							<TD><INPUT name="{$ar_hdldata_line.name}" type="{$ar_hdldata_line.type}" value="{$ar_hdldata_line.value}"></INPUT></TD>
						{elseif $ar_hdldata_line.type == "disp"}
							<TD class="{$css_maintab_hdlval_capt}">&nbsp;{$ar_hdldata_line.caption}</TD>
							<TD><INPUT size="{$ar_hdldata_line.width}" name="{$ar_hdldata_line.name}" type="{$ar_hdldata_line.type}" class="{$css_maintab_hdlval_txtro}" value="{$ar_hdldata_line.value}" readOnly=true></INPUT></TD>
						{elseif $ar_hdldata_line.type == "num"}
							<TD class="{$css_maintab_hdlval_capt}">&nbsp;{$ar_hdldata_line.caption}</TD>
							<TD><INPUT size="{$ar_hdldata_line.width}" name="{$ar_hdldata_line.name}" type="{$ar_hdldata_line.type}" class="{$css_maintab_hdlval_txtnumro}" value="{$ar_hdldata_line.value}" readOnly=true></INPUT></TD>
						{elseif $ar_hdldata_line.type == "list"}
							<TD class="{$css_maintab_hdlval_capt}">&nbsp;{$ar_hdldata_line.caption}</TD>
							<TD>
								<select name="{$ar_hdldata_line.name}" class="css_select">
									{foreach from=$ar_hdldata_line.list key=list_key item=list_item}
									<option value="{$list_key}" {if $list_item == $ar_hdldata_line.value}selected="selected" {/if}>{$list_item}</option>
									{/foreach}
								</select>
							</TD>
						{elseif $ar_hdldata_line.type == "comment"}
							<TD colspan="2" class="css_hl_comment"><span name="{$ar_hdldata_line.name}">{$ar_hdldata_line.value}</span></TD>
						{else}
							{if $ar_hdldata_line.name == "username" || $ar_hdldata_line.name == "password"}
								<TD class="{$css_maintab_hdlval_capt}">&nbsp;{$ar_hdldata_line.caption}</TD>
								<TD><INPUT size="{$ar_hdldata_line.width}" name="{$ar_hdldata_line.name}" type="{$ar_hdldata_line.type}" class="{$css_maintab_hdlval_txt}" value="{$ar_hdldata_line.value}"></INPUT></TD>
							{else}
								<TD class="{$css_maintab_hdlval_capt}">&nbsp;{$ar_hdldata_line.caption}</TD>
								<TD><INPUT size="{$ar_hdldata_line.width}" name="{$ar_hdldata_line.name}" title="{$ar_hdldata_line.title}" type="{$ar_hdldata_line.type}" class="{$css_maintab_hdlval_txtb}" value="{$ar_hdldata_line.value}"></INPUT></TD>
							{/if}
						{/if}
					{/if}
				{/foreach}
				<!-- 
				</TR>-->
			</TABLE>
			<P>
<!-- 4段目:ボタン -->
			{$html_menu}
			<P>
			<INPUT type="hidden" id="hd_delete_target" name="hd_delete_target"></INPUT>
			<INPUT type="hidden" id="hd_batchsend_target" name="hd_batchsend_target"></INPUT>
		</DIV>
	</FORM>
	<P>
<!-- 5段目:メッセージ出力領域 -->
	<DIV class={$css_maintab_msg}>
		{foreach from=$ar_msg item=ar_msg_line}
			{$ar_msg_line.txtmsg}<BR>
		{/foreach}
	</DIV>
<!-- 6段目:インナーフレーム -->
	<DIV class={$css_maintab_frame}>
		<!-- 明細表示部 -->
		{$html_dtltab}
	</DIV>
<!-- 7段目:フッター領域 -->
	<!-- フッター表示部 -->
	<DIV class={$css_maintab_footer}>
		{$html_footer}
	</DIV>
<!-- 8段目:コピーライト -->
	<DIV class={$css_maintab_btmcr}>
		{$txt_copyright}
	</DIV>
<!-- 本体終了 -->
</DIV>
<!-- 外枠終了 -->
</BODY>
</HTML>