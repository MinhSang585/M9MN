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
								<form action="<?php echo site_url('report/register_deposit_rate_yearly_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_year');?></label>
												<div class="col-8 input-group date" id="from_year_click" data-target-input="nearest">
													<input type="text" id="from_year" name="from_year" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y');?>" data-target="#from_year_click"/>
													<div class="input-group-append" data-target="#from_year_click" data-toggle="datetimepicker">
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
											<div class="row mb-0">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_agent');?></label>
												<div class="col-8">
													<select class="form-control select2bs4 col-12" id="agent" name="agent">
												
													</select>
												</div>
											</div>
											<div class="row mb-2 mt-1">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
												<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-sm btn-info"><i class="fas fa-eraser nav-icon"></i> <?php echo $this->lang->line('label_quick_search_clear');?></button>
											</div>										
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_register_from');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['from_date']) ? $data_search['from_date'] : date('Y-m-d 00:00:00'));?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_register_to');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['to_date']) ? $data_search['to_date'] : date('Y-m-d 23:59:59'));?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_search_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="type" name="type">
														<option value="<?php echo SELECTION_TYPE_FIXED;?>"><?php echo $this->lang->line('selection_type_fixed');?></option>
														<option value="<?php echo SELECTION_TYPE_MORE;?>"><?php echo $this->lang->line('selection_type_more');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_deposit_count');?></label>
												<div class="col-8">
													<input type="number" class="form-control form-control-sm" id="count_deposit" name="count_deposit" value="">
												</div>
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
											<div class="row mb-2" style="display: none;">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_clear');?></button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/register_deposit_rate_yearly_export', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="80" class="bg-info"><?php echo $this->lang->line('label_username');?></th>
											<th width="100" class="bg-info"><?php echo $this->lang->line('label_level');?></th>
											<th width="80" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_member_deposit');?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jan').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jan').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_feb').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_feb').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_mar').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_mar').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_apr').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_apr').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_may').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_may').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jun').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jun').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jul').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jul').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_aug').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_aug').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_sep').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_sep').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_oct').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_oct').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_nov').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_nov').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_dec').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_dec').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('label_total').")";?></th>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('label_total').")";?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th width="300"  colspan="3" class=""><?php echo $this->lang->line('label_total');?>:</th>
											<th width="80" class="text-right"><span id="total_register_count">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_jan">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_jan">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_feb">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_feb">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_mar">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_mar">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_apr">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_apr">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_may">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_may">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_jun">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_jun">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_jul">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_jul">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_aug">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_aug">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_sep">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_sep">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_oct">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_oct">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_nov">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_nov">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_dec">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_dec">0</span></th>
											<th width="120" class="text-right"><span id="deposit_value_total">0</span></th>
											<th width="120" class="text-right"><span id="win_loss_value_total">0</span></th>
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
			
			$('#from_year_click').datetimepicker({
				format: 'YYYY',
                icons: {
                    time: "fa fa-clock"
                }
            });

			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                buttons: {
	                showToday: true,
	                showClear: true,
	                showClose: true
	            },
	            icons: {
	                today: 'fas fa-caret-square-right',
	                time: "fa fa-clock",
	                clear: 'fa fa-trash',
	                close: 'fas fa-times-circle'
	            },
            });
            $('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
				buttons: {
	                showToday: true,
	                showClear: true,
	                showClose: true
	            },
	            icons: {
	                today: 'fas fa-caret-square-right',
	                time: "fa fa-clock",
	                clear: 'fa fa-trash',
	                close: 'fas fa-times-circle'
	            },
            });

			$('#agent').select2({
				placeholder: '<?php echo $this->lang->line('label_select');?>',
				allowClear: true,
			});

            $("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'),
							from_year: $('#from_year').val(),
							username : $('#username').val(),
							agent: $('#agent').val(),
							from_date: $('#from_date').val(),
							to_date: $('#to_date').val(),
							type: $('#type').val(),
							count_deposit: $('#count_deposit').val(),
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
							var message = '';
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
								if(json.msg.from_date_error != '') {
									message = json.msg.from_date_error;
								}
								else if(json.msg.to_date_error != '') {
									message = json.msg.to_date_error;
								}
								else if(json.msg.from_year_error != '') {
									message = json.msg.from_year_error;
								}
								else if(json.msg.general_error != '') {
									message = json.msg.general_error;
								}
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
		
		function loadTable(){
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"ordering": true,
				"pageLength" : 10,
				"ajax": {
					"url": "<?php echo site_url('report/register_deposit_rate_yearly_listing');?>",
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
					{"targets": [2], "visible": false},
					{"targets": [4], "orderable": false},
					{"targets": [5], "orderable": false},
					{"targets": [6], "orderable": false},
					{"targets": [7], "orderable": false},
					{"targets": [8], "orderable": false},
					{"targets": [9], "orderable": false},
					{"targets": [10], "orderable": false},
					{"targets": [11], "orderable": false},
					{"targets": [12], "orderable": false},
					{"targets": [13], "orderable": false},
					{"targets": [14], "orderable": false},
					{"targets": [15], "orderable": false},
					{"targets": [16], "orderable": false},
					{"targets": [17], "orderable": false},
					{"targets": [18], "orderable": false},
					{"targets": [19], "orderable": false},
					{"targets": [20], "orderable": false},
					{"targets": [21], "orderable": false},
					{"targets": [22], "orderable": false},
					{"targets": [23], "orderable": false},
					{"targets": [24], "orderable": false},
					{"targets": [25], "orderable": false},
					{"targets": [26], "orderable": false},
					{"targets": [27], "orderable": false},
					{"targets": [28], "orderable": false},
					{"targets": [29], "orderable": false},
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
				"rowCallback": function (row, data, index) {
					$('td', row).eq(1).addClass('text-right');
					$('td', row).eq(2).addClass('text-right');
					$('td', row).eq(3).addClass('text-right');
					$('td', row).eq(4).addClass('text-right');
					$('td', row).eq(5).addClass('text-right');
					$('td', row).eq(6).addClass('text-right');
					$('td', row).eq(7).addClass('text-right');
					$('td', row).eq(8).addClass('text-right');
					$('td', row).eq(9).addClass('text-right');
					$('td', row).eq(10).addClass('text-right');
					$('td', row).eq(11).addClass('text-right');
					$('td', row).eq(12).addClass('text-right');
					$('td', row).eq(13).addClass('text-right');
					$('td', row).eq(14).addClass('text-right');
					$('td', row).eq(15).addClass('text-right');
					$('td', row).eq(16).addClass('text-right');
					$('td', row).eq(17).addClass('text-right');
					$('td', row).eq(18).addClass('text-right');
					$('td', row).eq(19).addClass('text-right');
					$('td', row).eq(20).addClass('text-right');
					$('td', row).eq(21).addClass('text-right');
					$('td', row).eq(22).addClass('text-right');
					$('td', row).eq(23).addClass('text-right');
					$('td', row).eq(24).addClass('text-right');
					$('td', row).eq(25).addClass('text-right');
					$('td', row).eq(26).addClass('text-right');
					$('td', row).eq(27).addClass('text-right');
					$('td', row).eq(28).addClass('text-right');
                }
			});
		}

		function loadTotal(){
			$('span#total_register_count').removeClass();
			$('span#total_register_count').html("0");
			$('span#deposit_value_jan').removeClass();
			$('span#deposit_value_jan').html("0");
			$('span#win_loss_value_jan').removeClass();
			$('span#win_loss_value_jan').html("0");
			$('span#deposit_value_feb').removeClass();
			$('span#deposit_value_feb').html("0");
			$('span#win_loss_value_feb').removeClass();
			$('span#win_loss_value_feb').html("0");
			$('span#deposit_value_mar').removeClass();
			$('span#deposit_value_mar').html("0");
			$('span#win_loss_value_mar').removeClass();
			$('span#win_loss_value_mar').html("0");
			$('span#deposit_value_apr').removeClass();
			$('span#deposit_value_apr').html("0");
			$('span#win_loss_value_apr').removeClass();
			$('span#win_loss_value_apr').html("0");
			$('span#deposit_value_may').removeClass();
			$('span#deposit_value_may').html("0");
			$('span#win_loss_value_may').removeClass();
			$('span#win_loss_value_may').html("0");
			$('span#deposit_value_jun').removeClass();
			$('span#deposit_value_jun').html("0");
			$('span#win_loss_value_jun').removeClass();
			$('span#win_loss_value_jun').html("0");
			$('span#deposit_value_jul').removeClass();
			$('span#deposit_value_jul').html("0");
			$('span#win_loss_value_jul').removeClass();
			$('span#win_loss_value_jul').html("0");
			$('span#deposit_value_aug').removeClass();
			$('span#deposit_value_aug').html("0");
			$('span#win_loss_value_aug').removeClass();
			$('span#win_loss_value_aug').html("0");
			$('span#deposit_value_sep').removeClass();
			$('span#deposit_value_sep').html("0");
			$('span#win_loss_value_sep').removeClass();
			$('span#win_loss_value_sep').html("0");
			$('span#deposit_value_oct').removeClass();
			$('span#deposit_value_oct').html("0");
			$('span#win_loss_value_oct').removeClass();
			$('span#win_loss_value_oct').html("0");
			$('span#deposit_value_nov').removeClass();
			$('span#deposit_value_nov').html("0");
			$('span#win_loss_value_nov').removeClass();
			$('span#win_loss_value_nov').html("0");
			$('span#deposit_value_dec').removeClass();
			$('span#deposit_value_dec').html("0");
			$('span#win_loss_value_dec').removeClass();
			$('span#win_loss_value_dec').html("0");
			$('span#deposit_value_total').removeClass();
			$('span#deposit_value_total').html("0");
			$('span#win_loss_value_total').removeClass();
			$('span#win_loss_value_total').html("0");


			$.ajax({url: '<?php echo base_url('report/register_deposit_rate_yearly_total/');?>',
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
						if(json.total_data.total_register_count>0){var deposit_class = "text-primary";}else{if(json.total_data.total_register_count<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#total_register_count').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_jan>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_jan<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_jan').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_jan>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_jan<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_jan').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_feb>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_feb<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_feb').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_feb>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_feb<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_feb').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_mar>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_mar<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_mar').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_mar>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_mar<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_mar').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_apr>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_apr<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_apr').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_apr>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_apr<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_apr').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_may>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_may<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_may').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_may>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_may<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_may').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_jun>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_jun<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_jun').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_jun>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_jun<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_jun').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_jul>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_jul<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_jul').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_jul>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_jul<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_jul').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_aug>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_aug<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_aug').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_aug>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_aug<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_aug').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_sep>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_sep<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_sep').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_sep>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_sep<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_sep').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_oct>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_oct<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_oct').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_oct>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_oct<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_oct').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_nov>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_nov<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_nov').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_nov>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_nov<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_nov').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_dec>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_dec<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_dec').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_dec>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_dec<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_dec').removeClass().addClass(deposit_class);
						if(json.total_data.deposit_value_total>0){var deposit_class = "text-primary";}else{if(json.total_data.deposit_value_total<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#deposit_value_total').removeClass().addClass(deposit_class);
						if(json.total_data.win_loss_value_total>0){var deposit_class = "text-primary";}else{if(json.total_data.win_loss_value_total<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
						$('span#win_loss_value_total').removeClass().addClass(deposit_class);


						$('span#total_register_count').html(parseFloat(json.total_data.total_register_count).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#deposit_value_jan').html(parseFloat(json.total_data.deposit_value_jan).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_jan').html(parseFloat(json.total_data.win_loss_value_jan).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_feb').html(parseFloat(json.total_data.deposit_value_feb).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_feb').html(parseFloat(json.total_data.win_loss_value_feb).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_mar').html(parseFloat(json.total_data.deposit_value_mar).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_mar').html(parseFloat(json.total_data.win_loss_value_mar).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_apr').html(parseFloat(json.total_data.deposit_value_apr).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_apr').html(parseFloat(json.total_data.win_loss_value_apr).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_may').html(parseFloat(json.total_data.deposit_value_may).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_may').html(parseFloat(json.total_data.win_loss_value_may).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_jun').html(parseFloat(json.total_data.deposit_value_jun).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_jun').html(parseFloat(json.total_data.win_loss_value_jun).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_jul').html(parseFloat(json.total_data.deposit_value_jul).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_jul').html(parseFloat(json.total_data.win_loss_value_jul).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_aug').html(parseFloat(json.total_data.deposit_value_aug).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_aug').html(parseFloat(json.total_data.win_loss_value_aug).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_sep').html(parseFloat(json.total_data.deposit_value_sep).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_sep').html(parseFloat(json.total_data.win_loss_value_sep).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_oct').html(parseFloat(json.total_data.deposit_value_oct).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_oct').html(parseFloat(json.total_data.win_loss_value_oct).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_nov').html(parseFloat(json.total_data.deposit_value_nov).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_nov').html(parseFloat(json.total_data.win_loss_value_nov).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_dec').html(parseFloat(json.total_data.deposit_value_dec).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_dec').html(parseFloat(json.total_data.win_loss_value_dec).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('span#deposit_value_total').html(parseFloat(json.total_data.deposit_value_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#win_loss_value_total').html(parseFloat(json.total_data.win_loss_value_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/register_deposit_rate_yearly_export_check");?>',
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
