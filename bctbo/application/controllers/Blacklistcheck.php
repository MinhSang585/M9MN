<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blacklistcheck extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	public function check_name(){
		$create_time_from = 1672906717;
		$create_time_to = 1672906717;
		$type = BLACKLIST_BANK_NAME;
		$target_array = array();
		$player_array_ids = array();
		$player_array = array();
		$i = 0;

		$this->db->select('blacklist_value');
		$this->db->where('blacklist_type',$type);
		$this->db->where('created_date >= ',$create_time_from);
		$this->db->where('created_date <= ',$create_time_to);
		$query = $this->db->get('blacklists');

		if($query->num_rows() > 0) {
			foreach($query->result() as $row){
				$target_array[trim($row->blacklist_value)] = $i;
				$i++;
			}
		}
		$query->free_result();

		$target_array = array_flip($target_array);
    
        if(sizeof($target_array)>0){
            $this->db->select('player_id');
    		$this->db->where_in('bank_account_name',$target_array);
    		$query = $this->db->get('player_bank');
    		if($query->num_rows() > 0) {
    			foreach($query->result() as $row){
    				array_push($player_array_ids, $row->player_id);	
    			}
    		}
    		$query->free_result();
    
            if(sizeof($player_array_ids)>0){
                $this->db->select('username');
        		$this->db->where_in('player_id',$player_array_ids);
        		$query = $this->db->get('players');
        		if($query->num_rows() > 0) {
        			foreach($query->result() as $row){
        				array_push($player_array, $row->username);	
        			}
        		}
        		$query->free_result();
        		ad(implode(',',$player_array));       
            }
        }
	}

	public function check_mobile(){
		$create_time_from = 1672906717;
		$create_time_to = 1672906717;
		$type = BLACKLIST_PHONE_NUMBER;
		$target_array = array();
		$player_array_ids = array();
		$player_array = array();
		$i = 0;

		$this->db->select('blacklist_value');
		$this->db->where('blacklist_type',$type);
		$this->db->where('created_date >= ',$create_time_from);
		$this->db->where('created_date <= ',$create_time_to);
		$query = $this->db->get('blacklists');

		if($query->num_rows() > 0) {
			foreach($query->result() as $row){
				$target_array[trim($row->blacklist_value)] = $i;
				$i++;
			}
		}
		$query->free_result();

		$target_array = array_flip($target_array);
        if(sizeof($target_array)>0){
    		$this->db->select('username');
    		$this->db->where_in('mobile',$target_array);
    		$query = $this->db->get('players');
    		if($query->num_rows() > 0) {
    			foreach($query->result() as $row){
    				array_push($player_array, $row->username);
    			}
    		}
    		$query->free_result();
    		ad(implode(',',$player_array));
        }
	}

	public function check_bank(){
		$create_time_from = 1672906717;
		$create_time_to = 1672906717;
		$type = BLACKLIST_BANK_ACCOUNT;
		$target_array = array();
		$player_array_ids = array();
		$player_array = array();
		$i = 0;

		$this->db->select('blacklist_value');
		$this->db->where('blacklist_type',$type);
		$this->db->where('created_date >= ',$create_time_from);
		$this->db->where('created_date <= ',$create_time_to);
		$query = $this->db->get('blacklists');

		if($query->num_rows() > 0) {
			foreach($query->result() as $row){
				$target_array[trim($row->blacklist_value)] = $i;
				$i++;
			}
		}
		$query->free_result();

		$target_array = array_flip($target_array);
		if(sizeof($target_array)>0){
    		if(sizeof($target_array)>0){
    			foreach($target_array as $target_array_row){
    				$check_bank_value = mb_substr($target_array_row,-8,null, 'UTF-8');
    				$this->db->select('player_id');
    				$this->db->like('bank_account_no',$check_bank_value, 'before');
    				$query = $this->db->get('player_bank');
    				if($query->num_rows() > 0) {
    				    ad($check_bank_value);
    					foreach($query->result() as $row){
    						array_push($player_array_ids, $row->player_id);	
    					}
    				}		
    				$query->free_result();		
    			}
    		}
    
            if(sizeof($player_array_ids)>0){
        		$this->db->select('username');
        		$this->db->where_in('player_id',$player_array_ids);
        		$query = $this->db->get('players');
        		if($query->num_rows() > 0) {
        			foreach($query->result() as $row){
        				array_push($player_array, $row->username);	
        			}
        		}
        		$query->free_result();
        		ad(implode(',',$player_array));
            }
		}
	}
}