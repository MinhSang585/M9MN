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
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<form action="<?php echo site_url('report/transaction_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php echo (isset($data_search['username']) ? $data_search['username'] : '');?>">
												</div>
											</div>							
										</div>
										<div class="col-md-3">
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_bet_id');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="bet_id" name="bet_id" value="">
												</div>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_game_code');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="game_code" name="game_code" value="">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_time_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="game_time_type" name="game_time_type">
														<option value="<?php echo TIME_TYPE_BET_TIME;?>" selected><?php echo $this->lang->line('label_bet_time');?></option>
														<option value="<?php echo TIME_TYPE_PAYOUT_TIME;?>"><?php echo $this->lang->line('label_payout_time');?></option>
													    <option value="<?php echo TIME_TYPE_GAME_TIME;?>"><?php echo $this->lang->line('label_game_time');?></option>
													    <option value="<?php echo TIME_TYPE_REPORT_TIME;?>"><?php echo $this->lang->line('label_report_time');?></option>
													    <option value="<?php echo TIME_TYPE_SATTLE_TIME;?>"><?php echo $this->lang->line('label_sattle_time');?></option>
													    <option value="<?php echo TIME_TYPE_COMPARE_TIME;?>"><?php echo $this->lang->line('label_compare_time');?></option>
													    <option value="<?php echo TIME_TYPE_INSERT_UPDATE_TIME;?>"><?php echo $this->lang->line('label_insert_update_time');?></option>
													</select>
												</div>
											</div>							
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="result_status" name="result_status">
														<option value="" selected><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_PENDING;?>"><?php echo $this->lang->line('status_pending');?></option>
														<option value="<?php echo STATUS_COMPLETE;?>"><?php echo $this->lang->line('status_completed');?></option>
														<option value="<?php echo STATUS_CANCEL;?>"><?php echo $this->lang->line('status_cancelled');?></option>
													</select>
												</div>
											</div>
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
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/transaction_export_excel', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<!-- /.card-header -->
							<?php endif;?>
							
							<!-- /.card-header -->
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_bet_time');?></th>
											<th width="120"><?php echo $this->lang->line('label_username');?></th>
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
								username : $('#username').val(),
								game_type_code:  $('#game_type_code').val(),
								bet_id:  $('#bet_id').val(),
								game_code:  $('#game_code').val(),
								game_time_type : $('#game_time_type').val(),
								result_status : $('#result_status').val(),
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
				"lengthMenu": [[1, 10, 25, 50, 100, 1000], [1, 10, 25, 50, 100, 1000]],
				"order": [[1, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/transaction_listing');?>",
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
					{"targets": [0], "visible": false},
					{"targets": [11], "visible": false},
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
			$('span#total_bet_amount').removeClass();
			$('span#total_bet_amount').html("0.00");
			$('span#total_win_loss').removeClass();
			$('span#total_win_loss').html("0.00");
			$('span#total_rolling_amount').removeClass();
			$('span#total_rolling_amount').html("0.00");
			$('span#total_jackpot_win').removeClass();
			$('span#total_jackpot_win').html("0.00");

			$.ajax({url: '<?php echo base_url('report/transaction_total/');?>',
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
					}
				},
				error: function (request,error) {
				}
			});
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/transaction_export_excel_check");?>',
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
	</script>	
</body>
</html>
