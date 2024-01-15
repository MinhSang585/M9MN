<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title>Super Lottery</title>
	<style type="text/css">
		body {background: #000; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/img/loading.gif');?>" border="0" />
	
	<form id="game-form" method="post" action="<?php echo (isset($url) ? $url : '');?>" class="hidden">
		<input type="hidden" name="PostData" value="<?php echo (isset($data) ? $data : '');?>" />
	</form>
	<script type="text/javascript">
		document.getElementById("game-form").submit();
	</script>	
</body>
</html>
