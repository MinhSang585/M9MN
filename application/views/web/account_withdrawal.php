<?php $this->load->view('web/parts/header'); ?>

<section class="">
    <div id="liveAlertPlaceholder"></div>

    <div class="container py-5 mb-5">
        <div class="row account-layout__container__content">
            <?php $this->load->view('web/parts/wallet'); ?>
            <div class="col-9 account-layout__container__content__right">
                <div class="account-layout__container__content__right__content withdrawal-page__content">
                    <div class="my-profile-page__content__title"><?php echo $this->lang->line('label_withdrawal'); ?></div>
                    <?php echo form_open('ajax/withdrawal', array('id' => 'withdrawal-form')); ?>

                    <div class="withdrawal-page__content__input row" id="nBankAcc5">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_currency'); ?></label>
                        <div class="col-9">
                            <select class="form-control" name="currency_id" id="currency_id" onchange="calculateCurrencyRate(this.value,<?php echo TRANSACTION_TYPE_WITHDRAWAL; ?>);">
                                <option value=""><?php echo $this->lang->line('label_please_select'); ?></option>
                                <?php if ($this->session->userdata('player_type') == PLAYER_TYPE_MG_MARKET) { ?>
                                    <option value="1"><?php echo $this->lang->line('label_trust_world_select'); ?></option> <?php } ?>
                                    <?php if (isset($currencies)) {
                                        for ($i = 0; $i < sizeof($currencies); $i++) {
                                            echo '<option value="' . $currencies[$i]['currency_id'] . '">' . $currencies[$i]['currency_code'] . '</option>';
                                        }
                                    } ?>
                            </select>
                        </div>
                    </div>

                    <div class="withdrawal-page__content__input row" id="nBankAcc1">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_withdrawal_method'); ?></label>
                        <div class="col-9">
                            <select name="player_bank_id" id="player_bank_id" class="form-control">
                                <option value="" class="player_bank_class"><?php echo $this->lang->line('label_please_select'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="withdrawal-page__content__input row" id="nBankAcc3" style="display: none;">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_bank_account_name'); ?></label>
                        <div class="col-9">
                            <input type="text" class="form-control" disabled name="bank_account_name" id="bank_account_name">
                        </div>
                    </div>

                    <div class="withdrawal-page__content__input row" id="nBankAcc4" style="display: none;">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_bank_account_no'); ?></label>
                        <div class="col-9">
                            <input class="form-control" type="text" disabled id="bank_account_no" name="bank_account_no">
                        </div>
                    </div>

                    <div class="withdrawal-page__content__input row" id="nBankAcc6" style="display: none;">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_bank_account_address'); ?></label>
                        <div class="col-9">
                            <input class="form-control" type="text" disabled id="bank_account_address" name="bank_account_address">
                        </div>
                    </div>
                    <div class="withdrawal-page__content__input row">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_amount'); ?></label>
                        <div class="col-9">
                            <input class="form-control" type="number" id="amount" name="amount" onkeyup="calculateActualAmount(this.value)">
                        </div>
                    </div>
                    <div class="withdrawal-page__content__input row" id="nBankAcc7" style="display: none;">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_currency_rate'); ?></label>
                        <div class="col-9">
                            <input type="text" class="form-control" disabled id="currency_rate">
                        </div>
                    </div>
                    <div class="withdrawal-page__content__input row" id="nBankAcc8" style="display: none;">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_actual_amount'); ?></label>
                        <div class="col-9">
                            <input type="text" class="form-control" disabled id="actual_amount">
                        </div>
                    </div>
                    <div class="withdrawal-page__content__input row" id="nBankAcc5">
                        <label for="" class="col-3 input-group-text"><?php echo $this->lang->line('label_password'); ?></label>
                        <div class="col-9">
                            <input class="form-control" type="password" name="password">
                            <a href="<?php echo base_url('account') ?>">
                                <div class="text-danger text-small"><?php echo $this->lang->line('label_binding_bank_before_withdrawal'); ?></div>
                            </a>
                        </div>
                    </div>
                    <div class="card bg-transparent text-white border mt-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $this->lang->line('label_tips'); ?></h6>
                            <p class="card-text mb-3"><?php echo $this->lang->line('label_withdraw_notice_1'); ?></p>
                            <i style="color: red;"><?php echo $this->lang->line('label_withdraw_notice_2'); ?></i>
                        </div>
                    </div>
                    <div class="withdrawal-page__content__submit text-center">
                        <button class="btn btn-primary withdrawal-submit"><?php echo $this->lang->line('label_submit'); ?></button>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

</section>


<?php $this->load->view('web/parts/footer'); ?>
