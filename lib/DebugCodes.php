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

class DebugCodes{
// *****************************************************************************
// デバッグ用コード集
// *****************************************************************************
// -----------------------------------------------------------------------------
// 現在の隠し項目とPOST項目を表示する
// 引数：$trgt_class	呼び出し元のクラスインスタンス
// -----------------------------------------------------------------------------
	function getHiddenPost($trgt_class){
		if(is_array($trgt_class->ar_keyvalue)){
			$l_retval = "
			※デバッグ中<BR>
			-- 隠し項目--<BR>";
			foreach($trgt_class->ar_keyvalue as $key => $value){
				$l_retval .= $key." -> ".$value."<BR>";
			}
		}
		
		$l_retval .= "<BR>-- POST項目--<BR>";
		
		foreach($_POST as $key => $value){
			$l_retval .= $key." -> ".$value."<BR>";
		}
		return $l_retval;
	}
}
?>