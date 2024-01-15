<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
	
	public function index()
	{
		redirect('home');
	}
		
	public function change($selection = SYSTEM_LANG_EN)
	{
		$lang = get_language_name($selection);
		
		$this->session->set_userdata('lang', $lang);
		
		$url = $this->get_previous_url();
		
		redirect($url);
	}
}
