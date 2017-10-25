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
/*******************************************************************************
 サブ画面表示処理
*******************************************************************************/
var $debug_mode			= 0;						// デバッグモード
var $message			= "";						// メッセージ
var $ar_post_value;									// POSTする値の配列
var $mail_file_name		= "mailLogSubView.php";	
var $login_file_name	= "loginLogSubView.php";
var $load_file_name		= "";						// サブ画面に読み込むphpファイル
var $id_back_div		= "id_ext_back_div";		// 背景のDIVのID
var $id_page_div		= "id_ext_page_div";		// ページ表示用DIVのID
var $id_close_button	= "id_btn_subv_close";		// 閉じるボタンのID
var $id_loading_div		= "id_ext_loading_div";		// ロード中表示DIVのID

/*==============================================================================
  サブ画面を表示
  引数：
==============================================================================*/
function showSubView($pr_post_value, $p_file){
	// POSTする値をセット
	$ar_post_value = $pr_post_value;
	
	// サブ画面のファイルを設定
	if ($p_file == "mail"){
		$load_file_name = $mail_file_name;
	}else if($p_file == "login"){
		$load_file_name = $login_file_name;
	}else{
		alert("サブ画面のファイル指定が不正です。");
		return false;
	}
	
	// 背景のDIVを表示
	showBackDiv();
}
/*==============================================================================
  背景のDIVを表示
  引数：
==============================================================================*/
function showBackDiv(){
	// 背景のDIVを設定
	$l_html_backdiv = "<div style=\"display:none\" id=\"" + $id_back_div + "\"></div>"
	
	// 背景のDIVを表示
	$("body").prepend($l_html_backdiv);
	$lo_back_div = $("#" + $id_back_div);
	$lo_back_div.css("opacity", "0");
	$lo_back_div.show();
	$lo_back_div.animate({
	    	opacity: 0.7
		},600,function(){
			// HTMLを配置するDIVを表示
			showPageDiv($lo_back_div);
		}
	);
	
	$lo_back_div.bind("click", function(){
		removePageDiv();
	});
	
}
/*==============================================================================
  ページのDIVを表示
  引数：
  					$po_master_div				背景のDIV
==============================================================================*/
function showPageDiv($po_master_div){
	// HTMLを配置するDIVを設定
	$l_html_pagediv = "<div style=\"display:none\" id=\"" + $id_page_div + "\"></div>";
	
	// DIVを配置
	$po_master_div.after($l_html_pagediv);
	//$po_master_div.prepend($l_html_pagediv);
	$lo_page_div = $("#" + $id_page_div);
	
	// NowLoading表示
	showNowLoading();
	
	// phpページをロード
	$lo_page_div.load($load_file_name, $ar_post_value, function(){
			// NowLoading削除
			removeNowLoading();
		
			// DIVを表示
			$lo_page_div.slideDown("nomal",function(){
					// 閉じるボタンクリック時の処理をバインド
					$("#" + $id_close_button).bind("click", function(){
						removePageDiv();
					});
				}
			);
		}
	);
	
}
/*==============================================================================
  NowLoading表示
==============================================================================*/
function showNowLoading(){
	$l_html_nowloading = "<div style=\"display:none\" id=\"" + $id_loading_div + "\"></div>";
	$("body").prepend($l_html_nowloading);
	$lo_loading_div = $("#" + $id_loading_div);
	$lo_loading_div.fadeIn("fast");
}

/*==============================================================================
  NowLoading削除
==============================================================================*/
function removeNowLoading(){
	if($("#" + $id_loading_div)){
		$lo_loading_div.fadeOut("fast", function(){
				$lo_loading_div.remove();
			}
		);
	}
}
/*==============================================================================
  ページのDIVを削除
==============================================================================*/
function removePageDiv(){
	$lo_page_div = $("#" + $id_page_div);
	if($lo_page_div){
		$lo_page_div.slideUp("nomal",function(){
				// 背景を削除
				removeBackDiv();
			}
		);
	}
}

/*==============================================================================
  背景のDIVを削除
  ============================================================================*/
function removeBackDiv(){
	$lo_back_div = $("#" + $id_back_div);
	if($lo_back_div){
		$lo_back_div.animate({
				opacity: 0
			},500,function(){
				$lo_back_div.hide();
				$lo_back_div.remove();
			}
		);
	}
}

  
/*==============================================================================
  画面起動時処理
  ============================================================================*/
jQuery(function($){

});
