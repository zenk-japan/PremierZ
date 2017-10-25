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
 データ登録/更新画面処理
*******************************************************************************/
var $id_div_cover;										// カバーのID
var $id_div_dmmain;										// メイン領域のID
var $id_div_btn_area;									// ボタン領域のID
var $id_btn_btn_area;									// 閉じるボタンのID
var $id_iframe;											// IframeのID
var $button_area_height		= 20;						// ボタン領域の高さ
var $button_area_color		= "#7d83b5";				// ボタン領域の背景色
var $button_area_linecolor	= "#7b7f9c";				// ボタン領域の線色
var $button_area_txtcolor	= "#000000";				// ボタン領域の文字色
var $close_button_color		= "#2d334a";				// 閉じるボタンの背景色(未使用)
var $close_button_linecolor	= "#212430";				// 閉じるボタンの線色(未使用)
var $close_button_txtcolor	= "#ffffff";				// 閉じるボタンの文字色(未使用)

/*==============================================================================
  検索処理
  引数
  				$p_obj_hidden				隠し項目のオブジェクト
  				$p_show_page				表示ページ番号
  ============================================================================*/


/*==============================================================================
  カバー追加
  ============================================================================*/
function setCover(){
	// カバーのID
	$id_div_cover	= "id_div_cover_zz";
	
	// ドキュメントの高さと幅
	$l_doc_width	= $(document).width();
	$l_doc_height	= $(document).height();
	
	//alert("l_doc_width -> "+$l_doc_width+":"+"l_doc_height -> "+$l_doc_height);
	$l_cover_html = "<div id=\"" + $id_div_cover + "\" ";
	$l_cover_html = $l_cover_html + "style=\"";
	$l_cover_html = $l_cover_html + "position:fixed; ";
	$l_cover_html = $l_cover_html + "top:0px; ";
	$l_cover_html = $l_cover_html + "left:0px; ";
	$l_cover_html = $l_cover_html + "width:100%; ";
	$l_cover_html = $l_cover_html + "height:100%; ";
	$l_cover_html = $l_cover_html + "z-index:100; ";
	$l_cover_html = $l_cover_html + "background-color:#000000; ";
	//$l_cover_html = $l_cover_html + "filter:Alpha(opacity=50); ";		// fireFoxでエラーになる
	$l_cover_html = $l_cover_html + "text-align:center; ";
	$l_cover_html = $l_cover_html + "\">";
	$l_cover_html = $l_cover_html + "</div>";
	
	// ドキュメントに追加
	$(window.document.body).append($l_cover_html);
	
	// フォーカスをカバーに移す
	$("#"+$id_div_cover).focus();
	
	// 半透明化
	$("#"+$id_div_cover).css({opacity: 0.5});
}

/*==============================================================================
  main領域追加
  引数
  				$p_width				Iframeの幅
  				$p_height				Iframeの高さ
  ============================================================================*/
function putMainDiv($p_width, $p_height){
	// メイン領域のID
	$id_div_dmmain	= "id_div_dmmain_zz";
	
	// 設置位置の差分
	$l_doc_top	= ($(window).height() - $p_height) / 2;
	$l_doc_left	= ($(window).width() - $p_width) / 2;

	$l_dmmain_html = "<div id=\"" + $id_div_dmmain + "\" ";
	$l_dmmain_html = $l_dmmain_html + "style=\"";
	$l_dmmain_html = $l_dmmain_html + "position:absolute; ";
	$l_dmmain_html = $l_dmmain_html + "left:50%; ";
	$l_dmmain_html = $l_dmmain_html + "top:50%; ";
	$l_dmmain_html = $l_dmmain_html + "margin-left:-" + ($p_width / 2) + "px; ";
	$l_dmmain_html = $l_dmmain_html + "margin-top:-" + ($p_height / 2) + "px; ";
	$l_dmmain_html = $l_dmmain_html + "width:" + $p_width + "px; ";
	//$l_dmmain_html = $l_dmmain_html + "height:" + $p_height + "px; ";
	$l_dmmain_html = $l_dmmain_html + "height:0px; ";
	$l_dmmain_html = $l_dmmain_html + "z-index:1000; ";
	$l_dmmain_html = $l_dmmain_html + "border:1px solid #DDDDDD; ";
	$l_dmmain_html = $l_dmmain_html + "background-color:#ffffff; ";
	$l_dmmain_html = $l_dmmain_html + "\">";
	$l_dmmain_html = $l_dmmain_html + "</div>";
	
	// ドキュメントに追加
	$(window.document.body).append($l_dmmain_html);
	
	// フォーカスをDIVに移す
	$("#"+$id_div_dmmain).focus();
}

/*==============================================================================
  ボタン領域追加
  引数
  				$p_width				メイン領域の幅
  				$p_height				メイン領域の高さ
  ============================================================================*/
