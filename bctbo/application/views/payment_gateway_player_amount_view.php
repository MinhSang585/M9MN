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
								<form action="<?php echo site_url('paymentgateway/player_amount_search');?>" id="paymentgateway-form" name="paymentgateway-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_name');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="payment_gateway_code" name="payment_gateway_code">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php
															foreach(get_payment_gateway_code() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="payment_gateway_type_code" name="payment_gateway_type_code">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php
															foreach(get_deposit_type() as $k => $v)
															{
																if($k != DEPOSIT_OFFLINE_BANKING){
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_player_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_active');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_inactive');?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="addData()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
							</div>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="payment_gateway-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="180"><?php echo $this->lang->line('label_name');?></th>
											<th width="150"><?php echo $this->lang->line('label_type');?></th>
											<th width="150"><?php echo $this->lang->line('label_player_username');?></th>
											<th width="150"><?php echo $this->lang->line('label_min_amounts');?></th>
											<th width="150"><?php echo $this->lang->line('label_max_amounts');?></th>
											<th width="100"><?php echo $this->lang->line('label_status');?></th>
											<th width="120"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="150"><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE) == TRUE):?>
											<th width="50"><?php echo $this->lang->line('label_action');?></th>
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
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#paymentgateway-form');

			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
								csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
								payment_gateway_code:  $('#payment_gateway_code').val(),
								payment_gateway_type_code:  $('#payment_gateway_type_code').val(),
								username:  $('#username').val(),
								status : $('#status').val(),
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
									$('#payment_gateway-table').DataTable().ajax.reload();
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

			callloadtable();
		});

		function callloadtable(){
			var obj = $('.card-body');
			if (obj.is(':visible')) {
				$('#payment_gateway-table').DataTable().ajax.reload();
			}
			else {
				obj.show();
				loadTable();
			}
		}

		function loadTable(){
			$('#payment_gateway-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "asc"]],
				"ajax": {
					"url": "<?php echo site_url('paymentgateway/player_amount_listing');?>",
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

		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '600px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_payment_gateway_player_amount');?>',
				content: '<?php echo base_url('paymentgateway/player_amount_add/');?>'
			});
		}

		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '600px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_payment_gateway_player_amount_setting');?>',
				content: '<?php echo base_url('paymentgateway/player_amount_edit/');?>' + id
			});
		}

		function deleteData(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('paymentgateway/player_amount_delete/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#payment_gateway-table').DataTable().ajax.reload();
					}
					
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}
	</script>	
</body>
</html>