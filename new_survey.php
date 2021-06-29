<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

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

$idm=0;
$district = '';
$attendee = '';
$requestedBy = '';
$open = '';
$acc_link = 0;

//New Survey
if($_REQUEST['sc']){
	$idm=$_REQUEST['sc'];
	$sql = "SELECT * FROM service_req where id='$idm'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$car = $row['car'];
	$policy = $row['pol'];
	$insured = $row['insured'];
	$lic = $row['a_number'];
	$manu_date = $row['manu_date'];
	$claimNo = $rop['claimNo'];
	$addPhone = $row['AddPhone'];
	$claimNo = $row['claimNo'];
	$vin=$row['vin'];
	$location='';
	$attendee=32;
}

$sql2 = "SELECT * FROM VW_CLAIMS WHERE ClaimNo='$claimNo'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$rs2 = mssql_query($sql2);

if(mssql_num_rows($rs2)!=0){
	$row2 = mssql_fetch_array($rs2);
	$date_loss=substr(date("d-M-y", strtotime($row2['Date_Loss'])),0,10);
}

if($_POST['submit']==='Submit'){
	//Save survey
	$time = date('n-j-Y G:i:s');
	$sql="INSERT INTO `survey` (`service_req_id`, `location`, `bodyshop_id`, `adjuster_id`, `requested_by_id`, `contact_person`, `phone`, `notes`, `status_id`, `open_time`, `timestamp`, `insert_user_id`, `repairer`, `transmission`, `survey_type_id`, `manu_date`) VALUES ('$idm', '".$_POST['loc']."', '".$_POST['body_shop']."', '".$_POST['adjuster']."', '".$_POST['request']."', '".$_POST['contact_person']."', '".$_POST['addphone']."', '".$_POST['notes']."', '".$_POST['status']."', '".$_POST['opendt']."', '$time', '".$_SESSION['user_id']."', '".$_POST['repairer']."', '".$_POST['transmission']."', '".$_POST['survey_type_id']."', '".$_POST['manu_date']."')  ";
	mysql_query($sql);
	//echo $sql.'<br/>'.mysql_error();
	$lastid = mysql_insert_id();

	$t=$_SESSION['user_id'];

	$sql="UPDATE service_req SET `adjfee`=".$t." WHERE `id`='$idm' ";
	mysql_query($sql);

	header("location: survey.php?sid=".$lastid);
}


?>

