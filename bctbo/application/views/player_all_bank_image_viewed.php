<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fileinput/css/fileinput.css');?>">
	<style type="text/css">
		.theme-fa5{
			width: 100%;
		}
		.kv-file-remove{
			display: none;
		}
	</style>
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
							<div class="card-body">
								<?php
									if(!empty($player_bank_data)){
										foreach($player_bank_data as $player_bank_data_row){
								?>
								<?php echo form_open('bank/player_verify_update', array('id' => 'bank_player-form_'.$player_bank_data_row['player_bank_id'], 'name' => 'bank_player-form_'.$player_bank_data_row['player_bank_id'], 'class' => 'form-horizontal bank_player_form_actual'));?>
								<div class="form-group row" id="player_verify_div_<?php echo $player_bank_data_row['player_bank_id']; ?>">
									<div class="mb-2 col-6 col-md-2">
										<div class="form-group row">
											<label for="username" class="col-form-label"><?php echo $this->lang->line('label_username');?>：<?php echo (( ! empty($player_bank_data_row['username'])) ? $player_bank_data_row['username'] : '-');?></label>
										</div>
									</div>
									<div class="mb-2 col-6 col-md-2">
										<div class="form-group row">
											<label for="bank_id" class="col-form-label"><?php echo $this->lang->line('label_bank_name');?>：<?php for($i=0;$i<sizeof($bank_list);$i++){ if(isset($player_bank_data_row['bank_id'])){ if($bank_list[$i]['bank_id'] == $player_bank_data_row['bank_id']){ echo '<label class="col-form-label font-weight-normal">'.$bank_list[$i]['bank_name'].'</label>';}}} ?></label>
										</div>
									</div>
									<div class="mb-2 col-6 col-md-2">
										<label for="bank_account_name" class="col-form-label"><?php echo $this->lang->line('label_bank_account_name');?>：<?php echo (( ! empty($player_bank_data_row['bank_account_name'])) ? $player_bank_data_row['bank_account_name'] : '-');?></label>
									</div>
									<div class="mb-2 col-6 col-md-2">
										<label for="bank_account_no" class="col-form-label"><?php echo $this->lang->line('label_bank_account_no');?>：<?php echo (( ! empty($player_bank_data_row['bank_account_no'])) ? $player_bank_data_row['bank_account_no'] : '-');?></label>
									</div>
									<div class="mb-2 col-6 col-md-2">
										<div class="form-group row">
											<label for="active" class="col-3 col-form-label"><?php echo $this->lang->line('label_verify');?></label>
											<div class="col-9">
												<input type="checkbox" id="verify_<?php echo $player_bank_data_row['player_bank_id']; ?>" name="verify" value="1" <?php echo ((isset($player_bank_data_row['verify']) && $player_bank_data_row['verify'] == STATUS_VERIFY) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
									</div>
									<div class="mb-2 col-6 col-md-2">
										<input type="hidden" name="player_bank_id" value="<?php echo $player_bank_data_row['player_bank_id']; ?>">
										<?php if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE){ ?>
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<?php } ?>
										<?php if(permission_validation(PERMISSION_BANK_PLAYER_USER_DELETE) == TRUE){ ?>
										<button type="button" class="btn btn-danger" onclick="delete_player_bank(<?php echo $player_bank_data_row['player_bank_id']; ?>)"><?php echo $this->lang->line('button_delete');?></button>
										<?php } ?>
									</div>
									<div class="mb-2 col-6 col-md-3" style="display: none;">
										<label for="bank_account_address" class="col-form-label"><?php echo $this->lang->line('label_bank_account_address');?>：<?php echo (( ! empty($player_bank_data_row['bank_account_address'])) ? $player_bank_data_row['bank_account_address'] : '-');?></label>
									</div>
								</div>
								<?php echo form_close();?>
								<?php }} ?>
								<div class="form-group row">
									<div class="mb-2 col-12 col-md-6">
										<div class="form-group row mt-3 p">
											<label for="bank_player_bank" class="col-form-label"><?php echo $this->lang->line('bank_player_bank');?></label>
										</div>
										<div class="form-group row mt-3 p-3">
											<div class="file-loading">
									            <input id="file-bank" name="file-bank[]" type="file" multiple>
									        </div>
										</div>
									</div>
									<div class="mb-2 col-12 col-md-6">
										<div class="form-group row mt-3">
											<label for="bank_player_credit_card" class="col-form-label"><?php echo $this->lang->line('bank_player_credit_card');?></label>
										</div>
										<div class="form-group row mt-3 p-3">
											<div class="file-loading">
									            <input id="file-creditcard" name="file-creditcard[]" type="file" multiple>
									        </div>
										</div>
									</div>
								</div>
								<?php echo form_open_multipart('imageupload/rotate_player_bank_image', array('id' => 'rotate_player_bank_image-form')); ?>
								<input type="hidden" name="image_src_destination_alt" class="image_src_destination_alt">
								<input type="hidden" name="image_src_destination_value" class="image_src_destination_value">
								<input type="hidden" name="image_rotate_value" class="image_rotate_value">
								<?php echo form_close(); ?>
								<a onclick="runRotateIcon()" class="image_rotate_btn"></a>
							</div>
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
	<script src="<?php echo base_url('assets/plugins/fileinput/js/plugins/piexif.js');?>"></script>
	<script src="<?php echo base_url('assets/plugins/fileinput/js/plugins/sortable.js');?>"></script>
	<script src="<?php echo base_url('assets/plugins/fileinput/js/fileinput.js?v=3');?>"></script>
	<script src="<?php echo base_url('assets/plugins/fileinput/js/locales/zh-TW.js');?>"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			
			$('.select2').select2();
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});


			var form = $('.bank_player_form_actual');

			form.submit(function(event){
				var actual_form = $('#'+this.id);
				var form_id = this.id;
				var file_form = actual_form[0];
                var formData = new FormData(file_form);
                if(is_allowed == true) {
                	is_allowed = false;
                	$.ajax({url: actual_form.attr('action'),
						data: formData,
                        type: 'post',
                        processData: false,
                        contentType: false,
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
							
							parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								message = json.msg;
								msg_icon = 1;
							}
							else {
								if(json.msg.player_bank_id_error != '') {
									message = json.msg.player_bank_id_error;
								}
								else if(json.msg.general_error != '') {
									message = json.msg.general_error;
								}
							}
							
							parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
						}
                    });
                }
                event.preventDefault();
			});

			var rotate_player_bank_image_form = $('#rotate_player_bank_image-form');
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						$.ajax({url: rotate_player_bank_image_form.attr('action'),
							data: rotate_player_bank_image_form.serialize(),
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
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			rotate_player_bank_image_form.validate({
				rules: {
				},
				messages: {
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
	<script type="text/javascript">
		function viewImageData(username,type,filename) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '1000px'), ((browser_width < 600) ? '100%': '800px')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_bank_account_setting');?>',
				content: '<?php echo base_url('bank/player_bank_image_edit?username=');?>' + username + "&type=" +  type + "&filename=" + filename
			});
		}
	</script>
	<script type="text/javascript">
	    $('#file-creditcard').fileinput({
	    	language: 'zh-TW',
	    	uploadUrl: "<?php echo base_url('imageupload/upload_player_bank_image')?>",
	    	uploadExtraData: {
	    		'username': '<?php echo $username;?>',
	    		'type': '<?php echo BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;?>',
	    	},
	    	maxFileCount: 5,
	    	allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
	    	showCancel: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	       		<?php if(isset($credit_card_image_list) && sizeof($credit_card_image_list)>0){ ?>
					<?php foreach($credit_card_image_list as $credit_card_image_list_row){ ?>
				"<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD."/".$credit_card_image_list_row.'?v='.rand(1000,9999);?>",			
					<?php } ?>
				<?php } ?>
	        ],
	        initialPreviewConfig: [
	        	<?php
	        		if(isset($credit_card_image_list) && sizeof($credit_card_image_list)>0){
	        			$i = 1;
	        	?>
					<?php foreach($credit_card_image_list as $credit_card_image_list_row){ ?>
				{
					caption: "<?php echo $credit_card_image_list_row;?>",
					size: 329892,
					width: "120px",
					url: "<?php echo base_url('imageupload/delete_player_bank_image')?>?username=<?php echo $username;?>&type=<?php echo BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;?>&image_name=<?php echo $credit_card_image_list_row;?>",
					key: "<?php echo $i;?>",
				},
					<?php
						$i++;
						}
					?>
				<?php } ?>
	        ],
	        overwriteInitial: false,
	        theme: 'fa5',
	        deleteUrl: "<?php echo base_url('bank/delete_player_bank_image')?>?username=<?php echo $username;?>&type=<?php echo BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;?>",
	        //icon setup
	        fileActionSettings: {
	        	showUpload: false,
	        	dragIcon: '<i class="fas fa-hand-spock text-info"></i>',
	        	rotateIcon: '<i class="fas fa-sync text-purple"></i>',
	        	uploadIcon: '<i class="fas fa-cloud-upload-alt text-success"></i>',
	        	zoomIcon: '<i class="fas fa-search-plus text-primary"></i>',
	        	removeIcon: '<i class="fas fa-trash-alt text-danger"></i>',
	        },
	        previewZoomButtonIcons:{
	        	prev: '<i class="far fa-arrow-alt-circle-left text-info"></i>',
	        	next: '<i class="far fa-arrow-alt-circle-right text-info"></i>',
	        	rotate: '<i class="fas fa-sync text-purple"></i>',
	        	toggleheader: '<i class="fas fa-expand-arrows-alt text-warning"></i>',
	        	fullscreen: '<i class="far fa-window-maximize text-success"></i>',
	        	borderless: '<i class="fas fa-search-plus text-primary"></i>',
	        	close: '<i class="fas fa-window-close text-danger"></i>',
	        }
        }).on('fileuploaded', function(event, previewId, index, fileId) {
	        //console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
	    }).on('fileuploaderror', function(event, data, msg) {
	        //console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
	    }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
	        //console.log('File Batch Uploaded', preview, config, tags, extraData);
	        location.reload();
	    });

	    $('#file-bank').fileinput({
	    	language: 'zh-TW',
	    	uploadUrl: "<?php echo base_url('imageupload/upload_player_bank_image')?>",
	    	uploadExtraData: {
	    		'username': '<?php echo $username;?>',
	    		'type': '<?php echo BANKS_PLAYER_USER_BANK_TYPE_BANK;?>',
	    	},
	    	maxFileCount: 5,
	    	allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
	    	showCancel: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	       		<?php if(isset($bank_image_list) && sizeof($bank_image_list)>0){ ?>
					<?php foreach($bank_image_list as $bank_image_list_row){ ?>
				"<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_BANK."/".$bank_image_list_row.'?v='.rand(1000,9999);?>",			
					<?php } ?>
				<?php } ?>
	        ],
	        initialPreviewConfig: [
	        	<?php
	        		if(isset($bank_image_list) && sizeof($bank_image_list)>0){
	        			$i = 1;
	        	?>
					<?php foreach($bank_image_list as $bank_image_list_row){ ?>
				{
					caption: "<?php echo $bank_image_list_row;?>",
					size: 329892,
					width: "120px",
					url: "<?php echo base_url('imageupload/delete_player_bank_image')?>?username=<?php echo $username;?>&type=<?php echo BANKS_PLAYER_USER_BANK_TYPE_BANK;?>&image_name=<?php echo $bank_image_list_row;?>",
					key: "<?php echo $i;?>",
				},
					<?php
						$i++;
						}
					?>
				<?php } ?>
	        ],
	        overwriteInitial: false,
	        theme: 'fa5',
	        deleteUrl: "<?php echo base_url('bank/delete_player_bank_image')?>?username=<?php echo $username;?>&type=<?php echo BANKS_PLAYER_USER_BANK_TYPE_BANK;?>",
	        //icon setup
	        fileActionSettings: {
	        	showUpload: false,
	        	dragIcon: '<i class="fas fa-hand-spock text-info"></i>',
	        	rotateIcon: '<i class="fas fa-sync text-purple"></i>',
	        	uploadIcon: '<i class="fas fa-cloud-upload-alt text-success"></i>',
	        	zoomIcon: '<i class="fas fa-search-plus text-primary"></i>',
	        	removeIcon: '<i class="fas fa-trash-alt text-danger"></i>',
	        },
	        previewZoomButtonIcons:{
	        	prev: '<i class="far fa-arrow-alt-circle-left text-info"></i>',
	        	next: '<i class="far fa-arrow-alt-circle-right text-info"></i>',
	        	rotate: '<i class="fas fa-sync text-purple"></i>',
	        	toggleheader: '<i class="fas fa-expand-arrows-alt text-warning"></i>',
	        	fullscreen: '<i class="far fa-window-maximize text-success"></i>',
	        	borderless: '<i class="fas fa-search-plus text-primary"></i>',
	        	close: '<i class="fas fa-window-close text-danger"></i>',
	        }
        }).on('fileuploaded', function(event, previewId, index, fileId) {
	        //console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
	    }).on('fileuploaderror', function(event, data, msg) {
	        //console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
	    }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
	        //console.log('File Batch Uploaded', preview, config, tags, extraData);
	        location.reload();
	    });

	    function runRotateIcon(){
	    	var rotate_player_bank_image_form = $('#rotate_player_bank_image-form');
	    	rotate_player_bank_image_form.submit();
	    }

	    function delete_player_bank(id) {
			layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
				title: '<?php echo $this->lang->line('label_info');?>',
				btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
			}, function() {
				$.get('<?php echo base_url('bank/player_delete/');?>' + id, function(data) {
					var json = JSON.parse(JSON.stringify(data));
					var message = json.msg;
					var msg_icon = 2;
					
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						msg_icon = 1;
						$('#player_verify_div_'+id).hide();
					}
					
					layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				});
			});
		}
	</script>
</body>
</html>