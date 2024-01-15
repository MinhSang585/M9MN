<?php $this->load->view('web/parts/header');?>
	<style>
	/* Style the tab */
	.slot-game-title{
		color: white;
	}
	.slot-vendor-wrap {
		width: 85%;
	}

	.slot-vendor-nav {
		width: 1500px;
	}

	.tab {
	overflow: hidden;
	border: 1px solid #ccc;
	background-color: #f1f1f1;
	}

	/* Style the buttons inside the tab */
	.tab button {
	background-color: inherit;
	float: left;
	border: none;
	outline: none;
	cursor: pointer;
	padding: 14px 16px;
	transition: 0.3s;
	font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
	background-color: #ddd;
	}

	/* Create an active/current tablink class */
	.tab button.active {
	background-color: #ccc;
	}

	/* Style the tab content */
	.tabcontent {
	display: none;
	border-top: none;
	}

	.slot-game-container {
		height: 750px;
		display: flex !important;
		flex-wrap: nowrap;
		justify-content: space-around;
	}
	</style>

	<section class="main slot-page">
		<div id="liveAlertPlaceholder"></div>
		<div class="slot-page__top-container">
			<div class="slot-page__top-container__content container">
				<div class="slot-page__top-container__content__title"><img src="<?php echo base_url('assets/desktop/images/games/slot_title.png')?>"></div>
				<div class="slot-page__top-container__content__count d-none">
					<div class="jp-wrap">
						<div><span id="jp_1">8</span></div>
						<div><span id="jp_2">8</span></div>
						<div><span id="jp_3">8</span></div>
						<div><span id="jp_4">8</span></div>
						<div><span id="jp_5">8</span></div>
						<div><span id="jp_6">8</span></div>
						<div><span id="jp_7">8</span></div>
						<div><span id="jp_8">8</span></div>
						<div><span id="jp_10">8</span></div>
						<div><span id="jp_11">8</span></div>
					</div>
				</div>
			</div>
			<div class="slot-page__content container">
				<div class="slot-page__content__tags pb-2">
					<button onclick="sub_game('PP','<?php echo GAME_SLOTS?>');filter_slot('PP')" class="slot-page__content__tags__item btn slot-vendor active" id="PP">PRAGMATIC</button>
					<button onclick="sub_game('SG','<?php echo GAME_SLOTS?>');filter_slot('SG')" class="slot-page__content__tags__item btn slot-vendor" id="SG">SPADEGAMING</button>
					<button onclick="sub_game('NS','<?php echo GAME_SLOTS?>');filter_slot('NS')" class="slot-page__content__tags__item btn slot-vendor" id="NS">NEXTSPIN</button>
					<button onclick="sub_game('JILI','<?php echo GAME_SLOTS?>');filter_slot('JILI')" class="slot-page__content__tags__item btn slot-vendor" id="JILI">JILI</button>
					<button onclick="sub_game('FC','<?php echo GAME_SLOTS?>');filter_slot('FC')" class="slot-page__content__tags__item btn slot-vendor" id="FC">FACHAI</button>
					<button onclick="sub_game('MG','<?php echo GAME_SLOTS?>');filter_slot('MG')" class="slot-page__content__tags__item btn slot-vendor" id="MG">MICROGAMING</button>
					<button onclick="sub_game('JK','<?php echo GAME_SLOTS?>');filter_slot('JK')" class="slot-page__content__tags__item btn slot-vendor" id="JK">JOKER</button>
					<button onclick="sub_game('HB','<?php echo GAME_SLOTS?>');filter_slot('HB')" class="slot-page__content__tags__item btn slot-vendor" id="HB">HABANERO</button>
				</div>
				<div class="slot-page__content__tags py-2">
					<button onclick="sub_game('MEGA','<?php echo GAME_SLOTS?>');filter_slot('MEGA')" class="slot-page__content__tags__item btn slot-vendor" id="MEGA">MEGA888</button>
					<button onclick="sub_game('918KISS','<?php echo GAME_SLOTS?>');filter_slot('918KISS')" class="slot-page__content__tags__item btn slot-vendor" id="918KISS">918KISS</button>
					<button onclick="sub_game('NE','<?php echo GAME_SLOTS?>');filter_slot('NE')" class="slot-page__content__tags__item btn slot-vendor" id="NE">NETEND</button>
					<button onclick="sub_game('RSG','<?php echo GAME_SLOTS?>');filter_slot('RSG')" class="slot-page__content__tags__item btn slot-vendor" id="RSG">RSG</button>
					<button onclick="sub_game('PT','<?php echo GAME_SLOTS?>');filter_slot('PT')" class="slot-page__content__tags__item btn slot-vendor" id="PT">PLAYTECH</button>
					<button onclick="sub_game('PUSSY','<?php echo GAME_SLOTS?>');filter_slot('PUSSY')" class="slot-page__content__tags__item btn slot-vendor" id="PUSSY">PUSSY</button>
					<button onclick="sub_game('CQ9','<?php echo GAME_SLOTS?>');filter_slot('CQ9')" class="slot-page__content__tags__item btn slot-vendor" id="CQ9">CQ9</button>
					<button onclick="sub_game('JDB','<?php echo GAME_SLOTS?>');filter_slot('JDB')" class="slot-page__content__tags__item btn slot-vendor" id="JDB">JDB</button>
				</div>
				<div style="padding: 0.5rem 40px;">
					<button onclick="sub_game('SPRIBE','<?php echo GAME_SLOTS?>');filter_slot('SPRIBE')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="SPRIBE">SPRIBE</button>
					<button onclick="sub_game('DCTR','<?php echo GAME_SLOTS?>');filter_slot('RELAX')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="RELAX">RELAX</button>
					<button onclick="sub_game('PS','<?php echo GAME_SLOTS?>');filter_slot('PS')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="PS">PLAYSTAR</button>
				</div>
				<div class="slot-page__content__search">
					<div class="input-group">
						<input type="text" class="form-control" name="searchString" id="searchString" placeholder="Enter game name to search" />
						<span class="input-group-text" id="basic-addon2">
							<img src="<?php echo base_url('assets/desktop/images/games/slot_icon_search.png')?>" />
						</span>
					</div>
				</div>
				<div class="slot-page__content__tab">
					<ul class="nav nav-tabs" id="myTab" role="tablist" data-dom="slot-tab-nav">
						<li class="nav-item active" role="presentation">
							<button class="nav-link" data-dom="slot-tab" data-tab="top" type="button" onclick="_ctrl.drawGameLayout('top')">Top</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" data-dom="slot-tab" data-tab="all" type="button" onclick="_ctrl.drawGameLayout('all')">All</button>
						</li>
					</ul>

					<div class="row g-3 list-wrapper py-3" id="game-panel"></div>
				</div>

				<div id="pagination-container" class="d-none"></div>
			</div>
		</div>
	</section>

<?php $this->load->view('web/parts/footer');?>
<?php $this->load->view('jsslot');?>