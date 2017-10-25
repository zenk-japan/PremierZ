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
			<span id="id_span_cond_title">■操作メニュー</span>
			<table id="id_table_main_top">
				<tr id="id_tr_main_btn">
					<td id="id_td_main_btn1">
						<input type="button" id="id_btn_insert" class="c_btn_main_nomal" value="新規登録" />
						<input type="button" id="id_btn_save" class="c_btn_main_nomal" value="保存" />
						<input type="button" id="id_btn_reset" class="c_btn_main_nomal" value="変更を戻す" />
						<input type="button" id="id_btn_gomenu" class="c_btn_main_nomal" value="メニューに戻る" />
					</td>
				</tr>
			</table>
		</div>
		<br>
	<!-- メイン表示部 -->
		<div id="id_div_main_title">
			<span id="id_span_dtl_title">■利用会社一覧</span>
		</div>
		<div id="id_div_main_dtl">
			<table id="id_table_main_dtl">
			<!-- 見出し部 -->
				<tr id="id_tr_main_head">
					<th id="id_th_head_dataid" class="c_th_main_head">DATA_ID</th>
					<th id="id_th_head_uscpcd" class="c_th_main_head">利用会社コード<span class="c_span_required">*</span></th>
					<th id="id_th_head_uscpnm" class="c_th_main_head">利用会社名<span class="c_span_required">*</span></th>
					<th id="id_th_head_remark" class="c_th_main_head">備考</th>
					<th id="id_th_head_check" class="c_th_main_head">使用中データ</th>
					<th id="id_th_head_delete" class="c_th_main_head">削除</th>
				</tr>
			<!-- 明細部 -->
				{foreach from=$ar_usecomp_dtl item=usecomp_rec name=fe_usecomp_dtl_tr}
				<tr {if $smarty.foreach.fe_usecomp_dtl_tr.iteration mod 2==1}class="c_tr_main_dtl_odd"{else}class="c_tr_main_dtl_even"{/if}>
					<td>
						<input type="text" id="id_ipt_dataid{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_ipt_dataid" value="{$usecomp_rec.DATA_ID}" readOnly="true"/>
						<input type="hidden" id="id_hdn_dataid{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_hdn_dataid" value="{$usecomp_rec.DATA_ID}"/>
					</td>
					<td>
						<input type="text" id="id_ipt_compcd{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_ipt_compcd" value="{$usecomp_rec.USE_COMPANY_CODE}"/>
						<input type="hidden" id="id_hdn_compcd{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_hdn_compcd" value="{$usecomp_rec.USE_COMPANY_CODE}"/>
					</td>
					<td>
						<input type="text" id="id_ipt_compnm{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_ipt_compnm" value="{$usecomp_rec.USE_COMPANY_NAME}"/>
						<input type="hidden" id="id_hdn_compnm{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_hdn_compnm" value="{$usecomp_rec.USE_COMPANY_NAME}"/>
					</td>
					<td>
						<input type="text" id="id_ipt_remark{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_ipt_remark" value="{$usecomp_rec.REMARKS}"/>
						<input type="hidden" id="id_hdn_remark{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_hdn_remark" value="{$usecomp_rec.REMARKS}"/>
					</td>
					<td class="c_td_dtl_btn">
						<input type="button" id="id_btn_check{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_btn_check" value="表示"/>
					</td>
					<td class="c_td_dtl_btn">
						{if {$usecomp_rec.DATA_ID} == 0}&nbsp;
						{else}
						<input type="button" id="id_btn_delete{$smarty.foreach.fe_usecomp_dtl_tr.iteration}" class="c_btn_delete" value="削除"/>
						{/if}
					</td>
				</tr>
				{/foreach}
			</table>
			<span id="id_span_dtl_title">※「<span class="c_span_required">*</span>」の付いている項目は、入力必須項目です</span>
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