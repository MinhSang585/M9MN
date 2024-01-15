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
				<?php echo form_open('tag/player_bulk_modify_update', array('id' => 'tag-form', 'name' => 'tag-form', 'class' => 'form-horizontal'));?>
				<div class="row">
				<!-- form start -->
					<!-- left column -->
					<div class="col-6">
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title"><?php echo $this->lang->line('label_player');?></h3>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<label for="agent" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="type" name="type">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
											<option value="1"><?php echo $this->lang->line('label_agent');?></option>
											<option value="2"><?php echo $this->lang->line('label_bank_group');?></option>
											<option value="3"><?php echo $this->lang->line('label_tag_code');?></option>
											<option value="4"><?php echo $this->lang->line('label_tag_code_player');?></option>
											<option value="5"><?php echo $this->lang->line('label_level');?></option>
											<option value="6"><?php echo $this->lang->line('label_player_username');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_1" style="display: none;">
									<label for="agent" class="col-5 col-form-label"><?php echo $this->lang->line('label_agent');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="agent" name="agent">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_2" style="display: none;">
									<label for="bank_group" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_group');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="bank_group" name="bank_group">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_3" style="display: none;">
									<label for="tag" class="col-5 col-form-label"><?php echo $this->lang->line('label_tag_code');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="tag" name="tag">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_4" style="display: none;">
									<label for="tag_player" class="col-5 col-form-label"><?php echo $this->lang->line('label_tag_code_player');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="tag_player" name="tag_player">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_5" style="display: none;">
									<label for="level_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_level');?></label>
									<div class="col-7">
										<select class="form-control select2bs4 col-12" id="level_id" name="level_id">
											<option value=""><?php echo $this->lang->line('label_select');?></option>
										</select>
									</div>
								</div>
								<div class="form-group row type_selection_class" id="type_selection_6" style="display: none;">
									<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_player_username');?><br>(<?php echo $this->lang->line('label_seperate_by_hashtag');?>)</label>
									<div class="col-7">
										<textarea class="form-control" id="username" name="username" rows="10"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card card-info">
							<div class="card-header">
								<h3 class="card-title"><?php echo $this->lang->line('label_field');?></h3>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_1" name="modify_option_1" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_1" class="custom-control-label"></label></div>
									<label for="active" class="col-4 col-form-label"><?php echo $this->lang->line('label_status');?></label>
									<div class="col-7">
										<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_2" name="modify_option_2" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_2" class="custom-control-label"></label></div>
									<label for="is_offline_deposit" class="col-4 col-form-label"><?php echo $this->lang->line('deposit_offline_banking');?></label>
									<div class="col-7">
										<input type="checkbox" id="is_offline_deposit" name="is_offline_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_3" name="modify_option_3" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_3" class="custom-control-label"></label></div>
									<label for="bank_group_id" class="col-4 col-form-label"><?php echo $this->lang->line('label_bank_group');?></label>
									<div class="col-7">
										<select class="select2 col-12" id="bank_group_id" name="bank_group_id[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_4" name="modify_option_4" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_4" class="custom-control-label"></label></div>
									<label for="is_online_deposit" class="col-4 col-form-label"><?php echo $this->lang->line('deposit_online_banking');?></label>
									<div class="col-7">
										<input type="checkbox" id="is_online_deposit" name="is_online_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_5" name="modify_option_5" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_5" class="custom-control-label"></label></div>
									<label for="game_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_online_banking');?>)</label>
									<div class="col-7">
										<select class="select2 col-12" id="online_deposit_channel" name="online_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
											<?php
												$online_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_ONLINE_BANKING);
												if(!empty($online_deposit_channel_data)){
													foreach ($online_deposit_channel_data as $online_deposit_channel_key => $online_deposit_channel_value){
														echo '<option value="' . $online_deposit_channel_key . '">' . $this->lang->line($online_deposit_channel_value) . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_6" name="modify_option_6" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_6" class="custom-control-label"></label></div>
									<label for="is_credit_card_deposit" class="col-4 col-form-label"><?php echo $this->lang->line('deposit_credit_card');?></label>
									<div class="col-7">
										<input type="checkbox" id="is_credit_card_deposit" name="is_credit_card_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_7" name="modify_option_7" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_7" class="custom-control-label"></label></div>
									<label for="game_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_credit_card');?>)</label>
									<div class="col-7">
										<select class="select2 col-12" id="credit_card_deposit_channel" name="credit_card_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
											<?php
												$credit_card_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_CREDIT_CARD);
												if(!empty($credit_card_deposit_channel_data)){
													foreach ($credit_card_deposit_channel_data as $credit_card_deposit_channel_key => $credit_card_deposit_channel_value){
														echo '<option value="' . $credit_card_deposit_channel_key . '">' . $this->lang->line($credit_card_deposit_channel_value) . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_8" name="modify_option_8" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_8" class="custom-control-label"></label></div>
									<label for="is_hypermart_deposit" class="col-4 col-form-label"><?php echo $this->lang->line('deposit_hypermart');?></label>
									<div class="col-7">
										<input type="checkbox" id="is_hypermart_deposit" name="is_hypermart_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<div class="custom-control custom-checkbox col-1"><input class="custom-control-input" type="checkbox" id="modify_option_9" name="modify_option_9" value="<?php echo STATUS_ACTIVE?>"><label for="modify_option_9" class="custom-control-label"></label></div>
									<label for="game_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?> (<?php echo $this->lang->line('deposit_hypermart');?>)</label>
									<div class="col-7">
										<select class="select2 col-12" id="hypermart_deposit_channel" name="hypermart_deposit_channel[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
											<?php
												$hypermart_deposit_channel_data = get_payment_gateway_code_by_channel(DEPOSIT_HYPERMART);
												if(!empty($hypermart_deposit_channel_data)){
													foreach ($hypermart_deposit_channel_data as $hypermart_deposit_channel_key => $hypermart_deposit_channel_value){
														echo '<option value="' . $hypermart_deposit_channel_key . '">' . $this->lang->line($hypermart_deposit_channel_value) . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
							</div>
							<!-- /.card-footer -->
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
				<!-- /.row -->
				</div>
				<?php echo form_close();?>
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$("#type").on('change', function () {
			$(".type_selection_class").hide();
			var type = this.value;
			$("#type_selection_"+type).show();
		})

		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#tag-form');
			$('.select2').select2();
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
								console.log(json);
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
									if(json.msg.type_error != '') {
										message = json.msg.type_error;
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
					type:{
						required: true	
					},
				},
				messages: {
					type:{
						required: "<?php echo $this->lang->line('error_select_type');?>",
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

			call_user_data();
			call_tag_data();
			call_tag_player_data();
			call_bank_group_data();
			call_level_data();
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

		function call_tag_data(){
			$.ajax({url: '<?php echo base_url("tag/get_all_tag_data");?>',
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
							$("#tag").append($('<option></option>').val(json.response[i]['tag_id']).html(json.response[i]['tag_code']));
						}
						$("#tag").val('');
					}
				},
				error: function (request,error){
				}
			});
		}

		function call_tag_player_data(){
			$.ajax({url: '<?php echo base_url("tag/get_all_tag_player_data");?>',
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
							$("#tag_player").append($('<option></option>').val(json.response[i]['tag_player_id']).html(json.response[i]['tag_player_code']));
						}
						$("#tag_player").val('');
					}
				},
				error: function (request,error){
				}
			});
		}

		function call_bank_group_data(){
			$.ajax({url: '<?php echo base_url("group/get_all_group_data/".GROUP_BANK);?>',
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
							$("#bank_group_id").append($('<option></option>').val(json.response[i]['group_id']).html(json.response[i]['group_name']));
							$("#bank_group").append($('<option></option>').val(json.response[i]['group_id']).html(json.response[i]['group_name']));
						}
						$("#bank_group_id").val('');
						$("#bank_group").val('');
					}
				},
				error: function (request,error){
				}
			});
		}

		function call_level_data(){
			$.ajax({url: '<?php echo base_url("level/get_all_level_data");?>',
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
					console.log(json);
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						var userData = json.result;
						for (i = 0; i < json.response.length; i++) {
							$("#level_id").append($('<option></option>').val(json.response[i]['level_index']).html(json.response[i]['level_name']));
						}
						$("#level_id").val('');
					}
				},
				error: function (request,error){
				}
			});
		}

		
	</script>
</body>
</html>
