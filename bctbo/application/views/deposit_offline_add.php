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
							<?php echo form_open('deposit/deposit_offline_submit', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($upline) ? $upline : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_point_remaining');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($points) ? $points : '');?></label>
										</div>
									</div>
									<?php if(isset($currencies_data) && !empty($currencies_data)){ ?> 
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_currency');?></label>
										<div class="col-7">
											<select class="form-control form-control-sm select2bs4 col-12" id="currency_id" name="currency_id">
												<option value=""><?php echo $this->lang->line('place_holder_select_currencies');?></option>
												<?php foreach($currencies_data as $currencies_data_row){
													echo '<option value="'.$currencies_data_row['currency_id'].'">'.$currencies_data_row['currency_code'].'</option>';
												}?>
											</select>
										</div>
									</div>									
									<?php }?>
									<?php if(isset($promotion_data) && !empty($promotion_data)){ ?> 
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion');?></label>
										<div class="col-7">
											<select class="form-control form-control-sm select2bs4 col-12" id="promoId" name="promoId">
												<option value=""><?php echo $this->lang->line('place_holder_select_promotion');?></option>
												<?php foreach($promotion_data as $promotion_data_row){
													echo '<option value="'.$promotion_data_row['promotion_id'].'">'.$promotion_data_row['promotion_title'].'</option>';
												}?>
											</select>
										</div>
									</div>									
									<?php }?>

									<div class="form-group row">
										<label for="points" class="col-5 col-form-label">&nbsp;</label>
										<div class="col-3">
											<input type="text" class="form-control" id="points" name="points" value="">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['points']) ? $upline_data['points'] : '0');?></label>
									</div>
									<div class="form-group row">
										<label for="remark" class="col-5 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
										<div class="col-7">
											<textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
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
									message = json.msg;
									msg_icon = 1;
									parent.layer.close(index);
								}
								else {
									if(json.msg.points_error != '') {
										message = json.msg.points_error;
									}
									else if(json.msg.currency_id_error != '') {
										message = json.msg.currency_id_error;
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
					points: {
						required: true,
						number: true
					}
				},
				rules: {
					currency_id: {
						required: true,
					}
				},
				messages: {
					points: {
						required: "<?php echo $this->lang->line('error_invalid_points');?>",
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					currency_id: {
						required: "<?php echo $this->lang->line('error_select_currencies');?>",
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