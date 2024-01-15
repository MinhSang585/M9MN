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
			<?php
			if(permission_validation(PERMISSION_HOME) == TRUE){
			?>
			<section class="content">
				<div class="container-fluid">
					<!-- Info boxes -->
					<div class="row">
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box">
								<span class="info-box-icon bg-info elevation-1"><i class="fas fa-donate"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_today_deposits');?></span>
									<span class="info-box-number" id="val-deposit"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-deposit">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box">
								<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-coins"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_today_promotion');?></span>
									<span class="info-box-number" id="val-promotion"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-promotion">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- fix for small devices only -->
						<div class="clearfix hidden-md-up"></div>
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box">
								<span class="info-box-icon bg-teal elevation-1"><i class="fas fa-coins"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_today_bonus');?></span>
									<span class="info-box-number" id="val-bonus"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-bonus">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box mb-3">
								<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_today_withdrawals');?></span>
									<span class="info-box-number" id="val-wd"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-wd">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<!-- fix for small devices only -->
						<div class="clearfix hidden-md-up"></div>
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box mb-3">
								<span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_today_profit');?></span>
									<span class="info-box-number" id="val-profit"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-profit">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box mb-3">
								<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_new_players');?></span>
									<span class="info-box-number" id="val-nu"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-nu">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<!-- fix for small devices only -->
						<div class="clearfix hidden-md-up"></div>
						<!-- /.col -->
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box mb-3">
								<span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user"></i></span>
								<div class="info-box-content">
									<span class="info-box-text"><?php echo $this->lang->line('label_active_players');?></span>
									<span class="info-box-number" id="val-active"></span>
								</div>
								<!-- /.info-box-content -->
								<div class="overlay dark" id="load-active">
									<i class="fas fa-2x fa-spinner fa-pulse"></i>
								</div>
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div><!--/. container-fluid -->
			</section>
			<?php } ?>
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
	function today_deposit(){
		$.ajax({url: "<?php echo site_url('home/today_deposit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-deposit').show();
				$('#val-deposit').html('-');
			},
			complete: function() {
				$('#load-deposit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-deposit').html(json.result);
			}
		});
	}
	function today_promotion(){
		$.ajax({url: "<?php echo site_url('home/today_promotion'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-promotion').show();
				$('#val-promotion').html('-');
			},
			complete: function() {
				$('#load-promotion').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-promotion').html(json.result);
			}
		});
	}
	function today_bonus(){
		$.ajax({url: "<?php echo site_url('home/today_bonus'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-bonus').show();
				$('#val-bonus').html('-');
			},
			complete: function() {
				$('#load-bonus').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-bonus').html(json.result);
			}
		});
	}
	function today_withdraw(){
		$.ajax({url: "<?php echo site_url('home/today_withdraw'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-wd').show();
				$('#val-wd').html('-');
			},
			complete: function() {
				$('#load-wd').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-wd').html(json.result);
			}
		});
	}
	function today_profit(){
		$.ajax({url: "<?php echo site_url('home/today_profit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-profit').show();
				$('#val-profit').html('-');
			},
			complete: function() {
				$('#load-profit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-profit').html(json.result);
			}
		});
	}
	function today_user(){
		$.ajax({url: "<?php echo site_url('home/today_user'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-nu').show();
				$('#val-nu').html('-');
			},
			complete: function() {
				$('#load-nu').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-nu').html(json.result);
			}
		});
	}
	function today_active_user(){
		$.ajax({url: "<?php echo site_url('home/today_active_user'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-active').show();
				$('#val-active').html('-');
			},
			complete: function() {
				$('#load-active').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-active').html(json.result);
			}
		});
	}
	function active_user_deposit(){
		$.ajax({url: "<?php echo site_url('home/active_user_deposit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-active').show();
				$('#val-active').html('-');
			},
			complete: function() {
				$('#load-active').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-active').html(json.result);
			}
		});
	}
	$(document).ready(function() {				
		today_deposit();
		today_promotion();
		today_bonus();
		today_withdraw();
		today_profit();
		today_user();
		//today_active_user();
		active_user_deposit();
	});
	</script>
</body>
</html>