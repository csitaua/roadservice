<?php

class cm_encryption{
	private static $encryption_key_256bit = 'vua4JpuLGgJ6tLvQ3tyDaL5dXWSUXceU';
	var  $en_data="";
	var  $dc_data="";
	
	function my_decrypt($data){
		$encryption_key = base64_decode(cm_encryption::$encryption_key_256bit);
		list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
		$this->dc_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', cm_encryption::$encryption_key_256bit, 0, $iv);
	}
	
	function my_encrypt($data) {
		$encryption_key = cm_encryption::$encryption_key_256bit;
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
		// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
		$this->en_data = base64_encode($encrypted . '::' . $iv);
	}
}

?>