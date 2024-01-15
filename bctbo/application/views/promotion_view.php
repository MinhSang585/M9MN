<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
	<?php $this->load->view('parts/head_meta');?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('parts/navbar_page');?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php $this->load->view('parts/sidebar_page');?>
		<!-- /.sidebar -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<?php $this->load->view('parts/header_page');?>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><button onclick="addData()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
							</div>
							<div class="card-body">
								<table id="promotion-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_promotion_name');?></th>
											<th><?php echo $this->lang->line('label_promotion_code');?></th>
											<th><?php echo $this->lang->line('label_type');?></th>
											<th><?php echo $this->lang->line('label_start_date');?></th>
											<th><?php echo $this->lang->line('label_end_date');?></th>
											<th><?php echo $this->lang->line('label_promotion_status');?></th>
											<th><?php echo $this->lang->line('label_sequence');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE OR permission_validation(PERMISSION_PROMOTION_DELETE) == TRUE):?>
											<th><?php echo $this->lang->line('label_action');?></th>
											<?php endif;?>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>	
				</div>	
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Main Footer -->
		<?php $this->load->view('parts/footer_page');?>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#promotion-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('promotion/listing');?>",
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

		function addData(){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_promotion');?>',
				content: '<?php echo base_url('promotion/add/');?>'
			});
		}

		function updateData(id,code) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_promotion_setting');?>',
				content: '<?php echo base_url('promotion/edit/');?>' + id +'/'+ code
			});
		}

		function deleteData(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('promotion/delete/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#promotion-table').DataTable().ajax.reload();
					}
					
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}

		function updateContent(id){
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_promotion_setting');?>',
				content: '<?php echo base_url('promotion/tnc/');?>' + id
			});
		}
	</script>
</body>
</html>
