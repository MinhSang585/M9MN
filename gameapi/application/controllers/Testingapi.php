<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testingapi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function xg_testing(){
	    $game_data = NULL;
		$provider_code = "GX";
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
		ad($game_data);
		$arr = json_decode($game_data['api_data'],true);
		ad($arr);
		$url = $arr['APIUrl'];
		$url .= "/xg-casino/Login";
    		    
		$lang = 'en-US';
	
		$param_array = array(
            "Account" => 'bdhtestacc01',
            "Lang" => $lang,
            "UIVersion" => $arr['UIVersion'],
        );
	    $keyP = urldecode(http_build_query($param_array, '', '&'))."&AgentId=".$arr['AgentID'];
	    ad("Key P : ".$keyP);
        $keyA = rand(pow(10, $arr['FrontKey']-1), pow(10, $arr['FrontKey'])-1);
        ad("Key A : ".$keyA);
        $keyC = rand(pow(10, $arr['BackKey']-1), pow(10, $arr['BackKey'])-1);
        ad("Key C : ".$keyC);
        $keyT = date("ymj", strtotime('-12 hours', time()));
        ad("Key T : ".$keyT);
        $keyG = md5($keyT.$arr['AgentID'].$arr['AgentKey']);
        ad("Key G Plain: ".$keyT.$arr['AgentID'].$arr['AgentKey']);
        ad("Key G : ".$keyG);
        $key = $keyA . md5($keyP.$keyG).$keyC;
        ad("Key : ".$key);
        $param_array['AgentId'] = $arr['AgentID'];
        $param_array['Key'] = $key;
        $url .= "?" . http_build_query($param_array);
        ad("URL : ".$url);
        $response = $this->curl_get_json($url);
        ad($response);
	}
    
    public function sdsd2(){
        $input = '{"code":423,"data":"","uuid":"216021d5df04552ff4d1211b6b55913d","msg":"\u4e0a\u5c64\u9918\u984d\u4e0d\u8db3: -100"}';
        ad(json_decode($input,true));
    }
    
    public function ea($method = NULL){
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "EA",
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => 100,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "EA";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
        
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();

		$url = '';
		$xml = '';
		$xml_2 = '';
		$xml_utf8 = '';
		$xml_utf8_2 = '';
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
            if($method == 'cm'){
                $player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
    			if(empty($player_acc_data))
    			{
    				$output['errorCode'] = ERROR_SUCCESS;
    				$output['errorMessage'] = $this->lang->line('error_success');
    				$output['gameID'] = $post_data['username'];
    				$output['gamePassword'] = $post_data['password'];
    				$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
    			}
    			else
    			{
    				$output['errorCode'] = ERROR_USERNAME_EXITS;
    				$output['errorMessage'] = $this->lang->line('error_username_already_exits');
    			}
            }else if($method == 'li'){
                $url = (($post_data['device'] == PLATFORM_WEB) ? $arr['WebRoot'] : $arr['MobileRoot']);
			
    			$language = '3';
    			
    			switch($post_data['syslang'])
    			{
    				case LANG_ZH_CN: $language = '1'; break;
    				case LANG_ZH_HK:
    				case LANG_ZH_TW: $language = '2'; break;
    				case LANG_ID: $language = '12'; break;
    				case LANG_TH: $language = '8'; break;
    				case LANG_VI: $language = '11'; break;
    				case LANG_JA: $language = '5'; break;
    			}
    			
    			if($post_data['is_demo'] == STATUS_YES)
    			{
    				
    			}
    			else
    			{
    				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
    				if( ! empty($player_acc_data))
    				{
    					$this->load->library('rng');
    					$partner_member_token = $this->rng->get_token(50);
    					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token, $lang, $post_data['is_demo'], $this->input->ip_address());
    					
    					$output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['result'] = $url . '?userid=' . $post_data['username'] . '&uuid=' . $partner_member_token . '&lang=' . $language;
    				}
    				else
    				{
    					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					$output['errorMessage'] = $this->lang->line('error_username_not_found');
    				}
    			}
            }else if($method == 'gb'){
                $url = $arr['CheckClientUrl'];
    			$xml .= '<?xml version="1.0" encoding="utf-16"?>';
    			$xml .= '<request action="ccheckclient">';
    			$xml .= '<element id="C' . time() . rand(10000, 99999) . '">';
    			$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
    			$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
    			$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
    			$xml .= '</element>';
    			$xml .= '</request>';
    			
    			$response = $this->curl_xml($url, $xml);
    			$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
    				$xml_output = simplexml_load_string($xml_utf8);
    				$json = json_encode($xml_output);
    				$result_array = json_decode($json, TRUE);
    				if(isset($result_array['element']['properties'][2]) && ($result_array['element']['properties'][2] == '0' || $result_array['element']['properties'][2] == '1111'))
    				{
    				    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['element']['properties'][1], 1, 2);    
    				}
    				else if(isset($result_array_2['element']['properties'][2]) && $result_array_2['element']['properties'][2] == '203')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
            }else if($method == 'cb'){
                if($post_data['amount'] > 0) 
    			{
    				$requestOrderIDAlias = $post_data['order_id'];
    				$url = $arr['DepositUrl'];
    				$xml .= '<?xml version="1.0" encoding="utf-16"?>';
    				$xml .= '<request action="cdeposit">';
    				$xml .= '<element id="D' . time() . rand(10000, 99999) . '">';
    				$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
    				$xml .= '<properties name="acode"></properties>';
    				$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
    				$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
    				$xml .= '<properties name="amount">' . $post_data['amount'] . '</properties>';
    				$xml .= '<properties name="refno">' . $requestOrderIDAlias . '</properties>';
    				$xml .= '</element>';
    				$xml .= '</request>';
    			}
    			else
    			{
    				$requestOrderIDAlias = $post_data['order_id'];
    				$url = $arr['WithdrawalUrl'];
    				$xml .= '<?xml version="1.0" encoding="utf-16"?>';
    				$xml .= '<request action="cwithdrawal">';
    				$xml .= '<element id="W' . time() . rand(10000, 99999) . '">';
    				$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
    				$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
    				$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
    				$xml .= '<properties name="amount">' . bcdiv(($post_data['amount'] * -1), 1, 2) . '</properties>';
    				$xml .= '<properties name="refno">' . $requestOrderIDAlias . '</properties>';
    				$xml .= '</element>';
    				$xml .= '</request>';
    			}
    			$response = $this->curl_xml($url, $xml);
    			$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
    				$xml_output = simplexml_load_string($xml_utf8);
    				$json = json_encode($xml_output);
    				$result_array = json_decode($json, TRUE);
    				if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '0')
					{
					    if($post_data['amount'] > 0) 
    					{
            				$url_2 = $arr['DepositUrl'];
    						$xml_2 = '<?xml version="1.0" encoding="utf-16"?>';
    						$xml_2 .= '<request action="cdeposit-confirm">';
    						$xml_2 .= '<element id="' . $result_array['element']['@attributes']['id'] . '">';
    						$xml_2 .= '<properties name="acode"></properties>';
    						$xml_2 .= '<properties name="status">0</properties>';
    						$xml_2 .= '<properties name="paymentid">' . $result_array['element']['properties'][1] . '</properties>';
    						$xml_2 .= '<properties name="errdesc"></properties>';
    						$xml_2 .= '</element>';
    						$xml_2 .= '</request>';
    						$response_2 = $this->curl_xml($url_2, $xml_2);
        			        $curl_array_2 = $response_2['curl'];
        			        if($response_2['code'] == '0')
        		        	{
        		        	    $xml_utf8_2 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response_2['data']);
    							$xml_output_2 = simplexml_load_string($xml_utf8_2);
    							$json_2 = json_encode($xml_output_2);
    							$result_array_2 = json_decode($json_2, TRUE);
    							if(empty($result_array_2))
    					        {
    					            $output['errorCode'] = ERROR_SUCCESS;
    								$output['errorMessage'] = $this->lang->line('error_success');
    								$output['result'] = 0;
    					        }
                                else if(isset($result_array_2['element']['properties'][2]) && $result_array_2['element']['properties'][2] == '203')
            					{
            					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
            						$output['errorMessage'] = $this->lang->line('error_username_not_found');
            					}
            					else if(isset($result_array_2['element']['properties'][2]) && $result_array_2['element']['properties'][2] == '204')
            					{
            					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
            						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
            					}
        		        	}
    					}else{
    					    if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '0')
    						{
    							$output['errorCode'] = ERROR_SUCCESS;
    							$output['errorMessage'] = $this->lang->line('error_success');
    							$output['result'] = bcdiv($result_array['result']['element']['properties'][4], 1, 2);
    						}
    						else if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '203')
    						{
    							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    							$output['errorMessage'] = $this->lang->line('error_username_not_found');
    						}
    						else if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '204')
    						{
    							$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    							$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    						}
    					}
					}
					else if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '203')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['element']['properties'][2]) && $result_array['element']['properties'][2] == '204')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
    			}
            }else if($method == 'lo'){
            }else if($method == "bet"){
                //$initial_time = "2022-04-22 10:05:00";
                //$initial_time = "2022-04-22 11:35:00";
                $initial_time = "2022-04-22 13:45:00";
                
                $Bdata = array();
                $start_time = strtotime($initial_time);
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
                
                $url = '';
        		$xml = '';
        		$start_date = date('Y-m-d H:i:s',$start_time);
        		$end_date = date('Y-m-d H:i:s',$end_time);
        		/*
        		$minute_convert = intval($arr['TimeZone']) - 480;
        		$start_date = date('Y-m-d H:i:s', ($start_time+($minute_convert*60)));
        		$end_date = date('Y-m-d H:i:s', ($end_time+($minute_convert*60)));
        		*/
        		$url = 	$arr['GameInfoUrl'];
        		$xml .= '<?xml version="1.0" encoding="utf-16"?>';
        		$xml .= '<request action="gameinfo">';
        		$xml .= '<element id="GA' . time() . rand(10000, 99999) . '">';
        		$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
        		$xml .= '<properties name="startdate">'.$start_date.'</properties>';
        		$xml .= '<properties name="enddate">'.$end_date.'</properties>';
        		$xml .= '<properties name="timezone">' . $arr['TimeZone'] . '</properties>';
        		$xml .= '</element>';
        		$xml .= '</request>';
        		$response = $this->curl_xml_n2($url, $xml,'Accept-Encoding: gzip, deflate, br');
        		if($response['code'] == '0')
				{
					$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
					$xml_output = simplexml_load_string($xml_utf8);
					$json = json_encode($xml_output);
					$result_array = json_decode($json, TRUE);
					//ad($result_array);
					if(isset($result_array['element']['status']) && $result_array['element']['status'] == 'Success')
					{
					    if(sizeof($result_array['element']['result'])>0){
					        if(isset($result_array['element']['result']['game']['@attributes'])){
						        $game_result_data[0] = $result_array['element']['result']['game'];
						    }else{
						        $game_result_data = $result_array['element']['result']['game'];
						    }
						    foreach($game_result_data as $game_type_row){
						        if(isset($game_type_row['@attributes']['code']) && isset($game_type_row['deal'])){
						            $result_data = array();
						            if(isset($game_type_row['deal']['@attributes'])){
						                //one data;
						                $result_data[0] = $game_type_row['deal'];
						            }else{
						                $result_data = $game_type_row['deal'];
						            }
						            
						            
						            $game_code_data = array(
						                '1001' => "Baccarat",
						                '90001' => "Baccarat",
						                '1002' => "Baccarat",
						                '90002' => "Baccarat",
						                '2001' => "Roulette",
						                '50003' => "Roulette",
						                '3001' =>  "Sicbo",
						                '60001' =>  "Sicbo",
						                '4001' => "Blackjack",
						                '5001' => "Win Three Cards",
						                '5002' => "Win Three Cards",
						                '5003' => "Poker",
						                '5004' =>  "Pok Deng",
						                '6001' => "Andar Bahar",
						                '6002' => "Andar Bahar",
						                '101501' => "Bull Bull",
						                '101502' => "Bull Bull",
						                '102501' => "Dragon Tiger",
						                '102502' => "Dragon Tiger",
						            );
						            
						            
						            $game_code = (isset($game_code_data[trim($game_type_row['@attributes']['code'])]) ? $game_code_data[trim($game_type_row['@attributes']['code'])] : "Other");
						            
						            foreach($result_data  as $result_row){
						                $bet_data = array();
						                if(isset($result_row['betinfo']['clientbet']['@attributes'])){
						                    $bet_data[0] = $result_row['betinfo']['clientbet'];
						                }else{
						                    $bet_data = $result_row['betinfo']['clientbet'];
						                }
						                foreach($bet_data as $bet_row){
						                    $status = STATUS_COMPLETE;
    						                $tmp_username = strtolower(trim($bet_row['@attributes']['login']));
    										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
    										$PBdata = array(
        										'game_provider_code' => $provider_code,
        										'game_type_code' => GAME_LIVE_CASINO,
        										'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
        										'game_result_type' => $result_type,
        										'game_code' => $game_code,
        										'game_real_code' => $game_type_row['@attributes']['code'],
        										'bet_id' => trim($bet_row['@attributes']['betid']),
        										'bet_time' => strtotime(trim($result_row['@attributes']['startdate'])),
        										'bet_amount' => trim($bet_row['@attributes']['bet_amount']),
        										'bet_amount_valid' => trim($bet_row['@attributes']['valid_turnover']),
        										'payout_time' => strtotime(trim($result_row['@attributes']['startdate'])),
        										'sattle_time' => strtotime(trim($result_row['@attributes']['enddate'])),
        										'compare_time' => strtotime(trim($result_row['@attributes']['startdate'])),
        										'game_time' => strtotime(trim($result_row['@attributes']['enddate'])),
        										'created_date' => time(),
        										'win_loss' => trim($bet_row['@attributes']['hold']),
        										'payout_amount' => (trim($bet_row['@attributes']['bet_amount']) +  trim($bet_row['@attributes']['hold'])),
        										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
        										'status' => $status,
        										'game_username' => trim($bet_row['@attributes']['login']),
        										'round' => trim($result_row['@attributes']['code']),
        										'table_id' => trim($result_row['@attributes']['id']),
        										'player_id' => $member_lists[$exact_username],
        									);
        									
        									if($game_code == "Bull Bull"){
        									    $temporary_bet_amount = 0;
        									    if(isset($bet_row['betdetail']) && sizeof($bet_row['betdetail'])>0){
        									        foreach($bet_row['betdetail'] as $bet_row_key => $bet_row_value){
        									            if($bet_row_key != "@attributes"){
        									                if(strpos($bet_row_key, 'double')){
        									                    $temporary_bet_amount += ($bet_row_value/5);
        									                }else{
        									                    $temporary_bet_amount += $bet_row_value;
        									                }
        									            }
        									        }
        									    }
        									    $PBdata['bet_amount'] = $temporary_bet_amount;
        									    $PBdata['payout_amount'] = $temporary_bet_amount + $PBdata['win_loss'];
        									}
        									$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
						                }
						            }
						        }
						    }
						    
						    
					    }
					}
					
					ad($Bdata);
				}
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function obsb($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
		//dge00000000003_tb2
        $post_data =  array(
            "provider_code" => "OBSB",
            'game_id' => "dge00000000001_tb2",
            'player_id' => 1,
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => 100,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "OBSB";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/

        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		$url = $arr['APIUrl'];
            $requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
    		
            if($method == 'cm'){
                $url .= "/api_func.php?request=create_user";
                $param_array = array(
        			"agent_userid" => $arr['AgentID'],
        			"customer_userid" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        			"customer_name" => $post_data['username'],
        			"customer_status" => 1,
        			"tickets_credits" => 0,
        		);
        		
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if(isset($result_array['status']) && $result_array['status'] == '1')
				    {
				        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['gameID'] = $result_array['customer_userid'];
					    $output['gamePassword'] = $post_data['password'];
				    }
				    else if(isset($result_array['status']) && $result_array['status'] == '-4'){
				        $output['errorCode'] = ERROR_USERNAME_EXITS;
					    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				    }
        		}
            }else if($method == 'li'){
                $url .= "/api_func.php?request=get_api_token";
    			
    			$param_array = array(
        			"customer_userid" => $post_data['game_id'],
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if(isset($result_array['status']) && $result_array['status'] == '1')
				    {
				        $lang = 'en';
			
            			switch($post_data['syslang'])
            			{
            				case LANG_ZH_CN: $lang = 'cn'; break;
        					case LANG_ZH_HK:
        					case LANG_ZH_TW: $lang = 'tw'; break;
            			}
				        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = $arr['ForwardUrl'] . '/api_token.php?api_token=' . $result_array['api_token'] . '&lang=' . $lang;
				    }
				    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
				        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				    }
        		}
            }else if($method == 'gb'){
                $url .= "/api_func.php?request=check_user_point";
                $param_array = array(
        			"customer_userid" => $post_data['game_id'],
        		);
        		
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if(isset($result_array['status']) && $result_array['status'] == '1')
				    {
				        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['tickets_credits'], 1, 2);
				    }
				    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
				        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				    }
        		}
            }else if($method == 'cb'){
                $url .= "/api_func.php?request=modify_user_point";
                $requestOrderIDAlias = $post_data['order_id'];
                
                $param_array = array(
        			"customer_userid" => $post_data['game_id'],
        			"credits_type" => 2,
        			"add_point" => $post_data['amount'],
        		);
        		
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if(isset($result_array['status']) && $result_array['status'] == '1')
				    {
				        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['tickets_credits'], 1, 2);
				    }
				    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
				        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		    }
        		    else if(isset($result_array['status']) && $result_array['status'] == '-5'){
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
        		    }
        		    else if(isset($result_array['status']) && $result_array['status'] == '-6'){
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
        		    }
        		    else if(isset($result_array['status']) && $result_array['status'] == '-7'){
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['status']) && $result_array['status'] == '-100'){
						$output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}
					else if(isset($result_array['status']))
				    {
				        
				    }else{
				        $output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
				    }
        		}
            }else if($method == 'lo'){
                $url .= "/api_func.php?request=kick_users";
                
                $param_array = array(
        			"customer_userid_str" => $post_data['game_id'],
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if(isset($result_array['status']) && $result_array['status'] == '1')
				    {
        			    $output['errorCode'] = ERROR_SUCCESS;
			            $output['errorMessage'] = $this->lang->line('error_success');
				    }
        		}
            }else if($method == "bet"){
                $sys_data = $this->miscellaneous_model->get_miscellaneous();
                $initial_time = "2022-08-08 17:00:00";
                $start_time = strtotime($initial_time);
                
                $url .= "/api_func.php?request=get_not_finished_order_detail";
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
    				
                $param_array = array(
        			"agent_userid" => $arr['AgentID'],
        			"sbet_datetime" => date("Y-m-d H:i:s",$start_time),
        			"ebet_datetime" => date("Y-m-d H:i:s",$end_time),
        		);
        		ad($url);
        		ad($param_array);
        		$response = $this->curl_post($url, $param_array);
        		ad($response);
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        		    $url = $arr['APIUrl']."/api_func.php?request=get_history_has_finished_order_detail";
                    $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
        				
                    $param_array_2 = array(
            			"agent_userid" => $arr['AgentID'],
            			"sfinished_datetime" => date("Y-m-d H:i:s",$start_time),
            			"efinished_datetime" => date("Y-m-d H:i:s",$end_time),
            		);
            		ad($url);
            		ad($param_array_2);
            		$response_2 = $this->curl_post($url, $param_array_2);
            		if($response_2['code'] == '0')
        		    {
        		        $result_array_2 = json_decode($response_2['data'], TRUE);
        		        $DBdata['resp_data'] = json_encode(array_merge($result_array,$result_array_2));
        		        echo "asas";
        		        ad(array($result_array,$result_array_2));
        		        ad($DBdata['resp_data']);
        		        
        		        
        		        if($result_array['status'] == "1" && $result_array_2['status'] == "1"){
        		            $all_result = array_merge($result_array['orders_detail'],$result_array_2['orders_detail']);
        		            ad(json_encode($all_result,true));
        		            foreach($all_result as $result_row){
        		                
        		                
        		                ad($result_row);
        		                //ad(explode("_",trim($result_row['customer_userid'])));
        		                
        		                //$tmp_username = strtolower(str_replace($arr['OperatorID'].'_','',));
								//$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
								
								
        		            }
        		        }
        		    }
        		}
            }else if($method = "bet_all"){
                
                $game_code_data = array(
                    '1' => "Baseball",
                    '2' => "Soccer",
                    '3' => "Baseball",
                    '4' => "Baseball",
                    '5' => "Baseball",
                    '6' => "Ice Hockey",
                    '7' => "Basketball",
                    '8' => "Horse Race",
                    '9' => "Baseball",
                    '10' => "Ice Hockey",
                    '11' => "Basketball",
                    '13' => "Soccer",
                    '14' => "Soccer",
                    '15' => "Soccer",
                    '16' => "E-Sports",
				);
			    $BdataID = array();
			    $Bdata = array();
			    $BUdata  = array();
			    $BUIDdata = array();
			    
                $sys_data = $this->miscellaneous_model->get_miscellaneous();
                $all_result = json_decode('[{"order_id":"50587287","customer_userid":"dge00000000001_tb2","customer_win_amount_no_retreat":95,"customer_retreat":0,"order_no":"165096499202","league_name":"KBO-\u97d3\u570b\u8077\u68d2","event_id":"21665","game_category":"5","game_type":"1","bet_type":"1","bet_index":"1","bet_index_group":"","rank1":"\u767b\u9678\u8005","rank2":"\u6a02\u5929\u5de8\u4eba","bet_rank":"\u767b\u9678\u8005","strong_index":"1","bet_hscore":"2","bet_hpercent":"50","bet_odds":"0.950000","bet_amount":"100","bet_real_amount":"100.00","order_status":"1","order_extra_status":"0","game_start_datetime":"2022-04-26 17:30:00","billing_date":"2022-04-27","rank1_score":"8","rank2_score":"1","bet_ip":"2001:f40:90f:208:7884:720a:2ef5:bc59","bet_datetime":"2022-04-26 17:23:12","notes":"","game_store":"0","is_mobile":"0","credits_type":"2","finished_datetime":"2022-04-26 21:15:04"},{"order_id":"50587306","customer_userid":"dge00000000001_tb2","customer_win_amount_no_retreat":-100,"customer_retreat":0,"order_no":"165096504601","league_name":"","event_id":"0","game_category":"5","game_type":"7","bet_type":"0","bet_index":"0","bet_index_group":"","rank1":"","rank2":"","bet_rank":"","strong_index":"0","bet_hscore":"0","bet_hpercent":"0","bet_odds":"0.000000","bet_amount":"100","bet_real_amount":"100.00","order_status":"1","order_extra_status":"0","game_start_datetime":"0000-00-00 00:00:00","billing_date":"2022-04-27","rank1_score":"0","rank2_score":"0","bet_ip":"2001:f40:90f:208:7884:720a:2ef5:bc59","bet_datetime":"2022-04-26 17:24:06","notes":"","game_store":"0","is_mobile":"0","credits_type":"2","finished_datetime":"2022-04-26 21:15:06","cross_order_detail":[{"info_id":"6201594","league_name":"KBO-\u97d3\u570b\u8077\u68d2","event_id":"21665","game_category":"5","game_type":"1","bet_type":"1","rank1":"\u767b\u9678\u8005","rank2":"\u6a02\u5929\u5de8\u4eba","bet_rank":"\u767b\u9678\u8005","strong_index":"1","bet_index":"1","bet_hscore":"2","bet_hpercent":"50","bet_odds":"0.900","game_start_datetime":"2022-04-26 17:30:00","billing_date":"2022-04-27","rank1_score":"8","rank2_score":"1","win_percent":"100","info_status":"1","notes":""},{"info_id":"6201595","league_name":"KBO-\u97d3\u570b\u8077\u68d2","event_id":"21701","game_category":"5","game_type":"1","bet_type":"2","rank1":"\u8010\u514b\u68ee\u82f1\u96c4","rank2":"\u83ef\u8001\u9df9","bet_rank":"\u5927","strong_index":"1","bet_index":"1","bet_hscore":"7","bet_hpercent":"-50","bet_odds":"0.890","game_start_datetime":"2022-04-26 17:30:00","billing_date":"2022-04-27","rank1_score":"2","rank2_score":"5","win_percent":"-50","info_status":"1","notes":""},{"info_id":"6201596","league_name":"KBO-\u97d3\u570b\u8077\u68d2","event_id":"21674","game_category":"5","game_type":"1","bet_type":"1","rank1":"\u6050\u9f8d","rank2":"\u9b25\u5c71\u718a","bet_rank":"\u6050\u9f8d","strong_index":"2","bet_index":"1","bet_hscore":"0","bet_hpercent":"-10","bet_odds":"0.900","game_start_datetime":"2022-04-26 17:30:00","billing_date":"2022-04-27","rank1_score":"4","rank2_score":"8","win_percent":"-100","info_status":"1","notes":""}]},{"order_id":"50587287","customer_userid":"dge00000000001_tb2","customer_win_amount_no_retreat":95,"customer_retreat":0,"order_no":"165096499202","league_name":"KBO-\u97d3\u570b\u8077\u68d2","event_id":"21665","game_category":"5","game_type":"1","bet_type":"1","bet_index":"1","bet_index_group":"","rank1":"\u767b\u9678\u8005","rank2":"\u6a02\u5929\u5de8\u4eba","bet_rank":"\u767b\u9678\u8005","strong_index":"1","bet_hscore":"2","bet_hpercent":"50","bet_odds":"0.950000","bet_amount":"100","bet_real_amount":"100.00","order_status":"1","order_extra_status":"0","game_start_datetime":"2022-04-26 17:30:00","billing_date":"2022-04-27","rank1_score":"8","rank2_score":"1","bet_ip":"2001:f40:90f:208:7884:720a:2ef5:bc59","bet_datetime":"2022-04-26 17:23:12","notes":"","game_store":"0","is_mobile":"0","credits_type":"2","finished_datetime":"2022-04-26 21:15:04"}]',true);
                foreach($all_result as $result_row){
                    ad($result_row);
                    $tmp_username = strtolower(explode("_",trim($result_row['customer_userid']))[0]);
                    $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
                    
                    if($result_row['order_status'] == "1"){
                        $status = STATUS_COMPLETE;
                    }else{
                        $status = STATUS_CANCEL;
                    }
                    
                    $PBdata = array(
						'game_provider_code' => $provider_code,
						'game_type_code' => GAME_SPORTSBOOK,
						'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
						'game_result_type' => $result_type,
						'game_code' => (isset($game_code_data[trim($result_row['game_category'])]) ? $game_code_data[trim($result_row['game_category'])] : "Other"),
						'game_real_code' => trim($result_row['game_category']),
						'bet_id' => trim($result_row['order_id']),
						'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['bet_datetime']))),
						'bet_amount' => trim($result_row['bet_amount']),
						'bet_amount_valid' => trim($result_row['bet_real_amount']),
						'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
						'win_loss' => trim($result_row['customer_win_amount_no_retreat']) + trim($result_row['customer_retreat']),
						'game_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
						'report_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
						'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
						'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
						'created_date' => time(),
						'payout_amount' => 0,
						'promotion_amount' => 0,
						'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
						'odds_currency' => "OT",
						'odds_rate' => trim($result_row['bet_odds']),
						'status' => $status,
						'game_username' => trim($result_row['customer_userid']),
						'player_id' =>  $member_lists[$exact_username],
						'bet_code' => $result_row['game_type'],
						'game_result' => json_encode($result_row),
					);
					if($status == STATUS_COMPLETE){
						$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
						if($PBdata['win_loss'] != 0){
							$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
				    	}
					}else{
						$PBdata['payout_amount'] = 0;
					}
					
					
					
					if( ! in_array($PBdata['bet_id'], $BdataID)){
					    array_push($BdataID, $PBdata['bet_id']);
					    if( ! in_array($PBdata['bet_id'], $transaction_lists))
    					{					
    					    $PBdata['game_result'] = NULL;
    						$PBdata['bet_info'] = json_encode($result_row);
    				        $PBdata['insert_type'] = SYNC_DEFAULT;
    						array_push($Bdata, $PBdata);
    					}else{
    					    $PBdata['game_result'] = NULL;
    						$PBdata['bet_update_info'] = json_encode($result_row);
    				        $PBdata['update_type'] = SYNC_DEFAULT;
    						array_push($BUdata, $PBdata);
    						array_push($BUIDdata, $PBdata['bet_id']);
    					}
    					
    					if($PBdata['status'] == STATUS_COMPLETE){
    						$PBdataWL = array(
    							'player_id' => $PBdata['player_id'],
    							'payout_time' => $PBdata['payout_time'],
    							'game_provider_code' => $PBdata['game_provider_code'],
    							'game_type_code' => $PBdata['game_type_code'],
    							'total_bet' => 1,
    							'bet_amount' => $PBdata['bet_amount'],
    							'bet_amount_valid' => $PBdata['bet_amount_valid'],
    							'win_loss' => $PBdata['win_loss'],
    						);
    						array_push($BUDdata, $PBdataWL);
    					}
					}
					
				
					
					/*
					if( ! in_array($PBdata['bet_id'], $transaction_lists))
					{					
					    $PBdata['game_result'] = NULL;
						$PBdata['bet_info'] = json_encode($result_row);
				        $PBdata['insert_type'] = SYNC_DEFAULT;
						array_push($Bdata, $PBdata);
					}else{
					    $PBdata['game_result'] = NULL;
						$PBdata['bet_update_info'] = json_encode($result_row);
				        $PBdata['update_type'] = SYNC_DEFAULT;
						array_push($BUdata, $PBdata);
						array_push($BUIDdata, $PBdata['bet_id']);
					}

					if($PBdata['status'] == STATUS_COMPLETE){
						$PBdataWL = array(
							'player_id' => $PBdata['player_id'],
							'payout_time' => $PBdata['payout_time'],
							'game_provider_code' => $PBdata['game_provider_code'],
							'game_type_code' => $PBdata['game_type_code'],
							'total_bet' => 1,
							'bet_amount' => $PBdata['bet_amount'],
							'bet_amount_valid' => $PBdata['bet_amount_valid'],
							'win_loss' => $PBdata['win_loss'],
						);
						array_push($BUDdata, $PBdataWL);
					}
					*/
					$this->db->insert_batch('transaction_report', $Bdata);
					ad($PBdata);
                }
                ad($BdataID);
                ad($Bdata);
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function sp_give_lt_point($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "SPLT",
            "player_id" => 1,
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => 10000000,
            "order_id" => time(),
            "game_code" => "",
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "SPLT";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
            
            if($method == 'cb'){
                $url .= "api_101/points";
                if($post_data['amount'] > 0) 
    			{
    				
                    $param_array = array(
            			"act" => "add",
            			"up_acc" => $arr['Account'],
            			"up_pwd" => $arr['Password'],
            			"account" => $arr['UpAccount'],
            			"Point" => $post_data['amount'],
            			"track_id" => $requestOrderIDAlias,
            		);
            		ad($param_array);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
            		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			else
    			{
    				$param_array = array(
            			"act" => "sub",
            			"up_acc" => $arr['Account'],
            			"up_pwd" => $arr['Password'],
            			"account" => $arr['UpAccount'],
            			"Point" => bcdiv(($post_data['amount'] * -1), 1, 2),
            			"track_id" => $requestOrderIDAlias,
            		);
            		ad($param_array);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
            		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			
        		$response = $this->curl_post($url, $param_array);
        		ad($response);
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    ad($result_array);
        		}
            }else if($method == 'gb'){
                $url .= "api_101/points";
                $param_array = array(
        			"act" => "read",
        			"up_acc" => $arr['Account'],
        			"up_pwd" => $arr['Password'],
        			"account" => $arr['Account'],
        		);
        		ad($param_array);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
        		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		ad($response);
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    ad($result_array);
        		}
            }else{
                echo "error";      
            }
        }
    }
    
    public function spsb($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "SPSB",
            "player_id" => 2,
            "username" => "bttdev021",
            "password" => "b121231a",
            "amount" => -100,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "SPSB";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
    		
            if($method == 'cm'){
                $url .= "api/account";
                
                $param_array = array(
        			"act" => "add",
        			"up_account" => $arr['UpAccount'],
        			"up_passwd" => $arr['UpPassword'],
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        			"passwd" => $post_data['password'],
        			"nickname" => $post_data['username'],
        			"level" => 1
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
        		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
						$output['gamePassword'] = $post_data['password'];
					}
					else if(isset($result_array['code']) && $result_array['code'] == '912')
					{
					    $output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
        		}
            }else if($method == 'li'){
                if($post_data['is_demo'] == STATUS_YES)
			    {
			        
			    }else{
			        $url .= "api/login";
                    $language = 'EN-US';
    			
        			switch($post_data['syslang'])
        			{
        				case LANG_ZH_CN: $language = 'ZH-CN'; break;
        				case LANG_ZH_HK:
        				case LANG_ZH_TW: $language = 'ZH-TW'; break;
        				case LANG_TH: $language = 'TH'; break;
        				case LANG_VI: $language = 'VI'; break;
        			}
                    
                    $param_array = array(
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"passwd" => $post_data['password'],
            			"responseFormat" => "json",
            			"lang" => $language,
            		);
            		
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['account'] = $aes->encrypt($param_array['account']);
            		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
            		$response = $this->curl_post($url, $param_array);
            		$curl_array = $response['curl'];
            		
            		if($response['code'] == '0')
            		{
                        $result_array = json_decode($response['data'], TRUE);
                        if(isset($result_array['code']) && $result_array['code'] == '999')
    					{
    					    $output['errorCode'] = ERROR_SUCCESS;
    						$output['errorMessage'] = $this->lang->line('error_success');
    						$output['result'] = $result_array['data']['login_url'];
    					}
    					else if(isset($result_array['code']) && $result_array['code'] == '903')
    					{
    					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    						$output['errorMessage'] = $this->lang->line('error_username_not_found');
    					}
    					else if(isset($result_array['code']) && $result_array['code'] == '904')
    					{
    					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    						$output['errorMessage'] = $this->lang->line('error_username_not_found');
    					}
            		}   
			    }
            }else if($method == 'gb'){
                $url .= "api/points";
                
                $param_array = array(
        			"act" => "search",
        			"up_account" => $arr['UpAccount'],
        			"up_passwd" => $arr['UpPassword'],
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
        		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
        		}
            }else if($method == 'cb'){
                $url .= "api/points";
                $requestOrderIDAlias = $arr['UpAccount'].str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(10000000,99999999);
                
                if($post_data['amount'] > 0) 
    			{
    				
                    $param_array = array(
            			"act" => "add",
            			"up_account" => $arr['UpAccount'],
            			"up_passwd" => $arr['UpPassword'],
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"point" => $post_data['amount'],
            			"track_id" => $requestOrderIDAlias,
            		);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
            		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			else
    			{
    				$param_array = array(
            			"act" => "sub",
            			"up_account" => $arr['UpAccount'],
            			"up_passwd" => $arr['UpPassword'],
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"point" => bcdiv(($post_data['amount'] * -1), 1, 2),
            			"track_id" => $requestOrderIDAlias,
            		);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
            		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			
    			$response = $this->curl_post($url, $param_array);
    			$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $is_checking = FALSE;
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '921')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '921')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == 'exception')
					{
					    $is_checking = TRUE;
					}
					else if(isset($result_array['code'])){
					    
					}else{
					    $is_checking = TRUE;
					}
					
					if($is_checking){
					    $output['errorCode'] = ERROR_OVERTIME;
				        $output['errorMessage'] = $this->lang->line('error_overtime');
				        
				        $url_2 = $arr['APIUrl'];
				        $url_2 .= "api/points";
				        
					    $param_array_2 = array(
                			"act" => "checking",
                			"up_account" => $arr['UpAccount'],
                			"up_passwd" => $arr['UpPassword'],
                			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
                			"track_id" => $requestOrderIDAlias,
                		);
                		$this->load->library('aes_ecb');
                		$aes = new Aes_ecb();
                		$aes->set_mode(MCRYPT_MODE_CBC);
                		$aes->set_iv($arr['IVkey']);
                		$aes->set_key($arr['Deskey']);
                		$aes->require_pkcs5();
                		$param_array_2['up_account'] = $aes->encrypt($param_array_2['up_account']);
                		$param_array_2['up_passwd'] = $aes->encrypt($param_array_2['up_passwd']);
                		$param_array_2['account'] = $aes->encrypt($param_array_2['account']);
                		$response_2 = $this->curl_post($url_2, $param_array_2);
    			        $curl_array_2 = $response_2['curl'];
    			        if($response_2['code'] == '0')
        		        {
        		            $result_array_2 = json_decode($response_2['data'], TRUE);
        		            if(isset($result_array_2['code']) && $result_array_2['code'] == '999')
					        {
					            if(isset($result_array_2['result']) && $result_array_2['result'] == '1')
					            {
					                $output['errorCode'] = ERROR_SUCCESS;
            						$output['errorMessage'] = $this->lang->line('error_success');
            						$output['result'] = "0.00";
					            }
					            else if(isset($result_array_2['result']) && $result_array_2['result'] == '0')
					            {
					                $output['errorCode'] = ERROR_SYSTEM_ERROR;
            						$output['errorMessage'] = $this->lang->line('error_system_error');
					            }
					        }
        		        }
					}
        		}
            }else if($method == 'lo'){
                $url .= "api/logout";
                
                $param_array = array(
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        		);
        		
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
        		}
            }else if($method == "bet"){
                //$initial_time = "2022-07-05 00:00:00";
                //$initial_time = "2022-05-25 00:00:00";
                //$initial_time = "2022-07-26 00:00:00";
                $initial_time = "2022-07-28 00:00:00";
                $start_time = strtotime($initial_time);
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+43200 minutes', strtotime($initial_time))));
                
                //$initial_time = "2022-07-26 10:05:00";
                //$initial_time = "2022-07-25 16:35:00";
                //$initial_time = "2022-07-13 00:00:00";
                //$start_time = strtotime($initial_time);
                //$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1400 minutes', strtotime($initial_time))));
                
                
                $game_code_data = array(
                    '1' => "Baseball",
                    '2' => "Baseball",
                    '3' => "Baseball",
                    '4' => "Baseball",
                    '5' => "Ice Hockey",
                    '6' => "Basketball",
                    '7' => "Lottory",
                    '8' => "US Football",
                    '9' => "Tennis",
                    '10' => "Football",
                    '11' => 'Index',
                    '12' => "Horse Race",
                    '13' => "E-Sports",
                    '14' => "Olympics",
                );
                $arr['UpAccount'] = "FG51";
                $url .= "api/report";
                
                
                $param_array = array(
        			"act" => "detail",
        			"account" => $arr['UpAccount'],
        			"level" => 2,
        			"s_date" => date('Y-m-d',$start_time),
        			"e_date" => date('Y-m-d',$end_time),
        		);
        		ad($param_array);
        		/*
        		$param_array = array(
        			"act" => "detail",
        			"account" => $arr['UpAccount'],
        			"level" => 2,
        			"s_date" => date('Y-m-d',$start_time),
        			"e_date" => date('Y-m-d',$end_time),
        			"start_time" => date('H:i:s',$start_time),
        			"end_time" => date('H:i:s',$end_time),
        		);
        		*/
        		
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		//ad($param_array);
        		//ad($url);
        		//ad($response);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
        		    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
				           foreach($result_array['data'] as $result_row){
				                ad($result_row);
			                    $tmp_username = strtolower(trim($result_row['m_id']));
								$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);
								
								$status = STATUS_PENDING;
								$win_result = STATUS_UNKNOWN;
								
								if($result_row['end'] == "1"){
								    if($result_row['status_note'] == "Y"){
								        $status = STATUS_COMPLETE;
								        if($result_row['status'] == "w"){
								            $win_result = STATUS_WIN;
								        }else if($result_row['status'] == "l"){
								            $win_result = STATUS_LOSS;
								        }else{
								            $win_result = STATUS_TIE;
								        }
								    }else{
								        $status = STATUS_CANCEL;
								    }
                                }
								
								$PBdata = array(
									'game_provider_code' => $provider_code,
									'game_type_code' => GAME_SPORTSBOOK,
									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
									'game_result_type' => $result_type,
									'game_code' => (isset($game_code_data[trim($result_row['team_no'])]) ? $game_code_data[trim($result_row['team_no'])] : "Other"),
									'game_real_code' => trim($result_row['team_no']),
									'bet_id' => trim($result_row['sn']),
									'bet_transaction_id' => trim($result_row['gameSN']),
									'bet_ref_no' => trim($result_row['gsn']),
									'bet_match_id' => (isset($result_row['match_id']) ? trim($result_row['match_id']) : "0"),
									'bet_time' => strtotime(trim($result_row['m_date'])),
									'bet_amount' => trim($result_row['gold']),
									'bet_amount_valid' => trim($result_row['bet_gold']),
									'payout_time' => strtotime(trim($result_row['payout_time'])),
									'win_loss' => trim($result_row['result_gold']),
									'game_time' => strtotime(trim($result_row['payout_time'])),
									'report_time' => strtotime(trim($result_row['payout_time'])),
									'sattle_time' => strtotime(trim($result_row['payout_time'])),
									'compare_time' => strtotime(trim($result_row['count_date'])),
									'created_date' => time(),
									'payout_amount' => trim($result_row['sum_gold']),
									'promotion_amount' => trim($result_row['bet_gold']),
									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									'odds_rate' => trim($result_row['compensate']),
									'bet_code' => (isset($result_row['fashion']) ? trim($result_row['fashion']) : "0"),
									'status' => $status,
									'win_result' => $win_result,
									'game_username' => trim($result_row['m_id']),
									'player_id' =>  (int) $exact_username,
								);
								//ad($PBdata);
				           }
				       }
					}
        		}
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function splt($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "SPLT",
            "player_id" => 1,
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => 100,
            "order_id" => time(),
            "game_code" => "",
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "SPLT";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
    		
            if($method == 'cm'){
                $url .= "api_101/account";
                
                $param_array = array(
        			"act" => "create",
        			"up_acc" => $arr['UpAccount'],
        			"up_pwd" => $arr['UpPassword'],
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        			"passwd" => $post_data['password'],
        			"nickname" => $post_data['username'],
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
        		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
						$output['gamePassword'] = $post_data['password'];
					}
					else if(isset($result_array['code']) && $result_array['code'] == '909')
					{
					    $output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
        		}
            }else if($method == 'li'){
                if($post_data['is_demo'] == STATUS_YES)
			    {
			        
			    }else{
			        $url .= "api_101/login";
                    
                    $page = "Lobby";
                    if(!empty($post_data['game_code'])){
        			    $page = $post_data['game_code'];
        			}
                    
                    $param_array = array(
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"passwd" => $post_data['password'],
            			"page" => $page,
            		);
            		
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['account'] = $aes->encrypt($param_array['account']);
            		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
            		$response = $this->curl_post($url, $param_array);
            		$curl_array = $response['curl'];
            		
            		if($response['code'] == '0')
            		{
                        $result_array = json_decode($response['data'], TRUE);
                        ad(json_encode($result_array,true));
                        ad($result_array);
                        if(isset($result_array['code']) && $result_array['code'] == '999')
    					{
    					    $output['errorCode'] = ERROR_SUCCESS;
    						$output['errorMessage'] = $this->lang->line('error_success');
    					    
    					    if(strtolower(substr($result_array['data']['PostHost'],0,5)) == "https"){
    					        $game_url = $result_array['data']['PostHost'];    
    					    }else{
    					        $game_url = "https".substr($result_array['data']['PostHost'],4);
    					    }
    					    
    						$encrypt_data = str_replace('=', '', base64_encode($post_data['provider_code']. '|' . $game_url . '|' . $result_array['data']['PostData']));
    					    $output['result'] = base_url('game/splt/' . $encrypt_data);
    					}
    					else if(isset($result_array['code']) && $result_array['code'] == '903')
    					{
    					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    						$output['errorMessage'] = $this->lang->line('error_username_not_found');
    					}
    					else if(isset($result_array['code']) && $result_array['code'] == '904')
    					{
    					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    						$output['errorMessage'] = $this->lang->line('error_username_not_found');
    					}
            		}   
			    }
            }else if($method == 'gb'){
                $url .= "api_101/points";
                
                $param_array = array(
        			"act" => "read",
        			"up_acc" => $arr['UpAccount'],
        			"up_pwd" => $arr['UpPassword'],
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
        		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '404')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
        		}
            }else if($method == 'cb'){
                $url .= "api_101/points";
                $requestOrderIDAlias = $arr['UpAccount'].str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(10000000,99999999);
                
                if($post_data['amount'] > 0) 
    			{
                    $param_array = array(
            			"act" => "add",
            			"up_acc" => $arr['UpAccount'],
            			"up_pwd" => $arr['UpPassword'],
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"Point" => $post_data['amount'],
            			"track_id" => $requestOrderIDAlias,
            		);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
            		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			else
    			{
    				$param_array = array(
            			"act" => "sub",
            			"up_acc" => $arr['UpAccount'],
            			"up_pwd" => $arr['UpPassword'],
            			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
            			"Point" => bcdiv(($post_data['amount'] * -1), 1, 2),
            			"track_id" => $requestOrderIDAlias,
            		);
            		$this->load->library('aes_ecb');
            		$aes = new Aes_ecb();
            		$aes->set_mode(MCRYPT_MODE_CBC);
            		$aes->set_iv($arr['IVkey']);
            		$aes->set_key($arr['Deskey']);
            		$aes->require_pkcs5();
            		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
            		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
    			}
    			
    			$response = $this->curl_post($url, $param_array);
    			$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $is_checking = FALSE;
                    $result_array = json_decode($response['data'], TRUE);
                    ad($result_array);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '404')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '422')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '422')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '500')
					{
					    $is_checking = TRUE;
					}
					else if(isset($result_array['code'])){
					    
					}else{
					    $is_checking = TRUE;
					}
					
					if($is_checking){
					    /*
					    $output['errorCode'] = ERROR_OVERTIME;
				        $output['errorMessage'] = $this->lang->line('error_overtime');
				        
				        $url_2 = $arr['APIUrl'];
				        $url_2 .= "api_101/points";
				        
					    $param_array_2 = array(
                			"act" => "log",
                			"up_acc" => $arr['UpAccount'],
                			"up_pwd" => $arr['UpPassword'],
                			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
                			"track_id" => $requestOrderIDAlias,
                		);
                		$this->load->library('aes_ecb');
                		$aes = new Aes_ecb();
                		$aes->set_mode(MCRYPT_MODE_CBC);
                		$aes->set_iv($arr['IVkey']);
                		$aes->set_key($arr['Deskey']);
                		$aes->require_pkcs5();
                		$param_array_2['up_acc'] = $aes->encrypt($param_array_2['up_acc']);
                		$param_array_2['up_pwd'] = $aes->encrypt($param_array_2['up_pwd']);
                		$param_array_2['account'] = $aes->encrypt($param_array_2['account']);
                		$response_2 = $this->curl_post($url_2, $param_array_2);
    			        $curl_array_2 = $response_2['curl'];
    			        if($response_2['code'] == '0')
        		        {
        		            $result_array_2 = json_decode($response_2['data'], TRUE);
        		            ad($result_array_2);
        		            if(isset($result_array_2['code']) && $result_array_2['code'] == '999')
					        {
					            if(isset($result_array_2['result']) && $result_array_2['result'] == '1')
					            {
					                $output['errorCode'] = ERROR_SUCCESS;
            						$output['errorMessage'] = $this->lang->line('error_success');
            						$output['result'] = "0.00";
					            }
					            else if(isset($result_array_2['result']) && $result_array_2['result'] == '0')
					            {
					                $output['errorCode'] = ERROR_SYSTEM_ERROR;
            						$output['errorMessage'] = $this->lang->line('error_system_error');
					            }
					        }
        		        }
        		        */
					}
        		}
            }else if($method == 'lo'){
                $url .= "api/logout";
                
                $param_array = array(
        			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
        		);
        		
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
        		}
            }else if($method == "bet"){
                $url .= "api_101/reportItem";
                
                $param_array = array(
        			"account" => $arr['UpAccount'],
        			"passwd" => $arr['UpPassword'],
        			"date" => "2022-08-04",
        			"gameID" => 22,
        			"flags" => 1,
        		);
        		//11:六合,12:大樂,13:539,22:
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    ad($result_array);
					}
        		}
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function ninek($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "9K",
            "player_id" => 1,
            "username" => "dev003",
            "password" => "b121231a",
            "amount" => -100,
            "order_id" => time(),
            "game_code" => "BingoBingo",
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "9K";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
    		
            if($method == 'cm'){
                $url .= "/api/".$arr['ApiToken']."/RegisterUser";
                $param_array = array(
        			"BossID" => $arr['BossID'],
        			"MemberAccount" => $post_data['username'],
        			"MemberPassword" => $post_data['password'],
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['success']) && $result_array['success'] == '0')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['gameID'] = $param_array['MemberAccount'];
    					$output['gamePassword'] = $param_array['MemberPassword'];
					}else if(isset($result_array['success']) && $result_array['success'] == '-1003'){
					    $output['errorCode'] = ERROR_USERNAME_EXITS;
					    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
    			}
            }else if($method == 'li'){
                $url .= "/api/".$arr['ApiToken']."/UserLogin";
                $param_array = array(
        			"MemberAccount" => $post_data['username'],
        			"MemberPassword" => $post_data['password'],
        			"GameCode" => $post_data['game_code'],
        			"Platform" => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['success']) && $result_array['success'] == '0')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['result'] = $result_array['data']['UserLogin']['GameUrl'];
					}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
            }else if($method == 'gb'){
                $url .= "/api/".$arr['ApiToken']."/GetUserBalance";
                $param_array = array(
        			"MemberAccount" => $post_data['username'],
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['success']) && $result_array['success'] == '0')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
					}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
            }else if($method == 'cb'){
                $url .= "/api/".$arr['ApiToken']."/BalanceTransfer";
                $param_array = array(
        			"MemberAccount" => $post_data['username'],
        			"Balance" => $post_data['amount'],
        			"TradeNo" => $requestOrderID,
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['success']) && $result_array['success'] == '0')
					{
					    $requestOrderIDAlias = $result_array['data']['BalanceTransfer']['TransactionID'];
					    $output['orderID'] = $requestOrderID;
				        $output['orderIDAlias'] = $requestOrderIDAlias;
				        if(isset($result_array['success']) && $result_array['success'] == '0'){
				            $output['errorCode'] = ERROR_SUCCESS;
        					$output['errorMessage'] = $this->lang->line('error_success');
        					$output['result'] = bcdiv($result_array['data']['BalanceTransfer']['AfterBalance'], 1, 2);
				        }else{
				            $output['errorCode'] = ERROR_OVERTIME;
						    $output['errorMessage'] = $this->lang->line('error_overtime');
				        }
					}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
					}else if(isset($result_array['success']) && $result_array['success'] == '-2003'){
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}else if(isset($result_array['success']) && $result_array['success'] == '-2003'){
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}else if(isset($result_array['success']) && $result_array['success'] == '-9999'){
					    $output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}else if(isset($result_array['success'])){
					    
					}else{
					    $output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}
    			}
            }else if($method == 'lo'){
                $url .= "/api/".$arr['ApiToken']."/UserLogin";
                $param_array = array(
        			"MemberAccount" => $post_data['username'],
        			"MemberPassword" => $post_data['password'],
        		);
        		$response = $this->curl_post($url, $param_array);
        		$curl_array = $response['curl'];
    			if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['success']) && $result_array['success'] == '0')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
					}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
            }else if($method == "bet"){
                //$initial_time = "2022-06-28 17:00:00";
                $initial_time = "2022-06-29 10:00:00";
                $start_time = strtotime($initial_time);
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+120 minutes', strtotime($initial_time))));
                $url .= "/api/".$arr['ApiToken']."/BetList";
                $param_array = array(
        			"StartTime" => date('Y-m-d', $start_time)."T".date('H:i:00', $start_time),
        			"EndTime" => date('Y-m-d', $end_time)."T".date('H:i:00', $end_time),
        			"BossID" => $arr['BossID'],
        			"Page" =>2,
        		);
        		ad($url);
        		ad($param_array);
                $response = $this->curl_post($url, $param_array);
                ad($response);
                if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    ad($result_array);
    			}
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function og($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "OG",
            'game_type_code' => "LC",
            "player_id" => 1,
            "username" => "bttdev021",
            "password" => "b121231a",
            "amount" => -100,
            "order_id" => time(),
            "game_code" => "BingoBingo",
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "OG";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
            $game_url = $arr['APIUrl'];
    		$token_url = $arr['APIUrl'].'/token';
    		
    		$currency_one = array("IDR", "VND", "INR", "MMK");
    		
            if($method == 'cm'){
                $url .= "/register";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
    			        
    			        $lang = 'en';
			
    					switch($post_data['syslang'])
    					{
    						case LANG_ZH_CN:
    						case LANG_ZH_HK:
    						case LANG_ZH_TW: $lang = 'cn'; break;
    						case LANG_ID: $lang = 'id'; break;
    						case LANG_TH: $lang = 'th'; break;
    						case LANG_VI: $lang = 'vn'; break;
    						case LANG_JA: $lang = 'jp'; break;
    						case LANG_KO: $lang = 'kr'; break;
    					}
    			        $param_array = array(
    			            'username' => $post_data['username'],
    			            'country' => $sys_data['system_country'],
    			            'fullname' => $post_data['username'],
    			            'email' => $post_data['username']."@".$sys_data['system_prefix'].".com",
    			            'language' => $lang,
    			            'birthdate' => "1980-01-01",
    			        );
    			        $response = $this->curl_post($url, $param_array,"X-Token:".$token);
    			        $curl_array = $response['curl'];
            			if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            $output['gameID'] = $post_data['username'];
    				            $output['gamePassword'] = $post_data['password'];
    				        }
            			}
    			    }
    			}
            }else if($method == 'li'){
                $game_code = "ogplus";
                if($post_data['game_type_code'] == GAME_BOARD_GAME){
                    $game_code = "ogplus_tw_";
                }
                $game_url .= "/game-providers/".$arr['ProviderID']."/games/".$game_code."/key";
                $url .= "/game-providers/".$arr['ProviderID']."/play";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $game_param_array = array(
    			            'username' => $post_data['username'],
    			        );
    			        
    			        $game_url .= "?" . http_build_query($game_param_array);
    			        $game_response = $this->curl_get($game_url, "X-Token:".$token);
    			        if($game_response['code'] == '0')
            			{
            			    $game_result_array = json_decode($game_response['data'], TRUE);
            			    if(isset($game_result_array['status']) && ($game_result_array['status'] == 'success'))
    				        {
    				            $key = (isset($game_result_array['data']['key'])?$game_result_array['data']['key']:"");
    				            $param_array = array(
            			            'key' => $key,
            			            'type' => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
            			        );
            			        $url .= "?" . http_build_query($param_array);
    			                $response = $this->curl_get($url);
    			                $curl_array = $response['curl'];
    			                if($response['code'] == '0')
    			                {
    			                    $result_array = json_decode($response['data'], TRUE);
    			                    if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				                {
    				                    $output['errorCode'] = ERROR_SUCCESS;
                    					$output['errorMessage'] = $this->lang->line('error_success');
                    					$output['result'] = $result_array['data']['url'];
    				                }
    			                }
    				        }
    				        else if(isset($game_result_array['status']) && ($game_result_array['status'] == 'error'))
    				        {
    				            if(isset($game_result_array['data']['code']) && ($game_result_array['data']['code'] == '1'))
    				            {
    				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					            $output['errorMessage'] = $this->lang->line('error_username_not_found');
    				            }
    				        }
            			}
    			    }
    			}
            }else if($method == 'gb'){
                $url .= "/game-providers/".$arr['ProviderID']."/balance";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
    			        
    			        $param_array = array(
    			            'username' => $post_data['username'],
    			        );
    			        
    			        $url .= "?" . http_build_query($param_array);
    			        $response = $this->curl_get($url, "X-Token:".$token);
    			        $curl_array = $response['curl'];
            			if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            if(in_array($arr['CurrencyType'],$currency_one)){
        							$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
        						}else{
        							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
        						}
    				            $output['result'] = bcdiv($result_array['data']['balance'], 1, 2); 
    				        }
    				        else if(isset($result_array['status']) && ($result_array['status'] == 'error'))
    				        {
    				            if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '1'))
    				            {
    				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						            $output['errorMessage'] = $this->lang->line('error_username_not_found');
    				            }
    				        }
            			}
    			    }
    			}
            }else if($method == 'cb'){
                $url .= "/game-providers/".$arr['ProviderID']."/balance";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
                        
                        $requestOrderIDAlias = $post_data['order_id'];
                        if($post_data['amount'] > 0) 
            			{
            			    $action = "IN";
                            if(in_array($arr['CurrencyType'],$currency_one)){
                                $amount = bcdiv($post_data['amount'] / 1000,1,2);
                            }else{
                                $amount = $post_data['amount'];
                            }
            			}else{
            			    $action = "OUT";
            			    if(in_array($arr['CurrencyType'],$currency_one)){
                                $amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
                            }else{
                                $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
                            }
            			}
                        
                        $param_array = array(
        		            'username' => $post_data['username'],
        		            'balance' => $amount,
        		            'action' => $action,
        		            'transferId' => $requestOrderIDAlias,
        		        );
        		        
        		        $response = $this->curl_post($url, $param_array,"X-Token:".$token);
        		        $curl_array = $response['curl'];
            			if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            if(in_array($arr['CurrencyType'],$currency_one)){
        							$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
        						}else{
        							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
        						}
    				            $output['result'] = bcdiv($result_array['data']['balance'], 1, 2); 
    				        }
    				        else if(isset($result_array['status']) && ($result_array['status'] == 'error'))
    				        {
    				            if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '1'))
    				            {
    				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						            $output['errorMessage'] = $this->lang->line('error_username_not_found');
    				            }
    				            else if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '7'))
    				            {
    				                $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    							    $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    				            }
    				            else if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '7'))
    				            {
    				                $output['errorCode'] = ERROR_OVERTIME;
					                $output['errorMessage'] = $this->lang->line('error_overtime');
    				            }
    				            else if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '7'))
    				            {
    				                $output['errorCode'] = ERROR_OVERTIME;
					                $output['errorMessage'] = $this->lang->line('error_overtime');
    				            }
    				        }
            			}
    			    }
    			}
            }else if($method == 'lo'){
                $output['errorCode'] = ERROR_SUCCESS;
                $output['errorMessage'] = $this->lang->line('error_success');
            }else if($method == "bet"){
                $url = $arr['ReportUrl'];
                $initial_time = "2022-06-29 16:00:00";
                $start_time = strtotime($initial_time);
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
                $url .= "/transaction";
                $param_array = array(
        			'Operator' => $arr['Operator'],
                    'Key' =>$arr['Key'],
                    'SDate' => date('Y-m-d H:i:s',$start_time),
                    'EDate' => date('Y-m-d H:i:s',$end_time),
                    'Provider' => 'ogplus',
                    'Exact' => FALSE,
        		);
        		
        		ad($url);
        		ad($param_array);
                $response = $this->curl_post($url, $param_array);
                ad($response);
                if($response['code'] == '0')
    			{
    				$result_array = json_decode($response['data'], TRUE);
    			    ad($result_array);
    			}
            }else if($method == "gamelist"){
                $url .= "/games";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $param_array = array(
    			            'provider' => $arr['ProviderID'],
    			            'rows' => "50",
    			            'page' => "1",
    			        );
    			        $url .= "?" . http_build_query($param_array);
    			        $response = $this->curl_get($url, "X-Token:".$token);
    			        if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    ad($result_array);
            			}
    			    }
    			}
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function bng($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "BNG",
            "player_id" => 1,
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => "-100.00",
            "order_id" => "OUT20220629184507bttdev021"."A".time(),
            "game_code" => "209",
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "BNG";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
            $game_url = $arr['APIUrl'];
            
            if($method == 'cm'){
                $url .= '/wallet/transfer/create_player/';
                
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'is_test' => TRUE,
		        );
		        if(!empty($arr['Brand'])){
		            $param_array['brand'] = $arr['Brand'];
		        }
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
					    $output['gameID'] = $param_array['player_id'];
					    $output['gamePassword'] = $post_data['password'];
    			    }
    			}
            }else if($method == 'li'){
                $url .= '/wallet/transfer/get_player_token/';
                $game_url .= "/game.html";
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'tag' => "OG".$post_data['username'].time().rand(100000,999999),
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_token'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $token = $result_array['player_token'];
    			        $lang = 'en';
			
    					switch($post_data['syslang'])
    					{
    						case LANG_ZH_CN:
    						case LANG_ZH_HK:
    						case LANG_ZH_TW: $lang = 'cn'; break;
    						case LANG_ID: $lang = 'id'; break;
    						case LANG_TH: $lang = 'th'; break;
    						case LANG_VI: $lang = 'vn'; break;
    						case LANG_JA: $lang = 'jp'; break;
    						case LANG_KO: $lang = 'kr'; break;
    					}
    			        $game_param_array = array(
    			            'token' => $token,
    			            'game' => $post_data['game_code'],
    			            'ts' => time(),
    			            'platform' =>  (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
    			            'wl' => "transfer",
    			            'theme' => $arr['Theme'],
    			            'lang' => $lang,
    			            'tz' => $arr['TZ'],
    			        );
    			        $game_url .= "?" . http_build_query($game_param_array);
    			        $output['result'] = $game_url;
    			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			    }
    			}
            }else if($method == 'gb'){
                $url .= '/wallet/transfer/get_player';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $output['result'] = bcdiv($result_array['balance'], 1, 2);
    			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			    }
    			}
            }else if($method == 'cb'){
                $requestOrderIDAlias = $post_data['order_id'];
                if($post_data['amount'] > 0) 
    			{
    			    $type = "CREDIT";
                    $amount = $post_data['amount'];
    			}else{
    			    $type = "DEBIT";
    			    $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
    			}
                
                $url .= '/wallet/transfer/transfer_balance';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'uid' => $requestOrderIDAlias,
		            'amount' => $amount,
		            'type' => $type,
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $output['result'] = bcdiv($result_array['balance_after'], 1, 2);
        			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
        			}else if(isset($result_array['error']) && $result_array['error'] == "INSUFFICIENT_FUNDS"){
    			        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
        			}else if(isset($result_array['error'])){
    			    }else{
    			        $output['errorCode'] = ERROR_OVERTIME;
					    $output['errorMessage'] = $this->lang->line('error_overtime');
    			    }
    			}
            }else if($method == 'lo'){
                $url .= '/wallet/transfer/logout_player';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
        			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
        			}
    			}
            }else if($method == "bet"){
                $member_lists = array();
                $sys_data['system_prefix'] = "btt";
                $url .= '/api/v1/transaction/list';
                $initial_time = "2022-07-04 18:05:00";
                //$start_time = strtotime($initial_time);
                //$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
                $start_time = 1659522900;
                $end_time = 1659523200;
                $start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time));
                $end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time));
                
                $param_array = array(
                    "api_token" => $arr['Token'],
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "player_id" => "",
                    "status" => "OK",
                    "brand" => "",
                    "fetch_size" => 1000,
                    "fetch_state" => ""
                );
                $response = $this->curl_json($url, $param_array);
                ad($url);
                ad($param_array);
                ad($response);
                if($response['code'] == '0')
    			{
    			    if($response['http_code'] == '200'){
    			        //success
    			        $result_array = json_decode($response['data'], TRUE);
    			        $next_id = $result_array['fetch_state'];
    			        if(isset($result_array['items']) && sizeof($result_array['items'])>0){
    			            foreach($result_array['items'] as $result_row){
    			                if($result_row['mode'] == "REAL"){
    			                    if(empty($result_row['is_test'])){
    			                        
        			                }else{
        			                    ad($result_row);
        			                    $tmp_username = strtolower(trim($result_row['player_id']));
        			                    ad($tmp_username);
    									$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
    									ad($tmp_username);
    									
    									$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SLOTS,
    										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['game_name']),
    										'game_real_code' => trim($result_row['game_id']),
    										'bet_id' => trim($result_row['transaction_id']),
    										'bet_time' => strtotime(trim($result_row['c_at'])),
    										'bet_amount' => trim($result_row['bet']),
    										'bet_amount_valid' => trim($result_row['bet']),
    										'payout_time' => strtotime(trim($result_row['c_at'])),
    										'sattle_time' => strtotime(trim($result_row['c_at'])),
    										'compare_time' => strtotime(trim($result_row['c_at'])),
    										'game_time' => strtotime(trim($result_row['c_at'])),
    										'created_date' => time(),
    										'win_loss' => trim($result_row['win']) - trim($result_row['bet']),
    										'payout_amount' => trim($result_row['win']),
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'status' => STATUS_COMPLETE,
    										'game_username' => trim($result_row['player_id']),
    										'round' => trim($result_row['round_id']),
    										'player_id' => $member_lists[$exact_username],
    									);
    									
    									ad($PBdata);
        			                }
    			                }
    			            }
    			        }
    			    }
    			}
            }else if($method == "gamelist"){
                $url .= "/games";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $param_array = array(
    			            'provider' => $arr['ProviderID'],
    			            'rows' => "50",
    			            'page' => "1",
    			        );
    			        $url .= "?" . http_build_query($param_array);
    			        $response = $this->curl_get($url, "X-Token:".$token);
    			        if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    ad($result_array);
            			}
    			    }
    			}
            }else if($method == "currencylist"){
                $url = 'https://exchanger.booongo.com/api/v1/currencies';
                $response = $this->curl_get($url);
                ad($response);
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function bng_testing($method = NULL){
        //5.30
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "BNG",
            "player_id" => 1,
            "username" => "bngdev001",
            "password" => "b121231a",
            "amount" => "-3000.00",
            "order_id" => "OUT20220629184507bttdev021"."A".time(),
            "game_code" => "250",
            "device" => PLATFORM_WEB,
        );
        
        $game_data['api_data'] = '{"APIUrl":"https://gate-stage.betsrv.com/op/new-kali-stage","Token":"To7tl85uX0","Brand":"","CurrencyType":"TWD","Theme":"light","TZ":"480"}';
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
            $game_url = $arr['APIUrl'];
            
            if($method == 'cm'){
                $url .= '/wallet/transfer/create_player/';
                
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'is_test' => TRUE,
		        );
		        if(!empty($arr['Brand'])){
		            $param_array['brand'] = $arr['Brand'];
		        }
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
					    $output['gameID'] = $param_array['player_id'];
					    $output['gamePassword'] = $post_data['password'];
    			    }
    			}
            }else if($method == 'li'){
                $url .= '/wallet/transfer/get_player_token/';
                $game_url .= "/game.html";
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'tag' => "OG".$post_data['username'].time().rand(100000,999999),
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_token'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $token = $result_array['player_token'];
    			        $lang = 'en';
			
    					switch($post_data['syslang'])
    					{
    						case LANG_ZH_CN:
    						case LANG_ZH_HK:
    						case LANG_ZH_TW: $lang = 'cn'; break;
    						case LANG_ID: $lang = 'id'; break;
    						case LANG_TH: $lang = 'th'; break;
    						case LANG_VI: $lang = 'vn'; break;
    						case LANG_JA: $lang = 'jp'; break;
    						case LANG_KO: $lang = 'kr'; break;
    					}
    			        $game_param_array = array(
    			            'token' => $token,
    			            'game' => $post_data['game_code'],
    			            'ts' => time(),
    			            'platform' =>  (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
    			            'wl' => "transfer",
    			            'theme' => $arr['Theme'],
    			            'lang' => $lang,
    			            'tz' => $arr['TZ'],
    			        );
    			        $game_url .= "?" . http_build_query($game_param_array);
    			        $output['result'] = $game_url;
    			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			    }
    			}
            }else if($method == 'gb'){
                $url .= '/wallet/transfer/get_player';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $output['result'] = bcdiv($result_array['balance'], 1, 2);
    			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			    }
    			}
            }else if($method == 'cb'){
                $requestOrderIDAlias = $post_data['order_id'];
                if($post_data['amount'] > 0) 
    			{
    			    $type = "CREDIT";
                    $amount = $post_data['amount'];
    			}else{
    			    $type = "DEBIT";
    			    $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
    			}
                
                $url .= '/wallet/transfer/transfer_balance';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'uid' => $requestOrderIDAlias,
		            'amount' => $amount,
		            'type' => $type,
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
    			        $output['result'] = bcdiv($result_array['balance_after'], 1, 2);
        			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
        			}else if(isset($result_array['error']) && $result_array['error'] == "INSUFFICIENT_FUNDS"){
    			        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
        			}else if(isset($result_array['error'])){
    			    }else{
    			        $output['errorCode'] = ERROR_OVERTIME;
					    $output['errorMessage'] = $this->lang->line('error_overtime');
    			    }
    			}
            }else if($method == 'lo'){
                $url .= '/wallet/transfer/logout_player';
                $param_array = array(
		            'api_token' => $arr['Token'],
		            'player_id' => $post_data['username'],
		            'currency' => $arr['CurrencyType'],
		            'mode' => "REAL",
		            'brand' => "",
		        );
		        $response = $this->curl_json($url, $param_array);
		        if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if(isset($result_array['player_id'])){
    			        $output['errorCode'] = ERROR_SUCCESS;
    			        $output['errorMessage'] = $this->lang->line('error_success');
        			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
    			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
        			}
    			}
            }else if($method == "bet"){
                $url .= '/api/v1/transaction/list';
                $initial_time = "2022-07-05 00:00:00";
                $start_time = strtotime($initial_time);
                $end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1440 minutes', strtotime($initial_time))));
                
                $start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time));
                $end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time));
                
                $param_array = array(
                    "api_token" => $arr['Token'],
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "player_id" => "",
                    "status" => "OK",
                    "brand" => "",
                    "fetch_size" => 1000,
                    "fetch_state" => ""
                );
                $response = $this->curl_json($url, $param_array);
                if($response['code'] == '0')
    			{
    			    if($response['http_code'] == '200'){
    			        //success
    			        $result_array = json_decode($response['data'], TRUE);
    			        $next_id = $result_array['fetch_state'];
    			        ad($result_array['items']);
    			        if(isset($result_array['items']) && sizeof($result_array['items'])>0){
    			            foreach($result_array['items'] as $result_row){
    			                if($result_row['mode'] == "REAL"){
    			                    if(empty($result_row['is_test'])){
    			                        
        			                }else{
        			                    ad($result_row);
        			                    $tmp_username = strtolower(trim($result_row['player_id']));
    									$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
    									
    									$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SLOTS,
    										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['game_name']),
    										'game_real_code' => trim($result_row['game_id']),
    										'bet_id' => trim($result_row['transaction_id']),
    										'bet_transaction_id' => trim($result_row['original_transaction_id']),
    										'bet_time' => strtotime(trim($result_row['c_at'])),
    										'bet_amount' => ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
    										'bet_amount_valid' => ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
    										'payout_time' => strtotime(trim($result_row['c_at'])),
    										'sattle_time' => strtotime(trim($result_row['c_at'])),
    										'compare_time' => strtotime(trim($result_row['c_at'])),
    										'game_time' => strtotime(trim($result_row['c_at'])),
    										'created_date' => time(),
    										'win_loss' => trim($result_row['win']) - ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
    										'payout_amount' => trim($result_row['win']),
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'status' => STATUS_COMPLETE,
    										'game_username' => trim($result_row['player_id']),
    										'bet_code' => trim($result_row['bonus_event']),
    										'round' => trim($result_row['round_id']),
    										'player_id' => $member_lists[$exact_username],
    									);
    									
    									if(empty($PBdata['bet_amount'])){
    									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
    									}
    									
    									if(!empty($PBdata['bet_code'])){
    									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_GAME_ACTIVITY;
    									    if($PBdata['bet_code'] == "JACKPOT"){
        									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
    									    }
    									}
    									
    									ad($PBdata);
        			                }
    			                }
    			            }
    			        }
    			        if(empty($next_id)){
    			            echo "stop";
    			        }else{
    			            echo "loop";
    			        }
    			    }
    			}
            }else if($method == "gamelist"){
                $url .= "/games";
                $token_array = array(
                    'X-Operator: '.$arr['Operator'],
                    'X-key: '.$arr['Key'],
                );
                $token_response = $this->curl_get($token_url, $token_array);
                if($token_response['code'] == '0')
    			{
    			    $token_result_array = json_decode($token_response['data'], TRUE);
    			    if(isset($token_result_array['status']) && $token_result_array['status'] == "success"){
    			        $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
    			        $param_array = array(
    			            'provider' => $arr['ProviderID'],
    			            'rows' => "50",
    			            'page' => "1",
    			        );
    			        $url .= "?" . http_build_query($param_array);
    			        $response = $this->curl_get($url, "X-Token:".$token);
    			        if($response['code'] == '0')
            			{
            			    $result_array = json_decode($response['data'], TRUE);
            			    ad($result_array);
            			}
    			    }
    			}
            }else if($method == "currencylist"){
                $url = 'https://exchanger.booongo.com/api/v1/currencies';
                $response = $this->curl_get($url);
                ad($response);
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function png($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
    	$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
				
        $post_data =  array(
            "provider_code" => "PNG",
            "player_id" => 21,
            "username" => "bttdev021",
            'game_id' => "bttdev021_BTT",
            "password" => "b121231a",
            "amount" => "100.00",
            "order_id" => "OUT20220629184507bttdev021"."A".time(),
            "game_code" => "winabeest",
            "device" => PLATFORM_WEB,
        );
        
		$game_data = NULL;
		$provider_code = "PNG";
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
    		$requestOrderID = $post_data['order_id'];
    		$requestOrderIDAlias = "";
    		$curl_array = array();
            $url = $arr['APIUrl'];
            $xml = '';
            
            $username = $arr['ApiUsername'];
            $password = $arr['ApiPassword'];
            $auth = "Basic ".base64_encode("$username:$password");
            ad($arr);
            if($method == 'cm'){
                $lang = 'en_US';
    			switch($post_data['syslang'])
    			{
    				case LANG_ZH_CN: $lang = 'zh_CN'; break;
    				case LANG_ZH_HK:
    				case LANG_ZH_TW: $lang = 'zh_TW'; break;
    				case LANG_TH: $lang = 'th_TH'; break;
    				case LANG_VI: $lang = 'vi_VN'; break;
    				case LANG_JA: $lang = 'ja_JP'; break;
    				case LANG_KO: $lang = 'ko_KR'; break;
    			}
    			
    			
    			$xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
    			$xml .= '<soapenv:Header/>';
    			$xml .= '<soapenv:Body>';
    			$xml .= '<v1:RegisterUser>';
    			$xml .= '<v1:UserInfo>';
    			$xml .= '<v1:ExternalUserId>'.$post_data['username']."_".$arr['UPrefix'].'</v1:ExternalUserId>';
    			$xml .= '<v1:Username>'.$post_data['username']."_".$arr['UPrefix'].'</v1:Username>';
    			$xml .= '<v1:Nickname>'.$post_data['username']."_".$arr['UPrefix'].'</v1:Nickname>';
    			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
    			$xml .= '<v1:Country>'.$arr['Country'].'</v1:Country>';
    			$xml .= '<v1:Birthdate>1980-01-01</v1:Birthdate>';
    			$xml .= '<v1:Registration>'.date('Y-m-d')."T".date('H:i:s').'</v1:Registration>';
    			$xml .= '<v1:BrandId>'.$arr['BrandId'].'</v1:BrandId>';
    			$xml .= '<v1:Language>'.$lang.'</v1:Language>';
    			$xml .= '</v1:UserInfo>';
    			$xml .= '</v1:RegisterUser>';
    			$xml .= '</soapenv:Body>';
                $xml .= '</soapenv:Envelope>';
    			ad($xml);
    			$header = array(
    			    'action' => $arr['RegisterUserAction'],
    			    'authorization' => $auth,
    			    'content_type' => "text/xml",
    			);
    			$response = $this->curl_soap_version_one($url, $xml, $header);
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['data']);
                    $xml_output = simplexml_load_string($xml_utf8);
                    $json = json_encode($xml_output);
                    $result_array = json_decode($json,true);
                    if($response['http_code'] == "200"){
                        //success
                        $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['gameID'] = $post_data['username']."_".$arr['UPrefix'];
    					$output['gamePassword'] = $post_data['password'];
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "DuplicateEntry"){
                            $output['errorCode'] = ERROR_USERNAME_EXITS;
					        $output['errorMessage'] = $this->lang->line('error_username_already_exits');
                        }
                    }
    			}
            }else if($method == 'li'){
                $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
    			$xml .= '<soapenv:Header/>';
    			$xml .= '<soapenv:Body>';
    			$xml .= '<v1:GetTicket>';
    			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
    			$xml .= '</v1:GetTicket>';
    			$xml .= '</soapenv:Body>';
                $xml .= '</soapenv:Envelope>';
                
                $header = array(
    			    'action' => $arr['GetTicketAction'],
    			    'authorization' => $auth,
    			    'content_type' => "text/xml",
    			);
    			$response = $this->curl_soap_version_one($url, $xml, $header);
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['data']);
                    $xml_output = simplexml_load_string($xml_utf8);
                    $json = json_encode($xml_output);
                    $result_array = json_decode($json,true);
                    if($response['http_code'] == "200"){
                        if(isset($result_array['sBody']['GetTicketResponse']['Ticket'])){
                            $lang = 'en_US';
                			switch($post_data['syslang'])
                			{
                				case LANG_ZH_CN: $lang = 'zh_CN'; break;
                				case LANG_ZH_HK:
                				case LANG_ZH_TW: $lang = 'zh_TW'; break;
                				case LANG_TH: $lang = 'th_TH'; break;
                				case LANG_VI: $lang = 'vi_VN'; break;
                				case LANG_JA: $lang = 'ja_JP'; break;
                				case LANG_KO: $lang = 'ko_KR'; break;
                			}
                			$ticket = $result_array['sBody']['GetTicketResponse']['Ticket'];
                            
                            $param_array =  array(
                                'pid' => $arr['PID'],
                                'practice' => 0,
                                'gid' => $post_data['game_code'],
                                'channel' => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
                                'lang' => $lang,
                                'brand' => $arr['BrandId'],
                                'ticket' => $ticket,
                            );
                            
                            $output['errorCode'] = ERROR_SUCCESS;
                    	    $output['errorMessage'] = $this->lang->line('error_success');
                            $output['result'] = $arr['ForwardUrl']."?" . http_build_query($param_array);
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }
                    }
    			}
            }else if($method == 'gb'){
                $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
    			$xml .= '<soapenv:Header/>';
    			$xml .= '<soapenv:Body>';
    			$xml .= '<v1:Balance>';
    			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
    			$xml .= '</v1:Balance>';
    			$xml .= '</soapenv:Body>';
                $xml .= '</soapenv:Envelope>';
                
                $header = array(
    			    'action' => $arr['BalanceAction'],
    			    'authorization' => $auth,
    			    'content_type' => "text/xml",
    			);
    			$response = $this->curl_soap_version_one($url, $xml, $header);
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['data']);
                    $xml_output = simplexml_load_string($xml_utf8);
                    $json = json_encode($xml_output);
                    $result_array = json_decode($json,true);
                    if($response['http_code'] == "200"){
                        if(isset($result_array['sBody']['BalanceResponse']['UserBalance']['Real'])){
                            $output['errorCode'] = ERROR_SUCCESS;
                            $output['errorMessage'] = $this->lang->line('error_success');
						    $output['result'] = bcdiv($result_array['sBody']['BalanceResponse']['UserBalance']['Real'], 1, 2);
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }
                    }
    			}
            }else if($method == 'cb'){
                $requestOrderIDAlias = $post_data['order_id'];
                if($post_data['amount'] > 0){
                    $amount = $post_data['amount'];
                    
                    $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
        			$xml .= '<soapenv:Header/>';
        			$xml .= '<soapenv:Body>';
        			$xml .= '<v1:CreditAccount>';
        			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
        			$xml .= '<v1:Amount>'.$amount.'</v1:Amount>';
        			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
        			$xml .= '<v1:ExternalTransactionId>'.$requestOrderIDAlias.'</v1:ExternalTransactionId>';
        			$xml .= '</v1:CreditAccount>';
        			$xml .= '</soapenv:Body>';
                    $xml .= '</soapenv:Envelope>';
                    
                    $header = array(
        			    'action' => $arr['CreditAccountAction'],
        			    'authorization' => $auth,
        			    'content_type' => "text/xml",
        			);
                }else{
                    $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
                    
                    $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
        			$xml .= '<soapenv:Header/>';
        			$xml .= '<soapenv:Body>';
        			$xml .= '<v1:DebitAccount>';
        			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
        			$xml .= '<v1:Amount>'.$amount.'</v1:Amount>';
        			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
        			$xml .= '<v1:ExternalTransactionId>'.$requestOrderIDAlias.'</v1:ExternalTransactionId>';
        			$xml .= '</v1:DebitAccount>';
        			$xml .= '</soapenv:Body>';
                    $xml .= '</soapenv:Envelope>';
                    
                    $header = array(
        			    'action' => $arr['DebitAccountAction'],
        			    'authorization' => $auth,
        			    'content_type' => "text/xml",
        			);
                }
                
    			$response = $this->curl_soap_version_one($url, $xml, $header);
    			if($response['code'] == '0')
    			{
    			    $xml_utf8 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['data']);
                    $xml_output = simplexml_load_string($xml_utf8);
                    $json = json_encode($xml_output);
                    $result_array = json_decode($json,true);
                    if($response['http_code'] == "200"){
                        if($post_data['amount'] > 0){
                            if(isset($result_array['sBody']['CreditAccountResponse']['UserAccount']['Real'])){
                                $output['errorCode'] = ERROR_SUCCESS;
                                $output['errorMessage'] = $this->lang->line('error_success');
    						    $output['result'] = bcdiv($result_array['sBody']['CreditAccountResponse']['UserAccount']['Real'], 1, 2);
                            }   
                        }else{
                            if(isset($result_array['sBody']['DebitAccountResponse']['UserAccount']['Real'])){
                                $output['errorCode'] = ERROR_SUCCESS;
                                $output['errorMessage'] = $this->lang->line('error_success');
    						    $output['result'] = bcdiv($result_array['sBody']['DebitAccountResponse']['UserAccount']['Real'], 1, 2);
                            }
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }else if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "NotEnoughMoney"){
                            $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						    $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
                        }else{
                            $output['errorCode'] = ERROR_OVERTIME;
					        $output['errorMessage'] = $this->lang->line('error_overtime');
                        }
                    }
    			}
            }else if($method == 'lo'){
            }else if($method == "bet"){
            }else if($method == "gamelist"){
            }else if($method == "currencylist"){
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function dg($method = NULL){
        $output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "DG",
            "username" => "dev001",
            "password" => "b121231a",
            "amount" => 100,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "DG";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
        
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();

		$url = '';
		$xml = '';
		$xml_2 = '';
		$xml_utf8 = '';
		$xml_utf8_2 = '';
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		$url = $arr['APIUrl'];
    		
    		$random = rand(100000, 999999);
    		$amount = 0;
    		$key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
    		
    		$param_array = array(
				'token' => $key,
				'random' => $random,
			);
            if($method == 'cm'){
                
            }else if($method == 'li'){
                
            }else if($method == 'gb'){
                
            }else if($method == 'cb'){
                
            }else if($method == 'lo'){
                $url .= '/user/onlineReport/' . $arr['AgentName'];
                $response = $this->curl_json($url, $param_array);
                if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			ad($result_array);
        		}
            }else if($method == "bet"){
                
            }else{
                echo "unknown";
            }
        }else{
            echo "no game data";
        }
        
        ad($output);
    }
    
    public function rtg($method = NULL){
        $output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => $this->lang->line('error_system_error'),
			);
				
        $post_data =  array(
            "provider_code" => "RTG",
            "username" => "bttdev021",
            "password" => "98879684",
            "amount" => 100,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
            'game_id' => "ystestSA",
        );
        
        $game_data = NULL;
		$provider_code = "RTG";
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
		
        /*
        case 'cm': $method = 'CreateMember'; break;
		case 'li': $method = 'LoginGame'; break;
		case 'gb': $method = 'GetBalance'; break;
		case 'cb': $method = 'ChangeBalance'; break;
		case 'lo': $method = 'LogoutGame'; break;
		case 'gl': $method = 'GameList'; break;
		*/
        
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
        if( ! empty($game_data))
        {
    		//--Temp end
    		$arr = json_decode($game_data['api_data'], TRUE);
    		$url = $arr['APIUrl'];
    		if($method == 'cm'){
                
            }else if($method == 'li'){
                
            }else if($method == 'gb'){
                $token_url = $arr['APIUrl'];
        		$token_param_array = array(
        	        "username" => $arr['Username'],
        	        "password" => $arr['Password'],
        	    );
        	    ad($token_url);
        	    ad($token_param_array);
        	    $token_url .= "/api/start/token?" . http_build_query($token_param_array);
        	    $token_response = $this->curl_get($token_url);
        	    if(isset($token_response['http_code']) && $token_response['http_code'] == "200"){
        	    	$token_array = json_decode($token_response['data'], TRUE);
        	    	ad($token_array);
        	    	if(isset($token_array['token'])){
        	    		$token = $token_array['token'];
        	    	    $url .= "/api/wallet";
        				$param_array = array(
        			     "agentId" => $arr['agentId'],
        		         "playerLogin" => $post_data['game_id'],
        		        );
        		        ad($url);
        		        ad($param_array);
        		        $response = $this->curl_json($url,$param_array,"Authorization: ".$token);
        		        ad($response);
        		        $curl_array = $response['curl'];
        				if($response['code'] == '0')
        				{
        					$result_array = json_decode($response['data'], TRUE);
        					ad($result_array);
        				}
        	    	}
        	    }
            }else if($method == 'cb'){
                
            }else if($method == 'lo'){
                $token_url = $arr['APIUrl'];
        		$token_param_array = array(
        	        "username" => $arr['Username'],
        	        "password" => $arr['Password'],
        	    );
        	    $token_url .= "/api/start/token?" . http_build_query($token_param_array);
        	    $token_response = $this->curl_get($token_url);
        	    if(isset($token_response['http_code']) && $token_response['http_code'] == "200"){
        	    	$token_array = json_decode($token_response['data'], TRUE);
        	        if(isset($token_array['token'])){
	    		        $token = $token_array['token'];
	    		        $url .="/api/player/id/".$post_data['game_id']."?agentId=".$arr['agentId'];
	    		        ad($url);
	    		        $response = $this->curl_get($url,"Authorization: ".$token);
	    		        if($response['code'] == '0')
        				{
        				    $result_array = json_decode($response['data'], TRUE);
        					if($response['http_code'] == "200"){
        					    $game_id = $result_array['result'];
        					    $url = $arr['APIUrl']."/api/player/logout";
            					$param_array = array(
            				        "id" => $game_id, 
            			        );
            			        $url .= "/".$game_id;
            			        ad($url);
            			        ad($param_array);
            			        $response = $this->curl_json($url,$param_array,"Authorization: ".$token);
            			        ad($response);
            			        if($response['code'] == '0')
        				        {
        				            $result_array = json_decode($response['data'], TRUE);
        				            ad($result_array);
        				        }
        					}
        				}
        	        }
        	    }
            }
    		    
        }
    }
    
    public function testing_player(){
        $member_lists = $this->player_model->get_player_list_array_by_provider(array("AB","WM","SA"));
        $this->ab($member_lists['AB']);
        $this->wm($member_lists['WM']);
        $this->sa($member_lists['SA']);
    }
    
    public function ab($member_lists = NULL){
        ad($member_lists);
    }
    
    public function wm($member_lists = NULL){
        ad($member_lists);
    }
    
    public function sa($member_lists = NULL){
        ad($member_lists);
    }
    
    public function testingpng(){
        $total_result_data = array();
        $result = array();
        $this->db->select('game_result_push_log_id,input_json');
        $this->db->where('game_result_push_log_id >= ',32);
        $this->db->order_by('game_result_push_log_id',"ASC");
        $query = $this->db->get('game_result_push_log');
        if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$bet_amount = 0;
		$payout_amount = 0;
		
		if(!empty($result)){
		    foreach($result as $result_row){
		        $arr = json_decode($result_row['input_json'], TRUE);
		        if(!empty($arr)){
    		        if(isset($arr['Messages']) && sizeof($arr['Messages'])>0){
    		            foreach($arr['Messages'] as $arr_row){
    		                if($arr_row['MessageType'] == 3){
    	                        array_push($total_result_data, $arr_row);
    	                    }
    		            }
    		        }
		        }
		    }
		}
		
		
		if(sizeof($total_result_data)>0){
		    foreach($total_result_data as $result_row){
		        if($result_row['ExternalUserId'] == "Pok168SA"){
		            $bet_amount += trim($result_row['RoundLoss']);
		            $payout_amount += trim($result_row['Amount']);
		        }
		    }
		}
		
		ad("Bet Amount : ".$bet_amount);
		ad("Payout Amount : ".$payout_amount);
    }
    
    public function naga($method = NULL){
        $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$post_data =  array(
            "provider_code" => "NAGA",
            "username" => "sa3dev021",
            "password" => "b121231a",
            "game_id" => "sa3dev021",
            "amount" => 100.00,
            "order_id" => "IN".time(),
            "device" => PLATFORM_WEB,
            "game_code" => "sweet-bonanza",
            "return_url" => "",
        );
        
        $game_data = NULL;
		$provider_code = "NAGA";
		
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
		
		
	    if( ! empty($game_data))
        {
            $arr = json_decode($game_data['api_data'], TRUE);
    		$url = $arr['APIUrl'];
            $requestOrderID = $post_data['order_id'];
    		$curl_array = array();
    		
    		if($method == 'cm'){
    		    $url .= '/operator/transfer/create-player';
    		    $param_array = array(
    		        'nativeId' => $post_data['username'],
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'currencyCode' => $arr['Currency'],
    		    );
        		$plain_text = '{"nativeId":"'.$param_array['nativeId'].'","groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","currencyCode":"'.$param_array['currencyCode'].'"}';
        		$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
        		$token = 'x-signature: ' . $signature;
    		    $response = $this->curl_json($url,$plain_text,$token);
    		    $curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
        		    if(isset($result_array['currencyCode'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['gameID'] = $param_array['nativeId'];
					    $output['gamePassword'] = $post_data['password'];
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1207'){
        		            $output['errorCode'] = ERROR_USERNAME_EXITS;
    				        $output['errorMessage'] = $this->lang->line('error_username_already_exits');   
        		        }
        		    }
        		}
    		}else if($method == 'li'){
    		    $url .= '/client/game/enter-lobby-page-get-all-games';
    		    $param_array = array(
    		        'playerToken' => "vdHKY4Wsan9E1u2OlwOq1DAeFtHN3jvARQngBUUtPO2jhjeUjHjupVYih5808i4e",
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'sortBy' => "playCount",
    		        'orderBy' => "DESC",
    		    );
    		    $url .= "?" . http_build_query($param_array);
    		    $plain_text = '{"groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","playerToken":"'.$param_array['playerToken'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
        		$token = 'x-signature: ' . $signature;
        		ad($url);
        		ad($token);
    		    $response = $this->curl_get_json($url,$token);
    		    $curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    ad($response);
        		    $result_array = json_decode($response['data'], TRUE);
        		    ad($result_array);
        		    if(!empty($result_array)){
        		        $game_url = "";
        		        foreach($result_array as $result_array_row){
        		            if($result_array_row['code'] == $post_data['game_code']){
        		                $game_url = $result_array_row['playUrl'];
        		            }
        		        }
        		        if(!empty($game_url)){
        		            $lang = 'en';
            		        switch($post_data['syslang'])
        					{
        						case LANG_ZH_CN:
        						case LANG_ZH_HK:
        						case LANG_ZH_TW: $lang = 'zh'; break;
        						case LANG_ID: $lang = 'id'; break;
        						case LANG_TH: $lang = 'th'; break;
        						case LANG_VI: $lang = 'vi'; break;
        						case LANG_JA: $lang = 'ja'; break;
        						case LANG_KO: $lang = 'ko'; break;
        					}
        					
            		        $game_url_array = array(
            		            'playerToken' => 1666785586,
            		            'groupCode' => $arr['GroupCode'],
        		                'brandCode' => $arr['BrandCode'],
        		                'gameCode' => $post_data['game_code'],
        		                'language' => $lang,
            		        );
            		        
            		        if( ! empty($post_data['return_url']))
                			{
                				$game_url_array['redirectUrl'] = $post_data['return_url'];
                			}
            		        
            		        $output['result'] = $game_url."?" . http_build_query($game_url_array);
            		        $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');   
        		        }
        		    }
        		}
    		}else if($method == 'gb'){
    		    $url .= '/operator/transfer/wallet-balance';
    		    $param_array = array(
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'nativeId' => $post_data['game_id'],
    		    );
    		    $url .= "?" . http_build_query($param_array);
    		    $plain_text = '{"groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","nativeId":"'.$param_array['nativeId'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
		
        		$token = 'x-signature: ' . $signature;
    		    $response = $this->curl_get_json($url,$token);
    		    $curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
        		    ad($result_array);
        		    if(isset($result_array['currency'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['balance'], 1, 2);
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1206'){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }
        		    }
        		}
    		}else if($method == 'cb'){
    		    $requestOrderIDAlias = $post_data['order_id'];
    		    if($post_data['amount'] > 0) 
    			{
    			    $url .= '/operator/transfer/deposit';
    			    $amount_ori = bcdiv($post_data['amount'],1,2);
    			    $amount_mask_one = bcdiv($post_data['amount'],1,0);
    			    $amount_mask_two = bcdiv($post_data['amount'],1,1);
    			    if($amount_ori == $amount_mask_one){
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_one;
    			        }else{
    			            $amount = $amount_mask_two;   
    			        }
        			}else{
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_two;
    			        }else{
    			            $amount = $amount_ori;   
    			        }
        			}
    			}else{
    			    $url .= '/operator/transfer/withdraw';
    			    $amount_ori = bcdiv(($post_data['amount'] * -1),1,2);
    			    $amount_mask_one = bcdiv($post_data['amount'] * -1,1,0);
    			    $amount_mask_two = bcdiv($post_data['amount'] * -1,1,1);
    			    if($amount_ori == $amount_mask_one){
    			        if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_one;
    			        }else{
    			            $amount = $amount_mask_two;   
    			        }
        			}else{
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_two;
    			        }else{
    			            $amount = $amount_ori;   
    			        }
        			}
    			}
    			
    			$param_array = array(
			        'nativeId' => $post_data['game_id'],
                    'brandCode' => $arr['BrandCode'],
                    'groupCode' => $arr['GroupCode'],
                    'currencyCode' => $arr['Currency'],
                    'amount' => $amount,
                    'nativeTransactionId' => $requestOrderIDAlias,
    		    );
    			
    			$plain_text = '{"nativeId":"'.$param_array['nativeId'].'","brandCode":"'.$param_array['brandCode'].'","groupCode":"'.$param_array['groupCode'].'","currencyCode":"'.$param_array['currencyCode'].'","amount":'.$param_array['amount'].',"nativeTransactionId":"'.$param_array['nativeTransactionId'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
    			$token = 'x-signature: ' . $signature;
    		    $response = $this->curl_json($url,$plain_text,$token);
    		    $curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
        		    ad($result_array);
        		    if(isset($result_array['transactionId'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['updatedBalance'],1,2);
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1206'){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }else if(isset($result_array['code'])){
        		            
        		        }else{
        		            $output['errorCode'] = ERROR_OVERTIME;
						    $output['errorMessage'] = $this->lang->line('error_overtime');
        		        }
        		    }
        		}
    		}else if($method == 'lo'){
    		    
    		}else if($method == 'bet'){
    		    $url .= '/client/player/bet-histories';
    		    $param_array = array(
    		        'skip' => 0,
    		        'limit' => 100,
    		        'startDate' => "2022-10-31T00:00:00.000Z",
    		        'endDate' => "2022-11-01T00:00:00.000Z",
    		        'apiKey' => $arr['ApiKey'],
    		    );
    		    
    		    $param_array = array(
    		        'skip' => 0,
    		        'limit' => 100,
    		        'startDate' => "2022-11-09T00:00:00.000Z",
    		        'endDate' => "2022-11-10T00:00:00.000Z",
    		        'apiKey' => $arr['ApiKey'],
    		    );
    		    ad($param_array);
    		    $url .= "?" . http_build_query($param_array);
    		    $response = $this->curl_get_json($url,$token);
    		    $curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        		    $result_array = json_decode($response['data'], TRUE);
        		    ad($result_array);
        		    if(isset($result_array['data']) && sizeof($result_array['data']) > 0){
        		        foreach($result_array['data'] as $result_row){
        		            
        		            
        		        }
        		    }
        		}
    		}
    		
    		ad($output);
        }
    }
    
    public function ftg(){
	    $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		
		$post_data =  array(
            "provider_code" => "FTG",
            'game_id' => "dev002",
            'player_id' => 1,
            "username" => "dev002",
            "password" => "b121231a",
            "amount" => 1000,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "FTG";
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
		
	    $game_data['api_data'] = '{"APIUrl":"https://asia.h93r.com", "ClientID":"011837db", "InviteCode":"23885f7a", "APIKey":"50fef31722c54a2de84104fe6caba7e6", "CurrencyType":"608","LobbyID":"FTGSLOT"}';
	    if( ! empty($game_data))
        {
            $member_list = $this->player_model->get_player_list_array_by_provider(array("FTG"));
            $member_lists = $member_list['FTG']; 
            
            $start_time = strtotime("2023-06-27 22:30:00");
            $end_time = strtotime("2023-06-27 22:35:00");
            
            //$start_time = strtotime("2023-06-28 15:55:00");
            //$end_time = strtotime("2023-06-28 16:00:00");
            $page_id = 1;
            
    		$start_date = date('c', $start_time);
	        $end_date = date('c', $end_time);
	        
	        $arr = json_decode($game_data['api_data'], TRUE);
	        $game_code_data = array();
	        $game_type_code = "";
	        ad($arr);
	        
	        $this->load->helper('jwt');
	        $game_list_url = $arr['APIUrl'];
	        $game_list_url .= "/api/v2/game/outside/list";
	        $game_list_param_array = array(
    		    'client_id' => $arr['ClientID'],
    		);
    		$jwt = new JWT();
    		$game_list_param_array['iat'] = (int)microtime(true);
    		$json = json_encode($game_list_param_array);
    		$jwt_token = $jwt->encode($json, $arr['APIKey'], 'HS256');
    		$game_list_response = $this->curl_get($game_list_url."?" . http_build_query($game_list_param_array), "Authorization: Bearer " . $jwt_token);
	        if($game_list_response['code'] == '0')
			{
			    $game_list_result_array = json_decode($game_list_response['data'], TRUE);
			    if(isset($game_list_result_array['games']) && !empty($game_list_result_array['games']))
				{
				    foreach($game_list_result_array['games'] as $game_list_result_array_row){
				        switch($game_list_result_array_row['category'])
            			{
            				case 'fishing': $game_type_code = GAME_FISHING; break;
            				case 'slots': $game_type_code = GAME_SLOTS; break;
            				default: $game_type_code = GAME_BOARD_GAME; break;
            			}
            			
				        $game_code_data[$game_list_result_array_row['id']] = $game_type_code;
				    }
				}
				
				$url = $arr['APIUrl'];
    	        $url .= "/api/v2/wagers/outside/list";
    	        
                $param_array = array(
        		    'lobby_id' => $arr['LobbyID'],
        		    'date_type' => '2',
        		    'begin_at' => $start_date,
        		    'end_at' => $end_date,
        		    'page' => $page_id,
        		    'row_number' => 5000,
        		    'client_id' => $arr['ClientID'],
        		    'invite_code' => $arr['InviteCode'],
        		);
        		
        		$jwt = new JWT();
        		$param_array['iat'] = (int)microtime(true);
        		$json = json_encode($param_array);
        		$jwt_token = $jwt->encode($json, $arr['APIKey'], 'HS256');
    		    $response = $this->curl_get($url."?" . http_build_query($param_array), "Authorization: Bearer " . $jwt_token);
    		    ad($response);
    		    if($response['code'] == '0')
    			{
    			    $result_array = json_decode($response['data'], TRUE);
    			    if( ! empty($result_array))
    				{
    				    if(isset($result_array['row_number']))
						{
						    $DBdata['resp_data'] = json_encode($result_array);
						    $DBdata['sync_status'] = STATUS_YES;
						    if(sizeof($result_array['rows'])>0){
						        foreach($result_array['rows'] as $result_row){
						            $tmp_username = strtolower(trim($result_row['username']));
								    $exact_username = $tmp_username;
								    
								    if($result_row['result'] == "W" || $result_row['result'] == "L"){
										$status = STATUS_COMPLETE;
									}else if($result_row['result'] == "C"){
										$status = STATUS_CANCEL;
									}else{
										$status = STATUS_PENDING;
									}
									
									$PBdata = array(
    							        'game_provider_code' => $provider_code,
    							        'game_type_code' => (isset($game_code_data[trim($result_row['game_id'])]) ? $game_code_data[trim($result_row['game_id'])] : GAME_BOARD_GAME),
    							        'game_provider_type_code' => $provider_code."_".(isset($game_code_data[trim($result_row['game_id'])]) ? $game_code_data[trim($result_row['game_id'])] : GAME_BOARD_GAME),
    							        'game_result_type' => $result_type,
    							        'game_code' => trim($result_row['game_id']),
    							        'game_real_code' => trim($result_row['game_id']),
    							        'bet_id' => trim($result_row['id']),
    							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
    							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
    					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
    							        'bet_amount' => trim($result_row['bet_amount']),
    							        'bet_amount_valid' => trim($result_row['commissionable']),
    							        'payout_amount' => trim($result_row['payoff']),
    							        'promotion_amount' => trim($result_row['commissionable']),
    							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['modified_at']))),
    							        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['payoff_at']))),
    							        'win_loss' => trim($result_row['profit']),
    							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    							        'status' => $status,
    							        'game_username' => trim($result_row['username']),
    							        'player_id' => $member_lists[$exact_username],
    							    );
    							    ad($PBdata);
    							    
    							    if($PBdata['win_loss'] == 0){
									    $PBdata['promotion_amount'] = 0;
    							    }
									
									
									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{
									    $PBdata['bet_info'] = json_encode($result_row);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
										
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $PBdata['player_id'],
												'game_code' => $PBdata['game_code'],
												'bet_time' => $PBdata['bet_time'],
												'payout_time' => $PBdata['payout_time'],
												'game_provider_code' => $PBdata['game_provider_code'],
												'game_type_code' => $PBdata['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $PBdata['bet_amount'],
												'bet_amount_valid' => $PBdata['bet_amount_valid'],
												'win_loss' => $PBdata['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
						        }
						    }
						}
    				}
    			}
			}
			exit;
                
            $start_time = strtotime("2023-06-27 20:04:00");
            $end_time = strtotime("2023-06-27 20:05:00");
            $page_id = 1;
            
    		$start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
	        $end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
	        
            $arr = json_decode($game_data['api_data'], TRUE);
            $url = $arr['APIUrl'];
            
            $url .= '/bet_history';
    		$param_array = array(
    		    'timestamp' => strval(floor(microtime(true) * 1000)),
    		    'site_id' => $arr['SiteID'],
    		    'from' => $start_date,
    		    'to' => $end_date,
    		    'page' => $page_id,
    		);
    		ksort($param_array);
    		$string = '';
    		foreach($param_array as $k=>$v){
    			if(($k != 'signature')&&($k != 'bets')){
    			   $string .= $v;
    			}
    		}
    		
    		$param_array['signature'] = md5($string . $arr['APIKey']);
    		$response = $this->curl_json($url, $param_array);
		    $curl_array = $response['curl'];
		    
		    if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			    if( ! empty($result_array))
				{
				    ad($result_array);
				    if(isset($result_array['status']) && $result_array['status'] == 'success')
					{
					    $DBdata['resp_data'] = json_encode($result_array);
					    $DBdata['sync_status'] = STATUS_YES;
					    
					    if(sizeof($result_array['records'])>0){
					        foreach($result_array['records'] as $result_row){
					            ad($result_row);
					            $tmp_username = strtolower($result_row['user_id']);
								$exact_username = $tmp_username;
								
								$PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => GAME_COCKFIGHTING,
							        'game_provider_type_code' => $provider_code."_".GAME_COCKFIGHTING,
							        'game_result_type' => $result_type,
							        'game_code' => GAME_COCKFIGHTING,
							        'game_real_code' => GAME_COCKFIGHTING,
							        'bet_id' => trim($result_row['bet_id']),
							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['date_created']))),
							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['date_created']))),
					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['date_created']))),
							        'bet_amount' => trim($result_row['bet_amount']),
							        'bet_amount_valid' => trim($result_row['bet_amount']),
							        'payout_amount' => trim($result_row['payout']),
							        'promotion_amount' => trim($result_row['bet_amount']),
							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['date_created']))),
							        'win_loss' => trim($result_row['payout']) - trim($result_row['bet_amount']),
							        'round' => trim($result_row['round_id']),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => STATUS_COMPLETE,
							        'game_username' => trim($result_row['user_id']),
							        'player_id' => $member_lists[$exact_username],
							    );
							    
							    ad($PBdata);
					        }
					    }
					}
				}
			}
		    exit;
    		
    		
    		
    		$response = $this->curl_json($url, $param_array);
    		if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			    ad($result_array);
			    if( ! empty($result_array))
				{
				    if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
					    $DBdata['resp_data'] = json_encode($result_array);
					    $DBdata['sync_status'] = STATUS_YES;
					    if(sizeof($result_array['Games'])>0){
					        foreach($result_array['Games'] as $result_row){
					            $tmp_username = strtolower(trim(str_replace($arr['Prefix'],"",$result_row['PlayerName'])));
								$exact_username = $tmp_username;
								
								$PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => GAME_SLOTS,
							        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row['GameKey']),
							        'game_real_code' => trim($result_row['GameKey']),
							        'bet_id' => trim($result_row['GameId']),
							        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['Finished']))),
							        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['Finished']))),
					       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row['Finished']))),
							        'bet_amount' => trim($result_row['Bet']),
							        'bet_amount_valid' => trim($result_row['Bet']),
							        'payout_amount' => trim($result_row['Win']),
							        'promotion_amount' => trim($result_row['Bet']),
							        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['Finished']))),
							        'win_loss' => trim($result_row['Win']) - trim($result_row['Bet']),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => STATUS_COMPLETE,
							        'game_username' => trim($result_row['PlayerName']),
							        'player_id' => $member_lists[$exact_username],
							    );
							    if($PBdata['win_loss'] == 0){
							        $PBdata['promotion_amount'] = 0;
							    }
							    if($PBdata['bet_amount'] == 0){
							        $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
							    }
							    
							    ad($PBdata);
					        }
					    }
					}
				}
			}
        }
	}
	
	public function ds88(){
	    $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		
		$post_data =  array(
            "provider_code" => "DS88",
            'game_id' => "dev002",
            'player_id' => 1,
            "username" => "dev002",
            "password" => "b121231a",
            "amount" => 1000,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "DS88";
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
		
	    $game_data['api_data'] = '{"APIUrl":"https://api.ds88sabong.com", "Token":"eyJhbGciOiJIUzI1NiJ9.eyJpZCI6ODk4NTI3MywiZW1haWwiOiJhMzQ1NnBocEBkczg4c2Fib25nLmNvbSJ9._uQe_J9ax5ems-zs6A1RHtYBpgRdc2_f91p8WwDP8Ac"}';
	    if( ! empty($game_data))
        {
            $member_list = $this->player_model->get_player_list_array_by_provider(array("DS88"));
            $member_lists = $member_list['DS88']; 
            
            //$start_time = strtotime("2023-06-22 20:25:00");
            //$end_time = strtotime("2023-06-22 20:30:00");
            
            $start_time = strtotime("2023-06-29 13:25:29");
            $end_time = strtotime("2023-06-29 13:25:47");
            
            
    		$start_date = gmdate("Y-m-d\TH:i:s\Z", $start_time);
	        $end_date = gmdate("Y-m-d\TH:i:s\Z", $end_time);
	        
	        $page_id = 1;
	        $arr = json_decode($game_data['api_data'], TRUE);
	        
				
			$url = $arr['APIUrl'];
	        $url .= "/api/merchant/bets";
	        ad($url);
            $param_array = array(
    		    'time_type' => 'settled_at',
    		    'start_time' => $start_date,
    		    'end_time' => $end_date,
    		    'page' => $page_id,
    		    'page_size' => 10000,
    		);
    		$BUDdata = array();
    		$Bdata = array();
    		ad($param_array);
    		$response = $this->curl_get($url . '?' . http_build_query($param_array), "Authorization: Bearer " . $arr['Token']);
		    ad($response);
		    if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			    ad($result_array);
			    if( ! empty($result_array))
				{
				    if(isset($result_array['code']) && ($result_array['code'] == "OK"))
					{
					    $DBdata['resp_data'] = json_encode($result_array);
					    $DBdata['sync_status'] = STATUS_YES;
					    if(sizeof($result_array['data'])>0){
					        foreach($result_array['data'] as $result_row){
					            ad($result_row);
					            $tmp_username = strtolower(trim($result_row['account']));
							    $exact_username = $tmp_username;
							    
							    $payout_amount = 0;
							    if($result_row['is_settled'] == 1){
							        if($result_row['status'] == "settled"){
    									$status = STATUS_COMPLETE;
    									$payout_amount = ((isset($result_row['bet_return'])) ? $result_row['bet_return'] : 0);
    								}else if($result_row['status'] == "cancel" || $result_row['status'] == "fail"){
    									$status = STATUS_CANCEL;
    								}else{
    									$status = STATUS_PENDING;
    								}
							    }else{
							        $status = STATUS_PENDING;
							    }
							    
								
								$PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => GAME_COCKFIGHTING,
							        'game_provider_type_code' => $provider_code."_".GAME_COCKFIGHTING,
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row['category']),
							        'game_real_code' => trim($result_row['category']),
							        'bet_id' => trim($result_row['slug']),
							        'bet_transaction_id' => trim($result_row['arena_fight_no']),
							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
							        'bet_amount' => trim($result_row['bet_amount']),
							        'bet_amount_valid' => trim($result_row['valid_amount']),
							        'payout_amount' => $payout_amount,
							        'promotion_amount' => trim($result_row['valid_amount']),
							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
							        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
							        'win_loss' => trim($result_row['net_income']),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => $status,
							        'game_username' => trim($result_row['account']),
    						        'round' => ((isset($result_row['round_id'])) ? $result_row['round_id'] : ''),
    						        'subround'  => ((isset($result_row['fight_no'])) ? $result_row['fight_no'] : ''),
							        'player_id' => $member_lists[$exact_username],
							    );
							    
							    if($PBdata['win_loss'] == 0){
								    $PBdata['promotion_amount'] = 0;
							    }
								
								
								if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{
								    $PBdata['bet_info'] = json_encode($result_row);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
									
									if($PBdata['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $PBdata['player_id'],
											'game_code' => $PBdata['game_code'],
											'bet_time' => $PBdata['bet_time'],
											'payout_time' => $PBdata['payout_time'],
											'game_provider_code' => $PBdata['game_provider_code'],
											'game_type_code' => $PBdata['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $PBdata['bet_amount'],
											'bet_amount_valid' => $PBdata['bet_amount_valid'],
											'win_loss' => $PBdata['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
					        }
					    }
					}
				}
			}
			
			ad($Bdata);
			ad($BUDdata);
        }
	}
	
	public function dgg(){
	    $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		
		$post_data =  array(
            "provider_code" => "DGG",
            'game_id' => "dev002",
            'player_id' => 1,
            "username" => "dev002",
            "password" => "b121231a",
            "amount" => 1000,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "DGG";
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
		
	    $game_data['api_data'] = '{"APIUrl":"https://api.dragongaming.com/v1", "APIKey":"0NRLeIlYHWjKTgWl", "GameProvider":"dragongaming", "CurrencyType":"PHP", "CountryCode":"PH"}';
	    if( ! empty($game_data))
        {
            $member_list = $this->player_model->get_player_list_array_by_provider(array("DGG"));
            $member_lists = $member_list['DGG']; 
            
            $start_time = strtotime("2023-06-28 21:25:00");
            $end_time = strtotime("2023-06-28 21:35:00");
            
            
    		$start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
	        $end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
	        
	        $page_id = 1;
	        $arr = json_decode($game_data['api_data'], TRUE);
	        
				
			$url = $arr['APIUrl'];
	        $url .= "/games/game-history-all-players/";
	        ad($url);
            $param_array = array(
    		    'api_key' => $arr['APIKey'],
    		    'amount_type' => "real",
    		    'start_date' => $start_date,
    		    'end_date' => $end_date,
    		);
    		
    		$game_code_data = array(
    		    'slots' => GAME_SLOTS,
    		    'table_games' => GAME_BOARD_GAME,
    		    'scratch_cards' => GAME_OTHERS,
    		);
    		
    		ad($param_array);
    		$response = $this->curl_json($url, $param_array);
		    ad($response);
		    if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			    ad($result_array);
			    if( ! empty($result_array))
				{
				    if(isset($result_array['result']))
					{
					    $DBdata['resp_data'] = json_encode($result_array);
					    $DBdata['sync_status'] = STATUS_YES;
					    if(isset($result_array['result']['game_history']['data']) && (sizeof($result_array['result']['game_history']['data'])>0)){
					        foreach($result_array['result']['game_history']['data'] as $result_row){
					            $tmp_username = strtolower(trim($result_row[0]));
							    $exact_username = $tmp_username;
								
								$PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => (isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
							        'game_provider_type_code' => $provider_code."_".(isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row[1]),
							        'game_real_code' => trim($result_row[1]),
							        'bet_id' => trim($result_row[5]),
							        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'game_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
					       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'bet_amount' => trim($result_row[6]),
							        'bet_amount_valid' => trim($result_row[6]),
							        'payout_amount' => trim($result_row[7]),
							        'promotion_amount' => trim($result_row[6]),
							        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'win_loss' => trim($result_row[7]) - trim($result_row[6]),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => STATUS_COMPLETE,
							        'game_username' => trim($result_row[0]),
							        'player_id' => $exact_username,
							    );
							    
							    if(trim($result_row[9]) != "normal"){
							        $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
							    }
							    
							    if($PBdata['win_loss'] == 0){
								    $PBdata['promotion_amount'] = 0;
							    }
							    
							    ad($PBdata);
								
								
								if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{
								    $PBdata['bet_info'] = json_encode($result_row);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
									
									if($PBdata['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $PBdata['player_id'],
											'game_code' => $PBdata['game_code'],
											'bet_time' => $PBdata['bet_time'],
											'payout_time' => $PBdata['payout_time'],
											'game_provider_code' => $PBdata['game_provider_code'],
											'game_type_code' => $PBdata['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $PBdata['bet_amount'],
											'bet_amount_valid' => $PBdata['bet_amount_valid'],
											'win_loss' => $PBdata['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
					        }
					    }
					}
				}
			}
        }
	}
	
	public function t1g(){
	    $syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
        $this->lang->load('general', get_language($syslang));
        
        $output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		
		$post_data =  array(
            "provider_code" => "T1G",
            'game_id' => "dev002",
            'player_id' => 1,
            "username" => "dev002",
            "password" => "b121231a",
            "amount" => 1000,
            "order_id" => time(),
            "device" => PLATFORM_WEB,
        );
        
        $game_data = NULL;
		$provider_code = "T1G";
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
		
	    $game_data['api_data'] = '{"APIUrl":"https://bg03-open.t1games.live", "MerchantCode":"qvNDoxdjUaAbWHg95Xo6z", "SignKey":"cgebgLgUmYFsKYDTckeP5", "APIKey":"mE0KmLi8P4X723OcxmGpD"}';
	    if( ! empty($game_data))
        {
            $member_list = $this->player_model->get_player_list_array_by_provider(array("T1G"));
            $member_lists = $member_list['T1G']; 
            
            $start_time = strtotime("2023-06-29 16:00:00");
            $end_time = strtotime("2023-06-29 16:30:00");
            
            
    		$start_date = date('YmdHis', strtotime('-0 hours', $start_time));
	        $end_date = date('YmdHis', strtotime('-0 hours', $end_time));
	        
	        $page_id = 1;
	        $arr = json_decode($game_data['api_data'], TRUE);
	        
	        
	        $token_url = $arr['APIUrl'] . '/gameapi/v2/generate_token';
	        $token_param_array = array(
    			'merchant_code' => $arr['MerchantCode'],
    			'secure_key' => $arr['APIKey'],
    		);
    
    		ksort($token_param_array);
    		$token_string = '';
    		foreach($token_param_array as $k=>$v){
    			if($k != '' && ! is_array($k)){
    			   $token_string .= $v;
    			}
    		}
    		$token_param_array['sign'] = strtolower(sha1($token_string . $arr['SignKey']));
    		$response_token = $this->curl_json($token_url, $token_param_array);
    		if($response_token['code'] == '0')
    		{
    			$result_token_array = json_decode($response_token['data'], TRUE);
    			
    			if(isset($result_token_array['success']) && $result_token_array['success'] == true)
    			{
    			    $token = $result_token_array['detail']['auth_token'];
    		        
    		        $url = $arr['APIUrl'];
        	        $url .= "/gameapi/v2/chain/query_game_history";
        	        
        	        $param_array = array(
        	            'auth_token' => $token,
            		    'merchant_code' => $arr['MerchantCode'],
            		    'from' => $start_date,
            		    'to' => $end_date,
            		    'time_type' => 2,
            		    'page_number' => $page_id,
            		);
            		ksort($param_array);
    				$string = '';
    				foreach($param_array as $k=>$v){
    					if($k != '' && ! is_array($k)){
    					   $string .= $v;
    					}
    				}
    				
    				$param_array['sign'] = sha1($string . $arr['SignKey']);
    		        $url .= "?" . http_build_query($param_array);
					ad($url);
					$response = $this->curl_get($url);
					ad($response);
        		    if($response['code'] == '0')
        			{
        			    $result_array = json_decode($response['data'], TRUE);
        			    ad($result_array);
        			    if( ! empty($result_array))
        				{
        				    $DBdata['resp_data'] = json_encode($result_array);
        				    if(isset($result_array['code']) && $result_array['code'] == 0)
        					{
            				    if(isset($result_array['success']) && $result_array['success'] == 1)
            					{
            					    $DBdata['sync_status'] = STATUS_YES;
            					    if(isset($result_array['detail']['game_history']) && (sizeof($result_array['detail']['game_history'])>0)){
            					        foreach($result_array['detail']['game_history'] as $result_row){
            					            $tmp_username = strtolower(trim($result_row['username']));
            							    $exact_username = $tmp_username;
            								
            								$PBdata = array(
            							        'game_provider_code' => $provider_code,
            							        'game_type_code' => GAME_BOARD_GAME,
            							        'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
            							        'game_result_type' => $result_type,
            							        'game_code' => trim($result_row['game_code']),
            							        'game_real_code' => trim($result_row['game_code']),
            							        'bet_id' => trim($result_row['uniqueid']),
            							        'bet_time' => trim($result_row['bet_time']),
            							        'game_time' => trim($result_row['game_finish_time']),
            					       			'report_time' => trim($result_row['payout_time']),
            							        'bet_amount' => trim($result_row['bet_amount']),
            							        'bet_amount_valid' => trim($result_row['bet_amount']),
            							        'payout_amount' => trim($result_row['payout_amount']),
            							        'promotion_amount' => trim($result_row['bet_amount']),
            							        'payout_time' => trim($result_row['payout_time']),
            							        'sattle_time' => trim($result_row['payout_time']),
            							        'win_loss' => trim($result_row['payout_amount']) - trim($result_row['bet_amount']),
            							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            							        'status' => STATUS_COMPLETE,
            							        'game_username' => trim($result_row['username']),
            							        'player_id' => $member_lists[$exact_username],
            							    );
            							    
            							    
            							    if($PBdata['win_loss'] == 0){
            								    $PBdata['promotion_amount'] = 0;
            							    }
            							    
            							    ad($PBdata);
            								
            								
            								if( ! in_array($PBdata['bet_id'], $transaction_lists))
            								{
            								    $PBdata['bet_info'] = json_encode($result_row);
            							        $PBdata['insert_type'] = SYNC_DEFAULT;
            									array_push($Bdata, $PBdata);
            									
            									if($PBdata['status'] == STATUS_COMPLETE){
            										$PBdataWL = array(
            											'player_id' => $PBdata['player_id'],
            											'game_code' => $PBdata['game_code'],
            											'bet_time' => $PBdata['bet_time'],
            											'payout_time' => $PBdata['payout_time'],
            											'game_provider_code' => $PBdata['game_provider_code'],
            											'game_type_code' => $PBdata['game_type_code'],
            											'total_bet' => 1,
            											'bet_amount' => $PBdata['bet_amount'],
            											'bet_amount_valid' => $PBdata['bet_amount_valid'],
            											'win_loss' => $PBdata['win_loss'],
            										);
            										array_push($BUDdata, $PBdataWL);
            									}
            								}
            					        }
            					    }
        					    }
        					}
        				}
        			}
    			}
    		}
	        
	        exit;
				
			$url = $arr['APIUrl'];
	        $url .= "/games/game-history-all-players/";
	        ad($url);
            $param_array = array(
    		    'api_key' => $arr['APIKey'],
    		    'amount_type' => "real",
    		    'start_date' => $start_date,
    		    'end_date' => $end_date,
    		);
    		
    		$game_code_data = array(
    		    'slots' => GAME_SLOTS,
    		    'table_games' => GAME_BOARD_GAME,
    		    'scratch_cards' => GAME_OTHERS,
    		);
    		
    		ad($param_array);
    		$response = $this->curl_json($url, $param_array);
		    ad($response);
		    if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			    ad($result_array);
			    exit;
			    if( ! empty($result_array))
				{
				    if(isset($result_array['result']))
					{
					    $DBdata['resp_data'] = json_encode($result_array);
					    $DBdata['sync_status'] = STATUS_YES;
					    if(isset($result_array['result']['game_history']['data']) && (sizeof($result_array['result']['game_history']['data'])>0)){
					        foreach($result_array['result']['game_history']['data'] as $result_row){
					            $tmp_username = strtolower(trim($result_row[0]));
							    $exact_username = $tmp_username;
								
								$PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => (isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
							        'game_provider_type_code' => $provider_code."_".(isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row[1]),
							        'game_real_code' => trim($result_row[1]),
							        'bet_id' => trim($result_row[5]),
							        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'game_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
					       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'bet_amount' => trim($result_row[6]),
							        'bet_amount_valid' => trim($result_row[6]),
							        'payout_amount' => trim($result_row[7]),
							        'promotion_amount' => trim($result_row[6]),
							        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
							        'win_loss' => trim($result_row[7]) - trim($result_row[6]),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => STATUS_COMPLETE,
							        'game_username' => trim($result_row[0]),
							        'player_id' => $exact_username,
							    );
							    
							    if(trim($result_row[9]) != "normal"){
							        $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
							    }
							    
							    if($PBdata['win_loss'] == 0){
								    $PBdata['promotion_amount'] = 0;
							    }
							    
							    ad($PBdata);
								
								
								if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{
								    $PBdata['bet_info'] = json_encode($result_row);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
									
									if($PBdata['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $PBdata['player_id'],
											'game_code' => $PBdata['game_code'],
											'bet_time' => $PBdata['bet_time'],
											'payout_time' => $PBdata['payout_time'],
											'game_provider_code' => $PBdata['game_provider_code'],
											'game_type_code' => $PBdata['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $PBdata['bet_amount'],
											'bet_amount_valid' => $PBdata['bet_amount_valid'],
											'win_loss' => $PBdata['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
					        }
					    }
					}
				}
			}
        }
	}
}