<?phpclass Game_model extends CI_Model {	public function get_game_list()	{			$result = NULL;				$query = $this				->db				->select('game_code, game_name, game_type_code')				->where('active', STATUS_ACTIVE)				->order_by('game_sequence', 'ASC')				->get('games');				if($query->num_rows() > 0)		{			$result = $query->result_array();			}				$query->free_result();				return $result;	}		public function get_game_list_by_category($game_type_code = NULL)	{			$result = NULL;				$query = $this				->db				->select('game_code')				->like('game_type_code', $game_type_code)				->where('active', STATUS_ACTIVE)				->order_by('game_sequence', 'ASC')				->get('games');			   		if($query->num_rows() > 0)		{			$result = $query->result_array(); 		}				$query->free_result();				return $result;	}		public function get_game_data($game_code = NULL)	{			$result = NULL;				$query = $this				->db				->select('game_code, game_name, game_type_code, is_maintenance, fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date')				->where('game_code', $game_code)				->where('active', STATUS_ACTIVE)				->limit(1)				->get('games');			   		if($query->num_rows() > 0)		{			$result = $query->row_array(); 		}				$query->free_result();				return $result;	}	public function get_game_maintenance_data($game_code = NULL)	{			$result = NULL;				$query = $this				->db				->select('game_code, game_name, game_type_code, is_maintenance, fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date')				->where('game_code', $game_code)				->get('game_maintenance');			   		if($query->num_rows() > 0)		{			$result = $query->result_array();  		}				$query->free_result();				return $result;	}		public function get_sub_game_list($data = NULL)	{			$result = NULL;				$query = $this				->db				->select('game_provider_code, game_type_code, game_code, game_name_en, 	game_name_chs, game_name_cht, game_picture_en, game_picture_chs, game_picture_cht, is_progressive, is_hot, is_new')				->where('game_provider_code', $data['provider_code'])				->where('game_type_code', $data['game_type_code'])				->where('active', STATUS_ACTIVE)				->where('is_mobile', STATUS_YES)				->order_by('game_sequence DESC, sub_game_id ASC')				->get('sub_games');				if($query->num_rows() > 0)		{			$result = $query->result_array();  		}		ad($this->db->last_query());		$query->free_result();				return $result;	}}