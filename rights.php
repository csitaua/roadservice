<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if($_SESSION['user_level'] != ADMIN_LEVEL ) {
header("Location: index.php");
exit();
}

echo menu();
$col1 = 120;
$col2 = 275;

?>

<form name="m_report" action="edit_rights.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>User Rights</h3></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
       
	<tr>
        <td width="<?php echo $col1;?>">User:</td>
        <td width="<?php echo $col2;?>"><select name="aid" style="background-color:#FAD090">
            <?php
				$sql = "SELECT * FROM users ORDER BY `full_name` ASC";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['full_name'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td>
    <?php 
	if(strcmp($_REQUEST['submit'],'Submit')==0){
		
	}
	?>
   	<tr>
    	<td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
        <td>&nbsp;</td>
  	</tr>
    </table>
</form>

</body>
</html>