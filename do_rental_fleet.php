<?php
include 'dbc.php';
session_start();
if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$ret = $_SERVER['HTTP_REFERER'];

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

if($get['cmd'] == 'ban')
{
mysql_query("update rental_vehicle set active=0 where id='$get[id]'");

//header("Location: $ret");
echo "yes";
exit();

}
/* Editing users*/

if($get['cmd'] == 'edit')
{

/* Now update user data*/
mysql_query("
update rental_vehicle set
`licenseplate`='$get[licenseplate]',
`make`='$get[make]',
`model`='$get[model]',
`year`='$get[year]',
`rental`='$get[rental]',
`active`='$get[active]'
where `id`='$get[id]'") or die(mysql_error());
//header("Location: $ret");

echo "changes done";
exit();
}

if($get['cmd'] == 'unban')
{
mysql_query("update rental_vehicle set active=1 where id='$get[id]'");
echo "no";

//header("Location: $ret");
// exit();

}


?>
