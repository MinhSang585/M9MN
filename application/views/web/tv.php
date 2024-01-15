<?php $this->load->view('web/parts/header');?>

<style>
    .btnComingSoon {
        background: rgba(0, 0, 0, 0.3);
        border: #000 1px solid;
        color: #cdcdcd;
    }
</style>

<div id='theme-contain-cockfight'>
	<div id="div-cockfight" class="div-slot-body">
		<div class="img-banner">
			<div id="mainslider" role="main">
				<section class="flslider">
					<div class="flexslider">
						<ul class="slides">
							<li><a href='<?php echo site_url('casino');?>'><img src="<?php echo base_url('assets/data/1543/uploads/new/poster.jpg');?>" alt='' /></a></li>
							<li><a href='<?php echo site_url('sportsbook');?>'><img src="<?php echo base_url('assets/data/1543/uploads/new/SP02.png');?>" alt='' /></a></li>
						</ul>
					</div>
				</section>
			</div>
		</div>
		<div class="slot-body">
			<ul>
				<li>
					<table class="tbldetail" style="background-image: url('<?php echo base_url('assets/data/1543/uploads/new/comingsoon.png');?>');"></table>
				</li>
			</ul>
		</div>
	</div>
</div>

<script>
$( document ).ready(function() {

	  $('.flexslider').flexslider({
		animation: "fade",
		randomize:true,
		controlNav:false,
		directionNav:true,
		slideshowSpeed:7000,
		animationSpeed:600,
		randomize:false,
		start: function(slider){
		  $('body').removeClass('webloading');
		}
	  });
});
</script>

<?php $this->load->view('web/parts/footer');?>