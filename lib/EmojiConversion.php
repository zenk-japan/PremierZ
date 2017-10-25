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

require_once('../lib/CommonImageValue.php');
// *****************************************************************************
// ファイル名：EmojiConversion.php
// 処理概要  ：絵文字変換
// *****************************************************************************
class EmojiConversion {
	function getEmoji($unicode){
		
		$data = array(array($unicode));
		
		$emoji = '';
		foreach ($data as $carrier => $characters) {
			foreach ($characters as $character) {
				$emoji .= $character;
			}
		}
		
		require_once('../emoji/HTML/Emoji.php');
		$Comversion = HTML_Emoji::getInstance();
		$Comversion->setImageUrl('../img/mobilegif');
		$emoji = $Comversion->convertCarrier($emoji);
		
		return $emoji;
	}
	
/*==================================
  絵文字となる文字列の取得
  ==================================*/
	function getEmojiPhrase($p_terminal, $p_char){
		$l_disp_emoji = "";
		
		switch ($p_terminal){
			case TERMINAL_PC :
			// PC
				$l_disp_emoji = NULL;
			break;
			case TERMINAL_WILLCOM :
			// WILLCOM
				switch($p_char){
					case "un" :
						// un
						$l_disp_emoji = $this->getEmoji(UF987);
					break;
					case "due" :
						// due
						$l_disp_emoji = $this->getEmoji(UF988);
					break;
					case "trois" :
						// trois
						$l_disp_emoji = $this->getEmoji(UF989);
					break;
					case "quatre" :
						// quatre
						$l_disp_emoji = $this->getEmoji(UF98A);
					break;
					case "cinq" :
						// cinq
						$l_disp_emoji = $this->getEmoji(UF98B);
					break;
					case "six" :
						// six
						$l_disp_emoji = $this->getEmoji(UF98C);
					break;
					case "sept" :
						// sept
						$l_disp_emoji = $this->getEmoji(UF98D);
					break;
					case "huit" :
						// huit
						$l_disp_emoji = $this->getEmoji(UF98E);
					break;
					case "neuf" :
						// neuf
						$l_disp_emoji = $this->getEmoji(UF98F);
					break;
					case "dix" :
						// dix
						$l_disp_emoji = $this->getEmoji(UF990);
					break;
					case "sharp" :
						// sharp
						$l_disp_emoji = $this->getEmoji(UF985);
					break;
					case "new" :
						// new
						$l_disp_emoji = $this->getEmoji(UF982);
					break;
					case "ok" :
						// ok
						$l_disp_emoji = $this->getEmoji(UF9B0);
					break;
					case "ng" :
						// ng
						$l_disp_emoji = $this->getEmoji(UF9D4);
					break;
					case "id" :
						// id
						$l_disp_emoji = $this->getEmoji(UF97C);
					break;
					case "password" :
						// password
						$l_disp_emoji = $this->getEmoji(UF97D);
					break;
					case "search" :
						// search
						$l_disp_emoji = $this->getEmoji(UF981);
					break;
					case "warning" :
						// warning
						$l_disp_emoji = $this->getEmoji(UF9DC);
					break;
					case "phoneto" :
						// phoneto
						$l_disp_emoji = $this->getEmoji(UF972);
					break;
					case "phone" :
						// phone
						$l_disp_emoji = $this->getEmoji(UF8E9);
					break;
					case "mailto" :
						// mailto
						$l_disp_emoji = $this->getEmoji(UF973);
					break;
					case "mail" :
						// mail
						$l_disp_emoji = $this->getEmoji(UF977);
					break;
					case "clear" :
						// clear
						$l_disp_emoji = $this->getEmoji(UF980);
					break;
					case "recycle" :
						// recycle
						$l_disp_emoji = $this->getEmoji(UF9DA);
					break;
				}
			break;
			default :
			// その他は自動判別
				switch($p_char){
					case "un" :
						// un
						$l_disp_emoji = $this->getEmoji(UF987);
					break;
					case "due" :
						// due
						$l_disp_emoji = $this->getEmoji(UF988);
					break;
					case "trois" :
						// trois
						$l_disp_emoji = $this->getEmoji(UF989);
					break;
					case "quatre" :
						// quatre
						$l_disp_emoji = $this->getEmoji(UF98A);
					break;
					case "cinq" :
						// cinq
						$l_disp_emoji = $this->getEmoji(UF98B);
					break;
					case "six" :
						// six
						$l_disp_emoji = $this->getEmoji(UF98C);
					break;
					case "sept" :
						// sept
						$l_disp_emoji = $this->getEmoji(UF98D);
					break;
					case "huit" :
						// huit
						$l_disp_emoji = $this->getEmoji(UF98E);
					break;
					case "neuf" :
						// neuf
						$l_disp_emoji = $this->getEmoji(UF98F);
					break;
					case "dix" :
						// dix
						$l_disp_emoji = $this->getEmoji(UF990);
					break;
					case "sharp" :
						// sharp
						$l_disp_emoji = $this->getEmoji(UF985);
					break;
					case "new" :
						// new
						$l_disp_emoji = $this->getEmoji(UF982);
					break;
					case "ok" :
						// ok
						$l_disp_emoji = $this->getEmoji(UF9B0);
					break;
					case "ng" :
						// ng
						$l_disp_emoji = $this->getEmoji(UF9D4);
					break;
					case "id" :
						// id
						$l_disp_emoji = $this->getEmoji(UF97C);
					break;
					case "password" :
						// password
						$l_disp_emoji = $this->getEmoji(UF97D);
					break;
					case "search" :
						// search
						$l_disp_emoji = $this->getEmoji(UF981);
					break;
					case "warning" :
						// warning
						$l_disp_emoji = $this->getEmoji(UF9DC);
					break;
					case "phoneto" :
						// phoneto
						$l_disp_emoji = $this->getEmoji(UF972);
					break;
					case "phone" :
						// phone
						$l_disp_emoji = $this->getEmoji(UF8E9);
					break;
					case "mailto" :
						// mailto
						$l_disp_emoji = $this->getEmoji(UF973);
					break;
					case "mail" :
						// mail
						$l_disp_emoji = $this->getEmoji(UF977);
					break;
					case "clear" :
						// clear
						$l_disp_emoji = $this->getEmoji(UF980);
					break;
					case "recycle" :
						// recycle
						$l_disp_emoji = $this->getEmoji(UF9DA);
					break;
				}
			break;
		}
		
		return $l_disp_emoji;
	}
}
?>
