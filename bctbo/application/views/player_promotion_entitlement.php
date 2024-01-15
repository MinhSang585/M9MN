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
							<?php echo form_open('playerpromotion/entitlement_update', array('id' => 'playerpromotion-form', 'name' => 'playerpromotion-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($player['username']) ? $player['username'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_amount');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['deposit_amount']) ? $promotion['deposit_amount'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['promotion_name']) ? $promotion['promotion_name'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_status');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion_response['msg']) ? $promotion_response['msg'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_archieve_amount');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['achieve_amount']) ? $promotion['achieve_amount'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['reward_amount']) ? $promotion['reward_amount'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="remark" class="col-5 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
										<div class="col-7">
											<textarea class="form-control" id="remark" name="remark" rows="3"><?php echo (isset($promotion['remark']) ? $promotion['remark'] : '');?></textarea>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="status" name="status" value="<?php echo STATUS_CANCEL;?>">
									<input type="hidden" id="is_reward" name="is_reward" value="<?php echo (isset($promotion['is_reward']) ? $promotion['is_reward'] : '0');?>">
									<input type="hidden" id="player_promotion_id" name="player_promotion_id" value="<?php echo (isset($promotion['player_promotion_id']) ? $promotion['player_promotion_id'] : '');?>">
									<button type="submit" value="<?php echo STATUS_ENTITLEMENT;?>" class="btn btn-primary"><?php echo $this->lang->line('button_entitlement');?></button>
									<button type="submit" value="<?php echo STATUS_CANCEL;?>" class="btn btn-danger ml-2"><?php echo $this->lang->line('button_reject');?></button>
									<button type="submit" value="<?php echo STATUS_VOID;?>" class="btn btn-warning ml-2"><?php echo $this->lang->line('button_void');?></button>
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
			var form = $('#playerpromotion-form');
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
					$('#status').val($(document.activeElement).val());
					layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
						title: '<?php echo $this->lang->line('label_info');?>',
						btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
					}, function() {
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
										msg_icon = 1;
										message = json.msg;
										parent.$('#uc1_' + json.response.id).html(json.response.status);
										parent.$('#uc2_' + json.response.id).html(json.response.remark);
										parent.$('#uc21_' + json.response.id).remove();
										parent.$('#uc4_' + json.response.id).html(json.response.is_reward);
										parent.$('#uc5_' + json.response.id).html(json.response.reward_amount);
										parent.$('#uc6_' + json.response.id).html(json.response.reward_date);
										parent.$('#uc9_' + json.response.id).html(json.response.updated_by);
										parent.$('#uc10_' + json.response.id).html(json.response.updated_date);

										if(json.response.is_reward_code == '<?php echo STATUS_APPROVE;?>') {
											parent.$('#uc4_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-warning').addClass('bg-success');
										}else{
											parent.$('#uc4_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-warning').addClass('bg-secondary');
										}

										if(json.response.status_code == '<?php echo STATUS_ENTITLEMENT;?>') {
											parent.$('#uc22_' + json.response.id).show();
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-warning').removeClass('bg-success').addClass('bg-primary');
										}
										else if(json.response.status_code == '<?php echo STATUS_CANCEL;?>') {
											parent.$('#uc88_' + json.response.id).remove();
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-warning').addClass('bg-danger');
										}
										else if(json.response.status_code == '<?php echo STATUS_VOID;?>') {
											parent.$('#uc88_' + json.response.id).remove();
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-danger').addClass('bg-warning');
										}
										else {
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').removeClass('bg-primary').removeClass('bg-warning').addClass('bg-secondary');
										}
										
										parent.layer.close(index);
									}else{
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
					});	
				}
			});
			
			form.validate();
		});
	</script>
</body>
</html>
