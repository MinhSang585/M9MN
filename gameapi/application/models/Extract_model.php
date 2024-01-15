<?php
class Extract_model extends CI_Model {
    
    public function update_player_last_reward($player_id = NULL, $last_reward_date = NULL){
        $DBdata = array(
			'last_reward_date' => $last_reward_date,
		);
		$this->db->where('player_id', $player_id);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
    }
    
    public function get_all_player_got_in_game(){
        $result = NULL;
        
        $this->db->select('player_id, last_login_date, last_in_game, login_token');
        $this->db->where('last_in_game != ',"");
        $query = $this->db->get('players');
        
        if($query->num_rows() > 0)
		{
		    foreach($query->result() as $row) {
				$result[$row->player_id] = (array) $row;						
			}
		}
		
		$query->free_result();
		
		return $result;
    }
    
    public function get_all_player_existing_token($login_token){
        $result = NULL;
        
        $this->db->select('id');
        $this->db->where_in('id',$login_token);
        $query = $this->db->get('sessions');
        
        if($query->num_rows() > 0)
		{
		    $result = $query->result_array();
		}
		
		$query->free_result();
		
		return $result;
    }
    
    public function get_player_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $id)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}