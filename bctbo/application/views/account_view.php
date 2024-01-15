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
							<?php if(permission_validation(PERMISSION_SUB_ACCOUNT_ADD) == TRUE):?>
							<div class="card-header">
							  <h3 class="card-title"><button onclick="addData('<?php echo (isset($username) ? $username : '');?>')" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
							</div>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body">
								<table id="account-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="120"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="200"><?php echo $this->lang->line('label_username');?></th>
											<th width="120"><?php echo $this->lang->line('label_user_role');?></th>
											<th width="200"><?php echo $this->lang->line('label_nickname');?></th>
											<th width="240"><?php echo $this->lang->line('label_white_list_ip');?></th>
											<th width="60"><?php echo $this->lang->line('label_status');?></th>
											<th width="120"><?php echo $this->lang->line('label_registered_date');?></th>
											<th width="200"><?php echo $this->lang->line('label_last_login_date')." / ".$this->lang->line('label_ip');?></th>
											<?php if(permission_validation(PERMISSION_SUB_ACCOUNT_UPDATE) == TRUE OR permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE OR permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE):?>
											<th width="80"><?php echo $this->lang->line('label_action');?></th>
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
			$('#account-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo base_url('account/listing/') . (isset($username) ? $username : '');?>",
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
		
		function addData(username) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '440px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_sub_account');?>',
				content: '<?php echo base_url('account/add/');?>' + '/' + username
			});
		}
		
		function updateData(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '400px'), ((browser_width < 600) ? '100%': '450px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_sub_account_setting');?>',
				content: '<?php echo base_url('account/edit/');?>' + id
			});
		}
		
		function permissionSetup(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '610px'), ((browser_width < 600) ? '100%': '500px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_set_permissions');?>',
				content: '<?php echo base_url('account/permission/');?>' + id
			});
		}
		
		function changePassword(id) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '500px'), ((browser_width < 600) ? '100%': '340px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_change_password');?>',
				content: '<?php echo base_url('account/password/');?>' + id
			});
		}
	</script>	
</body>
</html>
