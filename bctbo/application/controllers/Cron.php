<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {
	var $telegram_player_id_array = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('cron_model','miscellaneous_model','player_model'));
	}
	
	public function risk_cron(){
		set_time_limit(0);
		$current_time = time();
		$cron_code = 'RISK';
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		if(!empty($cron_result_data))
		{
			if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
				$miscellaneous = $this->miscellaneous_model->get_miscellaneous();

				$risk_announcement_rate = array_values(array_filter(explode(',', $miscellaneous['risk_announcement_rate'])));
				asort($risk_announcement_rate);
				if(!empty($miscellaneous) && ($miscellaneous['risk_management'] = STATUS_ACTIVE)){
					if($miscellaneous['risk_period'] == RISK_YEARLY){
						//Yearly
						$start_date	= date('Y-m-d 00:00:00', strtotime('first day of january this year'));
						$end_date	= date('Y-m-d 23:59:59', strtotime('last day of december this year'));
					}else if($miscellaneous['risk_period'] == RISK_MONTHLY){
						//Monthly
						$start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
						$end_date	= date('Y-m-d 23:59:59', strtotime('last day of this month'));
					}else{
						//Daily
						$start_date	= date('Y-m-d 00:00:00', time());
						$end_date	= date('Y-m-d 23:59:59', time());
					}
					$start_time = strtotime($start_date); 
					$end_time = strtotime($end_date);
					$DBdata = array(
						'cron_time' => $start_time,
					);

					$player_risk_record = $this->cron_model->get_all_player_risk_record($start_time,$end_time);
					$player_list = $this->cron_model->get_player_data_risk_management();
					//initialize
					$win_loss_suspend_limit = 0;
					$percentage = 0;
					$percentage_calculate = 0;
					$player_total_win_loss = 0;
					$Bdata = array();
					$BUdata = array();

					if(!empty($player_list)){
						foreach($player_list as $player_list_row){
							//resetting
							$percentage = 0;
							$win_loss_suspend_limit = 0;
							$percentage_calculate = 0;
							$player_total_win_loss = 0;

							(empty($player_list_row['win_loss_suspend_limit'])) ? $win_loss_suspend_limit = $miscellaneous['win_loss_suspend_limit'] : $win_loss_suspend_limit = $player_list_row['win_loss_suspend_limit'];
							$player_total_win_loss = $this->cron_model->get_player_total_win_loss($player_list_row['player_id'],$start_time,$end_time);
							if($player_total_win_loss > 0){
								if($player_total_win_loss >= $win_loss_suspend_limit){
									$percentage = 100;
								}else{
									$percentage = 0;
									$percentage_calculate = bcdiv((($player_total_win_loss/$win_loss_suspend_limit)*100),1,0);
									if(!empty($risk_announcement_rate)){
										foreach($risk_announcement_rate as $risk_announcement_rate_row){
											if($percentage_calculate >= $risk_announcement_rate_row){
												$percentage = $risk_announcement_rate_row;
											}
										}
									}
								}
							}else{
								$percentage = 0;
							}

							if(isset($player_risk_record[$player_list_row['player_id']])){
								$PBdata = array(
									'player_id' => $player_list_row['player_id'],
									'report_date' => $start_time,
									'end_date' => $end_time,
									'percentage' => $percentage,
									'total_win_lose' => $player_total_win_loss,
									'win_loss_suspend' => $win_loss_suspend_limit,
									'suspended' => STATUS_ACTIVE,
									'updated_date' => time(),
								);

								if($percentage == 0){
									$this->cron_model->delete_risk_record($PBdata);
								}else if($percentage == 100){
									$PBdata['suspended'] = STATUS_SUSPEND;
									$this->cron_model->update_player_status($player_list_row['player_id'],STATUS_SUSPEND);
									$this->player_model->clear_login_token($player_list_row);
									array_push($BUdata, $PBdata);
								}else{
									array_push($BUdata, $PBdata);
								}
							}else{
								$PBdata = array(
									'player_id' => $player_list_row['player_id'],
									'report_date' => $start_time,
									'end_date' => $end_time,
									'percentage' => $percentage,
									'total_win_lose' => $player_total_win_loss,
									'win_loss_suspend' => $win_loss_suspend_limit,
									'suspended' => STATUS_ACTIVE,
									'updated_date' => time(),
									'created_date' => time(),
								);
								if($percentage == 0){

								}else if($percentage == 100){
									$PBdata['suspended'] = STATUS_SUSPEND;
									$this->cron_model->update_player_status($player_list_row['player_id'],STATUS_SUSPEND);
									$this->player_model->clear_login_token($player_list_row);
									array_push($Bdata, $PBdata);
								}else{
									array_push($Bdata, $PBdata);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('player_risk_report', $Bdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->cron_model->update_player_risk_record($BUdataRow);
						}
					}
					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
				}
				$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
			}
		}
	}

	public function tag_cron(){
		set_time_limit(0);
		$cron_code = 'TAG';
		$update_current_time = strtotime(date('Y-m-d 00:00:00'));
		$current_time = time();
		$today_day = date('j',$current_time);
		$today_hour = date('G',$current_time);
		$today_month = date('n',$current_time);
		$is_allow_cron = FALSE;
		$start_time = "";
		$end_time = "";
		if($today_day == "2" || $today_day == "16"){
			if($today_hour == "16"){
				$is_allow_cron = TRUE;
				if($today_day == "2"){
					$start_date	= date('Y-m-d 00:00:00', strtotime('first day of -3 month',$current_time));
					$end_date	= date('Y-m-d 23:59:59', strtotime('last day of last month',$current_time));
				}else{
					$start_date	= date('Y-m-15 00:00:00', strtotime('first day of -3 month',$current_time));
					$end_date	= date('Y-m-15 23:59:59', $current_time);
				}
			}
		}

		if($is_allow_cron){
			$dbprefix = $this->db->dbprefix;
			$cron_result_data = $this->cron_model->get_cron_result($cron_code);
			if(!empty($cron_result_data))
			{
				if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
					$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
					$DBdata = array('cron_time' => $current_time);
					$tag_lists = array();
					$last_player_id = 1000000000;
					$player_lists = array();
					$start_time = strtotime($start_date);
					$end_time = strtotime($end_date);
					$Bdata = array();
					$winloss = 0;
					$base_tag_id = 0;
					//GET ALL TAG
					$tag_query = $this->db->query("SELECT tag_id, tag_times, tag_min, tag_max FROM {$dbprefix}tag WHERE active = ? ORDER BY tag_min ASC", array(STATUS_ACTIVE));
					if($tag_query->num_rows() > 0) {
						foreach($tag_query->result() as $tag_row){
							if(empty($base_tag_id)){
								$base_tag_id = $tag_row->tag_id;
							}
							$tag_lists[$tag_row->tag_id] = array(
								'tag_id' => $tag_row->tag_id,
								'tag_times' => $tag_row->tag_times,
								'tag_min' => $tag_row->tag_min,
								'tag_max' => $tag_row->tag_max,
							);
						}
					}
					$tag_query->free_result();

					if(!empty($tag_lists)){
						$player_query = $this->db->query("SELECT player_id FROM {$dbprefix}players ORDER BY player_id DESC LIMIT 1");
						if($player_query->num_rows() > 0) {
							$player_row = $player_query->row();
							$last_player_id = $player_row->player_id;
						}
						$player_query->free_result();

						$player_query = $this->db->query("SELECT player_id, tag_id, tag_times, tag_force FROM {$dbprefix}players WHERE player_id <= ? ORDER BY player_id ASC", array($last_player_id));
						if($player_query->num_rows() > 0) {
							foreach($player_query->result() as $player_row){
								$player_lists[$player_row->player_id] = array(
									'player_id' => $player_row->player_id,
									'tag_id' => $player_row->tag_id,
									'tag_times' => $player_row->tag_times,
									'tag_force' => $player_row->tag_force,
									'to_tag_id' => 0,
									'win_loss' => 0,
									'is_win_loss' =>  false,
									'is_upgrade' => false,
									'is_maintain' => false,
									'is_downgrade' => false,
									'is_reset' => false,
								);
							}
						}

						if(!empty($player_lists)){
							$win_loss_query = $this->db->query("SELECT COALESCE(SUM(win_loss),0) as win_loss, player_id FROM {$dbprefix}total_win_loss_report WHERE report_date >= ? AND report_date <= ? AND win_loss != ? AND player_id <= ? GROUP BY player_id", array($start_time, $end_time, 0, $last_player_id));
							if($win_loss_query->num_rows() > 0) {
								foreach($win_loss_query->result() as $win_loss_row){
									if(isset($player_lists[$win_loss_row->player_id])){
										$player_lists[$win_loss_row->player_id]['is_win_loss'] = TRUE;
										$player_lists[$win_loss_row->player_id]['win_loss'] += $win_loss_row->win_loss;
									}
								}
							}

							//Decide current tag id
							foreach($player_lists as $player_lists_row){				
								if($player_lists_row['is_win_loss']){
									$winloss = abs($player_lists_row['win_loss']);
									foreach($tag_lists as $tag_lists_row){
										if(($winloss >= $tag_lists_row['tag_min']) && ($winloss <= $tag_lists_row['tag_max'])){
											$player_lists[$player_lists_row['player_id']]['to_tag_id'] = $tag_lists_row['tag_id'];
										}
									}
								}
							}

							//decide lvup
							foreach($player_lists as $player_lists_row){
								if(!empty($player_lists_row['to_tag_id'])){
									if(empty($player_lists_row['tag_id'])){
										$player_lists[$player_lists_row['player_id']]['is_upgrade'] = TRUE;
									}else if($player_lists_row['tag_id'] == $player_lists_row['to_tag_id']){
										$player_lists[$player_lists_row['player_id']]['is_reset'] = TRUE;
									}else{
										$old_player_tag = $tag_lists[$player_lists_row['tag_id']];
										$new_player_tag = $tag_lists[$player_lists_row['to_tag_id']];
										if($new_player_tag['tag_min'] > $old_player_tag['tag_min']){
											$player_lists[$player_lists_row['player_id']]['is_upgrade'] = TRUE;
										}else{
											if($player_lists_row['tag_times']+1 >= $old_player_tag['tag_times']){
												$player_lists[$player_lists_row['player_id']]['is_downgrade'] = TRUE;
											}else{
												$player_lists[$player_lists_row['player_id']]['is_maintain'] = TRUE;
											}
										}
									}
								}else{
									if(empty($player_lists_row['tag_id'])){

									}else if($player_lists_row['tag_id'] == $player_lists_row['to_tag_id']){
										$player_lists[$player_lists_row['player_id']]['is_reset'] = TRUE;
									}else{
										$old_player_tag = $tag_lists[$player_lists_row['tag_id']];
										if($player_lists_row['tag_times']+1 >= $old_player_tag['tag_times']){
											$player_lists[$player_lists_row['player_id']]['to_tag_id'] = $base_tag_id;
											$player_lists[$player_lists_row['player_id']]['is_downgrade'] = TRUE;
										}else{
											$player_lists[$player_lists_row['player_id']]['is_maintain'] = TRUE;
										}
									}
								}
							}
							
							foreach($player_lists as $player_lists_row){
								$PBdata = array(
									'created_date' => $update_current_time,
									'player_id' => $player_lists_row['player_id'],
									'tag_id' => $player_lists_row['tag_id'],
									'tag_force' => $player_lists_row['tag_force'],
									'tag_times' => $player_lists_row['tag_times'],
									'to_tag_id' => $player_lists_row['to_tag_id'],
									'win_loss' => $player_lists_row['win_loss'],
									'is_win_loss' => $player_lists_row['is_win_loss'],
									'is_upgrade' => $player_lists_row['is_upgrade'],
									'is_maintain' => $player_lists_row['is_maintain'],
									'is_downgrade' => $player_lists_row['is_downgrade'],
									'is_reset' => $player_lists_row['is_reset'],
								);
								array_push($Bdata, $PBdata);
								
								if($player_lists_row['tag_force']  == FALSE){
									if($player_lists_row['is_upgrade']  == TRUE){
										$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($player_lists_row['to_tag_id'], 0, $player_lists_row['player_id']));
									}else if($player_lists_row['is_reset']  == TRUE){
										$this->db->query("UPDATE {$dbprefix}players SET tag_times = ? WHERE player_id = ? LIMIT 1", array(0, $player_lists_row['player_id']));
									}else if($player_lists_row['is_maintain']  == TRUE){
										$this->db->query("UPDATE {$dbprefix}players SET tag_times = (tag_times + ?) WHERE player_id = ? LIMIT 1", array(1, $player_lists_row['player_id']));
									}else if($player_lists_row['is_downgrade']  == TRUE){
										$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($player_lists_row['to_tag_id'], 0, $player_lists_row['player_id']));
									}
								}
							}
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('tag_log', $Bdata);
							}
						}
					}
					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
					$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
				}
			}
		}
	}

	public function tag_cron_weekly(){
		set_time_limit(0);
		$cron_code = 'TAG';
		$update_current_time = strtotime(date('Y-m-d 00:00:00'));
		$current_time = time();
		$today_day = date('N',$current_time);
		$today_hour = date('G',$current_time);
		$is_allow_cron = FALSE;
		$calculate_duration = 7;

		$start_time = "";
		$end_time = "";
		if($today_day == "1"){
			if($today_hour == "5"){
				$is_allow_cron = TRUE;
				$start_date	= date('Y-m-d 00:00:00', strtotime('monday last week'));
				$end_date	= date('Y-m-d 23:59:59', strtotime('sunday last week'));
				$start_time = strtotime($start_date);
				$end_time = strtotime($end_date);
			}
		}
		
		if($is_allow_cron){
			$dbprefix = $this->db->dbprefix;
			$cron_result_data = $this->cron_model->get_cron_result($cron_code);
			if(!empty($cron_result_data))
			{
				if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
					$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
					$DBdata = array('cron_time' => $current_time);
					$tag_lists = array();
					$last_player_id = 1000000000;
					$player_lists = array();
					$start_time = strtotime($start_date);
					$end_time = strtotime($end_date);
					$Bdata = array();
					$winloss = 0;
					$base_tag_id = 0;
					$max_times = 0;
					//GET ALL TAG
					$tag_query = $this->db->query("SELECT tag_id, tag_times, tag_min, tag_max FROM {$dbprefix}tag WHERE active = ? ORDER BY tag_min ASC", array(STATUS_ACTIVE));
					if($tag_query->num_rows() > 0) {
						foreach($tag_query->result() as $tag_row){
							if($tag_row->tag_times > $max_times){
								$max_times = $tag_row->tag_times;
							}

							if(empty($base_tag_id)){
								$base_tag_id = $tag_row->tag_id;
							}
							$tag_lists[$tag_row->tag_id] = array(
								'tag_id' => $tag_row->tag_id,
								'tag_times' => $tag_row->tag_times,
								'tag_min' => $tag_row->tag_min,
								'tag_max' => $tag_row->tag_max,
							);
						}
					}
					$tag_query->free_result();
					if(!empty($tag_lists)){
						$player_query = $this->db->query("SELECT player_id FROM {$dbprefix}players ORDER BY player_id DESC LIMIT 1");
						if($player_query->num_rows() > 0) {
							$player_row = $player_query->row();
							$last_player_id = $player_row->player_id;
						}
						$player_query->free_result();

						$player_query = $this->db->query("SELECT player_id, tag_id, tag_times, tag_force FROM {$dbprefix}players WHERE player_id <= ? ORDER BY player_id DESC", array($last_player_id));
						if($player_query->num_rows() > 0) {
							foreach($player_query->result() as $player_row){
								$player_lists[$player_row->player_id] = array(
									'player_id' => $player_row->player_id,
									'tag_id' => $player_row->tag_id,
									'tag_times' => $player_row->tag_times,
									'tag_force' => $player_row->tag_force,
									'tag_min_datetime' => (isset($tag_lists[$player_row->tag_id]) ? strtotime('-'.($tag_lists[$player_row->tag_id]['tag_times']*$calculate_duration).' days', $update_current_time):0),
									'to_tag_id' => 0,
									'win_loss' => 0,
									'is_win_loss' =>  false,
									'is_upgrade' => false,
									'is_maintain' => false,
									'is_downgrade' => false,
									'is_reset' => false,
									'min_drop_tag_id' => 0,
									'is_run_back_ranking' => true,
									'is_update_back_ranking' => false,
									'back_ranking' => $base_tag_id,
									'back_ranking_times' => 0,
									'back_ranking_data' => array(),
								);
							}
						}

						if(!empty($player_lists)){
							$win_loss_query = $this->db->query("SELECT COALESCE(SUM(win_loss),0) as win_loss, player_id FROM {$dbprefix}total_win_loss_report WHERE report_date >= ? AND report_date <= ? AND win_loss != ? AND player_id <= ? GROUP BY player_id", array($start_time, $end_time, 0, $last_player_id));
							if($win_loss_query->num_rows() > 0) {
								foreach($win_loss_query->result() as $win_loss_row){
									if(isset($player_lists[$win_loss_row->player_id])){
										$player_lists[$win_loss_row->player_id]['is_win_loss'] = TRUE;
										$player_lists[$win_loss_row->player_id]['win_loss'] += $win_loss_row->win_loss;
									}
								}
							}

							foreach($player_lists as $player_lists_row){				
								if($player_lists_row['is_win_loss']){
									$winloss = abs($player_lists_row['win_loss']);
									foreach($tag_lists as $tag_lists_row){
										if(($winloss >= $tag_lists_row['tag_min']) && ($winloss <= $tag_lists_row['tag_max'])){
											$player_lists[$player_lists_row['player_id']]['to_tag_id'] = $tag_lists_row['tag_id'];
										}
									}
								}
							}


							//decide lvup
							foreach($player_lists as $player_lists_row){
								if(!empty($player_lists_row['to_tag_id'])){
									if(empty($player_lists_row['tag_id'])){
										$player_lists[$player_lists_row['player_id']]['is_upgrade'] = TRUE;
									}else if($player_lists_row['tag_id'] == $player_lists_row['to_tag_id']){
										$player_lists[$player_lists_row['player_id']]['is_reset'] = TRUE;
									}else{
										$old_player_tag = $tag_lists[$player_lists_row['tag_id']];
										$new_player_tag = $tag_lists[$player_lists_row['to_tag_id']];
										if($new_player_tag['tag_min'] > $old_player_tag['tag_min']){
											$player_lists[$player_lists_row['player_id']]['is_upgrade'] = TRUE;
										}else{
											if($player_lists_row['tag_times'] >= $old_player_tag['tag_times']){
												$player_lists[$player_lists_row['player_id']]['is_downgrade'] = TRUE;
											}else{
												$player_lists[$player_lists_row['player_id']]['is_maintain'] = TRUE;
											}
										}
									}
								}else{
									if(empty($player_lists_row['tag_id'])){

									}else if($player_lists_row['tag_id'] == $player_lists_row['to_tag_id']){
										$player_lists[$player_lists_row['player_id']]['is_reset'] = TRUE;
									}else{
										$old_player_tag = $tag_lists[$player_lists_row['tag_id']];
										if($player_lists_row['tag_times'] >= $old_player_tag['tag_times']){
											$player_lists[$player_lists_row['player_id']]['to_tag_id'] = $base_tag_id;
											$player_lists[$player_lists_row['player_id']]['is_downgrade'] = TRUE;
										}else{
											$player_lists[$player_lists_row['player_id']]['is_maintain'] = TRUE;
										}
									}
								}
							}

							sort($player_lists);
							$is_loop = TRUE;
							$page_id = 1;
							$max = sizeof($player_lists);
							$Bdata = array();
							$downgrade_player = array();
							$downgrade_player_ids = array();
 							while($is_loop == TRUE){
								$Bdata = array();			
								$j = ($page_id - 1) * 1000;
								$k = $page_id * 1000;

								if($max <= $k){
									$is_loop  = FALSE;
									$k = $max;
								}

								for($i=$j; $i<$k; $i++){
									$PBdata = array(
										'created_date' => $update_current_time,
										'player_id' => $player_lists[$i]['player_id'],
										'tag_id' => $player_lists[$i]['tag_id'],
										'tag_force' => $player_lists[$i]['tag_force'],
										'tag_times' => $player_lists[$i]['tag_times'],
										'to_tag_id' => $player_lists[$i]['to_tag_id'],
										'win_loss' => $player_lists[$i]['win_loss'],
										'is_win_loss' => $player_lists[$i]['is_win_loss'],
										'is_upgrade' => $player_lists[$i]['is_upgrade'],
										'is_maintain' => $player_lists[$i]['is_maintain'],
										'is_downgrade' => $player_lists[$i]['is_downgrade'],
										'is_reset' => $player_lists[$i]['is_reset'],
									);
									
									if($player_lists[$i]['tag_force']  == FALSE){
										if($player_lists[$i]['is_upgrade']  == TRUE){
											//ad("Upgrade");
											//ad($player_lists[$i]);
											array_push($Bdata, $PBdata);
											$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($player_lists[$i]['to_tag_id'], 0, $player_lists[$i]['player_id']));
										}else if($player_lists[$i]['is_reset']  == TRUE){
											//ad("Reset");
											//ad($player_lists[$i]);
											array_push($Bdata, $PBdata);
											$this->db->query("UPDATE {$dbprefix}players SET tag_times = ? WHERE player_id = ? LIMIT 1", array(0, $player_lists[$i]['player_id']));
										}else if($player_lists[$i]['is_maintain']  == TRUE){
											//ad("Maintain");
											//ad($player_lists[$i]);
											array_push($Bdata, $PBdata);
											$this->db->query("UPDATE {$dbprefix}players SET tag_times = (tag_times + ?) WHERE player_id = ? LIMIT 1", array(1, $player_lists[$i]['player_id']));
										}else if($player_lists[$i]['is_downgrade']  == TRUE){
											//ad("Downgrade");
											//ad($player_lists[$i]);
											$downgrade_player[$player_lists[$i]['player_id']] = $player_lists[$i];
											array_push($downgrade_player_ids,$player_lists[$i]['player_id']);
											$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($player_lists[$i]['to_tag_id'], 0, $player_lists[$i]['player_id']));
										}else{
											array_push($Bdata, $PBdata);
										}
									}else{
										array_push($Bdata, $PBdata);
									}
								}
								$page_id++;

								if( ! empty($Bdata))
								{
									$this->db->insert_batch('tag_log', $Bdata);
								}
							}

							if(sizeof($downgrade_player)){
								$Bdata = array();
								$total_days = $calculate_duration*$max_times;
								$min_date = strtotime('-'.$total_days.' days', $update_current_time);
								$downgrade_player_ids_string = implode(',', $downgrade_player_ids);
								$tag_log_query = $this->db->query("SELECT * FROM {$dbprefix}tag_log WHERE created_date >= ? AND created_date < ? AND player_id IN (".$downgrade_player_ids_string.") ORDER BY created_date DESC", array($min_date, $update_current_time));
								if($tag_log_query->num_rows() > 0) {
									foreach($tag_log_query->result() as $tag_log_row){
										if(isset($downgrade_player[$tag_log_row->player_id])){
											if($downgrade_player[$tag_log_row->player_id]['is_run_back_ranking'] == TRUE){
												if($tag_log_row->is_maintain == TRUE){
													if($downgrade_player[$tag_log_row->player_id]['tag_min_datetime'] <= $tag_log_row->created_date){
														if($tag_log_row->is_downgrade == TRUE){

														}else{
															if(isset($tag_lists[$tag_log_row->to_tag_id])){
																if(($tag_lists[$tag_log_row->to_tag_id]['tag_min'] > $tag_lists[$downgrade_player[$tag_log_row->player_id]['back_ranking']]['tag_min']) && ($tag_lists[$tag_log_row->to_tag_id]['tag_max'] > $tag_lists[$downgrade_player[$tag_log_row->player_id]['back_ranking']]['tag_max'])){
																	if($downgrade_player[$tag_log_row->player_id]['back_ranking'] != $tag_log_row->to_tag_id){
																		$downgrade_player[$tag_log_row->player_id]['back_ranking_times'] = $tag_lists[$tag_log_row->to_tag_id]['tag_times'] - $tag_log_row->tag_times + 1;
																	}
																	$downgrade_player[$tag_log_row->player_id]['back_ranking'] = $tag_log_row->to_tag_id;
																	$downgrade_player[$tag_log_row->player_id]['is_update_back_ranking'] = true;
																}
															}
														}	
													}
												}else{
													$downgrade_player[$tag_log_row->player_id]['is_run_back_ranking'] = false;
												}
											}
										}
									}
								}
								foreach($downgrade_player as $downgrade_player_row){
									if($downgrade_player_row['is_update_back_ranking'] == TRUE){
										$PBdata = array(
											'created_date' => $update_current_time,
											'player_id' => $downgrade_player_row['player_id'],
											'tag_id' => $downgrade_player_row['tag_id'],
											'tag_force' => $downgrade_player_row['tag_force'],
											'tag_times' => $downgrade_player_row['back_ranking_times'],
											'to_tag_id' => $downgrade_player_row['back_ranking'],
											'win_loss' => $downgrade_player_row['win_loss'],
											'is_win_loss' => $downgrade_player_row['is_win_loss'],
											'is_upgrade' => $downgrade_player_row['is_upgrade'],
											'is_maintain' => TRUE,
											'is_downgrade' => $downgrade_player_row['is_downgrade'],
											'is_reset' => $downgrade_player_row['is_reset'],
										);
										array_push($Bdata, $PBdata);
										$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($downgrade_player_row['back_ranking'], $downgrade_player_row['back_ranking_times'], $downgrade_player_row['player_id']));
									}else{
										$PBdata = array(
											'created_date' => $update_current_time,
											'player_id' => $downgrade_player_row['player_id'],
											'tag_id' => $downgrade_player_row['tag_id'],
											'tag_force' => $downgrade_player_row['tag_force'],
											'tag_times' => $downgrade_player_row['tag_times'],
											'to_tag_id' => $downgrade_player_row['to_tag_id'],
											'win_loss' => $downgrade_player_row['win_loss'],
											'is_win_loss' => $downgrade_player_row['is_win_loss'],
											'is_upgrade' => $downgrade_player_row['is_upgrade'],
											'is_maintain' => $downgrade_player_row['is_maintain'],
											'is_downgrade' => $downgrade_player_row['is_downgrade'],
											'is_reset' => $downgrade_player_row['is_reset'],
										);
										array_push($Bdata, $PBdata);
										$this->db->query("UPDATE {$dbprefix}players SET tag_id = ?, tag_times = ? WHERE player_id = ? LIMIT 1", array($downgrade_player_row['tag_id'], 0, $downgrade_player_row['player_id']));
									}
								}

								if( ! empty($Bdata))
								{
									$this->db->insert_batch('tag_log', $Bdata);
								}
							}
						}
					}


					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
					$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
				}
			}
		}
	}

	public function risk_cron_bulk_one(){
		set_time_limit(0);
		$cron_code = 'RISK2';
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		if(!empty($cron_result_data))
		{
			if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->risk_cron_second();
				$this->risk_cron_telegram_percent();
				$this->risk_cron_telegram_custom_one();
			}
		}
	}

	public function risk_cron_second(){
		set_time_limit(0);
		$current_time = time();
		$cron_code = 'RISK';
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		$dbprefix = $this->db->dbprefix;

		
		$last_player_id = 1000000000;
		$player_lists = array();
		$Bdata = array();

		if(!empty($cron_result_data))
		{
			if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
				$miscellaneous = $this->miscellaneous_model->get_miscellaneous();

				$risk_announcement_rate = array_values(array_filter(explode(',', $miscellaneous['risk_announcement_rate'])));
				asort($risk_announcement_rate);
				if(!empty($miscellaneous) && ($miscellaneous['risk_management'] = STATUS_ACTIVE)){
					if($miscellaneous['risk_period'] == RISK_YEARLY){
						//Yearly
						$start_date	= date('Y-m-d 00:00:00', strtotime('first day of january this year'));
						$end_date	= date('Y-m-d 23:59:59', strtotime('last day of december this year'));
					}else if($miscellaneous['risk_period'] == RISK_MONTHLY){
						//Monthly
						$start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
						$end_date	= date('Y-m-d 23:59:59', strtotime('last day of this month'));
					}else{
						//Daily
						$start_date	= date('Y-m-d 00:00:00', time());
						$end_date	= date('Y-m-d 23:59:59', time());
					}
					
					$start_time = strtotime($start_date); 
					$end_time = strtotime($end_date);

					$DBdata = array(
						'cron_time' => $start_time,
					);

					$player_query = $this->db->query("SELECT player_id FROM {$dbprefix}players ORDER BY player_id DESC LIMIT 1");
					if($player_query->num_rows() > 0) {
						$player_row = $player_query->row();
						$last_player_id = $player_row->player_id;
					}
					$player_query->free_result();

					$player_query = $this->db->query("SELECT player_id, win_loss_suspend_limit, active FROM {$dbprefix}players WHERE player_id <= ? ORDER BY player_id ASC", array($last_player_id));
					if($player_query->num_rows() > 0) {
						foreach($player_query->result() as $player_row){
							$player_lists[$player_row->player_id] = array(
								'player_id' => $player_row->player_id,
								'win_loss_suspend_limit' => ((empty($player_row->win_loss_suspend_limit)) ? $miscellaneous['win_loss_suspend_limit']: $player_row->win_loss_suspend_limit),
								'active' => $player_row->active,
								'win_loss' => 0,
								'is_win_loss' => FALSE,
								'is_old_risk' => FALSE,
								'old_risk_data' => array(),
								'is_new_risk' => FALSE,
								'new_risk_data' => array(),
								'is_ban' => FALSE,
							);
						}
					}
					$player_query->free_result();


					if(!empty($player_lists)){
						$win_loss_query = $this->db->query("SELECT COALESCE(SUM(win_loss),0) as win_loss, player_id FROM {$dbprefix}total_win_loss_report WHERE report_date >= ? AND report_date <= ? AND win_loss != ? AND player_id <= ? GROUP BY player_id", array($start_time, $end_time, 0, $last_player_id));
						foreach($win_loss_query->result() as $win_loss_row){
							$player_lists[$win_loss_row->player_id]['is_win_loss'] = TRUE;
							$player_lists[$win_loss_row->player_id]['win_loss'] = $win_loss_row->win_loss;
						}
						$win_loss_query->free_result();
					}

					if(!empty($player_lists)){
						$player_risk_query = $this->db->query("SELECT player_risk_id, player_id, percentage, total_win_lose, win_loss_suspend, suspended FROM {$dbprefix}player_risk_report WHERE report_date = ? AND end_date = ?", array($start_time, $end_time));
						foreach($player_risk_query->result() as $player_risk_row){
							$player_lists[$player_risk_row->player_id]['is_old_risk'] = TRUE;
							$PBdata = array(
								'player_risk_id' => $player_risk_row->player_risk_id,
								'player_id' => $player_risk_row->player_id,
								'percentage' => $player_risk_row->percentage,
								'total_win_lose' => $player_risk_row->total_win_lose,
								'win_loss_suspend' => $player_risk_row->win_loss_suspend,
								'suspended' => $player_risk_row->suspended,
							);
							$player_lists[$player_risk_row->player_id]['old_risk_data'] = $PBdata;
						}
						$player_risk_query->free_result();
					}
					if(!empty($player_lists)){
						foreach($player_lists as $player_lists_row){
							if($player_lists_row['is_win_loss']){
								if($player_lists_row['win_loss'] > 0){
									//ad($player_lists_row);
									$percentage = 0;
									if($player_lists_row['win_loss'] >= $player_lists_row['win_loss_suspend_limit']){
										$percentage = 100;
										$PBdata = array(
											'player_id' => $player_lists_row['player_id'],
											'report_date' => $start_time,
											'end_date' => $end_time,
											'percentage' => $percentage,
											'total_win_lose' => $player_lists_row['win_loss'],
											'win_loss_suspend' => $player_lists_row['win_loss_suspend_limit'],
											'suspended' => STATUS_ACTIVE,
											'updated_date' => time(),
											'created_date' => time(),
										);
										$player_lists[$player_lists_row['player_id']]['new_risk_data'] = $PBdata;
										$player_lists[$player_lists_row['player_id']]['is_new_risk'] = TRUE;
										$player_lists[$player_lists_row['player_id']]['is_ban'] = TRUE;
									}else{
										$percentage_calculate = bcdiv((($player_lists_row['win_loss']/$player_lists_row['win_loss_suspend_limit'])*100),1,0);
										if(!empty($risk_announcement_rate)){
											foreach($risk_announcement_rate as $risk_announcement_rate_row){
												if($percentage_calculate >= $risk_announcement_rate_row){
													$percentage = $risk_announcement_rate_row;
												}
											}
										}

										if($percentage){
											$PBdata = array(
												'player_id' => $player_lists_row['player_id'],
												'report_date' => $start_time,
												'end_date' => $end_time,
												'percentage' => $percentage,
												'total_win_lose' => $player_lists_row['win_loss'],
												'win_loss_suspend' => $player_lists_row['win_loss_suspend_limit'],
												'suspended' => STATUS_INACTIVE,
												'updated_date' => time(),
												'created_date' => time(),
											);
											$player_lists[$player_lists_row['player_id']]['new_risk_data'] = $PBdata;
											$player_lists[$player_lists_row['player_id']]['is_new_risk'] = TRUE;
										}
									}
								}
							}
						}
					}
					if(!empty($player_lists)){
						foreach($player_lists as $player_lists_row){
							if($player_lists_row['is_old_risk']){
								if($player_lists_row['is_new_risk']){
									if($player_lists_row['old_risk_data']['percentage'] != $player_lists_row['new_risk_data']['percentage']){
										//update data
										$this->db->query("UPDATE {$dbprefix}player_risk_report SET percentage = ?, total_win_lose = ?, win_loss_suspend = ?, suspended = ? WHERE player_risk_id = ? LIMIT 1", array($player_lists_row['new_risk_data']['percentage'], $player_lists_row['new_risk_data']['total_win_lose'], $player_lists_row['new_risk_data']['win_loss_suspend'], $player_lists_row['new_risk_data']['suspended'], $player_lists_row['old_risk_data']['player_risk_id']));
										array_push($this->telegram_player_id_array, $player_lists_row['new_risk_data']);
									}
								}else{
									//DELETE RECORD
									$this->db->query("DELETE FROM {$dbprefix}player_risk_report WHERE player_risk_id = ? LIMIT 1", array($player_lists_row['old_risk_data']['player_risk_id']));
								}
							}else if($player_lists_row['is_new_risk']){
								array_push($Bdata, $player_lists_row['new_risk_data']);
								array_push($this->telegram_player_id_array, $player_lists_row['new_risk_data']);
							}

							if($player_lists_row['is_ban']){
								$this->db->query("UPDATE {$dbprefix}players SET login_token = ?, active = ? WHERE player_id = ? LIMIT 1", array('', STATUS_SUSPEND, $player_lists_row['player_id']));
							}
						}

						if(!empty($Bdata)){
							$this->db->insert_batch('player_risk_report', $Bdata);
						}
					}


					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
				}
				$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
				unset($player_lists);
				unset($Bdata);
			}
		}
	}

	public function risk_cron_telegram_percent(){
		set_time_limit(0);
		$current_time = time();

		//$today_start_date 	= date('Y-m-d 00:00:00',1671724800);
		//$today_end_date 	= date('Y-m-d 23:59:59',1671724800);
		$today_start_date 	= date('Y-m-d 00:00:00',$current_time);
		$today_end_date 	= date('Y-m-d 23:59:59',$current_time);
		$month_start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
		$month_end_date		= date('Y-m-d 23:59:59', strtotime('last day of this month'));
		$years_start_date 	= date('Y-01-01 00:00:00',$current_time);
		$years_end_date 	= date('Y-12-31 23:59:59',$current_time);


		$today_start_time = strtotime($today_start_date);
		$today_end_time = strtotime($today_end_date);
		$month_start_time = strtotime($month_start_date);
		$month_end_time = strtotime($month_end_date);
		$years_start_time = strtotime($years_start_date);
		$years_end_time = strtotime($years_end_date);


		$player_lists = array();
		$player_ids_array = array();
		$player_ids = "";
		$dbprefix = $this->db->dbprefix;

		//$this->telegram_player_id_array = json_decode('[{"player_id":"41809","report_date":0,"end_date":1666195199,"percentage":100,"total_win_lose":"2542.25","win_loss_suspend":"2289","suspended":1,"updated_date":1666156286,"created_date":1666156286},{"player_id":"1365","report_date":0,"end_date":1666195199,"percentage":"90","total_win_lose":"3182.16","win_loss_suspend":"3535","suspended":0,"updated_date":1666156286,"created_date":1666156286}]',true);
		if(!empty($this->telegram_player_id_array)){
			foreach($this->telegram_player_id_array as $telegram_player_id_row){
				$player_lists[$telegram_player_id_row['player_id']]['today_deposit'] = array(
					'offline_deposit' => 0,
					'online_deposit' => 0,
					'hypermart_deposit' => 0,
					'credit_card_deposit' => 0,
					'point_deposit' => 0,
					'adjust_deposit' => 0,
					'total_deposit' => 0,
				);
				$player_lists[$telegram_player_id_row['player_id']]['month_deposit'] = array(
					'offline_deposit' => 0,
					'online_deposit' => 0,
					'hypermart_deposit' => 0,
					'credit_card_deposit' => 0,
					'point_deposit' => 0,
					'adjust_deposit' => 0,
					'total_deposit' => 0,
				);
				$player_lists[$telegram_player_id_row['player_id']]['today_winloss'] = array(
					GAME_SPORTSBOOK => array(),
					GAME_LIVE_CASINO => array(),
					GAME_SLOTS => array(),
					GAME_FISHING => array(),
					GAME_ESPORTS => array(),
					GAME_BOARD_GAME => array(),
					GAME_LOTTERY => array(),
					GAME_KENO => array(),
					GAME_VIRTUAL_SPORTS => array(),
					GAME_POKER => array(),
					GAME_COCKFIGHTING => array(),
					GAME_OTHERS => array(),
				);
				$player_lists[$telegram_player_id_row['player_id']]['month_winloss'] = array(
					GAME_SPORTSBOOK => array(),
					GAME_LIVE_CASINO => array(),
					GAME_SLOTS => array(),
					GAME_FISHING => array(),
					GAME_ESPORTS => array(),
					GAME_BOARD_GAME => array(),
					GAME_LOTTERY => array(),
					GAME_KENO => array(),
					GAME_VIRTUAL_SPORTS => array(),
					GAME_POKER => array(),
					GAME_COCKFIGHTING => array(),
					GAME_OTHERS => array(),
				);
				$player_lists[$telegram_player_id_row['player_id']]['years_winloss'] = 0;
				$player_lists[$telegram_player_id_row['player_id']]['risk_data'] = $telegram_player_id_row;
				array_push($player_ids_array, $telegram_player_id_row['player_id']);
			}
		}

		if(!empty($player_lists)){
			$player_ids = implode(',', $player_ids_array);
			$player_query = $this->db->query("SELECT player_id, username, upline, created_date FROM {$dbprefix}players WHERE player_id IN (".$player_ids.") ORDER BY player_id ASC");
			if($player_query->num_rows() > 0) {
				foreach($player_query->result() as $player_row){
					$player_lists[$player_row->player_id]['player_id'] = $player_row->player_id;
					$player_lists[$player_row->player_id]['username'] = $player_row->username;
					$player_lists[$player_row->player_id]['upline'] = $player_row->upline;
					$player_lists[$player_row->player_id]['register_date'] = $player_row->created_date;
				}
			}
			$player_query->free_result();

			$today_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($today_start_time,$today_end_time));
			if($today_deposit_query->num_rows() > 0) {
				foreach($today_deposit_query->result() as $today_deposit_row){
					if(isset($player_lists[$today_deposit_row->player_id])){
						$player_lists[$today_deposit_row->player_id]['today_deposit']['total_deposit'] += ($today_deposit_row->deposit_offline_amount + $today_deposit_row->deposit_online_online_amount + $today_deposit_row->deposit_online_credit_amount + $today_deposit_row->deposit_online_hypermart_amount + $today_deposit_row->deposit_point_amount  + $today_deposit_row->adjust_in_amount);
						$player_lists[$today_deposit_row->player_id]['today_deposit']['offline_deposit'] += $today_deposit_row->deposit_offline_amount;
						$player_lists[$today_deposit_row->player_id]['today_deposit']['online_deposit'] += $today_deposit_row->deposit_online_online_amount;
						$player_lists[$today_deposit_row->player_id]['today_deposit']['credit_card_deposit'] += $today_deposit_row->deposit_online_credit_amount;
						$player_lists[$today_deposit_row->player_id]['today_deposit']['hypermart_deposit'] += $today_deposit_row->deposit_online_hypermart_amount;
						$player_lists[$today_deposit_row->player_id]['today_deposit']['point_deposit'] += $today_deposit_row->deposit_point_amount;
						$player_lists[$today_deposit_row->player_id]['today_deposit']['adjust_deposit'] += $today_deposit_row->adjust_in_amount;
					}
				}
			}
			$today_deposit_query->free_result();

			$month_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($month_start_time,$month_end_time));
			if($month_deposit_query->num_rows() > 0) {
				foreach($month_deposit_query->result() as $month_deposit_row){
					if(isset($player_lists[$month_deposit_row->player_id])){
						$player_lists[$month_deposit_row->player_id]['month_deposit']['total_deposit'] += ($month_deposit_row->deposit_offline_amount + $month_deposit_row->deposit_online_online_amount + $month_deposit_row->deposit_online_credit_amount + $month_deposit_row->deposit_online_hypermart_amount + $month_deposit_row->deposit_point_amount  + $month_deposit_row->adjust_in_amount);
						$player_lists[$month_deposit_row->player_id]['month_deposit']['offline_deposit'] += $month_deposit_row->deposit_offline_amount;
						$player_lists[$month_deposit_row->player_id]['month_deposit']['online_deposit'] += $month_deposit_row->deposit_online_online_amount;
						$player_lists[$month_deposit_row->player_id]['month_deposit']['credit_card_deposit'] += $month_deposit_row->deposit_online_credit_amount;
						$player_lists[$month_deposit_row->player_id]['month_deposit']['hypermart_deposit'] += $month_deposit_row->deposit_online_hypermart_amount;
						$player_lists[$month_deposit_row->player_id]['month_deposit']['point_deposit'] += $month_deposit_row->deposit_point_amount;
						$player_lists[$month_deposit_row->player_id]['month_deposit']['adjust_deposit'] += $month_deposit_row->adjust_in_amount;
					}
				}
			}
			$month_deposit_query->free_result();

			//WIN LOSS
			$today_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($today_start_time,$today_end_time));
			if($today_win_loss_query->num_rows() > 0) {
				foreach($today_win_loss_query->result() as $today_win_loss_row){
					if(isset($player_lists[$today_win_loss_row->player_id])){
						$player_lists[$today_win_loss_row->player_id]['today_winloss'][$today_win_loss_row->game_type_code][$today_win_loss_row->game_provider_code] = $today_win_loss_row->amount;
					}
				}
			}
			$today_win_loss_query->free_result();

			$month_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($month_start_time,$month_end_time));
			if($month_win_loss_query->num_rows() > 0) {
				foreach($month_win_loss_query->result() as $month_win_loss_row){
					if(isset($player_lists[$month_win_loss_row->player_id])){
						$player_lists[$month_win_loss_row->player_id]['month_winloss'][$month_win_loss_row->game_type_code][$month_win_loss_row->game_provider_code] = $month_win_loss_row->amount;
					}
				}
			}
			$month_win_loss_query->free_result();

			$years_win_loss_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}total_win_loss_report_month WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id",array($years_start_time,$years_end_time));
			if($years_win_loss_query->num_rows() > 0) {
				foreach($years_win_loss_query->result() as $years_win_loss_row){
					if(isset($player_lists[$years_win_loss_row->player_id])){
						$player_lists[$years_win_loss_row->player_id]['years_winloss'] = $years_win_loss_row->amount;
					}
				}
			}
			$years_win_loss_query->free_result();

			$message = "";
			if(!empty($player_lists)){
				foreach($player_lists as $player_lists_row){
					$message = "";
					//NAME REGISTER
					$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
					$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";
					//TODAY DEPOSIT
					if(!empty($player_lists_row['today_deposit']['total_deposit'])){
						$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
						if(!empty($player_lists_row['today_deposit']['online_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['today_deposit']['point_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
						}

						$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
					}

					//Month DEPOSIT
					if(!empty($player_lists_row['month_deposit']['total_deposit'])){
						$message .= $this->lang->line('lang_telegram_risk_today_month')."\r\n";
						if(!empty($player_lists_row['month_deposit']['online_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['month_deposit']['online_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['month_deposit']['hypermart_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['month_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['month_deposit']['credit_card_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['month_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['month_deposit']['offline_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['month_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['month_deposit']['adjust_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['month_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
						}

						if(!empty($player_lists_row['month_deposit']['point_deposit'])){
							$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['month_deposit']['point_deposit'], 0, '.', ',')."\r\n";
						}

						$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['month_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
					}

					//Today Winloss
					$total = 0;
					if(!empty($player_lists_row['today_winloss'])){
						$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
						foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
							if(!empty($game_type_value)){
								foreach($game_type_value as $win_loss_key => $win_loss_value){
									if($win_loss_value >= 0){
										$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
									}else{
										$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
									}
									$total += $win_loss_value;
								}
							}
						}
						if($total >= 0){
							$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
						}else{
							$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
						}
					}

					$total = 0;
					if(!empty($player_lists_row['month_winloss'])){
						$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
						foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
							if(!empty($game_type_value)){
								foreach($game_type_value as $win_loss_key => $win_loss_value){
									if($win_loss_value >= 0){
										$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
									}else{
										$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
									}
									$total += $win_loss_value;
								}
							}
						}
						if($total >= 0){
							$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
						}else{
							$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
						}
					}

					$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
					if($player_lists_row['years_winloss'] >= 0){
						$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
					}else{
						$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
					}

					//$message .= $this->lang->line('lang_telegram_risk_reach_winloss')." : ".number_format($player_lists_row['risk_data']['win_loss_suspend'], 0, '.', ',')."\r\n";
					$message .= $this->lang->line('lang_telegram_risk_reach_winloss_percent')." : ".$player_lists_row['risk_data']['percentage']."%\r\n";

					send_message_telegram(TELEGRAM_RISK,$message);
				}
			}

			unset($player_lists);
			unset($player_ids_array);
			unset($this->telegram_player_id_array);
		}
	}

	public function risk_cron_telegram_custom_one(){
		set_time_limit(0);
		$slot_win_loss_amount_capture = 50000;
		$slot_win_loss_risk_code = 1;
		$casino_win_loss_amount_capture = 30000;
		$casino_win_loss_risk_code = 2;
		$sportbook_win_loss_amount_capture = 50000;
		$sportbook_win_loss_risk_code = 3;
		$risk_code_ids = "1,2,3";
		$current_time = time();

		$today_start_date 	= date('Y-m-d 00:00:00',$current_time);
		$today_end_date 	= date('Y-m-d 23:59:59',$current_time);
		$month_start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
		$month_end_date		= date('Y-m-d 23:59:59', strtotime('last day of this month'));
		$years_start_date 	= date('Y-01-01 00:00:00',$current_time);
		$years_end_date 	= date('Y-12-31 23:59:59',$current_time);


		$today_start_time = strtotime($today_start_date);
		$today_end_time = strtotime($today_end_date);
		$month_start_time = strtotime($month_start_date);
		$month_end_time = strtotime($month_end_date);
		$years_start_time = strtotime($years_start_date);
		$years_end_time = strtotime($years_end_date);

		$live_casino_bacarrat_array = array(GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_NO_COMMISSION_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_VIP_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT,GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT);

		$player_lists = array();
		$player_lists_capture = array();
		$player_lists_live_casino_capture = array();
		$player_ids_array = array();
		$Bdata = array();
		$player_ids = "";
		$dbprefix = $this->db->dbprefix;


		//get old data
		$old_data_capture_query = $this->db->query("SELECT * FROM {$dbprefix}player_risk_report_custom WHERE risk_code IN (".$risk_code_ids.") AND report_date = ?",array($today_start_time));
		if($old_data_capture_query->num_rows() > 0) {
			foreach($old_data_capture_query->result() as $old_data_capture_row){
				if(!isset($player_lists_capture[$old_data_capture_row->player_id])){
					$player_lists_capture[$old_data_capture_row->player_id][$slot_win_loss_risk_code]['is_old_slot_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$slot_win_loss_risk_code]['old_slot_max_data'] = array();
					$player_lists_capture[$old_data_capture_row->player_id][$slot_win_loss_risk_code]['is_new_slot_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$slot_win_loss_risk_code]['new_slot_max_data'] = array();

					$player_lists_capture[$old_data_capture_row->player_id][$casino_win_loss_risk_code]['is_old_casino_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$casino_win_loss_risk_code]['old_casino_max_data'] = array();
					$player_lists_capture[$old_data_capture_row->player_id][$casino_win_loss_risk_code]['is_new_casino_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$casino_win_loss_risk_code]['new_casino_max_data'] = array();

					$player_lists_capture[$old_data_capture_row->player_id][$sportbook_win_loss_risk_code]['is_old_sportbook_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$sportbook_win_loss_risk_code]['old_sportbook_max_data'] = array();
					$player_lists_capture[$old_data_capture_row->player_id][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = FALSE;
					$player_lists_capture[$old_data_capture_row->player_id][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = array();
				}

				if($old_data_capture_row->risk_code == $slot_win_loss_risk_code){
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['is_old_slot_max'] = TRUE;
					$PBdata = array(
						'player_risk_custom_id' => $old_data_capture_row->player_risk_custom_id,
						'player_id' => $old_data_capture_row->player_id,
						'risk_code' => $old_data_capture_row->risk_code,
						'report_date' => $old_data_capture_row->report_date,
						'game_provider_code' => $old_data_capture_row->game_provider_code,
						'game_type_code' => $old_data_capture_row->game_type_code,
						'game_code' => $old_data_capture_row->game_code,
						'total_win_lose' => $old_data_capture_row->total_win_lose,
					);
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['old_slot_max_data'] = $PBdata;
				}else if($old_data_capture_row->risk_code == $casino_win_loss_risk_code){
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['is_old_casino_max'] = TRUE;
					$PBdata = array(
						'player_risk_custom_id' => $old_data_capture_row->player_risk_custom_id,
						'player_id' => $old_data_capture_row->player_id,
						'risk_code' => $old_data_capture_row->risk_code,
						'report_date' => $old_data_capture_row->report_date,
						'game_provider_code' => $old_data_capture_row->game_provider_code,
						'game_type_code' => $old_data_capture_row->game_type_code,
						'game_code' => $old_data_capture_row->game_code,
						'total_win_lose' => $old_data_capture_row->total_win_lose,
					);
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['old_casino_max_data'][$old_data_capture_row->game_code] = $PBdata;
				}else{
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['is_old_sportbook_max'] = TRUE;
					$PBdata = array(
						'player_risk_custom_id' => $old_data_capture_row->player_risk_custom_id,
						'player_id' => $old_data_capture_row->player_id,
						'risk_code' => $old_data_capture_row->risk_code,
						'report_date' => $old_data_capture_row->report_date,
						'game_provider_code' => $old_data_capture_row->game_provider_code,
						'game_type_code' => $old_data_capture_row->game_type_code,
						'game_code' => $old_data_capture_row->game_code,
						'total_win_lose' => $old_data_capture_row->total_win_lose,
					);
					$player_lists_capture[$old_data_capture_row->player_id][$old_data_capture_row->risk_code]['old_sportbook_max_data'] = $PBdata;
				}
			}
		}

		//slot winloss
		$today_slot_win_loss_capture_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE game_type_code = ? AND report_date >= ? AND report_date <= ? GROUP BY player_id HAVING amount >= ?",array(GAME_SLOTS,$today_start_time,$today_end_time,$slot_win_loss_amount_capture));
		if($today_slot_win_loss_capture_query->num_rows() > 0) {
			foreach($today_slot_win_loss_capture_query->result() as $today_slot_win_loss_capture_row){
				if($player_lists_capture[$today_slot_win_loss_capture_row->player_id][$slot_win_loss_risk_code]['is_old_slot_max'] == FALSE){
					$player_lists_capture[$today_slot_win_loss_capture_row->player_id][$slot_win_loss_risk_code]['is_new_slot_max'] = TRUE;
					$PBdata = array(
						'player_id' => $today_slot_win_loss_capture_row->player_id,
						'risk_code' => $slot_win_loss_risk_code,
						'report_date' => $today_start_time,
						'game_provider_code' => 0,
						'game_type_code' => GAME_SLOTS,
						'game_code' => 0,
						'total_win_lose' => $today_slot_win_loss_capture_row->amount,
					);
					$player_lists_capture[$today_slot_win_loss_capture_row->player_id][$slot_win_loss_risk_code]['new_slot_max_data'] = $PBdata;
				}
			}
		}
		$today_slot_win_loss_capture_query->free_result();
		
		//live winloss
		$game_code = "";
		$live_casino_bacarrat_ids = implode("','", $live_casino_bacarrat_array);
		$today_casino_win_loss_capture_query = $this->db->query("SELECT player_id,game_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report_by_game_code WHERE game_type_code = ? AND report_date >= ? AND report_date <= ? GROUP BY player_id, game_code",array(GAME_LIVE_CASINO,$today_start_time,$today_end_time));
		if($today_casino_win_loss_capture_query->num_rows() > 0) {
			foreach($today_casino_win_loss_capture_query->result() as $today_casino_win_loss_capture_row){
				$game_code = "";
				switch($today_casino_win_loss_capture_row->game_code)
				{
					case GAME_CODE_TYPE_LIVE_CASINO_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_NO_COMMISSION_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_VIP_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT:
					case GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT: $game_code = 'BAC'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER: 
					case GAME_CODE_TYPE_LIVE_CASINO_NEW_DRAGON_TIGER:
					case GAME_CODE_TYPE_LIVE_CASINO_LIVE_DRAGON_TIGER:
					case GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_DRAGON_TIGER: $game_code = 'DT'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL: 
					case GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BULL_BULL: $game_code = 'OX'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA: 
					case GAME_CODE_TYPE_LIVE_CASINO_LUCKY_ZHA_JIN_HUA: 
					case GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_ZHA_JIN_HUA: $game_code = 'ZJH'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER: 
					case GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_THREE_FACE_POKER: $game_code = 'TFP'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_ROULETTE: $game_code = 'RO'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_SICBO: $game_code = 'DI'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN: $game_code = 'FT'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_SEDIE: $game_code = 'SEDIE'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_POK_DENG: $game_code = 'PD'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_ROCK_PAPER_SCISSORS: $game_code = 'RPS'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR: $game_code = 'ADBH'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_FISH_PRAWN_CRAB: $game_code = 'FPC'; break;
					case GAME_CODE_TYPE_LIVE_CASINO_MONEYWHEEL: $game_code = 'MW'; break;
					case GAME_CODE_TYPE_MEMBER_SEND_GIFT: 
					case GAME_CODE_TYPE_MEMBER_GET_GIFT: 
					case GAME_CODE_TYPE_ANCHOR_SEND_TIPS: 
					case GAME_CODE_TYPE_COMPANY_SEND_GIFT: 
					case GAME_CODE_TYPE_BO_BING: 
					case GAME_CODE_TYPE_CROUPIER_SEND_TIPS: $game_code = 'TIPS'; break;
					default: $game_code = 'UNKNOWN'; break;
				}
                if($game_code != "BAC"){
					$player_lists_live_casino_capture[$today_casino_win_loss_capture_row->player_id][$game_code] += $today_casino_win_loss_capture_row->amount;	
				}
			}
		}

		if(sizeof($player_lists_live_casino_capture)>0){
			foreach($player_lists_live_casino_capture as $k => $v){
				if(!empty($v)){
					foreach($v as $v_key => $v_value){
						if($v_value >= $casino_win_loss_amount_capture){
							if($player_lists_capture[$k][$casino_win_loss_risk_code]['is_old_casino_max'] == FALSE){
								$player_lists_capture[$k][$casino_win_loss_risk_code]['is_new_casino_max'] = TRUE;
								$PBdata = array(
									'player_id' => $k,
									'risk_code' => $casino_win_loss_risk_code,
									'report_date' => $today_start_time,
									'game_provider_code' => 0,
									'game_type_code' => GAME_LIVE_CASINO,
									'game_code' => $v_key,
									'total_win_lose' => $v_value,
								);
								$player_lists_capture[$k][$casino_win_loss_risk_code]['new_casino_max_data'][$v_key] = $PBdata;
							}else{
								if(!isset($player_lists_capture[$k][$casino_win_loss_risk_code]['old_casino_max_data'][$v_key])){
									$player_lists_capture[$k][$casino_win_loss_risk_code]['is_new_casino_max'] = TRUE;
									$PBdata = array(
										'player_id' => $k,
										'risk_code' => $casino_win_loss_risk_code,
										'report_date' => $today_start_time,
										'game_provider_code' => 0,
										'game_type_code' => GAME_LIVE_CASINO,
										'game_code' => $v_key,
										'total_win_lose' => $v_value,
									);
									$player_lists_capture[$k][$casino_win_loss_risk_code]['new_casino_max_data'][$v_key] = $PBdata;
								}
							}
						}
					}
				}
			}
		}
		$today_casino_win_loss_capture_query->free_result();

		//sportbook winloss
		$today_sportbook_win_loss_capture_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE game_type_code = ? AND report_date >= ? AND report_date <= ? GROUP BY player_id HAVING amount >= ?",array(GAME_SPORTSBOOK,$today_start_time,$today_end_time,$sportbook_win_loss_amount_capture));
		if($today_sportbook_win_loss_capture_query->num_rows() > 0) {
			foreach($today_sportbook_win_loss_capture_query->result() as $today_sportbook_win_loss_capture_row){
				if($player_lists_capture[$today_sportbook_win_loss_capture_row->player_id][$sportbook_win_loss_risk_code]['is_old_sportbook_max'] == FALSE){
					$player_lists_capture[$today_sportbook_win_loss_capture_row->player_id][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = TRUE;
					$PBdata = array(
						'player_id' => $today_sportbook_win_loss_capture_row->player_id,
						'risk_code' => $sportbook_win_loss_risk_code,
						'report_date' => $today_start_time,
						'game_provider_code' => 0,
						'game_type_code' => GAME_SPORTSBOOK,
						'game_code' => 0,
						'total_win_lose' => $today_sportbook_win_loss_capture_row->amount,
					);
					$player_lists_capture[$today_sportbook_win_loss_capture_row->player_id][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = $PBdata;
				}
			}
		}
		$today_sportbook_win_loss_capture_query->free_result();

		if(!empty($player_lists_capture)){
			foreach($player_lists_capture as $player_lists_capture_key => $player_lists_capture_row){
				//Detect Slot
				if($player_lists_capture_row[$slot_win_loss_risk_code]['is_new_slot_max']){
					if(!isset($player_lists[$player_lists_capture_key])){
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['is_new_slot_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['new_slot_max_data'] = array();
						
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['is_new_casino_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['new_casino_max_data'] = array();

						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = array();

						$player_lists[$player_lists_capture_key]['today_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['month_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['today_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['month_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['game_balance'] = 0;
						$player_lists[$player_lists_capture_key]['pending_withdrawal'] = 0;
						array_push($player_ids_array, $player_lists_capture_key);
					}

					$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['is_new_slot_max'] = $player_lists_capture_row[$slot_win_loss_risk_code]['is_new_slot_max'];
					$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['new_slot_max_data'] = $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data'];
					$PBdata = array(
						'player_id' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['player_id'],
						'risk_code' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['risk_code'],
						'game_provider_code' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['game_provider_code'],
						'game_type_code' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['game_type_code'],
						'game_code' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['game_code'],
						'total_win_lose' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['total_win_lose'],
						'report_date' => $player_lists_capture_row[$slot_win_loss_risk_code]['new_slot_max_data']['report_date'],
					);
					array_push($Bdata, $PBdata);
				}

				if($player_lists_capture_row[$casino_win_loss_risk_code]['is_new_casino_max']){
					if(!isset($player_lists[$player_lists_capture_key])){
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['is_new_slot_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['new_slot_max_data'] = array();
						
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['is_new_casino_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['new_casino_max_data'] = array();

						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = array();

						$player_lists[$player_lists_capture_key]['today_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['month_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['today_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['month_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['game_balance'] = 0;
						$player_lists[$player_lists_capture_key]['pending_withdrawal'] = 0;
						array_push($player_ids_array, $player_lists_capture_key);
					}
					$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['is_new_casino_max'] = $player_lists_capture_row[$casino_win_loss_risk_code]['is_new_casino_max'];
					$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['new_casino_max_data'] = $player_lists_capture_row[$casino_win_loss_risk_code]['new_casino_max_data'];
					if(!empty($player_lists_capture_row[$casino_win_loss_risk_code]['new_casino_max_data'])){
						foreach($player_lists_capture_row[$casino_win_loss_risk_code]['new_casino_max_data'] as $player_lists_capture_row_casino_row){
							$PBdata = array(
								'player_id' => $player_lists_capture_row_casino_row['player_id'],
								'risk_code' => $player_lists_capture_row_casino_row['risk_code'],
								'game_provider_code' => $player_lists_capture_row_casino_row['game_provider_code'],
								'game_type_code' => $player_lists_capture_row_casino_row['game_type_code'],
								'game_code' => $player_lists_capture_row_casino_row['game_code'],
								'total_win_lose' => $player_lists_capture_row_casino_row['total_win_lose'],
								'report_date' => $player_lists_capture_row_casino_row['report_date'],
							);
							array_push($Bdata, $PBdata);
						}
					}
				}

				if($player_lists_capture_row[$sportbook_win_loss_risk_code]['is_new_sportbook_max']){
					if(!isset($player_lists[$player_lists_capture_key])){
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['is_new_slot_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$slot_win_loss_risk_code]['new_slot_max_data'] = array();
						
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['is_new_casino_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$casino_win_loss_risk_code]['new_casino_max_data'] = array();

						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = FALSE;
						$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = array();

						$player_lists[$player_lists_capture_key]['today_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['month_deposit'] = array(
							'offline_deposit' => 0,
							'online_deposit' => 0,
							'hypermart_deposit' => 0,
							'credit_card_deposit' => 0,
							'adjust_deposit' => 0,
							'total_deposit' => 0,
						);
						$player_lists[$player_lists_capture_key]['today_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['month_winloss'] = array(
							GAME_SPORTSBOOK => array(),
							GAME_LIVE_CASINO => array(),
							GAME_SLOTS => array(),
							GAME_FISHING => array(),
							GAME_ESPORTS => array(),
							GAME_BOARD_GAME => array(),
							GAME_LOTTERY => array(),
							GAME_KENO => array(),
							GAME_VIRTUAL_SPORTS => array(),
							GAME_POKER => array(),
							GAME_COCKFIGHTING => array(),
							GAME_OTHERS => array(),
						);
						$player_lists[$player_lists_capture_key]['game_balance'] = 0;
						$player_lists[$player_lists_capture_key]['pending_withdrawal'] = 0;
						array_push($player_ids_array, $player_lists_capture_key);
					}
					$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['is_new_sportbook_max'] = $player_lists_capture_row[$sportbook_win_loss_risk_code]['is_new_sportbook_max'];
					$player_lists[$player_lists_capture_key][$sportbook_win_loss_risk_code]['new_sportbook_max_data'] = $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data'];
					$PBdata = array(
						'player_id' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['player_id'],
						'risk_code' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['risk_code'],
						'game_provider_code' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['game_provider_code'],
						'game_type_code' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['game_type_code'],
						'game_code' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['game_code'],
						'total_win_lose' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['total_win_lose'],
						'report_date' => $player_lists_capture_row[$sportbook_win_loss_risk_code]['new_sportbook_max_data']['report_date'],
					);
					array_push($Bdata, $PBdata);
				}
			}

			if(!empty($Bdata)){
				$this->db->insert_batch('player_risk_report_custom', $Bdata);
			}

			if(!empty($player_ids_array)){
				$player_ids = implode(',', $player_ids_array);
				//Get All Player Data
				$player_query = $this->db->query("SELECT player_id, username, upline, created_date FROM {$dbprefix}players WHERE player_id IN (".$player_ids.") ORDER BY player_id ASC");
				if($player_query->num_rows() > 0) {
					foreach($player_query->result() as $player_row){
						$player_lists[$player_row->player_id]['player_id'] = $player_row->player_id;
						$player_lists[$player_row->player_id]['username'] = $player_row->username;
						$player_lists[$player_row->player_id]['upline'] = $player_row->upline;
						$player_lists[$player_row->player_id]['register_date'] = $player_row->created_date;
					}
				}
				$player_query->free_result();

				$today_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($today_start_time,$today_end_time));
				if($today_deposit_query->num_rows() > 0) {
					foreach($today_deposit_query->result() as $today_deposit_row){
						if(isset($player_lists[$today_deposit_row->player_id])){
							$player_lists[$today_deposit_row->player_id]['today_deposit']['total_deposit'] += ($today_deposit_row->deposit_offline_amount + $today_deposit_row->deposit_online_online_amount + $today_deposit_row->deposit_online_credit_amount + $today_deposit_row->deposit_online_hypermart_amount + $today_deposit_row->deposit_point_amount  + $today_deposit_row->adjust_in_amount);
							$player_lists[$today_deposit_row->player_id]['today_deposit']['offline_deposit'] += $today_deposit_row->deposit_offline_amount;
							$player_lists[$today_deposit_row->player_id]['today_deposit']['online_deposit'] += $today_deposit_row->deposit_online_online_amount;
							$player_lists[$today_deposit_row->player_id]['today_deposit']['credit_card_deposit'] += $today_deposit_row->deposit_online_credit_amount;
							$player_lists[$today_deposit_row->player_id]['today_deposit']['hypermart_deposit'] += $today_deposit_row->deposit_online_hypermart_amount;
							$player_lists[$today_deposit_row->player_id]['today_deposit']['point_deposit'] += $today_deposit_row->deposit_point_amount;
							$player_lists[$today_deposit_row->player_id]['today_deposit']['adjust_deposit'] += $today_deposit_row->adjust_in_amount;
						}
					}
				}
				$today_deposit_query->free_result();

				$month_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($month_start_time,$month_end_time));
				if($month_deposit_query->num_rows() > 0) {
					foreach($month_deposit_query->result() as $month_deposit_row){
						if(isset($player_lists[$month_deposit_row->player_id])){
							$player_lists[$month_deposit_row->player_id]['month_deposit']['total_deposit'] += ($month_deposit_row->deposit_offline_amount + $month_deposit_row->deposit_online_online_amount + $month_deposit_row->deposit_online_credit_amount + $month_deposit_row->deposit_online_hypermart_amount + $month_deposit_row->deposit_point_amount  + $month_deposit_row->adjust_in_amount);
							$player_lists[$month_deposit_row->player_id]['month_deposit']['offline_deposit'] += $month_deposit_row->deposit_offline_amount;
							$player_lists[$month_deposit_row->player_id]['month_deposit']['online_deposit'] += $month_deposit_row->deposit_online_online_amount;
							$player_lists[$month_deposit_row->player_id]['month_deposit']['credit_card_deposit'] += $month_deposit_row->deposit_online_credit_amount;
							$player_lists[$month_deposit_row->player_id]['month_deposit']['hypermart_deposit'] += $month_deposit_row->deposit_online_hypermart_amount;
							$player_lists[$month_deposit_row->player_id]['month_deposit']['point_deposit'] += $month_deposit_row->deposit_point_amount;
							$player_lists[$month_deposit_row->player_id]['month_deposit']['adjust_deposit'] += $month_deposit_row->adjust_in_amount;
						}
					}
				}
				$month_deposit_query->free_result();

				//WIN LOSS
				$today_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($today_start_time,$today_end_time));
				if($today_win_loss_query->num_rows() > 0) {
					foreach($today_win_loss_query->result() as $today_win_loss_row){
						if(isset($player_lists[$today_win_loss_row->player_id])){
							$player_lists[$today_win_loss_row->player_id]['today_winloss'][$today_win_loss_row->game_type_code][$today_win_loss_row->game_provider_code] = $today_win_loss_row->amount;
						}
					}
				}
				$today_win_loss_query->free_result();

				$month_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($month_start_time,$month_end_time));
				if($month_win_loss_query->num_rows() > 0) {
					foreach($month_win_loss_query->result() as $month_win_loss_row){
						if(isset($player_lists[$month_win_loss_row->player_id])){
							$player_lists[$month_win_loss_row->player_id]['month_winloss'][$month_win_loss_row->game_type_code][$month_win_loss_row->game_provider_code] = $month_win_loss_row->amount;
						}
					}
				}
				$month_win_loss_query->free_result();

				$years_win_loss_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}total_win_loss_report_month WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id",array($years_start_time,$years_end_time));
				if($years_win_loss_query->num_rows() > 0) {
					foreach($years_win_loss_query->result() as $years_win_loss_row){
						if(isset($player_lists[$years_win_loss_row->player_id])){
							$player_lists[$years_win_loss_row->player_id]['years_winloss'] = $years_win_loss_row->amount;
						}
					}
				}
				$years_win_loss_query->free_result();


				//Get Player Pending Withdrawal
				$withdrawal_pending_query = $this->db->query("SELECT player_id,sum(amount) as amount FROM {$dbprefix}withdrawals WHERE status = ? AND player_id IN (".$player_ids.") GROUP BY player_id",array(0));
				if($withdrawal_pending_query->num_rows() > 0) {
					foreach($withdrawal_pending_query->result() as $withdrawal_pending_row){
						if(isset($player_lists[$withdrawal_pending_row->player_id])){
							$player_lists[$withdrawal_pending_row->player_id]['pending_withdrawal'] = $withdrawal_pending_row->amount;
						}
					}
				}
				$withdrawal_pending_query->free_result();

				//Get Player Wallet Balance
				if(!empty($player_ids_array)){
					foreach($player_ids_array as $player_ids_row){
						if(isset($player_lists[$player_ids_row])){
							$wallet_result = $this->get_member_total_wallet($player_ids_row);
							$player_lists[$player_ids_row]['game_balance'] = $wallet_result['balance_amount'];
						}
					}
				}

				$message = "";
				$casino_message = "";
				if(!empty($player_lists)){
					foreach($player_lists as $player_lists_row){
						$message = "";
						$casino_message = "";
						if($player_lists_row[$slot_win_loss_risk_code]['is_new_slot_max']){
							$message = "";
							$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
							$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";
							$message .= $this->lang->line('lang_telegram_risk_slot_title')."\r\n\n";
							//TODAY DEPOSIT
							if(!empty($player_lists_row['today_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
								if(!empty($player_lists_row['today_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Month DEPOSIT
							if(!empty($player_lists_row['month_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_month')."\r\n";
								if(!empty($player_lists_row['month_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['month_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['month_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['month_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['month_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['month_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['month_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['month_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Today Winloss
							$total = 0;
							if(!empty($player_lists_row['today_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
								foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
								}
							}

							$total = 0;
							if(!empty($player_lists_row['month_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
								foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
								}
							}

							$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
							if($player_lists_row['years_winloss'] >= 0){
								$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}else{
								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}
							if($player_lists_row['pending_withdrawal'] > 0){
								$message .= $this->lang->line('lang_telegram_risk_pending_withdrawal')." : ".number_format($player_lists_row['pending_withdrawal'], 0, '.', ',')."\r\n";
							}
							$message .= $this->lang->line('lang_telegram_risk_game_balance')." : ".number_format($player_lists_row['game_balance'], 0, '.', ',')."\r\n";
							send_message_telegram(TELEGRAM_RISK,$message);
						}

						if($player_lists_row[$casino_win_loss_risk_code]['is_new_casino_max']){
							$message = "";
							$casino_message = "";
							if(!empty($player_lists_row[$casino_win_loss_risk_code]['new_casino_max_data'])){
								foreach($player_lists_row[$casino_win_loss_risk_code]['new_casino_max_data'] as $new_casino_max_data_key => $new_casino_max_data_val) {
									switch($new_casino_max_data_key)
									{
										case 'BAC': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_baccarat') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_baccarat')); break;
										case 'DT': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_dragon_tiger') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_dragon_tiger')); break;
										case 'OX': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_bull_bull') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_bull_bull')); break;
										case 'ZJH': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_zha_jin_hua') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_zha_jin_hua')); break;
										case 'TFP': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_sam_gong') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_sam_gong')); break;
										case 'RO': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_roulette') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_roulette')); break;
										case 'DI': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_sicbo') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_sicbo')); break;
										case 'FT': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_fan_tan') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_fan_tan')); break;
										case 'SEDIE': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_se_die') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_se_die')); break;
										case 'PD': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_pok_deng') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_pok_deng')); break;
										case 'RPS': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_rock_paper_scissors') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_rock_paper_scissors')); break;
										case 'ADBH': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_andar_bahar') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_andar_bahar')); break;
										case 'FPC': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_fish_prawn_crab_dice') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_fish_prawn_crab_dice')); break;
										case 'MW': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_money_wheel') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_money_wheel')); break;
										case 'TIPS': $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_tip') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_tip')); break;
										default: $casino_message = ((empty($casino_message)) ? $this->lang->line('lang_telegram_risk_casino_type_others') : $casino_message. "," .$this->lang->line('lang_telegram_risk_casino_type_others')); break;
									}
								}
							}


							$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
							$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";
							$message .= $casino_message.$this->lang->line('lang_telegram_risk_casino_title')."\r\n\n";
							//TODAY DEPOSIT
							if(!empty($player_lists_row['today_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
								if(!empty($player_lists_row['today_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Month DEPOSIT
							if(!empty($player_lists_row['month_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_month')."\r\n";
								if(!empty($player_lists_row['month_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['month_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['month_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['month_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['month_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['month_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['month_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['month_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Today Winloss
							$total = 0;
							if(!empty($player_lists_row['today_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
								foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
								}
							}

							$total = 0;
							if(!empty($player_lists_row['month_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
								foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
								}
							}

							$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
							if($player_lists_row['years_winloss'] >= 0){
								$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}else{
								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}
							if($player_lists_row['pending_withdrawal'] > 0){
								$message .= $this->lang->line('lang_telegram_risk_pending_withdrawal')." : ".number_format($player_lists_row['pending_withdrawal'], 0, '.', ',')."\r\n";
							}
							$message .= $this->lang->line('lang_telegram_risk_game_balance')." : ".number_format($player_lists_row['game_balance'], 0, '.', ',')."\r\n";
							send_message_telegram(TELEGRAM_RISK,$message);
						}

						if($player_lists_row[$sportbook_win_loss_risk_code]['is_new_sportbook_max']){
							$message = "";
							$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
							$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";
							$message .= $this->lang->line('lang_telegram_risk_sportbook_title')."\r\n\n";
							//TODAY DEPOSIT
							if(!empty($player_lists_row['today_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
								if(!empty($player_lists_row['today_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['today_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Month DEPOSIT
							if(!empty($player_lists_row['month_deposit']['total_deposit'])){
								$message .= $this->lang->line('lang_telegram_risk_today_month')."\r\n";
								if(!empty($player_lists_row['month_deposit']['online_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['month_deposit']['online_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['hypermart_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['month_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['credit_card_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['month_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['offline_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['month_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['adjust_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['month_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
								}

								if(!empty($player_lists_row['month_deposit']['point_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['month_deposit']['point_deposit'], 0, '.', ',')."\r\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['month_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
							}

							//Today Winloss
							$total = 0;
							if(!empty($player_lists_row['today_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
								foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
								}
							}

							$total = 0;
							if(!empty($player_lists_row['month_winloss'])){
								$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
								foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
									if(!empty($game_type_value)){
										foreach($game_type_value as $win_loss_key => $win_loss_value){
											if($win_loss_value >= 0){
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}else{
												$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
											}
											$total += $win_loss_value;
										}
									}
								}
								if($total >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
								}
							}

							$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
							if($player_lists_row['years_winloss'] >= 0){
								$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}else{
								$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
							}
							if($player_lists_row['pending_withdrawal'] > 0){
								$message .= $this->lang->line('lang_telegram_risk_pending_withdrawal')." : ".number_format($player_lists_row['pending_withdrawal'], 0, '.', ',')."\r\n";
							}
							$message .= $this->lang->line('lang_telegram_risk_game_balance')." : ".number_format($player_lists_row['game_balance'], 0, '.', ',')."\r\n";
							send_message_telegram(TELEGRAM_RISK,$message);
						}
					}
				}

				unset($player_lists);
				unset($player_ids_array);
				unset($player_lists_capture);
				unset($Bdata);
			}
		}
	}

	public function risk_cron_withdrawal(){
		set_time_limit(0);
		$withdrawal_amount_capture = 300000;
		$current_time = time();
		$today_start_date 	= date('Y-m-d 00:00:00',$current_time);
		$today_end_date 	= date('Y-m-d 23:59:59',$current_time);
		$month_start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
		$month_end_date		= date('Y-m-d 23:59:59', strtotime('last day of this month'));
		$years_start_date 	= date('Y-01-01 00:00:00',$current_time);
		$years_end_date 	= date('Y-12-31 23:59:59',$current_time);

		$today_start_time = strtotime($today_start_date);
		$today_end_time = strtotime($today_end_date);
		$month_start_time = strtotime($month_start_date);
		$month_end_time = strtotime($month_end_date);
		$years_start_time = strtotime($years_start_date);
		$years_end_time = strtotime($years_end_date);

		$cron_code = 'RISKWITHD';
		$player_ids = "";
		$player_ids_array = array();
		$player_lists = array();
		$temp_array = array();
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		sleep(20);
		if(!empty($cron_result_data))
		{
			if(!empty($cron_result_data['cron_time'])){
				$end_date	= date('Y-m-d H:i:59', $current_time-60);
				$start_time = $cron_result_data['cron_time']+1;
				$end_time = strtotime($end_date);
			}else{
				$end_date	= date('Y-m-d H:i:59', $current_time-60);
				$start_time = 0;
				$end_time = strtotime($end_date);
			}
			
			if($end_time > $start_time){
				if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
					$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
					$DBdata = array('cron_time' => $end_time);
					$dbprefix = $this->db->dbprefix;
					$withdrawal_capture_query = $this->db->query("SELECT player_id,amount,created_date FROM {$dbprefix}withdrawals WHERE created_date >= ? AND created_date <= ? AND amount >= ? AND status = ?",array($start_time,$end_time,$withdrawal_amount_capture,STATUS_PENDING));
					if($withdrawal_capture_query->num_rows() > 0) {
						foreach($withdrawal_capture_query->result() as $withdrawal_capture_query_row){
							if(isset($player_lists[$withdrawal_capture_query_row->player_id])){
								$temp_array = array(
									'created_date' => $withdrawal_capture_query_row->created_date,
									'amount' => $withdrawal_capture_query_row->amount,
								);
								array_push($player_lists[$withdrawal_capture_query_row->player_id]['withdrawal_data'], $temp_array);
							}else{
								$player_lists[$withdrawal_capture_query_row->player_id]['today_deposit'] = array(
									'offline_deposit' => 0,
									'online_deposit' => 0,
									'hypermart_deposit' => 0,
									'credit_card_deposit' => 0,
									'point_deposit' => 0,
									'adjust_deposit' => 0,
									'total_deposit' => 0,
								);
								$player_lists[$withdrawal_capture_query_row->player_id]['month_deposit'] = array(
									'offline_deposit' => 0,
									'online_deposit' => 0,
									'hypermart_deposit' => 0,
									'credit_card_deposit' => 0,
									'point_deposit' => 0,
									'adjust_deposit' => 0,
									'total_deposit' => 0,
								);
								$player_lists[$withdrawal_capture_query_row->player_id]['today_winloss'] = array(
									GAME_SPORTSBOOK => array(),
									GAME_LIVE_CASINO => array(),
									GAME_SLOTS => array(),
									GAME_FISHING => array(),
									GAME_ESPORTS => array(),
									GAME_BOARD_GAME => array(),
									GAME_LOTTERY => array(),
									GAME_KENO => array(),
									GAME_VIRTUAL_SPORTS => array(),
									GAME_POKER => array(),
									GAME_COCKFIGHTING => array(),
									GAME_OTHERS => array(),
								);
								$player_lists[$withdrawal_capture_query_row->player_id]['month_winloss'] = array(
									GAME_SPORTSBOOK => array(),
									GAME_LIVE_CASINO => array(),
									GAME_SLOTS => array(),
									GAME_FISHING => array(),
									GAME_ESPORTS => array(),
									GAME_BOARD_GAME => array(),
									GAME_LOTTERY => array(),
									GAME_KENO => array(),
									GAME_VIRTUAL_SPORTS => array(),
									GAME_POKER => array(),
									GAME_COCKFIGHTING => array(),
									GAME_OTHERS => array(),
								);
								$player_lists[$withdrawal_capture_query_row->player_id]['withdrawal_data'] = array();
								array_push($player_ids_array, $withdrawal_capture_query_row->player_id);
								$temp_array = array(
									'created_date' => $withdrawal_capture_query_row->created_date,
									'amount' => $withdrawal_capture_query_row->amount,
								);
								array_push($player_lists[$withdrawal_capture_query_row->player_id]['withdrawal_data'], $temp_array);
							}
						}
					}

					if(!empty($player_ids_array)){
						$player_ids = implode(',', $player_ids_array);
						//Get All Player Data
						$player_query = $this->db->query("SELECT player_id, username, upline, created_date FROM {$dbprefix}players WHERE player_id IN (".$player_ids.") ORDER BY player_id ASC");
						if($player_query->num_rows() > 0) {
							foreach($player_query->result() as $player_row){
								$player_lists[$player_row->player_id]['player_id'] = $player_row->player_id;
								$player_lists[$player_row->player_id]['username'] = $player_row->username;
								$player_lists[$player_row->player_id]['upline'] = $player_row->upline;
								$player_lists[$player_row->player_id]['register_date'] = $player_row->created_date;
							}
						}
						$player_query->free_result();

						$today_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($today_start_time,$today_end_time));
						if($today_deposit_query->num_rows() > 0) {
							foreach($today_deposit_query->result() as $today_deposit_row){
								if(isset($player_lists[$today_deposit_row->player_id])){
									$player_lists[$today_deposit_row->player_id]['today_deposit']['total_deposit'] += ($today_deposit_row->deposit_offline_amount + $today_deposit_row->deposit_online_online_amount + $today_deposit_row->deposit_online_credit_amount + $today_deposit_row->deposit_online_hypermart_amount + $today_deposit_row->deposit_point_amount  + $today_deposit_row->adjust_in_amount);
									$player_lists[$today_deposit_row->player_id]['today_deposit']['offline_deposit'] += $today_deposit_row->deposit_offline_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['online_deposit'] += $today_deposit_row->deposit_online_online_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['credit_card_deposit'] += $today_deposit_row->deposit_online_credit_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['hypermart_deposit'] += $today_deposit_row->deposit_online_hypermart_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['point_deposit'] += $today_deposit_row->deposit_point_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['adjust_deposit'] += $today_deposit_row->adjust_in_amount;
								}
							}
						}
						$today_deposit_query->free_result();

						$month_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($month_start_time,$month_end_time));
						if($month_deposit_query->num_rows() > 0) {
							foreach($month_deposit_query->result() as $month_deposit_row){
								if(isset($player_lists[$month_deposit_row->player_id])){
									$player_lists[$month_deposit_row->player_id]['month_deposit']['total_deposit'] += ($month_deposit_row->deposit_offline_amount + $month_deposit_row->deposit_online_online_amount + $month_deposit_row->deposit_online_credit_amount + $month_deposit_row->deposit_online_hypermart_amount + $month_deposit_row->deposit_point_amount  + $month_deposit_row->adjust_in_amount);
									$player_lists[$month_deposit_row->player_id]['month_deposit']['offline_deposit'] += $month_deposit_row->deposit_offline_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['online_deposit'] += $month_deposit_row->deposit_online_online_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['credit_card_deposit'] += $month_deposit_row->deposit_online_credit_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['hypermart_deposit'] += $month_deposit_row->deposit_online_hypermart_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['point_deposit'] += $month_deposit_row->deposit_point_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['adjust_deposit'] += $month_deposit_row->adjust_in_amount;
								}
							}
						}
						$month_deposit_query->free_result();

						//WIN LOSS
						$today_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($today_start_time,$today_end_time));
						if($today_win_loss_query->num_rows() > 0) {
							foreach($today_win_loss_query->result() as $today_win_loss_row){
								if(isset($player_lists[$today_win_loss_row->player_id])){
									$player_lists[$today_win_loss_row->player_id]['today_winloss'][$today_win_loss_row->game_type_code][$today_win_loss_row->game_provider_code] = $today_win_loss_row->amount;
								}
							}
						}
						$today_win_loss_query->free_result();

						$month_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($month_start_time,$month_end_time));
						if($month_win_loss_query->num_rows() > 0) {
							foreach($month_win_loss_query->result() as $month_win_loss_row){
								if(isset($player_lists[$month_win_loss_row->player_id])){
									$player_lists[$month_win_loss_row->player_id]['month_winloss'][$month_win_loss_row->game_type_code][$month_win_loss_row->game_provider_code] = $month_win_loss_row->amount;
								}
							}
						}
						$month_win_loss_query->free_result();

						$years_win_loss_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}total_win_loss_report_month WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id",array($years_start_time,$years_end_time));
						if($years_win_loss_query->num_rows() > 0) {
							foreach($years_win_loss_query->result() as $years_win_loss_row){
								if(isset($player_lists[$years_win_loss_row->player_id])){
									$player_lists[$years_win_loss_row->player_id]['years_winloss'] = $years_win_loss_row->amount;
								}
							}
						}
						$years_win_loss_query->free_result();

						//Get Player Wallet Balance
						if(!empty($player_ids_array)){
							foreach($player_ids_array as $player_ids_row){
								if(isset($player_lists[$player_ids_row])){
									$wallet_result = $this->get_member_total_wallet($player_ids_row);
									$player_lists[$player_ids_row]['game_balance'] = $wallet_result['balance_amount'];
								}
							}
						}

						$message = "";
						if(!empty($player_lists)){
							foreach($player_lists as $player_lists_row){
								$message = "";
								$message = "";
								$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
								$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";

								if(!empty($player_lists_row['today_deposit']['total_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
									if(!empty($player_lists_row['today_deposit']['online_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['point_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
									}

									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
								}

								//Today Winloss
								$total = 0;
								if(!empty($player_lists_row['today_winloss'])){
									$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
									foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
										if(!empty($game_type_value)){
											foreach($game_type_value as $win_loss_key => $win_loss_value){
												if($win_loss_value >= 0){
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}else{
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}
												$total += $win_loss_value;
											}
										}
									}
									if($total >= 0){
										$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
									}else{
										$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
									}
								}

								$total = 0;
								if(!empty($player_lists_row['month_winloss'])){
									$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
									foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
										if(!empty($game_type_value)){
											foreach($game_type_value as $win_loss_key => $win_loss_value){
												if($win_loss_value >= 0){
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}else{
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}
												$total += $win_loss_value;
											}
										}
									}
									if($total >= 0){
										$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
									}else{
										$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
									}
								}
								$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
								if($player_lists_row['years_winloss'] >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_withdrawal_title')."\r\n";
								if(!empty($player_lists_row['withdrawal_data'])){
									foreach($player_lists_row['withdrawal_data'] as $player_withdrawal_row){
										$message .= $this->lang->line('lang_telegram_risk_pending_withdrawal').":".bcdiv($player_withdrawal_row['amount'],1,0)."\r\n";
									}
								}
								$message .= $this->lang->line('lang_telegram_risk_game_balance').": ".number_format($player_lists_row['game_balance'], 0, '.', ',')."\r\n";
								send_message_telegram(TELEGRAM_RISK,$message);
							}
						}
					}

					$player_ids = implode(',', $player_ids_array);
					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
					$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
				}
			}
		}
	}

	public function risk_cron_promotion_rebate(){
		set_time_limit(0);
		$promotion_amount_capture = 600000;
		$current_time = time();
		$promotion_genre_code = 'CRLV';
		$today_start_date 	= date('Y-m-d 00:00:00',$current_time);
		$today_end_date 	= date('Y-m-d 23:59:59',$current_time);
		$month_start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
		$month_end_date		= date('Y-m-d 23:59:59', strtotime('last day of this month'));
		$years_start_date 	= date('Y-01-01 00:00:00',$current_time);
		$years_end_date 	= date('Y-12-31 23:59:59',$current_time);

		$today_start_time = strtotime($today_start_date);
		$today_end_time = strtotime($today_end_date);
		$month_start_time = strtotime($month_start_date);
		$month_end_time = strtotime($month_end_date);
		$years_start_time = strtotime($years_start_date);
		$years_end_time = strtotime($years_end_date);

		$cron_code = 'RISKPROMO';
		$player_ids = "";
		$player_ids_array = array();
		$player_lists = array();
		$temp_array = array();
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		sleep(20);
		if(!empty($cron_result_data))
		{
			if(!empty($cron_result_data['cron_time'])){
				$end_date	= date('Y-m-d H:i:59', $current_time-300);
				$start_time = $cron_result_data['cron_time']+1;
				$end_time = strtotime($end_date);
			}else{
				$end_date	= date('Y-m-d H:i:59', $current_time-300);
				$start_time = 0;
				$end_time = strtotime($end_date);
			}
			if($end_time > $start_time){
				if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
					$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
					$DBdata = array('cron_time' => $end_time);
					$dbprefix = $this->db->dbprefix;
					$promotion_capture_query = $this->db->query("SELECT player_id,reward_amount,created_date FROM {$dbprefix}player_promotion WHERE created_date >= ? AND created_date <= ? AND reward_amount >= ? AND status = ? AND genre_code = ?",array($start_time,$end_time,$promotion_amount_capture,STATUS_PENDING,$promotion_genre_code));
					if($promotion_capture_query->num_rows() > 0) {
						foreach($promotion_capture_query->result() as $promotion_capture_query_row){
							if(isset($player_lists[$promotion_capture_query_row->player_id])){
								$temp_array = array(
									'created_date' => $promotion_capture_query_row->created_date,
									'amount' => $promotion_capture_query_row->amount,
								);
								array_push($player_lists[$promotion_capture_query_row->player_id]['promotion_data'], $temp_array);
							}else{
								$player_lists[$promotion_capture_query_row->player_id]['today_deposit'] = array(
									'offline_deposit' => 0,
									'online_deposit' => 0,
									'hypermart_deposit' => 0,
									'credit_card_deposit' => 0,
									'point_deposit' => 0,
									'adjust_deposit' => 0,
									'total_deposit' => 0,
								);
								$player_lists[$promotion_capture_query_row->player_id]['month_deposit'] = array(
									'offline_deposit' => 0,
									'online_deposit' => 0,
									'hypermart_deposit' => 0,
									'credit_card_deposit' => 0,
									'point_deposit' => 0,
									'adjust_deposit' => 0,
									'total_deposit' => 0,
								);
								$player_lists[$promotion_capture_query_row->player_id]['today_winloss'] = array(
									GAME_SPORTSBOOK => array(),
									GAME_LIVE_CASINO => array(),
									GAME_SLOTS => array(),
									GAME_FISHING => array(),
									GAME_ESPORTS => array(),
									GAME_BOARD_GAME => array(),
									GAME_LOTTERY => array(),
									GAME_KENO => array(),
									GAME_VIRTUAL_SPORTS => array(),
									GAME_POKER => array(),
									GAME_COCKFIGHTING => array(),
									GAME_OTHERS => array(),
								);
								$player_lists[$promotion_capture_query_row->player_id]['month_winloss'] = array(
									GAME_SPORTSBOOK => array(),
									GAME_LIVE_CASINO => array(),
									GAME_SLOTS => array(),
									GAME_FISHING => array(),
									GAME_ESPORTS => array(),
									GAME_BOARD_GAME => array(),
									GAME_LOTTERY => array(),
									GAME_KENO => array(),
									GAME_VIRTUAL_SPORTS => array(),
									GAME_POKER => array(),
									GAME_COCKFIGHTING => array(),
									GAME_OTHERS => array(),
								);
								$player_lists[$promotion_capture_query_row->player_id]['promotion_data'] = array();
								array_push($player_ids_array, $promotion_capture_query_row->player_id);
								$temp_array = array(
									'created_date' => $promotion_capture_query_row->created_date,
									'amount' => $promotion_capture_query_row->reward_amount,
								);
								array_push($player_lists[$promotion_capture_query_row->player_id]['promotion_data'], $temp_array);
							}
						}
					}

					if(!empty($player_ids_array)){
						$player_ids = implode(',', $player_ids_array);
						//Get All Player Data
						$player_query = $this->db->query("SELECT player_id, username, upline, created_date FROM {$dbprefix}players WHERE player_id IN (".$player_ids.") ORDER BY player_id ASC");
						if($player_query->num_rows() > 0) {
							foreach($player_query->result() as $player_row){
								$player_lists[$player_row->player_id]['player_id'] = $player_row->player_id;
								$player_lists[$player_row->player_id]['username'] = $player_row->username;
								$player_lists[$player_row->player_id]['upline'] = $player_row->upline;
								$player_lists[$player_row->player_id]['register_date'] = $player_row->created_date;
							}
						}
						$player_query->free_result();

						$today_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($today_start_time,$today_end_time));
						if($today_deposit_query->num_rows() > 0) {
							foreach($today_deposit_query->result() as $today_deposit_row){
								if(isset($player_lists[$today_deposit_row->player_id])){
									$player_lists[$today_deposit_row->player_id]['today_deposit']['total_deposit'] += ($today_deposit_row->deposit_offline_amount + $today_deposit_row->deposit_online_online_amount + $today_deposit_row->deposit_online_credit_amount + $today_deposit_row->deposit_online_hypermart_amount + $today_deposit_row->deposit_point_amount  + $today_deposit_row->adjust_in_amount);
									$player_lists[$today_deposit_row->player_id]['today_deposit']['offline_deposit'] += $today_deposit_row->deposit_offline_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['online_deposit'] += $today_deposit_row->deposit_online_online_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['credit_card_deposit'] += $today_deposit_row->deposit_online_credit_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['hypermart_deposit'] += $today_deposit_row->deposit_online_hypermart_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['point_deposit'] += $today_deposit_row->deposit_point_amount;
									$player_lists[$today_deposit_row->player_id]['today_deposit']['adjust_deposit'] += $today_deposit_row->adjust_in_amount;
								}
							}
						}
						$today_deposit_query->free_result();

						$month_deposit_query = $this->db->query("SELECT COALESCE(SUM(deposit_offline_amount),0) as deposit_offline_amount, COALESCE(SUM(deposit_online_online_amount),0) as deposit_online_online_amount, COALESCE(SUM(deposit_online_credit_amount),0) as deposit_online_credit_amount, COALESCE(SUM(deposit_online_hypermart_amount),0) as deposit_online_hypermart_amount, COALESCE(SUM(deposit_point_amount),0) as deposit_point_amount, COALESCE(SUM(adjust_in_amount),0) as adjust_in_amount, player_id FROM {$dbprefix}total_win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ? GROUP BY player_id",array($month_start_time,$month_end_time));
						if($month_deposit_query->num_rows() > 0) {
							foreach($month_deposit_query->result() as $month_deposit_row){
								if(isset($player_lists[$month_deposit_row->player_id])){
									$player_lists[$month_deposit_row->player_id]['month_deposit']['total_deposit'] += ($month_deposit_row->deposit_offline_amount + $month_deposit_row->deposit_online_online_amount + $month_deposit_row->deposit_online_credit_amount + $month_deposit_row->deposit_online_hypermart_amount + $month_deposit_row->deposit_point_amount  + $month_deposit_row->adjust_in_amount);
									$player_lists[$month_deposit_row->player_id]['month_deposit']['offline_deposit'] += $month_deposit_row->deposit_offline_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['online_deposit'] += $month_deposit_row->deposit_online_online_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['credit_card_deposit'] += $month_deposit_row->deposit_online_credit_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['hypermart_deposit'] += $month_deposit_row->deposit_online_hypermart_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['point_deposit'] += $month_deposit_row->deposit_point_amount;
									$player_lists[$month_deposit_row->player_id]['month_deposit']['adjust_deposit'] += $month_deposit_row->adjust_in_amount;
								}
							}
						}
						$month_deposit_query->free_result();

						//WIN LOSS
						$today_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($today_start_time,$today_end_time));
						if($today_win_loss_query->num_rows() > 0) {
							foreach($today_win_loss_query->result() as $today_win_loss_row){
								if(isset($player_lists[$today_win_loss_row->player_id])){
									$player_lists[$today_win_loss_row->player_id]['today_winloss'][$today_win_loss_row->game_type_code][$today_win_loss_row->game_provider_code] = $today_win_loss_row->amount;
								}
							}
						}
						$today_win_loss_query->free_result();

						$month_win_loss_query = $this->db->query("SELECT player_id,game_provider_code,game_type_code,sum(win_loss) as amount FROM {$dbprefix}win_loss_report WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id, game_provider_code, game_type_code",array($month_start_time,$month_end_time));
						if($month_win_loss_query->num_rows() > 0) {
							foreach($month_win_loss_query->result() as $month_win_loss_row){
								if(isset($player_lists[$month_win_loss_row->player_id])){
									$player_lists[$month_win_loss_row->player_id]['month_winloss'][$month_win_loss_row->game_type_code][$month_win_loss_row->game_provider_code] = $month_win_loss_row->amount;
								}
							}
						}
						$month_win_loss_query->free_result();

						$years_win_loss_query = $this->db->query("SELECT player_id,sum(win_loss) as amount FROM {$dbprefix}total_win_loss_report_month WHERE player_id IN (".$player_ids.") AND report_date >= ? AND report_date <= ?  GROUP BY player_id",array($years_start_time,$years_end_time));
						if($years_win_loss_query->num_rows() > 0) {
							foreach($years_win_loss_query->result() as $years_win_loss_row){
								if(isset($player_lists[$years_win_loss_row->player_id])){
									$player_lists[$years_win_loss_row->player_id]['years_winloss'] = $years_win_loss_row->amount;
								}
							}
						}
						$years_win_loss_query->free_result();

						//Get Player Wallet Balance
						if(!empty($player_ids_array)){
							foreach($player_ids_array as $player_ids_row){
								if(isset($player_lists[$player_ids_row])){
									$wallet_result = $this->get_member_total_wallet($player_ids_row);
									$player_lists[$player_ids_row]['game_balance'] = $wallet_result['balance_amount'];
								}
							}
						}

						$message = "";
						if(!empty($player_lists)){
							foreach($player_lists as $player_lists_row){
								$message = "";
								$message = "";
								$message .= $this->lang->line('lang_telegram_register_platform')." ".$this->lang->line('lang_telegram_risk_upline').":".$player_lists_row['upline']."\r\n";
								$message .= $this->lang->line('lang_telegram_risk_member').":".$player_lists_row['username']." (".date("Y-m-d",$player_lists_row['register_date'])." ".$this->lang->line('lang_telegram_register_time').")"."\r\n\n";

								if(!empty($player_lists_row['today_deposit']['total_deposit'])){
									$message .= $this->lang->line('lang_telegram_risk_today_deposit')."\r\n";
									if(!empty($player_lists_row['today_deposit']['online_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_online')." : ".number_format($player_lists_row['today_deposit']['online_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['hypermart_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_hypermarket')." : ".number_format($player_lists_row['today_deposit']['hypermart_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['credit_card_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_credit_card')." : ".number_format($player_lists_row['today_deposit']['credit_card_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['offline_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_offline')." : ".number_format($player_lists_row['today_deposit']['offline_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['adjust_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_adjust')." : ".number_format($player_lists_row['today_deposit']['adjust_deposit'], 0, '.', ',')."\r\n";
									}

									if(!empty($player_lists_row['today_deposit']['point_deposit'])){
										$message .= $this->lang->line('lang_telegram_risk_deposit_point')." : ".number_format($player_lists_row['today_deposit']['point_deposit'], 0, '.', ',')."\r\n";
									}

									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['today_deposit']['total_deposit'], 0, '.', ',')."\r\n\n";
								}

								//Today Winloss
								$total = 0;
								if(!empty($player_lists_row['today_winloss'])){
									$message .= $this->lang->line('lang_telegram_risk_today_winloss')."\r\n";
									foreach($player_lists_row['today_winloss'] as $game_type_key => $game_type_value){
										if(!empty($game_type_value)){
											foreach($game_type_value as $win_loss_key => $win_loss_value){
												if($win_loss_value >= 0){
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}else{
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}
												$total += $win_loss_value;
											}
										}
									}
									if($total >= 0){
										$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 2, '.', ',')."\r\n\n";
									}else{
										$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 2, '.', ',')."\r\n\n";
									}
								}

								$total = 0;
								if(!empty($player_lists_row['month_winloss'])){
									$message .= $this->lang->line('lang_telegram_risk_month_winloss')."\r\n";
									foreach($player_lists_row['month_winloss'] as $game_type_key => $game_type_value){
										if(!empty($game_type_value)){
											foreach($game_type_value as $win_loss_key => $win_loss_value){
												if($win_loss_value >= 0){
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : +".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}else{
													$message .= $this->lang->line('game_'.strtolower($win_loss_key))."(".$this->lang->line('game_type_'.strtolower($game_type_key)).")"." : ".number_format($win_loss_value, 0, '.', ',')."\r\n";
												}
												$total += $win_loss_value;
											}
										}
									}
									if($total >= 0){
										$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($total, 0, '.', ',')."\r\n\n";
									}else{
										$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($total, 0, '.', ',')."\r\n\n";
									}
								}
								$message .= $this->lang->line('lang_telegram_risk_year_winloss')."\r\n";
								if($player_lists_row['years_winloss'] >= 0){
									$message .= $this->lang->line('lang_telegram_risk_total')." : +".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
								}else{
									$message .= $this->lang->line('lang_telegram_risk_total')." : ".number_format($player_lists_row['years_winloss'], 0, '.', ',')."\r\n\n";
								}

								$message .= $this->lang->line('lang_telegram_risk_promotion_rebate_title')."\r\n";
								if(!empty($player_lists_row['promotion_data'])){
									foreach($player_lists_row['promotion_data'] as $player_promotion_row){
										$message .= $this->lang->line('lang_telegram_risk_pending_promotion_rebate').":".bcdiv($player_promotion_row['amount'],1,0)."\r\n";
									}
								}
								$message .= $this->lang->line('lang_telegram_risk_game_balance').": ".number_format($player_lists_row['game_balance'], 0, '.', ',')."\r\n";
								send_message_telegram(TELEGRAM_RISK,$message);
							}
						}
					}

					$player_ids = implode(',', $player_ids_array);
					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
					$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
				}
			}
		}
	}

	public function get_member_total_wallet($id){
		$is_balance_valid = TRUE;
		$total_amount = 0;
		$game_balance = 0;
		$main_wallet_balance = 0;
		$player_data = $this->player_model->get_player_data($id);
		if( ! empty($player_data))
		{
			$game_balance = 0;
			$total_amount = $player_data['points'];
			$main_wallet_balance = $player_data['points'];
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$url = SYSTEM_API_URL;
			$account_data_list = $this->player_model->get_player_game_account_data_list($player_data['player_id']);
			if( ! empty($account_data_list))
			{
				foreach($account_data_list as $account_data){
					$signature = md5(SYSTEM_API_AGENT_ID . $account_data['game_provider_code'] . $account_data['username'] . SYSTEM_API_SECRET_KEY);

					$param_array = array(
						"method" => 'GetBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
						"provider_code" => $account_data['game_provider_code'],
						"username" => $account_data['username'],
						"password" => $account_data['password'],
						"game_id" => $account_data['game_id'],
						"player_id" => $account_data['player_id'],
						"signature" => $signature,
					);

					$response = $this->curl_json($url, $param_array);
					$result_array = json_decode($response, TRUE);
					if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
					{
						$total_amount = ($total_amount + $result_array['result']);
						$game_balance = ($game_balance + $result_array['result']);
					}else{
						$is_balance_valid = FALSE;
					}
				}
			}else{
				$is_balance_valid = FALSE;
			}
		}else{
			$is_balance_valid = FALSE;
		}

		$result = array(
			'balance_valid' => $is_balance_valid,
			'balance_amount' => $total_amount,
			'game_balance' => $game_balance,
			'main_wallet_balance' => $main_wallet_balance,
		);
		return $result;
	}

	public function wm_system_message(){
		set_time_limit(0);
		$game_provider_code = "WM";
		$system_message_templete = SYSTEM_MESSAGE_PLATFORM_WM_ACCOUNT;
		$player_game_account_list = array();
		$system_message_data = array();
		$system_message_lang_data = array();
		$success_message_data = array();
		$current_time = time();
		$is_success = FALSE;
		sleep(15);
		$cron_code = "WMSM";
		$cron_result_data = $this->cron_model->get_cron_result($cron_code);
		if(!empty($cron_result_data))
		{
			if(!empty($cron_result_data['cron_time'])){
				$end_date	= date('Y-m-d H:i:59', $current_time-60);
				$start_time = $cron_result_data['cron_time']+1;
				$end_time = strtotime($end_date);
			}else{
				$end_date	= date('Y-m-d H:i:59', $current_time-60);
				$start_time = 0;
				$end_time = strtotime($end_date);
			}
		}

		if($end_time > $start_time){
			if($cron_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->cron_model->update_sync_lock($cron_code,STATUS_ACTIVE);
				$DBdata = array('cron_time' => $end_time);
				$dbprefix = $this->db->dbprefix;


				$message_query = $this->db->query("SELECT * FROM {$dbprefix}system_message WHERE system_message_templete = ? AND active = ? LIMIT 1",array($system_message_templete,STATUS_ACTIVE));
				if($message_query->num_rows() > 0)
				{
					$system_message_data = $message_query->row_array();
				}
				$message_query->free_result();
				
				if(!empty($system_message_data)){
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						$system_message_id = $system_message_data['system_message_id'];
						$message_lang_query = $this->db->query("SELECT system_message_title, system_message_content, language_id FROM {$dbprefix}system_message_lang WHERE system_message_id = ?",array($system_message_id));
						if($message_lang_query->num_rows() > 0)
						{
							foreach($message_lang_query->result() as $message_lang_query_row){
								$system_message_lang_data[$message_lang_query_row->language_id] = array(
									'system_message_title' => $message_lang_query_row->system_message_title,
									'system_message_content' => $message_lang_query_row->system_message_content,
								);
							}
						}
						$message_lang_query->free_result();
						if(!empty($system_message_lang_data)){
							$sys_data = $this->miscellaneous_model->get_miscellaneous();
							$create_time = time();
							if(empty($start_time)){
								$game_capture_query = $this->db->query("SELECT player_id,username,game_id,password FROM {$dbprefix}player_game_accounts WHERE game_provider_code = ? AND created_date <= ?",array($game_provider_code,$end_time));
							}else{
								$game_capture_query = $this->db->query("SELECT player_id,username,game_id,password FROM {$dbprefix}player_game_accounts WHERE game_provider_code = ? AND created_date >= ? AND created_date <= ?",array($game_provider_code,$start_time,$end_time));
							}

							if($game_capture_query->num_rows() > 0)
							{
								foreach($game_capture_query->result() as $game_capture_query_row){
									$username = ((substr($game_capture_query_row->username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($game_capture_query_row->username, strlen($sys_data['system_prefix'])) : $game_capture_query_row->username);

									$player_game_account_list[$game_capture_query_row->player_id] = array(
										'player_id' => $game_capture_query_row->player_id,
										'username' => $username, 
										'game_id' => $game_capture_query_row->game_id,
										'password' => $game_capture_query_row->password,
									);
								}
							}
							$game_capture_query->free_result();
							$Bdatalang = array();
							$Bdata = array();
							if(!empty($player_game_account_list)){
								foreach($player_game_account_list as $player_game_account_list_row){
									$PBdata = array(
										'system_message_id'	=> $system_message_id,
										'player_id'			=> $player_game_account_list_row['player_id'],
										'username'			=> $player_game_account_list_row['username'],
										'active' 			=> STATUS_ACTIVE,
										'is_read'			=> MESSAGE_UNREAD,
										'created_by'		=> '',
										'created_date'		=> $create_time,
									);
									array_push($Bdata, $PBdata);
								}

								if( ! empty($Bdata))
								{
									$this->db->insert_batch('system_message_user', $Bdata);
								}
							}

							$success_message_query = $this->db->query("SELECT system_message_user_id, player_id FROM {$dbprefix}system_message_user WHERE system_message_id = ? AND created_date = ?",array($system_message_id,$create_time));
							if($success_message_query->num_rows() > 0)
							{
								foreach($success_message_query->result() as $success_message_query_row){
									$success_message_data[$success_message_query_row->player_id] = $success_message_query_row->system_message_user_id;
								}						
							}
							$success_message_query->free_result();
							if(!empty($success_message_data)){
								$is_success = TRUE;
								foreach($success_message_data as $player_id => $success_message_id){
									foreach($lang as $k => $v){
										$replace_string_array = array(
											SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $player_game_account_list[$player_id]['username'],
											SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
											SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_USERNAME => $player_game_account_list[$player_id]['game_id'],
											SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_PASSWORD =>$player_game_account_list[$player_id]['password'],
										);

										$PBdataLang = array(
											'system_message_user_id'	=> $success_message_id,
											'system_message_title'		=> $system_message_lang_data[$v]['system_message_title'],
											'system_message_content'	=> get_system_message_content($system_message_lang_data[$v]['system_message_content'],$replace_string_array),
											'language_id' 				=> $v
										);
										array_push($Bdatalang, $PBdataLang);
									}
								}
							}

							if(!empty($Bdatalang)){
								$this->db->insert_batch('system_message_user_lang', $Bdatalang);
							}
						}
					}
				}

				if($is_success == TRUE){
					$this->cron_model->update_cron_result($cron_result_data,$DBdata);
				}
				$this->cron_model->update_sync_lock($cron_code,STATUS_INACTIVE);
			}
		}
	}
}