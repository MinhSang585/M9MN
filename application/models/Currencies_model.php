<?php

class Currencies_model extends CI_Model
{
	protected $table_currencies = 'currencies';

	public function get_currencies_data($id = NULL){

		$result = NULL;

		

		$query = $this

				->db

				->where('currency_id', $id)

				->limit(1)

				->get($this->table_currencies);

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}

}
