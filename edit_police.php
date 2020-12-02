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

<h1><table width="600">
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
		$sql = "INSERT INTO `polices` (`lastname`, `firstname`) VALUES ('".mysql_real_escape_string($_REQUEST[lname])."', '".mysql_real_escape_string($_REQUEST[fname])."')";	
		mysql_query($sql);
		echo '<script language=javascript>window.history.go(-2);</script>  
';
	}
	$cols = 20;
?>
<form name="add_pol" action="" method="post">
	<table width="600" cellspacing="2">
    	<tr>
        	<td colspan="6" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Edit Police</h3></div></td>
        </tr>
        <tr><td colspan="6">&nbsp;</td></tr>
        <tr class="thead">
        	<td width="<?php echo $cols;?>%">First Name</td>
            <td width="<?php echo $cols;?>%">Last Name</td>
            <td width="<?php echo $cols-10;?>%">Specialist</td>
            <td width="<?php echo $cols-10;?>%">Active</td>
            <td width="<?php echo $cols-5;?>%">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php
			$bg = 0;
			$sql = "SELECT * FROM `polices` WHERE 1";
			$rs = mysql_query($sql);
			while($row = mysql_fetch_array($rs)){
		?>	
        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                <td width="<?php echo $cols;?>%"><?php echo $row['firstname']?></td>
                <td width="<?php echo $cols;?>%"><?php echo $row['lastname']?></td>
                 <td width="<?php echo $cols-10;?>%"><?php if($row['specialist']){
					echo 'Yes';
				}
				else{
					echo 'No';	
				}?></td>
                <td width="<?php echo $cols-10;?>%"><?php if($row['active']){
					echo 'Yes';
				}
				else{
					echo 'No';	
				}?></td>
                <td width="<?php echo $cols-5;?>%">
                <?php if($row['active']){ ?>
                	<div class="buttonwrapper"><a class="squarebutton" href="deactivate_police.php?id=<?php echo $row['id'];?>&activate=0"><span>De-Activate</span></a></div>
               	<?php }
					else{
				?>
                	<div class="buttonwrapper"><a class="squarebutton" href="deactivate_police.php?id=<?php echo $row['id'];?>&activate=1"><span>Activate</span></a></div>
                <?php } ?>
                </td>
                <td>
                <div class="buttonwrapper"><a class="squarebutton" href="edit_police_info.php?id=<?php echo $row['id'];?>"><span>Edit</span></a></div>
                </td>
        </tr>
        <?php 
				if($bg ==1){
					$bg=0;	
				}
				else{
					$bg=1;	
				}
			}
		?>
         <tr><td colspan="6"><div class="buttonwrapper"><a class="squarebutton" onclick="window.history.go(-1);"><span>Back</span></a></div></td></tr>
   	</table>
</form>
<script type="text/javascript" src="js/functions.js">
</script>
</body>
</html>