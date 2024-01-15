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
							<?php echo form_open_multipart('bank/player_submit', array('id' => 'bank_player-form', 'name' => 'bank_player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
									</div>
									<div class="form-group row">
										<label for="player_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="player_bank_type" name="player_bank_type">
												<?php
													foreach(get_player_bank_type() as $k => $v)
													{
														echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="bank_id" name="bank_id">
												<?php
													for($i=0;$i<sizeof($bank_list);$i++)
													{
														echo '<option value="' . $bank_list[$i]['bank_id'] . '">' . $bank_list[$i]['bank_name'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_account_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="bank_account_name" name="bank_account_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_account_no" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="bank_account_no" name="bank_account_no" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
									<input type="hidden" id="username" name="username" value="<?php echo (isset($username) ? $username : '');?>">
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
			var form = $('#bank_player-form');
			
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
								var message = '';
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.layer.close(index);
									parent.$('#player_bank_account_data_json_' + json.response.player_id).html(json.response.player_bank_data);
									var player_bank_data = JSON.parse(json.response.player_bank_data);
									parent.$('.display_player_bank_account_'+ json.response.player_id).removeClass('badge-primary').addClass('badge-secondary');
									for (i = 0; i < player_bank_data.length; i++){
										parent.$('#display_player_bank_account_'+player_bank_data[i]['player_id']+'_'+(i+1)).removeClass('badge-secondary').addClass('badge-primary');
									}

									if(parent.$('#uc85_'+json.response.player_id).css('display') == 'block'){
										if(json.response.player_bank_type == "<?php echo BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK;?>"){
											parent.$('#uc85_'+json.response.player_id).hide();
											parent.$('#uc84_'+json.response.player_id).show();
											parent.document.getElementById('uc84_'+json.response.player_id).setAttribute("onClick", "updatePlayerBankData("+json.response.id+");");
											parent.$('#uc80_'+json.response.player_id).show();
											parent.$('#uc80_'+json.response.player_id).html(json.response.bank_name);
											parent.$('#uc82_'+json.response.player_id).show();
											parent.$('#uc82_'+json.response.player_id).html(json.response.bank_account_name);
											parent.$('#uc83_'+json.response.player_id).show();
											parent.$('#uc83_'+json.response.player_id).html(json.response.bank_account_no);
											if(json.response.verify == "<?php echo STATUS_VERIFY;?>"){
												parent.$('#uc81_'+json.response.player_id).show();
												parent.$('#uc81_'+json.response.player_id).html("<?php echo $this->lang->line('status_verify');?>");
												parent.$('#uc81_'+json.response.player_id).removeClass('bg-secondary').addClass('bg-success');
											}else{
												parent.$('#uc81_'+json.response.player_id).show();
												parent.$('#uc81_'+json.response.player_id).html("<?php echo $this->lang->line('status_unverify');?>");
												parent.$('#uc81_'+json.response.player_id).removeClass('bg-success').addClass('bg-secondary');
											}
										}
									}
								}
								else {
									if(json.msg.bank_id_error != '') {
										message = json.msg.bank_id_error;
									}
									else if(json.msg.bank_account_name_error != '') {
										message = json.msg.bank_account_name_error;
									}
									else if(json.msg.bank_account_no_error != '') {
										message = json.msg.bank_account_no_error;
									}
									else if(json.msg.username_error != '') {
										message = json.msg.daily_limit_error;
									}
									else if(json.msg.player_bank_type_error != '') {
										message = json.msg.player_bank_type_error;
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
					bank_id: {
						required: true
					},
					bank_account_name: {
						required: true
					},
					bank_account_no: {
						required: true,
						digits: true
					},
					username: {
						required: true,
					},
					player_bank_type: {
						required: true
					},
				},
				messages: {
					bank_id: {
						required: "<?php echo $this->lang->line('error_enter_bank_name');?>",
					},
					bank_account_name: {
						required: "<?php echo $this->lang->line('error_enter_bank_account_name');?>",
					},
					bank_account_no: {
						required: "<?php echo $this->lang->line('error_enter_bank_account_no');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_bank_account_no')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
					},
					player_bank_type: {
						required: "<?php echo $this->lang->line('error_select_type');?>",
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
