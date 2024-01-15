<?php $this->load->view('web/parts/header'); ?>
<style>
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: gold;
        background-color: transparent;
    }
    .nav-link {
        color: #5f5f5f;
        text-align: left;
        padding: 0.5rem 2rem;
    }
    .nav-link:focus, .nav-link:hover {
        color: gold;
    }
</style>
<section class="">
	<div class="container py-5 mb-5">
		<div class="row help-page__container__content">
			<div class="col-3 help-page__container__content__left">
               
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <p class="text-white mb-2">
                            <img class="img-left" src="<?php echo base_url('assets/desktop/images/help/user-guide.png') ?>" />
                            <span><?php echo $this->lang->line('label_user_guide') ?></span>
                        </p>
                        <button class="nav-link active" id="v-pills-register-tab" data-bs-toggle="pill" data-bs-target="#v-pills-register" type="button" role="tab" aria-controls="v-pills-register" aria-selected="true">
                            <span><?php echo $this->lang->line('label_register') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-deposit-tab" data-bs-toggle="pill" data-bs-target="#v-pills-deposit" type="button" role="tab" aria-controls="v-pills-deposit" aria-selected="false">
                            <span><?php echo $this->lang->line('label_deposit') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-withdrawal-tab" data-bs-toggle="pill" data-bs-target="#v-pills-withdrawal" type="button" role="tab" aria-controls="v-pills-withdrawal" aria-selected="false">
                            <span><?php echo $this->lang->line('label_withdrawal') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-promotion-tab" data-bs-toggle="pill" data-bs-target="#v-pills-promotion" type="button" role="tab" aria-controls="v-pills-setpromotiontings" aria-selected="false">
                            <span><?php echo $this->lang->line('label_promotion') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-browser-tab" data-bs-toggle="pill" data-bs-target="#v-pills-browser" type="button" role="tab" aria-controls="v-pills-browser" aria-selected="false">
                            <span><?php echo $this->lang->line('label_browser_guide') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-sportbetting-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sportbetting" type="button" role="tab" aria-controls="v-pills-sportbetting" aria-selected="false">
                            <span><?php echo $this->lang->line('label_sport_betting_guide') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-hijacking-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hijacking" type="button" role="tab" aria-controls="v-pills-hijacking" aria-selected="false">
                            <span><?php echo $this->lang->line('label_anti_hijacking_guide') ?></span>
                        </button>
                        <p class="text-white my-2">
                            <img class="img-left" src="<?php echo base_url('assets/desktop/images/help/game-guide.png') ?>" />
                            <span><?php echo $this->lang->line('label_game_guide') ?></span>
                        </p>
                        <button class="nav-link" id="v-pills-lottery-tab" data-bs-toggle="pill" data-bs-target="#v-pills-lottery" type="button" role="tab" aria-controls="v-pills-lottery" aria-selected="true">
                            <span><?php echo $this->lang->line('label_lottery_guide') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-esport-tab" data-bs-toggle="pill" data-bs-target="#v-pills-esport" type="button" role="tab" aria-controls="v-pills-esport" aria-selected="false">
                            <span><?php echo $this->lang->line('label_esport_guide') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-sport-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sport" type="button" role="tab" aria-controls="v-pills-sport" aria-selected="false">
                            <span><?php echo $this->lang->line('label_sport_guide') ?></span>
                        </button>
                        <button class="nav-link" id="v-pills-casino-tab" data-bs-toggle="pill" data-bs-target="#v-pills-casino" type="button" role="tab" aria-controls="v-pills-casino" aria-selected="false">
                            <span><?php echo $this->lang->line('label_casino_guide') ?></span>
                        </button>
                        <p class="text-white my-2">
                            <img class="img-left" src="<?php echo base_url('assets/desktop/images/help/contact-us.png') ?>" />
                            <span><?php echo $this->lang->line('label_contact_us') ?></span>
                        </p>
                        <button class="nav-link" id="v-pills-customer-tab" data-bs-toggle="pill" data-bs-target="#v-pills-customer" type="button" role="tab" aria-controls="v-pills-customer" aria-selected="false">
                            <span><?php echo $this->lang->line('label_customer_service') ?></span>
                        </button>
                    </div>
                </div>
			</div>
			<div class="col-9 help-page__container__content__right">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade text-white show active" id="v-pills-register" role="tabpanel" aria-labelledby="v-pills-register-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_user_guide_1') ?></div>
                                <div class="description bold"><?php echo $this->lang->line('label_user_guide_2') ?>
                                    <ul>
                                        <li><?php echo $this->lang->line('label_user_guide_3') ?></li>
                                        <li><?php echo $this->lang->line('label_user_guide_4') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_user_guide_5') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_user_guide_6') ?>
                                    <a href="javascript:void(0);" onclick="LC_API.open_chat_window();">
                                        <?php echo $this->lang->line('label_customer_service') ?>
                                    </a>
                                    <?php echo $this->lang->line('label_user_guide_7') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_user_guide_8') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_user_guide_9') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_user_guide_10') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_user_guide_11') ?>
                                    <a href="javascript:void(0);" onclick="LC_API.open_chat_window();">
                                        <?php echo $this->lang->line('label_customer_service') ?></a>.
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_user_guide_12') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_user_guide_13') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-deposit" role="tabpanel" aria-labelledby="v-pills-deposit-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_deposit_guide_1') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_deposit_guide_2') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_3') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_4') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_5') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_6') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_7') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_deposit_guide_8') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_deposit_guide_9') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_10') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_11') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_12') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_7') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_deposit_guide_13') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_deposit_guide_14') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_15') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_16') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_17') ?></li>
                                        <li><?php echo $this->lang->line('label_deposit_guide_18') ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-withdrawal" role="tabpanel" aria-labelledby="v-pills-withdrawal-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_1') ?></div>
                                <div class="description bold"><?php echo $this->lang->line('label_withdrawal_guide_2') ?>
                                    <ul>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_3') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_4') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_5') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_6') ?></li>
                                    </ul>
                                </div>
                                <img src="<?php echo base_url('assets/desktop/images/help/Withdrawal-help.png') ?>">
                                <div class="description bold"><?php echo $this->lang->line('label_withdrawal_guide_7') ?>
                                    <ul>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_8') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_9') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_10') ?></li>
                                    </ul>
                                </div>
                                <div class="description bold"><?php echo $this->lang->line('label_withdrawal_guide_11') ?>
                                    <ul>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_12') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_13') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_14') ?></div>
                                <div class="description bold">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_15') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_16') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_17') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_18') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_19') ?><br><?php echo $this->lang->line('label_withdrawal_guide_20') ?><br><?php echo $this->lang->line('label_withdrawal_guide_21') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_22') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_withdrawal_guide_23') ?>
                                    <span> <?php echo $this->lang->line('label_withdrawal_guide_24') ?></span>
                                    <?php echo $this->lang->line('label_withdrawal_guide_25') ?>
                                    <span><?php echo $this->lang->line('label_withdrawal_guide_26') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_27') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_28') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_29') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_30') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_31') ?></li>
                                        <li><?php echo $this->lang->line('label_withdrawal_guide_32') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_33') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_withdrawal_guide_34') ?></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_withdrawal_guide_35') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_withdrawal_guide_36') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-promotion" role="tabpanel" aria-labelledby="v-pills-promotion-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_promotion_guide_1') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_promotion_guide_2') ?></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_promotion_guide_3') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_promotion_guide_4') ?></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"> <?php echo $this->lang->line('label_promotion_guide_5') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_promotion_guide_6') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-browser" role="tabpanel" aria-labelledby="v-pills-browser-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_browser_guide_1') ?></div>
                                <div class="description">
                                    <ul style="list-style: disc;">
                                        <li><?php echo $this->lang->line('label_browser_guide_2') ?></li>
                                        <li><?php echo $this->lang->line('label_browser_guide_3') ?></li>
                                        <li><?php echo $this->lang->line('label_browser_guide_4') ?></li>
                                    </ul>
                                    <?php echo $this->lang->line('label_browser_guide_5') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_browser_guide_6') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_browser_guide_7') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_browser_guide_8') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_browser_guide_9') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-sportbetting" role="tabpanel" aria-labelledby="v-pills-sportbetting-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_bet_guide_1') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_2') ?>
                                    <div><?php echo $this->lang->line('label_sport_bet_guide_3') ?></div>
                                    <span><?php echo $this->lang->line('label_sport_bet_guide_4') ?></span>
                                </div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_5') ?><span><?php echo $this->lang->line('label_sport_bet_guide_6') ?></span>
                                </div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_7') ?>
                                    <span><?php echo $this->lang->line('label_sport_bet_guide_8') ?></span>
                                </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-3.png') ?>"> </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_bet_guide_9') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_10') ?><span><?php echo $this->lang->line('label_sport_bet_guide_11') ?></span></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-4.png') ?>"> </div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_12') ?><span><?php echo $this->lang->line('label_sport_bet_guide_13') ?></span>
                                </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-5.png') ?>"> </div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_14') ?><span><?php echo $this->lang->line('label_sport_bet_guide_15') ?></span> </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-8.png') ?>"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-hijacking" role="tabpanel" aria-labelledby="v-pills-hijacking-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_anti_hijack_guide_1') ?></div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_2') ?></div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_3') ?>
                                    <ul>
                                        <li><?php echo $this->lang->line('label_anti_hijack_guide_4') ?></li>
                                        <li><?php echo $this->lang->line('label_anti_hijack_guide_5') ?></li>
                                    </ul>
                                </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_6') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-1.png') ?>" /> </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_7') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-2.png') ?>" /> </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_8') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-3.png') ?>" /> </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_9') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-4.png') ?>" /> </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_10') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-5.png') ?>" /> </div>
                                <div class="description bold"><?php echo $this->lang->line('label_anti_hijack_guide_11') ?></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/Anti_Hijacking_Guide-6.png') ?>" /> </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade text-white" id="v-pills-lottery" role="tabpanel" aria-labelledby="v-pills-lottery-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_1') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_sport_guide_2') ?></li>
                                        <li><?php echo $this->lang->line('label_sport_guide_3') ?></li>
                                        <li><?php echo $this->lang->line('label_sport_guide_4') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_5') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_sport_guide_6') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_7') ?></div>
                                <div class="description">
                                <?php echo $this->lang->line('label_sport_guide_8') ?> <span><?php echo $this->lang->line('label_sport_guide_9') ?></span>
                                <?php echo $this->lang->line('label_sport_guide_10') ?><span><?php echo $this->lang->line('label_sport_guide_11') ?></span>
                                <?php echo $this->lang->line('label_sport_guide_12') ?><span><?php echo $this->lang->line('label_sport_guide_13') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_14') ?></div>
                                <div class="description">
                                <?php echo $this->lang->line('label_sport_guide_15') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_16') ?></div>
                                <div class="description">
                                    <ul>
                                        <li><?php echo $this->lang->line('label_sport_guide_17') ?></li>
                                        <li><?php echo $this->lang->line('label_sport_guide_18') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_19') ?></div>
                                <div class="description">
                                <?php echo $this->lang->line('label_sport_guide_20') ?>
                                <?php echo $this->lang->line('label_sport_guide_21') ?>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_22') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_sport_guide_23') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_24') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_sport_guide_25') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_guide_26') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_sport_guide_27') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-esport" role="tabpanel" aria-labelledby="v-pills-esport-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_1') ?></div>
                                <div class="description"><span><?php echo $this->lang->line('label_esport_guide_2') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_3') ?></div>
                                <div class="description"><span><?php echo $this->lang->line('label_esport_guide_4') ?></span></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_5') ?></div>
                                <div class="description"><span><?php echo $this->lang->line('label_esport_guide_6') ?></span></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_7') ?></div>
                                <div class="description"><span><?php echo $this->lang->line('label_esport_guide_8') ?></span></div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_9') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_esport_guide_10') ?><span>
                                        <?php echo $this->lang->line('label_esport_guide_11') ?></span>
                                    <?php echo $this->lang->line('label_esport_guide_12') ?><span>
                                        <?php echo $this->lang->line('label_esport_guide_13') ?></span>
                                        <span><?php echo $this->lang->line('label_esport_guide_14') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_15') ?><span>
                                            <?php echo $this->lang->line('label_esport_guide_16') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_esport_guide_17') ?></div>
                                <div class="description">
                                <?php echo $this->lang->line('label_esport_guide_18') ?><span><?php echo $this->lang->line('label_esport_guide_19') ?></span>
                                <?php echo $this->lang->line('label_esport_guide_20') ?> <span><?php echo $this->lang->line('label_esport_guide_21') ?></span>
                                <?php echo $this->lang->line('label_esport_guide_22') ?> <span><?php echo $this->lang->line('label_esport_guide_23') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_24') ?> <span><?php echo $this->lang->line('label_esport_guide_25') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_26') ?> <span><?php echo $this->lang->line('label_esport_guide_27') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_28') ?> <span><?php echo $this->lang->line('label_esport_guide_29') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_30') ?> <span><?php echo $this->lang->line('label_esport_guide_31') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_32') ?> <span><?php echo $this->lang->line('label_esport_guide_33') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_34') ?> <span><?php echo $this->lang->line('label_esport_guide_35') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_36') ?> <span><?php echo $this->lang->line('label_esport_guide_37') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_38') ?> <span><?php echo $this->lang->line('label_esport_guide_39') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_40') ?> <span><?php echo $this->lang->line('label_esport_guide_41') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_42') ?> <span><?php echo $this->lang->line('label_esport_guide_43') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_44') ?> <span><?php echo $this->lang->line('label_esport_guide_45') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_46') ?> <span><?php echo $this->lang->line('label_esport_guide_47') ?></span>
                                        <?php echo $this->lang->line('label_esport_guide_48') ?> <span><?php echo $this->lang->line('label_esport_guide_49') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-sport" role="tabpanel" aria-labelledby="v-pills-sport-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_bet_guide_1') ?></div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_2') ?>
                                    <div><?php echo $this->lang->line('label_sport_bet_guide_3') ?></div>
                                    <span><?php echo $this->lang->line('label_sport_bet_guide_4') ?></span>
                                </div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_5') ?><span><?php echo $this->lang->line('label_sport_bet_guide_6') ?></span>
                                </div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_7') ?>
                                    <span><?php echo $this->lang->line('label_sport_bet_guide_8') ?></span>
                                </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-3.png') ?>"> </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_sport_bet_guide_9') ?></div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_10') ?><span><?php echo $this->lang->line('label_sport_bet_guide_11') ?></span></div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-4.png') ?>"> </div>
                                <div class="description">
                                    <?php echo $this->lang->line('label_sport_bet_guide_12') ?><span><?php echo $this->lang->line('label_sport_bet_guide_13') ?></span>
                                </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-5.png') ?>"> </div>
                                <div class="description"><?php echo $this->lang->line('label_sport_bet_guide_14') ?><span><?php echo $this->lang->line('label_sport_bet_guide_15') ?></span> </div>
                                <div><img src="<?php echo base_url('assets/desktop/images/help/english/sport-betting-guide-8.png') ?>"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="v-pills-casino" role="tabpanel" aria-labelledby="v-pills-casino-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_1') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_2') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_3') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_4') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_5') ?></div>
                                <div class="description">
                                    <ul>
                                    <?php echo $this->lang->line('label_casino_guide_6') ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_7') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_8') ?></span>
                                </div>
                                <?php echo $this->lang->line('label_casino_guide_9') ?>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_10') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_11') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_12') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_13') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_14') ?></div>
                                <div class="description">
                                    <ul>
                                    <?php echo $this->lang->line('label_casino_guide_15') ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_16') ?></div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_17') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_18') ?>
                                </div>
                                <div class="description">
                                    <span><?php echo $this->lang->line('label_casino_guide_19') ?></span>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_casino_guide_20') ?></div>
                                <div class="description"><span><?php echo $this->lang->line('label_casino_guide_21') ?></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade text-white" id="v-pills-customer" role="tabpanel" aria-labelledby="v-pills-customer-tab">
                        <div class="">
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_contact_us_1') ?></div>
                                <div class="description bold">
                                    <a href="#" target="_blank"><?php echo $this->lang->line('label_contact_us_2') ?></a><br />
                                    <a href="#" target="_blank"><?php echo $this->lang->line('label_contact_us_3') ?></a><br />
                                    <a href="#" target="_blank"><?php echo $this->lang->line('label_contact_us_4') ?></a><br />
                                    <a href="#" target="_blank"><?php echo $this->lang->line('label_contact_us_5') ?></a>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_contact_us_6') ?></div>
                                <div class="description bold">
                                    <?php echo $this->lang->line('label_contact_us_7') ?> <a href="mailto:#">dynamite88@gmail.com</a><br />
                                    <?php echo $this->lang->line('label_contact_us_8') ?> <a href="mailto:#">dynamite99@gmail.com</a>
                                </div>
                            </div>
                            <div class="help-page__content__content__items">
                                <div class="title"><?php echo $this->lang->line('label_contact_us_9') ?></div>
                                <div class="description bold">
                                    <?php echo $this->lang->line('label_contact_us_10') ?>
                                    <a href="javascript:void(0);" onclick="">
                                        <?php echo $this->lang->line('label_contact_us_11') ?>
                                    </a> 
                                    <?php echo $this->lang->line('label_contact_us_12') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



				
				
				
				
				
				
				
				
				
				
				
			</div>
		</div>
	</div>
	<script>
		function HelpCtrl() {
			var vm = this;
			vm.init = init;


			let currentPage = 'help_center';
			let previousPage = [];

			function init() {
				setTimeout(function() {
					$('.help-page-list[data-type="register_guide"]').click();
				}, 300);


				$(".help-page-group").click(function() {
					$('.help-page-group').removeClass("active");
					$(this).addClass("active");
					$('.help-page__content__content').hide("fade", {
						direction: "out"
					}, 10);
					$(this).next().find('.list-group-item > .help-page-list').first().click();
				});

				$(".help-page-list").click(function() {
					var type = $(this).attr('data-type');
					$('.help-page-list').removeClass("active");
					$(this).addClass("active");
					$('.help-page__content__content').hide("fade", {
						direction: "out"
					}, 10);
					$('#' + type).show("fade", {
						direction: "in"
					}, 500);
					$("html, body").animate({
						scrollTop: 0
					}, "fast");
				});
			}
		}
		_fn.help = HelpCtrl;
	</script>
</section>
<?php $this->load->view('web/parts/footer'); ?>