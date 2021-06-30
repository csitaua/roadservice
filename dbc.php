<?php
/*************** PHP LOGIN SCRIPT V 2.3*********************
(c) Balakrishnan 2010. All Rights Reserved
Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!
Limitations:\n- This script cannot be sold.-
This script should have copyright notice intact.
Dont remove it please...\n- This script may not be provided for download except from its original site.\nFor further usage, please contact me.\n/******************** MAIN SETTINGS - PHP LOGIN SCRIPT V2.1 **********************\nPlease complete wherever marked xxxxxxxxx\n/************* MYSQL DATABASE SETTINGS *****************\n1. Specify Database name in $dbname\n2. MySQL host (localhost or remotehost)\n3. MySQL user name with ALL previleges assigned.\n4. MySQL password[\n]Note: If you use cpanel, the name will be like account_database*************************************************************/
session_start();
include dirname(__FILE__) . "/support/config.php";
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['SCRIPT_FILENAME'])){
$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['PATH_TRANSLATED'])){
$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };
require 'Controllers/Database.php';
//}
//$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Couldn't make connection.1");
//$db1= mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Couldn't make connection.");
//$db = mysqli_select_db(DB_NAME, $link) or die("Couldn't select database2".DB_NAME.' z'.mysqli_error($link));
/*
$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$connectionInfo = array( "Database"=> DB_NAME2 , "UID"=>USER_NAME2, "PWD"=>PASSWORD2,'ReturnDatesAsStrings'=>true);
$conn = sqlsrv_connect( HOST2, $connectionInfo);
if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}*/
//$connt = mssql_connect( HOST2, USER_NAME2 ,PASSWORD2);
//$conn=mssql_select_db(DB_NAME2,$connt);
//$inspro=new PDO("sqlsrv:Server=".$serverName.";Database=insproSQL", "exportsa", "nvsql2304@@");
//$mysqli = new mysqli("localhost", DB_USER, DB_PASS, DB_NAME);
//if ($mysqli->connect_errno) {
  // echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
//}
/* Registration Type (Automatic or Manual)
 1 -> Automatic Registration (Users will receive activation code and they will be automatically approved after clicking activation link)
 0 -> Manual Approval (Users will not receive activation code and you will need to approve every user manually)
*/
$user_registration = 1;  // set 0 or 1
define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password
define('PASSWORD_MIN_LENGTH',8); // min length for password
//define ("ADMIN_NAME", "admin"); // sp
/* Specify user levels */
define ("ADMIN_LEVEL", 5);
define ("POWER_LEVEL",4);
define ("RR_LEVEL",3);
define ("VIEW_LEVEL",2);
define ("EXTERNAL_LEVEL", 1);

/*************** reCAPTCHA KEYS****************/
$publickey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$privatekey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/
function page_protect() {
session_start();
global $db;
/* Secure against Session Hijacking by checking user agent */
if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        logout();
        exit;
    }
}
// before we allow sessions, we need to check authentication key - ckey and ctime stored in database
/* If session not set, check for cookies set by Remember me */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) )
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	/* we double check cookie expiry time against stored in database */

	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = mysqli_query("select `ckey`,`ctime` from `users` where `id` ='$cookie_user_id'") or die(mysqli_error());
	list($ckey,$ctime) = mysqli_fetch_row($rs_ctime);
	// coookie expiry
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {
		logout();
		}
/* Security check with untrusted cookies - dont trust value stored in cookie.
/* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/
	 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
	 	  session_regenerate_id(); //against session fixation attacks.

		  $_SESSION['user_id'] = $_COOKIE['user_id'];
		  $_SESSION['user_name'] = $_COOKIE['user_name'];
		  $_SESSION['DEV']=$_COOKIE['DEV'];
		/* query user level from database instead of storing in cookies */
		  list($user_level) = mysqli_fetch_row(mysqli_query("select user_level from users where id='$_SESSION[user_id]'"));
		  $_SESSION['user_level'] = $user_level;
		  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

	   } else {
	   logout();
	   }
  } else {
	header("Location: login.php");
	exit();
	}
}
}
function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));

	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
  $mysqli = new Controllers\Database();
	$data = $mysqli->connection->mysqli_real_escape_string($data);

	return $data;
}
function EncodeURL($url)
{
$new = strtolower(ereg_replace(' ','_',$url));
return($new);
}
function DecodeURL($url)
{
$new = ucwords(ereg_replace('_',' ',$url));
return($new);
}
function ChopStr($str, $len)
{
    if (strlen($str) < $len)
        return $str;
    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);
    return $str . "...";
}
function isEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
 }

