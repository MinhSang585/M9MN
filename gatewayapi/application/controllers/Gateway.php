<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gateway extends MY_Controller
{
    private $deposit_type;
    private $gateway;

	public function __construct()
    {
		parent::__construct();
		$this->load->model(array('deposit_model', 'general_model', 'miscellaneous_model','payment_gateway_model','player_model'));
	}
}
