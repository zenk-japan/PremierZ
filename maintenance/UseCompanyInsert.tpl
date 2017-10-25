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
<!-- 開始 -->
<div id="id_subv_div_outer">
	<p>
	<p>
	<table id="id_subv_table">
		<tr class="c_subv_tr_head">
			<th class="c_subv_th_dtl_cap" colspan=2>
				新規会社登録
			</th>
		</tr>
	<!-- 会社情報 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_title" colspan=2>
				会社情報
			</td>
		</tr>
	<!-- DATA_ID -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				DATA_ID<span class="c_span_required">*</span>
			</td>
			<td class="c_subv_td_dtl_shorttext">
				<input class="c_subv_ipt_dtl_shorttext" id="id_subv_ipt_dataid" type="text" value=""/>
				<br>
				数値３桁以内で入力して下さい
			</td>
		</tr>
	<!-- 利用会社コード -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				利用会社コード<span class="c_span_required">*</span>
			</td>
			<td class="c_subv_td_dtl_shorttext">
				<input class="c_subv_ipt_dtl_shorttext" id="id_subv_ipt_compcd" type="text" value=""/>
				<br>
				英数字６文字以内で入力して下さい
			</td>
		</tr>
	<!-- 利用会社名 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				利用会社名<span class="c_span_required">*</span>
			</td>
			<td class="c_subv_td_dtl_longtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_compnm" type="text" value=""/>
				<br>
				５０文字以内で入力して下さい
			</td>
		</tr>
	<!-- 備考 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				備考
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_remarks" type="text" value=""/>
				<br>
				５０文字以内で入力して下さい
			</td>
		</tr>
	<!-- 管理者ユーザーコード -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				管理者ユーザーコード<span class="c_span_required">*</span>
			</td>
			<td class="c_subv_ipt_dtl_shorttext">
				<input class="c_subv_ipt_dtl_shorttext" id="id_subv_ipt_admcode" type="text" value=""/>
				<br>
				英数字８文字以内で入力して下さい
			</td>
		</tr>
	<!-- 発信用メールサーバ設定 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_title" colspan=2>
				発信用メールサーバ設定
			</td>
		</tr>
	<!-- SMTPサーバ -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				SMTPサーバ
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_smtpsrv" type="text" value=""/>
			</td>
		</tr>
	<!-- ポート番号 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				SMTPサーバのポート番号
			</td>
			<td class="c_subv_ipt_dtl_shorttext">
				<input class="c_subv_ipt_dtl_shorttext" id="id_subv_ipt_smtpprt" type="text" value=""/>
			</td>
		</tr>
	<!-- 認証暗号化 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				SMTPサーバの認証暗号化方式(ssl or tls)
			</td>
			<td class="c_subv_ipt_dtl_shorttext">
				<input class="c_subv_ipt_dtl_shorttext" id="id_subv_ipt_smtpsecure" type="text" value=""/>
			</td>
		</tr>
	<!-- アカウント -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				SMTPサーバのアカウント
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_smtpact" type="text" value=""/>
			</td>
		</tr>
	<!-- パスワード -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				SMTPサーバのパスワード
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_smtppss" type="text" value=""/>
			</td>
		</tr>
	<!-- 受信用メールアドレス設定 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_title" colspan=2>
				受信用メールアドレス設定
			</td>
		</tr>
	<!-- 作業取り纏め用 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				作業取り纏め用アドレス
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_mailman" type="text" value=""/>
			</td>
		</tr>
	<!-- 勤怠報告用 -->
		<tr class="c_subv_tr_dtl">
			<td class="c_subv_td_dtl_cap">
				勤怠報告用アドレス
			</td>
			<td class="c_subv_td_dtl_verylongtext">
				<input class="c_subv_ipt_dtl_longtext" id="id_subv_ipt_mailrep" type="text" value=""/>
			</td>
		</tr>
	</table>
	<p>
	※「<span class="c_span_required">*</span>」の付いている項目は、入力必須項目です
	<p>
	<p>
	<input id="id_subv_btn_save" type="button" value="保存"/>
	<input id="id_subv_btn_close" type="button" value="キャンセル"/>
</div>
<!-- 終了 -->