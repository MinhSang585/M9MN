<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="<?php echo get_language_code('iso');?>">

<head>

	<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">

	<?php $this->load->view('parts/head_meta'); ?>

</head>
<body>

	<div class="wrapper">

		<section class="content">

			<div class="container-fluid mt-2">

				<div class="row">

					<div class="col-12">

						<?php if (!empty($player_bank_image_name)) : ?>
							
							<div class="setting_image">
								<div class="center-image">
									<img id="myImg" src="<?php echo BANKS_ACCOUNT_IMAGE . $player_bank_image_name; ?>" width="400px" height="600px" />
								</div>
							</div>
							
						<?php endif; ?>

					</div>

				</div>

			</div>

		</section>

	</div>

	<?php $this->load->view('parts/footer_js');?>

<!-- Get modal -->
	<div id="myModal" class="modal">
  		<span class="close">&times;</span>
  		<img class="modal-content" id="img01">
 		 <div id="caption"></div>
	</div>

<script>

	var modal = document.getElementById("myModal");

	// Get the image and insert it inside the modal - use its "alt" text as a caption
	var img = document.getElementById("myImg");
	var modalImg = document.getElementById("img01");
	var captionText = document.getElementById("caption");
	img.onclick = function(){
	
	var imagePath = './../../uploads/files/<?php echo $player_bank_image_name; ?>';
	
	modalImg.src = imagePath;
	
	captionText.innerHTML = this.alt;
	modal.style.display = "block";
	}

	var span = document.getElementsByClassName("close")[0];

	span.onclick = function() { 
	modal.style.display = "none";
	}

</script>

</body>

</html>

