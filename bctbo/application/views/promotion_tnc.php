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
							<?php echo form_open_multipart('promotion/tnc_update', array('id' => 'promotion-form', 'name' => 'promotion-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="promotion_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['promotion_name']) ? $promotion['promotion_name'] : '-');?></label>
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
														$content_html .= '<label for="promotion_title_' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_promotion_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="promotion_title_' . $v . '" name="promotion_title_' . $v . '" value="'.(isset($promotion_lang[$v]['promotion_title']) ? $promotion_lang[$v]['promotion_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="promotion_content_' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="promotion_content_' . $v . '" name="promotion_content_' . $v . '" rows="4">'.(isset($promotion_lang[$v]['promotion_content']) ? $promotion_lang[$v]['promotion_content'] : '').'</textarea>';
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
														if(isset($promotion_lang[$v]['promotion_banner_web']) && $promotion_lang[$v]['promotion_banner_web'] != ""){
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<a href="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_web'].'" target="_blank"><img src="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_web'].'" width="200" border="0" /></a>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														}

															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner_out').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="web_banner_'.$v.'" name="web_banner_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="web_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															
															
														if(isset($promotion_lang[$v]['promotion_banner_web_content']) && $promotion_lang[$v]['promotion_banner_web_content'] != ""){
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<a href="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_web_content'].'" target="_blank"><img src="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_web_content'].'" width="200" border="0" /></a>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														}
														    $content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="web_banner_content_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner_in').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="web_banner_content_'.$v.'" name="web_banner_content_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="web_banner_content_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															
														$content_html .= '</div>';
														
														
														
														
														$content_html .= '<div class="tab-pane fade" id="custom-content-below-mobile-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-mobile-'.$v.'-tab">';
														if(isset($promotion_lang[$v]['promotion_banner_mobile']) && $promotion_lang[$v]['promotion_banner_mobile'] != ""){
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<a href="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_mobile'].'" target="_blank"><img src="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_mobile'].'" width="200" border="0" /></a>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														}
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner_out').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="mobile_banner_'.$v.'" name="mobile_banner_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="mobile_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															
															
														if(isset($promotion_lang[$v]['promotion_banner_mobile_content']) && $promotion_lang[$v]['promotion_banner_mobile_content'] != ""){
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<a href="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_mobile_content'].'" target="_blank"><img src="'.PROMOTION_SOURCE_PATH . $promotion_lang[$v]['promotion_banner_mobile_content'].'" width="200" border="0" /></a>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														}
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="mobile_banner_content_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_banner_in').'</label>';
																$content_html .= '<div class="col-7">';
																	$content_html .= '<div class="custom-file col-10">';
																		$content_html .= '<input type="file" class="custom-file-input" id="mobile_banner_content_'.$v.'" name="mobile_banner_content_'.$v.'">';
																		$content_html .= '<label class="custom-file-label" for="mobile_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																	$content_html .= '</div>';
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
									<input type="hidden" id="promotion_id" name="promotion_id" value="<?php echo (isset($promotion['promotion_id']) ? $promotion['promotion_id'] : '');?>">
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
			$(function () {
		    // Summernote
		    $('.summernote').summernote({
		    	height: 300,
		    })

		    // CodeMirror
		    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
		      mode: "htmlmixed",
		      theme: "monokai"
		    });
		  })
			var is_allowed = true;
			var form = $('#promotion-form');
			
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
									parent.layer.close(index);
								}
								else {
									if(json.msg.promotion_id_error != '') {
										message = json.msg.promotion_id_error;
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
					promotion_id: {
						required: true
					},
				},
				messages: {
					promotion_id: {
						required: "<?php echo $this->lang->line('error_enter_promotion_name');?>",
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
