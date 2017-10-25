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
	set_include_path(
		get_include_path() . PATH_SEPARATOR .
		dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . PATH_SEPARATOR .
		dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'pear');

	require_once('../lib/IndividualStaticValue.php');
	require_once('../lib/CommonImageValue.php');
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
// *****************************************************************************
// ファイル名：CommonStaticValue
// 処理概要  ：共通で使用する定数の定義
// *****************************************************************************
// =============================================================================
// システム名
// =============================================================================
	define("SYSTEM_NAME",				"PremierZ");							// システム名
	
// =============================================================================
// FORM系
// =============================================================================
	define("FORM_PARAM_NAME",			"name");						// name
	define("FORM_PARAM_TYPE",			"type");						// type
	define("FORM_PARAM_ACTION",			"action");						// action
	define("FORM_PARAM_VALUE",			"value");						// value
	define("FORM_PARAM_ONCLICK",		"onclick");						// onclick
	define("FORM_PARAM_ONKEYUP",		"onKeyUp");						// onKeyUp
	define("FORM_PARAM_CLASS",			"class");						// class
	define("FORM_PARAM_ID",				"id");							// id
	define("FORM_PARAM_CAPTION",		"caption");						// caption(タグ間で表示するテキスト)
	define("FORM_PARAM_DISABLED",		"disabled");					// 無効化
	define("FORM_PARAM_SRC",			"src");							// src
	define("FORM_PARAM_ALT",			"alt");							// alt
	
// =============================================================================
// INPUT系
// =============================================================================
	define("INPUT_TYPE_TEXT",			"text");						// 編集可能なテキスト
	define("INPUT_TYPE_TEXTAREA",		"textarea");					// 編集可能なテキストエリア
	define("INPUT_TYPE_PASSWORD",		"password");					// パスワード
	define("INPUT_TYPE_NUM",			"text");						// 数値型
	define("INPUT_TYPE_TEXTNUM",		"num");							// 数値型（更新不可）
	define("INPUT_TYPE_DISP",			"disp");						// 編集不可なテキスト
	define("INPUT_TYPE_HIDDEN",			"hidden");						// 隠し項目
	define("INPUT_TYPE_BUTTON",			"button");						// ボタン
	define("INPUT_TYPE_SUBMIT",			"submit");						// 送信ボタン
	define("INPUT_TYPE_RESET",			"reset");						// リセットボタン
	define("INPUT_TYPE_CHKBOX",			"checkbox");					// チェックボックス
	define("INPUT_TYPE_RADIO",			"radio");						// ラジオボタン
	define("INPUT_TYPE_COMBO",			"combo");						// コンボボックス
	define("INPUT_TYPE_LIST",			"list");						// リスト
	define("INPUT_TYPE_CALENDAR",		"calendar");					// カレンダー
	define("INPUT_TYPE_PULLDOWN",		"pulldown");					// プルダウン
	define("INPUT_TYPE_FILE",			"file");						// ファイル
	define("INPUT_TYPE_IMAGE",			"image");						// イメージ
	define("INPUT_VALUE_AMOUNT",		"合計");						// 合計
	define("INPUT_TYPE_COMMENT",		"comment");						// コメント
	
// =============================================================================
// ヘッドライン項目系
// ※配列作成の時にキーになる値
// =============================================================================
	define("COLKEY_COLNAME",			"colname");						// 検索対象となるカラム値のキー値
	define("COLKEY_NAME",				"name");						// HTMLのnameにセットする値のキー値
	define("COLKEY_CAPTION",			"caption");						// 項目の見出しのキー値
	define("COLKEY_VALUE",				"value");						// 値のキー値
	define("COLKEY_ISTYLE",				"istyle");						// 文字指定(DoCoMo、au)
	define("COLKEY_MODE",				"mode");						// 文字指定(SoftBank)
	define("COLKEY_SELECTED",			"selected");					// 初期項目
	define("COLKEY_CHECKED",			"checked");						// 初期項目
	define("COLKEY_ITEMNAME",			"itemname");					// プルダウンの項目名
	define("COLKEY_TYPE",				"type");						// 項目タイプのキー値
	define("COLKEY_KEY",				"key");							// アクセスキー値
	define("COLKEY_TITLE",				"title");						// タイトル（入力例）
	define("COLKEY_WIDTH",				"width");						// 項目幅のキー値
	define("COLKEY_LIST",				"list");						// ondblclick時に呼び出すリストのキー値
	define("COLKEY_JUDGE",				"judge");						// 判定
	define("COLKEY_RETURN",				"RETURN");						// 改行指示とみなすnameの値
	define("COLKEY_DISABLED",			"disabled");					// 無効化
	define("COLKEY_SEPARATOR",			"SEPARATOR");					// formを同一ページに複数使用する時に使用する区切り（仕切りあり）
	define("COLKEY_SEPARATE",			"SEPARATE");					// formを同一ページに複数使用する時に使用する区切り（仕切りなし）
	
