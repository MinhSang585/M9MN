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
							<?php echo form_open('account/permission_setup', array('id' => 'account-form', 'name' => 'account-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<?php if($this->session->userdata('user_type') == USER_SA):?>
										<?php if($permissions[PERMISSION_MISCELLANEOUS_UPDATE]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_miscellaneous');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_MISCELLANEOUS_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_MISCELLANEOUS_UPDATE;?>" <?php echo (($permissions[PERMISSION_MISCELLANEOUS_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_MISCELLANEOUS_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_HOME]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_home');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_HOME;?>" name="permissions[]" value="<?php echo PERMISSION_HOME;?>" <?php echo (($permissions[PERMISSION_HOME]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_HOME;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_CONTACT_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_CONTACT_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_contact');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CONTACT_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CONTACT_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_CONTACT_UPDATE;?>" <?php echo (($permissions[PERMISSION_CONTACT_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CONTACT_UPDATE]['upline'] == TRUE && $permissions[PERMISSION_CONTACT_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CONTACT_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CONTACT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CONTACT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_CONTACT_VIEW;?>" <?php echo (($permissions[PERMISSION_CONTACT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CONTACT_VIEW]['upline'] && $permissions[PERMISSION_CONTACT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CONTACT_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_SEO_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_SEO_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_seo');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SEO_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SEO_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_SEO_UPDATE;?>" <?php echo (($permissions[PERMISSION_SEO_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SEO_UPDATE]['upline'] && $permissions[PERMISSION_SEO_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SEO_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SEO_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SEO_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SEO_VIEW;?>" <?php echo (($permissions[PERMISSION_SEO_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SEO_VIEW]['upline'] && $permissions[PERMISSION_SEO_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SEO_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_GAME_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_GAME_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_game');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GAME_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GAME_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_GAME_UPDATE;?>" <?php echo (($permissions[PERMISSION_GAME_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GAME_UPDATE]['upline'] && $permissions[PERMISSION_GAME_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GAME_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GAME_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GAME_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_GAME_VIEW;?>" <?php echo (($permissions[PERMISSION_GAME_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GAME_VIEW]['upline'] && $permissions[PERMISSION_GAME_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GAME_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BANNER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BANNER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BANNER_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BANNER_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_banner');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANNER_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANNER_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BANNER_ADD;?>" <?php echo (($permissions[PERMISSION_BANNER_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANNER_ADD]['upline'] && $permissions[PERMISSION_BANNER_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANNER_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANNER_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANNER_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BANNER_UPDATE;?>" <?php echo (($permissions[PERMISSION_BANNER_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANNER_UPDATE]['upline'] && $permissions[PERMISSION_BANNER_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANNER_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANNER_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANNER_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BANNER_DELETE;?>" <?php echo (($permissions[PERMISSION_BANNER_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANNER_DELETE]['upline'] && $permissions[PERMISSION_BANNER_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANNER_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANNER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANNER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BANNER_VIEW;?>" <?php echo (($permissions[PERMISSION_BANNER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANNER_VIEW]['upline'] && $permissions[PERMISSION_BANNER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANNER_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_ANNOUNCEMENT_ADD]['upline'] == TRUE OR $permissions[PERMISSION_ANNOUNCEMENT_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_ANNOUNCEMENT_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_ANNOUNCEMENT_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_announcement');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_ANNOUNCEMENT_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_ANNOUNCEMENT_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_ANNOUNCEMENT_ADD;?>" <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_ADD]['upline'] && $permissions[PERMISSION_ANNOUNCEMENT_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ANNOUNCEMENT_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_ANNOUNCEMENT_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_ANNOUNCEMENT_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_ANNOUNCEMENT_UPDATE;?>" <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_UPDATE]['upline'] && $permissions[PERMISSION_ANNOUNCEMENT_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ANNOUNCEMENT_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_ANNOUNCEMENT_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_ANNOUNCEMENT_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_ANNOUNCEMENT_DELETE;?>" <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_DELETE]['upline'] && $permissions[PERMISSION_ANNOUNCEMENT_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ANNOUNCEMENT_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_ANNOUNCEMENT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_ANNOUNCEMENT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_ANNOUNCEMENT_VIEW;?>" <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_ANNOUNCEMENT_VIEW]['upline'] && $permissions[PERMISSION_ANNOUNCEMENT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ANNOUNCEMENT_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_GROUP_ADD]['upline'] == TRUE OR $permissions[PERMISSION_GROUP_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_GROUP_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_GROUP_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_group');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GROUP_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GROUP_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_GROUP_ADD;?>" <?php echo (($permissions[PERMISSION_GROUP_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GROUP_ADD]['upline'] && $permissions[PERMISSION_GROUP_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GROUP_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GROUP_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GROUP_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_GROUP_UPDATE;?>" <?php echo (($permissions[PERMISSION_GROUP_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GROUP_UPDATE]['upline'] && $permissions[PERMISSION_GROUP_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GROUP_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GROUP_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GROUP_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_GROUP_DELETE;?>" <?php echo (($permissions[PERMISSION_GROUP_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GROUP_DELETE]['upline'] && $permissions[PERMISSION_GROUP_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GROUP_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GROUP_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GROUP_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_GROUP_VIEW;?>" <?php echo (($permissions[PERMISSION_GROUP_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GROUP_VIEW]['upline'] && $permissions[PERMISSION_GROUP_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GROUP_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BANK_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BANK_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_bank');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_ADD;?>" <?php echo (($permissions[PERMISSION_BANK_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_ADD]['upline'] && $permissions[PERMISSION_BANK_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_UPDATE;?>" <?php echo (($permissions[PERMISSION_BANK_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_UPDATE]['upline'] && $permissions[PERMISSION_BANK_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_DELETE;?>" <?php echo (($permissions[PERMISSION_BANK_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_DELETE]['upline'] && $permissions[PERMISSION_BANK_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_VIEW;?>" <?php echo (($permissions[PERMISSION_BANK_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_VIEW]['upline'] && $permissions[PERMISSION_BANK_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BANK_ACCOUNT_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BANK_ACCOUNT_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_ACCOUNT_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_ACCOUNT_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_bank_account');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_ACCOUNT_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_ACCOUNT_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_ACCOUNT_ADD;?>" <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_ADD]['upline'] && $permissions[PERMISSION_BANK_ACCOUNT_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_ACCOUNT_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_ACCOUNT_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_ACCOUNT_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_ACCOUNT_UPDATE;?>" <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_UPDATE]['upline'] && $permissions[PERMISSION_BANK_ACCOUNT_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_ACCOUNT_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_ACCOUNT_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_ACCOUNT_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_ACCOUNT_DELETE;?>" <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_DELETE]['upline'] && $permissions[PERMISSION_BANK_ACCOUNT_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_ACCOUNT_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_ACCOUNT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_ACCOUNT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_ACCOUNT_VIEW;?>" <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_ACCOUNT_VIEW]['upline'] && $permissions[PERMISSION_BANK_ACCOUNT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_ACCOUNT_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BANK_PLAYER_USER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BANK_PLAYER_USER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_PLAYER_USER_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BANK_PLAYER_USER_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_bank_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_PLAYER_USER_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_PLAYER_USER_ADD;?>" <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_ADD]['upline'] && $permissions[PERMISSION_BANK_PLAYER_USER_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_PLAYER_USER_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_PLAYER_USER_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_PLAYER_USER_UPDATE;?>" <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_UPDATE]['upline'] && $permissions[PERMISSION_BANK_PLAYER_USER_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_PLAYER_USER_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_PLAYER_USER_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_PLAYER_USER_DELETE;?>" <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_DELETE]['upline'] && $permissions[PERMISSION_BANK_PLAYER_USER_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_PLAYER_USER_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_PLAYER_USER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_PLAYER_USER_VIEW;?>" <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_PLAYER_USER_VIEW]['upline'] && $permissions[PERMISSION_BANK_PLAYER_USER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_PLAYER_USER_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BANK_PLAYER_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_bank_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BANK_PLAYER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BANK_PLAYER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BANK_PLAYER_VIEW;?>" <?php echo (($permissions[PERMISSION_BANK_PLAYER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BANK_PLAYER_VIEW]['upline'] && $permissions[PERMISSION_BANK_PLAYER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BANK_PLAYER_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_SYSTEM_MESSAGE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_message');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_ADD;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_ADD]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_UPDATE;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_UPDATE]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_DELETE;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_DELETE]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_VIEW;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_VIEW]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_SYSTEM_MESSAGE_USER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_USER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_USER_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_SYSTEM_MESSAGE_USER_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_message_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_USER_ADD;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_ADD]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_USER_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_USER_UPDATE;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_UPDATE]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_USER_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_USER_DELETE;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_DELETE]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_USER_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SYSTEM_MESSAGE_USER_VIEW;?>" <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_SYSTEM_MESSAGE_USER_VIEW]['upline'] && $permissions[PERMISSION_SYSTEM_MESSAGE_USER_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SYSTEM_MESSAGE_USER_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_LEVEL_ADD]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_level');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_ADD;?>" <?php echo (($permissions[PERMISSION_LEVEL_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_ADD]['upline'] && $permissions[PERMISSION_LEVEL_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_UPDATE;?>" <?php echo (($permissions[PERMISSION_LEVEL_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_UPDATE]['upline'] && $permissions[PERMISSION_LEVEL_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_DELETE;?>" <?php echo (($permissions[PERMISSION_LEVEL_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_DELETE]['upline'] && $permissions[PERMISSION_LEVEL_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_VIEW;?>" <?php echo (($permissions[PERMISSION_LEVEL_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_VIEW]['upline'] && $permissions[PERMISSION_LEVEL_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_AVATAR_ADD]['upline'] == TRUE OR $permissions[PERMISSION_AVATAR_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_AVATAR_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_AVATAR_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_avatar');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_AVATAR_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_AVATAR_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_AVATAR_ADD;?>" <?php echo (($permissions[PERMISSION_AVATAR_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_AVATAR_ADD]['upline'] && $permissions[PERMISSION_AVATAR_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_AVATAR_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_AVATAR_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_AVATAR_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_AVATAR_UPDATE;?>" <?php echo (($permissions[PERMISSION_AVATAR_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_AVATAR_UPDATE]['upline'] && $permissions[PERMISSION_AVATAR_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_AVATAR_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_AVATAR_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_AVATAR_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_AVATAR_DELETE;?>" <?php echo (($permissions[PERMISSION_AVATAR_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_AVATAR_DELETE]['upline'] && $permissions[PERMISSION_AVATAR_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_AVATAR_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_AVATAR_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_AVATAR_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_AVATAR_VIEW;?>" <?php echo (($permissions[PERMISSION_AVATAR_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_AVATAR_VIEW]['upline'] && $permissions[PERMISSION_AVATAR_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_AVATAR_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_CURRENCIES_ADD]['upline'] == TRUE OR $permissions[PERMISSION_CURRENCIES_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_CURRENCIES_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_CURRENCIES_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_currencies');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CURRENCIES_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CURRENCIES_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_CURRENCIES_ADD;?>" <?php echo (($permissions[PERMISSION_CURRENCIES_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CURRENCIES_ADD]['upline'] && $permissions[PERMISSION_CURRENCIES_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CURRENCIES_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CURRENCIES_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CURRENCIES_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_CURRENCIES_UPDATE;?>" <?php echo (($permissions[PERMISSION_CURRENCIES_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CURRENCIES_UPDATE]['upline'] && $permissions[PERMISSION_CURRENCIES_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CURRENCIES_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CURRENCIES_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CURRENCIES_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_CURRENCIES_DELETE;?>" <?php echo (($permissions[PERMISSION_CURRENCIES_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CURRENCIES_DELETE]['upline'] && $permissions[PERMISSION_CURRENCIES_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CURRENCIES_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_CURRENCIES_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_CURRENCIES_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_CURRENCIES_VIEW;?>" <?php echo (($permissions[PERMISSION_CURRENCIES_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_CURRENCIES_VIEW]['upline'] && $permissions[PERMISSION_CURRENCIES_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_CURRENCIES_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_FINGERPRINT_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_fingerprint');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_FINGERPRINT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_FINGERPRINT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_FINGERPRINT_VIEW;?>" <?php echo (($permissions[PERMISSION_FINGERPRINT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_FINGERPRINT_VIEW]['upline'] && $permissions[PERMISSION_FINGERPRINT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_AVATAR_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PROMOTION_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PROMOTION_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PROMOTION_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_PROMOTION_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_promotion');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PROMOTION_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PROMOTION_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_PROMOTION_ADD;?>" <?php echo (($permissions[PERMISSION_PROMOTION_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PROMOTION_ADD]['upline'] && $permissions[PERMISSION_PROMOTION_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PROMOTION_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PROMOTION_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PROMOTION_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PROMOTION_UPDATE;?>" <?php echo (($permissions[PERMISSION_PROMOTION_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PROMOTION_UPDATE]['upline'] && $permissions[PERMISSION_PROMOTION_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PROMOTION_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PROMOTION_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PROMOTION_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_PROMOTION_DELETE;?>" <?php echo (($permissions[PERMISSION_PROMOTION_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PROMOTION_DELETE]['upline'] && $permissions[PERMISSION_PROMOTION_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PROMOTION_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PROMOTION_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PROMOTION_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PROMOTION_VIEW;?>" <?php echo (($permissions[PERMISSION_PROMOTION_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PROMOTION_VIEW]['upline'] && $permissions[PERMISSION_PROMOTION_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PROMOTION_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BONUS_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BONUS_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BONUS_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BONUS_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_bonus');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BONUS_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BONUS_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BONUS_ADD;?>" <?php echo (($permissions[PERMISSION_BONUS_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BONUS_ADD]['upline'] && $permissions[PERMISSION_BONUS_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BONUS_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BONUS_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BONUS_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BONUS_UPDATE;?>" <?php echo (($permissions[PERMISSION_BONUS_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BONUS_UPDATE]['upline'] && $permissions[PERMISSION_BONUS_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BONUS_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BONUS_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BONUS_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BONUS_DELETE;?>" <?php echo (($permissions[PERMISSION_BONUS_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BONUS_DELETE]['upline'] && $permissions[PERMISSION_BONUS_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BONUS_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BONUS_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BONUS_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BONUS_VIEW;?>" <?php echo (($permissions[PERMISSION_BONUS_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BONUS_VIEW]['upline'] && $permissions[PERMISSION_BONUS_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BONUS_VIEW;?>">
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
										<?php if($permissions[PERMISSION_PLAYER_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_VIEW]['upline'] == TRUE OR $permissions[PERMISSION_VIEW_PLAYER_CONTACT_V2]['upline'] == TRUE):?>
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
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_VIEW_PLAYER_CONTACT_V2]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_VIEW_PLAYER_CONTACT_V2;?>" name="permissions[]" value="<?php echo PERMISSION_VIEW_PLAYER_CONTACT_V2;?>" <?php echo (($permissions[PERMISSION_VIEW_PLAYER_CONTACT_V2]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_VIEW_PLAYER_CONTACT_V2]['upline'] && $permissions[PERMISSION_VIEW_PLAYER_CONTACT_V2]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_VIEW_PLAYER_CONTACT_V2;?>">
													<?php echo $this->lang->line('label_view');?>2
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
										<?php if($permissions[PERMISSION_PLAYER_POINT_ADJUSTMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_point_adjustment');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_POINT_ADJUSTMENT;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_POINT_ADJUSTMENT;?>" <?php echo (($permissions[PERMISSION_PLAYER_POINT_ADJUSTMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_POINT_ADJUSTMENT;?>">
													<?php echo $this->lang->line('label_update');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_KICK_PLAYER]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_kick_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_KICK_PLAYER;?>" name="permissions[]" value="<?php echo PERMISSION_KICK_PLAYER;?>" <?php echo (($permissions[PERMISSION_KICK_PLAYER]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_KICK_PLAYER;?>">
													<?php echo $this->lang->line('label_update');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_DEPOSIT_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_DEPOSIT_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_deposit');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_UPDATE;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_UPDATE]['upline'] && $permissions[PERMISSION_DEPOSIT_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_VIEW;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_VIEW]['upline'] && $permissions[PERMISSION_DEPOSIT_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WITHDRAWAL_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_WITHDRAWAL_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_withdrawal');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_UPDATE;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_UPDATE]['upline'] && $permissions[PERMISSION_WITHDRAWAL_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_VIEW;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_VIEW]['upline'] && $permissions[PERMISSION_WITHDRAWAL_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PLAYER_PROMOTION_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_PROMOTION_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_promotion');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_PROMOTION_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_PROMOTION_ADD;?>" <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_ADD]['upline'] && $permissions[PERMISSION_PLAYER_PROMOTION_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_PROMOTION_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_PROMOTION_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_PROMOTION_UPDATE;?>" <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_UPDATE]['upline'] && $permissions[PERMISSION_PLAYER_PROMOTION_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_PROMOTION_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>" <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['upline'] && $permissions[PERMISSION_PLAYER_PROMOTION_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_PROMOTION_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PLAYER_BONUS_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_BONUS_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_BONUS_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_bonus');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_BONUS_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_BONUS_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_BONUS_ADD;?>" <?php echo (($permissions[PERMISSION_PLAYER_BONUS_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_BONUS_ADD]['upline'] && $permissions[PERMISSION_PLAYER_BONUS_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_BONUS_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_BONUS_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_BONUS_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_BONUS_UPDATE;?>" <?php echo (($permissions[PERMISSION_PLAYER_BONUS_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_BONUS_UPDATE]['upline'] && $permissions[PERMISSION_PLAYER_BONUS_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_BONUS_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_BONUS_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>" <?php echo (($permissions[PERMISSION_PLAYER_BONUS_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_BONUS_VIEW]['upline'] && $permissions[PERMISSION_PLAYER_BONUS_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_BONUS_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_MATCH_ADD]['upline'] == TRUE OR $permissions[PERMISSION_MATCH_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_MATCH_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_MATCH_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_match');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_MATCH_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_MATCH_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_MATCH_ADD;?>" <?php echo (($permissions[PERMISSION_MATCH_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_MATCH_ADD]['upline'] && $permissions[PERMISSION_MATCH_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_MATCH_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_MATCH_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_MATCH_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_MATCH_UPDATE;?>" <?php echo (($permissions[PERMISSION_MATCH_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_MATCH_UPDATE]['upline'] && $permissions[PERMISSION_MATCH_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_MATCH_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_MATCH_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_MATCH_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_MATCH_DELETE;?>" <?php echo (($permissions[PERMISSION_MATCH_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_MATCH_DELETE]['upline'] && $permissions[PERMISSION_MATCH_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_MATCH_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_MATCH_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_MATCH_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_MATCH_VIEW;?>" <?php echo (($permissions[PERMISSION_MATCH_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_MATCH_VIEW]['upline'] && $permissions[PERMISSION_MATCH_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_MATCH_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>

										<?php if($permissions[PERMISSION_LEVEL_EXECUTE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_EXECUTE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_EXECUTE_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_EXECUTE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_ranking_execute');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_EXECUTE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_EXECUTE_ADD;?>" <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_ADD]['upline'] && $permissions[PERMISSION_LEVEL_EXECUTE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_EXECUTE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_EXECUTE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_EXECUTE_UPDATE;?>" <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_UPDATE]['upline'] && $permissions[PERMISSION_LEVEL_EXECUTE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_EXECUTE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_EXECUTE_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_EXECUTE_DELETE;?>" <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_DELETE]['upline'] && $permissions[PERMISSION_LEVEL_EXECUTE_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_EXECUTE_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_EXECUTE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_EXECUTE_VIEW;?>" <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_EXECUTE_VIEW]['upline'] && $permissions[PERMISSION_LEVEL_EXECUTE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_EXECUTE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>


										<?php if($permissions[PERMISSION_LEVEL_LOG_VIEW]['upline'] == TRUE OR $permissions[PERMISSION_LEVEL_LOG_UPDATE]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_level_log');?></label>
											<div class="form-group clearfix col-7">
												
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_LOG_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_LOG_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_LOG_UPDATE;?>" <?php echo (($permissions[PERMISSION_LEVEL_LOG_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_LOG_UPDATE]['upline'] && $permissions[PERMISSION_LEVEL_LOG_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_LOG_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_LEVEL_LOG_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_LEVEL_LOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_LEVEL_LOG_VIEW;?>" <?php echo (($permissions[PERMISSION_LEVEL_LOG_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_LEVEL_LOG_VIEW]['upline'] && $permissions[PERMISSION_LEVEL_LOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_LEVEL_LOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>

										<?php if($permissions[PERMISSION_REWARD_DEDUCT]['upline'] == TRUE OR $permissions[PERMISSION_REWARD_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_REWARD_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_ranking_execute');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_REWARD_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_REWARD_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_REWARD_UPDATE;?>" <?php echo (($permissions[PERMISSION_REWARD_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_REWARD_UPDATE]['upline'] && $permissions[PERMISSION_REWARD_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_REWARD_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_REWARD_DEDUCT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_REWARD_DEDUCT;?>" name="permissions[]" value="<?php echo PERMISSION_REWARD_DEDUCT;?>" <?php echo (($permissions[PERMISSION_REWARD_DEDUCT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_REWARD_DEDUCT]['upline'] && $permissions[PERMISSION_REWARD_DEDUCT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_REWARD_DEDUCT;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_REWARD_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_REWARD_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_REWARD_VIEW;?>" <?php echo (($permissions[PERMISSION_REWARD_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_REWARD_VIEW]['upline'] && $permissions[PERMISSION_REWARD_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_REWARD_VIEW;?>">
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
										<?php if($permissions[PERMISSION_PLAYER_DAILY_REPORT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_daily_report');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_DAILY_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_DAILY_REPORT;?>" <?php echo (($permissions[PERMISSION_PLAYER_DAILY_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_DAILY_REPORT;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_VIEW]['upline'] == TRUE OR $permissions[PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('transfer_transaction_wallet_adjust');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE;?>" <?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE]['upline'] && $permissions[PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_VIEW;?>" <?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WALLET_TRANSACTION_PENDING_VIEW]['upline'] && $permissions[PERMISSION_WALLET_TRANSACTION_PENDING_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WALLET_TRANSACTION_PENDING_VIEW;?>">
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
										<?php if($permissions[PERMISSION_PLAYER_RISK_REPORT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_risk_report');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_RISK_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_RISK_REPORT;?>" <?php echo (($permissions[PERMISSION_PLAYER_RISK_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_RISK_REPORT;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PAYMENT_GATEWAY_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PAYMENT_GATEWAY_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_payment_gateway');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_UPDATE;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_UPDATE]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_VIEW;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_VIEW]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_ADMIN_LOG_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_admin_log');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_ADMIN_LOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_ADMIN_LOG_VIEW;?>" <?php echo (($permissions[PERMISSION_ADMIN_LOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ADMIN_LOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_ADMIN_PLAYER_LOG_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_admin_player_log');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_ADMIN_PLAYER_LOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_ADMIN_PLAYER_LOG_VIEW;?>" <?php echo (($permissions[PERMISSION_ADMIN_PLAYER_LOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_ADMIN_PLAYER_LOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_SUB_ACCOUNT_LOG_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_sub_account_log');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_SUB_ACCOUNT_LOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SUB_ACCOUNT_LOG_VIEW;?>" <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_LOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SUB_ACCOUNT_LOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_sub_account_player_log');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW;?>" <?php echo (($permissions[PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_GAME_MAINTENANCE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_GAME_MAINTENANCE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_GAME_MAINTENANCE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_ranking_execute');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GAME_MAINTENANCE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_GAME_MAINTENANCE_ADD;?>" <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_ADD]['upline'] && $permissions[PERMISSION_GAME_MAINTENANCE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GAME_MAINTENANCE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GAME_MAINTENANCE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_GAME_MAINTENANCE_UPDATE;?>" <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_UPDATE]['upline'] && $permissions[PERMISSION_GAME_MAINTENANCE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GAME_MAINTENANCE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_GAME_MAINTENANCE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_GAME_MAINTENANCE_VIEW;?>" <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_GAME_MAINTENANCE_VIEW]['upline'] && $permissions[PERMISSION_GAME_MAINTENANCE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_GAME_MAINTENANCE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_ranking_execute');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW;?>" <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW]['upline'] && $permissions[PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_deposit_offline');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_deposit_online');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_deposit_credit_card');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_deposit_hypermart');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_withdrawal');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLACKLIST_ANNOUNCEMENT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('announcement_blacklist');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLACKLIST_ANNOUNCEMENT]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLACKLIST_ANNOUNCEMENT;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_ANNOUNCEMENT;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_ANNOUNCEMENT]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLACKLIST_ANNOUNCEMENT]['upline'] && $permissions[PERMISSION_BLACKLIST_ANNOUNCEMENT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_ANNOUNCEMENT;?>">
													<?php echo $this->lang->line('label_view');?> &nbsp; 
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WHITELIST_ADD]['upline'] == TRUE OR $permissions[PERMISSION_WHITELIST_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_WHITELIST_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_WHITELIST_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_whitelist');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WHITELIST_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WHITELIST_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_WHITELIST_ADD;?>" <?php echo (($permissions[PERMISSION_WHITELIST_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WHITELIST_ADD]['upline'] && $permissions[PERMISSION_WHITELIST_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WHITELIST_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WHITELIST_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WHITELIST_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_WHITELIST_UPDATE;?>" <?php echo (($permissions[PERMISSION_WHITELIST_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WHITELIST_UPDATE]['upline'] && $permissions[PERMISSION_WHITELIST_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WHITELIST_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WHITELIST_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WHITELIST_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_WHITELIST_DELETE;?>" <?php echo (($permissions[PERMISSION_WHITELIST_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WHITELIST_DELETE]['upline'] && $permissions[PERMISSION_WHITELIST_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WHITELIST_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WHITELIST_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WHITELIST_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_WHITELIST_VIEW;?>" <?php echo (($permissions[PERMISSION_WHITELIST_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WHITELIST_VIEW]['upline'] && $permissions[PERMISSION_WHITELIST_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WHITELIST_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLACKLIST_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BLACKLIST_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BLACKLIST_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BLACKLIST_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_blacklist');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLACKLIST_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLACKLIST_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_ADD;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLACKLIST_ADD]['upline'] && $permissions[PERMISSION_BLACKLIST_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLACKLIST_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLACKLIST_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_UPDATE;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLACKLIST_UPDATE]['upline'] && $permissions[PERMISSION_BLACKLIST_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLACKLIST_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLACKLIST_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_DELETE;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLACKLIST_DELETE]['upline'] && $permissions[PERMISSION_BLACKLIST_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLACKLIST_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLACKLIST_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_VIEW;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLACKLIST_VIEW]['upline'] && $permissions[PERMISSION_BLACKLIST_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLACKLIST_REPORT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_blacklist_report');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_BLACKLIST_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_BLACKLIST_REPORT;?>" <?php echo (($permissions[PERMISSION_BLACKLIST_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLACKLIST_REPORT;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLOG_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_blog');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_ADD;?>" <?php echo (($permissions[PERMISSION_BLOG_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_ADD]['upline'] && $permissions[PERMISSION_BLOG_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_UPDATE;?>" <?php echo (($permissions[PERMISSION_BLOG_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_UPDATE]['upline'] && $permissions[PERMISSION_BLOG_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_DELETE;?>" <?php echo (($permissions[PERMISSION_BLOG_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_DELETE]['upline'] && $permissions[PERMISSION_BLOG_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_VIEW;?>" <?php echo (($permissions[PERMISSION_BLOG_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_VIEW]['upline'] && $permissions[PERMISSION_BLOG_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLOG_FRONTEND_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_blacklist_report');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_BLOG_FRONTEND_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_FRONTEND_VIEW;?>" <?php echo (($permissions[PERMISSION_BLOG_FRONTEND_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_FRONTEND_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_BLOG_CATEGORY_ADD]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_CATEGORY_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_CATEGORY_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_BLOG_CATEGORY_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_blog');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_CATEGORY_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_CATEGORY_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_CATEGORY_ADD;?>" <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_ADD]['upline'] && $permissions[PERMISSION_BLOG_CATEGORY_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_CATEGORY_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_CATEGORY_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_CATEGORY_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_CATEGORY_UPDATE;?>" <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_UPDATE]['upline'] && $permissions[PERMISSION_BLOG_CATEGORY_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_CATEGORY_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_CATEGORY_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_CATEGORY_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_CATEGORY_DELETE;?>" <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_DELETE]['upline'] && $permissions[PERMISSION_BLOG_CATEGORY_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_CATEGORY_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_BLOG_CATEGORY_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_BLOG_CATEGORY_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_BLOG_CATEGORY_VIEW;?>" <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_BLOG_CATEGORY_VIEW]['upline'] && $permissions[PERMISSION_BLOG_CATEGORY_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_BLOG_CATEGORY_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_USER_ROLE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_USER_ROLE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_USER_ROLE_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_USER_ROLE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_user_role');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_ROLE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_ROLE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_USER_ROLE_ADD;?>" <?php echo (($permissions[PERMISSION_USER_ROLE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_ROLE_ADD]['upline'] && $permissions[PERMISSION_USER_ROLE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_ROLE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_ROLE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_ROLE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_USER_ROLE_UPDATE;?>" <?php echo (($permissions[PERMISSION_USER_ROLE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_ROLE_UPDATE]['upline'] && $permissions[PERMISSION_USER_ROLE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_ROLE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_ROLE_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_ROLE_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_USER_ROLE_DELETE;?>" <?php echo (($permissions[PERMISSION_USER_ROLE_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_ROLE_DELETE]['upline'] && $permissions[PERMISSION_USER_ROLE_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_ROLE_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_USER_ROLE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_USER_ROLE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_USER_ROLE_VIEW;?>" <?php echo (($permissions[PERMISSION_USER_ROLE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_USER_ROLE_VIEW]['upline'] && $permissions[PERMISSION_USER_ROLE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_USER_ROLE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_additional_info');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO;?>" <?php echo (($permissions[PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO;?>">
													<?php echo $this->lang->line('label_update');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PLAYER_BANK_IMAGE]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player_bank_image');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_PLAYER_BANK_IMAGE;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_BANK_IMAGE;?>" <?php echo (($permissions[PERMISSION_PLAYER_BANK_IMAGE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_BANK_IMAGE;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_ADD]['upline'] == TRUE OR $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE]['upline'] == TRUE OR $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_DELETE]['upline'] == TRUE OR $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_VIEW]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_withdrawal_fee_setting');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_ADD]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_ADD;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_ADD;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_ADD]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_ADD]['upline'] && $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_ADD]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_ADD;?>">
													<?php echo $this->lang->line('label_add');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE]['upline'] && $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE;?>">
													<?php echo $this->lang->line('label_update');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_DELETE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_DELETE;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_DELETE;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_DELETE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_DELETE]['upline'] && $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_DELETE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_DELETE;?>">
													<?php echo $this->lang->line('label_delete');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_VIEW]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_VIEW;?>" name="permissions[]" value="<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_VIEW;?>" <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_VIEW]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_WITHDRAWAL_FEE_RATE_VIEW]['upline'] && $permissions[PERMISSION_WITHDRAWAL_FEE_RATE_VIEW]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WITHDRAWAL_FEE_RATE_VIEW;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_WIN_LOSS_REPORT_PLAYER]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_win_loss_report_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_WIN_LOSS_REPORT_PLAYER;?>" name="permissions[]" value="<?php echo PERMISSION_WIN_LOSS_REPORT_PLAYER;?>" <?php echo (($permissions[PERMISSION_WIN_LOSS_REPORT_PLAYER]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_WIN_LOSS_REPORT_PLAYER;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_YEARLY_REPORT]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_yearly_report');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="checkbox" id="permission_<?php echo PERMISSION_YEARLY_REPORT;?>" name="permissions[]" value="<?php echo PERMISSION_YEARLY_REPORT;?>" <?php echo (($permissions[PERMISSION_YEARLY_REPORT]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_YEARLY_REPORT;?>">
													<?php echo $this->lang->line('label_view');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php if($permissions[PERMISSION_PLAYER_MOBILE]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_LINE_ID]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_NICKNAME]['upline'] == TRUE OR $permissions[PERMISSION_PLAYER_EMAIL]['upline'] == TRUE):?>
										<div class="form-group row">
											<label class="col-5"><?php echo $this->lang->line('title_player');?></label>
											<div class="form-group clearfix col-7">
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_MOBILE]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_MOBILE;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_MOBILE;?>" <?php echo (($permissions[PERMISSION_PLAYER_MOBILE]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_MOBILE]['upline'] && $permissions[PERMISSION_PLAYER_MOBILE]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_MOBILE;?>">
													<?php echo $this->lang->line('label_mobile');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_LINE_ID]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_LINE_ID;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_LINE_ID;?>" <?php echo (($permissions[PERMISSION_PLAYER_LINE_ID]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_LINE_ID]['upline'] && $permissions[PERMISSION_PLAYER_LINE_ID]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_LINE_ID;?>">
													<?php echo $this->lang->line('im_line');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_NICKNAME]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_NICKNAME;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_NICKNAME;?>" <?php echo (($permissions[PERMISSION_PLAYER_NICKNAME]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_NICKNAME]['upline'] && $permissions[PERMISSION_PLAYER_NICKNAME]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_NICKNAME;?>">
													<?php echo $this->lang->line('label_nickname');?> &nbsp; 
													</label>
												</div>
												<div class="custom-control custom-checkbox d-inline">
													<input class="custom-control-input" type="<?php echo (($permissions[PERMISSION_PLAYER_EMAIL]['upline'] == FALSE) ? 'button' : 'checkbox');?>" id="permission_<?php echo PERMISSION_PLAYER_EMAIL;?>" name="permissions[]" value="<?php echo PERMISSION_PLAYER_EMAIL;?>" <?php echo (($permissions[PERMISSION_PLAYER_EMAIL]['upline'] == FALSE) ? 'disabled' : '');?> <?php echo (($permissions[PERMISSION_PLAYER_EMAIL]['upline'] && $permissions[PERMISSION_PLAYER_EMAIL]['downline'] === TRUE) ? 'checked' : '');?>>
													<label class="custom-control-label font-weight-normal" for="permission_<?php echo PERMISSION_PLAYER_EMAIL;?>">
													<?php echo $this->lang->line('label_email');?>
													</label>
												</div>
											</div>
										</div>
										<?php endif;?>
									<?php else:?>
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
									<?php endif;?>
								</div>
								<!-- /.card-body -->
								<div class="card-footer row">
									<div class="col-5">
										<input type="hidden" id="sub_account_id" name="sub_account_id" value="<?php echo (isset($sub_account_id) ? $sub_account_id : '');?>">
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
			var form = $('#account-form');
			
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
