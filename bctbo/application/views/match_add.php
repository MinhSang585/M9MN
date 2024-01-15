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
							<?php echo form_open_multipart('match/submit', array('id' => 'match-form', 'name' => 'match-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="match_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_match_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="match_name" name="match_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="match_url" class="col-5 col-form-label"><?php echo $this->lang->line('label_url');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-12" id="match_url" name="match_url" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="match_start" class="col-5 col-form-label"><?php echo $this->lang->line('label_match_start');?></label>
										<div class="col-7">
											<div class="input-group date" id="match_start_click" data-target-input="nearest">
												<input type="text" id="match_start" name="match_start" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 00:00:00');?>" data-target="#match_start_click"/>
												<div class="input-group-append" data-target="#match_start_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="match_end" class="col-5 col-form-label"><?php echo $this->lang->line('label_match_end');?></label>
										<div class="col-7">
											<div class="input-group date" id="match_end_click" data-target-input="nearest">
												<input type="text" id="match_end" name="match_end" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 23:59:59');?>" data-target="#match_end_click"/>
												<div class="input-group-append" data-target="#match_end_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="match_sequence" class="col-5 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-3" id="match_sequence" name="match_sequence" value="" maxlength="3">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
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
														$content_html .= '<label for="match_title_' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_match_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="match_title_' . $v . '" name="match_title_' . $v . '" value="">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="match_content_' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control" id="match_content_' . $v . '" name="match_content_' . $v . '" rows="4"></textarea>';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<ul class="nav nav-tabs" id="custom-content-below-'.$v.'-tab" role="tablist">';
														$content_html .= '<li class="nav-item">';
															$content_html .= '<a class="nav-link active" id="custom-content-below-web-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-web-'.$v.'" role="tab" aria-controls="custom-content-below-web-'.$v.'" aria-selected="true">'.$this->lang->line('label_web').'</a>';
														$content_html .= '</li>';
														$content_html .= '<li class="nav-item">';
															$content_html .= '<a class="nav-link" id="custom-content-below-mobile-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-mobile-'.$v.'" role="tab" aria-controls="custom-content-below-mobile-'.$v.'" aria-selected="true">'.$this->lang->line('label_mobile').'</a>';
														$content_html .= '</li>';
													$content_html .= '</ul>';
													$content_html .= '<div class="tab-content" id="custom-content-below-'.$v.'-tabContent">';
														$content_html .= '<div class="tab-pane fade show active" id="custom-content-below-web-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-web-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="web_banner_'.$v.'" name="web_banner_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="web_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_match_width_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_width').'</label>';
																$content_html .= '<div class="col-2">';
																	$content_html .= '<input type="text" class="form-control col-12" id="web_match_width_'.$v.'" name="web_match_width_'.$v.'" value="" maxlength="4">';
																$content_html .= '</div>';
																$content_html .= '<label class="col-5 col-form-label font-weight-normal">px</label>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_match_height_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_height').'</label>';
																$content_html .= '<div class="col-2">';
																	$content_html .= '<input type="text" class="form-control col-12" id="web_match_height_'.$v.'" name="web_match_height_'.$v.'" value="" maxlength="4">';
																$content_html .= '</div>';
																$content_html .= '<label class="col-5 col-form-label font-weight-normal">px</label>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_match_alt_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_image_alt').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<input type="text" class="form-control col-10" id="web_match_alt_'.$v.'" name="web_match_alt_'.$v.'" value="">';
																$content_html .= '</div>';
															$content_html .= '</div>';
														$content_html .= '</div>';
														$content_html .= '<div class="tab-pane fade" id="custom-content-below-mobile-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-mobile-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="mobile_banner_'.$v.'" name="mobile_banner_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="mobile_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_match_width_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_width').'</label>';
																$content_html .= '<div class="col-2">';
																	$content_html .= '<input type="text" class="form-control col-12" id="mobile_match_width_'.$v.'" name="mobile_match_width_'.$v.'" value="" maxlength="4">';
																$content_html .= '</div>';
																$content_html .= '<label class="col-5 col-form-label font-weight-normal">px</label>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_match_height_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_height').'</label>';
																$content_html .= '<div class="col-2">';
																	$content_html .= '<input type="text" class="form-control col-12" id="mobile_match_height_'.$v.'" name="mobile_match_height_'.$v.'" value="" maxlength="4">';
																$content_html .= '</div>';
																$content_html .= '<label class="col-5 col-form-label font-weight-normal">px</label>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_match_alt_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_image_alt').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<input type="text" class="form-control col-10" id="mobile_match_alt_'.$v.'" name="mobile_match_alt_'.$v.'" value="">';
																$content_html .= '</div>';
															$content_html .= '</div>';
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
			var form = $('#match-form');

			$('#match_start_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#match_end_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });

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
									parent.$('#match-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.match_name_error != '') {
										message = json.msg.match_name_error;
									}
									if(json.msg.match_sequence_error != '') {
										message = json.msg.match_sequence_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error){
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					match_name: {
						required: true
					},
					match_sequence: {
						required: true,
						digits: true
					},
				},
				messages: {
					match_name: {
						required: "<?php echo $this->lang->line('error_enter_match_name');?>",
					},
					match_sequence: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
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
