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

session_start();


echo menu();
$bid = $_REQUEST['id'];

?>

<form name="edit_sc" enctype="multipart/form-data" action="" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Blacklist Detail # <?php  echo str_pad($bid,3,'0',STR_PAD_LEFT);?></h3></td>
       	</tr>
   	</table>
</form>  

</body>
</html>
