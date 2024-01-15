<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title><?php echo $this->lang->line('js_processing'); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">	
	<meta name="google" content="notranslate">
	<link type="text/css" href="<?php echo base_url('assets/general/boostrap5/bootstrap.min.css'); ?>" rel="stylesheet">	
</head>
<body class="bg-dark">
	<div class="container">
		<div class="row justify-content-center align-items-center vh-100">			
			<div class="col-auto">
				<div class="spinner-border text-info text-warning" role="status">
				  <span class="visually-hidden">Loading...</span>				  
				</div>				
			</div>
			<div class="col-md-2 col-4 text-info fw-bold text-gold"><img src="<?php echo base_url('assets/desktop/images/web_logo.jpg'); ?>" class="img-fluid"></div>
		</div>
	</div>

</body>
</html>