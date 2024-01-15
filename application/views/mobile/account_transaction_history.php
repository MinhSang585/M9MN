<?php $this->load->view('mobile/parts/header'); ?>
<style>
	table.dataTable tr {
		background-color: transparent;
	}

	table.dataTable thead tr:first-child {
		background-color: #232323;
		border-bottom: 1px solid #2e2e2e;
	}

	table.dataTable tr:nth-child(even) {
		background-color: #333;
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

	table.dataTable a {
		color: #00BFFF !important;
	}

	table.dataTable td.dataTables_empty {
		font-size: big;
		line-height: 20px;
		padding: 1%;
	}

	.dataTables_info {
		width: auto;
		margin: 0px;
		float: left;
		display: inline;
	}

	.dataTables_paginate {
		margin: 0px;
		display: inline;
	}

	.paging_full_numbers {
		width: auto;
		margin: auto;
	}

	td.tdL {
		text-align: left;
		padding: 5px;
	}

	td.tdC {
		text-align: center;
		padding: 5px 0px;
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
		padding-top: 20px;
		padding-bottom: 5px;
	}

	div.dataTables_filter {
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
		background-color: #fdbe07 !important;
		color: #472901 !important;
	}

	.paging_simple_numbers a.paginate_button:hover {
		color: #000 !important;
		background-color: #EEE !important;
		border-color: #DDD !important;
	}

	.DDhistoryTable {
		padding-right: 15px;
		padding-left: 15px;
	}

	.DDhistoryTable table {
		width: 100% !important;
		margin: auto;
		border: 1px solid #2e2e2e;
		margin-bottom: 15px;
	}

	table.dataTable tbody tr {
		background-color: #232323;
	}

	.df,
	.dt {
		background-color: #181818 !important;
		border: 1px solid #2e2e2e;
		color: #fff;
	}

	.deposit-type-choice-wrap {
		display: flex;
		justify-content: space-between;
	}

	.deposit-type-choice {
		cursor: pointer;
	}

	.deposit-date-choice {
		display: inline-block;
		height: 28px;
		max-height: 74px;
	}

	.deposit-date-choice img {
		max-height: 100%;
	}

	.btn-outline-custom-1 {
		font-size: 0.7rem;
		color: #000;
		border-color: #000;
	}

	.btn-outline-custom-1:not(:disabled):not(.disabled).active,
	.btn-outline-custom-1:not(:disabled):not(.disabled):active,
	.show>.btn-outline-custom-1.dropdown-toggle {
		color: #FBBD2E;
		background-color: #000;
		border-color: #FBBD2E;
	}

	.btn-outline-custom-1:hover {
		color: #FBBD2E;
		background-color: #000;
		border-color: #FBBD2E;
	}

	.btn-outline-custom-1.focus,
	.btn-outline-custom-1:focus {
		box-shadow: 0 0 0 0.2rem rgb(251 189 46 / 50%);
	}

	header {
		height: 50px !important;
		box-shadow: 0 5px 10px #ccd7e6 !important;
		color: #6c6e71 !important;
		background: #fff !important;
	}

	.lr {
		display: none;
	}

	.m-bottom-menu {
		border-top: none;
		box-shadow: 0 -5px 8px -3px #ccd7e6;
	}

	.profile_title {
		text-align: center;
		line-height: 40px;
		font-size: 19px;
	}

	#comm-back-button {
		background: url(<?php echo base_url('assets/mobile/images/icon-back.png'); ?>) no-repeat;
		z-index: 99;
		width: 23px;
		height: 23px;
		background-size: 50%;
		position: absolute;
		left: 6px;
		top: 10px;
		filter: invert(60%);
	}
