<?php


/******************************************************************************
 ファイル名：wrworksituationdetail.php
 処理概要  ：作業一覧画面
 GET受領値：
             token                      トークン(必須)
             gv_show_page               表示ページ番号(任意)
             gv_max_page                最大ページ番号(任意)
             gv_num_to_show             表示レコード数(任意)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_GET);
		print "step1<br>";
	}
/*----------------------------------------------------------------------------
  変数定義
  ----------------------------------------------------------------------------*/
	$l_terminal			= "";					// 端末キャリア
	$l_model			= "";					// 端末モデル
	$lr_spdesc			= "";					// 端末固有のヘッダー記載情報
	$l_char_code		= "character_code";		// 文字コード
	$l_doctype			= "declaration";		// ドキュメントタイプ宣言
	$l_xmlns			= "xmlns";				// XML名前空間
	$l_token			= "";					// GETトークン
	$l_sess_token		= "";					// セッショントークン
	$l_err_flag			= true;					// エラーフラグ
	$l_show_rec_cnt		= 0;					// 表示項目カウント
	$l_str_mobile_phone = "";					// 携帯電話番号
	$l_call_phone		= "";
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_name		= "";									// セッションで保持しているユーザー名
	$l_data_id			= "";									// 画面にセットするDATA_ID
	$l_error_type_st	= "ST";									// エラータイプ(ST:セッション断)

