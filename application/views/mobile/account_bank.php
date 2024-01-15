<?php $this->load->view('mobile/parts/header'); ?>

<section class="main withdrawal manage-bank-account">
    <nav class="navbar fixed-top navbar-light nav-child">
        <div class="container-fluid content ">
            <a id="back_url" class="" href="javascript:history.back();">
                <img src="<?php echo base_url('assets/mobile/img/user_center/mywallet_header_icon_back.png'); ?>" alt="">
            </a>
            <div class="title"><?php echo $this->lang->line('label_bank_account') ?></div>
            <div class="opacity-0 ">
                <img src="obv1/m/images/icons/help.png" alt="">
            </div>
        </div>
    </nav>


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
                            <button type="submit" id="submit2" name="submit" class="btn btn-primary"><?php echo $this->lang->line('label_submit') ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
    <div class="withdrawal__content container-fluid" id="manage-have-bank">
        <div class="withdrawal__content__bank-account">

            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addBankModal" class="withdrawal__content__bank-account__add-bank p-0">
                <img src="https://0bspoob66s.cloudcdnetw.com/obv1/m/images/wallet/plus.png"> <span><?php echo $this->lang->line('label_add_bank_account') ?></span>
            </a>
        </div>
        <?php if (isset($player_cash_bank) && (!empty($player_cash_bank))) { ?>
            <div class=" manage-bank-account-page__content__title mt-3">
                <div class="title px-2"><?php echo $this->lang->line('label_bank_account'); ?></div>
            </div>
            <?php foreach ($player_cash_bank as $bank) { ?>
                <div class="text-white border p-2">
                    <div class="p-2">
                        <div>
                            <span><?php echo $this->lang->line('label_bank_account_no'); ?></span>: <?php echo $bank['bank_account_no'] ?>
                        </div>
                    </div>
                    <div class="p-2">
                        <div>
                            <span><?php echo $this->lang->line('label_bank_account_name'); ?></span>: <?php echo $bank['bank_account_name'] ?>
                        </div>
                    </div>
                    <div class="p-2">
                        <div>
                            <span><?php echo $this->lang->line('label_bank_name'); ?></span>: <?php echo $bank['bank_name'] ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</section>
<!-- End Content -->
<?php $this->load->view('mobile/parts/footer'); ?>