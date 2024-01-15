<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Synctwm extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all()
	{
		set_time_limit(0);

	}
    
    public function ninek($member_lists = NULL){
        set_time_limit(0);
        $member_lists_array = $this->player_model->get_player_list_array_by_provider(array("NK"));
        $member_lists = $member_lists_array['NK'];
		$provider_code = 'NK';
		$result_type = GAME_LOTTERY;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			
			$arr = json_decode($game_data['api_data'], TRUE);
			$current_time = time();
			$last_sync_time = strtotime('-30 minutes', $current_time);
			$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
			if( ! empty($sync_data))
			{
				$last_sync_time = $sync_data['end_time'];
			}
			
			//$initial_time = date('Y-m-d H:i:00', $last_sync_time);
			$initial_time = "2022-10-01 00:30:00";
			$start_time = strtotime($initial_time);
			$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1440 minutes', strtotime($initial_time))));
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
							$DBdata['resp_data'] = json_encode($result_array);
							if( ! empty($result_array))
							{
								if(isset($result_array['success']) && $result_array['success'] == '0'){
									$DBdata['sync_status'] = STATUS_YES;
									ad("yes");
									$page_total = trim($result_array['data']['PageInfo']['TotalPage']);
									if(sizeof($result_array['data']['BetList'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}

										foreach($result_array['data']['BetList'] as $result_row){
										    $tmp_username = strtolower(trim($result_row['MemberAccount']));
										    $exact_username = $tmp_username;
										    
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
    									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
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
								$page_id++;
							}
						}

						$result_promotion_reset = array('promotion_amount' => 0);
						ad($Bdata);
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
		}else{
			echo EXIT_ERROR;
		}
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
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	public function spsb_gamelog($member_lists = NULL){
	     set_time_limit(0);
	    $provider_code = 'SPSB';
	    $member_lists_array = $this->player_model->get_player_list_array_by_provider(array("SPSB"));
        $member_lists = $member_lists_array['SPSB'];
	    $result_type = GAME_ALL;
	    $sync_type = SYNC_TYPE_MODIFIED;
	    $game_result_log_id = 1000556;
	    $game_data = $this->report_model->get_wager_game_data($provider_code);
	    $result_array = array();
	    $this->db->select('game_result_log_id,resp_data');
	    $this->db->where('game_result_log_id',$game_result_log_id);
        $this->db->where('game_provider_code',$provider_code);
        $query = $this->db->get('game_result_logs');
        if($query->num_rows() > 0)
	    {
	        $result_query = $query->row_array();
		    $result_array = json_decode($result_query['resp_data'], TRUE);
	    }
	    $arr = json_decode($game_data['api_data'], TRUE);
	    
	    $start_time = time();
		$end_time = time();
		$sys_data = $this->miscellaneous_model->get_miscellaneous();
		$db_record_start_time = strtotime('-200 days' ,$start_time);
		$db_record_end_time = strtotime('+200 days' ,$end_time);
		$Bdata = array();
		$BUDdata = array();
	    if( ! empty($result_array))
		{
    	    $game_code_data = array(
                '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                '2' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                '5' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                '6' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                '7' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                '8' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                '9' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
                '10' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                '11' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
                '12' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                '13' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
                '14' => GAME_CODE_TYPE_SPORTBOOK_OTHER,
                '20' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
            );
            
            if(!empty($result_array))
			{
				$DBdata['resp_data'] = json_encode($result_array);
				if(isset($result_array['code']) && $result_array['code'] == '999')
				{
				   $DBdata['sync_status'] = STATUS_YES;
			       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
			           $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
			           foreach($result_array['data'] as $result_row){
			                $tmp_username = strtolower(trim($result_row['m_id']));
					        $exact_username = $tmp_username;
						
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
                                $PBdata = array(
									'game_provider_code' => $provider_code,
									'game_type_code' => GAME_SPORTSBOOK,
									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
									'game_result_type' => $result_type,
									'game_code' => (isset($game_code_data[trim($result_row['team_no'])]) ? $game_code_data[trim($result_row['team_no'])] : GAME_CODE_TYPE_UNKNOWN),
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
									'player_id' =>  $member_lists[$exact_username],
								);
								if( ! in_array($PBdata['bet_id'], $transaction_lists))
					            {	
    								$PBdata['bet_info'] = json_encode($result_row,true);
    								$PBdata['bet_update_info'] = json_encode($result_row,true);
							        $PBdata['insert_type'] = SYNC_DEFAULT;
							        $PBdata['update_type'] = SYNC_DEFAULT;
									array_push($Bdata, $PBdata);
									
									if($PBdata['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $PBdata['player_id'],
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
			
			if( ! empty($Bdata))
			{
				$this->db->insert_batch('transaction_report', $Bdata);
			}
			if( ! empty($BUDdata))
			{
				$this->db->insert_batch('win_loss_logs', $BUDdata);
			}
			
			ad($Bdata);
			ad($BUDdata);
		}
	}
	
	public function rtg($member_lists = NULL){
	    set_time_limit(0);
		$member_lists_array = $this->player_model->get_player_list_array_by_provider(array("RTG"));
        $member_lists = $member_lists_array['RTG'];
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
				$initial_time = "2023-02-23 00:00:00";
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1440 minutes', strtotime($initial_time))));
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
												$exact_username = $tmp_username;

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
													'game_code' => trim($result_row['gameId']),
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
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
								}
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
		$url .= '?isChildren=true&lang=en&pageSize=5000&updatedStart='.$start_date.'&updatedEnd='.$end_date.'&page='.$page_id;
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
	
	public function icg($member_lists = NULL){
		set_time_limit(0);
		$member_lists_array = $this->player_model->get_player_list_array_by_provider(array("ICG"));
        $member_lists = $member_lists_array['ICG'];
		$provider_code = 'ICG';
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
			    $bet_ids = array();
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2022-12-08 16:00:00";
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
				$is_retrieve = FALSE;
				ad(date('Y-m-d H:i:s',$start_time));
				ad(date('Y-m-d H:i:s',$end_time));
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
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
													$exact_username = $tmp_username;

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
														array_push($bet_ids,$PBdata['bet_id']);
													}else{
														$PBdata['bet_update_info'] = json_encode($result_row);
												        $PBdata['update_type'] = SYNC_DEFAULT;
														array_push($BUdata, $PBdata);
														array_push($BUIDdata, $PBdata['bet_id']);
													}

													if($PBdata['status'] == STATUS_COMPLETE){
														$PBdataWL = array(
															'player_id' => $PBdata['player_id'],
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
								//$this->db->insert('game_result_logs', $DBdata);
								$result_promotion_reset = array('promotion_amount' => 0);
								/*
								if(!empty($BUIDdata)){
									$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
									if( ! empty($transaction_lists_old)){
										foreach($transaction_lists_old as $transaction_lists_old_row){
											if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $transaction_lists_old_row['player_id'],
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
								*/
								
								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}
								if( ! empty($BUDdata))
								{
									$this->db->insert_batch('win_loss_logs', $BUDdata);
								}
								
								/*
								if( ! empty($BUdata))
								{
									foreach($BUdata as $BUdataRow){
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
									}
								}
								*/
								sleep(5);
							}
						}
					}
					echo implode(",",$bet_ids);
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
	
	public function spsb_bet_payout($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$member_lists_array = $this->player_model->get_player_list_array_by_provider(array("SPSB"));
        $member_lists = $member_lists_array['SPSB'];
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
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2023-02-21 12:00:00";
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				
				ad(date('Y-m-d H:i:s',$start_time));
				ad(date('Y-m-d H:i:s',$end_time));
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
                        '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                        '2' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                        '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                        '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                        '5' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                        '6' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                        '7' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                        '8' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                        '9' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
                        '10' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                        '11' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
                        '12' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                        '13' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
                        '14' => GAME_CODE_TYPE_SPORTBOOK_OTHER,
                        '20' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
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
        					   ad("yes");
        				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
        				            $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
                                    foreach($result_array['data'] as $result_row){
                                        if($result_row['payout_time'] == "0000-00-00 00:00:00"){
                                           $payout_time = strtotime($result_row['m_date']);
                                        }else{
                                           $payout_time = strtotime($result_row['payout_time']);
                                        }
        				               
                                        $tmp_username = strtolower(trim($result_row['m_id']));
    							        $exact_username = $tmp_username;
    								
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
        									'player_id' =>  $member_lists[$exact_username],
        								);
        								
        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
									    {
									        if($PBdata['status'] == STATUS_COMPLETE){
									            $PBdata['bet_info'] = json_encode($result_row);
    									        $PBdata['insert_type'] = SYNC_DEFAULT;
    									        array_push($Bdata, $PBdata);
        										$PBdataWL = array(
        											'player_id' => $PBdata['player_id'],
        											'bet_time' => $PBdata['bet_time'],
        											'payout_time' => $PBdata['payout_time'],
        											'game_provider_code' => $PBdata['game_provider_code'],
        											'game_type_code' => $PBdata['game_type_code'],
        											'game_code' => $PBdata['game_code'],
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
					ad($Bdata);
					ad($BUDdata);
					/*
					$this->db->insert('game_result_logs', $DBdata);
					*/
					
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

	public function spsb_payout($member_lists = NULL){
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
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				
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
            					   ad("YES");
            				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
            				           foreach($result_array['data'] as $result_row){
            				                $tmp_username = strtolower(trim($result_row['m_id']));
        							        $exact_username = $tmp_username;
        								
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
                    									'player_id' =>  $member_lists[$exact_username],
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
        										'bet_time' => $transaction_lists_old[$BUdataRow['bet_id']]['bet_time'],
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
												'bet_time' => $BUdataRow['bet_time'],
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
    											'bet_time' => $BUdataRow['bet_time'],
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
											'bet_time' => $BUdataRow['bet_time'],
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
	
	public function run_dg($member_lists = NULL){
	    echo "1";
        set_time_limit(0);
        $member_lists_array = $this->player_model->get_player_list_array_by_provider(array("DG"));
        $member_lists = $member_lists_array['DG'];
        $result_array = array();
        $is_insert = false;
        $game_result_log_id = 1000566;
        
        $provider_code = 'DG';
        $result_type = "LC";
        
        $this->db->select('game_result_log_id,resp_data');
        $this->db->where('game_result_log_id',$game_result_log_id);
        $this->db->where('game_provider_code',$provider_code);
        $this->db->where('game_result_type',$result_type);
        $this->db->where('sync_status',1);
        $this->db->order_by('game_result_log_id',"ASC");
        $this->db->limit(1);
        $query = $this->db->get('game_result_logs');
        if($query->num_rows() > 0)
	    {
	        $is_insert = TRUE;
	        $result_query = $query->row_array();
	        $result_array = json_decode($result_query['resp_data'], TRUE);
	    }
		$start_time = time();
		$end_time = time();
		$sys_data = $this->miscellaneous_model->get_miscellaneous();
		$db_record_start_time = strtotime('-200 days' ,$start_time);
		$db_record_end_time = strtotime('+200 days' ,$end_time);
		$Bdata = array();
		$BdataID = array();

		if( ! empty($result_array))
		{
		    if(isset($result_array['codeId']) && $result_array['codeId'] == "0"){
    			if(isset($result_array['list'])){
    				if(sizeof($result_array['list'])){
    					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    					foreach($result_array['list']  as $result_row){
    						$tmp_username = strtolower(trim($result_row['userName']));
							$exact_username = $tmp_username;

							if($result_row['isRevocation'] == "1"){
								$status = STATUS_COMPLETE;
							}else if($result_row['isRevocation'] == "2"){
								$status = STATUS_CANCEL;
							}else{
								$status = STATUS_PENDING;
							}
							
							$gameType = ((isset($result_row['gameType'])) ? $result_row['gameType'] : '');
							$gameId = ((isset($result_row['gameId'])) ? $result_row['gameId'] : '');
							$tableId = ((isset($result_row['tableId'])) ? $result_row['tableId'] : '');
							$game_type_code = GAME_LIVE_CASINO;
							$game_code = "";
							switch($gameType)
							{
								case "1":
									switch($gameId){
										case "1": 
											switch($tableId){
												case "30101": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
												case "30102": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
												case "30103": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
												case "30105": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
												default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BACCARAT; break;
											}break;
										case "2": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT; break;
										case "3":
											switch($tableId){
												case "30301": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_DRAGON_TIGER; break;
												default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER; break;
											}break;
										case "4": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_ROULETTE; break;
										case "5": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_SICBO; break;
										case "6": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN; break;
										case "7": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL; break;
										case "8": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT; break;
										case "11": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
										case "14": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_SEDIE; break;
										case "16": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER; break;
										case "41": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT; break;
										case "42": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_DRAGON_TIGER; break;
										case "43": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_ZHA_JIN_HUA; break;
										case "44": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BULL_BULL; break;
										case "45": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_THREE_FACE_POKER; break;
									}break;
								case "2":
									switch($gameId){
										case "1": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_MEMBER_SEND_GIFT; break;
										case "2": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_MEMBER_GET_GIFT; break;
										case "3": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_ANCHOR_SEND_TIPS; break;
										case "4": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_COMPANY_SEND_GIFT; break;
										case "5": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_BO_BING; break;
										case "6": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_CROUPIER_SEND_TIPS; break;
									}break;
								default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_UNKNOWN; break;
							}


							$PBdata = array(
						        'game_provider_code' => $provider_code,
						        'game_type_code' => $game_type_code,
						        'game_provider_type_code' => $provider_code . "_" . $game_type_code,
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
								array_push($BdataID, $PBdata['bet_id']);
							}
    					}
    				}
    			}
    		}
			ad($Bdata);
			//$this->db->insert('game_result_logs_test', $DBdata);
			
			if( ! empty($Bdata))
    		{
    			$this->db->insert_batch('transaction_report', $Bdata);
    		}
    		
    		if( ! empty($BUDdata))
			{
				$this->db->insert_batch('win_loss_logs', $BUDdata);
			}
		}
	}
	
	public function wm($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'WM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
        $member_lists_array = $this->player_model->get_player_list_array_by_provider(array("WM"));
        $member_lists = $member_lists_array['WM'];
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			//if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2023-01-03 19:00:00";
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				ad(date("Y-m-d H:i:s",$end_time));
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
						'101' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
						'102' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
						'103' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
						'104' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
						'105' => GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL,
						'106' => GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER,
						'107' => GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN,
						'108' => GAME_CODE_TYPE_LIVE_CASINO_SEDIE,
						'110' => GAME_CODE_TYPE_LIVE_CASINO_FISH_PRAWN_CRAB,
						'111' => GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA,
						'112' => "Wenzhou Pai Gow",
						'113' => "Mahjong Tiles",
						'128' => GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR,
					);

					$response = $this->wm_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['errorCode']) && ($result_array['errorCode'] == '0' OR $result_array['errorCode'] == '107'))
							{
							    echo "yes";
								$DBdata['sync_status'] = STATUS_YES;
								for($i=0;$i<sizeof($result_array['result']);$i++)
								{
									if($is_retrieve == FALSE){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$is_retrieve = TRUE;
									}
									//Response time (UTC +8)
									if(isset($result_array['result'][$i]['Tip']))
									{
										$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
										$exact_username = $tmp_username;
										
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
											'game_code' => (isset($game_code_data[trim($result_array['result'][$i]['gid'])]) ? $game_code_data[trim($result_array['result'][$i]['gid'])] : GAME_CODE_TYPE_UNKNOWN),
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
									}else{
										$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
										$exact_username = $tmp_username;

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
											'game_code' => (isset($game_code_data[trim($result_array['result'][$i]['gid'])]) ? $game_code_data[trim($result_array['result'][$i]['gid'])] : GAME_CODE_TYPE_UNKNOWN),
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
                    ad($Bdata);
                    ad($BUDdata);
                    /*
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
					*/
					
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
				}
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			/*
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
			*/
		}else{
			echo EXIT_ERROR;
		}
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
	
	public function bl($member_lists = NULL){
		set_time_limit(0);
		$member_lists_array = $this->player_model->get_player_list_array_by_provider(array("BL"));
		$member_lists = $member_lists_array['BL'];
		$provider_code = 'BL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;

		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				//$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2023-02-20 03:00:00";
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
										echo "yes";
										
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
												$exact_username = $tmp_username;
												
												//Response time (UTC +8)
												$PBdata = array(
													'game_provider_code' => $provider_code,
													'game_type_code' => GAME_BOARD_GAME,
													'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
													'game_result_type' => $result_type,
													'game_code' => trim($result_array['resp_data']['data'][$i]['game_code']),
													'game_real_code' => trim($result_array['resp_data']['data'][$i]['game_code']),
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

												if(trim($result_array['resp_data']['data'][$i]['type']) == 'slot')
												{
													$PBdata['game_type_code'] = GAME_SLOTS;
													$PBdata['game_provider_type_code'] = $provider_code."_".GAME_SLOTS;
												}
												
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
										
										$page_id++;
									}
								}
							}
							
							//$this->db->insert('game_result_logs', $DBdata);
                            ad($Bdata);
                            
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
								
							}
                            ad($BUDdata);
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
		}
	}
	
	private function bl_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page = NULL){
		#Minimum 5 sec per request
		$url = $arr['APIUrl'] . '/v1/game/get_all_record_list';
			
		$param_array = array(
			'start_time' => $start_time,
			'end_time' => $end_time,
			'page' => $page,
			'page_size' => 1000,
			'AccessKeyId' => $arr['AccessKeyId'],
			'Timestamp' => time(),
			'Nonce' => $this->rng->get_token(128)
		);
		
		$param_array['Sign'] = strtolower(sha1($arr['AccessKeySecret'] . $param_array['Nonce'] . $param_array['Timestamp']));
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		
		return $response;
	}
	
	public function bng($member_lists = NULL){
	    set_time_limit(0);
	    $member_lists_array = $this->player_model->get_player_list_array_by_provider(array("BNG"));
	    $member_lists = $member_lists_array['BNG'];
	    
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
				$initial_time = "2023-02-20 06:00:00";
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				
				ad(date('Y-m-d H:i:s', $start_time));
				ad(date('Y-m-d H:i:s', $end_time));
				
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
    						    $result_array = json_decode($response['data'], TRUE);
    						    if( ! empty($result_array))
    							{
    							    $DBdata['resp_data'] = json_encode($result_array);
								    $DBdata['sync_status'] = STATUS_YES;
								    echo "yes";
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
    									            $exact_username = $tmp_username;
    									            
    									            $PBdata = array(
                										'game_provider_code' => $provider_code,
                										'game_type_code' => GAME_SLOTS,
                										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
                										'game_result_type' => $result_type,
                										'game_code' => trim($result_row['game_id']),
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
						ad($Bdata);
						ad($BUDdata);
						/*
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						*/
						
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
	
	public function spsb2($member_lists = NULL){
		set_time_limit(0);
		$member_lists_array = $this->player_model->get_player_list_array_by_provider(array("SPSB"));
        $member_lists = $member_lists_array['SPSB'];
		
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
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				//$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$initial_time = "2023-03-07 00:00:00";
				$start_time = strtotime($initial_time);
				//$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1440 minutes', strtotime($initial_time))));
				$end_time = 1678160700;
				
				ad(date('Y-m-d H:i:s',$start_time));
				ad(date('Y-m-d H:i:s',$end_time));
				
				$game_code_data = array(
                    '1' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                    '3' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                    '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                    '5' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                    '11' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                    '12' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                    '13' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                    '14' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                    '16' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL,
                    '21' => GAME_CODE_TYPE_SPORTBOOK_PINGPONG,
                    '22' => GAME_CODE_TYPE_SPORTBOOK_BADMINTON,
                    '23' => GAME_CODE_TYPE_SPORTBOOK_VOLLEYBALL,
                    '24' => GAME_CODE_TYPE_SPORTBOOK_SNOOKER,
                    '31' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_FIFA,
                    '32' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
                    '55' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
                    '72' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                    '82' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                    '83' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                    '84' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
                    '85' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
                    '101' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                    '102' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                );
				
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
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-7 days' ,$start_time);
					$db_record_end_time = strtotime('+7 days' ,$end_time);
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
						$response = $this->spsb2_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['Code']) && $result_array['Code'] == '200'){
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									echo "yes";
									if(isset($result_array['Data']['List']) && sizeof($result_array['Data']['List'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										}
										foreach($result_array['Data']['List'] as $result_row){
                    		                $tmp_username = strtolower(trim($result_row['User']));
                    		                $exact_username = $tmp_username;
                    		               
                    		                if(trim($result_row['BetType']) == "1"){
                		                        $game_code = (isset($game_code_data[trim($result_row['dataBet'][0]['CatID'])]) ? $game_code_data[trim(trim($result_row['dataBet'][0]['CatID']))] : "Other");
                		                        $game_real_code = trim($result_row['dataBet'][0]['CatID']);
                		                    }else{
                		                        $game_code = "Parlay";
                		                        $game_real_code = $result_row['BetType'];
                		                    }
                		               
                		                    $status = STATUS_PENDING;
                    		                $win_result = STATUS_UNKNOWN;
                		               
                		                    if(trim($result_row['WinLoseStatus']) == "D"){
                		                        $win_result = STATUS_TIE;
                		                    }else if(trim($result_row['WinLoseStatus']) == "WA" || trim($result_row['WinLoseStatus']) == "WH"){
                		                        $win_result = STATUS_WIN;
                		                    }else if(trim($result_row['WinLoseStatus']) == "LA" || trim($result_row['WinLoseStatus']) == "LH"){
                		                        $win_result = STATUS_LOSS;
                		                    }
                		            
                		                    if(trim($result_row['WinLoseStatus']) == "D" || trim($result_row['WinLoseStatus']) == "V"){
                		                        $status = STATUS_CANCEL;
                		                    }else{
                		                        if(trim($result_row['IsPayout']) == "1"){
                		                            $status = STATUS_COMPLETE;
                		                        }
                		                    }
                		                
                		               
                		                    $PBdata = array(
        									    'game_provider_code' => $provider_code,
        								        'game_type_code' => GAME_SPORTSBOOK,
            									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
            									'game_result_type' => $result_type,
            									'game_code' => $game_code,
            									'game_real_code' => $game_real_code,
            									'bet_id' => trim($result_row['TicketID']),
            									'bet_time' => strtotime(trim($result_row['BetTimeStr'])),
            									'bet_amount' => trim($result_row['Amount']),
            									'bet_amount_valid' => trim($result_row['EffectiveAmount']),
            									'payout_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'win_loss' => trim($result_row['ResultAmount']),
            									'game_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'report_time' => strtotime(trim($result_row['AccDateStr'])),
            									'sattle_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'compare_time' => strtotime(trim($result_row['AccDateStr'])),
            									'created_date' => time(),
            									'payout_amount' => trim($result_row['ResultAmount']) + trim($result_row['EffectiveAmount']),
            									'promotion_amount' => trim($result_row['EffectiveAmount']),
            									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            									'bet_code' => (isset($result_row['BetType']) ? trim($result_row['BetType']) : "0"),
            									'status' => $status,
            									'win_result' => $win_result,
            									'game_username' => trim($result_row['User']),
            									'player_id' =>  $member_lists[$exact_username],
        							        );
        							        
        							        if($PBdata['win_loss'] != 0){
    									    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
    									    }else{
    									        $PBdata['payout_amount'] = 0;
    									    }
    									    
    									    
    									    if(isset($member_lists[$exact_username])){
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
        									    
        									    if($PBdata['status'] == STATUS_COMPLETE){
    												$PBdataWL = array(
    													'player_id' => $PBdata['player_id'],
    													'bet_time' => $PBdata['bet_time'],
    													'payout_time' => $PBdata['payout_time'],
    													'game_provider_code' => $PBdata['game_provider_code'],
    													'game_type_code' => $PBdata['game_type_code'],
    													'game_code' => $PBdata['game_code'],
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
                        /*
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'bet_time' => $transaction_lists_old_row['bet_time'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'game_code' => $transaction_lists_old_row['game_code'],
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
						*/
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						/*
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						*/
						ad($Bdata);
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
	
	private function spsb2_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
	    $url = $arr['APIUrl'];
	    $url .= '/api/Sport';
        $param_array = array(
            'Cmd' => "GetUserReport",
            'VendorId' => $arr['VendorID'],
            'UpAccount' => $arr['UpAccount'],
            'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
            'Lang' => $arr['Lang'],
            'StartTime' => $start_date,
            'EndTime' => $end_date,
            'CurrentPage' => $page_id,
            'PageSize' => 1000,
        );
        $response = $this->curl_post($url,$param_array);
		return $response;
	}
}