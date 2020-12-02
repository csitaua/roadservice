<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once($_SERVER['DOCUMENT_ROOT']."/support/freepbx_class.php");

$t=new freepbx();

$tt=$t->fdb->query("SELECT * FROM asteriskcdrdb.cdr WHERE clid LIKE '%RoadService:%' and dst IN (191,602) ORDER BY calldate DESC LIMIT 1, 25");

 while ($row=$tt->fetch_array()){
	 echo $row['calldate'];
 }


?>