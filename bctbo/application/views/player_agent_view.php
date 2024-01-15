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
								<form action="<?php echo site_url('player/agent_search');?>" id="player-form" name="player-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_active');?></option>
														<option value="<?php echo STATUS_SUSPEND;?>"><?php echo $this->lang->line('status_suspend');?></option>
													</select>
												</div>
											</div>							
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_agent');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="agent" name="agent" value="">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_upline');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="upline" name="upline" value="">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal">&nbsp;</label>
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
											<div class="row mb-2">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_clear');?></button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_PLAYER_AGENT_ADD) == TRUE || permission_validation(PERMISSION_PLAYER_LIST_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title">
									<?php if(permission_validation(PERMISSION_PLAYER_LIST_EXPORT_EXCEL) == TRUE):?>
									<button onclick="exportData()" type="button" class="btn bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button>
									<?php endif;?>
									<?php if(permission_validation(PERMISSION_PLAYER_AGENT_ADD) == TRUE):?>
									<button onclick="addData()" type="button" class="btn bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button>
									<?php endif;?>
								</h3>
							</div>
							<!-- /.card-header -->
							<?php endif;?>
							<?php if(permission_validation(PERMISSION_PLAYER_LIST_EXPORT_EXCEL) == TRUE):?>
							<?php echo form_open('export/player_agent_report_export', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="player-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="60"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="150"><?php echo $this->lang->line('label_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_upline');?></th>
											<th width="120"><?php echo $this->lang->line('label_main_wallet');?></th>
											<th width="120"><?php echo $this->lang->line('label_status');?></th>
											<th width="150"><?php echo $this->lang->line('label_referrer');?></th>
											<th width="150"><?php echo $this->lang->line('label_registered_date');?></th>
											<?php if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE || permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE || permission_validation(PERMISSION_PLAYER_AGENT_UPDATE) == TRUE OR permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE OR permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE OR permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE OR permission_validation(PERMISSION_LOGIN_REPORT) == TRUE):?>
											<th width="200"><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
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
			$('#player-form').submit();
		}
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#player-form');
			callloadtable();
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

			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
								csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
								upline:  $('#upline').val(),
								username : $('#username').val(),
								status : $('#status').val(),
								player_type : $('#player_type').val(),
								mobile : $('#mobile').val(),
								bank_account_name : $('#bank_account_name').val(),
								agent : $('#agent').val(),
								referrer : $('#referrer').val(),
								from_date:  $('#from_date').val(),
								to_date:  $('#to_date').val(),
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
								$('#player-table').DataTable().ajax.reload();
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
		});

		function callloadtable(){
			var obj = $('.card-body');
			if (obj.is(':visible')) {
				$('#player-table').DataTable().ajax.reload();
			}
			else {
				obj.show();
				loadTotal();
			}
		}

		function loadTotal(){
			$('#player-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('player/player_agent_listing/');?>",
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
		};

		function changePassword(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '380px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_change_password');?>',
				content: '<?php echo base_url('player/password/');?>' + id
			});
		}


		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '550px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_player');?>',
				content: '<?php echo base_url('player/agent_add/');?>'
			});
		}
		
		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_setting');?>',
				content: '<?php echo base_url('player/agent_edit/');?>' + id
			});
		}

		function bet_record(username){
			window.open("<?php echo base_url('report/transaction.jsp?username=')?>"+username, "_blank"); 
		}

		function win_loss_player(username){
			window.open("<?php echo base_url('report/winloss_player.jsp?username=')?>"+username, "_blank"); 
		}

		function login_report(username){
			window.open("<?php echo base_url('report/login.jsp?username=')?>"+username, "_blank"); 
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/player_agent_report_export_check");?>',
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

		function depositPoints(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '510px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_deposit_point_to_downline');?>',
				content: '<?php echo base_url('player/deposit/');?>' + id
			});
		}
		
		function withdrawPoints(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '510px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_withdraw_point_from_downline');?>',
				content: '<?php echo base_url('player/withdraw/');?>' + id
			});
		}
	</script>	
</body>
</html>
