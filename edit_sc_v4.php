<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();


echo menu();
$id = $_REQUEST['sc'];

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

?>


<form name="edit_sc" enctype="multipart/form-data" action="rec_sc.php?sc=<?php echo $id;?>" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h3></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <?php if($row['master_sc']!=0){
		?>
        <tr><td colspan="5">Service request came from#<a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['master_sc'];?>"/><?php echo str_pad($row['master_sc'],5,'0',STR_PAD_LEFT);?></td></tr>
        <?php
		}
		?>
        <tr>
        	<td colspan="4">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?>
            <td rowspan="15" valign="top">
            <table width="100%">
            	<tr><td colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td></tr>
            	<tr><td colspan="2">&nbsp;</td></tr>
                <?php	
				$lic = $row['a_number'];
				$policy = $row['pol'];
				//$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				$rs2 = mysql_query($sql2);
				if(mysql_num_rows($rs2)!=0){
					$vcol1 = 45;
					$vcol2 = 55;
					$row2 = mysql_fetch_array($rs2);
				?>
                	<tr>
                    	<td width="<?php echo $vcol1;?>%">Full Name:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['Full_Name'];?></td>
                    </tr>
                     <tr>
                    	<td width="<?php echo $vcol1;?>%">Client No:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['ClientNo'];?></td>
                    </tr>
                    <tr>
                    	<td width="<?php echo $vcol1;?>%">Address:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['Address1'];?></td>
                    </tr>
                    <tr>
                    	<td width="<?php echo $vcol1;?>%">Insurance Status:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['VehStatus'];?></td>
                    </tr>
                    <tr>
                    	<td width="<?php echo $vcol1;?>%">Car Insured Since:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['Date_Application'];?></td>
                    </tr>
                    <tr>
                    	<td width="<?php echo $vcol1;?>%">Renewal Date:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['VehDate_Renewal'];?></td>
                    </tr>
                     <tr>
                    	<td width="<?php echo $vcol1;?>%">Year / Color:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['YearMake'].' / '.$row2['Color'];?></td>
                    </tr>
                     <tr>
                    	<td width="<?php echo $vcol1;?>%">Vehicle Coverage/Use:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['VehCoverage'].'/'.$row2['VehUse'];?></td>
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
					?>
                    	<tr>
                    		<td width="<?php echo $vcol1;?>%">Phone Number(s):</td>
                        	<td width="<?php echo $vcol2;?>%"><?php echo $phone;?></td>
                    	</tr>
                        <tr>
                    		<td width="<?php echo $vcol1;?>%">Agent/Broker:</td>
                        	<td width="<?php echo $vcol2;?>%"><?php echo $row2['AgentName'];?></td>
                    	</tr>
                        <tr>
                    		<td width="<?php echo $vcol1;?>%">Total Premium:</td>
                        	<td width="<?php echo $vcol2;?>%"><?php //echo number_format(($row2['Premium']+(float)$row2['PolicyFee']),2);?></td>
                    	</tr>
                         <tr>
                    		<td width="<?php echo $vcol1;?>%">Blacklisted:</td>
                        	<td width="<?php echo $vcol2;?>%"><?php 
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
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" style="color:#148540" align="center">Vehicle Comments</td></tr>
                <?php
					$lic =  $row['a_number'];
					$sql3 = "SELECT * FROM vehicle_com WHERE license='$lic'";
					$rs3 = mysql_query($sql3);
					if(mysql_num_rows($rs3)!=0){
						while($row3 = mysql_fetch_array($rs3)){
				?>
                	<tr><td colspan="2"><?php echo $row3['comment'];?></td></tr>
                <?php	}
					}
				?>
                
            </table>
            </td>
        </tr>
        <tr>
        	<?php
				if($_SESSION['user_level'] >= POWER_LEVEL){
			?>
            	<td colspan="4">Time Requested: 
                <input type="text" id="opendt" name="opendt" readonly="readonly" value="<?php echo $row['opendt'];?>"/>
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
            <?php
				}
				else{
			?>
        	<td colspan="4">Time Requested: <?php echo $row['opendt'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            	<input type="hidden" name="opendt" value="<?php echo $row['opendt'];?>" />
            <?php } ?>
            <?php }?>
        </tr>
        <?php
			if($row['status']==2){ //Closed
		?>
         <tr>
        	<td colspan="4">Time Closed: <?php echo $row['closedt'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            	<input type="hidden" name="closeddt" value="<?php echo $row['closedt'];?>" />
            <?php } ?>
        </tr>	
        <?php
			}
			else if($row['status']==3){ //Cancelled
		?>
          <tr>
        	<td colspan="4">Time Cancelled: <?php echo $row['closedt'];?>
        </tr>	
        <?php
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr>
        	<td colspan="2" style="color:#148540" align="center">Call Information</td>
            <td colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Car:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="car" size="25" value="<?php echo $row['car'];?>" /></td>
            <td width="<?php echo $col1;?>">Car Number:</td>
            <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="num" size="15" value="<?php echo $row['a_number'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Location:</td>
            <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" size="25" value="<?php echo $row['location'];?>"/></td>
        	<td width="<?php echo $col1;?>">Vin:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="vin" size="25" value="<?php echo $row['vin'];?>" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Location to:</td>
            <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13){ echo 'display:none'; }?>" name="toloc" id="loc" size="25" value="<?php echo $row['to_location'];?>"/></td>
            <td width="<?php echo $col1;?>">Job:</td>
            <td width="<?php echo $col2;?>">
            <?php if($_SESSION['user_level'] >= POWER_LEVEL){
			
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
			?>
            </td>
        </tr>
         <tr>
            <td width="<?php echo $col1;?>">Attendee:</td>
            <td width="<?php echo $col2;?>">
            <?php if ($_SESSION['user_level'] >= POWER_LEVEL){
			?>
            <select name="attendee" style="background-color:#FAD090">
                    <?php
						$aid = $row['attendee_id'];
						$sql2 = "SELECT * FROM attendee WHERE id != 10 order by s_name ";
						$rs2 = mysql_query($sql2);
						while($row2=mysql_fetch_array($rs2)){
							if($aid == $row2['id']){
								echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['s_name'].'</option>';
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
			?>
            </td>
           	<td width="<?php echo $col1;?>">Insured:</td>
            <td width="<?php echo $col2;?>"><?php 
				if($_SESSION['user_level'] >= POWER_LEVEL){
			?>
            <input  type="checkbox" style="background-color:#FAD090" name="insured" <?php if($row['insured']){echo 'checked="checked"';}?>/>
            <?php }
				else{
			?>
            	<input type="hidden" name="insured" value="<?php echo $row['insured'];?>"/>
                <input type="text" style="background-color:#FAD090" readonly="readonly" name="insuredd" value="<?php 
					if($row['insured']){
						echo 'Yes';	
					}
					else{
						echo 'No';	
					}
				?>"/>
            <?php
				}
			?>
            </td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Charged:</td>
             <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" value="<?php echo number_format($row['charged'],2);?>" style="background-color:#FAD090; text-align:right" name="charged" size="10"/> 
             Afl.&nbsp;
             <?php
					$user_id = $_SESSION['user_id'];
					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
				?>
                	<a style="color:#DCB272" href="change_charge.php?sc=<?php echo $id?>">Change</a>
                <?php
					}
				?></td>
            <td width="<?php echo $col1;?>">Policy Number:</td>
             <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="pol" size="20" value="<?php echo $row['pol'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Receipt:</td>
             <td width="<?php echo $col2;?>"><input  <?php if($row['receipt']) {echo $st;}?> type="text" value="<?php echo $row['receipt'];?>" style="background-color:#FAD090;" name="receipt" size="20"/></td>
            <td width="<?php echo $col1;?>">Voucher:</td>
             <td width="<?php echo $col2;?>"><input  <?php if($row['voucher']){echo $st;}?> type="text" style="background-color:#FAD090" name="voucher" size="20" value="<?php echo $row['voucher'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Money Received:</td>
            <td width="<?php echo $col2;?>">
            	<?php
					$user_id = $_SESSION['user_id'];
					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){
				?>
                	<input  type="checkbox" style="background-color:#FAD090" name="money" <?php if($row['money_delivered']>0){echo 'checked="checked"';}?>/>
                <?php
					}
					else{
				?>
              		<input type="hidden" name="money" value="<?php echo $row['money_delivered'];?>"/>
                <input size="10" type="text" style="background-color:#FAD090" readonly="readonly" name="moneyd" value="<?php 
					if($row['money_delivered']>0){
						echo 'Yes';	
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
				?>
            </td>
             <td width="<?php echo $col1;?>">Voucher Amount:</td>
             <td width="<?php echo $col2;?>"><input  <?php if($row['voucher_amount']){echo $st;}?> type="text" style="background-color:#FAD090" name="voucher_amount" size="20" value="<?php echo number_format($row['voucher_amount'],2);?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Add. Phone:</td>
       		<td width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['AddPhone'];?>" style="background-color:#FAD090;" name="addphone" size="18"/>
            <td width="<?php echo $col1;?>">District:</td>
             <td width="<?php echo $col2;?>"><select name="district" style="background-color:#FAD090">
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
                    </select> </td>
        </tr>
        <tr>
       		<td width="<?php echo $col1;?>">Notes:</td>
       		<td width="<?php echo $col2;?>" colspan="3"><textarea name="notes" cols="50" rows="4" style="background-color:#FAD090"><?php echo $row['notes'];?></textarea></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Status:</td>
        	<td width="<?php echo $col2;?>"><?php 
				if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
			?><select name="status" id="status" style="background-color:#FAD090">
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
            <?php }?>
            </td>
            
            <?php 
			if($_SESSION['user_level'] < POWER_LEVEL && $row['status'] !=1){
			?>
        	<td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <?php } 
				else{
			?>
            	<td colspan="2">&nbsp;</td>
            <?php
				}
			?>
        </tr>
        
        	<?php
            	if($row['job']==7){ //Accidend check if present
			?>
            	<tr>
            		<td colspan="2">Vehicle Present:&nbsp;<input  type="checkbox" id="present"  style="background-color:#FAD090" name="present" <?php if($row['present']){echo 'checked="checked"';}?>/>
            		</td>
            	</tr>
            <?php } ?>

        <tr>
        	<td width="<?php echo $col1;?>">Picture/PDF:</td>
         	<td colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" /></td>
         </td>
        <?php 
			if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
		?>
        	 <tr>
        	<td width="<?php echo $col1;?>">Closed:</td>
        	<td width="<?php echo $col2;?>">
            	<input type="text" id="closeddt" name="closeddt" readonly="readonly" value="<?php echo $row['closedt'];?>"/>
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
            </td>
           <?php if(1){ ?>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
            <?php } ?>
          
        </tr>
        <?php
			if($row['master_sc']==0 && $_SESSION['user_level'] >= POWER_LEVEL){
		?>
        <tr><td colspan="4" align="right"><input type="submit" name="tow" value="Tow"/></td><td>&nbsp;</td></tr>  
        <?php
			}
		?>
        </tr>
        <?php if($_SESSION['user_level'] >= POWER_LEVEL) { ?>
        <tr><td colspan="4" align="right"><input type="submit" name="delete" value="Delete"/></td><td>&nbsp;</td></tr>
        <?php } ?>
        <tr><td colspan="5">
        	<table width="100%">
        <?php
			}
		?>
         <?php
			if($row['master_sc']==0 && $_SESSION['user_level'] < POWER_LEVEL){
		?>
        <tr><td colspan="4" align="right"><input type="submit" name="tow" value="Tow"/></td><td>&nbsp;</td></tr>  
        <?php
			}
		?>
        <tr><td colspan="5">&nbsp;</td></tr> 
        <tr><td colspan="5" style="color:#148540">History</td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">
        	<table width="100%">
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
        <tr><td colspan="4">&nbsp;</td></tr> 
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
				echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.$doc.'">'.$doc.'</a>';
				if($_SESSION['user_level'] == ADMIN_LEVEL){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.$doc.'&sc='.$id.'">Delete</a>';	
				}
				echo '</td></tr>';
				}
			}
			if(is_dir('rrdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=rrdocs/'.$id.'">Download All Document(s)</a></td></tr>';
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
					if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else{
						echo '<td width="25%"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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
					echo '<td width="25%">&nbsp;</td>';
					$r++;	
				}
				echo '</tr>';	
			}
		?>
        	</table>
        </td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4"><?php

			if (is_dir('rrimage/'.$id) && $_SESSION['user_level'] >= POWER_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';
			}
		?></td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>
    </table>
</form>
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
		txt = obj.options[obj.selectedIndex].value;
		if ( txt.match('3') || txt.match('12') || txt.match('13')  ) {
			document.getElementById('loc').style.display = 'block';
		}
		else{
			document.getElementById('loc').style.display = 'none';
		}
		
	}

</script>
</body>
</html>