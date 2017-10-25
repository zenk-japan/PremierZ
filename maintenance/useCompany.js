/*******************************************************************************
 利用会社画面処理
*******************************************************************************/
var $pkey_item_name			= "nm_define_id";				// 定義IDのPOST項目名(変更不可)
var $token_item_name		= "nm_token_code";				// トークンのPOST項目名(変更不可)
var $pmode_item_name		= "nm_proc_mode";				// 起動モードのPOST項目名(変更不可)
var $this_page				= "useCompany.php";				// 自ページのファイル名
var $key_item_colnum		= "3";							// 更新や削除のキーとなる項目の列番号
var $del_post_to			= "c_useCompanyDel.php";		// 削除処理でPOSTするPHPファイル
var $insupd_post_to			= "c_useCompanySave.php";		// 新規登録、更新処理でPOSTするPHPファイル
var $ins_sub_view			= "useCompanyInsert.php";		// 新規登録のサブ画面用PHPファイル
//var $ins_sub_view			= "x_stub.php";		// 新規登録のサブ画面用PHPファイル
var $menu_post_to			= "mntMenu.php";				// メニューに戻るでPOSTするphpファイル
var $dtlrec;												// 明細行オブジェクト
var $dtlreccnt				= 0;							// 明細行数
var $alert_color			= "#ff0000";					// 入力エラー項目の背景色
var $id_loading_div			= "id_ext_loading_div";			// ロード中表示DIVのID


/*==============================================================================
  新規登録処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procInsert($p_obj_hidden){
	//alert("insert");
	// 新規登録画面の起動
	$lr_insparam = {};
	$lr_insparam[$token_item_name] = $("input[name='"+$token_item_name+"']").val();
	showSubView($lr_insparam, $ins_sub_view);
	// 新規画面が開いている場合は保存ボタン処理をバインド
	
}

/*==============================================================================
  メニューに戻る処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  ============================================================================*/
function procReturn($p_obj_hidden){
	//alert("insert");
	// メニューの起動
	movePage($p_obj_hidden, $menu_post_to);
}

/*==============================================================================
  削除処理
  引数
  				$p_checked_index			選択されているレコードの番号(1が先頭)
  ============================================================================*/
function procDelete($p_checked_index){
	$lr_param = {};			// 連想配列の初期化

	// パラメータ設定
	$lr_param[$token_item_name]	= $("input[name='"+$token_item_name+"']").val();		// トークン
	$lr_param[$pkey_item_name]	= $("#id_ipt_dataid" + ($p_checked_index + 1)).val();			// DATA_ID
	//alert($lr_param[$pkey_item_name]);return;
	
	// 確認ダイアログ表示
	if(window.confirm("システム内のすべてのテーブルから\n\n「DATA_ID = " + $lr_param[$pkey_item_name] + "」\n\nのデータが削除されます。\nよろしいですか？")){

		// NowLoading表示
		showNowLoading();
		// 削除処理の起動
		$.post($del_post_to, $lr_param, callBackFncDel);
	
	}
}
// コールバック関数
function callBackFncDel($p_data){
	removeNowLoading();
	if($p_data){
		if($p_data==0){
			alert("削除しました");
			movePage($obj_hiddenform, $this_page);
		}else{
			alert($p_data);return;
			// 戻り値を「;」で分離
			$lr_message = $p_data.split(";");
			//alert($lr_message[0]);
			//alert($lr_message[1]);
			
			// エラーメッセージを表示
			alert($lr_message[1]);
			
			return false;
		}
		//alert($p_data);
	}else{
		alert("No DATA");
	}
}

/*==============================================================================
  削除処理
  引数
  				$p_checked_index			選択されているレコードの番号(1が先頭)
  ============================================================================*/
