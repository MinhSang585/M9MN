<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<!-- form start -->
							<?php echo form_open('blacklist/report_update', array('id' => 'blacklist_report-form', 'name' => 'blacklist_report-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="im_value" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($blacklist_type_content) ? $blacklist_type_content : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="im_value" class="col-5 col-form-label"><?php echo $this->lang->line('label_value');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($blacklist_content_content) ? $blacklist_content_content : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="im_value" class="col-5 col-form-label"><?php echo $this->lang->line('label_info');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($blacklist_info_content) ? $blacklist_info_content : '-');?></label>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="blacklists_report_id" name="blacklists_report_id" value="<?php echo (isset($blacklists_report_id) ? $blacklists_report_id : '');?>">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
								</div>
								<!-- /.card-footer -->
							<?php echo form_close();?>
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#blacklist_report-form');
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						$.ajax({url: form.attr('action'),
							data: form.serialize(),
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
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									msg_icon = 1;
									parent.$('#uc4_' + json.response.id).remove();
									parent.$('#uc3_' + json.response.id).html(json.response.status);
									parent.$('#uc5_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc6_' + json.response.id).html(json.response.updated_date);
									
									if(json.response.status_code == '<?php echo STATUS_COMPLETE;?>') {
										parent.$('#uc3_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc3_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									
									parent.layer.close(index);
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate();
		});
	</script>
</body>
</html>
