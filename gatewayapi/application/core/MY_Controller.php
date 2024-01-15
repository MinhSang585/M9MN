<?php 
defined('BASEPATH') OR exit('No direct script access allowed.');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array('api_model', 'gateway_model','miscellaneous_model','withdrawal_model','deposit_model','player_model'));
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
	
	public function curl_get_json($url = NULL, $token = NULL) 
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/json',
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
	
	public function curl_put_json($url = NULL, $arr = NULL, $token = NULL) 
	{
		$data_string = json_encode($arr);
		
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string),
				'DataType: JSON',
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

	public function curl_post($url = NULL, $arr = NULL, $token = NULL,$encoding = NULL) 
	{
		$data_string = ((is_array($arr)) ? http_build_query($arr) : $arr);  
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   

		if(is_array($arr) == FALSE)
		{
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, 
				array(
					$token
				)
			);
		}
		else
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, 
				array(
					'charset=UTF-8',
					'Content-Type: application/x-www-form-urlencoded',
					'Content-Length: ' . strlen($data_string),
					$token
				)
			);
		}
		if($encoding != NULL){
			curl_setopt($ch, CURLOPT_ENCODING,$encoding);
		}
		
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
	
	public function curl_post_for_pt($url = NULL, $entity_key = NULL) 
	{
		$header   = array();
		$header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive:timeout=5, max=100";
		$header[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3";
		$header[] = "Accept-Language:es-ES,es;q=0.8";
		$header[] = "Pragma: ";
		$header[] = "X_ENTITY_KEY: " . $entity_key;

		$ch= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_PORT , 443);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLCERT, FCPATH . 'assets/certs/pt/IDR.pem');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSLKEY, FCPATH . 'assets/certs/pt/IDR.key');
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
	
	public function curl_post_for_pt2($url = NULL, $arr = NULL, $entity_key = NULL) 
	{
		$curl = curl_init(); 
		curl_setopt_array($curl, array( 
    		CURLOPT_URL => $url, 
    		CURLOPT_RETURNTRANSFER => true, 
    		CURLOPT_ENCODING => "", 
    		CURLOPT_MAXREDIRS => 10, 
    		CURLOPT_TIMEOUT => 0, 
    		CURLOPT_FOLLOWLOCATION => true, 
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
    		CURLOPT_CUSTOMREQUEST => "POST", 
    		CURLOPT_POSTFIELDS => $arr, 
    		CURLOPT_HTTPHEADER => array( "X_ENTITY_KEY: $entity_key" ), 
    		CURLOPT_SSLKEY => FCPATH . 'assets/certs/pt/CNY.key', 
    		CURLOPT_SSLCERT => FCPATH . 'assets/certs/pt/CNY.pem')
    	);
		$response = curl_exec($curl);
        $result['curl'] = array('error_no'=>curl_errno($ch),'error_desc'=>curl_error($ch));
        $info = curl_getinfo($curl);
		$result['http_code'] = $info['http_code'];
		if (curl_errno($curl)) 
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
		
		curl_close($curl);
		
		return $result;
	}
	
	public function curl_post_for_sa($url = NULL, $arr = NULL) 
	{
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'q=' . $arr['q'] . '&s=' . $arr['s']);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/x-www-form-urlencoded'
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
	
	public function curl_json($url = NULL, $arr = NULL, $token = NULL) 
	{
		$data_string = json_encode($arr);
		
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string),
				'DataType: JSON',
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
	
	public function curl_xml($url = NULL, $data_string = NULL, $token = NULL) 
	{
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/xml',
				'Content-Length: ' . strlen($data_string),
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
	
	public function curl_post_xe($url = NULL, $arr = NULL, $token = NULL) 
	{
		$data_string = ((is_array($arr)) ? json_encode($arr,true) : $arr);
		$headers = [
        	$token
        ];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
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
	
	public function curl_xml_n2($url = NULL, $data_string = NULL, $token = NULL) 
	{
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                      
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/xml',
				'Content-Length: ' . strlen($data_string),
				$token
			)
		);
		curl_setopt($ch, CURLOPT_ENCODING,'gzip,deflate');
		$response = curl_exec($ch);
		$result['curl'] = array('error_no'=>curl_errno($ch),'error_desc'=>curl_error($ch));
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
