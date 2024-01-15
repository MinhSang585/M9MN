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
								<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('title_player_promotion');?></label>
							</div>
							<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
							<div class="card-body">
								<div>
									<h3 class="card-title">
										<button onclick="bulkUpdate('<?php echo $player_id;?>','<?php echo STATUS_SATTLEMENT;?>')" type="button" class="btn bg-gradient-info btn-sm"><i class="fas fa-highlighter nav-icon"></i> <?php echo $this->lang->line('button_bulk_approve');?></button>
										<button onclick="bulkUpdate('<?php echo $player_id;?>','<?php echo STATUS_CANCEL;?>')" type="button" class="btn bg-gradient-danger btn-sm"><i class="fas fa-times nav-icon"></i> <?php echo $this->lang->line('button_bulk_cancel');?></button>
										<button onclick="bulkUpdate('<?php echo $player_id;?>','<?php echo STATUS_VOID;?>')" type="button" class="btn bg-gradient-warning btn-sm"><i class="fas fa-remove-format nav-icon"></i> <?php echo $this->lang->line('button_bulk_void');?></button>
									</h3>
								</div>
							</div>
							<?php endif;?>
							<div class="card-body">
								<table id="playerpromotion-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_date');?></th>
											<th><?php echo $this->lang->line('label_username');?></th>
											<th><?php echo $this->lang->line('label_promotion');?></th>
											<th><?php echo $this->lang->line('label_deposit_amount');?></th>
											<th><?php echo $this->lang->line('label_current_amount');?></th>
											<th><?php echo $this->lang->line('label_archieve_amount');?></th>
											<th><?php echo $this->lang->line('label_reward_amount');?></th>
											<th><?php echo $this->lang->line('label_is_reward');?></th>
											<th><?php echo $this->lang->line('label_reward_date');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_remark');?></th>
											<th><?php echo $this->lang->line('label_starting_date');?></th>
											<th><?php echo $this->lang->line('label_complete_date');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="7" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_reward">0</span></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE):?>
											<th></th>
											<?php endif;?>
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
			$('#playerpromotion-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('withdrawal/promotion_unsattle_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						if(json.status == 200) {
							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);
							$('span#total_reward').html(json.responseJSON.total_data.total_reward);
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
				}
			});
		});

		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/edit/');?>' + id
			});
		}
		function promotionEntitlement(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/entitlement/');?>' + id
			});
		}
		function betDetailData(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_player_promotion_setting');?>',
				content: '<?php echo base_url('playerpromotion/bet_detail/');?>' + id
			});
		}
		function bulkUpdate(id,status){
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('playerpromotion/bulk_update/');?>' + id + "/" + status, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					console.log(json);
					var message = json.msg.general_error;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#playerpromotion-table').DataTable().ajax.reload();
					}
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}
	</script>
</body>
</html>