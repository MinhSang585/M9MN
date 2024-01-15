<?php
class Fingerprint_model extends CI_Model {
	protected $table_fingerprint = 'fingerprint';

	public function get_fingerprint_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('fingerprint_id', $id)
				->limit(1)
				->get($this->table_fingerprint);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
}