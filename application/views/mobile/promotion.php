<?php $this->load->view('mobile/parts/header'); ?>

<style type="text/css">
	.loginedhead {
		margin-top: 40px !important;
	}

	.modal-dialog {
		max-height: 80vh;
	}

	.modal-content {
		right: 0;
	}

	.modal-header {
		/* background-color: #C89F6A; */
		color: #FFFFFF;
	}

	header {
		height: 40px !important;
		box-shadow: 0 5px 10px #ccd7e6 !important;
		color: #6c6e71 !important;
		background: #fff !important;
	}

	.lr {
		display: none;
	}

	.m-bottom-menu {
		border-top: none;
		box-shadow: 0 -5px 8px -3px #ccd7e6;
	}

	.profile_title {
		text-align: center;
		line-height: 40px;
		font-size: 19px;
	}

	#comm-back-button {
		background: url(<?php echo base_url('assets/mobile/images/icon-back.png'); ?>) no-repeat;
		z-index: 99;
		width: 23px;
		height: 23px;
		background-size: 50%;
		position: absolute;
		left: 6px;
		top: 10px;
		filter: invert(60%);
	}

	table td,
	table th {
		font-size: 11px !important;
	}

	.radius img {
		border-radius: 40px;
		-moz-border-radius: 40px;
		/* Firefox */
	}

	.modal-content {
		background-image: url(<?php echo base_url('assets/dist/img/backgound_img.jpg'); ?>);
		background: #050505;
		color: #fff;
	}

	.custom-modal-size {
		max-width: 1400px;
		width: 90%;
	}
	table,thead,tr,td,th,tbody {
		border : 0 !important;
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

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML67X3F" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<section class="main promotion">
	<nav class="navbar fixed-top navbar-light nav-child" style="padding-bottom: 20px">
		<div class="container-fluid content justify-content-center">
			<div class="title"><?php echo $this->lang->line('label_promotion') ?></div>
		</div>
		<ul class="nav nav-pills promotion-nav" id="pills-tab" role="tablist" data-dom="promotion-Category">
			<li class="nav-item active" role="presentation">
				<button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-target="#promotion-all" type="button" role="tab" aria-controls="promotion-all" aria-selected="false"><?php echo $this->lang->line('label_all'); ?></button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="sports-tab" data-bs-toggle="tab" data-target="#promotion-sports" type="button" role="tab" aria-controls="promotion-sports" aria-selected="false"><?php echo $this->lang->line('page_sportsbook'); ?></button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="casino-tab" data-bs-toggle="tab" data-target="#promotion-casino" type="button" role="tab" aria-controls="promotion-casino" aria-selected="false"><?php echo $this->lang->line('label_casino'); ?></button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="slots-tab" data-bs-toggle="tab" data-target="#promotion-slots" type="button" role="tab" aria-controls="promotion-slots" aria-selected="false"><?php echo $this->lang->line('page_slots'); ?></button>	
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="esports-tab" data-bs-toggle="tab" data-target="#promotion-esports" type="button" role="tab" aria-controls="promotion-esports" aria-selected="false"><?php echo $this->lang->line('page_esports'); ?></button>	
			</li>

		</ul>
	</nav>

	<div class="tab-content">
		<div class="tab-pane fade show active" id="promotion-all" role="tabpanel" aria-labelledby="promotion-all-tab">
			<div class="container" style="background: #f8f8f8; padding-top: 20px">
				<div style="width: 100%; padding-top: 55px">
					<div class="row promotion-items dom-registered" data-dom="promotionCategory">
						<?php
						if (sizeof($promotion) > 0) {
							foreach ($promotion as $promotion_row) {
								$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
						?>
								<div class="radius" style="padding-top: 15px">
									<img class="promoImage" style="padding: 15px;" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_mobile']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>" height="100%" width="100%">
								</div>
						<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane fade sports" id="promotion-sports" role="tabpanel" aria-labelledby="promotion-sports-tab">
			<div class="container" style="background: #f8f8f8; padding-top: 20px">
				<div style="width: 100%; padding-top: 55px">
					<div class="row promotion-items dom-registered" data-dom="promotionCategory">
						<?php
						if (sizeof($promotion) > 0) {
							foreach ($promotion as $promotion_row) {
								$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								if (strpos($promotion_row['banner_category'], 'SB') !== false) {

						?>
									<div class="radius" style="padding-top: 15px">
										<img class="promoImage" style="padding: 15px;" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_mobile']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>" height="100%" width="100%">
									</div>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane fade casino" id="promotion-casino" role="tabpanel" aria-labelledby="promotion-casino-tab">
			<div class="container" style="background: #f8f8f8; padding-top: 20px">
				<div style="width: 100%; padding-top: 55px">
					<div class="row promotion-items dom-registered" data-dom="promotionCategory">
						<?php
						if (sizeof($promotion) > 0) {
							foreach ($promotion as $promotion_row) {
								$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								if (strpos($promotion_row['banner_category'], 'LC') !== false) {

						?>
									<div class="radius" style="padding-top: 15px">
										<img class="promoImage" style="padding: 15px;" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_mobile']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>" height="100%" width="100%">
									</div>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane fade slots" id="promotion-slots" role="tabpanel" aria-labelledby="promotion-slots-tab">
			<div class="container" style="background: #f8f8f8; padding-top: 20px">
				<div style="width: 100%; padding-top: 55px">
					<div class="row promotion-items dom-registered" data-dom="promotionCategory">
						<?php
						if (sizeof($promotion) > 0) {
							foreach ($promotion as $promotion_row) {
								$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								if (strpos($promotion_row['banner_category'], 'SL') !== false) {

						?>
									<div class="radius" style="padding-top: 15px">
										<img class="promoImage" style="padding: 15px;" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_mobile']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>" height="100%" width="100%">
									</div>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane fade esports" id="promotion-esports" role="tabpanel" aria-labelledby="promotion-esports-tab">
			<div class="container" style="background: #f8f8f8; padding-top: 20px">
				<div style="width: 100%; padding-top: 55px">
					<div class="row promotion-items dom-registered" data-dom="promotionCategory">
						<?php
						if (sizeof($promotion) > 0) {
							foreach ($promotion as $promotion_row) {
								$modalId = 'modal' . $promotion_row['promotion_id']; // Unique ID for each modal
								if (strpos($promotion_row['banner_category'], 'ES') !== false) {

						?>
									<div class="radius" style="padding-top: 15px">
										<img class="promoImage" style="padding: 15px;" src="<?php echo UPLOAD_PATH . 'promotions/' . $promotion_row['promotion_banner_mobile']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-promotion-id="<?php echo $promotion_row['promotion_id']; ?>" height="100%" width="100%">
									</div>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Single Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-0">
					<!-- Content of the modal will be loaded via AJAX -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('mobile/parts/footer'); ?>

<script type="text/javascript">
	// Modal info promotion
	var base_url = '<?php echo base_url(); ?>';

	$('.promoImage').on('click', function() {
		var promotionId = $(this).data('promotion-id');

		$.ajax({
			type: 'GET',
			url: base_url + 'promotion/showModalInfo/' + promotionId,
			success: function(response) {
				var promotionData = JSON.parse(response);
				var pb = "<?php echo UPLOAD_PATH.'promotions/'; ?>"+promotionData.promotion_banner_mobile;	
				$('#exampleModal .modal-title').text(promotionData.promotion_title);
				$('#exampleModal .modal-body').html("<img src='"+pb+"' class='img-fluid'>");
				$('#exampleModal .modal-body').append(promotionData.promotion_content);

				$('#exampleModal').modal('show');
			},
			error: function() {
				
			}
		});
	});

	$('#exampleModal').on('hidden.bs.modal', function (e) {
		$('#exampleModal .close').off('click');
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
