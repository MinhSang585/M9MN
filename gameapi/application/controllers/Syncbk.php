<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syncbk extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all()
	{
		set_time_limit(0);
		//Prepare member list
		$member_lists = $this->player_model->get_player_list_array();
		$this->sx($member_lists);
		$this->sexy_backup($member_lists);
		$this->sexy_secure($member_lists);
		
		$this->sx($member_lists);
		$this->sexy_backup($member_lists);
		$this->sexy_secure($member_lists);
	}

	public function ab($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'AB';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

					$game_code_data = array(
						'101' => "Baccarat",
						'102' => "Baccarat",
						'103' => "Baccarat",
						'104' => "Baccarat",
						'201' => "Sicbo",
						'301' => "Dragon Tiger",
						'401' => "Roulette",
						'501' => "Pok Deng",
						'801' => "Bull Bull",
						'901' => "Win Three Cards",
					);
					$response = $this->ab_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['histories']) &&  sizeof($result_array['histories'])>0){
									foreach($result_array['histories'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['client']));
										$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_row['betAmount']) * 1000;
											$bet_amount_valid = trim($result_row['validAmount']) * 1000;
											$win_loss = trim($result_row['winOrLoss']) * 1000;
										}else{
											$bet_amount = trim($result_row['betAmount']);
											$bet_amount_valid = trim($result_row['validAmount']);
											$win_loss = trim($result_row['winOrLoss']);
										}

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameType'])]) ? $game_code_data[trim($result_row['gameType'])] : "Other"),
									        'game_real_code' => trim($result_row['gameType']),
									        'bet_id' => trim($result_row['betNum']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['gameRoundStartTime'])),
									        'report_time' => strtotime(trim($result_row['gameRoundEndTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['gameRoundEndTime'])),
									        'sattle_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											'compare_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameResult']),
									        'table_id' => trim($result_row['tableName']),
									        'round' => trim($result_row['gameRoundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['client'],
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

	private function ab_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//GMT-8
		$url = $arr['APIUrl'];
		$url .= '/betlog_pieceof_histories_in30days';

		$start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
		$param_array = array(
			"random" => mt_rand(),
			"startTime" => $start_date,
			"endTime" => $end_date,
			"agent" => $arr['AgentId'],
		);
		$real_param = http_build_query($param_array);
		$this->load->library('triple_des');
		$encrypt_data = $this->triple_des->encrypt_text($this->triple_des->pkcs5_pad($real_param, 8), $arr['DESKey']);
		$to_sign = $encrypt_data . $arr['MD5Key'];
		$param_array_2 = array(
			"data" => $encrypt_data,
			"sign" => base64_encode(md5($to_sign, TRUE)),
			"propertyId" => $arr['PropertyID']
		);
		$response = $this->curl_post($url, $param_array_2);
		return $response;
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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
										}else{
											$PBdata['bet_update_info'] = json_encode($result_row);
									        $PBdata['update_type'] = SYNC_DEFAULT;
											array_push($BUdata, $PBdata);
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
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_CMD,$BUdataRow['bet_id'],$BUdataRow);
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
	
	public function dg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DG';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+5 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
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
											
											if($arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "LAK2"){
												$bet_amount = trim($result_row['betPoints']) * 1000;
												$bet_amount_valid = ((isset($result_row['availableBet'])) ? trim($result_row['availableBet']) : 0) * 1000;
												$win_loss = ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss'])-trim($result_row['betPoints']) : 0) * 1000;
											}else{
												$bet_amount = trim($result_row['betPoints']);
												$bet_amount_valid = ((isset($result_row['availableBet'])) ? trim($result_row['availableBet']) : 0);
												$win_loss = ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss'])-trim($result_row['betPoints']) : 0);
											}


											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => GAME_LIVE_CASINO,
										        'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
										        'game_result_type' => $result_type,
										        'game_code' => $game_code,
										        'game_real_code' => trim($result_row['gameId']),
										        'bet_id' => trim($result_row['id']),
										        'bet_time' => strtotime(trim($result_row['betTime'])),
										        'game_time' => strtotime(trim($result_row['calTime'])),
										        'report_time' => strtotime(trim($result_row['calTime'])),
										        'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount_valid,
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'payout_time' => strtotime(trim($result_row['calTime'])),
										        'sattle_time' => strtotime(trim($result_row['calTime'])),
												'compare_time' => strtotime(trim($result_row['calTime'])),
												'created_date' => time(),
										        'win_loss' =>  $win_loss,
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
												$PBdata['payout_amount'] = $PBdata['win_loss'];
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+0 days' ,$end_time);
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
										        'win_loss' => trim($result_row['payout']) - trim($result_row['bet']),
										        'table_id' => "",
										        'round' => trim($result_row['roundNo']),
										        'subround'  => "",
										        'bet_code' => json_encode($result_row['betMap'],true),
								        		'game_result' => json_encode($result_row['payoutDetail'],true),
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => STATUS_COMPLETE,
										        'game_username' => trim($result_row['playerName']),
										        'player_id' => $member_lists[$exact_username],
										    );
										    
										    $PBdata['game_provider_type_code'] = $provider_code."_".GAME_LIVE_CASINO;

											if($PBdata['win_loss'] != 0){
										    	$PBdata['payout_amount'] = trim($result_row['payout']);
										    	if($PBdata['game_type_code'] == GAME_LIVE_CASINO){
										    		if($PBdata['game_code']  =="Roulette"){
										    			if(sizeof($result_row['betMap']) < 25){
										    				$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
										    			}
										    		}else{
										    			if(sizeof($result_row['betMap']) < 2){
										    				$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
										    			}
										    		}
										    	}else{
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
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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

	public function eb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
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
	
	public function evo($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'EVO';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
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
									foreach($result_array['data'] as $date_result_row){
										if(isset($date_result_row['games']) && sizeof($date_result_row['games'])>0){
											foreach($date_result_row['games'] as $games_result_row){
												if(isset($games_result_row['participants']) && sizeof($games_result_row['participants'])>0){
													foreach($games_result_row['participants'] as $player_result_row){
														if(isset($player_result_row['bets']) && sizeof($player_result_row['bets'])>0){
															foreach($player_result_row['bets'] as $result_row){
																$tmp_username = strtolower(trim($player_result_row['playerId']));
																$exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);
																
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
                        									    		$PBdata['promotion_amount'] = trim($result_row['stake']);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

	private function hb_connect($arr = NULL, $start_time = NULL, $end_time = NULL)
	{
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

	public function icg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'ICG';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
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
								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}
								if( ! empty($BUdata))
								{
									foreach($BUdata as $BUdataRow){
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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
				$CashOutdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
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
                                                $payout_time = strtotime('+12 hours', strtotime(trim($result_row['settlement_time'])));
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
												$bet_amount_valid = trim($result_row['stake']) * 1000;
												$win_loss = trim($result_row['winlost_amount']) * 1000;
                                            }else{
                                            	$bet_amount = trim($result_row['stake']);
												$bet_amount_valid = trim($result_row['stake']);
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
	    										'bet_amount_valid' => $bet_amount,
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
                        							}
                							    }
                							}
										}
									}
									
									if(isset($result_array['Data']['BetNumberDetails']) && sizeof($result_array['Data']['BetNumberDetails'])>0){
										foreach ($result_array['Data']['BetNumberDetails'] as $result_row){
											$tmp_username = strtolower(str_replace($arr['OperatorID'].'_','',trim($result_row['vendor_member_id'])));
											$exact_username = ((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);

											$bet_time = strtotime('+0 hours', strtotime(trim($result_row['transaction_time'])));
                                            $payout_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $game_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $report_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $sattle_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $compare_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
											
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

											$bet_time = strtotime('+0 hours', strtotime(trim($result_row['transaction_time'])));
                                            $payout_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $game_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $report_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $sattle_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
                                            $compare_time = strtotime('+0 hours', strtotime(trim($result_row['winlost_datetime'])));
											
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

	public function ibc_game_result($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'IBC';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-70 minutes', $current_time);
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
				$Mdata = array();
				$CashOutdata = array();
				$is_loop = TRUE;
				$result = NULL;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
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
					
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
				    $result = array();
				    $query = $this
        			->db
        			->select('transaction_id,bet_id,bet_match_id')
        			->where('bet_match_id != ',0) 
        			->where('game_provider_code', $provider_code)
        			->where('game_result IS NULL', null, false)
        			->where('status',STATUS_COMPLETE)
        			->limit(100)
        			->get('transaction_report');
				    if($query->num_rows() > 0)
            		{
            			$result = $query->result_array();
            		}
            		if(!empty($result)){
            			foreach($result as $row){
            			    if( ! in_array($row['bet_match_id'], $Mdata))
							{
							    $Mdata[] = $row['bet_match_id'];
							}
            			}
            		}
            	    if(!empty($Mdata)){
            	        $response = $this->ibc_result_connect($arr,$Mdata);
            	        if($response['code'] == '0'){
    						$result_array = json_decode($response['data'], TRUE);
    						if(!empty($result_array))
    						{
    							if(isset($result_array['error_code']) && ($result_array['error_code'] == '0'))
    							{
    								$DBdata['resp_data'] = json_encode($result_array);
    								$DBdata['sync_status'] = STATUS_YES;
    								if(isset($result_array['Data'])){
    								    if(sizeof($result_array['Data'])>0){
    								        foreach ($result_array['Data'] as $result_row){
    								            $BUdataRow['game_result'] = json_encode($result_row,true);
    								            $this->report_model->update_transaction_record_by_match_id($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$result_row['match_id'],$BUdataRow);
    								        }
    								    }
    								}
    							}
    						}
            	        }
            	    }else{
            	        $DBdata['sync_status'] = STATUS_YES;
            	    }
            	    
            	    $this->db->insert('game_result_logs', $DBdata);
            	    
            	    $result2 = NULL;
            	    $query = $this
        			->db
        			->select('transaction_id,bet_id,bet_info,bet_update_info')
        			->where('bet_match_id',0) 
        			->where('game_provider_code', $provider_code)
        			->where('game_result IS NULL', null, false)
        			->limit(1)
        			->get('transaction_report');
				    if($query->num_rows() > 0)
            		{
            			$result2 = $query->row_array();
            		}
            		if(!empty($result2)){
        			    if(!empty($result2['bet_update_info'])){
        			        $result2_row = json_decode($result2['bet_update_info'],true);
        			    }else{
        			        $result2_row = json_decode($result2['bet_info'],true);
        			    }
        			    $response = $this->ibc_team_name_connect($arr,$result2_row['team_id'],$result2_row['bet_type']);
        			    if($response['code'] == '0'){
    						$result_array = json_decode($response['data'], TRUE);
    						if(!empty($result_array))
    						{
    							if(isset($result_array['error_code']) && ($result_array['error_code'] == '0'))
    							{
    							    $BUdataRow['game_result'] = json_encode($result_array['Data'],true);
    							    $this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$result2['bet_id'],$BUdataRow);
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
	
	private function ibc_result_connect($arr = NULL, $match_ids = NULL){
		$url = $arr['APIUrl'];
		$url .= '/GetGameDetail';
		//Response time (GMT -4)
		$param_array = array(
			'vendor_id' => $arr['VendorID'],
			'match_ids' => implode (",", $match_ids),
		);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	
	private function ibc_team_name_connect($arr = NULL, $team_id = NULL, $bet_type = NULL){
		$url = $arr['APIUrl'];
		$url .= '/GetTeamName';
		//Response time (GMT -4)
		$param_array = array(
			'vendor_id' => $arr['VendorID'],
			'team_id' => $team_id,
			'bet_type' => $bet_type
		);
		$response = $this->curl_post($url, $param_array);
		return $response;
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
				$last_sync_time = strtotime('-10 minutes', $current_time);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

					do 
					{
						$Bdata = array();
						$BUdata = array();
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
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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

	private function jk_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL)
	{
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
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+5 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
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
										        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['createdDateUTC']))),
										        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['gameStartTimeUTC']))),
										        'report_time' => strtotime('+0 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
										        'bet_amount' => $bet_amount,
												'bet_amount_valid' => $bet_amount,
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'status' => $status,
										        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
										        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
												'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['gameEndTimeUTC']))),
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
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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

    public function n2($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'N2';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
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
	    										$exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
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
	
	public function pt($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PT';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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

					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->pt_connect($arr, $start_time, $end_time, $page_id);
						
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
											    
											     $PBdata = array(
	    									        'game_provider_code' => $provider_code,
	    									        'game_type_code' => $game_type_code,
	    									        'game_provider_type_code' => $provider_code."_".$game_type_code,
	    									        'game_result_type' => $result_type,
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
	    									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	    									        'status' => STATUS_COMPLETE,
	    									        'game_username' => trim($result_row['PLAYERNAME']),
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
							if( ! empty($BUdata))
							{
								foreach($BUdata as $BUdataRow){
									$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$BUdataRow['bet_id'],$BUdataRow);
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

	private function pt_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id){
		$url = $arr['APIUrl'];
		$url .= "/customreport/getdata/reportname/PlayerGames/startdate/".str_replace(' ','%20',(date('Y-m-d H:i:s', strtotime('-0 hours', $start_time))))."/enddate/".str_replace(' ','%20',date('Y-m-d H:i:s', strtotime('-0 hours', $end_time)))."/frozen/all/page/".$page_id."/perPage/500";
		$response = $this->curl_post_for_pt($url, $arr['EntityKeys']);
		return $response;
	}
	
	public function pt2($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'PT';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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
							if( ! empty($BUdata))
							{
								foreach($BUdata as $BUdataRow){
									$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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
    
    public function lh($member_lists = NULL){
		
		set_time_limit(0);
		$provider_code = 'LH';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = $end_time;
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
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
										foreach($result_array['results'] as $result_row){
										    $tmp_username = strtolower(trim($result_row['member_code']));
										    $exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);
										    
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
	    											if($PBdata['win_loss'] > 0){
	    												if(($PBdata['odds_currency']=="MY" && $PBdata['odds_rate'] > 0.75) || ($PBdata['odds_currency']=="ID" && $PBdata['odds_rate'] > 1.35) || ($PBdata['odds_currency']=="HK" && $PBdata['odds_rate'] > 0.75) || ($PBdata['odds_currency']=="DE" && $PBdata['odds_rate'] > 1.75)){
	    													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
	    												}
	    											}else{
	    												if(($PBdata['odds_currency']=="MY" && $PBdata['odds_rate'] < -0.75) || ($PBdata['odds_currency']=="ID" && $PBdata['odds_rate'] < -1.35) || ($PBdata['odds_currency']=="HK" && $PBdata['odds_rate'] < -0.75) || ($PBdata['odds_currency']=="DE" && $PBdata['odds_rate'] < 1.75)){
	    													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
	    												}
	    											}
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
						sleep(5);
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

	private function lh_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//request utc+0
		$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time))."Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time))."Z";		

		$url = $arr['APIUrl'];
		$url .= '/api/v2/bet-transaction/?id=&LoginName=&bet_type=&from_datetime=&to_datetime=&from_settlement_datetime=&to_settlement_datetime=&settlement_status=&from_modified_datetime='.$start_date.'&to_modified_datetime='.$end_date.'&page='.$page_id.'&page_size=1000';
		$response = $this->curl_get($url, "Authorization: Token " . $arr['PrivateToken']);
		return $response;
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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
				$is_loop = TRUE;

				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = $end_time;
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
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
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = $end_time;
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
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
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = $end_time;
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_REPORT,$db_record_start_time, $db_record_end_time);
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
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_REPORT_TIME,$BUdataRow['bet_id'],$BUdataRow);
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

	public function sp($member_lists = NULL){
		//10:00
		
		set_time_limit(0);
		$provider_code = 'SP';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+5 days' ,$end_time);
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

					$game_code_data = array(
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect($arr, $start_time, $end_time);
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
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == $arr['UPrefix']) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

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
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
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
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect_backup($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);

							if(isset($result_array['status']) && $result_array['status'] == '0000')
							{
								$DBdata['sync_status'] = STATUS_YES;
								/*
								if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);

									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : "Other"),
									        'game_real_code' => trim($result_row['gameCode']),
									        'bet_id' => trim($result_row['ID']),
									        'bet_time' => strtotime(trim($result_row['betTime'])),
									        'game_time' => strtotime(trim($result_row['txTime'])),
									        'report_time' => strtotime(trim($result_row['updateTime'])),
									        'bet_amount' => trim($result_row['betAmount']),
									        'bet_amount_valid' => trim($result_row['realBetAmount']),
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['updateTime'])),
									        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['betType']),
									        'game_result' => trim($result_row['gameInfo']),
									        'table_id' => trim($result_row['platformTxId']),
									        'round' => trim($result_row['roundId']),
									        'subround'  => "",
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['userId'],
									        'player_id' => $member_lists[$exact_username],
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
										}
									}
								}
								*/
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					/*
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
						//promotion
						foreach($Bdata as $BdataRow){
							if($BdataRow['status'] == STATUS_COMPLETE){
								$this->report_model->update_promotion_amount($BdataRow,$result_promotion_reset);
							}
						}
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
						}
						foreach($BUdata as $BUdataRow){
							if($BUdataRow['status'] == STATUS_COMPLETE){
								$this->report_model->update_promotion_amount($BUdataRow,$result_promotion_reset);
							}
						}
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-180 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
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
						'MX-LIVE-009' => "Roulette",
						'MX-LIVE-007' => "SicBo",
						'MX-LIVE-006' => "Dragon Tiger",
						'MX-LIVE-001' => "Baccarat",
						'MX-LIVE-003' => "Baccarat",
						'MX-LIVE-010' => "Red Blue Duel",
					);
					$response = $this->sx_connect_update($arr, $start_time, $end_time);
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
									foreach($result_array['transactions'] as $result_row){
									    $tmp_username = strtolower(trim($result_row['userId']));
										$exact_username = ((substr($tmp_username, 0, strlen($arr['UPrefix'])) == $arr['UPrefix']) ? substr($tmp_username, strlen($arr['UPrefix'])) : $tmp_username);

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

	private function sx_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//Request time follow server time +08:00
		$url = $arr['ReportUrl'];
		$start_date = date('Y-m-d', strtotime('+0 hours', $start_time))."T".date('H:i:s', strtotime('+0 hours', $start_time))."+08:00";
		$end_date = date('Y-m-d', strtotime('+0 hours', $end_time))."T".date('H:i:s', strtotime('+0 hours', $end_time))."+08:00";
    
    
        $param_array2 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'SEXYBCRT',
			"timeFrom"	=> $start_date,
		);
		
		$param_array3 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'YL',
			"timeFrom"	=> $start_date,
		);
		
		$param_array4 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'VENUS',
			"timeFrom"	=> $start_date,
		);
		
		$param_array5 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'KINGMAKER',
			"timeFrom"	=> $start_date,
		);
		
		$param_array6 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'BG',
			"timeFrom"	=> $start_date,
		);
		
		$param_array7 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'YL',
			"timeFrom"	=> $start_date,
		);
		
		
		$param_array8 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'E1SPORT',
			"timeFrom"	=> $start_date,
		);
		
		$param_array9 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'YL',
			"timeFrom"	=> $start_date,
		);
		
		$param_array10 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'E1SPORT',
			"timeFrom"	=> $start_date,
		);
		
		
		
		
		$param_array = array(
			'cert' => $arr['Cert'],
			'agentId' => $arr['agentId'],
			'platform'  => 'SEXYBCRT',
			"timeFrom"	=> $start_date,
		);
		$url .= '/fetch/getTransactionByUpdateDate';
		$response = $this->curl_post($url, $param_array,null,"gzip");
		sleep(2);
		$response2 = $this->curl_post($url, $param_array2,null,"gzip");
		sleep(2);
		$response3 = $this->curl_post($url, $param_array3,null,"gzip");
		sleep(2);
		$response4 = $this->curl_post($url, $param_array4,null,"gzip");
		sleep(2);
		$response5 = $this->curl_post($url, $param_array5,null,"gzip");
		sleep(2);
		$response6 = $this->curl_post($url, $param_array6,null,"gzip");
		sleep(2);
		$response7 = $this->curl_post($url, $param_array7,null,"gzip");
		sleep(2);
		$response8 = $this->curl_post($url, $param_array8,null,"gzip");
		sleep(2);
		$response9 = $this->curl_post($url, $param_array9,null,"gzip");
		sleep(2);
		$response10 = $this->curl_post($url, $param_array10,null,"gzip");
		sleep(2);
		return $response;
	}

	private function sx_connect_backup($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		$url = $arr['ReportUrl'];
		$start_date = date('Y-m-d', strtotime('+0 hours', $start_time))."T".date('H', strtotime('+0 hours', $start_time))."+08:00";
		$end_date = date('Y-m-d', strtotime('+0 hours', $end_time))."T".date('H', strtotime('+0 hours', $end_time))."+08:00";
		
		$param_array2 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'SEXYBCRT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		
		$param_array3 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array4 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'VENUS',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array5 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'KINGMAKER',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array6 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'BG',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array7 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array8 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'E1SPORT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array9 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array10 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'E1SPORT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		
		$param_array = array(
			'cert' => $arr['Cert'],
			'agentId' => $arr['agentId'],
			'platform'  => 'SEXYBCRT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$url .= '/fetch/getSummaryByTxTimeHour';
		$response = $this->curl_post($url, $param_array,null,"gzip");
		sleep(2);
		$response2 = $this->curl_post($url, $param_array2,null,"gzip");
		sleep(2);
		$response3 = $this->curl_post($url, $param_array3,null,"gzip");
		sleep(2);
		$response4 = $this->curl_post($url, $param_array4,null,"gzip");
		sleep(2);
		$response5 = $this->curl_post($url, $param_array5,null,"gzip");
		sleep(2);
		$response6 = $this->curl_post($url, $param_array6,null,"gzip");
		sleep(2);
		$response7 = $this->curl_post($url, $param_array7,null,"gzip");
		sleep(2);
		$response8 = $this->curl_post($url, $param_array8,null,"gzip");
		sleep(2);
		$response9 = $this->curl_post($url, $param_array9,null,"gzip");
		sleep(2);
		$response10 = $this->curl_post($url, $param_array10,null,"gzip");
		sleep(2);
		return $response;
	}

	private function sx_connect_update($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		$url = $arr['ReportUrl'];
		$start_date = date('Y-m-d', strtotime('+0 hours', $start_time))."T".date('H:i:s', strtotime('+0 hours', $start_time))."+08:00";
		$end_date = date('Y-m-d', strtotime('+0 hours', $end_time))."T".date('H:i:s', strtotime('+0 hours', $end_time))."+08:00";
    
    
        
        $param_array2 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'SEXYBCRT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		
		$param_array3 = array(
			'cert' => "DiNbBV3kOIsyOiukjYI",
			'agentId' => 'goldbar916',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array4 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'VENUS',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array5 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'KINGMAKER',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array6 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'BG',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array7 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array8 = array(
			'cert' => "RYKFrjbjytBnOloeU9B",
			'agentId' => 'sandsintstgag',
			'platform'  => 'E1SPORT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array9 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'YL',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$param_array10 = array(
			'cert' => "pH8kJp318bOIRgg5qLf",
			'agentId' => 'goldenera',
			'platform'  => 'E1SPORT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		
		$param_array = array(
			'cert' => $arr['Cert'],
			'agentId' => $arr['agentId'],
			'platform'  => 'SEXYBCRT',
			"startTime"	=> $start_date,		
			"endTime"	=> $end_date,
		);
		$url .= '/fetch/getTransactionByTxTime';
		$response = $this->curl_post($url, $param_array,null,"gzip");
		sleep(2);
		$response2 = $this->curl_post($url, $param_array2,null,"gzip");
		sleep(2);
		$response3 = $this->curl_post($url, $param_array3,null,"gzip");
		sleep(2);
		$response4 = $this->curl_post($url, $param_array4,null,"gzip");
		sleep(2);
		$response5 = $this->curl_post($url, $param_array5,null,"gzip");
		sleep(2);
		$response6 = $this->curl_post($url, $param_array6,null,"gzip");
		sleep(2);
		$response7 = $this->curl_post($url, $param_array7,null,"gzip");
		sleep(2);
		$response8 = $this->curl_post($url, $param_array8,null,"gzip");
		sleep(2);
		$response9 = $this->curl_post($url, $param_array9,null,"gzip");
		sleep(2);
		$response10 = $this->curl_post($url, $param_array10,null,"gzip");
		return $response;
	}

	public function wm($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'WM';
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
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
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
							if(isset($result_array['errorCode']) && ($result_array['errorCode'] == '0' OR $result_array['errorCode'] == '107'))
							{
								$DBdata['sync_status'] = STATUS_YES;
								for($i=0;$i<sizeof($result_array['result']);$i++)
								{
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
									}else{
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
									}else{
										$PBdata['bet_update_info'] = json_encode($result_array['result'][$i]);
								        $PBdata['update_type'] = SYNC_DEFAULT;
										array_push($BUdata, $PBdata);
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
	
	public function rtg($member_lists = NULL){
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
				$is_loop = TRUE;

				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);

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

	public function rtg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL, $method = NULL,$token = NULL){
		if($method == "RetrieveRecord"){
			$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time))."Z";
			$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time))."Z";
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
}