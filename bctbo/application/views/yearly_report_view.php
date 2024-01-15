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
								<form action="<?php echo site_url('report/yearly_report_search');?>" id="report-form" name="report-form" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
									<div class="form-group row">
										<div class="col-md-3">
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
												<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
													<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="<?php echo date('Y');?>" data-target="#from_date_click"/>
													<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_type');?></label>
												<div class="col-8">
													<select class="form-control form-control-sm select2bs4 col-12" id="type" name="type">
														<?php
															foreach(get_yearly_report_setting() as $k => $v)
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
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_upline');?></label>
												<div class="col-8">
													<select class="form-control select2bs4 col-12" id="upline" name="upline"></select>
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_username');?></label>
												<div class="col-8">
													<input type="text" class="form-control form-control-sm" id="username" name="username" value="">
												</div>
											</div>									
										</div>
										<div class="col-md-3">
											<div class="row mb-2">
												<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search nav-icon"></i> <?php echo $this->lang->line('button_search');?></button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<?php if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE):?>
							<div class="card-header">
								<h3 class="card-title"><button onclick="exportData()" type="button" class="btn btn-block bg-gradient-success btn-sm"><i class="fas fa-plus nav-icon"></i> <?php echo $this->lang->line('button_export');?></button></h3>
							</div>
							<?php echo form_open('export/yearly_report_export', 'class="export" id="export_form"');?>
							<?php echo form_close(); ?>
							<!-- /.card-header -->
							<?php endif;?>
							
							<!-- /.card-header -->
							<div class="card-body" style="display:none;">
								<table id="report-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="80"><?php echo $this->lang->line('label_hashtag');?></th>
											<th width="50"><?php echo $this->lang->line('label_username');?></th>
											<th width="80"><?php echo $this->lang->line('label_level');?></th>
											<th width="80"><?php echo $this->lang->line('month_jan');?></th>
											<th width="80"><?php echo $this->lang->line('month_feb');?></th>
											<th width="80"><?php echo $this->lang->line('month_mar');?></th>
											<th width="80"><?php echo $this->lang->line('month_apr');?></th>
											<th width="80"><?php echo $this->lang->line('month_may');?></th>
											<th width="80"><?php echo $this->lang->line('month_jun');?></th>
											<th width="80"><?php echo $this->lang->line('month_jul');?></th>
											<th width="80"><?php echo $this->lang->line('month_aug');?></th>
											<th width="80"><?php echo $this->lang->line('month_sep');?></th>
											<th width="80"><?php echo $this->lang->line('month_oct');?></th>
											<th width="80"><?php echo $this->lang->line('month_nov');?></th>
											<th width="80"><?php echo $this->lang->line('month_dec');?></th>
											<th width="80"><?php echo $this->lang->line('label_total');?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th colspan="3" class="text-right"><?php echo $this->lang->line('label_total');?>:</th>
											<th><span id="total_value_jan">0</span></th>
											<th><span id="total_value_feb">0</span></th>
											<th><span id="total_value_mar">0</span></th>
											<th><span id="total_value_apr">0</span></th>
											<th><span id="total_value_may">0</span></th>
											<th><span id="total_value_jun">0</span></th>
											<th><span id="total_value_jul">0</span></th>
											<th><span id="total_value_aug">0</span></th>
											<th><span id="total_value_sep">0</span></th>
											<th><span id="total_value_oct">0</span></th>
											<th><span id="total_value_nov">0</span></th>
											<th><span id="total_value_dec">0</span></th>
											<th><span id="total_value_total">0</span></th>
										</tr>
									</tfoot>
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
			$('#report-form').submit();
		}
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#report-form');
			$('.select2').select2();
			$('#from_date_click').datetimepicker({
				format: 'YYYY',
                icons: {
                    time: "fa fa-clock"
                }
            });
			
			$('#upline').on('change', function() {
				$("#referrer").empty().trigger('change');
			});

			$('#upline').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_upline');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/upline_search');?>',
			        type: "post",
			        dataType: 'json',
			        delay: 250,
			        cache: false,
			        data: function (params) {
			           	return {
			            	csrf_bctp_bo_token : parent.$('meta[name=csrf_token]').attr('content'),
					        search: params.term,
					        page: params.page || 1,
					        length : 10,
					    }
			        },
			        processResults: function (data, params) {
			        	var json = JSON.parse(JSON.stringify(data));
			        	parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
					    params.page = params.page || 1;
					    return {
					        results: data.data,
					        pagination: {
					            more: (params.page * 10) < data.recordsFiltered
					        }
					    };
					}              
			    }
			});

			form.submit(function(e) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: form.attr('action'),
						data: { 
								csrf_bctp_bo_token : $('meta[name=csrf_token]').attr('content'), 
								from_date:  $('#from_date').val(),
								type:  $('#type').val(),
								upline:  $('#upline').val(),
								username : $('#username').val(),
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
							var message = json.msg;
							var msg_icon = 2;
							
							$('meta[name=csrf_token]').attr('content', json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								var obj = $('.card-body');
								
								if (obj.is(':visible')) {
									$('#report-table').DataTable().ajax.reload();
									loadTotal();
								}
								else {
									obj.show();
									loadTable();
									loadTotal();
								}
							}
							else {
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							}
						},
						error: function (request,error) {
						}
					});  
				}
				
				return false;
			});
		});
		
		function loadTable() {
			$('#report-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"responsive": false,
				"filter": false,
				"deferRender": true,
				"pageLength" : 10,
				<?php if(date('n') == "1"){ ?>
				"order": [[3, "desc"]],
				<?php }else if(date('n') == "2"){ ?>
				"order": [[4, "desc"]],
				<?php }else if(date('n') == "3"){ ?>
				"order": [[5, "desc"]],
				<?php }else if(date('n') == "4"){ ?>
				"order": [[6, "desc"]],
				<?php }else if(date('n') == "5"){ ?>
				"order": [[7, "desc"]],
				<?php }else if(date('n') == "6"){ ?>
				"order": [[8, "desc"]],
				<?php }else if(date('n') == "7"){ ?>
				"order": [[9, "desc"]],
				<?php }else if(date('n') == "8"){ ?>
				"order": [[10, "desc"]],
				<?php }else if(date('n') == "9"){ ?>
				"order": [[11, "desc"]],
				<?php }else if(date('n') == "10"){ ?>
				"order": [[12, "desc"]],
				<?php }else if(date('n') == "11"){ ?>
				"order": [[13, "desc"]],
				<?php }else if(date('n') == "12"){ ?>
				"order": [[14, "desc"]],
				<?php }else{ ?>
				"order": [[15, "desc"]],
				<?php } ?>
				"ajax": {
					"url": "<?php echo site_url('report/yearly_report_listing');?>",
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
					{"targets": [0], "visible": false},
					{"targets": [2], "visible": false},
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
		}

		function loadTotal(){
			$('span#total_value_jan').removeClass();
			$('span#total_value_jan').html("0.00");
			$('span#total_value_feb').removeClass();
			$('span#total_value_feb').html("0.00");
			$('span#total_value_mar').removeClass();
			$('span#total_value_mar').html("0.00");
			$('span#total_value_apr').removeClass();
			$('span#total_value_apr').html("0.00");
			$('span#total_value_may').removeClass();
			$('span#total_value_may').html("0.00");
			$('span#total_value_jun').removeClass();
			$('span#total_value_jun').html("0.00");
			$('span#total_value_jul').removeClass();
			$('span#total_value_jul').html("0.00");
			$('span#total_value_aug').removeClass();
			$('span#total_value_aug').html("0.00");
			$('span#total_value_sep').removeClass();
			$('span#total_value_sep').html("0.00");
			$('span#total_value_oct').removeClass();
			$('span#total_value_oct').html("0.00");
			$('span#total_value_nov').removeClass();
			$('span#total_value_nov').html("0.00");
			$('span#total_value_dec').removeClass();
			$('span#total_value_dec').html("0.00");
			$('span#total_value_total').removeClass();
			$('span#total_value_total').html("0.00");
			$.ajax({url: '<?php echo base_url('report/yearly_report_total/');?>',
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
				},
				complete: function() {
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					$('meta[name=csrf_token]').attr('content', json.csrfHash);
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						if(json.total_data.total_downline > 0){
							if(json.total_data.value_jan>0){var deposit_class = "text-primary";}else{if(json.total_data.value_jan<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_jan').removeClass().addClass(deposit_class);

							if(json.total_data.value_feb>0){var deposit_class = "text-primary";}else{if(json.total_data.value_feb<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_feb').removeClass().addClass(deposit_class);

							if(json.total_data.value_mar>0){var deposit_class = "text-primary";}else{if(json.total_data.value_mar<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_mar').removeClass().addClass(deposit_class);

							if(json.total_data.value_apr>0){var deposit_class = "text-primary";}else{if(json.total_data.value_apr<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_apr').removeClass().addClass(deposit_class);

							if(json.total_data.value_may>0){var deposit_class = "text-primary";}else{if(json.total_data.value_may<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_may').removeClass().addClass(deposit_class);

							if(json.total_data.value_jun>0){var deposit_class = "text-primary";}else{if(json.total_data.value_jun<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_jun').removeClass().addClass(deposit_class);

							if(json.total_data.value_jul>0){var deposit_class = "text-primary";}else{if(json.total_data.value_jul<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_jul').removeClass().addClass(deposit_class);

							if(json.total_data.value_aug>0){var deposit_class = "text-primary";}else{if(json.total_data.value_aug<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_aug').removeClass().addClass(deposit_class);

							if(json.total_data.value_sep>0){var deposit_class = "text-primary";}else{if(json.total_data.value_sep<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_sep').removeClass().addClass(deposit_class);

							if(json.total_data.value_oct>0){var deposit_class = "text-primary";}else{if(json.total_data.value_oct<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_oct').removeClass().addClass(deposit_class);

							if(json.total_data.value_nov>0){var deposit_class = "text-primary";}else{if(json.total_data.value_nov<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_nov').removeClass().addClass(deposit_class);

							if(json.total_data.value_dec>0){var deposit_class = "text-primary";}else{if(json.total_data.value_dec<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_dec').removeClass().addClass(deposit_class);

							if(json.total_data.value_total>0){var deposit_class = "text-primary";}else{if(json.total_data.value_total<0){var deposit_class = "text-danger";}else{var deposit_class = "text-dark";}}
							$('span#total_value_total').removeClass().addClass(deposit_class);

							$('span#total_value_jan').html(parseFloat(json.total_data.value_jan).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_feb').html(parseFloat(json.total_data.value_feb).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_mar').html(parseFloat(json.total_data.value_mar).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_apr').html(parseFloat(json.total_data.value_apr).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_may').html(parseFloat(json.total_data.value_may).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_jun').html(parseFloat(json.total_data.value_jun).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_jul').html(parseFloat(json.total_data.value_jul).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_aug').html(parseFloat(json.total_data.value_aug).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_sep').html(parseFloat(json.total_data.value_sep).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_oct').html(parseFloat(json.total_data.value_oct).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_nov').html(parseFloat(json.total_data.value_nov).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_dec').html(parseFloat(json.total_data.value_dec).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
							$('span#total_value_total').html(parseFloat(json.total_data.value_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

						}
					}
				},
				error: function (request,error) {
				}
			}); 
		}

		function exportData(){
			$.ajax({url: '<?php echo base_url("export/yearly_report_export_check");?>',
				type: 'get',								
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data) {
					var message = '';
					var msg_icon = 2;
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						message = json.msg.general_error;
						msg_icon = 1;
						var form_excel = $('#export_form').submit();
					}else{
						message = json.msg.general_error;
					}
					parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				},
				error: function (request,error){
				}
			});
		}
	</script>	
</body>
</html>
