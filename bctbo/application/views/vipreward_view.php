<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="<?php echo get_language_code('iso');?>">

<head>

	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">

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

				<div class="row">

					<div class="col-12">

						<div class="card">

							<div class="card-header">

								<form action="<?php echo site_url('vipreward/search');?>" id="vipreward-form" name="vipreward-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">

									<div class="form-group row">

										<div class="col-md-3">

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>

												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">

													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['from_date']) ? $data_search['from_date'] : date('Y-m-d 00:00:00'));?>" data-target="#from_date_click"/>

													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">

														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

													</div>

												</div>

											</div>

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>

												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">

													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['to_date']) ? $data_search['to_date'] : date('Y-m-d 23:59:59'));?>" data-target="#to_date_click"/>

													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">

														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

													</div>

												</div>

											</div>											

										</div>

										<div class="col-md-3">

											<div class="row mb-2">

												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>

												<div class="col-8">

													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">

														<option value="-1" <?php echo (isset($data_search['status']) && ($data_search['status'] == "-1") ? 'selected="selected"' : '""');?>><?php echo $this->lang->line('label_all');?></option>

														<option value="3" <?php echo (isset($data_search['status']) && ($data_search['status'] == 3) ? 'selected="selected"' : '""');?>><?php echo $this->lang->line('label_pending');?></option>

														<option value="<?php echo STATUS_APPROVE;?>" <?php echo (isset($data_search['status']) && ($data_search['status'] == STATUS_APPROVE) ? 'selected="selected"' : '""');?>><?php echo $this->lang->line('status_approved');?></option>

														<option value="<?php echo STATUS_CANCEL;?>" <?php echo (isset($data_search['status']) && ($data_search['status'] == STATUS_CANCEL) ? 'selected="selected"' : '""');?>><?php echo $this->lang->line('status_cancelled');?></option>

													</select>

												</div>

											</div>


											<div class="row mb-2">

												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>

											</div>						

										</div>

										<div class="col-md-3">

											<div class="row mb-2">

												

											</div>											

										</div>

										<div class="col-md-3">

										</div>

									</div>

									<div class="form-group row">

										<div class="col-md-12 col-12">

											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_quick_search');?></label>

										</div>

										<div class="col-md-12 col-12">

											<div class="row mb-2">

											    <div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_thirty_days_from;?>','<?php echo $date_thirty_days_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_thirty_day');?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_month_from;?>','<?php echo $date_last_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_month');?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from;?>','<?php echo $date_yesterday_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday');?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_today_from;?>','<?php echo $date_today_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_today');?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_week_from;?>','<?php echo $date_current_week_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_week');?></button>

												</div>

												<div class="col-md-2 col-2">

													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_month_from;?>','<?php echo $date_current_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_month');?></button>

												</div>

											</div>

										</div>

									</div>

								</form>

							</div>

							<!-- /.card-header -->

							<?php if(permission_validation(PERMISSION_DEPOSIT_REPORT_EXPORT_EXCEL) == TRUE):?>

							<div class="card-header">

								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>

							</div>

							<?php echo form_open('export/vipreward_export_excel', 'class="export" id="export_form"');?>

							<?php echo form_close(); ?>

							<!-- /.card-header -->

							<?php endif;?>

							<!-- /.card-header -->

							<div class="card-body" style="display:none;">

								<table id="vipreward-table" class="table table-striped table-bordered table-hover" style="width: 100%;">

									<thead>

										<tr>

											<th width="100"><?php echo $this->lang->line('label_date');?></th>

											<th width="100"><?php echo $this->lang->line('label_player_username');?></th>

											<th width="100">Old level</th>

											<th width="100">New level</th>

											<th width="100"><?php echo $this->lang->line('label_bet_amount');?></th>

											<th width="100"><?php echo $this->lang->line('label_level_up_bonus');?></th>

											<th width="100"><?php echo $this->lang->line('label_status');?></th>

											<th width="100"><?php echo $this->lang->line('label_updated_by');?></th>

											<th width="100"><?php echo $this->lang->line('label_updated_date');?></th>

											<?php if(permission_validation(PERMISSION_DEPOSIT_UPDATE) == TRUE || permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE):?>

											<th width="90"><?php echo $this->lang->line('label_action');?></th>

											<?php endif;?>

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

		<?php $this->load->view('parts/footer_page');?>

	</div>

	<!-- ./wrapper -->



	<!-- REQUIRED SCRIPTS -->

	<?php $this->load->view('parts/footer_js');?>



	<script type="text/javascript">

		function fastSetDateSearch(from,to){

			$('#from_date').val(from);

			$('#to_date').val(to);

			$('#vipreward-form').submit();

		}

		function exportData(){

			$.ajax({url: '<?php echo base_url("export/vipreward_export_excel_check");?>',

				type: 'get',								

				async: 'true',

				beforeSend: function() {

					layer.load(1);

				},

				complete: function() {

					layer.closeAll('loading');

				},

				success: function (data) {

					let message = '';
					let msg_icon = 2;

					let json = JSON.parse(JSON.stringify(data));

					if(json.status == '<?php echo EXIT_SUCCESS;?>') {

						message = json.msg.general_error;

						msg_icon = 1;

						var form_excel = $('#export_form').submit();

					}else{

						message = json.msg.general_error;

					}

					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});

				},

				error: function (request,error){

				}

			});
		}

		function updateData(id) {

			layer.open({

				type: 2,

				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '380px')],

				fixed: false,

				maxmin: true,

				scrollbar: false,

				title: '<?php echo $this->lang->line('title_vipreward_approve');?>',

				content: '<?php echo base_url('vipreward/edit/');?>' + id

			});

		}

		$(document).ready(function() {

			let is_allowed = true;
			
			let form = $('#vipreward-form');

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

				if(is_allowed == true) {

					is_allowed = false;

					$.ajax({url: form.attr('action'),

						data: { 

								csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 

								from_date:  $('#from_date').val(),

								to_date:  $('#to_date').val(),

								status : $('#status').val()

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

						success: function (data) {

							let json = JSON.parse(JSON.stringify(data));
							let message = '';
							let msg_icon = 2;

							$('meta[name=csrf_token]').attr('content', json.csrfHash);

							if(json.status == '<?php echo EXIT_SUCCESS;?>') {

								let obj = $('.card-body');

								if (obj.is(':visible')) {

									$('#vipreward-table').DataTable().ajax.reload();

								}

								else {

									obj.show();
									loadTable();

								}

							}

							else {

								if(json.msg.from_date_error != '') {

									message = json.msg.from_date_error;

								} else if(json.msg.to_date_error != '') {

									message = json.msg.to_date_error;

								} else if(json.msg.general_error != '') {

									message = json.msg.general_error;

								}

								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});

							}

						},

						error: function (request,error) {

						}

					});  

				}



				return false;

				});
			
			callloadtable();

		});



		function callloadtable(){
			
			let obj = $('.card-body');

			if (obj.is(':visible')) {

				$('#vipreward-table').DataTable().ajax.reload();

			}
			else {

				obj.show();
				loadTable();

			}
		}



		function loadTable(){
			
			$('#vipreward-table').DataTable({

				"processing": true,

				"serverSide": true,

				"scrollX": true,

				"responsive": false,

				"filter": false,

				"pageLength" : 10,

				"lengthMenu": [[1, 10, 25, 50, 100, 500, 1000], [1, 10, 25, 50, 100, 500, 1000]],

				"order": [[0, "desc"]],

				"ajax": {

					"url": "<?php echo site_url('vipreward/listing');?>",

					"dataType": "json",

					"type": "POST",

					"data": function (d) {

						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
						
					},

					"complete": function(response) {

						let json = JSON.parse(JSON.stringify(response));

						if(json.status == 200) {

							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);

						}

					},

				},

				"language": {

					"processing": "<?php echo $this->lang->line('js_processing');?>",

					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",

					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",

					"info": "<?php echo $this->lang->line('js_info');?>",

					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",

					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",

					"search": "<?php echo $this->lang->line('js_search');?>",

					"paginate": {

						"first": "<?php echo $this->lang->line('js_paginate_first');?>",

						"last": "<?php echo $this->lang->line('js_paginate_last');?>",

						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",

						"next": "<?php echo $this->lang->line('js_paginate_next');?>"

					}	

				}

			});

		}

	</script>	

	

</body>

</html>

