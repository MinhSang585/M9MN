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
							<?php echo form_open('announcement/submit', array('id' => 'announcement-form', 'name' => 'announcement-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="content" class="col-5 col-form-label"><?php echo $this->lang->line('label_content');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="content" name="content" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="start_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_start_date');?></label>
										<div class="col-7">
											<div class="input-group date" id="start_date_click" data-target-input="nearest">
												<input type="text" id="start_date" name="start_date" class="form-control col-6 datetimepicker-input" value="" data-target="#start_date_click"/>
												<div class="input-group-append" data-target="#start_date_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="end_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_end_date');?></label>
										<div class="col-7">
											<div class="input-group date" id="end_date_click" data-target-input="nearest">
												<input type="text" id="end_date" name="end_date" class="form-control col-6 datetimepicker-input" value="" data-target="#end_date_click"/>
												<div class="input-group-append" data-target="#end_date_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
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
												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-' . $v . '" role="tab" aria-controls="custom-content-below-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');
												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-' . $v . '-tab">';
												$content_html .= '<div class="form-group row mt-3">';
												$content_html .= '<label for="announcement_name-' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_content') . '</label>';
												$content_html .= '<div class="col-7">';
												$content_html .= '<textarea rows="6" class="form-control col-12" id="announcement_name-' . $v . '" name="announcement_name-' . $v . '" ></textarea>';
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
			var form = $('#announcement-form');
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			$('#start_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#end_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm',
                icons: {
                    time: "fa fa-clock"
                }
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
									parent.$('#announcement-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.content_error != '') {
										message = json.msg.content_error;
									}
									else if(json.msg.start_date_error != '') {
										message = json.msg.start_date_error;
									}
									else if(json.msg.end_date_error != '') {
										message = json.msg.end_date_error;
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
					content: {
						required: true
					}
				},
				messages: {
					content: {
						required: "<?php echo $this->lang->line('error_enter_content');?>",
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
