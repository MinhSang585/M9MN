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
							<?php echo form_open('player/agent_submit', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_referrer');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-12" id="referrer" name="referrer">
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="" maxlength="16">
										</div>
									</div>
									<div class="form-group row">
										<label for="password" class="col-5 col-form-label"><?php echo $this->lang->line('label_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="password" name="password" maxlength="15">
										</div>
									</div>
									<div class="form-group row">
										<label for="passconf" class="col-5 col-form-label"><?php echo $this->lang->line('label_confirm_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="passconf" name="passconf" maxlength="15">
										</div>
									</div>
									<div class="form-group row">
										<label for="mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_mobile');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="mobile" name="mobile" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="line_id" class="col-5 col-form-label"><?php echo $this->lang->line('im_line');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="line_id" name="line_id" value="<?php echo (isset($line_id) ? $line_id : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<input type="hidden" name="upline" value="<?php echo $this->session->userdata('root_username');?>">
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
			var form = $('#player-form');
			$('.select2').select2();
			$("#game_type_select_all").click(function(){
	        	$("#game_type").find('option').prop("selected",true);
	        	$("#game_type").trigger('change');
		  	});
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});

			//Username remote data
			$('#upline').on('change', function() {
				$("#referrer").empty().trigger('change');
			});

			$('#upline').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_upline');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/upline_search');?>',
			        type: "post",
			        dataType: 'json',
			        delay: 250,
			        cache: false,
			        data: function (params) {
			           	return {
			            	csrf_bctp_bo_token : parent.$('meta[name=csrf_token]').attr('content'),
					        search: params.term,
					        page: params.page || 1,
					        length : 10,
					    }
			        },
			        processResults: function (data, params) {
			        	var json = JSON.parse(JSON.stringify(data));
			        	parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
					    params.page = params.page || 1;
					    return {
					        results: data.data,
					        pagination: {
					            more: (params.page * 10) < data.recordsFiltered
					        }
					    };
					}              
			    }
			});

			$('#referrer').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_referrer');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/referrer_search');?>',
			        type: "post",
			        dataType: 'json',
			        delay: 250,
			        cache: false,
			        data: function (params) {
			           	return {
			            	csrf_bctp_bo_token : parent.$('meta[name=csrf_token]').attr('content'),
					        upline: '<?php echo $this->session->userdata('root_username');?>',
					        search: params.term,
					        page: params.page || 1,
					        length : 10,
					    }
			        },
			        processResults: function (data, params) {
			        	var json = JSON.parse(JSON.stringify(data));
			        	parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
					    params.page = params.page || 1;
					    return {
					        results: data.data,
					        pagination: {
					            more: (params.page * 10) < data.recordsFiltered
					        }
					    };
					}              
			    }
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
									parent.$('#player-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.nickname_error != '') {
										message = json.msg.nickname_error;
									}
									else if(json.msg.mobile_error != '') {
										message = json.msg.mobile_error;
									}
									else if(json.msg.email_error != '') {
										message = json.msg.email_error;
									}
									else if(json.msg.password_error != '') {
										message = json.msg.password_error;
									}
									else if(json.msg.passconf_error != '') {
										message = json.msg.passconf_error;
									}
									else if(json.msg.player_type_error != ''){
										message = json.msg.player_type_error;
									}
									else if(json.msg.win_loss_suspend_limit_error != '') {
										message = json.msg.win_loss_suspend_limit_error;
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
					username: {
						required: true,
						rangelength: [6, 16]
					},
					nickname: {
						//required: true
					},
					mobile: {
						digits: true
					},
					password: {
						required: true,
						rangelength: [6, 15]
					},
					passconf: {
						required: true
					},
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
					},
					nickname: {
						//required: "<?php echo $this->lang->line('error_enter_nickname');?>",
					},
					mobile: {
						digits: "<?php echo $this->lang->line('error_invalid_mobile');?>",
					},
					password: {
						required: "<?php echo $this->lang->line('error_enter_password');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_password');?>",
					},
					passconf: {
						required: "<?php echo $this->lang->line('error_enter_confirm_password');?>",
					},
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
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