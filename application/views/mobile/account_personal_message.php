<?php $this->load->view('mobile/parts/header'); ?>

<section class="main withdrawal pt-0">
	<nav class="navbar fixed-top sticky-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
			</a>
			<div class="title"><?php echo $this->lang->line('label_inbox'); ?></div>
			<div class="opacity-0 ">
				
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="row py-2">
			<?php if ($this->session->userdata('is_logged_in') == TRUE) : ?>
			<div class="col-4 d-grid">
				<a href="javascript:;" class="btn border-0 rounded-pill text-decoration-none bg-gradient-blue3 text-white"><?php echo $this->lang->line('label_personal_message');?></a>
			</div>
			<?php endif; ?>
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
						<img src="<?php echo base_url('themes/' . SYSTEM_THEME . '/web/assets/img/announcement/envelop.png');?>" alt="" class="img-fluid">
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
</section>

<?php $this->load->view('mobile/parts/footer'); ?>