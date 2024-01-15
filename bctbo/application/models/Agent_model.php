<?php

class Agent_model extends CI_Model {



	//Declare database tables

	protected $table_agents = 'users_agents';


	public function getAgentData($id = NULL)
	{	

		$result = NULL;

		$query = $this

				->db

				->where('user_id', $id)

				->limit(1)

				->get($this->table_agents);	

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}

		$query->free_result();
		return $result;
	}
	public function get_agent_data($id = NULL)
	{	

		$result = NULL;

		$query = $this

				->db

				->where('user_id', $id)

				->limit(1)

				->get($this->table_agents);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
}
