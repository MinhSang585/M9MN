<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Winlosscreate extends MY_Controller {

	var $player_winloss_list = array();

	var $player_winloss_list_record = array();

	var $player_winloss_list_monthly = array();

	var $player_level = array();

	

	public function __construct()

	{

		parent::__construct();

		$this->load->library('rng');

		$this->load->model(array('extract_model'));

	}

	

	public function win_loss_report_cron_test(){

	    $member_lists = $this->player_model->get_player_list_array();

	    //$this->cash_cron($member_lists);

		$this->deposit_cron($member_lists);

		$this->withdrawal_cron($member_lists);

	}

	

	public function win_loss_report_cron(){

		set_time_limit(0);

		$member_lists = $this->player_model->get_player_list_array();

		$this->win_loss_cron();

		$this->cash_cron($member_lists);

		$this->deposit_cron($member_lists);

		$this->withdrawal_cron($member_lists);

		$this->deposit_cron($member_lists);

		$this->withdrawal_cron($member_lists);

		$this->deposit_cron($member_lists);

		$this->withdrawal_cron($member_lists);

		$this->deposit_cron($member_lists);

		$this->withdrawal_cron($member_lists);

		$this->player_upgrade();

		$this->promotion_current_turnover();

		$this->player_current_turnover();

		$this->delete_all_empty_win_loss();

	}



	public function win_loss_cron(){

		set_time_limit(0);

		$dbprefix = $this->db->dbprefix;

		$provider_code = 'BECR';

		$result_type = 'WL';

		$sync_type = SYNC_TYPE_ALL;

		$player_winloss_list_game_code = array();

		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);

		if(!empty($game_result_data))

		{

			if($game_result_data['sync_lock'] == STATUS_INACTIVE){

				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);

				

				$result_key = NULL;

				$result_data = NULL;



				$this->db->select('win_loss_logs_id');

				$this->db->order_by('win_loss_logs_id',"DESC");

				$this->db->limit(1);

				$queryKey = $this->db->get('win_loss_logs');

				if($queryKey->num_rows() > 0)

				{

					$result_key = $queryKey->row_array();

					$last_id = 	$result_key['win_loss_logs_id'];					



					$this->db->where('win_loss_logs_id <= ',$last_id);

					$this->db->order_by('win_loss_logs_id',"ASC");

					$query = $this->db->get('win_loss_logs');

					if($query->num_rows() > 0)

					{

						$result_data = $query->result_array();

						$this->player_winloss_list_record = $result_data;
						//ad($result_data);

						foreach($result_data as $result_row){

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_provider_code'] = $result_row['game_provider_code'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_type_code'] = $result_row['game_type_code'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_provider_type_code'] = $result_row['game_provider_code'] . "_" .$result_row['game_type_code'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['payout_time'] = (strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])));

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['total_bet'] += $result_row['total_bet'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['bet_amount'] += bcdiv($result_row['bet_amount'],1,2);

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['bet_amount_valid'] += bcdiv($result_row['bet_amount_valid'],1,2);

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['win_loss'] += bcdiv($result_row['win_loss'],1,2);





							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['payout_time'] = (strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])));

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['deposit_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['withdrawals_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['point_in_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['point_out_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['bonus_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['promotion_amount'] += 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['bet_amount_valid'] += bcdiv($result_row['bet_amount_valid'],1,2);

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['bet_time'])))]['win_loss'] += bcdiv($result_row['win_loss'],1,2);





							//WIN LOSS BY GAME CODE

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['game_provider_code'] = $result_row['game_provider_code'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['game_type_code'] = $result_row['game_type_code'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['game_code'] = $result_row['game_code'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['game_provider_type_code'] = $result_row['game_provider_code'] . "_" .$result_row['game_type_code'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['player_id'] = $result_row['player_id'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['payout_time'] = (strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])));

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['total_bet'] += $result_row['total_bet'];

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['bet_amount'] += bcdiv($result_row['bet_amount'],1,2);

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['bet_amount_valid'] += bcdiv($result_row['bet_amount_valid'],1,2);

							$player_winloss_list_game_code[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['bet_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']][$result_row['game_code']]['win_loss'] += bcdiv($result_row['win_loss'],1,2);



						}

						$this->db->trans_start();

						if(!empty($this->player_winloss_list) && sizeof($this->player_winloss_list)>0){

							foreach($this->player_winloss_list as $player_winlost_row){

								if(!empty($player_winlost_row) && sizeof($player_winlost_row)>0){

									foreach($player_winlost_row as $each_player_winlost_row){

										$this->report_model->add_win_loss($each_player_winlost_row);

		                				$this->report_model->add_total_win_loss($each_player_winlost_row);

									}

								}

							}

						}



						if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){

							foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){

								if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){

									foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){

										$this->report_model->add_total_win_loss_monthly($each_player_winloss_list_monthly_row);

									}

								}

							}

						}



						if(!empty($player_winloss_list_game_code) && sizeof($player_winloss_list_game_code)>0){

							foreach($player_winloss_list_game_code as $player_winloss_list_game_code_row){

								if(!empty($player_winloss_list_game_code_row) && sizeof($player_winloss_list_game_code_row)>0){

									foreach($player_winloss_list_game_code_row as $each_player_winloss_list_game_code_row){

										if(!empty($each_player_winloss_list_game_code_row) && sizeof($each_player_winloss_list_game_code_row)>0){

											foreach($each_player_winloss_list_game_code_row as $single_player_winloss_list_game_code_row){

												$DBdata = array(

													'report_date' => $single_player_winloss_list_game_code_row['payout_time'],

													'game_provider_code' => $single_player_winloss_list_game_code_row['game_provider_code'],

													'game_type_code' => $single_player_winloss_list_game_code_row['game_type_code'],

													'game_code' => $single_player_winloss_list_game_code_row['game_code'],

													'game_provider_type_code' => $single_player_winloss_list_game_code_row['game_provider_type_code'],

													'player_id' => $single_player_winloss_list_game_code_row['player_id'],

													'total_bet' => $single_player_winloss_list_game_code_row['total_bet'],

													'bet_amount' => $single_player_winloss_list_game_code_row['bet_amount'],

													'bet_amount_valid' => $single_player_winloss_list_game_code_row['bet_amount_valid'],

													'win_loss' => $single_player_winloss_list_game_code_row['win_loss'],

												);



												$this->db->query("UPDATE {$dbprefix}win_loss_report_by_game_code SET total_bet = (total_bet + ?), bet_amount = (bet_amount + ?), bet_amount_valid = (bet_amount_valid + ?), win_loss = (win_loss + ?)  WHERE player_id = ? AND game_provider_code = ? AND game_type_code = ? AND game_provider_type_code = ? AND report_date = ? AND game_code = ? LIMIT 1", array($DBdata['total_bet'], $DBdata['bet_amount'], $DBdata['bet_amount_valid'], $DBdata['win_loss'], $DBdata['player_id'], $DBdata['game_provider_code'], $DBdata['game_type_code'], $DBdata['game_provider_type_code'], $DBdata['report_date'], $DBdata['game_code']));

												$afftectedRows = $this->db->affected_rows();

												if($afftectedRows == 0){

													$this->db->insert('win_loss_report_by_game_code', $DBdata);

												}

											}

										}

									}

								}

							}

						}

						$this->db->where('win_loss_logs_id <= ',$last_id);

						$this->db->delete('win_loss_logs');

						$this->db->trans_complete();

						unset($this->player_winloss_list);

						unset($this->player_winloss_list_monthly);

						unset($player_winloss_list_game_code);

					}

					$query->free_result();

				}

				$queryKey->free_result();



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



	public function promotion_current_turnover(){

		set_time_limit(0);

		$dbprefix = $this->db->dbprefix;
		
		if(!empty($this->player_winloss_list_record)){

			$promotion_data = array();

			$this->db->select('player_promotion_id,player_id,starting_date,complete_date,current_amount,game_ids');

			$this->db->where_in('status',array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));

			$query = $this->db->get('player_promotion');

			if($query->num_rows() > 0)

			{

				$promotion_data = $query->result_array();

			}

			$query->free_result();



			$player_promotion_array = array();



			if(!empty($promotion_data)){

				foreach($promotion_data as $promotion_data_row){

					if(!isset($player_promotion_array[$promotion_data_row['player_id']])){

						$player_promotion_array[$promotion_data_row['player_id']] = array(

							"is_calculate" => false,

							"player_promotion_group" => array(),

						);

					}	



					$promotion_data_temp = array(

						'player_promotion_id' => $promotion_data_row['player_promotion_id'],

						'player_id' => $promotion_data_row['player_id'],

						'starting_date' => $promotion_data_row['starting_date'],

						'complete_date' => $promotion_data_row['complete_date'],

						'current_amount' => $promotion_data_row['current_amount'],

						'game_ids' => $promotion_data_row['game_ids'],

						'is_calculate' => false,

					);

					array_push($player_promotion_array[$promotion_data_row['player_id']]['player_promotion_group'], $promotion_data_temp);

				}

			}



			if(!empty($player_promotion_array)){

				foreach($this->player_winloss_list_record as $player_winloss_list_record_row){

					if($player_winloss_list_record_row['bet_amount_valid'] > 0){

						if(isset($player_promotion_array[$player_winloss_list_record_row['player_id']])){

							for($i=0; $i<sizeof($player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group']); $i++){

								if(!empty($player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['complete_date']) &&  $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['complete_date'] > 0){

									if($player_winloss_list_record_row['bet_time'] >= $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['starting_date'] && $player_winloss_list_record_row['bet_time'] <= $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['complete_date']){

										if(strpos($player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['game_ids'], ",".$player_winloss_list_record_row['game_provider_code']."_".$player_winloss_list_record_row['game_type_code'].",") !== false){

										    $player_promotion_array[$player_winloss_list_record_row['player_id']]['is_calculate'] = true;

										    $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['is_calculate'] = true;

										    $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['current_amount'] += $player_winloss_list_record_row['bet_amount_valid'];

										}

									}

								}else{

									if($player_winloss_list_record_row['bet_time'] >= $player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['starting_date']){

									    if(strpos($player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['game_ids'], ",".$player_winloss_list_record_row['game_provider_code']."_".$player_winloss_list_record_row['game_type_code'].",") !== false){

    										$player_promotion_array[$player_winloss_list_record_row['player_id']]['is_calculate'] = true;

    										$player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['is_calculate'] = true;

    										$player_promotion_array[$player_winloss_list_record_row['player_id']]['player_promotion_group'][$i]['current_amount'] += $player_winloss_list_record_row['bet_amount_valid'];

									    }

									}

								}

							}

						}

					}

				}

			}

			if(!empty($player_promotion_array)){

				foreach($player_promotion_array as $player_promotion_array_row){

					if(!empty($player_promotion_array_row['is_calculate'])){

						if(!empty($player_promotion_array_row['player_promotion_group'])){

							for($i=0;$i<sizeof($player_promotion_array_row['player_promotion_group']);$i++){

								if(!empty($player_promotion_array_row['player_promotion_group'][$i]['is_calculate'])){

									$this->db->query("UPDATE {$dbprefix}player_promotion SET current_amount = ? WHERE player_promotion_id = ? LIMIT 1", array($player_promotion_array_row['player_promotion_group'][$i]['current_amount'], $player_promotion_array_row['player_promotion_group'][$i]['player_promotion_id']));

								}

							}

						}

					}

				}

			}

		}

	}

	

	public function player_current_turnover(){

		set_time_limit(0);

		$dbprefix = $this->db->dbprefix;

		if(!empty($this->player_winloss_list_record)){

			$dbprefix = $this->db->dbprefix;

			$player_lists = array();



			$dbprefix = $this->db->dbprefix;

			$player_query = $this->db->query("SELECT player_id FROM {$dbprefix}players ORDER BY player_id DESC LIMIT 1");

			if($player_query->num_rows() > 0) {

				$player_row = $player_query->row();

				$last_player_id = $player_row->player_id;

			}



			$player_lists = array();

			$player_query = $this->db->query("SELECT player_id, turnover_start_date, turnover_total_current FROM {$dbprefix}players WHERE player_id <= ?", array($last_player_id));



			if($player_query->num_rows() > 0) {

				foreach($player_query->result() as $player_row) {

					$player_lists[$player_row->player_id] = array(

						'player_id' => $player_row->player_id,

						'turnover_start_date' => $player_row->turnover_start_date,

						'turnover_total_current' => $player_row->turnover_total_current,

						'current_turnover' => 0,

						'is_new_update_turnover' => 0,

					);

				}

			}



			foreach($this->player_winloss_list_record as $player_winloss_list_record_row){

				if(isset($player_lists[$player_winloss_list_record_row['player_id']])){

				    if($player_lists[$player_winloss_list_record_row['player_id']]['turnover_total_current'] > 0){

				        if($player_winloss_list_record_row['bet_time'] >= $player_lists[$player_winloss_list_record_row['player_id']]['turnover_start_date']){

    						$player_lists[$player_winloss_list_record_row['player_id']]['turnover_total_current'] -= $player_winloss_list_record_row['bet_amount_valid'];

    						$player_lists[$player_winloss_list_record_row['player_id']]['current_turnover'] += $player_winloss_list_record_row['bet_amount_valid'];

    						$player_lists[$player_winloss_list_record_row['player_id']]['is_new_update_turnover'] = 1;

    					}   

				    }

				}

			}

			

			

			if(!empty($player_lists)){

				foreach($player_lists as $player_lists_row){

					if($player_lists_row['is_new_update_turnover']){

						if($player_lists_row['turnover_total_current'] > 0){

							$this->db->query("UPDATE {$dbprefix}players SET turnover_total_current = (turnover_total_current - ?)  WHERE player_id = ? LIMIT 1", array($player_lists_row['current_turnover'], $player_lists_row['player_id']));

						}else{

							$this->db->query("UPDATE {$dbprefix}players SET turnover_total_current = 0 WHERE player_id = ? LIMIT 1", array($player_lists_row['player_id']));

						}

					}

				}

			}

		}

	}



	public function cash_cron($member_lists = NULL){

		set_time_limit(0);

		$table_player = $this->db->dbprefix . "players";

		$provider_code = 'BECR';

		$result_type = 'CC';

		$sync_type = SYNC_TYPE_ALL;

		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);

		if(!empty($game_result_data))

		{

			if($game_result_data['sync_lock'] == STATUS_INACTIVE){

				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);

				$result_key = NULL;

				$result_data = NULL;

				$current_time = time();

				$last_sync_time = strtotime('-6 minutes', $current_time);

				$page_id = 0;

				$last_id = 0;

				$next_id = 0;

 				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);

				if( ! empty($sync_data))

				{

					$last_sync_time = $sync_data['end_time'];

					$next_id = $sync_data['next_id'];

				}



				$initial_time = date('Y-m-d H:i:00', $last_sync_time);

				$start_time = strtotime($initial_time);

				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+6 minutes', strtotime($initial_time))));

                //$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+1440 minutes', strtotime($initial_time))));

				if($end_time <= strtotime('-0 minutes', $current_time))

				{

					$DBdata = array(

						'game_provider_code' => $provider_code,

						'game_result_type' => $result_type,

						'game_sync_type' => $sync_type,

						'start_time' => $start_time,

						'end_time' => $end_time,

						'sync_time' => time(),

						'sync_status' => STATUS_YES,

						'page_id' => $page_id,

						'next_id' => $next_id,

						'resp_data' => '',

					);



					$this->db->where_in('transfer_type',array(TRANSFER_POINT_IN, TRANSFER_POINT_OUT, TRANSFER_ADJUST_IN,TRANSFER_ADJUST_OUT,TRANSFER_PROMOTION,TRANSFER_BONUS));

					$this->db->where('cash_transfer_id > ',$next_id);

					$this->db->order_by('cash_transfer_id',"DESC");

					$this->db->limit(1);

					$queryKey = $this->db->get('cash_transfer_report');

					if($queryKey->num_rows() > 0)

					{

						$result_key = $queryKey->row_array();

						$last_id = 	$result_key['cash_transfer_id'];

						$DBdata['next_id'] = $last_id;



						$this->db->select('transfer_type, username, deposit_amount, withdrawal_amount, report_date');

						$this->db->where_in('transfer_type',array(TRANSFER_POINT_IN, TRANSFER_POINT_OUT, TRANSFER_ADJUST_IN,TRANSFER_ADJUST_OUT,TRANSFER_PROMOTION,TRANSFER_BONUS));

						$this->db->where('cash_transfer_id > ',$next_id);

						$this->db->where('cash_transfer_id <= ',$last_id);

						$this->db->order_by('cash_transfer_id',"ASC");

						$query = $this->db->get('cash_transfer_report');

						if($query->num_rows() > 0)

						{

							$result_data = $query->result_array();

							foreach($result_data as $result_row){

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['player_id'] = $member_lists[strtolower($result_row['username'])];

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])));

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['bonus_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['promotion_amount'] += 0;

								//Devider

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_offline_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_online_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_online_online_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_online_credit_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_online_hypermart_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_point_amount'] += 0;

								

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_offline_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_online_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_point_amount'] += 0;

								

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_in_amount'] += 0;

								$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_out_amount'] += 0;

								

								

								

								if($result_row['transfer_type'] == TRANSFER_POINT_IN){

									//$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_amount'] += $result_row['deposit_amount'];

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_point_amount'] += $result_row['deposit_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_POINT_OUT){

									//$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += $result_row['withdrawal_amount'];

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_point_amount'] += $result_row['withdrawal_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_ADJUST_IN){

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] += $result_row['deposit_amount'];

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_in_amount'] += $result_row['deposit_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_ADJUST_OUT){

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] -= $result_row['withdrawal_amount'];

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_out_amount'] += $result_row['withdrawal_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_PROMOTION){

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['promotion_amount'] += $result_row['deposit_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_BONUS){

									$this->player_winloss_list[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['bonus_amount'] += $result_row['deposit_amount'];

								}





								//Monthly

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['player_id'] = $member_lists[strtolower($result_row['username'])];

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])));

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['point_in_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['point_out_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['bonus_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['promotion_amount'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['bet_amount_valid'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['win_loss'] += 0;

								$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_count'] += 0;





								if($result_row['transfer_type'] == TRANSFER_POINT_IN){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['point_in_amount'] += $result_row['deposit_amount'];

									/*

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_count'] += 1;

									if(!isset($this->player_level[$member_lists[strtolower($result_row['username'])]])){

                						$this->player_level[$member_lists[strtolower($result_row['username'])]] = $member_lists[strtolower($result_row['username'])];

                					}

                					*/

								}

								if($result_row['transfer_type'] == TRANSFER_POINT_OUT){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['point_out_amount'] += $result_row['withdrawal_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_ADJUST_IN){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_amount'] += $result_row['deposit_amount'];

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_count'] += 1;

									if(!isset($this->player_level[$member_lists[strtolower($result_row['username'])]])){

                						$this->player_level[$member_lists[strtolower($result_row['username'])]] = $member_lists[strtolower($result_row['username'])];

                					}

								}

								if($result_row['transfer_type'] == TRANSFER_ADJUST_OUT){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += $result_row['withdrawal_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_PROMOTION){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['promotion_amount'] += $result_row['deposit_amount'];

								}

								if($result_row['transfer_type'] == TRANSFER_BONUS){

									$this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['bonus_amount'] += $result_row['deposit_amount'];

								}



							}

							$this->db->trans_start();

							$this->db->insert('game_result_logs', $DBdata);

							if(!empty($this->player_winloss_list) && sizeof($this->player_winloss_list)>0){

								foreach($this->player_winloss_list as $player_winlost_row){

									if(!empty($player_winlost_row) && sizeof($player_winlost_row)>0){

										foreach($player_winlost_row as $each_player_winlost_row){

											$this->report_model->insert_total_win_loss_report_dwa($each_player_winlost_row);

											if(!empty($each_player_winlost_row['deposit_point_amount']) || !empty($each_player_winlost_row['adjust_in_amount'])){

											    $this->db->query("UPDATE {$table_player} SET last_deposit_date = ? WHERE player_id = ? AND last_deposit_date < ? LIMIT 1", array($each_player_winlost_row['report_date'],$each_player_winlost_row['player_id'],$each_player_winlost_row['report_date']));

											}

										}

									}

								}

							}



							if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){

								foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){

									if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){

										foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){

											$this->report_model->insert_total_win_loss_report_monthly_dwa($each_player_winloss_list_monthly_row);

											if(!empty($each_player_winloss_list_monthly_row['deposit_count'])){

											    $this->db->query("UPDATE {$table_player} SET deposit_count = (deposit_count + ?) WHERE player_id = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id']));   

											}

										}

									}

								}

							}

							$this->db->trans_complete();

							unset($this->player_winloss_list);

							unset($this->player_winloss_list_monthly);

						}

						$query->free_result();

					}

					$queryKey->free_result();

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



	public function deposit_cron($member_lists = NULL){

		set_time_limit(0);

		$table_player = $this->db->dbprefix . "players";

		$provider_code = 'BECR';

		$result_type = 'DP';

		$sync_type = SYNC_TYPE_ALL;

		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);

		if(!empty($game_result_data))

		{

			if($game_result_data['sync_lock'] == STATUS_INACTIVE){

				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);

				$result_key = NULL;

				$result_data = NULL;

				$current_time = time();

				$page_id = 0;

				$last_id = 0;

				$next_id = 0;

				$last_sync_time = strtotime('-312 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);

				if( ! empty($sync_data))

				{

					$last_sync_time = $sync_data['end_time'];

				}

				$initial_time = date('Y-m-d H:i:00', $last_sync_time);

				$start_time = strtotime($initial_time);

				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+6 minutes', strtotime($initial_time))));

                //$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));

				if($end_time <= strtotime('-6 minutes', $current_time))

				{

					$DBdata = array(

						'game_provider_code' => $provider_code,

						'game_result_type' => $result_type,

						'game_sync_type' => $sync_type,

						'start_time' => $start_time,

						'end_time' => $end_time,

						'sync_time' => time(),

						'sync_status' => STATUS_YES,

						'page_id' => $page_id,

						'next_id' => $next_id,

						'resp_data' => '',

					);

					$this->db->select('player_id,amount,deposit_type,updated_date,created_date');

					$this->db->where('updated_date >= ',$start_time);

					$this->db->where('updated_date <',$end_time);

					$this->db->where('status',STATUS_COMPLETE);

					$query = $this->db->get('deposits');

					if($query->num_rows() > 0)

					{

						$result_data = $query->result_array();

						foreach($result_data as $result_row){

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])));

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_amount'] += $result_row['amount'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['bonus_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['promotion_amount'] = 0;

							

							//seperator

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_offline_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_online_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_credit_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_hypermart_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_point_amount'] = 0;

							

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_offline_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_online_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_point_amount'] = 0;

							

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_in_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_out_amount'] = 0;

							

							if($result_row['deposit_type'] == DEPOSIT_OFFLINE_BANKING){

							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_offline_amount'] += $result_row['amount'];

							}else{

							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_amount'] += $result_row['amount'];

							    if($result_row['deposit_type'] == DEPOSIT_ONLINE_BANKING){

							        $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_online_amount'] += $result_row['amount'];

							    }else if($result_row['deposit_type'] == DEPOSIT_CREDIT_CARD){

							        $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_credit_amount'] += $result_row['amount'];

							    }else if($result_row['deposit_type'] == DEPOSIT_HYPERMART){

							        $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_hypermart_amount'] += $result_row['amount'];

							    }

							}





							//Monthly

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])));

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['deposit_amount'] += $result_row['amount'];

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['withdrawals_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['point_in_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['point_out_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['bonus_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['promotion_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['bet_amount_valid'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['win_loss'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['deposit_count'] += 1;

							

							if(!isset($this->player_level[$result_row['player_id']])){

        						$this->player_level[$result_row['player_id']] = $result_row['player_id'];

        					}





						}

					}

					$this->db->trans_start();

					$this->db->insert('game_result_logs', $DBdata);

					if(!empty($this->player_winloss_list) && sizeof($this->player_winloss_list)>0){

						foreach($this->player_winloss_list as $player_winlost_row){

							if(!empty($player_winlost_row) && sizeof($player_winlost_row)>0){

								foreach($player_winlost_row as $each_player_winlost_row){

									$this->report_model->insert_total_win_loss_report_dwa($each_player_winlost_row);

									if(!empty($each_player_winlost_row['deposit_offline_amount']) || !empty($each_player_winlost_row['deposit_online_amount'])){

									    $this->db->query("UPDATE {$table_player} SET last_deposit_date = ? WHERE player_id = ? AND last_deposit_date < ? LIMIT 1", array($each_player_winlost_row['report_date'],$each_player_winlost_row['player_id'],$each_player_winlost_row['report_date']));

									}

								}

							}

						}

					}



					if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){

						foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){

							if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){

								foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){

									$this->report_model->insert_total_win_loss_report_monthly_dwa($each_player_winloss_list_monthly_row);

									if(!empty($each_player_winloss_list_monthly_row['deposit_count'])){

									    $this->db->query("UPDATE {$table_player} SET deposit_count = (deposit_count + ?) WHERE player_id = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id']));   

									}

								}

							}

						}

					}

					$this->db->trans_complete();

					unset($this->player_winloss_list);

					unset($this->player_winloss_list_monthly);

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



	public function withdrawal_cron($member_lists = NULL){

		set_time_limit(0);

		$table_player = $this->db->dbprefix . "players";

		$provider_code = 'BECR';

		$result_type = 'WD';

		$sync_type = SYNC_TYPE_ALL;

		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);

		if(!empty($game_result_data))

		{

			if($game_result_data['sync_lock'] == STATUS_INACTIVE){

				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);

				$result_key = NULL;

				$result_data = NULL;

				$current_time = time();

				$page_id = 0;

				$last_id = 0;

				$next_id = 0;

				$last_sync_time = strtotime('-312 minutes', $current_time);

				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);

				if( ! empty($sync_data))

				{

					$last_sync_time = $sync_data['end_time'];

				}



				$initial_time = date('Y-m-d H:i:00', $last_sync_time);

				$start_time = strtotime($initial_time);

				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+6 minutes', strtotime($initial_time))));

                //$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));

				if($end_time <= strtotime('-6 minutes', $current_time))

				{

					$DBdata = array(

						'game_provider_code' => $provider_code,

						'game_result_type' => $result_type,

						'game_sync_type' => $sync_type,

						'start_time' => $start_time,

						'end_time' => $end_time,

						'sync_time' => time(),

						'sync_status' => STATUS_YES,

						'page_id' => $page_id,

						'next_id' => $next_id,

						'resp_data' => '',

					);



					$this->db->select('player_id,withdrawal_type,amount,updated_date,created_date');

					$this->db->where('updated_date >= ',$start_time);

					$this->db->where('updated_date <',$end_time);

					$this->db->where('status',STATUS_COMPLETE);

					$query = $this->db->get('withdrawals');

					if($query->num_rows() > 0)

					{

						$result_data = $query->result_array();

						foreach($result_data as $result_row){

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])));

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_amount'] += $result_row['amount'];

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['bonus_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['promotion_amount'] = 0;

							

							//seperator

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_offline_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_online_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_credit_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_hypermart_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_point_amount'] = 0;

							

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_offline_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_online_amount'] += 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_point_amount'] = 0;

							

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_in_amount'] = 0;

							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_out_amount'] = 0;

							

							if($result_row['withdrawal_type'] == WITHDRAWAL_OFFLINE_BANKING){

							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_offline_amount'] += $result_row['amount'];

							}else{

							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_online_amount'] += $result_row['amount'];

							}







							//Monthly

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])));

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['deposit_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['withdrawals_amount']  += $result_row['amount'];

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['point_in_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['point_out_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['bonus_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['promotion_amount'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['bet_amount_valid'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['win_loss'] = 0;

							$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['deposit_count'] = 0;

						}

					}



					$this->db->trans_start();

					$this->db->insert('game_result_logs', $DBdata);

					if(!empty($this->player_winloss_list) && sizeof($this->player_winloss_list)>0){

						foreach($this->player_winloss_list as $player_winlost_row){

							if(!empty($player_winlost_row) && sizeof($player_winlost_row)>0){

								foreach($player_winlost_row as $each_player_winlost_row){

									$this->report_model->insert_total_win_loss_report_dwa($each_player_winlost_row);

								}

							}

						}

					}



					if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){

						foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){

							if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){

								foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){

									$this->report_model->insert_total_win_loss_report_monthly_dwa($each_player_winloss_list_monthly_row);

									if(!empty($each_player_winloss_list_monthly_row['deposit_count'])){

									    $this->db->query("UPDATE {$table_player} SET deposit_count = (deposit_count + ?) WHERE player_id = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id']));   

									}

								}

							}

						}

					}

					$this->db->trans_complete();

					unset($this->player_winloss_list);

					unset($this->player_winloss_list_monthly);

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

	

	public function player_upgrade(){

		if(!empty($this->player_level)){

			$update_data = array(

				'level_id' => 2,

			);



			$this->db->where('level_id',1);

			$this->db->where_in('player_id',$this->player_level);

			$this->db->update('players', $update_data);

		}

	}



	public function delete_all_empty_win_loss(){

		//total monthly win_loss

		$dbprefix = $this->db->dbprefix;

		$result_data = array();

		$result_array = array();

		$result_ids_string = "";

		$query = $this->db->query("SELECT total_win_loss_month_id FROM {$dbprefix}total_win_loss_report_month WHERE `deposit_count` = 0 AND `deposit_amount` = 0 AND `withdrawals_amount` = 0 AND `point_in_amount` = 0 AND `point_out_amount` = 0 AND `bonus_amount` = 0 AND `promotion_amount` = 0 AND `bet_amount_valid` = 0 AND `win_loss` = 0");

		if($query->num_rows() > 0)

		{

			$result_data = $query->result_array();

			if(sizeof($result_data) > 0){

				foreach($result_data as $result_row){

					array_push($result_array,$result_row['total_win_loss_month_id']);

				}

				if(!empty($result_array)){

					$result_ids_string = implode(',', $result_array);

					$this->db->query("DELETE FROM {$dbprefix}total_win_loss_report_month WHERE total_win_loss_month_id IN (".$result_ids_string.")");

				}

			}

		}

		$query->free_result();



		//total win loss daily

		$result_data = array();

		$result_array = array();

		$result_ids_string = "";

		$query = $this->db->query("SELECT total_win_loss_id FROM {$dbprefix}total_win_loss_report WHERE `deposit_amount` = 0 AND `deposit_offline_amount` = 0 AND `deposit_online_amount` = 0 AND `deposit_point_amount` = 0 AND `withdrawals_amount` = 0 AND `withdrawals_point_amount` = 0 AND `withdrawals_online_amount` = 0 AND `withdrawals_offline_amount` = 0 AND `adjust_amount` = 0 AND `adjust_in_amount` = 0 AND `adjust_out_amount` = 0 AND `bonus_amount` = 0 AND `promotion_amount` = 0 AND `total_bet` = 0 AND `bet_amount` = 0 AND `bet_amount_valid` = 0 AND `win_loss` = 0");

		if($query->num_rows() > 0)

		{

			$result_data = $query->result_array();

			if(sizeof($result_data) > 0){

				foreach($result_data as $result_row){

					array_push($result_array,$result_row['total_win_loss_id']);

				}

				if(!empty($result_array)){

					$result_ids_string = implode(',', $result_array);

					$this->db->query("DELETE FROM {$dbprefix}total_win_loss_report WHERE total_win_loss_id IN (".$result_ids_string.")");

				}

			}

		}

		$query->free_result();





		//total win loss daily

		$result_data = array();

		$result_array = array();

		$result_ids_string = "";

		$query = $this->db->query("SELECT win_loss_id FROM {$dbprefix}win_loss_report WHERE `total_bet` = 0 AND `bet_amount` = 0 AND `bet_amount_valid` = 0 AND `win_loss` = 0");

		if($query->num_rows() > 0)

		{

			$result_data = $query->result_array();

			if(sizeof($result_data) > 0){

				foreach($result_data as $result_row){

					array_push($result_array,$result_row['win_loss_id']);

				}

				if(!empty($result_array)){

					$result_ids_string = implode(',', $result_array);

					$this->db->query("DELETE FROM {$dbprefix}win_loss_report WHERE win_loss_id IN (".$result_ids_string.")");

				}

			}

		}

		$query->free_result();



		//total win loss daily game_code

		$result_data = array();

		$result_array = array();

		$result_ids_string = "";

		$query = $this->db->query("SELECT win_loss_game_code_id FROM {$dbprefix}win_loss_report_by_game_code WHERE `total_bet` = 0 AND `bet_amount` = 0 AND `bet_amount_valid` = 0 AND `win_loss` = 0");

		if($query->num_rows() > 0)

		{

			$result_data = $query->result_array();

			if(sizeof($result_data) > 0){

				foreach($result_data as $result_row){

					array_push($result_array,$result_row['win_loss_game_code_id']);

				}

				if(!empty($result_array)){

					$result_ids_string = implode(',', $result_array);

					$this->db->query("DELETE FROM {$dbprefix}win_loss_report_by_game_code WHERE win_loss_game_code_id IN (".$result_ids_string.")");

				}

			}

		}

		$query->free_result();

	}

}