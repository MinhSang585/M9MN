<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync extends MY_Controller {
	var $player_winloss_list = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all($index = NULL) {
		set_time_limit(0);
		
		####REMOVE GAME RESULT LOG MORE THAN 3 day###		
		$deltime = strtotime('-3 day');		
		$this->db->where('sync_time <=', $deltime);		
		$this->db->delete('game_result_logs');		
		#############################################				

		####REMOVE API LOG MORE THAN 3 day###		
		$this->db->where('log_date <=', $deltime);		
		$this->db->delete('api_logs');
		$this->db->where('log_date <=', $deltime);		
		$this->db->delete('game_api_logs');		
		#####################################
		
		//Prepare member list
		$member_lists = $this->player_model->get_player_list_array();
		if($index == 1){
			$this->evo($member_lists);
			$this->pp($member_lists);
			$this->pp_lc($member_lists);
			sleep(5);
			$this->evo($member_lists);
			$this->pp($member_lists);
			$this->pp_lc($member_lists);
			sleep(5);
			$this->evo($member_lists);
			$this->pp($member_lists);
			$this->pp_lc($member_lists);			
		} 
		else if($index == 2){
			$this->sexy_secure($member_lists);
			sleep(3);
			$this->sxjl_secure($member_lists);
			sleep(3);
			$this->sxrt_secure($member_lists);
			sleep(3);
			$this->sxyl_secure($member_lists);
			sleep(3);
			$this->sxkm_secure($member_lists);
			sleep(3);
			$this->sxbg_secure($member_lists);
			sleep(3);
			$this->sxvn_secure($member_lists);
			sleep(3);
			$this->sxes_secure($member_lists);
			sleep(3);
		} else if($index == 3){
			$this->sexy_backup($member_lists);
			sleep(3);
			$this->sxjl_backup($member_lists);
			sleep(3);
			$this->sxrt_backup($member_lists);
			sleep(3);
			$this->sxyl_backup($member_lists);
			sleep(3);
			$this->sxkm_backup($member_lists);
			sleep(3);
			$this->sxbg_backup($member_lists);
			sleep(3);
			$this->sxvn_backup($member_lists);
			sleep(3);
			$this->sxes_backup($member_lists);
		} 
		else if($index == 4){
			$this->wm($member_lists);
			$this->dg($member_lists);
			$this->mg($member_lists);
			sleep(5);
			$this->wm($member_lists);
			$this->dg($member_lists);
			$this->mg($member_lists);
			sleep(5);
			$this->wm($member_lists);
			$this->dg($member_lists);
			$this->mg($member_lists);
			
		} 
		else if($index == 5){			
			$this->sg($member_lists);
			$this->jili($member_lists);
			sleep(5);			
			$this->sg($member_lists);
			$this->jili($member_lists);
			sleep(5);			
			$this->sg($member_lists);
			$this->jili($member_lists);
		} 
		else if($index == 6){
			$this->fc($member_lists);
			$this->fc_backup($member_lists);
			$this->jk($member_lists);
			sleep(5);
			$this->fc($member_lists);
			$this->fc_backup($member_lists);
			$this->jk($member_lists);
			sleep(5);
			$this->fc($member_lists);
			$this->fc_backup($member_lists);
			$this->jk($member_lists);			
		} 
		else if($index == 7){
			$this->pt2($member_lists);
			$this->hb($member_lists);			
			sleep(5);
			$this->pt2($member_lists);
			$this->hb($member_lists);			
			$this->km($member_lists);
			sleep(5);
			$this->pt2($member_lists);
			$this->hb($member_lists);
			$this->km($member_lists);			
		} 
		else if($index == 8){
		    $this->cq9($member_lists);
			$this->jdb($member_lists);
			$this->jdb_backup($member_lists);
			sleep(5);
			$this->cq9($member_lists);
			$this->jdb($member_lists);
			$this->jdb_backup($member_lists);
			sleep(5);
			$this->cq9($member_lists);
			$this->jdb($member_lists);
			$this->jdb_backup($member_lists);			
		} 		
		else if($index == 10){
			$this->cmd($member_lists);
			$this->ibc($member_lists);
			$this->lh($member_lists);
			sleep(5);
			$this->cmd($member_lists);
			$this->ibc($member_lists);
			$this->lh($member_lists);
			sleep(5);
			$this->cmd($member_lists);
			$this->ibc($member_lists);
			$this->lh($member_lists);			
		} else if($index == 11){
			$this->v8($member_lists);
			$this->dctr($member_lists);
			$this->ps($member_lists);
			sleep(5);
			$this->v8($member_lists);
			$this->dctr($member_lists);
			$this->ps($member_lists);
			sleep(5);
			$this->v8($member_lists);
			$this->dctr($member_lists);
			$this->ps($member_lists);			
		} 
		else if($index == 12){		    
			$this->mega();
		}
		else if($index == 14){		    			
			$this->pus8_game();						
		}		
		else if($index == 9){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("IGK","RSG","NXSP"));
			$this->igk($member_lists["IGK"]);
		    $this->igk_scan_data();
		    $this->rsg($member_lists["RSG"],GAME_SLOTS);
		    $this->rsg($member_lists["RSG"],GAME_FISHING);
		    $this->nxsp($member_lists["NXSP"]);
		}
		else if($index == 13){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("NE","GMPT"));
			$this->ne($member_lists["NE"]);	
			$this->gmpt($member_lists["GMPT"]);
			sleep(5);
			$this->ne($member_lists["NE"]);	
			$this->gmpt($member_lists["GMPT"]);
		}
	}
	
	public function gmpt($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = "GMPT";
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$result_capture_array = array();
                
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$BUIDdata = array();
				
				$DBdataAll = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = 0;
    				$db_record_end_time = 0;
    				$capture_bet_time = 0;
    				
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
					
					$game_type_code_data = array(
						'live' => GAME_LIVE_CASINO,
						'slots' => GAME_SLOTS,
						'table' => GAME_BOARD_GAME,
						'poker' => GAME_BOARD_GAME,
						'arcade' => GAME_OTHERS,
					);
					
					$is_loop = TRUE;
					$is_allow = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->gmpt_connect($arr, $start_time, $end_time, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['error']) && $result_array['error'] == '0')
                				    {
                				        $DBdata['sync_status'] = STATUS_YES;
                						$page_total = trim($result_array['pages']);
                						if(isset($result_array['records']) && !empty($result_array['records'])){
                						    $result_capture_array = array_merge($result_capture_array,$result_array['records']);
                							foreach($result_array['records'] as $result_row){
												$capture_bet_time = strtotime('+8 hours', strtotime(trim($result_row['startTime'])));
												if($db_record_start_time == 0){
		        		                            $db_record_start_time = $capture_bet_time - 300;
		        		                        }
		        		                        
		        		                        if($db_record_end_time == 0){
		        		                            $db_record_end_time = $capture_bet_time + 300;
		        		                        }
		        		                        
		        		                        if($capture_bet_time <= $db_record_start_time){
		        		                            $db_record_start_time = $capture_bet_time - 300;
		        		                        }
		        		                        
		        		                        if($capture_bet_time >= $db_record_end_time){
		        		                            $db_record_end_time = $capture_bet_time + 300;
		        		                        }
											}
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
										$is_allow = FALSE;
									}
								}else{
									$is_loop = FALSE;
									$is_allow = FALSE;
								}
							}else{
								$is_loop = FALSE;
								$is_allow = FALSE;
							}
							
							$current_page++;
							$page_id++;
							$DBdataAll[] = $DBdata;
							if($page_total > $current_page)
							{
								$is_loop = FALSE;
							}
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
					
					if($is_allow == TRUE){
			            if(!empty($result_capture_array)){
			                $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
			                foreach($result_capture_array as $result_row){
			                    $tmp_username = strtolower(trim($result_row['playerId']));
								$exact_username = $tmp_username;
				                
				                if(!empty($result_row['cancels'])){
				                    $status = STATUS_CANCEL;
				                }else{
				                    $status = STATUS_COMPLETE;
				                }
					            
					            $PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_SLOTS),
							        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_SLOTS),
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row['gameCode']),
							        'game_real_code' => trim($result_row['gameCode']),
							        'bet_id' => trim($result_row['roundId']),
							        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['startTime']))),
							        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['startTime']))),
							        'report_time' => strtotime('+8 hours', strtotime(trim($result_row['startTime']))),
							        'bet_amount' => $result_row['bets'],
							        'bet_amount_valid' => $result_row['bets'],
							        'payout_amount' => $result_row['bets'],
							        'promotion_amount' => $result_row['bets'],
							        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['endTime']))),
							        'sattle_time' =>  strtotime('+8 hours', strtotime(trim($result_row['endTime']))),
							        'compare_time' =>  strtotime('+8 hours', strtotime(trim($result_row['startTime']))),
							        'created_date' => time(),
							        'win_loss' => bcsub($result_row['wins'],$result_row['bets'],2),
							        'jackpot_win' => $result_row['jpw'],
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => $status,
							        'game_username' => $result_row['playerId'],
							        'player_id' => $member_lists[$exact_username],
							    );
				                
				                if(($result_row['roundType'] == "freegame") || ($result_row['roundType'] == "bonusgame")){
				                    $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
				                }
				            
				                if(!empty($result_row['jpw'])){
				                    $PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
				                }
							    
							    if($status == STATUS_CANCEL){
									$PBdata['payout_amount'] = 0;
									$PBdata['win_loss'] = 0;
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
			            
    
    					$this->db->trans_start();
    					$this->db->insert_batch('game_result_logs', $DBdataAll);
    					$result_promotion_reset = array('promotion_amount' => 0);
    					if( ! empty($Bdata))
    					{
    						$this->db->insert_batch('transaction_report', $Bdata);
    					}
    					if( ! empty($BUDdata))
    					{
    						$this->db->insert_batch('win_loss_logs', $BUDdata);
    					}
    					$this->db->trans_complete();
			        }
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
	
	public function gmpt_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL){
	    $url = $arr['ReportUrl'];
        $url .= '/history/game';
        
        $start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
        $end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
        
        $param_array = array(
            'requestId' => $this->rng->get_token(62),
            'brandId' => $arr['BrandID'],
            'startTime' => $start_date,
            'endTime' => $end_date,
            'showAll' => 0,
            'size' => 5000,
            'page' => $page_id,
        );
        
        ksort($param_array);
		unset($param_array['hash']);
		foreach($param_array as $k => $v) {
            if (is_array($v)) {
                $v = json_encode($v, JSON_UNESCAPED_SLASHES);
            }
            if(!empty($string)){
                $string .= '&';
            }
            $string .= $k . '=' . $v;
        }
        $hash = md5($string.$arr['SecretKey']);
        $url .= "?hash=".$hash;
        $response = $this->curl_json($url, $param_array);
		
        return $response;
	}
	
	public function nxsp($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = "NXSP";
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		
		$currency_one = array("IDR", "LAK", "MMK", "VND");
		$currency_two = array("ET2", "LT2", "BC2");
		$currency_three = array("XB3");
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));

				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$result_capture_array = array();
                
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = 0;
    				$db_record_end_time = 0;
    				$capture_bet_time = 0;
    				
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
					
					$game_type_code_data = array(
                        'SM' => GAME_SLOTS,
                        'TB' => GAME_BOARD_GAME,
                        'AD' => GAME_OTHERS,
                        'BN' => GAME_OTHERS,
                        'FH' => GAME_FISHING,
    				);
                    
					$is_loop = TRUE;
					$is_allow = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->nxsp_connect($arr, $start_time, $end_time, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['code']) && $result_array['code'] == '0')
                				    {
                				        $DBdata['sync_status'] = STATUS_YES;
                						$page_total = trim($result_array['pageCount']);
                						if(isset($result_array['list']) && !empty($result_array['list']))
                						{
                						    $result_capture_array = array_merge($result_capture_array,$result_array['list']);
                							foreach($result_array['list'] as $result_row){
												$capture_bet_time = strtotime('+0 hours', strtotime(trim($result_row['ticketTime'])));
												if($db_record_start_time == 0){
		        		                            $db_record_start_time = $capture_bet_time - 300;
		        		                        }
		        		                        
		        		                        if($db_record_end_time == 0){
		        		                            $db_record_end_time = $capture_bet_time + 300;
		        		                        }
		        		                        
		        		                        if($capture_bet_time <= $db_record_start_time){
		        		                            $db_record_start_time = $capture_bet_time - 300;
		        		                        }
		        		                        
		        		                        if($capture_bet_time >= $db_record_end_time){
		        		                            $db_record_end_time = $capture_bet_time + 300;
		        		                        }
		        		                        
		        		                        if(trim($result_row['completed']) != "1"){
		        		                            $is_loop = FALSE;
										            $is_allow = FALSE;
		        		                        }
											}
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
										$is_allow = FALSE;
									}
								}else{
									$is_loop = FALSE;
									$is_allow = FALSE;
								}
							}else{
								$is_loop = FALSE;
								$is_allow = FALSE;
							}
							
							$current_page++;
							$page_id++;
							$DBdataAll[] = $DBdata;
							if($page_total > $current_page)
							{
								$is_loop = FALSE;
							}
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
					
					if($is_allow == TRUE){
			            if(!empty($result_capture_array)){
			                $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
			                foreach($result_capture_array as $result_row){
			                    $tmp_username = strtolower(trim($result_row['acctId']));
					            $exact_username = $tmp_username;
					            
					            $status = STATUS_PENDING;
					            if(trim($result_row['completed']) == "1"){
                                    $status = STATUS_COMPLETE;
					            }
					            
					            
					            if(in_array($arr['CurrencyType'],$currency_one)){
        		                    $bet_amount = bcdiv($result_row['betAmount'] * 1000,1,2);
            		                $bet_amount_valid = bcdiv($result_row['betAmount'] * 1000,1,2);
            		                $win_loss = bcdiv($result_row['winLoss'] * 1000,1,2);
        		                }else if(in_array($arr['CurrencyType'],$currency_two)){
        		                    $bet_amount = bcdiv($result_row['betAmount'] * 0.001,1,2);
            		                $bet_amount_valid = bcdiv($result_row['betAmount'] * 0.001,1,2);
            		                $win_loss = bcdiv($result_row['winLoss'] * 0.001,1,2);
        		                }else if(in_array($arr['CurrencyType'],$currency_three)){
        		                    $bet_amount = bcdiv($result_row['betAmount'] * 0.000001,1,2);
            		                $bet_amount_valid = bcdiv($result_row['betAmount'] * 0.000001,1,2);
            		                $win_loss = bcdiv($result_row['winLoss'] * 0.000001,1,2);
        		                }else{
        		                    $bet_amount = $result_row['betAmount'];
            		                $bet_amount_valid = $result_row['betAmount'];
            		                $win_loss = $result_row['winLoss'];
        		                }
        		                
        		                
    						
					            
					            $PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => (isset($game_type_code_data[trim($result_row['categoryId'])]) ? $game_type_code_data[trim($result_row['categoryId'])] : GAME_SLOTS),
							        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['categoryId'])]) ? $game_type_code_data[trim($result_row['categoryId'])] : GAME_SLOTS),
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row['gameCode']),
							        'game_real_code' => trim($result_row['gameCode']),
							        'bet_id' => trim($result_row['ticketId']),
							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'report_time' => strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'bet_amount' => $bet_amount,
							        'bet_amount_valid' => $bet_amount_valid,
							        'payout_amount' => 0,
							        'promotion_amount' => 0,
							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'sattle_time' =>  strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'compare_time' =>  strtotime('+0 hours', strtotime(trim($result_row['ticketTime']))),
							        'created_date' => time(),
							        'win_loss' => $win_loss,
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => $status,
							        'game_username' => $result_row['acctId'],
							        'player_id' => $member_lists[$exact_username],
							    );
							    
							    if($status == STATUS_COMPLETE){
									$PBdata['payout_amount'] = bcadd($PBdata['bet_amount'], $PBdata['win_loss'],2);
									if($PBdata['win_loss'] != 0){
										$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									}
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
			            
    
    					$this->db->trans_start();
    					$this->db->insert_batch('game_result_logs', $DBdataAll);
    					$result_promotion_reset = array('promotion_amount' => 0);
    					if( ! empty($Bdata))
    					{
    						$this->db->insert_batch('transaction_report', $Bdata);
    					}
    					if( ! empty($BUDdata))
    					{
    						$this->db->insert_batch('win_loss_logs', $BUDdata);
    					}
    					$this->db->trans_complete();
			        }
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
	
	public function nxsp_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL){
	    $url = $arr['APIUrl'];
	    
	    $header_string = "API: getBetHistory";
	    
	    $start_date = date('Ymd\THis',strtotime('-0 hours', $start_time));
        $end_date = date('Ymd\THis',strtotime('-0 hours', $end_time));
                
	    $param_array =  array(
            'beginDate' => $start_date,
            'endDate' => $end_date,
            'pageIndex' => $page_id,
            'merchantCode' => $arr['MerchantCode'],
            'serialNo' => $serialNo
        );
        $response = $this->curl_json($url,$param_array,$header_string);
		
        return $response;
	}
	
	public function km($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'KM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-30 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+15 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;

                $game_type_code_data = array(
					'sugar-blast' => GAME_SLOTS,
				);
				
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
					
					$DBdata['sync_status'] = STATUS_NO;
					$DBdata['page_id'] = $page_id;
					$DBdata['resp_data'] = '';
					$Bdata = array();
					$BUDdata = array();
					$response = $this->km_connect($arr, $start_time, $end_time);
				    if($response['code'] == '0')
					{
					    $result_array = json_decode($response['data'], TRUE);
            		    $DBdata['resp_data'] = json_encode($result_array);
            		    if(isset($response['http_code']) && $response['http_code'] == '200'){
    						$DBdata['sync_status'] = STATUS_YES;
    						if(!empty($result_array))
    						{
    							if(isset($result_array) && sizeof($result_array)>0){
    								if($is_retrieve == FALSE){
    									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    									$is_retrieve = TRUE;
    								}
    								foreach($result_array as $result_row){
    								    if($result_row['playertype'] == "1"){
        									$tmp_username = strtolower(trim($result_row['userid']));
            								$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
            								
            								if($result_row['roundstatus'] == "Closed"){
            								    $status = STATUS_COMPLETE;
            								}else{
            								    $status = STATUS_PENDING;
            								}    
            								
            								if(in_array(trim($result_row['cur']),$currency_one)){
            				                    $payout_amount = trim($result_row['winamt']) * 1000;
            				                    $bet_amount = trim($result_row['riskamt']) * -1;
        										$bet_amount_valid = trim($result_row['validbet']) * 1000;
        										$win_loss = trim($result_row['winloss']) * 1000;
                        				    }else{
                        				        $payout_amount = trim($result_row['winamt']);
                    							$bet_amount = trim($result_row['riskamt']) * -1;
                    							$bet_amount_valid = trim($result_row['validbet']);
                    							$win_loss = trim($result_row['winloss']);
                    						}
                    						
                    						$PBdata = array(
        								        'game_provider_code' => $provider_code,
        								        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameid'])]) ? $game_type_code_data[trim($result_row['gameid'])] : GAME_BOARD_GAME),
        								        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['gameid'])]) ? $game_type_code_data[trim($result_row['gameid'])] : GAME_BOARD_GAME),
        								        'game_result_type' => $result_type,
        								        'game_code' => trim($result_row['gamename']),
        								        'game_real_code' => trim($result_row['gameid']),
        								        'bet_id' => trim($result_row['ugsbetid']),
        								        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['beton']))),
        								        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['betclosedon']))),
        								        'report_time' => strtotime('+0 hours', strtotime(trim($result_row['betupdatedon']))),
        								        'bet_amount' => $bet_amount,
        								        'bet_amount_valid' => $bet_amount_valid,
        								        'payout_amount' => $payout_amount,
        								        'promotion_amount' => 0,
        								        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['betupdatedon']))),
        								        'sattle_time' =>  strtotime('+0 hours', strtotime(trim($result_row['betupdatedon']))),
        								        'compare_time' =>  strtotime('+0 hours', strtotime(trim($result_row['betupdatedon']))),
        								        'created_date' => time(),
        								        'win_loss' => $win_loss,
        								        'game_round_type' => (($bet_amount==0)? GAME_ROUND_TYPE_FREE_SPIN: GAME_ROUND_TYPE_GAME_ROUND),
        								        'status' => $status,
        								        'game_username' => $result_row['userid'],
        								        'player_id' => $member_lists[$exact_username],
        								    );
            								
            								if($PBdata['win_loss'] != 0){
            								    $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
            								}
            								
            								if( ! in_array($PBdata['bet_id'], $transaction_lists))
        									{					
        										if($PBdata['status'] == STATUS_COMPLETE){
        										    $PBdata['bet_info'] = json_encode($result_row);
            								        $PBdata['insert_type'] = SYNC_DEFAULT;
            										array_push($Bdata, $PBdata);
        											
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
    								    }
    								}
    							}
    						}
						}
					}
					$this->db->trans_start();
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
		}
		else{
			echo EXIT_ERROR;
		}
		
	}
	
	private function km_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
        $url = $arr['APIUrl'];
        $header = array(
		    "Content-Type: application/json",
		    "X-QM-Accept: json",
		    "X-QM-ClientId: ".$arr['ClientID'],
		    "X-QM-ClientSecret: ".$arr['ClientSecret'],
		);
        
        $url .= "/api/history/bets";
        $param_array = array(
            'startdate' => date('c',$start_time),
            'enddate' => date('c',$end_time),
            'includetestplayers' => "false",
            'issettled' => "true",
            'includejackpotcontribution' => "false",
        );
        $response = $this->curl_get_json_km($url."?".http_build_query($param_array),$header);
        return $response;
    }
    
	public function pus8_game(){
		set_time_limit(0);
		$provider_code = 'PUS8';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$member_lists = $this->player_model->get_player_list_game_id_array($provider_code);
		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data) && !empty($member_lists))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				foreach($member_lists as $game_id => $player_id) {
					$this->pus8_by_player($arr,$current_time,$game_id,$player_id);
					sleep(5);
				}
				$provider_code = 'PUS8';
				$result_type = GAME_SLOTS;
				$sync_type = SYNC_TYPE_ALL;
				sleep(3);
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}		
	}

	public function pus8_by_player($arr = NULL,$current_time = NULL,$game_id = NULL, $player_id = NULL){
		set_time_limit(0);
		$provider_code = 'PUS8';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$current_time = $current_time;
		$last_sync_time = strtotime(date('Y-m-d 00:00:00',strtotime('-2160 minutes', $current_time)));
		#$last_sync_time = strtotime('-15 minutes', $current_time);
		$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type,$game_id);
		if( ! empty($sync_data))
		{
			$last_sync_time = $sync_data['end_time']+1;
		}
		$initial_time = date('Y-m-d H:i:00', $last_sync_time);
		$start_time = strtotime($initial_time);
		$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+1439 minutes', strtotime($initial_time))));
		#$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
		$page_id = 1;
		$page_total = 1;
		$current_page = 0;
		$next_id = 0;
		$Bdata = array();
		$BUdata = array();
		$BUIDdata = array();
		$BUDdata = array();
		$is_loop = TRUE;
		if($end_time <= strtotime('-720 minutes', $current_time)) {
		#if($end_time <= strtotime('-5 minutes', $current_time)) {	
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
		    $db_record_start_time = strtotime('-5 days' ,$start_time);
			$db_record_end_time = strtotime('+1 days' ,$start_time);
			#$db_record_start_time 	= strtotime('-20 minutes' ,$start_time);
			#$db_record_end_time 	= strtotime('+20 minutes' ,$end_time);
			$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
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
				'game_id' => $game_id,
				'resp_data' => '',
			);
			$response = $this->pus8_connect($arr, $start_time, $end_time,$game_id);
			if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
				if( ! empty($result_array))
				{
				    $DBdata['resp_data'] = json_encode($result_array);
				    if(array_key_exists('success',$result_array) && $result_array['success'] == true)
					{
					    $DBdata['sync_status'] = STATUS_YES;
					    if(array_key_exists('results',$result_array) && !empty($result_array['results'])) {
						    
						    foreach($result_array['results'] as $result_row){
						        $PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => GAME_SLOTS,
									'game_provider_type_code' => $provider_code . '_' . GAME_SLOTS,
							        'game_result_type' => $result_type,
							        'game_code' => '',
							        'game_real_code' => '',
							        'bet_id' => $game_id.trim($result_row['mydate']),
							        'bet_time' => $start_time,
							        'game_time' => $start_time,
					       			'report_time' => $start_time,
							        'bet_amount' => 0,
							        'bet_amount_valid' => 0,
							        'payout_amount' => 0,
							        'promotion_amount' => 0,
							        'payout_time' => $start_time,
							        'win_loss' => (trim($result_row['win'])*-1),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => STATUS_COMPLETE,
							        'game_username' => $game_id,
							        'player_id' => $player_id,
							    );

						    	if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{	
									$PBdata['bet_info'] = json_encode($result_row);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
								}else{
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
						}
					}
				}
			}
			$this->db->trans_start();
			$this->db->insert('game_result_logs', $DBdata);
			$result_promotion_reset = array('promotion_amount' => 0);
			
			if(!empty($BUIDdata)){
				$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
				if( ! empty($transaction_lists_old)){
					foreach($transaction_lists_old as $transaction_lists_old_row){
						if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
							$PBdataWL = array(
								'player_id' => $transaction_lists_old_row['player_id'],
								'payout_time' => $transaction_lists_old_row['payout_time'],
								'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
								'game_type_code' => $transaction_lists_old_row['game_type_code'],
								'total_bet' => -1,
								'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
								'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
								'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
							);
							array_push($BUDdata, $PBdataWL);
						}
					}
				}
			}
			
			if( ! empty($Bdata)) {
				$this->db->insert_batch('transaction_report', $Bdata);
			}
			if( ! empty($BUDdata)) {
				$this->db->insert_batch('win_loss_logs', $BUDdata);
			}
			if( ! empty($BUdata))
			{
				foreach($BUdata as $BUdataRow){
					$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$BUdataRow['bet_id'],$BUdataRow);
				}
			}
			$this->db->trans_complete();
		}
		echo EXIT_SUCCESS;
	}
	
	private function pus8_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$game_id = NULL){
        $this->load->library('rng');
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
		$url = $arr['APIUrl2'];
		$url .= 'ashx/AccountReport.ashx?sDate='.str_replace(' ','%20',$start_date).'&eDate='.str_replace(' ','%20',$end_date).'&userName='.$game_id.'&time='.$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $game_id .$timestamp . $arr['SecretKey'])));
		$response = $this->curl_get($url);
		return $response;
	}
	
	private function mega_connect($arr = NULL,$start_time = NULL,$end_time = NULL,$type = NULL,$loginId = NULL){
	    $start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
	    $url = $arr['APIUrl'];
	    $param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => $arr['SN'],
			),
			"jsonrpc" => $arr['JsonRPC'],
		);

		if (!empty($loginId)) {
			$url .= "open.mega.game.order.page";
			$param_array['method'] = "open.mega.game.order.page";
			$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$loginId.$arr['SecretCode']);
			$param_array['params']['pageSize'] = 9999;
			$param_array['params']['loginId'] = $loginId;
		} else {
			$url .= "open.mega.player.total.report";
			$param_array['method'] = "open.mega.player.total.report";
			$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$arr['Account'].$arr['SecretCode']);
		}
		
		$param_array['params']['agentLoginId'] = $arr['Account'];
		$param_array['params']['type'] = $type;
		$param_array['params']['startTime'] = $start_date;
		$param_array['params']['endTime'] = $end_date;

		$response = $this->curl_post_xe($url, $param_array);

		// echo '<pre>';
		// print_r($param_array);
		
		// echo '<pre>';
		// print_r($response);

		return $response;
	}
	
	public function mega(){
		#GMT 8
		set_time_limit(0);
		$provider_code = 'MEGA';
		$result_type = 1;
		$sync_type = SYNC_TYPE_ALL;
        $member_lists = $this->player_model->get_player_list_game_id_array($provider_code);
		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) && !empty($game_result_data) &&  !empty($member_lists))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				// $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				#$last_sync_time = strtotime('-2160 minutes', $current_time);
				$last_sync_time = strtotime('-5 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data)) {
					$last_sync_time = $sync_data['end_time']+1;
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				if (date("d", $start_time) != date("d", $current_time)) {
					$start_time = strtotime(date('Y-m-d 23:55:00', strtotime('-1 days', $current_time)));
				}
				#$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+1439 minutes', strtotime($initial_time))));
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('-1 minutes', $current_time)));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				#Must 5 minutes range from current time
				#if($end_time <= strtotime('-720 minutes', $current_time)) {

				log_message('error', print_r("Start time: ", true));
				log_message('error', print_r(date('Y-m-d H:i:s', $start_time), true));

				log_message('error', print_r("End time: ", true));
				log_message('error', print_r(date('Y-m-d H:i:s', $end_time), true));

				log_message('error', print_r("Current time: ", true));
				log_message('error', print_r(date('Y-m-d H:i:s', $current_time), true));

				if($end_time <= $current_time) {
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$gameList = $this->game_model->getSubGameByProvider($provider_code);
					// $db_record_start_time 	= strtotime('-5 minutes' ,$start_time);
					#$db_record_end_time 	= strtotime('+20 minutes' ,$end_time);
					#$db_record_start_time = strtotime('-5 days' ,$start_time);
					// $db_record_end_time = $end_time;
					// $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);

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

					$response = $this->mega_connect($arr, $start_time, $end_time,$result_type);
					if($response['code'] == '0'){
					    $result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array)){
						    if(array_key_exists('error',$result_array) && empty($result_array['error'])){
							    if(array_key_exists('result',$result_array) && !empty($result_array['result'])){
								    foreach($result_array['result'] as $result_row){

										$res = $this->mega_connect($arr, $start_time, $end_time,$result_type,$result_row['loginId']);

										if($res['code'] == '0'){
											$resultArray = json_decode($res['data'], TRUE);
											if( ! empty($resultArray)){
												$DBdata['resp_data'] = json_encode($resultArray);
												if(array_key_exists('error',$resultArray) && empty($resultArray['error'])){
													$DBdata['sync_status'] = STATUS_YES;
													if(array_key_exists('items',$resultArray['result']) && !empty($resultArray['result']['items'])){

														foreach($resultArray['result']['items'] as $row){
															$PBdata = array(
																'game_provider_code' => $provider_code,
																'game_type_code' => $gameList[$row['gameId']]['game_type_code'],
																'game_provider_type_code' => $provider_code . '_' . $gameList[$row['gameId']]['game_type_code'],
																'game_result_type' => $result_type,
																'game_code' => $row['gameId'],
																'game_real_code' => '',
																'bet_id' => $result_type.date('YmdHis', strtotime($row['createTime'])).$result_row['loginId'].$row['id'],
																'bet_time' => strtotime($row['createTime']),
																'game_time' => strtotime($row['createTime']),
																'report_time' => strtotime($row['createTime']),
																'bet_amount' => trim($row['bet']),
																'bet_amount_valid' => trim($row['bet']),
																'payout_amount' => (trim($row['win'])*-1)+trim($row['bet']),
																'promotion_amount' => 0,
																'payout_time' => strtotime($row['createTime']),
																'created_date' => strtotime($row['createTime']),
																'win_loss' => (trim($row['win'])*-1),
																'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
																'status' => STATUS_COMPLETE,
																'game_username' => $result_row['loginId'],
																'player_id' => $member_lists[$result_row['loginId']],
															);
															if($PBdata['win_loss'] != 0){
																$PBdata['promotion_amount'] = $PBdata['bet_amount'];
															}
					
															// if(!in_array($PBdata['bet_id'], $transaction_lists)){	
																$PBdata['bet_info'] = json_encode($row);
																$PBdata['insert_type'] = SYNC_DEFAULT;
																array_push($Bdata, $PBdata);
															// }
															// else{
															// 	$PBdata['bet_update_info'] = json_encode($row);
															// 	$PBdata['update_type'] = SYNC_DEFAULT;
															// 	array_push($BUdata, $PBdata);
															// 	array_push($BUIDdata, $PBdata['bet_id']);
															// }
															
															if($PBdata['status'] == STATUS_COMPLETE){
																$PBdataWL = array(
																	'player_id' => $PBdata['player_id'],
																	'payout_time' => $PBdata['payout_time'],
																	'bet_time' => $PBdata['bet_time'],
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
					}
					
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					// $result_promotion_reset = array('promotion_amount' => 0);
					
					// if(!empty($BUIDdata)){
					// 	$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
					// 	if( ! empty($transaction_lists_old)){
					// 		foreach($transaction_lists_old as $transaction_lists_old_row){
					// 			if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
					// 				$PBdataWL = array(
					// 					'player_id' => $transaction_lists_old_row['player_id'],
					// 					'payout_time' => $transaction_lists_old_row['payout_time'],
					// 					'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
					// 					'game_type_code' => $transaction_lists_old_row['game_type_code'],
					// 					'total_bet' => -1,
					// 					'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
					// 					'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
					// 					'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
					// 				);
					// 				array_push($BUDdata, $PBdataWL);
					// 			}
					// 		}
					// 	}
					// }
					
					if( ! empty($Bdata)) {
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					
					if( ! empty($BUDdata)) {
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					
					// if( ! empty($BUdata)) {
					// 	foreach($BUdata as $BUdataRow){
					// 		$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$BUdataRow['bet_id'],$BUdataRow);
					// 	}
					// }

					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	
	public function mega_bg(){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'MEGA';
		$result_type = 2;
		$sync_type = SYNC_TYPE_ALL;
        $member_lists = $this->player_model->get_player_list_game_id_array($provider_code);
		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) && !empty($game_result_data) &&  !empty($member_lists))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-2160 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+1439 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-720 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-5 days' ,$start_time);
					$db_record_end_time = $end_time;
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
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

					$response = $this->mega_connect($arr, $start_time, $end_time,$result_type);
					if($response['code'] == '0')
					{
					    $result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
						    $DBdata['resp_data'] = json_encode($result_array);
						    if(array_key_exists('error',$result_array) && empty($result_array['error']))
							{
							    $DBdata['sync_status'] = STATUS_YES;
							    if(array_key_exists('result',$result_array) && !empty($result_array['result']))
								{
								    
								    foreach($result_array['result'] as $result_row){
								        $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_SLOTS,
									        'game_result_type' => $result_type,
									        'game_code' => '',
									        'game_real_code' => '',
									        'bet_id' => $result_type.date('YmdHis', $start_time).$result_row['loginId'],
									        'bet_time' => $start_time,
									        'game_time' => $start_time,
							       			'report_time' => $start_time,
									        'bet_amount' => trim($result_row['bet']),
									        'bet_amount_valid' => trim($result_row['bet']),
									        'payout_amount' => (trim($result_row['win'])*-1)+trim($result_row['bet']),
									        'promotion_amount' => 0,
									        'payout_time' => $start_time,
									        'win_loss' => (trim($result_row['win'])*-1),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['loginId'],
									        'player_id' => $member_lists[$result_row['loginId']],
									    );
									    if($PBdata['win_loss'] != 0){
								    		$PBdata['promotion_amount'] = $PBdata['bet_amount'];
								    	}

								    	if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{	
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
											$PBdata['bet_update_info'] = json_encode($result_row);
									        $PBdata['update_type'] = SYNC_DEFAULT;
											array_push($BUdata, $PBdata);
										}
								    }
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	
	public function v8($member_lists = NULL){
	    #GMT 8
		set_time_limit(0);
		$provider_code = 'V8';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
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

					
					$response = $this->v8_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(isset($result_array['d'])){
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['d']['code']) && ($result_array['d']['code']=="16" || $result_array['d']['code']=="0")){
								$DBdata['sync_status'] = STATUS_YES;
								if($result_array['d']['code']=="0"){
									if(isset($result_array['d']['list']) && $result_array['d']['count']>0){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$result_row = $result_array['d']['list'];
										for($i=0;$i<$result_array['d']['count'];$i++){
										    (strpos($result_row['Accounts'][$i], $arr['Agent']) !== false) ? $le_username = substr(trim($result_row['Accounts'][$i]), strlen($arr['Agent'])+1) : $le_username = trim($result_row['Accounts'][$i]);
										  	$tmp_username = strtolower(trim($le_username));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											$PBdata = array(
            									'game_provider_code' => $provider_code,
            									'game_type_code' => GAME_BOARD_GAME,
            									'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
            									'game_result_type' => $result_type,
            									'game_code' => trim($result_row['KindID'][$i]),
            									'game_real_code' => trim($result_row['KindID'][$i]),
            									'bet_id' => trim($result_row['GameID'][$i]),
            									'bet_time' => strtotime(trim($result_row['GameStartTime'][$i])),
            									'bet_amount' => trim($result_row['AllBet'][$i]),
            									'bet_amount_valid' => trim($result_row['CellScore'][$i]),
            									'payout_time' => strtotime(trim($result_row['GameEndTime'][$i])),
            									'win_loss' =>  trim($result_row['Profit'][$i]),
            									'game_time' => strtotime(trim($result_row['GameStartTime'][$i])),
            									'sattle_time' => strtotime(trim($result_row['GameEndTime'][$i])),
            									'compare_time' => strtotime(trim($result_row['GameEndTime'][$i])),
            									'created_date' => time(),
            									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            									'status' => STATUS_COMPLETE,
            									'game_username' => trim($result_row['Accounts'][$i]),
            									'table_id' => trim($result_row['TableID'][$i]),
            									'game_result' => trim($result_row['CardValue'][$i]),
            									#'player_id' => $member_lists[$exact_username],
												'player_id' => $member_lists[$tmp_username],
            									'bet_info' => json_encode($result_row),
            									'insert_type' => SYNC_DEFAULT,
            								);
											
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{								
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
					
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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
    
    private function v8_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$start_date = str_pad($start_time, 13, 0);
		$end_date = str_pad($end_time, 13, 0);
		$orderid = $aes->getOrderId($arr['Agent']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();

		$str = 's=6&startTime=' . $start_date . '&endTime='.$end_date;
		$param = urlencode($aes->encrypt($str));
		$param_array = array(
			"agent" => $arr['Agent'],
			"timestamp" => $timestamp,
			"param" => $param,
			"key" => md5($arr['Agent'] . $timestamp . $arr['Md5key'])
		);
		$url = $arr['ReportUrl'] . '?' . urldecode(http_build_query($param_array));
		
		$response = $this->curl_get($url);
		return $response;
	}
	
	public function dctr($member_lists = NULL){
        set_time_limit(0);
		$provider_code = 'DCTR';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$next_id = 0;
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				
				if($next_id == 0 ){
				   $next_id = $start_time;
				}
				
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$current_next_id = $next_id;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-10 minutes' ,$next_id);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
					
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
					
					$board_game_array = array(
					    '150210' => GAME_BOARD_GAME,
					    '150186' => GAME_BOARD_GAME,
					);
					$result_size = array();
					
					$response = $this->dctr_connect($arr, $next_id, $end_time);
					if($response['code'] == '0')
				    {
				        $result_array = json_decode($response['data'], TRUE);
				        $DBdata['resp_data'] = json_encode($result_array);
				        if(!empty($result_array)){
				            if(isset($result_array['code']) && ($result_array['code'] == "1000" || $result_array['code'] == "5042"))
				            {
				                $DBdata['sync_status'] = STATUS_YES;
				                $current_next_id = $end_time;
				                if(isset($result_array['data']) && !empty($result_array['data']) && sizeof($result_array['data']) > 0){
				                    $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
				                    $result_size = sizeof($result_array['data']);
				                    foreach($result_array['data'] as $result_row){
				                        $current_next_id = strtotime('+8 hours', strtotime(trim($result_row['create_time'])));
				                        $tmp_username = strtolower(trim($result_row['brand_uid']));
        						        $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
				                        if($result_row['wager_type'] == "wager"){
				                            $info = array("wager" =>  $result_row);
				                            $PBdata = array(
                								'game_provider_code' => $provider_code,
                								'game_type_code' => (isset($board_game_array[trim($result_row['game_id'])]) ? $board_game_array[trim($result_row['game_id'])] : GAME_SLOTS),
                						        'game_provider_type_code' => $provider_code."_".(isset($board_game_array[trim($result_row['game_id'])]) ? $board_game_array[trim($result_row['game_id'])] : GAME_SLOTS),
                						        'game_result_type' => $result_type,
                								'game_code' => trim($result_row['game_name']),
                								'game_real_code' => trim($result_row['game_id']),
                								'bet_id' => trim($result_row['round_id']),
                								'bet_transaction_id' => trim($result_row['wager_id']),
                								'bet_ref_no' => '',
                								'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'bet_amount' => trim($result_row['amount']),
                								'bet_amount_valid' => trim($result_row['amount']),
                								'game_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'payout_time' => 0,
                								'sattle_time' => 0,
                								'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'created_date' => time(),
                								'win_loss' =>  0,
                								'jackpot_win' => 0,
                								'payout_amount' => 0,
                								'game_round_type' => ((trim($result_row['amount'])==0)? GAME_ROUND_TYPE_FREE_SPIN: GAME_ROUND_TYPE_GAME_ROUND),
                								'status' => STATUS_PENDING,
                								'game_username' => trim($result_row['brand_uid']),
                								'player_id' =>  $member_lists[$exact_username],
                								'bet_info' => json_encode($info,true),
                								'bet_update_info' => '',
                							);
                							
                							if( ! in_array($PBdata['bet_id'], $transaction_lists))
            	                        	{
            	                        	    $Bdata[trim($result_row['round_id'])] = $PBdata;
            	                        	}
				                        }else if($result_row['wager_type'] == "endWager"){
				                            $id = trim($result_row['round_id']);
				                            if(isset($Bdata[$id])){
				                                $info = json_decode($Bdata[$id]['bet_info'],true);
				                                $info['endWager'] = $result_row;
				                                $Bdata[$id]['status'] = STATUS_COMPLETE;
                		                        $Bdata[$id]['bet_ref_no'] = trim($result_row['wager_id']);
                		                        $Bdata[$id]['payout_time'] = strtotime('+8 hours', strtotime(trim($result_row['create_time'])));
                		                        $Bdata[$id]['sattle_time'] = strtotime('+8 hours', strtotime(trim($result_row['create_time'])));
                		                        $Bdata[$id]['payout_amount'] = $Bdata[$id]['payout_amount'] + trim($result_row['amount']);
                		                        $Bdata[$id]['win_loss'] = trim($result_row['amount']) - $Bdata[$id]['bet_amount'];
                		                        $Bdata[$id]['bet_update_info'] = json_encode($info,true);
                		                        
                		                        $PBdataWL = array(
                        							'player_id' => $Bdata[$id]['player_id'],
                        							'payout_time' => $Bdata[$id]['payout_time'],
                        							'game_provider_code' => $Bdata[$id]['game_provider_code'],
                        							'game_type_code' => $Bdata[$id]['game_type_code'],
                        							'total_bet' => 1,
                        							'bet_amount' => $Bdata[$id]['bet_amount'],
                        							'bet_amount_valid' => $Bdata[$id]['bet_amount_valid'],
                        							'win_loss' => $Bdata[$id]['win_loss'],
                        						);
                        						array_push($BUDdata, $PBdataWL);
				                            }else{
				                                $info = array("endWager" =>  $result_row);
                		                        $BUdata[$id] = array(
                		                              'status' => STATUS_COMPLETE,
                		                              'bet_ref_no' => trim($result_row['wager_id']),
                		                              'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                		                              'payout_amount' => trim($result_row['amount']),
                		                              'bet_update_info' => json_encode($info,true),
                		                        );
                		                        array_push($BUIDdata, $id);   
				                            }
				                        }else if($result_row['wager_type'] == "cancelWager"){
				                            $id = trim($result_row['round_id']);
                		                    if(isset($Bdata[$id])){
                		                        $info = json_decode($Bdata[$id]['bet_info'],true);
                		                        $info['cancelWager'] = $result_row;
                		                        $Bdata[$id]['status'] = STATUS_CANCEL;
                		                        $Bdata[$id]['bet_ref_no'] = trim($result_row['wager_id']);
                		                        $Bdata[$id]['payout_time'] = strtotime('+8 hours', strtotime(trim($result_row['create_time'])));
                		                        $Bdata[$id]['sattle_time'] = strtotime('+8 hours', strtotime(trim($result_row['create_time'])));
                		                        $Bdata[$id]['payout_amount'] = 0;
                		                        $Bdata[$id]['win_loss'] = 0;
                		                        $Bdata[$id]['bet_update_info'] = json_encode($info,true);
                		                    }else{
                		                        $info = array("cancelWager" =>  $result_row);
                		                        $BUdata[$id] = array(
                		                            'status' => STATUS_CANCEL,
            		                                'bet_ref_no' => trim($result_row['wager_id']),
            		                                'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
            		                                'payout_amount' => 0,
            		                                'bet_update_info' => json_encode($info,true),
                		                        );
                		                        array_push($BUIDdata, $id);
                		                    }
				                        }else if($result_row['wager_type'] == "appendWagerResult"){
                		                    $info = array("appendWagerResult" =>  $result_row);
                		                    $PBdata = array(
                								'game_provider_code' => $provider_code,
                								'game_type_code' => (isset($board_game_array[trim($result_row['game_id'])]) ? $board_game_array[trim($result_row['game_id'])] : GAME_SLOTS),
                						        'game_provider_type_code' => $provider_code."_".(isset($board_game_array[trim($result_row['game_id'])]) ? $board_game_array[trim($result_row['game_id'])] : GAME_SLOTS),
                						        'game_result_type' => $result_type,
                								'game_code' => trim($result_row['game_name']),
                								'game_real_code' => trim($result_row['game_id']),
                								'bet_id' => trim($result_row['round_id']),
                								'bet_transaction_id' => trim($result_row['wager_id']),
                								'bet_ref_no' => '',
                								'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'bet_amount' => 0,
                								'bet_amount_valid' => 0,
                								'game_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'sattle_time' => 0,
                								'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['create_time']))),
                								'created_date' => time(),
                								'win_loss' =>  trim($result_row['amount']),
                								'jackpot_win' => trim($result_row['amount']),
                								'payout_amount' => trim($result_row['amount']),
                								'game_round_type' => GAME_ROUND_TYPE_JACKPOT,
                								'status' => STATUS_COMPLETE,
                								'game_username' => trim($result_row['brand_uid']),
                								'player_id' =>  $member_lists[$exact_username],
                								'bet_info' => json_encode($info,true),
                								'bet_update_info' => '',
                							);
                							if( ! in_array($PBdata['bet_id'], $transaction_lists))
            	                        	{
            	                        	    $Bdata[trim($result_row['id'])] = $PBdata;
            	                        	}
                		                }
				                    }
				                }
				                if($next_id != $current_next_id){
                                    $DBdata['next_id'] = $current_next_id;
                    	        }else{
                    	            $DBdata['next_id'] = $current_next_id +1;
                    	        }
				            }
				        }
				    }
				    $Bdata = array_values($Bdata);
					$this->db->trans_start();
					if(!empty($BUIDdata)){
        				$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
        				if( ! empty($transaction_lists_old)){
        					foreach($transaction_lists_old as $transaction_lists_old_row_key => $transaction_lists_old_row){
        						if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
        						    if($BUdata[$transaction_lists_old_row_key]['status'] != STATUS_COMPLETE){
        						        $PBdataWL = array(
        									'player_id' => $transaction_lists_old_row['player_id'],
        									'payout_time' => $transaction_lists_old_row['payout_time'],
        									'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
        									'game_type_code' => $transaction_lists_old_row['game_type_code'],
        									'total_bet' => -1,
        									'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
        									'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
        									'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
        								);
        								array_push($BUDdata, $PBdataWL);
        						    }
        						}
        						if(isset($BUdata[$transaction_lists_old_row_key])){
        						    if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
        						        if($BUdata[$transaction_lists_old_row_key]['status'] == STATUS_COMPLETE){
        						            
        						        }
        						    }else{
        						        if($BUdata[$transaction_lists_old_row_key]['status'] == STATUS_COMPLETE){
        							        $BUdataRow = array(
        							            'status' =>  STATUS_COMPLETE,
        							            'bet_ref_no' => $BUdata[$transaction_lists_old_row_key]['bet_ref_no'],
        							            'payout_time' => $BUdata[$transaction_lists_old_row_key]['payout_time'],
        							            'sattle_time' => $BUdata[$transaction_lists_old_row_key]['payout_time'],
        							            'payout_amount' => $BUdata[$transaction_lists_old_row_key]['payout_amount'],
        							            'win_loss' => $BUdata[$transaction_lists_old_row_key]['payout_amount'] - $transaction_lists_old_row['bet_amount'],
        							            'bet_update_info' => $BUdata[$transaction_lists_old_row_key]['bet_update_info'],
        							        );
        							        $this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$transaction_lists_old_row_key,$BUdataRow);
        							        $PBdataWL = array(
        										'player_id' => $transaction_lists_old_row['player_id'],
        										'payout_time' => $BUdataRow['payout_time'],
        										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
        										'game_type_code' => $transaction_lists_old_row['game_type_code'],
        										'total_bet' => 1,
        										'bet_amount' => $transaction_lists_old_row['bet_amount'],
        										'bet_amount_valid' => $transaction_lists_old_row['bet_amount_valid'],
        										'win_loss' => $BUdataRow['win_loss'],
        									);
        									array_push($BUDdata, $PBdataWL);
        							    }else if($BUdata[$transaction_lists_old_row_key]['status'] == STATUS_CANCEL){
        							        $BUdataRow = array(
        							            'status' =>  STATUS_CANCEL,
        							            'bet_ref_no' => $BUdata[$transaction_lists_old_row_key]['bet_ref_no'],
        							            'payout_time' => $BUdata[$transaction_lists_old_row_key]['payout_time'],
        							            'sattle_time' => $BUdata[$transaction_lists_old_row_key]['payout_time'],
        							            'payout_amount' => $BUdata[$transaction_lists_old_row_key]['payout_amount'],
        							            'win_loss' => $BUdata[$transaction_lists_old_row_key]['payout_amount'] - $transaction_lists_old_row['bet_amount'],
        							            'bet_update_info' => $BUdata[$transaction_lists_old_row_key]['bet_update_info'],
        							        );
        							        $this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$transaction_lists_old_row_key,$BUdataRow);
        							    }
        						    }
        						}
        					}
        				}
        	        }
    		        
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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
    
    private function dctr_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
	    $url = $arr['ReportUrl'];
	    $url .= "/dct/getBetData";
	    
		$start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
    	$end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
		
	    $param_array = array(
	        'brand_id' => $arr['BrandID'],
	        'page' => 1,
	        'start_time' => $start_date,
	        'end_time' => $end_date,
	        'currency' => $arr['Currency'],
	        'provider' => $arr['ProviderCode'],
	    );
	    $param_array['sign'] = strtoupper(md5($param_array['brand_id'].$param_array['start_time'].$param_array['end_time'].$arr['ApiKey']));
		$response = $this->curl_json($url,$param_array);
		return $response;
	}
	
	public function igk($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = "IGK";
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$result_capture_array = array();
                
                $team_capture_array = array();
                $league_capture_array = array();
                $sporttype_capture_array = array();
                $parlay_capture_array = array();
                $content_capture_array = array();
		
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = 0;
    				$db_record_end_time = 0;
    				$capture_bet_time = 0;
    				
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
                    
                    $is_allow = false;
					$response = $this->igk_connect($arr, $start_time, $end_time, "RetrieveRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
                        if( ! empty($result_array))
    					{
    					    if(isset($result_array['errcode']) && $result_array['errcode'] == '0')
    						{   
    						    $is_allow = true;
    						    $DBdata['resp_data'] = json_encode($result_array);
    						    $DBdata['sync_status'] = STATUS_YES;
    						    if(isset($result_array['result']['ticket']) &&  sizeof($result_array['result']['ticket'])>0){
    						        foreach($result_array['result']['ticket'] as $result_row){
    						            $capture_bet_time = strtotime(trim($result_row['trandate']));
    									if($db_record_start_time == 0){
        		                            $db_record_start_time = $capture_bet_time - 300;
        		                        }
        		                        
        		                        if($db_record_end_time == 0){
        		                            $db_record_end_time = $capture_bet_time + 300;
        		                        }
        		                        
        		                        if($capture_bet_time <= $db_record_start_time){
        		                            $db_record_start_time = $capture_bet_time - 300;
        		                        }
        		                        
        		                        if($capture_bet_time >= $db_record_end_time){
        		                            $db_record_end_time = $capture_bet_time + 300;
        		                        }
        		                        
        		                        if(isset($result_capture_array[$result_row['id']])){
        		                            if($result_row['t'] >= $result_capture_array[$result_row['id']]['t']){
        		                                $result_capture_array[$result_row['id']] = $result_row;
        		                            }
        		                        }else{
        		                            $result_capture_array[$result_row['id']] = $result_row;
        		                        }
        		                        $team_capture_array[$result_row['home']] = 1;
        		                        $team_capture_array[$result_row['away']] = 1;
        		                        $league_capture_array[$result_row['league']] = 1;
        		                        $sporttype_capture_array[$result_row['sportstype']] = 1;
        		                        if($result_row['game'] == "PAR"){
        		                            $parlay_capture_array[$result_row['id']] = 1;
        		                        }
        		                        array_push($BdataID, $result_row['fid']);
    						        }
    						    }
    						}
    					}
					}
					
					if($is_allow == TRUE){
			            if(!empty($result_capture_array)){
			                $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
			                foreach($result_capture_array as $result_row){
			                    $tmp_username = strtolower(trim($result_row['u']));
					            $exact_username = $tmp_username;
					            
					            $status = STATUS_PENDING;
					            if($result_row['status'] == "N" || $result_row['status'] == "A"){
                                    if($result_row['res'] == "P"){
                                        $status = STATUS_PENDING;
                                    }else{
                                        $status = STATUS_COMPLETE;
                                    }
					            }else if($result_row['status'] == "D"){
					                $status = STATUS_PENDING;
					            }else{
					                $status = STATUS_CANCEL;
					            }
					            
					            
					            $PBdata = array(
							        'game_provider_code' => $provider_code,
							        'game_type_code' => GAME_SPORTSBOOK,
							        'game_provider_type_code' => $provider_code . '_' . GAME_SPORTSBOOK,
							        'game_result_type' => $result_type,
							        'game_code' => trim($result_row['sportstype']),
							        'game_real_code' => trim($result_row['sportstype']),
							        'bet_id' => trim($result_row['id']),
							        'bet_time' => strtotime(trim($result_row['trandate'])),
							        'game_time' => strtotime(trim($result_row['matchdate'])),
							        'report_time' => strtotime(trim($result_row['workdate'])),
							        'bet_amount' => trim($result_row['b']),
							        'bet_amount_valid' => trim($result_row['validamt']),
							        'payout_amount' => 0,
							        'promotion_amount' => 0,
							        'payout_time' => strtotime(trim($result_row['t'])),
							        'sattle_time' =>  strtotime(trim($result_row['t'])),
							        'compare_time' =>  strtotime(trim($result_row['t'])),
							        'created_date' => time(),
							        'win_loss' => trim($result_row['w']),
							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
							        'status' => $status,
							        'game_username' => $result_row['u'],
							        'player_id' => $member_lists[$exact_username],
							    );
							    
							    if($status == STATUS_COMPLETE){
									$PBdata['payout_amount'] = bcadd($PBdata['bet_amount'], $PBdata['win_loss'],2);
									if($PBdata['win_loss'] != 0){
										$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									}
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
								}else{
									$PBdata['bet_update_info'] = json_encode($result_row);
							        $PBdata['update_type'] = SYNC_DEFAULT;
									array_push($BUdata, $PBdata);
									array_push($BUIDdata, $PBdata['bet_id']);
								}
			                }
			            }
			            
		                if(sizeof($BdataID)>0){
    						$response_submit = $this->igk_connect($arr, $start_time, $end_time, "SubmitRecord",$BdataID);
    					}
    
    					$this->db->trans_start();
    					$this->db->insert('game_result_logs', $DBdata);
    					if(!empty($BUIDdata)){
                            $transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
                            if(!empty($transaction_lists_old)){
                                foreach($BUdata as $BUdataRow){
                                    if(isset($transaction_lists_old[$BUdataRow['bet_id']])){
                                        if($BUdataRow['payout_time'] > $transaction_lists_old[$BUdataRow['bet_id']]['payout_time']){
                                            if($transaction_lists_old[$BUdataRow['bet_id']]['status'] == STATUS_COMPLETE){
                								$PBdataWL = array(
                									'player_id' => $transaction_lists_old[$BUdataRow['bet_id']]['player_id'],
                									'game_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_code'],
                									'bet_time' => $transaction_lists_old[$BUdataRow['bet_id']]['bet_time'],
                									'payout_time' => $transaction_lists_old[$BUdataRow['bet_id']]['payout_time'],
                									'game_provider_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_provider_code'],
                									'game_type_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_type_code'],
                									'total_bet' => -1,
                									'bet_amount' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount'] * -1),
                									'bet_amount_valid' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount_valid'] * -1),
                									'win_loss' => ($transaction_lists_old[$BUdataRow['bet_id']]['win_loss'] * -1),
                									'win_result' => $transaction_lists_old[$BUdataRow['bet_id']]['win_result'],
                								);
                								array_push($BUDdata, $PBdataWL);
                							}
                							if($BUdataRow['status'] == STATUS_COMPLETE){
                								$PBdataWL = array(
                									'player_id' => $BUdataRow['player_id'],
                									'game_code' => $BUdataRow['game_code'],
                									'bet_time' => $BUdataRow['bet_time'],
                									'payout_time' => $BUdataRow['payout_time'],
                									'game_provider_code' => $BUdataRow['game_provider_code'],
                									'game_type_code' => $BUdataRow['game_type_code'],
                									'total_bet' => 1,
                									'bet_amount' => $BUdataRow['bet_amount'],
                									'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
                									'win_loss' => $BUdataRow['win_loss'],
												    'win_result' => $BUdataRow['win_result'],
                								);
                								array_push($BUDdata, $PBdataWL);
                							}
                							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
                                        }
                                    }else{
                                        if($BUdataRow['status'] == STATUS_COMPLETE){
                							$PBdataWL = array(
                								'player_id' => $BUdataRow['player_id'],
                								'game_code' => $BUdataRow['game_code'],
                								'bet_time' => $BUdataRow['bet_time'],
                								'payout_time' => $BUdataRow['payout_time'],
                								'game_provider_code' => $BUdataRow['game_provider_code'],
                								'game_type_code' => $BUdataRow['game_type_code'],
                								'total_bet' => 1,
                								'bet_amount' => $BUdataRow['bet_amount'],
                								'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
                								'win_loss' => $BUdataRow['win_loss'],
												'win_result' => $BUdataRow['win_result'],
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
                							'game_code' => $BUdataRow['game_code'],
                							'bet_time' => $BUdataRow['bet_time'],
                							'payout_time' => $BUdataRow['payout_time'],
                							'game_provider_code' => $BUdataRow['game_provider_code'],
                							'game_type_code' => $BUdataRow['game_type_code'],
                							'total_bet' => 1,
                							'bet_amount' => $BUdataRow['bet_amount'],
                							'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
                							'win_loss' => $BUdataRow['win_loss'],
                							'win_result' => $BUdataRow['win_result'],
                						);
                						array_push($BUDdata, $PBdataWL);
                					}
                					array_push($Bdata, $BUdataRow);
                			    }
                            }
                		}
    					$result_promotion_reset = array('promotion_amount' => 0);
    					if( ! empty($Bdata))
    					{
    						$this->db->insert_batch('transaction_report', $Bdata);
    					}
    					if( ! empty($BUDdata))
    					{
    						$this->db->insert_batch('win_loss_logs', $BUDdata);
    					}
    					$this->db->trans_complete();
			        }
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				if($is_allow == TRUE){
    				//insert finish
    				if(!empty($result_capture_array)){
                        $this->db->select('igk_content_type,igk_content_value');
                        $query = $this->db->get('igk_content');
                        if($query->num_rows() > 0)
                		{
                			foreach($query->result() as $row) {
                		        $content_capture_array[$row->igk_content_type][$row->igk_content_value] = 1;
                			}
                		}
                        
                        $insert_capture_array = array();
                        if(!empty($team_capture_array)){
                            $type = 1;
                            foreach($team_capture_array as $capture_key => $capture_value){
                                if(isset($content_capture_array[$type][$capture_key])){
                                    
                                }else{
                                    $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                                }
                            }
                        }
                        if(!empty($league_capture_array)){
                            $type = 2;
                            foreach($league_capture_array as $capture_key => $capture_value){
                                if(isset($content_capture_array[$type][$capture_key])){
                                    
                                }else{
                                    $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                                }
                            }
                        }
                        if(!empty($sporttype_capture_array)){
                            $type = 3;
                            foreach($sporttype_capture_array as $capture_key => $capture_value){
                                if(isset($content_capture_array[$type][$capture_key])){
                                    
                                }else{
                                    $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                                }
                            }
                        }
                        if(!empty($parlay_capture_array)){
                            $type = 4;
                            foreach($parlay_capture_array as $capture_key => $capture_value){
                                if(isset($content_capture_array[$type][$capture_key])){
                                    
                                }else{
                                    $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                                }
                            }
                        }
                        if(!empty($insert_capture_array)){
                            $this->db->insert_batch('igk_content',$insert_capture_array);
                        }
    				}
				}
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
	
    public function igk_scan_data(){
        $provider_code = "IGK";
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
        $game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $content_capture_array = array();
		    $team_capture_array = array();
            $league_capture_array = array();
		    
		    $this->db->select('igk_content_id,igk_content_type,igk_content_value,igk_content_data');
            $this->db->where('igk_content_data', null);
            $this->db->limit(100);
            $query = $this->db->get('igk_content');
            if($query->num_rows() > 0)
    		{
    			foreach($query->result() as $row){
    			    switch($row->igk_content_type){
    			        case 1 : $type = "TeamResult"; break;
    			        case 2 : $type = "LeagueResult"; break;
    			        case 3 : $type = "SportResult"; break;
    			        case 4 : $type = "ParlayResult"; break;
    			    }
    			    
    			    $response = $this->igk_connect($arr, 0, 0, $type,$row->igk_content_value);
                    if($response['code'] == '0')
                    {
    			        $result_array = json_decode($response['data'], TRUE);
    			        if( ! empty($result_array))
        				{
        			        if(isset($result_array['errcode']) && $result_array['errcode'] == '0')
        					{
        					    if($row->igk_content_type == "4"){
        					        if(isset($result_array['result']['ticket']) && !empty($result_array['result']['ticket'])){
        					            foreach($result_array['result']['ticket'] as $result_row){
        					                $team_capture_array[$result_row['home']] = 1;
            		                        $team_capture_array[$result_row['away']] = 1;
            		                        $league_capture_array[$result_row['league']] = 1;
        					            }
        					        }    
        					    }
        					    
        					    $update_data = array('igk_content_data'=> json_encode($result_array['result'],true));
        					    $this->db->where('igk_content_id',$row->igk_content_id);
        					    $this->db->limit(1);
        					    $this->db->update('igk_content',$update_data);
        					}
        				}
                    }
    			}
    			
    			if(!empty($team_capture_array) || !empty($league_capture_array)){
                    $this->db->select('igk_content_type,igk_content_value');
                    $query = $this->db->get('igk_content');
                    if($query->num_rows() > 0)
            		{
            			foreach($query->result() as $row) {
            		        $content_capture_array[$row->igk_content_type][$row->igk_content_value] = 1;
            			}
            		}
                    
                    $insert_capture_array = array();
                    if(!empty($team_capture_array)){
                        $type = 1;
                        foreach($team_capture_array as $capture_key => $capture_value){
                            if(isset($content_capture_array[$type][$capture_key])){
                                
                            }else{
                                $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                            }
                        }
                    }
                    if(!empty($league_capture_array)){
                        $type = 2;
                        foreach($league_capture_array as $capture_key => $capture_value){
                            if(isset($content_capture_array[$type][$capture_key])){
                                
                            }else{
                                $insert_capture_array[] = array('igk_content_type'=>$type,'igk_content_value'=>$capture_key);
                            }
                        }
                    }
                    if(!empty($insert_capture_array)){
                        $this->db->insert_batch('igk_content',$insert_capture_array);
                    }
				}
    		}
		}
    }
	
	private function igk_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL,$next_id = NULL){
		$url = $arr['APIUrl'];
		if($method == "RetrieveRecord"){
			$param_array = array(
                'action' => "fetch",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
            );
		}else if($method == "SubmitRecord"){
			$param_array = array(
                'action' => "mark_fetched",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'fetch_ids' => implode(',', $next_id),
            );
		}else if($method == "TeamResult"){
		    $param_array = array(
                'action' => "team",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'team_id' => $next_id,
            );
		}else if($method == "LeagueResult"){
		    $param_array = array(
                'action' => "league",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'league_id' => $next_id,
            );
		}else if($method == "SportResult"){
		    $param_array = array(
                'action' => "sportstype",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'sportstype_id' => $next_id,
            );
		}else if($method == "ParlayResult"){
		    $param_array = array(
                'action' => "parlay",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'ticket_id' => $next_id,
            );
		}else{
			$url = "";
		}
		$url .= "?" . http_build_query($param_array);
        $response = $this->curl_get_json($url);
		return $response;
	}
	
	public function rsg($member_lists = NULL, $result_type = NULL){
	    set_time_limit(0);
		$provider_code = 'RSG';
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				$currency_one = array("IDR", "VND");
                $currency_two = array("MYR2");
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-5 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
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

					$response = $this->rsg_connect($arr, $start_time, $end_time, $result_type);
					if($response['code'] == '0')
        		    {
        		        $result_string = openssl_decrypt(base64_decode($response['data']),'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        			    $result_array = json_decode($result_string, TRUE);
        			    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
        			        $DBdata['resp_data'] = json_encode($result_array);
							$DBdata['sync_status'] = STATUS_YES;
							if(isset($result_array['Data']['GameDetail']) && (sizeof($result_array['Data']['GameDetail']) > 0))
						    {
    							$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    							foreach($result_array['Data']['GameDetail'] as $result_row){
    							    $tmp_username = strtolower(trim($result_row['UserId']));
									$exact_username = $tmp_username;
    		
    							    if($result_type == GAME_SLOTS){
    							        $game_type_code = GAME_SLOTS;
    							    }else if($result_type == GAME_FISHING){
    							        $game_type_code = GAME_FISHING;
    							    }else{
    							        $game_type_code = GAME_SLOTS;
    							    }


    							    if(in_array($result_row['Currency'],$currency_one)){
    							        $bet_amount = bcdiv(trim($result_row['BetAmt']) * 1000, 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']) * 1000, 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']) * 1000, 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])) * 1000, 1, 2);
            					    }else if(in_array($result_row['Currency'],$currency_two)){
            					        $bet_amount = bcdiv(trim($result_row['BetAmt']) / 100, 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']) / 100, 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']) / 100, 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])) / 100, 1, 2);
            					    }else{
            					        $bet_amount = bcdiv(trim($result_row['BetAmt']), 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']), 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']), 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])), 1, 2);
            					    }

    						        $PBdata = array(
    							        'game_provider_code' => $provider_code,
    							        'game_type_code' => $game_type_code,
    							        'game_provider_type_code' => $provider_code."_".$game_type_code,
    							        'game_result_type' => $result_type,
    							        'game_code' => trim($result_row['GameId']),
    							        'game_real_code' => trim($result_row['GameId']),
    							        'bet_id' => trim($result_row['SequenNumber']),
    							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'bet_amount' => $bet_amount,
    							        'bet_amount_valid' => $bet_amount_valid,
    							        'payout_amount' => $payout_amount,
    							        'promotion_amount' => $bet_amount_valid,
    							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'win_loss' => $win_loss,
    							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    							        'status' => STATUS_COMPLETE,
    							        'game_username' => trim($result_row['UserId']),
    							        'player_id' => $member_lists[$exact_username],
    							    );


    							    if($PBdata['bet_amount'] == 0)
    								{
    									$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
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
        		    
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	private function rsg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $start_date = date('Y-m-d H:i', $start_time);
	    $end_date = date('Y-m-d H:i', $end_time);
	    $url = $arr['APIUrl'];
	    $url .= "/History/GetGameDetail";

	    if($type == GAME_SLOTS){
	        $game_type = 1;
	    }else if($type == GAME_FISHING){
	        $game_type = 2;
	    }else{
	        $game_type = 0;
	    }

		$timestamp = time();
        $param_array = array(
			"SystemCode" => $arr['SystemCode'],
			"WebId" => $arr['WebId'],
			"GameType" => $game_type,
			"TimeStart" => $start_date,
			"TimeEnd" => $end_date,
		);

		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","GameType":'.$param_array['GameType'].',"TimeStart":"'.$param_array['TimeStart'].'","TimeEnd":"'.$param_array['TimeEnd'].'"}';
		$encrypt_data = openssl_encrypt($str,'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        $msg = base64_encode($encrypt_data);
        $signature = md5($arr['ClientID'].$arr['Secret'].$timestamp.$msg);
        $header[]="X-API-ClientID: ".$arr['ClientID'];
        $header[]="X-API-Signature: ".$signature;
        $header[]="X-API-Timestamp: ".$timestamp;
		$param = 'Msg='.$msg;
		$response = $this->curl_post($url, $param,$header);
		$curl_array = $response['curl'];
		return $response;
	}
	
	public function jili($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'JILI';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-10 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
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
					$game_type = array(
    				    '1' => GAME_SLOTS,
                        '2' => GAME_BOARD_GAME,
                        '5' => GAME_FISHING,
                        '8' => GAME_BOARD_GAME,
    				);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->jili_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
									{
									    if(isset($result_array['Data'])){
    										$page_total = trim($result_array['Pagination']['TotalPages']);
    										$DBdata['sync_status'] = STATUS_YES;
    										if(isset($result_array['Data']['Result']) && sizeof($result_array['Data']['Result'])>0)
    										{
    											if($is_retrieve == FALSE){
    	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    	    										$is_retrieve = TRUE;
    	    									}
    											foreach($result_array['Data']['Result'] as $result_row){
    	                        					$tmp_username = trim($result_row['Account']);
                        							$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
                                                    if(trim($result_row['Type']) == "1"){
                                                        $game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
                                                    }else{
                                                        $game_round_type = GAME_ROUND_TYPE_GAME_ACTIVITY;
                                                    }
                                                    $bet_amount = bcdiv(trim($result_row['BetAmount']) * -1 * $arr['CurrencyRate'],1,2);
                									$bet_amount_valid = bcdiv(trim($result_row['Turnover']) * $arr['CurrencyRate'],1,2);
                                                    $payout_amount = bcdiv(trim($result_row['PayoffAmount']) * $arr['CurrencyRate'],1,2);
                									$win_loss = bcdiv((trim($result_row['PayoffAmount']) + trim($result_row['BetAmount'])) * $arr['CurrencyRate'],1,2);
                                                    if(trim($result_row['GameId']) == "61" || trim($result_row['GameId']) == "62" || trim($result_row['GameId']) == "63" || trim($result_row['GameId']) == "66"){
                                                        $game_type = GAME_BOARD_GAME;
                                                    }else{
                                                        $game_type = (isset($game_type[trim($result_row['GameCategoryId'])]) ? $game_type[trim($result_row['GameCategoryId'])] : GAME_SLOTS);   
                                                    }
                                                    $PBdata = array(
                								        'game_provider_code' => $provider_code,
                								        'game_type_code' => $game_type,
                								        'game_provider_type_code' => $provider_code . '_' . $game_type,
                								        'game_result_type' => $result_type,
                								        'game_code' => trim($result_row['GameId']),
                								        'game_real_code' => trim($result_row['GameId']),
                								        'bet_id' => trim($result_row['WagersId']),
                								        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['WagersTime']))),
                								        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['WagersTime']))),
                								        'report_time' => strtotime('+0 hours', strtotime(trim($result_row['SettlementTime']))),
                								        'bet_amount' => $bet_amount,
                								        'bet_amount_valid' => $bet_amount_valid,
                								        'payout_amount' => $payout_amount,
                								        'promotion_amount' => 0,
                								        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['PayoffTime']))),
                								        'sattle_time' =>  strtotime('+0 hours', strtotime(trim($result_row['SettlementTime']))),
                								        'compare_time' =>  strtotime('+0 hours', strtotime(trim($result_row['SettlementTime']))),
                								        'created_date' => time(),
                								        'win_loss' => $win_loss,
                								        'game_round_type' => $game_round_type,
                								        'status' => STATUS_COMPLETE,
                								        'game_username' => $result_row['Account'],
                								        'player_id' => $member_lists[$exact_username],
                								    );
                        						    if($PBdata['bet_amount'] == 0)
                        							{
                        								$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
                        							}
                        							if( ! in_array($PBdata['bet_id'], $transaction_lists))
                        							{					
                        								$PBdata['bet_info'] = json_encode($result_row);
                        						        $PBdata['insert_type'] = SYNC_DEFAULT;
                        								array_push($Bdata, $PBdata);
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
    	                        				}
    										}
    										$page_id++;
									    }
									}
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	public function ab($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'AB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-60 minutes' ,$start_time);
					$db_record_end_time = strtotime('+60 minutes' ,$end_time);
					
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
						'101' => "Baccarat",
						'102' => "Baccarat",
						'103' => "Baccarat",
						'104' => "Baccarat",
						'201' => "Sicbo",
						'301' => "Dragon Tiger",
						'401' => "Roulette",
						'501' => "Pok Deng",
						'601' => "Rock Paper Scissors",
						'801' => "Bull Bull",
						'901' => "Win Three Cards",
					);

					$is_loop = TRUE;
					while($is_loop == TRUE){
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata = array();

							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->ab_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['data']['total']) / trim($result_array['data']['pageSize']);
										if(isset($result_array['data']['list']) &&  sizeof($result_array['data']['list'])>0){
											foreach($result_array['data']['list'] as $result_row){
												$tmp_username = strtolower(substr(trim($result_row['player']), 0, strlen($arr['Suffix'])*-1));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_LIVE_CASINO,
											        'game_provider_type_code' => $provider_code . '_' . GAME_LIVE_CASINO,
											        'game_result_type' => $result_type,
											        'game_code' => (isset($game_code_data[trim($result_row['gameType'])]) ? $game_code_data[trim($result_row['gameType'])] : "Other"),
											        'game_real_code' => trim($result_row['gameType']),
											        'bet_id' => trim($result_row['betNum']),
											        'bet_time' => strtotime(trim($result_row['betTime'])),
											        'game_time' => strtotime(trim($result_row['gameRoundStartTime'])),
											        'report_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											        'bet_amount' => trim($result_row['betAmount']),
											        'bet_amount_valid' => trim($result_row['validAmount']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											        'sattle_time' =>  strtotime(trim($result_row['gameRoundEndTime'])),
											        'compare_time' =>  strtotime(trim($result_row['gameRoundEndTime'])),
											        'created_date' => time(),
											        'win_loss' => trim($result_row['winOrLossAmount']),
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'bet_code' => trim($result_row['betType']),
											        'game_result' => trim($result_row['gameResult']),
											        'table_id' => trim($result_row['tableName']),
											        'round' => trim($result_row['gameRoundId']),
											        'subround'  => "",
											        'status' => STATUS_CANCEL,
											        'game_username' => $result_row['player'],
											        'player_id' => $member_lists[$exact_username],
											    );

											    if($result_row['state'] == 0){
											    	$PBdata['status'] = STATUS_COMPLETE;
											    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
											    	//promotion
											    	if($PBdata['win_loss'] != 0){
											    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											    	}
											    }
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
												}else{
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
										}
									}
								}
							}

                            $current_page++;
						    sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function bbin($member_lists = NULL){
		//11:50
		
		set_time_limit(0);
		$provider_code = 'BBIN';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;

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
					$last_sync_time = $sync_data['end_time']+1;
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);

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
						'3001' => "Baccarat",
						'3003' => "Dragon Tiger",
						'3005' => "3 Face",
						'3006' => "Wenzhou Pai Gow",
						'3007' => "Roulette",
						'3008' => "Sicbo",
						'3011' => "Se Die",
						'3012' => "Bull Bull",
						'3015' => "Fan Tan",
						'3016' => "Fish Prawn Crab Dice",
						'3017' => "Baccarat",
						'3018' => "Three Card Poker",
						'3021' => "HiLo",
						'3025' => "Baccarat",
						'3026' => "Dragon Tiger",
						'3027' => "Baccarat",
						'3028' => "Roulette",
						'3029' => "Three Card Poker",
					);	

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata =  array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->bbin_connect($arr, $start_time, $end_time, $page_id,$result_type);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['result']) && $result_array['result'] == true && isset($result_array['pagination']))
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['pagination']['TotalPage']);
										if(!empty($result_array['data']) && sizeof($result_array['data'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}
											foreach($result_array['data'] as $result_row){
											    if(isset($arr['Suffix']) && !empty($arr['Suffix'])){
                                        	        $tmp_username = strtolower(substr(trim($result_row['UserName']), 0, strlen($arr['Suffix'])));
                                        	    }else{
                                        	        $tmp_username = strtolower(substr(trim($result_row['UserName']), 0, strlen($arr['Backfix'])*-1));
                                        	    }
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											    
											     $PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_LIVE_CASINO,
											        'game_provider_type_code' => $provider_code. "_".GAME_LIVE_CASINO,
											        'game_result_type' => $result_type,
											        'game_code' => (isset($game_code_data[trim($result_row['GameType'])]) ? $game_code_data[trim($result_row['GameType'])] : "Other"),
											        'game_real_code' => trim($result_row['GameType']),
											        'bet_id' => trim($result_row['WagersID']),
											        'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'game_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
									       			'report_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'bet_amount' => trim($result_row['BetAmount']),
											        'bet_amount_valid' => trim($result_row['BetAmount']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'sattle_time' =>  strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'compare_time' =>  strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'created_date' => time(),
											        'win_loss' => trim($result_row['Payoff']),
											        'table_id' => trim($result_row['GameCode']),
											        'round' => trim($result_row['SerialID']),
											        'subround'  => trim($result_row['RoundNo']),
											        'bet_code' => trim($result_row['WagerDetail']),
									        		'game_result' => json_encode(trim($result_row['Result'])),
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'status' => STATUS_CANCEL,
											        'game_username' => trim($result_row['UserName']),
											        'player_id' => $member_lists[$exact_username],
											    );

											     
											    
											    if($result_row['ResultType'] == " "){
											    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
											    	$PBdata['status'] = STATUS_COMPLETE;
											    	if($PBdata['win_loss'] != 0){
											    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											    	}
											    }else if($result_row['ResultType'] == "0"){
											    	$PBdata['status'] = STATUS_PENDING;
											    }
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
												}else{
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
										}
									}
									$page_id++;
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if(!empty($BUIDdata)){
								$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
								if( ! empty($transaction_lists_old)){
									foreach($transaction_lists_old as $transaction_lists_old_row){
										if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $transaction_lists_old_row['player_id'],
												'payout_time' => $transaction_lists_old_row['payout_time'],
												'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
												'game_type_code' => $transaction_lists_old_row['game_type_code'],
												'total_bet' => -1,
												'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
												'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
												'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							if( ! empty($BUdata))
							{
								foreach($BUdata as $BUdataRow){
									$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
								}
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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

	public function bbin_sl($member_lists = NULL, $sync_type = NULL){
		//11:50
		
		set_time_limit(0);
		$provider_code = 'BBIN';
		$result_type = GAME_SLOTS;
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
					$last_sync_time = $sync_data['end_time']+1;
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);

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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata =  array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->bbin_connect($arr, $start_time, $end_time, $page_id,$result_type,$sync_type);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['result']) && $result_array['result'] == true && isset($result_array['pagination']))
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['pagination']['TotalPage']);
										if(!empty($result_array['data']) && sizeof($result_array['data'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}
											foreach($result_array['data'] as $result_row){
												if(isset($arr['Suffix']) && !empty($arr['Suffix'])){
                                        	        $tmp_username = strtolower(substr(trim($result_row['UserName']), 0, strlen($arr['Suffix'])));
                                        	    }else{
                                        	        $tmp_username = strtolower(substr(trim($result_row['UserName']), 0, strlen($arr['Backfix'])*-1));
                                        	    }
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											    
											     $PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code. "_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['GameType']),
											        'game_real_code' => trim($result_row['GameType']),
											        'bet_id' => trim($result_row['WagersID']),
											        'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'game_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
									       			'report_time' => strtotime('+12 hours', strtotime(trim($result_row['ModifiedDate']))),
											        'bet_amount' => trim($result_row['BetAmount']),
											        'bet_amount_valid' => trim($result_row['BetAmount']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'sattle_time' =>  strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'compare_time' =>  strtotime('+12 hours', strtotime(trim($result_row['WagersDate']))),
											        'created_date' => time(),
											        'win_loss' => trim($result_row['Payoff']),
									        		'game_result' => trim($result_row['Result']),
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'status' => STATUS_CANCEL,
											        'game_username' => trim($result_row['UserName']),
											        'player_id' => $member_lists[$exact_username],
											    );

											    if($result_row['Result'] == "X"){
											    	$PBdata['status'] = STATUS_PENDING;
											    }else if($result_row['Result'] == "C"){
											    	$PBdata['status'] = STATUS_CANCEL;
											    }else{
											    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
											    	$PBdata['status'] = STATUS_COMPLETE;
											    	if($PBdata['win_loss'] != 0){
											    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											    	}
											    }
											    
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
												}else{
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
										}
									}
									$page_id++;
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if(!empty($BUIDdata)){
								$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
								if( ! empty($transaction_lists_old)){
									foreach($transaction_lists_old as $transaction_lists_old_row){
										if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $transaction_lists_old_row['player_id'],
												'payout_time' => $transaction_lists_old_row['payout_time'],
												'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
												'game_type_code' => $transaction_lists_old_row['game_type_code'],
												'total_bet' => -1,
												'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
												'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
												'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							if( ! empty($BUdata))
							{
								foreach($BUdata as $BUdataRow){
									$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
								}
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
					
					$is_loop = TRUE;
					while($is_loop == TRUE) {
					    $Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata =  array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['next_id'] = $next_id;
						$DBdata['resp_data'] = '';
						$response = $this->bng_connect($arr, $start_time, $end_time, $next_id);
						if($response['code'] == '0')
						{
						    if($response['http_code'] == '200'){
						        $DBdata['sync_status'] = STATUS_YES;
    						    $result_array = json_decode($response['data'], TRUE);
    						    if( ! empty($result_array))
    							{
    							    $next_id = $result_array['fetch_state'];
    							    
    							    if(isset($result_array['items']) && sizeof($result_array['items'])>0){
    							        if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
    							        foreach($result_array['items'] as $result_row){
    							            if($result_row['mode'] == "REAL"){
    							                if(empty($result_row['is_test'])){
    							                    if(trim($result_row['win']) < 0){
    												    $win_result = STATUS_LOSS;
    							                    }else if(trim($result_row['win']) > 0){
    							                        $win_result = STATUS_WIN;
    												}else{
    												    $win_result = STATUS_TIE;
    												}
    												
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
                										'win_result' => $win_result,
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
                									
                									if( ! in_array($PBdata['bet_id'], $transaction_lists))
    												{
    												    if($PBdata['status'] == STATUS_COMPLETE){
        													$PBdata['bet_info'] = json_encode($result_row);
        											        $PBdata['insert_type'] = SYNC_DEFAULT;
        													array_push($Bdata, $PBdata);
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
    							                }
    							            }
    							        }
    							    }
    							    
    							    if(empty($next_id)){
    							        $is_loop = FALSE;
    							    }
    							}else{
    							    $is_loop = FALSE;
    							}
						    }else{
						        $is_loop = FALSE;
						    }
						}else{
						    $is_loop = FALSE;
						}
						
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						sleep(5);
					}
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

	public function cmd($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'CMD';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUIDRFdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
						'S' => "Soccer",
						'34D' => "3D/4D",
						'12D' => "1D/2D",
						'BB' => "Basketball",
						'FS' => "Futsal",
						'BC' => "Beach Soccer",
						'UF' => "US Football",
						'BE' => "Baseball",
						'IH' => "Ice Hockey",
						'TN' => "Tennis",
						'FB' => "Financial Bets",
						'BA' => "Badminton",
						'GF' => "Golf",
						'CK' => "Cricket",
						'VB' => "Volleyball",
						'HB' => "Handball",
						'PL' => "Pool",
						'BL' => "Billiard",
						'NS' => "Snooker",
						'RB' => "Rugby",
						'GP' => "MotoGP",
						'DT' => "Darts",
						'BX' => "Boxing",
						'AT' => "Athletics",
						'AR' => "Archery",
						'CH' => "Chess",
						'DV' => "Diving",
						'AT' => "Athletics",
						'EQ' => "Equestrian",
						'ET' => "Entertainment",
						'CN' => "Canoeing",
						'CS' => "Canoeing",
						'CY' => "Cycling",
						'HK' => "Hockey",
						'GM' => "Gymnastics",
						'FL' => "Floor Ball",
						'NT' => "Novelties",
						'OL' => "Olympic",
						'OT' => "Other",
						'PO' => "Politics",
						'QQ' => "Squash",
						'MN' => "Swimming",
						'RU' => "Rugby Union",
						'TT' => "Table Tennis",
						'WG' => "Weightlifting",
						'WI' => "Winter Sports",
						'WP' => "Water Polo",
						'WS' => "Speedway",
						'ES' => "E-Sports",
						'MT' => "Muay Thai",
					);
					$response = $this->cmd_connect($arr, $start_time, $end_time, $next_id);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['Code']) && ($result_array['Code'] == '0'))
							{
								$DBdata['sync_status'] = STATUS_YES;
	    						if(sizeof($result_array['Data'])>0){
	    							$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
	    							foreach($result_array['Data'] as $result_row){
	    								if($result_row['DangerStatus'] == "C" || $result_row['DangerStatus'] == "R"){
    										$status = STATUS_CANCEL;
    									}else{
    										if($result_row['WinLoseStatus'] == "P"){
    											$status = STATUS_PENDING;
    										}else{
    											$status = STATUS_COMPLETE;
    										}
    									}
    									$tmp_username = strtolower(trim($result_row['SourceName']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if($arr['CurrencyType'] == "VD" || $arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "JPY"){
											$bet_amount = trim($result_row['BetAmount']) * 1000;
											$bet_amount_valid = trim($result_row['BetAmount']) * 1000;
											$win_loss = trim($result_row['WinAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['BetAmount']);
											$win_loss = trim($result_row['WinAmount']);
										}

										$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SPORTSBOOK,
    										'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
    										'game_result_type' => $result_type,
    										'game_code' => (isset($game_code_data[trim($result_row['SportType'])]) ? $game_code_data[trim($result_row['SportType'])] : "Other"),
    										'game_real_code' => trim($result_row['SportType']),
    										'bet_id' => trim($result_row['ReferenceNo']),
    										'bet_ref_no' => trim($result_row['Id']),
    										'bet_transaction_id' => trim($result_row['SocTransId']),
    										'bet_time' => (int)(((trim($result_row['TransDate']) - 621355968000000000)/10000000)-28800),
    										'game_time' => (int)(((trim($result_row['MatchDate']) - 621355968000000000)/10000000)-28800),
    										'report_time' => (int)(((trim($result_row['MatchDate']) - 621355968000000000)/10000000)-28800),
    										'bet_amount' => $bet_amount,
    										'bet_amount_valid' => $bet_amount_valid,
    										'bet_code' => trim($result_row['BetSource']),
    										'payout_time' => (int)(((trim($result_row['StateUpdateTs']) - 621355968000000000)/10000000)-28800),
    										'sattle_time' => (int)(((trim($result_row['StateUpdateTs']) - 621355968000000000)/10000000)-28800),
    										'compare_time' => (int)(((trim($result_row['StateUpdateTs']) - 621355968000000000)/10000000)-28800),
    										'created_date' => time(),
    										'win_loss' => $win_loss,
    										'payout_amount' => 0,
    										'promotion_amount' => 0,
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'odds_currency' => trim($result_row['OddsType']),
    										'odds_rate' => trim($result_row['Odds']),
    										'status' => $status,
    										'game_username' => trim($result_row['SourceName']),
    										'table_id' => trim($result_row['MatchGroupID']),
    										'player_id' =>  $member_lists[$exact_username],
    									);
    									if($status == STATUS_COMPLETE){
    										$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
    										if($PBdata['win_loss'] != 0){
    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
    									}else{
											$PBdata['payout_amount'] = 0;
										}
										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
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
										}else{
											$PBdata['bet_update_info'] = json_encode($result_row);
									        $PBdata['update_type'] = SYNC_DEFAULT;
											array_push($BUdata, $PBdata);
											array_push($BUIDdata, $PBdata['bet_id']);
											$BUIDRFdata[$PBdata['bet_id']] = $PBdata;
										}
										$DBdata['next_id'] = $result_row['Id'];
	    							}
	    						}
							}
						}
					}


					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									if(isset($BUIDRFdata[$transaction_lists_old_row['bet_id']]) && ($BUIDRFdata[$transaction_lists_old_row['bet_id']]['bet_ref_no'] > $transaction_lists_old_row['bet_ref_no'])){
										$PBdataWL = array(
											'player_id' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['player_id'],
											'payout_time' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['payout_time'],
											'game_provider_code' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['game_provider_code'],
											'game_type_code' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['bet_amount'],
											'bet_amount_valid' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['bet_amount_valid'],
											'win_loss' => $BUIDRFdata[$transaction_lists_old_row['bet_id']]['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);

										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_CMD,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function cq9($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'CQ9';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;

				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'slot' => GAME_SLOTS,
						'fish' => GAME_FISHING,
						'arcade' => GAME_OTHERS,
						'table' => GAME_BOARD_GAME,
					);

					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->cq9_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
							{

								$DBdata['resp_data'] = json_encode($result_array);
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']['Data']) && (sizeof($result_array['data']['Data']) > 0))
								{
									if($is_retrieve == FALSE){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										$is_retrieve = TRUE;
									}

									foreach($result_array['data']['Data'] as $result_row){
										$tmp_username = strtolower(trim($result_row['account']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);


										if($result_row['status'] == "complete"){
											$status = STATUS_COMPLETE;
										}else{
											$status = STATUS_PENDING;
										}

										$PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_SLOTS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_SLOTS),
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['gametype']),
									        'game_real_code' => trim($result_row['gamecode']),
									        'bet_id' => trim($result_row['round']),
									        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bettime']))),
									        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['createtime']))),
							       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['endroundtime']))),
									        'bet_amount' => trim($result_row['bet']),
									        'bet_amount_valid' => trim($result_row['bet']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['endroundtime']))),
									        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row['endroundtime']))),
                							'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['endroundtime']))),
                							'created_date' => time(),
									        'win_loss' => trim($result_row['win']) - trim($result_row['bet']),
									        'table_id' => trim($result_row['tableid']),
									        'round' => trim($result_row['roundnumber']),
									        'bet_code' => (is_array($result_row['bettype']) ? json_encode($result_row['bettype']) : trim($result_row['bettype'])),
							        		'game_result' => json_encode($result_row['gameresult']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => $status,
									        'game_username' => trim($result_row['account']),
									        'player_id' => $member_lists[$exact_username],
									    );

									    if($PBdata['win_loss'] != 0){
									    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
									    	$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    }
									    
									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
								$page_id++;
							}else{
								if(isset($result_array['status']['code']) && $result_array['status']['code'] == '8'){
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
								}
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}

						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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

	public function dg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DG';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-5 days' ,$start_time);
					$db_record_end_time = strtotime('+5 days' ,$end_time);
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
						'1' => "Baccarat",
						'2' => "Baccarat",
						'3' => "Dragon Tiger",
						'4' => "Roulette",
						'5' => "Sicbo",
						'6' => "Fan Tan",
						'7' => "Bull Bull",
						'8' => "Baccarat",
						'9' => "Poker",
						'10' => "Baccarat",
						'11' => "Three Card Poker",
						'12' => "Sicbo",
						'14' => "Se Die",
						'15' => "Fish Prawn Crab Dice",
						'51' => "Live Lucky 5",
						'52' => "Live Lucky 10",
					);

					$game_type_code_data = array(
						'1' => GAME_LIVE_CASINO,
						'2' => GAME_OTHERS,
					);

					$response = $this->dg_connect($arr, $start_time, $end_time, "RetrieveRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['codeId']) && $result_array['codeId'] == "0"){
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['list'])){
									if(sizeof($result_array['list'])){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										foreach($result_array['list']  as $result_row){
											$tmp_username = strtolower(trim($result_row['userName']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											if($result_row['isRevocation'] == "1"){
												$status = STATUS_COMPLETE;
											}else if($result_row['isRevocation'] == "2"){
												$status = STATUS_CANCEL;
											}else{
												$status = STATUS_PENDING;
											}
											
                                            if(!empty($result_row['tableId'])){
                                                $game_type_code = (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS);
    											if($game_type_code == GAME_LIVE_CASINO){
    												if($game_code_data[trim($result_row['gameId'])]){
    													$game_code = $game_code_data[trim($result_row['gameId'])];
    												}else{
    													$game_code = "Other";
    												}
    												$game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
    											}else{
    												$game_code = trim($result_row['gameId']);
    												$game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
    											}   
                                            }else{
                                                $game_code = trim($result_row['gameId']);
    											$game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
                                            }
											


											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => GAME_LIVE_CASINO,
										        'game_provider_type_code' => $provider_code . "_" . GAME_LIVE_CASINO,
										        'game_result_type' => $result_type,
										        'game_code' => $game_code,
										        'game_real_code' => trim($result_row['gameId']),
										        'bet_id' => trim($result_row['id']),
										        'bet_time' => strtotime(trim($result_row['betTime'])),
										        'game_time' => strtotime(trim($result_row['calTime'])),
										        'report_time' => strtotime(trim($result_row['calTime'])),
										        'bet_amount' => trim($result_row['betPoints']),
												'bet_amount_valid' => ((isset($result_row['availableBet'])) ? trim($result_row['availableBet']) : '0'),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'payout_time' => strtotime(trim($result_row['calTime'])),
										        'sattle_time' => strtotime(trim($result_row['calTime'])),
												'compare_time' => strtotime(trim($result_row['calTime'])),
												'created_date' => time(),
										        'win_loss' =>  ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss'])-trim($result_row['betPoints']) : '0'),
										        'game_round_type' => $game_round_type,
										        'bet_code' => trim($result_row['betDetail']),
										        'game_result' => trim($result_row['result']),
										        'table_id' => trim($result_row['tableId']),
										        'round' => trim($result_row['shoeId']),
										        'subround'  => trim($result_row['playId']),
										        'status' => $status,
										        'game_username' => $result_row['userName'],
										        'player_id' => $member_lists[$exact_username],
										    );

											if($status == STATUS_COMPLETE){
												$PBdata['payout_amount'] = ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss']) : '0');
												if($PBdata['win_loss'] != 0){
													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
												}
											}

											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
												if($PBdata['status'] != STATUS_PENDING){
													array_push($BdataID, $PBdata['bet_id']);
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
											}else{
												array_push($BdataID, $PBdata['bet_id']);
											}
										}
									}
								}
							}
						}
					}
					if(sizeof($BdataID)>0){
						$response_submit = $this->dg_connect($arr, $start_time, $end_time, "SubmitRecord",$BdataID);
					}

					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();

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

	public function dt($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DT';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->dt_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if(sizeof($result_array['BETSDETAILS'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										foreach($result_array['BETSDETAILS'] as $result_row){
											$tmp_username = strtolower(trim($result_row['playerName']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
										    
											if(trim($result_row['rewardType']) == ""){
												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameCode']),
											        'game_real_code' => trim($result_row['gameCode']),
											        'bet_id' => trim($result_row['id']),
											        'bet_ref_no' => (isset($result_row['fcid']) ? trim($result_row['fcid']) : ""),
											        'bet_transaction_id' => (isset($result_row['partentId']) ? trim($result_row['partentId']) : ""),
											        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
									       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'bet_amount' => trim($result_row['betPrice']),
											        'bet_amount_valid' => trim($result_row['betPrice']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'win_loss' => trim($result_row['prizeWins']),
											        'table_id' => "",
											        'round' => "",
											        'subround'  => "",
											        'bet_code' => "",
									        		'game_result' => "",
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'status' => STATUS_COMPLETE,
											        'game_username' => trim($result_row['playerName']),
											        'player_id' => $member_lists[$exact_username],
											    );

											    if($PBdata['win_loss'] != 0){
											    	$PBdata['payout_amount'] = trim($result_row['betPrice']) + trim($result_row['prizeWins']);
											    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
											    }
											}else{
												//Bonus
												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameCode']),
											        'game_real_code' => trim($result_row['gameCode']),
											        'bet_id' => trim($result_row['id']),
											        'bet_ref_no' => (isset($result_row['fcid']) ? trim($result_row['fcid']) : ""),
											        'bet_transaction_id' => (isset($result_row['partentId']) ? trim($result_row['partentId']) : ""),
											        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
									       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'bet_amount' => 0,
											        'bet_amount_valid' => 0,
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'win_loss' => trim($result_row['betWins']),
											        'table_id' => "",
											        'round' => "",
											        'subround'  => "",
											        'bet_code' => "",
									        		'game_result' => "",
											        'game_round_type' => GAME_ROUND_TYPE_FREE_SPIN,
											        'status' => STATUS_COMPLETE,
											        'game_username' => trim($result_row['playerName']),
											        'player_id' => $member_lists[$exact_username],
											    );
											}

										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}

						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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
    
    public function ea($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'EA';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					
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

					
					$response = $this->ea_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
						$xml_output = simplexml_load_string($xml_utf8);
						$json = json_encode($xml_output);
						$result_array = json_decode($json, TRUE);
						if(isset($result_array['element']['status']) && $result_array['element']['status'] == 'Success')
    					{
    					    $DBdata['sync_status'] = STATUS_YES;
							$DBdata['resp_data'] = json_encode($result_array);
    					    if(sizeof($result_array['element']['result'])>0){
    					        $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
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
        										if($exact_username == "deveaplayer1" || $exact_username == "deveaplayer2" || $exact_username == "deveaplayer3" || $exact_username == "deveaplayer4" || $exact_username == "deveaplayer5"){
    	    										    
	    										}else{
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
                									
                									if( ! in_array($PBdata['bet_id'], $transaction_lists))
    												{					
    													$PBdata['bet_info'] = json_encode($result_row);
    											        $PBdata['insert_type'] = SYNC_DEFAULT;
    													array_push($Bdata, $PBdata);
    												}else{
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
    						                }
    						            }
    						        }
    						    }
    					    }
    					}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);							
						}
					}
					$this->db->trans_complete();
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
	
	public function eb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'EB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
                    
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
						'1' => "Baccarat",
						'2' => "Dragon Tiger",
						'3' => "Sicbo",
						'4' => "Roulette",
						'5' => "Slot",
						'7' => "Baccarat",
						'8' => "Bull Bull",
						'23' => "Fortune Wheel",
					);

					$game_type_code_data = array(
						'1' => GAME_LIVE_CASINO,
						'2' => GAME_LIVE_CASINO,
						'3' => GAME_LIVE_CASINO,
						'4' => GAME_LIVE_CASINO,
						'5' => GAME_SLOTS,
						'7' => GAME_LIVE_CASINO,
						'8' => GAME_LIVE_CASINO,
						'23' => GAME_SLOTS,
					);

					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->eb_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['status']) && $result_array['status'] == 200)
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if(sizeof($result_array['betHistories'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										foreach($result_array['betHistories'] as $result_row){
											$tmp_username = strtolower(trim($result_row['username']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
										        'game_result_type' => $result_type,
										        'game_code' => (isset($game_code_data[trim($result_row['gameType'])]) ? $game_code_data[trim($result_row['gameType'])] : "Other"),
										        'game_real_code' => trim($result_row['gameType']),
										        'bet_id' => trim($result_row['betHistoryId']),
										        'bet_time' => trim($result_row['createTime']),
										        'game_time' => trim($result_row['payoutTime']),
								       			'report_time' => trim($result_row['payoutTime']),
										        'bet_amount' => trim($result_row['bet']),
										        'bet_amount_valid' => trim($result_row['validBet']),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'payout_time' => trim($result_row['payoutTime']),
										        'sattle_time' => trim($result_row['payoutTime']),
												'compare_time' => trim($result_row['payoutTime']),
												'created_date' => time(),
										        'win_loss' => trim($result_row['payout']) - trim($result_row['bet']),
										        'table_id' => "",
										        'round' => trim($result_row['roundNo']),
										        'subround'  => "",
										        'bet_code' => json_encode($result_row['betMap'],true),
								        		'game_result' => json_encode($result_row['payoutDetail'],true),
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => STATUS_COMPLETE,
										        'game_username' => trim($result_row['username']),
										        'player_id' => $member_lists[$exact_username],
										    );
										    
										    $PBdata['game_provider_type_code'] = $provider_code."_".$PBdata['game_type_code'];

											if($PBdata['win_loss'] != 0){
										    	$PBdata['payout_amount'] = trim($result_row['payout']);
										    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
										    }

										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}

						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}

						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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

	public function evo($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'EVO';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
						'baccarat' => "Baccarat",
						'topcard' => "Top Card",
						'dragontiger' => "Dragon Tiger",
						'blackjack' => "Blackjack",
						'scalableblackjack' => "Blackjack",
						'roulette' => "Roulette",
						'americanroulette' => "Roulette",
						'moneywheel' => "Money Wheel",
						'sicbo' => "Sicbo",
					);
					$response = $this->evo_connect($arr, $start_time, $end_time);
					//Response time (UTC +0)
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['uuid']))
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']) &&  sizeof($result_array['data'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['data'] as $date_result_row){
										if(isset($date_result_row['games']) && sizeof($date_result_row['games'])>0){
											foreach($date_result_row['games'] as $games_result_row){
												if(isset($games_result_row['participants']) && sizeof($games_result_row['participants'])>0){
													foreach($games_result_row['participants'] as $player_result_row){
														if(isset($player_result_row['bets']) && sizeof($player_result_row['bets'])>0){
															foreach($player_result_row['bets'] as $result_row){
																$tmp_username = strtolower(trim($player_result_row['playerId']));
																$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
																
																$placedOn = (substr(trim($result_row['placedOn']), 0, 19));
																$settledAt = (substr(trim($games_result_row['settledAt']), 0, 19));
																
															    $PBdata = array(
															        'game_provider_code' => $provider_code,
															        'game_type_code' => GAME_LIVE_CASINO,
															        'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
															        'game_result_type' => $result_type,
															        'game_code' => (isset($game_code_data[trim($games_result_row['gameType'])]) ? $game_code_data[trim($games_result_row['gameType'])] : "Other"),
															        'game_real_code' => trim($games_result_row['gameType']),
															        'bet_id' => trim($result_row['code']).trim($result_row['transactionId']),
															        'bet_time' => strtotime('+8 hours',strtotime($placedOn)),
															        'game_time' => strtotime('+8 hours',strtotime($settledAt)),
															        'report_time' => strtotime('+8 hours',strtotime($settledAt)),
															        'bet_amount' => trim($result_row['stake']),
															        'bet_amount_valid' => trim($result_row['stake']),
															        'payout_time' => strtotime('+8 hours',strtotime($settledAt)),
															        'sattle_time' => strtotime('+8 hours',strtotime($settledAt)),
																	'compare_time' => strtotime('+8 hours',strtotime($settledAt)),
																	'created_date' => time(),
															        'payout_amount' => 0,
	    									                    	'promotion_amount' => 0,
															        'win_loss' => trim($result_row['payout'])-trim($result_row['stake']),
															        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
															        'bet_code' => trim($result_row['description']),
															        'game_result' => json_encode($games_result_row['result']),
															        'table_id' => trim($games_result_row['table']['id']),
															        'round' => trim($games_result_row['id']),
															        'subround'  => trim($result_row['transactionId']),
															        'status' => STATUS_CANCEL,
															        'game_username' => $player_result_row['playerId'],
															        'player_id' => $member_lists[$exact_username],
															    );
															    if(trim($games_result_row['status']) == "Resolved"){
                        									    	$PBdata['status'] = STATUS_COMPLETE;
                        									    	$PBdata['payout_amount'] = trim($result_row['payout']);
                        									    	//promotion
                        									    	if($PBdata['win_loss'] != 0){
                        									    		$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
                        									    	}
                        									    }
																if( ! in_array($PBdata['bet_id'], $transaction_lists))
																{	
																	$PBdata['bet_info'] = json_encode($result_row);
															        $PBdata['insert_type'] = SYNC_DEFAULT;
																	array_push($Bdata, $PBdata);
																}else{
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
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					
					$this->db->trans_complete();
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

	public function evoplay($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'EVOP';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
                    
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

					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->evoplay_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(!isset($result_array['error']))
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if($result_array['last_page'] == $page_id){
										$is_loop = FALSE;
									}
									
									if($result_array['last_page'] == "0"){
									    $is_loop = FALSE;
									}

									if(sizeof($result_array['page_result']) > 0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										foreach($result_array['page_result'] as $result_row){
											$tmp_username = strtolower(trim($result_row['user_name']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											if($result_row['status'] == "1"){
												$status = STATUS_COMPLETE;
											}else if($result_row['status'] == "0"){
												$status = STATUS_PENDING;
											}else{
												$status = STATUS_CANCEL;
											}

											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => GAME_SLOTS,
										        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
										        'game_result_type' => $result_type,
										        'game_code' => trim($result_row['game_id']),
										        'game_real_code' => trim($result_row['game_id']),
										        'bet_id' => trim($result_row['round_id']),
										        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'game_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'report_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'bet_amount' => trim($result_row['bet_amount']),
										        'bet_amount_valid' => trim($result_row['bet_amount']),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										      	'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
										        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'created_date' => time(),
										        'win_loss' => trim($result_row['win_amount']) - trim($result_row['bet_amount']),
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => $status,
										        'game_username' => trim($result_row['user_name']),
										        'player_id' => $member_lists[$exact_username],
										    );

											if($PBdata['win_loss'] != 0){
										    	$PBdata['payout_amount'] = trim($result_row['payout']);
										    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
										    }

										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}

						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}

						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}

						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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

	public function hb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'HB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$response = $this->hb_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$DBdata['sync_status'] = STATUS_YES;
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							
							for($i=0;$i<sizeof($result_array);$i++)
							{
								if($is_retrieve == FALSE){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									$is_retrieve = TRUE;
								}
								$tmp_username = strtolower(trim($result_array[$i]['Username']));
								$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
												
								//Response time (UTC +0)

								$PBdata = array(
									'game_provider_code' => $provider_code,
									'game_type_code' => ((trim($result_array[$i]['GameTypeId']) == 11) ? GAME_SLOTS : GAME_OTHERS),
									'game_result_type' => $result_type,
									'game_code' => trim($result_array[$i]['GameKeyName']),
									'game_real_code' => trim($result_array[$i]['GameTypeId']),
									'bet_id' => trim($result_array[$i]['FriendlyGameInstanceId']),
									'bet_ref_no' => trim($result_array[$i]['GameInstanceId ']),
									'bet_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtStarted']))),
									'game_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtCompleted']))),
									'report_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtCompleted']))),
									'bet_amount' => trim($result_array[$i]['Stake']),
									'bet_amount_valid' => ((trim($result_array[$i]['GameStateId']) == 3) ? trim($result_array[$i]['Stake']) : 0),
									'payout_amount' => 0,
									'promotion_amount' => 0,
									'payout_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtCompleted']))),
									'sattle_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtCompleted']))),
									'compare_time' => strtotime('+8 hours', strtotime(trim($result_array[$i]['DtCompleted']))),
									'created_date' => time(),
									'win_loss' => (trim($result_array[$i]['Payout']) - trim($result_array[$i]['Stake'])),
									'jackpot_win' => trim($result_array[$i]['JackpotWin']),
									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									'status' => ((trim($result_array[$i]['GameStateId']) == 3) ? STATUS_COMPLETE : STATUS_CANCEL),
									'game_username' => trim($result_array[$i]['Username']),
									'player_id' => $member_lists[$exact_username],
								);
								$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];
								
								if($PBdata['jackpot_win'] > 0)
								{
									$PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
								}
								else if($PBdata['bet_amount'] == 0)
								{
									$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
								}

								if($PBdata['win_loss'] != 0){
							    	$PBdata['payout_amount'] = trim($result_array[$i]['Payout']);
							    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
							    }
								
								if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{					
									$PBdata['bet_info'] = json_encode($result_array[$i]);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
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
							}
						}
					}

					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();

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

	public function ibc($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'IBC';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$Mdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$CashOutdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
						'1' => "Soccer",
						'2' => "Basketball",
						'3' => "Football",
						'4' => "Ice Hockey",
						'5' => "Tennis",
						'6' => "Volleyball",
						'7' => "Snooker/Pool",
						'8' => "Baseball",
						'9' => "Badminton",
						'10' => "Golf",
						'11' => "Motorsports",
						'12' => "Swimming",
						'13' => "Politics",
						'14' => "Water Polo",
						'15' => "Diving",
						'16' => "Boxing/MMA",
						'17' => "Archery",
						'18' => "Table Tennis",
						'19' => "Weightlifting",
						'20' => "Canoeing",
						'21' => "Gymnastics",
						'22' => "Athletics",
						'23' => "Equestrian",
						'24' => "Handball",
						'25' => "Darts",
						'26' => "Rugby",
						'27' => "Field Hockey",
						'28' => "Winter Sports",
						'29' => "Squash",
						'30' => "Entertainment",
						'31' => "Netball",
						'32' => "Canoeing",
						'33' => "Cycling",
						'34' => "Fencing",
						'35' => "Judo",
						'36' => "M. Pentathlon",
						'37' => "Rowing",
						'38' => "Sailing",
						'39' => "Shooting",
						'40' => "Taekwondo",
						'41' => "Triathlon",
						'42' => "Wrestling",
						'43' => "E-Sports",
						'44' => "Muay Thai",
						'45' => "Beach Volleybal",
						'47' => "Kabaddi",
						'48' => "Sepak Takraw",
						'49' => "Futsal",
						'50' => "Cricket",
						'51' => "Beach Soccer",
						'52' => "Poker",
						'53' => "Chess",
						'54' => "Olympics",
						'55' => "Finance",
						'56' => "Lotto",
						'99' => "Other",
					);
					$odds_type = array(
						'1' => "MY",
						'2' => "HK",
						'3' => "DE",
						'4' => "ID",
						'5' => "USD",
					);
					$response = $this->ibc_connect($arr, $start_time, $end_time,$next_id);
					//Response time (GMT -4)
					if($response['code'] == '0'){
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							if(isset($result_array['error_code']) && ($result_array['error_code'] == '0'))
							{
								$DBdata['resp_data'] = json_encode($result_array);
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['Data']) && isset($result_array['Data']['last_version_key']) && $result_array['Data']['last_version_key']>$next_id){
									$DBdata['next_id'] = $result_array['Data']['last_version_key'];
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
									if(isset($result_array['Data']['BetDetails']) && sizeof($result_array['Data']['BetDetails'])>0){
										foreach ($result_array['Data']['BetDetails'] as $result_row){
										    $CashOutdata = array();
											if($result_row['ticket_status'] == "void" || $result_row['ticket_status'] == "refund" || $result_row['ticket_status'] == "reject"){
		    									$status = STATUS_CANCEL;
		    								}else{
		    									if($result_row['ticket_status'] == "running" || $result_row['ticket_status'] == "waiting"){
	    											$status = STATUS_PENDING;
	    										}else{
	    											$status = STATUS_COMPLETE;
	    										}
		    								}
											$tmp_username = strtolower(str_replace($arr['OperatorID'].'_','',trim($result_row['vendor_member_id'])));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
                                            
                                            if($result_row['sport_type']>=1 && $result_row['sport_type']<=99){
                                                $bet_time = strtotime('+12 hours', strtotime(trim($result_row['transaction_time'])));
                                                $payout_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $game_time = strtotime('+12 hours', strtotime(trim($result_row['match_datetime'])));
                                                $report_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $sattle_time = strtotime('+12 hours', strtotime(trim($result_row['settlement_time'])));
                                                $compare_time = strtotime('+12 hours', strtotime(trim($result_row['settlement_time'])));
                                            }else{
                                                $bet_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $payout_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $game_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $report_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                                $sattle_time = strtotime('+12 hours', strtotime(trim($result_row['settlement_time'])));
                                                $compare_time = strtotime('+12 hours', strtotime(trim($result_row['settlement_time'])));
                                            }

                                            if($arr['CurrencyId'] == "15" || $arr['CurrencyId'] == "51" || $arr['CurrencyId'] == "70" || $arr['CurrencyId'] == "71"){
                                            	$bet_amount = trim($result_row['stake']) * 1000;
												$bet_amount_valid = abs(trim($result_row['winlost_amount'])) * 1000;
												$win_loss = trim($result_row['winlost_amount']) * 1000;
                                            }else{
                                            	$bet_amount = trim($result_row['stake']);
												$bet_amount_valid = abs(trim($result_row['winlost_amount']));
												$win_loss = trim($result_row['winlost_amount']);
                                            }
                                            
                                            
											$PBdata = array(
	    										'game_provider_code' => $provider_code,
	    										'game_type_code' => GAME_SPORTSBOOK,
	    										'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
	    										'game_result_type' => $result_type,
	    										'game_code' => (isset($game_code_data[trim($result_row['sport_type'])]) ? $game_code_data[trim($result_row['sport_type'])] : "Other"),
	    										'game_real_code' => trim($result_row['sport_type']),
	    										'bet_id' => trim($result_row['trans_id']),
	    										'bet_transaction_id' => trim($result_row['parlay_ref_no']),
	    										'bet_ref_no' => '',
	    										'bet_match_id' => (isset($result_row['match_id']) ? trim($result_row['match_id']) : "0"),
	    										'bet_time' => $bet_time,
	    										'bet_amount' => $bet_amount,
	    										'bet_amount_valid' => $bet_amount_valid,
	    										'payout_time' => $payout_time,
	    										'win_loss' => $win_loss,
	    										'game_time' => $game_time,
	    										'report_time' => $report_time,
	    										'sattle_time' => $sattle_time,
	    										'compare_time' => $compare_time,
	    										'created_date' => time(),
	    										'payout_amount' => 0,
	    										'promotion_amount' => 0,
	    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	    										'odds_currency' => (isset($odds_type[trim($result_row['odds_type'])]) ? $odds_type[trim($result_row['odds_type'])] : "OT"),
	    										'odds_rate' => trim($result_row['odds']),
	    										'bet_code' => (isset($result_row['bet_type']) ? trim($result_row['bet_type']) : "0"),
	    										'status' => $status,
	    										'game_username' => trim($result_row['vendor_member_id']),
	    										'table_id' => trim($result_row['match_id']),
	    										'player_id' =>  $member_lists[$exact_username],
	    									);
	    									if($status == STATUS_COMPLETE){
	    										$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
	    										if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
	    									}else{
												$PBdata['payout_amount'] = 0;
											}
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
											
											//Cashout
                							if(isset($result_row['CashOutData']) && !empty($result_row['CashOutData'])){
                							    foreach($result_row['CashOutData'] as $cashout_result_row){

                							    	 if($arr['CurrencyId'] == "15" || $arr['CurrencyId'] == "51" || $arr['CurrencyId'] == "70" || $arr['CurrencyId'] == "71"){
		                                            	$bet_amount = trim($result_row['stake']) * 1000;
														$bet_amount_valid = trim($result_row['real_stake']) * 1000;
														$win_loss = (trim($cashout_result_row['buyback_amount']) - trim($cashout_result_row['real_stake'])) * 1000;
														$payout_amount = trim($cashout_result_row['buyback_amount']) * 1000;
		                                            }else{
		                                            	$bet_amount = trim($result_row['stake']);
														$bet_amount_valid = trim($result_row['real_stake']);
														$win_loss = (trim($cashout_result_row['buyback_amount']) - trim($cashout_result_row['real_stake']));
														$payout_amount = trim($cashout_result_row['buyback_amount']);
		                                            }

                							        $CashOutdata = array(
                    							        'game_provider_code' => $provider_code,
                        								'game_type_code' => GAME_SPORTSBOOK,
                        								'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
                        								'game_result_type' => $result_type,
                        								'game_code' => (isset($game_code_data[trim($result_row['sport_type'])]) ? $game_code_data[trim($result_row['sport_type'])] : "Other"),
                        								'game_real_code' => trim($result_row['sport_type']),
                        								'bet_id' => trim($cashout_result_row['cashout_id']),
                        								'bet_transaction_id' => '',
                        								'bet_ref_no' => trim($result_row['trans_id']),
                        								'bet_match_id' => (isset($result_row['match_id']) ? trim($result_row['match_id']) : "0"),
                        								'bet_time' => strtotime('+12 hours', strtotime(trim($cashout_result_row['transdate']))),
                        								'bet_amount' => $bet_amount,
                        								'bet_amount_valid' => $bet_amount_valid,
                        								'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['settlement_time']))),
                        								'win_loss' => $win_loss,
                        								'game_time' => strtotime('+12 hours', strtotime(trim($result_row['match_datetime']))),
                        								'report_time' => strtotime('+12 hours', strtotime(trim($cashout_result_row['winlost_datetime']))),
                        								'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['settlement_time']))),
			    										'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['settlement_time']))),
			    										'created_date' => time(),
                        								'payout_amount' => $payout_amount,
                        								'promotion_amount' => 0,
                        								'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        								'odds_currency' => (isset($odds_type[trim($result_row['odds_type'])]) ? $odds_type[trim($result_row['odds_type'])] : "OT"),
                        								'odds_rate' => trim($result_row['odds']),
                        								'bet_code' => (isset($result_row['bet_type']) ? trim($result_row['bet_type']) : "0"),
                        								'status' => $status,
                        								'game_username' => trim($result_row['vendor_member_id']),
                        								'table_id' => '',
                        								'player_id' =>  $member_lists[$exact_username],
                    							    );
                    							    if( ! in_array($CashOutdata['bet_id'], $transaction_lists))
                        							{
                        							    $PBdata['game_result'] = "";
                        								$CashOutdata['bet_info'] = json_encode($result_row);
                        						        $CashOutdata['insert_type'] = SYNC_DEFAULT;
                        								array_push($Bdata, $CashOutdata);
                        							}else{
                        							    $PBdata['game_result'] = "";
                        								$CashOutdata['bet_update_info'] = json_encode($result_row);
                        						        $CashOutdata['update_type'] = SYNC_DEFAULT;
                        								array_push($BUdata, $CashOutdata);
                        								array_push($BUIDdata, $CashOutdata['bet_id']);
                        							}

                        							if($PBdata['status'] == STATUS_COMPLETE){
														$PBdataWL = array(
															'player_id' => $CashOutdata['player_id'],
															'payout_time' => $CashOutdata['payout_time'],
															'game_provider_code' => $CashOutdata['game_provider_code'],
															'game_type_code' => $CashOutdata['game_type_code'],
															'total_bet' => 1,
															'bet_amount' => $CashOutdata['bet_amount'],
															'bet_amount_valid' => $CashOutdata['bet_amount_valid'],
															'win_loss' => $CashOutdata['win_loss'],
														);
														array_push($BUDdata, $PBdataWL);
													}
                							    }
                							}
										}
									}
									if(isset($result_array['Data']['BetNumberDetails']) && sizeof($result_array['Data']['BetNumberDetails'])>0){
										foreach ($result_array['Data']['BetNumberDetails'] as $result_row){
											$tmp_username = strtolower(str_replace($arr['OperatorID'].'_','',trim($result_row['vendor_member_id'])));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											$bet_time = strtotime('+12 hours', strtotime(trim($result_row['transaction_time'])));
                                            $payout_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $game_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $report_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $sattle_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $compare_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
											
											if($arr['CurrencyId'] == "15" || $arr['CurrencyId'] == "51" || $arr['CurrencyId'] == "70" || $arr['CurrencyId'] == "71"){
                                            	$bet_amount = trim($result_row['stake']) * 1000;
												$bet_amount_valid = abs(trim($result_row['winlost_amount'])) * 1000;
												$win_loss = trim($result_row['winlost_amount']) * 1000;
                                            }else{
                                            	$bet_amount = trim($result_row['stake']);
												$bet_amount_valid = abs(trim($result_row['winlost_amount']));
												$win_loss = trim($result_row['winlost_amount']);
                                            }

											$PBdata = array(
	    										'game_provider_code' => $provider_code,
	    										'game_type_code' => GAME_OTHERS,
	    										'game_provider_type_code' => $provider_code."_".GAME_OTHERS,
	    										'game_result_type' => $result_type,
	    										'game_code' => (isset($game_code_data[trim($result_row['sport_type'])]) ? $game_code_data[trim($result_row['sport_type'])] : "Other"),
	    										'game_real_code' => trim($result_row['sport_type']),
	    										'bet_id' => trim($result_row['trans_id']),
	    										'bet_transaction_id' => '',
	    										'bet_ref_no' => '',
	    										'bet_match_id' => (isset($result_row['match_id']) ? trim($result_row['match_id']) : "0"),
	    										'bet_time' => $bet_time,
	    										'bet_amount' => $bet_amount,
	    										'bet_amount_valid' => $bet_amount_valid,
	    										'payout_time' => $payout_time,
	    										'win_loss' => $win_loss,
	    										'game_time' => $game_time,
	    										'report_time' => $report_time,
	    										'sattle_time' => $sattle_time,
	    										'compare_time' => $compare_time,
	    										'created_date' => time(),
	    										'payout_amount' => 0,
	    										'promotion_amount' => 0,
	    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	    										'odds_currency' => (isset($odds_type[trim($result_row['odds_type'])]) ? $odds_type[trim($result_row['odds_type'])] : "OT"),
	    										'odds_rate' => trim($result_row['odds']),
	    										'bet_code' => (isset($result_row['bet_type']) ? trim($result_row['bet_type']) : "0"),
	    										'status' => STATUS_COMPLETE,
	    										'game_username' => trim($result_row['vendor_member_id']),
	    										'table_id' => trim($result_row['match_id']),
	    										'player_id' =>  $member_lists[$exact_username],
	    									);
	    									if($status == STATUS_COMPLETE){
	    										$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
	    										if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
	    									}else{
												$PBdata['payout_amount'] = 0;
											}
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
									}

									if(isset($result_array['Data']['BetVirtualSportDetails']) && sizeof($result_array['Data']['BetVirtualSportDetails'])>0){
										foreach ($result_array['Data']['BetVirtualSportDetails'] as $result_row){

											$CashOutdata = array();
											if($result_row['ticket_status'] == "void" || $result_row['ticket_status'] == "refund" || $result_row['ticket_status'] == "reject"){
		    									$status = STATUS_CANCEL;
		    								}else{
		    									if($result_row['ticket_status'] == "running" || $result_row['ticket_status'] == "waiting"){
	    											$status = STATUS_PENDING;
	    										}else{
	    											$status = STATUS_COMPLETE;
	    										}
		    								}
											$tmp_username = strtolower(str_replace($arr['OperatorID'].'_','',trim($result_row['vendor_member_id'])));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											$bet_time = strtotime('+12 hours', strtotime(trim($result_row['transaction_time'])));
                                            $payout_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $game_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $report_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $sattle_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $compare_time = strtotime('+12 hours', strtotime(trim($result_row['winlost_datetime'])));
											
											if($arr['CurrencyId'] == "15" || $arr['CurrencyId'] == "51" || $arr['CurrencyId'] == "70" || $arr['CurrencyId'] == "71"){
                                            	$bet_amount = trim($result_row['stake']) * 1000;
												$bet_amount_valid = abs(trim($result_row['winlost_amount'])) * 1000;
												$win_loss = trim($result_row['winlost_amount']) * 1000;
                                            }else{
                                            	$bet_amount = trim($result_row['stake']);
												$bet_amount_valid = abs(trim($result_row['winlost_amount']));
												$win_loss = trim($result_row['winlost_amount']);
                                            }

                                            $PBdata = array(
	    										'game_provider_code' => $provider_code,
	    										'game_type_code' => GAME_VIRTUAL_SPORTS,
	    										'game_provider_type_code' => $provider_code."_".GAME_VIRTUAL_SPORTS,
	    										'game_result_type' => $result_type,
	    										'game_code' => (isset($game_code_data[trim($result_row['sport_type'])]) ? $game_code_data[trim($result_row['sport_type'])] : "Other"),
	    										'game_real_code' => trim($result_row['sport_type']),
	    										'bet_id' => trim($result_row['trans_id']),
	    										'bet_transaction_id' => trim($result_row['parlay_ref_no']),
	    										'bet_ref_no' => '',
	    										'bet_match_id' => (isset($result_row['match_id']) ? trim($result_row['match_id']) : "0"),
	    										'bet_time' => $bet_time,
	    										'bet_amount' => $bet_amount,
	    										'bet_amount_valid' => $bet_amount_valid,
	    										'payout_time' => $payout_time,
	    										'win_loss' => $win_loss,
	    										'game_time' => $game_time,
	    										'report_time' => $report_time,
	    										'sattle_time' => $sattle_time,
	    										'compare_time' => $compare_time,
	    										'created_date' => time(),
	    										'payout_amount' => 0,
	    										'promotion_amount' => 0,
	    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	    										'odds_currency' => (isset($odds_type[trim($result_row['odds_type'])]) ? $odds_type[trim($result_row['odds_type'])] : "OT"),
	    										'odds_rate' => trim($result_row['odds']),
	    										'bet_code' => (isset($result_row['bet_type']) ? trim($result_row['bet_type']) : "0"),
	    										'status' => $status,
	    										'game_username' => trim($result_row['vendor_member_id']),
	    										'table_id' => trim($result_row['match_id']),
	    										'player_id' =>  $member_lists[$exact_username],
	    									);
	    									if($status == STATUS_COMPLETE){
	    										$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
	    										if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
	    									}else{
												$PBdata['payout_amount'] = 0;
											}
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
									}
								}
							}
						}
					}
                    
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function icg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'ICG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
					
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

					$game_type_code_data = array(
						'slot' => GAME_SLOTS,
						'fish' => GAME_FISHING,
						'arcade' => GAME_OTHERS,
						'card' => GAME_BOARD_GAME,
					);
					$token_response = $this->icg_connect_key($arr);
					if($token_response['code'] == '0')
					{
						$token_result_array = json_decode($token_response['data'], TRUE);
						if(isset($token_result_array['token']))
						{
						    $is_loop = TRUE;
						    while($is_loop == TRUE){
								$Bdata = array();
								$BUdata = array();
								$BUIDdata = array();
								$BUDdata = array();
								$DBdata['sync_status'] = STATUS_NO;
								$DBdata['page_id'] = $page_id;
								$DBdata['resp_data'] = '';
								$response = $this->icg_connect($arr, $start_time, $end_time, $page_id, $token_result_array['token']);
								if($response['code'] == '0')
								{
									$result_array = json_decode($response['data'], TRUE);
									if( ! empty($result_array))
									{
										if(isset($result_array['data']))
										{
											$DBdata['resp_data'] = json_encode($result_array);
											$DBdata['sync_status'] = STATUS_YES;
											if(sizeof($result_array['data'])>0){
												if($is_retrieve == FALSE){
													$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
													$is_retrieve = TRUE;
												}
												foreach($result_array['data'] as $result_row){
													$tmp_username = strtolower(trim($result_row['player']));
													$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

													if($result_row['status'] == "finish"){
														$status = STATUS_COMPLETE;
													}else if($result_row['isRevocation'] == "cancel"){
														$status = STATUS_CANCEL;
													}else{
														$status = STATUS_PENDING;
													}


													$PBdata = array(
												        'game_provider_code' => $provider_code,
												        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
												        'game_result_type' => $result_type,
												        'game_code' => trim($result_row['game']),
												        'game_real_code' => trim($result_row['gameType']),
												        'bet_id' => trim($result_row['id']),
												        'bet_time' => strtotime(trim($result_row['createdAt'])),
												        'game_time' => strtotime(trim($result_row['updatedAt'])),
										       			'report_time' => strtotime(trim($result_row['updatedAt'])),
												        'bet_amount' => bcdiv((trim($result_row['bet'])/100),1,2),
												        'bet_amount_valid' => bcdiv((trim($result_row['validBet'])/100),1,2),
												        'payout_amount' => 0,
												        'promotion_amount' => 0,
												        'payout_time' => strtotime(trim($result_row['updatedAt'])),
												        'sattle_time' => strtotime(trim($result_row['updatedAt'])),
														'compare_time' => strtotime(trim($result_row['updatedAt'])),
														'created_date' => time(),
												        'win_loss' => bcdiv(((trim($result_row['win']) - trim($result_row['bet']))/100),1,2),
												        'table_id' => trim($result_row['gameId']),
												        'round' => trim($result_row['productId']),
												        'subround'  => trim($result_row['setId']),
												        'bet_code' => '',
										        		'game_result' => '',
												        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												        'status' => $status,
												        'game_username' => trim($result_row['player']),
												        'player_id' => $member_lists[$exact_username],
												    );

													if($status == STATUS_COMPLETE){
														if($PBdata['win_loss'] != 0){
													    	$PBdata['payout_amount'] = bcdiv((trim($result_row['win'])/100),1,2);
													    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
													    }
													}
													$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];

													if( ! in_array($PBdata['bet_id'], $transaction_lists))
													{					
														$PBdata['bet_info'] = json_encode($result_row);
												        $PBdata['insert_type'] = SYNC_DEFAULT;
														array_push($Bdata, $PBdata);
													}else{
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
											}else{
												$is_loop = FALSE;
											}
											$page_id++;
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
								}
								$this->db->insert('game_result_logs', $DBdata);
								$result_promotion_reset = array('promotion_amount' => 0);
								if(!empty($BUIDdata)){
									$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
									if( ! empty($transaction_lists_old)){
										foreach($transaction_lists_old as $transaction_lists_old_row){
											if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $transaction_lists_old_row['player_id'],
													'payout_time' => $transaction_lists_old_row['payout_time'],
													'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
													'game_type_code' => $transaction_lists_old_row['game_type_code'],
													'total_bet' => -1,
													'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
													'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
													'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
									}
								}
								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}
								if( ! empty($BUDdata))
								{
									$this->db->insert_batch('win_loss_logs', $BUDdata);
								}
								if( ! empty($BUdata))
								{
									foreach($BUdata as $BUdataRow){
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
									}
								}
								sleep(5);
							}
						}
					}
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

	public function jdb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'JDB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-25 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+15 minutes', strtotime($initial_time))));
				}
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
				    $OffTime = ((isset($arr['OffTime'])) ? $arr['OffTime'] : 0);
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time) - $OffTime;
					$db_record_end_time = strtotime('+1 days' ,$end_time) - $OffTime;
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
					$game_type_code_data = array(
						'0' => GAME_SLOTS,
						'7' => GAME_FISHING,
						'9' => GAME_OTHERS,
						'12' => GAME_LOTTERY,
						'18' => GAME_BOARD_GAME,
					);
					$response = $this->jdb_connect($arr, $start_time, $end_time, 29);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']) && sizeof($result_array['data'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
									foreach($result_array['data'] as $result_row){
										$tmp_username = strtolower(trim($result_row['playerId']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
										if(isset($result_row['hasGamble']) && $result_row['hasGamble'] = "1"){
											$bet_amount = trim($result_row['bet']) * -1;
											$bet_amount_valid = trim($result_row['bet']) * -1;
											$win_loss = trim($result_row['total']);
											$payout_amount = trim($result_row['win']);
										}else{
											$bet_amount = trim($result_row['bet']) * -1;
											$bet_amount_valid = trim($result_row['bet']) * -1;
											$win_loss = trim($result_row['total']);
											$payout_amount = trim($result_row['win']);
										}
										$PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gType'])]) ? $game_type_code_data[trim($result_row['gType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['mtype']),
									        'game_real_code' => trim($result_row['mtype']),
									        'bet_id' => trim($result_row['seqNo']),
									        'bet_time' => strtotime(trim($result_row['gameDate'])) - $OffTime,
									        'game_time' => strtotime(trim($result_row['gameDate'])) - $OffTime,
									        'report_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => $payout_amount,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
									        'sattle_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
											'compare_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['playerId'],
									        'player_id' => $member_lists[$exact_username],
									    );
										if($status == STATUS_COMPLETE){
											if($PBdata['win_loss'] != 0){
												$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
											}
										}
										$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];
									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}else if(isset($result_array['status']) && $result_array['status'] == '8006')
							{
								$DBdata['sync_status'] = STATUS_YES;	
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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
	public function jdb_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'JDB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-240 minutes', $current_time);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
				    $OffTime = ((isset($arr['OffTime'])) ? $arr['OffTime'] : 0);
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time) - $OffTime;
					$db_record_end_time = strtotime('+15 days' ,$end_time) - $OffTime;
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
					$game_type_code_data = array(
						'0' => GAME_SLOTS,
						'7' => GAME_FISHING,
						'9' => GAME_OTHERS,
						'12' => GAME_LOTTERY,
						'18' => GAME_BOARD_GAME,
					);
					$response = $this->jdb_connect($arr, $start_time, $end_time, 64);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']) && sizeof($result_array['data'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
									foreach($result_array['data'] as $result_row){
										$tmp_username = strtolower(trim($result_row['playerId']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
										if(isset($result_row['hasGamble']) && $result_row['hasGamble'] = "1"){
											$bet_amount = trim($result_row['bet']) * -1;
											$bet_amount_valid = trim($result_row['bet']) * -1;
											$win_loss = trim($result_row['total']);
											$payout_amount = trim($result_row['win']);
										}else{
											$bet_amount = trim($result_row['bet']) * -1;
											$bet_amount_valid = trim($result_row['bet']) * -1;
											$win_loss = trim($result_row['total']);
											$payout_amount = trim($result_row['win']);
										}
										$PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gType'])]) ? $game_type_code_data[trim($result_row['gType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['mtype']),
									        'game_real_code' => trim($result_row['mtype']),
									        'bet_id' => trim($result_row['seqNo']),
									        'bet_time' => strtotime(trim($result_row['gameDate'])) - $OffTime,
									        'game_time' => strtotime(trim($result_row['gameDate'])) - $OffTime,
									        'report_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => $payout_amount,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
									        'sattle_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
											'compare_time' => strtotime(trim($result_row['lastModifyTime'])) - $OffTime,
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );
										$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];
										if($status == STATUS_COMPLETE){
											if($PBdata['win_loss'] != 0){
												$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
											}
										}
									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
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
										}else{
											$PBdata['bet_update_info'] = json_encode($result_row);
									        $PBdata['update_type'] = SYNC_DEFAULT;
											array_push($BUdata, $PBdata);
										}
									}
								}
							}else if(isset($result_array['status']) && $result_array['status'] == '8006')
							{
								$DBdata['sync_status'] = STATUS_YES;	
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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

	public function jk($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'JK';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time)-60;
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					do 
					{
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['next_id'] = $next_id;
						$DBdata['resp_data'] = '';
						
						$response = $this->jk_connect($arr, $start_time, $end_time, $next_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['data']))
								{
									if($is_retrieve == FALSE){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$is_retrieve = TRUE;
									}
									$next_id = trim($result_array['nextId']);
									$DBdata['sync_status'] = STATUS_YES;
									$DBdata['resp_data'] = $response['data'];
									
									//Prepare game list
									$game_list = array();
									if(isset($result_array['games']))
									{
										for($i=0;$i<sizeof($result_array['games']);$i++)
										{
											$game_list[trim($result_array['games'][$i]['GameCode'])] = trim($result_array['games'][$i]['GameType']);
										}
									}
									
									//Normal
									if(isset($result_array['data']['Game']))
									{
										for($i=0;$i<sizeof($result_array['data']['Game']);$i++)
										{
											$tmp_username = strtolower(trim($result_array['data']['Game'][$i]['Username']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
								
											//Response time (UTC +8)
											if($arr['CurrencyType'] == "IDR"){
												$bet_amount = trim($result_array['data']['Game'][$i]['Amount']) * 1000;
												$bet_amount_valid = trim($result_array['data']['Game'][$i]['Amount']) * 1000;
												$win_loss = (trim($result_array['data']['Game'][$i]['Result']) - trim($result_array['data']['Game'][$i]['Amount'])) * 1000;
												$payout_amount = ((trim($result_array['data']['Game'][$i]['Result']) > 0) ? trim($result_array['data']['Game'][$i]['Result']) : 0) * 1000;
												$jackpot_win = 0;
											}else{
												$bet_amount = trim($result_array['data']['Game'][$i]['Amount']);
												$bet_amount_valid = trim($result_array['data']['Game'][$i]['Amount']);
												$win_loss = (trim($result_array['data']['Game'][$i]['Result']) - trim($result_array['data']['Game'][$i]['Amount']));
												$payout_amount = ((trim($result_array['data']['Game'][$i]['Result']) > 0) ? trim($result_array['data']['Game'][$i]['Result']) : 0);
												$jackpot_win = 0;
											}

											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_SLOTS,
												'game_code' => trim($result_array['data']['Game'][$i]['GameCode']),
												'game_real_code' => trim($result_array['data']['Game'][$i]['GameCode']),
												'game_result_type' => $result_type,
												'bet_id' => trim($result_array['data']['Game'][$i]['OCode']),
												'bet_time' => strtotime(trim($result_array['data']['Game'][$i]['Time'])),
												'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount_valid,
												'payout_time' => strtotime(trim($result_array['data']['Game'][$i]['Time'])),
												'sattle_time' => strtotime(trim($result_array['data']['Game'][$i]['Time'])),
												'compare_time' => strtotime(trim($result_array['data']['Game'][$i]['Time'])),
												'created_date' => time(),
												'payout_amount' => $payout_amount,
												'promotion_amount' => 0,
												'win_loss' => $win_loss,
												'jackpot_win' => $jackpot_win,
												'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_array['data']['Game'][$i]['Username']),
												'player_id' => $member_lists[$exact_username],
											);
											
											switch($game_list[$PBdata['game_code']])
											{
												case 'Fishing': $PBdata['game_type_code'] = GAME_FISHING; break;
												case 'Slot': $PBdata['game_type_code'] = GAME_SLOTS; break;
												default: $PBdata['game_type_code'] = GAME_OTHERS; break;
											}

											$PBdata['game_provider_type_code'] = $provider_code."_".$PBdata['game_type_code'];
											
											
											if($PBdata['bet_amount'] == 0)
											{
												$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
											}else{
												if($PBdata['win_loss'] != 0){
											    	$PBdata['payout_amount'] = ((trim($result_array['data']['Game'][$i]['Result']) > 0) ? trim($result_array['data']['Game'][$i]['Result']) : 0);
											    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
											    }
											}

											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_array['data']['Game'][$i]);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);

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
										}
									}

									//Jackpot
									if(isset($result_array['data']['Jackpot']))
									{
										for($i=0;$i<sizeof($result_array['data']['Jackpot']);$i++)
										{
											$tmp_username = strtolower(trim($result_array['data']['Jackpot'][$i]['Username']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											
											//Response time (UTC +8)
											if($arr['CurrencyType'] == "IDR"){
												$bet_amount = trim($result_array['data']['Jackpot'][$i]['Amount']) * 1000;
												$bet_amount_valid = trim($result_array['data']['Jackpot'][$i]['Amount']) * 1000;
												$win_loss = (trim($result_array['data']['Jackpot'][$i]['Result']) - trim($result_array['data']['Jackpot'][$i]['Amount'])) * 1000;
												$payout_amount = ((trim($result_array['data']['Jackpot'][$i]['Result']) > 0) ? trim($result_array['data']['Jackpot'][$i]['Result']) : 0) * 1000;
												$jackpot_win = trim($result_array['data']['Jackpot'][$i]['Result']) * 1000;
											}else{
												$bet_amount = trim($result_array['data']['Jackpot'][$i]['Amount']);
												$bet_amount_valid = trim($result_array['data']['Jackpot'][$i]['Amount']);
												$win_loss = (trim($result_array['data']['Jackpot'][$i]['Result']) - trim($result_array['data']['Jackpot'][$i]['Amount']));
												$payout_amount = ((trim($result_array['data']['Jackpot'][$i]['Result']) > 0) ? trim($result_array['data']['Jackpot'][$i]['Result']) : 0);
												$jackpot_win = trim($result_array['data']['Jackpot'][$i]['Result']);
											}

											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_SLOTS,
												'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
												'game_code' => trim($result_array['data']['Jackpot'][$i]['GameCode']),
												'game_real_code' => trim($result_array['data']['Jackpot'][$i]['GameCode']), 
												'game_result_type' => $result_type,
												'bet_id' => trim($result_array['data']['Jackpot'][$i]['OCode']),
												'bet_time' => strtotime(trim($result_array['data']['Jackpot'][$i]['Time'])),
												'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount_valid,
												'payout_time' => strtotime(trim($result_array['data']['Jackpot'][$i]['Time'])),
												'sattle_time' => strtotime(trim($result_array['data']['Jackpot'][$i]['Time'])),
												'compare_time' => strtotime(trim($result_array['data']['Jackpot'][$i]['Time'])),
												'created_date' => time(),
												'payout_amount' => $payout_amount,
												'promotion_amount' => 0,
												'win_loss' => $win_loss,
												'jackpot_win' => $jackpot_win,
												'game_round_type' => GAME_ROUND_TYPE_JACKPOT,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_array['data']['Jackpot'][$i]['Username']),
												'player_id' => $member_lists[$exact_username],
											);
											
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_array['data']['Jackpot'][$i]);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);

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
										}
									}								
								}
							}
						}
						
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);

						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						sleep(1);
					}
					while(isset($next_id) &&  ! empty($next_id));

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
	
	public function ky($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'KY';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
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

					
					$response = $this->ky_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						ad($result_array);
						if(isset($result_array['d'])){
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['d']['code']) && ($result_array['d']['code']=="16" || $result_array['d']['code']=="0")){
								$DBdata['sync_status'] = STATUS_YES;
								if($result_array['d']['code']=="0"){
									if(isset($result_array['d']['list']) && $result_array['d']['count']>0){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$result_row = $result_array['d']['list'];
										for($i=0;$i<$result_array['d']['count'];$i++){
										    (strpos($result_row['Accounts'][$i], $arr['Agent']) !== false) ? $le_username = substr(trim($result_row['Accounts'][$i]), strlen($arr['Agent'])+1) : $le_username = trim($result_row['Accounts'][$i]);
										  	$tmp_username = strtolower(trim($le_username));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_BOARD_GAME,
												'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
												'game_result_type' => $result_type,
												'game_code' => trim($result_row['KindID'][$i]),
												'game_real_code' => trim($result_row['KindID'][$i]),
												'bet_id' => trim($result_row['GameID'][$i]),
												'bet_ref_no' => trim($result_row['RecordID'][$i]),
												'bet_time' => strtotime(trim($result_row['GameStartTime'][$i])),
												'bet_amount' => trim($result_row['AllBet'][$i]),
												'bet_amount_valid' => trim($result_row['CellScore'][$i]),
												'payout_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'win_loss' =>  trim($result_row['Profit'][$i]),
												'game_time' => strtotime(trim($result_row['GameStartTime'][$i])),
												'sattle_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'compare_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'created_date' => time(),
												'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_row['Accounts'][$i]),
												'table_id' => trim($result_row['TableID'][$i]),
												'game_result' => trim($result_row['CardValue'][$i]),
												'player_id' => $member_lists[$exact_username],
												'bet_info' => json_encode($result_row),
												'insert_type' => SYNC_DEFAULT,
											);
											
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{								
												array_push($Bdata, $PBdata);
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
										}
									}
								}
							}
						}
					}
					
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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

	public function le($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'LE';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					
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

					
					$response = $this->le_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(isset($result_array['d'])){
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['d']['code']) && ($result_array['d']['code']=="16" || $result_array['d']['code']=="0")){
								$DBdata['sync_status'] = STATUS_YES;
								if($result_array['d']['code']=="0"){
									if(isset($result_array['d']['list']) && $result_array['d']['count']>0){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$result_row = $result_array['d']['list'];
										for($i=0;$i<$result_array['d']['count'];$i++){
										    (strpos($result_row['Accounts'][$i], $arr['Agent']) !== false) ? $le_username = substr(trim($result_row['Accounts'][$i]), strlen($arr['Agent'])+1) : $le_username = trim($result_row['Accounts'][$i]);
										  	$tmp_username = strtolower(trim($le_username));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_BOARD_GAME,
												'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
												'game_result_type' => $result_type,
												'game_code' => trim($result_row['KindID'][$i]),
												'game_real_code' => trim($result_row['KindID'][$i]),
												'bet_id' => trim($result_row['GameID'][$i]),
												'bet_ref_no' => trim($result_row['RecordID'][$i]),
												'bet_time' => strtotime(trim($result_row['GameStartTime'][$i])),
												'bet_amount' => trim($result_row['AllBet'][$i]),
												'bet_amount_valid' => trim($result_row['CellScore'][$i]),
												'payout_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'win_loss' =>  trim($result_row['Profit'][$i]),
												'game_time' => strtotime(trim($result_row['GameStartTime'][$i])),
												'sattle_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'compare_time' => strtotime(trim($result_row['GameEndTime'][$i])),
												'created_date' => time(),
												'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_row['Accounts'][$i]),
												'table_id' => trim($result_row['TableID'][$i]),
												'game_result' => trim($result_row['CardValue'][$i]),
												'player_id' => $member_lists[$exact_username],
												'bet_info' => json_encode($result_row),
												'insert_type' => SYNC_DEFAULT,
											);
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{								
												array_push($Bdata, $PBdata);
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
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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

	public function lh($member_lists = NULL){
		
		set_time_limit(0);
		$provider_code = 'LH';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$odds_type = array(
						'malay' => "MY",
						'hongkong' => "HK",
						'euro' => "DE",
						'indo' => "ID",
					);

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						
						$Bdata = array();
						$BUdata = array();
						$BUDdata = array();
						$BUWdata = array();
						$BUWCdata = array();
						$BUIDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->lh_connect($arr, $start_time, $end_time, $page_id);
					
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['results']) && isset($result_array['count']))
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if(sizeof($result_array['results'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
    										$is_retrieve = TRUE;
    									}

										foreach($result_array['results'] as $result_row){
										    $tmp_username = strtolower(trim($result_row['member_code']));
										    $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
										    
										    $PBdata = array(
    									        'game_provider_code' => $provider_code,
    									        'game_type_code' => GAME_ESPORTS,
    									        'game_provider_type_code' => $provider_code."_".GAME_ESPORTS,
    									        'game_result_type' => $result_type,
    									        'game_code' => trim($result_row['game_type_name']),
    									        'game_real_code' => trim($result_row['game_type_id']),
    									        'bet_id' => trim($result_row['id']),
    									        'bet_time' => strtotime(trim($result_row['date_created'])),
    									        'game_time' => strtotime(trim($result_row['event_datetime'])),
								       			'report_time' => strtotime(trim($result_row['modified_datetime'])),
    									        'bet_amount' => trim($result_row['amount']),
    									        'bet_amount_valid' => trim($result_row['amount']),
    									        'payout_amount' => 0,
    									        'promotion_amount' => 0,
    									        'payout_time' => (!empty(trim($result_row['settlement_datetime'])) ? strtotime(trim($result_row['settlement_datetime'])) : "0"),
    									        'sattle_time' => (!empty(trim($result_row['settlement_datetime'])) ? strtotime(trim($result_row['settlement_datetime'])) : "0"),
												'compare_time' => (!empty(trim($result_row['settlement_datetime'])) ? strtotime(trim($result_row['settlement_datetime'])) : "0"),
												'created_date' => time(),
    									        'win_loss' => 0,
    									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    									       	'odds_currency' => (isset($odds_type[trim($result_row['member_odds_style'])]) ? $odds_type[trim($result_row['member_odds_style'])] : "OT"),
    											'odds_rate' => trim($result_row['member_odds']),
    											'table_id' => trim($result_row['event_id']),
    									        'status' => STATUS_CANCEL,
    									        'game_username' => trim($result_row['member_code']),
    									        'player_id' => $member_lists[$exact_username],
    									    );

										    if(trim($result_row['settlement_status']) == "settled"){
										    	$PBdata['status'] = STATUS_COMPLETE;
										    	$PBdata['win_loss'] = (trim($result_row['earnings']) - trim($result_row['amount']));
										    }else if(trim($result_row['settlement_status']) == "confirmed"){
										    	$PBdata['status'] = STATUS_PENDING;
										    }
										    if($PBdata['status'] == STATUS_COMPLETE){
	    										$PBdata['payout_amount'] = $result_row['earnings'];
	    										if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
	    									}else{
												$PBdata['payout_amount'] = 0;
											}

										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
							
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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

	public function mg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'MG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					
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

					$token_response = $this->mg_connect($arr, $start_time, $end_time, "RetrieveToken");
					if($token_response['code'] == '0')
					{
						$token_result_array = json_decode($token_response['data'], TRUE);
						if(isset($token_result_array['access_token']))
						{
							$response = $this->mg_connect($arr, $start_time, $end_time, "RetrieveRecord",$next_id,$token_result_array['access_token']);
							if($response['code'] == '0' && $response['http_code'] == '200' )
							{
							    $DBdata['sync_status'] = STATUS_YES;
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									$DBdata['resp_data'] = json_encode($result_array);
									if(sizeof($result_array)>0){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										foreach($result_array as $result_row){
											$tmp_username = strtolower(trim($result_row['playerId']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											if(trim($result_row['betStatus']) == 'Closed'){
												$status = STATUS_COMPLETE;
											}else{
												$status = STATUS_CANCEL;
											}

											$game_real_code = strtoupper(trim($result_row['gameCode']));
											if(strpos($game_real_code, 'BACCARAT')){
												$game_code = "Baccarat";
											  	$game_type_code = GAME_LIVE_CASINO;
											}else if(strpos($game_real_code, 'ROULETTE') !== false){
												$game_code = "Roulette";
											 	$game_type_code = GAME_LIVE_CASINO;
											}else if(strpos($game_real_code, 'ANDARBAHAR') !== false){
											    $game_code = "Andar Bahar";
											 	$game_type_code = GAME_LIVE_CASINO;
											}else if(strpos($game_real_code, 'SICBO') !== false){
											    $game_code = "Sicbo";
											 	$game_type_code = GAME_LIVE_CASINO;
											}else{
												$game_code = "Slot";
												$game_type_code = GAME_SLOTS;
											}


											if($arr['CurrencyType'] == "IDR"){
												$bet_amount = trim($result_row['betAmount']) * 1000;
												$bet_amount_valid = trim($result_row['betAmount']) * 1000;
												$win_loss = (trim($result_row['payoutAmount']) - trim($result_row['betAmount'])) * 1000;
												$payout_amount = trim($result_row['payoutAmount']) * 1000;
											}else{
												$bet_amount = trim($result_row['betAmount']);
												$bet_amount_valid = trim($result_row['betAmount']);
												$win_loss = (trim($result_row['payoutAmount']) - trim($result_row['betAmount']));
												$payout_amount = trim($result_row['payoutAmount']);
											}

											//UTC time
											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => $game_type_code,
										        'game_result_type' => $result_type,
										        'game_provider_type_code' => $provider_code."_".$game_type_code,
										        'game_code' => $game_code,
										        'game_real_code' => strtoupper(trim($result_row['gameCode'])),
										        'bet_id' => trim($result_row['betUID']),
										        'bet_transaction_id' => trim($result_row['externalTransactionId']),
										        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['createdDateUTC']))),
										        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['gameStartTimeUTC']))),
										        'report_time' => strtotime('+8 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
										        'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount,
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'status' => $status,
										        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
										        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
												'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
												'created_date' => time(),
										        'win_loss' =>  $win_loss,
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'game_result' => trim($result_row['metadata']),
										        'game_username' => $result_row['playerId'],
										        'player_id' => $member_lists[$exact_username],
										    );

											if($status == STATUS_COMPLETE){
												$PBdata['payout_amount'] = $payout_amount;
												if($PBdata['win_loss'] != 0){
													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
												}
											}

											$DBdata['next_id'] = $PBdata['bet_id'];

											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
									}									
								}


								$this->db->trans_start();
								$this->db->insert('game_result_logs', $DBdata);
								$result_promotion_reset = array('promotion_amount' => 0);

								if(!empty($BUIDdata)){
									$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
									if( ! empty($transaction_lists_old)){
										foreach($transaction_lists_old as $transaction_lists_old_row){
											if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $transaction_lists_old_row['player_id'],
													'payout_time' => $transaction_lists_old_row['payout_time'],
													'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
													'game_type_code' => $transaction_lists_old_row['game_type_code'],
													'total_bet' => -1,
													'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
													'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
													'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
									}
								}

								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}

								if( ! empty($BUDdata))
								{
									$this->db->insert_batch('win_loss_logs', $BUDdata);
								}

								if( ! empty($BUdata))
								{
									foreach($BUdata as $BUdataRow){
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
									}
								}
								$this->db->trans_complete();
							}
						}else{
							$DBdata['resp_data'] = json_encode($token_result_array);
							$this->db->trans_start();
							$this->db->insert('game_result_logs', $DBdata);
							$this->db->trans_complete();
						}
					}
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

	public function n2($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'N2';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					
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

					
					$response = $this->n2_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
						$xml_output = simplexml_load_string($xml_utf8);
						$json = json_encode($xml_output);
						$result_array = json_decode($json, TRUE);
						if(isset($result_array['status']) && $result_array['status'] == 'success')
						{
							$DBdata['sync_status'] = STATUS_YES;
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['result']['gameinfo']['game']))
							{
								$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
							    if(isset($result_array['result']['gameinfo']['game']['@attributes'])){
							        $game_result_data[0] = $result_array['result']['gameinfo']['game'];
							    }else{
							        $game_result_data = $result_array['result']['gameinfo']['game'];
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
							            if($game_type_row['@attributes']['code'] == "50002" || $game_type_row['@attributes']['code'] == "51002" || $game_type_row['@attributes']['code'] == "52002"){
							                $game_code ="Roulette";
							            }else if($game_type_row['@attributes']['code'] == "60001" || $game_type_row['@attributes']['code'] == "61001" || $game_type_row['@attributes']['code'] == "62001"){
							                $game_code ="Sicbo";
							            }else if($game_type_row['@attributes']['code'] == "90091" || $game_type_row['@attributes']['code'] == "91091" || $game_type_row['@attributes']['code'] == "90092" || $game_type_row['@attributes']['code'] == "91092"){
							                $game_code ="Baccarat";
							            }else if($game_type_row['@attributes']['code'] == "110001" || $game_type_row['@attributes']['code'] == "110002" || $game_type_row['@attributes']['code'] == "110003"){
							                $game_code ="Black Jack";
							            }else if($game_type_row['@attributes']['code'] == "210001"){
							                $game_code ="Poker";
							            }else if($game_type_row['@attributes']['code'] == "300001" || $game_type_row['@attributes']['code'] == "310001"){
							                $game_code ="Bull Bull";
							            }else{
							                $game_code ="Others";
							            }
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
	        									if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
												}else{
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
							            }
							        }
							    }
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);							
						}
					}
					$this->db->trans_complete();
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
	
	public function ninek($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'NK';
		$result_type = GAME_LOTTERY;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();

				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->ninek_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['success']) && $result_array['success'] == '0'){
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['data']['PageInfo']['TotalPage']);
										if(sizeof($result_array['data']['BetList'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}

											foreach($result_array['data']['BetList'] as $result_row){
											    $tmp_username = strtolower(trim($result_row['MemberAccount']));
											    $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											    
											    if(trim($result_row['Result']) == "X"){
    												$status = STATUS_PENDING;
    												$win_result = STATUS_PENDING;
											    }else if(trim($result_row['Result']) == "C"){
											        $status = STATUS_CANCEL;
    												$win_result = STATUS_CANCEL;
    											}else{
    												$status = STATUS_COMPLETE;
    												if(trim($result_row['Result']) == "W"){
    												    $win_result = STATUS_WIN;
    												}else if(trim($result_row['Result']) == "L"){
    												    $win_result = STATUS_LOSS;
    												}else{
    												    $win_result = STATUS_UNKNOWN;   
    												}
    											}

											    $PBdata = array(
	    									        'game_provider_code' => $provider_code,
	    									        'game_type_code' => GAME_LOTTERY,
	    									        'game_result_type' => $result_type,
	    									        'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
	    									        'game_code' => trim($result_row['TypeCode']),
	    									        'game_real_code' => trim($result_row['TypeCode']),
	    									        'bet_id' => trim($result_row['WagerID']),
	    									        'bet_time' => strtotime(trim($result_row['WagerDate'])),
	    									        'game_time' => strtotime(trim($result_row['WagerDate'])),
									       			'report_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
	    									        'bet_amount' => trim($result_row['TotalAmount']),
	    									        'bet_amount_valid' => trim($result_row['BetAmount']),
	    									        'payout_amount' => trim($result_row['PayOff']),
	    									        'promotion_amount' => 0,
	    									        'payout_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
	    									        'sattle_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
        											'compare_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
        											'created_date' => time(),
	    									        'win_loss' => (trim($result_row['PayOff']) - trim($result_row['TotalAmount'])),
	    									        'game_round_type' => $game_round_type,
	    									        'status' => STATUS_COMPLETE,
	    									        'win_result' => $win_result,
	    									        'game_username' => trim($result_row['MemberAccount']),
	    									        'bet_code' => $result_row['BetItem'],
	    									        'game_result' => trim($result_row['GameResult']),
	    									        'player_id' => $member_lists[$exact_username],
	    									    );
											    if($PBdata['win_loss'] != 0){
										    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
													
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
											}
										}
									}
									$page_id++;
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
	
	public function obsb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'OBSB';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-30 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
    				
					$response = $this->obsb_connect($arr, $start_time, $end_time, 'ORDER');
					if($response['code'] == '0')
					{
					    $response_2 = $this->obsb_connect($arr, $start_time, $end_time, 'HISTORY');
					    if($response_2['code'] == '0')
					    {
					        $result_array = json_decode($response['data'], TRUE);
					        $result_array_2 = json_decode($response_2['data'], TRUE);
					        if(!empty($result_array) && !empty($result_array_2))
						    {
						        $DBdata['resp_data'] = json_encode(array($result_array,$result_array_2));
						        if(isset($result_array['status']) && isset($result_array_2['status'])){
						            if($result_array['status'] == "1" && $result_array_2['status'] == "1"){
						                $DBdata['sync_status'] = STATUS_YES;
						                $all_result = array_merge($result_array['orders_detail'],$result_array_2['orders_detail']);
						                foreach($all_result as $result_row){
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
                        						'player_id' =>  (int) $exact_username,
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
						                }
						            }   
						        }
						    }
					    }
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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
	
	public function og($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'OG';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;
        
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				$next_id = 0;
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
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
					
					$currency_one = array("IDR", "VND", "INR", "MMK");
					
					$win_loss_data = array(
					    'lose' => STATUS_LOSS,
					    'win' => STATUS_WIN,
					    'tie' => STATUS_TIE,
				    );
					
					$game_code_data = array(
					    'SPEED BACCARAT' => "Baccarat",
					    'BACCARAT' => "Baccarat",
					    'BIDDING BACCARAT' => "Baccarat",
					    'NO COMMISSION BACCARAT' => "Baccarat",
					    'NEW DT' => "Dragon Tiger",
					    'CLASSIC DT' => "Dragon Tiger",
					    'MoneyWheel' => "Money Wheel",
					    'ROULETTE' => "Roulette",
					    'BULL BULL' => "Bull Bull",
					    'Three Card' => "Win Three Cards",
					    'SICBO' => "Sicbo",
    				);
    				
					$response = $this->og_connect($arr, $start_time, $end_time, 'ogplus');
					if($response['code'] == '0')
					{
					    if($response['http_code'] == '200'){
					        $result_array = json_decode($response['data'], TRUE);
					        $DBdata['sync_status'] = STATUS_YES;
					        $DBdata['resp_data'] = json_encode($result_array,true);
					        if(!empty($result_array)){
    					        foreach($result_array as $result_row){
    					            $tmp_username = strtolower(explode("_",trim($result_row['membername']))[1]);
    					            $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
                                    $status = STATUS_COMPLETE;
                                    
                                    if(in_array($result_row['currency'],$currency_one)){
                                        $bet_amount = bcdiv(trim($result_row['bettingamount']) * 1000, 1, 2);
                                        $bet_amount_valid = bcdiv(trim($result_row['validbet']) * 1000, 1, 2);
                                        $win_loss = bcdiv(trim($result_row['winloseamount']) * 1000, 1, 2);
                                        $payout_amount = bcdiv((trim($result_row['bettingamount']) + trim($result_row['winloseamount'])) * 1000, 1, 2);
            						}else{
            							$bet_amount = $result_row['bettingamount'];
                                        $bet_amount_valid = trim($result_row['validbet']);
                                        $win_loss = trim($result_row['winloseamount']);
                                        $payout_amount = trim($result_row['bettingamount']) + trim($result_row['winloseamount']);
            						}
            						
                                    $PBdata = array(
                						'game_provider_code' => $provider_code,
                						'game_type_code' => GAME_LIVE_CASINO,
                						'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
                						'game_result_type' => $result_type,
                						'game_code' => (isset($game_code_data[trim($result_row['gamename'])]) ? $game_code_data[trim($result_row['gamename'])] : "Other"),
                						'game_real_code' => trim($result_row['gamename']),
                						'bet_id' => trim($result_row['bettingcode']),
                						'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'bet_amount' => $bet_amount,
                						'bet_amount_valid' => $bet_amount_valid,
                						'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'win_loss' => $win_loss,
                						'game_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'report_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'created_date' => time(),
                						'payout_amount' => $payout_amount,
                						'promotion_amount' => $bet_amount_valid,
                						'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                						'status' => $status,
                						'game_username' => trim($result_row['membername']),
                						'player_id' =>  $member_lists[$exact_username],
                						'bet_code' => $result_row['bet'],
                						'game_result' => json_encode($result_row),
                						'win_result' => (isset($win_loss_data[trim($result_row['winloseresult'])]) ? $win_loss_data[trim($result_row['winloseresult'])] : STATUS_UNKNOWN),
                					);
                					if($status == STATUS_COMPLETE){
                						if($PBdata['win_loss'] != 0){
                							$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
                				    	}
                					}
                					
                					
									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{	
										$PBdata['bet_info'] = json_encode($result_row);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
										
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
    					    }
					    }
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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
	
	public function pgs2($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PGS2';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				#$last_sync_time = strtotime('-120 minutes', $current_time);
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$next_id = 1;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$max_bet_time = 0;
				$is_retrieve == FALSE;
				//Must 5 minutes range from current time
				#if($end_time <= strtotime('-70 minutes', $current_time))
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
						'next_id' => $max_bet_time,
						'resp_data' => '',
					);

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						$Bdata = array();
						$BUdata = array();
						$BUDdata = array();
						$BUWdata = array();
						$BUWCdata = array();
						$BUIDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['next_id'] = $max_bet_time;
						$DBdata['resp_data'] = '';

						$response = $this->pgs2_connect($arr, $start_time, $end_time);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								$DBdata['resp_data'] = json_encode($result_array);
								if(array_key_exists('error',$result_array) && $result_array['error'] == null){
									$DBdata['sync_status'] = STATUS_YES;
									if(isset($result_array['data'])){
										if(sizeof($result_array['data'])){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
    										}
											foreach($result_array['data']  as $result_row){
												$tmp_username = strtolower(trim($result_row['playerName']));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
												
												if($result_row['transactionType'] == 1){
												    $game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
												}else if($result_row['transactionType'] == 2){
												    $game_round_type = GAME_ROUND_TYPE_JACKPOT;
												}else if($result_row['transactionType'] == 3){
												    $game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
												}else{
												    $game_round_type = GAME_ROUND_TYPE_TIP;
												}

												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code . "_" . GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameId']),
											        'game_real_code' => trim($result_row['gameId']),
											        'bet_id' => trim($result_row['betId']),
											        'bet_ref_no' => trim($result_row['parentBetId']),
											        'bet_time' => (int) (trim($result_row['betTime'])/1000),
	                						        'game_time' => (int) (trim($result_row['betEndTime'])/1000),
	                						        'report_time' => (int) (trim($result_row['rowVersion'])/1000),
	                						        'bet_amount' => trim($result_row['betAmount']),
	                								'bet_amount_valid' => trim($result_row['betAmount']),
	                						        'payout_amount' => 0,
	                						        'promotion_amount' => 0,
	                						        'payout_time' => (int) (trim($result_row['betEndTime'])/1000),
	                						        'sattle_time' => (int) (trim($result_row['betEndTime'])/1000),
	                								'compare_time' => (int) (trim($result_row['betEndTime'])/1000),
													'created_date' => time(),
											        'win_loss' =>  trim($result_row['winAmount'])-trim($result_row['betAmount']),
											        'game_round_type' => $game_round_type,
											        'status' => STATUS_COMPLETE,
											        'game_username' => $result_row['playerName'],
											        'player_id' => $member_lists[$exact_username],
											    );
											    
											    if($PBdata['payout_time'] > $max_bet_time){
											        $max_bet_time = $PBdata['payout_time'];
											    }

												if($status == STATUS_COMPLETE){
													$PBdata['payout_amount'] = trim($result_row['winAmount']);
													if($PBdata['win_loss'] != 0){
														$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
													}
												}

												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);

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
												}else{
													array_push($BdataID, $PBdata['bet_id']);
												}
											}
											
											if(sizeof($result_array['data']) == 5000){
											    $DBdata['next_id'] = $max_bet_time;
											    $start_time = $max_bet_time;
											}else{
												$is_loop = FALSE;
											}
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}

						$this->db->trans_start();
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						$this->db->trans_complete();
					}

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

	public function pgsoft($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PGSF';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUIDdata  = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					
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

					$game_type_code_data = array(
						'SL' => GAME_SLOTS,
						'LC' => GAME_LIVE_CASINO,
						'SB' => GAME_SPORTSBOOK,
						'CB' => GAME_BOARD_GAME,
						'ES' => GAME_ESPORTS,
						'LK' => GAME_LOTTERY,
						'FH' => GAME_FISHING,
						'PK' => GAME_POKER,
						'MG' => GAME_OTHERS,
						'OT' => GAME_OTHERS,
					);

					$response = $this->pgsoft_connect($arr, $start_time, $end_time, "RetrieveRecord",$next_id);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(isset($result_array['errCode']) && $result_array['errCode'] == "0")
						{
						    $DBdata['sync_status'] = STATUS_YES;
							$DBdata['resp_data'] = json_encode($result_array);
							$DBdata['next_id'] = $result_array['lastversionkey'];
							if(!empty($result_array['result'])){
								$result_json = json_decode($result_array['result'],true);
								if(sizeof($result_json) > 0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
									foreach($result_json as $result_row){
										$tmp_username = strtolower(trim($result_row['member']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if(trim($result_row['status']) == '1'){
											$status = STATUS_COMPLETE;
										}else if(trim($result_row['status']) == '0'){
											$status = STATUS_PENDING;
										}else{
											$status = STATUS_CANCEL;
										}

										$bet_amount = trim($result_row['bet']);
										$bet_amount_valid = trim($result_row['turnover']);
										$win_loss = (trim($result_row['payout']) - trim($result_row['bet']));
										$payout_amount = trim($result_row['payout']);
										$jackpot_win = trim($result_row['p_win']);

										//UTC time
										$PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['product'])]) ? $game_type_code_data[trim($result_row['product'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['product'])]) ? $game_type_code_data[trim($result_row['product'])] : GAME_OTHERS),
									        'game_code' => trim($result_row['game_id']),
									        'game_real_code' => trim($result_row['game_id']),
									        'bet_id' => trim($result_row['id']),
									        'bet_transaction_id' => trim($result_row['ref_no']),
									        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['start_time']))),
									        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['match_time']))),
									        'report_time' => strtotime('+8 hours', strtotime(trim($result_row['end_time']))),
									        'bet_amount' => $bet_amount,
											'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'status' => $status,
									        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['end_time']))),
									        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row['end_time']))),
											'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['end_time']))),
											'created_date' => time(),
									        'win_loss' =>  $win_loss,
									        'jackpot_win' => $jackpot_win,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['bet_detail']),
									        'game_result' => trim($result_row['bet_detail']),
									        'game_username' => $result_row['member'],
									        'player_id' => $member_lists[$exact_username],
									    );

									    if(trim($PBdata['jackpot_win']) > 0){
									    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;

									    }

									    if(trim($PBdata['bet_amount']) == 0){
									    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
									    }

										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdata['payout_amount'] = $payout_amount;
											if($PBdata['win_loss'] != 0){
												$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											}
										}

										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
											$PBdata['bet_update_info'] = json_encode($result_row);
									        $PBdata['update_type'] = SYNC_DEFAULT;
											array_push($BUdata, $PBdata);
											array_push($BUIDdata, $PBdata['bet_id']);
										}

										array_push($BdataID, $PBdata['bet_id']);

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
								}
							}									
						}
						if(sizeof($BdataID)>0){
							$response_submit = $this->pgsoft_connect($arr, $start_time, $end_time, "SubmitRecord",$BdataID);
						}

						$this->db->trans_start();
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						$this->db->trans_complete();
					}
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

	public function pp($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PP';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = strtotime('-5 minutes', $sync_data['end_time']);
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
					
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
					$response = $this->pp_connect($arr, $start_time,$result_type);
					if($response['code'] == '0')
					{
						$result_array = array_map("str_getcsv", explode("\n", $response['data']));
						if( ! empty($result_array))
						{
							$DBdata['next_id'] = trim(str_replace('timepoint=', '', $result_array[0][0]));
							$DBdata['sync_status'] = STATUS_YES;
							$DBdata['resp_data'] = json_encode($result_array);
							
							for($i=2;$i<sizeof($result_array);$i++)
							{
								if(sizeof($result_array[$i]) > 10)
								{
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);

									$tmp_username = strtolower(trim($result_array[$i][1]));
									$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

									if($arr['CurrencyType'] == "IDR2"){
										$bet_amount = trim($result_array[$i][9]) * 1000;
										$bet_amount_valid = trim($result_array[$i][9]) * 1000;
										$win_loss = (trim($result_array[$i][10]) - trim($result_array[$i][9])) * 1000;
										$payout_amount = trim($result_array[$i][10]) * 1000;
										$jackpot_win = trim($result_array[$i][12]) * 1000;
									}else{
										$bet_amount = trim($result_array[$i][9]);
										$bet_amount_valid = trim($result_array[$i][9]);
										$win_loss = (trim($result_array[$i][10]) - trim($result_array[$i][9]));
										$payout_amount = trim($result_array[$i][10]);
										$jackpot_win = trim($result_array[$i][12]);
									}
									//Response time (UTC +0)
									$PBdata = array(
										'game_provider_code' => $provider_code,
										'game_type_code' => GAME_SLOTS,
										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
										'game_result_type' => $result_type,
										'game_code' => trim($result_array[$i][2]),
										'game_real_code' => trim($result_array[$i][2]),
										'bet_id' => trim($result_array[$i][3]),
										'bet_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][5]))),
										'bet_amount' => $bet_amount,
										'bet_amount_valid' => $bet_amount_valid,
										'payout_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'sattle_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'compare_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'created_date' => time(),
										'payout_amount' => $payout_amount,
										'promotion_amount' => 0,
										'win_loss' => $win_loss,
										'jackpot_win' => $jackpot_win,
										'game_round_type' => ((trim($result_array[$i][8]) == 'F') ? GAME_ROUND_TYPE_FREE_SPIN : GAME_ROUND_TYPE_GAME_ROUND),
										'status' => ((trim($result_array[$i][7]) == 'C') ? STATUS_COMPLETE : STATUS_PENDING),
										'game_username' => trim($result_array[$i][1]),
										'player_id' => $member_lists[$exact_username],
										'bet_info' => json_encode($result_array[$i])
									);
									
									if($PBdata['jackpot_win'] > 0)
									{
										$PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
									}
									
									if($PBdata['game_round_type'] == GAME_ROUND_TYPE_GAME_ROUND){
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										}
									}


									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{					
										$PBdata['bet_info'] = json_encode($result_array[$i]);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
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
								}
							}
						}
					}

					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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

	public function pp_lc($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PP';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-40 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = strtotime('-5 minutes', $sync_data['end_time']);
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
					
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
        				'104' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '401' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '404' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '405' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '411' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '413' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '425' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '426' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '427' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '429' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '422' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '423' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '402' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '403' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '412' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '414' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '415' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '431' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '432' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '430' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '421' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '424' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
                        '107' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Sicbo'),
                        '701' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Sicbo'),
                        '102' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '225' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '102' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '203' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '102' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '227' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '201' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '221' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '222' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '223' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '224' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '206' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '229' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '545' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '230' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '105' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Money Wheel'),
                        '801' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Money Wheel'),
                        '103' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '521' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '522' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '523' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '524' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '525' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '526' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '527' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '528' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '529' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '530' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '539' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '538' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '537' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '536' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '535' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '540' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '511' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '512' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '513' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '514' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '515' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '516' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '517' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '518' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '519' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '520' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '301' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '302' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '303' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '304' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '305' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '541' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '542' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '543' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '544' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '204' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
                        '901' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '902' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
                        '108' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Dragon Tiger'),
                        '1001' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Dragon Tiger'),
                        '1024' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Andar Bahar'),
                        '1101' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Sweet Bonanza Candyland'),
                    );
					$response = $this->pp_connect($arr, $start_time,$result_type);
					if($response['code'] == '0')
					{
						$result_array = array_map("str_getcsv", explode("\n", $response['data']));
						if( ! empty($result_array))
						{
							$DBdata['next_id'] = trim(str_replace('timepoint=', '', $result_array[0][0]));
							$DBdata['sync_status'] = STATUS_YES;
							$DBdata['resp_data'] = json_encode($result_array);
							
							for($i=2;$i<sizeof($result_array);$i++)
							{
								if(sizeof($result_array[$i]) > 12)
								{
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);

									$tmp_username = strtolower(trim($result_array[$i][1]));
									$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

									if($arr['CurrencyType'] == "IDR2"){
										$bet_amount = trim($result_array[$i][9]) * 1000;
										$bet_amount_valid = trim($result_array[$i][9]) * 1000;
										$win_loss = (trim($result_array[$i][10]) - trim($result_array[$i][9])) * 1000;
										$payout_amount = trim($result_array[$i][10]) * 1000;
										$jackpot_win = trim($result_array[$i][12]) * 1000;
									}else{
										$bet_amount = trim($result_array[$i][9]);
										$bet_amount_valid = trim($result_array[$i][9]);
										$win_loss = (trim($result_array[$i][10]) - trim($result_array[$i][9]));
										$payout_amount = trim($result_array[$i][10]);
										$jackpot_win = trim($result_array[$i][12]);
									}
									//Response time (UTC +0)
									$PBdata = array(
										'game_provider_code' => $provider_code,
										'game_type_code' => GAME_LIVE_CASINO,
										'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
										'game_result_type' => $result_type,
										'game_code' => (isset($game_code_data[trim($result_array[$i][2])]) ? $game_code_data[trim($result_array[$i][2])]['game_code'] : "Other"),
										'game_real_code' => trim($result_array[$i][2]),
										'bet_id' => trim($result_array[$i][3]),
										'bet_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][5]))),
										'game_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
									    'report_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'bet_amount' => $bet_amount,
										'bet_amount_valid' => $bet_amount_valid,
										'payout_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'sattle_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'compare_time' => strtotime('+8 hours', strtotime(trim($result_array[$i][6]))),
										'created_date' => time(),
										'payout_amount' => $payout_amount,
										'promotion_amount' => 0,
										'win_loss' => $win_loss,
										'jackpot_win' => $jackpot_win,
										'game_round_type' => ((trim($result_array[$i][8]) == 'F') ? GAME_ROUND_TYPE_FREE_SPIN : GAME_ROUND_TYPE_GAME_ROUND),
										'status' => ((trim($result_array[$i][7]) == 'C') ? STATUS_COMPLETE : STATUS_PENDING),
										'game_username' => trim($result_array[$i][1]),
										'player_id' => $member_lists[$exact_username],
										'game_result' => (isset($result_array[$i][13]) ? $result_array[$i][13] : ""),
									);
									
									if($PBdata['jackpot_win'] > 0)
									{
										$PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
									}
									
									if($PBdata['game_round_type'] == GAME_ROUND_TYPE_GAME_ROUND){
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										}
									}

                                    if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{					
										$PBdata['bet_info'] = json_encode($result_array[$i]);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
									}else{
										$PBdata['bet_update_info'] = json_encode($result_array[$i]);
								        $PBdata['update_type'] = SYNC_DEFAULT;
										array_push($BUdata, $PBdata);
										array_push($BUIDdata, $PBdata['bet_id']);
									}
												
									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{
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
								}
							}
						}
					}
                    if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
    				{
    					foreach($BUdata as $BUdataRow){
    						$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
    					}
    				}
					$this->db->trans_complete();
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

	public function pt2($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PT';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();

				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->pt2_connect($arr, $start_time, $end_time, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['result']) && isset($result_array['pagination']))
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['pagination']['totalPages']);
										if(sizeof($result_array['result'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}

											foreach($result_array['result'] as $result_row){
											    $tmp_username = strtolower(str_replace($arr['PlayerName'],'',trim($result_row['PLAYERNAME'])));
											    $exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											    
											    $game_type_code_temp = strtolower(trim($result_row['GAMETYPE']));
											    $game_code = trim($result_row['GAMENAME']);
											    if(strpos($game_type_code_temp, 'slot')!== false){
											        $game_type_code = GAME_SLOTS;
											    }else{
											        $game_type_code = GAME_LIVE_CASINO;
											        $game_code_temp = strtolower($result_row['GAMENAME']);
											        if(strpos($game_code_temp, 'baccarat')!== false){
											            $game_code = "Baccarat";
											        }
											    }
											    
											    if($result_row['BONUSTYPE'] == 4){
											    	$game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
											    }else if($result_row['BONUSTYPE'] == 5){
											    	$game_round_type = GAME_ROUND_TYPE_GOLDEN_CHIP;
											    }else{
											    	$game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
											    }

											    $PBdata = array(
	    									        'game_provider_code' => $provider_code,
	    									        'game_type_code' => $game_type_code,
	    									        'game_result_type' => $result_type,
	    									        'game_provider_type_code' => $provider_code."_".$game_type_code,
	    									        'game_code' => $game_code,
	    									        'game_real_code' => trim($result_row['GAMENAME']),
	    									        'bet_id' => trim($result_row['GAMECODE']),
	    									        'bet_time' => strtotime(trim($result_row['GAMEDATE'])),
	    									        'game_time' => strtotime(trim($result_row['GAMEDATE'])),
									       			'report_time' => strtotime(trim($result_row['GAMEDATE'])),
	    									        'bet_amount' => trim($result_row['BET']),
	    									        'bet_amount_valid' => trim($result_row['BET']),
	    									        'payout_amount' => trim($result_row['WIN']),
	    									        'promotion_amount' => 0,
	    									        'payout_time' => strtotime(trim($result_row['GAMEDATE'])),
	    									        'sattle_time' => strtotime(trim($result_row['GAMEDATE'])),
        											'compare_time' => strtotime(trim($result_row['GAMEDATE'])),
        											'created_date' => time(),
	    									        'win_loss' => (trim($result_row['WIN']) - trim($result_row['BET'])),
	    									        'game_round_type' => $game_round_type,
	    									        'status' => STATUS_COMPLETE,
	    									        'game_username' => trim($result_row['PLAYERNAME']),
	    									        'game_result' => (isset($result_row['INFO']) ? $result_row['INFO'] : ""),
	    									        'player_id' => $member_lists[$exact_username],
	    									    );
											    if($PBdata['win_loss'] != 0){
										    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
												}else{
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
										}
									}
									$page_id++;
								}
							}
							
							if(!empty($BUIDdata)){
								$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
								if( ! empty($transaction_lists_old)){
									foreach($transaction_lists_old as $transaction_lists_old_row){
										if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $transaction_lists_old_row['player_id'],
												'payout_time' => $transaction_lists_old_row['payout_time'],
												'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
												'game_type_code' => $transaction_lists_old_row['game_type_code'],
												'total_bet' => -1,
												'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
												'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
												'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							if( ! empty($BUdata))
							{
								foreach($BUdata as $BUdataRow){
									$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
								}
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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

	public function rtg($member_lists = NULL){
	    set_time_limit(0);
		$member_lists = $this->player_model->get_player_list_array();
		
		set_time_limit(0);
		$provider_code = 'RTG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;

				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					

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
					$is_loop = TRUE;
					$token_response = $this->rtg_connect($arr, $start_time, $end_time, $page_id, 'RetrieveToken');
					if(isset($token_response['http_code']) && $token_response['http_code'] == "200"){
						$token_array = json_decode($token_response['data'], TRUE);
						if(isset($token_array['token'])){
	    					$token = $token_array['token'];
							while($is_loop == TRUE){
								$DBdata['sync_status'] = STATUS_NO;
								$DBdata['page_id'] = $page_id;
								$DBdata['resp_data'] = '';
								$Bdata = array();
								$BUDdata = array();
								$response = $this->rtg_connect($arr, $start_time, $end_time, $page_id, 'RetrieveRecord', $token);
								if($response['code'] == '0' && $response['http_code'] == "200")
								{
									$DBdata['sync_status'] = STATUS_YES;
									$result_array = json_decode($response['data'], TRUE);
									if(!empty($result_array))
									{
										$DBdata['resp_data'] = json_encode($result_array);
										if(isset($result_array['items']) && sizeof($result_array['items']) > 0)
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
	    										$is_retrieve = TRUE;
	    									}
											foreach($result_array['items'] as $result_row){
												$tmp_username = strtolower(trim($result_row['playerName']));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

												if($result_row['gameId'] == "2162689"){
													$game_type_code = GAME_FISHING;
												}else{
													$game_type_code = GAME_SLOTS;
												}

												$PBdata = array(
													'game_provider_code' => $provider_code,
													'game_type_code' => $game_type_code,
											        'game_provider_type_code' => $provider_code."_".$game_type_code,
											        'game_result_type' => $result_type,
													'game_code' => trim($result_row['gameName']),
													'game_real_code' => trim($result_row['gameId']),
													'bet_id' => trim($result_row['id']),
													'bet_transaction_id' => trim($result_row['gameNumber']),
													'bet_time' => strtotime(trim($result_row['gameStartDate'])),
													'bet_amount' => trim($result_row['bet']),
													'bet_amount_valid' => trim($result_row['bet']),
													'game_time' => strtotime(trim($result_row['gameDate'])),
													'report_time' => strtotime(trim($result_row['gameDate'])),
													'payout_time' => strtotime(trim($result_row['gameDate'])),
													'sattle_time' => strtotime(trim($result_row['gameDate'])),
													'compare_time' => strtotime(trim($result_row['gameDate'])),
													'created_date' => time(),
													'win_loss' =>  trim($result_row['winLossAmount']),
													'jackpot_win' => trim($result_row['jpWin']),
													'payout_amount' => trim($result_row['win']) - trim($result_row['jpWin']),
													'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
													'status' => STATUS_COMPLETE,
													'game_username' => trim($result_row['playerName']),
													'player_id' =>  $member_lists[$exact_username],
													'bet_info' => json_encode($result_row),
												);
												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
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
											}
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
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
								$page_id++;
								sleep(5);
							}
						}
					}
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
		}
		else{
			echo EXIT_ERROR;
		}
	}

	public function sa($member_lists = NULL){
		//10:00
		
		set_time_limit(0);
		$provider_code = 'SA';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
						'bac' => "Baccarat",
						'dtx' => "Dragon Tiger",
						'sicbo' => "Sicbo",
						'ftan' => "Fan Tan",
						'rot' => "Roulette",
						'slot' => "Slot",
						'minigame' => "Mini Game",
						'multiplayer' => "Multiplayer Game",
						'moneywheel' => "Money Wheel",
						'tip' => "Tips",
					);			

					$response = $this->sa_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml = simplexml_load_string($response['data']);
						$json = json_encode($xml);
						$result_array = json_decode($json, TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['BetDetailList']['BetDetail']) && sizeof($result_array['BetDetailList']['BetDetail'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
								    if(isset($result_array['BetDetailList']['BetDetail'][0])){
                    					$bet_detail_array = $result_array['BetDetailList']['BetDetail'];
                    				}else{
                    					$bet_detail_array[0] = $result_array['BetDetailList']['BetDetail'];
                    				}
									foreach($bet_detail_array as $result_row){
									    $tmp_username = strtolower(trim($result_row['Username']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
											$bet_amount = trim($result_row['BetAmount']) * 1000;
											$bet_amount_valid = trim($result_row['Rolling']) * 1000;
											$win_loss = trim($result_row['ResultAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['Rolling']);
											$win_loss = trim($result_row['ResultAmount']);
										}


									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['GameType'])]) ? $game_code_data[trim($result_row['GameType'])] : "Other"),
									        'game_real_code' => trim($result_row['GameType']),
									        'bet_id' => trim($result_row['BetID']),
									        'bet_time' => strtotime(trim($result_row['BetTime'])),
									        'game_time' => strtotime(trim($result_row['PayoutTime'])),
									        'report_time' => strtotime(trim($result_row['PayoutTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['PayoutTime'])),
									        'sattle_time' => strtotime(trim($result_row['PayoutTime'])),
											'compare_time' => strtotime(trim($result_row['PayoutTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['BetType']),
									        'game_result' => json_encode($result_row['GameResult']),
									        'table_id' => trim($result_row['GameID']),
									        'round' => trim($result_row['Round']),
									        'subround'  => trim($result_row['Set']),
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );

									     switch(trim($PBdata['game_code']))
										{
											case 'Slot': $PBdata['game_type_code'] = GAME_SLOTS; break;
											case 'Multiplayer Game': $PBdata['game_type_code'] = GAME_FISHING; break;
											default: $PBdata['game_type_code'] = GAME_LIVE_CASINO; break;
										}

										$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];

									    if($result_row['State'] == true){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sbo_sb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SBO';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_ALL;

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
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
					$odds_type = array(
						'M' => "MY",
						'H' => "HK",
						'E' => "DE",
						'I' => "ID",
					);
					$response = $this->sbo_connect($arr, $start_time, $end_time, 'SportsBook');
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['error']['id']) && ($result_array['error']['id'] == '0'))
							{
								$DBdata['sync_status'] = STATUS_YES;
	    						if(sizeof($result_array['result'])>0){
	    							$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
	    							foreach($result_array['result'] as $result_row){
	    								$tmp_status = strtolower(trim($result_row['status']));
	    								if($tmp_status == "void" || $tmp_status == "waiting Rejected" || $tmp_status == "void(suspended match)"){
											$status = STATUS_CANCEL;
										}else{
											if($tmp_status == "running" || $tmp_status == "waiting"){
												$status = STATUS_PENDING;
											}else{
												$status = STATUS_COMPLETE;
											}
										}
										
										$tmp_username = strtolower(trim($result_row['username']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
										
										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_row['stake']) * 1000;
											$bet_amount_valid = trim($result_row['actualStake']) * 1000;
											$win_loss = trim($result_row['winLost']) * 1000;
										}else{
											$bet_amount = trim($result_row['stake']);
											$bet_amount_valid = trim($result_row['actualStake']);
											$win_loss = trim($result_row['winLost']);
										}

										$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SPORTSBOOK,
    										'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['sportsType']),
    										'game_real_code' => trim($result_row['sportsType']),
    										'bet_id' => trim($result_row['refNo']),
    										'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['orderTime']))),
    										'bet_amount' => $bet_amount,
    										'bet_amount_valid' => $bet_amount_valid,
    										'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'win_loss' => $win_loss,
    										'game_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'report_time' => strtotime('+12 hours', strtotime(trim($result_row['modifyDate']))),
    										'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'created_date' => time(),
    										'payout_amount' => 0,
    										'promotion_amount' => 0,
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'odds_currency' => (isset($odds_type[trim($result_row['oddsStyle'])]) ? $odds_type[trim($result_row['oddsStyle'])] : "OT"),
    										'odds_rate' => trim($result_row['odds']),
    										'status' => $status,
    										'game_username' => trim($result_row['username']),
    										'player_id' =>  $member_lists[$exact_username],
    										'game_result' => json_encode($result_row['subBet']),
    									);

										if($status == STATUS_COMPLETE){
											if($result_row['winLost'] != 0){
												if($result_row['isHalfWonLose']){
										            $PBdata['bet_amount'] = bcdiv(($PBdata['bet_amount']/2),1,2);
										            $PBdata['bet_amount_valid'] = bcdiv(($PBdata['bet_amount_valid']/2),1,2);
										        }
										        $PBdata['payout_amount'] = $PBdata['bet_amount_valid'] + $PBdata['win_loss'];
										        $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											}
										}

										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
	    						}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sbo_vs($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SBO';
		$result_type = GAME_VIRTUAL_SPORTS;
		$sync_type = SYNC_TYPE_ALL;

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
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					
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
					$odds_type = array(
						'Malay' => "MY",
						'HK' => "HK",
						'Euro' => "DE",
						'Indo' => "ID",
					);
					$response = $this->sbo_connect($arr, $start_time, $end_time, 'VirtualSports');
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['error']['id']) && ($result_array['error']['id'] == '0'))
							{
								$DBdata['sync_status'] = STATUS_YES;
	    						if(sizeof($result_array['result'])>0){
	    							$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
	    							foreach($result_array['result'] as $result_row){
	    								$tmp_status = strtolower(trim($result_row['status']));
	    								if($tmp_status == "void" || $tmp_status == "waiting Rejected" || $tmp_status == "void(suspended match)"){
											$status = STATUS_CANCEL;
										}else{
											if($tmp_status == "running" || $tmp_status == "waiting"){
												$status = STATUS_PENDING;
											}else{
												$status = STATUS_COMPLETE;
											}
										}
										
										$tmp_username = strtolower(trim($result_row['username']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_row['stake']) * 1000;
											$bet_amount_valid = trim($result_row['actualStake']) * 1000;
											$win_loss = trim($result_row['winLost']) * 1000;
										}else{
											$bet_amount = trim($result_row['stake']);
											$bet_amount_valid = trim($result_row['actualStake']);
											$win_loss = trim($result_row['winLost']);
										}
										
										$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_VIRTUAL_SPORTS,
    										'game_provider_type_code' => $provider_code."_".GAME_VIRTUAL_SPORTS,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['productType']),
    										'game_real_code' => trim($result_row['productType']),
    										'bet_id' => trim($result_row['refNo']),
    										'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['orderTime']))),
    										'bet_amount' => $bet_amount,
    										'bet_amount_valid' => $bet_amount_valid,
    										'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'win_loss' => $win_loss,
    										'game_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'report_time' => strtotime('+12 hours', strtotime(trim($result_row['modifyDate']))),
    										'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['winLostDate']))),
    										'created_date' => time(),
    										'payout_amount' => 0,
    										'promotion_amount' => 0,
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'odds_currency' => (isset($odds_type[trim($result_row['oddsStyle'])]) ? $odds_type[trim($result_row['oddsStyle'])] : "OT"),
    										'odds_rate' => trim($result_row['odds']),
    										'status' => $status,
    										'game_username' => trim($result_row['username']),
    										'player_id' =>  $member_lists[$exact_username],
    										'game_result' => json_encode($result_row['subBet']),
    									);

										if($status == STATUS_COMPLETE){
											if($result_row['winLost'] != 0){
										        $PBdata['payout_amount'] = $PBdata['bet_amount_valid'] + $PBdata['win_loss'];
										        $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											}
										}

										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
	    						}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sbo_games($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SBO';
		$result_type = GAME_OTHERS;
		$sync_type = SYNC_TYPE_ALL;

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
				$next_id = 0;
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
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
					$response = $this->sbo_connect($arr, $start_time, $end_time, 'VirtualSports');
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['error']['id']) && ($result_array['error']['id'] == '0'))
							{
								$DBdata['sync_status'] = STATUS_YES;
	    						if(sizeof($result_array['result'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
	    							foreach($result_array['result'] as $result_row){
	    								$tmp_status = strtolower(trim($result_row['status']));
	    								if($tmp_status == "void" || $tmp_status == "waiting Rejected" || $tmp_status == "void(suspended match)"){
											$status = STATUS_CANCEL;
										}else{
											if($tmp_status == "running" || $tmp_status == "waiting"){
												$status = STATUS_PENDING;
											}else{
												$status = STATUS_COMPLETE;
											}
										}
										
										$tmp_username = strtolower(trim($result_row['username']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);


										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_row['stake']) * 1000;
											$bet_amount_valid = trim($result_row['turnover']) * 1000;
											$win_loss = trim($result_row['winLost']) * 1000;
										}else{
											$bet_amount = trim($result_row['stake']);
											$bet_amount_valid = trim($result_row['turnover']);
											$win_loss = trim($result_row['winLost']);
										}
										
										$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_OTHERS,
    										'game_provider_type_code' => $provider_code."_".GAME_OTHERS,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['gameName']),
    										'game_real_code' => trim($result_row['gameId']),
    										'bet_id' => trim($result_row['refNo']),
    										'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['orderTime']))),
    										'bet_amount' => $bet_amount,
    										'bet_amount_valid' => $bet_amount_valid,
    										'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'win_loss' => $win_loss,
    										'game_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'report_time' => strtotime('+12 hours', strtotime(trim($result_row['modifyDate']))),
    										'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['settleTime']))),
    										'created_date' => time(),
    										'payout_amount' => 0,
    										'promotion_amount' => 0,
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'status' => $status,
    										'game_username' => trim($result_row['username']),
    										'player_id' =>  $member_lists[$exact_username],
    									);

										if($status == STATUS_COMPLETE){
											if($result_row['winLost'] != 0){
										        $PBdata['payout_amount'] = $PBdata['bet_amount_valid'] + $PBdata['win_loss'];
										        $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											}
										}

										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
	    						}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->sg_connect($arr, $start_time, $end_time, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['code']) && $result_array['code'] == '0')
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['pageCount']);
										if(sizeof($result_array['list'])>0){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											foreach($result_array['list'] as $result_row){
												$tmp_username = strtolower(trim($result_row['acctId']));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											    
											     $PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameCode']),
											        'game_real_code' => trim($result_row['gameCode']),
											        'bet_id' => trim($result_row['ticketId']),
											        'bet_time' => strtotime(trim($result_row['ticketTime'])),
											        'game_time' => strtotime(trim($result_row['ticketTime'])),
									       			'report_time' => strtotime(trim($result_row['ticketTime'])),
											        'bet_amount' => trim($result_row['betAmount']),
											        'bet_amount_valid' => trim($result_row['betAmount']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime(trim($result_row['ticketTime'])),
											        'win_loss' => trim($result_row['winLoss']),
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'status' => STATUS_CANCEL,
											        'game_username' => trim($result_row['acctId']),
											        'player_id' => $member_lists[$exact_username],
											    );


											    switch(trim($result_row['categoryId']))
												{
													case 'FH': $PBdata['game_type_code'] = GAME_FISHING; break;
													case 'SM': $PBdata['game_type_code'] = GAME_SLOTS; break;
													default: $PBdata['game_type_code'] = GAME_OTHERS; break;
												}

											    
											    if($result_row['completed'] == "1"){
											    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
											    	$PBdata['status'] = STATUS_COMPLETE;
											    	if($PBdata['win_loss'] != 0){
											    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											    	}
											    	if($result_row['jpWin'] != 0){
											    		$PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
											    		$PBdata['win_loss'] += abs($result_row['jpWin']);
											    	}
											    }
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);

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
											}
										}
									}
									$page_id++;
								}
							}
							
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}

							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}

	public function sp($member_lists = NULL){
		//10:00
		
		set_time_limit(0);
		$provider_code = 'SP';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$response = $this->sp_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml = simplexml_load_string($response['data']);
						$json = json_encode($xml);
						$result_array = json_decode($json, TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['BetDetailList']['BetDetail']) && sizeof($result_array['BetDetailList']['BetDetail'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
								    if(isset($result_array['BetDetailList']['BetDetail'][0])){
                    					$bet_detail_array = $result_array['BetDetailList']['BetDetail'];
                    				}else{
                    					$bet_detail_array[0] = $result_array['BetDetailList']['BetDetail'];
                    				}
									foreach($bet_detail_array as $result_row){
									    $tmp_username = strtolower(trim($result_row['Username']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
											$bet_amount = trim($result_row['BetAmount']) * 1000;
											$bet_amount_valid = trim($result_row['BetAmount']) * 1000;
											$win_loss = trim($result_row['ResultAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['BetAmount']);
											$win_loss = trim($result_row['ResultAmount']);
										}

										if($result_row['GameType'] == "slot"){
											$game_type_code = GAME_SLOTS;
											$game_code = "Slot";
										}else if($result_row['GameType'] == "multiplayer"){
											$game_type_code = GAME_FISHING;
											$game_code = "Multiplayer Game";
										}else{
											$game_type_code = GAME_OTHERS;
											$game_code = "Others";
										}

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => $game_type_code,
									        'game_provider_type_code' => $provider_code."_".$game_type_code,
									        'game_result_type' => $result_type,
									        'game_code' => $game_code,
									        'game_real_code' => trim($result_row['GameType']),
									        'bet_id' => trim($result_row['BetID']),
									        'bet_time' => strtotime(trim($result_row['BetTime'])),
									        'game_time' => strtotime(trim($result_row['PayoutTime'])),
									        'report_time' => strtotime(trim($result_row['PayoutTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['PayoutTime'])),
									        'sattle_time' => strtotime(trim($result_row['PayoutTime'])),
											'compare_time' => strtotime(trim($result_row['PayoutTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'game_result' => json_encode($result_row['Detail']),
									        'table_id' => trim($result_row['GameID']),
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );

									    if($PBdata['bet_amount'] == 0){
									    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
									    }else{
									    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
									    }

								    	//promotion
								    	if($PBdata['win_loss'] != 0){
								    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
								    	}


									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sx($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SX';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-002' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : "Other"),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sexy_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SX';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_UPDATE;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sexy_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SX';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-002' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);
									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : "Other"),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }
									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxjl($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXJL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['gameName']),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxjl_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXJL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxjl_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXJL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['gameName']),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxrt($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXRT';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxrt_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXRT';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxrt_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXRT';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxyl($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXYL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxyl_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXYL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxyl_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXYL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxkm($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXKM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxkm_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXKM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxkm_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXKM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxbg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXBG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxbg_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXBG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxbg_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXBG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxvn($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXVN';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxvn_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXVN';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxvn_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXVN';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxes($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXES';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_INSERT;

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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "InsertRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => (isset($result_row['betType']) ? trim($result_row['betType']) : ""),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}

					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}

					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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

	public function sxes_backup($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXES';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_UPDATE;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-180 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-120 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
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
					$response = $this->sx_connect($arr, $start_time, $end_time, "SummaryRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
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

	public function sxes_secure($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SXES';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime(date('Y-m-d H:00:00', strtotime('-240 minutes', $current_time)));
				$sync_data = $this->report_model->get_game_result_success_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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

					$game_type_code_data = array(
						'SLOT' => GAME_SLOTS,
						'FH' => GAME_FISHING,
						'TABLE' => GAME_BOARD_GAME,
						'LIVE' => GAME_LIVE_CASINO,
						'EGAME' => GAME_OTHERS,
						'ESPORTS' => GAME_ESPORTS,
						'VIRTUAL' => GAME_VIRTUAL_SPORTS,
						'LOTTO' => GAME_LOTTERY,
					);

					$game_code_data = array(
						'RT-TABLE-001' => "Baccarat",
						'RT-TABLE-002' => "Roulette",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time, "UpdateRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == strtolower($arr['UPrefix'])) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : trim($result_row['gameName'])),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
									        'bet_transaction_id' => trim($result_row['platformTxId']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'sattle_time' => strtotime(trim($result_row['updateTime'])),
											'compare_time' => strtotime(trim($result_row['updateTime'])),
											'created_date' => time(),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => (int) $exact_username,
									    );
									    
									    if($result_row['txStatus'] == 1){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $result_row['winAmount'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
									    }else if($result_row['txStatus'] == 0){
									    	$PBdata['status'] = STATUS_PENDING;
									    }

									    if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);

					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}

					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_GAME_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
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
	
	public function tcg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'TCG';
		$result_type = "ELOTTO";
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-20 minutes' ,$start_time);
					$db_record_end_time = strtotime('+20 minutes' ,$end_time);

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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata =  array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->tcg_connect($arr, $start_time, $end_time, "RetrieveData", $result_type, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									$page_total = trim($result_array['page']['total']) / trim($result_array['page']['pageSize']);
									if(isset($result_array['list']) && sizeof($result_array['list'])>0){
										foreach($result_array['list'] as $result_row){

											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
	    										$is_retrieve = TRUE;
	    									}
	    									
											if($result_row['betStatus'] == "3"){
												$status = STATUS_CANCEL;
											}else{
												$status = STATUS_COMPLETE;
											}

											$tmp_username = strtolower(trim($result_row['username']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);


											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => GAME_LOTTERY,
										        'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
										        'game_result_type' => $result_type,
										        'game_code' => trim($result_row['remark']),
										        'game_real_code' => trim($result_row['gameCode']),
										        'bet_id' => trim($result_row['betOrderNo']),
												'bet_ref_no' => trim($result_row['orderNum']),
												'bet_transaction_id' => trim($result_row['betContentId']),
										        'bet_time' => strtotime(trim($result_row['betTime'])),
										        'game_time' => strtotime(trim($result_row['transTime'])),
								       			'report_time' => strtotime(trim($result_row['transTime'])),
										        'bet_amount' => trim($result_row['actualBetAmount']),
										        'bet_amount_valid' => trim($result_row['actualBetAmount']),
										        'payout_amount' => trim($result_row['netPNL']) + trim($result_row['actualBetAmount']),
										        'promotion_amount' => trim($result_row['actualBetAmount']),
										        'payout_time' => strtotime(trim($result_row['settlementTime'])),
										        'sattle_time' => strtotime(trim($result_row['settlementTime'])),
	                							'compare_time' => strtotime(trim($result_row['settlementTime'])),
	                							'created_date' => time(),
										        'win_loss' => trim($result_row['netPNL']),
										        'bet_code' => (is_array($result_row['bettingContent']) ? json_encode($result_row['bettingContent']) : trim($result_row['bettingContent'])),
								        		'game_result' => $result_row['betStatus'],
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => $status,
										        'game_username' => trim($result_row['username']),
										        'player_id' => $member_lists[$exact_username],
										    );



											if($status == STATUS_COMPLETE){
												if($PBdata['win_loss'] != 0){
													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
												}
											}else{
												$PBdata['payout_amount'] = 0;
											}

											if( ! in_array($PBdata['bet_id'], $transaction_lists))
                    						{								
                    							array_push($Bdata, $PBdata);
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
										}
									}
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);

							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$page_id++;
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
	
	public function tg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'TG';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
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
						$db_record_start_time = strtotime('-1 days' ,$start_time);
						$db_record_end_time = strtotime('+1 days' ,$end_time);

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
						'BAC' => "Baccarat",
						'BAC_LS' => "Baccarat",
						'SIC' => "Sicbo",
						'SIC_LS' => "Sicbo",
						'DTB' => "Dragon Tiger",
						'DTB_LS' => "Dragon Tiger",
						'NIU' => "Bull Bull",
						'NIU_LS' => "Bull Bull",
						'LOT_SO' => "Lottery",
						'GRE' => "Angpao",
					);	

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata =  array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->tg_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['error']['code']) && $result_array['error']['code'] == '0'){
									    $DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['pagetotal']);
										if(isset($result_array['records']) && sizeof($result_array['records'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}
											foreach($result_array['records'] as $result_row){
												$tmp_username = strtolower(trim($result_row['username']));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

												if($result_row['type'] == "1"){
													if($result_row['status'] == "1"){
														$status = STATUS_COMPLETE;
													}else if($result_row['status'] == "0"){
														$status = STATUS_COMPLETE;
													}else{
														$status = STATUS_CANCEL;
													}

													
													$PBdata = array(
												        'game_provider_code' => $provider_code,
												        'game_type_code' => GAME_LIVE_CASINO,
												        'game_provider_type_code' => $provider_code. "_".GAME_LIVE_CASINO,
												        'game_result_type' => $result_type,
												        'game_code' => (isset($game_code_data[trim($result_row['gametype'])]) ? $game_code_data[trim($result_row['gametype'])] : "Other"),
												        'game_real_code' => trim($result_row['gametype']),
												        'bet_id' => trim($result_row['ordernumber']),
												        'bet_time' => trim($result_row['createtime']),
												        'game_time' => trim($result_row['reckontime']),
										       			'report_time' => trim($result_row['reckontime']),
												        'bet_amount' => trim($result_row['betamount']),
												        'bet_amount_valid' => trim($result_row['betscore']),
												        'payout_amount' => trim($result_row['betreward']),
												        'promotion_amount' => 0,
												        'payout_time' => trim($result_row['reckontime']),
												        'sattle_time' =>  trim($result_row['reckontime']),
												        'compare_time' =>  trim($result_row['reckontime']),
												        'created_date' => time(),
												        'win_loss' => trim($result_row['betincome']),
												        'table_id' => trim($result_row['table']),
												        'bet_code' => (isset($result_row['playtype']) ? trim($result_row['playtype']) : "0"),
												        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												        'status' => $status,
												        'game_username' => trim($result_row['username']),
												        'player_id' => $member_lists[$exact_username],
												    );
												}else if($result_row['type'] == "2"){
													$PBdata = array(
												        'game_provider_code' => $provider_code,
												        'game_type_code' => GAME_OTHERS,
												        'game_provider_type_code' => $provider_code. "_".GAME_OTHERS,
												        'game_result_type' => $result_type,
												        'game_code' => "Other",
												        'game_real_code' => "Other",
												        'bet_id' => trim($result_row['ordernumber']),
												        'bet_time' => trim($result_row['createtime']),
												        'game_time' => trim($result_row['createtime']),
										       			'report_time' => trim($result_row['createtime']),
												        'bet_amount' => 0,
												        'bet_amount_valid' => 0,
												        'payout_amount' => 0,
												        'promotion_amount' => 0,
												        'payout_time' => trim($result_row['createtime']),
												        'sattle_time' =>  trim($result_row['createtime']),
												        'compare_time' =>  trim($result_row['createtime']),
												        'created_date' => time(),
												        'win_loss' => trim($result_row['consumption']),
												        'table_id' => trim($result_row['table']),
												        'bet_code' => (isset($result_row['playtype']) ? trim($result_row['playtype']) : "0"),
												        'game_round_type' => GAME_ROUND_TYPE_GAME_ACTIVITY,
												        'status' => STATUS_COMPLETE,
												        'game_username' => trim($result_row['username']),
												        'player_id' => $member_lists[$exact_username],
												    );
												}else{
													$PBdata = array(
												        'game_provider_code' => $provider_code,
												        'game_type_code' => GAME_OTHERS,
												        'game_provider_type_code' => $provider_code. "_".GAME_OTHERS,
												        'game_result_type' => $result_type,
												        'game_code' => (isset($game_code_data[trim($result_row['gametype'])]) ? $game_code_data[trim($result_row['gametype'])] : "Other"),
												        'game_real_code' => trim($result_row['gametype']),
												        'bet_id' => trim($result_row['ordernumber']),
												        'bet_time' => trim($result_row['createtime']),
												        'game_time' => trim($result_row['createtime']),
										       			'report_time' => trim($result_row['createtime']),
												        'bet_amount' => 0,
												        'bet_amount_valid' => 0,
												        'payout_amount' => 0,
												        'promotion_amount' => 0,
												        'payout_time' => trim($result_row['createtime']),
												        'sattle_time' =>  trim($result_row['createtime']),
												        'compare_time' =>  trim($result_row['createtime']),
												        'created_date' => time(),
												        'win_loss' => trim($result_row['rebate']),
												        'table_id' => trim($result_row['table']),
												        'bet_code' => (isset($result_row['playtype']) ? trim($result_row['playtype']) : "0"),
												        'game_round_type' => GAME_ROUND_TYPE_GAME_ACTIVITY,
												        'status' => STATUS_COMPLETE,
												        'game_username' => trim($result_row['username']),
												        'player_id' => $member_lists[$exact_username],
												    );
												}

												if($status == STATUS_COMPLETE){
													if($PBdata['win_loss'] != 0){
														$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
													}
												}else{
													$PBdata['payout_amount'] = 0;
												}

												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);

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
											}
										}
									}
									$page_id++;
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);

							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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

	public function via($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'VIA';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
				
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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->via_connect($arr, $start_time, $end_time, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode(strip_tags($response['data']), TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['ReturnCode']) && trim($result_array['ReturnCode']) == '1')
									{
										$page_total = trim($result_array['PageTotal']);
										$DBdata['sync_status'] = STATUS_YES;
										if(isset($result_array['ArrayInvoice']) && ! empty($result_array['ArrayInvoice']))
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
	    										$is_retrieve = TRUE;
	    									}
											foreach($result_array['ArrayInvoice'] as $result_row){
	                        					if(sizeof($result_row['ArrayInvoiceChild'])>0){
	                        						$tmp_username = strtolower(trim($result_row['memberCode']));
	                        						$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == strtolower($sys_data['system_prefix'])) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
	                        						$PBdata = array(
	                        							'game_provider_code' => $provider_code,
	                        							'game_type_code' => GAME_OTHERS,
	                        							'game_provider_type_code' => $provider_code."_".GAME_OTHERS,
	                        							'game_result_type' => $result_type,
	                        							'bet_ref_no' => trim($result_row['batchNo']),
	                        							'game_time' => strtotime(trim($result_row['showRaceDate'])),
	                        							'payout_time' => strtotime(trim($result_row['showRaceDate'])),
	                        							'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	                        							'status' => STATUS_COMPLETE,
	                        							'game_username' => trim($result_row['memberCode']),
	                        							'player_id' =>  $member_lists[$exact_username],
	                        							'bet_info' => json_encode($result_row),
	                        							'insert_type' => SYNC_DEFAULT,
	                        						);
	                        						foreach($result_row['ArrayInvoiceChild'] as $child_result_row){
	                        						    $showAmount = (double) str_replace(',', '', trim($child_result_row['showAmount']));
	                        						    $showWin = (double) str_replace(',', '', trim($child_result_row['showWin']));
	                        						    
	                        							$PBdata['game_code'] = trim($child_result_row['showType']);
	                        							$PBdata['game_real_code'] = trim($child_result_row['showType']);
	                        							$PBdata['bet_id'] = trim($child_result_row['batchNo']);
	                        							$PBdata['bet_time'] = strtotime(trim($child_result_row['recordDate']));
	                        							$PBdata['bet_amount'] = $showAmount;
	                        							$PBdata['bet_amount_valid'] = $showAmount;
	                        							$PBdata['report_time'] = strtotime(trim($child_result_row['showDivideDate']));
	                        							$PBdata['sattle_time'] = strtotime(trim($child_result_row['showDivideDate']));
	                        							$PBdata['compare_time'] = strtotime(trim($child_result_row['showDivideDate']));
	                        							$PBdata['created_date'] = time();
	                        							$PBdata['win_loss'] = $showWin;
	                        							$PBdata['payout_amount'] = $showAmount + $showWin;
	                        							if( ! in_array($PBdata['bet_id'], $transaction_lists))
	                        							{								
	                        								array_push($Bdata, $PBdata);
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
	                        						}
	                        					}else{
	                        						$tmp_username = strtolower(trim($result_row['memberCode']));
	                        						$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == strtolower($sys_data['system_prefix'])) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
	                        						$showAmount = (double) str_replace(',', '', trim($result_row['showAmount']));
	                        						$showWin = (double) str_replace(',', '', trim($result_row['showWin']));
	                        						$PBdata = array(
	                        							'game_provider_code' => $provider_code,
	                        							'game_type_code' => GAME_OTHERS,
	                        							'game_provider_type_code' => $provider_code."_".GAME_OTHERS,
	                        							'game_result_type' => $result_type,
	                        							'game_code' => trim($result_row['showType']),
	                        							'game_real_code' => trim($result_row['showType']),
	                        							'bet_id' => trim($result_row['batchNo']),
	                        							'bet_ref_no' => trim($result_row['batchNo']),
	                        							'bet_time' => strtotime(trim($result_row['recordDate'])),
	                        							'bet_amount' => $showAmount,
	                        							'bet_amount_valid' => $showAmount,
	                        							'payout_time' => strtotime(trim($result_row['showRaceDate'])),
	                        							'game_time' => strtotime(trim($result_row['showRaceDate'])),
	                        							'report_time' => strtotime(trim($result_row['showDivideDate'])),
	                        							'sattle_time' => strtotime(trim($result_row['showDivideDate'])),
														'compare_time' => strtotime(trim($result_row['showDivideDate'])),
														'created_date' => time(),
	                        							'win_loss' =>  $showWin,
	                        							'payout_amount' => $showAmount + $showWin,
	                        							'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	                        							'status' => STATUS_COMPLETE,
	                        							'game_username' => trim($result_row['memberCode']),
	                        							'player_id' =>  $member_lists[$exact_username],
	                        							'bet_info' => json_encode($result_row),
	                        							'insert_type' => SYNC_DEFAULT,
	                        						);
	                        						if( ! in_array($PBdata['bet_id'], $transaction_lists))
	                        						{								
	                        							array_push($Bdata, $PBdata);
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
	                        					}
	                        				}
										}
										$page_id++;
									}
								}
							}
							
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
	
	public function vr($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'VR';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
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
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
				
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

					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->vr_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$response_data = base64_decode($response['data']);
					        $response_plain_text = openssl_decrypt ($response_data,"AES-256-ECB",$arr['SignatureKey'],OPENSSL_RAW_DATA,"");
					        $result_array = json_decode($response_plain_text, TRUE);

					        if( ! empty($result_array))
							{
								if(isset($result_array['recordCountPerPage']))
								{
									$DBdata['resp_data'] = json_encode($result_array);
	                                if(isset($result_array['betRecords']) && sizeof($result_array['betRecords']) > 0){
	                                    $page_id++;
	                                    if($is_retrieve == FALSE){
    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
    										$is_retrieve = TRUE;
    									}
	                                    foreach($result_array['betRecords'] as $result_row){
											$tmp_username = strtolower(trim($result_row['playerName']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);


											if($result_row['state'] == "2" || $result_row['state'] == "3"){
												$status = STATUS_COMPLETE;
											}else if($result_row['state'] == "1"){
												$status = STATUS_CANCEL;
											}else{
												$status = STATUS_PENDING;
											}

											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => GAME_LOTTERY,
										        'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
										        'game_result_type' => $result_type,
										        'game_code' => trim($result_row['channelName']),
										        'game_real_code' => trim($result_row['channelId']),
										        'bet_id' => trim($result_row['serialNumber']),
										        'bet_time' => strtotime(trim($result_row['createTime'])),
										        'game_time' => strtotime(trim($result_row['createTime'])),
								       			'report_time' => strtotime(trim($result_row['createTime'])),
										        'bet_amount' => trim($result_row['cost']),
										        'bet_amount_valid' => trim($result_row['cost']),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'payout_time' => strtotime(trim($result_row['updateTime'])),
										        'sattle_time' => strtotime(trim($result_row['updateTime'])),
	                							'compare_time' => strtotime(trim($result_row['updateTime'])),
	                							'created_date' => time(),
										        'win_loss' => trim($result_row['playerPrize']) - trim($result_row['cost']),
										        'table_id' => trim($result_row['channelId']),
										        'round' => trim($result_row['issueNumber']),
										        'bet_code' => (is_array($result_row['number']) ? json_encode($result_row['number']) : trim($result_row['number'])),
								        		'game_result' => json_encode($result_row['prizeDetail']),
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => $status,
										        'game_username' => trim($result_row['playerName']),
										        'player_id' => $member_lists[$exact_username],
										    );

										    if($PBdata['win_loss'] != 0){
										    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
										    	$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    }
										    
										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
											}else{
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
	                                }else{
								    	$DBdata['sync_status'] = STATUS_YES;
	                                    $is_loop = FALSE;
	                                }
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}

						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
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

	public function wm($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'WM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

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
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
						'101' => "Baccarat",
						'102' => "Dragon Tiger",
						'103' => "Roulette",
						'104' => "Sicbo",
						'105' => "Bull Bull",
						'106' => "Three Card Poker",
						'107' => "Fan Tan",
						'108' => "Se Die",
						'110' => "Fish Prawn Crab Dice",
						'111' => "Zha Jin Hua",
						'112' => "Wenzhou Pai Gow",
						'113' => "Mahjong Tiles",
						'128' => "Andar Bahar",
					);

					$response = $this->wm_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['errorCode']) && ($result_array['errorCode'] == '0' OR $result_array['errorCode'] == '107')) {
								$DBdata['sync_status'] = STATUS_YES;
								if($result_array['errorCode'] != '107') { #NO DATA
									for($i=0;$i<sizeof($result_array['result']);$i++) {
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										//Response time (UTC +8)
										if(isset($result_array['result'][$i]['Tip']))
										{
											$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
											
											if($arr['CurrencyType'] == "IDR"){
												$bet_amount = trim($result_array['result'][$i]['Tip']) * 1000;
												$bet_amount_valid = trim($result_array['result'][$i]['Tip']) * 1000;
												$win_loss = trim($result_array['result'][$i]['winLoss']) * 1000;
												$payout_amount = trim($result_array['result'][$i]['Tip']) * 1000;
											}else{
												$bet_amount = trim($result_array['result'][$i]['Tip']);
												$bet_amount_valid = trim($result_array['result'][$i]['Tip']);
												$win_loss = trim($result_array['result'][$i]['winLoss']);
												$payout_amount = trim($result_array['result'][$i]['Tip']);
											}

											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_LIVE_CASINO,
												'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
												'game_result_type' => $result_type,
												'game_code' => (isset($game_type_code_data[trim($result_row['gid'])]) ? $game_type_code_data[trim($result_row['gid'])] : "Other"),
												'game_real_code' => trim($result_array['result'][$i]['gid']),
												'bet_id' => trim($result_array['result'][$i]['betId']),
												'bet_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'game_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'report_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount_valid,
												'payout_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'sattle_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'compare_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'created_date' => time(),
												'payout_amount' => $payout_amount,
												'promotion_amount' => 0,
												'win_loss' => $win_loss,
												'game_round_type' => GAME_ROUND_TYPE_TIP,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_array['result'][$i]['username']),
												'bet_code' => '',
												'round' => trim($result_array['result'][$i]['round']),
												'subround' => trim($result_array['result'][$i]['subround']),
												'table_id' => trim($result_array['result'][$i]['tableId']),
												'game_result' => '',
												'player_id' => $member_lists[$exact_username],
											);
										}
										else{
											$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											if($arr['CurrencyType'] == "IDR"){
												$bet_amount = trim($result_array['result'][$i]['bet']) * 1000;
												$bet_amount_valid = trim($result_array['result'][$i]['validbet']) * 1000;
												$win_loss = trim($result_array['result'][$i]['winLoss']) * 1000;
												$payout_amount = trim($result_array['result'][$i]['result']) * 1000;
											}else{
												$bet_amount = trim($result_array['result'][$i]['bet']);
												$bet_amount_valid = trim($result_array['result'][$i]['validbet']);
												$win_loss = trim($result_array['result'][$i]['winLoss']);
												$payout_amount = trim($result_array['result'][$i]['result']);
											}
										
											$PBdata = array(
												'game_provider_code' => $provider_code,
												'game_type_code' => GAME_LIVE_CASINO,
												'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
												'game_result_type' => $result_type,
												'game_code' => (isset($game_type_code_data[trim($result_row['gid'])]) ? $game_type_code_data[trim($result_row['gid'])] : "Other"),
												'game_real_code' => trim($result_array['result'][$i]['gid']),
												'bet_id' => trim($result_array['result'][$i]['betId']),
												'bet_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
												'game_time' => strtotime(trim($result_array['result'][$i]['payout_time'])),
												'report_time' => strtotime(trim($result_array['result'][$i]['payout_time'])),
												'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount_valid,
												'payout_time' => strtotime(trim($result_array['result'][$i]['settime'])),
												'sattle_time' => strtotime(trim($result_array['result'][$i]['settime'])),
												'compare_time' => strtotime(trim($result_array['result'][$i]['settime'])),
												'created_date' => time(),
												'payout_amount' => $payout_amount,
												'promotion_amount' => 0,
												'win_loss' => $win_loss,
												'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												'status' => STATUS_COMPLETE,
												'game_username' => trim($result_array['result'][$i]['user']),
												'bet_code' => trim($result_array['result'][$i]['betCode']),
												'round' => trim($result_array['result'][$i]['round']),
												'subround' => trim($result_array['result'][$i]['subround']),
												'table_id' => trim($result_array['result'][$i]['tableId']),
												'game_result' => trim($result_array['result'][$i]['gameResult']),
												'player_id' => $member_lists[$exact_username],
											);
											if($PBdata['win_loss'] != 0){
												$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											}
										}

										if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_array['result'][$i]);
											$PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);

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
									}
								}
							}
						}
					}

					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata)) {
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata)) {
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}
			else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	public function ne($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'NE';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
					$response = $this->ne_connect($arr, $start_time, $end_time);
					//Response time (UTC +0)
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['uuid']))
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']) &&  sizeof($result_array['data'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['data'] as $date_result_row){
										if(isset($date_result_row['games']) && sizeof($date_result_row['games'])>0){
											foreach($date_result_row['games'] as $games_result_row){
												if(isset($games_result_row['participants']) && sizeof($games_result_row['participants'])>0){
													foreach($games_result_row['participants'] as $player_result_row){
														if(isset($player_result_row['bets']) && sizeof($player_result_row['bets'])>0){
															foreach($player_result_row['bets'] as $result_row){
																$tmp_username = strtolower(trim($player_result_row['playerId']));
																$exact_username = $tmp_username;
																$placedOn = (substr(trim($result_row['placedOn']), 0, 19));
																$settledAt = (substr(trim($games_result_row['settledAt']), 0, 19));
															    $PBdata = array(
                											        'game_provider_code' => $provider_code,
                											        'game_type_code' => GAME_SLOTS,
                											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
                											        'game_result_type' => $result_type,
                											        'game_code' => trim($games_result_row['gameType']),
                											        'game_real_code' => trim($games_result_row['gameType']),
                											        'bet_id' => trim($result_row['transactionId']).trim($result_row['code']),
                											        'bet_time' => strtotime('+8 hours',strtotime($placedOn)),
                											        'game_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'report_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'bet_amount' => trim($result_row['stake']),
                											        'bet_amount_valid' => trim($result_row['stake']),
                											        'payout_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'sattle_time' => strtotime('+8 hours',strtotime($settledAt)),
                													'compare_time' => strtotime('+8 hours',strtotime($settledAt)),
                													'created_date' => time(),
                											        'payout_amount' => 0,
                							                    	'promotion_amount' => 0,
                											        'win_loss' => trim($result_row['payout'])-trim($result_row['stake']),
                											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                											        'bet_code' => trim($result_row['description']),
                											        'game_result' => json_encode($games_result_row['result']),
                											        'table_id' => trim($games_result_row['table']['id']),
                											        'round' => trim($games_result_row['id']),
                											        'subround'  => trim($result_row['transactionId']),
                											        'status' => STATUS_CANCEL,
                											        'game_username' => $player_result_row['playerId'],
                											        'player_id' => $member_lists[$exact_username],
                											    );
															    if(trim($games_result_row['status']) == "Resolved"){
                        									    	$PBdata['status'] = STATUS_COMPLETE;
                        									    	$PBdata['payout_amount'] = trim($result_row['payout']);
                        									    	//promotion
                        									    	if($PBdata['win_loss'] != 0){
                        									    		$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
                        									    	}
                        									    }
                        									    if($PBdata['status'] == STATUS_COMPLETE){
    																if( ! in_array($PBdata['bet_id'], $transaction_lists))
    																{	
    																	$PBdata['bet_info'] = json_encode($result_row);
    															        $PBdata['insert_type'] = SYNC_DEFAULT;
    																	array_push($Bdata, $PBdata);
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
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
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
	
	public function ne_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request UTC+0
		$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".117Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".117Z";
		$url = $arr['APIUrl'];
		$url .= "/api/gamehistory/v1/casino/games";
		$url .= "?startDate=".$start_date;
		$url .= "&endDate=".$end_date;
		$CasinoKey = $arr['CasinoKey'];
		$APIToken = $arr['APIToken'];
		$response = $this->curl_get($url, "Authorization: Basic " . base64_encode("$CasinoKey:$APIToken"));
		return $response;
	}
	
	public function ps($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'PS';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('-6 minutes', $current_time)));
				
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-10 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
					

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
					
					$DBdata['sync_status'] = STATUS_NO;
					$DBdata['page_id'] = $page_id;
					$DBdata['resp_data'] = '';
					$Bdata = array();
					$BUDdata = array();
					
					$game_type_code_data = array(
					    'BCRT' => GAME_LIVE_CASINO,
						'SLOT' => GAME_SLOTS,
						'FISH' => GAME_FISHING,
						'CARD' => GAME_BOARD_GAME,
						'ARCADE' => GAME_OTHERS,
					);
					
					$response = $this->ps_connect($arr, $start_time, $end_time);
				    if($response['code'] == '0')
					{
					    $result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
						    $DBdata['sync_status'] = STATUS_YES;
						    $DBdata['resp_data'] = json_encode($result_array);
    						if(!empty($result_array))
    						{
    							if(isset($result_array) && (sizeof($result_array) > 0)){
    								if($is_retrieve == FALSE){
    									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    									$is_retrieve = TRUE;
    								}
    								foreach($result_array as $result_row_date){
    								    if(sizeof($result_row_date)>0){
    								        foreach($result_row_date as $result_row_username => $result_row_value){
    								            $tmp_username = strtolower(trim($result_row_username));
                								$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
            									if(sizeof($result_row_date)>0){
    								                foreach($result_row_value as $result_row){
                    							    	$bet_amount = bcdiv(trim($result_row['bet']) / 100,1,2);
                    								    $bet_amount_valid = bcdiv(trim($result_row['bet']) / 100,1,2);
                    								    $payout_amount = bcdiv(trim($result_row['win']) / 100,1,2);
                    								    $winloss_amount = bcdiv((trim($result_row['win']) + trim($result_row['jp']) - trim($result_row['bet'])) / 100,1,2);
                    								    $jackpot_amount = bcdiv(trim($result_row['jp']) / 100,1,2);
                        								
                        								$game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
                        								if(!empty($jackpot_amount)){
                        								    $game_round_type = GAME_ROUND_TYPE_JACKPOT;
                        								}
                        								
                        								if($bet_amount == "0"){
                    								        $game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
                    								    }
                    								    
                        								
                        								$PBdata = array(
                        							        'game_provider_code' => $provider_code,
                        							        'game_type_code' => (isset($game_type_code_data[trim($result_row['gt'])]) ? $game_type_code_data[trim($result_row['gt'])] : GAME_OTHERS),
                        							        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['gt'])]) ? $game_type_code_data[trim($result_row['gt'])] : GAME_OTHERS),
                        							        'game_result_type' => $result_type,
                        							        'game_code' => trim($result_row['gid']),
                        							        'game_real_code' => trim($result_row['gid']),
                        							        'bet_id' => trim($result_row['sn']),
                        							        'bet_time' => strtotime(trim($result_row['s_tm'])),
                        							        'game_time' => strtotime(trim($result_row['s_tm'])),
                        							        'report_time' => strtotime(trim($result_row['s_tm'])),
                        							        'bet_amount' => $bet_amount,
                        							        'bet_amount_valid' => $bet_amount_valid,
                        							        'payout_amount' => $payout_amount,
                        							        'promotion_amount' => 0,
                        							        'payout_time' => strtotime(trim($result_row['s_tm'])),
                        							        'sattle_time' =>  strtotime(trim($result_row['s_tm'])),
                        							        'compare_time' =>  strtotime(trim($result_row['s_tm'])),
                        							        'created_date' => time(),
                        							        'win_loss' => $winloss_amount,
                        							        'jackpot_win' => $jackpot_amount,
                        							        'game_round_type' => $game_round_type,
                        							        'status' => STATUS_COMPLETE,
                        							        'game_username' => $result_row_username,
                        							        'player_id' => $member_lists[$exact_username],
                        							    );
                        								
                        								if($PBdata['win_loss'] != 0){
                        								    $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
                        								}
                        								
                        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
                    									{					
                    										if($PBdata['status'] == STATUS_COMPLETE){
                    										    $PBdata['bet_info'] = json_encode($result_row);
                        								        $PBdata['insert_type'] = SYNC_DEFAULT;
                        										array_push($Bdata, $PBdata);
                    											
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
    								                }
            									}
    								        }
    								    }
    								}
    							}
    						}
						}
					}
					$this->db->trans_start();
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	public function fc($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'FC';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-30 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+14 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				
				$currency_one = array("IDR", "VND","MMKK","KHR");
                $currency_two = array("MMK");
                $currency_three = array("MYRR");
                $currency_four = array("THBB");

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
					
					$DBdata['sync_status'] = STATUS_NO;
					$DBdata['sync_status'] = STATUS_YES;
					$DBdata['page_id'] = $page_id;
					$DBdata['resp_data'] = '';
					$Bdata = array();
					$BUDdata = array();
					
					$game_type_code_data = array(
						'2' => GAME_SLOTS,
						'1' => GAME_FISHING,
						'7' => GAME_OTHERS,
					);
					
					$response = $this->fc_connect($arr, $start_time, $end_time,SYNC_TYPE_ALL);
				    if($response['code'] == '0')
					{
					    $result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
						    $DBdata['resp_data'] = json_encode($result_array);
        			        if(isset($result_array['Result']) && $result_array['Result'] == '0'){
        						if(!empty($result_array))
        						{
        							if(isset($result_array['Records']) && (sizeof($result_array['Records']) > 0)){
        								if($is_retrieve == FALSE){
        									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
        									$is_retrieve = TRUE;
        								}
        								foreach($result_array['Records'] as $result_row){
        									$tmp_username = strtolower(trim($result_row['account']));
            								$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
            								
            								if(in_array($arr['Currency'],$currency_one)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) * 1000,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) * 1000,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) * 1000,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) * 1000,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) * 1000,1,2);
            								}else if(in_array($arr['Currency'],$currency_two)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) * 100,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) * 100,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) * 100,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) * 100,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) * 100,1,2);
            								}else if(in_array($arr['Currency'],$currency_three)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) / 100,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) / 100,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) / 100,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) / 100,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) / 100,1,2);
            								}else if(in_array($arr['Currency'],$currency_four)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) / 10,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) / 10,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) / 10,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) / 10,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) / 10,1,2);
            								}else{
            								    $bet_amount = bcdiv(trim($result_row['bet']),1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']),1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']),1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']),1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']),1,2);
            								}
            								
            								$game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
            								if(!empty($jackpot_amount)){
            								    $game_round_type = GAME_ROUND_TYPE_JACKPOT;
            								}
            								
            								if($bet_amount == "0"){
        								        $game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
        								    }
        								    
            								
            								$PBdata = array(
            							        'game_provider_code' => $provider_code,
            							        'game_type_code' => (isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_OTHERS),
            							        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_OTHERS),
            							        'game_result_type' => $result_type,
            							        'game_code' => trim($result_row['gameID']),
            							        'game_real_code' => trim($result_row['gameID']),
            							        'bet_id' => trim($result_row['recordID']),
            							        'bet_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'game_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'report_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'bet_amount' => $bet_amount,
            							        'bet_amount_valid' => $bet_amount_valid,
            							        'payout_amount' => $payout_amount,
            							        'promotion_amount' => 0,
            							        'payout_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'sattle_time' =>  strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'compare_time' =>  strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'created_date' => time(),
            							        'win_loss' => $winloss_amount,
            							        'jackpot_win' => $jackpot_amount,
            							        'game_round_type' => $game_round_type,
            							        'status' => STATUS_COMPLETE,
            							        'game_username' => trim($result_row['account']),
            							        'player_id' => $member_lists[$exact_username],
            							    );
            								
            								if($PBdata['win_loss'] != 0){
            								    $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
            								}
            								
            								if( ! in_array($PBdata['bet_id'], $transaction_lists))
        									{					
        										if($PBdata['status'] == STATUS_COMPLETE){
        										    $PBdata['bet_info'] = json_encode($result_row);
            								        $PBdata['insert_type'] = SYNC_DEFAULT;
            										array_push($Bdata, $PBdata);
        											
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
        								}
        							}
        						}
        					}
						}
					}
					$this->db->trans_start();
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	public function fc_backup($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'FC';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;

		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-240 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-30 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+14 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				
				$currency_one = array("IDR", "VND","MMKK","KHR");
                $currency_two = array("MMK");
                $currency_three = array("MYRR");
                $currency_four = array("THBB");

				if($end_time <= strtotime('-180 minutes', $current_time))
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
					
					$DBdata['sync_status'] = STATUS_NO;
					$DBdata['page_id'] = $page_id;
					$DBdata['resp_data'] = '';
					$Bdata = array();
					$BUDdata = array();
					
					$game_type_code_data = array(
						'2' => GAME_SLOTS,
						'1' => GAME_FISHING,
						'7' => GAME_OTHERS,
					);
					
					$response = $this->fc_connect($arr, $start_time, $end_time,SYNC_TYPE_MODIFIED);
				    if($response['code'] == '0')
					{
					    $result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
						    $DBdata['resp_data'] = json_encode($result_array);
        			        if(isset($result_array['Result']) && $result_array['Result'] == '0'){
        			            $DBdata['sync_status'] = STATUS_YES;
        						if(!empty($result_array))
        						{
        							if(isset($result_array['Records']) && (sizeof($result_array['Records']) > 0)){
        								if($is_retrieve == FALSE){
        									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
        									$is_retrieve = TRUE;
        								}
        								foreach($result_array['Records'] as $result_row){
        									$tmp_username = strtolower(trim($result_row['account']));
            								$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
            								
            								if(in_array($arr['Currency'],$currency_one)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) * 1000,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) * 1000,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) * 1000,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) * 1000,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) * 1000,1,2);
            								}else if(in_array($arr['Currency'],$currency_two)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) * 100,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) * 100,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) * 100,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) * 100,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) * 100,1,2);
            								}else if(in_array($arr['Currency'],$currency_three)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) / 100,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) / 100,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) / 100,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) / 100,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) / 100,1,2);
            								}else if(in_array($arr['Currency'],$currency_four)){
            								    $bet_amount = bcdiv(trim($result_row['bet']) / 10,1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']) / 10,1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']) / 10,1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']) / 10,1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']) / 10,1,2);
            								}else{
            								    $bet_amount = bcdiv(trim($result_row['bet']),1,2);
            								    $bet_amount_valid = bcdiv(trim($result_row['bet']),1,2);
            								    $payout_amount = bcdiv(trim($result_row['prize']),1,2);
            								    $winloss_amount = bcdiv(trim($result_row['winlose']) + trim($result_row['jppoints']),1,2);
            								    $jackpot_amount = bcdiv(trim($result_row['jppoints']),1,2);
            								}
            								
            								$game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
            								if(!empty($jackpot_amount)){
            								    $game_round_type = GAME_ROUND_TYPE_JACKPOT;
            								}
            								
            								if($bet_amount == "0"){
        								        $game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
        								    }
        								    
            								
            								$PBdata = array(
            							        'game_provider_code' => $provider_code,
            							        'game_type_code' => (isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_OTHERS),
            							        'game_provider_type_code' => $provider_code . '_' . (isset($game_type_code_data[trim($result_row['gametype'])]) ? $game_type_code_data[trim($result_row['gametype'])] : GAME_OTHERS),
            							        'game_result_type' => $result_type,
            							        'game_code' => trim($result_row['gameID']),
            							        'game_real_code' => trim($result_row['gameID']),
            							        'bet_id' => trim($result_row['recordID']),
            							        'bet_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'game_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'report_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'bet_amount' => $bet_amount,
            							        'bet_amount_valid' => $bet_amount_valid,
            							        'payout_amount' => $payout_amount,
            							        'promotion_amount' => 0,
            							        'payout_time' => strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'sattle_time' =>  strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'compare_time' =>  strtotime($arr['OffMinR'].' minutes', strtotime(trim($result_row['bdate']))),
            							        'created_date' => time(),
            							        'win_loss' => $winloss_amount,
            							        'jackpot_win' => $jackpot_amount,
            							        'game_round_type' => $game_round_type,
            							        'status' => STATUS_COMPLETE,
            							        'game_username' => trim($result_row['account']),
            							        'player_id' => $member_lists[$exact_username],
            							    );
            								
            								if($PBdata['win_loss'] != 0){
            								    $PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
            								}
            								
            								if( ! in_array($PBdata['bet_id'], $transaction_lists))
        									{					
        										if($PBdata['status'] == STATUS_COMPLETE){
        										    $PBdata['bet_info'] = json_encode($result_row);
            								        $PBdata['insert_type'] = SYNC_DEFAULT;
            										array_push($Bdata, $PBdata);
        											
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
        								}
        							}
        						}
        					}
						}
					}
					$this->db->trans_start();
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	private function fc_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
		if($type == SYNC_TYPE_ALL){
		    $url = $arr['APIUrl'].'/GetRecordList';
		}else{
		    $url = $arr['APIUrl'].'/GetHistoryRecordList';
		}
		
	    $param_array = array(
	        'AgentCode' => $arr['AgentCode'],
	        'Currency' => $arr['Currency'],
	        'Params' => "",
	        'Sign' => "",
	    );
	    
	    $content_array = array(
	        'StartDate' => date('Y-m-d H:i:s', strtotime($arr['OffMin'].' minutes', $start_time)),
	        'EndDate' => date('Y-m-d H:i:s', strtotime($arr['OffMin'].' minutes', $end_time)),
	    );
	    
	    $sign = md5(json_encode($content_array,true));
	    $param_array['Sign'] = $sign;
	    $aes = openssl_encrypt(json_encode($content_array,true), 'AES-128-ECB', $arr['AgentKey'], OPENSSL_RAW_DATA);
        $params = base64_encode($aes);
        $param_array['Params'] = $params;
        $response = $this->curl_json($url, $param_array);
        return $response;
	}
	
	private function ps_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "/feed/gamehistory";
	    
	    $start_date = date('Y-m-d', strtotime('-0 hours', $start_time))."T".date('H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d', strtotime('-0 hours', $end_time))."T".date('H:i:s', strtotime('-0 hours', $end_time));
		
	    $param_array = array(
	        'host_id' =>  $arr['HostID'],
	        'start_dtm' => $start_date,
	        'end_dtm' => $end_date,
	        'host_type' => 0,
	        'detail_type' => 1,
	    );
	    $url .= "?".urldecode(http_build_query($param_array));
    	$response = $this->curl_get($url);
		return $response;
	}

	private function ab_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//GMT-8
		$url = $arr['APIUrl'];
		$url .= '/PagingQueryBetRecords';
        $path = '/PagingQueryBetRecords';
        $request_time = gmdate('D, j M Y H:i:s e');
        $start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
		$param_array = array(
			"agent" => $arr['AgentId'],
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"pageNum" => $page_id,
			"pageSize" => 1000,
		);
		$md5_content =  base64_encode(pack('H*', md5(json_encode($param_array,true))));
		$string_sign = "POST" . "\n" . $md5_content . "\n" . "application/json" . "\n" . $request_time . "\n" . $path;
        $des_key = base64_decode($arr['APIKey']);
        $hash_hmac = hash_hmac("sha1", $string_sign, $des_key, true);
        $encrypted = base64_encode($hash_hmac);
        $authorization = "AB" . " " . $arr['PropertyID'] . ":" . $encrypted;
		$header = array(
            "authorization" => $authorization,
            "date" => $request_time,
            "content" => $md5_content,
        );
        $response = $this->curl_post_for_allbet($url, $param_array,$header);
		return $response;
	}

	private function bbin_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL, $type = NULL, $sub_game = NULL){
		$url = $arr['APIUrl'];
		$param_array = array(
			"website" => $arr['Website']
		);



		$round_date = date('Y-m-d', strtotime('-12 hours', $start_time));
		$start_date = date('H:i:s', strtotime('-12 hours', $start_time));
		$end_date = date('H:i:s', strtotime('-12 hours', $end_time));
		$current_date = date('Ymd', strtotime('-12 hours', time()));

		$param_array['uppername'] = $arr['Account'];
		$param_array['starttime'] = $start_date;
		$param_array['endtime'] = $end_date;

		if($type == GAME_LIVE_CASINO){
			$url .= '/app/WebService/JSON/display.php/BetRecord';
			$param_array['rounddate'] = $round_date;
			$param_array['gamekind'] = 3;
			$param_array['pagelimit'] = 500;
		}else if($type == GAME_SLOTS){
			$url .= '/app/WebService/JSON/display.php/WagersRecordBy5';
			$param_array['date'] = $round_date;
			$param_array['action'] = "ModifiedTime";
			$param_array['subgamekind'] = $sub_game;
			$param_array['pagelimit'] = 1000;
		}
		$param_array['page'] = $page_id;
		$param_array['key'] = rand(pow(10, $arr['WagersRecordKeyA']-1), pow(10, $arr['WagersRecordKeyA'])-1) . md5($arr['Website'] . $arr['WagersRecordKeyB'] . $current_date) . rand(pow(10, $arr['WagersRecordKeyC']-1), pow(10, $arr['WagersRecordKeyC'])-1);
		$response = $this->curl_post($url, $param_array);
		return $response;
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
        $response = $this->curl_json($url, $param_array);
        return $response;
	}

	private function cmd_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL){
		//Game provider time (UTC +8)
		$start_date = date('YmdHis', $start_time);
		$end_date = date('YmdHis', $end_time);
		$param_array = array(
			'Method' => "betrecord",
			'PartnerKey' => $arr['PartnerKey'],
			'Version' => $next_id,
		);
		$param = "?Method=betrecord&PartnerKey=".$arr['PartnerKey']."&Version=".$next_id;
		$url = $arr['APIUrl'].$param;
		//Get response from curl
		$response = $this->curl_get($url);
		return $response;
	}

	private function cq9_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		$start_date = date('Y-m-d', strtotime('-12 hours', $start_time))."T".date('H:i:s', strtotime('-12 hours', $start_time))."-04:00";
		$end_date = date('Y-m-d', strtotime('-12 hours', $end_time))."T".date('H:i:s', strtotime('-12 hours', $end_time))."-04:00";
		$param_array = array(
			'starttime' => $start_date,
			'endtime' => $end_date,
			'page' => $page_id,
			'pagesize' => 500,
		);
		$url = $arr['APIUrl'] . "/gameboy/order/view?starttime=".$param_array['starttime']."&endtime=".$param_array['endtime']."&page=".$param_array['page']."&pagesize=".$param_array['pagesize'];
		$response = $this->curl_get($url,"Authorization: ".$arr['Key']);
		return $response;
	}

	private function dg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL,$next_id = NULL){
		$url = $arr['APIUrl'];
		$random = rand(100000, 999999);
		$key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
		$param_array = array(
			'token' => $key,
			'random' => $random,
		);
		if($method == "RetrieveRecord"){
			$url .= '/game/getReport/' . $arr['AgentName'];
		}else if($method == "SubmitRecord"){
			$param_array['list'] = $next_id;
			$url .= '/game/markReport/' . $arr['AgentName'];
		}else{
			$url = "";
		}
		$response = $this->curl_json($url, $param_array);
		return $response;
	}

	private function dt_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//Request Response gmt+8
		$url = $arr['APIUrl'];
		$start_date = date('Y-m-d H:i:s', strtotime('+0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('+0 hours', $end_time));
		$param_array = array(
			'METHOD' => "GETBETDETAIL",
			'BUSINESS' => $arr['BusinessCode'],
			'START_TIME' => $start_date,
			'END_TIME' => $end_date,
			'PAGENUMBER' => $page_id,
			'PAGESIZE' => 500,
			'REWARD_TYPE' => 2,
		);
		$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['START_TIME'] . $param_array['END_TIME'] . $arr['APIKey']);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	private function ea_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
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
		return $response;
	}

	private function eb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//request gmt8 response timestamp
		include_once(APPPATH . 'third_party/phpseclib/Crypt/RSA.php');
		$rsa = new Crypt_RSA(); 
		$rsa->loadKey($arr['PrivateKey']);
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
		$rsa->setHash("md5");

		$start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));

		$url = $arr['APIUrl'];
		$url .= 'userbethistory';
		$timestamp = time();
    	$signature = base64_encode($rsa->sign($timestamp));
		$param_array = array(
			'channelId' => $arr['ChannelID'],
			'timestamp' => $timestamp,
			'signature' => $signature,
			'currency' => $arr['Currency'],
			'startTimeStr' => $start_date,
			'endTimeStr' => $end_date,
			'pageNum' => $page_id,
			'pageSize' => 500,
			'betStatus' => 1,
		);
		$response = $this->curl_json($url, $param_array);
		return $response;
	}

	private function evo_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request UTC+0
		$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".117Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".117Z";
        
		$url = $arr['ReportUrl'];
		$url .= "/api/gamehistory/v1/casino/games";
		$url .= "?startDate=".$start_date;
		$url .= "&endDate=".$end_date;
		$CasinoKey = $arr['CasinoKey'];
		$APIToken = $arr['APIToken'];
		$response = $this->curl_get($url, "Authorization: Basic " . base64_encode("$CasinoKey:$APIToken"));
		return $response;
	}

	private function evoplay_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		$url = $arr['APIUrl'] . '/Game/getRoundsInfoByPeriod?';
		
		//Game provider time (UTC +0)
		$start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
		
		$param_array = array(
			'project' => $arr['Project'],
	        'version' => $arr['Version'],
			'start_time' => $start_date,
			'end_time' => $end_date,
			'page' => $page_id,
			'page_size' => 500,
			'signature' => $arr['Signature'],
		);
		
		//Get response from curl
		foreach ($param_array as $key => $value){
            $hash_array[$key] = is_array($value) ? implode(":", $value) : $value;
	    }
	    $param_array['signature'] = md5(implode('*', $hash_array));
		$response = $this->curl_json($url, $param_array);
		return $response;
	}

	private function hb_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$url = $arr['APIUrl'] . '/GetBrandCompletedGameResultsV2';
		
		//Game provider time (UTC +0)
		$start_date = date('YmdHis', strtotime('-8 hours', $start_time));
		$end_date = date('YmdHis', strtotime('-8 hours', $end_time));
		
		$param_array = array(
			'BrandId' => $arr['BrandId'],
			'APIKey' => $arr['APIKey'],
			'DtStartUTC' => $start_date,
			'DtEndUTC' => $end_date
		);
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		
		return $response;
	}

	private function ibc_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$next_id = NULL){
		$url = $arr['APIUrl'];
		$url .= '/GetBetDetail';
		//Response time (GMT -4)
		$param_array = array(
			'vendor_id' => $arr['VendorID'],
			'version_key' => $next_id,
		);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}

	public function icg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL,$token = NULL){
		$url = $arr['APIUrl'];
		$url .= '/api/v1/profile/rounds';
		//Game provider time (UTC +0)
		$start_date = strtotime('-0 hours', $start_time).'000';
		$end_date = strtotime('-0 hours', $end_time).'000';
		$param_array = array(
			'pageSize' => 500,
			'start' => $start_date,
			'end' => $end_date,
			'page' => $page_id,
			'isChildren' => true,
		);
		$url .= '?isChildren=true&lang=en&pageSize=500&updatedStart='.$start_date.'&updatedEnd='.$end_date.'&page='.$page_id;
		$response = $this->curl_get($url, "Authorization: Bearer " . $token);
		return $response;
	}

	public function icg_connect_key($arr = NULL){
		$url = $arr['APIUrl'];
		$url .= '/login';
		//Game provider time (UTC +0)
		$param_array = array(
			'username' => $arr['Username'],
			'password' => $arr['Password']
		);
		$response = $this->curl_json($url, $param_array);
		return $response;
	}

	private function jdb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $action = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$url = $arr['APIUrl'];
		$OffTime = ((isset($arr['OffTime'])) ? $arr['OffTime'] : 0);
		$start_date = date('d-m-Y H:i:s', $start_time + $OffTime);
		$end_date = date('d-m-Y H:i:s', $end_time + $OffTime);
		$param_array = array(
			'action' => $action,
			'ts' => $timestamp,
			'parent' => $arr['Parent'],
			'starttime' => $start_date,
			'endtime' => $end_date,
		);
		$str = json_encode($param_array);
		$params['x'] = $aes->encrypt($str);
		$params['dc'] = $arr['DC'];
		$response = $this->curl_post($url, $params);
		return $response;
	}

	private function jk_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL){
		$start_date = date('Y-m-d H:i', $start_time);
		$end_date = date('Y-m-d H:i', $end_time);
		
		$param_array = array(
			'EndDate' => $end_date,
			'Method' => 'TSM',
			'NextId' => $next_id,
			'StartDate' => $start_date,
			'Timestamp' => time()
		);
		
		$signature = base64_encode(hash_hmac('sha1', urldecode(http_build_query($param_array, '', '&')), $arr['Secret'], TRUE));
		$signature = urlencode($signature);
		$url = $arr['APIUrl'] . '?AppID=' . $arr['AppID'] . '&Signature=' . $signature;
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		
		return $response;
	}
    
    private function ky_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$start_date = str_pad($start_time, 13, 0);
		$end_date = str_pad($end_time, 13, 0);
		$orderid = $aes->getOrderId($arr['Agent']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();

		$str = 's=9&startTime=' . $start_date . '&endTime='.$end_date;
		$param = urlencode($aes->encrypt($str));
		$param_array = array(
			"agent" => $arr['Agent'],
			"timestamp" => $timestamp,
			"param" => $param,
			"key" => md5($arr['Agent'] . $timestamp . $arr['Md5key'])
		);
		$url = $arr['ReportUrl'] . '?' . urldecode(http_build_query($param_array));
		$response = $this->curl_get($url);
		return $response;
	}
	
	private function le_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$start_date = str_pad($start_time, 13, 0);
		$end_date = str_pad($end_time, 13, 0);
		$orderid = $aes->getOrderId($arr['Agent']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();

		$str = 's=9&startTime=' . $start_date . '&endTime='.$end_date;
		$param = urlencode($aes->encrypt($str));
		$param_array = array(
			"agent" => $arr['Agent'],
			"timestamp" => $timestamp,
			"param" => $param,
			"key" => md5($arr['Agent'] . $timestamp . $arr['Md5key'])
		);
		$url = $arr['ReportUrl'] . '?' . urldecode(http_build_query($param_array));
		$response = $this->curl_get($url);
		return $response;
	}
	
	private function lh_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//request utc+0
		$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time))."Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time))."Z";		

		$url = $arr['APIUrl'];
		$url .= '/api/v2/bet-transaction/?id=&LoginName=&bet_type=&from_datetime=&to_datetime=&from_settlement_datetime=&to_settlement_datetime=&settlement_status=&from_modified_datetime='.$start_date.'&to_modified_datetime='.$end_date.'&page='.$page_id.'&page_size=1000';
		$response = $this->curl_get($url, "Authorization: Token " . $arr['PrivateToken']);
		return $response;
	}

	private function mg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL,$next_id = NULL,$token = NULL){
		if($method == "RetrieveRecord"){
			$url = $arr['APIUrl'];
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/bets?limit=1000';
			if(!empty($next_id)){
				$url .= "&startingAfter=".$next_id;
			}
			$response = $this->curl_get($url, "Authorization: Bearer " . $token);
		}else{
			$url = $arr['STSUrl'] . '/connect/token';
			$param_array = array(
				'client_id' => $arr['AgentCode'],
				'client_secret' => $arr['SecretKey'],
				'grant_type' => 'client_credentials'
			);
			$response = $this->curl_post($url, $param_array);
		}
		return $response;
	}

	private function n2_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
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
		$xml .= '<element>';
		$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
		$xml .= '<properties name="merchantpasscode">' . $arr['MerchantPassword'] . '</properties>';
		$xml .= '<properties name="startdate">'.$start_date.'</properties>';
		$xml .= '<properties name="enddate">'.$end_date.'</properties>';
		$xml .= '<properties name="timezone">' . $arr['TimeZone'] . '</properties>';
		$xml .= '</element>';
		$xml .= '</request>';
		$response = $this->curl_xml_n2($url, $xml,'Accept-Encoding: gzip, deflate, br');
		return $response;
	}
	
	private function ninek_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "/api/".$arr['ApiToken']."/BetList";
		$param_array = array(
			"StartTime" => date('Y-m-d', $start_time)."T".date('H:i:s', $start_time),
			"EndTime" => date('Y-m-d', $end_time)."T".date('H:i:s', $end_time),
			"BossID" => $arr['BossID'],
			"Page" => $page_id,
		);
		ad($param_array);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	private function obsb_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL){
	    if($method == "ORDER"){
			$url = $arr['APIUrl'];
			$url .= "/api_func.php?request=get_has_finished_order_detail";
			
			$param_array = array(
				"agent_userid" => $arr['AgentID'],
    			"sfinished_datetime" => date("Y-m-d H:i:s",$start_time),
    			"efinished_datetime" => date("Y-m-d H:i:s",$end_time),
			);
			$response = $this->curl_post($url, $param_array);
		}else{
			$url = $arr['APIUrl'];
			$url .= "/api_func.php?request=get_history_has_finished_order_detail";
			
			$param_array = array(
				"agent_userid" => $arr['AgentID'],
    			"sfinished_datetime" => date("Y-m-d H:i:s",$start_time),
    			"efinished_datetime" => date("Y-m-d H:i:s",$end_time),
			);
			$response = $this->curl_post($url, $param_array);
		}
		return $response;
	}
	
	private function og_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$provider = NULL){
	    $url = $arr['ReportUrl'];
	    $url .= "/transaction";
	    $param_array = array(
    		'Operator' => $arr['Operator'],
            'Key' =>$arr['Key'],
            'SDate' => date('Y-m-d H:i:s',$start_time),
            'EDate' => date('Y-m-d H:i:s',$end_time),
            'Provider' => $provider,
    	);
    	$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	private function pgs2_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
	    $this->load->library('guid');
        $guid = $this->guid->get_token();
        
		$url = $arr['ReportUrl'];
		$url .= 'Bet/v4/GetHistoryForSpecificTimeRange?trace_id='.$guid;
		
		$param_array = array(
            "operator_token" => $arr['OperatorToken'],
            "secret_key" => $arr['SecretKey'],
            "count" => 5000,
            "bet_type" => 1,
            "from_time" => $start_time."000",
            'to_time' => $end_time."000"
        );
        
        $response = $this->curl_post($url, $param_array);
        
		return $response;
	}

	private function pgsoft_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL, $next_id = NULL){
		$url = $arr['ReportUrl'];
		$signature =  strtoupper(md5($arr['OperatorCode'] . $arr['SecretKey']));
		if($method == "RetrieveRecord"){
			$url .= '/fetchbykey.aspx';
			$url .= '?operatorcode='.$arr['OperatorCode']."&versionkey=".$next_id."&signature=".$signature;
			$response = $this->curl_get($url);
		}else{
			$url .= '/markbyjson.aspx';
			$param_array = array(
				'operatorcode' => $arr['OperatorCode'],
				'ticket' => implode(',',$next_id),
				'signature' => $signature,
			);
			$response = $this->curl_json($url,$param_array);
		}
		return $response;
	}

	private function pp_connect($arr = NULL, $start_time = NULL,$type = NULL){
		$param_array = array(
			'login' => $arr['secureLogin'],
			'password' => $arr['hash'],
			'timepoint' => $start_time . '000'
		);

		if($type == GAME_LIVE_CASINO){
			$param_array['dataType'] = "LC";
			$param_array['options'] = "addRoundDetails";
		}
		
		$url = $arr['ReportUrl'] . '/DataFeeds/gamerounds/finished/?' . http_build_query($param_array);
		
		//Get response from curl
		$response = $this->curl_get($url);
		
		return $response;
	}

	public function pt2_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id){
		$url = $arr['APIUrl'];
		$url .= 'game/flow/';
		$start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
		$param_array = array(
			'showdetailedinfo' => 1,
			'showbonustype' => 1,
			'excludezero' => 0,
			'progressiveonly' => 0,
			'startdate' => $start_date,
			'enddate' => $end_date,
			'page' => $page_id,
			'perPage' => 500,
		);
		$response = $this->curl_post_for_pt2($url, $param_array, $arr['EntityKeys']);
		return $response;
	}

	private function rtg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL, $method = NULL,$token = NULL){
		if($method == "RetrieveRecord"){
			$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".000Z";
			$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".000Z";
			$url = $arr['APIUrl'];
			$param_array = array(
		        "params" => array(
		        	"agentId" => $arr['agentId'],
		        	"fromDate" => $start_date, 
		        	"toDate" => $end_date,
		        ),
		        "pageIndex" => $page_id,
		        "pageSize" => 1000,
		        "language" => "en",
	        );
	        $url .= "/api/report/playergame";
	        $response = $this->curl_json($url,$param_array,"Authorization: ".$token);
		}else{
			$url = $arr['APIUrl'];
			$param_array = array(
		        "username" => $arr['Username'],
		        "password" => $arr['Password'],
		    );
		    $url .= "/api/start/token?" . http_build_query($param_array);
	   		$response = $this->curl_get($url);
		}
		return $response;
	}

	private function sa_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request GMT+8
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
		$str = '';
		$str = "method=GetAllBetDetailsForTimeIntervalDV&Key=".$arr['SecretKey']."&Time=".$current_time."&FromTime=".$start_date."&ToTime=".$end_date;

		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		
		$param_array = array(
			'q' => $q,
			's' => $s
		);
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		return $response;
	}

	private function sbo_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$Portfolio = NULL){
		//request gmt-4
		$url = $arr['APIUrl'];
		$start_date = date('Y-m-d\TH:i:s', strtotime('-12 hours', $start_time));
		$end_date = date('Y-m-d\TH:i:s', strtotime('-12 hours', $end_time));
		$param_array = array(
			'Portfolio' => $Portfolio,
			'StartDate' => $start_date,
			'EndDate' => $end_date,
			'CompanyKey' => $arr['CompanyKey'],
			'ServerId' => $arr['ServerId'],
			'Agent' => $arr['Agent'],
		);
		$url .= '/web-root/restricted/report/v2/get-bet-list-by-modify-date.aspx';
		$response = $this->curl_json($url, $param_array);
		return $response;
	}

	private function sp_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request GMT+8
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
		$str = '';
		$str = "method=GetAllBetDetailsForTimeInterval&Key=".$arr['SecretKey']."&Time=".$current_time."&FromTime=".$start_date."&ToTime=".$end_date;

		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		
		$param_array = array(
			'q' => $q,
			's' => $s
		);
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		return $response;
	}

	private function sg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//Game provider time (UTC +8)
		$url = $arr['APIUrl'];
		
		$start_date = date('Ymd', strtotime('-0 hours', $start_time))."T".date('His', strtotime('-0 hours', $start_time));
		$end_date = date('Ymd', strtotime('-0 hours', $end_time))."T".date('His', strtotime('-0 hours', $end_time));
		$param_array = array(
			'beginDate' => $start_date,
			'endDate' => $end_date,
			'pageIndex' => $page_id,
			"serialNo" => date('YmdHis') . rand(100000, 999999),
			"merchantCode" => $arr['MerchantCode']
		);
		
		$pd = json_encode($param_array); 
		$d 	= md5($pd.$arr['key']);			
		$interface 	= 'getBetHistory';			
		$token 		= $d;
		#$token = 'API: getBetHistory';
		
		#$response = $this->curl_json($url, $param_array, $token);
		$response = $this->curl_json_sg($url, $param_array, $token, $interface);
		return $response;
	}

	private function sx_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
		$url = $arr['ReportUrl'];
		$start_date = date('Y-m-d', strtotime('+0 hours', $start_time))."T".date('H:i:s', strtotime('+0 hours', $start_time))."+08:00";
		$end_date = date('Y-m-d', strtotime('+0 hours', $end_time))."T".date('H:i:s', strtotime('+0 hours', $end_time))."+08:00";
		//Request time follow server time +08:00
		if($type == "InsertRecord"){
			$url .= '/fetch/gzip/getTransactionByUpdateDate';
			$param_array = array(
				'cert' => $arr['Cert'],
				'agentId' => $arr['agentId'],
				'platform'  => $arr['Platform'],
				"timeFrom"	=> $start_date,
			);
		}else if($type == "UpdateRecord"){
			$url .= '/fetch/gzip/getTransactionByTxTime';
			$param_array = array(
				'cert' => $arr['Cert'],
				'agentId' => $arr['agentId'],
				'platform'  => $arr['Platform'],
				"startTime"	=> $start_date,		
				"endTime"	=> $end_date,
			);
		}else{
			$param_array = array(
				'cert' => $arr['Cert'],
				'agentId' => $arr['agentId'],
				'platform'  => $arr['Platform'],
				"startTime"	=> $start_date,		
				"endTime"	=> $end_date,
			);
			$url .= '/fetch/gzip/getSummaryByTxTimeHour';
		}
		$response = $this->curl_post($url, $param_array,null,"gzip");
		return $response;
	}
	
	private function tcg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $method = NULL, $type = NULL, $page_id = NULL){
	    if($method == "RetrieveData"){
	        $response = array(
    	        'code' => EXIT_ERROR,
    	    );
    	    $file = date('YmdHi',$start_time)."_".str_pad($page_id, 4, '0', STR_PAD_LEFT).".json";
    	    $path = "/".$type."/SETTLED/".date('Ymd',$start_time)."/".$file;
    	    $target = "./uploads/TCG/".$file;
    	    
	        $this->load->library('ftp');
            $config['hostname'] = $arr['ReportHost'];
            $config['username'] = $arr['HostUsername'];
            $config['password'] = $arr['HostPassword'];
            $config['debug']    = TRUE;
            $connected = $this->ftp->connect($config);
            if($connected){
                $files = $this->ftp->list_files($path);
                if(!empty($files)){
                    $download = $this->ftp->download($path, $target, 'ascii');
                    if($download){
                        $response['code'] = EXIT_SUCCESS;
                        $response['data'] = file_get_contents($target);
                        unlink($target);
                    }
                }
            }
	    }
	    return $response;
	}
	
	private function tg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "/api/QueryBets.do";
	    
		$param_array = array(
			'platform_id' => $arr['PlatformID'],
			'uuid' => $arr['PlatformID'] . time() .rand(100000000000000, 999999999999999).rand(100000000000000, 999999999999999).rand(100000000000000, 999999999999999),
			'begintime' => $start_time,
			'endtime' => $end_time,
			'pagesize' => 1000,
		    'pageindex'	=> $page_id,
		);
    
        $param_array['key'] = md5($param_array['begintime'].$param_array['endtime'].$param_array['pagesize'].$param_array['pageindex'].$arr['APIKey']);
        $response = $this->curl_post($url, $param_array);
		return $response;
	}

	private function via_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['PartnerKey']);
		$aes->require_pkcs5();	
		$start_date = date('Y-m-d',$start_time);
		$end_date = date('Y-m-d',$end_time);

		$url = $arr['APIUrl'];
		$hash = '|' . $start_date . '|' . $end_date . '|' . $page_id . '|1000';
		$url .=  "/index.php?m=api&c=index&a=ShowBetList&Hash=" . urlencode($aes->encrypt($hash)) . '&PartnerCode=' . $arr['PartnerCode'];
		$response = $this->curl_get($url);
		return $response;
	}
	
	private function vr_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "/MerchantQuery/Bet";
	    
		$param_array = array(
			'version' => $arr['Version'],
			'id' => $arr['MerchantID'],
			'startTime' => gmdate("Y-m-d\TH:i:s\Z", $start_time),
			'endTime' => gmdate("Y-m-d\TH:i:s\Z", $end_time),
			'channelId' => -1,
			'isUpdateTime' => true,
			'state' => -1,
			'betNumberFormat' => 0,
			'recordPage' => $page_id,
			'recordCountPerPage' => 1000,
		);

		$signature_array = $param_array;
		unset($signature_array['version']);
		unset($signature_array['id']);
		$string_signature = openssl_encrypt(json_encode($signature_array),"AES-256-ECB",$arr['SignatureKey'],OPENSSL_RAW_DATA,"");
		$signature = base64_encode($string_signature);
		$param_array['data'] = $signature;
		$response = $this->curl_post($url, $param_array);
		return $response;
	}

	private function wm_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$url = $arr['APIUrl'];
		//Game provider time (UTC +8)
		$start_date = date('YmdHis', strtotime('-0 hours', $start_time));
		$end_date = date('YmdHis', strtotime('-0 hours', $end_time));
		
		$param_array = array(
			'cmd' => 'GetDateTimeReport',
			'vendorId' =>  $arr['VendorId'],
			'signature' =>  $arr['Signature'],
			'startTime' => $start_date,
			'endTime' => $end_date,
			'syslang' => 1,
			'timetype' => 1,
			'datatype' => 2,
		);
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	public function clear_session_cache(){
		$backoffice_username = json_decode(OFFICE_USERNAME,true);
		$result = array();
		$this->db->select('login_token');
		$this->db->where_in('username',$backoffice_username);
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$result[] = $result_row['login_token'];
			} 
		}
		$query->free_result();
		$this->db->select('login_token');
		$this->db->where_in('username',$backoffice_username);
		$query = $this->db->get('sub_accounts');
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$result[] = $result_row['login_token'];
			} 
		}
		$query->free_result();
		$target_time = time()-CLEAR_SESSION_INTERVAL;
		$this->db->where('timestamp < ', $target_time);
		$this->db->where_not_in('id', $result);
		$this->db->delete('sessions');
	}
	
	public function spsb_bet($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-20 minutes', $current_time);
				$next_id = 0;
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
        				            $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
                                    foreach($result_array['data'] as $result_row){
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
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					$this->db->trans_complete();
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
		        $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
		        $arr = json_decode($game_data['api_data'], TRUE);
		        $current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$next_id = 0;
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
	
	public function splt($member_lists = NULL, $sync_type = NULL){
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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
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
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
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
        					    $DBdata['sync_status'] = STATUS_YES;
        					    //payout
        					    $max_bet_time = 0;
        					    $is_run_bet_data = true;
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
                    									'bet_amount_valid' => bcdiv(trim($result_row['4']),1,2),
                    									'payout_time' => $payout_time,
                    									'win_loss' => trim($result_row['2']) - bcdiv(trim($result_row['4']),1,2),
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
                        									'bet_amount_valid' => bcdiv(trim($result_row['4']),1,2),
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
        					}
						}
					}
					
					$this->db->trans_start();
					if(!empty($BUIDdata)){
    					$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
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
    								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
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
	
	public function bl($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'BL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->game_model->get_game_data($provider_code);
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
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
				
					//Prepare transaction list
					
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
					);

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							
							$response = $this->bl_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['resp_msg']['code']) && trim($result_array['resp_msg']['code']) == '200')
									{
										$page_total = trim($result_array['resp_data']['count']['page_total']);
										$DBdata['sync_status'] = STATUS_YES;
										
										if(isset($result_array['resp_data']['data']) && ! empty($result_array['resp_data']['data']))
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$start_time-3600, $end_time+3600);
	    										$is_retrieve = TRUE;
	    									}
											$DBdata['resp_data'] = json_encode($result_array['resp_data']['data']);
											
											for($i=0;$i<sizeof($result_array['resp_data']['data']);$i++)
											{
												$tmp_username = strtolower(trim($result_array['resp_data']['data'][$i]['player_account']));
												$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
												
												//Response time (UTC +8)
												$PBdata = array(
													'game_provider_code' => $provider_code,
													'game_type_code' => GAME_BOARD_GAME,
													'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
													'game_result_type' => $result_type,
													'game_code' => trim($result_array['resp_data']['data'][$i]['scene']),
													'game_real_code' => trim($result_array['resp_data']['data'][$i]['scene']),
													'bet_id' => trim($result_array['resp_data']['data'][$i]['id']),
													'bet_time' => trim($result_array['resp_data']['data'][$i]['start_time']),
													'game_time' => trim($result_array['resp_data']['data'][$i]['start_time']),
													'report_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'payout_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'sattle_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'compare_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'created_date' => time(),
													'bet_amount' => trim($result_array['resp_data']['data'][$i]['bet_num']),
													'bet_amount_valid' => trim($result_array['resp_data']['data'][$i]['bet_num_valid']),
													'payout_amount' => ((trim($result_array['resp_data']['data'][$i]['gain_gold']) > 0) ? trim($result_array['resp_data']['data'][$i]['gain_gold']) : 0),
													'win_loss' => trim($result_array['resp_data']['data'][$i]['gain_gold']),
													'jackpot_win' => 0,
													'promotion_amount' => 0,
													'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
													'status' => STATUS_COMPLETE,
													'game_username' => trim($result_array['resp_data']['data'][$i]['player_account']),
													'player_id' => $member_lists[$exact_username],
													'bet_info' => json_encode($result_array['resp_data']['data'][$i]),
													'insert_type' => SYNC_DEFAULT,
												);
												
												if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}

												if(trim($result_array['resp_data']['data'][$i]['game_code']) == 'slot')
												{
													$PBdata['game_type_code'] = GAME_SLOTS;
												}
												$PBdata['game_provider_type_code'] = $provider_code.$PBdata['game_type_code'];
												
												if($PBdata['bet_amount'] == 0)
												{
													$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
												}
												
												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{								
													array_push($Bdata, $PBdata);
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
											}
										}
										
										$page_id++;
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
					
							$current_page++;
							sleep(5);
						}
						else 
						{
							$is_loop = FALSE;
						}
					}
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
		}
	}
	
	private function bl_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page = NULL){
		#Minimum 5 sec per request
		$url = $arr['APIUrl'] . '/v1/game/get_all_record_list';
			
		$param_array = array(
			'start_time' => $start_time,
			'end_time' => $end_time,
			'page' => $page,
			'page_size' => 500,
			'AccessKeyId' => $arr['AccessKeyId'],
			'Timestamp' => time(),
			'Nonce' => $this->rng->get_token(128)
		);
		
		$param_array['Sign'] = strtolower(sha1($arr['AccessKeySecret'] . $param_array['Nonce'] . $param_array['Timestamp']));
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		
		return $response;
	}
	
	public function xg($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'XG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
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
					
					$game_code_data = array(
                        '1' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
                        '2' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
                        '3' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
                        '5' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
                        '6' => GAME_CODE_TYPE_LIVE_CASINO_SEDIE,
                        '7' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
    				);
    				
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->xg_connect($arr, $start_time, $end_time, $result_type, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
									{
									    if(isset($result_array['Data'])){
    										$page_total = trim($result_array['Data']['Pagination']['TotalPages']);
    										$DBdata['sync_status'] = STATUS_YES;
    										if(isset($result_array['Data']['Result']) &&  sizeof($result_array['Data']['Result'])>0){
    											if($is_retrieve == FALSE){
    	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
    	    										$is_retrieve = TRUE;
    	    									}
    											foreach($result_array['Data']['Result'] as $result_row){
    	                        					$tmp_username = trim($result_row['Account']);
                        							$exact_username = trim($result_row['Account']);
                                                    $win_result = STATUS_UNKNOWN;
                                                    
                                                    if($result_row['Status'] == "6"){
                    				                    $status = STATUS_CANCEL;
                    				                }else if($result_row['Status'] == "4"){
                    				                    $status = STATUS_PENDING;
                    				                }else if($result_row['Status'] == "7"){
                    				                    $status = STATUS_COMPLETE;
                    				                }else{
                    				                    if($result_row['Status'] == "1"){
                    				                        $win_result = STATUS_WIN;
                    				                    }else if($result_row['Status'] == "2"){
                    				                        $win_result = STATUS_LOSS;
                    				                    }else if($result_row['Status'] == "3"){
                    				                        $win_result = STATUS_TIE;
                    				                    }
                    				                    $status = STATUS_COMPLETE;
                    				                }
                    				                
                    				                $PBdata = array(
                            							'game_provider_code' => $provider_code,
                            							'game_type_code' => GAME_LIVE_CASINO,
                            							'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
                            							'game_result_type' => $result_type,
                            							'game_code' => (isset($game_code_data[trim($result_row['GameType'])]) ? $game_code_data[trim($result_row['GameType'])] : "Other"),
                        						        'game_real_code' => trim($result_row['GameType']),
                        						        'bet_id' => trim($result_row['WagersId']),
                        						        'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersTime']))),
                        						        'game_time' => strtotime('+12 hours', strtotime(trim($result_row['SettlementTime']))),
                        						        'report_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        						        'bet_amount' => trim($result_row['BetAmount']),
                        						        'bet_amount_valid' => trim($result_row['validBetAmount']),
                        						        'payout_amount' => trim($result_row['PayoffAmount']) + trim($result_row['BetAmount']),
                        						        'promotion_amount' => trim($result_row['validBetAmount']),
                        						        'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        						        'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        								'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        								'created_date' => time(),
                        						        'win_loss' => trim($result_row['PayoffAmount']),
                        						        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        						        'bet_code' => trim($result_row['BetType']),
                        						        'game_result' => trim($result_row['Transactions']),
                        						        'table_id' => trim($result_row['TableId']),
                        						        'round' => trim($result_row['Round']),
                        						        'status' => $status,
                        						        'game_username' => $result_row['Account'],
                        						        'player_id' => $member_lists[$exact_username],
                        						    );
                        						    
                        						    if($status != STATUS_COMPLETE){
                        						        $PBdata['payout_amount'] = 0;
                        						        $PBdata['promotion_amount'] = 0;
                        						    }
                        						    
                        						    if( ! in_array($PBdata['bet_id'], $transaction_lists))
                    								{
                    								    if($status != STATUS_PENDING){
                    								        $PBdata['bet_info'] = json_encode($result_row);
                        							        $PBdata['insert_type'] = SYNC_DEFAULT;
                        									array_push($Bdata, $PBdata);
                    								    }
                    								}else{
    													$PBdata['bet_update_info'] = json_encode($result_row);
    											        $PBdata['update_type'] = SYNC_DEFAULT;
    													array_push($BUdata, $PBdata);
    													array_push($BUIDdata, $PBdata['bet_id']);
    												}
    												
    												if($PBdata['status'] == STATUS_COMPLETE){
                										$PBdataWL = array(
                											'player_id' => $PBdata['player_id'],
                											'game_code' => $BUdataRow['game_code'],
    									                    'bet_time' => $BUdataRow['bet_time'],
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
    										$page_id++;
									    }
									}
								}
							}
							
							$this->db->insert('game_result_logs', $DBdata);
							if(!empty($BUIDdata)){
        						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
        						if( ! empty($transaction_lists_old)){
        							foreach($transaction_lists_old as $transaction_lists_old_row){
        								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
        									$PBdataWL = array(
        										'player_id' => $transaction_lists_old_row['player_id'],
        										'game_code' => $transaction_lists_old_row['game_code'],
            									'bet_time' => $transaction_lists_old_row['bet_time'],
        										'payout_time' => $transaction_lists_old_row['payout_time'],
        										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
        										'game_type_code' => $transaction_lists_old_row['game_type_code'],
        										'total_bet' => -1,
        										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
        										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
        										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
        									);
        									array_push($BUDdata, $PBdataWL);
        								}
        							}
        						}
        					}
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							if( ! empty($BUdata))
        					{
        						foreach($BUdata as $BUdataRow){
        							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
        						}
        					}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	private function xg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $url = $arr['APIUrl'];
	    $start_date = date('Y-m-d', strtotime('-12 hours', $start_time))."T".date('H:i:s', strtotime('-12 hours', $start_time));
		$end_date = date('Y-m-d', strtotime('-12 hours', $end_time))."T".date('H:i:s', strtotime('-12 hours', $end_time));
	    
	    $url .= "/xg-casino/GetBetRecordByTime";
        
        $param_array = array(
            "EndTime" => $end_date,
            "Page" => 1,
            "PageLimit" => 10000,
            "StartTime" => $start_date,
        );
        
        $keyP = urldecode(http_build_query($param_array, '', '&'))."&AgentId=".$arr['AgentID'];
        $keyA = rand(pow(10, $arr['FrontKey']-1), pow(10, $arr['FrontKey'])-1);
        $keyC = rand(pow(10, $arr['BackKey']-1), pow(10, $arr['BackKey'])-1);
        $keyT = date("ymj", strtotime('-12 hours', time()));
        $keyG = md5($keyT.$arr['AgentID'].$arr['AgentKey']);
        $key = $keyA . md5($keyP.$keyG).$keyC;
        $param_array['AgentId'] = $arr['AgentID'];
        $param_array['Key'] = $key;
        
        $response = $this->curl_json($url, $param_array);
        return $response;
	}
	
	public function gx($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'GX';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time']+1;
				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:59', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$BUIDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
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
					
					$game_code_data = array(
                        '1' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
                        '2' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
                        '3' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
                        '5' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
                        '6' => GAME_CODE_TYPE_LIVE_CASINO_SEDIE,
                        '7' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
    				);
    				
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->xg_connect($arr, $start_time, $end_time, $result_type, $page_id);
						
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
									{
									    if(isset($result_array['Data'])){
    										$page_total = trim($result_array['Data']['Pagination']['TotalPages']);
    										$DBdata['sync_status'] = STATUS_YES;
    										if(isset($result_array['Data']['Result']) &&  sizeof($result_array['Data']['Result'])>0){
    											if($is_retrieve == FALSE){
    	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
    	    										$is_retrieve = TRUE;
    	    									}
    											foreach($result_array['Data']['Result'] as $result_row){
    	                        					$tmp_username = trim($result_row['Account']);
                        							$exact_username = trim($result_row['Account']);													
                        							#$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
                                                    $win_result = STATUS_UNKNOWN;
                                                    
                                                    if($result_row['Status'] == "6"){
                    				                    $status = STATUS_CANCEL;
                    				                }else if($result_row['Status'] == "4"){
                    				                    $status = STATUS_PENDING;
                    				                }else if($result_row['Status'] == "7"){
                    				                    $status = STATUS_COMPLETE;
                    				                }else{
                    				                    if($result_row['Status'] == "1"){
                    				                        $win_result = STATUS_WIN;
                    				                    }else if($result_row['Status'] == "2"){
                    				                        $win_result = STATUS_LOSS;
                    				                    }else if($result_row['Status'] == "3"){
                    				                        $win_result = STATUS_TIE;
                    				                    }
                    				                    $status = STATUS_COMPLETE;
                    				                }
                    				                
                    				                $PBdata = array(
                            							'game_provider_code' => $provider_code,
                            							'game_type_code' => GAME_LIVE_CASINO,
                            							'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
                            							'game_result_type' => $result_type,
                            							'game_code' => (isset($game_code_data[trim($result_row['GameType'])]) ? $game_code_data[trim($result_row['GameType'])] : "Other"),
                        						        'game_real_code' => trim($result_row['GameType']),
                        						        'bet_id' => trim($result_row['WagersId']),
                        						        'bet_time' => strtotime('+12 hours', strtotime(trim($result_row['WagersTime']))),
                        						        'game_time' => strtotime('+12 hours', strtotime(trim($result_row['SettlementTime']))),
                        						        'report_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        						        'bet_amount' => trim($result_row['BetAmount']),
                        						        'bet_amount_valid' => trim($result_row['validBetAmount']),
                        						        'payout_amount' => trim($result_row['PayoffAmount']) + trim($result_row['BetAmount']),
                        						        'promotion_amount' => trim($result_row['validBetAmount']),
                        						        'payout_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        						        'sattle_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        								'compare_time' => strtotime('+12 hours', strtotime(trim($result_row['PayoffTime']))),
                        								'created_date' => time(),
                        						        'win_loss' => trim($result_row['PayoffAmount']),
                        						        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        						        'bet_code' => trim($result_row['BetType']),
                        						        'game_result' => trim($result_row['Transactions']),
                        						        'table_id' => trim($result_row['TableId']),
                        						        'round' => trim($result_row['Round']),
                        						        'status' => $status,
                        						        'game_username' => $result_row['Account'],
                        						        'player_id' => $member_lists[$exact_username],
                        						    );
                        						    
                        						    if($status != STATUS_COMPLETE){
                        						        $PBdata['payout_amount'] = 0;
                        						        $PBdata['promotion_amount'] = 0;
                        						    }
                        						    
                        						    if( ! in_array($PBdata['bet_id'], $transaction_lists))
                    								{
                    								    if($status != STATUS_PENDING){
                    								        $PBdata['bet_info'] = json_encode($result_row);
                        							        $PBdata['insert_type'] = SYNC_DEFAULT;
                        									array_push($Bdata, $PBdata);
                    								    }
                    								}else{
    													$PBdata['bet_update_info'] = json_encode($result_row);
    											        $PBdata['update_type'] = SYNC_DEFAULT;
    													array_push($BUdata, $PBdata);
    													array_push($BUIDdata, $PBdata['bet_id']);
    												}
    												
    												if($PBdata['status'] == STATUS_COMPLETE){
                										$PBdataWL = array(
                											'player_id' => $PBdata['player_id'],
                											'game_code' => $BUdataRow['game_code'],
    									                    'bet_time' => $BUdataRow['bet_time'],
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
    										$page_id++;
									    }
									}
								}
							}
							
							$this->db->insert('game_result_logs', $DBdata);
							if(!empty($BUIDdata)){
        						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
        						if( ! empty($transaction_lists_old)){
        							foreach($transaction_lists_old as $transaction_lists_old_row){
        								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
        									$PBdataWL = array(
        										'player_id' => $transaction_lists_old_row['player_id'],
        										'game_code' => $transaction_lists_old_row['game_code'],
            									'bet_time' => $transaction_lists_old_row['bet_time'],
        										'payout_time' => $transaction_lists_old_row['payout_time'],
        										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
        										'game_type_code' => $transaction_lists_old_row['game_type_code'],
        										'total_bet' => -1,
        										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
        										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
        										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
        									);
        									array_push($BUDdata, $PBdataWL);
        								}
        							}
        						}
        					}
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							if( ! empty($BUdata))
        					{
        						foreach($BUdata as $BUdataRow){
        							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
        						}
        					}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
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
		}
		else{
			echo EXIT_ERROR;
		}
	}
	
	private function jili_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//GMT-8
		$url = $arr['APIUrl'];
		$url .= "/GetBetRecordByTime";
	    $start_date = date('Y-m-d', strtotime('-12 hours', $start_time))."T".date('H:i:s', strtotime('-12 hours', $start_time));
        $end_date = date('Y-m-d', strtotime('-12 hours', $end_time))."T".date('H:i:s', strtotime('-12 hours', $end_time));
	    $param_array = array(
            'StartTime' => $start_date,
            'EndTime' => $end_date,
            'Page' => $page_id,
            'PageLimit' => 10000,
        );
		$keyP = urldecode(http_build_query($param_array, '', '&'))."&AgentId=".$arr['AgentID'];
        $keyA = rand(pow(10, $arr['FrontKey']-1), pow(10, $arr['FrontKey'])-1);
        $keyC = rand(pow(10, $arr['BackKey']-1), pow(10, $arr['BackKey'])-1);
        $keyT = date("ymj", strtotime('-12 hours', time()));
        $keyG = md5($keyT.$arr['AgentID'].$arr['AgentKey']);
        $key = $keyA . md5($keyP.$keyG).$keyC;
        $param_array['AgentId'] = $arr['AgentID'];
        $param_array['Key'] = $key;
        $param_array['FilterAgent'] = TRUE;
        $response = $this->curl_post($url, $param_array);
        return $response;
	}
	
	public function ug($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'UG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
        
        if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
		        
		        $arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
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
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				
				$db_record_start_time = 0;
        		$db_record_end_time = 0;
                $capture_bet_time = 0;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-60 minutes' ,$start_time);
					$db_record_end_time = strtotime('+60 minutes' ,$end_time);
					
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
					
					$response = $this->ug_connect($arr, $start_time, $end_time, $next_id);
					if($response['code'] == '0')
    				{
    					$result_array = json_decode($response['data'], TRUE);
    					if( ! empty($result_array))
    					{
    						$DBdata['resp_data'] = json_encode($result_array);
    						if(isset($result_array['code']) && $result_array['code'] == '000000'){
    							$DBdata['sync_status'] = STATUS_YES;
    							if(isset($result_array['data']) && sizeof($result_array['data'])>0){
    							    foreach($result_array['data'] as $result_row){
							            $capture_bet_time = strtotime(trim($result_row['betTime']));
        		                        
        		                        if($db_record_start_time == 0){
        		                            $db_record_start_time = $capture_bet_time - 300;
        		                        }
        		                        
        		                        if($db_record_end_time == 0){
        		                            $db_record_end_time = $capture_bet_time + 300;
        		                        }
        		                        
        		                        
        		                        if($capture_bet_time <= $db_record_start_time){
        		                            $db_record_start_time = $capture_bet_time - 300;
        		                        }
        		                        
        		                        if($capture_bet_time >= $db_record_end_time){
        		                            $db_record_end_time = $capture_bet_time + 300;
        		                        }
    							    }
    							    
    							    
    							    $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    							    
    								foreach($result_array['data'] as $result_row){
    									if($result_row['status'] == "4" || $result_row['status'] == "6" || $result_row['status'] == "2"){
    										$status = STATUS_CANCEL;
    									}else{
    										if($result_row['status'] == "0" || $result_row['status'] == "1" || $result_row['status'] == "3"){
    											$status = STATUS_PENDING;
    										}else{
    											$status = STATUS_COMPLETE;
    										}
    									}
    									
    									#$tmp_username = strtolower(trim($result_row['Account']));
										$tmp_username = strtolower(trim($result_row['userId']));
    									$exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);
    									$sport_type = ((isset($result_row['SubBets'][0]['SportID']))?trim($result_row['SubBets'][0]['SportID']) : '');
    									
										$bet_amount = trim($result_row['stake']);
										$bet_amount_valid = (isset($result_row['turnover'])? trim($result_row['turnover']): 0);
										$win_loss = trim($result_row['winLose']);
										$payout = trim($result_row['payout']);
										
    									$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SPORTSBOOK,
    										'game_code' => $sport_type,
    										'bet_id' => trim($result_row['ticketId']),
    										'bet_ref_no' => trim($result_row['sortNo']),
    										'bet_time' => strtotime(trim($result_row['betTime'])),
    										'bet_amount' => $bet_amount,
    										'bet_amount_valid' => $bet_amount_valid,
    										'payout_time' => strtotime(trim($result_row['reportTime'])),
    										'win_loss' =>  $win_loss,
    										'report_time' => strtotime(trim($result_row['updateTime'])),
    										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    										'status' => $status,
    										'game_username' => trim($result_row['userId']),
    										'player_id' =>  $member_lists[$exact_username],
    									);
    									if($status == STATUS_COMPLETE){
    										$PBdata['payout_amount'] = $payout;
    									}else{
    										$PBdata['payout_amount'] = 0;
    									}
    									if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{					
											$PBdata['bet_info'] = json_encode($result_row);
									        $PBdata['insert_type'] = SYNC_DEFAULT;
											array_push($Bdata, $PBdata);
										}else{
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
    								$this->db->insert('game_result_logs', $DBdata);
									
    								if(!empty($BUIDdata)){
                						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
                						if( ! empty($transaction_lists_old)){
                							foreach($transaction_lists_old as $transaction_lists_old_row){
                								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
                									$PBdataWL = array(
                										'player_id' => $transaction_lists_old_row['player_id'],
                										'payout_time' => $transaction_lists_old_row['payout_time'],
                										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
                										'game_type_code' => $transaction_lists_old_row['game_type_code'],
                										'total_bet' => -1,
                										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
                										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
                										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
                									);
                									array_push($BUDdata, $PBdataWL);
                								}
                							}
                						}
                					}
                					if( ! empty($Bdata))
                					{
                						$this->db->insert_batch('transaction_report', $Bdata);
                					}
                					if( ! empty($BUDdata))
                					{
                						$this->db->insert_batch('win_loss_logs', $BUDdata);
                					}
                					if( ! empty($BUdata))
                					{
                						foreach($BUdata as $BUdataRow){
                							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
                						}
                					}
    							}
    						}
    					}
    				}
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
	
	private function ug_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL){
		$url = $arr['APIUrl'].'/api/transfer/getTicketBySortNo';
		$param_array = array(
            "apiKey" => $arr['ApiKey'],
            "operatorId" => $arr['OperatorID'],
			"sortNo" => $next_id,
			"row" => 1000,
		);
		$response = $this->curl_json($url, $param_array);
		return $response;
	}
}