<?php
class Api_model extends CI_Model {

	public function get_api_data($agent_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('agent_id, secret_key, fe_whitelist_ip')
				->where('agent_id', $agent_id)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('api');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function insert_api_log($data = NULL)
	{
		$DBdata = array(
			'ip_address' => $this->input->ip_address(),
			'log_date' => time(),
			'method' => (isset($data['method']) ? $data['method'] : ''),
			'agent_id' => (isset($data['agentId']) ? $data['agentId'] : ''),
			'post_data' => (($data) ? json_encode($data) : '')
		);
		
		if($this->agent->is_mobile()) 
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
		}
		else 
		{
			$DBdata['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
		}
		
		$this->db->insert('api_logs', $DBdata);
	}
}