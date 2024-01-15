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
							<?php echo form_open_multipart('content/update', array('id' => 'content-form', 'name' => 'content-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row mt-3">
										<label for="content_name" class="col-2 col-form-label"><?php echo $this->lang->line('label_content_title'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="content_name" name="content_name" value="<?php echo (isset($content['content_name']) ? $content['content_name'] : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="content_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_name');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="content_id" name="content_id">
												<?php
													$get_content_page = get_content_page();
													if(isset($get_content_page) && sizeof($get_content_page)>0){
														foreach($get_content_page as $k => $v)
														{
															if(isset($content['content_id']) && $content['content_id'] == $k){
																echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
															}else{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="domain" class="col-2 col-form-label"><?php echo $this->lang->line('label_domain');?></label>
										<div class="col-10">
											<?php 
												$domain_text = SYSTEM_DEFAULT_DOMAIN;
												if(!empty($content['domain'])){
													$arr = explode(',', $content['domain']);
													$arr = array_values(array_filter($arr));

													$domain_text = implode("##",$arr);
												}
											?>
											<textarea class="form-control" id="domain" name="domain" rows="3"><?php echo $domain_text;?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-10">
											<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($content['active']) && $content['active'] == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-' . $v . '" role="tab" aria-controls="custom-content-below-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');
												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-' . $v . '-tab">';
													$content_html .= '<ul class="nav nav-tabs" id="custom-content-below-'.$v.'-tab" role="tablist">';
														$content_html .= '<li class="nav-item">';
															$content_html .= '<a class="nav-link active" id="custom-content-below-web-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-web-'.$v.'" role="tab" aria-controls="custom-content-below-web-'.$v.'" aria-selected="true">'.$this->lang->line('label_web').'</a>';
														$content_html .= '</li>';
														$content_html .= '<li class="nav-item">';
															$content_html .= '<a class="nav-link" id="custom-content-below-mobile-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-mobile-'.$v.'" role="tab" aria-controls="custom-content-below-mobile-'.$v.'" aria-selected="true">'.$this->lang->line('label_mobile').'</a>';
														$content_html .= '</li>';
														$content_html .= '<li class="nav-item">';
															$content_html .= '<a class="nav-link" id="custom-content-below-hybrid-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-hybrid-'.$v.'" role="tab" aria-controls="custom-content-below-hybrid-'.$v.'" aria-selected="true">'.$this->lang->line('label_hybrid').'</a>';
														$content_html .= '</li>';
													$content_html .= '</ul>';
													$content_html .= '<div class="tab-content" id="custom-content-below-'.$v.'-tabContent">';
														$content_html .= '<div class="tab-pane fade show active" id="custom-content-below-web-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-web-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="content_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control ckeditor_cust" id="content_web_content_' . $v . '" name="content_web_content_' . $v . '" rows="4">'.(isset($content_lang[$v]['content_web_content']) ? $content_lang[$v]['content_web_content'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														$content_html .= '</div>';
														$content_html .= '<div class="tab-pane fade" id="custom-content-below-mobile-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-mobile-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="content_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control ckeditor_cust" id="content_mobile_content_' . $v . '" name="content_mobile_content_' . $v . '" rows="4">'.(isset($content_lang[$v]['content_mobile_content']) ? $content_lang[$v]['content_mobile_content'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														$content_html .= '</div>';
														$content_html .= '<div class="tab-pane fade" id="custom-content-below-hybrid-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-hybrid-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="content_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control ckeditor_cust" id="content_hybrid_content_' . $v . '" name="content_hybrid_content_' . $v . '" rows="4">'.(isset($content_lang[$v]['content_hybrid_content']) ? $content_lang[$v]['content_hybrid_content'] : '').'</textarea>';
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
									<input type="hidden" id="content_key_id" name="content_key_id" value="<?php echo (isset($content['content_key_id']) ? $content['content_key_id'] : '');?>">
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
		function preventClose()
		{
		  return "<?php echo $this->lang->line('label_confirm_to_proceed');?>";
		}
		window.onbeforeunload = preventClose;

		$(document).ready(function() {
			$(function () {
			    var height = $(window).height() * 0.7;
				$(".ckeditor_cust").each(function () {
		        	let id = $(this).attr('id');
		        	var editor = CKEDITOR.replace( this.id, {
						height: height,
						language: 'en',
						autoParagraph: false,
						fillEmptyBlocks: true,
						ignoreEmptyParagraph: true,
						tabIndex: 3,
						enterMode: CKEDITOR.ENTER_BR,
						removePlugins: 'forms',
						extraAllowedContent: '*[*]{*}(*)',
						entities: false, 
						htmlEncodeOutput : false,
						entities_latin : false,
						basicEntities : false,
						toolbarGroups: [
							{ name: 'document', groups: ['mode', 'document'] },
							{ name: 'clipboard', groups: ['clipboard', 'undo'] },
							{ name: 'editing', groups: ['find', 'selection', 'spellchecker'] },
							{ name: 'forms' },
							'/',
							{ name: 'basicstyles', groups: ['basicstyles'] },
							{ name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
							{ name: 'links' },
							{ name: 'insert' },
							'/',
							{ name: 'styles' },
							{ name: 'colors' },
							{ name: 'tools' },
							{ name: 'others' },
						],
					});
					CKFinder.setupCKEditor(editor);
				});
			});
		});
		
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#content-form');
			
			bsCustomFileInput.init();
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
				    $('textarea.ckeditor_cust').each(function () {
                       var $textarea = $(this);
                       $textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
                    });
					if(is_allowed == true) {
						is_allowed = false;
						
						var file_form = form[0];
						var formData = new FormData(file_form);
						/*
						$.each($("input[type='file']")[0].files, function(i, file) {
							formData.append('file', file);
						});
						*/
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
								
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
								}
								else {
									if(json.msg.content_id_error != '') {
										message = json.msg.content_id_error;
									}
									else if(json.msg.content_name_error != '') {
										message = json.msg.content_name_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					content_id: {
						required: true
					},
					content_name: {
						required: true,
					},
				},
				messages: {
					content_id: {
						required: "<?php echo $this->lang->line('error_enter_name');?>",
					},
					content_name: {
						required: "<?php echo $this->lang->line('error_enter_content_name');?>",
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
