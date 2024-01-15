<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	public function cmd() 
	{
		$username = '';
		$status = 2;
		$message = 'Failed';
		if(isset($_GET['token']))
		{
			$token = trim($_GET['token']);
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token('CMD', $token);
				if( ! empty($player_acc_data))
				{
					$username = $player_acc_data['username'];
					$status = 0;
					$message = 'Success';
				}
			}	
		}
		$output = '<authenticate>';
		$output .= '<member_id>' . $username . '</member_id>';
		$output .= '<status_code>' . $status . '</status_code>';
		$output .= '<message>' . $message . '</message>';
		$output .= '</authenticate>';
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('text/xml', 'utf-8')
				->set_output($output)
				->_display();
		exit();
	}
	public function eb() 
	{
		$post = file_get_contents('php://input');
		$output = array(
			'status' => 410,
			'subChannelId' => 0,
			'accessToken' => '',
			'username' => '',
			'nickname' => '',
		);
		if( ! empty($post))
		{	
			$arr = json_decode($post, TRUE);
			$token = (isset($arr['accessToken']) ? trim($arr['accessToken']) : '');
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token('EB', $token);
				if( ! empty($player_acc_data))
				{
					$output['status'] = 200;
					$output['accessToken'] = $arr['accessToken'];
					$output['username'] = $player_acc_data['username'];
					$output['nickname'] = $player_acc_data['username'];
				}
			}	
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
		exit();
	}
	public function gpi() 
	{
		// $error_code = 3;
		// $error_msg = 'Authentication Failed';
		// $cust_id = '';
		// $cust_name = '';
		// $currency_code = '';
		// $language = '';
		// $test_cust = '';
		// $country = '';
		// $date_of_birth = '';
		// $ip = '';
		// if(isset($_GET['ticket']))
		// {
			// $token = trim($_GET['ticket']);
			// if( ! empty($token))
			// {
				// $player_acc_data = $this->player_model->get_player_game_username_by_token('GPI', $token);
				// if( ! empty($player_acc_data))
				// {
					// $sys_data = $this->miscellaneous_model->get_miscellaneous();
					// $error_code = 0;
					// $error_msg = 'Authentication Success';
					// $cust_id = $player_acc_data['username'];
					// $cust_name = $player_acc_data['username'];
					// $currency_code = $sys_data['system_currency'];
					// $language = $player_acc_data['language'];
					// $test_cust = (($player_acc_data['is_demo']) ? true : false);
					// $country = $sys_data['system_country'];
					// $date_of_birth = date('d-m-Y', strtotime('-22 years', time()));
					// $ip = $player_acc_data['ip_address'];
				// }
			// }	
		// }
		// $output = '<resp>';
		// $output .= '<error_code>' . $error_code . '</error_code>';
		// $output .= '<error_msg>' . $error_msg . '</error_msg>';
		// $output .= '<cust_id>' . $cust_id . '</cust_id>';
		// $output .= '<cust_name>' . $cust_name . '</cust_name>';
		// $output .= '<currency_code>' . $currency_code . '</currency_code>';
		// $output .= '<language>' . $language . '</language>';
		// $output .= '<test_cust>' . $test_cust . '</test_cust>';
		// $output .= '<country>' . $country . '</country>';
		// $output .= '<date_of_birth>' . $date_of_birth . '</date_of_birth>';
		// $output .= '<ip>' . $ip . '</ip>';
		// $output .= '</resp>';
		// //Output
		// $this->output
				// ->set_status_header(200)
				// ->set_content_type('text/xml', 'utf-8')
				// ->set_output($output)
				// ->_display();
		// exit();
		$data['error_msg'] = json_encode($_GET);
		$this->db->insert('bctp_game_api_logs', $data);
		$data2['error_msg'] = json_encode($_POST);
		$this->db->insert('bctp_game_api_logs', $data2);
		$data3['error_msg'] = json_encode($_REQUEST);
		$this->db->insert('bctp_game_api_logs', $data3);
	}
	public function icg() 
	{
	    /*
	    $prove_data['payment_gateway_code'] = "EA";
		$prove_data['input_get'] = json_encode($_GET);
		$prove_data['input_post'] = json_encode($_POST);
		$prove_data['input_request'] = json_encode($_REQUEST);
		$prove_data['input_json'] = file_get_contents("php://input");
		$prove_data['response_time'] = time();
		$prove_data['response_time'] = time();
		$prove_data['ip_address'] = $this->input->ip_address();
		$prove_data['input_type'] = 1;
		$this->db->insert('game_result_push_log',$prove_data);
		*/
		$output['data'] = array(
			'statusCode' => '1',
			'username' => ''
		);
		if(isset($_GET['token']))
		{
			$token = trim($_GET['token']);
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token('ICG', $token);
				if( ! empty($player_acc_data))
				{
					$output['data']['statusCode'] = 0;
					$output['data']['username'] = $player_acc_data['username'];
				}
			}		
		}
		if($output['data']['statusCode'] == 1)
		{
			$output['error'] = array(
				'title' => 'TOKEN_NOT_FOUND',
				'description' => 'Token not found'
			);
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
		exit();
	}
	public function lh() {
		
		$post = file_get_contents('php://input');
		#$dbdata2 = array('game_code'=>'LH','method'=>'authtest','input'=>json_encode($post));
		#$this->db->insert('game_api_logs', $dbdata2);
		$output = array(
			'loginName' => '',
		);
		if( ! empty($post)) {	
			$arr = json_decode($post, TRUE);			
			$token = (isset($arr['token']) ? trim($arr['token']) : '');
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token('LH', $token);
				if( ! empty($player_acc_data))
				{
					$output['loginName'] = $player_acc_data['username'];
				}
			}	
		}
		#Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		unset($output);	
		exit();
	}
	public function lh_test() {
		exit;
		$orin 	= array('token'=>'iRuMlq6BhBSdqeke9ak9miGu81nPcq99LWgDtp73pVsY0960SLnnp12ZpQehHgEc');
		$post = json_encode($orin);
		#$post = file_get_contents('php://input');
		$output = array(
			'loginName' => '',
		);
		if( ! empty($post)) {	
			$arr = json_decode($post, TRUE);
			$token = (isset($arr['token']) ? trim($arr['token']) : '');
			if( ! empty($token)) {
				$player_acc_data = $this->player_model->get_player_game_username_by_token('LH', $token);
				if( ! empty($player_acc_data)) {
					$output['loginName'] = $player_acc_data['username'];
				}
			}	
		}
		#Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		unset($output);
		exit();
	}
	public function n2() 
	{
		$post = file_get_contents('php://input');
		$output = '<?xml version="1.0" encoding="utf-16"?>';
		if( ! empty($post))
		{	
			$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $post);
			$xml = simplexml_load_string($xml_utf8);
			$json = json_encode($xml);
			$result_array = json_decode($json, TRUE);
			$action = (isset($result_array['@attributes']['action']) ? trim($result_array['@attributes']['action']) : '');
			if($action == 'userverf')
			{
				$darr = array(
					'action' => $action,
					'id' => (isset($result_array['element']['@attributes']['id']) ? trim($result_array['element']['@attributes']['id']) : ''),
					'status' => 'Fail',
					'userid' => '',
					'username' => '',
					'uuid' => '',
					'vendorid' => '',
					'merchantpasscode' => '',
					'clientip' => '',
					'currencyid' => '',
					'acode' => '',
					'errdesc' => 'ERR_INVALID_REQ',
					'status_code' => '001',
				);
				$username = (isset($result_array['element']['properties'][0]) ? trim($result_array['element']['properties'][0]) : '');
				$token = (isset($result_array['element']['properties'][1]) ? trim($result_array['element']['properties'][1]) : '');
				$client_ip = (isset($result_array['element']['properties'][2]) ? trim($result_array['element']['properties'][2]) : '');
				if( ! empty($token))
				{
					$provider_code = 'N2';
					$player_acc_data = $this->player_model->get_player_game_token_data($provider_code, $username);
					if( ! empty($player_acc_data))
					{
						if($token == $player_acc_data['token'])
						{
							// if($client_ip == $player_acc_data['ip_address'])
							// {
								// $game_data = $this->game_model->get_game_data($provider_code);
								//--Temp start
								$game_data = NULL;
								$query = $this
										->db
										->select('api_data')
										->where('game_code', $provider_code)
										->limit(1)
										->get('games');
								if($query->num_rows() > 0)
								{
									$game_data = $query->row_array();  
								}
								$query->free_result();
								//--Temp end
								$arr = json_decode($game_data['api_data'], TRUE);
								$darr['status'] = 'Success';
								$darr['userid'] = $player_acc_data['username'];
								$darr['username'] = $player_acc_data['username'];
								$darr['uuid'] = $player_acc_data['token'];
								$darr['vendorid'] = $arr['VendorId'];
								$darr['merchantpasscode'] = $arr['MerchantPassword'];
								$darr['clientip'] = $client_ip;
								$darr['currencyid'] = $arr['CurrencyId'];
								$darr['errdesc'] = '';
								$darr['status_code'] = '0';
							// }
							// else
							// {
								// $darr['errdesc'] = 'ERR_INVALID_IP';
								// $darr['status_code'] = '002';
							// }
						}
					}
					else
					{
						$darr['errdesc'] = 'ERR_INVALID_ACCOUNT_ID';
						$darr['status_code'] = '101';
					}
				}
				$output .= '<message>';
				$output .= '<status>' . $darr['status'] . '</status>';
				$output .= '<result action="' . $darr['action'] . '">';
				$output .= '<element id="' . $darr['id'] . '">';
				$output .= '<properties name="userid">' . $darr['userid'] . '</properties>';
				$output .= '<properties name="username">' . $darr['username'] . '</properties>';
				$output .= '<properties name="uuid">' . $darr['uuid'] . '</properties>';
				$output .= '<properties name="vendorid">' . $darr['vendorid'] . '</properties>';
				$output .= '<properties name="merchantpasscode">' . $darr['merchantpasscode'] . '</properties>';
				$output .= '<properties name="clientip">' . $darr['clientip'] . '</properties>';
				$output .= '<properties name="currencyid">' . $darr['currencyid'] . '</properties>';
				$output .= '<properties name="acode">' . $darr['acode'] . '</properties>';
				$output .= '<properties name="errdesc">' . $darr['errdesc'] . '</properties>';
				$output .= '<properties name="status">' . $darr['status_code'] . '</properties>';
				$output .= '</element>';
				$output .= '</result>';
				$output .= '</message>';
			}
			else if($action == 'clogout')
			{
				$darr = array(
					'action' => $action,
					'id' => (isset($result_array['element']['@attributes']['id']) ? trim($result_array['element']['@attributes']['id']) : ''),
					'status' => 'Fail',
					'errdesc' => 'ERR_XML_INPUT',
					'status_code' => '801',
				);
				$username = (isset($result_array['element']['properties'][0]) ? trim($result_array['element']['properties'][0]) : '');
				if( ! empty($username))
				{
					$provider_code = 'N2';
					$player_acc_data = $this->player_model->get_player_game_token_data($provider_code, $username);
					if( ! empty($player_acc_data))
					{
						$this->player_model->update_player_game_token($provider_code, $player_acc_data['username'], '');
						$darr['status'] = 'Success';
						$darr['errdesc'] = '';
						$darr['status_code'] = '0';
					}
				}
				$output .= '<message>';
				$output .= '<status>' . $darr['status'] . '</status>';
				$output .= '<result action="' . $darr['action'] . '">';
				$output .= '<element id="' . $darr['id'] . '">';
				$output .= '<properties name="status">' . $darr['status_code'] . '</properties>';
				$output .= '<properties name="errdesc">' . $darr['errdesc'] . '</properties>';
				$output .= '</element>';
				$output .= '</result>';
				$output .= '</message>';
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('text/xml', 'utf-8')
				->set_output($output)
				->_display();
		exit();
	}
	public function sg() 
	{
		$post = file_get_contents('php://input');
		$output = array();
		if( ! empty($post))
		{	
			$arr = json_decode($post, TRUE);
			$token = (isset($arr['token']) ? trim($arr['token']) : '');
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token('SG', $token);
				if( ! empty($player_acc_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$output = array(
						'acctInfo' => array(
							'acctId' => trim($arr['acctId']),
							'balance' => 0,
							'userName' => trim($arr['acctId']),
							'currency' => $sys_data['system_currency'],
							'siteId' => trim($arr['merchantCode']) . '_' . $sys_data['system_currency'],
						),
						'merchantCode' => trim($arr['merchantCode']),
						'msg' => 'success',
						'code' => 0,
						'serialNo' => trim($arr['serialNo']),
					);
				}
			}	
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
		exit();
	}
	public function pgs2() 
	{
	    $output = array(
	        'data' => null,
	        'error' => array(
	            "code" => "1034",
	            "message" => "Invalid request",
	        ),
		);
	    $provider_code = "PGS2";
	    if( ! empty($_POST))
		{
		    $token = (isset($_POST['operator_player_session']) ? trim($_POST['operator_player_session']) : '');
    		$operator_token = (isset($_POST['operator_token']) ? trim($_POST['operator_token']) : '');
    		$secret_key = (isset($_POST['secret_key']) ? trim($_POST['secret_key']) : '');
    		if( ! empty($token))
    		{
    			$player_acc_data = $this->player_model->get_player_game_username_by_token($provider_code, $token);
    			if( ! empty($player_acc_data))
    			{
    			    $sys_data = $this->miscellaneous_model->get_miscellaneous();
    				$game_data = NULL;
    		
    				$query = $this
    						->db
    						->select('api_data')
    						->where('game_code', $provider_code)
    						->limit(1)
    						->get('games');
    				
    				if($query->num_rows() > 0)
    				{
    					$game_data = $query->row_array();  
    				}
    				$query->free_result();
    				
    				if( ! empty($game_data))
    			    {
    					//--Temp end
    					$arr = json_decode($game_data['api_data'], TRUE);
    					if($arr['OperatorToken'] == $operator_token && $arr['SecretKey'] == $secret_key){
    					    $output = array(
    					        'data' => array(
    					            'player_name' => $player_acc_data['username'],
    					            'nickname' => $player_acc_data['username'],
        					        'currency' => $arr['Currency'],
    					        ),
    					        'error' => null,
        					);   
    					}
    			    }
    			}
    		}      
		}
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();
	}
	
	public function pgs3() 
	{
	    $output = array(
	        'data' => null,
	        'error' => array(
	            "code" => "1034",
	            "message" => "Invalid request",
	        ),
		);
	    $provider_code = "PGS3";
	    if( ! empty($_POST))
		{
		    $token = (isset($_POST['operator_player_session']) ? trim($_POST['operator_player_session']) : '');
    		$operator_token = (isset($_POST['operator_token']) ? trim($_POST['operator_token']) : '');
    		$secret_key = (isset($_POST['secret_key']) ? trim($_POST['secret_key']) : '');
    		if( ! empty($token))
    		{
    			$player_acc_data = $this->player_model->get_player_game_username_by_token($provider_code, $token);
    			if( ! empty($player_acc_data))
    			{
    			    $sys_data = $this->miscellaneous_model->get_miscellaneous();
    				$game_data = NULL;
    		
    				$query = $this
    						->db
    						->select('api_data')
    						->where('game_code', $provider_code)
    						->limit(1)
    						->get('games');
    				
    				if($query->num_rows() > 0)
    				{
    					$game_data = $query->row_array();  
    				}
    				$query->free_result();
    				
    				if( ! empty($game_data))
    			    {
    					//--Temp end
    					$arr = json_decode($game_data['api_data'], TRUE);
    					if($arr['OperatorToken'] == $operator_token && $arr['SecretKey'] == $secret_key){
    					    $output = array(
    					        'data' => array(
    					            'player_name' => $player_acc_data['username'],
    					            'nickname' => $player_acc_data['username'],
        					        'currency' => $arr['Currency'],
    					        ),
    					        'error' => null,
        					);   
    					}
    			    }
    			}
    		}      
		}
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();
	}
	public function ea() 
	{
	    $prove_data['payment_gateway_code'] = "EA";
		$prove_data['input_get'] = json_encode($_GET);
		$prove_data['input_post'] = json_encode($_POST);
		$prove_data['input_request'] = json_encode($_REQUEST);
		$prove_data['input_json'] = file_get_contents("php://input");
		$prove_data['response_time'] = time();
		$prove_data['response_time'] = time();
		$prove_data['ip_address'] = $this->input->ip_address();
		$prove_data['input_type'] = 1;
		$this->db->insert('payment_gateway_log',$prove_data);
		$post = file_get_contents('php://input');
		$output = '<?xml version="1.0" encoding="utf-16"?>';
		if( ! empty($post))
		{	
			$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $post);
			$xml = simplexml_load_string($xml_utf8);
			$json = json_encode($xml);
			$result_array = json_decode($json, TRUE);
			$action = (isset($result_array['@attributes']['action']) ? trim($result_array['@attributes']['action']) : '');
			if($action == 'userverf')
			{
				$darr = array(
					'action' => $action,
					'id' => (isset($result_array['element']['@attributes']['id']) ? trim($result_array['element']['@attributes']['id']) : ''),
					'status' => 'Fail',
					'userid' => '',
					'username' => '',
					'uuid' => '',
					'vendorid' => '',
					'merchantpasscode' => '',
					'clientip' => '',
					'currencyid' => '',
					'acode' => '',
					'errdesc' => 'ERR_INVALID_REQ',
					'status_code' => '001',
				);
				$username = (isset($result_array['element']['properties'][0]) ? trim($result_array['element']['properties'][0]) : '');
				$token = (isset($result_array['element']['properties'][1]) ? trim($result_array['element']['properties'][1]) : '');
				$client_ip = (isset($result_array['element']['properties'][2]) ? trim($result_array['element']['properties'][2]) : '');
				if( ! empty($token))
				{
					$provider_code = 'EA';
					$player_acc_data = $this->player_model->get_player_game_token_data($provider_code, $username);
					if( ! empty($player_acc_data))
					{
						if($token == $player_acc_data['token'])
						{
							// if($client_ip == $player_acc_data['ip_address'])
							// {
								// $game_data = $this->game_model->get_game_data($provider_code);
								//--Temp start
								$game_data = NULL;
								$query = $this
										->db
										->select('api_data')
										->where('game_code', $provider_code)
										->limit(1)
										->get('games');
								if($query->num_rows() > 0)
								{
									$game_data = $query->row_array();  
								}
								$query->free_result();
								//--Temp end
								$arr = json_decode($game_data['api_data'], TRUE);
								$darr['status'] = 'Success';
								$darr['userid'] = $player_acc_data['username'];
								$darr['username'] = $player_acc_data['username'];
								$darr['uuid'] = $player_acc_data['token'];
								$darr['vendorid'] = $arr['VendorId'];
								$darr['clientip'] = $client_ip;
								$darr['currencyid'] = $arr['CurrencyId'];
								$darr['errdesc'] = '';
								$darr['status_code'] = '0';
							// }
							// else
							// {
								// $darr['errdesc'] = 'ERR_INVALID_IP';
								// $darr['status_code'] = '002';
							// }
						}
					}
					else
					{
						$darr['errdesc'] = 'ERR_INVALID_ACCOUNT_ID';
						$darr['status_code'] = '101';
					}
				}
				$output .= '<response action="' . $darr['action'] . '">';
				$output .= '<element id="' . $darr['id'] . '">';
				$output .= '<properties name="userid">' . $darr['userid'] . '</properties>';
				$output .= '<properties name="username">' . $darr['username'] . '</properties>';
				$output .= '<properties name="uuid">' . $darr['uuid'] . '</properties>';
				$output .= '<properties name="vendorid">' . $darr['vendorid'] . '</properties>';
				$output .= '<properties name="clientip">' . $darr['clientip'] . '</properties>';
				$output .= '<properties name="currencyid">' . $darr['currencyid'] . '</properties>';
				$output .= '<properties name="acode">' . $darr['acode'] . '</properties>';
				$output .= '<properties name="errdesc">' . $darr['errdesc'] . '</properties>';
				$output .= '<properties name="status">' . $darr['status_code'] . '</properties>';
				$output .= '</element>';
				$output .= '</response>';
				$prove_data['payment_gateway_code'] = "EAR";
        		$prove_data['input_get'] = "";
        		$prove_data['input_post'] = "";
        		$prove_data['input_request'] = "";
        		$prove_data['input_json'] = $output;
        		$prove_data['response_time'] = time();
        		$prove_data['response_time'] = time();
        		$prove_data['ip_address'] = $this->input->ip_address();
        		$prove_data['input_type'] = 1;
        		$this->db->insert('payment_gateway_log',$prove_data);
			}
			else if($action == 'clogout')
			{
				$darr = array(
					'action' => $action,
					'id' => (isset($result_array['element']['@attributes']['id']) ? trim($result_array['element']['@attributes']['id']) : ''),
					'status' => 'Fail',
					'errdesc' => 'ERR_XML_INPUT',
					'status_code' => '801',
				);
				$username = (isset($result_array['element']['properties'][0]) ? trim($result_array['element']['properties'][0]) : '');
				if( ! empty($username))
				{
					$provider_code = 'N2';
					$player_acc_data = $this->player_model->get_player_game_token_data($provider_code, $username);
					if( ! empty($player_acc_data))
					{
						$this->player_model->update_player_game_token($provider_code, $player_acc_data['username'], '');
						$darr['status'] = 'Success';
						$darr['errdesc'] = '';
						$darr['status_code'] = '0';
					}
				}
				$output .= '<response action="' . $darr['action'] . '">';
				$output .= '<element id="' . $darr['id'] . '">';
				$output .= '<properties name="status">' . $darr['status_code'] . '</properties>';
				$output .= '<properties name="errdesc">' . $darr['errdesc'] . '</properties>';
				$output .= '</element>';
				$output .= '</response>';
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('text/xml', 'utf-8')
				->set_output($output)
				->_display();
		exit();
	}
	public function naga(){
	    $provider_code = "NAGA";
	    $post = file_get_contents('php://input');
		$output = array(
			'data' => array(
			   'nativeId' =>  '',
			   'currency' =>  '',  
			 ),
		);
        $error_output = array(
            'data' => null,
            'error' => array(
                'statusCode' => 404,
                'message' => "User Not Found"
            ),
        );
		if( ! empty($post))
		{	
			$arr = json_decode($post, TRUE);
			$token = (isset($arr['data']['playerToken']) ? trim($arr['data']['playerToken']) : '');
			if( ! empty($token))
			{
				$player_acc_data = $this->player_model->get_player_game_username_by_token($provider_code, $token);
				if( ! empty($player_acc_data))
				{
					$game_data = NULL;
					$query = $this
							->db
							->select('api_data')
							->where('game_code', $provider_code)
							->limit(1)
							->get('games');
					if($query->num_rows() > 0)
					{
						$game_data = $query->row_array();  
					}
					$query->free_result();
					//--Temp end
					$arr = json_decode($game_data['api_data'], TRUE);
					$output['data']['nativeId'] = $player_acc_data['username'];
					$output['data']['currency'] = $arr['Currency'];
					$output['data']['balance'] = 0;
				}else{
				    $output = $error_output;
				}
			}else{
			    $output = $error_output;
			}
		}else{
		    $output = $error_output;
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
		exit();
	}
	
	public function ps(){
	    $game_provider_code = "PS";
	    $segment_one 	= $this->uri->segment(3);
		$segment_two 	= $this->uri->segment(4);
		
		$output = array(
    		'status_code' => 1,
		);
		
		if($segment_one == "auth"){
		    $output = array(
    			'status_code' => 1,
    			'member_id' => "",
    		);
		    if(isset($_GET['access_token'])){
		        $token = trim($_GET['access_token']);
		        if( ! empty($token))
    			{
    				$player_acc_data = $this->player_model->get_player_game_username_by_token($game_provider_code, $token);
    				if( ! empty($player_acc_data))
    				{
    				    $output['status_code'] = 0;
    				    $output['member_id'] = $player_acc_data['username'];
    				}
    			}
		    }
		}else if($segment_two == "logout"){
		    $output = array(
    			'status_code' => 1,
    		);
		    if(isset($_GET['access_token'])){
		        $token = trim($_GET['access_token']);
		        if( ! empty($token))
    			{
    				$player_acc_data = $this->player_model->get_player_game_username_by_token($game_provider_code, $token);
    				if( ! empty($player_acc_data))
    				{
    				    $output['status_code'] = 0;
    				    $this->player_model->update_player_game_token($game_provider_code, $player_acc_data['username'], "");
    				}
    			}
		    }
		}else if($segment_two == "signin"){
		    $output = array(
    			'status_code' => 1,
    			'asscess_token' => "",
    		);
    		
		    $username = ((isset($_GET['member_id'])) ? $_GET['member_id'] : "");
		    $password = ((isset($_GET['password'])) ? $_GET['password'] : "");
		    if(!empty($username)){
		        $player_acc_data = $this->player_model->get_player_detail_by_game_id_data($game_provider_code,$username);
    		    if( ! empty($player_acc_data))
        		{
        		    if($player_acc_data['password'] == $password){
            		    $this->load->library('rng');
    					$partner_member_token = $this->rng->get_token(64);
    					$this->player_model->update_player_game_token($player_acc_data['game_provider_code'], $player_acc_data['game_id'], $partner_member_token);
    					$output['status_code'] = 0;
    					$output['asscess_token'] = $partner_member_token;   
        		    }
        		}   
		    }
		}else if($segment_two == "changepwd"){
		    $output = array(
    			'status_code' => 1,
    		);
    		
		    $username = ((isset($_GET['member_id'])) ? $_GET['member_id'] : "");
		    $password = ((isset($_GET['password'])) ? $_GET['password'] : "");
		    $new_password = ((isset($_GET['new_password'])) ? $_GET['new_password'] : "");
		    if(!empty($username)){
		        $player_acc_data = $this->player_model->get_player_detail_by_game_id_data($game_provider_code,$username);
    		    if( ! empty($player_acc_data))
        		{
        		    if($player_acc_data['password'] == $password){
        		        $DBdata = array(
                			'password' => $new_password,
                		);
                		
                		$this->db->where('game_provider_code', $game_provider_code);
                		$this->db->where('game_id', $username);
                		$this->db->limit(1);
                		$this->db->update('player_game_accounts', $DBdata);
    					$output['status_code'] = 0;   
        		    }
        		}   
		    }
		}
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();
	}
}