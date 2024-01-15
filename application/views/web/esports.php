<?php $this->load->view('web/parts/header');?>
	<section class="main e-sports-page">
		<div id="liveAlertPlaceholder"></div>
		<div class="sports-page__container">
			<div id="carouselSports" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
				<div class="carousel-indicators-container container">
					<div class="carousel-indicators">
						<button type="button" data-bs-target="#carouselSports" data-bs-slide-to="0" class="active" aria-current="true">
							<span>TF GAMING</span>
						</button>
						<button type="button" data-bs-target="#carouselSports" data-bs-slide-to="1" class="" aria-current="true">
							<span>IM ESPORT</span>
						</button>

					</div>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active saba">
						<div class="row sports-item container">
							<div class="col-4 sports-item__content">
								<div class="sports-item__content__title">TF GAMING</div>
								<div class="sports-item__content__description"></div>
								<div class="sports-item__content__action">
									<a href="javascript:void(0);" onclick="open_game('LH', '<?php echo GAME_ESPORTS;?>');"><?php echo $this->lang->line('label_play_now')?></a>
								</div>
							</div>
							<div class="col-7">
								<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/esport_object_lh.png')?>" />
							</div>
						</div>
					</div>
					<div class="carousel-item  cmd">
						<div class="row sports-item container">
							<div class="col-4 sports-item__content">
								<div class="sports-item__content__title">IM ESPORT</div>

								<div class="sports-item__content__action">
									<a href="javascript:void(0);" onclick="open_game('IM', '<?php echo GAME_ESPORTS;?>');"><?php echo $this->lang->line('label_play_now')?></a>
								</div>
							</div>
							<div class="col-7">
								<img class="sports-img" src="<?php echo base_url('assets/desktop/images/games/esport_object_ia.png')?>" />
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>

<?php $this->load->view('web/parts/footer');?>
<?php $this->load->view('jsesport');?>