// =============================================================================
// ヘッドライン項目系
// ※配列作成の時にキーになる値
// =============================================================================
	define("LINK_URL",					"link_url");					// 検索対象となるカラム値のキー値
	define("HYPER_LINK",				"hyperlink");					// ハイパーリンク
	define("LINK_VALUE",				"value");						// 値のキー値
	define("LINK_RETURN",				"RETURN");						// 改行指示とみなすnameの値
	define("LINK_KEY",					"key");							// アクセスキー値
	
// =============================================================================
// ディレクトリ
// =============================================================================
	define("DIR_CACHE",					"../cache/");
	define("DIR_CONFIGS",				"../configs/");
	define("DIR_CSS",					"../css/");
	define("DIR_CTL",					"../ctl/");
	define("DIR_IMG",					"../img/");
	define("DIR_JS",					"../js/");
	define("DIR_DATA",					"../js/data");
	define("DIR_JSON",					"../js/json/");
	define("DIR_LIB",					"../lib/");
	define("DIR_MDL",					"../mdl/");
	define("DIR_MOBILE",				"../mobile/");
	define("DIR_PAGE",					"../page/");
	define("DIR_SMARTY",				"../Smarty/");
	define("DIR_TEMPLATES",				"../templates/");
	define("DIR_TEMPLATES_C",			"../templates_c/");
	define("DIR_TCPDF",					"../tcpdf/");
	define("DIR_MAN",					"../manual/");
// =============================================================================
// ファイル名
// =============================================================================
	define("FILE_PC_PRESET",			"page/passwordReset.php");			//PC用パスワードリセット
	define("FILE_MOBILE_PRESET",		"mobile/mobilepasswordreset.php");	//モバイル用パスワードリセット
	
// =============================================================================
// フォーム名
// =============================================================================
	define("FMNAME_BCLINK",				"fm_bclink");					// パンくずリンク部のFORM名
	define("FMNAME_SEARCH",				"fm_search");					// ヘッドライン項目、メニュー部のFORM名
	define("FMNAME_DTLTAB",				"fm_dtltab");					// 明細表部のFORM名
	define("FMNAME_DTLAMTTAB",			"fm_dtl_amt_tab");				// 明細表部の合計部FORM名
	
// =============================================================================
// フォームアクション
// =============================================================================
	define("FMACT_POST",				"POST");						// POST
	define("FMACT_GET",					"GET");							// GET
	
// =============================================================================
// 隠し項目名
// =============================================================================
	define("HDITEM_DATAID",				"hd_dataid");					// 隠し項目データID
	define("HDITEM_LOGINUSERID",		"hd_loginuserid");				// 隠し項目ユーザID
	define("HDITEM_DELETE_TARGET",		"hd_delete_target");			// 隠し項目削除対象ID
	define("HDITEM_SEND_TARGET",		"hd_batchsend_target");			// 隠し項目一括メール送信対象ID
	define("HDITEM_DELETE_CHECK",		"hd_delete_check");				// 削除済みチェックの状態
	define("HDITEM_PAGE_NAME",			"hd_page_name");				// 隠し項目ページ名
	define("HDITEM_SESSID",				"hd_sessid");					// 隠し項目セッションID
	define("HDITEM_RESERV1",			"hd_reserv1_id");				// 隠し項目予備1
	define("HDITEM_RESERV2",			"hd_reserv2_id");				// 隠し項目予備2
	define("HDITEM_RESERV3",			"hd_reserv3_id");				// 隠し項目予備3
	define("HDITEM_RESERV4",			"hd_reserv4_id");				// 隠し項目予備4
	
