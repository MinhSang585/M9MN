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
		<div class="slot-page__top-container" style=" background-image: url('<?php echo base_url('assets/desktop/images/games/banner_fish.jpg')?>');">
			<div class="slot-page__top-container__content container">
				<div class="slot-page__top-container__content__title"><img src="<?php echo base_url('assets/desktop/images/games/fish_title.png')?>"></div>
			</div>
			<div class="slot-page__content container">
				<div style="padding: 30px 40px;">
					<button onclick="sub_game('JDB','<?php echo GAME_FISHING?>');filter_fish('JDB')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor active" id="JDB">JDB</button>
					<button onclick="sub_game('SG','<?php echo GAME_FISHING?>');filter_fish('SG')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="SG">SPADEGAMING</button>
					<button onclick="sub_game('JILI','<?php echo GAME_FISHING?>');filter_fish('JILI')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="JILI">JILI</button>
					<button onclick="sub_game('FC','<?php echo GAME_FISHING?>');filter_fish('FC')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="FC">FACHAI</button>
					<button onclick="sub_game('RSG','<?php echo GAME_FISHING?>');filter_fish('RSG')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="RSG">RSG</button>
					<button onclick="sub_game('CQ9','<?php echo GAME_FISHING?>');filter_fish('CQ9')" style="margin-right: 35px" class="slot-page__content__tags__item btn slot-vendor" id="CQ9">CQ9</button>
					<button onclick="sub_game('PS','<?php echo GAME_FISHING?>');filter_fish('PS')" class="slot-page__content__tags__item btn slot-vendor" id="PS">PLAYSTAR</button>
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
<?php $this->load->view('jsfish');?>