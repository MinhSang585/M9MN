<?php $this->load->view('web/parts/header'); ?>
<section class="">
    <div id="liveAlertPlaceholder"></div>

    <div class="container py-5 mb-5">
        <div class="row account-layout__container__content">
            <?php $this->load->view('web/parts/wallet'); ?>
            
            <div class="col-9 account-layout__container__content__right">
                <div class="account-layout__container__content__right__content manage-bank-account-page__content">
                <div class="my-profile-page__content__title"><?php echo $this->lang->line('label_add_bank_account'); ?></div>
                    <div class="withdrawal-page__content__bank-account">
                        <div class="withdrawal-page__content__bank-account__label"><?php echo $this->lang->line('label_bank_account') ?></div>
                        <div class="withdrawal-page__content__bank-account__container row" id="bankAccount">

                            <div class="col-2">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addBankModal" class="withdrawal-page__content__bank-account__container__item">
                                    <div class="withdrawal-page__content__bank-account__container__item__img add">
                                        +
                                    </div>
                                    <div class="withdrawal-page__content__bank-account__container__item__text"><?php echo $this->lang->line('label_add_bank_account') ?></div>
                                </a>
                            </div>
                        </div>

                    </div>
                    <?php if (isset($player_cash_bank) && (!empty($player_cash_bank))) { ?>
                        <div class=" manage-bank-account-page__content__title mt-3">
                            <div class="title"><?php echo $this->lang->line('label_bank_account'); ?></div>
                        </div>
                        <?php foreach ($player_cash_bank as $playerBank) { ?>
                            <div class="text-white border p-2">
                                <div class="p-2">
                                    <div>
                                        <span><?php echo $this->lang->line('label_bank_account_no'); ?></span>: <?php echo $playerBank['bank_account_no'] ?? $playerBank['bank_account_address'] ?>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <div>
                                        <span><?php echo $this->lang->line('label_bank_account_name'); ?></span>: <?php echo $playerBank['bank_account_name'] ?>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <div>
                                        <span><?php echo $this->lang->line('label_bank_name'); ?></span>: <?php echo $playerBank['bank_name'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

            </div>
            <div class="modal fade modal-custom" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <?php echo form_open('ajax/binding_bank', array('id' => 'player-bank-form')); ?>
                        <div class="modal-header">
                            <h5 class="modal-title" id="selectPromotionModalLabel"><?php echo $this->lang->line('label_add_bank_account') ?></h5>
                            <button type="button" id="" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body add-bank-modal">
                            <div class="row">
                                <label class="col-3 form-label" for=""> <?php echo $this->lang->line('label_bank_name'); ?> </label>
                                <div class="col-9">
                                    <select class="form-select" name="bank_id" id="bank_id">
                                        <option value=""> <?php echo $this->lang->line('label_please_select'); ?> </option> <?php for ($i = 0; $i
                                                                                                                                < sizeof($bank); $i++) {
                                                                                                                                echo '
                                                                            <option value="' . $bank[$i]['bank_id'] . '">' . $bank[$i]['bank_name'] . '</option>';
                                                                                                                            }                          ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3 form-label" for=""> <?php echo $this->lang->line('label_bank_account_name'); ?> </label>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="bank_account_name" id="bank_account_name" value="<?php echo $player['full_name']; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3 form-label" for=""> <?php echo $this->lang->line('label_bank_account_no'); ?> </label>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="bank_account_no" id="bank_account_no">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3 form-label" for=""> <?php echo $this->lang->line('label_password'); ?> </label>
                                <div class="col-9">
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="__submit">
                                <div class="__submit">
                                    <button type="submit" id="submit2" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- End Content -->
<?php $this->load->view('web/parts/footer'); ?>
