<?php
//survey.php
//session_start();
//error_reporting(E_ALL);
//INI_SET('display_errors', '1');
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/function.php";

session_start();

$sql4 = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";
$rs4 = mysql_query($sql4);
$row4 = mysql_fetch_array($rs4);
if(isPolice()){
	header('location:police.php?sc='.$_REQUEST['sc']);
}
else if($row4['clientId']!=0){
	header('location:police.php?sc='.$_REQUEST['sc']);
}
else if($_SESSION['user_level'] < 2){
	header('location:index.php');
}
$id = $_REQUEST['sc'];
$survey_id = $_REQUEST['sid'];
echo menu();
$col1 = 85;
$col2 = 200;
$history_item = 3;
if(trim($survey_id)!==''){
	$sql1 = "SELECT * FROM survey WHERE id= '$survey_id'";
	$rs1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($rs1);
	$id=$row1['service_req_id'];
}
$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';
if($_SESSION['user_level'] < POWER_LEVEL){
	$st='readonly="readonly"';
}
$sql14 = "SELECT * FROM service_req_extra WHERE sc_id = '$id'";
$rs14 = mysql_query($sql14);
$row14 = mysql_fetch_array($rs14);
$sql2 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A'";
$rs2=mssql_query($sql2);
$row2 = mssql_fetch_array($rs2);
$claimNo=$row['claimNo'];
$sql13 = "SELECT * FROM VW_CLAIMS WHERE ClaimNo='$claimNo'";
$rs13= mssql_query($sql13);
$row13 = mssql_fetch_array($rs13);

?>
<form name="survey" enctype="multipart/form-data" action="rec_survey.php?sc=<?php echo $survey_id;?>" method="post">
	<span class="middle-right-child">

	</span>
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center"><h8 style="color:#FFF">Survey Request # <?php  if(trim($survey_id)!==''){	echo str_pad($survey_id,5,'0',STR_PAD_LEFT);} else echo 'New';?></h8></td>
      </tr>
        <tr><td colspan="5" align="right" style="color:#fff">Total Insured Vehicles: <?php echo $row2['t'];?></td></tr>
        <tr>
        	<td class="top-child" colspan="5">Survey from#<a style="color:#DCB272" href="edit_sc.php?sc=<?php  echo $id;?>"/><?php echo str_pad($id,5,'0',STR_PAD_LEFT);?></td>

        </tr>
        <tr>
        	<td class="middle-child" colspan="5">Time Inserted: <?php echo substr($row1['timestamp'],0,-3);?>
            </td>
        </tr>
        <tr>
        	<td class="middle-child" colspan="5">Approval Status: <?php
			$readonly='';
			if($row1['approved']==1){
				echo " Approved ".getUserFNameID($row1['approve_by'])." (".$row1['approve_date'].")";
				$readonly='disabled="true"';
			}
			else{
				echo "Pending";
			}