function procDataCheck($p_checked_index){
	$lr_param = {};			// 連想配列の初期化

	// パラメータ設定
	$lr_param[$token_item_name]	= $("input[name='"+$token_item_name+"']").val();		// トークン
	$lr_param[$pmode_item_name]	= "CNT";												// 起動モード
	$lr_param[$pkey_item_name]	= $("#id_ipt_dataid" + $p_checked_index).val();			// DATA_ID
	//alert($lr_param[$pkey_item_name]);return;
	
	// NowLoading表示
	showNowLoading();
	// 削除処理の起動(カウントモード)
	$.post($del_post_to, $lr_param, callBackFncChk);
	
}
// コールバック関数
function callBackFncChk($p_data){
	removeNowLoading();
	if($p_data){
		$l_message = "==== 各テーブル内のデータ件数 ====\n" + $p_data;
		alert($l_message);
		return;
		//alert($p_data);
	}else{
		alert("No DATA");
	}
}
/*==============================================================================
  更新処理
  処理概要：
  		明細表のデータを配列に格納し、データ保存用のPHPファイルを起動する
  ============================================================================*/
function procUpdate(){
	$lr_param = {};
	
	// 明細行がなければ終了
	if($dtlreccnt == 0){
		return false;
	}
	//alert($dtlreccnt);
	
	
	// 入力項目の背景を元に戻す
	$dtlrec.find("input:text").css("background-color", "transparent");
		
	// パラメータのセット
	// トークン
	$lr_param[$token_item_name]	= $("input[name='"+$token_item_name+"']").val();

	// レコード
	var $l_linenum				= 0;
	$lr_param["data_record"]	= {};	// レコード用配列を初期化
	var $l_errflg_all			= 0;				// データ全体用エラーフラグ
	var $alert_mess				= "";
	
	$dtlrec.each(function(){
		$l_linenum++;
		$lr_data = {};
		var $l_errflg_rec = 0;	// レコード用エラーフラグ
		
		/*------------------------
			DATA_ID
		------------------------*/
		$l_itembuff					= "";
		$l_itembuff					= $("#id_ipt_dataid" + $l_linenum).val();
		$lr_data["data_id"]			= removeSpace($l_itembuff);
		
		/*------------------------
			利用会社コード
		------------------------*/
		$l_itembuff					= "";
		$l_itembuff					= $("#id_ipt_compcd" + $l_linenum).val();
		$lr_data["comp_code"]		= removeSpace($l_itembuff);
		// 入力されているかチェック
		if($lr_data["comp_code"]){
			// 英数字の組み合わせ
			if(!IsAlphNum($lr_data["comp_code"])){
				$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + " 利用会社コードは英数字で指定して下さい。" + "\n";
				$l_errflg_rec = 1;
			// ６文字以内
			}else if($lr_data["comp_code"].length > 6){
				$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + "利用会社コードは６文字以内で指定して下さい。" + "\n";
				$l_errflg_rec = 1;
			}
		}else{
			$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + "利用会社コードを入力して下さい。" + "\n";
			$l_errflg_rec = 1;
		}
		
		/*------------------------
			利用会社名
		------------------------*/
		$l_itembuff					= "";
		$l_itembuff					= $("#id_ipt_compnm" + $l_linenum).val();
		$l_itembuff					= removeSpace($l_itembuff);
		$lr_data["comp_name"]		= removeSpChar($l_itembuff);
		// 入力されているかチェック
		if($lr_data["comp_name"]){
			// ５０文字以内
			if($lr_data["comp_name"].length > 50){
				$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + "利用会社名は５０文字以内で指定して下さい。" + "\n";
				$l_errflg_rec = 1;
			}
		}else{
			$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + "利用会社名を入力して下さい。" + "\n";
			$l_errflg_rec = 1;
		}
		
		/*------------------------
			備考
		------------------------*/
		$l_itembuff					= "";
		$l_itembuff					= $("#id_ipt_remark" + $l_linenum).val();
		$l_itembuff					= removeSpace($l_itembuff);
		$lr_data["comp_remarks"]	= removeSpChar($l_itembuff);

		// ５０文字以内
		if($lr_data["comp_remarks"].length > 50){
			$alert_mess = $alert_mess + "DATA_ID：" + $lr_data["data_id"] + "備考は５０文字以内で指定して下さい。" + "\n";
			$l_errflg_rec = 1;
		}
		
		// レコード作成
		$lr_param["data_record"][$l_linenum] = $lr_data;
		
		if ($l_errflg_rec == 1){
			// エラーのあったレコードの背景色を変更
			$l_usecompcd_dom = $("#id_ipt_compcd" + $l_linenum + ",#id_ipt_compnm" + $l_linenum + ",#id_ipt_remark" + $l_linenum);
			$l_usecompcd_dom.css("background-color", $alert_color);
			$l_errflg_all = 1;
		}
	});
	
	// チェックエラーが有った場合はここで終了
	if ($l_errflg_all == 1){
		alert($alert_mess);
		return false;
	}
	
	// NowLoading表示
	showNowLoading();
	
	// 更新処理の起動
	//alert("update done");
	$.post($insupd_post_to, $lr_param, callBackFncUpd);
}
// コールバック関数
function callBackFncUpd($p_data){
	removeNowLoading();
	if($p_data){
		alert($p_data);
		
		return false;
	}else{
		//alert("No DATA");
		// 自画面を再読み込み
		alert("保存が完了しました");
		movePage($obj_hiddenform, $this_page);
	}
}

