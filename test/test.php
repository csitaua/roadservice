<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$ini = parse_ini_file('../../htmldev-ini/app.ini');
echo $ini['S3_Bucket'];
 ?>
