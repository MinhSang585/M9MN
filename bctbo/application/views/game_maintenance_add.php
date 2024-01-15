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
							<?php echo form_open('game/maintenance_submit', array('id' => 'game-form', 'name' => 'game-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_name');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-10" id="game_code" name="game_code">
												<option value=""><?php echo $this->lang->line('place_holder_game_name');?></option>
												<?php
													if(isset($game)){
														if(sizeof($game)>0){
															foreach($game as $row){
																echo '<option value="' . $row['game_id'] . '">' . (isset($row['game_name']) ? $this->lang->line($row['game_name']) : '') . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="game_sequence" class="col-5 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-3" id="game_sequence" name="game_sequence" value="" maxlength="3">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_maintenance" class="col-5 col-form-label"><?php echo $this->lang->line('label_maintenance');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_maintenance" name="is_maintenance" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_front_end_display" class="col-5 col-form-label"><?php echo $this->lang->line('label_frontend_display');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_front_end_display" name="is_front_end_display" value="1"data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="fixed_maintenance" class="col-5 col-form-label"><?php echo $this->lang->line('label_fixed_maintenance');?></label>
										<div class="col-7">
											<input type="checkbox" id="fixed_maintenance" name="fixed_maintenance" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="fixed_day" class="col-5 col-form-label"><?php echo $this->lang->line('label_day');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-6" id="fixed_day" name="fixed_day">
												<?php
													foreach(get_day() as $k => $v) 
													{
														if(isset($fixed_day)) {
															if($k == $fixed_day) {
																echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
															}
															else {
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
														else {
															echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="fixed_from_time" class="col-5 col-form-label"><?php echo $this->lang->line('label_from_time');?></label>
										<div class="col-7">
											<div class="input-group date" id="fixed_from_time_click" data-target-input="nearest">
												<input type="text" id="fixed_from_time" name="fixed_from_time" class="form-control col-6 datetimepicker-input" value="00:00:00" data-target="#fixed_from_time_click"/>
												<div class="input-group-append" data-target="#fixed_from_time_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-clock"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="fixed_to_time" class="col-5 col-form-label"><?php echo $this->lang->line('label_to_time');?></label>
										<div class="col-7">
											<div class="input-group date" id="fixed_to_time_click" data-target-input="nearest">
												<input type="text" id="fixed_to_time" name="fixed_to_time" class="form-control col-6 datetimepicker-input" value="00:00:00" data-target="#fixed_to_time_click"/>
												<div class="input-group-append" data-target="#fixed_to_time_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-clock"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="urgent_maintenance" class="col-5 col-form-label"><?php echo $this->lang->line('label_urgent_maintenance');?></label>
										<div class="col-7">
											<input type="checkbox" id="urgent_maintenance" name="urgent_maintenance" value="1"  data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="urgent_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_date');?></label>
										<div class="col-7">
											<div class="input-group date" id="urgent_date_click" data-target-input="nearest">
												<input type="text" id="urgent_date" name="urgent_date" class="form-control col-6 datetimepicker-input" value="" data-target="#urgent_date_click"/>
												<div class="input-group-append" data-target="#urgent_date_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
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
			var form = $('#game-form');
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			$('#fixed_from_time_click').datetimepicker({
				format: 'HH:mm:ss'
			});
			
			$('#fixed_to_time_click').datetimepicker({
				format: 'HH:mm:ss'
			});
			
			$('#urgent_date_click').datetimepicker({
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
									parent.$('#game-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.game_sequence_error != '') {
										message = json.msg.game_sequence_error;
									}
									else if(json.msg.fixed_day_error != '') {
										message = json.msg.fixed_day_error;
									}
									else if(json.msg.fixed_from_time_error != '') {
										message = json.msg.fixed_from_time_error;
									}
									else if(json.msg.fixed_to_time_error != '') {
										message = json.msg.fixed_to_time_error;
									}
									else if(json.msg.urgent_date_error != '') {
										message = json.msg.urgent_date_error;
									}
									else if(json.msg.game_code_error != '') {
										message = json.msg.game_code_error;
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
					game_sequence: {
						required: true,
						digits: true
					},
					fixed_day: {
						digits: true
					},
					game_code: {
						required: true,
					},
				},
				messages: {
					game_sequence: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					fixed_day: {
						digits: "<?php echo $this->lang->line('error_select_fixed_day');?>",
					},
					game_code: {
						digits: "<?php echo $this->lang->line('error_select_game_name');?>",
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
