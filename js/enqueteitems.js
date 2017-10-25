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
 アンケート呼び出し用javascript関数
*******************************************************************************/
var $debug_mode		= 0;								// デバッグモード(1:デバッグ)
var $l_data_id_item	= "hd_dataid";						// データIDの項目
var $post_to		= "../ctl/c_enquete_answer.php";	// POST先のphpファイル

/*==============================================================================
  質問番号切り出し処理
  ～_n形式のIDから質問番号部分を切り出す
  ============================================================================*/
function getQNum($p_id){
	// 0を返すとエラー処理に問題が発生する恐れがあるので、初期値は-1とする
	var $l_return_num = -1;
	var $l_row_str = "";
	var $l_hf_pos = $p_id.indexOf("_");			// アンダースコアの位置(先頭は0)
	
	if($l_hf_pos > 0){
		$l_return_num = $p_id.substr($l_hf_pos+1);
	}
	return $l_return_num;
}
/*==============================================================================
  番号切り出し処理
  ～_n-n形式のIDから番号部分を切り出す
  p_mode : 1…最初の番号(質問番号)、2…2番目の番号(回答番号)
  ============================================================================*/
function getQANum($p_id, $p_mode){
	// 0を返すとエラー処理に問題が発生する恐れがあるので、初期値は-1とする
	var $l_return_num = -1;
	var $l_row_str = "";
	var $l_us_pos = $p_id.indexOf("_");			// アンダースコアの位置(先頭は0)
	
	// n-n形式部分を抜き出し
	if($l_us_pos > 0){
		$l_addr = $p_id.substr($l_us_pos+1);
	}
	
	// ハイフンの位置検索
	var $l_hf_pos = $l_addr.indexOf("-");			// ハイフンの位置(先頭は0)
	
	if($p_mode==1){
	// 質問番号部分を抜き出し
		$l_return_num = $l_addr.substr(0, $l_hf_pos);
	}else{
	// 回答番号部分を抜き出し
		$l_return_num = $l_addr.substr($l_hf_pos+1);
	}
	
	return $l_return_num;
}

/*==============================================================================
  回答のセット
  ============================================================================*/
function setAnswer($p_this_id){
	// 回答ボタンが押されたら、該当質問のあるテーブル内の回答欄に回答をセットする
	//alert($p_this_id);
	$l_question_num = getQANum($p_this_id, 1);				// 質問番号
	$l_answer_num = getQANum($p_this_id, 2);				// 回答番号
	//alert("question -> "+$l_question_num+" answer -> "+$l_answer_num);
	
	// 回答タイプを取得
	$l_answer_type = $("#qtable_"+$l_question_num+" > * > TR > TD.css_td_answer:eq("+($l_answer_num-1)+")").attr("name");
	
	// 設定値を取得
	var $l_answer = "";
	if($l_answer_type=="ansgrph"){
		// 画像の場合は回答番号をセット
		$l_answer = $l_answer_num;
	}else{
		$l_answer = $("#qtable_"+$l_question_num+" > * > TR > TD.css_td_answer:eq("+($l_answer_num-1)+") > INPUT").val();
		if($l_answer.length>1000){
			alert("回答は1000文字以内にして下さい。※現在"+$l_answer.length+"文字");
			return false;
		}
	}
	
	// 前後の空白をトリミング
	$l_answer = $l_answer.replace(/^\s+|\s+$/g, "");
	
	// タグ用の括弧を置換
	$l_answer = $l_answer.replace(/\</g, ";&lt");
	$l_answer = $l_answer.replace(/\>/g, ";&gt");
	
	// 改行系は削除
	$l_answer = $l_answer.replace(/[\n,\r,\;]/g, "");
	
	// 引用符は削除
	//$l_answer = $l_answer.replace(/[\',\"]/g, "");
	//alert($l_answer);
	
	// 回答を保存用回答欄にセット
	$("#ansreslt_"+$l_question_num).val($l_answer);
	//alert($l_answer);
}

/*==============================================================================
  処理結果表示処理
  ============================================================================*/
function showMess(data){
	if( data != ''){
		alert(data);
		// 画面を閉じる
		self.parent.tb_remove();
	}
}

/*==============================================================================
  保存処理
  ============================================================================*/
function saveAnswer(){
	// エラーフラグ(0:正常,1:異常)
	var $l_errflg = 0;
	
	// 保存用回答を回収
	var $l_answer_count = $(".css_ansreslt").size();		// 回答の数をカウント
	var $lr_answer = new Array($l_answer_count);			// 配列を設定
	var $l_loopcnt = 0;
	$(".css_ansreslt").each(function(){
		// 空欄があればアラートを出力して終了(必須以外に指定されている場合は除く)
		$lr_answer[$l_loopcnt] = $(this).val();
		
		//alert($lr_answer[$l_loopcnt]);
		if($lr_answer[$l_loopcnt] == '' || $lr_answer[$l_loopcnt] == null){
			// 必須チェック対象外チェック(質問のnameがrequireなら必須)
			$l_require = $("#question_"+($l_loopcnt+1)).attr("name");
			
			// チェック対象外でない場合は、エラーメッセ―ジを表示して終了
			if($l_require=="require"){
				alert(($l_loopcnt+1)+"番目の回答が空欄です。\n必須回答項目をすべて回答してから保存して下さい。");
				// 空欄の問題のTOP算出
				$l_offset = $("#question_"+($l_loopcnt+1)).offset();
				// 問題の場所へ移動
				scrollTo(0,$l_offset.top);
				
				// エラーフラグを立てる
				$l_errflg = 1;
				return false;
			}
		}
		$l_loopcnt++;
	});
	// 上記の関数でエラーフラグがたった場合は、終了
	if($l_errflg==1){
		return false;
	}
	
	// DATA_ID取得
	var $l_data_id = $("input[name=DATA_ID]").val();
	// ENCRYPTION_KEY取得
	var $l_encryption_key = $("input[name=ENCRYPTION_KEY]").val();
	// LOGIN_USER_ID取得
	var $l_login_user_id = $("input[name=LOGIN_USER_ID]").val();
	
	// 保存用PHPにPOSTする
	// POST処理(ANSWERは先頭が0番の配列になるので注意)
	$.post(
		$post_to, {
			 "DATA_ID"					: $l_data_id
			,"ENCRYPTION_KEY"			: $l_encryption_key
			,"LOGIN_USER_ID"			: $l_login_user_id
			,"ANSWER[]"					: $lr_answer
		}, showMess
	);
	
}

/*==============================================================================
  画面起動時処理
  ============================================================================*/
$(document).ready(function(){
	
	//------------------------
	// 回答ボタン
	//------------------------
	$(".css_ansbtn").bind("click", function(){
		setAnswer($(this).attr("id"));
	});
	
	//------------------------
	// 保存ボタン
	//------------------------
	$("#savebt").bind("click", function(){
		saveAnswer();
	});
	//------------------------
	// キャンセルボタン
	//------------------------
	$("#cancelbt").bind("click", function(){
		self.parent.tb_remove();
	});
	//------------------------
	// 全てクリアボタン
	//------------------------
	$("#clearbt").bind("click", function(){
		$("INPUT.css_ansreslt").val("");
	});
	
	//------------------------
	// テキスト入力部の
	// テキスト変更時処理
	//------------------------
	$(".css_anstxt").bind("change", function(){
		setAnswer($(this).attr("id"));
	});

	//------------------------
	// あなたの回答でDELキークリック
	//------------------------
	$("INPUT.css_ansreslt").keyup(function(e){
		if(e.keyCode==46){
		// DELが押された場合は値消去
			$(this).val('');
		}
	})
});