/*==============================================================================
  入力を元に戻す
  処理概要：
  		入力項目を元に戻す
  引数：
  ============================================================================*/
function setDefault(){
	// 入力項目全体を取得
	$lo_inputtxt = $(".c_ipt_dataid, .c_ipt_compcd, .c_ipt_compnm, .c_ipt_remark");
	//alert($lo_inputtxt.size());
	
	if($lo_inputtxt) {
		$lo_nexthiddenval = "";
		$lo_inputtxt.each(function(){
			if($(this).next("input:hidden")) {
				// 該当項目の次のhidden項目が見つかった場合は値を取得し、該当項目にセット
				//alert($lo_nexthiddenval);
				$(this).val($(this).next("input:hidden").val());
			}
		});
	}
}


/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){
	// 隠し項目のオブジェクト
	$obj_hiddenform = $("#id_form_hidden");
	
	//==============================================
	// 値の取得
	//==============================================
	// 明細行
	$dtlrec = $(".c_tr_main_dtl_odd,.c_tr_main_dtl_even");
	
	// 明細行数
	$dtlreccnt = $dtlrec.size();
	
	//==============================================
	// 各ボタンクリック時処理
	//==============================================
	// 元に戻す
	$("#id_btn_reset").bind("click", function(){
		setDefault();
	});
	
	// 新規登録
	$("#id_btn_insert").bind("click", function(){
		procInsert($obj_hiddenform);
	});
	
	// 保存
	$("#id_btn_save").bind("click", function(){
		procUpdate();
	});
	
	// 表示
	$(".c_btn_check").bind("click", function(){
		$l_recnum = parseInt($(".c_btn_check").index(this), 10) + 1;
		//alert("delete"+$l_recnum);
		procDataCheck($l_recnum);
	});
	
	// 削除
	$(".c_btn_delete").bind("click", function(){
		$l_recnum = parseInt($(".c_btn_delete").index(this), 10) + 1;
		//alert("delete"+$l_recnum);
		procDelete($l_recnum);
	});
	
	// メニューに戻るボタン
	$("#id_btn_gomenu").bind("click", function(){
		// メニューの起動
		procReturn($obj_hiddenform);
	});
	
	//==============================================
	// ハイライト処理
	//==============================================
	// 明細左がある場合はハイライト処理をバインドする
	if("#id_table_dtl_left"){
		//bindHighlight();
	}

	//==============================================
	// テーブルクリック処理
	//==============================================
	if("#id_table_dtl_left"){
		//bindTableClick();
	}

});
