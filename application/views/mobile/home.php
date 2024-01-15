<?php $this->load->view('mobile/parts/header'); ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML67X3F" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<header class="main-wrapper header-wrapper">
	<nav class="navbar fixed-top navbar-light">
		<div class="container-fluid content">
			<button class="btn" id="navMenu">
				<img src="<?php echo base_url('assets/mobile/img/header_icon_menu.png') ?>" alt="Header Manu" />
			</button>
			<a href="<?php echo site_url('home') ?>">
				<img class="content__logo" src="<?php echo base_url('assets/mobile/img/web_logo.jpg') ?>" alt="Header Logo" />
			</a><a href="#" class="content__customer_service">
				<img src="<?php echo base_url('assets/mobile/img/header_icon_customer.png') ?>" alt="Header Customer Service" />
				<span><?php echo $this->lang->line('label_customer_service'); ?></span>
			</a>
		</div>
	</nav>
</header>

<div id="mySidenav" class="sidenav">
	<div class="left-nav">
		<div class="left-nav__top">
			<?php if ($this->session->userdata('is_logged_in') != TRUE) { ?>
				<div class="left-nav__top__info">
					<div class="left-nav__top__info__avatar">
						<img src="<?php echo base_url('assets/mobile/img/navbar_icon_myacc_on.png') ?>">
					</div>
					<a href="<?php echo site_url('login') ?>" class="left-nav__top__info__user-info">
						<div>Login/Register</div>
					</a>
				</div>
			<?php } else { ?>
				<div class="left-nav__top__info">
					<div class="left-nav__top__info__avatar">
						<img src="<?php echo base_url('assets/mobile/img/1.png') ?>" id="header-avater-img">
					</div>
					<a href="javascript:void(0);" class="left-nav__top__info__user-info">
						<h3><?php echo $this->session->userdata('username') ?></h3>
						<p><span>Total Balance</span></p>
						<div><?php echo $this->lang->line('system_currency') ?> <span class="bal_main">0.00</span>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_top_icon_refresh.png') ?>" style="width: 20px" class="trigger-spin">
						</div>
					</a>
				</div>
			<?php } ?>
			<ul class="left-nav__top__nav">
				<li class="left-nav__top__nav__item">
					<a href="<?php echo site_url('home') ?>" class="left-nav__top__nav__item__link">
						<div>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_icon_home.png') ?>" />
						</div>
						<span><?php echo $this->lang->line('label_home'); ?></span>
					</a>
				</li>
				<li class="left-nav__top__nav__item">
					<a href="<?php echo site_url('account') ?>" class="left-nav__top__nav__item__link">
						<div>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_icon_myacc.png') ?>" />
						</div>
						<span><?php echo $this->lang->line('label_my_account'); ?></span>
					</a>
				</li>
				<li class="left-nav__top__nav__item">
					<a href="<?php echo site_url('promotion') ?>" class="left-nav__top__nav__item__link">
						<div>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_icon_promo.png') ?>" />
						</div>
						<span><?php echo $this->lang->line('label_promotion'); ?></span>
					</a>
				</li>
				<li class="left-nav__top__nav__item">
					<a href="<?php echo site_url('lottery') ?>" class="left-nav__top__nav__item__link">
						<div>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_btm_icon_4d.png') ?>" />
						</div>
						<span><?php echo $this->lang->line('page_lottery'); ?></span>
					</a>
				</li>
				<li class="left-nav__top__nav__item">
					<a href="#" data-bs-toggle="modal" data-bs-target="#selectLanguage">
						<div>
							<img src="<?php echo base_url('assets/mobile/img/sidemenu_icon_en.png') ?>" />
						</div>
						<span><?php echo $this->lang->line('label_language'); ?></span>
					</a>
				</li>
			</ul>
		</div>
		<?php if ($this->session->userdata('is_logged_in') == TRUE) { ?>
			<div class="left-nav__bottom">
				<div class="left-nav__bottom__follow-us d-grid">
					<a href="<?php echo site_url('logout') ?>" class="btn btn-warning rounded-0">
						<div class="p-1"><?php echo $this->lang->line('label_logout'); ?></div>
					</a>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="backdrop"></div>
</div>
<div class="modal fade modal-custom" id="selectLanguage" tabindex="-1" aria-labelledby="selectLanguageLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0 px-3 py-2">
				<h5 class="modal-title" id="selectLanguageLabel"><?php echo $this->lang->line('label_choose_language'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body bg-dark">
				<div class="row mb-4" role="button">
					<div class="col-12 text-white text-center">
						<span class="choose-language" data-language="EN" data-region="Malaysia" onclick="change_language('<?php echo LANG_EN ?>');">English</span> 
						<span>|</span>
						<span class="choose-language" data-language="ZH" data-region="Malaysia" onclick="change_language('<?php echo LANG_ZH_CN; ?>');">中文</span>
						<span>|</span>
						<span class="choose-language" data-language="MS" data-region="Malaysia" onclick="change_language('<?php echo LANG_MS; ?>');">Malay</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Begin Home Banner Slideshow -->
<div style="margin-top: 55px">
	<?php
	if (ENVIRONMENT == 'production') {
		if (isset($banner) && sizeof($banner) > 0) {
	?>
			<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-indicators">
					<?php for ($i = 0; $i < sizeof($banner); $i++) { ?>
						<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i; ?>" class="<?php if ($i == 0) {
																																			echo 'active';
																																		} ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
					<?php } ?>
				</div>
				<div class="carousel-inner">
					<?php for ($i = 0; $i < sizeof($banner); $i++) { ?>
						<div class="carousel-item <?php if ($i == 0) {
														echo 'active';
													} ?>">
							<a href="<?php if ($banner[$i]['banner_url'] != "") {
											echo $banner[$i]['banner_url'];
										} ?>">
								<img class="d-block w-100" src="<?php echo UPLOAD_PATH . "/banners/" . $banner[$i]['mobile_banner'] . '?v=' . time(); ?>" alt="<?php echo $banner[$i]['mobile_banner_alt']; ?>">
							</a>
						</div>
					<?php } ?>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
		<?php } else { ?>
			<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img class="d-block w-100" src="<?php echo base_url('assets/mobile/images/banner/home_banner.jpg'); ?>" />
					</div>
				</div>
			</div>
		<?php }
	} else { ?>
		<div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
			<div class="carousel-indicators">
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class="" aria-label="Slide 2"></button>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="<?php echo base_url('assets/mobile/img/banner_home.jpg'); ?>" class="w-100" alt="...">
				</div>
				<div class="carousel-item">
					<img src="<?php echo base_url('assets/mobile/img/banner_home.jpg'); ?>" class="w-100" alt="...">
				</div>
			</div>
		</div>
	<?php
	}
	?>
