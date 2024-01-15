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

							<input type="hidden" class="form-control" id="reward_turnover_multiply" name="reward_turnover_multiply" value="0">

							<input type="hidden" class="form-control" id="reward_calculate_type" name="reward_calculate_type" value="0">

							<input type="hidden" class="form-control" id="reward_amount_auto_calculate" name="reward_amount_auto_calculate" value="0">

							<input type="hidden" class="form-control" id="reward_amount_rate" name="reward_amount_rate" value="0">

							<input type="hidden" class="form-control" id="reward_amount_max" name="reward_amount_max" value="0">

							<input type="hidden" class="form-control" id="reward_amount_is_level" name="reward_amount_is_level" value="0">

							<input type="hidden" class="form-control" id="reward_amount_deposit_min" name="reward_amount_deposit_min" value="0">

							<input type="hidden" class="form-control" id="reward_amount_deposit_max" name="reward_amount_deposit_max" value="0">

							<?php echo form_open_multipart('playerpromotion/submit', array('id' => 'promotion_submit-form', 'name' => 'promotion_submit-form', 'class' => 'form-horizontal'));?>

								<div class="card-body">

									<div class="form-group row">

										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_player_username');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="username" name="username" value="">

										</div>

									</div>

									<div class="form-group row">

										<label for="promotion_content" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>

										<div class="col-7">

											<select class="form-control select2bs4 col-12" id="promotion_id" name="promotion_id">

												<option value=""><?php echo $this->lang->line('place_holder_select_promotion');?></option>

												<?php

													if(isset($promotion_list)){

														if(sizeof($promotion_list)>0){

															foreach($promotion_list as $row){

																echo '<option value="' . $row['promotion_id'] . '">' . $row['promotion_name'] . '</option>';

															}

														}

													}

												?>

											</select>

										</div>

									</div>

									<div class="form-group row" id="player_promotion_range" style="display:none;">

										<label for="promotion_content" class="col-5 col-form-label"><?php echo $this->lang->line('label_range_type');?></label>

										<div class="col-7">

											<select class="form-control select2bs4 col-12" id="promotion_range" name="promotion_range">

												<option value=""><?php echo $this->lang->line('place_holder_select_promotion_default_range');?></option>

											</select>

										</div>

									</div>

									<div class="form-group row" id="player_promotion_deposit_amount" style="display:none;">

										<label for="deposit_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_amount');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="deposit_amount" name="deposit_amount" value="" onkeyup="calculateDepositRewardAmount(this.value)">

										</div>

									</div>

									<div class="form-group row" id="player_promotion_referrer" style="display:none;">

										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_referrer_username');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="referrer" name="referrer" value="">

										</div>

									</div>

									<div class="form-group row" id="player_promotion_reward_amount" style="display:none;">

										<label for="reward_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="reward_amount" name="reward_amount" value="">

										</div>

									</div>

									<div class="form-group row" id="player_promotion_archieve_amount" style="display:none;">

										<label for="achieve_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_archieve_amount');?></label>

										<div class="col-7">

											<input type="text" class="form-control" id="achieve_amount" name="achieve_amount" value="">

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

			var form = $('#promotion_submit-form');

			

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

									parent.$('#playerpromotion-table').DataTable().ajax.reload();

									parent.layer.close(index);

								}

								else {

									if(json.msg.username_error != '') {

										message = json.msg.username_error;

									}

									else if(json.msg.promotion_id_error != '') {

										message = json.msg.promotion_id_error;

									}

									else if(json.msg.deposit_amount_error != '') {

										message = json.msg.deposit_amount_error;

									}

									else if(json.msg.referrer_error != '') {

										message = json.msg.referrer_error;

									}

									else if(json.msg.achieve_amount_error != '') {

										message = json.msg.achieve_amount_error;

									}

									else if(json.msg.reward_amount_error != '') {

										message = json.msg.reward_amount_error;

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

					username: {

						required: true

					},

					promotion_id: {

						required: true,

					},

				},

				messages: {

					username: {

						required: "<?php echo $this->lang->line('error_enter_player_username');?>",

					},

					promotion_id: {

						required: "<?php echo $this->lang->line('error_enter_promotion_name');?>",

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



			$("#promotion_id").on('change', function () {

				$("#deposit_amount").val('0');

				var promotion_id = this.value;

				if(promotion_id != ""){

					var username = $("#username").val();

					var deposit_amount = parseFloat($("#deposit_amount").val());

					$("#reward_turnover_multiply").val("0");

					$("#reward_calculate_type").val("0");

					$("#reward_amount_auto_calculate").val("0");

					$("#reward_amount_rate").val("0");

					$("#reward_amount_max").val("0");

					$("#reward_amount_is_level").val("0");

					$("#reward_amount_deposit_min").val("0");

					$("#reward_amount_deposit_max").val("0");



					$("#player_promotion_range").hide();

					$("#player_promotion_deposit_amount").hide();

					$("#player_promotion_referrer").hide();

					$("#player_promotion_archieve_amount").hide();

					$("#player_promotion_reward_amount").hide();

					$("#promotion_range").html("");

						

					$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_setting/')?>"+promotion_id,

						data: form.serialize(),

						type: 'GET',                  

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

								if(json.response.is_deposit == '<?php echo STATUS_ACTIVE;?>'){

									$("#player_promotion_deposit_amount").show();

								}

								if(json.response.is_referral == '<?php echo STATUS_ACTIVE;?>'){

									$("#player_promotion_referrer").show();

								}

								if(json.response.is_achieve == '<?php echo STATUS_ACTIVE;?>'){

									$("#player_promotion_archieve_amount").show();

								}

								if(json.response.is_reward == '<?php echo STATUS_ACTIVE;?>'){

									$("#player_promotion_reward_amount").show();

								}

								if(json.response.is_range == '<?php echo STATUS_ACTIVE;?>'){

									//$("#player_promotion_range").show();

									$("#promotion_range").append($('<option></option>').val("").html("<?php echo $this->lang->line('place_holder_select_promotion_default_range');?>"));

									/*

									var i;

	                    			for (i = 0; i < json.response.range.length; i++) {

	                    				$("#promotion_range").append($('<option></option>').val(json.response.range[i]['bonus_index']).html("<?php echo $this->lang->line('label_rollover');?>"+" : "+json.response.range[i]['turnover_multiply']+" ("+"<?php echo $this->lang->line('label_amount_from');?>"+json.response.range[i]['amount_from']+")"));

	                    			}

	                    			*/

								}



								$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_reward_amount_setting/')?>"+promotion_id+"/"+deposit_amount+"/"+username,

									type: 'GET',                  

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

										if(json.status == '<?php echo EXIT_SUCCESS;?>') {

											$("#reward_turnover_multiply").val(json.response.reward_turnover_multiply);

											$("#reward_calculate_type").val(json.response.reward_calculate_type);

											$("#reward_amount_auto_calculate").val(json.response.reward_amount_auto_calculate);

											$("#reward_amount_rate").val(json.response.reward_amount_rate);

											$("#reward_amount_max").val(json.response.reward_amount_max);

											$("#reward_amount_is_level").val(json.response.reward_amount_is_level);

											$("#reward_amount_deposit_min").val(json.response.reward_amount_deposit_min);

											$("#reward_amount_deposit_max").val(json.response.reward_amount_deposit_max);

											if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

												var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

												if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

													rebate_amount = parseFloat(json.response.reward_amount_max);

												}

												$("#reward_amount").val(rebate_amount);

												$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

											}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

												var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

												if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

													rebate_amount = parseFloat(json.response.reward_amount_max);

												}

												$("#reward_amount").val(rebate_amount);

												$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(json.response.reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100) * parseFloat(json.response.reward_turnover_multiply)));

											}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

												var rebate_amount = parseFloat(json.response.reward_amount_max);

												$("#reward_amount").val(json.response.reward_amount_max);

												$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

											}else{

												var rebate_amount = parseFloat(json.response.reward_amount_max);

												$("#reward_amount").val(json.response.reward_amount_max);

												$("#achieve_amount").val((((parseFloat(json.response.reward_amount_deposit_min) + parseFloat(json.response.reward_amount_max)) * parseFloat(json.response.reward_turnover_multiply)) + (deposit_amount - parseFloat(json.response.reward_amount_deposit_min))));

											}
											if (promotion_id==25) {
												$("#deposit_amount").val(30);
												$("#reward_amount").val(30);
												$("#achieve_amount").val(100);
											}
										}

									},

									error: function (request,error) {

										

									}

								});

							}else{

								if(json.msg.general_error != '') {

									message = json.msg.general_error;

								}

								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});

							}

						},

						error: function (request,error) {

						}

					});

				}

			});

		});

		

		function calculateDepositRewardAmount(value){

			if(value == ""){

				deposit_amount = 0;

			}else{

				deposit_amount = parseFloat(value);

			}

			var username = $("#username").val();

			var promotion_id = $("#promotion_id").val();

			var reward_turnover_multiply = parseFloat($("#reward_turnover_multiply").val());

			var reward_calculate_type = $("#reward_calculate_type").val();

			var reward_amount_amount = parseFloat($("#reward_amount").val());

			var reward_amount_auto_calculate = $("#reward_amount_auto_calculate").val();

			var reward_amount_rate = parseFloat($("#reward_amount_rate").val());

			var reward_amount_max = parseFloat($("#reward_amount_max").val());

			var reward_amount_is_level = $("#reward_amount_is_level").val();

			var reward_amount_deposit_min = parseFloat($("#reward_amount_deposit_min").val());

			var reward_amount_deposit_max = parseFloat($("#reward_amount_deposit_max").val());

			if(reward_amount_auto_calculate == '<?php echo STATUS_ACTIVE;?>'){

				if(reward_amount_is_level  == '<?php echo STATUS_ACTIVE;?>'){

					if(reward_calculate_type  == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

						if(reward_amount_deposit_min <= deposit_amount && (reward_amount_deposit_max >= deposit_amount || reward_amount_deposit_max == 0)){

							var rebate_amount = deposit_amount * reward_amount_rate / 100;

							if(rebate_amount >= reward_amount_max){

								rebate_amount = reward_amount_max;

							}

							$("#reward_amount").val(rebate_amount);

							$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(reward_turnover_multiply));

						}else{

							$("#reward_calculate_type").val("0");

							$("#reward_amount_auto_calculate").val("0");

							$("#reward_amount_rate").val("0");

							$("#reward_amount_max").val("0");

							$("#reward_amount_is_level").val("0");

							$("#reward_amount_deposit_min").val("0");

							$("#reward_amount_deposit_max").val("0");

							$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_reward_amount_setting/')?>"+promotion_id+"/"+deposit_amount+"/"+username,

								type: 'GET',                  

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

									if(json.status == '<?php echo EXIT_SUCCESS;?>') {

										$("#reward_calculate_type").val(json.response.reward_calculate_type);

										$("#reward_amount_auto_calculate").val(json.response.reward_amount_auto_calculate);

										$("#reward_amount_rate").val(json.response.reward_amount_rate);

										$("#reward_amount_max").val(json.response.reward_amount_max);

										$("#reward_amount_is_level").val(json.response.reward_amount_is_level);

										$("#reward_amount_deposit_min").val(json.response.reward_amount_deposit_min);

										$("#reward_amount_deposit_max").val(json.response.reward_amount_deposit_max);

										$("#reward_turnover_multiply").val(json.response.reward_turnover_multiply);

										if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(json.response.reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100) * parseFloat(json.response.reward_turnover_multiply)));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT ?>"){

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else{

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((((parseFloat(json.response.reward_amount_deposit_min) + parseFloat(json.response.reward_amount_max)) * parseFloat(json.response.reward_turnover_multiply)) + (deposit_amount - parseFloat(json.response.reward_amount_deposit_min))));

										}

									}

								},

								error: function (request,error) {

									

								}

							});

						}

					}else if(reward_calculate_type  == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

						if(reward_amount_deposit_min <= deposit_amount && (reward_amount_deposit_max >= deposit_amount || reward_amount_deposit_max == 0)){

							var rebate_amount = deposit_amount * reward_amount_rate / 100;

							if(rebate_amount >= reward_amount_max){

								rebate_amount = reward_amount_max;

							}

							$("#reward_amount").val(rebate_amount);

							$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(reward_amount_rate) * 100) * parseFloat(reward_turnover_multiply)));

						}else{

							$("#reward_calculate_type").val("0");

							$("#reward_amount_auto_calculate").val("0");

							$("#reward_amount_rate").val("0");

							$("#reward_amount_max").val("0");

							$("#reward_amount_is_level").val("0");

							$("#reward_amount_deposit_min").val("0");

							$("#reward_amount_deposit_max").val("0");

							$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_reward_amount_setting/')?>"+promotion_id+"/"+deposit_amount+"/"+username,

								type: 'GET',                  

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

									if(json.status == '<?php echo EXIT_SUCCESS;?>') {

										$("#reward_calculate_type").val(json.response.reward_calculate_type);

										$("#reward_amount_auto_calculate").val(json.response.reward_amount_auto_calculate);

										$("#reward_amount_rate").val(json.response.reward_amount_rate);

										$("#reward_amount_max").val(json.response.reward_amount_max);

										$("#reward_amount_is_level").val(json.response.reward_amount_is_level);

										$("#reward_amount_deposit_min").val(json.response.reward_amount_deposit_min);

										$("#reward_amount_deposit_max").val(json.response.reward_amount_deposit_max);

										$("#reward_turnover_multiply").val(json.response.reward_turnover_multiply);

										if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(json.response.reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100) * parseFloat(json.response.reward_turnover_multiply)));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT ?>"){

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else{

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((((parseFloat(json.response.reward_amount_deposit_min) + parseFloat(json.response.reward_amount_max)) * parseFloat(json.response.reward_turnover_multiply)) + (deposit_amount - parseFloat(json.response.reward_amount_deposit_min))));

										}

									}

								},

								error: function (request,error) {

									

								}

							});

						}

					}else if(reward_calculate_type  == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT ?>"){

						if(reward_amount_deposit_min <= deposit_amount && (reward_amount_deposit_max >= deposit_amount || reward_amount_deposit_max == 0)){

						}else{

							$("#reward_calculate_type").val("0");

							$("#reward_amount_auto_calculate").val("0");

							$("#reward_amount_rate").val("0");

							$("#reward_amount_max").val("0");

							$("#reward_amount_is_level").val("0");

							$("#reward_amount_deposit_min").val("0");

							$("#reward_amount_deposit_max").val("0");

							$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_reward_amount_setting/')?>"+promotion_id+"/"+deposit_amount+"/"+username,

								type: 'GET',                  

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

									if(json.status == '<?php echo EXIT_SUCCESS;?>') {

										$("#reward_calculate_type").val(json.response.reward_calculate_type);

										$("#reward_amount_auto_calculate").val(json.response.reward_amount_auto_calculate);

										$("#reward_amount_rate").val(json.response.reward_amount_rate);

										$("#reward_amount_max").val(json.response.reward_amount_max);

										$("#reward_amount_is_level").val(json.response.reward_amount_is_level);

										$("#reward_amount_deposit_min").val(json.response.reward_amount_deposit_min);

										$("#reward_amount_deposit_max").val(json.response.reward_amount_deposit_max);

										$("#reward_turnover_multiply").val(json.response.reward_turnover_multiply);

										if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(json.response.reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100) * parseFloat(json.response.reward_turnover_multiply)));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT ?>"){

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else{

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((((parseFloat(json.response.reward_amount_deposit_min) + parseFloat(json.response.reward_amount_max)) * parseFloat(json.response.reward_turnover_multiply)) + (deposit_amount - parseFloat(json.response.reward_amount_deposit_min))));

											

										}

									}

								},

								error: function (request,error) {

									

								}

							});

						}

					}else if(reward_calculate_type  == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT_FROM ?>"){

						if(reward_amount_deposit_min <= deposit_amount && (reward_amount_deposit_max >= deposit_amount || reward_amount_deposit_max == 0)){

							//console.log("Current Amount : "+deposit_amount+" max Amount : "+ reward_amount_deposit_max);

							$("#achieve_amount").val((((parseFloat(reward_amount_deposit_min) + parseFloat(reward_amount_max)) * parseFloat(reward_turnover_multiply)) + (deposit_amount - parseFloat(reward_amount_deposit_min))));

						}else{

							//console.log("Reach Max : "+reward_amount_deposit_max+"Current Amount : "+deposit_amount);

							$("#reward_calculate_type").val("0");

							$("#reward_amount_auto_calculate").val("0");

							$("#reward_amount_rate").val("0");

							$("#reward_amount_max").val("0");

							$("#reward_amount_is_level").val("0");

							$("#reward_amount_deposit_min").val("0");

							$("#reward_amount_deposit_max").val("0");

							$.ajax({url: "<?php echo base_url('playerpromotion/get_promotion_reward_amount_setting/')?>"+promotion_id+"/"+deposit_amount+"/"+username,

								type: 'GET',                  

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

									if(json.status == '<?php echo EXIT_SUCCESS;?>') {

										$("#reward_calculate_type").val(json.response.reward_calculate_type);

										$("#reward_amount_auto_calculate").val(json.response.reward_amount_auto_calculate);

										$("#reward_amount_rate").val(json.response.reward_amount_rate);

										$("#reward_amount_max").val(json.response.reward_amount_max);

										$("#reward_amount_is_level").val(json.response.reward_amount_is_level);

										$("#reward_amount_deposit_min").val(json.response.reward_amount_deposit_min);

										$("#reward_amount_deposit_max").val(json.response.reward_amount_deposit_max);

										$("#reward_turnover_multiply").val(json.response.reward_turnover_multiply);

										if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER ?>"){

											var rebate_amount = deposit_amount * parseFloat(json.response.reward_amount_rate) / 100;

											if(rebate_amount >= parseFloat(json.response.reward_amount_max)){

												rebate_amount = parseFloat(json.response.reward_amount_max);

											}

											$("#reward_amount").val(rebate_amount);

											$("#achieve_amount").val((parseFloat(deposit_amount) - (parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100)) + (parseFloat(rebate_amount) * parseFloat(json.response.reward_turnover_multiply)) + ((parseFloat(rebate_amount) / parseFloat(json.response.reward_amount_rate) * 100) * parseFloat(json.response.reward_turnover_multiply)));

										}else if(json.response.reward_calculate_type == "<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT ?>"){

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(json.response.reward_turnover_multiply));

										}else{

											var rebate_amount = parseFloat(json.response.reward_amount_max);

											$("#reward_amount").val(json.response.reward_amount_max);

											$("#achieve_amount").val((((parseFloat(json.response.reward_amount_deposit_min) + parseFloat(json.response.reward_amount_max)) * parseFloat(json.response.reward_turnover_multiply)) + (deposit_amount - parseFloat(json.response.reward_amount_deposit_min))));

											

										}

									}

								},

								error: function (request,error) {

									

								}

							});

						}

					}

				}else{

					if(reward_calculate_type  == "<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE ?>"){

						var rebate_amount = parseFloat(deposit_amount) * parseFloat(reward_amount_rate) / 100;

						if(rebate_amount >= reward_amount_max){

							rebate_amount = reward_amount_max;

						}

						$("#reward_amount").val(rebate_amount);

						$("#achieve_amount").val((parseFloat(rebate_amount) + parseFloat(deposit_amount)) * parseFloat(reward_turnover_multiply));

					}

				}

			}

		}

	</script>

</body>

</html>