/*----------------------------------------------------------------------------
  モバイル共通関数インスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/MobileCommonFunctions.php');
	$lc_mcf = new MobileCommonFunctions();
	
/*==================================
  キャリア判別
  ==================================*/
	require_once('../lib/CommonMobiles.php');
	$lc_cm = new CommonMobiles();
	$l_connec_terminal = $lc_cm->checkMobiles();
	
	$l_terminal		= $l_connec_terminal['Terminal'];
	$l_model		= $l_connec_terminal['Model'];
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
		
	if($l_debug_mode==1){print("Step-キャリア判別");print "<br>";}
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_mainmenu(Exception $e){
		// セッション切断の場合はメッセージに「ST」と入ってくる
		if($e->getMessage() == "ST"){
			$l_error_type = "ST";
		}else{
			$l_error_type = "ER";
		}
		
		require_once('../lib/ShowMessage.php');
		$lc_smess = new ShowMessage($l_error_type);
		
		// 予期せぬ例外の場合は追加メッセージをセット
		if($l_error_type != "ST"){
			$lc_smess->setExtMessage($e->getMessage());
		}
		
		$lc_smess->showMessage();
		
		return;
    }
	set_exception_handler('my_exception_mainmenu');
	
	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	
	if(is_null($_GET['token']) || $_GET['token'] ==""){
		$l_token			= $_POST['TOKEN'];				// トークン
	}else {
		$l_token			= $_GET['token'];
	}
	
	if(is_null($_GET['wsid']) || $_GET['wsid'] ==""){
		$l_work_staff_id	= $_POST['WORK_STAFF_ID'];		// 作業人員ID
	}else {
		$l_work_staff_id	= $_GET['wsid'];
	}
	
	if(is_null($_GET['wdate']) || $_GET['wdate'] ==""){
		$l_work_date	= $_POST['WORK_DATE'];				// 作業日
	}else {
		$l_work_date	= $_GET['wdate'];
	}
	
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	/*
	// セッションチェック
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
	$l_authority = $lr_session['AUTHORITY_CODE'];
	
	if($l_debug_mode==1){
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_GET['token'];
	if(is_null($l_post_token)){
		$l_err_flag = false;
	}
	*/
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		$l_err_flag = false;
	}
	if($l_post_token != $l_sess_token){
		$l_err_flag = false;
	}
	
	// 権限の取得
	$l_authority = $lc_sess->getSesseionItem('AUTHORITY_CODE');
	
	// ユーザー権限名の取得
	$l_auth_name = $lc_sess->getSesseionItem('AUTHORITY_NAME');
	
	// ユーザーIDの取得
	$l_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_user_id == ""){
		$l_err_flag = false;
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		$l_err_flag = false;
	}
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($lr_spdesc, $l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_workstaff.php');
	$lc_mwkst = new m_workstaff();
	
	$lr_workstaff = $lc_mwkst->getWorkStaffRec($l_work_staff_id);
	
	if($l_debug_mode==1){
		print count($lr_workstaff)."<br>";
		print_r($lr_workstaff);
		print "<br>";
	}
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	// print_r($_REQUEST);
	
	// datetime型をtime型に変換するファンクションがあるオブジェクトの呼び出し
	require_once('../mdl/m_workstaff.php');
	$mwost = new m_workstaff();
	// ボタン押下判定
	if(empty($_POST)){
		
		// 作業者
		if(isset($lr_workstaff[WORK_USER_NAME])){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【作業者】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[WORK_USER_NAME])
												);
		}
		
		// 作業拠点
		if(isset($lr_workstaff[WORK_BASE_NAME])){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【作業拠点】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[WORK_BASE_NAME])
												);
		}
		
		// 作業状況
		if(isset($lr_workstaff[STAFF_STATUS_NAME])){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【作業状況】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[STAFF_STATUS_NAME])
												);
		}
			
		// 入退店予定時間
		if(isset($lr_workstaff[LEAVE_SCHEDULE_TIMET])){
			$l_show_rec_cnt++;
			// 年月日を非表示にする
			$lr_workstaff[ENTERING_SCHEDULE_TIMET]	= $mwost->convert_TIME($lr_workstaff[ENTERING_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
			$array_time = preg_split("/\:/",$lr_workstaff[ENTERING_SCHEDULE_TIMET]);
			if($array_time[0] > 23){
				$array_time[0] = $array_time[0] -24;
				$array_time[2] = "（翌）";
				$lr_workstaff[ENTERING_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
			}
			$lr_workstaff[LEAVE_SCHEDULE_TIMET]		= $mwost->convert_TIME($lr_workstaff[LEAVE_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
			$array_time = preg_split("/\:/",$lr_workstaff[LEAVE_SCHEDULE_TIMET]);
			if($array_time[0] > 23){
				$array_time[0] = $array_time[0] -24;
				$array_time[2] = "（翌）";
				$lr_workstaff[LEAVE_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
			}
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【入退店予定時間】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[ENTERING_SCHEDULE_TIMET]."～".$lr_workstaff[LEAVE_SCHEDULE_TIMET])
												);
		}
		
		// 最寄駅
		if(isset($lr_workstaff[WORK_CLOSEST_STATION])){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【最寄駅】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[WORK_CLOSEST_STATION])
												);
		}
		
		// 所属会社
		if(isset($lr_workstaff[COMPANY_NAME])){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【所属会社】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[COMPANY_NAME])
												);
		}
		
		// 連絡先
		// 電話
		if(isset($lr_workstaff[WORK_MOBILE_PHONE])){
			$l_mobile_phone = split("-", htmlspecialchars($lr_workstaff[WORK_MOBILE_PHONE]));
			for($i = 0; $i <= count($l_mobile_phone); $i++) {
				$l_str_mobile_phone .= $l_mobile_phone[$i];
			}
			$l_call_phone		=	"&nbsp;"."<a href=\"tel:".$l_str_mobile_phone."\">電話</a>";
			
			// メール
			if(isset($lr_workstaff[WORK_MOBILE_PHONE_MAIL])){
				$l_mobile_phone_mail = split("-", htmlspecialchars($lr_workstaff[WORK_MOBILE_PHONE_MAIL]));
				$l_str_mobile_phone_mail = "";
				for($i = 0; $i <= count($l_mobile_phone_mail); $i++) {
					$l_str_mobile_phone_mail .= $l_mobile_phone_mail[$i];
				}
				$l_mobile_mail		= "&nbsp;"."<a href=\"mailto:".$l_str_mobile_phone_mail."\">メール</a>";
			}
			
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【連絡先】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> $l_call_phone.$l_mobile_mail
												);
		}
		// 電話番号が登録されていない場合
		else if(isset($lr_workstaff[WORK_MOBILE_PHONE_MAIL])){
				$l_mobile_phone_mail = split("-", htmlspecialchars($lr_workstaff[WORK_MOBILE_PHONE_MAIL]));
				$l_str_mobile_phone_mail = "";
				for($i = 0; $i <= count($l_mobile_phone_mail); $i++) {
					$l_str_mobile_phone_mail .= $l_mobile_phone_mail[$i];
				}
				$l_mobile_mail		= "&nbsp;"."<a href=\"mailto:".$l_str_mobile_phone_mail."\">メール</a>";
				
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【連絡先】",
														"type"		=> INPUT_TYPE_DISP,
														"value"		=> $l_mobile_mail
													);
		}
		
		// 以下、作業状況で変化
		if($lr_workstaff[APPROVAL_DIVISION] != "AP"){
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"name"		=> "APPROVAL_DIVISION",
													"type"		=> INPUT_TYPE_HIDDEN,
													"value"		=> htmlspecialchars($lr_workstaff[APPROVAL_DIVISION])
												);
			
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"name"		=> "TOKEN",
													"type"		=> INPUT_TYPE_HIDDEN,
													"value"		=> $l_token
												);
			// 作業日
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"name"		=> "WORK_DATE",
													"type"		=> INPUT_TYPE_HIDDEN,
													"value"		=> $l_work_date
												);
			
			// 作業ステータス
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "【作業ステータス】",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[APPROVAL_DIVISION_NAME])
												);
		}
		else {
			// 作業員ステータス = 出発前
			if($lr_workstaff[STAFF_STATUS] == "BD"){
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "APPROVAL_DIVISION",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> htmlspecialchars($lr_workstaff[APPROVAL_DIVISION])
													);
				
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "TOKEN",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_token
													);
				// 作業日
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "WORK_DATE",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_work_date
													);
				
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【出発予定時間】",
														"type"		=> INPUT_TYPE_DISP,
														"value"		=> htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET])
													);
				
				// 出発時間
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【出発時間】",
														"name"		=> "DISPATCH_STAFF_TIMET",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> date("H:i"),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
													);
				
				// 備考
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【備考】",
														"name"		=> "REMARKS",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> htmlspecialchars($lr_workstaff[REMARKS]),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
													);
				
				if($lr_workstaff[CANCEL_DIVISION] =="WR"){
					// 出発登録ボタン
					$l_btn_rec_cnt++;
					$lr_btn_rec[$l_btn_rec_cnt]	=		array(
															"name"		=> "bt_dispatch",
															"type"		=> INPUT_TYPE_SUBMIT,
															"value"		=> "出発代理登録"
														);
					
					// 作業キャンセルボタン
					$l_btn_rec_cnt++;
					$lr_btn_rec[$l_btn_rec_cnt]	=		array(
															"name"		=> "work_cansel",
															"type"		=> INPUT_TYPE_SUBMIT,
															"value"		=> "作業キャンセル"
														);
				}
				else {
					// 作業依頼ボタン
					$l_btn_rec_cnt++;
					$lr_btn_rec[$l_btn_rec_cnt]	=		array(
															"name"		=> "work_request",
															"type"		=> INPUT_TYPE_SUBMIT,
															"value"		=> "作業依頼"
														);
				}
					
					
			// 作業員ステータス = 入店前
			} else if($lr_workstaff[STAFF_STATUS] == "BE"){
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "TOKEN",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_token
													);
				
				// 作業日
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "WORK_DATE",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_work_date
													);
				
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発予定：".htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET])
													);
				
				// 出発時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発時間：".htmlspecialchars($lr_workstaff[DISPATCH_STAFF_TIMET])
													);
				
				// 入店時間
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【入店時間】",
														"name"		=> "ENTERING_STAFF_TIMET",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> date("H:i"),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
													);
				
				// 備考
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【備考】",
														"name"		=> "REMARKS",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> htmlspecialchars($lr_workstaff[REMARKS]),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
													);
				
				if($lr_workstaff[CANCEL_DIVISION] =="WR"){
					// 入店登録ボタン
				$l_btn_rec_cnt++;
				$lr_btn_rec[$l_btn_rec_cnt]	=		array(
														"name"		=> "bt_entering",
														"type"		=> INPUT_TYPE_SUBMIT,
														"value"		=> "入店代理登録"
													);
					
					// 作業キャンセルボタン
					$l_btn_rec_cnt++;
					$lr_btn_rec[$l_btn_rec_cnt]	=		array(
															"name"		=> "work_cansel",
															"type"		=> INPUT_TYPE_SUBMIT,
															"value"		=> "作業キャンセル"
														);
				}
				else {
					// 作業依頼ボタン
					$l_btn_rec_cnt++;
					$lr_btn_rec[$l_btn_rec_cnt]	=		array(
															"name"		=> "work_request",
															"type"		=> INPUT_TYPE_SUBMIT,
															"value"		=> "作業依頼"
														);
				}
				
			// 作業員ステータス = 作業中
			} else if($lr_workstaff[STAFF_STATUS] == "NW"){
				
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "TOKEN",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_token
													);
				
				// 作業日
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "WORK_DATE",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_work_date
													);
				
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発予定：".htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET])
													);
				
				// 出発時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発時間：".htmlspecialchars($lr_workstaff[DISPATCH_STAFF_TIMET])
													);
				
				// 入店時間
				$l_show_rec_cnt++;
				$lr_workstaff[ENTERING_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[ENTERING_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[ENTERING_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[ENTERING_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "入店時間：".htmlspecialchars($lr_workstaff[ENTERING_STAFF_TIMET])
													);
				
				// 退店時間
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【退店時間】",
														"name"		=> "LEAVE_STAFF_TIMET",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> date("H:i"),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
													);
				
				// 休憩（リストボックス）
				$ar_break_timelist	=	array(
											array("value"	=>	"0.00",	"itemname"	=>	"休憩なし"),
											array("value"	=>	"0.25",	"itemname"	=>	"15分"),
											array("value"	=>	"0.50",	"itemname"	=>	"30分"),
											array("value"	=>	"0.75",	"itemname"	=>	"45分"),
											array("value"	=>	"1.00",	"itemname"	=>	"1時間"),
											array("value"	=>	"1.25",	"itemname"	=>	"1時間15分"),
											array("value"	=>	"1.50",	"itemname"	=>	"1時間30分"),
											array("value"	=>	"1.75",	"itemname"	=>	"1時間45分"),
											array("value"	=>	"2.00",	"itemname"	=>	"2時間"),
											array("value"	=>	"999",	"itemname"	=>	"その他"),
										);
				
				// 休憩（リストボックス）の初期値設定
				for($i = 0; $i <= count($ar_break_timelist); $i++) {
					if($ar_break_timelist[$i][value] == htmlspecialchars($lr_workstaff[BREAK_TIME])){
						$ar_break_timelist[$i][selected] = COLKEY_SELECTED;
					}
				}
				
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【休憩】",
														"name"		=> "BREAK_TIME",
														"type"		=> INPUT_TYPE_COMBO,
														"value"		=> $ar_break_timelist
													);
				
				// 備考
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "【備考】",
														"name"		=> "REMARKS",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> htmlspecialchars($lr_workstaff[REMARKS]),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "HIRAGANA")
													);
				
				// 退店登録ボタン
				$l_btn_rec_cnt++;
				$lr_btn_rec[$l_btn_rec_cnt]	=		array(
														"name"		=> "bt_leave",
														"type"		=> INPUT_TYPE_SUBMIT,
														"value"		=> "退店代理登録"
													);
			} else if($lr_workstaff[STAFF_STATUS] == "WC"){
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "TOKEN",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_token
													);
				
				// 作業日
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"name"		=> "WORK_DATE",
														"type"		=> INPUT_TYPE_HIDDEN,
														"value"		=> $l_work_date
													);
				
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発予定：".htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET])
													);
				
				// 出発時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[DISPATCH_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "出発時間：".htmlspecialchars($lr_workstaff[DISPATCH_STAFF_TIMET])
													);
				
				// 入店時間
				$l_show_rec_cnt++;
				$lr_workstaff[ENTERING_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[ENTERING_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[ENTERING_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[ENTERING_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "入店時間：".htmlspecialchars($lr_workstaff[ENTERING_STAFF_TIMET])
													);
				
				// 退店時間
				$l_show_rec_cnt++;
				$lr_workstaff[LEAVE_STAFF_TIMET]	= $mwost->convert_TIME($lr_workstaff[LEAVE_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
				$array_time = preg_split("/\:/",$lr_workstaff[LEAVE_STAFF_TIMET]);
				if($array_time[0] > 23){
					$array_time[0] = $array_time[0] -24;
					$array_time[2] = "（翌）";
					$lr_workstaff[LEAVE_STAFF_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
				}
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "退店時間：".htmlspecialchars($lr_workstaff[LEAVE_STAFF_TIMET])
													);
				
				// 休憩
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "休憩時間：".htmlspecialchars($lr_workstaff[BREAK_TIME])
													);
				
				// 備考
				$l_show_rec_cnt++;
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"type"		=> INPUT_TYPE_COMMENT,
														"value"		=> "備考：".htmlspecialchars($lr_workstaff[REMARKS])
													);
				
			}
		}
		
		// 作業者ID
		$l_show_rec_cnt++;
		$lr_show_rec[$l_show_rec_cnt]		=	array(
													"name"		=> "WORK_STAFF_ID",
													"type"		=> INPUT_TYPE_HIDDEN,
													"value"		=> htmlspecialchars($lr_workstaff[WORK_STAFF_ID])
												);
		
		// 改行
		$l_show_rec_cnt++;
		$lr_show_rec[$l_show_rec_cnt]		=	array(
													"type"		=> "RETURN",
												);
		
		
	} else {
		// 入力データ
		foreach($_POST as $key => $i_val){
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => $key, "Input_val" => $i_val);
		}
		
		// 承認および出発予定時間
		if($_POST[bt_send]){
		// 出発登録
		} else if($_POST[bt_dispatch]) {
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "BE");
		// 入店登録
		} else if($_POST[bt_entering]) {
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "NW");
		// 退店登録
		} else if($_POST[bt_leave]) {
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "WC");
		}
		
		if($_POST[work_cansel]){
			// 作業キャンセル
			$l_cansel_data = "WC";
			$l_msg = $lc_mwkst->upCancelDivision($l_user_id, $l_work_staff_id,$l_cansel_data);
		}
		
		else if($_POST[work_request]){
			// 作業依頼
			$l_cansel_data = "WR";
			$l_msg = $lc_mwkst->upCancelDivision($l_user_id, $l_work_staff_id,$l_cansel_data);
		}
			
		else {
			// WORK_STAFF更新
			$l_msg = $lc_mwkst->CommissionUpWorkstaffDetail($l_user_id, $input_data);
		}
		
		if($l_msg[RETERN_CODE] == RETURN_NOMAL){
			$lr_show_rec = array(
								array(
									"type"		=> INPUT_TYPE_COMMENT,
									"value"		=> $l_msg[RETERN_MSG]
								)
			);
			
		} else {
			// RETURNされたメッセージを出力
			foreach($l_msg as $key => $e_msg){
				if($e_msg != RETURN_ERROR){
					$l_msg_cnt++;
					$lr_show_rec[$l_msg_cnt] =	array(
													"type"		=> INPUT_TYPE_COMMENT,
													"value"		=> $e_msg
												);
				}
			}
		}
		
		// 前画面に戻るリンク
		$l_msg_cnt++;
		$lr_show_rec[$l_msg_cnt] =	array(
										"type"		=> INPUT_TYPE_COMMENT,
										"value"		=> "<br>&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?token=".$l_token."&wsid=".$l_work_staff_id."&wdate=".$l_work_date."\" ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 2).">"."OK</a>"
									);
	}
