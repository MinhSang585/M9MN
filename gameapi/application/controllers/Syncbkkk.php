<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syncbkkk extends MY_Controller {
	var $player_winloss_list = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all($index = NULL)
	{
		set_time_limit(0);
		//Prepare member list
		$member_lists = $this->player_model->get_player_list_array();
		if($index = 1){
		    $this->jk($member_lists);
			$this->rtg($member_lists);
			$this->sbo_sb($member_lists);
			$this->sbo_vs($member_lists);
			$this->sbo_games($member_lists);
			
			$this->jk($member_lists);
			$this->rtg($member_lists);
			$this->sbo_sb($member_lists);
			$this->sbo_vs($member_lists);
			$this->sbo_games($member_lists);
			
		}else if($index = 2){
			
		}
		
	}

	public function cmd($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'CMD';
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
		$game_data = $this->game_model->get_game_data($provider_code);
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
									        'bet_amount_valid' => trim($result_row['validbet']),
									        'payout_amount' => 0,
									        'promotion_amount' => trim($result_row['bet']),
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
	
	public function dt($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DT';
		$result_type = GAME_SLOTS;
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
											        'promotion_amount' => trim($result_row['betPrice']),
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
											    	$PBdata['promotion_amount'] = trim($result_row['betPrice']);
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
	
	public function eb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'EB';
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
	
	public function evoplay($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'EVOP';
		$result_type = GAME_SLOTS;
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
								}else{
									$PBdata['bet_update_info'] = json_encode($result_array[$i]);
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
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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
	
	public function ibc($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'IBC';
		$result_type = GAME_SPORTSBOOK;
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
	
	public function jdb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'JDB';
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
									        'bet_time' => strtotime(trim($result_row['gameDate'])),
									        'game_time' => strtotime(trim($result_row['gameDate'])),
									        'report_time' => strtotime(trim($result_row['lastModifyTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => $payout_amount,
									        'promotion_amount' => $bet_amount_valid,
									        'payout_time' => strtotime(trim($result_row['lastModifyTime'])),
									        'sattle_time' => strtotime(trim($result_row['lastModifyTime'])),
											'compare_time' => strtotime(trim($result_row['lastModifyTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['playerId'],
									        'player_id' => $member_lists[$exact_username],
									    );

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
		$game_data = $this->game_model->get_game_data($provider_code);
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
									        'bet_time' => strtotime(trim($result_row['gameDate'])),
									        'game_time' => strtotime(trim($result_row['gameDate'])),
									        'report_time' => strtotime(trim($result_row['lastModifyTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => $payout_amount,
									        'promotion_amount' => $bet_amount_valid,
									        'payout_time' => strtotime(trim($result_row['lastModifyTime'])),
									        'sattle_time' => strtotime(trim($result_row['lastModifyTime'])),
											'compare_time' => strtotime(trim($result_row['lastModifyTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );

										$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];

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

		$game_data = $this->game_model->get_game_data($provider_code);
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
											}else{
												$PBdata['bet_update_info'] = json_encode($result_array['data']['Game'][$i]);
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
											}else{
												$PBdata['bet_update_info'] = json_encode($result_array['data']['Jackpot'][$i]);
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
	
	public function mg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'MG';
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
											if(strpos($game_real_code, 'SMG_TITANIUMLIVEGAMES_BACCARAT') !== false || strpos($game_real_code, 'SMG_TITANIUMLIVEGAMES_MP_BACCARAT') !== false){
												$game_code = "Baccarat";
											  	$game_type_code = GAME_LIVE_CASINO;
											}else if(strpos($game_real_code, 'SMG_TITANIUMLIVEGAMES_ROULETTE') !== false){
												$game_code = "Roulette";
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
	
	public function pgsoft($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PGSF';
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
		$game_data = $this->game_model->get_game_data($provider_code);
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
					$response = $this->pp_connect($arr, $start_time);
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
									}else{
										$PBdata['bet_update_info'] = json_encode($result_array[$i]);
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
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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

	public function rtg($member_lists = NULL){
	    set_time_limit(0);
		$member_lists = $this->player_model->get_player_list_array();
		
		set_time_limit(0);
		$provider_code = 'RTG';
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
												}else{
													$PBdata['bet_update_info'] = json_encode($result_row);
											        $PBdata['update_type'] = SYNC_DEFAULT;
													array_push($BUdata, $PBdata);
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
	
	public function sbo_sb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SBO';
		$result_type = GAME_SPORTSBOOK;
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
	
	public function sa($member_lists = NULL){
		//10:00
		
		set_time_limit(0);
		$provider_code = 'SA';
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
											$bet_amount_valid = trim($result_row['BetAmount']) * 1000;
											$win_loss = trim($result_row['ResultAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['BetAmount']);
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
	
	public function sx($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SX';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_INSERT;

		$game_data = $this->report_model->get_game_data($provider_code);
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
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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
		$game_data = $this->report_model->get_game_data($provider_code);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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

		$game_data = $this->report_model->get_game_data($provider_code);
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
	
	private function jdb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $action = NULL){
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$url = $arr['APIUrl'];
		$start_date = date('d-m-Y H:i:s', $start_time);
		$end_date = date('d-m-Y H:i:s', $end_time);
		
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
	
	private function pp_connect($arr = NULL, $start_time = NULL){
		$param_array = array(
			'login' => $arr['secureLogin'],
			'password' => $arr['hash'],
			'timepoint' => $start_time . '000'
		);
		
		$url = $arr['ReportUrl'] . '/DataFeeds/gamerounds/finished/?' . http_build_query($param_array);
		
		//Get response from curl
		$response = $this->curl_get($url);
		
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
	
	private function sx_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
		$url = $arr['ReportUrl'];
		$start_date = date('Y-m-d', strtotime('+0 hours', $start_time))."T".date('H:i:s', strtotime('+0 hours', $start_time))."+08:00";
		$end_date = date('Y-m-d', strtotime('+0 hours', $end_time))."T".date('H:i:s', strtotime('+0 hours', $end_time))."+08:00";

		//Request time follow server time +08:00
		if($type == "InsertRecord"){
			$url .= '/fetch/getTransactionByUpdateDate';
			$param_array = array(
				'cert' => $arr['Cert'],
				'agentId' => $arr['agentId'],
				'platform'  => $arr['Platform'],
				"timeFrom"	=> $start_date,
			);
		}else if($type == "UpdateRecord"){
			$url .= '/fetch/getTransactionByTxTime';
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
			$url .= '/fetch/getSummaryByTxTimeHour';
		}
		$response = $this->curl_post($url, $param_array,null,"gzip");
		return $response;
	}
}