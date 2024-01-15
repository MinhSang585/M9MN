<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="<?php echo get_language_code('iso');?>">

<head>

	<?php $this->load->view('parts/head_meta');?>

</head>

<body>

	<div class="wrapper" id="load_page" style="display: none;">

		<!-- Main content -->

		<section class="content">

			<div class="container-fluid mt-2">

				<div class="row">

					<!-- left column -->

					<div class="col-12">

						<!-- jquery validation -->

						<div class="card card-primary">

							<!-- form start -->

							<?php echo form_open('withdrawal/update', array('id' => 'withdrawal-form', 'name' => 'withdrawal-form', 'class' => 'form-horizontal'));?>

								<div class="card-body">

									<div class="form-group row">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>

										<div class="col-7">

											<label class="col-form-label font-weight-normal"><?php echo (isset($player['username']) ? $player['username'] : '');?></label>

										</div>

									</div>

									<div class="form-group row">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_amount_apply');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($amount) ? number_format($amount, 0, '.', ',') : '');?></label>

										</div>

									</div>

									<div class="form-group row">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_fee');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($amount_rate) ? number_format($withdrawal_fee_value, 0, '.', ',') : '');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_actual_amount');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($amount_rate) ? $amount_rate : '');?></label>

										</div>

									</div>

									<div class="form-group row">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_actual_amount');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($amount_rate) ? number_format($withdrawal_fee_amount, 0, '.', ',') : '');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_currency');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($currency_code) ? $currency_code : '');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_currency_rate');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($currency_rate) ? $currency_rate : '');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($bank_name) ? $bank_name : '-');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($bank_account_name) ? $bank_account_name : '-');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($bank_account_no) ? $bank_account_no : '-');?></label>

										</div>

									</div>

									<div class="form-group row" style="display: none;">

										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_account_address');?></label>

										<div class="col-3">

											<label class="col-form-label font-weight-normal"><?php echo (isset($bank_account_address) ? $bank_account_address : '-');?></label>

										</div>

									</div>

									<div class="form-group row">

										<label for="payment_gateway_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_payment_gateway');?></label>

										<div class="col-7">

											<select class="form-control select2bs4" id="payment_gateway_id" name="payment_gateway_id">

												<option value=""><?php echo $this->lang->line('withdrawal_offline_banking');?></option>

												<?php

												if(isset($payment_gateway_list)){

													for($i=0;$i<sizeof($payment_gateway_list);$i++)

													{

														echo '<option value="' . $payment_gateway_list[$i]['payment_gateway_id'] . '">' . $this->lang->line($payment_gateway_list[$i]['payment_gateway_name']) . '</option>';

													}

												}

												?>

											</select>

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

									<input type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo (isset($withdrawal_id) ? $withdrawal_id : '');?>">

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

			var form = $('#withdrawal-form');

			

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

										msg_icon = 1;

										parent.$('#uc1_' + json.response.id).html(json.response.status);

										parent.$('#uc2_' + json.response.id).html(json.response.remark);

										parent.$('#uc3_' + json.response.id).remove();

										parent.$('#uc20_' + json.response.id).remove();

										parent.$('#uc6_' + json.response.id).html(json.response.updated_by);

										parent.$('#uc7_' + json.response.id).html(json.response.updated_date);

										if(json.response.status_code == '<?php echo STATUS_APPROVE;?>') {

											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');

										}

										else if(json.response.status_code == '<?php echo STATUS_ON_HOLD_PENDING;?>') {

											parent.$('#uc1_' + json.response.id).removeClass('bg-secondary').addClass('bg-info');

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

			$('#load_page').show();

		});

	</script>

</body>

</html>
