<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Winloss extends MY_Controller {
	var $player_winloss_list = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
		$this->load->model(array('extract_model','promotion_model'));
	}
	public function win_loss_report_cron(){
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$member_lists = $this->player_model->get_player_list_array();
		$this->win_loss_cron();
		$this->cash_cron($member_lists);
		$this->deposit_cron($member_lists);
		$this->withdrawal_cron($member_lists);
		$this->promotion_current_turnover();
	}
	public function win_loss_cron(){
		set_time_limit(0);
		$provider_code = 'BECR';
		$result_type = 'WL';
		$sync_type = SYNC_TYPE_ALL;
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
						//ad($result_data);
						foreach($result_data as $result_row){
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_provider_code'] = $result_row['game_provider_code'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_type_code'] = $result_row['game_type_code'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['game_provider_type_code'] = $result_row['game_provider_code'] . "_" .$result_row['game_type_code'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['player_id'] = $result_row['player_id'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['payout_time'] = (strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])));
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['total_bet'] += $result_row['total_bet'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['bet_amount'] += bcdiv($result_row['bet_amount'],1,2);
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['bet_amount_valid'] += bcdiv($result_row['bet_amount_valid'],1,2);
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-m-d 00:00:00',$result_row['payout_time'])))."_".$result_row['game_provider_code']."_".$result_row['game_type_code']]['win_loss'] += bcdiv($result_row['win_loss'],1,2);
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
						/*Delete before latest record*/
						$this->db->where('win_loss_logs_id <= ',$last_id);
						$this->db->delete('win_loss_logs');
						$this->db->trans_complete();
						unset($this->player_winloss_list);
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
	public function cash_cron($member_lists = NULL){
		set_time_limit(0);
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
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['player_id'] = $member_lists[$result_row['username']];
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])));
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['bonus_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['promotion_amount'] += 0;
								//Devider
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_offline_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_online_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_point_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_offline_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_online_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_point_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_in_amount'] += 0;
								$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_out_amount'] += 0;
								if($result_row['transfer_type'] == TRANSFER_POINT_IN){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_amount'] += $result_row['deposit_amount'];
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['deposit_point_amount'] += $result_row['deposit_amount'];
								}
								if($result_row['transfer_type'] == TRANSFER_POINT_OUT){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += $result_row['withdrawal_amount'];
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['withdrawals_point_amount'] += $result_row['withdrawal_amount'];
								}
								if($result_row['transfer_type'] == TRANSFER_ADJUST_IN){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] += $result_row['deposit_amount'];
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_in_amount'] += $result_row['deposit_amount'];
								}
								if($result_row['transfer_type'] == TRANSFER_ADJUST_OUT){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_amount'] -= $result_row['withdrawal_amount'];
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['adjust_out_amount'] += $result_row['deposit_amount'];
								}
								if($result_row['transfer_type'] == TRANSFER_PROMOTION){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['promotion_amount'] += $result_row['deposit_amount'];
								}
								if($result_row['transfer_type'] == TRANSFER_BONUS){
									$this->player_winloss_list[$member_lists[$result_row['username']]][(strtotime(date('Y-M-d 00:00:00',$result_row['report_date'])))]['bonus_amount'] += $result_row['deposit_amount'];
								}
							}
							//ad($this->player_winloss_list);
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
							$this->db->trans_complete();
							unset($this->player_winloss_list);
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
				$last_sync_time = strtotime('-12 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+6 minutes', strtotime($initial_time))));                
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
					$this->db->select('player_id,amount,deposit_type,updated_date');
					$this->db->where('updated_date >= ',$start_time);
					$this->db->where('updated_date <',$end_time);
					$this->db->where('status',STATUS_COMPLETE);
					$query = $this->db->get('deposits');
					if($query->num_rows() > 0)
					{
						$result_data = $query->result_array();
						foreach($result_data as $result_row){
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['player_id'] = $result_row['player_id'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])));
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_amount'] += $result_row['amount'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['bonus_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['promotion_amount'] = 0;
							//seperator
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_offline_amount'] += 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_online_amount'] += 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_point_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_offline_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_online_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_point_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_in_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_out_amount'] = 0;
							if($result_row['deposit_type'] == DEPOSIT_OFFLINE_BANKING){
							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_offline_amount'] += $result_row['amount'];
							}else{
							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_online_amount'] += $result_row['amount'];
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
								}
							}
						}
					}
					$this->db->trans_complete();
					unset($this->player_winloss_list);
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
				$last_sync_time = strtotime('-12 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+6 minutes', strtotime($initial_time))));                
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
					$this->db->select('player_id,amount,updated_date');
					$this->db->where('updated_date >= ',$start_time);
					$this->db->where('updated_date <',$end_time);
					$this->db->where('status',STATUS_COMPLETE);
					$query = $this->db->get('withdrawals');
					if($query->num_rows() > 0)
					{
						$result_data = $query->result_array();
						foreach($result_data as $result_row){
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['player_id'] = $result_row['player_id'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])));
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_amount'] += $result_row['amount'];
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['bonus_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['promotion_amount'] = 0;
							//seperator
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_offline_amount'] += 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_online_amount'] += 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['deposit_point_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_offline_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_online_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_point_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_in_amount'] = 0;
							$this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['adjust_out_amount'] = 0;
							if($result_row['withdrawal_type'] == WITHDRAWAL_OFFLINE_BANKING){
							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_offline_amount'] += $result_row['amount'];
							}else{
							    $this->player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['updated_date'])))]['withdrawals_online_amount'] += $result_row['amount'];
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
								}
							}
						}
					}
					$this->db->trans_complete();
					unset($this->player_winloss_list);
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
	
	public function promotion_current_turnover(){
		set_time_limit(0);
		$this->db->select('player_promotion_id,player_id,created_date,starting_date,complete_date,current_amount,achieve_amount,game_ids');
		$this->db->where_in('status',array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$query 			= $this->db->get('player_promotion');
		$promotion_data = ($query->num_rows() > 0) ? $query->result_array() : array();

		#ad($promotion_data);
		foreach($promotion_data as $a) {			
			$time   = ($a['starting_date'] != '' && $a['starting_date'] > 0) ? $a['starting_date'] : $a['created_date']; 
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->where('player_id',$a['player_id']);
			if($a['game_ids'] != '0'){
				$game_ids = array_filter(explode(',', $a['game_ids']));
				$this->db->where_in('game_provider_type_code', $game_ids);			
			}
			#$this->db->where('report_date >=', $time);
			$this->db->where('payout_time >=', $time);
			#$query 	= $this->db->get('win_loss_report');
			$query 	= $this->db->get('transaction_report');
			$row 	= $query->row_array();
			$stake 	= ($row['current_amount'] == '') ? 0 : $row['current_amount'];	
			$query->free_result();
			#ad($this->db->last_query());
			#ad($stake);

			if($stake > 0) {
				#ad($a['achieve_amount']);
				
				if($stake >= $a['achieve_amount'] && $a['achieve_amount'] > 0) {
					
					$update = array('current_amount'=>$a['achieve_amount'],'status'=>STATUS_SATTLEMENT);
					$this->db->where('player_promotion_id', $a['player_promotion_id']);
					$this->db->update('player_promotion', $update);
				}
				else {
					$update = array('current_amount'=>$stake);
					$this->db->where('player_promotion_id', $a['player_promotion_id']);
					$this->db->update('player_promotion', $update);
				}
				#ad($this->db->last_query());
				#ad($this->db->affected_rows());
				
				unset($update);
			}
			
		}
		unset($promotion_data);
	}
}