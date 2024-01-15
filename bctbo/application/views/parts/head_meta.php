<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta http-equiv="x-ua-compatible" content="ie=edge">

<meta name="google" content="notranslate"/>

<title><?php echo (isset($page_title) ? $this->lang->line('system_name')."-".$page_title : $this->lang->line('system_name'));?></title>

<meta name="application-name" content="<?php echo $this->lang->line('system_name');?>">

<meta rel="apple-touch-icon" href="<?php echo base_url('assets/dist/img/favicon.ico');?>">

<link rel="icon" type="image/png" href="<?php echo base_url('assets/dist/img/favicon-32.png');?>" sizes="32x32">

<meta name="msapplication-TileImage" content="<?php echo base_url('assets/dist/img/favicon-144.png');?>">

<meta name="msapplication-TileColor" content="#2A2A2A">

<!-- jquery css -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/4.0.0-alpha.1/css/bootstrap-switch.min.css" rel="stylesheet"> -->


<!-- Font Awesome Icons -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css');?>">

<!-- overlayScrollbars -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css');?>">

<!-- DataTables -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css');?>">

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css');?>">

<?php if($this->uri->segment(1) == 'marquee' || $this->uri->segment(1) == 'game' || $this->uri->segment(1) == 'announcement' || $this->uri->segment(1) == 'player' || $this->uri->segment(1) == 'deposit' || $this->uri->segment(1) == 'withdrawal' || $this->uri->segment(1) == 'report' || $this->uri->segment(1) == 'avatar'  || $this->uri->segment(1) == 'message' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'playerpromotion' || $this->uri->segment(1) == 'bonus'|| $this->uri->segment(1) == 'reportorder' || $this->uri->segment(1) == 'bank' || $this->uri->segment(1) == 'match' || $this->uri->segment(1) == 'level' || $this->uri->segment(1) == 'reward' || $this->uri->segment(1) == 'paymentgateway' || $this->uri->segment(1) == 'player'  || $this->uri->segment(1) == 'log' || $this->uri->segment(1) == 'transaction' || $this->uri->segment(1) == 'blacklist' || $this->uri->segment(1) == 'whitelist' || $this->uri->segment(1) == 'blog' || $this->uri->segment(1) == 'content' || $this->uri->segment(1) == 'agent' || $this->uri->segment(1) == 'vipreward' || $this->uri->segment(1) == 'bank_report'):?>

<!-- daterange picker -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.css');?>">

<!-- Tempusdominus Bbootstrap 4 -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css');?>">

<?php endif;?>

<?php if($this->uri->segment(1) == 'account' || $this->uri->segment(1) == 'user'|| $this->uri->segment(1) == 'promotion'|| $this->uri->segment(1) == 'reportorder' || $this->uri->segment(1) == 'blog' || $this->uri->segment(1) == 'report' || $this->uri->segment(1) == 'role'):?>

<!-- iCheck for checkboxes and radio inputs -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">

<?php endif;?>

<?php if($this->uri->segment(1) == 'marquee' || $this->uri->segment(1) == 'game' || $this->uri->segment(1) == 'announcement' || $this->uri->segment(1) == 'banner' || $this->uri->segment(1) == 'group' || $this->uri->segment(1) == 'bank' || $this->uri->segment(1) == 'avatar'  || $this->uri->segment(1) == 'message' || $this->uri->segment(1) == 'fingerprint' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'playerpromotion' || $this->uri->segment(1) == 'bonus' || $this->uri->segment(1) == 'report' || $this->uri->segment(1) == 'reportorder' || $this->uri->segment(1) == 'bank' || $this->uri->segment(1) == 'player' || $this->uri->segment(1) == 'user'|| $this->uri->segment(1) == 'account' || $this->uri->segment(1) == 'level' || $this->uri->segment(1) == 'reward' || $this->uri->segment(1) == 'miscellaneous' || $this->uri->segment(1) == 'paymentgateway' || $this->uri->segment(1) == 'log' || $this->uri->segment(1) == 'blog' || $this->uri->segment(1) == 'role' || $this->uri->segment(1) == 'deposit' || $this->uri->segment(1) == 'withdrawal' || $this->uri->segment(1) == 'content' || $this->uri->segment(1) == 'tag' || $this->uri->segment(1) == 'agent' || $this->uri->segment(1) == 'vipreward'):?>

<!-- Select2 -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2/css/select2.min.css');?>">

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css');?>">

<?php endif;?>

<!-- Theme style -->

<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/adminlte.min.css');?>">

<!-- Custom style -->

<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/custom.min.css');?>">

<!-- Item style -->

<link rel="stylesheet" href="<?php echo base_url('assets/css/item.css');?>">

<!-- Google Font: Source Sans Pro -->

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<?php if($this->uri->segment(1) == 'miscellaneous' || $this->uri->segment(1) == 'banner' || $this->uri->segment(1) == 'avatar'  || $this->uri->segment(1) == 'message' || $this->uri->segment(1) == 'fingerprint' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'playerpromotion' || $this->uri->segment(1) == 'bonus' || $this->uri->segment(1) == 'bank' || $this->uri->segment(1) == 'match'  || $this->uri->segment(1) == 'reward' || $this->uri->segment(1) == 'paymentgateway' || $this->uri->segment(1) == 'blog' || $this->uri->segment(1) == 'content'):?>

<style type="text/css">

.custom-file-input:lang(en)~.custom-file-label::after, .custom-file-label::after  {

    content: "<?php echo $this->lang->line('label_browse');?>";

}

</style>

<?php endif;?>

<?php if($this->uri->segment(1) == 'marquee' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'announcement'):?>

<!--Summernote-->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/summernote/summernote-bs4.css');?>">

<?php endif;?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css?v='.time());?>">



<!--BET CSS-->

<?php if($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'transaction'):?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/cards.css?v='.time());?>">

<link rel="stylesheet" href="<?php echo base_url('assets/css/dices.css?v='.time());?>">

<link rel="stylesheet" href="<?php echo base_url('assets/css/balls.css?v='.time());?>">

<?php endif;?>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css');?>">

