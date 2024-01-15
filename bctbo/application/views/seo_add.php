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
							<!-- form start -->
							<?php echo form_open('seo/submit', array('id' => 'seo-form', 'name' => 'seo-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-2 col-form-label"><?php echo $this->lang->line('label_name');?></label>
										<div class="col-10">
											<select class="form-control select2bs4 col-12" id="seo_id" name="seo_id">
												<?php
													$get_seo_page = get_seo_page();
													if(isset($get_seo_page) && sizeof($get_seo_page)>0){
														foreach($get_seo_page as $k => $v)
														{
															echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="page_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_page_title');?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="page_title" name="page_title" value="">
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="meta_keywords" class="col-2 col-form-label"><?php echo $this->lang->line('label_meta_keywords');?></label>
										<div class="col-10">
											<textarea class="form-control" rows="3" id="meta_keywords" name="meta_keywords"></textarea>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="meta_descriptions" class="col-2 col-form-label"><?php echo $this->lang->line('label_meta_descriptions');?></label>
										<div class="col-10">
											<textarea class="form-control" rows="3" id="meta_descriptions" name="meta_descriptions"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="meta_header" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_header');?></label>
										<div class="col-10">
											<textarea class="form-control" id="meta_header" name="meta_header" rows="25"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="domain" class="col-2 col-form-label"><?php echo $this->lang->line('label_domain');?></label>
										<div class="col-10">
											<textarea class="form-control" id="domain" name="domain" rows="3"><?php echo SYSTEM_DEFAULT_DOMAIN;?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-10">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
							<?php echo form_close();?>
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
			var is_allowed = true;
			var form = $('#seo-form');
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						$.ajax({url: form.attr('action'),
							data: form.serialize(),
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
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#seo-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.page_title_error != ''){
										message = json.msg.page_title_error;
									}
									else if(json.msg.meta_keywords_error != '') {
										message = json.msg.meta_keywords_error;
									}
									else if(json.msg.meta_descriptions_error != '') {
										message = json.msg.meta_descriptions_error;
									}
									else if(json.msg.seo_id_error != '') {
										message = json.msg.seo_id_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate();
		});
	</script>
</body>
</html>