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
 �l���X�g�pjavascript�֐�
*******************************************************************************/
var $debug_mode = 1;							// �f�o�b�O���[�h
var $g_width_adj = 1.2;							// ���ڕ������p����
var $l_header_id_prfx	= "lv_hd_";				// �w�b�_�[���̃e�L�X�g�{�b�N�XID�v���t�B�b�N�X
var $l_dtl_id_prfx		= "lv_dtl_";			// ���ו��̃e�L�X�g�{�b�N�XID�v���t�B�b�N�X
var $l_dtl_tr_prfx		= "lv_dtl_tr_";			// ���ו���TRID�v���t�B�b�N�X

/*==============================================================================
  ����������
  ���o�����ڂƖ��׍��ڂ̕����r���A�L�����ɍ��킹��
  ============================================================================*/
function dtl_based_adjust_width($p_this){
	// ���s�񐔃`�F�b�N
	if($("#lv_param1").val()>0){
		return;
	}
	
	$l_col_cnt = $(".css_lv_header_item_textro").size();
	$l_now_col = 1;
	var $lr_header_width = new Array($l_col_cnt);
	//alert("cond count -> " + $l_col_cnt);
	
	// ���o���̊e���ڂ̕���擾
	$(".css_lv_header_item_textro").each(function(){
		//alert($(this).width());
		$lr_header_width[$l_now_col] = $(this).width() * $g_width_adj;
		$l_now_col = $l_now_col + 1;
	});
	//alert("l_col_cnt -> " + $l_col_cnt + "l_now_col -> " + $l_now_col);
	if($lr_header_width[1]==0){
		return 2;
	}
	
	// ���ׂɃZ�b�g
	$l_now_col = 1;
	$l_now_item = 1;
	
	// ���ׂ̊e���ڂ̕���擾���A�e��ōł�傫���l��z��Ɋi�[����
	//alert($(".css_lv_dtl_td > INPUT").size());
	$(".css_lv_dtl_td > INPUT").each(function(){
		// ���݂̍��ڂ̕�
		$l_this_width = $(this).width() * $g_width_adj;
		
		// ���̔�r
		if($lr_header_width[$l_now_col] < $l_this_width){
			// ���݂̍��ڂ̕����L���ꍇ�͔z���㏑��
			$lr_header_width[$l_now_col] = $l_this_width;
		}
		
		// �񐔃C���N�������g
		$l_now_col = $l_now_col + 1;
		if($l_now_col > $l_col_cnt){
			// ���o���̍��ڐ��𒴂����烊�Z�b�g
			$l_now_col = 1;
		}
		
		// ���ڔԍ��C���N�������g
		$l_now_item = $l_now_item + 1;
	});

	// ���o���̊e���ڂ̕���ύX
	$l_now_col = 1;
	$(".css_lv_header_item_textro").each(function(){
		//alert($(this).width());
		$(this).width($lr_header_width[$l_now_col]);
		$l_now_col = $l_now_col + 1;
	});
	
	// ���ׂ̕���ύX
	$l_now_col = 1;
	$l_now_item = 1;
	$(".css_lv_dtl_td > INPUT").each(function(){
		//alert($(this).width());
		$(this).width($lr_header_width[$l_now_col]);
		
		// �񐔃C���N�������g
		$l_now_col = $l_now_col + 1;
		if($l_now_col > $l_col_cnt){
			// ���o���̍��ڐ��𒴂����烊�Z�b�g
			$l_now_col = 1;
		}
		
		// ���ڔԍ��C���N�������g
		$l_now_item = $l_now_item + 1;
	});
	
	// ���s�񐔃C���N�������g
	$("#lv_param1").val(Number($("#lv_param1").val())+1);
}

/*==============================================================================
  �\�����׍i�荞�ݏ���
  �w�b�_�[���ɓ��͂��ꂽ�i�荞�݃L�[���[�h����W���A���ׂ̊e���ڂɂ��āA�Y����
  �̃L�[���[�h��܂�ł��邩��������B
  �L�[���[�h��܂ނ��A�Y����ɃL�[���[�h���ݒ肳��Ă��Ȃ��ꍇ�́A�t���O��C���N
  �������g���A�ŏI�I�Ɋe�s�ɂ����ăt���O�����J�������Ɉ�v�����s�̂ݕ\������B
  ============================================================================*/
