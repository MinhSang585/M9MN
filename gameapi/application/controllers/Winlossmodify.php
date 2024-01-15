<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Winlossmodify extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function recount_win_loss_report_by_game_code_by_bet_time(){
	    $days = "2023-01-10";
		$start_date = strtotime($days.' 00:00:00');
		$end_date = strtotime($days.' 23:59:59');
	    ad($start_date);
        ad(date('Y-m-d H:i:s',$start_date));
        ad(date('Y-m-d H:i:s',$end_date+1));
	    $DBdata = array(
			'total_bet' => 0,
			'bet_amount' => 0,
			'bet_amount_valid' => 0,
			'win_loss' => 0,
		);
		$this->db->where('report_date',$start_date);
		$this->db->update('win_loss_report_by_game_code', $DBdata);
    
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

	 	$game_provider_code = "GR";
	    $game_type_code = "SL";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }

	    $game_provider_code = "GR";
	    $game_type_code = "BG";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }

	    $game_provider_code = "GR";
	    $game_type_code = "FH";
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
	    $game_type_code = "BG";
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
	    
	    $game_provider_code = "NAGA";
	    $game_type_code = "SL";
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

	    $game_provider_code = "RSG";
	    $game_type_code = "FH";
		for($i=0;$i<=100;$i++){
			$test = $this->recalculate_win_loss_bet($i,$game_provider_code,$game_type_code,$start_date,$end_date);
			if($test == "1"){
			  $i = 101;  
			}
	    }

	    $game_provider_code = "RSG";
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

	public function recalculate_win_loss_bet($start = 0,$game_provider_code = NULL,$game_type_code = NULL,$start_date = NULL,$end_date = NULL){
		$player_lists = array();
		$dbprefix = $this->db->dbprefix;
	    $game_provider_type_code = $game_provider_code."_".$game_type_code;
	    $limit = 5000;
	    $offset = $start *  $limit;
	    
		$trans_query = $this
			->db
			->select('player_id,,game_code,bet_amount,bet_amount_valid,win_loss')
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
			foreach($trans_query->result() as $trans_row){
				$player_lists[$trans_row->player_id][$trans_row->game_code]['payout_time'] = strtotime(date('Y-m-d 00:00:00',$start_date));
				$player_lists[$trans_row->player_id][$trans_row->game_code]['game_provider_code'] = $game_provider_code;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['game_type_code'] = $game_type_code;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['game_provider_type_code'] = $game_provider_type_code;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['game_code'] = $trans_row->game_code;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['player_id'] = $trans_row->player_id;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['total_bet'] += 1; 
				$player_lists[$trans_row->player_id][$trans_row->game_code]['bet_amount'] += $trans_row->bet_amount;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['bet_amount_valid'] += $trans_row->bet_amount_valid;
				$player_lists[$trans_row->player_id][$trans_row->game_code]['win_loss'] += $trans_row->win_loss;
			}
			if(sizeof($player_lists) > 0){
			    foreach($player_lists as $player_lists_row){
			    	if(!empty($player_lists_row) && sizeof($player_lists_row)>0){
			    		foreach($player_lists_row as $each_player_lists_row){
			    			$DBdata = array(
								'report_date' => $each_player_lists_row['payout_time'],
								'game_provider_code' => $each_player_lists_row['game_provider_code'],
								'game_type_code' => $each_player_lists_row['game_type_code'],
								'game_code' => $each_player_lists_row['game_code'],
								'game_provider_type_code' => $each_player_lists_row['game_provider_type_code'],
								'player_id' => $each_player_lists_row['player_id'],
								'total_bet' => $each_player_lists_row['total_bet'],
								'bet_amount' => $each_player_lists_row['bet_amount'],
								'bet_amount_valid' => $each_player_lists_row['bet_amount_valid'],
								'win_loss' => $each_player_lists_row['win_loss'],
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
		    return "0";
		}else{
		    echo "end<br/>";
		    return "1";
		}
	}

	public function replace_bl_game(){
		$this->db->select('transaction_id,bet_info,bet_update_info');
		$this->db->where('game_provider_code',"BL");
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		
		if(!empty($result)){
			foreach($result  as $row){
			    if(!empty($row['bet_update_info'])){
			        $result_row = json_decode($row['bet_update_info'],true);
			    }else{
			        $result_row = json_decode($row['bet_info'],true);   
			    }
			    
			    $PBdata = array(
			    	'game_provider_type_code' => "BL_BG",
			    	'game_code' => $result_row['game_code'],
			    	'game_real_code' => $result_row['game_code'],
			    );

			    
			    $this->db->where('transaction_id', $row['transaction_id']);
    		    $this->db->limit(1);
    		    $this->db->update('transaction_report', $PBdata);
			 }
		}
	}
	
	public function replace_sp_game(){
		$this->db->select('transaction_id,bet_info,bet_update_info');
		$this->db->where('game_provider_code',"SP");
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		
		if(!empty($result)){
			foreach($result  as $row){
			    if(!empty($row['bet_update_info'])){
			        $result_row = json_decode($row['bet_update_info'],true);
			    }else{
			        $result_row = json_decode($row['bet_info'],true);   
			    }
			    
			    $PBdata = array(
			    	'game_code' => $result_row['Detail'],
			    );

			    
			    $this->db->where('transaction_id', $row['transaction_id']);
    		    $this->db->limit(1);
    		    $this->db->update('transaction_report', $PBdata);
			 }
		}
	}
}