<?php $this->load->view('web/parts/header'); ?>
<!-- BEGIN BANNER -->
<div class="container-fluid p-0 mt-md-0 mt-6 mainbanner">
	<div id="carousel-home">		
		<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active">
				  <img src="<?php echo base_url('assets/desktop/images/banner/banner_loading.jpg');?>" class="d-block w-100" alt="">
				</div>				
			</div>
		</div>		
	</div>
</div> 
<!-- END BANNER -->
<div class="home-page__notification">
	<div class="container notification">
		<img src="<?php echo base_url('assets/desktop/images/icon_anno.png'); ?>" />
		<div class="text-animated">
			<span class="text-overflow">
				<marquee id="announcement" behavior="scroll" direction="left"> WELCOME TO BEST GAMING</marquee>
			</span>
		</div>
	</div>
</div>

<div class="home-page__content">

	<div class="home-page__content__exciting_game">
		<div class="title"><span><?php echo $this->lang->line('label_home_lobby_1')?></span></div>
		<h5 class="subtitle"><?php echo $this->lang->line('label_home_lobby_2')?></h5>
		<div class="home-page__content__exciting_game__content container">
			<div id="carouselExcitingGame" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="0" class="active" aria-label="Slide 1">
						<span><?php echo $this->lang->line('label_home_lobby_3')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_sport_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_sport_on.png'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="1" aria-label="Slide 2">
						<span><?php echo $this->lang->line('page_live_casino')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_live_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_live_on.png'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="2" aria-label="Slide 3">
						<span><?php echo $this->lang->line('label_home_lobby_4')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_slot_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_slot_on.png'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="3" aria-label="Slide 4">
						<span><?php echo $this->lang->line('label_home_lobby_5')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_fish_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_fish_on.png'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="4" aria-label="Slide 5">
						<span><?php echo $this->lang->line('page_esports')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_esport_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_esport_on.png'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="5" aria-label="Slide 6">
						<span><?php echo $this->lang->line('page_board_game')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_board_off.png?id=1'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_board_on.png?id=1'); ?>" />
					</button>
					<button type="button" data-bs-target="#carouselExcitingGame" data-bs-slide-to="6" aria-label="Slide 7">
						<span><?php echo $this->lang->line('game_type_lt')?></span>
						<img class="default" src="<?php echo base_url('assets/desktop/images/lobby_icon_lottery_off.png'); ?>" />
						<img class="focus" src="<?php echo base_url('assets/desktop/images/lobby_icon_lottery_on.png'); ?>" />
					</button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_sport.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="open_game('GX','<?php echo GAME_SPORTSBOOK?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/gx.png?id=1'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/cmd.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('IBC','<?php echo GAME_SPORTSBOOK?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/ibc.png'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_live.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="open_game('EVO','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/evo.png?id=1'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('SX','<?php echo GAME_LIVE_CASINO?>','MX-LIVE-002');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/sexy.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('PP','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/pp.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('WM','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/wm.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('DG','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/dg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('BG','<?php echo GAME_LIVE_CASINO?>','BG-LIVE-001');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/bg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('MG','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/mg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('PT','<?php echo GAME_LIVE_CASINO?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/pt.png?id=1'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_slot.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="window.location.href='slots/game/PP'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/pp.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/SG'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/sg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/ns.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/JILI'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/jili.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/FC'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/fc.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/MG'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/mg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/JK'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/jk.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/HB'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/hb.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/mega.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/kiss.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/NE'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/ne.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/RSG'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/rsg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/PT'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/pt.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/pussy.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/CQ9'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/cq9.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/JDB'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/jdb.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/spribe.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/DCTR'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/relax.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='slots/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/ps.png'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_fish.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/JDB'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/jdb.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/SG'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/sg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/JILI'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/jili.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/FC'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/fc.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/RSG'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/rsg.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/CQ9'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/cq9.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='fishing/game/'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/ps.png'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_esport.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="open_game('TF','<?php echo GAME_ESPORTS?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/tf.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="open_game('IM','<?php echo GAME_ESPORTS?>');"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/im.png?id=1'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_board.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary" onclick="window.location.href='board/game/KM'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/km.png'); ?>" /></button>
							<button class="btn btn-primary" onclick="window.location.href='board/game/V8'"><img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/v8.png?id=1'); ?>" /></button>
						</div>
					</div>
					<div class="carousel-item">
						<img src="<?php echo base_url('assets/desktop/images/lobby_img_lottery.jpg'); ?>" class="d-block w-100" alt="...">
						<div class="button-group">
							<button class="btn btn-primary">
								<a href="<?php echo site_url('lottery'); ?>" class="">
									<img class="img-fluids mb-2" src="<?php echo base_url('assets/desktop/images/gamebtn/4d.png'); ?>" />
								</a>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="home-page__content__why_choose_us">
		<div class="title"><span><?php echo $this->lang->line('label_home_title_1')?></span></div>
		<h5 class="subtitle"><?php echo $this->lang->line('label_home_title_2')?></h5>
		<div class="home-page__content__why_choose_us__content container">
			<div class="row">
				<div class="col-12">
					<video width="100%" controls>
						<source src="<?php echo base_url('assets/desktop/mp4/video1.mp4'); ?>" type="video/mp4">
						<source src="<?php echo base_url('assets/desktop/mp4/video1.ogg'); ?>" type="video/ogg">
					</video>
				</div>
			</div>
		</div>
	</div>
	<div class="home-page__content__why_choose_us">
		<div class="title"><span><?php echo $this->lang->line('label_home_title_3')?></span></div>
		<h5 class="subtitle"><?php echo $this->lang->line('label_home_title_4')?></h5>
		<div class="home-page__content__why_choose_us__content container">
			<div class="row">
				<div class="col-3">
					<div class="item">
						<div class="img">
							<img src="<?php echo base_url('assets/desktop/images/why_icon_game.png'); ?>" />
						</div>
						<div class="title"><?php echo $this->lang->line('label_games')?></div>
						<div class="description"><?php echo $this->lang->line('label_home_content_1')?></div>
					</div>
				</div>
				<div class="col-3">
					<div class="item">
						<div class="img">
							<img src="<?php echo base_url('assets/desktop/images/why_icon_support.png'); ?>" />
						</div>
						<div class="title"><?php echo $this->lang->line('label_support')?></div>
						<div class="description"><?php echo $this->lang->line('label_home_content_2')?></div>
					</div>
				</div>
				<div class="col-3">
					<div class="item">
						<div class="img">
							<img src="<?php echo base_url('assets/desktop/images/why_icon_payment.png'); ?>" />
						</div>
						<div class="title"><?php echo $this->lang->line('label_payment')?></div>
						<div class="description"><?php echo $this->lang->line('label_home_content_3')?></div>
					</div>
				</div>
				<div class="col-3">
					<div class="item">
						<div class="img">
							<img src="<?php echo base_url('assets/desktop/images/why_icon_guaranteed.png'); ?>" />
						</div>
						<div class="title"><?php echo $this->lang->line('label_guranteed')?></div>
						<div class="description"><?php echo $this->lang->line('label_home_content_4')?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('web/parts/footer'); ?>