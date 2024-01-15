<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title>Joker</title>
	<style type="text/css">
		body {background: #000; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/img/loading.gif');?>" border="0" />
	
	<form id="game-form" method="post" action="<?php echo (isset($url) ? $url : '');?>" class="hidden">
		<input type="hidden" name="token" value="<?php echo (isset($token) ? $token : '');?>" />
		<input type="hidden" name="game" value="<?php echo (isset($game_code) ? $game_code : '');?>" />
		<input type="hidden" name="mobile" value="<?php echo (isset($mobile) ? $mobile : 0);?>" />
		<input type="hidden" name="redirectUrl" value="<?php echo (isset($redirect_url) ? $redirect_url : '');?>" />
	</form>
	<script type="text/javascript">
		document.getElementById("game-form").submit();
	</script>	
</body>
</html>
