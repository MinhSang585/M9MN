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
							<?php echo form_open('user/permission_setup', array('id' => 'user-form', 'name' => 'user-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<?php if($permissions[PERMISSION_SUB_ACCOUNT_ADD]['upline'] == TRUE OR $permissions[PERMISSION_SUB_ACCOUNT_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_SUB_ACCOUNT_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_sub_account');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SUB_ACCOUNT_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SUB_ACCOUNT_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_SUB_ACCOUNT_ADD;?>" <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_ADD]['upline'] && $permissions[PERMISSION_SUB_ACCOUNT_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SUB_ACCOUNT_ADD;?>">
												<?php echo $this->lang->line('label_add');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SUB_ACCOUNT_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SUB_ACCOUNT_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_SUB_ACCOUNT_UPDATE;?>" <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_UPDATE]['upline'] && $permissions[PERMISSION_SUB_ACCOUNT_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SUB_ACCOUNT_UPDATE;?>">
												<?php echo $this->lang->line('label_update');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SUB_ACCOUNT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SUB_ACCOUNT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SUB_ACCOUNT_VIEW;?>" <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_VIEW]['upline'] && $permissions[PERMISSION_SUB_ACCOUNT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SUB_ACCOUNT_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_USER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_USER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_USER_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_user');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_USER_ADD;?>" <?php echo (($permissions[PERMISSION_USER_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_ADD]['upline'] && $permissions[PERMISSION_USER_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_ADD;?>">
												<?php echo $this->lang->line('label_add');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_USER_UPDATE;?>" <?php echo (($permissions[PERMISSION_USER_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_UPDATE]['upline'] && $permissions[PERMISSION_USER_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_UPDATE;?>">
												<?php echo $this->lang->line('label_update');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_USER_VIEW;?>" <?php echo (($permissions[PERMISSION_USER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_VIEW]['upline'] && $permissions[PERMISSION_USER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_PLAYER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_ADD;?>" <?php echo (($permissions[PERMISSION_PLAYER_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_ADD]['upline'] && $permissions[PERMISSION_PLAYER_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_ADD;?>">
												<?php echo $this->lang->line('label_add');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_UPDATE;?>" <?php echo (($permissions[PERMISSION_PLAYER_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_UPDATE]['upline'] && $permissions[PERMISSION_PLAYER_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_UPDATE;?>">
												<?php echo $this->lang->line('label_update');?> &nbsp; 
												</label>
											</div>
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_VIEW;?>" <?php echo (($permissions[PERMISSION_PLAYER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_VIEW]['upline'] && $permissions[PERMISSION_PLAYER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_PERMISSION_SETUP]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_set_permissions');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PERMISSION_SETUP;?>" name="permissions[]" value="<?php echo PERMISSION_PERMISSION_SETUP;?>" <?php echo (($permissions[PERMISSION_PERMISSION_SETUP]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PERMISSION_SETUP;?>">
												<?php echo $this->lang->line('label_update');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_CHANGE_PASSWORD]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_change_password');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_CHANGE_PASSWORD;?>" name="permissions[]" value="<?php echo PERMISSION_CHANGE_PASSWORD;?>" <?php echo (($permissions[PERMISSION_CHANGE_PASSWORD]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CHANGE_PASSWORD;?>">
												<?php echo $this->lang->line('label_update');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_DEPOSIT_POINT_TO_DOWNLINE]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_deposit_point_to_downline');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_DEPOSIT_POINT_TO_DOWNLINE;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_POINT_TO_DOWNLINE;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_POINT_TO_DOWNLINE]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_POINT_TO_DOWNLINE;?>">
												<?php echo $this->lang->line('label_update');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_withdraw_point_from_downline');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE;?>" <?php echo (($permissions[PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE;?>">
												<?php echo $this->lang->line('label_update');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_VIEW_PLAYER_CONTACT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player_contact');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_VIEW_PLAYER_CONTACT;?>" name="permissions[]" value="<?php echo PERMISSION_VIEW_PLAYER_CONTACT;?>" <?php echo (($permissions[PERMISSION_VIEW_PLAYER_CONTACT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_VIEW_PLAYER_CONTACT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_VIEW_PLAYER_WALLET]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player_wallet');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_VIEW_PLAYER_WALLET;?>" name="permissions[]" value="<?php echo PERMISSION_VIEW_PLAYER_WALLET;?>" <?php echo (($permissions[PERMISSION_VIEW_PLAYER_WALLET]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_VIEW_PLAYER_WALLET;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_PLAYER_WALLET_TRANSFER]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player_wallet_transfer');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_WALLET_TRANSFER;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_WALLET_TRANSFER;?>" <?php echo (($permissions[PERMISSION_PLAYER_WALLET_TRANSFER]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_WALLET_TRANSFER;?>">
												<?php echo $this->lang->line('label_update');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_DEPOSIT_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_deposit');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_DEPOSIT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_VIEW;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_WITHDRAWAL_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_withdrawal');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_WITHDRAWAL_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_VIEW;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player_promotion');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>" <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_PLAYER_BONUS_VIEW]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_player_bonus');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>" <?php echo (($permissions[PERMISSION_PLAYER_BONUS_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_WIN_LOSS_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_win_loss_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_WIN_LOSS_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_WIN_LOSS_REPORT;?>" <?php echo (($permissions[PERMISSION_WIN_LOSS_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WIN_LOSS_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_TRANSACTION_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_transaction_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_TRANSACTION_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_TRANSACTION_REPORT;?>" <?php echo (($permissions[PERMISSION_TRANSACTION_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_TRANSACTION_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_POINT_TRANSACTION_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_point_transaction_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_POINT_TRANSACTION_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_POINT_TRANSACTION_REPORT;?>" <?php echo (($permissions[PERMISSION_POINT_TRANSACTION_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_POINT_TRANSACTION_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_CASH_TRANSACTION_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_cash_transaction_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_CASH_TRANSACTION_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_CASH_TRANSACTION_REPORT;?>" <?php echo (($permissions[PERMISSION_CASH_TRANSACTION_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CASH_TRANSACTION_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_WALLET_TRANSACTION_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_wallet_transaction_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_WALLET_TRANSACTION_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_WALLET_TRANSACTION_REPORT;?>" <?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WALLET_TRANSACTION_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_VERIFY_CODE_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_verify_code_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_VERIFY_CODE_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_VERIFY_CODE_REPORT;?>" <?php echo (($permissions[PERMISSION_VERIFY_CODE_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_VERIFY_CODE_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_LOGIN_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_login_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_LOGIN_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_LOGIN_REPORT;?>" <?php echo (($permissions[PERMISSION_LOGIN_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LOGIN_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php if($permissions[PERMISSION_REWARD_TRANSACTION_REPORT]['upline'] == TRUE):?>
									<div class="form-group row">
										<label class="col-5"><?php echo $this->lang->line('title_reward_transaction_report');?></label>
										<div class="form-group clearfix col-7">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_REWARD_TRANSACTION_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_REWARD_TRANSACTION_REPORT;?>" <?php echo (($permissions[PERMISSION_REWARD_TRANSACTION_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
												<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_REWARD_TRANSACTION_REPORT;?>">
												<?php echo $this->lang->line('label_view');?>
												</label>
											</div>
										</div>
									</div>
									<?php endif;?>
								</div>
								<!-- /.card-body -->
								<div class="card-footer row">
									<div class="col-5">
										<input type="hidden" id="user_id" name="user_id" value="<?php echo (isset($user_id) ? $user_id : '');?>">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
									</div>
									<div class="col-7 text-left pt-1">
										<div class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" id="checkall">
											<label for="checkall" class="custom-control-label font-weight-normal"><?php echo $this->lang->line('label_select_all');?></label>
										</div>
									</div>								
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
			var form = $('#user-form');
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
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
								var message = json.msg;
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									msg_icon = 1;
									parent.layer.close(index);
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate();
			
			$('#checkall:checkbox').change(function () {
				if($(this).attr("checked")) {
					$('input:checkbox').removeAttr('checked');
				}	
				else {
					$('input:checkbox').attr('checked','checked');
				}	
			});
		});
	</script>
</body>
</html>
