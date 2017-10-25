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
  Copyright (c) 2005-2012 ZENK Co., Ltd. All Rights Reserved.
  license   http://www.php.net/license/3_01.txt PHP License 3.01

  http://www.zenk.co.jp/

  THIS SOFTWARE IS PROVIDED BY THE ZENK DEVELOPMENT TEAM ``AS IS'' AND 
  ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
  THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A 
  PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE ZENK
  DEVELOPMENT TEAM OR ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
  INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR 
  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
  HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
  STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
  OF THE POSSIBILITY OF SUCH DAMAGE.

******************************************************************************/
/******************************************************************************
 ファイル名：c_getUserList.php
 処理概要  ：ユーザーリスト取得
 POST受領値：
             token_code                トークン(必須)
             data_id                   DATA_ID(必須)
             company_name              会社名(必須)
             group_name                グループ名(必須)
******************************************************************************/
	$l_dir_prfx		= dirname(__FILE__)."/";				// 当画面のDIR階層を補完するためのDIRプレフィックス
	$l_debug_mode	= 0;									// デバッグモード(1:有効、0:無効)
	if($l_debug_mode==1){
		print_r($_POST);
		//print "step1<br>";
		//print "<br>";
		//print_r($_SESSION);
	}
// ==================================
// 前処理
// ==================================x
	require_once('../lib/CommonStaticValue.php');

	if($l_debug_mode==1){print "step前処理\n";}
// ==================================
// 変数宣言
// ==================================
	$l_mes_sufix		= " at=>".basename(__FILE__)."<BR>";	// メッセージ用接尾辞
	$l_html_rts			= "<BR>";								// HTMLの改行
	$l_post_token		= "";									// POSTされたトークン
	$l_sess_token		= "";									// セッションで保持しているトークン
	$l_user_id			= "";
	$l_company_name		= "";
	$l_group_name		= "";
	
	if($l_debug_mode==1){print "step変数宣言\n";}
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_getuserlist(Exception $e){
		//echo "例外が発生しました。";
		//echo $e->getMessage();
		// セッション切断の場合はメッセージに「ST」と入ってくる
		if($e->getMessage() == "ST"){
			$l_error_type = "ST";
		}else{
			$l_error_type = "ER";
		}
		
		require_once('../lib/ShowMessage.php');
		$lc_smess = new ShowMessage($l_error_type);
		
		// 予期せぬ例外の場合は追加メッセージをセット
		if($l_error_type != "ST"){
			$lc_smess->setExtMessage($e->getMessage());
		}
		
		$lc_smess->showMessage();
		return;
    }
	set_exception_handler('my_exception_getuserlist');
	
	if($l_debug_mode==1){print "step例外定義\n";}
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// postされたトークンとセッション内のトークンが一致しない場合は不正とみなす
	$l_post_token = $_POST['token_code'];
	if(is_null($l_post_token)){
		throw new Exception($l_error_type_st);
	}
	
	require_once('../lib/sessionControl.php');
	$lc_sess = new sessionControl();
	$l_sess_token = $lc_sess->getToken();
	if(is_null($l_sess_token)){
		throw new Exception($l_error_type_st);
	}
	if($l_post_token != $l_sess_token){
		throw new Exception($l_error_type_st);
	}
	
	// ユーザー名の取得
	$l_user_name = $lc_sess->getSesseionItem('NAME');
	
	// ユーザーIDの取得
	$l_sess_user_id = $lc_sess->getSesseionItem('USER_ID');
	if($l_sess_user_id == ""){
		throw new Exception($l_error_type_st);
	}
	
	// DATA_IDの取得
	$l_data_id = $lc_sess->getSesseionItem('DATA_ID');
	if($l_data_id == ""){
		throw new Exception($l_error_type_st);
	}
	/*
	// トークンを破棄し再取得
	$lc_sess->deleteToken();
	$l_token = $lc_sess->setToken();
	print "<br>";
	print_r($_SESSION);
	*/
	if($l_debug_mode==1){print "stepセッション確認\n";}
/*----------------------------------------------------------------------------
  POST変数取得
  ----------------------------------------------------------------------------*/
	$l_company_name		= $_POST['company_name'];
	$l_group_name		= $_POST['group_name'];
	if($l_debug_mode==1){print "stepPOST変数取得\n";}
/*----------------------------------------------------------------------------
  ユーザーの取得
  ----------------------------------------------------------------------------*/
	// 作業人員MDL
	require_once('../mdl/m_user_master.php');
	if($l_debug_mode==1){print "Step-ユーザーMDL\n" ;}
	
	//------------------------
	// 検索条件設定
	//------------------------
	// DATA_ID
	$lr_user_cond = array('DATA_ID = '.$l_data_id);
	
	// 有効フラグ
	array_push($lr_user_cond, "VALIDITY_FLAG = 'Y'");
	
	// 会社
	array_push($lr_user_cond, "COMPANY_NAME = '".$l_company_name."'");
	
	// グループ
	array_push($lr_user_cond, "GROUP_NAME = '".$l_group_name."'");
	
	//------------------------
	// 整列設定
	//------------------------
	$lr_user_order = array('KANA');
	
	//------------------------
	// レコード取得
	//------------------------
	$lc_user = new m_user_master('Y', $lr_user_cond, $lr_user_order);
	$lr_user = $lc_user->getViewRecord();
	//print_r($lr_user);
	if($l_debug_mode==1){print "Step-ユーザーレコード取得\n";}
/*----------------------------------------------------------------------------
  リストBOX用HTMLの作成
  ----------------------------------------------------------------------------*/
	$l_html = "";
	if (count($lr_user) > 0){
		foreach ($lr_user as $rec_num => $user_rec){
			$l_html .= '<option class="c_opt_edit_user_list" value="'.$user_rec['USER_ID'].'">'.$user_rec['NAME'].'</option>';
		}
	}else{
		$l_html = 0;
	}
	print $l_html;
?>
