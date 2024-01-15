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
							<?php echo form_open_multipart('message/submit', array('id' => 'message-form', 'name' => 'message-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="label_message_name" class="col-2 col-form-label"><?php echo $this->lang->line('label_message_name');?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="system_message_name" name="system_message_name" value="">
										</div>
									</div>

									<div class="form-group row">
										<label for="system_message_type" class="col-2 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="system_message_type" name="system_message_type">
												<option value=""><?php echo $this->lang->line('place_holder_select_type');?></option>
												<option value="<?php echo MESSAGE_CUSTOMER_SERVICE;?>"><?php echo $this->lang->line('message_customer_service');?></option>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="system_message_genre" class="col-2 col-form-label"><?php echo $this->lang->line('label_genre');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="system_message_genre" name="system_message_genre">
												<option value=""><?php echo $this->lang->line('place_holder_select_genres');?></option>
												<?php
													$system_message_genre_data = get_message_genre();
													if(!empty($system_message_genre_data) && sizeof($system_message_genre_data)){
														foreach($system_message_genre_data as $k => $v)
														{
															echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="system_message_remark" class="col-2 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
										<div class="col-10">
											<textarea class="form-control" id="system_message_remark" name="system_message_remark" rows="3"></textarea>
										</div>
									</div>

									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-10">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>

									<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-' . $v . '" role="tab" aria-controls="custom-content-below-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');
												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="message_title-' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_message_title') . '</label>';
														$content_html .= '<div class="col-10">';
															$content_html .= '<input type="text" class="form-control" id="message_title-' . $v . '" name="message_title-' . $v . '" value="">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="message_content-' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-10">';
															$content_html .= '<textarea class="form-control" id="message_content-' . $v . '" name="message_content-' . $v . '" rows="10"></textarea>';
														$content_html .= '</div>';
													$content_html .= '</div>';
												$content_html .= '</div>';
											}
											
											$tab_html .= '</ul>';
											$content_html .= '</div>';
										}

										$html = $tab_html . $content_html;
										echo $html;
									?>

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
			var form = $('#message-form');

			$('.select2').select2();

			$(document).on('keypress', '.select2', function () {
			    $(this).val($(this).val().replace(/[^\d].+/, ""));
			    if ((event.which < 48 || event.which > 57)) {
			      event.preventDefault();
			    }
			});
			
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
								var message = '';
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#message-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.system_message_name_error != '') {
										message = json.msg.system_message_name_error;
									}
									else if(json.msg.system_message_type_error != '') {
										message = json.msg.system_message_type_error;
									}
									else if(json.msg.system_message_genre_error != '') {
										message = json.msg.system_message_genre_error;
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
					system_message_name: {
						required: true
					},
					system_message_type: {
						required: true
					},
					system_message_genre: {
						required: true
					},
				},
				messages: {
					system_message_name: {
						required: "<?php echo $this->lang->line('error_enter_message_name');?>",
					},
					system_message_type: {
						required: "<?php echo $this->lang->line('error_select_type');?>",
					},
					system_message_genre: {
						required: "<?php echo $this->lang->line('error_select_genres');?>",
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
