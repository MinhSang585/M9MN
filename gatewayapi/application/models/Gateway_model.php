<?php
class Gateway_model extends CI_Model {
	public function get_gateway_data($payment_gateway_code = NULL, $payment_gateway_type_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('api_data,bank_data')
				->where('payment_gateway_code', $payment_gateway_code)
				->where('payment_gateway_type_code', $payment_gateway_type_code)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('payment_gateway');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function insert_api_log($payment_gateway_code = NULL, $method = NULL, $output = NULL, $post_data = NULL, $response_data = NULL,$curl_data = NULL)
	{
		$DBdata = array(
			'payment_gateway_code' => (isset($payment_gateway_code) ? $payment_gateway_code : ''),
			'method' => (isset($method) ? $method : ''),
			'error_code' => (isset($output) ? $output['errorCode'] : ''),
			'error_msg' => (isset($output) ? $output['errorMessage'] : ''),
			'log_date' => time(),
			'input' => (($post_data) ? json_encode($post_data) : ''),
			'output' => (($response_data) ? json_encode($response_data) : ''),
			'curl' => (($curl_data) ? json_encode($curl_data) : ''),
		);
		
		$this->db->insert('payment_gateway_api_logs', $DBdata);
	}
}