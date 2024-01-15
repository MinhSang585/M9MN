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
								<form action="<?php echo site_url('transaction/search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="transfer_type" name="transfer_type">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo TRANSFER_TRANSACTION_IN;?>"><?php echo $this->lang->line('transfer_transaction_in');?></option>
														<option value="<?php echo TRANSFER_TRANSACTION_OUT;?>"><?php echo $this->lang->line('transfer_transaction_out');?></option>
													</select>
												</div>
											</div>								
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_order_id');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="order_id" name="order_id" value="">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_order_id_alias');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="order_id_alias" name="order_id_alias" value="">
												</div>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_PENDING;?>"><?php echo $this->lang->line('status_pending');?></option>
														<option value="<?php echo STATUS_APPROVE;?>"><?php echo $this->lang->line('status_approved');?></option>
														<option value="<?php echo STATUS_CANCEL;?>"><?php echo $this->lang->line('status_cancelled');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal">&nbsp;</label>
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
								</form>
							</div>
							<!-- /.card-header -->
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="120"><?php echo $this->lang->line('label_type');?></th>
											<th width="120"><?php echo $this->lang->line('label_username');?></th>
											<th width="120"><?php echo $this->lang->line('label_order_id');?></th>
											<th width="120"><?php echo $this->lang->line('label_order_id_alias');?></th>
											<th width="80"><?php echo $this->lang->line('label_wallet_out');?></th>
											<th width="80"><?php echo $this->lang->line('label_wallet_in');?></th>
											<th width="130"><?php echo $this->lang->line('label_points_withdrawn');?></th>
											<th width="120"><?php echo $this->lang->line('label_points_deposited');?></th>
											<th width="130"><?php echo $this->lang->line('label_previous_balance');?></th>
											<th width="120"><?php echo $this->lang->line('label_balance');?></th>
											<th width="120"><?php echo $this->lang->line('label_status');?></th>
											<th width="120"><?php echo $this->lang->line('label_remark');?></th>
											<th width="120"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="120"><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="8" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_points_withdrawn">0</span></th>
											<th><span id="total_points_deposited">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<?php if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE) == TRUE):?>
											<th></th>
											<?php endif;?>
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
								username : $('#username').val(),
								order_id : $('#order_id').val(),
								order_id_alias : $('#order_id_alias').val(),
								status : $('#status').val(),
								transfer_type:  $('#transfer_type').val(),
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
					"url": "<?php echo site_url('transaction/listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							$('span#total_points_withdrawn').html(json.responseJSON.total_data.total_points_withdrawn);
							$('span#total_points_deposited').html(json.responseJSON.total_data.total_points_deposited);
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
		}

		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '380px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_wallet_transaction_pending_setting');?>',
				content: '<?php echo base_url('transaction/edit/');?>' + id
			});
		}	
	</script>	
</body>
</html>
