<?php
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

/******************************************************************************
 ファイル名：pdflist_mnt.php
 処理概要  ：PDF出力画面
 POST受領値：

******************************************************************************/

	$l_dir_prfx		= dirname(__FILE__)."/";								// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;					// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print "post-><br>";
		print var_dump($_POST);
		print "<br>";
		print "session-><br>";
		print var_dump($_SESSION);
		print "<br>";
	}
	
	session_start();
	
// =============================================================================
// 自由表示部
// フッター部に合計値等特殊な値を表示する場合は、このファンクションに記述する
// TABLEを使用する場合は変数定義にあるCSSクラスが使用できる
// 直接echoせずに、HTMLの文字列をreturnする
// =============================================================================

/*----------------------------------------------------------------------------
  前処理
  ----------------------------------------------------------------------------*/
	require_once('../lib/CommonStaticValue.php');

	//print "step2<br>";
	
	
	// 丸めと丸め基準時間の状態を表示する
	if(!empty($_POST["ESTIMATE_ID"])) {
		require_once('../mdl/m_common_master.php');
		$lc_mcm = new m_common_master();
		
		// 基準時間と丸め方法の設定。帳票出力時とPDF出力時でPOSTされる内容が異なる(VALUE/NAME)ため、一旦VALUEを検索し、検索できなければもともとVALUEとみなして出力
		$l_base_time = $lc_mcm->getCommonValue($_SESSION['_authsession']['data']['DATA_ID'], "FRACTION_UNIT", $_POST["BASE_TIME"]);
		if(is_null($l_base_time) && !is_null($_POST["BASE_TIME"])){$l_base_time = $_POST["BASE_TIME"];}
		$l_round_type = $lc_mcm->getCommonValue($_SESSION['_authsession']['data']['DATA_ID'], "ROUNDING_STATUS", $_POST["ROUND_TYPE"]);
		if(is_null($l_round_type) && !is_null($_POST["ROUND_TYPE"])){$l_round_type = $_POST["ROUND_TYPE"];}
		
		$l_html = "※実働時間、及び残業時間は、"
		         .$l_base_time
		         ."分単位に"
		         .$l_round_type
		         ."処理を行っています。";
		
	}
	$l_html = null;
	
	// データ検索
	require_once('../mdl/m_attendance_sheet.php');
	$mduty = new m_attendance_sheet();
	$mduty->attendance_sheet_defaultlist($w_attendance_sheet, $_POST);
	
	$workusername	=	$_POST["WORK_USER_NAME"];
	$workuser		=	$w_attendance_sheet[1]["WORK_USER_NAME"];
	$workdate_ym	=	$w_attendance_sheet[1]["WORK_DATE_YM"];
	$workdate		=	$w_attendance_sheet[1]["WORK_DATE"];
	$workname		=	$w_attendance_sheet[1]["WORK_NAME"];
	$workusername	=	$w_attendance_sheet[1]["WORK_USER_NAME"];
	//	echo "post".$workusername."<BR>";
	//	echo "取得".$workuser."<BR>";
	//	print_r($w_attendance_sheet);
	
	// PDF出力用TABLE作成
	//勤務表を表示
	$rcnt	= 0;
	$dtltab_item_cnt	= 0;
	
	//項目名
	$rcnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_work_date"							,INPUT_TYPE_DISP	,"作業日"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_work_name"							,INPUT_TYPE_DISP	,"作業名"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_entering_timet"						,INPUT_TYPE_DISP	,"開始時間"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_leave_timet"						,INPUT_TYPE_DISP	,"終了時間"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_break_time"							,INPUT_TYPE_DISP	,"休憩時間"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_actual_working_hours"				,INPUT_TYPE_DISP	,"実働時間"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_overtime_hours"						,INPUT_TYPE_DISP	,"残業時間"		,"#666670"	,"#ffffff"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_work_content_details"				,INPUT_TYPE_DISP	,"作業内容詳細"	,"#666670"	,"#ffffff"	,INPUT_WIDTH_S		,INPUT_ALIGN_CENTER);
	$dtltab_item_cnt++;
	$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_remarks"							,INPUT_TYPE_DISP	,"備考"			,"#666670"	,"#ffffff"	,INPUT_WIDTH_S		,INPUT_ALIGN_CENTER);
	
	// 作業日を元に月末日を取得
	$dt_year	=	date("Y", strtotime($workdate));
	$dt_month	=	date("m", strtotime($workdate));
	
	require_once('../lib/CommonDate.php');
	$commond = new CommonDate();
	// 基準時間、丸め方法のセット
	if(!is_null($_POST["BASE_TIME"])){
		$commond->setBaseTime($_POST["BASE_TIME"]);
	}
	if(!is_null($_POST["ROUND_TYPE"])){
		$commond->setRoundType($_POST["ROUND_TYPE"]);
	}
	
	//月初日取得
	$first_of_month	= $commond->getMonthfirstDay($dt_year,$dt_month);
	$from_year	=	date("Y", strtotime($first_of_month));
	$from_month	=	date("m", strtotime($first_of_month));
	$from_day	=	date("d", strtotime($first_of_month));
	
	//月末日取得
	$end_of_month	= $commond->getMonthEndDay($dt_year,$dt_month);
	$to_year	=	date("Y", strtotime($end_of_month));
	$to_month	=	date("m", strtotime($end_of_month));
	$to_day		=	date("d", strtotime($end_of_month));
	
	//日数取得
	$getdays	= $commond->compareDate(GETMODE_DAY_COUNT,$to_year,$to_month,$to_day,$from_year,$from_month,$from_day);
	
	//echo "基準：".$_POST["WORK_DATE"]."<BR>";
	//echo "月初：".$first_of_month."<BR>";
	//echo "月末：".$end_of_month."<BR>";
	//echo "日数：".$getdays."<BR>";
	
	$irow					=	1;
	$week					=	array("日", "月", "火", "水", "木", "金", "土");
	$total_break_time		=	null;
	$total_working_hours	=	null;
	$total_overtime_hours	=	null;
	
	//月初日から月末日までをループ出力
	for($i=0;$i<=$getdays;$i++){
		$rcnt++;
		$dtltab_details_cnt	= 0;
		$l_display_working_hours = "";
		$l_display_overwork_hours = "";
		
		
		//日付を設定
		$w_workdate		=	date("Y-m-d", mktime(0, 0, 0, $from_month, $from_day + $i, $from_year));
		$dayOfWeek		=	date("w", strtotime($w_workdate));
		
		$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_work_date"								,INPUT_TYPE_NUM			,date("n月j日",strtotime($w_workdate))."({$week[$dayOfWeek]})");
		
		//作業日が一致の場合はデータ表示
		if($w_workdate == $w_attendance_sheet[$irow]["WORK_DATE"]){
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_work_name"							,INPUT_TYPE_DISP		,$w_attendance_sheet[$irow]["WORK_NAME"]);
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_entering_timet"						,INPUT_TYPE_DISP		,$w_attendance_sheet[$irow]["ENTERING_TIMET"]);
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_leave_timet"						,INPUT_TYPE_DISP		,$w_attendance_sheet[$irow]["LEAVE_TIMET"]);
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_break_time"							,INPUT_TYPE_NUM			,$w_attendance_sheet[$irow]["DISP_BREAK_TIME"]);

			// 実動時間は丸め処理を行ってから出力する
			$l_display_working_hours = $commond->getRoundedTime($w_attendance_sheet[$irow]["ENTERING_TIMET"], $w_attendance_sheet[$irow]["LEAVE_TIMET"], $w_attendance_sheet[$irow]["DISP_BREAK_TIME"]);
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_actual_working_hours"				,INPUT_TYPE_NUM			,sprintf("%4.2f", $l_display_working_hours));

			// 残業時間を算出
			$l_display_overwork_hours = $commond->getOWTime($w_attendance_sheet[$irow]["ENTERING_TIMET"], $w_attendance_sheet[$irow]["LEAVE_TIMET"], $w_attendance_sheet[$irow]["DISP_BREAK_TIME"], $w_attendance_sheet[$irow]["DEFAULT_WORKING_TIME"]);
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_overtime_hours"						,INPUT_TYPE_NUM			,sprintf("%4.2f", $l_display_overwork_hours));

			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_work_content_details"				,INPUT_TYPE_DISP		,$w_attendance_sheet[$irow]["WORK_CONTENT_DETAILS"]);
			$dtltab_details_cnt++;
			$remarks	= null;
			//	$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_remarks"							,INPUT_TYPE_DISP		,$w_attendance_sheet[$irow]["APPROVAL_DIVISION"].$w_attendance_sheet[$irow]["CANCEL_DIVISION"]);
			//承認区分がAP（承諾）以外は備考欄に出力
			if($w_attendance_sheet[$irow]["APPROVAL_DIVISION"] <> "AP"){
				$remarks	=	$w_attendance_sheet[$irow]["APPROVAL_DIVISION_NAME"];
			}
			//キャンセル区分がWC（作業依頼）以外は備考欄に出力
			if($w_attendance_sheet[$irow]["CANCEL_DIVISION"] <> "WR"){
				if(is_null($remarks)){
					$remarks	.=	$w_attendance_sheet[$irow]["CANCEL_DIVISION_NAME"];
				} else {
					$remarks	.=	",".$w_attendance_sheet[$irow]["CANCEL_DIVISION_NAME"];
				}
			}
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_remarks"							,INPUT_TYPE_DISP		,$remarks);
			
			// 合計値の計算
			$total_break_time		=	$total_break_time		+	$w_attendance_sheet[$irow]["DISP_BREAK_TIME"];
			$total_working_hours	=	$total_working_hours	+	$l_display_working_hours;
			$total_overtime_hours	=	$total_overtime_hours	+	$l_display_overwork_hours;
			$irow++;
		//作業日が不一致の場合は空白
		} else {
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_work_name"							,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_entering_timet"						,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_leave_timet"						,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_break_time"							,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_actual_working_hours"				,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_overtime_hours"						,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_work_content_details"				,INPUT_TYPE_DISP		,"");
			$dtltab_details_cnt++;
			$ar_dtltab[$rcnt][$dtltab_details_cnt]	= array("tx_remarks"							,INPUT_TYPE_DISP		,"");
		}
	}
	
	// 最後尾に合計を表示
			$rcnt++;
			$dtltab_details_cnt	= 0;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_work_date"									,INPUT_TYPE_DISP		,"計"															,"#666670"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_work_name"									,INPUT_TYPE_DISP		,""																,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_entering_timet"								,INPUT_TYPE_DISP		,""																,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_leave_timet"								,INPUT_TYPE_DISP		,""																,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_CENTER);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_break_time"									,INPUT_TYPE_DISP		,number_format($total_break_time,2)								,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_RIGHT);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_actual_working_hours"						,INPUT_TYPE_DISP		,number_format($total_working_hours,2)							,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_RIGHT);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_overtime_hours"								,INPUT_TYPE_DISP		,number_format($total_overtime_hours,2)							,"#ffffff"	,"#000000"	,INPUT_WIDTH_10		,INPUT_ALIGN_RIGHT);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_work_content_details"						,INPUT_TYPE_DISP		,""																,"#ffffff"	,"#000000"	,INPUT_WIDTH_S		,INPUT_ALIGN_CENTER);
			$dtltab_details_cnt++;
			$ar_pdftab[$rcnt][$dtltab_details_cnt]	= array("tx_remarks"									,INPUT_TYPE_DISP		,""																,"#ffffff"	,"#000000"	,INPUT_WIDTH_S		,INPUT_ALIGN_CENTER);
			
		//	print_r($ar_pdftab);
			
			$l_retdtlval = "";
			
			// PDF出力用TABLE作成
			require_once('../lib/PDFTableSetup.php');
			$dtst = new PDFTableSetup();
			
			$tbl_interval	=	'0';		// 間隔
			$tbl_margins	=	'3';		// 余白
			$tbl_border		=	'5';		// 線の太さ
			$tbl_width		=	'100%';		// %指定
			$tbl_align		=	'center';	// left,right,center
			
			// 明細表作成
			$l_retdtlval .= $dtst->setDtlTab($ar_pdftab,$tbl_interval,$tbl_margins,$tbl_border,$tbl_width,$tbl_align);
			
		//-----------------------------------------------------------------------
			
			//項目名
			$rcnt				= 0;
			$ar_pdftab	= null;
			
			$rcnt++;
			$dtltab_item_cnt	= 0;
			$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("item_company_name"				,INPUT_TYPE_DISP	,"所属会社"											,"#666670"	,"#ffffff"	,INPUT_WIDTH_S	,INPUT_ALIGN_CENTER);
			$dtltab_item_cnt++;
			$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_company_name"				,INPUT_TYPE_DISP	,$w_attendance_sheet[1]["WORK_USER_COMPANY_NAME"]	,"#ffffff"	,"#000000"	,INPUT_WIDTH_L	,INPUT_ALIGN_LEFT);
			$rcnt++;
			$dtltab_item_cnt	= 0;
			$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("item_company_name"				,INPUT_TYPE_DISP	,"従業員氏名"										,"#666670"	,"#ffffff"	,INPUT_WIDTH_S	,INPUT_ALIGN_CENTER);
			$dtltab_item_cnt++;
			$ar_pdftab[$rcnt][$dtltab_item_cnt]			= array("tx_company_name"				,INPUT_TYPE_DISP	,$w_attendance_sheet[1]["WORK_USER_NAME"]			,"#ffffff"	,"#000000"	,INPUT_WIDTH_L	,INPUT_ALIGN_LEFT);
			
			$tbl_interval	=	'0';		// 間隔
			$tbl_margins	=	'5';		// 余白
			$tbl_border		=	'1';		// 線の太さ
			$tbl_width		=	'80%';		// %指定
			$tbl_align		=	'center';	// left,right,center
			
			// 明細表作成
			$l_rethedval .= $dtst->setDtlTab($ar_pdftab,$tbl_interval,$tbl_margins,$tbl_border,$tbl_width,$tbl_align);
			
			
		//-----------------------------------------------------------------------
	//		print_r($w_attendance_sheet);
	//		print_r($ar_pdftab);
	//		echo $l_rethedval;
	//		echo "<BR>";
	//		echo $l_retdtlval;
		//-----------------------------------------------------------------------
			
	/********************************************************
		PDF_OUTPUT
	********************************************************/
			
			/* PDF出力 */
			/* ライブラリをインクルードする(TCPDFをインストールしたパスを指定する) */
			require_once('../tcpdf/config/lang/eng.php');
			require_once('../tcpdf/tcpdf.php');
			
			/* PDF オブジェクトを作成し、以降の処理で操作します */
		//	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
			$pdf = new TCPDF('P', 'mm', 'A4', true); 
			
		/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	SetFontパラメータ																		//
		//////////////////////////////////////////////////////////////////////////////////////////////
			$family	AddFont()で追加したフォント名もしくは以下の標準フォント:
					* times (Times-Roman)
					* timesb (Times-Bol)
					* timesi (Times-Italic)
					* timesbi (Times-BoldItalic)
					* helvetica (Helvetica)
					* helveticab (Helvetica-Bold)
					* helveticai (Helvetica-Oblique)
					* helveticabi (Helvetica-BoldOblique)
					* courier (Courier)
					* courierb (Courier-Bold)
					* courieri (Courier-Oblique)
					* courierbi (Courier-BoldOblique)
					* symbol (Symbol)
					* zapfdingbats (ZapfDingbats)
			''空文字を指定するとこれまで使用していたフォントが使われる。
			$style	フォント・スタイル:
					* 空文字: regular
					* B: ボールド
					* I: イタリック
					* U: アンダーライン
					* D: 取り消し
			もしくは、上記の組み合わせ。既定値は'標準'、また'Symbol'か'ZapfDingbats'フォントを選択した場合、ボールドとイタリックは無効。
			$size	フォントサイズ(ポイント数)、省略時は現在までのフォントサイズ、ドキュメント開始時点では12pt。
			$fontfile	フォント定義ファイルを指定、フォント名とフォントスタイルから規定される名称。
		--------------------------------------------------------------------------------------------*/
			// フォントをセット
			//$pdf->SetFont('arialunicid0', 'B', 18);
			$pdf->SetFont('kozgopromedium', '', 18);
			
		/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	AddPageパラメータ																		//
		//////////////////////////////////////////////////////////////////////////////////////////////
			$orientation	用紙方向 (P or PORTRAIT(縦:既定) | L or LANDSCAPE(横))
			$format	用紙フォーマット、以下のいずれか。
					[ 4A0 | 2A0 | A0 | A1 | A2 | A3 | A4(既定) | A5 | A6 | A7 | A8 | A9 | A10
					| B0 | B1 | B2 | B3 | B4 | B5 | B6 | B7 | B8 | B9 | B10
					| C0 | C1 | C2 | C3 | C4 | C5 | C6 | C7 | C8 | C9 | C10
					| RA0 | RA2 | RA3 | RA4 | SRA0 | SRA1 | SRA2 | SRA3 | SRA4
					| LETTER | LEGAL | EXECUTIVE | FOLIO ]
					またカスタムページサイズの場合はheightとwidthの配列を指定。
		--------------------------------------------------------------------------------------------*/
			/* 1ページ目を準備します */
			$pdf->AddPage();
			/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	Cellパラメータ																			//
		//////////////////////////////////////////////////////////////////////////////////////////////
			$w		矩形領域の幅
			$h		矩形領域の高さ
			$txt	印字するテキスト
			$border	境界線で囲むか否かを指定する。
					* 0: 境界線なし(既定)
					* 1: 枠で囲む
					* L: 左
					* T: 上
					* R: 右
					* B: 下
			$ln	出力後のカーソルの移動方法を指定する:
					* 0: 右へ移動(既定)、但しアラビア語などRTLの場合は左へ移動
					* 1: 次の行へ移動
					* 2: 下へ移動
			$align	テキストの整列を以下のいずれかで指定する
					* L or 空文字: 左揃え(既定)
					* C: 中央揃え
					* R: 右揃え
					* J: 両端揃え
			$fill	矩形領域の塗つぶし指定 [0:透明(既定) 1:塗つぶす]
			$link	登録するリンク先のURL、もしくはAddLink()で作成したドキュメント内でのリンク
			$stretch	テキストの伸縮(ストレッチ)モード:
					* 0 = なし
					* 1 = 必要に応じて水平伸縮
					* 2 = 水平伸縮
					* 3 = 必要に応じてスペース埋め
					* 4 = スペース埋め
			$ignore_min_height	「true」とすると矩形領域の高さの最小値調整をしない
		--------------------------------------------------------------------------------------------*/
			$titlename		=	$dt_year."年".$dt_month."月度　勤務実績表";
			
			/* 文字列を出力します */
			$pdf->Cell(0,0,$titlename,'B',1,'C',0);
			
		/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	Lnパラメータ																			//
		//////////////////////////////////////////////////////////////////////////////////////////////
			$h	改行する高さ、既定では直近で処理したセルの高さ。
			$cell	trueとすると、次の行の左端からcMarginだけカーソルを右に移動する。
		--------------------------------------------------------------------------------------------*/
			/* 改行します */
			$pdf->Ln(3);
			
			// フォントをセット
			//$pdf->SetFont('arialunicid0', 'B', 10);
			$pdf->SetFont('kozgopromedium', '', 10);
			
		/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	writeHTMLパラメータ																		//
		//////////////////////////////////////////////////////////////////////////////////////////////
				$html	出力するHTMLテキスト
				$ln		改行する場合true
				$fill	背景の塗つぶし指定 [0:透明(既定) 1:塗つぶす]
				$reseth	前回の高さ設定をリセットする場合はtrue、引き継ぐ場合はfalse
				$cell	trueとすると各行にcMargin分のスペースを自動挿入する
				$align	テキストの整列を以下のいずれかで指定する
					* L : 左端
					* C : 中央
					* R : 右端
					* '' : 空文字 : 左端(RTLの場合は右端)
		--------------------------------------------------------------------------------------------*/
			// HTML出力
			$pdf->writeHTML($l_rethedval, false, false, false, false, '');
			
			/* 改行します */
			$pdf->Ln(1);
			
			// フォントをセット
			//$pdf->SetFont('arialunicid0', 'B', 8);
			$pdf->SetFont('kozgopromedium', '', 8);
			
			// HTML出力
			$pdf->writeHTML($l_retdtlval, false, false, false, false, '');
			
			/* 改行します */
			$pdf->Ln(1);
			
			// HTML出力
			$pdf->writeHTML($l_html, false, false, false, false, '');
			
			/*--------------------------------------------------------------------------------------------
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	Outputパラメータ																			//
		//////////////////////////////////////////////////////////////////////////////////////////////
			
			$name	保存時のファイル名、特殊文字は適宜'_'(アンダースコア)に置換される。
			$dest	ドキュメントの出力先を指定、以下のいずれかを指定。:
					* I: ブラウザに出力する(既定)、保存時のファイル名が$nameで指定した名前になる。
					* D: ブラウザで(強制的に)ダウンロードする。
					* F: ローカルファイルとして保存する。
					* S: PDFドキュメントの内容を文字列として出力する。
		--------------------------------------------------------------------------------------------*/
			/* PDF を出力します */
			$pdf->Output("work_experience.pdf", "D");
			
	/********************************************************
		PDF_END
	********************************************************/
		
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
	if($l_debug_mode==1){print("Step-Smarty変数セット");print "<br>";}
/*-----------------------------------
	Smartyセット
  -----------------------------------*/
	// ------------------------------
	// クラスインスタンス作成
	// ------------------------------
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir = DIR_TEMPLATES;
	$lc_smarty->compile_dir  = DIR_TEMPLATES_C;
	if($l_debug_mode==1){print("Step-Smartyクラスインスタンス作成");print "<br>";}	
	
	
	// ------------------------------
	// Smartyセット
	// ------------------------------
	$lc_smarty->assign("ar_dm_main"		,$lc_smarty->ar_dm_maintab);
	$lc_smarty->assign("txt_title"		,$lc_smarty->ar_dm_title[OPMODE_PDF]);
	
	print "テスト";
	
	if($l_debug_mode==1){print("Step-隠し項目の設定");print "<br>";}
	
/*-----------------------------------
	ページ表示
  -----------------------------------*/
	$lc_smarty->display('OperateData.tpl');
	if($l_debug_mode==1){print("Step-完了");print "<br>";}

?>