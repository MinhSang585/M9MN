<div id="card-table-<?php echo $num;?>" class="card">
	<?php if(permission_validation(PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL) == TRUE):?>
	<div class="card-header">
		<h3 class="card-title"><button onclick="exportData('<?php echo $num;?>','<?php echo $username;?>')" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
	</div>
	<?php endif;?>
	<div class="card-body">
		<table id="report-table-<?php echo $num;?>" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th width="40"><?php echo $this->lang->line('label_level');?></th>
					<th width="60"><?php echo $this->lang->line('label_game_type');?></th>
					<th width="60"><?php echo $this->lang->line('label_username');?></th>
					<th width="60"><?php echo $this->lang->line('label_agent');?></th>
					<th width="100"><?php echo $this->lang->line('label_deposit');?></th>
					<th width="100"><?php echo $this->lang->line('label_deposit_offline');?></th>
					<th width="100"><?php echo $this->lang->line('label_deposit_online');?></th>
					<th width="100"><?php echo $this->lang->line('label_deposit_point');?></th>
					<th width="100"><?php echo $this->lang->line('label_withdrawal');?></th>
					<th width="100"><?php echo $this->lang->line('label_withdrawal_offline');?></th>
					<th width="100"><?php echo $this->lang->line('label_withdrawal_online');?></th>
					<th width="100"><?php echo $this->lang->line('label_withdrawal_point');?></th>
					<th width="100"><?php echo $this->lang->line('label_adjust');?></th>
					<th width="100"><?php echo $this->lang->line('label_adjust_in');?></th>
					<th width="100"><?php echo $this->lang->line('label_adjust_out');?></th>
					<th width="100"><?php echo $this->lang->line('label_number_of_transaction');?></th>
					<th width="100"><?php echo $this->lang->line('label_bet_amount');?></th>
					<th width="100"><?php echo $this->lang->line('label_rolling_amount');?></th>
					<th width="100"><?php echo $this->lang->line('label_total_win_loss');?></th>
					<th width="100"><?php echo $this->lang->line('label_total_promotion_amount');?></th>
					<th width="100"><?php echo $this->lang->line('label_bonus');?></th>
					<th width="100"><?php echo $this->lang->line('label_agent_possess');?></th>
					<th width="100"><?php echo $this->lang->line('label_possess_win_loss');?></th>
					<th width="100"><?php echo $this->lang->line('label_possess_promotion');?></th>
					<th width="100"><?php echo $this->lang->line('label_possess_bonus');?></th>
					<th width="100"><?php echo $this->lang->line('label_rolling_commission');?></th>
					<th width="100"><?php echo $this->lang->line('label_profit');?></th>
					<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
					<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
					<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
					<th width="150" class="bg-maroon"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_lc');?>)</th>
					<th width="150" class="bg-warning"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
					<th width="150" class="bg-warning"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
					<th width="150" class="bg-warning"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
					<th width="150" class="bg-warning"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_sl');?>)</th>
					<th width="150" class="bg-success"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
					<th width="150" class="bg-success"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
					<th width="150" class="bg-success"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>
					<th width="150" class="bg-success"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_sb');?>)</th>					<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>					<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>					<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>					<th width="150" class="bg-cyan"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_cf');?>)</th>
					<th width="150" class="bg-primary"><?php echo $this->lang->line('label_rolling_amount');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
					<th width="150" class="bg-primary"><?php echo $this->lang->line('label_win_loss');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
					<th width="150" class="bg-primary"><?php echo $this->lang->line('label_comission_rate');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
					<th width="150" class="bg-primary"><?php echo $this->lang->line('label_comission');?> (<?php echo $this->lang->line('game_type_ot');?>)</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
					<th><span id="downline_total_deposit_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_deposit_offline_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_deposit_online_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_deposit_point_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_withdrawal_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_withdrawal_offline_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_withdrawal_online_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_withdrawal_point_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_adjust_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_adjust_in_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_adjust_out_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bet_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bet_amount_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_amount_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_promotion_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_bonus_<?php echo $num;?>">0</span></th>
					<th></th>
					<th><span id="downline_total_possess_win_loss_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_possess_promotion_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_possess_bonus_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_commission_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_profit_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_amount_live_casino_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_live_casino_<?php echo $num;?>">0</span></th>
					<th></th>
					<th><span id="downline_total_comission_live_casino_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_amount_slot_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_slot_<?php echo $num;?>">0</span></th>
					<th></th>
					<th><span id="downline_total_comission_slot_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_rolling_amount_sportbook_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_sportbook_<?php echo $num;?>">0</span></th>
					<th></th>
					<th><span id="downline_total_comission_sportbook_<?php echo $num;?>">0</span></th>					<th><span id="downline_total_rolling_amount_cock_fighting_<?php echo $num;?>">0</span></th>					<th><span id="downline_total_win_loss_cock_fighting_<?php echo $num;?>">0</span></th>					<th></th>					<th><span id="downline_total_comission_cock_fighting_<?php echo $num;?>">0</span></th>					
					<th><span id="downline_total_rolling_amount_other_<?php echo $num;?>">0</span></th>
					<th><span id="downline_total_win_loss_other_<?php echo $num;?>">0</span></th>
					<th></th>
					<th><span id="downline_total_comission_other_<?php echo $num;?>">0</span></th>
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
					"url": "<?php echo base_url('report/winloss_sum_listing/') . $num . '/' . $username;?>",
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
					{"targets": [1], "visible": false},
					{"targets": [4], "visible": false},
					{"targets": [8], "visible": false},
					{"targets": [10], "visible": false},
					{"targets": [12], "visible": false},
					{"targets": [15], "visible": false},
					{"targets": [20], "visible": false},
					{"targets": [24], "visible": false},
					{"targets": [25], "visible": false},
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
				<?php if(!$this->agent->is_mobile()) { ?>
				"fixedColumns": {
		            leftColumns: 4 //Your column number here
		        },
		    	<?php } ?>
			});
		});	
	</script>
</div>