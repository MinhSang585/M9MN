<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="<?php echo get_language_code('iso');?>">

<head>

	<?php $this->load->view('parts/head_meta');?>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

	<div class="wrapper">

		<!-- Navbar -->

		<?php $this->load->view('parts/navbar_page');?>

		<!-- /.navbar -->



		<!-- Main Sidebar Container -->

		<?php $this->load->view('parts/sidebar_page');?>

		<!-- /.sidebar -->

	  

		<!-- Content Wrapper. Contains page content -->

		<div class="content-wrapper">

			<!-- Content Header (Page header) -->

			<?php $this->load->view('parts/header_page');?>

			<!-- /.content-header -->



			<!-- Main content -->

			<section class="content">

				<div class="container-fluid">

					<div class="row">

						<!-- left column -->

						<div class="col-md-6">

							<!-- jquery validation -->

							<div class="card card-primary">

								<div class="card-header">

									<h3 class="card-title"><?php echo $this->lang->line('title_change_password');?></h3>

								</div>

								<!-- /.card-header -->

								<!-- form start -->

								<?php echo form_open('profile/submit', array('id' => 'profile-form', 'name' => 'profile-form', 'class' => 'form-horizontal'));?>

									<div class="card-body">

										<div class="form-group row">

											<label for="oldpass" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_current_password');?></label>

											<div class="col-sm-8">

												<input type="password" class="form-control" id="oldpass" name="oldpass" placeholder="<?php echo $this->lang->line('label_password');?>" maxlength="15">

											</div>

										</div>

										<div class="form-group row">

											<label for="password" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_new_password');?></label>

											<div class="col-sm-8">

												<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('label_password');?>" maxlength="15">

											</div>

										</div>

										<div class="form-group row">

											<label for="passconf" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_confirm_new_password');?></label>

											<div class="col-sm-8">

												<input type="password" class="form-control" id="passconf" name="passconf" placeholder="<?php echo $this->lang->line('label_password');?>" maxlength="15">

											</div>

										</div>

									</div>

									<!-- /.card-body -->

									<div class="card-footer">

										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>

									</div>

									<!-- /.card-footer -->

								<?php echo form_close();?>

							</div>

							<!-- /.card -->

						</div>

						<!--/.col (left) -->

						<div class="col-md-6">

							<div class="card card-secondary">

								<div class="card-header">

									<h3 class="card-title"><?php echo $this->lang->line('title_referral');?></h3>

								</div>

								<div class="card-body">

									<div class="form-group row">

										<div id="qrcode"></div>

									</div>

									<div class="form-group row">

										<label class="col-sm-6 col-form-label"><?php echo $this->lang->line('title_referral_link');?></label>

										<div class="col-sm-4">

											<button class="btn btn-secondary clipboard" data-clipboard-text="<?php echo SYSTEM_API_MEMBER_SITE_LINK . '/?code=' . (isset($username) ? $username : '');?>"><?php echo $this->lang->line('button_copy');?></button>

										</div>

									</div>

									<div class="form-group row">

										<div class="col-sm-12">

											<input type="text" disabled class="form-control" placeholder="<?php echo $this->lang->line('title_referral_link');?>" value="<?php echo SYSTEM_API_MEMBER_SITE_LINK . '/?code=' . (isset($username) ? $username : '');?>" id="referral_link">

										</div>

									</div>

									<div class="form-group row">

										<label class="col-sm-6 col-form-label"><?php echo $this->lang->line('title_referral_code');?></label>

										<div class="col-sm-4">

											<button class="btn btn-secondary clipboard" data-clipboard-text="<?php echo (isset($username) ? $username : '');?>"><?php echo $this->lang->line('button_copy');?></button>

										</div>

									</div>

									<div class="form-group row">

										<div class="col-sm-12">

											<input type="text" disabled class="form-control" placeholder="<?php echo $this->lang->line('title_referral_code');?>" value="<?php echo (isset($username) ? $username : '');?>" id="referral_code">

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<!-- /.row -->

				</div><!-- /.container-fluid -->

			</section>

			<!-- /.content -->

		</div>

		<!-- /.content-wrapper -->



		<!-- Main Footer -->

		<?php $this->load->view('parts/footer_page');?>

	</div>

	<!-- ./wrapper -->



	<!-- REQUIRED SCRIPTS -->

	<?php $this->load->view('parts/footer_js');?>



	<script type="text/javascript">

		$(document).ready(function() {

			var is_allowed = true;

			var form = $('#profile-form');

			

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

								

								if(json.status == '<?php echo EXIT_SUCCESS;?>'){

									message = json.msg;

									msg_icon = 1;

									form[0].reset();

								}

								else {

									if(json.msg.oldpass_error != '') {

										message = json.msg.oldpass_error;

									}

									else if(json.msg.password_error != '') {

										message = json.msg.password_error;

									}

									else if(json.msg.passconf_error != '') {

										message = json.msg.passconf_error;

									}

									else if(json.msg.general_error != '') {

										message = json.msg.general_error;

									}

								}

								

								layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});

								

								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

							},

							error: function (request,error) {

							}

						});  

					}

				}

			});

			

			form.validate({

				rules: {

					oldpass: {

						required: true

					},

					password: {

						required: true,

						rangelength: [6, 15]

					},

					passconf: {

						required: true

					}

				},

				messages: {

					oldpass: {

						required: "<?php echo $this->lang->line('error_enter_current_password');?>",

					},

					password: {

						required: "<?php echo $this->lang->line('error_enter_new_password');?>",

						rangelength: "<?php echo $this->lang->line('error_invalid_new_password');?>",

					},

					passconf: {

						required: "<?php echo $this->lang->line('error_enter_confirm_new_password');?>",

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

	<script type="text/javascript">

		var qrcode = new QRCode(document.getElementById("qrcode"), {

			text: "<?php echo SYSTEM_API_MEMBER_SITE_LINK . '/?code=' . (isset($username) ? $username : '');?>",

			width: 128,

			height: 128,

			colorDark : "#000000",

			colorLight : "#ffffff",

			correctLevel : QRCode.CorrectLevel.H

		});

	</script>

	<script type="text/javascript">

		new ClipboardJS('.clipboard');

	</script>

</body>

</html>

