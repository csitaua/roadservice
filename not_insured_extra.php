<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css" href="anytime/anytime.css" />
<script src="anytime/jquery-1.6.4.min.js"></script>
<script src="anytime/anytime.js"></script>
<script language="javascript" src="support/calendar/calendar.js"></script>
<title>Nagico Road and Claims Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="menu/helper.css" media="screen" rel="stylesheet" type="text/css" />
<link href="menu/css/dropdown/dropdown.css" media="all" rel="stylesheet" type="text/css" />
<link href="menu/default.advanced.css" media="all" rel="stylesheet" type="text/css" />
<script src="validator/gen_validatorv4.js" type="text/javascript"></script>
</head>
<body>
<?php
	include 'dbc.php';
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	$id = $_REQUEST['sc'];
	$sid = $_REQUEST['sr'];
	$sql = "SELECT * FROM `non_client_extra` WHERE `id`='$id'";
	$rs = mysql_query($sql);
	
	$sql2 = "SELECT * FROM service_req WHERE id = '$sid'";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	
	if(strcmp(($_REQUEST['Submit']),'submit')==0){
		if(mysql_num_rows($rs) > 0){ //update
			$extra='';
			if($_POST['cat_value']){
				$extra.=", `cat_value`=".$_POST['cat_value'];	
			}
			$sql = "UPDATE `non_client_extra` SET `lname`='".mysql_real_escape_string($_REQUEST['lname'])."', `fname`='".mysql_real_escape_string($_REQUEST['fname'])."', `address`='".mysql_real_escape_string($_REQUEST['address'])."', `phone`='".mysql_real_escape_string($_REQUEST['phone'])."', `mobile`='".mysql_real_escape_string($_REQUEST['mobile'])."', `year`='".mysql_real_escape_string($_REQUEST['year'])."', `color`='".mysql_real_escape_string($_REQUEST['color'])."', `clientNo`='".mysql_real_escape_string($_REQUEST['clientNo'])."', `insured_from`='".mysql_real_escape_string($_REQUEST['insured_from'])."', `insured_to`='".mysql_real_escape_string($_REQUEST['insured_to'])."', `vehicle_use`='".mysql_real_escape_string($_REQUEST['vehicle_use'])."', `vehicle_coverage`='".mysql_real_escape_string($_REQUEST['vehicle_coverage'])."', `make`='".$_REQUEST['make']."', `model`='".$_REQUEST['model']."', `PolicyNo`='".$_REQUEST['policyNo']."', `insured_at`=".$_REQUEST['insured_at'].", `licenseNo`='".$_REQUEST['licenseNo']."', `body_type`='".$_POST['body_type']."', `seats`='".$_POST['seats']."' ".$extra." WHERE `id`='$id'";
		}
		else{
			$cat_value=0;
			if($_POST['cat_value']){
				$cat_value=$_POST['cat_value'];	
			}
			$seats=0;
			if($_POST['seats']){
				$seats=$_POST['seats'];	
			}
			$sql = "INSERT INTO `non_client_extra` (`id`,`fname`,`lname`,`address`,`phone`,`mobile`,`year`,`color`, `clientNo`, `insured_from`, `insured_to`, `vehicle_use`, `vehicle_coverage`, `make`, `model`, `PolicyNo`, `insured_at`, `licenseNo`, `cat_value`, `body_type`, `seats`) VALUES ('$id', '".mysql_real_escape_string($_REQUEST['fname'])."', '".mysql_real_escape_string($_REQUEST['lname'])."','".mysql_real_escape_string($_REQUEST['address'])."','".mysql_real_escape_string($_REQUEST['phone'])."','".mysql_real_escape_string($_REQUEST['mobile'])."','".mysql_real_escape_string($_REQUEST['year'])."','".mysql_real_escape_string($_REQUEST['color'])."', '".mysql_real_escape_string($_REQUEST['clientNo'])."', '".mysql_real_escape_string($_REQUEST['insured_from'])."', '".mysql_real_escape_string($_REQUEST['insured_to'])."', '".mysql_real_escape_string($_REQUEST['vehicle_use'])."', '".mysql_real_escape_string($_REQUEST['vehicle_coverage'])."', '".$_REQUEST['make']."', '".$_REQUEST['model']."', '".$_REQUEST['policyNo']."', ".$_REQUEST['insured_at'].", '".$_REQUEST['licenseNo']."', '$cat_value', '".$_POST['body_type']."', $seats )";
		}
		mysql_query($sql);
		//echo mysql_error().'<br/>'.$sql;
		echo '<script type="text/javascript">window.close();</script>';
	}
	
	$sql = "SELECT * FROM `non_client_extra` WHERE `id`='$id'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	
