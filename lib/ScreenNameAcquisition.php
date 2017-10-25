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
class ScreenNameAcquisition{
	/**
	 * 画面名取得
	 *
	 * $screen_id  = 画面ID
	 * $screen_name  = 画面名
	 * @return array
	 */
	public static function getScreenName($screen_id){
		
		switch ($screen_id) {
			
			// ログイン画面
			case "ZSMMC001":
				 $screen_name = SCREEN_ZSMMC001;
				 break;
			// ログアウト画面
			case "ZSMMC002":
				 $screen_name = SCREEN_ZSMMC002;
				 break;
			// TOP画面
			case "ZSMMC003":
				 $screen_name = SCREEN_ZSMMC003;
				 break;
			// 問い合せ画面
			case "ZSMMC004":
				 $screen_name = SCREEN_ZSMMC004;
				 break;
			// 設定変更画面
			case "ZSMM0010":
				 $screen_name = SCREEN_ZSMM0010;
				 break;
			// 作業内容一覧画面
			case "ZSMM0020":
				 $screen_name = SCREEN_ZSMM0020;
				 break;
			// 作業内容詳細画面
			case "ZSMM0030":
				 $screen_name = SCREEN_ZSMM0030;
				 break;
			// 作業内容承認画面
			case "ZSMM0040":
				 $screen_name = SCREEN_ZSMM0040;
				 break;
			// 作業状況確認画面
			case "ZSMM0050":
				 $screen_name = SCREEN_ZSMM0050;
				 break;
			// 作業状況確認詳細画面
			case "ZSMM0060":
				 $screen_name = SCREEN_ZSMM0060;
				 break;
			// 作業完了詳細画面
			case "ZSMM0070":
				 $screen_name = SCREEN_ZSMM0070;
				 break;
			// 補足/修正送信フォーム画面
			case "ZSMM0071":
				 $screen_name = SCREEN_ZSMM0071;
				 break;
			// 送信完了画面
			case "ZSMM0072":
				 $screen_name = SCREEN_ZSMM0072;
				 break;
			
			
		}
		
		return $screen_name;
	}
}
?>
