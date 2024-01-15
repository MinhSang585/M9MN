
<?php $this->load->view('web/parts/header');?>

<div id='theme-contain-registration'>
	<div id="account-panel">
		<ul>
			<li>
				<a href="<?php echo site_url('account/deposit');?>"><?php echo $this->lang->line('lang_deposit');?></a>
			</li>
			<li>
				<a href="<?php echo site_url('account/withdrawal');?>"><?php echo $this->lang->line('lang_withdrawal');?></a>
			</li>
			<li>
				<a href="<?php echo site_url('account/transaction_history');?>"><?php echo $this->lang->line('lang_transaction_history');?></a>
			</li>
			<li>
				<a href="<?php echo site_url('account/change_password');?>" class="active"><?php echo $this->lang->line('lang_change_password');?></a>
			</li>
		</ul>
	
		<div class="content">
			<h1><?php echo strtoupper($this->lang->line('lang_change_password'));?></h1>
			<?php echo form_open('ajax/change_password', array('id' => 'registerform', 'class' => 'registerform'));?>
				<dl>
					<dt><?php echo $this->lang->line('label_current_password');?> : </dt>
					<dd>
						<input type='password' name='oldpass' placeholder='* <?php echo $this->lang->line('label_current_password');?>' maxlength="15" value=''>
					</dd>
				</dl>
				<dl>
					<dt><?php echo $this->lang->line('label_new_password');?> : </dt>
					<dd>
						<input type='password' name='password' placeholder='* <?php echo $this->lang->line('label_new_password');?>' maxlength="15" value=''>
					</dd>
				</dl>
				<dl>
					<dt><?php echo $this->lang->line('label_confirm_new_password');?> : </dt>
					<dd>
						<input type='password' name='passconf' placeholder='* <?php echo $this->lang->line('label_confirm_new_password');?>' maxlength="15" value=''>
					</dd>
				</dl>
				<dl id='groupSubmit'>
					<dt></dt>
					<dd><input type='submit' value='<?php echo $this->lang->line('lang_submit');?>'></dd>
				</dl>
			<?php echo form_close();?>
		</div>
	</div>	
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var is_allowed = true;
		var form = $('#registerform');
		
		form.submit(function(event) {
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
						
						$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
						
						if(json.status == '<?php echo EXIT_SUCCESS;?>') {
							msg_icon = 1;
							$('#registerform')[0].reset();
						}
								
						layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>'});
					},
					error: function (request,error) {
					}
				});
			}
			
			event.preventDefault();
		});
	});	
</script>

<?php $this->load->view('web/parts/footer');?>