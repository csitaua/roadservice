<?php

include 'dbc.php';
page_protect();
include "support/connect.php";
include "support/function.php";

echo menu();

$sql = "SELECT * FROM tpbp";
$rs = mysql_query($sql);

?>
<table border="1">
	<tr>
    	<td width="125"><h3>Minimum</h3></td>
        <td width="125"><h3>Maximum</h3></td>
        <td width="125"><h3>Base Premium</h3></td>
        <td width="125"><h3>Minimum Bon</h3></td>
        <td width="125"><h3>Maximum Bon</h3></td>
        <td width="125"><h3>Base Premium Bon</h3></td>
    </tr>
    <tr><td colspan="6">&nbsp;</td></tr>
   
<?php
while($row = mysql_fetch_array($rs)){
	echo '	<tr>
				<td>'.$row['min'].'</td>
				<td>'.$row['max'].'</td>
				<td>'.$row['bp'].'</td>
				<td>'.convDollar($row['min']).'</td>
				<td>'.convDollar($row['max']).'</td>
				<td>'.convDollar($row['bp']).'</td>
			</tr>';	
}
?>
</table>

</body>
</html>