<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>
<!DOCTYPE html>

<html lang="<?php echo get_language_code('iso'); ?>">

<head>

	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">

	<?php $this->load->view('parts/head_meta'); ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

	<div class="wrapper">

		<!-- Navbar -->

		<?php $this->load->view('parts/navbar_page'); ?>

		<!-- /.navbar -->



		<!-- Main Sidebar Container -->

		<?php $this->load->view('parts/sidebar_page'); ?>

		<!-- /.sidebar -->



		<!-- Content Wrapper. Contains page content -->

		<div class="content-wrapper">

			<!-- Content Header (Page header) -->

			<?php $this->load->view('parts/header_page'); ?>

			<!-- /.content-header -->



			<!-- Main content -->

			<section class="content">

				<div class="row">

					<div class="col-12">

						<div class="card">

							<div class="card-header">

								<form action="<?php echo site_url('log/player_log_search'); ?>" id="log-form" name="log-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">

									<div class="form-group row">

										<div class="col-md-3">

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date'); ?></label>

												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">

													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 00:00:00'); ?>" data-target="#from_date_click" />

													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">

														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

													</div>

												</div>

											</div>

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date'); ?></label>

												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">

													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 23:59:59'); ?>" data-target="#to_date_click" />

													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">

														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

													</div>

												</div>

											</div>

										</div>

										<div class="col-md-3">

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal">Log Type</label>

												<div class="col-8">

													<select class="form-control form-control-sm select2bs4 col-12" id="login_type" name="login_type">
													
														<option value="0">All</option>

														<option value="1">Player Login</option>

														<option value="2">Player logout</option>

														<option value="3">Player change password</option>

														<option value="4">Player add bank</option>

														<option value="5">Player deposit</option>

														<option value="6">Player withdraw</option>

														<option value="7">Player edit profile information</option>

														<option value="8">Player register</option>

													</select>

												</div>

											</div>

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username'); ?></label>

												<div class="col-8">

													<input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php echo (isset($data_search['username']) ? $data_search['username'] : ''); ?>">

												</div>

											</div>

										</div>

										<div class="col-md-3">

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_platform'); ?></label>

												<div class="col-8">

													<select class="form-control form-control-sm select2bs4 col-12" id="platform" name="platform">

														<option value="0"><?php echo $this->lang->line('label_all'); ?></option>

														<option value="<?php echo PLATFORM_WEB; ?>"><?php echo $this->lang->line('label_website'); ?></option>

														<option value="<?php echo PLATFORM_MOBILE_WEB; ?>"><?php echo $this->lang->line('label_mobile_web'); ?></option>

														<option value="<?php echo PLATFORM_APP_ANDROID; ?>"><?php echo $this->lang->line('label_mobile_app_android'); ?></option>

														<option value="<?php echo PLATFORM_APP_IOS; ?>"><?php echo $this->lang->line('label_mobile_app_ios'); ?></option>

													</select>

												</div>

											</div>

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_ip'); ?></label>

												<div class="col-8">

													<input type="text" class="form-control form-control-sm" id="ip_address" name="ip_address" value="">

												</div>

											</div>

										</div>

										<div class="col-md-3">

											<div class="row mb-2">

												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search'); ?></button>

											</div>

										</div>

									</div>

									<div class="form-group row">

										<div class="col-md-12 col-12">

											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_quick_search'); ?></label>

										</div>

										<div class="col-md-12 col-12">

											<div class="row mb-2">

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_month_from; ?>','<?php echo $date_last_month_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_month'); ?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_week_from; ?>','<?php echo $date_last_week_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_week'); ?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from; ?>','<?php echo $date_yesterday_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday'); ?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_today_from; ?>','<?php echo $date_today_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_today'); ?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_week_from; ?>','<?php echo $date_current_week_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_week'); ?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_month_from; ?>','<?php echo $date_current_month_to; ?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_month'); ?></button>

												</div>

											</div>

										</div>

									</div>

								</form>

							</div>

							<!-- /.card-header -->

							<div class="card-body">

								<table id="log-table" class="table table-striped table-bordered table-hover" style="width:100%;">

									<thead>

										<tr>

											<th><?php echo $this->lang->line('label_hashtag'); ?></th>

											<th><?php echo $this->lang->line('label_last_login_date'); ?></th>

											<th><?php echo $this->lang->line('label_username'); ?></th>

											<th><?php echo $this->lang->line('label_login_ip'); ?></th>

											<th>Log Type</th>

											<th><?php echo $this->lang->line('label_platform'); ?></th>

											<?php if (permission_validation(PERMISSION_PLAYER_LOG_VIEW) == TRUE) : ?>

												<th><?php echo $this->lang->line('label_action'); ?></th>

											<?php endif; ?>

										</tr>

									</thead>

									<tbody></tbody>

								</table>

							</div>

						</div>

					</div>

				</div>

			</section>

			<!-- /.content -->

		</div>

		<!-- /.content-wrapper -->



		<!-- Main Footer -->

		<?php $this->load->view('parts/footer_page'); ?>

	</div>

	<!-- ./wrapper -->



	<!-- REQUIRED SCRIPTS -->

	<?php $this->load->view('parts/footer_js'); ?>


	<script type="text/javascript">
		function fastSetDateSearch(from, to) {

			$('#from_date').val(from);

			$('#to_date').val(to);

			$('#log-form').submit();

		}

		$(document).ready(function() {

			$('.select2').select2();

			var is_allowed = true;

			var form = $('#log-form');



			$('#from_date_click').datetimepicker({

				format: 'YYYY-MM-DD HH:mm:ss',

				icons: {

					time: "fa fa-clock"

				}

			});



			$('#to_date_click').datetimepicker({

				format: 'YYYY-MM-DD HH:mm:ss',

				icons: {

					time: "fa fa-clock"

				}

			});



			form.submit(function(e) {

				if (is_allowed == true) {

					is_allowed = false;



					$.ajax({
						url: form.attr('action'),

						data: {

							csrf_bctp_bo_token: $('meta[name=csrf_token]').attr('content'),

							from_date: $('#from_date').val(),

							to_date: $('#to_date').val(),

							login_type: $('#login_type').val(),

							username: $('#username').val(),

							platform: $('#platform').val(),

							ip_address: $('#ip_address').val(),

						},

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

							var message = json.msg;

							var msg_icon = 2;



							$('meta[name=csrf_token]').attr('content', json.csrfHash);



							if (json.status == '<?php echo EXIT_SUCCESS; ?>') {

								var obj = $('.card-body');

								if (obj.is(':visible')) {

									$('#log-table').DataTable().ajax.reload();

								} else {

									obj.show();

									loadTable();

								}
							} else {

								if (json.msg.from_date_error != '') {

									message = json.msg.from_date_error;

								} else if (json.msg.to_date_error != '') {

									message = json.msg.to_date_error;

								} else if (json.msg.general_error != '') {

									message = json.msg.general_error;

								}

								parent.layer.alert(message, {
									icon: msg_icon,
									title: '<?php echo $this->lang->line('label_info'); ?>',
									btn: '<?php echo $this->lang->line('button_close'); ?>'
								});

							}

						},

						error: function(request, error) {

						}

					});

				}



				return false;

			});



			$('#log-table').DataTable({

				"processing": true,

				"serverSide": true,

				"scrollX": true,

				"responsive": false,

				"filter": false,

				"pageLength": 10,

				"order": [
					[0, "desc"]
				],

				"ajax": {

					"url": "<?php echo site_url('log/player_log_listing'); ?>",

					"dataType": "json",

					"type": "POST",

					"data": function(d) {

						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');

					},

					"complete": function(response) {

						var json = JSON.parse(JSON.stringify(response));

						if (json.status == 200) {

							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);

						}

					},

				},

				"columnDefs": [

					{
						"targets": [0],
						"visible": false
					}

				],

				"language": {

					"processing": "<?php echo $this->lang->line('js_processing'); ?>",

					"lengthMenu": "<?php echo $this->lang->line('js_length_menu'); ?>",

					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords'); ?>",

					"info": "<?php echo $this->lang->line('js_info'); ?>",

					"infoEmpty": "<?php echo $this->lang->line('js_info_empty'); ?>",

					"infoFiltered": "<?php echo $this->lang->line('info_filtered'); ?>",

					"search": "<?php echo $this->lang->line('js_search'); ?>",

					"paginate": {

						"first": "<?php echo $this->lang->line('js_paginate_first'); ?>",

						"last": "<?php echo $this->lang->line('js_paginate_last'); ?>",

						"previous": "<?php echo $this->lang->line('js_paginate_previous'); ?>",

						"next": "<?php echo $this->lang->line('js_paginate_next'); ?>"

					}

				}

			});

		});

		function displayContent(id) {

			layer.open({

				type: 2,

				area: [((browser_width < 600) ? '100%' : '100%'), ((browser_width < 600) ? '100%' : '100%')],

				fixed: false,

				maxmin: true,

				scrollbar: false,

				title: '<?php echo $this->lang->line('title_player_log_report'); ?>',

				content: '<?php echo base_url('log/playerContent/'); ?>' + id

			});

		}
	</script>


</body>

</html>