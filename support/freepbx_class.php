<?php

require_once($_SERVER['DOCUMENT_ROOT']."/support/encryption_class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/support/db-info.inc");

class freepbx{
	
private $db;
private $fdb;

    public function __construct() {
        $this->db   = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$rs=$this->db->query("SELECT * FROM settings");
		$row=$rs->fetch_assoc();
		$t=new cm_encryption();
		$t->my_decrypt($row['freepbx_password_enc']);
		$this->fdb = new mysqli($row['freepbx_host'].':'.$row['freepbx_port'],$row['freepbx_username'],$t->$dc_data,$row['freepbx_database']);
    }
	
}

?>