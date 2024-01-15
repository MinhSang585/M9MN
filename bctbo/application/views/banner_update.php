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
							<?php echo form_open_multipart('banner/update', array('id' => 'banner-form', 'name' => 'banner-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="banner_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_banner_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-10" id="banner_name" name="banner_name" value="<?php echo (isset($banner_name) ? $banner_name : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="language_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_language');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="language_id" name="language_id">
												<?php
													$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
													for($i=0;$i<sizeof($lang);$i++)
													{
														if(isset($language_id)) {
															if($lang[$i] == $language_id) {
																echo '<option value="' . $lang[$i] . '" selected="selected">' . $this->lang->line(get_site_language_name($lang[$i])) . '</option>';
															}
															else {
																echo '<option value="' . $lang[$i] . '">' . $this->lang->line(get_site_language_name($lang[$i])) . '</option>';
															}
														}
														else {
															echo '<option value="' . $lang[$i] . '">' . $this->lang->line(get_site_language_name($lang[$i])) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="banner_sequence" class="col-5 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-3" id="banner_sequence" name="banner_sequence" value="<?php echo (isset($banner_sequence) ? $banner_sequence : '');?>" maxlength="3">
										</div>
									</div>
									<div class="form-group row">
										<label for="banner_url" class="col-5 col-form-label"><?php echo $this->lang->line('label_url');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-10" id="banner_url" name="banner_url" value="<?php echo (isset($banner_url) ? $banner_url : '');?>">
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
											<?php if(isset($web_banner)):?>
											<div class="form-group row mt-3">
												<label class="col-5 col-form-label">&nbsp;</label>
												<div class="col-7">
													<a href="<?php echo BANNER_SOURCE_PATH . $web_banner;?>" target="_blank"><img src="<?php echo BANNER_SOURCE_PATH . $web_banner;?>" width="200" border="0" /></a>
												</div>
											</div>
											<?php endif;?>
											<div class="form-group row mt-3">
												<label for="web_banner" class="col-5 col-form-label"><?php echo $this->lang->line('label_banner');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="web_banner" name="web_banner">
														<label class="custom-file-label" for="web_banner"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="web_banner_width" class="col-5 col-form-label"><?php echo $this->lang->line('label_width');?></label>
												<div class="col-2">
													<input type="text" class="form-control col-12" id="web_banner_width" name="web_banner_width" value="<?php echo (isset($web_banner_width) ? $web_banner_width : '');?>" maxlength="4">
												</div>
												<label class="col-5 col-form-label font-weight-normal">px</label>
											</div>
											<div class="form-group row">
												<label for="web_banner_height" class="col-5 col-form-label"><?php echo $this->lang->line('label_height');?></label>
												<div class="col-2">
													<input type="text" class="form-control col-12" id="web_banner_height" name="web_banner_height" value="<?php echo (isset($web_banner_height) ? $web_banner_height : '');?>" maxlength="4">
												</div>
												<label class="col-5 col-form-label font-weight-normal">px</label>
											</div>
											<div class="form-group row">
												<label for="web_banner_alt" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_alt');?></label>
												<div class="col-7">
													<input type="text" class="form-control col-10" id="web_banner_alt" name="web_banner_alt" value="<?php echo (isset($web_banner_alt) ? $web_banner_alt : '');?>">
												</div>
											</div> 
										</div>
										<div class="tab-pane fade" id="custom-content-below-mobile" role="tabpanel" aria-labelledby="custom-content-below-mobile-tab">
											<?php if(isset($mobile_banner)):?>
											<div class="form-group row mt-3">
												<label class="col-5 col-form-label">&nbsp;</label>
												<div class="col-7">
													<a href="<?php echo BANNER_SOURCE_PATH . $mobile_banner;?>" target="_blank"><img src="<?php echo BANNER_SOURCE_PATH . $mobile_banner;?>" width="200" border="0" /></a>
												</div>
											</div>
											<?php endif;?>
											<div class="form-group row mt-3">
												<label for="mobile_banner" class="col-5 col-form-label"><?php echo $this->lang->line('label_banner');?></label>
												<div class="col-7">
													<div class="custom-file col-10">
														<input type="file" class="custom-file-input" id="mobile_banner" name="mobile_banner">
														<label class="custom-file-label" for="mobile_banner"><?php echo $this->lang->line('button_choose_file');?></label>
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="mobile_banner_width" class="col-5 col-form-label"><?php echo $this->lang->line('label_width');?></label>
												<div class="col-2">
													<input type="text" class="form-control col-12" id="mobile_banner_width" name="mobile_banner_width" value="<?php echo (isset($mobile_banner_width) ? $mobile_banner_width : '');?>" maxlength="4">
												</div>
												<label class="col-5 col-form-label font-weight-normal">px</label>
											</div>
											<div class="form-group row">
												<label for="mobile_banner_height" class="col-5 col-form-label"><?php echo $this->lang->line('label_height');?></label>
												<div class="col-2">
													<input type="text" class="form-control col-12" id="mobile_banner_height" name="mobile_banner_height" value="<?php echo (isset($mobile_banner_height) ? $mobile_banner_height : '');?>" maxlength="4">
												</div>
												<label class="col-5 col-form-label font-weight-normal">px</label>
											</div>
											<div class="form-group row">
												<label for="mobile_banner_alt" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_alt');?></label>
												<div class="col-7">
													<input type="text" class="form-control col-10" id="mobile_banner_alt" name="mobile_banner_alt" value="<?php echo (isset($mobile_banner_alt) ? $mobile_banner_alt : '');?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="banner_id" name="banner_id" value="<?php echo (isset($banner_id) ? $banner_id : '');?>">
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
			var form = $('#banner-form');
			
			bsCustomFileInput.init();
			
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
									parent.$('#uc1_' + json.response.id).html(json.response.banner_name);
									parent.$('#uc2_' + json.response.id).html(json.response.banner_sequence);
									parent.$('#uc3_' + json.response.id).html(json.response.language_id);
									parent.$('#uc4_' + json.response.id).html(json.response.active);
									parent.$('#uc5_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc6_' + json.response.id).html(json.response.updated_date);
									
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc4_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc4_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									
									parent.layer.close(index);
								}
								else {
									if(json.msg.banner_name_error != '') {
										message = json.msg.banner_name_error;
									}
									else if(json.msg.banner_sequence_error != '') {
										message = json.msg.banner_sequence_error;
									}
									else if(json.msg.language_id_error != '') {
										message = json.msg.language_id_error;
									}
									else if(json.msg.banner_url_error != '') {
										message = json.msg.banner_url_error;
									}
									else if(json.msg.web_banner_width_error != '') {
										message = json.msg.web_banner_width_error;
									}
									else if(json.msg.web_banner_height_error != '') {
										message = json.msg.web_banner_height_error;
									}
									else if(json.msg.mobile_banner_width_error != '') {
										message = json.msg.mobile_banner_width_error;
									}
									else if(json.msg.mobile_banner_height_error != '') {
										message = json.msg.mobile_banner_height_error;
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
					banner_name: {
						required: true
					},
					banner_sequence: {
						required: true,
						digits: true
					},
					language_id: {
						required: true,
						digits: true
					},
					banner_url: {
						url: true
					},
					web_banner_width: {
						digits: true
					},
					web_banner_height: {
						digits: true
					},
					mobile_banner_width: {
						digits: true
					},
					mobile_banner_height: {
						digits: true
					}
				},
				messages: {
					banner_name: {
						required: "<?php echo $this->lang->line('error_enter_banner_name');?>",
					},
					banner_sequence: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					language_id: {
						digits: "<?php echo $this->lang->line('error_select_language');?>",
					},
					banner_url: {
						url: "<?php echo $this->lang->line('error_ivalid_url_format');?>",
					},
					web_banner_width: {
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_width')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					web_banner_height: {
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_height')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					mobile_banner_width: {
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_width')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					mobile_banner_height: {
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_height')), $this->lang->line('error_only_digits_allowed'));?>",
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
