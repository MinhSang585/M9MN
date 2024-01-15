<script type="text/javascript">
	function startTime() {
		const today = new Date();
		let d = today.getDate();
		let mon = today.getMonth();
		let y = today.getFullYear();
		let h = today.getHours();
		let m = today.getMinutes();
		let s = today.getSeconds();
		d = checkTime(d);
		m = checkTime(m);
		mon = checkTime(mon);
		s = checkTime(s);
		var txtExists = document.getElementById("txt");
		if (txtExists) {
			document.getElementById('txt').innerHTML = y + "-" + mon + "-" + d +" " + h + ":" + m + ":" + s;
		}
		setTimeout(startTime, 1000);
	}
	function checkTime(i) {
		if (i < 10) {
			i = "0" + i
		}; // add zero in front of numbers < 10
		return i;
	}

	$(document).ready(function() {
		$('.lazyload').lazy();
		<?php if(!$this->agent->is_mobile()) : ?>
		startTime();
		<?php endif; ?>	
		
		//Change language
		$("#change_language").change(function() {
			location.href = "<?php echo base_url('ajax/change/');?>" + $('#change_language').val();
		});

		/*if (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)) {
			document.title = document.getElementsByName('apple-mobile-web-app-title')[0].content;
		}*/
		var is_allowed = true;
		<?php 
		##################################################LOGIN AREA DOCUMENT READY####################################################################
		if($this->session->userdata('is_logged_in') == true) { 
		?>
			latest_balance();
			//main_balance();
			//member_star();			
			inbox_counter();
			setInterval(inbox_counter, 300000); //5 minute
			//setInterval(main_balance, 300000); //5 minute
			
			setInterval(function () { //5 minute
				latest_balance();				
			}, 30000);
			
			$('#feedback-form').submit(function(event) {
				event.preventDefault();
				$.ajax({url: $('#feedback-form').attr('action'),
					data: $('#feedback-form').serialize(),
					datatype: "json",
					type: 'post',
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');
						
					},
					success: function (json) {
						//console.log(json);
						var message = json.msg;
						$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							var icon = '<i class="fa-regular fa-circle-check text-light-green fs-6"></i>&nbsp;';							
							layer.alert(icon+message,{			
								skin: 'default-class',
								title: '<?php echo $this->lang->line('label_info');?>',
								closeBtn: 0,
								btn: '<?php echo $this->lang->line('label_close');?>',
								anim: 2,
								shade: 0.8,
								shadeClose: true},
								function (){ 
									$('#feedback-form')[0].reset();						
									$('#feedback-modal').modal('hide');
									layer.closeAll(); 							
							});
						}
						else {
							alertbox(2,message);
						}
					},
					error: function (request,error) {
						console.log(request);
					}
				});			
			});
			
			<?php if($this->uri->segment(1) == 'logout' && $this->uri->segment(2) == 'force'):?>
				<?php if(isset($msg_alert)):?>
					layer.alert('<?php echo $msg_alert;?>', {icon: <?php echo $msg_icon;?>, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
				<?php endif;?>
			<?php endif;?>
			
			
			<?php if($this->uri->segment(1) == 'account' || ($this->uri->segment(1) == 'transfer' && $this->uri->segment(2) == '')):?>
			member_avatar();			
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account'): ?>
				$("#refresh_icon_2").click(function() {
					get_balance();
				});
		
				$("#transfer_all").click(function() {
					transfer_all();
				});
			<?php endif; ?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'wallet'):?>
			get_balance();			
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'deposit'):?>						
			var deposit_form = $('.deposit_form');
			deposit_form.submit(function(event){
				event.preventDefault();
				var deposit_actual_form = $('#'+this.id);
				var form_id = this.id;
				var file_form = deposit_actual_form[0];
				var formData = new FormData(file_form);

				if(form_id == "deposit-online-form" || form_id == "deposit-wallet-form"){
					var bank_type = <?php echo TRANSFER_PG_DEPOSIT;?>;					
				}
				else if(form_id == "deposit-hypermart-form"){
					var bank_type = <?php echo TRANSFER_HYPERMART_DEPOSIT;?>;					
				}
				else if(form_id == "deposit-credit-form"){
					var bank_type = <?php echo TRANSFER_CREDIT_CARD_DEPOSIT;?>;
				}
				else{
					var bank_type = <?php echo TRANSFER_OFFLINE_DEPOSIT;?>;
					$.each($("input[type='file']")[0].files, function(i, file) {
						formData.append('file', file);
					});
				}
				
				if(form_id == "deposit-online-form") {
					var box = $('#check_tnc_online').prop('checked');
				}
				else {
					var box = $('#check_tnc_wallet').prop('checked');
				}
				
				// var box temp
				var box = 1;

				if(box) {
					if(is_allowed == true) {
						//console.log(bank_type);
						if(bank_type != <?php echo TRANSFER_OFFLINE_DEPOSIT;?>) {
							<?php /*if($this->agent->is_mobile()) { ?>
							var windowReference = window.open();
							<?php } */ ?>
						}
						
						is_allowed = false;
						$.ajax({url: deposit_form.attr('action'),
							data: formData,
							type: 'post',
							processData: false,
							contentType: false,
							async: 'true',
							beforeSend: function() {
								$('.btndisable').prop("disabled", true);
								layer.load(1);
							},
							complete: function() {								
								is_allowed = true;
							},
							success: function (data) {
								layer.closeAll('loading');
								var json = JSON.parse(JSON.stringify(data));
								var message = json.msg;
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									layer.alert(message, {skin: 'default-class',icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ $('.deposit_form').trigger("reset"); layer.closeAll(); is_allowed = true; });
								}
								else if(json.status == '<?php echo EXIT_CONFIG;?>') {
									
									<?php if($this->agent->is_mobile()) { ?>
									layer.open({
										type: 2,	
										title: false,
										closeBtn: 0,
										shadeClose : true,
										area: ['75vw', '80vh'],
										content: json.url,
										// time:10,
										end: function(index, layero){
											window.parent.location.reload();
										}
									});
									<?php } else { ?>
									
									layer.open({
										type: 2,	
										title: false,
										area: ['80vw', '90vh'],
										content: json.url,
										// time:10,
										end: function(index, layero){
											window.parent.location.reload();
										}
									});
									<?php } ?>
									//$('#launch_payment_gateway').attr('href', json.url);
									//$('#launch_payment_gateway')[0].click();
									
									//location.href = json.url;
									//$('#launch_payment_gateway').attr('href', json.url);
									//$('#launch_payment_gateway')[0].click();
								}
								else{
									$('.btndisable').prop("disabled", false);
									if(bank_type != <?php echo TRANSFER_OFFLINE_DEPOSIT;?>) {
										<?php /* if($this->agent->is_mobile()) { ?>
										windowReference.close();
										<?php }*/ ?>
									}
									layer.alert(message, {skin: 'default-class', icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
								}
							},
							error: function (request,error) {
								console.log(request);
								layer.closeAll('loading');
							}
						});					
					}	
				}
				else {
					var read_msg = "<?php echo $this->lang->line('label_agreement'); ?>";
					alertbox(2,read_msg);
				}				
			});
			<?php endif;?>

			<?php if ($this->uri->segment(2) == 'transaction_history'): ?>
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

						$.ajax({
							url: transaction_form.attr('action'),
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
			<?php endif; ?>

			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'withdrawal' && $this->session->userdata('is_logged_in') == TRUE):?>
				$(document).ready(function () {
					$.ajax({
						url: "<?php echo base_url('ajax/get_player_currently_turnover/');?>",
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

					$.ajax({
						url: "<?php echo base_url('ajax/get_player_bank_list/');?>",
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
									$("#player_bank_id").append($('<option></option>').val(json.output[i]['player_bank_id']).html(json.output[i]['bank_account_name'] + " - "+ json.output[i]['bank_account_no'] + " ("+json.output[i]['bank_name'] + " )"));
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
						} else {
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
			<?php endif; ?>
			
			<?php if($this->uri->segment(1) == 'account' && ($this->uri->segment(2) == 'binding_bank' || $this->uri->segment(2) == 'badding_bank')):?>
			$("#captcha").click(function() {
				$(this).attr("src", "<?php echo site_url('ajax/captcha');?>");
			});
			
			var player_bank_form = $('#player-bank-form');
			player_bank_form.submit(function(event) {
				
				if(is_allowed == true) {
					is_allowed = false;
					$.ajax({url: player_bank_form.attr('action'),
						data: player_bank_form.serialize(),
						type: 'post',
						cache: false,												
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
								layer.alert(message,{			
									skin: 'default-class',
									icon: 1,
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true}, 
								function (){
									location.reload();
								});
							}
							else {
								if(json.status == '9090') {
									var msg_icon = 0;
								}
								else {
									var msg_icon = 2;
								}
								layer.alert(message,{
									skin: 'default-class',
									icon: msg_icon,
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true
								});
							}
						},
						error: function (request,error) {
							console.log(request);
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
								layer.alert(message, {
									icon: 1, title: '<?php echo $this->lang->line('label_info');?>',
									btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0
								}, function (){
									$('#player-bank-usdt-form')[0].reset();
									layer.closeAll();
									is_allowed = true;
								});
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
			
			var nric_form = $('#nric-form');
			nric_form.submit(function(event) {
				var file_form = nric_form[0];
				var formData = new FormData(file_form);
				$.each($("input[type='file']")[0].files, function(i, file) {
					formData.append('file', file);
				});
				if(is_allowed == true) {
					is_allowed = false;
					$.ajax({url: nric_form.attr('action'),
						data: formData,
						type: 'post',
						cache: false,
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
								var icon = '<i class="fa-regular fa-circle-check text-light-green fs-6"></i>&nbsp;';
								layer.alert(icon+message,{			
									skin: 'default-class',
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true,}, 
								function (){
									location.reload();									
								});
							}
							else {								
								alertbox(2,message);
							}
						},
						error: function (request,error) {
							console.log(request);
						}
					});
				}
				event.preventDefault();
			});			
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'binding_credit_card'):?>
			var credit_form = $('#credit-form');
			credit_form.submit(function(event) {
				var file_form = credit_form[0];
				var formData = new FormData(file_form);
				$.each($("input[type='file']")[0].files, function(i, file) {
					formData.append('file', file);
				});
				if(is_allowed == true) {
					is_allowed = false;
					$.ajax({url: credit_form.attr('action'),
						data: formData,
						type: 'post',
						cache: false,
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
								var icon = '<i class="fa-regular fa-circle-check text-light-green fs-6"></i>&nbsp;';
								layer.alert(icon+message,{			
									skin: 'default-class',
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true,}, 
								function (){
									location.reload();									
								});
							}
							else {								
								alertbox(2,message);
							}
						},
						error: function (request,error) {
							console.log(request);
						}
					});
				}
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'change_password'):?>			
			var change_password_form = $('#change-password-form');
			change_password_form.submit(function(event) {
				if(is_allowed == true) {
					is_allowed = false;
					$.ajax({url: change_password_form.attr('action'),
						data: change_password_form.serialize(),
						type: 'post',
						async: 'true',
						beforeSend: function() {
							layer.load(2);
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
								var icon = '<i class="fa-regular fa-circle-check text-light-green fs-6"></i>&nbsp;';
								layer.alert(icon+message,{			
									skin: 'default-class',
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true}, function (){ 
										window.location.href = "<?php echo site_url('logout'); ?>";									
								});
							}
							else {
								alertbox(2,message);
							}
						},
						error: function (request,error) {
						}
					});
				}
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php #CONTACT US ?>		
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'contact'):?>			 
			$.ajax({
				url: "<?php echo site_url('ajax/contact_list');?>",
				type: 'get',
				datatype: 'json',
				async: 'true',
				beforeSend: function() {
					$("#contact-line-qr").html("");
				},
				complete: function() {					
				},
				success: function (json) {
					$("#contact-line-value").html(json.line_value);
					var qrcode = new QRCode(document.getElementById("contact-line-qr"), {
						text: json.line_url,
						width: 200,
						height: 200,
						colorDark: "#000000",
						colorLight: "#ffffff",
						correctLevel: QRCode.CorrectLevel.H
					});
				}
			});
			<?php endif;?>		
			
			<?php #PROFILE ?>		
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == ''):?>			 
			$.ajax({
				url: "<?php echo site_url('ajax/contact_list');?>",
				type: 'get',
				datatype: 'json',
				async: 'true',
				beforeSend: function() {
				},
				complete: function() {					
				},
				success: function (json) {
					//console.log(json);					
					$('#contact-line-url').attr('href', json.line_url);
				}
			});
			<?php endif;?>
			
			<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'invite_friend'): ?>
			$.ajax({
				url: "<?php echo site_url('ajax/referral_dashboard');?>",
				type: 'get',
				datatype: 'json',
				async: 'true',
				beforeSend: function() {
					$("#friend-register,#friend-valid,#friend-reward").html('<div class="spinner-border text-info spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
				},
				complete: function() {},
				success: function (json) {
					$("#friend-register").html(json.reg);
					$("#friend-valid").html(json.valid);
					$("#friend-reward").html(""+json.reward);
				}
			});
			<?php endif; ?>
			
			<?php if($this->uri->segment(1) == 'account' || $this->uri->segment(1) == 'transfer'): ?>
			var sidenav1 = document.getElementById('membernav-1');
			if(sidenav1) {
				$('#membernav-1').hover(function() {
					$('#membernav-1').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-11').removeClass("d-block").addClass("d-none");
					$('#membernav-12').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-1').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-12').removeClass("d-block").addClass("d-none");
					$('#membernav-11').removeClass("d-none").addClass("d-block");
				   
				});
			}

			var sidenav2 = document.getElementById('membernav-2');
			if(sidenav2) {
				$('#membernav-2').hover(function() {
					$('#membernav-2').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-21').removeClass("d-block").addClass("d-none");
					$('#membernav-22').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-2').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-22').removeClass("d-block").addClass("d-none");
					$('#membernav-21').removeClass("d-none").addClass("d-block");
				   
				});
			}
			
			var sidenav3 = document.getElementById('membernav-3');
			if(sidenav3) {
				$('#membernav-3').hover(function() {
					$('#membernav-3').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-31').removeClass("d-block").addClass("d-none");
					$('#membernav-32').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-3').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-32').removeClass("d-block").addClass("d-none");
					$('#membernav-31').removeClass("d-none").addClass("d-block");
				   
				});
			}
			
			var sidenav4 = document.getElementById('membernav-4');
			if(sidenav4) {
				$('#membernav-4').hover(function() {
					$('#membernav-4').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-41').removeClass("d-block").addClass("d-none");
					$('#membernav-42').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-4').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-42').removeClass("d-block").addClass("d-none");
					$('#membernav-41').removeClass("d-none").addClass("d-block");
				   
				});
			}
			
			var sidenav5 = document.getElementById('membernav-5');
			if(sidenav5) {
				$('#membernav-5').hover(function() {
					$('#membernav-5').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-51').removeClass("d-block").addClass("d-none");
					$('#membernav-52').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-5').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-52').removeClass("d-block").addClass("d-none");
					$('#membernav-51').removeClass("d-none").addClass("d-block");
				   
				});
			}
			
			var sidenav6 = document.getElementById('membernav-6');
			if(sidenav6) {
				$('#membernav-6').hover(function() {
					$('#membernav-6').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-61').removeClass("d-block").addClass("d-none");
					$('#membernav-62').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-6').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-62').removeClass("d-block").addClass("d-none");
					$('#membernav-61').removeClass("d-none").addClass("d-block");
				   
				});
			}
			
			var sidenav7 = document.getElementById('membernav-7');
			if(sidenav7) {
				$('#membernav-7').hover(function() {
					$('#membernav-7').removeClass("text-dark").addClass("text-light bg-gradient-blue5");
					$('#membernav-71').removeClass("d-block").addClass("d-none");
					$('#membernav-72').removeClass("d-none").addClass("d-block");
				}, 
				function () {
					$('#membernav-7').removeClass("text-light bg-gradient-blue5").addClass("text-dark");
					$('#membernav-72').removeClass("d-block").addClass("d-none");
					$('#membernav-71').removeClass("d-none").addClass("d-block");
				   
				});
			}
			<?php endif; ?>
			
			var ddwallet = document.getElementById('dropdownMenu2');
			if (ddwallet) {
				ddwallet.addEventListener('show.bs.dropdown', function () {				
					get_all_balance();
				});				
			}

			var ddwallet3 = document.getElementById('dropdownMenu3');			
			if (ddwallet3) {
				ddwallet3.addEventListener('show.bs.dropdown', function () {
					get_all_balance();
				});				
			}
			
			var ddwallet4 = document.getElementById('dropdownMenu4');			
			if (ddwallet4) {
				ddwallet4.addEventListener('show.bs.dropdown', function () {
					get_all_balance();
				});				
			}
			
		<?php
		}		
		else {
		##################################################NON LOGIN AREA DOCUMENT READY####################################################################		
		?>			
			
			$('#login-form').submit(function(event) {
				event.preventDefault();
				if(is_allowed == true) {
					is_allowed = false;
					$.ajax({url: $('#login-form').attr('action'),
						data: $('#login-form').serialize(),
						type: 'post',
						async: 'true',
						beforeSend: function() {
							layer.load(2);
						},
						complete: function() {
							layer.closeAll('loading');
							is_allowed = true;
						},
						success: function (data) {
							//console.log(data);
							var json = JSON.parse(JSON.stringify(data));
							var message = json.msg;
							$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {								
								location.href = json.url;
							}
							else {
								alertbox(2,message);								
							}
						},
						error: function (request,error) {
							console.log(request);
						}
					});
				}				
			});			
			
			<?php if($this->uri->segment(1) == 'register'): ?>			
			get_fingerprint();

			$("#captcha").click(function() {
				$(this).attr("src", "<?php echo site_url('ajax/captcha');?>");
			});	
			
			var signup_form = $('#sign-up-form');
			signup_form.submit(function(event) {
				if(is_allowed == true) {					
					$.ajax({url: signup_form.attr('action'),
						data: signup_form.serialize(),
						type: 'post',
						async: 'true',
						beforeSend: function() {
							is_allowed = false;
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
								layer.alert(message,{			
									skin: 'default-class',
									icon: 1,
									title: '<?php echo $this->lang->line('label_info');?>',
									closeBtn: 0,
									btn: '<?php echo $this->lang->line('label_close');?>',
									anim: 2,
									shade: 0.8,
									shadeClose: true									
								}, function (){
									location.href = json.url;
								});
							}
							else {
								layer.alert(message,{
								skin: 'default-class',
								icon: 2,
								title: '<?php echo $this->lang->line('label_info');?>',
								closeBtn: 0,
								btn: '<?php echo $this->lang->line('label_close');?>',
								anim: 2,
								shade: 0.8,
								shadeClose: true
								});
								// alertbox(2,message);
								//$('#username,#password,#passconf,#line_id,#mobile').prop('readonly', false);
							}
						},
						error: function (request,error) {
							console.log(request);
						}
					});					
				}				
				event.preventDefault();
			});
			<?php endif;?>
			
			<?php #************Forgot password Form*************# ?>
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
								layer.alert(message, {skin: 'default-class',icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ location.reload(); });
							}
							else
							{
								layer.alert(message, {skin: 'default-class',icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); is_allowed = true; });
							}
						},
						error: function (request,error) {
						}
					});
				}
				event.preventDefault();
			});			
			<?php #*********************************************# ?>
		<?php 
		} 
		##################################################GENERAL AREA DOCUMENT READY####################################################################
		?>	
		main_banner();
		//main_footer_blog_menu();
		//news_url();
		//footer_contact();
		
		<?php if($this->uri->segment(1) == ''):?>
		setInterval(function () { //8 sec
			snackbar();
		}, 8000);
		<?php endif; ?>
		
		$('.card_c').click(function () {
			$(this).siblings().addClass('uk-blend-luminosity');
			$(this).removeClass('uk-blend-luminosity');
		});
		
		let topbutton = document.getElementById("back-to-top");
		if(topbutton) {
			window.onscroll = function() {scrollfunction()};
		}
		
		$("#scroll-up-btn").click(function() {
			$("html, body").animate({scrollTop: 0}, 500);
		});
		
		$.ajax({url: "<?php echo site_url('ajax/announcement');?>",
			type: 'get',
			datatype: 'json',
			async: 'true',			
			success: function (json) {		
				
				if(json.length > 0){
					var html = '';
					for(i = 0; i < json.length; i++) {
						html += '<span>' + json[i] + '</span>';
					}
					
					$("#announcement,#announcement-mobile").html(html);
				}
			}
		});
				
		<?php if($this->uri->segment(1) == 'slots' || $this->uri->segment(1) == 'fish' || $this->uri->segment(1) == 'card' || $this->uri->segment(1) == 'crash' || $this->uri->segment(1) == 'board'):?>		
			//Search game list
			$("#search-text").keyup(function() {
				var value = $(this).val().toLowerCase();
				$("#game-panel .game-name").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			
			<?php 
			if($this->uri->segment(1) == 'slots'){
				if($this->uri->segment(2) != '') {
			?>
				var seg2 = "<?php echo $this->uri->segment(2); ?>"; 				
				sub_game(seg2.toUpperCase(), '<?php echo GAME_SLOTS;?>');
				filter_slot('<?php echo $this->uri->segment(3);?>');
			<?php 
				}
				else {
			?>		
				sub_game('PP', '<?php echo GAME_SLOTS;?>');
			<?php 
				}
			} 
			?>
			<?php 
			if($this->uri->segment(1) == 'board'){
				if($this->uri->segment(2) != '') {
			?>
				var seg2 = "<?php echo $this->uri->segment(2); ?>"; 				
				sub_game(seg2.toUpperCase(), '<?php echo GAME_BOARD_GAME;?>');
				filter_board('<?php echo $this->uri->segment(3);?>');
			<?php 
				}
				else {
			?>		
				sub_game('KM', '<?php echo GAME_BOARD_GAME;?>');
			<?php 
				}
			} 
			?>
			<?php 
			if($this->uri->segment(1) == 'card'){
				if($this->uri->segment(2) != '') {
					if($this->uri->segment(2) == 'JILI') {
			?>
				var seg3 = "<?php echo $this->uri->segment(2); ?>"; 				
				sub_game(seg3.toUpperCase(), '<?php echo GAME_BOARD_GAME;?>');
			<?php 
					}
					else{
			?>
				var seg3 = "<?php echo $this->uri->segment(2); ?>"; 				
				sub_game(seg3.toUpperCase(), '<?php echo GAME_OTHERS;?>');
			<?php			
					}
				}
				else {
			?>		
				sub_game('JILI', '<?php echo GAME_BOARD_GAME;?>');
			<?php 
				}
			} 
			?>
			<?php 
			if($this->uri->segment(1) == 'fish'){
				if($this->uri->segment(2) != '') {
			?>
				var seg3 = "<?php echo $this->uri->segment(2); ?>"; 
				sub_game(seg3.toUpperCase(), '<?php echo GAME_FISHING;?>');
				filter_fish('<?php echo $this->uri->segment(3);?>');
			<?php 
				}
				else {
			?>
				sub_game('JDB', '<?php echo GAME_FISHING;?>');
			<?php }
			} 
			?>	
			<?php 
			if($this->uri->segment(1) == 'crash'){
				if($this->uri->segment(2) == 'game') {
			?>
				var seg3 = "<?php echo $this->uri->segment(3); ?>"; 				
				sub_game(seg3.toUpperCase(), '<?php echo GAME_BOARD_GAME;?>');
			<?php 
				}
				else {
			?>		
				sub_game('T1G', '<?php echo GAME_BOARD_GAME;?>');
			<?php 
				}
			} 
			?>	
		<?php endif;?>
		
		<?php if($this->uri->segment(1) == 'promotion' && $this->uri->segment(2) == ''):?>		
		promotion_list(0);		
		<?php endif; ?>
		
		$.ajax({url: "<?php echo site_url('ajax/get_contact_list');?>",
			type: 'get',
			datatype: 'json',
			async: 'true',		
			beforeSend: function() {
			},
			complete: function() {					
			},
			success: function (json) {
				//var json = JSON.parse(JSON.stringify(data));
				if(json.status == '<?php echo EXIT_SUCCESS;?>') {
					$.each(json.list, function( k, v ) {
						$('#' + v.im_name).html(v.im_value);
					});
				}
			}
		});

		<?php #MAINTENANCE LIST
		if($this->uri->segment(1) == 'maintain'):
		?>
		
		$.ajax({url: "<?php echo site_url('ajax/get_all_maintenance');?>",
			type: 'get',
			datatype: 'json',
			async: 'true',
			beforeSend: function() {
			},
			complete: function() {					
			},
			success: function (data) {
				var json = JSON.parse(JSON.stringify(data));
				
				if(json.status == '<?php echo EXIT_SUCCESS;?>') {
					var html = '';
					var html2 = '';
					var monday = '';
					var tuesday = '';
					var wednesday = '';
					var thursday = '';
					var friday = '';
					var saturday = '';
					var sunday = '';
					
					if(json.urgent_maintenance.length > 0){						
						//Static page						
						for(i = 0; i < json.urgent_maintenance.length; i++) {							
							html += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.urgent_maintenance[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.urgent_maintenance[i]['game_date'] +"</div>";
						}
					}
					else {
						html = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-urgent").html(html);
					
					if(json.maintenance_monday.length > 0){						
						//Static page						
						for(i = 0; i < json.maintenance_monday.length; i++) {							
							monday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_monday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_monday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_monday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						monday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-monday").html(monday);
					
					if(json.maintenance_tuesday.length > 0){						
						//Static page						
						for(i = 0; i < json.maintenance_tuesday.length; i++) {							
							tuesday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_tuesday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_tuesday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_tuesday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						tuesday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-tuesday").html(tuesday);
					
					if(json.maintenance_wednesday.length > 0){						
						for(i = 0; i < json.maintenance_wednesday.length; i++) {							
							wednesday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_wednesday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_wednesday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_wednesday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						wednesday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-wednesday").html(wednesday);
					
					if(json.maintenance_thursday.length > 0){						
						for(i = 0; i < json.maintenance_thursday.length; i++) {							
							thursday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_thursday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_thursday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_thursday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						thursday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-thursday").html(thursday);
				
					if(json.maintenance_friday.length > 0){						
						for(i = 0; i < json.maintenance_friday.length; i++) {							
							friday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_friday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_friday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_friday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						friday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-friday").html(friday);
					
					if(json.maintenance_saturday.length > 0){						
						for(i = 0; i < json.maintenance_saturday.length; i++) {							
							saturday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_saturday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_saturday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_saturday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						saturday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-saturday").html(saturday);
					
					if(json.maintenance_sunday.length > 0){						
						for(i = 0; i < json.maintenance_sunday.length; i++) {							
							sunday += "<div class=\"col-10 text-light text-center bg-deep-blue fw-bold rounded\">"+ json.maintenance_sunday[i]['game_name'] +"</div><div class=\"col-12 text-center text-dark text-sm9\">"+ json.maintenance_sunday[i]['game_date_from'] +" <?php echo $this->lang->line('label_until');?> "+json.maintenance_sunday[i]['game_date_to']+"</div>";
						}						
					}
					else {
						sunday = "<div class=\"col-12 text-center text-dark text-sm9 fw-bold\"><?php echo $this->lang->line('label_no_maintenance_now'); ?></div>";
					}
					$("#maintain-sunday").html(sunday);					
				}
			}
		});
		<?php endif; ?>
		
		<?php 
		#BLOGS CATEGORY
		if($this->uri->segment(1) == 'blog-category'):
			$bc = ($this->uri->segment(2)=='') ? 'official-news' : $this->uri->segment(2);
		?>				
		show_blog('<?php echo $bc; ?>');
		<?php endif;?>
		<?php 
		#BLOGS CATEGORY
		if($this->uri->segment(1) == 'blog'):
			$bc = 'official-news';
		?>				
		show_blog('<?php echo $bc; ?>');
		<?php endif;?>
		var csmodal = document.getElementById('exampleModalCenter2');
		if (csmodal) {
			csmodal.addEventListener('show.bs.modal', function () {
				var pathimg = "<?php echo base_url('themes/' . SYSTEM_THEME . '/mobile/assets/img/footericon/'); ?>";
				$('[class*="footernav-"]').removeClass("text-gradient-yellow").addClass("text-light");
				$(".footernav-cs").removeClass("text-light").addClass("text-gradient-yellow");
				
				$("#footer-cs").attr('src', pathimg+'24h.svg');
				$("#footer-home").attr('src', pathimg+'home_active.svg');
				$("#footer-promo").attr('src', pathimg+'promo_active.svg');
				$("#footer-transfer").attr('src', pathimg+'transfer_active.svg');
				$("#footer-menu").attr('src', pathimg+'me_active.svg');				
			});

			csmodal.addEventListener('hide.bs.modal', function () {
				var pathimg = "<?php echo base_url('themes/' . SYSTEM_THEME . '/mobile/assets/img/footericon/'); ?>";
				$('[class*="footernav-"]').removeClass("text-gradient-yellow").addClass("text-light");								
				$("#footer-cs").attr('src', pathimg+'24h_active.svg');				
			});	
		}
		
		$("#indexFooterBtn,#indexFooterBtnItemBG").click(function () {
			$("#indexFooterBtnItem").toggleClass("d-none");
			$("#indexFooterBtnItemBG").toggleClass("d-none");
			var pathimg = "<?php echo base_url('themes/' . SYSTEM_THEME . '/mobile/assets/img/footericon/'); ?>";
			if($('#indexFooterBtnItem').hasClass('d-none')){				
				$('[class*="footernav-"]').removeClass("text-gradient-yellow").addClass("text-light");								
				$("#footer-transfer").attr('src', pathimg+'transfer_active.svg');				
			} 
			else {				
				$('[class*="footernav-"]').removeClass("text-gradient-yellow").addClass("text-light");
				$(".footernav-transfer").removeClass("text-light").addClass("text-gradient-yellow");
				
				$("#footer-transfer").attr('src', pathimg+'transfer.svg');
				$("#footer-cs").attr('src', pathimg+'24h_active.svg');
				$("#footer-home").attr('src', pathimg+'home_active.svg');
				$("#footer-promo").attr('src', pathimg+'promo_active.svg');				
				$("#footer-menu").attr('src', pathimg+'me_active.svg');				
			}			
		});
	});

	<?php 
	##################################################LOGIN AREA FUNCTION################################################################################
	if($this->session->userdata('is_logged_in') == true) {  	
	?>
		var is_allowed_connect = true;
		
		function member_avatar() {		
			$.ajax({url: "<?php echo site_url('ajax/avatar');?>",
				type: 'get',
				dataType: 'json',
				cache: false,
				async: 'true',
				beforeSend: function() {},
				complete: function() {},
				success: function (json) {				
					if(json.status == '<?php echo EXIT_SUCCESS;?>') {
						$("#member_avatar,#member_avatar2").attr("src",json.result);
					}
					else{
						$("#member_avatar,#member_avatar2").attr("src","<?php echo UPLOAD_PATH.'avatar/avatar1.png'; ?>");
					}
				}
			});
		}
			
		function delete_message(pmid){
			layer.confirm('<?php echo $this->lang->line('label_confirm_delete'); ?>', function(index){
				layer.close(index);			
				$.ajax({url: "<?php echo base_url('ajax/delete_message');?>/"+pmid,
					type: 'get',
					dataType: 'json',
					cache: false,
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');	
					},
					success: function (json) {					
						var message = json.msg;
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); location.reload(); });
						}else{
							layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); });
						}
					}
				});
			});
		}
		
		function delete_all_message(){
			layer.confirm('<?php echo $this->lang->line('label_confirm_delete_all'); ?>', function(index){
				layer.close(index);
				
				$.ajax({url: "<?php echo base_url('ajax/delete_message');?>",
					type: 'get',
					dataType: 'json',
					cache: false,
					async: 'true',
					beforeSend: function() {
						layer.load(1);
					},
					complete: function() {
						layer.closeAll('loading');	
					},				
					success: function (json) {					
						var message = json.msg;
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); location.reload(); });
						}else{
							layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); });
						}
					}
				});
			});
		}
		
		function inbox_counter(){		
			var send_url = "<?php echo site_url('ajax/inbox_counter');?>";
			//console.log(send_url);	
			$.ajax({
				url: send_url,
				type: 'GET',
				dataType: 'json',
				cache: false,
				async: 'true',
				beforeSend: function() {},
				complete: function() {},
				success: function(json) {
					//console.log(json);
					if(json.flag > 0) {	
						$("#red-point,#red-point2,#red-point3").removeClass("d-none");
						$("#message-vol,#message-vol-mobile,#message-vol-menu").html(json.flag);
					}
					else {					
						$("#red-point,#red-point2,#red-point3").addClass("d-none");
						$("#message-vol,#message-vol-mobile,#message-vol-menu").html(0);
					}
				},
				error: function(xhr, status, error) {
					console.log(xhr);						
				}
			});			
		}
		
		function get_all_balance(){		
			main_balance();
			setTimeout(function(){
				get_balance();
			}, 300);
			
		}
		
		function main_balance() {
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				$.ajax({url: "<?php echo site_url('ajax/main_balance');?>",
					type: 'get',
					datatype: 'json',
					async: 'true',
					beforeSend: function() {
						
						<?php if($this->uri->segment(1) == 'transfer' && $this->uri->segment(2) == ''):?>
						var from_provider = $("#t_from").val();
						var to_provider = $("#t_to").val();
						
						if(from_provider == "MAIN") {
							$(".bal_from").html('<div class="spinner-border text-info spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
						}
						
						if(to_provider == "MAIN") {
							$(".bal_to").html('<div class="spinner-border text-info spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
						}
						<?php endif; ?>	
						
						$(".bal_total,.bal_main").html('<div class="spinner-border text-info spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
						$("#baltotal,#baltotal1,#baltotal2").val(0);
					},
					complete: function() {					
						is_allowed_connect = true;
						
					},
					success: function (json) {
						$(".bal_main").html("$ "+json.main_wallet);
						$(".bal_total").html("$ "+json.main_wallet);
						$("#baltotal,#baltotal1,#baltotal2").val(json.main_wallet);					
						$("#balance,#balance2,#balance_web").html(json.main_wallet);
						<?php if($this->uri->segment(1) == 'transfer' && $this->uri->segment(2) == ''):?>
						var from_provider = $("#t_from").val();
						var to_provider = $("#t_to").val();
						
						if(from_provider == "MAIN") {
							$(".bal_from").html("$"+json.main_wallet);
							$("#t_from").val("MAIN");	
							$("#tranfer_from_amount").val(json.main_wallet);
							$("#modal-bal-from").html("$"+json.main_wallet);
							$("#t_in").val("<?php echo $this->lang->line('label_main_wallet'); ?>");	
						}
						if(to_provider == "MAIN") {
							$(".bal_to").html("$"+json.main_wallet);
							$("#t_to").val("MAIN");	
							$("#tranfer_to_amount").val(json.main_wallet);
							$("#modal-bal-to").html("$"+json.main_wallet);
							$("#t_out").val("<?php echo $this->lang->line('label_main_wallet'); ?>");	
						}										
						$("#t_total").val(json.main_wallet);					
						<?php endif; ?>	
						
						<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'menu'):?>
						$("#user-info-balance").html(json.main_wallet);					
						<?php endif; ?>	
						<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'wallet'):?>
						$("#balance_mobile").html(json.main_wallet);					
						<?php endif; ?>						
					}
				});
			}
		}
		
		function latest_balance() {
			$.ajax({url: "<?php echo base_url('ajax/latest_balance/');?>",
				type: 'get',
				async: 'true',
				cache: 'false',
				beforeSend: function() {				
					$(".bal_latest").html('<div class="spinner-border text-info spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');				
				},
				complete: function() {				
				},
				success: function (data) {				
					$(".bal_latest, .bal_main").html("$ "+data.balance);
				},
				error: function(xhr, req) {
					console.log(xhr);
				}				
			});
		}
		
		function get_balance() {
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

		function wallet_update(data) {
			var json = JSON.parse(JSON.stringify(data));
			$("#balance").html(json.total);
			$("#balance1").html(json.total);
			$("#balance2").html(json.total);
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
			
		function get_game_balance(provider) {
			var turl = "<?php echo site_url('ajax/game_balance/"+provider+"');?>";
			$.ajax({url: turl,
				type: 'get',
				async: 'true',
				cache: false,
				datatype: 'json',
				beforeSend: function() {
					$("#bal_"+provider.toLowerCase()).html("-");
					if(provider == 'SP') {
						$("#bal_spfh").html("-");
					}
					if(provider == 'ICG') {
						$("#bal_icgfh").html("-");
					}
					if(provider == 'RSG') {
						$("#bal_rsgfh").html("-");
					}
					if(provider == 'GR') {
						$("#bal_grfh,#bal_grbg").html("-");
					}
					if(provider == 'NAGA') {
						$("#bal_naga").html("-");
					}
				},
				complete: function() {
					
				},
				success: function (json) {
					//console.log(json);
					$("#bal_"+provider.toLowerCase()).html(json.result);
					if(provider == 'SP') {
						$("#bal_spfh").html(json.result);
					}
					if(provider == 'ICG') {
						$("#bal_icgfh").html(json.result);
					}
					
					if(provider == 'RSG') {
						$("#bal_rsgfh").html(json.result);
					}
					if(provider == 'GR') {
						$("#bal_grfh,#bal_grbg").html(json.result);
					}
					if(provider == 'NAGA') {
						$("#bal_naga").html(json.result);
					}
				}
			});	
		}	
		
		function member_star() {
			$.ajax({url: "<?php echo site_url('ajax/member_star');?>",
				type: 'get',
				datatype: 'json',
				async: 'true',
				cache: false,
				beforeSend: function() {},
				complete: function() {},
				success: function (json) {				
					$(".member-star,#member-star,#member-star2,#member-star3,#member-star8").attr('src', '<?php echo base_url('themes/' . SYSTEM_THEME . '/assets/img/vip/');?>'+json.result+'.png?v=5');
					<?php if($this->uri->segment(1) == 'account') : ?>
					$("#member-star4").val(json.result);
					<?php endif; ?>
					<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'vip') : ?>
					$("#member-star5,#member-star7").html(json.result);
					$("#star-start").attr('src', '<?php echo base_url('themes/' . SYSTEM_THEME . '/assets/img/vip/');?>'+json.result+'.png?v=5');
					if(json.result == 6) {
						$("#member-star6").html("-");					
						$("#star-next").attr('src', '<?php echo base_url('themes/' . SYSTEM_THEME . '/assets/img/vip/0.png?v=5');?>');
					}
					else {
						var star = json.result+1;
						$("#member-star6").html(star);					
						$("#star-next").attr('src', '<?php echo base_url('themes/' . SYSTEM_THEME . '/assets/img/vip/');?>'+star+'.png?v=5');
					}
					
					if(json.result == 0) {
						var star_progress = "14.28%";
					}
					else if(json.result == 1) {
						var star_progress = "28.56%";
					}
					else if(json.result == 2) {
						var star_progress = "42.84%";
					}
					else if(json.result == 3) {
						var star_progress = "57.12%";
					}
					else if(json.result == 4) {
						var star_progress = "71.40%";
					}
					else if(json.result == 5) {
						var star_progress = "85.68%";
					}
					else {
						var star_progress = "100%";
					}
					$("#star-progress").css({"width": star_progress});
					
					<?php endif; ?>
				}
			});
		}
		
		function dropdown_transferall() {
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				$.ajax({url: "<?php echo site_url('ajax/transfer_all');?>",
					type: 'get',
					async: 'true',
					cache: false,
					beforeSend: function() {
						layer.load(1);											
					},
					complete: function() {	
						layer.closeAll('loading');					
						is_allowed_connect = true;
						main_balance();					
					},
					success: function (data) {
						layer.alert('<?php echo $this->lang->line('error_transfer_all_successful');?>', {
							icon: 1, title: '<?php echo $this->lang->line('label_info');?>',
							btn: '<?php echo $this->lang->line('label_close');?>', 
							closeBtn: 0},function (){ 
								$("#dropdownMenu2").dropdown('toggle');
								$("#dropdownMenu3").dropdown('toggle');
								$("#dropdownMenu4").dropdown('toggle');							
								layer.closeAll();
								
						});
					}
				});
			}
		}
		
		function transferall() {
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				$.ajax({url: "<?php echo site_url('ajax/transfer_all');?>",
					type: 'get',
					async: 'true',
					cache: false,
					beforeSend: function() {
						layer.load(1);											
					},
					complete: function() {	
						layer.closeAll('loading');					
						is_allowed_connect = true;									
						get_all_balance();
					},
					success: function (data) {
						layer.alert('<?php echo $this->lang->line('error_transfer_all_successful');?>', {
							skin: 'default-class',
							icon: 1, title: '<?php echo $this->lang->line('label_info');?>',
							btn: '<?php echo $this->lang->line('label_close');?>', 
							closeBtn: 0},function (){ 							
								layer.closeAll();
						});
					}
				});
			}
		}
		
		function transfer_all() {		
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				$.ajax({url: "<?php echo site_url('transfer/transfer_all');?>",
					type: 'get',
					async: 'true',
					beforeSend: function() {
						layer.load(1);											
					},
					complete: function() {					
						layer.alert('<?php echo $this->lang->line('error_transfer_all_successful');?>', {
							icon: 1, title: '<?php echo $this->lang->line('label_info');?>',
							btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0
						}, function (){
							layer.closeAll();
							is_allowed_connect = true;
							$(window.parent.document).find("iframe")[0].contentWindow.location.reload(true);
						});
					},
					success: function (data) {					
					}
				});
			}
		}
		
		function transfer_single(provider) {
			if(is_allowed_connect == true) {
				is_allowed_connect = false;
				
				var site_url = "<?php echo site_url('transfer/transfer_single/"+provider+"');?>";
				$.ajax({url: site_url,
					type: 'get',
					async: 'true',
					beforeSend: function() {					
						layer.load(1);
					},
					complete: function() {					
						layer.closeAll('loading');
						layer.alert('<?php echo $this->lang->line('error_transfer_all_successful');?>', {
							icon: 1, title: '<?php echo $this->lang->line('label_info');?>',
							btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0
						}, function (index){				
							layer.close(index);
							is_allowed_connect = true;
							$(window.parent.document).find("iframe")[0].contentWindow.location.reload(true);
						});
					},
					success: function (data) {					
					}
				});
			}
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
			//hardcode start
			<?php if($this->session->userdata('player_type') == PLAYER_TYPE_MG_MARKET){ ?>
			if(id){
				if(id == "1"){
					$('#nBankAccHidden').show();
					$('#nBankAcc1').hide();
					$('#nBankAcc3').hide();
					$('#nBankAcc4').hide();
					$('#nBankAcc6').hide();
				}else{
					$('#nBankAccHidden').hide();
					$('#nBankAcc1').show();
					$('#nBankAcc3').show();
					$('#nBankAcc4').show();
					$('#nBankAcc6').show();
				}
			}else{
				$('#nBankAccHidden').hide();
				$('#nBankAcc1').show();
				$('#nBankAcc3').show();
				$('#nBankAcc4').show();
				$('#nBankAcc6').show();
			}
			<?php } ?>
			//hardcode end
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
			$.ajax({
				url: "<?= base_url('ajax/get_bank_type_by_currency/') ?>" + id,
				type: 'get',
				async: 'true',
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
					$("#player_bank_id").html('');
					var json = JSON.parse(JSON.stringify(data));
					if(json.status == 0) {
						for (let i = 0; i < json.output.length; i++) {
							if (json.type == <?= BANK_TYPE_CRYTO ?>) {
								$("#player_bank_id").append($('<option></option>').val(json.output[i]['player_bank_id']).html(json.output[i]['bank_account_address'] + ' (' + json.output[i]['bank_name'] + ')'));
							} else {
								$("#player_bank_id").append($('<option></option>').val(json.output[i]['player_bank_id']).html(json.output[i]['bank_account_name'] + " - "+ json.output[i]['bank_account_no'] + " ("+json.output[i]['bank_name'] + " )"));
							}
						}
					}
				},
			});
		}	
		
		var loginSessionInterval = setInterval(verifyLoginSession, 10000);
		function verifyLoginSession() {
			$.ajax({
				url: "<?php echo site_url('home/verify_session');?>",
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

		<?php if($this->uri->segment(1) == 'account' && ($this->uri->segment(2) == 'transaction_history' || $this->uri->segment(2) == 'turnover_history')): ?>
			function select_transaction_type(obj, id) {
				$('.typeBtn').removeClass('active');
				$(obj).addClass('active');
				$('#transaction_type').val(id);
			}

			function select_transaction_date(obj, id) {
				$('.dateRangeBtn').removeClass('active');
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
						case 15: newday.setDate(currentday.getDate()-15); break;
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
			/*
			// code cũ
			function select_transaction_type(obj, id) {
				$('.tablinks').removeClass('btn_inquiry_on').addClass("btn_inquiry_off");		
				$(obj).addClass('btn_inquiry_on');
				$('#transaction_type').val(id);		
				$("#com-panel,#mol-panel").html("");
			}
			
			function select_transaction_date(obj, id) {
				$("a[id*='btnwager-']").removeClass("bg-dark-blue text-white").addClass("btn-outline-dark-blue");
				$("#btnwager-"+id).removeClass("btn-outline-dark-blue").addClass("bg-dark-blue text-white");
				
				if(id == 1) {
					var today = new Date();
					var d = today.getFullYear() + '-' + ("0"+(today.getMonth()+1)).slice(-2) + '-' + ("0" + today.getDate()).slice(-2);
					var start = d;
					var end = d;
					
				}
				else if(id == 3) {
					var date = new Date();
					date.setDate(date.getDate()-1);
					var d = date.getFullYear() + '-' + ("0"+(date.getMonth()+1)).slice(-2) + '-' + ("0" + date.getDate()).slice(-2);			
					var start = d;
					var end = d;
				}
				else if(id == 7) {
					var today = new Date();
					var first = today.getDate() - today.getDay() + 1;
					var last = first + 6;			
					var sunday = new Date(today.setDate(last));
					
					var formattedDate = new Date(sunday);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 9) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 9) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var d_end = yy + "-" + dismonth + "-" + disday;			
					var end = d_end;
					
					var monday = new Date(today.setDate(first));
					var formattedDate = new Date(monday);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					//mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 9) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 9) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var d_start = yy + "-" + dismonth + "-" + disday;			
					var start = d_start;
				}
				else {
					var d = new Date();
					d.setTime(d.getTime() - (d.getDay() ? d.getDay() : 7) * 24 * 60 * 60 * 1000);
					var formattedDate = new Date(d);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 9) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 9) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var d_end = yy + "-" + dismonth + "-" + disday;			
					var end = d_end;
					
					
					
					d.setTime(d.getTime() - 6 * 24 * 60 * 60 * 1000);
					var formattedDate = new Date(d);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 9) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 9) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var d_start = yy + "-" + dismonth + "-" + disday;			
					var start = d_start;
				}
				$("#wager_date").val(start);			
				$("#start_date").val(start);
				$("#end_date").val(end);
				var forms = $("#forms").val();
				if(forms == 2) {
					$("#transactions-form").submit();
				}
				else {
					$("#wager-form").submit();
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
			} */
			
			function load_table_deposit(){
				$('#transaction-table-1').DataTable({
					"destroy": true,
					"processing": true,
					"serverSide": true,
					"scrollX": false,
					"responsive": false,
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
						/*{"targets": [0], "visible": true, className: 'tdC'},
						{"targets": [1], "visible": true, className: 'tdC'},
						{"targets": [2], "visible": true, className: 'tdC'},
						{"targets": [3], "visible": true, className: 'tdC'},
						{"targets": [4], "visible": true, className: 'tdC'}*/
					],
					"language": {
						"processing": "<center class=\"text-sm\"><?php echo $this->lang->line('js_processing');?></center>",
						"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
						"zeroRecords": "<center><?php echo $this->lang->line('js_zero_ecords');?></center>",
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
					"responsive": false,
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
						/*{"targets": [0], "visible": true, className: 'tdC'},
						{"targets": [1], "visible": true, className: 'tdC'},
						{"targets": [2], "visible": false, className: 'tdC'},
						{"targets": [3], "visible": true, className: 'tdC'},
						{"targets": [4], "visible": true, className: 'tdC'}*/
					],
					"language": {
						"processing": "<center class=\"text-sm\"><?php echo $this->lang->line('js_processing');?></center>",
						"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
						"zeroRecords": "<center><?php echo $this->lang->line('js_zero_ecords');?></center>",
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
					"responsive": false,
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
						/*{"targets": [0], "visible": true, className: 'tdC'},
						{"targets": [1], "visible": true, className: 'tdC'},
						{"targets": [2], "visible": true, className: 'tdC'},
						{"targets": [3], "visible": true, className: 'tdC'},*/
					],
					"language": {
						"processing": "<center class=\"text-sm\"><?php echo $this->lang->line('js_processing');?></center>",
						"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
						"zeroRecords": "<center><?php echo $this->lang->line('js_zero_ecords');?></center>",
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
					"responsive": false,
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
						/*{"targets": [0], "visible": true, className: 'tdC'},
						{"targets": [1], "visible": true, className: 'tdC'},
						{"targets": [2], "visible": true, className: 'tdC'},*/
					],
					"language": {
						"processing": "<center class=\"text-sm\"><?php echo $this->lang->line('js_processing');?></center>",
						"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
						"zeroRecords": "<center><?php echo $this->lang->line('js_zero_ecords');?></center>",
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
			
			function load_table_bet(){
				$('#transaction-table-7').DataTable({
					"destroy": true,
					"processing": true,
					"serverSide": true,
					"scrollX": false,
					"responsive": false,
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
						/*{"targets": [0], "visible": true, className: 'tdC'},
						{"targets": [1], "visible": true, className: 'tdC'},
						{"targets": [2], "visible": true, className: 'tdC'},
						{"targets": [3], "visible": true, className: 'tdC'},
						{"targets": [4], "visible": true, className: 'tdC'},
						{"targets": [5], "visible": true, className: 'tdC'},*/
					],
					"language": {
						"processing": "<center class=\"text-sm\"><?php echo $this->lang->line('js_processing');?></center>",
						"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",
						"zeroRecords": "<center><?php echo $this->lang->line('js_zero_ecords');?></center>",
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
	
		<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'turnover_history'):?>	
			function turnover_tab(tab) {
				if(tab == 1) {
					$("#btn1").addClass('bg-dark-pink').removeClass('bg-pink');
					$("#btn2").addClass('bg-pink').removeClass('bg-dark-pink');			
					
					var today = new Date();
					var first = today.getDate() - today.getDay() + 1;
					var last = first + 6;			
					var sunday = new Date(today.setDate(last));
					
					var formattedDate = new Date(sunday);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 10) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 10) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var end = yy + "-" + dismonth + "-" + disday;			
					$("#to_date").val(end);
					
					var monday = new Date(today.setDate(first));
					var formattedDate = new Date(monday);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 10) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 10) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var start = yy + "-" + dismonth + "-" + disday;			
					$("#from_date").val(start);
					
				}
				else {			
					$("#btn1").addClass('bg-pink').removeClass('bg-dark-pink');
					$("#btn2").addClass('bg-dark-pink').removeClass('bg-pink');
					
					var d = new Date();
					d.setTime(d.getTime() - (d.getDay() ? d.getDay() : 7) * 24 * 60 * 60 * 1000);
					var formattedDate = new Date(d);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 10) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 10) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var end = yy + "-" + dismonth + "-" + disday;			
					$("#to_date").val(end);
					
					
					
					d.setTime(d.getTime() - 6 * 24 * 60 * 60 * 1000);
					var formattedDate = new Date(d);
					var dd = formattedDate.getDate();
					var mm =  formattedDate.getMonth();
					mm += 1;  // JavaScript months are 0-11
					var yy = formattedDate.getFullYear();
					
					if(mm > 10) {
						var dismonth =  mm;
					}
					else {
						var dismonth =  "0"+mm;
					}
					
					if(dd > 10) {
						var disday =  dd;
					}
					else {
						var disday =  "0"+dd;
					}
					
					var start = yy + "-" + dismonth + "-" + disday;			
					$("#from_date").val(start);	
					
				}
			}
			
			function turnover_search(){		
				var from = $("#from_date").val();
				var to = $("#to_date").val();
				if(from == '' || to == '') {
					if(from == '') {
						var msg = "<?php echo $this->lang->line('label_start_time'); ?>";
					}
					else {
						var msg = "<?php echo $this->lang->line('label_end_time'); ?>";
					}
					layer.alert(msg, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll();});
				}
				else {
					var send_url = "<?php echo base_url('ajax/turnover_listing/');?>" + from + "/" + to + ".html";
					//console.log(send_url);	
					$.ajax({
						url: send_url,
						type: 'GET',
						dataType: 'json',
						cache: false,
						async: 'true',
						beforeSend: function() {					
							layer.load(1);
							$("#result-table-turnover").html('');
						},
						complete: function() {						
							layer.closeAll('loading');					
						},
						success: function(json) {
							$("#result-table-turnover").html(json.data);
							
						},
						error: function(xhr, status, error) {
							console.log(xhr);						
						}
					});			
				}	
			}
		<?php endif;?>
	
		<?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'deposit'):?>
			function select_payment_method(obj, method, type) {
				$('#deposit_method_div_1').hide();
				$('#deposit_method_div_2').hide();
				$('#deposit_method_div_3').hide();
				$('#deposit_method_div_4').hide();
				$('.tablinks').removeClass('active');
				$(obj).addClass('active');
				$('#deposit_method_div_'+method).show();
			}
			
			function show_deposit_form(index) {
				$("#online_deposit").removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(500);
				$("#credit_deposit").removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(500);
				$("#offline_deposit").removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(500);
				$("#hypermart_deposit").removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(500);
				
				$("img[id*='dp-']").removeClass("uk-blend-luminosity").addClass("uk-blend-luminosity");		
				$("div[id*=check-]").addClass( "d-none" );
				
				if(index=="online") {
					$("#online_deposit").removeClass( "d-none" ).addClass( "d-block" );			
					$("#dp-online").removeClass( "uk-blend-luminosity" );
					$("#check-online").removeClass( "d-none" );
				}
				else if(index=="credit"){
					$("#credit_deposit").removeClass( "d-none" ).addClass( "d-block" );
					$("#dp-cc").removeClass( "uk-blend-luminosity" );
					$("#check-cc").removeClass( "d-none" );
				}
				else if(index=="offline"){
					$("#offline_deposit").removeClass( "d-none" ).addClass( "d-block" );
					$("#dp-offline").removeClass( "uk-blend-luminosity" );
					$("#check-offline").removeClass( "d-none" );
				}
				else{
					$("#hypermart_deposit").removeClass( "d-none" ).addClass( "d-block" );
					$("#dp-hyper").removeClass( "uk-blend-luminosity" );
					$("#check-hyper").removeClass( "d-none" );
				}
			}
			
			function select_payment_gateway(obj, method, code, is_bank, id){		
				var current_gateway = $('#payment_gateway_code_'+method).val();
				var min =  $('.'+code+'_'+method+"_min").val();
				var max =  $('.'+code+'_'+method+"_max").val();
				
				var getPlaceholderAmnout = $('#placeholder_amount_'+id).val();
				$("#amount_2").attr("placeholder",getPlaceholderAmnout);

				$(".limit"+method+"_min").attr("placeholder","TWD"+min+"~"+max);
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
										if(json.output[i]['name']){
											var option_name = json.output[i]['name'];
										}else{
											var option_name = json.output[i]['code'];
										}
										$('#payment_gateway_bank_'+method).append($('<option></option>').val(json.output[i]['code']).html(option_name));
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
			
			function select_bank(obj, type, id, baccname, baccno, bacname, bref) {
				$('.tablinks1').removeClass('active');
				$('.tablinks1').removeClass('bank-active-text');
				$(obj).addClass('active');
				$(obj).addClass('bank-active-text');
				$('#bank_account_id').val(id);
				$('#bank_acc_holder').text(baccname);
				$('#bank_acc_no').text(baccno);
				$('#bank_name').text(bacname);
				$('#bank_reference').text(bref);
			}
			function select_amount(obj, amount, method) {
				$('.fasttablinks'+method).removeClass('active');
				$(obj).addClass('active');
				$('#amount_'+method).val(amount);
				calculateActualAmount(amount,method);
			}
		<?php endif;?>
	<?php
	} else { 
	##################################################NON LOGIN AREA FUNCTION################################################################################
	?>
		<?php if($this->uri->segment(1) == 'register'||$this->uri->segment(1) == 'join'):?>
			function send_sms() {				
				var username = $("#username").val();						
				var mobile = $("#mobile").val();
				if(username == '' || mobile == '') {
					if(username == '') {
						var verify_msg = "<?php echo $this->lang->line('error_username_empty'); ?>";
					}
					else {
						var verify_msg = "<?php echo $this->lang->line('error_mobile_empty'); ?>";					
					}
					alertbox(2,verify_msg);
				}				
				else {
					var res = mobile.substring(0, 2);
					if(res != "09") {
						var verify_msg = "<?php echo $this->lang->line('error_mobile_prefix'); ?>";
						alertbox(2,verify_msg);
					}
					else {
						var send_url = "<?php echo base_url('ajax/send_sms_register/');?>" + username + "/" + mobile;
						//console.log(send_url);	
						$.ajax({
							url: send_url,
							type: 'GET',
							dataType: 'json',
							cache: false,
							async: 'true',
							beforeSend: function() {
								$("#sms-btn").removeClass("disabled").addClass("disabled");
								layer.load(1);
							},
							complete: function() {						
								layer.closeAll('loading');
								$("#sms-btn").removeClass("disabled");
							},
							success: function(json) {
								//console.log(json);	
								if(json.status == <?php echo EXIT_SUCCESS; ?>) {
									var icon_status = 1;
									$('#username,#password,#passconf,#line_id,#referrer,#mobile').prop('readonly', true);
								}
								else {
									var icon_status = 2;
									$('#username,#password,#passconf,#line_id,#referrer,#mobile').prop('readonly', false);
								}
								alertbox(icon_status,json.msg);
								
							},
							error: function(xhr, status, error) {
								console.log(xhr);						
							}
						});	
					}
				}				
			}		
		<?php endif;?>	
	<?php } ?>
	
	<?php ##############################################GENERAL AREA FUNCTION################################################################################ ?>
	<?php if($this->uri->segment(1) == 'promotion'):?>	
	function promotion_list(num) {		
		var endpoint = "<?php echo site_url('ajax/promotion/"+num+"');?>";		
		$.ajax({url: endpoint,
			type: 'get',
			datatype: 'json',
			async: 'true',
			cache:false,
			beforeSend: function() {
				/*custom*/				
				<?php if($this->agent->is_mobile()) { ?>				
				$("[id*=btnpromo-]").removeClass("text-blue").addClass("text-secondary");
				$("#btnpromo-" + num).removeClass("text-secondary").addClass("text-blue");
				<?php }else { ?>				
				$("[id*=btnpromo-]").removeClass( "bg-gradient-yellow2" ).addClass( "bg-gradient-blue5").removeClass("fc_fcbe01").addClass("text-secondary");
				$("#btnpromo-"+num).removeClass( "bg-gradient-blue5" ).addClass("bg-gradient-yellow2").addClass("fc_fcbe01").removeClass("text-secondary");
				/*
				if(num==0){
					$("#img-0").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/all_black.svg');?>");
					$("#img-1").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/limit.svg');?>");
					$("#img-2").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/always.svg');?>");
				}else if(num==1){
					$("#img-0").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/all.svg');?>");
					$("#img-1").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/limit_black.svg');?>");
					$("#img-2").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/always.svg');?>");
				}else{
					$("#img-0").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/all.svg');?>");
					$("#img-1").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/limit.svg');?>");
					$("#img-2").attr("src","<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/promotion/always_black.svg');?>");
				}
				*/
				<?php } ?>
				/********/
				layer.load(1);
				$("#promotion-panel").html("");				
			},
			complete: function() {
				layer.closeAll('loading');
			},
			success: function (json) {
				//console.log(json);	
				var html = '';	
				if(json.length > 0){					
					var banner_path = "<?php echo UPLOAD_PATH.'promotions/'; ?>";
					
					for(i = 0; i < json.length; i++) {
						var banner_web = banner_path + json[i]['promotion_banner_web'];
						var banner_mobile = banner_path + json[i]['promotion_banner_mobile'];
						html += '<div class="col-12 bg-dark">';
						html += '<div class="row g-md-0">';
						html += '<div class="col-md-4 text-center">';
						
						html += '<img src="'+banner_web+'" alt="" class="img-fluid d-none d-md-block h-100">';
						html += '<img src="'+banner_mobile+'" alt="" class="img-fluid d-md-none">';
						
						html += '</div>';
						html += '<div class="col-md-6 p-3">';
						html += '<h3>'+json[i]['promotion_title']+'</h3>';
						html += '<button type="button" class="btn btn_gold" data-bs-toggle="modal" data-bs-target="#exampleModal'+i+'">';
						html += '<?php echo $this->lang->line('label_more');?>';
						html += '</button>';
						html += '</div>';
						html += '<div class="col-md-2 p-3">';
						html += '<div class=""><i class="fa-regular fa-clock"></i> <span>Remaining Time</span></div>';
						html += '<div class="fs-4">No Limit Time</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						
						html += '<div class="modal fade" id="exampleModal'+i+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
						html += '<div class="modal-dialog modal-xl">';
						html += '<div class="modal-content bg-dark">';
						html += '<div class="modal-header border-0">';
						html += '<div class="col-12">';
						html += '<button type="button" class="btn-close float-end position-absolute bg-light rounded-pill" style="right:20px;" data-bs-dismiss="modal" aria-label="Close"></button>';
						html += '<div class="row g-0">';
						html += '<div class="col-md-12 text-center">';
						
						html += '<img src="'+banner_web+'" alt="" class="img-fluid d-none d-md-block">';
						html += '<img src="'+banner_mobile+'" alt="" class="img-fluid d-md-none">';
						
						html += '</div>';
						html += '<div class="col-md-12 pt-3">';
						html += '<h3>'+json[i]['promotion_title']+'</h3>';
						html += '<div class=""><i class="fa-regular fa-clock"></i> <span>Remaining Time</span></div>';
						html += '<div class="fs-4">No Limit Time</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						html += '<div class="modal-body bg-light">';
						html += json[i]['promotion_content'];
						html += '</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						
					}					
				}
				else {
					html += '<div class="col-md-8 col-11 shadow-lg text-center fw-bold py-3">~<?php echo $this->lang->line('label_no_promo');?>~</div>';
				}				
				$("#promotion-panel").html(html);
			}
		});
	}
	
	function fileExists(url) {
		if(url){
			var req = new XMLHttpRequest();
			req.open('GET', url, false);
			req.send();
			return req.status==true;
		} 
		else {
			return false;
		}
	}
	<?php endif;?>
	
	function transfer_popup(provider_code,game_type,game_code) {
		var site_url = "<?php echo site_url('ajax/check_login/"+provider_code+"'); ?>";		
		$.ajax({url: site_url,
			type: 'get',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {
				layer.load(1);
			},
			complete: function() {
				layer.closeAll('loading');
			},
			success: function (data) {
				if(data.status == 'true') {
					game_code = ((game_code != undefined) ? game_code : '');
					<?php if ($this->agent->is_mobile()) { ?>
						var w = '88vw';
						var h = '56vh';
					<?php } else { ?>
						var w = '46vw';
						var h = '70vh';
					<?php } ?>
					layer.open({
						title: ['<span class="fw-bold text-dark-blue fs-5"><?php echo $this->lang->line('label_game_transfer'); ?></span>'],
						type: 2,
						skin: 'layui-layer-rim',
						shadeClose: true,
						shade: 0.2,
						scrollbar: false,
						area: [w, h],
						content: '<?php echo base_url("transfer/transfer_popup/'+provider_code+'/'+game_type+'/'+game_code+'"); ?>'
					});
				}
				else{
					alertbox(0,data.msg);
				}
			},
			error: function (request,error) {
				console.log(request);
			}
		});
	}
	
	function show_blog(index) {
		
		$("[class*=catergorymenu]").removeClass( "active" ).fadeOut(0).fadeIn(0);
		$(".catergorymenu-"+index).addClass( "active" );
		$("[class*=cat-]").removeClass( "" ).addClass( "d-none" );
		$("[id*=blog-]").removeClass( "text-blue").addClass("text-secondary");


		$("[id*=web-]").addClass("text-dark").removeClass( "bg-gradient-blue3").removeClass("rounded-pill").removeClass("text-light").removeClass("shadow");
		$("#web-"+index).removeClass("text-dark").addClass("rounded-pill").addClass( "bg-gradient-blue3" ).addClass("text-light").addClass("shadow");
		$("#blog-"+index).addClass( "text-blue" );
		$.ajax({
			url: "<?php echo site_url('ajax/blog_category_list/"+index+"'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function(json) {								
				$("#"+index).html(json.html);
				$("#"+index).removeClass( "d-none" ).addClass( "" );					
				if(json.size > 10) {
					var items = $(".list-wrapper .list-item."+index);
					var numItems = items.length;
					var perPage = 10;

					items.slice(perPage).hide();
					$('#pagination-container'+index).pagination({
						
						items: numItems,
						itemsOnPage: perPage,
						prevText: "&laquo;",
						nextText: "&raquo;",
						onPageClick: function (pageNumber) {
							var showFrom = perPage * (pageNumber - 1);
							var showTo = showFrom + perPage;
							items.hide().slice(showFrom, showTo).show();
						}
					});
				}
			},
			error: function(xhr, status, error) {
				console.log(xhr);
				
			}
		});			
	}
	
	function news_url() {		
		$.ajax({
			url: "<?php echo site_url('ajax/news_url'); ?>",
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {				
			},
			complete: function() {					
			},
			success: function(json) {				
				$(".news-list,#news-list1,#news-list11,#news-list2,#news-list3,#news-list4").attr('href',json.url);
			},
			error: function(xhr, status, error) {
				console.log(xhr);				
			}
		});
	}
	
	function main_banner() {
		$.ajax({
			url: "<?php echo site_url('ajax/main_banner'); ?>",
			type: 'GET',
			dataType: 'html',
			cache: true,
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function(output) {
				$("#carousel-home").html(output);				
				$('.carousel').carousel({
				  interval: 3000
				});				
			},
			error: function(xhr, status, error) {				
			}
		});
	}
	
	function main_footer_blog_menu() {
		$.ajax({
			url: "<?php echo site_url('ajax/blog_category'); ?>",
			type: 'GET',
			dataType: 'html',
			cache: false,
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function(output) {
				$("#blog-menu").html(output);								
			},
			error: function(xhr, status, error) {
				//console.log(xhr);
				
			}
		});
	}
	
	function game_page(category) {
		var murl = "<?php echo site_url('ajax/game_page/"+category+"'); ?>";		
		$.ajax({
			url: murl,
			type: 'GET',
			dataType: 'html',
			cache: false,
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function(output) {
				$("#game-article-page").html(output);
			},
			error: function(xhr, status, error) {
				console.log(xhr);			
			}
		});	
	}
	
	function product_page(category,index) {
		
		var murl = "<?php echo site_url('ajax/product_page/"+category+"'); ?>";		
		$.ajax({
			url: murl,
			type: 'GET',
			dataType: 'json',
			cache: false,
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function(json) {
				console.log(json);
				$(index).html(json.html);
				if(json.size > 0) {
					var items = $(".list-wrapper .list-item");
					var numItems = items.length;
					var perPage = 5;

					items.slice(perPage).hide();

					$('#pagination-container').pagination({
						items: numItems,
						itemsOnPage: perPage,
						prevText: "&laquo;",
						nextText: "&raquo;",
						onPageClick: function (pageNumber) {
							var showFrom = perPage * (pageNumber - 1);
							var showTo = showFrom + perPage;
							items.hide().slice(showFrom, showTo).show();
						}
					});
				}
			},
			error: function(xhr, status, error) {
				console.log(xhr);			
			}
		});	
	}
	
	function change_language(id) {
		location.href = "<?php echo base_url('ajax/change/');?>" + id;
	}

	function checkTime(i) {
		if (i < 10) {
			i = "0" + i
		}; // add zero in front of numbers < 10
		return i;
	}

	function top_function() {
	  document.body.scrollTop = 0;
	  document.documentElement.scrollTop = 0;
	}
	
	function footer_contact() {
		$.ajax({url: "<?php echo site_url('ajax/contact_list');?>",
			type: 'get',
			datatype: 'json',
			async: 'true',
			beforeSend: function() {},
			complete: function() {},
			success: function (json) {				
				$('#line_url').attr('href', json.line_url);
				$('#fb_url').attr('href', json.fb_url);				
			}
		});
	}
	
