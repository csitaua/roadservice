<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();


echo menu();

$col1 = 75;
$col2 = 200;

?>

<table width="900">
    	<tr>
        	<td colspan="4" align="center" style="border:0;color:#148540"><h3>Attendee</h3></td>
        </tr>
        <tr><td colspan="4" >&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">ID</td>
            <td width="<?php echo $col2;?>">First Name</td>
            <td width="<?php echo $col2;?>">Full Name</td>
			<td>Active</td>
        </tr>
        
        <?php
			$sql = "SELECT * FROM  attendee";
			$rs = mysql_query($sql);
			while($row = mysql_fetch_array($rs)){
		?>
        	<tr>
            	<td width="<?php echo $col1;?>"><?php echo $row['id'];?></td>
                <td width="<?php echo $col2;?>"><?php echo $row['s_name'];?></td>
                <td width="<?php echo $col2;?>"><?php echo $row['f_name'];?></td>
                <td><?php echo $row['active'];?></td>
            </tr>
        <?php }?>
   
 </table>

</body>
</html>