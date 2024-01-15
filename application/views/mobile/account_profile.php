<?php $this->load->view('mobile/parts/header'); ?>

<section class="main wallet mt-3">
    <nav class="navbar fixed-top navbar-light nav-child">
        <div class="container-fluid content ">
            <a id="back_url" class="" href="javascript:history.back();">
                <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
            </a>
            <div class="title"><?php echo $this->lang->line('label_profile');?></div>
            <div class="opacity-0 d-none">
                
            </div>
            <a class="" href="<?php echo site_url('account/transaction_history') ?>">

            </a>
        </div>
    </nav>
    <div class="wallet__content container-fluid mt-4">
        <div class="account-layout__container__content__right__content my-profile-page__content">
            <div class="my-profile-page__content__title"><?php echo $this->lang->line('label_personal_profile');?></div>
            <div class="dom-registered">
                <div class="row my-profile-page__content__input mb-3">
                    <label for="inputPassword" class="col-4 col-form-label"><?php echo $this->lang->line('label_username');?></label>
                    <div class="col-6">
                        <input style="min-height: 36px;" type="text" class="form-control" value="<?php echo $player['username'] ?>" id="fr_fullname" data-profile="fullname" disabled="" readonly="">
                    </div>
                </div>
                <?php /*
                <div class="row my-profile-page__content__input">
                    <label for="inputPassword" class="col-4 col-form-label"><?php echo $this->lang->line('label_dob');?></label>
                    <div class="col-6">
                        <?php
                        if ($player['dob'] == null) {
                            echo form_open('ajax/dob', array('id' => 'dob-form', 'autocomplete' => 'off'));
                        ?>
                            <div id="div-dob">
                                <input style="min-height: 36px;" type="text" name="dob" class="form-control" id="dob" readonly>
                            </div>

                        <?php

                        } else { ?>
                            <div id="div-dob">

                                <input style="min-height: 36px;" type="text" class="form-control" value="<?php echo date('d-m-Y', $player['dob']); ?>" readonly disabled>

                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($player['dob'] == null) { ?>
                        <div id="profile-submit-btn" style="">
                            <button type="submit" class="btn btn-primary btn-login"><?php echo $this->lang->line('label_submit')?></button>
                        </div>
                    <?php } ?>
                    <?php echo form_close(); ?>
                </di>
                */ ?>
                <hr>
                <div class="my-profile-page__content__title"><?php echo $this->lang->line('label_account');?><?php echo $this->lang->line('label_nice3_1');?></div>
                <div class="my-profile-page__content__verify">
                    <div>
                        <span><?php echo $this->lang->line('label_mobile');?></span>: <div data-profile="mobile"><?php echo $player['mobile'] ?></div>
                    </div>
                </div>
                <div class="my-profile-page__content__verify">
                    <div>
                        <span><?php echo $this->lang->line('label_email');?></span>: <div data-profile="email"><?php echo $player['email'] ?></div>
                    </div>
                </div>
            </div>
            <div class="my-profile-page__content__verify">
                <div>
                    <span><?php echo $this->lang->line('label_password');?></span>: **********
                </div>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><?php echo $this->lang->line('label_change');?></button>
            </div>
        </div>

    </div>
    <div class="modal fade modal-custom" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title txtupper" id="selectPromotionModalLabel"><?php echo $this->lang->line('label_change');?><?php echo $this->lang->line('label_password');?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body add-bank-modal">
                    <?php echo form_open('ajax/change_password', array('id' => 'change-password-form')); ?> <div class="account-col-wrap clearfix">
                        <div class="row">
                            <label for="staticEmail" class="col-3 form-label"><?php echo $this->lang->line('label_current_password'); ?></label>
                            <div class="col-9">
                                <input type="password" class="form-control" name="oldpass" id="oldpass" />

                            </div>
                        </div>
                        <div class="row">
                            <label for="staticEmail" class="col-3 form-label"><?php echo $this->lang->line('label_new_password'); ?></label>
                            <div class="col-9">
                                <input type="password" class="form-control" name="password" id="password" />


                            </div>
                        </div>
                        <div class="row">
                            <label for="staticEmail" class="col-3 form-label"><?php echo $this->lang->line('label_confirm_new_password'); ?></label>
                            <div class="col-9">
                                <input type="password" class="form-control" name="passconf" id="passconf" />

                            </div>
                        </div>
                        <div class="__submit">
                            <button class="btn btn-primary" type='submit' id="tfButton"><?php echo $this->lang->line('label_submit')?></button>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="__help"><?php echo $this->lang->line('label_please_contact')?><a href="javascript:void(0);" onclick="LC_API.open_chat_window();"><?php echo $this->lang->line('label_customer_service')?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('mobile/parts/footer'); ?>