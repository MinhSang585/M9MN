
<script type="text/javascript">
    var _fn = {};
    var _ctrl = null;
</script> 

<?php $this->load->view('mobile/parts/header'); ?>
<section id="help_center" class="main help-page help-index">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content">
			<a id="back_url" class="" href="javascript:history.back();">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" />
			</a>
			<div class="title text-uppercase"><?php echo $this->lang->line('label_help_center') ?></div>
			<a class="" href="#">
				<img src="<?php echo base_url('assets/mobile/img/service.png') ?>" />
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__top">
			<div class="help-page__content__top__content">
				<div class="help-page__content__top__content__img">
					<img src="<?php echo base_url('assets/mobile/img/help-avatar.png') ?>" />
				</div>
				<div class="help-page__content__top__content__text">
					<h3><?php echo $this->lang->line('label_help_center_content_01') ?></h3>
					<div><?php echo $this->lang->line('label_help_center_content_02') ?></div>
				</div>
			</div>
			<div class="help-page__content__top__subtext"><?php echo $this->lang->line('label_help_center_content_03') ?></div>
			<div class="help-page__content__top__bg"></div>
		</div>
		<div class="help-page__content__content">
			<ul class="list-group">
				<li class="list-group-item">
					<a href="javascript: void(0);" onclick="_ctrl.cs_go_to('user_guide');">
						<img class="img-left" src="<?php echo base_url('assets/desktop/images/help/user-guide.png') ?>" />
						<span><?php echo $this->lang->line('label_user_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript: void(0);" onclick="_ctrl.cs_go_to('gameplay_tutorial');">
						<img class="img-left" src="<?php echo base_url('assets/desktop/images/help/game-guide.png') ?>" />
						<span><?php echo $this->lang->line('label_game_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript: void(0);" onclick="_ctrl.cs_go_to('contact_us');">
						<img class="img-left" src="<?php echo base_url('assets/desktop/images/help/contact-us.png') ?>" />
						<span><?php echo $this->lang->line('label_contact_us') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
			</ul>
		</div>
		<div class="d-none help-page__content__footer">Need assistance? Please contact <a href="javascript:void(0);" onclick="LC_API.open_chat_window();">Customer Service</a></div>
	</div>
</section>
<section id="user_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" />
			</a>
			<div class="title" data-i18n="help_page.NEW_USER_GUIDE"><?php echo $this->lang->line('label_new_user_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<ul class="list-group">
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('register_guide');">
						<span data-i18n="help_page.Register"><?php echo $this->lang->line('label_register') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('deposit_guide');">
						<span data-i18n="help_page.Deposit"><?php echo $this->lang->line('label_deposit') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('withdrawal_guide');">
						<span data-i18n="help_page.Withdrawal"><?php echo $this->lang->line('label_withdrawal') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('promotion_guide');">
						<span data-i18n="help_page.Promotion"><?php echo $this->lang->line('label_promotion') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('browser_guide');">
						<span data-i18n="help_page.Browser_Guide"><?php echo $this->lang->line('label_browser_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('sport_bet_guide');">
						<span data-i18n="help_page.Sport_Betting_Guide"><?php echo $this->lang->line('label_sport_betting_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('anti_hijacking_guide');">
						<span data-i18n="help_page.Anti_Hijacking_Guide"><?php echo $this->lang->line('label_anti_hijacking_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
			</ul>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="gameplay_tutorial" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.GAME_GUIDE"><?php echo $this->lang->line('label_game_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<ul class="list-group">
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('lottery_guide');">
						<span data-i18n="help_page.Lottery_Guide"><?php echo $this->lang->line('label_lottery_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('sport_guide');">
						<span data-i18n="help_page.Sport_Guide"><?php echo $this->lang->line('label_sport_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('casino_guide');">
						<span data-i18n="help_page.Live_Casino_Guide"><?php echo $this->lang->line('label_casino_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:void(0);" onclick="_ctrl.cs_go_to('esport_guide');">
						<span data-i18n="help_page.E_sport_Guide"><?php echo $this->lang->line('label_esport_guide') ?></span>
						<img class="right" src="<?php echo base_url('assets/mobile/img/right.png') ?>">
					</a>
				</li>
			</ul>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="register_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Register"><?php echo $this->lang->line('label_register') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.register_page.1"><?php echo $this->lang->line('label_user_guide_1') ?></div>
				<div class="description" data-i18n="help_page.register_page.des1"><?php echo $this->lang->line('label_user_guide_2') ?><ul>
						<li><?php echo $this->lang->line('label_user_guide_3') ?></li>
						<li><?php echo $this->lang->line('label_user_guide_4') ?></li>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.register_page.2"><?php echo $this->lang->line('label_user_guide_5') ?></div>
				<div class="description" data-i18n="help_page.register_page.des2"><?php echo $this->lang->line('label_user_guide_6') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.register_page.3"><?php echo $this->lang->line('label_user_guide_7') ?></div>
				<div class="description" data-i18n="help_page.register_page.des3"><?php echo $this->lang->line('label_user_guide_8') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.register_page.4"><?php echo $this->lang->line('label_user_guide_9') ?></div>
				<div class="description" data-i18n="help_page.register_page.des4"><?php echo $this->lang->line('label_user_guide_10') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.register_page.5"><?php echo $this->lang->line('label_user_guide_11') ?></div>
				<div class="description" data-i18n="help_page.register_page.des5"><?php echo $this->lang->line('label_user_guide_12') ?></div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="deposit_guide" class="main help-page" style="display: none;">

	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Deposit"><?php echo $this->lang->line('label_deposit') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.deposit_page.1"><?php echo $this->lang->line('label_deposit_guide_1') ?></div>
				<div class="description" data-i18n="help_page.deposit_page.des1">
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
				<div class="title" data-i18n="help_page.deposit_page.2"><?php echo $this->lang->line('label_deposit_guide_8') ?></div>
				<div class="description" data-i18n="help_page.deposit_page.des2">
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
				<div class="title" data-i18n="help_page.deposit_page.3"><?php echo $this->lang->line('label_deposit_guide_13') ?></div>
				<div class="description" data-i18n="help_page.deposit_page.des3">
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
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="withdrawal_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Withdrawal"><?php echo $this->lang->line('label_withdrawal') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Withdrawal_page.1"><?php echo $this->lang->line('label_withdrawal_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des1"><?php echo $this->lang->line('label_withdrawal_guide_2') ?><ul>
						<li><?php echo $this->lang->line('label_withdrawal_guide_3') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_4') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_5') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_6') ?></li>
					</ul>
				</div>
				<img src="<?php echo base_url('assets/mobile/img/Withdrawal-help.png') ?>" alt="">
				<div class="description" data-i18n="help_page.Withdrawal_page.des11"><?php echo $this->lang->line('label_withdrawal_guide_7') ?>
					<ul>
						<li><?php echo $this->lang->line('label_withdrawal_guide_8') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_9') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_10') ?></li>
					</ul>
				</div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des12"><?php echo $this->lang->line('label_withdrawal_guide_11') ?>
					<ul>
						<li><?php echo $this->lang->line('label_withdrawal_guide_12') ?></li>
						<li><?php echo $this->lang->line('label_withdrawal_guide_13') ?></li>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Withdrawal_page.2"><?php echo $this->lang->line('label_withdrawal_guide_14') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des2">
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
				<div class="title" data-i18n="help_page.Withdrawal_page.3"><?php echo $this->lang->line('label_withdrawal_guide_22') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des3"><?php echo $this->lang->line('label_withdrawal_guide_23') ?>
					<span><?php echo $this->lang->line('label_withdrawal_guide_24') ?></span>
					<?php echo $this->lang->line('label_withdrawal_guide_25') ?>
					<span><?php echo $this->lang->line('label_withdrawal_guide_26') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Withdrawal_page.4"><?php echo $this->lang->line('label_withdrawal_guide_27') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des4">
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
				<div class="title" data-i18n="help_page.Withdrawal_page.5"><?php echo $this->lang->line('label_withdrawal_guide_33') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des5"><?php echo $this->lang->line('label_withdrawal_guide_34') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Withdrawal_page.6"><?php echo $this->lang->line('label_withdrawal_guide_35') ?></div>
				<div class="description" data-i18n="help_page.Withdrawal_page.des6"><?php echo $this->lang->line('label_withdrawal_guide_36') ?></div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="promotion_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/right.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Promotion"><?php echo $this->lang->line('label_promotion') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.promotion_page.1"><?php echo $this->lang->line('label_promotion_guide_1') ?></div>
				<div class="description" data-i18n="help_page.promotion_page.des1"><?php echo $this->lang->line('label_promotion_guide_2') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.promotion_page.2"><?php echo $this->lang->line('label_promotion_guide_3') ?></div>
				<div class="description" data-i18n="help_page.promotion_page.des2"><?php echo $this->lang->line('label_promotion_guide_4') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.promotion_page.3"><?php echo $this->lang->line('label_promotion_guide_5') ?></div>
				<div class="description" data-i18n="help_page.promotion_page.des3"><?php echo $this->lang->line('label_promotion_guide_6') ?></div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="browser_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/right.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Browser_Guide"><?php echo $this->lang->line('label_browser_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Browser_Guide_page.1"><?php echo $this->lang->line('label_browser_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Browser_Guide_page.des1">
					<ul style="list-style: disc;">
						<li><?php echo $this->lang->line('label_browser_guide_2') ?></li>
						<li><?php echo $this->lang->line('label_browser_guide_3') ?></li>
						<li><?php echo $this->lang->line('label_browser_guide_4') ?></li>
					</ul>
					<?php echo $this->lang->line('label_browser_guide_5') ?>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Browser_Guide_page.2"><?php echo $this->lang->line('label_browser_guide_6') ?></div>
				<div class="description" data-i18n="help_page.Browser_Guide_page.des2"><?php echo $this->lang->line('label_browser_guide_7') ?></div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Browser_Guide_page.3"><?php echo $this->lang->line('label_browser_guide_8') ?></div>
				<div class="description" data-i18n="help_page.Browser_Guide_page.des3"><?php echo $this->lang->line('label_browser_guide_9') ?></div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="sport_bet_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" class="" href="javascript:void(0);">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>
			<div class="title"><?php echo $this->lang->line('label_sport_betting_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div	 class="title"><?php echo $this->lang->line('label_sport_bet_guide_1') ?></div>
				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_2') ?>
					<div><?php echo $this->lang->line('label_sport_bet_guide_3') ?></div>
					<span><?php echo $this->lang->line('label_sport_bet_guide_4') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img1">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-1.png') ?>" alt="">
				</div>

				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_5') ?>
					<span><?php echo $this->lang->line('label_sport_bet_guide_6') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img2">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-2.png') ?>" alt="">
				</div>
				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_7') ?>
					<span><?php echo $this->lang->line('label_sport_bet_guide_8') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img3">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-3.png') ?>" alt="">
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title"><?php echo $this->lang->line('label_sport_bet_guide_9') ?></div>
				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_10') ?>
					<span><?php echo $this->lang->line('label_sport_bet_guide_11') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img4">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-4.png') ?>" alt="">
				</div>

				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_12') ?>
					<span><?php echo $this->lang->line('label_sport_bet_guide_13') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img5">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-5.png') ?>" alt="">
				</div>

				<div data-i18n="Sport_Betting_Guide_page_img6">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-6.png') ?>" alt="">
				</div>

				<div class="description">
					<?php echo $this->lang->line('label_sport_bet_guide_14') ?>	
					<span><?php echo $this->lang->line('label_sport_bet_guide_15') ?></span>
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img7">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-7.png') ?>" alt="">
				</div>
				<div data-i18n="Sport_Betting_Guide_page_img8">
					<img src="<?php echo base_url('assets/mobile/img/sport-betting-guide-8.png') ?>" alt="">
				</div>
			</div>
		</div>
		<div class="d-none  our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="anti_hijacking_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javascript:void(0)">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Anti_Hijacking_Guide"><?php echo $this->lang->line('label_anti_hijacking_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Anti_Hijacking_Guide_page.1"><?php echo $this->lang->line('label_anti_hijack_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Anti_Hijacking_Guide_page.des1">
					<ul>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m2') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m3') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m4') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m5') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m6') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m7') ?></li>
					</ul>
					<?php echo $this->lang->line('label_anti_hijack_guide_m8') ?>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Anti_Hijacking_Guide_page.2"><?php echo $this->lang->line('label_anti_hijack_guide_m9') ?></div>
				<div class="description" data-i18n="help_page.Anti_Hijacking_Guide_page.des2">
					<ul>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m10') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m11') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m12') ?></li>
						<li><?php echo $this->lang->line('label_anti_hijack_guide_m13') ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="lottery_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javscript:void(0);">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Lottery_Guide"><?php echo $this->lang->line('label_lottery_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Lottery_Guide_page.1"><?php echo $this->lang->line('label_anti_lottery_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Lottery_Guide_page.des1">
					<ul>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_2') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_3') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_4') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_5') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_6') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_7') ?></li>
					</ul>
				</div>
				<img src="<?php echo base_url('assets/mobile/img/lottery-guide-1.png') ?>" alt="">
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Lottery_Guide_page.2"><?php echo $this->lang->line('label_anti_lottery_guide_8') ?></div>
				<div class="description" data-i18n="help_page.Lottery_Guide_page.des2">
					<ul>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_9') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_10') ?></li>
					</ul>
				</div>
				<div data-i18n="help_page.Lottery_Guide_page.img2">
					<img src="<?php echo base_url('assets/mobile/img/lottery-guide-2.png') ?>" alt="">
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Lottery_Guide_page.3"><?php echo $this->lang->line('label_anti_lottery_guide_11') ?></div>
				<div class="description" data-i18n="help_page.Lottery_Guide_page.des3">
					<ul>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_12') ?></li>
						<li><?php echo $this->lang->line('label_anti_lottery_guide_13') ?></li>
					</ul>
				</div>
				<div data-i18n="help_page.Lottery_Guide_page.img3">
					<img src="<?php echo base_url('assets/mobile/img/lottery-guide-3.png') ?>" alt="">
				</div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="esport_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javscript:void(0);">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.E_sport_Guide"><?php echo $this->lang->line('label_esport_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.1"><?php echo $this->lang->line('label_esport_guide_1') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des1">
					<span><?php echo $this->lang->line('label_esport_guide_2') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.2"><?php echo $this->lang->line('label_esport_guide_3') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des2">
					<span><?php echo $this->lang->line('label_esport_guide_4') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.3"><?php echo $this->lang->line('label_esport_guide_5') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des3">
					<span><?php echo $this->lang->line('label_esport_guide_6') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.4"><?php echo $this->lang->line('label_esport_guide_7') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des4">
					<span><?php echo $this->lang->line('label_esport_guide_8') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.5"><?php echo $this->lang->line('label_esport_guide_9') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des5"><?php echo $this->lang->line('label_esport_guide_10') ?>
					<span><?php echo $this->lang->line('label_esport_guide_11') ?></span>
					<?php echo $this->lang->line('label_esport_guide_12') ?>
					<span><?php echo $this->lang->line('label_esport_guide_13') ?></span>
					<span><?php echo $this->lang->line('label_esport_guide_14') ?></span>
					<?php echo $this->lang->line('label_esport_guide_15') ?>
					<span><?php echo $this->lang->line('label_esport_guide_16') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.E_sport_Guide_page.6"><?php echo $this->lang->line('label_esport_guide_17') ?></div>
				<div class="description" data-i18n="help_page.E_sport_Guide_page.des6">
					<?php echo $this->lang->line('label_esport_guide_18') ?>
					<span><?php echo $this->lang->line('label_esport_guide_19') ?></span>
					<?php echo $this->lang->line('label_esport_guide_20') ?>
					<span><?php echo $this->lang->line('label_esport_guide_21') ?></span>
					<?php echo $this->lang->line('label_esport_guide_22') ?>
					<span><?php echo $this->lang->line('label_esport_guide_23') ?></span>
					<?php echo $this->lang->line('label_esport_guide_24') ?>
					<span><?php echo $this->lang->line('label_esport_guide_25') ?></span>
					<?php echo $this->lang->line('label_esport_guide_26') ?>
					<span><?php echo $this->lang->line('label_esport_guide_27') ?></span>
					<?php echo $this->lang->line('label_esport_guide_28') ?>
					<span><?php echo $this->lang->line('label_esport_guide_29') ?></span>
					<?php echo $this->lang->line('label_esport_guide_30') ?>
					<span><?php echo $this->lang->line('label_esport_guide_31') ?></span>
					<?php echo $this->lang->line('label_esport_guide_32') ?>
					<span><?php echo $this->lang->line('label_esport_guide_33') ?></span>
					<?php echo $this->lang->line('label_esport_guide_34') ?>
					<span><?php echo $this->lang->line('label_esport_guide_35') ?></span>
					<?php echo $this->lang->line('label_esport_guide_36') ?>
					<span><?php echo $this->lang->line('label_esport_guide_37') ?></span>
					<?php echo $this->lang->line('label_esport_guide_38') ?>
					<span><?php echo $this->lang->line('label_esport_guide_39') ?></span>
					<?php echo $this->lang->line('label_esport_guide_40') ?>
					<span><?php echo $this->lang->line('label_esport_guide_41') ?></span>
					<?php echo $this->lang->line('label_esport_guide_42') ?>
					<span><?php echo $this->lang->line('label_esport_guide_43') ?></span>
					<?php echo $this->lang->line('label_esport_guide_44') ?>
					<span><?php echo $this->lang->line('label_esport_guide_45') ?></span>
					<?php echo $this->lang->line('label_esport_guide_46') ?>
					<span><?php echo $this->lang->line('label_esport_guide_47') ?></span>
					<?php echo $this->lang->line('label_esport_guide_48') ?>
					<span><?php echo $this->lang->line('label_esport_guide_49') ?></span>
				</div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="sport_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javscript:void(0);">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>

			<div class="title" data-i18n="help_page.Sport_Guide"><?php echo $this->lang->line('label_sport_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.1"><?php echo $this->lang->line('label_sport_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des1">
					<ul>
						<li><?php echo $this->lang->line('label_sport_guide_2') ?></li>
						<li><?php echo $this->lang->line('label_sport_guide_3') ?></li>
						<li><?php echo $this->lang->line('label_sport_guide_4') ?></li>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.2"><?php echo $this->lang->line('label_sport_guide_5') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des2">
					<span><?php echo $this->lang->line('label_sport_guide_6') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.3"><?php echo $this->lang->line('label_sport_guide_7') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des3">
					<?php echo $this->lang->line('label_sport_guide_8') ?>
				 	<span><?php echo $this->lang->line('label_sport_guide_9') ?></span>
					 <?php echo $this->lang->line('label_sport_guide_10') ?>
					 <span><?php echo $this->lang->line('label_sport_guide_11') ?></span>
					 <?php echo $this->lang->line('label_sport_guide_12') ?>
					 <span><?php echo $this->lang->line('label_sport_guide_13') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.4">
					<?php echo $this->lang->line('label_sport_guide_14') ?>
				</div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des4">
					<?php echo $this->lang->line('label_sport_guide_15') ?>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.5"><?php echo $this->lang->line('label_sport_guide_16') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des5">
					<ul>
						<li><?php echo $this->lang->line('label_sport_guide_17') ?></li>
						<li><?php echo $this->lang->line('label_sport_guide_18') ?></li>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.6"><?php echo $this->lang->line('label_sport_guide_19') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des6">
					<?php echo $this->lang->line('label_sport_guide_20') ?>
					<?php echo $this->lang->line('label_sport_guide_21') ?>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.7"><?php echo $this->lang->line('label_sport_guide_22') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des7">
					<span><?php echo $this->lang->line('label_sport_guide_23') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.8"><?php echo $this->lang->line('label_sport_guide_24') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des8">
					<span><?php echo $this->lang->line('label_sport_guide_25') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Sport_Guide_page.9"><?php echo $this->lang->line('label_sport_guide_26') ?></div>
				<div class="description" data-i18n="help_page.Sport_Guide_page.des9">
					<span><?php echo $this->lang->line('label_sport_guide_27') ?></span>
				</div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<section id="casino_guide" class="main help-page" style="display: none;">
	<nav class="navbar fixed-top navbar-light nav-child">
		<div class="container-fluid content ">
			<a class="back_url" href="javscript:void(0);">
				<img src="<?php echo base_url('assets/mobile/img/icon_back_white.png') ?>" alt="">
			</a>
			<div class="title" data-i18n="help_page.Live_Casino_Guide"><?php echo $this->lang->line('label_casino_guide') ?></div>
			<div class="opacity-0 d-none">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="">
			</div>
			<a class="/customer-service.html" href="customer-service">
				<img src="<?php echo base_url('assets/mobile/img/help.png') ?>" alt="" class="me-1">
			</a>
		</div>
	</nav>
	<div class="help-page__content">
		<div class="help-page__content__content">
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.1"><?php echo $this->lang->line('label_casino_guide_1') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des1">
					<span><?php echo $this->lang->line('label_casino_guide_2') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.2"><?php echo $this->lang->line('label_casino_guide_3') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des2">
					<span><?php echo $this->lang->line('label_casino_guide_4') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.3"><?php echo $this->lang->line('label_casino_guide_5') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des3">
					<ul>
						<?php echo $this->lang->line('label_casino_guide_6') ?>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.4"><?php echo $this->lang->line('label_casino_guide_7') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des4">
					<span><?php echo $this->lang->line('label_casino_guide_8') ?></span>
				</div>
				<?php echo $this->lang->line('label_casino_guide_9') ?>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.5"><?php echo $this->lang->line('label_casino_guide_10') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des5">
					<span><?php echo $this->lang->line('label_casino_guide_11') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.6"><?php echo $this->lang->line('label_casino_guide_12') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des6">
					<span><?php echo $this->lang->line('label_casino_guide_13') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.7"><?php echo $this->lang->line('label_casino_guide_14') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des7">
					<ul>
						<?php echo $this->lang->line('label_casino_guide_15') ?>
					</ul>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.8"><?php echo $this->lang->line('label_casino_guide_16') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des8">
					<span><?php echo $this->lang->line('label_casino_guide_17') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.9"><?php echo $this->lang->line('label_casino_guide_18') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des9">
					<span><?php echo $this->lang->line('label_casino_guide_19') ?></span>
				</div>
			</div>
			<div class="help-page__content__content__items">
				<div class="title" data-i18n="help_page.Live_Casino_Guide_page.10"><?php echo $this->lang->line('label_casino_guide_20') ?></div>
				<div class="description" data-i18n="help_page.Live_Casino_Guide_page.des10">
					<span><?php echo $this->lang->line('label_casino_guide_21') ?></span>
				</div>
			</div>
		</div>
		<div class="d-none help-page__content__footer" data-i18n="help_page.Need_assitance">Need assistance? Please contact our <a href="customer-service">Customer Service</a></div>
	</div>
</section>
<script>
	function HelpCtrl() {
		var vm = this;
		vm.init = init;

		vm.cs_go_to = cs_go_to;

		let currentPage = 'help_center';
		let previousPage = [];

		function init() {
			_registerClickEvent();
		}

		function cs_go_to(target) {
			$('#' + currentPage).hide("slide", {
				direction: "left"
			}, 500);
			$('#' + target).show("slide", {
				direction: "right"
			}, 500);
			$("html, body").animate({
				scrollTop: 0
			}, "fast");
			previousPage.push(currentPage);
			currentPage = target;
		}

		function _registerClickEvent() {
			$('.back_url').off('click').click(function() {
				let target = previousPage.pop();
				$('#' + currentPage).hide("slide", {
					direction: "right"
				}, 500);
				$('#' + target).show("slide", {
					direction: "left"
				}, 500);
				currentPage = target;
			});
		}
	}
	_fn.help = HelpCtrl;
</script>

<?php $this->load->view('mobile/parts/footer'); ?>

<script type="text/javascript">
	var _p = 'help';
</script>
<script defer>
	function navActiveClass() {
		if ($(".header-active-target[href='help']").length !== 0) $(".header-active-target[href='help']").addClass('active');
	}

	function init(module) {
		if (typeof module == 'undefined') {
			module = _p;
		}
		navActiveClass();
		if (typeof _fn[module] === 'function') {
			_ctrl = new _fn[module]();
			if (typeof _ctrl.init === 'function') {
				_ctrl.init(); //initialize page function;
			}
		}
	}
	
	$(document).ready(function() {
		init();
	})
</script>