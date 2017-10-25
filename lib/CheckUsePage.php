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
 ファイル名：CheckUsePage.php
 処理概要  ：画面使用チェック
******************************************************************************/
require_once('../lib/CommonStaticValue.php');
class CheckUsePage{
/* =============================================================================
   変数定義
   ===========================================================================*/
	private $page_code;											// 画面名
	private $user_authcode;										// 権限コード
	private $isusable;											// 使用可否
	private $data_id;											// DATA_ID
	
	private $debug_mode = 0;
/* =============================================================================
	例外定義
   ===========================================================================*/
	function expt_checkusepage(Exception $e){
		print "例外が発生しました。<BR>";
		print $e->getMessage()."<BR>";
		return;
    }

/* =============================================================================
	コンストラクタ
	引数:
			$p_data_id				DATA_ID
   ===========================================================================*/
	function __construct($p_data_id){
		if($this->debug_mode==1){print("Step-__construct-開始");print "<br>";}
		
		// クラス変数のセット
		$this->data_id = $p_data_id;
		
		// 使用可否をfalseにセット
		$this->isusable			= false;
		
		if($this->debug_mode==1){print("Step-__construct-終了");print "<br>";}
	}
	
/* =============================================================================
   画面使用許可チェック
	概要: 画面と権限の情報から画面の使用可否を判定する
	引数:
			$p_authcode				権限コード
			$p_page					画面名
   ===========================================================================*/
	function getIsUsable($p_authcode, $p_page){
		if($this->debug_mode==1){print("Step-getIsUsable-開始");print "<br>";}
		$l_allowed_authcode = "";					// ページが使える権限コード
		
		// 使用可否をfalseにセット
		$this->isusable			= false;
		
		// 画面名をクラス変数にセット
		$this->user_authcode	= $p_authcode;
		$this->page_code		= $p_page;
		
		// 画面ごとの使用許可情報を取得
		// 検索条件設定
		$lr_cond = array('PAGE_CODE = "'.$this->page_code.'"');
		$lr_cond[] = 'DATA_ID = ' . $this->data_id;
		
		if($this->debug_mode==1){print("Step-getIsUsable-レコード取得条件セット完了");print "<br>";}
		
		// レコード取得
		require_once('../mdl/m_page_using_conf.php');
		$lc_new = new m_page_using_conf();
		$lc_new->setWhereArray($lr_cond);
		$lr_getrec = $lc_new->getViewRecord();
		
		if($this->debug_mode==1){print("Step-getIsUsable-レコード取得完了");print "<br>";}
		
		// 権限設定部分を取り出し
		$l_allowed_authcode = $lr_getrec[1]["ALLOWED_AUTHCODE"];
		
		if($this->debug_mode==1){print($p_authcode . ":" . $p_page . ":" . $l_allowed_authcode . "<br>");}
		
		// 取得した権限の文字列をデリミタごとに分割し、クラス変数にセットされた権限があれば、
		// isusableにtureをセットする
		if ($l_allowed_authcode != ""){
			if (trim($l_allowed_authcode) == "ALL"){
				// ALLと指定されている場合は無条件にtrueを返す
				$this->isusable = true;
				
			}else{
				// ALL以外の場合は;で分割して配列に格納する
				$lr_authcode = explode(";", $l_allowed_authcode);
				//print_r($lr_authcode);
				
				if (count($lr_authcode) > 0){
					foreach ($lr_authcode as $l_value){
						// 配列の中に指定の権限コードがあればtrueを返す
						if (trim($l_value) == $this->user_authcode){
							$this->isusable = true;
						}
					}
				}
			}
		}
		
		if($this->debug_mode==1){print("Step-getIsUsable-終了");print "<br>";}
		return $this->isusable;
	}
}
?>