function putButtonArea($p_width, $p_height){
	$id_div_btn_area = "id_div_btn_area_zz";
	$id_btn_btn_area = "id_btn_btn_area_zz";
	
	$l_btn_area_html = "<div id=\"" + $id_div_btn_area + "\" ";
	$l_btn_area_html = $l_btn_area_html + "style=\"";
	$l_btn_area_html = $l_btn_area_html + "position:absolute; ";
	$l_btn_area_html = $l_btn_area_html + "left:0%; ";
	$l_btn_area_html = $l_btn_area_html + "top:0%; ";
	$l_btn_area_html = $l_btn_area_html + "width:100%; ";
	$l_btn_area_html = $l_btn_area_html + "border:1px outset " + $button_area_linecolor + "; ";
	$l_btn_area_html = $l_btn_area_html + "height:" + $button_area_height + "px; ";
	//$l_btn_area_html = $l_btn_area_html + "height:0px; ";
	$l_btn_area_html = $l_btn_area_html + "background-color:" + $button_area_color + "; ";
	$l_btn_area_html = $l_btn_area_html + "text-align:right; ";
	$l_btn_area_html = $l_btn_area_html + "\">";
	
	$l_btn_area_html = $l_btn_area_html + "<input type=\"button\" ";
	$l_btn_area_html = $l_btn_area_html + "id=\"" + $id_btn_btn_area + "\"; ";
	$l_btn_area_html = $l_btn_area_html + "style=\"";
	//$l_btn_area_html = $l_btn_area_html + "background-color;" + $close_button_color + "; ";
	//$l_btn_area_html = $l_btn_area_html + "border:1px solid " + $close_button_linecolor + "; ";
	//$l_btn_area_html = $l_btn_area_html + "color:" + $close_button_txtcolor + "; ";
	$l_btn_area_html = $l_btn_area_html + "width:20px; ";
	$l_btn_area_html = $l_btn_area_html + "height:" + ($button_area_height - 1) + "px; ";
	//$l_btn_area_html = $l_btn_area_html + "height:0px; ";
	$l_btn_area_html = $l_btn_area_html + "padding:0px; ";
	$l_btn_area_html = $l_btn_area_html + "margin:0px; ";
	$l_btn_area_html = $l_btn_area_html + "text-align:center; ";
	$l_btn_area_html = $l_btn_area_html + "font-size:12px; ";
	$l_btn_area_html = $l_btn_area_html + "\" ";
	$l_btn_area_html = $l_btn_area_html + "value=\"－\" />";
	
	$l_btn_area_html = $l_btn_area_html + "</div>";
	
	// メイン領域の内部に追加
	$("#"+$id_div_dmmain).append($l_btn_area_html);
}

/*==============================================================================
  Iframeの追加
  引数
  				$p_width				メイン領域の幅
  				$p_height				メイン領域の高さ
  ============================================================================*/
function putIframe($p_width, $p_height){
	$id_iframe = "id_iframe_zz";
	
	$l_iframe_html = "<div id=\"" + $id_iframe + "\" ";
	$l_iframe_html = $l_iframe_html + "style=\"";
	$l_iframe_html = $l_iframe_html + "position:relative; ";
	$l_iframe_html = $l_iframe_html + "border:0px solid; ";
	$l_iframe_html = $l_iframe_html + "overflow:hidden; ";
	$l_iframe_html = $l_iframe_html + "top:" + $button_area_height + "px; ";
	$l_iframe_html = $l_iframe_html + "width:100%; ";
	$l_iframe_html = $l_iframe_html + "height:" + ($p_height - $button_area_height) + "px; ";
	$l_iframe_html = $l_iframe_html + "\"></div>";
	
	// ボタン領域の後に追加
	//$("#"+$id_div_dmmain).append($l_iframe_html);
	$("#"+$id_div_btn_area).after($l_iframe_html);
	
}
	
/*==============================================================================
  閉じるボタン処理のバインド
  引数
  				$p_id					閉じるボタンのid
  ============================================================================*/
function bindBtnClick($p_id){
	if($("#"+$p_id)){
		$("#"+$p_id).bind("click", function(){
			// メイン領域の削除
			$("#"+$id_div_dmmain).animate({
					height: 0 + "px"
				},600, function(){
						$("#"+$id_div_dmmain).remove();
						// カバーの削除
						$("#"+$id_div_cover).hide("fast",function(){$("#"+$id_div_cover).remove();});
					}
			);
		});
	}
}

/*==============================================================================
  起動処理
  引数
  				$p_width				Iframeの幅
  				$p_height				Iframeの高さ
  ============================================================================*/
function setFrame($p_width, $p_height){
	// カバーをかける
	setCover();
	
	// main領域を追加
	putMainDiv($p_width, $p_height);
	
	// ボタン領域を追加
	putButtonArea($p_width, $p_height);
	
	// フレームを追加
	putIframe($p_width, $p_height);
	
	// 表示
	$("#"+$id_div_dmmain).animate({
			height: $p_height + "px"
		},600
	);
	
	// ボタンクリック時の処理をバインド
	bindBtnClick($id_btn_btn_area);
	
	$("#"+$id_iframe).load("https://192.168.1.2/maintenance/maintenance.php");
}

/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(function(){
	
});
