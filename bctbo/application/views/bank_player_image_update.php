<?php

defined('BASEPATH') or exit('No direct script access allowed');



?>
<!DOCTYPE html>

<html lang="<?php echo get_language_code('iso'); ?>">

<head>

	<?php $this->load->view('parts/head_meta'); ?>
	
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

						<?php echo form_open_multipart('bank/playerBankImagUpdateData', array('id' => 'bank-form', 'name' => 'bank-form', 'class' => 'form-horizontal')); ?>

						<div class="card-body">

							<div class="form-group row">

								<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username'); ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($user_name)) ? $user_name : null); ?></label>
								</div>

							</div>

							<div class="form-group row">

								<label for="bank_name" class="col-5 col-form-label"><?php echo 'Type'; ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($player_bank_image_type)) ? $player_bank_image_type : null); ?></label>

								</div>

							</div>

							<div class="form-group row">

								<label for="bank_code" class="col-5 col-form-label"><?php echo 'FullName'; ?></label>

								<div class="col-7">

									<label class="col-form-label font-weight-normal"><?php echo ((!empty($full_name)) ? $full_name : null); ?></label>
								</div>

							</div>

							<div class="form-group row">

								<label for="verify" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>

								<div class="col-7">

									<input type="checkbox" id="active" name="active" value="1" <?php echo (($active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">

								</div>

							</div>

							<div class="form-group row">

								<label for="verify" class="col-5 col-form-label"><?php echo $this->lang->line('label_verify');?></label>

								<div class="col-7">

									<input type="checkbox" id="verify" name="verify" value="1" <?php echo (($verify == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">

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

							


						</div>

						<!-- /.card-body -->

						<div class="card-footer">

							<input type="hidden" id="player_bank_image_id" name="player_bank_image_id" value="<?php echo (isset($player_bank_image_id) ? $player_bank_image_id : '');?>">

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

		var form = $('#bank-form');

		$("input[data-bootstrap-switch]").each(function() {

			$(this).bootstrapSwitch('state', $(this).prop('checked'));

		});

		var index = parent.layer.getFrameIndex(window.name);

		$('#button-cancel').click(function() {

			parent.layer.close(index);

		});

		$.validator.setDefaults({

			submitHandler: function() {

				if (is_allowed == true) {

					is_allowed = false;

					$.ajax({
						url: form.attr('action'),

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

						success: function(data) {

							var json = JSON.parse(JSON.stringify(data));

							var message = '';

							var msg_icon = 2;

							parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);

							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

							if (json.status == '<?php echo EXIT_SUCCESS; ?>') {

								message = json.msg;

								msg_icon = 1;

								parent.$('#bank_player_image-table').DataTable().ajax.reload();

								parent.layer.close(index);

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

		form.validate();

		$('#load_page').show();

	});
</script>

</body>

</html>