</script>
<script type="text/javascript">	
	
	$(document).ready(function() {						
		
		<?php 
		/*
		if($this->session->userdata('msg_alert')):
		?>
		layer.alert('<?php echo $this->session->userdata('msg_alert');?>', {icon: <?php echo $this->session->userdata('msg_icon');?>, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>'}, function (){ layer.closeAll(); });
		<?php $this->session->unset_userdata(array('msg_alert', 'msg_icon'));?>
		<?php 
		endif;
		*/
		?>
	});
	var is_allowed_connect = true;
	
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
		var base_url = '<?php echo base_url(); ?>';

		$.ajax({
			type: 'GET',
			url: base_url + 'promotion/showModalInfo/' + id,
			success: function(response) {
				var promotionData = JSON.parse(response);
				var pb = "<?php echo UPLOAD_PATH.'promotions/'; ?>"+promotionData.promotion_banner_web;	

				$('#myModal .modal-title').text(promotionData.promotion_title);
				$('#myModal .modal-body').html("<img src='"+pb+"' class='img-fluid'>");
				$('#myModal .modal-body').append(promotionData.promotion_content);

				$('#myModal').modal('show');

				$('#myModal .close').on('click', function() {
					$('#myModal').modal('hide');
				});
			},
			error: function() {

			}
		});
	}
	function close_promo(id) {
		$('body').removeClass('modal-open');
		$('#promo-modal-' + id).hide();
	}
	<?php endif;?>
	var is_allowed_open = true;
	function open_game(provider_code, game_type_code, game_code) {		
		if(is_allowed_open == true) {
			
			var windowReference = window.open("<?php echo base_url('home/loading'); ?>");
			
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
						<?php /*if($this->agent->is_mobile()) { ?>
						windowReference.location = json.url;
						<?php } else { ?>
						var event = "window.open('" + json.url + "', '_blank');";							
						$('#launch_game').attr('onclick', event).unbind('click');						
						$("#launch_game").click();
						<?php }*/ ?>
						windowReference.location = json.url;
						
						layer.closeAll();
					}
					else{
						windowReference.close();
						
						var icon = '<i class="fa-regular fa-circle-xmark text-danger fs-6"></i>&nbsp;';
						layer.alert(icon+message,{			
							skin: 'default-class',
							title: '<?php echo $this->lang->line('label_info');?>',
							closeBtn: 0,
							btn: '<?php echo $this->lang->line('label_close');?>',
							anim: 2,
							shade: 0.8,
							shadeClose: true }, function() {
								layer.closeAll();	
						});
					}
				},
				error: function (request,error) {
					console.log(request);
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
			
			/***Custom******/
			<?php if($this->agent->is_mobile()) { ?>
			$(".nav-link").removeClass("text-primary").addClass("text-secondary");
			//$("#slotbtn-" + provider_code.toLowerCase()).removeClass("btn-slot-default").addClass("bg-gradient-darkyellow");
			$("#slotbtn-" + provider_code).removeClass("text-secondary").addClass("text-primary");
			<?php }else { ?>
			//$("a[id*='slotbtn-']").removeClass("text-primary").addClass("text-secondary");
			$(".nav-link").removeClass("text-primary").addClass("text-secondary");
			$("#slotbtn-" + provider_code).removeClass("text-secondary").addClass("text-primary");
			<?php } ?>
			
			$.ajax({url: "<?php echo base_url('ajax/sub_game/');?>" + provider_code + '/' + game_type_code,
				type: 'get',
				async: 'true',
				cache: 'false',
				beforeSend: function() {
					layer.load(1);
				},
				complete: function() {
					layer.closeAll('loading');
					is_allowed_open = true;
				},
				success: function (data) {
					var json = JSON.parse(JSON.stringify(data));
					console.log(json);
					if(json.errorCode == '<?php echo EXIT_SUCCESS;?>') {
						if(json.result.length > 0){
							var html = '';
							//html += '<input type="text" class="offset-sm-9 col-sm-3" onkeyup="search()" id="search-text" placeholder="搜索">';
							for(i = 0; i < json.result.length; i++) {
								
								html += '<div class="col-md-2 col-4 list-item">';
								html += '<div class="card bg-transparent border-0">';
								html += '<div class="card-body p-0">';
								html += '<div class="row g-0 justify-content-between">';
								html += '<div class="col-12">';
								html += '<a href="javascript:;" onClick="open_game(\'' + provider_code + '\', \'' + game_type_code + '\', \'' + json.result[i]['game_code'] + '\');"><img class="img-fluid lazy w-100 rounded-3" src="' + json.result[i]['game_picture'] + '?v=3" alt="' + json.result[i]['game_name'] + '"></a>';
								html += '</div>';
								html += '<div class="w-100"></div>';
								html += '<div class="col text-center game-name">' + json.result[i]['game_name'] + '</div>';
								html += '<div class="col-auto d-none"><i class="fas fa-heart text-secondary"></i></div>';
								html += '</div></div></div></div>';
							}
							
							$('#game-panel').html(html);
							$(".lazy").lazy();
							
							if(json.result.length > 18){
								$('#pagination-container').removeClass("d-none");
								var items = $(".list-wrapper .list-item");
								var numItems = items.length;
								var perPage = 18;

								items.slice(perPage).hide();

								$('#pagination-container').pagination({
									items: numItems,
									itemsOnPage: perPage,
									prevText: "&laquo;",
									nextText: "&raquo;",
									onPageClick: function (pageNumber) {
										var showFrom = perPage * (pageNumber - 1);
										var showTo = showFrom + perPage;
										items.hide().slice(showFrom, showTo).show();
									}
								});
							}
							else {
								$('#pagination-container').addClass("d-none");
							}
						}
						else {
							$('#game-panel').html("");
							$('#pagination-container').addClass("d-none");
						}
					}
					else {
						$('#game-panel').html("");
						$('#pagination-container').addClass("d-none");
					}
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
	
	function scrollfunction() {
	  if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
		$("#back-to-top").removeClass("d-none");
	  } 
	  else {
		$("#back-to-top").addClass("d-none");
	  }
	}

	// When the user clicks on the button, scroll to the top of the document
	function topfunction() {
	  document.body.scrollTop = 0; // For Safari
	  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	}
	
	function coming_soon() {
		alertbox(0,"<?php echo $this->lang->line('label_coming_soon');?>");
	}

	function please_login() {
		alertbox(0,"<?php echo $this->lang->line('error_please_login_to_continue');?>");
	}
	
	function alertbox(status,msg) {
		if(status == 1) {
			var icon = '<i class="fa-regular fa-circle-check text-light-green fs-6"></i>&nbsp;';
		}
		else if(status == 2) {
			var icon = '<i class="fa-regular fa-circle-xmark text-danger fs-6"></i>&nbsp;';
		}
		else {
			var icon = '<i class="fa-solid fa-circle-info text-primary fs-6"></i>&nbsp;';
		}
		
		layer.alert(icon+msg,{			
			skin: 'default-class',
			title: '<?php echo $this->lang->line('label_info');?>',
			closeBtn: 0,
			btn: '<?php echo $this->lang->line('label_close');?>',
			anim: 2,
			shade: 0.8,
			shadeClose: true
		});
	}
</script>

<!-- Mobile nav tab Jquery -->
<script type="text/javascript">
	function onchange_icon(type) {

	<?php if ($this->uri->segment(1) == '' || $this->uri->segment(1) === NULL) { ?>
		var path = "<?php echo base_url('themes/'.SYSTEM_THEME.'/mobile/assets/img/category_icon/'); ?>";
		$('#casino_link').attr('src', path + 'icon_live_active.gif?v=1');
		$('#slot_link').attr('src', path + 'icon_slot_active.gif?v=1');
		$('#sport_link').attr('src', path + 'icon_sport_active.gif?v=1');
		$('#esport_link').attr('src', path + 'icon_esport_active.gif?v=1');
		$('#fishing_link').attr('src', path + 'icon_fishing_active.gif?v=1');
		$('#board_link').attr('src', path + 'icon_board_active.gif?v=1');
		$('#cock_link').attr('src', path + 'icon_cock_active.gif?v=1');

		if (type == "sport") {
			$('#sport_link').attr('src', path + 'icon_sport_active.gif?v=1');

		} else if (type == "slot") {
			$('#slot_link').attr('src', path + 'icon_slot_active.gif?v=1');

		} else if (type == "fishing") {
			$('#fishing_link').attr('src', path + 'icon_fishing_active.gif?v=1');

		} else if (type == "esport") {
			$('#esport_link').attr('src', path + 'icon_esport_active.gif?v=1');

		} else if (type == "board") {
			$('#board_link').attr('src', path + 'icon_board_active.gif?v=1');

		} else if (type == "cock") {
			$('#cock_link').attr('src', path + 'icon_cock_active.gif?v=1');

		} else {
			$('#casino_link').attr('src', path + 'icon_live_active.gif?v=1');

		}
		<?php } else { ?>
			window.location.href = "<?php echo site_url(''); ?>";
		<?php } ?>
	}
	
	
	function snackbar() {		
		$.ajax({url: "<?php echo site_url('ajax/snackbar');?>",
			type: 'get',
			datatype: 'json',
			async: 'true',			
			success: function (json) {
				layer.alert(json.msg,{			
					type: 0,
					title: false,
					skin: 'demo-class2',			
					closeBtn: 0,
					btn: false,	
					anim: 2,
					shade: 0,
					shadeClose: false,
					offset: 'b',
					time: 3000
				});				
			},
			error: function (xhr,req) {
				console.log(xhr);
			}
		});	
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		
		var is_allowed = true;
		<?php 
		if($this->agent->is_mobile() || $this->uri->segment(1) == "register"){
			echo 'function triggerSideNav() {
				$("#navMenu").on("click", function(e) {
					$("#mySidenav").addClass("active");
				});
		
				$("#mySidenav .backdrop, #mySidenav a.left-nav__top__nav__item__link").on("click", function(e) {
					$("#mySidenav").removeClass("active");
				});
			}
			$(document).ready(function () {
				triggerSideNav();
				});';
		}else{
			echo "startTime();";
		}
		?>
	})
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
		//console.clear();

		let jackpot = new Jackpot('.jackpot');
	})()
	</script>
<?php if(!$this->agent->is_mobile()) : ?>
	
<?php endif; ?>
