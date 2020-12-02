<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if(isPolice()){
	header('location:police.php?sc='.$_REQUEST['sc']);	
}
else if($_SESSION['user_level'] < 2){
	header('location:index.php');	
}
$id = $_REQUEST['sc'];
if($_REQUEST['message']==1){
	file("http://www.nagico-abc.com/roadservice/whatsapi/src/php/sendcall.php?id=".$id);	
}

echo menu();


$col1 = 85;
$col2 = 200;
$history_item = 3;

$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';
if($_SESSION['user_level'] < POWER_LEVEL){
	$st='readonly="readonly"';	
}

$sql2 = "SELECT COUNT(*) as t FROM vehicles_2 WHERE VehStatus ='A'";
$rs2 = mysql_query($sql2);
$row2 = mysql_fetch_array($rs2);

?>


<form name="edit_sc" enctype="multipart/form-data" action="rec_sc.php?sc=<?php echo $id;?>" method="post">
  <table width="1200" cellspacing="0">
  <tr><td colspan="5"><table width="100%" cellspacing="0">
    </table>
      <table width="1200" cellspacing="0">
        <tr>
          <td colspan="5" align="center"><h8 style="color:#FFF">Service Request #
            <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?>
          </h8></td>
        </tr>
        <tr>
          <td colspan="5" align="right" style="color:#fff">Total Insured Vehicles: <?php echo $row2['t'];?></td>
        </tr>
        <?php if($row['master_sc']!=0){
		?>
        <tr>
          <td class="top-child" colspan="5">Service request came from#<a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['master_sc'];?>"/><?php echo str_pad($row['master_sc'],5,'0',STR_PAD_LEFT);?></td>
        </tr>
        <tr>
          <td class="middle-child" colspan="5">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?>
            <?php if($_SESSION['user_level'] >=4){?>
            &nbsp;
            <?php } ?></td>
          <?php
		} 
		else{
		?>
        </tr>
        <tr>
          <td class="top-child" colspan="5">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?>
            <div class="buttonwrapper">
              <?php if($_SESSION['user_level'] >=4){?>
              &nbsp;
              <?php } ?>
            </div></td>
          <?php
		}
		?>
        </tr>
        <tr>
          <?php
				if($_SESSION['user_level'] >= POWER_LEVEL){
			?>
          <td <?php if($row['status']!=2 && $row['status']!=3) {
						echo 'class="bottom-child"';
					}
					else{
						echo 'class="middle-child"';	
					}
				?> 
               	colspan="5">Time Requested:
            <input type="text" id="opendt" name="opendt" readonly="readonly" value="<?php echo $row['opendt'];?>"/>
            <button id="openbutton"> <img src="anytime/calendar.png" alt="[calendar icon]"/> </button>
            <script>
    $('#openbutton').click(
      function(e) {
        $('#opendt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
        </script>
            <?php
				}
				else{
			?></td>
          <td <?php if($row['status']!=2 && $row['status']!=3) {
						echo 'class="bottom-child"';
					}
					else{
						echo 'class="middle-child"';	
					}
				?> colspan="5">Time Requested: <?php echo $row['opendt'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            <input type="hidden" name="opendt" value="<?php echo $row['opendt'];?>" />
            <?php } ?>
            <?php }?></td>
        </tr>
        <?php
			if($row['status']==2){ //Closed
		?>
        <tr>
          <td class="bottom-child" colspan="5">Time Closed: <?php echo $row['closedt'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            <input type="hidden" name="closeddt" value="<?php echo $row['closedt'];?>" />
            <?php } ?></td>
        </tr>
        <?php
			}
			else if($row['status']==3){ //Cancelled
		?>
        <tr>
          <td class="bottom-child" colspan="5">Time Cancelled: <?php echo $row['closedt'];?></td>
        </tr>
        <?php
			}
		?>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="top-child_h" style="color:#148540" align="center">Call Information</td>
          <td colspan="2" class="top-child_h" style="color:#148540" align="center">Vehicle Insurance Information</td>
          <td rowspan="25" valign="top"><table width="100%" cellspacing="0">
            <tr>
              <td class="top-child_h" colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td>
            </tr>
            <tr>
              <td class="middle-child" colspan="2">&nbsp;</td>
            </tr>
            <?php	
				$lic = $row['a_number'];
				$policy = $row['pol'];
				//$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY Status DESC, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				$rs2 = mysql_query($sql2);
				$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$row[a_number]'";
				$rs3 = mysql_query($sql3);
				if(mysql_num_rows($rs2)!=0 || mysql_num_rows($rs3)!=0){
					$vcol1 = 45;
					$vcol2 = 55;
					if(mysql_num_rows($rs2)!=0){
						$row2 = mysql_fetch_array($rs2);
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
							}?></td>
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
						}?></td>
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
              <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php echo $row2['Date_Application'];?></td>
            </tr>
            <tr>
              <td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured From:</td>
              <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo  $row2['VehDate_Effective'];
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
							echo $row2['VehDate_Renewal'];
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
							echo $row2['YearMake'].' / '.$row2['Color'];
						}
						else{
							echo $row3['year'].' / '.$row3['color'];	
						}?></td>
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
            <tr>
              <td class="top-child_h"  colspan="2" style="color:#148540" align="center">Drivers License</td>
            </tr>
            <tr>
              <td class="middle-child"  colspan="2" rowspan="3"> Drivers License:
                <input name="licenseNo" id="licenseNo"  style="background-color:#FAD090" value="<?php echo $row['licenseNo'];?>" size="20" onkeyup="showUpload()"/>
                <br/>
                <br/>
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
                <div class="buttonwrapper" style="display:inline"> <span id="upload_drv" style="display:<?php if(strlen($row['licenseNo'])!=0) {echo 'inline';} else{echo 'none';}?>"><a class="squarebutton"  onclick="uploadLicense()">Insert Drivers License</a> </span></div>
                <?php }?></td>
            </tr>
            <tr>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td class="bottom-child"  colspan="2" rowspan="3">Add. Drivers License:
                <input name="licenseNo2" id="licenseNo2"  style="background-color:#FAD090" value="<?php echo $row['licenseNo2'];?>" size="20" onkeyup="showUpload2()"/>
                <br/>
                <br/>
                <?php
				 	//$license = $row['licenseNo2'];
                 	$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo2]'";
					$rs3 = mysql_query($sql3);
					if($row3 = mysql_fetch_array($rs3)){
				 ?>
                <a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="200" src="download.php?file=<?php echo $row3['loc'];?>" /></a>
                    
                <?php } 
				 if ($_SESSION['user_id'] > 1){
					 ?>
                <br/>
                <br/>
                <div class="buttonwrapper" style="display:inline"> <span id="upload_drv2" style="display:<?php if(strlen($row['licenseNo2'])!=0) {echo 'inline';} else{echo 'none';}?>"><a class="squarebutton"  onclick="uploadLicense2()">Insert Drivers License</a> </span></div>
                <?php }?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Job:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php if($_SESSION['user_level'] >= RR_LEVEL){
			
			?>
            <select name="job" style="background-color:#FAD090" onchange="display(this)">
              <?php
						$jid = $row['job'];
						$sql2 = "SELECT * FROM jobs order by description";
						$rs2 = mysql_query($sql2);
						while($row2=mysql_fetch_array($rs2)){
							if($jid == $row2['id']){
								echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['description'].'</option>';	
							}
							else{
								echo '<option value="'.$row2['id'].'">'.$row2['description'].'</option>';	
							}
						}
					?>
            </select>
            <?php
			}
			else{
			?>
            <input type="text" <?php echo $st;?> name="jobd" style="background-color:#FAD090" value="<?php
                    	$jid = $row['job'];
						$sql2 = "SELECT * from `jobs` WHERE `id` = '$jid'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo $row2['description'];
					?>"/>
            <input type="hidden" name="job" value="<?php echo $row['job']; ?>"/>
            <?php 
			}
			?></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Car Number:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="num" size="15" value="<?php echo $row['a_number'];?>"/></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" size="25" value="<?php echo $row['location'];?>"/></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="car" size="25" value="<?php echo $row['car'];?>" /></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>"><?php
            	if($row['job']!=7){ echo 'Location to:';}
				else { echo '';}
			?></td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if(strcmp($row['toloc'],'')!=0){ echo $st;}?> type="text" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13 && $row['job'] != 14  && $row['job'] != 16  && $row['job'] != 19  && $row['job'] != 20  && $row['job'] != 22 && $row['job'] != 29 && $row['job'] != 32 && $row['job'] != 33 && $row['job'] != 36){ echo 'display:none'; }?>" name="toloc" id="loc" size="23" value="<?php echo $row['to_location'];?>"/>
            VII:
            <input type="checkbox" name="c_add" id="c_add" onchange="copyAddress()"/></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Vin:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php if($_SESSION['user_level'] < 3){echo $st;}?> type="text" style="background-color:#FAD090" name="vin" size="25" value="<?php echo $row['vin'];?>" /></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">District:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="district" style="background-color:#FAD090">
            <?php
						$sql3 = "SELECT * FROM districts order by district";
						$rs3 = mysql_query($sql3);
						while($row3=mysql_fetch_array($rs3)){
							if($row3['id'] == $row['district']){
								echo '<option selected="selected" value="'.$row3['id'].'">'.$row3['district'].'</option>';		
							}
							else{
								echo '<option value="'.$row3['id'].'">'.$row3['district'].'</option>';	
							}
						}
					?>
          </select></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Engine Nr.:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 3){echo $st;}?> type="text" style="background-color:#FAD090" name="engine" id="engine" size="20" value="<?php echo $row['engine'];?>"/></td>
        </tr>
        <tr>
          <td class="middle-child" colspan="2">&nbsp;</td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Policy Nr.:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="pol" id="pol" size="20" value="<?php echo $row['pol'];?>"/></td>
        </tr>
        <tr>
          <td class="middle-child" colspan="2">&nbsp;</td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Fuel Type:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="fuel" style="background-color:#FAD090">
            <option value="Gasoline" <?php if($row['fuel'] === 'Gasoline'){echo 'selected="selected"';}?> >Gasoline</option>
            <option value="Deisel" <?php if($row['fuel'] === 'Deisel'){echo 'selected="selected"';}?>>Deisel</option>
            <option value="LPG" <?php if($row['fuel'] === 'LPG'){echo 'selected="selected"';}?>>LPG</option>
            <option value="Electric" <?php if($row['fuel'] === 'Electric'){echo 'selected="selected"';}?>>Electric</option>
            <option value="Hybrid" <?php if($row['fuel'] === 'Hybrid'){echo 'selected="selected"';}?>>Hybrid</option>
          </select></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php if ( ($_SESSION['user_level'] >= RR_LEVEL && $row['job']!=11) || $_SESSION['user_level'] > RR_LEVEL){
			?>
            <select name="attendee" style="background-color:#FAD090">
              <?php
						$tp = 0;
						$aid = $row['attendee_id'];
						$sql2 = "SELECT * FROM attendee WHERE id != 10 AND active=1 order by s_name ";
						$rs2 = mysql_query($sql2);
						while($row2=mysql_fetch_array($rs2)){
							if($aid == $row2['id']){
								echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['s_name'].'</option>';
								$tp = $row2['tp'];
							}
							else{
								echo '<option value="'.$row2['id'].'">'.$row2['s_name'].'</option>';
							}
						}
					?>
            </select>
            <?php 
			}
			else{
			?>
            <input readonly="readonly" type="text" value="<?php
						$aid = $row['attendee_id'];
						$sql2 = "SELECT * FROM attendee WHERE `id`='$aid'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo $row2['s_name'];
					?>" name="attendeed" style="background-color:#FAD090; text-align:left" size="20" />
            <input type="hidden" name="attendee" value="<?php echo $row['attendee_id'];?>"/>
            <?php
			}
			if($_SESSION['user_level'] >= POWER_LEVEL){
				?>
            &nbsp;NSH:
            <input type="checkbox" name="over_time" <?php if($row['over_time']) echo 'checked="checked"'; ?>/>
            <?php	
			}
			else{
				?>
            &nbsp;NSH:
            <?php if($row['over_time']) {echo'Yes';} else {echo 'No';}?>
            <?php	
			}
			?></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">Insured:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php 
				if($_SESSION['user_level'] >= POWER_LEVEL){
			?>
            <input type="checkbox" style="background-color:#FAD090" name="insured" <?php if($row['insured']){echo 'checked="checked"';}?> id="insured" onchange="isInsured()"/>
            &nbsp;
            <select name="insured_at" id="insured_at" style="background-color:#FAD090; <?php if($row['insured']){echo 'display:none';} else {echo 'display:inline';} ?>">
              <option value="1" <?php if($row['insured_at']==1){ echo 'selected="selected"';}?>>N/A</option>
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
            <input type="text" style="background-color:#FAD090" readonly="readonly" name="insuredd" value="<?php 
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
          <td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['requestedBy'];?>" style="background-color:#FAD090;" name="requestedBy" id="requestedBy" size="20" maxlength="50"/>
            VII:
            <input type="checkbox" name="c_req" id="c_req" onchange="copyName()"/></td>
          <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><div class="buttonwrapper" id="extra_info_not_insured" style=" <?php if($row['insured']){echo 'display:none';} else {echo 'display:inline';} ?>"> <a class="squarebutton" href="javascript:popacc('not_insured_extra.php?sc=<?php echo $row['a_number']?>');"><span>Extra Information</span></a> </div></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['AddPhone'];?>" style="background-color:#FAD090;" name="addphone" id="addphone" size="18"/ >
            VII:
            <input type="checkbox" name="c_mob" id="c_mob" onchange="copyPhone()"/></td>
          <td class="bottom-child" colspan="2">Right Hand Drv.:
            <input type="checkbox" style="background-color:#FAD090" name="right" <?php if($row['RightHandDrive']==1){ echo 'checked="checked"';} ?>/></td>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Charged:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" value="<?php echo number_format($row['charged'],2,'.','');?>" style="background-color:#FAD090; text-align:right" name="charged" size="10"/>
            Afl.&nbsp;
            <?php
					$user_id = $_SESSION['user_id'];
					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
				?>
            <a style="color:#DCB272" href="change_charge.php?sc=<?php echo $id?>">Change</a>
            <?php } ?></td>
          <td class="top-child_h" colspan="2" style="color:#148540" align="center">Adm/Payment Info</td>
        </tr>
        <tr>
          <?php if($tp && $_SESSION['user_level']>3){ ?>
          <td class="middle-left-child" width="<?php echo $col1;?>">TP Charge:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="number" value="<?php echo number_format($row['tpCharged'],2,'.','')?>" name="tpCharged" style="background-color:#FAD090; text-align:right" size="10" maxlength="7"/>
            Afl.&nbsp;Rec.
            <input type="checkbox" name="tpChargedReceived" <?php if($row['tpChargedReceived']){echo 'checked="checked"';} ?> /></td>
          <?php } 
			if (!$tp && $_SESSION['user_level']>1){
			?>
          <td class="middle-left-child" width="<?php echo $col1;?>"></td>
          <td class="middle-right-child" width="<?php echo $col2;?>"></td>
          <?php
        	}
			if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Receipt:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($row['receipt']) {echo $st;}?> type="text" value="<?php echo $row['receipt'];?>" style="background-color:#FAD090;" name="receipt" size="20"/></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Vehicle Present:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  type="checkbox" id="present"  style="background-color:#FAD090" name="present" <?php if($row['present'] ==1 || !$row['present']){echo 'checked="checked"';}?>/>
            &nbsp;&nbsp;&nbsp;RS Present:&nbsp;
            <input type="checkbox" name="rspresent" <?php if($row['rspresent']){echo 'checked="checked"';}?>/></td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Invoice #:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="invoice" size="20" maxlength="30" value="<?php echo $row['invoice'];?>"/></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php 
				if( (($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] > POWER_LEVEL) || $_SESSION['user_id'] == 103) ){
					
			?>
            <select name="status" id="status" style="background-color:#FAD090">
              <?php
					$sql2 = "SELECT * FROM `status`";
					$rs2 = mysql_query($sql2);
					while($row2 = mysql_fetch_array($rs2)){
						if($row['status'] == $row2['id']){
							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['status'].'</option>';
						}
						else{
							echo '<option value="'.$row2['id'].'">'.$row2['status'].'</option>';	
						}
					}
					
				?>
            </select>
            <?php } 
			else{?>
            <input type="hidden" name="status" id="status" value="<?php echo $row['status'];?>"/>
            <input name="statusd" style="background-color:#FAD090" readonly="readonly" value="<?php
				$sql2 = "SELECT * FROM status WHERE id = '$row[status]'";
				$rs2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($rs2);
            	echo $row2['status'];
			?>"/>
            <?php }?></td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">PO Number:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="po_number" size="20" value="<?php echo $row['po_number'];?>"/></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Call Entered By:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php
            	$uid = $row['user_id'];
				$sql2 = "SELECT * FROM users WHERE id='$uid'";
				$rs2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($rs2);
				echo $row2['full_name'];
			?></td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Bill To:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="bill_to" style="background-color:#FAD090;width:125px">
            <option <?php if($row['bill_to']==0){echo 'selected="selected"';}?> value="0">Owner</option>
            <?php
					$sql2 = "SELECT * FROM insurance_company WHERE id != 1 ORDER BY name asc";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						if($row['bill_to']==$row2['id']){
							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';
						}
						else{
							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';	
						}
					}
					
					$sql2 = "SELECT * FROM clients ORDER BY name asc";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						if($row['bill_to']==-1*$row2['id']){
							echo '<option value="-'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';
						}
						else{
							echo '<option value="-'.$row2['id'].'">'.$row2['name'].'</option>';	
						}
					}
			?>
          </select>
            &nbsp;
            <div class="buttonwrapper"><a class="squarebutton" target="_blank" href="print_ind_statement.php?id=<?php echo $id ?>"><span>Statement</span></a></div></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="middle-left-child" width="<?php echo $col1;?>">Closed:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" id="closeddt" name="closeddt" readonly="readonly" value="<?php echo $row['closedt'];?>"/>
            <button id="closeddtbutton"> <img src="anytime/calendar.png" alt="[calendar icon]"/> </button>
            <script>
    $('#closeddtbutton').click(
      function(e) {
        $('#closeddt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
        </script></td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Receipt Rec.:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php
					$user_id = $_SESSION['user_id'];
					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
				?>
            <input  type="checkbox" style="background-color:#FAD090" name="po_received" <?php if($row['po_received']>0){echo 'checked="checked"';}?>/>
            <?php
					}
					else{
				?>
            <input type="hidden" name="po_received" value="<?php echo $row['po_received'];?>"/>
            <input size="5" type="text" style="background-color:#FAD090" readonly="readonly" name="po_received_d" value="<?php 
					if($row['po_received']>0){
						echo 'Yes';	
					}
					else if ($row['po_number'] != 0 && $row['receipt'] != 0){
						echo 'No';	
					}
				?>"/>
            <?php
					}
					if($row['po_received']>0){
						$did = $row['po_received'];
						$sql2 = "SELECT * FROM `users` WHERE `id`='$did'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo 'By: '.$row2['full_name'];
					}
				?></td>
          <?php } ?>
        </tr>
        <tr>
          <td colspan="2" class="bottom-child">&nbsp;</td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Subrogation:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php 
			if($_SESSION['user_level']==5){?>
            <input type="checkbox" name="sponser" <?php if($row['sponser']==1) {echo 'checked="checked"';}?>/>
            <?php } 
			else{
				if($row['sponser']==1){
					echo 'Yes';	
				}
				else{
					echo 'No';	
				}
			}
			?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="top-child_h" colspan="2" style="color:#148540" align="center">Job Information</td>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Money Rec:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><?php
					$user_id = $_SESSION['user_id'];
					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
				?>
            <input  type="checkbox" style="background-color:#FAD090" name="money" id="money" <?php if($row['money_delivered']>0){echo 'checked="checked"';} ?> />
            &nbsp;
            <select name="paymentType" id="paymentType" style="background-color:#FAD090;" >
              <option value="Cash" <?php if(strcmp($row['paymentType'],'Cash')==0){echo 'selected="selected"';}?>>Cash</option>
              <option value="Check" <?php if(strcmp($row['paymentType'],'Check')==0){echo 'selected="selected"';}?>>Check</option>
              <option value="Bank Transfer" <?php if(strcmp($row['paymentType'],'Bank Transfer')==0){echo 'selected="selected"';}?>>Bank Transfer</option>
              <option value="Office" <?php if(strcmp($row['paymentType'],'Office')==0){echo 'selected="selected"';}?>>Office</option>
              <option value="Agreement" <?php if(strcmp($row['paymentType'],'Agreement')==0){echo 'selected="selected"';}?>>Agreement</option>
            </select>
            <?php
					}
					else{
				?>
            <input type="hidden" name="money" value="<?php echo $row['money_delivered'];?>"/>
            <input size="5" type="text" style="background-color:#FAD090" readonly="readonly" name="moneyd" value="<?php 
					if($row['money_delivered']>0){
						if($row['paymentType']){
							echo $row['paymentType'];	
						}
						else{
							echo 'Yes';	
						}
					}
					else if ($row['charged'] != 0){
						echo 'No';	
					}
				?>"/>
            <?php
					}
					if($row['money_delivered']>0){
						$did = $row['money_delivered'];
						$sql2 = "SELECT * FROM `users` WHERE `id`='$did'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo 'By: '.$row2['full_name'];
					}
				?></td>
          <?php } ?>
        </tr>
        <tr>
          <?php 
				if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 ){
			?>
          <td class="middle-left-child" colspan="1">Accident Info:</td>
          <td class="middle-right-child" colspan="1"><div class="buttonwrapper"> <a class="squarebutton" target="_blank" href="accident_extra.php?sc=<?php echo $id?>"><span>Extra Information</span></a> </div></td>
          <?php } 
			else if($row['job']==2){ //flat Tires
			?>
          <td class="middle-left-child" colspan="1">Spare Tire Available:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="spare" style="background-color:#FAD090">
            <option value="1" <?php if($row['spare']==1){echo 'selected="selected"';}?>>Yes</option>
            <option value="0" <?php if($row['tools']==0){echo 'selected="selected"';}?>>No</option>
          </select></td>
          <?php }
			 else{
			?>
          <td class="middle-left-child" colspan="1">&nbsp;</td>
          <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
          <?php 
			 }
			 if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Voucher:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($row['voucher']){echo $st;}?> type="text" style="background-color:#FAD090" name="voucher" size="20" value="<?php echo $row['voucher'];?>"/></td>
          <?php } ?>
        </tr>
        <tr>
          <?php if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15){ ?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Claim Number:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="claimNo" size="20" value="<?php echo $row['claimNo'];?>"/></td>
          <?php } 
			else if($row['job']==2){ //flat Tires
			?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Tools:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="tools" style="background-color:#FAD090">
            <option value="1" <?php if($row['tools']==1){echo 'selected="selected"';}?>>Yes</option>
            <option value="0" <?php if($row['tools']==0){echo 'selected="selected"';}?>>No</option>
          </select></td>
          <?php	
			}
			else{?>
          <td class="middle-child" colspan="2">&nbsp;</td>
          <?php } ?>
          <?php if ($_SESSION['user_level']>1){?>
          <td class="middle-left-child" width="<?php echo $col1;?>">Voucher Amount:</td>
          <td class="middle-right-child" width="<?php echo $col2;?>"><select name="voucher_amount" style="background-color:#FAD090">
            <option value="0" <?php if(!$row['voucher_amount']){echo 'selected="selected"';}?>>0.00</option>
            <option value="35" <?php if($row['voucher_amount']==35){echo 'selected="selected"';}?>>35.00</option>
            <option value="50" <?php if($row['voucher_amount']==50){echo 'selected="selected"';}?>>50.00</option>
            <option value="75" <?php if($row['voucher_amount']==75){echo 'selected="selected"';}?>>75.00</option>
          </select></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="bottom-left-child" width="<?php echo $col1;?>"><?php
					if($row['job']==2){ //flat tire
						echo 'No. Flat Tires:';
					}
				?></td>
          <td class="bottom-right-child" width="<?php echo $col2;?>"><?php
					if($row['job']==2){ //flat tire
				?>
            <select name="no_flat" style="background-color:#FAD090">
              <option value="1" <?php if($row['no_flat']==1){echo 'selected="selected"';}?>>1</option>
              <option value="2" <?php if($row['no_flat']==2){echo 'selected="selected"';}?>>2</option>
              <option value="3" <?php if($row['no_flat']==3){echo 'selected="selected"';}?>>3</option>
              <option value="4" <?php if($row['no_flat']==4){echo 'selected="selected"';}?>>4</option>
            </select>
            <?php	
                    }
				?></td>
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
        <tr>
          <td class="middle-child" colspan="4"><span style="color:red;font-weight:bold">
            <?php 
						echo $row3['comment'];?>
            </span>
            <?php if(checkAdmin()){ ?>
            <a style="color:#DCB272" href="delete_veh_comment.php?id=<?php echo $row3['id']?>&lic=<?php echo $lic?>">Delete</a> <a style="color:#DCB272" href="new_com.php?lic=<?php echo $lic;?>&id=<?php echo $row3['id']?>&lic=<?php echo $lic?>&sc=<?php echo $id?>">Edit</a>
            <?php } ?></td>
        </tr>
        <?php	}
					}
				?>
        <tr>
          <td class="bottom-child"  colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td class="top-left-child" width="<?php echo $col1;?>">Tow Reason:</td>
          <td class="top-right-child" width="<?php echo $col2;?>" colspan="3"><select name="tow_reason" id="tow_reason" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13 && $row['job'] != 14  && $row['job'] != 16  && $row['job'] != 19  && $row['job'] != 20  && $row['job'] != 22 && $row['job'] != 32 && $row['job'] != 33 && $row['job'] != 36){ echo 'display:none'; }?>">
            <?php
					if($row['job']==32){
						//tow police
							$sql4 = "SELECT * FROM `towing_reason_police` ORDER BY `description`";
					}
					else{$sql4 = "SELECT * FROM `towing_reason` ORDER BY `description`";}
					$rs4 = mysql_query($sql4);
					while($row4 = mysql_fetch_array($rs4)){
				?>
            <option <?php if($row['tow_reason_id']==$row4['id']) {echo 'selected="selected"';} ?> value="<?php echo $row4['id'];?>"><?php echo $row4['description'];?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="bottom-left-child" width="<?php echo $col1;?>">Notes:</td>
          <td class="bottom-right-child" width="<?php echo $col2;?>" colspan="3"><textarea required="required" name="notes" cols="50" rows="4" style="background-color:#FAD090"><?php echo stripcslashes($row['notes']);?></textarea></td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td width="<?php echo $col1;?>">Picture/PDF:</td>
          <td colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" /></td>
          <?php 
			if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
		?>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" cellspacing="0"><table width="100%" cellspacing="0">
            <tr cellspacing="0">
              <td class="left-child" width="14%" valign="top" cellspacing="0"><?php if($_SESSION['user_level'] >= POWER_LEVEL) { ?>
                &nbsp;
                <input type="submit" name="acc_link" value="Couple Accident" style="font-size:14px; font-weight:bold;"/>
                <br/>
                &nbsp;ID:
                <input type="text" name="accident_id" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link']!=0){echo $row['accident_link'];}?>"/>
                <?php }
							else {
							?>
                &nbsp;
                <?php } ?>
                <br/>
                <?php if($row['accident_link']!=0) { ?>
                <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link']?>"><span>Linked Accident</span></a></div>
                <?php } ?></td>
              <td class="middle-full-child" width="14%" valign="top"><input type="submit" name="acc_link2" value="Couple Accident 2" style="font-size:14px; font-weight:bold;"/>
                <br/>
                ID:
                <input type="text" name="accident_id2" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link2']!=0){echo $row['accident_link2'];}?>"/>
                <?php if($row['accident_link2']!=0) { ?>
                <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link2']?>"><span>Linked Accident 2</span></a></div>
                <?php } ?></td>
              <td class="middle-full-child" width="14%" valign="top"><input type="submit" name="acc_link3" value="Couple Accident 3" style="font-size:14px; font-weight:bold;"/>
                <br/>
                ID:
                <input type="text" name="accident_id3" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link3']!=0){echo $row['accident_link3'];}?>"/>
                <?php if($row['accident_link3']!=0) { ?>
                <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link3']?>"><span>Linked Accident 3</span></a></div>
                <?php } ?></td>
              <td class="middle-full-child" valign="top" cellspacing="0"><?php if($row['master_sc']==0 && $_SESSION['user_level'] >= POWER_LEVEL){	?>
                &nbsp;
                <input type="submit" name="tow" value="Tow" style="font-size:14px; font-weight:bold; width:100px;"/>
                &nbsp;
                <?php } 
							else{?>
                &nbsp;
                <?php } ?>
                <?php if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8)) { ?>
                <input type="submit" name="rental" value="Rental" style="font-size:14px; font-weight:bold; width:100px;"/>
                &nbsp;
                <?php }
						else{?>
                &nbsp;
                <?php }
						if($_SESSION['user_level'] >= POWER_LEVEL){?>
                <input type="submit" name="delete" value="Delete" style="font-size:14px; font-weight:bold; width:100px;"/>
                &nbsp;
                <?php } 
							else { echo "&nbsp;";}?>
                <?php if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
						?>
                <input type="submit" name="submit" value="Submit" style="font-size:14px; font-weight:bold; width:100px;"/>
                <?php } 
						else { echo "&nbsp;";}?></td>
              <td class="right-child" width="14%" valign="top"><div class="buttonwrapper"><a class="squarebutton" style="font-size:14px; font-weight:bold" href="edit_sc.php?sc=<?php echo $id;?>&message=1"><span>Send Message</span></a> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="5"><table width="100%" cellspacing="0">
            <?php
			}
		?>
            <?php
			if($_SESSION['user_level'] < POWER_LEVEL){
		?>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5"><table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="left-child" width="14%" cellspacing="0"><input type="submit" name="acc_link" value="Couple Accident" style="font-size:14px; font-weight:bold;"/>
                    <br/>
                    ID:
                    <input type="text" name="accident_id" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link']!=0){echo $row['accident_link'];}?>"/>
                    <?php if($row['accident_link']!=0) { ?>
                    <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link']?>"><span>Linked Accident</span></a></div>
                    <?php } ?></td>
                  <td class="middle-full-child" width="14%" valign="top"><input type="submit" name="acc_link2" value="Couple Accident 2" style="font-size:14px; font-weight:bold;"/>
                    <br/>
                    ID:
                    <input type="text" name="accident_id2" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link2']!=0){echo $row['accident_link2'];}?>"/>
                    <?php if($row['accident_link2']!=0) { ?>
                    <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link2']?>"><span>Linked Accident 2</span></a></div>
                    <?php } ?></td>
                  <td class="middle-full-child" width="14%" valign="top"><input type="submit" name="acc_link3" value="Couple Accident 3" style="font-size:14px; font-weight:bold;"/>
                    <br/>
                    ID:
                    <input type="text" name="accident_id3" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link3']!=0){echo $row['accident_link3'];}?>"/>
                    <?php if($row['accident_link3']!=0) { ?>
                    <div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link3']?>"><span>Linked Accident 3</span></a></div>
                    <?php } ?></td>
                  <td class="right-child" valign="top" cellspacing="0">&nbsp;
                    <?php if($row['master_sc']==0){ ?>
                    <input type="submit" name="tow" value="Tow" style="font-size:14px; font-weight:bold;"/>
                    <?php } ?>
                    &nbsp;
                    <?php if($row['job']==7 || $row['job']==8) { ?>
                    <input type="submit" name="rental" value="Rental" style="font-size:14px; font-weight:bold;"/>
                    <?php } ?>
                    &nbsp;
                    <?php if($row['status'] !=1){?>
                    <input type="submit" name="submit" value="Submit" style="font-size:14px; font-weight:bold;"/>
                    <?php } ?></td>
                </tr>
              </table></td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td class="top-child_h" colspan="5" style="color:#148540">History</td>
            </tr>
            <tr>
              <td class="middle-child" colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td class="bottom-child" colspan="5"><table width="100%" cellspacing="0">
                <?php
					$sql2 = "SELECT * FROM service_req WHERE id != '$id' AND `a_number`='$lic' AND `delete` = 0 AND `pol` LIKE '$policy' order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";
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
                <?
						while($row = mysql_fetch_array($rs8)){
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
                                
                                ?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></a></td>
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
                  <td width="<?php echo $col3;?>"><?php echo number_format($row['charged'],2);?></td>
                  <td width="<?php echo $col3;?>"><?php
                                    if($row['insured']){
                                        echo 'Yes';	
                                    }
                                    else{
                                        echo 'No';	
                                    }
                                ?></td>
                  <td width="<?php echo $col3;?>"><?php
                                        if (is_dir('rrimage/'.$row['id'])){
                                            echo 'Yes';	
                                        }
                                        else{
                                            echo 'No';	
                                        }
                                    ?></td>
                  <td width="<?php echo $col4;?>"><?php 
                                    $sid = $row['status'];
                                    $sql2 = "SELECT * FROM status WHERE id = '$sid'";
                                    $rs2 = mysql_query($sql2);
                                    $row2 = mysql_fetch_array($rs2);
                                    echo $row2['status'];
                                ?></td>
                </tr>
                <?php	} //End While Loop
					} //End if Loop
				?>
              </table></td>
            </tr>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td class="child" colspan="5"><table width="100%" cellspacing="0">
                <?php
			$dirname = "rrimage/".$id;
			$thumbs = "rrthumbs/".$id;
			$docs = "rrdocs/".$id;
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
				echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a>';
				if($_SESSION['user_level'] == ADMIN_LEVEL){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a>';	
				}
				echo '</td></tr>';
				}
			}
			if(is_dir('rrdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=rrdocs/'.$id.'">Download All Document(s)</a></td></tr>';
			}
		?>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
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
					
					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
					}
					else{
						echo '<td width="25%"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4"><?php

			if (is_dir('rrimage/'.$id) && $_SESSION['user_level'] >= POWER_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';
			}
			?></td>
                </tr>
              </table>
                <?php if($row['master_sc']){ ?></td>
            </tr>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td class="child" colspan="5"><table width="100%" cellspacing="0">
                <?php
			$id = $row['master_sc'];
			$dirname = "rrimage/".$id;
			$thumbs = "rrthumbs/".$id;
			$docs = "rrdocs/".$id;
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
				echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a>';
				if($_SESSION['user_level'] == ADMIN_LEVEL){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a>';	
				}
				echo '</td></tr>';
				}
			}
			if(is_dir('rrdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=rrdocs/'.$id.'">Download All Document(s)</a></td></tr>';
			}
		?>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
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
					
					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
					}
					else{
						echo '<td width="25%"><img width="200" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4"><?php

			if (is_dir('rrimage/'.$id) && $_SESSION['user_level'] >= POWER_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';
			}
			?></td>
                </tr>
              </table>
                <?php } //end if master_sc?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      <table width="100%" cellspacing="0">
        <tr></tr>
      </table></td></tr>
  </table>
</form>
<script type="text/javascript" src="js/functions.js">
</script>
<?php if($_SESSION['user_level'] < POWER_LEVEL){?>
<script  type="text/javascript">
 var frmvalidator = new Validator("edit_sc");
 <?php if($row['job'] == 7 && !is_dir('rrimage/'.$id)){ ?>
 	frmvalidator.addValidation("image_upload_box","req","Accident, you are required to upload at least one image","VWZ_IsChecked(document.forms['edit_sc'].elements['present'],'Other')");
  <?php } ?>
</script>
<?php } ?>

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
		if( document.getElementById('money').checked){
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
		if(document.getElementById('licenseNo').value.length != 0){
			window.open('ins_drivers_license.php?license='+licensenumber+'&pol='+pol);	
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
		caddress = "<?php echo $caddress;?>"	
		if(document.getElementById('c_add').checked){
			document.getElementById('loc').value = caddress;	
		}
		else{
			document.getElementById('loc').value = "";	
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