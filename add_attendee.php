<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();
if(!checkAdmin()) {
header("Location: index.php");
exit();
}

echo menu();
$col1 = 100;
$col2 = 275;

?>

<form name="new_sc" action="rec_att.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Add Attendee</h3></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">First Name:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="fname" size="50" /></td>
            <td width="<?php echo $col1;?>">Last Name:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="lname" size="50" /></td>
        </tr>
        <tr>
        	<td colspan="5"><input type="submit" name="Submit" value="Submit" /></td>
        </tr>
    </table>
</form>
</body>
</html>