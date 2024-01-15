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
							<div class="card-body">
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($upline) ? $upline : '');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($nickname) ? $nickname : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="fullname" class="col-5 col-form-label"><?php echo $this->lang->line('label_fullname');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($full_name) ? $full_name : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="line_id" class="col-5 col-form-label"><?php echo $this->lang->line('im_line');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($line_id) ? $line_id : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_mobile');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($mobile) && ! empty($mobile)) ? $mobile : '-');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_EMAIL) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="email" class="col-5 col-form-label"><?php echo $this->lang->line('label_email');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($email) && ! empty($email)) ? $email : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="profile_group_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_profile_group');?>:</label>
									<div class="col-7">
										<?php
											$profile_group = '-';
											if(isset($profile_group_id)) 
											{
												for($i=0;$i<sizeof($player_group_list);$i++)
												{
													if($player_group_list[$i]['group_id'] == $profile_group_id) 
													{
														$profile_group = $player_group_list[$i]['group_name'];
														break;
													}
												}
											}
										?>
										<label class="col-form-label font-weight-normal"><?php echo $profile_group;?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_group_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_group');?>:</label>
									<div class="col-7">
										<?php
											$bank_group = '';
											$arr = explode(',', $bank_group_id);
											for($i=0;$i<sizeof($arr);$i++)
											{
												if( ! empty($arr[$i]))
												{
													if(isset($bank_group_list[$arr[$i]])){
														$bank_group .= $bank_group_list[$arr[$i]];
													
														if($i < (sizeof($arr) - 1))
														{
															$bank_group .= ', ';
														}
													}
												}	
											}
											if($bank_group == ''){
												$bank_group = '-';
											}
										?>
										<label class="col-form-label font-weight-normal"><?php echo $bank_group;?></label>
									</div>
								</div>
								<!--
								<div class="form-group row">
									<label for="bank_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?>:</label>
									<div class="col-7">
										<?php
											$bank_name = '-';
											if(isset($bank_id)) 
											{
												for($i=0;$i<sizeof($bank_list);$i++)
												{
													if($bank_list[$i]['bank_id'] == $bank_id) 
													{
														$bank_name = $bank_list[$i]['bank_name'];
														break;
													}
												}
											}
										?>
										<label class="col-form-label font-weight-normal"><?php echo $bank_name;?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_account_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($bank_account_name) && ! empty($bank_account_name)) ? $bank_account_name : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_account_no" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($bank_account_no) && ! empty($bank_account_no)) ? $bank_account_no : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="additional_info" class="col-5 col-form-label"><?php echo $this->lang->line('label_additional_info');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($additional_info) && ! empty($additional_info)) ? $additional_info : '-');?></label>
									</div>
								</div>
								-->
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_referrer');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($referrer) && ! empty($referrer)) ? $referrer : '-');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if($miscellaneous['player_bank_account'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
									<label for="is_player_bank_account" class="col-5 col-form-label"><?php echo $this->lang->line('label_player_bank_account_allow');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($is_player_bank_account) && $is_player_bank_account == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row" <?php if($miscellaneous['fingerprint_status'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
									<label for="is_fingerprint" class="col-5 col-form-label"><?php echo $this->lang->line('label_fingerprint_allow');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($is_fingerprint) && $is_fingerprint == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row" <?php if($miscellaneous['player_level'] == STATUS_INACTIVE){echo 'style="display:none"';}?>>
									<label for="is_level" class="col-5 col-form-label"><?php echo $this->lang->line('label_level_allow');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($is_level) && $is_level == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($active) && $active == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend'));?></label>
									</div>
								</div>																<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_deposit');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_deposit ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_withdraw');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_withdraw ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_bonus');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_bonus ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_transfer_in');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_transfer_in ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_transfer_out');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_transfer_out ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_adjust_in');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_adjustment_in ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_adjust_out');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_adjustment_out ?></label>									</div>								</div>								<div class="form-group row">									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_win_loss');?>:</label>									<div class="col-7">										<label class="col-form-label font-weight-normal"><?= $total_win_loss ?></label>									</div>								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_close');?></button>
							</div>
							<!-- /.card-footer -->
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
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
		});	
	</script>
</body>
</html>
