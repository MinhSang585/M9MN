	<!-- Begin Footer -->
	<!-- Begin Footer 1st Bar -->
	<div class="container-fluid p-0" style="background: linear-gradient(#ffa100, #ffd94b);">
		<div class="container py-3">
			<div class="row no-gutters">
				<div class="col-12">
					<div class="footer-nav list-desu">
						<ul>
							<li>
								<a href="<?php echo site_url('about');?>"><?php echo $this->lang->line('page_about_us');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('faq');?>"><?php echo $this->lang->line('page_faq');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('banking');?>"><?php echo $this->lang->line('banking_information');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('contact');?>"><?php echo $this->lang->line('label_contact_us');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('howtojoin');?>"><?php echo $this->lang->line('how_to_join');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('help');?>"><?php echo $this->lang->line('label_how_to_deposit');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('help');?>"><?php echo $this->lang->line('label_how_to_withdrawal');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('help');?>"><?php echo $this->lang->line('label_how_to_transfer');?></a>
							</li>
							<li>
								<a href="<?php echo site_url('terms');?>" style="border:0;"><?php echo $this->lang->line('page_tnc');?></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Footer 1st Bar -->
	
	<!-- Begin Footer 2nd Bar -->
	<div class="container-fluid p-0 footer-bg">
		<div class="container">
			<!-- Begin Cantect Us -->
			<div class="row no-gutters py-3" style="display: none;">
				<div class="col-12">
					<p><?php echo $this->lang->line('label_contact_us');?></p>
				</div>
				<div class="col-md-3 col-sm-6 p-1 contact_us_email_div" <?php echo (isset($this->session->userdata('get_contact_detail')['im_email']) ? '' : 'style="display: none;"'); ?>>
					<img src="<?php echo base_url('assets/ebv2/images/icon/footer_contect_us/icon-email.png'); ?>" alt="contect-us" class="img-fluid" /><span class="footer-contect contact_us_email_content"><?php echo (isset($this->session->userdata('get_contact_detail')['im_email']) ? $this->session->userdata('get_contact_detail')['im_email'] : ''); ?></span>
				</div>
				<div class="col-md-3 col-sm-6 p-1 contact_us_phone_div" <?php echo (isset($this->session->userdata('get_contact_detail')['im_tel']) ? '' : 'style="display: none;"'); ?>>
					<img src="<?php echo base_url('assets/ebv2/images/icon/footer_contect_us/icon-hp.png'); ?>" alt="contect-us" class="img-fluid" /><span class="footer-contect contact_us_phone_content"><?php echo (isset($this->session->userdata('get_contact_detail')['im_tel']) ? $this->session->userdata('get_contact_detail')['im_tel'] : ''); ?></span>
				</div>
				<div class="col-md-3 col-sm-6 p-1 contact_us_whatsapp_div" <?php echo (isset($this->session->userdata('get_contact_detail')['im_whatsapp']) ? '' : 'style="display: none;"'); ?>>
					<img src="<?php echo base_url('assets/ebv2/images/icon/footer_contect_us/icon-whatsapp.png'); ?>" alt="contect-us" class="img-fluid" /><span class="footer-contect contact_us_whatsapp_content"><?php echo (isset($this->session->userdata('get_contact_detail')['im_whatsapp']) ? $this->session->userdata('get_contact_detail')['im_whatsapp'] : ''); ?></span>
				</div>
				<div class="col-md-3 col-sm-6 p-1 contact_us_wechat_div" <?php echo (isset($this->session->userdata('get_contact_detail')['im_wechat']) ? '' : 'style="display: none;"'); ?>>
					<img src="<?php echo base_url('assets/ebv2/images/icon/footer_contect_us/icon-wechat.png'); ?>" alt="contect-us" class="img-fluid" /><span class="footer-contect contact_us_wechat_content"><?php echo (isset($this->session->userdata('get_contact_detail')['im_wechat']) ? $this->session->userdata('get_contact_detail')['im_wechat'] : ''); ?></span>
				</div>
			</div>
			<!-- End Contect End -->
			
			<!-- Begin Payment Method -->
			<div class="row no-gutters bt py-3" style="display:none;">
				<div class="col-12">
					<p><?php echo $this->lang->line('label_payment_method');?></p>
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-maybank.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-cimb.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-hongleong.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-public.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-rhb.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/bank/bank-ambank.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
			</div>
			<!-- End Payment Method -->
			
			<!-- Begin Game Provider -->
			<div class="row no-gutters bt py-3">
				<div class="col-12">
					<p><?php echo $this->lang->line('label_game_provider');?></p>
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_aes.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_bbin.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_cmd368.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_cq9.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_dg.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_evo.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="w-100">&nbsp;</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_haba.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_ibc.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_icg.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_ka.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_microgaming.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_pp.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="w-100">&nbsp;</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_rtg.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_sa.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_sbo.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
				<div class="col-md-2 col-sm-4 p-1">
					<img src="<?php echo base_url('assets/ebv2/images/logo/game/logo_sp.png'); ?>" alt="contect-us" class="img-fluid" />
				</div>
			</div>
			<!-- End Game Provider -->
		</div>
	</div>
	<!-- End Footer 2nd Bar -->		
	
	<!-- Begin Footer 3nd Bar -->
	<div class="container-fluid bt">
		<div class="container p-0">
			<div class="row no-gutters">
				<div class="col-12 text-center">
					<p class="my-2"><?php echo $this->lang->line('label_footer');?></p>
				</div>
			</div>
		</div>
	</div>
	<!-- End Footer 3nd Bar -->
	<!-- End Footer -->
