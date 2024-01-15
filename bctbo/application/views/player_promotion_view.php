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
								<form action="<?php echo site_url('playerpromotion/search');?>" id="playerpromotion-form" name="playerpromotion-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['from_date']) ? $data_search['from_date'] : date('Y-m-d 00:00:00'));?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['to_date']) ? $data_search['to_date'] : date('Y-m-d 23:59:59'));?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="">
												</div>
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
											<div class="row">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_PENDING;?>"><?php echo $this->lang->line('status_pending');?></option>
														<option value="<?php echo STATUS_ENTITLEMENT;?>"><?php echo $this->lang->line('status_entitlement');?></option>
														<option value="<?php echo STATUS_ACCOMPLISH;?>"><?php echo $this->lang->line('status_accomplish');?></option>
														<option value="<?php echo STATUS_SATTLEMENT;?>"><?php echo $this->lang->line('status_sattlement');?></option>
														<option value="<?php echo STATUS_CANCEL;?>"><?php echo $this->lang->line('status_cancelled');?></option>
														<option value="<?php echo STATUS_VOID;?>"><?php echo $this->lang->line('status_void');?></option>
														<option value="<?php echo STATUS_SYSTEM_CANCEL;?>"><?php echo $this->lang->line('status_system_cancel');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
												<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-sm btn-info"><i class="fas fa-eraser nav-icon"></i> <?php echo $this->lang->line('label_quick_search_clear');?></button>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_promotion');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="promotion" name="promotion" value="">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_is_reward');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="is_reward" name="is_reward">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_PENDING;?>"><?php echo $this->lang->line('status_pending');?></option>
														<option value="<?php echo STATUS_APPROVE;?>"><?php echo $this->lang->line('status_approved');?></option>
													</select>
												</div>
											</div>						
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_id');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="player_promotion_id" name="player_promotion_id" value="<?php echo (isset($data_search['player_promotion_id']) ? $data_search['player_promotion_id'] : '');?>">
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
										</div>
									</div>
								</form>
							</div>
							<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE || permission_validation(PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title">
									<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL) == TRUE):?>
									<button onclick="exportData()" type="button" class="btn bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button>
									<?php endif;?>
									<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE):?>
									<button onclick="addData()" type="button" class="btn bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button>
									<?php endif;?>
									<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_ADD) == TRUE):?>
									<button onclick="addBulkData()" type="button" class="btn bg-gradient-info btn-sm"><i class="fas fa-mail-bulk nav-icon"></i> <?php echo $this->lang->line('button_add_bulk');?></button>
									<?php endif;?>
								</h3>
							</div>
							<?php endif;?>
							<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL) == TRUE):?>
							<?php echo form_open('export/player_promotion_list_export', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<?php endif;?>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_UPDATE) == TRUE):?>
							<div class="card-header">
								<form action="<?php echo site_url('playerpromotion/bulk_update_check_submit');?>" id="playerpromotion-bulk-form" name="playerpromotion-bulk-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
								<div class="row mb-2">
									<div class="col-md-4 col-4">
										<input type="hidden" id="listing_submit" name="update_status" value="<?php echo STATUS_PENDING ?>">
										<button onclick="update_player_promotion_listing_status(<?php echo STATUS_ENTITLEMENT;?>)" type="button" class="btn bg-gradient-primary btn-sm"><i class="fas fa-running nav-icon"></i> <?php echo $this->lang->line('button_bulk_entitlement');?></button>
										<button onclick="update_player_promotion_listing_status(<?php echo STATUS_VOID;?>)" type="button" class="btn bg-gradient-warning btn-sm"><i class="fas fa-remove-format nav-icon"></i> <?php echo $this->lang->line('button_bulk_void');?></button>
										<button onclick="update_player_promotion_listing_status(<?php echo STATUS_CANCEL;?>)" type="button" class="btn bg-gradient-danger btn-sm"><i class="fas fa-times nav-icon"></i> <?php echo $this->lang->line('button_bulk_cancel');?></button>
										<button onclick="update_player_promotion_listing_status(<?php echo STATUS_SATTLEMENT;?>)" type="button" class="btn bg-gradient-success btn-sm"><i class="fas fa-highlighter nav-icon"></i> <?php echo $this->lang->line('button_bulk_approve');?></button>
									</div>
									<div class="col-md-8 col-8">
										<div class="row mb-2">
											<label class="col-2 col-form-label col-form-label-sm font-weight-normal text-right"><?php echo $this->lang->line('label_remark');?></label>
											<div class="col-10">
												<input type="text" class="form-control form-control-sm" id="remark" name="remark" value="">
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php endif;?>
							<div class="card-body">
								<table id="playerpromotion-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_UPDATE) == TRUE){ ?>
											<th><input type="checkbox" id="mainchkbox" name="chkbox[]" onchange="checkBox(this);" /></th>
											<?php }else{ ?>
											<th></th>
											<?php } ?>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_date');?></th>
											<th><?php echo $this->lang->line('label_username');?></th>
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
											<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="9" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_reward">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
											<th></th>
											<?php endif;?>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
							</form>
							<?php endif;?>
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
			$('#playerpromotion-form').submit();
		}
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#playerpromotion-form');
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

            $('#agent').select2({
				placeholder: '<?php echo $this->lang->line('label_select');?>',
				allowClear: true,
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
								status : $('#status').val(),
								promotion : $('#promotion').val(),
								player_promotion_id : $('#player_promotion_id').val(),
								agent: $('#agent').val(),
								is_reward: $('#is_reward').val(),
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
								$('#playerpromotion-table').DataTable().ajax.reload();
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


			var form_bulk = $('#playerpromotion-bulk-form');

			form_bulk.submit(function(e) {
				if(is_allowed == true) {
					var listing_submit = $('#listing_submit').val();
					if(listing_submit != "<?php echo STATUS_PENDING ?>"){
						is_allowed = false;
						var myCheckboxes = new Array();
				        $('input[name="chkbox[]"]:checked').each(function() {
				           myCheckboxes.push($(this).val());
				        });
						
						$.ajax({url: form_bulk.attr('action'),
							data: { 
									csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
									status:  $('#listing_submit').val(),
									remark:  $('#remark').val(),
									chkbox : myCheckboxes,
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
								console.log(json);
								var message = json.msg;
								var msg_icon = 2;
								
								$('meta[name=csrf_token]').attr('content', json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									msg_icon = 1;
									for (i = 0; i < json.response.length; i++) {
										$('#uc1_' + json.response[i]['id']).removeClass('bg-success').removeClass('bg-danger').removeClass('bg-primary').removeClass('bg-warning').removeClass('bg-secondary');
										$('#uc1_' + json.response[i]['id']).html(json.response[i]['status']);
										if(json.response[i]['status_code'] == '<?php echo STATUS_ENTITLEMENT;?>'){
											$('#uc1_' + json.response[i]['id']).addClass('bg-primary');
										}else if(json.response[i]['status_code'] == '<?php echo STATUS_SATTLEMENT;?>') {
											$('#uc1_' + json.response[i]['id']).addClass('bg-success');
										}else if(json.response[i]['status_code'] == '<?php echo STATUS_CANCEL;?>') {
											$('#uc1_' + json.response[i]['id']).addClass('bg-danger');
										}else if(json.response[i]['status_code'] == '<?php echo STATUS_VOID;?>') {
											$('#uc1_' + json.response[i]['id']).addClass('bg-warning');
										}else if(json.response[i]['status_code'] == '<?php echo STATUS_ACCOMPLISH;?>') {
											$('#uc1_' + json.response[i]['id']).addClass('bg-warning');
										}else{
											$('#uc1_' + json.response[i]['id']).addClass('bg-secondary');
										}
										$('#uc2_' + json.response[i]['id']).html(json.response[i]['remark']);
										$('#uc4_' + json.response[i]['id']).removeClass('bg-success').removeClass('bg-danger').removeClass('bg-primary').removeClass('bg-warning').removeClass('bg-secondary');
										$('#uc4_' + json.response[i]['id']).html(json.response[i]['is_reward']);
										if(json.response[i]['is_reward_code'] == '<?php echo STATUS_APPROVE;?>'){
											$('#uc4_' + json.response[i]['id']).addClass('bg-success');
										}else{
											$('#uc4_' + json.response[i]['id']).addClass('bg-secondary');
										}
										$('#uc9_' + json.response[i]['id']).html(json.response[i]['updated_by']);
										$('#uc10_' + json.response[i]['id']).html(json.response[i]['updated_date']);
										if(json.response[i]['reward_date'] != ""){
											$('#uc6_' + json.response[i]['id']).html(json.response[i]['reward_date']);
										}

										if(json.response[i]['starting_date'] != ""){
											$('#uc7_' + json.response[i]['id']).html(json.response[i]['starting_date']);
										}

										if(json.response[i]['complete_date'] != ""){
											$('#uc8_' + json.response[i]['id']).html(json.response[i]['complete_date']);
										}

										if(json.response[i]['status_code'] == '<?php echo STATUS_SATTLEMENT;?>' || json.response[i]['status_code'] == '<?php echo STATUS_VOID;?>' || json.response[i]['status_code'] == '<?php echo STATUS_CANCEL;?>') {
											$('#uc88_' + json.response[i]['id']).remove();
											$('#uc21_' + json.response[i]['id']).remove();
										}else{
											$('#uc21_' + json.response[i]['id']).hide();
											$('#uc22_' + json.response[i]['id']).show();
										}
									}
								}
								else {
									if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
								$('#listing_submit').val("<?php echo STATUS_PENDING ?>");
								$('#remark').val('');
							},
							error: function (request,error) {
							}
						});
					}
				}

				return false;
			});

			$('#playerpromotion-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[1, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('playerpromotion/listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							$('span#total_reward').html(json.responseJSON.total_data.total_reward);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "orderable": false},
					{"targets": [5], "visible": false},
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

			call_user_data();
		});
		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '600px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_promotion');?>',
				content: '<?php echo base_url('playerpromotion/add/');?>'
			});
		}

		function addBulkData(){
			layer.open({
				type: 2,
				area: [((browser_width < 1200) ? '100%': '1200px'), ((browser_width < 750) ? '100%': '750px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_bulk_promotion');?>',
				content: '<?php echo base_url('playerpromotion/bulk_promotion_add/');?>'
			});	
		}

		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/edit/');?>' + id
			});
		}
		function promotionEntitlement(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/entitlement/');?>' + id
			});
		}
		function betDetailData(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/bet_detail/');?>' + id
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

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/player_promotion_list_export_check");?>',
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

		function checkBox(t) {
			if(t.checked == true) {
				checkAll(document.getElementsByName(t.name));
			}
			else {
				uncheckAll(document.getElementsByName(t.name));
			}
			
		}

		// All chekcbox checking
		function checkOrUncheck(t) {
			var check = true;
			var field = document.getElementsByName(t.name);
			for(i = 1; i < field.length; i++) {
				if(field[i].checked == false)
					check = false;
			}
			field[0].checked = check;
			if(check == true) {
				$('#uniform-mainchkbox span').addClass('checked');
			}
			else {
				$('#uniform-mainchkbox span').removeClass('checked');
			}
		}

		function checkAll(field) {
			for (i = 0; i < field.length; i++)
				field[i].checked = true ;
		}

		function uncheckAll(field) {
			for (i = 0; i < field.length; i++)
				field[i].checked = false ;
		}

		function update_player_promotion_listing_status(status) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function(index) {
				$('#listing_submit').val(status);
				layer.close(index);
				$('#playerpromotion-bulk-form').submit();
			}, function() {
				$('#listing_submit').val(0);
				$('#remark').val('');
			});
		}
	</script>
</body>
</html>
