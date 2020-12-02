<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();


echo menu();
$col1 = 75;
$col2 = 275;

?>

<form name="m_report" action="rec_sc.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Montly Report</h3></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Year:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="car" size="25" /></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Month:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="loc" size="25" /></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>

</body>
</html>