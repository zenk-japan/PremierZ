<?php

/******************************************************************************
 ファイル名：wrcontentslist.php
 処理概要  ：作業詳細画面
 GET受領値：
             token                      トークン(必須)
             gv_work_staff_id           作業者ID(必須)
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
	$l_rec_cnt			= 0;					// ポスト配列のカウント
	$l_msg_cnt			= 0;

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
/*
	$l_token			= $_GET['token'];				// トークン
	$l_work_staff_id	= $_GET['gv_work_staff_id'];	// 作業人員ID
	if($l_work_staff_id == ''){$l_err_flag = false;}	// 作業人員IDが取得できない場合はエラー
	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
 */
 /*----------------------------------------------------------------------------
  POST引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_POST['nm_token_code'];
	$l_work_staff_id	= $_POST['nm_selected_workstaff_id'];
	

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
	*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $l_token;
	if(is_null($l_post_token)){
		$l_err_flag = false;
	}
	
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
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	if($l_user_name == ""){
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
  
	// datetime型をtime型に変換するファンクションがあるオブジェクトの呼び出し
	require_once('../mdl/m_workstaff.php');
	$mwost = new m_workstaff();
	// ボタン押下判定
	if($_POST["entry_workstaff_switch"]!="ON"){
		// 作業詳細を表示したら場合、承認区分を未確認から未回答に更新
		if($lr_workstaff[APPROVAL_DIVISION] == "UC"){
			$lc_mwkst->upApproval($l_user_id, $l_work_staff_id);
		}
		
		// 作業予定時間
		if(isset($lr_workstaff[ENTERING_SCHEDULE_TIMET]) || isset($lr_workstaff[LEAVE_SCHEDULE_TIMET])){
			$l_show_rec_cnt++;
			// 年月日を非表示にする
			$lr_workstaff[ENTERING_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[ENTERING_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
			$lr_workstaff[LEAVE_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[LEAVE_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
			$array_time = preg_split("/\:/",$lr_workstaff[LEAVE_SCHEDULE_TIMET]);
			if($array_time[0] > 23){
				$array_time[0] = $array_time[0] -24;
				$array_time[2] = "（翌）";
				$lr_workstaff[LEAVE_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
			}
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "作業予定時間",
													"type"		=> INPUT_TYPE_DISP,
													"value"		=> htmlspecialchars($lr_workstaff[ENTERING_SCHEDULE_TIMET]."～".$lr_workstaff[LEAVE_SCHEDULE_TIMET])
												);
		}
		
		// 注意事項
		require_once('../lib/CommonMessage.php');
		$lc_cmmess = new CommonMessage();
		$l_caution_msg						=	$lc_cmmess->getWorkDetailCaution();
		
		// 以下、作業状況で変化
		// 承認区分 = 承認済みの場合
		if($lr_workstaff[APPROVAL_DIVISION] == "AP") {
			
			// 作業員ステータス = 出発前
			if($lr_workstaff[STAFF_STATUS] == "BD"){
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
				$lr_show_rec[$l_show_rec_cnt]	=	array(
														"caption"	=> "出発予定時間",
														"name"		=> "DISPATCH_SCHEDULE_TIMET",
														"type"		=> INPUT_TYPE_TEXT,
														"value"		=> htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET]),
														"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
													);
				
			// 作業員ステータス = 入店前
			} else if($lr_workstaff[STAFF_STATUS] == "BE"){
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[DISPATCH_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				
			// 作業員ステータス = 作業中
			} else if($lr_workstaff[STAFF_STATUS] == "NW"){
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[DISPATCH_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[ENTERING_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[ENTERING_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				
			} else if($lr_workstaff[STAFF_STATUS] == "WC"){
				// 出発予定時間
				$l_show_rec_cnt++;
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[DISPATCH_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[ENTERING_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[ENTERING_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				$lr_workstaff[LEAVE_STAFF_TIMET] = $mwost->convert_TIME($lr_workstaff[LEAVE_STAFF_TIMET], $lr_workstaff[WORK_DATE]);
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
				
			}
			
		// 承認区分 = AP(承認)以外の場合
		} else {
			// 承認区分
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"name"		=> "APPROVAL_DIVISION",
													"type"		=> INPUT_TYPE_HIDDEN,
													"value"		=> htmlspecialchars($lr_workstaff[APPROVAL_DIVISION])
												);
			
			$l_show_rec_cnt++;
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "承認区分",
													"name"		=> "APPROVAL_DIVISION",
													"type"		=> INPUT_TYPE_RADIO,
													"value"		=> htmlspecialchars($lr_workstaff[APPROVAL_DIVISION])
												);
			
			// 出発予定時間
			$l_show_rec_cnt++;
			$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $mwost->convert_TIME($lr_workstaff[DISPATCH_SCHEDULE_TIMET], $lr_workstaff[WORK_DATE]);
			$array_time = preg_split("/\:/",$lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
			if($array_time[0] > 23){
				$array_time[0] = $array_time[0] -24;
				$array_time[2] = "（翌）";
				$lr_workstaff[DISPATCH_SCHEDULE_TIMET] = $array_time[0].":".$array_time[1].$array_time[2];
			}
			$lr_show_rec[$l_show_rec_cnt]	=	array(
													"caption"	=> "出発予定時間",
													"name"		=> "DISPATCH_SCHEDULE_TIMET",
													"type"		=> INPUT_TYPE_TEXT,
													"value"		=> htmlspecialchars($lr_workstaff[DISPATCH_SCHEDULE_TIMET]),
													"style"		=> $lc_mcf->getInputStylePhrase($l_terminal, "NUMERIC")
												);
			// 承認ボタン
			$l_btn_rec_cnt++;
			$lr_btn_rec[$l_btn_rec_cnt]		=	array(
													"name"		=> "bt_approval",
													"type"		=> INPUT_TYPE_SUBMIT,
													"value"		=> "送信"
												);
			
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
			if($key != "nm_token_code" && $key != "nm_selected_workstaff_id" && $key != "entry_workstaff_switch"){
				$l_rec_cnt++;
				$input_data[$l_rec_cnt] = array("Input_col" => $key, "Input_val" => $i_val);
			} else if($key == "nm_selected_workstaff_id"){
				$l_rec_cnt++;
				$input_data[$l_rec_cnt] = array("Input_col" => "WORK_STAFF_ID", "Input_val" => $i_val);
			}
		}
		
		// 承認および出発予定時間
		if($_POST[bt_approval]){
		// 出発登録
		} else if($_POST[bt_dispatch]) {
			// 出発時間（作業者）
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "DISPATCH_STAFF_TIMET", "Input_val" => date("Y-m-d H:i"));
			// 出発予定時間
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "DISPATCH_SCHEDULE_TIMET", "Input_val" => $lr_workstaff[DISPATCH_SCHEDULE_TIMET]);
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "BE");
		// 入店登録
		} else if($_POST[bt_entering]) {
			// 入店時間（作業者）
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "ENTERING_STAFF_TIMET", "Input_val" => date("Y-m-d H:i"));
			
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "NW");
		// 退店登録
		} else if($_POST[bt_leave]) {
			// 退店時間（作業者）
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "LEAVE_STAFF_TIMET", "Input_val" => date("Y-m-d H:i"));
			
			// 作業員ステータス
			$l_rec_cnt++;
			$input_data[$l_rec_cnt] = array("Input_col" => "STAFF_STATUS", "Input_val" => "WC");
		}
		
		// WORK_STAFF更新
		$l_msg = $lc_mwkst->upWorkstaffDetail($l_user_id, $input_data);
		if($l_msg[RETERN_CODE] == RETURN_NOMAL){
			$l_msg_cnt++;
			$show_msg[$l_msg_cnt] = $l_msg[RETERN_MSG];
		} else {
			// RETURNされたメッセージを出力
			foreach($l_msg as $key => $e_msg){
				if($e_msg != RETURN_ERROR){
					$l_msg_cnt++;
					$show_msg[$l_msg_cnt] =	$e_msg;
				}
			}
		}
		
		// 退店登録の場合は作業完了一覧へリンクし、退店登録以外は前画面に戻る
		if($_POST[bt_leave]){
			// 作業完了一覧リンク
			$l_msg_cnt++;
			$show_msg[$l_msg_cnt] =	"<form action=\"../workreport/wrcompletionlist.php\" method=\"POST\"><br><INPUT type=\"submit\" value=\"作業完了一覧へ\" /></INPUT>";
		} else {
			// 戻るリンク
			$l_msg_cnt++;
			$show_msg[$l_msg_cnt] =	"<form action=\"../workreport/wrworkdetail.php\" method=\"POST\"><br><INPUT type=\"submit\" value=\"戻る\" /></INPUT>";
		}
	}
/*----------------------------------------------------------------------------
  変数定義&セット
  ----------------------------------------------------------------------------*/
	$copyright_text	= NULL;			//コピーライト
	$copyright_text	= "<font size=\"1\">".COPY_RIGHT_PHRASE."</font>";
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*-----------------------------------
	Smarty変数定義
  -----------------------------------*/
	$ar_css_files	= NULL;			// CSSファイル
	$ar_js_files	= NULL;			// jsファイル
	
	if($l_debug_mode==1){print("Step-Smarty変数定義");print "<br>";}
