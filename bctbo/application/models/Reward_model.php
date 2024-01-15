<?php
class Reward_model extends CI_Model {
	protected $table_player_reward = 'player_reward';

	public function get_reward_data($id = NULL)
	{	
		$result = NULL;
		$query = $this
				->db
				->where('player_reward_id', $id)
				->limit(1)
				->get($this->table_player_reward);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_reward($arr = NULL)
	{	
		$DBdata = array(
			'status' => (($this->input->post('status', TRUE) == STATUS_APPROVE) ? STATUS_APPROVE : STATUS_CANCEL),
			'remark' => $this->input->post('remark', TRUE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('player_reward_id', $arr['player_reward_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update($this->table_player_reward, $DBdata);
		
		$DBdata['player_reward_id'] = $arr['player_reward_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $arr['reward_amount'];
		$DBdata['rewards'] = $arr['reward_amount'];
		return $DBdata;
	}
}