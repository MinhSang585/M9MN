<?php $this->load->view('mobile/parts/header'); ?>

<section class="main login mt-3">
    <nav class="navbar fixed-top navbar-light nav-child">
        <div class="container-fluid content ">
            <a id="back_url" class="" href="javascript:history.back();">
                <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
            </a>
            <div class="title"><?php echo $this->lang->line('label_forgot_password');?></div>
            <div class="opacity-0 d-none">

			</div>
            <a class="" href="<?php echo site_url('account/transaction_history') ?>">
                
            </a>
        </div>
    </nav>
    <div class="wallet__content container-fluid mt-4">
        <div class="wallet__content__top">
		<?php echo form_open('ajax/forgot_password', array('id' => 'forgot-password-form'));?>
			<div class="cont">
				<div style="text-align:center;">
					<span style="color:#767676;font-size:14px;"><?php echo $this->lang->line('label_fill_up_and_send_email');?></span><br><br><br>
				</div>
				<div class="general_err" id="display_db_error"></div>
				<div class="form-group">
					<div class="col-sm-10 pb-3">
						<input type="text" name="username" maxlength="64" placeholder="<?php echo $this->lang->line('label_username');?>">
					</div>
					<div class="col-sm-10 pb-3">
						<input type="text" name="mobile" maxlength="32" placeholder="<?php echo $this->lang->line('label_customer_phone_number');?>">
					</div>
					<?php /*
					<div class="col-sm-10 pb-3">
						<input type="text" class="form-control" name="email" maxlength="64" placeholder="user@example.com">
					</div>
					*/ ?>
				</div>
				<div class="form-btn-wrap">
					<button class="btn btn-primary w-100" id="submitButton"><?php echo $this->lang->line('label_submit');?></button>
				</div>
			</div>
		<?php echo form_close();?>
        </div>
    </div>

</section>
<?php $this->load->view('mobile/parts/footer'); ?>