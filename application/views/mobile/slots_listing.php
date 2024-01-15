<?php $this->load->view('mobile/parts/header');?>
	<style>
        header{height: 40px !important;box-shadow: 0 5px 10px #ccd7e6 !important;color:#6c6e71 !important;background:#fff !important;}
        .lr{display:none;}
        .m-bottom-menu{border-top:none;box-shadow: 0 -5px 8px -3px #ccd7e6;}
        .profile_title{text-align:center;line-height:40px;font-size:19px;}

        #comm-back-button {
            background: url(<?php echo base_url('assets/mobile/images/icon-back.png'); ?>) no-repeat;
            z-index: 99;
            width: 23px;
            height: 23px;
            background-size: 50%;
            position: absolute;
            left: 6px;
            top: 10px;
            filter: invert(60%);
        }

		.loginedhead {
			margin-top:40px !important;
		}

    </style>

    <header id="common-header" class="common-header" style="display: block;">
        <div class="cont relative profile_title">
            <a href="<?php echo site_url(); ?>"><div id="comm-back-button" class="left-button cursor_pointer" style=""></div></a>
            <div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('page_slots');?></div>
        </div>
    </header>

	<div class="container-fluid" style="margin-top: 60px;">
		<div class="row g-2" id="gameContent">
			<?php echo (isset($list) ? $list : '');?>
		</div>
	</div>


	<?php /*
	<div class="content-wrap">
		<div class="gamelanding">
			<div class="slot-header">
			   <div class="col-12"> </div>
				<!--<a href="javascript:void(0);" class="big-icon" onclick="sub_game('BBIN', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_bbin.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('CQ9', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_cq9.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('HB', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_haba.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('ICG', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_icg.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('KA', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_ka.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('MG', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_mg.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('PP', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_pp.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('RTG', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_rtg.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('SP', '<?php echo GAME_SLOTS;?>');">
					<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/slots-page/top_nav_sp.png');?>">
				</a>-->
			</div>
		</div>
		<div class="text-center pt-3">
			<h4><?php echo $this->uri->segment(3);?> <?php echo strtoupper($this->lang->line('page_slots'));?></h4>
		</div>

		<div id="gameContent" class="slotpage-wrap clear">
			<?php echo (isset($list) ? $list : '');?>
		</div>
	</div>
	*/ ?>

<?php $this->load->view('mobile/parts/footer');?>