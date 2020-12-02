<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css" href="anytime/anytime.css" />
<script src="anytime/jquery-1.6.4.min.js"></script>
<script src="anytime/anytime.js"></script>
<title>Nagico Road and Claims Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <link rel="shortcut icon" type="image/x-icon" href="menu/CSS/dropdown/transitional/themes/nvidia.com/images/favicon.ico" /> -->
<link href="menu/helper.css" media="screen" rel="stylesheet" type="text/css" />
<link href="menu/css/dropdown/dropdown.css" media="all" rel="stylesheet" type="text/css" />
<link href="menu/default.advanced.css" media="all" rel="stylesheet" type="text/css" />
<script src="validator/gen_validatorv4.js" type="text/javascript"></script>
</head>
<body>

<h1><table width="650">
	<tr>
		<td><img src="images/nagico-logo.jpg" width="225" /></td>
		<td align="right">Nagico Road and Claims Service</td>
	</tr>
</table>
</h1>
<?php
	include 'dbc.php';
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	$id = $_REQUEST['sc'];
	$sql2 = "SELECT * FROM `service_req_extra` WHERE `sc_id`='$id'";
	$rs2 = mysql_query($sql2);
	$pols = 0;
	$status = 0;
	if($row2 = mysql_fetch_array($rs2)){
		$pols = explode(',',$row2['police']);
		$status = $row2['status'];	
		$specs = explode(',',$row2['specialist']);
		$otherrs=$row2['otherrs'];
	}
	$licenseNo = $row2['dr_license'];
    $sql3 = "SELECT * FROM drivers_license where id = '$licenseNo'";
	$rs3 = mysql_query($sql3);
	$row3 = mysql_fetch_array($rs3);
