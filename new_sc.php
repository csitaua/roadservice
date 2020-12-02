<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
if($_SESSION['user_level'] < RR_LEVEL){
	header("Location: index.php");
	exit();
}

session_start();

echo menu();
$col1 = 100;
$col2 = 225;
$vcol1 = 45;
$vcol2 = 55;
$lic='';
$car = '';
$policy = '';
$location = '';
$insured = 0;
$licenseNo = '';
$link_pos=$_REQUEST['l'];
if($_REQUEST['lic']){
	$lic = $_REQUEST['lic'];
	$policy=$_REQUEST['pn'];
	$extra='';
	if(trim($policy)!==''){
		$extra=" AND PolicyNo='$policy' ";
	}
	/*$sql = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY VehStatus, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";
	$rs = mysql_query($sql);*/
	$sql = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' ".$extra." ORDER BY
				CASE	WHEN VehStatus='A' THEN 1
						WHEN VehStatus='L' THEN 2
						WHEN VehStatus='C' THEN 3
				End, Date_Renewal DESC, PolicyNo DESC";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$rs = mssql_query($sql);
	if(mssql_num_rows($rs)!=0){
		$row = mssql_fetch_array($rs);
		$car =  $row['Make'].' '.$row['Model'];
		$policy = $row['PolicyNo'];
		$clientno = $row['ClientNo'];
		$vin = $row['VinNo'];
		$veh_year= $row['YearMake'];
		if(strcmp(trim($row['VehStatus']),'A')==0){
			$insured = 1;
		}
		$licenseNo = $row['Driver1_License'];
		$clientNo = $row['ClientNo'];
		if(trim($licenseNo)===''){
			$sql11="SELECT * FROM VW_CLIENTS
  WHERE ClientNo='$clientNo'";
  			$rs11=mssql_query($sql11);
			$row11=mssql_fetch_array($rs11);
			$licenseNo=$row11['LicenseNo'];
		}
	}
	else{
		$sql = "SELECT * FROM service_req WHERE `a_number`='$lic' order by id desc";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)!=0){
			$row = mysql_fetch_array($rs);
			$car =  $row['car'];
			$policy = $row['pol'];
			$clientno = $row['ClientNo'];
			$vin = $row['vin'];
			$t = $row['id'];
			$licenseNo = $row['licenseNo'];
		}
	}
}
$idm=0;
$district = '';
$attendee = '';
$requestedBy = '';
$open = '';
$acc_link = 0;
if($_REQUEST['id']){
	$idm=$_REQUEST['id'];
	$sql = "SELECT * FROM service_req where id='$idm'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	//$car = $row['car'];
	//$policy = $row['pol'];
	//$insured = $row['insured'];
	//$lic = $row['a_number'];
	$location = $row['location'];
	$district = $row['district'];
	$attendee = $row['attendee_id'];
	$requestedBy = $row['requestedBy'];
	$open = $row['opendt'];
	$acc_link = $idm;
}

//Towing
if($_REQUEST['id'] && get_job_type($_REQUEST['job'])==5){
	$idm=$_REQUEST['id'];
	$sql = "SELECT * FROM service_req where id='$idm'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$car = $row['car'];
	$policy = $row['pol'];
	$insured = $row['insured'];
	$lic = $row['a_number'];
	$location = $row['location'];
	$requestedBy = $row['requestedBy'];
	$addPhone = $row['AddPhone'];
	$acc_link = 0;
}

//New Survey
if($_REQUEST['id'] && $_REQUEST['job']==43){
	$idm=$_REQUEST['id'];
	$sql = "SELECT * FROM service_req where id='$idm'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$car = $row['car'];
	$policy = $row['pol'];
	$insured = $row['insured'];
	$lic = $row['a_number'];
	$claimNo = $rop['claimNo'];
	$addPhone = $row['AddPhone'];
	$location='';
	$attendee=32;
}


?>

