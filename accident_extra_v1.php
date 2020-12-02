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
	}
	$licenseNo = $row2['dr_license'];
    $sql3 = "SELECT * FROM drivers_license where id = '$licenseNo'";
	$rs3 = mysql_query($sql3);
	$row3 = mysql_fetch_array($rs3);
?>
<form name="acc_extra" action="rec_extra.php?sc=<?php echo $id;?>" method="post">
	<table width="600" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Accident Extra Information Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h3></div></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td class="top-child" colspan="5">
            	<table width="100%">
                <tr>
                	<td width="20%">
            		<div class="buttonwrapper"><a class="squarebutton" href="add_police.php"><span>Add Police</span></a></div>
                	</td>
                    <td width="20%">
            		<div class="buttonwrapper"><a class="squarebutton" href="edit_police.php"><span>Edit Police</span></a></div>
                	</td>
                    <td width="20%">&nbsp;
            		
                	</td>
                    <td width="20%" align="right"><div class="buttonwrapper"><a class="squarebutton" onclick="window.close();"><span>Close</span></a></div></td>
                </tr>
                </table>
           	</td>
        </tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
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
            	<select name="status">
                	<option <?php if(strcmp($status,'Pending')==0) {echo 'selected="selected"';}?> value="Pending">Pending</option>
                    <option <?php if(strcmp($status,'At Fault')==0) {echo 'selected="selected"';}?> value="At Fault">At Fault</option>
                    <option <?php if(strcmp($status,'Not At Fault')==0) {echo 'selected="selected"';}?> value="Not At Fault">Not At Fault</option>
                    <option <?php if(strcmp($status,'Subrogation')==0) {echo 'selected="selected"';}?> value="Subrogation">Subrogation</option>
                    <option <?php if(strcmp($status,'Shared Liability')==0) {echo 'selected="selected"';}?> value="Shared Liability">Shared Liability</option>
                </select>
            </td>
        </tr>
        <tr><td class="bottom-child" colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="top-child_h" style="color:#148540" colspan="5">Accident Drivers Information Accident</td></tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
         <tr>
       		<td class="middle-left-child">Drivers License:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="dr_license" id="dr_license" maxlength="25" size="25" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['dr_license'].'"';?> class="fill" required="required" />
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Policy Holder:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="checkbox" <?php if($row2['ph']==0){}else{echo 'checked="checked"';}?> name="ph" id="ph" onchange="pol_holder()"/>
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">First Name:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="fname" id="fname" maxlength="50" size="35" <?php 
				echo 'value="'.$row3['firstName'].'"'; ?> readonly="readonly" />
            </td>
       	</tr>
       	<tr>
       		<td class="middle-left-child">Last Name:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="lname" id="lname" maxlength="50" size="35" <?php echo 'value="'.$row3['lastName'].'"';?> readonly="readonly" />
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Email:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="email" name="email" id="email" maxlength="75" size="45" <?php  echo 'value="'.$row3['email'].'"';?> readonly="readonly" />
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Address:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="address" id="address" maxlength="75" size="45" <?php  echo 'value="'.$row3['address'].'"';?> readonly="readonly"/>
            </td>
       	</tr>
       	<tr>
       		<td class="middle-left-child">Birth Day:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" id="bday" name="bday" readonly="readonly" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['bday'].'"';?>/>
              	<button id="bday_button" <?php if($row2['ph']!=0){echo 'disabled="disabled"';}?>>
                    <img src="anytime/calendar.png" alt="[calendar icon]"/>
               	</button>
              	<script>
					$('#bday_button').click(
					  function(e) {
						$('#bday').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
						e.preventDefault();
					  } );
				</script>
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Birth Place:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" name="bplace" id="bplace" maxlength="75" size="35" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['bplace'].'"';?> />
            </td>
       	</tr>
       	<tr>
       		<td class="middle-left-child">Gender:</td>
            <td class="middle-right-child" colspan="4">
            	<select name="gender" id="gender" <?php if($row2['ph']!=0){echo 'disabled="disabled"';}?>>
                	<option value="Male" <?php if(strcmp($row2['gender'],'Male')==0){echo 'selected="selected"';}?>>Male</option>
                    <option value="Female" <?php if(strcmp($row2['gender'],'Female')==0){echo 'selected="selected"';}?>>Female</option>
                </select>
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Phone / Cell:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="tel" name="phone" id="phone" size="15" maxlength="15" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['phone'].'"';?>/>&nbsp;/&nbsp;<input type="tel" name="mobile" id="mobile" size="15" maxlength="15" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['mobile'].'"';?>/>
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Expire Date:</td>
            <td class="middle-right-child" colspan="4">
            	<input type="text" id="exp_date" name="exp_date" readonly="readonly" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$row2['dr_exp'].'"';?>/>
              	<button id="exp_button"  <?php if($row2['ph']!=0){echo 'disabled="disabled"';}?>>
                    <img src="anytime/calendar.png" alt="[calendar icon]"/>
               	</button>
              	<script>
					$('#exp_button').click(
					  function(e) {
						$('#exp_date').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
						e.preventDefault();
					  } );
				</script>
            </td>
       	</tr>
        <tr>
       		<td class="middle-left-child">Drivers License:</td>
            <td class="middle-right-child" colspan="4">
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
					else{
				?>
            		 <div id="dr_upload" style="display:block" class="buttonwrapper"><a class="squarebutton" href="javascript:popacc('ins_drivers_license.php?license=<?php echo $id?>&close=0');"><span>Insert Drivers License</span></a></div>
              	<?php
					}
				?>
            </td>
       	</tr>
        <tr><td class="bottom-child" colspan="5"><input type="submit" name="Submit" value="submit" /></td></tr>
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