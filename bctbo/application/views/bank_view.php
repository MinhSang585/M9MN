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
							<?php if(permission_validation(PERMISSION_BANK_ADD) == TRUE):?>
							<div class="card-header">
							    <form action="<?php echo site_url('bank/search');?>" id="bank-form" name="bank-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_bank_name');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="bank_name" name="bank_name" value="<?php echo (isset($data_search['bank_name']) ? $data_search['bank_name'] : '');?>">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_code');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="bank_code" name="bank_code" value="<?php echo (isset($data_search['bank_code']) ? $data_search['bank_code'] : '');?>">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="currency_id" name="currency_id">
        												<option value=""><?php echo $this->lang->line('error_select_currencies');?></option>
        												<?php
        													for($i=0;$i<sizeof($currencies_list);$i++)
        													{
        														if(isset($currency_id)) 
        														{
        															if($currencies_list[$i]['currency_id'] == $currency_id) 
        															{
        																echo '<option value="' . $currencies_list[$i]['currency_id'] . '" selected="selected">' . $currencies_list[$i]['currency_code'] . '</option>';
        															}
        															else
        															{
        																echo '<option value="' . $currencies_list[$i]['currency_id'] . '">' . $currencies_list[$i]['currency_code'] . '</option>';
        															}
        														}
        														else 
        														{
        															echo '<option value="' . $currencies_list[$i]['currency_id'] . '">' . $currencies_list[$i]['currency_code'] . '</option>';
        														}
        													}
        												?>
        											</select>
												</div>
											</div>	
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_inactive');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_active');?></option>
													</select>
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
								</form>
							  <h3 class="card-title"><button onclick="addData()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
							</div>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body">
								<table id="bank-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_bank_name');?></th>
											<th><?php echo $this->lang->line('label_code');?></th>
											<th><?php echo $this->lang->line('label_currency');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_BANK_UPDATE) == TRUE OR permission_validation(PERMISSION_BANK_DELETE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
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
			var form = $('#bank-form');
			
		    form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: {
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'),
							bank_name : $('#bank_name').val(),
							bank_code : $('#bank_code').val(),
							currency_id : $('#currency_id').val(),
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
								$('#bank-table').DataTable().ajax.reload();
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
		    
			$('#bank-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('bank/listing');?>",
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
		});
		
		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_bank');?>',
				content: '<?php echo base_url('bank/add/');?>'
			});
		}
		
		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '410px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_bank_setting');?>',
				content: '<?php echo base_url('bank/edit/');?>' + id
			});
		}
		
		function deleteData(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('bank/delete/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#bank-table').DataTable().ajax.reload();
					}
					
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}
	</script>	
</body>
</html>
