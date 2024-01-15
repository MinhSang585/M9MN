<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends MY_Controller {

	public function __construct()
	{
		parent::__construct();	
	}
	
	public function testtastas(){
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
        
        ad($game_code_data);
	}
	
	public function recount_deposit_amount(){
	    $this->db->select('player_id,amount,deposit_type,updated_date,created_date');
	    $this->db->where('deposit_type != ',DEPOSIT_OFFLINE_BANKING);
		$this->db->where('status',STATUS_COMPLETE);
		$query = $this->db->get('deposits');
		$player_winloss_list = array();
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
			foreach($result_data as $result_row){
			    $player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];
			    $player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])));
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['bonus_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['promotion_amount'] = 0;
				
				//seperator
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_offline_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_online_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_credit_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_hypermart_amount'] += 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_point_amount'] = 0;
				
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_offline_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_online_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['withdrawals_point_amount'] = 0;
				
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_in_amount'] = 0;
				$player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['adjust_out_amount'] = 0;
				
				if($result_row['deposit_type'] == DEPOSIT_OFFLINE_BANKING){
				    
				}else{
				    if($result_row['deposit_type'] == DEPOSIT_ONLINE_BANKING){
				        $player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_online_amount'] += $result_row['amount'];
				    }else if($result_row['deposit_type'] == DEPOSIT_CREDIT_CARD){
				        $player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_credit_amount'] += $result_row['amount'];
				    }else if($result_row['deposit_type'] == DEPOSIT_HYPERMART){
				        $player_winloss_list[$result_row['player_id']][(strtotime(date('Y-M-d 00:00:00',$result_row['created_date'])))]['deposit_online_hypermart_amount'] += $result_row['amount'];
				    }
				}
			}
		}
		if(!empty($player_winloss_list) && sizeof($player_winloss_list)>0){
		    foreach($player_winloss_list as $player_winlost_row){
		        if(!empty($player_winlost_row) && sizeof($player_winlost_row)>0){
					foreach($player_winlost_row as $each_player_winlost_row){
					    $this->report_model->insert_total_win_loss_report_dwa($each_player_winlost_row);
					}
		        }
		    }
		}
		
		echo "done";
	}
	
	public function win_loss_monthly_deposit_recount(){
	    $table = $this->db->dbprefix . "total_win_loss_report_month";
	    $table_player = $this->db->dbprefix . "players";
	    $result_data = NULL;
	    $end_time = 1664611560;
	    $this->db->select('player_id,amount,deposit_type,updated_date,created_date');
		$this->db->where('updated_date <',$end_time);
		$this->db->where('status',STATUS_COMPLETE);
		$query = $this->db->get('deposits');
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
			foreach($result_data as $result_row){
		        $this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['player_id'] = $result_row['player_id'];
				$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])));
				$this->player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['created_date'])))]['deposit_count'] += 1;
			}
			
			if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){
			    foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){
			        if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){
			            foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){
			                $this->db->query("UPDATE {$table} SET deposit_count = (deposit_count + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id'], $each_player_winloss_list_monthly_row['report_date']));
			                $this->db->query("UPDATE {$table_player} SET deposit_count = (deposit_count + ?) WHERE player_id = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id']));
			            }
			        }
			    }
			}
		}
	}
	
	public function win_loss_monthly_cash_count(){
	    $table = $this->db->dbprefix . "total_win_loss_report_month";
	    $table_player = $this->db->dbprefix . "players";
	    set_time_limit(0);
		$member_lists = $this->player_model->get_player_list_array();
	    $result_data = NULL;
	    $cash_transfer_id = 4571;
	    $this->db->select('transfer_type, username, deposit_amount, withdrawal_amount, report_date');
		$this->db->where('transfer_type',TRANSFER_ADJUST_IN);
		$this->db->where('cash_transfer_id <= ',$cash_transfer_id);
		$this->db->order_by('cash_transfer_id',"ASC");
		$query = $this->db->get('cash_transfer_report');
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
			foreach($result_data as $result_row){
			    $this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['player_id'] = $member_lists[strtolower($result_row['username'])];
			    $this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])));
			    $this->player_winloss_list_monthly[$member_lists[strtolower($result_row['username'])]][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_count'] += 1;
			}
			if(!empty($this->player_winloss_list_monthly) && sizeof($this->player_winloss_list_monthly)>0){
			    foreach($this->player_winloss_list_monthly as $player_winloss_list_monthly_row){
			        if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){
			            foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){
			                $this->db->query("UPDATE {$table} SET deposit_count = (deposit_count + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id'], $each_player_winloss_list_monthly_row['report_date']));
			                $this->db->query("UPDATE {$table_player} SET deposit_count = (deposit_count + ?) WHERE player_id = ? LIMIT 1", array($each_player_winloss_list_monthly_row['deposit_count'],$each_player_winloss_list_monthly_row['player_id']));
			            }
			        }
			    }
			}
		}
	}
	
	public function reset_all_win_loss_report(){
	   $update_array = array(
	    'deposit_amount' => 0,
	    'deposit_offline_amount' => 0,
	    'deposit_online_amount' => 0,
	    'deposit_point_amount' => 0,
	    'withdrawals_amount' => 0,
	    'withdrawals_offline_amount' => 0,
	    'withdrawals_online_amount' => 0,
	    'withdrawals_point_amount' => 0,
	    'adjust_amount' => 0,
	    'adjust_in_amount' => 0,
	    'adjust_out_amount' => 0,
	    'bonus_amount' => 0,
	    'promotion_amount' => 0,
	   );
	   
	   $this->db->update('total_win_loss_report',$update_array);
	}
	
	public function make_win_loss_monthly_report(){
	    $start_date = strtotime('2022-08-01 00:00:00');
	    $end_date = strtotime('2022-09-01 00:00:00');
	    ad($start_date);
	    ad($end_date);
	    $player_winloss_list_monthly = array();
	    $bulk_result = array();
	    $this->db->where('report_date >=',$start_date);
	    $this->db->where('report_date <',$end_date);
	    $query = $this->db->get('total_win_loss_report');
	    if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $result_row){
			    $player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['player_id'] = $result_row['player_id'];
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['report_date'] = (strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])));
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['deposit_amount'] += ($result_row['deposit_offline_amount'] + $result_row['deposit_online_amount'] + $result_row['deposit_point_amount'] + $result_row['adjust_in_amount']);
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['withdrawals_amount'] += ($result_row['withdrawals_point_amount'] + $result_row['withdrawals_online_amount'] + $result_row['withdrawals_offline_amount'] + $result_row['adjust_out_amount']);
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['bonus_amount'] += $result_row['bonus_amount'];
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['promotion_amount'] += $result_row['promotion_amount'];
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['bet_amount_valid'] += $result_row['bet_amount_valid'];
				$player_winloss_list_monthly[$result_row['player_id']][(strtotime(date('Y-M-01 00:00:00',$result_row['report_date'])))]['win_loss'] += $result_row['win_loss'];
			}
			
			if(!empty($player_winloss_list_monthly) && sizeof($player_winloss_list_monthly)>0){
				foreach($player_winloss_list_monthly as $player_winloss_list_monthly_row){
					if(!empty($player_winloss_list_monthly_row) && sizeof($player_winloss_list_monthly_row)>0){
						foreach($player_winloss_list_monthly_row as $each_player_winloss_list_monthly_row){
						    array_push($bulk_result, $each_player_winloss_list_monthly_row);
						}
					}
				}
			}
			ad($bulk_result);
			if(!empty($bulk_result)){
				$this->db->insert_batch('total_win_loss_report_month', $bulk_result);
			}
		}
	}
    
    public function run_calculate_daily_bet(){
	    $start_date = strtotime('2022-08-18 00:00:00');
	    $end_date = strtotime('2022-08-18 23:59:59');
	    $DBdata = array(
			'total_bet' => 0,
			'bet_amount' => 0,
			'bet_amount_valid' => 0,
			'win_loss' => 0,
		);
		$this->db->where('report_date',$start_date);
		$this->db->update('win_loss_report', $DBdata);
		$this->db->where('report_date',$start_date);
		$this->db->update('total_win_loss_report', $DBdata);
		
		$game_provider_code = "AB";
	    $game_type_code = "LC";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "BL";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "BL";
	    $game_type_code = "BG";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "BNG";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "DG";
	    $game_type_code = "LC";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "DG";
	    $game_type_code = "OT";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "DT";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "ICG";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "ICG";
	    $game_type_code = "FH";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "ICG";
	    $game_type_code = "OT";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "ICG";
	    $game_type_code = "BG";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "NK";
	    $game_type_code = "LT";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "OBSB";
	    $game_type_code = "SB";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "OG";
	    $game_type_code = "LC";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "PNG";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "RTG";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "RTG";
	    $game_type_code = "FH";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SA";
	    $game_type_code = "LC";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SP";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SP";
	    $game_type_code = "FH";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SP";
	    $game_type_code = "OT";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SPLT";
	    $game_type_code = "LT";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "SPSB";
	    $game_type_code = "SB";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
	    
	    $game_provider_code = "WM";
	    $game_type_code = "LC";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }
		
    }
    
    public function run_calculate_daily_payout(){
	    $start_date = strtotime('2022-06-29 00:00:00');
	    $end_date = strtotime('2022-06-29 23:59:59');
	    $DBdata = array(
			'total_bet' => 0,
			'bet_amount' => 0,
			'bet_amount_valid' => 0,
			'win_loss' => 0,
		);
		$this->db->where('report_date',$start_date);
		$this->db->update('win_loss_report', $DBdata);
		$this->db->where('report_date',$start_date);
		$this->db->update('total_win_loss_report', $DBdata);
    }
    
    public function recalculate_win_loss_bet($start = 0,$game_provider_code = NULL,$game_type_code = NULL,$start_date = NULL,$end_date = NULL){
		$player_lists = array();
	    $game_provider_type_code = $game_provider_code."_".$game_type_code;
	    $limit = 5000;
	    $offset = $start *  $limit;
	    
		$query = $this->db->query("SELECT player_id FROM bctp_players");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$player_lists[$row->player_id] = array(
					'payout_time' => strtotime(date('Y-m-d 00:00:00',$start_date)),
					'game_provider_code' => $game_provider_code,
					'game_type_code' => $game_type_code,
					'game_provider_type_code' => $game_provider_type_code,
					'player_id' => $row->player_id,
					'total_bet' => 0,
					'bet_amount' => 0,
					'bet_amount_valid' => 0,
					'win_loss' => 0,
				);
			}
			$trans_query = $this
				->db
				->select('player_id,bet_amount,bet_amount_valid,win_loss')
				->where('game_provider_code', $game_provider_code)
				->where('game_type_code', $game_type_code)
				->where('status', STATUS_COMPLETE)
				->where('bet_time >= ', $start_date)
				->where('bet_time <= ', $end_date)
				->order_by('transaction_id', 'ASC')
				->limit($limit,$offset)
				->get('transaction_report');
			if($trans_query->num_rows() > 0)
			{
				foreach($trans_query->result() as $trans_row) {
					if(isset($player_lists[$trans_row->player_id])) {
						$player_lists[$trans_row->player_id]['total_bet'] = ($player_lists[$trans_row->player_id]['total_bet'] + 1);
						$player_lists[$trans_row->player_id]['bet_amount'] = ($player_lists[$trans_row->player_id]['bet_amount'] + $trans_row->bet_amount);
						$player_lists[$trans_row->player_id]['bet_amount_valid'] = ($player_lists[$trans_row->player_id]['bet_amount_valid'] + $trans_row->bet_amount_valid);
						$player_lists[$trans_row->player_id]['win_loss'] = ($player_lists[$trans_row->player_id]['win_loss'] + $trans_row->win_loss);
					}
				}
				
				
				if(sizeof($player_lists) > 0){
				    foreach($player_lists as $player_lists_row){
				        if($player_lists_row['total_bet'] > 0 ){
				            $this->report_model->add_win_loss($player_lists_row);
		                    $this->report_model->add_total_win_loss($player_lists_row);
		                    echo "1";
				        }
				    }
				}
			    return "0";
			}else{
			    echo "end<br/>";
			    return "1";
			}
		}
	}
	
    public function recalculate_win_loss_payout($start = 0,$game_provider_code = NULL,$game_type_code = NULL,$start_date = NULL,$end_date = NULL){
		$player_lists = array();
	    $game_provider_type_code = $game_provider_code."_".$game_type_code;
	    $limit = 5000;
	    $offset = $start *  $limit;
	    
		$query = $this->db->query("SELECT player_id FROM bctp_players");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$player_lists[$row->player_id] = array(
					'payout_time' => strtotime(date('Y-m-d 00:00:00',$start_date)),
					'game_provider_code' => $game_provider_code,
					'game_type_code' => $game_type_code,
					'game_provider_type_code' => $game_provider_type_code,
					'player_id' => $row->player_id,
					'total_bet' => 0,
					'bet_amount' => 0,
					'bet_amount_valid' => 0,
					'win_loss' => 0,
				);
			}
			$trans_query = $this
				->db
				->select('player_id,bet_amount,bet_amount_valid,win_loss')
				->where('game_provider_code', $game_provider_code)
				->where('game_type_code', $game_type_code)
				->where('status', STATUS_COMPLETE)
				->where('payout_time >= ', $start_date)
				->where('payout_time <= ', $end_date)
				->order_by('transaction_id', 'ASC')
				->limit($limit,$offset)
				->get('transaction_report');
			if($trans_query->num_rows() > 0)
			{
				foreach($trans_query->result() as $trans_row) {
					if(isset($player_lists[$trans_row->player_id])) {
						$player_lists[$trans_row->player_id]['total_bet'] = ($player_lists[$trans_row->player_id]['total_bet'] + 1);
						$player_lists[$trans_row->player_id]['bet_amount'] = ($player_lists[$trans_row->player_id]['bet_amount'] + $trans_row->bet_amount);
						$player_lists[$trans_row->player_id]['bet_amount_valid'] = ($player_lists[$trans_row->player_id]['bet_amount_valid'] + $trans_row->bet_amount_valid);
						$player_lists[$trans_row->player_id]['win_loss'] = ($player_lists[$trans_row->player_id]['win_loss'] + $trans_row->win_loss);
					}
				}
				
				
				if(sizeof($player_lists) > 0){
				    foreach($player_lists as $player_lists_row){
				        if($player_lists_row['total_bet'] > 0 ){
				            $this->report_model->add_win_loss($player_lists_row);
		                    $this->report_model->add_total_win_loss($player_lists_row);
		                    echo "1";
				        }
				    }
				}
			    return "0";
			}else{
			    echo "end<br/>";
			    return "1";
			}
		}
	}
	
	public function delete_duplicate_record(){
	    set_time_limit(0);
	    $lists = array();
	    $lists2 = array();
	    $game_provider_code = "DT";
	    $game_result_type = "SL";
	    $bet_id = "";
	    $query = $this->db->query("SELECT bet_id, COUNT(*) FROM bctp_transaction_report WHERE game_provider_code = '{$game_provider_code}' AND game_type_code = '{$game_result_type}' GROUP BY bet_id HAVING COUNT(*) > 1 ORDER BY COUNT(*) DESC LIMIT 100000");
	    //$query = $this->db->query("SELECT bet_id, COUNT(*) FROM bctp_transaction_report WHERE game_provider_code = '{$game_provider_code}' AND game_type_code = '{$game_result_type}' GROUP BY bet_id HAVING COUNT(*) > 1 ORDER BY COUNT(*) DESC");
	    
		if($query->num_rows() > 0)
		{
    		foreach($query->result() as $row) {
    			array_push($lists, $row->bet_id);
    		}
    		
    	    $this->db->select('transaction_id,bet_id');
    	    $this->db->where('game_provider_code', $game_provider_code);
    	    $this->db->where('game_type_code', $game_result_type);
    	    $this->db->where_in('bet_id', $lists);
    	    $this->db->order_by('bet_id', ASC);
    	    $this->db->order_by('transaction_id', ASC);
    	    $query2 = $this->db->get('transaction_report');
    	    if($query2->num_rows() > 0)
		    {      
    	       foreach($query2->result() as $row_test){
    	            if($bet_id != $row_test->bet_id){
    	                $bet_id = $row_test->bet_id;
    	                $this->db->where('transaction_id',$row_test->transaction_id);
    	                $this->db->limit(1);
    	                $this->db->delete('transaction_report');
    	                
    	            }
    	            echo "1";
    	       }
    	       ad("done");
		    }
		}
	}
	
	public function adjust_spsb_win_loss(){
	    $start_date = "2023-04-01 00:00:00";
	    $end_date = "2023-04-31 23:59:59";
	    $start_time = strtotime($start_date);
	    $end_time = strtotime($end_date);
	    $player_list = array();
	    $winloss_list = array();
	    $Bdata = array();
	    
	    $provider_code = "SPSB";
	    $game_type_code = "SB";
	    
	    $this->db->select('game_provider_code,game_type_code,game_code,bet_amount,bet_amount_valid,win_loss,player_id,bet_time,payout_time');
	    $this->db->where('bet_time >= ',$start_time);
	    $this->db->where('bet_time <= ',$end_time);
	    $this->db->where('game_provider_code',$provider_code);
	    $this->db->where('game_type_code',$game_type_code);
	    $this->db->where('status',1);
	    $query = $this->db->get('transaction_report');
	    if($query->num_rows() > 0)
		{
		    foreach($query->result() as $row){
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_provider_code'] = $row->game_provider_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_type_code'] = $row->game_type_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_provider_type_code'] = $row->game_provider_code . "_" . $row->game_type_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['player_id'] = $row->player_id;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['total_bet'] += 1;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['bet_amount'] += bcdiv($row->bet_amount,1,2);
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['bet_amount_valid'] += bcdiv($row->bet_amount_valid,1,2);
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['win_loss'] += bcdiv($row->win_loss,1,2);
		        
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['game_provider_code'] = $row->game_provider_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['game_type_code'] = $row->game_type_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['game_provider_type_code'] = $row->game_provider_code . "_" . $row->game_type_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['game_code'] = $row->game_code;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['player_id'] = $row->player_id;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['total_bet'] += 1;
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['bet_amount'] += bcdiv($row->bet_amount,1,2);
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['bet_amount_valid'] += bcdiv($row->bet_amount_valid,1,2);
		        $player_list[$row->player_id][(strtotime(date('Y-m-d 00:00:00',$row->bet_time)))]['game_code'][$row->game_code]['win_loss'] += bcdiv($row->win_loss,1,2);
		    }
		}
		$query->free_result();
		//ad($player_list);
		
		if(!empty($player_list)){
		    $this->db->where('report_date >= ',$start_time);
    	    $this->db->where('report_date <= ',$end_time);
    	    $this->db->where('game_provider_code',$provider_code);
    	    $this->db->where('game_type_code',$game_type_code);
    	    $query = $this->db->get('win_loss_report');
    	    if($query->num_rows() > 0)
    		{
    		    foreach($query->result() as $row){
    	            $winloss_list[$row->player_id][$row->report_date]['game_provider_code'] = $row->game_provider_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_type_code'] = $row->game_type_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_provider_type_code'] = $row->game_provider_type_code;
    	            $winloss_list[$row->player_id][$row->report_date]['report_date'] = $row->report_date;
    	            $winloss_list[$row->player_id][$row->report_date]['player_id'] = $row->player_id;
    	            $winloss_list[$row->player_id][$row->report_date]['total_bet'] += $row->total_bet;
    	            $winloss_list[$row->player_id][$row->report_date]['bet_amount'] += $row->bet_amount;
    	            $winloss_list[$row->player_id][$row->report_date]['bet_amount_valid'] += $row->bet_amount_valid;
    	            $winloss_list[$row->player_id][$row->report_date]['win_loss'] += $row->win_loss;
    		    }
    		}
    		$query->free_result();
		}
		
		
		if(!empty($player_list)){
		    $this->db->where('report_date >= ',$start_time);
    	    $this->db->where('report_date <= ',$end_time);
    	    $this->db->where('game_provider_code',$provider_code);
    	    $this->db->where('game_type_code',$game_type_code);
    	    $query = $this->db->get('win_loss_report_by_game_code');
    	    if($query->num_rows() > 0)
    		{
    		    foreach($query->result() as $row){
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['game_provider_code'] = $row->game_provider_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['game_type_code'] = $row->game_type_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['game_provider_type_code'] = $row->game_provider_type_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['game_code'] = $row->game_code;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['report_date'] = $row->report_date;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['player_id'] = $row->player_id;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['total_bet'] += $row->total_bet;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['bet_amount'] += $row->bet_amount;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['bet_amount_valid'] += $row->bet_amount_valid;
    	            $winloss_list[$row->player_id][$row->report_date]['game_code'][$row->game_code]['win_loss'] += $row->win_loss;
    		    }
    		}
    		$query->free_result();
		}
		//ad($winloss_list);
		
		
		if(!empty($player_list)){
		    foreach($player_list as $player_id => $player_list_row){
		        foreach($player_list_row as $report_date => $player_date_list_row){
		            if(($player_date_list_row['total_bet'] != $winloss_list[$player_id][$report_date]['total_bet']) || ($player_date_list_row['win_loss'] != $winloss_list[$player_id][$report_date]['win_loss'])){
		                foreach($player_date_list_row['game_code'] as $game_code => $row){
		                    $bData = array(
		                        'game_provider_code' => $row['game_provider_code'],
		                        'game_type_code' => $row['game_type_code'],
		                        'game_code' => $row['game_code'],
		                        'total_bet' => $row['total_bet'],
		                        'bet_amount' => $row['bet_amount'],
		                        'bet_amount_valid' => $row['bet_amount_valid'],
		                        'win_loss' => $row['win_loss'],
		                        'player_id' => $player_id,
		                        'payout_time' => $report_date,
		                        'bet_time' => $report_date,
		                    );
		                    
		                    if(isset($winloss_list[$player_id][$report_date]['game_code'][$row['game_code']])){
		                        $bData['total_bet'] -= $winloss_list[$player_id][$report_date]['game_code'][$row['game_code']]['total_bet'];
		                        $bData['bet_amount'] -= $winloss_list[$player_id][$report_date]['game_code'][$row['game_code']]['bet_amount'];
		                        $bData['bet_amount_valid'] -= $winloss_list[$player_id][$report_date]['game_code'][$row['game_code']]['bet_amount_valid'];
		                        $bData['win_loss'] -= $winloss_list[$player_id][$report_date]['game_code'][$row['game_code']]['win_loss'];
		                    }
		                    
		                    if(($bData['total_bet'] == 0) && ($bData['win_loss'] == 0) && ($bData['bet_amount'] == 0) && ($bData['bet_amount_valid'] == 0)){
		                        
		                    }else{
		                        array_push($Bdata, $bData);
		                    }
		                }
		            }
		        }
		    }
		}
		
		if( ! empty($Bdata))
		{
			$this->db->insert_batch('win_loss_logs', $Bdata);
		}
	}
}