// =============================================================================
// メニュー項目名
// =============================================================================
	define("MENUITEM_DISPDEL",			"ck_displaydelete");			// 削除済表示
	define("MENUITEM_LOGIN",			"bt_login");					// ログイン
	define("MENUITEM_LOGOUT",			"bt_logout");					// ログアウト
	define("MENUITEM_CLEAR",			"bt_clear");					// クリア
	define("MENUITEM_SEARCH",			"bt_search");					// 検索
	define("MENUITEM_DELETE",			"bt_delete");					// 削除
	define("MENUITEM_UPDATE",			"bt_update");					// 更新
	define("MENUITEM_LEDGERSHEET",		"bt_ledgersheet");				// 帳票
	define("MENUITEM_ATTENDANCESHEET",	"bt_attendancesheet");			// 勤務表
	define("MENUITEM_COMPANIES",		"bt_companies");				// 会社管理
	define("MENUITEM_ESTIMATES",		"bt_estimates");				// 作業管理
	define("MENUITEM_SITUATION",		"bt_situation");				// 作業状況
	define("MENUITEM_SETUP",			"bt_setup");					// 設定情報
	define("MENUITEM_SEND",				"bt_send");						// 送信
	define("MENUITEM_BATCHSEND",		"bt_batchsend");				// 一括メール送信
	define("MENUITEM_MAINTENANCE",		"bt_maintenance");				// 管理画面
	define("MENUITEM_MANUAL",			"bt_manual");					// マニュアル
	define("MENUITEM_PDF",				"bt_pdf");						// PDF
	define("MENUITEM_EXCEL",			"bt_excel");					// EXCEL
	
// =============================================================================
// オペレーションモード
// =============================================================================
	define("OPMODE_INSERT",				"insert");						// 新規登録
	define("OPMODE_UPDATE",				"update");						// 更新
	define("OPMODE_DELETE",				"delete");						// 削除
	define("OPMODE_SEND",				"send");						// メール送信
	define("OPMODE_BATCHSEND",			"batchsend");					// 一括メール送信
	define("OPMODE_USERSINFO",			"usersinfo");					// ログイン情報通知
	define("OPMODE_BATCHUSERSINFO",		"batchusersinfo");				// ログイン情報一括通知
	define("OPMODE_PDF",				"phppdf");						// PDF出力
	define("OPMODE_EXCEL",				"phpexcel");					// EXCEL出力
	
// =============================================================================
// 起動モード
// =============================================================================
	define("STMODE_NOMAL",				"nomal");						// 通常
	define("STMODE_RONLY",				"ronly");						// 読み込み専用
	define("STMODE_MOBILE",				"mobile");						// モバイル端末
	
// =============================================================================
// 取得モード
// =============================================================================
	define("GETMODE_DAY_COUNT",			"day_count");					// 日数取得
	define("GETMODE_BUSINESS_DAY",		"business_day");				// 日付取得
	
// =============================================================================
// 表示モード
// =============================================================================
	define("DISPMODE_NOMAL",			"nomal");						// 通常
	define("DISPMODE_SEARCH",			"search");						// 検索
	
// =============================================================================
// INPUT項目の幅
// =============================================================================
	define("INPUT_WIDTH_N",				"0");							// 隠し項目用
	define("INPUT_WIDTH_10",			"10");							// 
	define("INPUT_WIDTH_S",				"20");							// 小項目用
	define("INPUT_WIDTH_30",			"30");							// 
	define("INPUT_WIDTH_M",				"40");							// 中項目用
	define("INPUT_WIDTH_50",			"50");							// 
	define("INPUT_WIDTH_L",				"60");							// 大項目用
	define("INPUT_WIDTH_70",			"70");							// 
	define("INPUT_WIDTH_80",			"80");							// 
	define("INPUT_WIDTH_90",			"90");							// 
	define("INPUT_WIDTH_100",			"100");							// 
	
