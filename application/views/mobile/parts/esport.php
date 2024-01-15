<div class="tab-pane fade" id="e-sports" role="tabpanel" aria-labelledby="e-sports-tab">
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/esport_lh.png?=id2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">TF GAMING</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_esports'); ?></div>

			<button class="btn home-item__content__play_now" onclick="open_game('TF','<?php echo GAME_ESPORTS ?>');"><?php echo $this->lang->line('label_play_now'); ?></button>
		</div>
	</div>	
	<div class="home-item">
		<img class="home-item__bg" src="<?php echo base_url('assets/mobile/img/games/esport_im.png?=id2') ?>" />
		<div class="home-item__content">
			<h3 class="home-item__content__title">IM GAMING</h3>
			<div class="home-item__content__subtitle"><?php echo $this->lang->line('page_esports'); ?></div>

			<button class="btn home-item__content__play_now" onclick="open_game('IM','<?php echo GAME_ESPORTS ?>');"><?php echo $this->lang->line('label_play_now'); ?></button>
		</div>
	</div>
</div>