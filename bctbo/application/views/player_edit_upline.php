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

							<?php echo form_open_multipart('player/update_upline', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>

								<div class="card-body">

									<div class="form-group row">

										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>

										<div class="col-7">

											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>

										</div>

									</div>

									<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">

									<div class="form-group row">

										<label for="value" class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="upline" name="upline" value="<?php echo (isset($value) ? $value : '');?>">

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

			var form = $('#player-form');

			

			//bsCustomFileInput.init();

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

									parent.$('#player-table').DataTable().ajax.reload();

									parent.layer.close(index);

								}

								else {

									if(json.msg.type_error != '') {

										message = json.msg.type_error;

									}

									else if(json.msg.value_error != '') {

										message = json.msg.value_error;

									}

									else if(json.msg.username_error != '') {

										message = json.msg.username_error;

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

		});

	</script>

</body>

</html>

