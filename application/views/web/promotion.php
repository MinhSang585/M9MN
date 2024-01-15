	<?php $this->load->view('web/parts/header'); ?>

	<style>
		.promoItem {
			border-radius: 15px;
			box-shadow: 0 6px 10px 0 rgba(254, 252, 228, 0.9), 0 8px 22px 0 rgba(254, 252, 228, 0.8);
			max-height: 335px;
			max-width: 1000px;
			margin: 0 auto;
			background-repeat: no-repeat;
			/*position: absolute;*/
			overflow: hidden;
			cursor: pointer;
			/*-webkit-filter:brightness(85%);*/
			-webkit-filter: contrast(110%);
		}

		.promoItem:after {
			content: "";
			position: absolute;
			top: -50%;
			left: -60%;
			width: 20%;
			height: 200%;
			opacity: 0;
			transform: rotate(30deg);
			background: rgba(255, 255, 255, 0.13);
			background: linear-gradient(to right, rgba(255, 255, 255, 0.13) 0%, rgba(255, 255, 255, 0.13) 77%, rgba(255, 255, 255, 0.5) 92%, rgba(255, 255, 255, 0.0) 100%);
		}

		.promoItem:active,
		.promoItem:hover {
			-webkit-filter: contrast(110%);
			box-shadow: 0 8px 12px 0 rgba(95, 108, 123, 0.9), 0 10px 25px 0 rgba(95, 108, 123, 0.8);
			transform: scale(1.03);
		}

		.promoItem:active:after {
			left: 130%;
			transition-property: left, top, opacity;
			transition-duration: 0.7s, 0.7s, 0.15s;
			transition-timing-function: ease;
		}

		.promoItem:hover:after {
			opacity: 1;
			left: 130%;
			transition-property: left, top, opacity;
			transition-duration: 0.7s, 0.7s, 0.15s;
			transition-timing-function: ease;
		}

		.iziModal .iziModal-header {
			background: linear-gradient(90deg, #da8920, #bf9f76) no-repeat center top !important;
		}


		#modalPromo .iziModal-header-title {
			font-size: 24px !important;
			font-weight: 600 !important;
			color: #FEE5E2 !important;
		}

		#modalPromo img {
			border-radius: 10px !important;
		}

		.mymodalcontent ol {
			list-style: disc !important;
			padding-inline-start: 30px !important;
		}

		strong {
			color: #55225d;
			font-weight: bold;
			font-size: 1.1em;
		}

		#modalPromo::after {
			clear: both;
		}

		.mymodalcontent {
			padding: 2vw;
			line-height: 1.6;
		}


		.promoList {
			width: 100%;
			text-align: center !important;
		}

		.promoList li {
			display: inline-block;
			width: 45%;
			padding: 1em;
		}

		.divApplyPromo {
			position: absolute;
			right: 5%;
			width: 138px;
			height: 77px;
		}

		.btnApplyPromo {
			/*content: url('Assets/img/imgBtnApplyPromoDefault.png');*/
			cursor: pointer;
		}

		.btnApplyPromo:hover,
		.btnApplyPromo:focus {
			/*content: url('Assets/img/imgBtnApplyPromoHover.png');*/
			cursor: pointer;
		}

		.iziModal.hasScroll .iziModal-wrap {
			overflow-y: auto !important;
			overflow-x: hidden !important;
		}
		table,thead,tr,td,th,tbody {
			border : 0 !important;
		}
	</style>
	<!-- Filter tab -->
	<style>
		/*.container {
	width: 100%;
	margin-top: 20px;
	overflow: hidden;
	padding: 0;
	}*/

		.promo-nav-item {
			outline: none;
		}

		.promo-nav-item.active {
			border: 2px solid #d8ab3d;
			color: #d8ab3d;

		}

		.modal-content {
			background-image: url(<?php echo base_url('assets/dist/img/backgound_img.jpg'); ?>);
			background: #050505;
			color: #fff;
		}
		
		.modal-body, .modal-body *, .modal-footer, .modal-footer *:not(button) {
			background-color: #f2b250 !important;
		}
		.modal-body table {
			width: 95%;
			border-collapse: collapse;
			margin: 10px auto 10px auto;
		}
		.modal-body th, .modal-body td {
			border: 1px solid white !important;
			padding: 8px !important;
			text-align: center;
		}
		.modal-title{
			color: #f2b250;
		}
	</style>

	<section class="main promotion-page">
		<div id="liveAlertPlaceholder"></div>

		<style>
			.promotion-content-modal {
				display: none;
			}

			.promotion-content-modal .closing-btn i {
				color: #404040 !important;
				font-size: 28px;
				position: relative;
				display: flex;
				align-items: center;
				justify-items: center;
				justify-content: center;
				height: inherit;
			}

			.custom-modal-size {
				max-width: 1400px;
				width: 90%;
			}
		</style>
		<div id="promosList">
			<div class="promotion-page__top-bg">
				<img src="<?php echo base_url('assets/desktop/images/banner/banner_promo.jpg') ?>" />
			</div>
			<div class="promotion-page__container container">
				<div class="promotion-page__container__content">
					<ul class="nav nav-tabs" role="tablist" data-dom="promotion-Category">
						<li class="nav-item active" role="presentation">
							<button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-target="#promotion-all" type="button" role="tab" aria-controls="promotion-all" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_all_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_all_on.png') ?>">
								<span><?php echo $this->lang->line('label_all'); ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="sports-tab" data-bs-toggle="tab" data-target="#promotion-sports" type="button" role="tab" aria-controls="promotion-sports" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_sport_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_sport_on.png') ?>">
								<span><?php echo $this->lang->line('page_sportsbook'); ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="casino-tab" data-bs-toggle="tab" data-target="#promotion-casino" type="button" role="tab" aria-controls="promotion-casino" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_live_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_live_on.png') ?>">
								<span><?php echo $this->lang->line('page_live_casino'); ?></span>
							</button>
						</li>


						<li class="nav-item" role="presentation">
							<button class="nav-link" id="slots-tab" data-bs-toggle="tab" data-target="#promotion-slots" type="button" role="tab" aria-controls="promotion-slots" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_slot_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_slot_on.png') ?>">
								<span><?php echo $this->lang->line('page_slots'); ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="fishing-tab" data-bs-toggle="tab" data-target="#promotion-fishing" type="button" role="tab" aria-controls="promotion-fishing" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_fish_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_fish_on.png') ?>">
								<span><?php echo $this->lang->line('page_fishing'); ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="esports-tab" data-bs-toggle="tab" data-target="#promotion-esports" type="button" role="tab" aria-controls="promotion-esports" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_esport_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_esport_on.png') ?>">
								<span><?php echo $this->lang->line('page_esports'); ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="game-tab" data-bs-toggle="tab" data-target="#promotion-game" type="button" role="tab" aria-controls="promotion-game" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_board_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_board_on.png') ?>">
								<span><?php echo $this->lang->line('page_poker'); ?></span>
							</button>
						</li>


						<li class="nav-item" role="presentation">
							<button class="nav-link" id="lottery-tab" data-bs-toggle="tab" data-target="#promotion-lottery" type="button" role="tab" aria-controls="promotion-lottery" aria-selected="false">
								<img class="img-default" src="<?php echo base_url('assets/desktop/images/promo_lottery_off.png') ?>">
								<img class="img-active" src="<?php echo base_url('assets/desktop/images/promo_lottery_on.png') ?>">
								<span><?php echo $this->lang->line('page_4d'); ?></span>
							</button>
						</li>

					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="promotion-all" role="tabpanel" aria-labelledby="promotion-all-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<div class="col-4 grid-item pm-2" style="display: block;">
											<div class="promotion-items__content">
												<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
												<div class="promotion-items__content__text">
													<div class="promotion-text-wrap">
														<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

													</div>
													<div class="promotion-items__content__text__btn">

														<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
													</div>
												</div>
											</div>
										</div>
								<?php
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-sports" role="tabpanel" aria-labelledby="promotion-sports-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'SB') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-casino" role="tabpanel" aria-labelledby="promotion-casino-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'LC') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-slots" role="tabpanel" aria-labelledby="promotion-slots-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'SL') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-fishing" role="tabpanel" aria-labelledby="promotion-fishing-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'FH') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-esports" role="tabpanel" aria-labelledby="promotion-esports-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'ES') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-game" role="tabpanel" aria-labelledby="promotion-game-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'BG') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<div class="tab-pane fade" id="promotion-lottery" role="tabpanel" aria-labelledby="promotion-lottery-tab">
							<div class="row promotion-items dom-registered" data-dom="promotionCategory">

								<?php
								if (sizeof($promotion) > 0) {
									foreach ($promotion as $promotion_row) {
										$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								?>
										<?php
										if (strpos($promotion_row['banner_category'], 'LT') !== false) {


										?>
											<div class="col-4 grid-item pm-2" style="display: block;">
												<div class="promotion-items__content">
													<img class="promotion-items__content__img" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_web']; ?>" target="Modal<?php echo $promotion_row['promotion_id']; ?>" data-promoname="<?php echo $promotion_row['promotion_title']; ?>">
													<div class="promotion-items__content__text">
														<div class="promotion-text-wrap">
															<div class="promotion-items__content__text__title"><?php echo $promotion_row['promotion_title']; ?></div>

														</div>
														<div class="promotion-items__content__text__btn">

															<button type="button" class="btn btn-primary promo-box" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>">LEARN MORE</button>
														</div>
													</div>
												</div>
											</div>


								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<!-- Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
							<div class="modal-dialog custom-modal-size" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="modalTitle"></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body p-0"></div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
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
	<script type="text/javascript">
		function toggle_visibility(s) {
			var id = $(s).attr('target');
			var e = document.getElementById(id);
			if (e.style.display == 'block')
				e.style.display = 'none';
			else
				e.style.display = 'block';
		}

		(function($) {
			$(document).ready(function() {

				$('#frmPromo').submit(function(e) {
					e.preventDefault();
				});

				$('#modalPromo').iziModal();

				$('.promoImage').click(function() {
					var width = $(window).width() * 0.7;

					var target = $(this).attr('target');
					var html = $('#' + target).html();

					$('#modalPromo').iziModal('setTitle', $(this).data('promoname'));

					$('#modalPromo').iziModal('setTop', 50);
					$('#modalPromo').iziModal('setBottom', 50);
					$('#modalPromo').iziModal('setWidth', width);
					$('#modalPromo').iziModal('setOverlayClose', false);
					$('#modalPromo').iziModal('open', {
						overlayColor: "black",
						transitionIn: "bounceInDown",
					});
					$('#modalPromo').iziModal('setContent', html);


					$('.iziModal-overlay').css("background-color", "rgba(212, 212, 212, 0.5)");

				});

				if (24799 <= 0)
					$('.btnApplyPromo').hide();

			});
		})(jQuery);

		$(document).ready(function() {
			//check if open dialog instant
			var value = getParameterByName('target', window.location);
			if (value && value != "0") {
				$('.promoImage[target*="' + value + '"').click();
			}
		});

		function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, '\\$&');
			var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}

		// Modal info promotion
		var base_url = '<?php echo base_url(); ?>';

		$('.promo-box').on('click', function() {
			var promotionId = $(this).data('promotion-id');

			$.ajax({
				type: 'GET',
				url: base_url + 'promotion/showModalInfo/' + promotionId,
				success: function(response) {
					var promotionData = JSON.parse(response);
					var pb = "<?php echo UPLOAD_PATH.'promotions/'; ?>"+promotionData.promotion_banner_web;	
					$('#myModal .modal-title').text(promotionData.promotion_title);
					$('#myModal .modal-body').html("<img src='"+pb+"' class='img-fluid'>");
					$('#myModal .modal-body').append(promotionData.promotion_content);

					$('#myModal').modal('show');

					$('#myModal .close').on('click', function() {
						$('#myModal').modal('hide');
					});
				},
				error: function() {

				}
			});
		});

		// Tab navigation
		$(document).ready(function() {
			$('.nav-link').on('click', function() {
				var tabId = $(this).attr('data-target');
				$('.tab-pane').removeClass('show active');
				$(tabId).addClass('show active');
			});
		});
	</script>