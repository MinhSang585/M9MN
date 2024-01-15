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
								<form action="<?php echo site_url('blog/search');?>" id="blog-form" name="blog-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['from_date']) ? $data_search['from_date'] : date('Y-m-d 00:00:00'));?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
												<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
													<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo (isset($data_search['to_date']) ? $data_search['to_date'] : date('Y-m-d 23:59:59'));?>" data-target="#to_date_click"/>
													<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_blog_display');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="blog_display" name="blog_display">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php
															foreach(get_blog_display() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-2"  id="blog_category_id_div" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_blog_category');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="blog_category_id" name="blog_category_id">
														<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
														<?php
															if(isset($blog_category) && sizeof($blog_category)>0){
																for($i=0;$i<sizeof($blog_category);$i++)
																{
																	echo '<option value="' . $blog_category[$i]['blog_category_id'] . '">' . $blog_category[$i]['blog_category_name'] . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-2" id="page_category_id_div" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_blog_category');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="page_category_id" name="page_category_id">
														<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
														<?php
															foreach(get_blog_page() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-2" id="product_category_id_div" style="display:none;">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_blog_category');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="product_category_id" name="product_category_id">
														<option value=""><?php echo $this->lang->line('error_enter_blog_type');?></option>
														<?php
															foreach(get_blog_product() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>								
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_name');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="blog_name" name="blog_name" value="<?php echo (isset($data_search['blog_name']) ? $data_search['blog_name'] : '');?>">
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_blog_pathname');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="blog_pathname" name="blog_pathname" value="<?php echo (isset($data_search['blog_pathname']) ? $data_search['blog_pathname'] : '');?>">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="status" name="status">
														<option value="-1"><?php echo $this->lang->line('label_all');?></option>
														<option value="<?php echo STATUS_INACTIVE;?>"><?php echo $this->lang->line('status_inactive');?></option>
														<option value="<?php echo STATUS_ACTIVE;?>"><?php echo $this->lang->line('status_active');?></option>
													</select>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_domain');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="domain" name="domain">
														<option value="0"><?php echo $this->lang->line('label_all');?></option>
														<?php 
															$system_all_domain = json_decode(SYSTEM_ALL_DOMAIN,true);
															if(sizeof($system_all_domain)>0){
																foreach($system_all_domain as $k => $v)
																{
																	echo '<option value="' . $v . '">' . $v . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>	
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal">&nbsp;</label>
											</div>											
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-12">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_quick_search');?></label>
										</div>
										<div class="col-md-12 col-12">
											<div class="row mb-2">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_month_from;?>','<?php echo $date_last_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_month');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_last_week_from;?>','<?php echo $date_last_week_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_last_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_yesterday_from;?>','<?php echo $date_yesterday_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_yesterday');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_today_from;?>','<?php echo $date_today_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_today');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_week_from;?>','<?php echo $date_current_week_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_week');?></button>
												</div>
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_current_month_from;?>','<?php echo $date_current_month_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_this_month');?></button>
												</div>
											</div>
											<div class="row mb-2">
												<div class="col-md-2 col-2">
													<button type="button" onclick="fastSetDateSearch('<?php echo $date_clear_from;?>','<?php echo $date_clear_to;?>')" class="btn btn-block btn-info"><?php echo $this->lang->line('label_quick_search_clear');?></button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php if(permission_validation(PERMISSION_BLOG_ADD) == TRUE):?>
							<div class="card-header">
							  <h3 class="card-title"><button onclick="addData()" type="button" class="btn btn-block bg-gradient-primary btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_add_new');?></button></h3>
							</div>
							<!-- /.card-header -->
							<?php endif;?>
							<div class="card-body">
								<table id="blog-table" class="table table-striped table-bordered table-hover" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_hashtag');?></th>
											<th><?php echo $this->lang->line('label_blog_name');?></th>
											<th><?php echo $this->lang->line('label_blog_display');?></th>
											<th><?php echo $this->lang->line('label_blog_type');?></th>
											<th><?php echo $this->lang->line('label_blog_pathname');?></th>
											<th><?php echo $this->lang->line('label_domain');?></th>
											<th><?php echo $this->lang->line('label_status');?></th>
											<th><?php echo $this->lang->line('label_created_by');?></th>
											<th><?php echo $this->lang->line('label_created_date');?></th>
											<th><?php echo $this->lang->line('label_updated_by');?></th>
											<th><?php echo $this->lang->line('label_updated_date');?></th>
											<?php if(permission_validation(PERMISSION_BLOG_UPDATE) == TRUE OR permission_validation(PERMISSION_BLOG_DELETE) == TRUE):?>
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
		function fastSetDateSearch(from,to){
			$('#from_date').val(from);
			$('#to_date').val(to);
			$('#blog-form').submit();
		}
		$(document).ready(function() {
			$("#blog_display").on('change', function () {
				$("#blog_category_id_div").hide();
				$("#page_category_id_div").hide();
				$("#product_category_id_div").hide();

				var blog_display = this.value;
				if(blog_display == <?php echo BLOG_DISPLAY_BLOG?>){
					$("#blog_category_id_div").show();
				}else if(blog_display == <?php echo BLOG_DISPLAY_PAGE?>){
					$("#page_category_id_div").show();
				}else if(blog_display == <?php echo BLOG_DISPLAY_PRODUCT?>){
					$("#product_category_id_div").show();
				}
			});

			var is_allowed = true;
			var form = $('#blog-form');
			
			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });

            form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
							csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
							from_date:  $('#from_date').val(),
							to_date:  $('#to_date').val(),
							blog_name:  $('#blog_name').val(),
							blog_display : $('#blog_display').val(),
							blog_category_id : $('#blog_category_id').val(),
							page_category_id : $('#page_category_id').val(),
							product_category_id : $('#product_category_id').val(),
							blog_pathname : $('#blog_pathname').val(),
							status : $('#status').val(),
							domain : $('#domain').val(),
						},
						type: 'post',                  
						async: 'true',
						beforeSend: function() {
							layer.load(1);
						},
						complete: function() {
							layer.closeAll('loading');
							is_allowed = true;
						},
						success: function (data) {
							var json = JSON.parse(JSON.stringify(data));
							var message = '';
							var msg_icon = 2;
							
							$('meta[name=csrf_token]').attr('content', json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								$('#blog-table').DataTable().ajax.reload();
							}
							else {
								if(json.msg.from_date_error != '') {
									message = json.msg.from_date_error;
								}
								else if(json.msg.to_date_error != '') {
									message = json.msg.to_date_error;
								}
								else if(json.msg.general_error != '') {
									message = json.msg.general_error;
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							}
						},
						error: function (request,error) {
						}
					});  
				}
				
				return false;
			});
			
			$('#blog-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"pageLength" : 10,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('blog/listing');?>",
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
		
		function addData() {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_add_blog');?>',
				content: '<?php echo base_url('blog/add/');?>'
			});
		}
		
		function updateData(id) {
			window.open('<?php echo base_url('blog/edit/');?>'+ id, "_blank");
		}

		function viewData(id){
			$.get('<?php echo base_url('blog/view_frontend/');?>' + id, function(data) {
				var json = JSON.parse(JSON.stringify(data));
				var message = json.msg;
				var msg_icon = 2;
				
				if(json.status == '<?php echo EXIT_SUCCESS;?>') {
					msg_icon = 1;
					for (i = 0; i < json.url.length; i++) {
						window.open(json.url[i], "_blank");
					}
				}
				
				//layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
			});
		}
		
		function deleteData(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('blog/delete/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#blog-table').DataTable().ajax.reload();
					}
					
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}
	</script>	
</body>
</html>
