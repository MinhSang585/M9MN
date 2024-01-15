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
								<form action="<?php echo site_url('report/tag_process_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
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
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php echo (isset($data_search['username']) ? $data_search['username'] : '');?>">
												</div>
											</div>									
										</div>
										<div class="col-md-3">
											<div class="row mb-1">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_tag_from');?></label>
												<div class="col-8">
													<select class="select2 col-12" id="tag_from" name="tag_from[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>"></select>
												</div>
											</div>
											<div class="row mb-1">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_tag_to');?></label>
												<div class="col-8">
													<select class="select2 col-12" id="tag_to" name="tag_to[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>"></select>
												</div>
											</div>
											<div class="row mt-n0">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>&nbsp;
												<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-sm btn-info"><i class="fas fa-eraser nav-icon"></i> <?php echo $this->lang->line('label_quick_search_clear');?></button>
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
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo LEVEL_MOVEMENT_NONE;?>"><?php echo $this->lang->line('label_is_maintain');?></option>
														<option value="<?php echo LEVEL_MOVEMENT_UP;?>"><?php echo $this->lang->line('label_is_upgrade');?></option>
														<option value="<?php echo LEVEL_MOVEMENT_DOWN;?>"><?php echo $this->lang->line('label_is_downgrade');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_upgrade');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="is_upgrade" name="is_upgrade">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_no');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_yes');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_maintain');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="is_maintain" name="is_maintain">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_no');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_yes');?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_downgrade');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="is_downgrade" name="is_downgrade">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_no');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_yes');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_reset');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="is_reset" name="is_reset">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_no');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_yes');?></option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-1 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_more_filter');?></label>
										<div class="form-group clearfix col-11">
											<div class="custom-control custom-checkbox d-inline pr-2">
												<input class="custom-control-input" type="checkbox" id="tag_force" name="tag_force" value="1">
												<label class="custom-control-label font-weight-normal" for="tag_force"><?php echo $this->lang->line('label_force_change');?></label>
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
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_created_date');?></th>
											<th><?php echo $this->lang->line('label_username');?></th>
											<th><?php echo $this->lang->line('label_force_change');?></th>
											<th><?php echo $this->lang->line('label_tag_from');?></th>
											<th><?php echo $this->lang->line('label_tag_to');?></th>
											<th><?php echo $this->lang->line('label_win_loss');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<!--
											<th><?php echo $this->lang->line('label_is_upgrade');?></th>
											<th><?php echo $this->lang->line('label_is_maintain');?></th>
											<th><?php echo $this->lang->line('label_is_downgrade');?></th>
											<th><?php echo $this->lang->line('label_is_reset');?></th>
											-->
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

			$('.select2').select2();
			$('#agent').select2({
				placeholder: '<?php echo $this->lang->line('label_select');?>',
				allowClear: true,
			});
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});

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
							username : $('#username').val(),
							agent : $('#agent').val(),
							tag_from:  $('#tag_from').val(),
							tag_to:  $('#tag_to').val(),
							status:  $('#status').val(),
							tag_force : $('#tag_force').prop("checked"),
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
			call_tag_data();
		});
		
		function loadTable() {
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/tag_process_listing');?>",
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
					{"targets": [7], "orderable": false},
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

		function call_tag_data(){
			$.ajax({url: '<?php echo base_url("tag/get_all_tag_data");?>',
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
							$("#tag_from").append($('<option></option>').val(json.response[i]['tag_id']).html(json.response[i]['tag_code']));
							$("#tag_to").append($('<option></option>').val(json.response[i]['tag_id']).html(json.response[i]['tag_code']));
						}
						$("#tag_from").val('');
						$("#tag_to").val('');
					}
				},
				error: function (request,error){
				}
			});
		}
	</script>	
</body>
</html>