<form name="new_survey" action="new_survey.php?sc=<?php echo $idm?>" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">New Survey Request</h8></td>
        </tr>
        <tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        <td colspan="5">&nbsp;<input type="hidden" name="idm" value="<?php echo $idm;?>"/>
        <input type="hidden" name="acc_link" value="<?php echo $acc_link;?>"/>
        </td>
        </tr>
        <tr>
        	<td colspan="4" class="top-child_h" style="color:#148540;"><h4>Survey Call Details</h4></td>
                    <td rowspan="15" valign="top">
            <table width="100%" cellspacing="0">
            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td></tr>
            	<tr><td class="middle-child" colspan="2">&nbsp;</td></tr>
                <?php
				/*$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY VehStatus, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";
				$rs2 = mysql_query($sql2);*/
				$sql2 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY VehStatus, Date_Renewal DESC, PolicyNo DESC";
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
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['VehStatus'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured Since:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
													$tdate = new datetime (date("d-M-y", strtotime($row2['Date_Application'])));
													echo date_format($tdate,"d F, Y");
													?>
												</td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insurance Period:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
														$tdate = new datetime (date("d-M-y", strtotime($row2['VehDate_Effective'])));
														$tdate2 = new datetime (date("d-M-y", strtotime($row2['VehDate_Renewal'])));
														echo date_format($tdate,"d M, Y").' - '.date_format($tdate2,"d M, Y");?>

												</td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Year / Color:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['YearMake'].' / '.$row2['Color'];?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Body Type / Seats:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php  echo $row2['BodyType'].' / '.$row2['Seats'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">New Value / Day Value:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php  echo number_format($row2['VehicleValue'],2).' / '.number_format(dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']),2);?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Vehicle Coverage/Use:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['VehCoverage'].'/'.$row2['VehUse'];?></td>
                    </tr>
                      <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Total Premium / Balance:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php

								echo number_format((float)$row2['Premium']+(float)$row2['PolicyFee'],2);
								if($row2['AmountDeb'] > 0){
									echo ' / <a style="color:red">'.number_format($row2['AmountDeb'],2).'</a>';
								}
								else if($row2['AmountDeb'] <= 0){
									echo ' / '.number_format($row2['AmountDeb'],2);
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
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $lic;?>" style="background-color:#FAD090" name="num" size="15" /></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="loc" id="loc" size="25"/> VII: <input type="checkbox" name="a_req2" id="a_req2" onchange="copyAddress2()"/></td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">VIN:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $vin;?>" style="background-color:#FAD090" name="vin" size="25"/></td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Body Shop:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="body_shop" id="body_shop" style="background-color:#FAD090">
            	<option value="0"></option>
             <?php
					$sql5="SELECT * FROM `bodyshop` WHERE `active`=1 order by `name`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';
					}
				?>
                </select>
            </td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Engine Number:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" id="engine" name="engine" style="background-color:#FAD090" size="25">
                    </td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">Name Repairer:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" id="repairer" name="repairer" style="background-color:#FAD090">

            </td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Transmission:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><select name="transmission" id="transmission" style="background-color:#FAD090">
            	<option id="Automatic">Automatic</option>
                <option id="Manual">Manual</option>
                </select> RHD <input type="checkbox" style="background-color:#FAD090" name="right" <?php if($row['RightHandDrive']==1){ echo 'checked="checked"';} ?>/>
                    </td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">&nbsp;</td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Manu Date</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <input type="text" id="manu_date" name="manu_date" readonly value="<?php echo $manu_date;?>"/>
  <button id="manu_date_button">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#manu_date_button').click(
      function(e) {
        $('#manu_date').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d"}).focus();
        e.preventDefault();
      } );
  </script>
                    </td>
        </tr>
         <tr>
            <td class="middle-left-child" width="<?php echo $col1;?>">Survey Type</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
            <select name="survey_type_id" id="survey_type_id" style="background-color:#FAD090">
                <?php
					$sql5="SELECT * FROM `survey_type` order by `description`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						echo '<option value="'.$row5['id'].'">'.$row5['description'].'</option>';
					}
				?>
            </select>
            </td>
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
            </select></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Adjuster:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="adjuster" id="adjuster" style="background-color:#FAD090">
            	<option id="0"></option>
                <?php
					$sql5="SELECT * FROM `adjuster` WHERE `active`=1 order by `name`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';
					}
				?>
            </select></td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            </td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
           	<td class="middle-none-child" width="<?php echo $col2;?>"><select name="request" id="request" style="background-color:#FAD090">
            	<option value="0"></option>
            	<?php
					$sql5="SELECT * FROM `rental_request` WHERE `isSurveyRequestor`=1";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';
					}
				?>
            </select></td>
           <td class="middle-none-child" width="<?php echo $col1;?>">Policy Number:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $policy;?>" style="background-color:#FAD090" name="pol" size="20"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Contact Person:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="contact_person" id="contact_person" size="23" maxlength="50"/> VII: <input type="checkbox" name="c_req" id="c_req" onchange="copyName()"/></td>
             <td class="middle-none-child" width="<?php echo $col1;?>">Claim Number:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $claimNo;?>" style="background-color:#FAD090" name="claimNo" size="20"/></td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090;" name="addphone" id="addphone" size="18" value="<?php echo $addPhone;?>"/> VII: <input type="checkbox" name="c_mob" id="c_mob" onchange="copyPhone()"/></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">Date Of Loss:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090;" name="date_loss"  value="<?php
								$tdate = new datetime ($date_loss);
								echo date_format($tdate,"d M, Y");?>
							" size="30"/></td>
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
					$sql = "SELECT * FROM `status_survey` WHERE id = 1";
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
 var frmvalidator = new Validator("new_survey");
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


	function copyName(){
		cname = "<?php echo $cname;?>";
		if(document.getElementById('c_req').checked){
			document.getElementById('contact_person').value = cname;
		}
		else{
			document.getElementById('contact_person').value = "";
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


</script>
</body>
</html>
