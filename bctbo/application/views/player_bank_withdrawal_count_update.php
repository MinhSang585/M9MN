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
							<?php echo form_open('bank/player_bank_withdrawal_count_update', array('id' => 'bank_player-form', 'name' => 'bank_player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($username)) ? $username : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="player_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($player_bank_type)) ? $this->lang->line($player_bank_type) : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_data['bank_name'])) ? $bank_data['bank_name'] : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_account_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_name)) ? $this->lang->line($bank_account_name) : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_account_no" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_no)) ? $this->lang->line($bank_account_no) : '-');?></label>
										</div>
									</div>
									<div class="form-group row" style="display:none;">
										<label for="bank_account_address" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_address');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_address)) ? $this->lang->line($bank_account_address) : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="created_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (( ! empty($created_by)) ? $created_by : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="created_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="bank_account_address" class="col-5 col-form-label"><?php echo $this->lang->line('label_withdrawal_count');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="withdrawal_count" name="withdrawal_count" value="<?php echo (isset($withdrawal_count) ? $withdrawal_count : '0');?>">
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="player_bank_id" name="player_bank_id" value="<?php echo (isset($player_bank_id) ? $player_bank_id : '');?>">
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
									parent.$('#uc11_'+json.response.id).html(json.response.withdrawal_count);
								}
								else {
									if(json.msg.general_error != '') {
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
					player_bank_id: {
						required: true
					},
				},
				messages: {
					player_bank_id: {
						required: "<?php echo $this->lang->line('error_select_bank_name');?>",
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