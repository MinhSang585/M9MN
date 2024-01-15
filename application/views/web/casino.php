<?php $this->load->view('web/parts/header'); ?>
	<section class="main live-casino-page">
		<div id="liveAlertPlaceholder"></div>

		<div class="live-casino-page__container">
			<div id="carouselLiveCasino" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
				<div class="carousel-indicators-container container">
					<div class="carousel-indicators">
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="0" class="active" aria-current="true">
							<span>EVOLUTION<br>GAMING </span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="1" class="" aria-current="true">
							<span>SEXY</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="2" class="" aria-current="true">
							<span>PRAGMATIC<BR>PLAY</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="3" class="" aria-current="true">
							<span>WM<br>GAMING</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="4" class="" aria-current="true">
							<span>DREAM<br>GAMING</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="5" class="" aria-current="true">
							<span>BIG<BR>GAMING</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="6" class="" aria-current="true">
							<span>MICRO<br>GAMING</span>
						</button>
						<button type="button" data-bs-target="#carouselLiveCasino" data-bs-slide-to="7" class="" aria-current="true">
							<span>PLAYTECH</span>
						</button>
					</div>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_evo.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">EVOLUTION<br>GAMING</div>
									<div class="sports-item__content__description"></div>
									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('EVO','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_sexy.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">SEXY</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('SX','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_pp.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">PRAGMATIC<br>PLAY</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('PP','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_wm.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">WM<br>GAMING</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('WM','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_dg.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">DREAM<br>GAMING</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('DG','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_bg.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">BIG<BR>GAMING</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('BG','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_mg.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">MICRO<br>GAMING</div>
									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('MG','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="sports-img">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/live_object_pt.png') ?>" />
						</div>
						<div class="sports-item-container container">
							<div class="row sports-item">
								<div class="col-4 sports-item__content">
									<div class="sports-item__content__title">PLAYTECH</div>

									<div class="sports-item__content__action">
										<a href="javascript:void(0);" onclick="open_game('PT','<?php echo GAME_LIVE_CASINO?>');"><?php echo $this->lang->line('label_play_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
<?php $this->load->view('web/parts/footer'); ?>
<?php $this->load->view('jscasino'); ?>