<?php

class Game_model extends CI_Model {



	public function get_game_data($game_code = NULL)

	{	

		$result = NULL;

		

		$query = $this

				->db

				->select('api_data')

				->where('game_code', $game_code)

				->where('active', STATUS_ACTIVE)

				->limit(1)

				->get('games');

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}



	public function insert_api_log($provider_code = NULL, $method = NULL, $output = NULL, $post_data = NULL, $response_data = NULL,$curl_data = NULL)

	{

		$DBdata = array(

			'game_code' => (isset($provider_code) ? $provider_code : ''),

			'method' => (isset($method) ? $method : ''),

			'error_code' => (isset($output) ? $output['errorCode'] : ''),

			'error_msg' => (isset($output) ? $output['errorMessage'] : ''),

			'log_date' => time(),

			'input' => (($post_data) ? json_encode($post_data) : ''),

			'output' => (($response_data) ? json_encode($response_data) : ''),

			'curl' => (($curl_data) ? json_encode($curl_data) : ''),

		);

		

		$this->db->insert('game_api_logs', $DBdata);

	}

	

	public function get_sub_game_list($game_provider_code = NULL, $game_type_code = NULL)

	{	

		$result = NULL;

		

		$query = $this

				->db

				->select('game_code, game_name_en, 	game_name_chs, game_name_cht, game_picture_en, game_picture_chs, game_picture_cht, url, is_open_game, is_progressive, is_hot, is_new')

				->where('game_provider_code', $game_provider_code)

				->where('game_type_code', $game_type_code)

				->where('active', STATUS_ACTIVE)

				->where('is_mobile', STATUS_YES)

				->order_by('game_sequence DESC, sub_game_id ASC')

				->get('sub_games');

		

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();  

		}

		

		$query->free_result();

		

		return $result;

	}

	

	public function get_game_result_logs($game_provider_code = NULL)

	{	

		$result = NULL;

		

		$query = $this

				->db

				->select('end_time,next_id')

				->where('game_provider_code', $game_provider_code)

				->where('sync_status', STATUS_YES)

				->order_by('game_result_log_id', 'DESC')

				->limit(1)

				->get('game_result_logs');

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}

	

	public function get_game_result_logs_by_category_id($game_provider_code = NULL,$category_id = NULL){

	    $result = NULL;

		

		$query = $this

				->db

				->select('end_time,next_id')

				->where('game_provider_code', $game_provider_code)

				->where('category_id',$category_id)

				->where('sync_status', STATUS_YES)

				->order_by('game_result_log_id', 'DESC')

				->limit(1)

				->get('game_result_logs');

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}

	public function getSubGameByProvider($game_provider_code = NULL){
		$result = NULL;

		$query = $this
				->db
				->select('game_code, game_name_en, game_type_code')
				->where('game_provider_code', $game_provider_code)
				->where('active', STATUS_ACTIVE)
				->order_by('sub_game_id ASC')
				->get('sub_games');

		if($query->num_rows() > 0){
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['game_code']] = $row;
			}
		}

		$query->free_result();
		return $result;
	}
}