?>
<form name="acc_extra" action="rec_extra.php?sc=<?php echo $id;?>" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="9" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Accident Extra Information Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h3></div></td>
        </tr>
        <tr><td colspan="9">&nbsp;</td></tr>
        <tr>
        	<td colspan="4" class="top-child_h" width="495" style="color:#148540">
            	Incident Information
            </td>
 			<td width="10">&nbsp;</td>
            <td class="top-child_h" colspan="5" width="495" style="color:#148540">
            	Driver Information
           	</td>
       	</tr>
        <tr>
        	<td class="middle-left-child">Type of Incident</td>
            <td class="middle-none-child"><select name="type_incident" class="fill">
				<option value="" <?php if($row2['type_incident']===""){ echo 'selected="selected"';}?> ></option>            	
                <option value="Accident" <?php if($row2['type_incident']==="Accident"){ echo 'selected="selected"';}?> >Accident</option>
				<option value="Hit and Run" <?php if($row2['type_incident']==="Hit and Run"){ echo 'selected="selected"';}?>>Hit and Run</option>
                <option value="Burglary" <?php if($row2['type_incident']==="Burglary"){ echo 'selected="selected"';}?>>Burglary</option>
                <option value="Car Fire" <?php if($row2['type_incident']==="Car Fire"){ echo 'selected="selected"';}?>>Car Fire</option>
                <option value="Incident" <?php if($row2['type_incident']==="Incident"){ echo 'selected="selected"';}?>>Incident</option>
                <option value="Vandalism" <?php if($row2['type_incident']==="Vandalism"){ echo 'selected="selected"';}?>>Vandalism</option>
                <option value="Fatal Car Accident" <?php if($row2['type_incident']==="Fatal Car Accident"){ echo 'selected="selected"';}?>>Fatal Car Accident</option>
                <option value="Fatal Motor Accident" <?php if($row2['type_incident']==="Fatal Motor Accident"){ echo 'selected="selected"';}?>>Fatal Motor Accident</option>
                <option value="Water Damage" <?php if($row2['type_incident']==="Water Damage"){ echo 'selected="selected"';}?>>Water Damage</option>

            </select>
            </td>
             <td class="middle-none-child">Is the car driveable</td>
            <td class="middle-right-child"><select name="car_driveable" class="fill" style=" <?php if(trim($row2['car_driveable'])==='') {echo 'border:3px solid #FF0000;';}?>">
            	<option value="" <?php if($row2['car_driveable']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Driveable" <?php if($row2['car_driveable']==="Driveable"){ echo 'selected="selected"';}?> >Driveable</option>
				<option value="Not Driveble" <?php if($row2['car_driveable']==="Not Driveble"){ echo 'selected="selected"';}?>>Not Driveble</option>
                <option value="Not safe to drive" <?php if($row2['car_driveable']==="Not safe to drive"){ echo 'selected="selected"';}?>>Not safe to drive</option>
            </select>    
            </td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">Drivers License:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="dr_license" id="dr_license" maxlength="25" size="25" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['dr_license'].'"';?> class="fill" required="required" style=" <?php if(strlen(trim($row2['dr_license']))<3) {echo ';border:3px solid #FF0000;';}?>" />
            </td>			
       	</tr>
         <tr>
        	<td class="middle-left-child">What was your vehicle doing prior to the crash</td>
            <td class="middle-right-child" colspan="3"><select name="vehicle_doing" class="fill" style="width:350px">
            	<option value="" <?php if($row2['vehicle_doing']===""){ echo 'selected="selected"';}?>></option>
                <option value="Traveling straight ahead" <?php if($row2['vehicle_doing']==="Traveling straight ahead"){ echo 'selected="selected"';}?>>Traveling straight ahead</option>
				<option value="Stationary" <?php if($row2['vehicle_doing']==="Stationary"){ echo 'selected="selected"';}?>>Stationary</option>
                <option value="Slowing or Stopping" <?php if($row2['vehicle_doing']==="Slowing or Stopping"){ echo 'selected="selected"';}?>>Slowing or Stopping</option>
                <option value="Leaving a Parking Space" <?php if($row2['vehicle_doing']==="Leaving a Parking Space"){ echo 'selected="selected"';}?>>Leaving a Parking Space</option>
                <option value="Entering a parking space" <?php if($row2['vehicle_doing']==="Entering a parking space"){ echo 'selected="selected"';}?>>Entering a parking space</option>
                <option value="Failing to stop at Sign giving right of way" <?php if($row2['vehicle_doing']==="Failing to stop at Sign giving right of way"){ echo 'selected="selected"';}?>>Failing to stop at Sign giving right of way</option>
                <option value="Coming from the right on Intersection" <?php if($row2['vehicle_doing']==="Coming from the right on Intersection"){ echo 'selected="selected"';}?>>Coming from the right on Intersection</option>
                <option value="Turning to the right" <?php if($row2['vehicle_doing']==="Turning to the right"){ echo 'selected="selected"';}?>>Turning to the right</option>
                <option value="Turning to the Left" <?php if($row2['vehicle_doing']==="Turning to the Left"){ echo 'selected="selected"';}?>>Turning to the Left</option>
                <option value="Changing lanes" <?php if($row2['vehicle_doing']==="Changing lanes"){ echo 'selected="selected"';}?>>Changing lanes</option>
                <option value="Entering traffic Lane" <?php if($row2['vehicle_doing']==="Entering traffic Lane"){ echo 'selected="selected"';}?>>Entering traffic Lane</option>
                <option value="Leaving traffic Lane" <?php if($row2['vehicle_doing']==="Leaving traffic Lane"){ echo 'selected="selected"';}?>>Leaving traffic Lane</option>
                <option value="Driving in same direction in different lanes" <?php if($row2['vehicle_doing']==="Driving in same direction in different lanes"){ echo 'selected="selected"';}?>>Driving in same direction in different lanes</option>
                <option value="Entering a roundabout traffic" <?php if($row2['vehicle_doing']==="Entering a roundabout traffic"){ echo 'selected="selected"';}?>>Entering a roundabout traffic</option>
                <option value="Driving on a roundabout traffic" <?php if($row2['vehicle_doing']==="Driving on a roundabout traffic"){ echo 'selected="selected"';}?>>Driving on a roundabout traffic</option>
                <option value="Making a U-turn" <?php if($row2['vehicle_doing']==="Making a U-turn"){ echo 'selected="selected"';}?>>Making a U-turn</option>
                <option value="Overtaking / Passing" <?php if($row2['vehicle_doing']==="Overtaking / Passing"){ echo 'selected="selected"';}?>>Overtaking / Passing</option>
                <option value="Moving Backwards / Reversing" <?php if($row2['vehicle_doing']==="Moving Backwards / Reversing"){ echo 'selected="selected"';}?>>Moving Backwards / Reversing</option>
                <option value="Opening a Vehicle door" <?php if($row2['vehicle_doing']==="Opening a Vehicle door"){ echo 'selected="selected"';}?>>Opening a Vehicle door</option>
                <option value="Stationary at a intersection" <?php if($row2['vehicle_doing']==="Stationary at a intersection"){ echo 'selected="selected"';}?>>Stationary at a intersection</option>
                <option value="Driving Through Flooded Area" <?php if($row2['vehicle_doing']==="Driving Through Flooded Area"){ echo 'selected="selected"';}?>>Driving Through Flooded Area</option>
                <option value="Driving Through Water Channel" <?php if($row2['vehicle_doing']==="Driving Through Water Channel"){ echo 'selected="selected"';}?>>Driving Through Water Channel</option>
                <option value="Other" <?php if($row2['vehicle_doing']==="Other"){ echo 'selected="selected"';}?>>Other</option>
                <option value="Unknown" <?php if($row2['vehicle_doing']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>            </select>
            </td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">First Name:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="fname" id="fname" maxlength="50" size="35" <?php 
				echo 'value="'.$row3['firstName'].'"'; ?> readonly="readonly" />
            </td>
      	</tr>
         <tr>
        	<td class="middle-left-child">Manner of Collision</td>
            <td class="middle-right-child" colspan="3"><select name="manner_col" class="fill" style="width:250px">
            	<option value="" <?php if($row2['manner_col']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Single vehicle crash / Fixed Object" <?php if($row2['manner_col']==="Single vehicle crash / Fixed Object"){ echo 'selected="selected"';}?> >Single vehicle crash / Fixed Object</option>
				<option value="Rear-end collision" <?php if($row2['manner_col']==="Rear-end collision"){ echo 'selected="selected"';}?>>Rear-end collision</option>
                <option value="Rear to Rear collision" <?php if($row2['manner_col']==="Rear to Rear collision"){ echo 'selected="selected"';}?>>Rear to Rear collision</option>
                <option value="Rear to Side collision" <?php if($row2['manner_col']==="Rear to Side collision"){ echo 'selected="selected"';}?>>Rear to Side collision</option>
                 <option value="Front to Side collision" <?php if($row2['manner_col']==="Front to Side collision"){ echo 'selected="selected"';}?>>Front to Side collision</option>
                <option value="Head on Collision / Angular" <?php if($row2['manner_col']==="Head on Collision / Angular"){ echo 'selected="selected"';}?>>Head on Collision / Angular</option>
                <option value="Sideswipe, same direction" <?php if($row2['manner_col']==="Sideswipe, same direction"){ echo 'selected="selected"';}?>>Sideswipe, same direction</option>
                <option value="Sideswipe, opposite direction" <?php if($row2['manner_col']==="Sideswipe, opposite direction"){ echo 'selected="selected"';}?>>Sideswipe, opposite direction</option>
          
                <option value="Rear to Front Collision" <?php if($row2['manner_col']==="Rear to Front Collision"){ echo 'selected="selected"';}?>>Rear to Front Collision</option>
                <option value="Pedacycle" <?php if($row2['manner_col']==="Pedacycle"){ echo 'selected="selected"';}?>>Pedacycle</option>
                <option value="Pedestrian" <?php if($row2['manner_col']==="Pedestrian"){ echo 'selected="selected"';}?>>Pedestrian</option>
                <option value="Rail-Car Vehicle" <?php if($row2['manner_col']==="Rail-Car Vehicle"){ echo 'selected="selected"';}?>>Rail-Car Vehicle</option>
                <option value="Encroachment / Other" <?php if($row2['manner_col']==="Encroachment / Other"){ echo 'selected="selected"';}?>>Encroachment / Other</option>
                <option value="Water" <?php if($row2['manner_col']==="Water"){ echo 'selected="selected"';}?>>Water</option>
                <option value="Unknown" <?php if($row2['manner_col']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>

            </select>
            </td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">Last Name:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="lname" id="lname" maxlength="50" size="35" <?php echo 'value="'.$row3['lastName'].'"';?> readonly="readonly" />
            </td>            
      	</tr>
        <tr>
        	<td class="middle-left-child">Collision with</td>
            <td class="middle-right-child" colspan="3"><select name="col_with" class="fill" style="width:250px">
            	<option value="" <?php if($row2['col_with']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Motor vehicle in traffic" <?php if($row2['col_with']==="Motor vehicle in traffic"){ echo 'selected="selected"';}?> >Motor vehicle in traffic</option>
				<option value="Parked motor vehicle" <?php if($row2['col_with']==="Parked motor vehicle"){ echo 'selected="selected"';}?>>Parked motor vehicle</option>
                <option value="Pedestrian" <?php if($row2['col_with']==="Pedestrian"){ echo 'selected="selected"';}?>>Pedestrian</option>
                <option value="Cyclist" <?php if($row2['col_with']==="Cyclist"){ echo 'selected="selected"';}?>>Cyclist</option>
                <option value="Animal / Other" <?php if($row2['col_with']==="Animal / Other"){ echo 'selected="selected"';}?>>Animal / Other</option>
                <option value="Work zone maintenance Equipment" <?php if($row2['col_with']==="Work zone maintenance Equipment"){ echo 'selected="selected"';}?>>Work zone maintenance Equipment</option>
                <option value="Rocks" <?php if($row2['col_with']==="Rocks"){ echo 'selected="selected"';}?>>Rocks</option>
                <option value="Rock Fence" <?php if($row2['col_with']==="Rock Fence"){ echo 'selected="selected"';}?>>Rock Fence</option>
                <option value="Other movable object" <?php if($row2['col_with']==="Other movable object"){ echo 'selected="selected"';}?>>Other movable object</option>
                <option value="Curb / Trotuar" <?php if($row2['col_with']==="Curb / Trotuar"){ echo 'selected="selected"';}?>>Curb / Trotuar</option>
                <option value="Tree" <?php if($row2['col_with']==="Tree"){ echo 'selected="selected"';}?>>Tree</option>
                <option value="Utility Pole" <?php if($row2['col_with']==="Utility Pole"){ echo 'selected="selected"';}?>>Utility Pole</option>
                <option value="Light pole" <?php if($row2['col_with']==="Light pole"){ echo 'selected="selected"';}?>>Light pole</option>
                <option value=" Traffic Light Pole" <?php if($row2['col_with']===" Traffic Light Pole"){ echo 'selected="selected"';}?>> Traffic Light Pole</option>
                <option value=" Highway traffic signpost" <?php if($row2['col_with']===" Highway traffic signpost"){ echo 'selected="selected"';}?>> Highway traffic signpost</option>
                <option value="Guardrail" <?php if($row2['col_with']==="Guardrail"){ echo 'selected="selected"';}?>>Guardrail</option>
                <option value="Median barrier" <?php if($row2['col_with']==="Median barrier"){ echo 'selected="selected"';}?>>Median barrier</option>
                <option value="Roundabout" <?php if($row2['col_with']==="Roundabout"){ echo 'selected="selected"';}?>>Roundabout</option>
                <option value="Ditch" <?php if($row2['col_with']==="Ditch"){ echo 'selected="selected"';}?>>Ditch</option>
                <option value="Fence" <?php if($row2['col_with']==="Fence"){ echo 'selected="selected"';}?>>Fence</option>
                <option value="Low Hanging Pole Cable" <?php if($row2['col_with']==="Low Hanging Pole Cable"){ echo 'selected="selected"';}?>>Low Hanging Pole Cable</option>
                <option value="Trash Container" <?php if($row2['col_with']==="Trash Container"){ echo 'selected="selected"';}?>>Trash Container</option>
                <option value="Other fixed object (Wall, Building)" <?php if($row2['col_with']==="Other fixed object (Wall, Building)"){ echo 'selected="selected"';}?>>Other fixed object (Wall, Building)</option>
                <option value="Unknown fixid object" <?php if($row2['col_with']==="Unknown fixid object"){ echo 'selected="selected"';}?>>Unknown fixid object</option>
                </select>
            </td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">Email:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="email" name="email" id="email" maxlength="75" size="45" <?php  echo 'value="'.$row3['email'].'"';?> readonly="readonly" />
            </td>
       	</tr>
        <tr>
        	<td class="middle-left-child">Non Collision</td>
            <td class="middle-right-child" colspan="3"><select name="col_non" class="fill" style="width:350px">
                
                <option value="" <?php if($row2['col_non']===""){ echo 'selected="selected"';}?>></option>
                <option value="Ran off road right" <?php if($row2['col_non']==="Ran off road right"){ echo 'selected="selected"';}?>>Ran off road right</option>
                <option value="Ran off road left" <?php if($row2['col_non']==="Ran off road left"){ echo 'selected="selected"';}?>>Ran off road left</option>
                <option value="Cross median / centerline" <?php if($row2['col_non']==="Cross median / centerline"){ echo 'selected="selected"';}?>>Cross median / centerline</option>
                <option value="Overturn / Rollover" <?php if($row2['col_non']==="Overturn / Rollover"){ echo 'selected="selected"';}?>>Overturn / Rollover</option>
                <option value="Equipment failure (Blown tire, Brakes, etc)" <?php if($row2['col_non']==="Equipment failure (Blown tire, Brakes, etc)"){ echo 'selected="selected"';}?>>Equipment failure (Blown tire, Brakes, etc)</option>
                <option value="Jackknife" <?php if($row2['col_non']==="Jackknife"){ echo 'selected="selected"';}?>>Jackknife</option>
                <option value="Cargo / equipment loss or shift" <?php if($row2['col_non']==="Cargo / equipment loss or shift"){ echo 'selected="selected"';}?>>Cargo / equipment loss or shift</option>
                <option value="Other non-collision" <?php if($row2['col_non']==="Other non-collision"){ echo 'selected="selected"';}?>>Other non-collision</option>
                <option value="Unknown non-collision" <?php if($row2['col_non']==="Unknown non-collision"){ echo 'selected="selected"';}?>>Unknown non-collision</option>
    		</select>
            </td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">Address:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="address" id="address" maxlength="75" size="45" <?php  echo 'value="'.$row3['address'].'"';?> readonly="readonly"/>
            </td>       	
        </tr>
        <tr>
        	<td class="middle-left-child">Type of Impact</td>
            <td class="middle-right-child" colspan="3">
            <select name="rep_impact" id="rep_impact" style="background-color:#FAD090; width:350px <?php if($row2['rep_impact']==0) {echo ';border:3px solid #FF0000;';}?>">
            	<option id="0"></option>
                <?php
					$sql5="SELECT * FROM `vehicle_impact` order by `id`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						if($row2['rep_impact']===$row5['id']){
						echo '<option value="'.$row5['id'].'" selected="selected">'.$row5['description'].'</option>';	
						}
						else{
						echo '<option value="'.$row5['id'].'">'.$row5['description'].'</option>';
						}
					}
				?>
            </select>
            </td>
           	<td>&nbsp;</td>
       		<td class="middle-left-child">Birth Day:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" id="bday" name="bday" readonly="readonly" <?php  echo 'value="'.$row3['birthDay'].'"';?>/>
            </td>
       	</tr>
        <tr>
        	<td class="middle-left-child">Type of Damage</td>
            <td class="middle-right-child" colspan="3">
            <select name="rep_damage" id="rep_damage" style="background-color:#FAD090; width:350px; <?php if($row2['rep_damage']==0) {echo ';border:3px solid #FF0000;';}?>">
            	<option id="0"></option>
                <?php
					$sql5="SELECT * FROM `vehicle_damage` order by `id`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						if($row2['rep_damage']===$row5['id']){
						echo '<option value="'.$row5['id'].'" selected="selected">'.$row5['description'].'</option>';	
						}
						else{
						echo '<option value="'.$row5['id'].'">'.$row5['description'].'</option>';
						}
					}
				?>
            </select>
            </td>
           
           	<td>&nbsp;</td>
       		<td class="middle-left-child">Birth Place:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="bplace" id="bplace" maxlength="75" size="35" <?php  echo 'value="'.$row3['birthPlace'].'"';?> readonly="readonly" />
            </td>
       	</tr>
       
        <tr>
        	<td class="bottom-left-child">Airbag Status</td>
            <td class="bottom-center-child">
            <select name="airbag_status" class="fill" style=" <?php if(trim($row2['airbag_status'])==='') {echo 'border:3px solid #FF0000;';}?>">
            	<option value="" <?php if($row2['airbag_status']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Deployed-front" <?php if($row2['airbag_status']==="Deployed-front"){ echo 'selected="selected"';}?> >Deployed-front</option>
				<option value="Deployed-side" <?php if($row2['airbag_status']==="Deployed-side"){ echo 'selected="selected"';}?>>Deployed-side</option>
                <option value="Deployed both front and side" <?php if($row2['airbag_status']==="Deployed both front and side"){ echo 'selected="selected"';}?>>Deployed both front and side</option>
                <option value="Not deployed" <?php if($row2['airbag_status']==="Not deployed"){ echo 'selected="selected"';}?>>Not deployed</option>
                <option value="Not applicable" <?php if($row2['airbag_status']==="Not applicable"){ echo 'selected="selected"';}?>>Not applicable</option>
                <option value="Unknown" <?php if($row2['airbag_status']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
           	</select>
            </td>
        	<td class="bottom-right-child" colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
       		<td class="middle-left-child">Gender:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="gender" id="gender" maxlength="75" size="20" <?php if(strcmp($row3['gender'],'m')==0){echo 'value="Male"';} else{echo 'value="Female"';} ?> readonly="readonly" />
            </td>
       	</tr>
        <tr>
        	<td colspan="5">&nbsp;</td>
       		<td class="middle-left-child">Phone / Cell:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="tel" name="phone" id="phone" size="15" maxlength="15" <?php echo 'value="'.$row3['phone'].'"';?> readonly="readonly"/>&nbsp;/&nbsp;<input type="tel" name="mobile" id="mobile" size="15" maxlength="15" <?php echo 'value="'.$row3['mobile'].'"';?> readonly="readonly"/>
            </td>
        </tr>
        <tr>
        	<td class="top-child_h" style="color:#148540" colspan="4">Crash Conditions</td>
        	<td>&nbsp;</td>
       		<td class="middle-left-child">Expire Date:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" id="exp_date" name="exp_date" readonly="readonly" <?php  echo 'value="'.$row3['expireDate'].'"';?>/>
         
            </td>
        </tr>
        <tr>
        	<td class="middle-child" colspan="4">&nbsp;</td>
        	<td>&nbsp;</td>
       		<td class="middle-left-child" rowspan="6">Drivers License:</td>
            <td class="middle-right-child" colspan="4" rowspan="6">
            	<?php
					
					$sql4 = "SELECT * FROM drivers_license where id = '$id'";
					$rs4 = mysql_query($sql4);
					if($row3){
				 ?>
                 	<a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="200" src="download.php?file=<?php echo $row3['loc'];?>" /></a>
               	<?php
					}
					else if($row4 = mysql_fetch_array($rs4)){
				 ?>
                 	<a target="_blank" href="download.php?file=<?php echo $row4['loc'];?>"><img width="200" src="download.php?file=<?php echo $row4['loc'];?>" /></a>
               	<?php
					}
				?>
            		 <div id="dr_upload" style="display:block" class="buttonwrapper"><a class="squarebutton" href="javascript:popacc('ins_drivers_license.php?license=<?php echo $row2['dr_license']?>&close=0');"><span>Insert/Edit Drivers License</span></a></div>
            </td>        
        </tr>
        <tr>
        	<td class="middle-left-child">Weather/Visibility Conditions</td>
            <td class="middle-none-child">
            <select name="visibility" class="fill" style="width:175px">
            	<option value="" <?php if($row2['visibility']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Clear" <?php if($row2['visibility']==="Clear"){ echo 'selected="selected"';}?> >Clear</option>
				<option value="Cloudy" <?php if($row2['visibility']==="Cloudy"){ echo 'selected="selected"';}?>>Cloudy</option>
                <option value="Rainy" <?php if($row2['visibility']==="Rainy"){ echo 'selected="selected"';}?>>Rainy</option>
                <option value="Foggy, Smoke" <?php if($row2['visibility']==="Foggy, Smoke"){ echo 'selected="selected"';}?>>Foggy, Smoke</option>
                <option value="Obstructed" <?php if($row2['visibility']==="Obstructed"){ echo 'selected="selected"';}?>>Obstructed</option>
                <option value="Other" <?php if($row2['visibility']==="Other"){ echo 'selected="selected"';}?>>Other</option>
           	</select>
            </td>
            <td class="middle-none-child">Light Conditions</td>
            <td class="middle-right-child">
            <select name="light_con" class="fill">
            	<option value="" <?php if($row2['light_con']===""){ echo 'selected="selected"';}?> ></option>
                <option value="Daylight" <?php if($row2['light_con']==="Daylight"){ echo 'selected="selected"';}?> >Daylight</option>
				<option value="Dark – lighted roadway" <?php if($row2['light_con']==="Dark – lighted roadway"){ echo 'selected="selected"';}?>>Dark – lighted roadway</option>
                <option value="Dark – Roadway not lighted" <?php if($row2['light_con']==="Dark – Roadway not lighted"){ echo 'selected="selected"';}?>>Dark – Roadway not lighted</option>
                <option value="Other" <?php if($row2['light_con']==="Other"){ echo 'selected="selected"';}?>>Other</option>
                <option value="Unknown" <?php if($row2['light_con']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
           	</select>
            </td>
           	<td>&nbsp;</td>
     	</tr>
        <tr>
        	<td class="middle-left-child">Type of Preferential Road</td>
            <td class="middle-none-child">
              <select name="type_pref_road" class="fill" style="width:175px">
              	<option value="" <?php if($row2['type_pref_road']===""){ echo 'selected="selected"';}?>></option>
            	<option value="Paved" <?php if($row2['type_pref_road']==="Paved"){ echo 'selected="selected"';}?> >Paved</option>
				<option value="Unpaved" <?php if($row2['type_pref_road']==="Unpaved"){ echo 'selected="selected"';}?>>Unpaved</option>
           	</select>
            </td>
            <td class="middle-none-child">Road Conditions</td>
            <td class="middle-right-child">
            <select name="road_con" class="fill" style="width:125px">
            	<option value="" <?php if($row2['road_con']===""){ echo 'selected="selected"';}?>></option>
            	<option value="Dry" <?php if($row2['road_con']==="Dry"){ echo 'selected="selected"';}?> >Dry</option>
				<option value="Wet" <?php if($row2['road_con']==="Wet"){ echo 'selected="selected"';}?>>Wet</option>
                <option value="Sandy" <?php if($row2['road_con']==="Sandy"){ echo 'selected="selected"';}?>>Sandy</option>
                <option value="Oil on the road" <?php if($row2['road_con']==="Oil on the road"){ echo 'selected="selected"';}?>>Oil on the road</option>
                 <option value="Debris" <?php if($row2['road_con']==="Debris"){ echo 'selected="selected"';}?>>Debris</option>
                  <option value="Other" <?php if($row2['road_con']==="Other"){ echo 'selected="selected"';}?>>Other</option>
                <option value="Unknown" <?php if($row2['road_con']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
           	</select>
            </td>
           	<td>&nbsp;</td>
     	</tr>
        <tr>
        	<td class="middle-left-child">Roadway Intersection Type</td>
            <td class="middle-right-child" colspan="3">
            <select name="intersection_type" class="fill" style=" width:175px">
            	<option value="" <?php if($row2['intersection_type']===""){ echo 'selected="selected"';}?> ></option>
            	<option value="Not at Intersection" <?php if($row2['intersection_type']==="Not at Intersection"){ echo 'selected="selected"';}?> >Not at Intersection</option>
				<option value="Four-way intersection" <?php if($row2['intersection_type']==="Four-way intersection"){ echo 'selected="selected"';}?>>Four-way intersection</option>
                <option value="T-intersection" <?php if($row2['intersection_type']==="T-intersection"){ echo 'selected="selected"';}?>>T-intersection</option>
                <option value="Y-intersection" <?php if($row2['intersection_type']==="Y-intersection"){ echo 'selected="selected"';}?>>Y-intersection</option>
                <option value="On Ramp" <?php if($row2['intersection_type']==="On Ramp"){ echo 'selected="selected"';}?>>On Ramp</option>
                <option value="Off Ramp" <?php if($row2['intersection_type']==="Off Ramp"){ echo 'selected="selected"';}?>>Off Ramp</option>
                <option value="Roundabout" <?php if($row2['intersection_type']==="Roundabout"){ echo 'selected="selected"';}?>>Roundabout</option>
                <option value="Driveway" <?php if($row2['intersection_type']==="Driveway"){ echo 'selected="selected"';}?>>Driveway</option>
                 <option value="Parkinglot" <?php if($row2['intersection_type']==="Parkinglot"){ echo 'selected="selected"';}?>>Parkinglot</option>
           	
             <option value="Other Circular Intersections" <?php if($row2['intersection_type']==="Other Circular Intersections"){ echo 'selected="selected"';}?>>Other Circular Intersections</option>
             <option value="Non Conventional Intersection" <?php if($row2['intersection_type']==="Non Conventional Intersection"){ echo 'selected="selected"';}?>>Non Conventional Intersection</option>
             <option value="Pedestrian Crossing" <?php if($row2['intersection_type']==="Pedestrian Crossing"){ echo 'selected="selected"';}?>>Pedestrian Crossing</option>
             <option value="Staggered Intersection" <?php if($row2['intersection_type']==="Staggered Intersection"){ echo 'selected="selected"';}?>>Staggered Intersection</option>
             <option value="Acute Angle Intersection" <?php if($row2['intersection_type']==="Acute Angle Intersection"){ echo 'selected="selected"';}?>>Acute Angle Intersection</option>
             <option value="Channelized Intersection" <?php if($row2['intersection_type']==="Channelized Intersection"){ echo 'selected="selected"';}?>>Channelized Intersection</option>
             <option value="Water Channel" <?php if($row2['intersection_type']==="Water Channel"){ echo 'selected="selected"';}?>>Water Channel</option>
                <option value="Other" <?php if($row2['intersection_type']==="Other"){ echo 'selected="selected"';}?>>Other</option>
                <option value="Unknown" <?php if($row2['intersection_type']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
           	</select>
            </td>
           	<td>&nbsp;</td>
     	</tr>
        <tr>
        	<td class="middle-left-child">Trafficway Description</td>
            <td class="middle-right-child" colspan="3">
            <select name="trafficway" class="fill">
            	<option value="" <?php if($row2['trafficway']===""){ echo 'selected="selected"';}?> ></option>
            	<option value="Two-way, not Divided" <?php if($row2['trafficway']==="Two-way, not Divided"){ echo 'selected="selected"';}?> >Two-way, not Divided</option>
                <option value="Two-way, divided, unprotected medium" <?php if($row2['trafficway']==="Two-way, divided, unprotected medium"){ echo 'selected="selected"';}?> >Two-way, divided, unprotected medium</option>
                <option value="Two-way, divided, protected medium" <?php if($row2['trafficway']==="Two-way, divided, protected medium"){ echo 'selected="selected"';}?> >Two-way, divided, protected medium</option>
                <option value="One-way, not divided" <?php if($row2['trafficway']==="One-way, not divided"){ echo 'selected="selected"';}?> >One-way, not divided</option>
                 <option value="Non-Trafficway Area" <?php if($row2['trafficway']==="Non-Trafficway Area"){ echo 'selected="selected"';}?> >Non-Trafficway Area</option>
                  <option value="One-Way Trafficway, Not Divided" <?php if($row2['trafficway']==="One-Way Trafficway, Not Divided"){ echo 'selected="selected"';}?> >One-Way Trafficway, Not Divided</option>
                   <option value="Two-Way Trafficway, Not Divided" <?php if($row2['trafficway']==="Two-Way Trafficway, Not Divided"){ echo 'selected="selected"';}?> >Two-Way Trafficway, Not Divided</option>
                    <option value="Entrance/Exit Ramp" <?php if($row2['trafficway']==="Entrance/Exit Ramp"){ echo 'selected="selected"';}?> >Entrance/Exit Ramp</option>
                <option value="Unknown" <?php if($row2['trafficway']==="Unknown"){ echo 'selected="selected"';}?> >Unknown</option>
           	</select>
            </td>
           	<td>&nbsp;</td>
     	</tr>
        <tr>
        	<td class="middle-left-child">Was the direction indicator used</td>
            <td class="middle-none-child"><select name="direction_indicator" class="fill" style="width:125px">
            	<option value="" <?php if($row2['direction_indicator']===""){ echo 'selected="selected"';}?> ></option>
            	<option value="Yes" <?php if($row2['direction_indicator']==="Yes"){ echo 'selected="selected"';}?> >Yes</option>
				<option value="No" <?php if($row2['direction_indicator']==="No"){ echo 'selected="selected"';}?>>No</option>
                <option value="Unknown" <?php if($row2['direction_indicator']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
              	</select>
            </td>
            <td class="middle-none-child">Driving Side</td>
            <td class="middle-right-child">
            <select name="driving_side" class="fill" style=" width:125px">
            	<option value="" <?php if($row2['driving_side']===""){ echo 'selected="selected"';}?> ></option>
            	<option value="Right" <?php if($row2['driving_side']==="Right"){ echo 'selected="selected"';}?> >Right</option>
				<option value="Left" <?php if($row2['driving_side']==="Left"){ echo 'selected="selected"';}?>>Left</option>
                <option value="Middle" <?php if($row2['driving_side']==="Middle"){ echo 'selected="selected"';}?>>Middle</option>
              	</select>
            </td>
           	<td>&nbsp;</td>
            
     	</tr>
         <tr>
        	<td class="middle-left-child" colspan="2">Vehicle Speed at moment of collision</td>
            <td class="middle-right-child" colspan="2">
            <input type="number" name="vehicle_speed" value="<?php echo $row2['vehicle_speed']?>" class="fill" size="5"/> Kmh
            </td>
           	<td >&nbsp;</td>
            <td class="middle-child" colspan="5"></td>
        <tr>
        	<td class="middle-left-child" colspan="2">Max permitted vehicle speed at moment of collision</td>
            <td class="middle-right-child" colspan="2">
            <input type="number" name="max_speed" value="<?php echo $row2['max_speed']?>" class="fill" size="5"/> Kmh
            </td>
           	<td>&nbsp;</td>
            <td colspan="5" class="middle-child">&nbsp;</td>
     	</tr>
        <tr>
        	<td colspan="4" class="bottom-child">&nbsp;</td>
            <td >&nbsp;</td>
   			<td colspan="5" class="bottom-child">&nbsp;</td>
      	</tr>
        <tr>
        	<td colspan="10">&nbsp;</td>
      	</tr>
        <tr>
        	<td colspan="4" class="top-child_h" style="color:#148540">
            	Driver Conditions
            </td>
        	<td>&nbsp;</td>
            <td colspan="5" rowspan="6">
			<table width="100%" cellspacing="0">
            <tr>
            	<td colspan="5" class="top-child_h" style="color:#148540">
                	Police Attendant Information
                </td>
            </tr>
            <tr>
            	<td colspan="2" class="middle-left-child">
                Accident reported to the police? <select name="reported_police" class="fill">
                	<option value="" <?php if($row2['reported_police']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Yes" <?php if($row2['reported_police']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['reported_police']==="No"){ echo 'selected="selected"';}?>>No</option>
                </select>
                </td>
                <td colspan="3" class="middle-right-child">
                Action pending by the police? <select name="pending_police" class="fill">
                	<option value="" <?php if($row2['pending_police']===""){ echo 'selected="selected"';}?>></option>
                    <option value="Yes" <?php if($row2['pending_police']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['pending_police']==="No"){ echo 'selected="selected"';}?>>No</option>
                </select>
                </td>
            </tr>
            <tr>
            <td class="middle-left-child" width="100">Police Attendance:</td>
            <td class="middle-none-child" width="150">
            	<select multiple="multiple" size="5" name="police[]" id="police">
                	<?php
						$sql = "SELECT * FROM `polices` WHERE `active`=1 AND `specialist`=0";
						$rs = mysql_query($sql);
						while($row = mysql_fetch_array($rs)){
							if(in_array($row['id'],$pols)){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['lastname'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['firstname'].' '.$row['lastname'].'</option>';
							}
						}
					?>
                </select>
            </td>
            <td class="middle-none-child" width="100">Specialist:</td>
            <td class="middle-right-child" width="150" colspan="2">
            	<select multiple="multiple" size="5" name="specialist[]" id="specialist">
                	<?php
						$sql = "SELECT * FROM `polices` WHERE `active`=1 AND `specialist`=1";
						$rs = mysql_query($sql);
						while($row = mysql_fetch_array($rs)){
							if(in_array($row['id'],$specs)){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['lastname'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['firstname'].' '.$row['lastname'].'</option>';
							}
						}
					?>
                </select>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="100">Accident Status:</td>
            <td class="middle-right-child" width="500" colspan="4">
            	<select name="status" style=" <?php if(strcmp($row2['status'],'')==0) {echo ';border:3px solid #FF0000;';}?>">
                	<option <?php if(strcmp($status,'')==0) {echo 'selected="selected"';}?> value=""></option>
                	<option <?php if(strcmp($status,'Pending')==0) {echo 'selected="selected"';}?> value="Pending">Pending</option>
                    <option <?php if(strcmp($status,'At Fault')==0) {echo 'selected="selected"';}?> value="At Fault">At Fault</option>
                    <option <?php if(strcmp($status,'Not At Fault')==0) {echo 'selected="selected"';}?> value="Not At Fault">Not At Fault</option>
                    <option <?php if(strcmp($status,'Subrogation')==0) {echo 'selected="selected"';}?> value="Subrogation">Subrogation</option>
                    <option <?php if(strcmp($status,'Shared Liability')==0) {echo 'selected="selected"';}?> value="Shared Liability">Shared Liability</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="100">Other RS Present:</td>
            <td class="middle-right-child" width="500" colspan="4">
            	<select name="otherrs" style="border:3px solid #FF0000;">
                	<option value="-1" <?php if($otherrs==-1){ echo 'selected="selected"';}?>>Unknown</option>
                    <option value="0" <?php if($otherrs==0){ echo 'selected="selected"';}?>>No</option>
                    <option value="1" <?php if($otherrs==1){ echo 'selected="selected"';}?>>Yes</option>
                </select>
            </td>
        </tr>
        </table>
       
            </td>   
        </tr>
       <tr>
        	<td colspan="4" class="middle-child">
            	Were you wearing a safety helmet/seatbelt? Driver <select name="driver_safety" class="fill">
                	<option value="" <?php if($row2['driver_safety']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Yes" <?php if($row2['driver_safety']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['driver_safety']==="No"){ echo 'selected="selected"';}?>>No</option>
                </select> Passenger <select name="passenger_safety" class="fill">
                	<option value="" <?php if($row2['passenger_safety']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Yes" <?php if($row2['passenger_safety']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['passenger_safety']==="No"){ echo 'selected="selected"';}?>>No</option>
                </select>
            </td>
        	<td>&nbsp;</td>
   		</tr>
         <tr>
        	<td colspan="4" class="middle-child">
            	Were you under the influence of any intoxicating substance? <select name="substance" class="fill">
                	<option value="" <?php if($row2['substance']===""){ echo 'selected="selected"';}?>></option>
                    <option value="Alcohol" <?php if($row2['substance']==="Alcohol"){ echo 'selected="selected"';}?>>Alcohol</option>
                     <option value="Drugs" <?php if($row2['substance']==="Drugs"){ echo 'selected="selected"';}?>>Drugs</option>
                      <option value="Medicines" <?php if($row2['substance']==="Medicines"){ echo 'selected="selected"';}?>>Medicines</option>
                    </select>
            </td>
        	<td>&nbsp;</td>
   		</tr>
        <tr>
        	<td class="middle-left-child">
            	Were you tired? 
           	</td>
            <td colspan="3" class="middle-right-child">
            <select name="tired" class="fill">
                	<option value="" <?php if($row2['tired']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Yes" <?php if($row2['tired']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['tired']==="No"){ echo 'selected="selected"';}?>>No</option>
                    </select> If Yes: Reason <input type="text" name="tired_reason" value="<?php echo $row2['tired_reason'];?>" class="fill" maxlength="125" size="40"/>
            </td>
        	<td>&nbsp;</td>
   		</tr>
        <tr>
        	<td class="middle-left-child">
            	Were you feeling sick?
           	</td>
            <td colspan="3" class="middle-right-child">
            <select name="sick" class="fill">
                	<option value="" <?php if($row2['sick']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Yes" <?php if($row2['sick']==="Yes"){ echo 'selected="selected"';}?>>Yes</option>
                    <option value="No" <?php if($row2['sick']==="No"){ echo 'selected="selected"';}?>>No</option>
                    </select> If Yes: Type of Sickness <input type="text" name="sick_type" value="<?php echo $row2['sick_type'];?>" class="fill" maxlength="125" size="30"/>
            </td>
        	<td>&nbsp;</td>
   		</tr>
        <tr>
        	<td colspan="2" class="middle-left-child">
            	Were you Ejected from Vehicle?
            </td>
            <td colspan="2" class="middle-right-child">
            <select name="ejected" class="fill" style="width:225px">
                	<option value="" <?php if($row2['ejected']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Not ejected" <?php if($row2['ejected']==="Not ejected"){ echo 'selected="selected"';}?>>Not ejected</option>
                    <option value="Totally ejected" <?php if($row2['ejected']==="Totally ejected"){ echo 'selected="selected"';}?>>Totally ejected</option>
                    <option value="Partially ejected" <?php if($row2['ejected']==="Partially ejected"){ echo 'selected="selected"';}?>>Partially ejected</option>
                    <option value="Not Applicable" <?php if($row2['ejected']==="Not Applicable"){ echo 'selected="selected"';}?>>Not Applicable</option>
                    <option value="Unknown" <?php if($row2['ejected']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
                    </select>
            </td>
        	<td>&nbsp;</td>
   		</tr>
        <tr>
        	<td colspan="2" class="middle-left-child">
            	Were you Trapped in Vehicle?
           	</td>
            <td colspan="2" class="middle-right-child">
            <select name="trapped" class="fill" style="width:225px">
                	<option value="" <?php if($row2['trapped']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Not Trapped" <?php if($row2['trapped']==="Not Trapped"){ echo 'selected="selected"';}?>>Not Trapped</option>
                    <option value="Freed by mechanical means" <?php if($row2['trapped']==="Freed by mechanical means"){ echo 'selected="selected"';}?>>Freed by mechanical means</option>
                    <option value="Freed by non-mechanical means" <?php if($row2['trapped']==="Freed by non-mechanical means"){ echo 'selected="selected"';}?>>Freed by non-mechanical means</option>
                    <option value="Unknown" <?php if($row2['trapped']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
                    </select>
            </td>
        	<td>&nbsp;</td>
            <td colspan="5" class="middle-child">&nbsp;</td>
   		</tr>
        <tr>
        	<td colspan="2" class="middle-left-child">
            	Were you Injured?
           	</td>
            <td colspan="2" class="middle-right-child">
            <select name="injured" class="fill" style="width:225px">
                	<option value="" <?php if($row2['injured']===""){ echo 'selected="selected"';}?>></option>
                	<option value="Fatal injury" <?php if($row2['injured']==="Fatal injury"){ echo 'selected="selected"';}?>>Fatal injury</option>
                    <option value="Non fatal injury" <?php if($row2['injured']==="Non fatal injury"){ echo 'selected="selected"';}?>>Non fatal injury</option>
                    <option value="Incapacitating" <?php if($row2['injured']==="Incapacitating"){ echo 'selected="selected"';}?>>Incapacitating</option>
                    <option value="Non incapacitating" <?php if($row2['injured']==="Non incapacitating"){ echo 'selected="selected"';}?>>Non incapacitating</option>
                    <option value="No injury" <?php if($row2['injured']==="No injury"){ echo 'selected="selected"';}?>>No injury</option>
                    <option value="Possible" <?php if($row2['injured']==="Possible"){ echo 'selected="selected"';}?>>Possible</option>
                    <option value="Unknown" <?php if($row2['injured']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
                    </select>
            </td>
        	<td>&nbsp;</td>
            <td colspan="5" class="middle-child">&nbsp;</td>
   		</tr>   
        <tr>
        	<td colspan="2" class="bottom-left-child">
            	Were you transported for Medical Care? 
          	</td>
            <td colspan="2" class="bottom-right-child">
                <select name="med_care" class="fill" style="width:225px">
                	<option value="" <?php if($row2['med_care']===""){ echo 'selected="selected"';}?>></option>
                	<option value="EMS Ambulance" <?php if($row2['med_care']==="EMS Ambulance"){ echo 'selected="selected"';}?>>EMS Ambulancey</option>
                    <option value="Not transported" <?php if($row2['med_care']==="Not transported"){ echo 'selected="selected"';}?>>Not transported</option>
                    <option value="Police" <?php if($row2['med_care']==="Police"){ echo 'selected="selected"';}?>>Police</option>
                    <option value="Other" <?php if($row2['med_care']==="Other"){ echo 'selected="selected"';}?>>Other</option>
                    <option value="Unknown" <?php if($row2['med_care']==="Unknown"){ echo 'selected="selected"';}?>>Unknown</option>
                    </select>
            </td>
        	<td>&nbsp;</td>
            <td colspan="5">
             <table width="100%" cellspacing="0">
                <tr>
                	<td width="20%" class="bottom-left-child">
            		<div class="buttonwrapper"><a class="squarebutton" href="add_police.php"><span>Add Police</span></a></div>
                	</td>
                    <td width="20%" class="bottom-center-child">
            		<div class="buttonwrapper"><a class="squarebutton" href="edit_police.php"><span>Edit Police</span></a></div>
                	</td>
                    <td width="20%" class="bottom-center-child"><div class="buttonwrapper"><a class="squarebutton" onclick="window.close

();"><span>Close</span></a></div>
            		
                	</td>
                    <td width="20%" align="right" class="bottom-right-child"><input type="submit" name="Submit" value="submit"/></td>
                </tr>
                </table>
          	</td>
   		</tr>
        <tr><td colspan="9">&nbsp;</td></tr>
        <tr>
        	<td colspan="4" class="top-child_h" style="color:#148540">
            	Remarks Vehicle Driver
            </td>
        	<td>&nbsp;</td>
            <td colspan="5" class="top-child_h" style="color:#148540">
             	Remarks General
            </td>
       </tr> 
       <tr>
        	<td colspan="4" class="bottom-child">
            	<textarea name="remarks_a" cols="70" rows="12" style="background-color:#FAD090; font-weight:bold; font-size:14px <?php if(strlen(trim($row2['remarks_a']))<3) {echo ';border:3px solid #FF0000;';}?>"><?php echo stripcslashes($row2['remarks_a']);?></textarea>
          	</td>
       		<td>&nbsp;</td>
            <td colspan="5" class="bottom-child">
             	<textarea name="remarks_general" cols="60" rows="12" style="background-color:#FAD090; font-weight:bold; font-size:14px <?php if(strlen(trim($row2['remarks_general']))<3) {echo ';border:3px solid #FF0000;';}?>"><?php echo stripcslashes($row2['remarks_general']);?></textarea>
            </td>
    	</tr>
        
   	</table>
</form>
<script type="text/javascript" src="js/functions.js">
</script>
<script type="text/javascript">

	function pol_holder(){
		if(document.getElementById('ph').checked){
			document.getElementById('fname').disabled = true;
			document.getElementById('lname').disabled = true;	
			document.getElementById('email').disabled = true;	
			document.getElementById('address').disabled = true;	
			document.getElementById('bday').disabled = true;	
			document.getElementById('bday_button').disabled = true;	
			document.getElementById('bplace').disabled = true;	
			document.getElementById('gender').disabled = true;	
			document.getElementById('phone').disabled = true;	
			document.getElementById('mobile').disabled = true;	
			document.getElementById('dr_license').disabled = true;	
			document.getElementById('exp_date').disabled = true;	
			document.getElementById('exp_button').disabled = true;	
			document.getElementById('dr_upload').style.display = 'none';				
		}
		else{
			document.getElementById('fname').disabled = false;	
			document.getElementById('lname').disabled = false;
			document.getElementById('email').disabled = false;	
			document.getElementById('address').disabled = false;
			document.getElementById('bday').disabled = false;	
			document.getElementById('bday_button').disabled = false;
			document.getElementById('bplace').disabled = false;	
			document.getElementById('gender').disabled = false;	
			document.getElementById('phone').disabled = false;	
			document.getElementById('mobile').disabled = false;	
			document.getElementById('dr_license').disabled = false;	
			document.getElementById('exp_date').disabled = false;	
			document.getElementById('exp_button').disabled = false;
			document.getElementById('dr_upload').style.display = 'inline';		
		}
	}

</script>
</body>
</html>