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

require_once('../lib/CommonPageDef.php');
require_once('../lib/CommonStaticValue.php');
require_once('../lib/CommonFunctions.php');
class PageDef extends CommonPageDef{
// *****************************************************************************
// クラス名：PageDef
// 処理概要：帳票出力画面の定義
// *****************************************************************************
// ※変数はCommonPageDefクラスで一元管理します
// =============================================================================
// コンストラクタ
// 引数:
//			$p_mode				起動モード
// =============================================================================
	function __construct($p_start_mode){
		// 継承元のコンストラクタを起動
		CommonPageDef::__construct();
		
	//--------------------------------------------------
	// 起動モード
	// 		STMODE_NOMAL	通常
	//		STMODE_RONLY	読み込み専用
	//--------------------------------------------------
		$this->start_mode = $p_start_mode;
		
	//--------------------------------------------------
	// セッション開始
	//--------------------------------------------------
		session_start();
		
	//--------------------------------------------------
	// 権限情報
	// $authority = AUTH_ADMI	システム管理者
	// $authority = AUTH_MANG	管理者
	// $authority = AUTH_GEN1	社員（MANAGEMENT）
	// $authority = AUTH_GEN2	社員（FEG）
	// $authority = AUTH_GEN3	社員（OTHERS）
	// $authority = AUTH_GENE	一般
	// $authority = AUTH_MOGE	Mobileユーザ
	// $authority = AUTH_PCGE	PCユーザ
	// $authority = AUTH_ISOL	権限無
	//--------------------------------------------------
		$authority	=	$_SESSION['_authsession']['data']['AUTHORITY_CODE'];
		
	//--------------------------------------------------
	// 見出しタイトル
	//--------------------------------------------------
		$this->taitle_name							= "帳票出力";
		
	//--------------------------------------------------
	// ログイン名
	//--------------------------------------------------
		$this->l_name								= preg_replace("/　/", "", preg_replace("/ /", "", $_SESSION['_authsession']['data']['NAME']))." 様";
		
	//--------------------------------------------------
	// リンク項目
	//--------------------------------------------------
		$this->link_value							= "ログアウト";
		
	//--------------------------------------------------
	// キー値用配列に画面名を追加
	// 親画面のヘッドラインに親の親から引き継いだ項目がある場合は、
	// その項目、隠し項目のIDをセットしておかないと、
	// パンくずリンクから前の画面に戻った時に正常に表示されません
	//--------------------------------------------------
		// ページ名
		$this->ar_keyvalue[HDITEM_PAGE_NAME]		= "ledger_sheet";
		
		// データID
		$this->ar_keyvalue["hd_dataid"]				= $_SESSION['_authsession']['data']['DATA_ID'];
		
		// ユーザID
		$this->ar_keyvalue["hd_loginuserid"]		= $_SESSION['_authsession']['data']['USER_ID'];
		
	//--------------------------------------------------
	// 見出し文字列
	//--------------------------------------------------
		$this->title_text							= "帳票出力";
		
	//--------------------------------------------------
	// パンくずリンク用配列
	//--------------------------------------------------
		$this->ar_bclink["TOP"]						= "portalsite.php";
		$this->ar_bclink["帳票出力"]				= "ledger_sheet.php";
		
	//--------------------------------------------------
	// ヘッドライン項目用配列
	// colname:	DBのカラム名(検索時に対象となるカラム名)
	// name:	HTMLのnameにセットする値
	// caption:	項目の見出し
	// value:	値
	// type:	項目タイプ(text,disp,hidden)
	// width:	項目の幅
	// list:	ondblclick時に呼び出すリスト
	//--------------------------------------------------
		// ヘッドライン項目値用配列
		$hdlvalue_cnt	= 0;
		
		//作業日
		$hdlvalue_cnt++;
		$this->ar_hdlvalue[$hdlvalue_cnt][COLKEY_NAME]			= "WORK_DATE";
		$this->ar_hdlvalue[$hdlvalue_cnt][COLKEY_VALUE]			= date("Y-m");
		$this->ar_hdlvalue[$hdlvalue_cnt][COLKEY_TYPE]			= INPUT_TYPE_HIDDEN;
				
	//--------------------------------------------------
	// メニュー
	// 	value:	HTMLのvalueにセットする値
	// 	name:	HTMLのname,idにセットする値
	// 	type:	HTMLのtypeにセットする値
	// 	action:	HTMLのonClickで実行するスクリプト
	//--------------------------------------------------
		$menuset_cnt	= 0;
		if ($authority == AUTH_ADMI || $authority == AUTH_MANG || $authority == AUTH_GEN1 || $authority == AUTH_GEN2 || $authority == AUTH_GENE) {
			
			// 勤務表
			$menuset_cnt++;
			$this->ar_menuset[$menuset_cnt][FORM_PARAM_VALUE]		= "勤務表";
			$this->ar_menuset[$menuset_cnt][FORM_PARAM_NAME]		= MENUITEM_ATTENDANCESHEET;
			$this->ar_menuset[$menuset_cnt][FORM_PARAM_TYPE]		= INPUT_TYPE_BUTTON;
			$this->ar_menuset[$menuset_cnt][FORM_PARAM_ACTION]		= jsMovePage("attendance_sheet.php");
			
		}
	}
// =============================================================================
// 自由表示部
// フッター部に合計値等特殊な値を表示する場合は、このファンクションに記述する
// TABLEを使用する場合は変数定義にあるCSSクラスが使用できる
// 直接echoせずに、HTMLの文字列をreturnする
// =============================================================================
	function setExtension(){
		return true;
	}
}
?>