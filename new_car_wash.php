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

if(isset($_POST['submit'])){
		$vehicle=$_POST['vehicle'];
		$requestBy=$_POST['requestedBy'];
		$carWashVendor=$_POST['carWashVendor'];
		$wash_type=$_POST['wash_type'];
		$time_out=$_POST['time_out'];
		$sql = "SELECT * FROM car_wash_vehicle WHERE id=".$vehicle.";";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$vehicle_id=$row['car_wash_group_id'];
		$sql = "SELECT * FROM car_wash_rate WHERE car_wash_group_id=".$wash_type." AND car_wash_vendor_id=".$carWashVendor." and vehicle_type_id=".$vehicle_id." and active=1";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$rate=$row['rate'];
		$sql="INSERT INTO `car_wash` (car_wash_vehicle_id, car_wash_vendor_id, out_date_time, attendee_id, request_id, rate, wash_type ) values ($vehicle, $carWashVendor, '$time_out', ".$_SESSION['user_id'].",$requestBy , $rate , $wash_type);";
		if(mysql_query($sql)){
			$last_id = mysql_insert_id();
			header ('location: car_wash_detail.php?id='.$last_id);
		}
		else{
			echo mysql_error().'<br/>'.$sql;
		}
}

echo menu();

?>

<form name="new_rental" action="" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center"><h8 style="color:#FFF">View Car Wash</h8></td>
        </tr>
        <tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="5">&nbsp;<input type="hidden" name="idm" value="<?php echo $idm;?>"/>
        <input type="hidden" name="acc_link" value="<?php echo $acc_link;?>"/>
        	</td>
        </tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Car:</td>
            <td class="top-center-child" width="<?php echo $col2;?>"><select name="vehicle" style="background-color:#FAD090"/>
            	<?php
					$sql = "SELECT * FROM `car_wash_vehicle` WHERE `active`=1 ORDER BY `make`, `model`";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						?>
                        	<option <?php if($row['id']==$vehicle){echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['make'].' '.$row['model'].' - '.$row['year'].' ('.$row['license_plate'].')';?></option>
                        <?php	
					}
				?>
            </select>
            </td>
            
            <td class="top-center-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="top-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select style="background-color:#FAD090" name="requestedBy">
            	<?php
					$sql2 = "SELECT * FROM car_wash_request WHERE active=1 order by `name` asc";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';	
					}
				?>
            </select>
           </td>
        	<td colspan="2" class="middle-right-child" style="font-weight:bold">&nbsp;</td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Car Wash Company:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select style="background-color:#FAD090" name="carWashVendor">
            	<?php
					$sql2 = "SELECT * FROM car_wash_vendor WHERE active=1";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';	
					}
				?>
            </select>
          </td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Wash Type:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select name="wash_type" style="background-color:#FAD090" >
            <option value="1">Inside and Outside</option>
            <option value="2">Outside</option>
            <option value="3">Inside</option>
            <option value="4">Flatbed</option>
            </select>
            </td> 
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Date Time Requested:</td>
        	<td class="bottom-center-child" width="<?php echo $col2;?>">
            	<input type="text" id="time_out" name="time_out" readonly value="<?php echo $time_out;?>" />
  <button id="timeoutbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#timeoutbutton').click(
      function(e) {
        $('#time_out').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d %H:%i"}).focus();
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