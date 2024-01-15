<div class="tab-pane fade" id="board-game" role="tabpanel" aria-labelledby="board-game-tab">
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/board_km.png?=id2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">KINGMAKER</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_board_game'); ?></div>

			<a href="<?php echo site_url('board/game/KM') ?>" class="btn home-item__content__play_now"><?php echo $this->lang->line('label_play_now'); ?></a>
		</div>
	</div>
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/board_v8.png?=id2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">V8 POKER</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_board_game'); ?></div>

			<a href="<?php echo site_url('board/game/V8') ?>" class="btn home-item__content__play_now"><?php echo $this->lang->line('label_play_now'); ?></a>
		</div>
	</div>
</div>