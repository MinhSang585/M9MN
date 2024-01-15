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
							<?php echo form_open('player/transfer_submit', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_point_remaining');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($points) ? number_format($points, 2, '.', ',') : '0.00');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="points" class="col-5 col-form-label">&nbsp;</label>
										<div class="col-3">
											<input type="text" class="form-control" id="points" name="points" value="<?php echo (isset($transfer_provider_balance) ? number_format($transfer_provider_balance, 2, '.', ',') : '0.00');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal"></label>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_point_remaining');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($transfer_provider_balance) ? number_format($transfer_provider_balance, 2, '.', ',') : '0.00');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4" id="type" name="type">
												<option value="<?php echo TRANSFER_TRANSACTION_IN;?>"><?php echo $this->lang->line('transfer_transaction_in')."(".$this->lang->line('label_game_wallet')." -> ".$this->lang->line('label_main_wallet').")";?></option>
												<option value="<?php echo TRANSFER_TRANSACTION_OUT;?>"><?php echo $this->lang->line('transfer_transaction_out')."(".$this->lang->line('label_main_wallet')." -> ".$this->lang->line('label_game_wallet').")";?></option>
											</select>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
									<input type="hidden" id="transfer_provider_code" name="transfer_provider_code" value="<?php echo (isset($transfer_provider_code) ? $transfer_provider_code : '');?>">
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
			var form = $('#player-form');
			
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
									message = json.msg.general_error;
									msg_icon = 1;
									var game_provider = "<?php echo (isset($transfer_provider_code) ? $transfer_provider_code : '');?>";
									var current_main_wallet = parseFloat(parent.$('#main_wallet_balance').html().replace(',', ''));
									var current_game_wallet = parseFloat(parent.$('.'+game_provider.toLowerCase()+'_sum').html().replace(',', ''));

									parent.$('#main_wallet_balance').html(parseFloat(json.balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
									parent.$('.'+game_provider.toLowerCase()+'_sum').html(parseFloat(json.game_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
									parent.layer.close(index);
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
					points: {
						required: true,
						number: true
					},
					type:{
						required: true	
					},
				},
				messages: {
					points: {
						required: "<?php echo $this->lang->line('error_invalid_points');?>",
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					type: {
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
