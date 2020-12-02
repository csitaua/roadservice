<?php

include 'dbc.php';
page_protect();
include "support/connect.php";
include "support/function.php";
include "support/encryptionb.php";
if(!checkAdmin()){ //Not Allowed
	header( 'Location: index.php');		
}

session_start();
echo menu();
$sKey = 'aAuqkqtslbVzi8XhR60N';

if($_POST['cvalue']){
	$dec = decrypt($_POST['cvalue'], $sKey);
	list($name,$catvalue,$coverage,$vuse,$liab,$tp,$dud,$username,$country) = explode('~',$dec);
}

?>
<table>
    <form name="control" action="control.php" method="post">
    <tr>
    	<td colspan="2">Please enter control below</td>
    </tr>
    <tr>
    	<td width="400"><input type="text" name="cvalue" value="<?php echo $_POST['cvalue']?>"  style="background-color:#FAD090; width:380px" ></td>
    	<td><input type="Submit" value="Control"/></td>
    </tr>
    <?php if($_POST['cvalue']){ ?>
    	<tr>
        	<td colspan="2">
            	<table>
                	<tr>
                    	<td width="125">
                        	Name:
                        </td>
                        <td>
                        	<?php echo $name?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Catalog Value:
                        </td>
                        <td>
                        	<?php echo number_format($catvalue,2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Coverage:
                        </td>
                        <td>
                        	<?php 
								if($coverage==1){
									echo 'Comprehensive';
								}
								else if($coverage==2){
									echo 'Comprehensive Super Cover';
								}
								else if($coverage==3){
									echo 'Third Party';
								}
								else if($coverage==4){
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
								$sql = "SELECT * FROM vehicleuse WHERE id=".$vuse;
								$rs = mysql_query($sql);
								$row = mysql_fetch_array($rs);
								echo $row['description'];
							?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Liability:
                        </td>
                        <td>
                        	<?php echo number_format($liab,2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Total Premium:
                        </td>
                        <td>
                        	<?php echo number_format($tp,2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Deductible:
                        </td>
                        <td>
                        	<?php echo number_format($dud,2)?>
                        </td>
                  	</tr>
                    <tr>
                        <td width="125">
                        	Username:
                        </td>
                        <td>
                        	<?php echo $username?>
                        </td>
                    </tr>
                    <tr>
                        <td width="125">
                        	Country:
                        </td>
                        <td>
                        	<?php echo $country?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
    </form>
</table>
</body>
</html>