function searchDetail($p_this){
	
	// �s����擾���A�t���O�i�[�p�̔z���쐬
	var $l_row_cnt = $(".css_lv_dtl_tr").size();
	var $lr_find_flag = new Array($l_row_cnt);
	//alert($l_row_cnt);
	for($i=0;$i<$l_row_cnt;$i++){
		$lr_find_flag[$i] = 0;
	}
	
	// �񐔂�擾���A�����l����W
	var $l_col_cnt = $(".css_lv_header_item_textw").size();
	var $lr_cond_word = new Array($l_col_cnt);
	//alert($l_col_cnt);
	$l_cnt = 0;
	$l_not_null_flag = 0;
	$(".css_lv_header_item_textw").each(function(){
		$lr_cond_word[$l_cnt] = $(this).val();
		if($(this).val()){
			// �����ꂩ�̌����l��NULL�ȊO�̏ꍇ��NOT NULL�t���O�𗧂Ă�
			$l_not_null_flag = 1;
		}
		$l_cnt++;
	});
	/*
	if($l_not_null_flag==0){
		// �S�Ă̌����l��NULL�̏ꍇ�́A���ׂĂ�\�����ďI��
		$(".css_lv_dtl_tr").slideDown("normal");
		return;
	}
	*/

	// ���ׂ�������A�q�b�g�����ꍇ�̓t���O��C���N�������g
	$l_row_pos = 0;				// �s
	$l_col_pos = 0;				// ��
	$l_item_cnt = 0;			// ���ڒʂ��ԍ�
	//alert($(".css_lv_dtl_td > INPUT").size());
	$(".css_lv_dtl_td > INPUT").each(function(){
		// ���ڂ̒l��擾
		$l_now_value = $(this).val();
		
		// �w�b�_�[���̌����l�Ō������A�q�b�g������Y����̃t���O��C���N�������g
		if($lr_cond_word[$l_col_pos]){
			// �����l��NULL�ȊO�̏ꍇ�ɔ�r��s��
			if($l_now_value.indexOf($lr_cond_word[$l_col_pos]) >= 0){
				$lr_find_flag[$l_row_pos]++;
			}
		}else{
			$lr_find_flag[$l_row_pos]++;
		};
		// �e��C���N�������g
		$l_item_cnt++;
		$l_col_pos++;
		if($l_col_pos==$l_col_cnt){
			// �񐔂ɒB�����烊�Z�b�g���čs�𑝂₷
			$l_col_pos = 0;
			$l_row_pos++;
		}
	});
	
	// �t���O���ƍ��ڐ�����v�������׈ȊO��hide����
	$l_row_pos = 0;				// �s
	//alert($(".css_lv_dtl_tr").size());
	$(".css_lv_dtl_tr").each(function(){
		if($lr_find_flag[$l_row_pos]==$l_col_cnt){
			//alert($l_row_pos+"->"+$lr_find_flag[$l_row_pos]+"-> show");
			//alert($("#"+$l_dtl_tr_prfx+$l_row_pos+" * > INPUT").size());
			//$(this).slideDown("normal");
			$("#"+$l_dtl_tr_prfx+$l_row_pos+" * > INPUT").slideDown("normal");
		}else{
			//alert($l_row_pos+"->"+$lr_find_flag[$l_row_pos]+"-> hide");
			//alert($("#"+$l_dtl_tr_prfx+$l_row_pos+" * > INPUT").size());
			//$(this).slideUp("normal");
			$("#"+$l_dtl_tr_prfx+$l_row_pos+" * > INPUT").slideUp("normal");
		}
		$l_row_pos++;
	});
}

/*==============================================================================
  ��ԍ��؂�o������
  n-n�`���̃A�h���X�����ԍ���؂�o��
  ============================================================================*/
function getColNum($p_addr){
	// 0��Ԃ��ƃG���[�����ɖ�肪�������鋰�ꂪ����̂ŁA�����l��-1�Ƃ���
	var $l_return_num = -1;
	var $l_row_str = "";
	var $l_hf_pos = $p_addr.indexOf("-");			// �n�C�t���̈ʒu(�擪��0)
	
	if($l_hf_pos > 0){
		$l_return_num = $p_addr.substr($l_hf_pos+1);
	}
	return $l_return_num;
}

/*==============================================================================
  ���׎ȁX����
  ============================================================================*/
function setStripes(){
	$(".css_lv_dtl_tr:even > TD > .css_lv_dtl_item_textro").css("backgroundColor", "#D4F2E8");
	//$(".css_lv_dtl_item_textro").css("backgroundColor", "#EDEDCF");
}

/*==============================================================================
  ��ʋN��������
  ============================================================================*/
