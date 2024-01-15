<?php $this->load->view('web/parts/header');?>
<section class="main lottery-page">
	<div id="liveAlertPlaceholder"></div>

	<div class="fishing-page__container">
		<div id="carouselFishing" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
			<div class="carousel-indicators-container container">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselFishing" data-bs-slide-to="0" class="active" aria-current="true">
						<span>TC GAMING</span>
					</button>
					<button type="button" data-bs-target="#carouselFishing" data-bs-slide-to="1" class="" aria-current="true">
						<span>VR</span>
					</button>

				</div>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<div class="row sports-item container">
						<div class="col-4 sports-item__content">
							<div class="sports-item__content__title">TC<br>GAMING</div>
							<div class="sports-item__content__action">
								<a href="javascript:void(0);" onclick="open_game('F-SF01::game::DS::false');"><?php echo $this->lang->line('label_play_now')?></a>
							</div>
						</div>
						<div class="col-7">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/lottery_object_tc.png')?>" />
						</div>
					</div>
				</div>
				<div class="carousel-item ">
					<div class="row sports-item container">
						<div class="col-4 sports-item__content">
							<div class="sports-item__content__title">VR<br>GAMING</div>
							<div class="sports-item__content__action">
								<a href="javascript:void(0);" onclick="open_game('F-SF02::game::DS::false');"><?php echo $this->lang->line('label_play_now')?></a>
							</div>
						</div>
						<div class="col-7">
							<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/lottery_object_vr.png')?>" />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>
<?php $this->load->view('web/parts/footer');?>