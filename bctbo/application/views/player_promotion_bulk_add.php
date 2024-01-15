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
							<?php echo form_open_multipart('playerpromotion/bulk_promotion_submit', array('id' => 'promotion_submit-form', 'name' => 'promotion_submit-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="promotion_apply_genre" class="col-3 col-form-label"><?php echo $this->lang->line('label_genre');?></label>
										<div class="col-9">
											<select class="form-control select2bs4" id="promotion_apply_genre" name="promotion_apply_genre">
												<?php
													$get_promotion_genre_data = get_promotion_genre();
													if(!empty($get_promotion_genre_data) && sizeof($get_promotion_genre_data)){
														foreach($get_promotion_genre_data as $k => $v)
														{
															if($k == MESSAGE_GENRE_INDIVIDUAL){
																echo '<option value="' . $k . '" selected>' . $this->lang->line($v) . '</option>';
															}else{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" id="div_agent" style="display:none;">
										<label for="agent" class="col-3 col-form-label"><?php echo $this->lang->line('label_agent');?></label>
										<div class="col-9">
											<select class="form-control select2bs4 col-12" id="agent" name="agent">
												
											</select>
										</div>
									</div>
									<div class="form-group row" id="div_individual">
										<label for="username" class="col-3 col-form-label"><?php echo $this->lang->line('label_player_username');?><br>(<?php echo $this->lang->line('label_seperate_by_hashtag');?>)</label>
										<div class="col-9">
											<textarea class="form-control" id="username" name="username" rows="10"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="promotion_content" class="col-3 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
										<div class="col-9">
											<select class="form-control select2bs4 col-12" id="promotion_id" name="promotion_id">
												<option value=""><?php echo $this->lang->line('place_holder_select_promotion');?></option>
												<?php
													if(isset($promotion_list)){
														if(sizeof($promotion_list)>0){
															foreach($promotion_list as $row){
																echo '<option value="' . $row['promotion_id'] . '">' . $row['promotion_name'] . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row" id="player_promotion_reward_amount">
										<label for="reward_amount" class="col-3 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>
										<div class="col-9">
											<input type="text" class="form-control" id="reward_amount" name="reward_amount" value="">
										</div>
									</div>
									<div class="form-group row" id="player_promotion_archieve_amount">
										<label for="achieve_amount" class="col-3 col-form-label"><?php echo $this->lang->line('label_archieve_amount');?></label>
										<div class="col-9">
											<input type="text" class="form-control" id="achieve_amount" name="achieve_amount" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="remark" class="col-3 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
										<div class="col-9">
											<textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
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
			var form = $('#promotion_submit-form');
			call_user_data();
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
									parent.$('#playerpromotion-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.promotion_id_error != '') {
										message = json.msg.promotion_id_error;
									}
									else if(json.msg.achieve_amount_error != '') {
										message = json.msg.achieve_amount_error;
									}
									else if(json.msg.reward_amount_error != '') {
										message = json.msg.reward_amount_error;
									}
									else if(json.msg.agent_error != '') {
										message = json.msg.agent_error;
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
					promotion_apply_genre: {
						required: true,
					},
					promotion_id: {
						required: true,
					},
					achieve_amount: {
						required: true,
						number: true
					},
					reward_amount: {
						required: true,
						number: true
					},
				},
				messages: {
					promotion_apply_genre: {
						required: "<?php echo $this->lang->line('error_enter_promotion_name');?>",
					},
					promotion_id: {
						required: "<?php echo $this->lang->line('error_enter_promotion_name');?>",
					},
					achieve_amount: {
						required: "<?php echo $this->lang->line('error_invalid_points');?>",
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					reward_amount: {
						required: "<?php echo $this->lang->line('error_invalid_points');?>",
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
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

			$("#promotion_apply_genre").on('change', function () {
				$("#div_agent").hide();
				$("#div_individual").hide();
				var promotion_apply_genre = this.value;
				if(promotion_apply_genre == <?php echo MESSAGE_GENRE_INDIVIDUAL?>){
					$("#div_individual").show();
				}else if(promotion_apply_genre == <?php echo MESSAGE_GENRE_USER_ALL?>){
					$("#div_agent").show();
				}
			});
		});


		function call_user_data(){
			$.ajax({url: '<?php echo base_url("user/get_all_user_data");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						var userData = json.result;
						for (i = 0; i < json.response.length; i++) {
							$("#agent").append($('<option></option>').val(json.response[i]['username']).html(json.response[i]['username']));
						}
						$("#agent").val('');
					}
				},
				error: function (request,error){
				}
			});
		}
	</script>
</body>
</html>
