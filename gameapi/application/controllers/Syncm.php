<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syncm extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all()
	{
		set_time_limit(0);

	}
    
    public function png($method = NULL){
	    $payment_gateway_code = $method;
		$prove_data['payment_gateway_code'] = $payment_gateway_code;
		$prove_data['input_get'] = json_encode($_GET);
		$prove_data['input_post'] = json_encode($_POST);
		$prove_data['input_request'] = json_encode($_REQUEST);
		$prove_data['input_json'] = file_get_contents("php://input");
		$prove_data['response_time'] = time();
		$prove_data['response_time'] = time();
		$prove_data['ip_address'] = $this->input->ip_address();
		$prove_data['input_type'] = 5;
		$this->db->insert('payment_gateway_log',$prove_data);
	}
	
	public function png_testing(){
	    $result_data = NULL;
	    $total_result_data = array();
	    $query = $this->db->get('payment_gateway_log');
	    if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();  
		}
		
		if(!empty($result_data) && sizeof($result_data)>0){
		    foreach($result_data as $result_data_row){
		        if(!empty($result_data_row['input_json'])){
		            $testing_data = json_decode($result_data_row['input_json'],true);
		            if(!empty($testing_data) && sizeof($testing_data['Messages'])>0){
		                foreach($testing_data['Messages'] as $testing_data_row){
		                    if($testing_data_row['MessageType'] != 1 && $testing_data_row['MessageType'] != 2 && $testing_data_row['MessageType'] != 5){
		                        array_push($total_result_data, $testing_data_row);
		                    }
		                }  
		            }
		        }
		    }   
		}
		ad($total_result_data);
		$bet_amount = 0;
		$payout_amount = 0;
		if(!empty($total_result_data) && sizeof($total_result_data)>0){
		    foreach($total_result_data as $total_result_row){
		        $bet_amount += $total_result_row['RoundLoss'];
		        $payout_amount += $total_result_row['Amount'];
		    }
		}
		
		ad("Bet Amount : ".$bet_amount);
		ad("Payout Amount : ".$payout_amount);
	}
	
	public function spsb_bet(){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        //$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				//$initial_time = "2022-07-25 16:35:00";
				$initial_time = "2022-07-27 19:30:00";
				//$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-0 minutes', $current_time))
				{
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					
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
                    
					$response = $this->spsb_connect($arr, $start_time, $end_time, $sync_type);
					ad($response);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['code']) && $result_array['code'] == '999')
        					{
        				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
                                    foreach($result_array['data'] as $result_row){
                                        ad($result_row);
                                        if($result_row['payout_time'] == "0000-00-00 00:00:00"){
                                           $payout_time = strtotime($result_row['m_date']);
                                        }else{
                                           $payout_time = strtotime($result_row['payout_time']);
                                        }
        				               
                                        $tmp_username = strtolower(trim($result_row['m_id']));
    							        $exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);
    								
        							    $status = STATUS_PENDING;
        								$win_result = STATUS_UNKNOWN;
        								
        								if($result_row['end'] == "1"){
        								    $status = STATUS_COMPLETE;
        								    if($result_row['status_note'] == "Y"){
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
        									'bet_match_id' => (isset($result_row['gameSN']) ? trim($result_row['gameSN']) : "0"),
        									'bet_time' => strtotime(trim($result_row['m_date'])),
        									'bet_amount' => trim($result_row['gold']),
        									'bet_amount_valid' => trim($result_row['bet_gold']),
        									'payout_time' => $payout_time,
        									'win_loss' => trim($result_row['result_gold']),
        									'game_time' => $payout_time,
        									'report_time' => $payout_time,
        									'sattle_time' => $payout_time,
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
        								
        								ad($PBdata);
        								
        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
									    {
									        if($status == STATUS_PENDING){
    									        $PBdata['bet_info'] = json_encode($result_row);
    									        $PBdata['insert_type'] = SYNC_DEFAULT;
    									        array_push($Bdata, $PBdata);
    									    }
									    }
        				           }
        				       }
        					}
						}
					}
					ad($Bdata);
					/*
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					$this->db->trans_complete();
					*/
				}
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	
	public function spsb_payout(){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        //$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
		        $arr = json_decode($game_data['api_data'], TRUE);
		        $current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				
				//$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2022-07-28 06:15:00";
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
			        $last_bet_sync_time = 0;
    				$sync_bet_data = $this->report_model->get_game_result_logs($provider_code,$result_type,SYNC_TYPE_ALL);
    				if( ! empty($sync_bet_data))
    				{
    					$last_bet_sync_time = $sync_bet_data['end_time'];
    				}
    				
    				if($last_bet_sync_time > $end_time){
    				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
    					$db_record_start_time = strtotime('-30 days' ,$start_time);
    					$db_record_end_time = strtotime('+30 days' ,$end_time);
    					$DBdata = array(
    						'game_provider_code' => $provider_code,
    						'game_result_type' => $result_type,
    						'game_sync_type' => $sync_type,
    						'start_time' => $start_time,
    						'end_time' => $end_time,
    						'sync_time' => time(),
    						'sync_status' => STATUS_NO,
    						'page_id' => $page_id,
    						'next_id' => $next_id,
    						'resp_data' => '',
    					);
    					
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
                        
                        $response = $this->spsb_connect($arr, $start_time, $end_time, $sync_type);
                        
                        if($response['code'] == '0')
    					{
    						$result_array = json_decode($response['data'], TRUE);
    						if(!empty($result_array))
    						{
    							$DBdata['resp_data'] = json_encode($result_array);
    							if(isset($result_array['code']) && $result_array['code'] == '999')
            					{
            					   $DBdata['sync_status'] = STATUS_YES;
            				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
            				           foreach($result_array['data'] as $result_row){
            				                $tmp_username = strtolower(trim($result_row['m_id']));
        							        $exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);
        								
            							    $status = STATUS_PENDING;
            								$win_result = STATUS_UNKNOWN;
            								
            								if($result_row['end'] == "1"){
            								    $status = STATUS_COMPLETE;
            								    if($result_row['status_note'] == "Y"){
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
    
                                            if($status != STATUS_PENDING){
                                                $payout_time = strtotime($result_row['payout_time']);
                                                if(($payout_time >= $start_time) && ($payout_time < $end_time)){
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
                    									'bet_match_id' => (isset($result_row['gameSN']) ? trim($result_row['gameSN']) : "0"),
                    									'bet_time' => strtotime(trim($result_row['m_date'])),
                    									'bet_amount' => trim($result_row['gold']),
                    									'bet_amount_valid' => trim($result_row['bet_gold']),
                    									'payout_time' => $payout_time,
                    									'win_loss' => trim($result_row['result_gold']),
                    									'game_time' => $payout_time,
                    									'report_time' => $payout_time,
                    									'sattle_time' => $payout_time,
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
                    								
                    								$PBdata['bet_update_info'] = json_encode($result_row);
        									        $PBdata['update_type'] = SYNC_DEFAULT;
        									        array_push($BUdata, $PBdata);
        											array_push($BUIDdata, $PBdata['bet_id']);
                                                }
                                            }
            				           }
            				       }
            					}
    						}
    					}
    					ad($BUdata);
    					ad($BUIDdata);
    					if(!empty($BUIDdata)){
    						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time, $BUIDdata);
    						if(!empty($transaction_lists_old)){
    						    foreach($BUdata as $BUdataRow){
    						        ad($BUdataRow);
    						        if(isset($transaction_lists_old[$BUdataRow['bet_id']])){
    						            echo "as";
    						            if($transaction_lists_old[$BUdataRow['bet_id']]['status'] == STATUS_COMPLETE){
        									$PBdataWL = array(
        										'player_id' => $transaction_lists_old[$BUdataRow['bet_id']]['player_id'],
        										'payout_time' => $transaction_lists_old[$BUdataRow['bet_id']]['payout_time'],
        										'game_provider_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_provider_code'],
        										'game_type_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_type_code'],
        										'total_bet' => -1,
        										'bet_amount' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount'] * -1),
        										'bet_amount_valid' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount_valid'] * -1),
        										'win_loss' => ($transaction_lists_old[$BUdataRow['bet_id']]['win_loss'] * -1),
        									);
        									array_push($BUDdata, $PBdataWL);
        								}
        								if($BUdataRow['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $BUdataRow['player_id'],
												'payout_time' => $BUdataRow['payout_time'],
												'game_provider_code' => $BUdataRow['game_provider_code'],
												'game_type_code' => $BUdataRow['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $BUdataRow['bet_amount'],
												'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
												'win_loss' => $BUdataRow['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
        								//$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
    						        }else{
    						            echo "not exits";
    						        }
    						    }
    						}else{
    						    foreach($BUdata as $BUdataRow){
    						        if($BUdataRow['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $BUdataRow['player_id'],
											'payout_time' => $BUdataRow['payout_time'],
											'game_provider_code' => $BUdataRow['game_provider_code'],
											'game_type_code' => $BUdataRow['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $BUdataRow['bet_amount'],
											'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
											'win_loss' => $BUdataRow['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
									array_push($Bdata, $BUdataRow);
    						    }
    						    echo "here";
    						}
    					}
    					ad($Bdata);
    					ad($BUDdata);
    					/*
    					if( ! empty($Bdata))
    					{
    						$this->db->insert_batch('transaction_report', $Bdata);
    					}
    					
    					if( ! empty($BUDdata))
    					{
    						$this->db->insert_batch('win_loss_logs', $BUDdata);
    					}
    					*/
    				}
				}
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	
	private function spsb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "api/report";
        if($type == SYNC_TYPE_ALL){
            $param_array = array(
    			"act" => "detail",
    			"account" => $arr['UpAccount'],
    			"level" => 2,
    			"s_date" => date('Y-m-d',$start_time),
    			"e_date" => date('Y-m-d',$end_time),
    			"start_time" => date('H:i:s',$start_time),
    			"end_time" => date('H:i:s',$end_time),
    		);
        }else{
            $param_array = array(
    		    "act" => "detail",
    			"account" => $arr['UpAccount'],
    			"level" => 2,
    			"s_date" => date('Y-m-d', strtotime('-7 days', $start_time)),
    			"e_date" => date('Y-m-d', strtotime('+7 days', $end_time)),
    	   );  
        }
        
        $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$param_array['account'] = $aes->encrypt($param_array['account']);
		$response = $this->curl_post($url, $param_array);
		
		return $response;
	}
	
	public function splt($member_lists = NULL, $sync_type = 22){
		//11:50
		set_time_limit(0);
		$provider_code = 'SPLT';
		$result_type = GAME_ALL;
		$sync_type = $sync_type;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = "2022-07-29 00:00:00";
				//$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
                    
                    $response = $this->splt_connect($arr, $start_time, $end_time, $sync_type);
                    if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['code']) && $result_array['code'] == '999')
        					{
        					    echo "asas";
        					    $DBdata['sync_status'] = STATUS_YES;
        					    //payout
        					    $max_bet_time = 0;
        					    $is_run_bet_data = true;
        					    ad($result_array);
        					    /*
        					    if(isset($result_array['State']) && ($result_array['State'] == 1)){
        					        $game_result = $result_array['Lottery'];
        					        $match_id = $result_array['Name'];
        					        if(isset($result_array['Data']) && sizeof($result_array['Data'])>0){
        					            foreach($result_array['Data'] as $result_type_row){
        					                $bet_id = $result_type_row[0];
        					                $bet_time = strtotime($result_type_row[1]);
        					                if($bet_time > $max_bet_time){
        					                    $max_bet_time = $bet_time;
        					                }
        					                $payout_time = time();
        					                $tmp_username = strtolower(trim($result_type_row['2']));
        					                $exact_username = ((substr($tmp_username, 0, strlen($arr['Prefix'])) == strtolower($arr['Prefix'])) ? substr($tmp_username, strlen($arr['Prefix'])) : $tmp_username);
        					                $game_code = trim($result_type_row['3'])."_".trim($result_type_row['4']);
        					                if(isset($result_type_row[5]) && sizeof($result_type_row[5])>0){
        					                    $i = 0;
        					                    foreach($result_type_row[5] as $result_row){
        					                        $status = STATUS_COMPLETE;
                    								$win_result = STATUS_UNKNOWN;
                    								
                    								if($result_row[5] == "0"){
                    								    if($result_row['2'] == 0){
                    								        $win_result = STATUS_LOSS;
                    								    }else if($result_row['2'] == $result_row['1']){
                    								        $win_result = STATUS_TIE;
                    								    }else{
                    								        $win_result = STATUS_WIN;
                    								    }
                                                    }else{
                								        $status = STATUS_CANCEL;
                								    }
                								    
                								    $PBdata = array(
                    									'game_provider_code' => $provider_code,
                    									'game_type_code' => GAME_LOTTERY,
                    									'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
                    									'game_result_type' => $result_type,
                    									'game_code' => $game_code,
                    									'game_real_code' => $game_code,
                    									'bet_id' => $bet_id."_".$i,
                    									'bet_transaction_id' => $i,
                    									'bet_ref_no' => $bet_id,
                    									'bet_match_id' => $match_id,
                    									'bet_time' => $bet_time,
                    									'bet_amount' => trim($result_row['1']),
                    									'bet_amount_valid' => trim($result_row['1']),
                    									'payout_time' => $payout_time,
                    									'win_loss' => trim($result_row['2']) - trim($result_row['1']),
                    									'game_time' => $payout_time,
                    									'report_time' => $payout_time,
                    									'sattle_time' => $payout_time,
                    									'compare_time' => $payout_time,
                    									'created_date' => time(),
                    									'payout_amount' => trim($result_row['2']),
                    									'promotion_amount' => trim($result_row['1']),
                    									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                    									'odds_rate' => trim($result_row['3']),
                    									'bet_code' => trim($result_row['0']),
                    									'status' => $status,
                    									'win_result' => $win_result,
                    									'game_username' => $tmp_username,
                    									'player_id' =>  (int) $exact_username,
                    								);
                    								
                    								$PBdata['bet_update_info'] = json_encode($result_row);
        									        $PBdata['update_type'] = SYNC_DEFAULT;
        									        array_push($BUdata, $PBdata);
        											array_push($BUIDdata, $PBdata['bet_id']);
        											$i++;
        					                    }
        					                }
        					            }
        					        }
        					        if($max_bet_time >= $end_time){
        					            $is_run_bet_data = true;
        					        }else{
        					            $is_run_bet_data = false;
        					        }
        					    }
        					    
        					    if($is_run_bet_data){
        					        //reset
        					        $BUdata = array();
        					        $BUIDdata = array();
        					        
        					        $match_id = $result_array['Name'];
        					        if(isset($result_array['Data']) && sizeof($result_array['Data'])>0){
        					            $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
        					            foreach($result_array['Data'] as $result_type_row){
        					                $bet_id = $result_type_row[0];
        					                $bet_time = strtotime($result_type_row[1]);
        					                $tmp_username = strtolower(trim($result_type_row['2']));
        					                $exact_username = ((substr($tmp_username, 0, strlen($arr['Prefix'])) == strtolower($arr['Prefix'])) ? substr($tmp_username, strlen($arr['Prefix'])) : $tmp_username);
        					                $game_code = trim($result_type_row['3'])."_".trim($result_type_row['4']);
        					                if(($bet_time >= $start_time) && ($bet_time < $end_time)){
            					                if(isset($result_type_row[5]) && sizeof($result_type_row[5])>0){
            					                    $i = 0;
            					                    foreach($result_type_row[5] as $result_row){
            					                        $status = STATUS_PENDING;
                        								$win_result = STATUS_UNKNOWN;
                        								
                    								    $PBdata = array(
                        									'game_provider_code' => $provider_code,
                        									'game_type_code' => GAME_LOTTERY,
                        									'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
                        									'game_result_type' => $result_type,
                        									'game_code' => $game_code,
                        									'game_real_code' => $game_code,
                        									'bet_id' => $bet_id."_".$i,
                        									'bet_transaction_id' => $i,
                        									'bet_ref_no' => $bet_id,
                        									'bet_match_id' => $match_id,
                        									'bet_time' => $bet_time,
                        									'bet_amount' => trim($result_row['1']),
                        									'bet_amount_valid' => trim($result_row['1']),
                        									'payout_time' => 0,
                        									'win_loss' => 0,
                        									'game_time' => 0,
                        									'report_time' => 0,
                        									'sattle_time' => 0,
                        									'compare_time' => 0,
                        									'created_date' => time(),
                        									'payout_amount' => 0,
                        									'promotion_amount' => trim($result_row['1']),
                        									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        									'odds_rate' => trim($result_row['3']),
                        									'bet_code' => trim($result_row['0']),
                        									'status' => $status,
                        									'win_result' => $win_result,
                        									'game_username' => $tmp_username,
                        									'player_id' =>  (int) $exact_username,
                        								);
                        								
                        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
    										            {	
                            								$PBdata['bet_info'] = json_encode($result_row);
                									        $PBdata['insert_type'] = SYNC_DEFAULT;
                											array_push($Bdata, $PBdata);
    										            }
            											$i++;
            					                    }
            					                }
        					                }
        					            }
        					        }
        					    }
        					    */
        					}
						}
					}
					/*
					$this->db->trans_start();
					if(!empty($BUIDdata)){
    					$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time, $BUIDdata);
    					if(!empty($transaction_lists_old)){
    					    foreach($BUdata as $BUdataRow){
    					        if(isset($transaction_lists_old[$BUdataRow['bet_id']])){
    					            if($transaction_lists_old[$BUdataRow['bet_id']]['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $transaction_lists_old[$BUdataRow['bet_id']]['player_id'],
    										'payout_time' => $transaction_lists_old[$BUdataRow['bet_id']]['payout_time'],
    										'game_provider_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_provider_code'],
    										'game_type_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_type_code'],
    										'total_bet' => -1,
    										'bet_amount' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount'] * -1),
    										'bet_amount_valid' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount_valid'] * -1),
    										'win_loss' => ($transaction_lists_old[$BUdataRow['bet_id']]['win_loss'] * -1),
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								if($BUdataRow['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $BUdataRow['player_id'],
    										'payout_time' => $BUdataRow['payout_time'],
    										'game_provider_code' => $BUdataRow['game_provider_code'],
    										'game_type_code' => $BUdataRow['game_type_code'],
    										'total_bet' => 1,
    										'bet_amount' => $BUdataRow['bet_amount'],
    										'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    										'win_loss' => $BUdataRow['win_loss'],
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
    					        }else{
    					            if($BUdataRow['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $BUdataRow['player_id'],
    										'payout_time' => $BUdataRow['payout_time'],
    										'game_provider_code' => $BUdataRow['game_provider_code'],
    										'game_type_code' => $BUdataRow['game_type_code'],
    										'total_bet' => 1,
    										'bet_amount' => $BUdataRow['bet_amount'],
    										'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    										'win_loss' => $BUdataRow['win_loss'],
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								array_push($Bdata, $BUdataRow);
    					        }
    					    }
    					}else{
    					    foreach($BUdata as $BUdataRow){
    					        if($BUdataRow['status'] == STATUS_COMPLETE){
    								$PBdataWL = array(
    									'player_id' => $BUdataRow['player_id'],
    									'payout_time' => $BUdataRow['payout_time'],
    									'game_provider_code' => $BUdataRow['game_provider_code'],
    									'game_type_code' => $BUdataRow['game_type_code'],
    									'total_bet' => 1,
    									'bet_amount' => $BUdataRow['bet_amount'],
    									'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    									'win_loss' => $BUdataRow['win_loss'],
    								);
    								array_push($BUDdata, $PBdataWL);
    							}
    							array_push($Bdata, $BUdataRow);
    					    }
    					}
    				}
					$this->db->insert('game_result_logs', $DBdata);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
					*/
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
		        $time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
		    }
		}else{
			echo EXIT_ERROR;
		}
	}
	
	private function splt_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "api_101/reportItem";
        $param_array = array(
			"account" => $arr['UpAccount'],
			"passwd" => $arr['UpPassword'],
			"date" => date("Y-m-d",$start_time),
			"gameID" => $type,
			"flags" => 1,
		);
        ad($param_array);
    	$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$param_array['account'] = $aes->encrypt($param_array['account']);
		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		$response = $this->curl_post($url, $param_array);
		
		return $response;
	}
	
	public function bng($member_lists = NULL){
	    set_time_limit(0);
	    $member_lists = $this->player_model->get_player_list_array();
		$provider_code = 'BNG';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = "";
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					
					$response = $this->bng_connect($arr, $start_time, $end_time, $next_id);
					ad($response);
					if($response['code'] == '0')
					{
					    if($response['http_code'] == '200'){
						    $result_array = json_decode($response['data'], TRUE);
						    if( ! empty($result_array))
							{
							    $next_id = $result_array['fetch_state'];
							    
							    if(isset($result_array['items']) && sizeof($result_array['items'])>0){
							        
							    }
							}
					    }
					}
				}
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	
	private function bng_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL){
	    //UTF
	    $start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time));
        $end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time));
                
	    $url = $arr['APIUrl'];
	    $url .= '/api/v1/transaction/list';
	    $param_array = array(
            "api_token" => $arr['Token'],
            "start_date" => $start_date,
            "end_date" => $end_date,
            "player_id" => "",
            "status" => "OK",
            "brand" => "",
            "fetch_size" => 1000,
            "fetch_state" => $next_id,
        );
        ad($url);
        ad($param_array);
        $response = $this->curl_json($url, $param_array);
        return $response;
	}
}