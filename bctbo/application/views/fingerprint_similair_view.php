<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<?php echo form_open('fingerprint/similiar_fingerprint_code_search', array('id' => 'fingerprint_similiar-form', 'name' => 'fingerprint_similiar-form', 'class' => 'form-horizontal'));?>
								<div class="form-group row">
									<div class="col-md-3">
										<div class="row mb-2">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
											<div class="col-8">
												<input type="text" class="form-control form-control-sm" id="username" name="username" value="">
											</div>
										</div>
										<div class="row mb-2">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_ip');?></label>
											<div class="col-8">
												<input type="text" class="form-control form-control-sm" id="ip_address" name="ip_address" value="">
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
							<?php echo form_close();?>
						</div>
						<!-- /.card-header -->
						
						<!-- /.card-header -->
						<div class="card-body">
							<table id="fingerprint_similiar-table" class="table table-striped table-bordered table-hover" style="width:100%;">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('label_hashtag');?></th>
										<th><?php echo $this->lang->line('label_username');?></th>
										<th><?php echo $this->lang->line('label_fingerprint_code');?></th>
										<th><?php echo $this->lang->line('label_ip');?></th>
										<th><?php echo $this->lang->line('label_created_by');?></th>
										<th><?php echo $this->lang->line('label_created_date');?></th>
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
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			var form = $('#fingerprint_similiar-form');

			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
							username:  $('#username').val(),
							ip_address:  $('#ip_address').val(),
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
									$('#fingerprint_similiar-table').DataTable().ajax.reload();
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

			$('#fingerprint_similiar-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('fingerprint/fingerprint_similiar_listing/');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						console.log(json);
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
					"infoFiltered": "<?php echo $this->lang->line('js_info_filtered');?>",
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
	</script>
</body>
</html>
