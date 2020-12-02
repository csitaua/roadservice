<?php

include 'dbc.php';
page_protect();
include "support/connect.php";
include "support/function.php";
//error_reporting(E_ALL);
if(!checkAdmin()){ //Not Allowed
	header( 'Location: index.php');		
}

session_start();
echo menu();
$error = 0;

if($_POST['cvalue']){
	$cn = $_POST['cvalue'];
	$uid = substr($cn,0,3);
	$uid = intval($uid);
	
	$sql = "SELECT * FROM `covernote` WHERE `covernoteno` = '$_POST[cvalue]'";
	$rs = mysql_query($sql);
	if(mysql_num_rows($rs)==0){
		$error = 1;
	}
	else{
		$row = mysql_fetch_array($rs);	
	}
}

?>
<table>
    <form name="control" action="covernote_ver.php" method="post">
    <tr>
    	<td colspan="2">Please enter Covernote number below</td>
    </tr>
    <tr>
    	<td width="125"><input type="text" name="cvalue" value="<?php echo $_POST['cvalue']?>"  style="background-color:#FAD090; width:100px" maxlength="8" ></td>
    	<td><input type="Submit" value="Control"/></td>
    </tr>
    <?php if($_POST['cvalue'] && $error == 0){ ?>
    	<tr>
        	<td colspan="2">
            	<table>
                	<tr>
                    	<td width="125">
                        	Name:
                        </td>
                        <td>
                        	<?php echo $row['name'].mysql_error()?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Catalog Value:
                        </td>
                        <td>
                        	<?php echo number_format($row['value'],2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Coverage:
                        </td>
                        <td>
                        	<?php 
								if($row['coverage']==1){
									echo 'Comprehensive';
								}
								else if($row['coverage']==2){
									echo 'Comprehensive Super Cover';
								}
								else if($row['coverage']==3){
									echo 'Third Party';
								}
								else if($row['coverage']==4){
									echo 'Third Party Limited Comprehensive';
								}
							?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Vehicle Use:
                        </td>
                        <td>
                        	<?php 
								$sql2 = "SELECT * FROM vehicleuse WHERE id=".$row['vuse'];
								$rs2 = mysql_query($sql2);
								$row2 = mysql_fetch_array($rs2);
								echo $row2['description'];
							?>
                        </td>
                  	</tr>
           
                    <tr>
                        <td width="125">
                        	Total Premium:
                        </td>
                        <td>
                        	<?php echo number_format($row['net_premium'],2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Deductible:
                        </td>
                        <td>
                        	<?php echo number_format($row['deductible'],2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Username:
                        </td>
                        <td>
                        	<?php 
								$sql2 = "SELECT * FROM users WHERE id=".$uid;
								$rs2 = mysql_query($sql2);
								$row2 = mysql_fetch_array($rs2);
								echo $row2['user_name'];
							?>
                        </td>
                    </tr>
                    <tr>
                        <td width="125">
                        	Agent:
                        </td>
                        <td>
                        	<?php echo $row2['agent'];?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php }  else {?>
    	<tr>
        	<td colspan="3">Error please check Covernote number</td>
        </tr>
    <?php } ?>
    </form>
</table>
</body>
</html>