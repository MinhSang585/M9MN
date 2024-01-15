<?php

class Payment_gateway_model extends CI_Model {

	public function get_payment_gateway_list_by_type($payment_gateway_type_code = NULL)

	{	

		$result = array();

		$query = $this

				->db

				->select('payment_gateway.payment_gateway_id, payment_gateway.payment_gateway_code, payment_gateway.payment_gateway_name, payment_gateway.payment_gateway_type_code, payment_gateway.payment_gateway_rate, payment_gateway.web_image_on, payment_gateway.web_image_off, payment_gateway.mobile_image_on, payment_gateway.mobile_image_off, payment_gateway.is_select_bank, payment_gateway.bank_data, payment_gateway.payment_gateway_currency_code, payment_gateway.payment_gateway_currency_id, currencies.t_currency_rate, currencies.d_currency_rate, currencies.w_currency_rate, currencies.t_fee, currencies.d_fee, currencies.w_fee, payment_gateway.payment_gateway_min_amount, payment_gateway.payment_gateway_max_amount, payment_gateway.active')

				->join('currencies','payment_gateway.payment_gateway_currency_id = currencies.currency_id')

				->where('payment_gateway.payment_gateway_type_code', $payment_gateway_type_code)

				->where('payment_gateway.active', STATUS_ACTIVE)

				->where('payment_gateway.is_maintenance', STATUS_NO)

				->order_by('payment_gateway.payment_gateway_sequence', 'ASC')

				->get('payment_gateway');

		

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();

			for($i=0;$i<sizeof($result);$i++){

				$result[$i]['payment_gateway_name_text'] = $this->lang->line($result[$i]['payment_gateway_name']);

			}

		}

		

		$query->free_result();

		

		return $result;

	}



	public function get_payment_gateway_data_by_type($payment_gateway_code = NULL, $payment_gateway_type_code = NULL)

	{	

		$result = NULL;

		$query = $this

				->db

				->select('payment_gateway_id, payment_gateway_code, payment_gateway_name, payment_gateway_type_code, is_maintenance, fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date, payment_gateway_admin_verification ,forward_url,api_data,payment_gateway_rate, is_select_bank, bank_data, payment_gateway_currency_code, payment_gateway_currency_id, payment_gateway_min_amount, payment_gateway_max_amount')

				->where('payment_gateway_code', $payment_gateway_code)

				->where('payment_gateway_type_code', $payment_gateway_type_code)

				->where('active', STATUS_ACTIVE)

				->where('is_maintenance', STATUS_NO)

				->limit(1)

				->get('payment_gateway');

		if($query->num_rows() > 0)

		{

			$result = $query->row_array(); 

		}

		

		$query->free_result();

		

		return $result;

	}



	public function get_payment_gateway_data($payment_gateway_id = NULL)

	{	

		$result = NULL;

		

		$query = $this

				->db

				->select('payment_gateway_id, payment_gateway_code, payment_gateway_name, payment_gateway_type_code, is_maintenance, fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date, payment_gateway_admin_verification ,forward_url,api_data,payment_gateway_rate, is_select_bank, bank_data, payment_gateway_currency_code, payment_gateway_currency_id')

				->where('payment_gateway_id', $payment_gateway_id)

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

}