?>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<form name="acc_extra" action="" method="post">
	<table width="500" cellspacing="0">
    	<tr>
        	<td colspan="5" class="top-child_h" align="center" style="color:#148540"><h3>Extra Information Non Client</h3></td>
        </tr>
        <tr><td colspan="5" class="middle-child">&nbsp;</td></tr>
        <tr>
        	<td width="175" class="middle-left-child">First Name:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="fname" size="20" value="<?php echo $row['fname']?>"/></td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Last Name:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="lname" size="20" value="<?php echo $row['lname']?>"/></td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">License No:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="licenseNo" size="20" value="<?php 
			if(trim($row['licenseNo'])===''){
				echo $row2['licenseNo'];
			}
			else{
				echo $row['licenseNo'];
			}?>"/></td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Address:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="address" size="30" value="<?php echo $row['address']?>"/></td>
        </tr>
        
        <tr>
        	<td width="175" class="middle-left-child">Phone/Mobile:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="tel" name="phone" size="10" value="<?php echo $row['phone']?>"/>/<input type="tel" name="mobile" size="10" value="<?php echo $row['mobile']?>"/></td>
        </tr>
         <tr>
        	<td width="175" class="middle-left-child">Client No:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="clientNo" size="30" maxlength="15" value="<?php echo $row['clientNo']?>"/>
            </td>
        </tr>
         <tr>
        	<td width="175" class="middle-left-child">Vehicle Coverage:</td>
            <td width="425" colspan="4" class="middle-right-child"><select name="vehicle_coverage">
            	<option <?php if( strcmp($row['vehicle_coverage'],'C')==0) echo 'selected="selected" '; ?> value="C">Comprehensive</option>
                <option <?php if( strcmp($row['vehicle_coverage'],'CS')==0) echo 'selected="selected" '; ?> value="CS">Comprehensive Super Cover</option>
                <option <?php if( strcmp($row['vehicle_coverage'],'TP')==0) echo 'selected="selected" '; ?> value="TP">Third Party</option>
                <option <?php if( strcmp($row['vehicle_coverage'],'TPC')==0) echo 'selected="selected" '; ?> value="TPC">Third Party Limited Comprehensive</option>
                 <option <?php if( strcmp($row['vehicle_coverage'],'NC')==0) echo 'selected="selected" '; ?> value="NC">No Coverage</option>
        	</select>
            </td>
        </tr>
          <tr>
        	<td width="175" class="middle-left-child">Vehicle Use:</td>
            <td width="425" colspan="4" class="middle-right-child">
            <select name="vehicle_use">
            	<option <?php if( strcmp($row['vehicle_use'],'private')==0) echo 'selected="selected" '; ?> value="private">Private</option>
                <option  <?php if( strcmp($row['vehicle_use'],'commercial')==0) echo 'selected="selected" '; ?> value="commercial">Commercial</option>
            </select>
            </td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Policy No:</td>
            <td width="425" colspan="4" class="middle-right-child">
            <input type="text" name="policyNo" size="25" value="<?php 
			if(trim($row['PolicyNo'])===''){
				echo $row2['pol'];
			}
			else{
				echo $row['PolicyNo'];
			}
			?>"/>
            </td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Insured At:</td>
            <td width="425" colspan="4" class="middle-right-child">
            <select name="insured_at" id="insured_at">
            	<option value="0" <?php if($row['insured_at']==0){ echo 'selected="selected"';}?>></option>
            	<option value="1" <?php if($row['insured_at']==1){ echo 'selected="selected"';}?>>No Insurance</option>
                <?php
					$insured_at=$row['insured_at'];
					if($row['insured_at']==0){
						$insured_at=$row2['insured_at'];
					}
					$sql3 = "SELECT * FROM insurance_company WHERE id != 1 ORDER BY name asc";
					$rs3 = mysql_query($sql3);
					while($row3=mysql_fetch_array($rs3)){
						if($insured_at==$row3['id']){
							echo '<option value="'.$row3['id'].'" selected="selected">'.$row3['name'].'</option>';
						}
						else{
							echo '<option value="'.$row3['id'].'">'.$row3['name'].'</option>';	
						}
					}
				?>
            </select>
            </td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Make/Model:</td>
            <td width="425" colspan="4" class="middle-right-child">
            <?php
				list($make,$model)=explode(" ",$row2['car']);
			?>
            <input type="text" name="make" size="18" value="<?php 
			if(trim($row['make'])===''){ 
				echo $make; 
			} 
			else{ 
				echo $row['make'];
			}
			?>"/>/<input type="text" name="model" size="18" value="<?php if(trim($row['model'])===''){ echo $model;} else{echo $row['model'];}?>"/></td>
        </tr>
         <tr>
          <tr>
        	<td width="175" class="middle-left-child">Year/Color:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="year" size="10" value="<?php echo $row['year']?>"/>/<input type="text" name="color" size="10" value="<?php echo $row['color']?>"/></td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Body Type/Seats:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="body_type" name="body_type" size="10" value="<?php echo $row['body_type']?>"/>/<input type="text" name="seats" size="10" value="<?php echo $row['seats']?>"/></td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Cat Value:</td>
            <td width="425" colspan="4" class="middle-right-child"><input type="text" name="cat_value" size="10" value="<?php echo $row['cat_value']?>"/></td>
        </tr>
         <tr>
        	<td width="175" class="middle-left-child">Car Insured From:</td>
            <td width="425" colspan="4" class="middle-right-child">
             <?php
                        require_once('support/calendar/classes/tc_calendar.php');
                        $myCalendar = new tc_calendar("insured_from", true);
                        $myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
                        list($year,$month,$day) = explode("-",$row['insured_from']);
                        if( !(is_null($year))){
                            //Date NOT Change	
                            $myCalendar->setDate(intval($day),intval($month),intval($year));
                        	
						}
                        else{
                            $myCalendar->setDate(date('d'), date('m'), date('Y'));
                        }
                        $myCalendar->setPath("support/calendar/");
                        $myCalendar->setYearInterval(date('Y')-1, date('Y'));
                        //$myCalendar->setYearInterval('2011', '2013');
                        $myCalendar->dateAllow((date('Y')-2).'-'.(date('m')-1).'-'.date('d'), (date('Y')+1).'-'.(date('m')-1).'-'.date('d'));
                        //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
                        $myCalendar->startMonday(true);
                        $myCalendar->setAlignment('left', 'top');
                        $myCalendar->writeScript();
                    ?>
            </td>
        </tr>
        <tr>
        	<td width="175" class="middle-left-child">Car Insured To:</td>
            <td width="425" colspan="4" class="middle-right-child">
             <?php
                        require_once('support/calendar/classes/tc_calendar.php');
                        $myCalendar = new tc_calendar("insured_to", true);
                        $myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
                        list($year,$month,$day) = explode("-",$row['insured_to']);
                        if( !(is_null($year))){
                            //Date NOT Change	
                            $myCalendar->setDate($day,$month,$year);
                        }
                        else{
                            $myCalendar->setDate(date('d'), date('m'), date('Y'));
                        }
                        $myCalendar->setPath("support/calendar/");
                        $myCalendar->setYearInterval(date('Y')-1, date('Y')+2);
                        //$myCalendar->setYearInterval('2011', '2013');
                        $myCalendar->dateAllow((date('Y')-1).'-'.(date('m')-1).'-'.date('d'), (date('Y')+2).'-'.(date('m')).'-'.date('d'));
                        //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
                        $myCalendar->startMonday(true);
                        $myCalendar->setAlignment('left', 'top');
                        $myCalendar->writeScript();
                    ?>
            </td>
        </tr>
        <tr><td colspan="5" class="bottom-child"><input type="submit" name="Submit" value="submit" />
   	</table>
</form>

</body>
</html>