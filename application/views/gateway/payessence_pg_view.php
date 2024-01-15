<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title>PayEssence</title>
	<style type="text/css">
		body {background: #fff; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/ebv2/images/loading.gif');?>" border="0" />
	
	<?php
		$name_arr = (isset($player['full_name']) ? explode(' ', $player['full_name']) : '');
		$first_name = (isset($name_arr[0]) ? $name_arr[0] : 'ivy');
		$last_name = (isset($name_arr[1]) ? $name_arr[1] : 'chong');
		$email = (isset($player['email']) ? $player['email'] : 'ryan@wedoops.com');
		$mobile = (isset($player['mobile']) ? $player['mobile'] : '0169204890');
		
		$concate = PG_PE_MERCHANT_CODE . PG_PE_SECRET_KEY . (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');
		$hash = hash('sha256', $concate, true);
		$hex = bin2hex($hash);
	?>
	<form id="payment-form" class="hidden" method="post" action="<?php echo PG_PE_API_URL;?>">
		<input name="MerchantCode" value="<?php echo PG_PE_MERCHANT_CODE;?>">
		<input name="TransNum" value="<?php echo (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');?>">
		<input name="Currency" value="<?php echo (isset($setting['system_currency']) ? $setting['system_currency'] : '');?>">
		<input name="Amount" value="<?php echo (isset($deposit['amount']) ? $deposit['amount'] : '');?>">
		<input name="PaymentDesc" value="Payment Desc: <?php echo (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');?>">
		<input name="FirstName" value="<?php echo $first_name;?>">
		<input name="LastName" value="<?php echo $last_name;?>">
		<input name="EmailAddress" value="<?php echo $email;?>">
		<input name="PhoneNum" value="<?php echo $mobile;?>">
		<input name="Address" value="Kuala Lumpur">
		<input name="City" value="Kuala Lumpur">
		<input name="State" value="KUL">
		<input name="Country" value="MYS">
		<input name="Postcode" value="59000">
		<input name="MerchantRemark" value="<?php echo (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');?>">
		<input name="CheckString" value="<?php echo $hex;?>">
	</form>
	<script type="text/javascript">
		document.getElementById("payment-form").submit();
	</script>	
</body>
</html>