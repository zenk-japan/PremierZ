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

require_once('../lib/CommonStaticValue.php');
class CommonPageDef{
// *****************************************************************************
// クラス名：CommonPageDef
// 処理概要：画面定義で使用する変数と関数の定義
//           画面で使用する変数はこのクラスで一元管理する
// *****************************************************************************
// =============================================================================
// 変数定義
// =============================================================================
	public $link_value;						// リンク項目
	public $ar_menuset;						// メニューセット
	public $ar_bclink;						// パンくずリンク
	public $ar_keyvalue;					// キー値(隠し項目としてPOSTされる)
	public $ar_hdlvalue;					// ヘッドライン項目値
	public $ar_dtltab;						// 詳細テーブル
	public $ar_dm_title;					// 登録更新画面用タイトル配列
	public $ar_dm_maintab;					// 登録更新画面用項目配列
	public $ar_dm_buttons;					// 登録更新画面用ボタン配列
	public $ar_screenimg;					// 画面イメージ選択用配列
	public $title_text;						// 見出し文字列
	public $csscls_footer;					// フッター部のCSSクラス
	public $csscls_ftprmt;					// フッター部のTABLEのTDのCSSクラス
	public $csscls_fttext;					// フッター部のTABLEのTDのCSSクラス(枠付き)
	public $csscls_fttextbox;				// フッター部のTABLEのINPUTのCSSクラス
	public $csscls_fttxtro;					// フッター部のTABLEのINPUT(編集不可)のCSSクラス
	public $ar_original_js;					// 独自のjs設定
	public $ar_original_css;				// 独自のCSS設定
	protected $start_mode;					// 起動モード

// =============================================================================
// コンストラクタ
// =============================================================================
	function __construct(){
	
	//--------------------------------------------------
	// キー値用配列
	//--------------------------------------------------
		$this->ar_keyvalue[HDITEM_DATAID]				= $_POST[HDITEM_DATAID];		// データID
		$this->ar_keyvalue[HDITEM_LOGINUSERID]			= $_POST[HDITEM_LOGINUSERID];	// ユーザID
		$this->ar_keyvalue[HDITEM_DELETE_CHECK]			= $_POST[HDITEM_DELETE_CHECK];	// 削除済みチェックの状態
		$this->ar_keyvalue[HDITEM_RESERV1]				= $_POST[HDITEM_RESERV1];		// 予備1
		$this->ar_keyvalue[HDITEM_RESERV2]				= $_POST[HDITEM_RESERV2];		// 予備2
		$this->ar_keyvalue[HDITEM_RESERV3]				= $_POST[HDITEM_RESERV3];		// 予備3
		$this->ar_keyvalue[HDITEM_RESERV4]				= $_POST[HDITEM_RESERV4];		// 予備4
	}
}
?>