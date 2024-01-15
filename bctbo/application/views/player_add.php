<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<!-- form start -->
							<?php echo form_open('player/submit', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-12" id="upline" name="upline">
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_referrer');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-12" id="referrer" name="referrer">
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="" maxlength="16">
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="nickname" name="nickname" value="" maxlength="32">
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="fullname" class="col-5 col-form-label"><?php echo $this->lang->line('label_fullname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="fullname" name="fullname" value="">
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="line_id" class="col-5 col-form-label"><?php echo $this->lang->line('im_line');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="line_id" name="line_id" value="<?php echo (isset($line_id) ? $line_id : '');?>">
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_mobile');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="mobile" name="mobile" value="">
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_EMAIL) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="email" class="col-5 col-form-label"><?php echo $this->lang->line('label_email');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="email" name="email" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="password" class="col-5 col-form-label"><?php echo $this->lang->line('label_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="password" name="password" maxlength="15">
										</div>
									</div>
									<div class="form-group row">
										<label for="passconf" class="col-5 col-form-label"><?php echo $this->lang->line('label_confirm_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="passconf" name="passconf" maxlength="15">
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="win_loss_suspend_limit" class="col-5 col-form-label"><?php echo $this->lang->line('label_win_loss_suspend_limit');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="win_loss_suspend_limit" name="win_loss_suspend_limit" value="99999999999">
										</div>
									</div>
									<div class="form-group row">
										<label for="avatar" class="col-5 col-form-label"><?php echo $this->lang->line('label_avatar');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="avatar" name="avatar">
												<?php
													for($i=0;$i<sizeof($avatar_list);$i++)
													{
														echo '<option value="' . $avatar_list[$i]['avatar_id'] . '">' . $avatar_list[$i]['avatar_name'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<?php if($this->session->userdata('user_type') == USER_SA):?>
									<div class="form-group row">
										<label for="profile_group_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_profile_group');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="profile_group_id" name="profile_group_id">
												<option value="0"><?php echo $this->lang->line('label_select');?></option>
												<?php
													for($i=0;$i<sizeof($player_group_list);$i++)
													{
														echo '<option value="' . $player_group_list[$i]['group_id'] . '">' . $player_group_list[$i]['group_name'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_group_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_group');?></label>
										<div class="col-7">
											<select class="select2 col-12" id="bank_group_id" name="bank_group_id[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php
													for($i=0;$i<sizeof($bank_group_list);$i++)
													{
														echo '<option value="' . $bank_group_list[$i]['group_id'] . '">' . $bank_group_list[$i]['group_name'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<?php endif;?>
									<div class="form-group row d-none">
										<label for="player_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="player_type" name="player_type">
												<?php
													foreach(get_player_type() as $k => $v)
													{
														($k==PLAYER_TYPE_CASH_MARKET)? $select = "selected":$select = "";
														echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="game_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_type');?></label>
										<div class="col-7">
											<select class="select2 col-12" id="game_type" name="game_type[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php
													if(!empty($game_list)){
														foreach ($game_list as $game_list_key => $game_list_value) {
															if(!empty($game_list_value)){
																echo '<optgroup label='.str_replace(' ','-',$this->lang->line('game_type_'.strtolower($game_list_key))).'>';
																foreach($game_list_value as $game_list_value_row){
																	echo '<option value="' . $game_list_value_row."_".$game_list_key . '" alt='.str_replace(' ','-',$this->lang->line('game_type_'.strtolower($game_list_key))).'>' . $this->lang->line('game_'.strtolower($game_list_value_row)) .' ( '.str_replace(' ','-',$this->lang->line('game_type_'.strtolower($game_list_key))).' )'. '</option>';
																}
																echo '</optgroup>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row d-none">
										<div class="col-5">
										</div>
										<div class="col-7">
											<a class="btn btn-primary" id="game_type_select_all" style="color:white;"><?php echo $this->lang->line('label_select_all')?></a>
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="promotion_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="promotion_type" name="promotion_type">
												<?php
													foreach(get_promotion_type() as $k => $v)
													{
														($k==PROMOTION_TYPE_STRICT_BASED)? $select = "selected":$select = "";
														echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="is_promotion" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_allow');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_promotion" name="is_promotion" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row" <?php if($miscellaneous['player_bank_account'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
										<?php if($miscellaneous['player_bank_account'] == STATUS_INACTIVE){ ?>
										<input type="hidden" id="is_player_bank_account" name="is_player_bank_account" value="<?php echo STATUS_ACTIVE; ?>">
										<?php }else{ ?>
										<label for="is_player_bank_account" class="col-5 col-form-label"><?php echo $this->lang->line('label_player_bank_account_allow');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_player_bank_account" name="is_player_bank_account" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
										<?php } ?>
									</div>
									<div class="form-group row" <?php if($miscellaneous['fingerprint_status'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
										<?php if($miscellaneous['fingerprint_status'] == STATUS_INACTIVE){ ?>
										<input type="hidden" id="is_fingerprint" name="is_fingerprint" value="<?php echo STATUS_ACTIVE; ?>">
										<?php }else{ ?>
										<label for="is_fingerprint" class="col-5 col-form-label"><?php echo $this->lang->line('label_fingerprint_allow');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_fingerprint" name="is_fingerprint" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
										<?php } ?>
									</div>
									<div class="form-group row" <?php if($miscellaneous['player_level'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
										<?php if($miscellaneous['player_level'] == STATUS_INACTIVE){ ?>
										<input type="hidden" id="is_level" name="is_level" value="<?php echo STATUS_ACTIVE; ?>">
										<?php }else{ ?>
										<label for="is_level" class="col-5 col-form-label"><?php echo $this->lang->line('label_level_allow');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_level" name="is_level" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
										<?php } ?>
									</div>
									<div class="form-group row">
										<label for="is_offline_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_offline_banking');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_offline_deposit" name="is_offline_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_online_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_online_banking');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_online_deposit" name="is_online_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="game_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_online_banking');?>)</label>
										<div class="col-7">
											<select class="select2 col-12" id="online_deposit_channel" name="online_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php
													$online_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_ONLINE_BANKING);
													if(!empty($online_deposit_channel_data)){
														foreach ($online_deposit_channel_data as $online_deposit_channel_key => $online_deposit_channel_value){
															echo '<option value="' . $online_deposit_channel_key . '">' . $this->lang->line($online_deposit_channel_value) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="is_credit_card_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_credit_card');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_credit_card_deposit" name="is_credit_card_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="game_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_credit_card');?>)</label>
										<div class="col-7">
											<select class="select2 col-12" id="credit_card_deposit_channel" name="credit_card_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php
													$credit_card_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_CREDIT_CARD);
													if(!empty($credit_card_deposit_channel_data)){
														foreach ($credit_card_deposit_channel_data as $credit_card_deposit_channel_key => $credit_card_deposit_channel_value){
															echo '<option value="' . $credit_card_deposit_channel_key . '">' . $this->lang->line($credit_card_deposit_channel_value) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="is_hypermart_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_hypermart');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_hypermart_deposit" name="is_hypermart_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="game_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_hypermart');?>)</label>
										<div class="col-7">
											<select class="select2 col-12" id="hypermart_deposit_channel" name="hypermart_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php
													$hypermart_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_HYPERMART);
													if(!empty($hypermart_deposit_channel_data)){
														foreach ($hypermart_deposit_channel_data as $hypermart_deposit_channel_key => $hypermart_deposit_channel_value){
															echo '<option value="' . $hypermart_deposit_channel_key . '">' . $this->lang->line($hypermart_deposit_channel_value) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
							<?php echo form_close();?>
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- ./wrapper -->
	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>
	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#player-form');
			$('.select2').select2();
			$("#game_type_select_all").click(function(){
	        	$("#game_type").find('option').prop("selected",true);
	        	$("#game_type").trigger('change');
		  	});
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			var index = parent.layer.getFrameIndex(window.name);
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			//Username remote data
			$('#upline').on('change', function() {
				$("#referrer").empty().trigger('change');
			});
			$('#upline').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_upline');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/upline_search');?>',
			        type: "post",
			        dataType: 'json',
			        delay: 250,
			        cache: false,
			        data: function (params) {
			           	return {
			            	csrf_bctp_bo_token : parent.$('meta[name=csrf_token]').attr('content'),
					        search: params.term,
					        page: params.page || 1,
					        length : 10,
					    }
			        },
			        processResults: function (data, params) {
			        	var json = JSON.parse(JSON.stringify(data));
			        	parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
					    params.page = params.page || 1;
					    return {
					        results: data.data,
					        pagination: {
					            more: (params.page * 10) < data.recordsFiltered
					        }
					    };
					}              
			    }
			});
			$('#referrer').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_referrer');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/referrer_search');?>',
			        type: "post",
			        dataType: 'json',
			        delay: 250,
			        cache: false,
			        data: function (params) {
			           	return {
			            	csrf_bctp_bo_token : parent.$('meta[name=csrf_token]').attr('content'),
					        upline: $('#upline').val(),
					        search: params.term,
					        page: params.page || 1,
					        length : 10,
					    }
			        },
			        processResults: function (data, params) {
			        	var json = JSON.parse(JSON.stringify(data));
			        	parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
					    params.page = params.page || 1;
					    return {
					        results: data.data,
					        pagination: {
					            more: (params.page * 10) < data.recordsFiltered
					        }
					    };
					}              
			    }
			});
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						$.ajax({url: form.attr('action'),
							data: form.serialize(),
							type: 'post',                  
							async: 'true',
							beforeSend: function() {
								layer.load(1);
							},
							complete: function() {
								layer.closeAll('loading');
								is_allowed = true;
							},
							success: function (data) {
								var json = JSON.parse(JSON.stringify(data));
								var message = '';
								var msg_icon = 2;
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#player-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.nickname_error != '') {
										message = json.msg.nickname_error;
									}
									else if(json.msg.fullname_error != '') {
										message = json.msg.fullname_error;
									}
									else if(json.msg.mobile_error != '') {
										message = json.msg.mobile_error;
									}
									else if(json.msg.email_error != '') {
										message = json.msg.email_error;
									}
									else if(json.msg.password_error != '') {
										message = json.msg.password_error;
									}
									else if(json.msg.passconf_error != '') {
										message = json.msg.passconf_error;
									}
									else if(json.msg.player_type_error != ''){
										message = json.msg.player_type_error;
									}
									else if(json.msg.win_loss_suspend_limit_error != '') {
										message = json.msg.win_loss_suspend_limit_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			form.validate({
				rules: {
					username: {
						required: true,
						rangelength: [6, 16]
					},
					nickname: {
						//required: true
					},
					fullname: {
						//required: true
					},
					mobile: {
						digits: true
					},
					email: {
						email: true
					},
					password: {
						required: true,
						rangelength: [6, 15]
					},
					passconf: {
						required: true
					},
					player_type:{
						required: true	
					},
					win_loss_suspend_limit: {
						required: true,
						digits: true
					}
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
					},
					nickname: {
						//required: "<?php echo $this->lang->line('error_enter_nickname');?>",
					},
					fullname: {
						//required: "<?php echo $this->lang->line('error_enter_fullname');?>",
					},
					mobile: {
						digits: "<?php echo $this->lang->line('error_invalid_mobile');?>",
					},
					email: {
						email: "<?php echo $this->lang->line('error_invalid_email');?>",
					},
					password: {
						required: "<?php echo $this->lang->line('error_enter_password');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_password');?>",
					},
					passconf: {
						required: "<?php echo $this->lang->line('error_enter_confirm_password');?>",
					},
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
					},
					player_type:{
						required: "<?php echo $this->lang->line('error_select_player_type');?>",
					},
					win_loss_suspend_limit: {
						required: "<?php echo $this->lang->line('error_enter_win_loss_suspend_limit');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_win_loss_suspend_limit')), $this->lang->line('error_only_digits_allowed'));?>",
					}
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
</body>
</html>
