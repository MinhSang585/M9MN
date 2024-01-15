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
								<form action="<?php echo site_url('report/winloss_player_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
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
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d');?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php echo (isset($data_search['username']) ? $data_search['username'] : '');?>">
												</div>
											</div>									
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_game_provider');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="game_provider_code" name="game_provider_code">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php
															foreach($game_list as $row)
															{
																echo '<option value="' . $row['game_code'] . '">' . $this->lang->line($row['game_name']) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_game_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="game_type_code" name="game_type_code">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php
															foreach(get_game_type() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>	
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_agent');?></label>
												<div class="col-8">
													<select class="form-control select2bs4 col-12" id="agent" name="agent">
												
													</select>
												</div>
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
							<?php if(permission_validation(PERMISSION_WIN_LOSS_PLAYER_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/winloss_player_report_export', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="60"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="100"><?php echo $this->lang->line('label_username');?></th>
											<th width="60"><?php echo $this->lang->line('label_tag_code');?></th>
											<th width="120"><?php echo $this->lang->line('label_membership_level');?> (<?php echo $this->lang->line('label_membership_number');?>)</th>
											<th width="100"><?php echo $this->lang->line('label_bank_account_name');?></th>
											<th width="200"><?php echo $this->lang->line('label_bet_amount');?></th>
											<th width="200"><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th width="200"><?php echo $this->lang->line('label_win_loss');?></th>
											<th width="100"><?php echo $this->lang->line('label_rtp');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="5" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="player_total_bet_amount">0</span></th>
											<th><span id="player_total_rolling_amount">0</span></th>
											<th><span id="player_total_win_loss">0</span></th>
											<th><span id="player_total_rtp">0</span></th>
										</tr>
									</tfoot>
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
			$('#agent').select2({
				placeholder: '<?php echo $this->lang->line('label_select');?>',
				allowClear: true,
			});
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
							game_provider_code:  $('#game_provider_code').val(),
							username : $('#username').val(),
							game_type_code: $('#game_type_code').val(),
							agent: $('#agent').val(),
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
									$('#report-table').DataTable().ajax.reload();
									loadTotal();
								}
								else {
									obj.show();
									loadTable();
									loadTotal();
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

			call_user_data();
		});

		function loadTable() {
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"deferRender": true,
				"pageLength" : 10,
				"lengthMenu": [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/winloss_player_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							/*
							$('span#total_bet_amount').html(json.responseJSON.total_data.total_bet_amount);
							$('span#total_payout_amount').html(json.responseJSON.total_data.total_payout_amount);
							$('span#total_win_loss').html(json.responseJSON.total_data.total_win_loss);
							$('span#total_rolling_amount').html(json.responseJSON.total_data.total_rolling_amount);
							$('span#total_jackpot_win').html(json.responseJSON.total_data.total_jackpot_win);
							$('span#total_promotion_amount').html(json.responseJSON.total_data.total_promotion_amount);
							*/
						}
					},
				},
				"columnDefs": [
					<?php if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){ ?>
					{"targets": [0], "visible": false},
					{"targets": [8], "orderable": false},					
					<?php }else{ ?>
					{"targets": [0], "visible": false},
					{"targets": [8], "orderable": false},
					{"targets": [4], "visible": false},
					<?php } ?>
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
		}

		function loadTotal(){
			$.ajax({url: '<?php echo base_url('report/winloss_player_total/');?>',
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
							if(json.total_data.total_bet_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_bet_amount').removeClass().addClass(deposit_class);
							if(json.total_data.total_rolling_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_rolling_amount').removeClass().addClass(deposit_class);
							if(json.total_data.total_win_loss>=0){if(json.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#player_total_win_loss').removeClass().addClass(win_loss_class);
							if(json.total_data.total_rtp>=0){if(json.total_data.total_rtp==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#player_total_rtp').removeClass().addClass(win_loss_class);

							$('span#player_total_bet_amount').html(parseFloat(json.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_rolling_amount').html(parseFloat(json.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_win_loss').html(parseFloat(json.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_rtp').html(parseFloat(json.total_data.total_rtp).toFixed(2).toLocaleString('en')+"%");
						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function showGameProvider(username){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_win_loss_report_player');?>',
				content: '<?php echo base_url('report/winloss_player_game_provider/');?>' + username
			});
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/winloss_player_report_export_check");?>',
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
						var form_excel = $('#export_form').submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}

		function call_user_data(){
			$.ajax({url: '<?php echo base_url("user/get_all_user_data");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						var userData = json.result;
						for (i = 0; i < json.response.length; i++) {
							$("#agent").append($('<option></option>').val(json.response[i]['username']).html(json.response[i]['username']));
						}
						$("#agent").val('');
					}
				},
				error: function (request,error){
				}
			});
		}
	</script>	
</body>
</html>
