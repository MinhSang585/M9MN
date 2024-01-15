<?php
class Promotion_model extends CI_Model {
	public function get_promotion_banner_list($language_id = NULL){
		$result = NULL;
		$this->db->select('promotion.promotion_id, promotion.banner_category, promotion_lang.promotion_title, promotion_lang.promotion_content, promotion_lang.promotion_banner_web, promotion_lang.promotion_banner_mobile, promotion_lang.promotion_banner_web_content, promotion_lang.promotion_banner_mobile_content');
		$this->db->from('promotion');
		$this->db->where('promotion.is_banner', STATUS_ACTIVE);
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->join('promotion_lang','promotion.promotion_id = promotion_lang.promotion_id');
		$this->db->where('promotion_lang.language_id', $language_id);
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}

	//Promotion Other setting
	public function deposit_promotion_on_pending($data = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		$this->db->where('deposit_id',$data['deposit_id']);
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_not_complete_deposit_promotion($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH,PROMOTION_TYPE_DPR));
		$this->db->where_in('status', array(STATUS_PENDING,STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_pending_deposit_promotion($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('promotion_id',$promotionData['promotion_id']);
		$this->db->where('player_id',$data['player_id']);
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where_in('status', array(STATUS_PENDING));
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_pending_deposit_promotion_approve($data = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('status', STATUS_ENTITLEMENT);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_promotion_data_by_id($id){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->where('promotion_id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_deposit_promotion_available_by_id($data = NULL){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->where('promotion_id',$data['promotion_id']);
		$this->db->where_in('promotion.genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_PLAYER.',');
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_deposit_promotion_available_by_id_approve($data = NULL){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->where('promotion_id',$data['promotion_id']);
		$this->db->where_in('promotion.genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_check_expirate_date($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->join('player_promotion','promotion.promotion_id = player_promotion.promotion_id');
		$this->db->where('promotion.promotion_id', $promotionData['promotion_id']);
		$this->db->where('player_promotion.player_id', $data['player_id']);
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_PLAYER.',');
		$this->db->where('promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->where('player_promotion.updated_date <=',time());
		$this->db->where('player_promotion.updated_date >=',strtotime('-'.$promotionData['date_expirate_type'].' days', time()));
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_check_claim_limit($playerData = NULL,$promotionData = NULL){
		$result = NULL;

		$this->db->from('promotion');
		$this->db->join('player_promotion','promotion.promotion_id = player_promotion.promotion_id');
		$this->db->where('promotion.promotion_id', $promotionData['promotion_id']);
		$this->db->where('player_promotion.player_id', $playerData['player_id']);
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_PLAYER.',');
		$this->db->where('promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_ONCE){

		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',time())));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:00',time())));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of this month'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of this month'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of january'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of december'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('sunday last week'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('saturday this week'))));
		}

		$this->db->order_by('player_promotion.updated_date', 'DESC');
		$this->db->order_by('promotion.created_date', 'DESC');
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_success_deposit_data($data = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('status', STATUS_COMPLETE)
				->where('player_id', $data['player_id'])
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_total_success_deposit($data = NULL){
		$result = 0;
		
		$query = $this
				->db
				->where('status', STATUS_COMPLETE)
				->where('player_id', $data['player_id'])
				->get('deposits');
		$result = $query->num_rows(); 
		return $result;
	}

	public function get_success_deposit_data_today($data = NULL){
		$start_time = strtotime(date("Y-m-d 00:00:00"));
		$end_time = strtotime(date("Y-m-d 23:59:59"));
		$result = NULL;
		$query = $this
				->db
				->where('status', STATUS_COMPLETE)
				->where('player_id', $data['player_id'])
				->where('updated_date >= ',$start_time)
				->where('updated_date <= ',$end_time)
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_reward_amount_decision($promotion_amount=NULL,$promotionData = NULL){
		if($promotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			if($promotionData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){
				$reward_amount = $promotion_amount * $promotionData['rebate_percentage'] / 100;
				if($reward_amount >= $promotionData['max_rebate']){
					$reward_amount = $promotionData['max_rebate'];
				}
			}else{
				$reward_amount = $promotionData['rebate_amount'];
			}
		}else{
			$promotionBonusRangeData = $this->get_deposit_promotion_bonus_range_decision($promotion_amount,$promotionData);
			if(!empty($promotionBonusRangeData)){
				if($promotionData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){
					$reward_amount = $promotion_amount * $promotionBonusRangeData['percentage'] / 100;
					if($reward_amount >= $promotionBonusRangeData['max_amount']){
						$reward_amount = $promotionBonusRangeData['max_amount'];
					}
				}else{
					$reward_amount = $promotionBonusRangeData['bonus_amount'];
				}
			}else{
				$reward_amount = 0;
			}
			
		}
		return $reward_amount;
	}

	public function deposit_promotion_achieve_amount_decision($promotion_amount=NULL, $promotionData = NULL, $reward_amount = NULL, $deposit_amount = NULL){
		$game_ids = "";
		$achieve_amount = 0;
		$multiply = 0;
		$index = 0;
		$level = 0;
		
		if($promotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			$achieve_amount = (($promotion_amount + $reward_amount) * $promotionData['turnover_multiply']) + ($deposit_amount-$promotion_amount);
			$multiply = $promotionData['turnover_multiply']; 
			$game_ids = $promotionData['game_ids'];
		}else{
			$promotionBonusRangeData = $this->get_deposit_promotion_bonus_range_decision($promotion_amount,$promotionData);
			if(!empty($promotionBonusRangeData)){
				$achieve_amount = (($promotion_amount + $reward_amount) * $promotionBonusRangeData['turnover_multiply']) + ($deposit_amount-$promotion_amount);
				$level = $promotionBonusRangeData['bonus_level'];
				$index = $promotionBonusRangeData['bonus_index'];
				$multiply = $promotionBonusRangeData['turnover_multiply'];
				$game_ids = $promotionBonusRangeData['game_ids'];
			}
		}
		$achieve_amount_data = array(
			'amount' => $achieve_amount,
			'index' => $index, 
			'level' => $level,
			'multiply' => $multiply,
			'game_ids' => $game_ids,
		);
		return $achieve_amount_data;
	}

	public function get_deposit_promotion_bonus_range_decision($promotion_amount=NULL,$promotionData = NULL){
		$result = NULL;
		if($promotionData['reward_on_apply'] == STATUS_ACTIVE){
			$this->db->from('promotion_bonus_range');
			$this->db->where('promotion_id',$promotionData['promotion_id']);
			$this->db->where('active', STATUS_ACTIVE);
			$this->db->where('amount_from <=',$promotion_amount);
			$this->db->group_start();
				$this->db->where('amount_to >=',$promotion_amount);
				$this->db->or_where('amount_to',0);
			$this->db->group_end();
			$this->db->order_by('bonus_level',"ASC");
			$this->db->order_by('amount_from',"ASC");
			$this->db->order_by('game_ids',"DESC");
			$this->db->limit(1);
			$query = $this->db->get();
		}else{
			$this->db->from('promotion_bonus_range');
			$this->db->where('promotion_id',$promotionData['promotion_id']);
			$this->db->where('active', STATUS_ACTIVE);
			$this->db->order_by('bonus_level',"ASC");
			$this->db->order_by('amount_from',"ASC");
			$this->db->order_by('game_ids',"DESC");
			$this->db->limit(1);
			$query = $this->db->get();
		}
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function promotion_starting_date_decision($promotionData = NULL){
		$current_time = time();
		$starting_date = time();
		if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE || $promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE || $promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
			//calculate every week,years,month
			if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('sunday last week')));
			}

			else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('first day of this month')));
			}

			else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('first day of january')));
			}
		}else{
			if($promotionData['is_starting_of_the_day'] == STATUS_ACTIVE){
				$starting_date = strtotime(date('Y-m-d 00:00:00', $current_time));
			}
		}

		return $starting_date;
	}

	public function get_all_promotion_bonus_range($promotionData = NULL){
		$result = NULL;
		$this->db->from('promotion_bonus_range');
		$this->db->where('promotion_id',$promotionData['promotion_id']);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function check_min_level_deposit_amount($data = NULL){
		$this->db->from('promotion_bonus_range');
		$this->db->where('promotion_id',$data['promotion_id']);
		$this->db->where('active', STATUS_ACTIVE);
		$this->db->where('amount_from',$data['amount']);
		$this->db->order_by('bonus_level',"ASC");
		$this->db->order_by('amount_from',"ASC");
		$this->db->order_by('game_ids',"DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;	
	}

	//Promotion Strict Start//
	public function get_player_deposit_promotion_available_strict_with_detail($playerData = NULL,$language_id = NULL,$balanceData = NULL){
		$result = array();
		$result_data = NULL;
		$this->db->select('promotion.*, promotion_lang.promotion_title, promotion_lang.promotion_content, promotion_lang.promotion_banner_web, promotion_lang.promotion_banner_mobile, promotion_lang.promotion_banner_web_content, promotion_lang.promotion_banner_mobile_content');
		$this->db->from('promotion');
		$this->db->join('promotion_lang','promotion.promotion_id = promotion_lang.promotion_id');
		$this->db->where_in('promotion.genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('promotion_lang.language_id', $language_id);
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_PLAYER.',');
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();	
		}
		$query->free_result();
		if(!empty($result_data)){
			foreach($result_data as $promotionData){
				$BData = array();
				$allow_apply_promotion = TRUE;

				//checking day
				if($allow_apply_promotion){
					if(!empty($promotionData['apply_allow_date'])){
						$today = date("N");
						$arr = explode(',', $promotionData['apply_allow_date']);
						$arr = array_values(array_filter($arr));
						if(!in_array($today,$arr)){
							$allow_apply_promotion = FALSE;
						}
					}else{
						$allow_apply_promotion = FALSE;
					}
				}

				//checkking for birthday
				if($allow_apply_promotion){
					if($promotionData['genre_code'] == PROMOTION_TYPE_BIRTH){
						if(empty($playerData['dob'])){
							$allow_apply_promotion = FALSE;
						}else{
							$this_years_birthday = strtotime(date('Y').date('-m-d 00:00:00',$playerData['dob']));
							$this_week_monday = strtotime(date('Y-m-d 00:00:00',strtotime('monday this week')));
							$this_week_sunday = strtotime(date('Y-m-d 23:59:59',strtotime('sunday this week')));
							if(($this_week_monday <= $this_years_birthday) && ($this_week_sunday >= $this_years_birthday)){
								$total_deposit_history = $this->get_total_success_deposit($playerData);
								if($promotionData['deposit_history'] > $total_deposit_history){
									$allow_apply_promotion = FALSE;
								}
							}else{
								$allow_apply_promotion = FALSE;
							}
						}
					}
				}

				//Checking isit got promotion is not complete
				if($allow_apply_promotion){
					if($promotionData['promotion_id'] >= 7 && $promotionData['promotion_id'] <= 11){
						$promotionPendingData = $this->get1($playerData);
						if(!empty($promotionPendingData)){
							$allow_apply_promotion = FALSE;
						}
					// } else if($promotionData['promotion_id'] >= 15 && $promotionData['promotion_id'] <= 18){
					// 	$promotionPendingData = $this->get2($playerData);
					// 	if(!empty($promotionPendingData)){
					// 		$allow_apply_promotion = FALSE;
					// 	}
					} else {
						$promotionPendingData = $this->get_player_not_complete_deposit_promotion($playerData,$promotionData);
						if(!empty($promotionPendingData)){
							$allow_apply_promotion = FALSE;
						}
					}
				}   

				// Checking status == 1 && set Unlimited Bonus
				// if($allow_apply_promotion){
				// 	if($promotionData['promotion_id'] >= 15 && $promotionData['promotion_id'] <= 18){
				// 		$promotionPendingData = $this->get3($playerData);
				// 		if(!empty($promotionPendingData)){
				// 			$allow_apply_promotion = FALSE;
				// 		}
				// 	}
				// }

				// Checking status == 0
				if($allow_apply_promotion){
					$promotionPendingData = $this->get4($playerData);
						if(!empty($promotionPendingData)){
							$allow_apply_promotion = FALSE;
						}
				}

				//Checking promotion valid expirate date
				if($allow_apply_promotion){
					if($promotionData['date_expirate_type'] != "0"){
						$expirateDate = $this->deposit_promotion_check_expirate_date($playerData,$promotionData);
						if(!empty($expirateDate)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//Checking claim limit
				if($allow_apply_promotion){
					if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
						$claimLimit = $this->deposit_promotion_check_claim_limit($playerData,$promotionData);
						if(!empty($claimLimit)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//check first deposit
				if($allow_apply_promotion){
					if($promotionData['first_deposit'] == STATUS_ACTIVE){
						$depositData = $this->get_success_deposit_data($playerData);
						if(!empty($depositData)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//check daily first deposit
				if($allow_apply_promotion){
					if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
						$depositTodayData = $this->get_success_deposit_data_today($playerData);
						if(!empty($depositTodayData)){
							$allow_apply_promotion = FALSE;
						}
					}
				}
				
				//Check Balance Less
				if($allow_apply_promotion){
					if($promotionData['balance_less']>0){
						if($balanceData['balance_amount'] > $promotionData['balance_less']){
							$allow_apply_promotion = FALSE;
						}
					}
				}	

				if($allow_apply_promotion == TRUE){
					$BData['promotion_id'] = $promotionData['promotion_id'];
					$BData['promotion_title'] = $promotionData['promotion_title'];
					$BData['promotion_content'] = $promotionData['promotion_content'];
					$BData['promotion_banner_web'] = $promotionData['promotion_banner_web'];
					$BData['promotion_banner_mobile'] = $promotionData['promotion_banner_mobile'];
					$BData['promotion_banner_web_content'] = $promotionData['promotion_banner_web_content'];
					$BData['promotion_banner_mobile_content'] = $promotionData['promotion_banner_mobile_content'];
					
					
					$result[] = $BData;
				}
				//
			}
		}
		return $result;
	}

	public function deposit_promotion_strict_apply_decision($data = NULL,$balanceData = NULL){
		$result = array(
			'code' => DEPOSIT_PROMOTION_UNKNOWN_ERROR,
			'status' => EXIT_ERROR, 
			'msg' => $this->lang->line('error_system_error'), 	
		);
		$allow_apply_promotion = TRUE;

		//Checking either promotion is available
		if($allow_apply_promotion){
			$promotionData = $this->get_player_deposit_promotion_available_by_id($data);
			if(empty($promotionData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE;	
				$result['msg'] = $this->lang->line('error_promotion_not_available');
			}
		}

		if($allow_apply_promotion){
			if(!empty($promotionData['apply_allow_date'])){
				$today = date("N");
				$arr = explode(',', $promotionData['apply_allow_date']);
				$arr = array_values(array_filter($arr));
				if(!in_array($today,$arr)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE;	
					$result['msg'] = $this->lang->line('error_promotion_not_allow_date');
				}
			}else{
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE;	
				$result['msg'] = $this->lang->line('error_promotion_not_allow_date');
			}
		}

		//checkking for birthday
		if($allow_apply_promotion){
			if($promotionData['genre_code'] == PROMOTION_TYPE_BIRTH){
				if(empty($data['dob'])){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
					$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
				}else{
					$this_years_birthday = strtotime(date('Y').date('-m-d 00:00:00',$data['dob']));
					$this_week_monday = strtotime(date('Y-m-d 00:00:00',strtotime('monday this week')));
					$this_week_sunday = strtotime(date('Y-m-d 23:59:59',strtotime('sunday this week')));
					if(($this_week_monday <= $this_years_birthday) && ($this_week_sunday >= $this_years_birthday)){
						$total_deposit_history = $this->get_total_success_deposit($data);
						if($promotionData['deposit_history'] > $total_deposit_history){
							$allow_apply_promotion = FALSE;
							$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
							$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
						}
					}else{
						$allow_apply_promotion = FALSE;
						$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
						$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
					}
				}
			}
		}

		if($allow_apply_promotion){
			$promotionPendingData = $this->get_player_not_complete_deposit_promotion($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		//Checking either pending deposit promotion
		
		if($allow_apply_promotion){
			$promotionPendingData = $this->get_player_pending_deposit_promotion($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		//Checking promotion valid expirate date
		if($allow_apply_promotion){
			if($promotionData['date_expirate_type'] != "0"){
				$expirateDate = $this->deposit_promotion_check_expirate_date($data,$promotionData);
				if(!empty($expirateDate)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE;
					$result['msg'] = $this->lang->line('error_promotion_not_pass_expirate_date');	
				}
			}
		}

		//Checking claim limit
		if($allow_apply_promotion){
			if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
				$claimLimit = $this->deposit_promotion_check_claim_limit($data,$promotionData);
				if(!empty($claimLimit)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT;
					$result['msg'] = $this->lang->line('error_promotion_exceed_claim_limit');
				}
			}
		}

		//check first deposit
		if($allow_apply_promotion){
			if($promotionData['first_deposit'] == STATUS_ACTIVE){
				$depositData = $this->get_success_deposit_data($data);
				if(!empty($depositData)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit');
				}
			}
		}

		//check daily first deposit
		if($allow_apply_promotion){
			if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
				$depositTodayData = $this->get_success_deposit_data_today($data);
				if(!empty($depositTodayData)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit_daily');
				}
			}
		}

		//Check Balance Less
		if($allow_apply_promotion){
			if($promotionData['balance_less']>0){
				if($balanceData['balance_amount'] > $promotionData['balance_less']){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_BALANCE_MUST_LESS;
					$result['msg'] = $this->lang->line('error_promotion_balance_must_less')." : ".$promotionData['balance_less'];
				}
			}
		}

		//Check level min
		if($allow_apply_promotion){
			if($promotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_LEVEL && $promotionData['is_deposit_level_fixed'] == STATUS_ACTIVE){
				$check_min_level_deposit_amount = $this->check_min_level_deposit_amount($data);
				if(empty($check_min_level_deposit_amount)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_FIXED_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_deposit_promotion_fixed_deposit');
				}
			}	
		}

		//Check Min Deposit
		if($allow_apply_promotion == TRUE){
			if($data['amount'] >= $promotionData['min_deposit']){
				$result['status'] = EXIT_SUCCESS;
				$result['code'] = DEPOSIT_PROMOTION_SUCCESSS;
				$result['msg'] = $this->lang->line('error_success');
				$result['promotion_name'] = $promotionData['promotion_name'];
			}else{
				$result['msg'] = $this->lang->line('error_min_amount').$promotionData['min_deposit'];
			}	
		}
		return $result;
	}
	//Promotion Unstrict Start//
	public function deposit_promotion_unstrict_apply_decision($data = NULL,$balanceData = NULL){
		$result = array(
			'code' => DEPOSIT_PROMOTION_UNKNOWN_ERROR,
			'status' => EXIT_ERROR, 
			'msg' => $this->lang->line('error_system_error'), 	
		);
		$allow_apply_promotion = TRUE;

		//Checking either promotion is available
		if($allow_apply_promotion){
			$promotionData = $this->get_player_deposit_promotion_available_by_id($data);
			if(empty($promotionData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE;	
				$result['msg'] = $this->lang->line('error_promotion_not_available');
			}
		}

		if($allow_apply_promotion){
			if(!empty($promotionData['apply_allow_date'])){
				$today = date("N");
				$arr = explode(',', $promotionData['apply_allow_date']);
				$arr = array_values(array_filter($arr));
				if(!in_array($today,$arr)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE;	
					$result['msg'] = $this->lang->line('error_promotion_not_allow_date');
				}
			}else{
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE;	
				$result['msg'] = $this->lang->line('error_promotion_not_allow_date');
			}
		}

		//checkking for birthday
		if($allow_apply_promotion){
			if($promotionData['genre_code'] == PROMOTION_TYPE_BIRTH){
				if(empty($data['dob'])){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
					$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
				}else{
					$this_years_birthday = strtotime(date('Y').date('-m-d 00:00:00',$data['dob']));
					$this_week_monday = strtotime(date('Y-m-d 00:00:00',strtotime('monday this week')));
					$this_week_sunday = strtotime(date('Y-m-d 23:59:59',strtotime('sunday this week')));
					if(($this_week_monday <= $this_years_birthday) && ($this_week_sunday >= $this_years_birthday)){
						$total_deposit_history = $this->get_total_success_deposit($data);
						if($promotionData['deposit_history'] > $total_deposit_history){
							$allow_apply_promotion = FALSE;
							$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
							$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
						}
					}else{
						$allow_apply_promotion = FALSE;
						$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE;	
						$result['msg'] = $this->lang->line('error_promotion_not_birth_date');
					}
				}
			}
		}

		//Checking isit got promotion is not complete
		if($allow_apply_promotion){
			$promotionPendingData = $this->get_player_not_complete_deposit_promotion($playerData,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		//Checking either pending deposit promotion
		
		if($allow_apply_promotion){
			$promotionPendingData = $this->get_player_pending_deposit_promotion($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_apply_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		//Checking promotion valid expirate date
		if($allow_apply_promotion){
			if($promotionData['date_expirate_type'] != "0"){
				$expirateDate = $this->deposit_promotion_check_expirate_date($data,$promotionData);
				if(!empty($expirateDate)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE;
					$result['msg'] = $this->lang->line('error_promotion_not_pass_expirate_date');	
				}
			}
		}

		//Checking claim limit
		if($allow_apply_promotion){
			if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
				$claimLimit = $this->deposit_promotion_check_claim_limit($data,$promotionData);
				if(!empty($claimLimit)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT;
					$result['msg'] = $this->lang->line('error_promotion_exceed_claim_limit');
				}
			}
		}

		//check first deposit
		if($allow_apply_promotion){
			if($promotionData['first_deposit'] == STATUS_ACTIVE){
				$depositData = $this->get_success_deposit_data($data);
				if(!empty($depositData)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit');
				}
			}
		}

		//check daily first deposit
		if($allow_apply_promotion){
			if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
				$depositTodayData = $this->get_success_deposit_data_today($data);
				if(!empty($depositTodayData)){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit_daily');
				}
			}
		}

		//Check Balance Less
		if($allow_apply_promotion){
			if($promotionData['balance_less']>0){
				if($balanceData['balance_amount'] > $promotionData['balance_less']){
					$allow_apply_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_BALANCE_MUST_LESS;
					$result['msg'] = $this->lang->line('error_promotion_balance_must_less')." : ".$promotionData['balance_less'];
				}
			}
		}		

		//Check Min Deposit
		if($allow_apply_promotion == TRUE){
			if($data['amount'] >= $promotionData['min_deposit']){
				$result['status'] = EXIT_SUCCESS;
				$result['code'] = DEPOSIT_PROMOTION_SUCCESSS;
				$result['msg'] = $this->lang->line('error_success');
				$result['promotion_name'] = $promotionData['promotion_name'];
			}else{
				$result['msg'] = $this->lang->line('error_min_amount').$promotionData['min_deposit'];
			}	
		}
		return $result;
	}

	//Promotion Strict Start//
	public function get_player_deposit_promotion_available_unstrict_with_detail($playerData = NULL,$language_id = NULL,$balanceData = NULL){
		$result = array();
		$result_data = NULL;
		$this->db->select('promotion.*, promotion_lang.promotion_title, promotion_lang.promotion_content, promotion_lang.promotion_banner_web, promotion_lang.promotion_banner_mobile, promotion_lang.promotion_banner_web_content, promotion_lang.promotion_banner_mobile_content');
		$this->db->from('promotion');
		$this->db->join('promotion_lang','promotion.promotion_id = promotion_lang.promotion_id');
		$this->db->where_in('promotion.genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH));
		$this->db->where('promotion_lang.language_id', $language_id);
		$this->db->join('player_promotion', 'promotion.promotion_id = player_promotion.promotion_id 
			and player_promotion.times_limit_type = 5
			and player_promotion.player_id = "' . $playerData["player_id"] . '"', 'left');
		$this->db->where('player_promotion.player_id is null');
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_PLAYER.',');
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();	
		}
		$query->free_result();

		
		if(!empty($result_data)){
			foreach($result_data as $promotionData){
				$BData = array();
				$allow_apply_promotion = TRUE;

				if($allow_apply_promotion){
					if(!empty($promotionData['apply_allow_date'])){
						$today = date("N");
						$arr = explode(',', $promotionData['apply_allow_date']);
						$arr = array_values(array_filter($arr));
						if(!in_array($today,$arr)){
							$allow_apply_promotion = FALSE;
						}
					}else{
						$allow_apply_promotion = FALSE;
					}
				}

				//checkking for birthday
				if($allow_apply_promotion){
					if($promotionData['genre_code'] == PROMOTION_TYPE_BIRTH){
						if(empty($playerData['dob'])){
							$allow_apply_promotion = FALSE;
						}else{
							$this_years_birthday = strtotime(date('Y').date('-m-d 00:00:00',$playerData['dob']));
							$this_week_monday = strtotime(date('Y-m-d 00:00:00',strtotime('monday this week')));
							$this_week_sunday = strtotime(date('Y-m-d 23:59:59',strtotime('sunday this week')));
							if(($this_week_monday <= $this_years_birthday) && ($this_week_sunday >= $this_years_birthday)){
								$total_deposit_history = $this->get_total_success_deposit($playerData);
								if($promotionData['deposit_history'] > $total_deposit_history){
									$allow_apply_promotion = FALSE;
								}
							}else{
								$allow_apply_promotion = FALSE;
							}
						}
					}
				}

				//Checking isit got promotion is not complete
				if($allow_apply_promotion){
					$promotionPendingData = $this->get_player_not_complete_deposit_promotion($playerData,$promotionData);
					if(!empty($promotionPendingData)){
						$allow_apply_promotion = FALSE;
					}
				}

				//Checking either pending deposit promotion
				
				if($allow_apply_promotion){
					$promotionPendingData = $this->get_player_pending_deposit_promotion($playerData,$promotionData);
					if(!empty($promotionPendingData)){
						$allow_apply_promotion = FALSE;
					}
				}

				//Checking promotion valid expirate date
				if($allow_apply_promotion){
					if($promotionData['date_expirate_type'] != "0"){
						$expirateDate = $this->deposit_promotion_check_expirate_date($playerData,$promotionData);
						if(!empty($expirateDate)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//Checking claim limit
				if($allow_apply_promotion){
					if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
						$claimLimit = $this->deposit_promotion_check_claim_limit($playerData,$promotionData);
						if(!empty($claimLimit)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//check first deposit
				if($allow_apply_promotion){
					if($promotionData['first_deposit'] == STATUS_ACTIVE){
						$depositData = $this->get_success_deposit_data($playerData);
						if(!empty($depositData)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//check daily first deposit
				if($allow_apply_promotion){
					if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
						$depositTodayData = $this->get_success_deposit_data_today($playerData);
						if(!empty($depositTodayData)){
							$allow_apply_promotion = FALSE;
						}
					}
				}

				//Check Balance Less
				if($allow_apply_promotion){
					if($promotionData['balance_less']>0){
						if($balanceData['balance_amount'] > $promotionData['balance_less']){
							$allow_apply_promotion = FALSE;
						}
					}
				}	

				if($allow_apply_promotion == TRUE){
					$BData['promotion_id'] = $promotionData['promotion_id'];
					$BData['promotion_title'] = $promotionData['promotion_title'];
					$BData['promotion_content'] = $promotionData['promotion_content'];
					$BData['promotion_banner_web'] = $promotionData['promotion_banner_web'];
					$BData['promotion_banner_mobile'] = $promotionData['promotion_banner_mobile'];
					$BData['promotion_banner_web_content'] = $promotionData['promotion_banner_web_content'];
					$BData['promotion_banner_mobile_content'] = $promotionData['promotion_banner_mobile_content'];
					
					
					$result[] = $BData;
				}
				//
			}
		}
		return $result;
	}

	public function add_player_promotion($data = NULL,$balanceData = NULL, $referrer_data = NULL){
		$promotionData = $this->get_promotion_data_by_id($data['promotion_id']);
		$deposit_amount = $data['amount'];
		$promotion_amount = $data['amount'];
		if($data['amount'] > $promotionData['max_deposit']){
			$promotion_amount = $promotionData['max_deposit'];
		}
		$reward_amount = bcdiv($this->deposit_promotion_reward_amount_decision($promotion_amount,$promotionData),1,2);
		$achieve_amount_data = $this->deposit_promotion_achieve_amount_decision($promotion_amount,$promotionData,$reward_amount,$deposit_amount);

		$DBdata = array(
			'deposit_id' => $data['deposit_id'],
			'deposit_amount' => $deposit_amount,
			'promotion_amount' => $promotion_amount,
			'current_amount' => 0,
			'achieve_amount' => bcdiv($achieve_amount_data['amount'],1,2),
			'bonus_multiply' => $achieve_amount_data['multiply'],
			'bonus_index' => $achieve_amount_data['index'],
			'bonus_level' => $achieve_amount_data['level'],
			'bonus_ids' => (!empty($achieve_amount_data['game_ids']) ? $achieve_amount_data['game_ids'] : $promotionData['game_ids']),
			'reward_amount' => $reward_amount,
			'real_reward_amount' => $reward_amount,
			'original_amount' => $balanceData['balance_amount'],
			'player_id'  => $data['player_id'],
			'player_referrer_id' => ((isset($referrer_data['player_id']))?$referrer_data['player_id']:'0'),
			'promotion_id'  => $promotionData['promotion_id'],
			'promotion_name'  => $promotionData['promotion_name'],
			'url_path' => $promotionData['url_path'],
			'promotion_seq'  => $promotionData['promotion_seq'],
			'genre_code' => $promotionData['genre_code'],
			'genre_name' => $promotionData['genre_name'],
			'date_type' => $promotionData['date_type'],
			'start_date' => $promotionData['start_date'],
			'end_date' => $promotionData['end_date'],
			'specific_day_week' => $promotionData['specific_day_week'],
			'specific_day_day' => $promotionData['specific_day_day'],
			'reward_on_apply' => $promotionData['reward_on_apply'],
			'withdrawal_on_check' => $promotionData['withdrawal_on_check'],
			'is_auto_complete' => $promotionData['is_auto_complete'],
			'level' => $promotionData['level'],
			'accumulate_deposit' => $promotionData['accumulate_deposit'],
			'is_deposit_tied_promotion_count' => $promotionData['is_deposit_tied_promotion_count'],
			'apply_type' => $promotionData['apply_type'],
			'date_expirate_type' => $promotionData['date_expirate_type'],
			'times_limit_type' => $promotionData['times_limit_type'],
			'is_apply_on_first_day_of_times_limit_type' => $promotionData['is_apply_on_first_day_of_times_limit_type'],
			'is_starting_of_the_day' => $promotionData['is_starting_of_the_day'],
			'claim_type' => $promotionData['claim_type'],
			'calculate_day_type' => $promotionData['calculate_day_type'],
			'calculate_hour' => $promotionData['calculate_hour'],
			'calculate_minute' => $promotionData['calculate_minute'],
			'reward_day_type' => $promotionData['reward_day_type'],
			'reward_hour' => $promotionData['reward_hour'],
			'reward_minute' => $promotionData['reward_minute'],
			'first_deposit' => $promotionData['first_deposit'],
			'daily_first_deposit' => $promotionData['daily_first_deposit'],
			'min_deposit' => $promotionData['min_deposit'],
			'max_deposit' => $promotionData['max_deposit'],
			'calculate_type' => $promotionData['calculate_type'],
			'complete_wallet_left' => $promotionData['complete_wallet_left'],
			'bonus_range_type' => $promotionData['bonus_range_type'],
			'bonus_type' => $promotionData['bonus_type'],
			'turnover_multiply' => $promotionData['turnover_multiply'],
			'rebate_percentage' => $promotionData['rebate_percentage'],
			'max_rebate' => $promotionData['max_rebate'],
			'rebate_amount' => $promotionData['rebate_amount'],
			'game_ids' => $promotionData['game_ids'],
			'game_ids_all' => $promotionData['game_ids_all'],
			'live_casino_type' => $promotionData['live_casino_type'],
			'is_level' => $promotionData['is_level'],
			'is_banner' => $promotionData['is_banner'],
			'balance_less' => $promotionData['balance_less'],
			'active' => $promotionData['active'],
			'status' => STATUS_PENDING,
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
			'updated_date' => time()
		);
		$this->db->insert('player_promotion', $DBdata);
		$DBdata['player_promotion_id'] = $this->db->insert_id();
		$range_data = $this->get_all_promotion_bonus_range($data);
		if(!empty($range_data)){
			foreach($range_data as $range_data_row){
				$bonus_range = array(
					'player_promotion_id' => $DBdata['player_promotion_id'],
					'bonus_index' => $range_data_row['bonus_index'],
					'bonus_level' => $range_data_row['bonus_level'],
					'turnover_multiply' => $range_data_row['turnover_multiply'],
					'game_ids' => $range_data_row['game_ids'],
					'amount_from' => $range_data_row['amount_from'],
					'amount_to' => $range_data_row['amount_to'],
					'bonus_amount' => $range_data_row['bonus_amount'],
					'percentage' => $range_data_row['percentage'],
					'max_amount' => $range_data_row['max_amount'],
					'active' => $range_data_row['active'],
				);
				$this->db->insert('player_promotion_bonus_range', $bonus_range);
			}
		}
		return $DBdata;
	}

	public function deposit_promotion_check_expirate_date_approve($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_promotion.player_promotion_id != ', $data['player_promotion_id']);
		$this->db->where('player_promotion.promotion_id', $data['promotion_id']);
		$this->db->where('player_promotion.player_id', $data['player_id']);
		$this->db->where('player_promotion.active', STATUS_ACTIVE);
		$this->db->where('player_promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}
		$this->db->group_start();
			$this->db->where('player_promotion.end_date >= ',time());
			$this->db->or_where('player_promotion.end_date',0);
		$this->db->group_end();
		$this->db->where('player_promotion.updated_date <=',time());
		$this->db->where('player_promotion.updated_date >=',strtotime('-'.$data['date_expirate_type'].' days', time()));
		$this->db->order_by('player_promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_check_claim_limit_approve($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_promotion.player_promotion_id != ', $data['player_promotion_id']);
		$this->db->where('player_promotion.promotion_id', $data['promotion_id']);
		$this->db->where('player_promotion.player_id', $data['player_id']);
		$this->db->where('player_promotion.active', STATUS_ACTIVE);
		$this->db->where('player_promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}
		$this->db->group_start();
			$this->db->where('player_promotion.end_date >= ',time());
			$this->db->or_where('player_promotion.end_date',0);
		$this->db->group_end();
		if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_ONCE){

		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',time())));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:00',time())));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of this month'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of this month'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of january'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of december'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('sunday last week'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('saturday this week'))));
		}

		$this->db->order_by('player_promotion.updated_date', 'DESC');
		$this->db->order_by('player_promotion.created_date', 'DESC');
		$this->db->order_by('player_promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	//Promotion Strict Start//
	public function deposit_promotion_approve_decision($data  = NULL,$balanceData = NULL){
		$result = array(
			'status' => EXIT_ERROR,
			'code' => DEPOSIT_PROMOTION_UNKNOWN_ERROR,
			'msg' => $this->lang->line('error_system_error'), 	
		);
		$allow_approve_promotion = TRUE;

		//Checking either deposit promotion is available
		if($allow_approve_promotion){
			if(empty($data)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_NOT_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_not_exits');
			}
		}

		//Checking either promotion is available
		if($allow_approve_promotion){
			$promotionData = $this->get_player_deposit_promotion_available_by_id_approve($data);
			if(empty($promotionData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE;	
				$result['msg'] = $this->lang->line('error_promotion_not_available');
			}
		}

		if($allow_approve_promotion){
			$promotionPendingData = $this->get_player_not_complete_deposit_promotion($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		if($allow_approve_promotion){
			$promotionPendingData = $this->get_player_pending_deposit_promotion_approve($data);
			if(!empty($promotionPendingData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}

		//Checking promotion valid expirate date
		if($allow_approve_promotion){
			if($promotionData['date_expirate_type'] != "0"){
				$expirateDate = $this->deposit_promotion_check_expirate_date_approve($data,$promotionData);
				if(!empty($expirateDate)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE;
					$result['msg'] = $this->lang->line('error_promotion_not_pass_expirate_date');	
				}
			}
		}

		//Checking claim limit
		if($allow_approve_promotion){
			if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
				$claimLimit = $this->deposit_promotion_check_claim_limit_approve($data,$promotionData);
				if(!empty($claimLimit)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT;
					$result['msg'] = $this->lang->line('error_promotion_exceed_claim_limit');
				}
			}
		}

		//Check First Deposit
		if($allow_approve_promotion){
			if($promotionData['first_deposit'] == STATUS_ACTIVE){
				$depositData = $this->get_success_deposit_data($data);
				if(!empty($depositData)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit');
				}
			}
		}

		//check daily first deposit
		if($allow_approve_promotion){
			if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
				$depositTodayData = $this->get_success_deposit_data_today($data);
				if(!empty($depositTodayData)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit_daily');
				}
			}
		}

		if($allow_approve_promotion == TRUE){
			$result['status'] = EXIT_SUCCESS;
			$result['code'] = DEPOSIT_PROMOTION_SUCCESSS;
			$result['msg'] = $this->lang->line('error_success');
		}
		return $result;
	}

	public function update_deposit_promotion_status($arr = NULL,$promotion_status = NULL)
	{	
		$DBdata = array(
			'promotion_status' => $promotion_status,
		);
		$this->db->where('deposit_id', $arr['deposit_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update('deposits', $DBdata);
		return $DBdata;
	}

	public function update_player_promotion_after_deposit($arr = NULL,$balanceData = NULL,$depositAmount = NULL,$type = NULL){
		$starting_date = $this->promotion_starting_date_decision($arr);
		$balance_amount = (isset($balanceData['balance_amount'])?$balanceData['balance_amount']:"0");
		if(!empty($type)){
			$original_amount = $balance_amount - $depositAmount;
		}else{
			$original_amount = $balance_amount;
		}
		
		$DBdata = array(
			'original_amount' => $original_amount,
			'status' => STATUS_ENTITLEMENT,
			'starting_date' => $starting_date,
			'updated_by' => $arr['username'],
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update('player_promotion', $DBdata);
		return $DBdata;
	}

	public function update_player_promotion_reward_claim($arr = NULL,$balanceData = NULL){
		$DBdata = array(
			'is_reward' => STATUS_APPROVE,
			'reward_accumulate' => $arr['reward_accumulate'] + $arr['reward_amount'],
			'reward_date' => time(),
			'updated_by' => $arr['username'],
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update('player_promotion', $DBdata);
		return $DBdata;
	}

	public function check_promotion_allow_withdrawal($player_id = NULL){
		$result = array(
			'status' 	=> TRUE,
			'method' 	=> 0
		);
		$result_array = NULL;
		$current_amount = 0;
		$this->db->select('player_promotion_id, calculate_type, game_ids, live_casino_type, achieve_amount, starting_date, complete_date, current_amount');
		$this->db->where('player_id',$player_id);
		$this->db->where('withdrawal_on_check',STATUS_ACTIVE);
		$this->db->where_in('status',array(STATUS_ENTITLEMENT));
		$query = $this->db->get('player_promotion');
		if($query->num_rows() > 0)
		{
			$result_array = $query->result_array();  
		}

		if(!empty($result_array)){
			foreach($result_array as $result_array_row){
				if($result['status']){
					$current_amount = 0;
					$result_live_casino_amount = $this->promotion_calculate_current_amount($player_id, GAME_LIVE_CASINO, $result_array_row['calculate_type'], $result_array_row['game_ids'], $result_array_row['live_casino_type'], $result_array_row['starting_date'], $result_array_row['complete_date']);
					$result_all_amount = $this->promotion_calculate_current_amount($player_id, GAME_ALL, $result_array_row['calculate_type'], $result_array_row['game_ids'], $result_array_row['live_casino_type'], $result_array_row['starting_date'], $result_array_row['complete_date']);

					if(!empty($result_live_casino_amount['current_amount'])){
						$current_amount += $result_live_casino_amount['current_amount'];
					}

					if(!empty($result_all_amount['current_amount'])){
						$current_amount += $result_all_amount['current_amount'];
					}

					if($result_array_row['current_amount'] != $current_amount){
						$this->update_promotion_current_amount($result_array_row['player_promotion_id'] ,$current_amount);
					}
					if($result_array_row['achieve_amount'] > $current_amount){
						$result['status'] = FALSE;
					}
				} 
			}
		} else {
			$result['method'] = 1;
		}
		return $result;
	}

	public function promotion_calculate_current_amount($player_id = NULL, $game_type = NULL, $calculate_type = NULL, $game_ids = NULL, $live_casino_type = NULL, $starting_date = NULL, $complete_date = NULL){

		$result = NULL;
		if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($calculate_type == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
		}
		else if($calculate_type == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
		}

		if(!empty($game_ids)){
			$game_ids_array = array_filter(explode(',', $game_ids));
			$this->db->where_in('transaction_report.game_provider_type_code', $game_ids_array);
		}
		if($game_type == GAME_LIVE_CASINO){
			$this->db->group_start();
			$this->db->where('transaction_report.game_type_code', GAME_LIVE_CASINO);
			if(strpos($live_casino_type, (string)LIVE_CASINO_BACCARAT) === false){
				$this->db->where('transaction_report.game_code != ', 'Baccarat');
			}
			if(strpos($live_casino_type, (string)LIVE_CASINO_NON_BACCARAT) === false){
				$this->db->where('transaction_report.game_code', 'Baccarat');
			}
			$this->db->group_end();
		}else{
			$this->db->where('transaction_report.game_type_code !=', GAME_LIVE_CASINO);
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($calculate_type == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('transaction_report.status', STATUS_COMPLETE);
		$this->db->where('transaction_report.payout_time >= ', $starting_date);
		if(!empty($complete_date)){
			$this->db->where('transaction_report.payout_time <= ', $complete_date);
		}
		$this->db->where('transaction_report.player_id', $player_id);
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}

	public function update_promotion_current_amount($player_promotion_id = NULL, $current_amount = NULL){
		$DBdata = array(
			'current_amount' => $current_amount,
		);
		$this->db->where('player_promotion_id', $player_promotion_id);
		$this->db->limit(1);
		$this->db->update('player_promotion', $DBdata);
	}

	public function get_promotion_lang_data_by_id($id) {
		$result = NULL;
		$this->db->from('promotion_lang');
		$this->db->where('promotion_id', $id);
		$this->db->where("COALESCE(promotion_title, '') !=", ''); 
		$this->db->where("COALESCE(promotion_content, '') !=", ''); 

		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		$query->free_result();

		// echo $this->db->last_query();
		// die;

		return $result;
	}

	public function verify_turnover($player_id = NULL){
		$arr = array(STATUS_COMPLETE,STATUS_CANCEL);
		$this->db->select('created_date');
		$this->db->where('player_id',$player_id);
		$this->db->where_in('status',$arr);
		$this->db->order_by('player_promotion_id','DESC');
		$this->db->limit(1);
		$query = $this->db->get('player_promotion');
		if($query->num_rows() > 0) {
			$row = $query->row_array();  
			$ddate = $row['created_date'];
		}
		else {
			$ddate = 0;
		}
		$query->free_result();	
		
		$this->db->select('created_date');
		$this->db->where('player_id',$player_id);		
		$this->db->where('status',STATUS_APPROVE);
		$this->db->order_by('created_date','DESC');
		$this->db->limit(1);
		$query = $this->db->get('withdrawals');
		if($query->num_rows() > 0) {
			$c = $query->row_array();  
			$wdate = $c['created_date'];
		}
		else {
			$wdate = 0;
		}		
		$query->free_result();	
		
		$date = ($ddate > $wdate) ? $ddate : $wdate;
		
		###Get total deposit###
		$this->db->select_sum('amount');
		$this->db->where('player_id',$player_id);
		if($date > 0) {
			$this->db->where('created_date >=',$date);
		}
		$this->db->where('status',STATUS_APPROVE);
		$query = $this->db->get('deposits');
		$row = $query->row_array();  
		$target_turnover = ($row['amount'] != '') ? $row['amount'] : 0;
		$query->free_result();
		
		###Get total turnover###
		if($target_turnover > 0) {
			$this->db->select_sum('bet_amount_valid');
			$this->db->where('player_id',$player_id);
			if($date > 0) {
				$this->db->where('bet_time >=',$date);
			}
			$query = $this->db->get('transaction_report');
			$row = $query->row_array();
			$current_turnover = ($row['bet_amount_valid'] != '') ? $row['bet_amount_valid'] : 0;
			$query->free_result();
			
			if($current_turnover >= $target_turnover) {
				$result['status'] 	= TRUE;
				$result['target'] 	= val_decimal($target_turnover,2);
				$result['current'] 	= val_decimal($current_turnover,2);
				$result['need'] 	= val_decimal(0,2);
			}
			else {
				$result['status'] 	= FALSE;
				$result['target'] 	= val_decimal($target_turnover,2);
				$result['current'] 	= val_decimal($current_turnover,2);
				$result['need'] 	= val_decimal(($target_turnover-$current_turnover),2);
			}
		}
		else {
			$result['status'] 	= TRUE;
			$result['target'] 	= val_decimal($target_turnover,2);
			$result['current'] 	= val_decimal($target_turnover,2);
			$result['need'] 	= val_decimal($target_turnover,2);					
		}

		return $result;
	}

	// This is welcome bonus
	public function get1($data = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		$this->db->where_in('promotion_id',array(7,8,9,10,11));
		$this->db->where_in('status', array(STATUS_COMPLETE));
		$this->db->limit(1);
		$query = $this->db->get();

		// log_message('error', 'get1: '.$this->db->last_query());
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	// This is Unlimited Bonus
	// public function get2($data = NULL){
	// 	$result = NULL;
	// 	$this->db->from('player_promotion');
	// 	$this->db->where('player_id',$data['player_id']);
	// 	$this->db->where_in('promotion_id',array(7,8,9,10,11,15,16,17,18));
	// 	$this->db->where_in('status', array(STATUS_COMPLETE));
	// 	$this->db->limit(1);
	// 	$query = $this->db->get();

	// 	// log_message('error', 'get2: '.$this->db->last_query());
	// 	if($query->num_rows() > 0)
	// 	{
	// 		$result = $query->row_array();
	// 	}
	// 	$query->free_result();
	// 	return $result;
	// }

	// Hiden status == 0
	// public function get3($data = NULL){
	// 	$result = NULL;
	// 	$this->db->from('player_promotion');
	// 	$this->db->where('player_id',$data['player_id']);
	// 	$this->db->where_in('status', array(STATUS_PENDING,STATUS_ENTITLEMENT));
	// 	$this->db->limit(1);
	// 	$query = $this->db->get();

	// 	log_message('error', 'get1: '.$this->db->last_query());
	// 	if($query->num_rows() > 0)
	// 	{
	// 		$result = $query->row_array();
	// 	}
	// 	$query->free_result();
	// 	return $result;
	// }

	// Hiden status == 0 & 3
	public function get4($data = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		$this->db->where_in('status', array(STATUS_PENDING,STATUS_ENTITLEMENT));
		$this->db->limit(1);
		$query = $this->db->get();

		log_message('error', 'get1: '.$this->db->last_query());
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}
}