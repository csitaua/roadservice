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
$id = $_REQUEST['id'];
$sql2 = "SELECT * FROM car_wash WHERE id=$id";
$rs2 = mysql_query($sql2);
$row2 = mysql_fetch_array($rs2);

if(isset($_POST['delete'])){
	$sql="UPDATE `car_wash` set `active`=0 WHERE `id`=$id";
	if(mysql_query($sql)){
		$last_id = mysql_insert_id();
		header ('location: car_wash_list.php');
	}
	else{
		echo mysql_error().'<br/>'.$sql;
	}
}
else if(isset($_POST['submit'])){
		$attendee_key_id=$_POST['attendee_key_id'];
		$pick_date_time=$_POST['pick_date_time'];
		$status=$_POST['status'];
		$out_date_time=$_POST['time_out'];
		$po=$_POST['po'];
		if(trim($pick_date_time)===''){
			$sql="UPDATE `car_wash` SET `attendee_key_id`=$attendee_key_id, `status`='$status', `out_date_time`='$out_date_time', `po_number`='$po' WHERE `id`=$id";
		}
		else{
			$sql="UPDATE `car_wash` SET `attendee_key_id`=$attendee_key_id, `pick_date_time`='$pick_date_time', `status`='$status', `out_date_time`='$out_date_time', `po_number`='$po' WHERE `id`=$id";
		}
		if(mysql_query($sql)){
			$last_id = mysql_insert_id();
			header ('location: car_wash_list.php');
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
        	<td colspan="5" align="center"><h8 style="color:#FFF">New Car Wash</h8></td>
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
            <td class="top-center-child" width="<?php echo $col2;?>">
            	<?php
					$sql = "SELECT * FROM `car_wash_vehicle` WHERE id=".$row2['car_wash_vehicle_id'].";";
					$rs = mysql_query($sql);
					$row = mysql_fetch_array($rs);
					echo $row['make'].' '.$row['model'].' ('.$row['license_plate'].')';
					
				?>
            </td>
            
            <td class="top-center-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="top-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            	<?php
					$sql = "SELECT * FROM car_wash_request WHERE id=".$row2['request_id'].";";
					$rs = mysql_query($sql);
					$row=mysql_fetch_array($rs);
					echo $row['name'];	
					
				?>
           </td>
        	<td colspan="2" class="middle-right-child" style="font-weight:bold">&nbsp;</td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Car Wash Company:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            	<?php
					$sql = "SELECT * FROM car_wash_vendor WHERE id=".$row2['car_wash_vendor_id'].";";
					$rs = mysql_query($sql);
					$row=mysql_fetch_array($rs);
					echo $row['name'];
				?>
          </td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Wash Type:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <?php 
				if($row2['wash_type']==1){ echo "Inside and Outside (Afl. ".number_format($row2['rate'],2).")";}
				else if($row2['wash_type']==2){ echo "Outside (Afl. ".number_format($row2['rate'],2).")";}
				else if($row2['wash_type']==3){ echo "Inside (Afl. ".number_format($row2['rate'],2).")";}
				else if($row2['wash_type']==4){ echo "Flatbed (Afl. ".number_format($row2['rate'],2).")";}
			?>
            </td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Purchase Order#:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" name="po" style="background-color:#FAD090" value="<?php echo $row2['po_number'];?>"/></td>
        </tr>
         <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select style="background-color:#FAD090" name="attendee_key_id">
            <?php 
				$sql3 = "SELECT * FROM `car_wash_request` WHERE `active`=1";
				$rs3 = mysql_query($sql3);
				if($row2['attendee_key_id']==0){
					echo '<option value="0">None</option>';
				}
				while($row3 = mysql_fetch_array($rs3)){
					if($row3['id']==$row2['attendee_key_id']){
						echo '<option value="'.$row3['id'].'" selected="selected">'.$row3['name'].'</option>';	
					}
					else{
						echo '<option value="'.$row3['id'].'">'.$row3['name'].'</option>';	
					}
				}
			?>
            </select>
            </td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Date Time Request:</td>
        	<td class="middle-none-child" width="<?php echo $col2;?>">
            	<input type="text" id="time_out" name="time_out" readonly value="<?php echo $row2['out_date_time'];?>" />
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
             <td class="middle-right-child" colspan="2" align="right">&nbsp;</td>
             </tr>
             <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Date Time Pickup:</td>
        	<td class="middle-none-child" width="<?php echo $col2;?>">
            	<input type="text" id="pick_date_time" name="pick_date_time" value="<?php echo $row2['pick_date_time'];?>" />
  <button id="timepickbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#timepickbutton').click(
      function(e) {
        $('#pick_date_time').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
            </td>
            <td class="middle-right-child" colspan="2" align="right">&nbsp;</td>
        </tr>
         <tr >
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Status:</td>
            <td class="bottom-center-child" width="<?php echo $col2;?>">
            <select style="background-color:#FAD090" name="status">
            <option value="New" <?php if($row2['status']==='New') {echo 'selected="selected"';}?>>New</option>
            <option value="Pending Invoice" <?php if($row2['status']==='Pending Invoice') {echo 'selected="selected"';}?>>Pending Invoice</option>
            <option value="In Progress" <?php if($row2['status']==='In Progress') {echo 'selected="selected"';}?>>In Progress</option>
            <option value="Pending Payment" <?php if($row2['status']==='Pending Payment') {echo 'selected="selected"';}?>>Pending Payment</option>
            <option value="Closed" <?php if($row2['status']==='Closed') {echo 'selected="selected"';}?>>Closed</option>
            </select>
            </td>
            <td class="bottom-center-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="bottom-right-child" width="<?php echo $col2;?>" align="right"><input type="submit" name="delete" value="Delete"/>&nbsp;&nbsp;<input type="submit" name="submit" value="Submit"/></td>
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