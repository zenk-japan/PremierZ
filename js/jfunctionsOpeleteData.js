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
/*******************************************************************************
 javascript関数
*******************************************************************************/
var openWindow;								// 子画面のウィンドウオブジェクト
var thisWindowName;							// 今の画面のウィンドウ名
var thisWindow;								// 今の画面のウィンドウオブジェクト
/*==============================================================================
  画面起動時処理
  処理概要：画面起動時に行う処理
  ============================================================================*/
$(function(){
	// 今のウィンドウのオブジェクトをセット
	thisWindow = window;
	
	// カバーの状態をセット
//	$("input#hd_cover_stat").val("off");
	
	// 今のウィンドウの名前を日付シリアル値から作成
	// ただし、子画面の場合はそのまま
	
	if(!thisWindow.name){
		var dt_today = new Date();
		thisWindowName = dt_today.getTime();
		thisWindow.name = thisWindowName;
	}

/*==============================================================================
  作成日:2009/04/15
  新規登録するコード値処理
  処理概要：
		新規登録ボタン押下前にコード値をセットする
  引数：
  ============================================================================*/

	$("#bt_insert").mouseover(function(){
//		alert("insert");
		var select_value_pdn;
		var select_value_cldn;
		var select_value_wdn;
		var select_value_odn;
		var data_pdn = new Object();
		var data_cldn = new Object();
		var data_wdn = new Object();
		var data_odn = new Object();
		var L_JS_CODE_NAME_ARRAY_PDN = new Array();
		var L_JS_CODE_NAME_ARRAY_CLDN = new Array();
		var L_JS_CODE_NAME_ARRAY_WDN = new Array();
		var L_JS_CODE_NAME_ARRAY_ODN = new Array();
		//選択した値を確認
		select_value_pdn = $("input[name='PAYMENT_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/PAYMENT_DIVISIONCodeName.json", function(data_pdn){
			if ( ! data_pdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_PDN = data_pdn[select_value_pdn];
			$("input[name='PAYMENT_DIVISION']").val(L_JS_CODE_NAME_ARRAY_PDN);
		});
		
		select_value_cldn = $("input[name='CLASSIFICATION_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/CLASSIFICATION_DIVISIONCodeName.json", function(data_cldn){
			if ( ! data_cldn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_CLDN = data_cldn[select_value_cldn];
			$("input[name='CLASSIFICATION_DIVISION']").val(L_JS_CODE_NAME_ARRAY_CLDN);
		});
		
		select_value_odn = $("input[name='ORDER_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ORDER_DIVISIONCodeName.json", function(data_odn){
			if ( ! data_odn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ODN = data_odn[select_value_odn];
			$("input[name='ORDER_DIVISION']").val(L_JS_CODE_NAME_ARRAY_ODN);
			
		});
		
		select_value_wdn = $("input[name='WORK_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_DIVISIONCodeName.json", function(data_wdn){
			if ( ! data_wdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WDN = data_wdn[select_value_wdn];
			$("input[name='WORK_DIVISION']").val(L_JS_CODE_NAME_ARRAY_WDN);
			
		});

/*
	作業管理画面
*/
//エンドユーザID
		var select_value_euun;
		var data_euun = new Object();
		var L_JS_CODE_NAME_ARRAY_EUUN = new Array();

		select_value_euun = $("input[name='ENDUSER_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ENDUSER_USER_IDCodeName.json", function(data_euun){
			if ( ! data_euun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_EUUN = data_euun[select_value_euun];
			$("input[name='ENDUSER_USER_ID']").val(L_JS_CODE_NAME_ARRAY_EUUN);
		});

//依頼元ユーザID
		var select_value_ruun;
		var data_ruun = new Object();
		var L_JS_CODE_NAME_ARRAY_RUUN = new Array();

		select_value_ruun = $("input[name='REQUEST_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/REQUEST_USER_IDCodeName.json", function(data_ruun){
			if ( ! data_ruun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_RUUN = data_ruun[select_value_ruun];
			$("input[name='REQUEST_USER_ID']").val(L_JS_CODE_NAME_ARRAY_RUUN);
		});
/*
	作業内容画面
*/

//作業まとめ者ID
		var select_value_waun;
		var data_waun = new Object();
		var L_JS_CODE_NAME_ARRAY_WAUN = new Array();

		select_value_waun = $("input[name='WORK_ARRANGEMENT_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_ARRANGEMENT_USER_IDCodeName.json", function(data_waun){
			if ( ! data_waun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WAUN = data_waun[select_value_waun];
			$("input[name='WORK_ARRANGEMENT_ID']").val(L_JS_CODE_NAME_ARRAY_WAUN);
		});

//超過精算
		var select_value_elfn;
		var data_elfn = new Object();
		var L_JS_CODE_NAME_ARRAY_ELFN = new Array();

		select_value_elfn = $("input[name='EXCESS_LIQUIDATION_FLAG_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/EXCESS_LIQUIDATION_FLAGCodeName.json", function(data_elfn){
			if ( ! data_elfn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ELFN = data_elfn[select_value_elfn];
			$("input[name='EXCESS_LIQUIDATION_FLAG']").val(L_JS_CODE_NAME_ARRAY_ELFN);
		});

/*
	作業人員画面
*/	

//拠点ID
		var select_value_wbn;
		var data_wbn = new Object();
		var L_JS_CODE_NAME_ARRAY_WBN = new Array();

		select_value_wbn = $("input[name='WORK_BASE_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_BASE_IDCodeName.json", function(data_wbn){
			if ( ! data_wbn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WBN = data_wbn[select_value_wbn];
			$("input[name='WORK_BASE_ID']").val(L_JS_CODE_NAME_ARRAY_WBN);
		});

//作業者ID
		var select_value_wun;
		var data_wun = new Object();
		var L_JS_CODE_NAME_ARRAY_WUN = new Array();

		select_value_wun = $("input[name='WORK_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_USER_IDCodeName.json", function(data_wun){
			if ( ! data_wun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WUN = data_wun[select_value_wun];
			$("input[name='WORK_USER_ID']").val(L_JS_CODE_NAME_ARRAY_WUN);

		});
		
//承認区分コード値
		var select_value_adn;
		var data_adn = new Object();
		var L_JS_CODE_NAME_ARRAY_ADN = new Array();

		select_value_adn = $("input[name='APPROVAL_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/APPROVAL_DIVISIONCodeName.json", function(data_adn){
			if ( ! data_adn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ADN = data_adn[select_value_adn];
			$("input[name='APPROVAL_DIVISION']").val(L_JS_CODE_NAME_ARRAY_ADN);
		});

//キャンセル区分コード値
		var select_value_cdn;
		var data_cdn = new Object();
		var L_JS_CODE_NAME_ARRAY_CDN = new Array();

		select_value_cdn = $("input[name='CANCEL_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/CANCEL_DIVISIONCodeName.json", function(data_cdn){
			if ( ! data_cdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_CDN = data_cdn[select_value_cdn];
			$("input[name='CANCEL_DIVISION']").val(L_JS_CODE_NAME_ARRAY_CDN);
		});

/*
ユーザ管理 新規登録
*/
		var select_value_uain;
		var data_uain = new Object();
		var L_JS_CODE_NAME_ARRAY_UAIN = new Array();

		select_value_uain = $("input[name='AUTHORITY_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/AUTHORITY_IDCodeName.json", function(data_uain){
			if ( ! data_uain ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_UAIN = data_uain[select_value_uain];
			$("input[name='AUTHORITY_ID']").val(L_JS_CODE_NAME_ARRAY_UAIN);
			
		});


	});

	/*==============================================================================
  作成日:2009/05/13
  更新登録登録するコード値処理
  処理概要：
		更新登録ボタン押下前にコード値をセットする
  引数：
  ============================================================================*/

	$("#bt_update").mouseover(function(){
//		alert("insert");
		var select_value_pdn;
		var select_value_cldn;
		var select_value_wdn;
		var select_value_odn;
		var data_pdn = new Object();
		var data_cldn = new Object();
		var data_wdn = new Object();
		var data_odn = new Object();
		var L_JS_CODE_NAME_ARRAY_PDN = new Array();
		var L_JS_CODE_NAME_ARRAY_CLDN = new Array();
		var L_JS_CODE_NAME_ARRAY_WDN = new Array();
		var L_JS_CODE_NAME_ARRAY_ODN = new Array();
		//選択した値を確認
		select_value_pdn = $("input[name='PAYMENT_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/PAYMENT_DIVISIONCodeName.json", function(data_pdn){
			if ( ! data_pdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_PDN = data_pdn[select_value_pdn];
			$("input[name='PAYMENT_DIVISION']").val(L_JS_CODE_NAME_ARRAY_PDN);
		});
		
		select_value_cldn = $("input[name='CLASSIFICATION_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/CLASSIFICATION_DIVISIONCodeName.json", function(data_cldn){
			if ( ! data_cldn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_CLDN = data_cldn[select_value_cldn];
			$("input[name='CLASSIFICATION_DIVISION']").val(L_JS_CODE_NAME_ARRAY_CLDN);
			
		});

		select_value_odn = $("input[name='ORDER_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ORDER_DIVISIONCodeName.json", function(data_odn){
			if ( ! data_odn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ODN = data_odn[select_value_odn];
			$("input[name='ORDER_DIVISION']").val(L_JS_CODE_NAME_ARRAY_ODN);
			
		});
		
		select_value_wdn = $("input[name='WORK_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_DIVISIONCodeName.json", function(data_wdn){
			if ( ! data_wdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WDN = data_wdn[select_value_wdn];
			$("input[name='WORK_DIVISION']").val(L_JS_CODE_NAME_ARRAY_WDN);
			
		});

/*
	作業管理
*/

		
//エンドユーザ会社ID
		var select_value_eucn;
		var data_eucn = new Object();
		var L_JS_CODE_NAME_ARRAY_EUCI = new Array();
		
		select_value_eucn = $("input[name='ENDUSER_COMPANY_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ENDUSER_COMPANY_IDCodeName.json", function(data_eucn){
			if ( ! data_eucn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_EUCI = data_eucn[select_value_eucn];
			$("input[name='ENDUSER_COMPANY_ID']").val(L_JS_CODE_NAME_ARRAY_EUCI);
			
		});
		
//エンドユーザ担当者ID
		var select_value_eun;
		var data_eun = new Object();
		var L_JS_CODE_NAME_ARRAY_EUI = new Array();
		
		select_value_eun = $("input[name='ENDUSER_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ENDUSER_USER_IDCodeName.json", function(data_eun){
			if ( ! data_eun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_EUI = data_eun[select_value_eun];
			$("input[name='ENDUSER_USER_ID']").val(L_JS_CODE_NAME_ARRAY_EUI);
			
		});

//依頼元会社ID
		var select_value_rucn;
		var data_rucn = new Object();
		var L_JS_CODE_NAME_ARRAY_RUCI = new Array();
		
		select_value_rucn = $("input[name='REQUEST_COMPANY_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/REQUEST_COMPANY_IDCodeName.json", function(data_rucn){
			if ( ! data_rucn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_RUCI = data_rucn[select_value_rucn];
			$("input[name='REQUEST_COMPANY_ID']").val(L_JS_CODE_NAME_ARRAY_RUCI);
			
		});

//依頼元ユーザID
		var select_value_ruun;
		var data_ruun = new Object();
		var L_JS_CODE_NAME_ARRAY_RUUN = new Array();

		select_value_ruun = $("input[name='REQUEST_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/REQUEST_USER_IDCodeName.json", function(data_ruun){
			if ( ! data_ruun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_RUUN = data_ruun[select_value_ruun];
			$("input[name='REQUEST_USER_ID']").val(L_JS_CODE_NAME_ARRAY_RUUN);
		});
		
		select_value_odn = $("input[name='ORDER_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/ORDER_DIVISIONCodeName.json", function(data_odn){
			if ( ! data_odn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ODN = data_odn[select_value_odn];
			$("input[name='ORDER_DIVISION']").val(L_JS_CODE_NAME_ARRAY_ODN);
			
		});
		
		select_value_wdn = $("input[name='WORK_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_DIVISIONCodeName.json", function(data_wdn){
			if ( ! data_wdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WDN = data_wdn[select_value_wdn];
			$("input[name='WORK_DIVISION']").val(L_JS_CODE_NAME_ARRAY_WDN);
			
		});

/*
	作業内容画面
*/

//作業まとめ者ID
		var select_value_waun;
		var data_waun = new Object();
		var L_JS_CODE_NAME_ARRAY_WAUN = new Array();

		select_value_waun = $("input[name='WORK_ARRANGEMENT_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_ARRANGEMENT_USER_IDCodeName.json", function(data_waun){
			if ( ! data_waun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WAUN = data_waun[select_value_waun];
			$("input[name='WORK_ARRANGEMENT_ID']").val(L_JS_CODE_NAME_ARRAY_WAUN);
		});

//超過精算
		var select_value_elfn;
		var data_elfn = new Object();
		var L_JS_CODE_NAME_ARRAY_ELFN = new Array();

		select_value_elfn = $("input[name='EXCESS_LIQUIDATION_FLAG_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/EXCESS_LIQUIDATION_FLAGCodeName.json", function(data_elfn){
			if ( ! data_elfn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ELFN = data_elfn[select_value_elfn];
			$("input[name='EXCESS_LIQUIDATION_FLAG']").val(L_JS_CODE_NAME_ARRAY_ELFN);
		});

//*** ADD 2009-06-08 Start ***
//作業ステータス
		var select_value_wkst;
		var data_wkst = new Object();
		var L_JS_CODE_NAME_ARRAY_WKST = new Array();

		select_value_wkst = $("input[name='WORK_STATUS_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_STATUSCodeName.json", function(data_wkst){
			if ( ! data_wkst ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WKST = data_wkst[select_value_wkst];
			$("input[name='WORK_STATUS']").val(L_JS_CODE_NAME_ARRAY_WKST);
		});
//*** ADD 2009-06-08 End ***
		
/*
	作業人員画面
*/	

//拠点ID
		var select_value_wbn;
		var data_wbn = new Object();
		var L_JS_CODE_NAME_ARRAY_WBN = new Array();

		select_value_wbn = $("input[name='WORK_BASE_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_BASE_IDCodeName.json", function(data_wbn){
			if ( ! data_wbn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WBN = data_wbn[select_value_wbn];
			$("input[name='WORK_BASE_ID']").val(L_JS_CODE_NAME_ARRAY_WBN);
		});

//作業者ID
		var select_value_wun;
		var data_wun = new Object();
		var L_JS_CODE_NAME_ARRAY_WUN = new Array();

		select_value_wun = $("input[name='WORK_USER_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/WORK_USER_IDCodeName.json", function(data_wun){
			if ( ! data_wun ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_WUN = data_wun[select_value_wun];
			$("input[name='WORK_USER_ID']").val(L_JS_CODE_NAME_ARRAY_WUN);
		});
		
//承認区分コード値
		var select_value_adn;
		var data_adn = new Object();
		var L_JS_CODE_NAME_ARRAY_ADN = new Array();

		select_value_adn = $("input[name='APPROVAL_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/APPROVAL_DIVISIONCodeName.json", function(data_adn){
			if ( ! data_adn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_ADN = data_adn[select_value_adn];
			$("input[name='APPROVAL_DIVISION']").val(L_JS_CODE_NAME_ARRAY_ADN);
		});

//キャンセル区分コード値
		var select_value_cdn;
		var data_cdn = new Object();
		var L_JS_CODE_NAME_ARRAY_CDN = new Array();

		select_value_cdn = $("input[name='CANCEL_DIVISION_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/CANCEL_DIVISIONCodeName.json", function(data_cdn){
			if ( ! data_cdn ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_CDN = data_cdn[select_value_cdn];
			$("input[name='CANCEL_DIVISION']").val(L_JS_CODE_NAME_ARRAY_CDN);
		});
		
/*
ユーザ管理 新規登録(更新)
*/
		var select_value_uain;
		var data_uain = new Object();
		var L_JS_CODE_NAME_ARRAY_UAIN = new Array();

		select_value_uain = $("input[name='AUTHORITY_NAME']").attr("value");
		$.ajaxSetup({scriptCharset:'utf-8'});
		$.getJSON( "../js/json/AUTHORITY_IDCodeName.json", function(data_uain){
			if ( ! data_uain ) {
				alert("データが見つかりません");
				return;
			}
			var L_JS_CODE_NAME_ARRAY_UAIN = data_uain[select_value_uain];
			$("input[name='AUTHORITY_ID']").val(L_JS_CODE_NAME_ARRAY_UAIN);
			
		});
	});	
	
});



$(window).load(function(){
//	$("div.topline").hide();
//	$("div.headline").hide();
//	$("div.bclink").hide();
//	$("div.hdldata").hide();
//	$("div.offrame").hide();
//	$("div.footer").hide();
//	$("div.topline").show("slow");
//	$("div.headline").show("slow");
//	$("div.bclink").show("slow");
//	$("div.hdldata").show("slow");
//	$("div.offrame").slideDown("slow");
//	$("div.footer").show("slow");
});


