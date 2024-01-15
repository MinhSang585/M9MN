<?php $this->load->view('web/parts/header'); ?>
<section class="main forget-password-page">
    <div id="liveAlertPlaceholder"></div>

    <div class="forget-password-page__container container">
        <div class="forget-password-page__container__content row">
            <div class="col-6">
                <?php echo form_open('ajax/forgot_password', array('id' => 'forgot-password-form', 'autocomplete' => 'off')); ?>
                <div class="right-cont">
                    <div>
                        <div class="js-way-cont way-cont">
                            <div class="forget-password-page__container__content__title">
                                <h3><?php echo $this->lang->line('label_forgot_password_title'); ?></h3>
                            </div>

                            <div class="register-list active">
                                <div class="form-group">
                                <label class="form-label"><?php echo $this->lang->line('label_username'); ?></label>
                                    <input type="text" value="" name="username" id="username" maxlength="64" placeholder="<?php echo $this->lang->line('label_username'); ?>" class="form-control">
                                </div>

                                <div class="form-group">
                                <label class="form-label"><?php echo $this->lang->line('label_mobile'); ?></label>
                                    <input type="text" value="" name="mobile" id="mobile" maxlength="32" placeholder="<?php echo $this->lang->line('label_customer_phone_number'); ?>" class="form-control">
                                </div>
                                <?php /*
									<div class="position-relative mt-10">
                                        <input type="text" value="" name="email" id="email" maxlength="64" placeholder="<?php echo $this->lang->line('label_email_address');?>" class="r_form_input ui-style ui-input">										
                                    </div>
									*/ ?>
                                <div class="forget-password-page__container__content__button">
                                    <button class="btn btn-primary btn-next" type="submit"><?php echo $this->lang->line('label_submit')?></button>
                                    <div class="loading" style="display:none;text-align:center;"><img src="obv1/images/loading.gif" align="center"> </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</section>
<?php $this->load->view('web/parts/footer'); ?>