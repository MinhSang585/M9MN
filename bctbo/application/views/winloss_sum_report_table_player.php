<div id="p-card-table-1" class="card" style="display: none;">
	<!-- /.card-header -->
	<div class="card-body">
		<table id="p-report-table-1" class="table table-striped table-bordered table-hover" style="width:100%;">
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
					<th><?php echo $this->lang->line('label_promotion');?></th>
					<th><?php echo $this->lang->line('label_bonus');?></th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
					<th><span id="player_total_deposit">0</span></th>
					<th><span id="player_total_withdrawal">0</span></th>
					<th><span id="player_total_bet">0</span></th>
					<th><span id="player_total_bet_amount">0</span></th>
					<th><span id="player_total_win_loss">0</span></th>
					<th><span id="player_total_rolling_amount">0</span></th>
					<th><span id="player_total_promotion">0</span></th>
					<th><span id="player_total_bonus">0</span></th>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('#p-report-table-1').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": ((browser_width < 600) ? true: false),
				"responsive": false,
				"filter": false,
				"ordering": false,
				"pageLength" : 10,
				"lengthMenu": [[10, 25, 50, 100, 1000, 2000], [10, 25, 50, 100, 1000, 2000]],
				"ajax": {
					"url": "<?php echo base_url('report/player_winloss_sum_listing/') . $username;?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							if(json.responseJSON.recordsFiltered > 0) {
								$('#p-card-table-1').show();
							}
							else {
								$('#p-card-table-1').remove();
							}
						}
						
						layer.closeAll('loading');
					},
				},
				"columnDefs": [
					{"targets": [6], "visible": false},
					{"targets": [11], "visible": false},
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
	</script>
</div>