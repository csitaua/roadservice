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
mysql_query("update adjuster set active=0 where id='$get[id]'");
echo "yes";
exit();

}
/* Editing users*/

if($get['cmd'] == 'edit')
{
	$add='';

	if($get['approve']!=''){
		$rst = mysql_query("select * from adjuster WHERE id='$get[id]'");
		$rowt = mysql_fetch_array($rst);
		$tapprove = $rowt['approve'];
		if($tapprove=='' ){
			// no approve first one
			$add = $add.",`approve`='$get[approve]'";
		}
		else if(!strpos($tapprove,$get['approve'])){
				$add = $add.",`approve`='".$tapprove.",".$get['approve']."'";
		}
	}
	if($get['approve_non_preferred']!=''){
		$rst = mysql_query("select * from adjuster WHERE id='$get[id]'");
		$rowt = mysql_fetch_array($rst);
		$tapprove = $rowt['approve_non_preferred'];
		if($tapprove==''){
			// no approve first one
			$add = $add.",`approve_non_preferred`='$get[approve_non_preferred]'";
		}
		else if(!strpos($tapprove,$get['approve_non_preferred'])){
				$add = $add.",`approve_non_preferred`='".$tapprove.",".$get['approve_non_preferred']."'";
		}
	}

	mysql_query("
	update adjuster set
	`name`='$get[name]',
	`email`='$get[email]',
	`active`='$get[active]'".$add."
	where `id`='$get[id]'") or die(mysql_error());
	//header("Location: $ret");

	echo "changes done";
	exit();
}

if($get['cmd'] == 'unban')
{
mysql_query("update adjuster set active=1 where id='$get[id]'");
}

if($get['cmd'] == 'rmapp')
{
	$rst = mysql_query("select * from adjuster WHERE id='$get[id]'");
	$rowt = mysql_fetch_array($rst);
	$tapprove = $rowt['approve'];
	if($tapprove==$get['approver']){
		$nstring = '';
	}
	else{
		$nstring = str_replace(','.$get['approver'].',',',',$tapprove);
		$nstring = str_replace(','.$get['approver'],'',$nstring);
		$nstring = str_replace($get['approver'].',','',$nstring);

	}

	mysql_query("update adjuster set approve='$nstring' where id='$get[id]'");
}

if($get['cmd'] == 'rmappn')
{
	$rst = mysql_query("select * from adjuster WHERE id='$get[id]'");
	$rowt = mysql_fetch_array($rst);
	$tapprove = $rowt['approve_non_preferred'];
	if($tapprove===$get['approver']){
		$nstring = '';
	}
	else{
		$nstring = str_replace(','.$get['approver'].',',',',$tapprove);
		$nstring = str_replace(','.$get['approver'],'',$nstring);
		$nstring = str_replace($get['approver'].',','',$nstring);

	}

	mysql_query("update adjuster set approve_non_preferred='$nstring' where id='$get[id]'");
}


?>