</div>
	
	<a style="display: none;" id="launch_game" target="_blank"></a>
	<a style="display: none;" id="launch_payment_gateway" target="_blank"></a>
	
	<script type="text/javascript" src='<?php echo base_url('assets/comclass/jquery-ui/js/jquery.min.js');?>'></script>
	<script type="text/javascript" src='<?php echo base_url('assets/ebv2/js/jquery.lazy.min.js');?>'></script>
	<script type="text/javascript" src='<?php echo base_url('assets/ebv2/js/layer/layer.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/jquery.datetimepicker.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/datatables/datatables.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/copy2clipboard.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/bootstrap-4.2.1.js/js/bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/bootstrap-4.2.1.js/js/bootstrap.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/ebv2/js/fingerprint2.js');?>"></script>
	<script type="text/javascript">
        <?php if($this->session->userdata('is_logged_in') == true):?>
        var loginSessionInterval = setInterval(verifyLoginSession, 10000);
        function verifyLoginSession() {
            $.ajax({url: "<?php echo site_url('home/verify_session');?>",
                type: 'get',                  
                async: 'true',
                cache: false,
                success: function (data) {
                    if(data != '') {
                        clearInterval(loginSessionInterval);
                        parent.location.href = data;
                    }
                }
            });
        }
        <?php endif;?>
        <?php if($this->uri->segment(1) == 'logout' && $this->uri->segment(2) == 'force'):?>
            <?php if(isset($msg_alert)):?>
                layer.alert('<?php echo $msg_alert;?>', {icon: <?php echo $msg_icon;?>, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
            <?php endif;?>  
        <?php endif;?>
    </script>
	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			
			//Running time
			setInterval(function(){
				var now = getDateNow();
				$('#nowTime').html('<?php echo $this->lang->line('label_timezone');?> '+now);
			},1000);
			
			//Change language
			$("#change_language").change(function() {
				location.href = "<?php echo base_url('ajax/change/');?>" + $('#change_language').val();
			});
			
			//Ammouncement marque
			$.ajax({url: "<?php echo site_url('ajax/announcement');?>",
				type: 'get',                  
				async: 'true',
				success: function (data) {
					$("#announcementContent").html(data);
				}
			});

			<?php 
			if( ! $this->session->userdata('get_contact_list') ||  ! $this->session->userdata('get_contact_detail')) {
				$this->session->set_userdata('get_contact_list', 1)
			?>
			$.ajax({url: "<?php echo site_url('ajax/get_contact_list');?>",
				type: 'get',                  
				async: 'true',
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
		                if(data.list.length>0){
		                	for (i = 0; i < data.list.length; i++) {
		                		if(data.list[i]['im_name'] == "im_whatsapp"){
		                			$('.contact_us_whatsapp_content').html(data.list[i]['im_value']);
		                			$('.contact_us_whatsapp_div').show();
		                		}else if(data.list[i]['im_name'] == "im_wechat"){
		                			$('.contact_us_wechat_content').html(data.list[i]['im_value']);
		                			$('.contact_us_wechat_div').show();
		                		}else if(data.list[i]['im_name'] == "im_email"){
		                			$('.contact_us_email_content').html(data.list[i]['im_value']);
		                			$('.contact_us_email_div').show();
		                		}else if(data.list[i]['im_name'] == "im_tel"){
		                			$('.contact_us_phone_content').html(data.list[i]['im_value']);
		                			$('.contact_us_phone_div').show();
		                		}
		                	}
		                }
		            }
				}
			});
			<?php }?>
			<?php if($this->uri->segment(1) == 'register' && $this->session->userdata('is_logged_in') == FALSE):?>
			//Register account
			get_fingerprint();
			$("#captcha").click(function() {
				$(this).attr("src", "<?php echo site_url('ajax/captcha');?>");
			});
			
			var signup_form = $('#sign-up-form');
			
			signup_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: signup_form.attr('action'),
						data: signup_form.serialize(),
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
							
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ location.href = json.url; layer.closeAll(); is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->session->userdata('is_logged_in') == FALSE):?>
			//Login account
			var loginform = $('#login-form');
			
			loginform.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: loginform.attr('action'),
						data: loginform.serialize(),
						type: 'post',                  
						async: 'true',
						beforeSend: function() {
							layer.load(1);
						},
						complete: function() {
							layer.closeAll('loading');
						},
						success: function (data) {
						    console.log(data);
							var json = JSON.parse(JSON.stringify(data));
							var message = json.msg;
							
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ location.href = json.url; is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'forgot' && $this->session->userdata('is_logged_in') == FALSE):?>
			//Forgot password
			var forgot_form = $('#forgot-password-form');
			
			forgot_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: forgot_form.attr('action'),
						data: forgot_form.serialize(),
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
							
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('#forgot-password-form')[0].reset(); layer.closeAll(); is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && ( ! $this->uri->segment(2) OR $this->uri->segment(1) == 'index') && $this->session->userdata('is_logged_in') == TRUE):?>
			
			//binding bank
			var player_bank_form = $('#player-bank-form');
			
			player_bank_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: player_bank_form.attr('action'),
						data: player_bank_form.serialize(),
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
							
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('#player-bank-form')[0].reset(); layer.closeAll(); is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			
			var player_bank_usdt_form = $('#player-bank-usdt-form');
			
			player_bank_usdt_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: player_bank_usdt_form.attr('action'),
						data: player_bank_usdt_form.serialize(),
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
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('#player-bank-usdt-form')[0].reset(); layer.closeAll(); is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});

			//Change password
			var change_password_form = $('#change-password-form');
			
			change_password_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: change_password_form.attr('action'),
						data: change_password_form.serialize(),
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
							
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('#change-password-form')[0].reset(); layer.closeAll(); is_allowed = true; });
							}
							else
							{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->session->userdata('is_logged_in') == TRUE):?>
			$("#refresh_icon_2").click(function() {
				get_balance();
			});
			
			$("#transfer_all").click(function() {
				transfer_all();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'deposit' && $this->session->userdata('is_logged_in') == TRUE):?>
			$('#amount').keypress(function(e) {
				var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
				if (verified) {e.preventDefault();}
			});
			
			$('#bank_in_date').datetimepicker({
				format: 'Y-m-d',
				timepicker: false,
				onShow: function () {
					this.setOptions({
						maxDate:$('#bank_in_date').attr('dt-value')?$('#bank_in_date').attr('dt-value'):false
					});
				}
			});
			
			//Deposit
			var deposit_form = $('.deposit_form');
            
            deposit_form.submit(function(event){
            	event.preventDefault();
            	var deposit_actual_form = $('#'+this.id);
            	var form_id = this.id;
                var file_form = deposit_actual_form[0];
                var formData = new FormData(file_form);
                $.each($("input[type='file']")[0].files, function(i, file) {
                    formData.append('file', file);
                });

                if(form_id == "deposit-online-form"){
					var bank_type = <?php echo TRANSFER_PG_DEPOSIT;?>;
				}else if(form_id == "deposit-hypermart-form"){
					var bank_type = <?php echo TRANSFER_HYPERMART_DEPOSIT;?>;
				}else if(form_id == "deposit-credit-form"){
					var bank_type = <?php echo TRANSFER_CREDIT_CARD_DEPOSIT;?>;
				}else{
					var bank_type = <?php echo TRANSFER_OFFLINE_DEPOSIT;?>;
				}

                if(is_allowed == true) {
                    is_allowed = false;

                    $.ajax({url: "<?php echo base_url('ajax/get_transaction_notice');?>/"+bank_type+"/",
                        type: 'get',                  
                        async: 'true',
                        beforeSend: function() {
                            layer.load(1);
                        },
                        complete: function() {
                            is_allowed = true;
                        },
                        success: function (data1) {
                            var json = JSON.parse(JSON.stringify(data1));
                            var message = json.msg;
                            if(json.status == '<?php echo EXIT_SUCCESS;?>') {
                                if(json.is_notice == '<?php echo STATUS_ACTIVE;?>'){
                                    layer.confirm(''+json.notice_content+'', {
                                        title: ''+json.notice_title+'',
                                        btn: ['<?php echo $this->lang->line('label_agree');?>', '<?php echo $this->lang->line('label_cancel');?>']
                                    }, function() {
                                        $.ajax({url: deposit_form.attr('action'),
                                            data: formData,
                                            type: 'post',   
                                            processData: false,
                                            contentType: false,                
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
                                                $("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
                                                
                                                if(json.status == '<?php echo EXIT_SUCCESS;?>') {

                                                    layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('.deposit_form').trigger("reset"); layer.closeAll(); is_allowed = true; });
                                                }
                                                else if(json.status == '<?php echo EXIT_CONFIG;?>') { 
                                                    //location.href = json.url;
                                                    $('#launch_payment_gateway').attr('href', json.url);
                                                    $('#launch_payment_gateway')[0].click();
                                                }
                                                else
                                                {
                                                    layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
                                                }
                                            },
                                            error: function (request,error) {
                                            }
                                        });
                                    });
                                }else{
                                    $.ajax({url: deposit_form.attr('action'),
                                        data: formData,
                                        type: 'post',   
                                        processData: false,
                                        contentType: false,                
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
                                            $("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
                                            
                                            if(json.status == '<?php echo EXIT_SUCCESS;?>') {

                                                layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('.deposit_form').trigger("reset"); layer.closeAll(); is_allowed = true; });
                                            }
                                            else if(json.status == '<?php echo EXIT_CONFIG;?>') { 
                                                //location.href = json.url;
                                                $('#launch_payment_gateway').attr('href', json.url);
                                                $('#launch_payment_gateway')[0].click();
                                            }
                                            else
                                            {
                                                layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
                                            }
                                        },
                                        error: function (request,error) {
                                        }
                                    });
                                }
                            }else{
                                layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
                            }
                        }
                    });
                }
            });
			<?php endif;?>
			
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'withdrawal' && $this->session->userdata('is_logged_in') == TRUE):?>
			$(document).ready(function () {
			    $.ajax({url: "<?php echo base_url('ajax/get_player_currently_turnover/');?>",
	                type: 'get',                  
	                async: 'true',
	                beforeSend: function() {
	                    layer.load(1);
	                },
	                complete: function() {
	                    layer.closeAll('loading');
	                    is_allowed_open = true;
	                },
	                success: function (data) {  
	                    var json = JSON.parse(JSON.stringify(data));
	                    if(json.status == '0') {  
	                    	if(json.show_turnover == '0') { 
	                    		$('#capital').val(json.capital);
	                    		$('#target_turnover').val(json.target_turnover);
	                    		$('#current_turnover').val(json.current_turnover);
	                    		$('#div_capital').show();
	                    		$('#div_target_turnover').show();
	                    		$('#div_current_turnover').show();
	                    	}	
	                    }
	                },
	                error: function (request,error) {
	                    //console.log(request);
	                }
	            });
	            
	            $.ajax({url: "<?php echo base_url('ajax/get_player_bank_list/');?>",
	                type: 'get',                  
	                async: 'true',
	                beforeSend: function() {
	                    layer.load(1);
	                },
	                complete: function() {
	                    layer.closeAll('loading');
	                    is_allowed_open = true;
	                },
	                success: function (data) {  
	                    var json = JSON.parse(JSON.stringify(data));
	                    if(json.status == '0') {                          
	                        var i;
	                        for (i = 0; i < json.output.length; i++) {
	                            if(json.output[i]['bank_account_address'] == null){
	                        		$("#player_bank_id").append($('<option></option>').val(json.output[i]['player_bank_id']).html(json.output[i]['bank_account_name'] + " - "+ json.output[i]['bank_account_no'] + " ("+json.output[i]['bank_name'] + " )"));
	                        	}else{
	                        		$("#player_bank_id").append($('<option></option>').val(json.output[i]['player_bank_id']).html(json.output[i]['bank_account_address'] + " ("+json.output[i]['bank_name'] + " )"));
	                        	}
	                        }
	                    }
	                },
	                error: function (request,error) {
	                    //console.log(request);
	                }
	            });

	            $("#player_bank_id").change(function() {
	                $("#nBankAcc3").hide();
	            	$("#nBankAcc4").hide();
	            	$("#nBankAcc6").hide();
	                var option = this.value;
	                if(option != ''){
	                    $.ajax({url: "<?php echo base_url('ajax/get_player_bank_list/');?>"+option,
	                        type: 'get',                  
	                        async: 'true',
	                        beforeSend: function() {
	                            layer.load(1);
	                        },
	                        complete: function() {
	                            layer.closeAll('loading');
	                            is_allowed_open = true;
	                        },
	                        success: function (data) {                  
	                            var json = JSON.parse(JSON.stringify(data));
	                            if(json.status == '0') {
	                                if(json.output['bank_account_address'] == null){
	                            		$("#nBankAcc3").show();
	                            		$("#nBankAcc4").show();
	                            		$("#bank_account_name").val(json.output['bank_account_name']);
	                                	$("#bank_account_no").val(json.output['bank_account_no']);
	                            	}else{
	                            		$("#nBankAcc6").show();
	                            		$("#bank_account_address").val(json.output['bank_account_address']);	
	                            	}
	                            }
	                        },
	                        error: function (request,error) {
	                            //console.log(request);
	                        }
	                    });
	                }else{
	                    $("#bank_account_name").val('');
	                    $("#bank_account_no").val('');
	                }
	           });
	        });
			$('#bank_account_no').keypress(function(e) {
				var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
				if (verified) {e.preventDefault();}
			});
			
			$('#amount').keypress(function(e) {
				var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
				if (verified) {e.preventDefault();}
			});
			
			//Withdrawal
			var withdrawal_form = $('#withdrawal-form');
            withdrawal_form.submit(function(event) {
                event.preventDefault();
                if(is_allowed == true) {
                    is_allowed = false;
                    $.ajax({url: withdrawal_form.attr('action'),
                        data: withdrawal_form.serialize(),
                        type: 'post',                  
                        async: 'true',
                        cache: false,
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
                            
                            $("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
                            
                            if(json.status == '<?php echo EXIT_SUCCESS;?>') {
                                var msg_icon = 1;
                                layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('#withdrawal-form')[0].reset(); layer.closeAll(); is_allowed = true; });
                            }else{
                                layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>'});
                            }
                        },
                        error: function (request,error) {
                        }
                    });
                }
            });
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'transaction_history' && $this->session->userdata('is_logged_in') == TRUE):?>
			$('#from_date').datetimepicker({
				format: 'Y-m-d',
				timepicker: false,
				onShow: function () {
					this.setOptions({
						maxDate:$('#from_date').attr('dt-value')?$('#from_date').attr('dt-value'):false
					});
				},
				onChangeDateTime:function(dp,$input){
					$('.tablinks2').removeClass('active');
				}
			});
			
			$('#to_date').datetimepicker({
				format: 'Y-m-d',
				timepicker: false,
				onShow: function () {
					this.setOptions({
						maxDate:$('#to_date').attr('dt-value')?$('#to_date').attr('dt-value'):false
					});
				},
				onChangeDateTime:function(dp,$input){
					$('.tablinks2').removeClass('active');
				}
			});
			
			//Transaction search
			var transaction_form = $('#transaction-form');
			
			transaction_form.submit(function(event) {
				var transaction_type = $('#transaction_type').val();
				if(is_allowed == true) {
					is_allowed = false;
					
					$.ajax({url: transaction_form.attr('action'),
						data: transaction_form.serialize(),
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
							$('.transaction-table').hide();
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								if($('#transaction-table-'+transaction_type).length){
									$('#dptable-'+transaction_type).show();
									if(transaction_type == <?php echo TRANSACTION_TYPE_DEPOSIT;?>){
										load_table_deposit();
									}else if(transaction_type == <?php echo TRANSACTION_TYPE_WITHDRAWAL;?>){
										load_table_withdrawal();
									}else if(transaction_type == <?php echo TRANSACTION_TYPE_DEPOSIT_POINT;?>){
										load_table_deposit_point();
									}else if(transaction_type == <?php echo TRANSACTION_TYPE_WITHDRAWAL_POINT;?>){
										load_table_withdrawal_point();
									}else if(transaction_type == <?php echo TRANSACTION_TYPE_TRANSFER;?>){
										load_table_transfer();
									}else if(transaction_type == <?php echo TRANSACTION_TYPE_PROMOTION;?>){
										load_table_promotion();
									}else{
										load_table_bet();
									}
								}
								else {
									$('#transaction-table'+transaction_type).DataTable().ajax.reload();
								}
							}
							else
							{
								layer.alert(message, {icon: 2, skin: 'layui-layer-dar', title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->session->userdata('is_logged_in') == TRUE):?>
			get_balance();
					
			setInterval(get_balance, 60000);
			
			$("#refresh_icon").click(function() {
				get_balance();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'slots'):?>
			sub_game('CQ9', '<?php echo GAME_SLOTS;?>');
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'board'):?>
			sub_game('JDB', '<?php echo GAME_BOARD_GAME;?>');
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'fishing'):?>
			sub_game('JDB', '<?php echo GAME_FISHING;?>');
			<?php endif;?>
			
			<?php if($this->session->userdata('msg_alert')):?>
			layer.alert('<?php echo $this->session->userdata('msg_alert');?>', {icon: <?php echo $this->session->userdata('msg_icon');?>, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>'}, function (){ layer.closeAll(); });
			<?php $this->session->unset_userdata(array('msg_alert', 'msg_icon'));?>
			<?php endif;?>
		});
		
		var is_allowed_connect = true;
		
		<?php if($this->session->userdata('is_logged_in') == TRUE):?>
		
		function get_balance()
		{
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				
				$.ajax({url: "<?php echo site_url('ajax/get_balance');?>",
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
						$("#refresh_icon").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refreshing.gif'); ?>');
						$("#refresh_icon_2").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refreshing.gif'); ?>');
					},
					complete: function() {
						$("#refresh_icon").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refresh.png'); ?>');
						$("#refresh_icon_2").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refresh.png'); ?>');
						is_allowed_connect = true;
					},
					success: function (data) {
						wallet_update(data);
					}
				});
			}	
		}
		
		function transfer_all()
		{
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				
				$.ajax({url: "<?php echo site_url('ajax/transfer_all');?>",
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
						$("#refresh_icon").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refreshing.gif'); ?>');
						$("#refresh_icon_2").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refreshing.gif'); ?>');
					},
					complete: function() {
						$("#refresh_icon").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refresh.png'); ?>');
						$("#refresh_icon_2").attr('src', '<?php echo base_url('assets/ebv2/images/icon_refresh.png'); ?>');
						layer.alert('<?php echo $this->lang->line('error_transfer_all_successful');?>', {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed_connect = true; });
					},
					success: function (data) {
						wallet_update(data);
					}
				});
			}	
		}
		
		function wallet_update(data)
		{
			var json = JSON.parse(JSON.stringify(data));
			$("#balance").html(json.total);
			$("#text_curmain").html(json.main_wallet);
			$("#text_curbbin").html(json.bbin_wallet);
			$("#text_curdg").html(json.dg_wallet);
			$("#text_curevo").html(json.evo_wallet);
			$("#text_curmg").html(json.mg_wallet);
			$("#text_cursa").html(json.sa_wallet);
			$("#text_cursx").html(json.sx_wallet);
			$("#text_curpp").html(json.pp_wallet);
			$("#text_cursxbg").html(json.sxbg_wallet);
			$("#text_curog").html(json.og_wallet);
			$("#text_curezg").html(json.ezg_wallet);
			$("#text_curab").html(json.ab_wallet);
			$("#text_curpt").html(json.pt_wallet);
			$("#text_cureb").html(json.eb_wallet);
			$("#text_curwm").html(json.wm_wallet);
			$("#text_curthab").html(json.thab_wallet);
			$("#text_curthvv").html(json.thvv_wallet);
			$("#text_curyb").html(json.yb_wallet);
			$("#text_curob").html(json.ob_wallet);
			$("#text_curn2").html(json.n2_wallet);
			$("#text_cursxvn").html(json.sxvn_wallet);
			$("#text_curcq9").html(json.cq9_wallet);
			$("#text_curhb").html(json.hb_wallet);
			$("#text_curicg").html(json.icg_wallet);
			$("#text_curka").html(json.ka_wallet);
			$("#text_currtg").html(json.rtg_wallet);
			$("#text_cursp").html(json.sp_wallet);
			$("#text_curevop").html(json.evop_wallet);
			$("#text_curspn").html(json.spn_wallet);
			$("#text_curf8").html(json.f8_wallet);
			$("#text_curjdb").html(json.jdb_wallet);
			$("#text_curntd").html(json.ntd_wallet);
			$("#text_cursxjl").html(json.sxjl_wallet);
			$("#text_cursxrt").html(json.sxrt_wallet);
			$("#text_curpgs").html(json.pgsf_wallet);
			$("#text_currl").html(json.rlx_wallet);
			$("#text_curth").html(json.th_wallet);
			$("#text_cursbo").html(json.sbo_wallet);
			$("#text_curibc").html(json.ibc_wallet);
			$("#text_curcmd").html(json.cmd_wallet);
			$("#text_curug").html(json.ug_wallet);
			$("#text_curm8").html(json.m8_wallet);
			$("#text_curlh").html(json.lh_wallet);
			$("#text_cursxes").html(json.sxes_wallet);
			$("#text_curle").html(json.le_wallet);
			$("#text_curky").html(json.ky_wallet);
			$("#text_curbl").html(json.bl_wallet);
			$("#text_cursxkm").html(json.sxkm_wallet);
			$("#text_curig").html(json.ig_wallet);
			$("#text_curvr").html(json.vr_wallet);
			$("#text_curyl").html(json.yl_wallet);
			$("#text_curtotal").html(json.total);
		}

		function calculateActualAmount(value,method = 0){
			if(method > 0){
				var currency_rate = $('#currency_rate_'+method).val();
				$('#actual_amount_'+method).val((currency_rate * value).toFixed(2));
			}else{
				var currency_rate = $('#currency_rate').val();
				$('#actual_amount').val((currency_rate * value).toFixed(2));
			}
		}

		function calculateCurrencyRate(id,type,method = 0){
			if(method>0){
				var amount = $("#amount_"+method).val();
			}else{
				var amount = $("#amount").val();
			}
			$.ajax({url: "<?php echo base_url('ajax/calculate_currency_convert/');?>" + id + "/"+ type + "/" + amount,
				type: 'get',                  
				async: 'true',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
				},
				success: function (data){
					var json = JSON.parse(JSON.stringify(data));
					if(method>0){
						$('#nBankAcc7').show();
						$('#nBankAcc8').show();
						$('#currency_rate_'+method).val(json.currency_rate);
						$('#actual_amount_'+method).val(json.actual_amount);
					}else{
						$('#currency_rate').val(json.currency_rate);
						$('#actual_amount').val(json.actual_amount);
						$('#nBankAcc7').show();
						$('#nBankAcc8').show();
					}
				}
			});
		}
		<?php endif;?>
		
		<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'deposit' && $this->session->userdata('is_logged_in') == TRUE):?>
		function select_payment_method(obj, method, type) {
            $('#deposit_method_div_1').hide();
            $('#deposit_method_div_2').hide();
            $('#deposit_method_div_3').hide();
            $('#deposit_method_div_4').hide();
            $('.tablinks').removeClass('active');
            $(obj).addClass('active');

            $('#deposit_method_div_'+method).show();
        }

        function select_payment_gateway(obj, method, code, is_bank, id){
        	$('.tablinks'+method).removeClass('active');
        	$(obj).addClass('active');
        	var current_gateway = $('#payment_gateway_code_'+method).val();
        	if(current_gateway != code){
        		$('#payment_gateway_bank_div_'+method).hide();
        		$('#payment_gateway_code_'+method).val(code);
	        	$.ajax({url: "<?php echo base_url('ajax/get_payment_gateway_bank_data/');?>"+id,
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
	                    if(json.status == '0') {
							$('#payment_gateway_bank_'+method).empty();                          
	                        var i;
	                        if(json.output.length > 0){
	                        	$('#payment_gateway_bank_div_'+method).show();
	                        	for (i = 0; i < json.output.length; i++) {
		                            $('#payment_gateway_bank_'+method).append($('<option></option>').val(json.output[i]['code']).html(json.output[i]['name']));
		                        }
	                        }
	                        $('#currency_code_display_div_'+method).show();
	                        $('#currency_code_display_'+method).val(json.currency_code);
	                        calculateCurrencyRate(json.currency_id,<?php echo TRANSACTION_TYPE_DEPOSIT;?>,method);
	                    }
					}
				});
        	}
        }

        function select_bank(obj, type, id, baccname, baccno) {
			$('.tablinks1').removeClass('active');
			$(obj).addClass('active');
			$('#bank_account_id').val(id);
			$('#bank_acc_holder').html(baccname);
			$('#bank_acc_no').html(baccno);
		}

        function select_amount(obj, amount, method) {
			$('.fasttablinks'+method).removeClass('active');
			$(obj).addClass('active');
			$('#amount_'+method).val(amount);
			calculateActualAmount(amount,method);
		}
		<?php endif;?>
		
		<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'transaction_history' && $this->session->userdata('is_logged_in') == TRUE):?>
		function select_transaction_type(obj, id) {
			$('.tablinks').removeClass('active');
			$(obj).addClass('active');
			$('#transaction_type').val(id);
		}
		
		function select_transaction_date(obj, id) {
			$('.tablinks2').removeClass('active');
			$(obj).addClass('active');
			var currentday = new Date($('#to_date').val());
			var newday = currentday;
			
			if(id == 1) {
				var today = new Date();
				var d = today.getFullYear() + '-' + ("0"+(today.getMonth()+1)).slice(-2) + '-' + ("0" + today.getDate()).slice(-2);
				$('#from_date').val(d);
				$('#to_date').val(d);
			}
			else {
				switch(id) {
					case 3: newday.setDate(currentday.getDate()-3); break;
					case 7: newday.setDate(currentday.getDate()-7); break;
					case 30: newday.setDate(currentday.getDate()-30); break;
				}
				
				var d = newday.getFullYear() + '-' + ("0"+(newday.getMonth()+1)).slice(-2) + '-' + ("0" + newday.getDate()).slice(-2);
				$('#from_date').val(d);
			}
		}
		
		function load_table() {
			$('#transaction-table').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
					{"targets": [3], "visible": true, className: 'tdC'},
					{"targets": [4], "visible": true, className: 'tdC'}
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
		
		function load_table_deposit(){
			$('#transaction-table-1').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
					{"targets": [3], "visible": true, className: 'tdC'},
					{"targets": [4], "visible": true, className: 'tdC'}
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

		function load_table_withdrawal(){
			$('#transaction-table-2').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
					{"targets": [3], "visible": true, className: 'tdC'},
					{"targets": [4], "visible": true, className: 'tdC'}
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

		function load_table_deposit_point(){
			$('#transaction-table-3').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
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
		function load_table_withdrawal_point(){
			$('#transaction-table-4').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
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
		function load_table_transfer(){
			$('#transaction-table-5').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
					{"targets": [3], "visible": true, className: 'tdC'},
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
		function load_table_promotion(){
			$('#transaction-table-6').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
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
		};

		function load_table_bet(){
			$('#transaction-table-7').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				"scrollX": false,
				"responsive": true,
				"filter": false,
				"pageLength": 10,
				"lengthChange": false,
				"order": [[0, "desc"]],
				"ajax": {
					"url": "<?php echo site_url('ajax/transaction_listing');?>",
					"dataType": "json",
					"type": "POST",
					"data": function (d) {
						d.csrf_bctp_fe_token = $('input[name=csrf_bctp_fe_token]').val();
					},
					"complete": function(response) {
						var json = JSON.parse(JSON.stringify(response));
						
						if(json.status == 200) {
							var json2 = JSON.parse(json.responseText);
							$('input[name=csrf_bctp_fe_token]').val(json2.csrfHash);
						}
					},
				},
				"columnDefs": [
					{"targets": [0], "visible": true, className: 'tdC'},
					{"targets": [1], "visible": true, className: 'tdC'},
					{"targets": [2], "visible": true, className: 'tdC'},
					{"targets": [3], "visible": true, className: 'tdC'},
					{"targets": [4], "visible": true, className: 'tdC'},
					{"targets": [5], "visible": true, className: 'tdC'},
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
		<?php endif;?>
		
		<?php if($this->uri->segment(1) == 'promotion' || ($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'deposit')):?>
		function choose_promo(obj, id) {
			$('.promo-nav-item').removeClass('active');
			$(obj).addClass('active');
			
			if(id == "<?php echo GAME_ALL;?>") {
				$('.promo-box').show();
			}
			else {
				$('.promo-box').hide();
				$('.promo-category-' + id).show();
			}
		}
		
		function open_promo(id) {
			$('body').addClass('modal-open');
			$('#promo-modal-' + id).show();
		}
		
		function close_promo(id) {
			$('body').removeClass('modal-open');
			$('#promo-modal-' + id).hide();
		}
		<?php endif;?>
		
		var is_allowed_open = true;
		
		function open_game(provider_code, game_type_code, game_code)
		{
			if(is_allowed_open == true) {
				is_allowed_open = false;
				
				game_code = ((game_code != undefined) ? game_code : '');
				
				$.ajax({url: "<?php echo base_url('ajax/open_game/');?>" + provider_code + '/' + game_type_code + '/' + game_code,
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');
						is_allowed_open = true;
					},
					success: function (data) {
						var json = JSON.parse(JSON.stringify(data));
						var message = json.msg;
						
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							$('#launch_game').attr('href', json.url);
							$('#launch_game')[0].click();
						}
						else
						{
							layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>'}, function (){ layer.closeAll(); });
						}
					},
					error: function (request,error) {
					}
				});
			}
		}
		
		function open_wallet_balance(){
		    var isHidden = $('#all_wallet_balance').is(':hidden');
		    if(isHidden){
		        $('#all_wallet_balance').removeClass("hidden");
		    }else{
		        $('#all_wallet_balance').addClass("hidden");
		    }
		}
		
		function sub_game(provider_code, game_type_code)
		{
			if(is_allowed_open == true) {
				is_allowed_open = false;
				
				$('.tablinks').removeClass('active');
				$('#tab-' + provider_code.toLowerCase()).addClass('active');
				
				$.ajax({url: "<?php echo base_url('ajax/sub_game/');?>" + provider_code + '/' + game_type_code,
					type: 'get',                  
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');
						is_allowed_open = true;
					},
					success: function (data) {
						$('#slot-body').html(data);
					},
					error: function (request,error) {
					}
				});
			}
		}
		
		function customPagination(obj, id) 
		{
			$('.pagination-item').removeClass('active');
			$(obj).addClass('active');
			$('.page-count').css("display", "none");
			$('.page-count-' + id).css("display", "flex");
		}
		
		function getDateNow()
		{
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			var hour = today.getHours();
			var min = today.getMinutes();
			var sec = today.getSeconds();
			if(dd<10){
				dd='0'+dd
			} 
			if(mm<10){
				mm='0'+mm
			}
			if(hour<10){
				hour='0'+hour
			}
			if(min<10){
				min='0'+min
			}
			if(sec<10){
				sec='0'+sec
			}
			return yyyy+'-'+mm+'-'+dd+' '+hour+':'+min+':'+sec;
		}
	</script>
	<script>
		var coll = document.getElementsByClassName("collapsible");
		var i;

		for (i = 0; i < coll.length; i++) {
		  coll[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight){
			  content.style.maxHeight = null;
			} else {
			  content.style.maxHeight = content.scrollHeight + "px";
			} 
		  });
		}
	</script>
	<script>
		function get_fingerprint() {
            // var hasConsole = typeof console !== "undefined"
            var fingerprintReport = function() {
                Fingerprint2.get(function(components) {
                    var murmur = Fingerprint2.x64hash128(components.map(function(pair) {
                        return pair.value
                    }).join(), 31)
                    // document.querySelector("#fingerprint").textContent = murmur
                    $(".input_fingerprint").val(murmur);
                    // console.log(murmur)
                })
            }

            var cancelId
            var cancelFunction
            // see usage note in the README
            if (window.requestIdleCallback) {
                cancelId = requestIdleCallback(fingerprintReport)
                cancelFunction = cancelIdleCallback
            } else {
                cancelId = setTimeout(fingerprintReport, 500)
                cancelFunction = clearTimeout
            }
        }
	</script>
	<script>
	class Jackpot {
		constructor(selector) {
			this.selector = selector;

			this.loop();
		}

		loop() {
			let jackpots = document.querySelectorAll(this.selector);

			jackpots.forEach((item) => {
				let jackpot = item.querySelector('.jackpot__counter'),
					value = parseInt(jackpot.getAttribute(['data-jackpot'])),
					randomJackpotInt = this.randomInt(150, 650),
					randomIntervalInt = this.randomInt(1000, 2000),
					randomCentsInt = this.randomInt(10, 99);
				
				this.show(value, jackpot, randomJackpotInt, randomCentsInt);

				let interval = setInterval(() => {
					this.show(value, jackpot, randomJackpotInt, randomCentsInt);

					this.loop();

					clearInterval(interval);
				}, randomIntervalInt);
			})
		}

		show(value, jackpot, randomJackpotInt, randomCentsInt) {
			value += randomJackpotInt;

			let transformed = this.transform(value);

			jackpot.innerHTML = '$ ' + transformed + '.' + randomCentsInt;
			jackpot.setAttribute('data-jackpot', transformed.replace(/\,/g, ''));	
		}

		transform(value) {
			return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		}

		randomInt(min, max) {
			return Math.floor(Math.random() * (max - min + 1) + min);
		}
	}

	(() => {
		console.clear();

		let jackpot = new Jackpot('.jackpot');
	})()
	</script>
</body>
</html>