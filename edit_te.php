<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();


echo menu();
$id = $_REQUEST['te'];

$col1 = 75;
$col2 = 200;
$history_item = 3;

$sql = "SELECT * FROM travel_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';

if(!checkAdmin()){
	$st='readonly="readonly"';	
}

?>


<form name="edit_te" action="rec_te.php?te=<?php echo $id;?>" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Travel Insurance Emergency# <?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></h3></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Date/time:</td>
             <td colspan="3"><?php echo $row['datetime'];?></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Name:</td>
            <td colspan="3"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="name" size="30" value="<?php echo $row['name'];?>" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Address 1:</td>
            <td colspan="3"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="address1" size="50" value="<?php echo $row['address1'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Address 2:</td>
            <td colspan="3"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="address2" size="50" value="<?php echo $row['address2'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">City:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="city" size="20" value="<?php echo $row['city'];?>"/></td>
            <td width="<?php echo $col1;?>">State:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="state" size="20" value="<?php echo $row['state'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Country:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="country" size="20" value="<?php echo $row['country'];?>" /></td>
            <td width="<?php echo $col1;?>">Zip:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="zip" size="10" value="<?php echo $row['zip'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Phone 1:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="phone1" size="20" value="<?php echo $row['phone1'];?>"/></td>
            <td width="<?php echo $col1;?>">Phone 2:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="phone2" size="20" value="<?php echo $row['phone2'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Emergency Type:</td>
            <td colspan="3"><select name="em" style="background-color:#FAD090">
			<?php
            	$sql2 = "SELECT * FROM emergency ORDER BY description";
				$rs2 = mysql_query($sql2);
				while($row2 = mysql_fetch_array($rs2)){
					if($row2['id'] == $row['em']){
						echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['description'].'</option>';
					}
					else{
            			echo '<option value="'.$row2['id'].'">'.$row2['description'].'</option>';
					}
            	} ?>
            </select></td>
        </tr>
        <?php if(checkAdmin()){ ?>
        <tr>
            <td colspan="4" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
    </table>
</form>
<script  type="text/javascript">
 var frmvalidator = new Validator("edit_te");
</script>
</body>
</html>