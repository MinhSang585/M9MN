<?php $this->load->view('web/parts/header');?>
<style>
	.btn-close {
		filter: var(--bs-btn-close-white-filter);
		--bs-btn-close-white-filter: invert(1) grayscale(100%) brightness(200%);
	}
</style>
<section class="container-fluid lottery-page">
	<div class="container lottery-page py-5">
		<div class="row g-0 pb-5">
			<div class="col-6">
				<div class="row g-4">
					<div class="col-auto">
						<button type="button" class="btn btn-outline-warning rounded-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
							<?php echo $this->lang->line('label_4d_btn_tnc');?>
						</button>
					</div>
					<div class="col-auto">
						<button type="button" class="btn btn-outline-warning rounded-3" data-bs-toggle="modal" data-bs-target="#exampleModal1">
							<?php echo $this->lang->line('label_4d_btn_payout');?>
						</button>
					</div>
				</div>
			</div>
			<div class="col-6 text-end">
				<a href="https://wa.me/+" target="_blank" class="btn btn-warning rounded-3" >
					<?php echo $this->lang->line('label_4d_bet_now');?>
				</a>
			</div>
		</div>
		<div class="row g-4 text-white">
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-magnum.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Magnum - 4D</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-damacai.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Damacai 1+3D</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-toto.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Sports Toto</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-singapore.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Singapore 4D</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row g-4 text-white pt-4">
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-sabah.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Sabah 88</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-sandakan.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">4STC 4D</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-cashsweep.png')?>"></div>
							<div class="col-8 text-end"><b class="font-family-fantasy">Cash Sweep</b> <br> <small>22/10/2023(sun)</small></div>
						</div>
						<div class="row g-0 p-3">
							<div class="col-6 text-start">
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_1st_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_2nd_prize');?></h5>
								<h5 class="mb-2"><?php echo $this->lang->line('label_4d_3rd_prize');?></h5>
							</div>
							<div class="col-6">
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
								<h5 class="mb-2">8888</h5>
							</div>
						</div>
						<div class="row g-0">
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
							<div class="col-12 bg-dark p-2"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-12 p-3">
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
								</div>
								<div class="row g-0">
									<div class="col-3"></div>
									<div class="col-3">8888</div>
									<div class="col-3">8888</div>
									<div class="col-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content text-white bg-dark">
			<div class="modal-header bg-dark">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('label_4d_btn_tnc');?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body bg-dark">
				<h6 class="mb-4"><?php echo $this->lang->line('label_4d_tnc_sub_title');?></h6>
				<ul>
					<li><p><?php echo $this->lang->line('label_4d_tnc_contant_01');?></p></li>
					<li><p><?php echo $this->lang->line('label_4d_tnc_contant_02');?></p></li>
					<li><p><?php echo $this->lang->line('label_4d_tnc_contant_03');?></p></li>
					<li><p><?php echo $this->lang->line('label_4d_tnc_contant_04');?></p></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content text-white bg-dark">
			<div class="modal-header bg-dark">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('label_4d_payout_title');?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body bg-dark">
				<h6><?php echo $this->lang->line('label_4d_payout_sub_title_01');?></h6>
				<h6><?php echo $this->lang->line('label_4d_contant_01');?></h6>
				<div class="row g-0 py-3">
					<div class="col-12 text-center">
						<div class="row g-0 p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_big_forecast');?></div>
							<div class="col-6"><?php echo $this->lang->line('label_4d_prize_amount');?></div>
						</div>
						<div class="row g-0 bg-black p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_1st_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 2,500.00</div>
						</div>
						<div class="row g-0 bg-secondary p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_2nd_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 1,000.00</div>
						</div>
						<div class="row g-0 bg-black p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_3rd_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 500.00</div>
						</div>
						<div class="row g-0 bg-secondary p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_special');?></div>
							<div class="col-6 text-warning fw-900">$ 200.00</div>
						</div>
						<div class="row g-0 bg-black p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_consplaton');?></div>
							<div class="col-6 text-warning fw-900">$ 60.00</div>
						</div>
					</div>
				</div>

				<h6><?php echo $this->lang->line('label_4d_payout_title_02');?></h6>
				<h6><?php echo $this->lang->line('label_4d_payout_sub_title_02');?></h6>
				<div class="row g-0 pt-3">
					<div class="col-12 text-center">
						<div class="row g-0 p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_small_forecast');?></div>
							<div class="col-6"><?php echo $this->lang->line('label_4d_prize_amount');?></div>
						</div>
						<div class="row g-0 bg-black p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_1st_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 3,500.00</div>
						</div>
						<div class="row g-0 bg-secondary p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_2nd_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 2,000.00</div>
						</div>
						<div class="row g-0 bg-black p-3">
							<div class="col-6"><?php echo $this->lang->line('label_4d_3rd_prize');?></div>
							<div class="col-6 text-warning fw-900">$ 1000.00</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('web/parts/footer');?>