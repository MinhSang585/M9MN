<div id="card-table-<?php echo $num;?>" class="card" style="display: none;">
	<?php if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE):?>
	<div class="card-header">
		<h3 class="card-title"><button onclick="exportData('<?php echo $num;?>')" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
	</div>
	<?php echo form_open('export/winloss_export_excel/'.$num.'/'.$username, 'class="export" id="export_form_'.$num.'"');?>
	<?php echo form_close(); ?>
	<!-- /.card-header -->
	<?php endif;?>
	<div class="card-body">
		<table id="report-table-<?php echo $num;?>" class="table table-striped table-bordered table-hover" style="width:100%;">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('label_level');?></th>
					<th><?php echo $this->lang->line('label_game_type');?></th>
					<th><?php echo $this->lang->line('label_username');?></th>
					<th><?php echo $this->lang->line('label_agent');?></th>
					<th><?php echo $this->lang->line('label_deposit');?></th>
					<th><?php echo $this->lang->line('label_withdrawal');?></th>
					<th><?php echo $this->lang->line('label_number_of_transaction');?></th>
					<th><?php echo $this->lang->line('label_bet_amount');?></th>
					<th><?php echo $this->lang->line('label_win_loss');?></th>
					<th><?php echo $this->lang->line('label_rolling_amount');?></th>
					<th><?php echo $this->lang->line('label_rolling_commission');?></th>
					<th><?php echo $this->lang->line('label_promotion');?></th>
					<th><?php echo $this->lang->line('label_bonus');?></th>
					<th><?php echo $this->lang->line('label_profit');?></th>	
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
					<th><span id="downline_total_deposit_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_withdrawal_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bet_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bet_amount_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_amount_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_commission_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_promotion_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bonus_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_profit_<?php echo $num;?>">0</span></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#report-table-<?php echo $num;?>').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": ((browser_width < 600) ? true: false),
				"responsive": false,
				"filter": false,
				"ordering": false,
				"pageLength" : 10,
				"ajax": {
					"url": "<?php echo base_url('report/winloss_listing/') . $num . '/' . $username;?>",
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
	</script>
</div>