<?php
class Transaction_model extends CI_Model {

	//Declare database tables
	protected $table_game_transfer_pending = 'game_transfer_pending';

	public function get_wallet_transaction_pending_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('game_transfer_pending_id', $id)
				->limit(1)
				->get($this->table_game_transfer_pending);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_wallet_transaction_pending($arr = NULL){
		$DBdata = array(
			'status' => (($this->input->post('status', TRUE) == STATUS_APPROVE) ? STATUS_APPROVE : STATUS_CANCEL),
			'remark' => $this->input->post('remark', TRUE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('game_transfer_pending_id', $arr['game_transfer_pending_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update($this->table_game_transfer_pending, $DBdata);
		
		$DBdata['game_transfer_pending_id'] = $arr['game_transfer_pending_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['amount'] = $arr['deposit_amount'];
		return $DBdata;
	}
}