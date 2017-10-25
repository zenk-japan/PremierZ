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
require_once('../lib/CommonFunctions.php');
require_once('../mdl/ModelCommon.php');
	class Target_of_alert_v extends ModelCommon{
// =============================================================================
// コンストラクタ
// 引数:
// =============================================================================
		function __construct(){
			// 継承元のコンストラクタを起動
			ModelCommon::__construct("TARGET_OF_ALERT_V");					// ビュー名を指定
		}
	}
?>