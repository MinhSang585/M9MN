<?php 
defined('BASEPATH') OR exit('No direct script access allowed.');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->lang->load(array('general','bet','telegram','gamelist'), $this->get_language());
		$this->load->model(array('account_model', 'general_model', 'user_model'));
    }

    public function is_logged_in()
    {
		$result = 'logout/denied';
		
        if($this->session->userdata('is_logged_in') == TRUE)
		{
			$reponse = FALSE;
			if($this->session->userdata('user_group') == USER_GROUP_USER)
			{
				$reponse = $this->user_model->verify_session();
			}
			else
			{
				$reponse = $this->account_model->verify_session();
			}
			
			if($reponse == TRUE) 
			{
				$result = '';
			}
			else
			{
				$result = 'logout/force';
			}
		}
		
        return $result;
    }
	
	public function save_current_url($url = NULL)
	{
		if($url == NULL) 
		{
			$url = site_url();
		}
		
		$this->session->set_userdata('referrer_url', $url);
	}
	
	public function get_previous_url()
	{
		if($this->session->userdata('referrer_url')) 
		{
			$url = $this->session->userdata('referrer_url');
		}
		else 
		{
			$url = site_url();
		}
		
		return $url;
	}
	
	public function get_language()
	{
		$lang = $this->config->item('language');
		
		if($this->session->userdata('lang')) 
		{
			$lang = $this->session->userdata('lang');
		}
		else{
			#$this->session->set_userdata('lang', 'chinese_traditional');
			#$lang = 'chinese_traditional';
			$this->session->set_userdata('lang', 'english');
			$lang = 'english';
		}
		
		return $lang;
	}
	
	public function cal_days_in_year($year)
	{
		$days = 0; 
		
		for($month=1;$month<=12;$month++)
		{ 
			$days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$year);
		}
		
		return $days;
	}
	
	public function time_check($time = NULL) 
	{
		$result = FALSE;
		
		if( ! empty($time))
		{
			$arr = explode(':', $time);
			if(sizeof($arr) == 2) 
			{
				if(($arr[0] >= 0 && $arr[0] <= 23) && ($arr[1] >= 0 && $arr[1] <= 59))
				{
					$result = TRUE;
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		
		return $result;
	}

	public function full_time_check($time = NULL) 
	{
		$result = FALSE;
		
		if( ! empty($time))
		{
			$arr = explode(':', $time);
			if(sizeof($arr) == 3) 
			{
				if(($arr[0] >= 0 && $arr[0] <= 23) && ($arr[1] >= 0 && $arr[1] <= 59) && ($arr[2] >= 0 && $arr[2] <= 59))
				{
					$result = TRUE;
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		
		return $result;
	}

	public function year_check($datetime = NULL){
		$result = FALSE;
		if( ! empty($datetime))
		{
			$exp = '/^([0-9]{4})$/';
			if( ! empty($datetime) && $datetime != '0000') 
			{
				$match = array();
				if(preg_match($exp, $datetime, $match)) 
				{
					if(checkdate("01", "01", $match[1])) 
					{
						$result = TRUE;
					}
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		return $result;
	}

	public function month_check($datetime = NULL) 
	{
		$result = FALSE;
		if( ! empty($datetime))
		{
			$exp = '/^([0-9]{4})([\-])([0-9]{2})([\-])([0-9]{2})$/';
			if( ! empty($datetime) && $datetime != '0000-00') 
			{
				$datetime = $datetime."-01";
				$match = array();
				if(preg_match($exp, $datetime, $match)) 
				{
					if(checkdate($match[3], $match[5], $match[1])) 
					{
						$result = TRUE;
					}
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		return $result;
	}

	public function date_check($datetime = NULL) 
	{
		$result = FALSE;
		if( ! empty($datetime))
		{
			$exp = '/^([0-9]{4})([\-])([0-9]{2})([\-])([0-9]{2})$/';
			if( ! empty($datetime) && $datetime != '0000-00-00') 
			{
				$match = array();
				if(preg_match($exp, $datetime, $match)) 
				{
					if(checkdate($match[3], $match[5], $match[1])) 
					{
						$result = TRUE;
					}
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		return $result;
	}
	
	public function datetime_check($datetime = NULL) 
	{
		$result = FALSE;
		
		if( ! empty($datetime))
		{
			$exp = '/^([0-9]{4})([\-])([0-9]{2})([\-])([0-9]{2})[\ ]([0-9]{2})[\:]([0-9]{2})$/';
			if( ! empty($datetime) && $datetime != '0000-00-00 00:00') 
			{
				$match = array();
				if(preg_match($exp, $datetime, $match)) 
				{
					if(checkdate($match[3], $match[5], $match[1])) 
					{
						if(($match[6] >= 0 && $match[6] <= 23) && ($match[7] >= 0 && $match[7] <= 59))
						{
							$result = TRUE;
						}
					}
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		
		return $result;
	}
	
	public function full_datetime_check($datetime = NULL) 
	{
		$result = FALSE;
		
		if( ! empty($datetime))
		{
			$exp = '/^([0-9]{4})([\-])([0-9]{2})([\-])([0-9]{2})[\ ]([0-9]{2})[\:]([0-9]{2})[\:]([0-9]{2})$/';
			if( ! empty($datetime) && $datetime != '0000-00-00 00:00:00') 
			{
				$match = array();
				if(preg_match($exp, $datetime, $match)) 
				{
					if(checkdate($match[3], $match[5], $match[1])) 
					{
						if(($match[6] >= 0 && $match[6] <= 23) && ($match[7] >= 0 && $match[7] <= 59) && ($match[8] >= 0 && $match[8] <= 59))
						{
							$result = TRUE;
						}
					}
				}
			}
		}
		else
		{
			$result = TRUE;
		}
		
		return $result;
	}
	
	public function language_check($id = NULL) 
	{
		$arr = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
		if(in_array($id, $arr))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function group_type_check($id = NULL) 
	{
		$result = FALSE;
		
		switch($id)
		{
			case GROUP_BANK: $result = TRUE; break;
			case GROUP_PLAYER: $result = TRUE; break;
		}
		
		return $result;
	}
	
	public function multiple_select_check($post = NULL) 
	{
		$result = FALSE;
		
		if( ! empty($post))
		{
			return TRUE;
		}
		
		return $result;
	}
	
	public function curl_json($url = NULL, $arr = NULL) 
	{
		$data_string = json_encode($arr);  
		
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);   
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);   	
		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
}