// =============================================================================
// カラムタイプ
// =============================================================================
	define("COLUMN_TYPE_BIGINT",		"bigint");
	define("COLUMN_TYPE_DATE",			"date");
	define("COLUMN_TYPE_DATETIME",		"datetime");
	define("COLUMN_TYPE_INTEGER",		"integer");
	define("COLUMN_TYPE_VARCHAR",		"varchar");
	
// =============================================================================
// 子画面のボタンname
// =============================================================================
	define("CLD_BTN_INSERT",			"bt_insert");					// 新規登録
	define("CLD_BTN_UPDATE",			"bt_update");					// 更新
	define("CLD_BTN_INVALID",			"bt_invalid");					// 論理削除
	define("CLD_BTN_DELTE",				"bt_delete");					// 物理削除
	define("CLD_BTN_RELOAD",			"bt_reload");					// 再読み込み
	define("CLD_BTN_SEND",				"bt_send");						// 送信
	define("CLD_BTN_BATCHSEND",			"bt_batchsend");				// 一括メール送信
	define("CLD_BTN_USERSINFO",			"bt_usersinfo");				// ユーザ情報
	define("CLD_BTN_BATCHUSERSINFO",	"bt_batchusersinfo");			// ユーザ情報一括送信
	define("CLD_BTN_CLOSE",				"bt_close");					// 閉じる
	define("CLD_BTN_RESET",				"bt_reset");					// リセット
	define("CLD_BTN_COPY",				"bt_copy");						// コピー
	
// =============================================================================
// メッセージ
// =============================================================================
	define("MESSAGE_DELETE",			"■□■□以下のデータを削除します。■□■□");					// データ削除時のヘッダ表示
	define("MESSAGE_BATCHSEND",			"■□■□以下の作業者にメールを送信します。■□■□");			// 一括メール送信時のヘッダ表示
	define("MESSAGE_CANNOTBEREAD",		"ご使用の端末情報を読み取る事が出来ませんでした。");			// 端末情報読み取りエラー時表示
	define("MESSAGE_MISMATCH_LOGIN",	"入力されたユーザコードまたはパスワードが正しくありません。");	// 不一致表示メッセージ
	define("MESSAGE_RE_LOGIN",			"ログイン情報を再入力してください。");							// 再ログインメッセージ
	
// =============================================================================
// メール系
// =============================================================================
	define("INFORMATION_NAME",			"各ユーザ名");
	define("INFORMATION_USER_CODE",		"各ユーザ－ユーザコード");
	define("INFORMATION_PASSWORD",		"各ユーザ－パスワード");
	define("INFORMATION_UNIT_PRICE",	"各ユーザ－作業費");
	
// =============================================================================
// リスト
// =============================================================================
	define("LIST_TYPE_VALUE", 			"vlist");						// 普通の値リスト
	define("LIST_TYPE_CALENDAR", 		"calendar");					// カレンダー
	
// =============================================================================
// POSTしている項目数
// =============================================================================
	define("NUMBER_OF_POST", 			"63");							// 項目数
	
// =============================================================================
// PASSWORD指定
// =============================================================================
	define("PASS_NUMCHARACTERS", 		"8");							// パスワード文字数
	define("PASS_SMALL", 				"small");						// 小文字英字
	define("PASS_LARGE", 				"large");						// 大文字英字
	define("PASS_SMALLALNUM", 			"smallalnum");					// 小文字英数字
	define("PASS_LARGEALNUM", 			"largealnum");					// 大文字英数字
	define("PASS_NUM", 					"num");							// 数字
	define("PASS_SIG", 					"sig");							// 記号
	define("PASS_ALPHABET", 			"alphabet");					// 大小文字英字
	define("PASS_ALNUM", 				"alnum");						// 大小文字英数字
	define("PASS_ALSIG", 				"alsig");						// 大小文字英字
	define("PASS_ALNUMSIG", 			"alnumsig");					// 大小文字英数字記号
	define("SESSION_NUMTOKEN", 			"32");							// トークン文字数
	define("DEFAULT_PASSWORD", 			"test");						// ユーザー作成時のデフォルトパスワード
	
