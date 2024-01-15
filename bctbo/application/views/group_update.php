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
							<?php echo form_open('group/update', array('id' => 'group-form', 'name' => 'group-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="group_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_group_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-7" id="group_name" name="group_name" value="<?php echo (isset($group_name) ? $group_name : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="group_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_group_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="group_type" name="group_type">
												<?php
													foreach(get_group_type() as $k => $v)
													{
														if(isset($group_type)) 
														{
															if($k == $group_type) 
															{
																echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
															}
															else
															{
																echo '<option value="' . $k. '">' . $this->lang->line($v) . '</option>';
															}
														}
														else 
														{
															echo '<option value="' . $k. '">' . $this->lang->line($v) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="created_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($created_by)) ? $created_by : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="created_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-');?></label>
										</div>
									</div>
									<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 1)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent">';
											for($i=2;$i<sizeof($lang);$i++)
											{
												$tab_active = (($i == 2) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $i . '-tab" data-toggle="pill" href="#custom-content-below-' . $i . '" role="tab" aria-controls="custom-content-below-' . $i . '" aria-selected="true">' . $this->lang->line(get_site_language_name($i)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($i == 2) ? 'show active' : '');
												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $i . '" role="tabpanel" aria-labelledby="custom-content-below-' . $i . '-tab">';
												$content_html .= '<div class="form-group row mt-3">';
												$content_html .= '<label for="group_name-' . $i . '" class="col-5 col-form-label">' . $this->lang->line('label_group_name') . '</label>';
												$content_html .= '<div class="col-7">';
												$content_html .= '<input type="text" class="form-control col-7" id="group_name-' . $i . '" name="group_name-' . $i . '" value="' . (isset($group_lang[$i]) ? $group_lang[$i] : '') . '">';
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
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="group_id" name="group_id" value="<?php echo (isset($group_id) ? $group_id : '');?>">
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
			var form = $('#group-form');
			
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
									parent.$('#uc1_' + json.response.id).html(json.response.group_name);
									parent.$('#uc2_' + json.response.id).html(json.response.group_type);
									parent.$('#uc3_' + json.response.id).html(json.response.active);
									parent.$('#uc4_' + json.response.id).html(json.response.created_by);
									parent.$('#uc5_' + json.response.id).html(json.response.created_date);
									parent.$('#uc6_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc7_' + json.response.id).html(json.response.updated_date);
									
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc3_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc3_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									
									parent.layer.close(index);
								}
								else {
									if(json.msg.group_name_error != '') {
										message = json.msg.group_name_error;
									}
									else if(json.msg.group_type_error != '') {
										message = json.msg.group_type_error;
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
					group_name: {
						required: true
					},
					group_type: {
						required: true,
						digits: true
					}
				},
				messages: {
					group_name: {
						required: "<?php echo $this->lang->line('error_enter_group_name');?>",
					},
					group_type: {
						required: "<?php echo $this->lang->line('error_select_group_type');?>",
						digits: "<?php echo $this->lang->line('error_select_group_type');?>",
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