/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*==================================
  smartyクラスインスタンス作成
  ==================================*/
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir	= DIR_TEMPLATES;
	$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
	$lc_smarty->config_dir		= DIR_CONFIGS;
	$lc_smarty->cache_dir		= DIR_CACHE;
	
	if($l_debug_mode==1){print("Step-smartyクラスインスタンス作成");print "<br>";}
	
/*==================================
  smartyアサイン
  ==================================*/
	// ヘッダー部
	$lc_smarty->assign("doctype",			$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	// タイトル
	$lc_smarty->assign("headtitle",			SCREEN_ZSMM0030);
	$lc_smarty->assign("headinfo",			"");
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF']."?token=".$l_token."&wsid=".$l_work_staff_id);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// 作業内容詳細
	$lc_smarty->assign("ar_workstaff",		$lr_show_rec);
	$lc_smarty->assign("token",				$l_token);
	$lc_smarty->assign("ar_workstaff_btn",	$lr_btn_rec);
	
	// ハイパーリンク
	$lr_bottom_menu = array(
						array(
								"link_url"	=> "wrworkcontents.php?token=".$l_token,
								"value"		=> SCREEN_ZSMM0020."へ戻る",
								"key"		=> "0"
							),
						array(
								"link_url"	=> "wrworksituation.php?token=".$l_token."&WORK_DATE=".$l_work_date,
								"value"		=> "前の画面へ戻る",
								"key"		=> "2"
							),
						array(
								"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token."&wsid=".$l_work_staff_id,
								"value"		=> "ページ更新",
								"key"		=> "5"
							),
						array(
								"link_url"	=> "wrlogout.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC002,
								"key"		=> "9"
							),
						array(
								"link_url"	=> DIR_MAN."index.php?token=".$l_token,
								"value"		=> SCREEN_ZSMMC999,
								"key"		=> "#"
							)
						);
	
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", copyright_text);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateWorkSituationDetail.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>