</div>
<div class="home__content container-fluid main-content-wrapper">
	
<div class="">
		<!-- BEGIN BANNER -->
		<?php /*<section class="banner-section" style="">
			<?php $margintop = ($this->session->userdata('is_logged_in') == TRUE) ? 0 : 90; ?>
			<div id="jssor_1" style="position: relative; margin: 0 auto; top: <?php echo $margintop; ?>px; left: 0px; width: 2200px; height: 1100px; overflow: hidden; visibility: hidden;">
				<!-- Loading Screen -->
				<div data-u="loading" class="jssorl-004-double-tail-spin" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; text-align: center; background-color: rgba(0,0,0,0.7);">
					<!--<img style="margin-top: -19px; position: relative; top: 50%; width: 38px; height: 38px;" src="img/double-tail-spin.svg" />-->
				</div>
				<div id="MainContent_homeBannerDIV" data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 2200px; height: 1100px; overflow: hidden;">
					<?php
					if (sizeof($banner) > 0) {
						$html = '';
						foreach ($banner as $k => $v) {
							if (!empty($v['banner_url'])) {
								$html .= '<div><a href="' . $v['banner_url'] . '"><img data-u="image" src="' . UPLOAD_PATH . 'banners/' . $v['mobile_banner'] . '" alt="' . $v['mobile_banner_alt'] . '" /></a></div>';
							} else {
								$html .= '<div><img data-u="image" src="' . UPLOAD_PATH . 'banners/' . $v['mobile_banner'] . '" alt="' . $v['mobile_banner_alt'] . '" /></div>';
							}
						}
						echo $html;
					}
					?>
                    <div>
                        <a href="#">
                            <img data-u="image" src="<?php echo base_url('assets/mobile/images/banner/imgBannerFirstDepositBonus.jpg'); ?>" />
                        </a>
                    </div>
                    <div>
                        <a href="#">
							<img data-u="image" src="<?php echo base_url('assets/mobile/images/banner/home_mb_banner_ranking_promo.jpg'); ?>" />
                        </a>
                    </div>


				</div>
				<!-- Bullet Navigator -->
				<div data-u="navigator" class="jssorb031" style="position: absolute; bottom: 12px; right: 12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="-0.6">
					<div data-u="prototype" class="i" style="width: 16px; height: 16px;">
						<svg viewbox="0 0 16000 16000" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
							<circle class="b" cx="8000" cy="8000" r="5800"></circle>
						</svg>
					</div>
				</div>
				<!-- Arrow Navigator -->
				<div data-u="arrowleft" class="jssora051" style="width: 55px; height: 55px; top: 0px; left: 25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
					<svg viewbox="0 0 16000 16000" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
						<polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
					</svg>
				</div>
				<div data-u="arrowright" class="jssora051" style="width: 55px; height: 55px; top: 0px; right: 25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
					<svg viewbox="0 0 16000 16000" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
						<polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
					</svg>
				</div>
			</div>
			<script type="text/javascript">
				jssor_1_slider_init();
			</script>

		</section> */ ?>

		<!-- END BANNER -->
	</div>
	<div class="notification">
		<div class="notification__sound"><img src="<?php echo base_url('assets/mobile/img/icon_anno.png') ?>" alt=""></div>
		<div class="text-animated dom-registered" data-dom="announcement" data-setting="{&quot;add_marquee_tag&quot;: true}">
			<marquee id="announcement" behavior="scroll" direction="left"> WELCOME TO BEST GAMING</marquee>
		</div>
	</div>

	<div class="home-game">
		<ul class="home-game__nav nav nav-pills row" role="tablist">
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link active" id="sports-events-tab" data-bs-toggle="tab" data-bs-target="#sports-events" type="button" role="tab" aria-controls="home" aria-selected="true">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_sport_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_sport_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('label_home_lobby_3'); ?></div>
				</button>
			</li>
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link" id="live-casino-tab" data-bs-toggle="tab" data-bs-target="#live-casino" type="button" role="tab" aria-controls="profile" aria-selected="false">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_live_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_live_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('page_live_casino'); ?></div>
				</button>
			</li>
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link" id="slot-machines-tab" data-bs-toggle="tab" data-bs-target="#slot-machines" type="button" role="tab" aria-controls="contact" aria-selected="false">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_slot_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_slot_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('label_home_lobby_4'); ?></div>
				</button>
			</li>
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link" id="fishing-game-tab" data-bs-toggle="tab" data-bs-target="#fishing-game" type="button" role="tab" aria-controls="contact" aria-selected="false">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_fish_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_fish_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('label_home_lobby_5'); ?></div>
				</button>
			</li>
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link" id="e-sports-tab" data-bs-toggle="tab" data-bs-target="#e-sports" type="button" role="tab" aria-controls="contact" aria-selected="false">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_esport_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_esport_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('page_esports'); ?></div>
				</button>
			</li>
			<li class="nav-item home-game__nav__item col-4" role="presentation">
				<button class="nav-link home-game__nav__item__link" id="board-game-tab" data-bs-toggle="tab" data-bs-target="#board-game" type="button" role="tab" aria-controls="contact" aria-selected="false">
					<div class="home-game__nav__item__link__img">
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_lottery_off.png') ?>" />
						<img src="<?php echo base_url('assets/mobile/img/home_category_icon_lottery_on.png') ?>" />
					</div>
					<div class="home-game__nav__item__link__text"><?php echo $this->lang->line('page_board_game'); ?></div>
				</button>
			</li>
		</ul>
		<div class="tab-content home-game__content" id="myTabContent">
			<?php $this->load->view('mobile/parts/sport'); ?>
			<?php $this->load->view('mobile/parts/live'); ?>
			<?php $this->load->view('mobile/parts/slot'); ?>
			<?php $this->load->view('mobile/parts/fish'); ?>
			<?php $this->load->view('mobile/parts/esport'); ?>
			<?php $this->load->view('mobile/parts/board'); ?>
		</div>
	</div>

</div>
<?php $this->load->view('mobile/parts/footer'); ?>