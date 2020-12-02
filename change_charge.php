<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

$col1 = 85;
$col2 = 200;
$id = $_REQUEST['sc'];
$error = '';

$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$user_id = $_SESSION['user_id'];

if(strcmp($_REQUEST['submit'],'Submit')==0){
	$reason = trim($_REQUEST['reason']);
	if(strcmp($reason,'')!=0){
		$cur_value = $_REQUEST['charged'];
		$prev_value = $row['charged'];
		$timestamp = date('n-j-Y G:i:s'); 
		$sql2 = "INSERT INTO `change_log` (`prev_value`,`cur_value`,`reason`,`user_id`,`timestamp`, `table`, `row_id`) VALUES ('$prev_value', '$cur_value', '$reason', '$user_id', '$timestamp', 'service_req', '$id')";
		mysql_query($sql2);
		$sql2 = "UPDATE `service_req` SET `charged`='$cur_value' WHERE `id`='$id'";
		mysql_query($sql2);
		header('Location: edit_sc.php?sc='.$id);	
	}
	else{
		$error = "Please enter a reason";	
	}
}

$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
$rs3 = mysql_query($sql3);
$row3 = mysql_fetch_array($rs3);
if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
	echo menu();
	?>
	<form name="change_charged" enctype="multipart/form-data" action="change_charge.php?sc=<?php echo $id;?>" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Change Charge Amount for Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h3></td>
        </tr>
         <tr><td colspan="5">&nbsp;</td></tr>
         <?php if ($error){
		?>
         <tr><td colspan="5" style="color:#F00; font-weight:bold"><?php echo $error;?></td></tr>
        <?php
		 }
		?>
         <tr>
         	<td width="<?php echo $col1?>">Amount:</td>
            <td colspan="4"><input type="text" value="<?php echo number_format($row['charged'],2)?>" style="background-color:#FAD090; text-align:right" name="charged" size="10"/>&nbsp;Afl.</td>
         </tr>
         <tr>
         	<td width="<?php echo $col1?>">Reason:</td>
            <td colspan="4"><textarea name="reason" style="background-color:#FAD090;" cols="35" rows="5"></textarea></td>
         </tr>
         <tr>
         	<td colspan="5" align="center"><input type="submit" name="submit" value="Submit"/></td>
         </tr>
   	</table>
  	</form>
  	<?php
}
else{
	header('Location: index.php');	
}

?>
</body>
</html>