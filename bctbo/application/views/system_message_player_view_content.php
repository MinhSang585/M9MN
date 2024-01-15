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
							<?php echo form_open('message/player_update', array('id' => 'message_player-form', 'name' => 'message_player-form', 'class' => 'form-horizontal'));?>
							<div class="card-body">
								<div class="form-group row">
									<label for="system_message_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_message_name');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($message_data['system_message_name']) ? $message_data['system_message_name'] : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="username" class="col-2 col-form-label"><?php echo $this->lang->line('label_username');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="is_read" class="col-2 col-form-label"><?php echo $this->lang->line('label_read');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($is_read) ? $this->lang->line(get_message_read_status($is_read)) : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_status');?></label>
									<div class="col-10">
										<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<label for="created_by" class="col-2 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($created_by)) ? $created_by : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="created_date" class="col-2 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="updated_by" class="col-2 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($updated_by)) ? $updated_by : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="updated_date" class="col-2 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (($updated_date > 0) ? date('Y-m-d H:i:s', $updated_date) : '-');?></label>
									</div>
								</div>
								<?php if($system_message_id != 15){ ?>
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
													$content_html .= '<label for="message_title_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_message_title') . '</label>';
													$content_html .= '<div class="col-10">';
														$content_html .= (isset($message_lang[$v]['system_message_title']) ? $message_lang[$v]['system_message_title'] : '');
													$content_html .= '</div>';
												$content_html .= '</div>';
												$content_html .= '<div class="form-group row mt-3">';
													$content_html .= '<label for="message_content_' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
													$content_html .= '<div class="col-10">';
														$content_html .= (isset($message_lang[$v]['system_message_content']) ? $message_lang[$v]['system_message_content'] : '');
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
								<?php } ?>
							</div>

							<!-- /.card-body -->
							<div class="card-footer">
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
							</div>
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
			var form = $('#message_player-form');

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
		});
	</script>
</body>
</html>
