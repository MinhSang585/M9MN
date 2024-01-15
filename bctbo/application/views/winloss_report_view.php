<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
	<?php $this->load->view('parts/head_meta');?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('parts/navbar_page');?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php $this->load->view('parts/sidebar_page');?>
		<!-- /.sidebar -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<?php $this->load->view('parts/header_page');?>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div id="card-panel" class="col-12">
						<div id="card-table-1" class="card">
							<div class="card-header">
								<form action="<?php echo site_url('report/winloss_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 00:00:00');?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>										
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 23:59:59');?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>								
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>										
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-12">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_quick_search');?></label>
										</div>
										<div class="col-md-12 col-12">
											<div class="row mb-2">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_month_from;?>','<?php echo $date_last_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_month');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_week_from;?>','<?php echo $date_last_week_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from;?>','<?php echo $date_yesterday_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_today_from;?>','<?php echo $date_today_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_today');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_week_from;?>','<?php echo $date_current_week_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_month_from;?>','<?php echo $date_current_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_month');?></button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData(0)" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/winloss_export_excel', 'class="export" id="export_form_0"');?>
							<?php echo form_close(); ?>
							<!-- /.card-header -->
							<?php endif;?>
							<!-- /.card-header -->
							<div class="card-body" style="display:none;">

								<table id="report-table-1" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_level');?></th>
											<th><?php echo $this->lang->line('label_game_type');?></th>
											<th><?php echo $this->lang->line('label_username');?></th>
											<th><?php echo $this->lang->line('label_agent');?></th>
											<th><?php echo $this->lang->line('label_deposit');?></th>
											<th><?php echo $this->lang->line('label_withdrawal');?></th>
											<th><?php echo $this->lang->line('label_number_of_transaction');?></th>
											<th><?php echo $this->lang->line('label_bet_amount');?></th>
											<th><?php echo $this->lang->line('label_win_loss');?></th>
											<th><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th><?php echo $this->lang->line('label_rolling_commission');?></th>
											<th><?php echo $this->lang->line('label_promotion');?></th>
											<th><?php echo $this->lang->line('label_bonus');?></th>
											<th><?php echo $this->lang->line('label_profit');?></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>	
				</div>	
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Main Footer -->
		<?php $this->load->view('parts/footer_page');?>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		function fastSetDateSearch(from,to){
			$('#from_date').val(from);
			$('#to_date').val(to);
			$('#report-form').submit();
		}
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#report-form');
			
			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
								csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
								from_date:  $('#from_date').val(),
								to_date:  $('#to_date').val(),
								game_provider_code:  $('#game_provider_code').val(),
								username : $('#username').val()
						},
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
							
							$('meta[name=csrf_token]').attr('content', json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								var obj = $('.card-body');
								
								if (obj.is(':visible')) {
									for(var i=2;i<=table_num;i++) {
										$('#card-table-' + i).remove();
										$('#p-card-table-1').remove();
									}
								
									table_num = 1;
									$('#report-table-1').DataTable().ajax.reload();
								}
								else {
									obj.show();
									loadTable();
								}
							}
							else {
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							}
						},
						error: function (request,error) {
						}
					});  
				}
				
				return false;
			});
		});
		
		function loadTable(){
			$('#report-table-1').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"ordering": false,
				"pageLength" : 10,
				"ajax": {
					"url": "<?php echo site_url('report/winloss_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
						}
					},
				},
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
		}
		
		var is_allowed_2 = true;
		var table_num = 1;
		
		function getDownline(username, num) {
			if(is_allowed_2 == true) {
				is_allowed_2 = false;

				var next_num = (num + 1);
				for(var i=next_num;i<=table_num;i++) {
					$('#card-table-' + i).remove();
				}
				
				$('#p-card-table-1').remove();
			
				table_num = next_num;
				layer.load(1);
				load_table_downline(table_num, username);
				$('html, body').animate({scrollTop:$(document).height()}, 800);
			}
		}

		function load_table_downline(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_downline/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					
				},
				complete: function() {
				},
				success: function (data) {
					load_table_player(table_num, username);
					if(data != '') {
						$('#card-panel').append(data);
						load_table_downline_total(table_num, username);
					}	
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_downline_total(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_downline_total/');?>' + table_num + '/' + username,
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
						if(json.total_data.total_downline > 0){
							if(json.total_data.total_deposit>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#downline_total_deposit_'+table_num).removeClass().addClass(deposit_class);
							if(json.total_data.total_withdrawal>0){var withdrawal_class = "text-danger";}else{var withdrawal_class = "text-dark";}
							$('span#downline_total_withdrawal_'+table_num).removeClass().addClass(withdrawal_class);
							if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
							$('span#downline_total_bet_'+table_num).removeClass().addClass(bet_class);
							if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
							$('span#downline_total_bet_amount_'+table_num).removeClass().addClass(bet_amount_class);
							if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_'+table_num).removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_rolling_commission>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_rolling_commission_'+table_num).removeClass().addClass(rolling_comission_class);
							if(json.total_data.total_promotion>0){var promotion_class = "text-primary";}else{var promotion_class = "text-dark";}
							$('span#downline_total_promotion_'+table_num).removeClass().addClass(promotion_class);
							if(json.total_data.total_bonus>0){var bonus_class = "text-primary";}else{var bonus_class = "text-dark";}
							$('span#downline_total_bonus_'+table_num).removeClass().addClass(bonus_class);
							if(json.total_data.total_profit>=0){if(json.total_data.total_profit==0){var profit_class = "text-dark";}else{var profit_class = "text-danger";}}else{var profit_class = "text-primary";}
							$('span#downline_total_profit_'+table_num).removeClass().addClass(profit_class);

							$('span#downline_total_deposit_'+table_num).html(json.total_data.total_deposit.toFixed(2));
							$('span#downline_total_withdrawal_'+table_num).html(json.total_data.total_withdrawal.toFixed(2));
							$('span#downline_total_bet_'+table_num).html(json.total_data.total_bet.toFixed(2));
							$('span#downline_total_bet_amount_'+table_num).html(json.total_data.total_bet_amount.toFixed(2));
							$('span#downline_total_win_loss_'+table_num).html(json.total_data.total_win_loss.toFixed(2));
							$('span#downline_total_rolling_amount_'+table_num).html(json.total_data.total_rolling_amount.toFixed(2));
							$('span#downline_total_rolling_commission_'+table_num).html(json.total_data.total_rolling_commission.toFixed(2));
							$('span#downline_total_promotion_'+table_num).html(json.total_data.total_promotion.toFixed(2));
							$('span#downline_total_bonus_'+table_num).html(json.total_data.total_bonus.toFixed(2));
							$('span#downline_total_profit_'+table_num).html(json.total_data.total_profit.toFixed(2));
						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_player(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_downline_player/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
				},
				complete: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				success: function (data) {
					if(data != '') {
						$('#card-panel').append(data);
						load_table_player_total(username); 
					}	
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_player_total(username){
			$.ajax({url: '<?php echo base_url('report/winloss_downline_player_total/');?>' + username,
				type: 'get',                  
				async: 'true',
				dataType: "json",
				beforeSend: function() {
				},
				complete: function() {
					
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					$('meta[name=csrf_token]').attr('content', json.csrfHash);
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						if(json.total_data.total_player > 0){
							if(json.total_data.total_deposit>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_deposit').removeClass().addClass(deposit_class);
							if(json.total_data.total_withdrawal>0){var withdrawal_class = "text-danger";}else{var withdrawal_class = "text-dark";}
							$('span#player_total_withdrawal').removeClass().addClass(withdrawal_class);
							if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
							$('span#player_total_bet').removeClass().addClass(bet_class);
							if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
							$('span#player_total_bet_amount').removeClass().addClass(bet_amount_class);
							if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#player_total_win_loss').removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#player_total_rolling_amount').removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_promotion>0){var promotion_class = "text-primary";}else{var promotion_class = "text-dark";}
							$('span#player_total_promotion').removeClass().addClass(promotion_class);
							if(json.total_data.total_bonus>0){var bonus_class = "text-primary";}else{var bonus_class = "text-dark";}
							$('span#player_total_bonus').removeClass().addClass(bonus_class);

							$('span#player_total_deposit').html(json.total_data.total_deposit.toFixed(2));
							$('span#player_total_withdrawal').html(json.total_data.total_withdrawal.toFixed(2));
							$('span#player_total_bet').html(json.total_data.total_bet.toFixed(2));
							$('span#player_total_bet_amount').html(json.total_data.total_bet_amount.toFixed(2));
							$('span#player_total_win_loss').html(json.total_data.total_win_loss.toFixed(2));
							$('span#player_total_rolling_amount').html(json.total_data.total_rolling_amount.toFixed(2));
							$('span#player_total_promotion').html(json.total_data.total_promotion.toFixed(2));
							$('span#player_total_bonus').html(json.total_data.total_bonus.toFixed(2));
						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function getDownlineDeposit(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_deposit');?>',
				content: '<?php echo base_url('report/winloss_downline_deposit/');?>' + table_num + '/' + username
			});
		}

		function getDownlineWithdrawal(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_withdrawal');?>',
				content: '<?php echo base_url('report/winloss_downline_withdrawal/');?>' + table_num + '/' + username
			});
		}
		function getDownlineBet(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_bet');?>',
				content: '<?php echo base_url('report/winloss_downline_bet/');?>' + table_num + '/' + username
			});
		}
		function getDownlinePromotion(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_promotion');?>',
				content: '<?php echo base_url('report/winloss_downline_promotion/');?>' + table_num + '/' + username
			});
		}
		function getDownlineBonus(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_bonus');?>',
				content: '<?php echo base_url('report/winloss_downline_bonus/');?>' + table_num + '/' + username
			});
		}
		function getDownlineDepositPlayer(upline,username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_deposit');?>',
				content: '<?php echo base_url('report/winloss_downline_deposit_player/');?>' + upline + '/' + username
			});
		}

		function getDownlineWithdrawalPlayer(upline,username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_withdrawal');?>',
				content: '<?php echo base_url('report/winloss_downline_withdrawal_player/');?>' + upline + '/' + username
			});
		}
		function getDownlineBetPlayer(upline,username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_bet');?>',
				content: '<?php echo base_url('report/winloss_downline_bet_player/');?>' + upline + '/' + username
			});
		}
		function getDownlinePromotionPlayer(upline,username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_promotion');?>',
				content: '<?php echo base_url('report/winloss_downline_promotion_player/');?>' + upline + '/' + username
			});
		}
		function getDownlineBonusPlayer(upline,username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_bonus');?>',
				content: '<?php echo base_url('report/winloss_downline_bonus_player/');?>' + upline + '/' + username
			});
		}

		function exportData(index){
			$.ajax({url: '<?php echo base_url("export/winloss_export_excel_check");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var message = '';
					var msg_icon = 2;
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						message = json.msg.general_error;
						msg_icon = 1;
						var form_excel = $('#export_form_'+index).submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}

		function exportPlayerData(index){
			$.ajax({url: '<?php echo base_url("export/winloss_player_export_excel_check");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var message = '';
					var msg_icon = 2;
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						message = json.msg.general_error;
						msg_icon = 1;
						var form_excel = $('#export_form_player').submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}
	</script>	
</body>
</html>
