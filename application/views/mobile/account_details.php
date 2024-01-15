<?php $this->load->view('mobile/parts/header'); ?>

<section class="main my-account">
	<div class="my-account__content">
		<div class="my-account__content__topcontent">
			<a href="<?php echo site_url('message')?>" class="my-account__content__topcontent__nav pb-4">
				<img src="<?php echo base_url('assets/mobile/img/sidemenu_icon_inbox.png')?>" alt="" width="25px">
			</a>
			<div class="my-account__content__topcontent__top">
				<img class="my-account__content__topcontent__top__avatar" src="<?php echo base_url('assets/mobile/img/1.png')?>" alt="">
				<a class="my-account__content__topcontent__top__user-info" href="<?php echo site_url('account/profile')?>">
					<div>
						<h4> <?php echo $this->session->userdata('username')?></h4>
						<div><?php echo $this->lang->line('label_join_since');echo date('d-m-y',$player['created_date']) ?></div>
					</div>
				</a>
			</div>
			<div class="my-account__content__topcontent__balance">
				<div>
					<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_main_icon_balance.png')?>" class="img-fluid">
					<span class="mx-1"><?php echo $this->lang->line('label_total_balance')?></span>
				</div>
				<h4><span class="bal_main">0.00</span></h4>
			</div>
			<div class="my-account__content__topcontent__wallet">

				<div class="my-account__content__topcontent__wallet__bottom">
					<a href="<?php echo site_url('account/wallet')?>">
						<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_main_btm_icon_mywallet.png')?>">
						<div><?php echo $this->lang->line('label_my')?><br><?php echo $this->lang->line('label_wallet')?></div>
					</a>
					<a href="<?php echo site_url('account/transaction_history')?>">
						<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_main_btm_icon_history.png')?>">
						<div><?php echo $this->lang->line('label_transaction')?><br><?php echo $this->lang->line('label_history')?></div>
					</a>
					<a href="<?php echo site_url('account/transaction_history')?>" class="d-none">
						<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_main_btm_icon_record.png')?>">
						<div><?php echo $this->lang->line('label_bet')?><br> <?php echo $this->lang->line('label_record')?></div>
					</a>
					<a href="<?php echo site_url('account/profile')?>">
						<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_main_btm_icon_accsetting.png')?>">
						<div><?php echo $this->lang->line('label_account')?><br> <?php echo $this->lang->line('label_setting')?></div>
					</a>
				</div>
			</div>
		</div>
		<ul class="my-account__content__navbar">

			<li>
				<a href="javascript:void(0)" onclick="alert('Coming Soon!');">
					<div>
						<div class="icon"><img src="<?php echo base_url('assets/mobile/img/user_center/myacc_menu_icon_bonuscenter.png')?>"></div>
						<div><?php echo $this->lang->line('label_bonus_center')?></div>
					</div>
					<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_menu_icon_arrow.png')?>">
				</a>
			</li>
			<li>
				<a href="<?php echo site_url('help'); ?>">
					<div>
						<div class="icon"><img src="<?php echo base_url('assets/mobile/img/user_center/myacc_menu_icon_help.png')?>"></div>
						<div><?php echo $this->lang->line('label_help')?></div>
					</div>
					<img src="<?php echo base_url('assets/mobile/img/user_center/myacc_menu_icon_arrow.png')?>">
				</a>
			</li>
		</ul>
	</div>