</style>
<section class="main withdrawal">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
			</a>
			<div class="title"><?php echo $this->lang->line('label_transaction_history'); ?></div>
			<div class="opacity-0 ">
				<img src="obv1/m/images/icons/help.png" alt="">
			</div>
		</div>
	</nav>
	<div class="container pb-10vh">
		<div class="infos mt-5">

			<?php echo form_open('ajax/transaction_search', array('id' => 'transaction-form')); ?>

			<div class="transaction-history-page__content__status row">
				<div class="transaction-history-page__content__filter">
					<div class="col-12 transaction-history-page__content__status__item">

						<label><span><?php echo $this->lang->line('label_transaction_type'); ?></span></label>
						<div class="btn-group w-100">
							<a href="javascript:void(0);" onclick="select_transaction_type(this, 1)" class="btn btn-outline-secondary typeBtn active"><?php echo $this->lang->line('label_deposit'); ?></a>
							<a href="javascript:void(0);" onclick="select_transaction_type(this, 2)" class="btn btn-outline-secondary typeBtn"><?php echo $this->lang->line('label_withdrawal'); ?></a>
							<a href="javascript:void(0);" onclick="select_transaction_type(this, 7)" class="btn btn-outline-secondary typeBtn"><?php echo $this->lang->line('label_betting'); ?></a>
						</div>

						<input type="hidden" name="transaction_type" id="transaction_type" value="1">

					</div>

					<div class="col-12 transaction-history-page__content__status__item">
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
				<div class="col-12 transaction-history-page__content__status__item">

					<div class="transaction-history-page__content__filter">
						<label><span><?php echo $this->lang->line('label_transaction_date'); ?></span></label>
						<div class="btn-group w-100">
							<a onclick="select_transaction_date(this, 1)" class="btn btn-outline-secondary dateRangeBtn active" data-range="today"><?php echo $this->lang->line('label_today'); ?></a>
							<a onclick="select_transaction_date(this, 7)" class="btn btn-outline-secondary dateRangeBtn" data-range="week"><?php echo $this->lang->line('label_in_a_week'); ?></a>
							<a onclick="select_transaction_date(this, 15)" class="btn btn-outline-secondary dateRangeBtn" data-range="15days"><?php echo $this->lang->line('label_in_15_days'); ?></a>
							<a onclick="select_transaction_date(this, 30)" class="btn btn-outline-secondary dateRangeBtn" data-range="month"><?php echo $this->lang->line('label_in_a_month'); ?></a>
						</div>
					</div>
				</div>
				<div class="transaction-history-page__content__status__item">
					<button class="btn btn-primary" type="submit" id="submit" name="submit"><?php echo $this->lang->line('label_submit'); ?></button>

				</div>
			</div>


			<?php echo form_close(); ?>
			<div class="DDhistoryTable p-0">
				<div id="dptable-<?php echo TRANSACTION_TYPE_DEPOSIT; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_DEPOSIT; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_type'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_status'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_remark'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_type'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_status'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_remark'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_DEPOSIT_POINT; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_DEPOSIT_POINT; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_remark'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_WITHDRAWAL_POINT; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_WITHDRAWAL_POINT; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_remark'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_TRANSFER; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_TRANSFER; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_transfer_from'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_transfer_to'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_PROMOTION; ?>" class="transaction-table" style="margin-top:10px; color:#FFF; font-size:10px; display:none;" width="100%">
					<table cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_PROMOTION; ?>" width="100%" style="border-collapse: collapse;" class="dataTable no-footer">
						<thead>
							<tr style="text-align: center;" role="row">
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_remark'); ?></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div id="dptable-<?php echo TRANSACTION_TYPE_BET; ?>" class="transaction-table" style="margin-top:60px; color:#FFF; font-size:small; display: none;overflow-x: scroll;" width="100%">
					<table class="dataTable" cellpadding="0" cellspacing="5" border="0" id="transaction-table-<?php echo TRANSACTION_TYPE_BET; ?>" width="100%" style="border-collapse: collapse;">
						<thead>
							<tr style="text-align: center;">
								<td align="center" class="tab-top tdL" colspan="6">
									<?php echo $this->lang->line('table_bet_mobile_1'); ?><small style="color: #fdbe07; font-style: italic;"><?php echo $this->lang->line('table_bet_mobile_2'); ?></small><br><small style="color:red;"> <?php echo $this->lang->line('table_bet_mobile_3'); ?> <span id="rollover_lastupdate"><?php echo date('Y-m-d h:i:00', strtotime('-30 minutes')); ?></span></small>
								</td>
							</tr>
							<tr>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_date'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_game_provider'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_game_type'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_amount'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_win_loss'); ?></td>
								<td align="center" class="tab-top sorting_disabled tab-value tdC"><?php echo $this->lang->line('label_status'); ?></td>
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
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('mobile/parts/footer'); ?>