<?php $this->load->view('web/parts/header');?>
	
	<div class="container py-5 mb-5">
		<div class="row justify-content-center">
			<?php $this->load->view('web/parts/wallet'); ?>
			<div class="col-9 account-layout__container__content__right">
				<div class="row justify-content-md-start justify-content-center account-layout__container__content__right__content my-profile-page__content">
					<div class="my-profile-page__content__title"><?php echo $this->lang->line('label_inbox'); ?></div>
					<div class="col-md-6 bg-sharp-grey rounded-pill">
						<div class="row py-2">
							<?php if ($this->session->userdata('is_logged_in') == TRUE) : ?>
							<div class="col-4 d-grid">
								<a href="javascript:;" class="btn border-0 rounded-pill text-decoration-none bg-gradient-blue3 text-white"><?php echo $this->lang->line('label_personal_message');?></a>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-11 border-bottom-grey">&nbsp;</div>
				</div>
				<div class="row justify-content-center list-wrapper">
					<?php
					if(sizeof($messages)>0) {
						$i=1;
						foreach($messages as $msg) {
							if($msg['is_read'] == 1) {
								$read = '<span class="text-sm text-danger fw-bold" id="marker'.$i.'">('.$this->lang->line('label_unread').')</span>';
							}
							else {
								$read = '<span class="text-sm text-success fw-bold" id="marker'.$i.'">('.$this->lang->line('label_read').')</span>';
							}
					?>
					<div class="list-item col-md-11 border-bottom py-2">
						<div class="row align-items-center">
							<div class="col-auto me-0">
								<a href="javascript:;" class="btn btn-sm text-sm" onclick="delete_message(<?php echo $msg['system_message_user_id']; ?>,'<?php echo $msg['system_message_title']; ?>');"><i class="fas fa-trash-alt text-danger"></i></a>
							</div>
							<div class="col-auto d-none">
								<!-- <img src="<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/announcement/envelop.png');?>" alt="" class="img-fluid"> -->
							</div>
							<div class="col d-grid">
								<a class="btn text-start text-white text-decoration-none text-truncate" data-bs-toggle="collapse" href="#ann<?php echo $i; ?>" role="button" aria-expanded="false" aria-controls="ann<?php echo $i; ?>">【<?php echo $msg['system_message_title']; ?>】<?php echo $read; ?></a>
								<span class="d-none" id="uid<?php echo $i; ?>"><?php echo $msg['system_message_user_id']; ?></span>
							</div>
							<div class="col-auto text-end text-sm text-white">
								<?php echo date("Y-m-d", $msg['created_date']); ?>
							</div>
							<div class="col-12 collapse multi-collapse mt-3 text-secondary text-sm text-white" id="ann<?php echo $i; ?>" style="margin-left:12%">
								<?php echo $msg['system_message_content']; ?>
							</div>
						</div>
					</div>
					<?php
							$i++;
						}
					} else {
					?>
					<div class="list-item col-md-11 border-bottom-grey py-2 text-center"><?php echo $this->lang->line('label_no_messages');?></div>
					<?php } ?>
				</div>
				<?php if(sizeof($messages)>10) : ?>
				<div id="pagination-container" class="mt-4"></div>
				<?php endif; ?>
			</div>
		</div>
	</div>	
<?php $this->load->view('web/parts/footer');?>
<script type="text/javascript">
	$(document).ready(function() {
		<?php if(sizeof($messages)>10) : ?>
		var items = $(".list-wrapper .list-item");
		var numItems = items.length;
		var perPage = 10;

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
		<?php endif; ?>
		
		<?php 
		if(sizeof($messages)>0) {
			$j=1;
			foreach($messages as $msg) {
		?>
			var myCollapsible = document.getElementById('ann<?php echo $j; ?>');
			if(myCollapsible) {
				myCollapsible.addEventListener('shown.bs.collapse', function () {
					var index = $("#uid<?php echo $j; ?>").html();
					$.ajax({url: "<?php echo base_url('message/update_message');?>/"+index,
						type: 'get',
						dataType: 'json',
						cache: false,
						async: 'true',
						beforeSend: function() {
							//layer.load(1);
						},
						complete: function() {
							//layer.closeAll('loading');
						},
						success: function (json) {
							var message = json.msg;
							$("#marker<?php echo $j; ?>").removeClass('text-danger').addClass('text-success'); 
							$("#marker<?php echo $j; ?>").html('(<?php echo $this->lang->line('label_read');?>)');
							inbox_counter();
							/*
							if(json.status == '<?php echo EXIT_SUCCESS;?>') {
								layer.alert(message, {icon: 1, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); location.reload(); });
							}else{
								layer.alert(message, {icon: 2, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('label_close');?>', closeBtn: 0}, function (){ layer.closeAll(); });
							}
							*/
						}
					});
				});
			}
		<?php
				$j++;
			}
		}
		?>
	});
	
</script>
