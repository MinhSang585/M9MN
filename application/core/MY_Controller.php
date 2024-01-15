<?php
defined('BASEPATH') OR exit('No direct script access allowed.');
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->lang->load('general', $this->get_selected_language());
		$this->load->model(array('contact_model', 'game_model', 'player_model', 'seo_model'));
    }
    public function is_logged_in()
    {
		$result = 'logout/denied';
        if($this->session->userdata('is_logged_in') == TRUE)
		{
			$reponse = $this->player_model->verify_session();
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
		$lang = "english";//$this->config->item('language');
	    if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
	        if(substr($_SERVER['HTTP_HOST'], 4) == "338888.games"){
	            $lang = "malay";
	        }
	    }else{
	        if($_SERVER['HTTP_HOST'] == "338888.games"){
	            $lang = "malay";
	        }
	    }
	    
		if($this->session->userdata('lang'))
		{
			$lang = $this->session->userdata('lang');
		}else{
			$this->session->set_userdata('lang', $lang);
		}
		return $lang;
	}
	public function get_selected_language()
	{
		$lang = "english";//$this->config->item('language');
	    if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
	        if(substr($_SERVER['HTTP_HOST'], 4) == "338888.games"){
	            $lang = "malay";
	        }
	    }else{
	        if($_SERVER['HTTP_HOST'] == "338888.games"){
	            $lang = "malay";
	        }
	    }
	    
		if($this->session->userdata('lang'))
		{
			$lang = $this->session->userdata('lang');
		}else{
			$this->session->set_userdata('lang', $lang);
		}
		return $lang;
	}
	public function captcha_check($captcha = NULL)
	{
		$result = FALSE;
		if($this->session->userdata('captcha') == $captcha)
		{
			$result = TRUE;
		}
		return $result;
	}
	public function date_check($date = NULL)
	{
		$result = FALSE;
		if( ! empty($date))
		{
			$exp = '/^([0-9]{4})([\-])([0-9]{2})([\-])([0-9]{2})$/';
			if( ! empty($date) && $date != '0000-00-00')
			{
				$match = array();
				if(preg_match($exp, $date, $match))
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
	public function curl_get($url = NULL, $token = NULL)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,
			array(
				'charset=UTF-8',
				'Content-Type: application/x-www-form-urlencoded',
				$token
			)
		);
		$response = curl_exec($ch);
		$result['curl'] = array('error_no'=>curl_errno($ch),'error_desc'=>curl_error($ch));
		$info = curl_getinfo($ch);
		$result['http_code'] = $info['http_code'];
		if (curl_errno($ch))
		{
			if(curl_errno($ch) == 28){
				$result['code'] = '404';
				$result['msg'] = 'Failed';
				$result['data'] = '';
			}else{
				$result['code'] = '888';
				$result['msg'] = 'Failed';
				$result['data'] = '';
			}
		}
		else
		{
			$result['code'] = '0';
			$result['msg'] = 'Success';
			$result['data'] = $response;
		}
		curl_close($ch);
		return $result;
	}
}
