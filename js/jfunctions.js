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
	$("input#hd_cover_stat").val("off");
	
	// 今のウィンドウの名前を日付シリアル値から作成
	// ただし、子画面の場合はそのまま
	
	if(!thisWindow.name){
		var dt_today = new Date();
		thisWindowName = dt_today.getTime();
		thisWindow.name = thisWindowName;
	}
	
	// 検索項目のリストセット
	var l_item_name;
	$(".hdltextbox").focus(function(){
		// 項目名が正しく取得するため、フォーカスを得た段階でsuggesutのセットを行う
		
		// 表示項目の値からGET用引数を作成
		var l_get_param = "";
		var ar_ronly_obj = jQuery.makeArray($(".hdltxtro"));
		//alert(ar_ronly_obj.constructor===Array);
		$.each(ar_ronly_obj, function(key, item_obj) {
			l_get_param = l_get_param + "&" + item_obj.name + "=" + encodeURI(item_obj.value);
			});
		
		// 無効データ表示の状態からGET用引数を追加
		if(!$("#ck_displaydelete").attr("checked")){
			// チェックされていない場合は、有効データのみセットするのでYをセット
			l_get_param = l_get_param + "&VALIDITY_FLAG=Y";
		}
		
		//alert(l_get_param);
		
		// フォーカスを得た項目名
		l_item_name		= $(this).attr("name");
		
		// suggesutのセット
		$(".hdltextbox").suggest("../mdl/SuggestionList.php?tableName=" + encodeURI($(document.fm_search.hd_page_name).val()) + "&itemName=" + encodeURI(l_item_name) + "&dataId=" + encodeURI($(document.fm_search.hd_dataid).val()) + l_get_param,{});
//		alert($(this).attr("name"));
//		alert(encodeURI($(document.fm_search.hd_page_name).val()));
		//alert(l_item_name);
		
	});
	
	// 明細テーブルがある場合、フォーカスの当たっている行を強調する
	if($("form[name='fm_dtltab']")){
		var $obj_dtltab_tr = $("tr.dtl_tr_nomal");
		//$obj_dtltab_tr.css("border", "3px solid rgb(255,0,0)");
		$obj_dtltab_tr.bind("mouseover", function(){
			$(this).find("TD.dtltext_odd, TD.dtltext_even, TD.dtltextnum_even, TD.dtltextnum_odd").css("backgroundColor", "#B8E4F2");
		});
		$obj_dtltab_tr.bind("mouseout", function(){
			$(this).find("TD.dtltext_odd, TD.dtltext_even, TD.dtltextnum_even, TD.dtltextnum_odd").css("backgroundColor", "");
		});
	}
	
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
/*==============================================================================
  ウィンドウクリック時の処理
  処理概要：
  		子ウィンドウが開いていたら親ウィンドウの編集を禁止する
  引数：
  ============================================================================*/
$(document).click(function(){
	if(!!openWindow && !openWindow.closed){
		//alert("open");
		if($("input#hd_cover_stat").val()=="off"){
			setCover();
		}
		openWindow.focus();
	}else{
		//alert("close");
		if($("input#hd_cover_stat").val()=="on"){
			removeCover();
		}
	}
});
function setCover(){
	$(".coverplate").css("width", "100%");
	$(".coverplate").css("height", "100%");
	$(".coverplate").css("filter", "Alpha(opacity=50)");
	$(".coverplate").css("zindex", "1");
	$(document.body).css("overflow", "hidden");
	$("input#hd_cover_stat").val("on");
	
}
function removeCover(){
	$(".coverplate").css("width", "0%");
	$(".coverplate").css("height", "0%");
	$(".coverplate").css("filter", "Alpha(opacity=0)");
	$(".coverplate").css("zindex", "-1");
	$(document.body).css("overflow", "auto");
	$("input#hd_cover_stat").val("off");
}

/*==============================================================================
  削除済みチェック更新
  処理概要：
  		全ての削除済みチェックボックスにチェックを入れる
  		すべてのチェックボックスにチェックが入っている場合はすべて外す
  引数：
  		p_form_name			チェックボックスのあるフォーム名
  		p_chkbox_name		チェックボックス名
  ============================================================================*/