$(function(){
	var $l_caller_id = $('#_TARGET_ID').val();		//�Ăяo�����̍���ID
	
/*==============================================================================
  �L�����Z���{�^���N���b�N���̏���
  �L�����Z���{�^���ɂ�id=vl_cancelbt����鎖
  ============================================================================*/
	$("#lv_cancelbt").bind("click", function(){
	// ���X�g�����
		self.parent.tb_remove();
	});
	
	
/*==============================================================================
  ���׃X�N���[�����̏���
  ============================================================================*/
	$("#lv_detail").scroll(function(){
		//alert(this.type+" got focus.");
		$l_header_pos = $("#lv_header").scrollLeft();	// �w�b�_�[�̃X�N���[����
		$l_detail_pos = $("#lv_detail").scrollLeft();	// ���ׂ̃X�N���[����
		//$l_mess_value = $l_header_pos + ":" + $l_detail_pos;
		//alert($l_mess_value);
		
		// ���ׂ̈ʒu�Ƀw�b�_�[�̈ʒu����킹��
		$("#lv_header").scrollLeft($("#lv_detail").scrollLeft());
		if($("#lv_header").scrollLeft()!=$("#lv_detail").scrollLeft()){
			// �c�̃X�N���[���o�[������ꍇ�A�E�[�ł��ꂪ�N����̂ŁA�����I�ɓ�������
			$("#lv_detail").scrollLeft($("#lv_header").scrollLeft());
		}
	});

/*==============================================================================
  �w�b�_�[�X�N���[�����̏���
  ============================================================================*/
	$("#lv_header").scroll(function(){
		// ���ׂ̈ʒu��w�b�_�[�ɍ��킹��
		$l_header_pos = $("#lv_header").scrollLeft();	// �w�b�_�[�̃X�N���[����
		$l_detail_pos = $("#lv_detail").scrollLeft();	// ���ׂ̃X�N���[����
		//$l_mess_value = $l_header_pos + ":" + $l_detail_pos;
		//alert($l_mess_value);
		$("#lv_detail").scrollLeft($("#lv_header").scrollLeft());
	});

/*==============================================================================
  ���[�̒l�N���b�N���̏���
  �Ăяo�������ڂɒl��Z�b�g����
  ============================================================================*/
	$(".css_lv_dtl_item_txtret").bind("click", function(){
		// �l�Z�b�g
		$l_put_value = $(this).val();
		self.parent.$(":input#"+$l_caller_id).val($l_put_value);
		self.parent.$(":input#"+$l_caller_id).change();
		// ���X�g�����
		self.parent.tb_remove();
	});

/*==============================================================================
  ���׎ȁX�����N��
  ============================================================================*/
	//setStripes();

/*==============================================================================
  ���׃N���b�N���̏���(���[�ȊO)
  �w�b�_�[���̌����l�ɍ��ڒl��Z�b�g���A�Č�������
  ============================================================================*/
	$(".css_lv_dtl_item_textro").bind("click", function(){
		// �N���b�N���ꂽ���ڂ�ID�����ԍ���擾
		var $l_prfx_len = $l_dtl_id_prfx.length;			// �v���t�B�b�N�X�̕�����
		var $l_item_id = $(this).attr("id");				// ���ڂ�ID
		var $l_addr_str = $l_item_id.substr($l_prfx_len);	// �v���t�B�b�N�X������؂�o��
		var $l_col_num = getColNum($l_addr_str);			// ��ԍ���擾
		
		// �Y���ԍ��̃w�b�_�[���ɒl��Z�b�g
		var $l_target_id = $l_header_id_prfx + $l_col_num;
		$("#"+$l_target_id).val($(this).val());
		
		// �i�荞�ݏ����N��
		searchDetail(this);
	});
	
/*==============================================================================
  �w�b�_�[���{�^���N���b�N������
  ============================================================================*/
	$(".css_lv_header_item_btn").bind("click", function(){
		// �Y���{�^���̉��ɂ���e�L�X�g�{�b�N�X��N���A����
		$(this).next().val('');
		// �i�荞�ݏ����N��
		searchDetail(this);
		// �ȁX�����N��
		//setStripes();
	});

/*==============================================================================
  ���Z�b�g�{�^���N���b�N������
  ============================================================================*/
	$("#lv_resetbt").bind("click", function(){
		// �������ׂăN���A
		$(".css_lv_header_item_textw").val('');
		// �����[�h
		//location.reload();
		// �i�荞�ݏ����N��
		searchDetail(this);
		// �ȁX�����N��
		//setStripes();
	});

/*==============================================================================
  �w�b�_�[���e�L�X�g�ύX������
  ============================================================================*/
	$(".css_lv_header_item_textw").bind("change", function(){
		// �i�荞�ݏ����N��
		searchDetail(this);
		// �ȁX�����N��
		//setStripes();
	});

/*==============================================================================
  �e�[�u���̕��𑵂���
  300�~���b���ƂɋN�����邪�A���ۂ͓���ŃJ�E���g����A2�񂾂��N������
  1�񂾂��̋N���̏ꍇ�A��肭��������Ȃ���
  ============================================================================*/
	$("#lv_param1").val(0);			// �N���񐔂�J�E���g����B�����ڂ�ݒ�
	setInterval('dtl_based_adjust_width(this)',300);

});

