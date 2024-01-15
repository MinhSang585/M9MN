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
								<form action="<?php echo site_url('report/winloss_sum_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d');?>" data-target="#from_date_click"/>
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
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d');?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>								
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_exclude_zero');?></label>
												<div class="col-8">
													<input type="checkbox" id="excludezero" name="excludezero" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
										<label class="col-1 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_more_filter');?></label>
										<div class="form-group clearfix col-11">
											<div class="custom-control custom-checkbox d-inline pr-2">
												<input class="custom-control-input" type="checkbox" id="exclude_provider_splt" name="exclude_provider[]" value="SPLT">
												<label class="custom-control-label font-weight-normal" for="exclude_provider_splt"><?php echo $this->lang->line('checkbox_label_exclude_super_lottery');?></label>
											</div>
											<div class="custom-control custom-checkbox d-inline pr-2">
												<input class="custom-control-input" type="checkbox" id="exclude_gametype_lt" name="exclude_gametype[]" value="<?php echo GAME_LOTTERY;?>">
												<label class="custom-control-label font-weight-normal" for="exclude_gametype_lt"><?php echo $this->lang->line('checkbox_label_exclude_all_lottery');?></label>
											</div>
											<div class="custom-control custom-checkbox d-inline pr-2">
												<input class="custom-control-input" type="checkbox" id="exclude_gametype_bg" name="exclude_gametype[]" value="<?php echo GAME_BOARD_GAME;?>">
												<label class="custom-control-label font-weight-normal" for="exclude_gametype_bg"><?php echo $this->lang->line('checkbox_label_exclude_all_boardgame');?></label>
											</div>
											<div class="custom-control custom-checkbox d-inline pr-2">
												<input class="custom-control-input" type="checkbox" id="exclude_gametype_fh" name="exclude_gametype[]" value="<?php echo GAME_FISHING;?>">
												<label class="custom-control-label font-weight-normal" for="exclude_gametype_fh"><?php echo $this->lang->line('checkbox_label_exclude_all_fishing');?></label>
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
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_month_from_date;?>','<?php echo $date_last_month_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_month');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_week_from_date;?>','<?php echo $date_last_week_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from_date;?>','<?php echo $date_yesterday_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_today_from_date;?>','<?php echo $date_today_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_today');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_week_from_date;?>','<?php echo $date_current_week_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_month_from_date;?>','<?php echo $date_current_month_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_month');?></button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/winloss_report_export', 'class="export" id="export_form_1"');?>
							<?php echo form_close(); ?>
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="report-table-1" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="40"><?php echo $this->lang->line('label_level');?></th>
											<th width="60"><?php echo $this->lang->line('label_game_type');?></th>
											<th width="60"><?php echo $this->lang->line('label_username');?></th>
											<th width="60"><?php echo $this->lang->line('label_agent');?></th>
											<th width="100"><?php echo $this->lang->line('label_deposit');?></th>
											<th width="100"><?php echo $this->lang->line('label_deposit_offline');?></th>
											<th width="100"><?php echo $this->lang->line('label_deposit_online');?></th>
											<th width="100"><?php echo $this->lang->line('label_deposit_point');?></th>
											<th width="100"><?php echo $this->lang->line('label_withdrawal');?></th>
											<th width="100"><?php echo $this->lang->line('label_withdrawal_offline');?></th>
											<th width="100"><?php echo $this->lang->line('label_withdrawal_online');?></th>
											<th width="100"><?php echo $this->lang->line('label_withdrawal_point');?></th>
											<th width="100"><?php echo $this->lang->line('label_adjust');?></th>
											<th width="100"><?php echo $this->lang->line('label_adjust_in');?></th>
											<th width="100"><?php echo $this->lang->line('label_adjust_out');?></th>
											<th width="100"><?php echo $this->lang->line('label_number_of_transaction');?></th>
											<th width="100"><?php echo $this->lang->line('label_bet_amount');?></th>
											<th width="100"><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th width="100"><?php echo $this->lang->line('label_total_win_loss');?></th>
											<th width="100"><?php echo $this->lang->line('label_total_promotion_amount');?></th>
											<th width="100"><?php echo $this->lang->line('label_bonus');?></th>
											<th width="100"><?php echo $this->lang->line('label_agent_possess');?></th>
											<th width="100"><?php echo $this->lang->line('label_possess_win_loss');?></th>
											<th width="100"><?php echo $this->lang->line('label_possess_promotion');?></th>
											<th width="100"><?php echo $this->lang->line('label_possess_bonus');?></th>
											<th width="100"><?php echo $this->lang->line('label_rolling_commission');?></th>
											<th width="100"><?php echo $this->lang->line('label_profit');?></th>
											<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
											<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
											<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
											<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
											<th width="150" class="bg-warning"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
											<th width="150" class="bg-warning"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
											<th width="150" class="bg-warning"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
											<th width="150" class="bg-warning"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
											<th width="150" class="bg-success"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
											<th width="150" class="bg-success"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
											<th width="150" class="bg-success"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
											<th width="150" class="bg-success"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>																						<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>											<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>											<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>											<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>
											<th width="150" class="bg-primary"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
											<th width="150" class="bg-primary"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
											<th width="150" class="bg-primary"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
											<th width="150" class="bg-primary"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
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
				format: 'YYYY-MM-DD',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD',
                icons: {
                    time: "fa fa-clock"
                }
            });

            $("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					var excludeProviderCheckboxes = new Array();
			        $('input[name="exclude_provider[]"]:checked').each(function() {
			           excludeProviderCheckboxes.push($(this).val());
			        });

			        var excludeGametypeCheckboxes = new Array();
			        $('input[name="exclude_gametype[]"]:checked').each(function() {
			           excludeGametypeCheckboxes.push($(this).val());
			        });
			        
					$.ajax({url: form.attr('action'),
						data: { 
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
							from_date:  $('#from_date').val(),
							to_date:  $('#to_date').val(),
							username : $('#username').val(),
							excludezero : $('#excludezero').prop("checked"),
							excludeProviderCheckboxes : excludeProviderCheckboxes,
							excludeGametypeCheckboxes : excludeGametypeCheckboxes,
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
					"url": "<?php echo site_url('report/winloss_sum_listing');?>",
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
				"columnDefs": [
					{"targets": [4], "visible": false},
					{"targets": [8], "visible": false},
					{"targets": [10], "visible": false},
					{"targets": [12], "visible": false},
					{"targets": [15], "visible": false},
					{"targets": [20], "visible": false},
					{"targets": [24], "visible": false},
					{"targets": [25], "visible": false},
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
				},
				<?php if(!$this->agent->is_mobile()) { ?>
				"fixedColumns": {
		            leftColumns: 3 //Your column number here
		        },
		        <?php } ?>
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
			$.ajax({url: '<?php echo base_url('report/winloss_sum_downline/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				complete: function() {
				},
				success: function (data) {
					//load_table_player(table_num, username);
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
			$.ajax({url: '<?php echo base_url('report/winloss_sum_downline_total/');?>' + table_num + '/' + username,
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
							if(json.total_data.total_deposit_offline>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#downline_total_deposit_offline_'+table_num).removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_online>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#downline_total_deposit_online_'+table_num).removeClass().addClass(deposit_class);
							if(json.total_data.total_deposit_point>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#downline_total_deposit_point_'+table_num).removeClass().addClass(deposit_class);
							


							if(json.total_data.total_withdrawal>0){var withdrawal_class = "text-primary";}else{var withdrawal_class = "text-dark";}
							$('span#downline_total_withdrawal_'+table_num).removeClass().addClass(withdrawal_class);
							if(json.total_data.total_withdrawal_offline>0){var withdrawal_class = "text-primary";}else{var withdrawal_class = "text-dark";}
							$('span#downline_total_withdrawal_offline_'+table_num).removeClass().addClass(withdrawal_class);
							if(json.total_data.total_withdrawal_online>0){var withdrawal_class = "text-primary";}else{var withdrawal_class = "text-dark";}
							$('span#downline_total_withdrawal_online_'+table_num).removeClass().addClass(withdrawal_class);
							if(json.total_data.total_withdrawal_point>0){var withdrawal_class = "text-primary";}else{var withdrawal_class = "text-dark";}
							$('span#downline_total_withdrawal_point_'+table_num).removeClass().addClass(withdrawal_class);

							if(json.total_data.total_adjust>=0){if(json.total_data.total_adjust==0){var adjust_class = "text-dark";}else{var adjust_class = "text-primary";}}else{var adjust_class = "text-danger";}
							$('span#downline_total_adjust_'+table_num).removeClass().addClass(adjust_class);
							if(json.total_data.total_adjust_in>0){var adjust_class = "text-primary";}else{var adjust_class = "text-dark";}
							$('span#downline_total_adjust_in_'+table_num).removeClass().addClass(adjust_class);
							if(json.total_data.total_adjust_out>0){var adjust_class = "text-primary";}else{var adjust_class = "text-dark";}
							$('span#downline_total_adjust_out_'+table_num).removeClass().addClass(adjust_class);

							if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
							$('span#downline_total_bet_'+table_num).removeClass().addClass(bet_class);
							if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
							$('span#downline_total_bet_amount_'+table_num).removeClass().addClass(bet_amount_class);
							if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_'+table_num).removeClass().addClass(rolling_amount_class);
							
							if(json.total_data.total_promotion<0){var promotion_class = "text-danger";}else{var promotion_class = "text-dark";}
							$('span#downline_total_promotion_'+table_num).removeClass().addClass(promotion_class);
							if(json.total_data.total_bonus<0){var bonus_class = "text-danger";}else{var bonus_class = "text-dark";}
							$('span#downline_total_bonus_'+table_num).removeClass().addClass(bonus_class);
							
							if(json.total_data.total_possess_win_loss>=0){if(json.total_data.total_possess_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_possess_win_loss_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_possess_promotion>0){var promotion_class = "text-primary";}else{var promotion_class = "text-danger";}
							$('span#downline_total_possess_promotion_'+table_num).removeClass().addClass(promotion_class);
							if(json.total_data.total_possess_bonus>0){var bonus_class = "text-primary";}else{var bonus_class = "text-dark";}
							$('span#downline_total_possess_bonus_'+table_num).removeClass().addClass(bonus_class);
							if(json.total_data.total_rolling_commission>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_rolling_commission_'+table_num).removeClass().addClass(rolling_comission_class);
							if(json.total_data.total_profit>=0){if(json.total_data.total_profit==0){var profit_class = "text-dark";}else{var profit_class = "text-primary";}}else{var profit_class = "text-danger";}
							$('span#downline_total_profit_'+table_num).removeClass().addClass(profit_class);


							if(json.total_data.total_rolling_amount_live_casino>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_live_casino_'+table_num).removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_win_loss_live_casino>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_live_casino_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_comission_live_casino>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_comission_live_casino_'+table_num).removeClass().addClass(rolling_comission_class);

							if(json.total_data.total_rolling_amount_slot>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_slot_'+table_num).removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_win_loss_slot>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_slot_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_comission_slot>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_comission_slot_'+table_num).removeClass().addClass(rolling_comission_class);

							if(json.total_data.total_rolling_amount_sportbook>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_sportbook_'+table_num).removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_win_loss_sportbook>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_sportbook_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_comission_sportbook>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_comission_sportbook_'+table_num).removeClass().addClass(rolling_comission_class);
							
							if(json.total_data.total_rolling_amount_cock_fighting>0){let rolling_amount_class = "text-primary";}else{let rolling_amount_class = "text-dark";}							$('span#downline_total_rolling_amount_cock_fighting_'+table_num).removeClass().addClass(rolling_amount_class);							if(json.total_data.total_win_loss_cock_fighting>=0){if(json.total_data.total_win_loss==0){let win_loss_class = "text-dark";}else{let win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}							$('span#downline_total_win_loss_cock_fighting_'+table_num).removeClass().addClass(win_loss_class);							if(json.total_data.total_rolling_comission_cock_fighting>0){let rolling_comission_class = "text-primary";}else{let rolling_comission_class = "text-dark";}							$('span#downline_total_comission_cock_fighting_'+table_num).removeClass().addClass(rolling_comission_class);														if(json.total_data.total_rolling_amount_other>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
							$('span#downline_total_rolling_amount_other_'+table_num).removeClass().addClass(rolling_amount_class);
							if(json.total_data.total_win_loss_other>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#downline_total_win_loss_other_'+table_num).removeClass().addClass(win_loss_class);
							if(json.total_data.total_rolling_comission_other>0){var rolling_comission_class = "text-primary";}else{var rolling_comission_class = "text-dark";}
							$('span#downline_total_comission_other_'+table_num).removeClass().addClass(rolling_comission_class);
							



							$('span#downline_total_deposit_'+table_num).html(parseFloat(json.total_data.total_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_deposit_offline_'+table_num).html(parseFloat(json.total_data.total_deposit_offline).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_deposit_online_'+table_num).html(parseFloat(json.total_data.total_deposit_online).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_deposit_point_'+table_num).html(parseFloat(json.total_data.total_deposit_point).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_withdrawal_'+table_num).html(parseFloat(json.total_data.total_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_withdrawal_offline_'+table_num).html(parseFloat(json.total_data.total_withdrawal_offline).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_withdrawal_online_'+table_num).html(parseFloat(json.total_data.total_withdrawal_online).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_withdrawal_point_'+table_num).html(parseFloat(json.total_data.total_withdrawal_point).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));


							$('span#downline_total_adjust_'+table_num).html(parseFloat(json.total_data.total_adjust).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_adjust_in_'+table_num).html(parseFloat(json.total_data.total_adjust_in).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_adjust_out_'+table_num).html(parseFloat(json.total_data.total_adjust_out).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_bet_'+table_num).html(parseFloat(json.total_data.total_bet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
							$('span#downline_total_bet_amount_'+table_num).html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_win_loss_'+table_num).html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_rolling_amount_'+table_num).html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_promotion_'+table_num).html(parseFloat(json.total_data.total_promotion).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_bonus_'+table_num).html(parseFloat(json.total_data.total_bonus).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_possess_win_loss_'+table_num).html(parseFloat(json.total_data.total_possess_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_possess_promotion_'+table_num).html(parseFloat(json.total_data.total_possess_promotion).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_possess_bonus_'+table_num).html(parseFloat(json.total_data.total_possess_bonus).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_rolling_commission_'+table_num).html(parseFloat(json.total_data.total_rolling_commission).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_profit_'+table_num).html(parseFloat(json.total_data.total_profit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_rolling_amount_live_casino_'+table_num).html(parseFloat(json.total_data.total_rolling_amount_live_casino).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_win_loss_live_casino_'+table_num).html(parseFloat(json.total_data.total_win_loss_live_casino).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_comission_live_casino_'+table_num).html(parseFloat(json.total_data.total_rolling_comission_live_casino).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_rolling_amount_slot_'+table_num).html(parseFloat(json.total_data.total_rolling_amount_slot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_win_loss_slot_'+table_num).html(parseFloat(json.total_data.total_win_loss_slot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_comission_slot_'+table_num).html(parseFloat(json.total_data.total_rolling_comission_slot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_rolling_amount_sportbook_'+table_num).html(parseFloat(json.total_data.total_rolling_amount_sportbook).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));							$('span#downline_total_win_loss_sportbook_'+table_num).html(parseFloat(json.total_data.total_win_loss_sportbook).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));							$('span#downline_total_comission_sportbook_'+table_num).html(parseFloat(json.total_data.total_rolling_comission_sportbook).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_rolling_amount_cock_fighting_'+table_num).html(parseFloat(json.total_data.total_rolling_amount_cock_fighting).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_win_loss_cock_fighting_'+table_num).html(parseFloat(json.total_data.total_win_loss_cock_fighting).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_comission_cock_fighting_'+table_num).html(parseFloat(json.total_data.total_rolling_comission_cock_fighting).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

							$('span#downline_total_rolling_amount_other_'+table_num).html(parseFloat(json.total_data.total_rolling_amount_other).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_win_loss_other_'+table_num).html(parseFloat(json.total_data.total_win_loss_other).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#downline_total_comission_other_'+table_num).html(parseFloat(json.total_data.total_rolling_comission_other).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));


						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_player(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_downline_player/');?>' + table_num + '/' + username,
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
			$.ajax({url: '<?php echo base_url('report/winloss_sum_downline_player_total/');?>' + username,
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

							$('span#player_total_deposit').html(parseFloat(json.total_data.total_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_withdrawal').html(parseFloat(json.total_data.total_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_bet').html(parseFloat(json.total_data.total_bet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_bet_amount').html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_win_loss').html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_rolling_amount').html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_promotion').html(parseFloat(json.total_data.total_promotion).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_bonus').html(parseFloat(json.total_data.total_bonus).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function getDownlineDepositOffline(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_deposit_offline');?>',
				content: '<?php echo base_url('report/winloss_downline_deposit_offline/');?>' + username + '/' + table_num
			});
		}

		function getDownlineDepositOnline(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_deposit_online');?>',
				content: '<?php echo base_url('report/winloss_downline_deposit_online/');?>' + username + '/' + table_num
			});
		}

		function getDownlineWithdrawalOffline(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_withdrawal_offline');?>',
				content: '<?php echo base_url('report/winloss_downline_withdrawal_offline/');?>' + username + '/' + table_num
			});
		}

		function getDownlineAdjustIn(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_adjust_in');?>',
				content: '<?php echo base_url('report/winloss_downline_adjust/');?>' + username + '/' + <?php echo TRANSFER_ADJUST_IN;?>
			});
		}

		function getDownlineAdjustOut(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_adjust_in');?>',
				content: '<?php echo base_url('report/winloss_downline_adjust/');?>' + username + '/' + <?php echo TRANSFER_ADJUST_OUT;?>
			});
		}

		function getDownlinePromotion(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_total_promotion_amount');?>',
				content: '<?php echo base_url('report/winloss_downline_promotion/');?>' + username + '/' + table_num
			});
		}

		function getDownlineBet(username,table_num){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('label_total_win_loss');?>',
				content: '<?php echo base_url('report/winloss_report_downline_bet_table/');?>' + username + '/' + table_num
			});
		}

		function exportData(num = 0,username = 0){
			$.ajax({url: '<?php echo base_url("export/winloss_report_export_check");?>',
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
						if(num == 0){
							$('#export_form_1').attr('action', '<?php echo base_url('export/winloss_report_export')?>');
						}else{
							$('#export_form_1').attr('action', '<?php echo base_url('export/winloss_report_export/')?>'+num+"/"+username);
						}
						var form_excel = $('#export_form_1').submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}

		function getGameType(username, num){
			if(is_allowed_2 == true) {
				is_allowed_2 = false;

				var next_num = (num + 1);
				var max_page = 10;
				var min_page = 1+1;
				for(var i=min_page;i<=max_page;i++) {
					$('#gametype-card-table-' + i).remove();
				}
				
				$('#gametype-card-table-1').remove();
			
				table_num = next_num;
				layer.load(1);
				load_table_gametype(table_num, username);
				$('html, body').animate({scrollTop:$(document).height()}, 800);
			}
		}

		function load_table_gametype(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				complete: function() {
				},
				success: function (data) {
					//load_table_player(table_num, username);
					if(data != '') {
						$('#card-panel').append(data);
						load_table_gametype_total(table_num, username);
					}	
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_gametype_total(table_num, username){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype_total/');?>' + table_num + '/' + username,
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
						table_num = 1;
						if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
						$('span#gametype_total_bet_'+table_num).removeClass().addClass(bet_class);
						if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
						$('span#gametype_total_bet_amount_'+table_num).removeClass().addClass(bet_amount_class);
						if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
						$('span#gametype_total_win_loss_'+table_num).removeClass().addClass(win_loss_class);
						if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
						$('span#gametype_total_rolling_amount_'+table_num).removeClass().addClass(rolling_amount_class);


						$('span#gametype_total_bet_'+table_num).html(parseFloat(json.total_data.total_bet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#gametype_total_bet_amount_'+table_num).html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_win_loss_'+table_num).html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_rolling_amount_'+table_num).html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
					}
				}
			});
		}

		function getGameTypeProvider(username, num, game_type){
			if(is_allowed_2 == true) {
				is_allowed_2 = false;

				var next_num = (num + 1);
				var max_page = 10;
				var min_page = 2+1;
				for(var i=min_page;i<=max_page;i++) {
					$('#gametype-card-table-' + i).remove();
				}
				
				$('#gametype-card-table-2').remove();
			
				table_num = next_num;
				layer.load(1);
				load_table_gametype_provider(table_num, username, game_type);
				$('html, body').animate({scrollTop:$(document).height()}, 800);
			}	
		}

		function load_table_gametype_provider(table_num, username, game_type){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype_provider/');?>' + table_num + '/' + username + '/' + game_type,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				complete: function() {
				},
				success: function (data) {
					//load_table_player(table_num, username);
					if(data != '') {
						$('#card-panel').append(data);
						load_table_gametype_provider_total(table_num, username, game_type);
					}	
				},
				error: function (request,error) {
				}
			});
		}

		function load_table_gametype_provider_total(table_num, username, game_type){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype_provider_total/');?>' + table_num + '/' + username + "/" + game_type,
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
						table_num = 2;
						if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
						$('span#gametype_total_bet_'+table_num).removeClass().addClass(bet_class);
						if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
						$('span#gametype_total_bet_amount_'+table_num).removeClass().addClass(bet_amount_class);
						if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
						$('span#gametype_total_win_loss_'+table_num).removeClass().addClass(win_loss_class);
						if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
						$('span#gametype_total_rolling_amount_'+table_num).removeClass().addClass(rolling_amount_class);


						$('span#gametype_total_bet_'+table_num).html(parseFloat(json.total_data.total_bet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#gametype_total_bet_amount_'+table_num).html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_win_loss_'+table_num).html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_rolling_amount_'+table_num).html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
					}
				}
			});
		}

		function getGameTypeProviderGameCode(username, num, game_type, provider_code){
			if(is_allowed_2 == true) {
				is_allowed_2 = false;

				var next_num = (num + 1);
				var max_page = 10;
				var min_page = 3+1;
				for(var i=min_page;i<=max_page;i++) {
					$('#gametype-card-table-' + i).remove();
				}
				
				$('#gametype-card-table-3').remove();
			
				table_num = next_num;
				layer.load(1);
				load_table_gametype_provider_gamecode(table_num, username, game_type, provider_code);
				$('html, body').animate({scrollTop:$(document).height()}, 800);
			}	
		}

		function load_table_gametype_provider_gamecode(table_num, username, game_type, provider_code){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype_provider_gamecode/');?>' + table_num + '/' + username + '/' + game_type + '/' + provider_code,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				complete: function() {
				},
				success: function (data) {
					//load_table_player(table_num, username);
					if(data != '') {
						$('#card-panel').append(data);
						load_table_gametype_provider_gamecode_total(table_num, username, game_type, provider_code);
					}	
				},
				error: function (request,error) {
				}
			});
		}

		function load_table_gametype_provider_gamecode_total(table_num, username, game_type, provider_code){
			$.ajax({url: '<?php echo base_url('report/winloss_sum_gametype_provider_gamecode_total/');?>' + table_num + '/' + username + "/" + game_type + '/' + provider_code,
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
						table_num = 3;
						if(json.total_data.total_bet>0){var bet_class = "text-primary";}else{var bet_class = "text-dark";}
						$('span#gametype_total_bet_'+table_num).removeClass().addClass(bet_class);
						if(json.total_data.total_bet_amount>0){var bet_amount_class = "text-primary";}else{var bet_amount_class = "text-dark";}
						$('span#gametype_total_bet_amount_'+table_num).removeClass().addClass(bet_amount_class);
						if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
						$('span#gametype_total_win_loss_'+table_num).removeClass().addClass(win_loss_class);
						if(json.total_data.total_rolling_amount>0){var rolling_amount_class = "text-primary";}else{var rolling_amount_class = "text-dark";}
						$('span#gametype_total_rolling_amount_'+table_num).removeClass().addClass(rolling_amount_class);


						$('span#gametype_total_bet_'+table_num).html(parseFloat(json.total_data.total_bet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#gametype_total_bet_amount_'+table_num).html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_win_loss_'+table_num).html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#gametype_total_rolling_amount_'+table_num).html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
					}
				}
			});
		}
	</script>	
</body>
</html>