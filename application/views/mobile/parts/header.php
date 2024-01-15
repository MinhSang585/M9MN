<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_language_code('iso'); ?>">
<head>
    <title><?php echo (isset($seo['page_title']) ? $seo['page_title'] : ''); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1.0" />
    <meta name="description" content="<?php echo (isset($seo['meta_descriptions']) ? $seo['meta_descriptions'] : ''); ?>">
    <meta name="keywords" content="<?php echo (isset($seo['meta_keywords']) ? $seo['meta_keywords'] : ''); ?>">
    <meta name="theme-color" content="#FFFFFF">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo base_url('assets/favicon/favicon192x192.png'); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/favicon/favicon32x32.png'); ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/favicon/favicon16x16.png'); ?>" sizes="16x16">
    <link type="text/css" href="<?php echo base_url('assets/general/boostrap5/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/fontawesome-free/css/all.min.css'); ?>">
    <link type="text/css" href="<?php echo base_url('assets/mobile/css/master/innerpage.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/js/datatables/datatables.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/reveal.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery-ui.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery.datetimepicker.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/font-awesome.css'); ?>">
    <?php $this->load->view('meta'); ?>
    <link type="text/css" href="<?php echo base_url('assets/mobile/css/dbg_style.css?id=' . time()); ?>" rel="stylesheet">
</head>

<body>
