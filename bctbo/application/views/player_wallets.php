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
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_main_wallet');?>:</label>
									<div class="col-5">
										<label class="col-form-label font-weight-normal" id="main_wallet_balance"><?php echo (isset($points) ? $points : '0.00');?></label>
									</div>
									<div class="col-2">
										<i onclick="pull_all_balance('<?php echo $player_id;?>')" class="fas fa-wallet nav-icon text-maroon" title="<?php echo $this->lang->line('label_transfer_all')?>"></i>&nbsp;&nbsp;&nbsp;
										<i onclick="get_all_balance('<?php echo $player_id;?>')" class="fas fa-sync nav-icon text-warning" title="<?php echo $this->lang->line('label_refresh_all')?>"></i>&nbsp;&nbsp;&nbsp;
									</div>
								</div>
								<?php
									for($i=0;$i<sizeof($game_list);$i++)
									{
										$html = '<div class="form-group row">';
										$html .= '<label class="col-5 col-form-label">' . $this->lang->line($game_list[$i]['game_name']) . ':</label>';
										$html .= '<div class="col-3">';
										$html .= '<label class="col-form-label font-weight-normal" id="wb_' . strtolower($game_list[$i]['game_code']) . '">...</label>';
										$html .= '</div>';
										$html .= '<div class="col-2">';
										$html .= '<i onclick="pull_balance('."'".$player_id."'".' , '."'".$game_list[$i]['game_code']."'".', '."'".$i."'".')" class="fas fa-wallet nav-icon text-maroon" title="'.$this->lang->line('transfer_withdrawal').'"></i>';
										$html .= '</div>';
										$html .= '<div class="col-2">';
										$html .= '<i onclick="push_balance('."'".$player_id."'".' , '."'".$game_list[$i]['game_code']."'".', '."'".$i."'".')" class="fas fa-wallet nav-icon text-primary" title="'.$this->lang->line('transfer_deposit').'"></i>';
										$html .= '</div>';
										$html .= '</div>';
										echo $html;
									}
								?>
								<div class="form-group row">
									<label class="col-5 col-form-label font-weight-bold"><?php echo $this->lang->line('label_total');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-bold" id="total_amount">...</label>
									</div>
								</div>
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
		$(document).ready(function() {
			var total_amount = parseFloat('<?php echo (isset($points) ? $points : '0.00');?>');
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			get_all_balance('<?php echo $player_id;?>');
		});

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
						$('#main_wallet_balance').html(json.main_wallet);
						$('#total_amount').html(json.total);
						<?php
							for($i=0;$i<sizeof($game_list);$i++)
							{
						?>
						$('#wb_<?php echo strtolower($game_list[$i]['game_code'])?>').html(json.<?php echo strtolower($game_list[$i]['game_code'])?>_wallet);
						<?php 
							}
						?>
					}
				},
				error: function (request,error){
				}
			});
		}

		function get_all_balance(player_id){
			$.ajax({url: '<?php echo base_url("player/wallet_all_balance/");?>'+player_id,
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
						$('#main_wallet_balance').html(json.main_wallet);
						$('#total_amount').html(json.total);
						<?php
							for($i=0;$i<sizeof($game_list);$i++)
							{
						?>
						$('#wb_<?php echo strtolower($game_list[$i]['game_code'])?>').html(json.<?php echo strtolower($game_list[$i]['game_code'])?>_wallet);
						<?php 
							}
						?>
					}
				},
				error: function (request,error){
				}
			});
		}

		function get_balance(player_id, game_code){
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
						$('#wb_'+game_code.toLowerCase()).html(json.balance);
					}
				},
				error: function (request,error){
				}
			});
		}

		function pull_balance(player_id, game_code){
			$.ajax({url: '<?php echo base_url("player/pull_balance/");?>'+player_id+"/"+game_code,
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

						$('#wb_'+game_code.toLowerCase()).html("0.00");
						var main_wallet_balance = $('#main_wallet_balance').html();
						var total_main_balance =  (parseFloat(json.balance)+ parseFloat(main_wallet_balance));
						$('#main_wallet_balance').html(total_main_balance.toFixed(2));
					}
				},
				error: function (request,error){
				}
			});
		}

		function push_balance(player_id, game_code){
			$.ajax({url: '<?php echo base_url("player/push_balance/");?>'+player_id+"/"+game_code,
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
						$('#main_wallet_balance').html(json.balance);
						var game_wallet_balance = $('#wb_'+game_code.toLowerCase()).html();
						var game_wallet_total = parseFloat(json.game_balance)+ parseFloat(game_wallet_balance);
						$('#wb_'+game_code.toLowerCase()).html(game_wallet_total.toFixed(2));
					}
				},
				error: function (request,error){
				}
			});
		}
	</script>
</body>
</html>
