<!--
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
-->
<?xml version="1.0" encoding="{$char_code}"?>
<!-- {$terminal}/{$model} -->
{$doctype}
<html{$xmlns}>
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title>{$headtitle}</title>
</head>
<body>
	<div id="id_div_outer" >
		<!-- イメージファイル -->
		<div>
			<img src="{$img_logo}" alt="">
			<hr>
			<font color="#6495ED"><b>{$headtitle}</b></font>
		</div>
		<br>
		<br>
		<div id="id_div_main">
			<p id="id_p_main1" style="position:relative; left:5%; font-size:14px;">{$user_name}&nbsp;様</p>
			<p id="id_p_main1" style="position:relative; left:5%; font-size:14px;">パスワードをリセット致しました。</p>
			<p id="id_p_main1" style="position:relative; left:5%; font-size:14px;">新しいパスワードは、</p>
			<p id="id_p_main1" style="position:relative; left:5%; font-size:14px; color:red;">{$new_passwoed}</p>
			<p id="id_p_main1" style="position:relative; left:5%; font-size:14px;">です。</p>
			<p></p>
			<p id="id_p_main2" style="position:relative; left:5%; font-size:14px;"><a href="{$login_page}">ログイン画面へ</a></p>
		</div>
		<br>
	</div>
	<hr>
	<!-- コピーライト -->
	<div align="center">{$txt_copyright}</div>
</body>
</html>
