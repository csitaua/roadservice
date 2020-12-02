<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if($_SESSION['user_level'] < RR_LEVEL){
	header("Location: index.php");
	exit();
}
$col1 = 100;
$col2 = 225;
$vcol1 = 45;
$vcol2 = 55;
if(isset($_REQUEST['id'])){
	$idm=$_REQUEST['id'];
	$sql = "SELECT * FROM service_req where id='$idm'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$policy = $row['pol'];
	$claimNo = $row['claimNo'];
	$extra_drv = $row['licenseNo'];
}

$vehicle = $_POST['vehicle'];
$requestedBy = $_POST['requestedBy'];
$odo_out = $_POST['odo_out'];
$fuel_out = $_POST['fuel_out'];
//$extra_drv = $_POST['extra_drv'];
$time_out = $_POST['time_out'];
$claimNo_extra = $_POST['claimsNo'];

if(isset($_POST['submit'])){
	$sql = "SELECT * FROM `rental_vehicle` WHERE `id` = '$vehicle'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$rate = $row['rental'];
	$user_id=$_SESSION['user_id'];
	if($row['available']){
		//mark vehicle as not available.
		if(trim($idm)===''){
			$sql = "INSERT INTO `rental` (`rental_vehicle_id`, `rental_company_id`, `requested_by`, `odo_out`, `fuel_out`, `extra_drv`, `time_out`, `rate`, `status`, `claimNo`, `time_in_exp`, `notes`, `enter_by_id`) VALUES ('$vehicle', '".$_POST['company']."', '$requestedBy', '$odo_out', '$fuel_out', '$extra_drv', '$time_out', '$rate', 'Open', '$claimNo_extra', '".$_POST['time_in_exp']."', '".mysql_real_escape_string($_POST['notes'])."', '$user_id')";
		}
		else{
			$sql = "INSERT INTO `rental` (`rental_vehicle_id`, `rental_company_id`, `requested_by`, `odo_out`, `fuel_out`, `extra_drv`, `time_out`, `service_req_id`, `policy_no`, `rate`, `status`, `claimNo`, `time_in_exp`, `notes`, `enter_by_id`) VALUES ('$vehicle', '".$_POST['company']."', '$requestedBy', '$odo_out', '$fuel_out', '$extra_drv', '$time_out', '$idm', '$policy', '$rate', 'Open', '$claimNo_extra', '".$_POST['time_in_exp']."', '".mysql_real_escape_string($_POST['notes'])."', '$user_id')";
		}
		mysql_query($sql);
		//echo mysql_error().'<br/>'.$sql;
		$last_id = mysql_insert_id();
		
		$sql = "UPDATE `rental_vehicle` SET `available`=0, `currentRentalId1 ='$last_id' WHERE `id` = '$vehicle'";
		mysql_query($sql);
		
		header ('location: rental_detail.php?id='.$last_id);
	}	
}

echo menu();

?>

<form name="new_rental" action="" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center"><h8 style="color:#FFF">New Rental</h8></td>
        </tr>
        <tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="5">&nbsp;<input type="hidden" name="idm" value="<?php echo $idm;?>"/>
        <input type="hidden" name="acc_link" value="<?php echo $acc_link;?>"/>
        	</td>
        </tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Company:</td>
            <td class="top-center-child" width="<?php echo $col2;?>">
             <select name="company" style="background-color:#FAD090"/>
            	<?php
					$sql = "SELECT * FROM `rental_company`";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						?>
                        	<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                        <?php	
					}
				?>
            </select>
            </td>
            
            <td class="top-center-child" width="<?php echo $col1;?>">Policy Number:</td>
            <td class="top-right-child" width="<?php echo $col2;?>"><input type="text" name="pol" size="25" value="<?php echo $policy;?>" readonly/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select name="vehicle" style="background-color:#FAD090"/>
            	<?php
					$sql = "SELECT * FROM `rental_vehicle` WHERE `available`=1 and `active`=1 order by make, model";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						?>
                        	<option <?php if($row['id']==$vehicle){echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['make'].' '.$row['model'].' ('.$row['licenseplate'].')';?></option>
                        <?php	
					}
				?>
            </select>
           </td>
        	<td colspan="2" class="middle-right-child" style="font-weight:bold">&nbsp;</td>
      	</tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select style="background-color:#FAD090" name="requestedBy">
            	<?php
					$sql2 = "SELECT * FROM rental_request WHERE active=1 order by `name`";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';	
					}
				?>
            </select>
           </td>
        	<td colspan="2" class="middle-right-child" style="font-weight:bold">Extra Driver Information</td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Odometer Out:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="odo_out" size="15" value="<?php echo $odo_out;?>" /></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Claims No.:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="claimsNo" size="25" value="<?php echo $claimNo;?>" /></td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Fuel Out%:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" name="fuel_out" style="background-color:#FAD090;" size="15" value="<?php echo $fuel_out;?>"/></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Drivers License #:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="extra_drv" id="extra_drv" size="25" onKeyUp="showUpload()" value="<?php echo $extra_drv;?>" /> 
            <span id="upload_drv" style="display:<?php if(strlen($extra_drv)!=0) {echo 'inline';} else{echo 'none';}?>">
            	<div class="buttonwrapper">
					<a class="squarebutton" onClick="uploadLicense()"><span>Upload</span></a>
				</div>

            </span></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Date Time Out:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" id="time_out" name="time_out" readonly value="<?php echo $time_out;?>" />
  <button id="timeoutbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#timeoutbutton').click(
      function(e) {
        $('#time_out').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Notes:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><textarea  style="background-color:#FAD090" name="notes" rows="3" cols="30"></textarea></td>
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Exp. Return Date:</td>
        	<td class="bottom-center-child" width="<?php echo $col2;?>">
            <input type="text" id="time_in_exp" name="time_in_exp" readonly value="" />
  <button id="timeinexpbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#timeinexpbutton').click(
      function(e) {
        $('#time_in_exp').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
            	
            </td>
            <td class="bottom-right-child" colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
        </tr>
    </table>
</form>
<script  type="text/javascript">
	
	function showUpload(){
		if( document.getElementById('extra_drv').value.length != 0){
			document.getElementById('upload_drv').style.display = 'inline';	
		}
		else{
			document.getElementById('upload_drv').style.display = 'none';
		}
	}
	
	function uploadLicense(){
		licensenumber = document.getElementById('extra_drv').value;
		window.open('ins_drivers_license.php?license='+licensenumber);	
	}

</script>
<script type="text/javascript" src="js/functions.js">

</script>
</body>
</html>