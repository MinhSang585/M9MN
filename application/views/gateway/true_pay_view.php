<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<title>TruePay</title>
	<style type="text/css">
		body {background: #fff; margin: 0; padding: 0;}
		.loading {position: absolute; top: 50%; left: 50%; margin: -24px 0px 0px -24px;}
		.hidden {display: none;}
	</style>
</head>
<body>
	<img class="loading" src="<?php echo base_url('assets/ebv2/images/loading.gif');?>" border="0" />
	<?php
		$data = array(
			'Merchant' => (isset($paymend_gateway['Merchant']) ? $paymend_gateway['Merchant'] : ''),
			'Currency' => (isset($paymend_gateway['Currency']) ? $paymend_gateway['Currency'] : ''),
			'Customer' => (isset($deposit['player_id']) ? $deposit['player_id'] : '0'),
			'Reference' => (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : ''),
			'Amount' => (isset($deposit['amount_rate']) ? bcdiv($deposit['amount_rate'],1,2) : ''),
			'Note' => (isset($deposit['transaction_code']) ? $deposit['transaction_code'] : ''),
			'Datetime' => time(),
			'FrontURI' => (isset($paymend_gateway['ShowURL']) ? base_url('gateway/'.$paymend_gateway['ShowURL']) : ''),
			'BackURI' => (isset($paymend_gateway['NotifyURL']) ? base_url('gateway/'.$paymend_gateway['NotifyURL']) : ''),
			'Language' => ((get_language_folder()!="simplified") ? "en‐us" : "zn‐us"),
			'Bank' => (isset($deposit['payment_gateway_bank']) ? $deposit['payment_gateway_bank'] : ''),
			'ClientIP' => $this->input->ip_address(),
		);
		$plain_text = $data['Merchant'].$data['Reference'].$data['Customer'].$data['Amount'].$data['Currency'].date('YmdHis',$data['Datetime']).$paymend_gateway['key'].$data['ClientIP'];
		$hash = strtoupper(md5($plain_text));
	?>
	<form method="POST" name="myform" action="<?php echo $paymend_gateway['APIUrl'];?>" id="myform">
		<input type="hidden" name="Merchant" value="<?php echo $data['Merchant'];?>" />
		<input type="hidden" name="Currency" value="<?php echo $data['Currency'];?>" />
		<input type="hidden" name="Customer" value="<?php echo $data['Customer'];?>" />
		<input type="hidden" name="Reference" value="<?php echo $data['Reference'];?>" />
		<input type="hidden" name="Key" value="<?php echo $hash;?>" />
		<input type="hidden" name="Amount" value="<?php echo $data['Amount'];?>" />
		<input type="hidden" name="Note" value="<?php echo $data['Note'];?>"/>
		<input type="hidden" name="Datetime" value="<?php echo date('Y-m-d h:i:sA',$data['Datetime']);?>" />
		<input type="hidden" name="FrontURI" value="<?php echo $data['FrontURI'];?>"/> 
		<input type="hidden" name="BackURI" value="<?php echo $data['BackURI'];?>" /> 
		<input type="hidden" name="Language" value="<?php echo $data['Language'];?>" />
		<input type="hidden" name="Bank" value="<?php echo $data['Bank'];?>" />
		<input type="hidden" name="ClientIP" value="<?php echo $data['ClientIP'];?>"/>
	</form>
	<script type="text/javascript">
		document.getElementById("myform").submit();
	</script>	
</body>
</html>