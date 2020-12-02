<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css" href="anytime/anytime.css" />
<script src="anytime/jquery-1.6.4.min.js"></script>
<script src="anytime/anytime.js"></script>
<title>Nagico Road and Claims Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <link rel="shortcut icon" type="image/x-icon" href="menu/CSS/dropdown/transitional/themes/nvidia.com/images/favicon.ico" /> -->
<link href="menu/helper.css" media="screen" rel="stylesheet" type="text/css" />
<link href="menu/css/dropdown/dropdown.css" media="all" rel="stylesheet" type="text/css" />
<link href="menu/default.advanced.css" media="all" rel="stylesheet" type="text/css" />
<script src="validator/gen_validatorv4.js" type="text/javascript"></script>
</head>
<body>

<h1><table width="650">
	<tr>
		<td><img src="images/nagico-logo.jpg" width="225" /></td>
		<td align="right">Nagico Road and Claims Service</td>
	</tr>
</table>
</h1>
<?php
	include 'dbc.php';
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	$id = $_REQUEST['sc'];
	$sql2 = "SELECT * FROM `service_req_extra` WHERE `sc_id`='$id'";
	$rs2 = mysql_query($sql2);
	$pols = 0;
	$status = 0;
	if($row2 = mysql_fetch_array($rs2)){
		$pols = explode(',',$row2['police']);
		$status = $row2['status'];	
	}
	
	if(strcmp(($_REQUEST['Submit']),'submit')==0){
		$sql = "INSERT INTO `polices` (`lastname`, `firstname`,`specialist`) VALUES ('".mysql_real_escape_string($_REQUEST[lname])."', '".mysql_real_escape_string($_REQUEST[fname])."','$_POST[type]')";	
		mysql_query($sql);
		echo '<script language=javascript>window.history.go(-2);</script>  
';
	}
?>
<form name="add_pol" action="" method="post">
	<table width="600" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Add Police</h3></div></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" width="175">First Name:</td>
            <td class="top-right-child" width="425" colspan="4">
            	<input type="text" required="required" name="fname" size="20"/>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="175">Last Name:</td>
            <td class="middle-right-child" width="425" colspan="4">
            	<input type="text" required="required" name="lname" size="20"/>
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="175">Roll:</td>
            <td class="middle-right-child" width="425" colspan="4">
            	<select name="type">
                	<option value="0">Police</option>
                    <option value="1">Specialist</option>
                </select>
            </td>
        </tr>
        <tr><td class="bottom-child" colspan="5"><input type="submit" name="Submit" value="submit" />
   	</table>
</form>
<script type="text/javascript" src="js/functions.js">
</script>
</body>
</html>