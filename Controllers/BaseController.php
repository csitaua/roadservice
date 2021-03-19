<?php

namespace Controllers;

class Base{

  private $ini;

  public function __construct(){
    $this->ini = parse_ini_file('../htmldev-ini/app.ini');
	}

  public function encrypt($key){
    return openssl_encrypt($key, $this->ini['Encryption_Ciphering'],$this->ini['Encryption_Key']);
  }

  public function decrypt($key){
    return openssl_decrypt($key, $this->ini['Encryption_Ciphering'],$this->ini['Encryption_Key']);
  }


}
?>
