<?phpclass Seo_model extends CI_Model {	public function get_seo_data($id = NULL)	{			$result = NULL;				$query = $this				->db				->select('seo_id, page_name, page_title, meta_keywords, meta_descriptions')				->where('seo_id', $id)				->limit(1)				->get('seo');				if($query->num_rows() > 0)		{			$result = $query->row_array();  		}				$query->free_result();				return $result;	}}