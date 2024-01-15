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
							<?php echo form_open_multipart('avatar/submit', array('id' => 'avatar-form', 'name' => 'avatar-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="avatar_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_avatar_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="avatar_name" name="avatar_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="avatar_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_avatar_image');?></label>
										<div class="col-7">
											<div class="custom-file col-10">
												<input type="file" class="custom-file-input" id="avatar_image" name="avatar_image">
												<label class="custom-file-label" for="avatar_image"><?php echo $this->lang->line('button_choose_file');?></label>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
									</div>
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
			var form = $('#avatar-form');

			
			
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

						var file_form = form[0];
						var formData = new FormData(file_form);
						$.each($("input[type='file']")[0].files, function(i, file) {
							formData.append('file', file);
						});
						
						$.ajax({url: form.attr('action'),
							data: formData,
							type: 'post',	
							processData: false,
							contentType: false,              
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
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#avatar-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.avatar_name_error != ''){
										message = json.msg.avatar_name_error;
									}
									if(json.msg.avatar_image_error != '') {
										message = json.msg.avatar_image_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					avatar_name: {
						required: true
					},
				},
				messages: {
					avatar_name: {
						required: "<?php echo $this->lang->line('error_enter_avatar_name');?>",
					},
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
</body>
</html>
