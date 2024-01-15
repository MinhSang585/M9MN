<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title>EeziePay</title>
	<style type="text/css">
		body {background: #fff; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/ebv2/images/loading.gif');?>" border="0" style="display:none;">
	<?php
		$signature_string = "service_version=".$paymend_gateway['service_version']."&partner_code=".$paymend_gateway['partner_code']."&partner_orderid=".(isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '')."&member_id=".(isset($deposit['player_id']) ? $deposit['player_id'] : '')."&member_ip=".$this->input->ip_address()."&currency=".(isset($paymend_gateway['BankCurrency']) ? $paymend_gateway['BankCurrency'] : '')."&amount=".(isset($deposit['amount_rate']) ? str_replace('.','',(string)$deposit['amount_rate']) : '')."&backend_url=".(isset($paymend_gateway['NotifyURL']) ? base_url('gateway/'.$paymend_gateway['NotifyURL']) : '')."&redirect_url=".(isset($paymend_gateway['ShowURL']) ? base_url('gateway/'.$paymend_gateway['ShowURL']) : '')."&bank_code=".(isset($deposit['payment_gateway_bank']) ? $deposit['payment_gateway_bank'] : '').".".(isset($paymend_gateway['BankCode']) ? $paymend_gateway['BankCode'] : '')."&key=".$paymend_gateway['key'];
		$hash = sha1($signature_string);
		$hash_upper = strtoupper($hash);
	?>
	<form method="POST" name="myform" action="<?php echo $paymend_gateway['APIUrl'];?>" id="myform">
		<input type="text" hidden name="service_version" value="<?php echo $paymend_gateway['service_version'];?>" />
		<input type="text" hidden name="partner_code" value="<?php echo $paymend_gateway['partner_code'];?>" />
		<input type="text" hidden name="partner_orderid" value="<?php echo (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');?>" />
		<input type="text" hidden name="member_id" value="<?php echo (isset($deposit['player_id']) ? $deposit['player_id'] : '');?>" />
		<input type="text" hidden name="member_ip" value="<?php echo $this->input->ip_address();?>" />
		<input type="text" hidden name="currency" value="<?php echo (isset($paymend_gateway['BankCurrency']) ? $paymend_gateway['BankCurrency'] : '');?>" />
		<input type="text" hidden name="amount" value="<?php echo (isset($deposit['amount_rate']) ? str_replace('.','',(string)$deposit['amount_rate']) : '');?>" />
		<input type="text" hidden name="backend_url" value="<?php echo (isset($paymend_gateway['NotifyURL']) ? base_url('gateway/'.$paymend_gateway['NotifyURL']) : '');?>"/>
		<input type="text" hidden name="redirect_url" value="<?php echo (isset($paymend_gateway['ShowURL']) ? base_url('gateway/'.$paymend_gateway['ShowURL']) : '');?>" />
		<input type="text" hidden name="bank_code" value="<?php echo (isset($deposit['payment_gateway_bank']) ? $deposit['payment_gateway_bank'] : '').".".(isset($paymend_gateway['BankCode']) ? $paymend_gateway['BankCode'] : '');?>" />
		<input type="text" hidden name="sign" value="<?php echo $hash_upper;?>" />
		<input type="text" hidden name="remarks" value="<?php echo (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : '');?>" />
	</form>
	<script type="text/javascript">
		document.getElementById("myform").submit();
	</script>	
</body>
</html>