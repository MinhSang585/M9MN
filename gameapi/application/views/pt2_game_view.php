<!DOCTYPE html> 
<html lang="en"> 
<head> 
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>Playtech</title> 
	<style type="text/css">
		body {background: #000; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body> 
	<img class="loading" src="<?php echo base_url('assets/img/loading.gif');?>" border="0" />
	
	<!-- We do not support testing games via localhost --> 
	<!-- Please host this html file on your server and assign one domain with it, remeber that domain needs to be whitelisted otherwise you will get error code 6 --> 
	<!-- You can try testing games with fun mode.it doesnot require login.--> 
	<div class="hidden"> 
		<label for="username">Username:</label> <input type="text" id="username" name="username" value="<?php echo strtoupper($username);?>"><br><br> 
		<label for="password">Password:</label> <input type="text" id="password" name="password" value="<?php echo $password;?>"><br><br> 
		<label for="lang">Language:</label> <input type="text" id="lang" name="lang" value="<?php echo $lang;?>"><br><br> 
		<label for="client">Client:</label> <input type="text" id="client" name="client" value="<?php echo $client;?>" size="50"><br><br> 
		<label for="mode">Mode:</label> <input type="text" id="mode" name="mode" value="<?php echo $mode;?>"><br><br> 
		<label for="game">Game Code:</label> <input type="text" id="game" name="game" value="<?php echo $game;?>"><br><br> 
		<label for="lobby">Lobby:</label> <input type="text" id="lobby" name="lobby" value="<?php echo $lobby;?>"><br><br> 
	</div> 
	<script>
		function login() { 
			// Get variables 
			let username = document.getElementById("username").value; 
			let password = document.getElementById("password").value; 
			let lang = document.getElementById("lang").value; 
			let mode = document.getElementById("mode").value; 
			
			if (mode == 'real') { 
				iapiSetClientType('casino'); 
				iapiSetClientPlatform('web'); 
				iapiLogin(username, password, 1, lang); 
			} 
			else { 
				// mode is offline, which does not require login. NOTE: only supports client with ngm_desktop and ngm_mobile 
				launchGameWithFunMode(); 
			}
		}
				
		function launchGame() {
			// Get variables 
			let client = document.getElementById("client").value; 
			let mode = document.getElementById("mode").value; 
			let game = document.getElementById("game").value; 
			let lang = document.getElementById("lang").value; 
			let real = (mode == 'real') ? 1 : 0; 
			// Optional Variables 
			let lobbyUrl = document.getElementById("lobby").value; 
			let logoutUrl = ''; 
			let supportUrl = ''; 
			let depositUrl = ''; 
			
			// Slots,Table Games and other non-live games 
			if (client == 'ngm_desktop' || client == 'ngm_mobile') { 
				iapiSetClientParams(client, 'language=' + lang + '&real=' + real + '&lobby=' + lobbyUrl + '&logout=' + logoutUrl + '&deposit=' + depositUrl + '&support=' + supportUrl + '&backurl=' + lobbyUrl); 
				iapiLaunchClient(client, game, mode, '_self'); 
			} 
			
			// Live Games 
			if (client == 'live_desk' || client == 'live_mob') {
				iapiSetClientParams(client, '&launch_alias=' + game + '&language=' + lang + '&real=' + real + '&lobby=' + lobbyUrl + '&logout=' + logoutUrl + '&deposit=' + depositUrl + '&support=' + supportUrl); 
				iapiLaunchClient(client, null, mode, '_self'); 
			} 
		} 
		
		function launchGameWithFunMode() { 
			// Get variables 
			let client = document.getElementById("client").value; 
			let game = document.getElementById("game").value; 
			let lang = document.getElementById("lang").value; 
			let mode = document.getElementById("mode").value; 
			
			if (client == 'ngm_desktop' || client == 'ngm_mobile') {
				iapiSetClientParams(client, 'language=' + lang + '&real=0');
				iapiLaunchClient(client, game, mode, '_self'); 
			} 
		} 
		
		function calloutLogin(response) { 
			if (response.errorCode) { 
				// Login failed 
				if (response.errorCode == 48) { 
					alert('Login failed, error: ' + response.errorCode + ' playerMessage: ' + response.actions.PlayerActionShowMessage[0].message); 
				} 
				else { 
					alert('Login failed, error: ' + response.errorCode + ' playerMessage: ' + response.playerMessage); 
				} 
			} 
			else { 
				// Login success 
				launchGame(); 
			} 
		} 
	</script> 
	<script> 
		// Load JS file 
		let script = document.createElement('script'); 
		script.setAttribute('src', 'https://login-am.<?php echo $mobile_hub;?>/jswrapper/<?php echo $virtual_database;?>/integration.js'); 
		document.head.appendChild(script); 
		
		// Set up callback after JS file is loaded 
		script.onload = () => {
		    iapiSetCallout('Login', calloutLogin);
		    //add if use backup link
            iapiConf.clientUrl_ngm_desktop = 'https://cachedownload-am.<?php echo $mobile_hub;?>/ngmdesktop/casinoclient.html';
            iapiConf.clientUrl_ngm_mobile = 'https://games-am.<?php echo $mobile_hub;?>/casinomobile/casinoclient.html';
            iapiConf.clientUrl_live_desk = 'https://cachedownload-am.<?php echo $mobile_hub;?>/live/html5/desktop/';
            iapiConf.clientUrl_live_mob = 'https://cachedownload-am.<?php echo $mobile_hub;?>/live/html5/mobile/';
		} 
		
		setTimeout(function(){ login(); }, 10000);
	</script> 
</body> 
</html>