<form name="new_sc" action="rec_sc.php" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">New Service Request</h8></td>
        </tr>
        <tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        <td colspan="5">&nbsp;<input type="hidden" name="idm" value="<?php echo $idm;?>"/>
        <input type="hidden" name="acc_link" value="<?php echo $acc_link;?>"/>
        <input type="hidden" name="link_pos" value="<?php echo $link_pos;?>"/>
        </td>
        </tr>
        <tr>
        	<td colspan="4" class="top-child_h" style="color:#148540;"><h4>Service Call Details</h4></td>
                    <td rowspan="15" valign="top">
            <table width="100%" cellspacing="0">
            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td></tr>
            	<tr><td class="middle-child" colspan="2">&nbsp;</td></tr>
                <?php
				/*$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY VehStatus, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";
				$rs2 = mysql_query($sql2);*/
				$sql2 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' ".$extra." ORDER BY CASE
				WHEN VehStatus='A' THEN 1
						WHEN VehStatus='L' THEN 2
						WHEN VehStatus='C' THEN 3
				End, Date_Renewal DESC, PolicyNo DESC";
				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$rs2 = mssql_query($sql2);

				if(mssql_num_rows($rs2)==0 && $lic !== ''){
					$sql3 = "SELECT * FROM non_client_extra WHERE id = '$lic'";
					$rs3 = mysql_query($sql3);

				}
				if((mssql_num_rows($rs2)!=0 || mysql_num_rows($rs3)!=0) && $lic !== ''){
					$vcol1 = 45;
					$vcol2 = 55;
					$row2 = mssql_fetch_array($rs2);
					$row3 = mysql_fetch_array($rs3);
				?>
                	<tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Full Name:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['Full_Name'].$row3['fname'].' '.$row3['lname']; $cname = $row2['Full_Name'];?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Client No:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['ClientNo'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Address:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['Address1'].$row3['address'];
						$address = $row2['Address1'].$row3['address'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insurance Status:</td>
                        <td class="middle-right-child" <?php if(strcmp($row2['VehStatus'],'A')==0){
				echo 'style="color:#0C0;font-weight:bold;font-size:18px"' ;
			} ?> width="<?php echo $vcol2;?>%"><?php echo $row2['VehStatus'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured Since:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo substr($row2['Date_Application'],0,10);?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insurance Period:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						$fdate = new datetime(substr(date("d-M-y", strtotime($row2['VehDate_Effective'])),0,10));
						$tdate = new datetime(substr(date("d-M-y", strtotime($row2['VehDate_Renewal'])),0,10));
						echo date_format($fdate,"d M, Y").' - '.date_format($tdate,"d M, Y");
						?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Year / Color:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['YearMake'].' / '.$row2['Color'];?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Vehicle Coverage/Use:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['VehCoverage'].'/'.$row2['VehUse'];?></td>
                    </tr>
                      <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Total Premium/Balance:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
							if($_SESSION['user_level'] >= POWER_LEVEL){
								echo number_format((float)$row2['Premium']+(float)$row2['PolicyFee'],2);
								if($row2['AmountDeb'] > 0){
									echo ' / <a style="color:red">'.number_format($row2['AmountDeb'],2).'</a>';
								}
								else if($row2['AmountDeb'] < 0){
									echo ' / '.number_format($row2['AmountDeb'],2);
								}
							}
						?></td>
                    </tr>
                    <?php
							$phone = '';
							if(strcmp(trim($row2['WorkPhone']),'')!=0){
								$phone = $row2['WorkPhone'];
							}
							if(strcmp(trim($row2['HomePhone']),'')!=0){
								if(strcmp($phone,'')==0){
									$phone = $row2['HomePhone'];
								}
								else{
									$phone = $phone.' / '.$row2['HomePhone'];
								}
							}
							if(strcmp(trim($row2['MobilePhone']),'')!=0){
								if(strcmp($phone,'')==0){
									$phone = $row2['MobilePhone'];
								}
								else{
									$phone = $phone.' / '.$row2['MobilePhone'];
								}
							}
						$cmobile = $row2['MobilePhone'];
					?>
                    	<tr>
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Phone Number(s):</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $phone;?></td>
                    	</tr>
                        <tr>
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Agent/Broker:</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['AgentName'];?></td>
                    	</tr>
                         <tr>
                    		<td class="bottom-left-child" width="<?php echo $vcol1;?>%">Blacklisted:</td>
                        	<td class="bottom-right-child" width="<?php echo $vcol2;?>%"><?php
								$clientno = $row2['ClientNo'];
								$row4 = "SELECT * FROM blacklist WHERE a_number='$lic' AND ClientNo='$clientno'";
								$rs4 = mysql_query($row4);
								if(mysql_num_rows($rs4) != 0){
									echo 'Yes';
								}
								else{
									echo 'No';
								}
							?></td>
                    	</tr>
                <?php }
				else{
					echo '<tr><td class="bottom-child" colspan="2">&nbsp;</td></tr>';
				}?>


            </table>
            </td>

        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $car;?>" style="background-color:#FAD090" name="car" size="25" /></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Car Number:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $lic;?>" style="background-color:#FAD090" name="num" id="num" size="15" /> <?php
            	if($_REQUEST['l'] >= 1){
					echo ' <div class="buttonwrapper">
					<a class="squarebutton"  onClick="checkLicense()"><span>Check</span></a>';
				}
			?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Year:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $veh_year;?>" style="background-color:#FAD090" name="veh_year" size="25" /></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="loc" id="loc" size="25" value="<?php echo $location;?>" /> VII: <input type="checkbox" name="a_req2" id="a_req2" onchange="copyAddress2()"/></td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">Job Group:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><select name="jobs_group" id="jobs_group" style="background-color:#FAD090" onchange="updatejobs(this.selectedIndex)">
           <option value="0">All</option>
            <?php
				$sql = "SELECT * FROM jobs_group order by description ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';
				}
			?>
        </select>
        </td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">
            <?php
				if($_REQUEST['job']==43){echo 'Body Shop:';}
				else { echo 'Location To:';}
			?>

            </td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <?php
				if($_REQUEST['job']==43){
			?>
            	<input type="text" id="toloc" name="toloc" style="background-color:#FAD090">
            <?php }
			else{
			?><input type="text" id="toloc" name="toloc" style="background-color:#FAD090; display:none"> VII: <input type="checkbox" name="a_req" id="a_req" onchange="copyAddress()"/><?php } ?></td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Job:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><select name="job" id="jobs" style="background-color:#FAD090" onchange="display(this)">
                    <?php
						$sql = "SELECT * FROM jobs order by description";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($_REQUEST['job'] == $row['id']){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['description'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';
							}
						}
					?>
                    </select><input type="hidden" name="towing" id="towing" value=""/></td>
        </tr>
         <tr>
            <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="attendee" id="attendee" style="background-color:#FAD090">
            	<option value=""></option>
                    <?php
						$sql = "SELECT * FROM attendee WHERE id != 10 AND active=1 order by s_name ";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($row['id'] == $attendee){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['s_name'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['s_name'].'</option>';
							}
						}
					?>
                     <?php if($_SESSION['user_level'] >= POWER_LEVEL){ ?></select>&nbsp;NSH: <input type="checkbox" name="over_time" /><?php } ?></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">District:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><select name="district" id="district" style="background-color:#FAD090">
            		<option value=""></option>
                    <?php
						$sql = "SELECT * FROM districts order by district";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($row['id'] == $district){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['district'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['district'].'</option>';
							}
						}
					?>
                    </select>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <?php
				if($_REQUEST['job']==43){
			?>
            	<select style="background-color:#FAD090" name="requestedBy" id="requestedBy">
            	<?php
					$sql2 = "SELECT * FROM rental_request WHERE active=1";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
					}
				?>
            </select>
            <?php }
			else{
				?>
            <input type="text" style="background-color:#FAD090" name="requestedBy" id="requestedBy" size="23" maxlength="50"/> VII: <input type="checkbox" name="c_req" id="c_req" onchange="copyName()"/><?php } ?></td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">Insured:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="checkbox" <?php if($insured!=0) {echo 'checked="checked"';}?> style="background-color:#FAD090" name="insured" id="insured" onchange="isInsured()"/>&nbsp;<select name="insured_at" id="insured_at" style="background-color:#FAD090; display:none">
            	<option value="1">N/A</option>
                <?php
					$sql2 = "SELECT * FROM insurance_company WHERE id != 1 ORDER BY name asc";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
					}
				?>
            </select>
            </td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>"> <?php
				if($_REQUEST['job']==43){ echo 'Charged:';}
				else{ echo 'Settlement:';}
			?></td>
           	<td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" value="0.00" style="background-color:#FAD090; text-align:right" name="charged" size="10"/> Afl.</td>
           <td class="middle-none-child" width="<?php echo $col1;?>">Drivers License:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $licenseNo;?>" style="background-color:#FAD090" name="licenseNo" size="20"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090;" name="addphone" id="addphone" size="18" value="<?php echo $addPhone;?>"/> VII: <input type="checkbox" name="c_mob" id="c_mob" onchange="copyPhone()"/></td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Policy Number:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $policy;?>" style="background-color:#FAD090" name="pol" size="20"/> <input type="hidden" name="clientno" value="<?php echo $clientno;?>" /> </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Tow Reason:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="tow_reason" id="tow_reason" style="background-color:#FAD090;display:none;">
            	<option value=""></option>
            	<?php
					$sql4 = "SELECT * FROM `towing_reason` ORDER BY `description`";
					$rs4 = mysql_query($sql4);
					while($row4 = mysql_fetch_array($rs4)){
				?>
                	<option value="<?php echo $row4['id'];?>"><?php echo $row4['description'];?></option>
                <?php } ?>
            </select></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Vin:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090;" name="vin"  value="<?php echo $vin;?>" size="30"/></td>
        </tr>
        <tr>
       		<td class="middle-left-child" width="<?php echo $col1;?>">Notes:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>" colspan="3"><textarea name="notes" cols="50" rows="4" style="background-color:#FAD090"></textarea></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
        	<td class="middle-none-child" width="<?php echo $col2;?>">
            <select name="status" style="background-color:#FAD090">
            	<?php
					$sql = "SELECT * FROM `status` WHERE id = 1 OR id=25";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						echo '<option value="'.$row['id'].'">'.$row['status'].'</option>';
					}
				?>
           	</select>
            </td>
        	<td class="middle-right-child" colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Date Time:</td>
        	<td class="bottom-center-child" width="<?php echo $col2;?>">
            	<input type="text" id="opendt" name="opendt" readonly value="<?php echo $open;?>"/>
  <button id="opendtbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#opendtbutton').click(
      function(e) {
        $('#opendt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
            </td>
            <td class="bottom-right-child" colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
        </tr>
        <tr><td colspan="4">&nbsp;</td></td>
        <tr><td colspan="4">&nbsp;</td></td>
        <tr><td colspan="4">&nbsp;</td></td>
        <tr><td colspan="4">&nbsp;</td></td>
        <tr><td colspan="4">&nbsp;</td></td>
      	 <?php if (isset($_POST['lic'])){?>
       	<tr><td colspan="5">&nbsp;</td></td>
        <tr><td class="top-child_h" colspan="5" style="color:#148540"><h4>History</h4></td></tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">
        	<table width="100%" cellspacing="0">
        	 <?php
			 		$lic = $_POST['lic'];
					$sql2 = "SELECT * FROM service_req WHERE `a_number`='$lic' AND `delete` = 0 order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";
					$rs8 = mysql_query($sql2);

					if(mysql_num_rows($rs8)!=0){
						$col1 = 45;
						$col2 = 132;
						$col3 = 75;
						$col4 = 50;
						?>
                        	<tr class="thead">
                                <td class="middle-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>
                                <td width="<?php echo $col2+15;?>">Date</td>
                                <td width="<?php echo $col2;?>">Car</td>
                                <td width="<?php echo $col3;?>">A Number</td>
                                <td width="<?php echo $col2;?>">Location</td>
                                <td width="<?php echo $col2-15;?>">Job</td>
                                <td width="<?php echo $col4;?>">Attendee</td>
                                <td width="<?php echo $col4;?>">Charged</td>
                                <td width="<?php echo $col4;?>">Insured</td>
                                <td width="<?php echo $col4;?>">Image</td>
                                <td class="middle-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>
                            </tr>
                        <?php
						while($row = mysql_fetch_array($rs8)){
				?>
                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                                <td class="middle-left-child" style="background-color:#E1DDDC" <?php
                                if($row['master_sc']!=0){
                                    echo 'style="background-color:#6F3"';
                                }
                                else if($row['status']==4){
                                    echo 'style="background-color:#EB6C2C"';
                                }
                                else if($row['present'] == 0 && $row['job']==7){
                                    echo 'style="background-color:#FF0000"';
                                }
                                else if($row['charged']>0 && $row['money_delivered'] == 0 && $row['status'] != 3){
                                    echo 'style="background-color:#800080"';
                                }

                                ?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
                                <td width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16);?></td>
                                 <td width="<?php echo $col2;?>"><?php echo $row['car'];?></td>
                              <td width="<?php echo $col3;?>"><?php echo $row['a_number'];?></td>
                                <td width="<?php echo $col2;?>"><?php echo $row['location'];?></td>
                                <td width="<?php echo $col2-15;?>"><?php
                                    $jid = $row['job'];
                                    $sql2 = "SELECT * FROM jobs where id = '$jid'";
                                    $rs2 = mysql_query($sql2);
                                    $row2 = mysql_fetch_array($rs2);
                                    echo $row2['description'];
                                ?></td>
                                <td width="<?php echo $col3;?>"><?php
                                    $aid = $row['attendee_id'];
                                    $sql2 = "SELECT * FROM attendee where id = '$aid'";
                                    $rs2 = mysql_query($sql2);
                                    $row2 = mysql_fetch_array($rs2);
                                    echo $row2['s_name'];
                                ?></td>
                                <td width="<?php echo $col3;?>">
                                    <?php echo number_format($row['charged'],2);?>
                                </td>
                                <td width="<?php echo $col3;?>"><?php
                                    if($row['insured']){
                                        echo 'Yes';
                                    }
                                    else{
                                        echo 'No';
                                    }
                                ?></td>
                                <td width="<?php echo $col3;?>">
                                    <?php
                                        if (is_dir('rrimage/'.$row['id'])){
                                            echo 'Yes';
                                        }
                                        else{
                                            echo 'No';
                                        }
                                    ?>
                                </td>
                                <td class="middle-right-child" style="background-color:#E1DDDC" width="<?php echo $col4;?>"><?php
                                    $sid = $row['status'];
                                    $sql2 = "SELECT * FROM status WHERE id = '$sid'";
                                    $rs2 = mysql_query($sql2);
                                    $row2 = mysql_fetch_array($rs2);
                                    echo $row2['status'];
                                ?>
                                </td>
                            </tr>
                <?php	} //End While Loop
					} //End if Loop
				?>
                <tr><td class="bottom-child" colspan="11">&nbsp;</td></tr>
        	</table>
        </td></tr>

        <?php } // end if (isset($_REQUEST['lic']))?>
    </table>
</form>
<script  type="text/javascript">
 var frmvalidator = new Validator("new_sc");
 frmvalidator.addValidation("car","req","Please Enter Car Type");
 frmvalidator.addValidation("loc","req","Please Enter Location");
 frmvalidator.addValidation("num","req","Please Enter Car Number");
 frmvalidator.addValidation("attendee","req","Please select attendee");
 frmvalidator.addValidation("district","req","Please select district");
 frmvalidator.addValidation("addphone","req","Please enter additional phone number");
 frmvalidator.addValidation("charged","num","Please Check Charged field: you must enter numbers only");
 frmvalidator.addValidation("opendt","req","Please Enter Open Date Time");
 frmvalidator.addValidation("tow_reason","req","Tow Reason Required",
        "VWZ_IsValue(document.forms['new_sc'].elements['towing'],'1')");

</script>
<script type="text/javascript" src="js/functions.js">

</script>
<script language="javascript" type="text/javascript">
	var jobs_group_list=document.new_sc.jobs_group
	var jobs_list=document.new_sc.jobs

	var jobs=new Array()
	jobs[0]=[<?php
		$sql2 = "SELECT * FROM jobs order by description";
		$rs2 = mysql_query($sql2);
		$first_line = 1;
		while($row2 = mysql_fetch_array($rs2)){
			if($first_line){
				echo '"'.$row2['description'].'|'.$row2['id'].'"';
				$first_line = 0;
			}
			else{
				echo ', "'.$row2['description'].'|'.$row2['id'].'"';
			}
		}
	?>];
	<?php
		$sql2 = "SELECT * FROM jobs_group order by description";
		$rs2 = mysql_query($sql2);
		while($row2 = mysql_fetch_array($rs2)){
			$jobs_group_id = $row2['id'];
			$sql3 = "SELECT * FROM jobs WHERE jobs_group_id='$jobs_group_id' order by description";
			$first_line = 1;
			$end = 0;
			$rs3 = mysql_query($sql3);
			while($row3 = mysql_fetch_array($rs3)){
				$end=1;
				if($first_line){
					echo 'jobs['.$jobs_group_id.']=["'.$row3['description'].'|'.$row3['id'].'"';
					$first_line = 0;
				}
				else{
					echo ', "'.$row3['description'].'|'.$row3['id'].'"';
				}
			}
			if($end) {echo '];'."\n";	}
		}
	?>
	function updatejobs(selectedjobgroup){

		jobs_list.options.length=0
		var jobs_list_id = document.getElementById('jobs_group').options[selectedjobgroup].value;
		for (i=0; i<jobs[jobs_list_id].length; i++)
			jobs_list.options[jobs_list.options.length]=new Option(jobs[jobs_list_id][i].split("|")[0], jobs[jobs_list_id][i].split("|")[1])
	}

	function display(obj) {
		var tow = new Array();
		tow[0] = [<?php
			$sql2 = "SELECT * FROM towing_reason WHERE 1";
			$rs2 = mysql_query($sql2);
			$f_line = 1;
			while($row2 = mysql_fetch_array($rs2)){
				if($f_line){
					$f_line = 0;
					echo '" | ","'.$row2['description'].'|'.$row2['id'].'"';
				}
				else{
					echo ', "'.$row2['description'].'|'.$row2['id'].'"';
				}
			}
		?>];
		tow[1] = [<?php
			$sql2 = "SELECT * FROM towing_reason_police WHERE 1";
			$rs2 = mysql_query($sql2);
			$f_line = 1;
			while($row2 = mysql_fetch_array($rs2)){
				if($f_line){
					$f_line = 0;
					echo '" | ","'.$row2['description'].'|'.$row2['id'].'"';
				}
				else{
					echo ', "'.$row2['description'].'|'.$row2['id'].'"';
				}
			}
		?>];
		var tow_reason=document.new_sc.tow_reason
		txt = obj.options[obj.selectedIndex].value;
		if ( txt.toString()=='3' || txt.match('12') || txt.match('13') || txt.match('14') || txt.match('16') || txt.match('19') || txt.match('22') || txt.match('20')  || txt.match('29') || txt.match('32') || txt.match('33') || txt.match('36')) {
			document.getElementById('toloc').style.display = 'inline';
			document.getElementById('a_rec').style.display = 'inline';
			document.getElementById('tow_reason').style.display = 'inline';
			document.getElementById('towing').value='1';
		}
		else{
			document.getElementById('toloc').style.display = 'none';
			document.getElementById('tow_reason').style.display = 'none';
			document.getElementById('a_rec').style.display = 'none';
			document.getElementById('towing').value='';
		}
		tow_reason.options.length=0;
		if( txt.match('32')){
			//Towing polic change drop down
			for(i=0;i<tow[1].length; i++){
				tow_reason.options[document.getElementById('tow_reason').options.length] = new Option(tow[1][i].split("|")[0], tow[1][i].split("|")[1])
			}
		}
		else{
			for(i=0;i<tow[0].length; i++){
				tow_reason.options[tow_reason.options.length] = new Option(tow[0][i].split("|")[0], tow[0][i].split("|")[1])
			}
		}

	}

	function copyName(){
		cname = "<?php echo $cname;?>";
		if(document.getElementById('c_req').checked){
			document.getElementById('requestedBy').value = cname;
		}
		else{
			document.getElementById('requestedBy').value = "";
		}
	}

	function copyPhone(){
		cmobile = "<?php echo $cmobile;?>"
		if(document.getElementById('c_mob').checked){
			document.getElementById('addphone').value = cmobile;
		}
		else{
			document.getElementById('addphone').value = "";
		}
	}

	function copyAddress(){
		add = "<?php echo $address;?>";
		if(document.getElementById('a_req').checked){
			document.getElementById('toloc').value = add;
		}
		else{
			document.getElementById('toloc').value = "";
		}
	}

	function copyAddress2(){
		add = "<?php echo $address;?>";
		if(document.getElementById('a_req2').checked){
			document.getElementById('loc').value = add;
		}
		else{
			document.getElementById('loc').value = "";
		}
	}

	function checkLicense(){
		license = document.getElementById('num').value;
		if(document.getElementById('num').value.length != 0){
			window.open('new_sc.php?lic='+license+'&id='+<?php echo $idm;?>+'&job='+<?php if(isset($_REQUEST['job'])){echo $_REQUEST['job'];} else{echo 0;}?>+'&l='+<?php
			if(isset($_REQUEST['l'])) {
				echo $_REQUEST['l'];
			}
			else{
				echo 0;
			}?>, "_self");
		}
	}



</script>
</body>
</html>
