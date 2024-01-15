<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<!-- form start -->
							<div class="card-body">
								<div class="form-group row">
									<label for="username" class="col-2 col-form-label"><?php echo $this->lang->line('label_username');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($username)) ? $username : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_id" class="col-2 col-form-label"><?php echo $this->lang->line('label_bank_name');?></label>
									<div class="col-10">
											<?php
												for($i=0;$i<sizeof($bank_list);$i++)
												{
													if(isset($bank_id)) 
													{
														if($bank_list[$i]['bank_id'] == $bank_id) 
														{
															echo '<label class="col-form-label font-weight-normal">'.$bank_list[$i]['bank_name'].'</label>';
														}
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_account_name" class="col-2 col-form-label"><?php echo $this->lang->line('label_bank_account_name');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_name)) ? $bank_account_name : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="bank_account_no" class="col-2 col-form-label"><?php echo $this->lang->line('label_bank_account_no');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_no)) ? $bank_account_no : '-');?></label>
									</div>
								</div>
								<div class="form-group row" style="display: none;">
									<label for="bank_account_address" class="col-2 col-form-label"><?php echo $this->lang->line('label_bank_account_address');?></label>
									<div class="col-10">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($bank_account_address)) ? $bank_account_address : '-');?></label>
									</div>
								</div>
								<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="custom-content-below-web-tab" data-toggle="pill" href="#custom-content-below-web" role="tab" aria-controls="custom-content-below-web" aria-selected="true"><?php echo $this->lang->line('bank_player_bank');?></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-content-below-mobile-tab" data-toggle="pill" href="#custom-content-below-mobile" role="tab" aria-controls="custom-content-below-mobile" aria-selected="false"><?php echo $this->lang->line('bank_player_credit_card');?></a>
									</li>
								</ul>
								<div class="tab-content" id="custom-content-below-tabContent">
									<div class="tab-pane fade show active" id="custom-content-below-web" role="tabpanel" aria-labelledby="custom-content-below-web-tab">
										<?php if(isset($bank_image_list) && sizeof($bank_image_list)>0){ ?>
											<div class="form-group row mt-3">
												<?php foreach($bank_image_list as $bank_image_list_row){ ?>
												<div class="col-3"><a onclick="viewImageData('<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_BANK."/".$bank_image_list_row;?>')"><img src="<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_BANK."/".$bank_image_list_row;?>" width="200" border="0" /></a></div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
									<div class="tab-pane fade" id="custom-content-below-mobile" role="tabpanel" aria-labelledby="custom-content-below-mobile-tab">
										<?php if(isset($credit_card_image_list) && sizeof($credit_card_image_list)>0){ ?>
											<div class="form-group row mt-3">
												<?php foreach($credit_card_image_list as $credit_card_image_list_row){ ?>
												<div class="col-3"><a onclick="viewImageData('<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD."/".$credit_card_image_list_row;?>')"><img src="<?php echo BANKS_PLAYER_USER_BANK_SOURCE_PATH . $username . "/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD."/".$credit_card_image_list_row;?>" width="200" border="0" /></a></div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#bank_player-form');
			
			$('.select2').select2();
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
		});
	</script>
	<script type="text/javascript">
		function viewImageData(file_location) {
			layer.open({
				type: 2,
				area: [((browser_width < 600) ? '100%': '100%'), ((browser_width < 600) ? '100%': '100%')],
				fixed: false,
				maxmin: true,
				scrollbar: false,
				title: '<?php echo $this->lang->line('title_bank_account_setting');?>',
				content: file_location
			});
		}
	</script>
</body>
</html>