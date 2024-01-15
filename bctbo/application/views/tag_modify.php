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
							<?php echo form_open('player/tag_modify_update', array('id' => 'tag_modify-form', 'name' => 'tag_modify-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_tag_code');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-12" id="tag_id" name="tag_id">
												<option value=""><?php echo $this->lang->line('error_select_tag');?></option>
												<?php 
												if(isset($tag_list) && sizeof($tag_list)>0){
													foreach($tag_list as $tag_list_row){
														if($tag_list_row['active'] == STATUS_ACTIVE){
															if($tag_list_row['tag_id'] == $tag_id){
																echo '<option value="' . $tag_list_row['tag_id'] . '" selected>' . $tag_list_row['tag_code'] . '</option>';
															}else{
																echo '<option value="' . $tag_list_row['tag_id'] . '">' . $tag_list_row['tag_code'] . '</option>';
															}
														}
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" style="display:none;">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_maintain_membership_limit');?></label>
										<div class="col-7">
											<input type="number" class="form-control" id="tag_times" name="tag_times" value="<?php echo (isset($tag_times) ? $tag_times : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="tag_force" class="col-5 col-form-label"><?php echo $this->lang->line('label_force_change');?></label>
										<div class="col-7">
											<input type="checkbox" id="tag_force" name="tag_force" value="1" <?php echo ((isset($tag_force) && $tag_force == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
								</div>
								<div class="card-footer row">
									<div class="col-5">
										<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
									</div>							
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
			var form = $('#tag_modify-form');

			var index = parent.layer.getFrameIndex(window.name);

			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});

			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
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
									parent.$('#uc21_' + json.response.id).html(json.response.tags_text);
									parent.layer.close(index);
								}
								else {
									if(json.msg.tag_id_error != ''){
										message = json.msg.tag_id_error;
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
				},
				messages: {
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