// =============================================================================
// 権限コード
// =============================================================================
	define("AUTH_SADM",					"SYSADMIN");					// システム管理者
	define("AUTH_ADMI",					"ADMIN");						// 管理者
	define("AUTH_GEN1",					"GENERAL1");					// 特権一般ユーザー
	define("AUTH_GEN2",					"GENERAL2");					// 特権一般ユーザー
	define("AUTH_GEN3",					"GENERAL3");					// 特権一般ユーザー
	define("AUTH_GEN4",					"GENERAL4");					// 特権一般ユーザー
	define("AUTH_GENE",					"GENERAL");						// 一般ユーザー
	define("AUTH_MOGE",					"MO_GUEST");					// モバイル用ゲスト
	define("AUTH_PCGE",					"PC_GUEST");					// PC用ゲスト
	define("AUTH_ISOL",					"ISOLATED");					// 権限無
	
// =============================================================================
// 画面名
// =============================================================================
	define("SCREEN_ZSMPC001",			"ログイン");
	define("SCREEN_ZSMPC002",			"ログアウト");
	define("SCREEN_ZSMPC003",			"TOP");
	define("SCREEN_ZSMP0010",			"設定情報");
	define("SCREEN_ZSMP0011",			"設定情報 >> 更新");
	define("SCREEN_ZSMP0020",			"会社管理");
	define("SCREEN_ZSMP0021",			"会社管理 >> 検索");
	define("SCREEN_ZSMP0022",			"会社管理 >> 新規登録");
	define("SCREEN_ZSMP0023",			"会社管理 >> 更新");
	define("SCREEN_ZSMP0024",			"会社管理 >> 削除");
	define("SCREEN_ZSMP0030",			"グループ管理");
	define("SCREEN_ZSMP0031",			"グループ管理 >> 検索");
	define("SCREEN_ZSMP0032",			"グループ管理 >> 新規登録");
	define("SCREEN_ZSMP0033",			"グループ管理 >> 更新");
	define("SCREEN_ZSMP0034",			"グループ管理 >> 削除");
	define("SCREEN_ZSMP0040",			"ユーザ管理");
	define("SCREEN_ZSMP0041",			"ユーザ管理 >> 検索");
	define("SCREEN_ZSMP0042",			"ユーザ管理 >> 新規登録");
	define("SCREEN_ZSMP0043",			"ユーザ管理 >> 更新");
	define("SCREEN_ZSMP0044",			"ユーザ管理 >> 削除");
	define("SCREEN_ZSMP0050",			"拠点管理");
	define("SCREEN_ZSMP0051",			"拠点管理 >> 検索");
	define("SCREEN_ZSMP0052",			"拠点管理 >> 新規登録");
	define("SCREEN_ZSMP0053",			"拠点管理 >> 更新");
	define("SCREEN_ZSMP0054",			"拠点管理 >> 削除");
	define("SCREEN_ZSMP0060",			"作業管理");
	define("SCREEN_ZSMP0061",			"作業管理 >> 検索");
	define("SCREEN_ZSMP0062",			"作業管理 >> 新規登録");
	define("SCREEN_ZSMP0063",			"作業管理 >> 更新");
	define("SCREEN_ZSMP0064",			"作業管理 >> 削除");
	define("SCREEN_ZSMP0070",			"作業内容管理");
	define("SCREEN_ZSMP0071",			"作業内容管理 >> 検索");
	define("SCREEN_ZSMP0072",			"作業内容管理 >> 新規登録");
	define("SCREEN_ZSMP0073",			"作業内容管理 >> 更新");
	define("SCREEN_ZSMP0074",			"作業内容管理 >> 削除");
	define("SCREEN_ZSMP0080",			"作業人員管理");
	define("SCREEN_ZSMP0081",			"作業人員管理 >> 検索");
	define("SCREEN_ZSMP0082",			"作業人員管理 >> 新規登録");
	define("SCREEN_ZSMP0083",			"作業人員管理 >> 更新");
	define("SCREEN_ZSMP0084",			"作業人員管理 >> 削除");
	define("SCREEN_ZSMP0085",			"作業人員管理 >> メール一括送信");
	define("SCREEN_ZSMP0086",			"作業人員管理 >> メール送信");
	define("SCREEN_ZSMP0090",			"作業状況管理");
	define("SCREEN_ZSMP0091",			"作業状況管理 >> 検索");
	define("SCREEN_ZSMP0092",			"作業状況管理 >> 更新");
	define("SCREEN_ZSMMC001",			"ログイン");
	define("SCREEN_ZSMMC002",			"ログアウト");
	define("SCREEN_ZSMMC003",			"お問い合せ");
	define("SCREEN_ZSMM0010",			"設定情報");
	define("SCREEN_ZSMM0020",			"TOP");
	define("SCREEN_ZSMM0030",			"作業詳細");
	define("SCREEN_ZSMM0040",			"作業状況確認");
	define("SCREEN_ZSMM0050",			"作業状況詳細");
	define("SCREEN_ZSMM0060",			"作業完了一覧");
	define("SCREEN_ZSMM0070",			"作業完了詳細");
	define("SCREEN_ZSMM0071",			"補足/修正送信フォーム");
	define("SCREEN_ZSMM0072",			"送信完了");
	define("SCREEN_ZSMMC999",			"操作マニュアル");
	
