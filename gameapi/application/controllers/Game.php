<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function jk($encrypt_data = NULL) 
	{
		$str = base64_decode($encrypt_data);
		$arr = explode('|', $str);
		
		if(isset($arr[0]) && sizeof($arr) == 5)
		{
			$game_data = $this->game_model->get_game_data($arr[0]);
			if( ! empty($game_data))
			{
				$json_arr = json_decode($game_data['api_data'], TRUE);
				
				$data = array(
					'url' => $json_arr['ForwardUrl'],
					'token' => $arr[1],
					'game_code' => $arr[2],
					'mobile' => $arr[3],
					'redirect_url' => $arr[4]
				);
				
				$this->load->view('jk_game_view', $data);
			}
			else
			{
				echo show_404();
			}
		}	
		else
		{
			echo show_404();
		}	
	}
	
	public function pt($encrypt_data = NULL) 
	{
		$str = base64_decode($encrypt_data);
		$arr = explode('|', $str);
		
		if(isset($arr[0]) && sizeof($arr) == 8)
		{
			$game_data = $this->game_model->get_game_data($arr[0]);
			if( ! empty($game_data))
			{
				$json_arr = json_decode($game_data['api_data'], TRUE);
				
				$data = array(
					'virtual_database' => $json_arr['VirtualDatabase'],
					'mobile_hub' => $json_arr['MobileHub'],
					'system_id' => $json_arr['SystemID'],
					'username' => $arr[1],
					'password' => $arr[2],
					'game_code' => $arr[3],
					'mobile' => $arr[4],
					'play_fun' => $arr[5],
					'language' => $arr[6],
					'redirect_url' => $arr[7]
				);
				
				$this->load->view('pt_game_view', $data);
			}
			else
			{
				echo show_404();
			}
		}	
		else
		{
			echo show_404();
		}	
	}
	
	public function pt2($encrypt_data = NULL) 
	{
		$str = base64_decode($encrypt_data);
		$arr = explode('|', $str);
		
		if(isset($arr[0]) && sizeof($arr) == 8)
		{
			$game_data = $this->game_model->get_game_data($arr[0]);
			if( ! empty($game_data))
			{
				$json_arr = json_decode($game_data['api_data'], TRUE);
				
				$data = array(
					'virtual_database' => $json_arr['VirtualDatabase'],
					'mobile_hub' => $json_arr['MobileHub'],
					'username' => $arr[1],
					'password' => $arr[2],
					'game' => $arr[3],
					'lang' => $arr[4],
					'client' => $arr[5],
					'mode' => $arr[6],
					'lobby' => $arr[7]
				);
				
				$this->load->view('pt2_game_view', $data);
			}
			else
			{
				echo show_404();
			}
		}	
		else
		{
			echo show_404();
		}	
	}
	
	public function splt($encrypt_data = NULL) 
	{
		$str = base64_decode($encrypt_data);
		$arr = explode('|', $str);
		
		if(isset($arr[0]) && sizeof($arr) == 3)
		{
			$game_data = $this->game_model->get_game_data($arr[0]);
			if( ! empty($game_data))
			{
				$json_arr = json_decode($game_data['api_data'], TRUE);
				
				$data = array(
					'url' => $arr[1],
					'data' => $arr[2],
				);
				$this->load->view('splt_game_view', $data);
			}
			else
			{
				echo show_404();
			}
		}	
		else
		{
			echo show_404();
		}	
	}
}