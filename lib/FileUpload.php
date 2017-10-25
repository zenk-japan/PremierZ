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
// *****************************************************************************
// ファイル名：FileUpload.php
// 処理概要  ：ファイルアップロード処理
// *****************************************************************************
//	var_dump($_FILES);
	date_default_timezone_set('Asia/Tokyo');
	//echo "<hr/>";
	$uploads_dir = '../uploads';
	$filename = date(YmdHis).$_FILES["userfile"]["name"];
	$upFile = $uploads_dir."/".$filename;
	$fileMove = move_uploaded_file($_FILES["userfile"]["tmp_name"],$upFile);
	if($fileMove == 1){
		echo $upFile;
	}else{
		echo "アップロード失敗";
	}
?>