?>
            </td>
        </tr>
        <tr>
            	<td <?php if($row1['status']!=2 && $row1['status']!=3) {
						echo 'class="bottom-child"';
					}
					else{
						echo 'class="middle-child"';
					}
				?>
               	colspan="5">
                <?php if($_SESSION['user_level']>=POWER_LEVEL){
					?>
               	<input type="text" id="opendt" name="opendt" readonly value="<?php echo $row1['open_time'];?>"/>
  <button id="openbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#openbutton').click(
      function(e) {
        $('#opendt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
				<?php }
				else{ ?>
					Time Requested:<?php echo $row1['open_time'];
				}?>




                </td>
        </tr>
        <?php
			if($row1['status']==2){ //Closed
		?>
         <tr>
        	<td class="bottom-child" colspan="5">Time Closed: <?php echo $row1['close_time'];?>
            </td>
        </tr>
        <?php
			}
			else if($row1['status']==3){ //Cancelled
		?>
          <tr>
        	<td class="bottom-child" colspan="5">Time Cancelled: <?php echo $row['closedt'];?>
        </tr>
        <?php
			}
		?>
        <tr><td colspan="5">&nbsp;</td></tr>
         <tr><td colspan="5" class="top-child">
         	<table width="100%">
            	<tr>
                	<td width="(100/7)%">Damage Calculation <input type="checkbox" style="background-color:#FAD090" name="calculation2" <?php if($row1['calculation_t']!==''){ echo 'checked="checked"';}?>/>
                    </td>
                    <td width="(100/7)%">Survey Vehicle <input type="checkbox" style="background-color:#FAD090" name="survey_vehicle" <?php if($row1['survey_vehicle_t']!==''){ echo 'checked="checked"';}?>/>
                    </td>
                    <td width="(100/7)%">Parts List <input type="checkbox" style="background-color:#FAD090" name="parts_list" <?php if($row1['parts_list']==1){ echo 'checked="checked"';}?>/>
                    </td>
                     <td width="(100/7)%">Audatex / Labor Qoute <input type="checkbox" style="background-color:#FAD090" name="quotation" <?php if($row1['quotation']==1){ echo 'checked="checked"';}?>/>
                    </td>
                    <td width="(100/7)%">Parts Prices <input type="checkbox" style="background-color:#FAD090" name="parts_prices" <?php if($row1['parts_prices_t']!==''){ echo 'checked="checked"';}?> />
                    </td>
                    <td width="(100/7)%">
                    	Negiotiation / Review <input type="checkbox" style="background-color:#FAD090" name="neg_review" id="neg_review" <?php if($row1['neg_review_t']!==''){ echo 'checked="checked"';}?> />
                    </td>
                    <td width="(100/7)%">Estimation / Report <input type="checkbox" style="background-color:#FAD090" name="est_report" id="est_report" <?php if($row1['est_report_t']!==''){ echo 'checked="checked"';}?> />
                    </td>
             	</tr>
                <tr>
                	<td width="(100/7)%"><?php echo $row1['calculation_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['survey_vehicle_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['parts_list_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['quotation_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['parts_prices_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['neg_review_t']; ?>&nbsp;
                    </td>
                    <td width="(100/7)%"><?php echo $row1['est_report_t']; ?>&nbsp;
                    </td>
             	</tr>
            </table>
         </td>
         </tr>
         <tr><td colspan="5" class="bottom-child">
         <table width="100%">
         	<tr>
         	<td>Damage Calculation <input type="text" style="background-color:#FAD090; text-align: right" name="damage_calculation_a" id="damage_calculation_a" size="10" value="<?php echo number_format($row1['damage_calculation_a'],2,'.','');?>"/></td>
            <td>Parts Status  <select name="parts_status" id="parts_status" style="background-color:#FAD090">
            	<option value="0" <?php if($row1['parts_status']==0){ echo 'selected="selected"';}?>></option>
                <?php
					$sql15="SELECT * FROM `status_parts` WHERE `active`=1";
					$rs15=mysql_query($sql15);
					while($row15=mysql_fetch_array($rs15)){
						if($row1['parts_status']==$row15['id']){
							echo '<option value="'.$row15['id'].'" selected="selected">'.$row15['description'].'</option>';
						}
						else{
							echo '<option value="'.$row15['id'].'">'.$row15['description'].'</option>';
						}
					}
				?>
                </select>
         	</td>
            <td>Converstaion with Client <select name="conv_client" id="conv_client" style="background-color:#FAD090; width:50px">
            	<option value="0" <?php if($row1['conv_client']==0){ echo 'selected="selected"';}?>>0</option>
                <option value="1" <?php if($row1['conv_client']==1){ echo 'selected="selected"';}?>>1</option>
                <option value="2" <?php if($row1['conv_client']==2){ echo 'selected="selected"';}?>>2</option>
                <option value="3" <?php if($row1['conv_client']==3){ echo 'selected="selected"';}?>>3</option>
                <option value="4" <?php if($row1['conv_client']==4){ echo 'selected="selected"';}?>>4</option>
                <option value="5" <?php if($row1['conv_client']==5){ echo 'selected="selected"';}?>>5</option>
                <option value="6" <?php if($row1['conv_client']==6){ echo 'selected="selected"';}?>>6</option>
                <option value="7" <?php if($row1['conv_client']==7){ echo 'selected="selected"';}?>>7</option>
                <option value="8" <?php if($row1['conv_client']==8){ echo 'selected="selected"';}?>>8</option>
                <option value="9" <?php if($row1['conv_client']==9){ echo 'selected="selected"';}?>>9</option>
                </select>
            </td>
            <td>Client Status <select name="client_status" style="background-color:#FAD090">
            	<option value="0" <?php if($row1['client_status']==0){ echo 'selected="selected"';}?>></option>
                <?php
					$sql15="SELECT * FROM `status_survey_client`";
					$rs15=mysql_query($sql15);
					while($row15=mysql_fetch_array($rs15)){
						if($row1['client_status']==$row15['id']){
							echo '<option value="'.$row15['id'].'" selected="selected">'.$row15['description'].'</option>';
						}
						else{
							echo '<option value="'.$row15['id'].'">'.$row15['description'].'</option>';
						}
					}
				?>
                </select>
            </td>
            </tr>
         </table>
         </td></tr>
         <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="2" class="top-child_h" style="color:#148540" align="center">Call Information</td>
            <td colspan="2" class="top-child_h" style="color:#148540" align="center">Vehicle Insurance Information</td>
            <td rowspan="26" valign="top">
            <table width="100%" cellspacing="0">
            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td></tr>
            	<tr><td class="middle-child" colspan="2">&nbsp;</td></tr>
                <?php
				$lic = $row['a_number'];
				$policy = $row['pol'];
				$cond=2;
				if( ($row1['cond_ext']==='Excellent' || $row1['cond_ext']==='Good') && ( $row1['cond_int']==='Excellent' || $row1['cond_int']==='Good')){
					$cond=1;
				}
				//$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";
				/*$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY Status DESC, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";
				$rs2 = mysql_query($sql2);*/
				$sql2 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY
				CASE	WHEN VehStatus='A' THEN 1
						WHEN VehStatus='L' THEN 2
						WHEN VehStatus='C' THEN 3
				End
				, Date_Renewal DESC, PolicyNo DESC";
				//$params = array();
				//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$rs2=mssql_query($sql2);

				$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$row[a_number]'";
				$rs3 = mysql_query($sql3);
				if(mssql_num_rows($rs2)!=0 || mysql_num_rows($rs3)!=0){
					$vcol1 = 45;
					$vcol2 = 55;
					if(mssql_num_rows($rs2)!=0 && trim($policy)!==''){
						$row2 = mssql_fetch_array($rs2);
						$use_extra = 0;
					}
					else{
						$use_extra = 1;
						$row3 = mysql_fetch_array($rs3);
					}
				?>
                	<tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Full Name:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
							if(!$use_extra){
								echo $row2['Full_Name'];
								$cname = $row2['Full_Name'];
							}
							else{
								echo $row3['fname'].' '.$row3['lname'];
								$cname = $row3['fname'].' '.$row3['lname'];
							}
							?>
                      	</td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php
							echo $vcol1;?>%">Client No:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							echo $row2['ClientNo'];
						}
						else{
							echo $row3['clientNo'];
						}?>&nbsp;&nbsp;<span style="color:blue"><?php
            	echo getPolicyMadeBy($row['pol']);
			?></span></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Address:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							echo $row2['Address1'];
							$caddress = $row2['Address1'];
						}
						else{
							echo $row3['address'];
							$caddress = $row3['Address1'];
						}?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insurance Status:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%" <?php
						if(strcmp($row2['Status'],'A')==0){
							echo 'style="color:#0C0;font-weight:bold;font-size:18px"' ;
						}
						else if(strcmp($row2['Status'],'C')==0){
							echo 'style="color:#F00;font-weight:bold;font-size:18px"' ;
						}
						else{
							echo 'style="color:#F90;font-weight:bold;font-size:18px"' ;
						}
						?> ><?php echo $row2['VehStatus'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured Since:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php

												//$tdate = new datetime (substr($row2['Date_Application'],0,10));
												$tdate = new datetime (date("d-M-y", strtotime($row2['Date_Application'])));
			echo date_format($tdate,"d F, Y");?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured From:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							$fdate = new datetime(date("d-M-y", strtotime($row2['VehDate_Effective'])));
							$tdate = new datetime(date("d-M-y", strtotime($row2['VehDate_Renewal'])));
							echo date_format($fdate,"d F, Y");
						}
						else{
							echo $row3['insured_from'];
						}
						?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Renewal Date:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							echo date_format($tdate,"d F, Y");
						}
						else{
							echo $row3['insured_to'];
						}
						?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Year / Color:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							$vyear=$row2['YearMake'];
							echo $row2['YearMake'].' / '.$row2['Color'];
						}
						else{
							$vyear=$row3['year'];
							echo $row3['year'].' / '.$row3['color'];
						}?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Body Type / Seats:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							echo $row2['BodyType'].' / '.$row2['Seats'];
						}
						else{
							echo $row3['body_type'].' / '.$row3['seats'];
						}
						?>
                        </td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">New Value / Day Value:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						$dateofloss=substr(date("d-M-y", strtotime($row13['Date_Loss'])),0,10);

						if(!$use_extra){
						$vuse=$row2['VehUse'];
						$vvalue=$row2['VehicleValue'];
						echo number_format($row2['VehicleValue'],2).' / '.number_format(dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']),2);
						if(trim($row1['manu_date'])!=='' AND trim($row13['Date_Loss'])!==''){
						echo ' / <a style="font-weight:bold">'.number_format(dayValueA($row1['manu_date'],substr(date("y-m-d", strtotime($row13['Date_Loss'])),0,10),$row2['VehicleValue'],$row2['VehUse']),2).'</a>';
						}
						}
						else{
							if($row3['vehicle_use']==='private'){
								$vuse='PR';
							}
							else{
								$vuse="CM";
							}
							$vvalue=$row3['cat_value'];
							echo number_format($row3['cat_value'],2).' / '.number_format(dayValue($row3['year'],$row3['cat_value'],$vuse),2);

							if(trim($row1['manu_date'])!=='' AND trim($row13['Date_Loss'])!==''){
							echo ' / <a style="font-weight:bold">'.number_format(dayValueA($row1['manu_date'],substr(date("y-m-d", strtotime($row13['Date_Loss'])),0,10),$row3['cat_value'],$vuse),2).'</a>';
							}

						}
						?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Vehicle Coverage/Use:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php
						if(!$use_extra){
							echo $row2['VehCoverage'].'/'.$row2['VehUse'];
						}
						else{
							echo $row3['vehicle_coverage'].'/'.$row3['vehicle_use'];
						}?></td>
                    </tr>
                    <?php
						if(!$use_extra){
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
						}
						else{
							$phone = '';
							if(strcmp(trim($row3['phone']),'')!=0){
								$phone = $row3['phone'];

							}
							if(strcmp(trim($row3['mobile']),'')!=0){
								if(strcmp($phone,'')==0){
									$phone = $row3['mobile'];
									$cmobile = $row3['mobile'];
								}
								else{
									$phone = $phone.' / '.$row3['mobile'];
									$cmobile = $row3['mobile'];
								}
							}
						}
					?>
                    	<tr>
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Phone Number(s):</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $phone;?></td>
                    	</tr>
                        <tr>
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Agent/Broker:</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php if(!$use_extra){echo $row2['AgentName'];}?></td>
                    	</tr>
                        <tr>
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Total Premium / Balance:</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php if($_SESSION['user_level'] >= ADMIN_LEVEL){echo number_format(($row2['Premium']+(float)$row2['PolicyFee']),2).' / '.number_format($row2['AmountDeb'],2);}?></td>
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
                <?php } ?>
                <tr><td class="top-child_h"  colspan="2" style="color:#148540" align="center">Drivers License</td></tr>
                 <tr><td class="middle-child"  colspan="2" rowspan="3">
				 Drivers License: <input name="licenseNo" id="licenseNo"  style="background-color:#FAD090" value="<?php echo $row['licenseNo'];?>" size="20" onKeyUp="showUpload()"/>
                 <br/>
                 <br/>
                 <table width="100%">
                 	<tr>
                    	<td width="50%">
				 <?php
                 	$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo]'";
					$rs3 = mysql_query($sql3);
					$sql4 = "SELECT * FROM drivers_license where id = '$row[a_number]'";
					$rs4 = mysql_query($sql4);
					if($row3 = mysql_fetch_array($rs3)){
				 ?>
                 <a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="200" src="download.php?file=<?php echo $row3['loc'];?>" /></a>

                 <?php }
				 else if($row4 = mysql_fetch_array($rs4)){ ?>
					  <a target="_blank" href="download.php?file=<?php echo $row4['loc'];?>"><img width="200" src="download.php?file=<?php echo $row4['loc'];?>" /></a>
                	<?php
				 }
				 if ($_SESSION['user_id'] > 1){
					 ?>
                     <br/>
                     <br/>
                     <span id="upload_drv" style="display:<?php if(strlen($row['licenseNo'])!=0) {echo 'inline';} else{echo 'none';}?>">
                     <div class="buttonwrapper" style="display:inline">
					<a class="squarebutton"  onClick="uploadLicense()"><span>Insert Drivers License</span></a>
                    </div>
                    </span>
                 <?php }?>
                 		</td>
                        <td width="50%" valign="top">
                        	<b>Risk Factor</b>
                            <br/>
                            <table width="180">
                                    <tr>
                                        <td width="20%" style="background-color:#4DFF00" align="center"><input type="radio" name="risk1" value="1" <?php if($row3['risk']==1 || $row3['risk']==0) echo 'checked="checked"';?>/> </td>
                                        <td width="20%" style="background-color:#CCFF00" align="center"><input type="radio" name="risk1" value="2" <?php if($row3['risk']==2) echo 'checked="checked"';?>/></td>
                                        <td width="20%" style="background-color:#FFFF00" align="center"><input type="radio" name="risk1" value="3" <?php if($row3['risk']==3) echo 'checked="checked"';?>/></td>
                                        <td width="20%" style="background-color:#FFB300" align="center"><input type="radio" name="risk1" value="4" <?php if($row3['risk']==4) echo 'checked="checked"';?>/></td>
                                        <td width="20%" style="background-color:#FF3300" align="center"><input type="radio" name="risk1" value="5" <?php if($row3['risk']==5) echo 'checked="checked"';?>/></td>
                                    </tr>
                                    <tr>
                                        <td width="20%" align="center">1</td>
                                        <td width="20%" align="center">2</td>
                                        <td width="20%" align="center">3</td>
                                        <td width="20%" align="center">4</td>
                                        <td width="20%" align="center">5</td>
                                    </tr>
                                </table>
                          	<br/>
                                <b>Total Accident</b>
                                <table width="100%">
                                	<tr>
                                    	<td width="40%">
                                        	Not At Fault:
                                        </td>
                                        <td width="60%">
                                        	<?php
												$license = $row[licenseNo];
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND status='Not At Fault'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$natf=$row9['c'];
												$sql9 = "SELECT * FROM drivers_license WHERE id='$license'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pers = $row9['persoonsNo'];
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND status='Not At Fault'";
															$rs8=mysql_query($sql8);
															$row8=mysql_fetch_array($rs8);
															$natf = $natf + $row8['c'];
														}
													}
												}
												echo $natf
											?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td width="40%">
                                        	Pending:
                                        </td>
                                        <td width="60%">
                                        	<?php
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='Pending' OR status='')";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												echo $row9['c'];
											?>
                                        </td>
                                    </tr>
                                     <tr>
                                    	<td width="40%">
                                        	At Fault:
                                        </td>
                                        <td width="60%">
                                        	<?php
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='At Fault' OR status='Shared Liability')";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$atf = 0+$row9['c'];

												$sql9 = "SELECT * FROM drivers_license WHERE id='$license'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pers = $row9['persoonsNo'];
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND (status='At Fault' OR status='Shared Liability')";
															$rs8=mysql_query($sql8);
															$row8=mysql_fetch_array($rs8);
															$atf = $atf + $row8['c'];
														}
													}
												}
												echo $atf;
											?>
                                           </td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr>
                                    	<td>Persoons No.:</td>
                                   		<td><?php echo $pers;?></td>
                                    </tr>
                                </table>
                        </td>
                  	</tr>
             	</table>
                 </td>
                 </tr>
                 <tr><td colspan="2"></td></tr>
                <tr><td colspan="2"></td></tr>
                <tr><td class="bottom-child"  colspan="2" rowspan="3">Add. Drivers License:
              <input name="licenseNo2" id="licenseNo2"  style="background-color:#FAD090" value="<?php echo $row['licenseNo2'];?>" size="20" onkeyup="showUpload2()"/>
               	<br/>
                <br/>
				 <?php
				 	$license = $row['licenseNo2'];
                 	$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo2]'";
					$rs3 = mysql_query($sql3);
					if($row3 = mysql_fetch_array($rs3)){
				 ?>
                 <table width="100%">
                	<tr>
                    	<td width="50%">
                 <a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="200" src="download.php?file=<?php echo $row3['loc'];?>" /></a>
                 		</td>
                   	<td valign="top" width="50%"><b>Risk Factor</b> <br/>
                      <table width="180">
                        <tr>
                          <td width="20%" style="background-color:#4DFF00" align="center"><input type="radio" name="risk" value="1" <?php if($row3['risk']==1 || $row3['risk']==0) echo 'checked="checked"';?>/></td>
                          <td width="20%" style="background-color:#CCFF00" align="center"><input type="radio" name="risk" value="2" <?php if($row3['risk']==2) echo 'checked="checked"';?>/></td>
                          <td width="20%" style="background-color:#FFFF00" align="center"><input type="radio" name="risk" value="3" <?php if($row3['risk']==3) echo 'checked="checked"';?>/></td>
                          <td width="20%" style="background-color:#FFB300" align="center"><input type="radio" name="risk" value="4" <?php if($row3['risk']==4) echo 'checked="checked"';?>/></td>
                          <td width="20%" style="background-color:#FF3300" align="center"><input type="radio" name="risk" value="5" <?php if($row3['risk']==5) echo 'checked="checked"';?>/></td>
                        </tr>
                        <tr>
                          <td width="20%" align="center">1</td>
                          <td width="20%" align="center">2</td>
                          <td width="20%" align="center">3</td>
                          <td width="20%" align="center">4</td>
                          <td width="20%" align="center">5</td>
                        </tr>
                      </table>
                      <br/>
                      <b>Total Accident</b>
                      <table width="100%">
                        <tr>
                          <td width="40%"> Not At Fault: </td>
                          <td width="60%"><?php
						  						$license2=$row['licenseNo2'];
						  						$natf=0;
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license2' AND status='Not At Fault'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);

												$natf=$natf+$row9['c'];
												$sql9 = "SELECT * FROM drivers_license WHERE id='$license2'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pers = $row9['persoonsNo'];
												$adm = $row9['admNo'];
												$admExtra='';
												$persExtra='';
												/*if(trim($pers) !== ''){
													$persExtra="persoonsNo='$pers' ";
												}
												if($trim($adm) !==''){
													if($persExtra!==''){
														$admExtra="OR admNo='$adm'";
													}
													else{
														$admExtra=" admNo='$adm'";
													}
												}*/
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license2' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND status='Not At Fault'";
															$rs8=mysql_query($sql8);
															$row8=mysql_fetch_array($rs8);
															$natf = $natf + $row8['c'];
														}
													}
												}
												echo $natf;
											?></td>
                        </tr>
                        <tr>
                          <td width="40%"> Pending: </td>
                          <td width="60%"><?php
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='Pending' OR status='')";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pend =  $row9['c'];
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license2' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND (status='Pending' OR status='')";
															$rs8=mysql_query($sql8);
															$row8=mysql_fetch_array($rs8);
															$pend = $pend + $row8['c'];
														}
													}
												}
												echo $pend;
											?></td>
                        </tr>
                        <tr>
                          <td width="40%"> At Fault: </td>
                          <td width="60%"><?php
												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='At Fault' OR status='Shared Liability')";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$atf = 0+$row9['c'];

												$sql9 = "SELECT * FROM drivers_license WHERE id='$license'";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pers = $row9['persoonsNo'];
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND (status='At Fault' OR status='Shared Liability')";
															$rs8=mysql_query($sql8);
															$row8=mysql_fetch_array($rs8);
															$atf = $atf + $row8['c'];
														}
													}
												}
												echo $atf;
											?></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>Persoons No.:</td>
                          <td><?php echo $pers;?></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>


                 <?php }
				 if ($_SESSION['user_id'] > 1){
					 ?>
                     <br/>
                     <br/>
                     <span id="upload_drv2" style="display:<?php if(strlen($row['licenseNo2'])!=0) {echo 'inline';} else{echo 'none';}?>">
                     <div class="buttonwrapper" style="display:inline">
					<a class="squarebutton"  onClick="uploadLicense2()"><span>Insert Drivers License</span></a>
                    </div>
                    </span>
                 <?php }?>
              </td></tr>
            </table>
            <table width="100%" cellspacing="0">
            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Repair Information</td></tr>
                <tr>
                	<td width="30%" class="middle-left-child">&nbsp;</td>
                    <td width="70%" class="middle-right-child">&nbsp;</td>
                </tr>
                <tr>
                	<td class="middle-left-child">Days To Repair</td>
                    <td class="middle-right-child"><input type="text" value="<?php echo $row1['days_rep'];?>" style="background-color:#FAD090;" name="days_rep" id="days_rep" size="10"/ ></td>
                </tr>
                <tr>
                	<td class="middle-left-child">Schedule Rep Date</td>
                    <td class="middle-right-child">
                    <?php
					if(1){
					?>
                    <input type="text" id="srepdate" name="srepdate" readonly value="<?php echo $row1['s_rep_date'];?>"/>
  <button id="srepdatebutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#srepdatebutton').click(
      function(e) {
        $('#srepdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
        e.preventDefault();
      } );
  </script>
  			<?php }
				else {
					echo '<input type="text" disabled="disabled" value="'.$row1['s_rep_date'].'"/>';
				}
			?>

                    </td>
                </tr>
                <tr>
                	<td class="middle-left-child">All Parts Locally</td>
                    <td class="middle-right-child">
                    <select name="part_local" id="part_local" style="background-color:#FAD090">

                        <option value="1" <?php if($row1['parts_loc']==1) { echo 'selected="selected"';} ?>>Yes</option>
                        <option value="0"<?php if($row1['parts_loc']!=1) { echo 'selected="selected"';} ?>>No</option>
                    </select>
                    </td>
                </tr>
                <tr>
                	<td class="bottom-child" colspan="2">&nbsp;</td>
                </tr>
            </table>
            </td>
        </tr>

     	 <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Survey Type:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <?php
					if(($_SESSION['user_level']>=POWER_LEVEL || $_SESSION['user_id']==112)){
			?>
            <select name="survey_type_id" style="background-color:#FAD090">
                    <?php

					$sql5="SELECT * FROM `survey_type` order by `description`";
					$rs5=mysql_query($sql5);

					if($row1['survey_type_id']==0){
						echo '<option value="0" selected="selected"></option>';
					}
					else{
						echo '<option value="0"></option>';
					}

					while($row5=mysql_fetch_array($rs5)){

						if($row1['survey_type_id']==$row5['id']){
							echo '<option value="'.$row5['id'].'" selected="selected">'.$row5['description'].'</option>';
						}
						else{
						echo '<option value="'.$row5['id'].'">'.$row5['description'].'</option>';
						}
					}
					?>
              </select>
             <?php }
			 else{
				 $sql5="SELECT * FROM `survey_type` WHERE `id`=".$row1['survey_type_id'];
				$rs5=mysql_query($sql5);
				$row5=mysql_fetch_array($rs5);
			 ?>
             	<input type="hidden" name="survey_type_id" value="<?php echo $row1['survey_type_id'];?>"/>
                <input type="text" disabled value="<?php echo $row5['description'];?>"/>
             <?php } ?>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Adjuster:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <?php
					if(($_SESSION['user_level']>=POWER_LEVEL || $_SESSION['user_id']==112)){
			?>
            <select name="adjuster" style="background-color:#FAD090" <?php echo $readonly; ?> >
                    <?php
					$adj_approve_ids='';
					$sql5="SELECT * FROM `adjuster` WHERE `active`=1 order by `name`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						if($row1['adjuster_id']==$row5['id']){
							echo '<option value="'.$row5['id'].'" selected="selected">'.$row5['name'].'</option>';
							$adj_approve_ids=$row5['approve'];
							$adj_approve_non_preferred_ids=$row5['approve_non_preferred'];
						}
						else{
						echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';
						}
					}
					?>
              </select>
             <?php }
			 else{
				 $sql5="SELECT * FROM `adjuster` WHERE `id`=".$row1['adjuster_id'];
				$rs5=mysql_query($sql5);
				$row5=mysql_fetch_array($rs5);
				$adj_approve_ids=$row5['approve'];
				$adj_approve_non_preferred_ids=$row5['approve_non_preferred'];
			 ?>
             	<input type="hidden" name="adjuster" value="<?php echo $row5['id'];?>"/>
                <input type="text" disabled value="<?php echo $row5['name'];?>"/>
             <?php } ?>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Car Number:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" readonly name="num" id="num" size="15" value="<?php echo $row['a_number'];?>"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input  type="text" style="background-color:#FAD090" name="location" id="loc2" size="23" value="<?php echo $row1['location'];?>"/></td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" readonly name="car" size="25" value="<?php echo $row['car'];?>" /></td>
        </tr>
        <tr>
          	<td class="middle-left-child" width="<?php echo $col1;?>">Contact Pers.</td>
          	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="contact_pers" id="contact_pers" size="23" value="<?php echo $row1['contact_person'];?>"/>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Vin:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" readonly name="vin" size="25" value="<?php echo $row['vin'];?>" /></td>
        </tr>
         <tr>
         	<td class="middle-left-child" width="<?php echo $col1;?>">Phone:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="phone" id="phone" size="23" value="<?php echo $row1['phone'];?>"/> </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Engine Nr.:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 3){echo $st;}?> type="text" readonly name="engine" id="engine" size="20" value="<?php echo $row['engine'];?>"/></td>

        </tr>
        <tr>
        	<td class="middle-left-child">Veh Location</td>
            <td class="middle-right-child"><input  type="text" name="veh_location" size="23" value="<?php echo $row['veh_park_loc'];?>"/>

            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Policy Nr.:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" readonly name="pol" id="pol" size="20" value="<?php echo $row['pol'];?>"/> <?php $temp_pol=$row['pol']?></td>
        </tr>
        <tr>
        	<td class="middle-left-child"></td>
            <td class="middle-right-child">

            </td>
             <td class="middle-left-child" width="<?php echo $col1;?>">Fuel Type:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">
             <input type="text" readonly value="<?php echo $row['fuel']?>">
             </td>
        </tr>
        <tr>
        	 <td class="middle-left-child" width="<?php echo $col1;?>">Market Value:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <?php



				if($row2['VehCoverage']==='SC'){
					$v=$row2['VehicleValue'];
				}
				else if(!$use_extra){
						$v=dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']);
				}
				else{
					$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$row[a_number]'";
					$rs3 = mysql_query($sql3);
					$row3=mysql_fetch_array($rs3);
					if($row3['vehicle_use']==='private'){
						$vuse='PR';
					}
					else{
						$vuse="CM";
					}
					$v=dayValue($row3['year'],$row3['cat_value'],$vuse);
				}
			?>
            <input readonly type="text" style="text-align:right" name="market_value" id="market_value" size="8" value="<?php echo number_format($v,2,'.',',');?>"/> <input readonly type="text" name="max_rep" style="text-align:right" id="max_rep"
						size="8" value="<?php
			if(trim($row1['manu_date'])!=='' && trim($row13['Date_Loss'])!=='' && $row2['VehCoverage']==='SC'){
				$tv=dayValueA($row1['manu_date'],substr(date("y-m-d", strtotime($row13['Date_Loss'])),0,10),$row2['VehicleValue'],$row2['VehUse']);
			}
			else if($row2['VehCoverage']==='SC'){
				$tv=dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']);
			}
			else if(trim($row1['manu_date'])!=='' && trim($row13['Date_Loss'])!==''){
				$tv=dayValueA($row1['manu_date'],substr(date("y-m-d", strtotime($row13['Date_Loss'])),0,10),$row2['VehicleValue'],$row2['VehUse']);
			}
			if(trim($row1['manu_date'])!=='' && trim($row13['Date_Loss'])!=='' && $use_extra){
					$tv=dayValueA($row1['manu_date'],substr(date("y-m-d", strtotime($row13['Date_Loss'])),0,10),$row3['cat_value'],$vuse);
			}
			echo number_format($tv,2);?>"/>


            </td>
           	<td class="middle-left-child" width="<?php echo $col1;?>">Transmisssion:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <select name="transmission" id="transmission" style="background-color:#FAD090">
            	<option id="Automatic" <?php if($row1['transmission']==='Automatic'){ echo 'selected="selected"';}?>>Automatic</option>
                <option id="Manual"  <?php if($row1['transmission']==='Manual'){ echo 'selected="selected"';}?>>Manual</option>
                </select>
			&nbsp;RHD: <input type="checkbox" style="background-color:#FAD090" name="right" <?php if($row['RightHandDrive']==1){ echo 'checked="checked"';} ?>/>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child">Max Rep. Value:</td>
            <td class="middle-right-child"><input readonly type="text" name="max_rep" style="text-align:right" id="max_rep" size="8" value="<?php echo number_format($v/3*2,2,'.',',');?>"/> <input readonly type="text" name="max_rep" style="text-align:right" id="max_rep" size="8" value="<?php echo number_format($tv/3*2,2,'.',',');?>"/></td>
             <td class="middle-left-child" width="<?php echo $col1;?>">Manufacturing Date:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" id="manu_date" name="manu_date" readonly value="<?php echo $row1['manu_date'];?>"/>
  <button id="manu_date_button">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#manu_date_button').click(
      function(e) {
        $('#manu_date').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d"}).focus();
        e.preventDefault();
      } );
  </script></td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Wreck Value:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"> <input readonly type="text" name="max_rep" style="text-align:right" id="max_rep" size="8" value="<?php echo number_format($v/3,2,'.',',');?>"/> 	<input readonly type="text" name="max_rep" style="text-align:right" id="max_rep" size="8" value="<?php echo number_format($tv/3,2,'.',',');?>"/>
            </td>
        	 <td class="middle-left-child" width="<?php echo $col1;?>">Insured:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><?php
				if($_SESSION['user_level'] >= RR_LEVEL){
			?>
            <input type="checkbox" style="background-color:#FAD090" name="insured" <?php if($row['insured']){echo 'checked="checked"';}?> id="insured" onchange="isInsured()"/>&nbsp;<select name="insured_at" id="insured_at" style="background-color:#FAD090; <?php if($row['insured']){echo 'display:none';} else {echo 'display:inline';} ?>">
            	<option value="1" <?php if($row['insured_at']==1){ echo 'selected="selected"';}?>>No Insurance</option>
                <?php
					$sql2 = "SELECT * FROM insurance_company WHERE id != 1 ORDER BY name asc";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						if($row['insured_at']==$row2['id']){
							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';
						}
						else{
							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
						}
					}
				?>
            </select>
            <?php }
				else{
			?>
            	<input type="hidden" name="insured" value="<?php echo $row['insured'];?>"/>
                <input type="hidden" name="insured_at" value="<?php echo $row['insured_at'];?>"/>
                <input type="text" style="background-color:#FAD090" readonly name="insuredd" value="<?php
					if($row['insured']){
						echo 'Yes';
					}
					else{
						$sql2 = "SELECT * from insurance_company where id='$row[insured_at]'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo 'No insured at: '.$row2['name'];
					}
				?>"/>
            <?php
				}
			?></td>
        </tr>
         <tr>
         	<?php
			 	$away_from_wreck=($tv/3)-$row1['wreck_value'];
			 ?>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Adj. Wreck Value:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090; text-align:right" name="wreck_value" id="wreck_value" size="8" value="<?php echo number_format($row1['wreck_value'],2,'.','');?>" <?php echo $readonly; ?> /> / <input readonly type="text" style="text-align:right" name="wreck_away" id="wreck_away" size="8" value="<?php echo number_format($away_from_wreck,2,'.','');?>" />
            </td>
            <td class="middle-child" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Est. Labor / Parts OEM:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090; text-align:right" name="est_rep_cost" id="est_rep_cost" size="8" value="<?php echo number_format($row1['est_rep_cost'],2,'.','');?>" <?php echo $readonly; ?> /> / <input type="text" style="background-color:#FAD090; text-align:right" name="est_parts_cost" id="est_parts_cost" size="8" value="<?php echo number_format($row1['est_parts_cost'],2,'.','');?>" <?php echo $readonly; ?> />
            </td>
            <td class="middle-child" colspan="2">&nbsp;</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Unf. Labor / Parts:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090; text-align:right" name="unf_rep_cost" id="unf_rep_cost" size="8" value="<?php echo number_format($row1['unf_rep_cost'],2,'.','');?>"  /> / <input type="text" style="background-color:#FAD090; text-align:right" name="unf_parts_cost" id="unf_parts_cost" size="8" value="<?php echo number_format($row1['unf_parts_cost'],2,'.','');?>"  />
            </td>
            <td class="middle-child" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Depr. Parts Norm / 5%:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="text-align:right" n size="8" readonly value="<?php
			$partsDep=partsDep($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost'],$vuse);
			$partsDep5=partsDep5($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost']);
			$time_loss=$row1['days_rep']*time_loss_rate($_SESSION['country']);
			echo number_format(partsDep($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost'],$vuse),2);

			?>"/> / <input readonly type="text" style="text-align:right" name="est_parts_dep" id="est_parts_dep" size="8" value="<?php echo number_format(partsDep5($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost']),2,'.','');?>" />
            </td>
            <td class="middle-child" colspan="2">&nbsp;</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Est. Cash Loss:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="text-align:right" n size="8" readonly value="<?php
			$partsDep=partsDep($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost'],$vuse);
			$partsDep5=partsDep5($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost']);
			$rep=max(($row1['est_rep_cost']+$partsDep),($row1['est_rep_cost']+$partsDep5));
			//$rep=10;
			//$time_loss=$row1['days_rep']*time_loss_rate($_SESSION['country']);
			echo number_format(partsDep($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost'],$vuse)+$row1['est_rep_cost']+$time_loss,2);

			?>"/> / <input readonly type="text" style="text-align:right" name="est_parts_dep" id="est_parts_dep" size="8" value="<?php echo number_format(partsDep5($vyear,$row1['manu_date'],$dateofloss,$row1['est_parts_cost'])+$row1['est_rep_cost']+$time_loss,2,'.','');?>" />
            </td>
            <td class="middle-child" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        	<?php
				$away_from_repair=$rep-$row1['est_nrs_cost'];
				if($away_from_repair>=0){
					//positive
					$away_from_repair_color='style="color: #008000; text-align:right; font-weight: bold;"';
				}
				else if($away_from_repair<0){
					$away_from_repair_color='style="color: #FF0000; text-align:right; font-weight: bold;"';
				}
				else{
					$away_from_repair_color='style="text-align:right;"';
				}
			?>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Est. Repair Total</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090; text-align:right" name="est_nrs_cost" id="est_nrs_cost" size="8" value="<?php echo number_format($row1['est_nrs_cost'],2,'.','');?>" <?php echo $readonly; ?> /> <input readonly type="text" name="dif1" <?php echo $away_from_repair_color; ?> size="8" value="<?php echo number_format($away_from_repair,2,'.',',');?>"/> </td>
           	<td class="bottom-child" colspan="2"><div class="buttonwrapper" id="extra_info_not_insured" style=" <?php if($row['insured']){echo 'display:none';} else {echo 'display:inline';} ?>">
					<a class="squarebutton" href="javascript:popacc('not_insured_extra.php?sc=<?php echo $row['a_number']?>');"><span>Extra Information</span></a>
				</div></td>
        <tr>
        	<?php
				$away_from_cl=min(($row1['est_rep_cost']+$partsDep5),(max(($v/3*2),($tv/3*2))))-$row1['est_cl_cost']+$time_loss;
				if($away_from_cl>=0){
					//positive
					$away_from_cl_color='style="color: #008000; text-align:right; font-weight: bold;"';
				}
				else if($away_from_cl<0){
					$away_from_cl_color='style="color: #FF0000; text-align:right; font-weight: bold;"';
				}
				else{
					$away_from_cl_color='style="text-align:right;"';
				}
			?>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Cash Loss</td>
          	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090; text-align:right" name="est_cl_cost" size="8" value="<?php echo number_format($row1['est_cl_cost'],2,'.','');?>" <?php echo $readonly; ?>/> <input readonly type="text" name="dif1" <?php echo $away_from_cl_color;?> size="8" value="<?php echo number_format($away_from_cl,2,'.',',');?>"/>

            </td>
            <td class="top-child_h" colspan="2" style="color:#148540" align="center">Accident Information</td>
      	</tr>
        <tr>
       	  <td class="middle-left-child" width="<?php echo $col1;?>">Mileage:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row1['mileage'];?>" style="background-color:#FAD090;" name="mileage" id="mileage" size="18"/ >

       	  </td>
          <?php if ($_SESSION['user_level']>1){?>
             <td class="middle-left-child" width="<?php echo $col1;?>">Date of loss:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" readonly size="20" maxlength="30" value="<?php

			//$ddate= new datetime(substr($row13['Date_Loss'],0,10));
			$ddate= new datetime(date("d-M-y", strtotime($row13['Date_Loss'])));
			echo date_format($ddate,"d M, Y");
			?>"/>
            </td>
            <?php } ?>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top	">Bodyshop:</td>
          	<td class="middle-right-child" width="<?php echo $col2;?>">
             <select name="bodyshop_id" id="bodyshop_id" style="background-color:#FAD090" <?php echo $readonly; ?>>
            	<option value="0"></option>
             <?php
				 	$preferred=-1;
					$sql5="SELECT * FROM `bodyshop` WHERE `active`=1 order by `name`";
					$rs5=mysql_query($sql5);
					while($row5=mysql_fetch_array($rs5)){
						if($row5['id']==$row1['bodyshop_id']){
							echo '<option selected="selected" value="'.$row5['id'].'">'.$row5['name'].'</option>';
							if($row1['bodyshop_id']==''){
								$preferred=-1;
							}
							else{
								$preferred=$row5['preferred'];
							}
						}
						else{
							echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';
						}
					}

				?>
                </select>
				</br>
  		  		<?php
					if($preferred==1){
						echo 'Preferred';
					}
					else if ($preferred==0){
						echo 'Not Preferred';
					}
				?>
   		  </td>
            <?php if ($_SESSION['user_level']>1){?>
            <td class="middle-left-child" width="<?php echo $col1;?>">Type of Impact:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" size="28" value="<?php
				$sqlt="SELECT * FROM vehicle_impact WHERE id='".$row14['rep_impact']."'";
				$rst=mysql_query($sqlt);
				$rowt=mysql_fetch_array($rst);
				echo $rowt['description'];
			?>"/>
            </td>
            <?php } ?>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Modification:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
           	  <input type="text" name="modification" style="background-color:#FAD090;" value="<?php echo $row1['modification'];?>"/>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Type of Damage:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">
             <input type="text" size="28" value="<?php
				$sqlt="SELECT * FROM vehicle_damage WHERE id='".$row14['rep_damage']."'";
				$rst=mysql_query($sqlt);
				$rowt=mysql_fetch_array($rst);
				echo $rowt['description'];
			?>"/>
             </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
        	<td class="middle-right-child" width="<?php echo $col2;?>">
            <select name="status_id" id="status_id" style="background-color:#FAD090">
            	<?php
					$sql2 = "SELECT * FROM `status_survey` order by sort ASC";
					$rs2 = mysql_query($sql2);
					while($row2 = mysql_fetch_array($rs2)){
						if($row1['status_id'] == $row2['id']){
							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['status'].'</option>';
						}
						else if($row2['id']==4 && ($_SESSION['user_level']>=POWER_LEVEL || $_SESSION['user_id']==112)){
							echo '<option value="'.$row2['id'].'">'.$row2['status'].'</option>';
						}
						else if($row2['id']!=4){
							echo '<option value="'.$row2['id'].'">'.$row2['status'].'</option>';
						}
					}

				?>
            </select>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Airbag Status:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
           	<input type="text" readonly size="20" value="<?php  echo $row14['airbag_status'];?>" />
            </td>
        </tr>
       		<td class="middle-left-child" width="<?php echo $col1;?>">Request:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><?php
            	$uid = $row1['requested_by_id'];
				$sql2 = "SELECT * FROM `rental_request` WHERE id='$uid'";
				$rs2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($rs2);
				echo $row2['name'];
			?></td>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Is Car Drivable:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <input type="text" readonly size="10" value="<?php  echo $row14['car_driveable'];?>"/>
            </td>
        </tr>
         <tr>
         	<tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Closed:</td>
        	<td class="middle-right-child" width="<?php echo $col2;?>">
            	<?php
					if(($_SESSION['user_level']>=POWER_LEVEL || $_SESSION['user_id']==112)){
				?>
            	<input type="text" id="closeddt" name="closeddt" readonly value="<?php echo $row1['close_time'];?>"/>
  <button id="closeddtbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#closeddtbutton').click(
      function(e) {
        $('#closeddt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
  			<?php }
				else {
					echo '<input type="text" disabled="disabled" value="'.$row1['close_time'].'"/>';
				}
			?>
          	</td>
         	<td class="middle-left-child" width="<?php echo $col1;?>">Condition Exterior:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <select name="cond_ext" style="background-color:#FAD090;">
           	  <option value="" <?php if($row1['cond_ext']===''){ echo 'selected="selected"';} ?>></option>
              <option value="Unknown" <?php if($row1['cond_ext']==='Unknown'){ echo 'selected="selected"';} ?>>Unknown</option>
              <option value="Excellent" <?php if($row1['cond_ext']==='Excellent'){ echo 'selected="selected"';} ?>>Excellent</option>
                <option value="Good" <?php if($row1['cond_ext']==='Good'){ echo 'selected="selected"';} ?>>Good</option>
              <option value="Moderate" <?php if($row1['cond_ext']==='Moderate'){ echo 'selected="selected"';} ?>>Moderate</option>
              <option value="Poor" <?php if($row1['cond_ext']==='Poor'){ echo 'selected="selected"';} ?>>Poor</option>
                 <option value="Bad" <?php if($row1['cond_ext']==='Bad'){ echo 'selected="selected"';} ?>>Bad</option>
            </select>
            </td>
      	</tr>
       		<td class="bottom-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="bottom-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Condition Interior:</td>
            <td class="bottom-right-child" width="<?php echo $col2;?>">
            <select name="cond_int" style="background-color:#FAD090;">
            	<option value="" <?php if($row1['cond_int']===''){ echo 'selected="selected"';} ?>></option>
                <option value="Unknown" <?php if($row1['cond_int']==='Unknown'){ echo 'selected="selected"';} ?>>Unknown</option>
                <option value="Excellent" <?php if($row1['cond_int']==='Excellent'){ echo 'selected="selected"';} ?>>Excellent</option>
                <option value="Good" <?php if($row1['cond_int']==='Good'){ echo 'selected="selected"';} ?>>Good</option>
                <option value="Moderate" <?php if($row1['cond_int']==='Moderate'){ echo 'selected="selected"';} ?>>Moderate</option>
                <option value="Poor" <?php if($row1['cond_int']==='Poor'){ echo 'selected="selected"';} ?>>Poor</option>
                 <option value="Bad" <?php if($row1['cond_int']==='Bad'){ echo 'selected="selected"';} ?>>Bad</option>
            </select>
            </td>
        </tr>
        <tr>
        	<td class="top-child_h" colspan="2" style="color:#148540" align="center">Adjuster Information</td>
              <td class="top-child_h" colspan="2" style="color:#148540" align="center">Claims Department Information</td>
        </tr>
        <tr>
        	<td class="middle-left-child" colspan="1">Adjuster Report:</td>
            <td class="middle-right-child" colspan="1">
            	<div class="buttonwrapper">
					<a class="squarebutton" target="_blank" href="ajuster_report.php?sc=<?php echo $survey_id;?>"><span>Survey Report</span></a>
				</div>
           </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Ajuster Request:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>">
            <div class="buttonwrapper">
            <a class="squarebutton" target="_blank" href="adjuster_request.php?id=<?php echo $survey_id ?>"><span>Adjuster Request</span></a></div>
            </td>
      	</tr>
        <tr>
         	<td class="middle-left-child" width="<?php echo $col1;?>">Parts List</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><div class="buttonwrapper">
					<a class="squarebutton" target="_blank" href="survey_parts.php?sid=<?php echo $survey_id;?>"><span>Parts List</span></a>
				</div>

            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Claim No.:<br/>Handler:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
         	<input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" readonly size="20" value="<?php echo $row['claimNo'];?>"/>
            <br/>
            <input type="text" readonly size="20" value="<?php
            $sql12 = "SELECT * FROM rental_request WHERE id=".$row['claimsAttId'];
			$rs12=mysql_query($sql12);
			$row12=mysql_fetch_array($rs12);
			echo $row12['name'];
			?>"/>
            </td>
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Reqs

            </td>
           	<td class="bottom-right-child" width="<?php echo $col2;?>">
            <div class="buttonwrapper">
					<a class="squarebutton" target="_blank" href="survey_req.php?sid=<?php echo $survey_id;?>"><span>Reqs</span></a>
				</div>

            </td>
            <td class="bottom-left-child" width="<?php echo $col1;?>"></td>
            <td class="bottom-right-child" width="<?php echo $col2;?>"></td>
        </tr>
        <tr>
        	<td class="top-child_h" colspan="4" style="color:#148540" align="center">Vehicle Comments</td>
      	</tr>
        <?php
			$lic =  $row['a_number'];
			$sql3 = "SELECT * FROM vehicle_com WHERE license='$lic'";
			$rs3 = mysql_query($sql3);
			if(mysql_num_rows($rs3)!=0){
			while($row3 = mysql_fetch_array($rs3)){
				?>
                	<tr><td class="middle-child" colspan="4"><span style="color:red;font-weight:bold"><?php
						echo $row3['comment'];?></span>
                        <?php if(checkAdmin()){ ?>
					<a style="color:#DCB272" href="delete_veh_comment.php?id=<?php echo $row3['id']?>&lic=<?php echo $lic?>">Delete</a>

					<a style="color:#DCB272" href="new_com.php?lic=<?php echo $lic;?>&id=<?php echo $row3['id']?>&lic=<?php echo $lic?>&sc=<?php echo $id?>">Edit</a>
                  	<?php } ?>
                        </td></tr>
                <?php	}
					}
				?>
                <tr><td class="bottom-child"  colspan="4">&nbsp;</td>
        </tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="top-right-child" width="<?php echo $col2;?>" colspan="3">&nbsp;</td>
        </tr>
        <tr>
       		<td class="bottom-left-child" width="<?php echo $col1;?>">Notes:</td>
       		<td class="bottom-right-child" width="<?php echo $col2;?>" colspan="3"><textarea required name="notes" cols="50" rows="4" style="background-color:#FAD090"><?php echo stripcslashes($row1['notes']);?></textarea></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Picture/PDF:</td>
         	<td class="top-right-child" colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" /></td>
         </td>
         <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Parts Import:</td>
         	<td class="bottom-right-child" colspan="3"><input name="parts_list" type="file" id="parts_list" size="40" /></td>
         </td>
       	<tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="4" cellspacing="0" class="top-left-child" align="right">
       			<input type="submit" name="save" value="Submit" style="width:50px"/>
            </td>
        	<td colspan="1" cellspacing="0" class="top-right-child" align="right">
            	<?php
					if($_SESSION['user_level']>=4){
				?>
            	<input type="submit" name="delete" value="Delete" style="width:50px"/>
       			<?php } ?>
            </td>
   	  </tr>
       <tr>
        	<td colspan="4" cellspacing="0" class="bottom-left-child" align="right"><?php
			//Approval 4/8/2016

			if($preferred==0){
				$ids=explode(",",$adj_approve_non_preferred_ids);
			}
			else{
				$ids=explode(",",$adj_approve_ids);
			}
			if($row1['approved']==0 && in_array($_SESSION['user_id'],$ids)){
				echo '<input type="submit" name="approve" value="Approve" style="width:50px"/>';
			}
			?>
            </td>
        	<td colspan="1" cellspacing="0" class="bottom-right-child" align="right">&nbsp;</td>
   	  </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="top-child_h" colspan="5" style="color:#148540">History</td></tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
        <tr><td class="bottom-child" colspan="5">
        	<table width="100%" cellspacing="0">
        	 <?php
					$sql2 = "SELECT * FROM service_req WHERE `a_number`='$lic' AND `delete` = 0 order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";
					$rs8 = mysql_query($sql2);
					if(mysql_num_rows($rs8)!=0){
						$col1 = 45;
						$col2 = 132;
						$col3 = 75;
						$col4 = 50;
						?>
                        	<tr class="thead">
                                <td width="<?php echo $col1;?>">ID</td>
                                <td width="<?php echo $col2+15;?>">Date</td>
                                <td width="<?php echo $col2;?>">Car</td>
                                <td width="<?php echo $col3;?>">A Number</td>
                                <td width="<?php echo $col2;?>">Location</td>
                                <td width="<?php echo $col2-15;?>">Job</td>
                                <td width="<?php echo $col4;?>">Attendee</td>
                                <td width="<?php echo $col4;?>">Charged</td>
                                <td width="<?php echo $col4;?>">Insured</td>
                                <td width="<?php echo $col4;?>">Image</td>
                                <td width="<?php echo $col4;?>">Status</td>
                            </tr>
                        <?php while($row = mysql_fetch_array($rs8)){
				?>
                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                                <td <?php
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
                                <td width="<?php echo $col4;?>"><?php
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
        	</table>
        </td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="top-child_h" colspan="5" style="color:#148540"><h4>Rental</h4></td></tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">
        	<table width="100%" cellspacing="0">

        	 <?php
					if($temp_pol!==''){
						$sql8 = "SELECT * FROM `rental` WHERE (`policy_no`='".$temp_pol."' OR `service_req_id`='".$id."') AND `active`=1 order by STR_TO_DATE( `time_out` , '%m-%d-%Y %h:%m' ) DESC";
					}
					else{
						$sql8 = "SELECT * FROM `rental` WHERE `service_req_id`='".$id."' AND `active`=1 order by STR_TO_DATE( `time_out` , '%m-%d-%Y %h:%m' ) DESC";
					}

					$rs8 = mysql_query($sql8);
					if(mysql_num_rows($rs8)!=0){
						$col1 = 45;
						$col2 = 132;
						$col3 = 75;
						$col4 = 50;

						?>
                        	<tr class="thead">
                                <td class="middle-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>
                                <td width="<?php echo $col2+15;?>">Request By</td>
                                <td width="<?php echo $col2;?>">Car</td>
                                <td width="<?php echo $col3;?>">A Number</td>
                                <td width="<?php echo $col2;?>">Time Out</td>
                                <td width="<?php echo $col2-15;?>">Time In</td>
                                <td width="<?php echo $col4;?>">ClaimsNo</td>
                                <td width="<?php echo $col4;?>">&nbsp;</td>
                                <td width="<?php echo $col4;?>">Total Days</td>
                                <td width="<?php echo $col4;?>">Total Charged</td>
                                <td class="middle-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>
                            </tr>
                        <?php while($row = mysql_fetch_array($rs8)){
				?>
                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                                <td class="middle-left-child" style="background-color:#E1DDDC" width="<?php echo $col1;?>"><a style="color:#DCB272" href="rental_detail.php?id=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
                                <td width="<?php echo $col2+10;?>"><?php
                               		$sql10 = "SELECT * FROM rental_request WHERE id='".$row['requested_by']."'";
									$rs10 = mysql_query($sql10);
									$row10 = mysql_fetch_array($rs10);
									echo $row10['name'];
								?></td>
                                 <td width="<?php echo $col2;?>"><?php
                                 	$sql10 = "SELECT * FROM rental_vehicle WHERE id='".$row['rental_vehicle_id']."'";
									$rs10 = mysql_query($sql10);
									$row10 = mysql_fetch_array($rs10);
									echo $row10['make'].' '.$row10['model'];
								 	$rate = $row10['rental'];
								 ?></td>
                              <td width="<?php echo $col3;?>"><?php echo $row10['licenseplate'];?></td>
                                <td width="<?php echo $col2;?>"><?php echo $row['time_out'];?></td>
                                <td width="<?php echo $col2-15;?>"><?php echo $row['time_in'];?></td>
                                <td width="<?php echo $col3;?>"><?php echo $row['claimNo'];?></td>
                                <td width="<?php echo $col3;?>">&nbsp;</td>
                                <td width="<?php echo $col3;?>"><?php
									list($month,$day,$year) = explode('-',substr($row['time_out'],0,10));
									list($date,$time) = explode(' ',$row['time_out']);
									$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
									$date2 = new DateTime(date("Y-m-d H:i"));
									if(strlen(trim($row['time_in']))!=0){
										list($month,$day,$year) = explode('-',substr($row['time_in'],0,10));
										list($date,$time) = explode(' ',$row['time_in']);
										$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
									}
									$interval = $date1->diff($date2);
									if($interval->h==0){
										echo $interval->days;
									}
									else{
										echo ($interval->days+1);
									}

								?></td>
                                <td width="<?php echo $col3;?>"><?php
									list($month,$day,$year) = explode('-',substr($row['time_out'],0,10));
									list($date,$time) = explode(' ',$row['time_out']);
									$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
									$date2 = new DateTime(date("Y-m-d H:i"));
									if(strlen(trim($row['time_in']))!=0){
										list($month,$day,$year) = explode('-',substr($row['time_in'],0,10));
										list($date,$time) = explode(' ',$row['time_in']);
										$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
									}
									$interval = $date1->diff($date2);
									if($interval->h==0){
										echo number_format($rate*$interval->days,2);
									}
									else{
										echo number_format($rate*($interval->days+1),2);
									}

								?></td>
                                <td class="middle-right-child" style="background-color:#E1DDDC" width="<?php echo $col4;?>"><?php echo $row['status']; ?></td>
                            </tr>
                <?php	} //End While Loop
					} //End if Loop
				?>
                <tr><td class="bottom-child" colspan="11">&nbsp;</td></tr>
        	</table>
        </td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
        <?php
			$id=$_REQUEST['sid'];
			$dirname = FOLDER."simage/".$id;
			$thumbs = FOLDER."sthumbs/".$id;
			$docs = FOLDER."sdocs/".$id;
			$docst= FOLDER."sdocsthumbs/".$id;
			$documents = scandir($docs);
			$images = scandir($dirname);
			$ignore = Array(".", "..");
			$n = 1;
			$r = 1;
			foreach($documents as $doc){
				if(!in_array($doc,$ignore)){
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Document(s)</td></tr>';
						$n = 0;
					}
				if(file_exists($docst= FOLDER."sdocsthumbs/".$id)){
					if($r==1){
						echo '<tr><td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
					}
					else if($r==4){
						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
						$r=0;
					}
					else{
						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
					}
					if($_SESSION['user_level'] == ADMIN_LEVEL && $r!=4){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else if($_SESSION['user_level'] == ADMIN_LEVEL){
						echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'&s=1">Delete</a></td></tr>';
					}

					$r++;
				}
				else{
					echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a></td></tr>';
				}


				}

			}
			if(is_dir(FOLDER.'sdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=sdocs/'.$id.'">Download All Document(s)</a></td></tr>';
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <?php
			$n = 1;
			$r=1;
			foreach($images as $curimg){
				if(!in_array($curimg, $ignore)) {
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Image(s)</td></tr>';
						$n = 0;
					}
					if($r==1){
						echo'<tr>';
					}

					if($_SESSION['user_level'] > RR_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else if($_SESSION['user_level'] > RR_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}
					else{
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}

					if($r==4){
						echo '</tr>';
						$r = 0;
					}
					$r++;
				};
			}
			if($r!=1){
				while($r != 5){
					if($r==4){
						echo '<td width="25%">&nbsp;</td>';
					}
					else{
						echo '<td width="25%">&nbsp;</td>';
					}
					$r++;
				}
				echo '</tr>';
			}
		?>
        	<tr><td colspan="4">&nbsp;</td></tr>

        	<tr><td colspan="4"><?php
			if (is_dir(FOLDER.'simage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=simage/'.$id.'">Download All Image(s)</a>';
			}
			?></td></tr>
            </table>

       	<?php if($master_sc){ ?>
        <tr><td colspan="5">&nbsp;</td></tr>

        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
        <?php

			$id = $master_sc;
			$dirname = FOLDER."simage/".$id;
			$thumbs = FOLDER."sthumbs/".$id;
			$docs = FOLDER."sdocs/".$id;
			$documents = scandir($docs);
			$images = scandir($dirname);
			$ignore = Array(".", "..");
			$n = 1;
			$r = 1;
			foreach($documents as $doc){
				if(!in_array($doc,$ignore)){
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Document(s)</td></tr>';
						$n = 0;
					}
				if(file_exists($docst= FOLDER."sdocsthumbs/".$id)){
					if($r==1){
						echo '<tr><td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
					}
					else if($r==4){
						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
						$r=0;
					}
					else{
						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
					}
					if($_SESSION['user_level'] == ADMIN_LEVEL && $r!=4){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else{
						echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'&s=1">Delete</a></td></tr>';
					}

					$r++;
				}
				}
			}
			if(is_dir(FOLDER.'sdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=sdocs/'.$id.'">Download All Document(s)</a></td></tr>';
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <?php
			$n = 1;
			foreach($images as $curimg){
				if(!in_array($curimg, $ignore)) {
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Image(s)</td></tr>';
						$n = 0;
					}
					if($r==1){
						echo'<tr>';
					}

					if($_SESSION['user_level'] > RR_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else if($_SESSION['user_level'] > RR_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'&s=1">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}
					else{
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}

					if($r==4){
						echo '</tr>';
						$r = 0;
					}
					$r++;
				};
			}
			if($r!=1){
				while($r != 5){
					if($r==4){
						echo '<td width="25%">&nbsp;</td>';
					}
					else{
						echo '<td width="25%">&nbsp;</td>';
					}
					$r++;
				}
				echo '</tr>';
			}
		?>
        	<tr><td colspan="4">&nbsp;</td></tr>
        	<tr><td colspan="4"><?php
			if (is_dir(FOLDER.'simage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=simage/'.$id.'">Download All Image(s)</a>';
			}
			?></td></tr>
            </table>
            <?php } //end if master_sc?>
  </table>


        </td></tr>

        <tr><td colspan="4">&nbsp;</td></tr>
  </table>
</form>
<script type="text/javascript" src="js/functions.js">
</script>
<script type="text/javascript">
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
		var tow_reason=document.edit_sc.tow_reason
		txt = obj.options[obj.selectedIndex].value;
		if ( txt.toString()=='3' || txt.match('12') || txt.match('13') || txt.match('14') || txt.match('16') || txt.match('19') || txt.match('22') || txt.match('20') || txt.match('29') || txt.match('32') || txt.match('32') || txt.match('36')) {
			document.getElementById('loc').style.display = 'block';
			document.getElementById('tow_reason').style.display = 'block';
		}
		else{
			document.getElementById('loc').style.display = 'none';
			document.getElementById('tow_reason').style.display = 'none';
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

	function paymentTypeDisplay(){
		if( document.getElementById('licenseNo').value.length != 0){
			document.getElementById('paymentType').style.display = 'inline';
		}
		else{
			document.getElementById('paymentType').style.display = 'none';
		}
	}

	function showUpload(){
		if( document.getElementById('licenseNo').value.length != 0){
			document.getElementById('upload_drv').style.display = 'inline';
		}
		else{
			document.getElementById('upload_drv').style.display = 'none';
		}
	}

	function uploadLicense(){
		licensenumber = document.getElementById('licenseNo').value;
		pol = document.getElementById('pol').value
		num = document.getElementById('num').value
		if(document.getElementById('licenseNo').value.length != 0){
			window.open('ins_drivers_license.php?license='+licensenumber+'&pol='+pol+'&num='+num);
		}
	}

	function showUpload2(){
		if( document.getElementById('licenseNo2').value.length != 0){
			document.getElementById('upload_drv2').style.display = 'inline';
		}
		else{
			document.getElementById('upload_drv2').style.display = 'none';
		}
	}

	function uploadLicense2(){
		licensenumber = document.getElementById('licenseNo2').value;
		pol = document.getElementById('pol').value
		if(document.getElementById('licenseNo2').value.length != 0){
			window.open('ins_drivers_license.php?license='+licensenumber+'&pol='+pol);
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
		caddress = "<?php echo str_replace(array('.', ' ', "\n", "\t", "\r"), '', $caddress);;?>";
		if(document.getElementById('c_add').checked){
			document.getElementById('loc').value = caddress;
		}
		else{
			document.getElementById('loc').value = "";
		}
	}

	function copyAddress2(){
		caddress = "<?php echo str_replace(array('.', ' ', "\n", "\t", "\r"), '', $caddress);;?>";
		if(document.getElementById('c_add2').checked){
			document.getElementById('loc2').value = caddress;
		}
		else{
			document.getElementById('loc2').value = "";
		}
	}

	function bodyappdate(){
		if(document.getElementById('bodyshop_app').checked){
			document.getElementById('bodyappdate').style.display='';
		}
		else{
			document.getElementById('bodyappdate').style.display='none';
		}
	}
</script>
<?php
	if(isset($_REQUEST['error'])){
		switch($_REQUEST['error']){
			case 1:
				echo '<script type="text/javascript">alert("Cannot Find ID to couple accident");</script>';
				break;
			case 2:
				echo '<script type="text/javascript">alert("ID Found however this is not an accident");</script>';
				break;
		}
	}
?>
</body>
</html>
