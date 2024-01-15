<div id="card-table-<?php echo $num;?>" class="card">
	<?php if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL) == TRUE):?>
	<div class="card-header">
		<h3 class="card-title"><button onclick="exportData('<?php echo $num;?>','<?php echo $username;?>')" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
	</div>
	<?php endif;?>
	<div class="card-body">
		<table id="report-table-<?php echo $num;?>" class="table table-striped table-bordered table-hover">
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
			<tfoot>
				<tr>
					<th width="280" colspan="3" class="text-right bg-info"><?php echo $this->lang->line('label_total');?>:</th>
					<th width="150" class="bg-info text-right"><span id="downline_total_register_count_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_member_deposit_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_member_deposit_amount_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_member_deposit_rate_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_first_deposit_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_first_deposit_amount_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_first_deposit_rate_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_second_or_more_deposit_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_second_or_more_deposit_amount_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_second_or_more_deposit_rate_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_third_or_more_deposit_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_third_or_more_deposit_amount_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-info text-right"><span id="downline_total_third_or_more_deposit_rate_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-danger text-right"><span id="downline_total_no_deposit_<?php echo $num;?>">0</span></th>
					<th width="150" class="bg-danger text-right"><span id="downline_total_churn_rate_<?php echo $num;?>">0</span></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#report-table-<?php echo $num;?>').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"ordering": false,
				"pageLength" : 10,
				"lengthMenu": [[10, 25, 50, 100, 1000, 2000], [10, 25, 50, 100, 1000, 2000]],
				"ajax": {
					"url": "<?php echo base_url('report/register_deposit_rate_listing/') . $num . '/' . $username;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							
							if(json.responseJSON.recordsFiltered > 0) {
								$('#card-table-<?php echo $num;?>').show();
							}
							else {
								$('#card-table-<?php echo $num;?>').remove();
							}
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
		});	
	</script>
</div>