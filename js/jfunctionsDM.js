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
 javascript関数 データ操作画面用
*******************************************************************************/
var $operation_mode;
/*==============================================================================
  ウィンドウロード時の処理
  処理概要：
  		親ウィンドウの編集禁止を設定
  引数：
  ============================================================================*/
$(window).load(function(){
	var parentWin = window.opener;
	var parentCover = parentWin.document.getElementById("cp1");
	var parentCoverStat = parentWin.document.getElementById("hd_cover_stat");
	
	// 親画面の入力不可を設定
	if($(parentCoverStat).val()=="off"){
		parentCover.style.width = "100%";
		parentCover.style.height = "100%";
		parentCover.style.filter = "Alpha(opacity=50)";
		parentCover.style.zindex = "1";
		$(parentWin.document.body).css("overflow", "hidden");
		
		$(parentCoverStat).val("on");
	}
	
	//alert(this.window.closed);
});

/*==============================================================================
  ウィンドウロード時の処理
  処理概要：
  		入力例の表示
  引数：
  ============================================================================*/
$(document).ready(function(){
	$.updnWatermark.attachAll();
});

/*==============================================================================
  ウィンドウ切り替え時の処理
  処理概要：
  		親ウィンドウの編集禁止を解除
  引数：
  ============================================================================*/
$(window).unload(function(){
	var parentWin = window.opener;
	var parentCover = parentWin.document.getElementById("cp1");
	var parentCoverStat = parentWin.document.getElementById("hd_cover_stat");
	// 親画面のファイル名
	var openerFileName = parentWin.location.href.substring(parentWin.location.href.lastIndexOf("/")+1,parentWin.location.href.length);
	var openerSearchForm = parentWin.document.fm_search;
	
	// 親画面の入力不可を解除
	if($(parentCoverStat).val()=="on"){
		parentCover.style.width = "0%";
		parentCover.style.height = "0%";
		parentCover.style.filter = "Alpha(opacity=0)";
		parentCover.style.zindex = "-1";
		$(parentWin.document.body).css("overflow", "auto");
		
		$(parentCoverStat).val("off");
	}
	
	// 親画面の再検索
	openerSearchForm.target	= parentWin.name;
	openerSearchForm.method	= "POST";
	openerSearchForm.action	= openerFileName;
	parentWin.document.fm_search.submit();
	//alert(this.window.closed);
});

/*==============================================================================
  ウィンドウオープン時の処理
  処理概要：
  		
  引数：
  ============================================================================*/
