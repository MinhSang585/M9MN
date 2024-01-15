<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximum-scale=1,minimum-scale=1">
	<title>Playtech</title>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.latest.min.js');?>"></script>
	<script type="text/javascript" src="https://login.<?php echo $mobile_hub;?>.com/jswrapper/integration.js.php?casino=<?php echo $virtual_database;?>"></script>
	<script type="text/javascript">
	var ply_language = "<?php echo $language;?>";
	var ply_game_code = "<?php echo $game_code;?>";
	
	<?php if($play_fun == STATUS_YES):?>
	$(document).ready(function() {
		window.location = "https://cache.download.banner.<?php echo $virtual_database;?>.com/casinoclient.html?language=" + ply_language + "&game=" + ply_game_code + "&mode=offline";
	});
	<?php else:?>
	var ply_username = "<?php echo strtoupper($username);?>";
	var ply_password = "<?php echo $password;?>";
	var ply_redirect_url = "<?php echo $redirect_url;?>";
	iapiSetCallout('Login', calloutLogin);
	iapiSetCallout('Logout', calloutLogout);
	
	<?php if($mobile == STATUS_YES):?>
	var mobiledomain = "<?php echo $mobile_hub;?>";
	var systemidvar = "<?php echo $system_id;?>";
	iapiSetCallout('GetTemporaryAuthenticationToken', calloutGetTemporaryAuthenticationToken);
	<?php endif;?>
	
	function login(realMode) {
		<?php if($mobile == STATUS_YES):?>
		iapiSetClientPlatform("mobile&deliveryPlatform=HTML5");
		var realMode = 1;
		<?php endif;?>
		iapiLogin(ply_username, ply_password, realMode, ply_language);
	}

	function logout(allSessions, realMode) {
		iapiLogout(allSessions, realMode);
	}

	function calloutLogin(response) {
		if (response.errorCode) {
			alert("Login failed, " + response.errorText);
		}
		else {
			<?php if($mobile == STATUS_YES):?>
			askTempandLaunchGame(ply_game_code);
			<?php else:?>
			window.location = "https://cache.download.banner.<?php echo $virtual_database;?>.com/casinoclient.html?language=" + ply_language + "&game=" + ply_game_code;
			<?php endif;?>
		}
	}

	function calloutLogout(response) {
		if (response.errorCode) {
			alert("Logout failed, " + response.errorCode);
		}
		else {
			alert("Logout OK");
		}
	}

	<?php if($mobile == STATUS_YES):?>
	function askTempandLaunchGame(game) {
		currentgame = game;
		var realMode = 1;
		iapiRequestTemporaryToken(realMode, systemidvar, 'GamePlay');
	}

	function launchMobileClient(temptoken) {
		var clientUrl = 'https://hub.' + mobiledomain + '.com/igaming/' + '?gameId=' + currentgame + '&real=1' + '&username=' + ply_username + '&lang=' + ply_language + '&tempToken=' + temptoken + 
		'&lobby=' + ply_redirect_url + '&support=' + ply_redirect_url + '&logout=' + ply_redirect_url + '&deposit=' + ply_redirect_url;

		window.location = clientUrl;
	}

	function calloutGetTemporaryAuthenticationToken(response) {
		if (response.errorCode) {
			alert("Token failed. " + response.playerMessage + " Error code: " + response.errorCode);
		}
		else {
			launchMobileClient(response.sessionToken.sessionToken);
		}
	}
	<?php endif;?>
	
	$(document).ready(function() {
		login(1);
	});
	<?php endif;?>
	</script>
	<style type="text/css">
		body {background: #000; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/img/loading.gif');?>" border="0" />
</body>
</html>