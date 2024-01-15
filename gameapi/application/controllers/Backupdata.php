<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backupdata extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function backup_data(){
	    $min = 2100000;
	    $max = 2200000;
	    
	    $max_date = "2022-12-01 00:00:00";
	    $max_time = strtotime($max_date);
	    
	    $Bdata = array();
	    $this->db->where('transaction_id >',$min);
	    $this->db->where('transaction_id <= ',$max);
	    $this->db->where('bet_time < ',$max_time);
	    $query = $this->db->get('transaction_report');
	    if($query->num_rows() > 0)
		{
			foreach($query->result() as $row){
		        array_push($Bdata, (array)$row);
		        
		        $this->db->where('transaction_id',$row->transaction_id);
                $this->db->limit(1);
                $this->db->delete('transaction_report');
			}
		}
		if( ! empty($Bdata))
		{
		    echo "got record";
			$this->db->insert_batch('transaction_report_bk_batch', $Bdata);
		}
	}
	
	public function testing_delete_game_result_data(){
	    $date = "2023-03-01 00:00:00";
	    $date_time = strtotime($date);
	    $this->db->select('game_result_log_id');
	    $this->db->where('sync_time < ',$date_time);
	    $this->db->limit(1000000);
	    $query2 = $this->db->get('game_result_logs');
	    if($query2->num_rows() > 0)
	    {      
	       foreach($query2->result() as $row_test){
                $this->db->where('game_result_log_id',$row_test->game_result_log_id);
                $this->db->limit(1);
                $this->db->delete('game_result_logs');
	            echo "1";
	       }
	       ad("done");
	    }
	}
}