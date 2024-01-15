<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_language_code('iso'); ?>">
<head>
    <title><?php echo (isset($seo['page_title']) ? $seo['page_title'] : ''); ?></title>
    <link type="text/css" href="<?php echo base_url('assets/general/boostrap5/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/fontawesome-free/css/all.min.css'); ?>">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/master/master.css?=id123'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/modaltransfer.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/mainheader.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/layer/custom.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/iziModal.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/infoNew.css'); ?>" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo base_url('assets/favicon/favicon192x192.png'); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/favicon/favicon32x32.png'); ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/favicon/favicon16x16.png'); ?>" sizes="16x16">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/js/datatables/datatables.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/reveal.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery-ui.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery.datetimepicker.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/font-awesome.css'); ?>">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/general.css?id=' . time()); ?>" rel="stylesheet">
    <?php $this->load->view('meta'); ?>
    <!-- Desktop -->
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/dbg_style.css?id=' . time()); ?>" rel="stylesheet">
</head>
<body>
    <header class="sticky-top">
        <div class="my-navbar">
            <div class="my-navbar__top navbar navbar-expand-lg">
                <div class="my-navbar__top__container container">
                    <span class="navbar-text pe-3">
                        GMT+8 &nbsp;<span id="txt_clock"></span>
                    </span>
                    <?php /*
                    <ul class="navbar-nav me-auto nav-left">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo site_url(''); ?>"><?php echo $this->lang->line('page_home'); ?></a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link disabled" href="javascript:void(0)">
                                <div class="vr"></div>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" onclick="LC_API.open_chat_window();"><?php echo $this->lang->line('label_customer_service'); ?></a>
                        </li>
                    </ul>
                    */ ?>
                    <?php if ($this->session->userdata('is_logged_in') == TRUE) { ?>
                        <!-- After Login -->
                        <div class="fendc f08 navbar-nav">
                            <div class="red flc nav-item"><a class="nav-link" href="<?php echo site_url('account'); ?>"><?php echo $this->lang->line('label_welcome'); ?>: <?php echo $this->session->userdata('username'); ?></a></div>
                            <div class="red flc nav-item">
                                <span class="ms-2 nav-link"><?php echo $this->lang->line('label_wallet_balance'); ?> : </span><span class="nav-link bal_main">0.00</span>
                                <a class="nav-link" href="javascript:void(0);"><img id="refresh_icon" style="width:16px;height:16px;margin-top:0px;cursor:pointer;" src="<?php echo base_url('assets/desktop/images/icon_refresh.png'); ?>" border="0" /></a>
                            </div>
                            <div class="flc nav-item">
                                <a title="<?php echo $this->lang->line('label_deposit'); ?>" href="<?php echo site_url('account/deposit'); ?>" class="flc nav-link">
                                    <?php echo $this->lang->line('label_deposit'); ?>
                                </a>
                                <a title="<?php echo $this->lang->line('label_withdrawal'); ?>" href="<?php echo site_url('account/withdrawal'); ?>" class="flc nav-link">
                                    <?php echo $this->lang->line('label_withdrawal'); ?>
                                </a>
                            </div>
                            <a class="mx-2" href="<?php echo site_url('message'); ?>"><img src="<?php echo base_url('assets/desktop/images/header_user_icon_inbox.png'); ?>"></a>
                            <a class="mx-2" href="<?php echo site_url('logout'); ?>"><img src="<?php echo base_url('assets/desktop/images/header_user_icon_logout.png'); ?>"></a>
                        </div>
                    <?php } else { ?>
                        <!-- Begin Login -->
                        <?php echo form_open('ajax/login', array('id' => 'login-form', 'autocomplete' => 'off', 'class' => 'nav-login-form')); ?>
                        <div class="fendc">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="<?php echo base_url('assets/desktop/images/header_icon_username.png'); ?>" />
                                </span>
                                <input type="text" class="form-control" name="username" placeholder="<?php echo $this->lang->line('label_username'); ?>" value="">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="<?php echo base_url('assets/desktop/images/header_icon_password.png'); ?>" />
                                </span>
                                <input type="password" class="form-control" name="password" placeholder="<?php echo $this->lang->line('label_password'); ?>">

                                <span class="input-group-text">
                                    <a class="rmb" href="<?php echo site_url('forgot'); ?>"><?php echo $this->lang->line('label_forgot'); ?></a>
                                </span>
                            </div>
                            <input type="submit" name="login" class="btn btn-success" value="<?php echo $this->lang->line('label_login'); ?>">
                            &nbsp;
                            <a href="<?php echo site_url('register'); ?>" class="btn btn-info"><?php echo $this->lang->line('label_join_now'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="my-navbar__main navbar navbar-expand-lg" style="top:0;">
            <div class="my-navbar__main__container container">
                <a class="navbar-brand" href="<?php echo site_url(''); ?>">
                    <img src="<?php echo base_url('assets/desktop/images/web_logo.jpg'); ?>" />
                </a>
                <ul class="navbar-nav nav-left">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url(''); ?>"><?php echo $this->lang->line('label_home') ?></a>
                    </li>
                    <!-- SPORTBOOK -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "sportsbook") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('sportsbook'); ?>"><?php echo $this->lang->line('page_sportsbook') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <div class="col-3 mymenu__container__item" onclick="open_game('IGK','<?php echo GAME_SPORTSBOOK ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/sport_gx.png'); ?>" class="img-fluid lazy" />
                                    </div>
                                    <div class="col-3 mymenu__container__item " onclick="open_game('IBC','<?php echo GAME_SPORTSBOOK ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/sport_ibc.png'); ?>" class="img-fluid lazy" />
                                    </div>
                                    <div class="col-3 mymenu__container__item " onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/sport_cmd.png'); ?>" class="img-fluid lazy"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- LIVE -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "casino") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('casino'); ?>"><?php echo $this->lang->line('page_live_casino') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <div class="col mymenu__container__item " onclick="open_game('EVO','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_evo.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('SX','<?php echo GAME_LIVE_CASINO ?>','MX-LIVE-002');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_sexy.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('PP','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_pp.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('WM','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_wm.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('DG','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_dg.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('BG','<?php echo GAME_LIVE_CASINO ?>','BG-LIVE-001');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_bg.png'); ?>" class="img-fluid"/>
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('MG','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_mg.png'); ?>" class="img-fluid" />
                                    </div>
                                    <div class="col mymenu__container__item " onclick="open_game('PT','<?php echo GAME_LIVE_CASINO ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/live_pt.png'); ?>" class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- SLOT -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "slots") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('slots'); ?>"><?php echo $this->lang->line('page_slots') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container-fluid">
                                <div class="row mymenu__container live-casino custom-slot-img-size">
                                    <a class="col mymenu__container__item " href='<?php echo site_url('slots/game/PP') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_pp.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item " href='<?php echo site_url('slots/game/SG') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_sg.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item " href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_ns.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/JILI') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_jili.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/FC') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_fc.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/MG') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_mg.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/JK') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_jk.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/HB') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_hb.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_mega.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_kiss.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/NE') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_ne.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/RSG') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_rsg.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/PT') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_pt.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_pussy.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/CQ9') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_cq9.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/JDB') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_jdb.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_spribe.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/DCTR') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_relax.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('slots/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/slot_ps.png'); ?>" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- FISHING -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "fish") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('fish'); ?>"><?php echo $this->lang->line('page_fishing') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <a class="col mymenu__container__item " href='<?php echo site_url('fish/game/JDB') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_jdb.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item " href='<?php echo site_url('fish/game/SG') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_sg.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('fish/game/JILI') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_jili.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('fish/game/FC') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_fc.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('fish/game/RSG') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_rsg.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('fish/game/CQ9') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_cq9.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item" href='<?php echo site_url('fish/game/') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/fish_ps.png'); ?>" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- ESPORT -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "esports") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('esports'); ?>"><?php echo $this->lang->line('page_esports') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <div class="col mymenu__container__item" onclick="open_game('LH','<?php echo GAME_ESPORTS ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/esport_lh.png'); ?>" />
                                    </div>
                                    <div class="col mymenu__container__item" onclick="open_game('IM','<?php echo GAME_ESPORTS ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/esport_im.png'); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- BOARD -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "board") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('board'); ?>"><?php echo $this->lang->line('page_board_game') ?></a>
                        <div class="dropdown-menu mymenu">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <a class="col mymenu__container__item " href='<?php echo site_url('board/game/KM') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/board_km.png'); ?>" />
                                    </a>
                                    <a class="col mymenu__container__item " href='<?php echo site_url('board/game/V8') ?>'>
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/board_v8.png'); ?>" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- 4D -->
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if ($this->uri->segment(1) == "lottery") {
                                                echo "active";
                                            } ?> header-active-target" href="<?php echo site_url('lottery'); ?>"><?php echo $this->lang->line('page_lottery') ?></a>
                        <div class="dropdown-menu mymenu d-none">
                            <div class="container">
                                <div class="row mymenu__container live-casino">
                                    <div class="col mymenu__container__item" onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/lotter_vr.png'); ?>" />
                                    </div>
                                    <div class="col mymenu__container__item" onclick="open_game('CMD','<?php echo GAME_SPORTSBOOK ?>');">
                                        <img src="<?php echo base_url('assets/desktop/images/dropdown_games/lottery_tc.png'); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav nav-right ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);">
                            <div class="nav-link-img">
                                <img src="<?php echo base_url('assets/desktop/images/header_menu_icon_customer.png'); ?>" />
                            </div>
                            <div><?php echo $this->lang->line('label_customer_service'); ?></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('promotion'); ?>">
                            <div class="nav-link-img">
                                <img src="<?php echo base_url('assets/desktop/images/header_menu_icon_promo.png'); ?>" />
                            </div>
                            <div><?php echo $this->lang->line('label_promotion'); ?></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#selectLanguage">
                            <div class="nav-link-img">
                                <img id="flagLanguage" src="<?php echo base_url('assets/desktop/images/header_menu_icon_en.png'); ?>" />
                            </div>
                            <div id="countryNameLanguage"><?php echo $this->lang->line('label_language'); ?></div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

<script>
    function updateDateTime() {
        var now = new Date();
        var options = { 
            timeZone: 'Asia/Hong_Kong', 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };

        var formattedDateTime = now.toLocaleString('en-US', options);
        var formattedDate = formattedDateTime.split(', ')[0];
        var formattedTime = formattedDateTime.split(', ')[1];

        formattedDate = formattedDate.replace(/\//g, '-');
        var [month, day, year] = formattedDate.split('-');

        formattedTime = formattedTime.replace(/([APMapm]{2})/, ' $1');

        document.getElementById('txt_clock').innerHTML = `${month}-${day}-${year}, ${formattedTime}`;
        
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>