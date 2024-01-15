<?php $this->load->view('web/parts/header'); ?>
<section class="main ">
	<div id="liveAlertPlaceholder"></div>

	<div class="account-layout__container container">
		<div class="row account-layout__container__content">
			<?php $this->load->view('web/parts/wallet'); ?>

			<div class="col-9 account-layout__container__content__right">
				<div class="account-layout__container__content__right__content my-wallet-page__content">
					<h2 class="my-wallet-page__content__title txtupper"><?php echo $this->lang->line('label_my_wallet') ?></h2>
					<div class="my-wallet-page__content__card">
						<div class="my-wallet-page__content__card__title">
							<span><?php echo $this->lang->line('label_main_wallet') ?></span>
						</div>
					</div>
					<div class="row my-wallet-page__content__card__balance">
						<div class="col-7">
							<div class="my-wallet-page__content__card__balance__item left">
								<img src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_mywallet_off.png') ?>" />
								<span><?php echo $this->lang->line('label_total_balance') ?></span>
								<div class="vr"></div>
								<span class="currency"><?php echo $this->lang->line('system_currency') ?> <span id="balance2">0.00</span></span>
								<img class="refresh-balance" role="button" id="refresh_icon" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_refresh.png') ?>" />
							</div>
						</div>
						<div class="col-5">
							<div class="my-wallet-page__content__card__balance__item right">
								<a href="<?php echo site_url('account/deposit') ?>">
									<img src="<?php echo base_url('assets/desktop/images/user_center/uc_mywallet_icon_deposit.png') ?>" />
									<span><?php echo $this->lang->line('label_deposit') ?></span>
								</a>
								<a href="<?php echo site_url('account/withdrawal') ?>">
									<img src="<?php echo base_url('assets/desktop/images/user_center/uc_mywallet_icon_withdrawal.png') ?>" />
									<span><?php echo $this->lang->line('label_withdrawal') ?></span>
								</a>
								<a href="javascript: alert('Coming Soon')">
									<img src="<?php echo base_url('assets/desktop/images/user_center/uc_mywallet_icon_rebate.png') ?>" />
									<span><?php echo $this->lang->line('label_rebate') ?></span>
								</a>
								<a href="<?php echo site_url('account/badding_bank') ?>">
									<img src="<?php echo base_url('assets/desktop/images/user_center/uc_mywallet_icon_bankacc.png') ?>" />
									<span><?php echo $this->lang->line('label_bank_account') ?></span>
								</a>
							</div>
						</div>
					</div>
					<!-- <div class="my-wallet-page__content__card">
						<div class="my-wallet-page__content__card__title">
							<span><?php echo $this->lang->line('label_game_wallet_balance'); ?></span>
						</div>
						<div class="my-wallet-page__content__card__Recent_Transaction">
							<table class="table border-0" id="wallet_table">
							<thead>
										<tr>
											<th scope="col"><?php echo $this->lang->line('label_game_provider'); ?></th>
											<th scope="col"><?php echo $this->lang->line('label_amount'); ?></th>

										</tr>
									</thead>
								<tbody>
									<tr id="wallet_sa">
										<td class="subtitle"> <input type="hidden" name="w_bal_sa" id="w_bal_sa" value="0"><?php echo $this->lang->line('game_sa'); ?></td>
										<td id="text_cursa" class="">0.00</td>
									</tr>
									<tr id="wallet_ag">
										<td class="subtitle"> <input type="hidden" name="w_bal_ag" id="w_bal_ag" value="0"><?php echo $this->lang->line('game_ag'); ?></td>
										<td id="text_curag" class="">0.00</td>
									</tr>
									<tr id="wallet_wm">
										<td class="subtitle"> <input type="hidden" name="w_bal_wm" id="w_bal_wm" value="0"><?php echo $this->lang->line('game_wm'); ?></td>
										<td id="text_curwm" class="">0.00</td>
									</tr>
									<tr id="wallet_dg">
										<td class="subtitle"> <input type="hidden" name="w_bal_dg" id="w_bal_dg" value="0"><?php echo $this->lang->line('game_dg'); ?></td>
										<td id="text_curdg" class="">0.00</td>
									</tr>
									<tr id="wallet_sx">
										<td class="subtitle"> <input type="hidden" name="w_bal_sx" id="w_bal_sx" value="0"><?php echo $this->lang->line('game_sx'); ?></td>
										<td id="text_cursx" class="">0.00</td>
									</tr>
									<tr id="wallet_pt">
										<td class="subtitle"> <input type="hidden" name="w_bal_pt" id="w_bal_pt" value="0"><?php echo $this->lang->line('game_pt'); ?></td>
										<td id="text_curpt" class="">0.00</td>
									</tr>
									<tr id="wallet_jk">
										<td class="subtitle"> <input type="hidden" name="w_bal_jk" id="w_bal_jk" value="0"><?php echo $this->lang->line('game_jk'); ?></td>
										<td id="text_curjk" class="">0.00</td>
									</tr>
									<tr id="wallet_sg">
										<td class="subtitle"> <input type="hidden" name="w_bal_sg" id="w_bal_sg" value="0"><?php echo $this->lang->line('game_sg'); ?></td>
										<td id="text_cursg" class="">0.00</td>
									</tr>
									<tr id="wallet_mg">
										<td class="subtitle"> <input type="hidden" name="w_bal_mg" id="w_bal_mg" value="0"><?php echo $this->lang->line('game_mg'); ?></td>
										<td id="text_curmg" class="">0.00</td>
									</tr>
									<tr id="wallet_hb">
										<td class="subtitle"> <input type="hidden" name="w_bal_hb" id="w_bal_hb" value="0"><?php echo $this->lang->line('game_hb'); ?></td>
										<td id="text_curhb" class="">0.00</td>
									</tr>
									<tr id="wallet_pp">
										<td class="subtitle"> <input type="hidden" name="w_bal_pp" id="w_bal_pp" value="0"><?php echo $this->lang->line('game_pp'); ?></td>
										<td id="text_curpp" class="">0.00</td>
									</tr>
									<tr id="wallet_ibc">
										<td class="subtitle"> <input type="hidden" name="w_bal_ibc" id="w_bal_ibc" value="0"><?php echo $this->lang->line('game_ibc'); ?></td>
										<td id="text_curibc" class="">0.00</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<script>
		function MywalletCtrl() {
			var vm = this;
			vm.init = init;

			function init() {
				getRecentTransaction();
			}

			function getRecentTransaction() {
				$.ajax({
					"dataType": 'json',
					"type": "POST",
					"url": "a/getRecentTransaction"
				}).success(function(data) {
					if (data.status == 0) {
						if (data.d.data.length > 0) {
							$.each(data.d.data, function(i, d) {
								let tpl = '';
								if (d.type === 'dep') {
									tpl = $("#transaction-history-deposit-tpl").html();
								} else if (d.type === 'with') {
									tpl = $("#transaction-history-withdrawal-tpl").html();
								} else if (d.type === 'promo') {
									tpl = $("#transaction-history-promo-tpl").html();
								}
								let text_color = (d.status === 'Approved') ? '#0f9214' : (d.status === 'Rejected') ? '#ff0000' : '#000'
								var html = _Util.compile(tpl, {
									NUM: i + 1,
									STATUS: d.status,
									DATE: d.datetime,
									AMOUNT: d.amount,
									BANK: d.bank,
									CSS: text_color,
									PROMO_NAME: d.promo
								});
								$('#recent-transaction').append(html);
							})
						} else {
							let html = '';
							html += '<tr><td colspan=6><div class="no-recent-transaction-wrapper">';
							html += '	<img src="obv1/images/no-recent-transaction.png" alt="" class="notransaction-img">';
							html += '	<div class="no-recent-transaction-text">No latest transaction record</div>';
							html += '	<a href="mydeposit" class="btn btn-primary no-transaction-dep-btn">Deposit now</a>';
							html += '</div></td></tr>';
							$('#recent-transaction').html(html);
						}
					}
				});
			}
		}
		_fn.mywallet = MywalletCtrl;
	</script>
</section>
<?php $this->load->view('web/parts/footer'); ?>