function allCheck(p_form_name, p_chkbox_name){
	
	var len=document.getElementsByName(p_form_name).length;
	var chkcnt=0;
	var i=0;
	
	
	for (i=1; i<=len; i++){
		// チェックの入っているチェックボックスをカウント
		if(document.getElementsByName(p_chkbox_name)[i].checked){
			chkcnt++;
		}
	}
	if(chkcnt==len){
		// すべてチェックが入っている場合はすべて外す
		for (i=1; i<=len; i++){
			document.getElementsByName(p_chkbox_name)[i].checked=false;
		}
	}else{
		// それ以外はすべてにチェックを入れる
		for (i=1; i<=len; i++){
		document.getElementsByName(p_chkbox_name)[i].checked=true;
		}
	}
}
/*==============================================================================
  削除対象セット
  処理概要：
  		明細表のチェックが入っている行のIDを収集し、ヘッドラインにある
  		隠し項目にセットする
  引数：
  		p_srch_form_name	ヘッドラインおよびメニュー部のFORM名
  		p_set_item_name		IDをセットする隠し項目名
  		p_dtl_form_name		明細部のFORM名
  		p_chkbox_name		明細部のチェックボックス名
  		p_id_item_name		明細部にて収集するID名
  ============================================================================*/
function getDelTarget(p_srch_form_name, p_set_item_name, p_dtl_form_name, p_chkbox_name, p_id_item_name){
	var this_form = document.getElementsByName(p_srch_form_name);
	var dtl_form  = document.getElementsByName(p_dtl_form_name);
	var len       = dtl_form.length;
	var chkcnt    = 0;
	var i;
	var keyid     = "";
	var delimiter_char = ","; // 区切り文字
	
	for (i=1; i<=len; i++){
		// チェックの入っているチェックボックスと同じ行のID項目を収集
		if(document.getElementsByName(p_chkbox_name)[i].checked){
			if(!keyid==""){
				// 2個目以降は区切り文字をつける
				keyid = keyid + delimiter_char + document.getElementsByName(p_id_item_name)[i].value;
			}else{
				keyid = document.getElementsByName(p_id_item_name)[i].value;
			}
		}
	}
	//alert(keyid);
	// ヘッドラインの隠し項目にセット
	//this_form.getElementsByName(p_set_item_name).value = keyid;
	$("input#hd_delete_target").attr("value", keyid);
	//alert(this_form[0]);
	//alert(document.fm_search.hd_delete_target.value);
	
	if(keyid!=""){
		// 対象データがある場合は、データ操作画面起動
		openOpData(this_form[0], "delete");
	}else{
		alert("チェックされたレコードがありません");
	}
}

/*==============================================================================
  メール送信対象セット
  処理概要：
  		明細表のチェックが入っている行のIDを収集し、ヘッドラインにある
  		隠し項目にセットする
  引数：
  		p_srch_form_name	ヘッドラインおよびメニュー部のFORM名
  		p_set_item_name		IDをセットする隠し項目名
  		p_dtl_form_name		明細部のFORM名
  		p_chkbox_name		明細部のチェックボックス名
  		p_id_item_name		明細部にて収集するID名
  ============================================================================*/
function getMailTarget(p_srch_form_name, p_set_item_name, p_dtl_form_name, p_chkbox_name, p_id_item_name){
	var this_form = document.getElementsByName(p_srch_form_name);
	var dtl_form  = document.getElementsByName(p_dtl_form_name);
	var len       = dtl_form.length;
	var chkcnt    = 0;
	var i;
	var keyid     = "";
	var delimiter_char = ","; // 区切り文字
	
	for (i=1; i<=len; i++){
		// チェックの入っているチェックボックスと同じ行のID項目を収集
		if(document.getElementsByName(p_chkbox_name)[i].checked){
			if(!keyid==""){
				// 2個目以降は区切り文字をつける
				keyid = keyid + delimiter_char + document.getElementsByName(p_id_item_name)[i].value;
			}else{
				keyid = document.getElementsByName(p_id_item_name)[i].value;
			}
		}
	}
	//alert(keyid);
	// ヘッドラインの隠し項目にセット
	//this_form.getElementsByName(p_set_item_name).value = keyid;
	$("input#hd_batchsend_target").attr("value", keyid);
	//alert(this_form[0]);
	//alert(document.fm_search.hd_batchsend_target.value);
	
	if(keyid!=""){
		// 対象データがある場合は、データ操作画面起動
		openOpData(this_form[0], "batchsend");
	}else{
		alert("チェックされたレコードがありません");
	}
}

