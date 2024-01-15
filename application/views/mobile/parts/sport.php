<div class="tab-pane fade show active" id="sports-events" role="tabpanel" aria-labelledby="sports-events-tab">
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/sport_gxsport.png?id=2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">GX SPORT</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_sportsbook'); ?></div>

			<button class="btn home-item__content__play_now" onclick="open_game('IGK','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now'); ?></button>
		</div>
	</div>
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/sport_ibc.png?id=2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">IBCBET</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_sportsbook'); ?></div>

			<button class="btn home-item__content__play_now" onclick="open_game('IBC','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now'); ?></button>
		</div>
	</div>
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/sport_cmd.png?id=2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">CMD368</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_sportsbook'); ?></div>

			<button class="btn home-item__content__play_now" onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK ?>');"><?php echo $this->lang->line('label_play_now'); ?></button>
		</div>
	</div>
</div>