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
{* メール送信 *}
<!-- メール送信 -->
	<div id="id_div_mail_menu">
		<div id="id_div_mail_table">
			<form id="id_form_main">
				<table id="id_table_mail">
					<tr class="c_tr_section_header">
{* メール送信ボタン *}
						<td id="id_td_mail_buttons" class="c_td_edit_btn_5col" colspan=8>
{foreach from=$mail_button item=button_item}
							<input class="{$button_item.class}" type="{$button_item.type}" id="{$button_item.id}" value="{$button_item.value}">
{/foreach}
						</td>
					</tr>
{* 送信先アドレス *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							送信先
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td id="id_td_mail_cap_to" class="c_td_mail_caption">
							Ｔｏ :
						</td>
						<td id="id_td_mail_dtl_to" class="c_td_mail_dtl_7col" colspan=7>
							<table id="id_tab_mailto">
								<tr id="id_tr_mailto_top">
									<td id="id_td_mailto_top">
										<table id="id_tab_mailto_hd">
											<tr>
											{*
												<td class="c_td_mailto_sendok">
													送信
												</td>
											*}
												<td class="c_td_mailto_name">
													名前
												</td>
												<td class="c_td_mailto_addr">
													PCアドレス
												</td>
												<td class="c_td_mailto_addr">
													携帯アドレス
												</td>
												<td class="c_td_mailto_remark">
													備考
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td id="id_td_mailto_bottom">
										<div id="id_div_mailto_bottom">
											<table id="id_tab_mailto_bottom">
												{foreach from=$ar_trgt_worksaff key=l_key item=lr_workstaff_rec name=fe_workstaff}
												<tr>
												{*
													<td {if $lr_workstaff_rec.SEND_OK=='Y'}class="c_td_mailto_sendok_dtl_ok"{else}class="c_td_mailto_sendok_dtl_ng"{/if} id="id_td_mail_trgtws_send_ok{$smarty.foreach.fe_workstaff.iteration}" class="c_td_mail_trgtws_send_ok">
														{if $lr_workstaff_rec.SEND_OK=='Y'}
															<span class="c_span_mail">OK</span>
														{else}
															<span class="c_span_mail">NG</span>
														{/if}
													</td>
												*}
													<td class="c_td_mailto_name_dtl" id="id_td_mail_trgtws_work_user_name{$smarty.foreach.fe_workstaff.iteration}" class="c_td_mail_trgtws_work_user_name">
														<span id="id_span_mail_work_user_name{$smarty.foreach.fe_workstaff.iteration}" class="c_span_mail_work_user_name">{$lr_workstaff_rec.WORK_USER_NAME}</span>
													{* 人員IDの隠し項目 *}
														<input id="id_hd_mail_work_staff_id{$smarty.foreach.fe_workstaff.iteration}" class="c_hd_mail_work_staff_id" type="hidden" name="nm_hd_mail_work_staff_id" value="{$lr_workstaff_rec.WORK_STAFF_ID}"/>
													</td>
													<td class="c_td_mailto_addr_dtl" id="id_td_mail_trgtws_work_home_mail{$smarty.foreach.fe_workstaff.iteration}" class="c_td_mail_trgtws_work_user_mail">
														<input type="text" class="c_txt_mailto_addr_dtl" id="id_txt_mail_trgtws_work_home_mail{$smarty.foreach.fe_workstaff.iteration}" value="{$lr_workstaff_rec.WORK_HOME_MAIL}"/>
													</td>
													<td class="c_td_mailto_addr_dtl" id="id_td_mail_trgtws_work_mobile_phone_mail{$smarty.foreach.fe_workstaff.iteration}" class="c_td_mail_trgtws_work_user_mail">
														<input type="text" class="c_txt_mailto_addr_dtl" id="id_txt_mail_trgtws_work_mobile_phone_mail{$smarty.foreach.fe_workstaff.iteration}" value="{$lr_workstaff_rec.WORK_MOBILE_PHONE_MAIL}"/>
													</td>
													<td class="c_td_mailto_remark_dtl" id="id_td_mail_trgtws_send_remark{$smarty.foreach.fe_workstaff.iteration}" class="c_td_mail_trgtws_send_remark">
														{$lr_workstaff_rec.SEND_REMARK}
													</td>
												</tr>
												{/foreach}
											</table>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="c_td_mail_caption">
							Ｃｃ :
						</td>
						<td class="c_td_mail_dtl_7col" colspan=7>
							<input class="c_txt_mail_col7" id="id_txt_mail_cc" type="text" name="CC" value="{$mail_address_cc}" title="CCに送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr>
						<td class="c_td_mail_caption">
							Ｂｃｃ :
						</td>
						<td class="c_td_mail_dtl_7col" colspan=7>
							<input class="c_txt_mail_col7" id="id_txt_mail_bcc" type="text" name="BCC" value="{$mail_mobile_phone_mail}" title="BCCに送信するアドレスを入力"></input>
						</td>
					</tr>
					<tr class="c_tr_section_separator">
						<td class="c_td_section_separator" colspan=8>
						</td>
					</tr>
{* 件名 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							件名
						</td>
					</tr>
					<tr>
						<td class="c_td_mail_dtl_8col" colspan=8>
							<input class="c_txt_mail_col8" id="id_txt_mail_title" type="text" name="SUBJECT" value="{$mail_subject}" title=""></input>
						</td>
					</tr>
{* 本文 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							本文
						</td>
					</tr>
					<tr>
						<td id="id_td_mail_dtl_textarea" class="c_td_mail_dtl_8col" colspan=8>
							<textarea class="c_txtarea_mail_col8" id="id_txtarea_mail_body" name="BODY" title="">{$mail_body}</textarea>
						</td>
					</tr>
					<tr class="c_tr_section_separator">
						<td class="c_td_section_separator" colspan=8>
						</td>
					</tr>
{* 送信元情報 *}
					<tr class="c_tr_section_header">
						<td class="c_td_edit_section_header" colspan=3>
							送信元
						</td>
					</tr>
					<tr>
						<td class="c_td_mail_caption">
							アドレス :
						</td>
						<td class="c_td_mail_dtl_7col" colspan=7>
							{$mail_send_from}
						</td>
					</tr>
					
{* 隠し項目 *}
					<tr>
						<td class="c_table_td_hidden_col">
{foreach from=$ar_hidden_items item=hidden_items name=fe_hidden}
							<input type="hidden" class="c_hd_mail_hidden_items" id="id_ipt_hd{$smarty.foreach.fe_hidden.iteration}" name="{$hidden_items.name}" value="{$hidden_items.value}"></input>
{/foreach}
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
