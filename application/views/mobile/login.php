<?php $this->load->view('mobile/parts/header'); ?>

<section class="main login mt-3">
    <nav class="navbar fixed-top navbar-light nav-child">
        <div class="container-fluid content ">
            <a id="back_url" class="" href="javascript:history.back();">
                <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
            </a>
            <div class="title"><?php echo $this->lang->line('label_login');?></div>
            <div class="opacity-0 d-none">
                
            </div>
            <a class="" href="<?php echo site_url('account/transaction_history') ?>">
                
            </a>
        </div>
    </nav>
    <div class="wallet__content container-fluid mt-4">
        <div class="wallet__content__top">
            <?php echo form_open('ajax/login', array('id' => 'login-form', 'autocomplete' => 'off', 'class' => 'form form-horizontal container_login')); ?>
            <div class="cont">
                <div class="container_withdraw">
                    <div class="input_relative">
                        <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_username.png') ?>">
                        <input name="username" type="text" placeholder="<?php echo $this->lang->line('label_username'); ?>"/>
                    </div>

                    <div class="input_relative">
                    <img style="margin-top: 23px;" class="iconfont icon_size" src="<?php echo base_url('assets/mobile/img/signup_icon_password.png') ?>">
                        <input name="password" type="password" placeholder="<?php echo $this->lang->line('label_password'); ?>">
                    </div>

                    <style>
                        .rmb {
                            font-size: 14px;
                            position: inherit;
                            top: -2px;
                            color: #5f6c7b;
                        }
                    </style>

                    <div style="position:relative;margin-top:10px;">
                        <span class="rmb"><a class="rmb" href="<?php echo site_url('forgot'); ?>"><?php echo $this->lang->line('label_forgot_password'); ?></a></span>
                    </div>

                    <div style="text-align: center;margin-top:20px;">
                        <input type="submit" name="login" class="btn btn-primary w-100" value="<?php echo $this->lang->line('label_login'); ?>">
                    </div>

                    <div style="width:100%;margin-top:30px;height:30px;color: #5f6c7b;font-size: 15px;">
                        <?php echo $this->lang->line('label_no_acc'); ?>?
                        <a href="<?php echo site_url('register'); ?>" style="color:#ff2200;font-size: 17px;"><?php echo $this->lang->line('label_join_now'); ?></a>
                    </div>

                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</section>
<?php $this->load->view('mobile/parts/footer'); ?>