<?php $this->load->view('web/parts/header'); ?>

<style>
	table.dataTable tr {

		background-color: transparent;

	}



	table.dataTable thead tr:first-child {

		background-color: #181818;

	}



	table.dataTable tr:nth-child(odd) {

		background-color: #606870;

	}



	table.dataTable tr:nth-child(even) {

		background-color: #181818;

	}



	table.dataTable thead tr td {

		border-right: 1px solid #b1861c;

	}



	table.dataTable thead tr td:last-child {

		border-right: none;

	}



	table.dataTable td.sorting_1 {

		background-color: transparent;

	}



	table.dataTable td.sorting_1 a {

		color: #00BFFF;

	}



	table.dataTable td.dataTables_empty {

		font-size: big;

		line-height: 20px;

		padding: 1%;

	}



	.dataTables_info {

		width: auto;

		margin-left: 30px;

		float: left;

		display: inline;

	}



	.dataTables_paginate {

		margin-right: 23px;

		display: inline;

	}



	.paging_full_numbers {

		width: 450px;

	}



	td.tdL {

		text-align: left;

		padding: 5px;

	}



	td.tdC {

		text-align: center;

		padding: 5px 0px !important;

	}



	td.tdR {

		text-align: right;

		padding: 5px;

	}



	.step {

		display: inline-block;

		margin-left: 30px;

	}



	div.dataTables_length {

		margin-left: 31px;

		padding-top: 20px;

		padding-bottom: 5px;

	}



	div.dataTables_filter {

		margin-right: 34px;

		padding-top: 20px;

		padding-bottom: 5px;

	}



	div.dataTables_length select,

	div.dataTables_filter input {

		background: #181818;

		border: 1px solid;

		height: 25px;

		display: inline-block;

	}



	.paging_simple_numbers {

		float: right !important;

		text-align: right !important;

	}



	.paging_simple_numbers a.paginate_button {

		display: inline-block !important;

		padding: 4px 9px !important;

		margin: 0 !important;

		font-size: 13px !important;

		-webkit-border-radius: 0px !important;

		margin-left: -1px !important;

		line-height: 1.42857 !important;

		text-decoration: none !important;

		background-color: #FFF !important;

		border: 1px solid #DDD !important;

		color: #000 !important;

	}



	.paging_simple_numbers a.paginate_button.current {

		background: #fdbe07 !important;

		color: #472901 !important;

	}



	.paging_simple_numbers a.paginate_button:hover {

		color: #000 !important;

		background-color: #EEE !important;

		border-color: #DDD !important;

	}



	.DDhistoryTable table {

		margin: 18px auto;

		border: 1px solid #2e2e2e;

	}



	.wallet-wrap {

		width: 1115px;

		display: inline-block;

	}



	.acc-page-devider {

		padding-bottom: 5px;

	}



	.dataTables_processing {

		position: absolute !important;

		top: 50% !important;

		left: 50% !important;

		width: 250px !important;

		height: 30px !important;

		margin-left: -125px !important;

		margin-top: -15px !important;

		padding: 14px 0 2px !important;

		border: 1px solid #ddd !important;

		text-align: center !important;

		color: #999 !important;

		font-size: 14px !important;

		background-color: #fff !important;

		z-index: 1001 !important;

	}



	#transaction-table_processing {

		padding: 5px 0 !important;

	}
</style>

<!-- Begin Content -->

