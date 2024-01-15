<?php $this->load->view('mobile/parts/header'); ?>

<section class="main login mt-3">
    <nav class="navbar fixed-top navbar-light nav-child">
        <div class="container-fluid content ">
            <a id="back_url" class="" href="javascript:history.back();">
                <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
            </a>
            <div class="title"><?php echo $this->lang->line('label_create_an_account');?></div>
            <div class="opacity-0 d-none">
                
            </div>
            <a class="" href="<?php echo site_url('account/transaction_history') ?>">
                
            </a>
        </div>
    </nav>
    <div class="wallet__content container-fluid mt-4">
        <div class="wallet__content__top">
        <?php echo form_open('ajax/register', array('id'=>'sign-up-form','autocomplete'=>'off'));?>
            <div class="cont">

                <div class="input_relative">
                <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_username.png') ?>">
					<input type="text" name="username" maxlength="16" placeholder="<?php echo $this->lang->line('label_username');?>">
                </div>

                <div class="input_relative">
                <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_password.png') ?>">
					<input type="password" name="password" maxlength="15" placeholder="<?php echo $this->lang->line('label_password');?>">
                </div>

                <div class="input_relative">
                <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_password.png') ?>">
					<input type="password" name="passconf" maxlength="15" placeholder="<?php echo $this->lang->line('label_confirm_password');?>" >
                </div>

                <style>
                    .rmb {
                        font-size: 14px;
                        position: inherit;
                        top: -2px;
                        color: #5f6c7b;
                    }

                    .btn_tac {
                        width: 35%;
                        height: 40px;
                        float: right;
                        font-size: 14px;
                        color: #e1b09f;
                        font-weight: bold;
                        line-height: 32px;
                        margin-top: 12px;
                        border: 2px solid #e1b09f;
						background:#000000;
                    }
                </style>

                <div class="input_relative" style="display:none;">
                    <input type="text" name="email" maxlength="64" placeholder="<?php echo $this->lang->line('label_email');?>">
                </div>

				<div class="input_relative">
                <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_mobile.png') ?>">
                    <input type="text" name="mobile" id="mobile" maxlength="16" placeholder="<?php echo $this->lang->line('label_mobile');?>">
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div class="input_relative" style="width: 55%; float: left;">
                    <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_verify.png') ?>">
						<input type="text" value="" name="captcha" maxlength="4" placeholder="<?php echo $this->lang->line('label_captcha');?>" >
                    </div>
                    <div class="btn_tac btnTac">
                        <a href="javascript:void(0)"><img src="<?php echo site_url('ajax/captcha');?>" id="captcha" /></a>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 20px;">
					<input type="hidden" name="fingerprint" id="fingerprint" value="" class="input_fingerprint">
					<input type="hidden" name="referrer" id="referrer" value="<?php echo $this->session->userdata('referrer');?>">
					<button type="submit" name="submit" class="btn btn-primary w-100" id="register_submit_btn"><?php echo $this->lang->line('label_register');?></button>
                </div>

            </div>
        <?php echo form_close();?>
        </div>
    </div>

</section>
<?php $this->load->view('mobile/parts/footer'); ?>