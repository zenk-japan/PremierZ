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
					{*ユーザー設定情報*}
					<div id="id_div_use_main" class="c_div_use_main">
						<div id="id_div_use_main_title">
							ユーザー設定情報
						</div>
						<div id="id_div_use_main_edit">
							<table id="id_tab_use_main">
								<tr>
									<td class="c_td_use_edit_cap">ユーザコード</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="nm_user_code" class="c_txt_use_readonly" id="id_txt_use_user_code" value="{$ar_user_record.USER_CODE}" readOnly/>
										<input type="hidden" name="USER_ID" id="id_hd_use_user_id" value="{$ar_user_record.USER_ID}"/>
									</td>
									<td>
										<span class="c_span_use_edit_warning">ユーザーコードは変更できません。変更の必要がある場合は、管理者に連絡して下さい。</span>
									</td>
								</tr>
								<tr>
									<td id="id_td_use_edit_passwd" class="c_td_use_edit_cap">パスワード</td>
									<td class="c_td_use_edit_data">
										<input type="checkbox" id="id_ckb_change_passwd" /><label for="id_ckb_change_passwd">パスワードを変更する</label><br>
										<input type="password" name="OLD_PASSWORD" class="c_txt_use_input" id="id_txt_use_password_old" value="{$ar_user_record.PASSWORD}" disabled/>&nbsp;現在のパスワード<br>
										<input type="password" name="PASSWORD" class="c_txt_use_input" id="id_txt_use_password" value="" disabled/>&nbsp;新しいパスワード
										<input type="hidden" id="id_hd_use_change_password" name="hd_edit_password" value="0"/>
									</td>
									<td>
										<span class="c_span_use_edit_notice">パスワードを変更する場合は、「パスワードを変更する」にチェックを入れ、<br>上段に現在のパスワード、下段に新しいパスワードを入力して下さい。</span>
									</td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">名前</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="nm_name" class="c_txt_use_readonly" id="id_txt_use_name" value="{$ar_user_record.NAME}" readOnly/>
									</td>
									<td>
										<span class="c_span_use_edit_warning">名前は変更できません。変更の必要がある場合は、管理者に連絡して下さい。</span>
									</td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">フリガナ</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="nm_kana" class="c_txt_use_readonly" id="id_txt_use_kana" value="{$ar_user_record.KANA}" readOnly/>
									</td>
									<td>
										<span class="c_span_use_edit_warning">フリガナは変更できません。変更の必要がある場合は、管理者に連絡して下さい。</span>
									</td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">郵便番号</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="ZIP_CODE" class="c_txt_use_input" id="id_txt_use_zip_code" value="{$ar_user_record.ZIP_CODE}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">住所</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="ADDRESS" class="c_txt_use_input_long" id="id_txt_use_address" value="{$ar_user_record.ADDRESS}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">最寄駅</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="CLOSEST_STATION" class="c_txt_use_input" id="id_txt_use_closest_station" value="{$ar_user_record.CLOSEST_STATION}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">自宅電話番号</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="HOME_PHONE" class="c_txt_use_input" id="id_txt_use_home_phone" value="{$ar_user_record.HOME_PHONE}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">自宅メールアドレス</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="HOME_MAIL" class="c_txt_use_input_long" id="id_txt_use_home_mail" value="{$ar_user_record.HOME_MAIL}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">携帯電話番号</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="MOBILE_PHONE" class="c_txt_use_input" id="id_txt_use_mobile_phone" value="{$ar_user_record.MOBILE_PHONE}"/>
									</td>
									<td></td>
								</tr>
								<tr>
									<td class="c_td_use_edit_cap">携帯メールアドレス</td>
									<td class="c_td_use_edit_data">
										<input type="text" name="MOBILE_PHONE_MAIL" class="c_txt_use_input_long" id="id_txt_use_mobile_phone_mail" value="{$ar_user_record.MOBILE_PHONE_MAIL}"/>
									</td>
									<td></td>
								</tr>
							</table>
						</div>
						<div id="id_div_use_button">
							<table id="id_tab_use_button">
								<tr>
									<td>
										<input id="id_btn_use_save" class="c_btn_button" type="button" value="変更保存" />
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
										<input id="id_btn_use_reload" class="c_btn_button" type="button" value="元に戻す" />
									</td>
								</tr>
							</table>
						</div>
					</div>
