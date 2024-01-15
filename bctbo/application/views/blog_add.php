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
							<?php echo form_open('blog/submit', array('id' => 'blog-form', 'name' => 'blog-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row mt-3">
										<label for="blog_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_name'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="blog_name" name="blog_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_blog_display');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="blog_display" name="blog_display">
												<?php
													foreach(get_blog_display() as $k => $v)
													{
														echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" id="blog_category_id_div">
										<label for="currency_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_blog_category');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="blog_category_id" name="blog_category_id">
												<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
												<?php
													if(isset($blog_category) && sizeof($blog_category)>0){
														for($i=0;$i<sizeof($blog_category);$i++)
														{
															echo '<option value="' . $blog_category[$i]['blog_category_id'] . '">' . $blog_category[$i]['blog_category_name'] . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" id="page_category_id_div" style="display:none;">
										<label for="currency_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_blog_category');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="page_category_id" name="page_category_id">
												<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
												<?php
													foreach(get_blog_page() as $k => $v)
													{
														echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" id="product_category_id_div" style="display:none;">
										<label for="currency_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_blog_category');?></label>
										<div class="col-10">
											<select class="form-control select2bs4" id="product_category_id" name="product_category_id">
												<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
												<?php
													foreach(get_blog_product() as $k => $v)
													{
														echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="blog_pathname" class="col-2 col-form-label"><?php echo $this->lang->line('label_blog_pathname');?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="blog_pathname" name="blog_pathname" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_header');?></label>
										<div class="col-10">
											<textarea class="form-control" id="seo_header" name="seo_header" rows="3"></textarea>
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="seo_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_title'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_title" name="seo_title" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_og_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_og_title'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_og_title" name="seo_og_title" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_twitter_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_twitter_title'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_twitter_title" name="seo_twitter_title" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_meta_keywords" class="col-2 col-form-label"><?php echo $this->lang->line('label_meta_keywords'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_meta_keywords" name="seo_meta_keywords" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_og_keywords" class="col-2 col-form-label"><?php echo $this->lang->line('label_og_keywords'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_og_keywords" name="seo_og_keywords" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_twitter_keywords" class="col-2 col-form-label"><?php echo $this->lang->line('label_twitter_keywords'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_twitter_keywords" name="seo_twitter_keywords" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_description" class="col-2 col-form-label"><?php echo $this->lang->line('label_description'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_description" name="seo_description" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_og_description" class="col-2 col-form-label"><?php echo $this->lang->line('label_og_description'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_og_description" name="seo_og_description" value="">
										</div>
									</div>
									<div class="form-group row mt-3">
										<label for="seo_twitter_description" class="col-2 col-form-label"><?php echo $this->lang->line('label_twitter_description'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_twitter_description" name="seo_twitter_description" value="">
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="logo_image" class="col-2 col-form-label"><?php echo $this->lang->line('label_logo_image');?></label>
										<div class="col-8">
											<div class="custom-file col-12">
												<input type="text" class="form-control" id="ckfinder-input-1" name="logo_image">
											</div>
										</div>
										<div class="col-2">
											<button type="button" id="ckfinder-popup-1" class="btn btn-info"><?php echo $this->lang->line('button_choose_file');?></button>
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="og_image" class="col-2 col-form-label"><?php echo $this->lang->line('label_og_image');?></label>
										<div class="col-8">
											<div class="custom-file col-12">
												<input type="text" class="form-control" id="ckfinder-input-2" name="og_image">
											</div>
										</div>
										<div class="col-2">
											<button type="button" id="ckfinder-popup-2" class="btn btn-info"><?php echo $this->lang->line('button_choose_file');?></button>
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="twitter_image" class="col-2 col-form-label"><?php echo $this->lang->line('label_twitter_image');?></label>
										<div class="col-8">
											<div class="custom-file col-12">
												<input type="text" class="form-control" id="ckfinder-input-3" name="twitter_image">
											</div>
										</div>
										<div class="col-2">
											<button type="button" id="ckfinder-popup-3" class="btn btn-info"><?php echo $this->lang->line('button_choose_file');?></button>
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="seo_og_url" class="col-2 col-form-label"><?php echo $this->lang->line('label_og_url');?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_og_url" name="seo_og_url" value="">
										</div>
									</div>

									<div class="form-group row mt-3">
										<label for="seo_canonical" class="col-2 col-form-label"><?php echo $this->lang->line('label_canonical'); ?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="seo_canonical" name="seo_canonical" value="">
										</div>
									</div>
									<div class="form-group row" style="display:none;">
										<label for="blog_sequence" class="col-2 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
										<div class="col-10">
											<input type="number" class="form-control col-3" id="blog_sequence" name="blog_sequence" value="" maxlength="3">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_is_top');?></label>
										<div class="col-10">
											<input type="checkbox" id="is_top" name="is_top" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="domain" class="col-2 col-form-label"><?php echo $this->lang->line('label_domain');?></label>
										<div class="col-10">
											<textarea class="form-control" id="domain" name="domain" rows="3"><?php echo SYSTEM_DEFAULT_DOMAIN;?></textarea>
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
													$content_html .= '</ul>';
													$content_html .= '<div class="tab-content" id="custom-content-below-'.$v.'-tabContent">';
														$content_html .= '<div class="tab-pane fade show active" id="custom-content-below-web-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-web-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_title') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control" id="blog_web_title_' . $v . '" name="blog_web_title_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_web_title']) ? $blog_lang[$v]['blog_web_title'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_sub_title') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control" id="blog_web_sub_title_' . $v . '" name="blog_web_sub_title_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_web_sub_title']) ? $blog_lang[$v]['blog_web_sub_title'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_content') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control ckeditor_cust" id="blog_web_content_' . $v . '" name="blog_web_content_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_web_content']) ? $blog_lang[$v]['blog_web_content'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
														$content_html .= '</div>';
														$content_html .= '<div class="tab-pane fade" id="custom-content-below-mobile-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-mobile-'.$v.'-tab">';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_title') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control" id="blog_mobile_title_' . $v . '" name="blog_mobile_title_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_mobile_title']) ? $blog_lang[$v]['blog_mobile_title'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
															$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_sub_title') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control" id="blog_mobile_sub_title_' . $v . '" name="blog_mobile_sub_title_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_mobile_sub_title']) ? $blog_lang[$v]['blog_mobile_sub_title'] : '').'</textarea>';
																$content_html .= '</div>';
															$content_html .= '</div>';
																$content_html .= '<div class="form-group row mt-3">';
																$content_html .= '<label for="blog_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_blog_content') . '</label>';
																$content_html .= '<div class="col-10">';
																	$content_html .= '<textarea class="form-control ckeditor_cust" id="blog_mobile_content_' . $v . '" name="blog_mobile_content_' . $v . '" rows="4">'.(isset($blog_lang[$v]['blog_mobile_content']) ? $blog_lang[$v]['blog_mobile_content'] : '').'</textarea>';
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

		$("#blog_display").on('change', function () {
			$("#blog_category_id_div").hide();
			$("#page_category_id_div").hide();
			$("#product_category_id_div").hide();

			var blog_display = this.value;
			if(blog_display == <?php echo BLOG_DISPLAY_BLOG?>){
				$("#blog_category_id_div").show();
			}else if(blog_display == <?php echo BLOG_DISPLAY_PAGE?>){
				$("#page_category_id_div").show();
			}else{
				$("#product_category_id_div").show();
			}
		});

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
			var button1 = document.getElementById('ckfinder-popup-1');
			button1.onclick = function() {
				selectFileWithCKFinder('ckfinder-input-1');
			};

			var button2 = document.getElementById('ckfinder-popup-2');
			button2.onclick = function() {
				selectFileWithCKFinder('ckfinder-input-2');
			};

			var button3 = document.getElementById('ckfinder-popup-3');
			button3.onclick = function() {
				selectFileWithCKFinder('ckfinder-input-3');
			};


			var is_allowed = true;
			var form = $('#blog-form');
			
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
					 $('textarea.ckeditor_cust').each(function () {
                       var $textarea = $(this);
                       $textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
                    });
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
									parent.$('#blog-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.blog_category_id_error != '') {
										message = json.msg.blog_category_id_error;
									}
									else if(json.msg.blog_name_error != '') {
										message = json.msg.blog_name_error;
									}
									else if(json.msg.blog_pathname_error != '') {
										message = json.msg.blog_pathname_error;
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
					/*
					blog_category_id: {
						required: true
					},
					*/
					/*
					blog_sequence: {
						required: true,
						digits: true
					},
					*/
				},
				messages: {
					/*
					blog_category_id: {
						required: "<?php echo $this->lang->line('error_enter_blog_type');?>",
					},
					*/
					/*
					blog_sequence: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					*/
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
	<script>
		function ImagesURL(url,alt){
			window.parent.CKEDITOR.tools.callFunction(1, url, alt);
			$('.cke_dialog_image_url .cke_dialog_ui_hbox .cke_dialog_ui_hbox_first .cke_dialog_ui_text .cke_dialog_ui_labeled_content .cke_dialog_ui_input_text .cke_dialog_ui_input_text').val(url);
		}

		function selectFileWithCKFinder( elementId ) {
			CKFinder.popup( {
				chooseFiles: true,
				width: 800,
				height: 600,
				onInit: function( finder ) {
					finder.on( 'files:choose', function( evt ) {
						var file = evt.data.files.first();
						var output = document.getElementById( elementId );
						output.value = file.getUrl();
					} );

					finder.on( 'file:choose:resizedImage', function( evt ) {
						var output = document.getElementById( elementId );
						output.value = evt.data.resizedUrl;
					} );
				}
			} );
		}
	</script>
</body>
</html>
