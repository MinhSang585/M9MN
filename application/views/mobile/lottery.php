<?php $this->load->view('mobile/parts/header');?>
<style>
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
		background-color: transparent;
		border: 1px solid gold;
	}
	.nav-pills .nav-link {
		border: 1px solid #777;
	}
	.btn-close {
		filter: var(--bs-btn-close-white-filter);
		--bs-btn-close-white-filter: invert(1) grayscale(100%) brightness(200%);
	}
</style>	
<section class="promotion mb-5 pb-5">
	<nav class="navbar fixed-top navbar-light nav-child sticky-top">
		<div class="container-fluid content justify-content-center">
			<div class="title">4D <?php echo $this->lang->line('page_lottery'); ?></div>
		</div>
	</nav>
	<div class="container">
		<ul class="nav nav-pills row g-2" id="pills-tab" role="tablist" style="flex-wrap: nowrap;overflow-x: scroll;">
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link active" id="pills-magnum-tab" data-bs-toggle="pill" data-bs-target="#pills-magnum" type="button" role="tab" aria-controls="pills-magnum" aria-selected="true">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-magnum.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-damacai-tab" data-bs-toggle="pill" data-bs-target="#pills-damacai" type="button" role="tab" aria-controls="pills-damacai" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-damacai.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-toto-tab" data-bs-toggle="pill" data-bs-target="#pills-toto" type="button" role="tab" aria-controls="pills-toto" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-toto.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-singapore-tab" data-bs-toggle="pill" data-bs-target="#pills-singapore" type="button" role="tab" aria-controls="pills-singapore" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-singapore.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-sabah-tab" data-bs-toggle="pill" data-bs-target="#pills-sabah" type="button" role="tab" aria-controls="pills-sabah" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-sabah.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-sandakan-tab" data-bs-toggle="pill" data-bs-target="#pills-sandakan" type="button" role="tab" aria-controls="pills-sandakan" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-sandakan.png?id-1') ?>" />
				</button>
			</li>
			<li class="col-auto nav-item" role="presentation">
				<button class="nav-link" id="pills-cashsweep-tab" data-bs-toggle="pill" data-bs-target="#pills-cashsweep" type="button" role="tab" aria-controls="pills-cashsweep" aria-selected="false">
					<img src="<?php echo base_url('assets/mobile/img/games/4d-cashsweep.png?id-1') ?>" />
				</button>
			</li>
		</ul>
		<div class="tab-content pt-4" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-magnum" role="tabpanel" aria-labelledby="pills-magnum-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-magnum.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-damacai" role="tabpanel" aria-labelledby="pills-damacai-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-damacai.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-toto" role="tabpanel" aria-labelledby="pills-toto-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-toto.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-singapore" role="tabpanel" aria-labelledby="pills-singapore-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-singapore.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-sabah" role="tabpanel" aria-labelledby="pills-sabah-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-sabah.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-sandakan" role="tabpanel" aria-labelledby="pills-sandakan-tab">
				<div class="row g-0">
					<div class="col-12 rounded-10 border border-color-gold bg-black text-center p-0">
						<div class="row g-0 border-bottom border-color-gold p-3">
							<div class="col-4 text-start"><img src="<?php echo base_url('assets/desktop/images/games/4d-sandakan.png')?>"></div>
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
			<div class="tab-pane fade" id="pills-cashsweep" role="tabpanel" aria-labelledby="pills-cashsweep-tab">
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

		<div class="row g-2 pt-4">
			<div class="col-6 d-grid">
				<button type="button" class="btn btn-outline-warning rounded-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
					<?php echo $this->lang->line('label_4d_btn_tnc');?>
				</button>
			</div>
			<div class="col-6 d-grid"> 
				<button type="button" class="btn btn-outline-warning rounded-3" data-bs-toggle="modal" data-bs-target="#exampleModal1">
					<?php echo $this->lang->line('label_4d_btn_payout');?>
				</button>
			</div>
			<div class="col-12 pt-3">
				<a href="https://wa.me/+" target="_blank" class="btn btn-warning rounded-3 d-grid btn-lg" >
					<?php echo $this->lang->line('label_4d_bet_now');?>
				</a>
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
<?php $this->load->view('mobile/parts/footer');?>