</section>
<div class="d-none container pb-10vh">
	<div class="divprofile infos text-dark">
		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="fas fa-user-alt fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<span class="control-label" for="fr_username"><?php echo $this->lang->line('label_username'); ?>:&nbsp;&nbsp;</span>
					<span><?php echo $this->session->userdata('username'); ?></span>
				</div>
				<div class="mb-2">
					<span class="control-label" for="fr_firstname"><?php echo $this->lang->line('label_full_name'); ?> :</span>
					<span id="fr_firstname"><?php echo $player['full_name']; ?></span>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="far fa-envelope fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<?php
					if ($player['email'] == null) {
						echo form_open('ajax/email', array('id' => 'email-form', 'autocomplete' => 'off'));
					?>
						<div class="row justify-content-center align-items-center g-0">
							<div class="col-8">
								<input type="text" name="email" class="form-control text-small" id="email">
							</div>
							<div class="col-4">
								<button type="submit" id="submit" name="submit" class="btn col-sm-2 btn-sm btn-warning ms-1"> <?php echo $this->lang->line('label_update'); ?>
							</div>
						</div>
					<?php
						echo form_close();
					} else { ?>
						<span class="control-label" for="fr_email"><?php echo $this->lang->line('label_email'); ?>:&nbsp;&nbsp;</span>
						<span id="fr_email"><?php echo mask_email($player['email'], 2, 3); ?></span>

					<?php } ?>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="fas fa-phone-square-alt fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<span class="control-label" for="fr_mobile"><?php echo $this->lang->line('label_mobile'); ?>:&nbsp;&nbsp;</span>
					<span id="fr_mobile"><?php echo mask_email($player['mobile'], 2, 4); ?></span>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="fas fa-birthday-cake fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">

					<?php
					if ($player['dob'] == null) {
						echo form_open('ajax/dob', array('id' => 'dob-form', 'autocomplete' => 'off'));
					?>
						<div class="row justify-content-center align-items-center g-0">
							<div class="col-8">
								<input type="text" name="dob" class="form-control text-small" id="dob" readonly>
							</div>
							<div class="col-4">
								<button type="submit" id="submit" name="submit" class="btn col-sm-2 btn-sm btn-warning ms-1"> <?php echo $this->lang->line('label_update'); ?>
							</div>
						</div>
					<?php
						echo form_close();
					} else { ?>
						<span class="control-label" for="fr_dob"><?php echo $this->lang->line('label_dob'); ?>:&nbsp;&nbsp;</span>
						<span id="fr_dob"><?php echo date('d-m-Y', $player['dob']); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="fas fa-lock fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_1" aria-expanded="true" aria-controls="collapseOne">
								<span for="fr_bankacc"><?php echo $this->lang->line('label_security'); ?>
							</button>
						</h2>
					</div>
				</div>
			</div>

			<div id="collapse_1" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<span for="fr_bankacc" class="text-gold"><?php echo $this->lang->line('label_change_password'); ?></span>
					<input class="toggle-box" id="identifier-3" type="checkbox"><label for="identifier-3"></label>
					<div>
						<div class="account-dropdown-content mt-3" style="display: block;">
							<?php echo form_open('ajax/change_password', array('id' => 'change-password-form')); ?>
							<div class="mb-2">
								<label for="pwd"><?php echo $this->lang->line('label_current_password'); ?></label>
								<div>
									<input type="password" class="ui-input2" name="oldpass" id="oldpass">
									<div id="oldPass_msg" class="error_red"></div>
								</div>
							</div>
							<div class="mb-2">
								<label for="pwd"><?php echo $this->lang->line('label_new_password'); ?></label>
								<div>
									<input type="password" class="ui-input2" name="password" id="password">
									<div id="newPass_msg" class="error_red"></div>
								</div>
							</div>
							<div class="mb-2">
								<label for="pwd"><?php echo $this->lang->line('label_confirm_password'); ?> :</label>
								<div>
									<input type="password" class="ui-input2" name="passconf" id="passconf">
									<div id="newPassC_msg" class="error_red"></div>
								</div>
							</div>
							<div class="mb-2">
								<small><span class="help-block"><?php echo $this->lang->line('label_change_password_notice_1'); ?></span></small>
							</div>
							<div class="text-center">
								<button class="btn btn-wallet w-75" type="submit" id="submit" name="submit"><?php echo $this->lang->line('label_update'); ?></button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="far fa-credit-card fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_2" aria-expanded="true" aria-controls="collapseOne">
								<span class="account-title"><?php echo $this->lang->line('label_binding_bank_account'); ?></span>
							</button>
						</h2>
					</div>
				</div>
			</div>

			<div id="collapse_2" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<span for="fr_bankacc" class="text-gold"><?php echo $this->lang->line('label_binding_bank'); ?></span>
					<?php
					if (isset($player_cash_bank) && sizeof($player_cash_bank) > 0) {
						foreach ($player_cash_bank as $player_cash_bank_row) {
					?>
							<div class="mb-2 mt-3">
								<span class="control-label"><?php echo $this->lang->line('label_bank_name'); ?>:&nbsp;&nbsp;</span>
								<span><?php echo $player_cash_bank_row['bank_name']; ?></span>
							</div>
							<div class="mb-2">
								<span class="control-label"><?php echo $this->lang->line('label_bank_account_name'); ?> :</span>
								<span><?php echo $player_cash_bank_row['bank_account_name']; ?></span>
							</div>
							<div class="mb-2">
								<span class="control-label"><?php echo $this->lang->line('label_bank_account_no'); ?> :</span>
								<span><?php echo $player_cash_bank_row['bank_account_no']; ?></span>
							</div>
							<br />
					<?php }
					} ?>
					<input class="toggle-box" id="identifier-5" type="checkbox"><label for="identifier-5"></label>
					<div>
						<div class="account-dropdown-content mt-3" style="display: block;">
							<?php echo form_open('ajax/binding_bank', array('id' => 'player-bank-form')); ?>
							<div class="form-group">
								<label for="bank_id"><?php echo $this->lang->line('label_bank_name'); ?></label>
								<div>
									<select class="ui-input2 py-2" name="bank_id" id="bank_id">
										<option value=""><?php echo $this->lang->line('label_please_select'); ?></option>
										<?php
										for ($i = 0; $i < sizeof($bank); $i++) {
											echo '<option value="' . $bank[$i]['bank_id'] . '">' . $bank[$i]['bank_name'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="bank_account_name"><?php echo $this->lang->line('label_bank_account_name'); ?> :</label>
								<div>
									<input type="text" class="ui-input2" name="bank_account_name" id="bank_account_name" value="<?php echo $player['full_name']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="bank_account_name"><?php echo $this->lang->line('label_bank_account_no'); ?> :</label>
								<div>
									<input type="text" class="ui-input2" name="bank_account_no" id="bank_account_no" placeholder="<?php echo $this->lang->line('label_bank_account_no'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="pwd"><?php echo $this->lang->line('label_password'); ?></label>
								<div>
									<input type="password" class="ui-input2" name="password" id="password">
								</div>
							</div>
							<div class="form-group">
								<small>
									<span class="help-block"><?php echo $this->lang->line('label_change_password_notice_1'); ?></span>
								</small>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-wallet w-75" type="submit" id="submit" name="submit"><?php echo $this->lang->line('label_submit'); ?></button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row justify-content-center align-items-center mb-2">
			<div class="col-3">
				<i class="fas fa-dollar-sign fs-1"></i>
			</div>
			<div class="col-9">
				<div class="mb-2">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_3" aria-expanded="true" aria-controls="collapseOne">
								<span for="fr_bankacc"><?php echo $this->lang->line('label_binding_bank_usdt'); ?>
							</button>
						</h2>
					</div>
				</div>
			</div>

			<div id="collapse_3" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<span for="fr_bankacc" class="text-gold"><?php echo $this->lang->line('label_binding_bank_usdt_address'); ?></span>
					<?php
					if (isset($player_cryto_bank) && sizeof($player_cryto_bank) > 0) {
						foreach ($player_cryto_bank as $player_cryto_bank_row) {
					?>
							<div class="mb-2">
								<span class="control-label"><?php echo $this->lang->line('label_bank_name'); ?>:&nbsp;&nbsp;</span>
								<span><?php echo $player_cryto_bank_row['bank_name']; ?></span>
							</div>
							<div class="mb-2">
								<span class="control-label"><?php echo $this->lang->line('label_bank_account_address'); ?> :</span>
								<span><?php echo $player_cryto_bank_row['bank_account_address']; ?></span>
							</div>
							<br />
					<?php }
					} ?>
					<input class="toggle-box" id="identifier-6" type="checkbox"><label for="identifier-6"></label>
					<div>
						<div class="account-dropdown-content mt-3" style="display: block;">
							<?php echo form_open('ajax/binding_bank_usdt', array('id' => 'player-bank-usdt-form')); ?>
							<input type="hidden" name="bank_id" value="6">
							<div class="form-group">
								<label for="bank_account_name"><?php echo $this->lang->line('label_bank_account_address'); ?> :</label>
								<div>
									<input type="text" class="ui-input2" name="bank_account_address" id="bank_account_address" placeholder="<?php echo $this->lang->line('label_bank_account_address'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="pwd"><?php echo $this->lang->line('label_password'); ?></label>
								<div>
									<input type="password" class="ui-input2" name="password">
								</div>
							</div>
							<div class="form-group">
								<small>
									<span class="help-block"><?php echo $this->lang->line('label_change_password_notice_1'); ?></span>
								</small>
							</div>
							<div class="text-center mt-2">
								<button class="btn btn-wallet w-75" type="submit" id="submit" name="submit"><?php echo $this->lang->line('label_submit'); ?></button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php /*
			<div class="row justify-content-center align-items-center mb-2">
				<div class="col-3">
					<img src="<?php echo base_url('assets/ebv2/m/images/icon/acc_icon_language.png'); ?>" class="rounded-circle bg-black" height="50px" width="50px">
				</div>
				<div class="col-9">
					<div class="mb-2">
						<a href="javascript:void(0);" onclick="langSelect('<?php echo LANG_EN; ?>')" class="account-lang-icon"><img src="<?php echo base_url('assets/ebv2/m/images/flag-english.png'); ?>"></a>
						<a href="javascript:void(0);" onclick="langSelect('<?php echo LANG_ZH_CN; ?>')" class="account-lang-icon"><img src="<?php echo base_url('assets/ebv2/m/images/flag-simplified.png'); ?>"></a>
						<a href="javascript:void(0);" onclick="langSelect('<?php echo LANG_ZH_HK; ?>')" class="account-lang-icon"><img src="<?php echo base_url('assets/ebv2/m/images/flag-simplified-traditional.png'); ?>"></a>
					</div>
				</div>
			</div>

			<div class="row justify-content-center align-items-center mb-2">
				<div class="col-3">
					<img src="<?php echo base_url('assets/ebv2/m/images/icon/acc_icon_contact_us.png'); ?>" class="rounded-circle bg-black" height="50px" width="50px">
				</div>
				<div class="col-9">
					<div class="mb-2">
						<span for="fr_bankacc"><a href="contact.html"><?php echo $this->lang->line('label_contact_us'); ?></a></span>
					</div>
				</div>
			</div>

			<div class="row justify-content-center align-items-center mb-2">
				<div class="col-3">
					<img src="<?php echo base_url('assets/ebv2/m/images/icon/acc_icon_live_chat.png'); ?>" class="rounded-circle bg-black" height="50px" width="50px">
				</div>
				<div class="col-9">
					<div class="mb-2">
						<span for="fr_bankacc"><a href="javascript:void(0);"><?php echo $this->lang->line('label_customer_service'); ?></a></span>
					</div>
				</div>
			</div>
			*/ ?>
	</div>
</div>

<?php $this->load->view('mobile/parts/footer'); ?>