/*-----------------------------------
	Smarty変数セット
  -----------------------------------*/
	// CSSファイル
	$ar_css_files	= array(DIR_CSS."v_top_block.css",
							DIR_CSS."v_workreport_common.css",
							DIR_CSS."v_workreport_detail.css");
	// jsファイル
	$ar_js_files	= array(DIR_JS."jquery.js",
							DIR_JS."jfnc_common.js",
							DIR_JS."jfnc_workreport_detail.js",
							DIR_JS."jfnc_top.js");

	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
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
	$lc_smarty->assign("char_code",			$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",				$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",			$l_terminal);
	$lc_smarty->assign("model",				$l_model);
	
	$lc_smarty->assign("ar_js_files",		$ar_js_files);					// jsファイル
	$lc_smarty->assign("ar_css_files",		$ar_css_files);				// CSSファイル
	
	// タイトル
	$lc_smarty->assign("headtitle",			"作業報告");
	$lc_smarty->assign("user_auth",			$l_auth_name);					// ユーザー権限名
	$lc_smarty->assign("user_name",			$l_user_name);
	$lc_smarty->assign("headinfo",			"");
	
	// ロゴ
	$lc_smarty->assign("img_logo",			MOBILE_LOGO);
	
	// フォーム
	$lc_smarty->assign("fmurl",				$_SERVER['PHP_SELF']."?token=".$l_token."&gv_work_staff_id=".$l_work_staff_id);
	$lc_smarty->assign("fmact",				FMACT_POST);
	
	// 作業内容詳細
	$lc_smarty->assign("ar_workstaff",		$lr_show_rec);
	$lc_smarty->assign("token",				$l_token);
	$lc_smarty->assign("ar_workstaff_btn",	$lr_btn_rec);
	$lc_smarty->assign("ar_workstaff_rec",	$lr_workstaff);
	$lc_smarty->assign("ar_msg",			$show_msg);
	$lc_smarty->assign("ar_break_timelist",	$ar_break_timelist);
	$lc_smarty->assign("caution_msg",		$l_caution_msg);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $copyright_text);
	
	// 隠し項目
	$lr_hidden_items	= array(
							array(									// トークン
								  "name"	=> "nm_token_code"
								, "value"	=> $l_token
								),
							array(
								  "name"	=> "nm_selected_workstaff_id"
								, "value"	=> $l_work_staff_id
								),
							array(
								  "name"	=> "WORK_DATE"
								, "value"	=> $lr_workstaff[WORK_DATE]
								)
							);
	$lc_smarty->assign("ar_hidden_items",	$lr_hidden_items);
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('workreport_detail.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>