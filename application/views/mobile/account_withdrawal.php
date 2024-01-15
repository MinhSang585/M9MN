<?php $this->load->view('mobile/parts/header'); ?>

<section class="main withdrawal">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
			</a>
			<div class="title">Withdrawal</div>
			<div class="opacity-0 ">
				<img src="obv1/m/images/icons/help.png" alt="">
			</div>
		</div>
	</nav>
	<div class="container pb-10vh">
		<div class="infos mt-4">
			<?php echo form_open('ajax/withdrawal', array('id' => 'withdrawal-form')); ?>

			<div class="form-group mb-2" id="div_capital" style="display: none;">
				<label class="control-label col-sm-2" for="capital"><?php echo $this->lang->line('label_capital'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled id="capital" value="">
				</div>
			</div>
			<div class="form-group mb-2" id="div_target_turnover" style="display: none;">
				<label class="control-label col-sm-2" for="capital"><?php echo $this->lang->line('label_target_turnover'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled id="target_turnover" value="">
				</div>
			</div>
			<div class="form-group mb-2" id="div_current_turnover" style="display: none;">
				<label class="control-label col-sm-2" for="capital"><?php echo $this->lang->line('label_current_turnover'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled id="current_turnover" value="">
				</div>
			</div>
			<br>
			<div class="form-group mb-2" id="nBankAcc5">
				<label class="control-label col-sm-2" for="currency_id"><?php echo $this->lang->line('label_currency'); ?></label>
				<div class="col-sm-10">
					<select class=" py-2" name="currency_id" id="currency_id" onchange="calculateCurrencyRate(this.value,<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>);">
						<option value=""><?php echo $this->lang->line('label_please_select'); ?></option>
						<?php if ($this->session->userdata('player_type') == PLAYER_TYPE_MG_MARKET) { ?>
							<option value="1"><?php echo $this->lang->line('label_trust_world_select'); ?></option>
						<?php } ?>
						<?php
						if (isset($currencies)) {
							for ($i = 0; $i < sizeof($currencies); $i++) {
								echo '<option value="' . $currencies[$i]['currency_id'] . '">' . $currencies[$i]['currency_code'] . '</option>';
							}
						}

						?>
					</select>
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAccHidden" style="display: none;">
				<!--
					<label class="control-label col-sm-2" for="currency_id"><?php echo $this->lang->line('label_currency'); ?></label>
					<div class="col-sm-10">
						<select name="player_bank_hidden" id="player_bank_hidden" class="">
							<option value=""><?php echo $this->lang->line('label_trust_world_select'); ?></option>
						</select>
					</div>
					-->
			</div>
			<div class="form-group mb-2" id="nBankAcc1" style="">
				<label class="control-label col-sm-2" for="bank_id"><?php echo $this->lang->line('label_withdrawal_method'); ?></label>
				<div class="col-sm-10">
					<select class=" py-2" name="player_bank_id" id="player_bank_id">
						<option value=""><?php echo $this->lang->line('label_please_select'); ?></option>
					</select>
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAcc3" style="display: none;">
				<label class="control-label col-sm-2" for="bank_account_name"><?php echo $this->lang->line('label_bank_account_name'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled name="bank_account_name" id="bank_account_name" value="<?php echo $player['full_name']; ?>">
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAcc4" style="display: none;">
				<label class="control-label col-sm-2" for="bank_account_no"><?php echo $this->lang->line('label_bank_account_no'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled name="bank_account_no" id="bank_account_no" placeholder="<?php echo $this->lang->line('label_bank_account_no'); ?>">
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAcc6" style="display: none;">
				<label class="control-label col-sm-2" for="bank_account_address"><?php echo $this->lang->line('label_bank_account_address'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled name="bank_account_address" id="bank_account_address" placeholder="<?php echo $this->lang->line('label_bank_account_address'); ?>">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="control-label col-sm-2" for="amount"><?php echo $this->lang->line('label_amount'); ?></label>
				<div class="col-sm-10">
					<input type="number" class="" name="amount" id="amount" onkeyup="calculateActualAmount(this.value)" placeholder="<?php echo $this->lang->line('label_amount'); ?>">
					<small class="text-center"><?php echo $this->lang->line('label_min'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_withdrawal'], 2, '.', ','); ?> / <?php echo $this->lang->line('label_max'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['max_withdrawal'], 2, '.', ','); ?></small>
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAcc7" style="display: none;">
				<label class="control-label col-sm-2" for="currency_rate"><?php echo $this->lang->line('label_currency_rate'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled id="currency_rate" value="">
				</div>
			</div>
			<div class="form-group mb-2" id="nBankAcc8" style="display: none;">
				<label class="control-label col-sm-2" for="actual_amount"><?php echo $this->lang->line('label_actual_amount'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="" disabled id="actual_amount" value="">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="control-label col-sm-2" for="password"><?php echo $this->lang->line('label_password'); ?></label>
				<div class="col-sm-10">
					<input type="password" class="" name="password" id="password" placeholder="<?php echo $this->lang->line('label_password'); ?>">
					<a href="<?php echo base_url('account') ?>">
						<div style="color: red;"><?php echo $this->lang->line('label_binding_bank_before_withdrawal'); ?></div>
					</a>
				</div>
			</div>

			<div class="card mb-2">
				<div class="card-body">
					<h6 class="card-subtitle mb-2 text-muted"><?php echo $this->lang->line('label_tips'); ?></h6>
					<p class="card-text mb-3"><?php echo $this->lang->line('label_withdraw_notice_1'); ?></p>
					<i style="color: red;"><?php echo $this->lang->line('label_withdraw_notice_2'); ?></i>
				</div>
			</div>

			<div class="form-btn-wrap d-grid">
				<button type="submit" class="btn btn-primary btn-lg" id="wdButton"><?php echo $this->lang->line('label_submit'); ?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</section>

<?php $this->load->view('mobile/parts/footer'); ?>
