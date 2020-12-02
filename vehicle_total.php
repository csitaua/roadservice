<?php
//include 'dbc.php';
date_default_timezone_set('America/Aruba');
//page_protect();
//include "support/connect.php";
//include "support/function.php";
$_SESSION['country']='Aruba';
include ('db-info.inc');
define ("DB_HOST", "192.168.5.24"); // set database host
define ("DB_USER", "web"); // set database user
define ("DB_PASS","O&8Bd0&iq;A-"); // set database password

define ("DB_NAME","roadservice"); // set database name
define ("HOST_INSPRO", "181.41.56.22, 54050");
$serverName = "181.41.56.22, 54050"; 


$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.1");
$db1= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database2".$_SESSION['country'].' z');


$connectionInfo = array( "Database"=>"insproSQL" , "UID"=>"exportsa", "PWD"=>"nvsql2304@@",'ReturnDatesAsStrings'=>true);
$conn = sqlsrv_connect( $serverName, $connectionInfo);





$vtarget_2016=16500;
$vbegin=13000;
//$hbegin=1740;
//$lbegin=2897;
//$htarget_2016=2500;
//$ltarget_2016=4000;

$today = date('m/d/Y');
$today = strtotime($today);
$finish = '12/31/2019';
$finish = strtotime($finish);
    //difference
$diff = $finish - $today;
$diff=floor($diff/(60*60*24));

$vtarget=floor($vtarget_2016-(($vtarget_2016-$vbegin)/366)*$diff);
//$htarget=floor($htarget_2016-(($htarget_2016-$hbegin)/366)*$diff);
//$ltarget=floor($ltarget_2016-(($ltarget_2016-$lbegin)/366)*$diff);


$sql3 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A'";
$rs3= sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch_array($rs3);
$total = $row3['t'];

$sql3 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A' and YEAR([Date_Application])=".date("Y");
$rs3= sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch_array($rs3);
$totaly = $row3['t'];

$sql = "INSERT INTO `vehicle_total_history` (`amount`,`ytd_new`,`dateTime`,`target`) VALUES ('$total', '$totaly','".date(DATE_ATOM)."', '$vtarget')";
mysql_query($sql);
/*
$sql = "INSERT INTO `home_total_history` (`amount`,`dateTime`, `target`) VALUES ('$totalh','".date(DATE_ATOM)."', '$htarget')";
mysql_query($sql);
*/
/*
$serverName2 = "190.13.120.83"; 
$connectionInfo2 = array( "Database"=>"PAR_PRODUCTION" , "UID"=>"exportsa", "PWD"=>"nvsql2304@@",'ReturnDatesAsStrings'=>true);
$connl = sqlsrv_connect( $serverName2, $connectionInfo2);
$sql3 = "SELECT count(*) as t FROM [dbo].[FLX_CONMSTR] WHERE conmstr_status='IA'";
$rs3= sqlsrv_query($connl,$sql3);
$row3 = sqlsrv_fetch_array($rs3);
$totall=$row3['t'];

$sql = "INSERT INTO `life_total_history` (`amount`,`dateTime`, `target`) VALUES ('$totall','".date(DATE_ATOM)."', '$ltarget')";
mysql_query($sql);
*/

?>