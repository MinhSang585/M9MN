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
					<div id="card-panel" class="col-12">
						<div id="card-table-1" class="card">
							<div class="card-header">
								<?php if(permission_validation(PERMISSION_LEVEL_ADD) == TRUE):?>
								<div class="form-group row">
									<h3 class="card-title"><button onclick="addData()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
								</div>
								<?php endif;?>
								<?php if(permission_validation(PERMISSION_LEVEL_ADD) == TRUE):?>

								<div class="form-group row">

									<label for="calculate_type" class="col-2 col-form-label"><?php echo $this->lang->line('label_calculate_type');?></label>

									<div class="col-2">

										<select class="select2bs4 form-control " id="calculate_type" name="calculate_type" data-placeholder="<?php echo $this->lang->line('label_calculate_type');?>">
											<option value="0" <?php if($calculate_type == 0){ ?>selected<?php } ?>><?php echo $this->lang->line('label_select');?></option>
											<option value="<?=RANKING_CALCULATE_TYPE_EVERY_DAY_ONCE?>" <?php if($calculate_type == RANKING_CALCULATE_TYPE_EVERY_DAY_ONCE){ ?>selected<?php } ?>><?php echo $this->lang->line('ranking_calculate_type_every_day_once');?></option>
											<option value="<?=RANKING_CALCULATE_TYPE_EVERY_WEEK_ONCE?>" <?php if($calculate_type == RANKING_CALCULATE_TYPE_EVERY_WEEK_ONCE){ ?>selected<?php } ?>><?php echo $this->lang->line('ranking_calculate_type_every_week_once');?></option>
											<option value="<?=RANKING_CALCULATE_TYPE_EVERY_MONTH_ONCE?>" <?php if($calculate_type == RANKING_CALCULATE_TYPE_EVERY_MONTH_ONCE){ ?>selected<?php } ?>><?php echo $this->lang->line('ranking_calculate_type_every_month_once');?></option>
										</select>

									</div>

									<div class="col-3">

										<h3 class="card-title"><button onclick="updateCalculateType()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-edit nav-icon"></i> <?php echo $this->lang->line('button_update');?></button></h3>
									
									</div>

								</div>

								<?php endif;?>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
								<table id="level-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_ranking_name');?></th>
											<th><?php echo 'Daily Withdrawal Frequency' ?></th>
											<th><?php echo $this->lang->line('label_ranking_number');?></th>
											<th><?php echo $this->lang->line('label_player_count');?></th>
											<th><?php echo $this->lang->line('label_deposit_amount_from');?></th>
											<th><?php echo $this->lang->line('label_deposit_amount_to');?></th>
											<th><?php echo $this->lang->line('label_target_amount_from');?></th>
											<th><?php echo $this->lang->line('label_target_amount_to');?></th>
											<th><?php echo $this->lang->line('label_reward_amount');?></th>
										<?php
											$game_type = get_game_type_all();

											if(sizeof($game_type)>0){
												foreach($game_type as $game_type_row){
													echo "<th>".$this->lang->line(get_game_type($game_type_row))."</th>";
												}
											}
										?>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_LEVEL_UPDATE) == TRUE || permission_validation(PERMISSION_LEVEL_DELETE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<?php if(permission_validation(PERMISSION_LEVEL_EXECUTE_VIEW) == TRUE):?>
						<div id="card-table-2" class="card">
							<div class="card-header">								
								<?php if(permission_validation(PERMISSION_LEVEL_EXECUTE_ADD) == TRUE):?>
								<form action="<?php echo site_url('level/level_execute_submit');?>" id="level-form" name="level-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-12 mb-2">
											<h2 class="m-0 text-dark"><?php echo $this->lang->line('title_player_rating_job');?></h2>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d', strtotime('monday last week'));?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>										
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m-d 23:59:59', strtotime('sunday last week'));?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>								
										</div>
										<div class="col-md-6">
											<div class="row mb-2">
												<label class="col-2 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_remark');?></label>
												<div class="col-10">
													<input type="text" class="form-control form-control-sm" id="remark" name="remark" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-12">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_quick_search');?></label>
										</div>
										<div class="col-md-12 col-12">
											<div class="row mb-2">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_two_week_from_date;?>','<?php echo $date_last_two_week_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_two_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_week_from_date;?>','<?php echo $date_last_week_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from_date;?>','<?php echo $date_yesterday_to_date;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday');?></button>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_start_calculate');?></button>
									</div>
								</form>
								<?php endif;?>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
								<table id="level-schedule-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_job_id');?></th>
											<th><?php echo $this->lang->line('label_schedule_start');?></th>
											<th><?php echo $this->lang->line('label_schedule_end');?></th>
											<th><?php echo $this->lang->line('label_level_up_count');?></th>
											<th><?php echo $this->lang->line('label_level_down_count');?></th>
											<th><?php echo $this->lang->line('status_pending');?></th>
											<th><?php echo $this->lang->line('status_approved');?></th>
											<th><?php echo $this->lang->line('status_cancelled');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_remark');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_LEVEL_EXECUTE_UPDATE) == TRUE || permission_validation(PERMISSION_LEVEL_LOG_VIEW) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<?php endif;?>
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
		}

		$(document).ready(function() {
			var is_allowed = true;
			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM-DD',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD',
                icons: {
                    time: "fa fa-clock"
                }
            });

            var form = $('#level-form');
          	form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: {
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
							from_date:  $('#from_date').val(),
							to_date : $('#to_date').val(),
							remark : $('#remark').val(),
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
							var json = JSON.parse(JSON.stringify(data));
							console.log(json);
							var message = '';
							var msg_icon = 2;
							
							$('meta[name=csrf_token]').attr('content', json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								$('#level-schedule-table').DataTable().ajax.reload();
							}
							else {
								if(json.msg.from_date_error != '') {
									message = json.msg.from_date_error;
								}else if(json.msg.to_date_error != '') {
									message = json.msg.to_date_error;
								}else if(json.msg.remark_error != '') {
									message = json.msg.remark_error;
								}else if(json.msg.general_error != '') {
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

			$('#level-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('level/listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": false}
				],
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
			
			<?php if(permission_validation(PERMISSION_LEVEL_EXECUTE_VIEW) == TRUE):?>
			$('#level-schedule-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('level/level_execute_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
						}
					},
				},
				"columnDefs": [
				],
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
			<?php endif;?>
		});
		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_ranking');?>',
				content: '<?php echo base_url('level/add/');?>'
			});
		}
		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_ranking_setting');?>',
				content: '<?php echo base_url('level/edit/');?>' + id
			});
		}
		
		function deleteData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '380px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_ranking_setting');?>',
				content: '<?php echo base_url('level/delete/');?>' + id
			});
		}

		function approveData(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('level/level_execute_approve/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#level-schedule-table').DataTable().ajax.reload();
					}
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}

		function viewLogData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_level_log');?>',
				content: '<?php echo base_url('level/level_log/');?>' + id
			});
		}
		
		function updateCalculateType(){
			
			let calculate_type = $("#calculate_type").val();
			$.ajax({url: '<?php echo site_url('level/calculate_type_update');?>',
					data: {
						calculate_type: calculate_type,
						csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'),
					},
					type: 'post',                  
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');
					},
					success: function (data) {
						let json = JSON.parse(JSON.stringify(data));
						let message = json.msg;
						let msg_icon = 2;
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							msg_icon = 1;
						} else {
							if(json.msg.general_error != '') {
								message = json.msg.general_error;
							}
							if(json.msg.calculate_type_error != '') {
								message = json.msg.calculate_type_error;
							}
						}
						
						layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
					},
					error: function (request,error) {
					}
				});
		}
	</script>
</body>
</html>