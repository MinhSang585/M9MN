<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Des_ecb
{
     public static function pkcs5_pad($text, $blocksize) 
    {
	    $pad = $blocksize - (strlen($text) % $blocksize);
	    return $text . str_repeat(chr($pad), $pad);
	}

	public static function pkcs5_unpad($text) 
	{
	    $pad = ord($text{strlen($text)-1});
	    if ($pad > strlen($text)) return false;
	    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
	    return substr($text, 0, -1 * $pad);
	}
    
	//php7 or above need to use this method to encrypt
	public static function encrypt_text($string, $key)
    {
        $data = openssl_encrypt($string, 'DES-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING,base64_decode("AAAAAAAAAAA="));
        $data = base64_encode($data);
        return $data;
    }
}
