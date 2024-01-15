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
								<form action="<?php echo site_url('report/withdrawal_verify_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_withdrawal_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['from_date']) ? $data_search['from_date'] : date('Y-m-d'));?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_withdrawal_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['to_date']) ? $data_search['to_date'] : date('Y-m-d'));?>" data-target="#to_date_click"/>
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_agent');?></label>
												<div class="col-8">
													<select class="form-control select2bs4 col-12" id="agent" name="agent" value="<?php echo (isset($data_search['agent']) ? $data_search['agent'] : '');?>">
												
													</select>
												</div>
											</div>
											<div class="row">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal">&nbsp;</label>
											</div>
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>&nbsp;
												<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-sm btn-info"><i class="fas fa-eraser nav-icon"></i> <?php echo $this->lang->line('label_quick_search_clear');?></button>
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
							<?php if(permission_validation(PERMISSION_WITHDRAWAL_VERIFY_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/withdrawal_verify_export_excel', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_username');?></th>
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
					$.ajax({url: form.attr('action'),
						data: {
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'),
							from_date:  $('#from_date').val(),
							to_date:  $('#to_date').val(),
							username : $('#username').val(),
							agent: $('#agent').val(),
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
								}
								else {
									obj.show();
									loadTable();
								}
							}
							else {
								if(json.msg.from_date_error != '') {
									message = json.msg.from_date_error;
								}
								else if(json.msg.to_date_error != '') {
									message = json.msg.to_date_error;
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

		function loadTable() {
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"deferRender": true,
				"pageLength" : 10,
				//"lengthMenu": [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/withdrawal_verify_listing');?>",
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
					{"targets": [4], "visible": false},
					{"targets": [8], "visible": false},
					{"targets": [10], "visible": false},
					{"targets": [12], "visible": false},
					{"targets": [17], "visible": false},

					{"targets": [18], "orderable": false},
					{"targets": [19], "orderable": false},
					{"targets": [20], "orderable": false},
					{"targets": [21], "orderable": false},
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

		function load_game_wallet(player_id){
			var total = $('#uc19_'+player_id).html();
			$.ajax({url: '<?php echo base_url('player/wallet_game_balance/');?>'+player_id,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					$('#uc16_'+player_id).html('<i class="fas fa-spinner fa-pulse"></i>');
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					$('meta[name=csrf_token]').attr('content', json.csrfHash);
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						$('#uc19_'+player_id).html(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.balance)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('#total_amount').val(parseFloat(parseFloat(total.replace(',', '')) - parseFloat(json.balance)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						if(json.balance>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
						$('span#uc16_'+player_id).removeClass().addClass(deposit_class);
						$('span#uc16_'+player_id).html(parseFloat(json.balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
					}
				},
				error: function (request,error) {
				}
			});
		}

		function player_daily(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_withdrawal_verify');?>',
				content: '<?php echo base_url('player/player_daily_report/');?>' + id
			});
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/withdrawal_verify_export_excel_check");?>',
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
