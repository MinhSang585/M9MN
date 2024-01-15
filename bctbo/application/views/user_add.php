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
							<?php echo form_open('user/submit', array('id' => 'user-form', 'name' => 'user-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('label_type');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-radio d-inline">
												<input class="custom-control-input" type="radio" id="downline_type" name="user_type" value="1" checked>
												<label for="downline_type" class="custom-control-label font-weight-normal"><?php echo $this->lang->line('label_downline');?> &nbsp; </label>
											</div>
											<?php if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE):?>
											<div class="custom-control custom-radio d-none">
												<input class="custom-control-input" type="radio" id="player_type" name="user_type" value="2">
												<label for="player_type" class="custom-control-label font-weight-normal"><?php echo $this->lang->line('label_player');?> &nbsp; </label>
											</div>
											<?php endif;?>
										</div>
									</div>
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="" maxlength="16">
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="domain" class="col-5 col-form-label"><?php echo $this->lang->line('label_user_domain');?></label>
										<div class="col-4">
											<input type="text" class="form-control" id="domain" name="domain" value="" maxlength="16">
										</div>
										<div class="col-3">
											<button type="button" id="button-check-domain" class="btn btn-info"><?php echo $this->lang->line('button_search');?></button>
										</div>
									</div>
									<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
										<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="nickname" name="nickname" value="" maxlength="32">
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
									<div id="form-player" style="display: none;">
										<div class="form-group row">
											<label for="win_loss_suspend_limit" class="col-5 col-form-label"><?php echo $this->lang->line('label_win_loss_suspend_limit');?></label>
											<div class="col-7">
												<input type="text" class="form-control" id="win_loss_suspend_limit" name="win_loss_suspend_limit" value="">
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
										<!--
										<div class="form-group row">
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
										-->
										<div class="form-group row">
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
										<div class="form-group row">
											<div class="col-5">
											</div>
											<div class="col-7">
												<a class="btn btn-primary" id="game_type_select_all" style="color:white;"><?php echo $this->lang->line('label_select_all')?></a>
											</div>
										</div>
										<div class="form-group row">
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
										<div class="form-group row">
											<label for="is_credit_card_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_credit_card');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_credit_card_deposit" name="is_credit_card_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="is_hypermart_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_hypermart');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_hypermart_deposit" name="is_hypermart_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
									</div>
									<div id="form-panel">
										<div class="form-group row">
											<label for="user_role" class="col-5 col-form-label"><?php echo $this->lang->line('label_user_role');?></label>
											<div class="col-7">
												<select class="form-control select2bs4" id="user_role" name="user_role">
													<option value=""><?php echo $this->lang->line('label_select');?></option>
													<?php
														// if(!empty($role_list)){
														// 	foreach ($role_list as $role_list_row){
														// 		if($this->session->userdata('user_type') != 1) { #AGENT
														// 			if($this->session->userdata('user_type') == 2) { #Shareholder
														// 				if($role_list_row['user_role_id'] == 9 || $role_list_row['user_role_id'] == 7) { #SHAREHOLDER ROLE	
														// 					echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
														// 				}
														// 			}
														// 			else if($this->session->userdata('user_type') == 3) { #Agent Head
														// 				if($role_list_row['user_role_id'] == 7) { #AGENT HEAD ROLE	
														// 					echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
														// 				}
														// 			}
														// 			else {
														// 				if($role_list_row['user_role_id'] == 7) { #AGENT ROLE	
														// 					echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
														// 				}	
														// 			}																	
														// 		}
														// 		else { #ADMIN	
														// 			echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
														// 		}
														// 	}	
														// }

														if(!empty($role_list)){
															foreach ($role_list as $role_list_row){
																echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';																
															}	
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label for="white_list_ip" class="col-5 col-form-label"><?php echo $this->lang->line('label_white_list_ip');?></label>
											<div class="col-7">
												<select class="select2 col-12 white_list_ip" id="white_list_ip" name="white_list_ip[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label for="possess" class="col-5 col-form-label"><?php echo $this->lang->line('label_possess');?></label>
											<div class="col-3">
												<select class="select2 col-12 white_list_ip" id="possess" name="possess" data-placeholder="<?php echo $this->lang->line('label_select'); ?>">
													<?php
													foreach ($arrayPossess as $poss) {
														if ($poss == $possess) { ?>
															<option value="<?= $poss ?>" selected><?= $poss ?></option>
														<?php } else { ?>
															<option value="<?= $poss ?>"><?= $poss ?></option>
													<?php }
													} ?>
												</select>
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($possess) ? $possess : '0');?>%</label>
										</div>
										<div class="form-group row">
											<label for="sport_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_sport_comm');?></label>
											<div class="col-3">
												<input type="text" class="form-control" id="sport_comm" name="sport_comm" value="<?php echo (isset($sport_comm) ? $sport_comm : '0.0');?>">
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($sport_comm) ? $sport_comm : '0.0');?>%</label>
										</div>
										<div class="form-group row">
											<label for="casino_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_casino_comm');?></label>
											<div class="col-3">
												<input type="text" class="form-control" id="casino_comm" name="casino_comm" value="<?php echo (isset($casino_comm) ? $casino_comm : '0.0');?>">
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($casino_comm) ? $casino_comm : '0.0');?>%</label>
										</div>
										<div class="form-group row">
											<label for="slots_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_slots_comm');?></label>
											<div class="col-3">
												<input type="text" class="form-control" id="slots_comm" name="slots_comm" value="<?php echo (isset($slots_comm) ? $slots_comm : '0.0');?>">
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($slots_comm) ? $slots_comm : '0.0');?>%</label>
										</div>
										<div class="form-group row">
											<label for="cf_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_cf_comm');?></label>
											<div class="col-3">
												<input type="text" class="form-control" id="cf_comm" name="cf_comm" value="<?php echo (isset($cf_comm) ? $cf_comm : '0.0');?>">
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($cf_comm) ? $cf_comm : '0.0');?>%</label>
										</div>
										<div class="form-group row">
											<label for="other_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_other_comm');?></label>
											<div class="col-3">
												<input type="text" class="form-control" id="other_comm" name="other_comm" value="<?php echo (isset($other_comm) ? $other_comm : '0.0');?>">
											</div>
											<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($other_comm) ? $other_comm : '0.0');?>%</label>
										</div>
										<div class="form-group row">
											<?php echo $this->lang->line('notice_change_possess_and_commission');?>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="stype" name="stype" value="1">
									<input type="hidden" id="upline" name="upline" value="<?php echo (isset($username) ? $username : '');?>">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
							<?php echo form_close();?>
							<?php echo form_open('user/check_domain', array('id' => 'user_check-form', 'name' => 'user-form', 'class' => 'form-horizontal'));?>
								<input type="hidden" class="form-control" id="domain_check" name="domain_check" value="" maxlength="16">
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
			$('.select2').select2();
			$('.select2.white_list_ip').select2({
				tags: true,
				casesensitive: false,
			});
			var is_allowed = true;
			var form = $('#user-form');
			var form_user_check = $('#user_check-form');
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			var index = parent.layer.getFrameIndex(window.name);
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			$('#button-check-domain').click(function() {
				var domain =  $('#domain').val();
				$('#domain_check').val(domain);
				$('#user_check-form').submit();
			});
			$('#user_check-form').submit(function(event) {
				event.preventDefault();
				$.ajax({url: $('#user_check-form').attr('action'),
					data: $('#user_check-form').serialize(),
					datatype: "json",
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
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							message = json.msg;
							msg_icon = 1;
						}
						else {
							if(json.msg.domain_check_error != '') {
								message = json.msg.domain_check_error;
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
								console.log(json);
								var message = '';
								var msg_icon = 2;
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.nickname_error != '') {
										message = json.msg.nickname_error;
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
									else if(json.msg.possess_error != '') {
										message = json.msg.possess_error;
									}
									else if(json.msg.sport_comm_error != '') {
										message = json.msg.sport_comm_error;
									}
									else if(json.msg.casino_comm_error != '') {
										message = json.msg.casino_comm_error;
									}
									else if(json.msg.slots_comm_error != '') {
										message = json.msg.slots_comm_error;
									}
									else if(json.msg.cf_comm_error != '') {
										message = json.msg.slots_comm_error;
									}
									else if(json.msg.other_comm_error != '') {
										message = json.msg.other_comm_error;
									}
									else if(json.msg.white_list_ip_error != '') {
										message = json.msg.white_list_ip_error;
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
						required: true
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
					possess: {
						required: true,
						number: true
					},
					sport_comm: {
						required: true,
						number: true
					},
					casino_comm: {
						required: true,
						number: true
					},
					slots_comm: {
						required: true,
						number: true
					},
					cf_comm: {
						required: true,
						number: true
					},
					other_comm: {
						required: true,
						number: true
					}
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
					},
					nickname: {
						required: "<?php echo $this->lang->line('error_enter_nickname');?>",
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
					possess: {
						required: "<?php echo $this->lang->line('error_invalid_possess');?>",
						number: "<?php echo $this->lang->line('error_invalid_possess');?>",
					},
					sport_comm: {
						required: "<?php echo $this->lang->line('error_invalid_sport_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_sport_comm');?>",
					},
					casino_comm: {
						required: "<?php echo $this->lang->line('error_invalid_casino_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_casino_comm');?>",
					},
					slots_comm: {
						required: "<?php echo $this->lang->line('error_invalid_slots_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_slots_comm');?>",
					},
					cf_comm: {
						required: "<?php echo $this->lang->line('error_invalid_cf_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_cf_comm');?>",
					},
					other_comm: {
						required: "<?php echo $this->lang->line('error_invalid_other_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_other_comm');?>",
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
			<?php if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE):?>
			$('#player_type').click(function() {
				form.attr("action", "<?php echo site_url('player/submit');?>");
				$('#form-panel').hide();
				$('#form-player').show();
			});
			<?php endif;?>
			$('#downline_type').click(function() {
				form.attr("action", "<?php echo site_url('user/submit');?>");
				$('#form-panel').show();
				$('#form-player').hide();
			});
		});
	</script>
</body>
</html>