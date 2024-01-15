<?php $this->load->view('mobile/parts/header'); ?>

<section class="main wallet mt-3">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
			</a>
			<div class="title"><?php echo $this->lang->line('label_wallet')?></div>
			<div class="opacity-0 d-none">
				<img src="obv1/m/images/icons/help.png" alt="">
			</div>
			<a class="" href="<?php echo site_url('account/transaction_history') ?>">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_history.png'); ?>" width="20px" alt="" class="me-1">
				<span><?php echo $this->lang->line('label_history');?></span>
			</a>
		</div>
	</nav>
	<div class="wallet__content container-fluid mt-4">
		<div class="wallet__content__top">
			<div><?php echo $this->lang->line('label_total_balance')?></div>
			<div data-dom="wallet-ccl-body" data-setting='{"display": "none", "numberFormat": true }' style="display: none;"></div>
			<h3><?php echo $this->lang->line('system_currency')?> <span id="balance" data-wallet-display="wallet-total">0.00</span> <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_icon_refresh.png'); ?>" width="20px" onclick="_Wallet.refreshCclList();" class="trigger-spin"></h3>
			<div class="wallet__content__top__icons">
				<a href="<?php echo site_url('account/deposit') ?>">
					<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_card_icon_deposit.png'); ?>">
					<p><?php echo $this->lang->line('label_deposit');?></p>
				</a>
				<a href="<?php echo site_url('account/withdrawal') ?>">
					<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_card_icon_withdraw.png'); ?>">
					<p><?php echo $this->lang->line('label_withdrawal');?></p>
				</a>
				<a href="javascript: alert('Coming Soon')">
					<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_card_icon_rebate.png'); ?>">
					<p><?php echo $this->lang->line('label_rebate');?></p>
				</a>
				<a href="<?php echo site_url('account/badding_bank') ?>">
					<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_card_icon_addbank.png'); ?>">
					<p><?php echo $this->lang->line('label_bank_account');?></p>
				</a>
			</div>
		</div>
		<!-- <div class="wallet__content__bottom" id="recent-transaction">
			<div class="no-recent-transaction-wrapper">
				<img src="<?php echo base_url('assets/mobile/img/user_center/img_empty.png'); ?>" alt="">
				<div class="no-recent-transaction-text">No latest transaction record</div>
				<a href="mydeposit.html" class="btn btn-primary no-transaction-dep-btn">Deposit now</a>
			</div>
		</div> -->
	</div>
</section>
<?php $this->load->view('mobile/parts/footer'); ?>