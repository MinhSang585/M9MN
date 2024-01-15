<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Des 
{ 
    var $key; 
    var $iv; 
	
    public function DES($key = null, $iv=0 )
	{ 
        $this->key = $key;
		
        if( $iv == 0 ) 
		{ 
            $this->iv = $key; 
        }
		else 
		{ 
            $this->iv = $iv; 
        } 
    } 
 
    public function encrypt($str = null)
	{ 
		return base64_encode(openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv)); 
    } 	
}

 
