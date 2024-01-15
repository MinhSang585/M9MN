<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12 col-md-6">
						<!-- jquery validation -->
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('title_register_deposit_rate_report_player');?></label>
							</div>
							<div class="card-body">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="50" class="bg-info"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_agent_username');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_username');?></th>
											<th width="100" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_deposit_count');?></th>
											<th width="300" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th width="350" colspan="3" class="text-right bg-info"><?php echo $this->lang->line('label_total');?>:</th>
											<th width="100" class="text-right bg-info"><span id="total_member_deposit">0</span></th>
											<th width="300" class="text-right bg-info"><span id="total_member_deposit_amount">0</span></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<!--/.col (left) -->
					<!-- right column -->
					<div class="col-12 col-md-6">
						<!-- jquery validation -->
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('title_register_deposit_rate_report_agent');?></label>
							</div>
							<div class="card-body">
								<table id="report_agent-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_agent_username');?></th>
											<th width="150" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_register_count');?></th>
											<th width="100" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_deposit_count');?></th>
											<th width="300" class="bg-info"><?php echo $this->lang->line('label_register_deposit_rate_total_deposit_amount');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th width="200" class="bg-info"><?php echo $this->lang->line('label_total');?>:</th>
											<th width="100" class="text-right bg-info"><span id="total_agent_register_count">0</span></th>
											<th width="100" class="text-right bg-info"><span id="total_agent_deposit">0</span></th>
											<th width="300" class="text-right bg-info"><span id="total_agent_deposit_amount">0</span></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<!--/.col (right) -->
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
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[1, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/register_deposit_rate_player_listing/').$username."/".$num.'/'.$type;?>",
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
				},
				"rowCallback": function (row, data, index) {
					//align right
					$('td', row).eq(2).addClass('text-right');
					$('td', row).eq(3).addClass('text-right');
					$('td', row).eq(4).addClass('text-right');
                }
			});
			$('#report_agent-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[1, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('report/register_deposit_rate_agent_listing/').$username."/".$num.'/'.$type;?>",
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
				},
				"rowCallback": function (row, data, index) {
					//align right
					$('td', row).eq(1).addClass('text-right');
					$('td', row).eq(2).addClass('text-right');
					$('td', row).eq(3).addClass('text-right');
					$('td', row).eq(4).addClass('text-right');
                }
			});
			loadTotal();
		});


		function loadTotal(){
			$('span#total_member_deposit').html("0.00");
			$('span#total_member_deposit_amount').html("0.00");

			$.ajax({url: '<?php echo base_url('report/register_deposit_rate_player_total/').$username."/".$num.'/'.$type;?>',
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

						$('span#total_member_deposit').html(parseFloat(json.total_data.total_member_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#total_member_deposit_amount').html(parseFloat(json.total_data.total_member_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));

						$('span#total_agent_register_count').html(parseFloat(json.total_data.total_register_count).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#total_agent_deposit').html(parseFloat(json.total_data.total_member_deposit).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#total_agent_deposit_amount').html(parseFloat(json.total_data.total_member_deposit_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
					}
				},
				error: function (request,error) {
				}
			});
		}
	</script>
</body>
</html>