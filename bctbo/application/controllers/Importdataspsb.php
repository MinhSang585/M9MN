<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Importdataspsb extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function sa(){
		$index = "SPSB";
		$this->load->library('excel');
		$directory_path = './../uploads/excel/spsb/';
        $filname = "sa_balance.xlsx";
        $filepath = $directory_path.$filname;
        $Bdata = array();
    	$player_game_lists = array();
    	$player_lists = array();
    	$temp = array();
        if(file_exists($filepath)){
        	$this->db->select('player_id,username,points,old_points');
        	$query = $this->db->get('players');
        	if($query->num_rows() > 0)
			{
				foreach($query->result() as $row) {
					$player_lists[$row->player_id] = (array) $row;
				}
			}

			$this->db->select('game_id,player_id');
            $this->db->where('game_provider_code',"SPSB");
            $query = $this->db->get('player_game_accounts');
            if($query->num_rows() > 0)
			{
				foreach($query->result() as $row) {
					$player_game_lists[strtolower($row->game_id)] = $row->player_id;
				}
			}


        	$inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
            $sheetInsertData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $player_temp_name  = "";
            $amount = 0;
            $i = 0;
            $j = 0;
			foreach($sheetInsertData as $row){
				if($row['A']){
					$amount = bcdiv(str_replace(",","",$row['G']),1,2);
					if($amount > 0){
						if(isset($player_game_lists[strtolower($row['B'])])){
							if(isset($player_lists[$player_game_lists[strtolower($row['B'])]])){
								$player_username = $player_lists[$player_game_lists[strtolower($row['B'])]]['username'];
								$player_id = $player_lists[$player_game_lists[strtolower($row['B'])]]['player_id'];
							
								$temp = array(
									'from_wallet' => $index,
									'to_wallet' => "MAIN",
									'deposit_amount' => $amount,
									'withdrawal_amount' => $amount,
									'from_balance_before' => $amount,
									'from_balance_after' => 0,
									'to_balance_before' => $player_lists[$player_game_lists[strtolower($row['B'])]]['points'],
									'to_balance_after' => $player_lists[$player_game_lists[strtolower($row['B'])]]['points'] + $amount,
									'player_id' => $player_id,
									'report_date'  => time(),
								);
								array_push($Bdata, $temp);
								$player_lists[$player_game_lists[strtolower($row['B'])]]['points'] += $amount;
								$player_lists[$player_game_lists[strtolower($row['B'])]]['old_points'] += $amount;
								
								$this->db->where('player_id',$player_lists[$player_game_lists[strtolower($row['B'])]]['player_id']);
								$this->db->query("UPDATE  bctp_players SET points = (points + ?) WHERE player_id = ? LIMIT 1", array($amount, $player_id));
								
							}else{
								ad("Player Account No Found");
								ad($row);
							}
						}else{
							ad("Game Account No Found");
							ad($row);
						}
					}
				}
			}
			
			if(!empty($Bdata)){
    			//$this->db->insert_batch('game_transfer_report',$Bdata);
    		}
        }
	}
}