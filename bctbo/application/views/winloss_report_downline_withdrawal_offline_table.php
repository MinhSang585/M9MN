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
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_withdrawal_offline');?></label>
							</div>
							<div class="card-body">
								<table id="withdrawal-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="50"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="120"><?php echo $this->lang->line('label_date');?></th>
											<th width="80"><?php echo $this->lang->line('label_type');?></th>
											<th width="80"><?php echo $this->lang->line('label_username');?></th>
											<th width="120"><?php echo $this->lang->line('label_bank_name');?></th>
											<th width="100"><?php echo $this->lang->line('label_bank_account_name');?></th>
											<th width="120"><?php echo $this->lang->line('label_bank_account_no');?></th>
											<th width="80"><?php echo $this->lang->line('label_amount_apply');?></th>
											<th width="50"><?php echo $this->lang->line('label_fee');?></th>
											<th width="80"><?php echo $this->lang->line('label_actual_amount');?></th>
											<th width="50"><?php echo $this->lang->line('label_status');?></th>
											<th width="80"><?php echo $this->lang->line('label_ip');?></th>
											<th width="80"><?php echo $this->lang->line('label_remark');?></th>
											<th width="80"><?php echo $this->lang->line('label_updated_by');?></th>
											<th width="120"><?php echo $this->lang->line('label_updated_date');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_withdrawal">0</span></th>
											<th></th>
											<th><span id="total_withdrawal_fee_amount">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
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
			loadTotal();
			$('#withdrawal-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[1, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/winloss_downline_withdrawal_offline_listing');?>",
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
		});


		function loadTotal(){
			$('span#total_withdrawal').removeClass();
			$('span#total_withdrawal').html("0.00");
			$('span#total_withdrawal_fee_amount').removeClass();
			$('span#total_withdrawal_fee_amount').html("0.00");

			$.ajax({url: '<?php echo base_url('report/winloss_downline_withdrawal_offline_listing_total/');?>',
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
						if(json.total_data.total_withdrawal>0){var deposit_class = "text-dark";}else{var deposit_class = "text-dark";}
						$('span#total_withdrawal').removeClass().addClass(deposit_class);
						if(json.total_data.total_withdrawal_fee_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
						$('span#total_withdrawal_fee_amount').removeClass().addClass(deposit_class);
						$('span#total_withdrawal').html(parseFloat(json.total_data.total_withdrawal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
						$('span#total_withdrawal_fee_amount').html(parseFloat(json.total_data.total_withdrawal_fee_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').slice(0, -3));
					}
				},
				error: function (request,error) {
				}
			});
		}
	</script>
</body>
</html>