/*==============================================================================
  データ操作画面起動
  処理概要：
  		データ操作画面をPOSTで起動する
  引数：
  		p_form_obj			ボタンのあるFORM
  		p_mode				オペレーションモード(insert、update、delete)
  ============================================================================*/
function openOpData(p_form_obj, p_mode){
	// フォームの取得
	var form_obj;
	if(p_mode=="delete"){
		// deleteの場合は、呼び出し元がformのオブジェクトを渡してくる
		form_obj = p_form_obj;
	}else if(p_mode == "batchsend"){
		// 一括送信の場合は、呼び出し元がformのオブジェクトを渡してくる
		form_obj = p_form_obj;
	}else{
		// delete以外の場合は、呼び出し元がthisを渡してくるので、formを取得する
		form_obj = p_form_obj.form;
	}
	
	// 新規に開くウィンドウの名前をページ名と日付シリアル値から作成
	var dt_today = new Date();
	var trgt_name = form_obj.hd_page_name.value + "_opData" + dt_today.getTime();
	var windowObject;
	
	var wx = 600;
	var wy = 550;
	var x = (screen.width  - wx) / 2;
	var y = (screen.height - wy) / 2;
	
	// ウィンドウオープン
	windowObject = window.open("",trgt_name,"left="+x+",top="+y+",width="+wx+",height="+wy+",menubar=no,toolbar=no,scrollbars=yes,resizable=no");
	form_obj.target					= trgt_name;
	form_obj.hd_reserv1_id.value	= p_mode;
	form_obj.method					= "POST";
	form_obj.action					= "../page/operatedata.php";
	form_obj.submit();
	
	// フォーカスを移動
	windowObject.focus();
	
	openWindow = windowObject;
}
/*==============================================================================
  ページ移動
  処理概要：
  		削除済表示のチェック状態を隠し項目に、格納し、引数で指定されたphpファイルにPOSTする
  引数：
  		p_form_obj			ボタンのあるFORM
  		p_move_to			移動先のphpファイル
  ============================================================================*/
function movePage(p_form_obj, p_move_to){
	var form_obj = p_form_obj.form;
	
	// 削除済みチェックの状態をPOST用の隠し項目にセットする
	// オブジェクトの存在チェック
	try{
		if($("#ck_displaydelete").attr('checked')){
			form_obj.hd_delete_check.value = "on";
		}else{
			form_obj.hd_delete_check.value = "";
		}
	}catch(error){
		var no_del_check = 1;
	}
	form_obj.target = window.name;
	form_obj.method	= "POST";
	form_obj.action	= p_move_to;
	form_obj.submit();
}


/*==============================================================================
  検索条件クリア
  処理概要：
  		検索条件をクリアする
  引数：
  		p_form_obj			ボタンのあるFORM
  ============================================================================*/
function clearCondition(p_form_obj){
	$("input.hdltextbox").attr("value","");
	$("input.hdltext").attr("value","");
}

/*==============================================================================
  ページオープン
  処理概要：
  		新規にウィンドウを開いてHTMLファイルを表示する
  引数：
  		p_window_name		新規ウィンドウの名前
  		p_html				開くHTMLファイル
  ============================================================================*/
function openPage(p_window_name, p_html){
	$new_win = window.open(p_html, p_window_name);
	window.focus($new_win);
}

/*==============================================================================
  データ操作画面起動
  処理概要：
  		データ操作画面をPOSTで起動する
  引数：
  		p_form_obj			ボタンのあるFORM
  		p_mode				オペレーションモード(insert、update、delete)
  ============================================================================*/
function openPDFData(p_form_obj, p_mode){
	// フォームの取得
	form_obj = p_form_obj.form;
	
	// 新規に開くウィンドウの名前をページ名と日付シリアル値から作成
	var dt_today = new Date();
	var trgt_name = form_obj.hd_page_name.value + "_PDFData" + dt_today.getTime();
	var windowObject;
	
	// ウィンドウオープン
	//windowObject = window.open("",trgt_name);
	form_obj.target					= trgt_name;
	form_obj.target					= "";
	form_obj.hd_reserv1_id.value	= p_mode;
	form_obj.method					= "POST";
	form_obj.action					= "../page/pdflist.php";
	form_obj.submit();
	
	openWindow = windowObject;
	
}