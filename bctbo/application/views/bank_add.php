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
							<?php echo form_open_multipart('bank/submit', array('id' => 'bank-form', 'name' => 'bank-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="bank_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="bank_name" name="bank_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_code');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="bank_code" name="bank_code" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_currency');?></label>
										<div class="col-7">
											<select class="form-control select2bs4" id="currency_id" name="currency_id">
												<option value=""><?php echo $this->lang->line('error_select_currencies');?></option>
												<?php
													for($i=0;$i<sizeof($currencies_list);$i++)
													{
														echo '<option value="' . $currencies_list[$i]['currency_id'] . '">' . $currencies_list[$i]['currency_code'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="custom-content-below-web-tab" data-toggle="pill" href="#custom-content-below-web" role="tab" aria-controls="custom-content-below-web" aria-selected="true"><?php echo $this->lang->line('label_web');?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="custom-content-below-mobile-tab" data-toggle="pill" href="#custom-content-below-mobile" role="tab" aria-controls="custom-content-below-mobile" aria-selected="false"><?php echo $this->lang->line('label_mobile');?></a>
										</li>
									</ul>
									<div class="tab-content" id="custom-content-below-tabContent">
										<div class="tab-pane fade show active" id="custom-content-below-web" role="tabpanel" aria-labelledby="custom-content-below-web-tab">
											<div class="form-group row mt-3">
												<label for="web_image_on" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_on');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="web_image_on" name="web_image_on">
														<label class="custom-file-label" for="web_image_on"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
											<div class="form-group row mt-3">
												<label for="web_image_off" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_off');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="web_image_off" name="web_image_off">
														<label class="custom-file-label" for="web_image_off"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="custom-content-below-mobile" role="tabpanel" aria-labelledby="custom-content-below-mobile-tab">
											<div class="form-group row mt-3">
												<label for="mobile_image_on" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_on');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="mobile_image_on" name="mobile_image_on">
														<label class="custom-file-label" for="mobile_image_on"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
											<div class="form-group row mt-3">
												<label for="mobile_image_off" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_off');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="mobile_image_off" name="mobile_image_off">
														<label class="custom-file-label" for="mobile_image_off"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- /.card-body -->
								<div class="card-footer">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
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
			var form = $('#bank-form');
			
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
									parent.$('#bank-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.bank_name_error != '') {
										message = json.msg.bank_name_error;
									}
									else if(json.msg.bank_code_error != ''){
										message = json.msg.bank_code_error;
									}
									else if(json.msg.currency_id_error != ''){
										message = json.msg.currency_id_error;
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
					currency_id: {
						required: true
					},
					bank_name: {
						required: true
					},
					bank_code: {
						required: true
					}
				},
				messages: {
					currency_id: {
						required: "<?php echo $this->lang->line('error_select_currencies');?>",
					},
					bank_name: {
						required: "<?php echo $this->lang->line('error_enter_bank_name');?>",
					},
					bank_code: {
						required: "<?php echo $this->lang->line('error_enter_bank_code');?>",
					}
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
