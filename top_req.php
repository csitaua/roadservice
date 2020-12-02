<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if($_SESSION['user_level'] < POWER_LEVEL && !checkView()) {
header("Location: index.php");
exit();
}

$sql = "SELECT a_number, count( * ) , `delete` FROM `service_req` WHERE a_number != '??' AND `delete` =0 GROUP BY a_number ORDER BY count( * ) DESC LIMIT 0 , 50";
$rs = mysql_query($sql);
echo menu();
?>
<table width="900">
    	<tr>
        	<td colspan="9" align="center" style="border:0;color:#148540"><h3>Top 50 Request</h3></td>
        </tr>
        <tr><td colspan="9">&nbsp;</td></tr>
        <tr>
        	<td>A Number</td>
            <td>Times</td>
            <td>Policy No</td>
            <td>Date Renew</td>
            <td>Address</td>
            <td>Make</td>
          	<td>Model</td>
            <td>Year</td>
            <td>Coverage \ Use</td>
        </tr>
<?php
while($row = mysql_fetch_array($rs)){
	$lic = $row['a_number'];
	$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Effective` , '%m/%d/%Y' ) DESC";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
?>
	<tr>
    	<td><a style="color:#DCB272" href="check_ins.php?lic=<?php echo $row['a_number'];?>"/><?php echo $row['a_number'];?></td>
       	<td><?php echo $row[1];?></td>
        <td><?php echo $row2['PolicyNo'];?></td>	
        <td><?php echo $row2['Date_Renewal'];?></td>
        <td><?php echo $row2['Address1'];?></td>	
        <td><?php echo $row2['Make'];?></td>
        <td><?php echo $row2['Model'];?></td>
        <td><?php echo $row2['YearMake'];?></td>
        <td><?php echo $row2['VehCoverage'].' \ '.$row2['VehUse'];?></td>
    </tr>
<?php
}

?>
</table>
</body>
</html>