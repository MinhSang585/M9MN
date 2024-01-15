<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callbacktest extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function fastspay($method = NULL){
		if($method == "payout"){
		    $payment_gateway_code = "FASTSPAY";
		    echo "hello";
		    /*
			$payment_gateway_code = "FASTSPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 4;
			$this->db->insert('payment_gateway_log',$prove_data);
            */
            $input_capture = '{"service_version":"3.0","billno":"P22030410005037","partner_orderid":"W6016463763516384","currency":"MYR","amount":"10000","account_name":"goh yin wei","account_number":"114209689523","bank_code":"MBB.MY","status":"000","fee":"150","bank_charge":"0","sign":"88321D0FEB2B9B83B091588393FD63815BA25D5B"}';
            $_POST = json_decode($input_capture,TRUE);
            ad($_POST);
			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$amount = trim($_POST['amount']);
			$account_name  = trim($_POST['account_name']);
			$account_number = trim($_POST['account_number']);
			$bank_code  = trim($_POST['bank_code']);
			$result = trim($_POST['status']);
			$fee  = trim($_POST['fee']);
			$bank_charge  = trim($_POST['bank_charge']);
			$error_code  = trim($_POST['error_code']);
			$error_description = trim($_POST['error_description']);

			$withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_code($transaction_code);
			if(!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING)
			{
			    ad($withdrawal_data);
				$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code,"11");
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&amount=".$amount."&account_name=".$account_name."&account_number=".$account_number."&bank_code=".$bank_code."&status=".$result."&fee=".$fee."&bank_charge=".$bank_charge."&key=".$paymend_gateway_data['key'];
					ad($signature_string);
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
					    echo "here";
						$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
						ad($player_data);
						
						if( ! empty($player_data))
						{
							if($result == "000")
							{
								$status_change = STATUS_APPROVE;

								$pData = array(
									'transaction_code' => $transaction_code,
									'order_no' => $billno,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);						
							}
							else
							{
								$status_change = STATUS_CANCEL;
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);

								if($status_change == STATUS_CANCEL){
									$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
									$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);	
								}
							}
							ad($pData);
							$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
							echo '<xml><billno>'.$billno.'</billno><status>OK</status></xml>';
						}
						
					}
				}
			}
		}
	}

	public function gspay2($method = NULL){
		if($method == "payout"){
			$payment_gateway_code = "GSPAY2";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 4;
			$this->db->insert('payment_gateway_log',$prove_data);

			$post = '{"withdrawalplus_id":61356,"transaction_id":"W00000000000020","account_name":"Miao even enterprise","account_number":"514383568916","amount":100.0000,"completed":true,"withdrawal_success":true,"remark":"Transaction Successful!","signature":"5a83cf332d511fc3ba76a51a5d0f6930"}';//file_get_contents('php://input');
			if( ! empty($post)){
				$arr = json_decode($post, TRUE);
				$success = $arr['withdrawal_success'];
				$completed = $arr['completed'];
				$payment_id = $arr['withdrawalplus_id'];
                $transaction_code_alias = $arr['transaction_id'];
                $account_name = $arr['account_name'];
                $account_number = $arr['account_number'];
                $amount = $arr['amount'];
                $remark = $arr['remark'];
                $sign = $arr['signature'];


                $withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_alias_code($transaction_code_alias);
                if(!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING)
				{
					$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code,"11");
					if(!empty($paymend_gateway)){
						$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					    $signature_string = $payment_id.$account_number.bcdiv($amount,1,2).$transaction_code_alias.$paymend_gateway_data['OperatorSecretKey'];
					    $hash = md5($signature_string);
					    if($hash == $sign)
					    {
					    	$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
					    	if(!empty($player_data))
							{
							    if($completed)
    							{
    							    if($success){
    							        $status_change = STATUS_APPROVE;

    									$pData = array(
    										'transaction_code' => $withdrawal_data['transaction_code'],
    										'order_no' => $payment_id,
    										'status' => $status_change,
    										'updated_by' => $player_data['username'],
    									);	
    							    }else{
    							        $status_change = STATUS_CANCEL;
    							        
    									$pData = array(
    										'transaction_code' => $withdrawal_data['transaction_code'],
    										'status' => $status_change,
    										'updated_by' => $player_data['username'],
    									);
    							    }
    							}else{
    							    $status_change = STATUS_CANCEL;
    							    
									$pData = array(
										'transaction_code' => $withdrawal_data['transaction_code'],
										'status' => $status_change,
										'updated_by' => $player_data['username'],
									);
    							}
    							
    							if($status_change == STATUS_CANCEL){
									//$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
									//$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);	
								}
    							
    							//$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
							}
					    }
					}
				}
			}
		}
	}
}