// =============================================================================
// 画面コード
// =============================================================================
	define("PAGECODE_PROJECTS",			"PROJECTS");			// プロジェクト管理
	define("PAGECODE_WORKSTAT",			"WORKSTAT");			// 作業状況
	define("PAGECODE_WORKREPORT",		"WORKREPORT");			// 作業報告
	define("PAGECODE_COMPANY",			"COMPANY");				// 会社管理
	define("PAGECODE_BASE",				"BASE");				// 作業拠点管理
	define("PAGECODE_GROUP",			"GROUP");				// グループ管理
	define("PAGECODE_USER",				"USER");				// ユーザー管理
	define("PAGECODE_LIST",				"LIST");				// 帳票出力
	define("PAGECODE_USERSETTING",		"USERSETTING");			// ユーザー情報
	define("PAGECODE_MANUAL",			"MANUAL");				// マニュアル

	
// =============================================================================
// m_common_master
// =============================================================================
	define("GET_ITEM_VALUE",			"VALUE");						// コード値を示す文字列
	define("GET_ITEM_NAME",				"NAME");						// コード名を示す文字列
	define("GET_ITEM_VALUE_REC",		"VALUE_REC");					// コード値レコードを示す文字列

// =============================================================================
// リターンコード
// =============================================================================
	define("RETURN_NOMAL",				"0");							// リターンコード正常
	define("RETURN_ERROR",				"2");							// リターンコード異常
	
// =============================================================================
// Mobile入力モード設定
// =============================================================================
	// DoCoMo
	define("INPUT_ISTYLE_HIRAGANA",		"1");							// 全角かな
	define("INPUT_ISTYLE_HANKAKUKANA",	"2");							// 半角カナ
	define("INPUT_ISTYLE_ALPHABET",		"3");							// 半角英字
	define("INPUT_ISTYLE_NUMERIC",		"4");							// 半角数字
	// SoftBank
	define("INPUT_MODE_HIRAGANA",		"hiragana");					// 全角かな
	define("INPUT_MODE_KATAKANA",		"katakana");					// 全角カナ
	define("INPUT_MODE_HANKAKUKANA",	"hankakukana");					// 半角カナ
	define("INPUT_MODE_ALPHABET",		"alphabet");					// 半角英字
	define("INPUT_MODE_NUMERIC",		"numeric");						// 半角数字
	// au
	define("INPUT_FORMAT_HIRAGANA",		"*M");							// 全角かな
	define("INPUT_FORMAT_ZEN_ALPHABET",	"*m");							// 全角英字
	define("INPUT_FORMAT_LALPHABET",	"*A");							// 半角英字（大文字）
	define("INPUT_FORMAT_SALPHABET",	"*a");							// 半角英字（小文字）
	define("INPUT_FORMAT_LALPHA_NUM",	"*X");							// 半角英数（大文字）
	define("INPUT_FORMAT_SALPHA_NUM",	"*x");							// 半角英数（小文字）
	define("INPUT_FORMAT_NUMERIC",		"*N");							// 半角数字

// =============================================================================
// 文字コード
// =============================================================================
	define("ENCODE_TYPE",				"UTF-8");

