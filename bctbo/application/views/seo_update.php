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
							<?php echo form_open('seo/update', array('id' => 'seo-form', 'name' => 'seo-form', 'class' => 'form-horizontal'));?>
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
															if(isset($seo_id) && $seo_id == $k){
																echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
															}else{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="page_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_page_title');?></label>
										<div class="col-10">
											<input type="text" class="form-control" id="page_title" name="page_title" value="<?php echo (isset($page_title) ? $page_title : '');?>">
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="meta_keywords" class="col-2 col-form-label"><?php echo $this->lang->line('label_meta_keywords');?></label>
										<div class="col-10">
											<textarea class="form-control" rows="3" id="meta_keywords" name="meta_keywords"><?php echo (isset($meta_keywords) ? $meta_keywords : '');?></textarea>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="meta_descriptions" class="col-2 col-form-label"><?php echo $this->lang->line('label_meta_descriptions');?></label>
										<div class="col-10">
											<textarea class="form-control" rows="3" id="meta_descriptions" name="meta_descriptions"><?php echo (isset($meta_descriptions) ? $meta_descriptions : '');?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="meta_header" class="col-2 col-form-label"><?php echo $this->lang->line('label_seo_header');?></label>
										<div class="col-10">
											<textarea class="form-control" id="meta_header" name="meta_header" rows="25"><?php echo (isset($meta_header) ? $meta_header : '');?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="domain" class="col-2 col-form-label"><?php echo $this->lang->line('label_domain');?></label>
										<div class="col-10">
											<?php 
												$domain_text = SYSTEM_DEFAULT_DOMAIN;
												if(!empty($domain)){
													$arr = explode(',', $domain);
													$arr = array_values(array_filter($arr));

													$domain_text = implode("##",$arr);
												}
											?>
											<textarea class="form-control" id="domain" name="domain" rows="3"><?php echo $domain_text;?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-2 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-10">
											<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="seo_key_id" name="seo_key_id" value="<?php echo (isset($seo_key_id) ? $seo_key_id : '');?>">
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
									msg_icon = 1;
									parent.$('#uc1_' + json.response.id).html(json.response.page_title);
									parent.$('#uc2_' + json.response.id).html(json.response.meta_keywords);
									parent.$('#uc6_' + json.response.id).html(json.response.meta_descriptions);
									parent.$('#uc4_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc5_' + json.response.id).html(json.response.updated_date);
									parent.$('#uc7_' + json.response.id).html(json.response.domain);

									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc3_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc3_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									parent.layer.close(index);
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
