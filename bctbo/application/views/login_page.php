<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo (isset($page_title) ? $page_title : '');?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="application-name" content="<?php echo $this->lang->line('system_name');?>">
	<meta rel="apple-touch-icon" href="<?php echo base_url('assets/dist/img/favicon.ico');?>">
	<link rel="icon" type="image/png" href="<?php echo base_url('assets/dist/img/favicon-32.png');?>" sizes="32x32">
	<meta name="msapplication-TileImage" content="<?php echo base_url('assets/dist/img/favicon-144.png');?>">
	<meta name="msapplication-TileColor" content="#2A2A2A">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css');?>">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/adminlte.min.css');?>">
	<!-- icon -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/flag-icon-css/css/flag-icon.min.css');?>">
	<!-- Custom style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/custom.min.css');?>">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<div class="row justify-content-center d-none">
				<div class="col-md-6 col-10"><img src="<?php echo base_url('assets/logo.png?v=1'); ?>" class="img-fluid"></div>
			</div>
			<a href="<?php echo site_url();?>"><?php echo $this->lang->line('system_name');?></a>
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg"><?php echo $this->lang->line('label_login_account');?></p>

				<?php echo form_open('login/submit', array('id' => 'login-form', 'name' => 'login-form'));?>
					<div class="input-group mb-3">
						<input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $this->lang->line('label_username');?>" autocomplete="off" value="<?php if(get_cookie('username')) { echo get_cookie('username'); } ?>">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('label_password');?>" autocomplete="off" value="<?php if(get_cookie('password')) { echo get_cookie('password'); } ?>">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
							  <input name="remember" type="checkbox" value="Remember Me" id="remember" <?php if(get_cookie('username')) { ?> checked="checked" <?php } ?>>
							  <label for="remember"><?php echo $this->lang->line('label_remember_me'); ?></label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<button type="submit" id="login-btn" class="btn btn-primary btn-block"><?php echo $this->lang->line('button_login');?></button>
						</div>
						<!-- /.col -->
					</div>
				<?php echo form_close();?>
				<div class="social-auth-links text-center mt-2">
				<?php
				
					$lang_dropdown = '';
					$lang = json_decode(SYSTEM_LANGUAGES);
					$counter = 0;
					for($i=0;$i<sizeof($lang);$i++)
					{
						if($counter % 3 == 0){
							$lang_dropdown .= '<div class="row pt-2">';
						}
						$lang_dropdown .= '<div class="col">';
						$lang_dropdown .= '<a href="'.site_url('language/change/'.$lang[$i]).'" class="text-decoration-none text-dark">';
						$lang_dropdown .= '<i class="flag-icon flag-icon-'.get_flag_name($lang[$i]).'"></i> '.$this->lang->line('system_lang_'.$lang[$i]);
						$lang_dropdown .= '</a>';
						$lang_dropdown .= '</div>';
						$counter++;
						if($counter % 3 == 0){
							$lang_dropdown .= '</div>';
						}
					}
					echo $lang_dropdown;
				
				?>
				</div>
			</div>
			<!-- /.login-card-body -->
		</div>
		
		<p class="login-box-msg text-muted"><?php echo $this->lang->line('notice_browser_recommended');?></p>
	</div>
	<!-- /.login-box -->

	<!-- jQuery -->
	<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
	<!-- jquery-validation -->
	<script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.min.js');?>"></script>
	<script src="<?php echo base_url('assets/plugins/jquery-validation/additional-methods.min.js');?>"></script>
	<!-- Layer -->
	<script src="<?php echo base_url('assets/plugins/layer/layer.js');?>"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url('assets/dist/js/adminlte.min.js');?>"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#login-form');
			
			<?php if(isset($msg_alert)):?>
			layer.alert('<?php echo $msg_alert;?>', {icon: <?php echo $msg_icon;?>, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
			<?php else:?>
			$('#username').focus();
			<?php endif;?>
			
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
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>'){
									form[0].reset();
									layer.msg('<?php echo $this->lang->line('notice_successful_login');?>');
									location.href = json.msg;
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.password_error != '') {
										message = json.msg.password_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
									
									layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
									
									$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								}
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
						required: true
					},
					password: {
						required: true
					}
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
					},
					password: {
						required: "<?php echo $this->lang->line('error_enter_password');?>",
					}
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.input-group').append(error);
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