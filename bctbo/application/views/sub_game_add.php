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
							<?php echo form_open('game/sub_game_submit', array('id' => 'sub_game-form', 'name' => 'sub_game-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="game_provider_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_provider');?></label>
										<div class="col-7">
											<select class="form-control form-control-sm select2bs4 col-12" id="game_provider_code" name="game_provider_code">
												<option value=""><?php echo $this->lang->line('place_holder_game_provider');?></option>
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
										<label for="game_type_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_type');?></label>
										<div class="col-7">
											<select class="form-control form-control-sm select2bs4 col-12" id="game_type_code" name="game_type_code">
												<option value=""><?php echo $this->lang->line('place_holder_game_type');?></option>
												<?php
													foreach(get_game_type() as $k => $v)
													{
														echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="game_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_code');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="game_code" name="game_code" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="game_sequence" class="col-5 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-3" id="game_sequence" name="game_sequence" value="" maxlength="3">
										</div>
									</div>

									<div class="form-group row">
										<label for="game_name_en" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_name');?> - <?php echo $this->lang->line('lang_en');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="game_name_en" name="game_name_en" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="game_picture_en" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_image');?> - <?php echo $this->lang->line('lang_en');?></label>
										<div class="col-7">
											<div class="custom-file col-10">
												<input type="file" class="custom-file-input" id="game_picture_en" name="game_picture_en">
												<label class="custom-file-label" for="game_picture_en"><?php echo $this->lang->line('button_choose_file');?></label>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="game_type_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_name');?> - <?php echo $this->lang->line('lang_zh_cn');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="game_name_chs" name="game_name_chs" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="game_picture_chs" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_image');?> - <?php echo $this->lang->line('lang_zh_cn');?></label>
										<div class="col-7">
											<div class="custom-file col-10">
												<input type="file" class="custom-file-input" id="game_picture_chs" name="game_picture_chs">
												<label class="custom-file-label" for="game_picture_chs"><?php echo $this->lang->line('button_choose_file');?></label>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="game_type_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_name');?> - <?php echo $this->lang->line('lang_zh_hk');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="game_name_cht" name="game_name_cht" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="game_picture_cht" class="col-5 col-form-label"><?php echo $this->lang->line('label_game_image');?> - <?php echo $this->lang->line('lang_zh_hk');?></label>
										<div class="col-7">
											<div class="custom-file col-10">
												<input type="file" class="custom-file-input" id="game_picture_cht" name="game_picture_cht">
												<label class="custom-file-label" for="game_picture_cht"><?php echo $this->lang->line('button_choose_file');?></label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_mobile');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_mobile" name="is_mobile" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_progressive" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_progressive');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_progressive" name="is_progressive" value="1"  data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_hot" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_hot');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_hot" name="is_hot" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<div class="form-group row">
										<label for="is_new" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_new');?></label>
										<div class="col-7">
											<input type="checkbox" id="is_new" name="is_new" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
			var form = $('#sub_game-form');
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});

            // bsCustomFileInput.init();
			
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
									parent.$('#sub_game-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.game_code_error != ''){
										message = json.msg.game_code_error;
									}
									else if(json.msg.game_type_code_error != '') {
										message = json.msg.game_type_code_error;
									}
									else if(json.msg.game_provider_code_error != '') {
										message = json.msg.game_provider_code_error;
									}
									else if(json.msg.game_sequence_error != ''){
										message = json.msg.game_sequence_error;
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
					game_code: {
						required: true,
					},
					game_type_code: {
						required: true,
					},
					game_provider_code: {
						required: true,
					},
				},
				messages: {
					game_sequence: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					game_code: {
						required: "<?php echo $this->lang->line('error_select_game_code');?>",
					},
					game_type_code: {
						required: "<?php echo $this->lang->line('error_select_game_type');?>",
					},
					game_provider_code: {
						required: "<?php echo $this->lang->line('error_select_game_provider');?>",
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