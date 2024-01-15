<?php $this->load->view('web/parts/header');?>
	<section class="main sports-page">
		<div id="liveAlertPlaceholder"></div>

		<div class="sports-page__container">
			<div id="carouselSports" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
				<div class="carousel-indicators-container container">
					<div class="carousel-indicators">
						<button type="button" data-bs-target="#carouselSports" data-bs-slide-to="0" class="active" aria-current="true">
							<span>GX SPORT</span>
						</button>
						<button type="button" data-bs-target="#carouselSports" data-bs-slide-to="1" class="" aria-current="true">
							<span>IBC</span>
						</button>
						<button type="button" data-bs-target="#carouselSports" data-bs-slide-to="2" class=""aria-current="true">
							<span>CMD</span>
						</button>
					</div>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active saba">
						<div class="row sports-item container">
							<div class="col-4 sports-item__content">
								<div class="sports-item__content__title">GX<br>Sports</div>
								<div class="sports-item__content__description"></div>
								<div class="sports-item__content__action">
									<a href="javascript:void(0);" onclick="open_game('IGK','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now')?></a>
								</div>
							</div>
							<div class="col-7">
								<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/sport_object_sbo.png')?>" />
							</div>
						</div>
					</div>
					<div class="carousel-item  cmd">
						<div class="row sports-item container">
							<div class="col-4 sports-item__content">
								<div class="sports-item__content__title">IBC</div>

								<div class="sports-item__content__action">
									<a href="javascript:void(0);" onclick="open_game('IBC','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now')?></a>
								</div>
							</div>
							<div class="col-7">
								<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/sport_object_ibc.png')?>" />
							</div>
						</div>
					</div>
					<div class="carousel-item m8">
						<div class="row sports-item container">
							<div class="col-4 sports-item__content">
								<div class="sports-item__content__title">CMD</div>
								
								<div class="sports-item__content__action">
									<a href="javascript:void(0);" onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now')?></a>
								</div>
							</div>
							<div class="col-7">
								<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/sport_object_cmd.png')?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php $this->load->view('web/parts/footer');?>
<?php $this->load->view('jssport');?>