$(function(){
	$obj_form_list = $("form[name='INPUT_DATA']");
	
	// 入店予定時刻を取得
	$l_enter_time = $obj_form_list.find('input[name="DEFAULT_ENTERING_SCHEDULE_TIMET"]').val();
	// 退店予定時刻を取得
	$l_leave_time = $obj_form_list.find('input[name="DEFAULT_LEAVE_SCHEDULE_TIMET"]').val();
	// 規定基本時間を取得
	$l_working_time = $obj_form_list.find('input[name="DEFAULT_WORKING_TIME"]').val();
	// 規定休憩時間
	$l_break_time = $obj_form_list.find('input[name="DEFAULT_BREAK_TIME"]').val();
	
	// 新規登録
	$("#bt_insert").click(function(){
	//	alert("insert!");
		$operation_mode = "insert";
		$.post(
			"../ctl/c_post.php", {
				 "update1[]"				: [$("input[id=1]").attr("name")	,$("input[id=1]").val()]
				,"update2[]"				: [$("input[id=2]").attr("name")	,$("input[id=2]").val()]
				,"update3[]"				: [$("input[id=3]").attr("name")	,$("input[id=3]").val()]
				,"update4[]"				: [$("input[id=4]").attr("name")	,$("input[id=4]").val()]
				,"update5[]"				: [$("input[id=5]").attr("name")	,$("input[id=5]").val()]
				,"update6[]"				: [$("input[id=6]").attr("name")	,$("input[id=6]").val()]
				,"update7[]"				: [$("input[id=7]").attr("name")	,$("input[id=7]").val()]
				,"update8[]"				: [$("input[id=8]").attr("name")	,$("input[id=8]").val()]
				,"update9[]"				: [$("input[id=9]").attr("name")	,$("input[id=9]").val()]
				,"update10[]"				: [$("input[id=10]").attr("name")	,$("input[id=10]").val()]
				,"update11[]"				: [$("input[id=11]").attr("name")	,$("input[id=11]").val()]
				,"update12[]"				: [$("input[id=12]").attr("name")	,$("input[id=12]").val()]
				,"update13[]"				: [$("input[id=13]").attr("name")	,$("input[id=13]").val()]
				,"update14[]"				: [$("input[id=14]").attr("name")	,$("input[id=14]").val()]
				,"update15[]"				: [$("input[id=15]").attr("name")	,$("input[id=15]").val()]
				,"update16[]"				: [$("input[id=16]").attr("name")	,$("input[id=16]").val()]
				,"update17[]"				: [$("input[id=17]").attr("name")	,$("input[id=17]").val()]
				,"update18[]"				: [$("input[id=18]").attr("name")	,$("input[id=18]").val()]
				,"update19[]"				: [$("input[id=19]").attr("name")	,$("input[id=19]").val()]
				,"update20[]"				: [$("input[id=20]").attr("name")	,$("input[id=20]").val()]
				,"update21[]"				: [$("input[id=21]").attr("name")	,$("input[id=21]").val()]
				,"update22[]"				: [$("input[id=22]").attr("name")	,$("input[id=22]").val()]
				,"update23[]"				: [$("input[id=23]").attr("name")	,$("input[id=23]").val()]
				,"update24[]"				: [$("input[id=24]").attr("name")	,$("input[id=24]").val()]
				,"update25[]"				: [$("input[id=25]").attr("name")	,$("input[id=25]").val()]
				,"update26[]"				: [$("input[id=26]").attr("name")	,$("input[id=26]").val()]
				,"update27[]"				: [$("input[id=27]").attr("name")	,$("input[id=27]").val()]
				,"update28[]"				: [$("input[id=28]").attr("name")	,$("input[id=28]").val()]
				,"update29[]"				: [$("input[id=29]").attr("name")	,$("input[id=29]").val()]
				,"update30[]"				: [$("input[id=30]").attr("name")	,$("input[id=30]").val()]
				,"update31[]"				: [$("input[id=31]").attr("name")	,$("input[id=31]").val()]
				,"update32[]"				: [$("input[id=32]").attr("name")	,$("input[id=32]").val()]
				,"update33[]"				: [$("input[id=33]").attr("name")	,$("input[id=33]").val()]
				,"update34[]"				: [$("input[id=34]").attr("name")	,$("input[id=34]").val()]
				,"update35[]"				: [$("input[id=35]").attr("name")	,$("input[id=35]").val()]
				,"update36[]"				: [$("input[id=36]").attr("name")	,$("input[id=36]").val()]
				,"update37[]"				: [$("input[id=37]").attr("name")	,$("input[id=37]").val()]
				,"update38[]"				: [$("input[id=38]").attr("name")	,$("input[id=38]").val()]
				,"update39[]"				: [$("input[id=39]").attr("name")	,$("input[id=39]").val()]
				,"update40[]"				: [$("input[id=40]").attr("name")	,$("input[id=40]").val()]
				,"update41[]"				: [$("textarea[id=41]").attr("name"),$("textarea[id=41]").val()]
				,"SEX"						: $("input[name=SEX]:checked").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"ALERT_PERMISSION_FLAG"	: $("input[name=ALERT_PERMISSION_FLAG]").attr("checked")
				,"OPERATE_PAGE"				: "insert"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// 更新
	$("#bt_update").click(function(){
	//	alert("update!");
		$operation_mode = "update";
		
		// ページが"作業内容"の場合のみ以下の処理を実行する。
		$pagename = $obj_form_list.find('input[name="PAGE_NAME"]').val();
		
		if($pagename == "workcontents"){
			// 作業内容の入店予定時刻、退店予定時刻、規定基本時間、規定休憩時間変更時にひも付く作業人員の了承状況を未承認に変更するか確認メッセージを表示させる。
				
			// 更新時の入店予定時刻を取得
			$l_update_enter_time = $obj_form_list.find('input[name="DEFAULT_ENTERING_SCHEDULE_TIMET"]').val();
			// 更新時の退店予定時刻を取得
			$l_update_leave_time = $obj_form_list.find('input[name="DEFAULT_LEAVE_SCHEDULE_TIMET"]').val();
			// 更新時の規定基本時間を取得
			$l_update_working_time = $obj_form_list.find('input[name="DEFAULT_WORKING_TIME"]').val();
			// 更新時の規定休憩時間
			$l_update_break_time = $obj_form_list.find('input[name="DEFAULT_BREAK_TIME"]').val();
			
			if($l_enter_time != $l_update_enter_time || $l_leave_time != $l_update_leave_time || $l_working_time != $l_update_working_time || $l_break_time != $l_update_break_time){
				yn=confirm("「入店予定時刻」「退店予定時刻」「規定基本時間」「規定休憩時間」のいずれかが変更されています。作業人員の「承認区分」を未確認に変更しますか？");
					if (yn == true) {
						$obj_form_list.find('input[name="APPROVAL_DIVISION_CHANGE_CHECK"]').val("ON");
					}else {
						$obj_form_list.find('input[name="APPROVAL_DIVISION_CHANGE_CHECK"]').val("OFF");
					}
				// 入店予定時刻を取得
				$l_enter_time = $obj_form_list.find('input[name="DEFAULT_ENTERING_SCHEDULE_TIMET"]').val();
				// 退店予定時刻を取得
				$l_leave_time = $obj_form_list.find('input[name="DEFAULT_LEAVE_SCHEDULE_TIMET"]').val();
				// 規定基本時間を取得
				$l_working_time = $obj_form_list.find('input[name="DEFAULT_WORKING_TIME"]').val();
				// 規定休憩時間
				$l_break_time = $obj_form_list.find('input[name="DEFAULT_BREAK_TIME"]').val();
			}
		}
		$.post(
			"../ctl/c_post.php", {
				 "update1[]"				: [$("input[id=1]").attr("name")	,$("input[id=1]").val()]
				,"update2[]"				: [$("input[id=2]").attr("name")	,$("input[id=2]").val()]
				,"update3[]"				: [$("input[id=3]").attr("name")	,$("input[id=3]").val()]
				,"update4[]"				: [$("input[id=4]").attr("name")	,$("input[id=4]").val()]
				,"update5[]"				: [$("input[id=5]").attr("name")	,$("input[id=5]").val()]
				,"update6[]"				: [$("input[id=6]").attr("name")	,$("input[id=6]").val()]
				,"update7[]"				: [$("input[id=7]").attr("name")	,$("input[id=7]").val()]
				,"update8[]"				: [$("input[id=8]").attr("name")	,$("input[id=8]").val()]
				,"update9[]"				: [$("input[id=9]").attr("name")	,$("input[id=9]").val()]
				,"update10[]"				: [$("input[id=10]").attr("name")	,$("input[id=10]").val()]
				,"update11[]"				: [$("input[id=11]").attr("name")	,$("input[id=11]").val()]
				,"update12[]"				: [$("input[id=12]").attr("name")	,$("input[id=12]").val()]
				,"update13[]"				: [$("input[id=13]").attr("name")	,$("input[id=13]").val()]
				,"update14[]"				: [$("input[id=14]").attr("name")	,$("input[id=14]").val()]
				,"update15[]"				: [$("input[id=15]").attr("name")	,$("input[id=15]").val()]
				,"update16[]"				: [$("input[id=16]").attr("name")	,$("input[id=16]").val()]
				,"update17[]"				: [$("input[id=17]").attr("name")	,$("input[id=17]").val()]
				,"update18[]"				: [$("input[id=18]").attr("name")	,$("input[id=18]").val()]
				,"update19[]"				: [$("input[id=19]").attr("name")	,$("input[id=19]").val()]
				,"update20[]"				: [$("input[id=20]").attr("name")	,$("input[id=20]").val()]
				,"update21[]"				: [$("input[id=21]").attr("name")	,$("input[id=21]").val()]
				,"update22[]"				: [$("input[id=22]").attr("name")	,$("input[id=22]").val()]
				,"update23[]"				: [$("input[id=23]").attr("name")	,$("input[id=23]").val()]
				,"update24[]"				: [$("input[id=24]").attr("name")	,$("input[id=24]").val()]
				,"update25[]"				: [$("input[id=25]").attr("name")	,$("input[id=25]").val()]
				,"update26[]"				: [$("input[id=26]").attr("name")	,$("input[id=26]").val()]
				,"update27[]"				: [$("input[id=27]").attr("name")	,$("input[id=27]").val()]
				,"update28[]"				: [$("input[id=28]").attr("name")	,$("input[id=28]").val()]
				,"update29[]"				: [$("input[id=29]").attr("name")	,$("input[id=29]").val()]
				,"update30[]"				: [$("input[id=30]").attr("name")	,$("input[id=30]").val()]
				,"update31[]"				: [$("input[id=31]").attr("name")	,$("input[id=31]").val()]
				,"update32[]"				: [$("input[id=32]").attr("name")	,$("input[id=32]").val()]
				,"update33[]"				: [$("input[id=33]").attr("name")	,$("input[id=33]").val()]
				,"update34[]"				: [$("input[id=34]").attr("name")	,$("input[id=34]").val()]
				,"update35[]"				: [$("input[id=35]").attr("name")	,$("input[id=35]").val()]
				,"update36[]"				: [$("input[id=36]").attr("name")	,$("input[id=36]").val()]
				,"update37[]"				: [$("input[id=37]").attr("name")	,$("input[id=37]").val()]
				,"update38[]"				: [$("input[id=38]").attr("name")	,$("input[id=38]").val()]
				,"update39[]"				: [$("input[id=39]").attr("name")	,$("input[id=39]").val()]
				,"update40[]"				: [$("input[id=40]").attr("name")	,$("input[id=40]").val()]
				,"update41[]"				: [$("textarea[id=41]").attr("name"),$("textarea[id=41]").val()]
				,"SEX"						: $("input[name=SEX]:checked").val()
				,"WORK_UNIT_PRICE_DISPLAY_FLAG"	: $("input[name=WORK_UNIT_PRICE_DISPLAY_FLAG]").attr("checked")
				,"VALIDITY_FLAG"			: $("input[name=VALIDITY_FLAG]").attr("checked")
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"ALERT_PERMISSION_FLAG"	: $("input[name=ALERT_PERMISSION_FLAG]").attr("checked")
				,"OPERATE_PAGE"				: "update"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// コピー
	$("#bt_copy").click(function(){
	//	alert("copy!");
		$operation_mode = "copy";
		
		$.post(
			"../ctl/c_post.php", {
				 "update1[]"				: [$("input[id=1]").attr("name")	,$("input[id=1]").val()]
				,"update2[]"				: [$("input[id=2]").attr("name")	,$("input[id=2]").val()]
				,"update3[]"				: [$("input[id=3]").attr("name")	,$("input[id=3]").val()]
				,"update4[]"				: [$("input[id=4]").attr("name")	,$("input[id=4]").val()]
				,"update5[]"				: [$("input[id=5]").attr("name")	,$("input[id=5]").val()]
				,"update6[]"				: [$("input[id=6]").attr("name")	,$("input[id=6]").val()]
				,"update7[]"				: [$("input[id=7]").attr("name")	,$("input[id=7]").val()]
				,"update8[]"				: [$("input[id=8]").attr("name")	,$("input[id=8]").val()]
				,"update9[]"				: [$("input[id=9]").attr("name")	,$("input[id=9]").val()]
				,"update10[]"				: [$("input[id=10]").attr("name")	,$("input[id=10]").val()]
				,"update11[]"				: [$("input[id=11]").attr("name")	,$("input[id=11]").val()]
				,"update12[]"				: [$("input[id=12]").attr("name")	,$("input[id=12]").val()]
				,"update13[]"				: [$("input[id=13]").attr("name")	,$("input[id=13]").val()]
				,"update14[]"				: [$("input[id=14]").attr("name")	,$("input[id=14]").val()]
				,"update15[]"				: [$("input[id=15]").attr("name")	,$("input[id=15]").val()]
				,"update16[]"				: [$("input[id=16]").attr("name")	,$("input[id=16]").val()]
				,"update17[]"				: [$("input[id=17]").attr("name")	,$("input[id=17]").val()]
				,"update18[]"				: [$("input[id=18]").attr("name")	,$("input[id=18]").val()]
				,"update19[]"				: [$("input[id=19]").attr("name")	,$("input[id=19]").val()]
				,"update20[]"				: [$("input[id=20]").attr("name")	,$("input[id=20]").val()]
				,"update21[]"				: [$("input[id=21]").attr("name")	,$("input[id=21]").val()]
				,"update22[]"				: [$("input[id=22]").attr("name")	,$("input[id=22]").val()]
				,"update23[]"				: [$("input[id=23]").attr("name")	,$("input[id=23]").val()]
				,"update24[]"				: [$("input[id=24]").attr("name")	,$("input[id=24]").val()]
				,"update25[]"				: [$("input[id=25]").attr("name")	,$("input[id=25]").val()]
				,"update26[]"				: [$("input[id=26]").attr("name")	,$("input[id=26]").val()]
				,"update27[]"				: [$("input[id=27]").attr("name")	,$("input[id=27]").val()]
				,"update28[]"				: [$("input[id=28]").attr("name")	,$("input[id=28]").val()]
				,"update29[]"				: [$("input[id=29]").attr("name")	,$("input[id=29]").val()]
				,"update30[]"				: [$("input[id=30]").attr("name")	,$("input[id=30]").val()]
				,"update31[]"				: [$("input[id=31]").attr("name")	,$("input[id=31]").val()]
				,"update32[]"				: [$("input[id=32]").attr("name")	,$("input[id=32]").val()]
				,"update33[]"				: [$("input[id=33]").attr("name")	,$("input[id=33]").val()]
				,"update34[]"				: [$("input[id=34]").attr("name")	,$("input[id=34]").val()]
				,"update35[]"				: [$("input[id=35]").attr("name")	,$("input[id=35]").val()]
				,"update36[]"				: [$("input[id=36]").attr("name")	,$("input[id=36]").val()]
				,"update37[]"				: [$("input[id=37]").attr("name")	,$("input[id=37]").val()]
				,"update38[]"				: [$("input[id=38]").attr("name")	,$("input[id=38]").val()]
				,"update39[]"				: [$("input[id=39]").attr("name")	,$("input[id=39]").val()]
				,"update40[]"				: [$("input[id=40]").attr("name")	,$("input[id=40]").val()]
				,"update41[]"				: [$("textarea[id=41]").attr("name"),$("textarea[id=41]").val()]
				,"SEX"						: $("input[name=SEX]:checked").val()
				,"WORK_UNIT_PRICE_DISPLAY_FLAG"	: $("input[name=WORK_UNIT_PRICE_DISPLAY_FLAG]").attr("checked")
				,"VALIDITY_FLAG"			: $("input[name=VALIDITY_FLAG]").attr("checked")
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"ALERT_PERMISSION_FLAG"	: $("input[name=ALERT_PERMISSION_FLAG]").attr("checked")
				,"OPERATE_PAGE"				: "copy"
		//	}, displayData
			}, my_func
		);
		return false;
	});
	
	// 論理削除
	$("#bt_invalid").click(function(){
	//	alert("invalid!");
		$operation_mode = "invalid";
		
		$.post(
			"../ctl/c_post.php", {
				 "DATA_ID"					: $("input[name=DATA_ID]").val()
				,"COMPANY_ID"				: $("input[name=COMPANY_ID]").val()
				,"GROUP_ID"					: $("input[name=GROUP_ID]").val()
				,"USER_ID"					: $("input[name=USER_ID]").val()
				,"BASE_ID"					: $("input[name=BASE_ID]").val()
				,"ESTIMATE_ID"				: $("input[name=ESTIMATE_ID]").val()
				,"WORK_CONTENT_ID"			: $("input[name=WORK_CONTENT_ID]").val()
				,"WORK_STAFF_ID"			: $("input[name=WORK_STAFF_ID]").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"DELETE_TARGET"			: $("input[name=DELETE_TARGET]").val()
				,"OPERATE_PAGE"				: "invalid"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// 物理削除
	$("#bt_delete").click(function(){
	//	alert("delete!");
		$operation_mode = "delete";
		$.post(
			"../ctl/c_post.php", {
				 "DATA_ID"					: $("input[name=DATA_ID]").val()
				,"COMPANY_ID"				: $("input[name=COMPANY_ID]").val()
				,"GROUP_ID"					: $("input[name=GROUP_ID]").val()
				,"USER_ID"					: $("input[name=USER_ID]").val()
				,"BASE_ID"					: $("input[name=BASE_ID]").val()
				,"ESTIMATE_ID"				: $("input[name=ESTIMATE_ID]").val()
				,"WORK_CONTENT_ID"			: $("input[name=WORK_CONTENT_ID]").val()
				,"WORK_STAFF_ID"			: $("input[name=WORK_STAFF_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"DELETE_TARGET"			: $("input[name=DELETE_TARGET]").val()
				,"OPERATE_PAGE"				: "delete"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// メール送信
	$("#bt_send").click(function(){
	//	alert("send!");
		$operation_mode = "send";
		$.post(
			"../ctl/c_post.php", {
				 "SUBJECT"					: $("input[name=SUBJECT]").val()
				,"BODY"						: $("textarea[name=BODY]").val()
			//	,"NAME"						: $("input[name=NAME]").val()
			//	,"WORK_HOME_MAIL"			: $("input[name=WORK_HOME_MAIL]").val()
			//	,"WORK_MOBILE_PHONE_MAIL"	: $("input[name=WORK_MOBILE_PHONE_MAIL]").val()
				,"DATA_ID"					: $("input[name=DATA_ID]").val()
			//	,"WORK_STAFF_ID"			: $("input[name=WORK_STAFF_ID]").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"BATCHSEND_TARGET"			: $("input[name=BATCHSEND_TARGET]").val()
				,"OPERATE_PAGE"				: "send"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// 一括メール送信
	$("#bt_batchsend").click(function(){
	//	alert("batchsend!");
		$operation_mode = "batchsend";
		$.post(
			"../ctl/c_post.php", {
				 "SUBJECT"					: $("input[name=SUBJECT]").val()
				,"BODY"						: $("textarea[name=BODY]").val()
			//	,"NAME"						: $("input[name=NAME]").val()
			//	,"WORK_HOME_MAIL"			: $("input[name=WORK_HOME_MAIL]").val()
			//	,"WORK_MOBILE_PHONE_MAIL"	: $("input[name=WORK_MOBILE_PHONE_MAIL]").val()
				,"DATA_ID"					: $("input[name=DATA_ID]").val()
			//	,"WORK_STAFF_ID"			: $("input[name=WORK_STAFF_ID]").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"BATCHSEND_TARGET"			: $("input[name=BATCHSEND_TARGET]").val()
				,"OPERATE_PAGE"				: "batchsend"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// ログイン情報メール通知
	$("#bt_usersinfo").click(function(){
	//	alert("usersinfo!");
		$operation_mode = "usersinfo";
		$.post(
			"../ctl/c_post.php", {
				 "SUBJECT"					: $("input[name=SUBJECT]").val()
				,"BODY"						: $("textarea[name=BODY]").val()
				,"DATA_ID"					: $("input[name=DATA_ID]").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"BATCHSEND_TARGET"			: $("input[name=BATCHSEND_TARGET]").val()
				,"OPERATE_PAGE"				: $("input[name=OPERATE_PAGE]").val()
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// ログイン情報一括メール通知
	$("#bt_batchusersinfo").click(function(){
	//	alert("batchusersinfo!");
		$operation_mode = "batchusersinfo";
		$.post(
			"../ctl/c_post.php", {
				 "SUBJECT"					: $("input[name=SUBJECT]").val()
				,"BODY"						: $("textarea[name=BODY]").val()
				,"DATA_ID"					: $("input[name=DATA_ID]").val()
				,"LOGINUSER_ID"				: $("input[name=LOGINUSER_ID]").val()
				,"PAGE_NAME"				: $("input[name=PAGE_NAME]").val()
				,"BATCHSEND_TARGET"			: $("input[name=BATCHSEND_TARGET]").val()
			//	,"OPERATE_PAGE"				: $("input[name=OPERATE_PAGE]").val()
				,"OPERATE_PAGE"				: "batchusersinfo"
	//		}, displayData
			}, my_func
		);
		return false;
	});
	
	// 閉じる
	$("#bt_close").click(function(){
		window.close();
	});
});

/*==============================================================================
  ウィンドウオープン時の改行時に表示領域を拡大
  処理概要：
  		
  引数：
  ============================================================================*/
/*
$(document).ready(function(){
	$('.css_dm_textarea').autogrow();
});
*/

/*==============================================================================
  ウィンドウオープン時の編集情報表示(debug)
  処理概要：
  		
  引数：
  ============================================================================*/
	function displayData(data) {
		$("#textData").html(data);
	//	window.location.reload();
	}
	
	function my_func(data) {
		if( data != ''){
			/*
			var $l_mess = "";
			switch($operation_mode){
			case "insert":
			case "copy":
				//$l_mess = "新規登録が完了しました。";
				$l_mess = data;
				break;
			case "update":
				//$l_mess = "更新が完了しました。";
				$l_mess = data;
				break;
			case "invalid":
				//$l_mess = "論理削除が完了しました。";
				$l_mess = data;
				break;
			case "delete":
				//$l_mess = "物理削除が完了しました。";
				$l_mess = data;
				break;
			default:
				$l_mess = data;
				break;
			}
			alert($l_mess);
			*/
			alert(data);
		}
	}