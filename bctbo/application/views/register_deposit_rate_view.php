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
								<form action="<?php echo site_url('report/register_deposit_rate_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y-m');?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>										
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>										
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/register_deposit_rate_export', 'class="export" id="export_form_1"');?>
							<?php echo form_close(); ?>
							<?php endif;?>
							<div class="card-body" style="display:none;">
								<table id="report-table-1" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120" class="bg-info"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="80" class="bg-info"><?php echo $this->lang->line('label_level');?></th>
											<th width="100" class="bg-info"><?php echo $this->lang->line('label_agent_username');?></th>
											<th width="100" class="bg-info"><?php echo $this->lang->line('label_agent');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_register_count');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_member_deposit');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_member_deposit_rate');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_first_deposit');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_first_deposit_rate');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_second_or_more_deposit');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_second_or_more_deposit_rate');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_third_or_more_deposit');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_third_or_more_deposit_rate');?></th>
											<th width="150" class="bg-danger"><?php echo $this->lang->line('label_register_deposit_rate_no_deposit');?></th>
											<th width="150" class="bg-danger"><?php echo $this->lang->line('label_register_deposit_rate_churn_rate');?></th>
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
			$('#report-form').submit();
		}
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#report-form');
			
			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM',
                icons: {
                    time: "fa fa-clock"
                }
            });

            $("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
							from_date:  $('#from_date').val(),
							username : $('#username').val(),
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
							var message = json.msg;
							var msg_icon = 2;
							
							$('meta[name=csrf_token]').attr('content', json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								var obj = $('.card-body');
								
								if (obj.is(':visible')) {
									for(var i=2;i<=table_num;i++) {
										$('#card-table-' + i).remove();
										$('#p-card-table-1').remove();
									}
								
									table_num = 1;
									$('#report-table-1').DataTable().ajax.reload();
								}
								else {
									obj.show();
									loadTable();
								}
							}
							else {
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							}
						},
						error: function (request,error) {
						}
					});  
				}
				
				return false;
			});
		});
		
		function loadTable(){
			$('#report-table-1').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"ordering": false,
				"pageLength" : 10,
				"ajax": {
					"url": "<?php echo site_url('report/register_deposit_rate_listing');?>",
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
					{"targets": [0], "visible": false},
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
				},
				"rowCallback": function (row, data, index) {
					$('td', row).eq(16).addClass('bg-danger');
					$('td', row).eq(17).addClass('bg-danger');

					//align right
					$('td', row).eq(3).addClass('text-right');
					$('td', row).eq(4).addClass('text-right');
					$('td', row).eq(5).addClass('text-right');
					$('td', row).eq(6).addClass('text-right');
					$('td', row).eq(7).addClass('text-right');
					$('td', row).eq(8).addClass('text-right');
					$('td', row).eq(9).addClass('text-right');
					$('td', row).eq(10).addClass('text-right');
					$('td', row).eq(11).addClass('text-right');
					$('td', row).eq(12).addClass('text-right');
					$('td', row).eq(13).addClass('text-right');
					$('td', row).eq(14).addClass('text-right');
					$('td', row).eq(15).addClass('text-right');
					$('td', row).eq(16).addClass('text-right');
					$('td', row).eq(17).addClass('text-right');
                }
			});
		}
		
		var is_allowed_2 = true;
		var table_num = 1;
		
		function getDownline(username, num) {
			if(is_allowed_2 == true) {
				is_allowed_2 = false;

				var next_num = (num + 1);
				for(var i=next_num;i<=table_num;i++) {
					$('#card-table-' + i).remove();
				}
				
				$('#p-card-table-1').remove();
			
				table_num = next_num;
				layer.load(1);
				load_table_downline(table_num, username);
				$('html, body').animate({scrollTop:$(document).height()}, 800);
			}
		}

		function load_table_downline(table_num, username){
			$.ajax({url: '<?php echo base_url('report/register_deposit_rate_downline/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.closeAll('loading');
					is_allowed_2 = true;
				},
				complete: function() {
				},
				success: function (data) {
					//load_table_player(table_num, username);
					if(data != '') {
						$('#card-panel').append(data);
						load_table_downline_total(table_num, username);
					}	
				},
				error: function (request,error) {
				}
			}); 
		}

		function load_table_downline_total(table_num, username){
			$.ajax({url: '<?php echo base_url('report/register_deposit_rate_downline_total/');?>' + table_num + '/' + username,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
				},
				complete: function() {
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));

					$('meta[name=csrf_token]').attr('content', json.csrfHash);
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						$('span#downline_total_register_count_'+table_num).html(parseFloat(json.total_data.total_register_count).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_member_deposit_'+table_num).html(parseFloat(json.total_data.total_member_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_member_deposit_amount_'+table_num).html(parseFloat(json.total_data.total_member_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_member_deposit_rate_'+table_num).html(parseFloat(json.total_data.total_member_deposit_rate).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+"%");
						$('span#downline_total_first_deposit_'+table_num).html(parseFloat(json.total_data.total_first_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_first_deposit_amount_'+table_num).html(parseFloat(json.total_data.total_first_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_first_deposit_rate_'+table_num).html(parseFloat(json.total_data.total_first_deposit_rate).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+"%");
						$('span#downline_total_second_or_more_deposit_'+table_num).html(parseFloat(json.total_data.total_second_or_more_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_second_or_more_deposit_amount_'+table_num).html(parseFloat(json.total_data.total_second_or_more_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_second_or_more_deposit_rate_'+table_num).html(parseFloat(json.total_data.total_second_or_more_deposit_rate).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+"%");						
						$('span#downline_total_third_or_more_deposit_'+table_num).html(parseFloat(json.total_data.total_third_or_more_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_third_or_more_deposit_amount_'+table_num).html(parseFloat(json.total_data.total_third_or_more_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_third_or_more_deposit_rate_'+table_num).html(parseFloat(json.total_data.total_third_or_more_deposit_rate).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+"%");
						$('span#downline_total_no_deposit_'+table_num).html(parseFloat(json.total_data.total_no_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#downline_total_churn_rate_'+table_num).html(parseFloat(json.total_data.total_churn_rate).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+"%");
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function exportData(num = 0,username = 0){
			$.ajax({url: '<?php echo base_url("export/register_deposit_rate_export_check");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var message = '';
					var msg_icon = 2;
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						message = json.msg.general_error;
						msg_icon = 1;
						if(num == 0){
							$('#export_form_1').attr('action', '<?php echo base_url('export/register_deposit_rate_export')?>');
						}else{
							$('#export_form_1').attr('action', '<?php echo base_url('export/register_deposit_rate_export/')?>'+num+"/"+username);
						}
						var form_excel = $('#export_form_1').submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}


		function getPlayerHaveDeposit(username,table_num,type){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_register_deposit_rate_report');?>',
				content: '<?php echo base_url('report/register_deposit_rate_player_table/');?>' + username + '/' + table_num + '/' + type
			});
		}
	</script>	
</body>
</html>
