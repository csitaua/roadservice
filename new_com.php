<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

if($_SESSION['user_level'] < RR_LEVEL){
	header("Location: index.php");
	exit();
}
$lic = $_REQUEST['lic'];
$id = $_REQUEST['id'];
$sc = $_REQUEST['sc'];

if(strcmp($_REQUEST['submit'],'Submit')==0){
	$comment = $_REQUEST['comment'];
	$user_id = $_SESSION['user_id'];
	if(isset($_REQUEST['id'])){
		//edit
		$sql = "UPDATE vehicle_com SET comment='$comment', user_id='$user_id' WHERE id='$id'";
		mysql_query($sql);
	}
	else{
		$sql = "INSERT INTO vehicle_com (license, comment, user_id) VALUES ('$lic','$comment','$user_id')";
		mysql_query($sql);
	}
	
	if(isset($_REQUEST['sc'])){
		header("Location: edit_sc.php?sc=".$sc);
		exit();	
	}
	else{
		header("Location: check_ins.php?lic=".$lic);
		exit();
	}
}	
$message = '';

if(isset($_REQUEST['id'])){
	$sql = "SELECT * FROM vehicle_com WHERE id='$id'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);	
	$message = $row['comment'];
}

//session_start();

echo menu();
?>

<table width="900">
    <tr>
    	<td colspan="5" align="center" style="border:0;color:#148540"><h3><?php if(isset($_REQUEST['id'])){echo 'Edit ';} else{ echo 'New ';}?>Comment on Vehicle <?php echo $lic;?></h3></td>
   	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
    <form name="rec_com" action="new_com.php?lic=<?php echo $lic; if(isset($_REQUEST['id'])){ echo '&id='.$id;} if(isset($_REQUEST['sc'])){echo '&sc='.$sc;}?>" method="post">
    <tr>
    	<td width="100">Comment:</td>
        <td colspan="4"><textarea name="comment" rows="4" cols="40"><?php echo $message;?></textarea></td>
   	</tr>
    <tr>
    	<td width="100">&nbsp;</td>
        <td colspan="4"><input type="submit" name="submit" value="Submit"/></td>
    </tr>
    </form>
</table>

</body>
</html>