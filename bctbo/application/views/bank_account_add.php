<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="<?php echo get_language_code('iso');?>">
<style>
   
.col-70 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 63.333333%;
	}

.col-80 {
   	margin-left: 7px;
	}
	
</style>
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

							<?php echo form_open_multipart('bank/account_submit', array('id' => 'bank_account-form', 'name' => 'bank_account-form', 'class' => 'form-horizontal'));?>

								<div class="card-body">

									<div class="form-group row">

										<label for="bank_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>

										<div class="col-7">

											<select class="form-control select2bs4 col-7" id="bank_id" name="bank_id">

												<?php

													for($i=0;$i<sizeof($bank_list);$i++)

													{

														echo '<option value="' . $bank_list[$i]['bank_id'] . '">' . $bank_list[$i]['bank_name'] . '</option>';

													}

												?>

											</select>

										</div>

									</div>

									<div class="form-group row">

										<label for="bank_account_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="bank_account_name" name="bank_account_name" value="">

										</div>

									</div>

									<div class="form-group row">

										<label for="bank_account_no" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="bank_account_no" name="bank_account_no" value="">

										</div>

									</div>

									<div class="form-group row">

										<label for="bank_reference" class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_reference');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="bank_reference" name="bank_reference" value="">

										</div>

									</div>

									<div class="form-group row">

										<label for="daily_limit" class="col-5 col-form-label"><?php echo $this->lang->line('label_daily_limit');?></label>

										<div class="col-7">

											<input type="text" class="form-control col-6" id="daily_limit" name="daily_limit" value="">

										</div>

									</div>

									<div class="form-group row">

										<label for="language_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_group');?></label>

										<div class="col-7">

											<select class="select2 col-12" id="group_ids" name="group_ids[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select_group');?>">

												<?php

													for($i=0;$i<sizeof($group_list);$i++)

													{

														echo '<option value="' . $group_list[$i]['group_id'] . '">' . $group_list[$i]['group_name'] . '</option>';

													}

												?>

											</select>

										</div>

									</div>

									<div class="form-group row">

										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>

										<div class="col-7">

											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">

										</div>

									</div>

									<div class="form-group row">

										<label for="payment_type" class="col-5 col-form-label">Payment Type</label>

											<div class="col-7">

												<select class="form-control col-70" id="payment_type" name="payment_type">
													<option value="0">Apply Payment</option>
													<option value="1">QR Payment</option>
												</select>

											</div>

									</div>

									<div class="form-group row" id="qr_div">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_qr');?></label>
										<div class="col-7">
											<input type="file" class="custom-file-input" id="qr_image" name="qr_image">
											<label class="custom-file-label col-80" for="qr_image"><?php echo $this->lang->line('button_choose_file');?></label>
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


	<script>
    // Listen for change event
    document.getElementById("payment_type").addEventListener("change", function () {
        var selectedValue = this.value;

        // If selected value is not '0'
        if (selectedValue === "1") {
			$("#qr_div").show();
        } else {
			$("#qr_div").hide();
        }
    });

    // Check initial value of select element
    var initialSelectedValue = document.getElementById("payment_type").value;
    if (initialSelectedValue === "1") {
		$("#qr_div").hide();
    }
	</script>

	<script type="text/javascript">

		$(document).ready(function() {
			$("#qr_div").hide();
			var is_allowed = true;

			var form = $('#bank_account-form');

			bsCustomFileInput.init();
			$('.select2').select2();

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


						var file_form = form[0];

						var formData = new FormData(file_form);

						$.each($("input[type='file']")[0].files, function(i, file) {

							formData.append('file', file);

						});
						

						$.ajax({url: form.attr('action'),
							
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

							success: function (data) {

								var json = JSON.parse(JSON.stringify(data));

								var message = '';

								var msg_icon = 2;

								

								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);

								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

								if(json.status == '<?php echo EXIT_SUCCESS;?>') {

									message = json.msg;

									msg_icon = 1;

									parent.$('#bank_account-table').DataTable().ajax.reload();

									parent.layer.close(index);

								}

								else {

									if(json.msg.bank_id_error != '') {

										message = json.msg.bank_id_error;

									}

									else if(json.msg.bank_account_name_error != '') {

										message = json.msg.bank_account_name_error;

									}

									else if(json.msg.bank_account_no_error != '') {

										message = json.msg.bank_account_no_error;

									}

									else if(json.msg.daily_limit_error != '') {

										message = json.msg.daily_limit_error;

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

					bank_id: {

						required: true

					},

					bank_account_name: {

						required: true

					},

					bank_account_no: {

						required: true,

					},

					daily_limit: {

						required: true,

						digits: true

					}

				},

				messages: {

					bank_id: {

						required: "<?php echo $this->lang->line('error_select_bank_name');?>",

					},

					bank_account_name: {

						required: "<?php echo $this->lang->line('error_enter_bank_account_name');?>",

					},

					bank_account_no: {

						required: "<?php echo $this->lang->line('error_enter_bank_account_no');?>",

						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_bank_account_no')), $this->lang->line('error_only_digits_allowed'));?>",

					},

					daily_limit: {

						required: "<?php echo $this->lang->line('error_enter_daily_limit');?>",

						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_daily_limit')), $this->lang->line('error_only_digits_allowed'));?>",

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
