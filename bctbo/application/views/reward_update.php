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
							<?php echo form_open('reward/update', array('id' => 'reward-form', 'name' => 'reward-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($player['username']) ? $player['username'] : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_amount');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($reward_amount) ? $reward_amount : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($promotion_name) ? $promotion_name : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_amount_calculated');?></label>
										<div class="col-3">
											<label class="col-form-label font-weight-normal"><?php echo (isset($reward_calculated) ? $reward_calculated : '');?></label>
										</div>
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
									<input type="hidden" id="status" name="status" value="<?php echo STATUS_CANCEL;?>">
									<input type="hidden" id="player_reward_id" name="player_reward_id" value="<?php echo (isset($player_reward_id) ? $player_reward_id : '');?>">
									<button type="submit" value="<?php echo STATUS_APPROVE;?>" class="btn btn-success"><?php echo $this->lang->line('button_approve');?></button>
									<button type="submit" value="<?php echo STATUS_CANCEL;?>" class="btn btn-danger ml-2"><?php echo $this->lang->line('button_reject');?></button>
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
			var form = $('#reward-form');
			
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
									console.log(json);
									var message = json.msg;
									var msg_icon = 2;
									
									parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
									$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
									
									if(json.status == '<?php echo EXIT_SUCCESS;?>') {
										msg_icon = 1;
										parent.$('#uc1_' + json.response.id).html(json.response.status);
										parent.$('#uc2_' + json.response.id).html(json.response.remark);
										parent.$('#uc3_' + json.response.id).html(json.response.updated_by);
										parent.$('#uc4_' + json.response.id).html(json.response.updated_date);
										parent.$('#uc5_' + json.response.id).remove();
										
										if(json.response.status_code == '<?php echo STATUS_APPROVE;?>') {
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
										}
										else {
											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').addClass('bg-danger');
										}
										
										parent.layer.close(index);
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
