<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(array('user_model'));		
	}
		
	public function index() {		
	}
	
	/**
	 * Write the function that calculate ranking for player
	 */
	public function ranking_calculate() {
		
		//get upgrade type of level
		$sql = "SELECT upgrade_type FROM bctp_level LIMIT 1";
		$query = $this->db->query($sql);
		$upgradeTypeList = null;
		if($query->num_rows() > 0){
			$upgradeTypeList = $query->result_array();  
		}
		$query->free_result();
		
		$sql = "SELECT SUM(deposit_amount) as total_deposit, SUM(bet_amount_valid) as total_amount, player_id FROM bctp_total_win_loss_report
				WHERE player_id IS NOT NULL
				GROUP BY player_id";
		$query = $this->db->query($sql);
		
		$amountByPlayers = NULL;
		if($query->num_rows() > 0){
			$amountByPlayers = $query->result();  
		}	
		$query->free_result();

		$sql = "SELECT level_id, level_target_amount_from, level_reward_amount, level_deposit_amount_from FROM bctp_level ORDER BY level_target_amount_from ASC";
		$query = $this->db->query($sql);	
		$levelList = NULL;
		if($query->num_rows() > 0){
			$levelList = $query->result();  
		}	
		$query->free_result();
		$levelUpBonus = array();
		foreach($levelList as $levelItem){
			$levelUpBonus[$levelItem->level_id] = $levelItem->level_reward_amount;
		}

		$sql = "SELECT player_id, level_id, rewards FROM bctp_players";
		$query = $this->db->query($sql);
		$playerLists = NULL;
		if($query->num_rows() > 0){
			$playerLists = $query->result();  
		}	
		$query->free_result();
		$arrPlayerLevel = array();
		$arrPlayerReward = array();
		foreach($playerLists as $playerList){
			$arrPlayerLevel[$playerList->player_id] = $playerList->level_id;
			$arrPlayerReward[$playerList->player_id] = $playerList->rewards;
		}

		$sql = "SELECT SUM(amount) as total_deposit, player_id FROM bctp_deposits WHERE status = 1 GROUP BY player_id";
		$query = $this->db->query($sql);
	
		$depositByPlayers = NULL;
		if($query->num_rows() > 0){
			$depositByPlayers = $query->result();  
		}
		$arrDeposit = array();
		foreach($depositByPlayers as $depositItem){
			$arrDeposit[$depositItem->player_id] = $depositItem->total_deposit;
		}
		
		$sqlCombineUpdate = "";
		$sqlCombineInsert = "";
		$isUpdate = false;
		foreach($amountByPlayers as $amountByPlayer){
			$totalBet = $amountByPlayer->total_amount;
			$totalDeposit = isset($arrDeposit[$amountByPlayer->player_id]) ? $arrDeposit[$amountByPlayer->player_id] : 0;
			
			$levelId = 0;
			$i = 0;
			foreach($levelList as $level){
				if($upgradeTypeList[0]['upgrade_type'] == 1){ //Deposit
					if ($totalDeposit <= $level->level_deposit_amount_from) {
						$levelId = isset($levelList[$i-1]->level_id) ? $levelList[$i-1]->level_id : 0;
						break; 
					}
				} elseif ($upgradeTypeList[0]['upgrade_type'] == 2){ // Target
					if ($totalBet <= $level->level_target_amount_from) {
						$levelId = isset($levelList[$i-1]->level_id) ? $levelList[$i-1]->level_id : 0;
						break; 
					}
				} elseif ($upgradeTypeList[0]['upgrade_type'] == 3){ // Deposit & Target
					
					if ($totalDeposit <= $level->level_deposit_amount_from && $totalBet <= $level->level_target_amount_from) {
						$levelId = isset($levelList[$i-1]->level_id) ? $levelList[$i-1]->level_id : 0;
						
						break;
					}
				}
				
				$i++;
			}
			//sql update when case
			if($levelId > 0){
				if($arrPlayerLevel[$amountByPlayer->player_id] != $levelId){
					$sqlCombineUpdate .= " WHEN player_id = ".$amountByPlayer->player_id." THEN ".$levelId;

					$sqlCombineInsert .= "(".$amountByPlayer->player_id.",".$arrPlayerLevel[$playerList->player_id].",".$levelId.",".$amountByPlayer->total_deposit.",".$amountByPlayer->total_amount.",NOW(),".$levelUpBonus[$levelId]."),";
					$isUpdate = true;
				}
			}
		}

		if($isUpdate == true){
			$sql = "UPDATE bctp_players SET level_id = CASE ".$sqlCombineUpdate."
					ELSE level_id
					END
					WHERE player_id IS NOT NULL";
			$this->db->query($sql);

			$sqlCombineInsert = trim($sqlCombineInsert,",");
			// $sql = "INSERT INTO bctp_player_ranking_log (player_id, old_level_id, new_level_id, total_bet_amount_valid, created_date, level_up_bonus) VALUES ".$sqlCombineInsert;
			// $this->db->query($sql);
			
			$sql = "INSERT INTO bctp_player_request_award (player_id, old_level_id, new_level_id, total_deposit, total_bet_amount_valid, created_date, level_up_bonus) VALUES ".$sqlCombineInsert;
			$this->db->query($sql);
		}

		//calculate request award that is approved
		$sql = "SELECT id, player_id, level_up_bonus FROM bctp_player_request_award WHERE status = 1 and is_handle = 0";
		$query = $this->db->query($sql);
		$awardLists = NULL;
		if($query->num_rows() > 0){
			$awardLists = $query->result();  
		}
		$query->free_result();

		foreach($awardLists as $awardItem){
			$levelUpBonus = $awardItem->level_up_bonus;
			$sql = "UPDATE bctp_players SET awards = awards + " . $levelUpBonus . " WHERE player_id = " . $awardItem->player_id;
			$this->db->query($sql);
			
			$sql = "UPDATE bctp_player_request_award SET is_handle = 1 WHERE id = " . $awardItem->id;
			$this->db->query($sql);
		}
			
		echo "OK";	
		exit();
	}
}
