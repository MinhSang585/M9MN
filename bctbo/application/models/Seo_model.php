<?phpclass Seo_model extends CI_Model {	protected $table = 'seo';		public function get_seo_data($id = NULL)	{			$result = NULL;				$query = $this				->db				->where('seo_key_id', $id)				->limit(1)				->get($this->table);				if($query->num_rows() > 0)		{			$result = $query->row_array();  		}				$query->free_result();				return $result;	}		public function add_seo($id = NULL)	{			$domain = $this->input->post('domain', TRUE);		$domain_array = array_filter(explode('#',strtolower($domain)));		$DBdata = array(			'seo_id' => $this->input->post('seo_id', TRUE),			'page_title' => $this->input->post('page_title', TRUE),			'meta_keywords' => $this->input->post('meta_keywords', TRUE),			'meta_descriptions' => $this->input->post('meta_descriptions', TRUE),			'meta_header' => html_entity_decode($_POST['meta_header']),			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_SUSPEND),			'updated_by' => $this->session->userdata('username'),			'updated_date' => time()		);				$this->db->insert($this->table, $DBdata);		$DBdata['seo_key_id'] = $this->db->insert_id();		return $DBdata;	}	public function update_seo($id = NULL)	{			$domain = $this->input->post('domain', TRUE);		$domain_array = array_filter(explode('#',strtolower($domain)));		$DBdata = array(			'seo_id' => $this->input->post('seo_id', TRUE),			'page_title' => $this->input->post('page_title', TRUE),			'meta_keywords' => $this->input->post('meta_keywords', TRUE),			'meta_descriptions' => $this->input->post('meta_descriptions', TRUE),			'meta_header' => html_entity_decode($_POST['meta_header']),			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_SUSPEND),			'updated_by' => $this->session->userdata('username'),			'updated_date' => time()		);				$this->db->where('seo_key_id', $id);		$this->db->limit(1);		$this->db->update($this->table, $DBdata);				$DBdata['seo_key_id'] = $id;				return $DBdata;	}	public function get_seo_list()	{			$result = NULL;				$query = $this				->db				->select('seo_id, page_name')				->where('active', STATUS_ACTIVE)				->get($this->table);			   		if($query->num_rows() > 0)		{			$result = $query->result_array(); 		}				$query->free_result();				return $result;	}	public function delete_seo($id = NULL){		$this->db->where('seo_key_id', $id);		$this->db->delete($this->table);	}}