<section class="">
	<div id="liveAlertPlaceholder"></div>

	<div class="container py-5 mb-5">
		<div class="row account-layout__container__content">
			<!-- Begin Left Wallet -->

			<?php $this->load->view('web/parts/wallet'); ?>

			<!-- End Left Wallet -->



			<!-- Begin Right Wallet -->

			<div class="col-9 account-layout__container__content__right">
				<div class="account-layout__container__content__right__content transaction-history-page__content">
					<div class="my-profile-page__content__title">
						<?php echo $this->lang->line('label_transaction_history'); ?><br>
						<span class="text-secondary" style="font-size:12px;"><?php echo $this->lang->line('label_transaction1'); ?></span>
					</div>
					<?php echo form_open('ajax/transaction_search', array('id' => 'transaction-form')); ?>
					<div class="transaction-history-page__content__status row">
						<div class="transaction-history-page__content__filter">
							<div class="col-4 col-xl-5 transaction-history-page__content__status__item">

								<label><span><?php echo $this->lang->line('label_transaction_type'); ?></span></label>
								<div class="btn-group">
									<a href="javascript:void(0);" onclick="select_transaction_type(this, 1)" class="btn btn-outline-secondary typeBtn active"><?php echo $this->lang->line('label_deposit'); ?></a>
									<a href="javascript:void(0);" onclick="select_transaction_type(this, 2)" class="btn btn-outline-secondary typeBtn"><?php echo $this->lang->line('label_withdrawal'); ?></a>
									<a href="javascript:void(0);" onclick="select_transaction_type(this, 7)" class="btn btn-outline-secondary typeBtn"><?php echo $this->lang->line('label_betting'); ?></a>
								</div>

								<input type="hidden" name="transaction_type" id="transaction_type" value="1">

							</div>

							<div class="col-8 col-xl-7 transaction-history-page__content__status__item">
								<label><span><?php echo $this->lang->line('label_transaction_date'); ?></span></label>
								<div class="transaction-history-page__content__status__item__date-range input-group">
									<input class="form-control datefilterFrom " type="text" id="from_date" name="from_date" dt-value='<?php echo date('Y-m-d') ?>' value="<?php echo date('Y-m-d') ?>" readonly>
									<span class="input-group-text">to</span>
									<input class="form-control datefilterTo" type="text" id="to_date" name="to_date" dt-value='<?php echo date('Y-m-d') ?>' value="<?php echo date('Y-m-d') ?>" readonly="">

								</div>

							</div>
						</div>
					</div>

					<div class="transaction-history-page__content__status row">
						<div class="col-4 col-xl-6 transaction-history-page__content__status__item">

							<div class="transaction-history-page__content__filter">
								<label><span><?php echo $this->lang->line('label_transaction_date'); ?></span></label>
								<div class="btn-group">
									<a onclick="select_transaction_date(this, 1)" class="btn btn-outline-secondary dateRangeBtn active" data-range="today"><?php echo $this->lang->line('label_today'); ?></a>
									<a onclick="select_transaction_date(this, 7)" class="btn btn-outline-secondary dateRangeBtn" data-range="week"><?php echo $this->lang->line('label_in_a_week'); ?></a>
									<a onclick="select_transaction_date(this, 15)" class="btn btn-outline-secondary dateRangeBtn" data-range="15days"><?php echo $this->lang->line('label_in_15_days'); ?></a>
									<a onclick="select_transaction_date(this, 30)" class="btn btn-outline-secondary dateRangeBtn" data-range="month"><?php echo $this->lang->line('label_in_a_month'); ?></a>
								</div>
							</div>
						</div>
						<div class="col-8 col-xl-6 transaction-history-page__content__status__item">
							<button class="btn btn-outline-primary" type="submit" id="submit" name="submit"><?php echo $this->lang->line('label_submit'); ?></button>

						</div>
					</div>


					<?php echo form_close(); ?>

					<div class="DDhistoryTable">

						<div id="dptable-<?php echo TRANSACTION_TYPE_DEPOSIT; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_DEPOSIT; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_type'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_status'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_remark'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_type'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_status'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_remark'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_DEPOSIT_POINT; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_DEPOSIT_POINT; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_remark'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_WITHDRAWAL_POINT; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_WITHDRAWAL_POINT; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_remark'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_TRANSFER; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_TRANSFER; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_transfer_from'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_transfer_to'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_PROMOTION; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_PROMOTION; ?>" width="100%" style="border-collapse: collapse; padding: 5px;" class="dataTable">

								<thead>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_remark'); ?></td>

									</tr>

								</thead>

								<tbody></tbody>

							</table>

						</div>

						<div id="dptable-<?php echo TRANSACTION_TYPE_BET; ?>" class="transaction-table" style="margin-top:5px; color:#FFF; font-size:small;display:none;" width="100%">

							<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_BET; ?>" width="100%" style="border-collapse: collapse;" class="dataTable">

								<thead>

									<tr style="text-align: center;">

										<td align="center" class="tab-top tdL" colspan="6"> <?php echo $this->lang->line('table_bet_web_1'); ?><span style="color: #fdbe07; font-style: italic;"><?php echo $this->lang->line('table_bet_web_2'); ?></span><br><small style="color:red;"> <?php echo $this->lang->line('table_bet_web_3'); ?> <span id="rollover_lastupdate"><?php echo date('Y-m-d h:i:00', strtotime('-30 minutes')); ?></span></small>

										</td>

									</tr>

									<tr>

										<td class="tdC"><?php echo $this->lang->line('label_date'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_game_provider'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_game_type'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_amount'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_win_loss'); ?></td>

										<td class="tdC"><?php echo $this->lang->line('label_status'); ?></td>

									</tr>

								</thead>

								<tbody style="color:#FFF;">

									<tr>

										<td class="tab-value tdL"><?php echo $this->lang->line('table_bet_requirement'); ?></td>

										<td id="ro_requirement" class="tab-value tdR">0.00</td>

									</tr>

									<tr>

										<td class="tab-value tdL"><?php echo $this->lang->line('table_bet_accumulated'); ?></td>

										<td id="current_ro" class="tab-value tdR">0.00</td>

									</tr>

								</tbody>

							</table>

						</div>

						<br>

					</div>

				</div>
			</div>

			<!-- End Right Wallet -->

		</div>

	</div>

</section>
<!-- End Content -->


<script>
	function init() {
		_registerClickEvent();

	}

	function _registerClickEvent() {
		$('.dateRangeBtn').off('click').click(function() {
			$('.dateRangeBtn').removeClass('active');
			$(this).addClass('active');
			let target = $(this).data('range');
			let title = $(this).html();

		});

	}
</script>
<?php $this->load->view('web/parts/footer'); ?>