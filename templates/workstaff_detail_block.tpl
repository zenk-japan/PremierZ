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
<!-- メイン -->
	<div id="id_div_detail">
		<table id="id_table_detail">
			<tr id="id_tr_dtl_title" class="c_tr_detail">
				<td id="id_td_dtl_title">
					<span id="id_span_dtl_title">作業人員一覧</span>
				</td>
			</tr>
			<tr id="id_tr_dtl_header" class="c_tr_detail">
				<td id="id_td_dtl_header">
					<div id="id_div_dtl_header">
						<table id="id_tab_dtl_header">
							<tr id="id_tr_dtl_header">
								<td class="c_td_dtl_width10">&nbsp;</td>
								<td class="c_td_dtl_width30">No.</td>
								<td class="c_td_dtl_width20">
									<input type="checkbox" name="nm_ckb_dtl_delete" id="id_ckb_dtl_delete" />
								</td>
								<td class="c_td_dtl_width50"></td>
								<td class="c_td_dtl_width50">承認区分</td>
								<td class="c_td_dtl_width100">キャンセル区分</td>
								<td class="c_td_dtl_width150">拠点名</td>
								<td class="c_td_dtl_width150">作業者名</td>
								<td class="c_td_dtl_width150">フリガナ</td>
								<td class="c_td_dtl_width100">分類区分</td>
								<td class="c_td_dtl_width150">所属</td>
								<td class="c_td_dtl_width80">メール送信</td>
								<td class="c_td_dtl_width150">自宅電話番号</td>
								<td class="c_td_dtl_width150">自宅メールアドレス</td>
								<td class="c_td_dtl_width150">携帯電話番号</td>
								<td class="c_td_dtl_width150">携帯メールアドレス</td>
								<td class="c_td_dtl_width100">最寄駅</td>
								<td class="c_td_dtl_width50">支払区分</td>
								<td class="c_td_dtl_width80">作業費(単価)</td>
								<td class="c_td_dtl_width80">作業費表示</td>
								<td class="c_td_dtl_width80">超過(単価)</td>
								<td class="c_td_dtl_width100">出発予定時間</td>
								<td class="c_td_dtl_width100">出発時間(作業者)</td>
								<td class="c_td_dtl_width100">入店予定時間</td>
								<td class="c_td_dtl_width100">入店時間(作業者)</td>
								<td class="c_td_dtl_width100">入店時間(管理部)</td>
								<td class="c_td_dtl_width100">退店予定時間</td>
								<td class="c_td_dtl_width100">退店時間(作業者)</td>
								<td class="c_td_dtl_width100">退店時間(管理部)</td>
								<td class="c_td_dtl_width80">基本時間</td>
								<td class="c_td_dtl_width80">休憩時間</td>
								<td class="c_td_dtl_width80">交通費</td>
								<td class="c_td_dtl_width80">その他手当</td>
								<td class="c_td_dtl_width150">備考</td>
								<td class="c_td_dtl_width80">残業代</td>
								<td class="c_td_dtl_width80">作業費合計</td>
								<td class="c_td_dtl_width80">出金合計</td>
								<td class="c_td_dtl_width80">実作業時間</td>
								<td class="c_td_dtl_width80">実残業時間</td>
								<td class="c_td_dtl_width80">差引支給額</td>
							</tr>
							<tr id="id_tr_dtl_summary">
								<td class="c_td_dtl_width10">&nbsp;</td>
								<td class="c_td_dtl_width30">合計</td>
								<td class="c_td_dtl_width20">&nbsp;</td>
								<td class="c_td_dtl_width50">&nbsp;</td>
								<td class="c_td_dtl_width50">&nbsp;</td>{*承認区分*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*キャンセル区分*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*拠点名*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*作業者名*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*フリガナ*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*分類区分*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*所属*}
								<td class="c_td_dtl_width80">&nbsp;</td>{*メール送信*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*自宅電話番号*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*自宅メールアドレス*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*携帯電話番号*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*携帯メールアドレス*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*最寄駅*}
								<td class="c_td_dtl_width50">&nbsp;</td>{*支払区分*}
								{*<td class="c_td_dtl_width80">{$work_unit_price_orig_sum}</td>*}{*作業費(単価)*}
								<td class="c_td_dtl_width80">&nbsp;</td>{*作業費(単価)*}
								<td class="c_td_dtl_width80">&nbsp;</td>{*作業費表示*}
								{*<td class="c_td_dtl_width80">{$excess_amount_sum}</td>*}{*超過(単価)*}
								<td class="c_td_dtl_width80">&nbsp;</td>{*超過(単価)*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*出発予定時間*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*出発時間(作業者)*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*入店予定時間*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*入店時間(作業者)*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*入店時間(管理部)*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*退店予定時間*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*退店時間(作業者)*}
								<td class="c_td_dtl_width100">&nbsp;</td>{*退店時間(管理部)*}
								<td class="c_td_dtl_width80">{$basic_time_sum}</td>{*基本時間*}
								<td class="c_td_dtl_width80">{$break_time_sum}</td>{*休憩時間*}
								<td class="c_td_dtl_width80">{$transport_amount_sum}</td>{*交通費*}
								<td class="c_td_dtl_width80">{$other_amount_sum}</td>{*その他手当*}
								<td class="c_td_dtl_width150">&nbsp;</td>{*備考*}
								<td class="c_td_dtl_width80">{$overtime_work_amount_sum}</td>{*残業代*}
								<td class="c_td_dtl_width80">{$work_expense_amount_total_sum}</td>{*作業費合計*}
								<td class="c_td_dtl_width80">{$payment_amount_total_sum}</td>{*出金合計*}
								<td class="c_td_dtl_width80">{$real_working_hours_sum}</td>{*実作業時間*}
								<td class="c_td_dtl_width80">{$real_overtime_hours_sum}</td>{*実残業時間*}
								<td class="c_td_dtl_width80">{$supplied_amount_total_sum}</td>{*差引支給額*}
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr id="id_tr_dtl_detail" class="c_tr_detail">
				<td id="id_td_dtl_detail">
					<div id="id_div_dtl_detail">
						<table id="id_tab_dtl_detail">
						{foreach from=$ar_wstaff_dtl item=wstaff_dtl_item name=fe_workstaff_dtl}
							<tr id="id_tr_workstaff_dtl{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_tr_workstaff_dtl">
								<td class="c_td_dtl_width10">
							{* 有効無効表示 *}
								{if $wstaff_dtl_item.VALIDITY_FLAG=="Y"}
									<input id="id_txt_workstaff_valid{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_txt_td_workstaff_valid" type="text" value=" " readOnly="true" />
								{else}
									<input id="id_txt_workstaff_valid{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_txt_td_workstaff_invalid" type="text" value=" " readOnly="true" />
								{/if}
							{* 作業人員IDの隠し項目 *}
									<input id="id_txt_workstaff_id{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_hd_td_workstaff_id" type="hidden" value="{$wstaff_dtl_item.WORK_STAFF_ID}" />
								</td>
							{* No. *}
								<td class="c_td_dtl_width30">
									{$smarty.foreach.fe_workstaff_dtl.iteration}
								</td>
							{* 削除用チェック *}
								<td class="c_td_dtl_width20">
									<input id="id_chk_workstaff_chk{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_chk_workstaff_report" type="checkbox" value="" />
								</td>
							{* 更新ボタン *}
								<td class="c_td_dtl_width50">
									<input id="id_btn_workstaff_update{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_btn_workstaff_update" type="button" value="更新" />
								</td>
							{* 承認区分 *}
								<td class="c_td_dtl_width50">
									<div class="c_div_dtl_value_50">{$wstaff_dtl_item.APPROVAL_DIVISION_NAME}</div>
								</td>
							{* キャンセル区分 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.CANCEL_DIVISION_NAME}</div>
								</td>
							{* 拠点名 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_BASE_NAME}</div>
								</td>
							{* 作業者名 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_USER_NAME}</div>
							{* ユーザーIDの隠し項目 *}
									<input id="id_txt_work_user_id{$smarty.foreach.fe_workstaff_dtl.iteration}" class="c_hd_td_work_user_id" type="hidden" value="{$wstaff_dtl_item.WORK_USER_ID}" />
								</td>
							{* フリガナ *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_USER_KANA}</div>
								</td>
							{* 分類区分 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.WORK_CLASSIFICATION_DIVISION_NAME}</div>
								</td>
							{* 所属 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_COMPANY_NAME}</div>
								</td>
							{* メール送信 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.TRANSMISSION_FLAG_NAME}</div>
								</td>
							{* 自宅電話番号 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_HOME_PHONE}</div>
								</td>
							{* 自宅メールアドレス *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_HOME_MAIL}</div>
								</td>
							{* 携帯電話番号 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_MOBILE_PHONE}</div>
								</td>
							{* 携帯メールアドレス *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.WORK_MOBILE_PHONE_MAIL}</div>
								</td>
							{* 最寄駅 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.CLOSEST_STATION}</div>
								</td>
							{* 支払区分 *}
								<td class="c_td_dtl_width50">
									<div class="c_div_dtl_value_50">{$wstaff_dtl_item.WORK_PAYMENT_DIVISION_NAME}</div>
								</td>
							{* 作業費(単価) *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.WORK_UNIT_PRICE_ORIG|number_format:0:".":","}</div>
								</td>
							{* 作業費表示 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.WORK_UNIT_PRICE_DISPLAY_FLAG_NAME}</div>
								</td>
							{* 超過(単価) *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.EXCESS_AMOUNT|number_format:0:".":","}</div>
								</td>
							{* 出発予定時間 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.DISPATCH_SCHEDULE_TIMET}</div>
								</td>
							{* 出発時間(作業者) *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.DISPATCH_STAFF_TIMET}</div>
								</td>
							{* 入店予定時間 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.ENTERING_SCHEDULE_TIMET}</div>
								</td>
							{* 入店時間(作業者) *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.ENTERING_STAFF_TIMET}</div>
								</td>
							{* 入店時間(管理部) *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.ENTERING_MANAGE_TIMET}</div>
								</td>
							{* 退店予定時間 *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.LEAVE_SCHEDULE_TIMET}</div>
								</td>
							{* 退店時間(作業者) *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.LEAVE_STAFF_TIMET}</div>
								</td>
							{* 退店時間(管理部) *}
								<td class="c_td_dtl_width100">
									<div class="c_div_dtl_value_100">{$wstaff_dtl_item.LEAVE_MANAGE_TIMET}</div>
								</td>
							{* 基本時間 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.BASIC_TIME}</div>
								</td>
							{* 休憩時間 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.BREAK_TIME}</div>
								</td>
							{* 交通費 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.TRANSPORT_AMOUNT|number_format:0:".":","}</div>
								</td>
							{* その他手当 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.OTHER_AMOUNT|number_format:0:".":","}</div>
								</td>
							{* 備考 *}
								<td class="c_td_dtl_width150">
									<div class="c_div_dtl_value_150">{$wstaff_dtl_item.REMARKS}</div>
								</td>
							{* 残業代 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.OVERTIME_WORK_AMOUNT}</div>
								</td>
							{* 作業費合計 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.WORK_EXPENSE_AMOUNT_TOTAL}</div>
								</td>
							{* 出金合計 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.PAYMENT_AMOUNT_TOTAL|number_format:0:".":","}</div>
								</td>
							{* 実作業時間 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.REAL_LABOR_HOURS}</div>
								</td>
							{* 実残業時間 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.REAL_OVERTIME_HOURS}</div>
								</td>
							{* 差引支給額 *}
								<td class="c_td_dtl_width80">
									<div class="c_div_dtl_value_80">{$wstaff_dtl_item.SUPPLIED_AMOUNT_TOTAL|number_format:0:".":","}</div>
								</td>
							</tr>
						{foreachelse}
							<tr>
								<td colspan=14>該当する作業人員が存在しません</td>
							</tr>
						{/foreach}
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>

