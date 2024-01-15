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
							<?php echo form_open('player/player_tag_modify_update', array('id' => 'player_tag_modify-form', 'name' => 'player_tag_modify-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>

									<div class="form-group row">
										<?php
											if(isset($tag_player_list) && sizeof($tag_player_list)>0){
												foreach($tag_player_list as $tag_player_row){
													if($tag_player_row['active'] == STATUS_ACTIVE){
														if(strpos($tag_ids,",".$tag_player_row['tag_player_id'].",") !== false){
										?>
										<div class="form-group clearfix col-12 col-sm-6 col-md-4 col-lg-3 pt-3">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input checkbox_option" type="checkbox" id="tag_<?php echo $tag_player_row['tag_player_id'];?>" name="tag[]" value="<?php echo $tag_player_row['tag_player_id'];?>" checked>
												<label class="custom-control-label font-weight-normal" for="tag_<?php echo $tag_player_row['tag_player_id'];?>">
													<span class="badge bg-success" style="background-color: <?php echo $tag_player_row['tag_player_background_color'];?> !important;color: <?php echo $tag_player_row['tag_player_font_color'];?> !important;font-weight: <?php echo (($tag_player_row['is_bold'] == STATUS_ACTIVE) ? "bold": "normal");?> !important;"><?php echo $tag_player_row['tag_player_code'];?></span>
												</label>
											</div>
										</div>
										<?php
													}else{
										?>
										<div class="form-group clearfix col-12 col-sm-6 col-md-4 col-lg-3 pt-3">
											<div class="custom-control custom-checkbox d-inline">
												<input class="custom-control-input checkbox_option" type="checkbox" id="tag_<?php echo $tag_player_row['tag_player_id'];?>" name="tag[]" value="<?php echo $tag_player_row['tag_player_id'];?>">
												<label class="custom-control-label font-weight-normal" for="tag_<?php echo $tag_player_row['tag_player_id'];?>">
													<span class="badge bg-success" style="background-color: <?php echo $tag_player_row['tag_player_background_color'];?> !important;color: <?php echo $tag_player_row['tag_player_font_color'];?> !important;font-weight: <?php echo (($tag_player_row['is_bold'] == STATUS_ACTIVE) ? "bold": "normal");?>;"><?php echo $tag_player_row['tag_player_code'];?></span>
												</label>
											</div>
										</div>
										<?php
														}
													}
												}
											}
										?>
									</div>
								</div>
								<div class="card-footer row">
									<div class="col-5">
										<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
									</div>
									<div class="col-7 text-left pt-1">
										<div class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" id="checkall">
											<label for="checkall" class="custom-control-label font-weight-normal"><?php echo $this->lang->line('label_select_all');?></label>
										</div>
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
			var form = $('#player_tag_modify-form');

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
									parent.$('#uc22_' + json.response.id).html(json.response.tags_text);
									parent.layer.close(index);
								}
								else {
									if(json.msg.general_error != '') {
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

		$('#checkall').click(function(){
			if($(this).is(':checked')){
				$('.checkbox_option').prop( "checked", true);
			}else{
				$('.checkbox_option').prop( "checked", false);
			}
		});
	</script>
</body>
</html>