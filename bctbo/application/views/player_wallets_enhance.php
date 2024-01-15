<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
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
							<div class="card-body">
								<div class="form-group row">
									<label class="col-2 col-form-label"><?php echo $this->lang->line('label_username');?>:</label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-2 col-form-label"><?php echo $this->lang->line('label_main_wallet');?>:</label>
									<div class="col-8">
										<label class="col-form-label font-weight-normal" id="main_wallet_balance"><?php echo (isset($points) ? number_format($points, 2, '.', ',') : '0.00');?></label>
									</div>
									<div class="col-2">
										<i onclick="pull_all_balance('<?php echo $player_id;?>')" class="fas fa-wallet nav-icon text-maroon" title="<?php echo $this->lang->line('label_transfer_all')?>"></i>&nbsp;&nbsp;&nbsp;
										<i onclick="get_all_balance('<?php echo $player_id;?>')" class="fas fa-sync nav-icon text-warning" title="<?php echo $this->lang->line('label_refresh_all')?>"></i>&nbsp;&nbsp;&nbsp;
									</div>
								</div>
							</div>
						</div>
						<div class="card card-primary">
							<div class="card-header">
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_wallet_balances');?></label>
							</div>
							<div class="card-body">
								<table id="wallet-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_game_provider');?></th>
											<th><?php echo $this->lang->line('label_game_account');?></th>
											<th><?php echo $this->lang->line('label_wallet_balances');?></th>
											<th><?php echo $this->lang->line('label_created_date');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_PLAYER_GAME_TRANSFER) == TRUE):?>
											<th><?php echo $this->lang->line('button_transfers');?></th>
											<?php endif;?>
											<?php if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE):?>
											<th><?php echo $this->lang->line('button_kicks');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody>
										<?php
											$platform_kick = array_flip(json_decode(SYSTEM_API_GAME_PLATFORM_KICK,true));
											if(isset($game_provider) && !empty($game_provider)){
												foreach($game_provider as $game_provider_row){
										?>
										<tr id="<?php echo strtolower($game_provider_row['game_provider_code']);?>">
											<td><?php echo $this->lang->line('game_' . strtolower($game_provider_row['game_provider_code']));?></td>
											<td><?php echo $game_provider_row['game_id'];?></td>
											<td class="<?php echo strtolower($game_provider_row['game_provider_code']);?>_sum"><i class="fas fa-spinner fa-pulse"></i></td>
											<td><?php echo (($game_provider_row['created_date'] > 0) ? date('Y-m-d H:i:s', $game_provider_row['created_date']) : '-');?></td>
											<td><?php echo (($game_provider_row['updated_date'] > 0) ? date('Y-m-d H:i:s', $game_provider_row['updated_date']) : '-');?></td>
											<?php if(permission_validation(PERMISSION_PLAYER_GAME_TRANSFER) == TRUE):?>
											<td class="<?php echo strtolower($game_provider_row['game_provider_code']);?>_transfer"><button type="button" class="btn btn-block btn-outline-primary disabled" id="<?php echo strtolower($game_provider_row['game_provider_code']);?>_transfer_btn" onclick="transfer_player('<?php echo $player_id;?>','<?php echo $game_provider_row['game_provider_code'];?>')"><?php echo $this->lang->line('button_transfers');?></button></td>
											<?php endif;?>
											<?php if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE):?>
											<td class="<?php echo strtolower($game_provider_row['game_provider_code']);?>_kick">
												<?php if(isset($platform_kick[$game_provider_row['game_provider_code']])): ?>
												<button type="button" class="btn btn-block btn-outline-danger disabled" id="<?php echo strtolower($game_provider_row['game_provider_code']);?>_kick_btn" onclick="kick_player('<?php echo $player_id;?>','<?php echo $game_provider_row['game_provider_code'];?>')"><?php echo $this->lang->line('button_kicks');?></button>
												<?php endif;?>
											</td>
											<?php endif;?>
										</tr>
										<?php
												}
											}
										?>
									</tbody>
									<tfoot>
										<th colspan="2" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
										<th><span id="total_amount"></span></th>
										<th></th>
										<th></th>
										<?php if(permission_validation(PERMISSION_PLAYER_GAME_TRANSFER) == TRUE):?>
										<th></th>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE):?>
										<th></th>
										<?php endif;?>
									</tfoot>
								</table>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_close');?></button>
							</div>
							<!-- /.card-footer -->
						</div>
						<!-- /.card -->
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
		$(document).ready(function(){
			get_all_balance();
		});

		function get_balance(player_id, game_code){
			$('#'+game_code.toLowerCase()+'_transfer_btn').addClass('disabled');
			$('#'+game_code.toLowerCase()+'_kick_btn').addClass('disabled');
			
			$.ajax({url: '<?php echo base_url("player/wallet_balance/");?>'+player_id+"/"+game_code,
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						var total = $('#total_amount').html();
						$('#total_amount').html(parseFloat(parseFloat(total.replace(',', '')) + parseFloat(json.balance)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('.'+game_code.toLowerCase()+'_sum').html(parseFloat(json.balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('#'+game_code.toLowerCase()+'_transfer_btn').removeClass('disabled');
						$('#'+game_code.toLowerCase()+'_kick_btn').removeClass('disabled');
					}
				},
				error: function (request,error){
				}
			});
		}

		function get_all_balance(){
			var total = $('#main_wallet_balance').html();
			$('#total_amount').html(parseFloat(parseFloat(total.replace(',', ''))).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			<?php
				if(isset($game_provider) && !empty($game_provider)){
					foreach($game_provider as $game_provider_row){
			?>
			get_balance("<?php echo $player_id;?>","<?php echo $game_provider_row['game_provider_code'];?>");
			<?php
					} 
				}
			?>
		}

		function kick_player(player_id,game_code){
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('player/kick_game/');?>' +player_id+"/"+game_code , function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
					}
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}

		function transfer_player(player_id,game_code){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '600px'), ((browser_width < 600) ? '100%': '460px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_wallet_transfer');?>',
				content: '<?php echo base_url('player/wallet_transfer/');?>' + player_id + "/"+ game_code
			});
		}

		function pull_all_balance(player_id){
			$.ajax({url: '<?php echo base_url("player/pull_all_balance/");?>'+player_id,
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						$('#main_wallet_balance').html(parseFloat(json.main_wallet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						$('#total_amount').html(parseFloat(json.total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

						<?php
						if(isset($game_provider) && !empty($game_provider)){
							foreach($game_provider as $game_provider_row){
						?>
						$('.<?php echo strtolower($game_provider_row['game_provider_code']);?>_sum').html(parseFloat(json.<?php echo strtolower($game_provider_row['game_provider_code'])?>_wallet).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						<?php
							}
						}
						?>
					}
				},
				error: function (request,error){
				}
			});
		}
	</script>
</body>
</html>
