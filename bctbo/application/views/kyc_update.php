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

						<?php echo form_open_multipart('kyc/update', array('id' => 'kyc-form', 'name' => 'kyc-form', 'class' => 'form-horizontal')); ?>

						<div class="card-body">

							<div class="form-group row">

								<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username'); ?></label>

								<div class="col-7">
									
									<label class="col-form-label font-weight-normal"><?php echo ((!empty($user_name)) ? $user_name : null); ?></label>
								</div>

							</div>

							<div class="form-group row">

								<label for="bank_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name'); ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($bank_name)) ? $bank_name : null); ?></label>

								</div>

							</div>

							<div class="form-group row">

								<label for="bank_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_code'); ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($number_account)) ? $number_account : null); ?></label>
								</div>

							</div>

							<div class="form-group row">

								<label for="created_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by'); ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($created_by)) ? $created_by : '-'); ?></label>

								</div>

							</div>

							<div class="form-group row">

								<label for="created_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date'); ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-'); ?></label>

								</div>

							</div>

							<?php if (isset($front_image)) : ?>

								<div class="form-group row mt-3">

								<label for="front_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_front'); ?></label>

									<div class="col-7">

										<a href="<?php echo KYC_IMAGE . $front_image; ?>" target="_blank"><img src="<?php echo KYC_IMAGE . $front_image; ?>" width="200" border="0" /></a>

									</div>

								</div>

							<?php endif; ?>

							<?php if (isset($back_image)) : ?>

								<div class="form-group row mt-3">

								<label for="back_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_image_back'); ?></label>

									<div class="col-7">

										<a href="<?php echo KYC_IMAGE . $back_image; ?>" target="_blank"><img src="<?php echo KYC_IMAGE . $back_image; ?>" width="200" border="0" /></a>

									</div>

								</div>

							<?php endif; ?>

							<?php if (isset($bank_statement_image)) : ?>

								<div class="form-group row mt-3">

								<label for="back_image" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_statement'); ?></label>

									<div class="col-7">

										<a href="<?php echo KYC_IMAGE . $bank_statement_image; ?>" target="_blank"><img src="<?php echo KYC_IMAGE . $bank_statement_image; ?>" width="200" border="0" /></a>

									</div>

								</div>

							<?php endif; ?>

						</div>

						<!-- /.card-body -->

						<div class="card-footer">

							<input type="hidden" id="status" name="status" value="<?php echo STATUS_CANCEL; ?>">
							<input type="hidden" id="kyc_id" name="kyc_id" value="<?php echo (isset($kyc_id) ? $kyc_id : ''); ?>">
							<button type="submit" value="<?php echo STATUS_APPROVE; ?>" class="btn btn-success"><?php echo $this->lang->line('button_approve'); ?></button>
							<button type="submit" value="<?php echo STATUS_CANCEL; ?>" class="btn btn-danger ml-2"><?php echo $this->lang->line('button_reject'); ?></button>
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

									var message = json.msg;

									var msg_icon = 2;


									parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);

									$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

									if(json.status == '<?php echo EXIT_SUCCESS;?>') {

										message = json.msg;

										msg_icon = 1;

										parent.$('#kyc-table').DataTable().ajax.reload();

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

			$('#load_page').show();

		});

	</script>

</body>

</html>