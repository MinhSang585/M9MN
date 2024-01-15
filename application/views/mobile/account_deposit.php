<?php $this->load->view('mobile/parts/header'); ?>
<head>
	<style>
		.deposit-add-promotion label{
			padding: 4px 0px 4px 0px;
		}
		.tablinks1 {
			color: #bfbfbf;
		}
		.bank-active-text {
			color: #ffc107 !important;
		}
				
		.modal-content {
			background-image: url(<?php echo base_url('assets/dist/img/backgound_img.jpg'); ?>);
			color: #fff;
		}
		.custom-modal-size {
			max-width: 1400px;
			width: 96.5%;
		}

		.img-fluid{
			max-width: 100%;
			max-height: 250px;
		}

		.modal-body table{
			font-size: 12px !important;
			max-width: 340px !important;
		}
		.modal-body, .modal-body *, .modal-footer, .modal-footer *:not(button) {
			background-color: #f2b250 !important;
		}
		.modal-body table {
			width: 95%;
			border-collapse: collapse;
			margin: 10px auto 10px auto;
		}
		.modal-body th, .modal-body td {
			border: 1px solid white !important;
			padding: 8px !important;
			text-align: center;
		}
		.modal-title{
			color: #f2b250;
		}
	</style>
</head>
<section class="main deposit">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
			</a>
			<div class="title">Deposit</div>
			<div class="opacity-0 ">
				<img src="obv1/m/images/icons/help.png" alt="">
			</div>
		</div>
	</nav>
	<div class="container pb-10vh">
		<div id="divTransaction">

			<!-- Begin Tab Manu -->
			<div class="withdrawal-page__content__input pb-4">
				<p class="text-light text-center"><?php echo $this->lang->line('label_payment_method'); ?></p>
				<div class="row g-0 d-flex justify-content-center" id="bank_icon_list">
					<?php
					$class_active = "active";
					$default_div = "";
					$deposit_type = "";

					if ($isOffline) {
						$default_div = (empty($default_div) ? TRANSFER_OFFLINE_DEPOSIT : $default_div);
						$deposit_type = (empty($deposit_type) ? DEPOSIT_OFFLINE_BANKING : $deposit_type);
					?>
						<div class="text-center col-5 btn btn-primary mx-1">
							<div class="tablinks <?php echo $class_active; ?>" onclick="select_payment_method(this, <?php echo DEPOSIT_OFFLINE_BANKING; ?>, '<?php echo TRANSFER_OFFLINE_DEPOSIT; ?>')">
								<?php echo $this->lang->line('label_offline_banking_and_atm'); ?>
							</div>
						</div>
					<?php
						$class_active = "";
					}

					if (isset($vip_gateway) && sizeof($vip_gateway) > 0) {
						$default_div = (empty($default_div) ? TRANSFER_OFFLINE_DEPOSIT : $default_div);
						$deposit_type = (empty($deposit_type) ? DEPOSIT_OFFLINE_BANKING : $deposit_type);
					?>
						<div class="text-center col-5 btn btn-primary mx-1">
							<div class="tablinks <?php echo $class_active; ?>" onclick="select_payment_method(this, <?php echo DEPOSIT_OFFLINE_BANKING; ?>, '<?php echo TRANSFER_OFFLINE_DEPOSIT; ?>')">
								<?php echo $this->lang->line('label_online_banking_and_atm'); ?>
							</div>
						</div>
					<?php
						$class_active = "";
					}
					?>

					<?php
					if (isset($online_gateway) && sizeof($online_gateway) > 0) {
						$default_div = (empty($default_div) ? TRANSFER_PG_DEPOSIT : $default_div);
						$deposit_type = (empty($deposit_type) ? DEPOSIT_ONLINE_BANKING : $deposit_type);
					?>
						<div class="text-center col-5 btn btn-primary mx-1">
							<div class="tablinks <?php echo $class_active; ?>" onclick="select_payment_method(this, <?php echo DEPOSIT_ONLINE_BANKING; ?>, '<?php echo TRANSFER_PG_DEPOSIT; ?>')">
								<?php echo $this->lang->line('label_online_banking_atm_quickpay'); ?>
							</div>
						</div>
					<?php
						$class_active = "";
					}
					?>
					<?php
					if (isset($credit_card_gateway) && sizeof($credit_card_gateway) > 0) {
						$default_div = (empty($default_div) ? TRANSFER_CREDIT_CARD_DEPOSIT : $default_div);
						$deposit_type = (empty($deposit_type) ? DEPOSIT_CREDIT_CARD : $deposit_type);
					?>
						<div class="text-center col-5 btn btn-primary mx-1">
							<div class="tablinks <?php echo $class_active; ?>" onclick="select_payment_method(this, <?php echo DEPOSIT_CREDIT_CARD; ?>, '<?php echo TRANSFER_CREDIT_CARD_DEPOSIT; ?>')">
								<?php echo $this->lang->line('label_credit_card_banking'); ?>
							</div>
						</div>
					<?php
						$class_active = "";
					}
					?>
					<?php
					if (isset($hypermart_gateway) && sizeof($hypermart_gateway) > 0) {
						$default_div = (empty($default_div) ? TRANSFER_HYPERMART_DEPOSIT : $default_div);
						$deposit_type = (empty($deposit_type) ? DEPOSIT_HYPERMART : $deposit_type);
					?>
						<div class="text-center col-5 btn btn-primary mx-1">
							<div class="tablinks <?php echo $class_active; ?>" onclick="select_payment_method(this, <?php echo DEPOSIT_HYPERMART; ?>, '<?php echo TRANSFER_HYPERMART_DEPOSIT; ?>')">
								<?php echo $this->lang->line('label_hypermart_banking'); ?>
							</div>
						</div>
					<?php
						$class_active = "";
					}
					?>
				</div>
			</div>
			<?php echo form_open_multipart('ajax/deposit', array('id' => 'deposit-form', 'class' => "deposit_form")); ?>
			<input type="hidden" name="transaction_type" id="transaction_type_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" value="<?php echo TRANSFER_OFFLINE_DEPOSIT; ?>" />
			<input type="hidden" name="deposit_type" id="deposit_type_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" value="<?php echo DEPOSIT_OFFLINE_BANKING; ?>" />
			<div id="deposit_method_div_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" <?php if ($default_div != TRANSFER_OFFLINE_DEPOSIT) {
																					echo "style='display:none;'";
																				} ?>>
			<?php if($isOffline) { ?>
				<div class="withdrawal-page__content__input row bank-deposit bank-crypto" id="bank_selection">
					<p class="text-light text-center"><?php echo $this->lang->line('label_please_select'); ?> <?php echo $this->lang->line('label_bank'); ?></p>
					<div class="row gx-2 justify-content-center">
						<?php
						$bank_acc_holder = '';
						$bank_acc_no = '';
						
						for ($i = 0; $i < sizeof($default_gateway); $i++) {
							$active = '';
							if (($i == 0) && ($default_div == TRANSFER_OFFLINE_DEPOSIT)) {
								$bank_acc_holder = $default_gateway[$i]['bank_account_name'];
								$bank_acc_no = $default_gateway[$i]['bank_account_no'];
								$active = 'active';
							}
							$bank_account_id = $default_gateway[$i]['bank_account_id'] ?? "";
							$bank_account_name = $default_gateway[$i]['bank_account_name'] ?? "";
							$bank_account_no = $default_gateway[$i]['bank_account_no'] ?? "";
						?>
						<div class="col-4 text-center">
							<a class="tablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?> <?php echo $active; ?>"
								onclick="select_bank(
									this,
									<?= DEPOSIT_OFFLINE_BANKING; ?>,
									'<?= $bank_account_id; ?>',
									'<?= $bank_account_name; ?>',
									'<?= $bank_account_no; ?>'
								)">
								<img src="<?php echo base_url('uploads/banks/' . $bank[$default_gateway[$i]['bank_id']]['web_image_on']); ?>" 
									class="on w-100">
								<p><?php echo $bank[$default_gateway[$i]['bank_id']]['bank_name']; ?></p>
							</a>
						</div>
					<?php
					}
					?>
				</div>
			</div>
			<?php } else { ?>
				<div class="withdrawal-page__content__input row bank-deposit bank-crypto" id="bank_selection">
					<div class="row g-90">
						<?php
						$bank_acc_holder = '';
						$bank_acc_no = '';

						for ($i = 0; $i < sizeof($vip_gateway); $i++) {
							$active = '';
							if (($i == 0) && ($default_div == TRANSFER_OFFLINE_DEPOSIT)) {
								$bank_acc_holder = $vip_gateway[$i]['bank_account_name'];
								$bank_acc_no = $vip_gateway[$i]['bank_account_no'];
								$active = 'active';
							}
						?>
							<div class="col-2 text-center">
								<a class="tablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?> <?php echo $active; ?>" onclick="select_bank(this, <?php echo DEPOSIT_OFFLINE_BANKING; ?>, '<?php echo $vip_gateway[$i]['bank_account_id']; ?>', '<?php echo $vip_gateway[$i]['bank_account_name']; ?>', '<?php echo $vip_gateway[$i]['bank_account_no']; ?>')">
									<img src="<?php echo base_url('uploads/banks/' . $vip_gateway[$i]['web_image_on']); ?>" class="on w-100">
									<p><?php echo $vip_gateway[$i]['bank_name']; ?></p>
								</a>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<?php } ?>
				<div id="bank_details" class="bank-deposit bank-crypto col-12" style="margin-bottom: 20px;color:#fff">
					<td colspan="2">
						<div style="border:2px solid #f3c341; -webkit-border-radius: 10px;border-radius: 10px;padding: 10px;" class="abc">
							<table width="100%" border="0" cellspacing="3" cellpadding="0">
								<tbody>
									<tr>
										<td width="224" align="left"><span><?php echo $this->lang->line('label_bank_account_name'); ?> </span></td>
										<td width="300" align="left" style="border-left: 1px solid #8e8e8e;" class="ps-2"><span id="bank_acc_holder"><?php echo $bank_acc_holder; ?></span></td>
									</tr>
									<tr>
										<td align="left"><span><?php echo $this->lang->line('label_bank_account_no'); ?> </span></td>
										<td align="left" style="border-left: 1px solid #8e8e8e;" class="ps-2"><span id="bank_acc_no"><?php echo $bank_acc_no; ?></span>
											<div class="btn btn-yellow btn-copy" onclick="select_all_and_copy(document.getElementById('bank_acc_no'));"><?php echo $this->lang->line('label_copy'); ?></div>
										</td>
									</tr>
									<tr>
										<td align="left"><span class="deposit-green-bg-txt"><?php echo $this->lang->line('label_min_deposit'); ?> </span></td>
										<td align="left" style="border-left: 1px solid #8e8e8e;" class="ps-2"><span class="deposit-green-bg-txt"><?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_deposit'], 2, '.', ','); ?></span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
					<?php if($isOffline) { ?>
					<input type="hidden" name="bank_account_id" id="bank_account_id" value="<?php if (sizeof($default_gateway) > 0) {
																											echo $default_gateway[0]['bank_account_id'];
																										} else {
																											echo '0';
																										} ?>" />
					<?php } else { ?>
					<input type="hidden" name="bank_account_id" id="bank_account_id" value="<?php if (sizeof($vip_gateway) > 0) {
																											echo $vip_gateway[0]['bank_account_id'];
																										} else {
																											echo '0';
																										} ?>" />
					<?php } ?>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit" id="player_bank_account_div">
					<label for="" style="font-size:12px;"><?php echo $this->lang->line('label_player_bank_account'); ?> <span>*</span></label>
					<div class="col-12">
						<select name="player_bank_id" id="player_bank_id_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" class="form-control">
							<option value=""><?php echo $this->lang->line('placeholder_please_choose_player_bank_account'); ?></option>
							<?php if (isset($player_bank) && !empty($player_bank)) { ?>
								<?php foreach ($player_bank as $player_bank_row) { ?>
									<option value="<?php echo $player_bank_row['player_bank_id']; ?>"><?php echo $player_bank_row['bank_account_name'] . " - " . $player_bank_row['bank_account_no'] . " (" . $player_bank_row['bank_name'] . ")"; ?></option>
							<?php }
							} ?>
						</select>
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit" id="upload_receipt_div">
					<label for="" style="font-size:12px;"><?php echo $this->lang->line('label_upload_receipt'); ?></label>
					<div class="col-12">
						<input class="form-control" id="bank_slip_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" name="bank_slip" type="file">
					</div>
				</div>
				<div class="withdrawal-page__content__input row" id="nBankAcc5">
					<label for="currency_id"><?php echo $this->lang->line('label_currency'); ?></label>
					<div class="col-12">
						<select class="form-control" name="currency_id" id="currency_id_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onchange="calculateCurrencyRate(this.value,<?php echo TRANSACTION_TYPE_DEPOSIT; ?>,<?php echo DEPOSIT_OFFLINE_BANKING; ?>);">
							<option value=""><?php echo $this->lang->line('label_please_select'); ?></option>
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
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway bank-crypto">
					<label for="amount"><?php echo $this->lang->line('label_amount'); ?> (<?php echo $setting['system_currency'] ?>) <span>*</span></label>
					<div class="col-12">
						<input class="form-control" type="text" name="amount" id="amount_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" style="min-width: 330px;" placeholder="<?php echo $this->lang->line('label_min_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_deposit'], 2, '.', ','); ?> | <?php echo $this->lang->line('label_max_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['max_deposit'], 2, '.', ','); ?>" onkeyup="calculateActualAmount(this.value, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway">
					<label for="crypto-amount">&nbsp;</label>
					<!-- <div class="col-12 deposit-amount" style="display: flex;">
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onclick="select_amount(this, 200, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="200">
								<div class="deposit-amount__item__label__title"> 200</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="200">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onclick="select_amount(this, 500, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="500">
								<div class="deposit-amount__item__label__title"> 500</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="500">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onclick="select_amount(this, 1000, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="1000">
								<div class="deposit-amount__item__label__title"> 1000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="1000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onclick="select_amount(this, 3000, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="3000">
								<div class="deposit-amount__item__label__title"> 3000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="3000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_OFFLINE_BANKING; ?>" onclick="select_amount(this, 5000, <?php echo DEPOSIT_OFFLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="5000">
								<div class="deposit-amount__item__label__title"> 5000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="5000">
								<span class="checkmark"></span>
							</label>
						</div>
					</div> -->
				</div>
				<div class="withdrawal-page__content__input row" id="nBankAcc7" style="display: none;">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency_rate'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_rate_<?php echo DEPOSIT_OFFLINE_BANKING; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row" id="nBankAcc8" style="display: none;">
					<label for="actual_amount"><?php echo $this->lang->line('label_actual_amount'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="actual_amount_<?php echo DEPOSIT_OFFLINE_BANKING; ?>">
					</div>
				</div>
				<div class="form-group clearfix bank-deposit">
					<div class="col-sm-12">
						<div class="wallet-form-left mb-2"><?php echo $this->lang->line('label_deposit_date_time'); ?></div>
						<div class="wallet-form-right">
							<input type="text" class="form-control date-select-bg" id="dob" name="bank_in_date" dt-value="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d') ?>" placeholder="YYYY-MM-DD" style="width: 50%; float: left;">
							<select class="form-control" name="bank_in_hour" id="bank_in_hour_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" style="width: 23%; float: left; margin: 0 0 0 2%">
								<?php
								$html = '';

								for ($i = 0; $i <= 23; $i++) {
									if ($i == date('H')) {
										$html .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" selected="selected">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
									} else {
										$html .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
									}
								}

								echo $html;
								?>
							</select>
							<select class="form-control r_form_input" name="bank_in_minute" id="bank_in_minute_<?php echo DEPOSIT_OFFLINE_BANKING; ?>" style="width: 23%; float: left; margin: 0 0 0 2%">
								<?php
								$html = '';

								for ($i = 0; $i <= 59; $i++) {
									if ($i == date('i')) {
										$html .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" selected="selected">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
									} else {
										$html .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
									}
								}

								echo $html;
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for=""><?php echo $this->lang->line('label_promotion'); ?></label>
					<div class="col-12">
						<span id="showPromo" class="custom-radio-tag deposit-page__content__title deposit-add-promotion">
						<label><input type="radio" name="promoId" value="0" checked=""><span class="subtitle">&nbsp; <?php echo $this->lang->line('label_without_promotion'); ?></span></label><br>
							<?php if (isset($promotion) && !empty($promotion)) { ?>
								<?php foreach ($promotion as $promotion_row) { ?>
									<label><input type="radio" name="promoId" value="<?php echo $promotion_row['promotion_id']; ?>"><span class="subtitle">&nbsp; <?php echo $promotion_row['promotion_title']; ?> /&nbsp;<span class="promo_tc"><a href="#promotions-<?php echo $promotion_row['promotion_id']; ?>" onclick="open_promo(<?php echo $promotion_row['promotion_id']; ?>);" data-toggle="modal" style="color:red;"><?php echo $this->lang->line('label_read_promotion_tnc'); ?></a></span></span></label><br>
								<?php } ?>
							<?php } ?>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="">&nbsp;</label>
					<div class="col-12">
						<div style="color: red;"><?php echo $this->lang->line('label_read_promotion_tnc_before_choosing'); ?></div>
						<div style="color: red;" class="mb-2"><?php echo $this->lang->line('label_not_accept_atm'); ?></div>

						<div class="deposit-amount__action">
							<button class="btn btn-primary withdrawal-submit"><?php echo $this->lang->line('label_submit'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>

			<?php echo form_open_multipart('ajax/deposit', array('id' => 'deposit-online-form', 'class' => "deposit_form")); ?>
			<input type="hidden" name="transaction_type" id="transaction_type_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="<?php echo TRANSFER_PG_DEPOSIT; ?>" />
			<input type="hidden" name="deposit_type" id="deposit_type_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="<?php echo DEPOSIT_ONLINE_BANKING; ?>" />
			<div id="deposit_method_div_<?php echo DEPOSIT_ONLINE_BANKING; ?>" <?php if ($default_div != TRANSFER_PG_DEPOSIT) {
																					echo "style='display:none;'";
																				} ?>>
				<div class="withdrawal-page__content__input row bank-deposit bank-crypto" id="bank_online_selection">
					<div class="row g-90">
						<?php
						$currencyCode = '';
						$depositRate = 0.00;
						for ($i = 0; $i < sizeof($online_gateway); $i++) {
							?>										
								<input type="hidden" id="placeholder_amount_<?php echo $online_gateway[$i]['payment_gateway_id']; ?>" value="<?php echo $this->lang->line('label_min_amount'); ?> <?php echo $online_gateway[$i]['payment_gateway_currency_code']; ?> <?php echo number_format($online_gateway[$i]['payment_gateway_min_amount'], 2, '.', ','); ?> | <?php echo $this->lang->line('label_max_amount'); ?> <?php echo $online_gateway[$i]['payment_gateway_currency_code']; ?> <?php echo number_format($online_gateway[$i]['payment_gateway_max_amount'], 2, '.', ','); ?>" />
							<?php

							$active = '';
							$paymentGatewayCode = '';
							if ($i == 0) {
								$paymentGatewayCode = $online_gateway[0]['payment_gateway_code'];
								$currencyCode = $online_gateway[0]['payment_gateway_currency_code'];
								$depositRate = bcdiv($online_gateway[0]['d_currency_rate'], 1, 4);
								$active = 'active';
							}
						?>
							<div class="col-2 text-center">
								<a class="tablinks<?php echo DEPOSIT_ONLINE_BANKING; ?> <?php echo $active; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_ONLINE_BANKING; ?>, '<?php echo $online_gateway[$i]['payment_gateway_code'] ?>','<?php echo $online_gateway[$i]['is_select_bank'] ?>', '<?php echo $online_gateway[$i]['payment_gateway_id'] ?>')">
									<img src="<?php echo base_url('uploads/banks/' . $online_gateway[$i]['web_image_on']); ?>" class="on w-100">
									<p><?php echo $this->lang->line($online_gateway[$i]['payment_gateway_name']); ?></p>
								</a>
							</div>
							<!-- <div class="bank-wrapper clearfix <?php echo $active; ?> tablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_ONLINE_BANKING; ?>, '<?php echo $online_gateway[$i]['payment_gateway_code'] ?>','<?php echo $online_gateway[$i]['is_select_bank'] ?>', '<?php echo $online_gateway[$i]['payment_gateway_id'] ?>')">
					<div class="acc-bank-img-wrap"><img onclick="activateImg('off')" src="<?php echo base_url('uploads/banks/' . $online_gateway[$i]['web_image_off']); ?>" class="off"><img onclick="activateImg('on')" src="<?php echo base_url('uploads/banks/' . $online_gateway[$i]['web_image_on']); ?>" class="on"></div>
					<div class="desc"><?php echo $this->lang->line($online_gateway[$i]['payment_gateway_name']); ?></div>
				</div> -->
						<?php
						}
						?>
						<input type="hidden" name="payment_gateway_code" id="payment_gateway_code_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="<?php echo $paymentGatewayCode; ?>" />
					</div>
				</div>

				<?php
				$onlineBank = array();
				if (sizeof($online_gateway) > 0) {
					$onlineBank = explode(',', $online_gateway[0]['bank_data']);
					$onlineBank = array_values(array_filter($onlineBank));
				}
				?>

				<div class="withdrawal-page__content__input row bank-deposit" id="payment_gateway_bank_div_<?php echo DEPOSIT_ONLINE_BANKING; ?>" <?php if (sizeof($onlineBank) > 0) {
																																						echo 'style="display: block;"';
																																					} else {
																																						echo 'style="display: none;"';
																																					} ?>>
					<label for="" style="font-size:12px;"><?php echo $this->lang->line('payment_gateway_bank'); ?> <span>*</span></label>
					<div class="col-12">
						<select name="payment_gateway_bank" id="payment_gateway_bank_<?php echo DEPOSIT_ONLINE_BANKING; ?>" class="form-control">
							<?php
							if (sizeof($onlineBank) > 0) {
								foreach ($onlineBank as $onlineBankRow) {
									echo '<option value="' . $onlineBankRow . '">' . payment_gateway_code(strtoupper($online_gateway[0]['payment_gateway_name_text']), $onlineBankRow) . '</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="withdrawal-page__content__input row" id="currency_code_display_div_<?php echo DEPOSIT_ONLINE_BANKING; ?>">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_code_display_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="<?php echo $currencyCode; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway bank-crypto">
					<label for="amount"><?php echo $this->lang->line('label_amount'); ?> (<?php echo $setting['system_currency'] ?>) <span>*</span></label>
					<div class="col-12">
						<input class="form-control" type="text" name="amount" id="amount_<?php echo DEPOSIT_ONLINE_BANKING; ?>" style="min-width: 330px;" placeholder="<?php echo $this->lang->line('label_min_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_deposit'], 2, '.', ','); ?> | <?php echo $this->lang->line('label_max_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['max_deposit'], 2, '.', ','); ?>" onkeyup="calculateActualAmount(this.value, <?php echo DEPOSIT_ONLINE_BANKING; ?>)">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway">
					<label for="crypto-amount">&nbsp;</label>
					<!-- <div class="col-12" style="display: flex;">
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_amount(this, 200, <?php echo DEPOSIT_ONLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="200">
								<div class="deposit-amount__item__label__title"> 200</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="200">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_amount(this, 500, <?php echo DEPOSIT_ONLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="500">
								<div class="deposit-amount__item__label__title"> 500</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="500">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_amount(this, 1000, <?php echo DEPOSIT_ONLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="1000">
								<div class="deposit-amount__item__label__title"> 1000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="1000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_amount(this, 3000, <?php echo DEPOSIT_ONLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="3000">
								<div class="deposit-amount__item__label__title"> 3000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="3000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_ONLINE_BANKING; ?>" onclick="select_amount(this, 5000, <?php echo DEPOSIT_ONLINE_BANKING; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="5000">
								<div class="deposit-amount__item__label__title"> 5000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="5000">
								<span class="checkmark"></span>
							</label>
						</div>
					</div> -->
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency_rate'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_rate_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="<?php echo $depositRate; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="actual_amount"><?php echo $this->lang->line('label_actual_amount'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="actual_amount_<?php echo DEPOSIT_ONLINE_BANKING; ?>" value="0">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for=""><?php echo $this->lang->line('label_promotion'); ?></label>
					<div class="col-12">
						<span id="showPromo" class="custom-radio-tag deposit-page__content__title deposit-add-promotion">
						<label><input type="radio" name="promoId" value="0" checked=""><span class="subtitle">&nbsp; <?php echo $this->lang->line('label_without_promotion'); ?></span></label><br>
							<?php if (isset($promotion) && !empty($promotion)) { ?>
								<?php foreach ($promotion as $promotion_row) { ?>
									<label><input type="radio" name="promoId" value="<?php echo $promotion_row['promotion_id']; ?>"><span class="subtitle">&nbsp; <?php echo $promotion_row['promotion_title']; ?> /&nbsp;<span class="promo_tc"><a href="#promotions-<?php echo $promotion_row['promotion_id']; ?>" onclick="open_promo(<?php echo $promotion_row['promotion_id']; ?>);" data-toggle="modal" style="color:red;"><?php echo $this->lang->line('label_read_promotion_tnc'); ?></a></span></span></label><br>
								<?php } ?>
							<?php } ?>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="">&nbsp;</label>
					<div class="col-12">
						<div style="color: red;"><?php echo $this->lang->line('label_read_promotion_tnc_before_choosing'); ?></div>
						<div class="mb-2" style="color: red;"><?php echo $this->lang->line('label_not_accept_atm'); ?></div>

						<div class="deposit-amount__action">
							<button class="btn btn-primary withdrawal-submit"><?php echo $this->lang->line('label_submit'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>

			<?php echo form_open_multipart('ajax/deposit', array('id' => 'deposit-credit-form', 'class' => "deposit_form")); ?>
			<input type="hidden" name="transaction_type" id="transaction_type_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="<?php echo TRANSFER_CREDIT_CARD_DEPOSIT; ?>" />
			<input type="hidden" name="deposit_type" id="deposit_type_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="<?php echo DEPOSIT_CREDIT_CARD; ?>" />
			<div id="deposit_method_div_<?php echo DEPOSIT_CREDIT_CARD; ?>" <?php if ($default_div != TRANSFER_CREDIT_CARD_DEPOSIT) {
																				echo "style='display:none;'";
																			} ?>>
				<div class="withdrawal-page__content__input row bank-deposit bank-crypto" id="bank_online_selection">
					<div class="row g-90">
						<?php
						$currencyCode = '';
						$depositRate = 0.00;
						for ($i = 0; $i < sizeof($credit_card_gateway); $i++) {
							$active = '';
							$paymentGatewayCode = '';
							if ($i == 0) {
								$paymentGatewayCode = $credit_card_gateway[0]['payment_gateway_code'];
								$currencyCode = $credit_card_gateway[0]['payment_gateway_currency_code'];
								$depositRate = bcdiv($credit_card_gateway[0]['d_currency_rate'], 1, 4);
								$active = 'active';
							}
						?>
							<div class="col-2 text-center">
								<a class="tablinks<?php echo DEPOSIT_CREDIT_CARD; ?> <?php echo $active; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_CREDIT_CARD; ?>, '<?php echo $credit_card_gateway[$i]['payment_gateway_code'] ?>','<?php echo $credit_card_gateway[$i]['is_select_bank'] ?>', '<?php echo $credit_card_gateway[$i]['payment_gateway_id'] ?>')">
									<img src="<?php echo base_url('uploads/banks/' . $credit_card_gateway[$i]['web_image_on']); ?>" class="on w-100">
									<p><?php echo $this->lang->line($credit_card_gateway[$i]['payment_gateway_name']); ?></p>
								</a>
							</div>
							<!-- <div class="bank-wrapper clearfix <?php echo $active; ?> tablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_CREDIT_CARD; ?>, '<?php echo $credit_card_gateway[$i]['payment_gateway_code'] ?>','<?php echo $credit_card_gateway[$i]['is_select_bank'] ?>', '<?php echo $credit_card_gateway[$i]['payment_gateway_id'] ?>')">
					<div class="acc-bank-img-wrap"><img src="<?php echo base_url('uploads/banks/' . $credit_card_gateway[$i]['web_image_off']); ?>" class="off"><img src="<?php echo base_url('uploads/banks/' . $credit_card_gateway[$i]['web_image_on']); ?>" class="on"></div>
					<div class="desc"><?php echo $this->lang->line($credit_card_gateway[$i]['payment_gateway_name']); ?></div>
				</div> -->
						<?php
						}
						?>
						<input type="hidden" name="payment_gateway_code" id="payment_gateway_code_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="<?php echo $paymentGatewayCode; ?>" />
					</div>
				</div>

				<?php
				$creditBank = array();
				if (sizeof($credit_card_gateway) > 0) {
					$creditBank = explode(',', $credit_card_gateway[0]['bank_data']);
					$creditBank = array_values(array_filter($creditBank));
				}
				?>
				<div class="withdrawal-page__content__input row bank-deposit" id="payment_gateway_bank_div_<?php echo DEPOSIT_CREDIT_CARD; ?>" <?php if (sizeof($creditBank) > 0) {
																																					echo 'style="display: block;"';
																																				} else {
																																					echo 'style="display: none;"';
																																				} ?>>
					<label for="" style="font-size:12px;"><?php echo $this->lang->line('payment_gateway_bank'); ?> <span>*</span></label>
					<div class="col-12">
						<select name="payment_gateway_bank" id="payment_gateway_bank_<?php echo DEPOSIT_CREDIT_CARD; ?>" class="form-control">
							<?php
							if (sizeof($creditBank) > 0) {
								foreach ($creditBank as $creditBankRow) {
									echo '<option value="' . $creditBankRow . '">' . $this->lang->line('bank_name_' . strtolower($creditBankRow)) . '</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="withdrawal-page__content__input row" id="currency_code_display_div_<?php echo DEPOSIT_CREDIT_CARD; ?>">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_code_display_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="<?php echo $currencyCode; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway bank-crypto">
					<label for="amount"><?php echo $this->lang->line('label_amount'); ?> (<?php echo $setting['system_currency'] ?>) <span>*</span></label>
					<div class="col-12">
						<input class="form-control" type="text" name="amount" id="amount_<?php echo DEPOSIT_CREDIT_CARD; ?>" style="min-width: 330px;" placeholder="<?php echo $this->lang->line('label_min_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_deposit'], 2, '.', ','); ?> | <?php echo $this->lang->line('label_max_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['max_deposit'], 2, '.', ','); ?>" onkeyup="calculateActualAmount(this.value, <?php echo DEPOSIT_CREDIT_CARD; ?>)">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway">
					<label for="crypto-amount">&nbsp;</label>
					<!-- <div class="col-12" style="display: flex;">
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_amount(this, 200, <?php echo DEPOSIT_CREDIT_CARD; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="200">
								<div class="deposit-amount__item__label__title"> 200</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="200">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_amount(this, 500, <?php echo DEPOSIT_CREDIT_CARD; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="500">
								<div class="deposit-amount__item__label__title"> 500</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="500">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_amount(this, 1000, <?php echo DEPOSIT_CREDIT_CARD; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="1000">
								<div class="deposit-amount__item__label__title"> 1000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="1000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_amount(this, 3000, <?php echo DEPOSIT_CREDIT_CARD; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="3000">
								<div class="deposit-amount__item__label__title"> 3000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="3000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_CREDIT_CARD; ?>" onclick="select_amount(this, 5000, <?php echo DEPOSIT_CREDIT_CARD; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="5000">
								<div class="deposit-amount__item__label__title"> 5000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="5000">
								<span class="checkmark"></span>
							</label>
						</div>
					</div> -->
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency_rate'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_rate_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="<?php echo $depositRate; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="actual_amount"><?php echo $this->lang->line('label_actual_amount'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="actual_amount_<?php echo DEPOSIT_CREDIT_CARD; ?>" value="0">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for=""><?php echo $this->lang->line('label_promotion'); ?></label>
					<div class="col-12">
						<span id="showPromo" class="custom-radio-tag deposit-page__content__title deposit-add-promotion">
						<label><input type="radio" name="promoId" value="0" checked=""><span class="subtitle">&nbsp; <?php echo $this->lang->line('label_without_promotion'); ?></span></label><br>
							<?php if (isset($promotion) && !empty($promotion)) { ?>
								<?php foreach ($promotion as $promotion_row) { ?>
									<label><input type="radio" name="promoId" value="<?php echo $promotion_row['promotion_id']; ?>"><span class="subtitle">&nbsp; <?php echo $promotion_row['promotion_title']; ?> /&nbsp;<span class="promo_tc"><a href="#promotions-<?php echo $promotion_row['promotion_id']; ?>" onclick="open_promo(<?php echo $promotion_row['promotion_id']; ?>);" data-toggle="modal" style="color:red;"><?php echo $this->lang->line('label_read_promotion_tnc'); ?></a></span></span></label><br>
								<?php } ?>
							<?php } ?>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="">&nbsp;</label>
					<div class="col-12">
						<div style="color: red;"><?php echo $this->lang->line('label_read_promotion_tnc_before_choosing'); ?></div>
						<div class="mb-2" style="color: red;"><?php echo $this->lang->line('label_not_accept_atm'); ?></div>

						<div class="deposit-amount__action">
							<button class="btn btn-primary withdrawal-submit"><?php echo $this->lang->line('label_submit'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>

			<?php echo form_open_multipart('ajax/deposit', array('id' => 'deposit-hypermart-form', 'class' => "deposit_form")); ?>
			<input type="hidden" name="transaction_type" id="transaction_type_<?php echo DEPOSIT_HYPERMART; ?>" value="<?php echo TRANSFER_HYPERMART_DEPOSIT; ?>" />
			<input type="hidden" name="deposit_type" id="deposit_type_<?php echo DEPOSIT_HYPERMART; ?>" value="<?php echo DEPOSIT_HYPERMART; ?>" />
			<div id="deposit_method_div_<?php echo DEPOSIT_HYPERMART; ?>" <?php if ($default_div != TRANSFER_HYPERMART_DEPOSIT) {
																				echo "style='display:none;'";
																			} ?>>
				<div class="withdrawal-page__content__input row bank-deposit bank-crypto" id="bank_online_selection">
					<div class="col-sm-12 bank-list">
						<?php
						$currencyCode = '';
						$depositRate = 0.00;
						for ($i = 0; $i < sizeof($hypermart_gateway); $i++) {
							$active = '';
							$paymentGatewayCode = '';
							if ($i == 0) {
								$paymentGatewayCode = $hypermart_gateway[0]['payment_gateway_code'];
								$currencyCode = $hypermart_gateway[0]['payment_gateway_currency_code'];
								$depositRate = bcdiv($hypermart_gateway[0]['d_currency_rate'], 1, 4);
								$active = 'active';
							}
						?>
							<div class="col-2 text-center">
								<a class="tablinks<?php echo DEPOSIT_HYPERMART; ?> <?php echo $active; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_HYPERMART; ?>, '<?php echo $hypermart_gateway[$i]['payment_gateway_code'] ?>','<?php echo $hypermart_gateway[$i]['is_select_bank'] ?>', '<?php echo $hypermart_gateway[$i]['payment_gateway_id'] ?>')">
									<img src="<?php echo base_url('uploads/banks/' . $hypermart_gateway[$i]['web_image_on']); ?>" class="on w-100">
									<p><?php echo $this->lang->line($hypermart_gateway[$i]['payment_gateway_name']); ?></p>
								</a>
							</div>
							<!-- <div class="bank-wrapper clearfix <?php echo $active; ?> tablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_payment_gateway(this, <?php echo DEPOSIT_HYPERMART; ?>, '<?php echo $hypermart_gateway[$i]['payment_gateway_code'] ?>','<?php echo $hypermart_gateway[$i]['is_select_bank'] ?>', '<?php echo $hypermart_gateway[$i]['payment_gateway_id'] ?>')">
					<div class="acc-bank-img-wrap"><img src="<?php echo base_url('uploads/banks/' . $hypermart_gateway[$i]['web_image_off']); ?>" class="off"><img src="<?php echo base_url('uploads/banks/' . $hypermart_gateway[$i]['web_image_on']); ?>" class="on"></div>
					<div class="desc"><?php echo $this->lang->line($hypermart_gateway[$i]['payment_gateway_name']); ?></div>
				</div> -->
						<?php
						}
						?>
						<input type="hidden" name="payment_gateway_code" id="payment_gateway_code_<?php echo DEPOSIT_HYPERMART; ?>" value="<?php echo $paymentGatewayCode; ?>" />
					</div>
				</div>
				<?php
				$hyperBank = array();
				if (sizeof($hypermart_gateway) > 0) {
					$hyperBank = explode(',', $hypermart_gateway[0]['bank_data']);
					$hyperBank = array_values(array_filter($hyperBank));
				}
				?>
				<div class="withdrawal-page__content__input row bank-deposit" id="payment_gateway_bank_div_<?php echo DEPOSIT_HYPERMART; ?>" <?php if (sizeof($hyperBank) > 0) {
																																					echo 'style="display: block;"';
																																				} else {
																																					echo 'style="display: none;"';
																																				} ?>>
					<label for="" style="font-size:12px;"><?php echo $this->lang->line('payment_gateway_bank'); ?> <span>*</span></label>
					<div class="col-12">
						<select name="payment_gateway_bank" id="payment_gateway_bank_<?php echo DEPOSIT_HYPERMART; ?>" class="form-control">
							<?php
							if (sizeof($hyperBank) > 0) {
								foreach ($hyperBank as $hyperBankRow) {
									echo '<option value="' . $hyperBankRow . '">' . $this->lang->line('bank_name_' . strtolower($hyperBankRow)) . '</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="withdrawal-page__content__input row" id="currency_code_display_div_<?php echo DEPOSIT_HYPERMART; ?>">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_code_display_<?php echo DEPOSIT_HYPERMART; ?>" value="<?php echo $currencyCode; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway bank-crypto">
					<label for="amount"><?php echo $this->lang->line('label_amount'); ?> (<?php echo $setting['system_currency'] ?>) <span>*</span></label>
					<div class="col-12">
						<input class="form-control" type="text" name="amount" id="amount_<?php echo DEPOSIT_HYPERMART; ?>" style="min-width: 330px;" placeholder="<?php echo $this->lang->line('label_min_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['min_deposit'], 2, '.', ','); ?> | <?php echo $this->lang->line('label_max_amount'); ?> <?php echo $setting['system_currency'] ?> <?php echo number_format($setting['max_deposit'], 2, '.', ','); ?>" onkeyup="calculateActualAmount(this.value, <?php echo DEPOSIT_HYPERMART; ?>)">
					</div>
				</div>
				<div class="withdrawal-page__content__input row bank-deposit payment-gateway">
					<label for="crypto-amount">&nbsp;</label>
					<div class="col-12" style="display: flex;">
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_amount(this, 200, <?php echo DEPOSIT_HYPERMART; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="200">
								<div class="deposit-amount__item__label__title"> 200</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="200">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_amount(this, 500, <?php echo DEPOSIT_HYPERMART; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="500">
								<div class="deposit-amount__item__label__title"> 500</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="500">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_amount(this, 1000, <?php echo DEPOSIT_HYPERMART; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="1000">
								<div class="deposit-amount__item__label__title"> 1000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="1000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_amount(this, 3000, <?php echo DEPOSIT_HYPERMART; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="3000">
								<div class="deposit-amount__item__label__title"> 3000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="3000">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="deposit-amount-select deposit-amount__item__label fasttablinks<?php echo DEPOSIT_HYPERMART; ?>" onclick="select_amount(this, 5000, <?php echo DEPOSIT_HYPERMART; ?>)" style="margin-right: 5px; cursor: pointer;">
							<label class="amountBtn" data-amount="5000">
								<div class="deposit-amount__item__label__title"> 5000</div>
								<input class="deposit-amount__item__label__checkbox" type="radio" name="depositAmountBtn" data-amount="5000">
								<span class="checkmark"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="currency_rate"><?php echo $this->lang->line('label_currency_rate'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="currency_rate_<?php echo DEPOSIT_HYPERMART; ?>" value="<?php echo $depositRate; ?>">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="actual_amount"><?php echo $this->lang->line('label_actual_amount'); ?></label>
					<div class="col-12">
						<input type="text" class="form-control" disabled id="actual_amount_<?php echo DEPOSIT_HYPERMART; ?>" value="0">
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for=""><?php echo $this->lang->line('label_promotion'); ?></label>
					<div class="col-12">
						<span id="showPromo" class="custom-radio-tag deposit-page__content__title deposit-add-promotion">
						<label><input type="radio" name="promoId" value="0" checked=""><span class="subtitle">&nbsp; <?php echo $this->lang->line('label_without_promotion'); ?></span></label><br>
							<?php if (isset($promotion) && !empty($promotion)) { ?>
								<?php foreach ($promotion as $promotion_row) { ?>
									<label><input type="radio" name="promoId" value="<?php echo $promotion_row['promotion_id']; ?>"><span class="subtitle">&nbsp; <?php echo $promotion_row['promotion_title']; ?> /&nbsp;<span class="promo_tc"><a href="#promotions-<?php echo $promotion_row['promotion_id']; ?>" onclick="open_promo(<?php echo $promotion_row['promotion_id']; ?>);" data-toggle="modal" style="color:red;"><?php echo $this->lang->line('label_read_promotion_tnc'); ?></a></span></span></label><br>
								<?php } ?>
							<?php } ?>
					</div>
				</div>
				<div class="withdrawal-page__content__input row">
					<label for="">&nbsp;</label>
					<div class="col-12">
						<div style="color: red;"><?php echo $this->lang->line('label_read_promotion_tnc_before_choosing'); ?></div>
						<div class="mb-2" style="color: red;"><?php echo $this->lang->line('label_not_accept_atm'); ?></div>

						<div class="deposit-amount__action">
							<button class="btn btn-primary withdrawal-submit"><?php echo $this->lang->line('label_submit'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</section>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
	<div class="modal-dialog custom-modal-size" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('mobile/parts/footer'); ?>