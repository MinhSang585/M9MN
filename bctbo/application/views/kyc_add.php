<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>
<!DOCTYPE html>

<html lang="<?php echo get_language_code('iso'); ?>">

<head>

	<?php $this->load->view('parts/head_meta'); ?>
<style>
	.col-70 {
		-ms-flex: 0 0 58.333333%;
		flex: 0 0 58.333333%;
		max-width: 100%;
		}
	   
</style>
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

							<?php echo form_open_multipart('kyc/submit', array('id' => 'kyc-form', 'name' => 'kyc-form', 'class' => 'form-horizontal')); ?>

							<div class="card-body">
								<div class="form-group row">

									<label for="bank_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name'); ?></label>

									<div class="col-7">

										<select class="form-control select2bs4 col-70" id="bank_id" name="bank_id">

											<?php

											for ($i = 0; $i < sizeof($bank_list); $i++) {

												echo '<option value="' . $bank_list[$i]['bank_id'] . '">' . $bank_list[$i]['bank_name'] . '</option>';
											}

											?>

										</select>

									</div>

								</div>

								<div class="form-group row">

									<label for="bank_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_code'); ?></label>

									<div class="col-7">

										<input type="text" class="form-control" id="bank_code" name="bank_code" value="">

									</div>

								</div>

								<?php if (isset($username)) { ?>

									<div class="form-group row">

										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username'); ?></label>

										<div class="col-7">

											<label class="col-form-label font-weight-normal"><?php echo ((!empty($username)) ? $username : '-'); ?></label>

											<input type="hidden" class="form-control" id="username" name="username" value="<?php echo ((!empty($username)) ? $username : '-'); ?>">

										</div>

									</div>

								<?php } else { ?>

									<div class="form-group row">

										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username'); ?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="username" name="username" value="">

										</div>

									</div>

								<?php } ?>

								<div class="form-group row ">

									<label for="front_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_front');?></label>

									<div class="col-7">

										<div class="custom-file col-10">

											<input type="file" class="custom-file-input" id="front_image" name="front_image">

											<label class="custom-file-label" for="front_image"><?php echo $this->lang->line('button_choose_file');?></label>

										</div>

									</div>

								</div>

								<div class="form-group row ">

									<label for="back_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_back');?></label>

									<div class="col-7">

										<div class="custom-file col-10">

											<input type="file" class="custom-file-input" id="back_image" name="back_image">

											<label class="custom-file-label" for="back_image"><?php echo $this->lang->line('button_choose_file');?></label>

										</div>

									</div>

								</div>

								<div class="form-group row ">

									<label for="bank_statement_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_statement');?></label>

									<div class="col-7">

										<div class="custom-file col-10">

										<input type="file" class="custom-file-input" id="bank_statement_image" name="bank_statement_image">

											<label class="custom-file-label" for="bank_statement_image"><?php echo $this->lang->line('button_choose_file');?></label>

										</div>

									</div>

								</div>

							</div>

							<!-- /.card-body -->

							<div class="card-footer">

								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit'); ?></button>

								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel'); ?></button>

							</div>

							<!-- /.card-footer -->

							<?php echo form_close(); ?>

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

<?php $this->load->view('parts/footer_js'); ?>

<script type="text/javascript">
	$(document).ready(function() {

		var is_allowed = true;

		var form = $('#kyc-form');

		var index = parent.layer.getFrameIndex(window.name);

		$('#button-cancel').click(function() {

			parent.layer.close(index);

		});

		$.validator.setDefaults({

			submitHandler: function() {

				if (is_allowed == true) {

					is_allowed = false;

					var file_form = form[0];

					var formData = new FormData(file_form);

					$.each($("input[type='file']")[0].files, function(i, file) {

						formData.append('file', file);

					});

					$.ajax({
						url: form.attr('action'),

						data: formData,

						type: 'post',

						processData: false,

						contentType: false,

						async: 'true',

						beforeSend: function() {

							layer.load(1);

						},

						complete: function() {

							layer.closeAll('loading');

							is_allowed = true;

						},

						success: function(data) {

							var json = JSON.parse(JSON.stringify(data));

							var message = '';

							var msg_icon = 2;

							parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);

							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

							if (json.status == '<?php echo EXIT_SUCCESS; ?>') {

								message = json.msg;

								msg_icon = 1;

								parent.$('#kyc-table').DataTable().ajax.reload();

								parent.layer.close(index);

							} else {

								if (json.msg.error_enter_bank_code != '') {

									message = json.msg.error_enter_bank_code;

								} else if (json.msg.username_error != '') {

									message = json.msg.username_error;

								} else if (json.msg.general_error != '') {

									message = json.msg.general_error;
								}

							}

							parent.layer.alert(message, {
								icon: msg_icon,
								title: '<?php echo $this->lang->line('label_info'); ?>',
								btn: '<?php echo $this->lang->line('button_close'); ?>'
							});

						},

						error: function(request, error) {

						}

					});

				}

			}

		});

		form.validate({

			rules: {

				bank_code: {

					required: true
				},

				username: {

					required: true,
				},

			},

			messages: {

				bank_code: {

					required: "<?php echo 'Require insert code'; ?>",
				},

				username: {

					required: "<?php echo $this->lang->line('error_enter_username'); ?>",
				},

			},

			errorElement: 'span',

			errorPlacement: function(error, element) {

				error.addClass('invalid-feedback');

				element.closest('.form-group').append(error);

			},

			highlight: function(element, errorClass, validClass) {

				$(element).addClass('is-invalid');

			},

			unhighlight: function(element, errorClass, validClass) {

				$(element).removeClass('is-invalid');

			}

		});

	});
</script>

</body>

</html>