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
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_win_loss_report_player');?></label>
							</div>
							<div class="card-body">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="60"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="100"><?php echo $this->lang->line('label_username');?></th>
											<th width="60"><?php echo $this->lang->line('label_tag_code');?></th>
											<th width="120"><?php echo $this->lang->line('label_membership_level');?> (<?php echo $this->lang->line('label_membership_number');?>)</th>
											<th width="100"><?php echo $this->lang->line('label_bank_account_name');?></th>
											<th width="100"><?php echo $this->lang->line('label_bet');?></th>
											<th width="100"><?php echo $this->lang->line('label_game_provider');?></th>
											<th width="100"><?php echo $this->lang->line('label_game_type');?></th>
											<th width="200"><?php echo $this->lang->line('label_bet_amount');?></th>
											<th width="200"><?php echo $this->lang->line('label_rolling_amount');?></th>
											<th width="200"><?php echo $this->lang->line('label_win_loss');?></th>
											<th width="100"><?php echo $this->lang->line('label_rtp');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="5" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="player_total_bet">0</span></th>
											<th></th>
											<th></th>
											<th><span id="player_total_bet_amount">0</span></th>
											<th><span id="player_total_rolling_amount">0</span></th>
											<th><span id="player_total_win_loss">0</span></th>
											<th><span id="player_total_rtp">0</span></th>
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
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('report/winloss_player_game_provider_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							if(json.responseJSON.total_data.total_bet>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_bet').removeClass().addClass(deposit_class);
							if(json.responseJSON.total_data.total_bet_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_bet_amount').removeClass().addClass(deposit_class);
							if(json.responseJSON.total_data.total_rolling_amount>0){var deposit_class = "text-primary";}else{var deposit_class = "text-dark";}
							$('span#player_total_rolling_amount').removeClass().addClass(deposit_class);
							if(json.responseJSON.total_data.total_win_loss>=0){if(json.responseJSON.total_data.total_win_loss==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#player_total_win_loss').removeClass().addClass(win_loss_class);
							if(json.responseJSON.total_data.total_rtp>=0){if(json.responseJSON.total_data.total_rtp==0){var win_loss_class = "text-dark";}else{var win_loss_class = "text-primary";}}else{var win_loss_class = "text-danger";}
							$('span#player_total_rtp').removeClass().addClass(win_loss_class);

							$('span#player_total_bet').html(parseFloat(json.responseJSON.total_data.total_bet).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_bet_amount').html(parseFloat(json.responseJSON.total_data.total_bet_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_rolling_amount').html(parseFloat(json.responseJSON.total_data.total_rolling_amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_win_loss').html(parseFloat(json.responseJSON.total_data.total_win_loss).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#player_total_rtp').html(parseFloat(json.responseJSON.total_data.total_rtp).toFixed(2).toLocaleString('en')+"%");
						}
					},
				},
				"columnDefs": [
					<?php if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){ ?>
					{"targets": [0], "visible": false},
					{"targets": [11], "orderable": false},
					<?php }else{ ?>
					{"targets": [0], "visible": false},
					{"targets": [11], "orderable": false},
					{"targets": [4], "visible": false},
					<?php } ?>
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

		function showGameProviderDaily(username,game_provider_code,game_type_code){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_win_loss_report_player');?>',
				content: '<?php echo base_url('report/winloss_player_game_provider_daily/');?>' + username + "/" + game_provider_code + "/" + game_type_code
			});
		}
	</script>
</body>
</html>