function isURL($url)
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	} else {
		return false;
	}
}
function checkPwd($x,$y)
{
if(empty($x) || empty($y) ) { return false; }
if (strlen($x) < 4 || strlen($y) < 4) { return false; }
if (strcmp($x,$y) != 0) {
 return false;
 }
return true;
}
function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels

  $i = 0;

  while ($i < $length) {

    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);


    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }
  }
  return $password;
}
function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz";

  $i = 0;

  while ($i < $length) {

    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);


    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }
  }
  return $password;
}
function logout()
{
global $db;
session_start();
if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
mysqli_query("update `users`
			set `ckey`= '', `ctime`= ''
			where `id`='$_SESSION[user_id]' OR  `id` = '$_COOKIE[user_id]'") or die(mysqli_error());
}
/************ Delete the sessions****************/
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_level']);
unset($_SESSION['HTTP_USER_AGENT']);
unset($_SESSION['DEV']);
session_unset();
session_destroy();
/* Delete the cookies*******************/
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
header("Location: login.php");
}
// Password and salt generation
function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}
function checkAdmin() {
	if($_SESSION['user_level'] == ADMIN_LEVEL) {
		return 1;
	}
		else { return 0 ;
	}
}
function checkRR(){
	if($_SESSION['user_level'] == RR_LEVEL){
		return 1;
	}
	else{
		return 0;
	}
}
function checkView(){
	if($_SESSION['user_level'] == VIEW_LEVEL){
		return 1;
	}
	else{
		return 0;
	}
}
function checkEXT(){
	if($_SESSION['user_level'] == EXTERNAL_LEVEL){
		return 1;
	}
	else{
		return 0;
	}
}
function checkPower(){
	if($_SESSION['user_level'] == POWER_LEVEL){
		return 1;
	}
	else{
		return 0;
	}
}
function isAdmin(){
  if($_SESSION['user_level'] == ADMIN_LEVEL){
    return 1;
  }
  else{
    return 0;
  }
}
function getUserFName(){
	$sql = "SELECT * FROM users WHERE id=".$_SESSION['user_id'];
	$rs = mysqli_query($sql);
	$row = mysqli_fetch_array($rs);
	return $row['full_name'];
}
function getUserFNameID($id){
	$sql = "SELECT * FROM users WHERE id=".$id;
	$rs = mysqli_query($sql);
	$row = mysqli_fetch_array($rs);
	return $row['full_name'];
}
function isPolice(){
	$sql = "SELECT * FROM users WHERE id=".$_SESSION['user_id'];
	$rs = mysqli_query($sql);
	$row = mysqli_fetch_array($rs);
	return $row['police'];
}
function sendEmail($to,$from,$subject,$message){
	require_once('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();
    $mail->IsSMTP(); // we are going to use SMTP
	$mail->SMTPDebug  = 2;
    $mail->Host       = 'cs7-dallas.accountservergroup.com';      // setting GMail as our SMTP server
	$mail->Port		  = '465';
	$mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true; // enabled SMTP authentication
    $mail->Username   = email_user;  // user email address
    $mail->Password   = email_password;            // password in GMail
    $mail->AddReplyTo($from,'1');  //email address that receives the response
    $mail->Subject    = $subject;
    $mail->Body       = $message;
    $mail->AltBody    = $message;
    $mail->AddAddress($to, "2");
   	//$mail->AddAttachment($file_path);      // some attached files/
	$mail->Send();
}
// var pwd password returns true if meeting lenght and complexity
function checkPwdComp($pwd){
  $uppercase = preg_match('@[A-Z]@', $pwd);
  $lowercase = preg_match('@[a-z]@', $pwd);
  $number    = preg_match('@[0-9]@', $pwd);
  $specialChars = preg_match('@[^\w]@', $pwd);

  if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pwd) < PASSWORD_MIN_LENGTH){
    return false;
  }
  return true;
}
function getCurrency(){
	$dbi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `country_info WHERE `country`='".$_SESSION['country']."'";
	$dbi->close();
}
function getClaimsEmail(){
	$sql="SELECT * FROM `country_info` WHERE `country`='".$_SESSION['country']."'";
	$rs=mysqli_query($sql);
	$row=mysqli_fetch_array($rs);
	return $row['email'];
}
?>