// =============================================================================
// Mobile入力モード設定
// =============================================================================
	define("INPUT_ALIGN_LEFT",			"left");						// 左寄せ
	define("INPUT_ALIGN_CENTER",		"center");						// 中央寄せ
	define("INPUT_ALIGN_RIGHT",			"right");						// 右寄せ
	
// =============================================================================
// 新規登録用作業ステータス
// =============================================================================
	define("INPUT_WORK_STATUS",			"NW");							// 新規登録デフォルト表示用
	define("INPUT_WORK_STATUS_NAME",	"作業確定");					// 新規登録デフォルト表示用
	
// =============================================================================
// 新規登録用作業ステータス
// =============================================================================
	define("CONDITION_PLURAL",			"plural_number");				// 検索用
	
// =============================================================================
// 出力メッセージ
// =============================================================================
	define("OUTPUT_RETURN",				"RETURN");						// 改行指示とみなす値
	define("OUTPUT_MESSAGE",			"txtmsg");						// 表示するメッセージ

// =============================================================================
// 帳票出力のデフォルト値
// =============================================================================
	define("ATTENDANCE_OUTPUT_UNIT_DEFAULT",	"STAFF");				// 勤務表の出力単位
	define("BASE_TIME_DEFAULT",					60);					// 丸め基準時間(分)
	define("ROUND_TYPE_DEFAULT",				"切り捨て");			// 丸め方法(旧勤務表)
	define("ROUND_METHOD_DEFAULT",				"RD");					// 丸め方法(新勤務表)
	
// =============================================================================
// 端末（キャリア）
// =============================================================================
	define("TERMINAL_DOCOMO",			"DoCoMo");						// DoCoMo
	define("TERMINAL_SOFTBANK",			"SoftBank");					// SoftBank
	define("TERMINAL_AU",				"au");							// au
	define("TERMINAL_WILLCOM",			"Willcom");						// Willcom
	define("TERMINAL_PC",				"PersonalComputer");			// PC
	define("TERMINAL_UNKNOWN",			"Unknown");						// 不明
	define("AGENT_IPHONE",				"iPhone");						// iPhone
	define("AGENT_MSIE",				"MSIE");						// InternetExplorer
	define("AGENT_FIREFOX",				"Firefox");						// Firefox
	define("AGENT_OPERA",				"Opera");						// Opera
	define("AGENT_CHROME",				"Chrome");						// Google Chrome
	define("AGENT_SAFARI",				"Safari");						// Safari
	define("MODEL_IPHONE",				"iPhone");						// iPhone
	define("MODEL_MSIE",				"InternetExplorer");			// InternetExplorer
	define("MODEL_FIREFOX",				"Firefox");						// Firefox
	define("MODEL_OPERA",				"Opera");						// Opera
	define("MODEL_CHROME",				"GoogleChrome");				// Google Chrome
	define("MODEL_SAFARI",				"Safari");						// Safari
	define("MODEL_OTHER",				"Other");						// Other
	define("MODEL_UNKNOWN",				"Unknown");						// 不明
	
// =============================================================================
// 画像
// =============================================================================
	define("FAVICON_LOGO",				"../img/zenkicon.ico");			// ファビコン用ロゴ
	define("MOBILE_LOGO",				"../img/mobilelog.jpg");		// モバイル用ロゴ
	
// =============================================================================
// 省略時の文字数
// =============================================================================
	define("WORKNAME_SHORT_SIZE",		8);								// 作業名省略時の文字数
	define("USERNAME_SHORT_SIZE",		6);								// 作業者省略時の文字数
	
// =============================================================================
// SQL実行時のエラーステータス
// =============================================================================
	define("STATE_DUPLICATE_ENTRY",		"SQLSTATE[23000]");				// 重複
	
// =============================================================================
// コピーライト
// =============================================================================
//	define("COPY_RIGHT_PHRASE",			"Copyright &copy; 2010-".date(Y)." <b><font color=\"#B40303\">ZENK</font></b> Co., Ltd. All Rights Reserved.");
	define("COPY_RIGHT_PHRASE",			"2010-".date(Y)." <b><font color=\"#B40303\">ZENK</font></b> Co., Ltd. ");
	
?>
