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

					<!-- Today Report -->
					<div class="home_dashboar">
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
					</div>
					
					<!-- Weekly Report -->
					<div class="home_dashboar">
						<div class="row">
							<div class="col-12 col-sm-6 col-md-3">
								<div class="info-box">
									<span class="info-box-icon bg-info elevation-1"><i class="fas fa-donate"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><?php echo $this->lang->line('label_week_deposits');?></span>
										<span class="info-box-number" id="val-week-deposit"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-week-deposit">
										<i class="fas fa-2x fa-spinner fa-pulse"></i>
									</div>
								</div>
								<!-- /.info-box -->
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="info-box">
									<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-coins"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><?php echo $this->lang->line('label_week_promotion');?></span>
										<span class="info-box-number" id="val-week-promotion"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-week-promotion">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_week_bonus');?></span>
										<span class="info-box-number" id="val-week-bonus"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-week-bonus">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_week_withdrawals');?></span>
										<span class="info-box-number" id="val-week-wd"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-week-wd">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_week_profit');?></span>
										<span class="info-box-number" id="val-week-profit"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-week-profit">
										<i class="fas fa-2x fa-spinner fa-pulse"></i>
									</div>
								</div>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							
							<!-- /.col -->
						</div>
					</div>
					
					<!-- Month Report -->
					<div class="home_dashboar">
						<div class="row">
							<div class="col-12 col-sm-6 col-md-3">
								<div class="info-box">
									<span class="info-box-icon bg-info elevation-1"><i class="fas fa-donate"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><?php echo $this->lang->line('label_month_deposits');?></span>
										<span class="info-box-number" id="val-month-deposit"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-month-deposit">
										<i class="fas fa-2x fa-spinner fa-pulse"></i>
									</div>
								</div>
								<!-- /.info-box -->
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="info-box">
									<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-coins"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><?php echo $this->lang->line('label_month_promotion');?></span>
										<span class="info-box-number" id="val-month-promotion"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-month-promotion">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_month_bonus');?></span>
										<span class="info-box-number" id="val-month-bonus"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-month-bonus">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_month_withdrawals');?></span>
										<span class="info-box-number" id="val-month-wd"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-month-wd">
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
										<span class="info-box-text"><?php echo $this->lang->line('label_month_profit');?></span>
										<span class="info-box-number" id="val-month-profit"></span>
									</div>
									<!-- /.info-box-content -->
									<div class="overlay dark" id="load-month-profit">
										<i class="fas fa-2x fa-spinner fa-pulse"></i>
									</div>
								</div>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							
							<!-- /.col -->
						</div>
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
	<script type="text/javascript">
	function week_deposit(){
		$.ajax({url: "<?php echo site_url('home/week_deposit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-week-deposit').show();
				$('#val-week-deposit').html('-');
			},
			complete: function() {
				$('#load-week-deposit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-week-deposit').html(json.result);
			}
		});
	}
	function week_promotion(){
		$.ajax({url: "<?php echo site_url('home/today_promotion'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-week-promotion').show();
				$('#val-week-promotion').html('-');
			},
			complete: function() {
				$('#load-week-promotion').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-week-promotion').html(json.result);
			}
		});
	}
	function week_bonus(){
		$.ajax({url: "<?php echo site_url('home/week_bonus'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-week-bonus').show();
				$('#val-week-bonus').html('-');
			},
			complete: function() {
				$('#load-week-bonus').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-week-bonus').html(json.result);
			}
		});
	}
	function week_withdraw(){
		$.ajax({url: "<?php echo site_url('home/week_withdraw'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-week-wd').show();
				$('#val-week-wd').html('-');
			},
			complete: function() {
				$('#load-week-wd').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-week-wd').html(json.result);
			}
		});
	}
	function week_profit(){
		$.ajax({url: "<?php echo site_url('home/week_profit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-week-profit').show();
				$('#val-week-profit').html('-');
			},
			complete: function() {
				$('#load-week-profit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-week-profit').html(json.result);
			}
		});
	}
	
	$(document).ready(function() {				
		week_deposit();
		week_promotion();
		week_bonus();
		week_withdraw();
		week_profit();
	});
</script>
	<script type="text/javascript">
	function month_deposit(){
		$.ajax({url: "<?php echo site_url('home/month_deposit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-month-deposit').show();
				$('#val-month-deposit').html('-');
			},
			complete: function() {
				$('#load-month-deposit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-month-deposit').html(json.result);
			}
		});
	}
	function month_promotion(){
		$.ajax({url: "<?php echo site_url('home/today_promotion'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-month-promotion').show();
				$('#val-month-promotion').html('-');
			},
			complete: function() {
				$('#load-month-promotion').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-month-promotion').html(json.result);
			}
		});
	}
	function month_bonus(){
		$.ajax({url: "<?php echo site_url('home/month_bonus'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-month-bonus').show();
				$('#val-month-bonus').html('-');
			},
			complete: function() {
				$('#load-month-bonus').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-month-bonus').html(json.result);
			}
		});
	}
	function month_withdraw(){
		$.ajax({url: "<?php echo site_url('home/month_withdraw'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-month-wd').show();
				$('#val-month-wd').html('-');
			},
			complete: function() {
				$('#load-month-wd').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-month-wd').html(json.result);
			}
		});
	}
	function month_profit(){
		$.ajax({url: "<?php echo site_url('home/month_profit'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				$('#load-month-profit').show();
				$('#val-month-profit').html('-');
			},
			complete: function() {
				$('#load-month-profit').hide();
			},
			error: function (request,error) {
				//console.log(request);				
			},
			success: function(json){				
				$('#val-month-profit').html(json.result);
			}
		});
	}
	
	$(document).ready(function() {				
		month_deposit();
		month_promotion();
		month_bonus();
		month_withdraw();
		month_profit();
	});
</script>
</body>
</html>