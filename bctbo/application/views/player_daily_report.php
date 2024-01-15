<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
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
						<input id="total_amount" type="hidden">
						<div class="card card-info">						
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_summary');?></label>
							</div>
							<div class="card-body">
								<table id="summary-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_player_username');?></th>
											<th><?php echo $this->lang->line('label_main_wallet');?></th>
											<th><?php echo $this->lang->line('label_main_wallet_old');?></th>
											<th><?php echo $this->lang->line('label_deposit');?></th>
											<th><?php echo $this->lang->line('deposit_offline_banking');?></th>
											<th><?php echo $this->lang->line('label_deposit_online');?></th>
											<th><?php echo $this->lang->line('label_deposit_point');?></th>
											<th><?php echo $this->lang->line('label_withdrawal');?></th>
											<th><?php echo $this->lang->line('label_withdrawal');?></th>
											<th><?php echo $this->lang->line('label_withdrawal_online');?></th>
											<th><?php echo $this->lang->line('label_withdrawal_point');?></th>
											<th><?php echo $this->lang->line('label_adjust');?></th>
											<th><?php echo $this->lang->line('transfer_adjust_in');?></th>
											<th><?php echo $this->lang->line('transfer_adjust_out');?></th>
											<th><?php echo $this->lang->line('label_win_loss');?></th>
											<th><?php echo $this->lang->line('label_promotion_amount');?></th>
											<th><?php echo $this->lang->line('label_bonus');?></th>
											<th><?php echo $this->lang->line('label_game_wallet');?></th>
											<th><?php echo $this->lang->line('label_withdrawal_pending');?></th>
											<th><?php echo $this->lang->line('label_bet_pending');?></th>
											<th><?php echo $this->lang->line('label_total_verify');?></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
									</tfoot>
								</table>
							</div>
						</div>

						<input id="deposit_offline_total_div" type="hidden">
						<input id="deposit_online_total_div" type="hidden">
						<input id="deposit_credit_total_div" type="hidden">
						<input id="deposit_hypermart_total_div" type="hidden">
						<input id="adjust_in_total_div" type="hidden">
						<input id="adjust_out_total_div" type="hidden">
						<input id="point_in_total_div" type="hidden">
						<input id="point_out_total_div" type="hidden">
						<input id="promotion_total_div" type="hidden">
						<input id="bet_total_div" type="hidden">
						<input id="withdrawal_total_div" type="hidden">
						<input id="pending_bet_total_div" type="hidden">
						<input id="pending_withdrawal_total_div" type="hidden">
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('deposit_offline_banking');?></label>
							</div>
							<div class="card-body">
								<table id="deposit_offline-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="50"><?php echo $this->lang->line('label_date');?></th>
											<th width="50"><?php echo $this->lang->line('label_type');?></th>
											<th width="50"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_gateway');?></th>
											<th width="80"><?php echo $this->lang->line('label_transaction_code');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_info');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="30"><?php echo $this->lang->line('label_rate');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="50"><?php echo $this->lang->line('label_status');?></th>
											<th width="50"><?php echo $this->lang->line('label_ip');?></th>
											<th width="50"><?php echo $this->lang->line('label_remark');?></th>
											<th width="50"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_deposit_apply_offline">0</span></th>
											<th></th>
											<th><span id="total_deposit_amount_offline">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('deposit_online_banking');?></label>
							</div>
							<div class="card-body">
								<table id="deposit_online-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="50"><?php echo $this->lang->line('label_date');?></th>
											<th width="50"><?php echo $this->lang->line('label_type');?></th>
											<th width="50"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_gateway');?></th>
											<th width="150"><?php echo $this->lang->line('label_transaction_code');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_info');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="30"><?php echo $this->lang->line('label_rate');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="50"><?php echo $this->lang->line('label_status');?></th>
											<th width="50"><?php echo $this->lang->line('label_ip');?></th>
											<th width="50"><?php echo $this->lang->line('label_remark');?></th>
											<th width="50"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_deposit_apply_online">0</span></th>
											<th></th>
											<th><span id="total_deposit_amount_online">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('deposit_credit_card');?></label>
							</div>
							<div class="card-body">
								<table id="deposit_credit_card-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="50"><?php echo $this->lang->line('label_date');?></th>
											<th width="50"><?php echo $this->lang->line('label_type');?></th>
											<th width="50"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_gateway');?></th>
											<th width="150"><?php echo $this->lang->line('label_transaction_code');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_info');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="30"><?php echo $this->lang->line('label_rate');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="50"><?php echo $this->lang->line('label_status');?></th>
											<th width="50"><?php echo $this->lang->line('label_ip');?></th>
											<th width="50"><?php echo $this->lang->line('label_remark');?></th>
											<th width="50"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_deposit_apply_credit">0</span></th>
											<th></th>
											<th><span id="total_deposit_amount_credit">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('deposit_hypermart');?></label>
							</div>
							<div class="card-body">
								<table id="deposit_hypermart-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="50"><?php echo $this->lang->line('label_date');?></th>
											<th width="50"><?php echo $this->lang->line('label_type');?></th>
											<th width="50"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_gateway');?></th>
											<th width="150"><?php echo $this->lang->line('label_transaction_code');?></th>
											<th width="150"><?php echo $this->lang->line('label_payment_info');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="30"><?php echo $this->lang->line('label_rate');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="50"><?php echo $this->lang->line('label_status');?></th>
											<th width="50"><?php echo $this->lang->line('label_ip');?></th>
											<th width="50"><?php echo $this->lang->line('label_remark');?></th>
											<th width="50"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_deposit_apply_hypermart">0</span></th>
											<th></th>
											<th><span id="total_deposit_amount_hypermart">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<!-- /.card -->
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_adjust_in');?></label>
							</div>
							<div class="card-body">
								<table id="adjust_in-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="120"><?php echo $this->lang->line('label_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_before_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_in_amount');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_out_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_after_amount');?></th>
											<th width="230"><?php echo $this->lang->line('label_remark');?></th>
											<th width="130"><?php echo $this->lang->line('label_execution_account');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th></th>
											<th><span id="total_points_deposited_adjust_in">0</span></th>
											<th><span id="total_points_withdrawn_adjust_in">0</span></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_point_in');?></label>
							</div>
							<div class="card-body">
								<table id="point_in-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="120"><?php echo $this->lang->line('label_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_before_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_in_amount');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_out_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_after_amount');?></th>
											<th width="230"><?php echo $this->lang->line('label_remark');?></th>
											<th width="130"><?php echo $this->lang->line('label_execution_account');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th></th>
											<th><span id="total_points_deposited_point_in">0</span></th>
											<th><span id="total_points_withdrawn_point_in">0</span></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-info">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_promotion');?></label>
							</div>
							<div class="card-body">
								<table id="promotion-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_date');?></th>
											<th><?php echo $this->lang->line('label_player_username');?></th>
											<th><?php echo $this->lang->line('label_promotion');?></th>
											<th><?php echo $this->lang->line('label_deposit_amount');?></th>
											<th><?php echo $this->lang->line('label_promotion_amount');?></th>
											<th><?php echo $this->lang->line('label_current_amount');?></th>
											<th><?php echo $this->lang->line('label_archieve_amount');?></th>
											<th><?php echo $this->lang->line('label_reward_amount');?></th>
											<th><?php echo $this->lang->line('label_is_reward');?></th>
											<th><?php echo $this->lang->line('label_reward_date');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_remark');?></th>
											<th><?php echo $this->lang->line('label_starting_date');?></th>
											<th><?php echo $this->lang->line('label_complete_date');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="8" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_reward_promotion">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-info" style="display: none;">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_bonus');?></label>
							</div>
							<div class="card-body">
								<table id="bonus-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_date');?></th>
											<th><?php echo $this->lang->line('label_bonus_name');?></th>
											<th><?php echo $this->lang->line('label_player_username');?></th>
											<th><?php echo $this->lang->line('label_reward_amount');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_remark');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_reward">0</span></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-success">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_bet');?></label>
							</div>
							<div class="card-body">
								<table id="winloss-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_time');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_provider');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_game');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_code');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_result');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_win_loss');?></th>
											<th width="120"><?php echo $this->lang->line('label_jackpot_win');?></th>
											<th width="120"><?php echo $this->lang->line('label_status');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="8" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_bet_amount">0</span></th>
											<th><span id="total_rolling_amount">0</span></th>
											<th><span id="total_win_loss">0</span></th>
											<th><span id="total_jackpot_win">0</span></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-danger">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_withdrawal');?></label>
							</div>
							<div class="card-body">
								<table id="withdrawal-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="80"><?php echo $this->lang->line('label_date');?></th>
											<th width="80"><?php echo $this->lang->line('label_type');?></th>
											<th width="80"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_name');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_account_name');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_account_no');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="80"><?php echo $this->lang->line('label_fee');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="80"><?php echo $this->lang->line('label_status');?></th>
											<th width="80"><?php echo $this->lang->line('label_ip');?></th>
											<th width="80"><?php echo $this->lang->line('label_remark');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_withdrawal">0</span></th>
											<th></th>
											<th><span id="total_withdrawal_fee_amount">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-danger">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_adjust_out');?></label>
							</div>
							<div class="card-body">
								<table id="adjust_out-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="120"><?php echo $this->lang->line('label_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_before_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_in_amount');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_out_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_after_amount');?></th>
											<th width="230"><?php echo $this->lang->line('label_remark');?></th>
											<th width="130"><?php echo $this->lang->line('label_execution_account');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th></th>
											<th><span id="total_points_deposited_adjust_out">0</span></th>
											<th><span id="total_points_withdrawn_adjust_out">0</span></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-danger">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('transfer_point_out');?></label>
							</div>
							<div class="card-body">
								<table id="point_out-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="120"><?php echo $this->lang->line('label_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_before_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_in_amount');?></th>
											<th width="130"><?php echo $this->lang->line('label_wallet_transfer_out_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_wallet_transfer_after_amount');?></th>
											<th width="230"><?php echo $this->lang->line('label_remark');?></th>
											<th width="130"><?php echo $this->lang->line('label_execution_account');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th></th>
											<th><span id="total_points_deposited_point_out">0</span></th>
											<th><span id="total_points_withdrawn_point_out">0</span></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-warning">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_withdrawal_pending');?></label>
							</div>
							<div class="card-body">
								<table id="withdrawal_pending-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="80"><?php echo $this->lang->line('label_date');?></th>
											<th width="80"><?php echo $this->lang->line('label_type');?></th>
											<th width="80"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_name');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_account_name');?></th>
											<th width="80"><?php echo $this->lang->line('label_bank_account_no');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="80"><?php echo $this->lang->line('label_fee');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="80"><?php echo $this->lang->line('label_status');?></th>
											<th width="80"><?php echo $this->lang->line('label_ip');?></th>
											<th width="80"><?php echo $this->lang->line('label_remark');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_withdrawal_pending">0</span></th>
											<th></th>
											<th><span id="total_withdrawal_fee_amount_pending">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="card card-warning">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_bet_pending');?></label>
							</div>
							<div class="card-body">
								<table id="winloss_pending-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_time');?></th>
											<th width="120"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_provider');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_game');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_code');?></th>
											<th width="120"><?php echo $this->lang->line('label_game_result');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th width="120"><?php echo $this->lang->line('label_win_loss');?></th>
											<th width="120"><?php echo $this->lang->line('label_jackpot_win');?></th>
											<th width="120"><?php echo $this->lang->line('label_status');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="8" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_bet_amount_pending">0</span></th>
											<th><span id="total_rolling_amount_pending">0</span></th>
											<th><span id="total_win_loss_pending">0</span></th>
											<th><span id="total_jackpot_win_pending">0</span></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
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
			$('#summary-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_deposit/');?><?php echo $player_id;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							set_total_value();
							load_pending_withdrawal("<?php echo $username;?>");
							load_game_wallet("<?php echo $player_id;?>");
							load_pending_bet("<?php echo $player_id;?>");
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [4], "visible": false},
					{"targets": [8], "visible": false},
					{"targets": [10], "visible": false},
					{"targets": [12], "visible": false},
					{"targets": [17], "visible": false},
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
			
			$('#deposit_offline-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_deposit_type_listing/');?><?php echo $username;?>/<?php echo DEPOSIT_OFFLINE_BANKING;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_offline_deposit();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
		
			$('#deposit_online-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_deposit_type_listing/');?><?php echo $username;?>/<?php echo DEPOSIT_ONLINE_BANKING;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_online_deposit();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
		
			$('#deposit_credit_card-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_deposit_type_listing/');?><?php echo $username;?>/<?php echo DEPOSIT_CREDIT_CARD;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_credit_deposit();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#deposit_hypermart-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_deposit_type_listing/');?><?php echo $username;?>/<?php echo DEPOSIT_HYPERMART;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_hypermart_deposit();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
			
			$('#adjust_in-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_transfer_type_listing/');?><?php echo $username;?>/<?php echo TRANSFER_ADJUST_IN;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_adjust_in();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false},
					{"targets": [6], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#adjust_out-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_transfer_type_listing/');?><?php echo $username;?>/<?php echo TRANSFER_ADJUST_OUT;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_adjust_out();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false},
					{"targets": [5], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#point_in-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_transfer_type_listing/');?><?php echo $username;?>/<?php echo TRANSFER_POINT_IN;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_point_in();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false},
					{"targets": [6], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#point_out-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_transfer_type_listing/');?><?php echo $username;?>/<?php echo TRANSFER_POINT_OUT;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_point_out();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false},
					{"targets": [5], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
			
			$('#promotion-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_promotion_listing/');?><?php echo $username;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_promotion();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			/*
			$('#bonus-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_bonus_listing/');?><?php echo $username;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							$('span#bonus_total_reward').html(json.responseJSON.total_data.total_reward);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
			*/
			$('#withdrawal-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_withdrawal_listing/');?><?php echo $username;?>/<?php echo STATUS_APPROVE;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_withdrawal();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#winloss-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"deferRender": true,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_winloss_listing/');?><?php echo $username;?>/<?php echo STATUS_COMPLETE;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_winloss();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#withdrawal_pending-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_withdrawal_listing/');?><?php echo $username;?>/<?php echo STATUS_PENDING;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_withdrawal_pending();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});

			$('#winloss_pending-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"deferRender": true,
				"pageLength" : 1,
				"lengthMenu": [[1, 10, 25, 50, 100], [1, 10, 25, 50, 100]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/player_daily_winloss_listing/');?><?php echo $username;?>/<?php echo STATUS_PENDING;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							load_total_winloss_pending();
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
				"language": {
					"processing": "<?php echo $this->lang->line('js_processing');?>",
					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",
					"info": "<?php echo $this->lang->line('js_info');?>",
					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",
					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",
					"search": "<?php echo $this->lang->line('js_search');?>",
					"paginate": {
						"first": "<?php echo $this->lang->line('js_paginate_first');?>",
						"last": "<?php echo $this->lang->line('js_paginate_last');?>",
						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",
						"next": "<?php echo $this->lang->line('js_paginate_next');?>"
					}	
				}
			});
		});
	
		function set_total_value(){
			var total = $('#uc19_<?php echo $player_id;?>').html();
			$('#total_amount').val(parseFloat(parseFloat(total.replace(',', ''))).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
		}

		function load_pending_withdrawal(username){
			setTimeout(function(){
				var total = $('#total_amount').val();
				$.ajax({url: '<?php echo base_url('player/player_pending_withdrawal/');?>'+username,
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						$('meta[name=csrf_token]').attr('content', json.csrfHash);
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							$('#uc19_<?php echo $player_id;?>').html(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.total_data.pending_withdrawal)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('#total_amount').val(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.total_data.pending_withdrawal)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							if(json.total_data.pending_withdrawal>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#uc17_<?php echo $player_id;?>').removeClass().addClass(deposit_class);
							$('span#uc17_<?php echo $player_id;?>').html(parseFloat(json.total_data.pending_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						}
					},
					error: function (request,error) {
					}
				});
			},1000);
		}

		function load_game_wallet(player_id){
			setTimeout(function(){
				var total = $('#total_amount').val();
				$.ajax({url: '<?php echo base_url('player/wallet_game_balance/');?>'+player_id,
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						$('meta[name=csrf_token]').attr('content', json.csrfHash);
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							$('#uc19_<?php echo $player_id;?>').html(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.balance)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('#total_amount').val(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.balance)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							if(json.balance>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#uc16_<?php echo $player_id;?>').removeClass().addClass(deposit_class);
							$('span#uc16_<?php echo $player_id;?>').html(parseFloat(json.balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						}
					},
					error: function (request,error) {
					}
				});
			},5000);
		}

		function load_pending_bet(player_id){
			setTimeout(function(){
				var total = $('#total_amount').val();
				$.ajax({url: '<?php echo base_url('player/player_pending_bet/');?>'+player_id,
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						$('meta[name=csrf_token]').attr('content', json.csrfHash);
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							$('#uc19_<?php echo $player_id;?>').html(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.total_data.pending_bet_amount)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('#total_amount').val(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.total_data.pending_bet_amount)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							if(json.total_data.pending_bet_amount>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#uc18_<?php echo $player_id;?>').removeClass().addClass(deposit_class);
							$('span#uc18_<?php echo $player_id;?>').html(parseFloat(json.total_data.pending_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						}
					},
					error: function (request,error) {
					}
				});
			},3000);
		}

		function load_total_offline_deposit(){
			var deposit_search = $('#deposit_offline_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_deposit_type_total/');?><?php echo $username;?>/<?php echo DEPOSIT_OFFLINE_BANKING;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_deposit_apply>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_apply_offline').removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_amount_offline').removeClass().addClass(deposit_class);

							$('span#total_deposit_apply_offline').html(parseFloat(json.total_data.total_deposit_apply).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_deposit_amount_offline').html(parseFloat(json.total_data.total_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));

							$('#deposit_offline_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_online_deposit(){
			var deposit_search = $('#deposit_online_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_deposit_type_total/');?><?php echo $username;?>/<?php echo DEPOSIT_ONLINE_BANKING;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_deposit_apply>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_apply_online').removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_amount_online').removeClass().addClass(deposit_class);

							$('span#total_deposit_apply_online').html(parseFloat(json.total_data.total_deposit_apply).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_deposit_amount_online').html(parseFloat(json.total_data.total_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));

							$('#deposit_online_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_credit_deposit(){
			var deposit_search = $('#deposit_credit_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_deposit_type_total/');?><?php echo $username;?>/<?php echo DEPOSIT_CREDIT_CARD;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_deposit_apply>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_apply_credit').removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_amount_credit').removeClass().addClass(deposit_class);

							$('span#total_deposit_apply_credit').html(parseFloat(json.total_data.total_deposit_apply).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_deposit_amount_credit').html(parseFloat(json.total_data.total_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));

							$('#deposit_credit_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_hypermart_deposit(){
			var deposit_search = $('#deposit_hypermart_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_deposit_type_total/');?><?php echo $username;?>/<?php echo DEPOSIT_HYPERMART;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_deposit_apply>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_apply_hypermart').removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_deposit_amount_hypermart').removeClass().addClass(deposit_class);

							$('span#total_deposit_apply_hypermart').html(parseFloat(json.total_data.total_deposit_apply).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_deposit_amount_hypermart').html(parseFloat(json.total_data.total_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));

							$('#deposit_hypermart_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_adjust_in(){
			var deposit_search = $('#adjust_in_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_transfer_type_total/');?><?php echo $username;?>/<?php echo TRANSFER_ADJUST_IN;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_points_withdrawn>0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}
							$('span#total_points_withdrawn_adjust_in').removeClass().addClass(deposit_class);
							if(json.total_data.total_points_deposited>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_points_deposited_adjust_in').removeClass().addClass(deposit_class);

							$('span#total_points_withdrawn_adjust_in').html(parseFloat(json.total_data.total_points_withdrawn).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_points_deposited_adjust_in').html(parseFloat(json.total_data.total_points_deposited).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('#adjust_in_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_adjust_out(){
			var deposit_search = $('#adjust_out_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_transfer_type_total/');?><?php echo $username;?>/<?php echo TRANSFER_ADJUST_OUT;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_points_withdrawn>0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}
							$('span#total_points_withdrawn_adjust_out').removeClass().addClass(deposit_class);
							if(json.total_data.total_points_deposited>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_points_deposited_adjust_out').removeClass().addClass(deposit_class);

							$('span#total_points_withdrawn_adjust_out').html(parseFloat(json.total_data.total_points_withdrawn).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_points_deposited_adjust_out').html(parseFloat(json.total_data.total_points_deposited).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('#adjust_out_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_point_in(){
			var deposit_search = $('#point_in_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_transfer_type_total/');?><?php echo $username;?>/<?php echo TRANSFER_POINT_IN;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_points_withdrawn>0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}
							$('span#total_points_withdrawn_point_in').removeClass().addClass(deposit_class);
							if(json.total_data.total_points_deposited>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_points_deposited_point_in').removeClass().addClass(deposit_class);

							$('span#total_points_withdrawn_point_in').html(parseFloat(json.total_data.total_points_withdrawn).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_points_deposited_point_in').html(parseFloat(json.total_data.total_points_deposited).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('#point_in_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_point_out(){
			var deposit_search = $('#point_out_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_transfer_type_total/');?><?php echo $username;?>/<?php echo TRANSFER_POINT_OUT;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_points_withdrawn>0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}
							$('span#total_points_withdrawn_point_out').removeClass().addClass(deposit_class);
							if(json.total_data.total_points_deposited>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_points_deposited_point_out').removeClass().addClass(deposit_class);

							$('span#total_points_withdrawn_point_out').html(parseFloat(json.total_data.total_points_withdrawn).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_points_deposited_point_out').html(parseFloat(json.total_data.total_points_deposited).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('#point_out_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_promotion(){
			var deposit_search = $('#promotion_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_promotion_total/');?><?php echo $username;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_reward>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_reward_promotion').removeClass().addClass(deposit_class);
							$('span#total_reward_promotion').html(parseFloat(json.total_data.total_reward).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('#promotion_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_withdrawal(){
			var deposit_search = $('#withdrawal_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_withdrawal_total/');?><?php echo $username;?>/<?php echo STATUS_APPROVE;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_withdrawal>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_withdrawal').removeClass().addClass(deposit_class);
							if(json.total_data.total_withdrawal_fee_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_withdrawal_fee_amount').removeClass().addClass(deposit_class);
							$('span#total_withdrawal').html(parseFloat(json.total_data.total_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_withdrawal_fee_amount').html(parseFloat(json.total_data.total_withdrawal_fee_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('#withdrawal_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_winloss(){
			var deposit_search = $('#bet_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_winloss_total/');?><?php echo $username;?>/<?php echo STATUS_APPROVE;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_bet_amount>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_bet_amount').removeClass().addClass(deposit_class);
							if(json.total_data.total_rolling_amount>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_rolling_amount').removeClass().addClass(deposit_class);
							if(json.total_data.total_jackpot_win>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_jackpot_win').removeClass().addClass(deposit_class);
							if(json.total_data.total_win_loss>0){var deposit_class = "text-primary";}else{if(json.total_data.total_win_loss<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_win_loss').removeClass().addClass(deposit_class);


							$('span#total_bet_amount').html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_win_loss').html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_rolling_amount').html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_jackpot_win').html(parseFloat(json.total_data.total_jackpot_win).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('#bet_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_withdrawal_pending(){
			var deposit_search = $('#pending_withdrawal_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_withdrawal_total/');?><?php echo $username;?>/<?php echo STATUS_PENDING;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_withdrawal>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_withdrawal_pending').removeClass().addClass(deposit_class);
							if(json.total_data.total_withdrawal_fee_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#total_withdrawal_fee_amount_pending').removeClass().addClass(deposit_class);
							$('span#total_withdrawal_pending').html(parseFloat(json.total_data.total_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#total_withdrawal_fee_amount_pending').html(parseFloat(json.total_data.total_withdrawal_fee_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('#pending_withdrawal_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}

		function load_total_winloss_pending(){
			var deposit_search = $('#pending_bet_total_div').val();
			if(deposit_search != "1"){
				$.ajax({url: '<?php echo base_url('report/player_daily_winloss_total/');?><?php echo $username;?>/<?php echo STATUS_PENDING;?>',
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						if(json.status == '<?php echo EXIT_SUCCESS;?>'){
							if(json.total_data.total_bet_amount>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_bet_amount_pending').removeClass().addClass(deposit_class);
							if(json.total_data.total_rolling_amount>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_rolling_amount_pending').removeClass().addClass(deposit_class);
							if(json.total_data.total_jackpot_win>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
							$('span#total_jackpot_win_pending').removeClass().addClass(deposit_class);
							if(json.total_data.total_win_loss>0){var deposit_class = "text-primary";}else{if(json.total_data.total_win_loss<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_win_loss_pending').removeClass().addClass(deposit_class);


							$('span#total_bet_amount_pending').html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_win_loss_pending').html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_rolling_amount_pending').html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_jackpot_win_pending').html(parseFloat(json.total_data.total_jackpot_win).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('#pending_bet_total_div').val(1);
						}
					},
					error: function (request,error) {
					}
				});
			}
		}
	</script>
</body>
</html>