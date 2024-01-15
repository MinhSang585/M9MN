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
							<?php echo form_open('message/player_submit', array('id' => 'message_player-form', 'name' => 'message_player-form', 'class' => 'form-horizontal'));?>
							<div class="card-body">
								<div class="form-group row">
									<label for="system_message_title" class="col-2 col-form-label"><?php echo $this->lang->line('label_message_name');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($system_message_name) ? $system_message_name : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="system_message_type" class="col-2 col-form-label"><?php echo $this->lang->line('label_type');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($system_message_type) ? $this->lang->line(get_message_type($system_message_type)) : '-');?></label>
									</div>
								</div>

								<div class="form-group row">
									<label for="system_message_genre" class="col-2 col-form-label"><?php echo $this->lang->line('label_genre');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($system_message_genre) ? $this->lang->line(get_message_genre($system_message_genre)) : '-');?></label>
									</div>
								</div>

								<div class="form-group row">
									<label for="system_message_remark" class="col-2 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (isset($system_message_remark) ? $system_message_remark : '');?></label>
									</div>
								</div>
								<?php if($system_message_genre == MESSAGE_GENRE_ALL){ ?>

								<?php }else if($system_message_genre == MESSAGE_GENRE_USER_LEVEL){ ?>
								<div class="form-group row">
									<label for="player_level" class="col-2 col-form-label"><?php echo $this->lang->line('label_player_level');?></label>
									<div class="col-10">
										<select class="form-control select2bs4 col-12" id="player_level" name="player_level">
											<option value=""><?php echo $this->lang->line('place_holder_please_select_player_level');?></option>
											<?php
												if(isset($level) && sizeof($level)>0){
													foreach($level as $level_row)
													{
														echo '<option value="' . $level_row['level_id'] . '">' . $level_row['level_name']."( ". $level_row['level_number'] ." )" . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<?php }else if($system_message_genre == MESSAGE_GENRE_BANK_CHANNEL){ ?>
								<div class="form-group row">
									<label for="bank_channel" class="col-2 col-form-label"><?php echo $this->lang->line('label_bank_channel');?></label>
									<div class="col-10">
										<select class="form-control select2bs4 col-12" id="bank_channel" name="bank_channel">
											<option value=""><?php echo $this->lang->line('place_holder_please_select_bank_channel');?></option>
											<?php
												if(isset($channel) && sizeof($channel)>0){
													foreach($channel as $channel_row)
													{
														echo '<option value="' . $channel_row['bank_channel_id'] . '">' . $channel_row['bank_group_name'] . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<?php }else if($system_message_genre == MESSAGE_GENRE_USER_ALL){ ?>
								<div class="form-group row">
									<div class="col-6">
										<div class="form-group row">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_from_date');?></label>
											<div class="col-8 input-group date" id="from_date_click" data-target-input="nearest">
												<input type="text" id="from_date" name="from_date" class="form-control form-control-sm col-12 datetimepicker-input" value="" data-target="#from_date_click"/>
												<div class="input-group-append" data-target="#from_date_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group row">
											<label class="col-4 col-form-label col-form-label-sm font-weight-normal"><?php echo $this->lang->line('label_to_date');?></label>
											<div class="col-8 input-group date" id="to_date_click" data-target-input="nearest">
												<input type="text" id="to_date" name="to_date" class="form-control form-control-sm col-12 datetimepicker-input" value="" data-target="#to_date_click"/>
												<div class="input-group-append" data-target="#to_date_click" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_channel" class="col-2 col-form-label"><?php echo $this->lang->line('label_agent');?></label>
									<div class="col-10">
										<input type="text" class="form-control" id="agent" name="agent" value="">
									</div>
								</div>
								<?php }else{ ?>
								<div class="form-group row">
									<label for="username" class="col-2 col-form-label"><?php echo $this->lang->line('label_username');?></label>
									<div class="col-10">
										<textarea class="form-control" id="username" name="username" rows="10"></textarea>
										<!--
										<select class="form-control select2bs4 col-12" id="username" name="username[]" multiple>
												
										</select>
										-->
											<!--
										<select class="select2bs4 col-12" id="username" name="username[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
										</select>-->
									</div>
								</div>
								<?php } ?>
								<?php
									$tab_html = '';
									$content_html = '';
									$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
									if(sizeof($lang) > 0)
									{
										$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">';
										$content_html .= '<div class="tab-content" id="custom-content-below-tabContent">';
										foreach($lang as $k => $v)
										{
											$tab_active = (($k == 0) ? 'active' : '');
											$tab_html .= '<li class="nav-item">';
											$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-' . $v . '" role="tab" aria-controls="custom-content-below-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
											$tab_html .= '</li>';
										
											$content_active = (($k == 0) ? 'show active' : '');
											$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-' . $v . '-tab">';
												$content_html .= '<div class="tab-content" id="custom-content-below-'.$v.'-tabContent">';
														$content_html .= '<div class="form-group row mt-3">';
															$content_html .= '<label for="message_title-' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_message_title') . '</label>';
															$content_html .= '<div class="col-10">';
																$content_html .= '<input type="text" class="form-control" id="message_title_' . $v . '" name="message_title_' . $v . '" value="'.(isset($message_lang[$v]['system_message_title']) ? $message_lang[$v]['system_message_title'] : '').'">';
															$content_html .= '</div>';
														$content_html .= '</div>';
														$content_html .= '<div class="form-group row mt-3">';
															$content_html .= '<label for="message_content-' . $v . '" class="col-2 col-form-label">' . $this->lang->line('label_content') . '</label>';
															$content_html .= '<div class="col-10">';
																$content_html .= '<textarea class="form-control" id="message_content_' . $v . '" name="message_content_' . $v . '" rows="10">'.(isset($message_lang[$v]['system_message_content']) ? $message_lang[$v]['system_message_content'] : '').'</textarea>';
															$content_html .= '</div>';
														$content_html .= '</div>';

												$content_html .= '</div>';		
											$content_html .= '</div>';
										}
										$tab_html .= '</ul>';
										$content_html .= '</div>';
									}

									$html = $tab_html . $content_html;
									echo $html;
								?>

								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="system_message_id" name="system_message_id" value="<?php echo (isset($system_message_id) ? $system_message_id : '');?>">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_add');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
							</div>
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
			$('.select2').select2();
			$('#from_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                buttons: {
	                showToday: true,
	                showClear: true,
	                showClose: true
	            },
	            icons: {
	                today: 'fas fa-caret-square-right',
	                time: "fa fa-clock",
	                clear: 'fa fa-trash',
	                close: 'fas fa-times-circle'
	            },
            });
            $('#to_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
				buttons: {
	                showToday: true,
	                showClear: true,
	                showClose: true
	            },
	            icons: {
	                today: 'fas fa-caret-square-right',
	                time: "fa fa-clock",
	                clear: 'fa fa-trash',
	                close: 'fas fa-times-circle'
	            },
            });
			/*
			$('#username').select2({
				placeholder: '<?php echo $this->lang->line('place_holder_select_username');?>',
       			minimumInputLength: 1,
       			allowClear: true,
       			language: {
				    inputTooShort: function() {
				        return '<?php echo $this->lang->line('select_language_minimum_input_length_one');?>';
				    }
				},
       			ajax: {
			        url: '<?php echo base_url('player/username_search');?>',
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
			*/

			var is_allowed = true;
			var form = $('#message_player-form');
			
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
								var message = '';
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#bank_channel-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.player_level_error != '') {
										message = json.msg.player_level_error;
									}
									else if(json.msg.bank_channel_error != '') {
										message = json.msg.bank_channel_error;
									}
									else if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.system_message_id_error != '') {
										message = json.msg.system_message_id_error;
									}
									else if(json.msg.agent_error != '') {
										message = json.msg.agent_error;
									}
									else if(json.msg.from_date_error != '') {
										message = json.msg.from_date_error;
									}
									else if(json.msg.to_date_error != '') {
										message = json.msg.to_date_error;
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
			
			form.validate({
				rules: {
					<?php if($system_message_genre == MESSAGE_GENRE_ALL){ ?>

					<?php }else if($system_message_genre == MESSAGE_GENRE_USER_LEVEL){ ?>
					player_level: {
						required: true
					},
					<?php }else if($system_message_genre == MESSAGE_GENRE_BANK_CHANNEL){ ?>
					bank_channel: {
						required: true
					},
					<?php }else{ ?>
					username: {
						required: true
					},
					<?php } ?>
				},
				messages: {
					<?php if($system_message_genre == MESSAGE_GENRE_ALL){ ?>

					<?php }else if($system_message_genre == MESSAGE_GENRE_USER_LEVEL){ ?>
					player_level: {
						required: "<?php echo $this->lang->line('error_select_player_level');?>",
					},
					<?php }else if($system_message_genre == MESSAGE_GENRE_BANK_CHANNEL){ ?>
					bank_channel: {
						required: "<?php echo $this->lang->line('error_select_bank_channel');?>",
					},
					<?php }else{ ?>
					username: {
						required: "<?php echo $this->lang->line('error_enter_player_username');?>",
					},
					<?php } ?>
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
</body>
</html>
